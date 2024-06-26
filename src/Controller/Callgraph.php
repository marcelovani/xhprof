<?php
/**
 * @file Calgraph utils.
 */

namespace Xhprof\Controller;

use Xhprof\Config\ConfigLoader;
use Xhprof\XhprofLib;

/**
 * @class Callgraph.
 */
class Callgraph
{

    private $config;

    /**
     * @var XhprofLib;
     */
    private $xhprof_lib;

    public function __construct()
    {
        $this->xhprof_lib = new XhprofLib();
    }

    // Supported output format
    private $xhprof_legal_image_types = array(
        "jpg" => 1,
        "gif" => 1,
        "png" => 1,
        "ps" => 1,
        "svg" => 1,
    );

    public function getLegalImageTypes()
    {
        return $this->xhprof_legal_image_types;
    }

    /**
     * Send an HTTP header with the response. You MUST use this function instead
     * of header() so that we can debug header issues because they're virtually
     * impossible to debug otherwise. If you try to commit header(), SVN will
     * reject your commit.
     *
     * @param string  HTTP header name, like 'Location'
     * @param string  HTTP header value, like 'http://www.example.com/'
     *
     */
    public function setHttpHeader($name, $value) {

        if (!$name) {
            xhprof_error('http_header usage');
            return null;
        }

        if (!is_string($value)) {
            xhprof_error('http_header value not a string');
        }

        header($name.': '.$value, true);
    }

    /**
     * Genearte and send MIME header for the output image to client browser.
     *
     * @author cjiang
     */
    public function GenerateMimeHeader($type, $length) {
        switch ($type) {
            case 'jpg':
                $mime = 'image/jpeg';
                break;
            case 'gif':
                $mime = 'image/gif';
                break;
            case 'png':
                $mime = 'image/png';
                break;
            case 'ps':
                $mime = 'application/postscript';
            default:
                $mime = false;
        }

        if ($mime) {
            xhprof_http_header('Content-type', $mime);
            xhprof_http_header('Content-length', (string)$length);
        }
    }

    /*
     * Get the children list of all nodes.
     */
    public function getChildrenTable($raw_data) {
        $children_table = array();
        foreach ($raw_data as $parent_child => $info) {
            list($parent, $child) = $this->xhprof_lib->parseParentChild($parent_child);
            if (!isset($children_table[$parent])) {
                $children_table[$parent] = array($child);
            } else {
                $children_table[$parent][] = $child;
            }
        }
        return $children_table;
    }

    /**
     * Generate DOT script from the given raw phprof data.
     *
     * @param raw_data, phprof profile data.
     * @param threshold, float, the threshold value [0,1). The functions in the
     *                   raw_data whose exclusive wall times ratio are below the
     *                   threshold will be filtered out and won't apprear in the
     *                   generated image.
     * @param page, string(optional), the root node name. This can be used to
     *              replace the 'main()' as the root node.
     * @param func, string, the focus function.
     * @param critical_path, bool, whether or not to display critical path with
     *                             bold lines.
     * @returns, string, the DOT script to generate image.
     *
     * @author cjiang
     */
    public function generateDotScript($raw_data, $threshold, $source, $page,
                                        $func, $critical_path, $right=null,
                                        $left=null, $show_internal=false, $links=false
    ) {

        $max_width = 5;
        $max_height = 3.5;
        $max_fontsize = 35;
        $max_sizing_ratio = 20;
        $totals = 0;

        if ($left === null) {
            // init_metrics($raw_data, null, null);
        }

        if ($func) {
            // Filter list by function name.
            $interested_funcs = $this->filterOutFunctions(array_keys($raw_data), $func);
            foreach ($raw_data as $symbol => $info) {
                if (!array_key_exists($symbol, $interested_funcs)) {
                    unset($raw_data[$symbol]);
                }
            }
            // Now that the functions are filtered we don't need any further conditions.
            // @todo update the actual conditions to stop using this variable.
            $func='';
        }

        $sym_table = $this->xhprof_lib->computeFlatInfo($raw_data, $totals);

        // Show internal php functions if the button is selected (default 1).
        if (!$show_internal) {
            $all_functions = get_defined_functions();
            $internal = $all_functions['internal'];
            foreach ($sym_table as $symbol => $info) {
                if (in_array($symbol, $internal)) {
                    unset($sym_table[$symbol]);
                }
            }
        }

        // if it is a benchmark callgraph, we make the benchmarked function the root.
        if ($source == "bm" && array_key_exists("main()", $sym_table)) {
            $total_times = $sym_table["main()"]["ct"];
            $remove_funcs = array("main()",
                "hotprofiler_disable",
                "call_user_func_array",
                "xhprof_disable");

            foreach ($remove_funcs as $cur_del_func) {
                if (array_key_exists($cur_del_func, $sym_table) &&
                    $sym_table[$cur_del_func]["ct"] == $total_times) {
                    unset($sym_table[$cur_del_func]);
                }
            }
        }

        // Filter out functions whose exclusive time ratio is below threshold, and
        // also assign a unique integer id for each function to be generated. In the
        // meantime, find the function with the most exclusive time (potentially the
        // performance bottleneck).
        $cur_id = 0; $max_wt = 0;
        foreach ($sym_table as $symbol => $info) {
            if (empty($func) && abs($info["wt"] / $totals["wt"]) < $threshold) {
                unset($sym_table[$symbol]);
                continue;
            }
            if ($max_wt == 0 || $max_wt < abs($info["excl_wt"])) {
                $max_wt = abs($info["excl_wt"]);
            }
            $sym_table[$symbol]["id"] = $cur_id;
            $cur_id ++;
        }

        if ($critical_path) {
            $children_table = $this->getChildrenTable($raw_data);
            array_pop($sym_table);
            $node = array_key_last($sym_table);
            $path = array();
            $path_edges = array();
            $visited = array();
            while ($node) {
                $visited[$node] = true;
                if (isset($children_table[$node])) {
                    $max_child = null;
                    foreach ($children_table[$node] as $child) {

                        if (isset($visited[$child])) {
                            continue;
                        }
                        if ($max_child === null ||
                            abs($raw_data[$this->xhprof_lib->buildParentChildKey($node, $child)]["wt"]) >
                            abs($raw_data[$this->xhprof_lib->buildParentChildKey($node, $max_child)]["wt"])) {
                            $max_child = $child;
                        }
                    }
                    if ($max_child !== null) {
                        $path[$max_child] = true;
                        $path_edges[$this->xhprof_lib->buildParentChildKey($node, $max_child)] = true;
                    }
                    $node = $max_child;
                } else {
                    $node = null;
                }
            }
        }

        $result = "digraph call_graph {\n";
        $result .= 'graph [label="" style="filled" fontstyle="bold" fontname="Arial" ssize="30,60" fontsize="40" ];' . PHP_EOL;
        $result .= 'node [shape="box" style="filled" fontname="Arial" fontsize="11" caption="function" ];' . PHP_EOL;

        // Generate all nodes' information.
        foreach ($sym_table as $symbol => $info) {
            if ($info["excl_wt"] == 0) {
                $sizing_factor = $max_sizing_ratio;
            } else {
                $sizing_factor = $max_wt / abs($info["excl_wt"]) ;
                if ($sizing_factor > $max_sizing_ratio) {
                    $sizing_factor = $max_sizing_ratio;
                }
            }
            $fillcolor = (($sizing_factor < 1.5) ?
                ", style=filled, fillcolor=red" : "");

            if ($critical_path) {
                // highlight nodes along critical path.
                if (!$fillcolor && array_key_exists($symbol, $path)) {
                    $fillcolor = ", style=filled, fillcolor=yellow";
                }
            }

            $fontsize =", fontsize="
                .(int)($max_fontsize / (($sizing_factor - 1) / 10 + 1));

            $width = ", width=".sprintf("%.1f", $max_width / $sizing_factor);
            $height = ", height=".sprintf("%.1f", $max_height / $sizing_factor);

            if ($links) {
                $endpoint = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
                $parts = (isset($_SERVER['HTTP_REFERER'])) ? parse_url($_SERVER['HTTP_REFERER']) : ['path' => '/'];
                $link = ', URL="' .  $parts['path'] . '?url=' . $endpoint
                    . '%3Frun=' . $_GET['run']
                    . '%26links=' . $links
                    . '%26show_internal=' . $show_internal
                    . '%26func=' . urlencode($symbol) . '" ';
            }
            else {
                $link = '';
            }

            if ($symbol == "main()") {
                $name ="Total: ".($totals["wt"]/1000.0)." ms\\n";
                $name .= addslashes(isset($page) ? $page : $symbol);
            } else {
                $name = addslashes($symbol)."\\nInc: ". sprintf("%.3f",$info["wt"]/1000) .
                    " ms (" . sprintf("%.1f%%", 100 * $info["wt"]/$totals["wt"]).")";
            }

            if ($left === null) {
                $label = "label=\"".$name."\\nExcl: "
                    .(sprintf("%.3f",$info["excl_wt"]/1000.0))." ms ("
                    .sprintf("%.1f%%", 100 * $info["excl_wt"]/$totals["wt"])
                    . ")\\n".$info["ct"]." total calls\"";
            } else {
                if (isset($left[$symbol]) && isset($right[$symbol])) {
                    $label = "label=\"".addslashes($symbol).
                        "\\nInc: ".(sprintf("%.3f",$left[$symbol]["wt"]/1000.0))
                        ." ms - "
                        .(sprintf("%.3f",$right[$symbol]["wt"]/1000.0))." ms = "
                        .(sprintf("%.3f",$info["wt"]/1000.0))." ms".
                        "\\nExcl: "
                        .(sprintf("%.3f",$left[$symbol]["excl_wt"]/1000.0))
                        ." ms - ".(sprintf("%.3f",$right[$symbol]["excl_wt"]/1000.0))
                        ." ms = ".(sprintf("%.3f",$info["excl_wt"]/1000.0))." ms".
                        "\\nCalls: ".(sprintf("%.3f",$left[$symbol]["ct"]))." - "
                        .(sprintf("%.3f",$right[$symbol]["ct"]))." = "
                        .(sprintf("%.3f",$info["ct"]))."\"";
                } else if (isset($left[$symbol])) {
                    $label = "label=\"".addslashes($symbol).
                        "\\nInc: ".(sprintf("%.3f",$left[$symbol]["wt"]/1000.0))
                        ." ms - 0 ms = ".(sprintf("%.3f",$info["wt"]/1000.0))
                        ." ms"."\\nExcl: "
                        .(sprintf("%.3f",$left[$symbol]["excl_wt"]/1000.0))
                        ." ms - 0 ms = "
                        .(sprintf("%.3f",$info["excl_wt"]/1000.0))." ms".
                        "\\nCalls: ".(sprintf("%.3f",$left[$symbol]["ct"]))." - 0 = "
                        .(sprintf("%.3f",$info["ct"]))."\"";
                } else {
                    $label = "label=\"".addslashes($symbol).
                        "\\nInc: 0 ms - "
                        .(sprintf("%.3f",$right[$symbol]["wt"]/1000.0))
                        ." ms = ".(sprintf("%.3f",$info["wt"]/1000.0))." ms".
                        "\\nExcl: 0 ms - "
                        .(sprintf("%.3f",$right[$symbol]["excl_wt"]/1000.0))
                        ." ms = ".(sprintf("%.3f",$info["excl_wt"]/1000.0))." ms".
                        "\\nCalls: 0 - ".(sprintf("%.3f",$right[$symbol]["ct"]))
                        ." = ".(sprintf("%.3f",$info["ct"]))."\"";
                }
            }

            $result .= "N" . $sym_table[$symbol]["id"];
            $result .= "[".$label.$width.$link
                .$height.$fontsize.$fillcolor."];\n";
        }

        foreach ($raw_data as $parent_child => $info) {
            list($parent, $child) = $this->xhprof_lib->parseParentChild($parent_child);

            if (isset($sym_table[$parent]) && isset($sym_table[$child]) &&
                (empty($func) ||
                    (!empty($func) && ($parent == $func || $child == $func)) )) {

                $label = $info["ct"] == 1 ? $info["ct"]." call" : $info["ct"]." calls";

                $headlabel = $sym_table[$child]["wt"] > 0 ?
                    sprintf("%.1f%%", 100 * $info["wt"]
                        / $sym_table[$child]["wt"])
                    : "0.0%";

                $swt = $sym_table[$parent]["wt"];
                $sewt = $sym_table["$parent"]["excl_wt"];
                $diff = $swt - $sewt;
                $taillabel = ($sym_table[$parent]["wt"] > 0 && $diff > 0) ?
                    sprintf("%.1f%%", 100 * $info["wt"] / $diff)
                    : "0.0%";

                $linewidth= 1;
                $arrow_size = 1;

                if ($critical_path &&
                    isset($path_edges[$this->xhprof_lib->buildParentChildKey($parent, $child)])) {
                    $linewidth = 10; $arrow_size=2;
                }

                $result .= "N" . $sym_table[$parent]["id"] . " -> N"
                    . $sym_table[$child]["id"];
                $result .= "[arrowsize=$arrow_size, style=\"setlinewidth($linewidth)\","
                    ." label=\""
                    .$label."\", headlabel=\"".$headlabel
                    ."\", taillabel=\"".$taillabel."\" ]";
                $result .= ";\n";

            }
        }

        $result = $result . "\n}";

        return $result;
    }


    function  xhprof_render_diff_image($xhprof_runs_impl, $run1, $run2,
                                       $type, $threshold, $source) {
        $total1 = 0;
        $total2 = 0;

        $desc_unused = '';
        list($raw_data1, $a) = $xhprof_runs_impl->get_run($run1, $source, $desc_unused);
        list($raw_data2, $b) = $xhprof_runs_impl->get_run($run2, $source, $desc_unused);

        // init_metrics($raw_data1, null, null);
        $children_table1 = xhprof_get_children_table($raw_data1);
        $children_table2 = xhprof_get_children_table($raw_data2);
        $symbol_tab1 = $this->xhprof_lib->computeFlatInfo($raw_data1, $total1);
        $symbol_tab2 = $this->xhprof_lib->computeFlatInfo($raw_data2, $total2);
        $run_delta = $this->xhprof_lib->computeDiff($raw_data1, $raw_data2);
        $digraph = $this->generateDotScript($run_delta, $threshold, $source,
            null, null, true,
            $symbol_tab1, $symbol_tab2);
        $content = $this->generateDotImage($digraph, $type);

        $this->GenerateMimeHeader($type, strlen($content));

        echo $content;
    }

    /**
     * Generate image from phprof run id and send it to client.
     *
     * @param object  $xhprof_runs_impl  An object that implements
     *                                   the iXHProfRuns interface
     * @param run_id, integer, the unique id for the phprof run, this is the
     *                primary key for phprof database table.
     * @param type, string, one of the supported image types. See also
     *              $xhprof_legal_image_types.
     * @param threshold, float, the threshold value [0,1). The functions in the
     *                   raw_data whose exclusive wall times ratio are below the
     *                   threshold will be filtered out and won't apprear in the
     *                   generated image.
     * @param func, string, the focus function.
     * @param bool, does this run correspond to a PHProfLive run or a dev run?
     * @author cjiang
     */
    public function renderImage($xhprof_runs_impl, $run_id, $type, $threshold,
                                 $func, $source, $critical_path) {

        $content = $this->getContentByRun($xhprof_runs_impl, $run_id, $type,
            $threshold,
            $func, $source, $critical_path);
        if (!$content) {
            print "Error: either we can not find profile data for run_id ".$run_id
                ." or the threshold ".$threshold." is too small or you do not"
                ." have 'dot' image generation utility installed.";
            exit();
        }

        $this->GenerateMimeHeader($type, strlen($content));
        echo $content;
    }


    /**
     * Get a list of all called functions related to the specified function.
     *
     * @param array $data
     *   The raw data.
     * @param string $func
     *   The function name.
     */
    public function filterOutFunctions($data, $func) {
        $childrenMap = [];

        // Create a map of each parent to its direct children
        foreach ($data as $key => $item) {
            list($parent, $child) = $this->xhprof_lib->parseParentChild($item);

            if (!isset($childrenMap[$item])) {
                $childrenMap[$item] = [];
            }
            $childrenMap[$item]['child'] = $child;
            $childrenMap[$item]['parent'] = $parent;
            $childrenMap[$item]['key'] = $key;
        }

        // Recursive function to traverse children
        function include_children($childrenMap, $func, &$interested, &$pointer) {
            foreach ($childrenMap as $key => $item) {
                if ($item['parent'] == $func && !isset($interested[$key])) {
                    $interested[$key] = 1;
                    include_children($childrenMap, $item['child'], $interested, $key);
                }
                else if ($item['child'] == $func && !isset($interested[$key])) {
                    $interested[$key] = 1;
                }
            }
        }

        $interested = [$func => 1, 'main()' => 1];
        $pointer = 0;
        include_children($childrenMap, $func, $interested, $pointer);

        return $interested;
    }

    /**
     * Renders SVG image.
     *
     * @param object $xhprof_runs_impl
     *  An object that implements the iXHProfRuns interface
     * @param string $run_id
     *   the unique id for the phprof run, this is the primary key for phprof database table.
     * @param string $type
     *   One of the supported image types. See also $xhprof_legal_image_types
     * @param float $threshold
     *   The threshold value [0,1). The functions in the raw_data whose exclusive wall times ratio are below the
     *   threshold will be filtered out and won't appear in the generated image
     * @param string $func
     *   The focus function.
     * @param string $source
     *   The source.
     * @param bool $critical_path
     *   Show critical path.
     * @param bool $show_internal
     *   Show internal PHP functions.
     * @param bool $links
     *   Add links to the elements to allow filtering.
     * @return string|void
     */
    public function renderDot($xhprof_runs_impl, $run_id, $type, $threshold,
                               $func, $source, $critical_path, $show_internal=false, $links=false) {

        if (!$run_id)
            return;

        $description = '';
        list($raw_data, $a) = $xhprof_runs_impl->get_run($run_id, $source, $description);

        if (!$raw_data) {
            xhprof_error("Raw data is empty");
            return "";
        }

        $digraph = $this->generateDotScript($raw_data, $threshold, $source, $description, $func,
            $critical_path, null, null, $show_internal, $links);

        return $digraph;
    }

    /**
     * Generate image content from phprof run id.
     *
     * @param object  $xhprof_runs_impl  An object that implements
     *                                   the iXHProfRuns interface
     * @param run_id, integer, the unique id for the phprof run, this is the
     *                primary key for phprof database table.
     * @param type, string, one of the supported image types. See also
     *              $xhprof_legal_image_types.
     * @param threshold, float, the threshold value [0,1). The functions in the
     *                   raw_data whose exclusive wall times ratio are below the
     *                   threshold will be filtered out and won't apprear in the
     *                   generated image.
     * @param func, string, the focus function.
     * @returns, string, the DOT script to generate image.
     *
     * @author cjiang
     */
    public function getContentByRun($xhprof_runs_impl, $run_id, $type,
                                       $threshold, $func, $source,
                                       $critical_path) {
        if (!$run_id)
            return "";

        $description = '';
        list($raw_data, $a) = $xhprof_runs_impl->get_run($run_id, $source, $description);
        if (!$raw_data) {
            xhprof_error("Raw data is empty");
            return "";
        }

        $digraph = $this->generateDotScript($raw_data, $threshold, $source,
            $description, $func, $critical_path);

        $content = $this->generateDotImage($digraph, $type);
        return $content;
    }

    /**
     * Generate image according to DOT script. This function will make the
     * process working on windows boxes (some win-boxes seems to having problems
     * with creating processes via proc_open so we do it the lame win way by
     * creating and writing to temp-files and reading them in again ...
     * not really nice but functional
     *
     * @param dot_script, string, the script for DOT to generate the image.
     * @param type, one of the supported image types, see
     * @param errorFile, string, the file to write errors to
     * @param tmpDirectory, string, the directory for temporary created files
     * @param dotBin, the dot-binary file (e.g. dot.exe)
     * @returns, binary content of the generated image on success.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    public function GenerateImageDotWin($dot_script,
                                                 $type,
                                                 $errorFile,
                                                 $tmpDirectory,
                                                 $dotBin
    ) {
        // assume no error
        $error = false;

        // get unique identifier
        $uid = md5(time());

        // files we handle with
        $files = array(
            'dot'   => $tmpDirectory.'\\'.$uid.'.dot',
            'img' => $tmpDirectory.'\\'.$uid.'.'.$type
        );

        // build command for dot.exe
        $cmd = '"'.$dotBin.'" -T'.$type.' "'.$files['dot'].'" -o "'.$files['img'].'"';

        // 1. write dot script temp
        file_put_contents($files['dot'], $dot_script);

        // 2. call dot-binary with temp dot script and write file (out) type
        shell_exec($cmd);

        // 3. read in the img
        $output = file_get_contents($files['img']);
        if ($output == ''
            || !file_exists($files['img'])
            || filesize($files['img']) == 0
        ) {
            $error = true;
        }

        // 4. delete temp files
        foreach ($files as $type => $file) {
            unlink($file);
        }

        // 5. check for possible error (empty result)
        if ($error) {
            die("Error producing callgraph!");
        }

        // 6. return result
        return $output;
    }

    /**
     * Generate image according to DOT script. This function will spawn a process
     * with "dot" command and pipe the "dot_script" to it and pipe out the
     * generated image content.
     *
     * @param dot_script, string, the script for DOT to generate the image.
     * @param type, one of the supported image types, see
     * $xhprof_legal_image_types.
     * @returns, binary content of the generated image on success. empty string on
     *           failure.
     *
     * @author cjiang
     */
    public function generateDotImage($dot_script, $type) {
        $config_loader = new ConfigLoader();
        $this->config = $config_loader->get();

        $errorFile    = $this->config['dot_errfile'];
        $tmpDirectory = $this->config['dot_tempdir'];
        $dotBinary    = $this->config['dot_binary'];

        // detect windows
        if (stristr(PHP_OS, 'WIN') && !stristr(PHP_OS, 'Darwin')) {
            return $this->GenerateImageDotWin($dot_script,
                $type,
                $errorFile,
                $tmpDirectory,
                $dotBinary);
        }

        // parts of the original source
        $descriptorspec = array(
            // stdin is a pipe that the child will read from
            0 => array("pipe", "r"),
            // stdout is a pipe that the child will write to
            1 => array("pipe", "w"),
            // stderr is a file to write to
            2 => array("file", $errorFile, "a")
        );

        $cmd = ' "'.$dotBinary.'" -T'.$type;

        $process = proc_open($cmd, $descriptorspec, $pipes, $tmpDirectory, array());

        if (is_resource($process)) {
            fwrite($pipes[0], $dot_script);
            fclose($pipes[0]);

            $output = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            proc_close($process);
            if ($output == "" && filesize($errorFile) > 0)
            {
                die("Error producing callgraph, check $errorFile");
            }
            return $output;
        }
        print "failed to shell execute cmd=\"$cmd\"\n";

        $error = error_get_last();
        if (isset($error['message'])) {
            print($error['message'] . "\n");
        }

        exit();
    }

}

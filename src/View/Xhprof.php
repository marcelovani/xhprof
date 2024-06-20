<?php
/**
 * View for Xhprof.
 */

namespace Xhprof\View;

/**
 * View class.
 */
class Xhprof
{
    /**
     * Return attribute names and values to be used by javascript tooltip.
     */
    function getTooltipAttributes($type, $metric)
    {
        return "type='$type' metric='$metric'";
    }

    public function aggregateCalls($calls, $rules = null)
    {
        $rules = array(
            'Loading' => 'load::',
            'mysql' => 'mysql_'
        );

        // For domain-specific configuration, you can use Apache setEnv xhprof_aggregateCalls_include [some_php_file]
        if (isset($run_details['aggregateCalls_include']) && strlen($run_details['aggregateCalls_include']) > 1) {
            require_once($run_details['aggregateCalls_include']);
        }

        $addIns = array();
        foreach ($calls as $index => $call) {
            foreach ($rules as $rule => $search) {
                if (strpos($call['fn'], $search) !== false) {
                    if (isset($addIns[$search])) {
                        unset($call['fn']);
                        foreach ($call as $k => $v) {
                            $addIns[$search][$k] += $v;
                        }
                    } else {
                        $call['fn'] = $rule;
                        $addIns[$search] = $call;
                    }
                    unset($calls[$index]);  //Remove it from the listing
                    break;  //We don't need to run any more rules on this
                } else {
                    //echo "nomatch for $search in {$call['fn']}<br />\n";
                }
            }
        }
        return array_merge($addIns, $calls);
    }

    /*
 * Formats call counts for XHProf reports.
 *
 * Description:
 * Call counts in single-run reports are integer values.
 * However, call counts for aggregated reports can be
 * fractional. This function will print integer values
 * without decimal point, but with commas etc.
 *
 *   4000 ==> 4,000
 *
 * It'll round fractional values to decimal precision of 3
 *   4000.1212 ==> 4,000.121
 *   4000.0001 ==> 4,000
 *
 */
    public static function countFormat($num)
    {
        $num = round($num, 3);
        if (round($num) == $num) {
            return number_format($num);
        } else {
            return number_format($num, 3);
        }
    }

    /**
     * Get percent format.
     *
     * @param $s
     * @param $precision
     * @return string
     */
    public static function percentFormat($s, $precision = 1)
    {
        return sprintf('%.' . $precision . 'f%%', 100 * $s);
    }

    /**
     * Prints a <td> element with a percentage.
     */
    public function printTdPercent($numer, $denom, $bold = false, $attributes = null)
    {
        global $vbar;
        global $vbbar;
        global $diff_mode;

        $class = $this->getPrintClass($numer, $bold);

        if ($denom == 0) {
            $pct = "N/A%";
        } else {
            $pct = Xhprof::percentFormat($numer / abs($denom));
        }

        print("<td $attributes $class>$pct</td>\n");
    }

    /**
     * @param html-str $content the text/image/innerhtml/whatever for the link
     * @param raw-str $href
     * @param raw-str $class
     * @param raw-str $id
     * @param raw-str $title
     * @param raw-str $target
     * @param raw-str $onclick
     * @param raw-str $style
     * @param raw-str $access
     * @param raw-str $onmouseover
     * @param raw-str $onmouseout
     * @param raw-str $onmousedown
     * @param raw-str $dir
     * @param raw-str $rel
     */
    public function renderLink($content, $href, $class = '', $id = '', $title = '',
                               $target = '',
                               $onclick = '', $style = '', $access = '', $onmouseover = '',
                               $onmouseout = '', $onmousedown = '')
    {

        if (!$content) {
            return '';
        }

        if ($href) {
            $link = '<a href="' . ($href) . '"';
        } else {
            $link = '<span';
        }

        if ($class) {
            $link .= ' class="' . ($class) . '"';
        }
        if ($id) {
            $link .= ' id="' . ($id) . '"';
        }
        if ($title) {
            $link .= ' title="' . ($title) . '"';
        }
        if ($target) {
            $link .= ' target="' . ($target) . '"';
        }
        if ($onclick && $href) {
            $link .= ' onclick="' . ($onclick) . '"';
        }
        if ($style && $href) {
            $link .= ' style="' . ($style) . '"';
        }
        if ($access && $href) {
            $link .= ' accesskey="' . ($access) . '"';
        }
        if ($onmouseover) {
            $link .= ' onmouseover="' . ($onmouseover) . '"';
        }
        if ($onmouseout) {
            $link .= ' onmouseout="' . ($onmouseout) . '"';
        }
        if ($onmousedown) {
            $link .= ' onmousedown="' . ($onmousedown) . '"';
        }

        $link .= '>';
        $link .= $content;
        if ($href) {
            $link .= '</a>';
        } else {
            $link .= '</span>';
        }

        return $link;
    }

    /**
     * Callback comparison operator (passed to usort() for sorting array of
     * tuples) that compares array elements based on the sort column
     * specified in $sort_col (global parameter).
     *
     * @author Kannan
     */
    public static function sortCbk($a, $b)
    {
        global $sort_col;
        global $diff_mode;

        if ($sort_col == "fn") {

            // case insensitive ascending sort for function names
            $left = strtoupper($a["fn"]);
            $right = strtoupper($b["fn"]);

            if ($left == $right)
                return 0;
            return ($left < $right) ? -1 : 1;

        } else {

            // descending sort for all others
            $left = $a[$sort_col];
            $right = $b[$sort_col];

            // if diff mode, sort by absolute value of regression/improvement
            if ($diff_mode) {
                $left = abs($left);
                $right = abs($right);
            }

            if ($left == $right)
                return 0;
            return ($left > $right) ? -1 : 1;
        }
    }

    /**
     * Print "flat" data corresponding to one function.
     *
     * @author Kannan
     */
    public function printFunctionInfo($url_params, $info, $sort, $run1, $run2)
    {
        static $odd_even = 0;

        global $totals;
        global $sort_col;
        global $metrics;
        global $format_cbk;
        global $display_calls;
        global $base_path;

        // Toggle $odd_or_even
        $odd_even = 1 - $odd_even;

        if ($odd_even) {
            print("<tr>");
        } else {
            print('<tr>');
        }

        $href = "$base_path/?" .
            http_build_query(xhprof_array_set($url_params,
                'func', $info["fn"]));

        print('<td>');
        print($view->renderLink($info["fn"], $href));
        print("</td>\n");

        if ($display_calls) {
            // Call Count..
            $view->printTdNum($info["ct"], $format_cbk["ct"], ($sort_col == "ct"));
            $view->printTdPercent($info["ct"], $totals["ct"], ($sort_col == "ct"));
        }

        // Other metrics..
        foreach ($metrics as $metric) {
            // Inclusive metric
            $view->printTdNum($info[$metric], $format_cbk[$metric],
                ($sort_col == $metric));
            $view->printTdPercent($info[$metric], $totals[$metric],
                ($sort_col == $metric));

            // Exclusive Metric
            $view->printTdNum($info["excl_" . $metric],
                $format_cbk["excl_" . $metric],
                ($sort_col == "excl_" . $metric));
            $view->printTdPercent($info["excl_" . $metric],
                $totals[$metric],
                ($sort_col == "excl_" . $metric));
        }

        print("</tr>\n");
    }

    /**
     * Get the appropriate description for a statistic
     * (depending upon whether we are in diff report mode
     * or single run report mode).
     *
     * @author Kannan
     */
    public function statDescription($stat)
    {
        global $descriptions;
        global $diff_descriptions;
        global $diff_mode;

        if ($diff_mode) {
            return $diff_descriptions[$stat];
        } else {
            return $descriptions[$stat];
        }
    }

    /**
     * Print symbol summary.
     *
     * @param $symbol_info
     * @param $stat
     * @param $base
     * @return void
     */
    private function printSymbolSummary($symbol_info, $stat, $base)
    {
        $val = $symbol_info[$stat];
        $desc = str_replace("<br />", " ", $this->statDescription($stat));

        print("$desc: </td>");
        print(number_format($val));
        print(" (" . $this->pct($val, $base) . "% of overall)");
        if (substr($stat, 0, 4) == "excl") {
            $func_base = $symbol_info[str_replace("excl_", "", $stat)];
            print(" (" . $this->pct($val, $func_base) . "% of this function)");
        }
        print("<br />");
    }

    /**
     * Prints a <td> element with a numeric value.
     */
    public function printTdNum($num, $fmt_func, $bold = false, $attributes = null)
    {

        $class = $this->getPrintClass($num, $bold);

        if (!empty($fmt_func)) {
            $num = call_user_func($fmt_func, $num);
        }

        print("<td $attributes $class>$num</td>\n");
    }

    public static function sortWT($a, $b)
    {
        if ($a['excl_wt'] == $b['excl_wt']) {
            return 0;
        }
        return ($a['excl_wt'] < $b['excl_wt']) ? 1 : -1;
    }

    /**
     * Generates a report for a single function/symbol.
     *
     * @author Kannan
     */
    public function symbolReport($url_params,
                                 $run_data, $symbol_info, $sort, $rep_symbol,
                                 $run1,
                                 $symbol_info1 = null,
                                 $run2 = 0,
                                 $symbol_info2 = null)
    {
        $view = new Xhprof();

        // @todo delete all globals.
        global $vwbar;
        global $vbar;
        global $totals;
        global $pc_stats;
        global $sortable_columns;
        global $metrics;
        global $diff_mode;
        global $descriptions;
        global $format_cbk;
        global $sort_col;
        global $display_calls;
        global $base_path;

        $possible_metrics = xhprof_get_possible_metrics();

        if ($diff_mode) {
            $diff_text = "<b>Diff</b>";
            $regr_impr = "<i style='color:red'>Regression</i>/<i style='color:green'>Improvement</i>";
        } else {
            $diff_text = "";
            $regr_impr = "";
        }

        if ($diff_mode) {

            $base_url_params = xhprof_array_unset(xhprof_array_unset($url_params,
                'run1'),
                'run2');
            $href1 = "$base_path?"
                . http_build_query(xhprof_array_set($base_url_params, 'run', $run1));
            $href2 = "$base_path?"
                . http_build_query(xhprof_array_set($base_url_params, 'run', $run2));

            print("<h3 align=center>$regr_impr summary for $rep_symbol<br /><br /></h3>");
            print('<table cellpadding=2 cellspacing=1 width="30%" '
                . 'rules=rows align=center>' . "\n");
            print('<tr align=right>');
            print("<th align=left>$rep_symbol</th>");
            print("<th $vwbar><a href=" . $href1 . ">Run #$run1</a></th>");
            print("<th $vwbar><a href=" . $href2 . ">Run #$run2</a></th>");
            print("<th $vwbar>Diff</th>");
            print("<th $vwbar>Diff%</th>");
            print('</tr>');
            print('<tr>');

            if ($display_calls) {
                print("<td>Number of Function Calls</td>");
                $view->printTdNum($symbol_info1["ct"], $format_cbk["ct"]);
                $view->printTdNum($symbol_info2["ct"], $format_cbk["ct"]);
                $view->printTdNum($symbol_info2["ct"] - $symbol_info1["ct"],
                    $format_cbk["ct"], true);
                $view->printTdPercent($symbol_info2["ct"] - $symbol_info1["ct"],
                    $symbol_info1["ct"], true);
                print('</tr>');
            }

            foreach ($metrics as $metric) {
                $m = $metric;

                // Inclusive stat for metric
                print('<tr>');
                print("<td>" . str_replace("<br />", " ", $descriptions[$m]) . "</td>");
                $view->printTdNum($symbol_info1[$m], $format_cbk[$m]);
                $view->printTdNum($symbol_info2[$m], $format_cbk[$m]);
                $view->printTdNum($symbol_info2[$m] - $symbol_info1[$m], $format_cbk[$m], true);
                $view->printTdPercent($symbol_info2[$m] - $symbol_info1[$m], $symbol_info1[$m], true);
                print('</tr>');

                // AVG (per call) Inclusive stat for metric
                print('<tr>');
                print("<td>" . str_replace("<br />", " ", $descriptions[$m]) . " per call </td>");
                $avg_info1 = 'N/A';
                $avg_info2 = 'N/A';
                if ($symbol_info1['ct'] > 0) {
                    $avg_info1 = ($symbol_info1[$m] / $symbol_info1['ct']);
                }
                if ($symbol_info2['ct'] > 0) {
                    $avg_info2 = ($symbol_info2[$m] / $symbol_info2['ct']);
                }
                $view->printTdNum($avg_info1, $format_cbk[$m]);
                $view->printTdNum($avg_info2, $format_cbk[$m]);
                $view->printTdNum($avg_info2 - $avg_info1, $format_cbk[$m], true);
                $view->printTdPercent($avg_info2 - $avg_info1, $avg_info1, true);
                print('</tr>');

                // Exclusive stat for metric
                $m = "excl_" . $metric;
                print('<tr>');
                print("<td>" . str_replace("<br />", " ", $descriptions[$m]) . "</td>");
                $view->printTdNum($symbol_info1[$m], $format_cbk[$m]);
                $view->printTdNum($symbol_info2[$m], $format_cbk[$m]);
                $view->printTdNum($symbol_info2[$m] - $symbol_info1[$m], $format_cbk[$m], true);
                $view->printTdPercent($symbol_info2[$m] - $symbol_info1[$m], $symbol_info1[$m], true);
                print('</tr>');
            }

            print('</table>');
        }

        print("<br /><h1 class='runTitle'>");
        print("Parent/Child $regr_impr report for <b>$rep_symbol</b></h1>");

//  $callgraph_href = "$base_path/graphviz/?"
//    . http_build_query(xhprof_array_set($url_params, 'func', $rep_symbol));
//  print(" <a class='callgraph' href='$callgraph_href'>View Callgraph $diff_text</a><br />");

        $id = @$_GET['run'];
        // @todo create a function to remove duplicated code below.
        $si = !empty($_GET['show_internal']) ? $_GET['show_internal'] : '0';
        print('<a href="/graphviz/?url=/api/db/%3Frun=' . $id . '%26links=1%26show_internal=' . $si . '%26func=' . $rep_symbol . '" class="callgraph form-button">Callgraph</a>');

        print("<br />");

        print('<table class="box-tables" border=1 cellpadding=2 cellspacing=1 width="90%" '
            . 'rules=rows align=center>' . "\n");
        print('<tr align=right>');

        foreach ($pc_stats as $stat) {
            $desc = $this->statDescription($stat);
            if (array_key_exists($stat, $sortable_columns)) {

                $href = "$base_path/?" .
                    http_build_query(xhprof_array_set($url_params,
                        'sort', $stat));
                $header = $view->renderLink($desc, $href);
            } else {
                $header = $desc;
            }

            if ($stat == "fn")
                print("<th align=left><nobr>$header</th>");
            else
                print("<th " . $vwbar . "><nobr>$header</th>");
        }
        print("</tr>");

        print("<tr class='box-table-title'><td>");
        print("<strong>Current Function</strong>");
        print("</td></tr>");

        print("<tr>");
        // make this a self-reference to facilitate copy-pasting snippets to e-mails
        print("<td><a href=''>$rep_symbol</a></td>");

        if ($display_calls) {
            // Call Count
            $view->printTdNum($symbol_info["ct"], $format_cbk["ct"]);
            $view->printTdPercent($symbol_info["ct"], $totals["ct"]);
        }

        // Inclusive Metrics for current function
        foreach ($metrics as $metric) {
            $view->printTdNum($symbol_info[$metric], $format_cbk[$metric], ($sort_col == $metric));
            $view->printTdPercent($symbol_info[$metric], $totals[$metric], ($sort_col == $metric));
        }
        print("</tr>");

        print("<tr class='box-table-title'>");
        print("<td><strong>"
            . "Exclusive Metrics $diff_text for Current Function</strong></td>");

        if ($display_calls) {
            // Call Count
            print("<td $vbar></td>");
            print("<td $vbar></td>");
        }

        // Exclusive Metrics for current function
        foreach ($metrics as $metric) {
            $view->printTdNum($symbol_info["excl_" . $metric], $format_cbk["excl_" . $metric],
                ($sort_col == $metric),
                $this->getTooltipAttributes("Child", $metric));
            $view->printTdPercent($symbol_info["excl_" . $metric], $symbol_info[$metric],
                ($sort_col == $metric),
                $this->getTooltipAttributes("Child", $metric));
        }
        print("</tr>");

        // list of callers/parent functions
        $results = array();
        if ($display_calls) {
            $base_ct = $symbol_info["ct"];
        } else {
            $base_ct = 0;
        }
        foreach ($metrics as $metric) {
            $base_info[$metric] = $symbol_info[$metric];
        }
        foreach ($run_data as $parent_child => $info) {
            list($parent, $child) = xhprof_parse_parent_child($parent_child);
            if (($child == $rep_symbol) && ($parent)) {
                $info_tmp = $info;
                $info_tmp["fn"] = $parent;
                $results[] = $info_tmp;
            }
        }
        usort($results, '\Xhprof\View\Xhprof::sortCbk');

        if (count($results) > 0) {
            $this->printPcArray($url_params, $results, $base_ct, $base_info, true,
                $run1, $run2);
        }

        // list of callees/child functions
        $results = array();
        $base_ct = 0;
        foreach ($run_data as $parent_child => $info) {
            list($parent, $child) = xhprof_parse_parent_child($parent_child);
            if ($parent == $rep_symbol) {
                $info_tmp = $info;
                $info_tmp["fn"] = $child;
                $results[] = $info_tmp;
                if ($display_calls) {
                    $base_ct += $info["ct"];
                }
            }
        }
        usort($results, '\Xhprof\View\Xhprof::sortCbk');

        if (count($results)) {
            $this->printPcArray($url_params, $results, $base_ct, $base_info, false,
                $run1, $run2);
        }

        print("</table>");

        // These will be used for pop-up tips/help.
        // Related javascript code is in: xhprof_report.js
        print("\n");
        print('<script language="javascript">' . "\n");
        print("var func_name = '\"" . $rep_symbol . "\"';\n");
        print("var total_child_ct  = " . $base_ct . ";\n");
        if ($display_calls) {
            print("var func_ct   = " . $symbol_info["ct"] . ";\n");
        }
        print("var func_metrics = new Array();\n");
        print("var metrics_col  = new Array();\n");
        print("var metrics_desc  = new Array();\n");
        if ($diff_mode) {
            print("var diff_mode = true;\n");
        } else {
            print("var diff_mode = false;\n");
        }
        $column_index = 3; // First three columns are Func Name, Calls, Calls%
        foreach ($metrics as $metric) {
            print("func_metrics[\"" . $metric . "\"] = " . round($symbol_info[$metric]) . ";\n");
            print("metrics_col[\"" . $metric . "\"] = " . $column_index . ";\n");
            print("metrics_desc[\"" . $metric . "\"] = \"" . $possible_metrics[$metric][2] . "\";\n");

            // each metric has two columns..
            $column_index += 2;
        }
        print('</script>');
        print("\n");

    }


    /**
     * Print PC array
     * @param $url_params
     * @param $results
     * @param $base_ct
     * @param $base_info
     * @param $parent
     * @param $run1
     * @param $run2
     * @return void
     * @todo use template .phtml
     *
     */
    private function printPcArray($url_params, $results, $base_ct, $base_info, $parent, $run1, $run2)
    {
        $view = new Xhprof();
        global $base_path;

        // Construct section title
        if ($parent) {
            $title = 'Parent function';
        } else {
            $title = 'Child function';
        }
        if (count($results) > 1) {
            $title .= 's';
        }

        print("<tr class='box-table-title'><td>");
        print("<strong>" . $title . "</strong>");
        print("</td></tr>");

        $odd_even = 0;
        foreach ($results as $info) {
            $href = "$base_path/?" .
                http_build_query(xhprof_array_set($url_params,
                    'func', $info["fn"]));
            $odd_even = 1 - $odd_even;

            if ($odd_even) {
                print('<tr>');
            } else {
                print('<tr>');
            }

            print("<td>" . $view->renderLink($info["fn"], $href) . "</td>");
            $this->pcInfo($info, $base_ct, $base_info, $parent);
            print("</tr>");
        }
    }

    /**
     * Print info for a parent or child function in the
     * parent & children report.
     *
     * @author Kannan
     */
    private function pcInfo($info, $base_ct, $base_info, $parent)
    {
        $view = new Xhprof();

        global $sort_col;
        global $metrics;
        global $format_cbk;
        global $display_calls;

        if ($parent)
            $type = "Parent";
        else
            $type = "Child";

        if ($display_calls) {
            $mouseoverct = $this->getTooltipAttributes($type, "ct");
            /* call count */
            $view->printTdNum($info["ct"], $format_cbk["ct"], ($sort_col == "ct"), $mouseoverct);
            $view->printTdPercent($info["ct"], $base_ct, ($sort_col == "ct"), $mouseoverct);
        }

        /* Inclusive metric values  */
        foreach ($metrics as $metric) {
            $view->printTdNum($info[$metric], $format_cbk[$metric],
                ($sort_col == $metric),
                $this->getTooltipAttributes($type, $metric));
            $view->printTdPercent($info[$metric], $base_info[$metric], ($sort_col == $metric),
                $this->getTooltipAttributes($type, $metric));
        }
    }

    /**
     * Given a number, returns the td class to use for display.
     *
     * For instance, negative numbers in diff reports comparing two runs (run1 & run2)
     * represent improvement from run1 to run2. We use green to display those deltas,
     * and red for regression deltas.
     */
    public function getPrintClass($num, $bold)
    {
        global $vbar;
        global $vbbar;
        global $vrbar;
        global $vgbar;
        global $diff_mode;

        if ($bold) {
            if ($diff_mode) {
                if ($num <= 0) {
                    $class = $vgbar; // green (improvement)
                } else {
                    $class = $vrbar; // red (regression)
                }
            } else {
                $class = $vbbar; // blue
            }
        } else {
            $class = $vbar;  // default (black)
        }

        return $class;
    }

    /**
     * Computes percentage for a pair of values, and returns it
     * in string format.
     */
    public function pct($a, $b)
    {
        if ($b == 0) {
            return "N/A";
        } else {
            $res = (round(($a * 1000 / $b)) / 10);
            return $res;
        }
    }

    /**
     * Implodes the text for a bunch of actions (such as links, forms,
     * into a HTML list and returns the text.
     */
    function renderActions($actions)
    {
        $out = array();
        $out[] = "<div>\n";
        if (count($actions)) {
            $out[] = "<ul class=\"xhprof_actions\">\n";
            foreach ($actions as $action) {
                $out[] = "\t<li>" . $action . "</li>\n";
            }
            $out[] = "</ul>\n";
        }
        $out[] = "</div>\n";
        return implode('', $out);
    }
}
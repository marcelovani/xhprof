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

        $class = get_print_class($numer, $bold);

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
     * Print symbol summary.
     *
     * @param $symbol_info
     * @param $stat
     * @param $base
     * @return void
     */
    public function print_symbol_summary($symbol_info, $stat, $base)
    {
        $val = $symbol_info[$stat];
        $desc = str_replace("<br />", " ", stat_description($stat));

        print("$desc: </td>");
        print(number_format($val));
        print(" (" . pct($val, $base) . "% of overall)");
        if (substr($stat, 0, 4) == "excl") {
            $func_base = $symbol_info[str_replace("excl_", "", $stat)];
            print(" (" . pct($val, $func_base) . "% of this function)");
        }
        print("<br />");
    }
}
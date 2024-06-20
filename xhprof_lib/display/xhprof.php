<?php
//  Copyright (c) 2009 Facebook
//
//  Licensed under the Apache License, Version 2.0 (the "License");
//  you may not use this file except in compliance with the License.
//  You may obtain a copy of the License at
//
//      http://www.apache.org/licenses/LICENSE-2.0
//
//  Unless required by applicable law or agreed to in writing, software
//  distributed under the License is distributed on an "AS IS" BASIS,
//  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//  See the License for the specific language governing permissions and
//  limitations under the License.
//

//
// XHProf: A Hierarchical Profiler for PHP
//
// XHProf has two components:
//
//  * This module is the UI/reporting component, used
//    for viewing results of XHProf runs from a browser.
//
//  * Data collection component: This is implemented
//    as a PHP extension (XHProf).
//
// @author Kannan Muthukkaruppan
//

use Xhprof\View\Xhprof;

$view = new Xhprof();

if (!defined('XHPROF_LIB_ROOT')) {
    // by default, the parent directory is XHPROF lib root
    define('XHPROF_LIB_ROOT', dirname(dirname(__FILE__)));
}

include_once XHPROF_LIB_ROOT . '/utils/xhprof_lib.php';
include_once XHPROF_LIB_ROOT . '/utils/callgraph_utils.php';
include_once XHPROF_LIB_ROOT . '/utils/xhprof_runs.php';

/**
 * Our coding convention disallows relative paths in hrefs.
 * Get the base URL path from the SCRIPT_NAME.
 */
$base_path = rtrim(dirname($_SERVER['SCRIPT_NAME']), "/");

/**
 * Implodes the text for a bunch of actions (such as links, forms,
 * into a HTML list and returns the text.
 */
function xhprof_render_actions($actions)
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

// default column to sort on -- wall time
$sort_col = "wt";

// default is "single run" report
$diff_mode = false;

// call count data present?
$display_calls = true;

// The following column headers are sortable
$sortable_columns = array("fn" => 1,
    "ct" => 1,
    "wt" => 1,
    "excl_wt" => 1,
    "ut" => 1,
    "excl_ut" => 1,
    "st" => 1,
    "excl_st" => 1,
    "mu" => 1,
    "excl_mu" => 1,
    "pmu" => 1,
    "excl_pmu" => 1,
    "cpu" => 1,
    "excl_cpu" => 1,
    "samples" => 1,
    "excl_samples" => 1
);

// Textual descriptions for column headers in "single run" mode
$descriptions = array(
    "fn" => "Function Name",
    "ct" => "Calls",
    "Calls%" => "Calls%",

    "wt" => "Incl. Wall Time<br />(microsec)",
    "IWall%" => "IWall%",
    "excl_wt" => "Excl. Wall Time<br />(microsec)",
    "EWall%" => "EWall%",

    "ut" => "Incl. User<br />(microsecs)",
    "IUser%" => "IUser%",
    "excl_ut" => "Excl. User<br />(microsec)",
    "EUser%" => "EUser%",

    "st" => "Incl. Sys <br />(microsec)",
    "ISys%" => "ISys%",
    "excl_st" => "Excl. Sys <br />(microsec)",
    "ESys%" => "ESys%",

    "cpu" => "Incl. CPU<br />(microsecs)",
    "ICpu%" => "ICpu%",
    "excl_cpu" => "Excl. CPU<br />(microsec)",
    "ECpu%" => "ECPU%",

    "mu" => "Incl.<br />MemUse<br />(bytes)",
    "IMUse%" => "IMemUse%",
    "excl_mu" => "Excl.<br />MemUse<br />(bytes)",
    "EMUse%" => "EMemUse%",

    "pmu" => "Incl.<br /> PeakMemUse<br />(bytes)",
    "IPMUse%" => "IPeakMemUse%",
    "excl_pmu" => "Excl.<br />PeakMemUse<br />(bytes)",
    "EPMUse%" => "EPeakMemUse%",

    "samples" => "Incl. Samples",
    "ISamples%" => "ISamples%",
    "excl_samples" => "Excl. Samples",
    "ESamples%" => "ESamples%",
);

// Formatting Callback Functions...
$format_cbk = array(
    "fn" => "",
    "ct" => "\Xhprof\View\Xhprof::countFormat",
    "Calls%" => "\Xhprof\View\Xhprof::percentFormat",

    "wt" => "number_format",
    "IWall%" => "\Xhprof\View\Xhprof::percentFormat",
    "excl_wt" => "number_format",
    "EWall%" => "\Xhprof\View\Xhprof::percentFormat",

    "ut" => "number_format",
    "IUser%" => "\Xhprof\View\Xhprof::percentFormat",
    "excl_ut" => "number_format",
    "EUser%" => "\Xhprof\View\Xhprof::percentFormat",

    "st" => "number_format",
    "ISys%" => "\Xhprof\View\Xhprof::percentFormat",
    "excl_st" => "number_format",
    "ESys%" => "\Xhprof\View\Xhprof::percentFormat",

    "cpu" => "number_format",
    "ICpu%" => "\Xhprof\View\Xhprof::percentFormat",
    "excl_cpu" => "number_format",
    "ECpu%" => "\Xhprof\View\Xhprof::percentFormat",

    "mu" => "number_format",
    "IMUse%" => "\Xhprof\View\Xhprof::percentFormat",
    "excl_mu" => "number_format",
    "EMUse%" => "\Xhprof\View\Xhprof::percentFormat",

    "pmu" => "number_format",
    "IPMUse%" => "\Xhprof\View\Xhprof::percentFormat",
    "excl_pmu" => "number_format",
    "EPMUse%" => "\Xhprof\View\Xhprof::percentFormat",

    "samples" => "number_format",
    "ISamples%" => "\Xhprof\View\Xhprof::percentFormat",
    "excl_samples" => "number_format",
    "ESamples%" => "\Xhprof\View\Xhprof::percentFormat",
);


// Textual descriptions for column headers in "diff" mode
$diff_descriptions = array(
    "fn" => "Function Name",
    "ct" => "Calls Diff",
    "Calls%" => "Calls<br />Diff%",

    "wt" => "Incl. Wall<br />Diff<br />(microsec)",
    "IWall%" => "IWall<br /> Diff%",
    "excl_wt" => "Excl. Wall<br />Diff<br />(microsec)",
    "EWall%" => "EWall<br />Diff%",

    "ut" => "Incl. User Diff<br />(microsec)",
    "IUser%" => "IUser<br />Diff%",
    "excl_ut" => "Excl. User<br />Diff<br />(microsec)",
    "EUser%" => "EUser<br />Diff%",

    "cpu" => "Incl. CPU Diff<br />(microsec)",
    "ICpu%" => "ICpu<br />Diff%",
    "excl_cpu" => "Excl. CPU<br />Diff<br />(microsec)",
    "ECpu%" => "ECpu<br />Diff%",

    "st" => "Incl. Sys Diff<br />(microsec)",
    "ISys%" => "ISys<br />Diff%",
    "excl_st" => "Excl. Sys Diff<br />(microsec)",
    "ESys%" => "ESys<br />Diff%",

    "mu" => "Incl.<br />MemUse<br />Diff<br />(bytes)",
    "IMUse%" => "IMemUse<br />Diff%",
    "excl_mu" => "Excl.<br />MemUse<br />Diff<br />(bytes)",
    "EMUse%" => "EMemUse<br />Diff%",

    "pmu" => "Incl.<br /> PeakMemUse<br />Diff<br />(bytes)",
    "IPMUse%" => "IPeakMemUse<br />Diff%",
    "excl_pmu" => "Excl.<br />PeakMemUse<br />Diff<br />(bytes)",
    "EPMUse%" => "EPeakMemUse<br />Diff%",

    "samples" => "Incl. Samples Diff",
    "ISamples%" => "ISamples Diff%",
    "excl_samples" => "Excl. Samples Diff",
    "ESamples%" => "ESamples Diff%",
);

// columns that'll be displayed in a top-level report
$stats = array();

// columns that'll be displayed in a function's parent/child report
$pc_stats = array();

// Various total counts
$totals = 0;
$totals_1 = 0;
$totals_2 = 0;

/*
 * The subset of $possible_metrics that is present in the raw profile data.
 */
$metrics = null;

/**
 * Callback comparison operator (passed to usort() for sorting array of
 * tuples) that compares array elements based on the sort column
 * specified in $sort_col (global parameter).
 *
 * @author Kannan
 */
function sort_cbk($a, $b)
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
 * Get the appropriate description for a statistic
 * (depending upon whether we are in diff report mode
 * or single run report mode).
 *
 * @author Kannan
 */
function stat_description($stat)
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
 * Computes percentage for a pair of values, and returns it
 * in string format.
 */
function pct($a, $b)
{
    if ($b == 0) {
        return "N/A";
    } else {
        $res = (round(($a * 1000 / $b)) / 10);
        return $res;
    }
}

/**
 * Given a number, returns the td class to use for display.
 *
 * For instance, negative numbers in diff reports comparing two runs (run1 & run2)
 * represent improvement from run1 to run2. We use green to display those deltas,
 * and red for regression deltas.
 */
function get_print_class($num, $bold)
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
 * Prints a <td> element with a numeric value.
 */
function print_td_num($num, $fmt_func, $bold = false, $attributes = null)
{

    $class = get_print_class($num, $bold);

    if (!empty($fmt_func)) {
        $num = call_user_func($fmt_func, $num);
    }

    print("<td $attributes $class>$num</td>\n");
}

/**
 * Print "flat" data corresponding to one function.
 *
 * @author Kannan
 */
function print_function_info($url_params, $info, $sort, $run1, $run2)
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
        print_td_num($info["ct"], $format_cbk["ct"], ($sort_col == "ct"));
        $view->printTdPercent($info["ct"], $totals["ct"], ($sort_col == "ct"));
    }

    // Other metrics..
    foreach ($metrics as $metric) {
        // Inclusive metric
        print_td_num($info[$metric], $format_cbk[$metric],
            ($sort_col == $metric));
        $view->printTdPercent($info[$metric], $totals[$metric],
            ($sort_col == $metric));

        // Exclusive Metric
        print_td_num($info["excl_" . $metric],
            $format_cbk["excl_" . $metric],
            ($sort_col == "excl_" . $metric));
        $view->printTdPercent($info["excl_" . $metric],
            $totals[$metric],
            ($sort_col == "excl_" . $metric));
    }

    print("</tr>\n");
}

function sortWT($a, $b)
{
    if ($a['excl_wt'] == $b['excl_wt']) {
        return 0;
    }
    return ($a['excl_wt'] < $b['excl_wt']) ? 1 : -1;
}

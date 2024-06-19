<?php
/**
 * @file Report.
 */

namespace Xhprof\Controller;

use Xhprof\View\Xhprof;

/**
 * Report class.
 */
class Report
{

    /**
     * @var \Xhprof\View\Xhprof;
     */
    private $view;

    public function __construct()
    {
        $this->view = new Xhprof();
    }

    /**
     * Getter for Hxprof view.
     *
     * @return Xhprof
     *   The Xhprof view.
     */
    public function getView() {
        return $this->view;
    }

    /**
     * Generate a XHProf Display View given the various URL parameters
     * as arguments. The first argument is an object that implements
     * the iXHProfRuns interface.
     *
     * @param object $xhprof_runs_impl An object that implements
     *                                   the iXHProfRuns interface
     *.
     * @param array $url_params Array of non-default URL params.
     *
     * @param string $source Category/type of the run. The source in
     *                              combination with the run id uniquely
     *                              determines a profiler run.
     *
     * @param string $run run id, or comma separated sequence of
     *                              run ids. The latter is used if an aggregate
     *                              report of the runs is desired.
     *
     * @param string $wts Comma separate list of integers.
     *                              Represents the weighted ratio in
     *                              which which a set of runs will be
     *                              aggregated. [Used only for aggregate
     *                              reports.]
     *
     * @param string $symbol Function symbol. If non-empty then the
     *                              parent/child view of this function is
     *                              displayed. If empty, a flat-profile view
     *                              of the functions is displayed.
     *
     * @param string $run1 Base run id (for diff reports)
     *
     * @param string $run2 New run id (for diff reports)
     *
     */
    public function displayXHProfReport($xhprof_runs_impl, $url_params, $source,
                                        $run, $wts, $symbol, $sort, $run1, $run2)
    {

        if ($run) {                              // specific run to display?

            // run may be a single run or a comma separate list of runs
            // that'll be aggregated. If "wts" (a comma separated list
            // of integral weights is specified), the runs will be
            // aggregated in that ratio.
            //
            $runs_array = explode(",", $run);

            if (count($runs_array) == 1) {
                global $run_details;
                list($xhprof_data, $run_details) = $xhprof_runs_impl->get_run($runs_array[0],
                    $source,
                    $description);
            } else {
                if (!empty($wts)) {
                    $wts_array = explode(",", $wts);
                } else {
                    $wts_array = null;
                }
                $data = xhprof_aggregate_runs($xhprof_runs_impl,
                    $runs_array, $wts_array, $source, false);
                $xhprof_data = $data['raw'];
                $description = $data['description'];
            }

            if (!$xhprof_data) {
                echo "Given XHProf Run not found.";
                return;
            }


            $this->profiler_single_run_report($url_params,
                $xhprof_data,
                $description,
                $symbol,
                $sort,
                $run,
                $run_details);

        } else if ($run1 && $run2) {                  // diff report for two runs

            list($xhprof_data1, $run_details1) = $xhprof_runs_impl->get_run($run1, $source, $description1);
            list($xhprof_data2, $run_details2) = $xhprof_runs_impl->get_run($run2, $source, $description2);

            $this->profiler_diff_report($url_params,
                $xhprof_data1,
                $description1,
                $xhprof_data2,
                $description2,
                $symbol,
                $sort,
                $run1,
                $run2);

        } else {
            echo "No XHProf runs specified in the URL.";
        }
    }

    /**
     * Generate the profiler report for a single run.
     *
     * @author Kannan
     */
    public function profiler_single_run_report($url_params,
                                               $xhprof_data,
                                               $run_desc,
                                               $rep_symbol,
                                               $sort,
                                               $run,
                                               $run_details = null)
    {

        $this->init_metrics($xhprof_data, $rep_symbol, $sort, false);

        $this->profiler_report($url_params, $rep_symbol, $sort, $run, $run_desc,
            $xhprof_data, $run_details);
    }


    /**
     * Generates a tabular report for all functions. This is the top-level report.
     *
     * @author Kannan
     */
    public function full_report($url_params, $symbol_tab, $sort, $run1, $run2, $links) {
        global $vwbar;
        global $vbar;
        global $totals;
        global $totals_1;
        global $totals_2;
        global $metrics;
        global $diff_mode;
        global $descriptions;
        global $sort_col;
        global $format_cbk;
        global $display_calls;
        global $base_path;

        $possible_metrics = xhprof_get_possible_metrics();

        if ($diff_mode) {
            global $xhprof_runs_impl;
            include XHPROF_LIB_ROOT."/templates/diff_run_header_block.phtml";

        } else {
            global $xhprof_runs_impl;
            include XHPROF_LIB_ROOT."/templates/single_run_header_block.phtml";
        }


        //echo xhprof_render_actions($links);


        $flat_data = array();
        foreach ($symbol_tab as $symbol => $info) {
            $tmp = $info;
            $tmp["fn"] = $symbol;

            $flat_data[] = $tmp;
        }
        usort($flat_data, 'sort_cbk');

        print("<br />");

        if (!empty($url_params['all'])) {
            $all = true;
            $limit = 0;    // display all rows
        } else {
            $all = false;
            $limit = 100;  // display only limited number of rows
        }

        $desc = str_replace("<br />", " ", $descriptions[$sort_col]);

        if ($diff_mode) {
            if ($all) {
                $title = "Total Diff Report: '
               .'Sorted by absolute value of regression/improvement in $desc";
            } else {
                $title = "Top 100 <i style='color:red'>Regressions</i>/"
                    . "<i style='color:green'>Improvements</i>: "
                    . "Sorted by $desc Diff";
            }
        } else {
            if ($all) {
                $title = "Sorted by $desc";
            } else {
                $title = "Displaying top $limit functions: Sorted by $desc";
            }
        }
        $this->print_flat_data($url_params, $title, $flat_data, $sort, $run1, $run2, $limit);
    }


    /**
     * Analyze raw data & generate the profiler report
     * (common for both single run mode and diff mode).
     *
     * @author: Kannan
     */
    public function profiler_report($url_params,
                                    $rep_symbol,
                                    $sort,
                                    $run1,
                                    $run1_desc,
                                    $run1_data,
                                    $run2 = 0,
                                    $run2_desc = "",
                                    $run2_data = array())
    {
        global $totals;
        global $totals_1;
        global $totals_2;
        global $stats;
        global $pc_stats;
        global $diff_mode;
        global $base_path;

        // if we are reporting on a specific function, we can trim down
        // the report(s) to just stuff that is relevant to this function.
        // That way compute_flat_info()/compute_diff() etc. do not have
        // to needlessly work hard on churning irrelevant data.
        if (!empty($rep_symbol)) {
            $run1_data = xhprof_trim_run($run1_data, array($rep_symbol));
            if ($diff_mode) {
                $run2_data = xhprof_trim_run($run2_data, array($rep_symbol));
            }
        }

        if ($diff_mode) {
            $run_delta = xhprof_compute_diff($run1_data, $run2_data);
            $symbol_tab = xhprof_compute_flat_info($run_delta, $totals);
            $symbol_tab1 = xhprof_compute_flat_info($run1_data, $totals_1);
            $symbol_tab2 = xhprof_compute_flat_info($run2_data, $totals_2);
        } else {
            $symbol_tab = xhprof_compute_flat_info($run1_data, $totals);
        }

        $run1_txt = sprintf("<b>Run #%s:</b> %s", $run1, $run1_desc);

        $base_url_params = xhprof_array_unset(xhprof_array_unset($url_params,
            'func'),
            'all');

        $top_link_query_string = "$base_path/?" . http_build_query($base_url_params);

        if ($diff_mode) {
            $diff_text = "Diff";
            $base_url_params = xhprof_array_unset($base_url_params, 'run1');
            $base_url_params = xhprof_array_unset($base_url_params, 'run2');
            $run1_link = xhprof_render_link('View Run #' . $run1,
                "$base_path/?" .
                http_build_query(xhprof_array_set($base_url_params,
                    'run',
                    $run1)));
            $run2_txt = sprintf("<b>Run #%s:</b> %s",
                $run2, $run2_desc);

            $run2_link = xhprof_render_link('View Run #' . $run2,
                "$base_path/?" .
                http_build_query(xhprof_array_set($base_url_params,
                    'run',
                    $run2)));
        } else {
            $diff_text = "Run";
        }

        // set up the action links for operations that can be done on this report
        $links = array();


        if ($diff_mode) {
            $inverted_params = $url_params;
            $inverted_params['run1'] = $url_params['run2'];
            $inverted_params['run2'] = $url_params['run1'];

            // view the different runs or invert the current diff
            $links [] = $run1_link;
            $links [] = $run2_link;
            $links [] = xhprof_render_link('Invert ' . $diff_text . ' Report',
                "$base_path/?" .
                http_build_query($inverted_params));
        }

        // lookup function typeahead form


        /**
         * echo
         * '<dl class=phprof_report_info>' .
         * '  <dt>' . $diff_text . ' Report</dt>' .
         * '  <dd>' . ($diff_mode ?
         * $run1_txt . '<br /><b>vs.</b><br />' . $run2_txt :
         * $run1_txt) .
         * '  </dd>' .
         * '  <dt>Tip</dt>' .
         * '  <dd>Click a function name below to drill down.</dd>' .
         * '</dl>' .
         * '<div style="clear: both; margin: 3em 0em;"></div>';
         */
        // data tables
        if (!empty($rep_symbol)) {
            if (!isset($symbol_tab[$rep_symbol])) {
                echo "<hr>Symbol <b>$rep_symbol</b> not found in XHProf run</b><hr>";
                return;
            }

            /* single function report with parent/child information */
            if ($diff_mode) {
                $info1 = isset($symbol_tab1[$rep_symbol]) ?
                    $symbol_tab1[$rep_symbol] : null;
                $info2 = isset($symbol_tab2[$rep_symbol]) ?
                    $symbol_tab2[$rep_symbol] : null;
                symbol_report($url_params, $run_delta, $symbol_tab[$rep_symbol],
                    $sort, $rep_symbol,
                    $run1, $info1,
                    $run2, $info2);
            } else {
                symbol_report($url_params, $run1_data, $symbol_tab[$rep_symbol],
                    $sort, $rep_symbol, $run1);
            }
        } else {
            /* flat top-level report of all functions */
            $this->full_report($url_params, $symbol_tab, $sort, $run1, $run2, $links);
        }

    }


    /**
     * Generate the profiler report for diff mode (delta between two runs).
     *
     * @author Kannan
     */
    public function profiler_diff_report($url_params,
                                         $xhprof_data1,
                                         $run1_desc,
                                         $xhprof_data2,
                                         $run2_desc,
                                         $rep_symbol,
                                         $sort,
                                         $run1,
                                         $run2)
    {


        // Initialize what metrics we'll display based on data in Run2
        init_metrics($xhprof_data2, $rep_symbol, $sort, true);

        profiler_report($url_params,
            $rep_symbol,
            $sort,
            $run1,
            $run1_desc,
            $xhprof_data1,
            $run2,
            $run2_desc,
            $xhprof_data2);
    }


    /**
     * Initialize the metrics we'll display based on the information
     * in the raw data.
     *
     * @author Kannan
     */
    public function init_metrics($xhprof_data, $rep_symbol, $sort, $diff_report = false)
    {
        global $stats;
        global $pc_stats;
        global $metrics;
        global $diff_mode;
        global $sortable_columns;
        global $sort_col;
        global $display_calls;

        $diff_mode = $diff_report;

        if (!empty($sort)) {
            if (array_key_exists($sort, $sortable_columns)) {
                $sort_col = $sort;
            } else {
                print("Invalid Sort Key $sort specified in URL");
            }
        }

        // For C++ profiler runs, walltime attribute isn't present.
        // In that case, use "samples" as the default sort column.
        if (!isset($xhprof_data["main()"]["wt"])) {

            if ($sort_col == "wt") {
                $sort_col = "samples";
            }

            // C++ profiler data doesn't have call counts.
            // ideally we should check to see if "ct" metric
            // is present for "main()". But currently "ct"
            // metric is artificially set to 1. So, relying
            // on absence of "wt" metric instead.
            $display_calls = false;
        } else {
            $display_calls = true;
        }

        // parent/child report doesn't support exclusive times yet.
        // So, change sort hyperlinks to closest fit.
        if (!empty($rep_symbol)) {
            $sort_col = str_replace("excl_", "", $sort_col);
        }

        if ($display_calls) {
            $stats = array("fn", "ct", "Calls%");
        } else {
            $stats = array("fn");
        }

        $pc_stats = $stats;

        $possible_metrics = xhprof_get_possible_metrics($xhprof_data);
        foreach ($possible_metrics as $metric => $desc) {
            if (isset($xhprof_data["main()"][$metric])) {
                $metrics[] = $metric;
                // flat (top-level reports): we can compute
                // exclusive metrics reports as well.
                $stats[] = $metric;
                $stats[] = "I" . $desc[0] . "%";
                $stats[] = "excl_" . $metric;
                $stats[] = "E" . $desc[0] . "%";

                // parent/child report for a function: we can
                // only breakdown inclusive times correctly.
                $pc_stats[] = $metric;
                $pc_stats[] = "I" . $desc[0] . "%";
            }
        }
    }

    /**
     * Print non-hierarchical (flat-view) of profiler data.
     *
     * @author Kannan
     */
    public function print_flat_data($url_params, $title, $flat_data, $sort, $run1, $run2, $limit) {

        global $stats;
        global $sortable_columns;
        global $vwbar;
        global $base_path;

        $size  = count($flat_data);
        if (!$limit) {              // no limit
            $limit = $size;
            $display_link = "";
        } else {
            $display_link = xhprof_render_link(" [ <b class=bubble>display all </b>]",
                "$base_path/?" .
                http_build_query(xhprof_array_set($url_params,
                    'all', 1)));
        }

        //Find top $n requests
        $data_copy = $flat_data;
        $data_copy = $this->getView()->aggregateCalls($data_copy, null, $run2);
        usort($data_copy, 'sortWT');

        $iterations = 0;
        $colors = array('#4572A7', '#AA4643', '#89A54E', '#80699B', '#3D96AE', '#DB843D', '#92A8CD', '#A47D7C', '#B5CA92', '#EAFEBB', '#FEB4B1', '#2B6979', '#E9D6FE', '#FECDA3', '#FED980');
        foreach($data_copy as $datapoint)
        {
            if (++$iterations > 14)
            {
                $function_color[$datapoint['fn']] = $colors[14];
            }else
            {
                $function_color[$datapoint['fn']] = $colors[$iterations-1];
            }
        }

        include( XHPROF_LIB_ROOT."/templates/profChart.phtml");
        include( XHPROF_LIB_ROOT."/templates/profTable.phtml");

    }

}

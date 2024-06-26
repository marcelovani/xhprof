<?php
/**
 * @file Xhprof libraries
 */

namespace Xhprof;

/**
 * Xhproflib class.
 */
class XhprofLib
{
    /*
     * The list of possible metrics collected as part of XHProf that
     * require inclusive/exclusive handling while reporting.
     *
     * @author Kannan
     */
    /**
     * Return a trimmed version of the XHProf raw data. Note that the raw
     * data contains one entry for each unique parent/child function
     * combination.The trimmed version of raw data will only contain
     * entries where either the parent or child function is in the list
     * of $functions_to_keep.
     *
     * Note: Function main() is also always kept so that overall totals
     * can still be obtained from the trimmed version.
     *
     * @param array  XHProf raw data
     * @param array  array of function names
     *
     * @return array  Trimmed XHProf Report
     *
     * @author Kannan
     */
    public function trimRun($raw_data, $functions_to_keep)
    {

        // convert list of functions to a hash with function as the key
        $function_map = array_fill_keys($functions_to_keep, 1);

        // always keep main() as well so that overall totals can still
        // be computed if need be.
        $function_map['main()'] = 1;

        $new_raw_data = array();
        foreach ($raw_data as $parent_child => $info) {
            list($parent, $child) = $this->parseParentChild($parent_child);

            if (isset($function_map[$parent]) || isset($function_map[$child])) {
                $new_raw_data[$parent_child] = $info;
            }
        }

        return $new_raw_data;
    }

    /*
 * Get the list of metrics present in $xhprof_data as an array.
 *
 * @author Kannan
 */

    /**
     * Takes a parent/child function name encoded as
     * "a==>b" and returns array("a", "b").
     *
     * @author Kannan
     */
    public function parseParentChild($parent_child)
    {
        $ret = explode("==>", $parent_child);

        // Return if both parent and child are set
        if (isset($ret[1])) {
            return $ret;
        }

        return array(null, $ret[0]);
    }

    /**
     * Get raw data corresponding to specified array of runs
     * aggregated by certain weightage.
     *
     * Suppose you have run:5 corresponding to page1.php,
     *                  run:6 corresponding to page2.php,
     *             and  run:7 corresponding to page3.php
     *
     * and you want to accumulate these runs in a 2:4:1 ratio. You
     * can do so by calling:
     *
     *     aggregateRuns(array(5, 6, 7), array(2, 4, 1));
     *
     * The above will return raw data for the runs aggregated
     * in 2:4:1 ratio.
     *
     * @param object $xhprof_runs_impl An object that implements
     *                                    the iXHProfRuns interface
     * @param array $runs run ids of the XHProf runs..
     * @param array $wts integral (ideally) weights for $runs
     * @param string $source source to fetch raw data for run from
     * @param bool $use_script_name If true, a fake edge from main() to
     *                                  to __script::<scriptname> is introduced
     *                                  in the raw data so that after aggregations
     *                                  the script name is still preserved.
     *
     * @return array  Return aggregated raw data
     *
     * @author Kannan
     */
    public function aggregateRuns($xhprof_runs_impl, $runs,
                                  $wts, $source = "phprof",
                                  $use_script_name = false)
    {

        $raw_data_total = null;
        $raw_data = null;
        $metrics = array();

        $run_count = count($runs);
        $wts_count = count($wts);

        if (($run_count == 0) ||
            (($wts_count > 0) && ($run_count != $wts_count))) {
            return array('description' => 'Invalid input..',
                'raw' => null);
        }

        $bad_runs = array();
        foreach ($runs as $idx => $run_id) {

            $raw_data = $xhprof_runs_impl->get_run($run_id, $source, $description);

            // use the first run to derive what metrics to aggregate on.
            if ($idx == 0) {
                foreach ($raw_data["main()"] as $metric => $val) {
                    if ($metric != "pmu") {
                        // for now, just to keep data size small, skip "peak" memory usage
                        // data while aggregating.
                        // The "regular" memory usage data will still be tracked.
                        if (isset($val)) {
                            $metrics[] = $metric;
                        }
                    }
                }
            }

            if (!$this->validRun($run_id, $raw_data)) {
                $bad_runs[] = $run_id;
                continue;
            }

            if ($use_script_name) {
                $page = $description;

                // create a fake function '__script::$page', and have and edge from
                // main() to '__script::$page'. We will also need edges to transfer
                // all edges originating from main() to now originate from
                // '__script::$page' to all function called from main().
                //
                // We also weight main() ever so slightly higher so that
                // it shows up above the new entry in reports sorted by
                // inclusive metrics or call counts.
                if ($page) {
                    foreach ($raw_data["main()"] as $metric => $val) {
                        $fake_edge[$metric] = $val;
                        $new_main[$metric] = $val + 0.00001;
                    }
                    $raw_data["main()"] = $new_main;
                    $raw_data[$this->buildParentChildKey("main()",
                        "__script::$page")]
                        = $fake_edge;
                } else {
                    $use_script_name = false;
                }
            }

            // if no weights specified, use 1 as the default weightage..
            $wt = ($wts_count == 0) ? 1 : $wts[$idx];

            // aggregate $raw_data into $raw_data_total with appropriate weight ($wt)
            foreach ($raw_data as $parent_child => $info) {
                if ($use_script_name) {
                    // if this is an old edge originating from main(), it now
                    // needs to be from '__script::$page'
                    if (substr($parent_child, 0, 9) == "main()==>") {
                        $child = substr($parent_child, 9);
                        // ignore the newly added edge from main()
                        if (substr($child, 0, 10) != "__script::") {
                            $parent_child = $this->buildParentChildKey("__script::$page",
                                $child);
                        }
                    }
                }

                if (!isset($raw_data_total[$parent_child])) {
                    foreach ($metrics as $metric) {
                        $raw_data_total[$parent_child][$metric] = ($wt * $info[$metric]);
                    }
                } else {
                    foreach ($metrics as $metric) {
                        $raw_data_total[$parent_child][$metric] += ($wt * $info[$metric]);
                    }
                }
            }
        }

        $runs_string = implode(",", $runs);

        if (isset($wts)) {
            $wts_string = "in the ratio (" . implode(":", $wts) . ")";
            $normalization_count = array_sum($wts);
        } else {
            $wts_string = "";
            $normalization_count = $run_count;
        }

        $run_count = $run_count - count($bad_runs);

        $data['description'] = "Aggregated Report for $run_count runs: " .
            "$runs_string $wts_string\n";
        $data['raw'] = $this->normalizeMetrics($raw_data_total,
            $normalization_count);
        $data['bad_runs'] = $bad_runs;

        return $data;
    }

    /**
     * Checks if XHProf raw data appears to be valid and not corrupted.
     *
     * @param int $run_id Run id of run to be pruned.
     *                                 [Used only for reporting errors.]
     * @param array $raw_data XHProf raw data to be pruned
     *                                 & validated.
     *
     * @return  bool   true on success, false on failure
     *
     * @author Kannan
     */
    public function validRun($run_id, $raw_data)
    {

        $main_info = $raw_data["main()"];
        if (empty($main_info)) {
            xhprof_error("XHProf: main() missing in raw data for Run ID: $run_id");
            return false;
        }

        // raw data should contain either wall time or samples information...
        if (isset($main_info["wt"])) {
            $metric = "wt";
        } else if (isset($main_info["samples"])) {
            $metric = "samples";
        } else {
            xhprof_error("XHProf: Wall Time information missing from Run ID: $run_id");
            return false;
        }

        foreach ($raw_data as $info) {
            $val = $info[$metric];

            // basic sanity checks...
            if ($val < 0) {
                xhprof_error("XHProf: $metric should not be negative: Run ID $run_id"
                    . serialize($info));
                return false;
            }
            if ($val > (86400000000)) {
                xhprof_error("XHProf: $metric > 1 day found in Run ID: $run_id "
                    . serialize($info));
                return false;
            }
        }
        return true;
    }

    /**
     * Given parent & child function name, composes the key
     * in the format present in the raw data.
     *
     * @author Kannan
     */
    public function buildParentChildKey($parent, $child)
    {
        if ($parent) {
            return $parent . "==>" . $child;
        } else {
            return $child;
        }
    }

    /**
     * Takes raw XHProf data that was aggregated over "$num_runs" number
     * of runs averages/nomalizes the data. Essentially the various metrics
     * collected are divided by $num_runs.
     *
     * @author Kannan
     */
    public function normalizeMetrics($raw_data, $num_runs)
    {

        if (empty($raw_data) || ($num_runs == 0)) {
            return $raw_data;
        }

        $raw_data_total = array();

        if (isset($raw_data["==>main()"]) && isset($raw_data["main()"])) {
            xhprof_error("XHProf Error: both ==>main() and main() set in raw data...");
        }

        foreach ($raw_data as $parent_child => $info) {
            foreach ($info as $metric => $value) {
                $raw_data_total[$parent_child][$metric] = ($value / $num_runs);
            }
        }

        return $raw_data_total;
    }

    /**
     * Hierarchical diff:
     * Compute and return difference of two call graphs: Run2 - Run1.
     *
     * @author Kannan
     */
    public function computeDiff($xhprof_data1, $xhprof_data2)
    {
        global $display_calls;

        // use the second run to decide what metrics we will do the diff on
        $metrics = $this->getMetrics($xhprof_data2);

        $xhprof_delta = $xhprof_data2;

        foreach ($xhprof_data1 as $parent_child => $info) {

            if (!isset($xhprof_delta[$parent_child])) {

                // this pc combination was not present in run1;
                // initialize all values to zero.
                if ($display_calls) {
                    $xhprof_delta[$parent_child] = array("ct" => 0);
                } else {
                    $xhprof_delta[$parent_child] = array();
                }
                foreach ($metrics as $metric) {
                    $xhprof_delta[$parent_child][$metric] = 0;
                }
            }

            if ($display_calls) {
                $xhprof_delta[$parent_child]["ct"] -= $info["ct"];
            }

            foreach ($metrics as $metric) {
                $xhprof_delta[$parent_child][$metric] -= $info[$metric];
            }
        }

        return $xhprof_delta;
    }

    public function getMetrics($xhprof_data)
    {

        // get list of valid metrics
        $possible_metrics = $this->getPossibleMetrics();

        // return those that are present in the raw data.
        // We'll just look at the root of the subtree for this.
        $metrics = array();
        foreach ($possible_metrics as $metric => $desc) {
            if (isset($xhprof_data["main()"][$metric])) {
                $metrics[] = $metric;
            }
        }

        return $metrics;
    }

    public function getPossibleMetrics()
    {
        static $possible_metrics =
            array("wt" => array("Wall", "microsecs", "walltime"),
                "ut" => array("User", "microsecs", "user cpu time"),
                "st" => array("Sys", "microsecs", "system cpu time"),
                "cpu" => array("Cpu", "microsecs", "cpu time"),
                "mu" => array("MUse", "bytes", "memory usage"),
                "pmu" => array("PMUse", "bytes", "peak memory usage"),
                "samples" => array("Samples", "samples", "cpu time"));
        return $possible_metrics;
    }

    /*
     * Prunes XHProf raw data:
     *
     * Any node whose inclusive walltime accounts for less than $prune_percent
     * of total walltime is pruned. [It is possible that a child function isn't
     * pruned, but one or more of its parents get pruned. In such cases, when
     * viewing the child function's hierarchical information, the cost due to
     * the pruned parent(s) will be attributed to a special function/symbol
     * "__pruned__()".]
     *
     *  @param   array  $raw_data      XHProf raw data to be pruned & validated.
     *  @param   double $prune_percent Any edges that account for less than
     *                                 $prune_percent of time will be pruned
     *                                 from the raw data.
     *
     *  @return  array  Returns the pruned raw data.
     *
     *  @author Kannan
     */

    /**
     * Analyze hierarchical raw data, and compute per-function (flat)
     * inclusive and exclusive metrics.
     *
     * Also, store overall totals in the 2nd argument.
     *
     * @param array $raw_data XHProf format raw profiler data.
     * @param array &$overall_totals OUT argument for returning
     *                                  overall totals for various
     *                                  metrics.
     * @return array Returns a map from function name to its
     *               call count and inclusive & exclusive metrics
     *               (such as wall time, etc.).
     *
     * @author Kannan Muthukkaruppan
     */
    public function computeFlatInfo($raw_data, &$overall_totals)
    {

        global $display_calls;

        $metrics = $this->getMetrics($raw_data);

        $overall_totals = array("ct" => 0,
            "wt" => 0,
            "ut" => 0,
            "st" => 0,
            "cpu" => 0,
            "mu" => 0,
            "pmu" => 0,
            "samples" => 0
        );

        // compute inclusive times for each function
        $symbol_tab = $this->computeInclusiveTimes($raw_data);

        /* total metric value is the metric value for "main()" */
        foreach ($metrics as $metric) {
            $overall_totals[$metric] = $symbol_tab["main()"][$metric];
        }

        /*
         * initialize exclusive (self) metric value to inclusive metric value
         * to start with.
         * In the same pass, also add up the total number of function calls.
         */
        foreach ($symbol_tab as $symbol => $info) {
            foreach ($metrics as $metric) {
                $symbol_tab[$symbol]["excl_" . $metric] = $symbol_tab[$symbol][$metric];
            }
            if ($display_calls) {
                /* keep track of total number of calls */
                $overall_totals["ct"] += $info["ct"];
            }
        }

        /* adjust exclusive times by deducting inclusive time of children */
        foreach ($raw_data as $parent_child => $info) {
            list($parent, $child) = $this->parseParentChild($parent_child);

            if ($parent) {
                foreach ($metrics as $metric) {
                    // make sure the parent exists hasn't been pruned.
                    if (isset($symbol_tab[$parent])) {
                        $symbol_tab[$parent]["excl_" . $metric] -= $info[$metric];
                    }
                }
            }
        }

        return $symbol_tab;
    }

    /**
     * Compute inclusive metrics for function. This code was factored out
     * of computeFlatInfo().
     *
     * The raw data contains inclusive metrics of a function for each
     * unique parent function it is called from. The total inclusive metrics
     * for a function is therefore the sum of inclusive metrics for the
     * function across all parents.
     *
     * @return array  Returns a map of function name to total (across all parents)
     *                inclusive metrics for the function.
     *
     * @author Kannan
     */
    public function computeInclusiveTimes($raw_data)
    {
        global $display_calls;

        $metrics = $this->getMetrics($raw_data);

        $symbol_tab = array();

        /*
         * First compute inclusive time for each function and total
         * call count for each function across all parents the
         * function is called from.
         */
        foreach ($raw_data as $parent_child => $info) {

            list($parent, $child) = $this->parseParentChild($parent_child);

            if ($parent == $child) {
                /*
                 * XHProf PHP extension should never trigger this situation any more.
                 * Recursion is handled in the XHProf PHP extension by giving nested
                 * calls a unique recursion-depth appended name (for example, foo@1).
                 */
                xhprof_error("Error in Raw Data: parent & child are both: $parent");
                return;
            }

            if (!isset($symbol_tab[$child])) {

                if ($display_calls) {
                    $symbol_tab[$child] = array("ct" => $info["ct"]);
                } else {
                    $symbol_tab[$child] = array();
                }
                foreach ($metrics as $metric) {
                    $symbol_tab[$child][$metric] = $info[$metric];
                }
            } else {
                if ($display_calls) {
                    /* increment call count for this child */
                    $symbol_tab[$child]["ct"] += $info["ct"];
                }

                /* update inclusive times/metric for this child  */
                foreach ($metrics as $metric) {
                    $symbol_tab[$child][$metric] += $info[$metric];
                }
            }
        }

        return $symbol_tab;
    }

    public function pruneRun($raw_data, $prune_percent)
    {

        $main_info = $raw_data["main()"];
        if (empty($main_info)) {
            xhprof_error("XHProf: main() missing in raw data");
            return false;
        }

        // raw data should contain either wall time or samples information...
        if (isset($main_info["wt"])) {
            $prune_metric = "wt";
        } else if (isset($main_info["samples"])) {
            $prune_metric = "samples";
        } else {
            xhprof_error("XHProf: for main() we must have either wt "
                . "or samples attribute set");
            return false;
        }

        // determine the metrics present in the raw data..
        $metrics = array();
        foreach ($main_info as $metric => $val) {
            if (isset($val)) {
                $metrics[] = $metric;
            }
        }

        $prune_threshold = (($main_info[$prune_metric] * $prune_percent) / 100.0);

        init_metrics($raw_data, null, null, false);
        $flat_info = $this->computeInclusiveTimes($raw_data);

        foreach ($raw_data as $parent_child => $info) {

            list($parent, $child) = $this->parseParentChild($parent_child);

            // is this child's overall total from all parents less than threshold?
            if ($flat_info[$child][$prune_metric] < $prune_threshold) {
                unset($raw_data[$parent_child]); // prune the edge
            } else if ($parent &&
                ($parent != "__pruned__()") &&
                ($flat_info[$parent][$prune_metric] < $prune_threshold)) {

                // Parent's overall inclusive metric is less than a threshold.
                // All edges to the parent node will get nuked, and this child will
                // be a dangling child.
                // So instead change its parent to be a special function __pruned__().
                $pruned_edge = $this->buildParentChildKey("__pruned__()", $child);

                if (isset($raw_data[$pruned_edge])) {
                    foreach ($metrics as $metric) {
                        $raw_data[$pruned_edge][$metric] += $raw_data[$parent_child][$metric];
                    }
                } else {
                    $raw_data[$pruned_edge] = $raw_data[$parent_child];
                }

                unset($raw_data[$parent_child]); // prune the edge
            }
        }

        return $raw_data;
    }

    /**
     * Set one key in an array and return the array
     *
     * @author Kannan
     */
    public function arraySet($arr, $k, $v)
    {
        $arr[$k] = $v;
        return $arr;
    }

    /**
     * Removes/unsets one key in an array and return the array
     *
     * @author Kannan
     */
    public function arrayUnset($arr, $k)
    {
        //    var_dump($arr);
        unset($arr[$k]);
        return $arr;
    }

    /**
     * Given a partial query string $q return matching function names in
     * specified XHProf run. This is used for the type ahead function
     * selector.
     *
     * @author Kannan
     */
    public function getMatchingFunctions($q, $xhprof_data)
    {

        $matches = array();

        foreach ($xhprof_data as $parent_child => $info) {
            list($parent, $child) = $this->parseParentChild($parent_child);
            if (stripos($parent, $q) !== false) {
                $matches[$parent] = 1;
            }
            if (stripos($child, $q) !== false) {
                $matches[$child] = 1;
            }
        }

        $res = array_keys($matches);

        // sort it so the answers are in some reliable order...
        asort($res);

        return ($res);
    }

}
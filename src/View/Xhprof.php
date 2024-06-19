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
}
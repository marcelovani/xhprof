<?php
/**
 * @file xhprof ender.
 *
 * Include this file to stop profiling and save the data.
 */

// stop profiler
$xhprof_data = xhprof_disable();

if (empty($xhprof_data)) {
  return;
}

$XHPROF_ROOT = realpath(dirname(__FILE__) .'/..');
require_once $XHPROF_ROOT . "/config.php";
require_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_lib.php";
require_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_runs.php";
if (!defined('XHPROF_LIB_ROOT')) {
  define('XHPROF_LIB_ROOT', dirname(dirname(__FILE__)) . '/xhprof_lib');
}

// display raw xhprof data for the profiler run

if (!isCommandLineInterface()) {
  echo "<pre style='
          height: 200px;
          overflow-y: scroll;
          width: 500px;
          border: 1px solid #000;
          padding: 1em;'>";
  print_r($xhprof_data);
  echo "</pre>";
}

// save raw data for this profiler run using default
// implementation of iXHProfRuns.
$xhprof_runs = new XHProfRuns_Default();

// save the run under a namespace "xhprof_foo"
$run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_foo");

$xhprof_url = $_xhprof['url'];

if (!isCommandLineInterface()) {
  echo "<pre>";
  echo "<a href='";
}
echo "$xhprof_url/?run=$run_id";
if (!isCommandLineInterface()) {
  ">"."View the XH GUI for this run"."</a>\n". "</pre>";
}
echo PHP_EOL;

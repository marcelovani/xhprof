<?php
require_once getcwd() . '/../../../src/App.php';
require_once XHPROF_LIB_ROOT . '/params.php';

use Xhprof\Controller\XhprofRuns;

if (false !== $controlIPs && !in_array($_SERVER['REMOTE_ADDR'], $controlIPs))
{
  die("You do not have permission to view this page.");
}

include_once XHPROF_LIB_ROOT . '/display/xhprof.php';

$xhprof_runs_impl = new XhprofRuns();

if (!empty($run)) {
  $digraph = xhprof_render_dot($xhprof_runs_impl, $run, $type, $threshold, $func, $source, $critical, $show_internal, $links);

  print_r($digraph);
}
else {
  die('Something went wrong.');
}

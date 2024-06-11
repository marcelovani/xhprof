<?php
require_once("api_common.php");

$xhprof_runs_impl = new XHProfRuns_Default();

if (!empty($run)) {
  $digraph = xhprof_render_dot($xhprof_runs_impl, $run, $type, $threshold, $func, $source, $critical, $show_internal, $links);
  print_r($digraph);
}
else {
  die('Something went wrong.');
}

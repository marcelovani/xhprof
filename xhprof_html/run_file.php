<?php
require_once("api_common.php");

if (!empty($run)) {
  $raw_data = unserialize(file_get_contents('/traces/' . $run . '.xhprof'));
  $raw_data = (isset($raw_data['data'])) ? $raw_data['data'] : $raw_data;
  $source = 'xhprof';
  $page = 'XHProf Run (Namespace=xhprof)';
  $critical_path = true;
  $right = null;
  $left = null;

  $digraph = xhprof_generate_dot_script($raw_data, $threshold, $source, $page, $func, $critical_path, $right, $left, $show_internal, $links);

  print_r($digraph);
}
else {
  die('Something went wrong.');
}

<?php
require_once getcwd() . '/../../../xhprof_lib/defaults.php';
require_once XHPROF_CONFIG;
require_once XHPROF_LIB_ROOT . '/params.php';

if (false !== $controlIPs && !in_array($_SERVER['REMOTE_ADDR'], $controlIPs))
{
  die("You do not have permission to view this page.");
}

include_once XHPROF_LIB_ROOT . '/display/xhprof.php';

if (!empty($run)) {
  $filename = '/traces/' . $run . '.xhprof';
  $contents = file_get_contents($filename);
  $raw_data = unserialize($contents);
  $contents = null;
  $raw_data = (isset($raw_data['data'])) ? $raw_data['data'] : $raw_data;
  $source = 'xhprof';
  $page = 'XHProf Run (Namespace=xhprof)';
  $critical_path = true;
  $right = null;
  $left = null;

  $digraph = xhprof_generate_dot_script($raw_data, $threshold, $source, $page, $func, $critical_path, $right, $left, $show_internal, $links);
  $raw_data = null;

  print_r($digraph);
}
else {
  die('Something went wrong.');
}

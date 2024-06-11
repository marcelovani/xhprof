<?php

require_once("../config.php");

if (FALSE !== $controlIPs && !in_array($_SERVER['REMOTE_ADDR'], $controlIPs)) {
  die("You do not have permission to view this page.");
}

// by default assume that xhprof_html & xhprof_lib directories
// are at the same level.
if (!defined('XHPROF_LIB_ROOT')) {
  define('XHPROF_LIB_ROOT', dirname(dirname(__FILE__)) . '/xhprof_lib');
}

require_once XHPROF_LIB_ROOT . '/display/xhprof.php';
require_once XHPROF_LIB_ROOT . "/utils/common.php";
require_once "../xhprof_lib/utils/xhprof_lib.php";
require_once "../xhprof_lib/utils/callgraph_utils.php";

ini_set('max_execution_time', 100);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");


$params = array( // run id param
  'run' => array(XHPROF_STRING_PARAM, ''),
  // source/namespace/type of run
  'source' => array(XHPROF_STRING_PARAM, 'xhprof'),
  // the focus function, if it is set, only directly
  // parents/children functions of it will be shown.
  'func' => array(XHPROF_STRING_PARAM, ''),
  // image type, can be 'jpg', 'gif', 'ps', 'png'
  'type' => array(XHPROF_STRING_PARAM, 'png'),
  // only functions whose exclusive time over the total time
  // is larger than this threshold will be shown.
  // default is 0.01.
  'threshold' => array(XHPROF_FLOAT_PARAM, 0.01),
  // Show internal PHP functions
  'show_internal' => array(XHPROF_BOOL_PARAM, 'false'),
  // Show links.
  'links' => array(XHPROF_BOOL_PARAM, 'false'),
  // whether to show critical_path
  'critical' => array(XHPROF_BOOL_PARAM, 'true'),
  // first run in diff mode.
  'run1' => array(XHPROF_STRING_PARAM, ''),
  // second run in diff mode.
  'run2' => array(XHPROF_STRING_PARAM, '')
);
xhprof_param_init($params);

// if invalid value specified for threshold, then use the default
//if ($threshold < 0 || $threshold > 1) {
//  $threshold = .01
//}

// if invalid value specified for type, use the default
if (!array_key_exists($type, $xhprof_legal_image_types)) {
  $type = $params['type'][1]; // default image type.
}

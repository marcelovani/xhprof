<?php

require_once "utils/xhprof_lib.php";
require_once "utils/callgraph_utils.php";

$params = array( // run id param
    // The API url.
    'url' => array(XHPROF_URL_PARAM, ''),
    // The run id.
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
    // Turn on extra features to allow debugging.
    'debug' => array(XHPROF_BOOL_PARAM, 'false'),
    // Show links.
    'links' => array(XHPROF_BOOL_PARAM, 'true'),
    // whether to show critical_path
    'critical' => array(XHPROF_BOOL_PARAM, 'true'),
    // first run in diff mode.
    'run1' => array(XHPROF_STRING_PARAM, ''),
    // second run in diff mode.
    'run2' => array(XHPROF_STRING_PARAM, '')
);

xhprof_param_init($params);

// if invalid value specified for threshold, then use the default
if ($threshold < 0 || $threshold > 1) {
    $threshold = .01;
}

// if invalid value specified for type, use the default
if (!array_key_exists($type, $xhprof_legal_image_types)) {
    $type = $params['type'][1]; // default image type.
}

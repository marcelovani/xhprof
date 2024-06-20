<?php
require_once getcwd() . '/../../../src/App.php';

//require_once XHPROF_LIB_ROOT . '/params.php';

use Xhprof\Controller\XhprofRuns;
use Xhprof\Request\Params;

if (false !== $controlIPs && !in_array($_SERVER['REMOTE_ADDR'], $controlIPs)) {
    die("You do not have permission to view this page.");
}

include_once XHPROF_LIB_ROOT . '/display/xhprof.php';

$xhprof_runs_impl = new XhprofRuns();
$params = new Params();
$run = $params->get('run');
$type = $params->get('type');
$threshold = $params->get('threshold');
$func = $params->get('func');
$source = $params->get('source');
$critical = $params->get('critical');
$show_internal = $params->get('show_internal');
$links = $params->get('links');

if (!empty($run)) {
    // @todo why pass all these parameters individually? We can just pass the Params object
    $digraph = xhprof_render_dot($xhprof_runs_impl, $run, $type, $threshold, $func, $source, $critical, $show_internal, $links);

    print_r($digraph);
} else {
    die('Something went wrong.');
}

<?php
require_once getcwd() . '/../../src/App.php';

use \Xhprof\Request\Params;
use Xhprof\Utils;
use Xhprof\View\XhprofView;

if (false !== $controlIPs && !in_array($_SERVER['REMOTE_ADDR'], $controlIPs)) {
    die("You do not have permission to view this page.");
}

include_once XHPROF_LIB_ROOT . '/display/xhprof.php';
$params = new Params();
$run = $params->get('run');

$utils = new Utils();
$report_url = $utils->getReportUrl();
$view = new XhprofView();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Xhprof <?php echo $run; ?></title>
    <style>:root { --theme: <?php print $_ENV['THEME']; ?>; }</style>
    <link rel="stylesheet" media="all" href="/css/xhprof.css">
    <link rel="stylesheet" media="all" href="/graphviz/css/main.css">
    <link rel="stylesheet" media="all" href="/graphviz/css/toggles.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/viz.js/2.1.2/viz.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lz-string/1.5.0/lz-string.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/svg-pan-zoom@3.5.0/dist/svg-pan-zoom.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.34.2/ace.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/easy-toggle-state/1.16.0/easy-toggle-state.min.js"></script>
</head>
<body>

<div id="app">
    <div id="header">
        <?php $params = new Params(); ?>
        <?php $show_internal = $params->get('show_internal'); ?>
        <?php $threshold = $params->get('threshold'); ?>
        <?php require("templates/header.phtml"); ?>
    </div>
    <div id="panes">
        <div id="editor"></div>
        <div id="graph">
            <div id="output">
                <div id="error"></div>
                <?php require('templates/loader_animation.svg'); ?>
            </div>
            <div id="status"></div>
        </div>
    </div>
</div>

</body>
<script src="/graphviz/js/main.js"></script>
</html>

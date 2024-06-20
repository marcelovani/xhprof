<?php

// Autoloader.
$locations = [
    './',
    '../',
    '../../',
    '../../../',
];
foreach ($locations as $location) {
    $autoload = "$location/vendor/autoload.php";
    if (file_exists($autoload) && !defined('XHPROF_DOCROOT')) {
        define('XHPROF_DOCROOT', realpath(dirname($autoload) . '/../'));
        require_once $autoload;
    }
}

if (!defined('XHPROF_LIB_ROOT')) {
    define('XHPROF_LIB_ROOT', realpath(XHPROF_DOCROOT . '/xhprof_lib'));
}

if (!defined('XHPROF_CONFIG')) {
    define('XHPROF_CONFIG', realpath(XHPROF_LIB_ROOT . '/config.php'));
}

require XHPROF_CONFIG;

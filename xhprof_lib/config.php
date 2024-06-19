<?php
/**
 * @file This file is responsible for managing configs.
 */

ini_set('max_execution_time', 100);

// Autoloader.
$locations = [
    './',
    '../',
    '../../',
    '../../../',
];
foreach ($locations as $location) {
    $autoload = "$location/vendor/autoload.php";
    if (file_exists($autoload) && !defined('DOCROOT')) {
        define('DOCROOT', realpath(dirname($autoload) . '/../'));
        require_once $autoload;
    }
}

use Xhprof\Config\ConfigLoader;

$config = new ConfigLoader();

// @todo delete this variable and use from class only.
$_xhprof = $config->get();

// @todo find out what is this
$ignoreURLs = array();
$ignoreDomains = array();
$exceptionURLs = array();
$exceptionPostURLs = array();
$exceptionPostURLs[] = "login";

//@todo move this
//Control IPs allow you to specify which IPs will be permitted to control when profiling is on or off within your application, and view the results via the UI.
//$controlIPs = array();
//$controlIPs[] = "127.0.0.1";   // localhost, you'll want to add your own ip here
//$controlIPs[] = "::1";         // localhost IP v6
$controlIPs = false; //Disables access controls completely.

//$otherURLS = array();

//@todo check this
// ignore builtin functions and call_user_func* during profiling
//$ignoredFunctions = array('call_user_func', 'call_user_func_array', 'socket_select');

//Default weight - can be overridden by an Apache environment variable 'xhprof_weight' for domain-specific values
$weight = 100;

if ($domain_weight = getenv('xhprof_weight')) {
    $weight = $domain_weight;
}
unset($domain_weight);

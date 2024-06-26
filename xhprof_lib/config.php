<?php
/**
 * @file This file is responsible for managing configs.
 */

ini_set('max_execution_time', 100);

use Xhprof\Config\ConfigManager;

$config = new ConfigManager();

// @todo find out what is this
$ignoreURLs = array();
$ignoreDomains = array();
$exceptionURLs = array();
$exceptionPostURLs = array();
$exceptionPostURLs[] = "login";

//@todo check this
// ignore builtin functions and call_user_func* during profiling
//$ignoredFunctions = array('call_user_func', 'call_user_func_array', 'socket_select');

//Default weight - can be overridden by an Apache environment variable 'xhprof_weight' for domain-specific values
$config->set('weight', 100);

if ($domain_weight = getenv('xhprof_weight')) {
    $config->set('weight', $domain_weight);
    unset($domain_weight);
}

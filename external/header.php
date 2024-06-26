<?php

require_once getcwd() . '/../src/App.php';
//require_once XHPROF_CONFIG;

if (PHP_SAPI == 'cli') {
    $_SERVER['REMOTE_ADDR'] = null;
    $_SERVER['HTTP_HOST'] = null;
    $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
}

function getExtensionName()
{
    if (extension_loaded('tideways')) {
        return 'tideways';
    } elseif (extension_loaded('tideways_xhprof')) {
        return 'tideways_xhprof';
    } elseif (extension_loaded('xhprof')) {
        return 'xhprof';
    }
    return false;
}

$config->get('ext_name') = getExtensionName();
if ($config->get('ext_name')) {
    $flagsCpu = constant(strtoupper($config->get('ext_name')) . '_FLAGS_CPU');
    $flagsMemory = constant(strtoupper($config->get('ext_name')) . '_FLAGS_MEMORY');
    $envVarName = strtoupper($config->get('ext_name')) . '_PROFILE';
}


//I'm Magic :)
class visibilitator
{
    public static function __callstatic($name, $arguments)
    {
        $func_name = array_shift($arguments);
        //var_dump($name);
        //var_dump("arguments" ,$arguments);
        //var_dump($func_name);
        if (is_array($func_name)) {
            list($a, $b) = $func_name;
            if (count($arguments) == 0) {
                $arguments = $arguments[0];
            }
            return call_user_func_array(array($a, $b), $arguments);
            //echo "array call  -> $b ($arguments)";
        } else {
            call_user_func_array($func_name, $arguments);
        }
    }
}

// Only users from authorized IP addresses may control Profiling
if ($controlIPs === false || in_array($_SERVER['REMOTE_ADDR'], $controlIPs) || PHP_SAPI == 'cli') {
    /* Backwards Compatibility getparam check*/
    if (!isset($config->get('getparam'))) {
        $config->get('getparam') = '_profile';
    }

    if (isset($_GET[$config->get('getparam')])) {
        //Give them a cookie to hold status, and redirect back to the same page
        setcookie('_profile', $_GET[$config->get('getparam')]);
        $newURI = str_replace(array($config->get('getparam') . '=1', $config->get('getparam') . '=0'), '', $_SERVER['REQUEST_URI']);
        header("Location: $newURI");
        exit;
    }

    if (isset($_COOKIE['_profile']) && $_COOKIE['_profile']
        || PHP_SAPI == 'cli' && ((isset($_SERVER[$envVarName]) && $_SERVER[$envVarName])
            || (isset($_ENV[$envVarName]) && $_ENV[$envVarName]))) {
        $config->set('display', true);
        $config->set('doprofile', true);
        $config->set('type', 1);
    }
    unset($envVarName);
}


//Certain URLs should never have a link displayed. Think images, xml, etc. 
foreach ($exceptionURLs as $url) {
    if (stripos($_SERVER['REQUEST_URI'], $url) !== FALSE) {
        $config->set('display', false);
        header('X-XHProf-No-Display: Trueness');
        break;
    }
}
unset($exceptionURLs);

//Certain urls should have their POST data omitted. Think login forms, other privlidged info
$config->set('savepost', true);
foreach ($exceptionPostURLs as $url) {
    if (stripos($_SERVER['REQUEST_URI'], $url) !== FALSE) {
        $config->set('savepost', false);
        break;
    }
}
unset($exceptionPostURLs);

//Determine wether or not to profile this URL randomly
if ($config->get('doprofile') === false && $weight) {
    //Profile weighting, one in one hundred requests will be profiled without being specifically requested
    if (rand(1, $weight) == 1) {
        $config->set('doprofile', true);
        $config->set('type', 0);
    }
}
unset($weight);

// Certain URLS should never be profiled.
foreach ($ignoreURLs as $url) {
    if (stripos($_SERVER['REQUEST_URI'], $url) !== FALSE) {
        $config->set('doprofile', false);
        break;
    }
}
unset($ignoreURLs);

unset($url);

// Certain domains should never be profiled.
foreach ($ignoreDomains as $domain) {
    if (stripos($_SERVER['HTTP_HOST'], $domain) !== FALSE) {
        $config->set('doprofile', false);
        break;
    }
}
unset($ignoreDomains);
unset($domain);

//Display warning if extension not available
if ($config->get('ext_name') && $config->get('doprofile') === true) {
    include_once dirname(__FILE__) . '/../xhprof_lib/utils/xhprof_lib.php';
    // @todo update this
    include_once dirname(__FILE__) . '/../xhprof_lib/utils/xhprof_runs.php';
    if (isset($ignoredFunctions) && is_array($ignoredFunctions) && !empty($ignoredFunctions)) {
        call_user_func($config->get('ext_name') . '_enable', $flagsCpu + $flagsMemory, array('ignored_functions' => $ignoredFunctions));
    } else {
        call_user_func($config->get('ext_name') . '_enable', $flagsCpu + $flagsMemory);
    }
    unset($flagsCpu);
    unset($flagsMemory);

} elseif (false === $config->get('ext_name'] && $config->get('display'] === true) {
    $message = 'Warning! Unable to profile run, tideways or xhprof extension not loaded';
    trigger_error($message, E_USER_WARNING);
}
unset($flagsCpu);
unset($flagsMemory);
function xhprof_shutdown_function()
{
    require dirname(__FILE__) . '/footer.php';
}

register_shutdown_function('xhprof_shutdown_function');

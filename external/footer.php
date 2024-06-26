<?php
if (!defined('XHPROF_LIB_ROOT')) {
  define('XHPROF_LIB_ROOT', dirname(dirname(__FILE__)) . '/xhprof_lib');
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $isAjax = true;
}

if ($config->get('ext_name') && $config->get('doprofile') === true) {
    $profiler_namespace = $config->get('namespace');  // namespace for your application
    $xhprof_data = call_user_func($config->get('ext_name') . '_disable');
    $xhprof_runs = new XhprofRuns();
    $run_id = $xhprof_runs->save_run($xhprof_data, $profiler_namespace, null, $config->get());
    if ($config->get('display') === true && PHP_SAPI != 'cli' && !isset($isAjax))
    {
        // url to the XHProf UI libraries (change the host name and path)
        $profiler_url = sprintf($config->get('url').'/?run=%s&source=%s', $run_id, $profiler_namespace);
        echo '<a href="'. $profiler_url .'" target="_blank">Profiler output</a>';
    }
}

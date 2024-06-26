<?php

require_once '../src/App.php';

use Xhprof\Controller\Report;
use Xhprof\View\XhprofView;
use Xhprof\Controller\XhprofRuns;
use Xhprof\Request\Params;
use Xhprof\Utils;

$view = new XhprofView();
$utils = new Utils();

require XHPROF_CONFIG;

require_once XHPROF_LIB_ROOT . '/display/xhprof.php';

if (false !== $config->get('control_ips') && !in_array($_SERVER['REMOTE_ADDR'], $config->get('control_ips'))) {
    die("You do not have permission to view this page.");
}

// param name, its type, and default value
$valid_params = array('run' => array(XHPROF_STRING_PARAM, ''),
    'wts' => array(XHPROF_STRING_PARAM, ''),
    'func' => array(XHPROF_STRING_PARAM, ''),
    'sort' => array(XHPROF_STRING_PARAM, 'wt'), // wall time
    'run1' => array(XHPROF_STRING_PARAM, ''),
    'run2' => array(XHPROF_STRING_PARAM, ''),
    'source' => array(XHPROF_STRING_PARAM, 'xhprof'),
    'all' => array(XHPROF_UINT_PARAM, 0),
    'show_internal' => array(XHPROF_BOOL_PARAM, 'false'),
    'links' => array(XHPROF_BOOL_PARAM, 'true'),
    'func' => array(XHPROF_STRING_PARAM, ''),
    'threshold' => array(XHPROF_FLOAT_PARAM, 0.01),
);

// pull values of these params, and create named globals for each param
$params = new Params($valid_params);
$params = $params->getAll();
//var_dump($params);

/* reset params to be a array of variable names to values
   by the end of this page, param should only contain values that need
   to be preserved for the next page. unset all unwanted keys in $params.
 */
//foreach ($params as $k => $v) {
//    if (!isset($params[$k]) || !isset($v[1])) {
//        continue;
//    }
////    $params[$k] = $$k;
//    // unset key from params that are using default values. So URLs aren't
//    // ridiculously long.
//    if ($params[$k] == $v[1]) {
//        unset($params[$k]);
//    }
//}


$vbar = ' class="vbar"';
$vwbar = ' class="vwbar"';
$vwlbar = ' class="vwlbar"';
$vbbar = ' class="vbbar"';
$vrbar = ' class="vrbar"';
$vgbar = ' class="vgbar"';

$xhprof_runs_impl = new XhprofRuns();

$domainFilter = $utils->getFilter('domain_filter');
$serverFilter = $utils->getFilter('server_filter');

$domainsRS = $xhprof_runs_impl->getDistinct(array('column' => 'server name'));
$domainFilterOptions = array("None");
while ($row = $xhprof_runs_impl->getNextAssoc($domainsRS)) {
    $domainFilterOptions[] = $row['server name'];
}

$serverRS = $xhprof_runs_impl->getDistinct(array('column' => 'server_id'));
$serverFilterOptions = array("None");
while ($row = $xhprof_runs_impl->getNextAssoc($serverRS)) {
    $serverFilterOptions[] = $row['server_id'];
}

$criteria = array();
if (!is_null($domainFilter)) {
    $criteria['server name'] = $domainFilter;
}
if (!is_null($serverFilter)) {
    $criteria['server_id'] = $serverFilter;
}
$_xh_header = "";
if (isset($_GET['run1']) || isset($_GET['run'])) {
    include(XHPROF_LIB_ROOT . "/templates/header.phtml");
    $params = new Params();
//    var_dump($params);exit;
    $source = $params->get('source');
    $run = $params->get('run');
    $wts = $params->get('wts');
    $func = $params->get('func');
    $sort = $params->get('sort');
    $run1 = $params->get('run1');
    $run2 = $params->get('run2');
    $params = $params->getAll();
    $report = new Report();
//    var_dump($params, $source, $run, $wts, $func, $sort, $run1, $run2);exit;
    $report->displayXHProfReport($xhprof_runs_impl, $params, $source, $run, $wts, $func, $sort, $run1, $run2);
} elseif (isset($_GET['geturl'])) {
    $last = (isset($_GET['last'])) ? $_GET['last'] : 100;
    $last = (int)$last;
    $criteria['url'] = $_GET['geturl'];
    $criteria['limit'] = $last;
    $criteria['order by'] = 'timestamp';
    $rs = $xhprof_runs_impl->getUrlStats($criteria);
    list($header, $body) = $view->showChart($rs, true);
    $_xh_header .= $header;

    include(XHPROF_LIB_ROOT . "/templates/header.phtml");
    $rs = $xhprof_runs_impl->getRuns($criteria);
    include(XHPROF_LIB_ROOT . "/templates/emptyBody.phtml");

    $url = htmlentities($_GET['geturl'], ENT_QUOTES, "UTF-8");
    $view->displayRuns($rs, "Runs with URL: $url");
} elseif (isset($_GET['getcurl'])) {
    $last = (isset($_GET['last'])) ? $_GET['last'] : 100;
    $last = (int)$last;
    $criteria['c_url'] = $_GET['getcurl'];
    $criteria['limit'] = $last;
    $criteria['order by'] = 'timestamp';

    $rs = $xhprof_runs_impl->getUrlStats($criteria);
    list($header, $body) = $view->showChart($rs, true);
    $_xh_header .= $header;
    include(XHPROF_LIB_ROOT . "/templates/header.phtml");

    $url = htmlentities($_GET['getcurl'], ENT_QUOTES, "UTF-8");
    $rs = $xhprof_runs_impl->getRuns($criteria);
    include(XHPROF_LIB_ROOT . "/templates/emptyBody.phtml");
    $view->displayRuns($rs, "Runs with Simplified URL: $url");
} elseif (isset($_GET['getruns'])) {
    include(XHPROF_LIB_ROOT . "/templates/header.phtml");
    $days = (int)$_GET['days'];

    switch ($_GET['getruns']) {
        case "cpu":
            $load = "cpu";
            break;
        case "wt":
            $load = "wt";
            break;
        case "pmu":
            $load = "pmu";
            break;
    }

    $criteria['order by'] = $load;
    $criteria['limit'] = "500";
    $criteria['where'] = "DATE_SUB(CURDATE(), INTERVAL $days DAY) <= `timestamp`";
    $rs = $xhprof_runs_impl->getRuns($criteria);
    $view->displayRuns($rs, "Worst runs by $load");
} elseif (isset($_GET['hit'])) {
    include(XHPROF_LIB_ROOT . "/templates/header.phtml");
    $last = (isset($_GET['hit'])) ? $_GET['hit'] : 25;
    $last = (int)$last;
    $days = (isset($_GET['days'])) ? $_GET['days'] : 1;
    $days = (int)$days;
    if (isset($_GET['type']) && ($_GET['type'] === 'url' or $_GET['type'] = 'curl')) {
        $type = $_GET['type'];
    } else {
        $type = 'url';
    }

    $criteria['limit'] = $last;
    $criteria['days'] = $days;
    $criteria['type'] = $type;
    $resultSet = $xhprof_runs_impl->getHardHit($criteria);

    echo "<h1 class=\"runTitle\">Hardest Hit</h1>\n";
    echo "<table id=\"box-table-a\" class=\"tablesorter\" summary=\"Stats\"><thead><tr><th>URL</th><th>Hits</th><th class=\"{sorter: 'numeric'}\">Total Wall Time</th><th>Avg Wall Time</th></tr></thead>";
    echo "<tbody>\n";
    while ($row = $xhprof_runs_impl->getNextAssoc($resultSet)) {
        $url = urlencode($row['url']);
        $html['url'] = htmlentities($row['url'], ENT_QUOTES, 'UTF-8');
        echo "\t<tr><td><a href=\"?geturl={$url}\">{$html['url']}</a></td><td>{$row['count']}</td><td>" . number_format($row['total_wall']) . " ms</td><td>" . number_format($row['avg_wall']) . " ms</td></tr>\n";
    }
    echo "</tbody>\n";
    echo "</table>\n";
    echo <<<CODESE
    <script type="text/javascript">
    $(document).ready(function() {
      $.tablesorter.addParser({
	  id: 'pretty',
	  is: function(s) {
	      return false;
	  },
	  format: function(s) {
	      s = s.replace(/ ms/g,"");
	      return s.replace(/,/g,"");
	  },
	  // set type, either numeric or text
	  type: 'numeric'
      });
      $(function() {
	  $("table").tablesorter({
	      headers: {
		  2: {
		      sorter:'pretty'
		  },
		  3: {
		      sorter:'pretty'
		  }
	      }
	  });
      });
    });
    </script>
CODESE;
} else {
    include(XHPROF_LIB_ROOT . "/templates/header.phtml");
    $last = (isset($_GET['last'])) ? $_GET['last'] : 25;
    $last = (int)$last;
    $criteria['order by'] = "timestamp";
    $criteria['limit'] = $last;
    $rs = $xhprof_runs_impl->getRuns($criteria);
    $view->displayRuns($rs, "Last $last Runs");
}

include(XHPROF_LIB_ROOT . "/templates/footer.phtml");

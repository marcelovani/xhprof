<?php

function displayRuns($resultSet, $title = "")
{
    echo "<h1 class=\"runTitle\">$title</h1>\n";
    echo "<table id=\"box-table-a\" class=\"tablesorter\" summary=\"Stats\"><thead><tr><th>Timestamp</th><th>Cpu</th><th>Wall Time</th><th>Peak Memory Usage</th><th>URL</th><th>Simplified URL</th></tr></thead>";
    echo "<tbody>\n";
    while ($row = XHProfRuns_Default::getNextAssoc($resultSet))
    {
        $c_url = urlencode($row['c_url']);
        $url = urlencode($row['url']);
        $html['url'] = htmlentities($row['url'], ENT_QUOTES, 'UTF-8');
        $html['c_url'] = htmlentities($row['c_url'], ENT_QUOTES, 'UTF-8');
        $date = strtotime($row['timestamp']);
        $date = date('M d H:i:s', $date);
        echo "\t<tr><td><a href=\"?run={$row['id']}\">$date</a><br /><span class=\"runid\">{$row['id']}</span></td><td>{$row['cpu']}</td><td>{$row['wt']}</td><td>{$row['pmu']}</td><td><a href=\"?geturl={$url}\">{$html['url']}</a></td><td><a href=\"?getcurl={$c_url}\">{$html['c_url']}</a></td></tr>\n";
    }
    echo "</tbody>\n";
    echo "</table>\n";
    echo <<<SORTTABLE
<script type="text/javascript">
$(document).ready(function()
    {
        $("#box-table-a").tablesorter( {sortList: []} );
    }
);
</script>
SORTTABLE;
}

function printSeconds($time)
{
    $suffix = "microsecond";

    if ($time > 1000)
    {
        $time = $time / 1000;
        $suffix = "ms";

    }

    if ($time > 1000)
    {
        $time = $time / 1000;
        $suffix = "s";
    }

    if ($time > 60 && $suffix == "s")
    {
        $time = $time / 60;
        $suffix = "minutes!";
    }
    return sprintf("%.4f {$suffix}", $time);

}



function showChart($rs, $flip = false)
{

        $dataPoints = "";
        $ids = array();
        $arCPU = array();
        $arWT = array();
        $arPEAK = array();
        $arIDS = array();
        $arDateIDs = array();

         while($row = XHProfRuns_Default::getNextAssoc($rs))
        {
            $date[] = "'" . date("Y-m-d", $row['timestamp']) . "'" ;

            $arCPU[] = $row['cpu'];
            $arWT[] = $row['wt'];
            $arPEAK[] = $row['pmu'];
            $arIDS[] = $row['id'];

            $arDateIDs[] =  "'" . date("Y-m-d", $row['timestamp']) . " <br/> " . $row['id'] . "'";
        }

        $date = $flip ? array_reverse($date) : $date;
        $arCPU = $flip ? array_reverse($arCPU) : $arCPU;
        $arWT = $flip ? array_reverse($arWT) : $arWT;
        $arPEAK = $flip ? array_reverse($arPEAK) : $arPEAK;
        $arIDS = $flip ? array_reverse($arIDS) : $arIDS;
        $arDateIDs = $flip ? array_reverse($arDateIDs) : $arDateIDs;

       $dateJS = implode(", ", $date);
       $cpuJS = implode(", ", $arCPU);
       $wtJS = implode(", ", $arWT);
       $pmuJS = implode(", ", $arPEAK);
       $idsJS = implode(", ", $arIDS);
       $dateidsJS = implode(", ", $arDateIDs);


    ob_start();
      require (XHPROF_LIB_ROOT."/templates/chart.phtml");
      $stuff = ob_get_contents();
    ob_end_clean();
   return array($stuff, "<div id=\"container\" style=\"width: 1000px; height: 500px; margin: 0 auto\"></div>");
}



function getFilter($filterName)
{
    if (isset($_GET[$filterName]))
    {
      if ($_GET[$filterName] == "None")
      {
        $serverFilter = null;
        setcookie($filterName, null, 0);
      }else
      {
        setcookie($filterName, $_GET[$filterName], (time() + 60 * 60));
        $serverFilter = $_GET[$filterName];
      }
    }elseif(isset($_COOKIE[$filterName]))
    {
        $serverFilter = $_COOKIE[$filterName];
    }else
    {
      $serverFilter = null;
    }
    return $serverFilter;
}

/**
 * Helper for report url.
 *
 * @return string
 */
function xhprof_get_report_url()
{
    $uri = xhprof_parse_endpoint_uri();
    $url = xhprof_build_query_string($uri);

    return '/?' . $url;
}

/**
 * Helper to rebuild url
 *
 * @param $parsed_url
 * @param $parsed_qs
 * @return string
 */
function xhprof_build_url($parsed_qs)
{
    $base_uri = xhprof_parse_uri();

    $qs = xhprof_build_query_string($parsed_qs);
    $qs = str_replace('&', '%26', $qs);
    $url = $base_uri['path'] . '?' . $base_uri['api']['path'] . '%3F' . $qs;

    return $url;
}

/**
 * Builds a query string from uri parts.
 *
 * @param array $parts
 *   The query string arguments
 * @return string
 *   The query string
 */
function xhprof_build_query_string($parts) {
    $qs = '';
    foreach ($parts as $k => $v) {
        $qs .= sprintf('%s=%s&', $k, $v);
    }
    $qs = trim($qs, '&');

    return $qs;
}

/**
 * Helper to get the current path.
 * @return mixed
 */
function xhprof_get_request_path()
{
    $parsed_url = parse_url($_SERVER['REQUEST_URI']);

    return $parsed_url['path'];
}

/**
 * Helper to return markup for the threshold button
 *
 * @param $title
 * @param $increment
 * @param float $default
 * @return string
 */
function xhprof_get_threshold_button($title, $increment, $default = 0.01)
{
    $api_uri = xhprof_parse_endpoint_uri();
    if (isset($api_uri['threshold'])) {
        $current = (float) $api_uri['threshold'];
    } else {
        $current = $default;
    }

    $current = $current + $increment;
    if ($current <= 0) {
        $current = 0.01;
    }
    if ($current > 1) {
        $current = 1;
    }
    $api_uri['threshold'] = $current;

    $url = xhprof_build_url($api_uri);
//    $url = xhprof_build_endpoint_url($api_uri);
//    var_dump($base_uri, $api_uri, $url);
//exit;
    return "<span class=\"button form-button\"><a href=\"$url\">$current</a></span>";
//var_dump($button);exit;
    return $markup;
}

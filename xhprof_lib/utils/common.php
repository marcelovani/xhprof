<?php

function displayRuns($resultSet, $title = "")
{
    echo "<div class=\"runTitle\">$title</div>\n";
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
      require ("themes/templates/chart.tpl.php");
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
 * Helper for home button.
 *
 * @return string
 */
function get_home_button()  {
  $qs = '';
  foreach (parse_qs() as $k => $v) {
    $qs .= sprintf('%s=%s&', $k, $v);
  }
  $url = HOME . '?' . trim($qs, '&');
  $button  = '<span class="button"><a href="' . HOME . '">XH GUI</a></span>';

  return $button;
}

/**
 * On/Off button to show/hide internal php functions.
 *
 * @param $title
 * @return string
 */
function get_show_internal_button($title, $default = 0)  {
  $parsed_qs = parse_qs();
  if (!isset($parsed_qs['show_internal'])) {
    $parsed_qs['show_internal'] = $default;
  }
  if ((int) $parsed_qs['show_internal'] == 0) {
    $class = 'off';
    $parsed_qs['show_internal'] = 1;
  }
  else {
    $class = 'on';
    $parsed_qs['show_internal'] = 0;
  }
  $button = '<span class="show_internal">
  <input type="checkbox" ' . (($parsed_qs['show_internal'] == 0) ? 'checked="checked"' : '') . '/>
  <a href="' . build_url($parsed_qs) . '">' . $title . '</a>
  </span>';

  return $button;
}

/**
 * Helper to return a button
 *
 * @param $title
 * @param $increment
 * @param float $default
 * @return string
 */
function get_threshold_button($title, $increment, $default = 0.01)  {
  $parsed_qs = parse_qs();
  if (isset($parsed_qs['threshold'])) {
    $current = (float) $parsed_qs['threshold'];
  }
  else {
    $current = $default;
  }
  $current = $current + $increment;
  if ($current <= 0) {
    $current = 0.01;
  }
  if ($current > 1) {
    $current = 1;
  }
  $parsed_qs['threshold'] = $current;
  $button = '<span class="button"><a href="' . build_url($parsed_qs) . '">' . $parsed_qs['threshold'] . '</a></span>';

  return $button;
}

/**
 * Helper to parse the query string.
 *
 * @return array
 */
function parse_qs() {
  $parsed_url = parse_url($_SERVER['REQUEST_URI']);
  $qs = $parsed_url['query'];

  // Convert query string to array.
  $parsed_qs = [];
  foreach (explode('&', $qs) as $param) {
    $kv = explode('=', $param);
    if (isset($kv[1])) {
      $parsed_qs[$kv[0]] = $kv[1];
    }
  }

  return $parsed_qs;
}

/**
 * Helper to get the current path.
 * @return mixed
 */
function get_current_path() {
  $parsed_url = parse_url($_SERVER['REQUEST_URI']);
  return $parsed_url['path'];
}

/**
 * Helper to rebuild url
 *
 * @param $parsed_url
 * @param $parsed_qs
 * @return string
 */
function build_url($parsed_qs) {
  $qs = '';
  foreach ($parsed_qs as $k => $v) {
    $qs .= sprintf('%s=%s&', $k, $v);
  }
  return get_current_path() . '?' . trim($qs, '&');
}

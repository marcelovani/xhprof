<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
  <title>XHGui: Hierarchical Profiler Report</title>

  <link rel="stylesheet" href="./themes/css/xhprof.css" type="text/css"/>
  <link rel="stylesheet" href="./themes/css/tablesorter.pager.css" type="text/css"/>

  <link href='third-party/jquery/jquery.tooltip.css' rel='stylesheet' type='text/css'/>
  <link href='third-party/jquery/jquery.autocomplete.css' rel='stylesheet' type='text/css'/>
  <script src='third-party/jquery/jquery-1.7.1.min.js' type="text/javascript"></script>
  <script src='third-party/jquery/jquery.tooltip.js' type="text/javascript"></script>
  <script src='third-party/jquery/jquery.autocomplete.js' type="text/javascript"></script>
  <script src='third-party/jquery/jquery.stickytableheaders.js' type="text/javascript"></script>
  <script src='third-party/jquery/tablesorter.min.js' type="text/javascript"></script>
  <script src='third-party/jquery/tablesorter.pager.js' type="text/javascript"></script>
  <script src='third-party/highcharts/highcharts.js' type="text/javascript"></script>

  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-87733842-1', 'auto');
    ga('send', 'pageview', 'xhprof', 'home');

  </script>

  <?php
  if (isset($_xh_header)) {
    echo $_xh_header;
  }
  ?>
</head>
<body>
<div class="dash-header">
  <div class="search">
    <form name="simple_bar" method="get" action="">
      Search: <input type="text" name="run" size="25"/>
      <input type="submit" value="Go" name="submit" class="form-button"/>
    </form>
  </div>
  <div class="search">
    <form name="simple_bar" method="get" action="">
      Filter Domain:
      <select name="domain_filter">
        <?php
        foreach ($domainFilterOptions as $server) {
          if ($server == $domainFilter) {
            echo "<option value=\"{$server}\" selected=\"selected\">$server</option>\n";
          }
          else {
            echo "<option value=\"{$server}\">$server</option>\n";
          }
        }

        ?>
      </select>
      <input type="submit" value="Apply" name="submit" class="form-button"/>
    </form>
  </div>
  <div class="search">
    <form name="simple_bar" method="get" action="">
      Filter Server:
      <select name="server_filter">
        <?php
        foreach ($serverFilterOptions as $server) {
          if ($server == $serverFilter) {
            echo "<option value=\"{$server}\" selected=\"selected\">$server</option>\n";
          }
          else {
            echo "<option value=\"{$server}\">$server</option>\n";
          }
        }

        ?>
      </select>
      <input type="submit" value="Apply" name="submit" class="form-button"/>
    </form>
  </div>
  <div class="link-options">
    Last <a href="?last=25">25</a> <a href="?last=50">50</a> Runs |
    Hardest Hit <a href="?hit=50&amp;days=1">Today</a> <a href="?hit=50&amp;days=7">Past Week</a> |
    Most Expensive <a href="?getruns=cpu&amp;days=1">Today</a> <a href="?getruns=cpu&amp;days=7">Past Week</a> |
    Most Ram <a href="?getruns=pmu&amp;days=1">Today</a> <a href="?getruns=pmu&amp;days=7">Past Week</a> |
    Longest Running <a href="?getruns=wt&amp;days=1">Today</a> <a href="?getruns=wt&amp;days=7">Past Week</a>
  </div>
  <h1 class="xh-title"><a href="?">XH GUI</a></h1>
</div>
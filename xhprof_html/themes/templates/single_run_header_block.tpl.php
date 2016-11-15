<?php
        $arrayOfTotal = array();
        $i=0;
        foreach ($metrics as $metric) {

          $arrayOfTotal[$i]["label"]  = "Display run ". str_replace("<br />", " ", stat_description($metric));
          $arrayOfTotal[$i]["value"]  = number_format($totals[$metric]) .  " ". $possible_metrics[$metric][1];
          $arrayOfTotal[$i]["metric"] = $possible_metrics[$metric][1];
          $i++;
        }
        if ($display_calls) {
            $format_total= number_format($totals['ct']);
        }

        if (!isset($GLOBALS['_xhprof']['serializer']) || strtolower($GLOBALS['_xhprof']['serializer'] == 'php')) {
			$cookieArr =  unserialize($xhprof_runs_impl->run_details['cookie']);
			$getArr =  unserialize($xhprof_runs_impl->run_details['get']);
			$postArr =  unserialize($xhprof_runs_impl->run_details['post']);
		} else {
			$cookieArr =  json_decode($xhprof_runs_impl->run_details['cookie'], true);
			$getArr =  json_decode($xhprof_runs_impl->run_details['get'], true);
			$postArr =  json_decode($xhprof_runs_impl->run_details['post'], true);
		}

    //TODO This is lame
    global $comparative;
    ?>
<div id="view-center-tables">
   <div id="run-id-wrapper">
      <h2 class="run-details">RunID: <?php echo $xhprof_runs_impl->run_details['id']; ?> against <?php echo $xhprof_runs_impl->run_details['server name'];?><?php echo htmlentities($xhprof_runs_impl->run_details['url'], ENT_QUOTES, 'UTF-8'); ?> </h2>
      <a href="callgraph.php?theme=raw&run=<?php echo $xhprof_runs_impl->run_details['id']; ?>" class="callgraph">Raw List</a>
      <a href="callgraph.php?theme=viz.js&run=<?php echo $xhprof_runs_impl->run_details['id']; ?>" class="callgraph">Callgraph</a>
      <a href="callgraph.php?theme=3d&run=<?php echo $xhprof_runs_impl->run_details['id']; ?>" class="callgraph">3D Viewer</a>
      <a href="callgraph.php?theme=vr&run=<?php echo $xhprof_runs_impl->run_details['id']; ?>" class="callgraph">Stereo Viewer</a>
   </div>
    <div id="left-col">
     <div>
	<form method="get" action=""><input type="hidden" name="run1" value="<?php echo $xhprof_runs_impl->run_details['id']; ?>" />
     <table id="box-table-b">
     <thead>
        <tr><th>Stat</th><th>Exact URL</th><th>Similar URLs</th></tr>
     </thead>
     <tbody>
        <tr><td>Count</td><td><a href="?geturl=<?php echo urlencode($comparative['url']['url']);?>"><?php echo $comparative['url']['count(`id`)'];?></a></td><td><a href="?getcurl=<?php echo urlencode($comparative['c_url']['url']);?>"><?php echo $comparative['c_url']['count(`id`)'];?></a></td></tr>
        <tr><td>Min Wall Time</td><td><?php echo printSeconds($comparative['url']['min(`wt`)']);?></td><td><?php echo printSeconds($comparative['c_url']['min(`wt`)']);?></td></tr>
        <tr><td>Max Wall Time</td><td><?php echo printSeconds($comparative['url']['max(`wt`)']);?></td><td><?php echo printSeconds($comparative['c_url']['max(`wt`)']);?></td></tr>
        <tr><td>Avg Wall Time</td><td><?php echo printSeconds($comparative['url']['avg(`wt`)']);?></td><td><?php echo printSeconds($comparative['c_url']['avg(`wt`)']);?></td></tr>
        <tr><td>95% Wall Time</td><td><?php echo printSeconds($comparative['url']['95(`wt`)']);?></td><td><?php echo printSeconds($comparative['c_url']['95(`wt`)']);?></td></tr>
        <tr><td style="font-weight: bold;"><?php echo $arrayOfTotal[0]["label"]; ?></td><td style="font-weight: bold;"><?php echo $arrayOfTotal[0]["value"]; ?></td><td>&nbsp;</td></tr>
        <tr><td>Min CPU Ticks</td><td><?php echo printSeconds($comparative['url']['min(`cpu`)']);?></td><td><?php echo printSeconds($comparative['c_url']['min(`cpu`)']);?></td></tr>
        <tr><td>Max CPU Ticks</td><td><?php echo printSeconds($comparative['url']['max(`cpu`)']);?></td><td><?php echo printSeconds($comparative['c_url']['max(`cpu`)']);?></td></tr>
        <tr><td>Avg CPU Ticks</td><td><?php echo printSeconds($comparative['url']['avg(`cpu`)']);?></td><td><?php echo printSeconds($comparative['c_url']['avg(`cpu`)']);?></td></tr>
        <tr><td>95% CPU Ticks</td><td><?php echo printSeconds($comparative['url']['95(`cpu`)']);?></td><td><?php echo printSeconds($comparative['c_url']['95(`cpu`)']);?></td></tr>
        <?php if (isset($arrayOfTotal[1]["label"])) : ?>
        <tr><td style="font-weight: bold;"><?php echo $arrayOfTotal[1]["label"]; ?></td><td style="font-weight: bold;"><?php echo $arrayOfTotal[1]["value"]; ?></td><td>&nbsp;</td></tr>
        <tr><td>Min Peak Memory Usage</td><td><?php echo number_format($comparative['url']['min(`pmu`)']) . " " . $arrayOfTotal[1]["metric"];?></td><td><?php echo number_format($comparative['c_url']['min(`pmu`)']) . " " . $arrayOfTotal[1]["metric"];?></td></tr>
        <tr><td>Max Peak Memory Usage</td><td><?php echo number_format($comparative['url']['max(`pmu`)']) . " " . $arrayOfTotal[1]["metric"];?></td><td><?php echo number_format($comparative['c_url']['max(`pmu`)']) . " " . $arrayOfTotal[1]["metric"];?></td></tr>
        <tr><td>Avg Peak Memory Usage</td><td><?php echo number_format($comparative['url']['avg(`pmu`)']) . " " . $arrayOfTotal[1]["metric"];?></td><td><?php echo number_format($comparative['c_url']['avg(`pmu`)']) . " " . $arrayOfTotal[1]["metric"];?></td></tr>
        <tr><td>95% Peak Memory Usage</td><td><?php echo number_format($comparative['url']['95(`pmu`)']) . " " . $arrayOfTotal[1]["metric"];?></td><td><?php echo number_format($comparative['c_url']['95(`pmu`)']) . " " . $arrayOfTotal[1]["metric"];?></td></tr>
        <?php endif; ?>
        <?php if (isset($arrayOfTotal[2]["label"])) : ?>
        <tr><td style="font-weight: bold;"><?php echo $arrayOfTotal[2]["label"]; ?></td><td style="font-weight: bold;"><?php echo $arrayOfTotal[2]["value"]; ?></td><td>&nbsp;</td></tr>
        <?php endif; ?>
        <tr><td style="font-weight: bold;">Number of Function Calls:</td><td style="font-weight: bold;"><?php echo $format_total; ?></td><td>&nbsp;</td></tr>
        <tr>
            <td style="font-weight: bold;">Perform Delta:</td>
            <td><input type="text" name="run2" /></td>
            <td><input type="submit" value="Delta" /></td>
        </tr>
      </tbody>
    </table>
	</form>
  </div>
 </div>

 <div id="right-col">
  <div class="box-fix">
   <table class="box-tables">
    <thead>
     <tr><th>Cookie</th><th>Results</th></tr>
    </thead>
   <tbody>
  <?php
 // echo '<pre>'.print_r($cookieArr, true).'</pre>';
    foreach($cookieArr as $key=>$value){
        if (is_array($value))
        {
            $value = implode(", ", $value);
        }
        echo "<tr>\n";
        echo "\t<td>" . $key . "</td><td>" . chunk_split($value) . "</td>\n";
        echo "</tr>\n";
     }
     if(count($cookieArr) == 0)
     {
	echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>";
     }
  ?>
   </tbody>
  </table>
 </div>
 <div class="box-fix-cr">
  <table class="box-tables">
    <thead>
      <tr><th>Get</th><th>Results</th></tr>
    </thead>
   <tbody>
  <?php
 // echo '<pre>'.print_r($getArr, true).'</pre>';
    foreach($getArr as $key=>$value)
    {
        if (is_array($value))
        {
            $value = implode(", ", $value);
        }
        echo "<tr>";
        echo "<td>" . $key . "</td><td>" . $value . "</td>";
        echo "</tr>";
     }
     if(count($getArr) == 0)
     {
	echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>";
     }
  ?>
   </tbody>
  </table>
 </div>
 <div class="box-fix-cr">
   <table class="box-tables">
     <thead>
       <tr><th>Post</th><th>Results</th></tr>
     </thead>
    <tbody>
  <?php
  // echo '<pre>'.print_r($postArr, true).'</pre>';
    foreach($postArr as $key=>$value){
        if (is_array($value))
        {
            $value = implode(", ", $value);
        }
        echo "<tr>";
        echo "<td>" . $key . "</td><td>" . $value . "</td>";
        echo "</tr>";
     }
     if(count($postArr) == 0)
     {
	echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>";
     }
  ?>
    </tbody>
   </table>
  </div>
  <div id="container"></div>
 </div>
 <br style="clear: both">
</div>


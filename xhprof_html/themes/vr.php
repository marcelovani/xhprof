<!DOCTYPE html>
<html lang="en" >
<head>
  <title>VR xhprof</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
  <link rel="stylesheet" href="./themes/VR/css/main.css">
  <link rel="stylesheet" href="./themes/VR/css/gui.css">
  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-87733842-1', 'auto');
    ga('send', 'pageview', 'vr');

  </script>
</head>
<body>

<script>
var dotObjects;
<?php print getDotGraph($script); ?>
</script>

<!--<script src="../../node_modules/jquery/dist/jquery.min.js"></script>-->
<script src="../../node_modules/three/build/three.min.js"></script>
<script src="../../node_modules/leapjs/leap-0.6.4.min.js"></script>
<script src="../../node_modules/three/examples/js/libs/tween.min.js"></script>
<script src="../../node_modules/three/examples/js/effects/StereoEffect.js"></script>
<!--<script src="../../node_modules/viz.js/viz.js"></script>-->
<script src="../../node_modules/dat.gui/build/dat.gui.js"></script>
<script src="../../node_modules/mediator-js/mediator.min.js"></script>

<script data-main="/themes/VR/js/main" src="/node_modules/requirejs/require.js"></script>

<!--<script src="../themes/VR/js/main.js"></script>-->
<!--<script src="../themes/3D/js/utils.js"></script>-->
<script src="../themes/VR/js/vrGui.js"></script>
<script src="../themes/VR/js/vrPanel.js"></script>
<!--<script src="../themes/VR/js/vrControls.js"></script>-->
<!--<script src="../themes/VR/js/vrRenderer.js"></script> <!--@todo move to mediator-->-->
<!--<script src="../themes/VR/js/vrPlot.js"></script>-->
<!--<script src="../themes/VR/js/vrShapeTable.js"></script>-->
<!--<script src="../themes/VR/js/vrShapeTube.js"></script>
<script src="../themes/VR/js/vrShapeHelix.js"></script>
<script src="../themes/VR/js/vrShapeSphere.js"></script>
<script src="../themes/VR/js/vrShapeGrid.js"></script>-->


<div id="container"></div>
<div id="info"><?php echo $run; ?></div>
<div id="menu">
      <span class="group">
        <button id="callgraph" class="group-shape">CALLGRAPH</button>
        <button id="sphere" class="group-shape">SPHERE</button>
        <button id="tube" class="group-shape active">TUBE</button>
        <button id="helix" class="group-shape">HELIX</button>
        <button id="grid" class="group-shape">GRID</button>
      </span>

      <span class="group">
        <button id="3d" class="group-renderer">3D</button>
        <button id="vr" class="group-renderer active">VR</button>
      </span>

      <span class="group">
        <button id="accelerometerControls" class="group-controls">Accelerometer</button>
        <button id="leapControls" class="group-controls">LeapMotion</button>
        <button id="trackballControls" class="group-controls">Trackpad</button>
      </span>
</div>

</body>
</html>

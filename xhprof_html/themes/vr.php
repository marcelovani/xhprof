<!DOCTYPE html>
<html lang="en" >
<head>
  <title>VR xhprof</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
  <link rel="stylesheet" href="./themes/VR/css/main.css">
  <link rel="stylesheet" href="./themes/VR/css/gui.css">
  <link rel="stylesheet" href="./themes/VR/css/loader.css">
  <link rel="stylesheet" href="./themes/VR/css/led.css">
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

<script src="../../node_modules/three/build/three.min.js"></script>
<script src="../../node_modules/leapjs/leap-0.6.4.min.js"></script>
<script src="../../node_modules/three/examples/js/libs/tween.min.js"></script>
<script src="../../node_modules/three/examples/js/effects/StereoEffect.js"></script>
<script src="../../node_modules/dat.gui/build/dat.gui.js"></script>
<script src="../../node_modules/mediator-js/mediator.min.js"></script>

<script data-main="/themes/VR/js/main" src="/node_modules/requirejs/require.js"></script>

<script src="../themes/VR/js/vrGui.js"></script>
<script src="../themes/VR/js/vrPanel.js"></script>

<div id="container">
  <!-- Loader -->
  <div class="loader wrapper">
    <div class="cube">
      <b class="front">Loading</b>
      <b class="back">Loading</b>
      <b class="top">Xhprof</b>
      <b class="bottom">3D</b>
      <b class="left">Objects</b>
      <b class="right">Objects</b>
      <i class="front"></i>
      <i class="back"></i>
      <i class="top"></i>
      <i class="bottom"></i>
      <i class="left"></i>
      <i class="right"></i>
    </div>
  </div>
</div>
<div id="info"><?php echo $run; ?></div>
<div id="menu">
      <span class="group">
        <button id="callgraph" class="group-shape active">CALLGRAPH</button>
        <button id="sphere" class="group-shape">SPHERE</button>
        <button id="tube" class="group-shape">TUBE</button>
        <button id="helix" class="group-shape">HELIX</button>
        <button id="grid" class="group-shape">GRID</button>
      </span>

      <span class="group">
        <button id="3d" class="group-renderer">3D</button><span class="3dled"></span>
        <button id="vr" class="group-renderer">VR</button><span class="vrled"></span>
      </span>

      <span class="group">
        <button id="deviceOrientationControls" class="group-controls">Accelerometer</button>
        <button id="leapControls" class="group-controls">LeapMotion</button>
        <button id="trackballControls" class="group-controls">Trackpad</button>
        <button id="firstPersonControls" class="group-controls">1st Person</button>
      </span>

      <span class="group">
        <button id="R" class="group-trackball-controls">R</button>
        <button id="P" class="group-trackball-controls">P</button>
        <button id="Z" class="group-trackball-controls">Z</button>
      </span>
</div>

</body>
</html>

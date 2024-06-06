<!DOCTYPE html>
<!-- saved from url=(0065)https://threejs.org/examples/misc_controls_deviceorientation.html -->
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>three.js webgl - controls - deviceorientation</title>
  <meta name="viewport" content="user-scalable=no, initial-scale=1">
  <link rel="stylesheet" href="./themes/demo/vr/css/main.css">
  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-87733842-1', 'auto');
    ga('send', 'pageview', 'accelerometer2');

  </script>
</head>
<body>

<div id="container">
  <canvas width="2558" height="1264" style="width: 1279px; height: 632px; position: absolute; top: 0px;"></canvas>
</div>
<div id="menu">
      <span class="group">
        <button id="table" class="group-shape">CALLGRAPH</button>
        <button id="sphere" class="group-shape">SPHERE</button>
        <button id="tube" class="group-shape active">TUBE</button>
        <button id="helix" class="group-shape">HELIX</button>
        <button id="grid" class="group-shape">GRID</button>
      </span>

      <span class="group">
        <button id="3d" class="group-renderer active">3D</button>
        <button id="vr" class="group-renderer">VR</button>
      </span>

      <span class="group">
        <button id="accelerometerControls" class="group-controls">Accelerometer</button>
        <button id="leapControls" class="group-controls">LeapMotion</button>
        <button id="trackballControls" class="group-controls">Trackpad</button>
      </span>
</div>
<div id="info">
</div>
<script src="../../node_modules/jquery/dist/jquery.min.js"></script>
<script src="../../node_modules/three/build/three.min.js"></script>
<script src="../../node_modules/leapjs/leap-0.6.4.min.js"></script>
<script src="../../node_modules/three/examples/js/libs/tween.min.js"></script>
<script src="../../node_modules/three/examples/js/effects/StereoEffect.js"></script>
<script src="../../node_modules/viz.js/viz.js"></script>

<script src="../../vr/js/main.js"></script>
<script src="../3d/js/utils.js"></script>
<script src="../../vr/js/vrPanel.js"></script>
<script src="../../vr/js/vrControls.js"></script>
<script src="../../vr/js/vrRenderer.js"></script>
<script src="../../vr/js/vrPlot.js"></script>
<script src="../themes/vr/js/vrShapeTable.js"></script>
<script src="../themes/vr/js/vrShapeTube.js"></script>
<script src="../themes/vr/js/vrShapeHelix.js"></script>
<script src="../themes/vr/js/vrShapeSphere.js"></script>
<script src="../themes/vr/js/vrShapeGrid.js"></script>

<script>
  var container, camera, scene, renderer;

  var renderers = [];
  renderers['3d'] = "../../node_modules/three/examples/js/renderers/CSS3DRenderer.js";
  renderers['vr'] = "./themes/vr/js/CSS3DStereoRenderer2.js";

  var objects = [];
  var targets = { table: [], sphere: [], helix: [], tube: [], grid: [] };
  var offsetX, offsetY;
  var scale = 1.5; //@todo get from object.
  var table;

  (function () {
    "use strict";
    window.addEventListener( 'load', function () {

      scene = new THREE.Scene();
      ////////////////////////////////////////////////////////////////////////
      // DotGraph include                                                   //
      ////////////////////////////////////////////////////////////////////////
      <?php
        print getDotGraph($script);
      ?>
      // Position objects.
      var dotObjects = dotToObject2( dotPlain( dotGraph ) );

      var plot = plotObj( dotObjects );
      table = plot.table;

      // Calculate offset to center graph on the screen.
      offsetX = (plot.x2 - plot.x1) * scale / 2;
      offsetY = (plot.y2 - plot.y1) * scale / 2;

      setRenderer( '3d' );

      var animate = function () {

        window.requestAnimationFrame( animate );

        TWEEN.update();

        updateControls();

        render();

      };

      animate();

    }, false );

  })();

  function init() {
    scene = new THREE.Scene();
    renderer = getRenderer();
    addCSSObjToScene( table );

    camera = new THREE.PerspectiveCamera( 40, window.innerWidth / window.innerHeight, 1, 10000 );
    camera.position.z = 3000;

    // sphere
    vrShapeSphere();
    // helix
    vrShapeHelix();
    // tube
    vrShapeTube();
    // grid
    vrShapeGrid();

    renderer.domElement.style.position = 'absolute';
    container = document.getElementById( 'container' );
    container.appendChild( renderer.domElement );
    renderer.setSize( window.innerWidth, window.innerHeight );

    vrPannel();

    // Enable trackball controls.
    jQuery( '#trackballControls' ).click();

    // Default style.
    jQuery( '#tube' ).click();

    window.addEventListener( 'resize', onWindowResize, false );

    //animate();
  }

  function render() {
    if (typeof(renderer.render) == 'function') {
      renderer.render( scene, camera );
    }
  }

</script>
</body>
</html>
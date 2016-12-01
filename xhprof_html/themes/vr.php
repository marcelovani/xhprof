<!DOCTYPE html>
<html lang="en" >
<head>
  <title>VR xhprof</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
  <link rel="stylesheet" href="./themes/VR/css/main.css">
</head>
<body>
<script src="../../node_modules/jquery/dist/jquery.min.js"></script>
<script src="../../node_modules/three/build/three.min.js"></script>
<script src="../../node_modules/leapjs/leap-0.6.4.min.js"></script>
<script src="../../node_modules/three/examples/js/libs/tween.min.js"></script>
<script src="../../node_modules/three/examples/js/effects/StereoEffect.js"></script>
<script src="../../node_modules/viz.js/viz.js"></script>
<script src="../../node_modules/dat.gui/build/dat.gui.js"></script>

<script src="../themes/VR/js/main.js"></script>
<script src="../themes/3D/js/utils.js"></script>
<script src="../themes/VR/js/vrGui.js"></script>
<script src="../themes/VR/js/vrPanel.js"></script>
<script src="../themes/VR/js/vrControllers.js"></script>
<script src="../themes/VR/js/vrRenderer.js"></script>
<script src="../themes/VR/js/vrPlot.js"></script>
<script src="../themes/VR/js/vrShapeTable.js"></script>
<script src="../themes/VR/js/vrShapeTube.js"></script>
<script src="../themes/VR/js/vrShapeHelix.js"></script>
<script src="../themes/VR/js/vrShapeSphere.js"></script>
<script src="../themes/VR/js/vrShapeGrid.js"></script>

<div id="container"></div>
<div id="info"><?php echo $run; ?></div>
<div id="menu">
      <span class="group">
        <button id="table" class="group-shape">CALLGRAPH</button>
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
        <button id="trackpadControls" class="group-controls">Trackpad</button>
      </span>
</div>

<script>
  var camera, scene;

  var objects = [];
  var targets = { table: [], sphere: [], helix: [], tube: [], grid: [] };
  var offsetX, offsetY;

  var scale = 1.5; //@todo get from object.

  scene = new THREE.Scene();

  <?php print getDotGraph($script); ?>

  // Position objects.
  var dotObjects = dotToObject2( dotPlain( dotGraph ) );

  var plot = plotObj( dotObjects );
  var table = plot.table;

  // Calculate offset to center graph on the screen.
  var offsetX = (plot.x2 - plot.x1) * scale / 2;
  var offsetY = (plot.y2 - plot.y1) * scale / 2;

  activeRenderer = 'vr';

  updateRenderer();
  updateControllers();

  (function () {
    "use strict";
    window.addEventListener( 'load', function () {

      var animate = function () {
        updateRenderer();
        updateControllers();
        window.requestAnimationFrame( animate );
        TWEEN.update();

        updateCamera();
        //updateGui();
      };

      vrPannel();

      // Enable trackball controls.
      jQuery( '#trackpadControls' ).click();

      // Default style.
      jQuery( '#tube' ).click();

      animate();

    }, false );

    window.addEventListener( 'resize', onWindowResize, false );

  })();

  function reset() {
    jQuery('#container').html('');

    trackpadControls = {};
    leapControls = {};
    accelerometerControls = {};

    addCSSObjToScene( table );

    // sphere
    vrShapeSphere();
    // helix
    vrShapeHelix();
    // tube
    vrShapeTube();
    // grid
    vrShapeGrid();

    camera = new THREE.PerspectiveCamera( 40, window.innerWidth / window.innerHeight, 1, 10000 );
    camera.position.z = 3000;

    changeShape(activeShape, 200);
  }

</script>
</body>
</html>

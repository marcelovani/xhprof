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
<!--<script src="../../node_modules/jquery/dist/jquery.min.js"></script>-->
<script src="../../node_modules/three/build/three.min.js"></script>
<script src="../../node_modules/leapjs/leap-0.6.4.min.js"></script>
<script src="../../node_modules/three/examples/js/libs/tween.min.js"></script>
<script src="../../node_modules/three/examples/js/effects/StereoEffect.js"></script>
<!--<script src="../../node_modules/viz.js/viz.js"></script>-->
<script src="../../node_modules/dat.gui/build/dat.gui.js"></script>

<script data-main="/themes/VR/js/main" src="../../node_modules/requirejs/require.js"></script>

<!--<script src="../themes/VR/js/main.js"></script>-->
<!--<script src="../themes/3D/js/utils.js"></script>-->
<script src="../themes/VR/js/vrGui.js"></script>
<script src="../themes/VR/js/vrPanel.js"></script>
<script src="../themes/VR/js/vrControls.js"></script>
<script src="../themes/VR/js/vrRenderer.js"></script>
<!--<script src="../themes/VR/js/vrPlot.js"></script>-->
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
        <button id="trackballControls" class="group-controls">Trackpad</button>
      </span>
</div>

<script>
  var camera, scene;

  var objects = [];
  var targets = { table: [], sphere: [], helix: [], tube: [], grid: [] };
  var offsetX, offsetY;

  var scale = 1.5; //@todo get from object.

  var table; //@todo remove this global

  scene = new THREE.Scene();

  <?php print getDotGraph($script); ?>

  // Position objects.
  var dotObjects;

  require(['utils', 'vrPlot'], function (_utils, _vrPlot) {
    var utils = new _utils();
    var vrPlot = new _vrPlot();

    var dotObjects = utils.dotToObject2( utils.dotPlain( dotGraph ) );
    var plot = vrPlot.plotObj( dotObjects );
    table = plot.table;

    // Calculate offset to center graph on the screen.
    var offsetX = (plot.x2 - plot.x1) * scale / 2;
    var offsetY = (plot.y2 - plot.y1) * scale / 2;

    activeRenderer = 'vr';


    updateRenderer(); //@todo use require / mediator start
    updateControls(); //@todo use require / mediator start

    (function () {
      "use strict";
      //window.addEventListener( 'load', function () {

        var animate = function () {
          updateRenderer(); //@todo use require / mediator update
          updateControls();
          window.requestAnimationFrame( animate );
          TWEEN.update();

          //updateObjectProperties(); //@todo use require
        };

        vrPannel();

        // Enable trackball controls.
        jQuery( '#trackballControls' ).click();

        // Default style.
        jQuery( '#tube' ).click();

        animate();

      //}, false );

      window.addEventListener( 'resize', onWindowResize, false );

    })();

  });


//@todo implement this
//  var Mediator = require("mediator-js").Mediator,
//    mediator = new Mediator();
//
//  mediator.subscribe("wat", function(){
//    console.log(arguments);
//  });
//  mediator.publish("wat", 7, "hi", { one: 1 });





  function reset() {
    jQuery('#container').html('');

    trackballControls = {};
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
    camera.name = "Main";
    camera.position.z = 3000;

    changeShape(activeShape, 200);
  }

</script>
</body>
</html>

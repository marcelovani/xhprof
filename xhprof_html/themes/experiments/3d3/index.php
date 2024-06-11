<!DOCTYPE html>
<html lang="en">
<head>
  <title>Xhprof 3D</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
  <style>
    body {
      font-family: Monospace;
      background-color: #000;
      margin: 0px;
      overflow: hidden;
      color: #c8c8c8;
    }
    a, a:visited {
      color: white;
      text-decoration: none;
    }
    #engine,
    #format,
    #raw {
      display: none;
    }

  </style>
</head>
<body>
<?php include(getcwd() . '/themes/viz.js/graph_filter_options.php'); ?>

<script src="../../node_modules/jquery/dist/jquery.min.js"></script>
<script src="../../node_modules/three/build/three.js"></script>
<script src="../../node_modules/three/examples/js/controls/TrackballControls.js"></script>
<script src="../../node_modules/three/examples/js/libs/stats.min.js"></script>
<script src="../../node_modules/viz.js/viz.js"></script>
<script src="../../node_modules/leapjs/leap-0.6.4.min.js"></script>
<script src="../../node_modules/leap_three/controls/LeapTwoHandControls.js"></script>
<!--<script src="../themes/leap/LeapTwoHandControls.js"></script>-->
<script src="../../node_modules/three/examples/js/effects/StereoEffect.js"></script>
<script src="../../node_modules/three/examples/js/effects/VREffect.js"></script>
<script src="/themes/experiments/3d/js/utils.js"></script>
<script src="/themes/experiments/3d/js/leap.rigged-hand-0.1.5.min.js"></script>
<!--<script src="../../node_modules/leapjs/examples/lib/leap-plugins-0.1.6.js"></script>-->
<script src="/themes/experiments/3d/js/leap-plugins-0.1.11pre.js"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-87733842-1', 'auto');
  ga('send', 'pageview', '3D Interactive');

</script>
<script>

var camera, scene, renderer;
var container, stats;
var leapController;
var leapControls;
var trackballControls;
var transformPlugin;
var objects = [];
var plane = new THREE.Plane(); //@todo delete this
var raycaster = new THREE.Raycaster();
var mouse = new THREE.Vector2(),
  offset = new THREE.Vector3(),
  intersection = new THREE.Vector3(),
  INTERSECTED, SELECTED;

init();

function init() {

  var w     = window.innerWidth;
  var h     = window.innerHeight;

  scene = new THREE.Scene();
  camera = new THREE.PerspectiveCamera( 50, w / h, 1, 10000 );
  renderer = new THREE.WebGLRenderer();

  renderer.setSize( w , h );
  renderer.domElement.id = "scene";
  container = document.body;
  container.appendChild( renderer.domElement );

  camera.position.z = 2000;

  ////////////////////////////////////////////////////////////////////////
  // DotGraph include                                                   //
  ////////////////////////////////////////////////////////////////////////
  <?php
    print getDotGraph($digraph);
  ?>
  dotToScene(dotGraph, scene, objects);

  stats = new Stats();
  stats.dom.style.top = "20px";
  container.appendChild( stats.dom );

  renderer.domElement.addEventListener( 'mousemove', onDocumentMouseMove, false );
  renderer.domElement.addEventListener( 'mousedown', onDocumentMouseDown, false );
  renderer.domElement.addEventListener( 'mouseup', onDocumentMouseUp, false );

  //

  //@todo trackpad
  // Zoom: two fingers up and down
  // Rotation CW/CCW: two fingers rotating
  // Horizontal Rotation left<->right, up<->down: click and drag
  // Pan: Alt + click and drag

  //@todo leapmotion gestures
  // Zoom: open hand front and back
  // Rotation: two hands, lift one, lower the other
  // Horizontal Rotation left<->right, up<->down: One hand in vertical waving to the direction to go
  // Pan up/down/left/right: two hands, move to the direction to go

  leapController = new Leap.Controller();
  leapController.connect();

//  leapController.loop()
//    // note that transform must be _before_ rigged hand
//    .use('transform', {
//      quaternion: new THREE.Quaternion,
//      position: new THREE.Vector3,
//      scale: 0.5
//    })
//    //.use('playback', {recording: 'finger-tap-54fps.json.lz'})
//    .use('riggedHand', {
//      dotsMode: false,
//      parent: scene,
//      renderFn: function() {
//        render();
//        // @todo make hands stay in front of the camera.
//        stats.update();
//      }
//
//    })
//    .connect();
//  window.transformPlugin = leapController.plugins.transform;

  leapControls = new THREE.LeapTwoHandControls( camera, leapController );
  leapControls['translationSpeed'] = 10;
  leapControls['translationDecay'] = 0.3;
  leapControls['scaleDecay'] = 0.5;
  leapControls['rotationSlerp'] = 0.8;
  leapControls['rotationSpeed'] = 4;
  leapControls['pinchThreshold'] = 0.5;
  leapControls['transSmoothing'] = 0.5;
  leapControls['rotationSmoothing'] = 0.2;

  trackballControls = new THREE.TrackballControls( camera );
  trackballControls.rotateSpeed = 1.0;
  trackballControls.zoomSpeed = 1.2;
  trackballControls.panSpeed = 0.8;
  trackballControls.noZoom = false;
  trackballControls.noPan = false;
  trackballControls.staticMoving = true;
  trackballControls.dynamicDampingFactor = 0.3;

  //

  window.addEventListener( 'resize', onWindowResize, false );

  // Add stereo effect
  renderer = new THREE.StereoEffect( renderer );
  // Add VR effect
  //renderer = new THREE.VREffect( renderer );

  animate();
}

function animate() {
  render();
  stats.update();
  requestAnimationFrame( animate );
}

function render() {
  renderer.render( scene, camera );
  trackballControls.update();
  //leapControls.update();
}

function onWindowResize() {

  camera.aspect = window.innerWidth / window.innerHeight;
  camera.updateProjectionMatrix();

  renderer.setSize( window.innerWidth, window.innerHeight );

}

function onDocumentMouseMove( event ) {

  event.preventDefault();

  mouse.x = ( event.clientX / window.innerWidth ) * 2 - 1;
  mouse.y = - ( event.clientY / window.innerHeight ) * 2 + 1;

  raycaster.setFromCamera( mouse, camera );

  if ( SELECTED ) {

    if ( raycaster.ray.intersectPlane( plane, intersection ) ) {

      SELECTED.position.copy( intersection.sub( offset ) );

    }

    return;

  }

  var intersects = raycaster.intersectObjects( objects );

  if ( intersects.length > 0 ) {

    if ( INTERSECTED != intersects[ 0 ].object ) {

      if ( INTERSECTED ) INTERSECTED.material.color.setHex( INTERSECTED.currentHex );

      INTERSECTED = intersects[ 0 ].object;
      INTERSECTED.currentHex = INTERSECTED.material.color.getHex();

      plane.setFromNormalAndCoplanarPoint(
        camera.getWorldDirection( plane.normal ),
        INTERSECTED.position );

    }

    container.style.cursor = 'move';

  } else {

    if ( INTERSECTED ) INTERSECTED.material.color.setHex( INTERSECTED.currentHex );

    INTERSECTED = null;

    container.style.cursor = 'auto';

  }

}

function onDocumentMouseDown( event ) {

  event.preventDefault();

  raycaster.setFromCamera( mouse, camera );

  var intersects = raycaster.intersectObjects( objects );

  if ( intersects.length > 0 ) {

    trackballControls.enabled = false;

    SELECTED = intersects[ 0 ].object;

    if ( raycaster.ray.intersectPlane( plane, intersection ) ) {

      offset.copy( intersection ).sub( SELECTED.position );

    }

    container.style.cursor = 'move';

  }

}

function onDocumentMouseUp( event ) {

  event.preventDefault();

  trackballControls.enabled = true;

  if ( INTERSECTED ) {

    SELECTED = null;

  }

  container.style.cursor = 'auto';

}

//

</script>

<canvas width="1196" height="726" id="scene" style="width: 1196px; height: 726px;"></canvas>

</body>
</html>
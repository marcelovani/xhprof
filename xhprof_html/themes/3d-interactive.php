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
<?php include(getcwd() . '/themes/templates/graph_filter_options.php'); ?>

<script src="../../node_modules/jquery/dist/jquery.min.js"></script>
<script src="../../node_modules/three/build/three.min.js"></script>
<script src="../../node_modules/three/examples/js/controls/TrackballControls.js"></script>
<script src="../../node_modules/three/examples/js/libs/stats.min.js"></script>
<script src="../../node_modules/viz.js/viz.js"></script>
<script src="../../node_modules/leapjs/leap-0.6.4.min.js"></script>
<script src="../../node_modules/leap_three/controls/LeapTwoHandControls.js"></script>
<script src="../themes/3D/js/utils.js"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-87733842-1', 'auto');
  ga('send', 'pageview', '3D Interactive');

</script>
<script>

var container, stats;
var camera, controls, scene, renderer;
var objects = [];
var plane = new THREE.Plane();
var raycaster = new THREE.Raycaster();
var mouse = new THREE.Vector2(),
  offset = new THREE.Vector3(),
  intersection = new THREE.Vector3(),
  INTERSECTED, SELECTED;

init();
animate();

function init() {

  container = document.createElement( 'div' );
  document.body.appendChild( container );

  camera = new THREE.PerspectiveCamera( 70, window.innerWidth / window.innerHeight, 1, 10000 );
  camera.position.z = 1000;

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

  controls = new THREE.TrackballControls( camera );
  controls.rotateSpeed = 1.0;
  controls.zoomSpeed = 1.2;
  controls.panSpeed = 0.8;
  controls.noZoom = false;
  controls.noPan = false;
  controls.staticMoving = true;
  controls.dynamicDampingFactor = 0.3;

  scene = new THREE.Scene();

  scene.add( new THREE.AmbientLight( 0x505050 ) );

  var light = new THREE.SpotLight( 0xffffff, 1.5 );
  light.position.set( 0, 500, 2000 );
  light.castShadow = false;

  light.shadow = new THREE.LightShadow( new THREE.PerspectiveCamera( 50, 1, 200, 10000 ) );
  light.shadow.bias = - 0.00022;

  light.shadow.mapSize.width = 2048;
  light.shadow.mapSize.height = 2048;

  scene.add( light );

  ////////////////////////////////////////////////////////////////////////
  // DotGraph include                                                   //
  ////////////////////////////////////////////////////////////////////////
  <?php
    print getDotGraph($script);
  ?>
  dotToSene(dotGraph, scene, objects);

  renderer = new THREE.WebGLRenderer( { antialias: true } );
  renderer.setClearColor( 0xf0f0f0 );
  renderer.setPixelRatio( window.devicePixelRatio );
  renderer.setSize( window.innerWidth, window.innerHeight );
  renderer.sortObjects = false;

  renderer.shadowMap.enabled = true;
  renderer.shadowMap.type = THREE.PCFShadowMap;
  renderer.setClearColor( 0xffffff, 0);

  container.appendChild( renderer.domElement );

  var info = document.createElement( 'div' );
  info.style.position = 'absolute';
  info.style.top = '10px';
  info.style.width = '100%';
  info.style.textAlign = 'center';
  info.innerHTML = jQuery('#options' ).val();
  container.appendChild( info );

  stats = new Stats();
  stats.dom.style.top = "20px";
  container.appendChild( stats.dom );

  renderer.domElement.addEventListener( 'mousemove', onDocumentMouseMove, false );
  renderer.domElement.addEventListener( 'mousedown', onDocumentMouseDown, false );
  renderer.domElement.addEventListener( 'mouseup', onDocumentMouseUp, false );

  //

  window.addEventListener( 'resize', onWindowResize, false );
  object = {};
  dotObjects = {};
  dotGraph = {};
  dotPlain = {};
  geometry = {};
  params = {};


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

    controls.enabled = false;

    SELECTED = intersects[ 0 ].object;

    if ( raycaster.ray.intersectPlane( plane, intersection ) ) {

      offset.copy( intersection ).sub( SELECTED.position );

    }

    container.style.cursor = 'move';

  }

}

function onDocumentMouseUp( event ) {

  event.preventDefault();

  controls.enabled = true;

  if ( INTERSECTED ) {

    SELECTED = null;

  }

  container.style.cursor = 'auto';

}

//

function animate() {

  requestAnimationFrame( animate );

  render();
  stats.update();

}

function render() {

  controls.update();

  renderer.render( scene, camera );

}

</script>

</body>
</html>
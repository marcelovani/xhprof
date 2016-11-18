<!DOCTYPE html>
<html lang="en">
<head>
  <title>three.js webgl - draggable cubes</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
  <style>
    body {
      font-family: Monospace;
      background-color: #f0f0f0;
      margin: 0px;
      overflow: hidden;
    }
  </style>
</head>
<body>
<script src="../../node_modules/jquery/dist/jquery.min.js"></script>
<script src="../../node_modules/three/build/three.min.js"></script>
<script src="../../node_modules/three/examples/js/controls/TrackballControls.js"></script>
<script src="../../node_modules/three/examples/js/libs/stats.min.js"></script>
<script src="../../node_modules/viz.js/viz.js"></script>
<script src="../themes/3D/js/utils.js"></script>

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
  light.castShadow = true;

  light.shadow = new THREE.LightShadow( new THREE.PerspectiveCamera( 50, 1, 200, 10000 ) );
  light.shadow.bias = - 0.00022;

  light.shadow.mapSize.width = 2048;
  light.shadow.mapSize.height = 2048;

  scene.add( light );

  var geometry = new THREE.BoxGeometry( 40, 40, 40 );

  var dotGraph = {};
  <?php
  // Prepare graphlib-dot object.
  $script = preg_replace('/(.+)/', '\'$1\' +', $script);
  $script = preg_replace('/\}\'\s*\+/', "}'", $script);
  print 'dotGraph = ' . $script . ';';
  ?>
  // Convert to dot plain.
  var params = {
    src: dotGraph,
    options: {
      engine: 'dot',
      format: 'plain'
    }
  };
  var dotPlain = Viz(params.src, params.options);
  // Create objects.
  var objects = dotToObject2( dotPlain );
  var count = objects.length;
/*
  for ( var i = 0; i < 20; i ++ ) {
    var object = new THREE.Mesh( geometry, new THREE.MeshLambertMaterial( { color: Math.random() * 0xffffff } ) );
    object.position.x = Math.random() * 1000 - 500;
    object.position.y = Math.random() * 600 - 300;
    object.position.z = Math.random() * 800 - 400;
//    object.rotation.x = Math.random() * 2 * Math.PI;
//    object.rotation.y = Math.random() * 2 * Math.PI;
//    object.rotation.z = Math.random() * 2 * Math.PI;
    object.scale.x = Math.random() * 2 + 1;
    object.scale.y = Math.random() * 2 + 1;
    object.scale.z = Math.random() * 2 + 1;
    object.castShadow = true;
    object.receiveShadow = true;
    scene.add( object );
    objects.push( object );
  }*/
  for ( var i = 0; i < count; i ++ ) {
    var object = objects[i];
    var o = new THREE.Mesh( geometry, new THREE.MeshLambertMaterial( { color:getHexColor(object.color) } ) );
    o.position.x = object.position.x;
    o.position.y = object.position.y;
    o.position.z = object.position.z;
    //o.rotation.x = Math.random() * 2 * Math.PI;
    //o.rotation.y = Math.random() * 2 * Math.PI;
    //o.rotation.z = Math.random() * 2 * Math.PI;
    o.scale.x = o.scale.x;
    o.scale.y = o.scale.y;
    o.scale.z = o.scale.z;
    o.castShadow = true;
    o.receiveShadow = true;
    scene.add( o );
    objects.push( o );
  }
  renderer = new THREE.WebGLRenderer( { antialias: true } );
  renderer.setClearColor( 0xf0f0f0 );
  renderer.setPixelRatio( window.devicePixelRatio );
  renderer.setSize( window.innerWidth, window.innerHeight );
  renderer.sortObjects = false;

  renderer.shadowMap.enabled = true;
  renderer.shadowMap.type = THREE.PCFShadowMap;

  container.appendChild( renderer.domElement );

  var info = document.createElement( 'div' );
  info.style.position = 'absolute';
  info.style.top = '10px';
  info.style.width = '100%';
  info.style.textAlign = 'center';
  info.innerHTML = '<a href="http://threejs.org" target="_blank">three.js</a> webgl - draggable cubes';
  container.appendChild( info );

  stats = new Stats();
  container.appendChild( stats.dom );

  renderer.domElement.addEventListener( 'mousemove', onDocumentMouseMove, false );
  renderer.domElement.addEventListener( 'mousedown', onDocumentMouseDown, false );
  renderer.domElement.addEventListener( 'mouseup', onDocumentMouseUp, false );

  //

  window.addEventListener( 'resize', onWindowResize, false );
  object = {};
  objects = {};
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

    container.style.cursor = 'pointer';

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
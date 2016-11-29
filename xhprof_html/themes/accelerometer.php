<!DOCTYPE html>
<!-- saved from url=(0065)https://threejs.org/examples/misc_controls_deviceorientation.html -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>three.js webgl - controls - deviceorientation</title>
		
		<meta name="viewport" content="user-scalable=no, initial-scale=1">
		<style>
			body {
				margin: 0px;
				background-color: #000000;
				overflow: hidden;
			}

			#info {
				position: absolute;
				top: 0px; width: 100%;
				color: #ffffff;
				padding: 5px;
				font-family:Monospace;
				font-size:13px;
				font-weight: bold;
				text-align:center;
			}

			a {
				color: #ff8800;
			}
		</style>
	</head>
	<body>

		<div id="container"><canvas width="2558" height="1264" style="width: 1279px; height: 632px; position: absolute; top: 0px;"></canvas></div>

		<div id="info">
		</div>
    <script src="../../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../../node_modules/three/build/three.min.js"></script>
    <script src="../../node_modules/three/examples/js/controls/DeviceOrientationControls.js"></script>
    <script src="../../node_modules/viz.js/viz.js"></script>
    <script src="../themes/3D/js/utils.js"></script>

    <script>
			(function() {
				  "use strict";

				  window.addEventListener('load', function() {

						var container, camera, scene, renderer, accelerometerControls, geometry, mesh;
            var objects = [];

						var animate = function(){

							window.requestAnimationFrame( animate );

							accelerometerControls.update();
							renderer.render(scene, camera);

						};

						container = document.getElementById( 'container' );

						//camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 1, 1100);
            camera = new THREE.PerspectiveCamera( 40, window.innerWidth / window.innerHeight, 1, 10000 );
            //camera.position.z = 3000;

						accelerometerControls = new THREE.DeviceOrientationControls( camera );

						scene = new THREE.Scene();

//						var geometry = new THREE.SphereGeometry( 500, 16, 8 );
//						geometry.scale( - 1, 1, 1 );

//						var material = new THREE.MeshBasicMaterial( {
//							map: new THREE.TextureLoader().load( './themes/accelerometer/2294472375_24a3b8ef46_o.jpg' )
//						} );
//
//						var mesh = new THREE.Mesh( geometry, material );
//						scene.add( mesh );

            ////////////////////////////////////////////////////////////////////////
            // DotGraph include                                                   //
            ////////////////////////////////////////////////////////////////////////
            <?php
              print getDotGraph($script);
            ?>
            dotToScene(dotGraph, scene, objects);

//						var geometry = new THREE.BoxGeometry( 100, 100, 100, 4, 4, 4 );
//						var material = new THREE.MeshBasicMaterial( { color: 0xff00ff, side: THREE.BackSide, wireframe: true } );
//						var mesh = new THREE.Mesh( geometry, material );
//						scene.add( mesh );

						renderer = new THREE.WebGLRenderer();
						renderer.setPixelRatio( window.devicePixelRatio );
						renderer.setSize(window.innerWidth, window.innerHeight);
						renderer.domElement.style.position = 'absolute';
						renderer.domElement.style.top = 0;
						container.appendChild(renderer.domElement);

						window.addEventListener('resize', function() {

							camera.aspect = window.innerWidth / window.innerHeight;
							camera.updateProjectionMatrix();
							renderer.setSize( window.innerWidth, window.innerHeight );

						}, false);

						animate();

				  }, false);

			})();
		</script>

	

</body></html>
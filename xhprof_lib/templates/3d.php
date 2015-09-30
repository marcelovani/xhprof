<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<title>three.js css3d stereo - periodic table</title>
		<style>
			html, body {
				height: 100%;
			}

			body {
				background-color: #000000;
				margin: 0;
				font-family: Helvetica, sans-serif;;
				overflow: hidden;
			}

			a {
				color: #ffffff;
			}

			.element {
				width: 120px;
				height: 160px;
				box-shadow: 0px 0px 12px rgba(0,255,255,0.5);
				border: 1px solid rgba(127,255,255,0.25);
				text-align: center;
				cursor: default;
			}

			.element:hover {
				box-shadow: 0px 0px 12px rgba(0,255,255,0.75);
				border: 1px solid rgba(127,255,255,0.75);
			}

				.element .number {
					position: absolute;
					top: 20px;
					right: 20px;
					font-size: 12px;
					color: rgba(127,255,255,0.75);
				}

				.element .symbol {
					position: absolute;
					top: 40px;
					left: 0px;
					right: 0px;
					font-size: 60px;
					font-weight: bold;
					color: rgba(255,255,255,0.75);
					text-shadow: 0 0 10px rgba(0,255,255,0.95);
				}

				.element .details {
					position: absolute;
					bottom: 15px;
					left: 0px;
					right: 0px;
					font-size: 12px;
					color: black;
				}
            #messages {
                background: white;
                color: green;
            }


      html, body {
        height: 100%;
      }

      body {
        background-color: #000000;
        margin: 0;
        font-family: Helvetica, sans-serif;;
        overflow: hidden;
      }

      a {
        color: #ffffff;
      }

      #info {
        position: absolute;
        width: 100%;
        color: #ffffff;
        padding: 5px;
        font-family: Monospace;
        font-size: 13px;
        font-weight: bold;
        text-align: center;
        z-index: 1;
      }

      #menu {
        position: absolute;
        bottom: 20px;
        width: 100%;
        text-align: center;
      }

      .element {
        width: 120px;
        height: 160px;
        box-shadow: 0px 0px 12px rgba(0,255,255,0.5);
        border: 1px solid rgba(127,255,255,0.25);
        text-align: center;
        cursor: default;
      }

      .element:hover {
        box-shadow: 0px 0px 12px rgba(0,255,255,0.75);
        border: 1px solid rgba(127,255,255,0.75);
      }

      .element .number {
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 12px;
        color: rgba(127,255,255,0.75);
      }

      .element .symbol {
        position: absolute;
        top: 40px;
        left: 0px;
        right: 0px;
        font-size: 60px;
        font-weight: bold;
        color: black;
        text-shadow: 0 0 10px rgba(0,255,255,0.95);
      }

      .element .details {
        position: absolute;
        bottom: 15px;
        left: 0px;
        right: 0px;
        font-size: 12px;
        color: black;
      }

      button {
        color: rgba(127,255,255,0.75);
        background: transparent;
        outline: 1px solid rgba(127,255,255,0.75);
        border: 0px;
        padding: 5px 10px;
        cursor: pointer;
      }
      button:hover {
        background-color: rgba(0,255,255,0.5);
      }
      button:active {
        color: #000000;
        background-color: rgba(0,255,255,0.75);
      }
    </style>
	</head>
	<body>
<!--<script src="js/jquery-1.11.1.js"></script>
<script src="js/three.min.js"></script>
<script src="js/tween.min.js"></script>
<script src="js/TrackballControls.js"></script>
<script src="js/CSS3DStereoRenderer.js"></script>
<script src="js/socket.io-1.2.0.js"></script>-->

<!--<script src="http://threejs.org/build/three.min.js"></script>
<script src="http://threejs.org/examples/js/libs/tween.min.js"></script>
<script src="http://threejs.org/examples/js/controls/TrackballControls.js"></script>
<script src="http://threejs.org/examples/js/renderers/CSS3DStereoRenderer.js"></script>
<script src="https://cdn.socket.io/socket.io-1.2.0.js"></script>
<script src="http://code.jquery.com/jquery-1.11.1.js"></script>
<script src="http://cpettitt.github.io/project/graphlib-dot/v0.4.10/graphlib-dot.min.js"></script>-->

<!--<script src="http://drupal-7-32.local:8083/StereoCam/three.min.js"></script>
<script src="http://drupal-7-32.local:8083/StereoCam/tween.min.js"></script>
<script src="http://drupal-7-32.local:8083/StereoCam/TrackballControls.js"></script>
<script src="http://drupal-7-32.local:8083/StereoCam/CSS3DStereoRenderer.js"></script>
<script src="http://drupal-7-32.local:8083/StereoCam/socket.io-1.2.0.js"></script>
<script src="http://drupal-7-32.local:8083/StereoCam/jquery-1.11.1.js"></script>-->

<script src="./third-party/jquery/jquery-1.7.1.min.js"></script>
<script src="./third-party/graphlib-dot/dist/graphlib-dot.js"></script>

<script src="./third-party/three.js/examples/js/libs/stats.min.js"></script>

<script src="./third-party/three.js/build/three.js"></script>
<script src="./third-party/three.js/examples/js/controls/TrackballControls.js"></script>
<!--<script src="./third-party/three.js/examples/js/renderers/CSS3DStereoRenderer.js"></script>-->
<!--<script src="./third-party/three.js/examples/js/renderers/CSS3DRenderer.js"></script>-->

    <ul id="messages"></ul>

		<div id="container"></div>

		<script>
      <?php
        global $script;
        //print 'var dot_script = graphlibDot.parse(' . $script . ');';
        print 'var g = graphlibDot.read(' . PHP_EOL . $script . PHP_EOL . ');';
      ?>

      window.onload = function () {

        var container, stats;
        var camera, controls, scene, renderer;
        var objects = [], plane;

        var raycaster = new THREE.Raycaster();
        var mouse = new THREE.Vector2(),
          offset = new THREE.Vector3(),
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

          light.shadowCameraNear = 200;
          light.shadowCameraFar = camera.far;
          light.shadowCameraFov = 50;

          light.shadowBias = -0.00022;
          light.shadowDarkness = 0.5;

          light.shadowMapWidth = 2048;
          light.shadowMapHeight = 2048;

          scene.add( light );

          var geometry = new THREE.BoxGeometry( 40, 40, 40 );

          jQuery.each(g.nodes(), function( i, val ) {

            var node = g.node(val);

            color = 'white';
            if (typeof node.style == 'string') {
              node.style = node.style.replace(/\w+/g, '"$&"');
              var json = "{" + node.style + "}";
              var style = jQuery.parseJSON(json);
              color = style.fill;
            }

            var object = new THREE.Mesh( geometry, new THREE.MeshLambertMaterial( { color: color } ) );

            object.position.x = Math.random() * 1000 - 500;
            object.position.y = Math.random() * 600 - 300;
            object.position.z = Math.random() * 800 - 400;

            object.scale.x = node.width * 1 + 1;
            object.scale.y = node.height * 1 + 1;
            object.scale.z = node.height * 1 + 1;

            object.castShadow = true;
            object.receiveShadow = true;

            scene.add( object );

            objects.push( object );

          });

          renderer = new THREE.WebGLRenderer( { antialias: true } );
          renderer.setClearColor( 0xf0f0f0 );
          renderer.setPixelRatio( window.devicePixelRatio );
          renderer.setSize( window.innerWidth, window.innerHeight );
          renderer.sortObjects = false;

          renderer.shadowMapEnabled = true;
          renderer.shadowMapType = THREE.PCFShadowMap;

          container.appendChild( renderer.domElement );

          var info = document.createElement( 'div' );
          info.style.position = 'absolute';
          info.style.top = '10px';
          info.style.width = '100%';
          info.style.textAlign = 'center';
          info.innerHTML = '<a href="http://threejs.org" target="_blank">three.js</a> webgl - draggable cubes';
          container.appendChild( info );

          stats = new Stats();
          stats.domElement.style.position = 'absolute';
          stats.domElement.style.top = '0px';
          container.appendChild( stats.domElement );

          renderer.domElement.addEventListener( 'mousemove', onDocumentMouseMove, false );
          renderer.domElement.addEventListener( 'mousedown', onDocumentMouseDown, false );
          renderer.domElement.addEventListener( 'mouseup', onDocumentMouseUp, false );

          //

          window.addEventListener( 'resize', onWindowResize, false );

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

          //

          raycaster.setFromCamera( mouse, camera );

          if ( SELECTED ) {

            var intersects = raycaster.intersectObject( plane );
            SELECTED.position.copy( intersects[ 0 ].point.sub( offset ) );
            return;

          }

          var intersects = raycaster.intersectObjects( objects );

          if ( intersects.length > 0 ) {

            if ( INTERSECTED != intersects[ 0 ].object ) {

              if ( INTERSECTED ) INTERSECTED.material.color.setHex( INTERSECTED.currentHex );

              INTERSECTED = intersects[ 0 ].object;
              INTERSECTED.currentHex = INTERSECTED.material.color.getHex();

              plane.position.copy( INTERSECTED.position );
              plane.lookAt( camera.position );

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

          var vector = new THREE.Vector3( mouse.x, mouse.y, 0.5 ).unproject( camera );

          var raycaster = new THREE.Raycaster( camera.position, vector.sub( camera.position ).normalize() );

          var intersects = raycaster.intersectObjects( objects );

          if ( intersects.length > 0 ) {

            controls.enabled = false;

            SELECTED = intersects[ 0 ].object;

            var intersects = raycaster.intersectObject( plane );
            offset.copy( intersects[ 0 ].point ).sub( plane.position );

            container.style.cursor = 'move';

          }

        }

        function onDocumentMouseUp( event ) {

          event.preventDefault();

          controls.enabled = true;

          if ( INTERSECTED ) {

            plane.position.copy( INTERSECTED.position );

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

      }

    </script>
	</body>
</html>

define( [], function () {

	var f = function () {
		var scope = this;
		var controls = [ 'trackballControls', 'leapControls', 'deviceOrientationControls', 'firstPersonControls' ];

		var trackballControls;
		var leapController, leapControls;
		var deviceOrientationControls;
		var firstPersonControls;

		var enabledControls = [];

		this.init = function ( _objects ) {
			loaderMessage('Initializing', 'Controls');

			for ( var i = 0; i < controls.length; i++ ) {
				var controlName = controls[i];

				//@todo: create the buttons markup here, not in the html of main page.

				// Add click event on each button.
				$( "#" + controlName ).click( function () {
					buttonName = this.id;
					if ( jQuery( '#' + buttonName + '.active' ).length == 0 ) {
						scope.enable( buttonName );
					}
					else {
						scope.disable( buttonName );
					}
				} );
			}
		}

		this.enable = function ( name ) {
			if ( enabledControls.indexOf( name ) == -1 ) {
				jQuery( '#' + name ).addClass( 'active' );
				enabledControls.push( name );
			}
		};

		this.disable = function ( name ) {
			var pos = enabledControls.indexOf( name );
			if ( pos != -1 ) {
				jQuery( '#' + name ).removeClass( 'active' );
				enabledControls.splice( pos, 1 );
			}
		};

		this.getEnabled = function () {
			return enabledControls;
		};

		this.update = function () {
			var clock = new THREE.Clock();
			for ( var i = 0; i < Object.keys( enabledControls ).length; i++ ) {
				switch ( enabledControls[i] ) {
					case 'trackballControls':
						require( ['trackballControls'], function () {
							if ( trackballControls instanceof THREE.TrackballControls ) {
								trackballControls.update( clock.getDelta() );
							}
							else {
								trackballControls = new THREE.TrackballControls( camera, renderer.active().domElement );
								trackballControls.rotateSpeed = 4.5;
								trackballControls.zoomSpeed = 1.2;
								trackballControls.panSpeed = 0.3;

								trackballControls.staticMoving = false;
								trackballControls.dynamicDampingFactor = 0.2;

								trackballControls.minDistance = 0;
								trackballControls.maxDistance = Infinity;

								// Rotate / Zoom / Pan
								//@todo use click and drag to pan without having to press P
								trackballControls.keys = [ 82 /*R*/, 90 /*Z*/, 80 /*P*/ ];
								trackballControls.keyPersistentMode = true;

								trackballControls.noRotate = false;
								trackballControls.noZoom = false;
								trackballControls.noPan = false;

								trackballControls.addEventListener( 'change', renderer.render );
								trackballControls.addEventListener( 'keypress', function () {
									jQuery( '#' + this.keyPressed ).toggleClass( "active" );
								} );
							}
						} );
						break;

					case 'deviceOrientationControls':
						require( ['deviceOrientationControls'], function () {
							if ( deviceOrientationControls instanceof DeviceOrientationController ) {
								deviceOrientationControls.update();
								//@todo use .addEventListener( 'change', renderer.render) instead of render() below
								renderer.render();
							}
							else {
								deviceOrientationControls = new DeviceOrientationController( camera, renderer.active().domElement );
								deviceOrientationControls.connect();
								deviceOrientationControls.freeze = false;
								deviceOrientationControls.enableManualDrag = false;
								deviceOrientationControls.enableManualZoom = false;
								deviceOrientationControls.useQuaternions = false;
								scope.setupAccelerometerControlHandlers( deviceOrientationControls, renderer.active() );
							}
						} );
						break;

					case 'firstPersonControls': //@todo try https://github.com/mathisonian/three-first-person-controls
						require( ['firstPersonControls'], function () {
							if ( firstPersonControls instanceof THREE.FirstPersonControls ) {
								firstPersonControls.update( clock.getDelta() );
							}
							else {
								firstPersonControls = new THREE.FirstPersonControls( camera );
								firstPersonControls.movementSpeed = 1000;
								firstPersonControls.lookSpeed = 0.06;
							}
						} );
						break;

					case 'leapControls':
						var leapType = 'LeapTwoHandControls';

						switch ( leapType ) {
							case 'LeapTwoHandControls':
								require( ['LeapTwoHandControls', 'leapPlugins'], function () {
									if ( leapControls instanceof THREE.LeapTwoHandControls ) {
										leapControls.update();
										//@todo use .addEventListener( 'change', renderer.render) instead of render() below
										renderer.render();
									}
									else {
										leapController = new Leap.Controller();
										leapControls = new THREE.LeapTwoHandControls( camera, leapController);
										leapControls['translationSpeed'] = 10;
										leapControls['translationDecay'] = 0.3;
										leapControls['scaleDecay'] = 0.5;
										leapControls['rotationSlerp'] = 0.8;
										leapControls['rotationSpeed'] = 4;
										leapControls['pinchThreshold'] = 0.5;
										leapControls['transSmoothing'] = 0.5;
										leapControls['rotationSmoothing'] = 0.2;
										leapController.connect();
									}
									;
								} );
								break;

							case 'LeapSpringControls':
								require( ['LeapSpringControls', 'leapPlugins'], function () {
									if ( leapControls instanceof THREE.LeapSpringControls ) {
										leapControls.update();
										renderer.render();
									}
									else {
										leapController = new Leap.Controller();
										leapControls = new THREE.LeapSpringControls( camera, leapController, scene2 );

										leapControls.dampening = .75;
										leapControls.size = 20;
										leapControls.springConstant = 1;
										leapControls.mass = 100;
										leapControls.anchorSpeed = .1;
										leapControls.staticLength = 100;

										var geo = new THREE.IcosahedronGeometry( 5, 2 );
										var mat = new THREE.MeshNormalMaterial();

										var targetMesh = new THREE.Mesh( geo, mat );
										var anchorMesh = new THREE.Mesh( geo, mat );
										var handMesh = new THREE.Mesh( geo, mat );

										leapControls.addTargetMarker( targetMesh );
										leapControls.addAnchorMarker( anchorMesh );
										leapControls.addHandMarker( handMesh );
										leapController.connect();
									}
									;
								} );
								break;

							case 'LeapEyeLookControls':
								require( ['LeapEyeLookControls', 'leapPlugins'], function () {
									if ( leapControls instanceof THREE.LeapEyeLookControls ) {
										leapControls.update();
										renderer.render();
									}
									else {
										leapController = new Leap.Controller();
										leapControls = new THREE.LeapEyeLookControls( camera, leapController, scene2 );

										leapControls.eyeSize        = 10;
										leapControls.eyeMass        = 10;
										leapControls.eyeSpeed       = 1000;
										leapControls.eyeDampening   = 10.9

										var lookVertShader = [

											"varying vec3 vPos;",
											"void main(){",
											" vPos = (position + 1. ) * .25 +.5;",
											" vec4 mvPos = modelViewMatrix * vec4( position , 1.0 );",
											" gl_Position = projectionMatrix * mvPos;",
											"}"

										].join("\n");

										var lookFragShader = [
											"varying vec3 vPos;",
											"void main(){",
											" vec3 nPos = normalize( vPos );",
											" gl_FragColor = vec4( nPos , .5 );",
											"}"
										].join("\n");

										var lookUniforms = {
											color:{type:"c",value: new THREE.Color( 1. , .3 , .3 ) },
											map:{type:"t",value:null }
										}
										var lookGeometry = new THREE.IcosahedronGeometry( 10 , 3 );
										var lookMaterial = new THREE.ShaderMaterial({
											uniforms:       lookUniforms,
											fragmentShader: lookFragShader,
											vertexShader:   lookVertShader,
											transparent: true
										});

										lookMarker   = new THREE.Mesh( lookGeometry , lookMaterial );

										leapControls.addLookMarker( lookMarker );

										leapController.connect();
									}
									;
								} );
								break;

						}
						break;
				}
			}
		};

		this.setupAccelerometerControlHandlers = function ( _controls, _renderer ) {

			_controls.addEventListener( 'onScreenOrientationChange', function () {
				console.log( 'render' );
			} );

			// Listen for manual interaction (zoom OR rotate)


			_controls.addEventListener( 'userinteractionstart', function () {
				_renderer.domElement.style.cursor = 'move';
				console.log( 'move' );
			} );

			_controls.addEventListener( 'userinteractionend', function () {
				_renderer.domElement.style.cursor = 'default';
				console.log( 'default' );
			} );

			// Listen for manual rotate interaction

			_controls.addEventListener( 'rotatestart', function () {
				console.log( 'rotate' );
			} );

			_controls.addEventListener( 'rotateend', function () {

			} );

			// Listen for manual zoom interaction

			_controls.addEventListener( 'zoomstart', function () {
				console.log( 'zoom' );
			} );

			_controls.addEventListener( 'zoomend', function () {

			} );

			//

			// Show a simple 'canvas calibration required' dialog to user
			_controls.addEventListener( 'compassneedscalibration', function () {
				alert( 'Compass needs calibration' );
			} );

		};

	};

	return f;
} )
;

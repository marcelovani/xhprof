define( [], function () {

	var f = function () {
		var scope = this;
		var controls = [ 'trackballControls', 'leapControls', 'accelerometerControls', 'firstPersonControls' ];

		var trackballControls;
		var leapController, leapControls;
		var accelerometerControls;
		var firstPersonControls;

		var enabledControls = [];

		this.init = function(_objects) {
			for ( var i = 0; i < controls.length; i ++ ) {
				var controlName = controls[i];

				//@todo: create the buttons markup here, not in the html of main page.

				// Add click event on each button.
				$( "#" + controlName ).click(function() {
					buttonName = this.id;
					if (jQuery('#' + buttonName + '.active').length == 0) {
						scope.enable(buttonName);
					}
					else {
						scope.disable(buttonName);
					}
				});
			}
		}

		this.enable = function ( name ) {
			if (enabledControls.indexOf(name) == -1) {
				jQuery('#' + name).addClass('active');
				enabledControls.push(name);
			}
		};

		this.disable = function ( name ) {
			var pos = enabledControls.indexOf(name);
			if (pos != -1) {
				jQuery('#' + name).removeClass('active');
				enabledControls.splice(pos, 1);
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
								trackballControls.update(clock.getDelta());
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
								this.keys = [ 65 /*A*/, 83 /*S*/, 68 /*D*/ ];

								trackballControls.noRotate = false;
								trackballControls.noZoom = false;
								trackballControls.noPan = false;

								trackballControls.addEventListener( 'change', renderer.render);
							}
						} );
						break;

					case 'accelerometerControls':
						require( ['accelerometerControls'], function () {
							if ( accelerometerControls instanceof THREE.DeviceOrientationControls ) {
								//accelerometerControls.update();
							}
							else {
								accelerometerControls = new THREE.DeviceOrientationControls( camera );
								accelerometerControls.addEventListener( 'change', renderer.render);
							}
						} );
						break;

					case 'firstPersonControls':
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
				}
			}
		}

	};

	return f;
} );

/*

function xxxx( type ) {
	switch ( type ) {


		case 'leapControls':
			if ( typeof(THREE.LeapTwoHandControls) !== 'function' ) {
				loadControl( type );
			} else {
				if ( leapControls instanceof THREE.LeapTwoHandControls ) {
					leapControls.update();
				} else {
					leapController = new Leap.Controller();
					leapController.connect();
					leapControls = new THREE.LeapTwoHandControls( camera, leapController );
					leapControls['translationSpeed'] = 10;
					leapControls['translationDecay'] = 0.3;
					leapControls['scaleDecay'] = 0.5;
					leapControls['rotationSlerp'] = 0.8;
					leapControls['rotationSpeed'] = 4;
					leapControls['pinchThreshold'] = 0.5;
					leapControls['transSmoothing'] = 0.5;
					leapControls['rotationSmoothing'] = 0.2;
				}
			}
			break;

		case 'accelerometerControls':
			if ( typeof(THREE.DeviceOrientationControls) !== 'function' ) {
				loadControl( type );
			} else {
				if ( accelerometerControls instanceof THREE.DeviceOrientationControls ) {
					accelerometerControls.update();
				} else {
					accelerometerControls = new THREE.DeviceOrientationControls( camera );
					//accelerometerControls.connect();
					//camera.position.z = 1;
				}
			}
			break;
	}
}
*/

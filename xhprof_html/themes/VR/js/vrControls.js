define( [], function () {

	var f = function () {
		var trackballControls;
		var leapController, leapControls;
		var accelerometerControls;

		var enabledControls = ['trackballControls'];

		this.enableControl = function ( name ) {
			if (enabledControls[name].length == 0) {
				enabledControls.push(name);
			}
		};

		this.disableControl = function ( name ) {
			if (enabledControls[name].length > 0) {
				delete enabledControls[name];
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
								trackballControls.update(clock);
							}
							else {
								trackballControls = new THREE.TrackballControls( camera, renderer.active().domElement );
								trackballControls.rotateSpeed = 0.5;
								trackballControls.minDistance = 500;
								trackballControls.maxDistance = 6000;
								trackballControls.addEventListener( 'change', renderer.render);
							}
						} );
						break;

					case 'vr':
					default:
						require( ['CSS3DStereoRenderer'], function ( Renderer ) {
							if ( renderer instanceof THREE.CSS3DStereoRenderer ) {
								effect.render( scene, camera );
							}
							else {
								renderer = new THREE.CSS3DStereoRenderer();
								reset();
								var container = document.getElementById( 'container' );
								container.appendChild( renderer.domElement );
								renderer.setSize( window.innerWidth, window.innerHeight );

								effect = new THREE.StereoEffect( renderer );
								effect.setSize( window.innerWidth, window.innerHeight );
								effect.setEyeSeparation( 3 );
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
		case 'trackballControls':
			if ( typeof(THREE.TrackballControls) !== 'function' ) {
				loadControl( type );
			} else {
				if ( trackballControls instanceof THREE.TrackballControls ) {
					trackballControls.update();
				} else {
					trackballControls = new THREE.TrackballControls( camera, renderer.domElement );
					trackballControls.rotateSpeed = 0.5;
					trackballControls.minDistance = 500;
					trackballControls.maxDistance = 6000;
					trackballControls.addEventListener( 'change',
						mediator.publish( "wat", 7, "update", { one: 1 } )
					)
				}
			}
			break;

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

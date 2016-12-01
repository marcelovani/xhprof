var trackballControls;
var leapController, leapControls;
var accelerometerControls;

var controls = {};
controls['trackballControls'] = ["../../node_modules/three/examples/js/controls/TrackballControls.js"];
controls['accelerometerControls'] = ["../../node_modules/three/examples/js/controls/DeviceOrientationControls.js"];
controls['leapControls'] = [
	//"../../node_modules/leapjs/leap-0.6.4.min.js",
	"../../node_modules/leap_three/controls/LeapTwoHandControls.js",
	"../themes/3D/js/leap-plugins-0.1.11pre.js"
];

var enabledControls = {};
for ( var i = 0; i < controls.length; i ++ ) {
	var type = controls[i][0];
	enabledControls[type] =  false;
}

function loadControl(type) {
	for ( var i = 0; i < controls[type].length; i ++ ) {
		loadJS(controls[type][i], updateControls, document.body);
	}
}

function initControl( type ) {
	switch ( type ) {
		case 'trackballControls':
			if ( typeof(THREE.TrackballControls) !== 'function' ) {
				loadControl( type );
			} else {
				if (trackballControls instanceof THREE.TrackballControls) {
					trackballControls.update();
				} else {
					trackballControls = new THREE.TrackballControls( camera, renderer.domElement );
					trackballControls.rotateSpeed = 0.5;
					trackballControls.minDistance = 500;
					trackballControls.maxDistance = 6000;
					trackballControls.addEventListener( 'change', updateRenderer );
				}
			}
			break;

		case 'leapControls':
			if ( typeof(THREE.LeapTwoHandControls) !== 'function' ) {
				loadControl( type );
			} else {
				if (leapControls instanceof THREE.LeapTwoHandControls) {
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
				if (accelerometerControls instanceof THREE.DeviceOrientationControls) {
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

function updateControls() {
	for ( var i = 0; i < Object.keys(enabledControls).length; i ++ ) {
		var type = Object.keys(enabledControls)[i];
		if (enabledControls[type] == true) {
			initControl(type);
		}
	}
}
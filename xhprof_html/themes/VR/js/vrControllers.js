var trackpadControls;
var leapController, leapControls;
var accelerometerControls;

var controllers = {};
controllers['trackpadControls'] = ["../../node_modules/three/examples/js/controls/TrackballControls.js"];
controllers['accelerometerControls'] = ["../../node_modules/three/examples/js/controls/DeviceOrientationControls.js"];
controllers['leapControls'] = [
	//"../../node_modules/leapjs/leap-0.6.4.min.js",
	"../../node_modules/leap_three/controls/LeapTwoHandControls.js",
	"../themes/3D/js/leap-plugins-0.1.11pre.js"
];

var enabledControllers = {};
for ( var i = 0; i < controllers.length; i ++ ) {
	var type = controllers[i][0];
	enabledControllers[type] =  false;
}

function loadController(type) {
	for ( var i = 0; i < controllers[type].length; i ++ ) {
		loadJS(controllers[type][i], updateControllers, document.body);
	}
}

function initController( type ) {
	switch ( type ) {
		case 'trackpadControls':
			if ( typeof(THREE.TrackballControls) !== 'function' ) {
				loadController( type );
			} else {
				if ( typeof(trackpadControls) != 'object' ) {
					trackpadControls = new THREE.TrackballControls( camera, renderer.domElement );
					trackpadControls.rotateSpeed = 0.5;
					trackpadControls.minDistance = 500;
					trackpadControls.maxDistance = 6000;
					trackpadControls.addEventListener( 'change', render );
				} else {
					trackpadControls.update();
				}
			}
			break;

		case 'leapControls':
			if ( typeof(THREE.LeapTwoHandControls) !== 'function' ) {
				loadController( type );
			} else {
				if ( typeof(leapControls) != 'object' ) {
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
				} else {
					leapControls.update();
				}
			}
			break;

		case 'accelerometerControls':
			if ( typeof(THREE.DeviceOrientationControls) !== 'function' ) {
				loadController( type );
			} else {
				if ( typeof(accelerometerControls) != 'object' ) {
					accelerometerControls = new THREE.DeviceOrientationControls( camera );
					//accelerometerControls.connect();
				  //camera.position.z = 1;
				} else {
					accelerometerControls.update();
				}
			}
			break;
	}
}

function updateControllers() {
	for ( var i = 0; i < Object.keys(enabledControllers).length; i ++ ) {
		var type = Object.keys(enabledControllers)[i];
		if (enabledControllers[type] == true) {
			initController(type);
		}
	}
}
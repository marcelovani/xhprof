require.config( {
	baseurl: "./",
	paths: {
		'Viz': '/node_modules/viz.js/viz',
		'jQuery': '/node_modules/jquery/dist/jquery.min',
		//'mediator-js': '/node_modules/mediator-js/lib/mediator', //@todo make it work with require
		'CSS3DRenderer': '/node_modules/three/examples/js/renderers/CSS3DRenderer',
		'CSS3DStereoRenderer': '/node_modules/three/examples/js/renderers/CSS3DStereoRenderer',
		'trackballControls': '/node_modules/three/examples/js/controls/TrackballControls',
		'deviceOrientationControls': '/node_modules/threeVR/js/DeviceOrientationController',
		'firstPersonControls': '/node_modules/three/examples/js/controls/FirstPersonControls',
		'LeapTwoHandControls': '/node_modules/leap_three/controls/LeapTwoHandControls',
		'LeapSpringControls': '/node_modules/leap_three/controls/LeapSpringControls',
		'LeapEyeLookControls': '/node_modules/leap_three/controls/LeapEyeLookControls',
		'leapPlugins': '/themes/3D/js/leap-plugins-0.1.11pre',
		'utils': '/themes/VR/js/utils',
		'vrRenderer': '/themes/VR/js/vrRenderer',
		'vrControls': '/themes/VR/js/vrControls',
		'vrPlot': '/themes/VR/js/vrPlot',
		'vrTargets': '/themes/VR/js/vrTargets',
		'led': '/themes/VR/js/led'
	},
	shim: {
		'jQuery': {
			exports: 'jQuery'
		},
		'Viz': {
			exports: 'Viz'
		},
		'mediator-js': {
			exports: 'Mediator'
		}
	},
	waitSeconds: 1
} );

var camera, scene2, renderer, renderer2;

var objects = [];
var targets = { sphere: [], helix: [], tube: [], grid: [], callgraph: [] };
var camTarget;
var needsUpdate = false;

var mediator = new Mediator();

// Main scene used for CSS renderer.
scene = new THREE.Scene();
// Secondary scene used to render WebGL elements such as: Camera helper, Arrows, Leap target.
scene2 = new THREE.Scene();

var activeShape = 'callgraph';

//@testing
//require(['utils', 'vrPlot', 'mediator-js'], function (_utils, _vrPlot, Mediator) {
//	//var Mediator = require("mediator-js").Mediator,
//	var mediator = new Mediator();
//
//	mediator.subscribe("wat", function(){
//		console.log(arguments);
//	});
//	mediator.publish("wat", 7, "hi main", { one: 1 });
//});

require( [
	'utils',
	'vrPlot',
	'vrTargets',
	'vrRenderer',
	'vrControls'
	],
	function (_utils, _vrPlot, _vrTargets, Renderer, Controls ) {
		var utils = new _utils();
		var vrPlot = new _vrPlot();
		var vrTargets = new _vrTargets();

		var dotObjects = utils.dotToObject2( utils.dotPlain( dotGraph ) );

		vrPlot.plotObj( dotObjects );
		vrPlot.addCSSObjToScene( 'callgraph' );

		vrTargets.init(vrPlot.objects);

		jQuery('#container' ).html('');

		renderer = new Renderer();
		renderer.setType( '3d' );
		renderer.render();

		camera = new THREE.PerspectiveCamera( 40, window.innerWidth / window.innerHeight, 1, 10000 );
		camera.name = "Main";
		camera.position.z = 3000;

		var controls = new Controls();
		controls.init();
		controls.enable('trackballControls');
		if (window.DeviceOrientationEvent && /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
			controls.enable('deviceOrientationControls');
		}

		if ( typeof(window.mediator) == 'undefined' ) {
			window.mediator = new Mediator();

			window.mediator.subscribe( "wat", function () {
				console.log( arguments );
			} );

			window.mediator.publish( "wat", 7, "start", { one: 1 } );
		}

		//updateRenderer(); //@todo use require / mediator start
		//mediator.publish( "wat", 7, "update", { one: 1 } );
		//updateControls(); //@todo use require / mediator start
		//controls.update();

		var animate = function () {
			if (needsUpdate) renderer.render();
			//@todo only call animate when controls have changed
			//updateRenderer(); //@todo use require / mediator update
			//updateControls();//@todo use require / mediator update
			controls.update();
			//renderer.render();
			mediator.publish( "wat", 7, "update", { one: 1 } );
			window.requestAnimationFrame( animate );
			//TWEEN.update();//@todo reinstate

			//updateObjectProperties(); //@todo use require
		};

		vrPannel();

		animate();

		mediator.publish( "wat", 7, "init", { one: 1 } );

		//@todo fix this
		//window.addEventListener( 'resize', onWindowResize, false );

	} );

// @todo: do we need to call reset when changing renderers?
function reset() {
	return;
	jQuery( '#container' ).html( '' );//@todo reinstate

	camera = new THREE.PerspectiveCamera( 40, window.innerWidth / window.innerHeight, 1, 10000 );
	camera.name = "Main";
	camera.position.z = 3000;

	//changeShape( activeShape, 200 );
}

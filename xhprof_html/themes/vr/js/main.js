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
		'leapPlugins': '/themes/3d/js/leap-plugins-0.1.11pre',
		'utils': '/themes/vr/js/utils',
		'vrRenderer': '/themes/vr/js/vrRenderer',
		'vrControls': '/themes/vr/js/vrControls',
		'vrPlot': '/themes/vr/js/vrPlot',
		'vrTargets': '/themes/vr/js/vrTargets',
		'led': '/themes/vr/js/led'
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
var camTarget = false;
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

		loaderMessage('Initializing', 'Renderers');

		var utils = new _utils();
		var dotObjects = utils.dotToObject( dotGraph );
		var vrPlot = new _vrPlot();
		var vrTargets = new _vrTargets();
		var controls = new Controls();

		var initRenderer = function (type) {

			var initControls = function (type) {
				controls.init();
				controls.enable('trackballControls');
				if (window.DeviceOrientationEvent && /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
					controls.enable('deviceOrientationControls');
				}
			};

			if (typeof(renderer) != 'object') {

				renderer = new Renderer();
				renderer.setType( type );
				renderer.render();

			} else {
				if (renderer.getType() != type) {

					controls.destroy();

					renderer.destroy();

					renderer = new Renderer();
					renderer.setType( type );
					renderer.render();

					objects = [];
					targets = { sphere: [], helix: [], tube: [], grid: [], callgraph: [] };
					needsUpdate = true;
				}
			}

			initControls();

			camera = new THREE.PerspectiveCamera( 40, window.innerWidth / window.innerHeight, 1, 10000 );
			camera.name = "Main";
			camera.position.z = 3000;

			switch ( type ) {
				case '3d':
					require( [ 'CSS3DRenderer' ], function () {
						vrPlot.plotObj( dotObjects );
						vrPlot.addCSSObjToScene( 'callgraph' );
						vrTargets.init(objects);
					});
					break;

				case 'vr':
					require( [ 'CSS3DStereoRenderer' ], function () {
						vrPlot.plotObj( dotObjects );
						vrPlot.addCSSObjToScene( 'callgraph' );
						vrTargets.init(objects);
					});
					break;
			}


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
			animate();
		};

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



		initRenderer('3d');

		var button = document.getElementById( '3d' );
		button.addEventListener( 'click', function ( event ) {
			if (jQuery('#3d.active').length == 0) {
				initRenderer('3d');
			}
		}, false );

		var button = document.getElementById( 'vr' );
		button.addEventListener( 'click', function ( event ) {
			if (jQuery('#vr.active').length == 0) {
				initRenderer('vr');
			}
		}, false );

		vrPannel();



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

function loaderMessage(message1, message2) {
	jQuery(".loader b.front, .loader b.back" ).html(message1);
	jQuery(".loader b.left, .loader b.right" ).html(message2);
	console.log(message1 + ' ' + message2);
}
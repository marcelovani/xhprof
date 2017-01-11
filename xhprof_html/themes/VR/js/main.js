require.config( {
	baseurl: "./",
	paths: {
		'Viz': '/node_modules/viz.js/viz',
		'jQuery': '/node_modules/jquery/dist/jquery.min',
		//'mediator-js': '/node_modules/mediator-js/lib/mediator', //@todo make it work with require
		'CSS3DRenderer': '/node_modules/three/examples/js/renderers/CSS3DRenderer',
		'CSS3DStereoRenderer': '/node_modules/three/examples/js/renderers/CSS3DStereoRenderer',
		'trackballControls': '/node_modules/three/examples/js/controls/TrackballControls',
		'accelerometerControls': '/node_modules/three/examples/js/controls/DeviceOrientationControls',
		'leapControls': '/node_modules/leap_three/controls/LeapTwoHandControls',
		'leapPlugins': '/themes/3D/js/leap-plugins-0.1.11pre',
		'utils': '/themes/VR/js/utils',
		'vrRenderer': '/themes/VR/js/vrRenderer',
		'vrControls': '/themes/VR/js/vrControls',
		'vrPlot': '/themes/VR/js/vrPlot',
		'vrTargets': '/themes/VR/js/vrTargets'
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

var camera, scene, renderer;

var objects = [];
var targets = { sphere: [], helix: [], tube: [], grid: [], callgraph: [] };
var needsUpdate = false;

//var table; //@todo remove these global
//var offsetX, offsetY;

//var mediator;
var mediator = new Mediator();

scene = new THREE.Scene();

var activeShape = 'tube';

//todo: remove. moved to vtTargets
function changeShape( shape, duration ) {
	console.log('change shape ' + shape);
	activeShape = shape;
	jQuery( '.group-shape' ).removeClass( 'active' );
	jQuery( '#' + shape ).addClass( 'active' );
	transform2( targets[shape], duration );
}

//todo: remove. moved to vtTargets
/**
 * Temporary solution until transform() is fixed.
 * @param targets
 */
function transform2( targets ) {

	for ( var i = 0; i < objects.length; i++ ) {

		var object = objects[ i ];
		var target = targets[ i ];
		object.position.copy( target.position );
		object.rotation.copy( target.rotation );
		renderer.render();

	}

}

function onWindowResize() {

	camera.aspect = window.innerWidth / window.innerHeight;
	camera.updateProjectionMatrix();

	effect.setSize( window.innerWidth, window.innerHeight );

	mediator.publish( "wat", 7, "update", { one: 1 } );

	//updateRenderer();

}

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
	function ( _utils, _vrPlot, _vrTargets, Renderer, Controls ) {
		var utils = new _utils();
		var vrPlot = new _vrPlot();
		var vrTargets = new _vrTargets();

		var dotObjects = utils.dotToObject2( utils.dotPlain( dotGraph ) );

		vrPlot.plotObj( dotObjects );
		vrPlot.addCSSObjToScene( 'callgraph' );

		vrTargets.init(vrPlot.objects);
//		vrTargets.callgraph(vrPlot.objects);
//		vrTargets.tube(vrPlot.objects);
//		vrTargets.helix(vrPlot.objects);
//		vrTargets.sphere(vrPlot.objects);
//		vrTargets.grid(vrPlot.objects);

		//vrPlot.changeShape( targets, activeShape, 200 );//temporarily commented out

		renderer = new Renderer();
		renderer.setType( '3d' );
		renderer.render();

		var controls = new Controls();

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

		//(function () {
		//	"use strict";
		//window.addEventListener( 'load', function () {

		var animate = function () {
			if (needsUpdate) renderer.render();
			//@todo only call animate when controls have changed
			//updateRenderer(); //@todo use require / mediator update
			//updateControls();//@todo use require / mediator update
			controls.update();
			//renderer.render();
			mediator.publish( "wat", 7, "update", { one: 1 } );
			window.requestAnimationFrame( animate );
			TWEEN.update();//@todo reinstate

			//updateObjectProperties(); //@todo use require
		};

		vrPannel();

// Enable trackball controls.
		//jQuery( '#trackballControls' ).click();//@todo temporary disabled

// Default style.
		//jQuery( '#tube' ).click();

		animate();

		mediator.publish( "wat", 7, "init", { one: 1 } );

//}, false );

		window.addEventListener( 'resize', onWindowResize, false );

		//})();

	} );

// @todo: do we need to call reset when changing renderers?
function reset() {
	jQuery( '#container' ).html( '' );//@todo reinstate

	//trackballControls = {};
	//leapControls = {};
	//accelerometerControls = {};

// @todo: bug: when we switch render, the size of objects doubles
	//addCSSObjToScene( table ); //@todo moving this to main(), need to move whole reset() to main()

// sphere
	//vrShapeSphere();
// helix
	//vrShapeHelix();
// tube
	//vrShapeTube();
// grid
	//vrShapeGrid();

	camera = new THREE.PerspectiveCamera( 40, window.innerWidth / window.innerHeight, 1, 10000 );
	camera.name = "Main";
	camera.position.z = 3000;

	//changeShape( activeShape, 200 );
}


/**
 *
 * WebGL With Three.js - Lesson 1
 * http://www.script-tutorials.com/webgl-with-three-js-lesson-1/
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 *
 * Copyright 2013, Script Tutorials
 * http://www.script-tutorials.com/
 */

// The graphlib object.
var worker;
var result;

var g = {};

var colors = [
	0xFF62B0,
	0x9A03FE,
	0x62D0FF,
	0x48FB0D,
	0xDFA800,
	0xC27E3A,
	0x990099,
	0x9669FE,
	0x23819C,
	0x01F33E,
	0xB6BA18,
	0xFF800D,
	0xB96F6F,
	0x4A9586
];
var particleLight;

var lesson1 = {
	scene: null,
	camera: null,
	renderer: null,
	container: null,
	controls: null,
	clock: null,
	stats: null,

	init: function ( data ) { // Initialization
		console.log('Init');
		// create main scene
		this.scene = new THREE.Scene();

		var SCREEN_WIDTH = window.innerWidth,
			SCREEN_HEIGHT = window.innerHeight;

		// prepare camera
		var VIEW_ANGLE = 65, ASPECT = SCREEN_WIDTH / SCREEN_HEIGHT, NEAR = 1, FAR = 10000;
		this.camera = new THREE.PerspectiveCamera( VIEW_ANGLE, ASPECT, NEAR, FAR );
		this.scene.add( this.camera );
		this.camera.position.set( -1000, 1000, 0 );
		this.camera.lookAt( new THREE.Vector3( 0, 0, 0 ) );

		// prepare renderer
		this.renderer = new THREE.WebGLRenderer( {antialias: true, alpha: false} );
		this.renderer.setSize( SCREEN_WIDTH, SCREEN_HEIGHT );
		this.renderer.setClearColor( 0xffffff );

//		this.renderer.shadowMapEnabled = true;
//		this.renderer.shadowMapSoft = true;

		// prepare container
		this.container = document.createElement( 'div' );
		document.body.appendChild( this.container );
		this.container.appendChild( this.renderer.domElement );

		// events
		THREEx.WindowResize( this.renderer, this.camera );

		// prepare controls (OrbitControls)
		this.controls = new THREE.OrbitControls( this.camera, this.renderer.domElement );
		this.controls.target = new THREE.Vector3( 0, 0, 0 );

		// prepare clock
		this.clock = new THREE.Clock();

		// prepare stats
		this.stats = new Stats();
		this.stats.domElement.style.position = 'absolute';
		this.stats.domElement.style.bottom = '0px';
		this.stats.domElement.style.zIndex = 10;
		this.container.appendChild( this.stats.domElement );

		// add directional light
		var dLight = new THREE.DirectionalLight( 0xffff66 );
		dLight.position.set( 1, 1000, 1 );
		dLight.castShadow = true;
		dLight.shadowCameraVisible = true;
		dLight.shadowDarkness = 0.2;
		dLight.shadowMapWidth = dLight.shadowMapHeight = 1000;
		this.scene.add( dLight );

		// add particle of light
		particleLight = new THREE.Mesh(
			new THREE.SphereGeometry( 10, 10, 10 ),
			new THREE.MeshBasicMaterial( { color: 0x44ff44 } )
		);
		particleLight.position = dLight.position;
		this.scene.add( particleLight );

		// add simple ground
//		var groundGeometry = new THREE.PlaneGeometry( 1000, 1000, 1, 1 );
//		ground = new THREE.Mesh( groundGeometry, new THREE.MeshLambertMaterial( {
//			color: 0xfefefe
//		} ) );
//		ground.position.y = 0;
//		ground.rotation.x = -Math.PI / 2;
//		ground.receiveShadow = true;
//		this.scene.add( ground );

		var objects = dotToObject(data);
		//console.log( objects );
		var container = this.scene;
		jQuery.each( objects, function ( i, o ) {
			container.add( o );
		});
	},
	getRandColor: function () {
		return colors[Math.floor( Math.random() * colors.length )];
	}
};

function CSVToArray( strData, strDelimiter ){
	// Check to see if the delimiter is defined. If not,
	// then default to comma.
	strDelimiter = (strDelimiter || ",");

	// Create a regular expression to parse the CSV values.
	var objPattern = new RegExp(
		(
			// Delimiters.
			"(\\" + strDelimiter + "|\\r?\\n|\\r|^)" +

				// Quoted fields.
				"(?:\"([^\"]*(?:\"\"[^\"]*)*)\"|" +

				// Standard fields.
				"([^\"\\" + strDelimiter + "\\r\\n]*))"
			),
		"gi"
	);


	// Create an array to hold our data. Give the array
	// a default empty first row.
	var arrData = [[]];

	// Create an array to hold our individual pattern
	// matching groups.
	var arrMatches = null;


	// Keep looping over the regular expression matches
	// until we can no longer find a match.
	while (arrMatches = objPattern.exec( strData )){

		// Get the delimiter that was found.
		var strMatchedDelimiter = arrMatches[ 1 ];

		// Check to see if the given delimiter has a length
		// (is not the start of string) and if it matches
		// field delimiter. If id does not, then we know
		// that this delimiter is a row delimiter.
		if (
			strMatchedDelimiter.length &&
				(strMatchedDelimiter != strDelimiter)
			){

			// Since we have reached a new row of data,
			// add an empty row to our data array.
			arrData.push( [] );

		}


		// Now that we have our delimiter out of the way,
		// let's check to see which kind of value we
		// captured (quoted or unquoted).
		if (arrMatches[ 2 ]){

			// We found a quoted value. When we capture
			// this value, unescape any double quotes.
			var strMatchedValue = arrMatches[ 2 ].replace(
				new RegExp( "\"\"", "g" ),
				"\""
			);

		} else {

			// We found a non-quoted value.
			var strMatchedValue = arrMatches[ 3 ];

		}


		// Now that we have our value string, let's add
		// it to the data array.
		arrData[ arrData.length - 1 ].push( strMatchedValue );
	}

	// Return the parsed data.
	return( arrData );
}

function getMaterial(color) {
	switch ( color ) {
		case 'white':
			var material = new THREE.MeshLambertMaterial( { color: 0xFFFFFF } );

		case 'black':
			var material = new THREE.MeshLambertMaterial( { color: 0x000000 } );

		case 'red':
			var material = new THREE.MeshLambertMaterial( { color: 0xFF0000 } );

			break;
		case 'blue':
			var material = new THREE.MeshLambertMaterial( { color: 0x0000FF } );

			break;
		case 'green':
			var material = new THREE.MeshLambertMaterial( { color: 0x00FF00 } );

			break;
		case 'yellow':
			var material = new THREE.MeshLambertMaterial( { color: 0xFFF407 } );

			break;
		default:
			var material = new THREE.MeshLambertMaterial( { color: 0xfafafa } );
	}

	return material;
}

// Parse Dot script and return objects.
function dotToObject( data ) {
	//console.log(data);

  var graph = {};
	var objects = [];
  var lines = (CSVToArray(data, ' '));
	//console.log(lines);

	jQuery.each( lines, function ( i, c ) {
		switch(c[0]) {
			case "graph":
				graph.scale = c[1];
				graph.scale = 10; //@todo use dynamic value
				graph.width = c[2];
				graph.height = c[3];
				break;

			case "node":
				var shape = 'box';
				var name = c[1];
				var x = c[2] * graph.scale;
				var y = c[3] * graph.scale;
				var width = c[4] * graph.scale;
				var height = c[5] * graph.scale;
				var label = c[6];
				var style = c[7];
				var shape = c[8];
				var color = c[9];
				var fillcolor = c[10];
				//console.log('found node name ' + name + ' x ' + x + ' y ' + y + ' w ' + width + ' h ' + height + ' la ' + label + ' st ' + style + ' c ' + fillcolor);

				switch ( shape ) {
					case 'box':
					case 'octagon':
					default:
						var z = 1;
						var geometry = new THREE.CubeGeometry( x, y, x );
						break;
				}

				var material = getMaterial(fillcolor);

				break;

			case "XXXXedge":
				var l = c.length;
				var tail = c[1];
				var head = c[2];
				var n = c[3];

				// Need to collect the x, y pairs from here to
				//var width = c[4];
				//var height = c[5];
				// here

				var label = c[l -5];
				var x1 = c[l - 4] * graph.scale;
				var y1 = c[l - 3] * graph.scale;
				var style = c[l - 2];
				var color = c[l - 1];
				//console.log('found edge tail ' + tail + ' head ' + head + ' n ' + n + ' label ' + label + ' x1 ' + x1 + ' y1 ' + y1 + ' st ' + style + ' c ' + color);

				var material = getMaterial(color);
				break;
		}

		var o = new THREE.Mesh( geometry, material );
		o.position.x = x * graph.scale;
		o.position.y = y * graph.scale;
		o.position.z = z * graph.scale;
		//o.castShadow = o.receiveShadow = true;
		objects.push(o);
	});

	return objects;
}

// Animate the scene
function animate() {
	requestAnimationFrame( animate );
	render();
	update();
}

// Update controls and stats
function update() {
	lesson1.controls.update( lesson1.clock.getDelta() );
	lesson1.stats.update();

	// smoothly move the particleLight
	//var timer = Date.now() * 0.000025;
	//particleLight.position.x = Math.sin( timer * 5 ) * 300;
	//particleLight.position.z = Math.cos( timer * 5 ) * 300;
}

// Render the scene
function render() {
	if ( lesson1.renderer ) {
		lesson1.renderer.render( lesson1.scene, lesson1.camera );
	}
}

// Initialize lesson on page load
function initializeLesson( graph ) {
	worker = new Worker( "./themes/3D/js/worker.js" );

	worker.onmessage = function ( e ) {
		result = e.data;
		console.log(result);

		lesson1.init( result );
		animate();
	}

	worker.onerror = function ( e ) {
		var message = e.message === undefined ? "An error occurred while processing the graph input." : e.message;
		alert( message );
		console.error( e );
		e.preventDefault();
	}

	var params = {
		src: graph,
		options: {
			engine: 'dot',
			format: 'plain'
		}
	};

	worker.postMessage( params );
}
/*

 if ( window.addEventListener )
 window.addEventListener( 'load', initializeLesson, false );
 else if ( window.attachEvent )
 window.attachEvent( 'onload', initializeLesson );
 else window.onload = initializeLesson;
 */

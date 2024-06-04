
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

function getRgbColor(color) {
	return new THREE.Color(getHexColor(color));
}

function getHexColor(color) {
	switch ( color ) {
		case 'white':
			return 0xFFFFFF;

		case 'lightgrey':
			return 0xEFEFEF;

		case 'black':
			return 0x000000;

		case 'red':
			return 0xFF0000;

		case 'blue':
			return 0x0000FF;

		case 'green':
			return 0x00FF00;

		case 'yellow':
			return 0xFFF407;

		default:
			return 0xfafafa;
	}
}

// Parse Dot script and return objects.
function dotToObject( dotGraph ) {
	//console.log(dotGraph);

	var graph = {};
	var objects = [];
	var lines = (CSVToArray(dotGraph, ' '));
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

// This is a copy of dotToObject. The difference is that it returns plain objects.
function dotToObject2( dotGraph ) {
	//console.log(dotGraph);

	var graph = {};
	var objects = [];
	var lines = (CSVToArray(dotGraph, ' '));

	jQuery.each( lines, function ( i, c ) {
		var o = {};

		switch(c[0]) {
			case "graph":
				graph.scale = c[1];
				graph.scale = 30; //@todo use dynamic value
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
						break;
				}

				o.label = label;
				o.shape = shape;
				o.color = fillcolor;
				o.position = {
					x: x,
					y: y,
					z: z
				};
				o.scale = {
					x: 1,
					y: 1,
					z: 1
				}

				// Change the size depending on the color.
				switch ( fillcolor ) {
					case 'yellow':
						o.scale.x = o.scale.x * 2;
						o.scale.y = o.scale.y * 2;
						o.scale.z = o.scale.z * 2;
						break;
					case 'red':
						o.scale.x = o.scale.x * 4;
						o.scale.y = o.scale.y * 4;
						o.scale.z = o.scale.z * 4;
						break;
					default:
				}

				break;

			case "edge":
				var shape = 'line';
				var l = c.length;
				var tail = c[1];
				var head = c[2];
				var n = c[3];
				var z = 1;

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

				o.shape = shape;
				o.color = color;
				o.position = {
					x: x1,
					y: y1,
					z: 1
				};
				o.scale = {
					x: graph.scale,
					y: graph.scale,
					z: graph.scale
				}

				break;
		}

		if (typeof (o.position) != 'undefined') {
			objects.push(o);
		}
	});

	return objects;
}

// Convert to dot plain.
function dotPlain(dotGraph) {
	// Convert to dot plain.
	var params = {
		src: dotGraph,
		options: {
			engine: 'dot',
			format: 'plain'
		}
	};

	return Viz(params.src, params.options);
}

function dotToScene(dotGraph, scene, objects) {
	var cube = new THREE.BoxGeometry( 50, 50, 50 );
	var material = new THREE.MeshBasicMaterial( { color: 0xff0000, wireframe: false } );

	// Create dotObjects.
	var dotObjects = dotToObject2( dotPlain(dotGraph) );
	var count = dotObjects.length;

	var example = false;
	if (example) {
		for( var i = 0; i < 300; i++ ) {
			m = material.clone();
			m.color.setRGB( Math.random() + 0.1, Math.random() + 0.1, Math.random() + 0.1 );

			var mesh = new THREE.Mesh( cube , m );
			mesh.position.x = (Math.random() - .5 ) * 5000;
			mesh.position.y = (Math.random() - .5 ) * 5000;
			mesh.position.z = (Math.random() - .5 ) * 5000;
			scene.add( mesh );
		}
	}
	else {
		for (var i = 0; i < count; i++) {
			var object = dotObjects[i];

			switch (object.shape) {
				case 'line':
					//@todo implement this
					//var geometry = line;
					continue;
					break;
				case 'box':
					var geometry = cube;
					break;
				case 'octagon':
					//@todo implement this
					//var geometry = octagon;
					continue;
					break;
				default:
			}

			if (typeof(geometry) == 'object') {
				m = material.clone();
				m.color = getRgbColor(object.color);

				var mesh = new THREE.Mesh( geometry , m );
				// @todo calculate offsets based on screen dimensions and zoom.
				offsetX = 500;
				offsetY = 100;
				offsetZ = Math.random() * 400 * Math.PI;
				mesh.position.x = object.position.x - offsetX;
				mesh.position.y = object.position.y - offsetY;
				mesh.position.z = object.position.z - offsetZ;
				//mesh.rotation.x = Math.random() * 2 * Math.PI;
				//mesh.rotation.y = Math.random() * 2 * Math.PI;
				//mesh.rotation.z = Math.random() * 2 * Math.PI;
				mesh.scale.x = object.scale.x;
				mesh.scale.y = object.scale.y;
				mesh.scale.z = object.scale.z;
				mesh.castShadow = true;
				mesh.receiveShadow = true;
				scene.add(mesh);
				objects.push(mesh);
			}
		}

		object = {};
		dotObjects = {};
		dotGraph = {};
		dotPlain = {};
		geometry = {};
		params = {};
	}

}

function resetCamera(){
	window.camera.position.set( 0 , 0 , 1000 );
	window.camera.lookAt( new THREE.Vector3());
}

function loadJS(url, implementationCode, location) {

	//url is URL of external file, implementationCode is the code
	//to be called from the file, location is the location to
	//insert the <script> element

	//usage:
	// var loadJS = loadJS(url, implementationCode, location);
	// var yourCodeToBeCalled = function(){
	//   your code goes here
	// }
	// loadJS('yourcode.js', yourCodeToBeCalled, document.body);


	var scriptTag = document.createElement('script');
	scriptTag.src = url;

	scriptTag.onload = implementationCode;
	scriptTag.onreadystatechange = implementationCode;

	location.appendChild(scriptTag);
};

function removeJS(filename){
	var tags = document.getElementsByTagName('script');
	for (var i = tags.length; i >= 0; i--){ //search backwards within nodelist for matching elements to remove
		if (tags[i] && tags[i].getAttribute('src') != null && tags[i].getAttribute('src').indexOf(filename) != -1)
			tags[i].parentNode.removeChild(tags[i]); //remove element by calling parentNode.removeChild()
	}
}

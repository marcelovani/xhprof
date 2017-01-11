define( ['jQuery'], function ( jQuery ) {

	var f = function () {
		var scope = this;
		var objects = [];

		//var targets = { sphere: [], helix: [], tube: [], grid: [], callgraph: [] };

		/**
		 * Calls every target function to position the objects.
		 * Creates click event on buttons.
		 *
		 * @param objects
		 */
		this.init = function(_objects) {
			objects = _objects;
			for ( var i = 0; i < Object.keys(targets).length; i ++ ) {
				var targetName = Object.keys(targets)[i];
				var callback = eval("this." + targetName);
				callback();

				//@todo: create the buttons markup here, not in the html of main page.

				// Add click event on each button.
				$( "#" + targetName ).click(function() {
					activeShape = this.id;
					jQuery( '.group-shape' ).removeClass( 'active' );
					jQuery( '#' + activeShape ).addClass( 'active' );
					//changeShape(this.id, 1000);//todo call mediator changeshape
					scope.transform( targets[activeShape], 200 );
				});
			}
		}

		this.transform = function( targets ) {
			for ( var i = 0; i < objects.length; i++ ) {
				var object = objects[ i ];
				var target = targets[ i ];
				object.position.copy( target.position );
				object.rotation.copy( target.rotation );
				needsUpdate = true; //@todo call mediator update
			}
		}

		this.callgraph = function() {
			// This is pushed by default in vrPlot
		}

		this.grid = function() {
			for ( var i = 0; i < objects.length; i ++ ) {

				var object = new THREE.Object3D();

				object.position.x = ( ( i % 5 ) * 400 ) - 800;
				object.position.y = ( - ( Math.floor( i / 5 ) % 5 ) * 400 ) + 800;
				object.position.z = ( Math.floor( i / 25 ) ) * 1000 - 2000;

				targets.grid.push( object );

			}
		}

		this.helix = function() {
			var vector = new THREE.Vector3();

			for ( var i = 0, l = objects.length; i < l; i ++ ) {

				var phi = i * 0.175 + Math.PI;

				var object = new THREE.Object3D();

				object.position.x = 900 * Math.sin( phi );
				object.position.y = - ( i * 8 ) + 450;
				object.position.z = 900 * Math.cos( phi );

				// Look at the camera.
//					vector.x = camera.position.x;
//					vector.y = camera.position.y;
//					vector.z = camera.position.z;
				vector.x = object.position.x * 2;
				vector.y = object.position.y;
				vector.z = object.position.z * 2;

				object.lookAt( vector );

				targets.helix.push( object );

			}
		}

		this.sphere = function() {
			var vector = new THREE.Vector3();

			for ( var i = 0, l = objects.length; i < l; i ++ ) {

				var phi = Math.acos( -1 + ( 2 * i ) / l );
				var theta = Math.sqrt( l * Math.PI ) * phi;

				var object = new THREE.Object3D();

				object.position.x = 800 * Math.cos( theta ) * Math.sin( phi );
				object.position.y = 800 * Math.sin( theta ) * Math.sin( phi );
				object.position.z = 800 * Math.cos( phi );

				vector.copy( object.position ).multiplyScalar( 2 );

				object.lookAt( vector );

				targets.sphere.push( object );

			}
		}

		this.tube = function() {
			var vector = new THREE.Vector3();

			for ( var i = 0, l = objects.length; i < l; i ++ ) {

				var phi = i * 0.175 + Math.PI;

				var object = new THREE.Object3D();

				//object.position.x = 900 * Math.sin( phi );
				object.position.x = objects[i].position.x;
				object.position.y = objects[i].position.y;
				//object.position.y = - ( i * 8 ) + 450;
				object.position.z = 900 * Math.cos( phi );

				// Look at the camera.
				//					vector.x = camera.position.x;
				//					vector.y = camera.position.y;
				//					vector.z = camera.position.z;
				vector.x = object.position.x * 2;
				vector.y = object.position.y;
				vector.z = object.position.z * 2;

				object.lookAt( vector );

				targets.tube.push( object );

			}
		}
	};

	return f;
} );

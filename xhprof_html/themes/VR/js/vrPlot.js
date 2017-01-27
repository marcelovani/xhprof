define( [], function () {
	var f = function () {
		var scope = this;
		var scale = 1.5; //@todo get from object
		var offsetX, offsetY;
		var table = [];
		var _objects = [];

		this.plotObj = function ( dotObjects ) {
			loaderMessage('Plotting', 'objects');

			var total = dotObjects.length;
			if ( total > 0 ) {
				var x1 = 0;
				var x2 = 0;
				var y1 = 0;
				var y2 = 0;
				var table = [];
				for ( var i = 0; i < total; i++ ) {
					if ( dotObjects[i].shape == 'box' || dotObjects[i].shape == 'octagon' ) {
						// Store the smallest x
						if ( dotObjects[i].position.x < x1 ) {
							x1 = dotObjects[i].position.x;
						}
						// Store the greatest x
						if ( dotObjects[i].position.x > x2 ) {
							x2 = dotObjects[i].position.x;
						}
						// Store the smallest y
						if ( dotObjects[i].position.y < y1 ) {
							y1 = dotObjects[i].position.y;
						}
						// Store the greatest y
						if ( dotObjects[i].position.x > y2 ) {
							y2 = dotObjects[i].position.y;
						}
						label = dotObjects[i].label.split( "\n" );
						position = dotObjects[i].position;

						table[i] = [];
						table[i][0] = label[0].substr( 0, 14 );
						table[i][1] = label.join( '<br/>' );
						var color = '';
						switch ( dotObjects[i].color ) {
							case 'red':
								color = '255,0,0';
								break;
							case 'yellow':
								color = '251,255,32';
								break;
							default:
								color = '0,127,127';
						}
						table[i][2] = color;
						table[i][3] = position.x;
						table[i][4] = position.y;
					}
				}
				this.table = table;

				// Calculate offset to center graph on the screen.
				offsetX = (x2 - x1) * scale / 2;
				offsetY = (y2 - y1) * scale / 2;
			}
		}

		this.addCSSObjToScene = function ( _target ) {
			loaderMessage('Objects', 'to scene');

			// Used with WegGl renderer
			var cube = new THREE.BoxGeometry( 50, 50, 50 );
			var material = new THREE.MeshBasicMaterial( { color: 0x00ff00, wireframe: true } );

			for ( var i = 0; i < this.table.length; i++ ) {

				var element = document.createElement( 'div' );
				element.className = 'element';

				var color = 'rgba(' + this.table[i][2] + ', 0.8)';
				element.style.backgroundColor = color;

				var number = document.createElement( 'div' );
				number.className = 'number';
				number.textContent = (i) + 1;
				element.appendChild( number );

				var symbol = document.createElement( 'div' );
				symbol.className = 'symbol';
				symbol.textContent = this.table[i][0];
				element.appendChild( symbol );

				var details = document.createElement( 'div' );
				details.className = 'details';
				details.innerHTML = this.table[i][1];
				element.appendChild( details );

				// Used by webgl only
				//var object = new THREE.Mesh( cube , material );

				var cssObj = new THREE.CSS3DObject( element );
				cssObj.position.x = this.table[i][3] * scale - offsetX;
				cssObj.position.y = this.table[i][4] * scale - offsetY;
				cssObj.position.z = Math.random() * scale * 500 - 1000;
				scene.add( cssObj );

				objects.push( cssObj ); //todo change to this.objects

				// Push to default target;
				var object = new THREE.Object3D();
				object.position.x = cssObj.position.x;
				object.position.y = cssObj.position.y;
				object.position.z = cssObj.position.z;

				targets[_target].push( object ); //todo move to vrtargets

			}
//			this.objects = _objects; //todo remove line
		}
	};
	return f;

} );

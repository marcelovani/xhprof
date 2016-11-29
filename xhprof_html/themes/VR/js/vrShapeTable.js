function addCSSObjToScene(table) {
	// Used with WegGl renderer
	var cube = new THREE.BoxGeometry( 50, 50, 50 );
	var material = new THREE.MeshBasicMaterial( { color: 0x00ff00, wireframe: true } );

	for ( var i = 0; i < table.length; i ++ ) {

		var element = document.createElement( 'div' );
		element.className = 'element';

		var color = 'rgba(' + table[i][2] + ', 0.8)';
		element.style.backgroundColor = color;

		var number = document.createElement( 'div' );
		number.className = 'number';
		number.textContent = (i) + 1;
		element.appendChild( number );

		var symbol = document.createElement( 'div' );
		symbol.className = 'symbol';
		symbol.textContent = table[i][0];
		element.appendChild( symbol );

		var details = document.createElement( 'div' );
		details.className = 'details';
		details.innerHTML = table[i][1];
		element.appendChild( details );

		// Used by webgl only
		// var object = new THREE.Mesh( cube , material );

		// Used with CSS renderer
		var cssObj = new THREE.CSS3DObject( element );
		cssObj.position.x = table[i][3] * scale - offsetX;
		cssObj.position.y = table[i][4] * scale - offsetY;
		cssObj.position.z = Math.random() * scale * 500 - 1000;
		scene.add( cssObj );

		objects.push( cssObj );

		//

		var object = new THREE.Object3D();
		object.position.x = cssObj.position.x;
		object.position.y = cssObj.position.y;
		object.position.z = cssObj.position.z;

		targets.table.push( object );

	}
}

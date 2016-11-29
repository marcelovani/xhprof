function vrShapeTube() {
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

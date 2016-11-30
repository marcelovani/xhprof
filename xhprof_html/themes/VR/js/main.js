var activeShape = 'table';

function changeShape(shape, duration) {
	activeShape = shape;
	jQuery('.group-shape').removeClass('active');
	jQuery('#' + shape).addClass('active');
	transform( targets[shape], duration );
}

function transform( targets, duration ) {

	TWEEN.removeAll();

	for ( var i = 0; i < objects.length; i++ ) {

		var object = objects[ i ];
		var target = targets[ i ];

		new TWEEN.Tween( object.position )
			.to( { x: target.position.x, y: target.position.y, z: target.position.z }, Math.random() * duration + duration )
			.easing( TWEEN.Easing.Exponential.InOut )
			.start();

		new TWEEN.Tween( object.rotation )
			.to( { x: target.rotation.x, y: target.rotation.y, z: target.rotation.z }, Math.random() * duration + duration )
			.easing( TWEEN.Easing.Exponential.InOut )
			.start();

	}

	new TWEEN.Tween( this )
		.to( {}, duration * 2 )
		.onUpdate( updateRenderer )
		.start();

}

function onWindowResize() {

	camera.aspect = window.innerWidth / window.innerHeight;
	camera.updateProjectionMatrix();

	renderer.setSize( window.innerWidth, window.innerHeight );

	updateRenderer();

}

function cameraGui() {
	obj = new THREE.PerspectiveCamera( 50, window.innerWidth / window.innerHeight, 1, 10000 );
	var gui = new dat.gui.GUI();

	gui.remember(obj);
	gui.remember(obj.position);
	gui.remember(obj.rotation);
	gui.remember(obj.quaternion);

	gui.add( obj, 'zoom', 1, 100           ).onChange(function(v){UC( this,v )});
	gui.add( obj , 'aspect' , 1 , 4        ).onChange(function(v){UC( this,v )});
	gui.add( obj , 'fov' , 1 , 100         ).onChange(function(v){UC( this,v )});
	gui.add( obj , 'filmGauge' , 1 , 60    ).onChange(function(v){UC( this,v )});
	gui.add( obj , 'filmOffset' , 0 , 10   ).onChange(function(v){UC( this,v )});
	gui.add( obj , 'far' , 1 , 20000       ).onChange(function(v){UC( this,v )});
	gui.add( obj , 'focus' , 1 , 20        ).onChange(function(v){UC( this,v )});
	gui.add( obj , 'zoom' , 0 , 10         ).onChange(function(v){UC( this,v )});
	var position = gui.addFolder('Position');
	position.add( obj.position , 'x', 1, 1000);
	position.add( obj.position , 'y', 1, 1000);
	position.add( obj.position , 'z', 1, 1000);
	var rotation = gui.addFolder('Rotation');
	rotation.add( obj.rotation , 'x', 1, 1000);
	rotation.add( obj.rotation , 'y', 1, 1000);
	rotation.add( obj.rotation , 'z', 1, 1000);
	var quaternion = gui.addFolder('Quaternion');
	quaternion.add( obj.quaternion , 'x', 1, 1000);
	quaternion.add( obj.quaternion , 'y', 1, 1000);
	quaternion.add( obj.quaternion , 'z', 1, 1000);
}

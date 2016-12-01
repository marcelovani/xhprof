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

var guiData = null;
var guiDataChanged = false;

function cameraGui() {
	obj = new THREE.PerspectiveCamera( 50, window.innerWidth / window.innerHeight, 1, 10000 );
	guiData = obj;
	var gui = new dat.gui.GUI();

	gui.remember(obj);
	gui.remember(obj.position);
	gui.remember(obj.rotation);
	gui.remember(obj.quaternion);
	gui.remember(obj.scale);

	gui.add( obj , 'aspect' , 1 , 10       ).onChange(function(v){updateGuiData( this,v )});
	gui.add( obj , 'fov' , 1 , 1000        ).onChange(function(v){updateGuiData( this,v )});
	gui.add( obj , 'filmGauge' , 1 , 60    ).onChange(function(v){updateGuiData( this,v )});
	gui.add( obj , 'filmOffset' , 0 , 10   ).onChange(function(v){updateGuiData( this,v )});
	gui.add( obj , 'near' , 1 , 20         ).onChange(function(v){updateGuiData( this,v )});
	gui.add( obj , 'far' , 1 , 20000       ).onChange(function(v){updateGuiData( this,v )});
	gui.add( obj , 'focus' , 1 , 20        ).onChange(function(v){updateGuiData( this,v )});
	gui.add( obj , 'zoom' , 0 , 10         ).onChange(function(v){updateGuiData( this,v )});
	var position = gui.addFolder('Position');
	position.add( obj.position , 'x', -1000, 1000).onChange(function(v){updateGuiDataPosition( this,v )});
	position.add( obj.position , 'y', -1000, 1000).onChange(function(v){updateGuiDataPosition( this,v )});
	position.add( obj.position , 'z', -1000, 1000).onChange(function(v){updateGuiDataPosition( this,v )});
	var rotation = gui.addFolder('Rotation');
	rotation.add( obj.rotation , 'x', 0, 100).onChange(function(v){updateGuiDataRotation( this,v )});
	rotation.add( obj.rotation , 'y', 0, 100).onChange(function(v){updateGuiDataRotation( this,v )});
	rotation.add( obj.rotation , 'z', 0, 100).onChange(function(v){updateGuiDataRotation( this,v )});
	var up = gui.addFolder('Up');
	up.add( obj.up , 'x', 0, 10).onChange(function(v){updateGuiDataUp( this,v )});
	up.add( obj.up , 'y', 0, 10).onChange(function(v){updateGuiDataUp( this,v )});
	up.add( obj.up , 'z', 0, 10).onChange(function(v){updateGuiDataUp( this,v )});
	var quaternion = gui.addFolder('Quaternion');
	quaternion.add( obj.quaternion , '_w', 0, 10).onChange(function(v){updateGuiDataQuaternion( this,v )});
	quaternion.add( obj.quaternion , '_x', 0, 10).onChange(function(v){updateGuiDataQuaternion( this,v )});
	quaternion.add( obj.quaternion , '_y', 0, 10).onChange(function(v){updateGuiDataQuaternion( this,v )});
	quaternion.add( obj.quaternion , '_z', 0, 10).onChange(function(v){updateGuiDataQuaternion( this,v )});
	var scale = gui.addFolder('Scale');
	scale.add( obj.scale , 'x', 1, 1000).onChange(function(v){updateGuiDataScale( this,v )});
	scale.add( obj.scale , 'y', 1, 1000).onChange(function(v){updateGuiDataScale( this,v )});
	scale.add( obj.scale , 'z', 1, 1000).onChange(function(v){updateGuiDataScale( this,v )});
}

function updateCamera() {
	if (guiData && guiDataChanged) {
		camera.aspect = guiData.aspect;
		camera.fov = guiData.fov;
		camera.filmGauge = guiData.filmGauge;
		camera.filmOffset = guiData.filmOffset;
		camera.far = guiData.far;
		camera.near = guiData.near;
		camera.focus = guiData.focus;
		camera.zoom = guiData.zoom;
		camera.position.x = guiData.position.x;
		camera.position.z = guiData.position.z;
		camera.position.y = guiData.position.y;
		camera.up.x = guiData.up.x;
		camera.up.z = guiData.up.z;
		camera.up.y = guiData.up.y;
		camera.rotation.x = guiData.rotation.x;
		camera.rotation.z = guiData.rotation.z;
		camera.rotation.y = guiData.rotation.y;
		camera.quaternion._w = guiData.quaternion._w;
		camera.quaternion._x = guiData.quaternion._x;
		camera.quaternion._z = guiData.quaternion._z;
		camera.quaternion._y = guiData.quaternion._y;
		camera.scale.x = guiData.scale.x;
		camera.scale.z = guiData.scale.z;
		camera.scale.y = guiData.scale.y;
		guiDataChanged = false;
	}
}

function updateGuiData( change ,  value ){
	guiData[change.property] = value;
	guiDataChanged = true;
}
function updateGuiDataPosition( change ,  value ){
	guiData.position[change.property] = value;
	guiDataChanged = true;
}
function updateGuiDataUp( change ,  value ){
	guiData.up[change.property] = value;
	guiDataChanged = true;
}
function updateGuiDataRotation( change ,  value ){
	guiData.rotation[change.property] = value;
	guiDataChanged = true;
}
function updateGuiDataQuaternion( change ,  value ){
	guiData.quaternion[change.property] = value;
	guiDataChanged = true;
}
function updateGuiDataScale( change ,  value ){
	guiData.scale[change.property] = value;
	guiDataChanged = true;
}

var guiData = null;
var guiDataChanged = false;
var gui;

function initGui() {
	//obj = new THREE.PerspectiveCamera( 50, window.innerWidth / window.innerHeight, 1, 10000 );
	// @Todo: make it work with enabled controllers, not sure which one should come first
	if (typeof(trackballControls.object) != 'object') {
		return;
	}
	var object = trackballControls.object;
	guiData = object;
	gui = new dat.gui.GUI();

	gui.remember(object);
	gui.remember(object.position);
	gui.remember(object.rotation);
	gui.remember(object.quaternion);
	gui.remember(object.scale);

	gui.add( object , 'aspect' , 1 , 10       ).onChange(function(v){updateGuiData( this,v )});
	gui.add( object , 'fov' , 1 , 1000        ).onChange(function(v){updateGuiData( this,v )});
	gui.add( object , 'filmGauge' , 1 , 60    ).onChange(function(v){updateGuiData( this,v )});
	gui.add( object , 'filmOffset' , 0 , 10   ).onChange(function(v){updateGuiData( this,v )});
	gui.add( object , 'near' , 1 , 20         ).onChange(function(v){updateGuiData( this,v )});
	gui.add( object , 'far' , 1 , 20000       ).onChange(function(v){updateGuiData( this,v )});
	gui.add( object , 'focus' , 1 , 20        ).onChange(function(v){updateGuiData( this,v )});
	gui.add( object , 'zoom' , 0 , 10         ).onChange(function(v){updateGuiData( this,v )});
	var position = gui.addFolder('Position');
	position.add( object.position , 'x', -10000, 10000).onChange(function(v){updateGuiDataPosition( this,v )});
	position.add( object.position , 'y', -10000, 10000).onChange(function(v){updateGuiDataPosition( this,v )});
	position.add( object.position , 'z', -10000, 10000).onChange(function(v){updateGuiDataPosition( this,v )});
	var rotation = gui.addFolder('Rotation');
	rotation.add( object.rotation , 'x', 0, 100).onChange(function(v){updateGuiDataRotation( this,v )});
	rotation.add( object.rotation , 'y', 0, 100).onChange(function(v){updateGuiDataRotation( this,v )});
	rotation.add( object.rotation , 'z', 0, 100).onChange(function(v){updateGuiDataRotation( this,v )});
	var up = gui.addFolder('Up');
	up.add( object.up , 'x', 0, 10).onChange(function(v){updateGuiDataUp( this,v )});
	up.add( object.up , 'y', 0, 10).onChange(function(v){updateGuiDataUp( this,v )});
	up.add( object.up , 'z', 0, 10).onChange(function(v){updateGuiDataUp( this,v )});
	var quaternion = gui.addFolder('Quaternion');
	quaternion.add( object.quaternion , '_w', 0, 10).onChange(function(v){updateGuiDataQuaternion( this,v )});
	quaternion.add( object.quaternion , '_x', 0, 10).onChange(function(v){updateGuiDataQuaternion( this,v )});
	quaternion.add( object.quaternion , '_y', 0, 10).onChange(function(v){updateGuiDataQuaternion( this,v )});
	quaternion.add( object.quaternion , '_z', 0, 10).onChange(function(v){updateGuiDataQuaternion( this,v )});
	var scale = gui.addFolder('Scale');
	scale.add( object.scale , 'x', 1, 1000).onChange(function(v){updateGuiDataScale( this,v )});
	scale.add( object.scale , 'y', 1, 1000).onChange(function(v){updateGuiDataScale( this,v )});
	scale.add( object.scale , 'z', 1, 1000).onChange(function(v){updateGuiDataScale( this,v )});
}

function updateGuiControl() {
	if (guiData && guiDataChanged) {
		//@todo make it work with other controllers
		var guiTarget = trackballControls.object;
		guiTarget.aspect = guiData.aspect;
		guiTarget.fov = guiData.fov;
		guiTarget.filmGauge = guiData.filmGauge;
		guiTarget.filmOffset = guiData.filmOffset;
		guiTarget.far = guiData.far;
		guiTarget.near = guiData.near;
		guiTarget.focus = guiData.focus;
		guiTarget.zoom = guiData.zoom;
		guiTarget.position.x = guiData.position.x;
		guiTarget.position.z = guiData.position.z;
		guiTarget.position.y = guiData.position.y;
		guiTarget.up.x = guiData.up.x;
		guiTarget.up.z = guiData.up.z;
		guiTarget.up.y = guiData.up.y;
		guiTarget.rotation.x = guiData.rotation.x;
		guiTarget.rotation.z = guiData.rotation.z;
		guiTarget.rotation.y = guiData.rotation.y;
		guiTarget.quaternion._w = guiData.quaternion._w;
		guiTarget.quaternion._x = guiData.quaternion._x;
		guiTarget.quaternion._z = guiData.quaternion._z;
		guiTarget.quaternion._y = guiData.quaternion._y;
		guiTarget.scale.x = guiData.scale.x;
		guiTarget.scale.z = guiData.scale.z;
		guiTarget.scale.y = guiData.scale.y;
		guiDataChanged = false;
	}
}

function updateGui() {
	if (typeof(gui) != 'object') {
		initGui();
	} else {
		jQuery.each( gui.__controllers, function ( i, controller ) {
			var property = controller.property;
			var value = controller.getValue();
			if (trackballControls.object[property] != value) {
				guiDataChanged = false;
				//@todo make it work with other controls
				controller.setValue(trackballControls.object[property]);
			}
		});
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

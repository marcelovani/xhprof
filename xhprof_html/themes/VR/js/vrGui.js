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

	object.separation = 6;

	gui.add( object , 'separation' , -10 , 10 ).onFinishChange(function(v){updateGuiData( this,v )});
	gui.add( object , 'aspect' , 1 , 10       ).onFinishChange(function(v){updateGuiData( this,v )});
	gui.add( object , 'fov' , 1 , 1000        ).onFinishChange(function(v){updateGuiData( this,v )});
	gui.add( object , 'filmGauge' , 1 , 60    ).onFinishChange(function(v){updateGuiData( this,v )});
	gui.add( object , 'filmOffset' , 0 , 10   ).onFinishChange(function(v){updateGuiData( this,v )});
	gui.add( object , 'near' , 1 , 20         ).onFinishChange(function(v){updateGuiData( this,v )});
	gui.add( object , 'far' , 1 , 20000       ).onFinishChange(function(v){updateGuiData( this,v )});
	gui.add( object , 'focus' , 1 , 20        ).onFinishChange(function(v){updateGuiData( this,v )});
	gui.add( object , 'zoom' , 0 , 10         ).onFinishChange(function(v){updateGuiData( this,v )});
	var position = gui.addFolder('position');
	position.add( object.position , 'x', -10000, 10000).onFinishChange(function(v){updateGuiDataPosition( this,v )});
	position.add( object.position , 'y', -10000, 10000).onFinishChange(function(v){updateGuiDataPosition( this,v )});
	position.add( object.position , 'z', -10000, 10000).onFinishChange(function(v){updateGuiDataPosition( this,v )});
	var rotation = gui.addFolder('rotation');
	rotation.add( object.rotation , 'x', 0, 100).onFinishChange(function(v){updateGuiDataRotation( this,v )});
	rotation.add( object.rotation , 'y', 0, 100).onFinishChange(function(v){updateGuiDataRotation( this,v )});
	rotation.add( object.rotation , 'z', 0, 100).onFinishChange(function(v){updateGuiDataRotation( this,v )});
	var up = gui.addFolder('up');
	up.add( object.up , 'x', 0, 10).onFinishChange(function(v){updateGuiDataUp( this,v )});
	up.add( object.up , 'y', 0, 10).onFinishChange(function(v){updateGuiDataUp( this,v )});
	up.add( object.up , 'z', 0, 10).onFinishChange(function(v){updateGuiDataUp( this,v )});
	var quaternion = gui.addFolder('quaternion');
	quaternion.add( object.quaternion , '_w', 0, 10).onFinishChange(function(v){updateGuiDataQuaternion( this,v )});
	quaternion.add( object.quaternion , '_x', 0, 10).onFinishChange(function(v){updateGuiDataQuaternion( this,v )});
	quaternion.add( object.quaternion , '_y', 0, 10).onFinishChange(function(v){updateGuiDataQuaternion( this,v )});
	quaternion.add( object.quaternion , '_z', 0, 10).onFinishChange(function(v){updateGuiDataQuaternion( this,v )});
	var scale = gui.addFolder('scale');
	scale.add( object.scale , 'x', 1, 1000).onFinishChange(function(v){updateGuiDataScale( this,v )});
	scale.add( object.scale , 'y', 1, 1000).onFinishChange(function(v){updateGuiDataScale( this,v )});
	scale.add( object.scale , 'z', 1, 1000).onFinishChange(function(v){updateGuiDataScale( this,v )});
}

function updateObjectProperties() {
	if (guiData && !gui.closed && guiDataChanged) {
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

	updateGui({}); //enabling gui breaks trackball controls
}

function updateGui( guiFolderContents ) {
	if ( typeof(gui) != 'object' ) {
		initGui();
		return;
	}
	if (gui.closed) {
		return;
	}

	if ( Object.keys(guiFolderContents).length == 0 ) {
		var isFolder = false;
		guiObj = gui.__controllers;
	} else {
		var isFolder = true;
		guiObj = guiFolderContents;
	}

	// Do properties
	jQuery.each( guiObj, function ( i, controller ) {
		var property = controller.property;
		if ( !guiDataChanged ) {
			//@todo make it work with other controls
			if ( isFolder ) {
				//if (controller.object[property] != trackballControls.object[guiObj.parent][property]) {
					controller.setValue( trackballControls.object[guiObj.parent][property] );
				//}
			} else {
				if (controller.object[property] != trackballControls.object[property]) {
					controller.setValue( trackballControls.object[property] );
				}
			}
		}
	} );

	// Do folders now
	if (!isFolder) {
		jQuery.each( gui.__folders, function ( i, guiFolder ) {
			guiFolder.__controllers.parent = i;
			updateGui( guiFolder.__controllers );
		} );
	}

}

function updateGuiDataItem(folder, property, value) {

	if (folder == null) {
		if (guiData[property] != value) {
			guiData[property] = value;
			guiDataChanged = true;
		}
	} else {
		if (guiData[folder][property] != value) {
			guiData[folder][property] = value;
			guiDataChanged = true;
		}
	}
}

function updateGuiData( change,  value ) {
	var folder = null;
	updateGuiDataItem(folder, change.property, value);
}

function updateGuiDataPosition( change ,  value ){
	updateGuiDataItem('position', change.property, value);
}

function updateGuiDataUp( change ,  value ){
	updateGuiDataItem('up', change.property, value);
}

function updateGuiDataRotation( change ,  value ){
	updateGuiDataItem('rotation', change.property, value);
}

function updateGuiDataQuaternion( change ,  value ){
	updateGuiDataItem('quaternion', change.property, value);
}

function updateGuiDataScale( change ,  value ){
	updateGuiDataItem('scale', change.property, value);
}

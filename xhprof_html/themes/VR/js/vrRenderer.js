function getRenderer() {
	if (typeof(THREE.CSS3DRenderer) == 'function') {
		return new THREE.CSS3DRenderer();
	}
	if (typeof(THREE.CSS3DStereoRenderer) == 'function') {
		return new THREE.CSS3DStereoRenderer();
	}
}

function setRenderer(type) {
	// Clean everything.
	camera = {};
	scene = {};
	renderer = {};
	delete THREE.CSS3DRenderer;
	delete THREE.CSS3DStereoRenderer;
	jQuery('#container').html('');

	// Remove js. @Todo loop renderers
	removeJS(renderers['3d']);
	removeJS(renderers['vr']);

	// Load js
	loadJS(renderers[type], init, document.body);
}

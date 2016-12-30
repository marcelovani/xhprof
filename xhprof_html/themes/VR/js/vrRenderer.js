var renderer;
var renderers = {};
var effect;
renderers['3d'] = ["../../node_modules/three/examples/js/renderers/CSS3DRenderer.js"];
renderers['vr'] = ["../../node_modules/three/examples/js/renderers/CSS3DStereoRenderer.js"];

var activeRenderer = null;

function changeRenderer(renderer) {
	activeRenderer = renderer;
	jQuery('.group-renderer').removeClass('active');
	jQuery('#' + renderer).addClass('active');
}

function loadRenderer(type) {
	delete THREE.CSS3DRenderer;
	delete THREE.CSS3DStereoRenderer;
	for ( var i = 0; i < renderers[type].length; i ++ ) {
		loadJS(renderers[type][i], updateRenderer, document.body);
	}
}

function initRenderer( type ) {
	switch ( type ) {
		case '3d':
			if ( typeof(THREE.CSS3DRenderer) !== 'function' ) {
				loadRenderer( type );
			} else {
				if (renderer instanceof THREE.CSS3DRenderer) {
					renderer.render( scene, camera );
				}
				else {
					renderer = new THREE.CSS3DRenderer();
					reset();
					renderer.domElement.style.position = 'absolute';
					container = document.getElementById( 'container' );
					container.appendChild( renderer.domElement );
					renderer.setSize( window.innerWidth, window.innerHeight );
				}
			}
			break;

		case 'vr':
			if ( typeof(THREE.CSS3DStereoRenderer) !== 'function' ) {
				loadRenderer( type );
			} else {
				if (renderer instanceof THREE.CSS3DStereoRenderer) {
					effect.render( scene, camera );
				}
				else {
					renderer = new THREE.CSS3DStereoRenderer();
					reset();
					renderer.setSize( window.innerWidth, window.innerHeight );
					element = renderer.domElement;
					container = document.getElementById('container');
					container.appendChild(element);

					effect = new THREE.StereoEffect( renderer );
					effect.setSize( window.innerWidth, window.innerHeight );
					effect.setEyeSeparation(3);
				}
			}
			break;
	}
}

function updateRenderer() {
	initRenderer(activeRenderer);
}

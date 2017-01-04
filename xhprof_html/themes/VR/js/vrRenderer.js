var renderer;
var effect;
var activeRenderer = null;

function changeRenderer(_renderer) {
	activeRenderer = _renderer;
	jQuery('.group-renderer').removeClass('active');
	jQuery('#' + _renderer).addClass('active');
}

function updateRenderer() {
	render(activeRenderer);
}

function render( type ) {
	switch ( type ) {
		case '3d':
			require(['CSS3DRenderer'], function () {
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
			});
			break;

		case 'vr':
			require(['CSS3DStereoRenderer'], function () {
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
			});
			break;
	}
}

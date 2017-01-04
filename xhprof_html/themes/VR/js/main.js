require.config({
	paths: {
		'jQuery': '../../../node_modules/jquery/dist/jquery.min',
		'Viz': '../../../node_modules/viz.js/viz',
		'utils': '../../VR/js/utils',
		'vrPlot': '../../VR/js/vrPlot',
		'CSS3DRenderer': '../../../node_modules/three/examples/js/renderers/CSS3DRenderer',
		'CSS3DStereoRenderer': '../../../node_modules/three/examples/js/renderers/CSS3DStereoRenderer'
	},
	shim: {
		'jQuery': {
			exports: 'jQuery'
		},
		'Viz': {
			exports: 'Viz'
		}
	}
});

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

	effect.setSize( window.innerWidth, window.innerHeight );

	updateRenderer();

}

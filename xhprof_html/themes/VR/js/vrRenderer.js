define( [], function () {

	var f = function () {
		var scope = this;

		var renderer;
		var effect;
		var renderType;

		this.render = function () {
			switch ( renderType ) {
				case '3d':
					require( ['CSS3DRenderer'], function () {
						if ( renderer instanceof THREE.CSS3DRenderer ) {
							renderer.render( scene, camera );
						}
						else {
							renderer = new THREE.CSS3DRenderer();
							reset();
							renderer.domElement.style.position = 'absolute';
							var container = document.getElementById( 'container' );
							container.appendChild( renderer.domElement );
							renderer.setSize( window.innerWidth, window.innerHeight );
						}
					} );
					break;

				case 'vr':
				default:
					require( ['CSS3DStereoRenderer'], function ( Renderer ) {
						if ( renderer instanceof THREE.CSS3DStereoRenderer ) {
							effect.render( scene, camera );
						}
						else {
							renderer = new THREE.CSS3DStereoRenderer();
							reset();
							var container = document.getElementById( 'container' );
							container.appendChild( renderer.domElement );
							renderer.setSize( window.innerWidth, window.innerHeight );

							effect = new THREE.StereoEffect( renderer );
							effect.setSize( window.innerWidth, window.innerHeight );
							effect.setEyeSeparation( 3 );
						}
					} );
					break;
			}
		}

		this.setType = function ( type ) {
			renderType = type;
			jQuery( '.group-renderer' ).removeClass( 'active' );
			jQuery( '#' + this.renderType ).addClass( 'active' );
			this.render();
		};

		this.getType = function () {
			return renderType;
		};

		this.active = function () {
			return renderer;
		};
	};

	window.mediator.subscribe( "wat", function () {
		if ( arguments[1] == 'update' ) {
			//f.render( renderType ); //@todo this is not having access to the scope
		}
	} );

	return f;
} );

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
							renderer2.render( scene2, camera );

							//@todo move the target to where the camera is looking at
							//camTarget.rotation.setFromRotationMatrix(camera.matrix);
							//camTarget.position.copy(camera.position);
						}
						else {
							scope.addRendererCss3D();
							scope.addRendererGl();
						}
					} );
					break;

				case 'vr':
				default:
					require( ['CSS3DStereoRenderer'], function ( Renderer ) {
						if ( renderer instanceof THREE.CSS3DStereoRenderer ) {
							effect.render( scene, camera );
							renderer2.render( scene2, camera );
						}
						else {
							scope.addRendererCss3DStereo();
							scope.addRendererGl();
						}
					} );
					break;
			}
		}

		this.addRendererCss3D = function () {
			renderer = new THREE.CSS3DRenderer();
			reset();
			renderer.domElement.style.position = 'absolute';
			var container = document.getElementById( 'container' );
			container.appendChild( renderer.domElement );
			renderer.setSize( window.innerWidth, window.innerHeight );
		}

		this.addRendererCss3DStereo = function () {
			renderer = new THREE.CSS3DStereoRenderer();
			reset();
			var container = document.getElementById( 'container' );
			container.appendChild( renderer.domElement );
			renderer.setSize( window.innerWidth, window.innerHeight );

			effect = new THREE.StereoEffect( renderer );
			effect.setSize( window.innerWidth, window.innerHeight );
			effect.setEyeSeparation( 3 );
		}

		this.addRendererGl = function () {
			// Used for Camera helper, leap marker and maybe for arrows too.
			renderer2 = new THREE.WebGLRenderer({alpha:true});
			renderer2.setClearColor(0x00ff00, 0.0);
			renderer2.setSize( window.innerWidth, window.innerHeight );
			renderer2.domElement.style.position = 'absolute';
			renderer2.domElement.style.zIndex = 1;
			renderer2.domElement.style.top = 0;
			renderer.domElement.appendChild(renderer2.domElement);

			var helper = new THREE.CameraHelper( camera );
			scene2.add( helper );

			var geo = new THREE.IcosahedronGeometry( 5, 2 );
			var mat = new THREE.MeshNormalMaterial();
			camTarget = new THREE.Mesh( geo, mat );
			scene2.add( camTarget );

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

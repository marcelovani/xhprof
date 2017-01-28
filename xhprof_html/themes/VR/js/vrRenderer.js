define( ['jQuery'], function ( jQuery ) {

	var f = function () {
		var scope = this;

		var renderer;
		var effect;
		var renderType;

		var led3DRenderGreen;
		var ledVRRenderYellow;

		this.render = function () {
			switch ( renderType ) {
				case '3d':
					require( ['CSS3DRenderer', 'led'], function (_renderer, _led ) {
						if ( renderer instanceof THREE.CSS3DRenderer ) {
							renderer.render( scene, camera );
							renderer2.render( scene2, camera );

							led3DRenderGreen.on();
							setTimeout(function(){
								led3DRenderGreen.off();
							}, 500);

							//@todo move the target to where the camera is looking at
							//camTarget.rotation.setFromRotationMatrix(camera.matrix);
							//camTarget.position.copy(camera.position);
						}
						else {
							loaderMessage('Initializing', 'Renderer');

							led3DRenderGreen = new _led('.3dled', 'green' );
							scope.addRendererCss3D();
							scope.addRendererGl();
						}
					} );
					break;

				case 'vr':
				default:
					require( ['CSS3DStereoRenderer', 'led'], function ( Renderer, _led ) {
						if ( renderer instanceof THREE.CSS3DStereoRenderer ) {
							effect.render( scene, camera );
							renderer2.render( scene2, camera );

							ledVRRenderYellow.on();
							setTimeout(function(){
								ledVRRenderYellow.off();
							}, 500);
						}
						else {
							loaderMessage('Initializing', 'Renderer');

							ledVRRenderYellow = new _led('.vrled', 'yellow' );
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
			jQuery('#container').html('');
			renderer.domElement.style.position = 'absolute';
			var container = document.getElementById( 'container' );
			container.appendChild( renderer.domElement );
			renderer.setSize( window.innerWidth, window.innerHeight );

			window.addEventListener( 'resize', function() {
				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();
				renderer.setSize( window.innerWidth, window.innerHeight );
			}, false );

		}

		this.addRendererCss3DStereo = function () {
			renderer = new THREE.CSS3DStereoRenderer();
			reset();
			jQuery('#container').html('');
			var container = document.getElementById( 'container' );
			container.appendChild( renderer.domElement );
			renderer.setSize( window.innerWidth, window.innerHeight );

			effect = new THREE.StereoEffect( renderer );
			effect.setSize( window.innerWidth, window.innerHeight );
			effect.setEyeSeparation( 3 );

			window.addEventListener( 'resize', function() {
				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();
				renderer.setSize( window.innerWidth, window.innerHeight );
				effect.setSize( window.innerWidth, window.innerHeight );
			}, false );
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

			if (camTarget) {
				var helper = new THREE.CameraHelper( camera );
				scene2.add( helper );

				var geo = new THREE.IcosahedronGeometry( 5, 2 );
				var mat = new THREE.MeshNormalMaterial();
				camTarget = new THREE.Mesh( geo, mat );
				scene2.add( camTarget );
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

		this.destroy = function () {
			if (typeof(ledVRRenderYellow) != 'undefined') {
				ledVRRenderYellow.destroy();
			}

			if (typeof(led3DRenderGreen) != 'undefined') {
				led3DRenderGreen.destroy();
			}

			jQuery('#container').html('');

			renderer = null;
			delete renderer;

		};

	};

	window.mediator.subscribe( "wat", function () {
		if ( arguments[1] == 'update' ) {
			//f.render( renderType ); //@todo this is not having access to the scope
		}
	} );

	return f;
} );

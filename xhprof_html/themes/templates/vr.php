<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<title>three.js css3d stereo - periodic table</title>
		<style>
			html, body {
				height: 100%;
			}

			body {
				background-color: #000000;
				margin: 0;
				font-family: Helvetica, sans-serif;;
				overflow: hidden;
			}

			a {
				color: #ffffff;
			}

			.element {
				width: 120px;
				height: 160px;
				box-shadow: 0px 0px 12px rgba(0,255,255,0.5);
				border: 1px solid rgba(127,255,255,0.25);
				text-align: center;
				cursor: default;
			}

			.element:hover {
				box-shadow: 0px 0px 12px rgba(0,255,255,0.75);
				border: 1px solid rgba(127,255,255,0.75);
			}

				.element .number {
					position: absolute;
					top: 20px;
					right: 20px;
					font-size: 12px;
					color: rgba(127,255,255,0.75);
				}

				.element .symbol {
					position: absolute;
					top: 40px;
					left: 0px;
					right: 0px;
					font-size: 60px;
					font-weight: bold;
					color: rgba(255,255,255,0.75);
					text-shadow: 0 0 10px rgba(0,255,255,0.95);
				}

				.element .details {
					position: absolute;
					bottom: 15px;
					left: 0px;
					right: 0px;
					font-size: 12px;
					color: rgba(127,255,255,0.75);
				}
            #messages {
                background: white;
                color: green;
            }
		</style>
	</head>
	<body>
        <script src="js/jquery-1.11.1.js"></script>
        <script src="js/three.min.js"></script>
        <script src="js/tween.min.js"></script>
        <script src="js/TrackballControls.js"></script>
        <script src="js/CSS3DStereoRenderer.js"></script>
        <script src="js/socket.io-1.2.0.js"></script>

		<!--<script src="http://threejs.org/build/three.min.js"></script>
		<script src="http://threejs.org/examples/js/libs/tween.min.js"></script>
		<script src="http://threejs.org/examples/js/controls/TrackballControls.js"></script>
		<script src="http://threejs.org/examples/js/renderers/CSS3DStereoRenderer.js"></script>
        <script src="https://cdn.socket.io/socket.io-1.2.0.js"></script>
        <script src="http://code.jquery.com/jquery-1.11.1.js"></script>-->

        <!--<script src="http://drupal-7-32.local:8083/StereoCam/three.min.js"></script>
		<script src="http://drupal-7-32.local:8083/StereoCam/tween.min.js"></script>
		<script src="http://drupal-7-32.local:8083/StereoCam/TrackballControls.js"></script>
		<script src="http://drupal-7-32.local:8083/StereoCam/CSS3DStereoRenderer.js"></script>
        <script src="http://drupal-7-32.local:8083/StereoCam/socket.io-1.2.0.js"></script>
        <script src="http://drupal-7-32.local:8083/StereoCam/jquery-1.11.1.js"></script>-->

        <ul id="messages"></ul>

		<div id="container"></div>


		<script>

			var table = [
				"H", "Hydrogen", "1.00794", 1, 1,
				"He", "Helium", "4.002602", 18, 1,
				"Li", "Lithium", "6.941", 1, 2,
				"Be", "Beryllium", "9.012182", 2, 2,
				"B", "Boron", "10.811", 13, 2,
				"C", "Carbon", "12.0107", 14, 2,
				"N", "Nitrogen", "14.0067", 15, 2,
				"O", "Oxygen", "15.9994", 16, 2,
				"F", "Fluorine", "18.9984032", 17, 2,
				"Ne", "Neon", "20.1797", 18, 2,
				"Na", "Sodium", "22.98976...", 1, 3,
				"Mg", "Magnesium", "24.305", 2, 3,
				"Al", "Aluminium", "26.9815386", 13, 3,
				"Si", "Silicon", "28.0855", 14, 3,
				"P", "Phosphorus", "30.973762", 15, 3,
				"S", "Sulfur", "32.065", 16, 3,
				"Cl", "Chlorine", "35.453", 17, 3,
				"Ar", "Argon", "39.948", 18, 3,
				"K", "Potassium", "39.948", 1, 4,
				"Ca", "Calcium", "40.078", 2, 4,
				"Sc", "Scandium", "44.955912", 3, 4,
				"Ti", "Titanium", "47.867", 4, 4,
				"V", "Vanadium", "50.9415", 5, 4,
				"Cr", "Chromium", "51.9961", 6, 4,
				"Mn", "Manganese", "54.938045", 7, 4,
				"Fe", "Iron", "55.845", 8, 4,
				"Co", "Cobalt", "58.933195", 9, 4,
				"Ni", "Nickel", "58.6934", 10, 4,
				"Cu", "Copper", "63.546", 11, 4,
				"Zn", "Zinc", "65.38", 12, 4,
				"Ga", "Gallium", "69.723", 13, 4,
				"Ge", "Germanium", "72.63", 14, 4,
				"As", "Arsenic", "74.9216", 15, 4,
				"Se", "Selenium", "78.96", 16, 4,
				"Br", "Bromine", "79.904", 17, 4,
				"Kr", "Krypton", "83.798", 18, 4,
				"Rb", "Rubidium", "85.4678", 1, 5,
				"Sr", "Strontium", "87.62", 2, 5,
				"Y", "Yttrium", "88.90585", 3, 5,
				"Zr", "Zirconium", "91.224", 4, 5,
				"Nb", "Niobium", "92.90628", 5, 5,
				"Mo", "Molybdenum", "95.96", 6, 5,
				"Tc", "Technetium", "(98)", 7, 5,
				"Ru", "Ruthenium", "101.07", 8, 5,
				"Rh", "Rhodium", "102.9055", 9, 5,
				"Pd", "Palladium", "106.42", 10, 5,
				"Ag", "Silver", "107.8682", 11, 5,
				"Cd", "Cadmium", "112.411", 12, 5,
				"In", "Indium", "114.818", 13, 5,
				"Sn", "Tin", "118.71", 14, 5,
				"Sb", "Antimony", "121.76", 15, 5,
				"Te", "Tellurium", "127.6", 16, 5,
				"I", "Iodine", "126.90447", 17, 5,
				"Xe", "Xenon", "131.293", 18, 5,
				"Cs", "Caesium", "132.9054", 1, 6,
				"Ba", "Barium", "132.9054", 2, 6,
				"La", "Lanthanum", "138.90547", 4, 9,
				"Ce", "Cerium", "140.116", 5, 9,
				"Pr", "Praseodymium", "140.90765", 6, 9,
				"Nd", "Neodymium", "144.242", 7, 9,
				"Pm", "Promethium", "(145)", 8, 9,
				"Sm", "Samarium", "150.36", 9, 9,
				"Eu", "Europium", "151.964", 10, 9,
				"Gd", "Gadolinium", "157.25", 11, 9,
				"Tb", "Terbium", "158.92535", 12, 9,
				"Dy", "Dysprosium", "162.5", 13, 9,
				"Ho", "Holmium", "164.93032", 14, 9,
				"Er", "Erbium", "167.259", 15, 9,
				"Tm", "Thulium", "168.93421", 16, 9,
				"Yb", "Ytterbium", "173.054", 17, 9,
				"Lu", "Lutetium", "174.9668", 18, 9,
				"Hf", "Hafnium", "178.49", 4, 6,
				"Ta", "Tantalum", "180.94788", 5, 6,
				"W", "Tungsten", "183.84", 6, 6,
				"Re", "Rhenium", "186.207", 7, 6,
				"Os", "Osmium", "190.23", 8, 6,
				"Ir", "Iridium", "192.217", 9, 6,
				"Pt", "Platinum", "195.084", 10, 6,
				"Au", "Gold", "196.966569", 11, 6,
				"Hg", "Mercury", "200.59", 12, 6,
				"Tl", "Thallium", "204.3833", 13, 6,
				"Pb", "Lead", "207.2", 14, 6,
				"Bi", "Bismuth", "208.9804", 15, 6,
				"Po", "Polonium", "(209)", 16, 6,
				"At", "Astatine", "(210)", 17, 6,
				"Rn", "Radon", "(222)", 18, 6,
				"Fr", "Francium", "(223)", 1, 7,
				"Ra", "Radium", "(226)", 2, 7,
				"Ac", "Actinium", "(227)", 4, 10,
				"Th", "Thorium", "232.03806", 5, 10,
				"Pa", "Protactinium", "231.0588", 6, 10,
				"U", "Uranium", "238.02891", 7, 10,
				"Np", "Neptunium", "(237)", 8, 10,
				"Pu", "Plutonium", "(244)", 9, 10,
				"Am", "Americium", "(243)", 10, 10,
				"Cm", "Curium", "(247)", 11, 10,
				"Bk", "Berkelium", "(247)", 12, 10,
				"Cf", "Californium", "(251)", 13, 10,
				"Es", "Einstenium", "(252)", 14, 10,
				"Fm", "Fermium", "(257)", 15, 10,
				"Md", "Mendelevium", "(258)", 16, 10,
				"No", "Nobelium", "(259)", 17, 10,
				"Lr", "Lawrencium", "(262)", 18, 10,
				"Rf", "Rutherfordium", "(267)", 4, 7,
				"Db", "Dubnium", "(268)", 5, 7,
				"Sg", "Seaborgium", "(271)", 6, 7,
				"Bh", "Bohrium", "(272)", 7, 7,
				"Hs", "Hassium", "(270)", 8, 7,
				"Mt", "Meitnerium", "(276)", 9, 7,
				"Ds", "Darmstadium", "(281)", 10, 7,
				"Rg", "Roentgenium", "(280)", 11, 7,
				"Cn", "Copernicium", "(285)", 12, 7,
				"Uut", "Unutrium", "(284)", 13, 7,
				"Fl", "Flerovium", "(289)", 14, 7,
				"Uup", "Ununpentium", "(288)", 15, 7,
				"Lv", "Livermorium", "(293)", 16, 7,
				"Uus", "Ununseptium", "(294)", 17, 7,
				"Uuo", "Ununoctium", "(294)", 18, 7
			];

			var camera, scene, renderer;
			var controls;
            var socket;
            var pause = 0;
            var shape = 'sphere';
            var command = '';
			
			var state = 0;
            var step = 0;

            var objects = [];
			var targets = { table: [], sphere: [] };

			init();
			animate();

			function init() {

                socket = new io();

                socket.on('controls', function(controls){
                    //console.log(controls);
                    $.each( controls, function( key, obj ) {
                        $.each( obj, function( obkey, val ) {

                            if (typeof val === 'object') {
                                $.each( val, function( val_key, val_item ) {
                                    camera[obkey][val_key] = val_item;
                                });
                            }
                            if (typeof val === 'string' || typeof val === 'number') {
                                camera[obkey] = val;
                            }
                        });
                    });

                    if (pause) {

                    }
                    else {
                        renderer.render( scene, camera );
                    }
                });

                socket.on('renderer', function(renderer_msg){
                    //console.log(renderer);
                    $.each( renderer_msg, function( key, obj ) {
                        $.each( obj, function( obkey, val ) {

                            if (typeof val === 'object') {
                                $.each( val, function( val_key, val_item ) {
                                    renderer[obkey][val_key] = val_item;
                                });
                            }
                            if (typeof val === 'string' || typeof val === 'number') {
                                renderer[obkey] = val;
                                //console.log(val);
                            }
                        });
                    });

                    renderer.render( scene, camera );
                });

                socket.on('shape', function(shape_msg){

                    shape = shape_msg;
                    transform( targets[shape], 3000);
                    shape = '';

                    //renderer.render( scene, camera );
                });

                socket.on('status', function(status_msg){

                    switch (status_msg) {
                        case 'refresh':
                                location.reload();
                                command = '';
                            break;
                    }

                });


				camera = new THREE.PerspectiveCamera( 40, window.innerWidth / window.innerHeight, 1, 10000 );
				camera.position.z = 3000;

				scene = new THREE.Scene();

				// table

				for ( var i = 0; i < table.length; i += 5 ) {

					var element = document.createElement( 'div' );
					element.className = 'element';
					element.style.backgroundColor = 'rgba(0,127,127,' + ( Math.random() * 0.5 + 0.25 ) + ')';

					var number = document.createElement( 'div' );
					number.className = 'number';
					number.textContent = (i/5) + 1;
					element.appendChild( number );

					var symbol = document.createElement( 'div' );
					symbol.className = 'symbol';
					symbol.textContent = table[ i ];
					element.appendChild( symbol );

					var details = document.createElement( 'div' );
					details.className = 'details';
					details.innerHTML = table[ i + 1 ] + '<br>' + table[ i + 2 ];
					element.appendChild( details );

					var object = new THREE.CSS3DObject( element );
					object.position.x = Math.random() * 4000 - 2000;
					object.position.y = Math.random() * 4000 - 2000;
					object.position.z = Math.random() * 4000 - 2000;
					scene.add( object );

					objects.push( object );

					//

					var object = new THREE.Object3D();
					object.position.x = ( table[ i + 3 ] * 140 ) - 1330;
					object.position.y = - ( table[ i + 4 ] * 180 ) + 990;

					targets.table.push( object );

				}

				// sphere

				var vector = new THREE.Vector3();

				for ( var i = 0, l = objects.length; i < l; i ++ ) {

					var phi = Math.acos( -1 + ( 2 * i ) / l );
					var theta = Math.sqrt( l * Math.PI ) * phi;

					var object = new THREE.Object3D();

					object.position.x = 800 * Math.cos( theta ) * Math.sin( phi );
					object.position.y = 800 * Math.sin( theta ) * Math.sin( phi );
					object.position.z = 800 * Math.cos( phi );

					vector.copy( object.position ).multiplyScalar( 2 );

					object.lookAt( vector );

					targets.sphere.push( object );

				}

				//

				renderer = new THREE.CSS3DStereoRenderer();
                renderer.separation = 6;
				renderer.setSize( window.innerWidth, window.innerHeight );
				renderer.domElement.style.position = 'absolute';
				document.getElementById( 'container' ).appendChild( renderer.domElement );

				//

				controls = new THREE.TrackballControls( camera, renderer.domElement );
				controls.rotateSpeed = 0.5;
				controls.minDistance = 500;
				controls.maxDistance = 6000;
				controls.addEventListener( 'change', render );

				transform( targets.table, 500 ); //temp aaa

				//

                window.addEventListener("load", function(){ if(!window.pageYOffset){ hideAddressBar(); } } );
                window.addEventListener("orientationchange", hideAddressBar );

                window.addEventListener( 'resize', onWindowResize, false );
                document.addEventListener( 'keydown', onKeyDown, false );
                render();
            }

            function hideAddressBar() {
                if(!window.location.hash)
                {
                    if(document.height < window.outerHeight)
                    {
                        document.body.style.height = (window.outerHeight + 50) + 'px';
                    }

                    setTimeout( function(){ window.scrollTo(0, 1); }, 50 );
                }
            }


            //

            function onKeyDown ( event ) {

                console.log(event.keyCode );
                var r = Date.now() * 0.0005;

                switch( event.keyCode ) {
                    case 38: /* up */
                        camera.position.y = camera.position.y - 100;
                        break

                    case 40: /* down */
                        camera.position.y = camera.position.y + 100;
                        break;

                    case 37: /* left */
                        camera.position.x = camera.position.x + 100;
                        break

                    case 39: /* right */
                        camera.position.x = camera.position.x - 100;
                        break;

                    case 188: /* < */
                        camera.position.z = camera.position.z + 100;
                        break

                    case 190: /* > */
                        camera.position.z = camera.position.z - 100;
                        break;

                    case 70: /* f */
                        camera.far = camera.far - 100;
                        break;

                    case 67: /* c */
                        camera.far = camera.far + 100;
                        break;

                    case 81: /* q */
                        camera.fov = camera.fov - 1;
                        break

                    case 87: /* w */
                        camera.fov = camera.fov + 1;
                        break;

                    case 82: /* r */
                        command = 'refresh';
                        break


                    case 90: /* z */
                        camera.zoom = camera.zoom + 5;
                        break

                    case 88: /* x */
                        camera.zoom = camera.zoom - 5;
                        break;

                    case 73: /* i */

                        camera.fov = camera.fov = 5;
                        camera.far = 300;

                    case 83: /* s */
                        shape = 'sphere';
                        //transform( targets.sphere, 3000 );
                        break;

                    case 84: /* t */
                        shape = 'table';
                        //transform( targets.table, 3000 );
                        break;

                    case 189: /* - */
                        renderer.separation = renderer.separation + 1;
                            render( scene, camera );
                        break;

                    case 187: /* + */
                        renderer.separation = renderer.separation - 1;
                            render( scene, camera );
                        break;

                    case 80: /* p */
                        if (pause == 0) {
                            pause = 1;
                        }
                        else {
                            pause = 0;
                        }
                        break;

                }
                camera.updateProjectionMatrix();

            };

			//

			var isFullscreen = false;

			window.addEventListener( 'touchend', function () {

				if ( isFullscreen === false ) {

					document.body.webkitRequestFullscreen();

					isFullscreen = true;

				} else {
				
					document.webkitExitFullscreen();
					
					isFullscreen = false;
				
				}
			
			} );

			function transform( positions, duration ) {

				TWEEN.removeAll();

				for ( var i = 0; i < objects.length; i ++ ) {

					var object = objects[ i ];
					var target = positions[ i ];

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
					.onUpdate( render )
					.onComplete( function () {
                        if (step < 1) {
                            transform( targets.sphere, 1000 );
                        }
                        step = 1;
					})
					.start();

			}

			function onWindowResize() {

				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();

				renderer.setSize( window.innerWidth, window.innerHeight );

				render();

			}

			function animate() {
				requestAnimationFrame( animate );
				TWEEN.update();
				controls.update();
			}

            var prev_status = {};

			function render() {
                var status = { renderer: [], controls: [], camera: [], scene: [] };
                var camera_properties = [ "fov", "far", "zoom", "position" ];
                var renderer_properties = [ "separation" ];

                $.each(camera_properties, function( i, name ) {
                    var val = {};
                    val[name] = controls.object[name];
                    status.controls.push(val);
                });

                $.each(renderer_properties, function( i, name ) {
                    var val = {};
                    val[name] = renderer[name];
                    status.renderer.push(val);
                });

                if (prev_status != status) {
                    prev_status = status;
                    socket.emit('controls', status.controls);
                    socket.emit('renderer', status.renderer);
                }
                if (shape != '') {
                    socket.emit('shape', shape);
                }
                if (command != '') {
                    socket.emit('status', command);
                }
			}

            function sleep(milliseconds) {
                var start = new Date().getTime();
                for (var i = 0; i < 1e7; i++) {
                    if ((new Date().getTime() - start) > milliseconds){
                        break;
                    }
                }
            }

		</script>
	</body>
</html>
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
					color: black;
				}
            #messages {
                background: white;
                color: green;
            }


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

      #info {
        position: absolute;
        width: 100%;
        color: #ffffff;
        padding: 5px;
        font-family: Monospace;
        font-size: 13px;
        font-weight: bold;
        text-align: center;
        z-index: 1;
      }

      #menu {
        position: absolute;
        bottom: 20px;
        width: 100%;
        text-align: center;
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
        color: black;
        text-shadow: 0 0 10px rgba(0,255,255,0.95);
      }

      .element .details {
        position: absolute;
        bottom: 15px;
        left: 0px;
        right: 0px;
        font-size: 12px;
        color: black;
      }

      button {
        color: rgba(127,255,255,0.75);
        background: transparent;
        outline: 1px solid rgba(127,255,255,0.75);
        border: 0px;
        padding: 5px 10px;
        cursor: pointer;
      }
      button:hover {
        background-color: rgba(0,255,255,0.5);
      }
      button:active {
        color: #000000;
        background-color: rgba(0,255,255,0.75);
      }
    </style>
	</head>
	<body>
<!--<script src="js/jquery-1.11.1.js"></script>
<script src="js/three.min.js"></script>
<script src="js/tween.min.js"></script>
<script src="js/TrackballControls.js"></script>
<script src="js/CSS3DStereoRenderer.js"></script>
<script src="js/socket.io-1.2.0.js"></script>-->

<!--<script src="http://threejs.org/build/three.min.js"></script>
<script src="http://threejs.org/examples/js/libs/tween.min.js"></script>
<script src="http://threejs.org/examples/js/controls/TrackballControls.js"></script>
<script src="http://threejs.org/examples/js/renderers/CSS3DStereoRenderer.js"></script>
<script src="https://cdn.socket.io/socket.io-1.2.0.js"></script>
<script src="http://code.jquery.com/jquery-1.11.1.js"></script>
<script src="http://cpettitt.github.io/project/graphlib-dot/v0.4.10/graphlib-dot.min.js"></script>-->

<!--<script src="http://drupal-7-32.local:8083/StereoCam/three.min.js"></script>
<script src="http://drupal-7-32.local:8083/StereoCam/tween.min.js"></script>
<script src="http://drupal-7-32.local:8083/StereoCam/TrackballControls.js"></script>
<script src="http://drupal-7-32.local:8083/StereoCam/CSS3DStereoRenderer.js"></script>
<script src="http://drupal-7-32.local:8083/StereoCam/socket.io-1.2.0.js"></script>
<script src="http://drupal-7-32.local:8083/StereoCam/jquery-1.11.1.js"></script>-->

<script src="./third-party/jquery/jquery-1.7.1.min.js"></script>
<script src="./third-party/graphlib-dot/dist/graphlib-dot.js"></script>

<script src="./third-party/three.js/build/three.js"></script>
<script src="./third-party/three.js/examples/js/libs/tween.min.js"></script>
<script src="./third-party/three.js/examples/js/controls/TrackballControls.js"></script>
<!--<script src="./third-party/three.js/examples/js/renderers/CSS3DStereoRenderer.js"></script>-->
<script src="./third-party/three.js/examples/js/renderers/CSS3DRenderer.js"></script>

    <ul id="messages"></ul>

		<div id="container"></div>

		<script>
      <?php
        global $script;
        //print 'var dot_script = graphlibDot.parse(' . $script . ');';
        print 'var g = graphlibDot.read(' . PHP_EOL . $script . PHP_EOL . ');';
      ?>

      window.onload = function () {
/*

        var g = graphlibDot.parse(
          'digraph {\n' +
          'N0[shape=box , label="bar\nInc: 0.179 ms (66.1%)\nExcl: 0.048 ms (17.7%)\n5 total calls", width=3.3, height=2.3, fontsize=33, style=filled, fillcolor=yellow];' +
          'N1[shape=box , label="strlen\nInc: 0.023 ms (8.5%)\nExcl: 0.023 ms (8.5%)\n5 total calls", width=1.6, height=1.1, fontsize=28];' +
          'N2[shape=box , label="bar@1\nInc: 0.131 ms (48.3%)\nExcl: 0.029 ms (10.7%)\n4 total calls", width=2.0, height=1.4, fontsize=30, style=filled, fillcolor=yellow];' +
          'N3[shape=box , label="bar@2\nInc: 0.102 ms (37.6%)\nExcl: 0.024 ms (8.9%)\n3 total calls", width=1.6, height=1.2, fontsize=29, style=filled, fillcolor=yellow];' +
          'N4[shape=box , label="bar@3\nInc: 0.078 ms (28.8%)\nExcl: 0.073 ms (26.9%)\n2 total calls", width=5.0, height=3.5, fontsize=35, style=filled, fillcolor=red];' +
          'N5[shape=box , label="bar@4\nInc: 0.005 ms (1.8%)\nExcl: 0.005 ms (1.8%)\n1 total calls", width=0.3, height=0.2, fontsize=14, style=filled, fillcolor=yellow];' +
          'N6[shape=box , label="foo\nInc: 0.252 ms (93.0%)\nExcl: 0.050 ms (18.5%)\n1 total calls", width=3.4, height=2.4, fontsize=33, style=filled, fillcolor=red];' +
          'N7[shape=box , label="xhprof_disable\nInc: 0.004 ms (1.5%)\nExcl: 0.004 ms (1.5%)\n1 total calls", width=0.3, height=0.2, fontsize=12];' +
          'N8[shape=octagon , label="Total: 0.271 ms\nXHProf Run (Namespace=xhprof)\nExcl: 0.015 ms (5.5%)\n1 total calls", width=1.0, height=0.7, fontsize=25];' +
          'N6 -> N0[arrowsize=2, style="setlinewidth(10)", label="5 calls", headlabel="100.0%", taillabel="88.6%" ];' +
          'N6 -> N1[arrowsize=1, style="setlinewidth(1)", label="5 calls", headlabel="100.0%", taillabel="11.4%" ];' +
          'N0 -> N2[arrowsize=2, style="setlinewidth(10)", label="4 calls", headlabel="100.0%", taillabel="100.0%" ];' +
          'N2 -> N3[arrowsize=2, style="setlinewidth(10)", label="3 calls", headlabel="100.0%", taillabel="100.0%" ];' +
          'N3 -> N4[arrowsize=2, style="setlinewidth(10)", label="2 calls", headlabel="100.0%", taillabel="100.0%" ];' +
          'N4 -> N5[arrowsize=2, style="setlinewidth(10)", label="1 call", headlabel="100.0%", taillabel="100.0%" ];' +
          'N8 -> N6[arrowsize=2, style="setlinewidth(10)", label="1 call", headlabel="100.0%", taillabel="98.4%" ];' +
          'N8 -> N7[arrowsize=1, style="setlinewidth(1)", label="1 call", headlabel="100.0%", taillabel="1.6%" ];' +
      '}'
      )
*/

        //var g = graphlibDot.parse(dot_script);
        /*
        var g = graphlibDot.parse(
          'digraph {\n' +
            '    a -> b;\n' +
            '    }'
        )*/

        console.log(g);

        var camera, scene, renderer;
        var controls;

        var objects = [];
        var targets = { table: [], sphere: [], helix: [], grid: [] };

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


        init();
        animate();

        function init() {

          camera = new THREE.PerspectiveCamera( 40, window.innerWidth / window.innerHeight, 1, 10000 );
          camera.position.z = 3000;

          scene = new THREE.Scene();

          // table
/*
          var x = 0;
          var y = 0;
          var z = 0;
          jQuery.each(g.nodes(), function( i, val ) {
            var color = 'white';

            var node_style = g.node(val).style;
            var node_label = g.node(val).label;
            if (typeof node_style == 'string') {
              node_style = node_style.replace(/\w+/g, '"$&"');
              var json = "{" + node_style + "}";
              var style = jQuery.parseJSON(json);
              color = style.fill;
            }

            console.log(val, node_style, node_label, color);

            x = x + 100 + Math.ceil(Math.random() * 10000);
            y = y + 200 + Math.ceil(Math.random() * 10000);

          });
          */
          //for ( var i = 0; i < g.nodes().length; i ++ ) {
          jQuery.each(g.nodes(), function( i, val ) {

            var node = g.node(val);

            color = 'white';
            if (typeof node.style == 'string') {
              node.style = node.style.replace(/\w+/g, '"$&"');
              var json = "{" + node.style + "}";
              var style = jQuery.parseJSON(json);
              color = style.fill;
            }

            var element = document.createElement( 'div' );
            element.className = 'element';
            element.style.backgroundColor = color;
            element.style.width = node.width * 100 + 50 + 'px';

            var number = document.createElement( 'div' );
            number.className = 'number';
            number.textContent = i;
            element.appendChild( number );

            var symbol = document.createElement( 'div' );
            symbol.className = 'symbol';
            symbol.textContent = "foo()";
            element.appendChild( symbol );

            var details = document.createElement( 'div' );
            details.className = 'details';
            details.innerHTML = node.label;
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

          });

          // grid

          for ( var i = 0; i < objects.length; i ++ ) {

            var object = new THREE.Object3D();

            object.position.x = ( ( i % 5 ) * 400 ) - 800;
            object.position.y = ( - ( Math.floor( i / 5 ) % 5 ) * 400 ) + 800;
            object.position.z = ( Math.floor( i / 25 ) ) * 1000 - 2000;

            targets.grid.push( object );

          }

          //

          renderer = new THREE.CSS3DRenderer();
          renderer.setSize( window.innerWidth, window.innerHeight );
          renderer.domElement.style.position = 'absolute';
          document.getElementById( 'container' ).appendChild( renderer.domElement );

          //

          controls = new THREE.TrackballControls( camera, renderer.domElement );
          controls.rotateSpeed = 0.5;
          controls.minDistance = 500;
          controls.maxDistance = 6000;
          controls.addEventListener( 'change', render );

          transform( targets.grid, 2000 );

          //

          window.addEventListener( 'resize', onWindowResize, false );

        }

        function transform( targets, duration ) {

          TWEEN.removeAll();

          for ( var i = 0; i < objects.length; i ++ ) {

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
            .onUpdate( render )
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

        function render() {

          renderer.render( scene, camera );

        }


      }

    </script>
	</body>
</html>

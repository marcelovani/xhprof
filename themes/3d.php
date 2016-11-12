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
<!--<script src="js/jquery-1.11.1.js"></script>
<script src="js/three.min.js"></script>
<script src="js/tween.min.js"></script>
<script src="js/TrackballControls.js"></script>
<script src="js/CSS3DStereoRenderer.js"></script>
<script src="js/socket.io-1.2.0.js"></script>-->

<script src="http://threejs.org/build/three.min.js"></script>
<script src="http://threejs.org/examples/js/libs/tween.min.js"></script>
<script src="http://threejs.org/examples/js/controls/TrackballControls.js"></script>
<script src="http://threejs.org/examples/js/renderers/CSS3DStereoRenderer.js"></script>
<script src="https://cdn.socket.io/socket.io-1.2.0.js"></script>
<script src="http://code.jquery.com/jquery-1.11.1.js"></script>
<script src="http://cpettitt.github.io/project/graphlib-dot/v0.4.10/graphlib-dot.min.js"></script>

<!--<script src="http://drupal-7-32.local:8083/StereoCam/three.min.js"></script>
<script src="http://drupal-7-32.local:8083/StereoCam/tween.min.js"></script>
<script src="http://drupal-7-32.local:8083/StereoCam/TrackballControls.js"></script>
<script src="http://drupal-7-32.local:8083/StereoCam/CSS3DStereoRenderer.js"></script>
<script src="http://drupal-7-32.local:8083/StereoCam/socket.io-1.2.0.js"></script>
<script src="http://drupal-7-32.local:8083/StereoCam/jquery-1.11.1.js"></script>-->

    <ul id="messages"></ul>

		<div id="container"></div>

		<script>
      <?php
        global $script;
        //print 'var dot_script = graphlibDot.parse(' . $script . ');';
      ?>

      window.onload = function () {

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

        //var g = graphlibDot.parse(dot_script);
        var g = graphlibDot.parse(
          'digraph {\n' +
            '    a -> b;\n' +
            '    }'
        )
        console.log(g);
      }

    </script>
	</body>
</html>

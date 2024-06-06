<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<title>dot d3 viewer</title>
    <link rel="stylesheet" href="./themes/css/xhprof.css">
    <link rel="stylesheet" href="./themes/css/2d.css">
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-87733842-1', 'auto');
      ga('send', 'pageview', '2d');

    </script>
	</head>

  <?php
  //@todo use node_modules
  //@todo put whole thing inside a box like demo-dagre
  ?>
  <script src="./node_modules/d3/d3.min.js"></script>
<!--  <script src="http://cpettitt.github.io/project/graphlib-dot/v0.4.10/graphlib-dot.min.js"></script>-->
<!--  <script src="http://cpettitt.github.io/project/dagre-d3/v0.1.5/dagre-d3.min.js"></script>-->
  <script src="../templates/third-party/dagre-d3/dist/dagre-d3.js"></script>
  <script src="../templates//third-party/graphlib-dot/dist/graphlib-dot.js"></script>

	<body>
  <svg id="graphContainer">
    <g/>
  </svg>

		<script>
      <?php
        //global $script;
        // Prepare graphlib-dot object.
        $script = preg_replace('/(.+)/', '\'$1\' +', $script);
        $script = preg_replace('/\}\'\s*\+/', "}'", $script);
        print 'var g = graphlibDot.parse(' . PHP_EOL . $script . PHP_EOL . ')';
      ?>

      window.onload = function () {
/*

        var g = graphlibDot.parse(
          'digraph call_graph {\n' +
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
      );
*/


        //var g = graphlibDot.parse(dot_script);

//        console.log(g);return;

        // Render the graphlib object using d3.
        var renderer = new dagreD3.Renderer();
        renderer.run(g, d3.select("svg g"));


        // Optional - resize the SVG element based on the contents.
        var svg = document.querySelector('#graphContainer');
        var bbox = svg.getBBox();
        svg.style.width = bbox.width + 40.0 + "px";
        svg.style.height = bbox.height + 40.0 + "px";


      }

    </script>
	</body>
</html>

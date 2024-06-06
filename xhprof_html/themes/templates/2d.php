<?php die(__FILE__); ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<title>dot d3 viewer</title>
		<style>
      svg {
        overflow: hidden;
      }
      .node rect {
        stroke: #333;
        stroke-width: 1.5px;
        fill: #fff;
      }
      .edgeLabel rect {
        fill: #fff;
      }
      .edgePath {
        stroke: #333;
        stroke-width: 1.5px;
      }
      .edgePath path.path {
        stroke: #333;
        fill: none;
        stroke-width: 1.5px;
      }
		</style>
	</head>

  <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.4.11/d3.min.js"></script>-->
  <!--<script src="http://cpettitt.github.io/project/graphlib-dot/v0.4.10/graphlib-dot.min.js"></script>
  <script src="http://cpettitt.github.io/project/dagre-d3/v0.1.5/dagre-d3.min.js"></script>-->

  <script src="./third-party/jquery/jquery-1.7.1.min.js"></script>
  <script src="./third-party/d3/d3.min.js"></script>
  <script src="./third-party/graphlib-dot/dist/graphlib-dot.js"></script>
  <script src="./third-party/dagre-d3/dist/dagre-d3.js"></script>

	<body>
  <svg id="svg-canvas" width="900px" height="500px"></svg>

		<script id="js">
      <?php
        global $script;
        print 'var g = graphlibDot.read(' . PHP_EOL . $script . PHP_EOL . ');';
      ?>

      window.onload = function () {

/*        var g = graphlibDot.read(
          'digraph call_graph {' +
            'N0[shape=rect , label="bar\nInc: 0.179 ms (66.1%)\nExcl: 0.048 ms (17.7%)\n5 total calls", fontsize=33, style=filled, style="fill: yellow"];' +
            'N1[shape=rect , label="strlen\nInc: 0.023 ms (8.5%)\nExcl: 0.023 ms (8.5%)\n5 total calls", fontsize=28];' +
            'N2[shape=rect , label="bar@1\nInc: 0.131 ms (48.3%)\nExcl: 0.029 ms (10.7%)\n4 total calls", fontsize=30, style=filled, style="fill: yellow"];' +
            'N3[shape=rect , label="bar@2\nInc: 0.102 ms (37.6%)\nExcl: 0.024 ms (8.9%)\n3 total calls", fontsize=29, style=filled, style="fill: yellow"];' +
            'N4[shape=rect , label="bar@3\nInc: 0.078 ms (28.8%)\nExcl: 0.073 ms (26.9%)\n2 total calls", fontsize=35, style=filled, style="fill: red"];' +
            'N5[shape=rect , label="bar@4\nInc: 0.005 ms (1.8%)\nExcl: 0.005 ms (1.8%)\n1 total calls", fontsize=14, style=filled, style="fill: yellow"];' +
            'N6[shape=rect , label="foo\nInc: 0.252 ms (93.0%)\nExcl: 0.050 ms (18.5%)\n1 total calls", fontsize=33, style=filled, style="fill: red"];' +
            'N7[shape=rect , label="xhprof_disable\nInc: 0.004 ms (1.5%)\nExcl: 0.004 ms (1.5%)\n1 total calls", fontsize=12];' +
            'N8[shape=rect , label="Total: 0.271 ms\nXHProf Run (Namespace=xhprof)\nExcl: 0.015 ms (5.5%)\n1 total calls", fontsize=25];' +
            'N6 -> N0[arrowsize=2, style="setlinewidth(10)", label="5 calls", headlabel="100.0%", taillabel="88.6%" ];' +
            'N6 -> N1[arrowsize=1, style="setlinewidth(1)", label="5 calls", headlabel="100.0%", taillabel="11.4%" ];' +
            'N0 -> N2[arrowsize=2, style="setlinewidth(10)", label="4 calls", headlabel="100.0%", taillabel="100.0%" ];' +
            'N2 -> N3[arrowsize=2, style="setlinewidth(10)", label="3 calls", headlabel="100.0%", taillabel="100.0%" ];' +
            'N3 -> N4[arrowsize=2, style="setlinewidth(10)", label="2 calls", headlabel="100.0%", taillabel="100.0%" ];' +
            'N4 -> N5[arrowsize=2, style="setlinewidth(10)", label="1 call", headlabel="100.0%", taillabel="100.0%" ];' +
            'N8 -> N6[arrowsize=2, style="setlinewidth(10)", label="1 call", headlabel="100.0%", taillabel="98.4%" ];' +
            'N8 -> N7[arrowsize=1, style="setlinewidth(1)", label="1 call", headlabel="100.0%", taillabel="1.6%" ];' +
            '}'
        );*/

        g.nodes().forEach(function(v) {
          var node = g.node(v);
          // Round the corners of the nodes
          node.rx = node.ry = 5;
        });

        // Create the renderer
        var render = new dagreD3.render();

        // Set up an SVG group so that we can translate the final graph.
        var svg = d3.select("svg"), svgGroup = svg.append("g");

        // Run the renderer. This is what draws the final graph.
        render(d3.select("svg g"), g);

        // Resize the SVG element based on the contents.
        /*
        var container = document.querySelector('#svg-canvas');
        var bbox = container.getBBox();
        container.style.width = bbox.width + 40.0 + "px";
        container.style.height = bbox.height + 40.0 + "px";
        // Run the renderer. This is what draws the final graph.
        render(d3.select("svg g"), g);
*/
  //      console.log(jQuery('#svg-canvas').getBBox().width);
  //      jQuery('#svg-canvas').css('width', svg.width);

        // Center the graph
        var xCenterOffset = (svg.attr("width") - g.graph().width) / 2;
        //svgGroup.attr("transform", "translate(" + xCenterOffset + ", 20)");
        svg.attr("height", g.graph().height + 10);
        svg.attr("width", g.graph().width + 10);

        render(d3.select("svg g"), g);
      }

    </script>
	</body>
</html>

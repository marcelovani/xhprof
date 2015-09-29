<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
  <meta charset="UTF-8">
  <title>Graphosaurus</title>
  <style>
    #frame {
      position: absolute;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
      z-index: 0;
    }

    #label {
      position: absolute;
      top: 15;
      left: 15;
      z-index: 1;
      color: white;
      font-family: sans-serif;
    }

    #title {
      position: absolute;
      top: 15;
      right: 15;
      z-index: 1;
      color: white;
      font-family: sans-serif;
    }
  </style>
</head>
<body>

<div id="frame"></div>

<div id="label"></div>
<div id="title">Eve Online Universe</div>

<script src="./third-party/jquery/jquery-1.7.1.min.js"></script>
<!--<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>-->

<!--<script src="./third-party/d3/d3.min.js"></script>-->
<script src="./third-party/graphosaurus/dist/graphosaurus.js"></script>
<script src="./third-party/graphlib-dot/dist/graphlib-dot.js"></script>
<!--<script src="./third-party/dagre-d3/dist/dagre-d3.js"></script>-->

<script id="js">
  <?php
    global $script;
    print 'var g = graphlibDot.read(' . PHP_EOL . $script . PHP_EOL . ');';
  ?>

  window.onload = function () {
    window.eve = {"nodes": [
      [30000001, 0.858, "Tanoo", -0.8851, 0.4237, -0.4451],
      [30000002, 0.752, "Lashesih", -1.033, 0.4171, -0.2986],
      [30000003, 0.846, "Akpivem", -0.9117, 0.4394, -0.5648],
      [30000004, 0.817, "Jark", -0.9368, 0.506, -0.284],
      [30000005, 0.814, "Sasta", -0.9478, 0.4313, -0.319],
      [30000006, 0.864, "Zaid", -0.8479, 0.4231, -0.5707],
      [30000007, 0.907, "Yuzier", -0.9037, 0.5345, -0.4904],
      [30000008, 0.882, "Nirbhi", -0.7158, 0.4733, -0.4526],
      [30000009, 0.578, "Sooma", -0.8409, 0.6921, -0.7523]
    ], "edges": [
      [30000001, 30000003],
      [30000001, 30000005],
      [30000001, 30000007],
      [30000002, 30000005],
      [30000003, 30000001],
      [30000003, 30000007],
      [30000003, 30000009],
      [30000009, 30000008],
      [30000002, 30000004],
      [30000002, 30000006],
      [30000009, 30000007],
      [30000009, 30000004],
      [30000006, 30000003],
      [30000005, 30000007]
    ]};

    console.log(g.nodes());
    console.log(g.edges());

    graph = G.graph({
      //nodeImage: "../_common/disc.png",
      nodeImageTransparent: true,
      antialias: true,
      bgColor: "darkgray",
      edgeWidth: 2.0,
      nodeSize: 25,
      hover: function (node) {
        $("#label").text("Solar system: " + node.name);
      }
    });

    var x = 0;
    var y = 0;
    var z = 0;
    jQuery.each(g.nodes(), function( i, val ) {
      //var node = val;
      var yellow = 'yellow';
      console.log(g.node(val).style);
      var temp = eval ("var style = {" + g.node(val).style + "};");
      console.log(style);
      console.log(style.fill);
      //var color = g.node(val).style.replace(/fill:\s/, '');
      var node = G.node([x, y, z], {
        id: val,
        color: style.fill
      });

      node.name = node.label;
      node.addTo(graph);

      x = x + 100 + Math.ceil(Math.random() * 10000);
      y = y + 200 + Math.ceil(Math.random() * 10000);

      console.log(x, y);
    });

    jQuery.each(g.edges(), function( i, val ) {
      var edge = val;
      //console.log(edge);
      G.edge([edge.v, edge.w], {
        color: 0x0000aa
      }).addTo(graph);
    });

    /*
    for (var i = 0; i < g.nodes().length; i++) {
      var node = g.nodes[i];
      console.log(node); return;
      var nodeId = node[0];
      var coords = node.slice(3, 6);

      var node = G.node(coords, {
        id: nodeId,
        color: 'red'
      });
      node.name = node[2];
      node.addTo(graph);
    }
return;
    for (var i = 0; i < eve.edges.length; i++) {
      G.edge(eve.edges[i], {
        color: 0x0000aa
      }).addTo(graph);
    }*/

    graph.renderIn('frame');
  }
</script>
</body>
</html>

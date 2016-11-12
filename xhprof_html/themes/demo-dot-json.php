<!DOCTYPE html>

<meta charset='utf-8'>
<link rel='stylesheet/less' type='text/css' href='./themes/graphyte/less/main.less'>
<!--<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.9.1/styles/default.min.css">-->

<script>
  window.less = {
    env: 'development',
    async: true
  };
</script>

<script src='./themes/graphyte/js/config.js'></script>
<script src='./node_modules/graphlib-dot/dist/graphlib-dot.js'></script>
<script src='./node_modules/less/dist/less.min.js'></script>
<script src='./node_modules/requirejs/require.js'></script>
<!--<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.9.1/highlight.min.js"></script>-->

<h1>Les Misérables</h1>
<div id='graph'></div>

<script>
  require([
    'd3',
    'js/core',
    'layouts/collision',
    'layouts/force',
    'layouts/labels',
    'layouts/zoom'
  ], function(
    d3,
    graphyte,
    cdLayout,
    forceLayout,
    labelsLayout,
    zoomLayout
  ) {

    'use strict';

    var duration = 5000;
    var delay = 5000;

    var collisionDetection = cdLayout()
      .duration(duration)
      .delay(delay)
      .shape('ellipse');

    var labels = labelsLayout()
      .alignment('parent')
      .lineHeight(13)
      .outlines(true);

    var zoom = zoomLayout();

    // following ranges, setTimeout and update function
    // are an approach to sync force settings with collision detection
    // e.g. reduce edgeLength or edgeWeight while collision detection kicks in

    var edgeLength = d3.scale.linear()
      .domain([0, 1])
      .range([30, 300]);

    var edgeWeight = d3.scale.linear()
      .domain([0, 1])
      .range([0.8, 0.4]);

    var charge = d3.scale.linear()
      .domain([0, 1])
      .range([-120, 0]);

    var friction = d3.scale.linear()
      .domain([0, 1])
      .range([0.8, 0]);

    var color = d3.scale.category20();

    var force = forceLayout()
      .autoCharge(false)
      .charge(charge(0))
      .friction(friction(0))
      .edgeLength(edgeLength(0))
      .edgeWeight(edgeWeight(0));

    var graph = graphyte()
      .alpha(0.01)
      .debug(false)
      .layout(
        collisionDetection,
        labels,
        zoom,
        force
      );

    var start;

    var update = function() {
      var now = new Date().getTime();
      var delta = now - start;
      if (duration >= delta) {
        force.stop();
      }
    };

    setTimeout(function() {
      start = new Date().getTime();
      update();
    }, delay);

    d3.json('themes/graphyte/demo_data/demo-dot-json.json', function(error, data) {
      var nodes = data.nodes.map(function(node) {
        node.style = {
          fill: color(node.group)
        };
        return node;
      });

      graph.vertices(nodes)
        .edges(data.links.map(function(edge) {
          edge.source = nodes[edge.source];
          edge.target = nodes[edge.target];
          edge.style = {
            'stroke-width': Math.sqrt(edge.value)
          };
          return edge;
        }));

      d3.select('#graph').call(graph);
    });

  });
</script>

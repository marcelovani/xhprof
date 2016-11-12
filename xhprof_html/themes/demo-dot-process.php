<!DOCTYPE html>

<meta charset='utf-8'>
<link rel='stylesheet/less' type='text/css' href='./themes/graphyte//less/main.less'>
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

<h1>Process</h1>
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
    collisionDetection,
    forceLayout,
    labelLayout,
    zoomLayout
  ) {

    /**
     * Use xhprof data.
     */
    <?php
    $script = preg_replace('/(.+)/', '\'$1\' +', $script);
    $script = preg_replace('/\}\'\s*\+/', "}'", $script);
    ?>
    <?php echo 'var result = ' . $script . ';'; ?>

    var cd = collisionDetection();
    var force = forceLayout();
    var labels = labelLayout()
      .alignment('root');
    var zoom = zoomLayout();

    var graph = graphyte()
      .layout(cd ,labels, zoom, force);

    graph.import(result, 'dot');
    d3.select('#graph').call(graph);
    return;

    /**
     * Use file data.
     */
    d3.text('themes/graphyte/demo_data/demo-dot-process.gv', function(error, result) {
      if (error) throw error;

      var cd = collisionDetection();
      var force = forceLayout();
      var labels = labelLayout()
        .alignment('root');
      var zoom = zoomLayout();

      var graph = graphyte()
        .layout(cd ,labels, zoom, force);

      graph.import(result, 'dot');
      d3.select('#graph').call(graph);
    });
  });
</script>
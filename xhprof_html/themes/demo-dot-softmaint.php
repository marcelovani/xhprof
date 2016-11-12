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

<h1>Softmaint</h1>
<div id='graph'></div>
<a href='http:/ /www.graphviz.org/content/softmaint'>Source</a>

<script>
  require([
    'd3',
    'js/core',
    'layouts/force',
    'layouts/zoom'
  ], function(
    d3,
    graphyte,
    forceLayout,
    zoomLayout
  ) {

	  /**
	   * Use xhprof data.
	   */
    <?php
    $script = preg_replace('/(.+)/', '\'$1\' +', $script);
    $script = preg_replace('/\}\'\s*\+/', "}'", $script);
    ?>
    <?php echo 'var result = ' . $script; ?>

    var force = forceLayout();
    var zoom = zoomLayout();

    var graph = graphyte()
      .arrows(true)
      .layout(zoom, force);

    graph.import(result, 'dot');
    d3.select('#graph').call(graph);

    return;

    /**
     * Use file data.
     */
    d3.text('themes/graphyte/demo_data/demo-dot-softmaint.gv', function(error, result) {
      if (error) throw error;

      var force = forceLayout();
      var zoom = zoomLayout();

      var graph = graphyte()
        .arrows(true)
        .layout(zoom, force);

      graph.import(result, 'dot');
      d3.select('#graph').call(graph);
    });
  });
</script>

<script>
  //hljs.initHighlightingOnLoad();
</script>

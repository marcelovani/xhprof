<?php //die(__FILE__); ?>

<!-- http://www.script-tutorials.com/webgl-with-three-js-lesson-1/ -->
<!--http://www.graphviz.org/doc/info/output.html#d:plain-->
<!DOCTYPE html>
<html lang="en" >
    <head>
        <meta charset="utf-8" />
        <meta name="author" content="Script Tutorials" />
        <title>3D Debugger</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link href="/themes/experiments/3d/css/main.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <script src="../../node_modules/jquery/dist/jquery.min.js"></script>
        <script src="../../node_modules/jquery-csv/src/jquery.csv.min.js"></script>
        <script src="../../node_modules/viz.js/viz.js"></script>
        <script src="../../node_modules/three/build/three.in.js"></script>
        <script src="/themes/experiments/3d/js/three.min.js"></script>
        <script src="/themes/experiments/3d/js/THREEx.WindowResize.js"></script>
        <script src="/themes/experiments/3d/js/OrbitControls.js"></script>
        <script src="/themes/experiments/3d/js/stats.min.js"></script>
        <script src="/themes/experiments/3d/js/utils.js"></script>
        <script src="/themes/experiments/3d/js/main.js"></script>
        <output id="list"></output>
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-87733842-1', 'auto');
          ga('send', 'pageview', '3d');

        </script>
        <script>
          <?php
          // Prepare graphlib-dot object.
          $digraph = preg_replace('/(.+)/', '\'$1\' +', $digraph);
          $digraph = preg_replace('/\}\'\s*\+/', "}'", $digraph);
          print 'var g = ' . $digraph . ';';
          ?>
          initializeLesson(g);
        </script>
    </body>
</html>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Xhprof <?php echo $run; ?></title>
  <link rel="stylesheet" media="all" href="/themes/viz.js/main.css">
<!--      <link rel="stylesheet" media="all" href="/themes/viz-edit/main.css">-->
  <script src="./node_modules/jquery/dist/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/viz.js/2.1.2/viz.js" crossorigin="anonymous"
          referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/lz-string/1.5.0/lz-string.min.js" crossorigin="anonymous"
          referrerpolicy="no-referrer"></script>
  <script src="//cdn.jsdelivr.net/npm/svg-pan-zoom@3.5.0/dist/svg-pan-zoom.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.34.2/ace.min.js"></script>
</head>
<body>

<div id="app">
  <div id="header">
  </div>
  <div id="panes">
    <div id="editor"><?php echo $digraph; ?></div>
    <div id="graph">
      <?php include('graph_filter_options.php'); ?>
      <div id="output">
        <div id="error"></div>
        <?php include(getcwd() . '/themes/templates/loader_animation.php'); ?>
      </div>
      <div id="status"></div>
    </div>
  </div>
</div>

<!--    <script src="//cdn.jsdelivr.net/npm/svg-pan-zoom@3.5.0/dist/svg-pan-zoom.min.js"></script>-->
<!--    <script src="./themes/viz.js/js/svg_zoom.js"></script>-->

<!--    <script src="./themes/viz.js/js/ace/ace.js"></script>-->
<!--    <script src="./node_modules/viz.js/viz.js"></script>-->
<!--    <script src="//webgraphviz.com/viz.js"></script>-->
<!--    <script src="//magjac.com/graphviz-visual-editor/static/js/main.308319cd.js"></script>-->
<!--    <script src="full.render.js"></script>-->
<!--    <script data-main="/themes/viz.js/js/main" src="/node_modules/requirejs/require.js"></script>-->
<!--    <script src="/themes/viz.js/js/main.js"></script>-->
<!--    <script src="./node_modules/svg-pan-zoom/dist/svg-pan-zoom.min.js"></script>-->
<!--    <script src="./themes/3d/js/three.min.js"></script>-->
<!--    <script src="./themes/3d/js/THREEx.WindowResize.js"></script>-->
<!--    <script src="./themes/3d/js/OrbitControls.js"></script>-->
<!--    <script src="./themes/3d/js/stats.min.js"></script>-->
<!--    <script src="./themes/3d/js/main.js"></script>-->
<!---->
<!--    <script>-->
<!--      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){-->
<!--        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),-->
<!--        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)-->
<!--      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');-->
<!---->
<!--      ga('create', 'UA-87733842-1', 'auto');-->
<!--      ga('send', 'pageview', 'callgraph');-->
<!---->
<!--    </script>-->

<script>

  // var editor = ace.edit("editor");
  // editor.getSession().setMode("ace/mode/dot");

  var parser = new DOMParser();
  // var worker;
  // var worker3D;
  var result;

  function updateGraph() {
    // var params = {
    //   src: editor.getSession().getDocument().getValue(),
    //   options: {
    //     engine: document.querySelector("#engine select").value,
    //     format: document.querySelector("#format select").value
    //   }
    // };

    // Instead of asking for png-image-element directly, which we can't do in a worker,
    // ask for SVG and convert when updating the output.
    // if (params.options.format == "png") {
    //   params.options.format = "svg";
    // }
    // if (params.options.format == "3D") {
    //   params.options.format = "plain";
    //   params.options.callback = "3D";
    // }
    //console.log(params);

    // if (worker) {
    //   worker.terminate();
    // }

    // document.querySelector("#output").classList.add("working");
    // document.querySelector("#output").classList.remove("error");


    // worker = new Worker("./themes/viz.js/worker.js");

    // worker.onmessage = function(e) {
    //   document.querySelector("#output").classList.remove("working");
    //   document.querySelector("#output").classList.remove("error");
    //
    //   result = e.data;
    //   if (params.options.callback == '3D') {
    //     startWorker3D(params);
    //   }
    //   else {
    //     updateOutput();
    //   }
    // }

    // worker.onerror = function(e) {
    //   document.querySelector("#output").classList.remove("working");
    //   document.querySelector("#output").classList.add("error");
    //
    //   var message = e.message === undefined ? "An error occurred while processing the graph input." : e.message;
    //
    //   var error = document.querySelector("#error");
    //   while (error.firstChild) {
    //     error.removeChild(error.firstChild);
    //   }
    //
    //   document.querySelector("#error").appendChild(document.createTextNode(message));
    //
    //   console.error(e);
    //   e.preventDefault();
    // }
    //
    // worker.postMessage(params);
    update_graphviz();
    attach_svg_zoom();
  }

  //     function startWorker3D( params ) {
  //       worker3D = new Worker("./themes/viz.js/worker-3d.js");
  //
  //       worker3D.onmessage = function ( e ) {
  //         result = e.data;
  //         updateOutput();
  //       }
  //
  //       worker3D.onerror = function ( e ) {
  //         var message = e.message === undefined ? "An error occurred while processing the graph input." : e.message;
  //         alert( message );
  //         console.error( e );
  //         e.preventDefault();
  //       }
  //
  // //      var params = {
  // //        src: graph,
  // //        options: {
  // //          engine: 'dot',
  // //          format: 'plain'
  // //        }
  // //      };
  //
  //       console.log('Start work 3d');
  //       params.options.innerWidth = window.innerWidth;
  //       params.options.innerHeight = window.innerHeight;
  //       worker3D.postMessage( params );
  //     }

  function updateOutput() {
    var format = document.querySelector("#format select").value;
    var graph = document.querySelector("#output");

    var svg = graph.querySelector("svg");
    if (svg) {
      graph.removeChild(svg);
    }

    var text = graph.querySelector("#text");
    if (text) {
      graph.removeChild(text);
    }

    var img = graph.querySelector("img");
    if (img) {
      graph.removeChild(img);
    }

    if (!result) {
      return;
    }
    if (format == "3D") {
      console.log(result);
      lesson1.init(result);
      animate();
    } else if (format == "svg" && !document.querySelector("#raw input").checked) {
      var svg = parser.parseFromString(result, "image/svg+xml");
      graph.appendChild(svg.documentElement);

      // jQuery( document ).ready( function () {
      //   if (jQuery( '#output svg' ).length > 0) {
      //     window.svgZoom = svgPanZoom( '#output svg', {
      //       zoomEnabled: true,
      //       controlIconsEnabled: true,
      //       fit: true,
      //       center: true
      //       // viewportSelector: document.getElementById('demo-tiger').querySelector('#g4') // this option will make library to misbehave. Viewport should have no transform attribute
      //     } );
      //   }
      // } );
    } else if (format == "png") {
      var image = Viz.svgXmlToPngImageElement(result);
      graph.appendChild(image);
    } else {
      var text = document.createElement("div");
      text.id = "text";
      text.appendChild(document.createTextNode(result));
      graph.appendChild(text);
    }
  }

  // editor.on("change", function() {
  //   updateGraph();
  // });

  document.querySelector("#engine select").addEventListener("change", function () {
    updateGraph();
  });

  document.querySelector("#format select").addEventListener("change", function () {
    if (format === "svg") {
      document.querySelector("#raw").classList.remove("disabled");
      document.querySelector("#raw input").disabled = false;
    } else {
      document.querySelector("#raw").classList.add("disabled");
      document.querySelector("#raw input").disabled = true;
    }

    updateGraph();
  });

  document.querySelector("#raw input").addEventListener("change", function () {
    updateOutput();
  });

  // updateGraph();

  jQuery(document).ready(function () {
    // Emulate click on link when clicking on checkbox.
    jQuery('#options .show_internal').click(function () {
      window.location = jQuery(this).find('a').attr('href');
    });
  });
</script>

</body>
<script src="./themes/viz-edit/js/main.js"></script>
</html>

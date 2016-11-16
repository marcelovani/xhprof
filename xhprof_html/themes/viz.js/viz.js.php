<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Xhprof <?php echo $run; ?></title>
    <link rel="stylesheet" href="./themes/css/xhprof.css">
    <link rel="stylesheet" href="./themes/css/2d.css">
    <style>

    #app {
      display: flex;
      display: -webkit-flex;
      flex-direction: column;
      -webkit-flex-direction: column;
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 0;
    }

    #header {
      flex: 0 0 auto;
      -webkit-flex: 0 0 auto;
    }

    #panes {
      display: flex;
      display: -webkit-flex;
      flex: 1 1 auto;
      -webkit-flex: 1 1 auto;
    }
    
    #editor {
      flex: 1 1 50%;
      -webkit-flex: 1 1 50%;
      display: none;
    }

    #graph {
      display: flex;
      display: -webkit-flex;
      flex-direction: column;
      -webkit-flex-direction: column;
      flex: 1 1 50%;
      -webkit-flex: 1 1 50%;
    }
    
    #options {
      flex: 0 0 auto;
      -webkit-flex: 0 0 auto;
    }
    
    #output {
      flex: 1 1 auto;
      -webkit-flex: 1 1 auto;
      position: relative;
      overflow: auto;
    }
    
    
    #editor {
      border-right: 1px solid #ccc;
    }

    #header {
      border-bottom: 1px solid #ccc;
      text-align: center;
    }

    #header, .params b {
      font-size: 18px;
    }

    #options {
      background: #eee;
      border-bottom: 1px solid #ccc;
      padding: 8px;
    }
    
    #options label {
      margin-right: 8px;
    }
    
    #options #raw.disabled {
      opacity: 0.5;
    }
    
    #output svg {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
    }
    
    #output #text {
      font-size: 12px;
      font-family: monaco, courier, monospace;
      white-space: pre;
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
    }
    
    #output img {
      display: block;
      margin: 0 auto;
    }
    
    #output.working svg, #output.error svg,
    #output.working #text, #output.error #text,
    #output.working img, #output.error img {
      opacity: 0.4;
    }
    
    #output.error #error {
      display: inherit;
    }
    
    #output #error {
      display: none;
      position: absolute;
      top: 20px;
      left: 20px;
      margin-right: 20px;
      background: red;
      color: white;
      z-index: 1;
    }
    
    </style>
  </head>
  <body>

    <div id="app">
      <div id="header">
      </div>
      <div id="panes">
        <div id="editor"># http://www.graphviz.org/content/cluster
<?php echo $script; ?>
        </div>
        <div id="graph">
          <div id="options">
            <?php echo get_home_button(); ?>
            <label id="engine">
              Engine:
              <select>
                <option>circo</option>
                <option selected>dot</option>
                <option>fdp</option>
                <option>neato</option>
                <option>osage</option>
                <option>twopi</option>
              </select>
            </label>

            <label id="format">
              Format:
              <select>
                <option selected>svg</option>
                <option>png</option>
                <option>xdot</option>
                <option>plain</option>
                <option>ps</option>
                <option>3D</option>
              </select>
            </label>

            <label id="raw">
              <input type="checkbox"> Show raw output
            </label>

            <?php echo get_show_internal_button('Show internal functions', 1); ?>
            <span class="threshold">Threshold
            <?php
              echo get_threshold_button('++', 0.1, $threshold);
              echo get_threshold_button('+', 0.01, $threshold);
              echo get_threshold_button('-', -0.01, $threshold);
              echo get_threshold_button('--', -0.1, $threshold);
            ?>
            </span>

          </div>

          <div id="output">
            <div id="error"></div>
            <svg version="1.1" id="L6" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                 viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
              <rect fill="none" stroke="#fff" stroke-width="4" x="25" y="25" width="50" height="50">
                <animateTransform
                  attributeName="transform"
                  dur="0.5s"
                  from="0 50 50"
                  to="180 50 50"
                  type="rotate"
                  id="strokeBox"
                  attributeType="XML"
                  begin="rectBox.end"/>
              </rect>
              <rect x="27" y="27" fill="#fff" width="46" height="50">
                <animate
                  attributeName="height"
                  dur="1.3s"
                  attributeType="XML"
                  from="50"
                  to="0"
                  id="rectBox"
                  fill="freeze"
                  begin="0s;strokeBox.end"/>
              </rect>
            </svg>
          </div>
        </div>
      </div>
    </div>
    
    <script src="./themes/viz.js/js/ace/ace.js"></script>
    <script src="./node_modules/jquery/dist/jquery.min.js"></script>
    <script src="./node_modules/viz.js/viz.js"></script>
    <script src="./node_modules/svg-pan-zoom/dist/svg-pan-zoom.min.js"></script>
    <script>

    var editor = ace.edit("editor");
    editor.getSession().setMode("ace/mode/dot");

    var parser = new DOMParser();
    var worker;
    var result;

    function updateGraph() {
      if (worker) {
        worker.terminate();
      }

      document.querySelector("#output").classList.add("working");
      document.querySelector("#output").classList.remove("error");

      worker = new Worker("./themes/viz.js/worker.js");

      worker.onmessage = function(e) {
        document.querySelector("#output").classList.remove("working");
        document.querySelector("#output").classList.remove("error");
        
        result = e.data;
        
        updateOutput();
      }

      worker.onerror = function(e) {
        document.querySelector("#output").classList.remove("working");
        document.querySelector("#output").classList.add("error");
        
        var message = e.message === undefined ? "An error occurred while processing the graph input." : e.message;
        
        var error = document.querySelector("#error");
        while (error.firstChild) {
          error.removeChild(error.firstChild);
        }
        
        document.querySelector("#error").appendChild(document.createTextNode(message));
        
        console.error(e);
        e.preventDefault();
      }
      
      var params = {
        src: editor.getSession().getDocument().getValue(),
        options: {
          engine: document.querySelector("#engine select").value,
          format: document.querySelector("#format select").value
        }
      };
      
      // Instead of asking for png-image-element directly, which we can't do in a worker,
      // ask for SVG and convert when updating the output.
      
      if (params.options.format == "png") {
        params.options.format = "svg";
      }
      if (params.options.format == "3D") {
        params.options.format = "plain";
      }
      console.log(params);
      worker.postMessage(params);
    }
    
    function updateOutput() {
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
      if (document.querySelector("#format select").value == "3D") {

      }
      if (document.querySelector("#format select").value == "svg" && !document.querySelector("#raw input").checked) {
        var svg = parser.parseFromString(result, "image/svg+xml");
        graph.appendChild(svg.documentElement);
      } else if (document.querySelector("#format select").value == "png") {
        var image = Viz.svgXmlToPngImageElement(result);
        graph.appendChild(image);
      } else {
        var text = document.createElement("div");
        text.id = "text";
        text.appendChild(document.createTextNode(result));
        graph.appendChild(text);
      }

      jQuery( document ).ready( function () {
        if (jQuery( '#output svg' ).length > 0) {
          window.svgZoom = svgPanZoom( '#output svg', {
            zoomEnabled: true,
            controlIconsEnabled: true,
            fit: true,
            center: true
            // viewportSelector: document.getElementById('demo-tiger').querySelector('#g4') // this option will make library to misbehave. Viewport should have no transform attribute
          } );
        }
      } );
    }

    editor.on("change", function() {
      updateGraph();
    });
    
    document.querySelector("#engine select").addEventListener("change", function() {
      updateGraph();
    });

    document.querySelector("#format select").addEventListener("change", function() {
      if (document.querySelector("#format select").value === "svg") {
        document.querySelector("#raw").classList.remove("disabled");
        document.querySelector("#raw input").disabled = false;
      } else {
        document.querySelector("#raw").classList.add("disabled");
        document.querySelector("#raw input").disabled = true;
      }
      
      updateGraph();
    });

    document.querySelector("#raw input").addEventListener("change", function() {
      updateOutput();
    });
    
    updateGraph();

    jQuery( document ).ready( function () {
      // Emulate click on link when clicking on checkbox.
      jQuery('#options .show_internal' ).click( function () {
        window.location = jQuery(this).find('a' ).attr('href');
      });
    });
    </script>
    
  </body>
</html>

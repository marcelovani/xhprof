<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Viz.js</title>
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
            <span><a class="button" href="' . $_xhprof['url'] . '/?run=' . $run . '">Back</a></span>
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
                <option>png-image-element</option>
                <option>xdot</option>
                <option>plain</option>
                <option>ps</option>
              </select>
            </label>

            <label id="raw">
              <input type="checkbox"> Show raw output
            </label>

            <?php echo get_show_internal_button('Show internal functions', 1); ?>
            <span>Threshold
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
          </div>
        </div>
      </div>
    </div>
    
    <script src="./themes/viz.js/js/ace/ace.js"></script>
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
      
      if (params.options.format == "png-image-element") {
        params.options.format = "svg";
      }
      
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
      
      if (document.querySelector("#format select").value == "svg" && !document.querySelector("#raw input").checked) {
        var svg = parser.parseFromString(result, "image/svg+xml");
        graph.appendChild(svg.documentElement);
      } else if (document.querySelector("#format select").value == "png-image-element") {
        var image = Viz.svgXmlToPngImageElement(result);
        graph.appendChild(image);
      } else {
        var text = document.createElement("div");
        text.id = "text";
        text.appendChild(document.createTextNode(result));
        graph.appendChild(text);
      }

      window.svgZoom = svgPanZoom( '#output svg', {
        zoomEnabled: true,
        controlIconsEnabled: true,
        fit: true,
        center: true,
        // viewportSelector: document.getElementById('demo-tiger').querySelector('#g4') // this option will make library to misbehave. Viewport should have no transform attribute
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
    
    </script>
    
  </body>
</html>

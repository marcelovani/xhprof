function update_graphviz() {
  window.URL = window.URL || window.webkitURL;
  var el_stetus = document.getElementById("status"),
      t_stetus = -1,
      reviewer = document.getElementById("output"),
      scale = window.devicePixelRatio || 1,
      downloadBtn = document.getElementById("download"),
      lastHD = -1,
      worker = null,
      parser = new DOMParser(),
      showError = null,
      formatEl = document.querySelector("#format select"),
      engineEl = document.querySelector("#engine select"),
      rawEl = document.querySelector("#raw input"),
      shareEl = document.querySelector("#share"),
      shareURLEl = document.querySelector("#shareurl"),
      errorEl = document.querySelector("#error");

  // var svg_div = $('#output');
  // var data = 'digraph { a -> b }';
  $(document).ready(function () {
    reviewer = document.getElementById("output");
    // var data = getDotGraph();
    // console.log('' + data);
    // svg_div.html('');
    // var svg = Viz(data, 'svg');
    // svg_div.html(svg);

   renderGraph();
  });

  function renderGraph() {
    reviewer.classList.add("working");
    reviewer.classList.remove("error");

    if (worker) {
      worker.terminate();
    }

    worker = new Worker("/themes/viz.js/js/full.render.min.js");
    worker.addEventListener("message", function (e) {
      if (typeof e.data.error !== "undefined") {
        var event = new CustomEvent("error", {"detail": new Error(e.data.error.message)});
        worker.dispatchEvent(event);
        return
      }
      show_status("done", 500);
      reviewer.classList.remove("working");
      reviewer.classList.remove("error");
      updateOutput(e.data.result);
    }, false);
    worker.addEventListener('error', function (e) {
      show_error(e.detail);
    }, false);

    show_status("rendering...");
    var params = {
      "src": 'digraph { a -> b }', //getDotGraph(),
      "id": new Date().toJSON(),
      "options": {
        "files": [],
        "format": formatEl.value === "png-image-element" ? "svg" : formatEl.value,
        "engine": engineEl.value
      },
    };
    worker.postMessage(params);
  }

  function show_status(text, hide) {
    hide = hide || 0;
    clearTimeout(t_stetus);
    el_stetus.innerHTML = text;
    if (hide) {
      t_stetus = setTimeout(function () {
        el_stetus.innerHTML = "";
      }, hide);
    }
  }

  function show_error(e) {
    show_status("error", 500);
    reviewer.classList.remove("working");
    reviewer.classList.add("error");

    var message = e.message === undefined ? "An error occurred while processing the graph input." : e.message;
    while (errorEl.firstChild) {
      errorEl.removeChild(errorEl.firstChild);
    }
    errorEl.appendChild(document.createTextNode(message));
  }
}


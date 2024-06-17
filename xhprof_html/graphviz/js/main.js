(function (document) {
    //http://stackoverflow.com/a/10372280/398634
    window.URL = window.URL || window.webkitURL;
    var el_stetus = document.getElementById("status"),
        t_stetus = -1,
        reviewer = document.getElementById("output"),
        scale = window.devicePixelRatio || 1,
        downloadBtn = document.getElementById("download") || null,
        editor = ace.edit("editor"),
        lastHD = -1,
        worker = null,
        parser = new DOMParser(),
        showError = null,
        formatEl = document.querySelector("#format select"),
        engineEl = document.querySelector("#engine select") || null,
        rawEl = document.querySelector("#raw input") || null,
        showInternalEl = document.querySelector("#show_internal") || null,
        shareEl = document.querySelector("#share"),
        shareURLEl = document.querySelector("#shareurl"),
        errorEl = document.querySelector("#error");

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

    function svgXmlToImage(svgXml, callback) {
        var pngImage = new Image(), svgImage = new Image();

        svgImage.onload = function () {
            var canvas = document.createElement("canvas");
            canvas.width = svgImage.width * scale;
            canvas.height = svgImage.height * scale;

            var context = canvas.getContext("2d");
            context.drawImage(svgImage, 0, 0, canvas.width, canvas.height);

            pngImage.src = canvas.toDataURL("image/png");
            pngImage.width = svgImage.width;
            pngImage.height = svgImage.height;

            if (callback !== undefined) {
                callback(null, pngImage);
            }
        }

        svgImage.onerror = function (e) {
            if (callback !== undefined) {
                callback(e);
            }
        }
        svgImage.src = svgXml;
    }

    function copyShareURL(e) {
        updateState();

        var content = encodeURIComponent(editor.getSession().getDocument().getValue());

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "https://api-ssl.bitly.com/v4/shorten", true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        /* love and peace; don't let me down :) */
        xhr.setRequestHeader('Authorization', 'Bearer 5959ae0ffc42f5e6b8cee4ebf1b7ee0218bfc291');
        const longUrl = new URL(location.href);
        longUrl.hash = content;

        xhr.send(JSON.stringify({ "long_url": longUrl.toString() }));
        xhr.onreadystatechange = function () {
            if (this.readyState != 4) return;

            shareURLEl.style.display = "inline";
            if (this.status >= 200 && this.status < 300 && this.responseText.indexOf('"link":') >= 0) {
                var result = JSON.parse(this.responseText);
                shareURLEl.value = result.link;
            } else {
                const compressedContent = LZString.compressToEncodedURIComponent(editor.getSession().getDocument().getValue());
                const compressedUrl = new URL(location.href);
                compressedUrl.searchParams.append("compressed", compressedContent);
                compressedUrl.hash = "";

                shareURLEl.value = compressedUrl.toString();
            }
        };
    }

    function copyToClipboard(str) {
        const el = document.createElement('textarea');
        el.value = str;
        el.setAttribute('readonly', '');
        el.style.position = 'absolute';
        el.style.left = '-9999px';
        document.body.appendChild(el);
        const selected =
            document.getSelection().rangeCount > 0
                ? document.getSelection().getRangeAt(0)
                : false;
        el.select();
        var result = document.execCommand('copy')
        document.body.removeChild(el);
        if (selected) {
            document.getSelection().removeAllRanges();
            document.getSelection().addRange(selected);
        }
        return result;
    };

    function updateGraph(event) {
        const url = window.location;
        const protocol = window.location.protocol;
        const host = window.location.hostname;
        const port = window.location.port;
        const path = window.location.pathname;
        let query = window.location.search;

        let el = event.currentTarget;
        let show_internal = null;
        if (event.type == 'click') {
            if (event.target.getAttribute('id') == 'show_internal') {
                show_internal = el.classList.contains('is-pressed') ? '0' : '1';
                const regexPattern = /(show_internal=)\d{1}/;
                query = query.replace(regexPattern, '$1' + show_internal);
            }
        }
        const updated_url = protocol + '//' + host + ':' + port + path + query;
        window.location.href = updated_url;
    }

    function renderGraph() {
        reviewer.classList.add("working");
        reviewer.classList.remove("error");

        if (worker) {
            worker.terminate();
        }


        worker = new Worker("/graphviz/js/lite.render.min.js");
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
            "src": editor.getSession().getDocument().getValue(),
            "id": new Date().toJSON(),
            "options": {
                "files": [],
                "format": formatEl.value === "png-image-element" ? "svg" : formatEl.value,
                "engine": (engineEl !== null && engineEl.value) || 'dot',
            },
        };
        worker.postMessage(params);
    }

    function updateState() {
        var content = encodeURIComponent(editor.getSession().getDocument().getValue());
        history.pushState({"content": content}, "", "#" + content)
    }

    function updateOutput(result) {
        if (rawEl !== null) {
            if (formatEl.value === "svg") {
                document.querySelector("#raw").classList.remove("disabled");
                rawEl.disabled = false;
            } else {
                document.querySelector("#raw").classList.add("disabled");
                rawEl.disabled = true;
            }
        }

        var text = reviewer.querySelector("#text");
        if (text) {
            reviewer.removeChild(text);
        }

        var svg = reviewer.querySelector("svg");
        if (svg) {
            reviewer.removeChild(svg);
        }

        if (!result) {
            return;
        }

        reviewer.classList.remove("working");
        reviewer.classList.remove("error");

        if (formatEl.value == "svg") {
            var svg = parser.parseFromString(result, "image/svg+xml");
            //get svg source.
            var serializer = new XMLSerializer();
            var source = serializer.serializeToString(svg);
            //add name spaces.
            if(!source.match(/^<svg[^>]+xmlns="http\:\/\/www\.w3\.org\/2000\/svg"/)){
                source = source.replace(/^<svg/, '<svg xmlns="http://www.w3.org/2000/svg"');
            }
            if(!source.match(/^<svg[^>]+"http\:\/\/www\.w3\.org\/1999\/xlink"/)){
                source = source.replace(/^<svg/, '<svg xmlns:xlink="http://www.w3.org/1999/xlink"');
            }
            //add xml declaration
            if (!source.startsWith("<" + "?xml version")) {
                source = '<' + '?xml version="1.0" standalone="no"?>\r\n' + source;
            }
            // https://stackoverflow.com/questions/18925210/download-blob-content-using-specified-charset
            //const blob = new Blob(["\ufeff", svg], {type: 'image/svg+xml;charset=utf-8'});
            if (downloadBtn) {
                const url = "data:image/svg+xml;charset=utf-8,"+encodeURIComponent(source);
                downloadBtn.href = url;
                downloadBtn.download = "graphviz.svg";
            }
            // var a = document.createElement("a");
            var svgEl = svg.documentElement;
            // a.appendChild(svgEl);
            reviewer.appendChild(svgEl);
            svgPanZoom(svgEl, {
                zoomEnabled: true,
                controlIconsEnabled: true,
                fit: true,
                center: true,
            });
        } else if (formatEl.value == "png-image-element") {
            var resultWithPNGHeader = "data:image/svg+xml;base64," + btoa(unescape(encodeURIComponent(result)));
            svgXmlToImage(resultWithPNGHeader, function (err, image) {
                if (err) {
                    show_error(err)
                    return
                }
                image.setAttribute("title", "graphviz");
                downloadBtn.href = image.src;
                downloadBtn.download = "graphviz.png";
                var a = document.createElement("a");
                a.appendChild(image);
                reviewer.appendChild(a);
            })
        } else {
            var text = document.createElement("div");
            text.id = "text";
            text.appendChild(document.createTextNode(result));
            reviewer.appendChild(text);
        }
    }

    editor.setTheme("ace/theme/twilight");
    editor.getSession().setMode("ace/mode/dot");
    editor.getSession().on("change", function () {
        clearTimeout(lastHD);
        lastHD = setTimeout(renderGraph, 1500);
    });

    window.onpopstate = function(event) {
        if (event.state != null && event.state.content != undefined) {
            editor.getSession().setValue(decodeURIComponent(event.state.content));
        }
    };

    formatEl.addEventListener("change", renderGraph);

    if (engineEl !== null)
        engineEl.addEventListener("change", renderGraph);

    if (rawEl !== null)
        rawEl.addEventListener("change", renderGraph);

    showInternalEl.addEventListener("click", updateGraph);

    if (typeof share != 'undefined') {
        share.addEventListener("click", copyShareURL);
    }

    // Since apparently HTMLCollection does not implement the oh so convenient array functions
    HTMLOptionsCollection.prototype.indexOf = function(name) {
        for (let i = 0; i < this.length; i++) {
            if (this[i].value == name) {
                return i;
            }
        }

        return -1;
    };

    /* come from sharing */
    const params = new URLSearchParams(location.search.substring(1));
    if (params.has('engine')) {
        const engine = params.get('engine');
        const index = engineEl !== null && engineEl.options.indexOf(engine);
        if (engineEl !== null && index > -1) { // if index exists
            engineEl.selectedIndex = index;
        } else {
            show_error({ message: `invalid engine ${engine} selected` });
        }
    }

    if (params.has('raw')) {
        editor.getSession().setValue(params.get('raw'));
        renderGraph();
    } else if (params.has('compressed')) {
        const compressed = params.get('compressed');
        editor.getSession().setValue(LZString.decompressFromEncodedURIComponent(compressed));
    } else if (params.has('url')) {
        const url = params.get('url');
        let ok = false;
        fetch(url)
            .then(res => {
                ok = res.ok;
                return res.text();
            })
            .then(res => {
                if (!ok) {
                    throw { message: res };
                }

                editor.getSession().setValue(res);
                renderGraph();
            }).catch(e => {
            show_error(e);
        });
    } else if (location.hash.length > 1) {
        editor.getSession().setValue(decodeURIComponent(location.hash.substring(1)));
    } else if (editor.getValue()) { // Init
        renderGraph();
    }

})(document);

function handleFileSelect(evt) {
	var files = evt.target.files; // FileList object

	// files is a FileList of File objects. List some properties.
	var output = [];
	for (var i = 0, f; f = files[i]; i++) {
		var reader = new FileReader();

		// Closure to capture the file information.
		reader.onload = (function (theFile) {
			return function (e) {
				//console.log('e readAsText = ', e);
				//console.log('e readAsText target = ', e.target);
				console.log('e readAsText target = ', e.target.result);
				try {
					//json = JSON.parse(e.target.result);
					//console.log('json global var has been set to parsed json of this file here it is unevaled = \n' + JSON.stringify(json));
					/*var diagraph = 'digraph call_graph {\n' +
					 'N0[shape=box , label="bar\nInc: 0.179 ms (66.1%)\nExcl: 0.048 ms (17.7%)\n5 total calls", width=3.3, height=2.3, fontsize=33, style=filled, fillcolor=yellow];' +
					 'N1[shape=box , label="strlen\nInc: 0.023 ms (8.5%)\nExcl: 0.023 ms (8.5%)\n5 total calls", width=1.6, height=1.1, fontsize=28];' +
					 'N2[shape=box , label="bar@1\nInc: 0.131 ms (48.3%)\nExcl: 0.029 ms (10.7%)\n4 total calls", width=2.0, height=1.4, fontsize=30, style=filled, fillcolor=yellow];' +
					 'N3[shape=box , label="bar@2\nInc: 0.102 ms (37.6%)\nExcl: 0.024 ms (8.9%)\n3 total calls", width=1.6, height=1.2, fontsize=29, style=filled, fillcolor=yellow];' +
					 'N4[shape=box , label="bar@3\nInc: 0.078 ms (28.8%)\nExcl: 0.073 ms (26.9%)\n2 total calls", width=5.0, height=3.5, fontsize=35, style=filled, fillcolor=red];' +
					 'N5[shape=box , label="bar@4\nInc: 0.005 ms (1.8%)\nExcl: 0.005 ms (1.8%)\n1 total calls", width=0.3, height=0.2, fontsize=14, style=filled, fillcolor=yellow];' +
					 'N6[shape=box , label="foo\nInc: 0.252 ms (93.0%)\nExcl: 0.050 ms (18.5%)\n1 total calls", width=3.4, height=2.4, fontsize=33, style=filled, fillcolor=red];' +
					 'N7[shape=box , label="xhprof_disable\nInc: 0.004 ms (1.5%)\nExcl: 0.004 ms (1.5%)\n1 total calls", width=0.3, height=0.2, fontsize=12];' +
					 'N8[shape=octagon , label="Total: 0.271 ms\nXHProf Run (Namespace=xhprof)\nExcl: 0.015 ms (5.5%)\n1 total calls", width=1.0, height=0.7, fontsize=25];' +
					 'N6 -> N0[arrowsize=2, style="setlinewidth(10)", label="5 calls", headlabel="100.0%", taillabel="88.6%" ];' +
					 'N6 -> N1[arrowsize=1, style="setlinewidth(1)", label="5 calls", headlabel="100.0%", taillabel="11.4%" ];' +
					 'N0 -> N2[arrowsize=2, style="setlinewidth(10)", label="4 calls", headlabel="100.0%", taillabel="100.0%" ];' +
					 'N2 -> N3[arrowsize=2, style="setlinewidth(10)", label="3 calls", headlabel="100.0%", taillabel="100.0%" ];' +
					 'N3 -> N4[arrowsize=2, style="setlinewidth(10)", label="2 calls", headlabel="100.0%", taillabel="100.0%" ];' +
					 'N4 -> N5[arrowsize=2, style="setlinewidth(10)", label="1 call", headlabel="100.0%", taillabel="100.0%" ];' +
					 'N8 -> N6[arrowsize=2, style="setlinewidth(10)", label="1 call", headlabel="100.0%", taillabel="98.4%" ];' +
					 'N8 -> N7[arrowsize=1, style="setlinewidth(1)", label="1 call", headlabel="100.0%", taillabel="1.6%" ];' +
					 '}';*/
					var diagraph =  e.target.result;
					//console.log(diagraph);
					g = graphlibDot.parse( diagraph );
					console.log( g.nodes() );
					console.log( g.edges() );
					initializeLesson();

				} catch (ex) {
					//console.log(e);
					alert('Error parsing trace');
				}
			}
		})(f);
		reader.readAsText(f);
	}

}

(function ($) {
	'use strict';

	var init = function () {
		$(document).ready(function () {
			document.getElementById('files').addEventListener('change', handleFileSelect, false);
		});
	};

	init();

})(jQuery);

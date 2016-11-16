//importScripts("../../../node_modules/jquery/dist/jquery.min.js");
importScripts("../../node_modules/viz.js/viz.js");
importScripts("../../../node_modules/jquery-csv/src/jquery.csv.min.js");
importScripts("../../themes/3D/js/three.min.js");
importScripts("../../themes/3D/js/THREEx.WindowResize.js");
importScripts("../../themes/3D/js/OrbitControls.js");
importScripts("../../themes/3D/js/stats.min.js");
importScripts("../../themes/3D/js/main.js");

onmessage = function(e) {
	console.log('worker 3d received msg');
  var result = Viz(e.data.src, e.data.options);
console.log(result);

	lesson1.init( result );
	animate();

	postMessage(result);
}

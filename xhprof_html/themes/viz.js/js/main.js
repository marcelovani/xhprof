// const Viz = require('viz.js');
// const { Module, render } = require('../full.render.js');
// const assert = require('assert');
// const path = require('path');
// const Worker = require('tiny-worker');

// require( ['node_modules/viz.js/viz.js', 'node_modules/tiny-worker/lib/worker.js'], function () {
//     let worker = new Worker(path.resolve(__dirname, '../full.render.js'));
//     let viz = new Viz({ worker });
//
//     return viz.renderString('digraph { a -> b; }')
//         .then(function(result) {
//             assert.ok(result);
//             worker.terminate();
//         });
// } );

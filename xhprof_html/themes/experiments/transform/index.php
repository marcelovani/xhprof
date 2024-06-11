<!DOCTYPE html>
<!-- saved from url=(0059)https://leapmotion.github.io/leapjs-plugins/main/transform/ -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  
  <title>Leap Transform Plugin</title>

  <style>
    body{
      margin: 0;
      font-family: Helvetica;
    }
    canvas{
      pointer-events: none;
    }
    input{
      cursor: pointer;
    }
    #connect-leap{
      bottom: 0;
      top: auto !important;
    }

    table, th, td {
      border: 1px solid #aaa;
    }
    table {
      border-collapse: collapse;
    }
    td, th {
      vertical-align: top;
      padding: 3px;
    }
    th {
      color: #555;
    }
    #view-source {
      position: absolute;
      top: 0;
      right: 0;
      margin: 10px;
    }
  </style>


  <script src="./themes/experiments/transform/three.min.js"></script>
  <script src="./themes/experiments/transform/leap-0.6.3.min.js"></script>
  <script src="./themes/experiments/transform/leap-plugins-0.1.11pre.js"></script>
  <script src="./themes/experiments/transform/leap.rigged-hand-0.1.5.min.js"></script>
  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-87733842-1', 'auto');
    ga('send', 'pageview', 'transfor');

  </script>
</head>
<body>

<a id="view-source" href="view-source:https://leapmotion.github.io/leapjs-plugins/main/transform/" target="_blank">View Source</a>

<h1>Transform Plugin</h1>
<p>
  Transforms Leap data based off of a rotation matrix or a THREE.js rotation and vectors.
</p>
<p>
  Here we use the riggedHand to visualize the transformations, but it is not required.  All data in the frame is altered
  by the transform plugin.
</p>
<p>
  Parameters can be either static objects or methods which transform on every frame.
</p>
<p>
  In this way, you can then transform individual hands based upon their id.
</p>

<br>

<div style="float: left;">
  <label>Rotation: <span id="rotationOutput">0</span>π</label><br>
  <input id="rotationInput" type="range" min="-1" max="1" value="0" step="0.001">
</div>

<div style="float: left;">
  <label>Z Position: <span id="positionOutput">0</span></label><br>
  <input id="positionInput" type="range" min="-1000" max="300" value="0" step="1">
</div>

<div style="float: left;">
  <label>Scale: <span id="scaleOutput">0.3</span></label><br>
  <input id="scaleInput" type="range" min="0.1" max="1" value="0.3" step="0.01">
  <!-- note - there seems to be a strange issue with THREEjs sections of the hand disappearing for scales larger than 1.
       Scale set as an option for the riggedHand will cause the issue.  -->
</div>
<div style="float: left; padding: 10px">
  <button id="showHide">Show/Hide</button>
</div>

<br>

<pre><code>
  // At the simplest:
  Leap.loop()
    .use('transform', {
      // move 20 cm back.
      position: new THREE.Vector3(0,0,-200)
    });
</code></pre>

<br>

<h3>Options</h3>

<table>
  <tbody><tr>
    <th>Name</th>
    <th>Default</th>
    <th>Description</th>
  </tr>
  <tr>
    <td>VR</td>
    <td>false</td>
    <td>
      Sets scale, position, and rotation transforms for HMD mode.  These are: units in meters, 8cm forward, and all x, y, and z axis flipped. Also tells tracking to use head-mounted device mode.
    </td>
  </tr>
  <tr>
    <td>position</td>
    <td>undefined</td>
    <td>A THREE.Vector3 position offset vector</td>
  </tr>
  <tr>
    <td>quaternion</td>
    <td>undefined</td>
    <td>A THREE.Quaternion rotation</td>
  </tr>
  <tr>
    <td>scale</td>
    <td>undefined</td>
    <td>A THREE.Vector scale vector, or a single scalar to be applied to all three axis.</td>
  </tr>
  <tr>
    <td>effectiveParent</td>
    <td>undefined</td>
    <td>A THREE.Object3d, or anything which responds to `matrixWorld` and `scale`. This matrixWorld is applied to the
      hand data as well as any transformations specified through the other options.  This allows hands to be "attached"
      to a camera.</td>
  </tr>
</tbody></table>






<script>
  // all units in mm
  var initScene = function () {
    window.scene = new THREE.Scene();
    window.renderer = new THREE.WebGLRenderer({
      alpha: true
    });

    window.renderer.setClearColor(0x000000, 0);
    window.renderer.setSize(window.innerWidth, window.innerHeight);

    window.renderer.domElement.style.position = 'fixed';
    window.renderer.domElement.style.top = 0;
    window.renderer.domElement.style.left = 0;
    window.renderer.domElement.style.width = '100%';
    window.renderer.domElement.style.height = '100%';

    document.body.appendChild(window.renderer.domElement);

    window.scene.add(new THREE.AmbientLight(0x888888));

    var pointLight = new THREE.PointLight(0xFFffff);
    pointLight.position = new THREE.Vector3(-20, 10, 100);
    pointLight.lookAt(new THREE.Vector3(0, 0, 0));
    window.scene.add(pointLight);

    window.camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 10000);
    window.camera.position.fromArray([0, 160, 400]);
    window.camera.lookAt(new THREE.Vector3(0, 0, 0));

    window.addEventListener('resize', function () {

      camera.aspect = window.innerWidth / window.innerHeight;
      camera.updateProjectionMatrix();
      renderer.setSize(window.innerWidth, window.innerHeight);
      renderer.render(scene, camera);

    }, false);

    scene.add(camera);


    renderer.render(scene, camera);
  };

  initScene();




  Leap.loop()
  // note that transform must be _before_ rigged hand
  .use('transform', {
    quaternion: new THREE.Quaternion,
    position: new THREE.Vector3,
    scale: 0.3
  })
  .use('playback', {recording: 'finger-tap-54fps.json.lz'})
  .use('riggedHand', {
    dotsMode: false,
    parent: scene,
    renderFn: function(){
      renderer.render(scene, camera);
    }

  })
  .connect();

  window.transformPlugin = Leap.loopController.plugins.transform;

  document.getElementById('rotationInput').oninput = function(e){
    var value = e.target.value;
    transformPlugin.quaternion.setFromEuler(
        new THREE.Euler(0, Math.PI * parseFloat(value,10) , 0)
    );
    document.getElementById('rotationOutput').innerHTML = value;
  };

  document.getElementById('positionInput').oninput = function(e){
    var value = e.target.value;
    transformPlugin.position.set(
        0,0,parseInt(value, 10)
    );
    document.getElementById('positionOutput').innerHTML = value;
  };

  document.getElementById('scaleInput').oninput = function(e){
    var value = parseFloat(e.target.value,10);
    transformPlugin.scale.set(
        value, value, value
    );
    document.getElementById('scaleOutput').innerHTML = value;
  };

  document.getElementById('showHide').onclick = function(){
    var el = document.querySelector('canvas');
    if (el.style.display == 'none'){
      el.style.display = 'block'
    }else {
      el.style.display = 'none'
    }
  }

  document.getElementById('view-source').href = 'view-source:' + window.location.href;
  document.getElementById('view-source').target = "_blank";

</script><canvas width="2318" height="1264" style="width: 1159px; height: 632px; position: fixed; top: 0px; left: 0px;"></canvas><div id="connect-leap" style="width: 100%; position: absolute; top: 0px; left: 0px; padding: 10px; text-align: center; font-size: 18px; opacity: 0.8; display: block; z-index: 10; cursor: pointer;"><img class="playback-connect-leap" style="margin: 0px 2px -2px 0px; max-width: 100%;" src="data:image/gif;base64,R0lGODlh9AHmAPf/AOnxtbS0tc3eVTMzNM3NztvnhtbX1/Hy86ioqezs7Z6entzd3r7UJdHhZcnKzOjo6sXZO319fkJCQurq6xMTFPj65q2treHh4vT42uXl5/Lz9JCQkfP09FxcXebm54yMjsvMzSgoKdfleLGxsUtLTPDx8cHCwlNTVSIiI6CgoYSEhAUFBpmZmtHS1Li5ucHDxNHR0err7HZ2duDrl8XFxvP09vv98/Dx8trb3MjIyN7e3x4eHmZmZj4+PrzTHtDQ0uvs7dra2uzt7ZWVldfY2cPExtbX2AsLDImJiff4+MbHysjIyoCBgsXGyHp6er6+vsDVKm5ucOTk5XFxc83O0MbGx2FhYm1tbsDWLKWlpu70xRkZGkVFRtPU1pydoM/Q0bKztKamqCQkJeLi5O/w8Glpab/AwtjY2f7+/WtrbcfIytjZ26qrrDc4OdHS0nBxc7/VJ5KTlI6Oj9LS0/v8/b7UI3JzdMPDxIqKi5aWl7y9vi4uL8fHx3BwcNvc3pKSk62usCsrLN/f4Ly8vZydnp2dndXV1snJycXGym5vcefn6IuMjYWFhYODhiMjJCYmJ+fo6UhISR8fH/7+/hsbHP39/ejo6cHWLu/v8Lq7vPv7++nq6+3u7q+wsby9wL6/wvj4+Pz8/Pr6+vLy8+bn6O7u7vj4+e3u7/f3+PT19fn5+ff39/T09Pb29+jo6MHWL/b29vLy8vT09e7v8L2+v/Pz9O7u7/39/vHy9L2+wbi6ve/v7+no6fz9/fn6+vj5+fv7/Pr7+////v7//8XGx/X19vX29vb39+7v7/z8/enp6uPk5fr6+/n5+g8PENTU1Hh4eYeHh05OT+fo6JGSk0BAQOTl6CAgIS8vML+/wHNzc5eYmLa3t+Xm54OEhfn4+Y+PkFdXV1hZWv3++IiIiJGRkZubm3t8ff7+/35/gOjp6ru8vmRkZMTFyLO0tWJiY6eoqycnKGxsbkNDQ77TIl5eX6Kjo+Xm6QcHBwgICZSUlvLz8y0tLv///wAAAP///yH/C1hNUCBEYXRhWE1QPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS4zLWMwMTEgNjYuMTQ1NjYxLCAyMDEyLzAyLzA2LTE0OjU2OjI3ICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M2IChNYWNpbnRvc2gpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjQyMUExNDAzQjE2NzExRTNBMUE1RTA3Qjc0NDg0NDMxIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjQyMUExNDA0QjE2NzExRTNBMUE1RTA3Qjc0NDg0NDMxIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6NDIxQTE0MDFCMTY3MTFFM0ExQTVFMDdCNzQ0ODQ0MzEiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6NDIxQTE0MDJCMTY3MTFFM0ExQTVFMDdCNzQ0ODQ0MzEiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4B//79/Pv6+fj39vX08/Lx8O/u7ezr6uno5+bl5OPi4eDf3t3c29rZ2NfW1dTT0tHQz87NzMvKycjHxsXEw8LBwL++vby7urm4t7a1tLOysbCvrq2sq6qpqKempaSjoqGgn56dnJuamZiXlpWUk5KRkI+OjYyLiomIh4aFhIOCgYB/fn18e3p5eHd2dXRzcnFwb25tbGtqaWhnZmVkY2JhYF9eXVxbWllYV1ZVVFNSUVBPTk1MS0pJSEdGRURDQkFAPz49PDs6OTg3NjU0MzIxMC8uLSwrKikoJyYlJCMi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"></div>


</body></html>
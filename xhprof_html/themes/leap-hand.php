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


  <script src="./themes/leap-hand/three.min.js"></script>
  <script src="./themes/leap-hand/leap-0.6.3.min.js"></script>
  <script src="./themes/leap-hand/leap-plugins-0.1.11pre.js"></script>
  <script src="./themes/leap-hand/leap.rigged-hand-0.1.5.min.js"></script>

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
  <label>Rotation: <span id="rotationOutput">-0.061</span>π</label><br>
  <input id="rotationInput" type="range" min="-1" max="1" value="0" step="0.001">
</div>

<div style="float: left;">
  <label>Z Position: <span id="positionOutput">55</span></label><br>
  <input id="positionInput" type="range" min="-1000" max="300" value="0" step="1">
</div>

<div style="float: left;">
  <label>Scale: <span id="scaleOutput">0.55</span></label><br>
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
    scale:.9
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

</script><canvas width="2318" height="1264" style="width: 1159px; height: 632px; position: fixed; top: 0px; left: 0px;"></canvas><div id="connect-leap" style="width: 100%; position: absolute; top: 0px; left: 0px; padding: 10px; text-align: center; font-size: 18px; opacity: 0.8; display: block; z-index: 10; cursor: pointer;"><img class="playback-move-hand" style="margin: 0px 2px -2px 0px; max-width: 100%;" src="data:image/gif;base64,R0lGODlh9AHtAPf/AJ2dnfT09KWlpZWVlejo6bGxsRwcHHl5eYmJiRMTFOrq66GhoYWFhcHCxObm54GBgdjY2GFhYcXGydPT0+Dg4OPj4319fQsLC0ZGRuTk5GlpaVVVVV5eXpGRkXFxcd7e3kFBQXV1ddbW1lJSUsDAwNvb221tbk1OTgMDAykpKaurqzw9PdTU1FlZWdzc3JiYmMjKzLu7u8jIyJubnPLy9M7Ozr2/wUlJSTo6OsLCwmRkZDY2NtDQ0DIyMr29vaytsKysrC4uLra2ttHS1CUlJtXX2MrKy6+vr5qamiEhIczMzM3O0LS0tMTExMbGxsbIyt7e4I2Njby8vOTl5qioqMPEx+rr7KqqrOjo6np6fNTV17i4uMzNzo+Pj3Z3ePHx8rm7veDh4uzt7r/AwoeHh8PDxM/Q0sTFx42OkMDBw+fo6bW3utjZ2vHy8tHR0sjJyru8v+Xl5d3d3dzc3mNjZJ+fn2pra+np6t/f4OLj5NPT1V9fYFdXV8HCxsDBxbO0tq6vsqqsrk9PT0tLS5KTlGdnZ4uMjn1+gHd4e1JTUzs7PEJCQzMzMysrK+fn6P7+/v////39/fPz8+7u7/z8/Pv7+/f39/b29vr6+vj4+Pn5+e7u7vLy8u/v7+3t7ezs7PDw8Ozs7fHx8fDw8fb29/Ly8/X19fr6+/z8/fn5+tvc3e/v8HNzdKmpqvf3+CMjJPDx8fP09OPk5ZmZmvj5+Xt8flBQUP39/mtsbvLz8+/w8Nra3NXW15+govr7+x8fH7m5uicnJ+fn6ezt7Zqcn7Kzs7S0s1hYWLe4uvz9/aanqq2trf3+/ouLi2NkZqOjo5aXmqKkppeYmmBgYPv7/JSVl4ODg4eHihcXFw8PD/7//9HS0kBAQOHh4mhoa0RERDAwMPf4+AcHB+vr7NfX11xcXKenp5manJqameXm6ExMTcjHyfv8/G9vb/j4+UdHRywsLHN0dj4+PsrLzTQ0NObm6Obn6efm6Ly9v39/fzc4OL2+v+3s7fb39wAAAP///yH/C1hNUCBEYXRhWE1QPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS4zLWMwMTEgNjYuMTQ1NjYxLCAyMDEyLzAyLzA2LTE0OjU2OjI3ICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M2IChNYWNpbnRvc2gpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjQyMUExNDA3QjE2NzExRTNBMUE1RTA3Qjc0NDg0NDMxIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjQyMUExNDA4QjE2NzExRTNBMUE1RTA3Qjc0NDg0NDMxIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6NDIxQTE0MDVCMTY3MTFFM0ExQTVFMDdCNzQ0ODQ0MzEiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6NDIxQTE0MDZCMTY3MTFFM0ExQTVFMDdCNzQ0ODQ0MzEiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4B//79/Pv6+fj39vX08/Lx8O/u7ezr6uno5+bl5OPi4eDf3t3c29rZ2NfW1dTT0tHQz87NzMvKycjHxsXEw8LBwL++vby7urm4t7a1tLOysbCvrq2sq6qpqKempaSjoqGgn56dnJuamZiXlpWUk5KRkI+OjYyLiomIh4aFhIOCgYB/fn18e3p5eHd2dXRzcnFwb25tbGtqaWhnZmVkY2JhYF9eXVxbWllYV1ZVVFNSUVBPTk1MS0pJSEdGRURDQkFAPz49PDs6OTg3NjU0MzIxMC8uLSwrKikoJyYlJCMi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"></div>


</body></html>
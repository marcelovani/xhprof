<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Cardboard Example</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <style>
      body {
        margin: 0px;
        overflow: hidden;
      }
      #example {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
      }
    </style>
  </head>
  <body>
    <div id="example"></div>

  <script src="./themes/cardboard/js/third-party/threejs/three.js"></script>
  <script src="./themes/cardboard/js/third-party/threejs/StereoEffect.js"></script>
  <script src="./themes/cardboard/js/third-party/threejs/DeviceOrientationControls.js"></script>
  <script src="./themes/cardboard/js/third-party/threejs/OrbitControls.js"></script>

  <script>
    var camera, scene, renderer;
    var effect, controls;
    var element, container;

    var clock = new THREE.Clock();

    init();
    animate();

    function init() {
      renderer = new THREE.WebGLRenderer();
      element = renderer.domElement;
      container = document.getElementById('example');
      container.appendChild(element);

      effect = new THREE.StereoEffect(renderer);

      scene = new THREE.Scene();

      camera = new THREE.PerspectiveCamera(90, 1, 0.001, 700);
      camera.position.set(0, 10, 300);
      scene.add(camera);

      controls = new THREE.OrbitControls(camera, element);
      controls.rotateUp(Math.PI / 4);
      controls.target.set(
        camera.position.x + 0.1,
        camera.position.y + 2,
        camera.position.z - 3
      );
      //controls.noZoom = true;
      controls.noPan = true;

      function setOrientationControls(e) {
        if (!e.alpha) {
          return;
        }

        controls = new THREE.DeviceOrientationControls(camera, true);
        controls.connect();
        controls.update();

        element.addEventListener('click', fullscreen, false);

        window.removeEventListener('deviceorientation', setOrientationControls, true);
      }
      window.addEventListener('deviceorientation', setOrientationControls, true);


      var light = new THREE.HemisphereLight(0x777777, 0x000000, 0.6);
      scene.add(light);

      var texture = THREE.ImageUtils.loadTexture(
        './themes/cardboard/textures/patterns/checker.png'
      );
      texture.wrapS = THREE.RepeatWrapping;
      texture.wrapT = THREE.RepeatWrapping;
      texture.repeat = new THREE.Vector2(50, 50);
      texture.anisotropy = renderer.getMaxAnisotropy();

      var material = new THREE.MeshPhongMaterial({
        color: 0xffffff,
        specular: 0xffffff,
        shininess: 20,
        shading: THREE.FlatShading,
        map: texture
      });

      var geometry = new THREE.PlaneGeometry(1000, 1000);

      var mesh = new THREE.Mesh(geometry, material);
      mesh.rotation.x = -Math.PI / 2;
      scene.add(mesh);

      var cube = new THREE.BoxGeometry( 50, 50, 50 );
      var material = new THREE.MeshBasicMaterial( { color: 0xff0000, wireframe: true } );
      for( var i = 0; i < 5; i++ ) {
        material.color.setRGB( Math.random() + 0.1, Math.random() + 0.1, Math.random() + 0.1 );

        var mesh = new THREE.Mesh( cube , material );
        mesh.position.x = (Math.random() - .5 ) * 500;
        mesh.position.y = (Math.random() - .5 ) * 500;
        mesh.position.z = (Math.random() - .5 ) * 500;
        scene.add( mesh );
      }

      window.addEventListener('resize', resize, false);
      setTimeout(resize, 1);
    }

    function resize() {
      var width = container.offsetWidth;
      var height = container.offsetHeight;

      camera.aspect = width / height;
      camera.updateProjectionMatrix();

      renderer.setSize(width, height);
      effect.setSize(width, height);
    }

    function update(dt) {
      resize();

      camera.updateProjectionMatrix();

      controls.update(dt);
    }

    function render(dt) {
      effect.render(scene, camera);
    }

    function animate(t) {
      requestAnimationFrame(animate);

      update(clock.getDelta());
      render(clock.getDelta());
    }

    function fullscreen() {
      if (container.requestFullscreen) {
        container.requestFullscreen();
      } else if (container.msRequestFullscreen) {
        container.msRequestFullscreen();
      } else if (container.mozRequestFullScreen) {
        container.mozRequestFullScreen();
      } else if (container.webkitRequestFullscreen) {
        container.webkitRequestFullscreen();
      }
    }
  </script>
  </body>
</html>

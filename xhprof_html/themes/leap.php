
<!-- saved from url=(0056)https://leapmotion.github.io/Leap-Three-Camera-Controls/ -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><style type="text/css">#guidat { position: fixed; top: 0; right: 0; width: auto; z-index: 1001; text-align: right; } .guidat { color: #fff; opacity: 0.97; text-align: left; float: right; margin-right: 20px; margin-bottom: 20px; background-color: #fff; } .guidat, .guidat input { font: 9.5px Lucida Grande, sans-serif; } .guidat-controllers { height: 300px; overflow-y: auto; overflow-x: hidden; background-color: rgba(0, 0, 0, 0.1); } a.guidat-toggle:link, a.guidat-toggle:visited, a.guidat-toggle:active { text-decoration: none; cursor: pointer; color: #fff; background-color: #222; text-align: center; display: block; padding: 5px; } a.guidat-toggle:hover { background-color: #000; } .guidat-controller { padding: 3px; height: 25px; clear: left; border-bottom: 1px solid #222; background-color: #111; } .guidat-controller, .guidat-controller input, .guidat-slider-bg, .guidat-slider-fg { -moz-transition: background-color 0.15s linear; -webkit-transition: background-color 0.15s linear; transition: background-color 0.15s linear; } .guidat-controller.boolean:hover, .guidat-controller.function:hover { background-color: #000; } .guidat-controller input { float: right; outline: none; border: 0; padding: 4px; margin-top: 2px; background-color: #222; } .guidat-controller select { margin-top: 4px; float: right; } .guidat-controller input:hover { background-color: #444; } .guidat-controller input:focus, .guidat-controller.active input { background-color: #555; color: #fff; } .guidat-controller.number { border-left: 5px solid #00aeff; } .guidat-controller.string { border-left: 5px solid #1ed36f; } .guidat-controller.string input { border: 0; color: #1ed36f; margin-right: 2px; width: 148px; } .guidat-controller.boolean { border-left: 5px solid #54396e; } .guidat-controller.function { border-left: 5px solid #e61d5f; } .guidat-controller.number input[type=text] { width: 35px; margin-left: 5px; margin-right: 2px; color: #00aeff; } .guidat .guidat-controller.boolean input { margin-top: 6px; margin-right: 2px; font-size: 20px; } .guidat-controller:last-child { border-bottom: none; -webkit-box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.5); -moz-box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.5); box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.5); } .guidat-propertyname { padding: 5px; padding-top: 7px; cursor: default; display: inline-block; } .guidat-controller .guidat-slider-bg:hover, .guidat-controller.active .guidat-slider-bg { background-color: #444; } .guidat-controller .guidat-slider-bg .guidat-slider-fg:hover, .guidat-controller.active .guidat-slider-bg .guidat-slider-fg { background-color: #52c8ff; } .guidat-slider-bg { background-color: #222; cursor: ew-resize; width: 40%; margin-top: 2px; float: right; height: 21px; } .guidat-slider-fg { cursor: ew-resize; background-color: #00aeff; height: 21px; } </style>
    <style>


      body {
        margin:0px;
        font-family:"GeoSans";
        background:#000;
        font-size:20px;
        overflow:hidden;

      }

       a{
        color:#fff;
        text-decoration:none;
        opacity: .6;
      }

      a:hover{
        text-decoration:underline;
        opacity:1;
      }
      code{

        background-color: rgba(255,255,255,.2);
        display: inline-block;
        padding:0px 20px;
        width:90%;
        overflow:auto;
        font-size:12px;

      }

      code.inline{
        width:auto;
        padding:0px 0px;
        display:inline;
      }


      #header{
        width:100%;
        background:#000;
        padding:10px;
        height:30px;
        border-bottom:1px solid white;
      }

      #header > div, #header > div > a{
        font-size:25px;
        color:#fff;
        display:inline;
        padding:10px;
      }

      #header > #mainTitle{

        width:300px;
        display: inline-block;
        margin:-10px;

      }
      #slideControls{
        position:absolute;
        left: 420px;
        top:0px;
      }

      #resetCamera{
        position:absolute;
        left: 500px;
        top:0px;
      }
      #toggleInfo{
        position:absolute;
        left: 700px;
        top:0px;
      }

      #socialLinks{
        position:absolute;
        right:-5px;
        top:0px;
      }

      #socialLinks > a{
        display:inline-block;
        width: 25px;
        height: 25px;
        margin: 5px;
        margin-top:-7px;
        float:right;

      }

      #header{
        -webkit-touch-callout: none;
-webkit-user-select: none;
-khtml-user-select: none;
-moz-user-select: none;
-ms-user-select: none;
user-select: none;
      }
      #header > div > a{
        background:#222;
        cursor:pointer;
      }

      #header > div > a:hover{
        background:#444;
        text-decoration:none;

      }


      #socialLinks > a > img {

        width:100%;
        height:100%;
        opacity:.7;
        margin:0px;


      }

      #socialLinks > a:hover{
        background:#f00;
      }


      #downloadLinks{

        position:absolute;
        bottom:0px;
        right:0px;
        text-align:right;
        padding: 20px;
        display: none;

      }

      #downloadLinks > h1{

        font-size:20px;
        color:#fff;
        border-bottom:1px solid white;

      }
      #downloadLinks > a{
        opacity:.5;
      }
      #downloadLinks > a:hover{
        opacity:1;
      }


      .inactiveSlide{

        display:none;
      }


      #slides{
        position:absolute;
        top:51px;
        bottom:0px;
        background-color: rgba(0 , 0 , 0 ,.8);
        color:#fff;
        width:50%;
        overflow:auto;

      }
      .activeSlide{

        overflow:auto;
        padding:50px;

      }

      #scene{

        position:absolute;
        top:0px;
        left:0px;
        z-index:-99;
      }

      #GUI{

        position:absolute;
        right:0px;
        width:500px;
        height:500px;
        display:block;
        background:#111;

      }

      .cameraControlGUI{

      }


      ::-webkit-scrollbar {
          width: 12px;
      }

      ::-webkit-scrollbar-track {
          -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
          border-radius: 10px;
      }

      ::-webkit-scrollbar-thumb {
          border-radius: 10px;
          -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5);
      }

      .inactiveGui{
        display:none;
      }

      #guidat{
        top: 51px;
        right: -20px;
      }

    </style>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-87733842-1', 'auto');
  ga('send', 'pageview', 'leap');

</script>

  </head>

  <body>
  <div id="header">
    <div id="mainTitle"> Leap Three Camera Controls </div>
    <div id="slideCounter">0 / 14</div>
    <div id="slideControls">
      <a onclick="previousSlide()">&lt;</a>
      <a onclick="nextSlide()">&gt;</a>
    </div>

    <div id="resetCamera">
      <a onclick="resetCamera()"> RESET CAMERA </a>
    </div>
    <div id="toggleInfo">
      <a onclick="toggleInfo()">  INFO </a>
    </div>
    <div id="socialLinks">
      <a target="_blank" href="http://twitter.com/share?text=Learn%20to%20make%20your%20%23threejs%20projects%20explorable%20with%20@LeapMotion&amp;url=http://leapmotion.github.io/Leap-Three-Camera-Controls/">

        <img src="./themes/leap/twitter.png">
      </a>
      <a target="_blank" href="http://www.facebook.com/sharer.php?u=http://leapmotion.github.io/Leap-Three-Camera-Controls/"><img src="./themes/leap/facebook.png"></a>
      <a target="_blank" href="https://github.com/leapmotion/Leap-Three-Camera-Controls"><img src="./themes/leap/github.png"></a>
      <a target="_blank" href="https://developer.leapmotion.com/"><img src="./themes/leap/leapmotion.png"></a>
    </div>
  </div>

  <div id="downloadLinks">
    <h1> DOWNLOADS </h1>
    <a href="https://leapmotion.github.io/Leap-Three-Camera-Controls/example.zip"> EXAMPLE </a><br>
    <a href="https://leapmotion.github.io/Leap-Three-Camera-Controls/controls.zip"> CONTROLS </a><br>
  </div>
  <div id="slides">

    <div class="slide activeSlide" id="intro">
      <h1> Leap Three Camera Controls </h1>
      <p>
        Have you ever used three.js? Have you ever wanted to see your work in 3D?
        Have you wondered how we got here, what the meaning of life is?
      </p>
      <p>
        If your answer was yes to any of these questions, than you are in the right place!
        Here we are going to learn how to not only see things in 3D, but also make them move
        with just the flick of our wrist, the wave of our hands.
      </p>
      <p>
        If you have never touched three.js before, your best bet is to check out one of the
        tutorials from someone who knows what they are doing ( which is most definitely not me ).
        Below are some good starting resources:
        </p><ul>
          <li><a href="https://github.com/mrdoob/three.js/">Three.js Github Repo</a></li>
          <li><a href="http://aerotwist.com/tutorials/getting-started-with-three-js/">
            AeroTwist Three.js Tutorial</a></li>
        </ul>
      <p></p>
      <p>
        First we are going to quickly look at how to set up a three.js project with the camera controls,
        but if you are comfortable with three.js, feel free to skip to slide 8, where we will begin looking
        at <code class="inline">LeapSpringControls</code>
      </p>
      <p>
        Please keep in mind that these controls are works in progress!
        If you have ideas on how they could be made better, have ideas of your own,
        or just want to tell me about typos / bugs,
        <a target="_blank" href="https://github.com/leapmotion/Leap-Three-Camera-Controls"> Check out the Repo </a>
      or
      <a target="_blank" href="https://twitter.com/cabbibo"> Hit me up on Twitter </a>

              </p>
      <h3><a href="https://leapmotion.github.io/Leap-Three-Camera-Controls/example.zip"> DOWNLOADING THE EXAMPLE SOURCE </a></h3>
    </div>

    <div class="slide inactiveSlide" id="includingScripts">
      <h1> Including Scripts </h1>

      <p> Still with us? Good, than lets dive into some code right away!</p>
      <p>
        The point of these Leap-Three-Camera-Controls is to make it as easy as possible to get
        something you've made to the point where it is using the Leap Motion Controller to make
        more magic. But before magic comes CODE!
      </p>
      <p>
        The first thing we will do is to include all the files we need. For the purpose of this
        walkthrough, we will be defining the paths as we would in our examples, but keep in mind
        you will need to define your own paths:
      </p>
      <pre><code>
&lt;script src = "lib/three.min.js"                   &gt;&lt;/script&gt;
&lt;script src = "lib/leap.js"                        &gt;&lt;/script&gt;
&lt;script src = "controls/LeapTrackballControls.js"  &gt;&lt;/script&gt;
      </code></pre>

      <p>
        Next slide we'll get the camera set up, and initialize our controller!
      </p>

      <h3><a href="https://leapmotion.github.io/Leap-Three-Camera-Controls/example.zip"> DOWNLOAD THE EXAMPLE SOURCE </a></h3>
    </div>

    <div class="slide inactiveSlide" id="globalVariables">
      <h1> Global Variables </h1>

      <p>
        We will split the majority of our program into two parts: initialization, and animation.
        Animation will take care of updating and rendering things, and will be called every frame.
        Initialization will set up everything we need to use in the animation step. However, the
        first thing that we need to do is create things that we will share between the two steps:
        <br><br>
        Global Variables!
      </p>

      <pre><code>
// our leap controller
var controller;

// our leap camera controls
var controls;

// our three.js variables
var scene , camera , renderer;
      </code></pre>

            <h3><a href="https://leapmotion.github.io/Leap-Three-Camera-Controls/example.zip"> DOWNLOAD THE EXAMPLE SOURCE </a></h3>


    </div>

    <div class="slide inactiveSlide" id="initialization">
      <h1> Initialization </h1>

      <p>
        Now that we've got the global variables defined, we can initialize them. We will be going over
        the THREE.JS initialization pretty quickly, so if it doesn't make sense to you, make sure you
        check out <a href="http://aerotwist.com/tutorials/getting-started-with-three-js/">
        Aerotwist's three.js tutorial</a>.
      </p>
      <p>
        All of our code will be inside an <code class="inline">init</code> function. This function can be called
        wherever you want, but for this slide we are more concerned about what's INSIDE the function call
      </p>

      <pre><code>
function init(){

  // Setting up THREE.JS Scene / Camera / Renderer

  var w     = window.innerWidth;
  var h     = window.innerHeight;

  scene     = new THREE.Scene();
  camera    = new THREE.PerspectiveCamera( 50 , w / h , 1 , 1000 );
  renderer  = new THREE.WebGLRenderer();

  renderer.setSize( w , h );
  document.body.appendChild( renderer.domElement );

  // Adding something to the scene just so we can see if it works
  var geometry  = new THREE.IcosahedronGeometry( 5 , 2 );
  var material  = new THREE.MeshNormalMaterial();
  var mesh      = new THREE.Mesh( geometry , material );

  scene.add( mesh );

  // HERE IS WHERE WE WILL INITIALIZE OUR CONTROLS
  // IN THE NEXT SLIDE

}
      </code></pre>

            <h3><a href="https://leapmotion.github.io/Leap-Three-Camera-Controls/example.zip"> DOWNLOAD THE EXAMPLE SOURCE </a></h3>

    </div>

     <div class="slide inactiveSlide" id="leapInitialization">
      <h1> Leap Initialization </h1>

      <p>
        We've got the THREE.JS part of the initialization out of the way, but we've done NOTHING to include
        that sweet nectar of motion control. So lets get to work on including all of things we need to
        create a Leap-Three-Camera-Control!
      </p>
      <p>
        Keep in mind that this is the example for only ONE of the many camera controls. In later slides, we
        will get to see how the different controls work. and play with their parameters, but for
        this first example, we'll keep rolling with the <code class="inline">LeapTrackballControls</code>
      </p>

      <pre><code>
// AFTER OUR THREE.JS INITIALIZATION

// first off, create our leap controller to get data from
controller = new Leap.Controller();

// To get frames rolling!
// Our Camera Controls, should do this on our own,
// but its good practice to say it outright!
controller.connect();

// THIS! is where the magic is.
// We pass in the camera ( what we are acting on )
// and the controller ( which gives us data )
controls = new THREE.LeapTrackballControls( camera , controller );

// Any defining of parameters, you can do here , like so:

controls.rotationSpeed = 20;

// but these parameters can also be updated anywhere else in the program!

// It is important to note that with other controls,
// we may pass through our scene,
// to add markers, placeholders and other visual feedback.
// We will explore this more in later slides

      </code></pre>

            <h3><a href="https://leapmotion.github.io/Leap-Three-Camera-Controls/example.zip"> DOWNLOAD THE EXAMPLE SOURCE </a></h3>

    </div>


    <div class="slide inactiveSlide" id="animation">
      <h1> Animation </h1>
      <p>
        Now that we've got everything initialized, the only thing left to do is get frames rolling!
      </p>

      <pre><code>
function animate(){

  requestAnimationFrame( animate );

  controls.update();
  renderer.render( scene , camera );

}

      </code></pre>
      <p> Easy enough, right? </p>

      <h3><a href="https://leapmotion.github.io/Leap-Three-Camera-Controls/example.zip"> DOWNLOAD THE EXAMPLE SOURCE </a></h3>

    </div>

    <div class="slide inactiveSlide" id="allTogetherNow">
      <h1> All Together Now! </h1>
      <p>
        We done all the coding we need to do, so lets view it all together!
      </p>
      <pre><code>
&lt;html&gt;
  &lt;body&gt;

    &lt;script src = "lib/leap.min.js"                    &gt;&lt;/script&gt;
    &lt;script src = "lib/three.js"                       &gt;&lt;/script&gt;
    &lt;script src = "controls/LeapTrackballControls.js"  &gt;&lt;/script&gt;

    &lt;script&gt;

      var camera , scene, renderer;

      var controller , controls;

      init();
      animate();

      function init(){

        var w     = window.innerWidth;
        var h     = window.innerHeight;

        scene     = new THREE.Scene();
        camera    = new THREE.PerspectiveCamera( 50 , w / h , 1 , 1000 );
        renderer  = new THREE.WebGLRenderer();

        renderer.setSize( w , h );
        document.body.appendChild( renderer.domElement );

        var geometry  = new THREE.IcosahedronGeometry( 5 , 2 );
        var material  = new THREE.MeshNormalMaterial();
        var mesh      = new THREE.Mesh( geometry , material );

        scene.add( mesh );

        controller = new Leap.Controller();
        controller.connect();

        controls = new THREE.LeapTrackballControls( camera , controller );
        controls.rotationSpeed = 20;

      }


      function animate(){

        requestAnimationFrame( animate );

        controls.update();
        renderer.render( scene , camera );

      }

    &lt;/script&gt;
  &lt;/body&gt;
&lt;/html&gt;
      </code></pre>

      <p>
        Hopefully this all makes sense to you. If not, please check out
        <a href="http://aerotwist.com/tutorials/getting-started-with-three-js/"> A TUTORIAL </a>.
        Or reach out to me at icohen@leapmotion.com || @cabbibo
      </p>

      <h3><a href="https://leapmotion.github.io/Leap-Three-Camera-Controls/example.zip"> DOWNLOAD THE EXAMPLE SOURCE </a></h3>


    </div>


    <div class="slide inactiveSlide" id="nowForTheFunStuff">
      <h1> Now For The Fun Stuff </h1>
      <p>
        Congrats! You are now a graphics programmer! And a Leap programmer! And super cool , and popular!
        And you understand how to use the controls, so we can move onto describing each one in detail.
      </p>
      <p>
        Hopefully you don't look like this dude:
        <img src="./themes/leap/coolGif.gif">
        but if you do, don't worry! now we get to actually start using the camera.
      </p>

      <p>
        Whenever you are ready, head to the next slide to learn about <code class="inline">LeapSpringControls</code>
      </p>
    </div>

    <div class="slide inactiveSlide" id="springControls">
      <h1> Spring Controls </h1>
      <p>
        Spring controls attatch a spring from your camera to a target, which it
        is always looking at. When you pinch, it places a new anchor that the
        target will tween to, always giving you a smooth movement, while the spring
        pulls the camera toward the target. To see exactly what this means,
        try adding markers to the anchor , hand , and target as described in
        the below code snippet
      </p>
      <p>Pros:</p>
      <ul>
        <li>Smooth like butter</li>
        <li>Lets you fly to anywhere you want in the scene with relative ease</li>
        <li>Once you let go, slowly brings you to a final resting point</li>
      </ul>
      <p>Cons:</p>
      <ul>
        <li>Moving camera near poles results in some weirdness...</li>
        <li>Uses pinch, which removes the ability to use it for other gestures</li>
        <li>Easy to get lost in space if you have no reference points</li>
      </ul>
      <p>Pairings:</p>
      <ul>
        <li>Space Flying Games </li>
        <li>Plane Flying Games</li>
        <li>A quick addition to visual experiments</li>
      </ul>
      <p>Called using: </p>
      <pre><code>
&lt;!-- Include Script --&gt;
&lt;script src="path/to/controls/LeapEyeLookControls.js"&gt;&lt;/script&gt;

// Inside Init Function
controls = new THREE.LeapSpringControls( camera , controller , scene );

controls.dampening      = .75;
controls.size           = 120;
controls.springConstant =   1;
controls.mass           = 100;
controls.anchorSpeed    =  .1;
controls.staticLength   = 100;

// Adding meshes to the Anchor , Target and Hand
var geo = new THREE.IcosahedronGeometry( 5, 2 );
var mat = new THREE.MeshNormalMaterial();

var targetMesh  = new THREE.Mesh( geo , mat );
var anchorMesh  = new THREE.Mesh( geo , mat );
var handMesh    = new THREE.Mesh( geo , mat );

controls.addTargetMarker( targetMesh );
controls.addAnchorMarker( anchorMesh );
controls.addHandMarker(     handMesh );

// Inside Animate Function
controls.update();
      </code></pre>

      <p>Using the following parameters:</p>
      <ul>
        <li>dampening:      Tells us how quickly movement slows down </li>
        <li>size:           Tells us size of hand movements</li>
        <li>springConstant: Tells us value for Hooke's Law constant 'k' , which defines 'springyness' </li>
        <li>mass:           Tells us mass of camera</li>
        <li>anchorSpeed:    Tells us how fast anchor tweens to target
                            ( .5 and higher gets weird. but it shouldn't,
                              I just forgot how to do physics. Pull request maybe ?!!???!? )</li>
        <li>staticLength:   Tells us how far away from the target that the camera comes to rest</li>
      </ul>

      <h3><a target="_blank" href="./themes/leap/LeapSpringControls.js"> SEE SOURCE </a></h3>


    </div>


    <div class="slide inactiveSlide" id="pointerControls">
      <h1>Pointer Controls</h1>
      <p>
        The pointer controls always has the camera always pointing at a
        'target', when you pinch, you begin moving the camera around the object,
        and when you release, the camera will stop moving.
      </p>
      <p>Pros:</p>
      <ul>
        <li>Always looking at the same place, so its hard to get out of control</li>
        <li>Movements feel smoothish</li>
        <li>Absolute positioning means that when comparing to the leap,
          the position will always make sense</li>
      </ul>
      <p>Cons:</p>
      <ul>
        <li>Moving camera near poles results in some weirdness</li>
        <li>Because there is only a single target, hard to move around scene
          unless the target is dynamically updated</li>
        <li>Uses pinch, which removes the ability to use it for other gestures</li>
      </ul>
      <p>Pairings:</p>
      <ul>
        <li>Pointer controls work well with a single examined object</li>
        <li>3D Modeling camera controls</li>
        <li>A Game with a single scene that we are always looking at</li>
        <li>A quick addition to visual experiments</li>
      </ul>
      <p>Called using: </p>
      <pre><code>
&lt;!-- Include Script --&gt;
&lt;script src="path/to/controls/LeapPointerControls.js"&gt;&lt;/script&gt;

// Inside Init Function
var controls = THREE.LeapPointerControls( camera , controller );

controls.size       = 100;
controls.speed      = .01;
controls.dampening  = .99;
controls.target     = new THREE.Vector3( 0 , 100 , 0 );

// Inside Animate Function
controls.update();
      </code></pre>

      <p>Using the following parameters:</p>
      <ul>

        <li>size:       Tells us how big the motions will be, basically the spherical
                      distance from the target</li>
        <li>
        dampening:  Tells us how fast the camera will slow down once we release
                        it. also how 'smoothed' the movement will be
        </li>
        <li>
        speed:      Tells us how fast the camera will follow our hand movements.
                        This number should be between 0 and 1
        </li>
        <li>
        target:     Tells us where the camera is looking. A THREE.Vector3(),
                        target basically defines the center of the scene
        </li>
      </ul>


      <h3><a target="_blank" href="./themes/leap/LeapPointerControls.js"> SEE SOURCE </a></h3>

    </div>


    <div class="slide inactiveSlide" id="eyeLookControls">
      <h1> Leap Eye Look Controls </h1>

      <p>
        Eye Look Controls are very similar to the Pointer controls.
        Infact when you use your right hand, they are exactly the same.
        The biggest difference is that when you use your left hand, you
        dynamically move the target. This leads to the ability to easily
        move around a scene, but always have a specific point you are focused on.
        Also, all movements are relative, rather than absolute.
      </p>

      <p>Pros:</p>

      <ul>
        <li>Always looking at the same place, so its hard to get out of control </li>
        <li>Movements feel smoothish</li>
        <li>Relative movements allow for the exploration of the entire scene</li>
      </ul>

      <p>Cons:</p>

      <ul>
        <li>Moving camera near poles results in some weirdness...</li>
        <li>Uses pinch, which removes the ability to use it for other gestures</li>
        <li>Relative movement means that you can get very far away from your target, leading to depth being difficult to judge</li>
        <li>Difficult to move through an entire scene quickly</li>
      </ul>

      <p>Pairings:</p>

      <ul>
        <li>Slowly examining a full scene </li>
        <li>3D Modeling camera controls</li>
        <li>A quick addition to visual experiments</li>
      </ul>

      <p>Called using: </p>
      <pre><code>&lt;!-- Include Script --&gt;
&lt;script src="path/to/controls/LeapEyeLookControls.js"&gt;&lt;/script&gt;

// Inside Init Function
var controls = THREE.LeapEyeLookControls( camera , controller , scene );

controls.lookSize       = 10;
controls.lookMass       = 10;
controls.lookSpeed      = 10;
controls.lookDampening  = .9;

controls.eyeSize        = 10;
controls.eyeMass        = 10;
controls.eyeSpeed       = 10;
controls.eyeDampening   = .9;

// If you want to have a marker for your eye
// Which you probably do...

var geo   = new THREE.CubeGeometry( 1 , 1 , 1 );
var mat   = new THREE.MeshNormalMaterial();
var mesh  = new THREE.Mesh( geo , mat );

controls.addLookMarker( mesh );


// Inside Animate Function
controls.update();
      </code></pre>

      <p>Using the following parameters:</p>

      <ul>
        <li>lookSize: Tells us how big the movements will be for the look object by adding bigger or smaller numbers to the force </li>
        <li>lookMass: Tells us more about how the look object will move by giving it different mass. A smaller mass with fling around the field while a larger mass will be slower / harder to move</li>
        <li>lookSpeed: Tells us how much the speed will be multiplied by when we determine the final speed to be added to the position</li>
        <li>lookDampening: Tells us how quickly the look object will slow down</li>
        <li>eyeSize: Tells us how big the movements will be for the eye object by adding bigger or smaller numbers to the force</li>
        <li>eyeMass: Tells us more about how the eye object will move by giving it different mass. A smaller mass with fling around the field while a larger mass will be slower / harder to move</li>
        <li>eyeSpeed: Tells us how much the speed will be multiplied by when we determine the final speed to be added to the position</li>
        <li>eyeDampening: Tells us how quickly the eye object will slow down</li>
      </ul>

      <h3><a target="_blank" href="./themes/leap/LeapEyeLookControls.js"> SEE SOURCE </a></h3>
    </div>

    <div class="slide inactiveSlide" id="twoHandControls">
      <h1> Two Hand Controls </h1>
      <p>Two Hand controls let you translate around a scene by pinching with a single
      hand, and rotate scene when you pinch with two hands</p>
      <p>Pros:</p>
      <ul>
      <li>You feel a bit like Iron Man</li>
      <li>You don't accidentally rotate the scene when you don't want to </li>
      <li>Once you let go, slowly brings you to a final resting point</li>
      </ul>
      <p>Cons:</p>
      <ul>
      <li>Sometimes difficult for tracking to pick up two hands in the field</li>
      <li>Uses pinch, which removes the ability to use it for other gestures</li>
      <li>Using two hands might be more tiring</li>
      </ul>
      <p>Pairings:</p>
      <ul>
      <li>Quickly exploring large swatches of land</li>
      <li>Manipulating large scenes</li>
      <li>A quick addition to visual experiments</li>
      </ul>
      <p>Called using: </p>
      <pre><code>
&lt;!-- Include Script --&gt;
&lt;script src="path/to/controls/LeapEyeLookControls.js"&gt;&lt;/script&gt;

// Inside Init Function
controls = new THREE.LeapTwoHandControls( camera , controller , scene );

controls.translationSpeed   = 20;
controls.translationDecay   = 0.3;
controls.scaleDecay         = 0.5;
controls.rotationSlerp      = 0.8;
controls.rotationSpeed      = 4;
controls.pinchThreshold     = 0.5;
controls.transSmoothing     = 0.5;
controls.rotationSmoothing  = 0.2;

// Inside Animate Function
controls.update();
      </code></pre>

      <h3><a target="_blank" href="./themes/leap/LeapTwoHandControls.js"> SEE SOURCE </a></h3>

      <!--<p>TODO: Description of Parameters</p>-->


    </div>

    <div class="slide inactiveSlide" id="pinchRotateControls">
      <h1> Pinch Rotate Controls </h1>
      <p>Pinch Rotate Controls are nearly Identical to the Trackball controls, ( described next slide ) , except that they use pinch in order to move the camera. As well, they have the ability to zoom in and out, by simply pinching and moving inwards or outwards. In order to define when this happens, it looks at the movement in Z vs the movement in X and Y, and compares the two to see if there is more movement in Z than XY or vice versa</p>
      <p>Pros:</p>
      <ul>
      <li>Supersmooth. </li>
      <li>No Gimbal Lock!</li>
      </ul>
      <p>Cons:</p>
      <ul>
      <li>Only moves around single point</li>
      <li>Controls take some getting used to for some people</li>
      <li>No clear up vector, which leads to possible deorientation</li>
      <li>Uses Pinch :-( </li>
      </ul>
      <p>Pairings:</p>
      <ul>
      <li>3D Modeling camera controls</li>
      <li>A quick addition to visual experiments</li>
      </ul>
      <p>Called using: </p>
      <pre><code>
&lt;!-- Include Script --&gt;
&lt;script src="path/to/controls/LeapPinchRotateControls.js"&gt;&lt;/script&gt;

// Inside Init Function
var controls = THREE.LeapPinchRotateControls( camera , controller );

controls.rotationSpeed            =   10;
controls.rotationLowDampening     =  .98;
controls.rotationHighDampening    =   .7;
controls.zoom                     =   40;
controls.zoomDampening            =   .6;
controls.zoomSpeedRatio           =   10;
controls.zoomCutoff               =   .9;
controls.zoomEnabled              = true;
controls.zoomVsRotate             =    1;
controls.minZoom                  =   20;
controls.maxZoom                  =   80;

// Inside Animate Function
controls.update();
      </code></pre>

      <p>Using the following parameters:</p>
      <ul>
      <li>rotationSpeed:          Tells us the speed of the rotation</li>
      <li>rotationLowDampening:   Tells us how quickly the rotation will slow down when in moving state</li>
      <li>rotationHighDampening:  Tells us how quickly the rotation will slow down when in stopping state</li>
      <li>zoomEnabled:            Tells us if zooming is enabled</li>
      <li>zoom:                   Tells us how close we are to the center</li>
      <li>zoomDampening:          Tells us how quickly the zoom will slow down</li>
      <li>zoomSpeedRatio:         Tells us how quickly the zoom moves compared to palm</li>
      <li>zoomCutoff:             Tells us how forward facing our palm needs to be to zoom</li>
      <li>zoomVsRotate:           Tells us how much more we need to be moving in Z than XY to start zooming, vs rotating</li>
      <li>minZoom:                Tells us the closest we can be</li>
      <li>maxZoom:                Tells us the farthest we can be</li>
      </ul>

            <h3><a target="_blank" href="./themes/leap/LeapPinchRotateControls.js"> SEE SOURCE </a></h3>
    </div>

    <div class="slide inactiveSlide" id="trackballControls">
      <h1>Leap Trackball Controls</h1>

      <p>
        Trackball Controls let you swipe the camera around a target, as if you
        were pushing a giant bowling ball around ( your hand is always behind the ball )
        Also , if you turn your hand straight up, and zoom is enabled, you will
        stop spinning and start zooming, based on moving your hand forward and backwards
      </p>

      <p>Pros:</p>

      <ul>
        <li>Supersmooth. </li>
        <li>No Gimbal Lock!</li>
        <li>No use of Pinch! </li>
      </ul>

      <p>Cons:</p>

      <ul>
        <li>Only moves around single point</li>
        <li>Controls take some getting used to for some people</li>
        <li>No clear up vector, which leads to possible deorientation</li>
      </ul>

      <p>Pairings:</p>

      <ul>
        <li>3D Modeling camera controls</li>
        <li>A quick addition to visual experiments</li>
      </ul>

      <p>Called using: </p>

      <pre><code>
&lt;!-- Include Script --&gt;
&lt;script src="path/to/controls/LeapTrackballControls.js"&gt;&lt;/script&gt;

// Inside Init Function
var controls = THREE.LeapTrackballControls( camera , controller );

controls.rotationSpeed            = 10;
controls.rotationDampening        = .98;
controls.zoom                     = 40;
controls.zoomDampening            = .6;
controls.zoomCutoff               = .9;

controls.minZoom                  = 20;
controls.maxZoom                  = 80;

// Inside Animate Function
controls.update();
      </code></pre>

      <p>Using the following parameters:</p>

      <ul>
        <li>rotationSpeed:      Tells us the speed of the rotation</li>
        <li>rotationDampening:  Tells us how quickly the rotation will slow down</li>
        <li>zoomEnabled:        Tells us if zooming is enabled</li>
        <li>zoom:               Tells us how close we are to the center</li>
        <li>zoomDampening:      Tells us how quickly the zoom will slow down</li>
        <li>zoomCutoff:         Tells us how forward facing our palm needs to be to zoom</li>
        <li>minZoom:            Tells us the closest we can be</li>
        <li>maxZoom:            Tells us the farthest we can be</li>
      </ul>
      <h3><a target="_blank" href="./themes/leap/LeapTrackballControls.js"> SEE SOURCE </a></h3>
    </div>

    <div class="slide inactiveSlide" id="paddleControls">
      <h1> Paddle Controls </h1>
      <p>Paddle Controls Let you 'paddle' Around a scene, the way that you would paddle
      through water. Pretty cool huh?</p>
      <p>Pros:</p>
      <ul>
      <li>Supersmooth. </li>
      <li>Makes you feel a bit like a god</li>
      <li>No Gimbal Lock!</li>
      <li>No Pinch!</li>
      </ul>
      <p>Cons:</p>
      <ul>
      <li>No Rotate...</li>
      <li>Controls take some getting used to for some people</li>
      </ul>
      <p>Pairings:</p>
      <ul>
      <li>Great for moving a scene where you want don't want to rotate</li>
      <li>Great for 'infinite' terrains</li>
      <li>Great to combine with other methods of control!</li>
      </ul>
      <p>Called using: </p>
      <pre><code>
      &lt;!-- Include Script --&gt;
      &lt;script src="path/to/controls/LeapPaddleControls.js"&gt;&lt;/script&gt;

      // Inside Init Function
      var controls = THREE.LeapPaddleControls( camera , controller );

      controls.weakDampening        = .99;
      controls.strongDampening      = .9;
      controls.fingerMatchCutoff    = .5;
      controls.velocityMatchCutoff  =.5;
      controls.fingerMatchPower     = 5;
      controls.velocityMatchPower   = 5;
      controls.movementSpeed        = 1;
      controls.maxSpeed             = 10;

      // Inside Animate Function
      controls.update();
      </code></pre>

      <p>Using the following parameters:</p>
      <ul>
      <li>weakDampening:          Tells us dampening when there is a hand in field</li>
      <li>strongDampening:        Tells us dampening when there is no hand in field</li>
      <li>fingerMatchCutoff:      Tells us the number at which we will stop moving if the finger direction does not match the hand direction</li>
      <li>velocityMatchCutoff:    Tells us the number at which we will stop moving if the finger velocity does not match the hand normal</li>
      <li>fingerMatchPower:       Tells us the amount that the fingerMatch will be raised to to give a higher or lower turn on for movement</li>
      <li>velocityMatchPower:     Tells us the amount that the velocityMatch will be raised to to give a higher or lower turn on for movement</li>
      <li>movementSpeed:          Tells us how fast we are moving, by multiplying the force</li>
      <li>maxSpeed:               Tells us what we will limit the cameras speed to</li>
      </ul>

      <h3><a target="_blank" href="./themes/leap/LeapPaddleControls.js"> SEE SOURCE </a></h3>
    </div>

  </div>



  <!--


      Javascript for controlling this whole thing!


  -->

<!--  <script src="./themes/leap/jquery.min.js"></script>-->
<!--  <script src="./themes/leap/three.min.js"></script>-->
<!--  <script src="./themes/leap/leap.min.js"></script>-->
  <script src="../../node_modules/jquery/dist/jquery.min.js"></script>
  <script src="../../node_modules/three/build/three.min.js"></script>
  <script src="../../node_modules/leapjs/leap-0.6.4.min.js"></script>
  <script src="./themes/leap/dat.gui.min.js"></script>

  <script src="../../node_modules/viz.js/viz.js"></script>
  <script src="../themes/3D/js/utils.js"></script>

  <script src="./themes/leap/LeapSpringControls.js"></script>
  <script src="./themes/leap/LeapPointerControls.js"></script>
  <script src="./themes/leap/LeapEyeLookControls.js"></script>
<!--  <script src="./themes/leap/LeapTwoHandControls.js"></script>-->
  <script src="../../node_modules/leap_three/controls/LeapTwoHandControls.js"></script>
  <script src="./themes/leap/LeapPinchRotateControls.js"></script>
  <script src="./themes/leap/LeapTrackballControls.js"></script>
  <script src="./themes/leap/LeapPaddleControls.js"></script>

  <!--

    ORDER GOES:

      Spring
      Pointer
      EyeLook
      TwoHand
      PinchRotate
      Trackball
      Paddle

  -->


  <script>

    /*

     PRETTY GL BACKGROUND!

    */
    var camera , scene, renderer;

    var controller , controls = [];
    var activeControl = 0;

    var activeSlide = 11; // Jump to slide 11.
    var totalSlides = 14;

    var slides = $('.slide');
    var slideCounter = $('#slideCounter');
    var guidat = $('.guidat');
    //console.log( slides );


    init();
    toggleInfo();
    animate();
    activateSlide( activeSlide );
    resizeLayout();


    function nextSlide(){

      if( activeSlide == totalSlides ) activeSlide = totalSlides-1;

      activeSlide ++;
      //if( activeSlide == totalSlides ) activeSlide = totalSlides;
      activateSlide( activeSlide );

    }

    function previousSlide(){

      activeSlide --;
      if( activeSlide < 0 ) activeSlide = 0;
      activateSlide( activeSlide );

    }

    function activateSlide( slideNum ){

      activeSlide = slideNum;

      activateGUI( slideNum );
      activateControl( slideNum );

      //console.log( slides[ slideNum ] );

      var new1 =  $( slides[slideNum]).find('h1')[0].innerHTML;
      //console.log( new1 );

      //console.log( $("#mainTitle") );
      $("#mainTitle")[0].innerHTML = new1;
      for( var i = 0; i < slides.length; i++ ){

        if( i != slideNum ){
          slides[i].classList.add( 'inactiveSlide' );
          slides[i].classList.remove( 'activeSlide' );
        }else{
          slides[i].classList.add( 'activeSlide' );
          slides[i].classList.remove( 'inactiveSlide' );
        }

      }

      slideCounter[0].innerHTML = slideNum + " / " + totalSlides ;
    }


    function toggleInfo(){

      //console.log('hellos');
      $("#slides").toggle();

    }
    function activateControl( slideNum ){

      var id = slideNum - 8;

      if( controls[id] ){
        activeControl = controls[id];
      }else{
        activeControl = undefined;
      }


      //console.log( activeControl );
      if( activeControl ){
        //console.log( activeControl );
        if( activeControl.gui ){
          //console.log( activeControl );
          activeControl.gui.open();

        }
      }

    }

    function activateGUI( slideNum ){

      var guidat = $('.guidat');
      var id = slideNum - 8;

        for( var i = 0; i < guidat.length; i++ ){

          if( i != id ){
            guidat[i].classList.add( 'inactiveGui' );
            guidat[i].classList.remove( 'activeGui' );
          }else{
            guidat[i].classList.add( 'activeGui' );
            guidat[i].classList.remove( 'inactiveGui' );
          }
        }


    }

    function resizeLayout(){

      /*var slides = $('#slides');
      var header = $('#header');

      var wWidth = window.innerWidth;
      var wHeight = window.innerHeight;

      //console.log( slides.height() );
      //console.log( header.height() );

      var newSlideHeight = wHeight - header.height();
      slides.height( newSlideHeight );*/

    }

    function UC( change ,  value ){

      //console.log( this );
      //console.log( 'HELLO' );
      //console.log( change );

         //console.log( controls );
            //console.log( activeControl );
            //console.log( change.propertyName );
            //console.log( activeControl[change.propertyName] );
            activeControl[change.propertyName] = value;
            //console.log( change );
            //console.log( value );


    }

      function init(){

        var w     = window.innerWidth;
        var h     = window.innerHeight;

        scene     = new THREE.Scene();
        camera    = new THREE.PerspectiveCamera( 50 , w / h , 1 , 10000 );
        renderer  = new THREE.WebGLRenderer();

        renderer.setSize( w , h );
        renderer.domElement.id = "scene";
        document.body.appendChild( renderer.domElement );

        var cube = new THREE.BoxGeometry( 50, 50, 50 );
        var material = new THREE.MeshBasicMaterial( { color: 0xff0000, wireframe: false } );

//        skybox = new THREE.Mesh( cube , material );
//        skybox.scale.multiplyScalar( 100 );
//        skybox.position = camera.position;
        //scene.add( skybox );

        ////////////////////////////////////////////////////////////////////////
        // DotGraph include                                                   //
        ////////////////////////////////////////////////////////////////////////
        <?php
          print getDotGraph($script);
        ?>
        dotToScene(dotGraph, scene, []);


        camera.position.z = 200;
        controller = new Leap.Controller();
        controller.connect();


        /*

           Spring Controls

        */
        var control = new THREE.LeapSpringControls( camera , controller , scene );

        control.gui = new DAT.GUI();

        control.gui.domElement.class = 'cameraControlGUI';
        control.gui.domElement.id    = 'springControls';

        control.gui.close();
        control.params = {
          dampening      : .75,
          size           : 120,
          springConstant :   1,
          mass           : 100,
          anchorSpeed    :  .1,
          staticLength   : 100,
        }

        for( propt in control.params ){
          control[propt] = control.params[propt]
        }

        var geo   = new THREE.IcosahedronGeometry( 5 , 2 );
        var mat   = new THREE.MeshNormalMaterial();
        var mesh  = new THREE.Mesh( geo , mat );

        control.addHandMarker( mesh.clone() );
        control.addTargetMarker( mesh.clone() );
        control.addAnchorMarker( mesh.clone() );


        control.gui.add( control.params , 'dampening' , .5 , .999     ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'size' , 0 , 5000           ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'springConstant' , 0 , 100  ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'mass' , .0001 , 100        ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'anchorSpeed' , .0 , .6     ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'staticLength' , 0 , 5000   ).onChange(function(v){UC( this,v )});

        controls.push( control );

        /*

           Pointer Controls

        */
        var control = new THREE.LeapPointerControls( camera , controller );

        control.gui = new DAT.GUI();

        control.gui.domElement.class = 'cameraControlGUI';
        control.gui.domElement.id    = 'pointerControls';
        control.gui.close();


        controls.size       = 100;
        control.params = {
          size        : 120,
          dampening   :   .99,
          speed       : .01
        }

        for( propt in control.params ){
          control[propt] = control.params[propt]
        }

        control.gui.add( control.params , 'dampening' , .5 , .999     ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'size' , 0 , 5000           ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'speed' , 0 , 10            ).onChange(function(v){UC( this,v )});

        controls.push( control );



        /*

           Eye Look Controls

        */

        var control = new THREE.LeapEyeLookControls( camera , controller , scene );

        control.gui = new DAT.GUI();

        control.gui.domElement.class = 'cameraControlGUI';
        control.gui.domElement.id    = 'eyeLookControls';
        control.gui.close();


        control.params = {
          lookSize       : 10,
          lookMass       : 10,
          lookSpeed      : 10,
          lookDampening  : .9,

          eyeSize        : 10,
          eyeMass        : 10,
          eyeSpeed       : 10,
          eyeDampening   : .9
        }

        for( propt in control.params ){
          control[propt] = control.params[propt]
        }

        // If you want to have a marker for your eye
        // Which you probably do...

        var geo   = new THREE.IcosahedronGeometry( 5 , 2 );
        var mat   = new THREE.MeshNormalMaterial();
        var mesh  = new THREE.Mesh( geo , mat );

        control.addLookMarker( mesh );

        control.gui.add( control.params , 'lookSize'       , 0  , 1000  ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'lookMass'       , 0  , 100   ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'lookSpeed'      , 0  , 1000  ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'lookDampening'  , .5 , .999  ).onChange(function(v){UC( this,v )});

        control.gui.add( control.params , 'eyeSize'        , 0  , 1000  ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'eyeMass'        , 0  , 100   ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'eyeSpeed'       , 0  , 1000  ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'eyeDampening'   , .5 , .999  ).onChange(function(v){UC( this,v )});

        controls.push( control );




        /*

           Two Hand Controls

        */

        var control = new THREE.LeapTwoHandControls( camera , controller );

        control.gui = new DAT.GUI();

        control.gui.domElement.class = 'cameraControlGUI';
        control.gui.domElement.id    = 'twoHandControls';
        control.gui.close();

        control.params = {
          translationSpeed   : 10,
          translationDecay   : 0.3,
          scaleDecay         : 0.5,
          rotationSlerp      : 0.8,
          rotationSpeed      : 4,
          pinchThreshold     : 0.5,
          transSmoothing     : 0.5,
          rotationSmoothing  : 0.2

        }

        for( propt in control.params ){
          control[propt] = control.params[propt]
        }

        control.gui.add( control.params , 'translationSpeed'    , 0  , 1000  ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'translationDecay'    , 0  , 100   ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'scaleDecay'          , 0  , 1000  ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'rotationSlerp'       , .5 , .999  ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'rotationSpeed'       , 0  , 1000  ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'pinchThreshold'      , 0  , 100   ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'transSmoothing'      , 0  , 1000  ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'rotationSmoothing'   , .5 , .999  ).onChange(function(v){UC( this,v )});


        controls.push( control );
        //console.log(controls);

        /*

           Pinch Rotate Controls

        */

        var control = new THREE.LeapPinchRotateControls( camera , controller );

        control.gui = new DAT.GUI();

        control.gui.domElement.class = 'cameraControlGUI';
        control.gui.domElement.id    = 'pinchRotateControls';
        control.gui.close();


        control.params = {
          rotationSpeed            :   10,
          rotationLowDampening     :  .98,
          rotationHighDampening    :   .7,
          zoom                     :   40,
          zoomDampening            :   .6,
          zoomSpeedRatio           :   10,
          zoomVsRotate             :    1,
          zoomCutoff               :   .9,
          zoomEnabled              : true,
          minZoom                  :   20,
          maxZoom                  :   80,
        }

        for( propt in control.params ){
          control[propt] = control.params[propt]
        }

        control.gui.add( control.params , 'rotationSpeed' , 0 , 100             ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'rotationLowDampening' , .7 , .99999  ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'rotationHighDampening' , .7 , .99999 ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'zoom' , 10 , 10000                   ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'zoomDampening' , .5 , .99999         ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'zoomSpeedRatio' , 0 , 100            ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'zoomVsRotate' , 0 , 10               ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'zoomEnabled'                         ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'minZoom' , 10 , 10000                ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'maxZoom' , 10 , 10000                ).onChange(function(v){UC( this,v )});

        controls.push( control );






        /*

          Trackball Controls

        */
        var control = new THREE.LeapTrackballControls( camera , controller );

        control.gui = new DAT.GUI();

        control.gui.domElement.class = 'cameraControlGUI';
        control.gui.domElement.id    = 'trackballControls';
        control.gui.close();



        control.params = {
          rotationSpeed            :   10,
          rotationLowDampening     :  .98,
          rotationHighDampening    :   .7,
          zoom                     :   40,
          zoomDampening            :   .6,
          zoomSpeedRatio           :   10,
          zoomCutoff               :   .9,
          zoomEnabled              : true,
          minZoom                  :   20,
          maxZoom                  :   80,
        }

        for( propt in control.params ){
          control[propt] = control.params[propt]
        }

        control.gui.add( control.params , 'rotationSpeed' , 0 , 100             ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'rotationLowDampening' , .7 , .99999  ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'rotationHighDampening' , .7 , .99999 ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'zoom' , 10 , 10000                   ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'zoomDampening' , .5 , .99999         ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'zoomSpeedRatio' , 0 , 100            ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'zoomEnabled'                         ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'minZoom' , 10 , 10000                ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'maxZoom' , 10 , 10000                ).onChange(function(v){UC( this,v )});

        controls.push( control );


         /*

          Paddle Controls

        */
        var control = new THREE.LeapPaddleControls( camera , controller );

        control.gui = new DAT.GUI();

        control.gui.domElement.class = 'cameraControlGUI';
        control.gui.domElement.id    = 'paddleControls';
        control.gui.close();


        control.params = {
          weakDampening        : .99,
          strongDampening      :  .9,
          fingerMatchCutoff    :  .5,
          velocityMatchCutoff  :  .5,
          fingerMatchPower     :   5,
          velocityMatchPower   :   5,
          movementSpeed        :   1,
          maxSpeed             :  10,
        }


        for( propt in control.params ){
          control[propt] = control.params[propt]
        }

        control.gui.add( control.params , 'weakDampening'         , .5  , .999  ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'strongDampening'       , .5  , .999  ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'fingerMatchCutoff'     , 0   , 1     ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'velocityMatchCutoff'   , 0   , 1     ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'fingerMatchPower'      , 0   , 10    ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'velocityMatchPower'    , 0   , 10    ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'movementSpeed'         , 0   , 100   ).onChange(function(v){UC( this,v )});
        control.gui.add( control.params , 'maxSpeed'              , 0   , 1000  ).onChange(function(v){UC( this,v )});

        controls.push( control );




        window.addEventListener( 'resize', onResize , false );


      }


      function onResize() {
        windowHalfX = window.innerWidth / 2;
        windowHalfY = window.innerHeight / 2;
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
      }

      function animate(){

        if( activeControl ){
          activeControl.update();
        }
        //controls.update();

        //skybox.position = camera.position;
        renderer.render( scene , camera );
        requestAnimationFrame( animate );

      }



  </script><canvas width="1196" height="726" id="scene" style="width: 1196px; height: 726px;"></canvas><div id="guidat"><div class="guidat inactiveGui" id="springControls" style="width: 280px;"><div class="guidat-controllers" style="height: 0px; overflow-y: hidden;"><div class="guidat-controller number"><span class="guidat-propertyname">dampening</span><input id="dampening" type="text" value="0.75"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 50.1002%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">size</span><input id="size" type="text" value="120"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 2.4%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">springConstant</span><input id="springConstant" type="text" value="1"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 1%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">mass</span><input id="mass" type="text" value="100"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 100%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">anchorSpeed</span><input id="anchorSpeed" type="text" value="0.1"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 16.6667%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">staticLength</span><input id="staticLength" type="text" value="100"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 2%;"></div></div></div></div><a class="guidat-toggle" href="https://leapmotion.github.io/Leap-Three-Camera-Controls/#">Open Controls</a></div><div class="guidat inactiveGui" id="pointerControls" style="width: 280px;"><div class="guidat-controllers" style="height: 0px; overflow-y: hidden;"><div class="guidat-controller number"><span class="guidat-propertyname">dampening</span><input id="dampening" type="text" value="0.99"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 98.1964%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">size</span><input id="size" type="text" value="120"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 2.4%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">speed</span><input id="speed" type="text" value="0.01"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 0.1%;"></div></div></div></div><a class="guidat-toggle" href="https://leapmotion.github.io/Leap-Three-Camera-Controls/#">Open Controls</a></div><div class="guidat inactiveGui" id="eyeLookControls" style="width: 280px;"><div class="guidat-controllers" style="height: 0px; overflow-y: hidden;"><div class="guidat-controller number"><span class="guidat-propertyname">lookSize</span><input id="lookSize" type="text" value="10"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 1%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">lookMass</span><input id="lookMass" type="text" value="10"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 10%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">lookSpeed</span><input id="lookSpeed" type="text" value="10"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 1%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">lookDampening</span><input id="lookDampening" type="text" value="0.9"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 80.1603%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">eyeSize</span><input id="eyeSize" type="text" value="10"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 1%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">eyeMass</span><input id="eyeMass" type="text" value="10"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 10%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">eyeSpeed</span><input id="eyeSpeed" type="text" value="10"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 1%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">eyeDampening</span><input id="eyeDampening" type="text" value="0.9"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 80.1603%;"></div></div></div></div><a class="guidat-toggle" href="https://leapmotion.github.io/Leap-Three-Camera-Controls/#">Open Controls</a></div><div class="guidat inactiveGui" id="twoHandControls" style="width: 280px;"><div class="guidat-controllers" style="height: 0px; overflow-y: hidden;"><div class="guidat-controller number"><span class="guidat-propertyname">translationSpeed</span><input id="translationSpeed" type="text" value="20"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 2%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">translationDecay</span><input id="translationDecay" type="text" value="0.3"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 0.3%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">scaleDecay</span><input id="scaleDecay" type="text" value="0.5"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 0.05%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">rotationSlerp</span><input id="rotationSlerp" type="text" value="0.8"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 60.1202%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">rotationSpeed</span><input id="rotationSpeed" type="text" value="4"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 0.4%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">pinchThreshold</span><input id="pinchThreshold" type="text" value="0.5"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 0.5%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">transSmoothing</span><input id="transSmoothing" type="text" value="0.5"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 0.05%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">rotationSmoothing</span><input id="rotationSmoothing" type="text" value="0.2"><div class="guidat-slider-bg"><div class="guidat-slider-fg"></div></div></div></div><a class="guidat-toggle" href="https://leapmotion.github.io/Leap-Three-Camera-Controls/#">Open Controls</a></div><div class="guidat inactiveGui" id="pinchRotateControls" style="width: 280px;"><div class="guidat-controllers" style="height: 0px; overflow-y: hidden;"><div class="guidat-controller number"><span class="guidat-propertyname">rotationSpeed</span><input id="rotationSpeed" type="text" value="10"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 10%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">rotationLowDampening</span><input id="rotationLowDampening" type="text" value="0.98"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 93.3364%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">rotationHighDampening</span><input id="rotationHighDampening" type="text" value="0.7"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 0%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">zoom</span><input id="zoom" type="text" value="40"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 0.3003%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">zoomDampening</span><input id="zoomDampening" type="text" value="0.6"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 20.0004%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">zoomSpeedRatio</span><input id="zoomSpeedRatio" type="text" value="10"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 10%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">zoomVsRotate</span><input id="zoomVsRotate" type="text" value="1"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 10%;"></div></div></div><div class="guidat-controller boolean" style="cursor: pointer;"><span class="guidat-propertyname" style="cursor: pointer;">zoomEnabled</span><input type="checkbox"></div><div class="guidat-controller number"><span class="guidat-propertyname">minZoom</span><input id="minZoom" type="text" value="20"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 0.1001%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">maxZoom</span><input id="maxZoom" type="text" value="80"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 0.700701%;"></div></div></div></div><a class="guidat-toggle" href="https://leapmotion.github.io/Leap-Three-Camera-Controls/#">Open Controls</a></div><div class="guidat inactiveGui" id="trackballControls" style="width: 280px;"><div class="guidat-controllers" style="height: 0px; overflow-y: hidden;"><div class="guidat-controller number"><span class="guidat-propertyname">rotationSpeed</span><input id="rotationSpeed" type="text" value="10"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 10%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">rotationLowDampening</span><input id="rotationLowDampening" type="text" value="0.98"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 93.3364%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">rotationHighDampening</span><input id="rotationHighDampening" type="text" value="0.7"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 0%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">zoom</span><input id="zoom" type="text" value="40"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 0.3003%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">zoomDampening</span><input id="zoomDampening" type="text" value="0.6"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 20.0004%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">zoomSpeedRatio</span><input id="zoomSpeedRatio" type="text" value="10"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 10%;"></div></div></div><div class="guidat-controller boolean" style="cursor: pointer;"><span class="guidat-propertyname" style="cursor: pointer;">zoomEnabled</span><input type="checkbox"></div><div class="guidat-controller number"><span class="guidat-propertyname">minZoom</span><input id="minZoom" type="text" value="20"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 0.1001%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">maxZoom</span><input id="maxZoom" type="text" value="80"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 0.700701%;"></div></div></div></div><a class="guidat-toggle" href="https://leapmotion.github.io/Leap-Three-Camera-Controls/#">Open Controls</a></div><div class="guidat inactiveGui" id="paddleControls" style="width: 280px;"><div class="guidat-controllers" style="height: 0px; overflow-y: hidden;"><div class="guidat-controller number"><span class="guidat-propertyname">weakDampening</span><input id="weakDampening" type="text" value="0.99"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 98.1964%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">strongDampening</span><input id="strongDampening" type="text" value="0.9"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 80.1603%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">fingerMatchCutoff</span><input id="fingerMatchCutoff" type="text" value="0.5"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 50%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">velocityMatchCutoff</span><input id="velocityMatchCutoff" type="text" value="0.5"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 50%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">fingerMatchPower</span><input id="fingerMatchPower" type="text" value="5"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 50%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">velocityMatchPower</span><input id="velocityMatchPower" type="text" value="5"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 50%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">movementSpeed</span><input id="movementSpeed" type="text" value="1"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 1%;"></div></div></div><div class="guidat-controller number"><span class="guidat-propertyname">maxSpeed</span><input id="maxSpeed" type="text" value="10"><div class="guidat-slider-bg"><div class="guidat-slider-fg" style="width: 1%;"></div></div></div></div><a class="guidat-toggle" href="https://leapmotion.github.io/Leap-Three-Camera-Controls/#">Open Controls</a></div></div>


</body></html>

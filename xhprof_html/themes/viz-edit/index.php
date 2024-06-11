<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Xhprof <?php echo $run; ?></title>
  <link rel="stylesheet" media="all" href="/themes/viz-edit/main.css">
<!--    <link rel="stylesheet" media="all" href="/themes/viz.js/main.css">-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/viz.js/2.1.2/viz.js" crossorigin="anonymous"
          referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/lz-string/1.5.0/lz-string.min.js" crossorigin="anonymous"
          referrerpolicy="no-referrer"></script>
  <script src="//cdn.jsdelivr.net/npm/svg-pan-zoom@3.5.0/dist/svg-pan-zoom.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.34.2/ace.min.js"></script>
</head>
<body>
<pre id="editor"></pre>

<?php include('graph_filter_options.php'); ?>

<div id="output">
  <div id="error"></div>
</div>

<div id="status"></div>

</body>
<script src="/themes/viz-edit/js/main.js"></script>
</html>

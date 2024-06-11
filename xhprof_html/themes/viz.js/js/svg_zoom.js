function attach_svg_zoom() {
  var svg_div = '#output svg';
  $(document).ready(function () {
    var panZoom = window.panZoom = svgPanZoom(svg_div, {
      panEnabled: true,
      controlIconsEnabled: true,
      zoomEnabled: true,
      dblClickZoomEnabled: false,
      mouseWheelZoomEnabled: true,
      preventMouseEventsDefault: true,
      zoomScaleSensitivity: 0.2,
      minZoom: 0.5,
      maxZoom: 10,
      fit: true,
      contain: false,
      center: true,
      refreshRate: 'auto'
    });

    $(window).resize(function () {
      panZoom.resize();
      panZoom.fit();
      panZoom.center();
    })
  });
}


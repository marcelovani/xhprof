define(['d3', 'layouts/abstract', 'js/util'], function(d3, layout, util) {

  'use strict';

  return function(config) {
    if (!config) config = {};

    var _alpha = 0;
    var _delay = 1000;
    var _duration = 1000;
    var _graph = null;
    var _padding = 2;
    var _quadtree = null;
    var _running = true;
    var _shape = 'ellipse';
    var _time = new Date();

    var cd = function() {
      return cd;
    };

    // Collision detection using quadtrees based on
    // http://bl.ocks.org/mbostock/7881887
    var collide = function() {
      if (_shape === 'circle') {
        return function(vertexA) {
          if (_graph.select(vertexA).classed('focus')) {
            return;
          }

          var bboxA = _graph.bbox(vertexA);
          var radiusA = bboxA.r;
          var cx = bboxA.cx;
          var cy = bboxA.cy;
          var ax1 = cx - radiusA;
          var ax2 = cx + radiusA;
          var ay1 = cy - radiusA;
          var ay2 = cy + radiusA;

          _quadtree.visit(function(quad, bx1, by1, bx2, by2) {
            if (quad.point && (quad.point.vertex !== vertexA)) {
              var vertexB = quad.point.vertex;
              var bboxB = quad.point.bbox;

              if (_graph.select(vertexB).classed('focus')) {
                return;
              }

              var delta = new util.Vector(cx - bboxB.cx, cy - bboxB.cy);
              var current = delta.length();
              var required = radiusA + bboxB.r + _padding * 2;

              if (current < required) {
                // normalized offset
                var offset = (current - required) / current * (1 - _alpha);
                vertexA.x -= delta.x *= offset / 2;
                vertexA.y -= delta.y *= offset / 2;
                vertexB.x += delta.x;
                vertexB.y += delta.y;
              }
            }

            return bx1 > ax2 || bx2 < ax1 || by1 > ay2 || by2 < ay1;
          });
        };
      } else if (_shape === 'ellipse') {
        return function(vertexA) {
          if (_graph.select(vertexA).classed('focus')) {
            return;
          }

          var bboxA = _graph.bbox(vertexA);
          var radiusA = bboxA.r;
          var cx = bboxA.cx;
          var cy = bboxA.cy;
          var ax1 = cx - radiusA;
          var ax2 = cx + radiusA;
          var ay1 = cy - radiusA;
          var ay2 = cy + radiusA;

          // ellipse radius vector for shape A
          var vectorA = new util.Vector(bboxA.width / 2, bboxA.height / 2);

          _quadtree.visit(function(quad, bx1, by1, bx2, by2) {
            if (quad.point && (quad.point.vertex !== vertexA)) {
              var vertexB = quad.point.vertex;
              var bboxB = quad.point.bbox;

              if (_graph.select(vertexB).classed('focus')) {
                return;
              }

              // via http://www.gamedev.net/page/resources/_/technical/game-programming/general-collision-detection-for-games-using-ell-r1026

              // ellipse radius vector for shape B
              var vectorB = new util.Vector(bboxB.width / 2, bboxB.height / 2);

              // vector center A to center B
              var deltaVector = new util.Vector(cx - bboxB.cx, cy - bboxB.cy);

              // length of vector from center A to center B
              var currentLength = deltaVector.length();

              // normalized vector from center A to center B
              var normVector = new util.Vector(deltaVector.x / currentLength, deltaVector.y / currentLength);

              // vectors for both shapes taking into account the ellipsoid radius vector
              var radiusVectorA = new util.Vector(normVector.x * vectorA.x, normVector.y * vectorA.y);
              var radiusVectorB = new util.Vector(normVector.x * vectorB.x, normVector.y * vectorB.y);

              // minimum distance length required
              var requiredLength = radiusVectorA.length() + radiusVectorB.length() + _padding * 2;
              if (currentLength < requiredLength) {
                // normalized offset
                var offset = (currentLength - requiredLength) / currentLength * (1 - _alpha);
                vertexA.x -= deltaVector.x *= offset / 2;
                vertexA.y -= deltaVector.y *= offset / 2;
                vertexB.x += deltaVector.x;
                vertexB.y += deltaVector.y;
              }
            }

            return bx1 > ax2 || bx2 < ax1 || by1 > ay2 || by2 < ay1;
          });
        };
      } else if (_shape === 'rect') {
        return function(vertexA) {
          if (_graph.select(vertexA).classed('focus')) {
            return;
          }

          var bboxA = _graph.bbox(vertexA);
          var cx = bboxA.cx;
          var cy = bboxA.cy;
          var ax1 = cx - bboxA.width / 2;
          var ax2 = cx + bboxA.width / 2;
          var ay1 = cy - bboxA.height / 2;
          var ay2 = cy + bboxA.height / 2;

          _quadtree.visit(function(quad, bx1, by1, bx2, by2) {
            if (quad.point && (quad.point.vertex !== vertexA)) {
              var vertexB = quad.point.vertex;
              var bboxB = quad.point.bbox;

              if (_graph.select(vertexB).classed('focus')) {
                return;
              }

              var dxCurrent = Math.abs(bboxB.cx - cx);
              var dyCurrent = Math.abs(bboxB.cy - cy);
              var dxRequired = (bboxA.width + bboxB.width) / 2 + _padding;
              var dyRequired = (bboxA.height + bboxB.height) / 2 + _padding;

              if (dxCurrent < dxRequired && dyCurrent < dyRequired) {
                var inverseAlpha = 1 - _alpha;
                var dx = (dxCurrent - dxRequired) * inverseAlpha / 2;
                var dy = (dyCurrent - dyRequired) * inverseAlpha / 2;

                vertexA.x -= dx;
                vertexB.x += dx;
                vertexA.y -= dy;
                vertexB.y += dy;
              }
            }

            return bx1 > ax2 || bx2 < ax1 || by1 > ay2 || by2 < ay1;
          });
        };
      }
    };

    var debug = function(selection, debugElement) {
      if (!_running) {
        return;
      }

      var className = 'collision-detection-debug';

      selection.each(function() {
        var output = debugElement.select('.' + className);

        if (!output.node()) {
          debugElement.append('g')
            .classed(className, true);
        }

        var vertices = selection.selectAll('.vertex');

        var bboxes = output.selectAll(_shape)
          .data(vertices.data(), function(vertex) {
            return vertex.name;
          });

        bboxes.exit().remove();

        bboxes.enter()
          .append(_shape);

        if (_shape === 'circle') {
          bboxes.attr({
            stroke: '#fcc',
            fill: 'none',
            r: function(d) {
              return (_graph.bbox(d).r + _padding) * (1 - _alpha);
            },

            cx: function(d) {
              return _graph.bbox(d).cx;
            },

            cy: function(d) {
              return _graph.bbox(d).cy;
            }
          });
        } else if (_shape === 'ellipse') {
          bboxes.attr({
            stroke: '#fcc',
            fill: 'none',
            rx: function(d) {
              return (_graph.bbox(d).width / 2 + _padding) * (1 - _alpha);
            },

            ry: function(d) {
              return (_graph.bbox(d).height / 2 + _padding) * (1 - _alpha);
            },

            cx: function(d) {
              return _graph.bbox(d).cx;
            },

            cy: function(d) {
              return _graph.bbox(d).cy;
            }
          });
        } else if (_shape === 'rect') {
          bboxes.attr({
            stroke: '#fcc',
            fill: 'none',
            x: function(d) {
              return d.x;
            },

            y: function(d) {
              return d.y;
            },

            width: function(d) {
              return d.width;
            },

            height: function(d) {
              return d.height;
            }
          });
        }

        // Debugging quadtree
        if (false && _quadtree) {
          var processQuadTree = function(quadtree) {
            var vertices = [];
            quadtree.visit(function(vertex, x1, y1, x2, y2) {
              vertices.push({x: x1, y: y1, width: x2 - x1, height: y2 - y1});
            });

            return vertices;
          };

          var quads = output.selectAll('.quad')
            .data(processQuadTree(_quadtree));

          quads.enter()
            .append('rect')
            .attr({
              class: 'quad',
              fill: 'none',
              stroke: '#369'
            });

          quads.exit().remove();

          quads.attr({
            x: function(d) {
              return d.x;
            },

            y: function(d) {
              return d.y;
            },

            width: function(d) {
              return d.width;
            },

            height: function(d) {
              return d.height;
            }
          });
        }
      });
    };

    cd.start = function() {
      _graph = cd.graph();
      _time = new Date();
      _running = true;
      return cd;
    };

    cd.refresh = function(selection) {
      var now = new Date();
      var collisionThreshold = _time.getTime() + _delay;
      var Δt = now.getTime() - collisionThreshold;

      var step = 1 / _duration;
      _alpha = Math.max(0.0, Δt > 0 ? 1.0 - Δt * step : 1.0);

      selection.each(function() {
        var vertices = d3.select(this).selectAll('.vertex');

        var colliders = vertices.data().map(function(vertex) {
          var bbox = _graph.bbox(vertex);
          return {
            bbox: bbox,
            vertex: vertex,
            x: bbox.cx,
            y: bbox.cy
          };
        });

        _quadtree = d3.geom.quadtree(colliders);

        // _alpha runs from 1.0 to 0.0 (floating point)
        if (_running && _alpha < 1) {
          vertices.each(collide(_alpha));
          if (_graph.debug()) debug(selection, _graph.debug.element());
        }
      });
    };

    cd.stop = function() {
      _running = false;
      return cd;
    };

    cd.toString = function() {
      return 'MU Collision Detection Layout';
    };

    cd.alpha = function(value) {
      if (!arguments.length) return _alpha;
      _alpha = value;
      return cd;
    };

    cd.delay = function(value) {
      if (!arguments.length) return _delay;
      _delay = value;
      return cd;
    };

    cd.duration = function(value) {
      if (!arguments.length) return _duration;
      _duration = value;
      return cd;
    };

    cd.padding = function(value) {
      if (!arguments.length) return _padding;
      _padding = value;
      return cd;
    };

    cd.quadtree = function(value) {
      if (!arguments.length) return _quadtree;
      _quadtree = value;
      return cd;
    };

    cd.running = function() {
      return _running;
    };

    cd.shape = function(value) {
      if (!arguments.length) return _shape;
      _shape = value;
      return cd;
    };

    return layout(cd);
  };

});

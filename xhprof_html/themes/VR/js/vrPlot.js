define( [], function () {
	var vrPlot = function () {
		this.plotObj = function ( dotObjects ) {
			var total = dotObjects.length;
			if ( total > 0 ) {
				var table = [];
				var plot = {};
				var x1 = 0;
				var x2 = 0;
				var y1 = 0;
				var y2 = 0;
				for ( var i = 0; i < total; i++ ) {
					if ( dotObjects[i].shape == 'box' || dotObjects[i].shape == 'octagon' ) {
						// Store the smallest x
						if ( dotObjects[i].position.x < x1 ) {
							x1 = dotObjects[i].position.x;
						}
						// Store the greatest x
						if ( dotObjects[i].position.x > x2 ) {
							x2 = dotObjects[i].position.x;
						}
						// Store the smallest y
						if ( dotObjects[i].position.y < y1 ) {
							y1 = dotObjects[i].position.y;
						}
						// Store the greatest y
						if ( dotObjects[i].position.x > y2 ) {
							y2 = dotObjects[i].position.y;
						}
						label = dotObjects[i].label.split( "\n" );
						position = dotObjects[i].position;

						table[i] = [];
						table[i][0] = label[0].substr( 0, 14 );
						table[i][1] = label.join( '<br/>' );
						var color = '';
						switch ( dotObjects[i].color ) {
							case 'red':
								color = '255,0,0';
								break;
							case 'yellow':
								color = '251,255,32';
								break;
							default:
								color = '0,127,127';
						}
						table[i][2] = color;
						table[i][3] = position.x;
						table[i][4] = position.y;
					}
				}
				plot.table = table;
				plot.x1 = x1;
				plot.x2 = x2;
				plot.y1 = y1;
				plot.y2 = y2;
				return plot;
			}
		}
	};

	return vrPlot;
} );
function vrPannel() {
//@todo move all of these bits into their modules

	$( "#R" ).click(function() {
		alert('Click isn\'t implemented yet. Use the keyboard.');
	});
	$( "#P" ).click(function() {
		alert('Click isn\'t implemented yet. Use the keyboard.');
	});
	$( "#Z" ).click(function() {
		alert('Click isn\'t implemented yet. Use the keyboard.');
	});

	var button = document.getElementById( '3d' );
	button.addEventListener( 'click', function ( event ) {
		if (jQuery('#3d.active').length == 0) {
			changeRenderer('3d');
		}
	}, false );

	var button = document.getElementById( 'vr' );
	button.addEventListener( 'click', function ( event ) {
		if (jQuery('#vr.active').length == 0) {
			changeRenderer('vr');
		}
	}, false );
}

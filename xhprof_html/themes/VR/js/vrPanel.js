function vrPannel() {
//@todo move all of these bits into their modules

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

	var button = document.getElementById( 'accelerometerControls' );
	button.addEventListener( 'click', function ( event ) {
		if (jQuery('#accelerometerControls.active').length == 0) {
			jQuery('#accelerometerControls').addClass('active');
			enabledControls.accelerometerControls = true;
		}
		else {
			jQuery('#accelerometerControls').removeClass('active');
			enabledControls.accelerometerControls = false;
		}
	}, false );

	var button = document.getElementById( 'trackballControls' );
	button.addEventListener( 'click', function ( event ) {
		if (jQuery('#trackballControls.active').length == 0) {
			jQuery('#trackballControls').addClass('active');
			enabledControls.trackballControls = true;
		}
		else {
			jQuery('#trackballControls').removeClass('active');
			enabledControls.trackballControls = false;
		}
	}, false );

	var button = document.getElementById( 'leapControls' );
	button.addEventListener( 'click', function ( event ) {
		if (jQuery('#leapControls.active').length == 0) {
			jQuery('#leapControls').addClass('active');
			enabledControls.leapControls = true;
		}
		else {
			jQuery('#leapControls').removeClass('active');
			enabledControls.leapControls = false;
		}
	}, false );
}

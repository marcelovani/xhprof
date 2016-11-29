function vrPannel() {
	var button = document.getElementById( 'table' );
	button.addEventListener( 'click', function ( event ) {
		changeShape('table', 1000);

	}, false );

	var button = document.getElementById( 'sphere' );
	button.addEventListener( 'click', function ( event ) {
		changeShape('sphere', 1000);

	}, false );

	var button = document.getElementById( 'helix' );
	button.addEventListener( 'click', function ( event ) {
		changeShape('helix', 1000);

	}, false );

	var button = document.getElementById( 'tube' );
	button.addEventListener( 'click', function ( event ) {
		changeShape('tube', 1000);

	}, false );

	var button = document.getElementById( 'grid' );
	button.addEventListener( 'click', function ( event ) {
		changeShape('grid', 1000);

	}, false );

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
			enabledControllers.accelerometerControls = true;
		}
		else {
			jQuery('#accelerometerControls').removeClass('active');
			enabledControllers.accelerometerControls = false;
		}
	}, false );

	var button = document.getElementById( 'trackpadControls' );
	button.addEventListener( 'click', function ( event ) {
		if (jQuery('#trackpadControls.active').length == 0) {
			jQuery('#trackpadControls').addClass('active');
			enabledControllers.trackpadControls = true;
		}
		else {
			jQuery('#trackpadControls').removeClass('active');
			enabledControllers.trackpadControls = false;
		}
	}, false );

	var button = document.getElementById( 'leapControls' );
	button.addEventListener( 'click', function ( event ) {
		if (jQuery('#leapControls.active').length == 0) {
			jQuery('#leapControls').addClass('active');
			enabledControllers.leapControls = true;
		}
		else {
			jQuery('#leapControls').removeClass('active');
			enabledControllers.leapControls = false;
		}
	}, false );
}

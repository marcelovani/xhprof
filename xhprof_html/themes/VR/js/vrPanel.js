function vrPannel() {
	var button = document.getElementById( 'table' );
	button.addEventListener( 'click', function ( event ) {
		jQuery('.group-shape').removeClass('active');
		jQuery('#table').addClass('active');
		transform( targets.table, 2000 );

	}, false );

	var button = document.getElementById( 'sphere' );
	button.addEventListener( 'click', function ( event ) {
		jQuery('.group-shape').removeClass('active');
		jQuery('#sphere').addClass('active');
		transform( targets.sphere, 2000 );

	}, false );

	var button = document.getElementById( 'helix' );
	button.addEventListener( 'click', function ( event ) {
		jQuery('.group-shape').removeClass('active');
		jQuery('#helix').addClass('active');
		transform( targets.helix, 2000 );

	}, false );

	var button = document.getElementById( 'tube' );
	button.addEventListener( 'click', function ( event ) {
		jQuery('.group-shape').removeClass('active');
		jQuery('#tube').addClass('active');
		transform( targets.tube, 2000 );

	}, false );

	var button = document.getElementById( 'grid' );
	button.addEventListener( 'click', function ( event ) {
		jQuery('.group-shape').removeClass('active');
		jQuery('#grid').addClass('active');
		transform( targets.grid, 2000 );

	}, false );

	var button = document.getElementById( '3d' );
	button.addEventListener( 'click', function ( event ) {
		if (jQuery('#3d.active').length == 0) {
			jQuery('.group-renderer').removeClass('active');
			jQuery('#3d').addClass('active');
			setRenderer('3d');
		}
	}, false );

	var button = document.getElementById( 'vr' );
	button.addEventListener( 'click', function ( event ) {
		if (jQuery('#vr.active').length == 0) {
			jQuery('.group-renderer').removeClass('active');
			jQuery('#vr').addClass('active');
			setRenderer('vr');
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

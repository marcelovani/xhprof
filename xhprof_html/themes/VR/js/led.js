define( ['jQuery'], function () {
	var f = function ( _container, _color) {

		var scope = this;
		var container = _container;
		var color = _color;

		this.led = function () {
			jQuery( container ).append(scope.html());
		};

		this.html = function () {
			return '<span class="led ' + color + '"></span>';
		};

		this.on = function () {
			jQuery( container ).find('.led.' + color ).addClass('on');
		};

		this.off = function () {
			jQuery( container ).find('.led.' + color ).removeClass('on');
		};

		this.destroy = function () {
			jQuery( container ).find('.led.' + color ).remove();
		};

		this.led();

	};

	return f;

} );

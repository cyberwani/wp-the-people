( function( window, $, undefined ) {
	var document = window.document;

	function WeThePeopleDashboard() {
		var SELF = this;
		var INITIALIZED = false;
		var ANIMATION_TIME = 500;

		function _start() {
			if( INITIALIZED === true ) {
				return;
			}

			_animateProgressBars();

			INITIALIZED = true;
		}

		function _animateProgressBars() {
			var bars = document.querySelectorAll( '.wrap table.imported-petitions .progress-bar .progress' );
			var currentTime = 0;
			var interval = 100;
			var callback;

			if( bars.length === 0 ) {
				return;
			}

			for( var i = 0, len = bars.length; i < len; i++ ) {
				callback = ( function( i, bar ) {
					return function() {
						var width = bar.getAttribute( 'data-progress' );
						if( width === null ) {
							return;
						}
						width = width + '%';
						$( bar ).animate( { width : width }, ANIMATION_TIME );
					};
				} )( i, bars[ i ] );
				setTimeout( callback, currentTime );
				currentTime += interval;
			}
		}

		return {
			'start' : _start
		};
	}
	window.WeThePeopleDashboard = new WeThePeopleDashboard();

} )( window, jQuery );
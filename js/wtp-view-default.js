( function( window, $, undefined ) {
	var document = window.document;

	function WTPViewDefault() {

		function _start() {
			// defer progress load until last possible second
			$( document ).ready( function() {
				_animateProgressBars( 'section.wtp-petition .petition-progress-bar .progress' );
			} );
		}

		function _animateProgressBars( cssSelector ) {
			var bars = document.querySelectorAll( cssSelector );
			var currentTime = 0;
			var interval = 100;
			var callback;
			var animationTime = 500;

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
						$( bar ).animate( { width : width }, animationTime );
					};
				} )( i, bars[ i ] );
				setTimeout( callback, currentTime );
				currentTime += interval;
			}
		}

		_start();
	}

	window.WTPViewDefault = new WTPViewDefault();

} )( window, jQuery );
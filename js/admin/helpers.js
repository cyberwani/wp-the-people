( function( window, $, undefined ) {

	function WTPHelpers() {

		function _addEvent( event, element, callback ) {
			if( window.addEventListener ) {
				element.addEventListener( event, callback, false );
			}
			else {
				element.attachEvent( 'on' + event, callback );
			}
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

		function _hasClass( element, classToSearchFor ) {
			if( element.nodeType === 1 && (" " + element.className + " ").replace( /[\t\r\n]/g, " " ).indexOf( classToSearchFor ) >= 0 ) {
				return true;
			}

			return false;
		}

		function _killEvent( event ) {
			event.returnValue = false;
			event.cancelBubble = true;
			if( event.stopPropagation ) {
				event.stopPropagation();
			}
			if( event.preventDefault ) {
				event.preventDefault();
			}
		}

		return {
			addEvent : _addEvent,
			animateProgressBars : _animateProgressBars,
			hasClass : _hasClass,
			killEvent : _killEvent
		};
	}

	window.WTPHelpers = new WTPHelpers();

} )( window, jQuery );
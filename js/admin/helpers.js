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

		return {
			addEvent : _addEvent
		};
	}

	window.WTPHelpers = new WTPHelpers();

} )( window, jQuery );
( function( window, $, undefined ) {
	var document = window.document;

	function WTPDashboard() {
		var INITIALIZED = false;

		function _start() {
			if( INITIALIZED === true ) {
				return;
			}

			WTPHelpers.animateProgressBars( '.wrap table.imported-petitions .progress-bar .progress' );

			INITIALIZED = true;
		}

		return {
			'start' : _start
		};
	}
	window.WTPDashboard = new WTPDashboard();

} )( window, jQuery );
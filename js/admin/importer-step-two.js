( function( window, $, undefined ) {
	var document = window.document;

	function WTPImportStepTwo() {
		var INITIALIZED = null;

		function _start() {
			if( INITIALIZED === true ) {
				return;
			}

			WTPHelpers.animateProgressBars( '.wrap .petition-preview .progress-bar .progress' );

			INITIALIZED = true;
		}

		_start();
	}

	window.WTPImportStepTwo = new WTPImportStepTwo();

} )( window, jQuery );
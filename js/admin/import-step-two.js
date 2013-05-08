( function( window, $, undefined ) {
	var document = window.document;

	function WTPImportStepTwo() {
		var INITIALIZED = null;
		var UI = {};

		function _start() {
			if( INITIALIZED === true ) {
				return;
			}

			_cacheUI();
			_bindEvents();

			INITIALIZED = true;
		}

		function _cacheUI() {
			//
		}

		function _bindEvents() {
			//
		}

		_start();
	}

	window.WTPImportStepTwo = new WTPImportStepTwo();

} )( window, jQuery );
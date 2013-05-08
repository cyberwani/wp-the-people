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
			WTPHelpers.animateProgressBars( '.wrap .petition-preview .progress-bar .progress' );

			INITIALIZED = true;
		}

		function _cacheUI() {
			UI.phaseContainer = document.querySelector( '.wrap .phase-container' );
		}

		function _bindEvents() {
			WTPHelpers.addEvent( 'click', UI.phaseContainer, _delegateContainerClick );
		}

		function _createModal() {
			var modal = document.createElement( 'div' );
			modal.className = 'petition-import-modal';

			var html = '<h2>Sit tight...</h2><h3>We\'re downloading data for this petition.</h3><div class="file-copying"></div>';
			modal.innerHTML = html;

			document.body.appendChild( modal );
			UI.$modal = $( modal).animate( { opacity : 1.00 }, 500 );
		}

		function _createOverlay() {
			var overlay = document.createElement( 'div' );
			overlay.className = 'petition-import-overlay';
			document.body.appendChild( overlay );
			UI.$overlay = $( overlay ).animate( { opacity : 0.8 }, 500 );
		}

		function _delegateContainerClick( e ) {
			e = e || window.event;
			var target = e.srcElement || e.target;

			if( WTPHelpers.hasClass( target, 'button-primary' ) === true && WTPHelpers.hasClass( target, 'import-petition' ) === true ) {
				// import the petition
				_beginPetitionImport();
			}
			else if( WTPHelpers.hasClass( target, 'button' ) === true && WTPHelpers.hasClass( target, 'cancel-import' ) === true ) {
				// cancel the import
				window.location.href = WTPHelpers.baseURL;
			}
		}

		function _beginPetitionImport() {
			// create a modal that shows progress of the import
			_createOverlay();
			_createModal();
		}

		_start();
	}

	window.WTPImportStepTwo = new WTPImportStepTwo();

} )( window, jQuery );
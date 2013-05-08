( function( window, $, undefined ) {
	var document = window.document;

	function WTPImportStepOne() {
		var INITIALIZED = null;
		var UI = {};
		var AJAX = null;
		var SEARCH_TEXT = null;

		function _start() {
			if( INITIALIZED === true ) {
				return;
			}

			_cacheUI();
			_bindEvents();

			INITIALIZED = true;
		}

		function _cacheUI() {
			UI.searchInput = document.querySelector( '.wrap .search-input' );
			UI.searchLoader = document.querySelector( '.wrap .search-loader' );
			UI.searchResults = document.querySelector( '.wrap .search-results tbody' );
			UI.searchStatus = document.querySelector( '.wrap .search-status' );
		}

		function _bindEvents() {
			WTPHelpers.addEvent( 'keyup', UI.searchInput, _searchWeThePeopleAPI );
			WTPHelpers.addEvent( 'change', UI.searchStatus, _searchWeThePeopleAPI );
		}

		function _searchWeThePeopleAPI() {
			var value = UI.searchInput.value.replace( /^\s+|\s+$/i, '' );
			SEARCH_TEXT = value;

			if( value === '' ) {
				return;
			}

			if( AJAX !== null ) {
				AJAX.abort();
			}

			AJAX = $.ajax( {
				dataType : 'html',
				type : 'post',
				success : _renderSearchResults,
				url : WTPHelpers.ajaxURL,
				data : {
					action : 'we-the-people-import-search-api',
					text : value,
					_ajax_nonce : WTPHelpers.nonce,
					status : UI.searchStatus.value
				}
			} );

			UI.searchLoader.style.display = 'inline';
		}

		function _renderSearchResults( html ) {
			AJAX = null;
			UI.searchLoader.style.display = 'none';
			if( html.length === 0 ) {
				html = '<tr><td colspan="5"><p>We\'re sorry for the inconvenience, but your search request could not be completed. Please try again.</p></td></tr>';
			}
			UI.searchResults.innerHTML = html;
		}

		_start();
	}

	window.WTPImportStepOne = new WTPImportStepOne();

} )( window, jQuery );
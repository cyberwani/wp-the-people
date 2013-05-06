( function( window, $, undefined ) {
	var document = window.document;

	function WeThePeopleImport() {
		var INITIALIZED = null;
		var UI = {};
		var AJAX = null;
		var AJAX_URL = null;
		var NONCE = null;
		var SEARCH_TEXT = null;

		function _start( ajaxURL, nonce ) {
			if( INITIALIZED === true ) {
				return;
			}

			AJAX_URL = ajaxURL;
			NONCE = nonce;

			_cacheUI();
			_bindEvents();

			INITIALIZED = true;
		}

		function _addEvent( event, element, callback ) {
			if( window.addEventListener ) {
				element.addEventListener( event, callback, false );
			}
			else {
				element.attachEvent( 'on' + event, callback );
			}
		}

		function _cacheUI() {
			UI.searchInput = document.querySelector( '.wrap .search-input' );
			UI.searchLoader = document.querySelector( '.wrap .search-loader' );
			UI.searchResults = document.querySelector( '.wrap .search-results tbody' );
			UI.searchStatus = document.querySelector( '.wrap .search-status' );
		}

		function _bindEvents() {
			_addEvent( 'keyup', UI.searchInput, _searchWeThePeopleAPI );
			_addEvent( 'change', UI.searchStatus, _searchWeThePeopleAPI );
		}

		function _searchWeThePeopleAPI() {
			var value = UI.searchInput.value;
			SEARCH_TEXT = value;

			if( AJAX !== null ) {
				AJAX.abort();
			}

			AJAX = $.ajax( {
				dataType : 'html',
				type : 'post',
				success : _renderSearchResults,
				url : AJAX_URL,
				data : {
					action : 'we-the-people-import-search-api',
					text : value.replace( /^\s+|\s+$/i, '' ),
					_ajax_nonce : NONCE,
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

		return {
			'start' : _start
		};
	}

	window.WeThePeopleImport = new WeThePeopleImport();

} )( window, jQuery );
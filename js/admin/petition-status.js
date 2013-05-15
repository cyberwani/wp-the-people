( function( window, $, undefined ) {

	var document = window.document;

	function PetitionStatus() {

		var SELF = this;

		function _start() {
			_shopWTPMenu();
		}

		function _shopWTPMenu() {
			var menu = document.getElementById( 'toplevel_page_we-the-people' );
			menu.className = 'wp-has-submenu wp-has-current-submenu wp-menu-open menu-top toplevel_page_we-the-people';
			menu.childNodes[ 0 ].className = 'wp-has-submenu wp-has-current-submenu wp-menu-open menu-top toplevel_page_we-the-people';
			var ul = menu.querySelector( 'ul' );
			var li = ul.querySelectorAll( 'li' )[ 1 ];
			li.className = 'class="wp-first-item current';
			var currentA = li.querySelectorAll( 'a' )[ 0 ];
			currentA.className = 'wp-first-item current';
		}

		_start();

	}

	window.PetitionStatus = new PetitionStatus();

} )( window, jQuery );
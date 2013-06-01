( function( window, $, undefined ) {

	function WTPMediaPopup() {
		var default_args = {
			"type": 'default', // default, geographic, timeline
		}

		function _start() {
			$( '.wtp-insert-petition-shortcode' ).on( 'click', __addShortcodeClickHandler );
		}

		function _addShortcodeToPost( petition_id, args ) {

			if ( ! petition_id ) {
				alert( 'Hm, petition ID not found...' );
				return;
			}

			var shortcode_text = '',
				shortcode_attr = '',
				handler = ( window.dialogArguments || opener || parent || top );

			if ( typeof handler.send_to_editor !== 'function' ) {
				console.error( 'Hm, send_to_editor wasn\'t found... Are you in the Media Manager?' );
				return;
			}

			$.extend( args, default_args );

			$.each( args, function( attr_name, attr_value ) {
				shortcode_attr += _.template( ' <%= data.attr_name %>="<%= data.attr_value %>"', { attr_name: attr_name, attr_value: attr_value }, { variable: 'data' } );
			} );

			shortcode_text = _.template( '[petition id="<%= data.petition_id %>" <%= data.shortcode_attr %>]', { petition_id: petition_id, shortcode_attr: shortcode_attr }, { variable: 'data' } );

			handler.send_to_editor( shortcode_text );
		}

		function __addShortcodeClickHandler( e ) {
			e.preventDefault();

			var $this = $( this ),
				petition_id = $this.attr( 'data-petition-id' );

			_addShortcodeToPost( petition_id, {
				//"type": type,
			} );
		}

		return {
			start: _start,
			addShortcode : _addShortcodeToPost
		}
	}

	window.WTPMediaPopup = new WTPMediaPopup();
	$( document ).ready( window.WTPMediaPopup.start );

} )( window, jQuery );
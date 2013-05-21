( function( window, $, undefined ) {
	var document = window.document;

	function WTPViewGeographic() {

		var CLOUDMADE_URL = 'http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/997/256/{z}/{x}/{y}.png';
		var CLOUDMADE_ATTRIBUTION = '';
		var CLOUDMADE = null;

		function _start() {
			var maps = document.querySelectorAll( '.petition-map' );
			if( maps.length === 0 ) {
				return;
			}

			_initializeCloudMade();

			// loop through each map and process it
			var mapContainer;
			for( var i = 0, len = maps.length; i < len; i++ ) {
				mapContainer = maps[ i ];
				_initializeMap( mapContainer );
			}
		}

		function _initializeCloudMade() {
			CLOUDMADE = new L.TileLayer( CLOUDMADE_URL, {
				maxZoom : 17,
				attribution : CLOUDMADE_ATTRIBUTION
			} );
		}

		function _initializeMap( mapContainer ) {
			var geoDataKey = mapContainer.getAttribute( 'data-geo-key' );
			if( geoDataKey === null ) {
				return;
			}

			var records = window[ geoDataKey ];
			var map = L.map( mapContainer, {
				center : new L.LatLng( -37.82, 175.24 ),
				zoom : 13,
				layers : [
					CLOUDMADE
				]
			} );

			var markers = new L.MarkerClusterGroup();
			var record, marker, title;
			for( var i = 0, len = records.length; i < len; i++ ) {
				record = records[ i ];
				title = record.zip + ': ' + record.count + ( ( record.count === 1 ) ? ' signature' : ' signatures' );
				marker = new L.Marker( new L.LatLng( record.lat, record.long ), {
					title : title
				} );
				marker.bindPopup( title );
				markers.addLayer( marker );
			}

			map.addLayer( markers );
			map.fitBounds( markers.getBounds() );
		}

		_start();

	}

	window.WTPViewGeographic = new WTPViewGeographic();

} )( window, jQuery );
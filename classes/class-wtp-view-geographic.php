<?php

class WTP_View_Geographic {

	/**
	 * The singleton instance of this class.
	 *
	 * @var bool|WTP_View_Geographic
	 */
	protected static $_instance = false;

	/**
	 * A blank constructor
	 */
	public function __construct() {}

	/**
	 * Gets the singleton instance of this class and adds any actions/filters if need be
	 *
	 * @return bool|WTP_View_Geographic
	 */
	public static function instance() {
		if( ! self::$_instance ) {
			self::$_instance = new self();
			self::_add_actions();
		}

		return self::$_instance;
	}

	/**
	 * Enqueue all scripts needed by this class/view
	 *
	 * Examples of leaflet use:
	 * view-source:http://leaflet.github.io/Leaflet.markercluster/example/marker-clustering-realworld.388.html
	 * https://github.com/Leaflet/Leaflet.markercluster
	 * view-source:http://maps.mixedbredie.net/leaflet/meals-cluster-label.html
	 * https://github.com/kanarinka/We-The-People-Map
	 */
	public static function enqueue_scripts() {

		// enqueue the stylesheet for this view
		wp_enqueue_style( 'wtp-view-geographic', WTP_Core::$plugins_url . '/css/wtp-view-geographic.css' );

		// enqueue leaflet, the library
		wp_enqueue_script( 'leaflet', 'http://cdn.leafletjs.com/leaflet-0.5/leaflet.js' );
		wp_enqueue_style( 'leaflet', 'http://cdn.leafletjs.com/leaflet-0.5/leaflet.css' );

		// enqueue leaflet markercluster
		wp_enqueue_script( 'leaflet-markercluster', WTP_Core::$plugins_url . '/js/leaflet/leaflet.markercluster.js', array( 'leaflet' ) );
		wp_enqueue_style( 'leaflet-markercluster', WTP_Core::$plugins_url . '/css/leaflet/leaflet.markercluster.css', array( 'leaflet' ) );
		wp_enqueue_style( 'leaflet-markercluster-default', WTP_Core::$plugins_url . '/css/leaflet/leaflet.markercluster.default.css', array( 'leaflet-markercluster' ) );
	}

	/**
	 * Add all of the actions needed for this class to work correctly
	 */
	private static function _add_actions() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
	}

	/**
	 * Handles rendering this view
	 *
	 * @param bool|WP_Post $post
	 * @param array $attributes
	 * @return string
	 */
	public static function render( $post = false, $attributes = array() ) {

		// enqueue the geographic view javascript
		// wp_enqueue_script( 'google-maps-v3', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false', array(), '3' );
		wp_enqueue_script( 'wtp-view-geographic', plugins_url( '/../js/wtp-view-geographic.js', __FILE__ ), array( 'jquery', 'leaflet', 'leaflet-markercluster' ) );

		// get the petition id
		$petition_id = get_post_meta( $post->ID, 'petition_id', true );

		// get the geographic petition data
		$geographic_data = WTP_Intermediary_API::get_petition_geographical_data( $petition_id );

		ob_start();
		?>
		<div data-geo-key="geodata_<?php echo $petition_id; ?>" class="petition-map" style="height: 300px;"></div>
		<script type="text/javascript">var geodata_<?php echo $petition_id; ?> = <?php echo json_encode( $geographic_data ); ?>;</script>
		<?php
		$html = ob_get_clean();
		$html = apply_filters( 'wtp-view-geographic', $html );

		return $html;
	}

}

WTP_View_Geographic::instance();
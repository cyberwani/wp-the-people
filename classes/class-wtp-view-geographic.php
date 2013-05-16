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
		}

		return self::$_instance;
	}

	/**
	 * Enqueues leaflet.js for geographic map functionality
	 */
	public static function enqueue_leaflet() {
		$plugin_url = plugins_url( '/..', __FILE__ );

		// enqueue leaflet, the library
		wp_enqueue_script( 'leaflet', 'http://cdn.leafletjs.com/leaflet-0.4.5/leaflet.js' );
		wp_enqueue_style( 'leaflet', 'http://cdn.leafletjs.com/leaflet-0.4.5/leaflet.css' );

		// enqueue leaflet markercluster
		wp_enqueue_script( 'leaflet-markercluster', $plugin_url . '/js/leaflet/markercluster.js', array( 'leaflet' ) );
		wp_enqueue_style( 'leaflet-markercluster', $plugin_url . '/css/leaflet/leaflet.markercluster.css', array( 'leaflet' ) );
		wp_enqueue_style( 'leaflet-markercluster-default', $plugin_url . '/css/leaflet/leaflet.markercluster.default.css', array( 'leaflet-markercluster' ) );
	}

	/**
	 * Handles rendering this view
	 *
	 * @param bool|WP_Post $post
	 * @param array $attrs
	 */
	public static function render( $post = false, $attrs = array() ) {
		//
	}

}

WTP_View_Geographic::instance();
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
		$plugin_url = plugins_url( '/..', __FILE__ );

		// enqueue the stylesheet for this view
		wp_enqueue_style( 'wtp-view-default', $plugin_url . '/css/wtp-view-default.css' );

		// enqueue leaflet, the library
		wp_enqueue_script( 'leaflet', 'http://cdn.leafletjs.com/leaflet-0.4.5/leaflet.js' );
		wp_enqueue_style( 'leaflet', 'http://cdn.leafletjs.com/leaflet-0.4.5/leaflet.css' );

		// enqueue leaflet markercluster
		wp_enqueue_script( 'leaflet-markercluster', $plugin_url . '/js/leaflet/markercluster.js', array( 'leaflet' ) );
		wp_enqueue_style( 'leaflet-markercluster', $plugin_url . '/css/leaflet/leaflet.markercluster.css', array( 'leaflet' ) );
		wp_enqueue_style( 'leaflet-markercluster-default', $plugin_url . '/css/leaflet/leaflet.markercluster.default.css', array( 'leaflet-markercluster' ) );
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
		return '';
	}

}

WTP_View_Geographic::instance();
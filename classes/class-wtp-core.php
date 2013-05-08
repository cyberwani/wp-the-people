<?php

class WTP_Core {

	/**
	 * The singleton instance of this class.
	 *
	 * @var bool|WTP_Core
	 */
	protected static $_instance = false;

	/**
	 * A blank constructor
	 */
	public function __construct() {}

	/**
	 * Gets the singleton instance of this class and adds any actions/filters if need be
	 *
	 * @return bool|WTP_Core
	 */
	public static function instance() {
		if( ! self::$_instance ) {
			self::$_instance = new self();
			self::_add_actions();
		}

		return self::$_instance;
	}

	/**
	 * Adds any actions necessary for this class to work.
	 */
	private static function _add_actions() {
		add_action( 'admin_menu', array( self::$_instance, 'add_menu_pages' ) );
	}

	/**
	 * Sets up our menu pages
	 */
	public function add_menu_pages() {
		add_menu_page( 'We The People', 'We The People', 'editor', 'we-the-people', array( 'WTP_Dashboard', 'render_dashboard' ), '', 65 );
		add_submenu_page( 'we-the-people', 'Import a Petition', 'Import a Petition', 'editor', 'we-the-people-import', array( 'WTP_Importer', 'render_import_page' ) );
	}

	/**
	 * Sets up the Core for WTP based on the page
	 */
	public static function setup() {
		add_action( 'admin_enqueue_scripts', array( 'WTP_Core', 'enqueue_scripts' ) );
	}

	/**
	 * Selectively enqueues the correct scripts for the correct pages
	 *
	 * @param array $hook
	 */
	public static function enqueue_scripts( $hook = array() ) {
		if( ! is_array( $hook ) )
			$hook = array( $hook );

		$plugin_url = plugins_url( '/..', __FILE__ );

		// enqueue scripts based on page being displayed
		if( in_array( 'toplevel_page_we-the-people', $hook ) ) {
			wp_enqueue_style( 'wtp-general', $plugin_url . '/css/admin/general.css' );
			wp_enqueue_style( 'wtp-dashboard', $plugin_url . '/css/admin/dashboard.css', array( 'wtp-general' ) );
			wp_enqueue_script( 'wtp-helpers', $plugin_url . '/js/admin/helpers.js', array( 'jquery' ) );
			wp_enqueue_script( 'wtp-dashboard', $plugin_url . '/js/admin/dashboard.js', array( 'jquery', 'wtp-helpers' ) );
		}
		else if( in_array( 'we-the-people_page_we-the-people-import', $hook ) ) {
			wp_enqueue_style( 'wtp-general', $plugin_url . '/css/admin/general.css' );
			wp_enqueue_style( 'wtp-import', $plugin_url . '/css/admin/import.css', array( 'wtp-general' ) );
			wp_enqueue_script( 'wtp-helpers', $plugin_url . '/js/admin/helpers.js', array( 'jquery' ) );
		}
	}
}

WTP_Core::instance();
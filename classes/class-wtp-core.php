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
	 * Contains the base plugins URL so we don't hit that function 100 times
	 *
	 * @var string
	 */
	public static $plugins_url = '';

	/**
	 * @var bool Indicates whether or not the Core of this plugin has been previously setup or not
	 */
	public static $_has_been_setup = false;

	/**
	 * Gets the singleton instance of this class and adds any actions/filters if need be
	 *
	 * @return bool|WTP_Core
	 */
	public static function instance() {
		if( ! self::$_instance ) {
			self::$_instance = new self();
			self::_add_actions();
			self::$plugins_url = plugins_url( '/..', __FILE__ );
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
		add_menu_page( 'We The People', 'We The People', 'editor', 'we-the-people', array( 'WTP_Dashboard', 'load' ), plugins_url( 'we-the-people/images/us-flag-menu-icon.png' ), 65 );
		add_submenu_page( 'we-the-people', 'Import a Petition', 'Import a Petition', 'editor', 'we-the-people-import', array( 'WTP_Importer', 'render_import_page' ) );
	}

	/**
	 * Sets up the Core for WTP based on the page
	 */
	public static function setup() {
		if( self::$_has_been_setup )
			return;

		add_action( 'admin_enqueue_scripts', array( 'WTP_Core', 'enqueue_scripts' ) );

		self::$_has_been_setup = true;
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

		// enqueue the hider script
		wp_enqueue_style( 'wtp-menu-hider', $plugin_url . '/css/admin/menu-hider.css' );

		// enqueue scripts based on page being displayed
		if( in_array( 'toplevel_page_we-the-people', $hook ) ) {
			wp_enqueue_style( 'wtp-general', $plugin_url . '/css/admin/general.css' );
			wp_enqueue_style( 'wtp-dashboard', $plugin_url . '/css/admin/dashboard.css', array( 'wtp-general' ) );
			wp_enqueue_style( 'wtp-edit', $plugin_url . '/css/admin/edit.css', array( 'wtp-general' ) );
			wp_enqueue_script( 'wtp-helpers', $plugin_url . '/js/admin/helpers.js', array( 'jquery' ) );
			wp_enqueue_script( 'wtp-dashboard', $plugin_url . '/js/admin/dashboard.js', array( 'jquery', 'wtp-helpers' ) );
		}
		else if( in_array( 'we-the-people_page_we-the-people-import', $hook ) ) {
			wp_enqueue_style( 'wtp-general', $plugin_url . '/css/admin/general.css' );
			wp_enqueue_style( 'wtp-import-step-one', $plugin_url . '/css/admin/importer-step-one.css', array( 'wtp-general' ) );
			wp_enqueue_style( 'wtp-import-step-two', $plugin_url . '/css/admin/importer-step-two.css', array( 'wtp-general' ) );
			wp_enqueue_script( 'wtp-helpers', $plugin_url . '/js/admin/helpers.js', array( 'jquery' ) );
		}
		else if( in_array( 'post.php', $hook ) && WTP_Petitions::get_post_type() === get_post_type() ) {
			wp_enqueue_style( 'wtp-petition-editor', $plugin_url . '/css/admin/edit.css', array( 'wtp-general' ) );
		}
		else if( in_array( 'edit.php', $hook ) && WTP_Petitions::get_post_type() === get_query_var( 'post_type' ) )
			die( '<script type="text/javascript">document.querySelector( "html" ).style.display = "none"; window.location.href = "' . menu_page_url( 'we-the-people' ) . '";</script>' );
	}
}

WTP_Core::instance();
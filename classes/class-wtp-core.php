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
		// register all of our scripts
		self::_register_scripts();

		add_action( 'admin_menu', array( __CLASS__, 'add_menu_pages' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );

		// pulse an action to continue core setup if needed
		do_action( 'wtp-init' );
	}

	/**
	 * Sets up our menu pages
	 */
	public static function add_menu_pages() {
		add_menu_page( 'We The People', 'We The People', 'editor', 'we-the-people', array( 'WTP_Dashboard', 'load' ), plugins_url( 'we-the-people/images/us-flag-menu-icon.png' ), 65 );
		add_submenu_page( 'we-the-people', 'Import a Petition', 'Import a Petition', 'editor', 'we-the-people-import', array( 'WTP_Importer', 'render_import_page' ) );
	}

	/**
	 * Registers all scripts used by this plugin
	 */
	private static function _register_scripts() {
		$plugin_url = plugins_url( '/..', __FILE__ );

		// styles
		wp_register_style( 'wtp-edit', $plugin_url . '/css/admin/edit.css', array( 'wtp-general' ) );
		wp_register_style( 'wtp-general', $plugin_url . '/css/admin/general.css' );
		wp_register_style( 'wtp-dashboard', $plugin_url . '/css/admin/dashboard.css', array( 'wtp-general' ) );
		wp_register_style( 'wtp-menu-hider', $plugin_url . '/css/admin/menu-hider.css' );
		wp_register_style( 'wtp-view-timeline', $plugin_url . '/css/wtp-view-timeline.css' );
		wp_register_style( 'wtp-view-geographic', $plugin_url . '/css/wtp-view-geographic.css' );
		wp_register_style( 'wtp-import-step-one', $plugin_url . '/css/admin/importer-step-one.css', array( 'wtp-general' ) );
		wp_register_style( 'wtp-import-step-two', $plugin_url . '/css/admin/importer-step-two.css', array( 'wtp-general' ) );
		wp_register_style( 'wtp-petition-editor', $plugin_url . '/css/admin/edit.css' );

		// scripts
		wp_register_script( 'wtp-helpers', $plugin_url . '/js/admin/helpers.js', array( 'jquery' ) );
		wp_register_script( 'wtp-dashboard', $plugin_url . '/js/admin/dashboard.js', array( 'jquery', 'wtp-helpers' ) );
	}

	/**
	 * Selectively enqueues the correct scripts for the correct pages
	 *
	 * @param array $hook
	 */
	public static function enqueue_scripts( $hook = array() ) {
		if( ! is_array( $hook ) )
			$hook = array( $hook );

		// enqueue the hider script
		wp_enqueue_style( 'wtp-menu-hider' );

		// enqueue scripts for the dashboard
		if( in_array( 'toplevel_page_we-the-people', $hook ) ) {
			wp_enqueue_style( 'wtp-general' );
			wp_enqueue_style( 'wtp-dashboard' );
			wp_enqueue_style( 'wtp-edit' );
			wp_enqueue_script( 'wtp-helpers' );
			wp_enqueue_script( 'wtp-dashboard' );
		}

		// enqueue styles needed for import screen
		else if( in_array( 'we-the-people_page_we-the-people-import', $hook ) ) {
			wp_enqueue_style( 'wtp-general' );
			wp_enqueue_style( 'wtp-import-step-one' );
			wp_enqueue_style( 'wtp-import-step-two' );
			wp_enqueue_script( 'wtp-helpers' );
		}

		// handle masking the edit post screen as part of WTP
		else if( in_array( 'post.php', $hook ) && WTP_Petitions::get_post_type() === get_post_type() ) {
			wp_enqueue_script( 'wtp-helpers' );
			wp_enqueue_style( 'wtp-petition-editor' );
		}

		// handle redirects if the user ends up going to the edit table screen
		else if( in_array( 'edit.php', $hook ) && WTP_Petitions::get_post_type() === get_query_var( 'post_type' ) )
			die( '<script type="text/javascript">document.querySelector( "html" ).style.display = "none"; window.location.href = "' . menu_page_url( 'we-the-people' ) . '";</script>' );
	}
}

WTP_Core::instance();
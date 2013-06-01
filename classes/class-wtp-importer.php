<?php

// require the child classes
require_once( __DIR__ . '/class-wtp-importer-step-one.php' );
require_once( __DIR__ . '/class-wtp-importer-step-two.php' );
require_once( __DIR__ . '/class-wtp-importer-step-three.php' );

class WTP_Importer {

	/**
	 * The singleton instance of this class.
	 *
	 * @var bool|WTP_Importer
	 */
	protected static $_instance = false;

	/**
	 * A blank constructor
	 */
	public function __construct() {}

	/**
	 * Gets the singleton instance of this class and adds any actions/filters if need be
	 *
	 * @return bool|WTP_Importer
	 */
	public static function instance() {
		if( ! self::$_instance ) {
			self::$_instance = new self();
			self::_add_actions();
		}

		return self::$_instance;
	}

	/**
	 * Add all actions necessary for this class to work
	 */
	private static function _add_actions() {
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
	}

	/**
	 * Enqueue all required scripts for this class to work
	 */
	public static function enqueue_scripts( $hook = array() ) {
		if( ! is_array( $hook ) )
			$hook = array( $hook );

		if( in_array( 'we-the-people_page_we-the-people-import', $hook ) ) {
			wp_enqueue_style( 'wtp-import-step-one', WTP_Core::$plugins_url . '/css/admin/importer-step-one.css', array( 'wtp-general' ) );
			wp_enqueue_style( 'wtp-import-step-two', WTP_Core::$plugins_url . '/css/admin/importer-step-two.css', array( 'wtp-general' ) );
		}
	}

	/**
	 * Renders the import page for petitions
	 */
	public function render_import_page() {
		$step = self::get_current_step();

		switch ( $step ) {
			case 2:
				WTP_Importer_Step_Two::render();
				break;
			case 3:
				WTP_Importer_Step_Three::import( $_GET[ 'petition_id' ] );
				break;
			default:
				WTP_Importer_Step_One::render();
				break;
		}
	}

	private function get_current_step() {
		return isset( $_GET[ 'step' ] ) && absint( $_GET[ 'step' ] );
	}
}

WTP_Importer::instance();
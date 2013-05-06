<?php

class We_The_People {

	/**
	 * The singleton instance of this class.
	 *
	 * @var bool|We_The_People
	 */
	protected static $_instance = false;

	/**
	 * A blank constructor
	 */
	public function __construct() {}

	/**
	 * Gets the singleton instance of this class and adds any actions/filters if need be
	 *
	 * @return bool|We_The_People
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
		add_menu_page( 'We The People', 'We The People', 'editor', 'we-the-people', array( 'We_The_People_Dashboard', 'render_dashboard' ), '', 65 );
		add_submenu_page( 'we-the-people', 'Import a Petition', 'Import a Petition', 'editor', 'we-the-people-import', array( 'We_The_People_Import', 'render_import_page' ) );
	}
}

We_The_People::instance();
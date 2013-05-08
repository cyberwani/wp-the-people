<?php

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
		}

		return self::$_instance;
	}

	/**
	 * Renders the import page for petitions
	 */
	public function render_import_page() {
		if( ! isset( $_GET[ 'step' ] ) )
			WTP_Importer_Step_One::render();
		else if( '2' === $_GET[ 'step' ] )
			WTP_Importer_Step_Two::render();
		else
			WTP_Importer_Step_One::render();
	}
}

WTP_Importer::instance();
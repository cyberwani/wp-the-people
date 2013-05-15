<?php

// include our dependencies
require_once( __DIR__ . '/class-wtp-core.php' );

class WTP_Importer_Step_Three {

	/**
	 * The singleton instance of this class.
	 *
	 * @var bool|WTP_Importer_Step_Three
	 */
	protected static $_instance = false;

	/**
	 * A blank constructor
	 */
	public function __construct() {}

	/**
	 * Gets the singleton instance of this class and adds any actions/filters if need be
	 *
	 * @return bool|WTP_Importer_Step_Three
	 */
	public static function instance() {
		if( ! self::$_instance ) {
			WTP_Core::setup();
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Handles importing a petition by its petition ID
	 *
	 * @param string $petition_id
	 */
	public static function import( $petition_id = '' ) {
		// import the new petition into a post type
		$post_id = WTP_Petitions::import_petition( $petition_id );

		// now prevent the creation of a duplicate by redirecting the user
		wp_die( '<script type="text/javascript">document.getElementById( "wpbody-content" ).style.display = "none"; window.location.href = "' . admin_url( 'post.php?post=' . $post_id . '&action=edit' ) . '";</script>' );
	}

}

WTP_Importer_Step_Three::instance();
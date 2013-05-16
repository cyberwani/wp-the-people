<?php

class WTP_View_Timeline {

	/**
	 * The singleton instance of this class.
	 *
	 * @var bool|WTP_View_Timeline
	 */
	protected static $_instance = false;

	/**
	 * A blank constructor
	 */
	public function __construct() {}

	/**
	 * Gets the singleton instance of this class and adds any actions/filters if need be
	 *
	 * @return bool|WTP_View_Timeline
	 */
	public static function instance() {
		if( ! self::$_instance ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Handles rendering this view
	 *
	 * @param bool $post
	 * @param array $attrs
	 * @return string
	 */
	public static function render( $post = false, $attrs = array() ) {
		return '';
	}

}

WTP_View_Timeline::instance();
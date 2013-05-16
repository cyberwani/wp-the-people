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

}

WTP_View_Geographic::instance();
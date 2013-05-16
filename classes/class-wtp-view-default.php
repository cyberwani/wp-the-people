<?php

class WTP_View_Default {

	/**
	 * The singleton instance of this class.
	 *
	 * @var bool|WTP_View_Default
	 */
	protected static $_instance = false;

	/**
	 * A blank constructor
	 */
	public function __construct() {}

	/**
	 * Gets the singleton instance of this class and adds any actions/filters if need be
	 *
	 * @return bool|WTP_View_Default
	 */
	public static function instance() {
		if( ! self::$_instance ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Handles rendering this view
	 */
	public static function render() {
		//
	}

}

WTP_View_Default::instance();
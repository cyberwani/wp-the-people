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
			self::_add_actions();
		}

		return self::$_instance;
	}

	/**
	 * Adds all of the necessary actions for this class to work
	 */
	private static function _add_actions() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
	}

	/**
	 * Enqueue all scripts needed by this class/view
	 */
	public static function enqueue_scripts() {

		// enqueue the stylesheet for this view
		wp_enqueue_style( 'wtp-view-timeline', WTP_Core::$plugins_url . '/css/wtp-view-timeline.css' );
	}

	/**
	 * Handles rendering this view
	 *
	 * @param bool|WP_Post $post
	 * @param array $attributes
	 * @return string
	 */
	public static function render( $post = false, $attributes = array() ) {
		return '';
	}

}

WTP_View_Timeline::instance();
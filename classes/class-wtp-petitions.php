<?php

class WTP_Petitions {

	/**
	 * The singleton instance of this class.
	 *
	 * @var bool|WTP_Petitions
	 */
	protected static $_instance = false;

	/**
	 * A blank constructor
	 */
	public function __construct() {}

	/**
	 * Gets the singleton instance of this class and adds any actions/filters if need be
	 *
	 * @return bool|WTP_Petitions
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
		add_action( 'init', array( 'WTP_Petitions', 'add_post_type' ) );
	}

	/**
	 * Handles registering a new post type for use within the plugin
	 */
	public static function add_post_type() {
		$labels = array(
			'name' => 'Petitions',
			'singular_name' => 'Petition',
			'add_new' => 'Add New',
			'add_new_item' => 'Add New Petition',
			'edit_item' => 'Edit Petition',
			'new_item' => 'New Petition',
			'all_items' => 'All Petitions',
			'view_item' => 'View Petitions',
			'search_items' => 'Search Petitions',
			'not_found' =>  'No petitions found',
			'not_found_in_trash' => 'No petitions found in Trash',
			'parent_item_colon' => '',
			'menu_name' => 'Petitions',
		);

		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'petition' ),
			'capability_type' => 'post',
			'has_archive' => false,
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
		);

		register_post_type( 'wtp-petitions', $args );
	}
}

WTP_Petitions::instance();
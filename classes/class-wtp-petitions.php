<?php

// include our dependencies
require_once( __DIR__ . '/class-wtp-api.php' );
require_once( __DIR__ . '/class-wtp-intermediary-api.php' );
require_once( __DIR__ . '/class-wtp-core.php' );

// require the views for the shortcodes to work
require_once( __DIR__ . '/class-wtp-view-default.php' );
require_once( __DIR__ . '/class-wtp-view-geographic.php' );
require_once( __DIR__ . '/class-wtp-view-timeline.php' );

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
	 * @var string The post type of this custom post type
	 */
	private static $_post_type = 'wtp-petitions';

	/**
	 * Gets the singleton instance of this class and adds any actions/filters if need be
	 *
	 * @return bool|WTP_Petitions
	 */
	public static function instance() {
		if( ! self::$_instance ) {
			self::$_instance = new self();
			WTP_Core::setup();
			self::_add_actions();
		}

		return self::$_instance;
	}

	/**
	 * Gets the post type for this class
	 *
	 * @return string
	 */
	public static function get_post_type() {
		return self::$_post_type;
	}

	/**
	 * Adds any actions necessary for this class to work.
	 */
	private static function _add_actions() {
		add_action( 'init', array( 'WTP_Petitions', 'add_post_type' ) );
		add_action( 'add_meta_boxes', array( 'WTP_Petitions', 'add_meta_boxes' ) );
		add_action( 'init', array( 'WTP_Petitions', 'register_shortcodes' ) );
	}

	/**
	 * Handles adding all of the meta boxes for this custom post type
	 */
	public static function add_meta_boxes() {
		add_meta_box( 'wtp-petition-status', 'Petition Status', array( 'WTP_Petitions', 'render_petition_status_meta' ), self::$_post_type, 'side', 'default' );
	}

	/**
	 * Handles rendering the petition status for the meta box
	 */
	public static function render_petition_status_meta() {
		$plugin_url = plugins_url( '/..', __FILE__ );

		$id = get_the_ID();
		$status = get_post_meta( $id, 'petition_status', true );
		$signatures_needed = intval( get_post_meta( $id, 'petition_signatures_needed', true ) );
		$signature_count = intval( get_post_meta( $id, 'petition_signature_count', true ) );

		if( $signature_count >= $signatures_needed )
			$progress = 100;
		else if( $signatures_needed === 0 )
			$progress = 100;
		else
			$progress = $signature_count / $signatures_needed;

		?>
		<div class="petition-status">
			<div class="misc-pub-section">
				<span>Status:</span>
				<span><?php echo ucwords( $status ); ?></span>
			</div>
			<div class="misc-pub-section">
				<span class="alignleft progress-text">Progress:</span>
				<div class="progress-bar">
					<div class="progress" data-progress="<?php echo $progress; ?>"></div>
					<div class="text"><?php echo $signature_count; ?> / <?php echo $signatures_needed; ?></div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="misc-pub-section">
				<span>Signatures Needed:</span>
				<span><?php echo $signatures_needed; ?></span>
			</div>
			<div class="misc-pub-section">
				<span>Signature Count:</span>
				<span><?php echo $signature_count; ?></span>
			</div>
		</div>
		<script type="text/javascript" src="<?php echo $plugin_url; ?>/js/admin/petition-status.js"></script>
		<?php
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
			'supports' => array( 'title', 'editor', 'thumbnail' )
		);

		register_post_type( self::$_post_type, $args );
	}

	/**
	 * Creates a new petition post by using the petition ID to fetch all of the associated data. It will create the post
	 * and then store all of the petition's data to post meta for that post. All meta is prefixed with 'petition_' to
	 * avoid any conflicts.
	 *
	 * @param string $id
	 * @return int|WP_Error
	 */
	public static function import_petition( $id = '' ) {
		$data = WTP_Intermediary_API::get_petition_data( $id );

		if( isset( $data[ 'error' ] ) )
			return false;

		// create a new post with the data
		$params = array(
			'post_type' => self::$_post_type,
			'post_content' => $data[ 'body' ],
			'post_title' => $data[ 'title' ],
			'post_status' => 'publish'
		);

		$id = wp_insert_post( $params );

		if( ! $id )
			return false;

		// set all of the post meta's now
		$fields = array(
			'id', 'signature_threshold', 'signature_count', 'signatures_needed', 'url',
			'deadline', 'status', 'created', 'issues', 'response_url', 'response_association_time'
		);

		foreach( $fields as $field )
			update_post_meta( $id, 'petition_' . $field, $data[ $field ] );

		return $id;
	}

	/**
	 * Registers all shortcodes
	 */
	public static function register_shortcodes() {
		add_shortcode( 'petition', array( 'WTP_Petitions' , 'interpret_shortcode' ) );
	}

	/**
	 * Interprets the shortcode and all of the attributes passed to it and takes the appropriate action
	 *
	 * @param array $attributes
	 * @return string
	 */
	public static function interpret_shortcode( $attributes = array() ) {
		// check to see if the ID was specified
		$id = false;
		if( isset( $attributes[ 'id' ] ) )
			$id = intval( $attributes[ 'id' ] );

		if( ! $id )
			return '';

		// check to see if type is available
		$type = 'default';
		$whitelisted_types = array( 'default', 'timeline', 'geographic' );
		if( isset( $attributes[ 'type' ] ) && in_array( $attributes[ 'type' ], $whitelisted_types ) )
			$type = $attributes[ 'type' ];

		// get the post before rendering it
		$post = get_post( $id );
		if( ! $post )
			return '';

		if( 'default' === $type )
			return WTP_View_Default::render( $post, $attributes );
		else if( 'timeline' === $type )
			return WTP_View_Timeline::render( $post, $attributes );
		else if( 'geographic' === $type )
			return WTP_View_Geographic::render( $post, $attributes );
		else
			return '';
	}

}

WTP_Petitions::instance();
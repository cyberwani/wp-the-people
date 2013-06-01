<?php

// include our dependencies
require_once( __DIR__ . '/class-wtp-core.php' );
require_once( __DIR__ . '/class-wtp-petitions.php' );

class WTP_Dashboard {

	/**
	 * The singleton instance of this class.
	 *
	 * @var bool|WTP_Dashboard
	 */
	protected static $_instance = false;

	/**
	 * A blank constructor
	 */
	public function __construct() {}

	/**
	 * Gets the singleton instance of this class and adds any actions/filters if need be
	 *
	 * @return bool|WTP_Dashboard
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
		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
			add_filter( 'media_upload_tabs', array( __CLASS__, 'add_media_upload_tab' ) );
			add_filter( 'media_upload_wp-the-people', array( __CLASS__, 'render_media_upload_tab' ) );
		}
	}

	public function add_media_upload_tab( $tabs ) {
		return array_merge( $tabs, array(
			'wp-the-people' => __( 'Insert Petition', 'wp-the-people' ),
		) );
	}

	public function render_media_upload_tab() {
		// TODO: use backbone to render the view instead of the legacy iframe approach
		return wp_iframe( array( __CLASS__, 'render_media_upload_tab_iframe' ) );
	}

	public function render_media_upload_tab_iframe() {
		self::_render_dashboard();
		/*
		?>
		<div id="wp-the-people-insert-iframe" class="attachments-browser">
			<div id="wp-the-people-insert-toolbar" class="media-toolbar">
				Toolbar
			</div>
			<ul id="wpcom-shortcoder-shortcodes" class="attachments">
				List
			</ul>
			<div class="media-sidebar">Sidebar</div>
		</div>
		<?php
		*/
	}

	/**
	 * Enqueue all required scripts for this class to work
	 */
	public static function enqueue_scripts( $hook = array() ) {
		if( in_array( $hook, array( 'toplevel_page_we-the-people' ) ) || WTP_Core::is_media_upload_page() ) {
			wp_enqueue_style( 'wtp-dashboard', WTP_Core::$plugins_url . '/css/admin/dashboard.css', array( 'wtp-general' ) );
			wp_enqueue_script( 'wtp-dashboard', WTP_Core::$plugins_url . '/js/admin/dashboard.js', array( 'jquery', 'wtp-helpers' ) );
		}

		if ( WTP_Core::is_media_upload_page() ) {
			wp_enqueue_script( 'wtp-media-popup', WTP_Core::$plugins_url . '/js/admin/media-popup.js', array( 'jquery', 'underscore', 'wtp-dashboard' ) );
		}
	}

	/**
	 * Figures out which page to load and then renders it
	 */
	public static function load() {
		// we need to find out what we're loading so do a series of checks
		if( isset( $_GET[ 'remove' ] ) && 'true' === $_GET[ 'remove' ] ) {
			$id = $_GET[ 'id' ];
			$post = get_post( $id );

			if( $post )
				wp_delete_post( $id );

			self::_render_dashboard();
		}
		else
			self::_render_dashboard();
	}

	/**
	 * Renders the dashboard for this plugin
	 */
	private static function _render_dashboard() {
		?>
		<div class="wrap">
			<div class="alignleft us-seal"></div>
			<div class="alignleft logo"></div>
			<div class="clear"></div>
			<p>The right to petition your government is guaranteed by the First Amendment of the United States Constitution. <a href="https://petitions.whitehouse.gov/how-why/introduction">We the People</a> provides a new way to petition the Obama Administration to take action on a range of important issues facing our country. We created <a href="https://petitions.whitehouse.gov/how-why/introduction">We the People</a> because we want to hear from you. If a petition gets enough support, White House staff will review it, ensure it’s sent to the appropriate policy experts, and issue an official response.</p>
			<a href="<?php menu_page_url( 'we-the-people-import' ); ?>" class="button-primary" target="_top">Import A Petition</a>
			<a href="https://petitions.whitehouse.gov/petition/create" class="button" target="_blank">Create a Petition</a>
			<h3>Your Imported Petitions:</h3>
			<table class="wp-list-table widefat fixed imported-petitions">
				<thead>
					<tr>
						<th>Petition</th>
						<th style="width: 100px;">End Date</th>
						<th style="width: 210px;">Progress</th>
						<th style="width: 90px;"></th>
					</tr>
				</thead>
				<tbody>
					<?php self::_render_petitions(); ?>
				</tbody>
				<tfoot>
					<tr>
						<th>Petition</th>
						<th style="width: 100px;">End Date</th>
						<th style="width: 200px;">Progress</th>
						<th style="width: 80px;"></th>
					</tr>
				</tfoot>
			</table>
		</div>
		<script type="text/javascript">WTPDashboard.start();</script>
		<?php
	}

	/**
	 * Handles rendering each petition that was imported into the database
	 */
	private static function _render_petitions() {

		// get all petitions now
		$params = array(
			'post_type' => WTP_Petitions::get_post_type()
		);
		$query = new WP_Query( $params );

		if( ! $query->have_posts() ) {
			?>
			<tr>
				<td colspan="5">No petitions have been imported yet.</td>
			</tr>
			<?php
			return;
		}

		while( $query->have_posts() ) :
			$query->the_post();

			// get the extra information we need
			$id = get_the_ID();
			$signatures_needed = intval( get_post_meta( $id, 'petition_signatures_needed', true ) );
			$signature_count = intval( get_post_meta( $id, 'petition_signature_count', true ) );
			if( $signature_count >= $signatures_needed )
				$progress = 100;
			else if( $signatures_needed === 0 )
				$progress = 100;
			else
				$progress = $signature_count / $signatures_needed;

			?>
			<tr>
				<td><?php the_title(); ?></td>
				<td>May 24, 2013</td>
				<td>
					<div class="alignleft progress-bar">
						<div class="progress" data-progress="<?php echo $progress; ?>"></div>
					</div>
					<div class="alignleft progress-bar-text"><?php echo $signature_count; ?> / <?php echo $signatures_needed; ?></div>
					<div class="clear"></div>
				</td>
				<td style="text-align: right;">
					<?php if ( WTP_Core::is_media_upload_page() ) : ?>
						<a href="javascript:void(0);" class="button wtp-insert-petition-shortcode" data-petition-id="<?php echo esc_attr( $id ); ?>"><?php _e( 'Add to Post', 'wp-the-people' ); ?></a>
					<?php else : ?>
						<a href="<?php echo admin_url( 'post.php?post=' . $id . '&action=edit' ); ?>">Edit</a> | <a href="<?php menu_page_url( 'we-the-people' ); ?>&remove=true&id=<?php echo $id; ?>">Remove</a>
					<?php endif; ?>
				</td>
			</tr>
			<?php

		endwhile;
	}

}

WTP_Dashboard::instance();
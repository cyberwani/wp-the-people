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
		wp_enqueue_style( 'wtp-view-default', WTP_Core::$plugins_url . '/css/wtp-view-default.css' );
	}

	/**
	 * Handles rendering this view
	 *
	 * @param bool|WP_Post $post
	 * @param array $attributes
	 * @return string
	 */
	public static function render( $post = false, $attributes = array() ) {

		// enqueue the javascript we need for this to work
		wp_enqueue_script( 'wtp-view-default', WTP_Core::$plugins_url . '/js/wtp-view-default.js', array( 'jquery' ) );

		$status = get_post_meta( $post->ID, 'petition_status', true );
		$url = get_post_meta( $post->ID, 'petition_url', true );
		$issues = get_post_meta( $post->ID, 'petition_issues', true );
		$created = get_post_meta( $post->ID, 'petition_created', true );
		$created_date = date( 'F j, Y', intval( $created ) );

		$signatures_needed = intval( get_post_meta( $post->ID, 'petition_signatures_needed', true ) );
		$signature_count = intval( get_post_meta( $post->ID, 'petition_signature_count', true ) );

		if( $signatures_needed === 0 )
			$progress = 100;
		else if( $signature_count >= $signatures_needed )
			$progress = 100;
		else
			$progress = $signature_count / $signatures_needed;


		ob_start();
		?>
		<section class="wtp-petition">
			<div class="petition-title"><?php echo $post->post_title; ?></div>
			<div class="petition-created-date">Created on <?php echo $created_date; ?></div>
			<div class="petition-body"><?php echo $post->post_content; ?></div>
			<div class="petition-issues">Issues: <?php echo $issues; ?></div>
			<div class="petition-progress-bar">
				<div class="progress-text"><?php echo $signature_count, ' / ', $signatures_needed; ?></div>
				<div class="progress" data-progress="<?php echo $progress; ?>"></div>
			</div>
			<div class="petition-footer">
				<div class="petition-status alignleft">This petition is currently <?php echo $status; ?>.</div>
				<a class="petition-link alignright" href="<?php echo $url; ?>">View this Petition</a>
				<div class="clear"></div>
			</div>
		</section>
		<?php
		$html = ob_get_clean();
		$html = apply_filters( 'wtp-default-view', $html );

		wp_reset_postdata();

		return $html;
	}

}

WTP_View_Default::instance();
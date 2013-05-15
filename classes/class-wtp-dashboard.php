<?php

// include our dependencies
require_once( __DIR__ . '/class-wtp-core.php' );

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
			WTP_Core::setup();
		}

		return self::$_instance;
	}

	/**
	 * Figures out which page to load and then renders it
	 */
	public static function load() {
		// we need to find out what we're loading so do a series of checks
		if( isset( $_GET[ 'edit' ] ) )
			WTP_Edit_Petition::load();
		else
			self::_render_dashboard();
	}

	/**
	 * Renders the dashboard for this plugin
	 */
	private static function  _render_dashboard() {
		?>
		<div class="wrap">
			<div class="alignleft us-seal"></div>
			<div class="alignleft logo"></div>
			<div class="clear"></div>
			<p>The right to petition your government is guaranteed by the First Amendment of the United States Constitution. <a href="https://petitions.whitehouse.gov/how-why/introduction">We the People</a> provides a new way to petition the Obama Administration to take action on a range of important issues facing our country. We created <a href="https://petitions.whitehouse.gov/how-why/introduction">We the People</a> because we want to hear from you. If a petition gets enough support, White House staff will review it, ensure it’s sent to the appropriate policy experts, and issue an official response.</p>
			<a href="<?php menu_page_url( 'we-the-people-import' ); ?>" class="button-primary">Import A Petition</a>
			<a href="https://petitions.whitehouse.gov/petition/create" class="button">Create a Petition</a>
			<h3>Your Imported Petitions:</h3>
			<table class="wp-list-table widefat fixed imported-petitions">
				<thead>
					<tr>
						<th>Petition</th>
						<th style="width: 200px;">Short Code</th>
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
						<th style="width: 200px;">Short Code</th>
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
			$progress = 0;
			if( $signature_count >= $signatures_needed )
				$progress = 100;
			else if( $signatures_needed === 0 )
				$progress = 100;
			else
				$progress = $signature_count / $signatures_needed;

			?>
			<tr>
				<td><?php the_title(); ?></td>
				<td><code>[petition id="<?php echo $id; ?>"/]</code></td>
				<td>May 24, 2013</td>
				<td>
					<div class="alignleft progress-bar">
						<div class="progress" data-progress="<?php echo $progress; ?>"></div>
					</div>
					<div class="alignleft progress-bar-text"><?php echo $signature_count; ?> / <?php echo $signatures_needed; ?></div>
					<div class="clear"></div>
				</td>
				<td style="text-align: right;"><a href="<?php echo admin_url( 'post.php?post=' . $id . '&action=edit' ); ?>">Edit</a> | <a href="javascript:void(0);">Remove</a></td>
			</tr>
			<?php

		endwhile;
	}

}

WTP_Dashboard::instance();
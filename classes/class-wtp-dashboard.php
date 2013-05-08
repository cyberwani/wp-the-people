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
	 * Renders the dashboard for this plugin
	 */
	public function render_dashboard() {
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
					<tr>
						<td>Immediately address the issue of gun control through the introduction of legislation in Congress.</td>
						<td><code>[petition id="24"][/petition]</code></td>
						<td>January 13, 2013</td>
						<td>
							<div class="alignleft progress-bar">
								<div class="progress" data-progress="78"></div>
							</div>
							<div class="alignleft progress-bar-text">50,127 / 79,842</div>
							<div class="clear"></div>
						</td>
						<td style="text-align: right;"><a href="javascript:void(0);">Edit</a> | <a href="javascript:void(0);">Remove</a></td>
					</tr>
					<tr class="alternate">
						<td>Teach public school children the truth: ANTI-RACIST IS A CODE WORD FOR ANTI-WHITE!</td>
						<td><code>[petition id="36"][/petition]</code></td>
						<td>May 31, 2013</td>
						<td>
							<div class="alignleft progress-bar">
								<div class="progress" data-progress="1"></div>
							</div>
							<div class="alignleft progress-bar-text">197 / 99,803</div>
							<div class="clear"></div>
						</td>
						<td style="text-align: right;"><a href="javascript:void(0);">Edit</a> | <a href="javascript:void(0);">Remove</a></td>
					</tr>
					<tr>
						<td>Save the Postal Service-Save American Jobs</td>
						<td><code>[petition id="109"][/petition]</code></td>
						<td>May 24, 2013</td>
						<td>
							<div class="alignleft progress-bar">
								<div class="progress" data-progress="19"></div>
							</div>
							<div class="alignleft progress-bar-text">16,238 / 83,762</div>
							<div class="clear"></div>
						</td>
						<td style="text-align: right;"><a href="javascript:void(0);">Edit</a> | <a href="javascript:void(0);">Remove</a></td>
					</tr>
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

}

WTP_Dashboard::instance();
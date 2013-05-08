<?php

// include our dependencies
require_once( __DIR__ . '/class-wtp-api.php' );
require_once( __DIR__ . '/class-wtp-core.php' );
require_once( __DIR__ . '/class-wtp-importer.php' );

class WTP_Importer_Step_One {

	/**
	 * The singleton instance of this class.
	 *
	 * @var bool|WTP_Importer_Step_One
	 */
	protected static $_instance = false;

	/**
	 * A blank constructor
	 */
	public function __construct() {}

	/**
	 * Gets the singleton instance of this class and adds any actions/filters if need be
	 *
	 * @return bool|WTP_Importer_Step_One
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
	 * Adds any actions necessary for this class to work.
	 */
	private static function _add_actions() {
		add_action( 'wp_ajax_we-the-people-import-search-api', array( self::$_instance, 'search_petitions' ) );
	}

	/**
	 * Search the petitions with the pre-built API
	 */
	public function search_petitions() {
		check_ajax_referer( 'wtp-importer-step-one' );
		$search_text = $_REQUEST[ 'text' ];

		if( $search_text === '' )
			die( '<tr><td colspan="5"><p>Search for something using the box above.</p></td></tr>' );

		$params = array(
			'title' => $search_text,
			'limit' => 100,
		);

		// check for the status parameter
		if( isset( $_REQUEST[ 'status' ] ) && $_REQUEST[ 'status' ] === 'open' || $_REQUEST[ 'status' ] === 'closed' || $_REQUEST[ 'status' ] === 'responded' ) {
			$params[ 'status' ] = $_REQUEST[ 'status' ];
		}

		$petitions = WTP_API::get_petitions( $params );

		if( ! $petitions || ! isset( $petitions[ 'results' ] ) || count( $petitions[ 'results' ] ) === 0 )
			die( '<tr><td colspan="6"><p>No results were found for "' . $search_text . '".</p></td></tr>' );

		$html = '';
		$alternate = false;
		foreach( $petitions[ 'results' ] as $data ) {
			// highlight any parts of the text that were found
			$data[ 'title' ] = preg_replace( '/' . $search_text . '/i', '<span class="search-highlight">$0</span>', $data[ 'title' ] );
			$alternate = ! $alternate;

			ob_start();
			?>
			<tr <?php echo $alternate ? 'class="alternate"' : ''; ?>>
				<td><?php echo $data[ 'title' ]; ?></td>
				<td style="text-align: center;"><?php echo date( 'M. d, Y', $data[ 'deadline' ]) ?></td>
				<td style="text-align: center;"><?php echo $data[ 'signatureCount' ]; ?></td>
				<td style="text-align: center;"><?php echo $data[ 'signaturesNeeded' ]; ?></td>
				<td style="text-align: center;"><?php echo $data[ 'status' ]; ?></td>
				<td style="text-align: center;">
					<a target="_blank" href="<?php echo $data[ 'url' ]; ?>">View Details</a> | <a href="<?php admin_url( 'admin.php' ); ?>?page=we-the-people-import&step=2&id=<?php echo urlencode( $data[ 'id' ] ); ?>">Select Petition</a>
				</td>
			</tr>
			<?php
			$html .= ob_get_clean();
		}
		$html = str_replace( array( "\t", "\n", "\r" ), '', $html );
		die( $html );
	}

	public static function render() {
		// enqueue the appropriate JS
		wp_enqueue_script( 'wtp-import-step-one', plugins_url( '/../js/admin/import-step-one.js', __FILE__ ), array( 'jquery', 'wtp-helpers' ) );

		?>
		<div class="wrap">
			<div class="alignleft us-seal"></div>
			<div class="alignleft logo"></div>
			<div class="clear"></div>
			<h2>Import a Petition</h2>
			<p>To get started, begin by searching for a petition containing the keywords you enter in the search box below. After finding the petition you would like to import, continue by clicking the <b>`Select Petition`</b> link.</p>
			<div class="search-box">
				<div class="alignleft search-text">Search Petitions:</div>
				<input autocomplete="off" class="alignleft search-input" type="text"/>
				<div class="alignleft search-text" style="margin: 11px 10px 0 10px;">Status:</div>
				<select autocomplete="off" class="alignleft search-status">
					<option value="any">Any</option>
					<option value="open">Open</option>
					<option value="closed">Closed</option>
					<option value="responded">Responded</option>
				</select>
				<img class="alignleft search-loader" src="<?php echo plugins_url( '/../images/ajax-loader-fb.gif', __FILE__ ); ?>"/>
				<div class="clear"></div>
			</div>
			<h3>Search Results:</h3>
			<table class="wp-list-table widefat fixed search-results">
				<thead>
				<tr>
					<th>Name</th>
					<th style="width: 90px; text-align: center;">End Date</th>
					<th style="width: 110px;">Total Signatures</th>
					<th style="width: 120px;">Signatures Needed</th>
					<th style="width: 80px; text-align: center;">Status</th>
					<th style="width: 170px;"></th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td colspan="6"><p>Search for something using the box above.</p></td>
				</tr>
				</tbody>
			</table>
		</div>
		<script type="text/javascript">
			WTPHelpers.ajaxURL = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
			WTPHelpers.nonce = '<?php echo wp_create_nonce( 'wtp-importer-step-one' ); ?>';
		</script>
		<?php
	}

}

WTP_Importer_Step_One::instance();
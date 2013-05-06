<?php

class We_The_People_Import {

	/**
	 * The singleton instance of this class.
	 *
	 * @var bool|We_The_People_Import
	 */
	protected static $_instance = false;

	/**
	 * A blank constructor
	 */
	public function __construct() {}

	/**
	 * Gets the singleton instance of this class and adds any actions/filters if need be
	 *
	 * @return bool|We_The_People_Import
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
		add_action( 'admin_enqueue_scripts', array( self::$_instance, 'enqueue_styles_and_scripts' ) );
		add_action( 'wp_ajax_we-the-people-import-search-api', array( self::$_instance, 'search_petitions' ) );
	}

	/**
	 * Search the petitions with the pre-built API
	 */
	public function search_petitions() {
		check_ajax_referer( 'we-the-people-importer' );
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

		$petitions = We_The_People_API::get_petitions( $params );

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
					<a target="_blank" href="<?php echo $data[ 'url' ]; ?>">View Details</a> | <a href="/wp-admin/admin.php?page=we-the-people-import&step=2">Select Petition</a>
				</td>
			</tr>
			<?php
			$html .= ob_get_clean();
		}
		$html = str_replace( array( "\t", "\n", "\r" ), '', $html );
		die( $html );
	}

	/**
	 * Selectively enqueues CSS & JS based on what page the user is currently visiting
	 *
	 * @param array $hook
	 */
	public function enqueue_styles_and_scripts( $hook = array() ) {
		if( ! is_array( $hook ) )
			$hook = array( $hook );

		if( ! in_array( 'we-the-people_page_we-the-people-import', $hook ) )
			return;

		wp_enqueue_style( 'we-the-people-general', plugins_url( '/../css/admin/general.css', __FILE__ ) );
		wp_enqueue_style( 'we-the-people-import', plugins_url( '/../css/admin/import.css', __FILE__ ) );
		wp_enqueue_script( 'we-the-people-import', plugins_url( '/../js/admin/import.js', __FILE__ ), array( 'jquery', 'underscore' ) );
	}

	/**
	 * Renders the import page for petitions
	 */
	public function render_import_page() {
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
					<th style="width: 160px;"></th>
				</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="6"><p>Search for something using the box above.</p></td>
					</tr>
				</tbody>
			</table>
		</div>
		<script type="text/javascript">WeThePeopleImport.start( '<?php echo admin_url( 'admin-ajax.php' ); ?>', '<?php echo wp_create_nonce( 'we-the-people-importer' ); ?>' );</script>
	<?php
	}
}

We_The_People_Import::instance();
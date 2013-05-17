<?php

// include our dependencies
require_once( __DIR__ . '/class-wtp-api.php' );
require_once( __DIR__ . '/class-wtp-core.php' );
require_once( __DIR__ . '/class-wtp-importer.php' );

class WTP_Importer_Step_Two {

	/**
	 * The singleton instance of this class.
	 *
	 * @var bool|WTP_Importer_Step_Two
	 */
	protected static $_instance = false;

	/**
	 * A blank constructor
	 */
	public function __construct() {}

	/**
	 * Gets the singleton instance of this class and adds any actions/filters if need be
	 *
	 * @return bool|WTP_Importer_Step_Two
	 */
	public static function instance() {
		if( ! self::$_instance ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public static function render() {
		if( ! isset( $_GET[ 'id' ] ) )
			die( '<script type="text/javascript">window.location.href = \'' . admin_url( 'admin.php' ) . '?page=we-the-people-import\';</script>' );

		$petition = WTP_API::get_petition( $_GET[ 'id' ] );

		if( ! isset( $petition[ 'results' ] ) || count( $petition[ 'results' ] ) === 0 )
			die( '<script type="text/javascript">window.location.href = \'' . admin_url( 'admin.php' ) . '?page=we-the-people-import\';</script>' );

		// enqueue the appropriate JS
		wp_enqueue_script( 'wtp-import-step-two', WTP_Core::$plugins_url . '/js/admin/importer-step-two.js', array( 'jquery', 'wtp-helpers' ) );

		$petition = $petition[ 'results' ][ 0 ];

        // prevent division by zero
        if( 1 <= $petition[ 'signaturesNeeded' ] ) {
            // make sure that this percentage does not exceed 100
            $petition_progress = ( ( $petition[ 'signatureCount' ] / $petition[ 'signaturesNeeded' ] ) * 100 );
            if( 100 < $petition_progress )
                $petition_progress = 100;
        }
        else
            $petition_progress = 100;

		?>
		<div class="wrap">
			<div class="alignleft us-seal"></div>
			<div class="alignleft logo"></div>
			<div class="clear"></div>
			<div class="phase-container">
				<h2>Import Petition Confirmation</h2>
				<p>Please confirm that this is the petition you were looking for. Once you have done so, we'll automatically synchronize your website with the latest petition data.</p>
				<div class="petition-preview">
					<h3><?php echo $petition[ 'title' ]; ?></h3>
					<span>Created on: <?php echo date( 'F d, Y', $petition[ 'created' ] ); ?></span>
					<span>Ends on: <?php echo date( 'F d, Y', $petition[ 'deadline' ] ); ?></span>
					<span>Status: <i><?php echo ucwords( $petition[ 'status' ] ); ?></i></span>
					<?php echo wpautop( $petition[ 'body' ] ); ?>
					<div class="row">
						<span class="alignleft">Progress:</span>
						<div class="progress-bar alignleft">
							<div class="progress" data-progress="<?php echo $petition_progress; ?>"></div>
						</div>
						<span class="alignleft"><?php echo $petition[ 'signatureCount' ]; ?> of <?php echo $petition[ 'signaturesNeeded' ]; ?></span>
						<div class="clear"></div>
					</div>
				</div>
				<a class="button-primary import-petition" href="<?php menu_page_url( 'we-the-people-import' ); ?>&step=3&petition_id=<?php echo $petition[ 'id' ]; ?>">Import this Petition</a>
				<a class="button cancel-import" href="<?php menu_page_url( 'we-the-people-import' ); ?>">Cancel</a>
			</div>
		</div>
		<?php
	}

}

WTP_Importer_Step_Two::instance();
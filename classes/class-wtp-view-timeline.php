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

		wp_enqueue_script( 'chart-js', WTP_Core::$plugins_url . '/js/chart/chart.js' );

	}

	/**
	 * Handles rendering this view
	 *
	 * @param bool|WP_Post $post
	 * @param array $attributes
	 * @return string
	 */
	public static function render( $post = false, $attributes = array() ) {

		$days = array();
		$weeks = array();
		$signature_counts = array();
		$signature_totals = array();

		$petition_id = get_post_meta( $post->ID, 'petition_id', true );

		$default_interval = 'daily';
		$possible_intervals = array( 'daily', 'weekly', 'monthly' );

		$default_show = 'total';
		$possible_show = array( 'total', 'count', 'both' );

		$default_attrinbutes = array(
			'interval' => 'daily',
			'show' 	   => 'total',
			'type'     => 'line',
		);

		$attributes = wp_parse_args( $attributes, $default_attrinbutes );

		if( ! in_array( $attributes['interval'], $possible_intervals ) )
			$interval = $default_interval;

		if( ! in_array( $attributes['show'], $possible_show ) )
			$show = $default_show;

		// get the geographic petition data
		$timeline_data_array = WTP_Intermediary_API::get_petition_timeline_data( $petition_id, $attributes['interval'] );

		$signature_count = 0;

		if ( ! $timeline_data )
			return;

		foreach ( $timeline_data_array as $timeline_entry ) {


			if ( "total" == $attributes['show'] || "both" == $attributes['show'] )
				$signature_totals[] = $signature_count += $timeline_entry['count'];

			if ( "count" == $attributes['show'] || "both" == $attributes['show'] )
				$signature_counts[] = $timeline_entry['count'];

			if ( isset( $timeline_entry['timestamp'] ) )
				$days[] = date( 'm \/ d', $timeline_entry['timestamp'] );

			if ( isset( $timeline_entry['start'] ) && isset( $timeline_entry['end'] ) )
				$weeks[] = date( 'm \/ d', $timeline_entry['start'] ) . ' - ' . date( 'm \/ d', $timeline_entry['end'] );

		}

		$signature_totals = implode( ', ', $signature_totals );
		$signature_counts = implode( ', ', $signature_counts );

		$days = implode( '", "', $days );
		$weeks = implode( '", "', $weeks );

		$dates = ( $weeks ) ? '"' . $weeks . '"' : '"' . $days . '"';

		ob_start();
		?>
		<canvas id="petition-timeline-<?php echo $petition_id . '-' . $attributes['interval']; ?>" width="<?php echo $attributes['width']; ?>" height="<?php echo $attributes['height']; ?>"></canvas>
		<script>
			var lineChartData = {
				labels : [<?php echo $dates; ?>],
				datasets : [
					{
						fillColor : "rgba(220,220,220,0.5)",
						strokeColor : "rgba(220,220,220,1)",
						pointColor : "rgba(220,220,220,1)",
						pointStrokeColor : "#fff",
						data : [<?php echo $signature_totals; ?>]
					},
					{
						fillColor : "rgba(151,187,205,0.5)",
						strokeColor : "rgba(151,187,205,1)",
						pointColor : "rgba(151,187,205,1)",
						pointStrokeColor : "#fff",
						data : [<?php echo $signature_counts; ?>]
					}
				]

			}

			var myLine = new Chart(document.getElementById("petition-timeline-<?php echo $petition_id . '-' . $attributes['interval']; ?>").getContext("2d")).Line(lineChartData);

		</script>
		<?php
		$html = ob_get_clean();
		$html = apply_filters( 'wtp-view-timeline', $html );

		return $html;
	}

}

WTP_View_Timeline::instance();
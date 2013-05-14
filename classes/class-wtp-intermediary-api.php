<?php

class WTP_Intermediary_API {




	/**
	 * The base URL used for making any API requests
	 *
	 * @var string
	 */
	private static $_base_url = 'http://my.dev/wtp-api/';

	/**
	 * An empty constructor
	 */
	public function __construct() {}

	/**
	 * Retrieves the specified result via cURL and JSON decodes it. If no data is found, false is returned.
	 *
	 * @param string $url The URL we're retrieving
	 * @return mixed
	 */
	protected static function _retrieve_url( $url = '' ) {
		$result = wp_remote_get( $url );
		if( is_wp_error( $result ) )
			return false;

		if( 200 !== wp_remote_retrieve_response_code( $result ) )
			return false;

		$data = json_decode( wp_remote_retrieve_body( $result ), true );

		if( is_null( $data ) )
			return false;

		return $data;
	}

	/**
	 * Gets the petition data for the specified petition
	 *
	 * @param string $id
	 * @return mixed
	 */
	public static function get_petition_data( $id = '' ) {
		$params = array(
			'id' => $id
		);
		$url = self::$_base_url . '?' . http_build_query( $params );
		return self::_retrieve_url( $url );
	}

	/**
	 * Gets the geographical data for the specified petition
	 *
	 * @param string $id
	 * @return mixed
	 */
	public static function get_petition_geographical_data( $id = '' ) {
		$params = array(
			'id' => $id,
			'type' => 'geographical'
		);

		$url = self::$_base_url . '?' . http_build_query( $params );
		return self::_retrieve_url( $url );
	}

	/**
	 * Gets the specified timeline data for the petition
	 *
	 * @param string $id
	 * @param string $interval
	 * @param int $start
	 * @param int $end
	 * @return mixed
	 */
	public static function get_petition_timeline_data( $id = '', $interval = 'daily', $start = 0, $end = 0 ) {
		$default_interval = 'daily';
		$possible_intervals = array( 'daily', 'weekly', 'monthly' );
		if( ! in_array( $interval, $possible_intervals ) )
			$interval = $default_interval;

		$params = array(
			'id' => $id,
			'interval' => $interval,
			'type' => 'timeline',
		);

		if( $start && 10 === strlen( $start ) )
			$params[ 'start' ] = $start;
		if( $end && 10 === strlen( $end ) )
			$params[ 'end' ] = $end;

		$url = self::$_base_url . '?' . http_build_query( $params );
		return self::_retrieve_url( $url );
	}

}
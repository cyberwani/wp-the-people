<?php

class WTP_Intermediary_API {

	/**
	 * The base URL used for making any API requests
	 *
	 * @var string
	 */
	private static $_base_url = 'http://api.10uplabs.com/wethepeople/';

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
	 * Retrieves the URL with caching involved. Checks the hourly key & degrades to daily key when not accessible.
	 *
	 * @param string $url
	 * @return bool|mixed
	 */
	protected static function _retrieve_url_with_caching( $url = '' ) {
		if( empty( $url ) )
			return false;

		$key = md5( $url );
		$hourly_key = $key . '-hourly';
		$daily_key = $key . '-daily';

		// try getting the hourly key
		$data = get_transient( $hourly_key );

		if( ! $data ) {
			// hourly key failed, try grabbing again
			$data = self::_retrieve_url( $url );
			if( ! $data ) {
				// hourly key didn't exist and API failed, check the daily key as a last result
				$data = get_transient( $daily_key );

				if( ! $data )
					return false;

				return $data;
			}

			// reset transients
			set_transient( $hourly_key, $data, 3600 );
			set_transient( $daily_key, $data, 86400 );
		}

		return $data;
	}

	/**
	 * Gets the petition data for the specified petition
	 *
	 * @param string $id
	 * @param bool $use_cache
	 * @return mixed
	 */
	public static function get_petition_data( $id = '', $use_cache = true ) {
		$params = array(
			'id' => $id
		);
		$url = self::$_base_url . '?' . http_build_query( $params );

		if( $use_cache )
			return self::_retrieve_url_with_caching( $url );

		return self::_retrieve_url( $url );
	}

	/**
	 * Searches the intermediary API for petitions with a like %% query
	 *
	 * @param array $params
	 * @param bool $use_cache
	 * @return mixed
	 */
	public static function search_petitions( $params = array(), $use_cache = true ) {
		$params[ 'type' ] = 'search';

		$url = self::$_base_url . '?' . http_build_query( $params );

		if( $use_cache )
			return self::_retrieve_url_with_caching( $url );

		return self::_retrieve_url( $url );
	}

	/**
	 * Gets the geographical data for the specified petition
	 *
	 * @param string $id
	 * @param bool $use_cache
	 * @return mixed
	 */
	public static function get_petition_geographical_data( $id = '', $use_cache = true ) {
		$params = array(
			'id' => $id,
			'type' => 'geographical'
		);

		$url = self::$_base_url . '?' . http_build_query( $params );

		if( $use_cache )
			return self::_retrieve_url_with_caching( $url );

		return self::_retrieve_url( $url );
	}

	/**
	 * Gets the specified timeline data for the petition
	 *
	 * @param string $id
	 * @param string $interval
	 * @param int $start
	 * @param int $end
	 * @param bool $use_cache
	 * @return mixed
	 */
	public static function get_petition_timeline_data( $id = '', $interval = 'daily', $start = 0, $end = 0, $use_cache = true ) {
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

		if( $use_cache )
			return self::_retrieve_url_with_caching( $url );

		return self::_retrieve_url( $url );
	}

}
<?php

class WTP_API {

	/**
	 * The base URL used for making any API requests
	 *
	 * @var string
	 */
	private static $_base_url = 'https://api.whitehouse.gov/v1/';

	/**
	 * An empty constructor
	 */
	public function __construct() {}

	/**
	 * Returns the JSON data for a specified petition.
	 * todo: implement a cache for this data when we transition this code to a WP plugin
	 *
	 * Parameters that can be passed to the API (taken from https://petitions.whitehouse.gov/developers)
	 * mock								integer ( boolean )
	 * createdBefore					unix timestamp
	 * createdAfter						unix timestamp
	 * createdAt						unix timestamp
	 * limit							integer
	 * offset							integer
	 * title							string
	 * body								string
	 * signatureThresholdCeiling		integer
	 * signatureThreshold				integer
	 * signatureThresholdFloor			integer
	 * signatureCountCeiling			integer
	 * signatureCount					integer
	 * signatureCountFloor				integer
	 * url								string
	 * status							string
	 * responseID						string
	 * responseAssociationTimeBefore	integer
	 *
	 * @param string $id The unique ID of the petition
	 * @param array $params The GET parameters for the petition request
	 * @return mixed Returned JSON data
	 */
	public static function get_petition( $id = '', $params = array() ) {
		$url = self::$_base_url . 'petitions/' . $id . '.json?' . http_build_query( $params );
		return self::_retrieve_url( $url );
	}

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
	 * Returns a list of petitions matching passed parameters.
	 * todo: implement a cache for this data when we transition this code to a WP plugin
	 *
	 * Parameters that can be passed to the API (taken from https://petitions.whitehouse.gov/developers)
	 * createdBefore					unix timestamp
	 * createdAfter						unix timestamp
	 * createdAt						unix timestamp
	 * limit							integer
	 * offset							integer
	 * title							string
	 * body								string
	 * signatureThresholdCeiling		integer
	 * signatureThreshold				integer
	 * signatureThresholdFloor			integer
	 * signatureCountCeiling			integer
	 * signatureCount					integer
	 * signatureCountFloor				integer
	 * url								string
	 * status							string
	 * responseID						string
	 * responseAssociationTimeBefore	unix timestamp
	 * mock								integer ( boolean )
	 *
	 *
	 * @param array $params Parameters that will be used in the GET request
	 * @return mixed JSON data if the API request was successful or false if not.
	 */
	public static function get_petitions( $params = array() ) {
		$url = self::$_base_url . 'petitions.json?' . http_build_query( $params );
		return self::_retrieve_url( $url );
	}

	/**
	 * Display signatures attached to a petition. Unsupported non-Latin characters are replaced with an "x".
	 * todo: implement a cache for this data when we transition this code to a WP plugin
	 *
	 * Parameters that can be passed to the API (taken from https://petitions.whitehouse.gov/developers)
	 * petition_id			string
	 * city					string
	 * state				string
	 * zipcode				string
	 * country				string
	 * createdBefore		unix timestamp
	 * createdAfter			unix timestamp
	 * createdAt			unix timestamp
	 * limit				integer
	 * offset				integer
	 * mock					integer ( boolean )
	 *
	 * @param string $id The ID of the petition
	 * @param array $params The optional parameters
	 * @return mixed The JSON data or false if the request failed
	 */
	public static function get_signatures( $id = '', $params = array() ) {
		$url = self::$_base_url . 'petitions/' . $id . '/signatures.json?' . http_build_query( $params );
		return self::_retrieve_url( $url );
	}
}
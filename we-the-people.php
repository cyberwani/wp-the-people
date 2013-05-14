<?php

/*
Plugin Name: WP The People
Plugin URI: http://10up.com
Description: A plugin that uses the We The People API written for the White House hackathon
Version: 1.0.0
Author: 10up, Carl Danley, Chris Cochran, Mo Jangda
Author URI: http://10up.com
*/

// include the API class
require_once( __DIR__ . '/classes/class-wtp-api.php' );

// include the base class
require_once( __DIR__ . '/classes/class-wtp-core.php' );

// include the dashboard code
require_once( __DIR__ . '/classes/class-wtp-dashboard.php' );

// include the importer code
require_once( __DIR__ . '/classes/class-wtp-importer.php' );
require_once( __DIR__ . '/classes/class-wtp-importer-step-one.php' );
require_once( __DIR__ . '/classes/class-wtp-importer-step-two.php' );
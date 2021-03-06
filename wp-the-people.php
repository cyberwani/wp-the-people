<?php

/*
Plugin Name: WP The People
Plugin URI: http://10up.com
Description: A plugin that uses the We The People API written for the White House hackathon
Version: 1.0.0
Author: 10up, Carl Danley
Author URI: http://10up.com
*/

// include the API class
require_once( __DIR__ . '/classes/class-wtp-api.php' );

// include the Intermediary API
require_once( __DIR__ . '/classes/class-wtp-intermediary-api.php' );

// include our petition post type
require_once( __DIR__ . '/classes/class-wtp-petitions.php' );

// include the base class
require_once( __DIR__ . '/classes/class-wtp-core.php' );

// include the dashboard code
require_once( __DIR__ . '/classes/class-wtp-dashboard.php' );

// include the importer code
require_once( __DIR__ . '/classes/class-wtp-importer.php' );
require_once( __DIR__ . '/classes/class-wtp-importer-step-one.php' );
require_once( __DIR__ . '/classes/class-wtp-importer-step-two.php' );
require_once( __DIR__ . '/classes/class-wtp-importer-step-three.php' );

// add all of our views
require_once( __DIR__ . '/classes/class-wtp-view-default.php' );
require_once( __DIR__ . '/classes/class-wtp-view-geographic.php' );
require_once( __DIR__ . '/classes/class-wtp-view-timeline.php' );

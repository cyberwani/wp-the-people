<?php

/*
Plugin Name: We The People
Plugin URI: http://10up.com
Description: A plugin that uses the We The People API written for the White House hackathon
Version: 1.0.0
Author: 10up, Carl Danley, Chris Cochran
Author URI: http://10up.com
*/

// include the dependency classes
include_once( __DIR__ . '/classes/class-we-the-people-api.php' );
include_once( __DIR__ . '/classes/class-we-the-people-dashboard.php' );
include_once( __DIR__ . '/classes/class-we-the-people-import.php' );

// kick everything off with the base class
include_once( __DIR__ . '/classes/class-we-the-people.php' );
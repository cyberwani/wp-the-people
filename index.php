<?php
include_once __DIR__ . '/classes/class-we-the-people-api.php';

$petition = $we_the_people_api->get_petition( '50cb6d2ba9a0b1c52e000017', array( 'mock' => '1' ) );

echo '<pre>';
var_dump( $petition );
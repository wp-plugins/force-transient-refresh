<?php
/**
 * Plugin Name: Force Transient Refresh
 * Plugin URI: http://zachwills.net/force-transients-refresh-wordpress-plugin
 * Description: Force Transient Refresh is allows developers to easily force all of their transients to refresh through adding a query string to any URL.
 * Version: 0.1
 * Author: Zach Wills
 * Author URI: http://zachwills.net/
 * Text Domain: ftr
 * License: GPL2
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Load up all the transients currently saved on the site and set their
 * expiration time to 60 seconds ago. This will force them to be updated when
 * the transient is loaded.
 */
function ftr_refresh_transients() {
	// Get all the transients used on the site.
	global $wpdb;
	$sql = "SELECT `option_name` AS `name`, `option_value` AS `value`
	        FROM  $wpdb->options
	        WHERE `option_name` LIKE '%transient_timeout_%'
	        ORDER BY `option_name`";

	$results    = $wpdb->get_results( $sql );
	$transients = array();

	foreach ( $results as $transient ) {
		if ( 0 === strpos( $transient->name, '_transient_timeout_' ) ) {
			update_option( $transient->name, time() - 60 );
		}
	}
}

// Check if the site has the required $_GET paramater set.
if ( isset( $_GET['force_transient_refresh'] ) || isset( $_GET['ftr'] ) ) {
	ftr_refresh_transients();
}

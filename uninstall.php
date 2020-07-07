<?php

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$options = get_option( 'tkt_global_options' );
error_log(print_r($options, true));

$options_to_delete = array('tkt_seo_options','tkt_global_options');

if ( is_array($options) && array_key_exists('tkt_global_delete_options', $options) )
 	foreach ($options_to_delete as $option_to_delete) {
 	 	delete_option($option_to_delete);
 	 } 
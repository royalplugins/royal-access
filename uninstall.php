<?php
/**
 * Royal Access Uninstall
 *
 * Fired when the plugin is uninstalled.
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'raccess_settings' );
delete_option( 'raccess_activated_time' );

wp_cache_flush();

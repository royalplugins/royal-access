<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Installer — activation / deactivation hooks.
 */
class RACCESS_Installer {

	/**
	 * Runs on plugin activation — sets default options.
	 */
	public static function activate() {
		if ( false === get_option( 'raccess_settings' ) ) {
			add_option( 'raccess_settings', array() );
		}

		add_option( 'raccess_activated_time', gmdate( 'Y-m-d H:i:s' ) );
	}

	/**
	 * Runs on plugin deactivation.
	 */
	public static function deactivate() {
		// Nothing to clean up — options removed in uninstall.php.
	}
}

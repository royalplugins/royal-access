<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Core bootstrap — initialises frontend components on non-admin pages.
 */
class RACCESS_Core {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
			self::$instance->init();
		}
		return self::$instance;
	}

	private function __construct() {
		// Private constructor for singleton.
	}

	private function init() {
		if ( is_admin() ) {
			return;
		}

		// Automatic fixes always run — independent of toolbar.
		RACCESS_Fixes::instance();

		$settings = raccess_get_settings();

		if ( ! $settings['enable_toolbar'] ) {
			return;
		}

		if ( $settings['hide_for_admins'] && current_user_can( 'manage_options' ) ) {
			return;
		}

		RACCESS_Toolbar::instance();
	}
}

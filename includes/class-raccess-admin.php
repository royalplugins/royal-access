<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin — menus, enqueue, page renders, footer branding.
 */
class RACCESS_Admin {

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
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ) );
	}

	/**
	 * Register admin menu pages.
	 */
	public function add_menu() {
		add_menu_page(
			__( 'Royal Access', 'royal-access' ),
			__( 'Royal Access', 'royal-access' ),
			'manage_options',
			'royal-access',
			array( $this, 'dashboard_page' ),
			'dashicons-universal-access-alt',
			81
		);

		add_submenu_page(
			'royal-access',
			__( 'Dashboard', 'royal-access' ),
			__( 'Dashboard', 'royal-access' ),
			'manage_options',
			'royal-access',
			array( $this, 'dashboard_page' )
		);

		add_submenu_page(
			'royal-access',
			__( 'Settings', 'royal-access' ),
			__( 'Settings', 'royal-access' ),
			'manage_options',
			'raccess-settings',
			array( $this, 'settings_page' )
		);
	}

	/**
	 * Enqueue admin assets (only on plugin pages).
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function enqueue_assets( $hook ) {
		if ( strpos( $hook, 'royal-access' ) === false && strpos( $hook, 'raccess-' ) === false ) {
			return;
		}

		$css_file    = RACCESS_PLUGIN_DIR . 'admin/css/admin.css';
		$js_file     = RACCESS_PLUGIN_DIR . 'admin/js/admin.js';
		$css_version = file_exists( $css_file ) ? filemtime( $css_file ) : RACCESS_VERSION;
		$js_version  = file_exists( $js_file ) ? filemtime( $js_file ) : RACCESS_VERSION;

		wp_enqueue_style( 'raccess-admin', RACCESS_PLUGIN_URL . 'admin/css/admin.css', array(), $css_version );
		wp_enqueue_script( 'raccess-admin', RACCESS_PLUGIN_URL . 'admin/js/admin.js', array( 'jquery', 'wp-color-picker' ), $js_version, true );
		wp_enqueue_style( 'wp-color-picker' );

		wp_localize_script( 'raccess-admin', 'raccess_ajax', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'raccess_nonce' ),
			'strings'  => array(
				'saving'  => __( 'Saving...', 'royal-access' ),
				'saved'   => __( 'Settings saved.', 'royal-access' ),
				'error'   => __( 'An error occurred.', 'royal-access' ),
				'copied'  => __( 'Copied to clipboard!', 'royal-access' ),
			),
		) );
	}

	/**
	 * Render dashboard page.
	 */
	public function dashboard_page() {
		include RACCESS_PLUGIN_DIR . 'admin/views/dashboard.php';
	}

	/**
	 * Render settings page.
	 */
	public function settings_page() {
		include RACCESS_PLUGIN_DIR . 'admin/views/settings.php';
	}

	/**
	 * Customize admin footer text on plugin pages.
	 *
	 * @param string $text Default footer text.
	 * @return string
	 */
	public function admin_footer_text( $text ) {
		$screen = get_current_screen();

		if ( $screen && (
			strpos( $screen->id, 'royal-access' ) !== false ||
			strpos( $screen->id, 'raccess-' ) !== false
		) ) {
			return wp_kses_post( 'Built by <a href="https://royalplugins.com" target="_blank">Royal Plugins</a> | Thank you for creating with WordPress.' );
		}

		return $text;
	}
}

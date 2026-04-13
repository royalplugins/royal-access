<?php
/**
 * Plugin Name: Royal Access
 * Plugin URI:  https://royalplugins.com/royal-access/
 * Description: Free WordPress accessibility toolbar with 14 features, WCAG code fixes, contrast checker & statement generator. Not an overlay — no account needed.
 * Version:     1.0.3
 * Author:      Royal Plugins
 * Author URI:  https://royalplugins.com
 * License:     GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: royal-access
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'RACCESS_VERSION', '1.0.3' );
define( 'RACCESS_PLUGIN_FILE', __FILE__ );
define( 'RACCESS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'RACCESS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'RACCESS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Autoloader — explicit file map.
 */
spl_autoload_register( function( $class ) {
	if ( strpos( $class, 'RACCESS_' ) !== 0 ) {
		return;
	}

	$file_map = array(
		'RACCESS_Core'      => 'includes/class-raccess-core.php',
		'RACCESS_Installer'  => 'includes/class-raccess-installer.php',
		'RACCESS_Admin'      => 'includes/class-raccess-admin.php',
		'RACCESS_Ajax'       => 'includes/class-raccess-ajax.php',
		'RACCESS_Toolbar'    => 'includes/class-raccess-toolbar.php',
		'RACCESS_Fixes'      => 'includes/class-raccess-fixes.php',
		'RACCESS_Statement'  => 'includes/class-raccess-statement.php',
	);

	if ( isset( $file_map[ $class ] ) ) {
		$file = RACCESS_PLUGIN_DIR . $file_map[ $class ];
		if ( file_exists( $file ) ) {
			require_once $file;
		}
	}
} );

/**
 * Activation / deactivation.
 */
register_activation_hook( __FILE__, array( 'RACCESS_Installer', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'RACCESS_Installer', 'deactivate' ) );

/**
 * Bootstrap on plugins_loaded.
 */
add_action( 'plugins_loaded', function() {
	RACCESS_Core::instance();

	if ( is_admin() ) {
		RACCESS_Admin::instance();
		RACCESS_Ajax::instance();
	}

	do_action( 'raccess_initialized' );
}, 10 );

/**
 * Settings link on Plugins page.
 */
add_filter( 'plugin_action_links_' . RACCESS_PLUGIN_BASENAME, function( $links ) {
	$settings_link = '<a href="' . admin_url( 'admin.php?page=raccess-settings' ) . '">'
		. __( 'Settings', 'royal-access' ) . '</a>';
	array_unshift( $links, $settings_link );
	return $links;
} );

/**
 * Plugin row meta — docs link.
 */
add_filter( 'plugin_row_meta', function( $links, $file ) {
	if ( $file === RACCESS_PLUGIN_BASENAME ) {
		$links[] = '<a href="https://wordpress.org/support/plugin/royal-access/" target="_blank">'
			. __( 'Support', 'royal-access' ) . '</a>';
	}
	return $links;
}, 10, 2 );

/* ──────────────────────────────────────────────
 * Global settings helpers
 * ──────────────────────────────────────────── */

/**
 * Retrieve plugin settings (merged with defaults).
 *
 * @param string|null $key     Optional single key.
 * @param mixed       $default Fallback value when key is absent.
 * @return mixed
 */
function raccess_get_settings( $key = null, $default = null ) {
	$settings = get_option( 'raccess_settings', array() );

	$defaults = array(
		// Toolbar display.
		'enable_toolbar'         => true,
		'toolbar_position'       => 'right',
		'toolbar_icon_size'      => 'medium',
		'hide_on_mobile'         => false,
		'hide_for_admins'        => false,

		// Toolbar features (all enabled by default).
		'feature_font_size'      => true,
		'feature_contrast'       => true,
		'feature_dark_mode'      => true,
		'feature_dyslexia'       => true,
		'feature_line_height'    => true,
		'feature_letter_spacing' => true,
		'feature_word_spacing'   => true,
		'feature_links'          => true,
		'feature_big_cursor'     => true,
		'feature_animations'     => true,
		'feature_images'         => true,
		'feature_monochrome'     => true,
		'feature_reading_guide'  => true,
		'feature_focus'          => true,

		// Automatic fixes.
		'fix_skip_link'          => true,
		'fix_focus_css'          => true,
		'fix_viewport'           => true,
		'fix_read_more'          => true,

		// Toolbar colors.
		'toolbar_bg_color'       => '#1e293b',
		'toolbar_text_color'     => '#f1f5f9',
		'toolbar_accent_color'   => '#3b82f6',

		// Statement.
		'statement_page'         => 0,
	);

	$settings = wp_parse_args( $settings, $defaults );

	if ( null !== $key ) {
		return isset( $settings[ $key ] ) ? $settings[ $key ] : $default;
	}

	return $settings;
}

/**
 * Update plugin settings (merges into existing).
 *
 * @param array $new_settings Key-value pairs to merge.
 * @return bool
 */
function raccess_update_settings( $new_settings ) {
	$settings = raccess_get_settings();
	$settings = wp_parse_args( $new_settings, $settings );
	return update_option( 'raccess_settings', $settings );
}

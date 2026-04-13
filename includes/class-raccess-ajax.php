<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AJAX handlers for admin operations.
 */
class RACCESS_Ajax {

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
		add_action( 'wp_ajax_raccess_save_settings', array( $this, 'save_settings' ) );
		add_action( 'wp_ajax_raccess_check_contrast', array( $this, 'check_contrast' ) );
		add_action( 'wp_ajax_raccess_generate_statement', array( $this, 'generate_statement' ) );
	}

	/**
	 * Verify capability + nonce.
	 *
	 * @return bool
	 */
	private function verify_request() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'royal-access' ) ) );
			return false;
		}

		if ( ! check_ajax_referer( 'raccess_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'royal-access' ) ) );
			return false;
		}

		return true;
	}

	/**
	 * Save all plugin settings.
	 */
	public function save_settings() {
		if ( ! $this->verify_request() ) {
			return;
		}

		$raw = isset( $_POST['settings'] ) ? wp_unslash( $_POST['settings'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Nonce verified in verify_request(); JSON decoded and sanitized per-value below.

		if ( empty( $raw ) ) {
			wp_send_json_error( array( 'message' => __( 'No settings data received.', 'royal-access' ) ) );
			return;
		}

		$data = json_decode( $raw, true );

		if ( ! is_array( $data ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid settings data.', 'royal-access' ) ) );
			return;
		}

		$sanitized = $this->sanitize_settings( $data );
		raccess_update_settings( $sanitized );

		wp_send_json_success( array( 'message' => __( 'Settings saved.', 'royal-access' ) ) );
	}

	/**
	 * Sanitize incoming settings.
	 *
	 * @param array $data Raw settings array.
	 * @return array Sanitized settings.
	 */
	private function sanitize_settings( $data ) {
		$sanitized = array();

		// Boolean settings.
		$booleans = array(
			'enable_toolbar', 'hide_on_mobile', 'hide_for_admins',
			'feature_font_size', 'feature_contrast', 'feature_dark_mode',
			'feature_dyslexia', 'feature_line_height', 'feature_letter_spacing',
			'feature_word_spacing', 'feature_links', 'feature_big_cursor',
			'feature_animations', 'feature_images', 'feature_monochrome',
			'feature_reading_guide', 'feature_focus',
			'fix_skip_link', 'fix_focus_css', 'fix_viewport', 'fix_read_more',
		);

		foreach ( $booleans as $key ) {
			if ( isset( $data[ $key ] ) ) {
				$sanitized[ $key ] = (bool) $data[ $key ];
			}
		}

		// Select fields.
		if ( isset( $data['toolbar_position'] ) ) {
			$sanitized['toolbar_position'] = in_array( $data['toolbar_position'], array( 'left', 'right' ), true )
				? $data['toolbar_position']
				: 'right';
		}

		if ( isset( $data['toolbar_icon_size'] ) ) {
			$sanitized['toolbar_icon_size'] = in_array( $data['toolbar_icon_size'], array( 'small', 'medium', 'large' ), true )
				? $data['toolbar_icon_size']
				: 'medium';
		}

		// Color fields.
		$colors = array( 'toolbar_bg_color', 'toolbar_text_color', 'toolbar_accent_color' );
		foreach ( $colors as $key ) {
			if ( isset( $data[ $key ] ) ) {
				$sanitized[ $key ] = sanitize_hex_color( $data[ $key ] );
			}
		}

		// Statement page.
		if ( isset( $data['statement_page'] ) ) {
			$sanitized['statement_page'] = absint( $data['statement_page'] );
		}

		return $sanitized;
	}

	/**
	 * Check contrast ratio between two colors.
	 */
	public function check_contrast() {
		if ( ! $this->verify_request() ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verified in verify_request() above.
		$color1 = isset( $_POST['color1'] ) ? sanitize_hex_color( wp_unslash( $_POST['color1'] ) ) : '';
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verified in verify_request() above.
		$color2 = isset( $_POST['color2'] ) ? sanitize_hex_color( wp_unslash( $_POST['color2'] ) ) : '';

		if ( empty( $color1 ) || empty( $color2 ) ) {
			wp_send_json_error( array( 'message' => __( 'Please provide two valid hex colors.', 'royal-access' ) ) );
			return;
		}

		$lum1  = $this->get_relative_luminance( $color1 );
		$lum2  = $this->get_relative_luminance( $color2 );
		$ratio = $this->get_contrast_ratio( $lum1, $lum2 );

		wp_send_json_success( array(
			'ratio'       => round( $ratio, 2 ),
			'aa_normal'   => $ratio >= 4.5,
			'aa_large'    => $ratio >= 3.0,
			'aaa_normal'  => $ratio >= 7.0,
			'aaa_large'   => $ratio >= 4.5,
		) );
	}

	/**
	 * Calculate relative luminance from hex color.
	 *
	 * @param string $hex Hex color string.
	 * @return float
	 */
	private function get_relative_luminance( $hex ) {
		$hex = ltrim( $hex, '#' );
		$r   = hexdec( substr( $hex, 0, 2 ) ) / 255;
		$g   = hexdec( substr( $hex, 2, 2 ) ) / 255;
		$b   = hexdec( substr( $hex, 4, 2 ) ) / 255;

		$r = ( $r <= 0.03928 ) ? $r / 12.92 : pow( ( $r + 0.055 ) / 1.055, 2.4 );
		$g = ( $g <= 0.03928 ) ? $g / 12.92 : pow( ( $g + 0.055 ) / 1.055, 2.4 );
		$b = ( $b <= 0.03928 ) ? $b / 12.92 : pow( ( $b + 0.055 ) / 1.055, 2.4 );

		return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
	}

	/**
	 * Calculate contrast ratio between two luminance values.
	 *
	 * @param float $lum1 Luminance 1.
	 * @param float $lum2 Luminance 2.
	 * @return float
	 */
	private function get_contrast_ratio( $lum1, $lum2 ) {
		$lighter = max( $lum1, $lum2 );
		$darker  = min( $lum1, $lum2 );
		return ( $lighter + 0.05 ) / ( $darker + 0.05 );
	}

	/**
	 * Generate accessibility statement HTML.
	 */
	public function generate_statement() {
		if ( ! $this->verify_request() ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verified in verify_request() above.
		$site_name = isset( $_POST['site_name'] ) ? sanitize_text_field( wp_unslash( $_POST['site_name'] ) ) : get_bloginfo( 'name' );
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verified in verify_request() above.
		$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : get_option( 'admin_email' );
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verified in verify_request() above.
		$standard = isset( $_POST['standard'] ) ? sanitize_text_field( wp_unslash( $_POST['standard'] ) ) : 'wcag22aa';

		$html = RACCESS_Statement::generate( $site_name, $email, $standard );

		wp_send_json_success( array( 'html' => $html ) );
	}
}

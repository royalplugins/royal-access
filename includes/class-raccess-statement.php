<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Accessibility statement text generator.
 */
class RACCESS_Statement {

	/**
	 * Generate accessibility statement HTML.
	 *
	 * @param string $site_name Organisation/website name.
	 * @param string $email     Contact email address.
	 * @param string $standard  Standard to reference (wcag21aa, wcag22aa, eaa).
	 * @return string Statement HTML.
	 */
	public static function generate( $site_name, $email, $standard = 'wcag22aa' ) {
		$site_name = ! empty( $site_name ) ? $site_name : get_bloginfo( 'name' );
		$email     = ! empty( $email ) ? $email : get_option( 'admin_email' );
		$date      = gmdate( 'F j, Y' );

		$standard_labels = array(
			'wcag21aa' => 'WCAG 2.1 Level AA',
			'wcag22aa' => 'WCAG 2.2 Level AA',
			'eaa'      => 'WCAG 2.1 Level AA (European Accessibility Act)',
		);

		$standard_label = isset( $standard_labels[ $standard ] ) ? $standard_labels[ $standard ] : $standard_labels['wcag22aa'];

		$html = '<h2>' . esc_html__( 'Accessibility Statement', 'royal-access' ) . '</h2>' . "\n\n";

		$html .= '<p>' . sprintf(
			/* translators: %s: Site name */
			esc_html__( '%s is committed to ensuring digital accessibility for people with disabilities. We are continually improving the user experience for everyone and applying the relevant accessibility standards.', 'royal-access' ),
			'<strong>' . esc_html( $site_name ) . '</strong>'
		) . '</p>' . "\n\n";

		$html .= '<h3>' . esc_html__( 'Conformance Status', 'royal-access' ) . '</h3>' . "\n";
		$html .= '<p>' . sprintf(
			/* translators: 1: Site name, 2: Accessibility standard */
			esc_html__( '%1$s aims to conform to %2$s. This is an ongoing effort, and we welcome feedback on how we can improve.', 'royal-access' ),
			esc_html( $site_name ),
			'<strong>' . esc_html( $standard_label ) . '</strong>'
		) . '</p>' . "\n\n";

		$html .= '<h3>' . esc_html__( 'Accessibility Features', 'royal-access' ) . '</h3>' . "\n";
		$html .= '<p>' . esc_html__( 'This website provides the following accessibility tools:', 'royal-access' ) . '</p>' . "\n";
		$html .= '<ul>' . "\n";
		$html .= '<li>' . esc_html__( 'Adjustable font sizes, spacing, and line height.', 'royal-access' ) . '</li>' . "\n";
		$html .= '<li>' . esc_html__( 'High contrast and dark mode display options.', 'royal-access' ) . '</li>' . "\n";
		$html .= '<li>' . esc_html__( 'Dyslexia-friendly font option.', 'royal-access' ) . '</li>' . "\n";
		$html .= '<li>' . esc_html__( 'Link highlighting for easy identification.', 'royal-access' ) . '</li>' . "\n";
		$html .= '<li>' . esc_html__( 'Animation pause controls.', 'royal-access' ) . '</li>' . "\n";
		$html .= '<li>' . esc_html__( 'Reading guide for easier line tracking.', 'royal-access' ) . '</li>' . "\n";
		$html .= '<li>' . esc_html__( 'Enhanced keyboard focus indicators.', 'royal-access' ) . '</li>' . "\n";
		$html .= '<li>' . esc_html__( 'Skip-to-content navigation link.', 'royal-access' ) . '</li>' . "\n";
		$html .= '</ul>' . "\n\n";

		$html .= '<p><em>' . esc_html__( 'Note: This statement is a template. Please review and edit it to accurately reflect your specific accessibility efforts before publishing.', 'royal-access' ) . '</em></p>' . "\n\n";

		if ( 'eaa' === $standard ) {
			$html .= '<h3>' . esc_html__( 'European Accessibility Act', 'royal-access' ) . '</h3>' . "\n";
			$html .= '<p>' . esc_html__( 'This website has been developed in consideration of the European Accessibility Act (Directive (EU) 2019/882), which aims to improve the functioning of the internal market for accessible products and services. We strive to meet the harmonised accessibility requirements set forth by the Act.', 'royal-access' ) . '</p>' . "\n\n";
		}

		$html .= '<h3>' . esc_html__( 'Feedback', 'royal-access' ) . '</h3>' . "\n";
		$html .= '<p>' . sprintf(
			/* translators: %s: Contact email address */
			esc_html__( 'We welcome your feedback on the accessibility of this website. Please let us know if you encounter accessibility barriers by contacting us at: %s', 'royal-access' ),
			'<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>'
		) . '</p>' . "\n\n";

		$html .= '<p><em>' . sprintf(
			/* translators: %s: Date */
			esc_html__( 'This statement was last updated on %s.', 'royal-access' ),
			esc_html( $date )
		) . '</em></p>' . "\n";

		return $html;
	}
}

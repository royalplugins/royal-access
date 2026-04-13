<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$raccess_settings = raccess_get_settings();

// Accessibility checklist items.
$raccess_checks = array(
	array(
		'label'  => __( 'Accessibility toolbar enabled', 'royal-access' ),
		'status' => $raccess_settings['enable_toolbar'],
	),
	array(
		'label'  => __( 'Skip-to-content link active', 'royal-access' ),
		'status' => $raccess_settings['fix_skip_link'],
	),
	array(
		'label'  => __( 'Focus indicators active', 'royal-access' ),
		'status' => $raccess_settings['fix_focus_css'],
	),
	array(
		'label'  => __( 'Viewport zoom fix active', 'royal-access' ),
		'status' => $raccess_settings['fix_viewport'],
	),
	array(
		'label'  => __( 'Read more context active', 'royal-access' ),
		'status' => $raccess_settings['fix_read_more'],
	),
	array(
		'label'  => __( 'Accessibility statement page set', 'royal-access' ),
		'status' => ! empty( $raccess_settings['statement_page'] ),
	),
);

// Count enabled toolbar features.
$raccess_feature_keys = array(
	'feature_font_size', 'feature_contrast', 'feature_dark_mode',
	'feature_dyslexia', 'feature_line_height', 'feature_letter_spacing',
	'feature_word_spacing', 'feature_links', 'feature_big_cursor',
	'feature_animations', 'feature_images', 'feature_monochrome',
	'feature_reading_guide', 'feature_focus',
);
$raccess_enabled_count = 0;
foreach ( $raccess_feature_keys as $raccess_key ) {
	if ( ! empty( $raccess_settings[ $raccess_key ] ) ) {
		$raccess_enabled_count++;
	}
}

$raccess_checks[] = array(
	'label'  => sprintf(
		/* translators: %d: number of enabled features */
		__( 'Toolbar features enabled (%d of 14)', 'royal-access' ),
		$raccess_enabled_count
	),
	'status' => $raccess_enabled_count >= 10 ? 'good' : ( $raccess_enabled_count >= 5 ? 'fair' : false ),
);

// Check OpenDyslexic font available.
$raccess_font_file = RACCESS_PLUGIN_DIR . 'public/fonts/OpenDyslexic-Regular.woff2';
$raccess_checks[] = array(
	'label'  => __( 'OpenDyslexic font file available', 'royal-access' ),
	'status' => file_exists( $raccess_font_file ),
);

$raccess_passed = 0;
foreach ( $raccess_checks as $raccess_check ) {
	if ( $raccess_check['status'] && $raccess_check['status'] !== 'fair' ) {
		$raccess_passed++;
	}
}
?>

<div class="wrap">
	<h1><?php esc_html_e( 'Royal Access', 'royal-access' ); ?></h1>

	<div class="raccess-dashboard-grid">

		<!-- Accessibility Checklist -->
		<div class="raccess-card">
			<h2 style="margin-top: 0;">
				<span class="dashicons dashicons-yes-alt" style="color: #00a32a; margin-right: 6px;"></span>
				<?php esc_html_e( 'Accessibility Checklist', 'royal-access' ); ?>
			</h2>
			<p class="description" style="margin-bottom: 16px;">
				<?php
				printf(
					/* translators: 1: passed count, 2: total count */
					esc_html__( '%1$d of %2$d checks passing', 'royal-access' ),
					intval( $raccess_passed ),
					count( $raccess_checks )
				);
				?>
			</p>

			<table class="widefat" style="border: none;">
				<tbody>
					<?php foreach ( $raccess_checks as $raccess_check ) : ?>
					<tr>
						<td style="width: 30px; padding: 10px 8px;">
							<?php if ( $raccess_check['status'] === true || $raccess_check['status'] === 'good' ) : ?>
								<span class="dashicons dashicons-yes-alt" style="color: #00a32a;" aria-label="<?php esc_attr_e( 'Pass', 'royal-access' ); ?>"></span>
							<?php elseif ( $raccess_check['status'] === 'fair' ) : ?>
								<span class="dashicons dashicons-warning" style="color: #dba617;" aria-label="<?php esc_attr_e( 'Warning', 'royal-access' ); ?>"></span>
							<?php else : ?>
								<span class="dashicons dashicons-dismiss" style="color: #d63638;" aria-label="<?php esc_attr_e( 'Fail', 'royal-access' ); ?>"></span>
							<?php endif; ?>
						</td>
						<td style="padding: 10px 8px;"><?php echo esc_html( $raccess_check['label'] ); ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<!-- Quick Stats -->
		<div class="raccess-card">
			<h2 style="margin-top: 0;">
				<span class="dashicons dashicons-chart-bar" style="color: #3b82f6; margin-right: 6px;"></span>
				<?php esc_html_e( 'Quick Overview', 'royal-access' ); ?>
			</h2>

			<table class="form-table" style="margin-top: 0;">
				<tr>
					<th><?php esc_html_e( 'Toolbar', 'royal-access' ); ?></th>
					<td>
						<?php if ( $raccess_settings['enable_toolbar'] ) : ?>
							<span style="color: #00a32a; font-weight: 500;"><?php esc_html_e( 'Enabled', 'royal-access' ); ?></span>
						<?php else : ?>
							<span style="color: #d63638; font-weight: 500;"><?php esc_html_e( 'Disabled', 'royal-access' ); ?></span>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Position', 'royal-access' ); ?></th>
					<td><?php echo esc_html( ucfirst( $raccess_settings['toolbar_position'] ) ); ?></td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Features Enabled', 'royal-access' ); ?></th>
					<td>
						<?php
						printf(
							/* translators: 1: enabled features, 2: total features */
							esc_html__( '%1$d of %2$d', 'royal-access' ),
							intval( $raccess_enabled_count ),
							14
						);
						?>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Automatic Fixes', 'royal-access' ); ?></th>
					<td>
						<?php
						$raccess_fixes_count = 0;
						if ( $raccess_settings['fix_skip_link'] ) {
							$raccess_fixes_count++;
						}
						if ( $raccess_settings['fix_focus_css'] ) {
							$raccess_fixes_count++;
						}
						if ( $raccess_settings['fix_viewport'] ) {
							$raccess_fixes_count++;
						}
						if ( $raccess_settings['fix_read_more'] ) {
							$raccess_fixes_count++;
						}
						printf(
							/* translators: 1: enabled fixes, 2: total fixes */
							esc_html__( '%1$d of %2$d active', 'royal-access' ),
							intval( $raccess_fixes_count ),
							4
						);
						?>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Version', 'royal-access' ); ?></th>
					<td><?php echo esc_html( RACCESS_VERSION ); ?></td>
				</tr>
			</table>

			<p>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=raccess-settings' ) ); ?>" class="button button-primary">
					<?php esc_html_e( 'Configure Settings', 'royal-access' ); ?>
				</a>
			</p>
		</div>

		<!-- Quick Links -->
		<div class="raccess-card">
			<h2 style="margin-top: 0;">
				<span class="dashicons dashicons-external" style="color: #3b82f6; margin-right: 6px;"></span>
				<?php esc_html_e( 'Accessibility Resources', 'royal-access' ); ?>
			</h2>
			<ul style="margin: 0; list-style: disc; padding-left: 20px;">
				<li style="margin-bottom: 8px;">
					<a href="https://www.w3.org/WAI/WCAG22/quickref/" target="_blank" rel="noopener noreferrer">
						<?php esc_html_e( 'WCAG 2.2 Quick Reference', 'royal-access' ); ?>
					</a>
				</li>
				<li style="margin-bottom: 8px;">
					<a href="https://make.wordpress.org/accessibility/handbook/" target="_blank" rel="noopener noreferrer">
						<?php esc_html_e( 'WordPress Accessibility Handbook', 'royal-access' ); ?>
					</a>
				</li>
				<li style="margin-bottom: 8px;">
					<a href="https://ec.europa.eu/social/main.jsp?catId=1202" target="_blank" rel="noopener noreferrer">
						<?php esc_html_e( 'European Accessibility Act (EAA)', 'royal-access' ); ?>
					</a>
				</li>
				<li style="margin-bottom: 8px;">
					<a href="https://wave.webaim.org/" target="_blank" rel="noopener noreferrer">
						<?php esc_html_e( 'WAVE Accessibility Checker', 'royal-access' ); ?>
					</a>
				</li>
				<li>
					<a href="https://www.a11yproject.com/checklist/" target="_blank" rel="noopener noreferrer">
						<?php esc_html_e( 'A11Y Project Checklist', 'royal-access' ); ?>
					</a>
				</li>
			</ul>
		</div>

	</div>
</div>

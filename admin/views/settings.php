<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$raccess_settings = raccess_get_settings();

// Get pages for statement page selector.
$raccess_pages = get_pages( array( 'post_status' => 'publish' ) );
?>

<div class="wrap">
	<h1><?php esc_html_e( 'Royal Access Settings', 'royal-access' ); ?></h1>

	<div id="raccess-settings-notice" style="display: none;"></div>

	<form id="raccess-settings-form" method="post">

		<div class="raccess-settings-grid">

			<!-- Toolbar Settings Card -->
			<div class="raccess-card">
				<h2 style="margin-top: 0;">
					<span class="dashicons dashicons-admin-settings" style="margin-right: 6px;"></span>
					<?php esc_html_e( 'Toolbar Settings', 'royal-access' ); ?>
				</h2>

				<table class="form-table" style="margin-top: 0;">
					<tr>
						<th><label for="raccess-enable-toolbar"><?php esc_html_e( 'Enable Toolbar', 'royal-access' ); ?></label></th>
						<td>
							<label>
								<input type="checkbox" id="raccess-enable-toolbar" name="enable_toolbar" value="1" <?php checked( $raccess_settings['enable_toolbar'] ); ?>>
								<?php esc_html_e( 'Show accessibility toolbar on the frontend', 'royal-access' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th><label for="raccess-toolbar-position"><?php esc_html_e( 'Position', 'royal-access' ); ?></label></th>
						<td>
							<select id="raccess-toolbar-position" name="toolbar_position">
								<option value="right" <?php selected( $raccess_settings['toolbar_position'], 'right' ); ?>><?php esc_html_e( 'Right', 'royal-access' ); ?></option>
								<option value="left" <?php selected( $raccess_settings['toolbar_position'], 'left' ); ?>><?php esc_html_e( 'Left', 'royal-access' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th><label for="raccess-icon-size"><?php esc_html_e( 'Icon Size', 'royal-access' ); ?></label></th>
						<td>
							<select id="raccess-icon-size" name="toolbar_icon_size">
								<option value="small" <?php selected( $raccess_settings['toolbar_icon_size'], 'small' ); ?>><?php esc_html_e( 'Small', 'royal-access' ); ?></option>
								<option value="medium" <?php selected( $raccess_settings['toolbar_icon_size'], 'medium' ); ?>><?php esc_html_e( 'Medium', 'royal-access' ); ?></option>
								<option value="large" <?php selected( $raccess_settings['toolbar_icon_size'], 'large' ); ?>><?php esc_html_e( 'Large', 'royal-access' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Visibility', 'royal-access' ); ?></th>
						<td>
							<label style="display: block; margin-bottom: 8px;">
								<input type="checkbox" name="hide_on_mobile" value="1" <?php checked( $raccess_settings['hide_on_mobile'] ); ?>>
								<?php esc_html_e( 'Hide on mobile devices', 'royal-access' ); ?>
							</label>
							<label>
								<input type="checkbox" name="hide_for_admins" value="1" <?php checked( $raccess_settings['hide_for_admins'] ); ?>>
								<?php esc_html_e( 'Hide for logged-in administrators', 'royal-access' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th><label for="raccess-bg-color"><?php esc_html_e( 'Background Color', 'royal-access' ); ?></label></th>
						<td>
							<input type="text" id="raccess-bg-color" name="toolbar_bg_color" value="<?php echo esc_attr( $raccess_settings['toolbar_bg_color'] ); ?>" class="raccess-color-picker" data-default-color="#1e293b">
						</td>
					</tr>
					<tr>
						<th><label for="raccess-text-color"><?php esc_html_e( 'Text Color', 'royal-access' ); ?></label></th>
						<td>
							<input type="text" id="raccess-text-color" name="toolbar_text_color" value="<?php echo esc_attr( $raccess_settings['toolbar_text_color'] ); ?>" class="raccess-color-picker" data-default-color="#f1f5f9">
						</td>
					</tr>
					<tr>
						<th><label for="raccess-accent-color"><?php esc_html_e( 'Accent Color', 'royal-access' ); ?></label></th>
						<td>
							<input type="text" id="raccess-accent-color" name="toolbar_accent_color" value="<?php echo esc_attr( $raccess_settings['toolbar_accent_color'] ); ?>" class="raccess-color-picker" data-default-color="#3b82f6">
						</td>
					</tr>
				</table>
			</div>

			<!-- Toolbar Features Card -->
			<div class="raccess-card">
				<h2 style="margin-top: 0;">
					<span class="dashicons dashicons-universal-access-alt" style="margin-right: 6px;"></span>
					<?php esc_html_e( 'Toolbar Features', 'royal-access' ); ?>
				</h2>
				<p class="description" style="margin-bottom: 12px;">
					<?php esc_html_e( 'Toggle which features appear in the frontend toolbar.', 'royal-access' ); ?>
				</p>

				<?php
				$raccess_toolbar_features = array(
					'feature_font_size'      => __( 'Font Size Adjustment', 'royal-access' ),
					'feature_contrast'       => __( 'High Contrast Mode', 'royal-access' ),
					'feature_dark_mode'      => __( 'Dark Mode', 'royal-access' ),
					'feature_dyslexia'       => __( 'Dyslexia-Friendly Font', 'royal-access' ),
					'feature_line_height'    => __( 'Increase Line Height', 'royal-access' ),
					'feature_letter_spacing' => __( 'Increase Letter Spacing', 'royal-access' ),
					'feature_word_spacing'   => __( 'Increase Word Spacing', 'royal-access' ),
					'feature_links'          => __( 'Highlight Links', 'royal-access' ),
					'feature_big_cursor'     => __( 'Big Cursor', 'royal-access' ),
					'feature_animations'     => __( 'Stop Animations', 'royal-access' ),
					'feature_images'         => __( 'Hide Images', 'royal-access' ),
					'feature_monochrome'     => __( 'Monochrome / Greyscale', 'royal-access' ),
					'feature_reading_guide'  => __( 'Reading Guide', 'royal-access' ),
					'feature_focus'          => __( 'Highlight Focus', 'royal-access' ),
				);

				foreach ( $raccess_toolbar_features as $raccess_key => $raccess_label ) : ?>
					<label class="raccess-toggle-row">
						<input type="checkbox" name="<?php echo esc_attr( $raccess_key ); ?>" value="1" <?php checked( $raccess_settings[ $raccess_key ] ); ?>>
						<span><?php echo esc_html( $raccess_label ); ?></span>
					</label>
				<?php endforeach; ?>
			</div>

			<!-- Automatic Fixes Card -->
			<div class="raccess-card">
				<h2 style="margin-top: 0;">
					<span class="dashicons dashicons-admin-tools" style="margin-right: 6px;"></span>
					<?php esc_html_e( 'Automatic Fixes', 'royal-access' ); ?>
				</h2>
				<p class="description" style="margin-bottom: 12px;">
					<?php esc_html_e( 'Code-level fixes applied automatically — no user action needed.', 'royal-access' ); ?>
				</p>

				<label class="raccess-toggle-row">
					<input type="checkbox" name="fix_skip_link" value="1" <?php checked( $raccess_settings['fix_skip_link'] ); ?>>
					<span>
						<?php esc_html_e( 'Skip-to-Content Link', 'royal-access' ); ?>
						<em class="description"><?php esc_html_e( 'Adds a "Skip to content" link for keyboard users.', 'royal-access' ); ?></em>
					</span>
				</label>

				<label class="raccess-toggle-row">
					<input type="checkbox" name="fix_focus_css" value="1" <?php checked( $raccess_settings['fix_focus_css'] ); ?>>
					<span>
						<?php esc_html_e( 'Focus Indicator CSS', 'royal-access' ); ?>
						<em class="description"><?php esc_html_e( 'Adds consistent focus-visible outlines on all interactive elements.', 'royal-access' ); ?></em>
					</span>
				</label>

				<label class="raccess-toggle-row">
					<input type="checkbox" name="fix_viewport" value="1" <?php checked( $raccess_settings['fix_viewport'] ); ?>>
					<span>
						<?php esc_html_e( 'Viewport Zoom Fix', 'royal-access' ); ?>
						<em class="description"><?php esc_html_e( 'Removes user-scalable=no so users can pinch-to-zoom.', 'royal-access' ); ?></em>
					</span>
				</label>

				<label class="raccess-toggle-row">
					<input type="checkbox" name="fix_read_more" value="1" <?php checked( $raccess_settings['fix_read_more'] ); ?>>
					<span>
						<?php esc_html_e( 'Read More Context', 'royal-access' ); ?>
						<em class="description"><?php esc_html_e( 'Adds post title to "Read more" links for screen readers.', 'royal-access' ); ?></em>
					</span>
				</label>
			</div>

			<!-- Accessibility Statement Card -->
			<div class="raccess-card">
				<h2 style="margin-top: 0;">
					<span class="dashicons dashicons-media-document" style="margin-right: 6px;"></span>
					<?php esc_html_e( 'Accessibility Statement', 'royal-access' ); ?>
				</h2>
				<p class="description" style="margin-bottom: 12px;">
					<?php esc_html_e( 'Generate an accessibility statement page for your website.', 'royal-access' ); ?>
				</p>

				<table class="form-table" style="margin-top: 0;">
					<tr>
						<th><label for="raccess-statement-page"><?php esc_html_e( 'Statement Page', 'royal-access' ); ?></label></th>
						<td>
							<select id="raccess-statement-page" name="statement_page">
								<option value="0"><?php esc_html_e( '— Select —', 'royal-access' ); ?></option>
								<?php foreach ( $raccess_pages as $raccess_page ) : ?>
									<option value="<?php echo esc_attr( $raccess_page->ID ); ?>" <?php selected( $raccess_settings['statement_page'], $raccess_page->ID ); ?>>
										<?php echo esc_html( $raccess_page->post_title ); ?>
									</option>
								<?php endforeach; ?>
							</select>
							<p class="description"><?php esc_html_e( 'Choose an existing page to link to as your accessibility statement.', 'royal-access' ); ?></p>
						</td>
					</tr>
				</table>

				<div class="raccess-statement-generator" style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #ddd;">
					<h3 style="margin-top: 0;"><?php esc_html_e( 'Generate Statement', 'royal-access' ); ?></h3>

					<div style="background: #fef9e7; border-left: 4px solid #dba617; padding: 12px 16px; margin-bottom: 16px; border-radius: 0 4px 4px 0;">
						<p style="margin: 0 0 8px 0; font-weight: 600; color: #92400e;">
							<span class="dashicons dashicons-warning" style="font-size: 16px; width: 16px; height: 16px; line-height: 16px; margin-right: 4px; color: #dba617;"></span>
							<?php esc_html_e( 'Important Disclaimer', 'royal-access' ); ?>
						</p>
						<p style="margin: 0; color: #78350f; font-size: 13px; line-height: 1.5;">
							<?php esc_html_e( 'This generates a starting-point template only. It is not a legal document and does not constitute legal advice. You must review, customize, and verify the statement accurately reflects your site\'s actual accessibility efforts before publishing. Using this template as-is does not make your site WCAG, ADA, or EAA compliant. Consult an accessibility professional or legal advisor for compliance guidance.', 'royal-access' ); ?>
						</p>
					</div>

					<table class="form-table" style="margin-top: 0;">
						<tr>
							<th><label for="raccess-stmt-site"><?php esc_html_e( 'Site Name', 'royal-access' ); ?></label></th>
							<td>
								<input type="text" id="raccess-stmt-site" class="regular-text" value="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
							</td>
						</tr>
						<tr>
							<th><label for="raccess-stmt-email"><?php esc_html_e( 'Contact Email', 'royal-access' ); ?></label></th>
							<td>
								<input type="email" id="raccess-stmt-email" class="regular-text" value="<?php echo esc_attr( get_option( 'admin_email' ) ); ?>">
							</td>
						</tr>
						<tr>
							<th><label for="raccess-stmt-standard"><?php esc_html_e( 'Standard', 'royal-access' ); ?></label></th>
							<td>
								<select id="raccess-stmt-standard">
									<option value="wcag22aa"><?php esc_html_e( 'WCAG 2.2 Level AA', 'royal-access' ); ?></option>
									<option value="wcag21aa"><?php esc_html_e( 'WCAG 2.1 Level AA', 'royal-access' ); ?></option>
									<option value="eaa"><?php esc_html_e( 'European Accessibility Act (EAA)', 'royal-access' ); ?></option>
								</select>
							</td>
						</tr>
					</table>

					<p>
						<button type="button" id="raccess-generate-statement" class="button">
							<?php esc_html_e( 'Generate Statement', 'royal-access' ); ?>
						</button>
						<button type="button" id="raccess-copy-statement" class="button" style="display: none;">
							<?php esc_html_e( 'Copy to Clipboard', 'royal-access' ); ?>
						</button>
					</p>

					<div id="raccess-statement-preview" style="display: none;">
						<div id="raccess-statement-output" class="raccess-statement-box"></div>
					</div>
				</div>
			</div>

			<!-- Contrast Checker Card -->
			<div class="raccess-card">
				<h2 style="margin-top: 0;">
					<span class="dashicons dashicons-art" style="margin-right: 6px;"></span>
					<?php esc_html_e( 'Contrast Checker', 'royal-access' ); ?>
				</h2>
				<p class="description" style="margin-bottom: 12px;">
					<?php esc_html_e( 'Check if two colors meet WCAG contrast requirements.', 'royal-access' ); ?>
				</p>

				<table class="form-table" style="margin-top: 0;">
					<tr>
						<th><label for="raccess-contrast-fg"><?php esc_html_e( 'Foreground (text)', 'royal-access' ); ?></label></th>
						<td>
							<input type="text" id="raccess-contrast-fg" class="raccess-color-picker" value="#333333" data-default-color="#333333">
						</td>
					</tr>
					<tr>
						<th><label for="raccess-contrast-bg"><?php esc_html_e( 'Background', 'royal-access' ); ?></label></th>
						<td>
							<input type="text" id="raccess-contrast-bg" class="raccess-color-picker" value="#ffffff" data-default-color="#ffffff">
						</td>
					</tr>
				</table>

				<p>
					<button type="button" id="raccess-check-contrast" class="button">
						<?php esc_html_e( 'Check Contrast', 'royal-access' ); ?>
					</button>
				</p>

				<div id="raccess-contrast-result" style="display: none;">
					<div class="raccess-contrast-ratio">
						<span id="raccess-ratio-value"></span>
						<span class="raccess-contrast-label"><?php esc_html_e( 'contrast ratio', 'royal-access' ); ?></span>
					</div>
					<table class="widefat" style="max-width: 400px; border: none;">
						<tbody>
							<tr>
								<td><?php esc_html_e( 'AA Normal Text', 'royal-access' ); ?></td>
								<td id="raccess-aa-normal" style="width: 60px; text-align: center;"></td>
							</tr>
							<tr>
								<td><?php esc_html_e( 'AA Large Text', 'royal-access' ); ?></td>
								<td id="raccess-aa-large" style="width: 60px; text-align: center;"></td>
							</tr>
							<tr>
								<td><?php esc_html_e( 'AAA Normal Text', 'royal-access' ); ?></td>
								<td id="raccess-aaa-normal" style="width: 60px; text-align: center;"></td>
							</tr>
							<tr>
								<td><?php esc_html_e( 'AAA Large Text', 'royal-access' ); ?></td>
								<td id="raccess-aaa-large" style="width: 60px; text-align: center;"></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

		</div>

		<p class="submit" style="margin-top: 20px;">
			<button type="submit" id="raccess-save-settings" class="button button-primary button-hero">
				<?php esc_html_e( 'Save Settings', 'royal-access' ); ?>
			</button>
		</p>

	</form>
</div>

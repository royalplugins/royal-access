/**
 * Royal Access — Admin JavaScript
 */
(function($) {
	'use strict';

	$(function() {

		// Initialize color pickers.
		$('.raccess-color-picker').wpColorPicker();

		/* ─── Save Settings ─── */
		$('#raccess-settings-form').on('submit', function(e) {
			e.preventDefault();
			saveSettings();
		});

		function saveSettings() {
			var $btn = $('#raccess-save-settings');
			var original = $btn.text();
			$btn.prop('disabled', true).text(raccess_ajax.strings.saving);

			var settings = {};

			// Boolean checkboxes.
			var booleans = [
				'enable_toolbar', 'hide_on_mobile', 'hide_for_admins',
				'feature_font_size', 'feature_contrast', 'feature_dark_mode',
				'feature_dyslexia', 'feature_line_height', 'feature_letter_spacing',
				'feature_word_spacing', 'feature_links', 'feature_big_cursor',
				'feature_animations', 'feature_images', 'feature_monochrome',
				'feature_reading_guide', 'feature_focus',
				'fix_skip_link', 'fix_focus_css', 'fix_viewport', 'fix_read_more'
			];

			for (var i = 0; i < booleans.length; i++) {
				var name = booleans[i];
				settings[name] = $('input[name="' + name + '"]').is(':checked');
			}

			// Select fields.
			settings.toolbar_position  = $('select[name="toolbar_position"]').val();
			settings.toolbar_icon_size = $('select[name="toolbar_icon_size"]').val();

			// Color fields (from wp-color-picker).
			settings.toolbar_bg_color     = $('input[name="toolbar_bg_color"]').wpColorPicker('color');
			settings.toolbar_text_color   = $('input[name="toolbar_text_color"]').wpColorPicker('color');
			settings.toolbar_accent_color = $('input[name="toolbar_accent_color"]').wpColorPicker('color');

			// Statement page.
			settings.statement_page = parseInt($('select[name="statement_page"]').val(), 10) || 0;

			$.ajax({
				url: raccess_ajax.ajax_url,
				type: 'POST',
				data: {
					action: 'raccess_save_settings',
					nonce: raccess_ajax.nonce,
					settings: JSON.stringify(settings)
				},
				success: function(response) {
					if (response.success) {
						showNotice('success', response.data.message);
					} else {
						showNotice('error', response.data.message || raccess_ajax.strings.error);
					}
				},
				error: function() {
					showNotice('error', raccess_ajax.strings.error);
				},
				complete: function() {
					$btn.prop('disabled', false).text(original);
				}
			});
		}

		/* ─── Contrast Checker ─── */
		$('#raccess-check-contrast').on('click', function() {
			var fg = $('#raccess-contrast-fg').wpColorPicker('color');
			var bg = $('#raccess-contrast-bg').wpColorPicker('color');

			if (!fg || !bg) {
				showNotice('error', 'Please select both colors.');
				return;
			}

			$.ajax({
				url: raccess_ajax.ajax_url,
				type: 'POST',
				data: {
					action: 'raccess_check_contrast',
					nonce: raccess_ajax.nonce,
					color1: fg,
					color2: bg
				},
				success: function(response) {
					if (response.success) {
						var d = response.data;
						$('#raccess-ratio-value').text(d.ratio + ':1');
						$('#raccess-aa-normal').html(d.aa_normal ? passHtml() : failHtml());
						$('#raccess-aa-large').html(d.aa_large ? passHtml() : failHtml());
						$('#raccess-aaa-normal').html(d.aaa_normal ? passHtml() : failHtml());
						$('#raccess-aaa-large').html(d.aaa_large ? passHtml() : failHtml());
						$('#raccess-contrast-result').show();
					}
				}
			});
		});

		function passHtml() {
			return '<span class="raccess-pass">Pass</span>';
		}

		function failHtml() {
			return '<span class="raccess-fail">Fail</span>';
		}

		/* ─── Statement Generator ─── */
		$('#raccess-generate-statement').on('click', function() {
			var $btn = $(this);
			$btn.prop('disabled', true);

			$.ajax({
				url: raccess_ajax.ajax_url,
				type: 'POST',
				data: {
					action: 'raccess_generate_statement',
					nonce: raccess_ajax.nonce,
					site_name: $('#raccess-stmt-site').val(),
					email: $('#raccess-stmt-email').val(),
					standard: $('#raccess-stmt-standard').val()
				},
				success: function(response) {
					if (response.success) {
						$('#raccess-statement-output').html(response.data.html);
						$('#raccess-statement-preview').show();
						$('#raccess-copy-statement').show();
					}
				},
				complete: function() {
					$btn.prop('disabled', false);
				}
			});
		});

		$('#raccess-copy-statement').on('click', function() {
			var html = $('#raccess-statement-output').html();
			if (navigator.clipboard) {
				navigator.clipboard.writeText(html).then(function() {
					showNotice('success', raccess_ajax.strings.copied);
				});
			}
		});

		/* ─── Notice helper ─── */
		function showNotice(type, message) {
			var $notice = $('#raccess-settings-notice');
			$notice.attr('class', 'notice notice-' + type + ' is-dismissible')
				.html('<p>' + message + '</p>')
				.show();

			// Auto-hide after 4 seconds.
			setTimeout(function() {
				$notice.fadeOut(300);
			}, 4000);

			// Scroll to top.
			$('html, body').animate({ scrollTop: 0 }, 300);
		}
	});
})(jQuery);

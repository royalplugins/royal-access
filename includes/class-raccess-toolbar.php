<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Frontend accessibility toolbar — rendered in wp_footer.
 */
class RACCESS_Toolbar {

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
		$settings = raccess_get_settings();

		if ( $settings['hide_on_mobile'] ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'maybe_bail_mobile' ), 1 );
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'wp_footer', array( $this, 'render_toolbar' ), 100 );
	}

	/**
	 * Bail on mobile if setting is enabled.
	 */
	public function maybe_bail_mobile() {
		if ( wp_is_mobile() ) {
			remove_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
			remove_action( 'wp_footer', array( $this, 'render_toolbar' ), 100 );
		}
	}

	/**
	 * Enqueue frontend toolbar assets.
	 */
	public function enqueue_assets() {
		$css_file = RACCESS_PLUGIN_DIR . 'public/css/toolbar.css';
		$js_file  = RACCESS_PLUGIN_DIR . 'public/js/toolbar.js';

		$css_version = file_exists( $css_file ) ? filemtime( $css_file ) : RACCESS_VERSION;
		$js_version  = file_exists( $js_file ) ? filemtime( $js_file ) : RACCESS_VERSION;

		wp_enqueue_style( 'raccess-toolbar', RACCESS_PLUGIN_URL . 'public/css/toolbar.css', array(), $css_version );
		wp_enqueue_script( 'raccess-toolbar', RACCESS_PLUGIN_URL . 'public/js/toolbar.js', array(), $js_version, true );

		$settings = raccess_get_settings();

		$features = array();
		$feature_keys = array(
			'font_size', 'contrast', 'dark_mode', 'dyslexia', 'line_height',
			'letter_spacing', 'word_spacing', 'links', 'big_cursor', 'animations',
			'images', 'monochrome', 'reading_guide', 'focus',
		);
		foreach ( $feature_keys as $key ) {
			$features[ $key ] = (bool) $settings[ 'feature_' . $key ];
		}

		$config = array(
			'position' => $settings['toolbar_position'],
			'iconSize' => $settings['toolbar_icon_size'],
			'features' => $features,
			'fontUrl'  => RACCESS_PLUGIN_URL . 'public/fonts/OpenDyslexic-Regular.woff2',
			'i18n'     => array(
				'toolbar'        => __( 'Accessibility Tools', 'royal-access' ),
				'openToolbar'    => __( 'Open accessibility toolbar', 'royal-access' ),
				'closeToolbar'   => __( 'Close accessibility toolbar', 'royal-access' ),
				'fontSize'       => __( 'Font Size', 'royal-access' ),
				'increase'       => __( 'Increase', 'royal-access' ),
				'decrease'       => __( 'Decrease', 'royal-access' ),
				'highContrast'   => __( 'High Contrast', 'royal-access' ),
				'darkMode'       => __( 'Dark Mode', 'royal-access' ),
				'dyslexiaFont'   => __( 'Dyslexia Font', 'royal-access' ),
				'lineHeight'     => __( 'Line Height', 'royal-access' ),
				'letterSpacing'  => __( 'Letter Spacing', 'royal-access' ),
				'wordSpacing'    => __( 'Word Spacing', 'royal-access' ),
				'highlightLinks' => __( 'Highlight Links', 'royal-access' ),
				'bigCursor'      => __( 'Big Cursor', 'royal-access' ),
				'stopAnimations' => __( 'Stop Animations', 'royal-access' ),
				'hideImages'     => __( 'Hide Images', 'royal-access' ),
				'monochrome'     => __( 'Monochrome', 'royal-access' ),
				'readingGuide'   => __( 'Reading Guide', 'royal-access' ),
				'highlightFocus' => __( 'Highlight Focus', 'royal-access' ),
				'resetAll'       => __( 'Reset All', 'royal-access' ),
			),
		);

		wp_add_inline_script(
			'raccess-toolbar',
			'var raccessConfig=' . wp_json_encode( $config ) . ';',
			'before'
		);

		// Dynamic toolbar colors via CSS custom properties.
		$custom_css = sprintf(
			'.raccess-toolbar-panel{--raccess-bg:%s;--raccess-text:%s;--raccess-accent:%s;}',
			esc_attr( $settings['toolbar_bg_color'] ),
			esc_attr( $settings['toolbar_text_color'] ),
			esc_attr( $settings['toolbar_accent_color'] )
		);
		wp_add_inline_style( 'raccess-toolbar', $custom_css );
	}

	/**
	 * Render the floating toolbar HTML in wp_footer.
	 */
	public function render_toolbar() {
		$settings  = raccess_get_settings();
		$position  = sanitize_html_class( $settings['toolbar_position'] );
		$icon_size = sanitize_html_class( $settings['toolbar_icon_size'] );
		?>
		<!-- Royal Access Toolbar -->
		<div id="raccess-toolbar" class="raccess-toolbar raccess-pos-<?php echo esc_attr( $position ); ?> raccess-size-<?php echo esc_attr( $icon_size ); ?>">

			<button type="button" id="raccess-toggle" class="raccess-toolbar-toggle"
				aria-label="<?php esc_attr_e( 'Open accessibility toolbar', 'royal-access' ); ?>"
				aria-expanded="false"
				aria-controls="raccess-panel">
				<svg aria-hidden="true" focusable="false" width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
					<path d="M12 2a2 2 0 1 1 0 4 2 2 0 0 1 0-4zm-2 7h4a1 1 0 0 1 1 1v1h3a1 1 0 1 1 0 2h-3v5.5a1.5 1.5 0 0 1-3 0V13h-2v5.5a1.5 1.5 0 0 1-3 0V13H4a1 1 0 1 1 0-2h3v-1a1 1 0 0 1 1-1h2z"/>
				</svg>
			</button>

			<div id="raccess-panel" class="raccess-toolbar-panel" role="dialog"
				aria-label="<?php esc_attr_e( 'Accessibility Tools', 'royal-access' ); ?>"
				style="display:none;">

				<div class="raccess-panel-header">
					<span class="raccess-panel-title"><?php esc_html_e( 'Accessibility', 'royal-access' ); ?></span>
					<button type="button" class="raccess-panel-close" data-action="close"
						aria-label="<?php esc_attr_e( 'Close accessibility toolbar', 'royal-access' ); ?>">
						<svg aria-hidden="true" focusable="false" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
							<path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
						</svg>
					</button>
				</div>

				<div class="raccess-panel-body">

					<?php if ( $settings['feature_font_size'] ) : ?>
					<div class="raccess-feature raccess-feature-fontsize">
						<span class="raccess-feature-label"><?php esc_html_e( 'Font Size', 'royal-access' ); ?></span>
						<div class="raccess-feature-controls">
							<button type="button" data-action="font-decrease" aria-label="<?php esc_attr_e( 'Decrease font size', 'royal-access' ); ?>">A-</button>
							<button type="button" data-action="font-increase" aria-label="<?php esc_attr_e( 'Increase font size', 'royal-access' ); ?>">A+</button>
						</div>
					</div>
					<?php endif; ?>

					<?php
					$toggles = array(
						'contrast'      => array(
							'key'   => 'feature_contrast',
							'label' => __( 'High Contrast', 'royal-access' ),
							'icon'  => 'contrast',
						),
						'dark_mode'     => array(
							'key'   => 'feature_dark_mode',
							'label' => __( 'Dark Mode', 'royal-access' ),
							'icon'  => 'dark-mode',
						),
						'dyslexia'      => array(
							'key'   => 'feature_dyslexia',
							'label' => __( 'Dyslexia Font', 'royal-access' ),
							'icon'  => 'dyslexia',
						),
						'line_height'   => array(
							'key'   => 'feature_line_height',
							'label' => __( 'Line Height', 'royal-access' ),
							'icon'  => 'line-height',
						),
						'letter_spacing' => array(
							'key'   => 'feature_letter_spacing',
							'label' => __( 'Letter Spacing', 'royal-access' ),
							'icon'  => 'letter-spacing',
						),
						'word_spacing'  => array(
							'key'   => 'feature_word_spacing',
							'label' => __( 'Word Spacing', 'royal-access' ),
							'icon'  => 'word-spacing',
						),
						'links'         => array(
							'key'   => 'feature_links',
							'label' => __( 'Highlight Links', 'royal-access' ),
							'icon'  => 'links',
						),
						'big_cursor'    => array(
							'key'   => 'feature_big_cursor',
							'label' => __( 'Big Cursor', 'royal-access' ),
							'icon'  => 'cursor',
						),
						'animations'    => array(
							'key'   => 'feature_animations',
							'label' => __( 'Stop Animations', 'royal-access' ),
							'icon'  => 'animations',
						),
						'images'        => array(
							'key'   => 'feature_images',
							'label' => __( 'Hide Images', 'royal-access' ),
							'icon'  => 'images',
						),
						'monochrome'    => array(
							'key'   => 'feature_monochrome',
							'label' => __( 'Monochrome', 'royal-access' ),
							'icon'  => 'monochrome',
						),
						'reading_guide' => array(
							'key'   => 'feature_reading_guide',
							'label' => __( 'Reading Guide', 'royal-access' ),
							'icon'  => 'reading-guide',
						),
						'focus'         => array(
							'key'   => 'feature_focus',
							'label' => __( 'Highlight Focus', 'royal-access' ),
							'icon'  => 'focus',
						),
					);

					foreach ( $toggles as $feature => $meta ) :
						if ( ! $settings[ $meta['key'] ] ) {
							continue;
						}
						$action = 'toggle-' . str_replace( '_', '-', $feature );
						?>
						<button type="button" class="raccess-feature raccess-feature-toggle" data-action="<?php echo esc_attr( $action ); ?>" data-feature="<?php echo esc_attr( str_replace( '_', '-', $feature ) ); ?>" aria-pressed="false">
							<span class="raccess-feature-label"><?php echo esc_html( $meta['label'] ); ?></span>
							<span class="raccess-feature-indicator"></span>
						</button>
					<?php endforeach; ?>

				</div>

				<div class="raccess-panel-footer">
					<button type="button" class="raccess-reset-btn" data-action="reset">
						<?php esc_html_e( 'Reset All', 'royal-access' ); ?>
					</button>
				</div>

			</div>
		</div>
		<!-- / Royal Access Toolbar -->

		<div id="raccess-reading-guide" class="raccess-reading-guide" style="display:none;" aria-hidden="true"></div>
		<?php
	}
}

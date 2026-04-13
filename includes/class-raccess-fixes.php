<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Automatic code-level accessibility fixes applied via hooks.
 */
class RACCESS_Fixes {

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

		if ( $settings['fix_skip_link'] ) {
			add_action( 'wp_body_open', array( $this, 'add_skip_link' ), 1 );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_skip_link_css' ) );
		}

		if ( $settings['fix_focus_css'] ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_focus_css' ) );
		}

		if ( $settings['fix_viewport'] ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_viewport_fix' ) );
		}

		if ( $settings['fix_read_more'] ) {
			add_filter( 'excerpt_more', array( $this, 'fix_excerpt_more' ) );
			add_filter( 'the_content_more_link', array( $this, 'fix_content_more_link' ), 10, 2 );
		}
	}

	/**
	 * Add skip-to-content link immediately after <body>.
	 */
	public function add_skip_link() {
		echo '<a class="raccess-skip-link" href="#content">' . esc_html__( 'Skip to content', 'royal-access' ) . '</a>';
	}

	/**
	 * Enqueue skip link CSS so it works even when toolbar is disabled.
	 */
	public function enqueue_skip_link_css() {
		wp_register_style( 'raccess-skip-link', false, array(), RACCESS_VERSION );
		wp_enqueue_style( 'raccess-skip-link' );
		wp_add_inline_style( 'raccess-skip-link',
			'.raccess-skip-link{position:absolute;top:-100%;left:50%;transform:translateX(-50%);background:#1e293b;color:#f1f5f9;padding:12px 24px;border-radius:0 0 8px 8px;font-size:14px;font-weight:600;text-decoration:none;z-index:999999;transition:top .2s ease}'
			. '.raccess-skip-link:focus{top:0;outline:3px solid #3b82f6;outline-offset:2px}'
		);
	}

	/**
	 * Enqueue consistent focus-visible CSS via wp_add_inline_style.
	 */
	public function enqueue_focus_css() {
		wp_register_style( 'raccess-focus', false, array(), RACCESS_VERSION );
		wp_enqueue_style( 'raccess-focus' );
		wp_add_inline_style( 'raccess-focus', '*:focus-visible{outline:2px solid #3b82f6;outline-offset:2px;}' );
	}

	/**
	 * Enqueue inline JS to fix viewport meta tags (remove user-scalable=no and maximum-scale=1).
	 *
	 * Uses DOM manipulation instead of output buffering for safe, scoped execution
	 * that avoids buffer-stack issues in shared WordPress environments.
	 */
	public function enqueue_viewport_fix() {
		wp_register_script( 'raccess-viewport-fix', false, array(), RACCESS_VERSION, false );
		wp_enqueue_script( 'raccess-viewport-fix' );
		wp_add_inline_script(
			'raccess-viewport-fix',
			'document.querySelectorAll("meta[name=viewport]").forEach(function(m){'
			. 'var c=m.getAttribute("content");'
			. 'if(!c)return;'
			. 'c=c.replace(/,?\\s*user-scalable\\s*=\\s*no/gi,"");'
			. 'c=c.replace(/,?\\s*maximum-scale\\s*=\\s*1(\\.0)?/gi,"");'
			. 'c=c.replace(/^[\\s,]+/,"").replace(/[\\s,]+$/,"");'
			. 'if(c){m.setAttribute("content",c)}else{m.remove()}'
			. '});'
		);
	}

	/**
	 * Fix "read more" excerpt link to include post title for screen readers.
	 *
	 * @param string $more_string Default excerpt more string.
	 * @return string
	 */
	public function fix_excerpt_more( $more_string ) {
		$post = get_post();
		if ( ! $post ) {
			return $more_string;
		}

		return sprintf(
			' <a href="%s" class="more-link" aria-label="%s">%s</a>',
			esc_url( get_permalink( $post ) ),
			/* translators: %s: Post title */
			esc_attr( sprintf( __( 'Read more about %s', 'royal-access' ), get_the_title( $post ) ) ),
			esc_html__( 'Read more', 'royal-access' )
		);
	}

	/**
	 * Fix "more" link in content to include post title.
	 *
	 * @param string $link    Default more link HTML.
	 * @param string $more_text More link text (unused).
	 * @return string
	 */
	public function fix_content_more_link( $link, $more_text ) {
		$post = get_post();
		if ( ! $post ) {
			return $link;
		}

		return sprintf(
			'<a href="%s" class="more-link" aria-label="%s">%s</a>',
			esc_url( get_permalink( $post ) . '#more-' . $post->ID ),
			/* translators: %s: Post title */
			esc_attr( sprintf( __( 'Read more about %s', 'royal-access' ), get_the_title( $post ) ) ),
			esc_html( $more_text )
		);
	}
}

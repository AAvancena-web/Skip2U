<?php
/**
 * Assets and the ACF options page.
 *
 * @package skip2u-redesign
 */

defined( 'ABSPATH' ) || exit;

/**
 * Front-end assets.
 *
 * Loaded only on requests the redesign actually renders, so legacy pages
 * are untouched and the stylesheet cannot interfere with them.
 */
function s2u_enqueue_assets() {
	if ( ! s2u_should_render() ) {
		return;
	}

	wp_enqueue_style(
		's2u-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap',
		array(),
		null // phpcs:ignore WordPress.WP.EnqueuedResourceParameters
	);

	$css = S2U_DIR . 'assets/css/s2u.css';
	wp_enqueue_style(
		's2u',
		S2U_URL . 'assets/css/s2u.css',
		array(),
		file_exists( $css ) ? filemtime( $css ) : S2U_VERSION
	);

	$js = S2U_DIR . 'assets/js/s2u.js';
	wp_enqueue_script(
		's2u',
		S2U_URL . 'assets/js/s2u.js',
		array(),
		file_exists( $js ) ? filemtime( $js ) : S2U_VERSION,
		true
	);

	// The child theme's own footer scripts do not run on our templates,
	// because we never load its footer.php. This re-runs the initialisers
	// that page content still depends on.
	$compat = S2U_DIR . 'assets/js/s2u-compat.js';
	if ( file_exists( $compat ) ) {
		wp_enqueue_script(
			's2u-compat',
			S2U_URL . 'assets/js/s2u-compat.js',
			array( 'jquery' ),
			filemtime( $compat ),
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 's2u_enqueue_assets', 100 );

/**
 * Everything global to the site lives on one options page.
 */
function s2u_register_options_page() {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	acf_add_options_page(
		array(
			'page_title' => 'Site Content',
			'menu_title' => 'Site Content',
			'menu_slug'  => 's2u-site-content',
			'capability' => 'edit_theme_options',
			'icon_url'   => 'dashicons-admin-customizer',
			'position'   => 3,
			'redirect'   => false,
			'autoload'   => true,
		)
	);
}
add_action( 'acf/init', 's2u_register_options_page' );

/**
 * Body classes.
 *
 * s2u-active is a marker for targeting, not the CSS scope: the .s2u scope
 * class sits on individual wrappers so the redesign stylesheet can never
 * reach legacy page content.
 *
 * @param array $classes Body classes.
 * @return array
 */
function s2u_body_class( $classes ) {
	if ( s2u_should_render() ) {
		$classes[] = 's2u-active';
	}
	return $classes;
}
add_filter( 'body_class', 's2u_body_class' );

/**
 * Footer menu locations.
 *
 * The theme only registers menu-1 and menu-2, so the two footer columns get
 * their own locations rather than being hardcoded link lists.
 */
function s2u_register_menus() {
	register_nav_menus(
		array(
			's2u_footer_links'    => 'Redesign: Footer quick links',
			's2u_footer_services' => 'Redesign: Footer services',
		)
	);
}
add_action( 'after_setup_theme', 's2u_register_menus' );

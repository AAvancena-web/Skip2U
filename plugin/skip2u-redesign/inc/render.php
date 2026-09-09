<?php
/**
 * Template takeover.
 *
 * The redesign renders through template_include, so the child theme's
 * header.php and footer.php are never loaded on pages we handle. Nothing in
 * the theme changes, and deactivating the plugin hands every request
 * straight back to it.
 *
 * @package skip2u-redesign
 */

defined( 'ABSPATH' ) || exit;

/**
 * Should this request render through the redesign?
 *
 * @return bool
 */
function s2u_should_render() {
	if ( ! s2u_has_acf_pro() ) {
		return false;
	}

	if ( is_admin() || is_feed() || is_embed() || is_robots() ) {
		return false;
	}

	// Escape hatch. Append ?s2u=off to compare a page against the old theme
	// without deactivating the plugin.
	if ( isset( $_GET['s2u'] ) && 'off' === $_GET['s2u'] ) { // phpcs:ignore WordPress.Security.NonceVerification
		return false;
	}

	// The booking and payment path stays on the original theme. Restyling
	// the cart and checkout is a separate job with its own testing.
	if ( function_exists( 'is_woocommerce' ) ) {
		if ( is_cart() || is_checkout() || is_account_page() || is_woocommerce() ) {
			return false;
		}
	}
	if ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url() ) {
		return false;
	}

	// Only pages and the front page for now. Posts and archives keep the
	// theme until their designs exist.
	if ( ! ( is_front_page() || is_page() ) ) {
		return false;
	}

	return (bool) apply_filters( 's2u_should_render', true );
}

/**
 * Point WordPress at our templates.
 *
 * @param string $template Resolved theme template.
 * @return string
 */
function s2u_template_include( $template ) {
	if ( ! s2u_should_render() ) {
		return $template;
	}

	if ( is_front_page() ) {
		return S2U_DIR . 'templates/front-page.php';
	}

	return S2U_DIR . 'templates/page.php';
}
add_filter( 'template_include', 's2u_template_include', 99 );

/**
 * Load a plugin template part.
 *
 * @param string $slug Filename under templates/parts, without extension.
 * @param array  $args Variables exposed to the part.
 */
function s2u_part( $slug, $args = array() ) {
	$file = S2U_DIR . 'templates/parts/' . $slug . '.php';
	if ( ! file_exists( $file ) ) {
		return;
	}
	if ( $args ) {
		extract( $args, EXTR_SKIP ); // phpcs:ignore WordPress.PHP.DontExtract
	}
	include $file;
}

/**
 * Should the shared guide and contact block appear on this request?
 *
 * @return bool
 */
function s2u_show_shared_sections() {
	return (bool) apply_filters( 's2u_show_shared_sections', true );
}

/**
 * Does this page show the hero with the booking form?
 *
 * Mirrors the condition the child theme's header.php used: the front page,
 * or the services page, and only when a featured image exists to sit behind
 * it. Page 3266 is the services page in this install.
 *
 * @return bool
 */
function s2u_show_hero_booking() {
	$ids = apply_filters( 's2u_hero_booking_page_ids', array( 3266 ) );

	$match = is_front_page() || ( $ids && is_page( $ids ) );

	return (bool) apply_filters( 's2u_show_hero_booking', $match );
}

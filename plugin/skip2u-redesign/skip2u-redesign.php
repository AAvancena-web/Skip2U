<?php
/**
 * Plugin Name:       Skip 2 U Tas Redesign
 * Description:       Front-end redesign for Skip 2 U Tas. Ships its own header, footer, homepage and shared pre-footer block, driven by ACF Pro. Deactivate to return the site to the child theme exactly as it was.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Digital Movement
 * Author URI:        https://www.digitalmovement.com.au/
 * License:           GPL-2.0-or-later
 * Text Domain:       skip2u-redesign
 *
 * WHY A PLUGIN
 * The redesign renders through the template_include filter rather than by
 * editing siteorigin-corp-child. Nothing in the theme is touched, so
 * deactivating this plugin restores the previous site immediately.
 *
 * WHAT IT DOES NOT TOUCH
 * The WooCommerce cart, checkout, my-account and order-received screens are
 * deliberately left on the original theme. That is the booking and payment
 * path, and it is not worth the risk of restyling it in the same change.
 * See s2u_should_render() in inc/render.php.
 */

defined( 'ABSPATH' ) || exit;

define( 'S2U_VERSION', '1.0.0' );
define( 'S2U_FILE', __FILE__ );
define( 'S2U_DIR', plugin_dir_path( __FILE__ ) );
define( 'S2U_URL', plugin_dir_url( __FILE__ ) );

/**
 * ACF Pro is a hard dependency: the options page and every field group
 * below rely on it. Fail loudly in the admin rather than white-screening.
 */
function s2u_has_acf_pro() {
	return function_exists( 'acf_add_local_field_group' ) && function_exists( 'acf_add_options_page' );
}

function s2u_dependency_notice() {
	if ( s2u_has_acf_pro() ) {
		return;
	}
	echo '<div class="notice notice-error"><p><strong>Skip 2 U Tas Redesign</strong> needs Advanced Custom Fields <strong>Pro</strong> to be active. The redesign is paused until it is.</p></div>';
}
add_action( 'admin_notices', 's2u_dependency_notice' );

require_once S2U_DIR . 'inc/helpers.php';
require_once S2U_DIR . 'inc/nav-walker.php';
require_once S2U_DIR . 'inc/setup.php';
require_once S2U_DIR . 'inc/acf-fields.php';
require_once S2U_DIR . 'inc/seeder.php';
require_once S2U_DIR . 'inc/render.php';

/**
 * Deactivation leaves all content in place.
 *
 * Field values live in the options table and stay there, so reactivating
 * picks up exactly where it left off and the seeder does not re-run.
 */
register_deactivation_hook(
	__FILE__,
	function () {
		// Nothing to tear down. Content is intentionally preserved.
	}
);

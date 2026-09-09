<?php
/**
 * Document head and masthead.
 *
 * Replaces the child theme's header.php on requests the plugin renders.
 * The Google Tag Manager container and verification tag are carried over
 * verbatim from the theme so analytics keep working.
 *
 * @package skip2u-redesign
 */

defined( 'ABSPATH' ) || exit;

$s2u_phone     = s2u_option( 'phone', '0408 219 527' );
$s2u_phone_uri = 'tel:' . preg_replace( '/[^0-9+]/', '', $s2u_phone );
$s2u_email     = s2u_option( 'email', 'info@skip2utas.com.au' );
$s2u_logo      = s2u_option( 'header_logo' );
$s2u_cta       = s2u_option( 'header_cta', array( 'url' => '/skin-bin-hire-services-hobart/', 'title' => 'Hire Skip Bin' ) );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MFH4RHQL');</script>
<!-- End Google Tag Manager -->
<meta name="google-site-verification" content="87qiCKKiWda1pZt7LjnAJhVsM_567B2tk_ag2GtFx94" />
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}
do_action( 'siteorigin_corp_body_top' );
?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MFH4RHQL"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<div id="page" class="site">
<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'skip2u-redesign' ); ?></a>

<?php do_action( 'siteorigin_corp_header_before' ); ?>

<div class="s2u topbar">
	<div class="container topbar__inner">
		<ul>
			<li>
				<?php echo s2u_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span>Call us:</span><a href="<?php echo esc_url( $s2u_phone_uri ); ?>"><?php echo esc_html( $s2u_phone ); ?></a>
			</li>
			<li>
				<?php echo s2u_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span>Email us:</span><a href="mailto:<?php echo esc_attr( $s2u_email ); ?>"><?php echo esc_html( $s2u_email ); ?></a>
			</li>
			<?php $s2u_servicing = s2u_option( 'servicing_label' ); ?>
			<?php if ( $s2u_servicing ) : ?>
				<li>
					<?php echo s2u_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<span>Servicing:</span><a href="#contact"><?php echo esc_html( $s2u_servicing ); ?></a>
				</li>
			<?php endif; ?>
		</ul>
		<?php $s2u_note = s2u_option( 'topbar_note' ); ?>
		<?php if ( $s2u_note ) : ?>
			<div class="topbar__note">
				<?php echo s2u_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php echo esc_html( $s2u_note ); ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<header id="masthead" class="s2u site-header">
	<div class="container site-header__inner">

		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
			<?php
			if ( $s2u_logo ) {
				s2u_image( $s2u_logo, 'medium', array( 'alt' => get_bloginfo( 'name' ) ) );
			} elseif ( has_custom_logo() ) {
				the_custom_logo();
			} else {
				echo '<strong>' . esc_html( get_bloginfo( 'name' ) ) . '</strong>';
			}
			?>
		</a>

		<nav class="nav" aria-label="<?php esc_attr_e( 'Primary', 'skip2u-redesign' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'menu-1',
					'container'      => false,
					'menu_id'        => 's2u-primary-menu',
					'depth'          => 2,
					'fallback_cb'    => false,
					'walker'         => new S2U_Nav_Walker(),
				)
			);
			?>
		</nav>

		<div class="header-actions">
			<?php s2u_button( $s2u_cta, 'btn btn--primary header-cta' ); ?>

			<a class="phone-circle" href="<?php echo esc_url( $s2u_phone_uri ); ?>" aria-label="<?php echo esc_attr( 'Call us on ' . $s2u_phone ); ?>">
				<?php echo s2u_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</a>

			<button class="hamburger" id="navToggle" type="button" aria-label="<?php esc_attr_e( 'Open menu', 'skip2u-redesign' ); ?>" aria-expanded="false" aria-controls="mobileMenu">
				<span></span><span></span><span></span>
			</button>
		</div>

	</div>
</header>

<div class="s2u mobile-menu" id="mobileMenu">
	<div class="mobile-menu__panel">
		<div class="mobile-menu__top">
			<?php
			if ( $s2u_logo ) {
				s2u_image( $s2u_logo, 'medium', array( 'alt' => get_bloginfo( 'name' ) ) );
			} elseif ( has_custom_logo() ) {
				the_custom_logo();
			}
			?>
			<button class="mobile-close" id="navClose" type="button" aria-label="<?php esc_attr_e( 'Close menu', 'skip2u-redesign' ); ?>">&times;</button>
		</div>

		<nav aria-label="<?php esc_attr_e( 'Mobile', 'skip2u-redesign' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'menu-1',
					'container'      => false,
					'menu_id'        => 's2u-mobile-menu',
					'depth'          => 2,
					'fallback_cb'    => false,
					'walker'         => new S2U_Nav_Walker(),
				)
			);
			?>
		</nav>

		<div class="mobile-menu__cta">
			<?php s2u_button( $s2u_cta, 'btn btn--primary btn--block', false ); ?>
			<a class="btn btn--outline btn--block" href="<?php echo esc_url( $s2u_phone_uri ); ?>">Call <?php echo esc_html( $s2u_phone ); ?></a>
		</div>

		<?php $s2u_hours = s2u_option( 'hours' ); ?>
		<?php if ( $s2u_hours ) : ?>
			<div class="mobile-menu__meta">
				<strong>Opening hours</strong>
				<?php echo wp_kses_post( nl2br( $s2u_hours ) ); ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php do_action( 'siteorigin_corp_content_before' ); ?>
<div id="content" class="site-content">

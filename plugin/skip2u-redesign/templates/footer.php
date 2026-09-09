<?php
/**
 * Footer, plus the floating mobile call button.
 *
 * Replaces the child theme's footer.php on requests the plugin renders.
 *
 * @package skip2u-redesign
 */

defined( 'ABSPATH' ) || exit;

$s2u_phone     = s2u_option( 'phone', '0408 219 527' );
$s2u_phone_uri = 'tel:' . preg_replace( '/[^0-9+]/', '', $s2u_phone );
$s2u_email     = s2u_option( 'email', 'info@skip2utas.com.au' );
$s2u_address   = s2u_option( 'address', 'Southern Tasmania' );
$s2u_hours     = s2u_option( 'hours' );
$s2u_socials   = s2u_option( 'footer_socials', array() );
?>

</div><!-- #content -->

<?php
do_action( 'siteorigin_corp_footer_before' );

if ( s2u_show_shared_sections() ) {
	s2u_part( 'shared-sections' );
}
?>

<footer id="colophon" class="s2u s2u-footer">
	<div class="container">
		<div class="footer-grid">

			<div class="footer-brand">
				<?php
				$s2u_footer_logo = s2u_option( 'footer_logo' );
				if ( $s2u_footer_logo ) {
					s2u_image( $s2u_footer_logo, 'medium', array( 'alt' => get_bloginfo( 'name' ) ) );
				}
				?>
				<?php $s2u_about = s2u_option( 'footer_about' ); ?>
				<?php if ( $s2u_about ) : ?>
					<p><?php echo esc_html( $s2u_about ); ?></p>
				<?php endif; ?>

				<?php if ( $s2u_socials ) : ?>
					<div class="socials">
						<?php foreach ( $s2u_socials as $s2u_social ) : ?>
							<?php
							$net = isset( $s2u_social['social_network'] ) ? $s2u_social['social_network'] : 'facebook';
							$url = isset( $s2u_social['social_url'] ) ? $s2u_social['social_url'] : '';
							if ( ! $url ) {
								continue;
							}
							$href = ( 'mail' === $net && ! preg_match( '/^(https?:|mailto:)/', $url ) ) ? 'mailto:' . $url : $url;
							?>
							<a href="<?php echo esc_url( $href, array( 'http', 'https', 'mailto', 'tel' ) ); ?>" aria-label="<?php echo esc_attr( ucfirst( $net ) ); ?>"<?php echo ( 'facebook' === $net ) ? ' target="_blank" rel="noopener"' : ''; ?>>
								<?php echo s2u_icon( $net ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<div>
				<h4><?php echo esc_html( s2u_option( 'footer_links_title', 'Quick Links' ) ); ?></h4>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 's2u_footer_links',
						'container'      => false,
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
				?>
			</div>

			<div>
				<h4><?php echo esc_html( s2u_option( 'footer_services_title', 'Our Services' ) ); ?></h4>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 's2u_footer_services',
						'container'      => false,
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
				?>
			</div>

			<div>
				<h4><?php echo esc_html( s2u_option( 'footer_contact_title', 'Contact Info' ) ); ?></h4>
				<ul class="footer-contact">
					<?php if ( $s2u_address ) : ?>
						<li>
							<?php echo s2u_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<span><?php echo esc_html( $s2u_address ); ?></span>
						</li>
					<?php endif; ?>
					<li>
						<?php echo s2u_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<a href="<?php echo esc_url( $s2u_phone_uri ); ?>"><?php echo esc_html( $s2u_phone ); ?></a>
					</li>
					<li>
						<?php echo s2u_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<a href="mailto:<?php echo esc_attr( $s2u_email ); ?>"><?php echo esc_html( $s2u_email ); ?></a>
					</li>
					<?php if ( $s2u_hours ) : ?>
						<li>
							<?php echo s2u_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<span><?php echo wp_kses_post( nl2br( $s2u_hours ) ); ?></span>
						</li>
					<?php endif; ?>
				</ul>
			</div>

		</div>

		<div class="footer-bottom">
			<span><?php echo esc_html( s2u_option( 'copyright', 'Copyright © ' . gmdate( 'Y' ) . ' Skip 2 U Tas. All Rights Reserved.' ) ); ?></span>
			<?php $s2u_designer = s2u_option( 'designer_name' ); ?>
			<?php if ( $s2u_designer ) : ?>
				<span>
					Designed by
					<a href="<?php echo esc_url( s2u_option( 'designer_url', '#' ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $s2u_designer ); ?></a>
				</span>
			<?php endif; ?>
		</div>
	</div>

	<?php do_action( 'siteorigin_corp_footer_bottom' ); ?>
</footer>

</div><!-- #page -->

<a class="s2u floating-call" href="<?php echo esc_url( $s2u_phone_uri ); ?>">
	<?php echo s2u_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	<?php echo esc_html( s2u_option( 'floating_call_label', 'Call Now' ) ); ?>
</a>

<?php
wp_footer();
do_action( 'siteorigin_corp_footer_after' );
?>
</body>
</html>

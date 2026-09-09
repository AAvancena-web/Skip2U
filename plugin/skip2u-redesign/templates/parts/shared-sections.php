<?php
/**
 * Shared pre-footer block: the booking guide, the questions, and contact.
 *
 * Rendered on every page the plugin handles, from the options page, so the
 * copy is written once and appears site wide.
 *
 * @package skip2u-redesign
 */

defined( 'ABSPATH' ) || exit;

$guide_cards = s2u_option( 'guide_cards', array() );
$qa_items    = s2u_option( 'qa_items', array() );
$guide_intro = s2u_option( 'guide_intro' );
$closing     = s2u_option( 'closing_content' );
$map_url     = s2u_option( 'map_embed_url', 'https://www.google.com/maps?q=Southern%20Tasmania%2C%20Australia&z=9&output=embed' );
$phone       = s2u_option( 'phone', '0408 219 527' );
$phone_uri   = 'tel:' . preg_replace( '/[^0-9+]/', '', $phone );
$email       = s2u_option( 'email', 'info@skip2utas.com.au' );
$hours       = s2u_option( 'hours' );
?>

<?php if ( $guide_intro || $guide_cards || $qa_items ) : ?>
<section class="s2u section" id="skip-bin-guide">
	<div class="container">

		<?php if ( $guide_intro || s2u_option( 'guide_heading' ) ) : ?>
			<div class="section-head guide__intro">
				<?php if ( s2u_option( 'guide_eyebrow' ) ) : ?>
					<span class="eyebrow"><?php echo esc_html( s2u_option( 'guide_eyebrow' ) ); ?></span>
				<?php endif; ?>
				<?php if ( s2u_option( 'guide_heading' ) ) : ?>
					<h2><?php echo esc_html( s2u_option( 'guide_heading' ) ); ?></h2>
				<?php endif; ?>
				<?php echo wp_kses_post( $guide_intro ); ?>
			</div>
		<?php endif; ?>

		<?php if ( $guide_cards ) : ?>
			<div class="guide-grid">
				<?php foreach ( $guide_cards as $card ) : ?>
					<article class="guide-card<?php echo ! empty( $card['guide_card_wide'] ) ? ' guide-card--wide' : ''; ?>">
						<h3>
							<span class="ico"><?php echo s2u_icon( isset( $card['guide_card_icon'] ) ? $card['guide_card_icon'] : 'bin' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
							<?php echo esc_html( isset( $card['guide_card_title'] ) ? $card['guide_card_title'] : '' ); ?>
						</h3>
						<?php echo wp_kses_post( isset( $card['guide_card_content'] ) ? $card['guide_card_content'] : '' ); ?>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( $qa_items ) : ?>
			<div class="section-head" style="margin-top:clamp(52px,6vw,86px)">
				<?php if ( s2u_option( 'qa_eyebrow' ) ) : ?>
					<span class="eyebrow"><?php echo esc_html( s2u_option( 'qa_eyebrow' ) ); ?></span>
				<?php endif; ?>
				<?php if ( s2u_option( 'qa_heading' ) ) : ?>
					<h2><?php echo esc_html( s2u_option( 'qa_heading' ) ); ?></h2>
				<?php endif; ?>
				<?php if ( s2u_option( 'qa_intro' ) ) : ?>
					<p><?php echo esc_html( s2u_option( 'qa_intro' ) ); ?></p>
				<?php endif; ?>
			</div>

			<div class="qa-list">
				<?php foreach ( $qa_items as $qa ) : ?>
					<article class="qa">
						<h3><span class="q">Q:</span> <?php echo esc_html( isset( $qa['qa_question'] ) ? $qa['qa_question'] : '' ); ?></h3>
						<?php echo wp_kses_post( isset( $qa['qa_answer'] ) ? $qa['qa_answer'] : '' ); ?>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( $closing || s2u_option( 'closing_heading' ) ) : ?>
			<div class="guide-closing">
				<?php if ( s2u_option( 'closing_heading' ) ) : ?>
					<h3><?php echo esc_html( s2u_option( 'closing_heading' ) ); ?></h3>
				<?php endif; ?>
				<?php echo wp_kses_post( $closing ); ?>
				<?php s2u_button( s2u_option( 'closing_cta' ), 'btn btn--primary btn--lg' ); ?>
			</div>
		<?php endif; ?>

	</div>
</section>
<?php endif; ?>

<section class="s2u section section--soft" id="contact">
	<div class="container">

		<?php
		s2u_section_head(
			s2u_option( 'contact_eyebrow', 'Get in touch' ),
			s2u_option( 'contact_heading', 'Talk to Your Local Skip Bin Team' ),
			s2u_option( 'contact_intro' )
		);
		?>

		<div class="contact-grid">

			<div class="contact-info">

				<div class="info-card">
					<span class="ico"><?php echo s2u_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<div>
						<h4>Call us</h4>
						<a href="<?php echo esc_url( $phone_uri ); ?>"><?php echo esc_html( $phone ); ?></a>
					</div>
				</div>

				<div class="info-card">
					<span class="ico"><?php echo s2u_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<div>
						<h4>Email us</h4>
						<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
					</div>
				</div>

				<div class="info-card">
					<span class="ico"><?php echo s2u_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<div>
						<h4>Where we are</h4>
						<p><?php echo esc_html( s2u_option( 'address', 'Southern Tasmania' ) ); ?></p>
						<?php if ( s2u_option( 'address_note' ) ) : ?>
							<p class="small"><?php echo esc_html( s2u_option( 'address_note' ) ); ?></p>
						<?php endif; ?>
					</div>
				</div>

				<?php if ( $hours ) : ?>
					<div class="info-card">
						<span class="ico"><?php echo s2u_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<div>
							<h4>Opening hours</h4>
							<p class="small"><?php echo wp_kses_post( nl2br( $hours ) ); ?></p>
						</div>
					</div>
				<?php endif; ?>

				<?php s2u_button( s2u_option( 'contact_cta' ), 'btn btn--primary' ); ?>

			</div>

			<div class="map-embed">
				<iframe
					title="<?php esc_attr_e( 'Service area map', 'skip2u-redesign' ); ?>"
					src="<?php echo esc_url( $map_url ); ?>"
					loading="lazy"
					referrerpolicy="no-referrer-when-downgrade"
					allowfullscreen></iframe>
			</div>

		</div>
	</div>
</section>

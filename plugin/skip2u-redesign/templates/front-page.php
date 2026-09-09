<?php
/**
 * Homepage.
 *
 * @package skip2u-redesign
 */

defined( 'ABSPATH' ) || exit;

require S2U_DIR . 'templates/header.php';

$hero_image = s2u_field( 'hero_image' );
if ( ! $hero_image && has_post_thumbnail() ) {
	$hero_image = array( 'ID' => get_post_thumbnail_id() );
}
$hero_points = s2u_field( 'hero_points', array() );
?>

<section class="s2u hero">
	<?php if ( $hero_image ) : ?>
		<div class="hero__media">
			<?php s2u_image( $hero_image, 'full', array( 'alt' => '', 'fetchpriority' => 'high' ) ); ?>
		</div>
	<?php endif; ?>

	<div class="container hero__inner">
		<div class="hero__content">
			<?php if ( s2u_field( 'hero_badge' ) ) : ?>
				<span class="hero__badge"><span class="dot"></span> <?php echo esc_html( s2u_field( 'hero_badge' ) ); ?></span>
			<?php endif; ?>

			<h1>
				<?php echo esc_html( s2u_field( 'hero_title', get_the_title() ) ); ?>
				<?php if ( s2u_field( 'hero_title_accent' ) ) : ?>
					<span class="accent"><?php echo esc_html( s2u_field( 'hero_title_accent' ) ); ?></span>
				<?php endif; ?>
			</h1>

			<?php if ( s2u_field( 'hero_lead' ) ) : ?>
				<p class="hero__lead"><?php echo esc_html( s2u_field( 'hero_lead' ) ); ?></p>
			<?php endif; ?>

			<div class="hero__cta">
				<?php s2u_button( s2u_field( 'hero_cta_primary' ), 'btn btn--primary btn--lg' ); ?>
				<?php s2u_button( s2u_field( 'hero_cta_secondary' ), 'btn btn--ghost btn--lg', false ); ?>
			</div>

			<?php if ( $hero_points ) : ?>
				<ul class="hero__points">
					<?php foreach ( $hero_points as $point ) : ?>
						<li>
							<?php echo s2u_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<?php echo esc_html( isset( $point['hero_point_text'] ) ? $point['hero_point_text'] : '' ); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php if ( s2u_show_hero_booking() ) : ?>
	<section class="s2u booking" id="book">
		<div class="container">
			<div class="booking__card">
				<?php
				// The bespoke booking form from the child theme, unchanged.
				echo do_shortcode( '[bin_form]' );
				?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php $trust = s2u_field( 'trust_items', array() ); ?>
<?php if ( $trust ) : ?>
	<section class="s2u trust">
		<div class="container">
			<div class="trust__grid">
				<?php foreach ( $trust as $item ) : ?>
					<div class="trust__item">
						<div class="trust__icon"><?php echo s2u_icon( isset( $item['trust_icon'] ) ? $item['trust_icon'] : 'shield' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
						<div>
							<strong><?php echo esc_html( isset( $item['trust_title'] ) ? $item['trust_title'] : '' ); ?></strong>
							<span><?php echo esc_html( isset( $item['trust_text'] ) ? $item['trust_text'] : '' ); ?></span>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php $bins = s2u_field( 'bin_items', array() ); ?>
<?php if ( $bins ) : ?>
	<section class="s2u section" id="bin-sizes">
		<div class="container">
			<?php s2u_section_head( s2u_field( 'bins_eyebrow' ), s2u_field( 'bins_heading' ), s2u_field( 'bins_intro' ) ); ?>
			<div class="bins">
				<?php foreach ( $bins as $bin ) : ?>
					<article class="bin-card">
						<div class="bin-card__media">
							<?php if ( ! empty( $bin['bin_tag'] ) ) : ?>
								<span class="bin-card__tag"><?php echo esc_html( $bin['bin_tag'] ); ?></span>
							<?php endif; ?>
							<?php s2u_image( isset( $bin['bin_image'] ) ? $bin['bin_image'] : null, 'medium_large', array( 'loading' => 'lazy' ) ); ?>
						</div>
						<div class="bin-card__body">
							<h3><?php echo wp_kses_post( isset( $bin['bin_title'] ) ? $bin['bin_title'] : '' ); ?></h3>
							<?php if ( ! empty( $bin['bin_sub'] ) ) : ?>
								<div class="sub"><?php echo esc_html( $bin['bin_sub'] ); ?></div>
							<?php endif; ?>
							<p><?php echo esc_html( isset( $bin['bin_text'] ) ? $bin['bin_text'] : '' ); ?></p>
							<?php s2u_button( isset( $bin['bin_cta'] ) ? $bin['bin_cta'] : null, 'btn btn--blue', false ); ?>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php $services = s2u_field( 'service_items', array() ); ?>
<?php if ( $services ) : ?>
	<section class="s2u section section--soft" id="services">
		<div class="container">
			<?php s2u_section_head( s2u_field( 'services_eyebrow' ), s2u_field( 'services_heading' ), s2u_field( 'services_intro' ) ); ?>
			<div class="services">
				<?php foreach ( $services as $service ) : ?>
					<article class="service">
						<div class="service__icon"><?php echo s2u_icon( isset( $service['service_icon'] ) ? $service['service_icon'] : 'bin' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
						<h3><?php echo esc_html( isset( $service['service_title'] ) ? $service['service_title'] : '' ); ?></h3>
						<p><?php echo esc_html( isset( $service['service_text'] ) ? $service['service_text'] : '' ); ?></p>
						<?php s2u_button( isset( $service['service_cta'] ) ? $service['service_cta'] : null, 'link' ); ?>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php $why = s2u_field( 'why_features', array() ); ?>
<?php if ( $why || s2u_field( 'why_heading' ) ) : ?>
	<section class="s2u section">
		<div class="container">
			<div class="split">
				<div class="split__media"><?php s2u_image( s2u_field( 'why_image' ), 'large', array( 'loading' => 'lazy' ) ); ?></div>
				<div class="split__body">
					<?php if ( s2u_field( 'why_eyebrow' ) ) : ?>
						<span class="eyebrow eyebrow--blue"><?php echo esc_html( s2u_field( 'why_eyebrow' ) ); ?></span>
					<?php endif; ?>
					<h2><?php echo esc_html( s2u_field( 'why_heading' ) ); ?></h2>
					<?php if ( s2u_field( 'why_intro' ) ) : ?>
						<p><?php echo esc_html( s2u_field( 'why_intro' ) ); ?></p>
					<?php endif; ?>

					<?php if ( $why ) : ?>
						<ul class="feature-list">
							<?php foreach ( $why as $feature ) : ?>
								<li>
									<span class="ico"><?php echo s2u_icon( isset( $feature['why_icon'] ) ? $feature['why_icon'] : 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
									<div>
										<strong><?php echo esc_html( isset( $feature['why_title'] ) ? $feature['why_title'] : '' ); ?></strong>
										<p><?php echo esc_html( isset( $feature['why_text'] ) ? $feature['why_text'] : '' ); ?></p>
									</div>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<?php s2u_button( s2u_field( 'why_cta' ), 'btn btn--primary btn--lg' ); ?>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php if ( s2u_field( 'area_heading' ) ) : ?>
	<section class="s2u section section--soft" id="service-area">
		<div class="container">
			<div class="split split--reverse">
				<div class="split__media"><?php s2u_image( s2u_field( 'area_image' ), 'large', array( 'loading' => 'lazy' ) ); ?></div>
				<div class="split__body">
					<?php if ( s2u_field( 'area_eyebrow' ) ) : ?>
						<span class="eyebrow eyebrow--blue"><?php echo esc_html( s2u_field( 'area_eyebrow' ) ); ?></span>
					<?php endif; ?>
					<h2><?php echo esc_html( s2u_field( 'area_heading' ) ); ?></h2>
					<?php echo wp_kses_post( s2u_field( 'area_content' ) ); ?>
					<?php s2u_button( s2u_field( 'area_cta' ), 'btn btn--primary btn--lg' ); ?>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php $steps = s2u_field( 'step_items', array() ); ?>
<?php if ( $steps ) : ?>
	<section class="s2u section section--tint">
		<div class="container">
			<?php s2u_section_head( s2u_field( 'steps_eyebrow' ), s2u_field( 'steps_heading' ), s2u_field( 'steps_intro' ) ); ?>
			<div class="steps">
				<?php foreach ( $steps as $i => $step ) : ?>
					<div class="step">
						<div class="step__num"><?php echo (int) ( $i + 1 ); ?></div>
						<h3><?php echo esc_html( isset( $step['step_title'] ) ? $step['step_title'] : '' ); ?></h3>
						<p><?php echo esc_html( isset( $step['step_text'] ) ? $step['step_text'] : '' ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php $gallery = s2u_field( 'gallery_images', array() ); ?>
<?php if ( $gallery ) : ?>
	<section class="s2u section">
		<div class="container">
			<?php s2u_section_head( s2u_field( 'gallery_eyebrow' ), s2u_field( 'gallery_heading' ), s2u_field( 'gallery_intro' ) ); ?>
			<div class="gallery">
				<?php foreach ( array_slice( $gallery, 0, 5 ) as $i => $image ) : ?>
					<figure class="g<?php echo (int) ( $i + 1 ); ?>">
						<?php s2u_image( $image, 'large', array( 'loading' => 'lazy' ) ); ?>
					</figure>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php $reviews = s2u_field( 'review_items', array() ); ?>
<?php if ( $reviews ) : ?>
	<section class="s2u section reviews-band">
		<div class="container">
			<?php s2u_section_head( s2u_field( 'reviews_eyebrow' ), s2u_field( 'reviews_heading' ), s2u_field( 'reviews_intro' ) ); ?>
			<div class="reviews">
				<?php foreach ( $reviews as $review ) : ?>
					<?php $name = isset( $review['review_name'] ) ? $review['review_name'] : ''; ?>
					<article class="review">
						<div class="stars" aria-label="<?php esc_attr_e( '5 out of 5 stars', 'skip2u-redesign' ); ?>">
							<?php for ( $s = 0; $s < 5; $s++ ) {
								echo s2u_icon( 'star' ); // phpcs:ignore WordPress.Security.EscapeOutput
							} ?>
						</div>
						<p><?php echo esc_html( isset( $review['review_quote'] ) ? $review['review_quote'] : '' ); ?></p>
						<div class="who">
							<span class="avatar"><?php echo esc_html( strtoupper( mb_substr( $name, 0, 1 ) ) ); ?></span>
							<div>
								<strong><?php echo esc_html( $name ); ?></strong>
								<span><?php echo esc_html( isset( $review['review_source'] ) ? $review['review_source'] : '' ); ?></span>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php $logos = s2u_field( 'partner_logos', array() ); ?>
<?php if ( $logos ) : ?>
	<section class="s2u section">
		<div class="container">
			<?php s2u_section_head( s2u_field( 'partners_eyebrow' ), s2u_field( 'partners_heading' ) ); ?>
			<div class="logos">
				<?php foreach ( $logos as $logo ) : ?>
					<figure><?php s2u_image( $logo, 'medium', array( 'loading' => 'lazy' ) ); ?></figure>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php if ( s2u_field( 'cta_heading' ) ) : ?>
	<section class="s2u cta-band">
		<?php if ( s2u_field( 'cta_image' ) ) : ?>
			<div class="cta-band__media"><?php s2u_image( s2u_field( 'cta_image' ), 'large', array( 'alt' => '', 'loading' => 'lazy' ) ); ?></div>
		<?php endif; ?>
		<div class="container cta-band__inner">
			<div>
				<?php if ( s2u_field( 'cta_eyebrow' ) ) : ?>
					<span class="eyebrow"><?php echo esc_html( s2u_field( 'cta_eyebrow' ) ); ?></span>
				<?php endif; ?>
				<h2><?php echo esc_html( s2u_field( 'cta_heading' ) ); ?></h2>
				<?php if ( s2u_field( 'cta_text' ) ) : ?>
					<p><?php echo esc_html( s2u_field( 'cta_text' ) ); ?></p>
				<?php endif; ?>
			</div>
			<div class="cta-band__actions">
				<?php s2u_button( s2u_field( 'cta_primary' ), 'btn btn--primary btn--lg' ); ?>
				<?php s2u_button( s2u_field( 'cta_secondary' ), 'btn btn--ghost btn--lg', false ); ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php
require S2U_DIR . 'templates/footer.php';

<?php
/**
 * Inner pages.
 *
 * Renders the new header and footer around the page's existing content, so
 * WPBakery layouts keep working while the chrome is the redesign.
 *
 * @package skip2u-redesign
 */

defined( 'ABSPATH' ) || exit;

require S2U_DIR . 'templates/header.php';

while ( have_posts() ) :
	the_post();
	?>

	<?php if ( has_post_thumbnail() ) : ?>
		<section class="s2u page-hero">
			<div class="page-hero__media"><?php the_post_thumbnail( 'full', array( 'alt' => '' ) ); ?></div>
			<div class="container page-hero__inner">
				<h1><?php the_title(); ?></h1>
				<?php if ( s2u_field( 'banner_content' ) ) : ?>
					<p><?php echo esc_html( s2u_field( 'banner_content' ) ); ?></p>
				<?php endif; ?>
			</div>
		</section>
	<?php else : ?>
		<section class="s2u section section--soft">
			<div class="container">
				<div class="section-head"><h1><?php the_title(); ?></h1></div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( s2u_show_hero_booking() ) : ?>
		<section class="s2u booking" id="book">
			<div class="container">
				<div class="booking__card"><?php echo do_shortcode( '[bin_form]' ); ?></div>
			</div>
		</section>
	<?php endif; ?>

	<div class="s2u-page-content">
		<div class="corp-container">
			<?php
			the_content();
			wp_link_pages();
			?>
		</div>
	</div>

	<?php
endwhile;

require S2U_DIR . 'templates/footer.php';

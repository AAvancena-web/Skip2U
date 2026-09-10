<?php
/**
 * One-time content seeder.
 *
 * Writes every field once so nobody has to retype the redesign copy. It is
 * idempotent: an option records the version it last seeded, and it refuses to
 * run again unless forced.
 *
 * Run it either way:
 *   wp s2u seed              (add --force to re-run, --dry-run to preview)
 *   admin.php?page=s2u-seed   (Site Content > Seed content; nonce and capability checked)
 *
 * @package skip2u-redesign
 */

defined( 'ABSPATH' ) || exit;

const S2U_SEED_OPTION  = 's2u_seed_version';
const S2U_SEED_VERSION = '1.0.0';

/**
 * Resolve a media library attachment from a URL.
 *
 * These images are already in the library, so look the ID up rather than
 * downloading a duplicate. Sideloading is only the fallback.
 *
 * @param string $url Image URL.
 * @return int Attachment ID, or 0.
 */
function s2u_attachment_from_url( $url ) {
	if ( ! $url ) {
		return 0;
	}

	$id = attachment_url_to_postid( $url );
	if ( $id ) {
		return (int) $id;
	}

	// Try without the resized suffix, eg -1024x768.
	$stripped = preg_replace( '/-\d+x\d+(\.[a-z]{3,4})$/i', '$1', $url );
	if ( $stripped !== $url ) {
		$id = attachment_url_to_postid( $stripped );
		if ( $id ) {
			return (int) $id;
		}
	}

	if ( ! function_exists( 'media_sideload_image' ) ) {
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
	}

	$id = media_sideload_image( $url, 0, null, 'id' );

	return is_wp_error( $id ) ? 0 : (int) $id;
}

/**
 * The content the seeder writes.
 *
 * @return array {
 *     @type array $options Field name => value, for the options page.
 *     @type array $home    Field name => value, for the front page.
 * }
 */
function s2u_seed_content() {
	$up   = 'https://skip2utas.com.au/wp-content/uploads/';
	$hire = array( 'url' => '/skin-bin-hire-services-hobart/', 'title' => 'Hire Skip Bin', 'target' => '' );

	$options = array(
		/* Header */
		'phone'           => '0408 219 527',
		'email'           => 'info@skip2utas.com.au',
		'servicing_label' => 'Greater Hobart and Southern Tasmania',
		'topbar_note'     => 'Delivery available 24/7',
		'header_logo'     => s2u_attachment_from_url( $up . '2024/05/logo.png' ),
		'header_cta'      => $hire,

		/* Shared: guide */
		'guide_eyebrow'   => 'Your complete guide',
		'guide_heading'   => 'Everything You Need to Know Before You Book a Skip Bin',
		'guide_intro'     => '<p>When you\'re planning a renovation, a big clean out, or a construction project, choosing the right skip bin hire service can save you time, money, and a fair bit of stress. Whether you\'re clearing out a garage, renovating a kitchen, tidying up the garden, or managing waste on a commercial site, understanding how skip bin hire works will help you get the right bin, at the right size, delivered exactly when you need it and collected without any hassle once the job is done. That\'s where we come in. At Skip 2 U Tas, we match you with the right bin for the job and take care of the rest.</p>',
		'guide_cards'     => array(
			array(
				'guide_card_icon'    => 'chart',
				'guide_card_title'   => 'Choosing the Right Bin Size',
				'guide_card_wide'    => 0,
				'guide_card_content' => '<p>We offer skip bins in a range of sizes, from small 2 cubic metre bins suited to a garage clean out or a small bathroom renovation, up to larger 6 to 9 cubic metre bins for major renovations, landscaping projects, or commercial waste removal. As a general rule, it\'s better to slightly overestimate how much waste you\'ll generate. Ordering a bin that\'s too small often means paying for a second bin, while a bin that\'s too large simply gives you room to spare. If you\'re not sure what size suits your project, give our team a call. It\'s usually the fastest way to get it right.</p>',
			),
			array(
				'guide_card_icon'    => 'warning',
				'guide_card_title'   => 'What Can and Can\'t Go in a Skip Bin',
				'guide_card_wide'    => 0,
				'guide_card_content' => '<p>Most general waste, including household rubbish, garden waste, timber, furniture, and construction debris, can go straight into a skip bin. Certain materials are restricted for safety and environmental reasons, though. Items like asbestos, chemicals, paints, batteries, tyres, and other hazardous materials usually require separate, specialised disposal. It\'s always worth checking with us before you start filling the bin, since placing prohibited items in a general waste skip can result in additional fees or delays in collection.</p>',
			),
			array(
				'guide_card_icon'    => 'truck',
				'guide_card_title'   => 'Booking and Delivery',
				'guide_card_wide'    => 0,
				'guide_card_content' => '<p>We keep booking straightforward, whether that\'s online or over the phone. You can book your skip bin hire in under a minute using our simple online booking system, or by giving us a call. We only need your suburb location, the bin size you require, and your preferred delivery time. On the day, we\'ll give you a courtesy call ahead of delivery so you can prepare a clear, accessible space for the bin, whether that\'s a driveway, a section of your yard, or a spot at the front of a work site.</p><p>Delivery is available 24/7. Once you\'re finished, a simple phone call or online request is all that\'s needed to arrange pick up, and we\'ll collect it within 24 hours. We can even provide you with a replacement skip while we empty the old one.</p>',
			),
			array(
				'guide_card_icon'    => 'doc',
				'guide_card_title'   => 'Permits and Placement',
				'guide_card_wide'    => 0,
				'guide_card_content' => '<p>If you\'re planning to place a skip bin on a public road or footpath rather than on your own property, you may need a permit from your local council. Rules vary between councils, so it\'s worth checking with your local authority before delivery day if there\'s any chance the bin will need to sit on the street. Placing a bin on your own driveway or property generally doesn\'t require a permit, though it\'s always good practice to confirm site access is clear for our delivery truck.</p>',
			),
			array(
				'guide_card_icon'    => 'recycle',
				'guide_card_title'   => 'Responsible Waste Disposal',
				'guide_card_wide'    => 0,
				'guide_card_content' => '<p>A good skip bin provider does more than just drop off and collect a bin. We handle your waste responsibly, sorting recyclable materials where possible and disposing of the rest according to local council standards. We want to be as eco-friendly and ethical as possible, so choosing us for your rubbish removal means your job is not just convenient, but also better for the environment.</p>',
			),
			array(
				'guide_card_icon'    => 'clock',
				'guide_card_title'   => 'Getting the Most Out of Your Hire Period',
				'guide_card_wide'    => 0,
				'guide_card_content' => '<p>Most of our skip bin hire periods run for around a week, which gives you enough time to work through a renovation or clean out at a reasonable pace without feeling rushed. It helps to plan your loading in stages rather than leaving everything until the last day. Breaking down large items such as furniture or timber before they go into the bin makes better use of the available space, and loading heavier items first, followed by lighter, bulkier waste on top, keeps the load stable and easier to manage.</p>',
			),
			array(
				'guide_card_icon'    => 'pin',
				'guide_card_title'   => 'Working With a Local Provider',
				'guide_card_wide'    => 1,
				'guide_card_content' => '<p>There\'s a real benefit to choosing a local, family owned skip bin company like us over a large national operator. We know the Hobart area, the roads, and the local council rules well, which makes both delivery and any permit questions much easier to sort out. We\'re also flexible with scheduling and responsive if your plans change at short notice. For a straightforward, no fuss rubbish removal experience, our team takes pride in reliable service, and we think that makes all the difference.</p>',
			),
		),

		/* Shared: questions */
		'qa_eyebrow'      => 'Before you book',
		'qa_heading'      => 'Questions You Should Ask Before Hiring a Skip Bin',
		'qa_intro'        => 'Before you book a skip bin, it helps to know the answers to a few common questions. Here\'s what most people ask us before their bin arrives.',
		'qa_items'        => array(
			array(
				'qa_question' => 'What size skip bin do I actually need?',
				'qa_answer'   => '<p>The right size depends on the scope of your project rather than just the size of your home or site. A small bathroom renovation or a garage clean out might only need a 2 to 3 cubic metre bin, while a full home renovation, a large garden clean up, or a commercial fit out could require a 6 to 9 cubic metre bin or larger.</p><p>A helpful way to estimate is to think in terms of trailer loads, since a single trailer load of waste is roughly equivalent to a 2 cubic metre bin. If your waste includes bulky items like furniture or green waste that takes up more space than it weighs, it\'s worth sizing up slightly. When in doubt, give us a call and talk through your project with our team so you don\'t end up paying for a second bin halfway through the job.</p>',
			),
			array(
				'qa_question' => 'What items are not allowed in a skip bin?',
				'qa_answer'   => '<p>While skip bins are designed to handle most general waste, certain materials are restricted almost everywhere due to safety and environmental regulations. These typically include asbestos, paint and paint tins that haven\'t been dried out, chemicals, gas bottles, tyres, batteries, and electronic waste. We also restrict items like mattresses, fridges, or air conditioners unless arranged in advance, since these often require separate handling or attract extra disposal fees.</p><p>If you\'re unsure whether something can go in the bin, it\'s always worth checking with us first. Placing prohibited materials in a general waste bin can result in additional charges, and in the case of items like asbestos, can create a genuine health and safety risk for the people collecting and processing the waste.</p>',
			),
			array(
				'qa_question' => 'Do I need council permission to put a skip bin on the street?',
				'qa_answer'   => '<p>This depends entirely on where the bin will sit. If the skip bin will be placed entirely on private property, such as a driveway or yard, most councils don\'t require a permit. If there\'s any chance the bin will need to sit on a public road, footpath, or nature strip, even temporarily, you\'ll usually need to apply for a permit from your local council beforehand.</p><p>Requirements and fees vary from one council area to another, and processing a permit can sometimes take a few business days, so it\'s worth checking well ahead of your planned delivery date rather than leaving it until the last minute.</p>',
			),
			array(
				'qa_question' => 'How far in advance should I book a skip bin?',
				'qa_answer'   => '<p>For straightforward residential jobs, we can often have a skip bin delivered within a day or two, since we offer flexible scheduling. Booking a week or so ahead gives you more choice over delivery times and avoids the risk of missing out during busy periods, such as school holidays or the lead up to a long weekend, when demand for skip bins tends to increase.</p><p>If your project depends on a specific start date, or if you need a council permit for street placement, it\'s worth booking earlier still to leave time for that approval process to go through.</p>',
			),
			array(
				'qa_question' => 'What\'s the difference between a mini skip and a larger walk in skip bin?',
				'qa_answer'   => '<p>Mini skips are typically smaller, lower sided bins designed to be filled by hand, making them a practical choice for household clean outs, garden waste, or small renovation jobs where you\'re loading lighter, more manageable materials. Larger walk in skip bins have higher sides and more volume, and are generally better suited to bulkier waste, larger renovation or construction projects, or situations where a loader or wheelbarrow will be used to fill the bin.</p><p>Choosing between the two often comes down to both the volume of waste and how it will be loaded. If you\'re lifting everything in by hand, a lower sided mini skip will be far easier and safer to work with than trying to heave items over the high sides of a larger bin.</p>',
			),
		),
		'closing_heading' => 'Why Choose Skip 2 U Tas',
		'closing_content' => '<p>We\'re a Tasmanian family owned business located on the Southern Beaches, and for more than a decade we\'ve been the go to rubbish removal service for many homes, businesses, and industries across Hobart. Our professional services have made many customers from all walks of life completely satisfied, and that\'s because we take responsible waste disposal seriously, following the standards set by our local councils every step of the way.</p><p>We offer various skip bin sizes to the greater Hobart area, and we pride ourselves on reliable and efficient delivery. Whether you need a bin for a weekend clean out or an ongoing commercial project, we\'re committed to providing high quality, cost effective skip bin hire services to all our customers. We make booking easy, ensuring you get the right bin, right when you need it, and we back that up with the kind of local knowledge and personal service that a bigger operator simply can\'t match.</p><p>If you\'ve still got questions after reading through this, our team is always happy to talk you through your options. Give us a call or book online, and we\'ll help you get the right bin sorted so you can get on with the job at hand.</p>',
		'closing_cta'     => $hire,

		/* Shared: contact */
		'contact_eyebrow' => 'Get in touch',
		'contact_heading' => 'Talk to Your Local Skip Bin Team',
		'contact_intro'   => 'Call us or book online. We answer the phone, we know the area, and we will help you get the right bin sorted quickly.',
		'address'         => 'Southern Tasmania',
		'address_note'    => 'Servicing greater Hobart and surrounding areas',
		'hours'           => "Monday to Friday: 7.00AM to 6.00PM\nSaturday and Sunday: 7.00AM to 7.30PM\nBin delivery available 24/7",
		'map_embed_url'   => 'https://www.google.com/maps?q=Southern%20Tasmania%2C%20Australia&z=9&output=embed',
		'contact_cta'     => $hire,

		/* Footer */
		'footer_logo'           => s2u_attachment_from_url( $up . '2024/05/footer-logo.png' ),
		'footer_about'          => 'Welcome to Skip 2 U Tas. We are a Tasmanian family owned business located on the Southern Beaches. We offer various skip bin sizes to the greater Hobart area and we pride ourselves on the reliable and efficient delivery of our bins.',
		'footer_socials'        => array(
			array( 'social_network' => 'facebook', 'social_url' => 'https://www.facebook.com/profile.php?id=100089701823757' ),
			array( 'social_network' => 'phone', 'social_url' => 'tel:0408219527' ),
			array( 'social_network' => 'mail', 'social_url' => 'info@skip2utas.com.au' ),
		),
		'footer_links_title'    => 'Quick Links',
		'footer_services_title' => 'Our Services',
		'footer_contact_title'  => 'Contact Info',
		'copyright'             => 'Copyright © ' . gmdate( 'Y' ) . ' Skip 2 U Tas. All Rights Reserved.',
		'designer_name'         => 'Digital Movement',
		'designer_url'          => 'https://www.digitalmovement.com.au/',
		'floating_call_label'   => 'Call Now',
	);

	$home = array(
		'hero_badge'         => 'Tasmanian family owned since 2013',
		'hero_title'         => 'Trusted and Affordable Skip Bins in',
		'hero_title_accent'  => 'Southern Tasmania',
		'hero_lead'          => 'Our bins handle all types of rubbish including soil, concrete, bricks, tree stumps and more. Book in under a minute, get delivery 24/7 and collection within 24 hours of your call.',
		'hero_image'         => s2u_attachment_from_url( $up . '2024/06/new-homepage-banner.png' ),
		'hero_cta_primary'   => $hire,
		'hero_cta_secondary' => array( 'url' => 'tel:0408219527', 'title' => '0408 219 527', 'target' => '' ),
		'hero_points'        => array(
			array( 'hero_point_text' => 'Bins from 2m³ to 9m³' ),
			array( 'hero_point_text' => 'Delivery available 24/7' ),
			array( 'hero_point_text' => 'No hidden fees' ),
			array( 'hero_point_text' => 'Responsible waste disposal' ),
		),

		'trust_items' => array(
			array( 'trust_icon' => 'shield', 'trust_title' => 'Over a decade local', 'trust_text' => 'Family owned in Forcett' ),
			array( 'trust_icon' => 'truck', 'trust_title' => 'Delivery 24/7', 'trust_text' => 'Courtesy call before drop off' ),
			array( 'trust_icon' => 'refresh', 'trust_title' => 'Collected in 24 hours', 'trust_text' => 'Replacement bins available' ),
			array( 'trust_icon' => 'recycle', 'trust_title' => 'Recycled where possible', 'trust_text' => 'Council approved disposal' ),
		),

		'bins_eyebrow' => 'Choose your bin',
		'bins_heading' => 'Skip Bin Sizes to Suit Every Job',
		'bins_intro'   => 'From a small garage clean out to a full home renovation or a commercial site, we have a bin that fits. Not sure which size you need? Give our team a call and we will size it up with you in a couple of minutes.',
		'bin_items'    => array(
			array( 'bin_image' => s2u_attachment_from_url( $up . '2023/09/2m3slider.jpg' ), 'bin_tag' => 'Most popular', 'bin_title' => '2m<sup>3</sup> Mini Skip', 'bin_sub' => 'About 2 trailer loads', 'bin_text' => 'Ideal for a garage clean out, a small bathroom renovation or a tidy up of the garden shed.', 'bin_cta' => $hire ),
			array( 'bin_image' => s2u_attachment_from_url( $up . '2023/09/3m3slide.jpg' ), 'bin_tag' => '', 'bin_title' => '3m<sup>3</sup> Skip Bin', 'bin_sub' => 'About 3 trailer loads', 'bin_text' => 'A good fit for a bedroom or bathroom strip out, general household rubbish and light green waste.', 'bin_cta' => $hire ),
			array( 'bin_image' => s2u_attachment_from_url( $up . '2023/09/4m3slide.jpg' ), 'bin_tag' => '', 'bin_title' => '4m<sup>3</sup> Skip Bin', 'bin_sub' => 'About 4 trailer loads', 'bin_text' => 'Suits a bigger clean out, a kitchen renovation or a decent sized garden and landscaping job.', 'bin_cta' => $hire ),
			array( 'bin_image' => s2u_attachment_from_url( $up . '2023/09/6m3slide.jpg' ), 'bin_tag' => '', 'bin_title' => '6m<sup>3</sup> Skip Bin', 'bin_sub' => 'About 6 trailer loads', 'bin_text' => 'Built for major renovations, deceased estate clearances and ongoing commercial waste.', 'bin_cta' => $hire ),
			array( 'bin_image' => s2u_attachment_from_url( $up . '2023/09/8m3slide.jpg' ), 'bin_tag' => '', 'bin_title' => '8m<sup>3</sup> Walk In Skip', 'bin_sub' => 'About 8 trailer loads', 'bin_text' => 'Our largest bin for construction sites, fit outs and big landscaping projects with bulky waste.', 'bin_cta' => $hire ),
		),

		'services_eyebrow' => 'Professional services',
		'services_heading' => 'Everything We Take Care Of',
		'services_intro'   => 'We are a reliable, family owned business based in Forcett on the Southern Beaches of Tasmania. Our aim is to provide a reliable and efficient skip bin service to the greater Hobart area.',
		'service_items'    => array(
			array( 'service_icon' => 'bin', 'service_title' => 'Skip Bin Hire', 'service_text' => 'Looking for a reliable skip bin hire service in the Hobart area? We offer a range of skip bin sizes for general waste at genuinely competitive rates, so you get real value for money.', 'service_cta' => array( 'url' => '/skip-bin-hire-hobart/', 'title' => 'Learn more' ) ),
			array( 'service_icon' => 'building', 'service_title' => 'Commercial Skip Bin Hire', 'service_text' => 'Business premises need regular waste collection to stay clean and presentable. We keep commercial sites tidy with scheduled bins and prompt swap outs that work around your operating hours.', 'service_cta' => array( 'url' => '/commercial-skip-bin-hire-hobart/', 'title' => 'Learn more' ) ),
			array( 'service_icon' => 'home', 'service_title' => 'Residential Skip Bin Hire', 'service_text' => 'A residential skip bin makes light work of a big clean out. We are the preferred choice for many homeowners and property managers across the greater Hobart area.', 'service_cta' => array( 'url' => '/residential-skip-bin-hire-hobart/', 'title' => 'Learn more' ) ),
			array( 'service_icon' => 'cycle', 'service_title' => 'Waste Management', 'service_text' => 'Waste management can be a challenge for larger homes, residential complexes and commercial sites. You need a partner who turns up on time and handles the paperwork properly.', 'service_cta' => array( 'url' => '/waste-management-hobart/', 'title' => 'Learn more' ) ),
			array( 'service_icon' => 'check', 'service_title' => 'Waste Disposal and Rubbish Removal', 'service_text' => 'For rubbish removal that runs like clockwork, you need a company you can depend on. Waste management only works if drop off and pick up happen right on schedule.', 'service_cta' => array( 'url' => '/rubbish-removal-hobart/', 'title' => 'Learn more' ) ),
			array( 'service_icon' => 'calendar', 'service_title' => 'Order Bins Online', 'service_text' => 'Book your skip bin in under a minute with our online booking system. Choose your suburb, waste type, bin size and delivery date, then leave the rest to us.', 'service_cta' => array( 'url' => '/make-a-booking/', 'title' => 'Book now' ) ),
		),

		'why_eyebrow'  => 'Why choose us',
		'why_heading'  => 'Why Our Skip Bins Are Better',
		'why_intro'    => 'We are a Tasmanian family owned business located on the Southern Beaches. We offer a range of skip bin sizes to the greater Hobart area, and we pride ourselves on the reliable and efficient delivery of our bins.',
		'why_image'    => s2u_attachment_from_url( $up . '2024/05/skip-img1.jpg' ),
		'why_features' => array(
			array( 'why_icon' => 'bin', 'why_title' => 'Bins for all waste streams', 'why_text' => 'General waste, green waste, heavy waste like soil, concrete and bricks, plus mixed construction debris.' ),
			array( 'why_icon' => 'chart', 'why_title' => 'Wide range of bin sizes', 'why_text' => 'From a 2 cubic metre mini skip through to 9 cubic metre bins for major renovations and commercial sites.' ),
			array( 'why_icon' => 'clock', 'why_title' => 'Fast and friendly service', 'why_text' => 'A courtesy call before delivery, flexible scheduling and a local team that answers the phone.' ),
		),
		'why_cta'      => $hire,

		'area_eyebrow' => 'Where we deliver',
		'area_heading' => 'Our Service Area',
		'area_image'   => s2u_attachment_from_url( $up . '2024/05/service-area-image.png' ),
		'area_content' => '<p>We are a reliable, family owned business based in Forcett on Tasmania\'s Southern Beaches. We deliver skip bins right across the greater Hobart area and Southern Tasmania, including the Eastern Shore, the Southern Beaches, Sorell, Kingborough and the Hobart CBD.</p><p>Because we are local, we know the roads, the driveways and the council rules. That means fewer surprises on delivery day and quicker answers when you have a question about placement or permits.</p>',
		'area_cta'     => $hire,

		'steps_eyebrow' => 'Simple process',
		'steps_heading' => 'How It Works',
		'steps_intro'   => 'We are committed to providing high quality, cost effective skip bin hire services to all our customers. We make booking easy, ensuring you get the right bin, right when you need it.',
		'step_items'    => array(
			array( 'step_title' => 'Simple, easy booking', 'step_text' => 'Book your skip bin hire in under a minute. Use our online booking system or call us on 0408 219 527. We only need your suburb, the bin size and your preferred delivery time.' ),
			array( 'step_title' => 'Simple delivery', 'step_text' => 'On the day of delivery you will receive a courtesy call 30 minutes before drop off. All we need is an accessible place to leave the bin and clear access for our trucks. Delivery is available 24/7.' ),
			array( 'step_title' => 'Convenient pick up and replacement', 'step_text' => 'Once you are done with the skip, simply contact us and we will collect it within 24 hours. We can even provide you with a replacement skip while we empty the old one.' ),
		),

		'gallery_eyebrow' => 'Our work',
		'gallery_heading' => 'Bins on the Ground Across Southern Tasmania',
		'gallery_intro'   => 'Residential clean outs, renovations, landscaping jobs and commercial sites. Here is a look at our bins at work.',
		'gallery_images'  => array_values(
			array_filter(
				array(
					s2u_attachment_from_url( $up . '2024/05/img1.jpg' ),
					s2u_attachment_from_url( $up . '2024/05/img2.jpg' ),
					s2u_attachment_from_url( $up . '2024/05/img-1.jpg' ),
					s2u_attachment_from_url( $up . '2024/05/img-2.jpg' ),
					s2u_attachment_from_url( $up . '2024/05/homepage-content-image-5.png' ),
				)
			)
		),

		'reviews_eyebrow' => '5 star rated',
		'reviews_heading' => 'What Our Clients Say About Us',
		'reviews_intro'   => 'Real reviews from homeowners, tradies and businesses across the greater Hobart area.',
		'review_items'    => array(
			array( 'review_quote' => 'Skip 2 U Tas offer such a great service and are very efficient. 100% recommend.', 'review_name' => 'Jeremy Driver', 'review_source' => 'Google review' ),
			array( 'review_quote' => 'Skip2U are fantastic. I called for a bin and they delivered it that day, same with the pick up. Will be using them again for sure.', 'review_name' => 'Sam Langfeldt', 'review_source' => 'Google review' ),
			array( 'review_quote' => 'Great company to deal with, highly recommend. Responsive to questions about the sizing of bins, easy online ordering and prompt service. A very efficient way to get rid of bulk junk.', 'review_name' => 'Cindy Pearce', 'review_source' => 'Google review' ),
			array( 'review_quote' => 'When enquiring about hiring a skip, my questions were answered promptly and professionally. Once ordered, the skip was delivered on my preferred day by 8am. The driver was fantastic.', 'review_name' => 'Anita Gobbey', 'review_source' => 'Google review' ),
		),

		'partners_eyebrow' => 'Trusted by local business',
		'partners_heading' => 'Proudly Working With',
		'partner_logos'    => array_values(
			array_filter(
				array_map(
					function ( $n ) use ( $up ) {
						return s2u_attachment_from_url( $up . '2024/05/logo-' . $n . '.jpg' );
					},
					range( 1, 8 )
				)
			)
		),

		'cta_eyebrow'   => 'Ready when you are',
		'cta_heading'   => 'Need a Reliable Rubbish Removal Service?',
		'cta_text'      => 'Get a Skip 2 U Tas skip bin delivered to your door. Book online in under a minute or talk to our local team today.',
		'cta_image'     => s2u_attachment_from_url( $up . '2024/05/actual-skip-bin-image-v2.png' ),
		'cta_primary'   => $hire,
		'cta_secondary' => array( 'url' => 'tel:0408219527', 'title' => 'Call 0408 219 527', 'target' => '' ),
	);

	return array(
		'options' => $options,
		'home'    => $home,
	);
}

/**
 * Run the seeder.
 *
 * @param bool $force Re-run even if it has already seeded this version.
 * @param bool $dry   Report what would be written without writing it.
 * @return array {
 *     @type bool   $ran     Whether anything was written.
 *     @type string $message Human readable result.
 *     @type array  $written Field names written.
 * }
 */
function s2u_run_seeder( $force = false, $dry = false ) {
	if ( ! function_exists( 'update_field' ) ) {
		return array(
			'ran'     => false,
			'message' => 'ACF Pro is not active, so there is nothing to seed into.',
			'written' => array(),
		);
	}

	$seeded = get_option( S2U_SEED_OPTION );
	if ( $seeded === S2U_SEED_VERSION && ! $force ) {
		return array(
			'ran'     => false,
			'message' => sprintf( 'Already seeded at version %s. Nothing written. Use --force to overwrite.', $seeded ),
			'written' => array(),
		);
	}

	$content  = s2u_seed_content();
	$written  = array();
	$front_id = (int) get_option( 'page_on_front' );

	foreach ( $content['options'] as $key => $value ) {
		if ( ! $dry ) {
			update_field( $key, $value, 'option' );
		}
		$written[] = 'option:' . $key;
	}

	if ( $front_id ) {
		foreach ( $content['home'] as $key => $value ) {
			if ( ! $dry ) {
				update_field( $key, $value, $front_id );
			}
			$written[] = 'home:' . $key;
		}
	}

	if ( ! $dry ) {
		update_option( S2U_SEED_OPTION, S2U_SEED_VERSION, false );
	}

	$message = $dry
		? sprintf( 'Dry run. %d fields would be written.', count( $written ) )
		: sprintf( 'Seeded %d fields.', count( $written ) );

	if ( ! $front_id ) {
		$message .= ' No static front page is set, so the homepage fields were skipped. Set Settings > Reading to a static page and re-run with --force.';
	}

	return array(
		'ran'     => ! $dry,
		'message' => $message,
		'written' => $written,
	);
}

/**
 * WP-CLI: wp s2u seed [--force] [--dry-run]
 */
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command(
		's2u seed',
		function ( $args, $assoc ) {
			$result = s2u_run_seeder(
				! empty( $assoc['force'] ),
				! empty( $assoc['dry-run'] )
			);
			if ( $result['ran'] || ! empty( $assoc['dry-run'] ) ) {
				WP_CLI::success( $result['message'] );
			} else {
				WP_CLI::warning( $result['message'] );
			}
		}
	);
}

/**
 * Admin fallback for installs without WP-CLI.
 *
 * Runs at priority 100 because ACF registers its options pages on admin_menu
 * at 99. Registering earlier leaves this submenu parented to a menu that does
 * not exist yet, so it never appears in the sidebar.
 *
 * Reachable at: /wp-admin/admin.php?page=s2u-seed
 */
function s2u_seed_admin_page() {
	global $admin_page_hooks;

	// Nest under Site Content when that options page exists, otherwise fall
	// back to Tools so the seeder is never stranded.
	if ( isset( $admin_page_hooks['s2u-site-content'] ) ) {
		add_submenu_page(
			's2u-site-content',
			'Seed content',
			'Seed content',
			'manage_options',
			's2u-seed',
			's2u_seed_admin_screen'
		);
		return;
	}

	add_management_page(
		'Seed content',
		'Seed content',
		'manage_options',
		's2u-seed',
		's2u_seed_admin_screen'
	);
}
add_action( 'admin_menu', 's2u_seed_admin_page', 100 );

/**
 * Render the seed screen.
 */
function s2u_seed_admin_screen() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Not allowed.' );
	}

	$notice = '';
	if ( isset( $_POST['s2u_seed_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['s2u_seed_nonce'] ), 's2u_seed' ) ) {
		$result = s2u_run_seeder( isset( $_POST['s2u_force'] ), isset( $_POST['s2u_dry'] ) );
		$notice = '<div class="notice notice-' . ( $result['ran'] ? 'success' : 'warning' ) . '"><p>' . esc_html( $result['message'] ) . '</p></div>';
	}

	$seeded = get_option( S2U_SEED_OPTION );
	?>
	<div class="wrap">
		<h1>Seed content</h1>
		<?php echo $notice; // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<p>
			Writes the redesign copy into every Site Content and Homepage field in one pass, so nothing has to be
			retyped. It runs once: after that it refuses unless you tick <strong>Force</strong>.
		</p>
		<p>
			<strong>Status:</strong>
			<?php echo $seeded ? 'seeded at version ' . esc_html( $seeded ) . '.' : 'not seeded yet.'; ?>
		</p>
		<p><em>Force overwrites any edits made since the last seed. Take a database backup first.</em></p>
		<form method="post">
			<?php wp_nonce_field( 's2u_seed', 's2u_seed_nonce' ); ?>
			<p>
				<label><input type="checkbox" name="s2u_dry" value="1"> Dry run, report only</label><br>
				<label><input type="checkbox" name="s2u_force" value="1"> Force, overwrite existing values</label>
			</p>
			<?php submit_button( 'Run seeder' ); ?>
		</form>
	</div>
	<?php
}

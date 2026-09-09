<?php
/**
 * ACF field groups.
 *
 * Registered in PHP rather than imported so the definitions live in version
 * control and travel with a deploy. Only the values need seeding.
 *
 * @package skip2u-redesign
 */

defined( 'ABSPATH' ) || exit;

/**
 * Field builder. Keeps the group definitions below readable.
 *
 * @param string $key   Field name, also used for the key.
 * @param string $label Admin label.
 * @param string $type  ACF field type.
 * @param array  $extra Any other ACF field settings.
 * @return array
 */
function s2u_f( $key, $label, $type = 'text', $extra = array() ) {
	return array_merge(
		array(
			'key'   => 'field_s2u_' . $key,
			'name'  => $key,
			'label' => $label,
			'type'  => $type,
		),
		$extra
	);
}

/**
 * Tab.
 *
 * @param string $key   Unique key fragment.
 * @param string $label Tab label.
 * @return array
 */
function s2u_tab( $key, $label ) {
	return array(
		'key'       => 'field_s2u_tab_' . $key,
		'label'     => $label,
		'type'      => 'tab',
		'placement' => 'left',
	);
}

/**
 * Repeater.
 *
 * @param string $key        Field name.
 * @param string $label      Admin label.
 * @param array  $sub_fields Sub fields.
 * @param array  $extra      Any other ACF settings.
 * @return array
 */
function s2u_repeater( $key, $label, $sub_fields, $extra = array() ) {
	return array_merge(
		array(
			'key'          => 'field_s2u_' . $key,
			'name'         => $key,
			'label'        => $label,
			'type'         => 'repeater',
			'layout'       => 'block',
			'button_label' => 'Add row',
			'sub_fields'   => $sub_fields,
		),
		$extra
	);
}

/**
 * Icon picker.
 *
 * @param string $key     Field name.
 * @param string $default Default icon key.
 * @return array
 */
function s2u_icon_field( $key, $default = 'bin' ) {
	return s2u_f(
		$key,
		'Icon',
		'select',
		array(
			'choices'       => s2u_icon_choices(),
			'default_value' => $default,
			'ui'            => 1,
			'wrapper'       => array( 'width' => '25' ),
		)
	);
}

/**
 * Eyebrow, heading and intro, used by most sections.
 *
 * @param string $prefix Field name prefix.
 * @param bool   $intro  Include the intro field.
 * @return array List of field definitions.
 */
function s2u_heading_fields( $prefix, $intro = true ) {
	$fields = array(
		s2u_f( $prefix . '_eyebrow', 'Eyebrow', 'text', array( 'wrapper' => array( 'width' => '30' ) ) ),
		s2u_f( $prefix . '_heading', 'Heading', 'text', array( 'wrapper' => array( 'width' => '70' ) ) ),
	);
	if ( $intro ) {
		$fields[] = s2u_f( $prefix . '_intro', 'Intro', 'textarea', array( 'rows' => 3 ) );
	}
	return $fields;
}

/**
 * Link field.
 *
 * @param string $key   Field name.
 * @param string $label Admin label.
 * @param array  $extra Any other ACF settings.
 * @return array
 */
function s2u_link( $key, $label, $extra = array() ) {
	return s2u_f( $key, $label, 'link', array_merge( array( 'return_format' => 'array' ), $extra ) );
}

/**
 * Register both groups.
 */
function s2u_register_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	/* ------------------------------------------------------------------
	 * Site Content: header, shared pre-footer block, contact, footer
	 * ---------------------------------------------------------------- */
	$options = array_merge(
		array( s2u_tab( 'header', 'Header' ) ),
		array(
			s2u_f( 'phone', 'Phone number', 'text', array( 'wrapper' => array( 'width' => '50' ) ) ),
			s2u_f( 'email', 'Email address', 'email', array( 'wrapper' => array( 'width' => '50' ) ) ),
			s2u_f( 'servicing_label', 'Servicing text', 'text', array( 'wrapper' => array( 'width' => '50' ) ) ),
			s2u_f( 'topbar_note', 'Top bar note', 'text', array( 'wrapper' => array( 'width' => '50' ) ) ),
			s2u_f( 'header_logo', 'Header logo', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium', 'instructions' => 'The light-background logo. Falls back to the theme custom logo.' ) ),
			s2u_link( 'header_cta', 'Header button' ),
		),

		array( s2u_tab( 'guide', 'Shared: Guide' ) ),
		s2u_heading_fields( 'guide', false ),
		array(
			s2u_f( 'guide_intro', 'Intro', 'wysiwyg', array( 'media_upload' => 0, 'tabs' => 'visual' ) ),
			s2u_repeater(
				'guide_cards',
				'Guide cards',
				array(
					s2u_icon_field( 'guide_card_icon' ),
					s2u_f( 'guide_card_title', 'Title', 'text', array( 'wrapper' => array( 'width' => '55' ) ) ),
					s2u_f( 'guide_card_wide', 'Full width', 'true_false', array( 'ui' => 1, 'wrapper' => array( 'width' => '20' ) ) ),
					s2u_f( 'guide_card_content', 'Content', 'wysiwyg', array( 'media_upload' => 0, 'tabs' => 'visual' ) ),
				)
			),
		),

		array( s2u_tab( 'questions', 'Shared: Questions' ) ),
		s2u_heading_fields( 'qa' ),
		array(
			s2u_repeater(
				'qa_items',
				'Questions',
				array(
					s2u_f( 'qa_question', 'Question', 'text' ),
					s2u_f( 'qa_answer', 'Answer', 'wysiwyg', array( 'media_upload' => 0, 'tabs' => 'visual' ) ),
				)
			),
			s2u_f( 'closing_heading', 'Closing heading', 'text' ),
			s2u_f( 'closing_content', 'Closing content', 'wysiwyg', array( 'media_upload' => 0, 'tabs' => 'visual' ) ),
			s2u_link( 'closing_cta', 'Closing button' ),
		),

		array( s2u_tab( 'contact', 'Shared: Contact' ) ),
		s2u_heading_fields( 'contact' ),
		array(
			s2u_f( 'address', 'Address', 'text', array( 'wrapper' => array( 'width' => '50' ) ) ),
			s2u_f( 'address_note', 'Address note', 'text', array( 'wrapper' => array( 'width' => '50' ) ) ),
			s2u_f( 'hours', 'Opening hours', 'textarea', array( 'rows' => 3, 'new_lines' => 'br' ) ),
			s2u_f( 'map_embed_url', 'Google map embed URL', 'url', array( 'instructions' => 'A maps.google.com URL ending in &output=embed. No API key needed.' ) ),
			s2u_link( 'contact_cta', 'Contact button' ),
		),

		array( s2u_tab( 'footer', 'Footer' ) ),
		array(
			s2u_f( 'footer_logo', 'Footer logo', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
			s2u_f( 'footer_about', 'About text', 'textarea', array( 'rows' => 4 ) ),
			s2u_repeater(
				'footer_socials',
				'Social links',
				array(
					s2u_f(
						'social_network',
						'Network',
						'select',
						array(
							'choices' => array(
								'facebook' => 'Facebook',
								'phone'    => 'Phone',
								'mail'     => 'Email',
							),
							'wrapper' => array( 'width' => '30' ),
						)
					),
					s2u_f( 'social_url', 'URL', 'text', array( 'wrapper' => array( 'width' => '70' ) ) ),
				),
				array( 'layout' => 'table' )
			),
			s2u_f( 'footer_links_title', 'Quick links column title', 'text', array( 'wrapper' => array( 'width' => '33' ) ) ),
			s2u_f( 'footer_services_title', 'Services column title', 'text', array( 'wrapper' => array( 'width' => '33' ) ) ),
			s2u_f( 'footer_contact_title', 'Contact column title', 'text', array( 'wrapper' => array( 'width' => '34' ) ) ),
			s2u_f( 'copyright', 'Copyright line', 'text' ),
			s2u_f( 'designer_name', 'Designed by', 'text', array( 'wrapper' => array( 'width' => '50' ) ) ),
			s2u_f( 'designer_url', 'Designer URL', 'text', array( 'wrapper' => array( 'width' => '50' ) ) ),
			s2u_f( 'floating_call_label', 'Mobile call button label', 'text' ),
		)
	);

	acf_add_local_field_group(
		array(
			'key'      => 'group_s2u_options',
			'title'    => 'Site Content',
			'location' => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 's2u-site-content',
					),
				),
			),
			'fields'   => $options,
		)
	);

	/* ------------------------------------------------------------------
	 * Homepage sections
	 * ---------------------------------------------------------------- */
	$home = array_merge(
		array( s2u_tab( 'hero', 'Hero' ) ),
		array(
			s2u_f( 'hero_badge', 'Badge', 'text' ),
			s2u_f( 'hero_title', 'Title', 'text', array( 'wrapper' => array( 'width' => '60' ) ) ),
			s2u_f( 'hero_title_accent', 'Title accent', 'text', array( 'wrapper' => array( 'width' => '40' ), 'instructions' => 'Shown in orange after the title.' ) ),
			s2u_f( 'hero_lead', 'Lead paragraph', 'textarea', array( 'rows' => 3 ) ),
			s2u_f( 'hero_image', 'Background image', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium', 'instructions' => 'Falls back to the page featured image.' ) ),
			s2u_link( 'hero_cta_primary', 'Primary button', array( 'wrapper' => array( 'width' => '50' ) ) ),
			s2u_link( 'hero_cta_secondary', 'Secondary button', array( 'wrapper' => array( 'width' => '50' ) ) ),
			s2u_repeater( 'hero_points', 'Points', array( s2u_f( 'hero_point_text', 'Text', 'text' ) ), array( 'layout' => 'table' ) ),
		),

		array( s2u_tab( 'trust', 'Trust strip' ) ),
		array(
			s2u_repeater(
				'trust_items',
				'Trust items',
				array(
					s2u_icon_field( 'trust_icon', 'shield' ),
					s2u_f( 'trust_title', 'Title', 'text', array( 'wrapper' => array( 'width' => '35' ) ) ),
					s2u_f( 'trust_text', 'Subtitle', 'text', array( 'wrapper' => array( 'width' => '40' ) ) ),
				),
				array( 'layout' => 'table' )
			),
		),

		array( s2u_tab( 'bins', 'Bin sizes' ) ),
		s2u_heading_fields( 'bins' ),
		array(
			s2u_repeater(
				'bin_items',
				'Bins',
				array(
					s2u_f( 'bin_image', 'Image', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium', 'wrapper' => array( 'width' => '30' ) ) ),
					s2u_f( 'bin_tag', 'Tag', 'text', array( 'wrapper' => array( 'width' => '20' ), 'instructions' => 'Optional, eg Most popular.' ) ),
					s2u_f( 'bin_title', 'Title', 'text', array( 'wrapper' => array( 'width' => '25' ) ) ),
					s2u_f( 'bin_sub', 'Subtitle', 'text', array( 'wrapper' => array( 'width' => '25' ) ) ),
					s2u_f( 'bin_text', 'Description', 'textarea', array( 'rows' => 2 ) ),
					s2u_link( 'bin_cta', 'Button' ),
				)
			),
		),

		array( s2u_tab( 'services', 'Services' ) ),
		s2u_heading_fields( 'services' ),
		array(
			s2u_repeater(
				'service_items',
				'Services',
				array(
					s2u_icon_field( 'service_icon' ),
					s2u_f( 'service_title', 'Title', 'text', array( 'wrapper' => array( 'width' => '75' ) ) ),
					s2u_f( 'service_text', 'Text', 'textarea', array( 'rows' => 3 ) ),
					s2u_link( 'service_cta', 'Link' ),
				)
			),
		),

		array( s2u_tab( 'why', 'Why us' ) ),
		s2u_heading_fields( 'why' ),
		array(
			s2u_f( 'why_image', 'Image', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
			s2u_repeater(
				'why_features',
				'Features',
				array(
					s2u_icon_field( 'why_icon' ),
					s2u_f( 'why_title', 'Title', 'text', array( 'wrapper' => array( 'width' => '75' ) ) ),
					s2u_f( 'why_text', 'Text', 'textarea', array( 'rows' => 2 ) ),
				)
			),
			s2u_link( 'why_cta', 'Button' ),
		),

		array( s2u_tab( 'area', 'Service area' ) ),
		s2u_heading_fields( 'area', false ),
		array(
			s2u_f( 'area_image', 'Image', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
			s2u_f( 'area_content', 'Content', 'wysiwyg', array( 'media_upload' => 0, 'tabs' => 'visual' ) ),
			s2u_link( 'area_cta', 'Button' ),
		),

		array( s2u_tab( 'steps', 'How it works' ) ),
		s2u_heading_fields( 'steps' ),
		array(
			s2u_repeater(
				'step_items',
				'Steps',
				array(
					s2u_f( 'step_title', 'Title', 'text' ),
					s2u_f( 'step_text', 'Text', 'textarea', array( 'rows' => 3 ) ),
				)
			),
		),

		array( s2u_tab( 'gallery', 'Gallery' ) ),
		s2u_heading_fields( 'gallery' ),
		array(
			s2u_f(
				'gallery_images',
				'Images',
				'gallery',
				array(
					'return_format' => 'array',
					'preview_size'  => 'medium',
					'max'           => 5,
					'instructions'  => 'Five images. The first becomes the tall feature tile.',
				)
			),
		),

		array( s2u_tab( 'reviews', 'Reviews' ) ),
		s2u_heading_fields( 'reviews' ),
		array(
			s2u_repeater(
				'review_items',
				'Reviews',
				array(
					s2u_f( 'review_quote', 'Quote', 'textarea', array( 'rows' => 3 ) ),
					s2u_f( 'review_name', 'Name', 'text', array( 'wrapper' => array( 'width' => '50' ) ) ),
					s2u_f( 'review_source', 'Source', 'text', array( 'wrapper' => array( 'width' => '50' ) ) ),
				)
			),
		),

		array( s2u_tab( 'partners', 'Partners' ) ),
		s2u_heading_fields( 'partners', false ),
		array(
			s2u_f( 'partner_logos', 'Logos', 'gallery', array( 'return_format' => 'array', 'preview_size' => 'thumbnail' ) ),
		),

		array( s2u_tab( 'cta', 'CTA band' ) ),
		array(
			s2u_f( 'cta_eyebrow', 'Eyebrow', 'text', array( 'wrapper' => array( 'width' => '30' ) ) ),
			s2u_f( 'cta_heading', 'Heading', 'text', array( 'wrapper' => array( 'width' => '70' ) ) ),
			s2u_f( 'cta_text', 'Text', 'textarea', array( 'rows' => 2 ) ),
			s2u_f( 'cta_image', 'Background image', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
			s2u_link( 'cta_primary', 'Primary button', array( 'wrapper' => array( 'width' => '50' ) ) ),
			s2u_link( 'cta_secondary', 'Secondary button', array( 'wrapper' => array( 'width' => '50' ) ) ),
		)
	);

	acf_add_local_field_group(
		array(
			'key'      => 'group_s2u_home',
			'title'    => 'Homepage sections',
			'location' => array(
				array(
					array(
						'param'    => 'page_type',
						'operator' => '==',
						'value'    => 'front_page',
					),
				),
			),
			'fields'   => $home,
		)
	);
}
add_action( 'acf/init', 's2u_register_fields' );

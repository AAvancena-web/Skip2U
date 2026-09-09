<?php
/**
 * Field accessors, icons and small render helpers.
 *
 * @package skip2u-redesign
 */

defined( 'ABSPATH' ) || exit;

/**
 * Read an options-page field, falling back when empty.
 *
 * The fallbacks matter: templates must render sensibly before the seeder
 * has run, and after an editor clears a field by accident.
 *
 * @param string $key     Field name.
 * @param mixed  $default Value used when the field is empty.
 * @return mixed
 */
function s2u_option( $key, $default = '' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}
	$value = get_field( $key, 'option' );
	return ( null === $value || '' === $value || array() === $value ) ? $default : $value;
}

/**
 * Read a field on the current post, falling back when empty.
 *
 * @param string   $key     Field name.
 * @param mixed    $default Value used when the field is empty.
 * @param int|null $post_id Optional post ID.
 * @return mixed
 */
function s2u_field( $key, $default = '', $post_id = null ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}
	$value = get_field( $key, $post_id );
	return ( null === $value || '' === $value || array() === $value ) ? $default : $value;
}

/**
 * Inline SVG icon.
 *
 * Icons live in code, not the database, so editors pick a key from a
 * select instead of pasting markup into a field.
 *
 * @param string $name  Icon key.
 * @param string $class Optional class attribute.
 * @return string SVG markup.
 */
function s2u_icon( $name, $class = '' ) {
	$attr = $class ? ' class="' . esc_attr( $class ) . '"' : '';

	if ( 'star' === $name ) {
		return '<svg viewBox="0 0 24 24" fill="currentColor"' . $attr . '><path d="m12 2 3.1 6.3 6.9 1-5 4.9 1.2 6.9L12 17.8 5.8 21l1.2-6.9-5-4.9 6.9-1z"/></svg>';
	}

	if ( 'facebook' === $name ) {
		return '<svg viewBox="0 0 24 24" fill="currentColor"' . $attr . '><path d="M13.5 22v-8h2.7l.4-3.1h-3.1V8.9c0-.9.25-1.5 1.55-1.5h1.65V4.6c-.3 0-1.28-.1-2.42-.1-2.4 0-4.03 1.46-4.03 4.14v2.31H7.5V14h2.75v8z"/></svg>';
	}

	$paths = array(
		'phone'    => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/>',
		'mail'     => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/>',
		'pin'      => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
		'clock'    => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
		'arrow'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'check'    => '<path d="M20 6 9 17l-5-5"/>',
		'shield'   => '<path d="M12 2 4 6v6c0 5 3.4 9.4 8 10 4.6-.6 8-5 8-10V6z"/><path d="m9 12 2 2 4-4"/>',
		'truck'    => '<path d="M10 17h4V5H2v12h3M14 17h6v-5l-3-4h-3"/><circle cx="7.5" cy="17.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/>',
		'refresh'  => '<path d="M12 22a10 10 0 1 0-10-10"/><path d="M2 12h4M12 2v4"/><path d="m8 14 3 3 5-6"/>',
		'recycle'  => '<path d="M7 19a4 4 0 0 1-3.5-6l4.5-8a4 4 0 0 1 7 0l4.5 8a4 4 0 0 1-3.5 6z"/><path d="M9 19h6"/>',
		'bin'      => '<path d="M3 6h18l-2 13H5z"/><path d="M9 6V4h6v2M10 10v5M14 10v5"/>',
		'building' => '<path d="M3 21h18M5 21V7l7-4 7 4v14"/><path d="M9 21v-6h6v6M9 11h.01M15 11h.01"/>',
		'home'     => '<path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1z"/><path d="M9 21v-8h6v8"/>',
		'cycle'    => '<path d="M12 3v6l3-2M7 12l-4 3 3 4M17 12l4 3-3 4"/><circle cx="12" cy="12" r="9"/>',
		'calendar' => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/><path d="m9 16 2 2 4-4"/>',
		'chart'    => '<path d="M4 20h16M4 20V9M20 20V4M12 20V13"/>',
		'warning'  => '<path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><path d="M12 9v4M12 17h.01"/>',
		'doc'      => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M9 15l2 2 4-4"/>',
		'info'     => '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>',
	);

	$key = isset( $paths[ $name ] ) ? $name : 'arrow';

	return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"' . $attr . '>' . $paths[ $key ] . '</svg>';
}

/**
 * Icon keys offered to editors.
 *
 * @return array
 */
function s2u_icon_choices() {
	return array(
		'bin'      => 'Skip bin',
		'truck'    => 'Truck',
		'building' => 'Commercial building',
		'home'     => 'House',
		'cycle'    => 'Waste cycle',
		'recycle'  => 'Recycling',
		'calendar' => 'Calendar',
		'check'    => 'Tick',
		'shield'   => 'Shield',
		'refresh'  => 'Refresh',
		'clock'    => 'Clock',
		'chart'    => 'Bar chart',
		'warning'  => 'Warning',
		'doc'      => 'Document',
		'pin'      => 'Map pin',
		'phone'    => 'Phone',
		'mail'     => 'Envelope',
		'info'     => 'Information',
	);
}

/**
 * Render an ACF link field as a button.
 *
 * @param array  $link  ACF link array (url/title/target).
 * @param string $class Button classes.
 * @param bool   $arrow Append the arrow icon.
 */
function s2u_button( $link, $class = 'btn btn--primary', $arrow = true ) {
	if ( empty( $link ) || empty( $link['url'] ) ) {
		return;
	}

	$title  = '';
	if ( ! empty( $link['title'] ) ) {
		$title = $link['title'];
	} elseif ( ! empty( $link['label'] ) ) {
		$title = $link['label'];
	}

	$target = ! empty( $link['target'] ) ? ' target="' . esc_attr( $link['target'] ) . '" rel="noopener"' : '';

	echo '<a class="' . esc_attr( $class ) . '" href="' . esc_url( $link['url'] ) . '"' . $target . '>' // phpcs:ignore WordPress.Security.EscapeOutput
		. esc_html( $title )
		. ( $arrow ? s2u_icon( 'arrow' ) : '' ) // phpcs:ignore WordPress.Security.EscapeOutput
		. '</a>';
}

/**
 * Echo an image array from ACF, or nothing when unset.
 *
 * @param array  $image ACF image array.
 * @param string $size  Registered image size.
 * @param array  $args  Extra attributes.
 */
function s2u_image( $image, $size = 'large', $args = array() ) {
	if ( empty( $image ) || empty( $image['ID'] ) ) {
		return;
	}
	echo wp_get_attachment_image( $image['ID'], $size, false, $args ); // phpcs:ignore WordPress.Security.EscapeOutput
}

/**
 * Section heading block shared by most sections.
 *
 * @param string $eyebrow Small label above the heading.
 * @param string $heading Section heading.
 * @param string $intro   Optional intro paragraph.
 * @param string $class   Extra classes on the wrapper.
 */
function s2u_section_head( $eyebrow, $heading, $intro = '', $class = '' ) {
	if ( ! $eyebrow && ! $heading && ! $intro ) {
		return;
	}
	echo '<div class="section-head ' . esc_attr( $class ) . '">';
	if ( $eyebrow ) {
		echo '<span class="eyebrow">' . esc_html( $eyebrow ) . '</span>';
	}
	if ( $heading ) {
		echo '<h2>' . esc_html( $heading ) . '</h2>';
	}
	if ( $intro ) {
		echo '<p>' . esc_html( $intro ) . '</p>';
	}
	echo '</div>';
}

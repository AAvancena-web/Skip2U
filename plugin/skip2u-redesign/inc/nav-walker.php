<?php
/**
 * Menu walker.
 *
 * Emits the class names the redesign stylesheet expects (has-sub, is-current,
 * .sub) instead of the WordPress defaults, so no extra CSS aliasing is needed.
 *
 * @package skip2u-redesign
 */

defined( 'ABSPATH' ) || exit;

class S2U_Nav_Walker extends Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '<ul class="sub">';
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '</ul>';
	}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes = array();

		if ( in_array( 'menu-item-has-children', (array) $item->classes, true ) ) {
			$classes[] = 'has-sub';
		}
		foreach ( array( 'current-menu-item', 'current_page_item', 'current-menu-ancestor', 'current_page_ancestor' ) as $current ) {
			if ( in_array( $current, (array) $item->classes, true ) ) {
				$classes[] = 'is-current';
				break;
			}
		}

		$output .= '<li' . ( $classes ? ' class="' . esc_attr( implode( ' ', array_unique( $classes ) ) ) . '"' : '' ) . '>';

		$atts = '';
		if ( ! empty( $item->url ) ) {
			$atts .= ' href="' . esc_url( $item->url ) . '"';
		}
		if ( ! empty( $item->target ) ) {
			$atts .= ' target="' . esc_attr( $item->target ) . '"';
		}
		if ( ! empty( $item->xfn ) ) {
			$atts .= ' rel="' . esc_attr( $item->xfn ) . '"';
		}

		$output .= '<a' . $atts . '>' . esc_html( $item->title ) . '</a>';
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= '</li>';
	}
}

/**
 * The child theme appends a .reflex-submenu-toggle span to every menu-1 item
 * with children, and its stylesheet renders that as a solid black square
 * under 992px. Our menu has its own toggle, so drop the filter on requests
 * we render.
 */
function s2u_drop_theme_menu_arrow() {
	if ( s2u_should_render() && function_exists( 'add_arrow' ) ) {
		remove_filter( 'walker_nav_menu_start_el', 'add_arrow', 10 );
	}
}
add_action( 'template_redirect', 's2u_drop_theme_menu_arrow', 20 );

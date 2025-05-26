<?php
/**
 * The template to display default site header
 *
 * @package SPOCK
 * @since SPOCK 1.0
 */

$spock_header_css   = '';
$spock_header_image = get_header_image();
$spock_header_video = spock_get_header_video();
if ( ! empty( $spock_header_image ) && spock_trx_addons_featured_image_override( is_singular() || spock_storage_isset( 'blog_archive' ) || is_category() ) ) {
	$spock_header_image = spock_get_current_mode_image( $spock_header_image );
}

?><header class="top_panel top_panel_default
	<?php
	echo ! empty( $spock_header_image ) || ! empty( $spock_header_video ) ? ' with_bg_image' : ' without_bg_image';
	if ( '' != $spock_header_video ) {
		echo ' with_bg_video';
	}
	if ( '' != $spock_header_image ) {
		echo ' ' . esc_attr( spock_add_inline_css_class( 'background-image: url(' . esc_url( $spock_header_image ) . ');' ) );
	}
	if ( is_single() && has_post_thumbnail() ) {
		echo ' with_featured_image';
	}
	if ( spock_is_on( spock_get_theme_option( 'header_fullheight' ) ) ) {
		echo ' header_fullheight spock-full-height';
	}
	$spock_header_scheme = spock_get_theme_option( 'header_scheme' );
	if ( ! empty( $spock_header_scheme ) && ! spock_is_inherit( $spock_header_scheme  ) ) {
		echo ' scheme_' . esc_attr( $spock_header_scheme );
	}
	?>
">
	<?php

	// Background video
	if ( ! empty( $spock_header_video ) ) {
		get_template_part( apply_filters( 'spock_filter_get_template_part', 'templates/header-video' ) );
	}

	// Main menu
	get_template_part( apply_filters( 'spock_filter_get_template_part', 'templates/header-navi' ) );

	// Mobile header
	if ( spock_is_on( spock_get_theme_option( 'header_mobile_enabled' ) ) ) {
		get_template_part( apply_filters( 'spock_filter_get_template_part', 'templates/header-mobile' ) );
	}

	// Page title and breadcrumbs area
	if ( ! is_single() ) {
		get_template_part( apply_filters( 'spock_filter_get_template_part', 'templates/header-title' ) );
	}

	// Header widgets area
	get_template_part( apply_filters( 'spock_filter_get_template_part', 'templates/header-widgets' ) );
	?>
</header>

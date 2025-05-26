<?php
/**
 * The template to display custom header from the ThemeREX Addons Layouts
 *
 * @package SPOCK
 * @since SPOCK 1.0.06
 */

$spock_header_css   = '';
$spock_header_image = get_header_image();
$spock_header_video = spock_get_header_video();
if ( ! empty( $spock_header_image ) && spock_trx_addons_featured_image_override( is_singular() || spock_storage_isset( 'blog_archive' ) || is_category() ) ) {
	$spock_header_image = spock_get_current_mode_image( $spock_header_image );
}

$spock_header_id = spock_get_custom_header_id();
$spock_header_meta = get_post_meta( $spock_header_id, 'trx_addons_options', true );
if ( ! empty( $spock_header_meta['margin'] ) ) {
	spock_add_inline_css( sprintf( '.page_content_wrap{padding-top:%s}', esc_attr( spock_prepare_css_value( $spock_header_meta['margin'] ) ) ) );
}

?><header class="top_panel top_panel_custom top_panel_custom_<?php echo esc_attr( $spock_header_id ); ?> top_panel_custom_<?php echo esc_attr( sanitize_title( get_the_title( $spock_header_id ) ) ); ?>
				<?php
				echo ! empty( $spock_header_image ) || ! empty( $spock_header_video )
					? ' with_bg_image'
					: ' without_bg_image';
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

	// Custom header's layout
	do_action( 'spock_action_show_layout', $spock_header_id );

	// Header widgets area
	get_template_part( apply_filters( 'spock_filter_get_template_part', 'templates/header-widgets' ) );

	?>
</header>

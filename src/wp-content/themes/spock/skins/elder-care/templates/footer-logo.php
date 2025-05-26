<?php
/**
 * The template to display the site logo in the footer
 *
 * @package SPOCK
 * @since SPOCK 1.0.10
 */

// Logo
if ( spock_is_on( spock_get_theme_option( 'logo_in_footer' ) ) ) {
	$spock_logo_image = spock_get_logo_image( 'footer' );
	$spock_logo_text  = get_bloginfo( 'name' );
	if ( ! empty( $spock_logo_image['logo'] ) || ! empty( $spock_logo_text ) ) {
		?>
		<div class="footer_logo_wrap">
			<div class="footer_logo_inner">
				<?php
				if ( ! empty( $spock_logo_image['logo'] ) ) {
					$spock_attr = spock_getimagesize( $spock_logo_image['logo'] );
					echo '<a href="' . esc_url( home_url( '/' ) ) . '">'
							. '<img src="' . esc_url( $spock_logo_image['logo'] ) . '"'
								. ( ! empty( $spock_logo_image['logo_retina'] ) ? ' srcset="' . esc_url( $spock_logo_image['logo_retina'] ) . ' 2x"' : '' )
								. ' class="logo_footer_image"'
								. ' alt="' . esc_attr__( 'Site logo', 'spock' ) . '"'
								. ( ! empty( $spock_attr[3] ) ? ' ' . wp_kses_data( $spock_attr[3] ) : '' )
							. '>'
						. '</a>';
				} elseif ( ! empty( $spock_logo_text ) ) {
					echo '<h1 class="logo_footer_text">'
							. '<a href="' . esc_url( home_url( '/' ) ) . '">'
								. esc_html( $spock_logo_text )
							. '</a>'
						. '</h1>';
				}
				?>
			</div>
		</div>
		<?php
	}
}

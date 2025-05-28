<?php
/**
 * The template to display the logo or the site name and the slogan in the Header
 *
 * @package SPOCK
 * @since SPOCK 1.0
 */

$spock_args = get_query_var( 'spock_logo_args' );

// Site logo
$spock_logo_type   = isset( $spock_args['type'] ) ? $spock_args['type'] : '';
$spock_logo_image  = spock_get_logo_image( $spock_logo_type );
$spock_logo_text   = spock_is_on( spock_get_theme_option( 'logo_text' ) ) ? get_bloginfo( 'name' ) : '';
$spock_logo_slogan = get_bloginfo( 'description', 'display' );
if ( ! empty( $spock_logo_image['logo'] ) || ! empty( $spock_logo_text ) ) {
	?><a class="sc_layouts_logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php
		if ( ! empty( $spock_logo_image['logo'] ) ) {
			if ( empty( $spock_logo_type ) && function_exists( 'the_custom_logo' ) && is_numeric($spock_logo_image['logo']) && (int) $spock_logo_image['logo'] > 0 ) {
				the_custom_logo();
			} else {
				$spock_attr = spock_getimagesize( $spock_logo_image['logo'] );
				echo '<img src="' . esc_url( $spock_logo_image['logo'] ) . '"'
						. ( ! empty( $spock_logo_image['logo_retina'] ) ? ' srcset="' . esc_url( $spock_logo_image['logo_retina'] ) . ' 2x"' : '' )
						. ' alt="' . esc_attr( $spock_logo_text ) . '"'
						. ( ! empty( $spock_attr[3] ) ? ' ' . wp_kses_data( $spock_attr[3] ) : '' )
						. '>';
			}
		} else {
			spock_show_layout( spock_prepare_macros( $spock_logo_text ), '<span class="logo_text">', '</span>' );
			spock_show_layout( spock_prepare_macros( $spock_logo_slogan ), '<span class="logo_slogan">', '</span>' );
		}
		?>
	</a>
	<?php
}

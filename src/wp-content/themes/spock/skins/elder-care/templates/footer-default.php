<?php
/**
 * The template to display default site footer
 *
 * @package SPOCK
 * @since SPOCK 1.0.10
 */

?>
<footer class="footer_wrap footer_default
<?php
$spock_footer_scheme = spock_get_theme_option( 'footer_scheme' );
if ( ! empty( $spock_footer_scheme ) && ! spock_is_inherit( $spock_footer_scheme  ) ) {
	echo ' scheme_' . esc_attr( $spock_footer_scheme );
}
?>
				">
	<?php

	// Footer widgets area
	get_template_part( apply_filters( 'spock_filter_get_template_part', 'templates/footer-widgets' ) );

	// Logo
	get_template_part( apply_filters( 'spock_filter_get_template_part', 'templates/footer-logo' ) );

	// Socials
	get_template_part( apply_filters( 'spock_filter_get_template_part', 'templates/footer-socials' ) );

	// Copyright area
	get_template_part( apply_filters( 'spock_filter_get_template_part', 'templates/footer-copyright' ) );

	?>
</footer><!-- /.footer_wrap -->

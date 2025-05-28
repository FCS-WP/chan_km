<?php
/**
 * The template to display the socials in the footer
 *
 * @package SPOCK
 * @since SPOCK 1.0.10
 */


// Socials
if ( spock_is_on( spock_get_theme_option( 'socials_in_footer' ) ) ) {
	$spock_output = spock_get_socials_links();
	if ( '' != $spock_output ) {
		?>
		<div class="footer_socials_wrap socials_wrap">
			<div class="footer_socials_inner">
				<?php spock_show_layout( $spock_output ); ?>
			</div>
		</div>
		<?php
	}
}

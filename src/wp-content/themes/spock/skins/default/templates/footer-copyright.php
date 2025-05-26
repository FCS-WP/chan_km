<?php
/**
 * The template to display the copyright info in the footer
 *
 * @package SPOCK
 * @since SPOCK 1.0.10
 */

// Copyright area
?> 
<div class="footer_copyright_wrap
<?php
$spock_copyright_scheme = spock_get_theme_option( 'copyright_scheme' );
if ( ! empty( $spock_copyright_scheme ) && ! spock_is_inherit( $spock_copyright_scheme  ) ) {
	echo ' scheme_' . esc_attr( $spock_copyright_scheme );
}
?>
				">
	<div class="footer_copyright_inner">
		<div class="content_wrap">
			<div class="copyright_text">
			<?php
				$spock_copyright = spock_get_theme_option( 'copyright' );
			if ( ! empty( $spock_copyright ) ) {
				// Replace {{Y}} or {Y} with the current year
				$spock_copyright = str_replace( array( '{{Y}}', '{Y}' ), date( 'Y' ), $spock_copyright );
				// Replace {{...}} and ((...)) on the <i>...</i> and <b>...</b>
				$spock_copyright = spock_prepare_macros( $spock_copyright );
				// Display copyright
				echo wp_kses( nl2br( $spock_copyright ), 'spock_kses_content' );
			}
			?>
			</div>
		</div>
	</div>
</div>

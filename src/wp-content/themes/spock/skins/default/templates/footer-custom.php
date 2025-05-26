<?php
/**
 * The template to display default site footer
 *
 * @package SPOCK
 * @since SPOCK 1.0.10
 */

$spock_footer_id = spock_get_custom_footer_id();
$spock_footer_meta = get_post_meta( $spock_footer_id, 'trx_addons_options', true );
if ( ! empty( $spock_footer_meta['margin'] ) ) {
	spock_add_inline_css( sprintf( '.page_content_wrap{padding-bottom:%s}', esc_attr( spock_prepare_css_value( $spock_footer_meta['margin'] ) ) ) );
}
?>
<footer class="footer_wrap footer_custom footer_custom_<?php echo esc_attr( $spock_footer_id ); ?> footer_custom_<?php echo esc_attr( sanitize_title( get_the_title( $spock_footer_id ) ) ); ?>
						<?php
						$spock_footer_scheme = spock_get_theme_option( 'footer_scheme' );
						if ( ! empty( $spock_footer_scheme ) && ! spock_is_inherit( $spock_footer_scheme  ) ) {
							echo ' scheme_' . esc_attr( $spock_footer_scheme );
						}
						?>
						">
	<?php
	// Custom footer's layout
	do_action( 'spock_action_show_layout', $spock_footer_id );
	?>
</footer><!-- /.footer_wrap -->

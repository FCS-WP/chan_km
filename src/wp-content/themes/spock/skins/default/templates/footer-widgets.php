<?php
/**
 * The template to display the widgets area in the footer
 *
 * @package SPOCK
 * @since SPOCK 1.0.10
 */

// Footer sidebar
$spock_footer_name    = spock_get_theme_option( 'footer_widgets' );
$spock_footer_present = ! spock_is_off( $spock_footer_name ) && is_active_sidebar( $spock_footer_name );
if ( $spock_footer_present ) {
	spock_storage_set( 'current_sidebar', 'footer' );
	$spock_footer_wide = spock_get_theme_option( 'footer_wide' );
	ob_start();
	if ( is_active_sidebar( $spock_footer_name ) ) {
		dynamic_sidebar( $spock_footer_name );
	}
	$spock_out = trim( ob_get_contents() );
	ob_end_clean();
	if ( ! empty( $spock_out ) ) {
		$spock_out          = preg_replace( "/<\\/aside>[\r\n\s]*<aside/", '</aside><aside', $spock_out );
		$spock_need_columns = true;   //or check: strpos($spock_out, 'columns_wrap')===false;
		if ( $spock_need_columns ) {
			$spock_columns = max( 0, (int) spock_get_theme_option( 'footer_columns' ) );			
			if ( 0 == $spock_columns ) {
				$spock_columns = min( 4, max( 1, spock_tags_count( $spock_out, 'aside' ) ) );
			}
			if ( $spock_columns > 1 ) {
				$spock_out = preg_replace( '/<aside([^>]*)class="widget/', '<aside$1class="column-1_' . esc_attr( $spock_columns ) . ' widget', $spock_out );
			} else {
				$spock_need_columns = false;
			}
		}
		?>
		<div class="footer_widgets_wrap widget_area<?php echo ! empty( $spock_footer_wide ) ? ' footer_fullwidth' : ''; ?> sc_layouts_row sc_layouts_row_type_normal">
			<?php do_action( 'spock_action_before_sidebar_wrap', 'footer' ); ?>
			<div class="footer_widgets_inner widget_area_inner">
				<?php
				if ( ! $spock_footer_wide ) {
					?>
					<div class="content_wrap">
					<?php
				}
				if ( $spock_need_columns ) {
					?>
					<div class="columns_wrap">
					<?php
				}
				do_action( 'spock_action_before_sidebar', 'footer' );
				spock_show_layout( $spock_out );
				do_action( 'spock_action_after_sidebar', 'footer' );
				if ( $spock_need_columns ) {
					?>
					</div><!-- /.columns_wrap -->
					<?php
				}
				if ( ! $spock_footer_wide ) {
					?>
					</div><!-- /.content_wrap -->
					<?php
				}
				?>
			</div><!-- /.footer_widgets_inner -->
			<?php do_action( 'spock_action_after_sidebar_wrap', 'footer' ); ?>
		</div><!-- /.footer_widgets_wrap -->
		<?php
	}
}

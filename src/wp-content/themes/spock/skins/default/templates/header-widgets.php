<?php
/**
 * The template to display the widgets area in the header
 *
 * @package SPOCK
 * @since SPOCK 1.0
 */

// Header sidebar
$spock_header_name    = spock_get_theme_option( 'header_widgets' );
$spock_header_present = ! spock_is_off( $spock_header_name ) && is_active_sidebar( $spock_header_name );
if ( $spock_header_present ) {
	spock_storage_set( 'current_sidebar', 'header' );
	$spock_header_wide = spock_get_theme_option( 'header_wide' );
	ob_start();
	if ( is_active_sidebar( $spock_header_name ) ) {
		dynamic_sidebar( $spock_header_name );
	}
	$spock_widgets_output = ob_get_contents();
	ob_end_clean();
	if ( ! empty( $spock_widgets_output ) ) {
		$spock_widgets_output = preg_replace( "/<\/aside>[\r\n\s]*<aside/", '</aside><aside', $spock_widgets_output );
		$spock_need_columns   = strpos( $spock_widgets_output, 'columns_wrap' ) === false;
		if ( $spock_need_columns ) {
			$spock_columns = max( 0, (int) spock_get_theme_option( 'header_columns' ) );
			if ( 0 == $spock_columns ) {
				$spock_columns = min( 6, max( 1, spock_tags_count( $spock_widgets_output, 'aside' ) ) );
			}
			if ( $spock_columns > 1 ) {
				$spock_widgets_output = preg_replace( '/<aside([^>]*)class="widget/', '<aside$1class="column-1_' . esc_attr( $spock_columns ) . ' widget', $spock_widgets_output );
			} else {
				$spock_need_columns = false;
			}
		}
		?>
		<div class="header_widgets_wrap widget_area<?php echo ! empty( $spock_header_wide ) ? ' header_fullwidth' : ' header_boxed'; ?>">
			<?php do_action( 'spock_action_before_sidebar_wrap', 'header' ); ?>
			<div class="header_widgets_inner widget_area_inner">
				<?php
				if ( ! $spock_header_wide ) {
					?>
					<div class="content_wrap">
					<?php
				}
				if ( $spock_need_columns ) {
					?>
					<div class="columns_wrap">
					<?php
				}
				do_action( 'spock_action_before_sidebar', 'header' );
				spock_show_layout( $spock_widgets_output );
				do_action( 'spock_action_after_sidebar', 'header' );
				if ( $spock_need_columns ) {
					?>
					</div>	<!-- /.columns_wrap -->
					<?php
				}
				if ( ! $spock_header_wide ) {
					?>
					</div>	<!-- /.content_wrap -->
					<?php
				}
				?>
			</div>	<!-- /.header_widgets_inner -->
			<?php do_action( 'spock_action_after_sidebar_wrap', 'header' ); ?>
		</div>	<!-- /.header_widgets_wrap -->
		<?php
	}
}

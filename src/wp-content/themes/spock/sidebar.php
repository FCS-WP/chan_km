<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package SPOCK
 * @since SPOCK 1.0
 */

if ( spock_sidebar_present() ) {
	
	$spock_sidebar_type = spock_get_theme_option( 'sidebar_type' );
	if ( 'custom' == $spock_sidebar_type && ! spock_is_layouts_available() ) {
		$spock_sidebar_type = 'default';
	}
	
	// Catch output to the buffer
	ob_start();
	if ( 'default' == $spock_sidebar_type ) {
		// Default sidebar with widgets
		$spock_sidebar_name = spock_get_theme_option( 'sidebar_widgets' );
		spock_storage_set( 'current_sidebar', 'sidebar' );
		if ( is_active_sidebar( $spock_sidebar_name ) ) {
			dynamic_sidebar( $spock_sidebar_name );
		}
	} else {
		// Custom sidebar from Layouts Builder
		$spock_sidebar_id = spock_get_custom_sidebar_id();
		do_action( 'spock_action_show_layout', $spock_sidebar_id );
	}
	$spock_out = trim( ob_get_contents() );
	ob_end_clean();
	
	// If any html is present - display it
	if ( ! empty( $spock_out ) ) {
		$spock_sidebar_position    = spock_get_theme_option( 'sidebar_position' );
		$spock_sidebar_position_ss = spock_get_theme_option( 'sidebar_position_ss', 'below' );
		?>
		<div class="sidebar widget_area
			<?php
			echo ' ' . esc_attr( $spock_sidebar_position );
			echo ' sidebar_' . esc_attr( $spock_sidebar_position_ss );
			echo ' sidebar_' . esc_attr( $spock_sidebar_type );

			$spock_sidebar_scheme = apply_filters( 'spock_filter_sidebar_scheme', spock_get_theme_option( 'sidebar_scheme', 'inherit' ) );
			if ( ! empty( $spock_sidebar_scheme ) && ! spock_is_inherit( $spock_sidebar_scheme ) && 'custom' != $spock_sidebar_type ) {
				echo ' scheme_' . esc_attr( $spock_sidebar_scheme );
			}
			?>
		" role="complementary">
			<?php

			// Skip link anchor to fast access to the sidebar from keyboard
			?>
			<a id="sidebar_skip_link_anchor" class="spock_skip_link_anchor" href="#"></a>
			<?php

			do_action( 'spock_action_before_sidebar_wrap', 'sidebar' );

			// Button to show/hide sidebar on mobile
			if ( in_array( $spock_sidebar_position_ss, array( 'above', 'float' ) ) ) {
				$spock_title = apply_filters( 'spock_filter_sidebar_control_title', 'float' == $spock_sidebar_position_ss ? esc_html__( 'Show Sidebar', 'spock' ) : '' );
				$spock_text  = apply_filters( 'spock_filter_sidebar_control_text', 'above' == $spock_sidebar_position_ss ? esc_html__( 'Show Sidebar', 'spock' ) : '' );
				?>
				<a href="#" class="sidebar_control" title="<?php echo esc_attr( $spock_title ); ?>"><?php echo esc_html( $spock_text ); ?></a>
				<?php
			}
			?>
			<div class="sidebar_inner">
				<?php
				do_action( 'spock_action_before_sidebar', 'sidebar' );
				spock_show_layout( preg_replace( "/<\/aside>[\r\n\s]*<aside/", '</aside><aside', $spock_out ) );
				do_action( 'spock_action_after_sidebar', 'sidebar' );
				?>
			</div>
			<?php

			do_action( 'spock_action_after_sidebar_wrap', 'sidebar' );

			?>
		</div>
		<div class="clearfix"></div>
		<?php
	}
}

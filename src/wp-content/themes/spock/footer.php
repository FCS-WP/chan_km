<?php
/**
 * The Footer: widgets area, logo, footer menu and socials
 *
 * @package SPOCK
 * @since SPOCK 1.0
 */

							do_action( 'spock_action_page_content_end_text' );
							
							// Widgets area below the content
							spock_create_widgets_area( 'widgets_below_content' );
						
							do_action( 'spock_action_page_content_end' );
							?>
						</div>
						<?php
						
						do_action( 'spock_action_after_page_content' );

						// Show main sidebar
						get_sidebar();

						do_action( 'spock_action_content_wrap_end' );
						?>
					</div>
					<?php

					do_action( 'spock_action_after_content_wrap' );

					// Widgets area below the page and related posts below the page
					$spock_body_style = spock_get_theme_option( 'body_style' );
					$spock_widgets_name = spock_get_theme_option( 'widgets_below_page', 'hide' );
					$spock_show_widgets = ! spock_is_off( $spock_widgets_name ) && is_active_sidebar( $spock_widgets_name );
					$spock_show_related = spock_is_single() && spock_get_theme_option( 'related_position', 'below_content' ) == 'below_page';
					if ( $spock_show_widgets || $spock_show_related ) {
						if ( 'fullscreen' != $spock_body_style ) {
							?>
							<div class="content_wrap">
							<?php
						}
						// Show related posts before footer
						if ( $spock_show_related ) {
							do_action( 'spock_action_related_posts' );
						}

						// Widgets area below page content
						if ( $spock_show_widgets ) {
							spock_create_widgets_area( 'widgets_below_page' );
						}
						if ( 'fullscreen' != $spock_body_style ) {
							?>
							</div>
							<?php
						}
					}
					do_action( 'spock_action_page_content_wrap_end' );
					?>
			</div>
			<?php
			do_action( 'spock_action_after_page_content_wrap' );

			// Don't display the footer elements while actions 'full_post_loading' and 'prev_post_loading'
			if ( ( ! spock_is_singular( 'post' ) && ! spock_is_singular( 'attachment' ) ) || ! in_array ( spock_get_value_gp( 'action' ), array( 'full_post_loading', 'prev_post_loading' ) ) ) {
				
				// Skip link anchor to fast access to the footer from keyboard
				?>
				<a id="footer_skip_link_anchor" class="spock_skip_link_anchor" href="#"></a>
				<?php

				do_action( 'spock_action_before_footer' );

				// Footer
				$spock_footer_type = spock_get_theme_option( 'footer_type' );
				if ( 'custom' == $spock_footer_type && ! spock_is_layouts_available() ) {
					$spock_footer_type = 'default';
				}
				get_template_part( apply_filters( 'spock_filter_get_template_part', "templates/footer-" . sanitize_file_name( $spock_footer_type ) ) );

				do_action( 'spock_action_after_footer' );

			}
			?>

			<?php do_action( 'spock_action_page_wrap_end' ); ?>

		</div>

		<?php do_action( 'spock_action_after_page_wrap' ); ?>

	</div>

	<?php do_action( 'spock_action_after_body' ); ?>

	<?php wp_footer(); ?>

</body>
</html>
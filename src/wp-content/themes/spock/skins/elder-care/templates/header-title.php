<?php
/**
 * The template to display the page title and breadcrumbs
 *
 * @package SPOCK
 * @since SPOCK 1.0
 */

// Page (category, tag, archive, author) title

if ( spock_need_page_title() ) {
	spock_sc_layouts_showed( 'title', true );
	spock_sc_layouts_showed( 'postmeta', true );
	?>
	<div class="top_panel_title sc_layouts_row sc_layouts_row_type_normal">
		<div class="content_wrap">
			<div class="sc_layouts_column sc_layouts_column_align_center">
				<div class="sc_layouts_item">
					<div class="sc_layouts_title sc_align_center">
						<?php
						// Post meta on the single post
						if ( is_single() ) {
							?>
							<div class="sc_layouts_title_meta">
							<?php
								spock_show_post_meta(
									apply_filters(
										'spock_filter_post_meta_args', array(
											'components' => join( ',', spock_array_get_keys_by_value( spock_get_theme_option( 'meta_parts' ) ) ),
											'counters'   => join( ',', spock_array_get_keys_by_value( spock_get_theme_option( 'counters' ) ) ),
											'seo'        => spock_is_on( spock_get_theme_option( 'seo_snippets' ) ),
										), 'header', 1
									)
								);
							?>
							</div>
							<?php
						}

						// Blog/Post title
						?>
						<div class="sc_layouts_title_title">
							<?php
							$spock_blog_title           = spock_get_blog_title();
							$spock_blog_title_text      = '';
							$spock_blog_title_class     = '';
							$spock_blog_title_link      = '';
							$spock_blog_title_link_text = '';
							if ( is_array( $spock_blog_title ) ) {
								$spock_blog_title_text      = $spock_blog_title['text'];
								$spock_blog_title_class     = ! empty( $spock_blog_title['class'] ) ? ' ' . $spock_blog_title['class'] : '';
								$spock_blog_title_link      = ! empty( $spock_blog_title['link'] ) ? $spock_blog_title['link'] : '';
								$spock_blog_title_link_text = ! empty( $spock_blog_title['link_text'] ) ? $spock_blog_title['link_text'] : '';
							} else {
								$spock_blog_title_text = $spock_blog_title;
							}
							?>
							<h1 itemprop="headline" class="sc_layouts_title_caption<?php echo esc_attr( $spock_blog_title_class ); ?>">
								<?php
								$spock_top_icon = spock_get_term_image_small();
								if ( ! empty( $spock_top_icon ) ) {
									$spock_attr = spock_getimagesize( $spock_top_icon );
									?>
									<img src="<?php echo esc_url( $spock_top_icon ); ?>" alt="<?php esc_attr_e( 'Site icon', 'spock' ); ?>"
										<?php
										if ( ! empty( $spock_attr[3] ) ) {
											spock_show_layout( $spock_attr[3] );
										}
										?>
									>
									<?php
								}
								echo wp_kses_data( $spock_blog_title_text );
								?>
							</h1>
							<?php
							if ( ! empty( $spock_blog_title_link ) && ! empty( $spock_blog_title_link_text ) ) {
								?>
								<a href="<?php echo esc_url( $spock_blog_title_link ); ?>" class="theme_button theme_button_small sc_layouts_title_link"><?php echo esc_html( $spock_blog_title_link_text ); ?></a>
								<?php
							}

							// Category/Tag description
							if ( ! is_paged() && ( is_category() || is_tag() || is_tax() ) ) {
								the_archive_description( '<div class="sc_layouts_title_description">', '</div>' );
							}

							?>
						</div>
						<?php

						// Breadcrumbs
						ob_start();
						do_action( 'spock_action_breadcrumbs' );
						$spock_breadcrumbs = ob_get_contents();
						ob_end_clean();
						spock_show_layout( $spock_breadcrumbs, '<div class="sc_layouts_title_breadcrumbs">', '</div>' );
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}

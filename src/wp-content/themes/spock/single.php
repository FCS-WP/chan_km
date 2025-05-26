<?php
/**
 * The template to display single post
 *
 * @package SPOCK
 * @since SPOCK 1.0
 */

// Full post loading
$full_post_loading          = spock_get_value_gp( 'action' ) == 'full_post_loading';

// Prev post loading
$prev_post_loading          = spock_get_value_gp( 'action' ) == 'prev_post_loading';
$prev_post_loading_type     = spock_get_theme_option( 'posts_navigation_scroll_which_block', 'article' );

// Position of the related posts
$spock_related_position   = spock_get_theme_option( 'related_position', 'below_content' );

// Type of the prev/next post navigation
$spock_posts_navigation   = spock_get_theme_option( 'posts_navigation' );
$spock_prev_post          = false;
$spock_prev_post_same_cat = (int)spock_get_theme_option( 'posts_navigation_scroll_same_cat', 1 );

// Rewrite style of the single post if current post loading via AJAX and featured image and title is not in the content
if ( ( $full_post_loading 
		|| 
		( $prev_post_loading && 'article' == $prev_post_loading_type )
	) 
	&& 
	! in_array( spock_get_theme_option( 'single_style' ), array( 'style-6' ) )
) {
	spock_storage_set_array( 'options_meta', 'single_style', 'style-6' );
}

do_action( 'spock_action_prev_post_loading', $prev_post_loading, $prev_post_loading_type );

get_header();

while ( have_posts() ) {

	the_post();

	// Type of the prev/next post navigation
	if ( 'scroll' == $spock_posts_navigation ) {
		$spock_prev_post = get_previous_post( $spock_prev_post_same_cat );  // Get post from same category
		if ( ! $spock_prev_post && $spock_prev_post_same_cat ) {
			$spock_prev_post = get_previous_post( false );                    // Get post from any category
		}
		if ( ! $spock_prev_post ) {
			$spock_posts_navigation = 'links';
		}
	}

	// Override some theme options to display featured image, title and post meta in the dynamic loaded posts
	if ( $full_post_loading || ( $prev_post_loading && $spock_prev_post ) ) {
		spock_sc_layouts_showed( 'featured', false );
		spock_sc_layouts_showed( 'title', false );
		spock_sc_layouts_showed( 'postmeta', false );
	}

	// If related posts should be inside the content
	if ( strpos( $spock_related_position, 'inside' ) === 0 ) {
		ob_start();
	}

	// Display post's content
	get_template_part( apply_filters( 'spock_filter_get_template_part', 'templates/content', 'single-' . spock_get_theme_option( 'single_style' ) ), 'single-' . spock_get_theme_option( 'single_style' ) );

	// If related posts should be inside the content
	if ( strpos( $spock_related_position, 'inside' ) === 0 ) {
		$spock_content = ob_get_contents();
		ob_end_clean();

		ob_start();
		do_action( 'spock_action_related_posts' );
		$spock_related_content = ob_get_contents();
		ob_end_clean();

		if ( ! empty( $spock_related_content ) ) {
			$spock_related_position_inside = max( 0, min( 9, spock_get_theme_option( 'related_position_inside' ) ) );
			if ( 0 == $spock_related_position_inside ) {
				$spock_related_position_inside = mt_rand( 1, 9 );
			}

			$spock_p_number         = 0;
			$spock_related_inserted = false;
			$spock_in_block         = false;
			$spock_content_start    = strpos( $spock_content, '<div class="post_content' );
			$spock_content_end      = strrpos( $spock_content, '</div>' );

			for ( $i = max( 0, $spock_content_start ); $i < min( strlen( $spock_content ) - 3, $spock_content_end ); $i++ ) {
				if ( $spock_content[ $i ] != '<' ) {
					continue;
				}
				if ( $spock_in_block ) {
					if ( strtolower( substr( $spock_content, $i + 1, 12 ) ) == '/blockquote>' ) {
						$spock_in_block = false;
						$i += 12;
					}
					continue;
				} else if ( strtolower( substr( $spock_content, $i + 1, 10 ) ) == 'blockquote' && in_array( $spock_content[ $i + 11 ], array( '>', ' ' ) ) ) {
					$spock_in_block = true;
					$i += 11;
					continue;
				} else if ( 'p' == $spock_content[ $i + 1 ] && in_array( $spock_content[ $i + 2 ], array( '>', ' ' ) ) ) {
					$spock_p_number++;
					if ( $spock_related_position_inside == $spock_p_number ) {
						$spock_related_inserted = true;
						$spock_content = ( $i > 0 ? substr( $spock_content, 0, $i ) : '' )
											. $spock_related_content
											. substr( $spock_content, $i );
					}
				}
			}
			if ( ! $spock_related_inserted ) {
				if ( $spock_content_end > 0 ) {
					$spock_content = substr( $spock_content, 0, $spock_content_end ) . $spock_related_content . substr( $spock_content, $spock_content_end );
				} else {
					$spock_content .= $spock_related_content;
				}
			}
		}

		spock_show_layout( $spock_content );
	}

	// Comments
	do_action( 'spock_action_before_comments' );
	comments_template();
	do_action( 'spock_action_after_comments' );

	// Related posts
	if ( 'below_content' == $spock_related_position
		&& ( 'scroll' != $spock_posts_navigation || (int)spock_get_theme_option( 'posts_navigation_scroll_hide_related', 0 ) == 0 )
		&& ( ! $full_post_loading || (int)spock_get_theme_option( 'open_full_post_hide_related', 1 ) == 0 )
	) {
		do_action( 'spock_action_related_posts' );
	}

	// Post navigation: type 'scroll'
	if ( 'scroll' == $spock_posts_navigation && ! $full_post_loading ) {
		?>
		<div class="nav-links-single-scroll"
			data-post-id="<?php echo esc_attr( get_the_ID( $spock_prev_post ) ); ?>"
			data-post-link="<?php echo esc_attr( get_permalink( $spock_prev_post ) ); ?>"
			data-post-title="<?php the_title_attribute( array( 'post' => $spock_prev_post ) ); ?>"
			data-cur-post-link="<?php echo esc_attr( get_permalink() ); ?>"
			data-cur-post-title="<?php the_title_attribute(); ?>"
			<?php do_action( 'spock_action_nav_links_single_scroll_data', $spock_prev_post ); ?>
		></div>
		<?php
	}
}

get_footer();

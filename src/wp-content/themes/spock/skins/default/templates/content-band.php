<?php
/**
 * 'Band' template to display the content
 *
 * Used for index/archive/search.
 *
 * @package SPOCK
 * @since SPOCK 1.71.0
 */

$spock_template_args = get_query_var( 'spock_template_args' );
if ( ! is_array( $spock_template_args ) ) {
	$spock_template_args = array(
								'type'    => 'band',
								'columns' => 1
								);
}

$spock_columns       = 1;

$spock_expanded      = ! spock_sidebar_present() && spock_get_theme_option( 'expand_content' ) == 'expand';

$spock_post_format   = get_post_format();
$spock_post_format   = empty( $spock_post_format ) ? 'standard' : str_replace( 'post-format-', '', $spock_post_format );

if ( is_array( $spock_template_args ) ) {
	$spock_columns    = empty( $spock_template_args['columns'] ) ? 1 : max( 1, $spock_template_args['columns'] );
	$spock_blog_style = array( $spock_template_args['type'], $spock_columns );
	if ( ! empty( $spock_template_args['slider'] ) ) {
		?><div class="slider-slide swiper-slide">
		<?php
	} elseif ( $spock_columns > 1 ) {
	    $spock_columns_class = spock_get_column_class( 1, $spock_columns, ! empty( $spock_template_args['columns_tablet']) ? $spock_template_args['columns_tablet'] : '', ! empty($spock_template_args['columns_mobile']) ? $spock_template_args['columns_mobile'] : '' );
				?><div class="<?php echo esc_attr( $spock_columns_class ); ?>"><?php
	}
}
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class( 'post_item post_item_container post_layout_band post_format_' . esc_attr( $spock_post_format ) );
	spock_add_blog_animation( $spock_template_args );
	?>
>
	<?php

	// Sticky label
	if ( is_sticky() && ! is_paged() ) {
		?>
		<span class="post_label label_sticky"></span>
		<?php
	}

	// Featured image
	$spock_hover      = ! empty( $spock_template_args['hover'] ) && ! spock_is_inherit( $spock_template_args['hover'] )
							? $spock_template_args['hover']
							: spock_get_theme_option( 'image_hover' );
	$spock_components = ! empty( $spock_template_args['meta_parts'] )
							? ( is_array( $spock_template_args['meta_parts'] )
								? $spock_template_args['meta_parts']
								: array_map( 'trim', explode( ',', $spock_template_args['meta_parts'] ) )
								)
							: spock_array_get_keys_by_value( spock_get_theme_option( 'meta_parts' ) );
	spock_show_post_featured( apply_filters( 'spock_filter_args_featured',
		array(
			'no_links'   => ! empty( $spock_template_args['no_links'] ),
			'hover'      => $spock_hover,
			'meta_parts' => $spock_components,
			'thumb_bg'   => true,
			'thumb_ratio'   => '1:1',
			'thumb_size' => ! empty( $spock_template_args['thumb_size'] )
								? $spock_template_args['thumb_size']
								: spock_get_thumb_size( 
								in_array( $spock_post_format, array( 'gallery', 'audio', 'video' ) )
									? ( strpos( spock_get_theme_option( 'body_style' ), 'full' ) !== false
										? 'full'
										: ( $spock_expanded 
											? 'big' 
											: 'medium-square'
											)
										)
									: 'masonry-big'
								)
		),
		'content-band',
		$spock_template_args
	) );

	?><div class="post_content_wrap"><?php

		// Title and post meta
		$spock_show_title = get_the_title() != '';
		$spock_show_meta  = count( $spock_components ) > 0 && ! in_array( $spock_hover, array( 'border', 'pull', 'slide', 'fade', 'info' ) );
		if ( $spock_show_title ) {
			?>
			<div class="post_header entry-header">
				<?php
				// Categories
				if ( apply_filters( 'spock_filter_show_blog_categories', $spock_show_meta && in_array( 'categories', $spock_components ), array( 'categories' ), 'band' ) ) {
					do_action( 'spock_action_before_post_category' );
					?>
					<div class="post_category">
						<?php
						spock_show_post_meta( apply_filters(
															'spock_filter_post_meta_args',
															array(
																'components' => 'categories',
																'seo'        => false,
																'echo'       => true,
																'cat_sep'    => false,
																),
															'hover_' . $spock_hover, 1
															)
											);
						?>
					</div>
					<?php
					$spock_components = spock_array_delete_by_value( $spock_components, 'categories' );
					do_action( 'spock_action_after_post_category' );
				}
				// Post title
				if ( apply_filters( 'spock_filter_show_blog_title', true, 'band' ) ) {
					do_action( 'spock_action_before_post_title' );
					if ( empty( $spock_template_args['no_links'] ) ) {
						the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );
					} else {
						the_title( '<h4 class="post_title entry-title">', '</h4>' );
					}
					do_action( 'spock_action_after_post_title' );
				}
				?>
			</div><!-- .post_header -->
			<?php
		}

		// Post content
		if ( ! isset( $spock_template_args['excerpt_length'] ) && ! in_array( $spock_post_format, array( 'gallery', 'audio', 'video' ) ) ) {
			$spock_template_args['excerpt_length'] = 13;
		}
		if ( apply_filters( 'spock_filter_show_blog_excerpt', empty( $spock_template_args['hide_excerpt'] ) && spock_get_theme_option( 'excerpt_length' ) > 0, 'band' ) ) {
			?>
			<div class="post_content entry-content">
				<?php
				// Post content area
				spock_show_post_content( $spock_template_args, '<div class="post_content_inner">', '</div>' );
				?>
			</div><!-- .entry-content -->
			<?php
		}
		// Post meta
		if ( apply_filters( 'spock_filter_show_blog_meta', $spock_show_meta, $spock_components, 'band' ) ) {
			if ( count( $spock_components ) > 0 ) {
				do_action( 'spock_action_before_post_meta' );
				spock_show_post_meta(
					apply_filters(
						'spock_filter_post_meta_args', array(
							'components' => join( ',', $spock_components ),
							'seo'        => false,
							'echo'       => true,
						), 'band', 1
					)
				);
				do_action( 'spock_action_after_post_meta' );
			}
		}
		// More button
		if ( apply_filters( 'spock_filter_show_blog_readmore', ! $spock_show_title || ! empty( $spock_template_args['more_button'] ), 'band' ) ) {
			if ( empty( $spock_template_args['no_links'] ) ) {
				do_action( 'spock_action_before_post_readmore' );
				spock_show_post_more_link( $spock_template_args, '<div class="more-wrap">', '</div>' );
				do_action( 'spock_action_after_post_readmore' );
			}
		}
		?>
	</div>
</article>
<?php

if ( is_array( $spock_template_args ) ) {
	if ( ! empty( $spock_template_args['slider'] ) || $spock_columns > 1 ) {
		?>
		</div>
		<?php
	}
}

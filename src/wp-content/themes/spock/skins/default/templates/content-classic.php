<?php
/**
 * The Classic template to display the content
 *
 * Used for index/archive/search.
 *
 * @package SPOCK
 * @since SPOCK 1.0
 */

$spock_template_args = get_query_var( 'spock_template_args' );

if ( is_array( $spock_template_args ) ) {
	$spock_columns    = empty( $spock_template_args['columns'] ) ? 2 : max( 1, $spock_template_args['columns'] );
	$spock_blog_style = array( $spock_template_args['type'], $spock_columns );
    $spock_columns_class = spock_get_column_class( 1, $spock_columns, ! empty( $spock_template_args['columns_tablet']) ? $spock_template_args['columns_tablet'] : '', ! empty($spock_template_args['columns_mobile']) ? $spock_template_args['columns_mobile'] : '' );
} else {
	$spock_template_args = array();
	$spock_blog_style = explode( '_', spock_get_theme_option( 'blog_style' ) );
	$spock_columns    = empty( $spock_blog_style[1] ) ? 2 : max( 1, $spock_blog_style[1] );
    $spock_columns_class = spock_get_column_class( 1, $spock_columns );
}
$spock_expanded   = ! spock_sidebar_present() && spock_get_theme_option( 'expand_content' ) == 'expand';

$spock_post_format = get_post_format();
$spock_post_format = empty( $spock_post_format ) ? 'standard' : str_replace( 'post-format-', '', $spock_post_format );

?><div class="<?php
	if ( ! empty( $spock_template_args['slider'] ) ) {
		echo ' slider-slide swiper-slide';
	} else {
		echo ( spock_is_blog_style_use_masonry( $spock_blog_style[0] ) ? 'masonry_item masonry_item-1_' . esc_attr( $spock_columns ) : esc_attr( $spock_columns_class ) );
	}
?>"><article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class(
		'post_item post_item_container post_format_' . esc_attr( $spock_post_format )
				. ' post_layout_classic post_layout_classic_' . esc_attr( $spock_columns )
				. ' post_layout_' . esc_attr( $spock_blog_style[0] )
				. ' post_layout_' . esc_attr( $spock_blog_style[0] ) . '_' . esc_attr( $spock_columns )
	);
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
								: explode( ',', $spock_template_args['meta_parts'] )
								)
							: spock_array_get_keys_by_value( spock_get_theme_option( 'meta_parts' ) );

	spock_show_post_featured( apply_filters( 'spock_filter_args_featured',
		array(
			'thumb_size' => ! empty( $spock_template_args['thumb_size'] )
				? $spock_template_args['thumb_size']
				: spock_get_thumb_size(
					'classic' == $spock_blog_style[0]
						? ( strpos( spock_get_theme_option( 'body_style' ), 'full' ) !== false
								? ( $spock_columns > 2 ? 'big' : 'huge' )
								: ( $spock_columns > 2
									? ( $spock_expanded ? 'square' : 'square' )
									: ($spock_columns > 1 ? 'square' : ( $spock_expanded ? 'huge' : 'big' ))
									)
							)
						: ( strpos( spock_get_theme_option( 'body_style' ), 'full' ) !== false
								? ( $spock_columns > 2 ? 'masonry-big' : 'full' )
								: ($spock_columns === 1 ? ( $spock_expanded ? 'huge' : 'big' ) : ( $spock_columns <= 2 && $spock_expanded ? 'masonry-big' : 'masonry' ))
							)
			),
			'hover'      => $spock_hover,
			'meta_parts' => $spock_components,
			'no_links'   => ! empty( $spock_template_args['no_links'] ),
        ),
        'content-classic',
        $spock_template_args
    ) );

	// Title and post meta
	$spock_show_title = get_the_title() != '';
	$spock_show_meta  = count( $spock_components ) > 0 && ! in_array( $spock_hover, array( 'border', 'pull', 'slide', 'fade', 'info' ) );

	if ( $spock_show_title ) {
		?>
		<div class="post_header entry-header">
			<?php

			// Post meta
			if ( apply_filters( 'spock_filter_show_blog_meta', $spock_show_meta, $spock_components, 'classic' ) ) {
				if ( count( $spock_components ) > 0 ) {
					do_action( 'spock_action_before_post_meta' );
					spock_show_post_meta(
						apply_filters(
							'spock_filter_post_meta_args', array(
							'components' => join( ',', $spock_components ),
							'seo'        => false,
							'echo'       => true,
						), $spock_blog_style[0], $spock_columns
						)
					);
					do_action( 'spock_action_after_post_meta' );
				}
			}

			// Post title
			if ( apply_filters( 'spock_filter_show_blog_title', true, 'classic' ) ) {
				do_action( 'spock_action_before_post_title' );
				if ( empty( $spock_template_args['no_links'] ) ) {
					the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );
				} else {
					the_title( '<h4 class="post_title entry-title">', '</h4>' );
				}
				do_action( 'spock_action_after_post_title' );
			}

			if( !in_array( $spock_post_format, array( 'quote', 'aside', 'link', 'status' ) ) ) {
				// More button
				if ( apply_filters( 'spock_filter_show_blog_readmore', ! $spock_show_title || ! empty( $spock_template_args['more_button'] ), 'classic' ) ) {
					if ( empty( $spock_template_args['no_links'] ) ) {
						do_action( 'spock_action_before_post_readmore' );
						spock_show_post_more_link( $spock_template_args, '<div class="more-wrap">', '</div>' );
						do_action( 'spock_action_after_post_readmore' );
					}
				}
			}
			?>
		</div><!-- .entry-header -->
		<?php
	}

	// Post content
	if( in_array( $spock_post_format, array( 'quote', 'aside', 'link', 'status' ) ) ) {
		ob_start();
		if (apply_filters('spock_filter_show_blog_excerpt', empty($spock_template_args['hide_excerpt']) && spock_get_theme_option('excerpt_length') > 0, 'classic')) {
			spock_show_post_content($spock_template_args, '<div class="post_content_inner">', '</div>');
		}
		// More button
		if(! empty( $spock_template_args['more_button'] )) {
			if ( empty( $spock_template_args['no_links'] ) ) {
				do_action( 'spock_action_before_post_readmore' );
				spock_show_post_more_link( $spock_template_args, '<div class="more-wrap">', '</div>' );
				do_action( 'spock_action_after_post_readmore' );
			}
		}
		$spock_content = ob_get_contents();
		ob_end_clean();
		spock_show_layout($spock_content, '<div class="post_content entry-content">', '</div><!-- .entry-content -->');
	}
	?>

</article></div><?php
// Need opening PHP-tag above, because <div> is a inline-block element (used as column)!

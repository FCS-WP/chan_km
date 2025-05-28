<?php
/**
 * The Portfolio template to display the content
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

$spock_post_format = get_post_format();
$spock_post_format = empty( $spock_post_format ) ? 'standard' : str_replace( 'post-format-', '', $spock_post_format );

?><div class="
<?php
if ( ! empty( $spock_template_args['slider'] ) ) {
	echo ' slider-slide swiper-slide';
} else {
	echo ( spock_is_blog_style_use_masonry( $spock_blog_style[0] ) ? 'masonry_item masonry_item-1_' . esc_attr( $spock_columns ) : esc_attr( $spock_columns_class ));
}
?>
"><article id="post-<?php the_ID(); ?>" 
	<?php
	post_class(
		'post_item post_item_container post_format_' . esc_attr( $spock_post_format )
		. ' post_layout_portfolio'
		. ' post_layout_portfolio_' . esc_attr( $spock_columns )
		. ( 'portfolio' != $spock_blog_style[0] ? ' ' . esc_attr( $spock_blog_style[0] )  . '_' . esc_attr( $spock_columns ) : '' )
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

	$spock_hover   = ! empty( $spock_template_args['hover'] ) && ! spock_is_inherit( $spock_template_args['hover'] )
								? $spock_template_args['hover']
								: spock_get_theme_option( 'image_hover' );

	if ( 'dots' == $spock_hover ) {
		$spock_post_link = empty( $spock_template_args['no_links'] )
								? ( ! empty( $spock_template_args['link'] )
									? $spock_template_args['link']
									: get_permalink()
									)
								: '';
		$spock_target    = ! empty( $spock_post_link ) && spock_is_external_url( $spock_post_link )
								? ' target="_blank" rel="nofollow"'
								: '';
	}
	
	// Meta parts
	$spock_components = ! empty( $spock_template_args['meta_parts'] )
							? ( is_array( $spock_template_args['meta_parts'] )
								? $spock_template_args['meta_parts']
								: explode( ',', $spock_template_args['meta_parts'] )
								)
							: spock_array_get_keys_by_value( spock_get_theme_option( 'meta_parts' ) );

	// Featured image
	spock_show_post_featured( apply_filters( 'spock_filter_args_featured',
        array(
			'hover'         => $spock_hover,
			'no_links'      => ! empty( $spock_template_args['no_links'] ),
			'thumb_size'    => ! empty( $spock_template_args['thumb_size'] )
								? $spock_template_args['thumb_size']
								: spock_get_thumb_size(
									spock_is_blog_style_use_masonry( $spock_blog_style[0] )
										? (	strpos( spock_get_theme_option( 'body_style' ), 'full' ) !== false || $spock_columns < 3
											? 'masonry-big'
											: 'masonry'
											)
										: (	strpos( spock_get_theme_option( 'body_style' ), 'full' ) !== false || $spock_columns < 3
											? 'square'
											: 'square'
											)
								),
			'thumb_bg' => spock_is_blog_style_use_masonry( $spock_blog_style[0] ) ? false : true,
			'show_no_image' => true,
			'meta_parts'    => $spock_components,
			'class'         => 'dots' == $spock_hover ? 'hover_with_info' : '',
			'post_info'     => 'dots' == $spock_hover
										? '<div class="post_info"><h5 class="post_title">'
											. ( ! empty( $spock_post_link )
												? '<a href="' . esc_url( $spock_post_link ) . '"' . ( ! empty( $target ) ? $target : '' ) . '>'
												: ''
												)
												. esc_html( get_the_title() ) 
											. ( ! empty( $spock_post_link )
												? '</a>'
												: ''
												)
											. '</h5></div>'
										: '',
            'thumb_ratio'   => 'info' == $spock_hover ?  '100:102' : '',
        ),
        'content-portfolio',
        $spock_template_args
    ) );
	?>
</article></div><?php
// Need opening PHP-tag above, because <article> is a inline-block element (used as column)!
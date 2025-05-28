<?php
/**
 * The custom template to display the content
 *
 * Used for index/archive/search.
 *
 * @package SPOCK
 * @since SPOCK 1.0.50
 */

$spock_template_args = get_query_var( 'spock_template_args' );
if ( is_array( $spock_template_args ) ) {
	$spock_columns    = empty( $spock_template_args['columns'] ) ? 2 : max( 1, $spock_template_args['columns'] );
	$spock_blog_style = array( $spock_template_args['type'], $spock_columns );
} else {
	$spock_template_args = array();
	$spock_blog_style = explode( '_', spock_get_theme_option( 'blog_style' ) );
	$spock_columns    = empty( $spock_blog_style[1] ) ? 2 : max( 1, $spock_blog_style[1] );
}
$spock_blog_id       = spock_get_custom_blog_id( join( '_', $spock_blog_style ) );
$spock_blog_style[0] = str_replace( 'blog-custom-', '', $spock_blog_style[0] );
$spock_expanded      = ! spock_sidebar_present() && spock_get_theme_option( 'expand_content' ) == 'expand';
$spock_components    = ! empty( $spock_template_args['meta_parts'] )
							? ( is_array( $spock_template_args['meta_parts'] )
								? join( ',', $spock_template_args['meta_parts'] )
								: $spock_template_args['meta_parts']
								)
							: spock_array_get_keys_by_value( spock_get_theme_option( 'meta_parts' ) );
$spock_post_format   = get_post_format();
$spock_post_format   = empty( $spock_post_format ) ? 'standard' : str_replace( 'post-format-', '', $spock_post_format );

$spock_blog_meta     = spock_get_custom_layout_meta( $spock_blog_id );
$spock_custom_style  = ! empty( $spock_blog_meta['scripts_required'] ) ? $spock_blog_meta['scripts_required'] : 'none';

if ( ! empty( $spock_template_args['slider'] ) || $spock_columns > 1 || ! spock_is_off( $spock_custom_style ) ) {
	?><div class="
		<?php
		if ( ! empty( $spock_template_args['slider'] ) ) {
			echo 'slider-slide swiper-slide';
		} else {
			echo esc_attr( ( spock_is_off( $spock_custom_style ) ? 'column' : sprintf( '%1$s_item %1$s_item', $spock_custom_style ) ) . "-1_{$spock_columns}" );
		}
		?>
	">
	<?php
}
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class(
			'post_item post_item_container post_format_' . esc_attr( $spock_post_format )
					. ' post_layout_custom post_layout_custom_' . esc_attr( $spock_columns )
					. ' post_layout_' . esc_attr( $spock_blog_style[0] )
					. ' post_layout_' . esc_attr( $spock_blog_style[0] ) . '_' . esc_attr( $spock_columns )
					. ( ! spock_is_off( $spock_custom_style )
						? ' post_layout_' . esc_attr( $spock_custom_style )
							. ' post_layout_' . esc_attr( $spock_custom_style ) . '_' . esc_attr( $spock_columns )
						: ''
						)
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
	// Custom layout
	do_action( 'spock_action_show_layout', $spock_blog_id, get_the_ID() );
	?>
</article><?php
if ( ! empty( $spock_template_args['slider'] ) || $spock_columns > 1 || ! spock_is_off( $spock_custom_style ) ) {
	?></div><?php
	// Need opening PHP-tag above just after </div>, because <div> is a inline-block element (used as column)!
}

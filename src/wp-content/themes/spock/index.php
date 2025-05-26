<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: //codex.wordpress.org/Template_Hierarchy
 *
 * @package SPOCK
 * @since SPOCK 1.0
 */

$spock_template = apply_filters( 'spock_filter_get_template_part', spock_blog_archive_get_template() );

if ( ! empty( $spock_template ) && 'index' != $spock_template ) {

	get_template_part( $spock_template );

} else {

	spock_storage_set( 'blog_archive', true );

	get_header();

	if ( have_posts() ) {

		// Query params
		$spock_stickies   = is_home()
								|| ( in_array( spock_get_theme_option( 'post_type' ), array( '', 'post' ) )
									&& (int) spock_get_theme_option( 'parent_cat' ) == 0
									)
										? get_option( 'sticky_posts' )
										: false;
		$spock_post_type  = spock_get_theme_option( 'post_type' );
		$spock_args       = array(
								'blog_style'     => spock_get_theme_option( 'blog_style' ),
								'post_type'      => $spock_post_type,
								'taxonomy'       => spock_get_post_type_taxonomy( $spock_post_type ),
								'parent_cat'     => spock_get_theme_option( 'parent_cat' ),
								'posts_per_page' => spock_get_theme_option( 'posts_per_page' ),
								'sticky'         => spock_get_theme_option( 'sticky_style', 'inherit' ) == 'columns'
															&& is_array( $spock_stickies )
															&& count( $spock_stickies ) > 0
															&& get_query_var( 'paged' ) < 1
								);

		spock_blog_archive_start();

		do_action( 'spock_action_blog_archive_start' );

		if ( is_author() ) {
			do_action( 'spock_action_before_page_author' );
			get_template_part( apply_filters( 'spock_filter_get_template_part', 'templates/author-page' ) );
			do_action( 'spock_action_after_page_author' );
		}

		if ( spock_get_theme_option( 'show_filters', 0 ) ) {
			do_action( 'spock_action_before_page_filters' );
			spock_show_filters( $spock_args );
			do_action( 'spock_action_after_page_filters' );
		} else {
			do_action( 'spock_action_before_page_posts' );
			spock_show_posts( array_merge( $spock_args, array( 'cat' => $spock_args['parent_cat'] ) ) );
			do_action( 'spock_action_after_page_posts' );
		}

		do_action( 'spock_action_blog_archive_end' );

		spock_blog_archive_end();

	} else {

		if ( is_search() ) {
			get_template_part( apply_filters( 'spock_filter_get_template_part', 'templates/content', 'none-search' ), 'none-search' );
		} else {
			get_template_part( apply_filters( 'spock_filter_get_template_part', 'templates/content', 'none-archive' ), 'none-archive' );
		}
	}

	get_footer();
}

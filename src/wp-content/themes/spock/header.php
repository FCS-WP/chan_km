<?php
/**
 * The Header: Logo and main menu
 *
 * @package SPOCK
 * @since SPOCK 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js<?php
	// Class scheme_xxx need in the <html> as context for the <body>!
	echo ' scheme_' . esc_attr( spock_get_theme_option( 'color_scheme' ) );
?>">

<head>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	} else {
		do_action( 'wp_body_open' );
	}
	do_action( 'spock_action_before_body' );
	?>

	<div class="<?php echo esc_attr( apply_filters( 'spock_filter_body_wrap_class', 'body_wrap' ) ); ?>" <?php do_action('spock_action_body_wrap_attributes'); ?>>

		<?php do_action( 'spock_action_before_page_wrap' ); ?>

		<div class="<?php echo esc_attr( apply_filters( 'spock_filter_page_wrap_class', 'page_wrap' ) ); ?>" <?php do_action('spock_action_page_wrap_attributes'); ?>>

			<?php do_action( 'spock_action_page_wrap_start' ); ?>

			<?php
			$spock_full_post_loading = ( spock_is_singular( 'post' ) || spock_is_singular( 'attachment' ) ) && spock_get_value_gp( 'action' ) == 'full_post_loading';
			$spock_prev_post_loading = ( spock_is_singular( 'post' ) || spock_is_singular( 'attachment' ) ) && spock_get_value_gp( 'action' ) == 'prev_post_loading';

			// Don't display the header elements while actions 'full_post_loading' and 'prev_post_loading'
			if ( ! $spock_full_post_loading && ! $spock_prev_post_loading ) {

				// Short links to fast access to the content, sidebar and footer from the keyboard
				?>
				<a class="spock_skip_link skip_to_content_link" href="#content_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'spock_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to content", 'spock' ); ?></a>
				<?php if ( spock_sidebar_present() ) { ?>
				<a class="spock_skip_link skip_to_sidebar_link" href="#sidebar_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'spock_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to sidebar", 'spock' ); ?></a>
				<?php } ?>
				<a class="spock_skip_link skip_to_footer_link" href="#footer_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'spock_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to footer", 'spock' ); ?></a>

				<?php
				do_action( 'spock_action_before_header' );

				// Header
				$spock_header_type = spock_get_theme_option( 'header_type' );
				if ( 'custom' == $spock_header_type && ! spock_is_layouts_available() ) {
					$spock_header_type = 'default';
				}
				get_template_part( apply_filters( 'spock_filter_get_template_part', "templates/header-" . sanitize_file_name( $spock_header_type ) ) );

				// Side menu
				if ( in_array( spock_get_theme_option( 'menu_side', 'none' ), array( 'left', 'right' ) ) ) {
					get_template_part( apply_filters( 'spock_filter_get_template_part', 'templates/header-navi-side' ) );
				}

				// Mobile menu
				if ( apply_filters( 'spock_filter_use_navi_mobile', true ) ) {
					get_template_part( apply_filters( 'spock_filter_get_template_part', 'templates/header-navi-mobile' ) );
				}

				do_action( 'spock_action_after_header' );

			}
			?>

			<?php do_action( 'spock_action_before_page_content_wrap' ); ?>

			<div class="page_content_wrap<?php
				if ( spock_is_off( spock_get_theme_option( 'remove_margins' ) ) ) {
					if ( empty( $spock_header_type ) ) {
						$spock_header_type = spock_get_theme_option( 'header_type' );
					}
					if ( 'custom' == $spock_header_type && spock_is_layouts_available() ) {
						$spock_header_id = spock_get_custom_header_id();
						if ( $spock_header_id > 0 ) {
							$spock_header_meta = spock_get_custom_layout_meta( $spock_header_id );
							if ( ! empty( $spock_header_meta['margin'] ) ) {
								?> page_content_wrap_custom_header_margin<?php
							}
						}
					}
					$spock_footer_type = spock_get_theme_option( 'footer_type' );
					if ( 'custom' == $spock_footer_type && spock_is_layouts_available() ) {
						$spock_footer_id = spock_get_custom_footer_id();
						if ( $spock_footer_id ) {
							$spock_footer_meta = spock_get_custom_layout_meta( $spock_footer_id );
							if ( ! empty( $spock_footer_meta['margin'] ) ) {
								?> page_content_wrap_custom_footer_margin<?php
							}
						}
					}
				}
				do_action( 'spock_action_page_content_wrap_class', $spock_prev_post_loading );
				?>"<?php
				if ( apply_filters( 'spock_filter_is_prev_post_loading', $spock_prev_post_loading ) ) {
					?> data-single-style="<?php echo esc_attr( spock_get_theme_option( 'single_style' ) ); ?>"<?php
				}
				do_action( 'spock_action_page_content_wrap_data', $spock_prev_post_loading );
			?>>
				<?php
				do_action( 'spock_action_page_content_wrap', $spock_full_post_loading || $spock_prev_post_loading );

				// Single posts banner
				if ( apply_filters( 'spock_filter_single_post_header', spock_is_singular( 'post' ) || spock_is_singular( 'attachment' ) ) ) {
					if ( $spock_prev_post_loading ) {
						if ( spock_get_theme_option( 'posts_navigation_scroll_which_block', 'article' ) != 'article' ) {
							do_action( 'spock_action_between_posts' );
						}
					}
					// Single post thumbnail and title
					$spock_path = apply_filters( 'spock_filter_get_template_part', 'templates/single-styles/' . spock_get_theme_option( 'single_style' ) );
					if ( spock_get_file_dir( $spock_path . '.php' ) != '' ) {
						get_template_part( $spock_path );
					}
				}

				// Widgets area above page
				$spock_body_style   = spock_get_theme_option( 'body_style' );
				$spock_widgets_name = spock_get_theme_option( 'widgets_above_page', 'hide' );
				$spock_show_widgets = ! spock_is_off( $spock_widgets_name ) && is_active_sidebar( $spock_widgets_name );
				if ( $spock_show_widgets ) {
					if ( 'fullscreen' != $spock_body_style ) {
						?>
						<div class="content_wrap">
							<?php
					}
					spock_create_widgets_area( 'widgets_above_page' );
					if ( 'fullscreen' != $spock_body_style ) {
						?>
						</div>
						<?php
					}
				}

				// Content area
				do_action( 'spock_action_before_content_wrap' );
				?>
				<div class="content_wrap<?php echo 'fullscreen' == $spock_body_style ? '_fullscreen' : ''; ?>">

					<?php do_action( 'spock_action_content_wrap_start' ); ?>

					<div class="content">
						<?php
						do_action( 'spock_action_page_content_start' );

						// Skip link anchor to fast access to the content from keyboard
						?>
						<a id="content_skip_link_anchor" class="spock_skip_link_anchor" href="#"></a>
						<?php
						// Single posts banner between prev/next posts
						if ( ( spock_is_singular( 'post' ) || spock_is_singular( 'attachment' ) )
							&& $spock_prev_post_loading 
							&& spock_get_theme_option( 'posts_navigation_scroll_which_block', 'article' ) == 'article'
						) {
							do_action( 'spock_action_between_posts' );
						}

						// Widgets area above content
						spock_create_widgets_area( 'widgets_above_content' );

						do_action( 'spock_action_page_content_start_text' );

<?php
/**
 * Theme functions: init, enqueue scripts and styles, include required files and widgets
 *
 * @package SPOCK
 * @since SPOCK 1.0
 */

if ( ! defined( 'SPOCK_THEME_DIR' ) ) {
	define( 'SPOCK_THEME_DIR', trailingslashit( get_template_directory() ) );
}
if ( ! defined( 'SPOCK_THEME_URL' ) ) {
	define( 'SPOCK_THEME_URL', trailingslashit( get_template_directory_uri() ) );
}
if ( ! defined( 'SPOCK_CHILD_DIR' ) ) {
	define( 'SPOCK_CHILD_DIR', trailingslashit( get_stylesheet_directory() ) );
}
if ( ! defined( 'SPOCK_CHILD_URL' ) ) {
	define( 'SPOCK_CHILD_URL', trailingslashit( get_stylesheet_directory_uri() ) );
}

//-------------------------------------------------------
//-- Theme init
//-------------------------------------------------------

if ( ! function_exists( 'spock_theme_setup1' ) ) {
	add_action( 'after_setup_theme', 'spock_theme_setup1', 1 );
	/**
	 * Load a text domain before all other actions.
	 *
	 * Theme-specific init actions order:
	 *
	 * Action 'after_setup_theme':
	 *
	 * 1 - register filters to add/remove items to the lists used in the Theme Options
	 *
	 * 2 - create the Theme Options
	 *
	 * 3 - add/remove elements to the Theme Options
	 *
	 * 5 - load the Theme Options. Attention! After this step you can use only basic options (not overriden options)
	 *
	 * 9 - register other filters (for installer, etc.)
	 *
	 * 10 - all other (standard) Theme init procedures (not ordered)
	 *
	 * Action 'wp_loaded'
	 *
	 * 1 - detect an override mode. Attention! Only after this step you can use overriden options
	 *     (separate values for the Blog, Shop, Team, Courses, etc.)
	 */
	function spock_theme_setup1() {
		// Make theme available for translation
		// Translations can be filed in the /languages directory
		// Attention! Translations must be loaded before first call any translation functions!
		load_theme_textdomain( 'spock', spock_get_folder_dir( 'languages' ) );
	}
}

if ( ! function_exists( 'spock_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'spock_theme_setup9', 9 );
	/**
	 * A general theme setup: add a theme supports, navigation menus, hooks for other actions and filters.
	 */
	function spock_theme_setup9() {

		// Set theme content width
		$GLOBALS['content_width'] = apply_filters( 'spock_filter_content_width', (int)spock_get_theme_option( 'page_width' ) );

		// Theme support '-full' versions of styles and scripts (used in the editors)
		add_theme_support( 'styles-and-scripts-full-merged' );
		
		// Allow external updtates
		if ( SPOCK_THEME_ALLOW_UPDATE ) {
			add_theme_support( 'theme-updates-allowed' );
		}

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		// Custom header setup
		add_theme_support( 'custom-header',
			array(
				'header-text' => false,
				'video'       => true,
			)
		);

		// Custom logo
		add_theme_support( 'custom-logo',
			array(
				'width'       => 250,
				'height'      => 60,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
		// Custom backgrounds setup
		add_theme_support( 'custom-background', array() );

		// Partial refresh support in the Customize
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Supported posts formats
		add_theme_support( 'post-formats', array( 'gallery', 'video', 'audio', 'link', 'quote', 'image', 'status', 'aside', 'chat' ) );

		// Autogenerate title tag
		add_theme_support( 'title-tag' );

		// Add theme menus
		add_theme_support( 'nav-menus' );

		// Switch default markup for search form, comment form, and comments to output valid HTML5.
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

		// Register navigation menu
		register_nav_menus( apply_filters( 'spock_filter_register_nav_menus', array(
			'menu_main'   => esc_html__( 'Main Menu', 'spock' ),
			'menu_mobile' => esc_html__( 'Mobile Menu', 'spock' ),
			'menu_footer' => esc_html__( 'Footer Menu', 'spock' ),
		) ) );

		// Register theme-specific thumb sizes
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 370, 0, false );
		$thumb_sizes = spock_storage_get( 'theme_thumbs' );
		$mult        = spock_get_theme_option( 'retina_ready', 1 );
		if ( $mult > 1 ) {
			$GLOBALS['content_width'] = apply_filters( 'spock_filter_content_width', (int)spock_get_theme_option( 'page_width' ) * $mult );
		}
		foreach ( $thumb_sizes as $k => $v ) {
			add_image_size( $k, $v['size'][0], $v['size'][1], $v['size'][2] );
			if ( $mult > 1 ) {
				add_image_size( $k . '-@retina', $v['size'][0] * $mult, $v['size'][1] * $mult, $v['size'][2] );
			}
		}
		// Add new thumb names
		add_filter( 'image_size_names_choose', 'spock_theme_thumbs_sizes' );

		// Excerpt filters
		add_filter( 'excerpt_length', 'spock_excerpt_length' );
		add_filter( 'excerpt_more', 'spock_excerpt_more' );

		// Comment form
		add_filter( 'comment_form_fields', 'spock_comment_form_fields' );
		add_filter( 'comment_form_fields', 'spock_comment_form_agree', 11 );

		// Add required meta tags in the head
		add_action( 'wp_head', 'spock_wp_head', 0 );

		// Load current page/post customization (if present)
		add_action( 'wp_footer', 'spock_wp_footer' );
		add_action( 'admin_footer', 'spock_wp_footer' );

		// Enqueue scripts and styles for the frontend
		add_action( 'wp_enqueue_scripts', 'spock_load_theme_fonts', 0 );
		add_action( 'wp_enqueue_scripts', 'spock_load_theme_icons', 0 );
		add_action( 'wp_enqueue_scripts', 'spock_wp_styles', 1000 );                  // priority 1000 - load main theme styles
		add_action( 'wp_enqueue_scripts', 'spock_wp_styles_single', 1020);            // priority 1020 - load styles of single posts
		add_action( 'wp_enqueue_scripts', 'spock_wp_styles_plugins', 1100 );          // priority 1100 - load styles of the supported plugins
		add_action( 'wp_enqueue_scripts', 'spock_wp_styles_custom', 1200 );           // priority 1200 - load styles with custom fonts and colors
		add_action( 'wp_enqueue_scripts', 'spock_wp_styles_child', 1500 );            // priority 1500 - load styles of the child theme
		add_action( 'wp_enqueue_scripts', 'spock_wp_styles_responsive', 2000 );       // priority 2000 - load responsive styles after all other styles
		add_action( 'wp_enqueue_scripts', 'spock_wp_styles_single_responsive', 2020); // priority 2020 - load responsive styles of single posts after all other styles
		add_action( 'wp_enqueue_scripts', 'spock_wp_styles_responsive_child', 2500);  // priority 2500 - load responsive styles of the child theme after all other responsive styles

		// Enqueue scripts for the frontend
		add_action( 'wp_enqueue_scripts', 'spock_wp_scripts', 1000 );                 // priority 1000 - load main theme scripts
		add_action( 'wp_footer', 'spock_localize_scripts' );

		// Add body classes
		add_filter( 'body_class', 'spock_add_body_classes' );

		// Register sidebars
		add_action( 'widgets_init', 'spock_register_sidebars' );
	}
}


//-------------------------------------------------------
//-- Theme styles
//-------------------------------------------------------

if ( ! function_exists( 'spock_theme_fonts' ) ) {
	/**
	 * Load a theme-specific fonts at priority 0, because the font styles must be loaded before a main stylesheet.
	 *
	 * Hooks: add_action('wp_enqueue_scripts', 'spock_load_theme_fonts', 0);
	 */
	function spock_load_theme_fonts() {
		$links = spock_theme_fonts_links();
		if ( count( $links ) > 0 ) {
			foreach ( $links as $slug => $link ) {
				wp_enqueue_style( sprintf( 'spock-font-%s', $slug ), $link, array(), null );
			}
		}
	}
}

if ( ! function_exists( 'spock_load_theme_icons' ) ) {
	/**
	 * Load a theme-specific font icons at priority 0, because the icon styles must be loaded before a main stylesheet.
	 *
	 * Hooks: add_action('wp_enqueue_scripts', 'spock_load_theme_icons', 0);
	 */
	function spock_load_theme_icons() {
		// This style NEED the theme prefix, because style 'fontello' in some plugin contain different set of characters
		// and can't be used instead this style!
		wp_enqueue_style( 'spock-fontello', spock_get_file_url( 'css/font-icons/css/fontello.css' ), array(), null );
	}
}

if ( ! function_exists( 'spock_wp_styles' ) ) {
	/**
	 * Load a main theme styles for the frontend.
	 *
	 * Hooks: add_action('wp_enqueue_scripts', 'spock_wp_styles', 1000);
	 */
	function spock_wp_styles() {

		// Load main stylesheet
		$main_stylesheet = SPOCK_THEME_URL . 'style.css';
		wp_enqueue_style( 'spock-style', $main_stylesheet, array(), null );

		// Add custom bg image
		$bg_image = spock_remove_protocol_from_url( spock_get_theme_option( 'front_page_bg_image' ), false );
		if ( is_front_page() && ! empty( $bg_image ) && spock_is_on( spock_get_theme_option( 'front_page_enabled', false ) ) ) {
			// Add custom bg image for the Front page
			spock_add_inline_css( 'body.frontpage, body.home-page, body.home { background-image:url(' . esc_url( $bg_image ) . ') !important }' );
		} else {
			// Add custom bg image for the body_style == 'boxed'
			$bg_image = spock_get_theme_option( 'boxed_bg_image' );
			if ( ! empty( $bg_image ) && ( spock_get_theme_option( 'body_style' ) == 'boxed' || is_customize_preview() ) ) {
				spock_add_inline_css( '.body_style_boxed { background-image:url(' . esc_url( spock_clear_thumb_size( $bg_image ) ) . ') !important }' );
			}
		}

		// Add post nav background
		spock_add_bg_in_post_nav();
	}
}

if ( ! function_exists( 'spock_wp_styles_single' ) ) {
	/**
	 * Load styles for single posts.
	 *
	 * Hooks: add_action('wp_enqueue_scripts', 'spock_wp_styles_single', 1020);
	 */
	function spock_wp_styles_single() {
		if ( apply_filters( 'spock_filters_separate_single_styles', false )
			&& apply_filters( 'spock_filters_load_single_styles',
								spock_is_single()
								|| spock_is_singular( 'attachment' )
								|| (int)spock_get_theme_option( 'open_full_post_in_blog', 0 ) > 0
								|| ( spock_is_singular( 'page' ) && ! is_front_page() && ( comments_open() || get_comments_number() ) )
							)
		) {
			if ( spock_is_off( spock_get_theme_option( 'debug_mode' ) ) ) {
				$file = spock_get_file_url( 'css/__single.css' );
				if ( ! empty( $file ) ) {
					wp_enqueue_style( 'spock-single', $file, array(), null );
				}
			} else {
				$file = spock_get_file_url( 'css/single.css' );
				if ( ! empty( $file ) ) {
					wp_enqueue_style( 'spock-single', $file, array(), null );
				}
			}
		}
	}
}

if ( ! function_exists( 'spock_wp_styles_plugins' ) ) {
	/**
	 * Load styles for all supported plugins.
	 *
	 * Hooks: add_action('wp_enqueue_scripts', 'spock_wp_styles_plugins', 1100);
	 */
	function spock_wp_styles_plugins() {
		if ( spock_is_off( spock_get_theme_option( 'debug_mode' ) ) ) {
			wp_enqueue_style( 'spock-plugins', spock_get_file_url( 'css/__plugins' . ( spock_is_preview() || ! spock_optimize_css_and_js_loading() ? '-full' : '' ) . '.css' ), array(), null );
		}
	}
}

if ( ! function_exists( 'spock_wp_styles_custom' ) ) {
	/**
	 * Load styles with CSS variables to set up a theme-specific custom fonts and colors.
	 *
	 * Hooks: add_action('wp_enqueue_scripts', 'spock_wp_styles_custom', 1200);
	 */
	function spock_wp_styles_custom() {
		if ( ! is_customize_preview() && spock_is_off( spock_get_theme_option( 'debug_mode' ) ) && ! spock_is_blog_mode_custom() ) {
			wp_enqueue_style( 'spock-custom', spock_get_file_url( 'css/__custom.css' ), array(), null );
		} else {
			wp_enqueue_style( 'spock-custom', spock_get_file_url( 'css/__custom-inline.css' ), array(), null );
			wp_add_inline_style( 'spock-custom', spock_customizer_get_css() );
		}
	}
}

if ( ! function_exists( 'spock_wp_styles_responsive' ) ) {
	/**
	 * Load a theme responsive styles (a priority 2000 is used to load it after the main styles and plugins custom styles)
	 *
	 * Hooks: add_action('wp_enqueue_scripts', 'spock_wp_styles_responsive', 2000);
	 */
	function spock_wp_styles_responsive() {
		if ( spock_is_off( spock_get_theme_option( 'debug_mode' ) ) ) {
			wp_enqueue_style( 'spock-responsive', spock_get_file_url( 'css/__responsive' . ( spock_is_preview() || ! spock_optimize_css_and_js_loading() ? '-full' : '' ) . '.css' ), array(), null, spock_media_for_load_css_responsive( 'main' ) );
		} else {
			wp_enqueue_style( 'spock-responsive', spock_get_file_url( 'css/responsive.css' ), array(), null, spock_media_for_load_css_responsive( 'main' ) );
		}
	}
}

if ( ! function_exists( 'spock_wp_styles_single_responsive' ) ) {
	/**
	 * Load a theme responsive styles for single posts (a priority 2020 is used to load it after the main and plugins responsive styles).
	 *
	 * Hooks: add_action('wp_enqueue_scripts', 'spock_wp_styles_single_responsive', 2020);
	 */
	function spock_wp_styles_single_responsive() {
		if ( apply_filters( 'spock_filters_separate_single_styles', false )
			&& apply_filters( 'spock_filters_load_single_styles', spock_is_single() || spock_is_singular( 'attachment' ) || (int)spock_get_theme_option( 'open_full_post_in_blog', 0 ) > 0 )
		) {
			if ( spock_is_off( spock_get_theme_option( 'debug_mode' ) ) ) {
				$file = spock_get_file_url( 'css/__single-responsive.css' );
				if ( ! empty( $file ) ) {
					wp_enqueue_style( 'spock-single-responsive', $file, array(), null, spock_media_for_load_css_responsive( 'single' ) );
				}
			} else {
				$file = spock_get_file_url( 'css/single-responsive.css' );
				if ( ! empty( $file ) ) {
					wp_enqueue_style( 'spock-single-responsive', $file, array(), null, spock_media_for_load_css_responsive( 'single' ) );
				}
			}
		}
	}
}

if ( ! function_exists( 'spock_wp_styles_child' ) ) {
	/**
	 * Load a child-theme stylesheet after all theme styles (if child-theme folder is not equal to the theme folder).
	 *
	 * Hooks: add_action('wp_enqueue_scripts', 'spock_wp_styles_child', 1500);
	 */
	function spock_wp_styles_child() {
		if ( SPOCK_THEME_URL != SPOCK_CHILD_URL ) {
			wp_enqueue_style( 'spock-child', SPOCK_CHILD_URL . 'style.css', array( 'spock-style' ), null );
		}
	}
}

if ( ! function_exists( 'spock_wp_styles_responsive_child' ) ) {
	/**
	 * Load a child-theme responsive styles (a priority 2500 is used to load it after other responsive styles
	 * and after the child-theme stylesheet)
	 *
	 * Hooks: add_action('wp_enqueue_scripts', 'spock_wp_styles_responsive_child', 2500);
	 */
	function spock_wp_styles_responsive_child() {
		if ( SPOCK_THEME_URL != SPOCK_CHILD_URL && file_exists( SPOCK_CHILD_DIR . 'responsive.css' ) ) {
			wp_enqueue_style( 'spock-responsive-child', SPOCK_CHILD_URL . 'responsive.css', array( 'spock-responsive' ), null, spock_media_for_load_css_responsive( 'main' ) );
		}
	}
}

if ( ! function_exists( 'spock_media_for_load_css_responsive' ) ) {
	/**
	 * Return a 'media' descriptor for the tag 'link' to load responsive CSS only on devices where they are really needed.
	 *
	 * @param string $slug   Optional. A slug of responsive CSS. Default is 'main'.
	 * @param string $media  Optional. A default media descriptor. Default is 'all'.
	 *
	 * @return string        A media descriptor corresponding to the specified slug.
	 */
	function spock_media_for_load_css_responsive( $slug = 'main', $media = 'all' ) {
		global $SPOCK_STORAGE;
		$condition = 'all';
		$media = apply_filters( 'spock_filter_media_for_load_css_responsive', $media, $slug );
		if ( ! empty( $SPOCK_STORAGE['responsive'][ $media ]['max'] ) ) {
			$condition = sprintf( '(max-width:%dpx)', $SPOCK_STORAGE['responsive'][ $media ]['max'] );
		} 
		return apply_filters( 'spock_filter_condition_for_load_css_responsive', $condition, $slug );
	}
}

if ( ! function_exists( 'spock_media_for_load_css_responsive_callback' ) ) {
	add_filter( 'spock_filter_media_for_load_css_responsive', 'spock_media_for_load_css_responsive_callback', 10, 2 );
	/**
	 * Return a maximum 'media' slug to use as a default value for all responsive css-files
	 * (if corresponding media is not detected by a specified slug).
	 *
	 * Hooks: add_filter( 'spock_filter_media_for_load_css_responsive', 'spock_media_for_load_css_responsive_callback', 10, 2 );
	 *
	 * @param string $media  A current media descriptor.
	 * @param string $slug   A current slug to detect a media descriptor. Not used in this function.
	 *
	 * @return string        A default media descriptor, if media stay equal to 'all' after all previous hooks.
	 */
	function spock_media_for_load_css_responsive_callback( $media, $slug ) {
		return 'all' == $media ? 'xxl' : $media;
	}
}


//-------------------------------------------------------
//-- Theme scripts
//-------------------------------------------------------

if ( ! function_exists( 'spock_wp_scripts' ) ) {
	/**
	 * Load a theme-specific scripts for the frontend.
	 *
	 * Hooks: add_action('wp_enqueue_scripts', 'spock_wp_scripts', 1000);
	 */
	function spock_wp_scripts() {
		$blog_archive = spock_storage_get( 'blog_archive' ) === true || is_home();
		$blog_style   = spock_get_theme_option( 'blog_style' );
		$use_masonry  = false;
		if ( strpos( $blog_style, 'blog-custom-' ) === 0 ) {
			$blog_id   = spock_get_custom_blog_id( $blog_style );
			$blog_meta = spock_get_custom_layout_meta( $blog_id );
			if ( ! empty( $blog_meta['scripts_required'] ) && ! spock_is_off( $blog_meta['scripts_required'] ) ) {
				$blog_style  = $blog_meta['scripts_required'];
				$use_masonry = strpos( $blog_meta['scripts_required'], 'masonry' ) !== false;
			}
		} else {
			$blog_parts  = explode( '_', $blog_style );
			$blog_style  = $blog_parts[0];
			$use_masonry = spock_is_blog_style_use_masonry( $blog_style );
		}

		// Superfish Menu
		// Attention! To prevent duplicate this script in the plugin and in the menu, don't merge it!
		wp_enqueue_script( 'superfish', spock_get_file_url( 'js/superfish/superfish.min.js' ), array( 'jquery' ), null, true );

		// Background video
		$header_video = spock_get_header_video();
		if ( ! empty( $header_video ) && ! spock_is_inherit( $header_video ) ) {
			if ( spock_is_youtube_url( $header_video ) ) {
				wp_enqueue_script( 'jquery-tubular', spock_get_file_url( 'js/tubular/jquery.tubular.js' ), array( 'jquery' ), null, true );
			} else {
				wp_enqueue_script( 'bideo', spock_get_file_url( 'js/bideo/bideo.js' ), array(), null, true );
			}
		}

		// Merged scripts
		if ( spock_is_off( spock_get_theme_option( 'debug_mode' ) ) ) {
			wp_enqueue_script( 'spock-init', spock_get_file_url( 'js/__scripts' . ( spock_is_preview() || ! spock_optimize_css_and_js_loading() ? '-full' : '' ) . '.js' ), apply_filters( 'spock_filter_script_deps', array( 'jquery' ) ), null, true );
		} else {
			// Skip link focus
			wp_enqueue_script( 'skip-link-focus-fix', spock_get_file_url( 'js/skip-link-focus-fix/skip-link-focus-fix.js' ), null, true );
			// Theme scripts
			wp_enqueue_script( 'spock-utils', spock_get_file_url( 'js/utils.js' ), array( 'jquery' ), null, true );
			wp_enqueue_script( 'spock-init', spock_get_file_url( 'js/init.js' ), array( 'jquery' ), null, true );
		}

		// Load scripts for smooth parallax animation on the single post or on the post archive (if the option 'open_full_post_in_blog' is on)
		if ( (int)spock_get_theme_option( 'single_parallax', 0 ) != 0
			&& ( spock_is_singular( 'post' )
				|| ( (int)spock_get_theme_option( 'open_full_post_in_blog', 0 ) > 0 && ( is_home() || is_archive() || is_category() ) )
				)
		) {
			spock_load_parallax_scripts();
		}

		// Load masonry scripts
		if ( ( $blog_archive && $use_masonry ) || ( spock_is_singular( 'post' ) && str_replace( 'post-format-', '', get_post_format() ) == 'gallery' ) ) {
			spock_load_masonry_scripts();
		}

		// Load tabs to show filters
		if ( $blog_archive && ! is_customize_preview() && ! spock_is_off( spock_get_theme_option( 'show_filters', 0 ) ) ) {
			wp_enqueue_script( 'jquery-ui-tabs', false, array( 'jquery', 'jquery-ui-core' ), null, true );
		}

		// Comments
		if ( spock_is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Media elements library
		if ( spock_get_theme_setting( 'use_mediaelements' ) ) {
			wp_enqueue_style( 'wp-mediaelement' );
			wp_enqueue_script( 'wp-mediaelement' );
		}
	}
}

if ( ! function_exists( 'spock_localize_scripts' ) ) {
	/**
	 * Localize a theme-specific scripts: add variables to use in JS in the frontend.
	 *
	 * Trigger a filter 'spock_filter_localize_script' to allow other modules add their variables to the localization array.
	 *
	 * Hooks: add_action( 'wp_footer', 'spock_localize_scripts' );
	 */
	function spock_localize_scripts() {

		$video = spock_get_header_video();

		wp_localize_script( 'spock-init', 'SPOCK_STORAGE', apply_filters( 'spock_filter_localize_script', array(
			// AJAX parameters
			'ajax_url'            => esc_url( admin_url( 'admin-ajax.php' ) ),
			'ajax_nonce'          => esc_attr( wp_create_nonce( admin_url( 'admin-ajax.php' ) ) ),

			// Base urls
			'home_url'            => esc_url( home_url() ),
			'theme_url'           => SPOCK_THEME_URL,

			// Site color scheme
			'site_scheme'         => sprintf( 'scheme_%s', spock_get_theme_option( 'color_scheme' ) ),

			// User logged in
			'user_logged_in'      => is_user_logged_in() ? true : false,

			// Window width to switch the site header to the mobile layout
			'mobile_layout_width' => 768,
			'mobile_device'       => wp_is_mobile(),

			// Mobile breakpoints for JS (if window width less then)
			'mobile_breakpoint_underpanels_off' => 768,
			'mobile_breakpoint_fullheight_off' => 1025,

			// Sidemenu options
			'menu_side_stretch'   => (int) spock_get_theme_option( 'menu_side_stretch', 0 ) > 0,
			'menu_side_icons'     => (int) spock_get_theme_option( 'menu_side_icons', 0 ) > 0,

			// Video background
			'background_video'    => spock_is_from_uploads( $video ) ? $video : '',

			// Video and Audio tag wrapper
			'use_mediaelements'   => spock_get_theme_setting( 'use_mediaelements' ) ? true : false,

			// Resize video and iframe
			'resize_tag_video'    => false,
			'resize_tag_iframe'   => true,

			// Allow open full post in the blog
			'open_full_post'      => (int)spock_get_theme_option( 'open_full_post_in_blog', 0 ) > 0,

			// Which block to load in the single posts
			'which_block_load'    => spock_get_theme_option( 'posts_navigation_scroll_which_block', 'article' ),

			// Current mode
			'admin_mode'          => false,

			// Strings for translation
			'msg_ajax_error'      => esc_html__( 'Invalid server answer!', 'spock' ),
			'msg_i_agree_error'   => esc_html__( 'Please accept the terms of our Privacy Policy.', 'spock' ),
		) ) );
	}
}

if ( ! function_exists( 'spock_load_masonry_scripts' ) ) {
	/**
	 * Enqueue a masonry scripts (if need for the current page).
	 */
	function spock_load_masonry_scripts() {
		static $once = true;
		if ( $once ) {
			$once = false;
			wp_enqueue_script( 'imagesloaded' );
			wp_enqueue_script( 'masonry' );
		}
	}
}

if ( ! function_exists( 'spock_load_parallax_scripts' ) ) {
	/**
	 * Enqueue a parallax scripts (if need for the current page).
	 */
	function spock_load_parallax_scripts() {
		if ( function_exists( 'trx_addons_enqueue_parallax' ) ) {
			trx_addons_enqueue_parallax();
		}
	}
}

if ( ! function_exists( 'spock_load_specific_scripts' ) ) {
	add_filter( 'spock_filter_enqueue_blog_scripts', 'spock_load_specific_scripts', 10, 5 );
	/**
	 * Enqueue a blog-specific styles and scripts.
	 *
	 * Hooks: add_filter( 'spock_filter_enqueue_blog_scripts', 'spock_load_specific_scripts', 10, 5 );
	 *
	 * @param bool $load           A filterable flag indicating whether scripts should be loaded by default (true)
	 *                             or they are already loaded by one of the handlers (false).
	 * @param string $blog_style   A slug of the blog style.
	 * @param string $script_slug  A slug of the script to load.
	 * @param array|bool $list     A list with scripts to merge or false if called from enqueue_scripts.
	 * @param bool $responsive     If true - need to load responsive styles, else - a main styles and scripts.
	 *
	 * @return bool                A filtered flag indicating whether scripts should be loaded by default (true)
	 *                             or they are already loaded by one of the handlers (false).
	 */
	function spock_load_specific_scripts( $load, $blog_style, $script_slug, $list, $responsive ) {
		if ( 'masonry' == $script_slug && false === $list ) { // if list === false - called from enqueue_scripts, else - called from merge_script
			spock_load_masonry_scripts();
			$load = false;
		}
		return $load;
	}
}


//-------------------------------------------------------
//-- Head, body and footer
//-------------------------------------------------------

if ( ! function_exists( 'spock_wp_head' ) ) {
	/**
	 * Add meta tags to the header for the frontend.
	 *
	 * Hooks: add_action( 'wp_head',	'spock_wp_head', 1 );
	 */
	function spock_wp_head() {
		if ( ! spock_gutenberg_is_fse_theme() ) {
			?>
			<meta charset="<?php bloginfo( 'charset' ); ?>">
			<?php
		}
		// Add ', maximum-scale=1' to the content of the meta name 'viewport' to disallow the page scaling.
		?>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="format-detection" content="telephone=no">
		<link rel="profile" href="//gmpg.org/xfn/11">
		<?php
		if ( spock_is_singular() && pings_open() ) {
			?>
			<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
			<?php
		}
	}
}

if ( ! function_exists( 'spock_add_body_classes' ) ) {
	/**
	 * Add a theme-specific classes to the tag 'body'.
	 *
	 * Hooks: add_filter( 'body_class', 'spock_add_body_classes' );
	 *
	 * @param array $classes  An array with classes for the tag 'body'.
	 *
	 * @return array          A filtered array with a theme-specific classes for the tag 'body'.
	 */
	function spock_add_body_classes( $classes ) {

		$classes[] = 'scheme_' . esc_attr( spock_get_theme_option( 'color_scheme' ) );

		if ( is_customize_preview() ) {
			$classes[] = 'customize_preview';
		}

		$blog_mode = spock_storage_get( 'blog_mode' );
		$classes[] = 'blog_mode_' . esc_attr( $blog_mode );
		$classes[] = 'body_style_' . esc_attr( spock_get_theme_option( 'body_style' ) );

		if ( in_array( $blog_mode, array( 'post', 'page' ) ) || apply_filters( 'spock_filter_single_post_header', spock_is_singular( 'post' ) ) ) {
			$classes[] = 'is_single';
		} else {
			$classes[] = ' is_stream';
			$classes[] = 'blog_style_' . esc_attr( spock_get_theme_option( 'blog_style' ) );
			if ( spock_storage_get( 'blog_template' ) > 0 ) {
				$classes[] = 'blog_template';
			}
		}

		if ( apply_filters( 'spock_filter_single_post_header', spock_is_singular( 'post' ) || spock_is_singular( 'attachment' ) ) ) {
			$classes[] = 'single_style_' . esc_attr( spock_get_theme_option( 'single_style' ) );
		}

		if ( spock_sidebar_present() ) {
			$classes[] = 'sidebar_show sidebar_' . esc_attr( spock_get_theme_option( 'sidebar_position' ) );
			$classes[] = 'sidebar_small_screen_' . esc_attr( spock_get_theme_option( 'sidebar_position_ss', 'below' ) );
		} else {
			$expand = spock_get_theme_option( 'expand_content' );
			// Compatibility with old versions
			if ( "={$expand}" == '=0' ) {
				$expand = 'normal';
			} else if ( "={$expand}" == '=1' ) {
				$expand = 'expand';
			}
			if ( 'narrow' == $expand && ! spock_is_singular( apply_filters('spock_filter_is_singular_type', array('post') ) ) ) {
				$expand = 'normal';
			}
			$classes[] = 'sidebar_hide';
			$classes[] = "{$expand}_content";
		}

		if ( spock_is_on( spock_get_theme_option( 'remove_margins' ) ) ) {
			$classes[] = 'remove_margins';
		}

		$bg_image = spock_get_theme_option( 'front_page_bg_image' );
		if ( is_front_page() && ! empty( $bg_image ) && spock_is_on( spock_get_theme_option( 'front_page_enabled', false ) ) ) {
			$classes[] = 'with_bg_image';
		}

		$classes[] = 'trx_addons_' . esc_attr( spock_exists_trx_addons() ? 'present' : 'absent' );

		$classes[] = 'header_type_' . esc_attr( spock_get_theme_option( 'header_type' ) );
		$classes[] = 'header_style_' . esc_attr( 'default' == spock_get_theme_option( 'header_type' ) ? 'header-default' : spock_get_theme_option( 'header_style' ) );
		$header_position = spock_get_theme_option( 'header_position' );
		if ( 'over' == $header_position && spock_is_single() && ! has_post_thumbnail() ) {
			$header_position = 'default';
		}
		$classes[] = 'header_position_' . esc_attr( $header_position );

		$menu_side = spock_get_theme_option( 'menu_side' );
		$classes[] = 'menu_side_' . esc_attr( $menu_side ) . ( in_array( $menu_side, array( 'left', 'right' ) ) ? ' menu_side_present' : '' );
		$classes[] = 'no_layout';

		if ( spock_get_theme_setting( 'fixed_blocks_sticky' ) ) {
			$classes[] = 'fixed_blocks_sticky';
		}

		if ( spock_get_theme_option( 'blog_content', 'excerpt' ) == 'fullpost' ) {
			$classes[] = 'fullpost_exist';
		}

		return $classes;
	}
}

if ( ! function_exists( 'spock_wp_footer' ) ) {
	/**
	 * Load a customization styles with css rules added while a current page built.
	 *
	 * Hooks: add_action('wp_footer', 'spock_wp_footer');
	 *        add_action('admin_footer', 'spock_wp_footer');
	 */
	function spock_wp_footer() {
		// Add header zoom
		$header_zoom = max( 0.2, min( 2, (float)spock_get_theme_option( 'header_zoom', 1 ) ) );
		if ( 1 != $header_zoom ) {
			spock_add_inline_css( ".sc_layouts_title_title{font-size:{$header_zoom}em}" );
		}
		// Add logo zoom
		$logo_zoom = max( 0.2, min( 2, (float)spock_get_theme_option( 'logo_zoom', 1 ) ) );
		if ( 1 != $logo_zoom ) {
			spock_add_inline_css( ".custom-logo-link,.sc_layouts_logo{font-size:{$logo_zoom}em}" );
		}
		// Put inline styles to the output
		$css = spock_get_inline_css();
		if ( ! empty( $css ) ) {
			// Attention! Don't change id in the tag 'style' - need to properly work the 'view more' script
			spock_show_layout( apply_filters( 'spock_filter_inline_css', $css ), '<style type="text/css" id="spock-inline-styles-inline-css">', '</style>' );
		}
	}
}


//-------------------------------------------------------
//-- Sidebars and widgets
//-------------------------------------------------------

if ( ! function_exists( 'spock_register_sidebars' ) ) {
	/**
	 * Register a theme-specific widgetized areas.
	 *
	 * Hooks: add_action('widgets_init', 'spock_register_sidebars');
	 */
	function spock_register_sidebars() {
		$sidebars = spock_get_sidebars();
		if ( is_array( $sidebars ) && count( $sidebars ) > 0 ) {
			$cnt = 0;
			foreach ( $sidebars as $id => $sb ) {
				$cnt++;
				register_sidebar(
					apply_filters( 'spock_filter_register_sidebar',
						array(
							'name'          => $sb['name'],
							'description'   => $sb['description'],
							// Translators: Add the sidebar number to the id
							'id'            => ! empty( $id ) ? $id : sprintf( 'theme_sidebar_%d', $cnt),
							'before_widget' => '<aside class="widget %2$s">',	// %1$s - id, %2$s - class
							'after_widget'  => '</aside>',
							'before_title'  => '<h5 class="widget_title">',
							'after_title'   => '</h5>',
						)
					)
				);
			}
		}
	}
}

if ( ! function_exists( 'spock_get_sidebars' ) ) {
	/**
	 * Return a list with all theme-specific widgetized areas.
	 *
	 * @return array  A list of the widgetized areas in format:
	 *                [
	 *                  ['name' => 'Sidebar1 Name', 'description' => 'Sidebar1 Description'],
	 *                  ['name' => 'Sidebar2 Name', 'description' => 'Sidebar2 Description'],
	 *                  ...
	 *                ]
	 */
	function spock_get_sidebars() {
		$list = apply_filters(
			'spock_filter_list_sidebars', array(
				'sidebar_widgets'       => array(
					'name'        => esc_html__( 'Sidebar Widgets', 'spock' ),
					'description' => esc_html__( 'Widgets to be shown on the main sidebar', 'spock' ),
				),
				'header_widgets'        => array(
					'name'        => esc_html__( 'Header Widgets', 'spock' ),
					'description' => esc_html__( 'Widgets to be shown at the top of the page (in the page header area)', 'spock' ),
				),
				'above_page_widgets'    => array(
					'name'        => esc_html__( 'Top Page Widgets', 'spock' ),
					'description' => esc_html__( 'Widgets to be shown below the header, but above the content and sidebar', 'spock' ),
				),
				'above_content_widgets' => array(
					'name'        => esc_html__( 'Above Content Widgets', 'spock' ),
					'description' => esc_html__( 'Widgets to be shown above the content, near the sidebar', 'spock' ),
				),
				'below_content_widgets' => array(
					'name'        => esc_html__( 'Below Content Widgets', 'spock' ),
					'description' => esc_html__( 'Widgets to be shown below the content, near the sidebar', 'spock' ),
				),
				'below_page_widgets'    => array(
					'name'        => esc_html__( 'Bottom Page Widgets', 'spock' ),
					'description' => esc_html__( 'Widgets to be shown below the content and sidebar, but above the footer', 'spock' ),
				),
				'footer_widgets'        => array(
					'name'        => esc_html__( 'Footer Widgets', 'spock' ),
					'description' => esc_html__( 'Widgets to be shown at the bottom of the page (in the page footer area)', 'spock' ),
				),
			)
		);
		return $list;
	}
}


//-------------------------------------------------------
//-- Theme fonts
//-------------------------------------------------------

if ( ! function_exists( 'spock_theme_fonts_links' ) ) {
	/**
	 * Return a list with links for all theme-specific fonts in the format:
	 *
	 * [
	 *   'font1-slug' => 'font1-url',
	 *   'font2-slug' => 'font2-url',
	 *   ...
	 * ]
	 * 
	 * @param bool $separate  Combine all Google fonts to the single link (if false)
	 *                        or add each font-face as separate link (if true)
	 *
	 * @return array  An array with links for all theme-specific fonts.
	 */
	function spock_theme_fonts_links( $separate = false ) {
		$links = array();

		/*
		Translators: If there are characters in your language that are not supported
		by chosen font(s), translate this to 'off'. Do not translate into your own language.
		*/
		$google_fonts_enabled = ( 'off'  !== _x( 'on', 'Google fonts: on or off', 'spock' ) );
		$google_fonts_api     = ( 'css2' !== _x( 'css2', 'Google fonts API: css or css2', 'spock' ) ? 'css' : 'css2' );
		$adobe_fonts_enabled  = ( 'off'  !== _x( 'on', 'Adobe fonts: on or off', 'spock' ) );
		$custom_fonts_enabled = ( 'off'  !== _x( 'on', 'Custom fonts (included in the theme): on or off', 'spock' ) );

		if ( ( $google_fonts_enabled || $adobe_fonts_enabled || $custom_fonts_enabled ) && ! spock_storage_empty( 'load_fonts' ) ) {
			$load_fonts = spock_storage_get( 'load_fonts' );
			if ( count( $load_fonts ) > 0 ) {
				$google_fonts_subset = spock_get_theme_option( 'load_fonts_subset' );
				$google_fonts = '';
				$adobe_fonts  = '';
				foreach ( $load_fonts as $font ) {
					$used = false;
					$slug = spock_get_load_fonts_slug( $font['name'] );
					// Custom (in the theme folder included) font
					if ( $custom_fonts_enabled && empty( $font['styles'] ) && empty( $font['link'] ) ) {
						$url = spock_get_file_url( "css/font-face/{$slug}/stylesheet.css" );
						if ( ! empty( $url ) ) {
							$links[ $slug ] = $url;
							$used = true;
						}
					}
					// Adobe font
					if ( $adobe_fonts_enabled && ! empty( $font['link'] ) ) {
						if ( ! in_array( $font['link'], $links ) ) {
							$links[ $slug ] = $font['link'];
						}
						$used = true;
					}
					// Google font
					if ( $google_fonts_enabled && ! $used ) {
						$link = str_replace( ' ', '+', $font['name'] )
									. ':'
									. ( empty( $font['styles'] )
										? ( 'css2' == $google_fonts_api
											? 'ital,wght@0,400;0,700;1,400;1,700'
											: '400,700,400italic,700italic'
											)
										: $font['styles']
										);
						if ( $separate ) {
							$links[ $slug ] = esc_url( "https://fonts.googleapis.com/{$google_fonts_api}?family={$link}&subset={$google_fonts_subset}&display=swap" );
						} else {
							$google_fonts .= ( $google_fonts
												? ( 'css2' == $google_fonts_api
													? '&family='
													: '|'			// Attention! Using '%7C' instead '|' damage loading second+ fonts
													)
												: ''
												)
											. $link;
						}
					}
				}
				if ( $google_fonts_enabled && ! empty( $google_fonts ) && ! $separate ) {
					$links['google_fonts'] = esc_url( "https://fonts.googleapis.com/{$google_fonts_api}?family={$google_fonts}&subset={$google_fonts_subset}&display=swap" );
				}
			}
		}
		return apply_filters( 'spock_filter_theme_fonts_links', $links );
	}
}

if ( ! function_exists( 'spock_theme_fonts_for_editor' ) ) {
	/**
	 * Return a list with links for all theme-specific fonts to use its as editor styles.
	 * 
	 * @param bool $separate  Combine all Google fonts to the single link (if false)
	 *                        or add each font-face as separate link (if true)
	 *
	 * @return array  An array with links for all theme-specific fonts
	 */
	function spock_theme_fonts_for_editor( $separate = false ) {
		$links = array_values( spock_theme_fonts_links( $separate ) );
		if ( is_array( $links ) && count( $links ) > 0 ) {
			for ( $i = 0; $i < count( $links ); $i++ ) {
				$links[ $i ] = str_replace( ',', '%2C', $links[ $i ] );
			}
		}
		return $links;
	}
}


//-------------------------------------------------------
//-- The Excerpt
//-------------------------------------------------------

if ( ! function_exists( 'spock_excerpt_length' ) ) {
	/**
	 * Return an excerpt length depends of the current blog style.
	 *
	 * Hooks: add_filter( 'excerpt_length', 'spock_excerpt_length' );
	 *
	 * @param int $length  Current value of the length.
	 *
	 * @return int         Filtered value of the length.
	 */
	function spock_excerpt_length( $length ) {
		$blog_style = explode( '_', spock_get_theme_option( 'blog_style' ) );
		return max( 0, round( spock_get_theme_option( 'excerpt_length' ) / ( in_array( $blog_style[0], array( 'classic', 'masonry', 'portfolio' ) ) ? 2 : 1 ) ) );
	}
}

if ( ! function_exists( 'spock_excerpt_more' ) ) {
	/**
	 * Return a string '&hellip;' to append to the excerpt.
	 *
	 * Hooks: add_filter( 'excerpt_more', 'spock_excerpt_more' );
	 *
	 * @param string $more  A current string to append.
	 *
	 * @return string       A theme-specific string to append.
	 */
	function spock_excerpt_more( $more ) {
		return '&hellip;';
	}
}


//-------------------------------------------------------
//-- Comments
//-------------------------------------------------------

if ( ! function_exists( 'spock_comment_form_fields' ) ) {
	/**
	 * Reorder a list with fields for the comment form - put the field 'comment' to the end of the list.
	 *
	 * Hooks: add_filter('comment_form_fields', 'spock_comment_form_fields');
	 *
	 * @param array $comment_fields  An array with fields for the comments form.
	 *
	 * @return array                 A reordered array with fields.
	 */
	function spock_comment_form_fields( $comment_fields ) {
		if ( spock_get_theme_setting( 'comment_after_name' ) ) {
			$keys = array_keys( $comment_fields );
			if ( 'comment' == $keys[0] ) {
				$comment_fields['comment'] = array_shift( $comment_fields );
			}
		}
		return $comment_fields;
	}
}

if ( ! function_exists( 'spock_comment_form_agree' ) ) {
	/**
	 * Add a checkbox with "I agree ..." to the list of fields for the comments form.
	 *
	 * Hooks: add_filter('comment_form_fields', 'spock_comment_form_agree', 11);
	 *
	 * @param array $comment_fields  An array with fields for the comments form.
	 *
	 * @return array                 A list with the comments form fields with a checkbox added.
	 */
	function spock_comment_form_agree( $comment_fields ) {
		$privacy_text = spock_get_privacy_text();
		if ( ! empty( $privacy_text )
			&& ( ! function_exists( 'spock_exists_gdpr_framework' ) || ! spock_exists_gdpr_framework() )
			&& ( ! function_exists( 'spock_exists_wp_gdpr_compliance' ) || ! spock_exists_wp_gdpr_compliance() )
		) {
			$comment_fields['i_agree_privacy_policy'] = spock_single_comments_field(
				array(
					'form_style'        => 'default',
					'field_type'        => 'checkbox',
					'field_req'         => '',
					'field_icon'        => '',
					'field_value'       => '1',
					'field_name'        => 'i_agree_privacy_policy',
					'field_title'       => $privacy_text,
				)
			);
		}
		return $comment_fields;
	}
}


//-------------------------------------------------------
//-- Thumb sizes
//-------------------------------------------------------

if ( ! function_exists( 'spock_theme_thumbs_sizes' ) ) {
	/**
	 * Add a retina-ready dimensions to the list with thumb sizes.
	 *
	 * Hooks: add_filter( 'image_size_names_choose', 'spock_theme_thumbs_sizes' );
	 *
	 * @param $sizes
	 * @return mixed
	 */
	function spock_theme_thumbs_sizes( $sizes ) {
		$thumb_sizes = spock_storage_get( 'theme_thumbs' );
		$mult        = spock_get_theme_option( 'retina_ready', 1 );
		foreach ( $thumb_sizes as $k => $v ) {
			$sizes[ $k ] = $v['title'];
			if ( $mult > 1 ) {
				$sizes[ $k . '-@retina' ] = $v['title'] . ' ' . esc_html__( '@2x', 'spock' );
			}
		}
		return $sizes;
	}
}


//-------------------------------------------------------
//-- Include theme (or child) PHP-files
//-------------------------------------------------------

// Load a theme core files
require_once SPOCK_THEME_DIR . 'includes/utils.php';
require_once SPOCK_THEME_DIR . 'includes/storage.php';

require_once SPOCK_THEME_DIR . 'includes/lists.php';
require_once SPOCK_THEME_DIR . 'includes/wp.php';

if ( is_admin() ) {
	require_once SPOCK_THEME_DIR . 'includes/tgmpa/class-tgm-plugin-activation.php';
	require_once SPOCK_THEME_DIR . 'includes/admin.php';
}

require_once SPOCK_THEME_DIR . 'theme-options/theme-customizer.php';

require_once SPOCK_THEME_DIR . 'front-page/front-page-options.php';

// Load a skins support
if ( defined( 'SPOCK_ALLOW_SKINS' ) && SPOCK_ALLOW_SKINS && file_exists( SPOCK_THEME_DIR . 'skins/skins.php' ) ) {
	require_once SPOCK_THEME_DIR . 'skins/skins.php';
}

// Load next files after the skins support loaded to allow a file substitution from the skins folder
require_once spock_get_file_dir( 'theme-specific/theme-improves.php' );
require_once spock_get_file_dir( 'theme-specific/theme-tags.php' );
require_once spock_get_file_dir( 'theme-specific/theme-about/theme-about.php' );

// Add a free theme support
if ( SPOCK_THEME_FREE ) {
	require_once spock_get_file_dir( 'theme-specific/theme-about/theme-upgrade.php' );
}

// Load an image hover effects
$spock_file_dir = spock_get_file_dir( 'theme-specific/theme-hovers/theme-hovers.php' );
if ( ! empty( $spock_file_dir ) ) {
	require_once spock_get_file_dir( 'theme-specific/theme-hovers/theme-hovers.php' );      // Substitution from skin is allowed
}

// Load a plugins support
$spock_required_plugins = apply_filters( 'spock_required_plugins', spock_storage_get( 'required_plugins' ) );
if ( is_array( $spock_required_plugins ) ) {
	foreach ( $spock_required_plugins as $spock_plugin_slug => $spock_plugin_data ) {
		$spock_plugin_slug = spock_esc( $spock_plugin_slug );
		$spock_plugin_path = spock_get_file_dir( sprintf( 'plugins/%1$s/%1$s.php', $spock_plugin_slug ) );
		if ( ! empty( $spock_plugin_path ) ) {
			require_once $spock_plugin_path;
		}
	}
}

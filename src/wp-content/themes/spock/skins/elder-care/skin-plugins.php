<?php
/**
 * Required plugins
 *
 * @package SPOCK
 * @since SPOCK 1.76.0
 */

// THEME-SUPPORTED PLUGINS
// If plugin not need - remove its settings from next array
//----------------------------------------------------------
$spock_theme_required_plugins_groups = array(
	'core'          => esc_html__( 'Core', 'spock' ),
	'page_builders' => esc_html__( 'Page Builders', 'spock' ),
	'ecommerce'     => esc_html__( 'E-Commerce & Donations', 'spock' ),
	'socials'       => esc_html__( 'Socials and Communities', 'spock' ),
	'events'        => esc_html__( 'Events and Appointments', 'spock' ),
	'content'       => esc_html__( 'Content', 'spock' ),
	'other'         => esc_html__( 'Other', 'spock' ),
);
$spock_theme_required_plugins        = array(
	'trx_addons'                 => array(
		'title'       => esc_html__( 'ThemeREX Addons', 'spock' ),
		'description' => esc_html__( "Will allow you to install recommended plugins, demo content, and improve the theme's functionality overall with multiple theme options", 'spock' ),
		'required'    => true,
		'logo'        => 'trx_addons.png',
		'group'       => $spock_theme_required_plugins_groups['core'],
	),
	'elementor'                  => array(
		'title'       => esc_html__( 'Elementor', 'spock' ),
		'description' => esc_html__( "Is a beautiful PageBuilder, even the free version of which allows you to create great pages using a variety of modules.", 'spock' ),
		'required'    => false,
		'logo'        => 'elementor.png',
		'group'       => $spock_theme_required_plugins_groups['page_builders'],
	),
	'gutenberg'                  => array(
		'title'       => esc_html__( 'Gutenberg', 'spock' ),
		'description' => esc_html__( "It's a posts editor coming in place of the classic TinyMCE. Can be installed and used in parallel with Elementor", 'spock' ),
		'required'    => false,
		'install'     => false,          // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'gutenberg.png',
		'group'       => $spock_theme_required_plugins_groups['page_builders'],
	),
	'js_composer'                => array(
		'title'       => esc_html__( 'WPBakery PageBuilder', 'spock' ),
		'description' => esc_html__( "Popular PageBuilder which allows you to create excellent pages", 'spock' ),
		'required'    => false,
		'install'     => false,          // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'js_composer.jpg',
		'group'       => $spock_theme_required_plugins_groups['page_builders'],
	),
	'woocommerce'                => array(
		'title'       => esc_html__( 'WooCommerce', 'spock' ),
		'description' => esc_html__( "Connect the store to your website and start selling now", 'spock' ),
		'required'    => false,
		'install'     => false,
		'logo'        => 'woocommerce.png',
		'group'       => $spock_theme_required_plugins_groups['ecommerce'],
	),
	'elegro-payment'             => array(
		'title'       => esc_html__( 'Elegro Crypto Payment', 'spock' ),
		'description' => esc_html__( "Extends WooCommerce Payment Gateways with an elegro Crypto Payment", 'spock' ),
		'required'    => false,
		'install'     => false, // TRX_addons has marked the "Elegro Crypto Payment" plugin as obsolete and no longer recommends it for installation, even if it had been previously recommended by the theme
		'logo'        => 'elegro-payment.png',
		'group'       => $spock_theme_required_plugins_groups['ecommerce'],
	),
	'instagram-feed'             => array(
		'title'       => esc_html__( 'Instagram Feed', 'spock' ),
		'description' => esc_html__( "Displays the latest photos from your profile on Instagram", 'spock' ),
		'required'    => false,
		'logo'        => 'instagram-feed.png',
		'group'       => $spock_theme_required_plugins_groups['socials'],
	),
	'mailchimp-for-wp'           => array(
		'title'       => esc_html__( 'MailChimp for WP', 'spock' ),
		'description' => esc_html__( "Allows visitors to subscribe to newsletters", 'spock' ),
		'required'    => false,
		'logo'        => 'mailchimp-for-wp.png',
		'group'       => $spock_theme_required_plugins_groups['socials'],
	),
	'booked'                     => array(
		'title'       => esc_html__( 'Booked Appointments', 'spock' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => 'booked.png',
		'group'       => $spock_theme_required_plugins_groups['events'],
	),
	'quickcal'                     => array(
		'title'       => esc_html__( 'QuickCal', 'spock' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => 'quickcal.png',
		'group'       => $spock_theme_required_plugins_groups['events'],
	),
	'the-events-calendar'        => array(
		'title'       => esc_html__( 'The Events Calendar', 'spock' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => 'the-events-calendar.png',
		'group'       => $spock_theme_required_plugins_groups['events'],
	),
	'contact-form-7'             => array(
		'title'       => esc_html__( 'Contact Form 7', 'spock' ),
		'description' => esc_html__( "CF7 allows you to create an unlimited number of contact forms", 'spock' ),
		'required'    => false,
		'logo'        => 'contact-form-7.png',
		'group'       => $spock_theme_required_plugins_groups['content'],
	),

	'latepoint'                  => array(
		'title'       => esc_html__( 'LatePoint', 'spock' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => spock_get_file_url( 'plugins/latepoint/latepoint.png' ),
		'group'       => $spock_theme_required_plugins_groups['events'],
	),
	'advanced-popups'                  => array(
		'title'       => esc_html__( 'Advanced Popups', 'spock' ),
		'description' => '',
		'required'    => false,
		'logo'        => spock_get_file_url( 'plugins/advanced-popups/advanced-popups.jpg' ),
		'group'       => $spock_theme_required_plugins_groups['content'],
	),
	'devvn-image-hotspot'                  => array(
		'title'       => esc_html__( 'Image Hotspot by DevVN', 'spock' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => spock_get_file_url( 'plugins/devvn-image-hotspot/devvn-image-hotspot.png' ),
		'group'       => $spock_theme_required_plugins_groups['content'],
	),
	'ti-woocommerce-wishlist'                  => array(
		'title'       => esc_html__( 'TI WooCommerce Wishlist', 'spock' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => spock_get_file_url( 'plugins/ti-woocommerce-wishlist/ti-woocommerce-wishlist.png' ),
		'group'       => $spock_theme_required_plugins_groups['ecommerce'],
	),
	'woo-smart-quick-view'                  => array(
		'title'       => esc_html__( 'WPC Smart Quick View for WooCommerce', 'spock' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => spock_get_file_url( 'plugins/woo-smart-quick-view/woo-smart-quick-view.png' ),
		'group'       => $spock_theme_required_plugins_groups['ecommerce'],
	),
	'twenty20'                  => array(
		'title'       => esc_html__( 'Twenty20 Image Before-After', 'spock' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => spock_get_file_url( 'plugins/twenty20/twenty20.png' ),
		'group'       => $spock_theme_required_plugins_groups['content'],
	),
	'essential-grid'             => array(
		'title'       => esc_html__( 'Essential Grid', 'spock' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => 'essential-grid.png',
		'group'       => $spock_theme_required_plugins_groups['content'],
	),
	'revslider'                  => array(
		'title'       => esc_html__( 'Revolution Slider', 'spock' ),
		'description' => '',
		'required'    => false,
		'logo'        => 'revslider.png',
		'group'       => $spock_theme_required_plugins_groups['content'],
	),
	'sitepress-multilingual-cms' => array(
		'title'       => esc_html__( 'WPML - Sitepress Multilingual CMS', 'spock' ),
		'description' => esc_html__( "Allows you to make your website multilingual", 'spock' ),
		'required'    => false,
		'install'     => false,      // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'sitepress-multilingual-cms.png',
		'group'       => $spock_theme_required_plugins_groups['content'],
	),
	'wp-gdpr-compliance'         => array(
		'title'       => esc_html__( 'Cookie Information', 'spock' ),
		'description' => esc_html__( "Allow visitors to decide for themselves what personal data they want to store on your site", 'spock' ),
		'required'    => false,
		'install'     => false,
		'logo'        => 'wp-gdpr-compliance.png',
		'group'       => $spock_theme_required_plugins_groups['other'],
	),
	'gdpr-framework'         => array(
		'title'       => esc_html__( 'The GDPR Framework', 'spock' ),
		'description' => esc_html__( "Tools to help make your website GDPR-compliant. Fully documented, extendable and developer-friendly.", 'spock' ),
		'required'    => false,
		'install'     => false,
		'logo'        => 'gdpr-framework.png',
		'group'       => $spock_theme_required_plugins_groups['other'],
	),
	'trx_updater'                => array(
		'title'       => esc_html__( 'ThemeREX Updater', 'spock' ),
		'description' => esc_html__( "Update theme and theme-specific plugins from developer's upgrade server.", 'spock' ),
		'required'    => false,
		'logo'        => 'trx_updater.png',
		'group'       => $spock_theme_required_plugins_groups['other'],
	),
);

if ( SPOCK_THEME_FREE ) {
	unset( $spock_theme_required_plugins['js_composer'] );
	unset( $spock_theme_required_plugins['booked'] );
	unset( $spock_theme_required_plugins['quickcal'] );
	unset( $spock_theme_required_plugins['the-events-calendar'] );
	unset( $spock_theme_required_plugins['calculated-fields-form'] );
	unset( $spock_theme_required_plugins['essential-grid'] );
	unset( $spock_theme_required_plugins['revslider'] );
	unset( $spock_theme_required_plugins['sitepress-multilingual-cms'] );
	unset( $spock_theme_required_plugins['trx_updater'] );
	unset( $spock_theme_required_plugins['trx_popup'] );
}

// Add plugins list to the global storage
spock_storage_set( 'required_plugins', $spock_theme_required_plugins );

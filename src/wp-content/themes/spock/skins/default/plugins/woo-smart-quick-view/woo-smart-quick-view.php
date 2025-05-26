<?php
/* WPC Smart Quick View for WooCommerce support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('spock_quick_view_theme_setup9')) {
	add_action( 'after_setup_theme', 'spock_quick_view_theme_setup9', 9 );
	function spock_quick_view_theme_setup9() {
		if (spock_exists_quick_view()) {
			add_action( 'wp_enqueue_scripts', 'spock_quick_view_frontend_scripts', 1100 );
			add_filter( 'spock_filter_merge_styles', 'spock_quick_view_merge_styles' );
		}
		if (is_admin()) {
			add_filter( 'spock_filter_tgmpa_required_plugins',		'spock_quick_view_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'spock_quick_view_tgmpa_required_plugins' ) ) {
	function spock_quick_view_tgmpa_required_plugins($list=array()) {
		if (spock_storage_isset( 'required_plugins', 'woocommerce' ) && spock_storage_get_array( 'required_plugins', 'woocommerce', 'install' ) !== false &&
			spock_storage_isset('required_plugins', 'woo-smart-quick-view') && spock_storage_get_array( 'required_plugins', 'woo-smart-quick-view', 'install' ) !== false) {
			$list[] = array(
				'name' 		=> spock_storage_get_array('required_plugins', 'woo-smart-quick-view', 'title'),
				'slug' 		=> 'woo-smart-quick-view',
				'required' 	=> false
			);
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'spock_exists_quick_view' ) ) {
	function spock_exists_quick_view() {
		return function_exists('woosq_init');
	}
}

// Enqueue custom scripts
if ( ! function_exists( 'spock_quick_view_frontend_scripts' ) ) {
	function spock_quick_view_frontend_scripts() {
		if ( spock_is_on( spock_get_theme_option( 'debug_mode' ) ) ) {
			$spock_url = spock_get_file_url( 'plugins/woo-smart-quick-view/woo-smart-quick-view.css' );
			if ( '' != $spock_url ) {
				wp_enqueue_style( 'spock-woo-smart-quick-view', $spock_url, array(), null );
			}
		}
	}
}

// Merge custom styles
if ( ! function_exists( 'spock_quick_view_merge_styles' ) ) {
	function spock_quick_view_merge_styles( $list ) {
		$list['plugins/woo-smart-quick-view/woo-smart-quick-view.css'] = true;
		return $list;
	}
}

// Add plugin-specific colors and fonts to the custom CSS
if ( spock_exists_quick_view() ) {
	require_once spock_get_file_dir( 'plugins/woo-smart-quick-view/woo-smart-quick-view-style.php' );
}


// One-click import support
//------------------------------------------------------------------------

// Check plugin in the required plugins
if ( !function_exists( 'spock_quick_view_importer_required_plugins' ) ) {
    if (is_admin()) add_filter( 'trx_addons_filter_importer_required_plugins',	'spock_quick_view_importer_required_plugins', 10, 2 );
    function spock_quick_view_importer_required_plugins($not_installed='', $list='') {
        if (strpos($list, 'woo-smart-quick-view')!==false && !spock_exists_quick_view() )
            $not_installed .= '<br>' . esc_html__('WPC Smart Quick View for WooCommerce', 'spock');
        return $not_installed;
    }
}

// Set plugin's specific importer options
if ( !function_exists( 'spock_quick_view_importer_set_options' ) ) {
    if (is_admin()) add_filter( 'trx_addons_filter_importer_options',	'spock_quick_view_importer_set_options' );
    function spock_quick_view_importer_set_options($options=array()) {
        if ( spock_exists_quick_view() && in_array('woo-smart-quick-view', $options['required_plugins']) ) {
            $options['additional_options'][] = 'woosq_%';
        }
        return $options;
    }
}
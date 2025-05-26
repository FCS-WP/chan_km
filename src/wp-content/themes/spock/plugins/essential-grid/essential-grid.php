<?php
/* Essential Grid support functions
------------------------------------------------------------------------------- */


// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'spock_essential_grid_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'spock_essential_grid_theme_setup9', 9 );
	function spock_essential_grid_theme_setup9() {
		if ( spock_exists_essential_grid() ) {
			add_action( 'wp_enqueue_scripts', 'spock_essential_grid_frontend_scripts', 1100 );
			add_action( 'trx_addons_action_load_scripts_front_essential_grid', 'spock_essential_grid_frontend_scripts', 10, 1 );
			add_filter( 'spock_filter_merge_styles', 'spock_essential_grid_merge_styles' );
		}
		if ( is_admin() ) {
			add_filter( 'spock_filter_tgmpa_required_plugins', 'spock_essential_grid_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'spock_essential_grid_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('spock_filter_tgmpa_required_plugins',	'spock_essential_grid_tgmpa_required_plugins');
	function spock_essential_grid_tgmpa_required_plugins( $list = array() ) {
		if ( spock_storage_isset( 'required_plugins', 'essential-grid' ) && spock_storage_get_array( 'required_plugins', 'essential-grid', 'install' ) !== false && spock_is_theme_activated() ) {
			$path = spock_get_plugin_source_path( 'plugins/essential-grid/essential-grid.zip' );
			if ( ! empty( $path ) || spock_get_theme_setting( 'tgmpa_upload' ) ) {
				$list[] = array(
					'name'     => spock_storage_get_array( 'required_plugins', 'essential-grid', 'title' ),
					'slug'     => 'essential-grid',
					'source'   => ! empty( $path ) ? $path : 'upload://essential-grid.zip',
					'version'  => '2.2.4.2',
					'required' => false,
				);
			}
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( ! function_exists( 'spock_exists_essential_grid' ) ) {
	function spock_exists_essential_grid() {
		return defined( 'EG_PLUGIN_PATH' ) || defined( 'ESG_PLUGIN_PATH' );
	}
}

// Enqueue styles for frontend
if ( ! function_exists( 'spock_essential_grid_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'spock_essential_grid_frontend_scripts', 1100 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_essential_grid', 'spock_essential_grid_frontend_scripts', 10, 1 );
	function spock_essential_grid_frontend_scripts( $force = false ) {
		spock_enqueue_optimized( 'essential_grid', $force, array(
			'css' => array(
				'spock-essential-grid' => array( 'src' => 'plugins/essential-grid/essential-grid.css' ),
			)
		) );
	}
}

// Merge custom styles
if ( ! function_exists( 'spock_essential_grid_merge_styles' ) ) {
	//Handler of the add_filter('spock_filter_merge_styles', 'spock_essential_grid_merge_styles');
	function spock_essential_grid_merge_styles( $list ) {
		$list[ 'plugins/essential-grid/essential-grid.css' ] = false;
		return $list;
	}
}

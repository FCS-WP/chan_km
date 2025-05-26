<?php
/**
 * The template to display Admin notices
 *
 * @package SPOCK
 * @since SPOCK 1.0.64
 */

$spock_skins_url  = get_admin_url( null, 'admin.php?page=trx_addons_theme_panel#trx_addons_theme_panel_section_skins' );
$spock_skins_args = get_query_var( 'spock_skins_notice_args' );
?>
<div class="spock_admin_notice spock_skins_notice notice notice-info is-dismissible" data-notice="skins">
	<?php
	// Theme image
	$spock_theme_img = spock_get_file_url( 'screenshot.jpg' );
	if ( '' != $spock_theme_img ) {
		?>
		<div class="spock_notice_image"><img src="<?php echo esc_url( $spock_theme_img ); ?>" alt="<?php esc_attr_e( 'Theme screenshot', 'spock' ); ?>"></div>
		<?php
	}

	// Title
	?>
	<h3 class="spock_notice_title">
		<?php esc_html_e( 'New skins are available', 'spock' ); ?>
	</h3>
	<?php

	// Description
	$spock_total      = $spock_skins_args['update'];	// Store value to the separate variable to avoid warnings from ThemeCheck plugin!
	$spock_skins_msg  = $spock_total > 0
							// Translators: Add new skins number
							? '<strong>' . sprintf( _n( '%d new version', '%d new versions', $spock_total, 'spock' ), $spock_total ) . '</strong>'
							: '';
	$spock_total      = $spock_skins_args['free'];
	$spock_skins_msg .= $spock_total > 0
							? ( ! empty( $spock_skins_msg ) ? ' ' . esc_html__( 'and', 'spock' ) . ' ' : '' )
								// Translators: Add new skins number
								. '<strong>' . sprintf( _n( '%d free skin', '%d free skins', $spock_total, 'spock' ), $spock_total ) . '</strong>'
							: '';
	$spock_total      = $spock_skins_args['pay'];
	$spock_skins_msg .= $spock_skins_args['pay'] > 0
							? ( ! empty( $spock_skins_msg ) ? ' ' . esc_html__( 'and', 'spock' ) . ' ' : '' )
								// Translators: Add new skins number
								. '<strong>' . sprintf( _n( '%d paid skin', '%d paid skins', $spock_total, 'spock' ), $spock_total ) . '</strong>'
							: '';
	?>
	<div class="spock_notice_text">
		<p>
			<?php
			// Translators: Add new skins info
			echo wp_kses_data( sprintf( __( "We are pleased to announce that %s are available for your theme", 'spock' ), $spock_skins_msg ) );
			?>
		</p>
	</div>
	<?php

	// Buttons
	?>
	<div class="spock_notice_buttons">
		<?php
		// Link to the theme dashboard page
		?>
		<a href="<?php echo esc_url( $spock_skins_url ); ?>" class="button button-primary"><i class="dashicons dashicons-update"></i> 
			<?php
			// Translators: Add theme name
			esc_html_e( 'Go to Skins manager', 'spock' );
			?>
		</a>
	</div>
</div>

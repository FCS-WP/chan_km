<?php
/**
 * The template to display Admin notices
 *
 * @package SPOCK
 * @since SPOCK 1.98.0
 */

$spock_skins_url   = get_admin_url( null, 'admin.php?page=trx_addons_theme_panel#trx_addons_theme_panel_section_skins' );
$spock_active_skin = spock_skins_get_active_skin_name();
?>
<div class="spock_admin_notice spock_skins_notice notice notice-error">
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
		<?php esc_html_e( 'Active skin is missing!', 'spock' ); ?>
	</h3>
	<div class="spock_notice_text">
		<p>
			<?php
			// Translators: Add a current skin name to the message
			echo wp_kses_data( sprintf( __( "Your active skin <b>'%s'</b> is missing. Usually this happens when the theme is updated directly through the server or FTP.", 'spock' ), ucfirst( $spock_active_skin ) ) );
			?>
		</p>
		<p>
			<?php
			echo wp_kses_data( __( "Please use only <b>'ThemeREX Updater v.1.6.0+'</b> plugin for your future updates.", 'spock' ) );
			?>
		</p>
		<p>
			<?php
			echo wp_kses_data( __( "But no worries! You can re-download the skin via 'Skins Manager' ( Theme Panel - Theme Dashboard - Skins ).", 'spock' ) );
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

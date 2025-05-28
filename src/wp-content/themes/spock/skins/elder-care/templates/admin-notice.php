<?php
/**
 * The template to display Admin notices
 *
 * @package SPOCK
 * @since SPOCK 1.0.1
 */

$spock_theme_slug = get_option( 'template' );
$spock_theme_obj  = wp_get_theme( $spock_theme_slug );
?>
<div class="spock_admin_notice spock_welcome_notice notice notice-info is-dismissible" data-notice="admin">
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
		<?php
		echo esc_html(
			sprintf(
				// Translators: Add theme name and version to the 'Welcome' message
				__( 'Welcome to %1$s v.%2$s', 'spock' ),
				$spock_theme_obj->get( 'Name' ) . ( SPOCK_THEME_FREE ? ' ' . __( 'Free', 'spock' ) : '' ),
				$spock_theme_obj->get( 'Version' )
			)
		);
		?>
	</h3>
	<?php

	// Description
	?>
	<div class="spock_notice_text">
		<p class="spock_notice_text_description">
			<?php
			echo str_replace( '. ', '.<br>', wp_kses_data( $spock_theme_obj->description ) );
			?>
		</p>
		<p class="spock_notice_text_info">
			<?php
			echo wp_kses_data( __( 'Attention! Plugin "ThemeREX Addons" is required! Please, install and activate it!', 'spock' ) );
			?>
		</p>
	</div>
	<?php

	// Buttons
	?>
	<div class="spock_notice_buttons">
		<?php
		// Link to the page 'About Theme'
		?>
		<a href="<?php echo esc_url( admin_url() . 'themes.php?page=spock_about' ); ?>" class="button button-primary"><i class="dashicons dashicons-nametag"></i> 
			<?php
			echo esc_html__( 'Install plugin "ThemeREX Addons"', 'spock' );
			?>
		</a>
	</div>
</div>

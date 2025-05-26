<?php
/**
 * The template to display Admin notices
 *
 * @package SPOCK
 * @since SPOCK 1.0.1
 */

$spock_theme_slug = get_template();
$spock_theme_obj  = wp_get_theme( $spock_theme_slug );

?>
<div class="spock_admin_notice spock_rate_notice notice notice-info is-dismissible" data-notice="rate">
	<?php
	// Theme image
	$spock_theme_img = spock_get_file_url( 'screenshot.jpg' );
	if ( '' != $spock_theme_img ) {
		?>
		<div class="spock_notice_image"><img src="<?php echo esc_url( $spock_theme_img ); ?>" alt="<?php esc_attr_e( 'Theme screenshot', 'spock' ); ?>"></div>
		<?php
	}

	// Title
	$spock_theme_name = '"' . $spock_theme_obj->get( 'Name' ) . ( SPOCK_THEME_FREE ? ' ' . __( 'Free', 'spock' ) : '' ) . '"';
	?>
	<h3 class="spock_notice_title"><a href="<?php echo esc_url( spock_storage_get( 'theme_rate_url' ) ); ?>" target="_blank">
		<?php
		echo esc_html(
			sprintf(
				// Translators: Add theme name to the 'Welcome' message
				__( 'Help Us Grow - Rate %s Today!', 'spock' ),
				$spock_theme_name
			)
		);
		?>
	</a></h3>
	<?php

	// Description
	?>
	<div class="spock_notice_text">
		<p><?php
			// Translators: Add theme name to the 'Welcome' message
			echo wp_kses_data( sprintf( __( "Thank you for choosing the %s theme for your website! We're excited to see how you've customized your site, and we hope you've enjoyed working with our theme.", 'spock' ), $spock_theme_name ) );
		?></p>
		<p><?php
			// Translators: Add theme name to the 'Welcome' message
			echo wp_kses_data( sprintf( __( "Your feedback really matters to us! If you've had a positive experience, we'd love for you to take a moment to rate %s and share your thoughts on the customer service you received.", 'spock' ), $spock_theme_name ) );
		?></p>
	</div>
	<?php

	// Buttons
	?>
	<div class="spock_notice_buttons">
		<?php
		// Link to the theme download page
		?>
		<a href="<?php echo esc_url( spock_storage_get( 'theme_rate_url' ) ); ?>" class="button button-primary" target="_blank"><i class="dashicons dashicons-star-filled"></i> 
			<?php
			// Translators: Add the theme name to the button caption
			echo esc_html( sprintf( __( 'Rate %s Now', 'spock' ), $spock_theme_name ) );
			?>
		</a>
		<?php
		// Link to the theme support
		?>
		<a href="<?php echo esc_url( spock_storage_get( 'theme_support_url' ) ); ?>" class="button" target="_blank"><i class="dashicons dashicons-sos"></i> 
			<?php
			esc_html_e( 'Support', 'spock' );
			?>
		</a>
		<?php
		// Link to the theme documentation
		?>
		<a href="<?php echo esc_url( spock_storage_get( 'theme_doc_url' ) ); ?>" class="button" target="_blank"><i class="dashicons dashicons-book"></i> 
			<?php
			esc_html_e( 'Documentation', 'spock' );
			?>
		</a>
	</div>
</div>

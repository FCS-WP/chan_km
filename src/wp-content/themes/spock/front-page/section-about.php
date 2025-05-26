<div class="front_page_section front_page_section_about<?php
	$spock_scheme = spock_get_theme_option( 'front_page_about_scheme' );
	if ( ! empty( $spock_scheme ) && ! spock_is_inherit( $spock_scheme ) ) {
		echo ' scheme_' . esc_attr( $spock_scheme );
	}
	echo ' front_page_section_paddings_' . esc_attr( spock_get_theme_option( 'front_page_about_paddings' ) );
	if ( spock_get_theme_option( 'front_page_about_stack' ) ) {
		echo ' sc_stack_section_on';
	}
?>"
		<?php
		$spock_css      = '';
		$spock_bg_image = spock_get_theme_option( 'front_page_about_bg_image' );
		if ( ! empty( $spock_bg_image ) ) {
			$spock_css .= 'background-image: url(' . esc_url( spock_get_attachment_url( $spock_bg_image ) ) . ');';
		}
		if ( ! empty( $spock_css ) ) {
			echo ' style="' . esc_attr( $spock_css ) . '"';
		}
		?>
>
<?php
	// Add anchor
	$spock_anchor_icon = spock_get_theme_option( 'front_page_about_anchor_icon' );
	$spock_anchor_text = spock_get_theme_option( 'front_page_about_anchor_text' );
if ( ( ! empty( $spock_anchor_icon ) || ! empty( $spock_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
	echo do_shortcode(
		'[trx_sc_anchor id="front_page_section_about"'
									. ( ! empty( $spock_anchor_icon ) ? ' icon="' . esc_attr( $spock_anchor_icon ) . '"' : '' )
									. ( ! empty( $spock_anchor_text ) ? ' title="' . esc_attr( $spock_anchor_text ) . '"' : '' )
									. ']'
	);
}
?>
	<div class="front_page_section_inner front_page_section_about_inner
	<?php
	if ( spock_get_theme_option( 'front_page_about_fullheight' ) ) {
		echo ' spock-full-height sc_layouts_flex sc_layouts_columns_middle';
	}
	?>
			"
			<?php
			$spock_css           = '';
			$spock_bg_mask       = spock_get_theme_option( 'front_page_about_bg_mask' );
			$spock_bg_color_type = spock_get_theme_option( 'front_page_about_bg_color_type' );
			if ( 'custom' == $spock_bg_color_type ) {
				$spock_bg_color = spock_get_theme_option( 'front_page_about_bg_color' );
			} elseif ( 'scheme_bg_color' == $spock_bg_color_type ) {
				$spock_bg_color = spock_get_scheme_color( 'bg_color', $spock_scheme );
			} else {
				$spock_bg_color = '';
			}
			if ( ! empty( $spock_bg_color ) && $spock_bg_mask > 0 ) {
				$spock_css .= 'background-color: ' . esc_attr(
					1 == $spock_bg_mask ? $spock_bg_color : spock_hex2rgba( $spock_bg_color, $spock_bg_mask )
				) . ';';
			}
			if ( ! empty( $spock_css ) ) {
				echo ' style="' . esc_attr( $spock_css ) . '"';
			}
			?>
	>
		<div class="front_page_section_content_wrap front_page_section_about_content_wrap content_wrap">
			<?php
			// Caption
			$spock_caption = spock_get_theme_option( 'front_page_about_caption' );
			if ( ! empty( $spock_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<h2 class="front_page_section_caption front_page_section_about_caption front_page_block_<?php echo ! empty( $spock_caption ) ? 'filled' : 'empty'; ?>"><?php echo wp_kses( $spock_caption, 'spock_kses_content' ); ?></h2>
				<?php
			}

			// Description (text)
			$spock_description = spock_get_theme_option( 'front_page_about_description' );
			if ( ! empty( $spock_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<div class="front_page_section_description front_page_section_about_description front_page_block_<?php echo ! empty( $spock_description ) ? 'filled' : 'empty'; ?>"><?php echo wp_kses( wpautop( $spock_description ), 'spock_kses_content' ); ?></div>
				<?php
			}

			// Content
			$spock_content = spock_get_theme_option( 'front_page_about_content' );
			if ( ! empty( $spock_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<div class="front_page_section_content front_page_section_about_content front_page_block_<?php echo ! empty( $spock_content ) ? 'filled' : 'empty'; ?>">
					<?php
					$spock_page_content_mask = '%%CONTENT%%';
					if ( strpos( $spock_content, $spock_page_content_mask ) !== false ) {
						$spock_content = preg_replace(
							'/(\<p\>\s*)?' . $spock_page_content_mask . '(\s*\<\/p\>)/i',
							sprintf(
								'<div class="front_page_section_about_source">%s</div>',
								apply_filters( 'the_content', get_the_content() )
							),
							$spock_content
						);
					}
					spock_show_layout( $spock_content );
					?>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>

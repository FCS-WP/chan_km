<div class="front_page_section front_page_section_googlemap<?php
	$spock_scheme = spock_get_theme_option( 'front_page_googlemap_scheme' );
	if ( ! empty( $spock_scheme ) && ! spock_is_inherit( $spock_scheme ) ) {
		echo ' scheme_' . esc_attr( $spock_scheme );
	}
	echo ' front_page_section_paddings_' . esc_attr( spock_get_theme_option( 'front_page_googlemap_paddings' ) );
	if ( spock_get_theme_option( 'front_page_googlemap_stack' ) ) {
		echo ' sc_stack_section_on';
	}
?>"
		<?php
		$spock_css      = '';
		$spock_bg_image = spock_get_theme_option( 'front_page_googlemap_bg_image' );
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
	$spock_anchor_icon = spock_get_theme_option( 'front_page_googlemap_anchor_icon' );
	$spock_anchor_text = spock_get_theme_option( 'front_page_googlemap_anchor_text' );
if ( ( ! empty( $spock_anchor_icon ) || ! empty( $spock_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
	echo do_shortcode(
		'[trx_sc_anchor id="front_page_section_googlemap"'
									. ( ! empty( $spock_anchor_icon ) ? ' icon="' . esc_attr( $spock_anchor_icon ) . '"' : '' )
									. ( ! empty( $spock_anchor_text ) ? ' title="' . esc_attr( $spock_anchor_text ) . '"' : '' )
									. ']'
	);
}
?>
	<div class="front_page_section_inner front_page_section_googlemap_inner
		<?php
		$spock_layout = spock_get_theme_option( 'front_page_googlemap_layout' );
		echo ' front_page_section_layout_' . esc_attr( $spock_layout );
		if ( spock_get_theme_option( 'front_page_googlemap_fullheight' ) ) {
			echo ' spock-full-height sc_layouts_flex sc_layouts_columns_middle';
		}
		?>
		"
			<?php
			$spock_css      = '';
			$spock_bg_mask  = spock_get_theme_option( 'front_page_googlemap_bg_mask' );
			$spock_bg_color_type = spock_get_theme_option( 'front_page_googlemap_bg_color_type' );
			if ( 'custom' == $spock_bg_color_type ) {
				$spock_bg_color = spock_get_theme_option( 'front_page_googlemap_bg_color' );
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
		<div class="front_page_section_content_wrap front_page_section_googlemap_content_wrap
		<?php
		if ( 'fullwidth' != $spock_layout ) {
			echo ' content_wrap';
		}
		?>
		">
			<?php
			// Content wrap with title and description
			$spock_caption     = spock_get_theme_option( 'front_page_googlemap_caption' );
			$spock_description = spock_get_theme_option( 'front_page_googlemap_description' );
			if ( ! empty( $spock_caption ) || ! empty( $spock_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				if ( 'fullwidth' == $spock_layout ) {
					?>
					<div class="content_wrap">
					<?php
				}
					// Caption
				if ( ! empty( $spock_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					?>
					<h2 class="front_page_section_caption front_page_section_googlemap_caption front_page_block_<?php echo ! empty( $spock_caption ) ? 'filled' : 'empty'; ?>">
					<?php
					echo wp_kses( $spock_caption, 'spock_kses_content' );
					?>
					</h2>
					<?php
				}

					// Description (text)
				if ( ! empty( $spock_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					?>
					<div class="front_page_section_description front_page_section_googlemap_description front_page_block_<?php echo ! empty( $spock_description ) ? 'filled' : 'empty'; ?>">
					<?php
					echo wp_kses( wpautop( $spock_description ), 'spock_kses_content' );
					?>
					</div>
					<?php
				}
				if ( 'fullwidth' == $spock_layout ) {
					?>
					</div>
					<?php
				}
			}

			// Content (text)
			$spock_content = spock_get_theme_option( 'front_page_googlemap_content' );
			if ( ! empty( $spock_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				if ( 'columns' == $spock_layout ) {
					?>
					<div class="front_page_section_columns front_page_section_googlemap_columns columns_wrap">
						<div class="column-1_3">
					<?php
				} elseif ( 'fullwidth' == $spock_layout ) {
					?>
					<div class="content_wrap">
					<?php
				}

				?>
				<div class="front_page_section_content front_page_section_googlemap_content front_page_block_<?php echo ! empty( $spock_content ) ? 'filled' : 'empty'; ?>">
				<?php
					echo wp_kses( $spock_content, 'spock_kses_content' );
				?>
				</div>
				<?php

				if ( 'columns' == $spock_layout ) {
					?>
					</div><div class="column-2_3">
					<?php
				} elseif ( 'fullwidth' == $spock_layout ) {
					?>
					</div>
					<?php
				}
			}

			// Widgets output
			?>
			<div class="front_page_section_output front_page_section_googlemap_output">
				<?php
				if ( is_active_sidebar( 'front_page_googlemap_widgets' ) ) {
					dynamic_sidebar( 'front_page_googlemap_widgets' );
				} elseif ( current_user_can( 'edit_theme_options' ) ) {
					if ( ! spock_exists_trx_addons() ) {
						spock_customizer_need_trx_addons_message();
					} else {
						spock_customizer_need_widgets_message( 'front_page_googlemap_caption', 'ThemeREX Addons - Google map' );
					}
				}
				?>
			</div>
			<?php

			if ( 'columns' == $spock_layout && ( ! empty( $spock_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				</div></div>
				<?php
			}
			?>
		</div>
	</div>
</div>

<?php
$spock_woocommerce_sc = spock_get_theme_option( 'front_page_woocommerce_products' );
if ( ! empty( $spock_woocommerce_sc ) ) {
	?><div class="front_page_section front_page_section_woocommerce<?php
		$spock_scheme = spock_get_theme_option( 'front_page_woocommerce_scheme' );
		if ( ! empty( $spock_scheme ) && ! spock_is_inherit( $spock_scheme ) ) {
			echo ' scheme_' . esc_attr( $spock_scheme );
		}
		echo ' front_page_section_paddings_' . esc_attr( spock_get_theme_option( 'front_page_woocommerce_paddings' ) );
		if ( spock_get_theme_option( 'front_page_woocommerce_stack' ) ) {
			echo ' sc_stack_section_on';
		}
	?>"
			<?php
			$spock_css      = '';
			$spock_bg_image = spock_get_theme_option( 'front_page_woocommerce_bg_image' );
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
		$spock_anchor_icon = spock_get_theme_option( 'front_page_woocommerce_anchor_icon' );
		$spock_anchor_text = spock_get_theme_option( 'front_page_woocommerce_anchor_text' );
		if ( ( ! empty( $spock_anchor_icon ) || ! empty( $spock_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
			echo do_shortcode(
				'[trx_sc_anchor id="front_page_section_woocommerce"'
											. ( ! empty( $spock_anchor_icon ) ? ' icon="' . esc_attr( $spock_anchor_icon ) . '"' : '' )
											. ( ! empty( $spock_anchor_text ) ? ' title="' . esc_attr( $spock_anchor_text ) . '"' : '' )
											. ']'
			);
		}
	?>
		<div class="front_page_section_inner front_page_section_woocommerce_inner
			<?php
			if ( spock_get_theme_option( 'front_page_woocommerce_fullheight' ) ) {
				echo ' spock-full-height sc_layouts_flex sc_layouts_columns_middle';
			}
			?>
				"
				<?php
				$spock_css      = '';
				$spock_bg_mask  = spock_get_theme_option( 'front_page_woocommerce_bg_mask' );
				$spock_bg_color_type = spock_get_theme_option( 'front_page_woocommerce_bg_color_type' );
				if ( 'custom' == $spock_bg_color_type ) {
					$spock_bg_color = spock_get_theme_option( 'front_page_woocommerce_bg_color' );
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
			<div class="front_page_section_content_wrap front_page_section_woocommerce_content_wrap content_wrap woocommerce">
				<?php
				// Content wrap with title and description
				$spock_caption     = spock_get_theme_option( 'front_page_woocommerce_caption' );
				$spock_description = spock_get_theme_option( 'front_page_woocommerce_description' );
				if ( ! empty( $spock_caption ) || ! empty( $spock_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					// Caption
					if ( ! empty( $spock_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
						?>
						<h2 class="front_page_section_caption front_page_section_woocommerce_caption front_page_block_<?php echo ! empty( $spock_caption ) ? 'filled' : 'empty'; ?>">
						<?php
							echo wp_kses( $spock_caption, 'spock_kses_content' );
						?>
						</h2>
						<?php
					}

					// Description (text)
					if ( ! empty( $spock_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
						?>
						<div class="front_page_section_description front_page_section_woocommerce_description front_page_block_<?php echo ! empty( $spock_description ) ? 'filled' : 'empty'; ?>">
						<?php
							echo wp_kses( wpautop( $spock_description ), 'spock_kses_content' );
						?>
						</div>
						<?php
					}
				}

				// Content (widgets)
				?>
				<div class="front_page_section_output front_page_section_woocommerce_output list_products shop_mode_thumbs">
					<?php
					if ( 'products' == $spock_woocommerce_sc ) {
						$spock_woocommerce_sc_ids      = spock_get_theme_option( 'front_page_woocommerce_products_per_page' );
						$spock_woocommerce_sc_per_page = count( explode( ',', $spock_woocommerce_sc_ids ) );
					} else {
						$spock_woocommerce_sc_per_page = max( 1, (int) spock_get_theme_option( 'front_page_woocommerce_products_per_page' ) );
					}
					$spock_woocommerce_sc_columns = max( 1, min( $spock_woocommerce_sc_per_page, (int) spock_get_theme_option( 'front_page_woocommerce_products_columns' ) ) );
					echo do_shortcode(
						"[{$spock_woocommerce_sc}"
										. ( 'products' == $spock_woocommerce_sc
												? ' ids="' . esc_attr( $spock_woocommerce_sc_ids ) . '"'
												: '' )
										. ( 'product_category' == $spock_woocommerce_sc
												? ' category="' . esc_attr( spock_get_theme_option( 'front_page_woocommerce_products_categories' ) ) . '"'
												: '' )
										. ( 'best_selling_products' != $spock_woocommerce_sc
												? ' orderby="' . esc_attr( spock_get_theme_option( 'front_page_woocommerce_products_orderby' ) ) . '"'
													. ' order="' . esc_attr( spock_get_theme_option( 'front_page_woocommerce_products_order' ) ) . '"'
												: '' )
										. ' per_page="' . esc_attr( $spock_woocommerce_sc_per_page ) . '"'
										. ' columns="' . esc_attr( $spock_woocommerce_sc_columns ) . '"'
						. ']'
					);
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
}

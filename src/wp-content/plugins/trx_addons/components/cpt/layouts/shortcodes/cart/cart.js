/* global jQuery, TRX_ADDONS_STORAGE */

jQuery(document).on('action.ready_trx_addons', function() {

	"use strict";

	trx_addons_sc_layouts_cart_init_js();
} );

jQuery(window).on( "elementor/frontend/init", function () {
	elementorFrontend.hooks.addAction( "frontend/element_ready/trx_sc_layouts_cart.default", trx_addons_sc_layouts_cart_init_js );
} );

function trx_addons_sc_layouts_cart_init_js ( $scope = false ) {
	var $document = jQuery( document ),
		$body = jQuery( 'body' ),
		is_trx_addons_customizable = $body.find( '.sc_layouts_cart.trx_addons_customizable' ).length > 0,
		is_edit_mode = is_trx_addons_customizable && $scope && elementorFrontend.isEditMode();

	if ( ! $body.hasClass( 'added_to_cart_inited' ) || is_edit_mode ) {

		$body.addClass( 'added_to_cart_inited' );

		var $sc_cart = jQuery( '.sc_layouts_cart' ),
			$cart_button = jQuery( '.sc_layouts_cart_button_wrap' );

		if ( $sc_cart.length > 0 ) {

			// Show/Hide cart
			$sc_cart.filter(':not(.inited)').each( function(idx) {
				var cart = jQuery(this);
				// Show/Hide cart widget as dropdown
				cart.addClass('inited')
					.on('click', '.sc_layouts_cart_icon,.sc_layouts_cart_details', function(e) {
						var link = jQuery( this );
						if ( ! link.data( 'panel-id' ) ) {
							var widget = link.siblings('.sc_layouts_cart_widget').eq(0),
								row    = link.parents('.sc_layouts_row').eq(0),
								cart   = link.parents('.sc_layouts_cart').eq(0);
							if ( widget.length > 0 && widget.text().replace(/\s*/g, '') !== '' ) {
								cart.toggleClass( 'sc_layouts_cart_opened' );
								row.toggleClass( 'sc_layouts_row_on_top' );
								widget.fadeToggle();
								$document.trigger( 'action.opened_dropdown_elements', [$sc_cart] );
							}
							//e.preventDefault();
							//return false;
						}
					})
					.on('click', '.sc_layouts_cart_widget_close', function(e) {
						var link = jQuery( this ),
							row  = link.parents('.sc_layouts_row').eq(0),
							cart = link.parents('.sc_layouts_cart').eq(0);
						row.removeClass( 'sc_layouts_row_on_top' );
						cart.removeClass( 'sc_layouts_cart_opened' );
						link.parent().fadeOut();
						//e.preventDefault();
						//return false;
					} );
			} );

			// Show cart widget as a panel
			$document.on( 'action.prepare_popup_elements', function( e, panel ) {
				if ( panel.hasClass( 'sc_layouts_cart_panel') ) {
					$sc_cart.find( '[data-panel-id="' + panel.attr('id') + '"]' ).each( function() {
						var $cart = jQuery( this ).parents( '.sc_layouts_cart' );
						if ( $cart.is( ':visible' ) ) {
							$cart.toggleClass( 'sc_layouts_cart_opened', true );
						}
					} );

					trx_addons_sc_layouts_cart_panel_close_icon_replace( panel );
				}
			} );

			// Hide cart widget as a panel
			$document.on( 'action.close_popup_elements', function( e, panel ) {
				if ( panel.hasClass( 'sc_layouts_cart_panel' ) ) {
					$sc_cart.find( '[data-panel-id="' + panel.attr('id') + '"]' ).each( function() {
						var $cart = jQuery( this ).parents( '.sc_layouts_cart' );
						if ( $cart.is( ':visible' ) ) {
							$cart.removeClass( 'sc_layouts_cart_opened' );
						}
					} );
				}
			} );

			// Hide cart widget as button
			if ( trx_addons_apply_filters( 'trx_addons_filter_sc_layouts_cart_button_hide_on_scroll', true ) ) {
				$document.on( 'action.scroll_trx_addons', function() {
					$cart_button.each( function() {
						var $self = jQuery( this );
						if ( $self.hasClass( 'sc_layouts_cart_button_showed' ) && ! is_edit_mode ) {
							$self.removeClass( 'sc_layouts_cart_button_showed' );
						}
					} );
				} );
			}

			// WooCommerce Cart: Update amount on the cart button
			jQuery( document.body ).on( 'wc_fragments_refreshed wc_fragments_loaded update_cart added_to_cart removed_from_cart', function(e) {

				jQuery( '.widget_shopping_cart' ).each( function() {
					var $widget = jQuery( this );

					// Update amount value on the cart button
					var total = 0;
					var $total = $widget.find( '.total .amount' );
					if ( ! $total.length ) {
						$total = $widget.find( '.elementor-menu-cart__subtotal .amount' );
					}
					if ( $total.length ) {
						total = $total.text();
					}
					$sc_cart.find( '.sc_layouts_cart_summa' ).text( total );

					// Update count items on the cart button
					var cnt = 0, cart_list = false;
					$widget.find( '.cart_list li' ).each( function() {
						var q = jQuery( this ).find( '.quantity' ).html().split( ' ', 2 );
						if ( ! isNaN( q[0] ) ) {
							cnt += Number( q[0] );
						}
						cart_list = true;
					} );
					if ( ! cart_list ) {
						$widget.find( '.elementor-menu-cart__product' ).each( function() {
							var q = jQuery( this ).find( '.product-quantity' ).text().split( ' ' );
							if ( ! isNaN( q[0] ) ) {
								cnt += Number( q[0] );
							}
						} );
					}
					var $items = $sc_cart.find( '.sc_layouts_cart_items' ).eq(0),
						items = $items.text().split( ' ', 2 );
					items[0] = cnt;
					$items.text( items[0] + ( items.length > 1 ? ' ' + ( cnt == 1 ? $items.data( 'item' ) : $items.data( 'items' ) ) : '' ) );
					jQuery( '.sc_layouts_cart_items_short' ).text( items[0] );
					// Update data-attr on button
					$sc_cart.data( {
						'items': cnt ? cnt : 0,
						'summa': total ? total : 0
					} );
					// Open cart panel
					if ( e.type == 'added_to_cart' ) {
						sc_layouts_cart_panel_open( $sc_cart );
					}

					trx_addons_sc_layouts_cart_item_close_icon_replace( $widget );
				} );

			} );

			// EDD Cart: Update amount on the cart button
			jQuery( document.body ).on( 'edd_cart_item_added edd_cart_item_removed edd_quantity_updated', function ( e, data ) {
				var items = $sc_cart.find( '.sc_layouts_cart_items' ).eq(0).text().split( ' ', 2 );
				items[0] = data.cart_quantity ? data.cart_quantity : data.quantity;
				jQuery( '.sc_layouts_cart_items' ).text( items[0] + ( items.length > 1 ? ' ' + items[1] : '' ) );
				jQuery( '.sc_layouts_cart_items_short' ).text( items[0] );
				jQuery( '.sc_layouts_cart_summa' ).text( data.total );
				// Update data-attr on button
				$sc_cart.data( {
					'items': data.cart_quantity ? data.cart_quantity : 0,
					'summa': data.total ? data.total : 0
				} );
				// Open cart panel
				sc_layouts_cart_panel_open( $sc_cart );
			} );

		}
	}

	// Open a panel with cart widget or display an icon with counter after elements are added to the cart
	function sc_layouts_cart_panel_open( $sc_cart ) {
		if ( ! $sc_cart.hasClass( 'sc_layouts_cart_opened' ) ) {
			var link = $sc_cart.filter( ':visible' ).find( '.sc_layouts_cart_icon,.sc_layouts_cart_details' ).eq(0);
			if ( link.length ) {
				// Show panel
				if ( link.data( 'panel-id' ) ) {
					link.trigger( 'click' );

				// Show button after hidden elements are inited (to skip scroll event inside init_hidden_elements)
				} else if ( link.data( 'button-id' ) ) {
					setTimeout( function() {
						jQuery( '#' + link.data( 'button-id' ) ).toggleClass( 'sc_layouts_cart_button_showed' );
					}, 10 );
				}
			}
		}
	}

	function trx_addons_sc_layouts_cart_panel_close_icon_replace ( panel = false ) {
		if ( ! is_trx_addons_customizable || ! panel ) {
			return false;
		}

		if ( panel.prev( '.sc_layouts_panel_hide_content.trx_addons_customizable' ).length <= 0 && panel.hasClass( 'trx_addons_customizable' ) ) {
			panel.prev( '.sc_layouts_panel_hide_content' ).addClass( 'trx_addons_customizable' );
		}

		if ( panel.prev( '.sc_layouts_panel_hide_content[class*="sc_layouts_cart_panel_style_"]' ).length <= 0 ) {
			var panel_class_list = panel.attr('class').split(/\s+/);
			for ( var i = 0; i < panel_class_list.length; i++ ) {
				if ( panel_class_list[i].indexOf( "sc_layouts_cart_panel_style_" ) !== -1 ) {
					panel.prev( '.sc_layouts_panel_hide_content' ).addClass( panel_class_list[i] + '-panel_overlay' );
					break;
				}
			}
		}

		if ( panel.length > 0 && panel.attr( 'id' ) && panel.find( '.sc_layouts_panel_close.replaced-icon' ).length <= 0 ) {

			var $cart_widget_wrapper = jQuery( '.sc_layouts_item_icon[data-panel-id="' + panel.attr( 'id' ) + '"]' );
			if ( $cart_widget_wrapper.length > 0 && $cart_widget_wrapper.closest( '.sc_layouts_cart' ).length > 0 ) {

				var $cart_item_close_icon = $cart_widget_wrapper.closest( '.sc_layouts_cart' );
				if ( $cart_item_close_icon.length > 0 && $cart_item_close_icon.data( 'panel-close-icon' ) ) {
					panel.find( '.sc_layouts_panel_close' ).html( $cart_item_close_icon.data( 'panel-close-icon' ) );
				}
			}
			panel.find( '.sc_layouts_panel_close' ).addClass( 'replaced-icon' );
		}
	}

	function trx_addons_sc_layouts_cart_item_close_icon_replace ( $widget = false ) {
		if ( ! is_trx_addons_customizable || ! $widget ) {
			return false;
		}

		//Change close button icon 'default'
		var $try_cart_item_close_icon = $widget.closest( '[data-cart-item-close-icon]' );

		if ( $try_cart_item_close_icon.length <= 0 ) {
			$try_cart_item_close_icon = $widget.find( '[data-cart-item-close-icon]' );
		}

		if ( $try_cart_item_close_icon.length > 0 && $try_cart_item_close_icon.data( 'cart-item-close-icon' ) ) {
			var $try_cart_item_close_icon_html = jQuery( $try_cart_item_close_icon.data( 'cart-item-close-icon' ) );
			$widget.find( 'a.remove' ).html( $try_cart_item_close_icon_html.removeAttr( 'id' ) );
		} else {

			//Change close button icon 'panel'
			var $layouts_panel = $widget.closest( '.sc_layouts_panel' );
			if ( $layouts_panel.length > 0 && $layouts_panel.attr( 'id' ) ) {

				var $cart_widget_wrapper = jQuery( '.sc_layouts_item_icon[data-panel-id="' + $layouts_panel.attr( 'id' ) + '"]' );
				if ( $cart_widget_wrapper.length > 0 && $cart_widget_wrapper.closest( '.sc_layouts_cart' ).length > 0 ) {

					var $cart_item_close_icon = $cart_widget_wrapper.closest( '.sc_layouts_cart' );
					if ( $cart_item_close_icon.length > 0 && $cart_item_close_icon.data( 'cart-item-close-icon' ) ) {
						var $cart_item_close_icon_html = jQuery( $cart_item_close_icon.data( 'cart-item-close-icon' ) );
						$widget.find( 'a.remove' ).html( $cart_item_close_icon_html.removeAttr( 'id' ) );
					}
				}
			}
		}
	}

	function trx_addons_sc_layouts_cart_preview_panel_z_index ( $scope = false ) {
		if ( ! $scope ) {
			return false;
		}

		var $closest_parent = $scope.closest( '.elementor-section-wrap > .elementor-element' );

		if ( $closest_parent.find( '.sc_layouts_cart_preview_init' ).length > 0 ) {
			$closest_parent.addClass( 'trx_addons_customizable trx_addons_sc_layouts_cart_general_parent' );
		} else {
			$closest_parent.removeClass( 'trx_addons_customizable trx_addons_sc_layouts_cart_general_parent' );
		}
	}

	if ( is_edit_mode ) {

		trx_addons_sc_layouts_cart_preview_panel_z_index( $scope );

		trx_addons_sc_layouts_cart_item_close_icon_replace( $scope );

		var editPanelID = $scope.find( '[data-panel-id]' ).eq(0);
		if ( editPanelID.length > 0 && editPanelID.data( 'panel-id' ) ) {

			var editPanel = jQuery( '#' + editPanelID.data( 'panel-id' ) );
			if ( editPanel.length > 0 ) {
				trx_addons_sc_layouts_cart_panel_close_icon_replace( editPanel );

				if ( editPanel.prev( '.sc_layouts_panel_hide_content.sc_layouts_cart_preview_init' ).length <= 0 ) {
					editPanel.prev( '.sc_layouts_panel_hide_content' ).addClass( 'sc_layouts_cart_preview_init' )
				}
			}
		}
	}
};
(function ($) {
    'use strict';

	function selectProduct () {
		$( '#motta-product-pbt .product-select' ).on( 'click', 'a', function (e) {
			e.preventDefault();

			var $this				 = $(this).closest( '#motta-product-pbt' ),
				subTotalData      	 = $this.find('#motta-data_subtotal'),
				subTotal             = parseFloat($this.find('#motta-data_subtotal').attr('data-price')),
				totalPriceData       = $this.find('#motta-data_price'),
				totalPrice           = parseFloat($this.find('#motta-data_price').attr('data-price')),
				$discountAll         = parseFloat($this.find('#motta-data_discount-all').data('discount')),
				$quantityDiscountAll = parseFloat($this.find('#motta-data_quantity-discount-all').data('quantity')),
				$subTotal            = $this.find('.motta-pbt-subtotal .woocommerce-Price-amount'),
				$savePrice           = $this.find('.motta-pbt-save-price .woocommerce-Price-amount'),
				$percent             = $this.find('.motta-pbt-save-price .percent'),
				$priceAt             = $this.find('.motta-pbt-total-price .woocommerce-Price-amount'),
				$motta_pbt_ids       = $this.find('input[name="motta_pbt_ids"]'),
				$button              = $this.find('.motta-pbt-add-to-cart'),
				currentPrice 		 = $(this).closest( '.product-select' ).find( '.s-price' ).attr( 'data-price' ),
				$productsVariation   = $this.find('li.product[data-type="variable"]'),
				$motta_variation_id  = $this.find('input[name="motta_variation_id"]'),
				$product_ids 		 = '',
				$productVariation_ids= '',
				$i 					 = 0,
				$numberProduct 		 = [];

			if( $(this).closest( '.product-select' ).hasClass( 'product-current' ) ) {
				return false;
			}

			$(this).closest( '.product-select' ).toggleClass( 'uncheck' );

			$this.find( '.product-select' ).each(function () {
				if ( ! $(this).hasClass( 'uncheck' ) ) {
					if( $(this).hasClass( 'product-current' ) ) {
						$product_ids = $(this).find('.product-id').attr('data-id');
					} else {
						$product_ids += ',' + $(this).find('.product-id').attr('data-id');
					}

					if( parseFloat( $(this).find('.product-id').attr('data-id') ) !== 0 && parseFloat( $(this).find('.s-price').attr('data-price') ) !== 0 ) {
						$numberProduct[$i] = $(this).find('.product-id').attr('data-id');
					}

					$i++;
				}
			});

			$numberProduct = jQuery.grep( $numberProduct, function(n){ return (n); });

			$productsVariation.find( '.product-select' ).each(function () {
				if ( ! $(this).hasClass( 'uncheck' ) ) {
					$productVariation_ids += $(this).find('.product-id').attr('data-id') + ',';
				}

				if( ! $productVariation_ids ) {
					$productVariation_ids = 0;
				}
			});

			$motta_variation_id.attr( 'value', $productVariation_ids );
			$motta_pbt_ids.attr( 'value', $product_ids );
			$button.attr( 'value', $product_ids );

			if ( $(this).closest( '.product-select' ).hasClass( 'uncheck' ) ) {
				$(this).closest( 'li.product' ).addClass( 'un-active' );
				subTotal -= parseFloat(currentPrice);
			} else {
				$(this).closest( 'li.product' ).removeClass( 'un-active' );
				subTotal += parseFloat(currentPrice);
			}

			var savePrice = ( subTotal / 100 ) * $discountAll;

			if( $discountAll || $discountAll !== 0 ) {
				if( $quantityDiscountAll <= $numberProduct.length ) {
					subTotalData.attr( 'data-price', subTotal );
					$subTotal.html(formatNumber(subTotal));
					$savePrice.html(formatNumber(savePrice));
					$percent.text($discountAll);
					$priceAt.html(formatNumber(subTotal - savePrice));
					totalPriceData.attr( 'data-price', subTotal - savePrice );
					$(this).closest( 'ul.products' ).find( '.price-new' ).removeClass( 'hidden' );
					$(this).closest( 'ul.products' ).find( '.price-ori' ).addClass( 'hidden' );
					$(this).closest( 'ul.products' ).find( '.product-variation-price' ).addClass( 'active' );
					$(this).closest( 'ul.products' ).find( '.product-variation-price .price' ).addClass( 'hidden' );
					$(this).closest( 'ul.products' ).find( '.product-variation-price .price-new' ).removeClass( 'hidden' );
				} else {
					subTotalData.attr( 'data-price', subTotal );
					$subTotal.html(formatNumber(subTotal));
					$savePrice.html(formatNumber(0));
					$percent.text(0);
					$priceAt.html(formatNumber(subTotal));
					totalPriceData.attr( 'data-price', subTotal );
					$(this).closest( 'ul.products' ).find( '.price-new' ).addClass( 'hidden' );
					$(this).closest( 'ul.products' ).find( '.price-ori' ).removeClass( 'hidden' );
					$(this).closest( 'ul.products' ).find( '.product-variation-price' ).removeClass( 'active' );
					$(this).closest( 'ul.products' ).find( '.product-variation-price .price' ).removeClass( 'hidden' );
					$(this).closest( 'ul.products' ).find( '.product-variation-price .price-new' ).addClass( 'hidden' );
				}
			} else {
				$priceAt.html(formatNumber(totalPrice));
				totalPriceData.attr( 'data-price', totalPrice );
			}

			check_ready( $this );

			check_button();
		});
	}

	$(document).on( 'found_variation', function(e, t) {
		var $wrap          = $(e['target']).closest('#motta-product-pbt'),
			$product       = $(e['target']).closest('li.product'),
			$productPrice  = $(e['target']).closest('li.product').find( '.s-price' ),
			$productAttrs  = $(e['target']).closest('li.product').find( '.s-attrs' ),
			$productID     = $(e['target']).closest('li.product').find( '.product-id' ),
			$badges        = $(e['target']).closest('li.product').find( '.woocommerce-badges' ),
			$textBadges    = $(e['target']).closest('li.product').find( '.woocommerce-variation-availability .out-of-stock' ).text(),
			$button        = $wrap.find('.motta-pbt-add-to-cart'),
			$display_price = t['display_price'],
			$stock		   = t['is_in_stock'],
			attrs          = {};

		if ( $product.length ) {
			if( $button.val() == 0 ) {
				$button.attr( 'value', $productID );
			}

			if( ! $stock ) {
				$display_price = 0;
				$product.addClass( 'out-of-stock' );
				$badges.find( '.woocommerce-badge' ).addClass( 'hidden' );
				$badges.append( '<span class="sold-out woocommerce-badge">' + $textBadges + '</span>' );
			} else {
				$product.removeClass( 'out-of-stock' );
				$badges.find( '.woocommerce-badge' ).removeClass( 'hidden' );
				$badges.find( '.sold-out' ).remove();
			}

			if ( $product.attr( 'data-type' ) == 'variable' ) {
				$productPrice.attr('data-price', $display_price);
		  	}

			$productID.attr('data-id', t['variation_id']);
			if ( $product.find( '.product-select' ).hasClass('product-current') ) {
				$wrap.find('.motta_variation_id').attr('value', t['variation_id']);
			}

			if ( t['image']['url'] && t['image']['srcset'] ) {
				// change image
				$product.find('.thumbnail .thumb-ori').css( 'opacity', '0' );
				$product.find('.thumbnail .thumb-new').html('<img src="' + t['image']['url'] + '" srcset="' + t['image']['srcset'] + '"/>').css( 'opacity', '1' );
			}

			// change attributes
			if (t['is_purchasable'] && t['is_in_stock']) {
				$product.find('select[name^="attribute_"]').each(function() {
					var attr_name = $(this).attr('name');
					attrs[attr_name] = $(this).val();
				});

				$productAttrs.attr('data-attrs', JSON.stringify(attrs));
			} else {
				$productAttrs.attr('data-attrs', '');
			}
		}

		variationProduct( $product, $productID.attr('data-id'), $stock );
	});

	$(document).on('reset_data', function(e) {
		var $wrap     	      = $(e['target']).closest('#motta-product-pbt'),
			$product          = $(e['target']).closest('li.product'),
			$productPrice     = $(e['target']).closest('li.product').find( '.s-price' ),
			$productAttrs  	  = $(e['target']).closest('li.product').find( '.s-attrs' ),
			$productPriceData = parseFloat($(e['target']).closest('li.product').find( '.s-price' ).attr('data-price')),
			$productID        = $(e['target']).closest('li.product').find( '.product-id' ),
			$badges           = $(e['target']).closest('li.product').find( '.woocommerce-badges' ),
			subTotal          = parseFloat($wrap.find('#motta-data_subtotal').attr('data-price')),
			subTotalData      = $wrap.find('#motta-data_subtotal');

		if ($product.length) {
			$productID.attr( 'data-id', 0 );
			$productAttrs.attr('data-attrs', '');
			$product.removeClass( 'out-of-stock' );

			// reset badges
			$badges.find( '.woocommerce-badge' ).removeClass( 'hidden' );
			$badges.find( '.sold-out' ).remove();

			// reset thumb
			$product.find('.thumbnail .thumb-new').css( 'opacity', '0' );
			$product.find('.thumbnail .thumb-ori').css( 'opacity', '1' );

		  	// reset price
			if ( $product.attr( 'data-type' ) == 'variable' ) {
				$productPrice.attr('data-price', 0);
			}

			if ( $product.find( '.product-select' ).hasClass('product-current') ) {
				$wrap.find('.motta_variation_id').attr( 'value', 0 );
			}

			subTotalData.attr('data-price', subTotal - $productPriceData );
		}

		variationProduct( $product, $productID.attr('data-id') );
	});

	function variationProduct ( $this, $productID = 0 ) {
		if( $this.attr( 'data-type' ) !== 'variable' ) {
			return;
		}

		if( $this.find( '.product-select' ).hasClass( 'unckeck' ) ) {
			return;
		}

		var $pbtProducts            = $this.closest('#motta-product-pbt'),
			$products		        = $pbtProducts.find('li.product'),
			$productsVariable       = $pbtProducts.find('li.product[data-type="variable"]'),
			$subTotal               = $pbtProducts.find('.motta-pbt-subtotal .woocommerce-Price-amount'),
			$priceAt                = $pbtProducts.find('.motta-pbt-total-price .woocommerce-Price-amount'),
			$discountAll            = parseFloat( $pbtProducts.find('#motta-data_discount-all').data('discount')),
			$discountHtml           = $pbtProducts.find('.motta-pbt-save-price .woocommerce-Price-amount'),
			$quantityDiscountAll    = parseFloat($pbtProducts.find('#motta-data_quantity-discount-all').data('quantity')),
			$motta_product_id       = parseFloat( $pbtProducts.find('input[name="motta_product_id"]').val()),
			$motta_variation_id     = $pbtProducts.find('input[name="motta_variation_id"]'),
			$motta_variation_id_val = $motta_variation_id.val(),
			$motta_pbt_ids          = $pbtProducts.find('input[name="motta_pbt_ids"]'),
			$motta_variation_attrs  = $pbtProducts.find('input[name="motta_variation_attrs"]'),
			$button                 = $pbtProducts.find('.motta-pbt-add-to-cart'),
			$percent                = $pbtProducts.find('.motta-pbt-save-price .percent'),
			subTotal                = parseFloat( $pbtProducts.find('#motta-data_subtotal').attr('data-price') ),
			subTotalData            = $pbtProducts.find('#motta-data_subtotal'),
			totalPriceData          = $pbtProducts.find('#motta-data_price'),
			$variation_attrs 		= {},
			$product_ids 		    = '',
			$motta_variation_ids 	= '',
			$savePrice				= parseFloat( $pbtProducts.find('#motta-data_save-price').attr('data-price') ),
			$savePriceData			= $pbtProducts.find('#motta-data_save-price'),
			$total 					= 0,
			$i 						= 0,
			$numberProduct 		    = [];

		$pbtProducts.find( '.product-select' ).each(function () {
			if ( ! $(this).hasClass( 'uncheck' ) ) {
				if( $(this).hasClass( 'product-current' ) ) {
					$product_ids = $(this).find('.product-id').attr('data-id');
				} else {
					$product_ids += ',' + $(this).find('.product-id').attr('data-id');
				}

				if( parseFloat( $(this).find('.product-id').attr('data-id') ) !== 0 && parseFloat( $(this).find('.s-price').attr('data-price') ) !== 0 ) {
					$numberProduct[$i] = $(this).find('.product-id').attr('data-id');
				}

				$i++;
			}
		});

		$numberProduct = jQuery.grep( $numberProduct, function(n){ return (n); });

		$motta_pbt_ids.attr( 'value', $product_ids );
		$button.attr( 'value', $product_ids );

		if( $motta_variation_id_val == 0 ) {
			$motta_variation_id.attr( 'value', $productID );

			$variation_attrs[$productID] = $this.find('.s-attrs').attr('data-attrs');
			$motta_variation_attrs.attr( 'value', JSON.stringify($variation_attrs) );
		} else {
			$productsVariable.find( '.product-select' ).each( function () {
				if ( ! $(this).hasClass( 'uncheck' ) ) {
					var $pid 	= $(this).find('.product-id').attr('data-id'),
						$pattrs = $(this).find('.s-attrs').attr('data-attrs');

					$motta_variation_ids += $pid + ',';
					$variation_attrs[$pid] = $pattrs;
				}
			});

			$motta_variation_id.attr( 'value', $motta_variation_ids );
			$motta_variation_attrs.attr( 'value', JSON.stringify($variation_attrs) );
		}

		$products.find( '.product-select' ).each( function () {
			if ( ! $(this).hasClass( 'uncheck' ) ) {
				var $pPrice = $(this).find('.s-price').attr('data-price');

				$total += parseFloat($pPrice);
			}
		});

		subTotal = $total;

		if( $discountAll !== 0 && $quantityDiscountAll <= $numberProduct.length ) {
			$savePrice = ( subTotal / 100 ) * $discountAll;
			$percent.text($discountAll);

			if( ! $this.hasClass( 'product-primary' ) ) {
				$this.closest( 'ul.products' ).find( '.product-primary .price-ori' ).addClass( 'hidden' );
				$this.closest( 'ul.products' ).find( '.product-primary .price-new' ).removeClass( 'hidden' );
			}
		} else {
			$savePrice = 0;
			$percent.text(0);

			if( ! $this.hasClass( 'product-primary' ) ) {
				$this.closest( 'ul.products' ).find( '.product-primary .price-ori' ).removeClass( 'hidden' );
				$this.closest( 'ul.products' ).find( '.product-primary .price-new' ).addClass( 'hidden' );
			}
		}

		if( $motta_product_id == 0 ) {
			$savePrice = 0;

			if( $motta_variation_id !== 0 && $quantityDiscountAll <= $numberProduct.length ) {
				$savePrice = ( subTotal / 100 ) * $discountAll;
				$percent.text($discountAll);

				$this.closest( 'ul.products' ).find( '.product-variation-price' ).addClass( 'active' );
				$this.closest( 'ul.products' ).find( '.product-variation-price .price' ).addClass( 'hidden' );
				$this.closest( 'ul.products' ).find( '.product-variation-price .price-new' ).removeClass( 'hidden' );
			} else {
				$percent.text(0);
			}
		}

		$savePriceData.attr( 'data-price', $savePrice );
		$discountHtml.html(formatNumber($savePrice));

		subTotalData.attr( 'data-price', subTotal );
		$subTotal.html(formatNumber(subTotal));
		totalPriceData.attr( 'data-price', subTotal - $savePrice );
		$priceAt.html(formatNumber(subTotal - $savePrice ));
		$pbtProducts.find('#motta-data_price').attr( 'data-price', subTotal - $savePrice );

		check_button();
	}

	// Add to cart ajax
    function pbtAddToCartAjax () {

		if (! $('body').hasClass('single-product')) {
			return;
		}

		var $pbtProducts = $('#motta-product-pbt');

        if ( $pbtProducts.length <= 0 ) {
            return;
        }

        $pbtProducts.on('click', '.motta-pbt-add-to-cart.ajax_add_to_cart', function (e) {
            e.preventDefault();

            var $singleBtn = $(this);

			if ( $singleBtn.data('requestRunning') || $singleBtn.hasClass( 'disabled' ) ) {
				return;
			}

			$singleBtn.data('requestRunning', true);
			$singleBtn.addClass('loading');

			var $cartForm = $singleBtn.closest('.pbt-cart'),
				formData = $cartForm.serializeArray(),
				formAction = $cartForm.attr('action');

			if ($singleBtn.val() != '') {
				formData.push({name: $singleBtn.attr('name'), value: $singleBtn.val()});
			}

			$.ajax({
				url: formAction,
				method: 'post',
				data: formData,
				error: function (response) {
					window.location = formAction;
				},
                success: function (response) {
                    if (typeof wc_add_to_cart_params !== 'undefined') {
                        if (wc_add_to_cart_params.cart_redirect_after_add === 'yes') {
                            window.location = wc_add_to_cart_params.cart_url;
                            return;
                        }
                    }

					if ( $(response).find('.woocommerce-message').length > 0 ) {
						$(document.body).trigger('wc_fragment_refresh');
					}

					$singleBtn.removeClass('loading');
					$singleBtn.data('requestRunning', false);

					if (!$.fn.notify) {
						return;
					}

					var $checkIcon = '<span class="motta-svg-icon message-icon"><svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 50 50" width="50px" height="50px"><path d="M 25 2 C 12.309295 2 2 12.309295 2 25 C 2 37.690705 12.309295 48 25 48 C 37.690705 48 48 37.690705 48 25 C 48 12.309295 37.690705 2 25 2 z M 25 4 C 36.609824 4 46 13.390176 46 25 C 46 36.609824 36.609824 46 25 46 C 13.390176 46 4 36.609824 4 25 C 4 13.390176 13.390176 4 25 4 z M 25 11 A 3 3 0 0 0 22 14 A 3 3 0 0 0 25 17 A 3 3 0 0 0 28 14 A 3 3 0 0 0 25 11 z M 21 21 L 21 23 L 22 23 L 23 23 L 23 36 L 22 36 L 21 36 L 21 38 L 22 38 L 23 38 L 27 38 L 28 38 L 29 38 L 29 36 L 28 36 L 27 36 L 27 21 L 26 21 L 22 21 L 21 21 z"/></svg></span>',
						$closeIcon = '<span class="motta-svg-icon svg-active"><svg class="svg-icon" aria-hidden="true" role="img" focusable="false" width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 1L1 14M1 1L14 14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"></path></svg></span>',
						className = 'success',
						$message = mottaPbt.add_to_cart_notice;

					$.notify.addStyle('motta', {
						html: '<div>' + $checkIcon + '<ul class="message-box">' + $message + '</ul>' + $closeIcon + '</div>'
					});

					$.notify('&nbsp', {
						autoHideDelay: 115000,
						className: className,
						style: 'motta',
						showAnimation: 'fadeIn',
						hideAnimation: 'fadeOut'
					});
                }
			});

        });

    };

	function check_ready( $wrap = $( '#motta-product-pbt' ) ) {
		var $products    	= $wrap.find( 'ul.products' ),
			$alert          = $wrap.find( '.motta-pbt-alert' ),
			$selection_name = '',
			$is_selection   = false;

		$products.find( 'li.product' ).each(function() {
			var $this = $(this),
				$type = $this.attr( 'data-type' );

			if ( ! $this.find( '.product-select' ).hasClass( 'uncheck' ) && $type == 'variable' ) {
				$is_selection = true;

				if ( $selection_name === '' ) {
					$selection_name = $this.attr( 'data-name' );
				} else {
					if( $selection_name ) {
						$selection_name += ', ';
					}

					$selection_name += $this.attr( 'data-name' );
				}
			}
		});

		if ( $is_selection ) {
			$alert.html( mottaPbt.alert.replace( '[name]', '<strong>' + $selection_name + '</strong>') ).slideDown();
			$(document).trigger( 'motta_pbt_check_ready', [false, $is_selection, $wrap] );
		} else {
			$alert.html('').slideUp();
			$(document).trigger( 'motta_pbt_check_ready', [true, $is_selection, $wrap] );
		}

		check_button();
	}

	function formatNumber( $number ) {
		var currency       = mottaPbt.currency_symbol,
			thousand       = mottaPbt.thousand_sep,
			decimal        = mottaPbt.decimal_sep,
			price_decimals = mottaPbt.price_decimals,
			currency_pos   = mottaPbt.currency_pos,
			n              = $number;

		if ( parseInt(price_decimals) > 0 ) {
			$number = $number.toFixed(price_decimals) + '';
			var x = $number.split('.');
			var x1 = x[0],
				x2 = x.length > 1 ? decimal + x[1] : '';
			var rgx = /(\d+)(\d{3})/;
			while (rgx.test(x1)) {
				x1 = x1.replace(rgx, '$1' + thousand + '$2');
			}

			n = x1 + x2
		}

		switch (currency_pos) {
			case 'left' :
				return currency + n;
				break;
			case 'right' :
				return n + currency;
				break;
			case 'left_space' :
				return currency + ' ' + n;
				break;
			case 'right_space' :
				return n + ' ' + currency;
				break;
		}
	}

	function productVariationChange() {
        $('.motta-product-pbt .variations_form').on( 'show_variation', function () {
            var $container          = $(this).closest( '.product-summary' ).find( 'div.price' ),
                $price_new          = $(this).find( '.woocommerce-variation-price' ).html();

			if( $container.hasClass( 'hidden' ) ) {
				$container.parent().find( '.product-variation-price' ).remove();
			} else {
				$container.addClass( 'hidden' );
			}

            $container.after( $price_new );
			$container.parent().find( '.product-variation-price' ).addClass( 'active' );

			check_button();
        });

        $('.motta-product-pbt .variations_form').on( 'hide_variation', function () {
            var $container = $(this).closest( '.product-summary' ).find( 'div.price' );

            if( $container.hasClass( 'hidden' ) ) {
				$container.removeClass( 'hidden' );
				$container.parent().find( '.product-variation-price' ).remove();
			}

			check_button();
        });
    }

	function check_button() {
		var $pbtProducts = $('#motta-product-pbt'),
			$total = parseFloat( $pbtProducts.find( '#motta-data_price' ).attr( 'data-price' ) ),
			$pID = parseFloat( $pbtProducts.find( '.motta_product_id' ).val() ),
			$pVID = parseFloat( $pbtProducts.find( '.motta_variation_id' ).val() ),
			$button = $pbtProducts.find( '.motta-pbt-add-to-cart' );

		if( parseFloat( $pbtProducts.find( '.product-select.product-current .s-price' ).attr( 'data-price' ) ) == 0 ) {
			$button.addClass( 'disabled' );
		} else {
			if( $total == 0 || ( $pID == 0 && $pVID == 0 ) ) {
				$button.addClass( 'disabled' );
			} else {
				$button.removeClass( 'disabled' );
			}
		}
	}

    /**
     * Document ready
     */
    $(function () {
		if ( typeof mottaPbt === 'undefined' ) {
			return false;
		}

		if (! $('body').hasClass('single-product')) {
			return;
		}

		var $pbtProducts = $('#motta-product-pbt');

		if ( $pbtProducts.length <= 0) {
			return;
		}

		check_button();

        selectProduct();
		pbtAddToCartAjax();
		check_ready();

		productVariationChange();
    });

})(jQuery);
jQuery(document).ready(function ($) {

	/**
	* Init sticky add to cart
	*/
        var $selector = $( '.motta-sticky-add-to-cart' ),
			$body = $(document.body),
            $page = $( '.single-product' ),
            $button_sticky_atc = $selector.find( '.motta-sticky-add-to-cart__button' ),
            $button_atc = $page.find( '.product' ).find( '.entry-summary' ),
            $tabs_atc = $selector.find( '.motta-sticky-add-to-cart__product-tabs ul li' ),
            $product      = $( '.single-product div.product' ),
            $tabs         = $product.find( '.woocommerce-tabs' );


        if ( !$selector.length ) {
            return;
        }

        if ( !$( 'div.product .entry-summary .cart' ).length ) {
            return;
        }

        var headerHeight = 0,
            cartHeight;

        if ( $body.hasClass( 'admin-bar' ) ) {
            headerHeight += 32;
        }

        if( $product.hasClass( 'product-wc-tabs-dropdown' ) ) {
            $tabs_atc.removeClass( 'active' );
        }

        var isTop = $selector.hasClass( 'motta-sticky-add-to-cart' ) ? true : false;

        function stickyAddToCartToggle() {
            cartHeight = $( '.entry-summary .cart' ).offset().top + $( '.entry-summary .cart' ).outerHeight() - headerHeight;

            if ( window.pageYOffset > cartHeight ) {
                $selector.addClass( 'open' );

                if ( $body.hasClass( 'header-sticky' ) && isTop ) {
                    $body.find( '.site-header' ).addClass( 'motta-header_sticky-act-active' );
                }
            } else {
                $selector.removeClass( 'open' );
                $body.find( '.site-header' ).removeClass( 'motta-header_sticky-act-active' );
            }

            if ( !isTop ) {
                var documentHeight = document.body.scrollHeight;
                if ( window.pageYOffset > documentHeight - window.innerHeight ) {
                    $selector.removeClass( 'open' );
                }
            }
        }

		$(window).on( 'scroll', function () {
            stickyAddToCartToggle();
        }).trigger( 'scroll' );

        //sticky-atc-button
        $button_sticky_atc.on( 'click', function ( event ) {
            if ( $button_sticky_atc.hasClass('product_type_simple') || $button_sticky_atc.hasClass('product_type_external') ) {
                return;
            }

            event.preventDefault();

            $( 'html,body' ).stop().animate({
                scrollTop: $button_atc.offset().top
            },
            'slow');
        });

        //sticky-atc-buy-now
        $selector.find( '.motta-buy-now-button' ).on( 'click', function (event) {
            event.preventDefault();

            $page.find( '.product' ).find( '.motta-buy-now-button' ).trigger('click');
        });

        //sticky-atc-product-tabs
        $tabs_atc.on( 'click', function (event) {
            event.preventDefault();

            var $attr = $( this ).attr( "aria-controls" ),
                $id = $( this ).attr( "id" ),
                $click = '',
                $top = $selector.outerHeight(true) + $tabs_atc.outerHeight(true) + headerHeight + 60,
                $status = false;

            $tabs_atc.removeClass( 'active' );
            $( this ).addClass( 'active' );

            if( $product.hasClass( 'product-wc-tabs-dropdown' ) ) {
                $top = $selector.outerHeight(true) + $tabs_atc.outerHeight(true);

                if ( $product.hasClass( 'layout-6' ) ) {
                    $(this).siblings('.motta-dropdown__content').stop().slideDown("slow");
                    $tabs.find( '.motta-dropdown__title' ).removeClass('active');
                }

                $click = $tabs.find( '.motta-dropdown__title.' + $id );

                if( $click.hasClass( 'active' ) ){
                    $status = true;
                }
            } else if( $product.hasClass( 'layout-2' ) ) {
                $click = $tabs.find( 'li[aria-controls=' + $attr + '] a' );

                $status = false;
            } else {
                $click = $tabs.find( 'li[aria-controls=' + $attr + '] a' );

                if( $tabs.find( 'li[aria-controls=' + $attr + '] ' ).hasClass( 'active' ) ){
                    $status = true;
                }
            }

            var $tab = $( '#' + $attr );

            if( ! $status ){
                $click.trigger( 'click' );

                setTimeout( function () {
                    $( 'html,body' ).stop().animate({
                        scrollTop: $tab.offset().top - $top
                    },
                    'slow' );
                }, 400 );
            }
        });

        $tabs.find( '.motta-tabs-heading li' ).on( 'click', function (event) {
            event.preventDefault();

            var $id = $( this ).attr( "aria-controls" );

            $selector.find( '.motta-product-tabs li' ).removeClass( 'active' );
            if (  $selector.find( '.motta-product-tabs li[aria-controls=' + $id + ']' ) ) {
                $selector.find( '.motta-product-tabs li[aria-controls=' + $id + ']' ).removeClass( 'active' ).addClass( 'active' );
            }
        });

        $tabs.find( '.motta-dropdown__title' ).on( 'click', function (event) {
            event.preventDefault();

            var $id = $( this ).attr( "id" );

            $selector.find( '.motta-product-tabs li' ).removeClass( 'active' );

            if (  $selector.find( '.motta-product-tabs li[id=' + $id + ']' ) ) {
                if (  $( this ).hasClass( 'active' ) ) {
                    $selector.find( '.motta-product-tabs li[id=' + $id + ']' ).removeClass( 'active' );
                }else{
                    $selector.find( '.motta-product-tabs li[id=' + $id + ']' ).addClass( 'active' );
                }
            }

        });

});
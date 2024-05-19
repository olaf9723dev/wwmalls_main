<?php

namespace Motta\Addons\Modules\Sticky_Add_To_Cart;
use Motta\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Frontend {

	/**
	 * Instance
	 *
	 * @var $instance
	 */
	protected static $instance = null;

	/**
	 * Initiator
	 *
	 * @since 1.0.0
	 * @return object
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Instantiate the object.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );

		// Sticky add to cart
		add_action( 'wp_footer', array( $this, 'sticky_single_add_to_cart' ) );

	}

	public function scripts() {
		wp_enqueue_style( 'motta-sticky-add-to-cart', MOTTA_ADDONS_URL . 'modules/sticky-add-to-cart/assets/css/sticky-add-to-cart.css', array(), '1.0.1' );

		wp_enqueue_script('motta-sticky-add-to-cart', MOTTA_ADDONS_URL . 'modules/sticky-add-to-cart/assets/js/sticky-add-to-cart.js');
	}

	/**
	 * Check has sticky add to cart
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	public function has_sticky_atc() {
		global $product;

		if ( $product->is_purchasable() && $product->is_in_stock() ) {
			return true;
		} elseif ( $product->is_type( 'external' ) || $product->is_type( 'grouped' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Add sticky add to cart HTML
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function sticky_single_add_to_cart( $sticky_class ) {
		global $product;

		if ( ! $this->has_sticky_atc() ) {
			return;
		}

		$product_type    = $product->get_type();
		$sticky_class    = 'motta-sticky-add-to-cart product-' . $product_type;

		$sticky_class    .= ' motta-sticky-add-to-cart__layout-' . Helper::get_option( 'product_layout' );

		if ( get_option( 'motta_buy_now', 'yes' ) == 'yes' ) {
			$sticky_class    .= ' has-buy-now';
		}

		$post_thumbnail_id =  $product->get_image_id();

		if ( $post_thumbnail_id ) {
			$gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
			$thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
			$thumbnail_src     = wp_get_attachment_image_src( $post_thumbnail_id, $thumbnail_size );
			$alt_text          = trim( wp_strip_all_tags( get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true ) ) );
		} else {
			$thumbnail_src = wc_placeholder_img_src( 'gallery_thumbnail' );
			$alt_text      = esc_html__( 'Awaiting product image', 'motta-addons' );
		}
		$container_class = 'container';

		$layout = in_array(Helper:: get_option( 'product_layout' ), array( '4', '5', '6' ) );

		if( $layout ) {
			if( in_array( Helper::get_option( 'product_layout' ), array( '6' ) ) ) {
				add_filter( 'motta_sale_percentage' , array( $this, 'motta_sticky_add_to_cart__percentage' ), 10, 2 );
			}
		}

		?>
        <section id="motta-sticky-add-to-cart" class="<?php echo esc_attr( $sticky_class ) ?>">
                <div class="motta-sticky-add-to-cart__content-wrapper product">
				<div class="<?php echo esc_attr($container_class); ?>">
					<div class="motta-sticky-add-to-cart__content product-gallery-summary">
						<div class="motta-sticky-atc__product-image"><img src="<?php echo esc_url( $thumbnail_src[0] ); ?>" alt="<?php echo esc_attr( $alt_text ); ?>" data-o_src="<?php echo esc_url( $thumbnail_src[0] );?>"></div>
							<div class="motta-sticky-add-to-cart__content-product-info">
								<div class="motta-sticky-add-to-cart__content-title"><?php the_title(); ?></div>

								<?php do_action( 'motta_before_sticky_add_to_cart_price' ); ?>

								<span class="motta-sticky-add-to-cart__content-price price">
									<?php echo wp_kses_post( $product->get_price_html() ); ?>
								</span>
							</div>
							<div class="motta-sticky-add-to-cart__button-group">
								<?php
									$classes = $product->is_type( 'simple' ) ? ' product_type_simple add_to_cart_button ajax_add_to_cart' : '';
									$classes = $product->is_type( 'external' ) ? ' product_type_external' : '';

									echo sprintf( '<a href="%s" data-quantity="1" class="motta-sticky-add-to-cart__button motta-button%s" data-product_id="%s">%s %s </a>',
													esc_url( $product->add_to_cart_url() ),
													esc_attr( $classes ),
													esc_attr( $product->get_id() ),
													\Motta\Addons\Helper::get_svg( 'cart-trolley', '', array( 'class' => 'motta-sticky-add-to-cart__icon-cart' ) ),
													esc_html( $product->add_to_cart_text() )
												);
								?>

								<?php do_action( 'motta_after_sticky_add_to_cart_button' ); ?>
							</div>
						</div>
					</div>
                </div>
				<?php if( get_option( 'motta_sticky_add_to_cart_product_tabs_toggle' ) == "yes" ) : ?>
					<div class="motta-sticky-add-to-cart__product-tabs woocommerce-tabs wc-tabs-wrapper">
						<div class="<?php echo esc_attr($container_class); ?>">
							<?php $product_tabs = apply_filters( 'woocommerce_product_tabs', array() );?>
							<ul class="motta-product-tabs" role="tablist">
								<?php do_action( 'motta_before_sticky_add_to_cart_product_tabs' ); ?>

								<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
									<li class="<?php echo esc_attr( $key ); ?>_tab" id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
										<a href="#tab-<?php echo esc_attr( $key ); ?>">
											<?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
										</a>
									</li>
								<?php endforeach; ?>

								<?php do_action( 'motta_after_sticky_add_to_cart__product_tabs' ); ?>
							</ul>
						</div>
					</div>
				<?php endif;?>
        </section><!-- .motta-sticky-add-to-cart -->
		<?php
	}

	/**
	 *
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function motta_sticky_add_to_cart__percentage( $html, $percentage ) {
		$html = '-' . $percentage . '%' . '';
		return $html;
	}
}
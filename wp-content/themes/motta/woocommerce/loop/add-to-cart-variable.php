<?php
/**
 * Variable product card add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart-variable.php.
 *
 */

defined( 'ABSPATH' ) || exit;

global $product;

$attribute_keys  = array_keys( $attributes );
$variations_json = wp_json_encode( $available_variations );

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

    <form class="variations_form cart variations_form_loop"
          action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>"
          method="post" enctype='multipart/form-data'
          data-product_id="<?php echo absint( $product->get_id() ); ?>"
          data-product_variations="<?php echo wc_esc_json($variations_json); // WPCS: XSS ok. ?>">
		<?php do_action( 'woocommerce_before_variations_form' ); ?>

		<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
            <p class="stock out-of-stock"><?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'motta' ) ) ); ?></p>
		<?php else : ?>
            <table class="variations" cellspacing="0">
                <tbody>
				<?php foreach ( $attributes as $attribute_name => $options ) : ?>
                    <tr>
                        <td class="label"><label
                                    for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>"><?php echo wc_attribute_label( $attribute_name ); // WPCS: XSS ok. ?></label>
                        </td>
                        <td class="value">
							<?php
							wc_dropdown_variation_attribute_options(
								array(
									'options'   => $options,
									'attribute' => $attribute_name,
									'product'   => $product,
								)
							);
							?>
                        </td>
                    </tr>
				<?php endforeach; ?>
                </tbody>
            </table>

            <div class="single_variation_wrap">
				<?php

				/**
				 * Hook: woocommerce_single_variation. Used to output the cart button and placeholder for variation data.
				 *
				 * @since 2.4.0
				 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
				 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
				 */
				do_action( 'woocommerce_single_variation' );

				?>
            </div>
		<?php endif; ?>

    </form>

<?php

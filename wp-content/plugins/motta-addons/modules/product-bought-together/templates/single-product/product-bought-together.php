<?php
/**
 * Product Bought Together
 *
 */

defined( 'ABSPATH' ) || exit;

global $product;
?>

<div class="motta-product-pbt" id="motta-product-pbt">
	<h3 class="motta-product-pbt__title"><?php esc_html_e( 'Frequently Bought Together', 'motta-addons' ); ?></h3>
	<div class="motta-product-pbt__wrapper">
		<ul class="products product-card-layout-1">
			<?php
			$pids = [];
			$total_price = 0;
			$discount = intval( get_post_meta( $product->get_id(), 'motta_pbt_discount_all', true ) );
			$checked_all = get_post_meta( $product->get_id(), 'motta_pbt_checked_all', true );
			$quantity_discount_all = intval( get_post_meta( $product->get_id(), 'motta_pbt_quantity_discount_all', true ) );

			$countProduct = empty( $checked_all ) ? 1 : count( $product_ids );

			foreach ( $product_ids as $product_id ) {
				$product_id = apply_filters( 'wpml_object_id', $product_id, 'product' );
				$item       = wc_get_product( $product_id );
				$classPrice = '';

				if ( empty( $item ) ) {
					continue;
				}

				if ( $item->get_stock_status() == 'outofstock' || $item->is_type( 'grouped' ) || $item->is_type( 'external' )  ) {
					$key = array_search( $product_id, $product_ids );
					if ( $key !== false ) {
						unset( $product_ids[ $key ] );
					}
					continue;
				}

				$data_id = $item->get_id();
				if ( $item->get_parent_id() > 0 ) {
					$data_id = $item->get_parent_id();
				}

				if( empty( $checked_all ) ) {
					$total_price = $product->is_type( 'variable' ) ? 0 : wc_get_price_to_display( $product );
				} else {
					$total_price  += $item->is_type( 'variable' ) ? 0 : wc_get_price_to_display( $item );
				}

				$current_class_li = $current_class = '';
				if ( $item->get_id() == $product->get_id() ) {
					$current_class_li = 'product-primary';
					$current_class = 'product-current';
				}

				if( $item->get_id() !== $product->get_id() && empty( $checked_all ) ) {
					$current_class_li .= ' un-active';
					$current_class .= ' uncheck';
				}

				$pids[] = $item->is_type( 'variable' ) ? 0 : $item->get_id();

				$product_name = $item->get_name();
				?>
				<li id="pbt-product-<?php echo esc_attr( $item->get_id() ); ?>" class="product <?php echo esc_attr( $current_class_li ); ?>" data-type="<?php echo esc_attr( $item->get_type() ); ?>" data-name="<?php echo esc_attr( $item->get_name() ); ?>">
					<div class="product-content">
						<a class="thumbnail" href="<?php echo esc_url( $item->get_permalink() ) ?>">
							<span class="thumb-ori">
								<?php echo wp_get_attachment_image( $item->get_image_id(), 'shop_catalog' ); ?>
							</span>
							<?php if( $item->is_type( 'variable' ) ) : ?>
								<span class="thumb-new"></span>
							<?php endif; ?>
							<?php
								$badges = \Motta\WooCommerce\Badges::get_badges( $item );
								if( $badges ) {
									printf( '<span class="woocommerce-badges variations-attribute-change">%s</span>', implode( '', $badges ) );
								}
							?>
						</a>
						<div class="product-summary">
							<h2 class="woocommerce-loop-product__title">
								<a class="woocommerce-LoopProduct-link woocommerce-loop-product__link" href="<?php echo esc_url( $item->get_permalink() ) ?>">
									<?php echo esc_html( $product_name ); ?>
								</a>
							</h2>
							<?php if ( $item->get_rating_count() ) : ?>
								<div class="motta-rating">
									<?php
										echo wc_get_rating_html( $item->get_average_rating() );
										if( intval( $item->get_review_count() ) > 0 ) {
											?>
											<div class="review-count">(<?php echo '<span class="count">' . esc_html( $item->get_review_count() ) . '</span>'; ?>)</div>
											<?php
										}
									?>
								</div>
							<?php endif; ?>
							<?php if ( ! $item->is_type( 'variable' ) && $discount !== 0 ) : ?>
								<?php $classPrice = ( ! empty( $checked_all ) || empty( $checked_all ) ) && $quantity_discount_all <= $countProduct ? '' : 'hidden'; ?>
								<div class="price price-new <?php echo esc_attr( $classPrice ); ?>">
									<?php
										$sale_price = $item->get_price() * ( 100 - (float) $discount ) / 100;
										$save_price = $item->get_price() - $sale_price;
										echo wc_format_sale_price( $item->get_price(), $sale_price ) . $item->get_price_suffix( $sale_price );
										$classPrice = empty( $classPrice ) ? 'price-ori hidden' : 'price-ori';
									?>
								</div>
							<?php endif; ?>
							<div class="price <?php echo esc_attr( $classPrice ); ?>">
								<?php
									echo wp_kses_post( $item->get_price_html() );
								?>
							</div>
							<?php
							if( $item->is_type( 'variable' ) ) {
								$attributes           = $item->get_variation_attributes();
								$available_variations = $item->get_available_variations();

								if ( is_array( $attributes ) && ( count( $attributes ) > 0 ) ) {

									if( $discount !== 0 ) {
										foreach( $available_variations as $key => $available_variation ) {
											$_p = $available_variation['display_price'];
											$_p_html = $available_variation['price_html'];
											$_class = $quantity_discount_all <= $countProduct ? 'active' : '';
											$_ps = $_p * ( 100 - (float) $discount ) / 100;
											$available_variations[$key]['price_html'] = '<div class="product-variation-price ' . esc_attr( $_class ) . '">' . $_p_html . '<span class="price price-new">' . wc_format_sale_price( $_p, $_ps ) . $item->get_price_suffix( $_ps ) . '</span></div>';
										}
									}

									$attribute_keys  = array_keys( $attributes );
									$variations_json = wp_json_encode( $available_variations );
									$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );
									?>
									<form class="variations_form cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $item->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $item->get_id() ); ?>" data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok. ?>">

										<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
											<p class="stock out-of-stock"><?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'woocommerce' ) ) ); ?></p>
										<?php else : ?>
											<table class="variations" cellspacing="0" role="presentation">
												<tbody>
													<?php foreach ( $attributes as $attribute_name => $options ) : ?>
														<tr>
															<th class="label"><label for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>"><?php echo wc_attribute_label( $attribute_name ); // WPCS: XSS ok. ?></label></th>
															<td class="value">
																<?php
																	wc_dropdown_variation_attribute_options(
																		array(
																			'options'   => $options,
																			'attribute' => $attribute_name,
																			'product'   => $item,
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
								}
							}
							?>
						</div>
						<div class="product-select <?php echo esc_attr($current_class); ?>">
							<?php
								echo sprintf(
									'<a class="product-id" href="%s" data-id="%s" data-title="%s"><span class="select"></span><span class="p-title">%s</span></a>
									<span class="s-price hidden" data-price="%s">(%s)</span>%s',
									esc_url( $item->get_permalink() ),
									esc_attr( $item->get_id() ),
									esc_attr( $product_name ),
									esc_html__( 'Add to Package', 'motta-addons' ),
									$item->is_type( 'variable' ) ? 0 : esc_attr( $item->get_price() ),
									$item->get_price_html(),
									$item->is_type( 'variable' ) ? '<span class="s-attrs hidden" data-attrs=""></span>' : ''
								);
							?>
						</div>
					</div>
				</li>
				<?php
			}
			?>
		</ul>
		<div class="product-buttons">
			<div class="price-box__title"><?php esc_html_e( 'Special Price', 'motta-addons' ); ?></div>
			<?php
				if( empty( $checked_all ) ) {
					$pids = $product->get_id();
					$numberProduct = count( (array) $pids );
				} else {
					$numberProduct = count( $pids );
					$pids = implode( ',', $pids );
				}
			?>
		<?php if( $discount !== 0 ) : ?>
			<?php
				if( $product->is_type( 'variable' ) ) {
					$save_price = 0;
				} else {
					$save_price = ( $total_price / 100 ) * (float) $discount;
				}
			?>
			<div class="price-box price-box__subtotal">
				<span class="label"><?php esc_html_e( 'SubTotal: ', 'motta-addons' ); ?></span>
				<span class="s-price motta-pbt-subtotal"><?php echo wc_price( $total_price ); ?></span>
				<input type="hidden" data-price="<?php echo esc_attr( $total_price ); ?>" id="motta-data_subtotal">
			</div>
			<div class="price-box price-box__save">
				<span class="label"><?php esc_html_e( 'Save: ', 'motta-addons' ); ?></span>
				<span class="s-price motta-pbt-save-price"><?php echo wc_price( $quantity_discount_all <= $numberProduct ? $save_price : 0 ); ?> (<span class="percent"><?php echo esc_html( $quantity_discount_all <= $numberProduct ? $discount : 0 ); ?></span>%)</span>
				<input type="hidden" data-price="<?php echo esc_attr( $save_price ); ?>" id="motta-data_save-price">
				<input type="hidden" data-discount="<?php echo esc_attr( $discount ); ?>" id="motta-data_discount-all">
				<input type="hidden" data-quantity="<?php echo esc_attr( $quantity_discount_all ); ?>" id="motta-data_quantity-discount-all">
			</div>
			<?php $total_price = $quantity_discount_all <= $numberProduct ? $total_price - $save_price : $total_price; ?>
		<?php else : ?>
			<div class="price-box price-box__subtotal hidden">
				<input type="hidden" data-price="<?php echo esc_attr( $total_price ); ?>" id="motta-data_subtotal">
			</div>
		<?php endif; ?>
			<div class="price-box price-box__total">
				<span class="label"><?php esc_html_e( 'Total: ', 'motta-addons' ); ?></span>
				<span class="s-price motta-pbt-total-price"><?php echo wc_price( $total_price ); ?></span>
				<input type="hidden" data-price="<?php echo esc_attr( $total_price ); ?>" id="motta-data_price">
			</div>
			<form class="pbt-cart cart" action="<?php echo esc_url( $product->get_permalink() ); ?>" method="post"
					enctype="multipart/form-data">
				<input class="motta_product_id" name="motta_product_id" type="hidden" data-title="<?php echo esc_attr( $product->get_name() ); ?>" value="<?php echo esc_attr( $product->is_type( 'variable' ) || ! $product->is_in_stock() ? 0 : $product->get_id() ); ?>">
				<input type="hidden" name="motta_variation_id" class="motta_variation_id" value="0">
				<input type="hidden" name="motta_variation_attrs" class="motta_variation_attrs" value="0">
				<input name="motta_pbt_ids" type="hidden" value="<?php echo esc_attr( $pids ); ?>">
				<button type="submit" name="motta_pbt_add_to_cart" value="<?php echo esc_attr( $pids ); ?>"	class="motta-pbt-add-to-cart ajax_add_to_cart"><?php esc_html_e( 'Add Selected To Cart', 'motta-addons' ); ?></button>
			</form>
		</div>
	</div>
	<div class="motta-pbt-alert woocommerce-message"></div>
</div>
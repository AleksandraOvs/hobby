<?php

/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

defined('ABSPATH') || exit;

?>

<!--== Start Cart Page Wrapper ==-->
<div id="cart-page-wrapper">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<?php do_action('woocommerce_before_cart'); ?>
				<div class="shopping-cart-list-area">

					<form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
						<div class="shopping-cart-table table-responsive">
							<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents table table-bordered text-center">
								<thead>
									<tr>
										<th>Товары</th>
										<th>Цена</th>
										<th>Количество</th>
										<th>Итого</th>
									</tr>
								</thead>

								<tbody>
									<?php do_action('woocommerce_before_cart_contents'); ?>
									<?php
									foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
										$_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
										$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

										if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
											$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
									?>


											<tr class="woocommerce-cart-form__cart-item cart_item">
												<td class="product-list">
													<div class="cart-product-item d-flex align-items-center">

														<div class="remove-icon product-remove">
															<?php
															echo sprintf(
																'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="fa fa-trash-o"></i></a>',
																esc_url(wc_get_cart_remove_url($cart_item_key)),
																esc_html__('Remove this item', 'woocommerce'),
																esc_attr($product_id),
																esc_attr($_product->get_sku())
															);
															?>
														</div>

														<?php
														$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

														if (! $product_permalink) {
															echo $thumbnail; // PHPCS: XSS ok.
														} else {
															printf('<a href="%s" class="product-thumb">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
														}
														?>

														<?php
														if (! $product_permalink) {
															echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
														} else {
															echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s" class="product-name">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
														}
														?>
													</div>
												</td>
												<td>
													<span class="price"><?php echo WC()->cart->get_product_price($_product) ?></span>
												</td>
												<td class="product-quantity">
													<?php
													if ($_product->is_sold_individually()) {
														$product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
													} else {
														$product_quantity = woocommerce_quantity_input(
															array(
																'input_name'   => "cart[{$cart_item_key}][qty]",
																'input_value'  => $cart_item['quantity'],
																'max_value'    => $_product->get_max_purchase_quantity(),
																'min_value' => $_product->get_min_purchase_quantity(),
																'product_name' => $_product->get_name(),
															),
															$_product,
															false
														);
													}
													echo 	$product_quantity;
													?>
												</td>
												<td>
													<span class="price"><?php echo WC()->cart->get_product_subtotal($_product, $cart_item['quantity']) ?></span>
												</td>
											</tr>

									<?php }
									} ?>


									<?php do_action('woocommerce_after_cart_contents'); ?>
								</tbody>
							</table>
						</div>


						<div class="cart-coupon-update-area d-sm-flex justify-content-between align-items-center">
							<?php do_action('woocommerce_cart_actions'); ?>
							<?php if (wc_coupons_enabled()) : ?>
								<div class="coupon-form-wrap">

									<input type="text" autocomplete="off" name="coupon_code" id="coupon_code" placeholder="Код купона" />
									<button type="submit" class="btn-apply" name="apply_coupon">Применить купон</button>

								</div>
							<?php endif; ?>

							<div class="cart-update-buttons mt-xs-14">
								<button type="submit" name="update_cart" class="btn-update-cart">Обновить корзину</button>
								<?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<!-- Cart Calculate Area -->
				<?php
				woocommerce_cart_totals();
				?>
			</div>
		</div>
	</div>
</div>
<!--== End Cart Page Wrapper ==-->

<?php do_action('woocommerce_after_cart'); ?>
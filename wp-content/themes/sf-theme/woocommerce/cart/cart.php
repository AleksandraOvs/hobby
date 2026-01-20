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



	<?php do_action('woocommerce_before_cart'); ?>
	<div class="shopping-cart-list-area">

		<form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">

			<div class="shop_table shop_table_responsive cart woocommerce-cart-form__contents">




				<?php
				foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
					$_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
					$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

					if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
						$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
				?>


						<div class="cart-flex__row cart_item">

							<div class="cart-flex__col cart-flex__col--product">

								<!-- <div class="remove-icon product-remove">
                                                                                <?php
																				// echo sprintf(
																				//         '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="fa fa-trash-o"></i></a>',
																				//         esc_url(wc_get_cart_remove_url($cart_item_key)),
																				//         esc_html__('Remove this item', 'woocommerce'),
																				//         esc_attr($product_id),
																				//         esc_attr($_product->get_sku())
																				// );
																				?>
                                                                        </div> -->

								<div class="cart-product-item">
									<?php
									$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

									if (! $product_permalink) {
										echo $thumbnail; // PHPCS: XSS ok.
									} else {
										printf('<a href="%s" class="product-thumb">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
									}
									?>
									<div class="cart-product-item__name">

										<?php
										if (! $product_permalink) {
											echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
										} else {
											echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s" class="product-name">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
										}
										?>

										<?php
										$sku = $_product->get_sku();

										if ($sku) {
											echo '<div class="product-sku">Артикул: ' . esc_html($sku) . '</div>';
										}
										?>
									</div>


								</div>


							</div>

							<div class="cart_item__inner">
								<div class="cart-flex__col cart-flex__col--weight">
									<div class="cart-flex__col__label">Вес:</div>

									<?php
									if ($_product && $_product->has_weight()) {

										$single_weight = (float) $_product->get_weight();
										$total_weight  = $single_weight * (int) $cart_item['quantity'];

										echo esc_html(wc_format_weight($total_weight));
									} else {
										echo '—';
									}
									?>
								</div>

								<div class="cart-flex__col cart-flex__col--price">
									<div class="cart-flex__col__label">Цена:</div>
									<span class="price"><?php echo WC()->cart->get_product_price($_product); ?></span>
								</div>


								<div class="cart-flex__col cart-flex__col--qty">
									<div class="cart-flex__col__label">Кол-во:</div>
									<?php
									if ($_product->is_sold_individually()) {

										echo sprintf(
											'1 <input type="hidden" name="cart[%s][qty]" value="1" />',
											$cart_item_key
										);
									} else {

										woocommerce_quantity_input(
											array(
												'input_name'   => "cart[{$cart_item_key}][qty]",
												'input_value'  => $cart_item['quantity'],
												'max_value'    => $_product->get_max_purchase_quantity(),
												'min_value'    => '0',
												'product_name' => $_product->get_name(),
											),
											$_product,
											true
										);
									}
									?>
								</div>

								<div class="cart-flex__col cart-flex__col--total">
									<div class="cart-flex__col__label">Сумма:</div>
									<span class="price"><?php echo WC()->cart->get_product_subtotal($_product, $cart_item['quantity']) ?></span>
								</div>

								<div class="product-remove">
									<a href="<?php echo esc_url(wc_get_cart_remove_url($cart_item_key)); ?>"
										class="remove"
										aria-label="<?php esc_attr_e('Remove this item', 'woocommerce'); ?>"
										data-product_id="<?php echo esc_attr($product_id); ?>"
										data-product_sku="<?php echo esc_attr($_product->get_sku()); ?>">
										<svg width="20" height="22" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M17.75 7.75L15.755 19.096C15.6736 19.5594 15.4315 19.9792 15.0712 20.2817C14.7109 20.5842 14.2555 20.75 13.785 20.75H5.715C5.24454 20.75 4.78913 20.5842 4.42882 20.2817C4.06852 19.9792 3.82639 19.5594 3.745 19.096L1.75 7.75M18.75 4.75H13.125M13.125 4.75V2.75C13.125 2.21957 12.9143 1.71086 12.5392 1.33579C12.1641 0.960714 11.6554 0.75 11.125 0.75H8.375C7.84457 0.75 7.33586 0.960714 6.96079 1.33579C6.58571 1.71086 6.375 2.21957 6.375 2.75V4.75M13.125 4.75H6.375M0.75 4.75H6.375" stroke="#797979" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
										</svg>

									</a>
								</div>
							</div>


						</div>

				<?php }
				} ?>



			</div>



			<div class="cart-coupon-update-area">

				<?php if (wc_coupons_enabled()) : ?>
					<div class="coupon-form-wrap">

						<input type="text" autocomplete="off" name="coupon_code" id="coupon_code" placeholder="Код купона" />
						<button type="submit" class="btn-apply" name="apply_coupon">
							<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M8 0C3.58175 0 0 3.58175 0 8C0 12.4185 3.58175 16 8 16C12.4185 16 16 12.4185 16 8C16 3.58175 12.4185 0 8 0ZM8 15.0157C4.14025 15.0157 1 11.8597 1 7.99997C1 4.14022 4.14025 0.999969 8 0.999969C11.8598 0.999969 15 4.14023 15 7.99997C15 11.8597 11.8598 15.0157 8 15.0157ZM11.1927 5.07275L6.49898 9.796L4.38523 7.68225C4.18998 7.487 3.87348 7.487 3.67798 7.68225C3.48273 7.8775 3.48273 8.194 3.67798 8.38925L6.15273 10.8643C6.34798 11.0593 6.66448 11.0593 6.85998 10.8643C6.88248 10.8418 6.90175 10.8172 6.91925 10.7917L11.9003 5.77998C12.0953 5.58473 12.0953 5.26823 11.9003 5.07275C11.7048 4.8775 11.3882 4.8775 11.1927 5.07275Z" fill="#cacaca" />
							</svg>

							<span>Применить купон</span>
						</button>

					</div>
				<?php endif; ?>

				<div class="cart-update-buttons mt-xs-14">
					<button type="submit" name="update_cart" class="btn-update-cart">Обновить корзину</button>
					<?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
				</div>
			</div>
		</form>
	</div>

	<!-- Cart Calculate Area -->
	<?php
	woocommerce_cart_totals();
	?>

</div>
<!--== End Cart Page Wrapper ==-->

<?php do_action('woocommerce_after_cart'); ?>
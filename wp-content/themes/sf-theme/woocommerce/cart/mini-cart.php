<?php
defined('ABSPATH') || exit;

do_action('woocommerce_before_mini_cart'); ?>

<div class="widget_shopping_cart_content">
	<?php if (WC()->cart && ! WC()->cart->is_empty()) : ?>

		<ul class="woocommerce-mini-cart cart_list product_list_widget <?php echo esc_attr($args['list_class']); ?>">
			<?php
			do_action('woocommerce_before_mini_cart_contents');

			foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
				$_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
				$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

				if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key)) {
					$product_name      = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
					$thumbnail         = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
					$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
			?>
					<li class="woocommerce-mini-cart-item">

						<figure class="product-thumb">
							<?php if (! empty($thumbnail)) { ?>
								<a href="<?php echo esc_url($product_permalink); ?>"><?php echo $thumbnail; ?></a>
							<?php } else {
							?>
								<a href="<?php echo esc_url($product_permalink); ?>"><?php echo get_stylesheet_directory_uri() . '/assets/img/svg/placeholder.svg' ?></a>
							<?php
							} ?>


							<?php
							// Кнопка удаления товара
							echo apply_filters(
								'woocommerce_cart_item_remove_link',
								sprintf(
									'<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">
									<svg width="9" height="9" viewBox="0 0 9 9" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M4.114 4.822L7.36 8.068C7.45333 8.16133 7.568 8.21133 7.704 8.218C7.84 8.22467 7.96133 8.17467 8.068 8.068C8.17467 7.96133 8.228 7.84333 8.228 7.714C8.228 7.58467 8.17467 7.46667 8.068 7.36L4.822 4.114L8.068 0.868C8.16133 0.774666 8.21133 0.66 8.218 0.524C8.22467 0.388 8.17467 0.266666 8.068 0.159999C7.96133 0.0533327 7.84333 0 7.714 0C7.58467 0 7.46667 0.0533327 7.36 0.159999L4.114 3.406L0.868 0.159999C0.774666 0.066666 0.66 0.0166664 0.524 0.00999975C0.388 0.00333309 0.266666 0.0533327 0.159999 0.159999C0.0533327 0.266666 0 0.384666 0 0.513999C0 0.643333 0.0533327 0.761333 0.159999 0.868L3.406 4.114L0.159999 7.36C0.066666 7.45333 0.0166664 7.56833 0.00999975 7.705C0.00333309 7.84033 0.0533327 7.96133 0.159999 8.068C0.266666 8.17467 0.384666 8.228 0.513999 8.228C0.643333 8.228 0.761333 8.17467 0.868 8.068L4.114 4.822Z" fill="white"/>
</svg>

									</a>',
									esc_url(wc_get_cart_remove_url($cart_item_key)),
									esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($product_name))),
									esc_attr($product_id),
									esc_attr($cart_item_key),
									esc_attr($_product->get_sku())
								),
								$cart_item_key
							);
							?>

						</figure>

						<div class="product-details">
							<h2 class="product-title">
								<?php if (! empty($product_permalink)) : ?>
									<a href="<?php echo esc_url($product_permalink); ?>"><?php echo $product_name; ?></a>
								<?php else : ?>
									<?php echo $product_name; ?>
								<?php endif; ?>
							</h2>

							<div class="prod-cal d-flex flex-column">
								<?php
								$qty = (int) $cart_item['quantity'];

								// 1. Базовая цена за штуку (витрина)
								$base_unit_price = (float) $_product->get_price();

								// 2. Цена за штуку с учётом всех скидок корзины
								$discounted_unit_price = $qty > 0
									? (float) $cart_item['line_total'] / $qty
									: 0;
								?>

								<?php if ($discounted_unit_price !== $base_unit_price) { ?>


									<div class="price-discounted">
										<p>Цена:</p>

										<div class="price-base">
											<?php echo wc_price($discounted_unit_price); ?>
											<div class="price-base _old">
												<div class="screen-reader-text">
													Цена с учетом скидки
												</div> <?php echo wc_price($base_unit_price); ?>
											</div>

										</div>
									</div>


								<?php } else {
								?>
									<div class="price-base">
										<span class="price"><?php echo wc_price($base_unit_price) . '/шт.'; ?></span>
										<!-- Количество -->
										<div class="quantity">
											Кол-во: <?php echo $qty; ?>
										</div>
									</div>



								<?php
								} ?>

								<?php
								// 3. Итог за количество

								//echo '<div class="product-total">' . $total_price = (float) $cart_item['line_total'] . '</div>';
								?>


								<!-- <span class="price-total">
									<?php //echo sprintf('%d × %s = %s', $qty, wc_price($discounted_unit_price), wc_price($total_price)); 
									?>
								</span> -->
							</div>
						</div>

						<?php echo wc_get_formatted_cart_item_data($cart_item); ?>
					</li>
			<?php
				}
			}

			do_action('woocommerce_mini_cart_contents');
			?>
		</ul>

		<div class="woocommerce-mini-cart__footer">
			<p class="woocommerce-mini-cart__total total">
				<?php do_action('woocommerce_widget_shopping_cart_total'); ?>
			</p>

			<div class="minicart-btn-group">
				<a href="<?php echo wc_get_cart_url(); ?>" class="btn">Просмотр корзины</a>
				<a href="<?php echo wc_get_checkout_url(); ?>" class="btn">Оформление заказа</a>
			</div>
		</div>

	<?php else : ?>

		<p class="woocommerce-mini-cart__empty-message"><?php esc_html_e('No products in the cart.', 'woocommerce'); ?></p>

	<?php endif; ?>

	<?php do_action('woocommerce_after_mini_cart'); ?>
</div>
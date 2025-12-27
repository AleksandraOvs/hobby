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
					$product_price     = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
					$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
			?>
					<li class="woocommerce-mini-cart-item">


						<figure class="product-thumb">
							<?php if (! empty($product_permalink)) : ?>
								<a href="<?php echo esc_url($product_permalink); ?>"><?php echo $thumbnail; ?></a>
							<?php else : ?>
								<?php echo $thumbnail; ?>
							<?php endif; ?>

							<?php
							// Кнопка удаления товара
							echo apply_filters(
								'woocommerce_cart_item_remove_link',
								sprintf(
									'<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&#215;</a>',
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

							<div class="prod-cal d-flex align-items-center">
								<?php
								// Количество и цена через стандартный фильтр WooCommerce
								echo apply_filters(
									'woocommerce_widget_cart_item_quantity',
									'<span class="quantity">' . sprintf('%s &times; %s', $cart_item['quantity'], $product_price) . '</span>',
									$cart_item,
									$cart_item_key
								);
								?>
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
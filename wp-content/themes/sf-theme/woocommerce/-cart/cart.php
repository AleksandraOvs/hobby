<?php
defined('ABSPATH') || exit;

do_action('woocommerce_before_cart'); ?>

<form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
	<?php do_action('woocommerce_before_cart_table'); ?>

	<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
		<thead>
			<tr>
				<th class="product-remove"><span class="screen-reader-text"><?php esc_html_e('Remove item', 'woocommerce'); ?></span></th>
				<th class="product-thumbnail"><span class="screen-reader-text"><?php esc_html_e('Thumbnail image', 'woocommerce'); ?></span></th>
				<th scope="col" class="product-name"><?php esc_html_e('Product', 'woocommerce'); ?></th>
				<th scope="col" class="product-price"><?php esc_html_e('Price', 'woocommerce'); ?></th>
				<th scope="col" class="product-quantity"><?php esc_html_e('Quantity', 'woocommerce'); ?></th>
				<th scope="col" class="product-subtotal"><?php esc_html_e('Subtotal', 'woocommerce'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php do_action('woocommerce_before_cart_contents'); ?>

			<?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
				$_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
				$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

				if (!$_product || !$_product->exists() || $cart_item['quantity'] <= 0 || !apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
					continue;
				}

				$product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
				$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
			?>
				<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">

					<td class="product-remove">
						<?php
						echo apply_filters(
							'woocommerce_cart_item_remove_link',
							sprintf(
								'<a role="button" href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
								esc_url(wc_get_cart_remove_url($cart_item_key)),
								esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($product_name))),
								esc_attr($product_id),
								esc_attr($_product->get_sku())
							),
							$cart_item_key
						);
						?>
					</td>

					<td class="product-thumbnail">
						<?php
						$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
						if (!$product_permalink) {
							echo $thumbnail;
						} else {
							printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail);
						}
						?>
					</td>

					<td class="product-name" data-title="<?php esc_attr_e('Product', 'woocommerce'); ?>">
						<?php
						if (!$product_permalink) {
							echo wp_kses_post($product_name);
						} else {
							echo wp_kses_post(sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $product_name));
						}
						echo wc_get_formatted_cart_item_data($cart_item);
						?>
					</td>

					<td class="product-price" data-title="<?php esc_attr_e('Price', 'woocommerce'); ?>">
						<?php echo WC()->cart->get_product_price($_product); ?>
					</td>

					<td class="product-quantity" data-title="<?php esc_attr_e('Quantity', 'woocommerce'); ?>">
						<?php if ($_product->is_sold_individually()) : ?>
							1 <input type="hidden" name="cart[<?php echo esc_attr($cart_item_key); ?>][qty]" value="1" />
						<?php else :
							$min = $_product->get_min_purchase_quantity();
							$max = $_product->get_max_purchase_quantity();
							$value = $cart_item['quantity'];
						?>
							<div class="quantity pro-qty">
								<a href="#" class="dec qty-btn">-</a>
								<input
									type="text"
									class="input-text qty text"
									data-step="1"
									data-min="<?php echo esc_attr($min); ?>"
									data-max="<?php echo esc_attr($max); ?>"
									name="cart[<?php echo esc_attr($cart_item_key); ?>][qty]"
									value="<?php echo esc_attr($value); ?>"
									title="Qty"
									size="4"
									inputmode="numeric" />
								<a href="#" class="inc qty-btn">+</a>
							</div>
						<?php endif; ?>
					</td>

					<td class="product-subtotal" data-title="<?php esc_attr_e('Subtotal', 'woocommerce'); ?>">
						<?php echo WC()->cart->get_product_subtotal($_product, $cart_item['quantity']); ?>
					</td>
				</tr>
			<?php endforeach; ?>

			<?php do_action('woocommerce_after_cart_contents'); ?>
		</tbody>
	</table>

	<?php do_action('woocommerce_after_cart_table'); ?>
</form>

<?php do_action('woocommerce_before_cart_collaterals'); ?>

<div class="cart-collaterals">
	<?php do_action('woocommerce_cart_collaterals'); ?>
</div>

<?php do_action('woocommerce_after_cart'); ?>

<!-- ==== JS для qty (ссылки +/-) ==== -->
<script>
	document.addEventListener('DOMContentLoaded', () => {
		document.querySelectorAll('.pro-qty .qty-btn').forEach(link => {
			link.addEventListener('click', e => {
				e.preventDefault();
				e.stopPropagation(); // блокируем всплытие

				const container = link.closest('.pro-qty');
				const input = container.querySelector('input.qty');

				let val = parseInt(input.value) || 0;
				const step = parseInt(input.dataset.step) || 1;
				const min = parseInt(input.dataset.min) || 0;
				const max = input.dataset.max ? parseInt(input.dataset.max) : Infinity;

				if (link.classList.contains('inc')) val = Math.min(val + step, max);
				if (link.classList.contains('dec')) val = Math.max(val - step, min);

				if (val !== parseInt(input.value)) {
					input.value = val;
					input.dispatchEvent(new Event('change', {
						bubbles: true
					})); // WooCommerce ловит изменение
				}

				return false; // предотвращаем переход по #
			});
		});
	});
</script>
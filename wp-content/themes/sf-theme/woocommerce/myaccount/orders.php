<?php

/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.5.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_account_orders', $has_orders); ?>

<?php if ($has_orders) : ?>
	<h3>Заказы</h3>
	<div class="my-account-orders">

		<?php foreach ($customer_orders->orders as $customer_order) :
			$order = wc_get_order($customer_order);
			$items = $order->get_items();
			$item_count = $order->get_item_count() - $order->get_item_count_refunded();

			// Общий вес
			$total_weight = 0;
			foreach ($items as $item) {
				$product = $item->get_product();
				if ($product && $product->get_weight()) {
					$total_weight += $product->get_weight() * $item->get_quantity();
				}
			}

			// Сумма скидок
			$discount_total = $order->get_total_discount();
		?>
			<div class="my-order">
				<h3 class="my-order__title">
					<?php printf(
						esc_html__('Заказ %s', 'woocommerce'),
						'<a href="' . esc_url($order->get_view_order_url()) . '">№' . esc_html($order->get_order_number()) . '</a>'
					); ?>
					<span class="my-order__date"><?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></span>
				</h3>

				<div class="my-order__items">
					<?php foreach ($items as $item_id => $item) :
						$product = $item->get_product();
						if (! $product) continue;

						$product_name = $product->get_name();
						$product_sku  = $product->get_sku();
						$product_img  = $product->get_image('thumbnail');
						$quantity     = $item->get_quantity();
						$weight       = $product->get_weight() ? wc_format_weight($product->get_weight() * $quantity) : '';
					?>
						<div class="my-order__item">
							<div class="my-order__item-left">
								<div class="my-order__item-img"><?php echo $product_img; ?></div>
								<div class="my-order__item-info">
									<div class="my-order__item-name"><?php echo esc_html($product_name); ?></div>
									<?php if ($product_sku) : ?>
										<div class="my-order__item-sku"><?php echo esc_html('SKU: ' . $product_sku); ?></div>
									<?php endif; ?>
								</div>
							</div>
							<div class="my-order__item-right">
								<?php if ($weight) : ?>
									<div class="my-order__item-right__item _weight">
										<p class="label">Вес:</p>
										<?php echo esc_html($weight); ?>
									</div>
								<?php endif; ?>
								<div class="my-order__item-right__item _qty">
									<p class="label">Кол-во:</p>
									<?php echo esc_html($quantity); ?>
								</div>
								<div class="my-order__item-right__item _total">
									<p class="label">Сумма:</p>
									<?php echo wp_kses_post(wc_price($item->get_total())); ?>
								</div>
								<div class="my-order__item-right__item _item-view">
									<a href="<?php echo esc_url($order->get_view_order_url()); ?>"><?php esc_html_e('View', 'woocommerce'); ?></a>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<!-- Финальный блок заказа -->
				<div class="my-order__summary">
					<div class="my-order__summary-item _status">
						<span><?php echo esc_html(wc_get_order_status_name($order->get_status())); ?></span>
					</div>
					<div class="my-order__summary-item">
						<p class="label"><?php esc_html_e('Позиций:', 'woocommerce'); ?></p>
						<span><?php echo esc_html($item_count); ?></span>
					</div>
					<div class="my-order__summary-item">
						<p class="label"><?php esc_html_e('Вес:', 'woocommerce'); ?></p>
						<span><?php echo $total_weight ? esc_html(wc_format_weight($total_weight)) : '-'; ?></span>
					</div>

					<div class="my-order__summary-item _discount">
						<p class="label"><?php esc_html_e('Скидка:', 'woocommerce'); ?></p>

						<?php if ($discount_total > 0) { ?>
							<span><?php echo wp_kses_post(wc_price($discount_total)); ?></span>
						<?php } else {
							echo '—';
						} ?>
					</div>

					<div class="my-order__summary-item">
						<strong><?php esc_html_e('Total:', 'woocommerce'); ?></strong>
						<span><?php echo wp_kses_post($order->get_formatted_order_total()); ?></span>
					</div>
				</div>

			</div>
		<?php endforeach; ?>

	</div>
<?php else : ?>
	<?php wc_print_notice(
		esc_html__('No order has been made yet.', 'woocommerce') .
			' <a class="woocommerce-Button wc-forward button" href="' . esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))) . '">' . esc_html__('Browse products', 'woocommerce') . '</a>',
		'notice'
	); ?>
<?php endif; ?>
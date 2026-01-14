<?php

/**
 * Custom Cart Page
 *
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

defined('ABSPATH') || exit;
?>

<div id="cart-page-wrapper">

	<?php do_action('woocommerce_before_cart'); ?>

	<div class="shopping-cart-list-area">

		<form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
			<?php do_action('woocommerce_before_cart_contents'); ?>

			<?php
			// Группируем товары по категориям
			$cart_groups = [];

			foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
				$_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
				if (!$_product || !$_product->exists() || $cart_item['quantity'] <= 0) continue;

				$terms = get_the_terms($_product->get_id(), 'product_cat');
				$cat_name = 'Без категории';
				if (!empty($terms) && !is_wp_error($terms)) {
					$term = array_shift($terms);
					if ($term->parent) {
						$parent = get_term($term->parent, 'product_cat');
						$cat_name = ($parent && !is_wp_error($parent)) ? $parent->name : $term->name;
					} else {
						$cat_name = $term->name;
					}
				}

				$cart_groups[$cat_name][] = [
					'cart_item_key' => $cart_item_key,
					'cart_item'     => $cart_item,
					'product'       => $_product,
				];
			}
			?>

			<?php foreach ($cart_groups as $cat_title => $items) : ?>
				<div class="cart-flex__category"><?php echo esc_html($cat_title); ?></div>

				<?php foreach ($items as $data) :
					$cart_item_key = $data['cart_item_key'];
					$cart_item     = $data['cart_item'];
					$_product      = $data['product'];
					$product_id    = $_product->get_id();
					$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
				?>

					<div class="cart-flex__row cart_item">

						<div class="cart-flex__col cart-flex__col--product">
							<div class="cart-product-item">
								<?php
								$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
								echo $product_permalink ? sprintf('<a href="%s" class="product-thumb">%s</a>', esc_url($product_permalink), $thumbnail) : $thumbnail;
								?>
								<div class="cart-product-item__name">
									<?php
									echo $product_permalink
										? sprintf('<a href="%s" class="product-name">%s</a>', esc_url($product_permalink), esc_html($_product->get_name()))
										: esc_html($_product->get_name());

									if ($_product->get_sku()) {
										echo '<div class="product-sku">Артикул: ' . esc_html($_product->get_sku()) . '</div>';
									}
									?>
								</div>
							</div>
						</div>

						<div class="cart_item__inner">

							<div class="cart-flex__col cart-flex__col--weight">
								<div class="cart-flex__col__label">Вес:</div>
								<?php
								if ($_product->has_weight()) {
									$total_weight = $_product->get_weight() * $cart_item['quantity'];
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
									echo sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
								} else {
									echo woocommerce_quantity_input([
										'input_name'   => "cart[{$cart_item_key}][qty]",
										'input_value'  => $cart_item['quantity'],
										'max_value'    => $_product->get_max_purchase_quantity(),
										'min_value'    => $_product->get_min_purchase_quantity(),
										'product_name' => $_product->get_name(),
									], $_product, false);
								}
								?>
							</div>

							<div class="cart-flex__col cart-flex__col--total">
								<div class="cart-flex__col__label">Сумма:</div>
								<span class="price"><?php echo WC()->cart->get_product_subtotal($_product, $cart_item['quantity']); ?></span>
							</div>

							<div class="product-remove">
								<?php
								echo sprintf(
									'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">×</a>',
									esc_url(wc_get_cart_remove_url($cart_item_key)),
									esc_attr__('Remove this item', 'woocommerce'),
									esc_attr($product_id),
									esc_attr($_product->get_sku())
								);
								?>
							</div>

						</div>

					</div>

				<?php endforeach; ?>
			<?php endforeach; ?>

			<?php do_action('woocommerce_after_cart_contents'); ?>

			<?php
			// Кнопки и totals
			wc_get_template_part('cart/cart', 'totals');
			?>

			<div class="cart-form-actions">
				<?php do_action('woocommerce_cart_actions'); ?>

				<?php if (wc_coupons_enabled()) : ?>
					<div class="coupon-form-wrap">
						<input type="text" name="coupon_code" id="coupon_code" placeholder="Введите промокод">
						<button type="submit" name="apply_coupon" class="btn-apply">Применить купон</button>
					</div>
				<?php endif; ?>

				<button type="submit" name="update_cart" class="btn-update-cart">Обновить корзину</button>

				<?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
			</div>

		</form>
	</div>

	<div class="cart-buttons">
		<a class="cart-button btn" href="<?php echo site_url('catalog'); ?>">Продолжить покупки</a>
		<?php do_action('woocommerce_proceed_to_checkout'); ?>
		<?php do_action('woocommerce_after_cart'); ?>
	</div>

</div>

<script>
	jQuery(function($) {
		// Кнопки +/-
		$(document).on('click', '.quantity .qty-btn', function(e) {
			e.preventDefault();
			let $input = $(this).siblings('input.qty');
			let val = parseInt($input.val()) || 0;
			const step = parseInt($input.data('step')) || 1;
			if ($(this).hasClass('inc')) val += step;
			if ($(this).hasClass('dec') && val > 1) val -= step;
			$input.val(val).trigger('change'); // триггерим AJAX обновление
		});

		// Слушаем change на qty для AJAX обновления
		$(document).on('change', 'form.woocommerce-cart-form input.qty', function() {
			$('body').trigger('update_cart');
			console.log('Количество товара изменено:', $(this).val());
		});
	});
</script>
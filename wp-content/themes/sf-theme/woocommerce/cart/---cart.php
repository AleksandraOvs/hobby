<?php
defined('ABSPATH') || exit;
?>

<div id="cart-page-wrapper">

	<?php do_action('woocommerce_before_cart'); ?>

	<div class="shopping-cart-list-area">

		<form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">

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
						$cat_name = $parent && !is_wp_error($parent) ? $parent->name : $term->name;
					} else $cat_name = $term->name;
				}

				$cart_groups[$cat_name][] = [
					'key'     => $cart_item_key,
					'item'    => $cart_item,
					'product' => $_product,
				];
			}
			?>

			<?php do_action('woocommerce_before_cart_contents'); ?>

			<?php foreach ($cart_groups as $cat_title => $items) : ?>
				<div class="cart-flex__category"><?php echo esc_html($cat_title); ?></div>

				<?php foreach ($items as $data) :
					$cart_item_key = $data['key'];
					$cart_item     = $data['item'];
					$_product      = $data['product'];
					$product_permalink = $_product->is_visible() ? $_product->get_permalink($cart_item) : '';
				?>

					<div class="cart-flex__row cart_item">

						<div class="cart-flex__col cart-flex__col--product">
							<div class="cart-product-item">
								<?php
								$thumbnail = $_product->get_image();
								echo $product_permalink
									? '<a href="' . esc_url($product_permalink) . '">' . $thumbnail . '</a>'
									: $thumbnail;
								?>
								<div class="cart-product-item__name">
									<?php
									echo $product_permalink
										? '<a href="' . esc_url($product_permalink) . '">' . esc_html($_product->get_name()) . '</a>'
										: esc_html($_product->get_name());
									?>
								</div>
							</div>
						</div>

						<div class="cart_item__inner">

							<div class="cart-flex__col cart-flex__col--price">
								<span><?php echo WC()->cart->get_product_price($_product); ?></span>
							</div>

							<div class="cart-flex__col cart-flex__col--qty">
								<?php
								if ($_product->is_sold_individually()) {
									echo '1 <input type="hidden" name="cart[' . $cart_item_key . '][qty]" value="1">';
								} else {
									echo woocommerce_quantity_input(
										array(
											'input_name'   => "cart[{$cart_item_key}][qty]",
											'input_value'  => $cart_item['quantity'],
											'min_value'    => $_product->get_min_purchase_quantity(),
											'max_value'    => $_product->get_max_purchase_quantity(),
											'product_name' => $_product->get_name(),
										),
										$_product,
										false
									);
								}
								?>
							</div>

							<div class="cart-flex__col cart-flex__col--total">
								<?php echo WC()->cart->get_product_subtotal($_product, $cart_item['quantity']); ?>
							</div>

							<div class="product-remove">
								<a href="<?php echo esc_url(wc_get_cart_remove_url($cart_item_key)); ?>" class="remove">×</a>
							</div>

						</div>
					</div>

				<?php endforeach; ?>
			<?php endforeach; ?>

			<?php do_action('woocommerce_after_cart_contents'); ?>

			<!-- Купон и totals внутри формы -->
			<div class="cart_totals">

				<div class="cart-coupon-update-area">
					<?php do_action('woocommerce_cart_coupon'); ?>
				</div>

				<?php
				$cart = WC()->cart;
				$total_weight = 0;
				foreach ($cart->get_cart() as $cart_item) {
					if ($cart_item['data']->has_weight()) {
						$total_weight += $cart_item['data']->get_weight() * $cart_item['quantity'];
					}
				}
				$discount = max(0, $cart->get_subtotal() - $cart->get_total('edit'));
				?>

				<ul class="cart-totals__info">
					<li>Товаров: <strong><?php echo esc_html($cart->get_cart_contents_count()); ?></strong></li>
					<li>Вес: <strong><?php echo wc_format_weight($total_weight); ?></strong></li>
					<li>Скидка: <strong><?php echo wc_price($discount); ?></strong></li>
					<li class="cart-total">Итого: <strong><?php echo $cart->get_total('edit'); ?></strong></li>
				</ul>

			</div>

			<!-- скрытая кнопка update_cart -->
			<button type="submit" name="update_cart" value="1" hidden></button>
			<?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>

		</form>

	</div>

	<div class="cart-buttons">
		<a class="cart-button btn" href="<?php echo site_url('catalog'); ?>">Продолжить покупки</a>
		<?php do_action('woocommerce_proceed_to_checkout'); ?>
	</div>

	<?php do_action('woocommerce_after_cart'); ?>
</div>
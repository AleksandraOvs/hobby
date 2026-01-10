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

			<?php
			// 1. Собираем товары по категориям
			$cart_groups = [];

			foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {

				$_product = apply_filters(
					'woocommerce_cart_item_product',
					$cart_item['data'],
					$cart_item,
					$cart_item_key
				);

				if (! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0) {
					continue;
				}

				// Берём категории товара
				$terms = get_the_terms($_product->get_id(), 'product_cat');

				// Название категории по умолчанию
				$cat_name = 'Без категории';

				if (! empty($terms) && ! is_wp_error($terms)) {

					// Берём первую категорию
					$term = array_shift($terms);

					// Если есть родитель — можно вывести именно его
					if ($term->parent) {
						$parent = get_term($term->parent, 'product_cat');
						if ($parent && ! is_wp_error($parent)) {
							$cat_name = $parent->name;
						} else {
							$cat_name = $term->name;
						}
					} else {
						$cat_name = $term->name;
					}
				}

				// Кладём товар в нужную группу
				$cart_groups[$cat_name][] = [
					'cart_item_key' => $cart_item_key,
					'cart_item'     => $cart_item,
					'product'       => $_product,
				];
			}
			?>


			<div class="cart-flex woocommerce-cart-form__contents">

				<!-- <div class="cart-flex__head">
						<div class="cart-flex__col cart-flex__col--product">Товары</div>
						<div class="cart-flex__col cart-flex__col--price">Цена</div>
						<div class="cart-flex__col cart-flex__col--qty">Количество</div>
						<div class="cart-flex__col cart-flex__col--total">Итого</div>
					</div> -->

				<?php do_action('woocommerce_before_cart_contents'); ?>

				<?php foreach ($cart_groups as $cat_title => $items) : ?>

					<!-- строка-заголовок категории -->
					<div class="cart-flex__category">
						<?php echo esc_html($cat_title); ?>
					</div>

					<?php foreach ($items as $data) :

						$cart_item_key = $data['cart_item_key'];
						$cart_item     = $data['cart_item'];
						$_product      = $data['product'];

						$product_id = $_product->get_id();

						$product_permalink = apply_filters(
							'woocommerce_cart_item_permalink',
							$_product->is_visible() ? $_product->get_permalink($cart_item) : '',
							$cart_item,
							$cart_item_key
						);
					?>

						<div class="cart-flex__row cart_item">

							<div class="cart-flex__col cart-flex__col--product">
								<div class="cart-product-item">
									<?php
									$thumbnail = apply_filters(
										'woocommerce_cart_item_thumbnail',
										$_product->get_image(),
										$cart_item,
										$cart_item_key
									);

									if ($product_permalink) {
										printf('<a href="%s" class="product-thumb">%s</a>', esc_url($product_permalink), $thumbnail);
									} else {
										echo $thumbnail;
									}
									?>
									<div class="cart-product-item__name">
										<?php
										if ($product_permalink) {
											printf(
												'<a href="%s" class="product-name">%s</a>',
												esc_url($product_permalink),
												esc_html($_product->get_name())
											);
										} else {
											echo esc_html($_product->get_name());
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
									echo sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
								} else {
									echo woocommerce_quantity_input(
										array(
											'input_name'   => "cart[{$cart_item_key}][qty]",
											'input_value'  => $cart_item['quantity'],
											'max_value'    => $_product->get_max_purchase_quantity(),
											'min_value'    => $_product->get_min_purchase_quantity(),
											'product_name' => $_product->get_name(),
										),
										$_product,
										false
									);
								}
								?>
							</div>

							<div class="cart-flex__col cart-flex__col--total">
								<div class="cart-flex__col__label">Сумма:</div>
								<span class="price">
									<?php echo WC()->cart->get_product_subtotal($_product, $cart_item['quantity']); ?>
								</span>
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

					<?php endforeach; ?>

				<?php endforeach; ?>

				<?php do_action('woocommerce_after_cart_contents'); ?>

			</div>

			<div class="cart-totals">
				<div class="cart-coupon-update-area d-sm-flex justify-content-between align-items-center">
					<?php do_action('woocommerce_cart_actions'); ?>
					<?php if (wc_coupons_enabled()) : ?>
						<div class="coupon-form-wrap">

							<input type="text" autocomplete="off" name="coupon_code" id="coupon_code" placeholder="Введите промокод" />
							<button type="submit" class="btn-apply" name="apply_coupon"><span>Применить купон</span></button>

						</div>
					<?php endif; ?>

					<div class="cart-update-buttons mt-xs-14">
						<button type="submit" name="update_cart" class="btn-update-cart">Обновить корзину</button>
						<?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce');
						?>
					</div>
				</div>

				<ul class="cart-totals__info">
					<li class="cart-totals__info__item">
						<span>Позиций:</span>
						<p><?php echo count(WC()->cart->get_cart()) ?></p>
					</li>

					<li class="cart-totals__info__item">
						<span>Вес:</span>
						<p> <?php
							$total_weight = WC()->cart->get_cart_contents_weight();
							echo esc_html(
								wc_get_weight(
									$total_weight,
									get_option('woocommerce_weight_unit')
								)
							);
							?></p>
					</li>
					<?php
					$discount_total = WC()->cart->get_discount_total(); // общая сумма скидок

					if ($discount_total > 0) {
					?>
						<li class="cart-totals__info__item">
							<span>Скидка:</span>
							<p><?php echo wc_price($discount_total) ?></p>
						</li>
					<?php
					}
					?>

					<li class="cart-totals__info__item">
						<span>Итого:</span>
						<p><?php echo wc_cart_totals_order_total_html(); ?></p>
					</li>
				</ul>
			</div>
		</form>
	</div>


	<div class="cart-buttons">
		<a class="cart-button btn" href="<?php echo site_url('catalog') ?>" class="btn">Продолжить покупки</a>
		<?php do_action('woocommerce_proceed_to_checkout');
		?>


		<?php do_action('woocommerce_after_cart');
		?>
	</div>
</div>
<!--== End Cart Page Wrapper ==-->




<?php

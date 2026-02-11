<?php

defined('ABSPATH') || exit;

?>

<!--== Start Cart Page Wrapper ==-->
<div id="cart-page-wrapper">

	<?php do_action('woocommerce_before_cart'); ?>

	<div class="shopping-cart-list-area">

		<form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">

			<div class="shop_table shop_table_responsive cart woocommerce-cart-form__contents">

				<div class="cart-select-toolbar">

					<label class="cart-select-all">
						<input type="checkbox" id="select-all" checked>
						<span>Все</span>
					</label>

					<div class="cart-selected-count">
						Выбрано: <span id="selected-count">0</span>
					</div>

					<button type="button" id="remove-selected" class="remove-selected-btn">
						<svg width="20" height="22" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M17.75 7.75L15.755 19.096C15.6736 19.5594 15.4315 19.9792 15.0712 20.2817C14.7109 20.5842 14.2555 20.75 13.785 20.75H5.715C5.24454 20.75 4.78913 20.5842 4.42882 20.2817C4.06852 19.9792 3.82639 19.5594 3.745 19.096L1.75 7.75M18.75 4.75H13.125M13.125 4.75V2.75C13.125 2.21957 12.9143 1.71086 12.5392 1.33579C12.1641 0.960714 11.6554 0.75 11.125 0.75H8.375C7.84457 0.75 7.33586 0.960714 6.96079 1.33579C6.58571 1.71086 6.375 2.21957 6.375 2.75V4.75M13.125 4.75H6.375M0.75 4.75H6.375" stroke="#797979" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
						<span>Удалить выбранное</span>
					</button>

				</div>

				<?php
				// 1. Группируем товары корзины по родительским категориям
				$grouped_cart = [];

				foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
					$_product = $cart_item['data'];
					if (!$_product || !$cart_item['quantity']) continue;

					$product_id = $cart_item['product_id'];

					$terms = get_the_terms($product_id, 'product_cat');
					$parent_term = null;

					if (!empty($terms) && !is_wp_error($terms)) {
						$term = array_shift($terms);

						// Поднимаемся к родителю
						while ($term->parent != 0) {
							$term = get_term($term->parent, 'product_cat');
						}

						$parent_term = $term;
					}

					$group_key = $parent_term ? $parent_term->term_id : 'no-category';

					$grouped_cart[$group_key]['term'] = $parent_term;
					$grouped_cart[$group_key]['items'][$cart_item_key] = $cart_item;
				}
				?>

				<?php foreach ($grouped_cart as $group) : ?>

					<div class="cart-flex__category">
						<?php echo esc_html($group['term'] ? $group['term']->name : 'Без категории'); ?>
					</div>

					<?php foreach ($group['items'] as $cart_item_key => $cart_item) :

						$_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
						$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

						if (!$_product || !$cart_item['quantity']) continue;

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
									<div class="cart-select">
										<label>
											<input type="checkbox"
												class="cart-item-checkbox"
												data-key="<?php echo esc_attr($cart_item_key); ?>"
												name="cart[<?php echo $cart_item_key; ?>][selected]"
												value="1"
												<?php checked(1, isset($cart_item['selected']) ? $cart_item['selected'] : 1); ?>>


										</label>
									</div>
									<?php
									$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

									if (!$product_permalink) {
										echo $thumbnail;
									} else {
										printf('<a href="%s" class="product-thumb">%s</a>', esc_url($product_permalink), $thumbnail);
									}
									?>

									<div class="cart-product-item__name">

										<?php
										if (!$product_permalink) {
											echo wp_kses_post($_product->get_name());
										} else {
											printf('<a href="%s" class="product-name">%s</a>', esc_url($product_permalink), esc_html($_product->get_name()));
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
									if ($_product->has_weight()) {
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
											[
												'input_name'   => "cart[{$cart_item_key}][qty]",
												'input_value'  => $cart_item['quantity'],
												'max_value'    => $_product->get_max_purchase_quantity(),
												'min_value'    => '0',
												'product_name' => $_product->get_name(),
											],
											$_product,
											true
										);
									}
									?>
								</div>

								<div class="cart-flex__col cart-flex__col--total">
									<div class="cart-flex__col__label">Сумма:</div>
									<span class="price"><?php echo WC()->cart->get_product_subtotal($_product, $cart_item['quantity']); ?></span>
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

					<?php endforeach; ?>
				<?php endforeach; ?>

			</div>

			<div class="cart-bottom">
				<div class="cart-coupon-update-area">

					<?php if (wc_coupons_enabled()) : ?>
						<div class="coupon-form-wrap">
							<input type="text" autocomplete="off" name="coupon_code" id="coupon_code" placeholder="Код купона" />
							<button type="submit" class="btn-apply" name="apply_coupon">
								<svg width="17" height="13" viewBox="0 0 17 13" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M5.17202 10.162L1.70202 6.69202C1.51504 6.50504 1.26145 6.4 0.99702 6.4C0.732594 6.4 0.478998 6.50504 0.292021 6.69202C0.105043 6.879 0 7.13259 0 7.39702C0 7.52795 0.0257889 7.6576 0.0758939 7.77856C0.125999 7.89953 0.199439 8.00944 0.292021 8.10202L4.47202 12.282C4.86202 12.672 5.49202 12.672 5.88202 12.282L16.462 1.70202C16.649 1.51504 16.754 1.26145 16.754 0.997021C16.754 0.732594 16.649 0.478998 16.462 0.292021C16.275 0.105043 16.0214 0 15.757 0C15.4926 0 15.239 0.105043 15.052 0.292021L5.17202 10.162Z" fill="#898989" />
								</svg>

								<span>Применить купон</span></button>
						</div>
					<?php endif; ?>

					<div class="cart-update-buttons mt-xs-14">
						<button type="submit" name="update_cart" class="btn-update-cart"></button>
						<?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
					</div>

				</div>

				<div class="cart-calculate-area cart_totals <?php echo (WC()->customer->has_calculated_shipping()) ? 'calculated_shipping' : ''; ?>">

					<div class="cart-cal-table table-responsive">

						<ul class="cart-totals__info">
							<li class="cart-totals__info__item">
								<span><?php esc_html_e('Позиций', 'woocommerce'); ?></span>
								<p data-title="<?php esc_attr_e('Количество товаров', 'woocommerce'); ?>" class="cart-items-count-value">
									<?php echo WC()->cart->get_cart_contents_count(); ?>
								</p>
							</li>

							<li class="cart-totals__info__item">
								<span><?php esc_html_e('Вес:', 'woocommerce'); ?></span>
								<p data-title="<?php esc_attr_e('Вес:', 'woocommerce'); ?>" class="cart-weight-value">
									<?php echo WC()->cart->get_cart_contents_weight() . ' ' . get_option('woocommerce_weight_unit'); ?>
								</p>
							</li>

							<!-- <tr class="cart-sub-total cart-subtotal">
				<th><?php //esc_html_e('Subtotal', 'woocommerce'); 
					?></th>
				<td data-title="<?php //esc_attr_e('Subtotal', 'woocommerce'); 
								?>"><?php //wc_cart_totals_subtotal_html(); 
									?></td>
			</tr> -->

							<li class="cart-totals__info__item">
								<span>Скидка:</span>
								<?php
								// 1️⃣ Вывод купонов
								foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
									<div class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
										<span><?php wc_cart_totals_coupon_label($coupon); ?></span>
										<p data-title="<?php echo esc_attr(wc_cart_totals_coupon_label($coupon, false)); ?>">
											<?php wc_cart_totals_coupon_html($coupon); ?>
										</p>
									</div>
								<?php endforeach; ?>

								<?php
								// 2️⃣ Вывод лояльной скидки
								if (is_user_logged_in()) {
									$uld_discount = WC()->session->get('uld_discount_total', 0);
									if ($uld_discount > 0) : ?>
										<div class="cart-discount loyalty-discount">

											<div class="discount-summary" data-title="Лояльная скидка">

												<?php echo wc_price($uld_discount); ?>
												<p class="discount-info">Скидка по программе лояльности</p>

											</div>
										</div>
								<?php endif;
								}
								?>
							</li>

							<li class="cart-totals__info__item order-total">
								<span>Итого:</span>
								<p data-title="<?php esc_attr_e('Total', 'woocommerce'); ?>"><?php wc_cart_totals_order_total_html(); ?></p>
							</li>

					</div>
					</ul>

					<div class="cart-buttons">
						<a class="cart-button btn" href="<?php echo site_url('catalog') ?>" class="btn">Продолжить покупки</a>
						<!-- <div class="wc-proceed-to-checkout"> -->
						<?php do_action('woocommerce_proceed_to_checkout'); ?>
						<!-- </div> -->
					</div>


					<?php do_action('woocommerce_after_cart_totals'); ?>

				</div>
			</div>


		</form>
	</div>

	<?php woocommerce_cart_totals(); ?>

</div>

<?php do_action('woocommerce_after_cart'); ?>
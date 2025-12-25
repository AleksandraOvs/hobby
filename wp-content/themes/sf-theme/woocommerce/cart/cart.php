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
									<i class="fa fa-trash-o"></i>
								</a>
							</div>

						</div>

					<?php endforeach; ?>

				<?php endforeach; ?>

				<?php do_action('woocommerce_after_cart_contents'); ?>

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
					<?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce');
					?>
				</div>
			</div>
		</form>
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
<!--== End Cart Page Wrapper ==-->

<?php do_action('woocommerce_after_cart'); ?>

<?php
/**
 * end of cart section
 */

/**
 * start of checkout section
 */

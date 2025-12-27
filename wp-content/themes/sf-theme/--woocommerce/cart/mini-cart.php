<?php

/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_mini_cart'); ?>

<div class="widget_shopping_cart_content">
	<?php if (! WC()->cart->is_empty()) : ?>

		<ul class="minicart-product-list woocommerce-mini-cart cart_list product_list_widget <?php echo esc_attr($args['list_class']); ?>">
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
					<li class="single-product-item d-flex woocommerce-mini-cart-item mini_cart_item">

						<figure class="product-thumb">
							<?php if (empty($product_permalink)) : ?>
								<?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
								?>
							<?php else : ?>
								<a href="<?php echo esc_url($product_permalink); ?>">
									<?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
									?>
								</a>
							<?php endif; ?>


						</figure>
						<div class="product-details">
							<h2 class="product-title">
								<?php if (empty($product_permalink)) : ?>
									<?php echo $product_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
									?>
								<?php else : ?>
									<a href="<?php echo esc_url($product_permalink); ?>">
										<?php echo $product_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
										?>
									</a>
								<?php endif; ?>
							</h2>
							<div class="prod-cal d-flex align-items-center">
								<span class="quantity"><?php echo $cart_item['quantity'] ?></span>
								<span class="multiplication">&#215;</span>
								<span class="price"><?php echo $product_price ?></span>
							</div>
						</div>
						<?php
						echo sprintf(
							'<a href="%s" class="remove remove_from_cart_button remove-icon" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s"></a>',
							esc_url(wc_get_cart_remove_url($cart_item_key)),
							esc_attr__('Remove this item', 'woocommerce'),
							esc_attr($product_id),
							esc_attr($cart_item_key),
							esc_attr($_product->get_sku())
						);
						?>
					</li>
			<?php
				}
			}

			do_action('woocommerce_mini_cart_contents');
			?>
		</ul>

		<p class="woocommerce-mini-cart__total total minicart-calculation-wrap d-flex justify-content-between align-items-center">
			<?php
			/**
			 * Hook: woocommerce_widget_shopping_cart_total.
			 *
			 * @hooked woocommerce_widget_shopping_cart_subtotal - 10
			 */
			do_action('woocommerce_widget_shopping_cart_total');
			?>
		</p>

		<div class="minicart-btn-group mt-38">
			<a href="<?php echo wc_get_cart_url() ?>" class="btn btn-black ">Просмотр корзины</a>
			<a href="<?php echo wc_get_checkout_url() ?>" class="btn btn-black mt-10">Оформление заказа</a>
		</div>

	<?php else : ?>

		<p class="woocommerce-mini-cart__empty-message"><?php esc_html_e('No products in the cart.', 'woocommerce'); ?></p>

	<?php endif; ?>

	<?php do_action('woocommerce_after_mini_cart'); ?>
</div>
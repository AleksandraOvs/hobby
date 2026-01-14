<?php
defined('ABSPATH') || exit;

do_action('woocommerce_before_cart_totals');
?>

<ul class="cart-totals__info">

	<li class="cart-totals__info__item">
		<span>Позиций:</span>
		<p><?php echo WC()->cart->get_cart_contents_count(); ?></p>
	</li>

	<li class="cart-totals__info__item">
		<span>Вес:</span>
		<p>
			<?php echo wc_get_weight(
				WC()->cart->get_cart_contents_weight(),
				get_option('woocommerce_weight_unit')
			); ?>
		</p>
	</li>

	<?php
	$discount_total  = WC()->cart->get_discount_total();
	$applied_coupons = WC()->cart->get_applied_coupons();
	?>

	<li class="cart-totals__info__item">
		<span>Скидка:</span>

		<?php if ($discount_total > 0 && ! empty($applied_coupons)) : ?>
			<div class="cart-discount-list">

				<?php foreach ($applied_coupons as $coupon_code) : ?>
					<div class="cart-discount-item">

						<span class="coupon-code">
							<?php echo esc_html(wc_format_coupon_code($coupon_code)); ?>
						</span>

						<span class="coupon-amount">
							−<?php
								echo wc_price(
									WC()->cart->get_coupon_discount_amount($coupon_code)
								);
								?>
						</span>

						<a
							href="<?php
									echo esc_url(
										add_query_arg(
											'remove_coupon',
											rawurlencode($coupon_code),
											wc_get_cart_url()
										)
									);
									?>"
							class="remove-coupon"
							aria-label="Удалить купон">×</a>

					</div>
				<?php endforeach; ?>

			</div>
		<?php else : ?>
			<p>—</p>
		<?php endif; ?>
	</li>

	<li class="cart-totals__info__item cart-total">
		<span>Итого:</span>
		<p><?php wc_cart_totals_order_total_html(); ?></p>
	</li>

</ul>

<?php do_action('woocommerce_after_cart_totals'); ?>
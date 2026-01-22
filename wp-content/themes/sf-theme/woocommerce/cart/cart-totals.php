<?php

/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 2.3.6
 */

defined('ABSPATH') || exit;

?>
<div class="cart-calculate-area mt-sm-30 mt-md-30 cart_totals <?php echo (WC()->customer->has_calculated_shipping()) ? 'calculated_shipping' : ''; ?>">

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
				<?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
					<div class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
						<span><?php wc_cart_totals_coupon_label($coupon); ?></span>
						<p data-title="<?php echo esc_attr(wc_cart_totals_coupon_label($coupon, false)); ?>"><?php wc_cart_totals_coupon_html($coupon); ?></p>
					</div>
				<?php endforeach; ?>
			</li>

			<li class="cart-totals__info__item">
				<?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) :
				?>

					<span><?php wc_cart_totals_shipping_html();
							?></span>

				<?php endif;
				?>

				<?php foreach (WC()->cart->get_fees() as $fee) :
				?>
					<tr class="fee">
						<th><?php echo esc_html($fee->name);
							?></th>
						<td data-title="<?php echo esc_attr($fee->name);
										?>"><?php wc_cart_totals_fee_html($fee);
											?></td>
					</tr>
				<?php endforeach;
				?>

				<?php
				if (wc_tax_enabled() && ! WC()->cart->display_prices_including_tax()) {
					$taxable_address = WC()->customer->get_taxable_address();
					$estimated_text  = '';

					if (WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping()) {
						/* translators: %s location. */
						$estimated_text = sprintf(' <small>' . esc_html__('(estimated for %s)', 'woocommerce') . '</small>', WC()->countries->estimated_for_prefix($taxable_address[0]) . WC()->countries->countries[$taxable_address[0]]);
					}

					if ('itemized' === get_option('woocommerce_tax_total_display')) {
						foreach (WC()->cart->get_tax_totals() as $code => $tax) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
				?>
							<tr class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code));
															?>">
								<th><?php echo esc_html($tax->label) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
									?></th>
								<td data-title="<?php echo esc_attr($tax->label);
												?>"><?php echo wp_kses_post($tax->formatted_amount);
													?></td>
							</tr>
						<?php
						}
					} else {
						?>
						<tr class="tax-total">
							<th><?php echo esc_html(WC()->countries->tax_or_vat()) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
								?></th>
							<td data-title="<?php echo esc_attr(WC()->countries->tax_or_vat());
											?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
						</tr>
				<?php
					}
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
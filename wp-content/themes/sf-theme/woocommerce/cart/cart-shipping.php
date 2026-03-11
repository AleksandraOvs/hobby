<!-- фрагмент со стандартными методами доставки (на будущее, если нужен будет подсчет стоимости доставки в итоговую цену заказа) -->

<tr class="woocommerce-shipping-totals shipping">
	<td colspan="2">
		<div class="checkout-section__title">Способ получения</div>

		<?php
		$checkout = WC()->checkout();
		$packages = WC()->shipping()->get_packages();
		$chosen_methods = WC()->session->get('chosen_shipping_methods', []);
		?>

		<div class="pickup-fields">
			<div class="form-row">
				<?php
				woocommerce_form_field(
					'billing_country',
					$checkout->get_checkout_fields()['billing']['billing_country'],
					$checkout->get_value('billing_country')
				);
				?>
			</div>

			<div class="form-row">
				<?php
				woocommerce_form_field(
					'pickup_address',
					[
						'type' => 'text',
						'label' => 'Адрес',
						'placeholder' => 'Введите адрес',
						'class' => ['form-row-wide'],
					],
					$checkout->get_value('pickup_address')
				);
				?>
			</div>
		</div>

		<?php if (!empty($packages)) : ?>
			<div class="custom-delivery-options">
				<?php foreach ($packages as $package_index => $package) :
					foreach ($package['rates'] as $rate_id => $rate) :
						$checked = isset($chosen_methods[$package_index]) && $chosen_methods[$package_index] === $rate_id ? 'checked' : '';
				?>
						<div class="delivery-option wc-delivery-option">
							<label class="delivery-option__inner">
								<span class="delivery-option__left">
									<input type="radio"
										name="shipping_method[<?= $package_index ?>]"
										data-index="<?= $package_index ?>"
										id="shipping_method_<?= $package_index ?>_<?= esc_attr(sanitize_title($rate_id)) ?>"
										value="<?= esc_attr($rate_id) ?>"
										class="shipping_method"
										<?= $checked ?> />
									<span class="delivery-option__title"><?= esc_html($rate->get_label()) ?></span>
								</span>
								<span class="delivery-option__desc"><?= wc_price($rate->get_cost()) ?></span>
							</label>
							<?php do_action('woocommerce_after_shipping_rate', $rate, $package_index); ?>
						</div>
				<?php
					endforeach;
				endforeach; ?>
			</div>
		<?php else : ?>
			<p><?php esc_html_e('Для вашего адреса доставки нет доступных способов получения.', 'woocommerce'); ?></p>
		<?php endif; ?>
	</td>
</tr>
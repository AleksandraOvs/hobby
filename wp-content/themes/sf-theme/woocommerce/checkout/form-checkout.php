<?php

/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

if (! defined('ABSPATH')) {
	exit;
}
?>

<div id="checkout-page-wrapper" class="">
	<div class="container">
		<?php
		do_action('woocommerce_before_checkout_form', $checkout);

		// If checkout registration is disabled and not logged in, the user cannot checkout.
		if (! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in()) {
			echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
			return;
		}
		?>
		<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">
			<div class="checkout-order-fields">
				<?php if ($checkout->get_checkout_fields()) : ?>

					<!-- Checkout Form Area Start -->
					<div class="checkout-billing-details-wrap">
						<div class="billing-form-wrap">

							<!-- Покупатель -->
							<div class="checkout-section">
								<div class="checkout-section__title">Покупатель</div>
								<div class="checkout-section__content">
									<?php do_action('woocommerce_checkout_billing');
									?>
								</div>
							</div>

							<!-- Кастомные варианты доставки (работают, если ни одного метода доставки не добавлено и расчет доставки выключен)-->
							<!-- Способ получения -->
							<div class="checkout-section">
								<div class="checkout-section__title">Способ получения</div>

								<!-- <div class="delivery-desc">
									<p>Расчет стоимости доставки производится менеджером после подтверждения заказа</p>
								</div> -->
								<div class="checkout-section__content">

									<?php
									$selected = $_POST['custom_delivery_method'] ?? '';
									?>

									<div class="custom-delivery-options">

										<div class="delivery-option">
											<label class="delivery-option__inner">
												<span class="delivery-option__left">
													<input type="radio"
														name="custom_delivery_method"
														value="Самовывоз"
														<?php checked($selected, 'Самовывоз'); ?>>
													<span class="delivery-option__title">Самовывоз</span>
												</span>
												<span class="delivery-option__desc">
													За 60 минут, бесплатно
												</span>
											</label>
										</div>

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
												<label>Адрес</label>
												<input type="text" name="pickup_address" placeholder="Введите адрес">
											</div>
										</div>

										<div class="delivery-option">
											<label class="delivery-option__inner">
												<span class="delivery-option__left">
													<input type="radio"
														name="custom_delivery_method"
														value="Доставка курьером"
														<?php checked($selected, 'Доставка курьером'); ?>>
													<span class="delivery-option__title">Доставка курьером</span>
												</span>
												<span class="delivery-option__desc">
													Завтра, от 500 руб.
												</span>
											</label>
										</div>

										<div class="delivery-option">
											<label class="delivery-option__inner">
												<span class="delivery-option__left">
													<input type="radio"
														name="custom_delivery_method"
														value="СДЭК"
														<?php //checked($selected, 'Транспортная компания'); 
														?>>
													<span class="delivery-option__title">СДЭК</span>
												</span>
												<span class="delivery-option__desc">
													Доставка до пункта отправки - 0 рублей
												</span>
											</label>

											<!-- Дополнительные варианты СДЭК -->
											<div class="sdek-suboptions">
												<label>
													<input type="checkbox" name="sdek_options[]" value="СДЭК - курьер">
													Доставка курьером до двери
												</label>
												<br>
												<label>
													<input type="checkbox" name="sdek_options[]" value="СДЭК - ПВЗ">
													Доставка до ПВЗ

												</label>
											</div>
										</div>

										<div class="delivery-option">
											<label class="delivery-option__inner">
												<span class="delivery-option__left">
													<input type="radio"
														name="custom_delivery_method"
														value="Почта России"
														<?php checked($selected, 'Почта России'); ?>>
													<span class="delivery-option__title">Почта России</span>
												</span>
												<span class="delivery-option__desc">
													От 6 дней
												</span>
											</label>
										</div>

										<div class="delivery-option">
											<label class="delivery-option__inner">
												<span class="delivery-option__left">
													<input type="radio"
														name="custom_delivery_method"
														value="Наиболее выгодный вариант"
														<?php checked($selected, 'Наиболее выгодный вариант'); ?>>
													<span class="delivery-option__title">Наиболее выгодный вариант</span>
												</span>
												<span class="delivery-option__desc">
													Подберем для Вас наиболее выгодный вариант
												</span>
											</label>
										</div>

									</div>
								</div>
								<?php
								// echo '<pre>';
								// print_r(WC()->shipping()->get_packages());
								// echo '</pre>';
								?>
								<?php

								$packages = WC()->cart->get_shipping_packages();

								foreach ($packages as $package_index => $package) {

									$rates = WC()->shipping()->calculate_shipping_for_package($package);

									if (!empty($rates['rates'])) {

										foreach ($rates['rates'] as $rate_id => $rate) {

											$label = $rate->get_label();
											$cost  = wc_price($rate->get_cost());
								?>

											<div class="delivery-option wc-delivery-option">
												<label class="delivery-option__inner">

													<span class="delivery-option__left">
														<input type="radio"
															name="shipping_method[<?php echo $package_index; ?>]"
															value="<?php echo esc_attr($rate_id); ?>">

														<span class="delivery-option__title">
															<?php echo esc_html($label); ?>
														</span>
													</span>

													<span class="delivery-option__desc">
														<?php echo $cost; ?>
													</span>

												</label>
											</div>
								<?php
										}
									}
								}
								?>
							</div>
						</div>

						<?php do_action('sf_checkout_products_block'); ?>

					<?php endif; ?>

					</div>

					<!-- Checkout Page Order Details -->
					<div class="order-details-area-wrap ">
						<h2>Ваш заказ</h2>

						<div id="order_review" class="woocommerce-checkout-review-order order-details-table table-responsive">
							<?php do_action('woocommerce_checkout_order_review'); ?>
						</div>
					</div>
		</form>


		<?php do_action('woocommerce_after_checkout_form', $checkout); ?>
	</div>
</div>

<script>
	jQuery(function($) {

		$('body').on('change', '.shipping_method', function() {

			$('body').trigger('update_checkout');

		});

	});
</script>
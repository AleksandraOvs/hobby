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

							<!-- Способ получения -->
							<div class="checkout-section">
								<div class="checkout-section__title">Способ получения</div>

								<!-- <div class="delivery-desc">
									<p>Расчет стоимости доставки производится менеджером после подтверждения заказа</p>
								</div> -->

								<div class="checkout-section__content">

									<div class="custom-delivery-options">

										<?php
										$selected = $_POST['custom_delivery_method'] ?? '';
										?>

										<div class="delivery-option">
											<label class="delivery-option__inner">
												<span class="delivery-option__left">
													<input type="checkbox" name="custom_delivery_method"
														value="Самовывоз"
														<?php checked($selected, 'Самовывоз'); ?>>
													<span class="delivery-option__title">Самовывоз</span>
												</span>
												<span class="delivery-option__desc">
													За 60 минут, бесплатно
												</span>
											</label>
										</div>

										<?php do_action('woocommerce_checkout_shipping'); ?>

										<div class="delivery-option">
											<label class="delivery-option__inner">
												<span class="delivery-option__left">
													<input type="checkbox" name="custom_delivery_method"
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
													<input type="checkbox" name="custom_delivery_method"
														value="Транспортная компания"
														<?php checked($selected, 'Транспортная компания'); ?>>
													<span class="delivery-option__title">Транспортная компания</span>
												</span>
												<span class="delivery-option__desc">
													От 2 дней
												</span>
											</label>
										</div>

										<div class="delivery-option">
											<label class="delivery-option__inner">
												<span class="delivery-option__left">
													<input type="checkbox" name="custom_delivery_method"
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
													<input type="checkbox" name="custom_delivery_method"
														value="Наиболее выгодный вариант"
														<?php checked($selected, 'Наиболее выгодный вариант'); ?>>
													<span class="delivery-option__title">Наиболее выгодный вариант</span>
												</span>
												<span class="delivery-option__desc">
													Оставьте заявку — менеджер подберет лучший способ
												</span>
											</label>
										</div>

									</div>



								</div>
							</div>

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
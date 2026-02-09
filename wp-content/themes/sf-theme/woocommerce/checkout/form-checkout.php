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
									<?php do_action('woocommerce_checkout_billing'); ?>
								</div>
							</div>

							<!-- Способ получения -->
							<div class="checkout-section">
								<div class="checkout-section__title">Способ получения</div>
								<div class="delivery-desc">
									<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M9 15H11V9H9V15ZM10 7C10.2833 7 10.521 6.904 10.713 6.712C10.905 6.52 11.0007 6.28267 11 6C10.9993 5.71733 10.9033 5.48 10.712 5.288C10.5207 5.096 10.2833 5 10 5C9.71667 5 9.47933 5.096 9.288 5.288C9.09667 5.48 9.00067 5.71733 9 6C8.99933 6.28267 9.09533 6.52033 9.288 6.713C9.48067 6.90567 9.718 7.00133 10 7ZM10 20C8.61667 20 7.31667 19.7373 6.1 19.212C4.88334 18.6867 3.825 17.9743 2.925 17.075C2.025 16.1757 1.31267 15.1173 0.788001 13.9C0.263335 12.6827 0.000667932 11.3827 1.26582e-06 10C-0.000665401 8.61733 0.262001 7.31733 0.788001 6.1C1.314 4.88267 2.02633 3.82433 2.925 2.925C3.82367 2.02567 4.882 1.31333 6.1 0.788C7.318 0.262667 8.618 0 10 0C11.382 0 12.682 0.262667 13.9 0.788C15.118 1.31333 16.1763 2.02567 17.075 2.925C17.9737 3.82433 18.6863 4.88267 19.213 6.1C19.7397 7.31733 20.002 8.61733 20 10C19.998 11.3827 19.7353 12.6827 19.212 13.9C18.6887 15.1173 17.9763 16.1757 17.075 17.075C16.1737 17.9743 15.1153 18.687 13.9 19.213C12.6847 19.739 11.3847 20.0013 10 20ZM10 18C12.2333 18 14.125 17.225 15.675 15.675C17.225 14.125 18 12.2333 18 10C18 7.76667 17.225 5.875 15.675 4.325C14.125 2.775 12.2333 2 10 2C7.76667 2 5.875 2.775 4.325 4.325C2.775 5.875 2 7.76667 2 10C2 12.2333 2.775 14.125 4.325 15.675C5.875 17.225 7.76667 18 10 18Z" fill="#8B4512" />
									</svg>

									<p>Расчет стоимости доставки производится менеджером после подтверждения заказа</p>
								</div>
								<div class="checkout-section__content">
									<?php do_action('woocommerce_checkout_shipping'); ?>
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
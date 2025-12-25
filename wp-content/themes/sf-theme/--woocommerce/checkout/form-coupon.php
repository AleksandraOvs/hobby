<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.4
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
	return;
}

?>



<div class="row">
		<div class="col-lg-12">
				<div class="checkout-page-coupon-area">
						<!-- Checkout Coupon Accordion Start -->
						<div class="checkoutAccordion" id="checkOutAccordion">
								<div class="card">
										<h3>Имеете купон? <a href="#" class="showcoupon">Нажмите тут, чтобы ввести его</a>
										</h3>

										<!-- <div id="couponaccordion" class="collapse" data-parent="#checkOutAccordion"> -->
										<form class="checkout_coupon woocommerce-form-coupon" method="post" style="display:none">
												<div class="card-body">
														<div class="apply-coupon-wrapper">
																<p>Если у вас есть купон, пожалуйста примените его ниже.</p>

																		<input type="text" name="coupon_code" id="coupon_code" placeholder="Ваш код купона" required/>
																		<button type="submit" name="apply_coupon" class="btn btn-black">Применить купон</button>

														</div>
												</div>
										</form>

								</div>
						</div>
						<!-- Checkout Coupon Accordion End -->
				</div>
		</div>
</div>

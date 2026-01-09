<?php

/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

defined('ABSPATH') || exit;

global $product;

// Check if the product is a valid WooCommerce product and ensure its visibility before proceeding.
if (! is_a($product, WC_Product::class) || ! $product->is_visible()) {
	return;
}

$product_published = $product->get_date_created();
?>

<!-- Start Single Product -->

<div class="single-product">
	<!-- Product Thumbnail -->
	<figure class="product-thumbnail">
		<div class="badges">
			<?php
			if ($product->is_on_sale()) : ?>
				<span class="product-badge sale">Акция</span>
			<?php endif; ?>

			<?php
			if ($product_published && $product_published->getTimestamp() > (time() - 86400 * 5)) : ?>
				<span class="product-badge hot">NEW</span>
			<?php
			endif;
			?>
		</div>
		<a href="<?php echo $product->get_permalink() ?>" class="d-block">
			<?php echo $product->get_image('full') ?>
		</a>

		<?php custom_add_to_wishlist_button(); ?>
	</figure>

	<!-- Product Details -->
	<div class="product-details">
		<h2 class="product-name"><a href="<?php echo $product->get_permalink() ?>"><?php echo $product->get_title() ?></a></h2>
		<?php
		if ($product->is_in_stock()): ?>
			<?php
			$icon_check = '<svg width="25" height="17" viewBox="0 0 25 17" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M6.80197 10.6692C7.47019 11.3408 8.69775 12.573 9.28206 12.9743L20.7333 1.45849L20.9854 1.18071C21.3947 0.709402 21.9635 0.0551611 22.6363 0.00451539C22.8615 -0.0121145 23.0732 0.0177393 23.2641 0.0850149C23.5222 0.176857 23.7418 0.341262 23.9017 0.552916C24.06 0.763058 24.1609 1.02423 24.1821 1.31148C24.1995 1.53863 24.1678 1.78428 24.0778 2.03487C23.8911 2.54397 18.1005 8.25448 16.0713 10.2561L15.437 10.8839C14.3939 11.9244 13.5635 12.7839 12.8745 13.4959C11.1715 15.2572 10.2908 16.1669 9.52773 16.3978C8.59948 16.6783 8.02575 16.0717 6.84427 14.8229C6.54266 14.5039 6.19192 14.1335 5.73385 13.6754L5.09284 13.0484C3.51602 11.5177 0.0596547 8.16338 0.00409559 7.4328C-0.0117784 7.2117 0.0192093 7.00422 0.0845951 6.8194C0.179461 6.55748 0.348034 6.33748 0.563089 6.17987C0.776255 6.0234 1.0378 5.92627 1.32051 5.90737C1.5401 5.89187 1.77518 5.92288 2.01215 6.00754C2.39578 6.14549 3.03187 6.80617 3.4359 7.22419L3.66116 7.45587C4.14418 7.93322 4.61889 8.4287 5.09284 8.92457C5.53051 9.38265 5.96707 9.84075 6.50112 10.3691L6.80197 10.6692Z" fill="#B6713D"/>
</svg>'; ?>
			<div class="stock-status"><?php echo $icon_check ?> <span>В наличии</span></div>
		<?php else : ?>
			<div class="stock-status"><span>Нет в наличии</span></div>
		<?php endif; ?>

		<div class="product-prices">
			<?php echo $product->get_price_html() ?>
		</div>

		<?php // do_action('woocommerce_after_shop_loop_item'); 
		?>
	</div>
</div>
<!-- End Single Product -->
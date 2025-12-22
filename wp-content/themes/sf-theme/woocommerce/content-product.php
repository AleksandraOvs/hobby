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
		<a href="<?php echo $product->get_permalink() ?>" class="d-block">
			<!-- <img class="primary-thumb" src="assets/img/products/prod-1-1.jpg"
					alt="Product" /> -->
			<?php echo $product->get_image('full') ?>

			<!-- <img class="secondary-thumb" src="assets/img/products/prod-1-2.jpg"
					alt="Product" /> -->
		</a>


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


	</figure>

	<!-- Product Details -->
	<div class="product-details">
		<h2 class="product-name"><a href="<?php echo $product->get_permalink() ?>"><?php echo $product->get_title() ?></a></h2>
		<div class="product-prices">
			<?php echo $product->get_price_html() ?>
		</div>
		<div class="list-view-content">
			<p class="product-desc"><?php $product->get_short_description() ?></p>

			<div class="list-btn-group mt-30 mt-sm-14">
				<a href="<?php echo $product->add_to_cart_url() ?>" class="btn btn-black"><?php echo $product->add_to_cart_text() ?></a>

			</div>
		</div>
	</div>
</div>
<!-- End Single Product -->
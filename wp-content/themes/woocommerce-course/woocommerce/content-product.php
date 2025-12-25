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
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$product_published = $product->get_date_created(); // $product_published->date
?>
<!-- Start Single Product -->
<div <?php $class = is_product() ? 'col-lg-3 col-sm-6' : 'col-lg-4 col-sm-6'; wc_product_class( $class, $product ); ?>>
		<div class="single-product-wrap">
				<!-- Product Thumbnail -->
				<figure class="product-thumbnail">
						<a href="<?php echo $product->get_permalink() ?>" class="d-block">
								<?php echo $product->get_image() ?>
						</a>
						<figcaption class="product-hvr-content">
								<a href="<?php echo $product->add_to_cart_url() ?>" class="btn btn-black btn-addToCart"><?php echo $product->add_to_cart_text() ?></a>

								<?php if( $product->is_on_sale() ) : ?>
									<span class="product-badge sale">Распродажа</span>
								<?php elseif( $product->is_featured() ) : ?>
									<span class="product-badge hot">Хит продаж</span>
								<?php elseif( $product_published->getTimestamp() < ( time() - 86400 * 5 ) ) : ?>
									<span class="product-badge">Новинка</span>
								<?php endif; ?>

						</figcaption>
				</figure>

				<!-- Product Details -->
				<div class="product-details">
						<h2 class="product-name"><a href="<?php echo $product->get_permalink() ?>"><?php echo $product->get_title() ?></a></h2>
						<div class="product-prices">
							<?php echo $product->get_price_html() ?>
						</div>
						<div class="list-view-content">
								<p class="product-desc"><?php echo $product->get_short_description() ?></p>

								<div class="list-btn-group mt-30 mt-sm-14">

										<?php
										echo sprintf(
											'<a href="%s" data-product_id="%s" class="%s">%s</a>',
											$product->add_to_cart_url(),
											$product->get_id(),
											'btn btn-black product_type_' . $product->get_type() . ' ' . ( $product->supports( 'ajax_add_to_cart' ) ? 'add_to_cart_button ajax_add_to_cart' : '' ),
											$product->add_to_cart_text()
										);
										// loading
										// added

										?>

								</div>
						</div>
				</div>
		</div>
</div>
<!-- End Single Product -->

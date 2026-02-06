<?php
defined('ABSPATH') || exit;

global $product;

do_action('woocommerce_before_single_product');

if (post_password_required()) {
    echo get_the_password_form();
    return;
}
?>

<div class="page-header-wrapper">
    <div class="container">
        <?php woocommerce_breadcrumb(); ?>
    </div>
</div>

<div class="page-content woo-page">
    <div class="container">

        <h1 class="product-title"><?php the_title(); ?></h1>

        <div id="product-<?php the_ID(); ?>" <?php wc_product_class('single-product', $product); ?>>

            <div class="single-product__top">

                <?php
                $product_image_id = $product->get_image_id();
                $product_gallery_ids = $product->get_gallery_image_ids();
                ?>

                <div class="single-product-thumb-wrap <?php if ($product_gallery_ids) echo 'tab-style-left'; ?>">

                    <?php if ($product_gallery_ids) : ?>
                        <div class="swiper product-thumbnail-nav">
                            <div class="swiper-wrapper">
                                <figure class="swiper-slide product-thumb-item">
                                    <?php echo wp_get_attachment_image($product_image_id); ?>
                                </figure>
                                <?php foreach ($product_gallery_ids as $gid) : ?>
                                    <figure class="swiper-slide product-thumb-item">
                                        <?php echo wp_get_attachment_image($gid, 'full'); ?>
                                    </figure>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="swiper product-thumb-carousel">
                        <div class="swiper-wrapper">
                            <figure class="swiper-slide product-thumb-item">
                                <?php echo wp_get_attachment_image($product_image_id, 'full'); ?>
                            </figure>
                            <?php foreach ($product_gallery_ids as $gid) : ?>
                                <figure class="swiper-slide product-thumb-item">
                                    <?php echo wp_get_attachment_image($gid, 'full'); ?>
                                </figure>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <?php custom_add_to_wishlist_button(); ?>
                </div>


                <div class="single-product-add-to-cart">

                    <?php if ($product->is_in_stock()) : ?>
                        <div class="stock-status"><span>В наличии</span></div>
                    <?php else : ?>
                        <div class="stock-status"><span>Нет в наличии</span></div>
                    <?php endif; ?>


                    <?php if ($product->is_type('variable')) : ?>

                        <?php
                        /**
                         * ВАЖНО:
                         * используем стандартный шаблон WooCommerce,
                         * чтобы JS вариаций работал корректно
                         */
                        wc_get_template('single-product/add-to-cart/variable.php');
                        ?>


                    <?php else : ?>

                        <form class="cart"
                            action="<?php echo esc_url($product->add_to_cart_url()); ?>"
                            method="post"
                            enctype="multipart/form-data">

                            <div class="cart__price-update">

                                <?php
                                woocommerce_quantity_input([
                                    'min_value'   => $product->get_min_purchase_quantity(),
                                    'max_value'   => $product->get_max_purchase_quantity(),
                                    'input_value' => $product->get_min_purchase_quantity(),
                                ]);
                                ?>

                                <?php $base_price = (float) $product->get_price(); ?>

                                <span class="price-single"
                                    data-base-price="<?php echo esc_attr($base_price); ?>">
                                    <?php echo wc_price($base_price); ?>
                                </span>

                                <span class="price-total">
                                    <?php echo wc_price($base_price); ?>
                                </span>

                            </div>

                            <button type="submit"
                                name="add-to-cart"
                                value="<?php echo esc_attr($product->get_id()); ?>"
                                class="single_add_to_cart_button button alt">
                                <?php echo esc_html($product->single_add_to_cart_text()); ?>
                            </button>

                        </form>

                    <?php endif; ?>

                </div>
            </div>

            <?php do_action('woocommerce_after_single_product'); ?>

        </div>
    </div>
<?php
defined('ABSPATH') || exit;

global $product;

do_action('woocommerce_before_single_product');

if (post_password_required()) {
    echo get_the_password_form();
    return;
}

$product_image_id   = $product->get_image_id();
$placeholder_url = get_stylesheet_directory_uri() . '/assets/img/svg/placeholder.svg';
$product_gallery_ids = (array) $product->get_gallery_image_ids();

/**
 * Собираем единый массив изображений:
 * первое — главное, дальше галерея (без дублей)
 */
$image_ids = [];

if ($product_image_id) {
    $image_ids[] = $product_image_id;
}

foreach ($product_gallery_ids as $gallery_id) {
    if ($gallery_id && $gallery_id !== $product_image_id) {
        $image_ids[] = $gallery_id;
    }
}

$has_gallery = count($image_ids) > 1;
?>

<!-- Breadcrumbs -->
<div class="page-header-wrapper">
    <div class="container">
        <?php woocommerce_breadcrumb();
        ?>
        <?php //site_breadcrumbs(); 
        ?>
    </div>
</div>

<div class="page-content woo-page">
    <div class="container">

        <h1 class="product-title"><?php the_title(); ?></h1>

        <div id="product-<?php the_ID(); ?>" <?php wc_product_class('single-product', $product); ?>>

            <!-- ================= Верхний блок ================= -->
            <div class="single-product__top">

                <!-- Start Single Product Thumbnail -->

                <?php
                $product_image_id = $product->get_image_id();
                $product_gallery_ids = $product->get_gallery_image_ids();
                ?>
                <div class="single-product-thumb-wrap
				<?php if ($product_gallery_ids) : ?>
					tab-style-left <?php endif; ?>">

                    <?php if ($product_gallery_ids) : ?>
                        <!-- Product Thumbnail Nav -->
                        <div class="swiper product-thumbnail-nav">
                            <div class="swiper-wrapper">
                                <figure class="swiper-slide product-thumb-item">
                                    <?php echo wp_get_attachment_image($product_image_id); ?>
                                </figure>
                                <?php foreach ($product_gallery_ids as $product_gallery_id) : ?>
                                    <figure class="swiper-slide product-thumb-item">
                                        <?php echo wp_get_attachment_image($product_gallery_id, 'full'); ?>
                                    </figure>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <!-- Product Thumbnail Large View -->

                    <div class="swiper product-thumb-carousel">
                        <div class="swiper-wrapper">
                            <figure class="swiper-slide product-thumb-item">
                                <?php
                                if ($product_image_id) {
                                    echo wp_get_attachment_image($product_image_id);
                                } else {
                                    echo '<img src="' . esc_url($placeholder_url) . '" alt="Placeholder">';
                                }
                                ?>
                            </figure>
                            <?php if ($product_gallery_ids) : ?>
                                <?php foreach ($product_gallery_ids as $product_gallery_id) : ?>
                                    <figure class="swiper-slide product-thumb-item">
                                        <?php echo wp_get_attachment_image($product_gallery_id, 'full'); ?>
                                    </figure>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php custom_add_to_wishlist_button(); ?>
                </div>

                <!-- End Single Product Thumbnail -->

                <div class="single-product-add-to-cart">
                    <?php
                    $icon_check = '<svg width="25" height="17" viewBox="0 0 25 17" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.80197 10.6692C7.47019 11.3408 8.69775 12.573 9.28206 12.9743L20.7333 1.45849L20.9854 1.18071C21.3947 0.709402 21.9635 0.0551611 22.6363 0.00451539C22.8615 -0.0121145 23.0732 0.0177393 23.2641 0.0850149C23.5222 0.176857 23.7418 0.341262 23.9017 0.552916C24.06 0.763058 24.1609 1.02423 24.1821 1.31148C24.1995 1.53863 24.1678 1.78428 24.0778 2.03487C23.8911 2.54397 18.1005 8.25448 16.0713 10.2561L15.437 10.8839C14.3939 11.9244 13.5635 12.7839 12.8745 13.4959C11.1715 15.2572 10.2908 16.1669 9.52773 16.3978C8.59948 16.6783 8.02575 16.0717 6.84427 14.8229L5.09284 13.0484C3.51602 11.5177 0.0596547 8.16338 0.00409559 7.4328C-0.0117784 7.2117 0.0192093 7.00422 0.0845951 6.8194C0.179461 6.55748 0.348034 6.33748 0.563089 6.17987C0.776255 6.0234 1.0378 5.92627 1.32051 5.90737C1.5401 5.89187 1.77518 5.92288 2.01215 6.00754C2.39578 6.14549 3.03187 6.80617 3.4359 7.22419L6.80197 10.6692Z" fill="#B6713D"/></svg>';

                    if ($product->is_in_stock()) : ?>
                        <div class="stock-status"><?php echo $icon_check; ?><span>В наличии</span></div>
                    <?php else : ?>
                        <div class="stock-status"><span>Нет в наличии</span></div>
                    <?php endif; ?>

                    <?php if ($product->is_type('variable')) : ?>

                        <?php
                        /**
                         * Стандартный шаблон WooCommerce для вариативного товара
                         */
                        woocommerce_variable_add_to_cart();


                        ?>

                    <?php else : ?>

                        <form class="cart"
                            action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>"
                            method="post"
                            enctype="multipart/form-data">

                            <div class="cart__price-update">
                                <?php
                                woocommerce_quantity_input([
                                    'min_value'   => $product->get_min_purchase_quantity(),
                                    'max_value'   => $product->get_max_purchase_quantity(),
                                    'input_value' => isset($_POST['quantity'])
                                        ? wc_stock_amount(wp_unslash($_POST['quantity']))
                                        : $product->get_min_purchase_quantity(),
                                    'step'        => 1, // шаг
                                ]);
                                ?>

                                <?php
                                $base_price = floatval($product->get_price());
                                $bulk_prices = get_post_meta($product->get_id(), '_bulk_prices', true) ?: [];
                                ?>
                                <span class="price-single"
                                    data-base-price="<?php echo $base_price; ?>"
                                    data-bulk='<?php echo json_encode($bulk_prices); ?>'>
                                    <?php echo wc_price($base_price); ?>
                                </span>
                                <span class="price-total">
                                    <?php echo wc_price($base_price); ?>
                                </span>
                            </div>

                            <div class="bulk-discount-wrapper">
                                <?php
                                if ($product && $product->is_type('simple')) {
                                    echo wc_render_bulk_discount_table($product);
                                }
                                ?>
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

            <!-- ================= Описание + характеристики ================= -->
            <div class="single-product__info">

                <div class="info-description">
                    <h2>Описание</h2>
                    <?php if (wc_product_sku_enabled() && ($product->get_sku() || $product->is_type('variable'))) : ?>
                        <span class="sku"><?php esc_html_e('SKU:', 'woocommerce'); ?> <span><?php echo ($sku = $product->get_sku()) ? $sku : esc_html__('N/A', 'woocommerce'); ?></span></span>
                    <?php endif; ?>
                    <?php echo wpautop($product->get_description()); ?>

                    <div class="single-product__delivery">
                        <h2>Доставка</h2>
                    </div>
                </div>

                <div class="info-attributes">
                    <h2>Характеристики</h2>
                    <?php sf_product_attributes(); ?>
                </div>

            </div>

            <!-- ================= Related products ================= -->
            <div class="single-product__related">
                <div class="relative-products__head">
                    <h2>С этим товаром также покупают</h2>
                    <a href="<?php echo esc_url(site_url('/related-products/?product_id=' . $product->get_id())); ?>">
                        <span>Смотреть еще</span>
                        <svg width="48" height="9" viewBox="0 0 48 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 3.21917H44.9276L43.2673 1.5456C42.9158 1.1941 42.9158 0.616966 43.2673 0.26547C43.6192 -0.086404 44.1891 -0.0894275 44.5406 0.262069L47.7426 3.46182C48.0877 3.81104 48.0843 4.38024 47.7369 4.72682L44.5406 7.92317C44.191 8.27316 43.6173 8.27316 43.2673 7.92317C42.9158 7.57167 42.9158 7.01571 43.2673 6.66421L44.9276 5.01898H0V3.21917Z" fill="#8B4512" />
                        </svg>
                    </a>
                </div>

                <?php
                $upsell_ids = $product->get_upsell_ids();
                if (! empty($upsell_ids)) {
                    $products_ids = $upsell_ids;
                    $loop_name    = 'upsells';
                } else {
                    $products_ids = wc_get_related_products($product->get_id(), 4);
                    $loop_name    = 'related';
                }

                if (! empty($products_ids)) :
                    wc_set_loop_prop('name', $loop_name);
                    wc_set_loop_prop('columns', 4);
                ?>

                    <div class="products-on-column">
                        <?php foreach ($products_ids as $product_id) : ?>
                            <?php
                            $post_object = get_post($product_id);
                            setup_postdata($GLOBALS['post'] = $post_object);
                            wc_get_template_part('content', 'product');
                            ?>
                        <?php endforeach; ?>
                    </div>

                    <div class="relative-products__head visible-on-mobile">
                        <a href="<?php echo esc_url(site_url('/related-products/?product_id=' . $product->get_id())); ?>">
                            <span>Смотреть еще</span>
                            <svg width="48" height="9" viewBox="0 0 48 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 3.21917H44.9276L43.2673 1.5456C42.9158 1.1941 42.9158 0.616966 43.2673 0.26547C43.6192 -0.086404 44.1891 -0.0894275 44.5406 0.262069L47.7426 3.46182C48.0877 3.81104 48.0843 4.38024 47.7369 4.72682L44.5406 7.92317C44.191 8.27316 43.6173 8.27316 43.2673 7.92317C42.9158 7.57167 42.9158 7.01571 43.2673 6.66421L44.9276 5.01898H0V3.21917Z" fill="#8B4512" />
                            </svg>
                        </a>
                    </div>

                <?php
                    wp_reset_postdata();
                endif;
                ?>

            </div>

        </div>
    </div>
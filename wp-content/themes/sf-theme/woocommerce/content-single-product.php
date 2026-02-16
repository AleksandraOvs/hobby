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

            <!-- ================= Описание + характеристики ================= -->
            <div class="single-product__info">
                <div class="info-description">
                    <h2>Описание</h2> <?php if (wc_product_sku_enabled() && ($product->get_sku() || $product->is_type('variable'))) : ?> <span class="sku"><?php esc_html_e('SKU:', 'woocommerce'); ?> <span><?php echo ($sku = $product->get_sku()) ? $sku : esc_html__('N/A', 'woocommerce'); ?></span></span> <?php endif; ?> <?php echo wpautop($product->get_description()); ?> <div class="single-product__delivery">
                        <h2>Доставка</h2>
                        <div class="delivery-desc">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 15H11V9H9V15ZM10 7C10.2833 7 10.521 6.904 10.713 6.712C10.905 6.52 11.0007 6.28267 11 6C10.9993 5.71733 10.9033 5.48 10.712 5.288C10.5207 5.096 10.2833 5 10 5C9.71667 5 9.47933 5.096 9.288 5.288C9.09667 5.48 9.00067 5.71733 9 6C8.99933 6.28267 9.09533 6.52033 9.288 6.713C9.48067 6.90567 9.718 7.00133 10 7ZM10 20C8.61667 20 7.31667 19.7373 6.1 19.212C4.88334 18.6867 3.825 17.9743 2.925 17.075C2.025 16.1757 1.31267 15.1173 0.788001 13.9C0.263335 12.6827 0.000667932 11.3827 1.26582e-06 10C-0.000665401 8.61733 0.262001 7.31733 0.788001 6.1C1.314 4.88267 2.02633 3.82433 2.925 2.925C3.82367 2.02567 4.882 1.31333 6.1 0.788C7.318 0.262667 8.618 0 10 0C11.382 0 12.682 0.262667 13.9 0.788C15.118 1.31333 16.1763 2.02567 17.075 2.925C17.9737 3.82433 18.6863 4.88267 19.213 6.1C19.7397 7.31733 20.002 8.61733 20 10C19.998 11.3827 19.7353 12.6827 19.212 13.9C18.6887 15.1173 17.9763 16.1757 17.075 17.075C16.1737 17.9743 15.1153 18.687 13.9 19.213C12.6847 19.739 11.3847 20.0013 10 20ZM10 18C12.2333 18 14.125 17.225 15.675 15.675C17.225 14.125 18 12.2333 18 10C18 7.76667 17.225 5.875 15.675 4.325C14.125 2.775 12.2333 2 10 2C7.76667 2 5.875 2.775 4.325 4.325C2.775 5.875 2 7.76667 2 10C2 12.2333 2.775 14.125 4.325 15.675C5.875 17.225 7.76667 18 10 18Z" fill="#8B4512" />
                            </svg>

                            <p>Расчет стоимости доставки производится менеджером после подтверждения заказа</p>
                        </div>
                    </div>
                </div>
                <div class="info-attributes">
                    <h2>Характеристики</h2> <?php sf_product_attributes(); ?>
                </div>
            </div> <!-- ================= Related products ================= -->
            <div class="single-product__related">
                <div class="relative-products__head">
                    <h2>С этим товаром также покупают</h2> <a href="<?php echo esc_url(site_url('/related-products/?product_id=' . $product->get_id())); ?>"> <span>Смотреть еще</span> <svg width="48" height="9" viewBox="0 0 48 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 3.21917H44.9276L43.2673 1.5456C42.9158 1.1941 42.9158 0.616966 43.2673 0.26547C43.6192 -0.086404 44.1891 -0.0894275 44.5406 0.262069L47.7426 3.46182C48.0877 3.81104 48.0843 4.38024 47.7369 4.72682L44.5406 7.92317C44.191 8.27316 43.6173 8.27316 43.2673 7.92317C42.9158 7.57167 42.9158 7.01571 43.2673 6.66421L44.9276 5.01898H0V3.21917Z" fill="#8B4512" />
                        </svg> </a>
                </div> <?php $upsell_ids = $product->get_upsell_ids();
                        if (! empty($upsell_ids)) {
                            $products_ids = $upsell_ids;
                            $loop_name = 'upsells';
                        } else {
                            $products_ids = wc_get_related_products($product->get_id(), 4);
                            $loop_name = 'related';
                        }
                        if (! empty($products_ids)) : wc_set_loop_prop('name', $loop_name);
                            wc_set_loop_prop('columns', 4); ?> <div class="products-on-column"> <?php foreach ($products_ids as $product_id) : ?> <?php $post_object = get_post($product_id);
                                                                                                                                                    setup_postdata($GLOBALS['post'] = $post_object);
                                                                                                                                                    wc_get_template_part('content', 'product'); ?> <?php endforeach; ?> </div>
                    <div class="relative-products__head visible-on-mobile"> <a href="<?php echo esc_url(site_url('/related-products/?product_id=' . $product->get_id())); ?>"> <span>Смотреть еще</span> <svg width="48" height="9" viewBox="0 0 48 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 3.21917H44.9276L43.2673 1.5456C42.9158 1.1941 42.9158 0.616966 43.2673 0.26547C43.6192 -0.086404 44.1891 -0.0894275 44.5406 0.262069L47.7426 3.46182C48.0877 3.81104 48.0843 4.38024 47.7369 4.72682L44.5406 7.92317C44.191 8.27316 43.6173 8.27316 43.2673 7.92317C42.9158 7.57167 42.9158 7.01571 43.2673 6.66421L44.9276 5.01898H0V3.21917Z" fill="#8B4512" />
                            </svg> </a> </div> <?php wp_reset_postdata();
                                            endif; ?>
            </div>
        </div>
    </div>

</div>
</div>
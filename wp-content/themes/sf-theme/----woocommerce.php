<?php

/**
 * WooCommerce main template
 *
 * Используется для:
 * – shop
 * – product category
 * – product tag
 * – single product
 * – cart / checkout / account
 */

defined('ABSPATH') || exit;

get_header('shop');
?>

<main id="primary" class="site-main woocommerce-page">

    <?php
    /**
     * Хлебные крошки, уведомления, результаты поиска и т.д.
     */
    do_action('woocommerce_before_main_content');
    ?>

    <?php if (woocommerce_content()) : ?>
        <?php woocommerce_content(); ?>
    <?php endif; ?>

    <?php
    /**
     * Закрывающие теги + сайдбар (если есть)
     */
    do_action('woocommerce_after_main_content');
    ?>

</main>

<?php
get_footer('shop');

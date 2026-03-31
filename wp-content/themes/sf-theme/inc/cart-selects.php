<?php
/* ---------- selected logic ---------- */

// по умолчанию товар выбран
add_action('woocommerce_add_to_cart', function ($key) {
    if (isset(WC()->cart->cart_contents[$key])) {
        WC()->cart->cart_contents[$key]['selected'] = 1;
    }
}, 10);

// AJAX переключение чекбокса
add_action('wp_ajax_toggle_cart_item', 'sf_toggle_cart_item');
add_action('wp_ajax_nopriv_toggle_cart_item', 'sf_toggle_cart_item');

function sf_toggle_cart_item()
{
    if (empty($_POST['cart_item_key']) || !isset(WC()->cart->cart_contents[$_POST['cart_item_key']])) {
        wp_send_json_error();
    }

    $key = wc_clean($_POST['cart_item_key']);
    $selected = !empty($_POST['selected']) ? 1 : 0;

    // Устанавливаем selected
    WC()->cart->cart_contents[$key]['selected'] = $selected;
    WC()->cart->set_session();
    // Пересчёт totals с учётом только выбранных товаров
    WC()->cart->calculate_totals();

    $totals = WC()->cart->get_totals();

    wp_send_json_success([
        'total' => wc_price($totals['total']),
        'subtotal' => wc_price($totals['subtotal']),
        'items_count' => WC()->cart->get_cart_contents_count(),
        'weight' => WC()->cart->get_cart_contents_weight() . ' ' . get_option('woocommerce_weight_unit'),
        'discount_total' => wc_price($totals['discount_total']),
        'applied_coupons' => WC()->cart->get_coupons(),
        'mini_cart' => wc_get_template_html('cart/mini-cart.php'),
    ]);
}

add_action('woocommerce_cart_calculate_fees', function ($cart) {

    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    $excluded_total = 0;

    foreach ($cart->get_cart() as $cart_item) {
        if (empty($cart_item['selected'])) {
            $line_total = $cart_item['data']->get_price() * $cart_item['quantity'];
            $excluded_total += $line_total;
        }
    }

    if ($excluded_total > 0) {
        $cart->add_fee(
            'Исключённые товары',
            -$excluded_total,
            false
        );
    }
}, 99);

// Чтобы "Исключённые товары" не считались в totals
add_filter('woocommerce_get_cart_fees', function ($fees) {
    foreach ($fees as $key => $fee) {
        if (isset($fee->name) && $fee->name === 'Исключённые товары') {
            // не удаляем полностью, оставляем в отдельном выводе
            unset($fees[$key]);
        }
    }
    return $fees;
}, 20);

/* ---------- Checkout products block ---------- */
add_action('sf_checkout_products_block', function () {

    if (WC()->cart->is_empty()) return;

    echo '<div class="checkout-products-block">';
    echo '<h3>Товары в заказе</h3>';
    echo '<div class="cart-flex woocommerce-cart-form__contents checkout-products-block__contents">';

    foreach (WC()->cart->get_cart() as $item) {
        // Проверяем selected
        if (isset($item['selected']) && $item['selected'] == 0) {
            continue; // пропускаем товар
        }

        $_product = $item['data'];
        if (! $_product || ! $_product->exists()) continue;

        $qty = $item['quantity'];
?>
        <div class="cart-flex__row cart_item">
            <div class="cart-flex__col cart-flex__col--product checkout-products-block__contents__product">

                <?= $_product->get_image(); ?>
                <div class="cart-product-item__name">
                    <?= esc_html($_product->get_name()); ?>
                    <div class="product-sku">Артикул:
                        <?php if ($sku = $_product->get_sku()) { ?>
                            <?= esc_html($sku); ?>
                        <?php } else {
                            echo '—';
                        } ?>
                    </div>
                </div>

            </div>

            <div class="cart-flex__col cart-flex__col--qty">
                <div class="cart-flex__col__label">Кол-во:</div>
                <p><?= esc_html($qty); ?> шт.</p>
            </div>

            <div class="cart-flex__col cart-flex__col--total">
                <div class="cart-flex__col__label">Сумма:</div>
                <?= WC()->cart->get_product_subtotal($_product, $qty); ?>
            </div>
        </div>
    <?php }

    echo '</div></div>';
});

// Показывать на странице оформления заказа только выбранные товары
add_filter('woocommerce_cart_item_visible', function ($visible, $cart_item, $cart_item_key) {
    // Если selected явно 0, скрываем товар
    if (isset($cart_item['selected']) && $cart_item['selected'] == 0) {
        return false;
    }

    return $visible;
}, 10, 3);

/* ---------- Cart count fragment ---------- */
add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
    ob_start();
    ?>
    <span class="cart-count"><?= WC()->cart->get_cart_contents_count(); ?></span>
<?php
    $fragments['span.cart-count'] = ob_get_clean();
    return $fragments;
});

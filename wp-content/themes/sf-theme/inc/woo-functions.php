<?php
/* ======================================
 * Woo helpers / cart / checkout
 * ====================================== */

/* ---------- Cart fragments ---------- */
add_action('wp_footer', function () {
    wp_enqueue_script('wc-cart-fragments');
});

/* ---------- Stock status in loop ---------- */
add_action('woocommerce_after_shop_loop_item_title', function () {
    global $product;
    if (! $product) return;

    $status = $product->get_stock_status();

    if ($status === 'instock') {
        if (
            $product->managing_stock() &&
            $product->get_stock_quantity() === 0 &&
            $product->backorders_allowed()
        ) {
            echo '<span class="stock-status on-order">На заказ</span>';
        } else {
            echo '<span class="stock-status in-stock">В наличии</span>';
        }
    } elseif ($status === 'onbackorder') {
        echo '<span class="stock-status on-order">На заказ</span>';
    } else {
        echo '<span class="stock-status out-of-stock">Нет в наличии</span>';
    }
});

/* ---------- UI cleanup ---------- */
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
add_filter('woocommerce_quantity_input_type', fn() => 'text');

/* ---------- Product attributes ---------- */
function sf_product_attributes()
{
    global $product;
    if (! $product) return;

    $attributes = $product->get_attributes();
    if (empty($attributes)) return;
?>
    <div class="product-specs">
        <?php foreach ($attributes as $attribute) :
            $label = wc_attribute_label($attribute->get_name());

            if ($attribute->is_taxonomy()) {
                $values = wc_get_product_terms(
                    $product->get_id(),
                    $attribute->get_name(),
                    ['fields' => 'names']
                );
                $value = implode(', ', $values);
            } else {
                $value = implode(', ', $attribute->get_options());
            }

            if (! $value) continue;
        ?>
            <div class="product-specs__row">
                <div class="product-specs__name"><?= esc_html($label); ?></div>
                <div class="product-specs__value"><?= esc_html($value); ?></div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php }

/* ---------- AJAX add to cart ---------- */
add_action('wp_ajax_ajax_add_to_cart', 'theme_ajax_add_to_cart');
add_action('wp_ajax_nopriv_ajax_add_to_cart', 'theme_ajax_add_to_cart');

function theme_ajax_add_to_cart()
{
    $product_id = intval($_POST['add-to-cart'] ?? 0);
    $qty        = intval($_POST['quantity'] ?? 1);

    if (! $product_id) wp_send_json_error();

    $key = WC()->cart->add_to_cart($product_id, $qty);

    if ($key) {
        WC()->session?->__unset('wc_notices');
        wp_send_json_success([
            'cart_count' => WC()->cart->get_cart_contents_count(),
        ]);
    }

    wp_send_json_error();
}

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
    if (
        empty($_POST['cart_item_key']) ||
        ! isset(WC()->cart->cart_contents[$_POST['cart_item_key']])
    ) {
        wp_send_json_error();
    }

    $key = wc_clean($_POST['cart_item_key']);
    $selected = ! empty($_POST['selected']) ? 1 : 0;

    WC()->cart->cart_contents[$key]['selected'] = $selected;
    WC()->cart->set_session();

    // 🔥 ВАЖНО
    WC()->cart->calculate_totals();

    wp_send_json_success([
        'totals' => WC()->cart->get_totals(),
    ]);
}

// ❗ единственное место, где selected влияет на корзину
// add_action('woocommerce_before_calculate_totals', function ($cart) {

//     if (is_admin() && ! defined('DOING_AJAX')) {
//         return;
//     }

//     foreach ($cart->get_cart() as $cart_item) {
//         if (empty($cart_item['selected'])) {
//             $cart_item['data']->set_price(0);
//         }
//     }
// }, 100);

add_action('woocommerce_cart_calculate_fees', function ($cart) {

    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    $excluded_total = 0;

    foreach ($cart->get_cart() as $cart_item) {

        // если товар НЕ выбран
        if (empty($cart_item['selected'])) {

            // WooCommerce иногда ещё не посчитал line_total
            $line_total = isset($cart_item['line_total'])
                ? (float) $cart_item['line_total']
                : 0;

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
});

// Скрываем категорию 'misc' на фронтенде
// add_filter('get_terms', function ($terms, $taxonomies, $args, $term_query) {
//     if (in_array('product_cat', $taxonomies) && !is_admin()) {
//         foreach ($terms as $key => $term) {
//             if ($term->slug === 'misc') { // или $term->term_id === 123
//                 unset($terms[$key]);
//             }
//         }
//     }
//     return $terms;
// }, 10, 4);

// Исключаем категорию "misc" с фронтенда
add_action('pre_get_posts', function ($query) {
    if (!is_admin() && $query->is_main_query() && (is_shop() || is_product_category() || is_product_tag())) {
        $tax_query = (array) $query->get('tax_query');

        $tax_query[] = array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => array('misc'), // slug вашей категории
            'operator' => 'NOT IN',
        );

        $query->set('tax_query', $tax_query);
    }
});

// Исключаем категорию "misc" из виджетов и других списков категорий
add_filter('woocommerce_product_categories_widget_args', function ($args) {
    if (isset($args['exclude'])) {
        $args['exclude'][] = get_term_by('slug', 'misc', 'product_cat')->term_id;
    } else {
        $args['exclude'] = array(get_term_by('slug', 'misc', 'product_cat')->term_id);
    }
    return $args;
});


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

/* ---------- Checkout fields (единый блок) ---------- */
add_filter('woocommerce_checkout_fields', function ($fields) {

    // Убираем стандартные
    unset(
        $fields['billing']['billing_first_name'],
        $fields['billing']['billing_last_name'],
        $fields['billing']['billing_address_1'],
        $fields['billing']['billing_address_2']
    );

    // ФИО
    $fields['billing']['billing_full_name'] = [
        'type'        => 'text',
        'required'    => true,
        'priority'    => 10,
        'class'       => ['form-row-wide'],
        'placeholder' => 'Ф.И.О *',
    ];

    // Телефон
    $fields['billing']['billing_phone']['priority']    = 20;
    $fields['billing']['billing_phone']['placeholder'] = '+7 (___) ___-__-__';

    // Email
    $fields['billing']['billing_email']['priority']    = 30;
    $fields['billing']['billing_email']['placeholder'] = 'E-mail *';

    // Адрес
    $fields['billing']['billing_full_address'] = [
        'type'        => 'text',
        'required'    => false,
        'priority'    => 60,
        'class'       => ['form-row-wide'],
        'placeholder' => 'Адрес доставки',
    ];

    return $fields;
});

// add_action('woocommerce_before_checkout_billing_form', function () {
//     echo '<div class="checkout-block-title">Покупатель</div>';
// });

// add_action('woocommerce_before_checkout_shipping_form', function () {
//     echo '<div class="checkout-block-title">Способ получения</div>';
// });


/* ---------- Split name on order ---------- */
add_action('woocommerce_checkout_create_order', function ($order, $data) {

    if (empty($data['billing_full_name'])) return;

    $parts = preg_split('/\s+/u', trim($data['billing_full_name']));
    $first = array_shift($parts);
    $last  = implode(' ', $parts);

    $order->set_billing_first_name($first);
    $order->set_billing_last_name($last);
}, 10, 2);


add_action('init', function () {
    remove_action(
        'woocommerce_checkout_order_review',
        'woocommerce_checkout_shipping',
        10
    );
});

add_action('woocommerce_review_order_after_shipping', function () {
    $packages = WC()->shipping()->get_packages();
    $chosen   = WC()->session->get('chosen_shipping_methods');

    if (empty($packages) || empty($chosen)) return;

    foreach ($packages as $i => $package) {
        foreach ($package['rates'] as $rate_id => $rate) {
            if ($rate_id === $chosen[$i]) {
                echo '<div class="review-order__row">';
                echo '<div class="review-order__col">Доставка:</div>';
                echo '<div class="review-order__col">' . esc_html($rate->get_label()) . ': ' . wc_price($rate->get_cost()) . '</div>';
                echo '</div>';
            }
        }
    }
});

add_action('wp', function () {

    // работаем только на странице "Спасибо за заказ"
    if (!is_wc_endpoint_url('order-received')) {
        return;
    }

    // убираем детали заказа
    remove_action(
        'woocommerce_thankyou',
        'woocommerce_order_details_table',
        10
    );

    // убираем данные покупателя
    remove_action(
        'woocommerce_thankyou',
        'woocommerce_order_details_customer_details',
        20
    );
});

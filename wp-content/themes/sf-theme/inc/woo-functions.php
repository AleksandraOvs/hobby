<?php

function enqueue_wc_cart_fragments_in_footer()
{
    wp_enqueue_script('wc-cart-fragments');
}
add_action('wp_footer', 'enqueue_wc_cart_fragments_in_footer');


add_action('woocommerce_after_shop_loop_item_title', 'sf_show_stock_status_loop', 10);
function sf_show_stock_status_loop()
{
    global $product;

    if (! $product) return;

    $stock_status = $product->get_stock_status(); // возвращает 'instock', 'outofstock' или 'onbackorder'

    if ($stock_status === 'instock') {
        // Проверяем разрешены ли предзаказы и нет ли наличия
        if ($product->managing_stock() && $product->get_stock_quantity() === 0 && $product->backorders_allowed()) {
            echo '<span class="stock-status on-order">На заказ</span>';
        } else {
            echo '<span class="stock-status in-stock">В наличии</span>';
        }
    } elseif ($stock_status === 'onbackorder') {
        echo '<span class="stock-status on-order">На заказ</span>';
    } else {
        echo '<span class="stock-status out-of-stock">Нет в наличии</span>';
    }
}

remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

add_filter('woocommerce_quantity_input_type', 'sf_quantity_input_text');
function sf_quantity_input_text($type)
{
    return 'text';
}

/**
 * Вывод атрибутов (характеристик) товара 
 */

function sf_product_attributes()
{
    global $product;

    if (! $product) {
        return;
    }

    $attributes = $product->get_attributes();

    if (empty($attributes)) {
        return;
    }
?>
    <div class="product-specs">
        <?php foreach ($attributes as $attribute) :

            // Название характеристики
            $label = wc_attribute_label($attribute->get_name());

            // Значение
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

            if (! $value) {
                continue;
            }
        ?>

            <div class="product-specs__row">
                <div class="product-specs__name">
                    <?php echo esc_html($label); ?>
                </div>
                <div class="product-specs__value">
                    <?php echo esc_html($value); ?>
                </div>
            </div>

        <?php endforeach; ?>
    </div>
    <?php
}

add_action('wp_ajax_ajax_add_to_cart', 'theme_ajax_add_to_cart');
add_action('wp_ajax_nopriv_ajax_add_to_cart', 'theme_ajax_add_to_cart');

function theme_ajax_add_to_cart()
{
    if (empty($_POST['add-to-cart'])) {
        wp_send_json_error();
    }

    $product_id = intval($_POST['add-to-cart']);
    $quantity   = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    $added = WC()->cart->add_to_cart($product_id, $quantity);

    if ($added) {
        // -----------------------
        // Очищаем уведомления из сессии WooCommerce,
        // чтобы они не появлялись повторно на других страницах
        // -----------------------
        if (isset(WC()->session)) {
            WC()->session->__unset('wc_notices');
        }

        wp_send_json_success([
            'cart_count' => WC()->cart->get_cart_contents_count(),
        ]);
    } else {
        wp_send_json_error();
    }
}



// // По умолчанию — товар считается "отмеченным"
// add_action('woocommerce_add_to_cart', function ($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data) {

//     if (isset(WC()->cart->cart_contents[$cart_item_key])) {
//         WC()->cart->cart_contents[$cart_item_key]['selected'] = 1;
//     }
// }, 10, 6);

// // Убираем из корзины позиции без чекбокса — только при обновлении корзины
// add_action('woocommerce_before_calculate_totals', function ($cart) {

//     if (is_admin() && ! defined('DOING_AJAX')) {
//         return;
//     }

//     // Выполняем ТОЛЬКО если нажата кнопка "Обновить корзину"
//     if (empty($_POST['update_cart'])) {
//         return;
//     }

//     if (empty($_POST['cart'])) {
//         return;
//     }

//     foreach ($cart->get_cart() as $key => $item) {

//         // если чекбокса нет — удаляем позицию
//         if (empty($_POST['cart'][$key]['selected'])) {
//             $cart->remove_cart_item($key);
//         }
//     }
// });


// add_filter('woocommerce_cart_item_set_quantity', function ($quantity, $cart_item_key) {
//     if (isset($_POST['cart'][$cart_item_key]['selected'])) {
//         WC()->cart->cart_contents[$cart_item_key]['selected'] = 1;
//     } else {
//         WC()->cart->cart_contents[$cart_item_key]['selected'] = 0;
//     }

//     return $quantity;
// }, 10, 2);

// add_action('woocommerce_before_calculate_totals', function ($cart) {

//     if (is_admin() && ! defined('DOING_AJAX')) {
//         return;
//     }

//     foreach ($cart->get_cart() as $key => $item) {
//         if (empty($item['selected'])) {
//             $cart->remove_cart_item($key);
//         }
//     }
// });


//список товаров в заказе 
add_action('sf_checkout_products_block', function () {

    if (WC()->cart->is_empty()) {
        return;
    }

    echo '<div class="checkout-products-block">';
    echo '<h3>Товары в заказе</h3>';
    echo '<div class="cart-flex woocommerce-cart-form__contents">';

    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {

        $_product = $cart_item['data'];

        if (! $_product || ! $_product->exists()) {
            continue;
        }

        $product_id        = $_product->get_id();
        $product_permalink = $_product->is_visible() ? $_product->get_permalink() : '';
        $qty               = $cart_item['quantity'];
        $thumb             = $_product->get_image();
    ?>
        <div class="cart-flex__row cart_item">

            <div class="cart-flex__col cart-flex__col--product">
                <div class="cart-product-item">
                    <?php
                    if ($product_permalink) {
                        printf('<a href="%s" class="product-thumb">%s</a>', esc_url($product_permalink), $thumb);
                    } else {
                        echo $thumb;
                    }
                    ?>
                    <div class="cart-product-item__name">
                        <?php
                        echo esc_html($_product->get_name());

                        $sku = $_product->get_sku();
                        if ($sku) {
                            echo '<div class="product-sku">Артикул: ' . esc_html($sku) . '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="cart-flex__col cart-flex__col--price">
                <div class="cart-flex__col__label">Цена:</div>
                <span class="price"><?php echo WC()->cart->get_product_price($_product); ?></span>
            </div>

            <div class="cart-flex__col cart-flex__col--qty">
                <div class="cart-flex__col__label">Кол-во:</div>
                <?php echo esc_html($qty); ?>
            </div>

            <div class="cart-flex__col cart-flex__col--total">
                <div class="cart-flex__col__label">Сумма:</div>
                <span class="price">
                    <?php echo WC()->cart->get_product_subtotal($_product, $qty); ?>
                </span>
            </div>

        </div>
    <?php
    }

    echo '</div></div>';
});

add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
    //$fragments['.cart-count'] = '<span class="cart-count">' . count(WC()->cart->get_cart()) . '</span>';
    return $fragments;
});

// add_filter('woocommerce_checkout_fields', function ($checkout_fields) {

//     $checkout_fields['billing']['billing_first_name']['placeholder'] = 'Как вас зовут?';
//     //echo '<pre>';print_r( $checkout_fields );exit;
//     return $checkout_fields;
// });

// add_filter('woocommerce_order_button_html', function ($html) {

//     return str_replace('button alt', 'btn btn-full btn-black mt-26', $html);
// });

// Добавляем единое поле "ФИО" и убираем стандартные
add_filter('woocommerce_checkout_fields', function ($fields) {

    // убираем стандартные
    unset($fields['billing']['billing_first_name']);
    unset($fields['billing']['billing_last_name']);

    // добавляем наше
    $fields['billing']['billing_full_name'] = [
        'type'        => 'text',
        'label'       => 'Имя и фамилия',
        'required'    => true,
        'class'       => ['form-row-wide'],
        'priority'    => 10,
        'autocomplete' => 'name',
        'placeholder' => 'Ф.И.О.',
    ];

    return $fields;
});

add_action('wp_footer', function () {
    if (! is_cart()) return;
    ?>
    <script>
        jQuery(function($) {
            $(document).on('change', '.qty', function() {
                $('[name="update_cart"]').trigger('click');
            });
        });
    </script>
<?php
});

// При сохранении — разбиваем на имя и фамилию
add_action('woocommerce_checkout_create_order', function ($order, $data) {

    if (! empty($data['billing_full_name'])) {
        $full = trim($data['billing_full_name']);

        // Простейшая логика: первое слово — имя, всё остальное — фамилия
        $parts = preg_split('/\s+/u', $full);

        $first = array_shift($parts);
        $last  = count($parts) ? implode(' ', $parts) : '';

        $order->set_billing_first_name($first);
        $order->set_billing_last_name($last);

        // Для аккаунта/профиля покупателя — тоже запишем
        if ($customer = $order->get_customer_id()) {
            update_user_meta($customer, 'billing_first_name', $first);
            update_user_meta($customer, 'billing_last_name', $last);
        }
    }
}, 10, 2);

add_filter('woocommerce_checkout_fields', function ($fields) {

    // 🔹 Удаляем стандартные поля
    unset($fields['billing']['billing_first_name']);
    unset($fields['billing']['billing_last_name']);

    // 🔹 Добавляем наше поле
    $fields['billing']['billing_full_name'] = array(
        'type'        => 'text',
        'label'       => 'Имя и фамилия',
        'placeholder' => 'Например: Иван Петров',
        'required'    => true,
        'priority'    => 10,   // тут оно становится первым
        'class'       => array('form-row-wide'),
    );

    // 🔹 дальше — твой порядок
    $fields['billing']['billing_phone']['priority']     = 20;
    $fields['billing']['billing_email']['priority']     = 30;
    $fields['billing']['billing_country']['priority']   = 40;
    $fields['billing']['billing_city']['priority']      = 50;
    $fields['billing']['billing_address_1']['priority'] = 60;
    $fields['billing']['billing_postcode']['priority']  = 70;

    return $fields;
});

// Единое поле "Адрес"
add_filter('woocommerce_checkout_fields', function ($fields) {

    // убираем стандартные поля адреса
    unset($fields['billing']['billing_address_1']);
    unset($fields['billing']['billing_address_2']);

    // (по желанию можно убрать и город / индекс)
    // unset( $fields['billing']['billing_city'] );
    // unset( $fields['billing']['billing_postcode'] );

    // добавляем новое поле
    $fields['billing']['billing_full_address'] = [
        'type'        => 'text',
        'label'       => 'Адрес',
        'required'    => true,
        'class'       => ['form-row-wide'],
        'priority'    => 55,
        'placeholder' => 'Например: ул. Ленина, д. 5, кв. 12',
        'autocomplete' => 'street-address',
    ];

    return $fields;
});

// Фрагмент для обновления счетчика корзины
add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {

    $count = WC()->cart->get_cart_contents_count();

    ob_start();
?>
    <span class="cart-count"><?php echo esc_html($count); ?></span>
<?php

    $fragments['span.cart-count'] = ob_get_clean();

    return $fragments;
});

// add_action('init', function () {
//     add_rewrite_endpoint('support', EP_ROOT | EP_PAGES);
// });

// Убираем тип "Вариативный товар" из админки
// add_filter('product_type_selector', function ($types) {
//     unset($types['variable']);
//     return $types;
// });

// add_action('wp_ajax_calc_single_price', 'calc_single_price');
// add_action('wp_ajax_nopriv_calc_single_price', 'calc_single_price');

// function calc_single_price()
// {
//     $product_id = intval($_POST['product_id'] ?? 0);
//     $qty        = intval($_POST['qty'] ?? 1);

//     $product = wc_get_product($product_id);
//     if (!$product) wp_send_json_error();

//     $price = $product->get_price() * $qty;

//     wp_send_json_success([
//         'price_html' => wc_price($price)
//     ]);
// }

add_filter('woocommerce_add_to_cart_fragments', 'custom_woocommerce_cart_totals_fragment');
function custom_woocommerce_cart_totals_fragment($fragments)
{
    ob_start();
?>
    <div class="cart_totals <?php echo WC()->customer->has_calculated_shipping() ? 'calculated_shipping' : ''; ?>">
        <?php woocommerce_cart_totals(); ?>
    </div>
<?php
    $fragments['div.cart_totals'] = ob_get_clean();
    return $fragments;
}

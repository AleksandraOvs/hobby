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

add_filter('get_terms', function ($terms, $taxonomies, $args, $term_query) {

    if (in_array('product_cat', (array) $taxonomies, true)) {

        foreach ($terms as $key => $term) {

            // пропускаем, если это не объект
            if (! is_object($term)) {
                continue;
            }

            if ($term->slug === 'misc') {
                unset($terms[$key]);
            }
        }

        $terms = array_values($terms);
    }

    return $terms;
}, 10, 4);

// По умолчанию — товар считается "отмеченным"
add_action('woocommerce_add_to_cart', function ($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data) {

    if (isset(WC()->cart->cart_contents[$cart_item_key])) {
        WC()->cart->cart_contents[$cart_item_key]['selected'] = 1;
    }
}, 10, 6);

// Убираем из корзины позиции без чекбокса — только при обновлении корзины
add_action('woocommerce_before_calculate_totals', function ($cart) {

    if (is_admin() && ! defined('DOING_AJAX')) {
        return;
    }

    // Выполняем ТОЛЬКО если нажата кнопка "Обновить корзину"
    if (empty($_POST['update_cart'])) {
        return;
    }

    if (empty($_POST['cart'])) {
        return;
    }

    foreach ($cart->get_cart() as $key => $item) {

        // если чекбокса нет — удаляем позицию
        if (empty($_POST['cart'][$key]['selected'])) {
            $cart->remove_cart_item($key);
        }
    }
});


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


add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
    $fragments['.cart-count'] = '<span class="cart-count">' . count(WC()->cart->get_cart()) . '</span>';
    return $fragments;
});

<?php
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

// Скрываем категорию misc из всех списков категорий WooCommerce
add_filter('get_terms', function ($terms, $taxonomies, $args, $term_query) {

    if (in_array('product_cat', (array)$taxonomies)) {
        foreach ($terms as $key => $term) {
            if ($term->slug === 'misc') {
                unset($terms[$key]);
            }
        }
        // Чтобы индексы были последовательными
        $terms = array_values($terms);
    }

    return $terms;
}, 10, 4);

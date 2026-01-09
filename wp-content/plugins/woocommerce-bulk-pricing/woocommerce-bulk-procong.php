<?php
/*
Plugin Name: WooCommerce Bulk Discount by Quantity
Description: Скидки по количеству товара (только проценты). Цена за единицу пересчитывается автоматически в корзине.
Version: 2.2
Author: Шурочка
*/

if (!defined('ABSPATH')) exit;

/* --------------------------------------------------------------------------
| Подключаем фронтальные стили
|-------------------------------------------------------------------------- */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'wc-bulk-discount',
        plugin_dir_url(__FILE__) . 'assets/css/wc-bulk-discount.css',
        [],
        '1.0'
    );
});

/* --------------------------------------------------------------------------
| 1. Админка: мета-блок "Скидки по количеству"
|-------------------------------------------------------------------------- */
add_action('woocommerce_product_options_general_product_data', function () {
    global $post;
    echo '<div class="options_group">';
    echo '<h4>Скидки по количеству</h4>';
    echo '<p>Укажите минимальное количество и процент скидки. Верхняя граница не требуется.</p>';
?>
    <div id="bulk_discount_container">
        <?php
        $rows = get_post_meta($post->ID, '_bulk_discounts', true) ?: [];
        foreach ($rows as $row): ?>
            <div class="bulk-discount-row">
                <input type="number" min="1" name="bulk_discounts[min_qty][]" value="<?php echo esc_attr($row['min_qty']); ?>" placeholder="Мин. кол-во">
                <input type="number" step="0.01" min="0" name="bulk_discounts[discount][]" value="<?php echo esc_attr($row['discount']); ?>" placeholder="% Скидки">
                <button type="button" class="button remove-row">Удалить</button>
            </div>
        <?php endforeach; ?>
    </div>

    <button type="button" class="button" id="add_bulk_discount">Добавить диапазон</button>

    <script>
        jQuery(function($) {
            $('#add_bulk_discount').on('click', function() {
                $('#bulk_discount_container').append(
                    '<div class="bulk-discount-row">' +
                    '<input type="number" min="1" name="bulk_discounts[min_qty][]" placeholder="Мин. кол-во">' +
                    '<input type="number" step="0.01" min="0" name="bulk_discounts[discount][]" placeholder="% Скидки">' +
                    '<button type="button" class="button remove-row">Удалить</button>' +
                    '</div>'
                );
            });

            $(document).on('click', '.remove-row', function() {
                $(this).closest('.bulk-discount-row').remove();
            });
        });
    </script>
<?php
    echo '</div>';
});

/* --------------------------------------------------------------------------
| 2. Сохранение мета-данных
|-------------------------------------------------------------------------- */
add_action('woocommerce_process_product_meta', function ($post_id) {
    if (empty($_POST['bulk_discounts'])) return;

    $data = [];
    foreach ($_POST['bulk_discounts']['min_qty'] as $i => $min_qty) {
        if ($min_qty === '') continue;
        $data[] = [
            'min_qty' => (int) $min_qty,
            'discount' => (float) $_POST['bulk_discounts']['discount'][$i],
        ];
    }
    update_post_meta($post_id, '_bulk_discounts', $data);
});

/* --------------------------------------------------------------------------
| 3. Вывод скидок на фронте (flex)
|-------------------------------------------------------------------------- */
function wc_render_bulk_discount_table($product = null)
{
    if (!$product) global $product;
    if (!$product) return '';

    $discounts = get_post_meta($product->get_id(), '_bulk_discounts', true);
    if (!$discounts) $discounts = []; // на случай, если скидок нет

    usort($discounts, fn($a, $b) => $a['min_qty'] <=> $b['min_qty']);
    $base_price = (float) $product->get_price();
    if (!$base_price) return '';

    ob_start();
?>
    <div class="bulk-discount-flex">
        <h4>Скидки!</h4>

        <!-- Базовая цена за 1 шт -->
        <div class="bulk-discount-item">
            <span class="bulk-min-qty">От 1 шт.</span>
            <span class="bulk-price"><?php echo wc_price($base_price); ?>/шт.</span>
            <span class="bulk-discount">Розница</span>
        </div>

        <!-- Диапазоны скидок -->
        <?php foreach ($discounts as $row):
            $final = $base_price * (1 - $row['discount'] / 100); ?>
            <div class="bulk-discount-item">
                <span class="bulk-min-qty">От <?php echo esc_html($row['min_qty']); ?> шт.</span>
                <span class="bulk-price"><?php echo wc_price($final); ?>/шт.</span>
                <span class="bulk-discount _percent">- <?php echo esc_html($row['discount']); ?>%</span>
            </div>
        <?php endforeach; ?>
    </div>
<?php
    return ob_get_clean();
}

/* --------------------------------------------------------------------------
| 4. Шорткод
|-------------------------------------------------------------------------- */
add_shortcode('bulk_price_table', 'wc_render_bulk_discount_table');

/* --------------------------------------------------------------------------
| 5. Пересчет цены в корзине
|-------------------------------------------------------------------------- */
add_action('woocommerce_before_calculate_totals', function ($cart) {
    if (is_admin() && !defined('DOING_AJAX')) return;

    foreach ($cart->get_cart() as $cart_item) {
        $product = $cart_item['data'];
        $qty = $cart_item['quantity'];

        $discounts = get_post_meta($product->get_id(), '_bulk_discounts', true);
        if (!$discounts) continue;
        usort($discounts, fn($a, $b) => $a['min_qty'] <=> $b['min_qty']);

        $applicable = null;
        foreach ($discounts as $row) {
            if ($qty >= $row['min_qty']) $applicable = $row;
        }
        if (!$applicable) continue;

        $base_price = (float) $product->get_regular_price();
        if (!$base_price) $base_price = (float) $product->get_price();

        $final_price = $base_price * (1 - $applicable['discount'] / 100);
        $product->set_price($final_price);
    }
}, 20);

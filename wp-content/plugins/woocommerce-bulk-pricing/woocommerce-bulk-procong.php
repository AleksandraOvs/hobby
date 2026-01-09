<?php
/*
Plugin Name: WooCommerce Bulk Discount by Quantity
Description: Скидки по количеству товара (только проценты). Цена за единицу пересчитывается автоматически в корзине.
Version: 2.0
Author: Шурочка
*/

if (!defined('ABSPATH')) exit;

/*
|--------------------------------------------------------------------------
| 1. Админка: мета-блок "Скидки по количеству"
|--------------------------------------------------------------------------
*/

add_action('woocommerce_product_options_general_product_data', function () {
    global $post;

    echo '<div class="options_group">';
    echo '<h4>Скидки по количеству</h4>';
    echo '<p>Укажите минимальное количество и процент скидки. Верхняя граница не требуется.</p>';
?>

    <table class="widefat" id="bulk_discount_table">
        <thead>
            <tr>
                <th>От (шт)</th>
                <th>% Скидки</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $rows = get_post_meta($post->ID, '_bulk_discounts', true) ?: [];
            foreach ($rows as $row): ?>
                <tr>
                    <td>
                        <input type="number" min="1"
                            name="bulk_discounts[min_qty][]"
                            value="<?php echo esc_attr($row['min_qty']); ?>">
                    </td>
                    <td>
                        <input type="number" step="0.01" min="0"
                            name="bulk_discounts[discount][]"
                            value="<?php echo esc_attr($row['discount']); ?>">
                    </td>
                    <td>
                        <button type="button" class="button remove-row">Удалить</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button type="button" class="button" id="add_bulk_discount">Добавить диапазон</button>

    <script>
        jQuery(function($) {
            $('#add_bulk_discount').on('click', function() {
                $('#bulk_discount_table tbody').append(
                    '<tr>' +
                    '<td><input type="number" min="1" name="bulk_discounts[min_qty][]"></td>' +
                    '<td><input type="number" step="0.01" min="0" name="bulk_discounts[discount][]"></td>' +
                    '<td><button type="button" class="button remove-row">Удалить</button></td>' +
                    '</tr>'
                );
            });

            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
            });
        });
    </script>

<?php
    echo '</div>';
});

/*
|--------------------------------------------------------------------------
| 2. Сохранение мета-данных
|--------------------------------------------------------------------------
*/

add_action('woocommerce_process_product_meta', function ($post_id) {
    if (empty($_POST['bulk_discounts'])) return;

    $data = [];

    foreach ($_POST['bulk_discounts']['min_qty'] as $i => $min_qty) {
        if ($min_qty === '') continue;

        $data[] = [
            'min_qty'  => (int) $min_qty,
            'discount' => (float) $_POST['bulk_discounts']['discount'][$i],
        ];
    }

    update_post_meta($post_id, '_bulk_discounts', $data);
});

/*
|--------------------------------------------------------------------------
| 3. Таблица скидок (фронт, информационно)
|--------------------------------------------------------------------------
*/

function wc_render_bulk_discount_table($product = null)
{
    if (!$product) global $product;
    if (!$product) return '';

    $discounts = get_post_meta($product->get_id(), '_bulk_discounts', true);
    if (!$discounts) return '';

    usort($discounts, fn($a, $b) => $a['min_qty'] <=> $b['min_qty']);

    $base_price = (float) $product->get_price();
    if (!$base_price) return '';

    $html  = '<div class="bulk-discount-table">';
    $html .= '<h4>Скидки при покупке от количества</h4>';
    $html .= '<table>';
    $html .= '<thead><tr>';
    $html .= '<th>От (шт)</th><th>Цена за 1 шт</th><th>Скидка</th>';
    $html .= '</tr></thead><tbody>';

    foreach ($discounts as $row) {
        $final = $base_price * (1 - $row['discount'] / 100);

        $html .= '<tr>';
        $html .= '<td>' . esc_html($row['min_qty']) . '</td>';
        $html .= '<td><del>' . wc_price($base_price) . '</del> ' . wc_price($final) . '</td>';
        $html .= '<td>' . esc_html($row['discount']) . '%</td>';
        $html .= '</tr>';
    }

    $html .= '</tbody></table></div>';

    return $html;
}

/*
|--------------------------------------------------------------------------
| 4. Шорткод
|--------------------------------------------------------------------------
*/

add_shortcode('bulk_price_table', 'wc_render_bulk_discount_table');

/*
|--------------------------------------------------------------------------
| 5. Пересчет цены в корзине (КЛЮЧЕВОЕ МЕСТО)
|--------------------------------------------------------------------------
*/

add_action('woocommerce_before_calculate_totals', function ($cart) {
    if (is_admin() && !defined('DOING_AJAX')) return;

    foreach ($cart->get_cart() as $cart_item) {
        $product = $cart_item['data'];
        $qty     = $cart_item['quantity'];

        $discounts = get_post_meta($product->get_id(), '_bulk_discounts', true);
        if (!$discounts) continue;

        usort($discounts, fn($a, $b) => $a['min_qty'] <=> $b['min_qty']);

        $applicable = null;
        foreach ($discounts as $row) {
            if ($qty >= $row['min_qty']) {
                $applicable = $row;
            }
        }

        if (!$applicable) continue;

        // БАЗОВАЯ ЦЕНА ЗА 1 ШТУКУ
        $base_price = (float) $product->get_regular_price();
        if (!$base_price) {
            $base_price = (float) $product->get_price();
        }

        // ЦЕНА С УЧЁТОМ СКИДКИ (ЗА 1 ШТ)
        $final_price = $base_price * (1 - $applicable['discount'] / 100);

        // ❗️ НИКАКИХ УМНОЖЕНИЙ НА QTY
        $product->set_price($final_price);
    }
}, 20);

<?php
/*
Plugin Name: WooCommerce Bulk Discount by Quantity
Description: Скидки по количеству товара (только проценты). Цена за единицу пересчитывается автоматически в корзине.
Version: 2.3
Author: PurpleWeb
*/

if (!defined('ABSPATH')) exit;

/* --------------------------------------------------------------------------
| Подключение фронт/админ скриптов
|-------------------------------------------------------------------------- */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'wc-bulk-discount',
        plugin_dir_url(__FILE__) . 'assets/css/wc-bulk-discount.css',
        [],
        '1.0'
    );

    wp_enqueue_script(
        'wc-bulk-variation-front',
        plugin_dir_url(__FILE__) . 'assets/js/bulk-variation-front.js',
        ['jquery'],
        '1.0',
        true
    );

    wp_localize_script('wc-bulk-variation-front', 'wc_bulk_discount', [
        'ajax_url' => admin_url('admin-ajax.php')
    ]);
});

add_action('admin_enqueue_scripts', function ($hook) {
    if (!in_array($hook, ['post.php', 'post-new.php'])) return;

    global $post;
    if (!$post || $post->post_type !== 'product') return;

    wp_enqueue_script(
        'wc-bulk-variation-admin',
        plugin_dir_url(__FILE__) . 'assets/js/bulk-variation-admin.js',
        ['jquery'],
        '1.0',
        true
    );
});

/* --------------------------------------------------------------------------
| 1. Админка: метаблок "Скидки по количеству" (для продукта)
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
<?php
    echo '</div>';
});

/* --------------------------------------------------------------------------
| 1.1. Метаблок внутри вариации
|-------------------------------------------------------------------------- */
add_action('woocommerce_variation_options_pricing', function ($loop, $variation_data, $variation) {
    $variation_id = $variation->ID;
    $rows = get_post_meta($variation_id, '_bulk_discounts', true) ?: [];
?>
    <div class="form-row form-row-full">
        <strong>Скидки по количеству</strong>
        <div class="bulk_discount_container">
            <?php foreach ($rows as $row): ?>
                <div class="bulk-discount-row">
                    <input type="number" min="1"
                        name="bulk_discounts[<?php echo $variation_id; ?>][min_qty][]"
                        value="<?php echo esc_attr($row['min_qty']); ?>"
                        placeholder="Мин. кол-во">
                    <input type="number" step="0.01" min="0"
                        name="bulk_discounts[<?php echo $variation_id; ?>][discount][]"
                        value="<?php echo esc_attr($row['discount']); ?>"
                        placeholder="% Скидки">
                    <button type="button" class="button remove-row">✕</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button add-bulk-row">Добавить диапазон</button>
    </div>
<?php
}, 10, 3);

/* --------------------------------------------------------------------------
| 2. Сохранение мета-данных (для продукта и вариаций)
|-------------------------------------------------------------------------- */
function wc_save_bulk_discounts($post_id, $variation_id = null)
{
    $input_data = $variation_id
        ? ($_POST['bulk_discounts'][$variation_id] ?? [])
        : ($_POST['bulk_discounts'] ?? []);

    if (!$input_data) {
        if ($variation_id) delete_post_meta($variation_id, '_bulk_discounts');
        return;
    }

    $data = [];
    $min_qtys = $input_data['min_qty'] ?? [];
    $discounts = $input_data['discount'] ?? [];

    foreach ($min_qtys as $i => $min_qty) {
        if ($min_qty === '') continue;
        $data[] = [
            'min_qty' => (int) $min_qty,
            'discount' => (float) ($discounts[$i] ?? 0),
        ];
    }

    update_post_meta($variation_id ?: $post_id, '_bulk_discounts', $data);
}

add_action('woocommerce_process_product_meta', function ($post_id) {
    wc_save_bulk_discounts($post_id);
});

add_action('woocommerce_save_product_variation', function ($variation_id) {
    wc_save_bulk_discounts(null, $variation_id);
});

/* --------------------------------------------------------------------------
| 3. Вывод скидок на фронте
|-------------------------------------------------------------------------- */
function wc_render_bulk_discount_table($product = null)
{
    if (!$product) global $product;
    if (!$product) return '';

    $discounts = get_post_meta($product->get_id(), '_bulk_discounts', true);
    if (!$discounts) return '';

    usort($discounts, fn($a, $b) => $a['min_qty'] <=> $b['min_qty']);

    $base_price = (float) $product->get_price();
    if (!$base_price) return '';

    ob_start();
?>
    <div class="bulk-discount-flex">
        <h4>Скидки</h4>
        <?php foreach ($discounts as $row):
            $final = $base_price * (1 - $row['discount'] / 100); ?>
            <div class="bulk-discount-item">
                <span class="bulk-min-qty">От <?php echo esc_html($row['min_qty']); ?> шт.</span>
                <span class="bulk-price"><?php echo wc_price($final); ?>/шт.</span>
                <span class="bulk-discount">
                    <span class="_percent">-<?php echo esc_html($row['discount']); ?>%</span>
                </span>
            </div>
        <?php endforeach; ?>
    </div>
<?php
    return ob_get_clean();
}

add_shortcode('bulk_price_table', 'wc_render_bulk_discount_table');

/* --------------------------------------------------------------------------
| 4. Пересчет цены в корзине
|-------------------------------------------------------------------------- */
add_action('woocommerce_before_calculate_totals', function ($cart) {
    if (is_admin() && !defined('DOING_AJAX')) return;

    foreach ($cart->get_cart() as $cart_item) {
        $product = $cart_item['data'];
        $qty = $cart_item['quantity'];

        $target_id = $product->is_type('variation')
            ? $product->get_id()
            : ($product->get_parent_id() ?: $product->get_id());

        $discounts = get_post_meta($target_id, '_bulk_discounts', true);
        if (!$discounts) continue;

        usort($discounts, fn($a, $b) => $a['min_qty'] <=> $b['min_qty']);

        $applicable = null;
        foreach ($discounts as $row) {
            if ($qty >= $row['min_qty']) $applicable = $row;
        }

        if (!$applicable) continue;

        $base_price = (float) $product->get_regular_price();
        if (!$base_price) $base_price = (float) $product->get_price();

        $product->set_price($base_price * (1 - $applicable['discount'] / 100));
    }
}, 20);

/* --------------------------------------------------------------------------
| 5. AJAX для вариаций
|-------------------------------------------------------------------------- */
add_action('wp_ajax_get_bulk_discount_table', 'wc_ajax_bulk_discount_table');
add_action('wp_ajax_nopriv_get_bulk_discount_table', 'wc_ajax_bulk_discount_table');

function wc_ajax_bulk_discount_table()
{
    $variation_id = isset($_POST['variation_id']) ? absint($_POST['variation_id']) : 0;
    if (!$variation_id) wp_send_json_error();

    $variation = wc_get_product($variation_id);
    if (!$variation) wp_send_json_error();

    ob_start();
    echo wc_render_bulk_discount_table($variation);
    wp_send_json_success(ob_get_clean());
}

add_filter('woocommerce_available_variation', function ($data, $product, $variation) {

    $discounts = get_post_meta($variation->get_id(), '_bulk_discounts', true);

    if (!empty($discounts) && is_array($discounts)) {
        $data['bulk_discounts'] = $discounts;
    } else {
        $data['bulk_discounts'] = [];
    }

    return $data;
}, 10, 3);

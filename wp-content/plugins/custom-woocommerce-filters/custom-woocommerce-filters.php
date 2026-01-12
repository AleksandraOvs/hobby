<?php
/*
Plugin Name: Custom WooCommerce Filters
Description: AJAX фильтр товаров WooCommerce через шорткод [shop_filters] с jQuery UI Slider.
Version: 1.4
Author: PurpleWeb
*/

if (!defined('ABSPATH')) exit;

// ----------------------
// Подключение JS и CSS
// ----------------------
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script('jquery-ui-slider');
    wp_enqueue_style('jquery-ui-style', 'https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css');

    wp_enqueue_style('cwc-style', plugin_dir_url(__FILE__) . 'css/style.css');
    wp_enqueue_script('cwc-ajax-filters', plugin_dir_url(__FILE__) . 'js/ajax-filters.js', ['jquery', 'jquery-ui-slider'], '1.4', true);

    wp_localize_script('cwc-ajax-filters', 'cwc_ajax_object', [
        'ajax_url' => admin_url('admin-ajax.php')
    ]);
});

// ----------------------
// Получение минимальной и максимальной цены по магазину
// ----------------------
function cwc_get_store_price_range()
{
    $cached = get_transient('cwc_price_range');
    if ($cached !== false) {
        return $cached;
    }

    $all_product_ids = wc_get_products([
        'status' => 'publish',
        'limit'  => -1,
        'return' => 'ids',
    ]);

    $prices = [];

    foreach ($all_product_ids as $product_id) {
        $price = get_post_meta($product_id, '_price', true);
        if (is_numeric($price)) {
            $prices[] = (float) $price;
        }
    }

    $min_price = !empty($prices) ? floor(min($prices)) : 0;
    $max_price = !empty($prices) ? ceil(max($prices)) : 100000;

    $result = [$min_price, $max_price];

    // Кешируем на 12 часов
    set_transient('cwc_price_range', $result, 12 * HOUR_IN_SECONDS);

    return $result;
}

// ----------------------
// Фильтр атрибутов
// ----------------------
function cwc_render_attribute_filter($taxonomy, $title, $current_cat_id = 0)
{
    $terms = get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false]);
    if (empty($terms) || is_wp_error($terms)) return '';

    list($store_min_price, $store_max_price) = cwc_get_store_price_range();

    ob_start();
?>
    <div class="single-sidebar-wrap">
        <h3 class="sidebar-title"><?php echo esc_html($title); ?></h3>
        <div class="sidebar-body">
            <ul class="sidebar-list" data-taxonomy="<?php echo esc_attr($taxonomy); ?>">
                <?php foreach ($terms as $term) :
                    $count_args = [
                        'status' => 'publish',
                        'limit' => -1,
                        'tax_query' => [
                            [
                                'taxonomy' => $taxonomy,
                                'field'    => 'slug',
                                'terms'    => $term->slug,
                            ],
                        ],
                        'meta_query' => [
                            [
                                'key'     => '_price',
                                'value'   => [$store_min_price, $store_max_price],
                                'compare' => 'BETWEEN',
                                'type'    => 'NUMERIC',
                            ]
                        ],
                    ];

                    if ($current_cat_id) {
                        $count_args['tax_query'][] = [
                            'taxonomy' => 'product_cat',
                            'field'    => 'term_id',
                            'terms'    => $current_cat_id,
                        ];
                    }

                    $products = wc_get_products($count_args);
                    $count = count($products);
                ?>
                    <li>
                        <a href="#"
                            class="filter-item <?php echo (isset($_GET['filter_' . $taxonomy]) && $_GET['filter_' . $taxonomy] === $term->slug) ? 'active' : ''; ?>"
                            data-slug="<?php echo esc_attr($term->slug); ?>">
                            <span class="filter-checkbox"></span>
                            <?php echo esc_html($term->name); ?>(<?php echo $count; ?>)
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php
    return ob_get_clean();
}

// ----------------------
// Фильтр цены
// ----------------------
function cwc_render_price_filter()
{
    list($store_min_price, $store_max_price) = cwc_get_store_price_range();

    $min_price = isset($_GET['min_price']) ? intval($_GET['min_price']) : $store_min_price;
    $max_price = isset($_GET['max_price']) ? intval($_GET['max_price']) : $store_max_price;

    ob_start();
?>
    <div class="single-sidebar-wrap">
        <h3 class="sidebar-title">Цена</h3>
        <div class="sidebar-body">
            <div class="price-range-wrap">
                <div id="price-slider"
                    class="price-range"
                    data-min="<?php echo $store_min_price; ?>"
                    data-max="<?php echo $store_max_price; ?>"></div>

                <div class="range-inputs">
                    <div class="price-input">
                        <span class="price-prefix">От</span>
                        <input type="number"
                            id="min_price"
                            min="<?php echo $store_min_price; ?>"
                            max="<?php echo $store_max_price; ?>"
                            value="<?php echo $min_price; ?>">
                    </div>

                    <div class="price-input">
                        <span class="price-prefix">До</span>
                        <input type="number"
                            id="max_price"
                            min="<?php echo $store_min_price; ?>"
                            max="<?php echo $store_max_price; ?>"
                            value="<?php echo $max_price; ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
    return ob_get_clean();
}

// ----------------------
// Шорткод
// ----------------------
function cwc_shop_filters_shortcode()
{
    ob_start();

    $current_cat_id = 0;
    if (is_product_category()) {
        $current_cat = get_queried_object();
        if ($current_cat && isset($current_cat->term_id)) {
            $current_cat_id = $current_cat->term_id;
        }
    }
?>
    <div class="sidebar-area-wrapper" data-current-cat="<?php echo esc_attr($current_cat_id); ?>">

        <?php
        // цена
        echo cwc_render_price_filter();
        ?>

        <div class="single-sidebar-wrap">
            <div class="sidebar-body">
                <ul class="sidebar-list" data-taxonomy="instock_filter">
                    <li>
                        <a href="#" class="filter-item" data-slug="instock">
                            <span class="filter-checkbox"></span> Есть в наличии
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <?php
        // атрибуты
        echo cwc_render_attribute_filter('pa_material', 'Материал', $current_cat_id);
        echo cwc_render_attribute_filter('pa_strana', 'Страна', $current_cat_id);
        echo cwc_render_attribute_filter('pa_metod', 'Метод', $current_cat_id);
        ?>

        <div class="cwc-filter-actions">
            <button id="cwc-apply-filters" class="cwc-apply-button">Применить</button>
            <button id="cwc-reset-filters" class="cwc-reset-button">Сбросить</button>
        </div>
    </div>
<?php
    return ob_get_clean();
}
add_shortcode('shop_filters', 'cwc_shop_filters_shortcode');

// ----------------------
// AJAX обработчик
// ----------------------

add_action('wp_ajax_cwc_filter_products', 'cwc_filter_products');
add_action('wp_ajax_nopriv_cwc_filter_products', 'cwc_filter_products');

function cwc_filter_products()
{
    /* ------------------
     * Базовые аргументы
     * ------------------ */
    $args = [
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => 12,
        'tax_query'      => [],
        'meta_query'     => [],
    ];

    /* ------------------
     * Категория
     * ------------------ */
    if (!empty($_POST['current_cat_id'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'product_cat',
            'field'    => 'term_id',
            'terms'    => (int) $_POST['current_cat_id'],
        ];
    }

    /* ------------------
     * Сортировка WooCommerce (КЛЮЧЕВО)
     * ------------------ */
    $orderby = !empty($_POST['orderby'])
        ? wc_clean($_POST['orderby'])
        : '';

    // Получаем корректные аргументы сортировки
    $ordering_args = WC()->query->get_catalog_ordering_args($orderby);

    $args['orderby'] = $ordering_args['orderby'];
    $args['order']   = $ordering_args['order'];

    if (!empty($ordering_args['meta_key'])) {
        $args['meta_key'] = $ordering_args['meta_key'];
    }

    /* ------------------
     * Атрибуты
     * ------------------ */
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'filter_pa_') === 0 && !empty($value)) {
            $args['tax_query'][] = [
                'taxonomy' => str_replace('filter_', '', $key),
                'field'    => 'slug',
                'terms'    => wc_clean($value),
            ];
        }
    }

    if (count($args['tax_query']) > 1) {
        $args['tax_query']['relation'] = 'AND';
    }

    /* ------------------
     * Цена
     * ------------------ */
    list($store_min, $store_max) = cwc_get_store_price_range();

    $min_price = isset($_POST['min_price']) ? (int) $_POST['min_price'] : $store_min;
    $max_price = isset($_POST['max_price']) ? (int) $_POST['max_price'] : $store_max;

    $args['meta_query'][] = [
        'key'     => '_price',
        'value'   => [$min_price, $max_price],
        'compare' => 'BETWEEN',
        'type'    => 'NUMERIC',
    ];

    /* ------------------
     * Запрос товаров
     * ------------------ */
    $query = new WP_Query($args);

    ob_start();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'product');
        }
    } else {
        echo '<p class="no-products">Товары не найдены</p>';
    }

    wp_reset_postdata();

    wp_send_json_success([
        'html' => ob_get_clean(),
    ]);
}

<?php
/*
Plugin Name: Custom WooCommerce Filters (Auto Detect, Full Compatible)
Description: AJAX фильтр WooCommerce с авто-определением атрибутов (полная совместимость с исходной версткой)
Version: 2.2
Author: PurpleWeb
*/

if (!defined('ABSPATH')) exit;

/* ---------------------------------------------------
 * Подключение JS и CSS
 * --------------------------------------------------- */
add_action('wp_enqueue_scripts', function () {

    wp_enqueue_script('jquery-ui-slider');
    wp_enqueue_style(
        'jquery-ui-style',
        'https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css'
    );

    wp_enqueue_style(
        'cwc-style',
        plugin_dir_url(__FILE__) . 'css/style.css'
    );

    wp_enqueue_script(
        'cwc-scripts',
        plugin_dir_url(__FILE__) . 'js/scripts.js',
        'jquery',
        '1.1',
        true
    );

    wp_enqueue_script(
        'cwc-ajax-filters',
        plugin_dir_url(__FILE__) . 'js/ajax-filters.js',
        ['jquery', 'jquery-ui-slider'],
        '2.2',
        true
    );

    wp_localize_script('cwc-ajax-filters', 'cwc_ajax_object', [
        'ajax_url' => admin_url('admin-ajax.php')
    ]);
});

/* ---------------------------------------------------
 * Диапазон цен магазина и категорий
 * --------------------------------------------------- */
function cwc_get_category_price_range($category_id = 0)
{
    $args = [
        'status' => 'publish',
        'limit' => -1,
    ];

    if ($category_id) {
        $args['tax_query'] = [[
            'taxonomy' => 'product_cat',
            'field'    => 'term_id',
            'terms'    => $category_id,
        ]];
    }

    $products = wc_get_products($args);
    $prices = [];

    foreach ($products as $product) {
        if ($product->is_type('variable')) {
            $prices[] = (float)$product->get_variation_price('min', true);
            $prices[] = (float)$product->get_variation_price('max', true);
        } else {
            $prices[] = (float)$product->get_price();
        }
    }

    if (!$prices) {
        return [0, 100000];
    }

    return [
        floor(min($prices)),
        ceil(max($prices)),
    ];
}

// Диапазон цен всего магазина
function cwc_get_store_price_range()
{
    return cwc_get_category_price_range(0);
}

/* ---------------------------------------------------
 * Определение типа атрибута
 * --------------------------------------------------- */
function cwc_detect_attribute_type($taxonomy)
{
    $terms = get_terms([
        'taxonomy'   => $taxonomy,
        'hide_empty' => false,
        'number'     => 20,
    ]);

    if (is_wp_error($terms) || !$terms) {
        return 'text';
    }

    foreach ($terms as $term) {
        if (!is_numeric($term->name)) {
            return 'text';
        }
    }

    return 'numeric';
}

/* ---------------------------------------------------
 * Все атрибуты WooCommerce
 * --------------------------------------------------- */
function cwc_get_all_product_attributes()
{
    $taxes = wc_get_attribute_taxonomies();
    $out = [];

    foreach ($taxes as $tax) {
        $out[] = 'pa_' . $tax->attribute_name;
    }

    return $out;
}

/* ---------------------------------------------------
 * Очистка заголовка
 * --------------------------------------------------- */
function cwc_clean_title($title)
{
    return preg_replace('/^Товар\s*[:\-–—]?\s*/ui', '', $title);
}

/* ---------------------------------------------------
 * ТЕКСТОВЫЙ АТРИБУТ
 * --------------------------------------------------- */
function cwc_render_attribute_filter($taxonomy, $title, $current_cat_id = 0)
{
    $terms = get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false]);
    if (!$terms || is_wp_error($terms)) return '';

    list($store_min, $store_max) = cwc_get_store_price_range();

    // Отфильтруем термы, у которых нет товаров
    $filtered_terms = [];
    foreach ($terms as $term) {
        $args = [
            'status' => 'publish',
            'limit'  => -1,
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
                    'value'   => [$store_min, $store_max],
                    'compare' => 'BETWEEN',
                    'type'    => 'NUMERIC',
                ]
            ]
        ];

        if ($current_cat_id) {
            $args['tax_query'][] = [
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $current_cat_id,
            ];
        }

        $count = count(wc_get_products($args));

        if ($count > 0) {
            $term->count = $count; // добавим количество для вывода
            $filtered_terms[] = $term;
        }
    }

    if (!$filtered_terms) return ''; // нет товаров — не выводим блок

    ob_start(); ?>
    <div class="single-sidebar-wrap">
        <h4 class="sidebar-title"><?php echo esc_html(cwc_clean_title($title)); ?></h4>
        <div class="sidebar-body">
            <ul class="sidebar-list" data-taxonomy="<?php echo esc_attr($taxonomy); ?>">
                <?php foreach ($filtered_terms as $term): ?>
                    <li>
                        <a href="#" class="filter-item" data-slug="<?php echo esc_attr($term->slug); ?>">
                            <span class="filter-checkbox"></span>
                            <?php echo esc_html($term->name); ?> <?php //echo $term->count; 
                                                                    ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php
    return ob_get_clean();
}

/* ---------------------------------------------------
 * ЧИСЛОВОЙ АТРИБУТ
 * --------------------------------------------------- */
function cwc_render_numeric_attribute_filter($taxonomy, $title, $current_cat_id = 0)
{
    $values = [];
    $args = [
        'status' => 'publish',
        'limit'  => -1,
    ];

    if ($current_cat_id) {
        $args['tax_query'][] = [
            'taxonomy' => 'product_cat',
            'field'    => 'term_id',
            'terms'    => $current_cat_id,
        ];
    }

    foreach (wc_get_products($args) as $product) {
        $terms = wp_get_post_terms($product->get_id(), $taxonomy);
        foreach ($terms as $term) {
            if (is_numeric($term->name)) {
                $values[] = (float) $term->name;
            }
        }
    }

    if (!$values) {
        return '';
    }

    $min = floor(min($values));
    $max = ceil(max($values));

    ob_start(); ?>
    <div class="single-sidebar-wrap">
        <h4 class="sidebar-title"><?php echo esc_html(cwc_clean_title($title)); ?></h4>
        <div class="sidebar-body">
            <div class="range-inputs" data-taxonomy="<?php echo esc_attr($taxonomy); ?>">
                <div class="price-input">
                    <span class="price-prefix">От</span>
                    <input
                        type="number"
                        class="attr-min"
                        name="filter_<?php echo esc_attr($taxonomy); ?>_min"
                        min="<?php echo esc_attr($min); ?>"
                        max="<?php echo esc_attr($max); ?>"
                        value="<?php echo esc_attr($min); ?>">
                </div>
                <div class="price-input">
                    <span class="price-prefix">До</span>
                    <input
                        type="number"
                        class="attr-max"
                        name="filter_<?php echo esc_attr($taxonomy); ?>_max"
                        min="<?php echo esc_attr($min); ?>"
                        max="<?php echo esc_attr($max); ?>"
                        value="<?php echo esc_attr($max); ?>">
                </div>
            </div>
        </div>
    </div>
<?php
    return ob_get_clean();
}

/* ---------------------------------------------------
 * ФИЛЬТР ЦЕНЫ
 * --------------------------------------------------- */
function cwc_render_price_filter()
{
    $current_cat_id = is_product_category() ? get_queried_object_id() : 0;
    list($min, $max) = cwc_get_category_price_range($current_cat_id);

    ob_start(); ?>
    <div class="single-sidebar-wrap">
        <h4 class="sidebar-title">Цена</h4>
        <div class="sidebar-body">
            <div class="price-range-wrap">
                <div id="price-slider" class="price-range" data-min="<?php echo $min; ?>" data-max="<?php echo $max; ?>"></div>
                <div class="range-inputs">
                    <div class="price-input"><span class="price-prefix">От</span><input type="number" id="min_price" value="<?php echo $min; ?>"></div>
                    <div class="price-input"><span class="price-prefix">До</span><input type="number" id="max_price" value="<?php echo $max; ?>"></div>
                </div>
            </div>
        </div>
    </div>
<?php
    return ob_get_clean();
}

/* ---------------------------------------------------
 * ШОРТКОД
 * --------------------------------------------------- */
function cwc_shop_filters_shortcode()
{
    $current_cat_id = is_product_category() ? get_queried_object_id() : 0;

    $text_filters = [];
    $numeric_filters = [];

    foreach (cwc_get_all_product_attributes() as $taxonomy) {
        if (!taxonomy_exists($taxonomy)) continue;

        $tax = get_taxonomy($taxonomy);
        $title = $tax->label ?? $taxonomy;

        if (cwc_detect_attribute_type($taxonomy) === 'numeric') {
            $numeric_filters[] = cwc_render_numeric_attribute_filter($taxonomy, $title, $current_cat_id);
        } else {
            $text_filters[] = cwc_render_attribute_filter($taxonomy, $title, $current_cat_id);
        }
    }

    ob_start(); ?>
    <div class="sidebar-area-wrapper _filters" data-current-cat="<?php echo esc_attr($current_cat_id); ?>">
        <h3 class="filters-heading">Фильтры</h3>
        <div class="close-filters">
            <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6.8125 6.10156L12.2031 11.5L11.5 12.2031L6.10156 6.8125L0.703125 12.2031L0 11.5L5.39062 6.10156L0 0.703125L0.703125 0L6.10156 5.39062L11.5 0L12.2031 0.703125L6.8125 6.10156Z" fill="#564d49" />
            </svg>
        </div>
        <?php echo cwc_render_price_filter(); ?>

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
        echo implode('', $text_filters);
        echo implode('', $numeric_filters);
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

/* ---------------------------------------------------
 * AJAX: фильтрация товаров
 * --------------------------------------------------- */
function cwc_filter_products_callback()
{

    error_log('CWC POST: ' . print_r($_POST, true));
    if (!isset($_POST['action']) || $_POST['action'] !== 'cwc_filter_products') {
        wp_send_json_error('Неверный запрос');
    }

    $tax_query  = [];
    $meta_query = ['relation' => 'AND'];

    /* -------------------------
     * Атрибуты
     * ------------------------- */
    foreach ($_POST as $key => $value) {

        if (strpos($key, 'filter_') !== 0) continue;
        if ($key === 'filter_current_cat_id') continue;

        $taxonomy = str_replace('filter_', '', $key);

        // числовые атрибуты (meta)
        if (is_numeric($value)) {
            $meta_query[] = [
                'key'     => $taxonomy,
                'value'   => floatval($value),
                'compare' => '=',
                'type'    => 'NUMERIC',
            ];
        }
        // текстовые атрибуты (tax)
        else {
            $tax_query[] = [
                'taxonomy' => $taxonomy,
                'field'    => 'slug',
                'terms'    => sanitize_text_field($value),
            ];
        }
    }

    /* -------------------------
     * Цена (ПРАВИЛЬНО ДЛЯ ВАРИАЦИЙ)
     * ------------------------- */
    if (isset($_POST['min_price'], $_POST['max_price'])) {

        $min_price = floatval($_POST['min_price']);
        $max_price = floatval($_POST['max_price']);

        $meta_query[] = [
            'relation' => 'OR',

            // простые товары
            [
                'key'     => '_price',
                'value'   => [$min_price, $max_price],
                'compare' => 'BETWEEN',
                'type'    => 'NUMERIC',
            ],

            // вариативные: диапазоны пересекаются
            [
                'key'     => '_min_variation_price',
                'value'   => $max_price,
                'compare' => '<=',
                'type'    => 'NUMERIC',
            ],
            [
                'key'     => '_max_variation_price',
                'value'   => $min_price,
                'compare' => '>=',
                'type'    => 'NUMERIC',
            ],
        ];
    }

    /* -------------------------
     * Категория
     * ------------------------- */
    if (!empty($_POST['current_cat_id'])) {
        $tax_query[] = [
            'taxonomy' => 'product_cat',
            'field'    => 'term_id',
            'terms'    => intval($_POST['current_cat_id']),
        ];
    }

    /* -------------------------
     * WP_Query (ВМЕСТО wc_get_products)
     * ------------------------- */
    $query = new WP_Query([
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'tax_query'      => $tax_query ?: [],
        'meta_query'     => count($meta_query) > 1 ? $meta_query : [],
    ]);

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
        'html' => ob_get_clean()
    ]);
}

add_action('wp_ajax_cwc_filter_products', 'cwc_filter_products_callback');
add_action('wp_ajax_nopriv_cwc_filter_products', 'cwc_filter_products_callback');

<?php
defined('ABSPATH') || exit;

/**
 * Sidebar shop filters
 */

// базовый URL магазина
$shop_page_url = get_permalink(wc_get_page_id('shop'));

// сохраняем диапазон цен
if (!empty($_GET['min_price'])) {
    $shop_page_url = add_query_arg('min_price', intval($_GET['min_price']), $shop_page_url);
}
if (!empty($_GET['max_price'])) {
    $shop_page_url = add_query_arg('max_price', intval($_GET['max_price']), $shop_page_url);
}

/**
 * Универсальный вывод фильтра по атрибуту
 */
function render_attribute_filter($taxonomy, $title, $shop_page_url)
{
    $terms = get_terms([
        'taxonomy'   => $taxonomy,
        'hide_empty' => false,
    ]);

    if (empty($terms) || is_wp_error($terms)) {
        return;
    }
?>
    <div class="single-sidebar-wrap">
        <h3 class="sidebar-title"><?php echo esc_html($title); ?></h3>
        <div class="sidebar-body">
            <ul class="sidebar-list">
                <?php foreach ($terms as $term) :

                    $current_url = $shop_page_url;

                    // сохраняем другие выбранные атрибуты
                    foreach ($_GET as $key => $value) {
                        if (
                            strpos($key, 'filter_') === 0 &&
                            $key !== 'filter_' . $taxonomy
                        ) {
                            $current_url = add_query_arg(
                                sanitize_key($key),
                                sanitize_text_field($value),
                                $current_url
                            );
                        }
                    }

                    // добавляем текущий атрибут
                    $current_url = add_query_arg(
                        'filter_' . $taxonomy,
                        $term->slug,
                        $current_url
                    );

                    $active = (
                        isset($_GET['filter_' . $taxonomy]) &&
                        $_GET['filter_' . $taxonomy] === $term->slug
                    );
                ?>
                    <li>
                        <a href="<?php echo esc_url($current_url); ?>"
                            class="<?php echo $active ? 'active' : ''; ?>">
                            <?php echo esc_html($term->name); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php
}
?>

<div class="sidebar-area-wrapper">

    <!-- Атрибуты -->
    <?php
    render_attribute_filter('pa_material', 'Материал', $shop_page_url);
    render_attribute_filter('pa_strana', 'Страна', $shop_page_url);
    render_attribute_filter('pa_metod', 'Метод', $shop_page_url);
    ?>

    <!-- Цена -->
    <div class="single-sidebar-wrap">
        <h3 class="sidebar-title">Цена</h3>
        <div class="sidebar-body">
            <div class="price-range-wrap">
                <div
                    class="price-range"
                    data-min="10"
                    data-max="100000">
                </div>

                <div class="range-slider">
                    <form
                        action="<?php echo esc_url($shop_page_url); ?>"
                        method="GET"
                        id="price_filter">

                        <?php
                        // сохраняем выбранные атрибуты
                        foreach ($_GET as $key => $value) :
                            if (strpos($key, 'filter_') === 0) : ?>
                                <input type="hidden"
                                    name="<?php echo esc_attr($key); ?>"
                                    value="<?php echo esc_attr($value); ?>">
                        <?php endif;
                        endforeach; ?>

                        <label for="amount">Цена:</label>
                        <input type="text" id="amount" readonly />

                        <input type="hidden"
                            id="min_price"
                            name="min_price"
                            value="<?php echo isset($_GET['min_price']) ? intval($_GET['min_price']) : 10; ?>">

                        <input type="hidden"
                            id="max_price"
                            name="max_price"
                            value="<?php echo isset($_GET['max_price']) ? intval($_GET['max_price']) : 100000; ?>">
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
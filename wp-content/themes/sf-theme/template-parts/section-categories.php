<?php
// ===============================
// Получаем родительскую группу
// ===============================
$group = get_field('products_categories');

if (!$group) {
    return; // Если родительская группа не заполнена, не выводим шаблон
}

// ===============================
// Категории товаров
// ===============================
$categories_field = $group['product_categories'] ?? [];
$categories = is_array($categories_field) ? $categories_field : [];

// ===============================
// Группа "Смотреть всё"
// ===============================
$show_all_group = $group['show_all'] ?? [];
$show_all_text       = $show_all_group['show_all_text'] ?? '';
$show_all_link_field = $show_all_group['show_all_link'] ?? '';
$show_all_img_field  = $show_all_group['show_all_img'] ?? '';

// ===============================
// Плейсхолдер WooCommerce
// ===============================
$placeholder = function_exists('wc_placeholder_img_src')
    ? wc_placeholder_img_src()
    : get_template_directory_uri() . '/assets/img/placeholder.jpg';

// ===============================
// Ссылка "Смотреть всё" (ACF Link / URL)
// ===============================
$show_all_link = is_array($show_all_link_field)
    ? ($show_all_link_field['url'] ?? '')
    : $show_all_link_field;

// ===============================
// Изображение "Смотреть всё" (ACF Image)
// ===============================
if (is_array($show_all_img_field)) {
    $show_all_image = $show_all_img_field['sizes']['full']
        ?? $show_all_img_field['url']
        ?? $placeholder;
} elseif (is_int($show_all_img_field)) {
    $show_all_image = wp_get_attachment_image_url($show_all_img_field, 'full');
} else {
    $show_all_image = $placeholder;
}
?>

<?php if (!empty($categories)) : ?>
    <section class="section-categories">
        <div class="container">
            <h2>Ассортимент</h2>

            <ul class="product-categories">

                <?php foreach ($categories as $category) :

                    // Только категории WooCommerce
                    if ($category->taxonomy !== 'product_cat') {
                        continue;
                    }

                    $link = get_term_link($category);
                    if (is_wp_error($link)) {
                        continue;
                    }

                    $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);

                    $image_url = $thumbnail_id
                        ? wp_get_attachment_image_url($thumbnail_id, 'full')
                        : $placeholder;
                ?>
                    <li class="product-category">
                        <a href="<?php echo esc_url($link); ?>">
                            <div class="product-category__image">
                                <img src="<?php echo esc_url($image_url); ?>"
                                    alt="<?php echo esc_attr($category->name); ?>"
                                    loading="lazy">
                            </div>

                            <span class="product-category__title">
                                <?php echo esc_html($category->name); ?>
                            </span>
                        </a>
                    </li>
                <?php endforeach; ?>

                <?php if ($show_all_text || $show_all_link) : ?>
                    <li class="product-category product-category--show-all">
                        <a href="<?php echo esc_url($show_all_link ?: '#'); ?>">
                            <div class="product-category__image">
                                <img src="<?php echo esc_url($show_all_image); ?>"
                                    alt="<?php echo esc_attr($show_all_text); ?>"
                                    loading="lazy">
                            </div>

                            <span class="product-category__title">
                                <?php echo esc_html($show_all_text ?: 'Смотреть все'); ?>
                            </span>
                        </a>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </section>
<?php endif; ?>
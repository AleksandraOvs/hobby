<?php
$offset = $args['offset'] ?? 0;
$limit  = $args['limit'] ?? 8;

$parents = get_terms([
    'taxonomy'   => 'product_cat',
    'parent'     => 0,
    'hide_empty' => false,
    'number'     => $limit,
    'offset'     => $offset,
]);

foreach ($parents as $parent) :

    $children = get_terms([
        'taxonomy'   => 'product_cat',
        'parent'     => $parent->term_id,
        'hide_empty' => false,
    ]);

    if (!$children) continue;

    $total_children = count($children);
?>

    <div class="categories-list__item">

        <!-- Заголовок основной категории -->
        <h2 class="parent-category-title">
            <a href="<?php echo get_term_link($parent); ?>">
                <?php echo esc_html($parent->name); ?>
            </a>
        </h2>

        <!-- Подкатегории -->
        <div class="subcategory-grid">

            <?php foreach ($children as $index => $child) :

                $thumb_id = get_term_meta($child->term_id, 'thumbnail_id', true);
                $image    = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'medium') : '';

                // скрываем всё, что после первых 8
                $hidden_class = $index >= 8 ? ' is-hidden' : '';
            ?>

                <div class="subcategory-item<?php echo $hidden_class; ?>">

                    <a href="<?php echo get_term_link($child); ?>" class="subcategory-image">
                        <?php if ($image) { ?>
                            <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_html($child->name); ?>">
                        <?php } else {
                        ?>
                            <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/svg/placeholder.svg" alt="<?php echo esc_html($child->name); ?>">
                        <?php
                        } ?>
                    </a>

                    <div class="subcategory-title">
                        <a href="<?php echo get_term_link($child); ?>">
                            <?php echo esc_html($child->name); ?>
                        </a>
                        <span class="subcategory-count">
                            (<?php echo intval($child->count); ?>)
                        </span>
                    </div>

                </div>

            <?php endforeach; ?>

        </div>

        <?php if ($total_children > 8): ?>
            <button class="load-more-subcats" data-step="8">
                <svg width="14" height="23" viewBox="0 0 14 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M11.1652 14.4953C11.5893 14.0474 12.2979 14.0277 12.7458 14.4518C13.1937 14.8755 13.2129 15.5841 12.7893 16.032L7.36374 21.7746C6.93968 22.224 6.22837 22.2467 5.77898 21.8226C3.94705 19.9907 2.09584 17.9237 0.305852 16.032C-0.118211 15.5841 -0.0989344 14.8755 0.34894 14.4518C0.796814 14.0277 1.50547 14.0474 1.92954 14.4953L6.54736 19.3739L11.1652 14.4953Z" fill="#674126" />
                    <path d="M5.5095 1.11458C5.51101 0.49738 6.01558 -0.00150789 6.63278 3.95061e-06C7.24998 0.00151579 7.7485 0.50645 7.74736 1.12327L7.66648 21.0108C7.66497 21.628 7.16002 22.1269 6.54283 22.1254C5.92563 22.1238 5.42711 21.6189 5.42862 21.0017L5.5095 1.11458Z" fill="#674126" />
                </svg>

                Показать ещё
            </button>
        <?php endif; ?>

    </div>

<?php endforeach; ?>
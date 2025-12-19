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
                Показать ещё
            </button>
        <?php endif; ?>

    </div>

<?php endforeach; ?>
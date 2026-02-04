<div class="woo-page__content__sidebar-top">
    <?php
    // определяем текущую категорию
    $current_term = null;

    if (is_product_category()) {
        $current_term = get_queried_object();
    }

    // если мы в категории — берём её ID, иначе можно оставить 0 или вообще не выводить
    $parent_id = $current_term ? $current_term->term_id : 0;

    $product_categories = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'parent'     => $parent_id, // ТОЛЬКО прямые потомки текущей категории
    ]);
    ?>

    <?php if (!empty($product_categories) && !is_wp_error($product_categories)) : ?>
        <div class="single-sidebar-wrap">
            <div class="sidebar-body">
                <ul class="cats-list">
                    <?php foreach ($product_categories as $product_category) : ?>
                        <li>
                            <a href="<?php echo esc_url(get_term_link($product_category)); ?>">
                                <?php echo esc_html($product_category->name); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>
</div>
<div class="woo-page__content__sidebar-top">
    <?php
    // показываем только на странице магазина
    if (is_shop()) :

        $product_categories = get_terms([
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
            'parent'     => 0, // ТОЛЬКО основные категории
        ]);
    ?>

        <?php if (!empty($product_categories) && !is_wp_error($product_categories)) : ?>

            <div class="single-sidebar-wrap">
                <div class="sidebar-body">
                    <ul class="sidebar-list">
                        <?php foreach ($product_categories as $product_category) : ?>
                            <li>
                                <a href="<?php echo esc_url(get_term_link($product_category)); ?>">
                                    <?php echo esc_html($product_category->name); ?>
                                    <!-- <span><?php //echo intval($product_category->count); 
                                                ?></span> -->
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

        <?php endif; ?>

    <?php endif; ?>
</div>
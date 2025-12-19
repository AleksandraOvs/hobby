<div class="categories-menu">
    <?php
    $button_svg = '<svg width="20" height="12" viewBox="0 0 20 12" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M17.2626 0.469778C17.8276 -0.130789 18.7755 -0.159138 19.3761 0.405523C19.9771 0.970184 20.0054 1.91847 19.4407 2.51904L11.012 11.4346C10.4474 12.0351 9.49907 12.0635 8.8985 11.4988C6.02719 8.62751 3.20804 5.48332 0.405525 2.51904C-0.159136 1.91847 -0.130792 0.970184 0.469775 0.405523C1.07072 -0.159138 2.01863 -0.130789 2.58329 0.469778L9.92313 8.2333L17.2626 0.469778Z" fill="white"/>
</svg>';
    ?>

    <button class="btn toggle-cat-menu-button"></span>Каталог</span> <?php echo $button_svg ?> </button>
    <?php echo '<div class="menu-catalog__container" id="menu-catalog__container">'; ?>

    <!-- categories output -->

    <?php
    function render_product_categories_menu($parent_id = 0, $level = 0)
    {

        $terms = get_terms([
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
            'parent'     => $parent_id,
        ]);

        if (empty($terms) || is_wp_error($terms)) {
            return;
        }

        // Класс для вложенных списков
        $ul_class = $level === 0
            ? 'menu'
            : 'dropdown-menu level-' . $level;

        // id только для корневого ul
        $ul_id = $level === 0 ? ' id="menu-catalog"' : '';

        echo '<ul' . $ul_id . ' class="' . esc_attr($ul_class) . '">';

        foreach ($terms as $term) {

            // Проверяем, есть ли дети
            $children = get_terms([
                'taxonomy'   => 'product_cat',
                'hide_empty' => false,
                'parent'     => $term->term_id,
                'number'     => 1,
            ]);

            $has_children = !empty($children);

            $li_classes = ['menu-item'];
            if ($has_children) {
                $li_classes[] = 'menu-item-has-children';
            }

            echo '<li class="' . esc_attr(implode(' ', $li_classes)) . '">';

            echo '<a href="' . esc_url(get_term_link($term)) . '">';
            echo esc_html($term->name);
            echo '</a>';

            // Дочерние категории — на том же уровне, сразу после <a>
            if ($has_children) {
                render_product_categories_menu($term->term_id, $level + 1);
            }

            echo '</li>';
        }

        echo '</ul>';
    }
    ?>

    <div class="menu-catalog__container">
        <?php render_product_categories_menu(); ?>
    </div>
    <!-- /categories output -->
    <a href="/shop" class="btn shop-btn">Все товары</a>

    <?php echo '</div>'; ?>
</div>
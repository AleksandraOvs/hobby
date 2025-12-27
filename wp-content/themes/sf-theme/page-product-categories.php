<?php
/*
Template Name: Категории товаров
*/
get_header() ?>

<section class="page">
    <?php get_template_part('template-parts/elements/page-header'); ?>
    <div class="page-content">
        <div class="container">
            <h1>Каталог</h1>

            <div class="product-categories">


                <nav id="catalog-navigation" class="catalog-navigation">
                    <?php //wp_nav_menu([
                    //'container' => false,
                    //'theme_location'  => 'cat_menu',
                    //'walker' => new My_Custom_Walker_Nav_Menu,
                    //'depth'           => 0,
                    //]); 
                    ?>

                    <?php
                    $terms = get_terms([
                        'taxonomy'   => 'product_cat',
                        'hide_empty' => false,
                        'parent'     => 0,
                    ]);

                    if (empty($terms) || is_wp_error($terms)) {
                        return;
                    }

                    echo '<ul class="menu">';

                    foreach ($terms as $term) {

                        // Проверяем, есть ли дети
                        $children = get_terms([
                            'taxonomy'   => 'product_cat',
                            'hide_empty' => false,
                            'number'     => 1,
                        ]);


                        $li_classes = ['menu-item'];

                        echo '<li class="' . esc_attr(implode(' ', $li_classes)) . '">';

                        // ======= Получаем иконку через ACF ======= //
                        $icon_url = '';

                        $icon_id = get_field('category_icon', 'product_cat_' . $term->term_id);
                        if ($icon_id) {
                            $icon_url = wp_get_attachment_image_url($icon_id, 'thumbnail');
                        }


                        echo '<a href="' . esc_url(get_term_link($term)) . '">';
                        if ($icon_url) {
                            echo '<img class="cat-icon" src="' . esc_url($icon_url) . '" alt="' . esc_attr($term->name) . '">';
                        }
                        echo esc_html($term->name);
                        echo '</a>';

                        echo '</li>';
                    }

                    echo '</ul>';


                    ?>

                </nav><!-- #site-navigation -->


                <div id="categories-list"
                    data-offset="0"
                    data-limit="8">
                    <?php
                    // первый вывод
                    get_template_part('template-parts/product', 'categories', [
                        'offset' => 0,
                        'limit'  => 8
                    ]);
                    ?>

                    <?php get_template_part('template-parts/banners-catalog') ?>
                </div>

                <!-- <button id="load-more-categories">Показать ещё</button> -->

            </div>

        </div>
    </div>
</section>

<?php get_template_part('template-parts/section-contacts') ?>

<?php get_footer() ?>
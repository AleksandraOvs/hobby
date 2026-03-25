<?php
function site_breadcrumbs()
{
    global $post;

    if (!$post || !($post instanceof WP_Post)) {
        return;
    }

    echo '<ul class="breadcrumbs__list">';

    $page_num = get_query_var('paged') ? get_query_var('paged') : 1;

    $separator = ' <svg width="4" height="8" viewBox="0 0 4 8" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M2.66823 4.06847L-2.62371e-07 1.40024L0.665885 0.73436L4 4.06847L0.665885 7.40259L-2.91067e-08 6.7367L2.66823 4.06847Z" fill="#fff"/>
    </svg> ';

    // Главная
    if (is_front_page()) {
        if ($page_num > 1) {
            echo '<a class="home-link" href="' . site_url() . '">Главная</a>' . $separator . $page_num . '-page';
        } else {
            echo 'Вы находитесь на главной странице';
        }
        echo '</ul>';
        return;
    }

    echo '<a class="home-link" href="' . site_url() . '">Главная</a>' . $separator;

    // ======================
    // SINGULAR (пост / страница / товар)
    // ======================
    if (is_singular()) {

        $post_id = $post->ID;
        $post_type = get_post_type($post_id);
        $post_type_obj = get_post_type_object($post_type);

        // CPT архив
        if (
            $post_type !== 'post'
            && $post_type_obj instanceof WP_Post_Type
            && !empty($post_type_obj->has_archive)
        ) {
            echo '<a href="' . get_post_type_archive_link($post_type) . '">'
                . esc_html($post_type_obj->labels->name)
                . '</a>' . $separator;
        }

        // ===== POSTS =====
        if ($post_type === 'post') {

            $primary_cat = null;

            if (class_exists('WPSEO_Primary_Term')) {
                $wpseo_primary_term = new WPSEO_Primary_Term('category', $post_id);
                $primary_cat_id = $wpseo_primary_term->get_primary_term();
                if ($primary_cat_id) {
                    $primary_cat = get_category($primary_cat_id);
                }
            }

            if (!$primary_cat) {
                $categories = get_the_category($post_id);
                if (!empty($categories)) {
                    $primary_cat = $categories[0];
                }
            }

            if ($primary_cat) {
                $parents = get_ancestors($primary_cat->term_id, 'category');
                $parents = array_reverse($parents);

                foreach ($parents as $parent_id) {
                    $parent = get_category($parent_id);
                    echo '<a href="' . get_category_link($parent->term_id) . '">'
                        . esc_html($parent->name) . '</a>' . $separator;
                }

                echo '<a href="' . get_category_link($primary_cat->term_id) . '">'
                    . esc_html($primary_cat->name) . '</a>' . $separator;
            }
        }

        // ===== PRODUCT =====
        if ($post_type === 'product') {
            $terms = get_the_terms($post_id, 'product_cat');

            if ($terms && !is_wp_error($terms)) {
                $term = $terms[0];
                $ancestors = get_ancestors($term->term_id, 'product_cat');
                $ancestors = array_reverse($ancestors);

                foreach ($ancestors as $ancestor_id) {
                    $ancestor = get_term($ancestor_id, 'product_cat');
                    echo '<a href="' . get_term_link($ancestor) . '">'
                        . esc_html($ancestor->name) . '</a>' . $separator;
                }

                echo '<a href="' . get_term_link($term) . '">'
                    . esc_html($term->name) . '</a>' . $separator;
            }
        }

        // ===== CUSTOM TAX =====
        $taxonomies = get_object_taxonomies($post_type);

        foreach ($taxonomies as $taxonomy) {
            if (in_array($taxonomy, ['category', 'post_tag', 'product_cat'])) continue;

            $terms = get_the_terms($post_id, $taxonomy);

            if (!empty($terms) && !is_wp_error($terms)) {
                $term = $terms[0];

                echo '<a href="' . get_term_link($term) . '">'
                    . esc_html($term->name) . '</a>' . $separator;
            }
        }

        // ТЕКУЩИЙ ЗАГОЛОВОК (БЕЗ the_title!)
        echo '<span>' . esc_html(get_the_title($post_id)) . '</span>';
    }

    // ======================
    // TAXONOMY
    // ======================
    elseif (is_category() || is_tax() || is_product_category()) {

        $term = get_queried_object();

        if ($term && !is_wp_error($term)) {

            $taxonomy = get_taxonomy($term->taxonomy);

            if ($taxonomy && !empty($taxonomy->object_type)) {
                $post_type = $taxonomy->object_type[0];
                $post_type_obj = get_post_type_object($post_type);

                if (
                    $post_type_obj instanceof WP_Post_Type
                    && !empty($post_type_obj->has_archive)
                    && $term->taxonomy !== 'category'
                ) {
                    echo '<a href="' . get_post_type_archive_link($post_type) . '">'
                        . esc_html($post_type_obj->labels->name)
                        . '</a>' . $separator;
                }
            }

            $ancestors = get_ancestors($term->term_id, $term->taxonomy);
            $ancestors = array_reverse($ancestors);

            foreach ($ancestors as $ancestor_id) {
                $ancestor = get_term($ancestor_id, $term->taxonomy);
                echo '<a href="' . get_term_link($ancestor) . '">'
                    . esc_html($ancestor->name) . '</a>' . $separator;
            }

            echo '<span>' . esc_html($term->name) . '</span>';
        }
    }

    // ======================
    // ARCHIVES
    // ======================
    elseif (is_post_type_archive()) {
        $post_type = get_post_type();
        $obj = get_post_type_object($post_type);
        echo esc_html($obj->labels->name);
    } elseif (is_page()) {
        echo '<span>' . esc_html(get_the_title($post->ID)) . '</span>';
    } elseif (is_tag()) {
        echo '<span>' . single_tag_title('', false) . '</span>';
    } elseif (is_404()) {
        echo 'Ошибка 404';
    }

    if ($page_num > 1) {
        echo ' (' . $page_num . '-page)';
    }

    echo '</ul>';
}

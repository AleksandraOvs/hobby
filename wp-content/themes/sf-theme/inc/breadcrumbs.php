<?php

// Функция для вывода цепочки категорий с родителями
function get_category_breadcrumbs($category)
{
    echo '<ul class="breadcrumbs__list">';
    $separator = ' <svg width="4" height="8" viewBox="0 0 4 8" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M2.66823 4.06847L-2.62371e-07 1.40024L0.665885 0.73436L4 4.06847L0.665885 7.40259L-2.91067e-08 6.7367L2.66823 4.06847Z" fill="#fff"/>
    </svg> ';

    $parents = get_ancestors($category->term_id, 'category'); // массив ID родителей
    $parents = array_reverse($parents); // сверху вниз

    foreach ($parents as $parent_id) {
        $parent = get_category($parent_id);
        echo '<a href="' . get_category_link($parent->term_id) . '">' . esc_html($parent->name) . '</a>' . $separator;
    }

    // текущая категория
    echo '<a href="' . get_category_link($category->term_id) . '">' . esc_html($category->name) . '</a>' . $separator;
    echo '</ul>';
}

// Основная функция хлебных крошек
function site_breadcrumbs()
{
    echo '<ul class="breadcrumbs__list">';
    $page_num = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $separator = ' <svg width="4" height="8" viewBox="0 0 4 8" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M2.66823 4.06847L-2.62371e-07 1.40024L0.665885 0.73436L4 4.06847L0.665885 7.40259L-2.91067e-08 6.7367L2.66823 4.06847Z" fill="#fff"/>
    </svg> ';

    if (is_front_page()) {
        if ($page_num > 1) {
            echo '<a class="home-link" href="' . site_url() . '">Главная </a>' . $separator . $page_num . '-page';
        } else {
            echo 'Вы находитесь на главной странице';
        }
        return;
    }

    // Начало хлебных крошек: ссылка на главную
    echo '<a class="home-link" href="' . site_url() . '">Главная</a>' . $separator;

    if (is_singular()) {
        $post_type = get_post_type();
        $post_type_obj = get_post_type_object($post_type);

        // CPT с архивом
        if ($post_type !== 'post' && $post_type_obj && $post_type_obj->has_archive) {
            echo '<a href="' . get_post_type_archive_link($post_type) . '">' . esc_html($post_type_obj->labels->name) . '</a>' . $separator;
        }

        // Для обычных постов: Primary Category + иерархия
        if ($post_type === 'post') {
            $primary_cat = null;

            if (class_exists('WPSEO_Primary_Term')) {
                $wpseo_primary_term = new WPSEO_Primary_Term('category', get_the_ID());
                $primary_cat_id = $wpseo_primary_term->get_primary_term();
                if ($primary_cat_id) {
                    $primary_cat = get_category($primary_cat_id);
                }
            }

            if (!$primary_cat) {
                $categories = get_the_category();
                if (!empty($categories)) {
                    $primary_cat = $categories[0];
                }
            }

            if ($primary_cat) {
                get_category_breadcrumbs($primary_cat);
            }
        }

        // Кастомные таксономии
        $taxonomies = get_object_taxonomies($post_type);
        foreach ($taxonomies as $taxonomy) {
            if ($taxonomy === 'category' || $taxonomy === 'post_tag') continue;
            $terms = get_the_terms(get_the_ID(), $taxonomy);
            if (!empty($terms) && !is_wp_error($terms)) {
                $first_term = $terms[0];
                echo '<a href="' . get_term_link($first_term) . '">' . esc_html($first_term->name) . '</a>' . $separator;
            }
        }

        the_title();
    } elseif (is_category() || is_tax()) {
        $term = get_queried_object();
        if ($term) {
            // Для таксономий с CPT выводим архив
            $taxonomy = get_taxonomy($term->taxonomy);
            if (!empty($taxonomy->object_type)) {
                $post_type = $taxonomy->object_type[0];
                $post_type_obj = get_post_type_object($post_type);
                if ($post_type_obj && $post_type_obj->has_archive && $term->taxonomy !== 'category') {
                    echo '<a href="' . get_post_type_archive_link($post_type) . '">' . esc_html($post_type_obj->labels->name) . '</a>' . $separator;
                }
            }

            if ($term->taxonomy === 'category') {
                get_category_breadcrumbs($term);
            } else {
                echo '<a href="' . get_term_link($term) . '">' . esc_html($term->name) . '</a>' . $separator;
            }
        }
    } elseif (is_post_type_archive()) {
        $post_type = get_post_type();
        $post_type_obj = get_post_type_object($post_type);
        echo esc_html($post_type_obj->labels->name);
    } elseif (is_page()) {
        the_title();
    } elseif (is_tag()) {
        single_tag_title();
    } elseif (is_day()) {
        echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $separator;
        echo '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a>' . $separator;
        echo get_the_time('d');
    } elseif (is_month()) {
        echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $separator;
        echo get_the_time('F');
    } elseif (is_year()) {
        echo get_the_time('Y');
    } elseif (is_author()) {
        $userdata = get_userdata(get_query_var('author'));
        echo 'Опубликовал(а) ' . esc_html($userdata->display_name);
    } elseif (is_404()) {
        echo 'Ошибка 404';
    }

    if ($page_num > 1) {
        echo ' (' . $page_num . '-page)';
    }
    echo '</ul>';
}

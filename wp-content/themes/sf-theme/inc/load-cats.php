<?php

/**
 * AJAX подгрузка категорий на странице /catalog
 */
add_action('wp_ajax_load_more_categories', 'load_more_categories');
add_action('wp_ajax_nopriv_load_more_categories', 'load_more_categories');

function load_more_categories()
{

    $offset = intval($_POST['offset']);
    $limit  = intval($_POST['limit']);

    get_template_part('template-parts/product', 'categories', [
        'offset' => $offset,
        'limit'  => $limit
    ]);

    wp_die();
}

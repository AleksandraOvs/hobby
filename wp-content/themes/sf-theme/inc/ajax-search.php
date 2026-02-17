<?php
add_action('wp_ajax_ajax_product_search', 'ajax_product_search');
add_action('wp_ajax_nopriv_ajax_product_search', 'ajax_product_search');

function ajax_product_search()
{

    $search_term = sanitize_text_field($_POST['term']);

    if (empty($search_term)) {
        wp_die();
    }

    $args = [
        'post_type'      => 'product',
        'posts_per_page' => 5,
        's'              => $search_term,
    ];

    $query = new WP_Query($args);

    $total = (int) $query->found_posts;

    // правильная ссылка на страницу поиска товаров
    $search_url = add_query_arg(
        [
            's' => $search_term,
            'post_type' => 'product'
        ],
        home_url('/')
    );


    $query = new WP_Query($args);

    if ($query->have_posts()) {
        echo '<ul class="search-results-list">';
        while ($query->have_posts()) {
            $query->the_post();
            global $product;

?>

            <li class="search-item">
                <a href="<?php the_permalink() ?>">
                    <?php echo get_the_post_thumbnail(get_the_ID(), 'thumbnail'); ?>
                    <div class="search-item__main">
                        <span class="title"><?php the_title() ?></span>
                        <span class="price"><?php echo $product->get_price_html() ?></span>
                    </div>
                </a>
            </li>



        <?php

            // echo '<li class="search-item">';
            // echo '<a href="' . get_permalink() . '">';
            // echo get_the_post_thumbnail(get_the_ID(), 'thumbnail');
            // echo '';
            // echo '<span class="price">' . $product->get_price_html() . '</span>';
            // echo '</a>';
            // echo '</li>';
        }
        echo '</ul>';
        ?>
        <div class="results-footer">
            <div class="results-count">
                Найдено: <?php echo $total; ?>
            </div>
            <a class="results-all" href="<?php echo $search_url; ?>">
                Показать все результаты
            </a>
        </div>
<?php
    } else {
        echo '<div class="no-results">Ничего не найдено</div>';
    }

    wp_reset_postdata();
    wp_die();
}

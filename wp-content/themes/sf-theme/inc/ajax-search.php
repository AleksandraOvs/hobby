<?php
add_action('wp_ajax_ajax_product_search', 'ajax_product_search');
add_action('wp_ajax_nopriv_ajax_product_search', 'ajax_product_search');

function ajax_product_search()
{

    $search = sanitize_text_field($_POST['term']);

    if (empty($search)) {
        wp_die();
    }

    $args = [
        'post_type'      => 'product',
        'posts_per_page' => 5,
        's'              => $search,
    ];

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
    } else {
        echo '<div class="no-results">Ничего не найдено</div>';
    }

    wp_reset_postdata();
    wp_die();
}

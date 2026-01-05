<?php

add_action('wp_ajax_load_more_works', 'load_more_works');
add_action('wp_ajax_nopriv_load_more_works', 'load_more_works');

function load_more_works()
{
    $paged = intval($_POST['page']) + 1;

    $query = new WP_Query([
        'post_type'      => 'works',
        'posts_per_page' => 8,
        'paged'          => $paged,
        'post_status'    => 'publish',
    ]);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();

            // $image       = get_field('work_image');
            // $heading     = get_field('work_heading');
            // $description = get_field('work_description');
            // $sign        = get_field('work_sign');
?>

             <?php get_template_part('template-parts/work-item'); ?>

<?php endwhile;
    endif;

    wp_die();
}

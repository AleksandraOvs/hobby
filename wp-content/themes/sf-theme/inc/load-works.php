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

            $image       = get_field('work_image');
            $heading     = get_field('work_heading');
            $description = get_field('work_description');
            $sign        = get_field('work_sign');
?>

            <div class="work-item">
                <div class="work-image">
                    <?php if ($image) { ?>

                        <a
                            href="<?php echo esc_url($image['url']); ?>"
                            data-fancybox="works-gallery"
                            data-caption="<?php echo esc_attr($heading); ?>">
                            <img
                                src="<?php echo esc_url($image['sizes']['large']); ?>"
                                alt="<?php echo esc_attr($image['alt']); ?>">
                        </a>

                    <?php } else {
                    ?>
                        <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/svg/placeholder.svg" alt="<?php echo esc_attr($image['alt']); ?>">
                    <?php
                    } ?>
                </div>
                <?php if ($heading): ?>
                    <h3 class="work-heading"><?php echo esc_html($heading); ?></h3>
                <?php endif; ?>

                <?php if ($description): ?>
                    <div class="work-description"><?php echo esc_html($description); ?></div>
                <?php endif; ?>

                <?php if ($sign): ?>
                    <div class="work-sign"><?php echo esc_html($sign); ?></div>
                <?php endif; ?>
            </div>

<?php endwhile;
    endif;

    wp_die();
}

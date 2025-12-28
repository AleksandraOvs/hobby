<?php
$args = [
    'post_type'      => 'works',
    'posts_per_page' => 8,
    'post_status'    => 'publish',
    'paged'          => 1,
];

$query = new WP_Query($args);

if ($query->have_posts()) :
    echo '<div class="works-list">';
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

<?php
    endwhile;
    echo '</div>';
    wp_reset_postdata();
endif;
?>

<?php if ($query->max_num_pages > 1): ?>
    <button class="btn" id="load-more-works"
        data-page="1"
        data-max="<?php echo $query->max_num_pages; ?>">
        Посмотреть ещё
    </button>
<?php endif; ?>
</div>

</section>
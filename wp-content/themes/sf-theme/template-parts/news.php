<section class="section-news">
    <div class="container">
        <h2>Новости</h2>

        <?php
        $args = [
            'post_type'      => 'post',
            'posts_per_page' => 3,
            'post_status'    => 'publish',
        ];

        $query = new WP_Query($args);

        if ($query->have_posts()) : ?>
            <div class="posts-grid swiper posts-slider">
                <div class="swiper-wrapper">
                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                        <div class="swiper-slide post-item">
                            <?php get_template_part('template-parts/content-post') ?>
                        </div>
                    <?php endwhile; ?>
                </div>

                <a href="/" class="btn btn-news">Все новости</a>



            </div>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>

    </div>
</section>
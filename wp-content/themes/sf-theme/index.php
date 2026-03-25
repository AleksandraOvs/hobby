<?php get_header() ?>
<section class="page">
    <?php get_template_part('template-parts/elements/page-header'); ?>
    <div class="page-content">
        <div class="container">
            <h1>
                <?php
                if (is_home()) {
                    echo esc_html(get_the_title(get_option('page_for_posts')));
                } elseif (is_singular()) {
                    echo esc_html(get_the_title());
                } elseif (is_category() || is_tag()) {
                    single_term_title();
                } else {
                    wp_title('');
                }
                ?>
            </h1>

            <?php if (have_posts()) : ?>
                <div class="posts-grid">
                    <?php
                    while (have_posts()) {
                        the_post();
                        get_template_part('template-parts/content-post');
                    }
                    ?>
                </div>

                <?php theme_posts_pagination_with_load_more(); ?>

            <?php else : ?>
                <?php the_content(); ?>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php get_footer() ?>
<?php

/**
 * Archive template
 *
 * Used for category, tag, date, author and other archives
 *
 * @package YourTheme
 */

get_header();
?>

<main id="primary" class="site-main archive-page">

    <section class="page">
        <?php get_template_part('template-parts/elements/page-header'); ?>
        <div class="page-content">
            <div class="container">
                <?php the_archive_title('<h1 class="archive-title">', '</h1>'); ?>
                <?php the_archive_description('<div class="archive-description">', '</div>'); ?>

                <?php if (have_posts()) : ?>

                    <div class="archive-posts">
                        <?php
                        while (have_posts()) {
                            the_post();
                            get_template_part('template-parts/content-post');
                        }
                        ?>

                    </div>

                    <?php theme_posts_loop_with_pagination(['post_type' => 'post', 'posts_per_page' => 9]); ?>

                <?php else : ?>
                    <p>Записей не найдено.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();

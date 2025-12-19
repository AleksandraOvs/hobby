<?php get_header() ?>
<section class="page">
    <?php get_template_part('template-parts/elements/page-header'); ?>
    <div class="page-content">
        <div class="container">
            <h1><?php the_title(); ?></h1>

            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <?php endwhile;
            else: the_content();
            endif; ?>
        </div>
    </div>
</section>
<?php get_footer() ?>
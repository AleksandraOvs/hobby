<?php get_header() ?>
<section class="page">
    <?php get_template_part('template-parts/elements/page-header'); ?>
    <div class="page-content">
        <div class="container">
            <h1><?php the_title(); ?></h1>
            <div class="single-description">
                <div class="sd-date"><?php echo get_the_date('d.m.Y'); ?></div>
                <div class="sd-excerpt"><?php the_excerpt() ?></div>
            </div>

            <?php the_content(); ?>

        </div>
    </div>
</section>

<?php get_template_part('template-parts/section-contacts') ?>


<?php get_footer() ?>
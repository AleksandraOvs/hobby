<?php get_header() ?>
<section class="page">
    <?php get_template_part('template-parts/elements/page-header'); ?>
    <div class="page-content">
        <div class="container">
            <h1><?php the_title(); ?></h1>

            <?php the_content(); ?>

        </div>
    </div>
</section>

<?php get_template_part('template-parts/section-contacts') ?>


<?php get_footer() ?>
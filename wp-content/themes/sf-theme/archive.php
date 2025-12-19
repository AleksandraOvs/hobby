<?php get_header() ?>

<section>
    <div class="container">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <?php endwhile;
        else: ?>
            <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
        <?php endif; ?>
    </div>

</section>


<?php get_footer() ?>
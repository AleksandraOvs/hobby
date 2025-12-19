<article id="post-<?php the_ID(); ?>" <?php post_class('post-item'); ?>>

    <?php
    $post_arrow = '<svg width="48" height="9" viewBox="0 0 48 9" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M0 3.21917H44.9276L43.2673 1.5456C42.9158 1.1941 42.9158 0.616966 43.2673 0.26547C43.6192 -0.086404 44.1891 -0.0894275 44.5406 0.262069L47.7426 3.46182C48.0877 3.81104 48.0843 4.38024 47.7369 4.72682L44.5406 7.92317C44.191 8.27316 43.6173 8.27316 43.2673 7.92317C42.9158 7.57167 42.9158 7.01571 43.2673 6.66421L44.9276 5.01898H0V3.21917Z" fill="#8B4512"/>
</svg>';
    ?>
    <div class="post-thumb">
        <?php if (has_post_thumbnail()) { ?>
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('full'); ?>
            </a>
        <?php } else {
        ?>
            <a href="<?php the_permalink(); ?>">
                <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/svg/placeholder.svg" alt="">
            </a>
        <?php
        } ?>
    </div>


    <div class="post-content">
        <h3 class="post-title">
            <a href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
            </a>
        </h3>

        <div class="post-excerpt">
            <?php the_excerpt(); ?>
        </div>

        <div class="post-content__footer">
            <time class="post-date" datetime="<?php echo get_the_date('c'); ?>">
                <?php echo get_the_date('d.m.Y'); ?>
            </time>

            <a class="btn-link" href="<?php the_permalink(); ?>">
                Подробнее
                <?php echo  $post_arrow  ?>

            </a>

        </div>

    </div>

</article>
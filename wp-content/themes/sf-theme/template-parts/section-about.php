<section class="section-about">
    <div class="container">
        <?php if (get_field('about_heading')) : ?>
            <h2><?php the_field('about_heading'); ?></h2>
        <?php endif; ?>

        <?php if (get_field('about_description')) : ?>
            <div class="about-description">
                <?php the_field('about_description'); ?>
            </div>
        <?php endif; ?>


        <?php
        $about_items = get_field('about_items');

        if (! empty($about_items) && is_array($about_items)) : ?>
            <div class="about-items">

                <?php foreach ($about_items as $item) :

                    if (empty($item) || ! is_array($item)) {
                        continue;
                    }

                    // Берём первое и второе значение массива, не привязываясь к названиям
                    $values = array_values($item);

                    $pic  = $values[0] ?? null;
                    $desc = $values[1] ?? null;

                    if (empty($pic) && empty($desc)) {
                        continue;
                    }
                ?>
                    <div class="about-item">

                        <?php if ($pic) : ?>
                            <?php
                            // если картинка ID
                            if (is_int($pic)) :
                                echo wp_get_attachment_image($pic, 'full');
                            // если URL
                            elseif (is_string($pic)) :
                            ?>
                                <img src="<?php echo esc_url($pic); ?>" alt="">
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($desc) : ?>
                            <div class="about-item__text">
                                <?php echo esc_html($desc); ?>
                            </div>
                        <?php endif; ?>

                    </div>
                <?php endforeach; ?>

            </div>
        <?php endif; ?>

        <?php
        //var_dump(get_field('about_items'));
        ?>
    </div>

</section>
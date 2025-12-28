<?php

$images_group = get_field('works_image_group');
$heading      = get_field('work_heading');
$description  = get_field('work_description');
$sign         = get_field('work_sign');

?>

<div class="work-item">

    <div class="work-image">

        <?php if (! empty($images_group)): ?>

            <?php
            // посчитаем, сколько изображений реально есть
            $valid_images = array_filter($images_group);
            $count = count($valid_images);
            ?>

            <?php if ($count > 1): ?>

                <!-- SWIPER -->
                <div class="swiper works-swiper">
                    <div class="swiper-wrapper">

                        <?php foreach ($valid_images as $image_id): ?>

                            <?php
                            $img_full  = wp_get_attachment_image_src($image_id, 'full');
                            $img_large = wp_get_attachment_image_src($image_id, 'large');
                            $alt       = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                            ?>

                            <div class="swiper-slide">
                                <a
                                    href="<?php echo esc_url($img_full[0]); ?>"
                                    data-fancybox="works-gallery-<?php the_ID(); ?>"
                                    data-caption="<?php echo esc_attr($heading); ?>">
                                    <img
                                        src="<?php echo esc_url($img_large[0]); ?>"
                                        alt="<?php echo esc_attr($alt ?: $heading); ?>">
                                </a>
                            </div>

                        <?php endforeach; ?>

                    </div>

                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-pagination"></div>
                </div>

            <?php else: ?>

                <!-- ОДНО ИЗОБРАЖЕНИЕ -->
                <?php
                $image_id = reset($valid_images);
                $img_full  = wp_get_attachment_image_src($image_id, 'full');
                $img_large = wp_get_attachment_image_src($image_id, 'large');
                $alt       = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                ?>

                <a
                    href="<?php echo esc_url($img_full[0]); ?>"
                    data-fancybox="works-gallery-<?php the_ID(); ?>"
                    data-caption="<?php echo esc_attr($heading); ?>">
                    <img
                        src="<?php echo esc_url($img_large[0]); ?>"
                        alt="<?php echo esc_attr($alt ?: $heading); ?>">
                </a>

            <?php endif; ?>

        <?php else: ?>

            <img
                src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/svg/placeholder.svg"
                alt="">

        <?php endif; ?>

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
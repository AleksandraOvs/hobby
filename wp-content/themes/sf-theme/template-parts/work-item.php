<?php

$images_group = get_field('work_image_group');
$heading      = get_field('work_heading');
$description  = get_field('work_description');

// сразу нормализуем список изображений
$valid_images = [];
if ($images_group && is_array($images_group)) {
    $valid_images = array_filter($images_group);
}

$count = count($valid_images);

?>

<div class="work-item">

    <div class="work-image">

        <?php if ($count > 0): ?>

            <?php if ($count > 1): ?>

                <div class="swiper works-swiper">
                    <div class="swiper-wrapper">

                        <?php foreach ($valid_images as $img_id): ?>

                            <?php
                            $preview = wp_get_attachment_image_src($img_id, 'large');
                            $alt     = get_post_meta($img_id, '_wp_attachment_image_alt', true);
                            ?>

                            <div class="swiper-slide">
                                <a
                                    data-fancybox
                                    data-src="#work-modal-<?php the_ID(); ?>"
                                    href="javascript:;">
                                    <img
                                        src="<?php echo esc_url($preview[0]); ?>"
                                        alt="<?php echo esc_attr($alt); ?>">
                                </a>
                            </div>

                        <?php endforeach; ?>

                    </div>
                    <div class="works-swiper-pagination"></div>
                </div>

            <?php else: ?>

                <?php
                $img_id  = reset($valid_images);
                $preview = wp_get_attachment_image_src($img_id, 'large');
                $alt     = get_post_meta($img_id, '_wp_attachment_image_alt', true);
                ?>

                <a
                    data-fancybox
                    data-src="#work-modal-<?php the_ID(); ?>"
                    href="javascript:;"
                    class="work-open">
                    <img
                        src="<?php echo esc_url($preview[0]); ?>"
                        alt="<?php echo esc_attr($alt); ?>">
                </a>

            <?php endif; ?>

        <?php else: ?>

            <img
                src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/svg/placeholder.svg"
                alt="">

        <?php endif; ?>

    </div>

    <?php if ($description): ?>
        <div class="work-description"><?php echo esc_html($description); ?></div>
    <?php endif; ?>

    <?php if ($heading): ?>
        <h3 class="work-heading"><?php echo esc_html($heading); ?></h3>
    <?php endif; ?>

    <?php if ($count > 0): ?>
        <div style="display:none;">
            <div class="work-modal" id="work-modal-<?php the_ID(); ?>">

                <div class="swiper work-modal-swiper">
                    <div class="swiper-wrapper">

                        <?php foreach ($valid_images as $img_id): ?>
                            <?php
                            $full = wp_get_attachment_image_src($img_id, 'full');
                            $alt  = get_post_meta($img_id, '_wp_attachment_image_alt', true);
                            ?>
                            <div class="swiper-slide">
                                <img src="<?php echo esc_url($full[0]); ?>" alt="<?php echo esc_attr($alt); ?>">
                            </div>
                        <?php endforeach; ?>

                    </div>

                    <div class="work-modal-pagination"></div>
                </div>
                <?php if ($description): ?>
                    <div class="work-modal-description">
                        <?php echo wpautop($description); ?>
                    </div>
                <?php endif; ?>

                <?php if ($heading): ?>
                    <h3 class="work-modal-heading"><?php echo esc_html($heading); ?></h3>
                <?php endif; ?>

                <div class="work-title"><span>Изделие: </span><?php the_title() ?></div>

                <?php
                $products_group = get_field('works_products');
                $links = $products_group['work_products_link'] ?? [];
                ?>

                <?php if (!empty($links) && is_array($links)): ?>

                    <div class="work-modal-products">
                        <span>Товар: </span>
                        <?php $total = count($links);
                        $i = 0; ?>
                        <?php foreach ($links as $post_id): $i++; ?>
                            <?php
                            $post_obj = get_post($post_id);
                            if (!$post_obj) continue;
                            $url   = get_permalink($post_obj);
                            $title = get_the_title($post_obj);
                            ?>
                            <a
                                href="<?php echo esc_url($url); ?>"
                                class="work-modal-product-link">
                                <?php echo esc_html($title); ?>
                            </a>
                            <?php echo ($i < $total) ? ', ' : '.'; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    <?php endif; ?>

</div>
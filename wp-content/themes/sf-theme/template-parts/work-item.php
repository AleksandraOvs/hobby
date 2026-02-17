<?php

$images_group = get_field('work_image_group');
$heading      = get_field('work_heading');
$description  = get_field('work_description');
$sign         = get_field('work_sign');

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

    <?php if ($sign): ?>
        <div class="work-sign"><?php echo esc_html($sign); ?></div>
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

                    <div class="work-modal-prev">
                        <svg width="48" height="84" viewBox="0 0 48 84" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M45.8516 9.73709C46.9431 8.67315 47.5157 7.2464 47.5581 5.80299C47.6004 4.35695 47.1121 2.89427 46.0841 1.76193L45.9468 1.6149C44.9438 0.5846 43.6376 0.0441388 42.3181 0.00256398C41.0002 -0.0386329 39.6649 0.418309 38.6029 1.38209L38.4566 1.51852L1.70899 37.5057C0.617465 38.5685 0.0448615 39.9956 0.0025308 41.439C-0.0397999 42.8847 0.448893 44.3485 1.47655 45.4809C9.38899 53.6764 17.8113 61.7793 26.1769 69.8263C30.2894 73.7827 34.3894 77.727 38.4562 81.7099L38.6017 81.8448C39.6641 82.809 40.9998 83.2667 42.3181 83.2255C43.6387 83.1847 44.9441 82.6438 45.948 81.6135L46.084 81.4676C47.1121 80.3345 47.6004 78.8715 47.5581 77.4254C47.5157 75.982 46.9435 74.556 45.8516 73.4924L13.3004 41.614L45.8516 9.73709Z" fill="#FEFEFE" />
                        </svg>

                    </div>
                    <div class="work-modal-next">
                        <svg width="48" height="84" viewBox="0 0 48 84" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M1.70893 73.4909C0.617404 74.5549 0.0448108 75.9816 0.00247998 77.425C-0.0398508 78.8711 0.448463 80.3338 1.47649 81.4661L1.6137 81.6131C2.61679 82.6434 3.92299 83.1839 5.24242 83.2255C6.56034 83.2667 7.89564 82.8097 8.95769 81.8459L9.10396 81.7095L45.8516 45.7224C46.9431 44.6595 47.5157 43.2324 47.558 41.789C47.6003 40.3433 47.1117 38.8795 46.084 37.7472C38.1716 29.5516 29.7492 21.4487 21.3836 13.4017C17.2711 9.44532 13.1711 5.501 9.10435 1.51813L8.95884 1.38321C7.89641 0.419054 6.56072 -0.0386478 5.24242 0.00254901C3.92186 0.0433679 2.61641 0.584214 1.61256 1.61451L1.4765 1.76041C0.448466 2.89351 -0.0398475 4.35656 0.00248311 5.80261C0.0448138 7.24601 0.617029 8.67203 1.70893 9.73559L34.2601 41.614L1.70893 73.4909Z" fill="#FEFEFE" />
                        </svg>

                    </div>

                </div>

                <?php if ($description): ?>
                    <div class="work-modal-description">
                        <?php echo wpautop($description); ?>
                    </div>
                <?php endif; ?>

                <?php if ($sign): ?>
                    <h3 class="work-modal-heading"><?php echo esc_html($sign); ?></h3>
                <?php endif; ?>

                <div class="work-title"><span>Изделие: </span><?php the_title(); ?></div>

                <?php
                $links = get_field('work_products_link');
                ?>

                <?php if (!empty($links)): ?>
                    <div class="work-modal-products">
                        <span>Товар: </span>

                        <?php
                        // Нормализуем к массиву объектов
                        if (is_object($links)) {
                            $links = [$links];
                        }

                        $total = count($links);
                        $i = 0;

                        foreach ($links as $post_obj):
                            $i++;

                            if (!is_object($post_obj)) {
                                continue;
                            }

                            $url   = get_permalink($post_obj->ID);
                            $title = get_the_title($post_obj->ID);
                        ?>
                            <a href="<?php echo esc_url($url); ?>" class="work-modal-product-link">
                                <?php echo esc_html($title); ?>
                            </a><?php echo ($i < $total) ? ', ' : ''; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    <?php endif; ?>

</div>
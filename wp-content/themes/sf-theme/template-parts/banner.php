<?php
$banner      = get_field('banner_bg');
$banner_title       = get_field('banner_heading');
$banner_description = get_field('banner_text');

// Получаем URL безопасно
$url  = '';
$text = '';
?>

<section class="banner">
    <div class="container">
        <div class="banner__inner">
            <?php if ($banner): ?>
                <img class="banner__inner__img" src="<?= esc_url($banner); ?>" alt="">
            <?php endif; ?>

            <div class="banner__inner__content">

                <?php if ($banner_title): ?>
                    <h2 class="banner__inner__content__title"><?= esc_html($banner_title); ?></h2>
                <?php endif; ?>

                <?php if ($banner_description): ?>
                    <div class="banner__inner__content__description">
                        <?= wp_kses_post($banner_description); ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>
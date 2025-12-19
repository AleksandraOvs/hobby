<?php
$hero_banner      = get_field('main_banner');
$hero_title       = get_field('main_title');
$hero_description = get_field('main_description');
$hero_button = get_field('main_button'); // убедись, что имя поля верное

// Получаем URL безопасно
$url  = '';
$text = '';

?>

<section class="main-hero">
    <div class="main-hero__inner">
        <?php if ($hero_banner): ?>
            <img class="main-hero__banner" src="<?= esc_url($hero_banner); ?>" alt="">
        <?php endif; ?>

        <div class="main-hero__inner__content">
            <div class="container">
                <?php if ($hero_title): ?>
                    <h1 class="main-hero__title"><?= esc_html($hero_title); ?></h1>
                <?php endif; ?>

                <?php if ($hero_description): ?>
                    <div class="main-hero__description">
                        <?= wp_kses_post($hero_description); ?>
                    </div>
                <?php endif; ?>


                <!-- <pre>
<?php //var_dump($hero_button);
?>
</pre> -->
                <?php

                if (!empty($hero_button)) {
                    $text = $hero_button['text'] ?? '';
                    // Link теперь строка
                    $url = is_string($hero_button['link']) ? $hero_button['link'] : '';
                }
                ?>

                <?php if (!empty($url) && !empty($text)) : ?>
                    <a
                        href="<?= esc_url($url); ?>"
                        class="btn hero-btn">
                        <?= esc_html($text); ?>
                    </a>
                <?php endif; ?>

            </div>


        </div>
    </div>







</section>
<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package tours
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo get_stylesheet_directory_uri() ?>/images/favicons/favi.ico">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_stylesheet_directory_uri() ?>/images/favicons/favicon_16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_stylesheet_directory_uri() ?>/images/favicons/favicon_32x32.png">

    <!-- Apple -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_stylesheet_directory_uri() ?>/images/favicons/apple-touch-icon.png">

    <?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <div id="page" class="site">
        <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'tours'); ?></a>

        <header id="masthead" class="site-header">
            <?php get_template_part('template-parts/mobile-menu') ?>
            <!-- end of HEADER TOP -->
            <div class="container">
                <div class="site-header-top">
                    <div class="header-left">
                        <?php
                        $header_logo = get_theme_mod('header_logo');
                        $img = wp_get_attachment_image_src($header_logo, 'full');
                        if ($img) : echo '<a class="custom-logo-link" href="' . site_url() . '"><img src="' . $img[0] . '" alt=""></a>';
                        endif;
                        ?>
                    </div><!-- .site-branding -->

                    <div class="header-center">
                        <nav id="site-navigation" class="main-navigation">


                            <?php wp_nav_menu([
                                'container' => false,
                                'theme_location'  => 'main_menu',
                                //'walker' => new My_Custom_Walker_Nav_Menu,
                                //'depth'           => 2,
                            ]); ?>

                        </nav><!-- #site-navigation -->

                        <div class="header-center__contacts">
                            <!-- Телефон -->
                            <?php // Телефон
                            $phone = get_field('contact_phone', 'option');
                            ?>
                            <?php if ($phone): ?>
                                <div class="contact-phone">
                                    <?php if (!empty($phone['phone_icon'])): ?>
                                        <img src="<?php echo esc_url($phone['phone_icon']); ?>" alt="">
                                    <?php endif; ?>

                                    <?php if (!empty($phone['phone_link']['url'])): ?>
                                        <a href="<?php echo esc_url($phone['phone_link']['url']); ?>"
                                            <?php if (!empty($phone['phone_link']['target'])) echo 'target="' . esc_attr($phone['phone_link']['target']) . '"'; ?>>
                                            <?php echo esc_html($phone['phone_text']); ?>
                                        </a>
                                    <?php else: ?>
                                        <?php echo esc_html($phone['phone_text']); ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Мессенджеры -->
                            <?php
                            // Мессенджеры
                            $mes1 = get_field('mes_1', 'option');
                            $mes2 = get_field('mes_2', 'option');
                            $mes3 = get_field('mes_3', 'option');
                            ?>
                            <div class="contacts-messengers">
                                <?php
                                $messengers = [$mes1, $mes2, $mes3];

                                foreach ($messengers as $index => $mes):
                                    if (!$mes) continue;

                                    $icon_key = "mes" . ($index + 1) . "_icon";
                                    $text_key = "mes" . ($index + 1) . "_text";
                                    $link_key = "mes" . ($index + 1) . "_link";

                                    // безопасный доступ к Link полю
                                    $url = (isset($mes[$link_key]['url']) && is_string($mes[$link_key]['url'])) ? $mes[$link_key]['url'] : '';
                                    $target = (isset($mes[$link_key]['target']) && is_string($mes[$link_key]['target'])) ? $mes[$link_key]['target'] : '';
                                    $text = !empty($mes[$text_key]) ? $mes[$text_key] : '';
                                    $icon = !empty($mes[$icon_key]) ? $mes[$icon_key] : '';
                                ?>
                                    <?php if ($url): ?>
                                        <a class="messenger messenger-<?php echo $index + 1; ?>" href="<?php echo esc_url($url); ?>" <?php if ($target) echo 'target="' . esc_attr($target) . '"'; ?>>
                                            <?php if ($icon): ?>
                                                <img src="<?php echo esc_url($icon); ?>" alt="<?php echo esc_html($text); ?>">
                                            <?php endif; ?>

                                        </a>
                                    <?php elseif ($text): ?>
                                        <span class="messenger messenger-<?php echo $index + 1; ?>">
                                            <?php if ($icon): ?>
                                                <img src="<?php echo esc_url($icon); ?>" alt="<?php echo esc_html($text); ?>">
                                            <?php endif; ?>

                                        </span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>

                            <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                                <div class="bar"></div>
                                <div class="bar"></div>
                                <div class="bar"></div>
                            </button>

                        </div>


                    </div>
                    <div class="header-right">
                        <!-- Адрес -->
                        <?php
                        $address = get_field('address', 'option');
                        if ($address): ?>
                            <div class="contact-address">
                                <?php
                                // Иконка
                                if (!empty($address['address_icon'])):
                                    $icon = $address['address_icon'];
                                    $icon_url = is_array($icon) && isset($icon['url']) ? $icon['url'] : $icon;
                                    $icon_alt = is_array($icon) && isset($icon['alt']) ? $icon['alt'] : '';
                                ?>
                                    <img src="<?php echo esc_url($icon_url); ?>" alt="<?php echo esc_attr($icon_alt); ?>">
                                <?php endif; ?>

                                <!-- Текст адреса -->
                                <?php if (!empty($address['address_text'])): ?>
                                    <?php echo nl2br(wp_kses_post($address['address_text'])); ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php $email = get_field('email', 'option');
                        if ($email): ?>
                            <div class="contact-email">
                                <?php if (!empty($email['email_icon'])): ?>
                                    <img src="<?php echo esc_url($email['email_icon']); ?>" alt="">
                                <?php endif; ?>

                                <?php if (!empty($email['email_link']['url'])): ?>
                                    <a href="<?php echo esc_url($email['email_link']['url']); ?>"
                                        <?php if (!empty($email['email_link']['target'])) echo 'target="' . esc_attr($email['email_link']['target']) . '"'; ?>>
                                        <?php echo esc_html($email['email_text']); ?>
                                    </a>
                                <?php else: ?>
                                    <?php echo esc_html($email['email_text']); ?>
                                <?php endif; ?>

                            </div>
                        <?php endif; ?>

                        <?php
                        $schedule = get_field('schedule', 'option');
                        if ($schedule): ?>
                            <div class="contact-schedule">
                                <?php if (!empty($schedule['schedule_icon']['url'])): ?>
                                    <img src="<?php echo esc_url($schedule['schedule_icon']['url']); ?>"
                                        alt="<?php echo esc_attr($schedule['schedule_icon']['alt'] ?? ''); ?>">
                                <?php endif; ?>

                                <?php if (!empty($schedule['schedule_text'])): ?>
                                    <span><?php echo esc_html($schedule['schedule_text']); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>

                <div class="site-header-bottom">
                    <?php get_template_part('template-parts/categories-menu') ?>

                    <?php if (is_active_sidebar('header')) : ?>
                        <?php dynamic_sidebar('header'); ?>
                    <?php endif; ?>
                </div>
            </div>
        </header><!-- #masthead -->
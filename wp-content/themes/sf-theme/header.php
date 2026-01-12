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
    <script>
        // Предотвратить изменение scrollTop всеми скриптами
        let scrollBlocked = true;

        const originalScrollTop = Object.getOwnPropertyDescriptor(HTMLElement.prototype, 'scrollTop');
        if (originalScrollTop && originalScrollTop.set) {
            Object.defineProperty(document.documentElement, 'scrollTop', {
                set(value) {
                    if (!scrollBlocked) originalScrollTop.set.call(this, value);
                }
            });
        }

        const originalScrollTopBody = Object.getOwnPropertyDescriptor(HTMLElement.prototype, 'scrollTop');
        if (originalScrollTopBody && originalScrollTopBody.set) {
            Object.defineProperty(document.body, 'scrollTop', {
                set(value) {
                    if (!scrollBlocked) originalScrollTopBody.set.call(this, value);
                }
            });
        }

        console.log('🛑 Автоскролл заблокирован, страница больше не телепортируется.');
    </script>
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

                    <?php
                    // Получаем текущего пользователя
                    if (is_user_logged_in()) {

                        $icon = '<svg width="29" height="26" viewBox="0 0 29 26" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M14.0712 2.37846C14.411 2.02507 14.7734 1.70759 15.1661 1.42602C16.4871 0.47622 18.0121 0 19.9831 0C22.1866 0 24.1969 0.817512 25.6649 2.13581C27.1941 3.51232 28.1424 5.43118 28.1424 7.56132C28.1424 10.8178 26.451 13.9302 23.9195 16.9648C21.5709 19.7824 18.5692 22.4818 15.572 25.1747C14.7077 25.9521 13.4045 25.9344 12.5628 25.1675C9.56674 22.4757 6.56957 19.7794 4.22249 16.9648C1.69096 13.9302 0 10.8178 0 7.56132C0 5.43118 0.947909 3.5108 2.479 2.13732C3.94545 0.817511 5.95843 0 8.15887 0C10.1318 0 11.6564 0.47622 12.9778 1.42602C13.3686 1.70759 13.731 2.02507 14.0712 2.37846ZM19.9831 2.24655C23.2486 2.24655 25.8954 4.62841 25.8954 7.56132C25.8954 12.8772 19.9831 18.1931 14.0712 23.5075C8.15887 18.1931 2.24693 12.8772 2.24693 7.56132C2.24693 4.62841 4.89487 2.24655 8.15887 2.24655C11.1156 2.24655 12.5926 3.57392 14.0712 6.23244C15.5494 3.57392 17.0279 2.24655 19.9831 2.24655Z" fill="#3D332E"/>
</svg>';

                        // Получаем массив товаров пользователя
                        $user_id = get_current_user_id();
                        $wishlist = get_user_meta($user_id, 'custom_wishlist', true) ?: [];
                        $count = count($wishlist);

                        // Ссылка на страницу вишлиста (можно заменить на свой URL)
                        $url = get_permalink(get_page_by_path('izbrannoe')); // страница «Избранное»
                    ?>

                        <a class="header-wishlist" href="/wishlist">
                            <span class="wishlist-icon"><?php echo $icon; ?></span>
                            <?php if ($count > 0): ?>
                                <span class="wishlist-counter"><?php echo esc_html($count); ?></span>
                            <?php endif; ?>
                        </a>

                    <?php } ?>

                    <?php if (!is_cart()) : ?>
                        <button class="mini-cart-icon modalActive" data-mfp-src="#miniCart-popup">
                            <div class="shopping-cart-icon">
                                <svg width="35" height="28" viewBox="0 0 35 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1.12328 2.24693C0.503063 2.24693 0 1.7435 0 1.12328C0 0.503056 0.503063 0 1.12328 0H4.7331C5.77134 0 6.49171 0.238112 7.06091 0.844726C7.53713 1.35269 7.80549 2.04472 7.99106 3.02854L33.5645 3.03761C34.1786 3.09921 34.6265 3.65178 34.5653 4.26595C34.5638 4.29279 33.292 14.1713 32.6839 17.4055C31.914 21.5036 28.2875 21.631 28.2784 21.631H13.0617C8.9828 21.6593 8.32062 17.8159 8.26242 17.4172L5.80875 3.56788C5.69801 2.93745 5.58426 2.54778 5.42816 2.38148C5.33859 2.28586 5.11672 2.24693 4.7331 2.24693H1.12328ZM8.39094 5.27547L10.4791 17.0865C10.5494 17.4849 10.9928 19.4079 13.0526 19.3886L28.2784 19.3841C28.283 19.3841 30.0461 19.3107 30.4819 16.992C30.9494 14.5024 31.8179 8.00617 32.1759 5.27547H8.39094Z" fill="#3D332E" />
                                    <path d="M14.8517 27.4322C16.0818 27.4322 17.0789 26.435 17.0789 25.2049C17.0789 23.9748 16.0818 22.9777 14.8517 22.9777C13.6216 22.9777 12.6244 23.9748 12.6244 25.2049C12.6244 26.435 13.6216 27.4322 14.8517 27.4322Z" fill="#3D332E" />
                                    <path d="M25.7291 27.4322C26.9592 27.4322 27.9564 26.435 27.9564 25.2049C27.9564 23.9748 26.9592 22.9777 25.7291 22.9777C24.499 22.9777 23.5018 23.9748 23.5018 25.2049C23.5018 26.435 24.499 27.4322 25.7291 27.4322Z" fill="#3D332E" />
                                </svg>

                            </div>

                            <span class="cart-count"><?php //echo WC()->cart->get_cart_contents_count()
                                                        ?>
                                <?php echo count(WC()->cart->get_cart())
                                ?>
                            </span>
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mobile-nav">
                <div class="container">
                    <nav class="main-navigation">
                        <?php wp_nav_menu([
                            'container' => false,
                            'theme_location'  => 'main_menu',
                            //'walker' => new My_Custom_Walker_Nav_Menu,
                            //'depth'           => 2,
                        ]); ?>

                    </nav><!-- #site-navigation -->
                </div>

            </div>
        </header><!-- #masthead -->
<div class="mobile-menu__container">
    <div class="mobile-menu">
        <div class="container">
            <?php wp_nav_menu([
                'container' => false,
                'theme_location'  => 'mob_menu',
            ]); ?>
        </div>
    </div>

    <div class="mobile-menu__inner">
        <div class="mobile-menu__close">
            <svg width="9" height="9" viewBox="0 0 9 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4.114 4.822L7.36 8.068C7.45333 8.16133 7.568 8.21133 7.704 8.218C7.84 8.22467 7.96133 8.17467 8.068 8.068C8.17467 7.96133 8.228 7.84333 8.228 7.714C8.228 7.58467 8.17467 7.46667 8.068 7.36L4.822 4.114L8.068 0.868C8.16133 0.774666 8.21133 0.66 8.218 0.524C8.22467 0.388 8.17467 0.266666 8.068 0.159999C7.96133 0.0533327 7.84333 0 7.714 0C7.58467 0 7.46667 0.0533327 7.36 0.159999L4.114 3.406L0.868 0.159999C0.774666 0.066666 0.66 0.0166664 0.524 0.00999975C0.388 0.00333309 0.266666 0.0533327 0.159999 0.159999C0.0533327 0.266666 0 0.384666 0 0.513999C0 0.643333 0.0533327 0.761333 0.159999 0.868L3.406 4.114L0.159999 7.36C0.066666 7.45333 0.0166664 7.56833 0.00999975 7.705C0.00333309 7.84033 0.0533327 7.96133 0.159999 8.068C0.266666 8.17467 0.384666 8.228 0.513999 8.228C0.643333 8.228 0.761333 8.17467 0.868 8.068L4.114 4.822Z" fill="#564d49" />
            </svg>
        </div>
        <div class="mm-item mm-categories" id="catalog">

            <?php

            $parent_id = 0;
            $level = 0;

            $terms = get_terms([
                'taxonomy'   => 'product_cat',
                'hide_empty' => false,
                'parent'     => $parent_id,
            ]);



            if (empty($terms) || is_wp_error($terms)) {
                return;
            }

            // Класс для вложенных списков
            $ul_class = $level === 0
                ? 'menu'
                : 'dropdown-menu level-' . $level;

            // id только для корневого ul
            $ul_id = $level === 0 ? ' id="menu-mobile-catalog"' : '';

            echo '<ul' . $ul_id . ' class="' . esc_attr($ul_class) . '">';

            foreach ($terms as $term) {

                // Проверяем, есть ли дети
                $children = get_terms([
                    'taxonomy'   => 'product_cat',
                    'hide_empty' => false,
                    'parent'     => $term->term_id,
                    'number'     => 1,
                ]);

                $has_children = !empty($children);

                $li_classes = ['menu-item'];
                if ($has_children) {
                    $li_classes[] = 'menu-item-has-children';
                }

                echo '<li class="' . esc_attr(implode(' ', $li_classes)) . '">';

                echo '<a href="' . esc_url(get_term_link($term)) . '">';
                echo esc_html($term->name);
                echo '</a>';

                // Дочерние категории — на том же уровне, сразу после <a>
                if ($has_children) {
                    render_product_categories_menu($term->term_id, $level + 1);
                }

                echo '</li>';
            }

            echo '</ul>';
            ?>




        </div>

        <div class="mm-item mm-search" id="search">
            <?php if (is_active_sidebar('header')) : ?>
                <?php dynamic_sidebar('header'); ?>
            <?php endif; ?>
        </div>



        <div class="mm-item mm-contacts" id="contacts">

            <div class="section-contacts__inner__left">
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

                        <div class="contacts-address__inner">
                            <!-- Текст адреса -->
                            <?php if (!empty($address['address_text'])): ?>
                                <span>Адрес:</span>
                                <?php echo nl2br(wp_kses_post($address['address_text'])); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php $email = get_field('email', 'option');
                if ($email): ?>
                    <div class="contact-email">
                        <?php if (!empty($email['email_icon'])): ?>
                            <img src="<?php echo esc_url($email['email_icon']); ?>" alt="">
                        <?php endif; ?>

                        <?php if (!empty($email['email_link']['url'])): ?>
                            <span>E-mail:</span>
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
                        <span>График работы:</span>
                        <?php if (!empty($schedule['schedule_text'])): ?>
                            <p><?php echo esc_html($schedule['schedule_text']); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>


        </div>



    </div>
</div>
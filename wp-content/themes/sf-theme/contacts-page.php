<?php

/** Template name: Страница контактов */
get_header();

// ----------------------
// Телефон
$phone = get_field('contact_phone', 'option');

// ----------------------
// Email
$email = get_field('email', 'option');

// ----------------------
// Мессенджеры
$mes1 = get_field('mes_1', 'option');
$mes2 = get_field('mes_2', 'option');
$mes3 = get_field('mes_3', 'option');

// ----------------------
// Адрес
$address = get_field('address', 'option');

// ----------------------
// Режим работы
$schedule = get_field('schedule', 'option');

// ----------------------
// Карта
$map = get_field('map_code', 'option');
?>

<div class="contacts-block">

    <!-- Телефон -->
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

    <!-- Email -->
    <?php if ($email): ?>
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

    <!-- Мессенджеры -->
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
                        <img src="<?php echo esc_url($icon); ?>" alt="">
                    <?php endif; ?>
                    <span><?php echo esc_html($text); ?></span>
                </a>
            <?php elseif ($text): ?>
                <span class="messenger messenger-<?php echo $index + 1; ?>">
                    <?php if ($icon): ?>
                        <img src="<?php echo esc_url($icon); ?>" alt="">
                    <?php endif; ?>
                    <?php echo esc_html($text); ?>
                </span>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <!-- Адрес -->
    <?php if ($address): ?>
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
                <span><?php echo nl2br(wp_kses_post($address['address_text'])); ?></span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Режим работы -->
    <?php if ($schedule): ?>
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

    <!-- Карта -->
    <?php if ($map): ?>
        <div class="contact-map">
            <?php echo $map; // HTML / JS код карты 
            ?>
        </div>
    <?php endif; ?>

</div>

<?php get_footer(); ?>
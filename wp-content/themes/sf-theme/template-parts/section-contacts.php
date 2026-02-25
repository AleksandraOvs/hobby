<section class="section-contacts">
    <div class="container">

        <div class="section-contacts__inner">
            <div class="section-contacts__inner__left">
                <h2>Контакты</h2>
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

                <?php
                $phone = get_field('contact_phone', 'option');
                if ($phone): ?>
                    <div class="contact-phone">
                        <?php if (!empty($phone['phone_icon'])): ?>
                            <img src="<?php echo esc_url($phone['phone_icon']); ?>" alt="">
                        <?php endif; ?>

                        <?php if (!empty($phone['phone_link']['url'])): ?>
                            <span>Тел.:</span>
                            <a href="<?php echo esc_url($phone['phone_link']['url']); ?>"
                                <?php if (!empty($phone['phone_link']['target'])) echo 'target="' . esc_attr($phone['phone_link']['target']) . '"'; ?>>
                                <?php echo esc_html($phone['phone_text']); ?>
                            </a>
                        <?php else: ?>
                            <?php echo esc_html($phone['phone_text']); ?>
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
            <div class="section-contacts__inner__right">
                <?php
                $map_code = get_field('map_code', 'option');
                if ($map_code) {
                    echo '<div class="contacts-map">';
                    echo $map_code;
                    echo '</div>';
                }

                ?>
            </div>
        </div>

    </div>
</section>
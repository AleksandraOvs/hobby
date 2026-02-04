<?php

/**
 * Шаблон письма для уведомления пользователя о новом сообщении
 *
 * @var string $user_name
 * @var string $message
 * @var string $file_html
 */
?>

<p>Здравствуйте, <?php echo esc_html($user_name); ?>!</p>
<p>Вы получили новое сообщение от специалиста:</p>
<div style="padding:10px; border:1px solid #ddd; margin-bottom:10px;">
    <div><?php echo wp_kses_post($message); ?></div>
    <?php if (!empty($file_html)) : ?>
        <div style="margin-top:10px;">
            <?php echo $file_html; ?>
        </div>
    <?php endif; ?>
</div>
<p>Перейти к чату можно в личном кабинете:
    <a href="<?php echo esc_url(wc_get_account_endpoint_url('support')); ?>">Ваши обращения</a>
</p>
<p>С уважением,<br>Команда сайта</p>
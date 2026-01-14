<?php
if (! defined('ABSPATH')) exit;

$cls   = $sender === 'admin' ? 'admin' : 'user';
$label = $sender === 'admin' ? 'Ответ специалиста' : 'Ваше обращение';
?>

<div class="wc-chat-message-inner <?php echo esc_attr($cls); ?>">
    <div class="wc-chat-message-inner-content">

        <?php if ($message) : ?>
            <div class="wc-chat-message">
                <?php echo wp_kses_post($message); ?>
            </div>
        <?php endif; ?>

        <?php if ($file_html) : ?>
            <?php echo $file_html; ?>
        <?php endif; ?>

    </div>

    <p class="time">
        <?php echo esc_html($label); ?> · <?php echo esc_html($sent_at); ?>
    </p>
</div>
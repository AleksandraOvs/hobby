<php
    function wc_user_chat_notify_user($user_id, $message, $file_html='' ) {
    $user=get_userdata($user_id);
    if (!$user) return;

    $to=$user -> user_email;
    $subject = 'Новое сообщение в чате от администратора';

    // Можно хранить шаблон письма в отдельном файле
    $template = plugin_dir_path(__FILE__) . 'templates/email-notification.php';
    if (file_exists($template)) {
    ob_start();
    include $template;
    $body = ob_get_clean();
    } else {
    $body = '<p>У вас новое сообщение:</p>';
    $body .= '<p>' . nl2br(esc_html($message)) . '</p>';
    if ($file_html) $body .= '<p>' . $file_html . '</p>';
    }

    wp_mail($to, $subject, $body, ['Content-Type: text/html; charset=UTF-8']);
    }
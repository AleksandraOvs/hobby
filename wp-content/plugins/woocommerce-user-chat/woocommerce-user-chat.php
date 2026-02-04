<?php

/**
 * Plugin Name: WooCommerce User Chat
 * Description: Простой чат с админом для личного кабинета WooCommerce с обновлением через AJAX и подсветкой новых сообщений.
 * Version: 1.2
 * Author: PurpleWeb
 */

if (!defined('ABSPATH')) exit;

// ---------------------------
// Endpoint "Обращения" в ЛК
// ---------------------------
add_action('init', function () {
    add_rewrite_endpoint('support', EP_ROOT | EP_PAGES);
});

add_filter('woocommerce_account_menu_items', function ($items) {
    $new_items = [];
    foreach ($items as $key => $label) {
        if ($key === 'customer-logout') {
            $new_items['support'] = 'Обращения';
        }
        $new_items[$key] = $label;
    }
    return $new_items;
});

add_action('woocommerce_account_support_endpoint', function () {
    echo '<h3>Обращения</h3>';
    echo do_shortcode('[wc_user_chat]');
});

// Подключаем админскую часть
if (is_admin() && file_exists(plugin_dir_path(__FILE__) . 'admin/admin-chat.php')) {
    require plugin_dir_path(__FILE__) . 'admin/admin-chat.php';
}

// ---------------------------
// Создание таблицы сообщений с поддержкой файлов
// ---------------------------
register_activation_hook(__FILE__, function () {
    global $wpdb;
    $table_name = $wpdb->prefix . 'wc_user_chat';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id BIGINT UNSIGNED NOT NULL,
        admin_id BIGINT UNSIGNED DEFAULT 1,
        message TEXT NULL,
        message_type VARCHAR(20) NOT NULL DEFAULT 'text',
        file_url TEXT NULL,
        file_name VARCHAR(255) NULL,
        file_type VARCHAR(100) NULL,
        sent_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        sender ENUM('user','admin') NOT NULL,
        is_read TINYINT(1) DEFAULT 0,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
});

// ---------------------------
// Подключение скриптов и стилей
// ---------------------------
add_action('wp_enqueue_scripts', function () {
    if (!is_account_page()) return;

    wp_enqueue_script(
        'wc-user-chat',
        plugin_dir_url(__FILE__) . 'wc-user-chat.js',
        ['jquery'],
        '1.2',
        true
    );

    wp_localize_script('wc-user-chat', 'wcUserChat', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('wc_user_chat_nonce'),
        'user_id'  => get_current_user_id(),
    ]);

    wp_enqueue_style(
        'wc-user-chat-style',
        plugin_dir_url(__FILE__) . 'wc-user-chat.css'
    );
});

// ---------------------------
// Шорткод чата
// ---------------------------
add_shortcode('wc_user_chat', function () {

    if (!is_user_logged_in() || !class_exists('WooCommerce')) {
        return 'Только для авторизованных клиентов';
    }

    ob_start(); ?>

    <div id="wc-chat-container">

        <div id="wc-chat-messages"></div>

        <?php
        include plugin_dir_path(__FILE__) . 'templates/chat-form.php';
        ?>

    </div>

<?php
    return ob_get_clean();
});

// ---------------------------
// AJAX: отправка сообщений пользователем с поддержкой файлов
// ---------------------------
add_action('wp_ajax_wc_send_chat', function () {
    check_ajax_referer('wc_user_chat_nonce', 'nonce');
    global $wpdb;

    $user_id = get_current_user_id();
    if (!$user_id) wp_send_json_error();

    $message = sanitize_text_field($_POST['message'] ?? '');
    $file_name = '';
    $file_url  = '';

    // Если пришел файл
    if (!empty($_FILES['file']['name'])) {
        $uploaded_file = $_FILES['file'];

        // Ограничение размера 5 МБ
        if ($uploaded_file['size'] > 5 * 1024 * 1024) {
            wp_send_json_error(['message' => 'Файл слишком большой. Максимум 5 МБ']);
        }

        // Разрешенные типы файлов
        $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
        if (!in_array($uploaded_file['type'], $allowed_types)) {
            wp_send_json_error(['message' => 'Недопустимый тип файла']);
        }

        require_once ABSPATH . 'wp-admin/includes/file.php';
        $upload = wp_handle_upload($uploaded_file, ['test_form' => false]);

        if (!isset($upload['error'])) {
            $file_name = sanitize_file_name($uploaded_file['name']);
            $file_url  = esc_url($upload['url']);
        } else {
            wp_send_json_error(['message' => $upload['error']]);
        }
    }

    // Если нет текста и нет файла — не сохраняем
    if (!$message && !$file_url) wp_send_json_error(['message' => 'Нет текста или файла']);

    $wpdb->insert($wpdb->prefix . 'wc_user_chat', [
        'user_id'   => $user_id,
        'message'   => $message,
        'file_name' => $file_name,
        'file_url'  => $file_url,
        'sender'    => 'user',
        'is_read'   => 0,
        'sent_at'   => current_time('mysql')
    ]);

    wp_send_json_success([
        'message'   => $message,
        'file_name' => $file_name,
        'file_url'  => $file_url
    ]);
});

// ---------------------------
// AJAX: получение сообщений пользователя
// ---------------------------
add_action('wp_ajax_wc_get_chat', function () {
    check_ajax_referer('wc_user_chat_nonce', 'nonce');
    global $wpdb;

    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error();
    }

    $messages = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}wc_user_chat 
             WHERE user_id = %d 
             ORDER BY sent_at DESC",
            $user_id
        ),
        ARRAY_A
    );

    ob_start();

    foreach ($messages as $msg) {

        $message  = $msg['message'];
        $sender   = $msg['sender'];
        $sent_at  = $msg['sent_at'];

        $file_html = '';
        if (!empty($msg['file_url'])) {

            $ext = strtolower(pathinfo($msg['file_url'], PATHINFO_EXTENSION));
            $img_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (in_array($ext, $img_exts, true)) {

                // 🖼 миниатюра изображения
                $file_html = '
            <div class="wc-chat-file">
                <img
                    src="' . esc_url($msg['file_url']) . '"
                    data-full="' . esc_url($msg['file_url']) . '"
                    class="wc-chat-thumb"
                    alt="' . esc_attr($msg['file_name']) . '"
                >
            </div>';
            } else {

                // 📄 обычный файл
                $file_html = '
            <div class="wc-chat-file">
                <a href="' . esc_url($msg['file_url']) . '" target="_blank">
                    ' . esc_html($msg['file_name']) . '
                </a>
            </div>';
            }
        }

        include plugin_dir_path(__FILE__) . 'templates/chat-message.php';
    }

    wp_send_json_success([
        'html' => ob_get_clean()
    ]);
});


// ---------------------------
// AJAX: очистка чата
// ---------------------------
add_action('wp_ajax_wc_clear_chat', function () {
    check_ajax_referer('wc_user_chat_nonce', 'nonce');
    global $wpdb;

    $user_id = get_current_user_id();
    if (!$user_id) wp_send_json_error();

    $wpdb->delete($wpdb->prefix . 'wc_user_chat', ['user_id' => $user_id]);

    wp_send_json_success();
});

// ---------------------------
// Админка: чат
// ---------------------------
add_action('admin_menu', 'wc_user_chat_admin_menu');
function wc_user_chat_admin_menu()
{
    add_submenu_page(
        'woocommerce',
        'User Chat',
        'User Chat',
        'manage_options',
        'wc-user-chat',
        'wc_user_chat_admin_page'
    );
}


//отправка файлов
add_action('wp_ajax_wc_user_chat_upload_file', 'wc_user_chat_upload_file');
add_action('wp_ajax_nopriv_wc_user_chat_upload_file', 'wc_user_chat_upload_file');

function wc_user_chat_upload_file()
{

    if (empty($_FILES['file'])) {
        wp_send_json_error('Файл не передан');
    }

    // Кто отправляет
    $sender = is_admin() ? 'admin' : 'user';

    // Ограничения
    $max_size = 5 * 1024 * 1024; // 5 MB
    $allowed_types = [
        'image/jpeg',
        'image/png',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];

    $file = $_FILES['file'];

    if ($file['size'] > $max_size) {
        wp_send_json_error('Файл слишком большой');
    }

    if (!in_array($file['type'], $allowed_types, true)) {
        wp_send_json_error('Недопустимый тип файла');
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';

    $upload = wp_handle_upload($file, [
        'test_form' => false
    ]);

    if (isset($upload['error'])) {
        wp_send_json_error($upload['error']);
    }

    global $wpdb;

    $table = $wpdb->prefix . 'wc_user_chat_messages';

    $wpdb->insert($table, [
        'chat_id'      => intval($_POST['chat_id']),
        'sender'       => $sender,
        'message_type' => 'file',
        'file_url'     => esc_url_raw($upload['url']),
        'file_name'    => sanitize_text_field($file['name']),
        'file_type'    => sanitize_text_field($file['type']),
        'created_at'   => current_time('mysql')
    ]);

    wp_send_json_success([
        'url'  => $upload['url'],
        'name' => $file['name'],
        'type' => $file['type']
    ]);
}

// Тестовый эндпоинт для проверки таблицы
add_action('wp_ajax_wc_user_chat_test', function () {
    global $wpdb;
    $table = $wpdb->prefix . 'wc_user_chat';

    $results = $wpdb->get_results(
        "SELECT id, user_id, message, sender, sent_at, is_read 
         FROM $table 
         ORDER BY sent_at DESC 
         LIMIT 5",
        ARRAY_A
    );

    wp_send_json_success($results);
});

// Обновляем структуру таблицы для хранения имени и URL файла
// register_activation_hook(__FILE__, function () {
//     global $wpdb;
//     $table_name = $wpdb->prefix . 'wc_user_chat';  // Имя таблицы чата
//     $charset_collate = $wpdb->get_charset_collate();

//     // Обновленный SQL запрос для добавления новых столбцов
//     $sql = "CREATE TABLE IF NOT EXISTS $table_name (
//         id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
//         user_id BIGINT UNSIGNED NOT NULL,
//         admin_id BIGINT UNSIGNED DEFAULT 1,
//         message TEXT NOT NULL,
//         sent_at DATETIME DEFAULT CURRENT_TIMESTAMP,
//         sender ENUM('user','admin') NOT NULL,
//         is_read TINYINT(1) DEFAULT 0,
//         file_name VARCHAR(255) DEFAULT '',    -- Новое поле для имени файла
//         file_url  VARCHAR(255) DEFAULT '',    -- Новое поле для URL файла
//         PRIMARY KEY (id)
//     ) $charset_collate;";

//     // Включаем WP функцию для выполнения SQL запроса
//     require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
//     dbDelta($sql);  // dbDelta автоматически обновит структуру таблицы, если нужно
// });

// ---------------------------
// AJAX отправка сообщения админом с файлом и уведомление пользователя
// ---------------------------

//уведомление о сообщении по почте пользователю
function wc_user_chat_notify_user($user_id, $message, $file_html = '')
{
    $user = get_userdata($user_id);
    if (!$user) return;

    $to = $user->user_email;
    $subject = 'Новое сообщение в чате поддержки';

    // Подключаем шаблон письма
    ob_start();
    include plugin_dir_path(__FILE__) . 'templates/email-user-notify.php';
    $body = ob_get_clean();

    $headers = ['Content-Type: text/html; charset=UTF-8'];

    wp_mail($to, $subject, $body, $headers);
}

add_action('wp_ajax_wc_user_chat_send_admin', function () {
    global $wpdb;

    $user_id = intval($_POST['user_id']);
    $message = sanitize_text_field($_POST['message']);
    $table_name = $wpdb->prefix . 'wc_user_chat';

    $file_name = '';
    $file_url  = '';

    // Обработка файла
    if (!empty($_FILES['file']['name'])) {
        $uploaded_file = $_FILES['file'];

        // Ограничение размера 5 МБ
        if ($uploaded_file['size'] > 5 * 1024 * 1024) {
            wp_send_json_error(['message' => 'Файл слишком большой. Максимум 5 МБ']);
        }

        // Разрешенные типы файлов
        $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
        if (!in_array($uploaded_file['type'], $allowed_types)) {
            wp_send_json_error(['message' => 'Недопустимый тип файла']);
        }

        // Используем WP функцию загрузки
        $upload = wp_handle_upload($uploaded_file, ['test_form' => false]);

        if (!isset($upload['error'])) {
            $file_name = sanitize_file_name($uploaded_file['name']);
            $file_url  = esc_url($upload['url']);
        } else {
            wp_send_json_error(['message' => $upload['error']]);
        }
    }

    // Сохраняем сообщение в базе
    $wpdb->insert($table_name, [
        'user_id'   => $user_id,
        'message'   => $message,
        'file_name' => $file_name,
        'file_url'  => $file_url,
        'sender'    => 'admin',
        'is_read'   => 1,
        'sent_at'   => current_time('mysql')
    ]);

    // --------------------
    // Уведомление пользователя по почте
    $user = get_userdata($user_id);
    $user_name = $user ? $user->display_name : 'Пользователь';

    // Формируем HTML для файла (если есть)
    $file_html = '';
    if (!empty($file_url)) {
        $ext = strtolower(pathinfo($file_url, PATHINFO_EXTENSION));
        $img_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($ext, $img_exts, true)) {
            $file_html = '<img src="' . esc_url($file_url) . '" style="max-width:200px;" />';
        } else {
            $file_html = '<a href="' . esc_url($file_url) . '" target="_blank">' . esc_html($file_name) . '</a>';
        }
    }

    //wc_user_chat_notify_user($user_id, $message, $file_html);
    // --------------------

    wp_send_json_success([
        'message'   => $message,
        'file_name' => $file_name,
        'file_url'  => $file_url
    ]);
});



// Тестовый эндпоинт для проверки отправки письма пользователю
add_action('wp_ajax_wc_user_chat_test_email', function () {
    $user_id = get_current_user_id(); // текущий пользователь
    if (!$user_id) wp_send_json_error('Не авторизован');

    $test_message = 'Тестовое сообщение от чата';
    $test_file = '<p>Файл прикреплен: <a href="#">пример.pdf</a></p>';

    // Вызов функции уведомления
    wc_user_chat_notify_user($user_id, $test_message, $test_file);

    wp_send_json_success('Письмо отправлено (проверка)');
});

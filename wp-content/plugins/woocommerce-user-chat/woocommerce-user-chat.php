<?php

/**
 * Plugin Name: WooCommerce User Chat
 * Description: Простой чат с админом для личного кабинета WooCommerce с обновлением через AJAX и кнопкой "Очистить чат".
 * Version: 1.1
 * Author: Шурочка
 */

if (!defined('ABSPATH')) exit;

// ---------------------------
// Создание таблицы сообщений
// ---------------------------
register_activation_hook(__FILE__, function () {
    global $wpdb;
    $table_name = $wpdb->prefix . 'wc_user_chat';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id BIGINT UNSIGNED NOT NULL,
        admin_id BIGINT UNSIGNED DEFAULT 1,
        message TEXT NOT NULL,
        sent_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        sender ENUM('user','admin') NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
});

// ---------------------------
// Подключение скриптов и стилей
// ---------------------------
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script('wc-user-chat', plugin_dir_url(__FILE__) . 'wc-user-chat.js', ['jquery'], '1.1', true);
    wp_localize_script('wc-user-chat', 'wcUserChat', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('wc_user_chat_nonce'),
        'user_id'  => get_current_user_id(),
    ]);
    wp_enqueue_style('wc-user-chat-style', plugin_dir_url(__FILE__) . 'wc-user-chat.css');
});

// ---------------------------
// Шорткод чата
// ---------------------------
add_shortcode('wc_user_chat', function () {
    if (!is_user_logged_in() || !class_exists('WooCommerce')) return 'Только для авторизованных клиентов';

    ob_start(); ?>
    <div id="wc-chat-container">
        <div id="wc-chat-messages"></div>
        <textarea id="wc-chat-input" placeholder="Написать сообщение"></textarea>
        <button id="wc-chat-send">Отправить</button>
        <button id="wc-chat-clear" class="wc-chat-clear">Очистить чат</button>
    </div>
<?php
    return ob_get_clean();
});

// ---------------------------
// AJAX добавление сообщения
// ---------------------------
add_action('wp_ajax_wc_send_chat', function () {
    check_ajax_referer('wc_user_chat_nonce', 'nonce');
    global $wpdb;

    $message = sanitize_text_field($_POST['message'] ?? '');
    $user_id = get_current_user_id();

    if (!$message || !$user_id) wp_send_json_error();

    $wpdb->insert($wpdb->prefix . 'wc_user_chat', [
        'user_id' => $user_id,
        'message' => $message,
        'sender'  => 'user',
    ]);

    wp_send_json_success();
});

// ---------------------------
// AJAX получение сообщений
// ---------------------------
add_action('wp_ajax_wc_get_chat', function () {
    check_ajax_referer('wc_user_chat_nonce', 'nonce');
    global $wpdb;

    $user_id = get_current_user_id();
    if (!$user_id) wp_send_json_error();

    $messages = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}wc_user_chat WHERE user_id = %d ORDER BY sent_at ASC",
        $user_id
    ));

    wp_send_json_success($messages);
});

// ---------------------------
// AJAX очистка чата
// ---------------------------
add_action('wp_ajax_wc_clear_chat', function () {
    check_ajax_referer('wc_user_chat_nonce', 'nonce');
    global $wpdb;

    $user_id = get_current_user_id();
    if (!$user_id) wp_send_json_error();

    $wpdb->delete($wpdb->prefix . 'wc_user_chat', ['user_id' => $user_id]);

    wp_send_json_success();
});

// Админский интерфейс чата
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

function wc_user_chat_admin_page()
{
?>
    <div class="wrap">
        <h1>User Chat</h1>
        <div id="wc-user-chat-container">
            <p>Выберите пользователя, чтобы открыть чат.</p>
            <ul id="wc-user-list">
                <?php
                global $wpdb;
                $table_name = $wpdb->prefix . 'wc_user_chat';
                $users = $wpdb->get_results("SELECT DISTINCT user_id FROM $table_name ORDER BY timestamp DESC");
                foreach ($users as $user) {
                    $user_data = get_userdata($user->user_id);
                    if ($user_data) {
                        echo '<li data-user-id="' . esc_attr($user->user_id) . '">' . esc_html($user_data->user_login) . '</li>';
                    }
                }
                ?>
            </ul>
            <div id="wc-chat-box" style="display:none;">
                <div id="wc-chat-messages" style="border:1px solid #ccc; height:300px; overflow-y:scroll; padding:5px;"></div>
                <textarea id="wc-chat-input" rows="3" style="width:100%;"></textarea>
                <button id="wc-chat-send" class="button button-primary">Отправить</button>
                <button id="wc-chat-clear" class="button button-secondary">Очистить чат</button>
            </div>
        </div>
    </div>

    <script>
        jQuery(function($) {
            var selectedUser = null;

            $('#wc-user-list li').on('click', function() {
                selectedUser = $(this).data('user-id');
                $('#wc-chat-box').show();
                loadMessages();
            });

            function loadMessages() {
                if (!selectedUser) return;
                $.post(ajaxurl, {
                    action: 'wc_user_chat_load',
                    user_id: selectedUser
                }, function(response) {
                    $('#wc-chat-messages').html(response);
                    $('#wc-chat-messages').scrollTop($('#wc-chat-messages')[0].scrollHeight);
                });
            }

            $('#wc-chat-send').on('click', function() {
                var msg = $('#wc-chat-input').val();
                if (!msg || !selectedUser) return;
                $.post(ajaxurl, {
                    action: 'wc_user_chat_send',
                    user_id: selectedUser,
                    message: msg
                }, function() {
                    $('#wc-chat-input').val('');
                    loadMessages();
                });
            });

            $('#wc-chat-clear').on('click', function() {
                if (!selectedUser) return;
                $.post(ajaxurl, {
                    action: 'wc_user_chat_clear',
                    user_id: selectedUser
                }, function() {
                    loadMessages();
                });
            });

            setInterval(function() {
                if (selectedUser) loadMessages();
            }, 5000); // автообновление каждые 5 сек
        });
    </script>
<?php
}


// Загрузка сообщений
add_action('wp_ajax_wc_user_chat_load', function () {
    global $wpdb;
    $user_id = intval($_POST['user_id']);
    $table_name = $wpdb->prefix . 'wc_user_chat';
    $messages = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE user_id=%d ORDER BY timestamp ASC", $user_id));

    foreach ($messages as $msg) {
        $sender = $msg->sender === 'admin' ? '<strong>Admin:</strong> ' : '';
        echo '<div>' . $sender . esc_html($msg->message) . '</div>';
    }
    wp_die();
});

// Отправка сообщений
add_action('wp_ajax_wc_user_chat_send', function () {
    global $wpdb;
    $user_id = intval($_POST['user_id']);
    $message = sanitize_text_field($_POST['message']);
    $table_name = $wpdb->prefix . 'wc_user_chat';
    $wpdb->insert($table_name, [
        'user_id' => $user_id,
        'message' => $message,
        'sender' => 'admin',
        'timestamp' => current_time('mysql')
    ]);
    wp_die();
});

// Очистка чата
add_action('wp_ajax_wc_user_chat_clear', function () {
    global $wpdb;
    $user_id = intval($_POST['user_id']);
    $table_name = $wpdb->prefix . 'wc_user_chat';
    $wpdb->delete($table_name, ['user_id' => $user_id]);
    wp_die();
});

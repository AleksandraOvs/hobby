<?php

/**
 * Plugin Name: WooCommerce User Chat
 * Description: Простой чат с админом для личного кабинета WooCommerce с обновлением через AJAX и подсветкой новых сообщений.
 * Version: 1.2
 * Author: Шурочка
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
// AJAX: отправка сообщений пользователем
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
        'is_read' => 0
    ]);

    wp_send_json_success();
});

// ---------------------------
// AJAX: получение сообщений пользователя
// ---------------------------
add_action('wp_ajax_wc_get_chat', function () {
    check_ajax_referer('wc_user_chat_nonce', 'nonce');
    global $wpdb;

    $user_id = get_current_user_id();
    if (!$user_id) wp_send_json_error();

    // Помечаем сообщения пользователя как прочитанные админом
    $wpdb->update(
        $wpdb->prefix . 'wc_user_chat',
        ['is_read' => 1],
        ['user_id' => $user_id, 'sender' => 'user', 'is_read' => 0]
    );

    $messages = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}wc_user_chat WHERE user_id = %d ORDER BY sent_at ASC",
            $user_id
        )
    );

    wp_send_json_success($messages);
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

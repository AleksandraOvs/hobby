<?php
if (!defined('ABSPATH')) exit;

// ---------------------------
// Админка: чат
// ---------------------------
add_action('admin_menu', function () {
    add_menu_page(
        'User Chat',
        'User Chat',
        'manage_options',
        'wc-user-chat',
        'wc_user_chat_admin_page',
        'dashicons-format-chat',
        25
    );
});

function wc_user_chat_admin_page()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'wc_user_chat';
    $users = $wpdb->get_results("SELECT DISTINCT user_id FROM $table_name ORDER BY sent_at DESC"); ?>

    <div class="wrap">
        <h1>User Chat</h1>
        <div style="display:flex; gap:20px;">
            <ul id="wc-user-list" style="width:200px; border:1px solid #ccc; padding:10px;">
                <?php foreach ($users as $user):
                    $user_data = get_userdata($user->user_id);
                    if ($user_data):
                        $unread = $wpdb->get_var($wpdb->prepare(
                            "SELECT COUNT(*) FROM $table_name WHERE user_id=%d AND sender='user' AND is_read=0",
                            $user->user_id
                        ));
                        $badge = $unread > 0 ? " <strong style='color:red;'>($unread)</strong>" : "";
                        echo '<li data-user-id="' . esc_attr($user->user_id) . '" style="cursor:pointer;">' . esc_html($user_data->user_login) . $badge . '</li>';
                    endif;
                endforeach; ?>
            </ul>

            <div id="wc-chat-box" style="flex:1; border:1px solid #ccc; padding:10px; display:none;">
                <div id="wc-chat-messages" style="height:400px; overflow-y:scroll; border-bottom:1px solid #ccc; margin-bottom:10px;"></div>
                <textarea id="wc-chat-input" rows="3" style="width:100%;"></textarea>
                <button id="wc-chat-send" class="button button-primary">Ответить</button>
            </div>
        </div>
    </div>

    <script>
        jQuery(function($) {
            let selectedUser = null;

            $('#wc-user-list li').on('click', function() {
                selectedUser = $(this).data('user-id');
                $('#wc-chat-box').show();
                loadMessages();
            });

            function loadMessages() {
                if (!selectedUser) return;
                $.post(ajaxurl, {
                    action: 'wc_user_chat_load_admin',
                    user_id: selectedUser
                }, function(res) {
                    const $box = $('#wc-chat-messages');
                    $box.html('');
                    res.forEach(msg => {
                        const sender = msg.sender === 'admin' ? '<strong>Admin:</strong>' : '<strong>User:</strong>';
                        const answered = msg.sender === 'user' && msg.admin_reply ? '<span style="color:green;">(отвечено)</span>' : '';
                        $box.append(
                            `<div style="margin-bottom:5px; border-bottom:1px solid #eee; padding:3px;">
                            ${sender} ${msg.message} ${answered}<br>
                            <small style="color:#666;">${msg.sent_at}</small>
                        </div>`
                        );
                    });
                    $box.scrollTop($box[0].scrollHeight);
                });
            }

            $('#wc-chat-send').on('click', function() {
                const msg = $('#wc-chat-input').val();
                if (!msg || !selectedUser) return;
                $.post(ajaxurl, {
                    action: 'wc_user_chat_send_admin',
                    user_id: selectedUser,
                    message: msg
                }, function() {
                    $('#wc-chat-input').val('');
                    loadMessages();
                });
            });

            setInterval(function() {
                if (selectedUser) loadMessages();
            }, 5000);
        });
    </script>
<?php
}

// ---------------------------
// AJAX загрузка сообщений для админа
// ---------------------------
add_action('wp_ajax_wc_user_chat_load_admin', function () {
    global $wpdb;
    $user_id = intval($_POST['user_id']);
    $table_name = $wpdb->prefix . 'wc_user_chat';

    $messages = $wpdb->get_results($wpdb->prepare(
        "SELECT *,
         (SELECT COUNT(*) FROM $table_name t2 WHERE t2.user_id=t1.user_id AND t2.sender='admin' AND t2.sent_at>t1.sent_at) as admin_reply
         FROM $table_name t1
         WHERE user_id=%d ORDER BY sent_at ASC",
        $user_id
    ), ARRAY_A);

    $wpdb->update($table_name, ['is_read' => 1], ['user_id' => $user_id, 'sender' => 'user', 'is_read' => 0]);

    wp_send_json_success($messages);
});

// ---------------------------
// AJAX отправка сообщения админом
// ---------------------------
add_action('wp_ajax_wc_user_chat_send_admin', function () {
    global $wpdb;
    $user_id = intval($_POST['user_id']);
    $message = sanitize_text_field($_POST['message']);
    $table_name = $wpdb->prefix . 'wc_user_chat';

    $wpdb->insert($table_name, [
        'user_id' => $user_id,
        'message' => $message,
        'sender' => 'admin',
        'is_read' => 1,
        'sent_at' => current_time('mysql')
    ]);

    wp_send_json_success();
});

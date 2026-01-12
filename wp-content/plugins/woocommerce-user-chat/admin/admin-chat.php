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

// подключаем стили админки
add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook !== 'toplevel_page_wc-user-chat') return;

    wp_enqueue_style(
        'wc-user-chat-admin',
        plugin_dir_url(__FILE__) . 'admin-chat.css',
        [],
        '1.0'
    );
});

function wc_user_chat_admin_page()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'wc_user_chat';
    $users = $wpdb->get_results("SELECT DISTINCT user_id FROM $table_name ORDER BY sent_at DESC");
?>

    <div class="wrap wc-user-chat-admin">
        <h1>User Chat</h1>

        <div class="wc-chat-layout">
            <ul id="wc-user-list" class="wc-user-list">
                <?php foreach ($users as $user):
                    $user_data = get_userdata($user->user_id);
                    if (!$user_data) continue;

                    $unread = (int) $wpdb->get_var($wpdb->prepare(
                        "SELECT COUNT(*) FROM $table_name 
						 WHERE user_id=%d AND sender='user' AND is_read=0",
                        $user->user_id
                    ));
                ?>
                    <li data-user-id="<?php echo esc_attr($user->user_id); ?>">
                        <?php echo esc_html($user_data->user_login); ?>
                        <?php if ($unread > 0): ?>
                            <span class="wc-unread-count"><?php echo $unread; ?></span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div id="wc-chat-box" class="wc-chat-box">

                <div id="wc-chat-messages" class="wc-chat-messages"></div>

                <textarea id="wc-chat-input" rows="3"></textarea>
                <button id="wc-chat-send" class="button button-primary">
                    Ответить
                </button>
                <input type="file" id="wc-chat-file" />
            </div>
        </div>
    </div>

    <script>
        jQuery(function($) {
            let selectedUser = null;

            $('#wc-user-list').on('click', 'li', function() {
                selectedUser = $(this).data('user-id');
                $('#wc-chat-box').addClass('is-visible');
                loadMessages();
            });

            function loadMessages() {
                if (!selectedUser) return;

                $.post(ajaxurl, {
                    action: 'wc_user_chat_load_admin',
                    user_id: selectedUser
                }, function(res) {
                    if (!res.success) return;

                    const $box = $('#wc-chat-messages');
                    $box.empty();

                    res.data.forEach(msg => {
                        const senderClass = msg.sender === 'admin' ? 'admin' : 'user';

                        // отметка, если сообщение пользователя прочитано (админ ответил)
                        const answered = (msg.sender === 'user' && msg.is_read == 1) ?
                            '<span class="wc-answered">✔ отвечено</span>' : '';

                        // формируем имя с ссылкой, если есть
                        const senderLabel = msg.sender_link ?
                            `<a href="${msg.sender_link}" target="_blank">${msg.sender_name}</a>` :
                            msg.sender_name;

                        // основной блок сообщения
                        let messageHtml = `
                <div class="wc-chat-message ${senderClass}">
                    <div class="wc-chat-text">
                        <h3>${senderLabel}:</h3>
                        ${msg.message}
                        ${answered}
                    </div>
                    <div class="wc-chat-time">${msg.sent_at}</div>
                </div>
            `;

                        // если есть файл, добавляем ссылку
                        if (msg.file_url) {
                            const isImage = msg.file_url.match(/\.(jpg|jpeg|png|webp|gif)$/i);

                            messageHtml += `
        <div class="wc-chat-file">
            ${isImage ? `
                <a href="${msg.file_url}" target="_blank" class="wc-chat-thumb">
                    <img src="${msg.file_url}" alt="${msg.file_name}">
                </a>
            ` : ''}

            <div class="wc-chat-file-actions">
                <span class="wc-chat-file-name">${msg.file_name}</span>
                <a href="${msg.file_url}" download class="wc-chat-download">
                    ⬇ Скачать
                </a>
            </div>
        </div>
    `;
                        }

                        $box.append(messageHtml);
                    });

                    $box.scrollTop($box[0].scrollHeight);
                });
            }

            $('#wc-chat-send').on('click', function() {
                const msg = $('#wc-chat-input').val().trim();
                const fileInput = $('#wc-chat-file')[0];
                if (!msg && !fileInput.files.length) return;

                const formData = new FormData();
                formData.append('action', 'wc_user_chat_send_admin');
                formData.append('user_id', selectedUser);
                formData.append('message', msg);

                if (fileInput.files.length > 0) {
                    formData.append('file', fileInput.files[0]);
                }

                $.ajax({
                    url: ajaxurl,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.success) {
                            $('#wc-chat-input').val('');
                            $('#wc-chat-file').val('');
                            loadMessages();
                        }
                    }
                });
            });

            setInterval(function() {
                if (selectedUser) loadMessages();
            }, 50000);
        });
    </script>
<?php
}

// ---------------------------
// AJAX загрузка сообщений для админа
// ---------------------------
// add_action('wp_ajax_wc_user_chat_load_admin', function () {
//     global $wpdb;
//     $user_id = intval($_POST['user_id']);
//     $table_name = $wpdb->prefix . 'wc_user_chat';

//     $messages = $wpdb->get_results(
//         $wpdb->prepare(
//             "SELECT id, user_id, message, sender, sent_at, is_read
//              FROM $table_name
//              WHERE user_id = %d
//              ORDER BY sent_at ASC",
//             $user_id
//         ),
//         ARRAY_A
//     );

//     // помечаем сообщения пользователя как прочитанные
//     $wpdb->update(
//         $table_name,
//         ['is_read' => 1],
//         ['user_id' => $user_id, 'sender' => 'user', 'is_read' => 0]
//     );

//     wp_send_json_success($messages);
// });

add_action('wp_ajax_wc_user_chat_load_admin', function () {
    global $wpdb;

    $user_id = intval($_POST['user_id'] ?? 0);
    if (!$user_id) {
        wp_send_json_error();
    }

    $table = $wpdb->prefix . 'wc_user_chat';
    $user  = get_userdata($user_id);

    $user_name = $user ? $user->display_name : 'Пользователь';
    $user_edit = $user ? admin_url('user-edit.php?user_id=' . $user_id) : '';

    $messages = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT id, message, sender, sent_at, is_read, file_name, file_url
         FROM $table
         WHERE user_id = %d
         ORDER BY sent_at ASC",
            $user_id
        ),
        ARRAY_A
    );

    // дополняем каждое сообщение
    foreach ($messages as &$msg) {
        if ($msg['sender'] === 'user') {
            $msg['sender_name'] = $user_name;
            $msg['sender_link'] = $user_edit;
        } else {
            $msg['sender_name'] = 'Администратор';
            $msg['sender_link'] = '';
        }
    }

    // помечаем сообщения пользователя как прочитанные
    $wpdb->update(
        $table,
        ['is_read' => 1],
        ['user_id' => $user_id, 'sender' => 'user', 'is_read' => 0]
    );

    wp_send_json_success($messages);
});

// ---------------------------
// AJAX отправка сообщения админом с файлом
// ---------------------------
add_action('wp_ajax_wc_user_chat_send_admin', function () {
    global $wpdb;
    $user_id = intval($_POST['user_id']);
    $message = sanitize_text_field($_POST['message']);
    $table_name = $wpdb->prefix . 'wc_user_chat';

    $file_name = '';
    $file_url  = '';

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

    // Сохраняем сообщение
    $wpdb->insert($table_name, [
        'user_id'   => $user_id,
        'message'   => $message,
        'file_name' => $file_name,
        'file_url'  => $file_url,
        'sender'    => 'admin',
        'is_read'   => 1,
        'sent_at'   => current_time('mysql')
    ]);

    wp_send_json_success([
        'message'   => $message,
        'file_name' => $file_name,
        'file_url'  => $file_url
    ]);
});

jQuery(function ($) {
    const $messages = $('#wc-chat-messages');
    const $input = $('#wc-chat-input');

    // -----------------------------
    // Рендер сообщений в контейнер
    // -----------------------------
    function renderMessages(messages) {
        $messages.html('');
        messages.forEach(msg => {
            const cls = msg.sender === 'admin' ? 'admin' : 'user';
            $messages.append(
                `<div class="wc-chat-message ${cls}">
                    <span class="text">${msg.message}</span>
                    <span class="time">${msg.sent_at}</span>
                </div>`
            );
        });
        $messages.scrollTop($messages[0].scrollHeight);
    }

    // -----------------------------
    // Загрузка всей истории сообщений
    // -----------------------------
    function loadHistory() {
        $.post(wcUserChat.ajax_url, {
            action: 'wc_chat_load_history',
            nonce: wcUserChat.nonce
        }, function (res) {
            if (res.success) {
                renderMessages(res.data);
            }
        });
    }

    // -----------------------------
    // Загрузка новых сообщений для автообновления
    // -----------------------------
    function loadMessages() {
        $.post(wcUserChat.ajax_url, {
            action: 'wc_get_chat',
            nonce: wcUserChat.nonce
        }, function (res) {
            if (res.success) {
                renderMessages(res.data);
            }
        });
    }

    // -----------------------------
    // Отправка сообщения
    // -----------------------------
    $('#wc-chat-send').on('click', function () {
        const text = $input.val().trim();
        if (!text) return;

        $.post(wcUserChat.ajax_url, {
            action: 'wc_send_chat',
            message: text,
            nonce: wcUserChat.nonce
        }, function (res) {
            if (res.success) {
                $input.val('');
                loadMessages();
            }
        });
    });

    // -----------------------------
    // Очистка чата
    // -----------------------------
    $('#wc-chat-clear').on('click', function () {
        if (!confirm('Вы уверены, что хотите очистить чат?')) return;
        $.post(wcUserChat.ajax_url, {
            action: 'wc_clear_chat',
            nonce: wcUserChat.nonce
        }, function (res) {
            if (res.success) {
                $messages.html('');
            }
        });
    });

    // -----------------------------
    // Автоподгрузка каждые 3 секунды
    // -----------------------------
    setInterval(loadMessages, 3000);

    // -----------------------------
    // Первая загрузка истории сообщений
    // -----------------------------
    loadHistory();
});

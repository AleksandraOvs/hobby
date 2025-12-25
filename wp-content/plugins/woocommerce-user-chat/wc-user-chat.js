jQuery(function ($) {
    const $messages = $('#wc-chat-messages');
    const $input = $('#wc-chat-input');

    function loadMessages() {
        $.post(wcUserChat.ajax_url, {
            action: 'wc_get_chat',
            nonce: wcUserChat.nonce
        }, function (res) {
            if (res.success) {
                $messages.html('');
                res.data.forEach(msg => {
                    const cls = msg.sender === 'admin' ? 'admin' : 'user';
                    $messages.append('<div class="wc-chat-message ' + cls + '">' + msg.message + '<span class="time">' + msg.sent_at + '</span></div>');
                });
                $messages.scrollTop($messages[0].scrollHeight);
            }
        });
    }

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

    // Очистка чата
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

    // автоподгрузка каждые 3 секунды
    setInterval(loadMessages, 3000);
    loadMessages();
});

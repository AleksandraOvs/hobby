jQuery(function ($) {
    const $messages = $('#wc-chat-messages');
    const $input = $('#wc-chat-input');

    // -----------------------------
    // Рендер сообщений в контейнер
    // -----------------------------
    function renderMessages(messages) {
        if (!$messages.length) return;

        $messages.html('');

        messages.forEach(msg => {
            const cls = msg.sender === 'admin' ? 'admin' : 'user';
            const label = msg.sender === 'admin'
                ? 'Ответ специалиста'
                : 'Ваше обращение';

            let messageContent = '';

            if (msg.message) {
                messageContent += `<div class="wc-chat-message">${msg.message}</div>`;
            }

            // Вставляем готовый HTML для файлов
            if (msg.file_html) {
                messageContent += msg.file_html;
            }

            $messages.append(`
            <div class="wc-chat-message-inner ${cls}">
                <div class="wc-chat-message-inner-content">
                    ${messageContent}
                </div>
                <p class="time">${label} · ${msg.sent_at}</p>
            </div>
        `);
        });

        if ($messages[0]) {
            $messages.scrollTop($messages[0].scrollHeight);
        }
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
    // Отправка сообщения с файлом
    // -----------------------------
    const $chatWrapper = $('<div class="wc-chat-wrapper"></div>'); // обертка для чата
    $input.wrap($chatWrapper);
    $input.after('<input type="file" id="wc-chat-file" />');

    $('#wc-chat-send').on('click', function () {
        const text = $input.val().trim();
        const fileInput = $('#wc-chat-file')[0];

        if (!text && !fileInput.files.length) return;

        const formData = new FormData();
        formData.append('action', 'wc_send_chat');
        formData.append('message', text);
        formData.append('nonce', wcUserChat.nonce);

        if (fileInput.files.length > 0) {
            formData.append('file', fileInput.files[0]);
        }

        $.ajax({
            url: wcUserChat.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                if (res.success) {
                    $input.val('');
                    $('#wc-chat-file').val('');
                    loadMessages();
                } else {
                    alert(res.data?.message || 'Ошибка при отправке сообщения');
                }
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
                $('#wc-chat-file').val('');
            }
        });
    });

    // -----------------------------
    // Автоподгрузка каждые 3 секунд
    // -----------------------------
    setInterval(loadMessages, 30000);

    // -----------------------------
    // Первая загрузка истории сообщений
    // -----------------------------
    loadHistory();
});

// -----------------------------
// Лайтбокс изображений
// -----------------------------
jQuery(function ($) {
    $('body').on('click', '.wc-chat-thumb', function (e) {
        e.preventDefault();
        const src = $(this).data('full');
        if (!src) return;

        const $overlay = $(`
            <div class="wc-chat-lightbox">
                <img src="${src}" />
            </div>
        `);

        $('body').append($overlay);

        $overlay.on('click', function () {
            $overlay.remove();
        });
    });
});
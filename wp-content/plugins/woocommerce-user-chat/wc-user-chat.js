jQuery(function ($) {

    const $messages = $('#wc-chat-messages');
    const $input = $('#wc-chat-input');

    // -----------------------------
    // Рендер HTML сообщений
    // -----------------------------
    function renderMessages(html) {
        if (!$messages.length) return;

        $messages.html(html);

        if ($messages[0]) {
            $messages.scrollTop($messages[0].scrollHeight);
        }
    }

    // -----------------------------
    // Загрузка сообщений (история + обновления)
    // -----------------------------
    function loadMessages() {
        $.post(wcUserChat.ajax_url, {
            action: 'wc_get_chat',
            nonce: wcUserChat.nonce
        }, function (res) {
            if (res.success && res.data.html !== undefined) {
                renderMessages(res.data.html);
            }
        });
    }

    // -----------------------------
    // Обертка + file input
    // -----------------------------
    const $chatWrapper = $('<div class="wc-chat-wrapper"></div>');
    $input.wrap($chatWrapper);

    const $fileInput = $('<input type="file" id="wc-chat-file" />');
    $input.before($fileInput);

    // -----------------------------
    // Отправка сообщения
    // -----------------------------
    $('#wc-chat-send').on('click', function () {

        const text = $input.val().trim();
        const fileEl = $('#wc-chat-file')[0];

        if (!text && !fileEl.files.length) return;

        const formData = new FormData();
        formData.append('action', 'wc_send_chat');
        formData.append('message', text);
        formData.append('nonce', wcUserChat.nonce);

        if (fileEl.files.length > 0) {
            formData.append('file', fileEl.files[0]);
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
    // Автообновление
    // -----------------------------
    setInterval(loadMessages, 30000);

    // -----------------------------
    // Первая загрузка
    // -----------------------------
    loadMessages();
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

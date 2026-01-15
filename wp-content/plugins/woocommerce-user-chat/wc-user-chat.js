jQuery(function ($) {

    const $messages = $('#wc-chat-messages');
    const $input = $('#wc-chat-input');
    const $file = $('#wc-chat-file');

    // -----------------------------
    // Рендер сообщений
    // -----------------------------
    function renderMessages(html) {
        $messages.html(html);
        // $messages.scrollTop($messages[0].scrollHeight);
    }

    // -----------------------------
    // Загрузка сообщений
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
    // Отправка сообщения
    // -----------------------------
    $('#wc-chat-send').on('click', function () {

        const text = $input.val().trim();
        const fileEl = $file[0];

        if (!text && !fileEl.files.length) return;

        const formData = new FormData();
        formData.append('action', 'wc_send_chat');
        formData.append('nonce', wcUserChat.nonce);
        formData.append('message', text);

        if (fileEl.files.length) {
            formData.append('file', fileEl.files[0]);
        }

        $.ajax({
            url: wcUserChat.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success(res) {
                if (res.success) {
                    $input.val('');
                    $file.val('');
                    loadMessages();
                } else {
                    alert(res.data?.message || 'Ошибка отправки');
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
                $messages.empty();
                $file.val('');
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

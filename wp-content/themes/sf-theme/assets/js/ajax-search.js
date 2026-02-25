jQuery(function ($) {

    let timer;

    const input = $('.wp-block-search__input');
    const results = $('.results');
    //const closeBtn = $('.close');

    input.on('keyup', function () {

        let term = $(this).val();

        clearTimeout(timer);

        if (term.length < 2) {
            results.hide().html('');
            //closeBtn.hide();
            return;
        }

        timer = setTimeout(function () {

            $.ajax({
                url: ajax_search_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'ajax_product_search',
                    term: term
                },
                success: function (response) {
                    results.html(response).show();
                    //closeBtn.show();
                }
            });

        }, 300);

    });

    // closeBtn.on('click', function () {
    //     input.val('');
    //     results.hide().html('');
    //     $(this).hide();
    // });

});
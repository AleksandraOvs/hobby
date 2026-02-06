(function ($) {

    acf.addAction('select2_init', function ($select, args, settings) {

        if (settings.fieldName !== 'work_products_link') return;

        let lastCategory = null;

        $select.find('option').each(function () {

            const text = $(this).text();

            // ищем нашу метку категории из PHP
            const match = text.match(/^📁 (.*?) → (.*)$/);

            if (!match) return;

            const category = match[1];
            const title = match[2];

            // если категория изменилась — добавляем псевдо-заголовок
            if (category !== lastCategory) {
                $(this).before(
                    $('<option disabled class="acf-cat">')
                        .text('— ' + category + ' —')
                );
                lastCategory = category;
            }

            $(this).text(' ' + title);

        });

    });

})(jQuery);
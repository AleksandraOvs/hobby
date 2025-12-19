<?php

/**
 * Title: Скрипт для якорных ссылок
 * Slug: sf-theme/hrefs
 * Categories: text, layout
 * Description: Собирает h2 на странице в меню якорных ссылок
 */
?>

<!-- wp:group {"align":"full"} -->
<div class="wp-block-group alignfull">

    <!-- wp:html -->
    <ul id="toc"></ul>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const content = document.querySelector('.page-content');
            const toc = document.getElementById('toc');

            if (!content || !toc) return;

            const headings = content.querySelectorAll('h2');
            if (!headings.length) return;

            headings.forEach((heading, index) => {
                // если id нет — создаём
                if (!heading.id) {
                    heading.id = 'section-' + (index + 1);
                }

                const li = document.createElement('li');
                const a = document.createElement('a');

                a.href = '#' + heading.id;
                a.textContent = heading.textContent.trim();

                li.appendChild(a);
                toc.appendChild(li);
            });
        });
    </script>
    <!-- /wp:html -->

</div>
<!-- /wp:group -->
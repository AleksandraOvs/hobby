<?php
/*
 * Template name: Страница О компании
 */
get_header() ?>
<section class="page">
    <?php get_template_part('template-parts/elements/page-header');
    ?>
    <div class="page-content">
        <div class="container">
            <div class="page-title">О компании</div>


            <h1><?php the_title() ?></h1>


            <?php if ($show_toc): ?>
                <div class="hrefs-block">
                    <ul id="toc">
                        <p>Содержание статьи</p>
                    </ul>
                </div>
            <?php endif; ?>

            <?php the_content(); ?>

        </div>
    </div>
</section>

<?php get_template_part('template-parts/section-contacts') ?>

<?php if ($show_toc): ?>
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
<?php endif; ?>

<?php get_footer() ?>
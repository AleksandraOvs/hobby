<?php
/*
 * Template name: Страница Документы
 */
get_header() ?>
<section class="page">
    <?php get_template_part('template-parts/elements/page-header'); ?>
    <div class="page-content">
        <div class="container">
            <div class="page-title">Документы</div>

            <?php the_content(); ?>

        </div>
    </div>
</section>

<?php get_template_part('template-parts/section-contacts') ?>


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

<?php get_footer() ?>
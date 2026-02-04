<?php
/*
 * Template name: Страница О компании
 */
get_header() ?>
<section class="page">
    <?php get_template_part('template-parts/elements/page-header'); ?>
    <div class="page-content">
        <div class="container">
            <div class="page-title">О компании</div>

            <?php
            // Получаем текущую страницу
            $current_id = get_the_ID();

            // Определяем родительскую страницу раздела
            $parent_id = wp_get_post_parent_id($current_id);
            $root_id = $parent_id ? $parent_id : $current_id;

            // Получаем все дочерние страницы родителя
            $children = get_pages([
                'child_of'    => $root_id,
                'sort_column' => 'menu_order',
                'post_status' => 'publish'
            ]);
            ?>

            <nav class="docs-nav">
                <ul>

                    <!-- Ссылка на родительскую страницу -->
                    <li class="<?php echo ($current_id == $root_id) ? 'active' : ''; ?>">
                        <a href="<?php echo get_permalink($root_id); ?>">
                            <?php echo get_the_title($root_id); ?>
                        </a>
                    </li>

                    <!-- Ссылки на дочерние страницы -->
                    <?php foreach ($children as $page): ?>
                        <li class="<?php echo ($current_id == $page->ID) ? 'active' : ''; ?>">
                            <a href="<?php echo get_permalink($page->ID); ?>">
                                <?php echo esc_html($page->post_title); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>

                </ul>
            </nav>

            <?php $show_toc = get_post_meta(get_the_ID(), '_show_page_toc', true); ?>
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
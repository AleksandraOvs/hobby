<section class="section-works">
    <div class="container">

        <h2>Работы наших клиентов</h2>
        <?php
        $args = [
            'post_type'      => 'works',
            'posts_per_page' => 8,
            'post_status'    => 'publish',
            'paged'          => 1,
        ];

        $query = new WP_Query($args);

        if ($query->have_posts()) :
            echo '<div class="works-list">';
            while ($query->have_posts()) : $query->the_post();
        ?>
                <?php get_template_part('template-parts/work-item'); ?>
        <?php
            endwhile;
            echo '</div>';
            wp_reset_postdata();
        endif;
        ?>

        <?php if ($query->max_num_pages > 1): ?>
            <button class="btn" id="load-more-works"
                data-page="1"
                data-max="<?php echo $query->max_num_pages; ?>">
                Посмотреть ещё
            </button>
        <?php endif; ?>
    </div>

</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const button = document.getElementById('load-more-works');
        if (!button) return;

        button.addEventListener('click', function() {
            let page = parseInt(this.dataset.page);
            let max = parseInt(this.dataset.max);

            const data = new FormData();
            data.append('action', 'load_more_works');
            data.append('page', page);

            button.classList.add('is-loading');
            button.textContent = 'Загружаем…';

            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                    method: 'POST',
                    body: data
                })
                .then(res => res.text())
                .then(html => {
                    if (!html.trim()) return;

                    const container = document.querySelector('.works-list');
                    const temp = document.createElement('div');
                    temp.innerHTML = html;

                    const items = temp.querySelectorAll('.work-item');

                    items.forEach((item, index) => {
                        item.style.transitionDelay = `${index * 80}ms`;
                        container.appendChild(item);

                        requestAnimationFrame(() => {
                            item.classList.add('is-visible');
                        });
                    });

                    Fancybox.bind('[data-fancybox="works-gallery"]');

                    button.dataset.page = page + 1;
                    button.classList.remove('is-loading');
                    button.textContent = 'Посмотреть ещё';

                    if (page + 1 >= max) {
                        button.remove();
                    }
                });
        });

        // анимация для первых 8 элементов
        document.querySelectorAll('.work-item').forEach((item, index) => {
            item.style.transitionDelay = `${index * 80}ms`;
            requestAnimationFrame(() => {
                item.classList.add('is-visible');
            });
        });
    });
</script>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        Fancybox.bind("[data-fancybox]", {
            on: {
                done: (fancybox, slide) => {
                    const modal = slide.$content;
                    const swiperEl = modal.querySelector('.work-modal-swiper');

                    if (swiperEl && !swiperEl.swiper) {
                        new Swiper(swiperEl, {
                            loop: true,
                            slidesPerView: 1,
                            spaceBetween: 16,
                            navigation: {
                                nextEl: modal.querySelector('.work-modal-next'),
                                prevEl: modal.querySelector('.work-modal-prev'),
                            },
                            pagination: {
                                el: modal.querySelector('.work-modal-pagination'),
                                clickable: true,
                            }
                        });
                    }
                }
            }
        });
    });
</script>
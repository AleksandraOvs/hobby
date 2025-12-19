<?php
/*
Template Name: Категории товаров
*/
get_header() ?>

<section class="page">
    <?php get_template_part('template-parts/elements/page-header'); ?>
    <div class="page-content">
        <div class="container">
            <h1>Каталог</h1>

            <div class="product-categories">

                <div id="categories-list"
                    data-offset="0"
                    data-limit="8">
                    <?php
                    // первый вывод
                    get_template_part('template-parts/product', 'categories', [
                        'offset' => 0,
                        'limit'  => 8
                    ]);
                    ?>
                </div>

                <!-- <button id="load-more-categories">Показать ещё</button> -->

            </div>

        </div>
    </div>
</section>

<?php get_template_part('template-parts/section-contacts') ?>

<?php get_footer() ?>
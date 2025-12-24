<div class="sidebar-area-wrapper">

    <!-- Start Single Sidebar -->
    <?php //$product_categories = get_terms(array('taxonomy' => 'product_cat', 'hide_empty' => false, 'show_count' => false)); 
    ?>

    <?php $product_pokrytie = get_terms(array('taxonomy' => 'pa_pokrytie', 'hide_empty' => false)); ?>

    <?php
    if ($product_pokrytie) :

        $shop_page_url = get_permalink(wc_get_page_id('shop'));
        if (!empty($_GET['min_price'])) {
            $shop_page_url = add_query_arg('min_price', $_GET['min_price'], $shop_page_url);
        }

        if (!empty($_GET['max_price'])) {
            $shop_page_url = add_query_arg('max_price', $_GET['max_price'], $shop_page_url);
        }
    ?>
        <div class="single-sidebar-wrap">
            <h3 class="sidebar-title">Покрытие</h3>
            <div class="sidebar-body">
                <ul class="sidebar-list">
                    <?php foreach ($product_pokrytie as $product_pokr) : ?>
                        <li><a href="<?php echo add_query_arg('filter_pokrytie', $product_pokr->slug, $shop_page_url) ?>"
                                <?php if (isset($_GET['filter_pokrytie']) && $product_pokr->slu == $_GET['filter_pokrytie']) : ?>class="active" <?php endif; ?>><?php echo $product_pokr->name ?></span></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>
    <!-- End Single Sidebar -->

    <!-- Start Single Sidebar -->
    <div class="single-sidebar-wrap">
        <h3 class="sidebar-title">Цена</h3>
        <div class="sidebar-body">
            <div class="price-range-wrap">
                <div class="price-range" data-min="10" data-max="100000"></div>
                <div class="range-slider">
                    <form action="" id="price_filter" method="GET">
                        <label for="amount">Цена: </label>
                        <input type="text" id="amount" />
                        <input type="hidden" id="min_price" name="min_price" value="<?php echo isset($_GET['min_price']) ? intval($_GET['min_price']) : 10 ?>" />
                        <input type="hidden" id="max_price" name="max_price" value="<?php echo isset($_GET['max_price']) ? intval($_GET['max_price']) : 100000 ?>" />
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Single Sidebar -->
</div>
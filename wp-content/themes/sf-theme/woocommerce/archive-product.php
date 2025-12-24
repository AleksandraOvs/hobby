<?php

/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined('ABSPATH') || exit;

get_header(); ?>

<div class="page-header-wrapper">
	<div class="container">
		<?php site_breadcrumbs() ?>
	</div>
</div>

<!--== Start Shop Page Wrapper ==-->
<div id="shop-page-wrapper" class="archive page-content woo-page">
	<div class="container">
		<h1><?php woocommerce_page_title() ?></h1>

		<?php get_sidebar('shop-top') ?>

		<div class="woo-page__content">

			<?php get_sidebar('shop') ?>
			<!-- Start Shop Page Product Area -->
			<div class="product-area">
				<?php if (woocommerce_product_loop()) { ?>
					<?php woocommerce_output_all_notices() ?>
					<!-- Start Product Config Area -->

					<div class="product-config-area d-md-flex justify-content-between align-items-center">
						<div class="product-config-right d-flex align-items-center mt-sm-14">
							<!-- <ul class="product-view-mode">
								<li data-viewmode="grid-view" class="active"><i class="fa fa-th"></i></li>
								<li data-viewmode="list-view"><i class="fa fa-list"></i></li>
							</ul> -->
							<ul class="product-filter-sort">
								<li class="dropdown-show sort-by">
									<button class="arrow-toggle">Сортировать по</button>
									<ul class="dropdown-nav">
										<li><a href="?orderby=date" <?php if (isset($_GET['orderby']) && 'date' == $_GET['orderby']) : ?> class="active" <?php endif; ?>>Сначала новые</a></li>
										<li><a href="?orderby=popularity" <?php if (isset($_GET['orderby']) && 'date' == $_GET['orderby']) : ?> class="active" <?php endif; ?>>По популярности</a></li>
										<li><a href="?orderby=rating" <?php if (isset($_GET['orderby']) && 'rating' == $_GET['orderby']) : ?> class="active" <?php endif; ?>>По среднему рейтингу</a></li>
										<li><a href="?orderby=price" <?php if (isset($_GET['orderby']) && 'price' == $_GET['orderby']) : ?> class="active" <?php endif; ?>>По цене &uarr;</a></li>
										<li><a href="?orderby=price-desc" <?php if (isset($_GET['orderby']) && 'price-desc' == $_GET['orderby']) : ?> class="active" <?php endif; ?>>По цене &darr;</a></li>
									</ul>
								</li>
							</ul>
						</div>
						<div class="product-config-left d-sm-flex">
							<?php woocommerce_result_count() ?>
						</div>

					</div>
					<!-- End Product Config Area -->

					<!-- Start Product Wrapper -->
					<?php woocommerce_product_loop_start(); ?>


					<?php
					if (wc_get_loop_prop('total')) {
						while (have_posts()) {
							the_post();

							/**
							 * Hook: woocommerce_shop_loop.
							 */
							do_action('woocommerce_shop_loop');

							wc_get_template_part('content', 'product');
						}
					}
					?>

					<?php woocommerce_product_loop_end(); ?>

				<?php
				} else {
					wc_no_products_found();
				}
				?>

				<!-- End Product Wrapper -->

				<!-- Page Pagination Start  -->
				<?php if (wc_get_loop_prop('total_pages') > 1) : ?>
					<div class="load-more-wrapper">
						<button id="load-more-btn" class="btn">
							<span class="btn-content">
								<svg width="14" height="23" viewBox="0 0 14 23" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" clip-rule="evenodd" d="M11.1652 14.4953C11.5893 14.0474 12.2979 14.0277 12.7458 14.4518C13.1937 14.8755 13.2129 15.5841 12.7893 16.032L7.36374 21.7746C6.93968 22.224 6.22837 22.2467 5.77898 21.8226C3.94705 19.9907 2.09584 17.9237 0.305852 16.032C-0.118211 15.5841 -0.0989344 14.8755 0.34894 14.4518C0.796814 14.0277 1.50547 14.0474 1.92954 14.4953L6.54736 19.3739L11.1652 14.4953Z" fill="#674126" />
									<path d="M5.5095 1.11458C5.51101 0.49738 6.01558 -0.00150789 6.63278 3.95061e-06C7.24998 0.00151579 7.7485 0.50645 7.74736 1.12327L7.66648 21.0108C7.66497 21.628 7.16002 22.1269 6.54283 22.1254C5.92563 22.1238 5.42711 21.6189 5.42862 21.0017L5.5095 1.11458Z" fill="#674126" />
								</svg>
								Показать ещё
							</span>
							<span class="btn-loader" aria-hidden="true">
								<span></span>
								<span></span>
								<span></span>
							</span>
						</button>
					</div>
				<?php endif; ?>

				<div class="page-pagination-wrapper">

					<?php
					$total = isset($total) ? $total : wc_get_loop_prop('total_pages');
					$current = isset($current) ? $current : wc_get_loop_prop('current_page');
					$base = isset($base) ? $base : esc_url_raw(str_replace(999999999, '%#%', remove_query_arg('add-to-cart', get_pagenum_link(999999999, false))));
					$format = isset($format) ? $format : '';

					// if ($total <= 1) {
					// 	return;
					// }
					?>
					<?php if ($total > 1) : ?>
						<nav class="page-pagination" aria-label="<?php esc_attr_e('Product Pagination', 'woocommerce'); ?>">
							<?php
							echo paginate_links(
								apply_filters(
									'woocommerce_pagination_args',
									array(
										'base'      => $base,
										'format'    => $format,
										'add_args'  => false,
										'current'   => max(1, $current),
										'total'     => $total,
										'prev_text' => '<span class="arrow-prev"></span>',
										'next_text' => '<span class="arrow-next"></span>',
										'type'      => 'list',
										'end_size'  => 3,
										'mid_size'  => 3,
									)
								)
							);
							?>
						</nav>
					<?php endif; ?>


				</div>
				<!-- Page Pagination End  -->
			</div>
			<!-- End Shop Page Product Area -->

		</div>
	</div>
</div>
<!--== End Shop Page Wrapper ==-->

<?php get_footer() ?>
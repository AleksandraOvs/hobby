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
		<?php woocommerce_breadcrumb() ?>
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
						<button class="toggle-filter">
							<svg width="18" height="11" viewBox="0 0 18 11" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M16.8433 1.52234C17.1564 1.52234 17.4106 1.77662 17.4106 2.08972C17.4106 2.40283 17.1564 2.6571 16.8433 2.6571H0.567871C0.254765 2.6571 0.000488281 2.40283 0.000488281 2.08972C0.000488281 1.77662 0.254765 1.52234 0.567871 1.52234H16.8433Z" fill="#BDBFC1" />
								<path d="M5.78333 4.15748C6.93139 4.15748 7.86207 3.2268 7.86207 2.07874C7.86207 0.930684 6.93139 0 5.78333 0C4.63527 0 3.70459 0.930684 3.70459 2.07874C3.70459 3.2268 4.63527 4.15748 5.78333 4.15748Z" fill="#BDBFC1" />
								<path d="M16.8428 8.22119C17.1559 8.22119 17.4102 8.47547 17.4102 8.78857C17.4102 9.10168 17.1559 9.35596 16.8428 9.35596H0.567383C0.254277 9.35596 0 9.10168 0 8.78857C0 8.47547 0.254277 8.22119 0.567383 8.22119H16.8428Z" fill="#BDBFC1" />
								<path d="M11.6276 10.8563C12.7756 10.8563 13.7063 9.92565 13.7063 8.77759C13.7063 7.62954 12.7756 6.69885 11.6276 6.69885C10.4795 6.69885 9.54883 7.62954 9.54883 8.77759C9.54883 9.92565 10.4795 10.8563 11.6276 10.8563Z" fill="#BDBFC1" />
							</svg>
							<span>Фильтр</span>
						</button>

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
					<!-- Page Pagination Start  -->



				<?php
				} else {
					wc_no_products_found();
				}
				?>

				<!-- End Product Wrapper -->


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
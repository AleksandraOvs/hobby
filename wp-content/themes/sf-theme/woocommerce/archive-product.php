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

get_header();
?>

<!-- Start Page Header Wrapper -->
<div class="page-header-wrapper">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 text-center">
				<div class="page-header-content">
					<h2><?php woocommerce_page_title(); ?></h2>
					<?php site_breadcrumbs() ?>
					<!-- <nav class="page-breadcrumb">
						<ul class="d-flex justify-content-center">
							<li><a href="index.html">Главная</a></li>
							<li><a href="index.html" class="active">Каталог</a></li>
						</ul>
					</nav> -->
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Page Header Wrapper -->

<!--== Start Shop Page Wrapper ==-->
<div id="shop-page-wrapper" class="pt-86 pt-md-56 pt-sm-46 pb-50 pb-md-20 pb-sm-10">
	<div class="container">
		<div class="row">
			<?php get_sidebar('shop') ?>

			<!-- Start Shop Page Product Area -->
			<div class="col-lg-9">
				<?php if (woocommerce_product_loop()) { ?>
					<?php woocommerce_output_all_notices() ?>
					<!-- Start Product Config Area -->

					<div class="product-config-area d-md-flex justify-content-between align-items-center">
						<div class="product-config-right d-flex align-items-center mt-sm-14">
							<ul class="product-view-mode">
								<li data-viewmode="grid-view" class="active"><i class="fa fa-th"></i></li>
								<li data-viewmode="list-view"><i class="fa fa-list"></i></li>
							</ul>
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
				<div class="page-pagination-wrapper mt-70 mt-md-50 mt-sm-40">

					<?php
					$total = isset($total) ? $total : wc_get_loop_prop('total_pages');
					$current = isset($current) ? $current : wc_get_loop_prop('current_page');
					$base = isset($base) ? $base : esc_url_raw(str_replace(999999999, '%#%', remove_query_arg('add-to-cart', get_pagenum_link(999999999, false))));
					$format = isset($format) ? $format : '';

					if ($total <= 1) {
						return;
					}
					?>
					<nav class="page-pagination" aria-label="<?php esc_attr_e('Product Pagination', 'woocommerce'); ?>">
						<?php
						echo paginate_links(
							apply_filters(
								'woocommerce_pagination_args',
								array( // WPCS: XSS ok.
									'base'      => $base,
									'format'    => $format,
									'add_args'  => false,
									'current'   => max(1, $current),
									'total'     => $total,
									'prev_text' => '<i class="fa fa-angle-double-left"></i>',
									'next_text' => '<i class="fa nfa-angle-double-right"></i>',
									'type'      => 'list',
									'end_size'  => 3,
									'mid_size'  => 3,
								)
							)
						);
						?>
					</nav>


				</div>
				<!-- Page Pagination End  -->
			</div>
			<!-- End Shop Page Product Area -->
		</div>
	</div>
</div>
<!--== End Shop Page Wrapper ==-->

<?php get_footer() ?>
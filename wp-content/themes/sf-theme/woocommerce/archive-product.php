<?php

/**
 * The Template for displaying products in a product category. Simply includes the archive template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/taxonomy-product-cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     4.7.0
 */

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

//wc_get_template( 'archive-product.php' );
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

			<?php get_sidebar('shop-cat')
			?>
			<!-- Start Shop Page Product Area -->
			<div class="product-area">
				<?php
				$categories = get_terms([
					'taxonomy'   => 'product_cat',
					'hide_empty' => true,
					'parent'     => 0, // только верхний уровень
					'exclude'    => [get_term_by('slug', 'misc', 'product_cat')->term_id]
				]);

				if (!empty($categories) && !is_wp_error($categories)) :

					foreach ($categories as $category) :

						$args = [
							'post_type'      => 'product',
							'posts_per_page' => -1,
							'tax_query'      => [
								[
									'taxonomy' => 'product_cat',
									'field'    => 'term_id',
									'terms'    => $category->term_id,
								]
							]
						];

						$products = new WP_Query($args);

						if ($products->have_posts()) :
				?>

							<div class="category-block">
								<h2 class="category-title">
									<?php echo esc_html($category->name); ?>
								</h2>

								<?php woocommerce_product_loop_start(); ?>

								<?php
								while ($products->have_posts()) :
									$products->the_post();
									wc_get_template_part('content', 'product');
								endwhile;
								?>

								<?php woocommerce_product_loop_end(); ?>
							</div>

				<?php
						endif;

						wp_reset_postdata();

					endforeach;

				endif;
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
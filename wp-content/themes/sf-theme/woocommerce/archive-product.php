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

			<?php //get_sidebar('shop-cat')
			?>

			<nav id="catalog-navigation" class="catalog-navigation">
				<?php //wp_nav_menu([
				//'container' => false,
				//'theme_location'  => 'cat_menu',
				//'walker' => new My_Custom_Walker_Nav_Menu,
				//'depth'           => 0,
				//]); 
				?>

				<?php
				$terms = get_terms([
					'taxonomy'   => 'product_cat',
					'hide_empty' => false,
					'parent'     => 0,
				]);

				if (empty($terms) || is_wp_error($terms)) {
					return;
				}

				echo '<ul class="menu">';

				foreach ($terms as $term) {

					// Проверяем, есть ли дети
					$children = get_terms([
						'taxonomy'   => 'product_cat',
						'hide_empty' => false,
						'number'     => 1,
					]);


					$li_classes = ['menu-item'];

					echo '<li class="' . esc_attr(implode(' ', $li_classes)) . '">';

					// ======= Получаем иконку через ACF ======= //
					$icon_url = '';

					$icon_id = get_field('category_icon', 'product_cat_' . $term->term_id);
					if ($icon_id) {
						$icon_url = wp_get_attachment_image_url($icon_id, 'thumbnail');
					}


					echo '<a href="' . esc_url(get_term_link($term)) . '">';
					if ($icon_url) {
						echo '<img class="cat-icon" src="' . esc_url($icon_url) . '" alt="' . esc_attr($term->name) . '">';
					}
					echo esc_html($term->name);
					echo '</a>';

					echo '</li>';
				}

				echo '</ul>';


				?>

			</nav><!-- #site-navigation -->

			<!-- Start Shop Page Product Area -->
			<div id="categories-list"
				data-offset="0"
				data-limit="8">

				<?php
				$misc = get_term_by('slug', 'misc', 'product_cat');
				$exclude = $misc ? [$misc->term_id] : [];

				$parents = get_terms([
					'taxonomy'   => 'product_cat',
					'parent'     => 0,
					'hide_empty' => true,
					'exclude'    => $exclude,
				]);

				if (!empty($parents) && !is_wp_error($parents)) :

					foreach ($parents as $parent) :
				?>

						<div class="categories-list__item">

							<h2 class="parent-category-title">
								<a href="<?php echo esc_url(get_term_link($parent)); ?>">
									<?php echo esc_html($parent->name); ?>
								</a>
							</h2>

							<?php
							$children = get_terms([
								'taxonomy'   => 'product_cat',
								'parent'     => $parent->term_id,
								'hide_empty' => false,
								'exclude'    => $exclude,
							]);

							if (!empty($children) && !is_wp_error($children)) :
							?>

								<div class="subcategory-grid">

									<?php
									$limit = 8;
									$index = 0;

									foreach ($children as $child) :

										$thumb_id = get_term_meta($child->term_id, 'thumbnail_id', true);
										$image_url = $thumb_id
											? wp_get_attachment_image_url($thumb_id, 'medium')
											: get_stylesheet_directory_uri() . '/assets/img/svg/placeholder.svg';

										$hidden_class = $index >= $limit ? ' is-hidden' : '';
									?>

										<div class="subcategory-item<?php echo $hidden_class; ?>">

											<a href="<?php echo esc_url(get_term_link($child)); ?>"
												class="subcategory-image">

												<img src="<?php echo esc_url($image_url); ?>"
													alt="<?php echo esc_attr($child->name); ?>">

											</a>

											<div class="subcategory-title">
												<a href="<?php echo esc_url(get_term_link($child)); ?>">
													<?php echo esc_html($child->name); ?>
												</a>
												<span class="subcategory-count">
													(<?php echo esc_html($child->count); ?>)
												</span>
											</div>

										</div>

									<?php
										$index++;
									endforeach;
									?>

								</div>

								<?php if (count($children) > $limit) : ?>
									<button class="load-more-subcats" data-step="8">
										<svg width="14" height="23" viewBox="0 0 14 23" fill="none"
											xmlns="http://www.w3.org/2000/svg">
											<path fill-rule="evenodd" clip-rule="evenodd"
												d="M11.1652 14.4953C11.5893 14.0474 12.2979 14.0277 12.7458 14.4518C13.1937 14.8755 13.2129 15.5841 12.7893 16.032L7.36374 21.7746C6.93968 22.224 6.22837 22.2467 5.77898 21.8226C3.94705 19.9907 2.09584 17.9237 0.305852 16.032C-0.118211 15.5841 -0.0989344 14.8755 0.34894 14.4518C0.796814 14.0277 1.50547 14.0474 1.92954 14.4953L6.54736 19.3739L11.1652 14.4953Z"
												fill="#674126" />
											<path d="M5.5095 1.11458C5.51101 0.49738 6.01558 -0.00150789 6.63278 3.95061e-06C7.24998 0.00151579 7.7485 0.50645 7.74736 1.12327L7.66648 21.0108C7.66497 21.628 7.16002 22.1269 6.54283 22.1254C5.92563 22.1238 5.42711 21.6189 5.42862 21.0017L5.5095 1.11458Z"
												fill="#674126" />
										</svg>
										Показать ещё
									</button>
								<?php endif; ?>

							<?php endif; ?>

						</div>

				<?php
					endforeach;
				endif;
				?>

			</div>
			<!-- End Shop Page Product Area -->

		</div>
	</div>
</div>
<!--== End Shop Page Wrapper ==-->

<?php get_footer() ?>
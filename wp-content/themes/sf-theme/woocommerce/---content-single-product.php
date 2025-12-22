<?php

/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;
?>

<!-- Start Page Header Wrapper -->
<div class="page-header-wrapper">
	<div class="container">
		<?php woocommerce_breadcrumb() ?>
	</div>
</div>

<div class="page-content woo-page">
	<div class="container">
		<h1><?php the_title() ?></h1>
		<div id="single-product" <?php wc_product_class('', $product); ?>>
			<?php do_action('woocommerce_before_single_product'); ?>
			<div class="single-product__inner">













			</div>

			<div class="row">
				<!-- Start Single Product Thumbnail -->
				<div class="col-xl-7 col-lg-6">


				</div>
				<!-- End Single Product Thumbnail -->

				<!-- Start Single Product Content -->
				<div class="col-xl-5 col-lg-6">
					<div class="single-product-content-wrapper">


						<div class="product-description-review">
							<!-- Product Description Tab Menu -->
							<ul class="nav nav-tabs desc-review-tab-menu" id="desc-review-tab" role="tablist">
								<li>
									<a class="active" id="desc-tab" data-toggle="tab" href="#descriptionContent" role="tab">Описание</a>
								</li>
								<li>
									<a id="profile-tab" data-toggle="tab" href="#reviewContent">Отзывы</a>
								</li>
							</ul>
							<div class="tab-content" id="myTabContent">
								<div class="tab-pane fade show active" id="descriptionContent">
									<div class="description-content">
										<p class="m-0"><?php echo $product->get_description() ?></p>
									</div>
								</div>

								<div class="tab-pane fade" id="reviewContent">
									<div class="product-ratting-wrap">
										<div class="pro-avg-ratting">
											<h4>4.5 <span>(всего)</span></h4>
											<span>На основани 9 отзывов</span>
										</div>
										<div class="rattings-wrapper">

											<div class="sin-rattings">
												<div class="ratting-author">
													<h3>Александр</h3>
													<div class="ratting-star">
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<span>(5)</span>
													</div>
												</div>
												<p>enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit,
													sed quia res eos qui ratione voluptatem sequi Neque porro quisquam est,
													qui dolorem ipsum quia dolor sit amet, consectetur, adipisci veli</p>
											</div>

											<div class="sin-rattings">
												<div class="ratting-author">
													<h3>Николай</h3>
													<div class="ratting-star">
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<span>(5)</span>
													</div>
												</div>
												<p>enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit,
													sed quia res eos qui ratione voluptatem sequi Neque porro quisquam est,
													qui dolorem ipsum quia dolor sit amet, consectetur, adipisci veli</p>
											</div>

											<div class="sin-rattings">
												<div class="ratting-author">
													<h3>Ольга</h3>
													<div class="ratting-star">
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<span>(5)</span>
													</div>
												</div>
												<p>enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit,
													sed quia res eos qui ratione voluptatem sequi Neque porro quisquam est,
													qui dolorem ipsum quia dolor sit amet, consectetur, adipisci veli</p>
											</div>

										</div>
										<div class="ratting-form-wrapper">
											<h3>Добавить свой отзыв</h3>
											<form action="#" method="post">
												<div class="ratting-form row">
													<div class="col-12 mb-16">
														<h5>Рейтинг:</h5>
														<div class="ratting-star fix">
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
														</div>
													</div>
													<div class="col-md-6 col-12 mb-10">
														<label for="name">Имя:</label>
														<input id="name" placeholder="Name" type="text">
													</div>
													<div class="col-md-6 col-12 mb-10">
														<label for="email">Емайл:</label>
														<input id="email" placeholder="Email" type="text">
													</div>
													<div class="col-12">
														<label for="your-review">Что думаете:</label>
														<textarea name="review" id="your-review"
															placeholder="Write a review"></textarea>
													</div>
													<div class="col-12 mt-22">
														<button class="btn btn-black">Отправить</button>
													</div>
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="single-product-footer d-block d-sm-flex justify-content-between">
							<div class="prod-footer-left mb-xs-26">





								<?php
								echo wc_get_product_category_list(
									$product->get_id(),
									'<li> </li>',
									'<ul class="prod-footer-list"<li class="list-name">Категории:</li><li>',
									'</span>'
								);
								?>

							</div>

						</div>
					</div>
				</div>
				<!-- End Single Product Content -->
			</div>

		</div>


	</div>


	<!--== Start Related Products Area ==-->
	<section id="related-products-wrapper" class="pb-48 pb-md-18 pb-sm-8">
		<div class="container-fluid">
			<div class="row">
				<!-- Start Section title -->
				<div class="col-lg-8 m-auto text-center">
					<div class="section-title-wrap">
						<h2>Смотрите также</h2>
					</div>
				</div>
				<!-- End Section title -->
			</div>

			<div class="row products-on-column">
				<!-- Start Single Product -->
				<div class="col-sm-6 col-lg-3">
					<div class="single-product-wrap">
						<!-- Product Thumbnail -->
						<figure class="product-thumbnail">
							<a href="single-product.html" class="d-block">
								<img class="primary-thumb" src="assets/img/products/prod-1-1.jpg"
									alt="Product" />
								<img class="secondary-thumb" src="assets/img/products/prod-1-2.jpg"
									alt="Product" />
							</a>
							<figcaption class="product-hvr-content">
								<a href="#" class="btn btn-black btn-addToCart">Добавить в корзинку</a>

								<span class="product-badge">Новинка</span>
							</figcaption>
						</figure>

						<!-- Product Details -->
						<div class="product-details">
							<h2 class="product-name"><a href="single-product.html">Товар 1</a></h2>
							<div class="product-prices">
								<del class="oldPrice">5000 р</del>
								<span class="price">4000 р</span>
							</div>
						</div>
					</div>
				</div>
				<!-- End Single Product -->

				<!-- Start Single Product -->
				<div class="col-sm-6 col-lg-3">
					<div class="single-product-wrap">
						<!-- Product Thumbnail -->
						<figure class="product-thumbnail">
							<a href="single-product.html" class="d-block">
								<img class="primary-thumb" src="assets/img/products/prod-2-1.jpg"
									alt="Product" />
								<img class="secondary-thumb" src="assets/img/products/prod-2-2.jpg"
									alt="Product" />
							</a>
							<figcaption class="product-hvr-content">
								<a href="#" class="btn btn-black btn-addToCart">Добавить в корзину</a>

								<span class="product-badge">Новинка</span>
							</figcaption>
						</figure>

						<!-- Product Details -->
						<div class="product-details">
							<h2 class="product-name"><a href="single-product.html">Товар 2</a></h2>
							<div class="product-prices">
								<del class="oldPrice">5000 р</del>
								<span class="price">4000 р</span>
							</div>
						</div>
					</div>
				</div>
				<!-- End Single Product -->

				<!-- Start Single Product -->
				<div class="col-sm-6 col-lg-3">
					<div class="single-product-wrap">
						<!-- Product Thumbnail -->
						<figure class="product-thumbnail">
							<a href="single-product.html" class="d-block">
								<img class="primary-thumb" src="assets/img/products/prod-3-1.jpg"
									alt="Product" />
								<img class="secondary-thumb" src="assets/img/products/prod-3-2.jpg"
									alt="Product" />
							</a>
							<figcaption class="product-hvr-content">
								<a href="#" class="btn btn-black btn-addToCart">Добавить в корзину</a>

								<span class="product-badge">Новинка</span>
							</figcaption>
						</figure>

						<!-- Product Details -->
						<div class="product-details">
							<h2 class="product-name"><a href="single-product.html">Товар 3</a></h2>
							<div class="product-prices">
								<del class="oldPrice">5000 р</del>
								<span class="price">4000 р</span>
							</div>
						</div>
					</div>
				</div>
				<!-- End Single Product -->

				<!-- Start Single Product -->
				<div class="col-sm-6 col-lg-3">
					<div class="single-product-wrap">
						<!-- Product Thumbnail -->
						<figure class="product-thumbnail">
							<a href="single-product.html" class="d-block">
								<img class="primary-thumb" src="assets/img/products/prod-4-1.jpg"
									alt="Product" />
								<img class="secondary-thumb" src="assets/img/products/prod-4-2.jpg"
									alt="Product" />
							</a>
							<figcaption class="product-hvr-content">
								<a href="#" class="btn btn-black btn-addToCart">Добавить в корзину</a>

							</figcaption>
						</figure>

						<!-- Product Details -->
						<div class="product-details">
							<h2 class="product-name"><a href="single-product.html">Товар 4</a></h2>
							<div class="product-prices">
								<del class="oldPrice">5000 р</del>
								<span class="price">4000 р</span>
							</div>
						</div>
					</div>
				</div>
				<!-- End Single Product -->
			</div>
		</div>
	</section>
	<!--== End Related Products Area ==-->






	<?php

	/**
	 * Hook: woocommerce_before_single_product.
	 *
	 * @hooked woocommerce_output_all_notices - 10
	 */
	do_action('woocommerce_before_single_product');

	if (post_password_required()) {
		echo get_the_password_form(); // WPCS: XSS ok.
		return;
	}
	?>
	<div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>

		<?php
		/**
		 * Hook: woocommerce_before_single_product_summary.
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		do_action('woocommerce_before_single_product_summary');
		?>

		<div class="summary entry-summary">
			<?php
			/**
			 * Hook: woocommerce_single_product_summary.
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 * @hooked WC_Structured_Data::generate_product_data() - 60
			 */
			do_action('woocommerce_single_product_summary');
			?>
		</div>

		<?php
		/**
		 * Hook: woocommerce_after_single_product_summary.
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action('woocommerce_after_single_product_summary');
		?>
	</div>
</div>

<?php do_action('woocommerce_after_single_product'); ?>
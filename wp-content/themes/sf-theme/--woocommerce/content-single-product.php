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
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;


?><!-- Start Page Header Wrapper -->
<div class="page-header-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="page-header-content">
                    <h2><?php the_title() ?></h2>
                   	<?php woocommerce_breadcrumb() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page Header Wrapper -->

<!--== Start Single Product Page Wrapper ==-->
<div id="single-product-page" <?php wc_product_class( 'pt-90 pt-md-60 pt-sm-50 pb-92 pb-md-58 pb-sm-50', $product ); ?>>
		<?php do_action( 'woocommerce_before_single_product' ); ?>
    <div class="container-fluid">
        <div class="row">


						<!-- Start Single Product Thumbnail -->
            <div class="col-xl-7 col-lg-6">
								<?php
									$product_image_id = $product->get_image_id();
									$product_gallery_ids = $product->get_gallery_image_ids();
								?>
                <div class="single-product-thumb-wrap<?php if( $product_gallery_ids ) : ?> tab-style-left<?php endif; ?> p-0 pb-sm-30 pb-md-30">
                    <!-- Product Thumbnail Large View -->
                    <div class="product-thumb-large-view">
                        <div class="product-thumb-carousel vertical-tab">

                            <figure class="product-thumb-item">
															<?php echo wp_get_attachment_image( $product_image_id, 'woocommerce_single' ) ?>
                            </figure>

														<?php if( $product_gallery_ids ) : ?>
															<?php foreach( $product_gallery_ids as $product_gallery_id ) : ?>
		                            <figure class="product-thumb-item">
		                              <?php echo wp_get_attachment_image( $product_gallery_id, 'woocommerce_single' ) ?>
		                            </figure>
															<?php endforeach; ?>
														<?php endif; ?>

                        </div>


                    </div>

										<?php if( $product_gallery_ids ) : ?>
	                    <!-- Product Thumbnail Nav -->
	                    <div class="vertical-tab-nav">

													<figure class="product-thumb-item">
														<?php echo wp_get_attachment_image( $product_image_id, 'full' ) ?>
													</figure>

													<?php foreach( $product_gallery_ids as $product_gallery_id ) : ?>
														<figure class="product-thumb-item">
															<?php echo wp_get_attachment_image( $product_gallery_id, 'full' ) ?>
														</figure>
													<?php endforeach; ?>

	                    </div>
										<?php endif; ?>
                </div>
            </div>
            <!-- End Single Product Thumbnail -->


            <!-- Start Single Product Content -->
            <div class="col-xl-5 col-lg-6">
                <div class="single-product-content-wrapper">
                    <div class="single-product-details">
                        <h2 class="product-name"><?php the_title() ?></h2>
                        <div class="prices-stock-status d-flex align-items-center justify-content-between">
                            <div id="product_cena" class="prices-group">
                                <div class="price"><?php echo $product->get_price_html() ?></div>
                            </div>
														<?php if( $product->is_in_stock() ) : ?>
                            	<span class="stock-status"><i class="dl-icon-check-circle1"></i> В наличии</span>
														<?php else : ?>
															<span class="stock-status"> Нет в наличии</span>
														<?php endif; ?>
                        </div>
                        <p class="product-desc"><?php echo $product->get_short_description() ?></p>

												<?php woocommerce_template_single_add_to_cart() ?>

                    </div>

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
																	<?php
																		$count = $product->get_review_count();
																		if ( $count && wc_review_ratings_enabled() ) { ?>
                                    <div class="pro-avg-ratting">
                                        <h4><?php echo $product->get_average_rating() ?> <span>(всего)</span></h4>
                                        <span>На основании <?php echo $count ?> отзывов</span>
                                    </div>
																	<?php } ?>

																		<?php if( $reviews = get_comments( array( 'status' => 'approve', 'type' => 'review', 'post_id' => $product->get_id() ) ) ) : ?>
	                                    <div class="rattings-wrapper">

																				<?php foreach( $reviews as $comment ) : ?>
	                                        <div class="sin-rattings">
	                                            <div class="ratting-author">
	                                                <h3><?php echo $comment->comment_author ?></h3>

																									<?php if( $rating = get_comment_meta( $comment->comment_ID, 'rating', true ) ) : ?>
		                                                <div class="ratting-star">
																												<?php
																												$stars = 0;
																												while( $stars < $rating ) {

																											  	echo '<i class="fa fa-star"></i>';
																													$stars++;

																												}

																												?>
		                                                    <span>(<?php echo $rating ?>)</span>
		                                                </div>
																									<?php endif; ?>
	                                            </div>
	                                            <p><?php echo get_comment_text( $comment->comment_ID ) ?></p>
	                                        </div>
																				<?php endforeach; ?>



	                                    </div>
																		<?php endif; ?>
																		<?php if( is_user_logged_in() ) : ?>
	                                    <div class="ratting-form-wrapper">
	                                        <h3>Добавить свой отзыв</h3>
	                                        <form action="<?php echo site_url( 'wp-comments-post.php' ) ?>" method="post">
	                                            <div class="ratting-form row">
	                                                <div class="col-12 mb-16">
	                                                    <h5>Рейтинг:</h5>
	                                                    <div class="ratting-star fix">
	                                                        <i class="change-star fa fa-star" data-value="1"></i>
	                                                        <i class="change-star fa fa-star" data-value="2"></i>
	                                                        <i class="change-star fa fa-star" data-value="3"></i>
	                                                        <i class="change-star fa fa-star" data-value="4"></i>
	                                                        <i class="change-star fa fa-star" data-value="5"></i>
	                                                    </div>
																											<div style="display:none">
																												<select name="rating">
																													<option value="1">1</option>
																													<option value="2">2</option>
																													<option value="3">3</option>
																													<option value="4">4</option>
																													<option value="5">5</option>
																												</select>
																											</div>
	                                                </div>
	                                                <div class="col-12">
	                                                    <label for="your-review">Что думаете:</label>
	                                                    <textarea name="comment" id="your-review"
	                                                              placeholder="Write a review"></textarea>
	                                                </div>
	                                                <div class="col-12 mt-22">
	                                                    <button class="btn btn-black">Отправить</button>
																											<?php
																												comment_id_fields();
																												do_action( 'comment_form', $product->get_id() );

																											?>
	                                                </div>
	                                            </div>
	                                        </form>
	                                    </div>
																		<?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="single-product-footer d-block d-sm-flex justify-content-between">
                        <div class="prod-footer-left mb-xs-26">



														<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

															<span class="sku mb-6 d-block sku_wrapper">Артикул: <span><?php echo ( $sku = $product->get_sku() ) ? $sku : 'N/A'; ?></span></span>

														<?php endif; ?>


														<?php
															echo wc_get_product_category_list(
																$product->get_id(),
																'</li> <li>',
																'<ul class="prod-footer-list"><li class="list-name">Категории:</li><li>',
																'</li></ul>'
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
<!--== End Single Product Page Wrapper ==-->

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
						<?php
						$related_products = array(); // array( 5, 10, 35 )

						$upsells = $product->get_upsell_ids();

						if( $upsells ) {
							$related_products = $upsells;
						} else {
							$related_products = wc_get_related_products( $product->get_id(), 4 );
						}

						?>
						<?php foreach ( $related_products as $related_product ) : ?>

								<?php
								$post_object = get_post( $related_product );

								setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

								wc_get_template_part( 'content', 'product' );
								?>

						<?php endforeach; ?>

        </div>
    </div>
</section>
<!--== End Related Products Area ==-->

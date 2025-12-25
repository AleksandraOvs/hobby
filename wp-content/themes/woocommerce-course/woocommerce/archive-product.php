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
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;


get_header();
?><!-- Start Page Header Wrapper -->
<div class="page-header-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="page-header-content">
                    <h2><?php woocommerce_page_title(); ?></h2>
										<?php woocommerce_breadcrumb() ?>
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

					  <?php get_sidebar( 'shop' ) ?>

            <!-- Start Shop Page Product Area -->
            <div class="col-lg-9">


							<?php if ( woocommerce_product_loop() ) { ?>
                <!-- Start Product Config Area -->
								<?php woocommerce_output_all_notices() ?>
                <div class="product-config-area d-md-flex justify-content-between align-items-center">
                    <div class="product-config-left d-sm-flex">
											<?php woocommerce_result_count() ?>
                    </div>

                    <div class="product-config-right d-flex align-items-center mt-sm-14">
                        <ul class="product-view-mode">
                            <li data-viewmode="grid-view" class="active"><i class="fa fa-th"></i></li>
                            <li data-viewmode="list-view"><i class="fa fa-list"></i></li>
                        </ul>
                        <ul class="product-filter-sort">
													<li class="dropdown-show sort-by">
															<button class="arrow-toggle">Сортировать по</button>
															<ul class="dropdown-nav">
																	<li><a href="?orderby=date" <?php if( isset( $_GET['orderby'] ) && 'date' == $_GET['orderby'] ) : ?> class="active"<?php endif; ?>>Сначала новые</a></li>
																	<li><a href="?orderby=price"<?php if( isset( $_GET['orderby'] ) && 'price' == $_GET['orderby'] ) : ?> class="active"<?php endif; ?>>По цене &uarr;</a></li>
																	<li><a href="?orderby=price-desc"<?php if( isset( $_GET['orderby'] ) && 'price-desc' == $_GET['orderby'] ) : ?> class="active"<?php endif; ?>>По цене &darr;</a></li>
															</ul>
													</li>
                        </ul>
                    </div>
                </div>
                <!-- End Product Config Area -->

                <?php

								woocommerce_product_loop_start();

								if ( wc_get_loop_prop( 'total' ) ) {
									while ( have_posts() ) {
										the_post();

										wc_get_template_part( 'content', 'product' );
									}
								}

								woocommerce_product_loop_end();

							} else {

								wc_no_products_found();

							}

							?>

                <!-- Page Pagination Start  -->
                <div class="page-pagination-wrapper mt-70 mt-md-50 mt-sm-40">

										<?php
										$total   = isset( $total ) ? $total : wc_get_loop_prop( 'total_pages' );
										$current = isset( $current ) ? $current : wc_get_loop_prop( 'current_page' );
										$base    = isset( $base ) ? $base : esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
										$format  = isset( $format ) ? $format : '';

										if ( $total > 1 ) : ?>
										<nav class="page-pagination">
											<?php
											echo str_replace( '<ul class=\'page-numbers\'>', '<ul class="pagination justify-content-center">', paginate_links(
												apply_filters(
													'woocommerce_pagination_args',
													array( // WPCS: XSS ok.
														'base'      => $base,
														'format'    => $format,
														'add_args'  => false,
														'current'   => max( 1, $current ),
														'total'     => $total,
														'prev_text' => '<i class="fa fa-angle-double-left"></i>',
														'next_text' => '<i class="fa fa-angle-double-right"></i>',
														'type'      => 'list',
														'end_size'  => 3,
														'mid_size'  => 3,
													)
												) )
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

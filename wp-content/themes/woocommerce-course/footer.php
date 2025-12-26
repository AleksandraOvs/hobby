<!--== Start Footer Section ===-->
<footer id="footer-area">
    <!-- Start Footer Widget Area -->
    <div class="footer-widget-area pt-40 pb-28">
        <div class="container">
            <div class="footer-widget-content">
                <div class="row">

                    <!-- Start Footer Widget Item -->
                    <div class="col-sm-3 col-lg-3">
                        <div class="footer-widget-item-wrap">
                            <h3 class="widget-title">Каталог</h3>
                            <div class="widget-body">
															<?php
																wp_nav_menu(
																	array(
																		'theme_location' => 'foot_1',
																		'container' => false,
																		'menu_class' => 'footer-list',
																	)
																);
                              ?>
                            </div>
                        </div>
                    </div>
                    <!-- End Footer Widget Item -->

                    <!-- Start Footer Widget Item -->
                    <div class="col-sm-3 col-lg-3">
                        <div class="footer-widget-item-wrap">
                            <h3 class="widget-title">Страницы</h3>
                            <div class="widget-body">
															<?php
															 wp_nav_menu(
																 array(
																	 'theme_location' => 'foot_2',
																	 'container' => false,
																	 'menu_class' => 'footer-list',
																 )
															 );
															?>
                            </div>
                        </div>
                    </div>
                    <!-- End Footer Widget Item -->

                    <!-- Start Footer Widget Item -->
                    <div class="col-sm-3 col-lg-3">
                        <div class="footer-widget-item-wrap">
                            <h3 class="widget-title">Товары</h3>
                            <div class="widget-body">
															<?php
																wp_nav_menu(
																	array(
																		'theme_location' => 'foot_3',
																		'container' => false,
																		'menu_class' => 'footer-list',
																	)
																);
															?>
                            </div>
                        </div>
                    </div>
                    <!-- End Footer Widget Item -->

                    <!-- Start Footer Widget Item -->
                    <div class="col-sm-3 col-lg-3">
                        <div class="footer-widget-item-wrap">
                            <h3 class="widget-title">Напишите мне</h3>
                            <div class="widget-body">
                                <div class="contact-text">
                                    <a href="#">(+1) 234 56 78</a>
                                    <a href="#">me@misha.blog</a>
                                    <p>Санкт-Петербург, Невский пр.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Footer Widget Item -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Footer Widget Area -->

    <!-- Start Footer Bottom Area -->
    <div class="footer-bottom-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-sm-3 col-lg-3 m-auto order-1">
                    <div class="footer-social-icons nav justify-content-center justify-content-sm-start mb-xs-10">
                        <a href="#" target="_blank"><i class="fa fa-facebook"></i></a>
                        <a href="#" target="_blank"><i class="fa fa-twitter"></i></a>
                        <a href="#" target="_blank"><i class="fa fa-pinterest-p"></i></a>
                    </div>
                </div>

                <div class="col-sm-5 col-lg-6 m-auto order-3 order-sm-2 text-center text-sm-left text-lg-center">
                    <div class="copyright-text mt-xs-10 ">
                        <p>&copy; 2020 Курс WooCommerce от Миши Рудрастых.</p>
                    </div>
                </div>

                <div class="col-sm-4 col-lg-3 m-auto order-2 text-center text-md-right">
                    <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/payments.png" alt="Payment Method"/>
                </div>
            </div>
        </div>
    </div>
    <!-- End Footer Bottom Area -->
</footer>
<!--== End Footer Section ===-->

<?php if( ! is_cart() ) : ?>
	<!--== Start Mini Cart Wrapper ==-->
	<div class="mfp-hide modal-minicart" id="miniCart-popup">
	    <div class="minicart-content-wrap">
				<?php woocommerce_mini_cart() ?>
				
	        <!-- <h2>Корзина</h2>
	        <div class="minicart-product-list">

	            <div class="single-product-item d-flex">
	                <figure class="product-thumb">
	                    <a href="single-product.html"><img src="assets/img/products/prod-1-1.jpg" alt="Product"></a>
	                </figure>
	                <div class="product-details">
	                    <h2 class="product-title"><a href="single-product.html">Какой-то товар</a></h2>
	                    <div class="prod-cal d-flex align-items-center">
	                        <span class="quantity">1</span>
	                        <span class="multiplication">&#215;</span>
	                        <span class="price">9999 р</span>
	                    </div>
	                </div>
	                <a href="#" class="remove-icon">&#215;</a>
	            </div>

	            <div class="single-product-item d-flex">
	                <figure class="product-thumb">
	                    <a href="single-product.html"><img src="assets/img/products/prod-2-1.jpg" alt="Product"></a>
	                </figure>
	                <div class="product-details">
	                    <h2 class="product-title"><a href="single-product.html">Второй товар</a></h2>
	                    <div class="prod-cal d-flex align-items-center">
	                        <span class="quantity">2</span>
	                        <span class="multiplication">&#215;</span>
	                        <span class="price">3900 р</span>
	                    </div>
	                </div>
	                <a href="#" class="remove-icon">&#215;</a>
	            </div>

	            <div class="single-product-item d-flex">
	                <figure class="product-thumb">
	                    <a href="single-product.html"><img src="assets/img/products/prod-3-1.jpg" alt="Product"></a>
	                </figure>
	                <div class="product-details">
	                    <h2 class="product-title"><a href="single-product.html">Ещё один товар</a></h2>
	                    <div class="prod-cal d-flex align-items-center">
	                        <span class="quantity">1</span>
	                        <span class="multiplication">&#215;</span>
	                        <span class="price">3300 р</span>
	                    </div>
	                </div>
	                <a href="#" class="remove-icon">&#215;</a>
	            </div>

	            <div class="single-product-item d-flex">
	                <figure class="product-thumb">
	                    <a href="single-product.html"><img src="assets/img/products/prod-4-1.jpg" alt="Product"></a>
	                </figure>
	                <div class="product-details">
	                    <h2 class="product-title"><a href="single-product.html">Найс сникеры</a></h2>
	                    <div class="prod-cal d-flex align-items-center">
	                        <span class="quantity">1</span>
	                        <span class="multiplication">&#215;</span>
	                        <span class="price">3300 р</span>
	                    </div>
	                </div>
	                <a href="#" class="remove-icon">&#215;</a>
	            </div>

	        </div>
	        <div class="minicart-calculation-wrap d-flex justify-content-between align-items-center">
	            <span class="cal-title">Подытог</span>
	            <span class="cal-amount">11900 р</span>
	        </div>
	        <div class="minicart-btn-group mt-38">
	            <a href="cart.html" class="btn btn-black ">Просмотр корзины</a>
	            <a href="checkout.html" class="btn btn-black mt-10">Оформление заказа</a>
	        </div> -->


	    </div>
	</div>
	<!--== End Mini Cart Wrapper ==-->
<?php endif; ?>

<?php wp_footer() ?>

</body>
</html>

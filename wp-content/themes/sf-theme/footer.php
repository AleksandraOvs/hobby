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
                            <!-- <h3 class="widget-title">Напишите мне</h3>
                            <div class="widget-body">
                                <div class="contact-text">
                                    <a href="#">(+1) 234 56 78</a>
                                    <a href="#">me@misha.blog</a>
                                    <p>Санкт-Петербург, Невский пр.</p>
                                </div>
                            </div> -->
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

        </div>
    </div>
    <!-- End Footer Bottom Area -->
</footer>
<!--== End Footer Section ===-->

<?php if (!is_cart()) : ?>
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

<?php
if (current_user_can('administrator')) {
?>
    <div class="show-temp"><?php echo get_current_template(); ?> </div>
<?php
}
?>
<?php wp_footer() ?>

<div class="arrow-up">
    <svg width="20" height="12" viewBox="0 0 20 12" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M2.5836 11.4345C2.01856 12.0351 1.07065 12.0634 0.470085 11.4988C-0.130859 10.9341 -0.159204 9.98583 0.405457 9.38526L8.83418 0.469731C9.39884 -0.130835 10.3471 -0.15918 10.9477 0.405482C13.819 3.27679 16.6381 6.42098 19.4407 9.38526C20.0053 9.98583 19.977 10.9341 19.3764 11.4988C18.7755 12.0634 17.8276 12.0351 17.2629 11.4345L9.92306 3.67099L2.5836 11.4345Z" fill="white" />
    </svg>

</div>
</div>
</body>

</html>
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
                    <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/payments.png" alt="Payment Method" />
                </div>
            </div>
        </div>
    </div>
    <!-- End Footer Bottom Area -->
</footer>
<!--== End Footer Section ===-->

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

</body>

</html>
<!DOCTYPE html>
<html class="no-js" lang="zxx">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo wp_get_document_title() ?></title>

    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

		<?php wp_head() ?>

</head>

<body <?php body_class() ?>>



<!--== Start Header Area ===-->
<header id="header-area" class="sticky-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="header-content-wrapper d-flex align-items-center">
                    <div class="header-left-area d-flex align-items-center">
                        <!-- Start Logo Area -->
                        <div class="logo-area">
                            <a href="<?php echo site_url() ?>">Курс Миши</a>
                        </div>
                        <!-- End Logo Area -->

                    </div>

                    <div class="header-mainmenu-area d-none d-lg-block">
                        <!-- Start Main Menu -->
												<?php
												wp_nav_menu(
													array(
														'theme_location' => 'head_menu',
														'container' => 'nav',
														'container_id' => 'mainmenu-wrap',
														'menu_class' => 'nav mainmenu justify-content-center',
													)
												);
//                         <nav id="mainmenu-wrap">
//                             <ul class="">
//     <li class="dropdown-show"><a class="current" href="index.html">Каталог</a></li>
//     <li class="dropdown-show"><a href="#">Страницы</a>
//         <ul class="dropdown-nav">
//             <li><a href="cart.html">Корзина</a></li>
//             <li><a href="checkout.html">Страница оплаты</a></li>
//         </ul>
//     </li>
// 		<li class="dropdown-show"><a href="#">Товары</a>
//         <ul class="dropdown-nav">
//             <li><a href="single-product.html">Простой</a></li>
//             <li><a href="single-product-grouped.html">Сгруппированный</a></li>
//             <li><a href="single-product-affiliate.html">Внешний/Партнерский</a></li>
// 						<li><a href="single-product-variable.html">Вариативный</a></li>
//         </ul>
//     </li>
//     <li><a href="#">Блог</a></li>
//     <li><a href="#">Контакты</a></li>
// </ul>
                        ?>
                        <!-- End Main Menu -->
                    </div>

                    <div class="header-right-area d-flex justify-content-end align-items-center">
                        <button class="mini-cart-icon modalActive" data-mfp-src="#miniCart-popup">
                            <i class="fa fa-shopping-cart"></i>
                            <span class="cart-count"><?php echo count( WC()->cart->get_cart() ) ?></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!--== End Header Area ===-->

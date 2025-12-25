<?php
/* подключение стилей и скриптов */
add_action( 'wp_enqueue_scripts', function(){

	wp_enqueue_style( 'open-sans-font', 'https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,500,600,700,800' );
	wp_enqueue_style( 'playfair-font', 'https://fonts.googleapis.com/css?family=Playfair+Display' );

	wp_enqueue_style( 'bootstrap', get_stylesheet_directory_uri() . '/assets/css/vendor/bootstrap.min.css' );
	wp_enqueue_style( 'dl-icon', get_stylesheet_directory_uri() . '/assets/css/vendor/dl-icon.css' );
	wp_enqueue_style( 'fa', get_stylesheet_directory_uri() . '/assets/css/vendor/font-awesome.css' );
	wp_enqueue_style( 'helper', get_stylesheet_directory_uri() . '/assets/css/helper.min.css' );
	wp_enqueue_style( 'plugins', get_stylesheet_directory_uri() . '/assets/css/plugins.css' );
	wp_enqueue_style( 'main', get_stylesheet_directory_uri() . '/assets/css/style.css', array(), time() );

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'bootstrap', get_stylesheet_directory_uri() . '/assets/js/bootstrap.min.js', 'jquery', null, true );
	wp_enqueue_script( 'plugins', get_stylesheet_directory_uri() . '/assets/js/plugins.js', 'jquery', null, true );
	wp_enqueue_script( 'cookie', get_stylesheet_directory_uri() . '/assets/js/jquery.cookie.js', 'jquery', null, true );
	wp_enqueue_script( 'scripts', get_stylesheet_directory_uri() . '/assets/js/scripts.js', array( 'jquery', 'plugins', 'cookie' ), time(), true );


	wp_deregister_style( 'woocommerce-general' );
	wp_deregister_style( 'woocommerce-layout' );
});


/* регистрация меню */

register_nav_menus(
	array(
		'head_menu' => 'Меню в шапке',
		'foot_1' => 'Футер 1: Каталог',
		'foot_2' => 'Футер 2: Страницы',
		'foot_3' => 'Футер 3: Товары',
	)
);

add_theme_support( 'woocommerce' );


add_filter( 'woocommerce_breadcrumb_defaults', function() {

	return array(
		'delimiter' => '',
		'wrap_before' => '<nav class="page-breadcrumb"><ul class="d-flex justify-content-center">',
		'wrap_after' => '</ul></nav>',
		'before' => '<li>',
		'after' => '</li>',
		'home' => 'Главная',

	);

} );


remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );


add_action( 'wp_footer', function() {

	?><script>
	jQuery( function( $ ) {

		$( '.change_razmer' ).click(function(){
			var el = $(this),
					name = el.text(),
					val = el.data( 'value' );

			$( '.change_razmer' ).removeClass( 'active' );
			el.addClass( 'active' );

			$( '#pa_razmer' ).val( val );
			$( '#pa_razmer' ).change();

			$( '#configurable-name' ).html( 'Размер: <b>' + name + '</b>' );

		});

		$('.single_variation_wrap').on( 'show_variation', function( event, variation ) {

			$( '#product_cena' ).html( variation.price_html );
			console.log( variation );


		} );

		$( '.change-star' ).click(function(){
			var el = $(this),
					val = el.data( 'value' );

			el.addClass( 'active' );
			el.nextAll().removeClass('active');
			el.prevAll().addClass('active');


			$( 'select[name="rating"]' ).val( val );

		});


	} );
	</script><?php

});

add_filter( 'woocommerce_add_to_cart_fragments', function( $fragments ) {

	$fragments[ '.cart-count' ] = '<span class="cart-count">' . count( WC()->cart->get_cart() ) . '</span>';

	return $fragments;

} );


add_filter( 'woocommerce_checkout_fields', function( $checkout_fields ) {

	$checkout_fields[ 'billing' ][ 'billing_first_name' ][ 'placeholder' ] = 'Как вас зовут?';
	//echo '<pre>';print_r( $checkout_fields );exit;
	return $checkout_fields;

} );

add_filter( 'woocommerce_order_button_html', function( $html ) {

	return str_replace( 'button alt', 'btn btn-full btn-black mt-26', $html );

} );


/**
 * AJAX фильтр товаров
 */
add_action( 'wp_ajax_ajaxfilter', 'true_filter' );
add_action( 'wp_ajax_nopriv_ajaxfilter', 'true_filter' );

function true_filter() {
	// замедляет в секундах выполнение AJAX запроса
	sleep( 1 );

	$args = array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'orderby' => 'date',
		'order' => 'DESC',
		'tax_query' => array(
			'relation' => 'AND',
   		array( // исключаем из фильтрации товары, который скрыты из каталога
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'exclude-from-catalog',
				'operator' => 'NOT IN',
			),
		)
	);

	// категории товара
	$product_cats = ! empty( $_POST[ 'product_cats' ] ) && $_POST[ 'product_cats' ] ? $_POST[ 'product_cats' ] : array();

	if( $product_cats ) {
		$args[ 'tax_query' ][] = array(
			'taxonomy' => 'product_cat',
			'terms' => $product_cats
		);
	}

	// атрибуты товара
	// да, атрибуты это тоже таксономии, причём каждый атрибут – отдельная таксономия
	$razmers = ! empty( $_POST[ 'razmer' ] ) && $_POST[ 'razmer' ] ? $_POST[ 'razmer' ] : array();

	if( $razmers ) {
		$args[ 'tax_query' ][] = array(
			'taxonomy' => 'pa_razmer',
			'field' => 'slug',
			'terms' => $razmers
		);
	}

	// фильтрация по ценам
	$min_price = ! empty( $_POST[ 'min_price' ] ) && $_POST[ 'min_price' ] ? absint( $_POST[ 'min_price' ] ) : 0;
	$max_price = ! empty( $_POST[ 'max_price' ] ) && $_POST[ 'max_price' ] ? absint( $_POST[ 'max_price' ] ) : 100;

	$args[ 'meta_query' ] = array(
		'price_clause' => array(
			'key' => '_price',
			'value' => array( $min_price, $max_price ), // значения ОТ и ДО
			'compare' => 'between',
			'type' => 'numeric'
		)
	);

	// сортировка
	$orderby = ! empty( $_POST[ 'orderby' ] ) && $_POST[ 'orderby' ] ? $_POST[ 'orderby' ] : 'date';

	if( 'price' ===  $orderby ) {

		$args[ 'orderby' ] = 'price_clause';
		$args[ 'order' ] = 'ASC';
	}
	if( 'price-desc' ===  $orderby ) {

		$args[ 'orderby' ] = 'price_clause';
		$args[ 'order' ] = 'DESC';

	}

	$query = new WP_Query( $args );

	// запускаем буферизацию
	// после этой строчки всё, что будет выводитсья функцией echo по факту выводиться не будет, а запишется в буфер
	ob_start();

	if( $query->have_posts() ) {

		echo '<div class="row">';
		while ( $query->have_posts() ) {
			$query->the_post();

			wc_get_template_part( 'content', 'product' );
		}
		echo '</div>';


	} else {
		echo '<p>Ничего не найдено.</p>';
	}

	// записываем всё, что в буфере, в переменную
	$html_products = ob_get_contents();
	// очищаем буфер
	ob_end_clean();

	// при использовании wp_send_json() функции die()/exit(); уже не понадобятся после
	wp_send_json( array(
		'products' => $html_products,
		'count' => 'Отображение 1–' . $query->found_posts . ' из ' . $query->found_posts
	) );

}

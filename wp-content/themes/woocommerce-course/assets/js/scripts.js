jQuery( function( $ ) {

	/*===============================
		 Shop page
	==================================*/

	/*===== Product View Mode Change =====*/
	var viewItemClick = $(".product-view-mode li"),
			productWrapper = $(".shop-page-products-wrapper .products-wrapper");

	viewItemClick.each(function (index, elem) {
			var element = $(elem),
					viewStyle = element.data('viewmode');

			viewItemClick.on('click', function () {
					var viewMode = $(this).data('viewmode');
					productWrapper.removeClass(viewStyle).addClass(viewMode);
					viewItemClick.removeClass('active');
					$(this).addClass('active')
			});
	});

	/*=============================
		Mfp Modal Active JS
	==============================*/
	$(".modalActive").magnificPopup({
			type: 'inline',
			midClick: true,
			mainClass: 'veeraModal',
			preloader: false
	});

	/*=============================
		Nice Select Js
	==============================*/
	$('select').niceSelect();

	/*=============================
		Price Range Slider JS
	==============================*/

	var rangeSlider = $(".price-range"),
			amount = $("#amount"),
			minPrice = rangeSlider.data('min'),
			maxPrice = rangeSlider.data('max'),
			minPriceField = $("#min_price"),
			maxPriceField = $("#max_price"),
			form = $('#ajaxform');


	rangeSlider.slider({
			range: true,
			min: minPrice,
			max: maxPrice,
			values: [minPriceField.val(), maxPriceField.val()],
			slide: function (event, ui) {
					amount.val( ui.values[0] + " р - " + ui.values[1] + " р");
					minPriceField.val( ui.values[0] );
					maxPriceField.val( ui.values[1] );
			},
			stop: function( event, ui ) {
				form.submit();
			}
	});
	amount.val( rangeSlider.slider("values", 0) +
			" р - " + rangeSlider.slider("values", 1) + " р" );



	/*=============================
		Product Quantity
	==============================*/
	var proQty = $(".pro-qty");
	proQty.append('<a href="#" class="inc qty-btn">+</a>');
	proQty.append('<a href="#" class= "dec qty-btn">-</a>');
	$( 'body' ).on('click', '.qty-btn', function (e) {
			e.preventDefault();
			var $button = $(this);
			var oldValue = $button.parent().find('input').val();
			if( ! oldValue ) {
				oldValue = 0;
			}
			if ($button.hasClass('inc')) {
					var newVal = parseFloat(oldValue) + 1;
			} else {
					// Don't allow decrementing below zero
					if (oldValue > 0) {
							var newVal = parseFloat(oldValue) - 1;
					} else {
							newVal = 0;
					}
			}
			$button.parent().find('input').val(newVal).change();
	});

	$( document ).on( 'updated_cart_totals', function(){
		$(".pro-qty").append('<a href="#" class="inc qty-btn">+</a><a href="#" class= "dec qty-btn">-</a>');
	});

	/*==================================
			Single Product Zoom
	===================================*/
	$('.product-thumb-large-view .product-thumb-item').zoom();

	/*==================================
			Single Product Thumbnail JS
	===================================*/
	$('.product-thumb-carousel').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			fade: true,
			asNavFor: '.product-thumbnail-nav, .vertical-tab-nav'
	});

	// Horizontal Nav Style
	$('.product-thumbnail-nav').slick({
			slidesToShow: 3,
			slidesToScroll: 1,
			asNavFor: '.product-thumb-carousel',
			dots: false,
			arrows: false,
			centerMode: true,
			centerPadding: 0,
			variableWidth: false,
			focusOnSelect: true
	});

	// Vertical Nav Style
	$('.vertical-tab-nav').slick({
			slidesToShow: 3,
			slidesToScroll: 1,
			asNavFor: '.product-thumb-carousel',
			dots: false,
			arrows: false,
			focusOnSelect: true,
			vertical: true
	});

	/*=============================
		Checkout Page Checkbox
	==============================*/
	$("#create_pwd").on("change", function () {
			$(".account-create").slideToggle("100");
	});

	$("#ship_to_different").on("change", function () {
			$(".ship-to-different").slideToggle("100");
	});

	/*=============================
		Payment Method Accordion
	==============================*/
	$('input[name="paymentmethod"]').on('click', function () {

			var $value = $(this).attr('value');

			$('.payment-method-details').slideUp();
			$('[data-method="' + $value + '"]').slideDown();
	});

	/*=============================
		AJAX фильтрация товаров
	==============================*/
	// опции сортировки товаров
	$( '.product-filter-sort a' ).click( function( event ) {
		const el = $(this);
		const val = el.attr( 'href' ).replace( '?orderby=', '' );

		$( '.product-filter-sort a' ).removeClass( 'active' );
		el.addClass( 'active' );

		$( 'input[name="orderby"]' ).val( val );

		$('#ajaxform').submit();
		event.preventDefault();
	} );

	// асинхронный запрос при отправке формы
	$( '#ajaxform' ).submit( function( event ) {
		event.preventDefault();

		const form = $(this);

		$.ajax( {
			type : 'POST',
			url : woocommerce_params.ajax_url,
			data : form.serialize(),
			beforeSend : function( xhr ) {
				// отображаем прелоадер и блокируем клик по фильтр в момент, когда он загружается
				$( '#shop-page-wrapper' ).block({
					message : null,
					overlayCSS: {
            background: '#fff url( ' + window.location.protocol + '//' + window.location.hostname + '/wp-content/themes/woocommerce-course/assets/img/oval.svg) center 150px no-repeat',
            opacity: 0.6
        	}

				})
			},
			success : function( data ) {
				// выводим отфильтрованные товары
				$( '.products-wrapper' ).html( data.products );
				// выводим счётчик количества товаров
				$( '.woocommerce-result-count' ).text( data.count );

				$( '.page-pagination-wrapper' ).html( '' );

				$( '#shop-page-wrapper' ).unblock();
			}

		} );

	} );

	// отправляем форму при клике на чекбоксы также
	$( '#ajaxform input[type="checkbox"]' ).change( function() {
		$( '#ajaxform' ).submit();
	} );

} );

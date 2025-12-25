jQuery(function ($) {

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
		minPriceField = $('#min_price'),
		maxPriceField = $('#max_price'),
		form = $('#price_filter');

	rangeSlider.slider({
		range: true,
		min: minPrice,
		max: maxPrice,
		values: [minPriceField.val(), maxPriceField.val()],
		slide: function (event, ui) {
			amount.val(ui.values[0] + " р - " + ui.values[1] + " р");
			minPriceField.val(ui.values[0]);
			maxPriceField.val(ui.values[1]);
		},
		stop: function (event, ui) {
			form.submit();
		}
	});
	amount.val(rangeSlider.slider("values", 0) +
		" р - " + rangeSlider.slider("values", 1) + " р");



	/*=============================
		Product Quantity
	==============================*/
	/*=============================
	Product Quantity
==============================*/
	(function ($) {
		let cartUpdateTimer = null;

		$(document).on('click', '.qty-btn', function (e) {
			e.preventDefault();

			const $btn = $(this);
			const $wrap = $btn.closest('.pro-qty');
			const $input = $wrap.find('input.qty');

			let value = parseFloat($input.val()) || 0;
			const step = parseFloat($input.data('step')) || 1;
			const min = parseFloat($input.data('min')) || 1;
			const max = parseFloat($input.data('max')) || Infinity;

			if ($btn.hasClass('inc')) {
				value += step;
			} else if ($btn.hasClass('dec')) {
				value -= step;
			}

			// ограничиваем диапазон
			value = Math.max(min, value);
			value = Math.min(max, value);

			// устанавливаем значение и уведомляем WooCommerce
			$input.val(value).trigger('input').trigger('change');

			/* =========
			   Обновление корзины
			   ========= */
			if ($('body').hasClass('woocommerce-cart')) {
				clearTimeout(cartUpdateTimer);

				cartUpdateTimer = setTimeout(function () {
					const $updateBtn = $('button[name="update_cart"]');

					if ($updateBtn.length) {
						$updateBtn.prop('disabled', false).trigger('click');
					}
				}, 400);
			}
		});

	})(jQuery);
	/*==================================
			Single Product Zoom
	===================================*/
	$('.product-thumb-large-view .product-thumb-item').zoom();

	// /*==================================
	// 		Single Product Thumbnail JS
	// ===================================*/
	// $('.product-thumb-carousel').slick({
	// 	slidesToShow: 1,
	// 	slidesToScroll: 1,
	// 	arrows: false,
	// 	fade: true,
	// 	asNavFor: '.product-thumbnail-nav, .vertical-tab-nav'
	// });

	// // Horizontal Nav Style
	// $('.product-thumbnail-nav').slick({
	// 	slidesToShow: 3,
	// 	slidesToScroll: 1,
	// 	asNavFor: '.product-thumb-carousel',
	// 	dots: false,
	// 	arrows: false,
	// 	//centerMode: true,
	// 	//centerPadding: 0,
	// 	variableWidth: false,
	// 	infinite: false,
	// 	focusOnSelect: true
	// });

	// // Vertical Nav Style
	// $('.vertical-tab-nav').slick({
	// 	slidesToShow: 3,
	// 	slidesToScroll: 1,
	// 	asNavFor: '.product-thumb-carousel',
	// 	dots: false,
	// 	arrows: false,
	// 	focusOnSelect: true,
	// 	vertical: true
	// });

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

});

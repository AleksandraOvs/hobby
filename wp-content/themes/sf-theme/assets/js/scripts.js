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
		maxPrice = rangeSlider.data('max');
	rangeSlider.slider({
		range: true,
		min: minPrice,
		max: maxPrice,
		values: [minPrice, maxPrice],
		slide: function (event, ui) {
			amount.val(ui.values[0] + " р - " + ui.values[1] + " р");
		}
	});
	amount.val(rangeSlider.slider("values", 0) +
		" р - " + rangeSlider.slider("values", 1) + " р");

	/*=============================
		Product Quantity
	==============================*/
	var proQty = $(".pro-qty");
	proQty.append('<a href="#" class="inc qty-btn">+</a>');
	proQty.append('<a href="#" class= "dec qty-btn">-</a>');
	$('.qty-btn').on('click', function (e) {
		e.preventDefault();
		var $button = $(this);
		var oldValue = $button.parent().find('input').val();
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
		$button.parent().find('input').val(newVal);
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

});

// document.addEventListener('DOMContentLoaded', function () {
// 	const button = document.querySelector('.toggle-cat-menu-button');
// 	const menuContainer = document.getElementById('menu-catalog__container');

// 	if (!button || !menuContainer) return;

// 	const catMenu = document.getElementById('menu-catalog');
// 	if (!catMenu) return;

// 	const isMobile = () => window.matchMedia('(max-width: 991px)').matches;

// 	// Закрываем все подменю
// 	function closeAllSubmenus(parent = catMenu) {
// 		parent.querySelectorAll('li.is-open').forEach(li => li.classList.remove('is-open'));
// 	}

// 	// Клик по кнопке — открываем/закрываем контейнер, но подменю остаются закрыты
// 	button.addEventListener('click', function (e) {
// 		e.preventDefault();
// 		const isOpen = menuContainer.classList.toggle('is-open');
// 		button.classList.toggle('is-active', isOpen);

// 		// Подменю остаются закрытыми, закрываем только если меню закрыто
// 		if (!isOpen) {
// 			closeAllSubmenus();
// 		}
// 	});

// 	// Закрытие меню при клике вне его
// 	document.addEventListener('click', function (e) {
// 		if (!menuContainer.contains(e.target) && !button.contains(e.target)) {
// 			menuContainer.classList.remove('is-open');
// 			button.classList.remove('is-active');
// 			closeAllSubmenus();
// 		}
// 	});

// 	// Клик по пунктам меню
// 	catMenu.addEventListener('click', function (e) {
// 		const link = e.target.closest('a');
// 		if (!link) return;

// 		const item = link.closest('li');
// 		const submenu = item.querySelector(':scope > .dropdown-menu');
// 		if (!submenu) return;

// 		e.preventDefault();

// 		if (isMobile()) {
// 			// мобильная версия — просто переключаем подменю
// 			item.classList.toggle('is-open');
// 			return;
// 		}

// 		// десктоп — только первый уровень меню по клику
// 		if (item.parentElement.id === 'menu-catalog') {
// 			[...item.parentElement.children].forEach(li => {
// 				if (li !== item) {
// 					li.classList.remove('is-open');
// 					li.querySelectorAll('li.is-open').forEach(sub => sub.classList.remove('is-open'));
// 				}
// 			});

// 			item.classList.toggle('is-open');
// 		}
// 	});

// 	// При загрузке страницы меню закрыто, подменю закрыты
// 	menuContainer.classList.remove('is-open');
// 	button.classList.remove('is-active');
// 	closeAllSubmenus();
// });
document.addEventListener('click', function (e) {
	const toggleBtn = e.target.closest('.toggle-cat-menu-button');
	const menu = document.getElementById('menu-catalog__container');

	if (!menu) return;

	/* Клик по кнопке */
	if (toggleBtn) {
		e.preventDefault();

		const isOpen = menu.classList.contains('is-open');

		menu.classList.toggle('is-open', !isOpen);
		toggleBtn.classList.toggle('active', !isOpen);
		return;
	}

	/* Клик вне меню и кнопки */
	const clickedInsideMenu = e.target.closest('#menu-catalog__container');

	if (!clickedInsideMenu) {
		menu.classList.remove('is-open');

		const btn = document.querySelector('.toggle-cat-menu-button');
		if (btn) btn.classList.remove('active');
	}
});

document.addEventListener("DOMContentLoaded", function () {

	// === Универсальный плавный скролл ===
	function easeInOutQuad(t) { return t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t; }

	function smoothScrollToElement(selector, duration = 700) {
		const target = document.querySelector(selector);
		if (!target) return;
		document.documentElement.style.scrollBehavior = "auto";
		const element = document.scrollingElement || document.documentElement;
		const start = element.scrollTop;
		const targetTop = target.getBoundingClientRect().top + start - 160;
		const change = targetTop - start;
		const startTime = performance.now();

		function animate(currentTime) {
			const elapsed = currentTime - startTime;
			const progress = Math.min(elapsed / duration, 1);
			element.scrollTop = start + change * easeInOutQuad(progress);
			if (elapsed < duration) requestAnimationFrame(animate);
			else document.documentElement.style.scrollBehavior = "";
		}
		requestAnimationFrame(animate);
	}

	function smoothScrollToTop(duration = 700) {
		const element = document.scrollingElement || document.documentElement;
		const start = element.scrollTop;
		const change = -start;
		const startTime = performance.now();

		function animate(currentTime) {
			const elapsed = currentTime - startTime;
			const progress = Math.min(elapsed / duration, 1);
			element.scrollTop = start + change * easeInOutQuad(progress);
			if (elapsed < duration) requestAnimationFrame(animate);
		}
		requestAnimationFrame(animate);
	}

	document.querySelectorAll('a[href^="#"]').forEach(link => {
		link.addEventListener("click", e => {
			const targetSelector = link.getAttribute("href");
			if (!targetSelector || targetSelector.length <= 1) return;

			// Если это форма для Fancybox
			if (targetSelector === "#main-form") {
				e.preventDefault();
				const target = document.querySelector(targetSelector);
				if (target) {
					Fancybox.show([{ src: target, type: "inline" }]);
				}
				return; // не запускать плавный скролл
			}

			// Для остальных якорей — плавный скролл
			e.preventDefault();
			smoothScrollToElement(targetSelector, 800);
		});
	});


	// === Кнопка "вверх" ===
	const upArrow = document.querySelector(".arrow-up");
	if (upArrow) {
		upArrow.addEventListener("click", e => { e.preventDefault(); smoothScrollToTop(800); });
		window.addEventListener("scroll", () => {
			upArrow.classList.toggle("show", window.scrollY > 300);
		});
	}

});

document.addEventListener('DOMContentLoaded', () => {

	document.addEventListener('click', (e) => {

		const btn = e.target.closest('.load-more-subcats');
		if (!btn) return;

		const container = btn.closest('.categories-list__item');
		const hiddenItems = container.querySelectorAll('.subcategory-item.is-hidden');
		const step = parseInt(btn.dataset.step, 10);

		// показываем следующие 8
		Array.from(hiddenItems)
			.slice(0, step)
			.forEach(item => item.classList.remove('is-hidden'));

		// если скрытых больше нет — убираем кнопку
		if (container.querySelectorAll('.subcategory-item.is-hidden').length === 0) {
			btn.remove();
		}
	});

	// document.addEventListener('DOMContentLoaded', () => {

	// 	const btn = document.getElementById('load-more-categories');
	// 	const box = document.getElementById('categories-list');

	// 	if (!btn || !box) return;

	// 	btn.addEventListener('click', () => {

	// 		let offset = parseInt(box.dataset.offset);
	// 		let limit = parseInt(box.dataset.limit);

	// 		offset += limit;

	// 		const formData = new FormData();
	// 		formData.append('action', 'load_more_categories');
	// 		formData.append('offset', offset);
	// 		formData.append('limit', limit);

	// 		fetch('<?php echo admin_url('admin - ajax.php'); ?>', {
	// 			method: 'POST',
	// 			body: formData
	// 		})
	// 			.then(res => res.text())
	// 			.then(html => {

	// 				if (!html.trim()) {
	// 					btn.style.display = 'none';
	// 					return;
	// 				}

	// 				box.insertAdjacentHTML('beforeend', html);
	// 				box.dataset.offset = offset;
	// 			});

	// 	});

	// });

});
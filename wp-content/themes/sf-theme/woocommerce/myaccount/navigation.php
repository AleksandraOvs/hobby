<?php

/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

if (! defined('ABSPATH')) {
	exit;
}

do_action('woocommerce_before_account_navigation');
?>

<nav class="woocommerce-MyAccount-navigation" aria-label="<?php esc_html_e('Account pages', 'woocommerce'); ?>">
	<ul>
		<?php foreach (wc_get_account_menu_items() as $endpoint => $label) : ?>
			<li class="<?php echo wc_get_account_menu_item_classes($endpoint); ?>">
				<a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>">
					<span class="nav-icon"><?php echo my_account_svg($endpoint); ?></span>
					<span class="nav-label"><?php echo esc_html($label); ?></span>
				</a>
				<svg width="20" height="12" viewBox="0 0 20 12" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" clip-rule="evenodd" d="M17.2626 0.469778C17.8276 -0.130789 18.7755 -0.159138 19.3761 0.405523C19.9771 0.970184 20.0054 1.91847 19.4407 2.51904L11.012 11.4346C10.4474 12.0351 9.49907 12.0635 8.8985 11.4988C6.02719 8.62751 3.20804 5.48332 0.405525 2.51904C-0.159136 1.91847 -0.130792 0.970184 0.469775 0.405523C1.07072 -0.159138 2.01863 -0.130789 2.58329 0.469778L9.92313 8.2333L17.2626 0.469778Z" fill="#8B4512" />
				</svg>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>

<script>
	document.addEventListener('click', function(e) {
		const nav = document.querySelector('.woocommerce-MyAccount-navigation');
		if (!nav) return;

		const ul = nav.querySelector('ul');
		if (!ul) return;

		const isMobile = window.innerWidth < 768;
		if (!isMobile) return;

		const link = e.target.closest('.woocommerce-MyAccount-navigation a');

		// Клик по ссылке меню
		if (link) {
			// если меню ещё закрыто — открываем и ОТМЕНЯЕМ переход
			if (!ul.classList.contains('show')) {
				e.preventDefault();
				ul.classList.add('show');
			}
			// если уже открыто — ничего не делаем, переход произойдёт сам
			return;
		}

		// Клик вне меню — закрываем
		if (!nav.contains(e.target)) {
			ul.classList.remove('show');
		}
	});
</script>


<?php do_action('woocommerce_after_account_navigation'); ?>
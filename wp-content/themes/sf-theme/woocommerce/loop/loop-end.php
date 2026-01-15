<?php

/**
 * Product Loop End
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-end.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     2.0.0
 */

if (! defined('ABSPATH')) {
	exit;
}
?>

</div>
<?php if (wc_get_loop_prop('total_pages') > 1) : ?>
	<div class="load-more-wrapper">
		<button id="load-more-btn" class="btn">
			<span class="btn-content">
				<svg width="14" height="23" viewBox="0 0 14 23" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" clip-rule="evenodd" d="M11.1652 14.4953C11.5893 14.0474 12.2979 14.0277 12.7458 14.4518C13.1937 14.8755 13.2129 15.5841 12.7893 16.032L7.36374 21.7746C6.93968 22.224 6.22837 22.2467 5.77898 21.8226C3.94705 19.9907 2.09584 17.9237 0.305852 16.032C-0.118211 15.5841 -0.0989344 14.8755 0.34894 14.4518C0.796814 14.0277 1.50547 14.0474 1.92954 14.4953L6.54736 19.3739L11.1652 14.4953Z" fill="#674126" />
					<path d="M5.5095 1.11458C5.51101 0.49738 6.01558 -0.00150789 6.63278 3.95061e-06C7.24998 0.00151579 7.7485 0.50645 7.74736 1.12327L7.66648 21.0108C7.66497 21.628 7.16002 22.1269 6.54283 22.1254C5.92563 22.1238 5.42711 21.6189 5.42862 21.0017L5.5095 1.11458Z" fill="#674126" />
				</svg>
				Показать ещё
			</span>
			<span class="btn-loader" aria-hidden="true">
				<span></span>
				<span></span>
				<span></span>
			</span>
		</button>
	</div>
<?php endif; ?>
</div>
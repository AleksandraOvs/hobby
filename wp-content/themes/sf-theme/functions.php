<?php

/* подключение стилей и скриптов */
add_action('wp_enqueue_scripts', function () {

	wp_enqueue_style('open-sans-font', 'https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,500,600,700,800');
	wp_enqueue_style('playfair-font', 'https://fonts.googleapis.com/css?family=Playfair+Display');

	wp_enqueue_style('swiper_styles', get_stylesheet_directory_uri() . '/assets/css/swiper-bundle.min.css', array(), time());
	wp_enqueue_style('bootstrap', get_stylesheet_directory_uri() . '/assets/css/vendor/bootstrap.min.css');
	wp_enqueue_style('dl-icon', get_stylesheet_directory_uri() . '/assets/css/vendor/dl-icon.css');
	wp_enqueue_style('fa', get_stylesheet_directory_uri() . '/assets/css/vendor/font-awesome.css');
	wp_enqueue_style('helper', get_stylesheet_directory_uri() . '/assets/css/helper.min.css');
	wp_enqueue_style('plugins', get_stylesheet_directory_uri() . '/assets/css/plugins.css');
	wp_enqueue_style('fonts', get_stylesheet_directory_uri() . '/assets/css/fonts.css', array(), time());
	wp_enqueue_style('main', get_stylesheet_directory_uri() . '/assets/css/style.css', array(), time());
	wp_enqueue_style('single-product-style', get_stylesheet_directory_uri() . '/assets/css/single-product.css', array(), time());
	wp_enqueue_style('searh-style', get_stylesheet_directory_uri() . '/assets/css/search.css', array(), time());
	wp_enqueue_style('cart-style', get_stylesheet_directory_uri() . '/assets/css/cart.css', array(), time());
	if (is_page('my-account')) {
		wp_enqueue_style('my-account-style', get_stylesheet_directory_uri() . '/assets/css/account.css', array(), time());
	}
	wp_enqueue_script('jquery');
	wp_enqueue_script('swiper_scripts', get_template_directory_uri() . '/assets/js/swiper-bundle.min.js', array(), null, true);
	wp_enqueue_script('slider_scripts', get_template_directory_uri() . '/assets/js/slider-scripts.js', array(), null, true);
	wp_enqueue_script('bootstrap', get_stylesheet_directory_uri() . '/assets/js/bootstrap.min.js', 'jquery', null, true);
	wp_enqueue_script('plugins', get_stylesheet_directory_uri() . '/assets/js/plugins.js', 'jquery', null, true);
	wp_enqueue_script('scripts', get_stylesheet_directory_uri() . '/assets/js/scripts.js', array(), time(), true);
	//wp_enqueue_script('add_scripts', get_stylesheet_directory_uri() . '/assets/js/add-scripts.js', array(), time(), true);
	wp_enqueue_script('add_ui_scripts', get_stylesheet_directory_uri() . '/assets/js/add-scripts/ui.js', array(), time(), true);
	wp_enqueue_script('add_product_scripts', get_stylesheet_directory_uri() . '/assets/js/add-scripts/product.js', array(), time(), true);
	wp_enqueue_script('popup_scripts', get_stylesheet_directory_uri() . '/assets/js/popup.js', array(), time(), true);
	//wp_enqueue_script('add_cart_scripts', get_stylesheet_directory_uri() . '/assets/js/add-scripts/cart.js', array(), time(), true);

	wp_enqueue_script(
		'cart-js',
		get_stylesheet_directory_uri() . '/assets/js/add-scripts/cart.js',
		['jquery', 'wc-cart-fragments'],
		'1.0',
		true
	);

	wp_localize_script('cart-js', 'cart_ajax', [
		'ajax_url' => admin_url('admin-ajax.php'),
	]);


	wp_enqueue_script('ajax_scripts', get_stylesheet_directory_uri() . '/assets/js/ajax.js', array(), time(), true);

	// Стили
	wp_enqueue_style(
		'fancybox',
		'https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css',
		array(),
		null
	);

	// Скрипт
	wp_enqueue_script(
		'fancybox',
		'https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js',
		array(), // можно добавить 'jquery' если нужно
		null,
		true // грузить в footer
	);

	wp_enqueue_script(
		'ajax_scripts',
		get_stylesheet_directory_uri() . '/assets/js/ajax.js',
		['jquery'],
		null,
		true
	);

	wp_localize_script(
		'ajax_scripts',
		'themeAjax',
		['url' => admin_url('admin-ajax.php')]
	);

	/*поиск по сайту */
	wp_enqueue_script(
		'ajax-search',
		get_stylesheet_directory_uri() . '/assets/js/ajax-search.js',
		['jquery'],
		'1.0',
		true
	);

	wp_localize_script('ajax-search', 'ajax_search_params', [
		'ajax_url' => admin_url('admin-ajax.php'),
	]);
});

/**
 * Guttenberg support
 */

function mytheme_setup()
{
	// Добавляем поддержку блоков
	add_theme_support('align-wide'); // Поддержка широкого и полного выравнивания
	add_theme_support('editor-styles'); // Позволяет использовать кастомные стили в редакторе
	add_theme_support('wp-block-styles'); // Подключает стили по умолчанию для блоков
	add_theme_support('responsive-embeds'); // Адаптивные вставки (видео и др.)

	// Подключаем CSS редактора
	add_editor_style('css/style-editor.css');
}
add_action('after_setup_theme', 'mytheme_setup');


require get_template_directory() . '/inc/acf/acf-settings.php';

function mytheme_add_woocommerce_support()
{
	add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'mytheme_add_woocommerce_support');

add_theme_support('wc-product-gallery-zoom');
add_theme_support('wc-product-gallery-lightbox');
add_theme_support('wc-product-gallery-slider');

/**
 * Guttenberg support
 */

function theme_setup()
{
	// Добавляем поддержку блоков
	add_theme_support('align-wide'); // Поддержка широкого и полного выравнивания
	add_theme_support('editor-styles'); // Позволяет использовать кастомные стили в редакторе
	add_theme_support('wp-block-styles'); // Подключает стили по умолчанию для блоков
	add_theme_support('responsive-embeds'); // Адаптивные вставки (видео и др.)

	// Подключаем CSS редактора
	add_editor_style('assets/css/style-editor.css');
}
add_action('after_setup_theme', 'theme_setup');

/* регистрация меню */

register_nav_menus(
	array(
		'main_menu' => 'Главное меню',
		'cat_menu' => 'Каталог',
		'page_cat_menu' => 'Меню для страница Catalog',
		'mob_menu' => 'Мобильное меню',
		'foot_3' => 'Футер 3: Товары',
	)
);


/*Иконки для пунктов меню */
// add_filter('walker_nav_menu_start_el', function ($item_output, $item, $depth, $args) {

// 	$icon = get_field('menu_icon', $item);

// 	if ($icon) {
// 		$icon_html = '<img class="menu-icon" src="' . esc_url($icon) . '" alt="">';
// 		$item_output = str_replace('</a>', $icon_html . '</a>', $item_output);
// 	}

// 	return $item_output;
// }, 10, 4);

// Поле с кнопкой Media Uploader в меню
add_action('wp_nav_menu_item_custom_fields', function ($item_id, $item) {

	$icon = get_post_meta($item_id, '_menu_icon', true);
?>

	<p class="description description-wide menu-icon-field">
		<label>
			Иконка пункта меню
			<br>

			<input
				type="hidden"
				class="menu-icon-input"
				name="menu_item_icon[<?= esc_attr($item_id); ?>]"
				value="<?= esc_attr($icon); ?>">

			<button class="button select-menu-icon">
				Выбрать иконку
			</button>

			<button class="button remove-menu-icon" <?= empty($icon) ? 'style="display:none"' : ''; ?>>
				Удалить
			</button>

			<div class="menu-icon-preview" style="margin-top:8px;">
				<?php if ($icon): ?>
					<img src="<?= esc_url($icon); ?>" style="max-width:40px;height:auto;">
				<?php endif; ?>
			</div>
		</label>
	</p>

<?php
}, 10, 2);

//Сохраняем значение
add_action('wp_update_nav_menu_item', function ($menu_id, $menu_item_db_id) {

	if (isset($_POST['menu_item_icon'][$menu_item_db_id])) {
		update_post_meta(
			$menu_item_db_id,
			'_menu_icon',
			esc_url_raw($_POST['menu_item_icon'][$menu_item_db_id])
		);
	}
}, 10, 2);

//Подключаем Media Uploader + JS
add_action('admin_enqueue_scripts', function ($hook) {

	if ($hook !== 'nav-menus.php') {
		return;
	}

	wp_enqueue_media();

	wp_add_inline_script('jquery', '
        jQuery(document).ready(function ($) {

            let frame;

            $(document).on("click", ".select-menu-icon", function (e) {
                e.preventDefault();

                const wrapper = $(this).closest(".menu-icon-field");

                if (frame) {
                    frame.open();
                    return;
                }

                frame = wp.media({
                    title: "Выберите иконку",
                    button: { text: "Использовать" },
                    multiple: false
                });

                frame.on("select", function () {
                    const attachment = frame.state().get("selection").first().toJSON();

                    wrapper.find(".menu-icon-input").val(attachment.url);
                    wrapper.find(".menu-icon-preview").html(
                        "<img src=\'" + attachment.url + "\' style=\'max-width:40px;height:auto;\'>"
                    );
                    wrapper.find(".remove-menu-icon").show();
                });

                frame.open();
            });

            $(document).on("click", ".remove-menu-icon", function (e) {
                e.preventDefault();

                const wrapper = $(this).closest(".menu-icon-field");

                wrapper.find(".menu-icon-input").val("");
                wrapper.find(".menu-icon-preview").html("");
                $(this).hide();
            });

        });
    ');
});

//Подмешиваем иконку в объект меню
add_filter('wp_setup_nav_menu_item', function ($item) {
	$item->icon = get_post_meta($item->ID, '_menu_icon', true);
	return $item;
});

//Вывод иконки в меню
add_filter('walker_nav_menu_start_el', function ($output, $item) {

	if (!empty($item->icon)) {
		$icon = '<img class="menu-icon" src="' . esc_url($item->icon) . '" alt="">';
		$output = str_replace('</a>', $icon . '</a>', $output);
	}

	return $output;
}, 10, 2);


//разрешить загрузку свг только админам
function allow_svg_upload_for_admins($mimes)
{
	if (current_user_can('administrator')) {
		$mimes['svg'] = 'image/svg+xml';
	}
	return $mimes;
}
add_filter('upload_mimes', 'allow_svg_upload_for_admins');

//показать название шаблона 
add_filter('template_include', 'var_template_include', 1000);
function var_template_include($t)
{
	$GLOBALS['current_theme_template'] = basename($t);
	return $t;
}

function get_current_template($echo = false)
{
	if (!isset($GLOBALS['current_theme_template']))
		return false;
	if ($echo)
		echo $GLOBALS['current_theme_template'];
	else
		return $GLOBALS['current_theme_template'];
}

add_filter('woocommerce_breadcrumb_default', function () {
	return array(
		'delimeter' => '',
		'wrap_before' => ' <nav class="page-breadcrumb">
                        <ul class="d-flex justify-content-center">',
		'wrap_after' => '</ul></div>',
		'before' => '<li>',
		'after' => '</li>'
	);
});

register_sidebar(array(
	'id' => 'filter',
	'name' => 'Сайдбар для виджета товаров',
	'before_widget' => '<div class="single-sidebar-wrap">',
	'after_widget' => '</div>',
	'before_title' => '',
	'after_title' => '',
));

register_sidebar(array(
	'id' => 'header',
	'name' => 'Сайдбар шапки',
	'before_widget' => '<div class=" header-sidebar-wrap">',
	'after_widget'  => '</div>',
	'before_title'  => '',
	'after_title'   => '',
));

register_sidebar(array(
	'id' => 'footer-1',
	'name' => 'Сайдбар футера',
	'before_widget' => '<div class=" footer-sidebar-wrap">',
	'after_widget'  => '</div>',
	'before_title'  => '',
	'after_title'   => '',
));

register_sidebar(array(
	'id' => 'footer-2',
	'name' => 'Сайдбар футера2',
	'before_widget' => '<div class=" footer-sidebar-wrap">',
	'after_widget'  => '</div>',
	'before_title'  => '',
	'after_title'   => '',
));

register_sidebar(array(
	'id' => 'footer-3',
	'name' => 'Сайдбар футера3',
	'before_widget' => '<div class=" footer-sidebar-wrap">',
	'after_widget'  => '</div>',
	'before_title'  => '',
	'after_title'   => '',
));


function render_product_categories_menu($parent_id = 0, $level = 0)
{
	$terms = get_terms([
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
		'parent'     => $parent_id,
	]);

	if (empty($terms) || is_wp_error($terms)) {
		return;
	}

	// Класс для вложенных списков
	$ul_class = $level === 0
		? 'menu'
		: 'dropdown-menu level-' . $level;

	// id только для корневого ul
	$ul_id = $level === 0 ? ' id="menu-catalog"' : '';

	echo '<ul' . $ul_id . ' class="' . esc_attr($ul_class) . '">';

	foreach ($terms as $term) {

		// Пропускаем категорию misc
		if ($term->slug === 'misc') {
			continue;
		}

		// Проверяем, есть ли дети
		$children = get_terms([
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'parent'     => $term->term_id,
			'number'     => 1,
		]);

		$has_children = !empty($children);

		$li_classes = ['menu-item'];
		if ($has_children) {
			$li_classes[] = 'menu-item-has-children';
		}

		echo '<li class="' . esc_attr(implode(' ', $li_classes)) . '">';

		// ======= Получаем иконку через ACF только для верхнего уровня =======
		$icon_url = '';
		if ($level === 0) {
			$icon_id = get_field('category_icon', 'product_cat_' . $term->term_id);
			if ($icon_id) {
				$icon_url = wp_get_attachment_image_url($icon_id, 'thumbnail');
			}
		}

		echo '<a href="' . esc_url(get_term_link($term)) . '">';
		if ($icon_url) {
			echo '<img class="cat-icon" src="' . esc_url($icon_url) . '" alt="' . esc_attr($term->name) . '">';
		}
		echo esc_html($term->name);
		echo '</a>';

		// Дочерние категории
		if ($has_children) {
			render_product_categories_menu($term->term_id, $level + 1);
		}

		echo '</li>';
	}

	echo '</ul>';
}

/*CUSTOM LOGO */
add_theme_support('customize-selective-refresh-widgets');

require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/walker.php';
require get_template_directory() . '/inc/post-types.php';
require get_template_directory() . '/inc/load-works.php';
require get_template_directory() . '/inc/load-cats.php';
require get_template_directory() . '/inc/breadcrumbs.php';
require get_template_directory() . '/inc/woo-functions.php';
require get_template_directory() . '/wishlist/wl.php';
require get_template_directory() . '/my-account/ma-functions.php';
require get_template_directory() . '/inc/ajax-search.php';


function theme_posts_pagination_with_load_more($query = null)
{
	// Если передан кастомный WP_Query — используем его, иначе global
	if (! $query) {
		global $wp_query;
		$query = $wp_query;
	}

	$max_pages = $query->max_num_pages;

	if ($max_pages <= 1) {
		return; // страниц нет — выходим
	}

	// Кнопка load more
	echo '<div class="load-more-wrapper">';
	echo '<button 
            id="load-more-posts" 
            class="btn" 
            data-page="1" 
            data-max="' . esc_attr($max_pages) . '">
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
          </button>';
	echo '</div>';
	// Цифровая пагинация
	//echo '<nav class="page-pagination">';
	echo get_the_posts_pagination([
		'mid_size'           => 2,
		'prev_text'          => '<span class="arrow-prev"></span>',
		'next_text'          => '<span class="arrow-next"></span>',
		'screen_reader_text' => 'Постраничная навигация по товарам',
		'type'               => 'list', // чтобы вернулся <ul class="page-numbers">
		'class'              => 'page-pagination', // класс для <nav>
	]);
	//echo '</nav>';


}

add_action('wp_ajax_load_more_posts', 'theme_load_more_posts_ajax');
add_action('wp_ajax_nopriv_load_more_posts', 'theme_load_more_posts_ajax');

function theme_load_more_posts_ajax()
{

	$page = isset($_POST['page']) ? (int) $_POST['page'] + 1 : 2;

	$args = [
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => 9, // сколько загружаем за клик
		'paged'          => $page,
	];

	$query = new WP_Query($args);

	if ($query->have_posts()) :
		while ($query->have_posts()) : $query->the_post();
			get_template_part('template-parts/content-post');
		endwhile;
	endif;

	wp_die();
}

// Метабокс "Показывать содержание"
add_action('add_meta_boxes', function () {
	add_meta_box(
		'page_docs_toc',
		'Содержание страницы',
		'render_page_docs_toc_metabox',
		'page',
		'normal',
		'high'
	);
});

function render_page_docs_toc_metabox($post)
{
	// Показываем только для шаблона page-docs
	$template = get_page_template_slug($post->ID);
	if ($template !== 'page-docs.php') {
		echo '<p>Доступно только для шаблона "Страница Документы".</p>';
		return;
	}

	$value = get_post_meta($post->ID, '_show_page_toc', true);

	wp_nonce_field('save_page_docs_toc', 'page_docs_toc_nonce');
?>
	<label>
		<input type="checkbox" name="show_page_toc" value="1" <?php checked($value, '1'); ?>>
		Показывать содержание
	</label>
<?php
}

// Сохранение значения
add_action('save_post', function ($post_id) {

	if (!isset($_POST['page_docs_toc_nonce'])) return;
	if (!wp_verify_nonce($_POST['page_docs_toc_nonce'], 'save_page_docs_toc')) return;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if (!current_user_can('edit_post', $post_id)) return;

	if (isset($_POST['show_page_toc'])) {
		update_post_meta($post_id, '_show_page_toc', '1');
	} else {
		delete_post_meta($post_id, '_show_page_toc');
	}
});

add_action('wp_enqueue_scripts', function () {
	if (is_product()) {
		wp_enqueue_script('wc-add-to-cart-variation');
	}
});

/**
 * Ограничиваем поле только товарами WooCommerce
 */
add_filter('acf/fields/post_object/query/name=work_products_link', function ($args) {

	$args['post_type'] = ['product'];
	$args['posts_per_page'] = -1;
	$args['orderby'] = 'title';
	$args['order'] = 'ASC';

	return $args;
});


/**
 * Меняем отображаемый текст товара в списке ACF:
 * добавляем категорию перед названием
 */
add_filter('acf/fields/post_object/result/name=work_products_link', function ($text, $post) {

	$terms = get_the_terms($post->ID, 'product_cat');

	if ($terms && !is_wp_error($terms)) {

		usort($terms, function ($a, $b) {
			return $b->parent - $a->parent;
		});

		$cat_name = $terms[0]->name;

		$text = '📁 ' . $cat_name . ' → ' . $text;
	}

	return $text;
}, 10, 2);


add_action('acf/input/admin_enqueue_scripts', function () {
	wp_enqueue_script(
		'acf-products-grouping',
		get_stylesheet_directory_uri() . '/assets/js/acf-products-grouping.js',
		['jquery'],
		'1.0',
		true
	);
});

add_filter('render_block', function ($block_content, $block) {

	if ($block['blockName'] === 'core/search') {

		$insert = '<span class="close" style="display:none;"></span>
                   <div class="results"></div>';

		$block_content = str_replace(
			'</div></form>',
			$insert . '</div></form>',
			$block_content
		);
	}

	return $block_content;
}, 10, 2);

// Contact Form 7 remove auto added p tags
add_filter('wpcf7_autop_or_not', '__return_false');

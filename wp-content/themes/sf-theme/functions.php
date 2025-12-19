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

	wp_enqueue_script('jquery');
	wp_enqueue_script('swiper_scripts', get_template_directory_uri() . '/assets/js/swiper-bundle.min.js', array(), null, true);
	wp_enqueue_script('tours_slider_scripts', get_template_directory_uri() . '/assets/js/slider-scripts.js', array(), null, true);
	wp_enqueue_script('bootstrap', get_stylesheet_directory_uri() . '/assets/js/bootstrap.min.js', 'jquery', null, true);
	wp_enqueue_script('plugins', get_stylesheet_directory_uri() . '/assets/js/plugins.js', 'jquery', null, true);
	wp_enqueue_script('scripts', get_stylesheet_directory_uri() . '/assets/js/scripts.js', array(), time(), true);

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


/*CUSTOM LOGO */
add_theme_support('customize-selective-refresh-widgets');

require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/walker.php';
require get_template_directory() . '/inc/post-types.php';
require get_template_directory() . '/inc/load-works.php';
require get_template_directory() . '/inc/load-cats.php';
require get_template_directory() . '/inc/breadcrumbs.php';

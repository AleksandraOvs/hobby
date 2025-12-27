<?php
function register_template_post_type()
{
    register_post_type('works', [
        'labels' => [
            'name' => 'Работы',
            'singular_name' => 'Работа',
            'add_new' => 'Добавить работу',
            'add_new_item' => 'Добавить новую работу',
            'edit_item' => 'Редактировать работу',
            'new_item' => 'Новая работа',
            'view_item' => 'Просмотреть работу',
            'search_items' => 'Поиск работ',
            'not_found' => 'Работы не найдены',
            'menu_name' => 'Работы',
        ],
        'public' => true, // ✅ обязательно true, чтобы включился Gutenberg
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true, // ✅ без этого Gutenberg не заработает
        'supports' => ['title'], // ✅ поддержка редактора
        'menu_icon' => 'dashicons-layout',
        'has_archive' => true,
        'rewrite'            => ['slug' => 'works'],
        'publicly_queryable' => true, // можно отключить вывод на фронте
    ]);
}
add_action('init', 'register_template_post_type');

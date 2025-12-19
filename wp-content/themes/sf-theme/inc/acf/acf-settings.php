<?php

add_action('init', function () {

    if (function_exists('acf_add_options_page')) {

        acf_add_options_page([
            'page_title' => 'Контакты',
            'menu_title' => 'Контакты',
            'menu_slug'  => 'contacts-settings',
            'capability' => 'edit_posts',
            'redirect'   => false,
            'position'   => 25,
        ]);
    }
});

// add_action('init', function () {
//     var_dump(function_exists('acf_add_options_page'));
//     die;
// });


// add_filter('acf/settings/show_admin', '__return_true');

// add_filter('acf/location/rule_types', function ($choices) {
//     $choices['Menu']['menu_item'] = 'Пункт меню';
//     return $choices;
// });

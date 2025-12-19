<?php

/**
 * untheme Theme Customizer
 *
 * @package untheme
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */

/**
 * Регистрируем настройки цветов в кастомайзере
 */
function mytheme_customize_register($wp_customize)
{
    $color_settings = [
        'primary'    => '#0073aa',
        'secondary'  => '#005177',
        'accent'     => '#d54e21',
        'beige'     => '#f2ddc8',
        'beige-light'     => '#FDF8F4',
        'light'     => '#f5f5f5',
        'background' => '#ffffff',
        'black'      => '#000000',
    ];

    foreach ($color_settings as $key => $default) {

        $wp_customize->add_setting(
            "mytheme_{$key}_color",
            [
                'default'   => $default,
                'transport' => 'refresh',
            ]
        );

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                "mytheme_{$key}_color_control",
                [
                    'label'   => ucfirst($key) . ' Color',
                    'section' => 'colors',
                    'settings' => "mytheme_{$key}_color",
                ]
            )
        );
    }
}
add_action('customize_register', 'mytheme_customize_register');



/**
 * Вывод CSS-переменных (фронтенд + Gutenberg)
 */

/**
 * Dynamic CSS variables — frontend
 */
function mytheme_output_custom_colors()
{
    $primary    = get_theme_mod('mytheme_primary_color', '#0073aa');
    $secondary  = get_theme_mod('mytheme_secondary_color', '#005177');
    $light  = get_theme_mod('mytheme_light_color', '#f5f5f5');
    $accent     = get_theme_mod('mytheme_accent_color', '#d54e21');
    $beige     = get_theme_mod('mytheme_beige_color', '#f2ddc8');
    $beige_light     = get_theme_mod('mytheme_beige_light_color', '#FDF8F4');
    $background = get_theme_mod('mytheme_background_color', '#ffffff');

    echo "<style>
        :root {
            --theme-color-primary: {$primary};
            --theme-color-secondary: {$secondary};
            --theme-color-light: {$light};
            --theme-color-accent: {$accent};
            --theme-color-background: {$background};
            --theme-color-beige: {$beige};
            --theme-color-beige-light: {$beige_light};
        }
    </style>";
}
add_action('wp_head', 'mytheme_output_custom_colors');

add_action('admin_head', 'mytheme_output_custom_colors'); // проброс в редактор


/**
 * Dynamic CSS variables — Gutenberg editor (iframe)
 */
function mytheme_editor_custom_properties()
{

    $primary    = get_theme_mod('mytheme_primary_color', '#0073aa');
    $secondary  = get_theme_mod('mytheme_secondary_color', '#005177');
    $light  = get_theme_mod('mytheme_light_color', '#f5f5f5');
    $accent     = get_theme_mod('mytheme_accent_color', '#d54e21');
    $background = get_theme_mod('mytheme_background_color', '#ffffff');
    $beige = get_theme_mod('mytheme_beige_color', '#f2ddc8');
    $beige_light = get_theme_mod('mytheme_beige_light_color', '#FDF8F4');

    $css = "
    :root {
        --theme-color-primary: {$primary};
        --theme-color-secondary: {$secondary};
        --theme-color-accent: {$accent};
        --theme-color-light: {$light};
        --theme-color-background: {$background};
        --theme-color-beige: {$beige};
        --theme-color-beige-light: {$beige_light};

        /* ДЛЯ ПОЛНОЙ СОВМЕСТИМОСТИ С GUTENBERG */
        --wp--preset--color--primary: {$primary};
        --wp--preset--color--secondary: {$secondary};
        --wp--preset--color--accent: {$accent};
    }";

    wp_add_inline_style('wp-block-library', $css);
}
add_action('enqueue_block_editor_assets', 'mytheme_editor_custom_properties');


function untheme_customize_register($wp_customize)
{
    $wp_customize->get_setting('blogname')->transport         = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport  = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial(
            'blogname',
            array(
                'selector'        => '.site-title a',
                'render_callback' => 'untheme_customize_partial_blogname',
            )
        );
        $wp_customize->selective_refresh->add_partial(
            'blogdescription',
            array(
                'selector'        => '.site-description',
                'render_callback' => 'untheme_customize_partial_blogdescription',
            )
        );
    };

    $wp_customize->add_setting('header_logo', array(
        'default' => '',
        //'height' => '48',
        'width' => '280',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'header_logo', array(

        'section' => 'title_tagline',
        'label' => 'Логотип Header'

    )));
    $wp_customize->add_setting('footer_logo', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'footer_logo', array(
        'section' => 'title_tagline',
        'label' => 'Логотип Footer'
    )));
}
add_action('customize_register', 'untheme_customize_register');


/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function untheme_customize_partial_blogname()
{
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function untheme_customize_partial_blogdescription()
{
    bloginfo('description');
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function untheme_customize_preview_js()
{
    wp_enqueue_script('untheme-customizer', get_template_directory_uri() . '/js/customizer.js', array('customize-preview'), 1, true);
}
add_action('customize_preview_init', 'untheme_customize_preview_js');

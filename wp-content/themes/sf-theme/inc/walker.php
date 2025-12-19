<?php
class My_Custom_Walker_Nav_Menu extends Walker_Nav_Menu
{
    // Начало уровня (подменю)
    public function start_lvl(&$output, $depth = 0, $args = array())
    {
        $indent = str_repeat("\t", $depth);

        $level = $depth + 1;
        $classes = 'dropdown-menu level-' . $level;

        $output .= "\n{$indent}<ul class=\"{$classes}\">\n";
    }

    // Конец уровня
    public function end_lvl(&$output, $depth = 0, $args = array())
    {
        $indent = str_repeat("\t", $depth);
        $output .= "{$indent}</ul>\n";
    }

    // Начало элемента
    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        if (is_array($args)) {
            $args = (object) $args;
        }

        $indent = $depth ? str_repeat("\t", $depth) : '';

        // классы пункта меню
        $classes = empty($item->classes) ? array() : (array) $item->classes;

        $has_children = in_array('menu-item-has-children', $classes, true);
        if ($has_children) {
            $classes[] = 'has-children';
        }

        $class_names = implode(' ', array_map('esc_attr', array_filter($classes)));
        $class_names = $class_names ? ' class="' . $class_names . '"' : '';

        $output .= $indent . '<li' . $class_names . '>';

        // атрибуты ссылки
        $atts  = ! empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $atts .= ! empty($item->target)     ? ' target="' . esc_attr($item->target) . '"' : '';
        $atts .= ! empty($item->xfn)        ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $atts .= ! empty($item->url)        ? ' href="' . esc_url($item->url) . '"' : '';

        $title = apply_filters('the_title', $item->title, $item->ID);

        $item_output  = $args->before;
        $item_output .= '<a' . $atts . '>';
        $item_output .= $args->link_before . $title . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters(
            'walker_nav_menu_start_el',
            $item_output,
            $item,
            $depth,
            $args
        );
    }
}

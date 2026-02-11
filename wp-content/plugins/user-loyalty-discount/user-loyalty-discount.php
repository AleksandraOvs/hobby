<?php
/*
Plugin Name: User Loyalty Discount
Description: Система скидок по сумме покупок пользователя с админ-панелью.
Version: 1.1
Author: You
*/

if (!defined('ABSPATH')) exit;

define('ULD_PATH', plugin_dir_path(__FILE__));
define('ULD_URL', plugin_dir_url(__FILE__));

require_once ULD_PATH . 'includes/uld-functions.php';
require_once ULD_PATH . 'includes/uld-admin.php';
require_once ULD_PATH . 'includes/uld-shortcodes.php';
require_once ULD_PATH . 'includes/uld-cart.php';

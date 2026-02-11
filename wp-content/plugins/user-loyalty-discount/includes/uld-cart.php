<?php
/*
 * Файл: uld-cart.php
 * Описание: Применение лояльной скидки пользователя в корзине и на оформлении заказа
 */

if (!defined('ABSPATH')) exit;

/**
 * Пересчёт цены в корзине с лояльной скидкой и сохранение суммы скидки
 */
add_action('woocommerce_before_calculate_totals', function ($cart) {

    if (is_admin() && !defined('DOING_AJAX')) return;
    if (!is_user_logged_in()) return;

    $user_id = get_current_user_id();
    $discount_percent = uld_get_discount_percent($user_id);

    if ($discount_percent <= 0) return;

    $total_discount = 0;

    foreach ($cart->get_cart() as $cart_item) {
        $product = $cart_item['data'];
        $base_price = (float) $product->get_regular_price();
        if (!$base_price) $base_price = (float) $product->get_price();

        $discounted_price = $base_price * (1 - $discount_percent / 100);
        $product->set_price($discounted_price);

        // считаем сумму скидки для этого товара
        $total_discount += ($base_price - $discounted_price) * $cart_item['quantity'];
    }

    // сохраняем сумму скидки в сессии WC
    WC()->session->set('uld_discount_total', $total_discount);
}, 20);

/**
 * Уведомление пользователя о лояльной скидке
 */
function uld_discount_notice()
{
    if (!is_user_logged_in()) return;

    $user_id = get_current_user_id();
    $discount_percent = uld_get_discount_percent($user_id);

    if ($discount_percent <= 0) return;

    $total_spent = uld_get_user_total_spent($user_id);

    wc_print_notice(
        sprintf(
            'Ваша скидка %s%% применена!',
            esc_html($discount_percent),
            wc_price($total_spent)
        ),
        'success'
    );
}
add_action('woocommerce_before_cart', 'uld_discount_notice');
add_action('woocommerce_before_checkout_form', 'uld_discount_notice');

/**
 * Вывод суммы лояльной скидки в Review Order
 */
add_action('woocommerce_review_order_before_order_total', function () {
    if (!is_user_logged_in()) return;

    $discount_total = WC()->session->get('uld_discount_total', 0);

    if ($discount_total <= 0) return;
?>
    <tr class="review-order__row">
        <th class="review-order__col">Скидка по программе лояльности:</th>
        <td class="review-order__col">
            <?php echo wc_price($discount_total); ?>
        </td>
    </tr>
<?php
});

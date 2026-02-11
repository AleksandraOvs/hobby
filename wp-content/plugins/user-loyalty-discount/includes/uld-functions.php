<?php
if (!defined('ABSPATH')) exit;

/**
 * Получить сумму всех оплаченных заказов пользователя
 */
function uld_get_user_total_spent($user_id)
{
    if (!$user_id) return 0;

    $orders = wc_get_orders([
        'customer_id' => $user_id,
        'status'      => ['completed', 'processing'],
        'limit'       => -1,
        'return'      => 'ids',
    ]);

    $total = 0;

    foreach ($orders as $order_id) {
        $order = wc_get_order($order_id);
        $total += (float) $order->get_total();
    }

    return $total;
}

/**
 * Получить диапазоны скидок
 */
function uld_get_discount_ranges()
{
    $ranges = get_option('uld_discount_ranges');

    if (!$ranges || !is_array($ranges)) {
        $ranges = [
            ['sum' => 10000, 'percent' => 5],
            ['sum' => 30000, 'percent' => 10],
            ['sum' => 60000, 'percent' => 15],
        ];
    }

    return $ranges;
}

/**
 * Определение процента скидки
 */
function uld_get_discount_percent($user_id)
{
    $total  = uld_get_user_total_spent($user_id);
    $ranges = uld_get_discount_ranges();

    $percent = 0;

    foreach ($ranges as $range) {
        if ($total >= floatval($range['sum'])) {
            $percent = intval($range['percent']);
        }
    }

    return $percent;
}

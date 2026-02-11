<?php
if (!defined('ABSPATH')) exit;

/**
 * [user_discount]
 */
function uld_discount_shortcode()
{
    if (!is_user_logged_in()) {
        return 'Войдите в аккаунт, чтобы увидеть вашу скидку.';
    }

    $user_id = get_current_user_id();
    $percent = uld_get_discount_percent($user_id);
    $total   = uld_get_user_total_spent($user_id);

    error_log('ULD user id: ' . $user_id);
    error_log('ULD percent: ' . $percent);
    error_log('ULD total spent: ' . $total);


    ob_start();
?>

    <div class="uld-box">
        <div class="uld-box__block --discount-percent">
            <p>Ваша скидка составляет</p>
            <span><?php echo esc_html($percent); ?>%</span>
        </div>

        <div class="uld-box__block --orders">
            <p>Выкуплено на сумму</p>
            <span> <?php echo wc_price($total); ?></span>
        </div>

    </div>
<?php
    return ob_get_clean();
}
add_shortcode('user_discount', 'uld_discount_shortcode');


/**
 * [user_discount_ranges]
 */
function uld_discount_ranges_shortcode()
{
    $ranges = uld_get_discount_ranges();
    if (empty($ranges)) return '';

    ob_start();
?>

    <div class="uld-box__info">
        <div class="uld-box__info__content --uld-description">
            <p>Расчет Вашей персональной скидки производится по общей сумме Ваших заказов за год. Перерасчет осуществляется еженедельно и его результат Вы можете увидеть в личном кабинете.</p>
            <p>Преимущество накопительной системы - возможность покупать продукцию в любом объеме без розничной наценки.</p>
            <p>Накопительная скидка, скидка от объема вашего заказа и сезонная скидка не суммируются. Выбирается большая из них.</p>

        </div>

        <div class="uld-box__info__content --table">
            <h3>Система скидок</h3>
            <div class="uld-ranges-table">

                <div class="uld-ranges-table__body">
                    <?php foreach ($ranges as $i => $range): ?>
                        <?php
                        $from = $range['sum'];

                        // если есть следующая граница — берём её и вычитаем 1
                        if (isset($ranges[$i + 1])) {
                            $to = $ranges[$i + 1]['sum'] - 1;
                            $range_text = 'от&nbsp;' . wc_price($from) . ' до&nbsp;' . wc_price($to);
                        } else {
                            // последняя строка — без верхнего предела
                            $range_text = 'от ' . wc_price($from);
                        }
                        ?>

                        <div class="uld-ranges-table__row">
                            <div class="uld-ranges-table__cell">
                                <?php echo $range_text; ?>
                            </div>
                            <div class="uld-ranges-table__cell">
                                <?php echo '- ' . esc_html($range['percent']); ?>%
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>

<?php
    return ob_get_clean();
}
add_shortcode('user_discount_ranges', 'uld_discount_ranges_shortcode');

<?php
if (!defined('ABSPATH')) exit;

/**
 * Меню
 */
add_action('admin_menu', function () {
    add_menu_page(
        'Скидки пользователей',
        'Скидки',
        'manage_woocommerce',
        'uld-discounts',
        'uld_admin_page',
        'dashicons-awards',
        56
    );
});

/**
 * Сохранение диапазонов
 */
add_action('admin_init', 'uld_save_ranges');

function uld_save_ranges()
{
    if (
        !isset($_POST['uld_ranges_nonce']) ||
        !wp_verify_nonce($_POST['uld_ranges_nonce'], 'uld_save_ranges')
    ) {
        return;
    }

    if (!current_user_can('manage_woocommerce')) return;

    $ranges = [];

    if (!empty($_POST['sum']) && !empty($_POST['percent'])) {
        foreach ($_POST['sum'] as $i => $sum) {

            $sum     = floatval($sum);
            $percent = intval($_POST['percent'][$i]);

            if ($sum <= 0 && $percent <= 0) continue;

            $ranges[] = [
                'sum'     => $sum,
                'percent' => $percent,
            ];
        }
    }

    update_option('uld_discount_ranges', $ranges);
}

/**
 * Страница админки
 */
function uld_admin_page()
{
    $ranges = uld_get_discount_ranges();

    $users = get_users([
        'fields' => ['ID', 'display_name'],
    ]);
?>
    <h2>Доступные шорткоды</h2>

    <table class="widefat" style="max-width:800px; margin-bottom:20px;">
        <thead>
            <tr>
                <th style="width:200px;">Шорткод</th>
                <th>Описание</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>[user_discount]</code></td>
                <td>Показывает текущую скидку пользователя и сумму его покупок (только для авторизованных пользователей).</td>
            </tr>
            <tr>
                <td><code>[user_discount_ranges]</code></td>
                <td>Выводит таблицу всех диапазонов скидок, настроенных в плагине.</td>
            </tr>
        </tbody>
    </table>
    <div class="wrap">
        <h1>Система скидок пользователей</h1>

        <h2>Диапазоны скидок</h2>
        <form method="post">
            <?php wp_nonce_field('uld_save_ranges', 'uld_ranges_nonce'); ?>

            <table class="widefat" style="max-width:600px">
                <thead>
                    <tr>
                        <th>Сумма покупок от</th>
                        <th>Скидка %</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ranges as $range): ?>
                        <tr>
                            <td><input type="number" name="sum[]" value="<?php echo esc_attr($range['sum']); ?>"></td>
                            <td><input type="number" name="percent[]" value="<?php echo esc_attr($range['percent']); ?>"></td>
                        </tr>
                    <?php endforeach; ?>

                    <tr>
                        <td><input type="number" name="sum[]" placeholder="Новая сумма"></td>
                        <td><input type="number" name="percent[]" placeholder="%"></td>
                    </tr>
                </tbody>
            </table>

            <p><button class="button button-primary">Сохранить</button></p>
        </form>

        <hr>

        <h2>Пользователи</h2>

        <table class="widefat">
            <thead>
                <tr>
                    <th>Имя</th>
                    <th>Сумма покупок</th>
                    <th>Скидка %</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user):
                    $total   = uld_get_user_total_spent($user->ID);
                    $percent = uld_get_discount_percent($user->ID);
                    if ($total <= 0) continue;
                ?>
                    <tr>
                        <td><?php echo esc_html($user->display_name); ?></td>
                        <td><?php echo wc_price($total); ?></td>
                        <td><strong><?php echo esc_html($percent); ?>%</strong></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php
}

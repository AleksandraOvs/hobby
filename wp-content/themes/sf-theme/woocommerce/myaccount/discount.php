<?php
defined('ABSPATH') || exit;
?>

<h3>Моя скидка</h3>

<div class="myaccount-discount">

    <?php
    // Шорткод выполняется прямо здесь
    echo do_shortcode('[user_discount]');
    ?>



    <?php
    // Шорткод выполняется прямо здесь
    echo do_shortcode('[user_discount_ranges]');
    ?>
</div>
<!-- Способ получения -->
<div class="checkout-section">
    <div class="checkout-section__title">Способ получения!</div>
    <div class="checkout-section__content">

        <?php
        $selected = $_POST['custom_delivery_method'] ?? '';
        ?>

        <div class="custom-delivery-options">
            <div class="delivery-option">
                <label class="delivery-option__inner">
                    <span class="delivery-option__left">
                        <input type="radio"
                            name="custom_delivery_method"
                            value="Самовывоз"
                            <?php checked($selected, 'Самовывоз'); ?>>
                        <span class="delivery-option__title">Самовывоз</span>
                    </span>
                    <span class="delivery-option__desc">
                        За 60 минут, бесплатно
                    </span>
                </label>
            </div>

            <div class="pickup-fields">
                <div class="form-row">
                    <?php
                    woocommerce_form_field(
                        'billing_country',
                        $checkout->get_checkout_fields()['billing']['billing_country'],
                        $checkout->get_value('billing_country')
                    );
                    ?>
                </div>

                <div class="form-row">
                    <label>Адрес</label>
                    <input type="text" name="pickup_address" placeholder="Введите адрес">
                </div>
            </div>

            <div class="delivery-option">
                <label class="delivery-option__inner">
                    <span class="delivery-option__left">
                        <input type="radio"
                            name="custom_delivery_method"
                            value="Доставка курьером"
                            <?php //checked($selected, 'Доставка курьером'); 
                            ?>>
                        <span class="delivery-option__title">Доставка курьером</span>
                    </span>
                    <span class="delivery-option__desc">
                        Завтра, от 500 руб.
                    </span>
                </label>
            </div>

            <div class="delivery-option">
                <label class="delivery-option__inner">
                    <span class="delivery-option__left">
                        <input type="radio"
                            name="custom_delivery_method"
                            value="Транспортная компания"
                            <?php //checked($selected, 'Транспортная компания'); 
                            ?>>
                        <span class="delivery-option__title">Транспортная компания</span>
                    </span>
                    <span class="delivery-option__desc">
                        От 2 дней
                    </span>
                </label>
            </div>

            <div class="delivery-option">
                <label class="delivery-option__inner">
                    <span class="delivery-option__left">
                        <input type="radio"
                            name="custom_delivery_method"
                            value="Почта России"
                            <?php //checked($selected, 'Почта России'); 
                            ?>>
                        <span class="delivery-option__title">Почта России</span>
                    </span>
                    <span class="delivery-option__desc">
                        От 6 дней
                    </span>
                </label>
            </div>

            <div class="delivery-option">
                <label class="delivery-option__inner">
                    <span class="delivery-option__left">
                        <input type="radio"
                            name="custom_delivery_method"
                            value="Наиболее выгодный вариант"
                            <?php //checked($selected, 'Наиболее выгодный вариант'); 
                            ?>>
                        <span class="delivery-option__title">Наиболее выгодный вариант</span>
                    </span>
                    <span class="delivery-option__desc">
                        Подберем для Вас наиболее выгодный вариант
                    </span>
                </label>
            </div>

        </div>
    </div>


    <?php
    // echo '<pre>';
    // print_r(WC()->shipping()->get_packages());
    // echo '</pre>';
    ?>
    <?php

    $packages = WC()->cart->get_shipping_packages();

    foreach ($packages as $package_index => $package) {

        $rates = WC()->shipping()->calculate_shipping_for_package($package);

        if (!empty($rates['rates'])) {

            foreach ($rates['rates'] as $rate_id => $rate) {

                $label = $rate->get_label();
                $cost  = wc_price($rate->get_cost());
    ?>

                <div class="delivery-option wc-delivery-option">
                    <label class="delivery-option__inner">

                        <span class="delivery-option__left">
                            <input type="radio"
                                name="shipping_method[<?php echo $package_index; ?>]"
                                value="<?php echo esc_attr($rate_id); ?>">

                            <span class="delivery-option__title">
                                <?php echo esc_html($label); ?>
                            </span>
                        </span>

                        <span class="delivery-option__desc">
                            <?php echo $cost; ?>
                        </span>

                    </label>
                </div>

    <?php
            }
        }
    }
    ?>

    <?php wc_cart_totals_shipping_html(); ?>



</div>
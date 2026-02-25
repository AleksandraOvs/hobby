<!--== Start Footer Section ===-->
<footer id="footer-area">
    <div class="container">
        <div class="footer-col">
            <?php
            $footer_logo = get_theme_mod('footer_logo');
            $img = wp_get_attachment_image_src($footer_logo, 'full');
            if ($img) : echo '<a class="custom-logo-link" href="' . site_url() . '"><img src="' . $img[0] . '" alt=""></a>';
            endif;
            ?>

            <div class="site-info">
                <span>&copy;<?php echo date('Y'); ?>.Все права защищены</span>
            </div>

        </div>

        <?php if (is_active_sidebar('footer-1')) : ?>
            <div class="footer-col">
                <?php dynamic_sidebar('footer-1'); ?>
            </div>
        <?php endif; ?>

        <?php if (is_active_sidebar('footer-2')) : ?>
            <div class="footer-col">
                <?php dynamic_sidebar('footer-2'); ?>
            </div>
        <?php endif; ?>

        <div class="footer-col">
            <h3>Напишите нам</h3>
            <!-- Мессенджеры -->
            <?php
            // Мессенджеры
            $mes1 = get_field('mes_1', 'option');
            $mes2 = get_field('mes_2', 'option');
            $mes3 = get_field('mes_3', 'option');
            ?>
            <div class="contacts-messengers">
                <?php
                $messengers = [$mes1, $mes2, $mes3];

                foreach ($messengers as $index => $mes):
                    if (!$mes) continue;

                    $icon_key = "mes" . ($index + 1) . "_icon";
                    $text_key = "mes" . ($index + 1) . "_text";
                    $link_key = "mes" . ($index + 1) . "_link";

                    // безопасный доступ к Link полю
                    $url = (isset($mes[$link_key]['url']) && is_string($mes[$link_key]['url'])) ? $mes[$link_key]['url'] : '';
                    $target = (isset($mes[$link_key]['target']) && is_string($mes[$link_key]['target'])) ? $mes[$link_key]['target'] : '';
                    $text = !empty($mes[$text_key]) ? $mes[$text_key] : '';
                    $icon = !empty($mes[$icon_key]) ? $mes[$icon_key] : '';
                ?>
                    <?php if ($url): ?>
                        <a class="messenger messenger-<?php echo $index + 1; ?>" href="<?php echo esc_url($url); ?>" <?php if ($target) echo 'target="' . esc_attr($target) . '"'; ?>>
                            <?php if ($icon): ?>
                                <img src="<?php echo esc_url($icon); ?>" alt="<?php echo esc_html($text); ?>">
                            <?php endif; ?>

                        </a>
                    <?php elseif ($text): ?>
                        <span class="messenger messenger-<?php echo $index + 1; ?>">
                            <?php if ($icon): ?>
                                <img src="<?php echo esc_url($icon); ?>" alt="<?php echo esc_html($text); ?>">
                            <?php endif; ?>

                        </span>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <?php if (is_active_sidebar('footer-3')) : ?>
                <div class="links-sidebar">
                    <?php dynamic_sidebar('footer-3'); ?>
                </div>
            <?php endif; ?>
        </div>


    </div>
</footer>
<!--== End Footer Section ===-->

<?php if (!is_cart()) : ?>
    <!--== Start Mini Cart Wrapper ==-->
    <div class="mfp-hide modal-minicart" id="miniCart-popup">
        <div class="minicart-content-wrap">

            <?php woocommerce_mini_cart()
            ?>


        </div>
    </div>
    <!--== End Mini Cart Wrapper ==-->

<?php endif; ?>

<?php
if (current_user_can('administrator')) {
?>
    <div class="show-temp"><?php echo get_current_template(); ?> </div>
<?php
}
?>
<?php wp_footer() ?>

<div id="main-form" style="display:none;">
    <?php
    $form = get_field('contact_form', 'option');

    if ($form) {
        echo do_shortcode('[contact-form-7 id="' . $form->ID . '"]');
    }
    ?>
</div>

<div id="popup-map" style="display:none;">
    <?php
    $map_code = get_field('map_code', 'option');

    if ($map_code) {
        echo '<div class="popup-map__inner">';
        echo $map_code;
        echo '</div>';
    }
    ?>
</div>

<div class="arrow-up">
    <svg width="20" height="12" viewBox="0 0 20 12" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M2.5836 11.4345C2.01856 12.0351 1.07065 12.0634 0.470085 11.4988C-0.130859 10.9341 -0.159204 9.98583 0.405457 9.38526L8.83418 0.469731C9.39884 -0.130835 10.3471 -0.15918 10.9477 0.405482C13.819 3.27679 16.6381 6.42098 19.4407 9.38526C20.0053 9.98583 19.977 10.9341 19.3764 11.4988C18.7755 12.0634 17.8276 12.0351 17.2629 11.4345L9.92306 3.67099L2.5836 11.4345Z" fill="white" />
    </svg>

</div>


<script>
    (function() {
        let lastScrollTop = document.documentElement.scrollTop || document.body.scrollTop;

        const observer = new MutationObserver(() => {
            const currentScrollTop = document.documentElement.scrollTop || document.body.scrollTop;
            if (currentScrollTop !== lastScrollTop) {
                console.group('⚠ Scroll изменился!');
                console.log('Старое значение:', lastScrollTop);
                console.log('Новое значение:', currentScrollTop);
                console.trace(); // Показывает, какой скрипт вызвал изменение
                console.groupEnd();
                lastScrollTop = currentScrollTop;
            }
        });

        // Следим за изменениями всего документа (DOM + атрибуты)
        observer.observe(document.documentElement, {
            childList: true,
            subtree: true,
            attributes: true,
            characterData: true
        });

        // Также ловим прямое изменение scrollTop через JS
        const originalScrollTop = Object.getOwnPropertyDescriptor(HTMLElement.prototype, 'scrollTop');
        if (originalScrollTop && originalScrollTop.set) {
            Object.defineProperty(document.documentElement, 'scrollTop', {
                set(value) {
                    console.group('⚠ Установка scrollTop!');
                    console.log('Новое значение:', value);
                    console.trace();
                    console.groupEnd();
                    lastScrollTop = value;
                    originalScrollTop.set.call(this, value);
                }
            });
        }

        //console.log('🟢 Диагностика scrollTop активирована. Обнови корзину или элемент, чтобы отследить скрипт.');
    })();
</script>

<script src="https://unpkg.com/imask"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        function initPhoneMask() {
            const inputs = document.querySelectorAll('.js-phone-mask');

            inputs.forEach(function(input) {
                if (!input.classList.contains('masked')) {
                    IMask(input, {
                        mask: '+{7}(000)000-00-00'
                    });
                    input.classList.add('masked');
                }
            });
        }

        initPhoneMask();

        document.addEventListener('wpcf7mailsent', initPhoneMask);
        document.addEventListener('wpcf7invalid', initPhoneMask);

    });
</script>
</body>

</html>
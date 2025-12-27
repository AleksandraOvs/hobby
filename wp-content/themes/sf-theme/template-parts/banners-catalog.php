<div class="banners-catalog">

    <?php
    $banner = get_field('banner_1');

    if ($banner) {

        $head        = $banner['banner_1_head'] ?? '';
        $description = $banner['banner_1_description'] ?? '';
        $link        = $banner['banner_1_link'] ?? '';
        $img         = $banner['banner_1_img'] ?? null;
    }
    ?>



    <?php if (! empty($banner)): ?>
        <?php if ($link): ?>

            <a href="<?php echo esc_url($link); ?>" class="banner">

                <?php if ($img) { ?>
                    <img class="banner-background" src="<?php echo esc_url($img['url'] ?? ''); ?>" alt="">
                    <div class="banner-content">
                        <?php if ($head): ?>
                            <h2 class="banner-title">
                                <?php echo esc_html($head); ?>
                            </h2>
                        <?php endif; ?>

                        <?php if ($description): ?>
                            <div class="banner-text">
                                <?php echo wp_kses_post($description); ?>
                            </div>
                        <?php endif; ?>

                    </div>
                    <svg class="banner-arrow" width="48" height="9" viewBox="0 0 48 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 3.21917H44.9276L43.2673 1.5456C42.9158 1.1941 42.9158 0.616966 43.2673 0.26547C43.6192 -0.086404 44.1891 -0.0894275 44.5406 0.262069L47.7426 3.46182C48.0877 3.81104 48.0843 4.38024 47.7369 4.72682L44.5406 7.92317C44.191 8.27316 43.6173 8.27316 43.2673 7.92317C42.9158 7.57167 42.9158 7.01571 43.2673 6.66421L44.9276 5.01898H0V3.21917Z" fill="#fff" />
                    </svg>

                <?php } else {
                ?>
                    <div class="banner-content full-width">
                        <?php if ($head): ?>
                            <h2 class="banner-title">
                                <?php echo esc_html($head); ?>
                            </h2>
                        <?php endif; ?>

                        <?php if ($description): ?>
                            <div class="banner-text">
                                <?php echo wp_kses_post($description); ?>
                            </div>
                        <?php endif; ?>

                    </div>
                    <svg class="banner-arrow" width="48" height="9" viewBox="0 0 48 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 3.21917H44.9276L43.2673 1.5456C42.9158 1.1941 42.9158 0.616966 43.2673 0.26547C43.6192 -0.086404 44.1891 -0.0894275 44.5406 0.262069L47.7426 3.46182C48.0877 3.81104 48.0843 4.38024 47.7369 4.72682L44.5406 7.92317C44.191 8.27316 43.6173 8.27316 43.2673 7.92317C42.9158 7.57167 42.9158 7.01571 43.2673 6.66421L44.9276 5.01898H0V3.21917Z" fill="#8B4512" />
                    </svg>
                <?php

                } ?>
            </a>
        <?php endif; ?>
    <?php endif; ?>

    <?php
    $banner2 = get_field('banner_2');

    if ($banner2) {

        $head2        = $banner2['banner_2_head'] ?? '';
        $description2 = $banner2['banner_2_description'] ?? '';
        $link2        = $banner2['banner_2_link'] ?? '';
        $img2         = $banner2['banner_2_img'] ?? null;
    }
    ?>

    <?php if (! empty($banner2)): ?>
        <?php if ($link2): ?>

            <a href="<?php echo esc_url($link2); ?>" class="banner">

                <?php if ($img2) { ?>
                    <img class="banner-background" src="<?php echo esc_url($img2['url'] ?? ''); ?>" alt="">
                    <div class="banner-content">
                        <?php if ($head2): ?>
                            <h2 class="banner-title">
                                <?php echo esc_html($head2); ?>
                            </h2>
                        <?php endif; ?>

                        <?php if ($description2): ?>
                            <div class="banner-text">
                                <?php echo wp_kses_post($description2); ?>
                            </div>
                        <?php endif; ?>





                    </div>
                    <svg class="banner-arrow" width="48" height="9" viewBox="0 0 48 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 3.21917H44.9276L43.2673 1.5456C42.9158 1.1941 42.9158 0.616966 43.2673 0.26547C43.6192 -0.086404 44.1891 -0.0894275 44.5406 0.262069L47.7426 3.46182C48.0877 3.81104 48.0843 4.38024 47.7369 4.72682L44.5406 7.92317C44.191 8.27316 43.6173 8.27316 43.2673 7.92317C42.9158 7.57167 42.9158 7.01571 43.2673 6.66421L44.9276 5.01898H0V3.21917Z" fill="#fff" />
                    </svg>

                <?php } else {
                ?>
                    <div class="banner-content full-width">
                        <?php if ($head2): ?>
                            <h2 class="banner-title">
                                <?php echo esc_html($head2); ?>
                            </h2>
                        <?php endif; ?>

                        <?php if ($description2): ?>
                            <div class="banner-text">
                                <?php echo wp_kses_post($description2); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <svg class="banner-arrow" width="48" height="9" viewBox="0 0 48 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 3.21917H44.9276L43.2673 1.5456C42.9158 1.1941 42.9158 0.616966 43.2673 0.26547C43.6192 -0.086404 44.1891 -0.0894275 44.5406 0.262069L47.7426 3.46182C48.0877 3.81104 48.0843 4.38024 47.7369 4.72682L44.5406 7.92317C44.191 8.27316 43.6173 8.27316 43.2673 7.92317C42.9158 7.57167 42.9158 7.01571 43.2673 6.66421L44.9276 5.01898H0V3.21917Z" fill="#8B4512" />
                    </svg>
                <?php

                } ?>
            </a>
        <?php endif; ?>
    <?php endif; ?>



</div>
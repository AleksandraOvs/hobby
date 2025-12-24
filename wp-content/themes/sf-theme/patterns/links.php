<?php

/**
 * Title: Ссылки
 * Slug: sf-theme/links
 * Categories: text, layout
 * Description: Блок ссылок для страницы
 */
?>
<!-- wp:group {"layout":{"type":"flex","className":"page-links","orientation":"horizontal","justifyContent":"space-between","flexWrap":"wrap"},"style":{"spacing":{"gap":"16px"}}} -->
<div class="wp-block-group page-links">
    <!-- wp:buttons -->
    <div class="wp-block-buttons">
        <!-- wp:button {"className":"page-links__item"} -->
        <div class="wp-block-button page-links__item"> <a class="wp-block-button__link wp-element-button" href="#">Кнопка 1</a> </div>
        <!-- /wp:button -->

        <!-- wp:button {"className":"page-links__item"} -->
        <div class="wp-block-button page-links__item"> <a class="wp-block-button__link wp-element-button" href="#">Кнопка 2</a> </div>
        <!-- /wp:button -->

        <!-- wp:button {"className":"page-links__item"} -->
        <div class="wp-block-button page-links__item"> <a class="wp-block-button__link wp-element-button" href="#">Кнопка 3</a> </div>
        <!-- /wp:button -->
    </div>
    <!-- /wp:buttons -->
</div> <!-- /wp:group -->
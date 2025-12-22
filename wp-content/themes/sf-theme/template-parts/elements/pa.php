<?php
$attributes = $product->get_attributes();

if ($attributes): ?>
    <div class="product-specs">

        <?php foreach ($attributes as $attribute):

            // Название характеристики
            $label = wc_attribute_label($attribute->get_name());

            // Значения
            if ($attribute->is_taxonomy()) {
                $values = wc_get_product_terms(
                    $product->get_id(),
                    $attribute->get_name(),
                    ['fields' => 'names']
                );
                $value = implode(', ', $values);
            } else {
                $value = implode(', ', $attribute->get_options());
            }

            if (!$value) continue;
        ?>

            <div class="product-specs__row">
                <div class="product-specs__name">
                    <?php echo esc_html($label); ?>
                </div>
                <div class="product-specs__value">
                    <?php echo esc_html($value); ?>
                </div>
            </div>

        <?php endforeach; ?>

    </div>
<?php endif; ?>
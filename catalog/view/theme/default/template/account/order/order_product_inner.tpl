<label class="order-product-items image-zoom-hover" >
    <div class="image-container">
        <img src="<?= $thumb; ?>" alt="<?= $name; ?>" title="<?= $name; ?>" class="img-responsive" />
    </div>
    <input type="hidden" name="products[<?= $i; ?>][product_id]" value="<?= $product_id; ?>" />
    <?= $name; ?>

    <?php if($option) { ?>
    <div class="order-product-option">
        <div class="order-product-option-title">
            <?= $product_options; ?>
        </div>

        <?php foreach($option as $opt){ ?>
            <div class="order-product-option-values">
                <?= $opt['name']; ?>: <?= $opt['value_to_show']; ?>
                <textarea class="hidden" name="products[<?= $i; ?>][option][<?= $opt['product_option_value_id']; ?>]"><?= $opt['value']; ?></textarea>
            </div>            
        <?php } ?>
    </div>
    <?php } ?>

    <div class="order-product-quantity">
        <?= $product_quantity; ?>: <?= $quantity; ?>
        <input type="hidden" name="products[<?= $i; ?>][quantity]" value="<?= $quantity; ?>" />
    </div>
    
</label>
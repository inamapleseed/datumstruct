<div class="free-shipping-indicator">
    <div class="free-shipping-bar-area">
        <div class="free-shipping-text">
            <?= $text; ?>
        </div>
        <div class="free-shipping-group">
            <div class="free-shipping-progress <?= $freed; ?>">
                <div class="free-shipping-progress-bar" style='width: <?= $percentage; ?>%;'></div>
            </div>
            <div class="free-shipping-icon">
                <span class="free-shipping-label" ><?= $text_free_label; ?></span>
                <i class="fa fa-truck"></i>
            </div>
        </div>
    </div>
</div>
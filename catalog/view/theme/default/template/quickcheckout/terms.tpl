<div id="payment" class="text-left" style="display:none;"></div>
<div class="terms">
  <label><?php if ($text_agree) { ?>
    <input type="checkbox" name="agree" value="1" />
    <?php echo $text_agree; ?>
  <?php } ?></label>
  <button type="button" id="button-payment-method" class="btn btn-primary" data-loading-text="<?php echo $text_loading; ?>"><?php echo $button_continue; ?></button>
</div>
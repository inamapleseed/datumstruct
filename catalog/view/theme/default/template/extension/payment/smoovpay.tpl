<?php if ($testmode) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $text_testmode; ?></div>
<?php } ?>
<form action="<?php echo $action; ?>" method="post">
  <input type="hidden" name="version" value="2.0" />
  <input type="hidden" name="action" value="<?php echo $transaction; ?>" />
  <input type="hidden" name="merchant" value="<?php echo $merchant; ?>" />
  <input type="hidden" name="ref_id" value="<?php echo $ref_id; ?>" />
  <?php $count = 1; ?>
  <?php foreach ($products as $product) { ?>
  <input type="hidden" name="item_name_<?php echo $count; ?>" value="<?php echo $product['name']; ?>" />
  <input type="hidden" name="item_description_<?php echo $count; ?>" value="<?php echo $product['model']; ?>" />
  <input type="hidden" name="item_quantity_<?php echo $count; ?>" value="<?php echo $product['quantity']; ?>" />
  <input type="hidden" name="item_amount_<?php echo $count; ?>" value="<?php echo $product['price']; ?>" />
  <?php $count++; ?>
  <?php } ?>
  <?php if ($discount) { ?>
  <input type="hidden" name="discount_amount" value="<?php echo $discount; ?>" />
  <?php } ?>
  <input type="hidden" name="currency" value="<?php echo $currency_code; ?>" />
  <input type="hidden" name="total_amount" value="<?php echo $total; ?>" />
  <input type="hidden" name="success_url" value="<?php echo $success; ?>" />
  <input type="hidden" name="cancel_url" value="<?php echo $cancel; ?>" />
  <input type="hidden" name="str_url" value="<?php echo $callback; ?>" />
  <input type="hidden" name="signature" value="<?php echo $signature; ?>" />
  <input type="hidden" name="signature_algorithm" value="sha1" />
  <div class="buttons">
    <div class="pull-right">
      <input type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary" />
    </div>
  </div>
</form>
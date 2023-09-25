<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>

  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h2><?php echo $heading_title; ?></h2>
      <p>Thank you for your order, please click the button below to pay using your credit card with PayHere by Merchant e-Solutions.</p>
      <form action="<?php echo $action; ?>" method="post" id="mes_hc_form">
          <input type="hidden" name="profile_id" value="<?php echo $profile_id; ?>">
          <input type="hidden" name="invoice_number" value="<?php echo $invoice_number; ?>">
          <input type="hidden" name="transaction_amount" value="<?php echo $transaction_amount; ?>">
          <input type="hidden" name="use_merch_receipt" value="<?php echo $use_merch_receipt; ?>">
          <?php if (isset($transaction_key)) { ?>
          <input type="hidden" name="transaction_key" value="<?php echo $transaction_key; ?>">
          <?php } ?>
          <?php if (isset($use_simulator)) { ?>
          <input type="hidden" name="use_simulator" value="<?php echo $use_simulator; ?>">
          <?php } ?>
          <input type="hidden" name="cardholder_street_address" value="<?php echo $cardholder_street_address; ?>">
          <input type="hidden" name="cardholder_zip" value="<?php echo $cardholder_zip; ?>">
          <input type="hidden" name="return_url" value="<?php echo $return_url; ?>">
          <input type="hidden" name="cancel_url" value="<?php echo $cancel_url; ?>">
      <br />
      <div class="buttons">
        <div class="pull-right"><button type="submit" class="btn btn-primary"><?php echo $button_confirm; ?></button></div>
      </div>
      </form>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
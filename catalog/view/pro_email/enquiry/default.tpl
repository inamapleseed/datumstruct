<style>
.invoice {
	border-collapse: collapse;
	width: 100%;
	margin: 15px 0;
}
.invoice td {
  line-height:16px;
  font-size:12px;
	padding: 7px;
	border: 1px solid <?php echo $config->get('proemail_color_tborder') ? $config->get('proemail_color_tborder') : '#ddd'; ?>;
}
.invoice thead td {
	background-color: <?php echo $config->get('proemail_color_thead') ? $config->get('proemail_color_thead') : '#efefef'; ?>;
	color: <?php echo $config->get('proemail_color_theadtxt') ? $config->get('proemail_color_theadtxt') : '#444'; ?>;
	font-weight: bold;
}
.invoice tbody td {
	vertical-align: top;
}
</style>
  <table class="invoice">
    <thead>
      <tr>
        <td colspan="2"><?php echo $text_enquiry_detail; ?></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="width:50%;">
		  <?php if($invoice_no){ ?><b><?php echo $text_invoice_no; ?></b> <?php echo $invoice_prefix . $invoice_no; ?><br /><?php } ?>
		  <b><?php echo $text_order_id; ?></b> <?php echo $enquiry_order_id; ?><br />
          <b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?><br />
          <!-- <b><?php echo $text_payment_method; ?></b> <?php echo $payment_method; ?><br /> -->
          </td>
        <!--
        <td>
        <?php echo $store_name; ?><br />
        <?php echo $store_address; ?><br />
        <b><?php echo $text_telephone; ?></b> <?php echo $store_telephone; ?><br />
        <?php if ($store_fax) { ?><b><?php echo $text_fax; ?></b> <?php echo $store_fax; ?><br /><?php } ?>
        <b><?php echo $text_email; ?></b> <?php echo $store_email; ?>
          </td>
        -->
      </tr>
    </tbody>
  </table>
  <table class="invoice">
    <thead>
      <tr>
        <td><?php echo $text_payment_address; ?></td>        
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?php echo $payment_address; ?>
			<br /><?php echo $text_email; ?> <?php echo $email; ?>
			<br /><?php echo $text_telephone; ?> <?php echo $telephone; ?>
			<?php if ($payment_company_id || $payment_tax_id) { ?><br/><?php } ?>
			<?php if ($payment_company_id) { ?><br /><?php echo $language->get('text_company_id'); ?> <?php echo $payment_company_id; ?><?php } ?>
			<?php if ($payment_tax_id) { ?><br /><?php echo $language->get('text_tax_id'); ?> <?php echo $payment_tax_id; ?><?php } ?>
      <?php foreach ($custom_fields as $custom_field) { ?>
        <br /><?php echo $custom_field['name']; ?>: <?php echo $custom_field['value']; ?>
      <?php } ?>
		</td>
        <?php if ($shipping_address) { ?>
        <td style="width:50%"><?php echo $shipping_address; ?></td>
        <?php } ?>
      </tr>
    </tbody>
  </table>
  <table class="invoice">
    <thead>
      <tr>
		<?php foreach ($columns as $col) { ?>
      <td
      <?php if($col=='quantity') echo 'style="width: 50px; text-align: right;"'; ?>
      ><?php echo $language->get('column_'.$col); ?></td>
    <?php } ?>
    <!--
        <td width="70"><?php if ($config->get('pdf_invoice_total_tax')) { echo $language->get('column_total_tax'); } else { echo $language->get('column_total'); } ?></td>
    -->
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $product) { ?>
      <tr>
		<?php foreach ($columns as $col) { ?>
			<td <?php if(in_array($col, array('weight', 'quantity', 'price', 'tax', 'tax_rate', 'tax_total', 'price_tax', 'total'))){ ?>class="right"<?php } ?> <?php if(in_array($col, array('image', 'quantity'))){ ?>style="width:1px"<?php } ?>>
				<?php if($col == 'product'){ ?>
					<?php echo $product['name']; ?>
					<?php foreach ($product['option'] as $option) { ?>
					<br />
					&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
					<?php } ?>
				<?php }elseif($col == 'image'){ ?>
					<a href="<?php echo $product['href'] ?>" target="_blank"><img src="<?php echo $product['image'] ?>" alt="" style="max-width:none"/></a>
				<?php }else{ ?>
					<?php echo isset($product[$col]) ? $product[$col] : ''; ?>
				<?php } ?>
			</td>
    <?php } ?>
    <!--
        <td class="right"><?php echo $product['total_tax']; ?></td>
      -->
      </tr>
      <?php } ?>
      <?php if(isset($vouchers)) foreach ($vouchers as $voucher) { ?>
      <tr>
		<?php foreach ($columns as $col) { ?>
			<?php if($col == 'product'){ ?>
				<td><?php echo $voucher['description']; ?></td>
			<?php }elseif($col == 'quantity'){ ?>
				<td class="right">1</td>
			<?php }elseif($col == 'price'){ ?>
				<td class="right"><?php echo $voucher['amount']; ?></td>
			<?php }else{ ?>
				<td></td>
			<?php } ?>
		<?php } ?>
        <td class="right"><?php echo $voucher['amount']; ?></td>
      </tr>
      <?php } ?>
      <?php foreach ($totals as $total) { ?>
      <tr>
        <td colspan="<?php echo count($columns) - 1; ?>" class="right"><b><?php echo $total['title']; ?>:</b></td>
        <td class="right"><?php echo $total['text']; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  <?php if ($order_comment) { ?>
  <table class="invoice">
    <thead><tr><td><?php echo $language->get('text_instruction'); ?></td></tr></thead>
    <tbody><tr><td><?php echo $order_comment; ?></td></tr></tbody>
  </table>
  <?php } ?>
  <?php if ($comment) { ?>
  <table class="invoice">
    <thead><tr><td><?php echo $language->get('text_customer_comment'); ?></td></tr></thead>
    <tbody><tr><td><?php echo $comment; ?></td></tr></tbody>
  </table>
  <?php } ?>
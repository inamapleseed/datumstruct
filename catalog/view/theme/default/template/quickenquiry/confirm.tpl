<?php if (!isset($redirect)) { ?>
  <?php if ($confirmation_page) { ?>
	<div class="table-responsive">
	  <table class="table table-bordered table-hover">
		<thead>
		  <tr>
			<td class="text-left"><?php echo $column_name; ?></td>
			<td class="text-left"><?php echo $column_model; ?></td>
			<td class="text-right"><?php echo $column_quantity; ?></td>
			<!--
			<td class="text-right"><?php echo $column_price; ?></td>
			<td class="text-right"><?php echo $column_total; ?></td>
			-->
		  </tr>
		</thead>
		<tbody>
		  <?php foreach ($products as $product) { ?>
		  <tr>
			<td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
			  <?php foreach ($product['option'] as $option) { ?>
			  <br />
			  &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
			  <?php } ?>
			  <?php if($product['recurring']) { ?>
			  <br />
			  <span class="label label-info"><?php echo $text_recurring; ?></span> <small><?php echo $product['recurring']; ?></small>
			  <?php } ?></td>
			<td class="text-left"><?php echo $product['model']; ?></td>
			<td class="text-right"><?php echo $product['quantity']; ?></td>
			<!--
			<td class="text-right"><?php echo $product['price']; ?></td>
			<td class="text-right"><?php echo $product['total']; ?></td>
			-->
		  </tr>
		  <?php } ?>
		  
		</tbody>
		<tfoot>
		  <?php foreach ($totals as $total) { ?>
		  <tr>
			<td colspan="2" class="text-right"><strong><?php echo $total['title']; ?>:</strong></td>
			<td class="text-right"><?php echo $total['text']; ?></td>
		  </tr>
		  <?php } ?>
		</tfoot>
	  </table>
	</div>
  <?php } ?>
  <div class="payment"><?php echo $payment; ?></div>
  <a class="btn btn-danger pull-left" href="<?php echo $back; ?>"><?php echo $button_back; ?></a>
  
  <script type="text/javascript"><!--
  <?php if ($payment_target && $auto_submit) { ?>
  $('.payment').find('<?php echo $payment_target; ?>').trigger('click');
  
  setTimeout(function() {
	  $('#quickcheckoutconfirm').show();
	  $('#payment').show();
	  $('.fa-spinner').remove();
  }, 4000);
  <?php } ?>
  //--></script> 
<?php } else { ?>
<script type="text/javascript"><!--
location = '<?php echo $redirect; ?>';
//--></script>
<?php } ?>
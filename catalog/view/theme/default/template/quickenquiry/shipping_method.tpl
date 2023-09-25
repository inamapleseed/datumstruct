<?php if ($error_warning) { ?>
<div class="alert alert-danger"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($shipping_methods) { ?>
<p><?php echo $text_shipping_method; ?></p>
<?php if ($shipping) { ?>

<?php if($general_description){ ?>
	<div class="alert alert-info">
		<?= $general_description; ?>
	</div>
<?php } ?>

<table class="table table-hover">
	<?php foreach ($shipping_methods as $key => $shipping_method) { ?>

	<!--
  <tr>
    <td colspan="3" width="160px">
	  <?php if (!empty($shipping_logo[$key])) { ?>
	  <img src="<?php echo $shipping_logo[$key]; ?>" alt="<?php echo $shipping_method['title']; ?>" title="<?php echo $shipping_method['title']; ?>" class="img-responsive" />
	  <?php } else { ?>
	  <b><?php echo $shipping_method['title']; ?></b>
	  <?php } ?>
	</td>
	</tr>
	-->

  <?php if (!$shipping_method['error']) { ?>
  <?php foreach ($shipping_method['quote'] as $quote) { ?>
  <tr>
    <td><?php if ($quote['code'] == $code) { ?>
      <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" checked="checked" />
      <?php } else { ?>
      <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" />
      <?php } ?></td>
    <td><label for="<?php echo $quote['code']; ?>"><?php echo $quote['title']; ?></label></td>
    <td style="text-align: right;"><label for="<?php echo $quote['code']; ?>"><?php echo $quote['text']; ?></label></td>
  </tr>
  <?php } ?>
  <?php } else { ?>
  <tr>
    <td colspan="3"><div class="error"><?php echo $shipping_method['error']; ?></div></td>
  </tr>
  <?php } ?>
	
  <?php } ?>
</table>
<?php } else { ?>
  <select class="form-control" name="shipping_method">
   <?php foreach ($shipping_methods as $shipping_method) { ?>
     <?php if (!$shipping_method['error']) { ?>
		<?php foreach ($shipping_method['quote'] as $quote) { ?>
		  <?php if ($quote['code'] == $code) { ?>
		    <?php $code = $quote['code']; ?>
			<?php $exists = true; ?>
			<option value="<?php echo $quote['code']; ?>" selected="selected">
		  <?php } else { ?>
			<option value="<?php echo $quote['code']; ?>">
		  <?php } ?>
		  <?php echo $quote['title']; ?>&nbsp;&nbsp;(<?php echo $quote['text']; ?>) </option>
		<?php } ?>
	 <?php } ?>
   <?php } ?>
  </select><br />
<?php } ?>

<?php } ?>
<?php if ($delivery && (!$delivery_delivery_time || $delivery_delivery_time == '1' || $delivery_delivery_time == '3')) { ?>
	
<div<?php echo $delivery_required ? ' class="required"' : ''; ?>>
  <label class="control-label"><strong><?php echo $text_delivery; ?></strong></label>
  <?php if ($delivery_delivery_time == '1') { ?>
  <input type="text" name="delivery_date" value="<?php echo $delivery_date; ?>" class="form-control date" readonly="true" style="background:#ffffff;" />
  <?php } else { ?>
  <input type="text" name="delivery_date" value="<?php echo $delivery_date; ?>" class="form-control date" readonly="true" style="background:#ffffff;" />
  <?php } ?>
  <?php if ($delivery_delivery_time == '3') { ?><br />
    <select name="delivery_time" class="form-control"><?php foreach ($delivery_times as $quickcheckout_delivery_time) { ?>
    <?php if (!empty($quickcheckout_delivery_time[$language_id])) { ?>
      <?php if ($delivery_time == $quickcheckout_delivery_time[$language_id]) { ?>
	  <option value="<?php echo $quickcheckout_delivery_time[$language_id]; ?>" selected="selected"><?php echo $quickcheckout_delivery_time[$language_id]; ?></option>
	  <?php } else { ?>
	  <option value="<?php echo $quickcheckout_delivery_time[$language_id]; ?>"><?php echo $quickcheckout_delivery_time[$language_id]; ?></option>
      <?php } ?>
	<?php } ?>
    <?php } ?></select>
  <?php } ?>
</div>
<?php } elseif ($delivery_delivery_time && $delivery_delivery_time == '2') { ?>
	
  <input type="text" name="delivery_date" value="" class="hide" />
  <select name="delivery_time" class="hide"><option value=""></option></select>
  <strong><?php echo $text_estimated_delivery; ?></strong><br />
  <?php echo $estimated_delivery; ?><br />
  <?php echo $estimated_delivery_time; ?>
<?php } else { ?>
	
  <input type="text" name="delivery_date" value="" class="hide" />
  <select name="delivery_time" class="hide"><option value=""></option></select>
<?php } ?>

<script type="text/javascript"><!--
$('#shipping-method input[name=\'shipping_method\'], #shipping-method select[name=\'shipping_method\']').on('change', function() {
	<?php if (!$logged) { ?>
		if ($('#payment-address input[name=\'shipping_address\']:checked').val()) {
			var post_data = $('#payment-address input[type=\'text\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select, #shipping-method input[type=\'text\'], #shipping-method input[type=\'checkbox\']:checked, #shipping-method input[type=\'radio\']:checked, #shipping-method input[type=\'hidden\'], #shipping-method select, #shipping-method textarea');
		} else {
			var post_data = $('#shipping-address input[type=\'text\'], #shipping-address input[type=\'checkbox\']:checked, #shipping-address input[type=\'radio\']:checked, #shipping-address input[type=\'hidden\'], #shipping-address select, #shipping-method input[type=\'text\'], #shipping-method input[type=\'checkbox\']:checked, #shipping-method input[type=\'radio\']:checked, #shipping-method input[type=\'hidden\'], #shipping-method select, #shipping-method textarea');
		}

		$.ajax({
			url: 'index.php?route=quickenquiry/shipping_method/set',
			type: 'post',
			data: post_data,
			dataType: 'html',
			cache: false,
			success: function(html) {
				<?php if ($cart) { ?>
				loadCart();
				<?php } ?>
				
				<?php if ($shipping_reload) { ?>
				reloadPaymentMethod();
				<?php } ?>
			},
			<?php if ($debug) { ?>
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
			<?php } ?>
		});
	<?php } else { ?>
		if ($('#shipping-address input[name=\'shipping_address\']:checked').val() == 'new') {
			var url = 'index.php?route=quickenquiry/shipping_method/set';
			var post_data = $('#shipping-address input[type=\'text\'], #shipping-address input[type=\'checkbox\']:checked, #shipping-address input[type=\'radio\']:checked, #shipping-address input[type=\'hidden\'], #shipping-address select, #shipping-method input[type=\'text\'], #shipping-method input[type=\'checkbox\']:checked, #shipping-method input[type=\'radio\']:checked, #shipping-method input[type=\'hidden\'], #shipping-method select, #shipping-method textarea');
		} else {
			var url = 'index.php?route=quickenquiry/shipping_method/set&address_id=' + $('#shipping-address select[name=\'address_id\']').val();
			var post_data = $('#shipping-method input[type=\'text\'], #shipping-method input[type=\'checkbox\']:checked, #shipping-method input[type=\'radio\']:checked, #shipping-method input[type=\'hidden\'], #shipping-method select, #shipping-method textarea');
		}
		
		$.ajax({
			url: url,
			type: 'post',
			data: post_data,
			dataType: 'html',
			cache: false,
			success: function(html) {
				<?php if ($cart) { ?>
				loadCart();
				<?php } ?>
				
				<?php if ($shipping_reload) { ?>
				if ($('#payment-address input[name=\'payment_address\']').val() == 'new') {
					reloadPaymentMethod();
				} else {
					reloadPaymentMethodById($('#payment-address select[name=\'address_id\']').val());
				}
				<?php } ?>
			},
			<?php if ($debug) { ?>
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
			<?php } ?>
		});
	<?php } ?>
});

$(document).ready(function() {
	$('#shipping-method input[name=\'shipping_method\']:checked, #shipping-method select[name=\'shipping_method\']').trigger('change');
});

<?php if ($delivery && $delivery_delivery_time == '1') { ?>
$(document).ready(function() {
	$('input[name=\'delivery_date\']').datetimepicker({
		format: 'YYYY-MM-DD HH:mm',
		minDate: '<?php echo $delivery_min; ?>',
		maxDate: '<?php echo $delivery_max; ?>',
		disabledDates: [<?php echo $delivery_unavailable; ?>],
		enabledHours: [<?php echo $hours; ?>],
		ignoreReadonly: true,
		<?php if ($delivery_days_of_week != '') { ?>
		daysOfWeekDisabled: [<?php echo $delivery_days_of_week; ?>]
		<?php } ?>
	});
});
<?php } elseif ($delivery && ($delivery_delivery_time == '3' || $delivery_delivery_time == '0')) { ?>
	$('input[name=\'delivery_date\']').datetimepicker({
		format: 'YYYY-MM-DD',
		minDate: '<?php echo $delivery_min; ?>',
		maxDate: '<?php echo $delivery_max; ?>',
		disabledDates: [<?php echo $delivery_unavailable; ?>],
		ignoreReadonly: true,
		<?php if ($delivery_days_of_week != '') { ?>
		daysOfWeekDisabled: [<?php echo $delivery_days_of_week; ?>]
		<?php } ?>
	});
<?php } ?>
//--></script>


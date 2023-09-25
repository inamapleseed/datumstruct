<?php //debug($fields); ?>

<?php foreach ($fields as $field) { ?>
  <?php if ($field == 'country') { ?>
    <?php if (!empty(${'field_' . $field}['display'])) { ?>
	<div class="col-sm-6<?php echo !empty(${'field_' . $field}['required']) ? ' required' : ''; ?>">
	  <label class="control-label"><?php echo $entry_country; ?></label>
	  <select name="country_id" class="form-control" id="input-payment-country">
	  <?php foreach ($countries as $country) { ?>
		<?php if ($country['country_id'] == $country_id) { ?>
		<option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
		<?php } else { ?>
		<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
		<?php } ?>
	  <?php } ?>
	  </select>
	</div>
		
	<?php } else { ?>
	<select name="country_id" class="hide">
	<?php foreach ($countries as $country) { ?>
	  <?php if ($country['country_id'] == $country_id) { ?>
	  <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
	  <?php } else { ?>
	  <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
	  <?php } ?>
	<?php } ?>
	</select>
	<?php } ?>
  <?php } elseif ($field == 'zone') { ?>
    <?php if (!empty(${'field_' . $field}['display'])) { ?>
	<div class="col-sm-6<?php echo !empty(${'field_' . $field}['required']) ? ' required' : ''; ?>">
	  <label class="control-label"><?php echo $entry_zone; ?></label>
	  <select name="zone_id" class="form-control" id="input-payment-zone"></select>
	</div>
		
	<?php } else { ?>
	  <select name="zone_id" class="hide"></select>
	<?php } ?>
  <?php } elseif ($field == 'customer_group') { ?>
    <?php if (!empty(${'field_' . $field}['display'])) { ?>

	<div class="col-sm-6 required"<?php echo count($customer_groups) <= 1 ? ' style="display:none !important"' : ''; ?>>
	  <label class="control-label"><?php echo $entry_customer_group; ?></label>
	  <select name="customer_group_id" class="form-control" id="input-payment-customer-group">
		<?php foreach ($customer_groups as $customer_group) { ?>
		<option value="<?php echo $customer_group['customer_group_id']; ?>"<?php echo $customer_group['customer_group_id'] == $customer_group_id ? ' selected="selected"' : ''; ?>><?php echo $customer_group['name']; ?></option>
		<?php } ?>
	  </select>
	</div>
		
	<?php } else { ?>
	  <select name="customer_group_id" class="hide">
		<?php foreach ($customer_groups as $customer_group) { ?>
		<option value="<?php echo $customer_group['customer_group_id']; ?>"<?php echo $customer_group['customer_group_id'] == $customer_group_id ? ' selected="selected"' : ''; ?>><?php echo $customer_group['name']; ?></option>
		<?php } ?>
	  </select>
	<?php } ?>
  <?php } else { ?>
	<?php if (!empty(${'field_' . $field}['display'])) { ?>

	<div <?php echo $field == 'postcode' ? ' id="payment-postcode-required"' : ''; ?> class=" col-sm-6<?php echo !empty(${'field_' . $field}['required']) ? ' required' : ''; ?>">
	  <label class="control-label" for="input-payment-<?php echo str_replace('_', '-', $field); ?>"><?php echo ${'entry_' . $field}; ?></label>
	  <input type="text" name="<?php echo $field; ?>" placeholder="<?php echo !empty(${'field_' . $field}['placeholder']) ? ${'field_' . $field}['placeholder'] : ''; ?>" value="<?php echo ${$field} ? ${$field} : ${'field_' . $field}['default']; ?>" class="form-control <?= ($field=='telephone' || $field=='postcode')?'input-number':''; ?>"  id="input-payment-<?php echo str_replace('_', '-', $field); ?>" />
	</div>
		
	<?php } else { ?>
	<input type="text" name="<?php echo $field; ?>" value="<?php echo ${$field} ? ${$field} : ${'field_' . $field}['default']; ?>" class="hide" />
	<?php } ?>
  <?php } ?>

	
<?php } ?>
<!-- CUSTOM FIELDS -->
<div id="custom-field-payment">
  <?php foreach ($custom_fields as $custom_field) { ?>
  <?php if ($custom_field['location'] == 'account' || $custom_field['location'] == 'address') { ?>
	<div class="col-sm-6 custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>" id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>">
	  <label class="control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
	  <?php if ($custom_field['type'] == 'select') { ?>
		<select name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control">
		  <option value=""><?php echo $text_select; ?></option>
		  <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
		  <?php if (isset($guest_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $guest_custom_field[$custom_field['custom_field_id']]) { ?>
		  <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>" selected="selected"><?php echo $custom_field_value['name']; ?></option>
		  <?php } else { ?>
		  <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
		  <?php } ?>
		  <?php } ?>
		</select>
	  <?php } ?>
	  <?php if ($custom_field['type'] == 'radio') { ?>
		<?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
		  <div class="radio">
			<?php if (isset($guest_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $guest_custom_field[$custom_field['custom_field_id']]) { ?>
			<label>
			  <input type="radio" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
			  <?php echo $custom_field_value['name']; ?></label>
			<?php } else { ?>
			<label>
			  <input type="radio" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
			  <?php echo $custom_field_value['name']; ?></label>
			<?php } ?>
		  </div>
		<?php } ?>
	  <?php } ?>
	  <?php if ($custom_field['type'] == 'checkbox') { ?>
		<?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
		  <div class="checkbox">
			<?php if (isset($guest_custom_field[$custom_field['custom_field_id']]) && in_array($custom_field_value['custom_field_value_id'], $guest_custom_field[$custom_field['custom_field_id']])) { ?>
			<label>
			  <input type="checkbox" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
			  <?php echo $custom_field_value['name']; ?></label>
			<?php } else { ?>
			<label>
			  <input type="checkbox" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
			  <?php echo $custom_field_value['name']; ?></label>
			<?php } ?>
		  </div>
		<?php } ?>
	  <?php } ?>
	  <?php if ($custom_field['type'] == 'text') { ?>
		<input type="text" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
	  <?php } ?>
	  <?php if ($custom_field['type'] == 'textarea') { ?>
		<textarea name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" rows="5" placeholder="<?php echo $custom_field['name']; ?>" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control"><?php echo (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?></textarea>
	  <?php } ?>
	  <?php if ($custom_field['type'] == 'file') { ?>
		<br />
		<button type="button" id="button-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
		<input type="hidden" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : ''); ?>" />
	  <?php } ?>
	  <?php if ($custom_field['type'] == 'date') { ?>
		<input type="text" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control date" />
	  <?php } ?>
	  <?php if ($custom_field['type'] == 'time') { ?>
		<input type="text" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control time" />
	  <?php } ?>
	  <?php if ($custom_field['type'] == 'datetime') { ?>
		<input type="text" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control datetime" />
	  <?php } ?>
  </div>
  <?php } ?>
  <?php } ?>
</div>
<div style="clear:both;"></div>
<div style="clear:both;margin-top:20px;padding-top:15px;border-top:1px solid #DDDDDD; width: 100%;">
  <?php if (!empty($field_register['display'])) { ?>
	<?php if (!$guest_checkout || !empty($field_register['required'])) { ?>
	  <input type="checkbox" name="create_account" value="1" id="create" class="hide" checked="checked" />
	<?php } else { ?>
	  <input type="checkbox" name="create_account" value="1" id="create"<?php echo $create_account ? ' checked="checked"' : ''; ?> />
	  <label for="create"><?php echo $text_create_account; ?></label><br />
	<?php } ?>
	<div id="create_account"><?php echo $register; ?></div>
  <?php } else { ?>
    <input type="checkbox" name="create_account" value="1" id="create" class="hide" />
  <?php } ?>
  <?php if ($shipping_required) { ?>
    <input type="checkbox" name="shipping_address" value="1" id="shipping"<?php echo $shipping_address ? ' checked="checked"' : ''; ?> />
    <label for="shipping"><?php echo $entry_shipping; ?></label>
  <?php } else { ?>
    <input type="checkbox" name="shipping_address" value="1" id="shipping" checked="checked" class="hide" />
  <?php } ?>
</div>
<script type="text/javascript"><!--
	<?php if( in_array('address_2', $fields) ){ ?>
			postalcode('#input-payment-postcode', '#input-payment-address-1', '#input-payment-address-2');
		<?php }else{ ?>
			postalcode('#input-payment-postcode', '#input-payment-address-1');
		<?php } ?>
//--></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	// Sort the custom fields
	$('#custom-field-payment .custom-field[data-sort]').detach().each(function() {
		if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#payment-address .col-sm-6').length) {
			$('#payment-address .col-sm-6').eq($(this).attr('data-sort')).before(this);
		} 
		
		if ($(this).attr('data-sort') > $('#payment-address .col-sm-6').length) {
			$('#payment-address .col-sm-6:last').after(this);
		}
			
		if ($(this).attr('data-sort') < -$('#payment-address .col-sm-6').length) {
			$('#payment-address .col-sm-6:first').before(this);
		}
	});

	$('#payment-address select[name=\'customer_group_id\']').on('change', function() {
		$.ajax({
			url: 'index.php?route=checkout/checkout/customfield&customer_group_id=' + this.value,
			dataType: 'json',
			success: function(json) {
				$('#payment-address .custom-field').hide();
				$('#payment-address .custom-field').removeClass('required');

				for (i = 0; i < json.length; i++) {
					custom_field = json[i];

					$('#payment-custom-field' + custom_field['custom_field_id']).show();

					if (custom_field['required']) {
						$('#payment-custom-field' + custom_field['custom_field_id']).addClass('required');
					} else {
						$('#payment-custom-field' + custom_field['custom_field_id']).removeClass('required');
					}
				}
				
				<?php if ($shipping_required) { ?>
				$('#shipping-address .custom-field').hide();
				$('#shipping-address .custom-field').removeClass('required');

				for (i = 0; i < json.length; i++) {
					custom_field = json[i];

					$('#shipping-custom-field' + custom_field['custom_field_id']).show();

					if (custom_field['required']) {
						$('#shipping-custom-field' + custom_field['custom_field_id']).addClass('required');
					} else {
						$('#shipping-custom-field' + custom_field['custom_field_id']).removeClass('required');
					}
				}
				<?php } ?>
			},
			<?php if ($debug) { ?>
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
			<?php } ?>
		});
	});

	$('#payment-address select[name=\'customer_group_id\']').trigger('change');

	$('#payment-address button[id^=\'button-payment-custom-field\']').on('click', function() {
		var node = this;

		$('#form-upload').remove();

		$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

		$('#form-upload input[name=\'file\']').trigger('click');

		timer = setInterval(function() {
			if ($('#form-upload input[name=\'file\']').val() != '') {
				clearInterval(timer);
			
				$.ajax({
					url: 'index.php?route=tool/upload',
					type: 'post',
					dataType: 'json',
					data: new FormData($('#form-upload')[0]),
					cache: false,
					contentType: false,
					processData: false,
					beforeSend: function() {
						$(node).button('loading');
					},
					complete: function() {
						$(node).button('reset');
					},
					success: function(json) {
						$('.text-danger').remove();
						
						if (json['error']) {
							$(node).parent().find('input[name^=\'custom_field\']').after('<div class="text-danger">' + json['error'] + '</div>');
						}
		
						if (json['success']) {
							alert(json['success']);
		
							$(node).parent().find('input[name^=\'custom_field\']').attr('value', json['file']);
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		}, 500);
	});

	$('#payment-address select[name=\'country_id\']').on('change', function() {
		$.ajax({
			url: 'index.php?route=quickenquiry/checkout/country&country_id=' + this.value,
			dataType: 'json',
			cache: false,
			beforeSend: function() {
				$('#payment-address select[name=\'country_id\']').after('<i class="fa fa-spinner fa-spin"></i>');
			},
			complete: function() {
				$('.fa-spinner').remove();
			},			
			success: function(json) {
				if (json['postcode_required'] == '1') {
					$('#payment-postcode-required').addClass('required');
				} else {
					$('#payment-postcode-required').removeClass('required');
				}
				
				var html = '';
				
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>'; 
				
				if (json['zone'] != '') {
					for (i = 0; i < json['zone'].length; i++) {
						html += '<option value="' + json['zone'][i]['zone_id'] + '"';
						
						if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
							html += ' selected="selected"';
						}
		
						html += '>' + json['zone'][i]['name'] + '</option>';
					}
				} 
				
				$('#payment-address select[name=\'zone_id\']').html(html).trigger('change');
			},
			<?php if ($debug) { ?>
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
			<?php } ?>
		});
	}).change();

	<?php if ($shipping_required) { ?>
		// Guest Shipping Form
		$('#payment-address input[name=\'shipping_address\']').on('change', function() {
			if ($('#payment-address input[name=\'shipping_address\']:checked').val()) {
				$('#shipping-address').slideUp('slow');

				<?php if ($shipping_required) { ?>
				reloadShippingMethod('payment');
				<?php } ?>
			} else {
				$.ajax({
					url: 'index.php?route=quickenquiry/guest_shipping&customer_group_id=' + $('#payment-address select[name=\'customer_group_id\']').val(),
					dataType: 'html',
					cache: false,
					beforeSend: function() {
						// Nothing at the moment
					},
					success: function(html) {
						$('#shipping-address .quickcheckout-content').html(html);
						
						$('#shipping-address').slideDown('slow');
					},
					<?php if ($debug) { ?>
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
					<?php } ?>
				});
			}
		});
		
		<?php if ($shipping_address) { ?>
		$('#shipping-address').hide();
		<?php } else { ?>
		$('#payment-address input[name=\'shipping_address\']').trigger('change');
		<?php } ?>
	<?php } ?>

	$('#payment-address select[name=\'zone_id\']').on('change', function() {
		reloadPaymentMethod();
		
		<?php if ($shipping_required) { ?>
		if ($('#payment-address input[name=\'shipping_address\']:checked').val()) {
			reloadShippingMethod('payment');
		}
		<?php } ?>
	});

	// Create account
	$('#payment-address input[name=\'create_account\']').on('change', function() {
		if ($('#payment-address input[name=\'create_account\']:checked').val()) {
			$('#create_account').slideDown('slow');
		} else {
			$('#create_account').slideUp('slow');
		}
	});

	<?php if ($create_account || !$guest_checkout || !empty($field_register['required'])) { ?>
	$('#create_account').show();
	<?php } else { ?>
	$('#create_account').hide();
	<?php } ?>
});
//--></script>
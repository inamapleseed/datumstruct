<?php if ($addresses) { ?>
<div class="radio">
  <label><input type="radio" name="payment_address" value="existing" id="payment-address-existing" checked="checked" />
  <?= $text_address_existing; ?></label>
</div>
<div id="payment-existing">
  <select name="address_id" class="form-control">
    <?php foreach ($addresses as $address) { ?>
    <?php if ($address['address_id'] == $address_id) { ?>
    <option value="<?= $address['address_id']; ?>" selected="selected"><?= $address['address_formated']; ?></option>
    <?php } else { ?>
    <option value="<?= $address['address_id']; ?>"><?= $address['address_formated']; ?></option>
    <?php } ?>
    <?php } ?>
  </select>
</div>
<div class="radio">
  <label><input type="radio" name="payment_address" value="new" id="payment-address-new" />
  <?= $text_address_new; ?></label>
</div>
<?php } else { ?>
  <input type="radio" name="payment_address" value="new" id="payment-address-new" class="hide" checked="checked" />
<?php } ?>
<div id="payment-new" style="display: <?= ($addresses ? 'none' : 'block'); ?>;">
<?php $i=1; ?>
<?php foreach ($fields as $field) { ?>
  <?php if ($field == 'country') { ?>
    <?php if (!empty(${'field_' . $field}['display'])) { ?>
	<div class="col-sm-6<?= !empty(${'field_' . $field}['required']) ? ' required' : ''; ?>">
	  <label class="control-label"><?= $entry_country; ?></label>
	  <select name="country_id" class="form-control" id="input-payment-country">
	  <?php foreach ($countries as $country) { ?>
		<?php if ($country['country_id'] == $country_id) { ?>
		<option value="<?= $country['country_id']; ?>" selected="selected"><?= $country['name']; ?></option>
		<?php } else { ?>
		<option value="<?= $country['country_id']; ?>"><?= $country['name']; ?></option>
		<?php } ?>
	  <?php } ?>
	  </select>
	</div> <?php $i++; ?>

	<?php } else { ?>
	<select name="country_id" class="hide">
	<?php foreach ($countries as $country) { ?>
	  <?php if ($country['country_id'] == $country_id) { ?>
	  <option value="<?= $country['country_id']; ?>" selected="selected"><?= $country['name']; ?></option>
	  <?php } else { ?>
	  <option value="<?= $country['country_id']; ?>"><?= $country['name']; ?></option>
	  <?php } ?>
	<?php } ?>
	</select>
	<?php } ?>
  <?php } elseif ($field == 'zone') { ?>
    <?php if (!empty(${'field_' . $field}['display'])) { ?>
	<div class="col-sm-6<?= !empty(${'field_' . $field}['required']) ? ' required' : ''; ?>">
	  <label class="control-label"><?= $entry_zone; ?></label>
	  <select name="zone_id" class="form-control" id="input-payment-zone"></select>
	</div> <?php $i++; ?>
	<?php } else { ?>
	  <select name="zone_id" class="hide"></select>
	<?php } ?>
  <?php } else { ?>
	<?php if (!empty(${'field_' . $field}['display'])) { ?>
	<div<?= $field == 'postcode' ? ' id="payment-postcode-required"' : ''; ?> class=" col-sm-6<?= !empty(${'field_' . $field}['required']) ? ' required' : ''; ?>">
	  <label class="control-label" for="input-payment-<?= str_replace('_', '-', $field); ?>"><?= ${'entry_' . $field}; ?></label>
	  <input type="text" name="<?= $field; ?>" placeholder="<?= !empty(${'field_' . $field}['placeholder']) ? ${'field_' . $field}['placeholder'] : ''; ?>" value="<?= ${'field_' . $field}['default']; ?>" class="form-control  <?= ($field=='telephone' || $field=='postcode')?'input-number':''; ?>"  id="input-payment-<?= str_replace('_', '-', $field); ?>" />
	</div> <?php $i++; ?>
	<?php } else { ?>
	<input type="text" name="<?= $field; ?>" value="<?= ${'field_' . $field}['default']; ?>" class="hide" />
	<?php } ?>
  <?php } ?>
<?php } ?>
<!-- CUSTOM FIELDS -->
<div id="custom-field-payment">
  <?php foreach ($custom_fields as $custom_field) { ?>
  <?php if ($custom_field['location'] == 'address') { ?>
	<div class="col-sm-6 custom-field" data-sort="<?= $custom_field['sort_order']; ?>" id="payment-custom-field<?= $custom_field['custom_field_id']; ?>">
	  <label class="control-label" for="input-payment-custom-field<?= $custom_field['custom_field_id']; ?>"><?= $custom_field['name']; ?></label>
	  <?php if ($custom_field['type'] == 'select') { ?>
		<select name="custom_field[<?= $custom_field['location']; ?>][<?= $custom_field['custom_field_id']; ?>]" id="input-payment-custom-field<?= $custom_field['custom_field_id']; ?>" class="form-control">
		  <option value=""><?= $text_select; ?></option>
		  <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
		  <?php if (isset($guest_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $guest_custom_field[$custom_field['custom_field_id']]) { ?>
		  <option value="<?= $custom_field_value['custom_field_value_id']; ?>" selected="selected"><?= $custom_field_value['name']; ?></option>
		  <?php } else { ?>
		  <option value="<?= $custom_field_value['custom_field_value_id']; ?>"><?= $custom_field_value['name']; ?></option>
		  <?php } ?>
		  <?php } ?>
		</select>
	  <?php } ?>
	  <?php if ($custom_field['type'] == 'radio') { ?>
		<?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
		  <div class="radio">
			<?php if (isset($guest_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $guest_custom_field[$custom_field['custom_field_id']]) { ?>
			<label>
			  <input type="radio" name="custom_field[<?= $custom_field['location']; ?>][<?= $custom_field['custom_field_id']; ?>]" value="<?= $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
			  <?= $custom_field_value['name']; ?></label>
			<?php } else { ?>
			<label>
			  <input type="radio" name="custom_field[<?= $custom_field['location']; ?>][<?= $custom_field['custom_field_id']; ?>]" value="<?= $custom_field_value['custom_field_value_id']; ?>" />
			  <?= $custom_field_value['name']; ?></label>
			<?php } ?>
		  </div>
		<?php } ?>
	  <?php } ?>
	  <?php if ($custom_field['type'] == 'checkbox') { ?>
		<?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
		  <div class="checkbox">
			<?php if (isset($guest_custom_field[$custom_field['custom_field_id']]) && in_array($custom_field_value['custom_field_value_id'], $guest_custom_field[$custom_field['custom_field_id']])) { ?>
			<label>
			  <input type="checkbox" name="custom_field[<?= $custom_field['location']; ?>][<?= $custom_field['custom_field_id']; ?>][]" value="<?= $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
			  <?= $custom_field_value['name']; ?></label>
			<?php } else { ?>
			<label>
			  <input type="checkbox" name="custom_field[<?= $custom_field['location']; ?>][<?= $custom_field['custom_field_id']; ?>][]" value="<?= $custom_field_value['custom_field_value_id']; ?>" />
			  <?= $custom_field_value['name']; ?></label>
			<?php } ?>
		  </div>
		<?php } ?>
	  <?php } ?>
	  <?php if ($custom_field['type'] == 'text') { ?>
		<input type="text" name="custom_field[<?= $custom_field['location']; ?>][<?= $custom_field['custom_field_id']; ?>]" value="<?= (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?= $custom_field['name']; ?>" id="input-payment-custom-field<?= $custom_field['custom_field_id']; ?>" class="form-control" />
	  <?php } ?>
	  <?php if ($custom_field['type'] == 'textarea') { ?>
		<textarea name="custom_field[<?= $custom_field['location']; ?>][<?= $custom_field['custom_field_id']; ?>]" rows="5" placeholder="<?= $custom_field['name']; ?>" id="input-payment-custom-field<?= $custom_field['custom_field_id']; ?>" class="form-control"><?= (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?></textarea>
	  <?php } ?>
	  <?php if ($custom_field['type'] == 'file') { ?>
		<br />
		<button type="button" id="button-payment-custom-field<?= $custom_field['custom_field_id']; ?>" data-loading-text="<?= $text_loading; ?>" class="btn btn-default"><i class="fa fa-upload"></i> <?= $button_upload; ?></button>
		<input type="hidden" name="custom_field[<?= $custom_field['location']; ?>][<?= $custom_field['custom_field_id']; ?>]" value="<?= (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : ''); ?>" />
	  <?php } ?>
	  <?php if ($custom_field['type'] == 'date') { ?>
		<input type="text" name="custom_field[<?= $custom_field['location']; ?>][<?= $custom_field['custom_field_id']; ?>]" value="<?= (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?= $custom_field['name']; ?>" id="input-payment-custom-field<?= $custom_field['custom_field_id']; ?>" class="form-control date" />
	  <?php } ?>
	  <?php if ($custom_field['type'] == 'time') { ?>
		<input type="text" name="custom_field[<?= $custom_field['location']; ?>][<?= $custom_field['custom_field_id']; ?>]" value="<?= (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?= $custom_field['name']; ?>" id="input-payment-custom-field<?= $custom_field['custom_field_id']; ?>" class="form-control time" />
	  <?php } ?>
	  <?php if ($custom_field['type'] == 'datetime') { ?>
		<input type="text" name="custom_field[<?= $custom_field['location']; ?>][<?= $custom_field['custom_field_id']; ?>]" value="<?= (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?= $custom_field['name']; ?>" id="input-payment-custom-field<?= $custom_field['custom_field_id']; ?>" class="form-control datetime" />
	  <?php } ?>
  </div>
  <?php } ?>
  <?php } ?>
</div>
</div>
<script type="text/javascript"><!--
	<?php if( in_array('address_2', $fields) ){ ?>
			postalcode('#input-payment-postcode', '#input-payment-address-1', '#input-payment-address-2');
		<?php }else{ ?>
			postalcode('#input-payment-postcode', '#input-payment-address-1');
		<?php } ?>
//--></script>
<script type="text/javascript"><!--
// Payment address form function
$(document).ready(function() {
	$('#payment-address input[name=\'payment_address\']').on('change', function() {
		if (this.value == 'new') {
			$('#payment-existing').slideUp();
			$('#payment-new').slideDown();

			$('#payment-address select[name=\'country_id\']').trigger('change');
		} else {
			$('#payment-existing').slideDown();
			$('#payment-new').slideUp();
			
			reloadPaymentMethodById($('#payment-address select[name=\'address_id\']').val());
		}
	});
	
	$('#payment-address input[name=\'payment_address\']:checked').trigger('change');

	// Sort the custom fields
	$('#custom-field-payment .custom-field[data-sort]').detach().each(function() {
		if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#payment-new .col-sm-6').length) {
			$('#payment-new .col-sm-6').eq($(this).attr('data-sort')).before(this);
		} 
		
		if ($(this).attr('data-sort') > $('#payment-new .col-sm-6').length) {
			$('#payment-new .col-sm-6:last').after(this);
		}
			
		if ($(this).attr('data-sort') < -$('#payment-new .col-sm-6').length) {
			$('#payment-new .col-sm-6:first').before(this);
		}
	});

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

	$('#payment-address select[name=\'zone_id\']').on('change', function() {
		reloadPaymentMethod();
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

				html = '';

				if (json['zone'] != '') {
					for (i = 0; i < json['zone'].length; i++) {
						html += '<option value="' + json['zone'][i]['zone_id'] + '"';

						if (json['zone'][i]['zone_id'] == '<?= $zone_id; ?>') {
							html += ' selected="selected"';
						}

						html += '>' + json['zone'][i]['name'] + '</option>';
					}
				} else {
					html += '<option value="0" selected="selected"><?= $text_none; ?></option>';
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

	$('#payment-address select[name=\'address_id\']').on('change', function() {
		if ($('#payment-address input[name=\'payment_address\']:checked').val() == 'new') {
			reloadPaymentMethod();
		} else {
			reloadPaymentMethodById($('#payment-address select[name=\'address_id\']').val());
		}
	});
});
//--></script>
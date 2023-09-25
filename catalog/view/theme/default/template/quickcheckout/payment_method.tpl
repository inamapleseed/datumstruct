<?php if ($error_warning) { ?>
<div class="alert alert-danger"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($payment_methods) { ?>
<p><?php echo $text_payment_method; ?></p>
<?php if ($payment) { ?>
<table class="table table-hover">
  <?php foreach ($payment_methods as $payment_method) { ?>
  <tr>
    <td><?php if ($payment_method['code'] == $code) { ?>
      <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" checked="checked" />
      <?php } else { ?>
      <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" />
      <?php } ?></td>
    <td><label for="<?php echo $payment_method['code']; ?>"><?php echo $payment_method['title']; ?></label></td>
	<?php if (!empty($payment_logo[$payment_method['code']])) { ?>
	<td width="160px;" ><img src="<?php echo $payment_logo[$payment_method['code']]; ?>" alt="<?php echo $payment_method['title']; ?>" class="img-responsive" /></td>
	<?php } else { ?>
	<td></td>
	<?php } ?>
  </tr>
  <?php } ?>
</table>
<?php } else { ?>
  <select name="payment_method" class="form-control">
  <?php foreach ($payment_methods as $payment_method) { ?>
	<?php if ($payment_method['code'] == $code) { ?>
      <option value="<?php echo $payment_method['code']; ?>" selected="selected">
      <?php } else { ?>
      <option value="<?php echo $payment_method['code']; ?>">
      <?php } ?>
    <?php echo $payment_method['title']; ?></option>
  <?php } ?>
  </select><br />
<?php } ?>
<br />
<?php } ?>
<?php if ($survey_survey) { ?>
<div<?php echo $survey_required ? ' class="required"' : ''; ?>>
  <label class="control-label"><strong><?php echo $text_survey; ?></strong></label>
  <?php if ($survey_type) { ?>
  <select name="survey" class="form-control">
    <option value=""></option>
    <?php foreach ($survey_answers as $survey_answer) { ?>
    <?php if (!empty($survey_answer[$language_id])) { ?>
	  <?php if ($survey == $survey_answer[$language_id]) { ?>
      <option value="<?php echo $survey_answer[$language_id]; ?>" selected="selected"><?php echo $survey_answer[$language_id]; ?></option>
      <?php } else { ?>
	  <option value="<?php echo $survey_answer[$language_id]; ?>"><?php echo $survey_answer[$language_id]; ?></option>
      <?php } ?>
	<?php } ?>
  <?php } ?></select><br />
  <?php } else { ?>
  <textarea name="survey" class="form-control" rows="1"><?php echo $survey; ?></textarea><br /><br />
  <?php } ?>
</div>
<?php } else { ?>
<textarea name="survey" class="hide"><?php echo $survey; ?></textarea>
<?php } ?>
<?php if (!empty($field_comment['display'])) { ?>
<strong><?php if (!empty($field_comment['required'])) { ?><span class="required">*</span> <?php } ?><?php echo $text_comments; ?></strong>
<textarea name="comment" rows="8" class="form-control no-custom" placeholder="<?php echo !empty($field_comment['placeholder']) ? $field_comment['placeholder'] : ''; ?>"><?php echo $comment ? $comment : $field_comment['default']; ?></textarea>
<?php } else { ?>
<textarea name="comment" class="hide"></textarea>
<?php } ?>

<script type="text/javascript"><!--
$('#payment-method input[name=\'payment_method\'], #payment-method select[name=\'payment_method\']').on('change', function() {
	<?php if (!$logged) { ?>
		$.ajax({
			url: 'index.php?route=quickcheckout/payment_method/set',
			type: 'post',
			data: $('#payment-address input[type=\'text\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select, #payment-method input[type=\'text\'], #payment-method input[type=\'checkbox\']:checked, #payment-method input[type=\'radio\']:checked, #payment-method input[type=\'hidden\'], #payment-method select, #payment-method textarea'),
			dataType: 'html',
			cache: false,
			success: function(html) {
				<?php if ($cart && $payment_reload) { ?>
				loadCart();
				<?php } ?>
			},
			<?php if ($debug) { ?>
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
			<?php } ?>
		});
	<?php } else { ?>
		if ($('#payment-address input[name=\'payment_address\']:checked').val() == 'new') {
			var url = 'index.php?route=quickcheckout/payment_method/set';
			var post_data = $('#payment-address input[type=\'text\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select, #payment-method input[type=\'text\'], #payment-method input[type=\'checkbox\']:checked, #payment-method input[type=\'radio\']:checked, #payment-method input[type=\'hidden\'], #payment-method select, #payment-method textarea');
		} else {
			var url = 'index.php?route=quickcheckout/payment_method/set&address_id=' + $('#payment-address select[name=\'address_id\']').val();
			var post_data = $('#payment-method input[type=\'text\'], #payment-method input[type=\'checkbox\']:checked, #payment-method input[type=\'radio\']:checked, #payment-method input[type=\'hidden\'], #payment-method select, #payment-method textarea');
		}
		
		$.ajax({
			url: url,
			type: 'post',
			data: post_data,
			dataType: 'html',
			cache: false,
			success: function(html) {
				<?php if ($cart && $payment_reload) { ?>
				loadCart();
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

<?php if ($payment_reload) { ?>
$(document).ready(function() {
	$('#payment-method input[name=\'payment_method\']:checked, #payment-method select[name=\'payment_method\']').trigger('change');
});
<?php } ?>
//--></script>
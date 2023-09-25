<br />
<div style="overflow:auto;">
  <div class="col-sm-6 required">
	<label class="control-label"><?php echo $entry_password; ?></label>
	<input type="password" name="password" value="" class="form-control" placeholder="<?php echo $entry_password; ?>" />
  </div>
  <div class="col-sm-6 required">
	<label class="control-label"><?php echo $entry_confirm; ?></label>
	<input type="password" name="confirm" value="" class="form-control" placeholder="<?php echo $entry_confirm; ?>" />
  </div>
  <div class="col-xs-12" style="clear:both;border-bottom:1px solid #dddddd;margin:10px 0px;">
	<?php if (!empty($field_newsletter['required'])) { ?>
	<input type="checkbox" name="newsletter" value="1" id="newsletter" class="hide" checked="checked" />
	<?php } elseif (!empty($field_newsletter['display'])) { ?>
	  <?php if(!empty($field_newsletter['default'])) { ?>
	  <input type="checkbox" name="newsletter" value="1" id="newsletter" checked="checked" />
	  <?php } else { ?>
	  <input type="checkbox" name="newsletter" value="1" id="newsletter" />
	  <?php } ?>
	  <label for="newsletter"><?php echo $entry_newsletter; ?></label><br />
	<?php } else { ?>
    <input type="checkbox name="newsletter" value="1" id="newsletter" class="hide" />
	<?php } ?>
	<?php if ($text_agree) { ?>
    <input type="checkbox" name="agree" value="1" id="agree-reg" />
	<label for="agree-reg"><?php echo $text_agree; ?></label>
	<?php } ?>
  </div>
</div>
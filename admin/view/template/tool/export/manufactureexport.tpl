<hr />
<div class="row">
<div class="col-sm-6">
  <div class="form-group">
	<label class="control-label" for="input-name"><?php echo $entry_store; ?></label>
	<select class="form-control" name="filter_store">
	 <option value="0"><?php echo $text_default; ?></option>
	 <?php foreach($stores as $store){ ?>	
		<option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
	  <?php } ?>
	</select>
  </div>
  <div class="form-group">
	<label style="width:100%" class="control-label" for="input-limit"><?php echo $entry_limit; ?> (Note:Export Data limit)</label>
	 <input style="display:inline-block; width:47%;"; type="text" name="filter_start" value="0" placeholder="Start" id="input-start" class="form-control"/> -
	 <input style="display:inline-block; width:47%;"; type="text" name="filter_limit" value="<?php echo $filter_limit; ?>" placeholder="<?php echo $entry_limit; ?>" id="input-limit" class="form-control" />
  </div>
</div>
<div class="col-sm-6">
 <div class="form-group">
	<label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
	<select name="filter_status" id="input-status" class="form-control">
	  <option value="*"></option>
	  <?php if ($filter_status) { ?>
	  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
	  <?php } else { ?>
	  <option value="1"><?php echo $text_enabled; ?></option>
	  <?php } ?>
	  <?php if (!$filter_status && !is_null($filter_status)) { ?>
	  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
	  <?php } else { ?>
	  <option value="0"><?php echo $text_disabled; ?></option>
	  <?php } ?>
	</select>
  </div>
  <div class="form-group">
	<label class="control-label" for="input-status"></label>
	<button type="button" id="button_filter_manufacture" class="ourbtn btn btn-primary form-control"><i class="fa fa-download"></i> <?php echo $button_export; ?></button>
  </div>
</div>
</div>
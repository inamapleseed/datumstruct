<hr/>
<div class="row">
	<div class="col-sm-4">
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
		<label class="control-label" for="input-name"><?php echo $entry_categories; ?></label>
		<select name="filter_categories" id="input-categories" class="form-control">
			<option value="*"><?php echo $select_categories; ?></option>
			<?php foreach($categories as $category){ ?>
			<option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
			<?php } ?>
		</select>
	  </div>
	  <div class="form-group">
		<label style="width:100%;" class="control-label" for="input-quantity"><?php echo $entry_quantityrange; ?></label>
		   <input style="display:inline-block; width:47%;"; type="text" name="filter_quantity_to" value="<?php echo $filter_price; ?>" placeholder="To" id="input-price" class="form-control" /> - <input style="display:inline-block; width:47%;"; type="text" name="filter_quantity_form" value="<?php echo $filter_quantity; ?>" placeholder="From" id="input-quantity" class="form-control"/>
	  </div>
	  <div class="form-group">
		<label class="control-label" for="input-quantity"><?php echo $entry_stock_status; ?></label>
		<select name="filter_stock_status" id="input-status" class="form-control">
			<option value="*"><?php echo $select_stock_status; ?></option>
			<?php foreach($stock_statuses as $stock){ ?>
			<option value="<?php echo $stock['stock_status_id']; ?>"><?php echo $stock['name']; ?></option>
			<?php } ?>
		</select>
	  </div>
	  <div class="form-group">
		<label class="control-label" for="input-eformat"><?php echo $export_format; ?></label>
		<select name="filter_eformat" id="input-eformat" class="form-control">
			<option value="xls">XLS</option>
			<option value="csv">CSV</option>
			<option value="xlsx">XLSX</option>
			<option value="xml">XML</option>
		</select>
	  </div>
	</div>
	<div class="col-sm-4">
	  <div class="form-group">
		<label class="control-label" for="input-language"><?php echo $entry_language; ?></label>
		<select class="form-control" name="filter_language_id">
		 <?php foreach($languages as $language){ ?>
			<option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
		  <?php } ?>
		</select>
	  </div>
	  <div class="form-group">
		<label class="control-label" for="input-name"><?php echo $entry_manufacturer; ?></label>
		<select name="filter_manufacturer" id="input-categories" class="form-control">
			<option value="*"><?php echo $select_manufacture; ?></option>
			<?php foreach($manufacturers as $manufacturer){ ?>
			<option value="<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></option>
			<?php } ?>
		</select>
	  </div>
	  <div class="form-group">
		<label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
		<select name="filter_status" id="input-status" class="form-control">
		  <option value="*"><?php echo $select_status; ?></option>
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
		<label style="width:100%;" class="control-label" for="input-limit"><?php echo $entry_limit; ?> (Note:Export Data limit)</label>
		<input style="display:inline-block; width:47%;"; type="text" name="filter_start" value="0" placeholder="Start" id="input-start" class="form-control" />
		-
		<input style="display:inline-block; width:47%;"; type="text" name="filter_limit" value="<?php echo $filter_limit; ?>" placeholder="<?php echo $entry_limit; ?>" id="input-limit" class="form-control" />
	  </div>
	   <div class="form-group">
		<label class="control-label" for="input-status"></label>
		<button type="button" id="button-filter_product" class="ourbtn btn btn-primary form-control"><i class="fa fa-download"></i> <?php echo $button_export; ?></button>
	  </div>
	</div>
	<div class="col-sm-4">
	  <div class="form-group">
		 <label class="control-label" for="input-pimage"><?php echo $product_image; ?></label>
		 <select name="filter_pimage" id="input-pimage" class="form-control selectpicker">
		  <option value="no">No</option>
		  <option value="yes">Yes</option>
		 </select>
	  </div>
	  <div class="form-group">
		<label style="width:100%;" class="control-label" for="input-limit">Product ID - Condition </label>
		<input style="display:inline-block; width:47%;"; type="text" name="filter_idstart" value="<?php echo $miniproduct_id; ?>" placeholder="Start" id="input-start" class="form-control" />
		-
		<input style="display:inline-block; width:47%;"; type="text" name="filter_idend" value="<?php echo $maxproduct_id; ?>" placeholder="<?php echo $entry_limit; ?>" id="input-idend" class="form-control" />
	  </div> 
	  <div class="form-group">
		<label style="width:100%;" class="control-label" for="input-price"><?php echo $entry_pricerange; ?></label>
		   <input style="display:inline-block; width:47%;"; type="text" name="filter_price_to" value="<?php echo $filter_price; ?>" placeholder="To" id="input-price" class="form-control" /> - <input style="display:inline-block; width:47%;"; type="text" name="filter_price_form" value="<?php echo $filter_price; ?>" placeholder="From" id="input-price" class="form-control"/>
	  </div>
	  <div class="form-group">
		<label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
		<input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
	  </div>
	  <div class="form-group">
		<label class="control-label" for="input-model"><?php echo $entry_model; ?></label>
		<input type="text" name="filter_model" value="<?php echo $filter_model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />
	  </div>
	</div>
</div>
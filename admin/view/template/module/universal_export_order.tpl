<fieldset class="filters"><legend><div class="pull-right" style="font-size:13px; color:#666"><?php echo $_language->get('total_export_items'); ?> <span class="export_number badge clearblue"></span></div><?php echo $_language->get('export_filters'); ?></legend>
  
  <div class="row">
    <div class="col-sm-6">
      <div class="form-group">
        <label class="control-label"><?php echo $_language->get('filter_store'); ?></label>
        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-fw fa-home"></i></span>
        <select class="form-control" name="filter_store">
          <option value=""><?php echo $_language->get('export_all'); ?></option>
          <?php foreach ($stores as $store) { ?>
            <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
          <?php } ?>
        </select>
        </div>
      </div>
    </div>
    
    <div class="col-sm-6">
      <div class="form-group">
        <label class="control-label"><span data-toggle="tooltip" title="<?php echo $_language->get('filter_limit_i'); ?>"><?php echo $_language->get('filter_limit'); ?></span></label>
        <div class="input-group">
        <input type="text" class="form-control" name="filter-start" placeholder="<?php echo $_language->get('filter_limit_start'); ?>" />
        <span class="input-group-addon">-</span>
        <input type="text" class="form-control" name="filter-limit" placeholder="<?php echo $_language->get('filter_limit_limit'); ?>"/>
        </div>
      </div>
    </div>
  </div>
  
  <hr />
  
  <div class="row">
    <div class="col-sm-4">
      <div class="form-group">
        <label class="control-label" for="input-order-id"><?php echo $_language->get('filter_order_id'); ?></label>
        <div class="input-group">
          <span class="input-group-addon">#</span>
          <input type="text" class="form-control" name="filter_order_id_min" placeholder="<?php echo $_language->get('filter_min'); ?>" />
          <span class="input-group-addon">-</span>
          <input type="text" class="form-control" name="filter_order_id_max" placeholder="<?php echo $_language->get('filter_max'); ?>"/>
        </div>
      </div>
    </div>
    <?php $filter_order_status = ''; ?>
    <div class="col-sm-4">
      <div class="form-group">
        <label class="control-label" for="input-order-status"><span data-toggle="tooltip" title="<?php echo $_language->get('filter_filter_order_status_i'); ?>"><?php echo $_language->get('filter_order_status'); ?></span></label>
        <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-fw fa-check"></i></span>
          <select name="filter_order_status[]" id="input-order-status" class="selectize_order_status" placeholder="<?php echo $_language->get('export_all'); ?>">
            <option value=""></option>
            <?php if ($filter_order_status == '0') { ?>
            <option value="0" selected="selected"><?php echo $_language->get('text_missing_orders'); ?></option>
            <?php } else { ?>
            <option value="0"><?php echo $_language->get('text_missing_orders'); ?></option>
            <?php } ?>
            <?php foreach ($order_statuses as $order_status) { ?>
            <?php if ($order_status['order_status_id'] == $filter_order_status) { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
      </div>
    </div>
    <div class="col-sm-2">
      <div class="form-group">
        <label class="control-label" for="input-date-added"><?php echo $_language->get('filter_date_added'); ?></label>
        <div class="input-group date">
          <input type="text" name="filter_date_added_min" value="" placeholder="<?php echo $_language->get('filter_start'); ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
          <span class="input-group-btn">
          <button type="button" class="btn btn-default"><i class="fa fa-fw fa-calendar"></i></button>
          </span></div>
      </div>
    </div>
    <div class="col-sm-2">
      <div class="form-group">
        <label class="control-label" for="input-date-added">&nbsp;</label>
        <div class="input-group date">
          <input type="text" name="filter_date_added_max" value="" placeholder="<?php echo $_language->get('filter_end'); ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
          <span class="input-group-btn">
          <button type="button" class="btn btn-default"><i class="fa fa-fw fa-calendar"></i></button>
          </span></div>
      </div>
    </div>
   </div>
   
   <hr />
   
   <div class="row">
    <div class="col-sm-4">
      <div class="form-group">
        <label class="control-label" for="input-customer"><?php echo $_language->get('filter_customer'); ?></label>
         <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-fw fa-user"></i></span>
          <input type="text" name="filter_customer" value="" placeholder="<?php echo $_language->get('filter_customer'); ?>" id="input-customer" class="form-control" />
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="form-group">
        <label class="control-label" for="input-total"><?php echo $_language->get('filter_total'); ?></label>
        <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-fw fa-dollar"></i></span>
          <input type="text" class="form-control" name="filter_total_min" placeholder="<?php echo $_language->get('filter_min'); ?>" />
          <span class="input-group-addon">-</span>
          <input type="text" class="form-control" name="filter_total_max" placeholder="<?php echo $_language->get('filter_max'); ?>"/>
        </div>
      </div>
    </div>
    <div class="col-sm-2">
      <div class="form-group">
        <label class="control-label" for="input-date-modified"><?php echo $_language->get('filter_date_modified'); ?></label>
        <div class="input-group date">
          <input type="text" name="filter_date_modified_min" value="" placeholder="<?php echo $_language->get('filter_start'); ?>" data-date-format="YYYY-MM-DD" id="input-date-modified" class="form-control" />
          <span class="input-group-btn">
          <button type="button" class="btn btn-default"><i class="fa fa-fw fa-calendar"></i></button>
          </span></div>
      </div>
    </div>
    <div class="col-sm-2">
      <div class="form-group">
        <label class="control-label" for="input-date-modified">&nbsp;</label>
        <div class="input-group date">
          <input type="text" name="filter_date_modified_max" value="" placeholder="<?php echo $_language->get('filter_end'); ?>" data-date-format="YYYY-MM-DD" id="input-date-modified" class="form-control" />
          <span class="input-group-btn">
          <button type="button" class="btn btn-default"><i class="fa fa-fw fa-calendar"></i></button>
          </span></div>
      </div>
    </div>
  </div>

</fieldset>

<div class="spacer"></div>

<script type="text/javascript"><!--
$('input[name=\'filter_customer\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=customer/customer/autocomplete&<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['customer_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_customer\']').val(item['label']);
	}
});
//--></script> 
<script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<script type="text/javascript"><!--
$('.date').datetimepicker({pickTime: false});
//--></script></div>
<script type="text/javascript">
var $selectize_order_status = $('.selectize_order_status').selectize({
  maxItems: null
});

getTotalExportCount();
</script>
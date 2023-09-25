<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-bganycombi').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="panel panel-primary">
          <div class="panel-heading"><?php echo $text_display_col;?></div>
          <div class="panel-body">
            <div class="checkboxcolumn">
              <ul class="list-group">
                <?php foreach($checkcolumns as $chkcol) { ?>
                <li class="list-group-item col-sm-2">
                  <input type="checkbox" data-str="<?php echo $chkcol;?>" class="chkcolumn" <?php if($check_columns[$chkcol] == 1) { ?>checked<?php } ?>/>
                  <?php echo $check_head_columns[$chkcol]; ?></li>
                <?php } ?>
              </ul>
            </div>
          </div>
        </div>
        <div class="panel panel-default">
          <div class="panel-heading"><?php echo $text_filter;?></div>
          <div class="panel-body">
            <div class="row">
              <div class="form-group col-sm-2">
                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              </div>
              <div class="form-group col-sm-2 filbuyqty">
                <label class="control-label" for="input-buyqty"><?php echo $entry_buyqty; ?></label>
                <input type="text" name="filter_buyqty" value="<?php echo $filter_buyqty; ?>" placeholder="<?php echo $entry_buyqty; ?>" id="input-buyqty" class="form-control" />
              </div>
              <div class="form-group col-sm-2 filbuyproduct">
                <label class="control-label" for="input-product_name"><?php echo $entry_buyproduct; ?></label>
                <input type="text" name="filter_buyproduct_name" value="<?php echo $filter_buyproduct_name; ?>" placeholder="<?php echo $entry_buyproduct; ?>" id="input-product_name" class="form-control" />
                <input type="hidden" name="filter_buyproduct_id" value="<?php echo $filter_buyproduct_id; ?>" id="input-product_id" class="form-control" />
              </div>
              <div class="form-group col-sm-2 filbuycategory">
                <label class="control-label" for="input-category_name"><?php echo $entry_buycategory; ?></label>
                <input type="text" name="filter_buycategory_name" value="<?php echo $filter_buycategory_name; ?>" placeholder="<?php echo $entry_buycategory; ?>" id="input-category_name" class="form-control" />
                <input type="hidden" name="filter_buycategory_id" value="<?php echo $filter_buycategory_id; ?>" id="input-category_id" class="form-control" />
              </div>
              <div class="form-group col-sm-2 filbuymanufacturer">
                <label class="control-label" for="input-manufacturer_name"><?php echo $entry_buymanufacturer; ?></label>
                <input type="text" name="filter_buymanufacturer_name" value="<?php echo $filter_buymanufacturer_name; ?>" placeholder="<?php echo $entry_buymanufacturer; ?>" id="input-manufacturer_name" class="form-control" />
                <input type="hidden" name="filter_buymanufacturer_id" value="<?php echo $filter_buymanufacturer_id; ?>" id="input-manufacturer_id" class="form-control" />
              </div>
              <div class="form-group col-sm-2 filgetqty">
                <label class="control-label" for="input-getqty"><?php echo $entry_getqty; ?></label>
                <input type="text" name="filter_getqty" value="<?php echo $filter_getqty; ?>" placeholder="<?php echo $entry_getqty; ?>" id="input-getqty" class="form-control" />
              </div>
              <div class="form-group col-sm-2 filgetproduct">
                <label class="control-label" for="input-product_name"><?php echo $entry_getproduct; ?></label>
                <input type="text" name="filter_getproduct_name" value="<?php echo $filter_getproduct_name; ?>" placeholder="<?php echo $entry_getproduct; ?>" id="input-product_name" class="form-control" />
                <input type="hidden" name="filter_getproduct_id" value="<?php echo $filter_getproduct_id; ?>" id="input-product_id" class="form-control" />
              </div>
              <div class="form-group col-sm-2 filgetcategory">
                <label class="control-label" for="input-category_name"><?php echo $entry_getcategory; ?></label>
                <input type="text" name="filter_getcategory_name" value="<?php echo $filter_getcategory_name; ?>" placeholder="<?php echo $entry_getcategory; ?>" id="input-category_name" class="form-control" />
                <input type="hidden" name="filter_getcategory_id" value="<?php echo $filter_getcategory_id; ?>" id="input-category_id" class="form-control" />
              </div>
              <div class="form-group col-sm-2 filgetmanufacturer">
                <label class="control-label" for="input-manufacturer_name"><?php echo $entry_getmanufacturer; ?></label>
                <input type="text" name="filter_getmanufacturer_name" value="<?php echo $filter_getmanufacturer_name; ?>" placeholder="<?php echo $entry_getmanufacturer; ?>" id="input-manufacturer_name" class="form-control" />
                <input type="hidden" name="filter_getmanufacturer_id" value="<?php echo $filter_getmanufacturer_id; ?>" id="input-manufacturer_id" class="form-control" />
              </div>
              <div class="form-group col-sm-2 fildiscount_type">
                <label class="control-label" for="input-discount_type"><?php echo $entry_discount_type; ?></label>
                <select name="filter_discount_type" id="input-filter_discount_type" class="form-control">
                  <option value="">---</option>
                  <option value="1" <?php if ($filter_discount_type == 1) { ?> selected="selected" <?php } ?> ><?php echo $text_free; ?></option>
                  <option value="2" <?php if ($filter_discount_type == 2) { ?> selected="selected" <?php } ?> ><?php echo $text_fixed_amount; ?></option>
                  <option value="3" <?php if ($filter_discount_type == 3) { ?> selected="selected" <?php } ?> ><?php echo $text_percentage; ?></option>
                </select>
              </div>
              <div class="form-group col-sm-2 filcustomer_group">
                <label class="control-label" for="input-name"><?php echo $entry_customer_group; ?></label>
                <select name="filter_customer_group_id" id="filter_customer_group_id" class="form-control">
                  <option value=""></option>
                  <?php foreach ($customer_groups as $cgrp) { ?>
                  <option value="<?php echo $cgrp['customer_group_id']; ?>" <?php if($filter_customer_group_id == $cgrp['customer_group_id']) { ?>selected="selected"<?php } ?>><?php echo $cgrp['name']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group col-sm-2 fildate_start">
                <label class="control-label" for="input-date_start"><?php echo $entry_date_start; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date_start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group col-sm-2 fildate_end">
                <label class="control-label" for="input-date_end"><?php echo $entry_date_end; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date_end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group col-sm-2">
                <label class="control-label col-sm-12" for="input-btnfilter">&nbsp;</label>
                <button type="button" id="button-filter" class="btn btn-primary"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
              </div>
            </div>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-bganycombi">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php if ($sort == 'name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>
                  <td class="text-left coldiscount_type"><?php echo $column_discount_type; ?></td>
                  <td class="text-left"><?php echo $column_discount_value; ?></td>
                  <td class="text-left colbuyqty"><?php echo $column_buyqty; ?></td>
                  <td class="text-left colgetqty"><?php echo $column_getqty; ?></td>
                  <td class="text-left colbuyproduct"><?php echo $entry_buyproduct; ?></td>
                  <td class="text-left colbuycategory"><?php echo $entry_buycategory; ?></td>
                  <td class="text-left colbuymanufacturer"><?php echo $entry_buymanufacturer; ?></td>
                  <td class="text-left colgetproduct"><?php echo $entry_getproduct; ?></td>
                  <td class="text-left colgetcategory"><?php echo $entry_getcategory; ?></td>
                  <td class="text-left colgetmanufacturer"><?php echo $entry_getmanufacturer; ?></td>
                  <td class="text-left colcustomer_group"><?php echo $entry_customer_group; ?></td>
                  <td class="text-left coldate_start"><?php echo $column_date_start; ?></td>
                  <td class="text-left coldate_end"><?php echo $column_date_end; ?></td>
                  <td class="text-left"><?php echo $column_status; ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($bganycombis) { ?>
                <?php foreach ($bganycombis as $bganycombi) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($bganycombi['bganycombi_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $bganycombi['bganycombi_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $bganycombi['bganycombi_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php echo $bganycombi['name']; ?></td>
                  <td class="text-left coldiscount_type"><?php echo $bganycombi['discount_type']; ?></td>
                  <td class="text-left"><?php echo $bganycombi['discount_value']; ?></td>
                  <td class="text-left colbuyqty"><?php echo $bganycombi['buyqty']; ?></td>
                  <td class="text-left colgetqty"><?php echo $bganycombi['getqty']; ?></td>
                  <td class="text-left colbuyproduct"><?php echo $bganycombi['buyproduct_data']; ?></td>
                  <td class="text-left colbuycategory"><?php echo $bganycombi['buycategory_data']; ?></td>
                  <td class="text-left colbuymanufacturer"><?php echo $bganycombi['buymanufacturer_data']; ?></td>
                  <td class="text-left colgetproduct"><?php echo $bganycombi['getproduct_data']; ?></td>
                  <td class="text-left colgetcategory"><?php echo $bganycombi['getcategory_data']; ?></td>
                  <td class="text-left colgetmanufacturer"><?php echo $bganycombi['getmanufacturer_data']; ?></td>
                  <td class="text-left colcustomer_group"><?php echo $bganycombi['customer_group_data']; ?></td>
                  <td class="text-left coldate_start"><?php echo $bganycombi['date_start']; ?></td>
                  <td class="text-left coldate_end"><?php echo $bganycombi['date_end']; ?></td>
                  <td class="text-center"><label class="oct_switch">
                    <input type="checkbox" <?php if(isset($bganycombi['status_int']) && $bganycombi['status_int']) { ?>checked<?php } ?> class="chkstatus" data-str="<?php echo $bganycombi['bganycombi_id']; ?>">
                    <span class="slider round"></span> </label>
                  </td>
                  <td class="text-right"><a href="<?php echo $bganycombi['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="30"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
<style>
.form-group { padding: 5px;}
</style>
<script type="text/javascript"><!--
$('.date').datetimepicker({pickTime: false});

function savecheckcolumn(name,val) {
	$.ajax({
		url: 'index.php?route=extension/bganycombi/savecheckcolumn&token=<?php echo $token;?>',
		type: 'post',
		data : { name : name, val : val },
		dataType: 'json',
		cache: false,
		success: function(json) {
			console.log('saved ' + name);	
			location.reload(); 
		} 
	});
}

$('.chkcolumn').each(function() {
	var name = $(this).attr('data-str');
	
	$(this).click(function() {
		if($(this).is(':checked')) {
			savecheckcolumn(name , 1);
		} else {
			savecheckcolumn(name , 0);
		}
	});	
	
	if($(this).is(':checked')) {
		$('.fil'+name).show();
		$('.col'+name).show();
	} else {
		$('.fil'+name).hide();
		$('.col'+name).hide();
	}
});

$('#button-filter').on('click', function() {
	var url = 'index.php?route=extension/bganycombi&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();
 	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_buyqty = $('input[name=\'filter_buyqty\']').val();
 	if (filter_buyqty) {
		url += '&filter_buyqty=' + encodeURIComponent(filter_buyqty);
	}
	
	var filter_getqty = $('input[name=\'filter_getqty\']').val();
 	if (filter_getqty) {
		url += '&filter_getqty=' + encodeURIComponent(filter_getqty);
	}
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
 	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	
	var filter_date_end = $('input[name=\'filter_date_end\']').val();
 	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
	var filter_customer_group_id = $('select[name=\'filter_customer_group_id\']').val();
 	if (filter_customer_group_id != '') {
		url += '&filter_customer_group_id=' + encodeURIComponent(filter_customer_group_id);
	}
	
	var filter_discount_type  = $('select[name=\'filter_discount_type\']').val();
 	if (filter_discount_type) {
		url += '&filter_discount_type=' + encodeURIComponent(filter_discount_type);
	}
	
	var filter_buyproduct_name = $('input[name=\'filter_buyproduct_name\']').val();
	var filter_buyproduct_id = $('input[name=\'filter_buyproduct_id\']').val();
	if (filter_buyproduct_name && filter_buyproduct_id) {
		url += '&filter_buyproduct_name=' + encodeURIComponent(filter_buyproduct_name);
		url += '&filter_buyproduct_id=' + encodeURIComponent(filter_buyproduct_id);
	} 

	var filter_buycategory_name = $('input[name=\'filter_buycategory_name\']').val();
	var filter_buycategory_id = $('input[name=\'filter_buycategory_id\']').val();
	if (filter_buycategory_name && filter_buycategory_id) {
		url += '&filter_buycategory_name=' + encodeURIComponent(filter_buycategory_name);
		url += '&filter_buycategory_id=' + encodeURIComponent(filter_buycategory_id);
	} 
	
	var filter_buymanufacturer_name = $('input[name=\'filter_buymanufacturer_name\']').val();
	var filter_buymanufacturer_id = $('input[name=\'filter_buymanufacturer_id\']').val();
	if (filter_buymanufacturer_name && filter_buymanufacturer_id) {
		url += '&filter_buymanufacturer_name=' + encodeURIComponent(filter_buymanufacturer_name);
		url += '&filter_buymanufacturer_id=' + encodeURIComponent(filter_buymanufacturer_id);
	} 
	
	var filter_getproduct_name = $('input[name=\'filter_getproduct_name\']').val();
	var filter_getproduct_id = $('input[name=\'filter_getproduct_id\']').val();
	if (filter_getproduct_name && filter_getproduct_id) {
		url += '&filter_getproduct_name=' + encodeURIComponent(filter_getproduct_name);
		url += '&filter_getproduct_id=' + encodeURIComponent(filter_getproduct_id);
	} 

	var filter_getcategory_name = $('input[name=\'filter_getcategory_name\']').val();
	var filter_getcategory_id = $('input[name=\'filter_getcategory_id\']').val();
	if (filter_getcategory_name && filter_getcategory_id) {
		url += '&filter_getcategory_name=' + encodeURIComponent(filter_getcategory_name);
		url += '&filter_getcategory_id=' + encodeURIComponent(filter_getcategory_id);
	} 
	
	var filter_getmanufacturer_name = $('input[name=\'filter_getmanufacturer_name\']').val();
	var filter_getmanufacturer_id = $('input[name=\'filter_getmanufacturer_id\']').val();
	if (filter_getmanufacturer_name && filter_getmanufacturer_id) {
		url += '&filter_getmanufacturer_name=' + encodeURIComponent(filter_getmanufacturer_name);
		url += '&filter_getmanufacturer_id=' + encodeURIComponent(filter_getmanufacturer_id);
	}

	location = url;
});
function loadajaxautocomplete(target, route) { 
	$('input[name=\'filter_'+target+'_name\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/'+route+'/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item[''+route+'_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('input[name=\'filter_'+target+'_name\']').val(item['label']);
			$('input[name=\'filter_'+target+'_id\']').val(item['value']);
		}
	});
}

loadajaxautocomplete('buyproduct', 'product');
loadajaxautocomplete('getproduct', 'product');
loadajaxautocomplete('buycategory', 'category');
loadajaxautocomplete('getcategory', 'category');
loadajaxautocomplete('buymanufacturer', 'manufacturer');
loadajaxautocomplete('getmanufacturer', 'manufacturer'); 
 
//--></script>
<style>
.oct_switch{position:relative;display:inline-block;width:60px;height:34px}
.oct_switch input{display:none}
.slider{position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background-color:#ccc;-webkit-transition:.4s;transition:.4s}
.slider:before{position:absolute;content:"";height:26px;width:26px;left:4px;bottom:4px;background-color:#fff;-webkit-transition:.4s;transition:.4s}
input:checked + .slider{background-color:#2196F3}
input:focus + .slider{box-shadow:0 0 1px #2196F3}
input:checked + .slider:before{-webkit-transform:translateX(26px);-ms-transform:translateX(26px);transform:translateX(26px)}
.slider.round{border-radius:34px}
.slider.round:before{border-radius:50%}
</style>
<script language="javascript"> 
$('.chkstatus').each(function() {
	$(this).click(function() {
		if($(this).is(':checked')) {
			savestatus(parseInt($(this).attr('data-str')) , 1);
		} else {
			savestatus(parseInt($(this).attr('data-str')) , 0);
		}
	});	
});

function savestatus(id,status) {
	$.ajax({
		url: 'index.php?route=extension/bganycombi/savestatus&token=<?php echo $token;?>',
		type: 'post',
		data : { id : id, status : status },
		dataType: 'json',
		cache: false,
		success: function(json) {
			//console.log('saved ' + id);	
		} 
	});
}
</script>
<?php echo $footer; ?>
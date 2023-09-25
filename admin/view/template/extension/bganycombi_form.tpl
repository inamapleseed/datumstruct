<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-bganycombi" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>  </div> 
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-bganycombi" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general;?></a></li>
            <li><a href="#tab-buyfrom" data-toggle="tab"><?php echo $tab_buyfrom;?></a></li>
            <li><a href="#tab-getfrom" data-toggle="tab"><?php echo $tab_getfrom;?></a></li>
            <li><a href="#tab-displayoffer" data-toggle="tab"><?php echo $tab_displayoffer;?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                  <?php if (isset($error_name) && $error_name) { ?>
                  <div class="text-danger"><?php echo $error_name; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-discount_type"><?php echo $entry_discount_type; ?></label>
                <div class="col-sm-10">
                  <select name="discount_type" id="input-discount_type" class="form-control" onchange="showdiscount_value(this.value);">
                    <option value="1" <?php if ($discount_type == 1) { ?> selected="selected" <?php } ?> ><?php echo $text_free; ?></option>
                    <option value="2" <?php if ($discount_type == 2) { ?> selected="selected" <?php } ?> ><?php echo $text_fixed_amount; ?></option>
                    <option value="3" <?php if ($discount_type == 3) { ?> selected="selected" <?php } ?> ><?php echo $text_percentage; ?></option>
                  </select>
                </div>
              </div>
			  <div class="form-group" id="discount_value" style="display:none">
				<label class="col-sm-2 control-label"><?php echo $entry_discount_value; ?></label>
				<div class="col-sm-10">
				  <input type="text" name="discount_value" value="<?php echo $discount_value; ?>" class="form-control" /> 
				</div>
			  </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-buyqty"><?php echo $entry_buyqty; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="buyqty" value="<?php echo $buyqty; ?>" placeholder="<?php echo $entry_buyqty; ?>" id="input-buyqty" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-getqty"><?php echo $entry_getqty; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="getqty" value="<?php echo $getqty; ?>" placeholder="<?php echo $entry_getqty; ?>" id="input-getqty" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-ribbontext"><?php echo $entry_ribbontext; ?></label>
                <div class="col-sm-10">
                  <?php foreach ($languages as $language) { ?>
                  <div class="input-group pull-left"><span class="input-group-addon"><img src="<?php echo $language['imgsrc'];?>" /> </span>
                    <input type="text" name="ribbontext[<?php echo $language['language_id'];?>]" value="<?php echo isset($ribbontext[$language['language_id']]) ? $ribbontext[$language['language_id']] : ''; ?>" placeholder="<?php echo $entry_ribbontext; ?>" id="input-ribbontext<?php echo $language['language_id'];?>" class="form-control" />
                  </div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-ordertotaltext"><?php echo $entry_ordertotaltext; ?></label>
                <div class="col-sm-10">
                  <?php foreach ($languages as $language) { ?>
                  <div class="input-group pull-left"><span class="input-group-addon"><img src="<?php echo $language['imgsrc'];?>" /> </span>
                    <input type="text" name="ordertotaltext[<?php echo $language['language_id'];?>]" value="<?php echo isset($ordertotaltext[$language['language_id']]) ? $ordertotaltext[$language['language_id']] : ''; ?>" placeholder="<?php echo $entry_ordertotaltext; ?>" id="input-ordertotaltext<?php echo $language['language_id'];?>" class="form-control" />
                  </div>
                  <?php } ?>
                </div>
              </div> 
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-date_start"><?php echo $entry_date_start; ?></label>
                <div class="col-sm-3">
                  <div class="input-group date">
                    <input type="text" name="date_start" value="<?php echo $date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date_start" class="form-control" /> <span class="input-group-btn"> <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button> </span></div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-date_end"><?php echo $entry_date_end; ?></label>
                <div class="col-sm-3">
                  <div class="input-group date">
                    <input type="text" name="date_end" value="<?php echo $date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date_end" class="form-control" /> <span class="input-group-btn"> <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button> </span></div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_customer_group; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($customer_groups as $cgrp) { ?>
                    <div class="checkbox">
                      <label>
                      <?php if (isset($customer_group) && in_array($cgrp['customer_group_id'], $customer_group)) { ?>
                      <input type="checkbox" name="customer_group[]" value="<?php echo $cgrp['customer_group_id']; ?>" checked="checked" />
                      <?php echo $cgrp['name']; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="customer_group[]" value="<?php echo $cgrp['customer_group_id']; ?>" />
                      <?php echo $cgrp['name']; ?>
                      <?php } ?>
                      </label>
                    </div>
                    <?php } ?>
                  </div>
                <a class="badge" onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a class="badge" onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a> </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">
                    <div class="checkbox">
                      <label>
                      <?php if (isset($store) && in_array(0, $store)) { ?>
                      <input type="checkbox" name="store[]" value="0" checked="checked" />
                      <?php echo $text_default; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="store[]" value="0" />
                      <?php echo $text_default; ?>
                      <?php } ?>
                      </label>
                    </div>
                    <?php foreach ($stores as $store_data) { ?>
                    <div class="checkbox">
                      <label>
                      <?php if (isset($store) && in_array($store_data['store_id'], $store)) { ?>
                      <input type="checkbox" name="store[]" value="<?php echo $store_data['store_id']; ?>" checked="checked" />
                      <?php echo $store_data['name']; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="store[]" value="<?php echo $store_data['store_id']; ?>" />
                      <?php echo $store_data['name']; ?>
                      <?php } ?>
                      </label>
                    </div>
                    <?php } ?>
                  </div>
                <a class="badge" onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a class="badge" onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a> </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="status" id="input-status" class="form-control">
                    <?php if ($status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-buyfrom">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-buyproduct"><?php echo $entry_buyproduct; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="buyproduct" value=""  id="input-buyproduct" class="form-control" />
                  <div id="buyproduct" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php if (isset($buyproduct_data) && $buyproduct_data) { ?>
                    <?php foreach ($buyproduct_data as $product) { ?>
                    <div id="buyproduct-<?php echo $product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product['name']; ?>
                      <input type="hidden" name="buyproduct[]" value="<?php echo $product['product_id']; ?>" />
                    </div>
                    <?php } ?>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-buycategory"><?php echo $entry_buycategory; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="buycategory" value=""  id="input-buycategory" class="form-control" />
                  <div id="buycategory" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php if (isset($buycategory_data) && $buycategory_data) { ?>
                    <?php foreach ($buycategory_data as $category) { ?>
                    <div id="buycategory-<?php echo $category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $category['name']; ?>
                      <input type="hidden" name="buycategory[]" value="<?php echo $category['category_id']; ?>" />
                    </div>
                    <?php } ?>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-buymanufacturer"><?php echo $entry_buymanufacturer; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="buymanufacturer" value=""  id="input-buymanufacturer" class="form-control" />
                  <div id="buymanufacturer" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php if (isset($buymanufacturer_data) && $buymanufacturer_data) { ?>
                    <?php foreach ($buymanufacturer_data as $manufacturer) { ?>
                    <div id="buymanufacturer-<?php echo $manufacturer['manufacturer_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $manufacturer['name']; ?>
                      <input type="hidden" name="buymanufacturer[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />
                    </div>
                    <?php } ?>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-getfrom">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-getproduct"><?php echo $entry_getproduct; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="getproduct" value=""  id="input-getproduct" class="form-control" />
                  <div id="getproduct" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php if (isset($getproduct_data) && $getproduct_data) { ?>
                    <?php foreach ($getproduct_data as $product) { ?>
                    <div id="getproduct-<?php echo $product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product['name']; ?>
                      <input type="hidden" name="getproduct[]" value="<?php echo $product['product_id']; ?>" />
                    </div>
                    <?php } ?>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-getcategory"><?php echo $entry_getcategory; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="getcategory" value=""  id="input-getcategory" class="form-control" />
                  <div id="getcategory" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php if (isset($getcategory_data) && $getcategory_data) { ?>
                    <?php foreach ($getcategory_data as $category) { ?>
                    <div id="getcategory-<?php echo $category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $category['name']; ?>
                      <input type="hidden" name="getcategory[]" value="<?php echo $category['category_id']; ?>" />
                    </div>
                    <?php } ?>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-getmanufacturer"><?php echo $entry_getmanufacturer; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="getmanufacturer" value=""  id="input-getmanufacturer" class="form-control" />
                  <div id="getmanufacturer" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php if (isset($getmanufacturer_data) && $getmanufacturer_data) { ?>
                    <?php foreach ($getmanufacturer_data as $manufacturer) { ?>
                    <div id="getmanufacturer-<?php echo $manufacturer['manufacturer_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $manufacturer['name']; ?>
                      <input type="hidden" name="getmanufacturer[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />
                    </div>
                    <?php } ?>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-displayoffer">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-display_offer_at"><?php echo $entry_display_offer_at; ?></label>
                <div class="col-sm-10">
                  <select name="display_offer_at" id="input-display_offer_at" class="form-control">
                    <option value="1" <?php if ($display_offer_at == 1) { ?> selected="selected" <?php } ?> ><?php echo $text_above_desc_tab; ?></option>
                    <option value="2" <?php if ($display_offer_at == 2) { ?> selected="selected" <?php } ?> ><?php echo $text_at_popup; ?></option>
                    <option value="3" <?php if ($display_offer_at == 3) { ?> selected="selected" <?php } ?> ><?php echo $text_at_desc_tab; ?></option>
                  </select>
                </div>
              </div>
              <ul class="nav nav-tabs" id="language">
                <?php foreach ($languages as $language) { ?>
                <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="<?php echo $language['imgsrc'];?>" /> <?php echo $language['name']; ?></a></li>
                <?php } ?>
              </ul>
              <div class="tab-content">
                <?php foreach ($languages as $language) { ?>
                <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-offer_heading_text<?php echo $language['language_id']; ?>"><?php echo $entry_offer_heading_text; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="offer_heading_text[<?php echo $language['language_id']; ?>]" value="<?php echo isset($offer_heading_text[$language['language_id']]) ? $offer_heading_text[$language['language_id']] : ''; ?>" placeholder="<?php echo $entry_offer_heading_text; ?>" id="input-offer_heading_text<?php echo $language['language_id']; ?>" class="form-control" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-offer_content<?php echo $language['language_id']; ?>"><?php echo $entry_offer_content; ?></label>
                    <div class="col-sm-10">
                      <textarea name="offer_content[<?php echo $language['language_id']; ?>]" placeholder="<?php echo $entry_offer_content; ?>" id="input-offer_content<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($offer_content[$language['language_id']]) ? $offer_content[$language['language_id']] : ''; ?></textarea>
                    </div>
                  </div>
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php if(substr(VERSION,0,3)=='2.3') { ?>
  <script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
  <link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
  <script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>
  <?php } else if(substr(VERSION,0,3)=='2.0' || substr(VERSION,0,3)=='2.1') { ?>
  <script type="text/javascript">$('.summernote').summernote({height: 300});</script>
  <?php } ?>
  <script type="text/javascript"> 
function loadajaxprocatmanu(typeset, inptname) { 
$('input[name=\''+inptname+'\']').autocomplete({
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/'+typeset+'/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item[''+typeset+'_id']
					}
				}));
			}
		});
	},
	select: function(item) {
		$('input[name=\''+inptname+'\']').val('');
		
		$('#'+inptname+ item['value']).remove();
		
		$('#'+inptname).append('<div id="'+inptname+'-' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="'+inptname+'[]" value="' + item['value'] + '" /></div>');	
	}
});
	
$('#'+inptname).delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
}); 
}
</script>
  <script language="javascript"> 
loadajaxprocatmanu('product', 'buyproduct') ; 
loadajaxprocatmanu('category', 'buycategory') ; 
loadajaxprocatmanu('manufacturer', 'buymanufacturer') ; 
loadajaxprocatmanu('product', 'getproduct') ; 
loadajaxprocatmanu('category', 'getcategory') ; 
loadajaxprocatmanu('manufacturer', 'getmanufacturer') ; 

$('#language a:first').tab('show');

$( document ).ready(function() {
	$('#discount_value').hide();
    if($('#input-discount_type').val() == 2 || $('#input-discount_type').val() == 3) { 
		$('#discount_value').show();
	}
});
function showdiscount_value(selectval) {
	if(selectval == 2 || selectval == 3) {
		$('#discount_value').show();
	} else {
		$('#discount_value').hide();
	}
}
</script>
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

$('.time').datetimepicker({
	pickDate: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});
//--></script>
</div>
<?php echo $footer; ?>
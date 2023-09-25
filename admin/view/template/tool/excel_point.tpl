<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>Excel Export Point</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if($error_warning){ ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php if($success){ ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> Excel Export Point</h3>
      </div>
      <div class="panel-body">
	  <style>
		.nav-tabs{
			margin-bottom:0px;
		}
		.tab-content{
			margin-top: 0px;
			border-left: 1px solid #ddd;
			border-right: 1px solid #ddd;
			border-bottom: 1px solid #ddd;
			padding:20px;
		}
		.btn-success a{
			color:#fff;
			font-size:14px;
			text-transform:uppercase;
		}
		.tbpadding{
			padding:10px;
		}
		.ourbtn{
			background-color:#921e6f;
			border:none;
			text-transform:uppercase;
		}
		.ourbtn:hover{
			background-color:#921e6f;
			border:none;
		}
		.ourbtn i{
			margin-right:5px;
		}
		
		.tab-content input {
			background-color: #fafafa;
			border-radius: 0;
			height: 40px;	
		}
		.tab-content select{
			background-color: #fafafa;
			border-radius: 0;
			height: 40px;	
		}
		</style>
	      <div class="tab-content">
            <div class="tab-pane active in" id="tab-export">
				<div class="row">
					<div class="col-sm-12">
					  <ul class="nav nav-tabs">
						<li class="active"><a href="#categories" data-toggle="tab">Categories</a></li>
						<li><a href="#product" data-toggle="tab">Products</a></li>
						<li><a href="#manufacture" data-toggle="tab">Manufacture</a></li>
					   </ul>
					</div>
					<div class="col-sm-12">
						<div class="tab-content">
							<div class="tab-pane active" id="categories">
								<h3>Category Export</h3>
								<?php require_once('export/categoriesexport.tpl'); ?>
							</div>
							<div class="tab-pane" id="product">
								<h3>Products Export</h3>
								<?php require_once('export/pexport.tpl'); ?>
							</div>
							<div class="tab-pane" id="manufacture">
								<h3>Manufacturer Export</h3>
								<?php require_once('export/manufactureexport.tpl'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	  </div>
    </div>
 </div>
</div>
<script type="text/javascript"><!--
$('#button_filter_manufacture').on('click', function(){
	var url = 'index.php?route=tool/excel_export/exportManufacture&token=<?php echo $token; ?>';
	
	var filter_store = $('#manufacture select[name=\'filter_store\']').val();

	if(filter_store != '*'){
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
	
	var filter_language_id = $('#manufacture select[name=\'filter_language_id\']').val();

	if(filter_language_id != '*'){
		url += '&filter_language=' + encodeURIComponent(filter_language_id);
	}
	
	var filter_limit = $('#manufacture input[name=\'filter_limit\']').val();

	if(filter_limit != '*'){
		url += '&filter_limit=' + encodeURIComponent(filter_limit);
	}
	
	var filter_start = $('#manufacture input[name=\'filter_start\']').val();

	if(filter_start != '*'){
		url += '&filter_start=' + encodeURIComponent(filter_start);
	}
	
	var filter_categories = $('#manufacture select[name=\'filter_categories\']').val();

	if (filter_categories != '*') {
		url += '&filter_categories=' + encodeURIComponent(filter_categories);
	}
	
	location = url;
});

$('#button_filter_categories').on('click', function(){
	var url = 'index.php?route=tool/excel_export/exportCategories&token=<?php echo $token; ?>';
	
	var filter_store = $('#categories select[name=\'filter_store\']').val();

	if(filter_store != '*'){
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
	
	var filter_language_id = $('#categories select[name=\'filter_language_id\']').val();

	if(filter_language_id != '*'){
		url += '&filter_language=' + encodeURIComponent(filter_language_id);
	}
	
	var filter_limit = $('#categories input[name=\'filter_limit\']').val();

	if(filter_limit != '*'){
		url += '&filter_limit=' + encodeURIComponent(filter_limit);
	}
	
	var filter_start = $('#categories input[name=\'filter_start\']').val();

	if(filter_start != '*'){
		url += '&filter_start=' + encodeURIComponent(filter_start);
	}
	
	var filter_categories = $('#categories select[name=\'filter_categories\']').val();

	if (filter_categories != '*') {
		url += '&filter_categories=' + encodeURIComponent(filter_categories);
	}
	
	var filter_eformat = $('#categories select[name=\'filter_eformat\']').val();
	if(filter_eformat != ''){
		url += '&filter_eformat=' + encodeURIComponent(filter_eformat);
	}
	
	var filter_pimage = $('#categories select[name=\'filter_pimage\']').val();
	if(filter_pimage != ''){
		url += '&filter_pimage=' + encodeURIComponent(filter_pimage);
	}
	
	location = url;
});

$('#button-filter_product').on('click', function() {
	var url = 'index.php?route=tool/excel_export/exportproducts&token=<?php echo $token; ?>';

	var filter_name = $('#product input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_model = $('#product input[name=\'filter_model\']').val();

	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}

	var filter_price_to = $('#product input[name=\'filter_price_to\']').val();

	if (filter_price_to) {
		url += '&filter_price_to=' + encodeURIComponent(filter_price_to);
	}
	
	var filter_price_form = $('#product input[name=\'filter_price_form\']').val();

	if (filter_price_form) {
		url += '&filter_price_form=' + encodeURIComponent(filter_price_form);
	}

	var filter_quantity_to = $('#product input[name=\'filter_quantity_to\']').val();

	if (filter_quantity_to) {
		url += '&filter_quantity_to=' + encodeURIComponent(filter_quantity_to);
	}
	
	var filter_quantity_form = $('#product input[name=\'filter_quantity_form\']').val();

	if (filter_quantity_form) {
		url += '&filter_quantity_form=' + encodeURIComponent(filter_quantity_form);
	}

	var filter_status = $('#product select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
	
	var filter_store = $('#product select[name=\'filter_store\']').val();

	if (filter_store != '*') {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
	
	var filter_language_id = $('#product select[name=\'filter_language_id\']').val();

	if (filter_language_id != '*') {
		url += '&filter_language=' + encodeURIComponent(filter_language_id);
	}
	
	var filter_stock_status = $('#product select[name=\'filter_stock_status\']').val();

	if (filter_stock_status != '*') {
		url += '&filter_stock_status=' + encodeURIComponent(filter_stock_status);
	}
	
	var filter_start = $('#product input[name=\'filter_start\']').val();

	if (filter_start != '*') {
		url += '&filter_start=' + encodeURIComponent(filter_start);
	}
	
	var filter_limit = $('#product input[name=\'filter_limit\']').val();

	if (filter_limit != '*') {
		url += '&filter_limit=' + encodeURIComponent(filter_limit);
	}
	
	var filter_idstart = $('#product input[name=\'filter_idstart\']').val();

	if (filter_idstart != '*') {
		url += '&filter_idstart=' + encodeURIComponent(filter_idstart);
	}
	
	var filter_idend = $('#product input[name=\'filter_idend\']').val();

	if (filter_idend != '*') {
		url += '&filter_idend=' + encodeURIComponent(filter_idend);
	}
	
	var filter_categories = $('#product select[name=\'filter_categories\']').val();

	if (filter_categories != '*') {
		url += '&filter_categories=' + encodeURIComponent(filter_categories);
	}
	
	var filter_eformat = $('#product select[name=\'filter_eformat\']').val();
	if(filter_eformat != ''){
		url += '&filter_eformat=' + encodeURIComponent(filter_eformat);
	}
	
	var filter_pimage = $('#product select[name=\'filter_pimage\']').val();
	if(filter_pimage != ''){
		url += '&filter_pimage=' + encodeURIComponent(filter_pimage);
	}
	
	var filter_manufacturer = $('#product select[name=\'filter_manufacturer\']').val();

	if (filter_manufacturer != '*') {
		url += '&filter_manufacturer=' + encodeURIComponent(filter_manufacturer);
	}
	
	location = url;
});
//--></script>
<script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_name\']').val(item['label']);
	}
});

$('input[name=\'filter_model\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['model'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_model\']').val(item['label']);
	}
});
//--></script>
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script>
<?php echo $footer; ?>
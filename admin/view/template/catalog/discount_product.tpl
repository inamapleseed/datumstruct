<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a type="submit" form="" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary" id="save-button"><i class="fa fa-save"></i></a>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
		<form method="post" enctype="multipart/form-data" id="form-product-discount" class="form-horizontal">  	
		  <?php $discount_row = 0; ?>
		  <!-- Navigation Buttons -->
		  <div class="col-md-2">
			<ul class="nav nav-pills nav-stacked" id="productTabs">
				<?php foreach ($product_discounts as $product) { ?>
				<li><a href="#<?php echo $product['product_data']['product_id']; ?>" data-toggle="pill"><?php echo $product['product_data']['product_name']; ?></a></li>
				<?php } ?>
				<li data-toggle="modal" data-target="#addProduct" id="add-button"><a href="" data-toggle="pill"><i class="fa fa-plus-square fa-2x"></i></a></li>
			</ul>
		  </div>
		  <!-- Content -->
				  <div class="col-md-10">
					<div class="tab-content">
						<?php foreach ($product_discounts as $product) { ?>
						<div class="tab-pane" id="<?php echo $product['product_data']['product_id']; ?>">
							<h4><?php echo $product['product_data']['product_name']; ?> <?php echo $heading_title; ?></h4>
							<input type="hidden" name="product_price<?php echo $product['product_data']['product_id']; ?>" value="<?php echo $product['product_data']['product_price']; ?>"  />
								<div class="table-responsive">
								<table id="discount<?php echo $product['product_data']['product_id']; ?>" class="table table-striped table-bordered table-hover">
								  <thead>
									<tr>
									  <td class="text-left col-md-2"><?php echo $entry_customer_group; ?></td>
									  <td class="text-right col-md-1"><?php echo $entry_quantity; ?></td>
									  <td class="text-right col-md-1"><?php echo $entry_priority; ?></td>
									  <td class="text-right col-md-1"><?php echo $entry_percentage; ?></td>
									  <td class="text-right col-md-2"><?php echo $entry_price; ?></td>
									  <td class="text-left col-md-2"><?php echo $entry_date_start; ?></td>
									  <td class="text-left col-md-2"><?php echo $entry_date_end; ?></td>
									  <td class="text-left col-md-1"></td>
									</tr>
								  </thead>
								  <tbody>
									<?php foreach ($product['discount_data'] as $product_discount) { ?>
									<tr id="discount-row<?php echo $discount_row; ?>"> 
									  <td class="text-left">
										<input type="hidden" name="product_discount[<?php echo $discount_row; ?>][product_id]" value="<?php echo $product['product_data']['product_id']; ?>">
										<select name="product_discount[<?php echo $discount_row; ?>][customer_group_id]" class="form-control row<?php echo $discount_row; ?>">
										  <?php foreach ($customer_groups as $customer_group) { ?>
										  <?php if ($customer_group['customer_group_id'] == $product_discount['customer_group_id']) { ?>
										  <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
										  <?php } else { ?>
										  <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
										  <?php } ?>
										  <?php } ?>
										</select></td>
										<td class="text-right"><input type="text" name="product_discount[<?php echo $discount_row; ?>][quantity]" value="<?php echo $product_discount['quantity']; ?>" placeholder="<?php echo $entry_quantity; ?>" class="form-control row<?php echo $discount_row; ?>" /></td>
									  <td class="text-right"><input type="text" name="product_discount[<?php echo $discount_row; ?>][priority]" value="<?php echo $product_discount['priority']; ?>" placeholder="<?php echo $entry_priority; ?>" class="form-control row<?php echo $discount_row; ?>" /></td>
									  <td class="text-right"><input type="text" name="product_discount[<?php echo $discount_row; ?>][percentage]" value="<?php echo $product_discount['percentage']; ?>" placeholder="<?php echo $entry_percentage; ?>" class="form-control row<?php echo $discount_row; ?>" onkeyup="calcPrice('discount', <?php echo $discount_row; ?>, ?php echo $product['product_data']['product_id']; ?>)" /></td>
									  <td class="text-right"><input type="text" name="product_discount[<?php echo $discount_row; ?>][price]" value="<?php echo $product_discount['price']; ?>" placeholder="<?php echo $entry_price; ?>" class="form-control row<?php echo $discount_row; ?>" />
									   </td>
									  <td class="text-left" style="width: 20%;"><div class="input-group date">
										  <input type="text" name="product_discount[<?php echo $discount_row; ?>][date_start]" value="<?php echo $product_discount['date_start']; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control row<?php echo $discount_row; ?>" />
										  <span class="input-group-btn">
										  <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
										  </span></div></td>
									  <td class="text-left" style="width: 20%;"><div class="input-group date">
										  <input type="text" name="product_discount[<?php echo $discount_row; ?>][date_end]" value="<?php echo $product_discount['date_end']; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control row<?php echo $discount_row; ?>" />
										  <span class="input-group-btn">
										  <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
										  </span></div></td>
									  <td class="text-left"><button type="button" onclick="$('#discount-row<?php echo $discount_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
									</tr>
									<?php $discount_row++; ?>
									<?php } ?>
								  </tbody>
								  <tfoot>
									<tr>
									  <td colspan="7"></td>
									  <td class="text-left"><button type="button" onclick="addDiscount(<?php echo $product['product_data']['product_id']; ?>);" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
									</tr>
								  </tfoot>
								</table>
							 </div> <!-- // .table-responsive -->
						</div> <!-- // .tab-pane -->
						<?php } ?> 	
					</div>
				  </div> <!-- // Content -->
       		</form>
       		<div class="modal fade" id="addProduct" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
       			<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel"><?php echo $button_add; ?> <?php echo $heading_title; ?></h4>
						</div>
						<div class="modal-body">
							<input type="hidden" id="ac_product_id" name="new_discount[product_id]" value="" />
							<input type="hidden" id="ac_product_price" name="new_discount[price]" value="" />
							<input type="text" id="ac_product_name" name="new_discount[product_name]" value="" class="form-control" onkeyup="AC()"/>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" onclick="addProduct();" data-dismiss="modal"><?php echo $button_add; ?></button>
						</div>
					</div>
				</div>
       		</div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
	
	var discount_row = <?php echo $discount_row; ?>;

	function addDiscount(id) {
		
		html  = '<tr id="discount-row' + discount_row + '">';
		html += '  <td class="text-left"><input type="hidden" name="product_discount[' + discount_row + '][product_id]" value="' + id + '" />';
		html += '  <select name="product_discount[' + discount_row + '][customer_group_id]" class="form-control">';
		<?php foreach ($customer_groups as $customer_group) { ?>
		html += '    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo addslashes($customer_group['name']); ?></option>';
		<?php } ?>
		html += '  </select></td>';	
		html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][quantity]" value="" placeholder="<?php echo $entry_quantity; ?>" class="form-control" /></td>';	
		html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][priority]" value="" placeholder="<?php echo $entry_priority; ?>" class="form-control" /></td>';
		html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][percentage]" value="" placeholder="<?php echo $entry_percentage; ?>" class="form-control" onkeyup="calcPrice(\'discount\',' + discount_row +',' + id + ')"/></td>';
		html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][price]" value="" placeholder="<?php echo $entry_price; ?>" class="form-control" />';
		html += '  </td>';
		html += '  <td class="text-left"><div class="input-group date"><input type="text" name="product_discount[' + discount_row + '][date_start]" value="" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
		html += '  <td class="text-left"><div class="input-group date"><input type="text" name="product_discount[' + discount_row + '][date_end]" value="" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
		html += '  <td class="text-left"><button type="button" onclick="$(\'#discount-row' + discount_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
		html += '</tr>';	
	
		$('#discount' + id + ' tbody').append(html);

		$('.date').datetimepicker({
			pickTime: false
		});
	
		discount_row++;
	}
	
	function addProduct() {
		
		var id = $('#ac_product_id').val();
		var name = $('#ac_product_name').val();
		var price = $('#ac_product_price').val();
		
		html = '<li><a href="#' + id + '" data-toggle="pill" id="pill' + id + '">' + name + '</a></li>';
		$('#add-button').before(html);
		
		html = '<div class="tab-pane" id="' + id + '"><h4>' + name + ' <?php echo $heading_title; ?></h4><input type="hidden" name="product_price' + id + '" value="' + price + '"  />';
		html += '<div class="table-responsive"><table id="discount' + id + '" class="table table-striped table-bordered table-hover">';
		html += '<thead><tr><td class="text-left col-md-2"><?php echo $entry_customer_group; ?></td>';
		html += '<td class="text-right col-md-1"><?php echo $entry_quantity; ?></td><td class="text-right col-md-1"><?php echo $entry_priority; ?></td>';
		html += '<td class="text-right col-md-1"><?php echo $entry_percentage; ?></td><td class="text-right col-md-1"><?php echo $entry_price; ?></td>';
		html += '<td class="text-left col-md-2"><?php echo $entry_date_start; ?></td><td class="text-left col-md-2"><?php echo $entry_date_end; ?></td>';
		html += '<td></td></tr></thead><tbody>';
		html += '</tbody><tfoot><tr><td colspan="7"></td>';
		html += '<td class="text-left"><button type="button" onclick="addDiscount(' + id + ')" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>';
		html += '</tr></tfoot></table></div></div>';
		
		$('.tab-content').append(html);
		
		$('#pill' + id).tab('show');
	}
	
	function calcPrice(name, row, id) {
			
		var perc = $('input[name=\'product_' + name + '[' + row + '][percentage]\']').val();
		var price = $('input[name=\'product_price' + id + '\']').val();
		var discounted_price = price * (1-(perc/100));
		var calculated_percentage = discounted_price * (1-(price/100));
		
		$('input[name=\'product_' + name + '[' + row + '][price]\']').val(discounted_price);
		
	}
	
	<?php if ($permission) { ?>
	$('#save-button').click(function(){
		
		$.ajax({
			url:'index.php?route=catalog/discount_product/saveDiscount&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			data: {
				product_discount: $('#form-product-discount').serialize(),
			},
			success: function(json) {
				alertJson('alert alert-success', json);
			},
			error: function(json) {
				alertJson('alert alert-warning', json);
			}
		});
		
		return false;
	});
	<?php } else { ?>
		$('#save-button').click(function(){
			$('.alert').remove();
			
			$(".panel").before('<div class="alert alert-warning"><?php echo $error_permission; ?></div>');
		});
	<?php } ?>
	
	function alertJson(action, json) {
		
		$('.alert').remove();
		
		if (json['success']) {
			$(".panel").before('<div class="' + action + '">' + json['success'] + '</div>');
		} else if (json['error']) {
			$(".panel").before('<div class="' + action + '">' + json['error'] + '</div>');
		}
		
	}
	
	function AC() {
					
		$('#ac_product_name').autocomplete({
				delay: 300,
				source: function(request, response) {
					$.ajax({
						url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
						dataType: 'json',
						success: function(json) {	
							response($.map(json, function(item) {
								return {
									label: item.name,
									value: item.product_id,
									price: item.price
								}
							}));
						}
					});
				}, 
				select: function(item) {
					$('#ac_product_name').val(item['label']);
					$('#ac_product_id').val(item['value']);
					$('#ac_product_price').val(item['price']);
					return false;
				},
				minLength: 3
		});
		
	}
	
	$('#productTabs a:first').tab('show');
	
//--></script></div>
<?php echo $footer; ?>
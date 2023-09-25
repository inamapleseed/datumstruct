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
		  	<form method="post" enctype="multipart/form-data" id="form-settings" class="form-horizontal"> 
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
				<div class="col-sm-10">
				  <select name="loyalty_discount_status" id="input-status" class="setting form-control">
					<?php if ($loyalty_discount_status) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
				  </select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
				<div class="col-sm-10">
				  <input type="text" name="loyalty_discount_sort_order" value="<?php echo $loyalty_discount_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="setting form-control" />
				</div>
			</div>
			</form>
			<form method="post" enctype="multipart/form-data" id="form-loyalty-discount" class="form-horizontal">  	
              <div class="table-responsive">
                <table id="discount" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td></td>	
                      <td class="text-left col-md-2"><?php echo $entry_customer_group; ?></td>
                      <td class="text-left col-md-1"><?php echo $entry_ordertotal; ?></td>
                      <td class="text-left col-md-2"><?php echo $entry_order_status; ?></td>	
                      <td class="text-left col-md-1"><?php echo $entry_priority; ?></td>
                      <td class="text-left col-md-1"><?php echo $entry_discount; ?></td>
                      <td class="text-left col-md-3"><?php echo $entry_date_start; ?></td>
                      <td class="text-left col-md-3"><?php echo $entry_date_end; ?></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $discount_row = 0; ?>
                    <?php foreach ($loyalty_discounts as $loyalty_discount) { ?>
                      <tr id="discount-row<?php echo $discount_row; ?>">
						  <td><?php if ($loyalty_discount['status']) { ?>
								<a  id="active-<?php echo $loyalty_discount['loyalty_discount_id']; ?>" onclick="<?php if ($permission) { ?>deactivate(<?php echo $loyalty_discount['loyalty_discount_id']; ?>);<?php } ?>" class="btn btn-sm btn-success" data-toggle="tooltip" title="<?php echo $text_enabled; ?>">
								<i class="fa fa-minus-circle fa-rotate-90 fa-2x"></i>
								</a>
								<input name="loyalty_discount[<?php echo $discount_row; ?>][status]" id="status<?php echo $loyalty_discount['loyalty_discount_id']; ?>" type="hidden" value="1">
								<?php } else { ?>
								<a id="inactive-<?php echo $loyalty_discount['loyalty_discount_id']; ?>" onclick="<?php if ($permission) { ?>activate(<?php echo $loyalty_discount['loyalty_discount_id']; ?>);<?php } ?>" class="btn btn-sm btn-danger" data-toggle="tooltip" title="<?php echo $text_disabled; ?>"><i class="fa fa-minus-circle fa-rotate-90 fa-2x"></i></a>
								<input name="loyalty_discount[<?php echo $discount_row; ?>][status]" id="status<?php echo $loyalty_discount['loyalty_discount_id']; ?>" type="hidden" value="0">
								<?php } ?>
						  </td>
						  <td class="text-left"><select name="loyalty_discount[<?php echo $discount_row; ?>][customer_group_id]" class="form-control row<?php echo $discount_row; ?>">
							  <?php foreach ($customer_groups as $customer_group) { ?>
							  <?php if ($customer_group['customer_group_id'] == $loyalty_discount['customer_group_id']) { ?>
							  <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
							  <?php } else { ?>
							  <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
							  <?php } ?>
							  <?php } ?>
						  </select></td>                     
						  <td class="text-left">
						  	<input type="text" name="loyalty_discount[<?php echo $discount_row; ?>][ordertotal]" value="<?php echo $loyalty_discount['ordertotal']; ?>" placeholder="<?php echo $entry_ordertotal; ?>" class="form-control row<?php echo $discount_row; ?>" />
						  </td>
						  <td class="text-left">
						  
						  	<div id="order_status<?php echo $row; ?>" class="panel panel-default panel-body" style="height: 150px; overflow: auto;">
							<?php foreach ($order_statuses as $order_status) { ?>
								<div class="checkbox">
								<label>
								<?php if (isset($loyalty_discount['order_status']) && is_array($loyalty_discount['order_status']) && in_array($order_status['order_status_id'], $loyalty_discount['order_status'])) { ?>
									<input type="checkbox" class="cbox" name="loyalty_discount[<?php echo $discount_row; ?>][order_status][]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" /> <?php echo $order_status['name']; ?>
								<?php } else { ?>
									<input type="checkbox" class="cbox" name="loyalty_discount[<?php echo $discount_row; ?>][order_status][]" value="<?php echo $order_status['order_status_id']; ?>"/> <?php echo $order_status['name']; ?>
								<?php } ?>
								</label>
								</div>
							<?php } ?>
							<?php if (count($order_statuses) > 1) { ?>
							<div class="panel-footer"><a onclick="$('#order_status<?php echo $row; ?> .cbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$('#order_status<?php echo $row; ?> .cbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
							<?php } ?>
							</div>
						  </div>
						  </td>
						  <td class="text-right"><input type="text" name="loyalty_discount[<?php echo $discount_row; ?>][priority]" value="<?php echo $loyalty_discount['priority']; ?>" placeholder="<?php echo $entry_priority; ?>" class="form-control row<?php echo $discount_row; ?>" /></td>
						  
						  <td class="text-left"><input type="text" name="loyalty_discount[<?php echo $discount_row; ?>][discount]" value="<?php echo $loyalty_discount['discount']; ?>" placeholder="<?php echo $entry_discount; ?>" class="form-control row<?php echo $discount_row; ?>" /></td>
						  <td class="text-left" style="width: 20%;"><div class="input-group date">
							  <input type="text" name="loyalty_discount[<?php echo $discount_row; ?>][date_start]" value="<?php echo $loyalty_discount['date_start']; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control row<?php echo $discount_row; ?>" />
							  <span class="input-group-btn">
							  <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
							  </span></div></td>
						  <td class="text-left" style="width: 20%;"><div class="input-group date">
							  <input type="text" name="loyalty_discount[<?php echo $discount_row; ?>][date_end]" value="<?php echo $loyalty_discount['date_end']; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control row<?php echo $discount_row; ?>" />
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
                      <td colspan="8"></td>
                      <td class="text-left"><button type="button" onclick="addDiscount();" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
	
	var discount_row = <?php echo $discount_row; ?>;

	function addDiscount() {
		html  = '<tr id="discount-row' + discount_row + '">';
		html += '  <td><input name="loyalty_discount[' + discount_row + '][status]" id="status' + discount_row + '" type="hidden" value="1"></td>';
		html += '  <td class="text-left"><select name="loyalty_discount[' + discount_row + '][customer_group_id]" class="form-control">';
		<?php foreach ($customer_groups as $customer_group) { ?>
		html += '    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo addslashes($customer_group['name']); ?></option>';
		<?php } ?>
		html += '  </select></td>';	
		html += '  <td class="text-left"><input type="text" name="loyalty_discount[' + discount_row + '][ordertotal]" value="" placeholder="<?php echo $entry_ordertotal; ?>" class="form-control" /></td>';
		html += '<td class="left"><div id="order_status' + discount_row + '" class="panel panel-default panel-body" style="height: 150px; overflow: auto;">';
		<?php foreach ($order_statuses as $order_status) { ?>
		html += '<div class="checkbox"><label>';
		html += '<input type="checkbox" class="cbox" name="loyalty_discount[' + discount_row + '][order_status][]" value="<?php echo $order_status['order_status_id']; ?>" checked/>';
		html += '<?php echo $order_status['name']; ?></label></div>';
		<?php } ?>
		<?php if (count($order_statuses) > 1) { ?>
		html += '<div class="panel-footer"><a onclick="$(\'#order_status' + discount_row + ' .cbox\').prop(\'checked\', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(\'#order_status' + discount_row + '\  .cbox\').prop(\'checked\', false);"><?php echo $text_unselect_all; ?></a></td>';
		<?php } ?>			
		html += '  <td class="text-left"><input type="text" name="loyalty_discount[' + discount_row + '][priority]" value="" placeholder="<?php echo $entry_priority; ?>" class="form-control" /></td>';
		html += '  <td class="text-left"><input type="text" name="loyalty_discount[' + discount_row + '][discount]" value="" placeholder="<?php echo $entry_discount; ?>" class="form-control" /></td>';
		html += '  <td class="text-left"><div class="input-group date"><input type="text" name="loyalty_discount[' + discount_row + '][date_start]" value="" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
		html += '  <td class="text-left"><div class="input-group date"><input type="text" name="loyalty_discount[' + discount_row + '][date_end]" value="" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
		html += '  <td class="text-left"><button type="button" onclick="$(\'#discount-row' + discount_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
		html += '</tr>';	
	
		$('#discount tbody').append(html);

		$('.date').datetimepicker({
			pickTime: false
		});
	
		discount_row++;
	}
	
	
	<?php if ($permission) { ?>
	$('#save-button').click(function(){
		
		$.ajax({
			url:'index.php?route=catalog/discount_loyalty/saveDiscount&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			data: {
				setting: $("#form-settings").serialize(),
				loyalty_discount: $('#form-loyalty-discount').serialize(),
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
	
	function activate(row) {
		
		$.ajax({
			url:'index.php?route=catalog/discount_loyalty/activate&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			data: {
				row: row,
			},
			success: function(json) {
				alertJson('alert alert-success', json);
				$('#inactive-' + row).replaceWith('<a  id="active-' + row + '" onclick="<?php if ($permission) { ?>deactivate(' + row + ');<?php } ?>" class="btn btn-sm btn-success" data-toggle="tooltip" title="<?php echo $text_enabled; ?>"><i class="fa fa-minus-circle fa-rotate-90 fa-2x"></i></a>');
				$('#status' + row).val(1);
			},
			error: function(json) {
				alertJson('alert alert-warning', json);
			}
		});
	
	}
	
	function deactivate(row) {
		
		$.ajax({
			url:'index.php?route=catalog/discount_loyalty/deactivate&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			data: {
				row: row,
			},
			success: function(json) {
				alertJson('alert alert-success', json);
				$('#active-' + row).replaceWith('<a id="inactive-' + row + '" onclick="<?php if ($permission) { ?>activate(' + row + ');<?php } ?>" class="btn btn-sm btn-danger" data-toggle="tooltip" title="<?php echo $text_disabled; ?>"><i class="fa fa-minus-circle fa-rotate-90 fa-2x"></i></a>');
				$('#status' + row).val(0);
			},
			error: function(json) {
				alertJson('alert alert-warning', json);
			}
		});
	
	}
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
//--></script></div>
<?php echo $footer; ?>
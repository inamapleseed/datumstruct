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
				  <select name="ordertotal_discount_status" id="input-status" class="setting form-control">
					<?php if ($ordertotal_discount_status) { ?>
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
				  <input type="text" name="ordertotal_discount_sort_order" value="<?php echo $ordertotal_discount_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="setting form-control" />
				</div>
			</div>
			</form>
			<form method="post" enctype="multipart/form-data" id="form-ordertotal-discount" class="form-horizontal">  	
              <div class="table-responsive">
                <table id="discount" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td></td>	
                      <td class="text-left col-md-2"><?php echo $entry_customer_group; ?></td>
                      <td class="text-left col-md-1"><?php echo $entry_ordertotal; ?></td>	
                      <td class="text-left col-md-1"><?php echo $entry_priority; ?></td>
                      <td class="text-left col-md-2"><?php echo $entry_type; ?></td>
                      <td class="text-left col-md-1"><?php echo $entry_discount; ?></td>
                      <td class="text-left col-md-3"><?php echo $entry_date_start; ?></td>
                      <td class="text-left col-md-3"><?php echo $entry_date_end; ?></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $discount_row = 0; ?>
                    <?php foreach ($ordertotal_discounts as $ordertotal_discount) { ?>
                      <tr id="discount-row<?php echo $discount_row; ?>">
						  <td><?php if ($ordertotal_discount['status']) { ?>
								<a  id="active-<?php echo $ordertotal_discount['ordertotal_discount_id']; ?>" onclick="<?php if ($permission) { ?>deactivate(<?php echo $ordertotal_discount['ordertotal_discount_id']; ?>);<?php } ?>" class="btn btn-sm btn-success" data-toggle="tooltip" title="<?php echo $text_enabled; ?>">
								<i class="fa fa-minus-circle fa-rotate-90 fa-2x"></i>
								</a>
								<input name="ordertotal_discount[<?php echo $discount_row; ?>][status]" id="status<?php echo $ordertotal_discount['ordertotal_discount_id']; ?>" type="hidden" value="1">
								<?php } else { ?>
								<a id="inactive-<?php echo $ordertotal_discount['ordertotal_discount_id']; ?>" onclick="<?php if ($permission) { ?>activate(<?php echo $ordertotal_discount['ordertotal_discount_id']; ?>);<?php } ?>" class="btn btn-sm btn-danger" data-toggle="tooltip" title="<?php echo $text_disabled; ?>"><i class="fa fa-minus-circle fa-rotate-90 fa-2x"></i></a>
								<input name="ordertotal_discount[<?php echo $discount_row; ?>][status]" id="status<?php echo $ordertotal_discount['ordertotal_discount_id']; ?>" type="hidden" value="0">
								<?php } ?>
						  </td>
						  <td class="text-left"><select name="ordertotal_discount[<?php echo $discount_row; ?>][customer_group_id]" class="form-control row<?php echo $discount_row; ?>">
							  <?php foreach ($customer_groups as $customer_group) { ?>
							  <?php if ($customer_group['customer_group_id'] == $ordertotal_discount['customer_group_id']) { ?>
							  <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
							  <?php } else { ?>
							  <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
							  <?php } ?>
							  <?php } ?>
						  </select></td>                     
						  <td class="text-left">
						  	<input type="text" name="ordertotal_discount[<?php echo $discount_row; ?>][ordertotal]" value="<?php echo $ordertotal_discount['ordertotal']; ?>" placeholder="<?php echo $entry_ordertotal; ?>" class="form-control row<?php echo $discount_row; ?>" />
						  </td>	
						  <td class="text-right"><input type="text" name="ordertotal_discount[<?php echo $discount_row; ?>][priority]" value="<?php echo $ordertotal_discount['priority']; ?>" placeholder="<?php echo $entry_priority; ?>" class="form-control row<?php echo $discount_row; ?>" /></td>
						  <td class="text-left">
						  <select name="ordertotal_discount[<?php echo $discount_row; ?>][type]" class="form-control row<?php echo $discount_row; ?>">
								  <?php if ($ordertotal_discount['type'] == 'F') { ?>
								  	<option value="F" selected="selected"><?php echo $text_fixed; ?></option>
								  <?php } else { ?>
								  	<option value="F"><?php echo $text_fixed; ?></option>
								  <?php } ?>
								   <?php if ($ordertotal_discount['type'] == 'P') { ?>
								  	 <option value="P" selected="selected"><?php echo $text_percentage; ?></option>
								  <?php } else { ?>
								  	<option value="P"><?php echo $text_percentage; ?></option>
								  <?php } ?>
							</select>
						  </td>
						  <td class="text-left"><input type="text" name="ordertotal_discount[<?php echo $discount_row; ?>][discount]" value="<?php echo $ordertotal_discount['discount']; ?>" placeholder="<?php echo $entry_discount; ?>" class="form-control row<?php echo $discount_row; ?>" /></td>
						  <td class="text-left" style="width: 20%;"><div class="input-group date">
							  <input type="text" name="ordertotal_discount[<?php echo $discount_row; ?>][date_start]" value="<?php echo $ordertotal_discount['date_start']; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control row<?php echo $discount_row; ?>" />
							  <span class="input-group-btn">
							  <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
							  </span></div></td>
						  <td class="text-left" style="width: 20%;"><div class="input-group date">
							  <input type="text" name="ordertotal_discount[<?php echo $discount_row; ?>][date_end]" value="<?php echo $ordertotal_discount['date_end']; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control row<?php echo $discount_row; ?>" />
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
		html += '  <td><input name="ordertotal_discount[' + discount_row + '][status]" id="status' + discount_row + '" type="hidden" value="1"></td>';
		html += '  <td class="text-left"><select name="ordertotal_discount[' + discount_row + '][customer_group_id]" class="form-control">';
		<?php foreach ($customer_groups as $customer_group) { ?>
		html += '    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo addslashes($customer_group['name']); ?></option>';
		<?php } ?>
		html += '  </select></td>';	
		html += '  <td class="text-left"><input type="text" name="ordertotal_discount[' + discount_row + '][ordertotal]" value="" placeholder="<?php echo $entry_ordertotal; ?>" class="form-control" /></td>';			
		html += '  <td class="text-left"><input type="text" name="ordertotal_discount[' + discount_row + '][priority]" value="" placeholder="<?php echo $entry_priority; ?>" class="form-control" /></td>';
		html += '  <td class="text-left"><select name="ordertotal_discount[' + discount_row + '][type]" class="form-control">';
		html += '    <option value="F"><?php echo $text_fixed; ?></option>';
		html += '    <option value="P"><?php echo $text_percentage; ?></option>';
		html += '  </select></td>';
		html += '  <td class="text-left"><input type="text" name="ordertotal_discount[' + discount_row + '][discount]" value="" placeholder="<?php echo $entry_discount; ?>" class="form-control" /></td>';
		html += '  <td class="text-left"><div class="input-group date"><input type="text" name="ordertotal_discount[' + discount_row + '][date_start]" value="" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
		html += '  <td class="text-left"><div class="input-group date"><input type="text" name="ordertotal_discount[' + discount_row + '][date_end]" value="" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
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
			url:'index.php?route=catalog/discount_ordertotal/saveDiscount&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			data: {
				setting: $("#form-settings").serialize(),
				ordertotal_discount: $('#form-ordertotal-discount').serialize(),
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
			url:'index.php?route=catalog/discount_ordertotal/activate&token=<?php echo $token; ?>',
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
			url:'index.php?route=catalog/discount_ordertotal/deactivate&token=<?php echo $token; ?>',
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
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
	<button id="verify_settings_button" data-toggle="tooltip" title="<?php echo $button_verify_settings; ?>" class="btn btn-primary"><i class="fa fa-play"></i></button>

        <button type="submit" form="form-bluesnap" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <?php if ($success) {?>
	<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      		<button type="button" class="close" data-dismiss="alert">&times;</button>
    	</div>
    <?php }?>
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#tab-settings">Settings</a></li>
		<li><a data-toggle="tab" href="#tab-transactions">Transactions</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab-settings">
			<?php echo $text_welcome; ?>
		        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-bluesnap" class="form-horizontal">
			  <div class="form-group required">
		            <label class="col-sm-2 control-label" for="input-mode"><?php echo $entry_mode; ?></label>
		            <div class="col-sm-10">
				<select name="bluesnap_mode" id="input-mode" class="form-control">
					<option value="production" <?php if ($bluesnap_mode == "production") { echo " selected='selected' "; } ?>><?php echo $text_mode_production;?></option>
					<option value="sandbox" <?php if ($bluesnap_mode == "sandbox") { echo " selected='selected' "; } ?>><?php echo $text_mode_sandbox;?></option>
				</select>
		            </div>
		          </div>
		  	  <div class="form-group required">
		            <label class="col-sm-2 control-label" for="input-username"><span data-toggle="tooltip" title="<?php echo $help_username; ?>"><?php echo $entry_username; ?></span></label>
		            <div class="col-sm-10">
		              <input type="text" name="bluesnap_username" value="<?php echo $bluesnap_username; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control" />
		              <?php if ($error_username) { ?>
		              <div class="text-danger"><?php echo $error_username; ?></div>
		              <?php } ?>
		            </div>
		          </div>
		  	  <div class="form-group required">
		            <label class="col-sm-2 control-label" for="input-password"><span data-toggle="tooltip" title="<?php echo $help_password; ?>"><?php echo $entry_password; ?></span></label>
		            <div class="col-sm-10">
		              <input type="text" name="bluesnap_password" value="<?php echo $bluesnap_password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
		              <?php if ($error_password) { ?>
		              	<div class="text-danger"><?php echo $error_password; ?></div>
		              <?php } ?>
		            </div>
		          </div>
		          <div class="form-group required">
		            <label class="col-sm-2 control-label" for="input-description_prefix"><span data-toggle="tooltip" title="<?php echo $help_description_prefix; ?>"><?php echo $entry_description_prefix; ?></span></label>
		            <div class="col-sm-10">
		              <input type="text" name="bluesnap_description_prefix" value="<?php echo $bluesnap_description_prefix; ?>" placeholder="<?php echo $entry_description_prefix; ?>" id="input-description_prefix" class="form-control" />
		              <?php if ($error_description_prefix) { ?>
		              	<div class="text-danger"><?php echo $error_description_prefix; ?></div>
		              <?php } ?>
		            </div>
		          </div>
			  <div class="form-group required">
		            <label class="col-sm-2 control-label" for="input-server_ip"><span data-toggle="tooltip" title="<?php echo $help_server_ip; ?>"><?php echo $entry_server_ip; ?></span></label>
		            <div class="col-sm-10">
				<input type="text" name="bluesnap_server_ip" value="<?php echo $bluesnap_server_ip; ?>" disabled="disabled" placeholder="<?php echo $entry_server_ip; ?>" id="input-server_ip" class="form-control" />
		            </div>
		          </div>
		   	  <div class="form-group required">
		            <label class="col-sm-2 control-label" for="input-debug_enabled"><?php echo $entry_debug_enabled; ?></label>
		            <div class="col-sm-10">
				<div class="col-sm-10">
		                    <label class="radio-inline">
		                      <?php if ($bluesnap_debug_enabled) { ?>
			                      <input type="radio" name="bluesnap_debug_enabled" value="1" checked="checked" />
		        	              <?php echo $text_yes; ?>
		                      <?php } else { ?>
			                      <input type="radio" name="bluesnap_debug_enabled" value="1" />
			                      <?php echo $text_yes; ?>
                		      <?php } ?>
		                    </label>
		                    <label class="radio-inline">
		                      <?php if (!$bluesnap_debug_enabled) { ?>
			                      <input type="radio" name="bluesnap_debug_enabled" value="0" checked="checked" />
			                      <?php echo $text_no; ?>
		                      <?php } else { ?>
			                      <input type="radio" name="bluesnap_debug_enabled" value="0" />
			                      <?php echo $text_no; ?>
		                      <?php } ?>
		                    </label>
				</div>
		            </div>
		          </div>
		          <div class="form-group">
		            <label class="col-sm-2 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
		            <div class="col-sm-10">
		              <input type="text" name="bluesnap_total" value="<?php echo $bluesnap_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
		            </div>
		          </div>
		          <div class="form-group">
		            <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
		            <div class="col-sm-10">
       			       <select name="bluesnap_order_status_id" id="input-order-status" class="form-control">
		                <?php foreach ($order_statuses as $order_status) { ?>
			                <?php if ($order_status['order_status_id'] == $bluesnap_order_status_id) { ?>
				                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
		        	        <?php } else { ?>
                				<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
			                <?php } ?>
		                <?php } ?>
		              </select>
		            </div>
		          </div>
		          <div class="form-group">
		            <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
		            <div class="col-sm-10">
		              <select name="bluesnap_geo_zone_id" id="input-geo-zone" class="form-control">
                		<option value="0"><?php echo $text_all_zones; ?></option>
		                <?php foreach ($geo_zones as $geo_zone) { ?>
			                <?php if ($geo_zone['geo_zone_id'] == $bluesnap_geo_zone_id) { ?>
			                	<option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
		                	<?php } else { ?>
       					         <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
			                <?php } ?>
		                <?php } ?>
        		      </select>
		            </div>
		          </div>
		          <div class="form-group">
		            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
		            <div class="col-sm-10">
		              <select name="bluesnap_status" id="input-status" class="form-control">
		                <?php if ($bluesnap_status) { ?>
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
		              <input type="text" name="bluesnap_sort_order" value="<?php echo $bluesnap_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
		            </div>
		          </div>
		        </form>
	      	</div>
		<div class="tab-pane" id="tab-transactions">
			<div class="well">
			  <div class="row">
				<div class="col-sm-4">
				  <div class="form-group">
					<label class="control-label" for="input-order-id"><?php echo $entry_order_id; ?></label>
					<input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-order-id" class="form-control" />
				  </div>
				  <div class="form-group">
					<label class="control-label" for="input-outcome"><?php echo $entry_outcome; ?></label>
					<select name="filter_result_code" id="input-result_code" class="form-control">
						<option value="" <?php echo (strlen($filter_result_code) == 0 ? "selected='selected'":'');?>></option>
						<option value="0" <?php echo (strlen($filter_result_code) > 0 && $filter_result_code == '0' ? " selected='selected' ":""); ?>>0 (<?php echo $text_result_code_success;?>)</option>
						<option value="1" <?php echo (strlen($filter_result_code) > 0 && $filter_result_code != '0' ? " selected='selected' ":""); ?>>1 (<?php echo $text_result_code_failure;?>)</option>
					</select>
				  </div>
				</div>
				<div class="col-sm-4">
				  <div class="form-group">
					<label class="control-label" for="input-total"><?php echo $entry_total; ?></label>
					<input type="text" name="filter_total" value="<?php echo $filter_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
				  </div>
				</div>
				<div class="col-sm-4">
				  <div class="form-group">
					<label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
					<div class="input-group date">
					  <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
					  <span class="input-group-btn">
					  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
					  </span></div>
				  </div>
				  <div class="form-group">
					<label class="control-label" for="input-remote_ip"><?php echo $entry_remote_ip; ?></label>
					<input type="text" name="filter_remote_ip" value="<?php echo $filter_remote_ip; ?>" placeholder="<?php echo $entry_remote_ip; ?>" id="input-remote_ip" class="form-control" />
				  </div>
				  <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
				</div>
			  </div>
			</div>
			<form method="post" enctype="multipart/form-data" id="form-order">
			  <div class="table-responsive">
				<table class="table table-bordered table-hover">
				  <thead>
					<tr>
					  <td class="text-right"><?php if ($sort == 'order_id') { ?>
						<a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_order_id; ?></a>
						<?php } else { ?>
						<a href="<?php echo $sort_order; ?>"><?php echo $column_order_id; ?></a>
						<?php } ?></td>
					  <td class="text-left"><?php if ($sort == 'result_code') { ?>
						<a href="<?php echo $sort_outcome; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_outcome; ?></a>
						<?php } else { ?>
						<a href="<?php echo $sort_outcome; ?>"><?php echo $column_outcome; ?></a>
						<?php } ?></td>
					  <td class="text-right"><?php if ($sort == 'amount') { ?>
						<a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total; ?></a>
						<?php } else { ?>
						<a href="<?php echo $sort_total; ?>"><?php echo $column_total; ?></a>
						<?php } ?></td>
					  <td class="text-left"><?php if ($sort == 'date_added') { ?>
						<a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
						<?php } else { ?>
						<a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
						<?php } ?></td>
					  <td class="text-left"><?php if ($sort == 'remote_ip') { ?>
						<a href="<?php echo $sort_remote_ip; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_remote_ip; ?></a>
						<?php } else { ?>
						<a href="<?php echo $sort_remote_ip; ?>"><?php echo $column_remote_ip; ?></a>
						<?php } ?></td>
					  <td class="text-right"><?php echo $column_action; ?></td>
					</tr>
				  </thead>
				  <tbody>
					<?php if ($entries) { ?>
					<?php foreach ($entries as $entry) { ?>
					<tr>
					  <td class="text-right"><?php echo $entry['order_id']; ?></td>
					  <td class="text-left"><?php echo $entry['status']; ?></td>
					  <td class="text-right"><?php echo $entry['total']; ?></td>
					  <td class="text-left"><?php echo $entry['date_added']; ?></td>
					  <td class="text-left"><?php echo $entry['remote_ip']; ?></td>
					  <td class="text-right"><a data-bluesnap_audit_id="<?php echo $entry['bluesnap_audit_id'];?>" data-audit_viewer_url="<?php echo $entry['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="show_audit_log btn btn-info"><i class="fa fa-eye"></i></a>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr>
					  <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
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

<div id="auditViewerModal" class="modal fade in" aria-hidden="true">
    <div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title" id="auditviewer-modal-title"></h4>
		</div>
		<div class="modal-body" id="auditviewer-modal-body">
		</div>
		<div class="modal-footer">
			<a href="#" class="btn btn-primary" data-dismiss="modal"><?php echo $button_modal_popup_close;?></a>
		</div>
	</div> 
   </div>
</div> 

                </div>
	</div>
    </div>
  </div>
</div>


<style>

.modal-body {
    max-height:500px;
    overflow:auto;
}
</style>

<script type="text/javascript"><!--
$(".show_audit_log").on("click", function(e) { 
	var bluesnap_audit_id= $(this).data("bluesnap_audit_id");
	$("#auditviewer-modal-title").html("<?php echo $modal_popup_title;?> " + bluesnap_audit_id);
	var html = "<table class='table table-hover'><thead><tr><th>Name</th><th>Value</th></tr></thead><tbody>";
	$.ajax({
               	type: 'POST',
                url: 'index.php?route=extension/payment/bluesnap/get_audit_entry&token=<?php echo $token;?>',
		async: false,
		data: { bluesnap_audit_id: bluesnap_audit_id },
                success: function(data) {
			if (data.result_code != 0) {
				alert(data.result_msg);
			} else {
				$.each(data.bluesnap_audit_record , function(key, value) {
					html += "<tr><td>" + key + "</td><td style='max-width:600px;overflow-x:auto;'><pre>"+ value +"</pre></td></tr>";
				});
			}
          	},
        });
	html += "</tbody></table>";
	$("#auditviewer-modal-body").html(html);
	$('#auditViewerModal').modal('show');
});

$('#button-filter').on('click', function() {
	url = 'index.php?route=extension/payment/bluesnap&token=<?php echo $token; ?>';
	
	var filter_order_id = $('input[name=\'filter_order_id\']').val();
	
	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}
	
	var filter_result_code = $('#input-result_code').val();
	
	if (filter_result_code) {
		url += '&filter_result_code=' + encodeURIComponent(filter_result_code);
	}

	var filter_total = $('input[name=\'filter_total\']').val();

	if (filter_total) {
		url += '&filter_total=' + encodeURIComponent(filter_total);
	}	
	
	var filter_date_added = $('input[name=\'filter_date_added\']').val();
	
	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}
	
	var filter_remote_ip = $('input[name=\'filter_remote_ip\']').val();
	
	if (filter_remote_ip) {
		url += '&filter_remote_ip=' + encodeURIComponent(filter_remote_ip);
	}
	location = url;
});
//--></script> 
  <script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script>

<script>
$("#verify_settings_button").on("click", function() {
	var mode = $("#input-mode").val();
	var username = $("#input-username").val();
	var password = $("#input-password").val();
	$("#verify_settings_button i")
		.removeClass("fa-play")
		.addClass("fa-circle-o-notch").addClass("fa-spin")
		.attr("disabled", "disabled")
	;
	$.ajax({
		type: 'POST',
        url: 'index.php?route=extension/payment/bluesnap/verify_settings&token=<?php echo $token;?>',
        sync: false,
        data : { mode : mode, username : username, password : password }, 
        success: function(data) {
			alert(data.result_msg);
		},
		complete: function() {
			$("#verify_settings_button i")
				.removeClass("fa-circle-o-notch").removeClass("fa-spin")
				.addClass("fa-play")
				.removeAttr("disabled")
			;
		}
	});
});
</script>

<?php echo $footer; ?> 

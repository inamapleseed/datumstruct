<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button id="save-button" data-toggle="tooltip" title="<?php echo $button_save_stay; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <div class="panel panel-default" id="main-panel" >
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-discounts" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_settings; ?></a></li>
            <li class="hidden"><a href="#tab-about" data-toggle="tab"><?php echo $tab_about; ?></a></li>
          </ul>
        	<div class="tab-content">
				<div class="tab-pane active" id="tab-general">
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
					<div class="col-sm-10">
					  <select name="discounts_status" id="input-status" class="setting form-control">
						<?php if ($discounts_status) { ?>
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
					<label class="col-sm-2 control-label" for="input-include-specials"><?php echo $entry_include_specials; ?></label>
					<div class="col-sm-10">
					  <select name="discounts_include_specials" id="input-include-specials" class="setting form-control">
						<?php if ($discounts_include_specials) { ?>
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
					<label class="col-sm-2 control-label" for="input-override-discount-price"><span data-toggle="tooltip" title="<?php echo $help_override_discount_price; ?>"><?php echo $entry_override_discount_price; ?></span></label>
					<div class="col-sm-10">
					  	<select name="discounts_override_discount_price" id="input-override-discount-price" class="setting form-control">
							<?php foreach ($options_discount as $option => $option_text) { ?>
								<?php if ($discounts_override_discount_price == $option) { ?>					
									<option value="<?php echo $option; ?>" selected="selected"><?php echo $option_text; ?></option>
								<?php } else { ?>
									<option value="<?php echo $option; ?>"><?php echo $option_text; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
					</div>
			  	</div>
			  	<div class="form-group">
					<label class="col-sm-2 control-label" for="input-override-special-price"><span data-toggle="tooltip" title="<?php echo $help_override_special_price; ?>"><?php echo $entry_override_special_price; ?></span></label>
					<div class="col-sm-10">
					  	<select name="discounts_override_special_price" id="input-override-special-price" class="setting form-control">
							<?php foreach ($options_special as $option => $option_text) { ?>
								<?php if ($discounts_override_special_price == $option) { ?>					
									<option value="<?php echo $option; ?>" selected="selected"><?php echo $option_text; ?></option>
								<?php } else { ?>
									<option value="<?php echo $option; ?>"><?php echo $option_text; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
					</div>
			  	</div>
				<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
				  <?php foreach ($discount_modules as $module) { ?>
	  
				  <?php $title = 'text_' . $module; 
						$status = $module . '_discount_status';
						$sort_order = $module . '_discount_sort_order';
						$override_special_price = $module . '_discount_override_special_price';
				  ?>
				  		<div class="panel panel-default">
					<div class="panel-heading" role="tab" id="heading">
					  <h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $module; ?>" aria-expanded="true" aria-controls="collapse<?php echo $module; ?>">
						  <?php echo $$title; ?> <?php echo $heading_title; ?>
						</a>
					  </h4>	
					</div>
					<div id="collapse<?php echo $module; ?>" class="panel-collapse collapse <?php echo($module=='category' ? 'in' : ''); ?>" role="tabpanel" aria-labelledby="heading<?php echo $module; ?>">
					  <div class="panel-body">
						  <div class="form-group">
								<label class="col-sm-2 control-label" for="input-status-<?php echo $module; ?>"><?php echo $$title; ?> <?php echo $heading_title; ?> <?php echo $entry_status; ?></label>
								<div class="col-sm-10">
								  <select name="<?php echo $status; ?>" id="input-status-<?php echo $module; ?>" class="<?php echo $module; ?> form-control">
									<?php if ($$status) { ?>
									<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
									<option value="0"><?php echo $text_disabled; ?></option>
									<?php } else { ?>
									<option value="1"><?php echo $text_enabled; ?></option>
									<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								  </select>
								</div>
							</div>
							<?php if ($module != 'customer') { ?>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-sort-order-<?php echo $module; ?>"><?php echo $entry_sort_order; ?></label>
								<div class="col-sm-10">
								  <input type="text" name="<?php echo $sort_order; ?>" value="<?php echo $$sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order-<?php echo $module; ?>" class="<?php echo $module; ?> form-control" />
								</div>
							</div>
							<?php } ?>
							 <div class="form-group">
								<label class="col-sm-2 control-label" for="edit-link-<?php echo $module; ?>"><?php echo $text_edit_discounts; ?></label>
								<div class="col-sm-10">
								  <a href="<?php echo str_replace('%s',$module,$link); ?>" id="edit-link-<?php echo $module; ?>" class="btn btn-primary" title="<?php echo $text_edit_discounts; ?>"><i class="fa fa-edit fa-2x"></i></a>
								</div>
						  	</div>
						  </div>						 
					</div>
					</div>			  
				  <?php } ?>
				</div>        	
				<?php if ($upgrade) { ?>
					<a id="upgrade" class="btn btn-warning pull-right"><i class="fa fa-database"></i> <?php echo $text_upgrade;?></a>
				<?php } ?>
				</div>
				</form>
				<div class="tab-pane" id="tab-about">
					<div class="panel panel-body" class=""><h1>Discounts Pack</h1>
					<p>This extension bundles several discount modules: Category Discount, Customer Group Discount, Manufacturer Discount, Ordertotal Discount and global Volume/Tier Discount. Additionally, an extra module is included where you can edit Product Discounts.</p>
					</div>
					<hr />
					<div class="form-group">
						<label class="col-sm-2 control-label" for="copyright">Copyright</label>
						<div class="col-sm-10" id="copyright">
						  <p style="padding-top:9px;">Jorim van Hove &copy; 2015 <?php if (date('Y') != '2015') echo " - " . date('Y'); ?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="version">Version</label>
						<div class="col-sm-10" id="version">
						 <p style="padding-top:9px;"> v1.3.8.2 <a class="" href="http://www.opencart.com/index.php?route=extension/extension/info&extension_id=20914" target="_blank">Check for newer version</a></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="version">Documentation</label>
						<div class="col-sm-10" id="version">
						 <p><a class="btn btn-success" href="http://jorimvanhove.com/plugins/discounts-pack" target="_blank">Link</a> (opens in new window)</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="support">Support</label>
						<div class="col-sm-10" id="support">
						 <p style="padding-top:9px;">Email the extension order ID, admin access details and FTP login details, together with a detailed description of the issue and you will receive an answer within 24h: <a href="mailto:support@jorimvanhove.com" class="">support@jorimvanhove.com</a></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="other">Other extensions</label>
						<div class="col-sm-10" id="other">
							<div class="row">
								<div class="col-md-3 col-sm-6" >
						 			<a href="http://www.opencart.com/index.php?route=extension/extension/info&extension_id=18442" target="_blank" class="thumbnail"><img src="http://jorimvanhove.com/plugins/opencart/img/OC_FBCP_Tile.png" title="Facebook Conversion Pixels" alt="Facebook Conversion Pixels"></a>
						 		</div>
						 		<div class="col-md-3 col-sm-6">
									<a href="http://www.opencart.com/index.php?route=extension/extension/info&extension_id=19545" target="_blank" class="thumbnail" ><img src="http://jorimvanhove.com/plugins/opencart/img/OC_FSP_Tile.png" title="Free Shipping Pro" alt="Free Shipping Pro"></a>
								</div>
								<div class="col-md-3 col-sm-6">
									<a href="http://www.opencart.com/index.php?route=extension/extension/info&extension_id=20575" target="_blank" class="thumbnail"><img src="http://jorimvanhove.com/plugins/opencart/img/OC_EUVAT_Tile.png" title="EU VAT Number Validation" alt="EU VAT Number Validation"></a>
								</div>
								<div class="col-md-3 col-sm-6">
									<a href="http://www.opencart.com/index.php?route=extension/extension/info&extension_id=20301" target="_blank" class="thumbnail"><img src="http://jorimvanhove.com/plugins/opencart/img/OC_IOL_Tile_OC2.png" title="Improved Order List" alt="Improved Order List"></a>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3 col-sm-6" >
									<a href="http://www.opencart.com/index.php?route=extension/extension/info&extension_id=20705" target="_blank" class="thumbnail"><img src="http://jorimvanhove.com/plugins/opencart/img/OC_TCT_Tile.png" title="Twitter Conversion Tracking" alt="Twitter Conversion Tracking"></a>
								</div>
								<div class="col-md-3 col-sm-6" >
									<a href="http://www.opencart.com/index.php?route=extension/extension/info&extension_id=20726" target="_blank" class="thumbnail"><img src="http://jorimvanhove.com/plugins/opencart/img/OC_OIE_Tile.png" title="Admin Order Info Edit" alt="Admin Order Info Edit"></a>
								</div>
							</div>	
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="all">All extensions by Jorim van Hove</label>
						<div class="col-sm-10" id="all">
						  <a href="http://www.opencart.com/index.php?route=extension/extension&filter_username=Jorim%20van%20Hove" target="_blank" class="btn btn-default">Opencart.com</a>
						</div>
					</div>
				  <tr>
					<td></td>
					<td></td>
				  </tr>
				</div>
        	</div>
        
        <p class="hidden">Discounts Pack v1.3.8.2 &copy; 2015 <a href="http://jorimvanhove.com/" target="_blank">Jorim van Hove</a> - <a href="http://jorimvanhove.com/plugins/discounts-pack" target="_blank">Online Documentation</a></p>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
<?php if ($permission) { ?>
	$('#save-button').click(function(){
		
		$.ajax({
			url:'index.php?route=extension/module/discounts/saveSettings&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			data: {
				settings : $('.setting').serialize(),
				
				<?php foreach ($discount_modules as $module) { ?>
					<?php echo $module; ?> : $(".<?php echo $module; ?>").serialize(),
				<?php } ?>
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
	
	$('#upgrade').click(function(){
		$.ajax({
			url:'index.php?route=extension/module/discounts/upgrade&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			success: function(json) {
				alertJson('alert alert-success', json);
			 	$('#upgrade').remove();
			}
		});
		return false;
	});
	
	<?php } else { ?>
		$('#save-button').click(function(){
			$('.alert').remove();
			
			$("#main-panel").before('<div class="alert alert-warning"><?php echo $error_permission; ?></div>');
		});
	<?php } ?>

	function alertJson(action, json) {
		
		$('.alert').remove();
		
		if (json['success']) {
			$("#main-panel").before('<div class="' + action + '">' + json['success'] + '</div>');
		} else if (json['error']) {
			$("#main-panel").before('<div class="' + action + '">' + json['error'] + '</div>');
		}
		
	}
//--></script>
<?php echo $footer; ?>
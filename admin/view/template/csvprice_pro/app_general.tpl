<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
		<div class="container-fluid">
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
					<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
    </div>
    <div class="container-fluid csvprice_pro_container">
		<?php if (isset($warning) && !empty($warning)) { ?>
			<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $warning; ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
		<?php } ?>
        <?php if (isset($success) && !empty($success)) { ?>
			<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
        <?php } ?>
		<?php echo $app_header; ?>
		<div class="panel panel-default">
			<div class="panel-body">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab_setting" data-toggle="tab" id="link_tab_setting"><?php echo $tab_setting; ?></a></li>
					<li><a href="#tab_tool_image" data-toggle="tab" id="link_tab_tool_image"><?php echo $tab_tool_image; ?></a></li>
					<li><a href="#tab_tool_backup" data-toggle="tab" id="link_tab_tool_backup"><?php echo $tab_tool_backup; ?></a></li>
				</ul>
				<div class="tab-content">
					<div id="tab_setting" class="tab-pane active">
						<div class="row">
							<div class="col-sm-8 col-md-offset-1">
								<form action="<?php echo $action; ?>" method="post" id="form_general" enctype="multipart/form-data" class="form-horizontal">
									<div class="form-group form-group-sm">
										<label class="col-sm-5 control-label"><?php echo $entry_csv_import_mod; ?></label>
										<div class="col-sm-7">
											<select name="csvprice_pro_csv_import_mod" class="form-control input-sm">
												<?php if ($csvprice_pro_csv_import_mod == 1) { ?>
												<option value="1" selected="selected"><?php echo $text_manual; ?></option>
												<option value="2"><?php echo $text_auto; ?></option>
												<?php } else { ?>
												<option value="1"><?php echo $text_manual; ?></option>
												<option value="2" selected="selected"><?php echo $text_auto; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group form-group-sm">
										<label class="col-sm-5 control-label" data-prop_id="1"><?php echo $entry_image_download_mod; ?></label>
										<div class="col-sm-7">
											<select name="csvprice_pro_image_download_mod" class="form-control input-sm">
												<?php if ($csvprice_pro_image_download_mod == 2) { ?>
												<option value="1"><?php echo $text_auto; ?></option>
												<option value="2" selected="selected"><?php echo $text_mirror; ?></option>
												<?php } else { ?>
												<option value="1" selected="selected"><?php echo $text_auto; ?></option>
												<option value="2"><?php echo $text_mirror; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group form-group-sm">
										<label class="col-sm-5 control-label" data-prop_id="2"><?php echo $entry_save_img_table; ?></label>
										<div class="col-sm-7">
											<select name="csvprice_pro_save_image_table" class="form-control input-sm">
												<?php if (isset($csvprice_pro_save_image_table) && $csvprice_pro_save_image_table == 0) { ?>
												<option value="1"> <?php echo $text_yes; ?> </option>
												<option value="0" selected="selected"> <?php echo $text_no; ?> </option>
												<?php } else { ?>
												<option value="1" selected="selected"> <?php echo $text_yes; ?> </option>
												<option value="0"> <?php echo $text_no; ?> </option>
												<?php } ?>
											</select>
											<div class="control-label pull-left"><button type="button" class="btn btn-warning btn-sm" onclick="confirm('<?php echo $text_confirm; ?>') ? location.href = '<?php echo $clear;?>' : false;"><i class="fa fa-eraser"></i> <?php echo $button_delete_image_cache; ?></button></div>
										</div>
									</div>
									<div class="form-group form-group-sm">
										<label class="col-sm-5 control-label" data-prop_id="3"><?php echo $entry_work_directory; ?></label>
										<div class="col-sm-7">
											<input name="csvprice_pro_work_directory"  class="form-control input-sm" value="<?php echo $csvprice_pro_work_directory; ?>">
										</div>
									</div>
									<div class="form-group form-group-sm">
										<label class="col-sm-5 control-label"><?php echo $entry_product_log; ?></label>
										<div class="col-sm-7">
											<select name="csvprice_pro_product_log" class="form-control input-sm">
												<?php if (!isset($csvprice_pro_product_log) || (isset($csvprice_pro_product_log) && $csvprice_pro_product_log == 0)) { ?>
												<option value="1"> <?php echo $text_yes; ?> </option>
												<option value="0" selected="selected"> <?php echo $text_no; ?> </option>
												<?php } else { ?>
												<option value="1" selected="selected"> <?php echo $text_yes; ?> </option>
												<option value="0"> <?php echo $text_no; ?> </option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group form-group-sm">
										<label class="col-sm-5 control-label">&nbsp;</label>
										<div class="col-sm-7">
											<div class="pull-right">
												<button type="button" class="btn btn-primary" style="min-width:120px" onclick="$('#form_general').submit();"><?php echo $button_save; ?></button>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div id="tab_tool_image" class="tab-pane">
						<div class="row">
							<div class="col-sm-8 col-md-offset-1">
								<form class="form-horizontal form-control-sm">
									<div class="form-group form-group-sm">
										<label class="col-sm-5 control-label"><?php echo $entry_clear_image_cache; ?></label>
										<div class="col-sm-7">
											<a onclick="confirm('<?php echo $text_confirm; ?>') ? location.href = '<?php echo $cache;?>' : false;" class="btn btn-warning btn-sm"><i class="fa fa-eraser"></i> <?php echo $button_clear; ?></a>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div id="tab_tool_backup" class="tab-pane">
						<div class="row">
							<div class="col-sm-8 col-md-offset-1">
								<form action="<?php echo $backup; ?>" method="post" name="form_general_backup" id="form_general_backup" enctype="multipart/form-data" class="form-horizontal form-control-sm">
									<div class="form-group form-group-sm">
										<label class="col-sm-5 control-label"><?php echo $entry_backup_data; ?></label>
										<div class="col-sm-7">
											<select name="csv_backup_data" class="form-control input-sm">
												<option value="1"<?php if(!isset($csv_backup_data) || $csv_backup_data == 1) { ?> selected="selected"<?php } ?>><?php echo $text_product_backup; ?></option>
												<option value="2"<?php if(isset($csv_backup_data) && $csv_backup_data == 2) { ?> selected="selected"<?php } ?>><?php echo $text_category_backup; ?></option>
												<option value="3"<?php if(isset($csv_backup_data) && $csv_backup_data == 3) { ?> selected="selected"<?php } ?>><?php echo $text_manufacturer_backup; ?></option>
												<option value="4"<?php if(isset($csv_backup_data) && $csv_backup_data == 4) { ?> selected="selected"<?php } ?>><?php echo $text_product_all_backup; ?></option>
												<option value="5"<?php if(isset($csv_backup_data) && $csv_backup_data == 5) { ?> selected="selected"<?php } ?>><?php echo $text_full_backup; ?></option>
											</select>
										</div>
									</div>
									<div class="form-group form-group-sm">
										<label class="col-sm-5 control-label"><?php echo $entry_backup_type; ?></label>
										<div class="col-sm-7">
											<select name="csv_backup_type" class="form-control input-sm">
												<option value="oc"<?php if(!isset($csv_backup_type) || $csv_backup_type == 'oc') { ?> selected="selected"<?php } ?>><?php echo $text_opencart_backup; ?></option>
												<option value="raw"<?php if(isset($csv_backup_type) && $csv_backup_type == 'raw') { ?> selected="selected"<?php } ?>><?php echo $text_raw_backup; ?></option>
											</select>
										</div>
									</div>
									<div class="form-group form-group-sm">
										<label class="col-sm-5 control-label"><?php echo $entry_backup_zip; ?></label>
										<div class="col-sm-7">
											<select name="csv_backup_zip" class="form-control input-sm">
												<option value="on"<?php if(isset($csv_backup_zip) && $csv_backup_zip == 'on') { ?> selected="selected"<?php } ?>><?php echo $text_enabled; ?></option>
												<option value="off"<?php if(!isset($csv_backup_zip) || $csv_backup_zip == 'off') { ?> selected="selected"<?php } ?>><?php echo $text_disabled; ?></option>
											</select>
										</div>
									</div>
									<div class="form-group form-group-sm">
										<label class="col-sm-5 control-label">&nbsp;</label>
										<div class="col-sm-7">
											<button type="submit" form="form_general_backup" class="btn btn-default"><i class="fa fa-download"></i> <?php echo $button_export; ?></button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
<script type="text/javascript">
	jQuery(document).ready(function(e) {
		$('.csvprice_pro_container .nav-tabs li.active').removeClass('active');
		$('.csvprice_pro_container .tab-content div.active').removeClass('active');
		$("#link_<?php echo $tab_selected; ?>").parent().addClass('active');
		$("#<?php echo $tab_selected; ?>").removeClass('fade').addClass('active');
	});
	var prop_descr = new Array();
    <?php if(isset($prop_descr)) echo $prop_descr; ?>
</script>
    </div>
<?php echo $app_footer; ?>
</div>
<?php echo $footer; ?>


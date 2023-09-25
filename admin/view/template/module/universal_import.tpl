<?php function imageRepeat($id, $_language, $val) { $id.= '_repeat'; ?>
  <select name="univimport_theme[<?php echo $id; ?>]" class="form-control changeReload">
    <option value="" <?php if($val[$id] == '') echo 'selected="selected"'; ?>><?php echo $_language->get('text_repeat'); ?></option>
    <option value="repeat-x" <?php if($val[$id] == 'repeat-x') echo 'selected="selected"'; ?>><?php echo $_language->get('text_repeat-x'); ?></option>
    <option value="repeat-y" <?php if($val[$id] == 'repeat-y') echo 'selected="selected"'; ?>><?php echo $_language->get('text_repeat-y'); ?></option>
    <option value="no-repeat" <?php if($val[$id] == 'no-repeat') echo 'selected="selected"'; ?>><?php echo $_language->get('text_no-repeat'); ?></option>
    <option value="top center no-repeat" <?php if($val[$id] == 'top center no-repeat') echo 'selected="selected"'; ?>><?php echo $_language->get('text_no-repeat_center'); ?></option>
  </select>
<?php } ?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<?php if(!empty($style_v15)) { ?><style scoped><?php echo $style_v15; ?></style><?php } ?>
<input type="hidden" name="no-image" value="0" />
<div id="modal-alert" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby=""><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title"></h4> </div><div class="modal-body"></div></div></div></div>
<div id="modal-info" class="modal <?php if (version_compare(VERSION, '2', '>=')) echo ' fade'; ?>" tabindex="-1" role="dialog" aria-hidden="true"></div>
<div class="modal fade" id="modal-profile" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $_language->get('text_save_profile'); ?></h4>
      </div>
      <div class="modal-body form-horizontal">
        <form id="profile-form">
          <div class="alert alert-success" style="display:none"><i class="fa fa-check-circle"></i> <span></span></div>
          <div class="alert alert-warning" style="display:none"><i class="fa fa-exclamation-circle"></i> <span></span></div>
          <p><?php echo $_language->get('text_save_profile_i'); ?></p>
          <div class="form-group">
            <div class="col-sm-12">
              <select class="form-control" name="save_profile">
                <option value=""><?php echo $_language->get('text_new_profile'); ?></option>
                <?php foreach ($profiles as $item) { ?>
                  <option value="<?php echo $item['name']; ?>" class="type-hide show-<?php echo $item['type']; ?>"><?php echo $item['name']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-12">
              <input name="profile_name" class="form-control" placeholder="<?php echo $_language->get('text_profile_name'); ?>"/>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $_language->get('text_close'); ?></button>
        <button type="button" class="btn btn-primary" id="saveProfile"><?php echo $_language->get('button_save'); ?></button>
      </div>
    </div>
  </div>
</div>

			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
					<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>

  <div class="<?php if(version_compare(VERSION, '2', '>=')) echo 'container-fluid'; ?>">
	<?php if (isset($success) && $success) { ?><div class="alert alert-success success"><i class="fa fa-check-circle"></i> <?php echo $success; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div><script type="text/javascript">setTimeout("$('.alert-success').slideUp();",5000);</script><?php } ?>
	<?php if (isset($info) && $info) { ?><div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $info; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div><?php } ?>
	<?php if (isset($error) && $error) { ?><div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div><?php } ?>
    <?php if (isset($warning) && $warning) { ?><div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $warning; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div><?php } ?>
<div class="panel panel-default">
	<div class="panel-heading module-heading">
    <div class="pull-right saveBtns" style="display:none">
      <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $button_save; ?></button>
      <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?></a>
    </div>
		<h3 class="panel-title"><img src="<?php echo $_img_path; ?>icon_big.png" alt="" style="vertical-align:top;"/> <?php echo $heading_title; ?></h3>
	</div>
	<div class="content panel-body">
  <!--<div id="stores" class="form-inline" <?php if (version_compare(VERSION, '2', '>=') && 0) echo 'class="v2"'; ?>>
		<?php echo $_language->get('text_store_select'); ?>
		<select name="store" class="form-control input-sm">
			<?php foreach ($stores as $store) { ?>
			<?php if ($store_id == $store['store_id']) { ?>
			<option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
			<?php } else { ?>
			<option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
			<?php } ?>
			<?php } ?>
		</select>
	</div>-->
		<ul class="nav nav-tabs mainMenu">
    	<li class="active"><a href="#tab-0" data-toggle="tab"><i class="fa fa-download"></i><?php echo $_language->get('text_tab_0'); ?></a></li>
			<li><a href="#tab-1" data-toggle="tab"><i class="fa fa-upload"></i><?php echo $_language->get('text_tab_1'); ?></a></li>
			<li><a href="#tab-2" data-toggle="tab"><i class="fa fa-gear"></i><?php echo $_language->get('text_tab_2'); ?></a></li>
			<!--<li><a href="#tab-3" data-toggle="tab"><i class="fa fa-pencil-square-o"></i><?php echo $_language->get('text_tab_3'); ?></a></li>-->
      <li><a href="#tab-cron" data-toggle="pill"><i class="fa fa-terminal"></i> <?php echo $_language->get('tab_option_2'); ?></a></li>
			<li><a href="#tab-about" data-toggle="tab"><i class="fa fa-info"></i><?php echo $_language->get('text_tab_about'); ?></a></li>
		</ul>
    
		<div class="tab-content container-fluid">
      <div class="tab-pane active" id="tab-0">
        <form id="import-form" class="form-horizontal">
          <div id="importAccordion" role="tablist" aria-multiselectable="true">
            <div class="panel-heading" role="tab" data-toggle="collapse" data-parent="#importAccordion" href="#importStep1" aria-expanded="true">
              <h4 class="panel-title"><?php echo $_language->get('text_import_step1'); ?></h4>
            </div>
            <div id="importStep1" class="collapse in clearfix" role="tabpanel">
              <div class="spacer"></div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $_language->get('entry_type_i'); ?>"><?php echo $_language->get('entry_type'); ?></span></label>
                <div class="col-sm-4">
                  <select name="import_type" class="form-control big-select">
                   <?php $currentType = '';
                      foreach ($import_types as $import_type) {
                        if ($currentType == (strstr($import_type, '_', true) ? strstr($import_type, '_', true) : $import_type)) { ?>
                          <option value="<?php echo $import_type; ?>"><?php echo $_language->get('text_type_'.$import_type); ?></option>
                          <?php } else { ?>
                            <option value="<?php echo $import_type; ?>"><?php echo $_language->get('text_type_'.$import_type); ?></option>
                          <?php }
                          $currentType = strstr($import_type, '_', true) ? strstr($import_type, '_', true) : $import_type;
                        }
                    ?>
                   <?php /*
                    <?php $currentType = '';
                      foreach ($import_types as $import_type) {
                        if ($currentType == (strstr($import_type, '_', true) ? strstr($import_type, '_', true) : $import_type)) { ?>
                          <option value="<?php echo $import_type; ?>"><?php echo $_language->get('text_type_'.$import_type); ?></option>
                          <?php } else { ?>
                          <?php if ($currentType) { ?></optgroup><?php } ?>
                          <optgroup label="<?php echo $_language->get('text_type_'.$import_type); ?>">
                            <option value="<?php echo $import_type; ?>"><?php echo $_language->get('text_type_'.$import_type); ?></option>
                          <?php }
                          $currentType = strstr($import_type, '_', true) ? strstr($import_type, '_', true) : $import_type;
                        }
                    ?>
                    </optgroup>
                    */ ?>
                  </select>
                </div>
                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $_language->get('entry_profile_i'); ?>"><?php echo $_language->get('entry_profile'); ?></span></label>
                <div class="col-sm-4" style="padding-right:57px">
                  <button type="button" data-toggle="tooltip" class="btn btn-danger deleteProfile" style="position:absolute; right:15px;" title="<?php echo $_language->get('text_delete_profile'); ?> "disabled="disabled"><i class="fa fa-minus-circle"></i></button>
                  <select class="form-control" name="profile">
                    <option value=""><?php echo $_language->get('text_select_profile'); ?></option>
                    <?php foreach ($profiles as $item) { ?>
                      <option value="<?php echo $item['name']; ?>" class="type-hide show-<?php echo $item['type']; ?>"><?php echo $item['name']; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              
              <hr class="dotted"/>
              
              <div class="form-group">
                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $_language->get('entry_file_i'); ?>"><?php echo $_language->get('entry_file'); ?></span></label>
                <div class="col-sm-7">
                  <select name="import_source" class="form-control">
                    <?php foreach (array('upload', 'url', 'ftp', 'path') as $file_source) { ?>
                    <option value="<?php echo $file_source; ?>"><?php echo $_language->get('text_source_'.$file_source); ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-sm-3">
                  <select name="import_extension" class="form-control">
                    <option value=""><?php echo $_language->get('text_extension_auto'); ?></option>
                    <?php foreach ($import_extensions as $file_extension) { ?>
                    <option value="<?php echo $file_extension; ?>"><?php echo strtoupper($file_extension); ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <?php if ($demo_mode) { ?>
              <div class="alert alert-warning col-md-offset-2"><i class="fa fa-exclamation-circle"></i> Demo mode: only file upload or demo files are allowed</div>
              <?php } ?>
              <div id="alertFileUpload" class="alert alert-danger col-md-offset-2" style="display:none"><i class="fa fa-exclamation-circle"></i> <span></span></div>
              <div id="importSource"></div>
              
              <?php if ($demo_mode) { ?>
              <div class="form-group" style="background:#c0d7d7; padding:10px 0;">
                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $_language->get('entry_demo_file_i'); ?>"><?php echo $_language->get('entry_demo_file'); ?></span></label>
                <div class="col-sm-10">
                  <select name="demo_file" class="form-control big-select">
                    <option></option>
                    <?php foreach (array('products','categories','informations','manufacturers','customers') as $demo_type) { ?>
                    <option value="<?php echo $demo_type.'.csv'; ?>"><?php echo $demo_type.'.csv'; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <?php } ?>
              
              <hr class="dotted"/>
              
              <div class="pull-right">
                <button type="button" class="btn btn-success submit" disabled="disabled" data-step="1"><i class="fa fa-check"></i> <?php echo $_language->get('text_next_step'); ?></button>
              </div>
              <div class="spacer"></div>
            </div>
            
            <div class="panel-heading" role="tab" data-toggle="collapse" data-parent="#importAccordion" href="#importStep2">
              <h4 class="panel-title"><?php echo $_language->get('text_import_step2'); ?></h4>
            </div>
            <div id="importStep2" class="collapse" role="tabpanel"></div>
            
            <div class="panel-heading" role="tab" data-toggle="collapse" data-parent="#importAccordion" href="#importStep3">
              <h4 class="panel-title"><?php echo $_language->get('text_import_step3'); ?></h4>
            </div>
            <div id="importStep3" class="collapse" role="tabpanel"></div>
            
            <div class="panel-heading" role="tab" data-toggle="collapse" data-parent="#importAccordion" href="#importStep4">
              <h4 class="panel-title"><?php echo $_language->get('text_import_step4'); ?></h4>
            </div>
            <div id="importStep4" class="collapse" role="tabpanel"></div>
            
            <div class="panel-heading" role="tab" data-toggle="collapse" data-parent="#importAccordion" href="#importStep5">
              <h4 class="panel-title"><?php echo $_language->get('text_import_step5'); ?></h4>
            </div>
            <div id="importStep5" class="collapse" role="tabpanel"></div>
            
          </div>
          </form>
        </div>
        <div class="tab-pane clearfix" id="tab-1">
          <form id="export-form">
            <div class="form-group form-horizontal well well-blue clearfix">
              <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $_language->get('entry_export_type_i'); ?>"><?php echo $_language->get('entry_export_type'); ?></span></label>
              <div class="col-sm-4">
                <select name="export_type" class="form-control big-select">
                  <?php $i=1; foreach ($export_types as $export_type) { ?>
                  <option value="<?php echo $export_type; ?>"><?php echo $_language->get('text_type_'.$export_type); ?></option>
                  <?php } ?>
                </select>
              </div>
              <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $_language->get('entry_export_format_i'); ?>"><?php echo $_language->get('entry_export_format'); ?></span></label>
              <div class="col-sm-4">
                <select name="export_format" class="form-control big-select">
                  <?php foreach ($export_extensions as $file_format) { ?>
                  <option value="<?php echo $file_format; ?>"><?php echo $_language->get('text_format_'.$file_format); ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            
            <hr class="dotted"/>
            
            <div id="exportContainer"></div>
            
            <div>
              <button type="button" class="startExportProcess btn btn-success btn-lg btn-block"><i class="fa fa-play"></i> <?php echo $_language->get('text_start_export'); ?></button>
              <button type="button" class="pauseExportProcess btn btn-default btn-lg btn-block" style="display:none"><i class="fa fa-gear fa-spin"></i> <?php echo $_language->get('text_pause_export'); ?></button>
            </div>

            <div class="spacer"></div>
            
            <div id="exportProgress" class="progress" style="display:none">
              <div class="progress-bar progress-bar-striped progress-bar-success active"></div>
            </div>
            
          </form>
        </div>
    <div class="tab-pane" id="tab-2">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
        <ul class="nav nav-pills nav-stacked col-md-2">
          <li class="active"><a href="#tab-option-3" data-toggle="pill"><i class="fa fa-cog"></i> <?php echo $_language->get('tab_option_3'); ?></a></li>
          <li><a href="#tab-option-1" data-toggle="pill"><i class="fa fa-bolt"></i> <?php echo $_language->get('tab_option_1'); ?></a></li>
          <!--li><a href="#tab-option-2" data-toggle="pill"><i class="fa fa-terminal"></i> <?php echo $_language->get('tab_option_2'); ?></a></li-->
        </ul>
        <div class="tab-content col-md-10">
          <div class="tab-pane" id="tab-option-1">
            <div class="well">
              <?php echo $_language->get('batch_import_i'); ?>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $_language->get('entry_batch_import'); ?></label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="<?php echo $prefix; ?>batch_imp" value="<?php echo ${$prefix.'batch_imp'}; ?>" placeholder="200"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $_language->get('entry_batch_export'); ?></label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="<?php echo $prefix; ?>batch_exp" value="<?php echo ${$prefix.'batch_exp'}; ?>" placeholder="200"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $_language->get('entry_sleep'); ?></label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="<?php echo $prefix; ?>sleep" value="<?php echo ${$prefix.'sleep'}; ?>" placeholder="200"/>
              </div>
            </div>
            <div class="spacer"></div>
          </div>
          <div class="tab-pane active" id="tab-option-3">
            <div class="well">
              <h4><?php echo $_language->get('tab_option_3'); ?></h4>
              <p><?php echo $_language->get('default_label_i'); ?></p>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $_language->get('entry_default_import_label'); ?></label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="<?php echo $prefix; ?>default_label" value="<?php echo ${$prefix.'default_label'}; ?>" placeholder="[profile]-[year]-[month]-[day]"/>
              </div>
            </div>
            <div class="spacer"></div>
          </div>
        </div>
      </form>
		</div>
    <div class="tab-pane" id="tab-cron">
      <form id="formCron">
      <div class="well">
        <h4><?php echo $_language->get('tab_option_2'); ?></h4>
        <p><?php echo $_language->get('cron_jobs_i'); ?></p>
      </div>
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-cron-2" data-toggle="pill"><i class="fa fa-download"></i> <?php echo $_language->get('text_tab_cron_import'); ?></a></li>
        <li><a href="#tab-cron-3" data-toggle="pill"><i class="fa fa-upload"></i> <?php echo $_language->get('text_tab_cron_export'); ?></a></li>
        <li><a href="#tab-cron-1" data-toggle="pill"><i class="fa fa-cogs"></i> <?php echo $_language->get('text_tab_cron_config'); ?></a></li>
        <li><a href="#tab-cron-4" data-toggle="pill"><i class="fa fa-file-text-o"></i> <?php echo $_language->get('text_tab_cron_report'); ?></a></li>
      </ul>
      <div class="tab-content form-horizontal">
        <div class="tab-pane" id="tab-cron-1">
          <div class="form-group">
            <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $_language->get('entry_cron_key_i'); ?>"><?php echo $_language->get('entry_cron_key'); ?></span></label>
            <div class="col-sm-10">
              <input class="form-control" type="text" name="<?php echo $prefix; ?>cron_key" value="<?php echo (!empty(${$prefix.'cron_key'})) ? ${$prefix.'cron_key'} : 'cron_secure_key'; ?>"/>
            </div>
          </div>
        </div>
        <div class="tab-pane active" id="tab-cron-2">
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $_language->get('entry_type'); ?></label>
            <div class="col-sm-4">
              <select class="importCronTypeSelect form-control big-select">
                <?php foreach ($import_types as $import_type) { ?>
                <option value="<?php echo $import_type; ?>"><?php echo $_language->get('text_type_'.$import_type); ?></option>
                <?php } ?>
              </select>
            </div>
            <label class="col-sm-2 control-label"><?php echo $_language->get('entry_profile'); ?></label>
            <div class="col-sm-4" style="padding-right:57px">
              <select class="importCronProfileSelect form-control">
                <option value=""><?php echo $_language->get('text_select_profile'); ?></option>
                <?php foreach ($profiles as $item) { ?>
                  <option value="<?php echo $item['name']; ?>" class="importCronTypeHide importCronTypeShow-<?php echo $item['type']; ?>"><?php echo $item['name']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $_language->get('entry_cron_command_i'); ?>"><?php echo $_language->get('entry_cron_command'); ?></span></label>
            <div class="col-sm-10">
              <div class="gkdCode gkdCodeBig">/[path_to_php]/php <?php echo !$demo_mode ? str_replace('system/', '', DIR_SYSTEM) : ''; ?>univ_import_cron.php k=<b><?php echo (!empty(${$prefix.'cron_key'})) ? ${$prefix.'cron_key'} : 'cron_secure_key'; ?></b> type=<b class="importCronType">product</b> profile=<b class="importCronProfile">Profile_Name</b></div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $_language->get('entry_cron_params_i'); ?>"><?php echo $_language->get('entry_cron_params'); ?></span></label>
            <div class="col-sm-10">
              <?php echo $_language->get('text_cron_label'); ?>
              <div class="gkdCode">label=<b>cron_import</b></div>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="tab-cron-3">
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $_language->get('entry_export_type'); ?></label>
            <div class="col-sm-4">
              <select class="exportCronTypeSelect form-control big-select">
                <?php foreach ($export_types as $export_type) { ?>
                <option value="<?php echo $export_type; ?>"><?php echo $_language->get('text_type_'.$export_type); ?></option>
                <?php } ?>
              </select>
            </div>
            <?php /*
            <label class="col-sm-2 control-label"><?php echo $_language->get('entry_profile'); ?></label>
            <div class="col-sm-4" style="padding-right:57px">
              <select class="exportCronProfileSelect form-control">
                <option value=""><?php echo $_language->get('text_select_profile'); ?></option>
                <?php foreach ($profiles as $item) { ?>
                  <option value="<?php echo $item['name']; ?>" class="importCronTypeHide importCronTypeShow-<?php echo $item['type']; ?>"><?php echo $item['name']; ?></option>
                <?php } ?>
              </select>
            </div>
            */ ?>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $_language->get('entry_cron_command_i'); ?>"><?php echo $_language->get('entry_cron_command'); ?></span></label>
            <div class="col-sm-10">
              <div class="gkdCode gkdCodeBig">/[path_to_php]/php <?php echo !$demo_mode ? str_replace('system/', '', DIR_SYSTEM) : ''; ?>univ_import_cron.php k=<b><?php echo (!empty(${$prefix.'cron_key'})) ? ${$prefix.'cron_key'} : 'cron_secure_key'; ?></b> export=<b>1</b> type=<b class="exportCronType">product</b></div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $_language->get('entry_cron_params_i'); ?>"><?php echo $_language->get('entry_cron_params'); ?></span></label>
            <div class="col-sm-10">
              <?php echo $_language->get('text_cron_export_email'); ?>
              <div class="gkdCode">email=<b>mail@yourwebsite.com</b></div>
              <?php echo $_language->get('entry_export_format'); ?>
              <div class="gkdCode">export_format=<b>xml</b></div>
              <?php echo $_language->get('filter_language'); ?>
              <div class="gkdCode">filter_language=<b>XXX</b> (ID)</div>
              <?php echo $_language->get('filter_store'); ?>
              <div class="gkdCode">filter_store=<b>XXX</b> (ID)</div>
              <?php echo $_language->get('filter_category'); ?>
              <div class="gkdCode">filter_category[]=<b>XXX</b> (ID)</div>
              <?php echo $_language->get('filter_manufacturer'); ?>
              <div class="gkdCode">filter_manufacturer[]=<b>XXX</b> (ID)</div>
              <?php echo $_language->get('export_fields'); ?>
              <div class="gkdCode">export_fields[]=<b>model</b> export_fields[]=<b>quantity</b> export_fields[]=<b>price</b></div>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="tab-cron-4">
          <p>Show latest 10.000 lines of log file, to see full log click on Save log file</p>
          <textarea wrap="off" rows="30" readonly class="form-control"><?php echo $cli_log; ?></textarea>
          <div class="text-center" style="margin-top:20px">
            <a class="btn btn-success" <?php if(!$cli_log_link || !$cli_log) { echo 'disabled'; } else { echo 'href="'.$cli_log_link.'"';} ?>><i class="fa fa-download"></i> <?php echo $_language->get('text_cli_log_save'); ?><?php echo isset($cli_log_size) ? ' ('.$cli_log_size.')' : ''; ?></a>
            <a <?php if(!$cli_log_link || !$cli_log) { echo 'disabled not'; } ?>onclick="confirm('<?php echo $_language->get('text_confirm'); ?>') ? location.href='<?php echo $action.'&clear_cli_logs=1'; ?>' : false;" class="btn btn-danger"><i class="fa fa-eraser"></i> <?php echo $_language->get('text_cli_clear_logs'); ?></a>
          </div>
        </div>
      </div>
      </form>
    </div>
		<div class="tab-pane" id="tab-about">
			<table class="form about">
				<tr>
					<td colspan="2" style="text-align:center;padding:30px 0 50px"><!--<img src="<?php echo $_img_path; ?>logo.gif" alt="Pro Email Template"/>-->Universal Import/Export</td>
				</tr>
				<tr>
					<td>Version</td>
					<td><?php echo $module_version; ?> - <?php echo $module_type; ?></td>
				</tr>
				<tr>
					<td>Free support</td>
					<td>I take care of maintaining my modules at top quality and affordable price.<br/>In case of bug, incompatibility, or if you want a new feature, just contact me on my mail.</td>
				</tr>
				<tr>
					<td>Contact</td>
					<td><a href="mailto:support@geekodev.com">support@geekodev.com</a></td>
				</tr>
				<tr>
					<td>Links</td>
					<td>
						If you like this module, please consider to make a star rating <span style="position:relative;top:3px;width:80px;height:17px;display:inline-block;background:url(data:data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAARCAYAAADUryzEAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABZ0RVh0Q3JlYXRpb24gVGltZQAwNy8wNy8xMrG4sToAAAAcdEVYdFNvZnR3YXJlAEFkb2JlIEZpcmV3b3JrcyBDUzbovLKMAAACr0lEQVQ4jX1US0+TURA98/Xri0KBYqG8BDYItBoIBhFBBdRNTTQx0Q0gujBiAkEXxoXxD6iJbRcaY1iQEDXqTgwQWkWDIBU3VqWQoEgECzUU+n5910VbHhacZHLvzD05c+fMzaVhgxYJIwIYi+8B8FJ5bzjob9ucB4DmLttGMGyoAGMsyc1G7bEvA91roz2NL7Y7TziHHSxFmWsorbuUFgn79BaTLnMn3LYEZqPukCKruFAUGEd54w1ekqK69x8CSkoqMnJv72noTmN+O9Q5KlE44GqxmHTS7Qho5MH+X8SJUuMhAIbM/CrS1tSnCYsmkOoUnO7SiP3dHV8Mw5AoKkRCfTwR96ei+ZZGVVDDJQhIWAVbfhjDe8eQnd/Aq8+/VAIsAcGbR8ejQiR8jcwGbYZEkTFVd7I9B4IXcL+GEPwdK4SN0XJSDaCoAvHZsA4/93hWHNVNnbZpjoG5gl7XvpFnxggxAZRaA0rokliIAIkaxMnwdWLE7XW77jd12qYBgCMiNHfZlhgTCkZfPfUDBAYGItoiL0lK8N0+51txzD1u7Ji8njTGpk6bg/iUhSiU4GT5YOtPL940AOfiDyHod9/dMsYEzmLS5bBoKE/ES8ECCyACSF4IFledAdhd2SIFUdtmAp7i92QM+uKqVg6RJXDKakCcjyjSwcldMUDgG7I0h8WKdI0ewM2kFuTpmlb1bp2UMYBJyjBjm/FYh57MjA/1+1wuESNZOfjoLPwe516zUSdLIgi6l+sl3CIW5leD7/v7HPNTE+cOtr8tDXhWy+zWAcvnDx/XoiEPiirPBomgXxd32KAFEWp3FR0YdP60pop4sfHI5cmr+MfMRl2tXKnqzS5pyFuaHRusu2A5EyeoAEAQS2Q94VDg4pY/YUOf9ZgxnBaJJSeOdny6AgB/AYEpKtpaTusRAAAAAElFTkSuQmCC)"></span> on the module page :]<br/><br/>
						<b>Module page :</b> <a target="new" href="https://www.opencart.com/index.php?route=extension/extension/info&extension_id=21842">Pro Email Template</a><br/>
						<b>Other modules :</b> <a target="new" href="https://www.opencart.com/index.php?route=marketplace/extension&filter_member=GeekoDev">My modules on opencart</a><br/>
					</td>
				</tr>
			</table>
		</div>
		</div>
	  </div>
	  </div>
  </div>
</div>
<!-- jQuery File Upload Dependencies -->
<?php /*
<script src="<?php echo $_asset_path; ?>file-upload/jquery.ui.widget.js"></script>
<script src="<?php echo $_asset_path; ?>file-upload/jquery.iframe-transport.js"></script>
<script src="<?php echo $_asset_path; ?>file-upload/jquery.fileupload.js"></script>
*/ ?>

<script type="text/javascript"><!--
var uploadUrl = 'index.php?route=module/universal_import/import_file&<?php echo $token; ?>';

$(document).on('submit','#form',function(){
  $('#form :input[isacopy]').remove();
  $('#formCron :input').not(':submit').clone().hide().attr('isacopy','y').appendTo('#form');
  return true;
});

// info wells
$('body').on('click', '.infowell h4', function (e) {
  $(this).parent().find('div').slideToggle();
  $(this).find('i').toggleClass('fa-caret-right').toggleClass('fa-caret-down');
});

// category bindings
var categoriesOptions = [
<?php $f=false; foreach ($categories as $id => $cat) {
  echo $f ? ',' : ''; $f = true;
  echo '{id:\''.$id.'\', title:'.json_encode(html_entity_decode($cat, ENT_QUOTES, 'UTF-8')).'}';
  /*
  if ($f) {
    echo '{id:\''.$id.'\', title:'.json_encode(html_entity_decode($cat)).'}';
  } else {
    echo '{id:\'\', title:'.json_encode(html_entity_decode($_language->get('text_no_binding'))).'}';
    $f=true;
  }
  */
} ?>
];

$('body').tooltip({selector: '[data-toggle=tooltip]'});

$('body').on('click', 'button.get-bindings', function(){
  $('#categoryBinding tbody').html('<tr><td colspan="3" style="text-align:center"><img src="view/universal_import/img/loader.gif" alt=""/></td></tr>');
  
  $.post('index.php?route=module/universal_import/get_bindings&<?php echo $token; ?>', $('#import-form').serialize(), function(data) {
    $('#categoryBinding tbody').html(data);
    $('.catBindSelect').selectize({valueField: 'id', labelField: 'title', searchField: 'title', options: categoriesOptions});
  });
});

$('body').on('click', 'button.get-option-fields', function(){
  $('#optionFields tbody').html('<tr><td colspan="3" style="text-align:center"><img src="view/universal_import/img/loader.gif" alt=""/></td></tr>');
  
  $.post('index.php?route=module/universal_import/get_option_fields&<?php echo $token; ?>', $('#import-form').serialize(), function(data) {
    $('#optionFields tbody').html(data);
  });
});
      
$('.mainMenu').on('click', 'li > a', function(){
  if ($(this).attr('href') == '#tab-2' || $(this).attr('href') == '#tab-cron') {
    $('.saveBtns').show();
  } else {
    $('.saveBtns').hide();
  }
});

$('body').on('change', 'select[name=option_identifier]', function(){
  if ($(this).val() == '') {
    $('.hideForOptionUpdate').fadeIn();
  } else {
    $('.hideForOptionUpdate').fadeOut();
  }
});

$('body').on('change', 'select[name=import_source]', function(){
  //$('#importSource').load('index.php?route=module/universal_import/import_step1&<?php echo $token; ?>&file='+$('#import_file').val());
  
  $.post('index.php?route=module/universal_import/import_step1&<?php echo $token; ?>', $('#import-form').serialize(), function(data) {
    $('#importSource').html(data);
  });
  
  /*
  $('div[class^=source_]').fadeOut(100);
  $('div.source_'+$(this).val()).delay(100).fadeIn();
  */
  <?php if (!$demo_mode) { ?>
  if ($(this).val() == 'upload') {
    $('#importStep1 button.submit').attr('disabled', true);
  } else {
    $('#importStep1 button.submit').attr('disabled', false);
  }
  <?php } ?>
});
$('select[name=import_source]').change();

$('body').on('change', 'select[name=delete]', function(){
  if ($(this).val()) {
    if ($('select[name=import_type]').val() == 'product' || $('select[name=import_type]').val() == 'product_update') {
      $('.delete_batch').slideDown();
    }
    $('.delete_action').fadeIn();
  } else {
    $('.delete_batch').slideUp();
    $('.delete_action').fadeOut();
  }
});

<?php if ($demo_mode) { ?>
$('body').on('change', 'select[name=demo_file]', function(){
  if ($(this).val()) {
    $('#importStep1 button.submit').attr('disabled', false);
    $('#import_file').val($(this).val());
    
    $('#files').hide().html('<?php echo $_language->get('text_file_loaded'); ?> <i class="fa fa-file"></i> <b>' + $(this).val() + '</b>').fadeIn();
  } else {
    $('#import_file').val('');
    $('#importStep1 button.submit').attr('disabled', true);
    $('#files').fadeOut('fast');
  }
});

$('select[name=demo_file]').trigger('change');
<?php } ?>

// handle extra functions
$('body').on('change', '.xfnFieldVal', function(){
  if ($(this).val()) {
    $(this).next().hide();
  } else {
    $(this).next().show();
  }
  
  $(this).parent().parent().find('.extraFieldColumnMl').each(function() {
    name = $(this).attr('fieldname');
    name = name.replace('[_extra_]', '['+$(this).val()+']');
    $(this).attr('name', name);
  });
});

// handle extra columns
$('body').on('change', '.extraFieldName', function(){
  $(this).parent().parent().find('.extraFieldColumn').attr('name', 'columns['+$(this).val()+']');
});

$('body').on('change', '.extraFieldNameMl', function(){
  name = $(this).parent().parent().find('.extraFieldColumnMl').attr('fieldname');
  //name = name.replace(/(columns\[.+\]\[.+\]\[).+\]/, '$1'+$(this).val()+']');
  name = name.replace('[_extra_]', '['+$(this).val()+']');
  $(this).parent().parent().find('.extraFieldColumnMl').attr('name', name);
});

$('body').on('click', '.add-extra', function (e) {
  var element = $('.extraField');
  var extra = element.first().clone();
  extra.find('[disabled]').removeAttr('disabled');
  extra.insertAfter(element.last());
  $('.extraField').last().slideDown();
});

$('body').on('click', '.add-extra-ml', function (e) {
  var element = $('.extraFieldMl');
  var extra = element.first().clone();
  extra.find('[disabled]').removeAttr('disabled');
  extra.insertAfter(element.last());
  $('.extraFieldMl').last().slideDown();
});

$('body').on('click', '.remove-extra-column', function (e) {
  $(this).closest('.extraField').slideUp(400, function(){$(this).closest('.extraField').remove()});
});

$('body').on('click', '.remove-extra-column-ml', function (e) {
  $(this).closest('.extraFieldMl').slideUp(400, function(){$(this).closest('.extraFieldMl').remove()});
});

$('#import-form').on('click', 'button.submit', function(){
  var current_step = $(this).attr('data-step');
  var next_step = parseInt($(this).attr('data-step')) + 1;

  $('#importStep' + next_step).html('<div style="text-align:center"><img src="view/universal_import/img/loader.gif" alt=""/></div>');
  $('#importStep' + current_step).collapse('hide');
  $('#importStep' + next_step).collapse('show');
  
  $.ajax({
    type: 'POST',
    url: 'index.php?route=module/universal_import/import_step' + next_step + '&<?php echo $token; ?>',
    data: $('#import-form').serialize(),
    success: function(data) {
      if (data.file_error) {
        $('#alertFileUpload span').html(data.file_error);
        $('#alertFileUpload').show();
        $('#importStep2').html('').collapse('hide');
        $('#importStep1').collapse('show');
      } else {
        $('#alertFileUpload').hide();
        $('#importStep' + next_step).html(data);
        $.each( $('#importStep' + next_step + ' input.switch'), function(){ $(this).prettyCheckable(); });
      }
    },
    error: function(data, e, error) {
      $('#importStep' + next_step).html('<div class="well clearfix" style="margin: 60px"><h4>Error: '+error+'</h4><div>'+data.responseText+'</div><div class="pull-right"><button type="button" class="btn btn-default cancel" data-step="2"><i class="fa fa-reply"></i> <?php echo $_language->get('text_previous_step'); ?></button></div></div>');
      // $('#modal-alert').find('.modal-title').text('Error: ' + error);
      // $('#modal-alert').find('.modal-body').html(data.responseText);
      // $('#modal-alert').modal('show');
    }
  });
/*
  $.post('index.php?route=module/universal_import/import_step' + next_step + '&<?php echo $token; ?>', $('#import-form').serialize(), function(data) {
    if (data == 'file_not_found') {
      $('#alertFileNotFound').show();
      $('#importStep2').html('').collapse('hide');
      $('#importStep1').collapse('show');
    } else {
      $('#alertFileNotFound').hide();
      $('#importStep' + next_step).html(data);
      $.each( $('#importStep' + next_step + ' input.switch'), function(){ $(this).prettyCheckable(); });
    }
    //$('#importStep' + next_step + ' input.switch').prettyCheckable();
  });
  */
//  $('#importStep2').load('index.php?route=module/universal_import/import_step1&<?php echo $token; ?>&file='+$('#import_file').val());
});

$('#import-form').on('click', 'button.cancel', function(){
  var current_step = $(this).attr('data-step');
  var previous_step = parseInt($(this).attr('data-step')) - 1;

  $('#importStep' + current_step).collapse('hide');
  $('#importStep' + previous_step).collapse('show');
});

//$('input.switch').prettyCheckable();
$.each( $('input.switch'), function(){ $(this).prettyCheckable(); });

// display specific field
$('body').on('change', 'input[name="pwd_hash"]', function(){
  if ($(this).attr('checked')) {
    $('#pwd_salt').slideUp();
  } else {
    $('#pwd_salt').slideDown();
  }
});

$('body').on('change', 'select[name=import_type]', function(){
  $('.type-hide').hide();
  $('.show-'+$(this).val()).show();
  $('select[name=profile]').val('');
});

$('body').on('change', 'select.importCronTypeSelect', function(){
  $('.importCronType').hide();
  $('.importCronType').html($(this).val());
  $('.importCronType').fadeIn();
  $('.importCronTypeHide').hide();
  $('.importCronTypeShow-'+$(this).val()).show();
  $('select[name=importCronProfileSelect]').val('');
});

$('body').on('change', 'select.importCronProfileSelect', function(){
  $('.importCronProfile').hide();
  $('.importCronProfile').html($(this).val());
  $('.importCronProfile').fadeIn();
});

$('body').on('change', 'select.exportCronTypeSelect', function(){
  $('.exportCronType').hide();
  $('.exportCronType').html($(this).val());
  $('.exportCronType').fadeIn();
});

/* Profiles */
$('body').on('change', 'select[name=profile]', function(){
  $.post('index.php?route=module/universal_import/get_profile_source&<?php echo $token; ?>', $('#import-form').serialize(), function(data) {
    if (!($('select[name=import_source]').val() == 'upload' && data.source == 'upload')) {
     $('select[name=import_source]').val(data.source);
     $('select[name=import_source]').change();
    }
    
    if (data.extension) {
      $('select[name=import_extension]').val(data.extension);
    } else {
      $('select[name=import_extension]').val('');
    }
  });
  $('select[name=save_profile]').val($(this).val()).trigger('change');
  if ($(this).val()) {
    $('.deleteProfile').attr('disabled', false);
  } else {
    $('.deleteProfile').attr('disabled', true);
  }
});

$('body').on('click', '.deleteProfile', function(){
  if (!$('select[name=profile]').val()) return;
  
  if (window.confirm('<?php echo $_language->get('text_really_delete'); ?>')) {
    $.post('index.php?route=module/universal_import/delete_profile&<?php echo $token; ?>', $('#import-form').serialize(), function(data) {
      if(data.success) {
        $('select[name=profile] option:selected').remove();
      } else {
        alert('Error');
      }
    }, 'json');
  }
});

$('select[name=import_type]').trigger('change');

$('body').on('change', 'select[name=save_profile]', function(){
  if ($(this).val() == '') {
    $('input[name=profile_name]').show();
  } else {
    $('input[name=profile_name]').hide();
  }
});

$('#modal-profile').on('shown.bs.modal', function () {
  $('#modal-profile .alert').hide();
});

$('#saveProfile').on('click', function (e) {
  e.preventDefault();

  $.ajax({
		url: 'index.php?route=module/universal_import/save_profile&<?php echo $token; ?>',
    type:'POST',
		data: $('#import-form, #profile-form').serialize(),
		dataType: 'json',
		success: function(data){
      if(data.success) {
        $('#profile-form .alert-success span').html(data.success);
        $('#profile-form .alert-success').fadeIn();
        //$('#saveProfile').hide();
      } else if(data.error) {
        $('#profile-form .alert-warning span').html(data.error);
        $('#profile-form .alert-warning').fadeIn();
      }
		}
	});
});

$('body').on('click', '.add-column', function (e) {
  e.preventDefault();
  var element = $(this).closest('.form-group').find('select[name^=column]:not(.subitem)');
  var elCopy = $('select[name="source_columns"]').clone();
  var elContainer = document.createElement('div');
  $(elContainer).addClass('multipleElement');
  
  if ($('input[name="import_filetype"]').val() == 'xml') {
    elCopy.removeAttr('disabled').removeAttr('class').removeAttr('style').attr('name', element.attr('name'));
    
    if ($(this).hasClass('is-subcat')) {
      elCopy.addClass('subitem');
      elCopy.attr('name', element.attr('name').replace('columns[', 'columns[sub_').replace('[]', '[' + (element.length-1) + '][]'));
      $(elContainer).addClass('subitem');
    }
    
    $(elContainer).html(elCopy);
    $(elContainer).appendTo(element.closest('.multipleElement').parent());
    
    $(elContainer).find('select').selectize({create: function(input) {
      return {
        value: input,
        text: input
      }
    }});
  } else {
    elCopy.removeAttr('disabled').removeAttr('style').attr('name', element.attr('name'));
    
    if ($(this).hasClass('is-subcat')) {
      elCopy.addClass('subitem');
      elCopy.attr('name', element.attr('name').replace('columns[', 'columns[sub_').replace('[]', '[' + (element.length-1) + '][]'));
      $(elContainer).addClass('subitem');
    }
    
    $(elContainer).html(elCopy);
    $(elContainer).appendTo(element.closest('.multipleElement').parent());
  }
  
  $(this).closest('.col-sm-1').append('<button title="<?php echo $_language->get('text_remove_column'); ?>" type="button" data-toggle="tooltip" class="btn btn-danger remove-column"><i class="fa fa-minus-circle"></i></button>');
  
  return false;
});

$('body').on('click', '.remove-column', function (e) {
  //if ($('input[name="import_filetype"]').val() == 'xml') {
  //  $(this).parent().parent().find('div.selectize-control').eq($(this).index()).remove();
  //}
  
  $(this).parent().parent().find('div.multipleElement').eq($(this).index()).remove();

  var currentFieldIteration = -1;
  
  $(this).parent().parent().find('select').each(function(i, el) {
    if ($(el).hasClass('subitem')) {
      $(el).attr('name', 'columns[sub_product_category]['+currentFieldIteration+'][]');
    } else {
      currentFieldIteration++;
    }
  });
  
  $(this).remove();
  $('.tooltip').remove();
});

$('select[name=store]').change(function(){
	document.location = 'index.php?route=module/universal_import&<?php echo $token; ?>&store_id='+$(this).val();
});

/* extra functions handler */
$('body').on('click', '.add-function', function (e) {
  var element = $('#extraFuncsSrc .xfn-'+$('#extra_func_select').val());
  element.clone().insertBefore($(this).parent().parent());
  $('#extraFuncs [disabled]').removeAttr('disabled');
  var xfnRow = $('#extraFuncs tr[class^="xfn-"]').length;
  $('#extraFuncs [name^="extra_func[]"]').each(function(i) {
    $(this).attr('name', $(this).attr('name').replace('extra_func[]', 'extra_func['+xfnRow+']'));
  });
  
  $("#extraFuncs").tableDnDUpdate();
});

$('body').on('click', '.remove-function', function (e) {
  $(this).parent().parent().remove();
  $('.tooltip').remove();
});

// Export
//$('#exportContainer').tooltip({selector: '[data-toggle="tooltip"]'});

$('body').on('change', 'select[name=export_type]', function(){
  $('#exportProgress').hide();
  $('#exportContainer').html('<div style="text-align:center"><img src="view/universal_import/img/loader.gif" alt=""/></div>');
  
  $.post('index.php?route=module/universal_import/export_form&<?php echo $token; ?>', $('#export-form').serialize(), function(data) {
    //$('#exportContainer').hide().html(data).slideDown();
    $('#exportContainer').fadeOut('fast', function(){$('#exportContainer').html(data).slideDown();});
    $.each( $('#exportContainer input.switch'), function(){ $(this).prettyCheckable(); });
    //$.each( $('#exportContainer [data-toggle="tooltip"]'), function(){ $(this).tooltip(); });
  });
  
  $('.startExportProcess').attr('disabled', false);
});

$('select[name=export_type]').trigger('change');

function getTotalExportCount() {
  $('.export_number').html('<i class="fa fa-refresh fa-spin"></i>');
  
  $.post('index.php?route=module/universal_import/export_count&<?php echo $token; ?>', $('#export-form').serialize(), function(data) {
    $('.export_number').html(data);
    if (data == 0) {
      $('.startExportProcess').attr('disabled', true);
    } else {
      $('.startExportProcess').attr('disabled', false);
    }
  });
}
  
$('body').on('change', '.filters select', function(){getTotalExportCount();});
$('body').on('keyup', '.filters input', function(){getTotalExportCount();});

var pauseExportProcess = 1;
function processExportQueue(start) {
  $.ajax({
		url: 'index.php?route=module/universal_import/process_export&start='+start+'&<?php echo $token; ?>',
    type: 'POST',
		data: $('#export-form').serialize(),
		dataType: 'json',
		success: function(data){
      if(data.success) {
        $('#exportProgress .progress-bar').css('width',data.progress + '%').html(data.progress + ' %');
        
        if (!pauseExportProcess && !data.finished) {
          processExportQueue(data.processed);
        } else {
          $('#exportProgress .progress-bar').removeClass('active');
          
          if (data.finished) {
            $('.pauseExportProcess').hide();
            document.location = 'index.php?route=module/universal_import/get_export&file='+data.file+'&<?php echo $token; ?>'
          }
        }
      }
		},
    error: function(xhr) {
      alert(xhr.responseText);
    }
	});
}


$('.startExportProcess').click(function() {
  $('#exportProgress').show();
  $('#exportProgress .progress-bar').addClass('active');
  
  $('#exportProgress .progress-bar').css('min-width', '2em').html('0 %');
  
  pauseExportProcess = 0;
  processExportQueue('init');
});

$('.pauseExportProcess').click(function() {
  $('.pauseExportProcess').hide();
  $('.startExportProcess').show();
  pauseExportProcess = 1;
});
--></script>
<style>
.selectize-input{padding: 7px 12px;}
.selectize-control + .selectized + .selectize-control{margin-top:1px;}
</style>
<?php echo $footer; ?>
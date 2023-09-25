<?php
if (isset($vqmod)) {
  if (function_exists('modification')) {
    include($vqmod->modCheck(modification(DIR_TEMPLATE.'module/universal_import_functions.tpl')));
  } else {
    include($vqmod->modCheck(DIR_TEMPLATE.'module/universal_import_functions.tpl'));
  }
} else if (class_exists('VQMod')) {
  if (function_exists('modification')) {
    include(VQMod::modCheck(modification(DIR_TEMPLATE.'module/universal_import_functions.tpl')));
  } else {
    include(VQMod::modCheck(DIR_TEMPLATE.'module/universal_import_functions.tpl'));
  }
} else {
  if (function_exists('modification')) {
    include(modification(DIR_TEMPLATE.'module/universal_import_functions.tpl'));
  } else {
    include(DIR_TEMPLATE.'module/universal_import_functions.tpl');
  }
}
?>
  <select class="form-control" name="source_columns" disabled="disabled" style="display:none">
    <option value=""><?php echo $_language->get('text_ignore'); ?></option>
    <?php foreach ($columns as $key => $row) { ?>
      <option value="<?php echo $key; ?>"><?php echo $row; ?></option>
    <?php } ?>
    <?php if (!empty($profile['extra_fields'])) { ?>
      <?php foreach (explode(',', $profile['extra_fields']) as $i => $extra_field_name) { ?>
        <option value="__extra_field_<?php echo $i; ?>"><?php echo trim($extra_field_name); ?></option>
      <?php } ?>
    <?php } ?>
  </select>
  
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $_language->get('tab_general'); ?></a></li>
    <li><a href="#tab-data" data-toggle="tab"><?php echo $_language->get('tab_data'); ?></a></li>
    <li><a href="#tab-design" data-toggle="tab"><?php echo $_language->get('tab_design'); ?></a></li>
    <li class="pull-right"><a href="#tab-functions" data-toggle="tab"><?php echo $_language->get('tab_functions'); ?></a></li>
  </ul>
  <div class="tab-content alternateColors">
    <div class="tab-pane active" id="tab-general">
      <?php if(isset($profile['item_identifier']) && $profile['item_identifier'] == $type.'_id') dataField($type.'_id', $_language->get('entry_'.$type.'_id'), $columns, $profile, $_language); ?>
      <?php dataFieldML('title', $_language->get('entry_title'), $columns, $profile, $_language, $languages, $type); ?>
      <?php dataFieldML('description', $_language->get('entry_description'), $columns, $profile, $_language, $languages, $type); ?>
      <?php if (in_array('complete_seo', $installed_modules)) { ?>
        <?php dataFieldML('seo_keyword', $_language->get('entry_keyword'), $columns, $profile, $_language, $languages, $type); ?>
        <?php dataFieldML('seo_h1', $_language->get('entry_seo_h1'), $columns, $profile, $_language, $languages, $type); ?>
        <?php dataFieldML('seo_h2', $_language->get('entry_seo_h2'), $columns, $profile, $_language, $languages, $type); ?>
        <?php dataFieldML('seo_h3', $_language->get('entry_seo_h3'), $columns, $profile, $_language, $languages, $type); ?>
      <?php } ?>
      <?php dataFieldML('meta_title', $_language->get('entry_meta_title'), $columns, $profile, $_language, $languages, $type); ?>
      <?php dataFieldML('meta_description', $_language->get('entry_meta_description'), $columns, $profile, $_language, $languages, $type); ?>
      <?php dataFieldML('meta_keyword', $_language->get('entry_meta_keyword'), $columns, $profile, $_language, $languages, $type); ?>
    </div>
    
    <div class="tab-pane" id="tab-data">
      <?php dataField('information_store', $_language->get('entry_store'), $columns, $profile, $_language, 'store', $stores); ?>
      <?php if (!in_array('complete_seo', $installed_modules)) { ?>
        <?php dataField('keyword', $_language->get('entry_keyword'), $columns, $profile, $_language); ?>
      <?php } ?>
      <?php dataField('bottom', $_language->get('entry_bottom'), $columns, $profile, $_language, 'checkbox'); ?>
      <?php dataField('status', $_language->get('entry_status'), $columns, $profile, $_language, 'enabled'); ?>
      <?php dataField('sort_order', $_language->get('entry_sort_order'), $columns, $profile, $_language, 'text'); ?>
    </div>
    
    <div class="tab-pane" id="tab-design">
      <?php dataField('category_layout', $_language->get('entry_layout'), $columns, $profile, $_language); ?>
    </div>
    
    <div class="tab-pane" id="tab-functions">
    
      <ul class="nav nav-pills nav-stacked col-md-2">
        <li class="active"><a href="#tab-extra-func-1" data-toggle="pill"><?php echo $_language->get('tab_functions'); ?></a></li>
        <li><a href="#tab-extra-func-2" data-toggle="pill"><?php echo $_language->get('tab_extra'); ?></a></li>
        <li><a href="#tab-extra-func-4" data-toggle="pill"><?php echo $_language->get('tab_disable_cfg'); ?></a></li>
      </ul>
      <div class="tab-content col-md-10" style="min-height:400px;padding-bottom:120px">
        <div class="tab-pane active" id="tab-extra-func-1">
          <div class="well infowell">
            <h4><i class="fa fa-fw fa-caret-right"></i> <?php echo $_language->get('tab_functions'); ?></h4>
            <div style="display:none"><?php echo $_language->get('info_extra_functions'); ?></div>
          </div>
          
          <?php extraImportFunctions($columns, $profile, $_language, $languages); ?>
        </div>
        <div class="tab-pane" id="tab-extra-func-2">
          <div class="well infowell">
            <h4><i class="fa fa-fw fa-caret-right"></i> <?php echo $_language->get('tab_extra'); ?></h4>
            <div style="display:none"><?php echo $_language->get('info_extra_field'); ?></div>
          </div>
          
          <?php dataField('_extra_', $_language->get('entry_extra'), $columns, $profile, $_language); ?>
          <?php if (!empty($profile['extra'])) { foreach ($profile['extra'] as $extra) { ?>
            <?php dataField($extra, $_language->get('entry_extra'), $columns, $profile, $_language); ?>
          <?php }} ?>
          <div class="row">
            <div class="col-md-offset-2 col-md-7">
              <button type="button" class="btn btn-success btn-block add-extra"><i class="fa fa-plus"></i> <?php echo $_language->get('text_add_extra_field'); ?></button>
            </div>
          </div>
          
          <hr class="dotted"/>
          
          <?php dataFieldML('_extra_', $_language->get('entry_extra_ml'), $columns, $profile, $_language, $languages, $type); ?>
          <?php if (!empty($profile['extraml'])) { foreach ($profile['extraml'] as $extra) { ?>
            <?php dataFieldML($extra, $_language->get('entry_extra_ml'), $columns, $profile, $_language, $languages, $type); ?>
          <?php }} ?>
          <div class="row">
            <div class="col-md-offset-2 col-md-7">
              <button type="button" class="btn btn-success btn-block add-extra-ml"><i class="fa fa-plus"></i> <?php echo $_language->get('text_add_extra_field_ml'); ?></button>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="tab-extra-func-4">
          <div class="well infowell">
            <h4><i class="fa fa-fw fa-caret-right"></i> <?php echo $_language->get('tab_disable_cfg'); ?></h4>
            <div style="display:none"><?php echo $_language->get('info_disable_cfg'); ?></div>
          </div>
          
          <div class="row">
            <label class="col-sm-2 control-label"><?php echo $_language->get('entry_disable_config'); ?></label>
            <div class="col-md-8">
              <input class="form-control" type="text" name="disable_cfg" value="<?php echo isset($profile['disable_cfg']) ? $profile['disable_cfg'] : ''; ?>" placeholder="<?php echo $_language->get('placeholder_disable_config'); ?>"/>
            </div>
          </div>
        </div>
      </div>
    </div>
    
  </div>
  
  <hr />

  <div class="pull-right">
    <button type="button" class="btn btn-default cancel" data-step="3"><i class="fa fa-reply"></i> <?php echo $_language->get('text_previous_step'); ?></button>
    <button type="button" class="btn btn-success submit" data-step="3"><i class="fa fa-check"></i> <?php echo $_language->get('text_next_step'); ?></button>
  </div>
<div class="spacer"></div>
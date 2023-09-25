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
    <li><a href="#tab-links" data-toggle="tab"><?php echo $_language->get('tab_links'); ?></a></li>
    <li><a href="#tab-attribute" data-toggle="tab"><?php echo $_language->get('tab_attribute'); ?></a></li>
    <li><a href="#tab-option" data-toggle="tab"><?php echo $_language->get('tab_option'); ?></a></li>
    <li><a href="#tab-recurring" data-toggle="tab"><?php echo $_language->get('tab_recurring'); ?></a></li>
    <li><a href="#tab-discount" data-toggle="tab"><?php echo $_language->get('tab_discount'); ?></a></li>
    <li><a href="#tab-special" data-toggle="tab"><?php echo $_language->get('tab_special'); ?></a></li>
    <li><a href="#tab-image" data-toggle="tab"><?php echo $_language->get('tab_image'); ?></a></li>
    <li><a href="#tab-reward" data-toggle="tab"><?php echo $_language->get('tab_reward'); ?></a></li>
    <li><a href="#tab-design" data-toggle="tab"><?php echo $_language->get('tab_design'); ?></a></li>
    <li class="pull-right"><a href="#tab-functions" data-toggle="tab"><?php echo $_language->get('tab_functions'); ?></a></li>
  </ul>
  <div class="tab-content alternateColors">
  
    <div class="tab-pane active" id="tab-general">
      <input type="hidden" name="columns[minimum]" value=""/>
      <input type="hidden" name="columns[subtract]" value=""/>
      <input type="hidden" name="columns[stock_status_id]" value=""/>
      <input type="hidden" name="columns[shipping]" value=""/>
      <input type="hidden" name="columns[product_attribute]" value=""/>
      <?php if(isset($profile['item_identifier']) && $profile['item_identifier'] == $type.'_id') dataField($type.'_id', $_language->get('entry_'.$type.'_id'), $columns, $profile, $_language); ?>
      <?php dataFieldML('name', $_language->get('entry_name'), $columns, $profile, $_language, $languages, 'product'); ?>
      <?php dataFieldML('description', $_language->get('entry_description'), $columns, $profile, $_language, $languages, 'product'); ?>
      <?php if (in_array('complete_seo', $installed_modules) && $_config->get('mlseo_enabled')) { ?>
        <?php dataFieldML('seo_keyword', $_language->get('entry_keyword'), $columns, $profile, $_language, $languages, 'product'); ?>
        <?php dataFieldML('seo_h1', $_language->get('entry_seo_h1'), $columns, $profile, $_language, $languages, 'product'); ?>
        <?php dataFieldML('seo_h2', $_language->get('entry_seo_h2'), $columns, $profile, $_language, $languages, 'product'); ?>
        <?php dataFieldML('seo_h3', $_language->get('entry_seo_h3'), $columns, $profile, $_language, $languages, 'product'); ?>
        <?php dataFieldML('image_title', $_language->get('entry_img_title'), $columns, $profile, $_language, $languages, 'product'); ?>
        <?php dataFieldML('image_alt', $_language->get('entry_img_alt'), $columns, $profile, $_language, $languages, 'product'); ?>
      <?php } else if (version_compare(VERSION, '3', '>=')) { ?>
        <?php dataFieldMSML('product_seo_url', $_language->get('entry_keyword'), $columns, $profile, $_language, $languages, 'product', $stores); ?>
      <?php } ?>
      <?php dataFieldML('meta_title', $_language->get('entry_meta_title'), $columns, $profile, $_language, $languages, 'product'); ?>
      <?php dataFieldML('meta_description', $_language->get('entry_meta_description'), $columns, $profile, $_language, $languages, 'product'); ?>
      <?php dataFieldML('meta_keyword', $_language->get('entry_meta_keyword'), $columns, $profile, $_language, $languages, 'product'); ?>
      <?php dataFieldML('tag', $_language->get('entry_tag'), $columns, $profile, $_language, $languages, 'product'); ?>
    </div>
    
    <div class="tab-pane" id="tab-data">
      
      <?php dataField('model', $_language->get('entry_model'), $columns, $profile, $_language); ?>
      <?php dataField('sku', $_language->get('entry_sku'), $columns, $profile, $_language); ?>
      <?php dataField('upc', $_language->get('entry_upc'), $columns, $profile, $_language); ?>
      <?php dataField('ean', $_language->get('entry_ean'), $columns, $profile, $_language); ?>
      <?php dataField('jan', $_language->get('entry_jan'), $columns, $profile, $_language); ?>
      <?php dataField('isbn', $_language->get('entry_isbn'), $columns, $profile, $_language); ?>
      <?php dataField('mpn', $_language->get('entry_mpn'), $columns, $profile, $_language); ?>
      <?php dataField('location', $_language->get('entry_location'), $columns, $profile, $_language); ?>
      <?php dataField('price', $_language->get('entry_price'), $columns, $profile, $_language); ?>
      <div class="form-group" style="margin-bottom:0">
        <label class="col-sm-2 control-label"><?php echo $_language->get('entry_tax_class'); ?></label>
        <div class="col-md-4"><?php dataField('tax_class_id', null, $columns, $profile, $_language); ?></div>
        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $_language->get('entry_default_i'); ?>"><?php echo $_language->get('entry_default'); ?></span></label>
        <div class="col-md-4">
          <select name="defaults[tax_class_id]" class="form-control">
            <option value="0"><?php echo $_language->get('text_none'); ?></option>
            <?php foreach ($tax_classes as $tax_class) { ?>
            <option value="<?php echo $tax_class['tax_class_id']; ?>" <?php if (isset($profile['defaults']['tax_class_id']) && $profile['defaults']['tax_class_id'] == $tax_class['tax_class_id']) echo 'selected="selected"'; ?>><?php echo $tax_class['title']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <hr class="dotted"/>
      <?php dataField('quantity', $_language->get('entry_quantity'), $columns, $profile, $_language, 'text'); ?>
      <?php dataField('minimum', $_language->get('entry_minimum'), $columns, $profile, $_language, 'text'); ?>
      <?php dataField('subtract', $_language->get('entry_subtract'), $columns, $profile, $_language, 'radio'); ?>
      <div class="form-group" style="margin-bottom:0">
        <label class="col-sm-2 control-label"><?php echo $_language->get('entry_stock_status'); ?></label>
        <div class="col-md-4"><span data-toggle="tooltip" title="<?php echo $_language->get('help_field_stock_status_id'); ?>"><?php dataField('stock_status_id', null, $columns, $profile, $_language); ?></span></div>
        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $_language->get('entry_default_i'); ?>"><?php echo $_language->get('entry_default'); ?></span></label>
        <div class="col-md-4">
          <select name="defaults[stock_status_id]" class="form-control">
            <?php foreach ($stock_statuses as $stock_status) { ?>
            <option value="<?php echo $stock_status['stock_status_id']; ?>" <?php if (isset($profile['defaults']['stock_status_id']) && $profile['defaults']['stock_status_id'] == $stock_status['stock_status_id']) echo 'selected="selected"'; ?>><?php echo $stock_status['name']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <hr class="dotted"/>
      <?php dataField('shipping', $_language->get('entry_shipping'), $columns, $profile, $_language, 'radio'); ?>
      <?php if (!in_array('complete_seo', $installed_modules)) { ?>
        <?php dataField('keyword', $_language->get('entry_keyword'), $columns, $profile, $_language); ?>
      <?php } ?>
      <?php dataField('date_available', $_language->get('entry_date_available'), $columns, $profile, $_language); ?>
      <?php dataField('length', $_language->get('entry_dimension_l'), $columns, $profile, $_language); ?>
      <?php dataField('width', $_language->get('entry_dimension_w'), $columns, $profile, $_language); ?>
      <?php dataField('height', $_language->get('entry_dimension_h'), $columns, $profile, $_language); ?>
      <div class="form-group" style="margin-bottom:0">
        <label class="col-sm-2 control-label"><?php echo $_language->get('entry_length_class'); ?></label>
        <div class="col-md-4"><?php dataField('length_class_id', null, $columns, $profile, $_language); ?></div>
        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $_language->get('entry_default_i'); ?>"><?php echo $_language->get('entry_default'); ?></span></label>
        <div class="col-md-4">
          <select name="defaults[length_class_id]" class="form-control">
            <?php foreach ($length_classes as $length_class) { ?>
            <option value="<?php echo $length_class['length_class_id']; ?>" <?php if ((isset($profile['defaults']['length_class_id']) && $profile['defaults']['length_class_id'] == $length_class['length_class_id']) || (!isset($profile['defaults']['length_class_id']) && $config_length_class_id == $length_class['length_class_id'])) echo 'selected="selected"'; ?>><?php echo $length_class['title']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <hr class="dotted"/>
      <?php dataField('weight', $_language->get('entry_weight'), $columns, $profile, $_language, 'text'); ?>
      <div class="form-group" style="margin-bottom:0">
        <label class="col-sm-2 control-label"><?php echo $_language->get('entry_weight_class'); ?></label>
        <div class="col-md-4"><?php dataField('weight_class_id', null, $columns, $profile, $_language); ?></div>
        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $_language->get('entry_default_i'); ?>"><?php echo $_language->get('entry_default'); ?></span></label>
        <div class="col-md-4">
          <select name="defaults[weight_class_id]" class="form-control">
            <?php foreach ($weight_classes as $weight_class) { ?>
            <option value="<?php echo $weight_class['weight_class_id']; ?>" <?php if ((isset($profile['defaults']['weight_class_id']) && $profile['defaults']['weight_class_id'] == $weight_class['weight_class_id']) || (!isset($profile['defaults']['weight_class_id']) && $config_weight_class_id == $weight_class['weight_class_id'])) echo 'selected="selected"'; ?>><?php echo $weight_class['title']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <hr class="dotted"/>
      <?php if (!in_array('complete_seo', $installed_modules)) { ?>
        <?php dataField('seo_keyword', $_language->get('entry_seo_keyword'), $columns, $profile, $_language, 'text'); ?>
      <?php } ?>
      <?php dataField('status', $_language->get('entry_status'), $columns, $profile, $_language, 'enabled'); ?>
      <?php dataField('sort_order', $_language->get('entry_sort_order'), $columns, $profile, $_language, 'text'); ?>
    </div>
    
    <div class="tab-pane" id="tab-links">
      <?php dataField('manufacturer_id', $_language->get('entry_manufacturer'), $columns, $profile, $_language, 'selectize_single', $manufacturers); ?>
      <?php dataField('product_category', $_language->get('entry_category'), $columns, $profile, $_language, 'selectize', $categories, 'category'); ?>
      
      <?php dataField('product_filter', $_language->get('entry_filter'), $columns, $profile, $_language, false, false, true); ?>
      <div class="row col-md-offset-2">
        <div class="well infowell">
          <h4><i class="fa fa-fw fa-caret-right"></i> <?php echo $_language->get('info_filter_title'); ?></h4>
          <div style="display:none"><?php echo $_language->get('info_filter_xml'); ?></div>
        </div>
        <table id="attributeFields" class="table table-bordered">
          <thead>
            <tr>
              <th style="width:20%;"><?php echo $_language->get('text_optbinding_name'); ?></th>
              <th><?php echo $_language->get('text_optbinding_bind_to'); ?></th>
            </tr>
          </thead>
          <tbody>
          <?php foreach (array('group', 'name') as $attribute_type) { ?>
          <tr>
            <td><?php echo $_language->get('text_filter_'.$attribute_type); ?></td>
            <td>
              <?php foreach ($languages as $lang) { ?>
                <div class="input-group">
                  <span class="input-group-addon"><img src="<?php echo $lang['image']; ?>" title="<?php echo $lang['name']; ?>"></span>
                  <input type="text" name="filter_fields[<?php echo $attribute_type; ?>][<?php echo $lang['language_id']; ?>]" value="<?php echo isset($profile['filter_fields'][$attribute_type][$lang['language_id']]) ? $profile['filter_fields'][$attribute_type][$lang['language_id']] : ''; ?>" class="form-control"/>
                  </span>
                </div>
              <?php } ?>
            </td>
          </tr>
          <?php } ?>
          </tbody>
        </table>
      </div>
      <hr class="dotted"/>

      <?php dataField('product_store', $_language->get('entry_store'), $columns, $profile, $_language, 'store', $stores); ?>
      <?php dataField('product_download', $_language->get('entry_download'), $columns, $profile, $_language); ?>
      <?php dataField('product_related', $_language->get('entry_related'), $columns, $profile, $_language, false, false, true); ?>
    </div>
    <div class="tab-pane" id="tab-attribute">
      <div class="well infowell">
        <h4><i class="fa fa-fw fa-caret-right"></i> <?php echo $_language->get('info_attributes_title'); ?></h4>
        <div style="display:none"><?php echo $_language->get('info_attributes'); ?></div>
      </div>
      
      <?php dataField('product_attribute', $_language->get('entry_attribute'), $columns, $profile, $_language, false, false, true); ?>
      
      <?php if ($filetype == 'xml') { ?>
      <div class="well infowell">
        <h4><i class="fa fa-fw fa-caret-right"></i> <?php echo $_language->get('info_attributes_xml_title'); ?></h4>
        <div style="display:none"><?php echo $_language->get('info_attributes_xml'); ?></div>
      </div>
      <table id="attributeFields" class="table table-bordered">
        <thead>
          <tr>
            <th style="width:20%;"><?php echo $_language->get('text_optbinding_name'); ?></th>
            <th><?php echo $_language->get('text_optbinding_bind_to'); ?></th>
            <td><?php echo $_language->get('entry_default'); ?></td>
          </tr>
        </thead>
        <tbody>
        <?php foreach (array('group', 'name', 'value') as $attribute_type) { ?>
        <tr>
          <td><?php echo $_language->get('text_attribute_'.$attribute_type); ?></td>
          <td>
            <?php foreach ($languages as $lang) { ?>
              <div class="input-group">
                <span class="input-group-addon"><img src="<?php echo $lang['image']; ?>" title="<?php echo $lang['name']; ?>"></span>
                <input type="text" name="attribute_fields[<?php echo $attribute_type; ?>][<?php echo $lang['language_id']; ?>]" value="<?php echo isset($profile['attribute_fields'][$attribute_type][$lang['language_id']]) ? $profile['attribute_fields'][$attribute_type][$lang['language_id']] : ''; ?>" class="form-control"/>
                </span>
              </div>
            <?php } ?>
          </td>
          <td>
            <?php foreach ($languages as $lang) { ?>
              <div class="input-group">
                <span class="input-group-addon"><img src="<?php echo $lang['image']; ?>" title="<?php echo $lang['name']; ?>"></span>
                <input type="text" name="attribute_fields_default[<?php echo $attribute_type; ?>][<?php echo $lang['language_id']; ?>]" value="<?php echo isset($profile['attribute_fields_default'][$attribute_type][$lang['language_id']]) ? $profile['attribute_fields_default'][$attribute_type][$lang['language_id']] : ''; ?>" class="form-control" placeholder="<?php echo ($attribute_type == 'group') ? 'Default' : ''; ?>"/>
                </span>
              </div>
            <?php } ?>
          </td>
        </tr>
        <?php } ?>
        </tbody>
        
      </table>
      <?php } ?>
    </div>
    
    <div class="tab-pane" id="tab-option">
      <div class="well infowell">
        <h4><i class="fa fa-fw fa-caret-right"></i> <?php echo $_language->get('info_options_title'); ?></h4>
        <div style="display:none"><?php echo $_language->get('info_options'); ?></div>
      </div>
      <?php dataField('product_option', $_language->get('entry_option'), $columns, $profile, $_language, false, false, true); ?>
      
      <div class="well infowell">
        <?php if ($filetype == 'xml') { ?>
          <h4><i class="fa fa-fw fa-caret-right"></i> <?php echo $_language->get('info_options_xml_title'); ?></h4>
          <div style="display:none"><?php echo $_language->get('info_options_xml'); ?></div>
        <?php } else { ?>
          <h4><i class="fa fa-fw fa-caret-right"></i> <?php echo $_language->get('info_options_advanced_title'); ?></h4>
          <div style="display:none"><?php echo $_language->get('info_options_advanced'); ?></div>
        <?php } ?>
      </div>
      <table id="optionFields" class="table table-bordered">
        <thead>
          <tr>
            <th style="width:20%;"><?php echo $_language->get('text_optbinding_name'); ?></th>
            <th><?php echo $_language->get('text_optbinding_bind_to'); ?></th>
            <td><?php echo $_language->get('entry_default'); ?></td>
            <th style="width:55px;"></th>
          </tr>
        </thead>
        <tbody>
        <?php /*
          <?php if (!empty($profile['option_fields'])){ foreach ($profile['option_fields'] as $col_from => $col_to) { ?>
            <tr>
              <td>Option <?php echo $col_from ?></td>
              <td><input type="text" name="option_fields[<?php echo $col_from ?>]" class="form-control" value="<?php echo $col_to ?>"/></td>
              <td></td>
              <td><button title="<?php echo $_language->get('text_remove_function'); ?>" type="button" data-toggle="tooltip" class="btn btn-danger remove-function"><i class="fa fa-minus-circle"></i></button></td>
            </tr>
          <?php }} else { ?>
            <tr><td colspan="4" class="text-center"><?php echo $_language->get('text_no_bindings'); ?></td></tr>
          <?php } ?>
          */ ?>
          <tr>
            <td colspan="3" style="text-align:center" class="form-inline">
              <button type="button" class="btn btn-success get-option-fields"><i class="fa fa-refresh"></i> <?php echo $_language->get('text_get_optbinding'); ?></button>
            </td>
          </tr>
        </tbody>
        
      </table>
      
    </div>

    <div class="tab-pane" id="tab-recurring">
      <?php dataField('product_recurrings', $_language->get('entry_recurring'), $columns, $profile, $_language); ?>
    </div>
    
    <div class="tab-pane" id="tab-discount">
      <?php dataField('product_discount', $_language->get('tab_discount'), $columns, $profile, $_language, false, false, true); ?>
    </div>
    
    <div class="tab-pane" id="tab-special">
      <?php dataField('product_special', $_language->get('tab_special'), $columns, $profile, $_language, false, false, true); ?>
    </div>
    
    <div class="tab-pane" id="tab-image">
      <?php dataField('image', $_language->get('entry_image'), $columns, $profile, $_language, false); ?>
      <?php dataField('product_image', $_language->get('entry_additional_image'), $columns, $profile, $_language, false, true, true); ?>
    </div>

    <div class="tab-pane" id="tab-reward">
      <?php dataField('points', $_language->get('entry_points'), $columns, $profile, $_language); ?>
      <?php dataField('product_reward', $_language->get('entry_reward'), $columns, $profile, $_language); ?>
    </div>

    <div class="tab-pane" id="tab-design">
      <?php dataField('product_layout', $_language->get('entry_layout'), $columns, $profile, $_language); ?>
    </div>
    
    <div class="tab-pane" id="tab-functions">
    
      <ul class="nav nav-pills nav-stacked col-md-2">
        <li class="active"><a href="#tab-extra-func-1" data-toggle="pill"><?php echo $_language->get('tab_functions'); ?></a></li>
        <li><a href="#tab-extra-func-2" data-toggle="pill"><?php echo $_language->get('tab_extra'); ?></a></li>
        <li><a href="#tab-extra-func-3" data-toggle="pill"><?php echo $_language->get('tab_cat_binding'); ?></a></li>
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
        <div class="tab-pane" id="tab-extra-func-3">
          <div class="well infowell">
            <h4><i class="fa fa-fw fa-caret-right"></i> <?php echo $_language->get('tab_cat_binding'); ?></h4>
            <div style="display:none"><?php echo $_language->get('info_cat_binding'); ?></div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $_language->get('entry_cat_binding_mode'); ?></label>
            <div class="col-md-10">
              <select name="col_binding_mode" class="form-control">
                <option value="" <?php if (empty($profile['col_binding_mode'])) echo 'selected="selected"'; ?>><?php echo $_language->get('text_cat_binding_default'); ?></option>
                <option value="1" <?php if (isset($profile['col_binding_mode']) && $profile['col_binding_mode'] == '1') echo 'selected="selected"'; ?>><?php echo $_language->get('text_cat_binding_exclusive'); ?></option>
                <option value="3" <?php if (isset($profile['col_binding_mode']) && $profile['col_binding_mode'] == '3') echo 'selected="selected"'; ?>><?php echo $_language->get('text_cat_binding_exclusive_skip'); ?></option>
                <option value="2" <?php if (isset($profile['col_binding_mode']) && $profile['col_binding_mode'] == '2') echo 'selected="selected"'; ?>><?php echo $_language->get('text_cat_binding_skip'); ?></option>
              </select>
            </div>
          </div>
          
          <table id="categoryBinding" class="table table-bordered">
            <thead>
              <tr>
                <th style="width:45%;"><?php echo $_language->get('text_catbinding_name'); ?></th>
                <th><?php echo $_language->get('text_catbinding_bind_to'); ?></th>
                <th style="width:55px;"></th>
              </tr>
            </thead>
            <tbody>
              <?php if(!empty($profile['col_binding'])){ foreach ($profile['col_binding'] as $col_from => $col_to) { ?>
                <tr>
                  <td><input type="hidden" name="col_binding_names[<?php echo $col_from; ?>]" value="<?php echo isset($profile['col_binding_names'][$col_from]) ? $profile['col_binding_names'][$col_from] : ''; ?>"/><?php echo isset($profile['col_binding_names'][$col_from]) ? $profile['col_binding_names'][$col_from] : ''; ?></td>
                  <td>
                    <select name="col_binding[<?php echo $col_from; ?>][]" class="catBindSelect" multiple>
                      <?php foreach((array) $col_to as $val) { ?>
                      <option value="<?php echo $val; ?>" selected></option>
                      <?php } ?>
                    </select>
                  </td>
                  <td><button title="<?php echo $_language->get('text_remove_function'); ?>" type="button" data-toggle="tooltip" class="btn btn-danger remove-function"><i class="fa fa-minus-circle"></i></button></td>
                </tr>
              <?php }} else { ?>
                <tr><td colspan="3" class="text-center"><?php echo $_language->get('text_no_bindings'); ?></td></tr>
              <?php } ?>
              <tr>
                <td colspan="3" style="text-align:center" class="form-inline">
                  <button type="button" class="btn btn-success get-bindings"><i class="fa fa-refresh"></i> <?php echo $_language->get('text_get_bindings'); ?></button>
                </td>
              </tr>
            </tbody>
            
          </table>
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

<script type="text/javascript"><!--
<?php if (!empty($profile['option_fields'])) { ?>
$('.get-option-fields').trigger('click');
<?php } ?>
$('.catBindSelect').selectize({valueField: 'id', labelField: 'title', searchField: 'title', plugins: ['remove_button'], options: categoriesOptions});
<?php if ($filetype == 'xml') { ?>
$('select[name^="columns"]').removeClass('form-control');
$('select[name^="columns"]').selectize({create: function(input) {
  return {
    value: input,
    text: input
  }
}});
<?php } ?>
--></script>
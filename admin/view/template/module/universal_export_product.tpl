  <?php include 'universal_export_functions.tpl'; ?>
  
  <fieldset class="filters"><legend><div class="pull-right" style="font-size:13px; color:#666"><?php echo $_language->get('total_export_items'); ?> <span class="export_number badge clearblue"></span></div><?php echo $_language->get('export_filters'); ?></legend>
  
    <div class="row">
      <div class="col-sm-4">
        <div class="form-group">
          <?php fieldLabel('filter_language', $_language); ?>
          <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-fw fa-flag"></i></span>
          <select class="form-control" name="filter_language">
            <option value=""><?php echo $_language->get('export_all'); ?></option>
            <?php foreach ($languages as $language) { ?>
              <option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
            <?php } ?>
          </select>
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="form-group">
          <?php fieldLabel('filter_store', $_language); ?>
          <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-fw fa-home"></i></span>
          <select class="form-control" name="filter_store">
            <?php foreach ($stores as $store) { ?>
              <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
            <?php } ?>
          </select>
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="form-group">
          <?php fieldLabel('filter_limit', $_language); ?>
          <div class="input-group">
          <span class="input-group-addon"><?php echo $_language->get('filter_limit_start'); ?></span>
          <input type="text" class="form-control" name="filter-start" placeholder="" />
          <span class="input-group-addon"><?php echo $_language->get('filter_limit_limit'); ?></span>
          <input type="text" class="form-control" name="filter-limit" placeholder=""/>
          </div>
        </div>
      </div>
    </div>
    <hr class="dotted" style="margin:0"/>
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <?php fieldLabel('filter_category', $_language); ?>
          <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-fw fa-folder-open-o"></i></span>
          <select class="selectize_category" name="filter_category[]">
            <option value=""><?php echo $_language->get('export_all'); ?></option>
            <?php foreach ($categories as $category) { ?>
              <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
            <?php } ?>
          </select>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <?php fieldLabel('filter_manufacturer', $_language); ?>
          <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-fw fa-industry"></i></span>
          <select class="selectize_category" name="filter_manufacturer[]">
            <option value=""><?php echo $_language->get('export_all'); ?></option>
            <?php foreach ($manufacturers as $manufacturer) { ?>
              <option value="<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></option>
            <?php } ?>
          </select>
          </div>
        </div>
      </div>
    </div>
  
  </fieldset>
  
  <fieldset><legend><?php echo $_language->get('export_options'); ?></legend>
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <?php fieldLabel('param_price_multiplier', $_language); ?>
          <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-fw fa-dollar"></i></span>
            <input type="text" class="form-control" name="price_multiplier" placeholder="1.2"/>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <?php fieldLabel('param_image_path', $_language); ?>
          <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-fw fa-image"></i></span>
          <select class="form-control" name="param_image_path">
            <option value=""><?php echo $_language->get('image_path_absolute'); ?></option>
            <option value="1"><?php echo $_language->get('image_path_relative'); ?></option>
          </select>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="form-group">
          <?php fieldLabel('export_fields', $_language); ?>
          <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-fw fa-list"></i></span>
          <select class="selectize_category" name="export_fields[]">
            <option value=""><?php echo $_language->get('export_all'); ?></option>
            <?php foreach (array(
              //'product_id','model','sku','upc','ean','jan','isbn','mpn','location','quantity','stock_status_id','image','manufacturer_id','shipping','price','points','tax_class_id','date_available','weight','weight_class_id','length','width','height','length_class_id','subtract','minimum','sort_order','status','viewed','date_added','date_modified','meta_robots','mfilter_values','mfilter_tags','import_batch','delivered_by','public_download','manufacturer','name','description','tag','meta_title','meta_description','meta_keyword','seo_keyword','seo_h1','seo_h2','seo_h3','image_title','image_alt','imgtitlecp','additional_images','product_filter','product_attribute','product_option','product_category','product_discount','product_special'
              'product_id','model','name','link','description','sku','upc','ean','jan','isbn','mpn','location','quantity','store','stock_status_id','image','manufacturer','manufacturer_id','tag','meta_title','meta_description','meta_keyword','seo_keyword','additional_images','product_filter','product_attribute','product_option','product_category','product_discount','product_special','shipping','price','points','tax_class_id','date_available','weight','weight_class_id','length','width','height','length_class_id','subtract','minimum','sort_order','status','viewed','date_added','date_modified',
            ) as $field) { ?>
              <option value="<?php echo $field; ?>"><?php echo $field; ?></option>
            <?php } ?>
          </select>
          </div>
        </div>
      </div>
    </div>
  </fieldset>

<div class="spacer"></div>

<script type="text/javascript">
var $selectize_category = $('.selectize_category').selectize({
  maxItems: null
});

getTotalExportCount();
</script>
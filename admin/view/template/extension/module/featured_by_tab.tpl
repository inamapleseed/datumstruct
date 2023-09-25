<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-featured" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <div class="panel panel-default">

      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>

      <div class="panel-body">

        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-featured" class="form-horizontal">

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div> 

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="status" id="input-status" class="form-control">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>         

          <div class="tab-pane">
              <ul class="nav nav-tabs" id="language">
                <?php foreach ($languages as $language) { ?>
                  <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                <?php } ?>
              </ul>
              <div class="tab-content">
                <?php foreach ($languages as $language) { ?>
                  <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="input-title<?php echo $language['language_id']; ?>">Title</label>
                      <div class="col-sm-10">
                        <input type="text" name="title[<?php echo $language['language_id']; ?>][title]" placeholder="Title" id="input-heading<?php echo $language['language_id']; ?>" value="<?php echo isset($title[$language['language_id']]['title']) ? $title[$language['language_id']]['title'] : ''; ?>" class="form-control" />
                      </div>
                    </div>
                  </div>
                <?php } ?>
              </div>
              <script type="text/javascript"><!--
                $('#language a:first').tab('show');
              //--></script>
          </div>
    
          <div class="form-group">
            <div class="col-sm-12">

              <h3>
                <button id="add_category" class="btn btn-warning text-left" style="white-space: normal; display: inline-block; vertical-align: middle;position: relative;top: -2px;" >
                  <i class="fa fa-plus-circle"></i>   
                  Add Category Tab
                </button>
                 &nbsp;&nbsp;Category Tabs
              </h3>
              <hr/>

            </div>
            <div class="col-sm-2">
              <ul class="nav nav-pills nav-stacked" id="category_tabs" >
                <?php $nav_i = 0; ?>
                <?php foreach($tabs as $tab){ ?>
                  <li>
                    <a href="#tab-category-<?= $nav_i; ?>" data-toggle="tab" >
                        <i class="fa fa-minus-circle" onclick="removeTab(<?= $nav_i; ?>);"></i>
                        Category Tab <?= 1+$nav_i; ?>
                    </a>
                  </li>
                  <?php $nav_i++; ?>
                <?php } ?>
              </ul>
            </div>
            <div class="col-sm-10">
              <div class="tab-content" id="category_body" >
                  <?php $body_i = 0; ?>
                  <?php foreach($tabs as $tab){ ?>
                    <div class="tab-pane" id="tab-category-<?= $body_i; ?>">
                        <!-- Category -->
                        <div class="form-group">-
                            <label class="col-sm-2 control-label">Tab Name</label>
                            <div class="col-sm-10">
                                <input  type="text" name="tabs[<?= $body_i; ?>][tab_name]" class="form-control" value="<?= $tab['tab_name']; ?>" />
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Category</label>
                            <div class="col-sm-10">
                                <select name="tabs[<?= $body_i; ?>][category_id]" class="form-control">
                                    <option value="">None</option>
                                    <?php foreach($category_list as $each){ ?>
                                      <option value="<?= $each['category_id']; ?>" 
                                        <?= $tab['category_id']==$each['category_id']?'selected':''; ?>
                                        ><?= $each['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <!-- Product -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Products</label>
                            <div class="col-sm-10">
                                <input type="text" value="" placeholder="Products" id="input-product-<?= $body_i; ?>" class="form-control" autocomplete="off">
                                <div id="category_tab_products_<?= $body_i; ?>" class="well well-sm" style="height: 150px; overflow: auto;">
                                  <?php foreach($tab['product_ids'] as $product){ ?>
                                    <div id="featured-product-<?= $body_i; ?>-<?= $product['product_id']; ?>">
                                      <i class="fa fa-minus-circle"></i> <?= $product['name']; ?>
                                      <input type="hidden" name="tabs[<?= $body_i; ?>][product_ids][]" value="<?= $product['product_id']; ?>" />
                                    </div>
                                  <?php } ?>
                                </div>
                            </div>
                        </div>
            
                        <!-- Status -->
                        <div class="form-group">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tab Status</label>
                                <div class="col-sm-10">
                                    <select name="tabs[<?= $body_i; ?>][status]" class="form-control">
                                        <option value="1" 
                                            <?= $tab['status']?'selected':''; ?>
                                            >Enabled</option>
                                        <option value="0" 
                                            <?= !$tab['status']?'selected':''; ?>
                                            >Disabled</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $body_i++; ?>
                  <?php } ?>
              </div>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
<script type="text/javascript">
  $('#category_tabs a:first').tab('show');
</script>
<script type="text/javascript">

  var li  =   '<li>';
      li  +=  ' <a href="#tab-category-[INDEX]" data-toggle="tab">';
      li  +=  '   <i class="fa fa-minus-circle" onclick="removeTab([INDEX]);"></i>';
      li  +=  '   Category Tab [INDEX_PLUS_ONE]';
      li  +=  ' </a>';
      li  +=  '</li>';
      
  var body  =   '<div class="tab-pane" id="tab-category-[INDEX]">';

      // Tab Name
      body  +=  ' <div class="form-group">';
      body  +=  '   <label class="col-sm-2 control-label">Tab Name</label>';
      body  +=  '   <div class="col-sm-10">';
      body  +=  '     <input type="text" name="tabs[[INDEX]][tab_name]" class="form-control">';
      body  +=  '   </div>';
      body  +=  '  </div>';

      // Category
      body  +=  ' <div class="form-group">';
      body  +=  '   <label class="col-sm-2 control-label">Category</label>';
      body  +=  '   <div class="col-sm-10">';
      body  +=  '     <select name="tabs[[INDEX]][category_id]" class="form-control">';
      body  +=  '       <option value="">None</option>';
                        <?php foreach($category_list as $each){ ?>
      body  +=  '         <option value="<?= $each['category_id']; ?>" ><?= $each['name']; ?></option>';
                        <?php } ?>
      body  +=  '     </select>';
      body  +=  '   </div>';
      body  +=  '  </div>';

      // Product
      body  +=  ' <div class="form-group">';
      body  +=  '   <label class="col-sm-2 control-label">Products</label>';
      body  +=  '   <div class="col-sm-10">';
      body  +=  '     <input type="text" value="" placeholder="Products" id="input-product-[INDEX]" class="form-control" autocomplete="off">';
      body  +=  '     <div id="category_tab_products_[INDEX]" class="well well-sm" style="height: 150px; overflow: auto;">';
      body  +=  '     </div>';
      body  +=  '   </div>';
      body  +=  '  </div>';

      // Status
      body  +=  ' <div class="form-group">';
      body  +=  '   <label class="col-sm-2 control-label" >Tab Status</label>';
      body  +=  '   <div class="col-sm-10">';
      body  +=  '     <select name="tabs[[INDEX]][status]" class="form-control" >';
      body  +=  '         <option value="1" >Enabled</option>';
      body  +=  '         <option value="0" >Disabled</option>';
      body  +=  '     </select>';
      body  +=  '   </div>';
      body  +=  '  </div>';

      body  +=  '</div>'; // End tab-pane

  var index = <?= $nav_i; ?>;

  $('#add_category').on('click', function(e){
    e.preventDefault();

    // Initialize new tab
    var index_plus_one = index+1;

    // Set Html
    var li_to_add = li.replace('[INDEX_PLUS_ONE]', index_plus_one);
    li_to_add = li_to_add.split('[INDEX]');
    li_to_add = li_to_add.join(index);    

    var body_to_add = body.split('[INDEX]');
    body_to_add = body_to_add.join(index);
    
    // Append Html
    $('#category_tabs').append(li_to_add);
    $('#category_body').append(body_to_add);

    // Switch to new tab;
    $('#category_tabs a[href="#tab-category-'+index+'"]').tab('show');
    
    // Auto Complete Products in tab
    autoComplete(index);

    // Increment index
    index++;
  });

</script>
<script type="text/javascript"><!--
  function removeTab($index){
    $('a[href="#tab-category-'+$index+'"]').parent().remove();
    $('#tab-category-'+$index+'').remove();
  }
  function autoComplete($index){
    $('#input-product-'+$index).autocomplete({
      source: function(request, response) {
        $.ajax({
          url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
          dataType: 'json',
          success: function(json) {
            response($.map(json, function(item) {
              return {
                label: item['name'],
                value: item['product_id']
              }
            }));
          }
        });
      },
      select: function(item) {
        $('#input-product-'+$index).val('');
        
        $('#featured-product-' + $index + '-' + item['value']).remove();
        
        var $row  =   '<div id="featured-product-' + $index + '-' + item['value'] + '">';
            $row  +=  '   <i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="tabs['+$index+'][product_ids][]" value="' + item['value'] + '" />';
            $row  +=  '</div>';

        $('#category_tab_products_' + $index).append($row);	
      }
    });
      
    $('#category_tab_products_' + $index).delegate('.fa-minus-circle', 'click', function() {
      $(this).parent().remove();
    });
  }
//--></script>
<script type="text/javascript">
  <?php $body_script_i = 0; ?>
  <?php foreach($tabs as $tab){ ?>
    autoComplete('<?= $body_script_i; ?>');
    <?php $body_script_i++; ?>
  <?php } ?>
</script>
</div>
<?php echo $footer; ?>
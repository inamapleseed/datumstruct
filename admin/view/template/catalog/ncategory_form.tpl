<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-ncategory" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
  
    <?php echo $newspanel; ?>
	
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($error_keyword) { ?>
       <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_keyword; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
       </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-ncategory" class="form-horizontal">
		  <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
            <li><a href="#tab-design" data-toggle="tab"><?php echo $tab_design; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active in" id="tab-general">
				<ul class="nav nav-tabs" id="language">
					<?php foreach ($languages as $language) { ?>
						<li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
					<?php } ?>
				</ul>
		<div class="tab-content">
          <?php foreach ($languages as $language) { ?>
           <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
				<div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-name<?php echo $language['language_id']; ?>"><?php echo $entry_name; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="ncategory_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($ncategory_description[$language['language_id']]) ? $ncategory_description[$language['language_id']]['name'] : ''; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_name[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                 </div>
                 <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="ncategory_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($ncategory_description[$language['language_id']]) ? $ncategory_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                    </div>
                 </div>
				 <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
                    <div class="col-sm-10">
                      <textarea name="ncategory_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($ncategory_description[$language['language_id']]) ? $ncategory_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
                    </div>
                 </div>
                 <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="ncategory_description[<?php echo $language['language_id']; ?>][description]" id="input-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($ncategory_description[$language['language_id']]) ? $ncategory_description[$language['language_id']]['description'] : ''; ?></textarea>
                    </div>
                 </div>
           </div>
          <?php } ?>
		</div>
            </div>
        <div class="tab-pane fade" id="tab-data">
			<div class="form-group">
                <label class="col-sm-2 control-label" for="input-parent"><?php echo $entry_parent; ?></label>
                <div class="col-sm-10">
					<select name="parent_id" id="input-parent" class="form-control">
						<option value="0"><?php echo $text_none; ?></option>
						<?php foreach ($ncategories as $ncategory) { ?>
							<?php if ($ncategory['ncategory_id'] == $parent_id) { ?>
								<option value="<?php echo $ncategory['ncategory_id']; ?>" selected="selected"><?php echo $ncategory['name']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $ncategory['ncategory_id']; ?>"><?php echo $ncategory['name']; ?></option>
							<?php } ?>
						<?php } ?>
					</select>
                </div>
            </div>
			<div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">
                    <div class="checkbox">
                      <label>
                        <?php if (in_array(0, $ncategory_store)) { ?>
                        <input type="checkbox" name="ncategory_store[]" value="0" checked="checked" />
                        <?php echo $text_default; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="ncategory_store[]" value="0" />
                        <?php echo $text_default; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php foreach ($stores as $store) { ?>
                    <div class="checkbox">
                      <label>
                        <?php if (in_array($store['store_id'], $ncategory_store)) { ?>
                        <input type="checkbox" name="ncategory_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                        <?php echo $store['name']; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="ncategory_store[]" value="<?php echo $store['store_id']; ?>" />
                        <?php echo $store['name']; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php } ?>
                  </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_groups; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($customer_groups as $group) { ?>
                    <div class="checkbox">
                      <label>
                        <?php if (in_array($group['customer_group_id'], $ncategory_group)) { ?>
                        <input type="checkbox" name="ncategory_group[]" value="<?php echo $group['customer_group_id']; ?>" checked="checked" />
                        <?php echo $group['name']; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="ncategory_group[]" value="<?php echo $group['customer_group_id']; ?>" />
                        <?php echo $group['name']; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php } ?>
                  </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-keyword"><?php echo $entry_keyword; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="keyword" value="<?php echo $keyword; ?>" id="input-keyword" class="form-control" />
                    <?php if ($error_keyword) { ?>
                      <div class="text-danger"><?php echo $error_keyword; ?></div>
                    <?php } ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_image; ?></label>
                <div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
                </div>
            </div>
			<div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_top; ?></label>
                <div class="col-sm-10"><br />
					<?php if ($top) { ?>
						<input type="checkbox" name="top" value="1" checked="checked" class="form-control" />
					<?php } else { ?>
						<input type="checkbox" name="top" value="1" class="form-control" />
					<?php } ?>
                </div>
            </div>
			<div class="form-group">
                <label class="col-sm-2 control-label" for="input-column"><?php echo $entry_column; ?></label>
                <div class="col-sm-10">
				  <input type="text" name="column" value="<?php echo $column; ?>" id="input-column" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" id="input-sort-order" class="form-control" />
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
        </div>
		<div class="tab-pane" id="tab-design">
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_store; ?></td>
                      <td class="text-left"><?php echo $entry_layout; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="text-left"><?php echo $text_default; ?></td>
                      <td class="text-left"><select name="ncategory_layout[0]" class="form-control">
                          <option value=""></option>
                          <?php foreach ($layouts as $layout) { ?>
                          <?php if (isset($ncategory_layout[0]) && $ncategory_layout[0] == $layout['layout_id']) { ?>
                          <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select></td>
                    </tr>
                    <?php foreach ($stores as $store) { ?>
                    <tr>
                      <td class="text-left"><?php echo $store['name']; ?></td>
                      <td class="text-left"><select name="ncategory_layout[<?php echo $store['store_id']; ?>]" class="form-control">
                          <option value=""></option>
                          <?php foreach ($layouts as $layout) { ?>
                          <?php if (isset($ncategory_layout[$store['store_id']]) && $ncategory_layout[$store['store_id']] == $layout['layout_id']) { ?>
                          <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
        </div>
       </div>
      </form>
    </div>
  </div>
</div></div>
<!-- Enhanced CKEditor -->
<?php if ($fm_installed == 0) { ?>
<?php if ($ckeditor_enabled == 1) { ?>
<script type="text/javascript">
<?php foreach($languages as $language) { ?>
    CKEDITOR.replace("input-description<?= $language['language_id']; ?>", {
      baseHref: "<?= $base_url; ?>",
      language: "<?= $language['code']; ?>",
      filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?= $token; ?>',
      filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?= $token; ?>',
      filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?= $token; ?>',
      skin: "<?= $ckeditor_skin; ?>",
      codemirror: { theme: "<?= $codemirror_skin; ?>", }, height: 350
    });
</script>
<?php } ?>
<?php } ?>
<?php } ?>
<!-- Enhanced CKEditor -->
<?php if ($bnews_html_editor == 'ckeditor') { ?>
<script type="text/javascript" src="view/blog-res/ckeditor/ckeditor.js"></script> 
<script type="text/javascript">
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('input-description<?php echo $language['language_id']; ?>');
<?php } ?>
CKEDITOR.on( 'dialogDefinition', function( e ) {
    var dialogName = e.data.name;
    var dialogDefinition = e.data.definition;
    editor = e.editor;
    callBack = editor._.filebrowserFn;

    if ( dialogName == 'image' ) {   
        var infoTab = dialogDefinition.getContents( 'info' );
        var browseButton = infoTab.get( 'browse' );
        browseButton.hidden = false;
        browseButton.onClick = function () {
            editor._.filebrowserSe = this;
            $('#modal-image').remove();
            $.ajax({
                url: 'index.php?route=common/filemanager&token=<?php echo $token; ?>&target=editor',
                dataType: 'html',
                success: function(html) {
                    $('body').append('<div id="modal-image" style="z-index: 999999;" class="modal modal-blog-page">' + html + '</div>');              
                    $('#modal-image').modal('show');
                }
            });         
        }            
    }
});
$(document).on('click', '#modal-image.modal-blog-page a.thumbnail', function(e) {
    e.preventDefault();                                   
    window.CKEDITOR.tools.callFunction( callBack, $(this).attr('href') );
    $('#modal-image').modal('hide');
});
</script> 
<?php } elseif ($bnews_html_editor == 'tinymce') { ?>
<script type="text/javascript" src="view/blog-res/tinymce/tinymce.min.js"></script> 
<script>
  tinymce.init({
    selector: '<?php $langcount = 0; foreach ($languages as $language) { $langcount++; if ($langcount > 1) { echo ', '; } ?>#input-description<?php echo $language['language_id']; ?><?php } ?>',
    height: 500,
    plugins: 'advlist image code link table anchor autolink hr textcolor visualblocks contextmenu',
    toolbar: ['undo redo | styleselect forecolor backcolor | bold italic | bullist numlist | alignleft aligncenter alignright | hr | anchor link image | visualblocks code'],
    contextmenu: 'link image inserttable | cell row column deletetable',
    relative_urls: false,
    remove_script_host: false,
    file_picker_types: 'image',
    file_picker_callback : function(callback) {
        $('#modal-image').remove();
        $.ajax({
            url: 'index.php?route=common/filemanager&token=<?php echo $token; ?>&target=editor',
            dataType: 'html',
            success: function(html) {
                $('body').append('<div id="modal-image" style="z-index: 999999;" class="modal modal-blog-page">' + html + '</div>');              
                $('#modal-image').modal('show');
                $(document).on('click', '#modal-image.modal-blog-page a.thumbnail', function(e,win,field_name) {
                    e.preventDefault();                 
                    callback($(this).attr('href'));
                    $('#modal-image').modal('hide');
                });
            }
        });   
    }
  });
</script>
<?php } else { ?>
<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/summernote/opencart.js"></script> 
<?php } ?>
<script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script>
<?php echo $footer; ?>
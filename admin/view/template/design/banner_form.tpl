<?php echo $header; ?><?php echo $column_left; ?>
<div id="content" class="<?= BANNER_EXTRA?'':'basic-banner'; ?> " >
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-banner" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-banner" class="form-horizontal">
          <div class="form-group required">
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
          <br />
          <ul class="nav nav-tabs" id="language">
            <?php foreach ($languages as $language) { ?>
            <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
            <?php } ?>
          </ul>
          <div class="tab-content">
            <?php $image_row = 0; ?>
            <?php foreach ($languages as $language) { ?>
            <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
              <table id="images<?php echo $language['language_id']; ?>" class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <td class="text-left"><?php echo $entry_title; ?></td>
                    <td class="text-left"><?php echo $entry_link; ?></td>
                    <td width="250px" class="text-center"><?php echo $entry_image; ?></td>
                    <td width="250px" class="text-center">Mobile <?php echo $entry_image; ?></td>
                    <td width="80px" class="text-right"><?php echo $entry_sort_order; ?></td>
                    <td width="1px"></td>
                  </tr>
                </thead>
                <tbody>
                  <?php if (isset($banner_images[$language['language_id']])) { ?>
                  <?php foreach ($banner_images[$language['language_id']] as $banner_image) { ?>
                  <tr id="image-row<?php echo $image_row; ?>">
                    <td class="text-left"><input type="text" name="banner_image[<?php echo $language['language_id']; ?>][<?php echo $image_row; ?>][title]" value="<?php echo $banner_image['title']; ?>" placeholder="<?php echo $entry_title; ?>" class="form-control" />
                      <?php if (isset($error_banner_image[$language['language_id']][$image_row])) { ?>
                      <div class="text-danger"><?php echo $error_banner_image[$language['language_id']][$image_row]; ?></div>
                      <?php } ?>
                    <span class="extras">
                      <hr/>
                      <p>Description</p>	
                      <textarea rows="3" class="form-control" name="banner_image[<?php echo $language['language_id']; ?>][<?php echo $image_row; ?>][description]" ><?php echo $banner_image['description']; ?></textarea>
                    </span>
                    </td>
                    <td class="text-left">
                    <span class="extras">
                      <p>Label</p>
                      <input type="text" name="banner_image[<?php echo $language['language_id']; ?>][<?php echo $image_row; ?>][link_text]" value="<?php echo $banner_image['link_text']; ?>" placeholder="Label" class="form-control" />
                    <p>Link</p>
                    </span>
                    <input type="text" name="banner_image[<?php echo $language['language_id']; ?>][<?php echo $image_row; ?>][link]" value="<?php echo $banner_image['link']; ?>" placeholder="<?php echo $entry_link; ?>" class="form-control" /></td>
                    <td class="text-center"><a href="" id="thumb-image-<?php echo $image_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $banner_image['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                      <input type="hidden" name="banner_image[<?php echo $language['language_id']; ?>][<?php echo $image_row; ?>][image]" value="<?php echo $banner_image['image']; ?>" id="input-image<?php echo $image_row; ?>" />
                    <hr/>
                    <div class="input-group">
                      <div class="input-group-addon">
                        Background&nbsp;type
                      </div>
                      <select name="banner_image[<?php echo $language['language_id']; ?>][<?php echo $image_row; ?>][color_theme]" class="form-control">
                        <option value="light_image" <?php echo ($banner_image['color_theme']=="light_image" )?'selected':''; ?> > Light
                        </option>
                        <option value="dark_image" <?php echo ($banner_image['color_theme']=="dark_image" )?'selected':''; ?> > Dark </option>
                      </select>
                    </div>
                     
                    
                    </td>
                    <td class="text-center"><a href="" id="thumb-mobile-image-<?php echo $image_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $banner_image['mobile_thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                        <input type="hidden" name="banner_image[<?php echo $language['language_id']; ?>][<?php echo $image_row; ?>][mobile_image]" value="<?php echo $banner_image['mobile_image']; ?>" id="input-mobile-image<?php echo $image_row; ?>" />
                        <hr/>

                        <div class="input-group">
                          <div class="input-group-addon">
                            Background&nbsp;type
                          </div>
                          <select name="banner_image[<?php echo $language['language_id']; ?>][<?php echo $image_row; ?>][mobile_color_theme]" class="form-control" >
                            <option value="light_image" <?= $banner_image['mobile_color_theme']=="light_image"?'selected':''; ?> >Light</option>
                            <option value="dark_image" <?= $banner_image['mobile_color_theme']=="dark_image"?'selected':''; ?>>Dark</option>
                          </select>
                        </div>
                    </td>
                    <td class="text-right" style="width: 10%;"><input type="text" name="banner_image[<?php echo $language['language_id']; ?>][<?php echo $image_row; ?>][sort_order]" value="<?php echo $banner_image['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>
                    <td class="text-left"><button type="button" onclick="$('#image-row<?php echo $image_row; ?>, .tooltip').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                  </tr>
                  <?php $image_row++; ?>
                  <?php } ?>
                  <?php } ?>
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="5"></td>
                    <td class="text-left"><button type="button" onclick="addImage('<?php echo $language['language_id']; ?>');" data-toggle="tooltip" title="<?php echo $button_banner_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                  </tr>
                </tfoot>
              </table>
            </div>
            <?php } ?>
          </div>
        </form>
      </div>
    </div>
  </div>
  
  <script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage(language_id) {
	html  = '<tr id="image-row' + image_row + '">';
  html += '  <td class="text-left"><input type="text" name="banner_image[' + language_id + '][' + image_row + '][title]" value="" placeholder="<?php echo $entry_title; ?>" class="form-control" /><span class="extras"><hr/><p>Description</p><textarea rows="3" class="form-control" name="banner_image[' + language_id + ']['+ image_row +'][description]" ></textarea></span></td>';
	html += '  <td class="text-left"><span class="extras"><p>Label</p><input type="text" name="banner_image[' + language_id + '][' + image_row + '][link_text]" value="" placeholder="Label" class="form-control" /><p>Link</p></span><input type="text" name="banner_image[' + language_id + '][' + image_row + '][link]" value="" placeholder="<?php echo $entry_link; ?>" class="form-control" /></td>';	
	html += '  <td class="text-center"><a href="" id="thumb-image' + image_row + '" data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="banner_image[' + language_id + '][' + image_row + '][image]" value="" id="input-image' + image_row + '" /><hr/>';
  html += '<div class="input-group">';
  html += ' <div class="input-group-addon">Background&nbsp;Type</div>';
  
  html += '<select name="banner_image[' + language_id + '][' + image_row + '][color_theme]" class="form-control" ><option value="light_image" >Light</option><option value="dark_image" >Dark</option></select></div></td>';
  html += '  <td class="text-center"><a href="" id="thumb-mobile-image' + image_row + '" data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="banner_image[' + language_id + '][' + image_row + '][mobile_image]" value="" id="input-mobile-image' + image_row + '" /><hr/>';
  
  html += '<div class="input-group">';
  html += ' <div class="input-group-addon">Background&nbsp;Type</div>';

  html += '<select name="banner_image[' + language_id + '][' + image_row + '][mobile_color_theme]" class="form-control" ><option value="light_image" >Light</option><option value="dark_image" >Dark</option></select></div></td>';
	html += '  <td class="text-right" style="width: 10%;"><input type="text" name="banner_image[' + language_id + '][' + image_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row  + ', .tooltip\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	$('#images' + language_id + ' tbody').append(html);
	
	image_row++;
}
//--></script> 
  <script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script> 
</div>
<?php echo $footer; ?> 
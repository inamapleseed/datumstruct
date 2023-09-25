<?php function mailPreviewPanel($_language, $order_statuses, $mail_custom, $store_id, $token, $mijourl, $languages) { ?>
<div class="mail-preview panel panel-default">
  <div class="panel-heading clearfix">
    <h3 class="panel-title"><i class="fa fa-search"></i> <?php echo $_language->get('text_preview'); ?></h3>
    <div class="pull-right">
      <div class="btn-group" role="group">
        <button type="button" class="btn btn-default preview-refresh"><i class="fa fa-refresh"></i></button>
      </div>
      <div class="btn-group" role="group">
        <button type="button" class="btn btn-default preview-image"><i class="fa fa-picture-o"></i></button>
      </div>
      <div class="btn-group preview-size" role="group">
        <button type="button" class="btn btn-default active" data-size="100%"><i class="fa fa-desktop"></i></button>
        <button type="button" class="btn btn-default" data-size="768px"><i class="fa fa-tablet"></i></button>
        <button type="button" class="btn btn-default" data-size="320px"><i class="fa fa-mobile"></i></button>
      </div>
     
    </div>
    <div class="pull-right">
      <select class="preview-lang form-control" style="width:50px;padding-left:3px;padding-right:0">
        <?php foreach ($languages as $language) { ?>
        <option value="<?php echo $language['language_id']; ?>"><?php echo strtoupper(substr($language['code'], 0, 2)); ?></a></li>
        <?php } ?>
      </select>
    </div>
     <div class="pull-right">
      <select class="preview-type form-control" style="width:200px">
       <optgroup label="<?php echo $_language->get('text_type_customer'); ?>">
        <option value="customer.register"><?php echo $_language->get('text_type_customer.register'); ?></option>
        <option value="customer.approve"><?php echo $_language->get('text_type_customer.approve'); ?></option>
        <option value="customer.forgotten"><?php echo $_language->get('text_type_customer.forgotten'); ?></option>
        <option value="customer.reward"><?php echo $_language->get('text_type_customer.reward'); ?></option>
        <option value="customer.credit"><?php echo $_language->get('text_type_customer.credit'); ?></option>
        <option value="customer.voucher"><?php echo $_language->get('text_type_customer.voucher'); ?></option>
       </optgroup>
       <?php /* ?>
       <optgroup label="<?php echo $_language->get('text_type_affiliate'); ?>">
        <option value="affiliate.register"><?php echo $_language->get('text_type_affiliate.register'); ?></option>
        <option value="affiliate.approve"><?php echo $_language->get('text_type_affiliate.approve'); ?></option>
        <option value="affiliate.forgotten"><?php echo $_language->get('text_type_affiliate.forgotten'); ?></option>
        <option value="affiliate.transaction"><?php echo $_language->get('text_type_affiliate.transaction'); ?></option>
       </optgroup>
       <?php */ ?>
       <optgroup label="<?php echo $_language->get('text_type_order'); ?>">
        <option value="order.confirm"><?php echo $_language->get('text_type_order.confirm'); ?></option>
        <option value="order.return"><?php echo $_language->get('text_type_order.return'); ?></option>
       </optgroup>
      <optgroup label="<?php echo $_language->get('text_type_enquiry'); ?>">
          <option value="enquiry.confirm"><?php echo $_language->get('text_type_enquiry.confirm'); ?></option>
      </optgroup>
      <optgroup label="<?php echo $_language->get('text_type_enquirystatus'); ?>">
          <?php foreach ($order_statuses as $status) { ?>
          <option value="enquiry.update|<?php echo $status['order_status_id']; ?>">
            <?php echo $status['name']; ?></option>
          <?php } ?>
      </optgroup>
       <optgroup label="<?php echo $_language->get('text_type_orderstatus'); ?>">
        <?php foreach ($order_statuses as $status) { ?>
          <option value="order.update|<?php echo $status['order_status_id']; ?>">
            <?php echo $status['name']; ?></option>
        <?php } ?>
       </optgroup>
       <optgroup label="<?php echo $_language->get('text_type_admin'); ?>">
        <?php 
          //foreach (array('admin.order.confirm', 'admin.enquiry.confirm', 'admin.customer.register', 'admin.affiliate.register', 'admin.information.contact') as $type) {
          foreach (array('admin.order.confirm', 'admin.enquiry.confirm', 'admin.customer.register', 'admin.information.contact') as $type) { ?>
          <option value="<?php echo $type; ?>"><?php echo $_language->get('text_type_'.$type); ?></option>
        <?php } ?>
       </optgroup>
       <?php if (!empty($mail_custom)) { ?>
       <optgroup label="<?php echo $_language->get('text_tab_content_4'); ?>">
        <?php foreach ($mail_custom as $type => $custom) { ?>
          <option value="<?php echo $type; ?>"><?php echo $custom['name']; ?></option>
        <?php } ?>
       </optgroup>
       <?php } ?>
    </select>
    </div>
  </div>
  <div class="preview-content">
    <iframe src="index.php?<?php echo $mijourl; ?>route=module/pro_email/preview&store_id=<?php echo $store_id; ?>&<?php echo $token; ?>"></iframe>
  </div>
</div>
<?php } ?>
<?php function imageField($id, $OC_V2, $_language, $thumb, $val, $no_image) { ?>
  <?php if ($OC_V2) { ?>
    <a href="" id="thumb_<?php echo $id; ?>" data-toggle="image" class="img-thumbnail"><img class="imgChangeReload" src="<?php echo $thumb[$id]; ?>" alt="" title="" data-placeholder="<?php echo $no_image; ?>" /></a>
    <input type="hidden" name="proemail_theme[<?php echo $id; ?>]" value="<?php echo $val[$id]; ?>" id="input-image-<?php echo $id; ?>" />
  <?php } else { ?>
  <div class="image" style="text-align:center; float:left;"><img src="<?php echo $thumb[$id]; ?>" alt="" id="thumb_<?php echo $id; ?>" />
  <input type="hidden" name="proemail_theme[<?php echo $id; ?>]" value="<?php echo $val[$id]; ?>" id="proemail_<?php echo $id; ?>" />
  <br />
  </div>
  <div style="margin-left:10px;float:left;"><br /><a onclick="image_upload('proemail_<?php echo $id; ?>', 'thumb_<?php echo $id; ?>');"><?php echo $_language->get('text_browse'); ?></a><br /><br /><a onclick="jQuery('#thumb_<?php echo $id; ?>').attr('src', '<?php echo $no_image; ?>'); jQuery('#proemail_<?php echo $id; ?>').attr('value', '');"><?php echo $_language->get('text_clear'); ?></a></div>
  <?php } ?>
<?php } ?>
<?php function imageRepeat($id, $_language, $val) { $id.= '_repeat'; ?>
  <select name="proemail_theme[<?php echo $id; ?>]" class="form-control changeReload">
    <option value="" <?php if($val[$id] == '') echo 'selected="selected"'; ?>><?php echo $_language->get('text_repeat'); ?></option>
    <option value="repeat-x" <?php if($val[$id] == 'repeat-x') echo 'selected="selected"'; ?>><?php echo $_language->get('text_repeat-x'); ?></option>
    <option value="repeat-y" <?php if($val[$id] == 'repeat-y') echo 'selected="selected"'; ?>><?php echo $_language->get('text_repeat-y'); ?></option>
    <option value="no-repeat" <?php if($val[$id] == 'no-repeat') echo 'selected="selected"'; ?>><?php echo $_language->get('text_no-repeat'); ?></option>
    <option value="top center no-repeat" <?php if($val[$id] == 'top center no-repeat') echo 'selected="selected"'; ?>><?php echo $_language->get('text_no-repeat_center'); ?></option>
  </select>
<?php } ?>
<?php function mailEditorForm($type, $items, $languages, $_language, $from_name_placeholder, $from_email_placeholder) { ?>
  <?php $admin = ''; ?>
  <?php $f=1; $row=0; foreach ($items as $key => $item) { ?>
  <?php
    if ($type == 'admin') {
      $admin = 'admin-';
      $type = 'type';
    }

    if ($type == 'enquiry_status') {
      $key = $item['order_status_id'];
      $extra = '';
      if(true) {
        $extra .= 'tags_qesu,';
      }
      $tags = 'tags,tags_status,'.$extra.'tags_enquiry_cond,tags_enquiry,tags_conditions';
    }else
    if ($type == 'status') {
      $key = $item['order_status_id'];
      $extra = '';
      if(true) {
        $extra .= 'tags_qosu,';
      }
      $tags = 'tags,tags_status,'.$extra.'tags_order_cond,tags_order,tags_conditions';
    } else if ($type == 'custom') {
      $tags = 'tags,custom';
    } else { //debug($type);
      $tags = 'tags';
      switch ($key) {
        case 'order.confirm':
        case 'admin.order.confirm':
          $tags .= ',tags_order_cond,tags_order,tags_conditions'; break;
        case 'enquiry.confirm':
          $tags .= ',tags_enquiry_cond,tags_enquiry,tags_conditions'; break;
        case 'admin.customer.register':
          $tags .= ',tags_customer'; break;
        default:
          $tags .= ','.$key;
      }
    }
    ?>
  <div id="tab-<?php echo $admin.$type; ?>-<?php echo $row; ?>" class="tab-pane <?php if($f) echo ' active'; $f=0; ?>">
    <ul class="nav nav-tabs nav-language">
    <?php $f=1; foreach ($languages as $language) { ?>
    <li class="tab-lang-<?php echo $language['language_id']; ?> <?php if($f) echo 'active'; $f=0; ?>"><a data-lang="<?php echo $language['language_id']; ?>" href=".tab-lang-<?php echo $language['language_id']; ?>"><img src="<?php echo $language['image']; ?>" alt=""/> <?php echo $language['name']; ?></a></li>
    <?php } ?>
    </ul>
    <div class="tab-content tab-language">
      <?php $f=1; foreach ($languages as $language) { ?>
      <div class="tab-lang-<?php echo $language['language_id']; ?> tab-pane <?php if($f) echo ' active'; $f=0; ?>">
      <table class="form">
        <tr>
          <td><?php echo $_language->get('entry_from'); ?></td>
          <td class="container-fluid">
            <div class="col-md-6" style="padding-left:0">
            <input type="text" name="proemail_<?php echo $type; ?>[<?php echo $key; ?>][from_name][<?php echo $language['language_id']; ?>]" value="<?php echo isset($item['from_name'][$language['language_id']]) ? $item['from_name'][$language['language_id']] : ''; ?>" class="form-control" placeholder="<?php echo !empty($from_name_placeholder[$language['language_id']]) ? $from_name_placeholder[$language['language_id']] : $from_name_placeholder['default']; ?>"/>
            </div>
            <div class="col-md-6" style="padding-right:0">
            <input type="text" name="proemail_<?php echo $type; ?>[<?php echo $key; ?>][from_email][<?php echo $language['language_id']; ?>]" value="<?php echo isset($item['from_email'][$language['language_id']]) ? $item['from_email'][$language['language_id']] : ''; ?>" class="form-control" placeholder="<?php echo !empty($from_email_placeholder[$language['language_id']]) ? $from_email_placeholder[$language['language_id']] : $from_email_placeholder['default']; ?>"/>
            </div>
          </td>
        </tr>
        <?php if ($admin) { ?>
        <tr>
          <td><?php echo $_language->get('entry_to'); ?></td>
          <td><input type="text" name="proemail_<?php echo $type; ?>[<?php echo $key; ?>][to][<?php echo $language['language_id']; ?>]" value="<?php echo isset($item['to'][$language['language_id']]) ? $item['to'][$language['language_id']] : ''; ?>" class="form-control" placeholder="<?php echo $from_email_placeholder['default']; ?>"/></td>
        </tr>
        <?php } ?>
        <tr>
          <td><?php echo $_language->get('entry_subject'); ?></td>
          <td><input type="text" name="proemail_<?php echo $type; ?>[<?php echo $key; ?>][subject][<?php echo $language['language_id']; ?>]" value="<?php echo isset($item['subject'][$language['language_id']]) ? $item['subject'][$language['language_id']] : ''; ?>" class="form-control"/></td>
        </tr>
        <tr>
        <td>
          <button type="button" class="btn btn-default btn-xs info-btn" data-toggle="modal" data-target="#modal-info" data-info="<?php echo $tags; ?>"><i class="fa fa-info"></i></button>
          <?php echo $_language->get('entry_content'); ?>
          <!--<img style="" src="view/pro_email/img/layout_body.png" alt=""/>-->
        </td>
        <td>
          <?php if(defined('JPATH_MIJOSHOP_OC')) {
            $desc = isset($item['content'][$language['language_id']]) ? $item['content'][$language['language_id']] : '';
            echo MijoShop::get('base')->editor()->display("proemail_".$type."[".$key."][content][".$language['language_id']."]", $desc, '97% !important', '320', '50', '11');
           } else { ?>
          <textarea name="proemail_<?php echo $type; ?>[<?php echo $key; ?>][content][<?php echo $language['language_id']; ?>]" id="proemail_<?php echo $type; ?>-<?php echo str_replace('.', '_', $key); ?>-<?php echo $language['language_id']; ?>" class="editorInit" style="height:307px; width:100%"><?php echo isset($item['content'][$language['language_id']]) ? $item['content'][$language['language_id']] : ''; ?></textarea>
          <?php } ?>
        </td>
        </tr>
        <tr>
          <td><?php echo $_language->get('entry_attachment'); ?></td>
          <td>
            <div class="input-group">
              <input type="text" name="proemail_<?php echo $type; ?>[<?php echo $key; ?>][file][<?php echo $language['language_id']; ?>]" value="<?php echo isset($item['file'][$language['language_id']]) ? $item['file'][$language['language_id']] : ''; ?>" placeholder="<?php echo $_language->get('placeholder_file'); ?>" class="form-control fileinput" style="height:35px"/>
              <span class="input-group-btn">
              <button type="button" data-loading-text="<?php echo $_language->get('text_loading'); ?>" class="btn btn-primary button-upload" style="height:35px"><i class="fa fa-upload"></i> <?php echo $_language->get('button_upload'); ?></button>
              </span>
            </div>
          </td>
        </tr>
      </table>
      </div>
      <?php } ?>
    </div>
  </div>
  <?php $row++; } ?>
<?php } ?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<?php if(!empty($style_scoped)) { ?><style scoped><?php echo $style_scoped; ?></style><?php } ?>
<input type="hidden" name="no-image" value="0" />
<div id="modal-info" class="modal <?php if ($OC_V2) echo ' fade'; ?>" tabindex="-1" role="dialog" aria-hidden="true"><span class="modalContent"></span></div>

			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
					<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>

  <div class="<?php if($OC_V2) echo 'container-fluid'; ?>">
	<?php if (isset($success) && $success) { ?><div class="alert alert-success success"><i class="fa fa-check-circle"></i> <?php echo $success; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div><script type="text/javascript">setTimeout("jQuery('.alert-success').slideUp();",5000);</script><?php } ?>
	<?php if (isset($info) && $info) { ?><div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $info; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div><?php } ?>
	<?php if (isset($error) && $error) { ?><div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div><?php } ?>
    <?php if (isset($error_warning) && $error_warning) { ?><div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div><?php } ?>
<div class="panel panel-default">
	<div class="panel-heading">
    <div class="pull-right">
      <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $button_save; ?></button>
      <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default hidden"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?></a>
    </div>
		<h3 class="panel-title"><img src="<?php echo $_img_path; ?>icon_big.png" alt="" style="vertical-align:top;"/> <?php echo $heading_title; ?></h3>
	</div>
	<div class="content panel-body">
  <div id="stores" class="form-inline" <?php if ($OC_V2 && 0) echo 'class="v2"'; ?>>
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
	</div>
		<ul class="nav nav-tabs">
    	<li class="active"><a href="#tab-0" data-toggle="tab" class="tabChangeReload"><i class="fa fa-file-text-o"></i><?php echo $_language->get('text_tab_0'); ?></a></li>
			<li><a href="#tab-1" data-toggle="tab" class="tabChangeReload"><i class="fa fa-eyedropper"></i><?php echo $_language->get('text_tab_1'); ?></a></li>
			<li><a href="#tab-2" data-toggle="tab" class="setContentPreview"><i class="fa fa-pencil"></i><?php echo $_language->get('text_tab_2'); ?></a></li>
			<li><a href="#tab-3" data-toggle="tab"><i class="fa fa-cube"></i><?php echo $_language->get('text_tab_3'); ?></a></li>
			<li><a href="#tab-4" data-toggle="tab"><i class="fa fa-cog"></i><?php echo $_language->get('text_tab_4'); ?></a></li>
			<li><a href="#tab-about" data-toggle="tab"><i class="fa fa-info"></i><?php echo $_language->get('text_tab_about'); ?></a></li>
		</ul>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <input type="hidden" name="proemail_seourl" value="<?php echo (in_array('complete_seo', $installed_modules) || in_array('multilingual_seo', $installed_modules)) ? '1' : ''; ?>">
		<div class="tab-content container-fluid">
       <div class="tab-pane active row" id="tab-0">
       <div class="col-md-6">
        <table class="form">
          <tr>
            <td><?php echo $_language->get('entry_layout'); ?></td>
            <td><select name="proemail_layout" id="selectize_layouts">
              <option value="<?php echo $proemail_layout; ?>"><?php echo $proemail_layout; ?></option>
            </select></td>
          </tr>
          <tr>
            <td><?php echo $_language->get('entry_color_scheme'); ?></td>
            <td><select name="proemail_color_scheme" id="selectize_colors">
              <option value="<?php echo $_config->get('proemail_color_scheme'); ?>"></option>
            </select></td>
          </tr>
        </table>
        </div>
        <div class="col-md-6">
          <?php echo mailPreviewPanel($_language, $order_statuses, $mail_custom, $store_id, $token, $mijourl, $languages); ?>
        </div>
      </div>
      <div class="tab-pane clearfix" id="tab-1">
      <div class="pull-right">
        <button id="save_scheme" class="btn btn-warning"><?php echo $_language->get('text_save_scheme'); ?></button>
      </div>
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-design-1" data-toggle="tab"><?php echo $_language->get('text_global'); ?></a></li>
          <li><a href="#tab-design-2" data-toggle="tab"><?php echo $_language->get('text_top'); ?></a></li>
          <li><a href="#tab-design-3" data-toggle="tab"><?php echo $_language->get('text_header'); ?></a></li>
          <li><a href="#tab-design-4" data-toggle="tab"><?php echo $_language->get('text_body'); ?></a></li>
          <li><a href="#tab-design-5" data-toggle="tab"><?php echo $_language->get('text_foot'); ?></a></li>
          <li><a href="#tab-design-6" data-toggle="tab"><?php echo $_language->get('text_bottom'); ?></a></li>
        </ul>
        <div class="tab-content clearfix">
          <div class="tab-pane active" id="tab-design-1">
            <table class="form">
              <tr>
                <td class="form-horizontal">
                  <h3><img style="padding-right:15px" src="view/pro_email/img/layout_global.png" alt=""/><?php echo $_language->get('text_global'); ?></h3>
                  
                  <div class="form-group form-inline">
                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $_language->get('entry_layout_width_i'); ?>"><?php echo $_language->get('entry_layout_width'); ?></span></label>
                    <div class="col-sm-4">
                      <input name="proemail_theme[width]" type="text" class="form-control delayChangeReload" value="<?php echo isset($proemail_theme['width']) ? $proemail_theme['width'] : ''; ?>" />
                      <select name="proemail_theme[width_unit]" class="form-control changeReload">
                        <option value="px" <?php if(isset($proemail_theme['width_unit']) && $proemail_theme['width_unit'] == 'px') echo 'selected="selected"'; ?>>px</option>
                        <option value="%" <?php if(isset($proemail_theme['width_unit']) && $proemail_theme['width_unit'] == '%') echo 'selected="selected"'; ?>>%</option>
                      </select>
                    </div>
                  </div>
                  
                  <div class="form-group  form-inline">
                    <label class="col-sm-2 control-label"><?php echo $_language->get('entry_logo'); ?></label>
                    <div class="col-sm-4">
                      <?php imageField('logo', $OC_V2, $_language, $thumb, $proemail_theme, $no_image); ?>
                    </div>
                    
                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $_language->get('entry_logo_width_i'); ?>"><?php echo $_language->get('entry_logo_width'); ?></span></label>
                    <div class="col-sm-4">
                      <input name="proemail_theme[logo_width]" type="text" class="form-control delayChangeReload" value="<?php echo isset($proemail_theme['logo_width']) ? $proemail_theme['logo_width'] : ''; ?>" />
                      px
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="color_btn" class="col-sm-2 control-label"><?php echo $_language->get('entry_color_btn'); ?></label>
                    <div class="col-sm-4">
                      <input name="proemail_color[btn]" data-target=".button" data-property="background-color" type="text" id="color_btn" class="form-control minicolors" value="<?php echo isset($proemail_color['btn']) ? $proemail_color['btn'] : ''; ?>" />
                    </div>
                    <label for="color_btn_text" class="col-sm-2 control-label"><?php echo $_language->get('entry_color_btn_text'); ?></label>
                    <div class="col-sm-4">
                      <input name="proemail_color[btn_text]" data-target=".btn_txt" data-property="color" type="text" id="color_btn_text" class="form-control minicolors" value="<?php echo isset($proemail_color['btn_text']) ? $proemail_color['btn_text'] : ''; ?>" />
                    </div>
                  </div>
     
                  <div class="form-group">
                    <label for="color_bg_page" class="col-sm-2 control-label"><?php echo $_language->get('entry_color'); ?></label>
                    <div class="col-sm-10">
                      <input name="proemail_color[bg_page]" data-target="body, .body" data-property="background-color" type="text" id="color_bg_page" class="form-control minicolors" value="<?php echo isset($proemail_color['bg_page']) ? $proemail_color['bg_page'] : ''; ?>" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $_language->get('entry_background_image'); ?></label>
                    <div class="col-sm-4">
                      <?php imageField('bg_page', $OC_V2, $_language, $thumb, $proemail_theme, $no_image); ?>
                    </div>
                    <label class="col-sm-2 control-label"><?php echo $_language->get('entry_repeat'); ?></label>
                    <div class="col-sm-4">
                      <?php imageRepeat('bg_page', $_language, $proemail_theme); ?>
                    </div>
                  </div>
                </td>
              </tr>
            </table>
          </div>
          <div class="tab-pane" id="tab-design-2">
            <table class="form">
              <tr>
                <td class="form-horizontal">
                  <h3><img style="padding-right:15px" src="view/pro_email/img/layout_top.png" alt=""/><?php echo $_language->get('text_top'); ?></h3>
                  
                  <div class="form-group">
                    <label for="color_text_top" class="col-sm-2 control-label"><?php echo $_language->get('entry_color_text'); ?></label>
                    <div class="col-sm-4">
                      <input name="proemail_color[text_top]" data-target=".top, .top p" data-property="color" type="text" id="color_text_top" class="form-control minicolors" value="<?php echo isset($proemail_color['text_top']) ? $proemail_color['text_top'] : ''; ?>" />
                    </div>
                    <label for="color_link_top" class="col-sm-2 control-label"><?php echo $_language->get('entry_color_link'); ?></label>
                    <div class="col-sm-4">
                      <input name="proemail_color[link_top]" data-target=".top a:not([class])" data-property="color" type="text" id="color_link_top" class="form-control minicolors" value="<?php echo isset($proemail_color['link_top']) ? $proemail_color['link_top'] : ''; ?>" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="top" class="col-sm-2 control-label"><?php echo $_language->get('entry_color'); ?></label>
                    <div class="col-sm-10">
                      <input name="proemail_color[bg_top]" data-target=".top" data-property="background-color" type="text" id="color_bg_top" class="form-control minicolors" value="<?php echo isset($proemail_color['bg_top']) ? $proemail_color['bg_top'] : ''; ?>" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $_language->get('entry_background_image'); ?></label>
                    <div class="col-sm-4">
                      <?php imageField('bg_top', $OC_V2, $_language, $thumb, $proemail_theme, $no_image); ?>
                    </div>
                    <label class="col-sm-2 control-label"><?php echo $_language->get('entry_repeat'); ?></label>
                    <div class="col-sm-4">
                      <?php imageRepeat('bg_top', $_language, $proemail_theme); ?>
                    </div>
                  </div>
                </td>
              </tr>
            </table>
          </div>
          <div class="tab-pane" id="tab-design-3">
            <table class="form">
              <tr>
                <td class="form-horizontal">
                  <h3><img style="padding-right:15px" src="view/pro_email/img/layout_header.png" alt=""/><?php echo $_language->get('text_header'); ?></h3>
                  
                  <div class="form-group">
                    <label for="color_text_head" class="col-sm-2 control-label"><?php echo $_language->get('entry_color_text'); ?></label>
                    <div class="col-sm-4">
                      <input name="proemail_color[text_head]" data-target=".header, .header p" data-property="color" type="text" id="color_text_head" class="form-control minicolors" value="<?php echo isset($proemail_color['text_head']) ? $proemail_color['text_head'] : ''; ?>" />
                    </div>
                    <label for="color_link_head" class="col-sm-2 control-label"><?php echo $_language->get('entry_color_link'); ?></label>
                    <div class="col-sm-4">
                      <input name="proemail_color[link_head]" data-target=".header a:not([class])" data-property="color" type="text" id="color_link_head" class="form-control minicolors" value="<?php echo isset($proemail_color['link_head']) ? $proemail_color['link_head'] : ''; ?>" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="color_bg_header" class="col-sm-2 control-label"><?php echo $_language->get('entry_color'); ?></label>
                    <div class="col-sm-10">
                      <input name="proemail_color[bg_header]" data-target=".header" data-property="background-color" type="text" id="color_bg_header" class="form-control minicolors" value="<?php echo isset($proemail_color['bg_header']) ? $proemail_color['bg_header'] : ''; ?>" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $_language->get('entry_background_image'); ?></label>
                    <div class="col-sm-4">
                      <?php imageField('bg_header', $OC_V2, $_language, $thumb, $proemail_theme, $no_image); ?>
                    </div>
                    <label class="col-sm-2 control-label"><?php echo $_language->get('entry_repeat'); ?></label>
                    <div class="col-sm-4">
                      <?php imageRepeat('bg_header', $_language, $proemail_theme); ?>
                    </div>
                  </div>
                </td>
              </tr>
            </table>
          </div>
          <div class="tab-pane" id="tab-design-4">
            <table class="form">
              <tr>
                <td class="form-horizontal">
                  <h3><img style="padding-right:15px" src="view/pro_email/img/layout_body.png" alt=""/><?php echo $_language->get('text_body'); ?></h3>
                  
                  <div class="form-group">
                    <label for="color_text" class="col-sm-2 control-label"><?php echo $_language->get('entry_color_text'); ?></label>
                    <div class="col-sm-4">
                      <input name="proemail_color[text]" data-target=".content,.content p,.content td" data-property="color" type="text" id="color_text" class="form-control minicolors" value="<?php echo isset($proemail_color['text']) ? $proemail_color['text'] : ''; ?>" />
                    </div>
                    <label for="color_link" class="col-sm-2 control-label"><?php echo $_language->get('entry_color_link'); ?></label>
                    <div class="col-sm-4">
                      <input name="proemail_color[link]" data-target=".content a:not([class])" data-property="color" type="text" id="color_link" class="form-control minicolors" value="<?php echo isset($proemail_color['link']) ? $proemail_color['link'] : ''; ?>" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="color_bg_body" class="col-sm-2 control-label"><?php echo $_language->get('entry_color'); ?></label>
                    <div class="col-sm-10">
                      <input name="proemail_color[bg_body]" data-target=".main" data-property="background-color" type="text" id="color_bg_body" class="form-control minicolors" value="<?php echo isset($proemail_color['bg_body']) ? $proemail_color['bg_body'] : ''; ?>" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $_language->get('entry_background_image'); ?></label>
                    <div class="col-sm-4">
                      <?php imageField('bg_body', $OC_V2, $_language, $thumb, $proemail_theme, $no_image); ?>
                    </div>
                    <label class="col-sm-2 control-label"><?php echo $_language->get('entry_repeat'); ?></label>
                    <div class="col-sm-4">
                      <?php imageRepeat('bg_body', $_language, $proemail_theme); ?>
                    </div>
                  </div>
                </td>
              </tr>
            </table>
          </div>
          <div class="tab-pane" id="tab-design-5">
            <table class="form">
              <tr>
                <td class="form-horizontal">
                  <h3><img style="padding-right:15px" src="view/pro_email/img/layout_footer.png" alt=""/><?php echo $_language->get('text_foot'); ?></h3>
                  
                  <div class="form-group">
                    <label for="color_text_foot" class="col-sm-2 control-label"><?php echo $_language->get('entry_color_text'); ?></label>
                    <div class="col-sm-4">
                      <input name="proemail_color[text_foot]" data-target=".footer, .footer p" data-property="color" type="text" id="color_text_foot" class="form-control minicolors" value="<?php echo isset($proemail_color['text_foot']) ? $proemail_color['text_foot'] : ''; ?>" />
                    </div>
                    <label for="color_link_foot" class="col-sm-2 control-label"><?php echo $_language->get('entry_color_link'); ?></label>
                    <div class="col-sm-4">
                      <input name="proemail_color[link_foot]" data-target=".footer a:not([class])" data-property="color" type="text" id="color_link_foot" class="form-control minicolors" value="<?php echo isset($proemail_color['link_foot']) ? $proemail_color['link_foot'] : ''; ?>" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="color_bg_footer" class="col-sm-2 control-label"><?php echo $_language->get('entry_color'); ?></label>
                    <div class="col-sm-10">
                      <input name="proemail_color[bg_footer]" data-target=".footer" data-property="background-color" type="text" id="color_bg_footer" class="form-control minicolors" value="<?php echo isset($proemail_color['bg_footer']) ? $proemail_color['bg_footer'] : ''; ?>" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $_language->get('entry_background_image'); ?></label>
                    <div class="col-sm-4">
                      <?php imageField('bg_footer', $OC_V2, $_language, $thumb, $proemail_theme, $no_image); ?>
                    </div>
                    <label class="col-sm-2 control-label"><?php echo $_language->get('entry_repeat'); ?></label>
                    <div class="col-sm-4">
                      <?php imageRepeat('bg_footer', $_language, $proemail_theme); ?>
                    </div>
                  </div>
                </td>
              </tr>
            </table>
          </div>
          <div class="tab-pane" id="tab-design-6">
            <table class="form">
              <tr>
                <td class="form-horizontal">
                  <h3><img style="padding-right:15px" src="view/pro_email/img/layout_bottom.png" alt=""/><?php echo $_language->get('text_bottom'); ?></h3>
                  <div class="form-group">
                    <label for="color_text_bottom" class="col-sm-2 control-label"><?php echo $_language->get('entry_color_text'); ?></label>
                    <div class="col-sm-4">
                      <input name="proemail_color[text_bottom]" data-target=".bottom, .bottom p" data-property="color" type="text" id="color_text_bottom" class="form-control minicolors" value="<?php echo isset($proemail_color['text_bottom']) ? $proemail_color['text_bottom'] : ''; ?>" />
                    </div>
                    <label for="color_link_bottom" class="col-sm-2 control-label"><?php echo $_language->get('entry_color_link'); ?></label>
                    <div class="col-sm-4">
                      <input name="proemail_color[link_bottom]" data-target=".bottom a:not([class])" data-property="color" type="text" id="color_link_bottom" class="form-control minicolors" value="<?php echo isset($proemail_color['link_bottom']) ? $proemail_color['link_bottom'] : ''; ?>" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="bottom" class="col-sm-2 control-label"><?php echo $_language->get('entry_color'); ?></label>
                    <div class="col-sm-10">
                      <input name="proemail_color[bg_bottom]" data-target=".bottom" data-property="background-color" type="text" id="color_bg_bottom" class="form-control minicolors" value="<?php echo isset($proemail_color['bg_bottom']) ? $proemail_color['bg_bottom'] : ''; ?>" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $_language->get('entry_background_image'); ?></label>
                    <div class="col-sm-4">
                      <?php imageField('bg_bottom', $OC_V2, $_language, $thumb, $proemail_theme, $no_image); ?>
                    </div>
                    <label class="col-sm-2 control-label"><?php echo $_language->get('entry_repeat'); ?></label>
                    <div class="col-sm-4">
                      <?php imageRepeat('bg_bottom', $_language, $proemail_theme); ?>
                    </div>
                  </div>
                </td>
              </tr>
            </table>
          </div>
        </div>
        <?php echo mailPreviewPanel($_language, $order_statuses, $mail_custom, $store_id, $token, $mijourl, $languages); ?>
      </div>
      <div class="tab-pane clearfix" id="tab-2">
        <ul class="nav nav-tabs contentTabs">
          <li class="active"><a href="#tab-content-0" data-toggle="tab" class="setContentPreview"><?php echo $_language->get('text_tab_content_0'); ?></a></li>
          <li><a href="#tab-content-1" data-toggle="tab" class="setContentPreview"><?php echo $_language->get('text_tab_content_1'); ?></a></li>
          <li><a href="#tab-content-1-a" data-toggle="tab" class="setContentPreview"><?php echo $_language->get('text_tab_content_1_a'); ?></a></li>
          <li><a href="#tab-content-2" data-toggle="tab" class="setContentPreview"><?php echo $_language->get('text_tab_content_2'); ?></a></li>
          <li><a href="#tab-content-3" data-toggle="tab"><?php echo $_language->get('text_tab_content_3'); ?></a></li>
          <li><a href="#tab-content-4" data-toggle="tab" class="setContentPreview"><?php echo $_language->get('text_tab_content_4'); ?></a></li>
        </ul>
        <div class="tab-content clearfix">
          <?php include_once('pro_email_enquiry_update.tpl'); ?>
          <div class="tab-pane active" id="tab-content-0">
              
            <ul class="nav nav-pills nav-stacked col-md-2 menu-types">
              <?php $f=1; $row=0; foreach ($mail_types as $key => $item) { ?>
              <li <?php if($f) echo 'class="active"'; $f=0; ?>><a href="#tab-type-<?php echo $row; ?>" data-type="<?php echo $key; ?>" data-toggle="pill"><?php echo $_language->get('text_type_' . $key); ?></a></li>
              <?php $row++; } ?>
            </ul>
            <div class="tab-content col-md-10">
              <?php mailEditorForm('type', $mail_types, $languages, $_language, $from_name_placeholder, $from_email_placeholder); ?>
            </div>
          </div>
          <div class="tab-pane" id="tab-content-1">
            <ul class="nav nav-pills nav-stacked col-md-2 menu-statuses">
              <?php $f=1; $row=0; foreach ($order_statuses as $status) { ?>
              <li <?php if($f) echo 'class="active"'; $f=0; ?>><a href="#tab-status-<?php echo $row; ?>" data-type="order.update|<?php echo $status['order_status_id']; ?>" data-toggle="pill" <?php if(isset($status['color']) && $status['color'] != '000000') { ?>style="color:#<?php echo $status['color']; ?>"<?php } ?>><?php echo $status['name']; ?></a></li>
              <?php $row++; } ?>
            </ul>
            <div class="tab-content col-md-10">
              <?php mailEditorForm('status', $order_statuses, $languages, $_language, $from_name_placeholder, $from_email_placeholder); ?>
            </div>
          </div>
          <div class="tab-pane" id="tab-content-2">
            <ul class="nav nav-pills nav-stacked col-md-2 menu-types">
              <?php $f=1; $row=0; foreach ($mail_admin as $key => $item) { ?>
              <li <?php if($f) echo 'class="active"'; $f=0; ?>><a href="#tab-admin-type-<?php echo $row; ?>" data-type="<?php echo $key; ?>" data-toggle="pill"><?php echo $_language->get('text_type_' . $key); ?></a></li>
              <?php $row++; } ?>
            </ul>
            <div class="tab-content col-md-10">
              <?php mailEditorForm('admin', $mail_admin, $languages, $_language, $from_name_placeholder, $from_email_placeholder); ?>
            </div>
          </div>
          <div class="tab-pane" id="tab-content-3">
            <ul class="nav nav-tabs nav-language">
            <?php $f=1; foreach ($languages as $language) { ?>
              <li class="tab-lang-<?php echo $language['language_id']; ?> <?php if($f) echo 'active'; $f=0; ?>"><a data-lang="<?php echo $language['language_id']; ?>" href=".tab-lang-<?php echo $language['language_id']; ?>"><img src="<?php echo $language['image']; ?>" alt=""/> <?php echo $language['name']; ?></a></li>
            <?php } ?>
            </ul>
            <div class="tab-content tab-language">
              <?php $f=1; foreach ($languages as $language) { ?>
              <div class="form-horizontal tab-lang-<?php echo $language['language_id']; ?> tab-pane <?php if($f) echo ' active'; $f=0; ?>">
                <?php foreach (array('top', 'header', 'footer', 'bottom') as $type) { ?>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><?php echo $_language->get('text_type_common.'.$type); ?><br/>
                  <img style="padding-top:14px;" src="view/pro_email/img/layout_<?php echo $type; ?>.png" alt=""/>
                  </label>
                  <div class="col-sm-10">
                      <?php $desc = isset($proemail_content['common.'.$type]['content'][$language['language_id']]) ? $proemail_content['common.'.$type]['content'][$language['language_id']] : '';
                      if(defined('JPATH_MIJOSHOP_OC')) {
                        echo MijoShop::get('base')->editor()->display("proemail_type[common".$type."][content][".$language['language_id']."]", $desc, '97% !important', '320', '50', '11');
                       } else { ?>
                      <textarea name="proemail_type[common.<?php echo $type; ?>][content][<?php echo $language['language_id']; ?>]" id="proemail_type-common_<?php echo $type; ?>-<?php echo $language['language_id']; ?>" class="editorInit" style="height:86px; width:100%"><?php echo $desc; ?></textarea>
                      <?php } ?>
                  </div>
                </div>
                <?php } ?>
              </div>
              <?php } ?>
            </div>
            <div class="form-group">
              <div class="col-sm-2"></div>
              <div class="col-sm-10 alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $_language->get('text_warning_layout_zones'); ?></div>
            </div>
          </div>
          
          <div class="tab-pane" id="tab-content-4">
            <?php if (empty($mail_custom)) { ?>
              <div style="padding: 30px; margin-bottom: 30px; background-color: #F3F3F3; border-radius:5px;">
                <h3 style="margin-bottom:20px"><?php echo $_language->get('info_title_custom_mail'); ?></h3>
                <?php echo $_language->get('info_msg_custom_mail'); ?>
              </div>
            <?php } else { ?>
            <ul class="nav nav-pills nav-stacked col-md-2 menu-custom">
              <?php $f=1; $row=0; foreach ($mail_custom as $key => $item) { ?>
              <li <?php if($f) echo 'class="active"'; $f=0; ?>><a href="#tab-custom-<?php echo $row; ?>" data-type="<?php echo $key; ?>" data-toggle="pill"><?php echo $item['name']; ?></a></li>
              <?php $row++; } ?>
            </ul>
            <div class="tab-content col-md-10">
              <btn style="position:absolute;right:15px;" class="btn btn-default info-btn pull-right" data-info="custom_mail" data-target="#modal-info" data-toggle="modal" type="button"><i class="fa fa-info"></i>&nbsp;&nbsp;<?php echo $_language->get('text_btn_custom_binder'); ?></btn>
              <?php mailEditorForm('custom', $mail_custom, $languages, $_language, $from_name_placeholder, $from_email_placeholder); ?>
            </div>
            <?php } ?>
          </div>
         
        </div>
        
        <div class="contentPreviewDisplay">
        <?php echo mailPreviewPanel($_language, $order_statuses, $mail_custom, $store_id, $token, $mijourl, $languages); ?>
        </div>
		</div>
    <div class="tab-pane" id="tab-3">
			<ul class="nav nav-pills nav-stacked col-md-2">
        <li class="active"><a href="#tab-module-2" data-toggle="pill"><?php echo $_language->get('tab_module_prodad'); ?></a></li>
        <li><a href="#tab-module-1" data-toggle="pill"><?php echo $_language->get('tab_module_invoice'); ?></a></li>
      </ul>
      <div class="tab-content col-md-10">
        <div class="tab-pane form-horizontal" id="tab-module-1">
          <div class="form-group">
            <label class="col-sm-2 control-label" style="padding-top:20px;"><span data-toggle="tooltip" title="<?php echo $_language->get('entry_total_tax_i'); ?>"><?php echo $_language->get('entry_total_tax'); ?></span></label>
            <div class="col-sm-10">
              <input class="switch" type="checkbox"  id="proemail_total_tax" name="proemail_total_tax" value="1" <?php echo !empty($proemail_total_tax) ? 'checked="checked"':''; ?>/>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" style="padding-top:20px;"><span data-toggle="tooltip" title="<?php echo $_language->get('entry_customer_comment_i'); ?>"><?php echo $_language->get('entry_customer_comment'); ?></span></label>
            <div class="col-sm-10">
              <input class="switch" type="checkbox"  id="proemail_total_tax" name="proemail_customer_comment" value="1" <?php echo !empty($proemail_customer_comment) ? 'checked="checked"':''; ?>/>
            </div>
          </div>
          <?php if ($OC_V2) { ?>
           <div class="form-group">
            <label class="col-sm-2 control-label" style="padding-top:20px;"><span data-toggle="tooltip" title="<?php echo $_language->get('entry_custom_fields_i'); ?>"><?php echo $_language->get('entry_custom_fields'); ?></span></label>
            <div class="col-sm-10">
              <?php if (!$custom_fields) { echo $_language->get('entry_custom_fields_empty'); } ?>
              <?php foreach ($custom_fields as $item) { ?>
              <div>
                <span><?php echo $item['name']; ?></span>
                <input class="switch" type="checkbox"  id="proemail_custom_fields_<?php echo $item['custom_field_id']; ?>" name="proemail_custom_fields[]" value="<?php echo $item['custom_field_id']; ?>" <?php echo in_array($item['custom_field_id'], (array) $_config->get('proemail_custom_fields')) ? 'checked="checked"':''; ?>/>
              </div>
              <?php } ?>
            </div>
          </div>
          <?php } ?>
        </div>
        <div class="tab-pane active" id="tab-module-2">
          <ul class="nav nav-tabs">
            <?php $f=1; foreach (array('featured','latest') as $pad_type) { ?>
            <li class="<?php if($f) echo 'active'; $f=0; ?>"><a href="#tab-prodad-<?php echo $pad_type; ?>" data-toggle="tab"><?php echo $_language->get('tab_prod_ad_'.$pad_type); ?></a></li>
            <?php } ?>
          </ul>
          <div class="tab-content">
            <div class="tab-pane form-horizontal" id="tab-prodad-latest">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-padlat-width"><?php echo $_language->get('entry_img_size'); ?></label>
                <div class="col-sm-5">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-arrows-h"></i></span>
                    <input type="text" name="proemail_mod_product[latest][width]" value="<?php echo isset($proemail_mod_product['latest']['width']) ? $proemail_mod_product['latest']['width'] : ''; ?>" placeholder="<?php echo $_language->get('entry_width'); ?>" id="input-padlat-width" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-5">
                  <div style="position:absolute;margin-left:-19px;top:10px;" class="hidden-xs">x</div>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-arrows-v"></i></span>
                    <input type="text" name="proemail_mod_product[latest][height]" value="<?php echo isset($proemail_mod_product['latest']['height']) ? $proemail_mod_product['latest']['height'] : ''; ?>" placeholder="<?php echo $_language->get('entry_height'); ?>" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-padlat-limit"><span data-toggle="tooltip" title="<?php echo $_language->get('entry_item_number_i'); ?>"><?php echo $_language->get('entry_item_number'); ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="proemail_mod_product[latest][limit]" value="<?php echo isset($proemail_mod_product['latest']['limit']) ? $proemail_mod_product['latest']['limit'] : ''; ?>" placeholder="3" id="input-padlat-limit" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-padlat-limit"><span data-toggle="tooltip" title="<?php echo $_language->get('entry_per_row_i'); ?>"><?php echo $_language->get('entry_per_row'); ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="proemail_mod_product[latest][per_row]" value="<?php echo isset($proemail_mod_product['latest']['per_row']) ? $proemail_mod_product['latest']['per_row'] : ''; ?>" placeholder="3" id="input-padlat-limit" class="form-control" />
                  </div>
                </div>
              <div class="form-group well">
                <div class="col-sm-1"><i class="fa fa-info fa-2x pull-right"></i></div>
                <div class="col-sm-10"><?php echo $_language->get('info_product_ad_latest'); ?></div>
              </div>
            </div>
            <div class="tab-pane active form-horizontal" id="tab-prodad-featured">
               <div class="form-group">
                <label class="col-sm-2 control-label" for="input-padlat-width"><?php echo $_language->get('entry_img_size'); ?></label>
                <div class="col-sm-5">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-arrows-h"></i></span>
                    <input type="text" name="proemail_mod_product[featured][width]" value="<?php echo isset($proemail_mod_product['featured']['width']) ? $proemail_mod_product['featured']['width'] : ''; ?>" placeholder="<?php echo $_language->get('entry_width'); ?>" id="input-padlat-width" class="form-control" />
                  </div>
                </div>
                <div class="col-sm-5">
                  <div style="position:absolute;margin-left:-19px;top:10px;" class="hidden-xs">x</div>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-arrows-v"></i></span>
                    <input type="text" name="proemail_mod_product[featured][height]" value="<?php echo isset($proemail_mod_product['featured']['height']) ? $proemail_mod_product['featured']['height'] : ''; ?>" placeholder="<?php echo $_language->get('entry_height'); ?>" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-padlat-limit"><span data-toggle="tooltip" title="<?php echo $_language->get('entry_item_number_i'); ?>"><?php echo $_language->get('entry_item_number'); ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="proemail_mod_product[featured][limit]" value="<?php echo isset($proemail_mod_product['featured']['limit']) ? $proemail_mod_product['featured']['limit'] : ''; ?>" placeholder="3" id="input-padlat-limit" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-padlat-limit"><span data-toggle="tooltip" title="<?php echo $_language->get('entry_per_row_i'); ?>"><?php echo $_language->get('entry_per_row'); ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="proemail_mod_product[featured][per_row]" value="<?php echo isset($proemail_mod_product['featured']['per_row']) ? $proemail_mod_product['featured']['per_row'] : ''; ?>" placeholder="3" id="input-padlat-limit" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-product"><span data-toggle="tooltip" title="<?php echo $_language->get('help_product'); ?>"><?php echo $_language->get('entry_product'); ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="prod_adv_autocomp" value="" placeholder="<?php echo $_language->get('entry_product'); ?>" id="input-product" class="form-control" />
                  <div id="featured-product" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php if(!empty($mod_product_ad_products)) foreach ($mod_product_ad_products as $product) { ?>
                    <div id="featured-product<?php echo $product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product['name']; ?>
                      <input type="hidden" name="proemail_mod_product[featured][product][]" value="<?php echo $product['product_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group well">
                <div class="col-sm-2"><i class="fa fa-info fa-2x pull-right"></i></div>
                <div class="col-sm-10"><?php echo $_language->get('info_product_ad_featured'); ?></div>
              </div>
            </div>
          </div>
        </div>
		</div>
		</div>
    <div class="tab-pane" id="tab-4">
      <ul class="nav nav-pills nav-stacked col-md-2">
        <li class="active"><a href="#tab-config-1" data-toggle="pill"><?php echo $_language->get('tab_config_1'); ?></a></li>
        <li><a href="#tab-config-2" data-toggle="pill"><?php echo $_language->get('tab_config_2'); ?></a></li>
        <li><a href="#tab-config-10" data-toggle="pill"><?php echo $_language->get('tab_config_10'); ?></a></li>
      </ul>
      <div class="tab-content col-md-10">
        <div class="tab-pane active" id="tab-config-1">
          <ul class="nav nav-tabs nav-language">
            <?php $f=1; foreach ($languages as $language) { ?>
            <li class="tab-lang-<?php echo $language['language_id']; ?> <?php if($f) echo 'active'; $f=0; ?>"><a href=".tab-lang-<?php echo $language['language_id']; ?>"><img src="<?php echo $language['image']; ?>" alt=""/> <?php echo $language['name']; ?></a></li>
            <?php } ?>
            </ul>
            <div class="tab-content tab-language">
              <?php $f=1; foreach ($languages as $language) { ?>
              <div class="tab-lang-<?php echo $language['language_id']; ?> tab-pane <?php if($f) echo ' active'; $f=0; ?>">
              <table class="form">
                <tr>
                  <td><?php echo $_language->get('entry_from'); ?></td>
                  <td class="container-fluid">
                    <div class="col-md-6" style="padding-left:0">
                    <input type="text" name="proemail_from_name[<?php echo $language['language_id']; ?>]" value="<?php echo !empty($proemail_from_name[$language['language_id']]) ? $proemail_from_name[$language['language_id']] : ''; ?>" class="form-control" placeholder="<?php echo $from_name_placeholder['default']; ?>"/>
                    </div>
                    <div class="col-md-6" style="padding-right:0">
                    <input type="text" name="proemail_from_email[<?php echo $language['language_id']; ?>]" value="<?php echo !empty($proemail_from_email[$language['language_id']]) ? $proemail_from_email[$language['language_id']] : ''; ?>" class="form-control" placeholder="<?php echo $from_email_placeholder['default']; ?>"/>
                    </div>
                  </td>
                </tr>
              </table>
              </div>
              <?php } ?>
            </div>
        </div>
        <div class="tab-pane form-horizontal" id="tab-config-2">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-padlat-width"><span data-toggle="tooltip" title="<?php echo $_language->get('entry_admin_layout_i'); ?>"><?php echo $_language->get('entry_admin_layout'); ?></span></label>
            <div class="col-sm-10">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                <select name="proemail_admin_layout" class="form-control">
                  <option value=""><?php echo $_language->get('text_layout_default'); ?></option>
                  <option value="_" <?php if($proemail_admin_layout == '_') echo 'selected'; ?>><?php echo $_language->get('text_layout_opencart'); ?></option>
                  <option disabled>--------------------------------------</option>
                  <?php foreach ($layouts as $layout) { ?>
                  <option value="<?php echo $layout['value']; ?>" <?php if($proemail_admin_layout == $layout['value']) echo 'selected'; ?>><?php echo $layout['name']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-padlat-width"><span data-toggle="tooltip" title="<?php echo $_language->get('entry_bcc_forward_i'); ?>"><?php echo $_language->get('entry_bcc_forward'); ?></span></label>
            <div class="col-sm-10">
              <?php if (empty($phpmailer_installed)) { ?><div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $_language->get('error_phpmailer_required'); ?></div><?php } ?>
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-mail-forward"></i></span>
                <input type="text" name="proemail_bcc_forward" value="<?php echo isset($proemail_bcc_forward) ? $proemail_bcc_forward : ''; ?>" placeholder="" id="input-padlat-width" class="form-control" <?php if (empty($phpmailer_installed)) echo 'disabled="disabled"'; ?>/>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="tab-config-10">
          <table class="form">
            <tr>
              <td>
                <button type="button" class="btn btn-default btn-xs info-btn" data-toggle="modal" data-target="#modal-info" data-info="reset_content"><i class="fa fa-info"></i></button>
              <?php echo $_language->get('entry_reset_content'); ?></td>
              <td>
                <button type="button" class="btn btn-warning" id="restoreContent"><i class="fa fa-repeat"></i> <?php echo $_language->get('btn_reset_content'); ?></button>
              </td>
            </tr>
          </table>
        </div>
      </div>
		</div>
    
		<div class="tab-pane" id="tab-about">
			<table class="form about">
				<tr>
					<td colspan="2" style="text-align:center;padding:30px 0 50px"><!--<img src="<?php echo $_img_path; ?>logo.gif" alt="Pro Email Template"/>-->Pro Email Template</td>
				</tr>
				<tr>
					<td>Version</td>
					<td><?php echo $module_version; ?> - <?php echo $module_type; ?></td>
				</tr>
				<tr>
					<td>Free support</td>
					<td>Top quality module guaranteed.<br/>In case of bug, incompatibility, or if you want a new feature, just contact me on my mail.</td>
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
      </form>
	  </div>
	  </div>
  </div>
</div>
<?php if (version_compare(VERSION, '2.3', '>=')) { ?>
<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/summernote/opencart.js"></script> 
<?php } ?>
<script type="text/javascript"><!--
jQuery('input.switch').iToggle({easing:'swing', speed:200});

jQuery('body').on('click','.nav-language  a',function(e){
  e.preventDefault();
  if (!jQuery(this).parent().hasClass('active')) {
    jQuery('.tab-language > .tab-pane, .nav-language > li').removeClass('active');
    jQuery('.tab-language > .tab-pane'+jQuery(this).attr('href')+', .nav-language > li'+jQuery(this).attr('href')).addClass('active');
  }
  return false;
});

jQuery('select[name=store]').change(function(){
	document.location = 'index.php?<?php echo $mijourl; ?>route=module/pro_email&<?php echo $token; ?>&store_id='+jQuery(this).val();
});

jQuery('.minicolors').minicolors({
  theme: 'bootstrap',
  //changeDelay: 100,
  change: function(hex, opacity) {
      jQuery('form > div.tab-content > div.active .mail-preview iframe').contents().find(jQuery(this).attr('data-target')).css(jQuery(this).attr('data-property'), hex);
    }
});

var $selectize_layouts = jQuery('#selectize_layouts').selectize({
grid: true,
labelField: 'name',
valueField: 'value',
options: <?php echo $json_layouts; ?>,
render: {
  option: function(item, escape) {
    return '<div class="select-box">' +
      '<img src="' + item.img + '" alt=""/><div>' + escape(item.name) +
    '</div></div>';
  }
},
onChange: function(value) {
  reloadPreview();
}
});

var $selectize_colors = jQuery('#selectize_colors').selectize({
grid: true,
labelField: 'name',
valueField: 'value',
create:false,
options: <?php echo $color_schemes; ?>,
render: {
  option: function(item, escape) {
    return '<div class="scheme_option">' +
      '<div class="bg" style="background:' + item.scheme.bg_page + '">' +
        '<div class="head" style="background:' + item.scheme.bg_header + ';color:' + item.scheme.text_head + ';"><?php echo $_language->get('text_header'); ?></div>' +
        '<div class="body" style="background:' + item.scheme.bg_body + ';color:' + item.scheme.text + ';">' + 
          '<?php echo $_language->get('text_body'); ?> - <span style="color:' + item.scheme.link + '"><?php echo $_language->get('text_link'); ?></span>' +
          '<br/><div class="button" style="background:' + item.scheme.btn + ';color:' + item.scheme.btn_text + ';"><?php echo $_language->get('text_button'); ?></div>' +
        '</div>' +
        '<div class="foot" style="background:' + item.scheme.bg_footer + ';color:' + item.scheme.text_foot + '"><?php echo $_language->get('text_foot'); ?></div>' +
      '</div>' +
    '</div>';
  },
  item: function(item, escape) {
    return '<div class="scheme_option">' +
      '<span style="background:' + item.scheme.bg_page + '">&nbsp;</span>' +
      '<span style="background:' + item.scheme.bg_header + ';color:' + item.scheme.text_head + '">&nbsp;</span>' +
      '<span style="background:' + item.scheme.bg_body + ';color:' + item.scheme.text + ';">&nbsp;</span>' +
      '<span style="background:' + item.scheme.btn + ';color:' + item.scheme.btn_text + ';">&nbsp;</span>' +
      '</div>';
  },
},
onChange: function(value) {
  item = JSON.parse(value);
  jQuery.each(item, function(field, value) {
    jQuery('input[name="proemail_color['+field+']"]').val(value);
    jQuery('input[name="proemail_color['+field+']"]').trigger('keyup');
  });
  
  //reloadPreview();
}
});

jQuery('.selectize').selectize();

jQuery('body').on('change', '.changeReload', function () {
  reloadPreview();
});

jQuery('body').on('keyup', '.delayChangeReload', function () {
  delay(function(){
    reloadPreview();
  }, 500);
});

var delay = (function(){
  var timer = 0;
  return function(callback, ms){
  clearTimeout (timer);
  timer = setTimeout(callback, ms);
 };
})();
 
jQuery(window).load(function() {
  jQuery('.imgChangeReload').on('load', function () {
    reloadPreview();
  });
});

function reloadPreview() {
  jQuery('.preview-content iframe').fadeOut();
  
  <?php if (!$OC_V2 && !defined('JPATH_MIJOSHOP_OC')) { ?>
  for(var instanceName in CKEDITOR.instances){ CKEDITOR.instances[instanceName].updateElement(); }
  <?php } else { ?>
    jQuery('.summernote').each(function(){
    <?php if (version_compare(VERSION, '2.2', '>=')) { ?>
      jQuery(this).val(jQuery(this).summernote('code'));
    <?php } else { ?>
      jQuery(this).val(jQuery(this).code());
    <?php } ?>
    });
  <?php } ?>
  type = jQuery('form > div.tab-content > div.active .preview-type').val();
  lang = jQuery('form > div.tab-content > div.active .preview-lang').val();
	jQuery.ajax({
		url: 'index.php?<?php echo $mijourl; ?>route=module/pro_email/previewParams&type='+type+'&lang='+lang+'&<?php echo $token; ?>',
    type:'POST',
		data: jQuery('#form').serialize() + '&' + jQuery.param({'no-image': jQuery('input[name="no-image"]').val()}),
		dataType: 'text',
		success: function(data){
      //jQuery('.mail-preview iframe').contents().find('html').html(data);
      //jQuery(target).closest('.mail-preview').find('iframe').attr('src', 'index.php?route=module/pro_email/preview&<?php echo $token; ?>');
      jQuery('form > div.tab-content > div.active .mail-preview iframe').attr('src', 'index.php?<?php echo $mijourl; ?>route=module/pro_email/preview&type='+type+'&store_id=<?php echo $store_id; ?>&<?php echo $token; ?>');
      //jQuery('.preview-refresh i').removeClass('fa-spin');
		}
	});
}

jQuery('#restoreContent').on('click', function (e) {
  e.preventDefault();
  if(confirm('<?php echo $_language->get('text_confirm_restore_content'); ?>')) {
    document.location = 'index.php?<?php echo $mijourl; ?>route=module/pro_email/restore_content&<?php echo $token; ?>&store_id=<?php echo $store_id; ?>';
  }
});

jQuery('#save_scheme').on('click', function (e) {
  e.preventDefault();

  jQuery.ajax({
		url: 'index.php?<?php echo $mijourl; ?>route=module/pro_email/saveColorScheme&<?php echo $token; ?>',
    type:'POST',
		data: jQuery('#form').serialize(),
		dataType: 'text',
		success: function(data){
      alert(data);
		}
	});
});

jQuery('.mail-preview iframe').on('load', function () {
  jQuery('.preview-content iframe').fadeIn();
});

jQuery('.contentTabs li').on('click', function() {
  jQuery('.contentPreviewDisplay').hide();
});

jQuery('.setContentPreview').on('shown.bs.tab', function() {
  if(jQuery('#tab-2 > div.tab-content > div.active li.active a[data-type]').length) {
    jQuery('form > div.tab-content > div.active .preview-type').val(jQuery('#tab-2 > div.tab-content > div.active li.active a[data-type]').attr('data-type'));
    jQuery('.contentPreviewDisplay').show();
    reloadPreview();
  }
});

jQuery('.tabChangeReload').on('shown.bs.tab', function() {
  reloadPreview();
});

jQuery('.menu-types, .menu-statuses, .menu-custom').on('click', 'a', function() {
  jQuery('form > div.tab-content > div.active .preview-type').val(jQuery(this).attr('data-type'));
  reloadPreview();
});

jQuery('body').on('click', 'a[data-lang]', function() {
  jQuery('form > div.tab-content > div.active .preview-lang').val(jQuery(this).attr('data-lang'));
  reloadPreview();
});

jQuery('body').on('change', '.preview-type,.preview-lang', function() {
  reloadPreview();
});

jQuery('body').on('click', '.preview-refresh', function() {
  reloadPreview();
});

jQuery('body').on('click', '.preview-image', function() {
  if (jQuery('input[name="no-image"]').val() == 0) {
    val = '1';
    jQuery(this).css('color', '#aaa');
  } else {
    val = '0';
    jQuery(this).css('color', '#555');
  }
  
  jQuery('input[name="no-image"]').val(val);
  reloadPreview();
});

jQuery('body').on('click', '.preview-size .btn', function() {
  jQuery('.preview-size .btn').removeClass('active');
  jQuery(this).addClass('active');
  jQuery('.mail-preview iframe').animate({width: jQuery(this).attr('data-size')});
});
--></script>
<?php if (!$OC_V2 && !defined('JPATH_MIJOSHOP_OC')) { ?>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<?php } ?>
<!-- order status -->
<?php if (!defined('JPATH_MIJOSHOP_OC')) { ?>
<script type="text/javascript"><!--
jQuery('.editorInit').appear(function() {
var editor = jQuery(this).attr('id');
  <?php if ($OC_V2) { ?>
    <?php if (defined('JOOCART_SITE_URL')) { ?>
      //jQuery('#'+editor).summernote({});
      tinyMCE.init({
        selector: '#'+editor,
        plugins : "table link image code hr charmap autolink lists importcss print preview anchor searchreplace visualblocks fullscreen insertdatetime media contextmenu",
        toolbar: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | formatselect | bullist numlist | outdent indent | undo redo | link unlink anchor image insertdatetime media hr table | subscript superscript charmap | print preview searchreplace visualblocks code",
        removed_menuitems: "newdocument",
        content_css : "<?php echo JOOCART_SITE_URL; ?>templates/system/css/editor.css",
        file_browser_callback : function (field_name, url, type, win) {
              ocFileManager(field_name, url, type, win);
          },
      });
    <?php } else { ?>
      jQuery('#'+editor).addClass('summernote');
      
      if (editor.search('proemail_type-common') === 0) {
        var summerHeight = 150;
      } else {
        var summerHeight = 300;
      }
      
      <?php if (version_compare(VERSION, '2.2', '>=')) { ?>
        jQuery('#'+editor).summernote({
          disableDragAndDrop: true,
          height: summerHeight,
          emptyPara: '',
          toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'image', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
          ],
          buttons: {
              image: function() {
              var ui = $.summernote.ui;

              // create button
              var button = ui.button({
                contents: '<i class="fa fa-image" />',
                tooltip: $.summernote.lang[$.summernote.options.lang].image.image,
                click: function () {
                  $('#modal-image').remove();
                
                  $.ajax({
                    url: 'index.php?route=common/filemanager&<?php echo $token; ?>',
                    dataType: 'html',
                    beforeSend: function() {
                      $('#button-image i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
                      $('#button-image').prop('disabled', true);
                    },
                    complete: function() {
                      $('#button-image i').replaceWith('<i class="fa fa-upload"></i>');
                      $('#button-image').prop('disabled', false);
                    },
                    success: function(html) {
                      $('body').append('<div id="modal-image" class="modal">' + html + '</div>');
                      
                      $('#modal-image').modal('show');
                      
                      $('#modal-image').delegate('a.thumbnail', 'click', function(e) {
                        e.preventDefault();
                        
                        $('#'+editor).summernote('insertImage', $(this).attr('href'));
                        
                        $('#modal-image').modal('hide');
                      });
                    }
                  });						
                }
              });
            
              return button.render();
            }
          }
        });
      <?php } else { /* OC 2.0 - 2.1 */ ?>
        jQuery('#'+editor).summernote({
          height: summerHeight
        });
        
        // Override summernotes image manager
        $('button[data-event=\'showImageDialog\']').attr('data-toggle', 'image').removeAttr('data-event');
      <?php } ?>
        
    <?php } ?>
  
  <?php } else { ?>
    CKEDITOR.replace(editor, {
      height:'300px',
      filebrowserBrowseUrl: 'index.php?<?php echo $mijourl; ?>route=common/filemanager&<?php echo $token; ?>',
      filebrowserImageBrowseUrl: 'index.php?<?php echo $mijourl; ?>route=common/filemanager&<?php echo $token; ?>',
      filebrowserFlashBrowseUrl: 'index.php?<?php echo $mijourl; ?>route=common/filemanager&<?php echo $token; ?>',
      filebrowserUploadUrl: 'index.php?<?php echo $mijourl; ?>route=common/filemanager&<?php echo $token; ?>',
      filebrowserImageUploadUrl: 'index.php?<?php echo $mijourl; ?>route=common/filemanager&<?php echo $token; ?>',
      filebrowserFlashUploadUrl: 'index.php?<?php echo $mijourl; ?>route=common/filemanager&<?php echo $token; ?>'
    });
  <?php } ?>
});
//--></script> 
<?php } ?>

<script type="text/javascript"><!--
<?php /*
var feed_row = <?php echo count($proemail_feeds)+1; ?>;
function addShipping() {	
	html  = '<div id="tab-feed-' + feed_row + '" class="tab-pane">';
	html += '  <table class="form">';
	html += '    <tr>';
	html += '      <td><?php echo $_language->get('entry_feed_title'); ?></td>';
	html += '      <td><input type="text" name="proemail_feeds[' + feed_row + '][title]" value="" class="form-control"/></td>';
	html += '    </tr>';
	html += '  </table>'; 
	html += '</div>';
	
	jQuery('#tab-0 > .tab-content').append(html);
	
	jQuery('#feed-add').before('<li><a href="#tab-feed-' + feed_row + '" id="shipping-' + feed_row + '" data-toggle="pill"><?php echo $_language->get('text_add_feed'); ?> ' + feed_row + '&nbsp;<i class="fa fa-minus-circle" onclick="jQuery(\'#proemail_feeds a:first\').trigger(\'click\'); jQuery(\'#shipping-' + feed_row + '\').remove(); jQuery(\'#tab-feed-' + feed_row + '\').remove(); return false;"></i></a></li>');
	
	jQuery('#shipping-' + feed_row).trigger('click');
	
	feed_row++;
}
*/ ?>
//--></script> 
<script type="text/javascript"><!--
jQuery('body').on('click', '.info-btn', function() {
  jQuery('#modal-info .modalContent').html('<div style="text-align:center"><img src="view/pro_email/img/loader.gif" alt=""/></div>');
  jQuery('#modal-info .modalContent').load('index.php?<?php echo $mijourl; ?>route=module/pro_email/modal_info&<?php echo $token; ?>', {'info': jQuery(this).attr('data-info')});
});
jQuery('body').on('click', '.modalContent', function(e) {
  if (jQuery(e.target).attr('class') == 'modalContent') {
    jQuery('#modal-info').modal('hide');
  }
});
//--></script> 
<!-- /custom blocks -->
<?php if(!$OC_V2) { ?>
<script type="text/javascript"><!--
function image_upload(field, thumb) {
	jQuery('#dialog').remove();
	
	jQuery('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?<?php echo $mijourl; ?>route=common/filemanager&<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	jQuery('#dialog').dialog({
		title: '<?php echo htmlspecialchars($_language->get('text_image_manager'), ENT_QUOTES, 'UTF-8'); ?>',
		close: function (event, ui) {
			if (jQuery('#' + field).attr('value')) {
				jQuery.ajax({
					url: 'index.php?<?php echo $mijourl; ?>route=common/filemanager/image&<?php echo $token; ?>&image=' + encodeURIComponent(jQuery('#' + field).val()),
					dataType: 'text',
					success: function(data) {
						jQuery('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
            reloadPreview();
					}
				});
			}
		},	
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
};
<?php if (defined('JPATH_MIJOSHOP_OC') && !$OC_V2) { ?>
jQuery('select').css("max-height", "");
<?php } ?>
//--></script> 
<?php } ?>
<script type="text/javascript"><!--
$('.button-upload').on('click', function() {
  var file_input = $(this).parent().parent().find('input.fileinput');
  
	$('#form-upload').remove();
	
	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');
  
	$('#form-upload input[name=\'file\']').trigger('click');
	
	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);		
			
			$.ajax({
				url: 'index.php?route=module/pro_email/fileupload&<?php echo $token; ?>',
				type: 'post',		
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,		
				beforeSend: function() {
					$('#button-upload').button('loading');
				},
				complete: function() {
					$('#button-upload').button('reset');
				},	
				success: function(json) {
					if (json['error']) {
						alert(json['error']);
					}
								
					if (json['success']) {
						alert(json['success']);
						
						//$('input[name=\'filename\']').attr('value', json['filename']);
						file_input.attr('value', json['filename']);
					}
				},			
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});

// featured autocomplete
<?php if (version_compare(VERSION, '2', '>=')) { ?>
$('input[name=\'prod_adv_autocomp\']').autocomplete({
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id
					}
				}));
			}
		});
	},
	select: function(item) {
		$('input[name=\'prod_adv_autocomp\']').val('');
		
		$('#featured-product' + item['value']).remove();
		
		$('#featured-product').append('<div id="featured-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="proemail_mod_product[featured][product][]" value="' + item['value'] + '" /></div>');
	}
});
<?php } else { ?>
$('input[name=\'prod_adv_autocomp\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
    $('input[name=\'prod_adv_autocomp\']').val('');
		
		$('#featured-product' + ui.item.value).remove();
		
		$('#featured-product').append('<div id="featured-product' + ui.item.value + '"><i class="fa fa-minus-circle"></i> ' + ui.item.label + '<input type="hidden" name="proemail_mod_product[featured][product][]" value="' + ui.item.value + '" /></div>');
    
		return false;
	}
});
<?php } ?>
$('#featured-product').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});
//--></script></div> 
<?php if (defined('JPATH_MIJOSHOP_OC') && !$OC_V2) { /*fix for old mijoshop*/ ?>
<style>
input.form-control{display: block; width: 100%!important; height: 34px; padding: 6px 12px; font-size: 14px; line-height: 1.42857; color: #555; background-color: #FFF; background-image: none; border: 1px solid #CCC; border-radius: 4px; box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.075) inset; transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;}
.form-inline input.form-control{width: auto!important;}
.form-horizontal .control-label { float: left; width: 160px; padding-top: 5px; text-align: right;}
select{height: 34px; font-size: 14px; line-height: 1.42857; padding: 6px 12px!important; max-height: 34px!important; }
select:-moz-focusring {color:transparent; text-shadow:0 0 0 #000;}
.breadcrumb_oc li{float:left; margin-right:20px; list-style-type:none;}
.breadcrumb_oc{padding-bottom:20px;}
</style>
<?php } ?>
<?php if (defined('JOOCART_SITE_URL')) { ?>
<style>
.modal-body{max-height:600px; overflow:auto;}
input.minicolors{padding-bottom:8px;}
#stores select{height:24px;}
</style>
<?php } ?>
<?php if (version_compare(VERSION, '2', '<')) { ?>
<style>
.cke *{box-sizing:content-box!important;}
</style>
<?php } ?>
<?php echo $footer; ?>
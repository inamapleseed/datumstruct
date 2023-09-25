<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-ncat" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <?php if ($success) { ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php } ?>
	 <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> Edit</h3>
      </div>
      <div class="panel-body">
	  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-ncat" class="form-horizontal">
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="ncategory_status" id="input-status" class="form-control">
                <?php if ($ncategory_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div> 
		 <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><i class="fa fa-cogs"></i> <?php echo $text_bsettings; ?></a></li>
            <li><a href="#tab-disqus" data-toggle="tab"><i class="fa fa-comments"></i> <?php echo $tab_disqus; ?></a></li>
            <li><a href="#tab-fb" data-toggle="tab"><i class="fa fa-facebook-square"></i> <?php echo $tab_facebook; ?></a></li>
            <li><a href="#tab-other" data-toggle="tab"><i class="fa fa-info-circle"></i> <?php echo $tab_other; ?></a></li>
          </ul>
		   <div class="tab-content">
			<div class="tab-pane active in" id="tab-general">
				<table class="form">
					<tr>
              <td><?php echo $text_bnews_order; ?></td>
              <td><?php if ($bnews_order) { ?>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_order" value="1" checked="checked" />
                <?php echo $text_yess; ?></div>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_order" value="0" />
                <?php echo $text_noo; ?></div>
                <?php } else { ?>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_order" value="1" />
                <?php echo $text_yess; ?></div>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_order" value="0" checked="checked" />
                <?php echo $text_noo; ?></div>
                <?php } ?></td>
					</tr>
					<tr>
              <td><?php echo $text_bnews_display_style; ?></td>
              <td><?php if ($bnews_display_style) { ?>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_display_style" value="1" checked="checked" />
                <?php echo $text_bnews_dscols; ?></div>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_display_style" value="0" />
                <?php echo $text_bnews_dscol; ?></div>
                <?php } else { ?>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_display_style" value="1" />
                <?php echo $text_bnews_dscols; ?></div>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_display_style" value="0" checked="checked" />
                <?php echo $text_bnews_dscol; ?></div>
                <?php } ?></td>
					</tr>	
					<tr>
              <td><?php echo $text_tplpick; ?></td>
              <td><?php if ($bnews_tplpick) { ?>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_tplpick" value="1" checked="checked" />
                <?php echo $text_yes; ?></div>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_tplpick" value="0" />
                <?php echo $text_no; ?></div>
                <?php } else { ?>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_tplpick" value="1" />
                <?php echo $text_yes; ?></div>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_tplpick" value="0" checked="checked" />
                <?php echo $text_no; ?></div>
                <?php } ?></td>
					</tr>
					<tr>
				<td><?php echo $text_bnews_dselements; ?></td>
				<td>
			<div class="radiowraper bes">
				<?php if ($bnews_display_elements_s === 'none') { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="name" checked="checked" />
                    <?php echo $text_bnews_dse_name; ?>
                <?php } elseif (in_array("name", $bnews_display_elements)) { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="name" checked="checked" />
                    <?php echo $text_bnews_dse_name; ?>
                <?php } else { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="name" />
                    <?php echo $text_bnews_dse_name; ?>
                <?php } ?>
            </div>
			<div class="radiowraper bes">
				<?php if ($bnews_display_elements_s === 'none') { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="image" checked="checked" />
                    <?php echo $text_bnews_dse_image; ?>
                <?php } elseif (in_array("image", $bnews_display_elements)) { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="image" checked="checked" />
                    <?php echo $text_bnews_dse_image; ?>
                <?php } else { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="image" />
                    <?php echo $text_bnews_dse_image; ?>
                <?php } ?>
            </div>
			<div class="radiowraper bes">
				<?php if ($bnews_display_elements_s === 'none') { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="da" checked="checked" />
                    <?php echo $text_bnews_dse_da; ?>
                <?php } elseif (in_array("da", $bnews_display_elements)) { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="da" checked="checked" />
                    <?php echo $text_bnews_dse_da; ?>
                <?php } else { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="da" />
                    <?php echo $text_bnews_dse_da; ?>
                <?php } ?>
            </div>
			<div class="radiowraper bes">
				<?php if ($bnews_display_elements_s === 'none') { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="du" checked="checked" />
                    <?php echo $text_bnews_dse_du; ?>
                <?php } elseif (in_array("du", $bnews_display_elements)) { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="du" checked="checked" />
                    <?php echo $text_bnews_dse_du; ?>
                <?php } else { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="du" />
                    <?php echo $text_bnews_dse_du; ?>
                <?php } ?>
            </div>
			<div class="radiowraper bes">
				<?php if ($bnews_display_elements_s === 'none') { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="author" checked="checked" />
                    <?php echo $text_bnews_dse_author; ?>
                <?php } elseif (in_array("author", $bnews_display_elements)) { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="author" checked="checked" />
                    <?php echo $text_bnews_dse_author; ?>
                <?php } else { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="author" />
                    <?php echo $text_bnews_dse_author; ?>
                <?php } ?>
            </div>
			<div class="radiowraper bes">
				<?php if ($bnews_display_elements_s === 'none') { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="category" checked="checked" />
                    <?php echo $text_bnews_dse_category; ?>
                <?php } elseif (in_array("category", $bnews_display_elements)) { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="category" checked="checked" />
                    <?php echo $text_bnews_dse_category; ?>
                <?php } else { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="category" />
                    <?php echo $text_bnews_dse_category; ?>
                <?php } ?>
            </div>
			<div class="radiowraper bes">
				<?php if ($bnews_display_elements_s === 'none') { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="desc" checked="checked" />
                    <?php echo $text_bnews_dse_desc; ?>
                <?php } elseif (in_array("desc", $bnews_display_elements)) { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="desc" checked="checked" />
                    <?php echo $text_bnews_dse_desc; ?>
                <?php } else { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="desc" />
                    <?php echo $text_bnews_dse_desc; ?>
                <?php } ?>
            </div>
			<div class="radiowraper bes">
				<?php if ($bnews_display_elements_s === 'none') { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="button" checked="checked" />
                    <?php echo $text_bnews_dse_button; ?>
                <?php } elseif (in_array("button", $bnews_display_elements)) { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="button" checked="checked" />
                    <?php echo $text_bnews_dse_button; ?>
                <?php } else { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="button" />
                    <?php echo $text_bnews_dse_button; ?>
                <?php } ?>
            </div>
			<div class="radiowraper bes">
				<?php if ($bnews_display_elements_s === 'none') { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="com" checked="checked" />
                    <?php echo $text_bnews_dse_com; ?>
                <?php } elseif (in_array("com", $bnews_display_elements)) { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="com" checked="checked" />
                    <?php echo $text_bnews_dse_com; ?>
                <?php } else { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="com" />
                    <?php echo $text_bnews_dse_com; ?>
                <?php } ?>
            </div>
			<div class="radiowraper bes">
				<?php if ($bnews_display_elements_s === 'none') { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="custom1" checked="checked" />
                    <?php echo $text_bnews_dse_custom; ?> 1
                <?php } elseif (in_array("custom1", $bnews_display_elements)) { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="custom1" checked="checked" />
                    <?php echo $text_bnews_dse_custom; ?> 1
                <?php } else { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="custom1" />
                    <?php echo $text_bnews_dse_custom; ?> 1
                <?php } ?>
            </div>
			<div class="radiowraper bes">
				<?php if ($bnews_display_elements_s === 'none') { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="custom2" checked="checked" />
                    <?php echo $text_bnews_dse_custom; ?> 2
                <?php } elseif (in_array("custom2", $bnews_display_elements)) { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="custom2" checked="checked" />
                    <?php echo $text_bnews_dse_custom; ?> 2
                <?php } else { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="custom2" />
                    <?php echo $text_bnews_dse_custom; ?> 2
                <?php } ?>
            </div>
			<div class="radiowraper bes">
				<?php if ($bnews_display_elements_s === 'none') { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="custom3" checked="checked" />
                    <?php echo $text_bnews_dse_custom; ?> 3
                <?php } elseif (in_array("custom3", $bnews_display_elements)) { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="custom3" checked="checked" />
                    <?php echo $text_bnews_dse_custom; ?> 3
                <?php } else { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="custom3" />
                    <?php echo $text_bnews_dse_custom; ?> 3
                <?php } ?>
            </div>
			<div class="radiowraper bes">
				<?php if ($bnews_display_elements_s === 'none') { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="custom4" checked="checked" />
                    <?php echo $text_bnews_dse_custom; ?> 4
                <?php } elseif (in_array("custom4", $bnews_display_elements)) { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="custom4" checked="checked" />
                    <?php echo $text_bnews_dse_custom; ?> 4
                <?php } else { ?>
                    <input type="checkbox" name="ncategory_bnews_display_elements[]" value="custom4" />
                    <?php echo $text_bnews_dse_custom; ?> 4
                <?php } ?>
            </div>
			</td>
			</tr>
			<tr>
	<td><?php echo $text_bnews_image; ?></td>
    <td>
    <?php echo $text_bwidth; ?> <?php if ($bnews_image_width) { ?>
	<input type="text" name="ncategory_bnews_image_width" value="<?php echo $bnews_image_width; ?>" size="3" />
	<?php } else { ?>
	<input type="text" name="ncategory_bnews_image_width" value="80" size="3" />
	<?php } ?>   
	<?php echo $text_bheight; ?> <?php if ($bnews_image_height) { ?>
	<input type="text" name="ncategory_bnews_image_height" value="<?php echo $bnews_image_height; ?>" size="3" />
	<?php } else { ?>
	<input type="text" name="ncategory_bnews_image_height" value="80" size="3" />
	<?php } ?>
    </td></tr><tr>
    <td><?php echo $text_bnews_thumb; ?></td>
    <td>
    <?php echo $text_bwidth; ?> <?php if ($bnews_thumb_width) { ?>
	<input type="text" name="ncategory_bnews_thumb_width" value="<?php echo $bnews_thumb_width; ?>" size="3" />
	<?php } else { ?>
	<input type="text" name="ncategory_bnews_thumb_width" value="230" size="3" />
	<?php } ?>  
	<?php echo $text_bheight; ?> <?php if ($bnews_thumb_height) { ?>
	<input type="text" name="ncategory_bnews_thumb_height" value="<?php echo $bnews_thumb_height; ?>" size="3" />
	<?php } else { ?>
	<input type="text" name="ncategory_bnews_thumb_height" value="230" size="3" />
	<?php } ?>
    </td>	
			</tr>	
	<tr>
		<td><?php echo $text_bnews_catalog_limit; ?></td>
		<td>
			<?php if ($bnews_catalog_limit) { ?>
				<input type="text" name="ncategory_bnews_catalog_limit" value="<?php echo $bnews_catalog_limit; ?>" size="3" />
			<?php } else { ?>
				<input type="text" name="ncategory_bnews_catalog_limit" value="14" size="3" />
			<?php } ?>  
		</td>	
	</tr>		
	<tr>
		<td><?php echo $text_bnews_admin_limit; ?></td>
		<td>
			<?php if ($bnews_admin_limit) { ?>
				<input type="text" name="ncategory_bnews_admin_limit" value="<?php echo $bnews_admin_limit; ?>" size="3" />
			<?php } else { ?>
				<input type="text" name="ncategory_bnews_admin_limit" value="20" size="3" />
			<?php } ?>  
		</td>	
	</tr>	
	<tr>
		<td><?php echo $text_bnews_headlines_url; ?></td>
		<td>
			<?php if ($bnews_headlines_url) { ?>
				<input type="text" name="ncategory_bnews_headlines_url" value="<?php echo $bnews_headlines_url; ?>" size="25" />
			<?php } else { ?>
				<input type="text" name="ncategory_bnews_headlines_url" value="blog-headlines" size="25" />
			<?php } ?>  
		</td>	
	</tr>	
	<tr>
		<td><?php echo $text_bnews_desc_length; ?></td>
		<td>
			<?php if ($bnews_desc_length) { ?>
				<input type="text" name="ncategory_bnews_desc_length" value="<?php echo $bnews_desc_length; ?>" size="3" />
			<?php } else { ?>
				<input type="text" name="ncategory_bnews_desc_length" value="600" size="3" />
			<?php } ?>  
		</td>	
	</tr>	
    <tr>
        <td><?php echo $text_bnews_restrict_group; ?></td>
        <td>
            <?php if ($ncategory_bnews_restrictgroup) { ?>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_restrictgroup" value="1" checked="checked" />
                <?php echo $text_yes; ?></div>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_restrictgroup" value="0" />
                <?php echo $text_no; ?></div>
                <?php } else { ?>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_restrictgroup" value="1" />
                <?php echo $text_yes; ?></div>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_restrictgroup" value="0" checked="checked" />
                <?php echo $text_no; ?></div>
            <?php } ?>
        </td>   
    </tr>  
    <tr>
        <td><?php echo $text_date_format; ?></td>
        <td>
            <select name="ncategory_bnews_date_format">
                <?php foreach ($bnews_date_formats as $format => $format_text) { ?>
                    <?php if ($format == $bnews_date_format) { ?>
                        <option value="<?php echo $format; ?>" selected="selected"><?php echo $format_text; ?></option>
                    <?php } else { ?>
                        <option value="<?php echo $format; ?>"><?php echo $format_text; ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </td>   
    </tr>   
    <tr>
        <td><?php echo $text_top_menu_link; ?></td>
        <td>
            <select name="ncategory_bnews_top_link">
                <?php if ($bnews_top_link) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                <?php } ?>
            </select>
        </td>   
    </tr>  
    <tr>
        <td><?php echo $text_blog_html_editor; ?></td>
        <td>
            <select name="ncategory_bnews_html_editor">
                <?php foreach ($html_editors as $html_editor) { ?>
                    <?php if ($bnews_html_editor == $html_editor['code']) { ?>
                        <option value="<?php echo $html_editor['code']; ?>" selected="selected"><?php echo $html_editor['name']; ?></option>
                    <?php } else { ?>
                        <?php if ($html_editor['enabled']) { ?>
                            <option value="<?php echo $html_editor['code']; ?>"><?php echo $html_editor['name']; ?></option>
                        <?php } else { ?>
                            <option value="<?php echo $html_editor['code']; ?>" disabled="disabled"><?php echo $html_editor['name']; ?> (Not installed)</option>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </select>
        </td>   
    </tr>   
		</table>	
	</div>
			<div class="tab-pane" id="tab-disqus">
				<table class="form">
				<tr>
				<td><?php echo $tab_disqus_enable; ?></td>
				<td><?php if ($bnews_disqus_status) { ?>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_disqus_status" value="1" checked="checked" />
                <?php echo $text_yes; ?></div>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_disqus_status" value="0" />
                <?php echo $text_no; ?></div>
                <?php } else { ?>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_disqus_status" value="1" />
                <?php echo $text_yes; ?></div>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_disqus_status" value="0" checked="checked" />
                <?php echo $text_no; ?></div>
                <?php } ?></td>
				</tr>
				<tr>
				<td><?php echo $tab_disqus_sname; ?></td>
				<td><?php if ($bnews_disqus_sname) { ?>
						<input type="text" name="ncategory_bnews_disqus_sname" value="<?php echo $bnews_disqus_sname; ?>" size="30" />
					<?php } else { ?>
						<input type="text" name="ncategory_bnews_disqus_sname" value="short_name" size="30" />
					<?php } ?>
				</td>
				</tr>
				</table>
			</div>
			<div class="tab-pane" id="tab-fb">
				<table class="form">
					<tr>
				<td><?php echo $tab_facebook_status; ?></td>
				<td><?php if ($bnews_fbcom_status) { ?>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_fbcom_status" value="1" checked="checked" />
                <?php echo $text_yes; ?></div>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_fbcom_status" value="0" />
                <?php echo $text_no; ?></div>
                <?php } else { ?>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_fbcom_status" value="1" />
                <?php echo $text_yes; ?></div>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_fbcom_status" value="0" checked="checked" />
                <?php echo $text_no; ?></div>
                <?php } ?></td>
					</tr>
					<tr>
				<td><?php echo $tab_facebook_appid; ?></td>
				<td><?php if ($bnews_fbcom_appid) { ?>
						<input type="text" name="ncategory_bnews_fbcom_appid" value="<?php echo $bnews_fbcom_appid; ?>" size="30" />
					<?php } else { ?>
						<input type="text" name="ncategory_bnews_fbcom_appid" value="" size="30" />
					<?php } ?>
				</td>
					</tr>
					<tr>
				<td><?php echo $tab_facebook_posts; ?></td>
				<td><?php if ($bnews_fbcom_posts) { ?>
						<input type="text" name="ncategory_bnews_fbcom_posts" value="<?php echo $bnews_fbcom_posts; ?>" size="2" />
					<?php } else { ?>
						<input type="text" name="ncategory_bnews_fbcom_posts" value="10" size="2" />
					<?php } ?>
				</td>
					</tr>
					<tr>
				<td><?php echo $tab_facebook_theme ?></td>
				<td><?php if ($bnews_fbcom_theme == 'light') { ?>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_fbcom_theme" value="light" checked="checked" />
                Light</div>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_fbcom_theme" value="dark" />
                Dark</div>
                <?php } else { ?>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_fbcom_theme" value="light" />
                Light</div>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_fbcom_theme" value="dark" checked="checked" />
                Dark</div>
                <?php } ?></td>
					</tr>
				</table>
			</div>
			<div class="tab-pane" id="tab-other">
				<table class="form">
					<tr>
				<td><?php echo $text_facebook_tags; ?></td>
				<td><?php if (!$bnews_facebook_tags) { ?>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_facebook_tags" value="0" checked="checked" />
                <?php echo $text_yes; ?></div>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_facebook_tags" value="1" />
                <?php echo $text_no; ?></div>
                <?php } else { ?>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_facebook_tags" value="0" />
                <?php echo $text_yes; ?></div>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_facebook_tags" value="1" checked="checked" />
                <?php echo $text_no; ?></div>
                <?php } ?></td>
					</tr>
					<tr>
				<td><?php echo $text_twitter_tags; ?></td>
				<td><?php if (!$bnews_twitter_tags) { ?>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_twitter_tags" value="0" checked="checked" />
                <?php echo $text_yes; ?></div>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_twitter_tags" value="1" />
                <?php echo $text_no; ?></div>
                <?php } else { ?>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_twitter_tags" value="0" />
                <?php echo $text_yes; ?></div>
                <div class="radiowraper"><input type="radio" name="ncategory_bnews_twitter_tags" value="1" checked="checked" />
                <?php echo $text_no; ?></div>
                <?php } ?></td>
					</tr>
				</table>
			</div>
		</div>
    </form>
      </div>
    </div>
  </div>
  </div>
<?php echo $footer; ?>
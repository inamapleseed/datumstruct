<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-blog" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <style type="text/css">
		#sdesc1, #sdesc2, #sdesc3, #sdesc4, #sdesc5, #sdesc6, #sdesc7, #sdesc8, #sdesc9, #sdesc10 { display: none; }
	</style>
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
	  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-blog" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-seo" data-toggle="tab"><?php echo $tab_seo; ?></a></li>
            <li><a href="#tab-custom" data-toggle="tab"><?php echo $tab_custom; ?></a></li>
            <li><a href="#tab-settings" data-toggle="tab"><?php echo $tab_settings; ?></a></li>
            <li><a href="#tab-related" data-toggle="tab"><?php echo $tab_related; ?></a></li>
            <li><a href="#tab-video" data-toggle="tab"><?php echo $tab_video; ?></a></li>
            <li><a href="#tab-gallery" data-toggle="tab"><?php echo $tab_gallery; ?></a></li>
            <li><a href="#tab-design" data-toggle="tab"><?php echo $entry_layout; ?></a></li>
          </ul>
		
		<div class="tab-content">
<div class="tab-pane active" id="tab-general">
	<ul class="nav nav-tabs" id="language">
                <?php foreach ($languages as $language) { ?>
					<li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                <?php } ?>
    </ul>
	  <div class="tab-content">
		<?php foreach ($languages as $language) { ?>
			<div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
			
				<div class="form-group required">
					<label class="col-sm-2 control-label" for="input-name<?php echo $language['language_id']; ?>"><?php echo $entry_title; ?></label>
                    <div class="col-sm-10">
					  <input type="text" name="news_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset( $news_description[$language['language_id']]['title']) ? $news_description[$language['language_id']]['title'] : ''; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control" />
					  <?php if (isset($error_title[$language['language_id']])) { ?>
						<div class="text-danger"><?php echo $error_title[$language['language_id']]; ?></div>
					<?php } ?>
                    </div>
				</div>
				
                <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                    <div class="col-sm-10">
					  <textarea name="news_description[<?php echo $language['language_id']; ?>][description]" id="input-description<?php echo $language['language_id']; ?>" class="form-control 7"><?php echo isset( $news_description[$language['language_id']]['description']) ? $news_description[$language['language_id']]['description'] : ''; ?></textarea>
					  <?php if (isset($error_description[$language['language_id']])) { ?>
						<div class="text-danger"><?php echo $error_description[$language['language_id']]; ?></div>
					  <?php } ?>
                    </div>
                </div>
				
				<div style="padding-top: 5px; padding-bottom: 5px;"><h3 class="addsuperh3 active" id="hidesdesc<?php echo $language['language_id']; ?>"><?php echo $entry_addsdesc; ?></h3></div>
				<div id="sdesc<?php echo $language['language_id']; ?>">
						<div class="form-group required">
							<label class="col-sm-2 control-label" for="input-descriptions<?php echo $language['language_id']; ?>"><?php echo $entry_description2; ?></label>
							<div class="col-sm-10">
								<textarea name="news_description[<?php echo $language['language_id']; ?>][description2]" id="input-descriptions<?php echo $language['language_id']; ?>" class="form-control "><?php echo isset( $news_description[$language['language_id']]['description2']) ? $news_description[$language['language_id']]['description2'] : ''; ?></textarea>
							</div>
						</div>
				</div>
			</div>
		<?php } ?>
	  </div>
	    <div class="form-group">
                <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
                <div class="col-sm-10">
                  <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
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
		<div class="form-group">
                <label class="col-sm-2 control-label" for="input-nauthor"><?php echo $entry_nauthor; ?></label>
                <div class="col-sm-10">
                  <select name="nauthor_id" id="input-nauthor" class="form-control">
                   <?php foreach ($authors as $author) { ?>
					<?php if ($nauthor_id == $author['nauthor_id']) { ?>
						<option value="<?php echo $author['nauthor_id']; ?>" selected="selected"><?php echo $author['name']; ?></option>
					<?php } else { ?>
						<option value="<?php echo $author['nauthor_id']; ?>"><?php echo $author['name']; ?></option>
					<?php } ?>
				   <?php } ?>
                  </select>
                </div>
        </div>
</div>
<div class="tab-pane" id="tab-seo">
    <div class="form-group">
					<label class="col-sm-2 control-label" for="input-seok"><?php echo $entry_keyword; ?></label>
                    <div class="col-sm-10">
					  <input type="text" name="keyword" value="<?php echo $keyword; ?>" id="input-seok" class="form-control" />
                        <?php if ($error_keyword) { ?>
                            <div class="text-danger"><?php echo $error_keyword; ?></div>
                        <?php } ?>
                    </div>
	</div>
	<ul class="nav nav-tabs" id="languagesseo">
                <?php foreach ($languages as $language) { ?>
					<li><a href="#languageseo<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                <?php } ?>
    </ul>
   <div class="tab-content">
	<?php foreach ($languages as $language) { ?>
	 <div class="tab-pane" id="languageseo<?php echo $language['language_id']; ?>">
		
            <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_ctitle; ?></label>
                    <div class="col-sm-10">
					  <input type="text" name="news_description[<?php echo $language['language_id']; ?>][ctitle]" value="<?php echo isset( $news_description[$language['language_id']]['ctitle']) ? $news_description[$language['language_id']]['ctitle'] : ''; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
                    </div>
            </div>
			<div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_desc; ?></label>
                    <div class="col-sm-10">
					  <textarea name="news_description[<?php echo $language['language_id']; ?>][meta_desc]" rows="5" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset( $news_description[$language['language_id']]['meta_desc']) ? $news_description[$language['language_id']]['meta_desc'] : ''; ?></textarea>
                    </div>
            </div>
			<div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-key<?php echo $language['language_id']; ?>"><?php echo $entry_meta_key; ?></label>
                    <div class="col-sm-10">
					  <textarea name="news_description[<?php echo $language['language_id']; ?>][meta_key]" rows="5" id="input-meta-key<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset( $news_description[$language['language_id']]['meta_key']) ? $news_description[$language['language_id']]['meta_key'] : ''; ?></textarea>
                    </div>
            </div>
			<div class="form-group">
                    <label class="col-sm-2 control-label" for="input-ntags<?php echo $language['language_id']; ?>"><?php echo $entry_ntags; ?></label>
                    <div class="col-sm-10">
					  <input type="text" name="news_description[<?php echo $language['language_id']; ?>][ntags]" value="<?php echo isset( $news_description[$language['language_id']]['ntags']) ? $news_description[$language['language_id']]['ntags'] : ''; ?>" id="input-ntags<?php echo $language['language_id']; ?>" class="form-control" />
                    </div>
            </div>
	 </div>
	<?php } ?>
   </div>
</div>
<div class="tab-pane" id="tab-custom">
	<div class="form-group">
                <label class="col-sm-2 control-label" for="input-date-added"><?php echo $entry_datea; ?></label>
                <div class="col-sm-3">
                  <div class="input-group datetime">
					<input type="text" name="date_added" value="<?php echo $date_added; ?>"  data-date-format="YYYY-MM-DD HH:mm" id="input-date-added" class="form-control" />
                    <span class="input-group-btn">
						<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                    </span>
				  </div>
                </div>
    </div>
	<div class="form-group">
                <label class="col-sm-2 control-label" for="input-date-up"><?php echo $entry_dateu; ?></label>
                <div class="col-sm-3">
                  <div class="input-group datetime">
					<input type="text" name="date_updated" value="<?php echo $date_updated; ?>"  data-date-format="YYYY-MM-DD HH:mm" id="input-date-up" class="form-control" />
                    <span class="input-group-btn">
						<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                    </span>
				  </div>
                </div>
    </div>
	<div class="form-group">
                <label class="col-sm-2 control-label" for="input-date-p"><?php echo $entry_datep; ?></label>
                <div class="col-sm-3">
                  <div class="input-group datetime">
					<input type="text" name="date_pub" value="<?php echo $date_pub; ?>"  data-date-format="YYYY-MM-DD HH:mm" id="input-date-p" class="form-control" />
                    <span class="input-group-btn">
						<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                    </span>
				  </div>
                </div>
    </div>
	<div class="form-group">
                <label class="col-sm-2 control-label" for="input-image-2"><?php echo $entry_image2; ?></label>
                <div class="col-sm-10">
                  <a href="" id="thumb-image2" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb2; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="image2" value="<?php echo $image2; ?>" id="input-image-2" />
                </div>
    </div>
	<ul class="nav nav-tabs" id="languagesc">
                <?php foreach ($languages as $language) { ?>
					<li><a href="#languagesc<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                <?php } ?>
    </ul>
	<div class="tab-content">
	<?php foreach ($languages as $language) { ?>
	<div class="tab-pane" id="languagesc<?php echo $language['language_id']; ?>">
				<div class="form-group">
                    <label class="col-sm-2 control-label" for="input-cfield1<?php echo $language['language_id']; ?>"><?php echo $entry_cfield; ?> 1</label>
                    <div class="col-sm-10">
					  <input type="text" name="news_description[<?php echo $language['language_id']; ?>][cfield1]" value="<?php echo isset( $news_description[$language['language_id']]['cfield1']) ? $news_description[$language['language_id']]['cfield1'] : ''; ?>" id="input-cfield1<?php echo $language['language_id']; ?>" class="form-control" />
                    </div>
				</div>
				<div class="form-group">
                    <label class="col-sm-2 control-label" for="input-cfield2<?php echo $language['language_id']; ?>"><?php echo $entry_cfield; ?> 2</label>
                    <div class="col-sm-10">
					  <input type="text" name="news_description[<?php echo $language['language_id']; ?>][cfield2]" value="<?php echo isset( $news_description[$language['language_id']]['cfield2']) ? $news_description[$language['language_id']]['cfield2'] : ''; ?>" id="input-cfield2<?php echo $language['language_id']; ?>" class="form-control" />
                    </div>
				</div>
				<div class="form-group">
                    <label class="col-sm-2 control-label" for="input-cfield3<?php echo $language['language_id']; ?>"><?php echo $entry_cfield; ?> 3</label>
                    <div class="col-sm-10">
					  <input type="text" name="news_description[<?php echo $language['language_id']; ?>][cfield3]" value="<?php echo isset( $news_description[$language['language_id']]['cfield3']) ? $news_description[$language['language_id']]['cfield3'] : ''; ?>" id="input-cfield3<?php echo $language['language_id']; ?>" class="form-control" />
                    </div>
				</div>
				<div class="form-group">
                    <label class="col-sm-2 control-label" for="input-cfield4<?php echo $language['language_id']; ?>"><?php echo $entry_cfield; ?> 4</label>
                    <div class="col-sm-10">
					  <input type="text" name="news_description[<?php echo $language['language_id']; ?>][cfield4]" value="<?php echo isset( $news_description[$language['language_id']]['cfield4']) ? $news_description[$language['language_id']]['cfield4'] : ''; ?>" id="input-cfield4<?php echo $language['language_id']; ?>" class="form-control" />
                    </div>
				</div>
	</div>
	<?php } ?>
	</div>
</div>
<div class="tab-pane" id="tab-settings">
        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-filename">Brochure</label>
            <div class="col-sm-10">
                <div class="input-group">
                    <input type="text" name="mask" value="<?php echo $mask; ?>" placeholder="" id="input-filename" class="form-control" />
                    <span class="input-group-btn">
                        <button type="button" id="button-upload" data-loading-text="<?= $text_loading; ?>" class="btn btn-primary"><i class="fa fa-upload"></i> Upload</button>
                    </span> 
                    <input type="hidden" name="filename" value="<?= $filename; ?>" />
                    <input type="hidden" name="brochure_pdf_id" value="<?= $brochure_pdf_id; ?>" />
                    <input type="hidden" name="brochure_id" value="<?= $brochure_id; ?>" />
                </div>
            </div>
        </div>

			<div class="form-group">
                    <label class="col-sm-2 control-label" for="input-acom"><?php echo $entry_acom; ?></label>
                    <div class="col-sm-10">
						<select name="acom" id="input-acom" class="form-control">
							<?php if ($acom) { ?>
								<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
								<option value="0"><?php echo $text_disabled; ?></option>
							<?php } else { ?>
								<option value="1"><?php echo $text_enabled; ?></option>
								<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
							<?php } ?>
						</select>
                    </div>
			</div>
			<div class="form-group">
                    <label class="col-sm-2 control-label" for="input-sort"><?php echo $entry_sort_order; ?></label>
                    <div class="col-sm-10">
						<input type="text" name="sort_order" value="<?php echo $sort_order; ?>" id="input-sort" class="form-control"/>
                    </div>
			</div>
			<div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_category; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($ncategories as $category) { ?>
                    <div class="checkbox">
                      <label>
                        <?php if (in_array($category['ncategory_id'], $news_ncategory)) { ?>
						<input type="checkbox" name="news_ncategory[]" value="<?php echo $category['ncategory_id']; ?>" checked="checked" />
						<?php echo $category['name']; ?>
                        <?php } else { ?>
						<input type="checkbox" name="news_ncategory[]" value="<?php echo $category['ncategory_id']; ?>" />
						<?php echo $category['name']; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
			  <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">
                    <div class="checkbox">
                      <label>
                        <?php if (in_array(0, $news_store)) { ?>
						<input type="checkbox" name="news_store[]" value="0" checked="checked" />
						<?php echo $text_default; ?>
						<?php } else { ?>
						<input type="checkbox" name="news_store[]" value="0" />
						<?php echo $text_default; ?>
						<?php } ?>
                      </label>
                    </div>
                    <?php foreach ($stores as $store) { ?>
                    <div class="checkbox">
                      <label>
                        <?php if (in_array($store['store_id'], $news_store)) { ?>
						<input type="checkbox" name="news_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
						<?php echo $store['name']; ?>
						<?php } else { ?>
						<input type="checkbox" name="news_store[]" value="<?php echo $store['store_id']; ?>" />
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
                        <?php if (in_array($group['customer_group_id'], $news_group)) { ?>
                        <input type="checkbox" name="news_group[]" value="<?php echo $group['customer_group_id']; ?>" checked="checked" />
                        <?php echo $group['name']; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="news_group[]" value="<?php echo $group['customer_group_id']; ?>" />
                        <?php echo $group['name']; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php } ?>
                  </div>
                </div>
            </div>
</div>
<div class="tab-pane" id="tab-related">
			<div class="form-group">
                <label class="col-sm-2 control-label" for="input-nrelated"><?php echo $entry_nrelated; ?></label>
                <div class="col-sm-10">
				  <input type="text" name="nrelated" value="" id="input-nrelated" class="form-control"/>
                  <div id="news-nrelated" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($news_nrelated as $news_nrelated) { ?>
						<div id="news-nrelated<?php echo $news_nrelated['news_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $news_nrelated['title']; ?>
							<input type="hidden" name="news_nrelated[]" value="<?php echo $news_nrelated['news_id']; ?>" />
						</div>
                    <?php } ?>
                  </div>
                </div>
            </div>
			<div class="form-group">
                <label class="col-sm-2 control-label" for="input-related"><?php echo $entry_related; ?></label>
                <div class="col-sm-10">
				  <input type="text" name="related" value="" id="input-related" class="form-control"/>
                  <div id="news-related" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($news_related as $news_related) { ?>
						<div id="news-related<?php echo $news_related['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $news_related['name']; ?>
							<input type="hidden" name="news_related[]" value="<?php echo $news_related['product_id']; ?>" />
						</div>
                    <?php } ?>
                  </div>
                </div>
            </div>
</div>
<div class="tab-pane" id="tab-video">
 <div class="table-responsive">
	<table id="video" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-left"><?php echo $entry_video_id; ?></td>
				<td class="text-left"><?php echo $entry_video_text; ?></td>
				<td class="text-left"><?php echo $entry_video_size; ?></td>
                <td class="text-right"><?php echo $entry_sort_order; ?></td>
                <td></td>
              </tr>
            </thead>
            <?php $video_row = 0; ?>
            <?php foreach ($news_video as $video) { ?>
            <tbody id="video-row<?php echo $video_row; ?>">
              <tr>
                <td class="text-left">
					<input type="text" name="news_video[<?php echo $video_row; ?>][video]" value="<?php echo $video['video']; ?>" size="20" />
				</td>
				<td class="text-left">
					<?php $video['text'] = unserialize($video['text']); ?>
					<?php foreach ($languages as $language) { ?>
						<img style="margin: 10px;" src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
						<textarea name="news_video[<?php echo $video_row; ?>][text][<?php echo $language['language_id']; ?>]" rows="2" cols="30"><?php echo isset($video['text'][$language['language_id']]) ? $video['text'][$language['language_id']] : '' ; ?></textarea><br />
					<?php } ?>
				</td>
                <td class="text-left"><input type="text" name="news_video[<?php echo $video_row; ?>][width]" value="<?php echo $video['width']; ?>" size="3" />
				<input type="text" name="news_video[<?php echo $video_row; ?>][height]" value="<?php echo $video['height']; ?>" size="3" />
				</td>
                <td class="text-right"><input type="text" name="news_video[<?php echo $video_row; ?>][sort_order]" value="<?php echo $video['sort_order']; ?>" size="2" /></td>
                <td class="text-left"><a onclick="$('#video-row<?php echo $video_row; ?>').remove();" class="button sterge"><?php echo $button_remove; ?></a></td>
              </tr>
            </tbody>
            <?php $video_row++; ?>
            <?php } ?>
            <tfoot>
              <tr>
                <td colspan="4"></td>
                <td class="text-left"><a onclick="addVideo();" class="button"><?php echo $button_add_video; ?></a></td>
              </tr>
            </tfoot>
    </table>
 </div>
</div>
<div class="tab-pane" id="tab-gallery">
			<div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $entry_gallery_thumb; ?></label>
                    <div class="col-sm-10">
						<input type="text" name="gal_thumb_w" value="<?php echo $gal_thumb_w; ?>" class="form-control" />
						<input type="text" name="gal_thumb_h" value="<?php echo $gal_thumb_h; ?>" class="form-control" />
                    </div>
			</div>
			<div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $entry_gallery_popup; ?></label>
                    <div class="col-sm-10">
						<input type="text" name="gal_popup_w" value="<?php echo $gal_popup_w; ?>" class="form-control" />
						<input type="text" name="gal_popup_h" value="<?php echo $gal_popup_h; ?>" class="form-control" />
                    </div>
			</div>
			<div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $entry_gallery_slidert; ?></label>
                    <div class="col-sm-10">
						<select name="gal_slider_t" class="form-control" >
							<?php if ($gal_slider_t == 1) { ?>
								<option value="1" selected="selected">Classic</option>
								<option value="2">Slideshow</option>
							<?php } else { ?>
								<option value="1">Classic</option>
								<option value="2" selected="selected">Slideshow</option>
							<?php } ?>
						</select>
                    </div>
			</div>
			<div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $entry_gallery_slider; ?></label>
                    <div class="col-sm-10">
						<input type="text" name="gal_slider_w" value="<?php echo $gal_slider_w; ?>" class="form-control"/>
						<input type="text" name="gal_slider_h" value="<?php echo $gal_slider_h; ?>" class="form-control"/>
                    </div>
			</div>
 <div class="table-responsive">
	<table id="gallery" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-left"><?php echo $entry_image; ?></td>
				<td class="text-left"><?php echo $entry_gallery_text; ?></td>
                <td class="text-right"><?php echo $entry_sort_order; ?></td>
                <td></td>
              </tr>
            </thead>
            <tbody>
            <?php $image_row = 0; ?>
            <?php foreach ($news_gallery as $news_image) { ?>
            
              <tr id="image-row<?php echo $image_row; ?>">
                <td class="text-left">
				
				<a href="" id="thumb-image<?php echo $image_row; ?>-gallery" data-toggle="image" class="img-thumbnail"><img src="<?php echo $news_image['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="news_gallery[<?php echo $image_row; ?>][image]" value="<?php echo $news_image['image']; ?>" id="input-image<?php echo $image_row; ?>-gallery" />
				
				</td>
				<td class="text-left">
					<?php $news_image['text'] = unserialize($news_image['text']); ?>
					<?php foreach ($languages as $language) { ?>
						<img style="margin: 10px;" src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
						<textarea name="news_gallery[<?php echo $image_row; ?>][text][<?php echo $language['language_id']; ?>]" rows="2" cols="30"><?php echo isset($news_image['text'][$language['language_id']]) ? $news_image['text'][$language['language_id']] : '' ; ?></textarea><br />
					<?php } ?>
				</td>
                <td class="text-right"><input type="text" name="news_gallery[<?php echo $image_row; ?>][sort_order]" value="<?php echo $news_image['sort_order']; ?>" size="2" /></td>
                <td class="text-left"><a onclick="$('#image-row<?php echo $image_row; ?>').remove();" class="button btn sterge"><?php echo $button_remove; ?></a></td>
              </tr>
            
            <?php $image_row++; ?>
            <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="3"></td>
                <td class="left"><a onclick="addGallery();" class="button btn"><?php echo $button_add_image; ?></a></td>
              </tr>
            </tfoot>
          </table>
 </div>
</div>
		<div  class="tab-pane" id="tab-design">
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
						<td class="left"><?php echo $text_default; ?></td>
						<td class="left"><select name="news_layout[0]">
							<option value=""></option>
							<?php foreach ($layouts as $layout) { ?>
								<?php if (isset($news_layout[0]) && $news_layout[0] == $layout['layout_id']) { ?>
									<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
								<?php } else { ?>
									<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
								<?php } ?>
							<?php } ?>
						</select></td>
					</tr>
					<?php foreach ($stores as $store) { ?>
						<tr>
							<td class="left"><?php echo $store['name']; ?></td>
							<td class="left"><select name="news_layout[<?php echo $store['store_id']; ?>]">
								<option value=""></option>
								<?php foreach ($layouts as $layout) { ?>
									<?php if (isset($news_layout[$store['store_id']]) && $news_layout[$store['store_id']] == $layout['layout_id']) { ?>
										<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
									<?php } else { ?>
										<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</select></td>
						</tr>
					<?php } ?>
				</table>
			</div>
		</div>
</div>
</form>
</div>
</div>
</div>
<script type="text/javascript"><!--
$('input[name=\'nrelated\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/news/autocomplete&token=<?php echo $token; ?>&filter_aname=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['title'],
						value: item['news_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'nrelated\']').val('');
		
		$('#news-nrelated' + item['value']).remove();
		
		$('#news-nrelated').append('<div id="news-nrelated' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="news_nrelated[]" value="' + item['value'] + '" /></div>');	
	}	
});

$('#news-nrelated').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});
//--></script> 
<script type="text/javascript"><!--
$('input[name=\'related\']').autocomplete({
	'source': function(request, response) {
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
	'select': function(item) {
		$('input[name=\'related\']').val('');
		
		$('#news-related' + item['value']).remove();
		
		$('#news-related').append('<div id="news-related' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="news_related[]" value="' + item['value'] + '" /></div>');	
	}	
});

$('#news-related').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});

//--></script> 
<script>
<!-- Enhanced CKEditor -->
<?php if ($fm_installed == 0) { ?>
  <?php if ($ckeditor_enabled == 1) { ?>
    <script type="text/javascript">
      <?php foreach ($languages as $language) { ?>
        CKEDITOR.replace("input-description<?= $language['language_id']; ?>", { 
          baseHref: "<?= $base_url; ?>", 
          language: "<?= $language['code']; ?>", 
          filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?= $token; ?>', 
          filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?= $token; ?>', 
          filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?= $token; ?>', 
          skin : "<?= $ckeditor_skin; ?>", 
        codemirror: { theme: "<?= $codemirror_skin; ?>", }, height: 350 });
        
        CKEDITOR.replace("input-descriptions<?= $language['language_id']; ?>", { 
          baseHref: "<?= $base_url; ?>", 
          language: "<?= $language['code']; ?>", 
          filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?= $token; ?>', 
          filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?= $token; ?>', 
          filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?= $token; ?>', 
          skin : "<?= $ckeditor_skin; ?>", 
        codemirror: { theme: "<?= $codemirror_skin; ?>", }, height: 350 });
      <?php } ?> 
    </script>
  <?php } ?>
<?php } ?>
<!-- Enhanced CKEditor -->	
  
<?php if ($bnews_html_editor == 'ckeditor') { ?>

<script type="text/javascript" src="view/blog-res/ckeditor/ckeditor.js"></script> 
<script type="text/javascript">
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('input-description<?php echo $language['language_id']; ?>');
CKEDITOR.replace('input-descriptions<?php echo $language['language_id']; ?>');
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
    selector: '<?php $langcount = 0; foreach ($languages as $language) { $langcount++; if ($langcount > 1) { echo ', '; } ?>#input-description<?php echo $language['language_id']; ?>, #input-descriptions<?php echo $language['language_id']; ?><?php } ?>',
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
var image_row = <?php echo $image_row; ?>;

function addGallery() {
  let html = '';
	html += '  <tr id="image-row' + image_row + '" >';
	html += '    <td class="text-left"><a href="" id="thumb-image' + image_row + '-gallery" data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="news_gallery[' + image_row + '][image]" value="" id="input-image' + image_row + '" /></td>';
	html += '    <td class="text-left">';
		<?php foreach ($languages as $language) { ?>
			html += '	<img style="margin: 10px;" src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><textarea name="news_gallery[' + image_row  + '][text][<?php echo $language['language_id']; ?>]" rows="2" cols="30"></textarea><br />';
		<?php } ?>
	html += '</td>';
	html += '    <td class="text-right"><input type="text" name="news_gallery[' + image_row + '][sort_order]" value="" size="2" /></td>';
	html += '    <td class="text-left"><a onclick="$(\'#image-row' + image_row  + '\').remove();" class="button btn sterge"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	
	$('#gallery tbody').append(html);
	
	image_row++;
}
var video_row = <?php echo $video_row; ?>;

function addVideo() {
    html  = '<tbody id="video-row' + video_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><input type="text" name="news_video[' + video_row + '][video]" value="" size="20" /></td>';
	html += '    <td class="left">';
		<?php foreach ($languages as $language) { ?>
			html += '	<img style="margin: 10px;" src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><textarea name="news_video[' + video_row  + '][text][<?php echo $language['language_id']; ?>]" rows="2" cols="30"></textarea><br />';
		<?php } ?>
	html += '</td>';
	html += '    <td class="left"><input type="text" name="news_video[' + video_row + '][width]" value="" size="3" /><input type="text" name="news_video[' + video_row + '][height]" value="" size="3" /></td>';
	html += '    <td class="right"><input type="text" name="news_video[' + video_row + '][sort_order]" value="" size="2" /></td>';
	html += '    <td class="left"><a onclick="$(\'#video-row' + video_row  + '\').remove();" class="button sterge"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#video tfoot').before(html);
	
	video_row++;
}
//--></script> 
<script type="text/javascript"><!--
	<?php foreach ($languages as $language) { ?>
		var superh3stat<?php echo $language['language_id']; ?> = $('#hidesdesc<?php echo $language['language_id']; ?>').hasClass('active');
        $('#hidesdesc<?php echo $language['language_id']; ?>').bind('click', function() {
              if (!superh3stat<?php echo $language['language_id']; ?>) {
			  $("#sdesc<?php echo $language['language_id']; ?>").slideDown('slow');
	          $(this).addClass('active');
	          superh3stat<?php echo $language['language_id']; ?> = true;
	          } else {
			  $("#sdesc<?php echo $language['language_id']; ?>").slideUp('slow');
	          $(this).removeClass('active');
	          superh3stat<?php echo $language['language_id']; ?> = false;
	          }
        });

        $("#sdesc<?php echo $language['language_id']; ?>").slideDown('slow');
	<?php } ?>
//--></script> 
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});
//--></script> 
<script type="text/javascript"><!--
$('#language a:first').tab('show');
$('#languagesseo a:first').tab('show');
$('#languagesc a:first').tab('show');
//--></script>
</div>

<script type="text/javascript">
    $('#button-upload').on('click', function() {
        $('#form-upload').remove();
        
        $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');
        
        $('#form-upload input[name=\'file\']').trigger('click');
        
        if (typeof timer != 'undefined') {
            clearInterval(timer);
        }
        
        timer = setInterval(function() {
            if ($('#form-upload input[name=\'file\']').val() != '') {
                clearInterval(timer);       
                
                $.ajax({
                    url: 'index.php?route=catalog/download/upload&token=<?php echo $token; ?>',
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
                            
                            $('input[name=\'filename\']').val(json['filename']);
                            $('input[name=\'mask\']').val(json['mask']);
                        }
                    },          
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        }, 500);
    });
            $('input[name=\'download\']').autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: 'index.php?route=catalog/download/autocomplete&token=<?= $token; ?>&filter_name=' +  encodeURIComponent(request),
                    dataType: 'json',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                label: item['name'],
                                value: item['download_id']
                            }
                        }));
                    }
                });
            },
            'select': function(item) {
                $('input[name=\'download\']').val('');
                
                $('#product-download' + item['value']).remove();
                
                $('#product-download').append('<div id="product-download' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_download[]" value="' + item['value'] + '" /></div>');
            }
        });
        
        $('#product-download').delegate('.fa-minus-circle', 'click', function() {
            $(this).parent().remove();
        });
        
</script>
<?php echo $footer; ?> 
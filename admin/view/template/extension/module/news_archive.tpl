<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-na" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-na" class="form-horizontal">

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="news_archive_status" id="input-status" class="form-control">
                <?php if ($news_archive_status) { ?>
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
            <label class="col-sm-2 control-label"><?php echo $text_build; ?></label>
            <div class="col-sm-10">
              <select name="news_archive_build" class="form-control">
                <?php if ($news_archive_build) { ?>
							<option value="1" selected="selected"><?php echo $text_yes; ?></option>
							<option value="0"><?php echo $text_no; ?></option>
						<?php } else { ?>
							<option value="1"><?php echo $text_yes; ?></option>
							<option value="0" selected="selected"><?php echo $text_no; ?></option>
						<?php } ?>
              </select>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_archive_date; ?></label>
            <div class="col-sm-10">
              <select name="news_archive_date" class="form-control">
						<?php if ($news_archive_date == "da") { ?>
							<option value="da" selected="selected"><?php echo $text_da; ?></option>
						<?php } else { ?>
							<option value="da"><?php echo $text_da; ?></option>
						<?php } ?>
						<?php if ($news_archive_date == "du") { ?>
							<option value="du" selected="selected"><?php echo $text_du; ?></option>
						<?php } else { ?>
							<option value="du"><?php echo $text_du; ?></option>
						<?php } ?>
						<?php if ($news_archive_date == "dp") { ?>
							<option value="dp" selected="selected"><?php echo $text_dp; ?></option>
						<?php } else { ?>
							<option value="dp"><?php echo $text_dp; ?></option>
						<?php } ?>
              </select>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_jan; ?></label>
            <div class="col-sm-10">
              <?php foreach ($languages as $language) { ?>
					<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
					
						<input class="form-control" type="text" name="news_archive_months[jan][<?php echo $language['language_id']; ?>]" value="<?php echo isset($news_archive_months['jan'][$language['language_id']]) && $news_archive_months['jan'][$language['language_id']] ? $news_archive_months['jan'][$language['language_id']] : 'January'; ?>" size="150" /><br />
					<?php } ?>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_feb; ?></label>
            <div class="col-sm-10">
					<?php foreach ($languages as $language) { ?>
					<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
					
						<input class="form-control" type="text" name="news_archive_months[feb][<?php echo $language['language_id']; ?>]" value="<?php echo isset($news_archive_months['feb'][$language['language_id']]) && $news_archive_months['jan'][$language['language_id']] ? $news_archive_months['feb'][$language['language_id']] : 'February'; ?>" size="150" /><br />
					<?php } ?>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_march; ?></label>
            <div class="col-sm-10">
					<?php foreach ($languages as $language) { ?>
					<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
					
						<input class="form-control" type="text" name="news_archive_months[march][<?php echo $language['language_id']; ?>]" value="<?php echo isset($news_archive_months['march'][$language['language_id']]) && $news_archive_months['march'][$language['language_id']] ? $news_archive_months['march'][$language['language_id']] : 'March'; ?>" size="150" /><br />
					<?php } ?>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_april; ?></label>
            <div class="col-sm-10">
					<?php foreach ($languages as $language) { ?>
					<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
					
						<input class="form-control" type="text" name="news_archive_months[april][<?php echo $language['language_id']; ?>]" value="<?php echo isset($news_archive_months['april'][$language['language_id']]) && $news_archive_months['april'][$language['language_id']] ? $news_archive_months['april'][$language['language_id']] : 'April'; ?>" size="150" /><br />
					<?php } ?>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_may; ?></label>
            <div class="col-sm-10">
					<?php foreach ($languages as $language) { ?>
					<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
					
						<input class="form-control" type="text" name="news_archive_months[may][<?php echo $language['language_id']; ?>]" value="<?php echo isset($news_archive_months['may'][$language['language_id']]) && $news_archive_months['may'][$language['language_id']] ? $news_archive_months['may'][$language['language_id']] : 'May'; ?>" size="150" /><br />
					<?php } ?>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_june; ?></label>
            <div class="col-sm-10">
					<?php foreach ($languages as $language) { ?>
					<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
					
						<input class="form-control" type="text" name="news_archive_months[june][<?php echo $language['language_id']; ?>]" value="<?php echo isset($news_archive_months['june'][$language['language_id']]) && $news_archive_months['june'][$language['language_id']] ? $news_archive_months['june'][$language['language_id']] : 'June'; ?>" size="150" /><br />
					<?php } ?>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_july; ?></label>
            <div class="col-sm-10">
					<?php foreach ($languages as $language) { ?>
					<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
					
						<input class="form-control" type="text" name="news_archive_months[july][<?php echo $language['language_id']; ?>]" value="<?php echo isset($news_archive_months['july'][$language['language_id']]) && $news_archive_months['july'][$language['language_id']] ? $news_archive_months['july'][$language['language_id']] : 'July'; ?>" size="150" /><br />
					<?php } ?>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_aug; ?></label>
            <div class="col-sm-10">
					<?php foreach ($languages as $language) { ?>
					<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
					
						<input class="form-control" type="text" name="news_archive_months[aug][<?php echo $language['language_id']; ?>]" value="<?php echo isset($news_archive_months['aug'][$language['language_id']]) && $news_archive_months['aug'][$language['language_id']] ? $news_archive_months['aug'][$language['language_id']] : 'August'; ?>" size="150" /><br />
					<?php } ?>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_sep; ?></label>
            <div class="col-sm-10">
					<?php foreach ($languages as $language) { ?>
					<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
					
						<input class="form-control" type="text" name="news_archive_months[sep][<?php echo $language['language_id']; ?>]" value="<?php echo isset($news_archive_months['sep'][$language['language_id']]) && $news_archive_months['sep'][$language['language_id']] ? $news_archive_months['sep'][$language['language_id']] : 'September'; ?>" size="150" /><br />
					<?php } ?>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_oct; ?></label>
            <div class="col-sm-10">
					<?php foreach ($languages as $language) { ?>
					<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
					
						<input class="form-control" type="text" name="news_archive_months[oct][<?php echo $language['language_id']; ?>]" value="<?php echo isset($news_archive_months['oct'][$language['language_id']]) && $news_archive_months['oct'][$language['language_id']] ? $news_archive_months['oct'][$language['language_id']] : 'October'; ?>" size="150" /><br />
					<?php } ?>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_nov; ?></label>
            <div class="col-sm-10">
					<?php foreach ($languages as $language) { ?>
					<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
					
						<input class="form-control" type="text" name="news_archive_months[nov][<?php echo $language['language_id']; ?>]" value="<?php echo isset($news_archive_months['nov'][$language['language_id']]) && $news_archive_months['nov'][$language['language_id']] ? $news_archive_months['nov'][$language['language_id']] : 'November'; ?>" size="150" /><br />
					<?php } ?>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_dec; ?></label>
            <div class="col-sm-10">
					<?php foreach ($languages as $language) { ?>
					<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
					
						<input class="form-control" type="text" name="news_archive_months[dec][<?php echo $language['language_id']; ?>]" value="<?php echo isset($news_archive_months['dec'][$language['language_id']]) && $news_archive_months['dec'][$language['language_id']] ? $news_archive_months['dec'][$language['language_id']] : 'December'; ?>" size="150" /><br />
					<?php } ?>
            </div>
          </div>
    </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
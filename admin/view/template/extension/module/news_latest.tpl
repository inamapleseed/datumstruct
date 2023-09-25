<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-news" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <?php if ($istemplate == 'yes') { ?>
    <div class="alert alert-info">
        On this page by filling the information bellow you will create a new Latest Articles module which will appear in the modules list so you can edit it later and When editting a layout you will be able to add this module you have created to it. The Module Name field below is meant to help you locate this particular module you have created on the layout page (this name will not be the name of the module in frontend).<button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } else { ?>
    <div class="alert alert-info">
        On this page you can edit this instance module that you have created previously from the main Latest Article module. The Module Name field below is meant to help you locate this particular module you have created on the layout page (this name will not be the name of the module in frontend).<button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?> <?php if ($istemplate == 'yes') { ?> - <b>Create</b><?php } else { ?> - <b>Edit</b><?php } ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-news" class="form-horizontal">
         
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
            <label class="col-sm-2 control-label" for="input-limit"><?php echo $entry_limit; ?></label>
            <div class="col-sm-10">
              <input type="text" name="news_limit" value="<?php echo $news_limit; ?>" placeholder="<?php echo $entry_limit; ?>" id="input-limit" class="form-control" />
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-ncat"><?php echo $entry_cat; ?></label>
            <div class="col-sm-10">
              <select name="ncategory_id" id="input-ncat" class="form-control">
                  <?php if ($ncategory_id == 'all') { ?>
					 <option value="all" selected="selected"><?php echo $entry_nocat; ?></option>
				  <?php } else { ?>
					 <option value="all"><?php echo $entry_nocat; ?></option>
				  <?php } ?>
				  <?php if ($ncategories){ ?><option value="" disabled="disabled">By Categories:</option><?php } ?>
				  <?php foreach ($ncategories as $ncategory ){ ?>
						 <?php if ($ncategory['ncategory_id'] == $ncategory_id) { ?>
								 <option value="<?php echo $ncategory['ncategory_id']; ?>" selected="selected"><?php echo $ncategory['name']; ?></option>
						 <?php } else { ?>
								 <option value="<?php echo $ncategory['ncategory_id']; ?>"><?php echo $ncategory['name']; ?></option>
						 <?php } ?>
				  <?php } ?>
              </select>
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
        </form>
  </div>
</div>
</div>
</div>
<?php echo $footer; ?>
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
    <?php if ($error_warning) {?>
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
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-ftp-host"><?php echo $entry_localfilesystem_alias; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="alias" value="<?php echo isset($alias)?$alias:''; ?>" placeholder="<?php echo $entry_localfilesystem_alias; ?>" id="input-ftp-host" class="form-control" />
                  <?php if (isset($error_localfilesystem_alias)) { ?>
                  <div class="text-danger"><?php echo $error_localfilesystem_alias; ?></div>
                  <?php } ?>
                  <?php if (isset($error_alias_exist)) { ?>
                  <div class="text-danger"><?php echo $error_alias_exist; ?></div>
                  <?php } ?>                  
                  
                </div>
              </div>

              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-localfilesystem-path"><span data-toggle="tooltip" data-html="true" title="<?php echo htmlspecialchars($help_localfilesystem_path); ?>"><?php echo $entry_localfilesystem_path; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="path" value="<?php echo isset($path)?$path:DIR_IMAGE."catalog/"; ?>" placeholder="<?php echo $entry_localfilesystem_path; ?>" id="input-localfilesystem-path" class="form-control" />
                  <?php if (isset($error_localfilesystem_path)) { ?>
                  <div class="text-danger"><?php echo $error_localfilesystem_path; ?></div>
                  <?php } ?>                  
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-localfilesystem-URL"><span data-toggle="tooltip" data-html="true" title="<?php echo htmlspecialchars($help_localfilesystem_URL); ?>"><?php echo $entry_localfilesystem_URL; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="URL" value="<?php echo isset($URL)?$URL:HTTPS_CATALOG.'image/catalog/' ?>" placeholder="<?php echo $entry_localfilesystem_URL; ?>" id="input-localfilesystem-URL" class="form-control" />
                </div>
              </div>              
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_localfilesystem_status; ?></label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <input type="radio" name="status" value="1" <?php if ((isset($status) && $status) || !isset($status)) { ?> checked="checked" <?php } ?> />
                    <?php echo $text_yes; ?>
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="status" value="0" <?php if (isset($status) && $status == 0){ ?> checked="checked" <?php } ?> />
                    <?php echo $text_no; ?>
                  </label>
                </div>
              </div>    
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
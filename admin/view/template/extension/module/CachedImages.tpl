<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-CachedImages" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label">Total Images:</label>
            <label class="col-sm-2 control-label"><?php echo $images_total ?></label>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">Total Cache Images:</label>
            <label class="col-sm-2 control-label"><?php echo $images_cache_total ?></label>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">Total Product Images:</label>
            <label class="col-sm-2 control-label"><?php echo $images_product_total ?></label>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">You Can Delete The Cached Images:</label>
            <a href="<?php echo $delete_cached_image ?>" class="btn btn-danger" style="margin-left:12.5%; margin-top:1%">Delete</a>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">You Can Optimize The Cached Images:</label>
            <a href="<?php echo $optimize_cached_image ?>" class="btn btn-success" style="margin-left:12.5%; margin-top:1%">Optimize</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
  <link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
  <script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>  
  <script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script></div>
<?php echo $footer; ?>
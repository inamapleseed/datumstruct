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
              <input type="text" name="page_name" value="<?php echo $page_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
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

          <div class="form-group">
              <label class="col-sm-2 control-label" for="input-value">Page</label>
              <div class="col-sm-10">
                <select name="value" id="input-value" class="form-control" onchange="updateText(this);" >
                  <?php foreach($route_sets as $set){ ?>
                    <optgroup label="<?= $set['optgroup']; ?>">
                      <?php foreach($set['options'] as $opt){ ?>
                        <option 
                          <?= $value==$opt['value']?'selected':''; ?>
                          value="<?= $opt['value']; ?>"
                          data-route="<?= $opt['route']; ?>"
                          data-query="<?= $opt['query']; ?>"
                          ><?= $opt['name']; ?></option>
                      <?php } ?>
                    </optgroup>
                  <?php } ?>
                </select>
              </div>
              <input type="hidden" name="route" value="<?= $route; ?>" />
              <input type="hidden" name="query" value="<?= $query; ?>" />
              <script>
                $('#input-value').change();
                function updateText(el){
                  var v = $(el).val();
                  var opt = $('#input-value option[value="'+v+'"]');
                  var route = opt.attr('data-route');
                  var query = opt.attr('data-query');
                  $('input[name="route"]').val(route);
                  $('input[name="query"]').val(query);
                }
              </script>
            </div>

          <div class="form-group">
              <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
              <div class="col-sm-10">
                  <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?= $image_thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="image" value="<?= $image; ?>" id="input-image" />
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-2 control-label" for="input-image-mobile">Mobile <?php echo $entry_image; ?></label>
              <div class="col-sm-10">
                  <a href="" id="thumb-image-mobile" data-toggle="image" class="img-thumbnail"><img src="<?= $mobile_image_thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="mobile_image" value="<?= $mobile_image; ?>" id="input-image-mobile" />
              </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 
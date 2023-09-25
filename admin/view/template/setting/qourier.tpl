<?= $header, $column_left; ?>

<div id="content">
    
    <div class="page-header">
            
        <div class="container-fluid">
          <div class="pull-right">
                <button type="submit" id="button-save" form="form-qourier" data-toggle="tooltip" title="<?= $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
          </div>
             
                <h1><?= $heading_title; ?></h1>
          
                <ul class="breadcrumb">
                    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    
                        <li><a href="<?= $breadcrumb['href']; ?>"><?= $breadcrumb['text']; ?></a></li>
                    
                    <?php } ?>
                </ul>
          
        </div>
        
  </div>
  
  <div class="container-fluid">
    
    <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?= $error_warning; ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php } ?>
    
    <?php if ($success) { ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?= $success; ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php } ?>
    
    <div class="panel panel-default">
        
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?= $text_edit; ?></h3>
      </div>
      
      <div class="panel-body">
        
        <form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="form-qourier" class="form-horizontal">
            
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-qourier-status"><?= $entry_qourier_status; ?></label>
                <div class="col-sm-10">
                    <select name="qourier_status" value="" id="input-qourier-status" class="form-control">
                        <option value="0" <?= $qourier_status==0?'selected':''; ?> ><?= $text_disabled; ?></option>
                        <option value="1" <?= $qourier_status==1?'selected':''; ?> ><?= $text_enabled; ?></option>
                    </select>
                </div>
            </div>
            
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-qourier-mode"><?= $entry_qourier_mode; ?></label>
                <div class="col-sm-10">
                    <select name="qourier_mode" value="" id="input-qourier-mode" class="form-control">
                        <option value="staging" <?= !$qourier_mode=='staging'?'selected':''; ?> ><?= $mode_staging; ?></option>
                        <option value="live" <?= $qourier_mode=='live'?'selected':''; ?> ><?= $mode_live; ?></option>
                    </select>
                </div>
            </div>
            
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-qourier-staging-url"><?= $entry_qourier_staging_url; ?></label>
                <div class="col-sm-10">
                    <input type="text" name="qourier_staging_url" value="<?= $qourier_staging_url; ?>" id="input-qourier-staging-url" class="form-control" />
                    <?php if($error_qourier_staging_url){ ?>
                        <div class="text-danger">
                            <?= $error_qourier_staging_url; ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-qourier-live-url"><?= $entry_qourier_live_url; ?></label>
                <div class="col-sm-10">
                    <input type="text" name="qourier_live_url" value="<?= $qourier_live_url; ?>" id="input-qourier-live-url" class="form-control" />
                    <?php if($error_qourier_live_url){ ?>
                        <div class="text-danger">
                            <?= $error_qourier_live_url; ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-qourier-staging-api"><?= $entry_qourier_staging_api; ?></label>
                <div class="col-sm-10">
                    <input type="text" name="qourier_staging_api" value="<?= $qourier_staging_api; ?>" id="input-qourier-staging-api" class="form-control" />
                    <?php if($error_qourier_staging_api){ ?>
                        <div class="text-danger">
                            <?= $error_qourier_staging_api; ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-qourier-live-api"><?= $entry_qourier_live_api; ?></label>
                <div class="col-sm-10">
                    <input type="text" name="qourier_live_api" value="<?= $qourier_live_api; ?>" id="input-qourier-live-api" class="form-control" />
                    <?php if($error_qourier_live_api){ ?>
                        <div class="text-danger">
                            <?= $error_qourier_live_api; ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            
        </form>
        
      </div>
      
    </div>
    
  </div>
  
</div>

<?= $footer; ?>
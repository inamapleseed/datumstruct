<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
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
    
      <div class="pull-right">
        <button type="submit" form="form-ppexpress" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <?php if (isset($error['error_warning'])) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error['error_warning']; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i>Module settings</h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-multiimage" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-volumes" data-toggle="tab"><?php echo $tab_volume; ?></a></li>
            <li><a href="#tab-modules" data-toggle="tab"><?php echo $tab_module; ?></a></li>  
            <li><a href="#tab-about" data-toggle="tab"><?php echo $tab_help; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
             <fieldset>
              <legend><?php echo 'General Settings'; ?></legend>

              <div class="tab-pane active" id="tab-api-details">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-2">
                  <select name="pim_status" id="input-status" class="form-control">
                    <?php if ($pim_status) { ?>
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
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_miu_patch; ?></label>
                <div class="col-sm-2">
                  <select name="pim_miu" id="input-status" class="form-control">
                    <?php if ($pim_miu) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>          
              </div>
              </fieldset>
              <fieldset>
              <legend><?php echo 'Server Settings'; ?></legend>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_delete_def_image; ?></label>
                <div class="col-sm-4">
                  <select name="pim_deletedef" id="input-status" class="form-control">
                  <option value="0" <?php if ($pim_deletedef == 0){ echo " selected";}?>><?php echo $text_no;?></option>
                  <option value="1" <?php if ($pim_deletedef<>0 ){ echo " selected";}?>><?php echo $text_yes;?></option>
                  </select>
                </div>
              </div>                  
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_copyOverwrite; ?></label>
                <div class="col-sm-4">
                  <select name="pim_copyOverwrite" id="input-status" class="form-control">
                  <?php if ($pim_copyOverwrite) { ?>
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
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_uploadOverwrite; ?></label>
                <div class="col-sm-4">
                  <select name="pim_uploadOverwrite" id="input-status" class="form-control">
                  <?php if ($pim_uploadOverwrite) { ?>
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
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_uploadMaxSize; ?></label>
                <div class="col-sm-2">
                   <input type="text" class="form-control" name="pim_uploadMaxSize" value="<?php echo $pim_uploadMaxSize; ?>" size="4" />
                </div>
                <div class="col-sm-2"><select name="pim_uploadMaxType" id="input-status" class="form-control">
                  <option value="M" <?php if ($pim_uploadMaxType == 'M' || !$pim_uploadMaxType) { echo " selected";}?>>MegaBytes (MB)</option>
                  <option value="K" <?php if ($pim_uploadMaxType == 'K') { echo " selected";}?>>KiloBytes (KB)</option>
                  </select>
                </div>                
              </div>                   
              </fieldset>
              <fieldset>
                <legend><?php echo 'View Settings'; ?></legend>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_language; ?></label>
                  <div class="col-sm-4">
                    <select name="pim_language"  class="form-control">
                    <option value=""> EN </option>
                    <?php foreach ($langs as $l) {?>
                        <option value="<?php echo $l;?>" <?php if ($l==$pim_language){ echo " selected";}?>><?php echo strtoupper($l);?></option>
                    <?php } ?>
                  </select>
                  </div>
                </div>  
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_dimensions; ?></label>
                  <div class="col-sm-6">
                     <div class="col-sm-3">
                    <input type="text" class="form-control" name="pim_width" value="<?php echo $pim_width; ?>" size="4" /> </div> <div class="col-sm-1">x</div> <div class="col-sm-3"><input type="text" class="form-control" name="pim_height" value="<?php echo $pim_height; ?>" size="4" /></div>
                  </div>
                </div>                     
              </fieldset>
            </div>
            <div class="tab-pane" id="tab-volumes">
              <div class="tab-pane">
                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <td class="text-left"><?php echo $column_name; ?></td>
                        <td class="text-left"><?php echo $column_description ?></td>
                        <td class="text-right"><?php echo $column_action; ?></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if ($volumes) { ?>
                      <?php foreach ($volumes as $extension) { ?>
                      <tr>
                        <td><?php echo $extension['name']; ?></td>
                        <td><?php echo $extension['text']; ?></td>
                        <td class="text-right"><?php if (!$extension['installed']) { ?>
                          <a href="<?php echo $extension['install']; ?>" data-toggle="tooltip" title="<?php echo $button_install; ?>" class="btn btn-success"><i class="fa fa-plus-circle"></i></a>
                          <?php } else { ?>
                          <a onclick="confirm('<?php echo $text_confirm; ?>') ? location.href='<?php echo $extension['uninstall']; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_uninstall; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
                          <?php } ?>
                          <?php if ($extension['installed']) { ?>
                          <a href="<?php echo $extension['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
                          <?php } else { ?>
                          <button type="button" class="btn btn-primary" disabled="disabled"><i class="fa fa-pencil"></i></button>
                          <?php } ?></td>
                      </tr>
                        <?php if (!empty($extension['childs'])){
                          foreach ($extension['childs'] as $child) { ?>
                            <tr>
                              <td colspan="2"><?php echo $extension['name']; ?> > <b>(<?php echo $child['name'];?>)</b></td>
                              <td class="text-right">
                                <a onclick="confirm('<?php echo $text_confirm; ?>') ? location.href='<?php echo $child['delete']; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
                                <a href="<?php echo $child['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                            
                              </td>
                            </tr>
                          <?php } ?>
                        <?php } ?>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="text-center" colspan="2"><?php echo $text_no_results; ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>                                 
            </div>
          </div>            
          <div class="tab-pane" id="tab-modules">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left"><?php echo $column_name; ?></td>
                  <td class="text-left"><?php echo $column_description ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($extensions) { ?>
                <?php foreach ($extensions as $extension) { ?>
                <tr>
                  <td><?php echo $extension['name']; ?></td>
                  <td><?php echo $extension['text']; ?></td>
                  <td class="text-right"><?php if (!$extension['installed']) { ?>
                    <a href="<?php echo $extension['install']; ?>" data-toggle="tooltip" title="<?php echo $button_install; ?>" class="btn btn-success"><i class="fa fa-plus-circle"></i></a>
                    <?php } else { ?>
                    <a onclick="confirm('<?php echo $text_confirm; ?>') ? location.href='<?php echo $extension['uninstall']; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_uninstall; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
                    <?php } ?>
                    <?php if ($extension['installed']) { ?>
                    <a href="<?php echo $extension['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                    <?php } else { ?>
                    <button type="button" class="btn btn-primary" disabled="disabled"><i class="fa fa-pencil"></i></button>
                    <?php } ?></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="2"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>                                 
          </div>
            
            <div class="tab-pane" id="tab-about">
      			  <h3>Welcome and thank you for purchsing our module!</h3>
      			  <div class="panel panel-default">
      			    <div class="panel-heading"><h4 class="panel-title">About This Module</h4></div>
      			    <div class="panel-body"><strong>Power Image Manager</strong> is another great module developed by <a href="http://bit.ly/1vHShWu" target="_blank">Sharley's</a></div>
      			  </div>
      			  <div class="panel panel-default">
      			    <div class="panel-heading"><h4 class="panel-title">Need Support?</h4></div>
      			    <div class="panel-body">
      			      <a href="mailto: support@sharleys.co.uk?subject=Power Image Manager (OCV2) support on <?php echo HTTP_CATALOG;?>" class="btn btn-success"> <i class="fa fa-life-ring fa-lg"></i> Contact Us</a>
      			      <a href="http://on.fb.me/1inp4Ik" target="_blank" class="btn btn-primary"> <i class="fa fa-thumbs-up"></i> Follow us on Facebook</a>
      			    </div>
      			  </div>
      			  <div class="panel panel-default">
      			    <div class="panel-heading"><h4 class="panel-title">Happy about our modules?</h4></div>
      			    <div class="panel-body">Please give as your vote &amp; comment on <a href="http://bit.ly/1vHShWu" target="_blank">Opencart Extension Store</a></div>
      			  </div>
              </div>            
            </div>
          </div> 
        </form>
      </div>
    </div>
  </div>
<?php echo $footer; ?> 
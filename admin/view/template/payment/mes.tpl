<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-mes" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-mes" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="mes_status" id="input-status" class="form-control">
                    <?php if ($mes_status) { ?>
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
                <label class="col-sm-2 control-label" for="mes_mode"><span data-toggle="tooltip" title="<?php echo $help_mode ?>"><?php echo $entry_payment_mode; ?></span></label>
                <div class="col-sm-10">
                  <select name="mes_mode" id="mes_mode" class="form-control">
                    <?php if ($mes_mode === 'pg') { ?>
                    <option value="pg" selected="selected"><?php echo $text_pg; ?></option>
                    <option value="ph"><?php echo $text_ph; ?></option>
                    <?php } else { ?>
                    <option value="pg"><?php echo $text_pg; ?></option>
                    <option value="ph" selected="selected"><?php echo $text_ph; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="mes_test"><span data-toggle="tooltip" title="<?php echo $help_test; ?>"><?php echo $entry_test; ?></span></label>
                <div class="col-sm-10">
                  <select name="mes_test" id="mes_test" class="form-control">
                    <?php if ($mes_test) { ?>
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
                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="mes_sort_order" value="<?php echo $mes_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
                <div class="col-sm-10">
                  <select name="mes_geo_zone_id" id="input-geo-zone" class="form-control">
                    <option value="0"><?php echo $text_all_zones; ?></option>
                    <?php foreach ($geo_zones as $geo_zone) { ?>
                    <?php if ($geo_zone['geo_zone_id'] == $mes_geo_zone_id) { ?>
                    <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_completed_status; ?></label>
                <div class="col-sm-10">
                  <select name="mes_completed_status_id" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $mes_completed_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="mes_profile_id"><span data-toggle="tooltip" title="<?php echo $help_profile; ?>"><?php echo $entry_profile_id; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="mes_profile_id" value="<?php echo $mes_profile_id; ?>" placeholder="<?php echo $entry_profile_id; ?>" id="mes_profile_id" class="form-control"/>
                  <?php if ($error_profile_id) { ?>
                  <div class="text-danger"><?php echo $error_profile_id; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="mes_profile_key"><span data-toggle="tooltip" title="<?php echo $help_profile; ?>"><?php echo $entry_profile_key; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="mes_profile_key" value="<?php echo $mes_profile_key; ?>" placeholder="<?php echo $entry_profile_key; ?>" id="mes_profile_key" class="form-control"/>
                  <?php if ($error_profile_key) { ?>
                  <div class="text-danger"><?php echo $error_profile_key; ?></div>
                  <?php } ?>
                </div>
              </div>
              <?php if ($mes_mode === 'pg') { ?>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="mes_transaction"><span data-toggle="tooltip" title="<?php echo $help_auth; ?>"><?php echo $entry_auth; ?></span></label>
                <div class="col-sm-10">
                  <select name="mes_transaction" class="form-control">
                    <?php if (!$mes_transaction) { ?>
                    <option value="0" selected="selected"><?php echo $text_authorization; ?></option>
                    <option value="1" selected="selected"><?php echo $text_sale; ?></option>
                    <?php } else { ?>
                    <option value="0"><?php echo $text_authorization; ?></option>
                    <option value="1"><?php echo $text_sale; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <?php } else { ?>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="mes_security_key"><span data-toggle="tooltip" title="<?php echo $help_key; ?>"><?php echo $entry_security_key; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="mes_security_key" value="<?php echo $mes_security_key; ?>" id="mes_security_key" class="form-control" placeholder="<?php echo $entry_security_key; ?>" >
                  <?php if ($error_security_key) { ?>
                  <div class="text-danger"><?php echo $error_security_key; ?></div>
                  <?php } ?>
                </div>
              </div>
              <?php } ?>
            </div>
          </div>
        </form>
      </div> 
    </div>
  </div>
</div>
<?php echo $footer; ?>
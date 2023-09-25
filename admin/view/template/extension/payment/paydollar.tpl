<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-paydollar" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-paydollar" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-merchant"><?php echo $entry_merchant; ?> *</label>
            <div class="col-sm-10">
              <input type="text" name="paydollar_merchant" value="<?php echo $paydollar_merchant; ?>" placeholder="<?php echo $entry_merchant; ?>" id="input-merchant" class="form-control" />
              <?php if ($error_merchant) { ?>
              	<div class="text-danger"><?php echo $error_merchant; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-security"><?php echo $entry_security; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="paydollar_security" value="<?php echo $paydollar_security; ?>" placeholder="<?php echo $entry_security; ?>" id="input-security" class="form-control" />
              <?php if ($error_security) { ?>
              	<div class="text-danger"><?php echo $error_security; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-payserverurl"><?php echo $entry_payserverurl; ?> *</label>
            <div class="col-sm-10">
              <select name="paydollar_payserverurl" id="input-payserverurl" class="form-control">
                <?php foreach ($paydollar_payserverurls as $temp) { ?>
                	<?php if ($temp == $paydollar_payserverurl) { ?>
                		<option value="<?php echo $temp; ?>" selected="selected"><?php echo $temp; ?></option>
                	<?php } else { ?>
                		<option value="<?php echo $temp; ?>"><?php echo $temp; ?></option>
                	<?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-mps-mode"><?php echo $entry_mps_mode; ?></label>
            <div class="col-sm-10">
              <select name="paydollar_mps_mode" id="input-mps-mode" class="form-control">
                <?php foreach ($paydollar_mps_modes as $temp) { ?>
                	<?php if (($mps_mode=substr($temp,0,3)) == $paydollar_mps_mode) { ?>
                		<option value="<?php echo $mps_mode; ?>" selected="selected"><?php echo $temp; ?></option>
                	<?php } else { ?>
                		<option value="<?php echo $mps_mode; ?>"><?php echo $temp; ?></option>
                	<?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-currency"><?php echo $entry_currency; ?></label>
            <div class="col-sm-10">
              <select name="paydollar_currency" id="input-currency" class="form-control">
                <?php foreach ($paydollar_currencies as $temp) { ?>
                	<?php if (($currency_code=substr($temp,0,3)) == $paydollar_currency) { ?>
                		<option value="<?php echo $currency_code; ?>" selected="selected"><?php echo $temp; ?></option>
                	<?php } else { ?>
                		<option value="<?php echo $currency_code; ?>"><?php echo $temp; ?></option>
                	<?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-payment_type"><?php echo $entry_payment_type; ?></label>
            <div class="col-sm-10">
              <select name="paydollar_payment_type" id="input-payment-type" class="form-control">
                <?php foreach ($paydollar_payment_types as $temp) { ?>
                	<?php if (($payment_type=substr($temp,0,1)) == $paydollar_payment_type) { ?>
                		<option value="<?php echo $payment_type; ?>" selected="selected"><?php echo $temp; ?></option>
                	<?php } else { ?>
                		<option value="<?php echo $payment_type; ?>"><?php echo $temp; ?></option>
                	<?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-paymethod"><?php echo $entry_paymethod; ?></label>
            <div class="col-sm-10">
              <select name="paydollar_paymethod" id="input-paymethod" class="form-control">
                <?php foreach ($paydollar_paymethods as $temp) { ?>
                	<?php if ($temp == $paydollar_paymethod) { ?>
                		<option value="<?php echo $temp; ?>" selected="selected"><?php echo $temp; ?></option>
                	<?php } else { ?>
                		<option value="<?php echo $temp; ?>"><?php echo $temp; ?></option>
                	<?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-lang"><?php echo $entry_lang; ?></label>
            <div class="col-sm-10">
              <select name="paydollar_lang" id="input-lang" class="form-control">
                <?php foreach ($paydollar_langs as $temp) { ?>
                	<?php if (($lang = substr($temp,0,1)) == $paydollar_lang) { ?>
                		<option value="<?php echo $lang; ?>" selected="selected"><?php echo $temp; ?></option>
                	<?php } else { ?>
                		<option value="<?php echo $lang; ?>"><?php echo $temp; ?></option>
                	<?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
            <div class="col-sm-10">
              <select name="paydollar_order_status_id" id="input-order-status" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $paydollar_order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>          
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
            <div class="col-sm-10">
              <select name="paydollar_geo_zone_id" id="input-geo-zone" class="form-control">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $paydollar_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="paydollar_status" id="input-status" class="form-control">
                <?php if ($paydollar_status) { ?>
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
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="paydollar_sort_order" value="<?php echo $paydollar_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><div style="color:red;"><?php echo $entry_callback; ?> *</div></label>
            <div class="col-sm-10">
            	<div style="color:red;">
					<p><?php echo $callback; ?></p>
				</div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>










































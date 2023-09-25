<?php echo $header; ?>
<div class="container">
  <?php echo $content_top; ?>
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?> pd-b100">
      <h2><?php echo $heading_title; ?></h2>
      <div class="row marg-auto">
        <div class="col-md-5 col-lg-6 pd-b40">
          <div class="product-block">
            <div class="product-image-block relative marg-auto">
                <div class="relative">
                  <img 
                    src="<?= $image; ?>" 
                    alt="<?= $name; ?>" 
                    title="<?= $name; ?>"
                    class="img-responsive marg-auto" />	
                </div>
                <div class="absolute position-right-top"><div class="corner-badge"></div></div>
                <div class="absolute position-right-top z1"><div class="corner-badge-price"><?= $price_num ?></div></div>
            </div>
          </div>
        </div>
        <div class="col-md-7 col-lg-6">
          <div class="bold pd-b40"><?= $name ?></div>
          <div class="pd-b40"><?= $description ?></div>
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
            <div class="form-group">
              <div class="col-sm-12">
                <label class="control-label"><?= $text_dd ?></label>
                <div class="input-group date">
                <span class="input-group-btn">
                <button class="btn btn-default btn-calendar" type="button"><i class="fa fa-calendar"></i></button>
                </span>
                <input type="text" name="delivery_date" value="<?php ?>" id="input-delivery-date" class="form-control" data-date-format="YYYY-MM-DD" style="border-right:1px solid #ccc;" />
                </div>
                <?php if ($error_delivery_date) { ?>
                <div class="text-danger"><?php echo $error_delivery_date; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-12">
                <label class="control-label"><?= $text_to ?></label>
                <input type="text" name="to_name" value="<?php echo $to_name; ?>" id="input-to-name" class="form-control" placeholder="<?php echo $entry_to_name; ?>" />
                <?php if ($error_to_name) { ?>
                <div class="text-danger"><?php echo $error_to_name; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-12">
                <input type="text" name="to_email" value="<?php echo $to_email; ?>" id="input-to-email" class="form-control" placeholder="<?php echo $entry_to_email; ?>" />
                <?php if ($error_to_email) { ?>
                <div class="text-danger"><?php echo $error_to_email; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-12">
              <label class="control-label"><?= $text_from ?></label>
                <input type="text" name="from_name" value="<?php echo $from_name; ?>" id="input-from-name" class="form-control" placeholder="<?php echo $entry_from_name; ?>" />
                <?php if ($error_from_name) { ?>
                <div class="text-danger"><?php echo $error_from_name; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-12">
                <input type="text" name="from_email" value="<?php echo $from_email; ?>" id="input-from-email" class="form-control" placeholder="<?php echo $entry_from_email; ?>" />
                <?php if ($error_from_email) { ?>
                <div class="text-danger"><?php echo $error_from_email; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-12">
                <label class="control-label"><?= $entry_headerline ?></label>
                <input type="text" name="headerline" value="<?php echo $headerline; ?>" id="input-headerline" class="form-control" placeholder="<?php echo $entry_headerline; ?>" />
              </div>
            </div>
            <?php /* ?>
            <div class="form-group required">
              <label class="col-sm-3 control-label"><?php echo $entry_theme; ?></label>
              <div class="col-sm-9">
                <?php foreach ($voucher_themes as $voucher_theme) { ?>
                <?php if ($voucher_theme['voucher_theme_id'] == $voucher_theme_id) { ?>
                <div class="radio">
                  <label>
                    <input type="radio" name="voucher_theme_id" value="<?php echo $voucher_theme['voucher_theme_id']; ?>" checked="checked" />
                    <?php echo $voucher_theme['name']; ?></label>
                </div>
                <?php } else { ?>
                <div class="radio">
                  <label>
                    <input type="radio" name="voucher_theme_id" value="<?php echo $voucher_theme['voucher_theme_id']; ?>" />
                    <?php echo $voucher_theme['name']; ?></label>
                </div>
                <?php } ?>
                <?php } ?>
                <?php if ($error_theme) { ?>
                <div class="text-danger"><?php echo $error_theme; ?></div>
                <?php } ?>
              </div>
            </div>
            <?php */ ?>
            <div class="form-group">
              <div class="col-sm-12">
                <label class="control-label" for="input-message"><span data-toggle="tooltip" title="<?php echo $help_message; ?>"><?php echo $entry_message; ?></span></label>
                <div class="">
                  <textarea name="message" cols="40" rows="5" id="input-message" class="form-control"><?php echo $message; ?></textarea>
                </div>
              </div>
            </div>
            <?php /* ?>
            <div class="form-group">
              <label class="col-sm-3 control-label" for="input-amount"><span data-toggle="tooltip" title="<?php echo $help_amount; ?>"><?php echo $entry_amount; ?></span></label>
              <div class="col-sm-9">
                <input type="text" name="amount" value="<?php echo $amount; ?>" id="input-amount" class="form-control" size="5" />
                <?php if ($error_amount) { ?>
                <div class="text-danger"><?php echo $error_amount; ?></div>
                <?php } ?>
              </div>
            </div>
            <?php */ ?>
            <div class="buttons clearfix">
              <div class="pull-right"> <?php /* ?><?php echo $text_agree; ?>
                <?php if ($agree) { ?>
                <input type="checkbox" name="agree" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="agree" value="1" />
                <?php } ?>
                &nbsp;
                <?php */ ?>
                <input type="submit" value="<?php echo $button_cart; ?>" class="btn btn-primary btn-m" />
              </div>
            </div>
          </form>
        </div>
      </div>
      </div>
    <?php echo $column_right; ?></div>
    <?php echo $content_bottom; ?>
</div>
<?php if($success_message) { ?>
<script type="text/javascript">
  swal({
    title: '<?= $success_title ?>',
    html: '<?= $success_message ?>',
    type: "success"
  });
</script>
<?php } ?>
<script type="text/javascript"><!--
$('.date').datetimepicker({
    pickTime: false
});
//-->
</script>
<?php echo $footer; ?>
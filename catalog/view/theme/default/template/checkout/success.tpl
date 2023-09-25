<?php echo $header; ?>
<div class="container">
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h2><?php echo $heading_title; ?></h2>
      <div class='checkout-success'>
        <div class="line-1">
            <?= $text_order_numrber_is; ?>
        </div>
        <div class="line-2">
            <?= $text_you_will_receive_an_email_confirmation_shortly_at; ?>
            <p>We will contact you for the delivery details.</p>
        </div>
        <div class="line-3">
            <a href="<?= $print_receipt_url; ?>" target="_blank" ><?= $print_receipt; ?></a>
        </div>
        <div class="line-4">
            <?php if ($humanscaleSession == 2) { ?>
            <a href="<?php echo $continue; ?>" class="btn btn-primary margin-auto d-none"><?php echo $button_to_home; ?></a>

    <?php } else { ?>
        <a href="<?php echo $continue; ?>" class="btn btn-primary margin-auto"><?php echo $button_to_home; ?></a>
   <?php } ?>
        </div>
      </div>
    
      <?php //echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>

</div>
<!-- <?php debug($humanscaleSession); ?> -->
<?php echo $footer; ?>
<script>
    <?php if ($humanscaleSession == 2) { ?>
        $('header').addClass('d-none');
        $('.page-banner').addClass('d-none');
        $('#footer-area').addClass('d-none');
    <?php } ?>
</script>
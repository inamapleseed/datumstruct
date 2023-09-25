<?php echo $header; ?>
<div class="container">
  <?php echo $content_top; ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>">
    <?php echo $column_right; ?>
    <h2><?php echo $heading_title; ?></h2>
    <?php echo $content_bottom; ?>
      </div>
  </div>
</div>
<?php echo $footer; ?>
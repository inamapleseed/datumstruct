<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
		<div class="container-fluid">
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
					<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
    </div>
  <div class="container-fluid csvprice_pro_container">
    <?php if (isset($warning) && !empty($warning)) { ?>
			<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $warning; ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
		<?php } ?>
        <?php if (isset($success) && !empty($success)) { ?>
			<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
        <?php } ?>
		<?php echo $app_header; ?>
    <div class="panel panel-default">
      <div class="panel-body">
	<div class="text-right">
	  <a href="<?php echo $download; ?>" data-toggle="tooltip" title="<?php echo $button_download; ?>" class="btn btn-primary btn-sm"><i class="fa fa-download"></i></a>
	  <a onclick="confirm('<?php echo $text_confirm; ?>') ? location.href = '<?php echo $clear; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_clear; ?>" class="btn btn-danger btn-sm"><i class="fa fa-eraser"></i></a>
	</div>
	<div style="margin-top:10px;">
		<textarea wrap="off" rows="15" readonly class="form-control"><?php echo $log; ?></textarea>
	</div>
      </div>
    </div>
  </div>
<?php echo $app_footer; ?>
</div>
<?php echo $footer; ?>
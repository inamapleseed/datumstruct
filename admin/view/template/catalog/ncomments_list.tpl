<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $insert; ?>" data-toggle="tooltip" title="<?php echo $button_insert; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-ncom').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
  
	<?php echo $newspanel; ?>
  
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
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading_title; ?> list</h3>
      </div>
      <div class="panel-body">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-ncom">
	    <div class="table-responsive">
            <table class="table table-bordered table-hover">
				<thead>
					<tr>
						<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);$.uniform.update();" /></td>
						<td class="text-left"><?php if ($sort == 'bd.title') { ?>
								<a href="<?php echo $sort_product; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_product; ?></a>
							<?php } else { ?>
								<a href="<?php echo $sort_product; ?>"><?php echo $column_product; ?></a>
						<?php } ?></td>
						<td class="text-left"><?php if ($sort == 'n.author') { ?>
								<a href="<?php echo $sort_author; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_author; ?></a>
							<?php } else { ?>
								<a href="<?php echo $sort_author; ?>"><?php echo $column_author; ?></a>
						<?php } ?></td>
						<td class="text-left"><?php if ($sort == 'n.status') { ?>
								<a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
							<?php } else { ?>
								<a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
						<?php } ?></td>
						<td class="text-left"><?php if ($sort == 'n.date_added') { ?>
								<a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
							<?php } else { ?>
								<a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
						<?php } ?></td>
						<td class="text-right"><?php echo $column_action; ?></td>
					</tr>
				</thead>
				<tbody>
					<?php if ($ncomments) { ?>
						<?php foreach ($ncomments as $comment) { ?>
							<tr>
								<td style="text-align: center;"><?php if ($comment['selected']) { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $comment['ncomment_id']; ?>" checked="checked" />
									<?php } else { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $comment['ncomment_id']; ?>" />
								<?php } ?></td>
								<td class="text-left"><?php echo $comment['name']; ?></td>
								<td class="text-left"><?php echo $comment['author']; ?></td>
								<td class="text-left"><?php echo $comment['status']; ?></td>
								<td class="text-left"><?php echo $comment['date_added']; ?></td>
								<td class="text-right"><?php foreach ($comment['action'] as $action) { ?>
									<a href="<?php echo $action['href']; ?>" data-toggle="tooltip" title="<?php echo $action['text']; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
								<?php } ?></td>
							</tr>
						<?php } ?>
					<?php } else { ?>
						<tr>
							<td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		  </div>
      </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
    </div>
  </div>
</div>
</div>
<?php echo $footer; ?>
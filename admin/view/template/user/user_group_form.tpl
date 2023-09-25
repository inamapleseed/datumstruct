<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-user-group" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-user-group" class="form-horizontal">
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
						<div class="col-sm-10">
							<input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
							<?php if ($error_name) { ?>
								<div class="text-danger"><?php echo $error_name; ?></div>
							<?php  } ?>
						</div>
					</div>
					<div class="form-group hidden">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?= $help_is_dev; ?>"><?= $entry_is_dev; ?></span></label>
						<div class="col-sm-10">
							<label class="radio-inline">
								<?php if ($is_dev) { ?>
									<input type="radio" name="is_dev" value="1" checked="checked" />
									<?= $text_yes; ?>
								<?php } else { ?>
									<input type="radio" name="is_dev" value="1" />
									<?= $text_yes; ?>
								<?php } ?>
							</label>
							<label class="radio-inline">
								<?php if (!$is_dev) { ?>
									<input type="radio" name="is_dev" value="0" checked="checked" />
									<?= $text_no; ?>
								<?php } else { ?>
									<input type="radio" name="is_dev" value="0" />
									<?= $text_no; ?>
								<?php } ?>
							</label>
						</div>
					</div>
					
					<div class = "row">
						
						<div class="col-xs-12">
								<div class="row">
									<div class="col-sm-6">
											<p class = "bold"><?php echo $entry_access; ?></p>
											<p><a style="cursor:pointer;" onclick="$('.access').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a style="cursor:pointer;" onclick="$('.access').prop('checked', false);"><?php echo $text_unselect_all; ?></a></p>
									</div>
									<div class="col-sm-6"><p class = "bold"><?php echo $entry_modify; ?></p>
										<p><a style="cursor:pointer;" onclick="$('.modify').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a style="cursor:pointer;" onclick="$('.modify').prop('checked', false);"><?php echo $text_unselect_all; ?></a></p>
									</div>
								</div>
								<div class="well well-sm" style="height: 400px; overflow: auto;">
									<div class="col-sm-6">
											<?php foreach ($permissions as $permission) { ?>
												<div class="checkbox">
													<label>
														<?php if (in_array($permission, $access)) { ?>
															<input type="checkbox" name="permission[access][]" value="<?php echo $permission; ?>" checked="checked" class="access" />
															<?php echo $permission; ?>
															<?php } else { ?>
															<input type="checkbox" name="permission[access][]" value="<?php echo $permission; ?>" class="access" />
															<?php echo $permission; ?>
														<?php } ?>
													</label>
												</div>
											<?php } ?>
									</div>
									
									<div class="col-sm-6">
											<?php foreach ($permissions as $permission) { ?>
												<div class="checkbox">
													<label>
														<?php if (in_array($permission, $modify)) { ?>
															<input type="checkbox" name="permission[modify][]" value="<?php echo $permission; ?>" checked="checked" class="modify" />
															<?php echo $permission; ?>
															<?php } else { ?>
															<input type="checkbox" name="permission[modify][]" value="<?php echo $permission; ?>" class="modify" />
															<?php echo $permission; ?>
														<?php } ?>
													</label>
												</div>
											<?php } ?>
									</div>
								</div>
						</div>

					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php echo $footer; ?> 
<fieldset>
	<legend><?php echo $heading_title; ?></legend>
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
	<div class="alert alert-info sticky"><i class="fa fa-info-circle"></i> <?php echo $text_layout; ?></div>
	
	<ul class="nav nav-tabs nav-justified">
		<li class="active"><a data-toggle="tab" href="#active_panel">Active</a></li>
		<li><a data-toggle="tab" href="#nonactive_panel">Not Active</a></li>
	</ul>
	
	<div class="tab-content">
		<div id="active_panel" class="tab-pane fade in active">

			<div class="input-group">
				<div class="input-group-addon">Search</div>
				<input type="text" class="form-control" onkeyup="filterExtension(this.value);" />
			</div>
			<script>
				function filterExtension(keyword) {
					if (keyword) {
						keyword = keyword.split(' ').join('-');
						keyword = keyword.toLowerCase();
						$("#installed_modules > tr:not([query*=\"" + keyword + "\"])").hide();
						$("#installed_modules > tr[query*=\"" + keyword + "\"]").removeAttr('style');
					}
					else {
						$("#installed_modules > tr").removeAttr('style');
					}

				}
			</script>
			<hr/>

			<div class="table-responsive">
				<table class="table table-bordered table-hover extension-table">
					<thead>
						<tr>
							<td class="text-left"><?php echo $column_name; ?></td>
							<td class="text-right"><?php echo $column_action; ?></td>
						</tr>
					</thead>
					<tbody id="installed_modules" >

						<?php if ($installed_extensions) { ?>
							<?php foreach ($installed_extensions as $extension) {  ?>
								<tr class="parent" query="<?= generateSlug($extension['name']); ?>" >
									<td><b ><?php echo $extension['name']; ?></b></td>
									<td class="text-right">
											
										<?php if ($extension['installed']) { ?>
											<?php if ($extension['module']) { ?>
												<a href="<?php echo $extension['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></a>
												<?php } else { ?>
												<a href="<?php echo $extension['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
											<?php } ?>
											<?php } else { ?>
											<button type="button" class="btn btn-primary" disabled="disabled"><i class="fa fa-pencil"></i></button>
										<?php } ?>
										<?php if (!$extension['installed']) { ?>
											
											<a href="<?php echo $extension['install']; ?>" data-toggle="tooltip" title="<?php echo $button_install; ?>" class="btn btn-success"><i class="fa fa-magic"></i></a>
											<?php } else { ?>
											<a href="<?php echo $extension['uninstall']; ?>" data-toggle="tooltip" title="<?php echo $button_uninstall; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
										
										<?php } ?>
									
									</td>
								</tr>
								<?php foreach ($extension['module'] as $module) { ?>
									<tr class="child" query="<?= generateSlug($extension['name']); ?>
									<?= generateSlug($module['name']); ?>">
										<td class="text-left">&nbsp;&nbsp;&nbsp;<span class="tag label label-success" ><i class="fa fa-folder-open"></i></span>&nbsp;&nbsp;&nbsp;<?php echo $module['name']; ?></td>
										<td class="text-right">
											
											<a href="<?php echo $module['duplicate']; ?>" data-toggle="tooltip" title="Duplicate" class="btn btn-default"><i class="fa fa-copy"></i></a>
											<a href="<?php echo $module['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-info"><i class="fa fa-pencil"></i></a> 
											<a href="<?php echo $module['delete']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-warning"><i class="fa fa-trash-o"></i></a>
										
										</td>
									</tr>
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
		<div id="nonactive_panel" class="tab-pane fade">

			<div class="input-group">
				<div class="input-group-addon">Search</div>
				<input type="text" class="form-control" onkeyup="filterNonExtension(this.value);" />
			</div>
			<script>
				function filterNonExtension(keyword) {
					if (keyword) {
						keyword = keyword.toLowerCase();
						$("#not_install_modules > tr:not([query*=\"" + keyword + "\"])").hide();
						$("#not_install_modules > tr[query*=\"" + keyword + "\"]").removeAttr('style');
					}
					else {
						$("#not_install_modules > tr").removeAttr('style');
					}

				}
			</script>
			<hr />

			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<td class="text-left"><?php echo $column_name; ?></td>
							<td class="text-right"><?php echo $column_action; ?></td>
						</tr>
					</thead>
					<tbody id="not_install_modules" >
						<?php if ($extensions) { ?>
							<?php foreach ($extensions as $extension) {  ?>
								<tr query="<?= generateSlug($extension['name']); ?>" >
									<td><b><?php echo $extension['name']; ?></b></td>
									<td class="text-right" >
										<?php if ($extension['installed']) { ?>
											<?php if ($extension['module']) { ?>
												<a href="<?php echo $extension['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></a>
												<?php } else { ?>
												<a href="<?php echo $extension['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
											<?php } ?>
											<?php } else { ?>
											<button type="button" class="btn btn-primary" disabled="disabled"><i class="fa fa-pencil"></i></button>
										<?php } ?>
										<?php if (!$extension['installed']) { ?>
											<a href="<?php echo $extension['install']; ?>" data-toggle="tooltip" title="<?php echo $button_install; ?>" class="btn btn-success"><i class="fa fa-magic"></i></a>
											<?php } else { ?>
											<a href="<?php echo $extension['uninstall']; ?>" data-toggle="tooltip" title="<?php echo $button_uninstall; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
										<?php } ?>
									</td>
								</tr>
								<?php foreach ($extension['module'] as $module) { ?>
									<tr>
										<td class="text-left">&nbsp;&nbsp;&nbsp;<i class="fa fa-folder-open"></i>&nbsp;&nbsp;&nbsp;<?php echo $module['name']; ?></td>
										<td class="text-right"><a href="<?php echo $module['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-info"><i class="fa fa-pencil"></i></a> <a href="<?php echo $module['delete']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-warning"><i class="fa fa-trash-o"></i></a></td>
									</tr>
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
	
	
</fieldset>

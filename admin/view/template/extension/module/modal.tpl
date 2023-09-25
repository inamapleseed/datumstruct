<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-html" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-html" class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
						<div class="col-sm-10">
							<input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
							<?php if ($error_name) { ?>
								<div class="text-danger"><?php echo $error_name; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="tab-pane">
						<ul class="nav nav-tabs" id="language">
							<?php foreach ($languages as $language) { ?>
								<li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
							<?php } ?>
						</ul>
						<div class="tab-content">
							<?php foreach ($languages as $language) { ?>
								<div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-title<?php echo $language['language_id']; ?>">
											<span data-toggle="tooltip" title="First line for smaller text. Line 2 onwards are the larger text portion" ><?php echo $entry_title; ?></span>
										</label>
										<div class="col-sm-10">
											<textarea name="module_description[<?php echo $language['language_id']; ?>][title]" placeholder="<?php echo $entry_title; ?>" id="input-heading<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($module_description[$language['language_id']]['title']) ? $module_description[$language['language_id']]['title'] : ''; ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
										<div class="col-sm-10">
											<textarea name="module_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" class="<?php if ($ckeditor_enabled == 1) { ?>form-control<?php } else { ?>form-control summernote<?php } ?>"><?php echo isset($module_description[$language['language_id']]['description']) ? $module_description[$language['language_id']]['description'] : ''; ?></textarea>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
						<div class="col-sm-10">
                            <select name="status" id="input-status" class="form-control">
								<?php if ($status) { ?>
									<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
									<option value="0"><?php echo $text_disabled; ?></option>
									<?php } else { ?>
									<option value="1"><?php echo $text_enabled; ?></option>
									<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Enhanced CKEditor -->
	<?php if ($fm_installed == 0) { ?>
		<?php if ($ckeditor_enabled == 1) { ?>
			<script type="text/javascript">
				<?php foreach ($languages as $language) { ?>
					CKEDITOR.replace("input-description<?php echo $language['language_id']; ?>", {
						language: "<?php echo $language['code']; ?>",
						filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
						filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
						filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
						skin : "<?php echo $ckeditor_skin; ?>",
						codemirror: {
							theme: "<?php echo $codemirror_skin; ?>",
						},
						height: 350
					});
				<?php } ?>
			</script>
			<script type="text/javascript"><!--
				CKEDITOR.on('dialogDefinition', function (event) {
					var editor = event.editor;
					var dialogDefinition = event.data.definition;
					var dialogName = event.data.name;
					var tabCount = dialogDefinition.contents.length;
					for (var i = 0; i < tabCount; i++) {
						var browseButton = dialogDefinition.contents[i].get('browse');
						if (browseButton !== null) {
							browseButton.hidden = false;
							browseButton.onClick = function() {
								$('#modal-image').remove();
								$.ajax({
                                    url: 'index.php?route=common/filemanager&token=<?php echo $token; ?>&ckedialog='+this.filebrowser.target,
                                    dataType: 'html',
                                    cache: false,
                                    contentType: false,
                                    processData: true,
                                    success: function(html) {
										$('body').append('<div id="modal-image" class="modal cke_eval">' + html + '</div>');
										$('#modal-image').modal('show');
									},
                                    error: function(xhr, ajaxOptions, thrownError){
										alert(xhr.responseText);
									}
								});
							}
						}
					}
				});
			//--></script>
		<?php } ?>
	<?php } ?>
	<!-- Enhanced CKEditor -->
	
	<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
	<script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>
	<script type="text/javascript"><!--
		$('#language a:first').tab('show');
	//--></script></div>
	<?php echo $footer; ?>
		
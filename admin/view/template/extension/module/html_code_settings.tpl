<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-html" class="btn btn-primary"><?php echo $button_save; ?></button>
				<a onclick="$('#form-html').append('<input type=\'hidden\' name=\'exit\' value=\'1\' />');$('#form-html').submit();" class="btn btn-info"><?php echo $button_save_exit; ?></a>
				<a href="<?php echo $back; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
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
		<?php if ($success) { ?>
			<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
		<?php } ?>

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
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-html">
					  <ul class="nav nav-tabs">
						  <li><a href="<?php echo $tab_action_main; ?>"><i class="fa fa-file"></i> <?php echo $tab_main; ?></a></li>
						  <li class="active"><a href="<?php echo $tab_action_settings; ?>"><i class="fa fa-cog"></i> <?php echo $tab_settings; ?></a></li>
						  <li><a href="<?php echo $tab_action_about; ?>"><i class="fa fa-question"></i> <?php echo $tab_about; ?></a></li>
					  </ul>
				  <div class="tab-content">
					<div class="tab-pane active">
						<table class="table">
							<tbody>
								<tr>
									<td width="200">
										<?php echo $layout_information; ?>
									</td>
									<td>
										<select name="settings[html_code_layout_i]">
											<?php foreach( $layouts as $layout ) { ?>
												<option value="<?php echo $layout['layout_id']; ?>"<?php if( $settings['html_code_layout_i'] == $layout['layout_id'] ) { ?> selected="selected"<?php } ?>><?php echo $layout['name']; ?></option>
											<?php } ?>
										</select>
									</td>
								</tr>
								<tr>
									<td>
										<?php echo $layout_category; ?>
									</td>
									<td>
										<select name="settings[html_code_layout_c]">
											<?php foreach( $layouts as $layout ) { ?>
												<option value="<?php echo $layout['layout_id']; ?>"<?php if( $settings['html_code_layout_c'] == $layout['layout_id'] ) { ?> selected="selected"<?php } ?>><?php echo $layout['name']; ?></option>
											<?php } ?>
										</select>
									</td>
								</tr>
								<tr>
									<td>
										<?php echo $layout_product; ?>
									</td>
									<td>
										<select name="settings[html_code_layout_p]">
											<?php foreach( $layouts as $layout ) { ?>
												<option value="<?php echo $layout['layout_id']; ?>"<?php if( $settings['html_code_layout_p'] == $layout['layout_id'] ) { ?> selected="selected"<?php } ?>><?php echo $layout['name']; ?></option>
											<?php } ?>
										</select>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				  </div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php echo $footer; ?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-save"></i></button>
				<a onclick="$('#form').attr('action', '<?php echo $continue; ?>');$('#form').submit();" data-toggle="tooltip" title="<?php echo $button_continue; ?>" class="btn btn-primary"><i class="fa fa-check"></i></a>
			<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
			<h1><?php echo $heading_title; ?></h1>
			<?php echo bread($breadcrumbs); ?>
		</div>
	</div>
	<div class="container-fluid">
		<?php if ($success) { ?>
			<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
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
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>&ensp;
				<i class="fa fa-pencil"></i> <?php echo $entry_store; ?> 
				<select onchange="store();" name="store_id">
					<option value="0"<?php echo $store_id == 0 ? ' selected="selected"' : ''; ?>><?php echo $text_default_store; ?></option>
					<?php foreach ($stores as $store) { ?>
						<option value="<?php echo $store['store_id']; ?>"<?php echo $store_id == $store['store_id'] ? ' selected="selected"' : ''; ?>><?php echo $store['name']; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
						<li><a href="#tab-field" data-toggle="tab"><?php echo $tab_field; ?></a></li>
					</ul>
					<div class="tab-content">
						
						<div class="tab-pane active" id="tab-general">
							<legend>Main</legend>
							<div class="row">
								<div class="form-group col-sm-6">
									<label class="col-sm-4 control-label" for="input-status"><span title="<?php echo $help_status; ?>" data-toggle="tooltip"><?php echo $entry_status; ?></span></label>
									<div class="col-sm-8">
										<div align="center" class="onoffswitch">
											<input type="checkbox" name="quickregistration_status" class="onoffswitch-checkbox" id="quickregistration_status" value="1" <?php echo ($quickregistration_status == '1') ? 'checked="checked"' : '' ; ?>  >
											
										</div>
									</div>
								</div>
								
								<div class="form-group col-sm-6">
									<label class="col-sm-4 control-label" for="input-proceed-button-text"><span title="<?php echo $help_proceed_button_text; ?>" data-toggle="tooltip"><?php echo $entry_proceed_button_text; ?></span></label>
									<div class="col-sm-8">
										<?php foreach ($languages as $language) { ?>
											<div class="input-group">
												<span class="input-group-addon"><img src="<?php echo version_compare(VERSION, '2.2.0.0', '<') ? 'view/image/flags/' . $language['image'] : 'language/' . $language['code'] . '/' . $language['code'] . '.png'; ?>" title="<?php echo $language['name']; ?>" /></span>
												<input type="text" name="quickregistration_proceed_button_text[<?php echo $language['language_id']; ?>]" value="<?php echo !empty($quickregistration_proceed_button_text[$language['language_id']]) ? $quickregistration_proceed_button_text[$language['language_id']] : ''; ?>" class="form-control" />
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
							
							<legend>Custom HTML (for advanced users)</legend>
							<div class="row">
								<div class="form-group col-sm-6">
									<label class="col-sm-4 control-label" for="input-html-header"><span title="<?php echo $help_html_header; ?>" data-toggle="tooltip"><?php echo $entry_html_header; ?></span></label>
									<div class="col-sm-8">
										<?php foreach ($languages as $language) { ?>
											<div class="input-group">
												<span class="input-group-addon"><img src="<?php echo version_compare(VERSION, '2.2.0.0', '<') ? 'view/image/flags/' . $language['image'] : 'language/' . $language['code'] . '/' . $language['code'] . '.png'; ?>" title="<?php echo $language['name']; ?>" /></span>
												<textarea name="quickregistration_html_header[<?php echo $language['language_id']; ?>]" rows="4" cols="30" class="form-control"><?php echo !empty($quickregistration_html_header[$language['language_id']]) ? $quickregistration_html_header[$language['language_id']] : ''; ?></textarea>
											</div>
										<?php } ?>
									</div>
								</div>
								<div class="form-group col-sm-6">
									<label class="col-sm-4 control-label" for="input-html-footer"><span title="<?php echo $help_html_footer; ?>" data-toggle="tooltip"><?php echo $entry_html_footer; ?></span></label>
									<div class="col-sm-8">
										<?php foreach ($languages as $language) { ?>
											<div class="input-group">
												<span class="input-group-addon"><img src="<?php echo version_compare(VERSION, '2.2.0.0', '<') ? 'view/image/flags/' . $language['image'] : 'language/' . $language['code'] . '/' . $language['code'] . '.png'; ?>" title="<?php echo $language['name']; ?>" /></span>
												<textarea name="quickregistration_html_footer[<?php echo $language['language_id']; ?>]" rows="4" cols="30" class="form-control"><?php echo !empty($quickregistration_html_footer[$language['language_id']]) ? $quickregistration_html_footer[$language['language_id']] : ''; ?></textarea>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
							
                            <legend>Custom CSS (for advanced users)</legend>
							<div class="row">
								<div class="form-group col-sm-12">
									<label class="col-sm-2 control-label" for="input-custom-css"><?php echo $entry_custom_css; ?></label>
									<div class="col-sm-10">
										<textarea name="quickregistration_custom_css" id="input-custom-css" class="form-control" rows="5"><?php echo $quickregistration_custom_css; ?></textarea>
									</div>
								</div>
							</div>
						</div>
						
						<div class="tab-pane table-responsive" id="tab-field">
							
							<legend>Registration Fields</legend>
							
							<table class="table table-bordered table-hover table-striped">
								<tr>
									<td></td>
									<td class="text-center"><?php echo $text_display; ?></td>
									<td class="text-center"><?php echo $text_required; ?></td>
									<td><?php echo $text_presets; ?></td>
								</tr>
								<?php foreach ($fields as $field) { ?>
									<?php if ($field == 'country') { ?>
										<tr>
											<td>
											<span><?php echo ${'entry_field_' . $field}; ?></span></td>
											
											<td align="center">
												<span >
													<div class="onoffswitch">
														<input type="checkbox" name="quickregistration_field_<?php echo $field; ?>[display]" class="onoffswitch-checkbox" id="quickregistration_field_<?php echo $field; ?>[display]" value="1" <?php echo (!empty(${'quickregistration_field_' . $field}['display']) && ${'quickregistration_field_' . $field}['display']) ? ' checked' : ''; ?> >
													</div>
												</span>
											</td>
											<td align="center">
												<span>
													<div class="onoffswitch">
														<input type="checkbox" name="quickregistration_field_<?php echo $field; ?>[required]" class="onoffswitch-checkbox" id="quickregistration_field_<?php echo $field; ?>[required]" value="1" <?php echo !empty(${'quickregistration_field_' . $field}['required']) ? ' checked' : ''; ?> >
														
													</div>
												</span>
											</td>
											<td>
												<div class="row reg-fields">
													<div class="col-sm-4 reg-field"><?php echo $text_default; ?></div>
													<div class="col-sm-8"><select name="quickregistration_field_<?php echo $field; ?>[default]" class="form-control">
														<option value=""><?php echo $text_select; ?></option>
														<?php foreach ($countries as $country) { ?>
															<?php if (!empty(${'quickregistration_field_' . $field}['default']) && ${'quickregistration_field_' . $field}['default'] == $country['country_id']) { ?>
																<option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
																<?php } else { ?>
																<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
															<?php } ?>
														<?php } ?>
													</select></div>
												</div>
											</td>
										</tr>
										<?php } elseif ($field == 'email') { ?>
										<tr>
											<td>
											<span><?php echo ${'entry_field_' . $field}; ?></span></td>
											
											<td align="center">
												<span>
													<div class="onoffswitch">
														<input type="checkbox" name="quickregistration_field_<?php echo $field; ?>[display]" class="onoffswitch-checkbox" id="quickregistration_field_<?php echo $field; ?>[display]" value="1" <?php echo !empty(${'quickregistration_field_' . $field}['display']) ? ' checked' : ''; ?> onclick="return false" >
														
													</div>
												</span>
											</td>
											<td align="center">
												<span>
													<div class="onoffswitch">
														<input type="checkbox" name="quickregistration_field_<?php echo $field; ?>[required]" class="onoffswitch-checkbox" id="quickregistration_field_<?php echo $field; ?>[required]" value="1" <?php echo !empty(${'quickregistration_field_' . $field}['required']) ? ' checked' : ''; ?> onclick="return false" >
														
													</div>
												</span>
											</td>
											<td>
												<div class="row reg-fields">
													<div class="col-sm-4 reg-field"><?php echo $text_placeholder; ?></div>
													<div class="col-sm-8"><input type="text" name="quickregistration_field_<?php echo $field; ?>[placeholder]" value="<?php echo !empty(${'quickregistration_field_' . $field}['placeholder']) ? ${'quickregistration_field_' . $field}['placeholder'] : ''; ?>" class="form-control" /></div>
												</div>
											</td>
										</tr>
										<?php } elseif ($field == 'zone') { ?>
										<tr>
											<td>
											<span><?php echo ${'entry_field_' . $field}; ?></span></td>
											
											<td align="center">
												<span>
													<div class="onoffswitch">
														<input type="checkbox" name="quickregistration_field_<?php echo $field; ?>[display]" class="onoffswitch-checkbox" id="quickregistration_field_<?php echo $field; ?>[display]" value="1" <?php echo !empty(${'quickregistration_field_' . $field}['display']) ? ' checked' : ''; ?> >
														
													</div>
												</span>
											</td>
											<td align="center">
												<span>
													<div class="onoffswitch">
														<input type="checkbox" name="quickregistration_field_<?php echo $field; ?>[required]" class="onoffswitch-checkbox" id="quickregistration_field_<?php echo $field; ?>[required]" value="1" <?php echo !empty(${'quickregistration_field_' . $field}['required']) ? ' checked' : ''; ?> >
														
													</div>
												</span>
											</td>
											<td class="text-center"></td>
										</tr>
										<?php } elseif ($field == 'customer_group' || $field == 'address_text' || $field == 'details_text' || $field == 'password_text') { ?>
										<tr>
											<td>
											<span><?php echo ${'entry_field_' . $field}; ?></span></td>
											
											<td align="center">
												<span>
													<div class="onoffswitch">
														<input type="checkbox" name="quickregistration_field_<?php echo $field; ?>[display]" class="onoffswitch-checkbox" id="quickregistration_field_<?php echo $field; ?>[display]" value="1" <?php echo !empty(${'quickregistration_field_' . $field}['display']) ? ' checked' : ''; ?> >
														
													</div>
												</span>
											</td>
											<td class="text-center"></td>
											<td class="text-center"></td>
										</tr>
										<?php } elseif ($field == 'newsletter') { ?>
										<tr>
											<td>
											<span><?php echo ${'entry_field_' . $field}; ?></span></td>
											
											<td align="center"><div class="onoffswitch">
												<input type="checkbox" name="quickregistration_field_<?php echo $field; ?>[display]" class="onoffswitch-checkbox" id="quickregistration_field_<?php echo $field; ?>[display]" value="1" <?php echo !empty(${'quickregistration_field_' . $field}['display']) ? ' checked' : ''; ?> >
												
											</div>
											</td>
											<td align="center"><div class="onoffswitch">
												<input type="checkbox" name="quickregistration_field_<?php echo $field; ?>[required]" class="onoffswitch-checkbox" id="quickregistration_field_<?php echo $field; ?>[required]" value="1" <?php echo !empty(${'quickregistration_field_' . $field}['required']) ? ' checked' : ''; ?> onclick="return false" >
												
											</div>
											</td>
											<td>
												<div class="row reg-fields">
													<div class="col-sm-4 reg-field"><?php echo $text_default; ?></div>
													<div class="col-sm-8 text-center"><input type="checkbox" name="quickregistration_field_<?php echo $field; ?>[default]"<?php echo !empty(${'quickregistration_field_' . $field}['default']) ? ' checked' : ''; ?> /></div>
												</div>
											</td>
										</tr>
										<?php } elseif ($field == 'agree') { ?>
										<tr>
											<td>
											<span><?php echo ${'entry_field_' . $field}; ?></td></span>
											
											<td align="center"><div class="onoffswitch">
												<input type="checkbox" name="quickregistration_field_<?php echo $field; ?>[display]" class="onoffswitch-checkbox" id="quickregistration_field_<?php echo $field; ?>[display]" value="1" <?php echo !empty(${'quickregistration_field_' . $field}['display']) ? ' checked' : ''; ?> onclick="return false" >
												
											</div>
											</td>
											<td align="center"><div class="onoffswitch">
												<input type="checkbox" name="quickregistration_field_<?php echo $field; ?>[required]" class="onoffswitch-checkbox" id="quickregistration_field_<?php echo $field; ?>[required]" value="1" <?php echo !empty(${'quickregistration_field_' . $field}['required']) ? ' checked' : ''; ?> onclick="return false" >
												
											</div>
											</td>
											<td>
												<div class="row reg-fields">
													<div class="col-sm-4 reg-field"><?php echo $text_default; ?></div>
													<div class="col-sm-8 text-center"><input type="checkbox" name="quickregistration_field_<?php echo $field; ?>[default]"<?php echo !empty(${'quickregistration_field_' . $field}['default']) ? ' checked' : ''; ?> /></div>
												</div>
											</td>
										</tr>
										<?php } else { ?>
										<tr>
											<td>
											<span><?php echo ${'entry_field_' . $field}; ?></span></td>
											
											<td align="center"> <span >                                  
												<div class="onoffswitch">
													<input type="checkbox" name="quickregistration_field_<?php echo $field; ?>[display]" class="onoffswitch-checkbox" id="quickregistration_field_<?php echo $field; ?>[display]" value="1" <?php echo !empty(${'quickregistration_field_' . $field}['display']) ? ' checked' : ''; ?> >
													
												</div>
											</span>
											</td>
											
											<?php if ($field == 'postcode1') { ?>
												<td class="text-center">NA</td>
												<?php } else { ?>
												
												<td align="center">  <span >                                 
													<div class="onoffswitch">
														<input type="checkbox" name="quickregistration_field_<?php echo $field; ?>[required]" class="onoffswitch-checkbox" id="quickregistration_field_<?php echo $field; ?>[required]" value="1" <?php echo !empty(${'quickregistration_field_' . $field}['required']) ? ' checked' : ''; ?> >
														
													</div>
												</span>
												</td>
												
											<?php } ?>
											<td>
												<div class="row reg-fields">
													<div class="col-sm-4 reg-field"><?php echo $text_placeholder; ?></div>
													<div class="col-sm-8"><input type="text" name="quickregistration_field_<?php echo $field; ?>[placeholder]" value="<?php echo !empty(${'quickregistration_field_' . $field}['placeholder']) ? ${'quickregistration_field_' . $field}['placeholder'] : ''; ?>" class="form-control" /></div>
												</div>
											</td>
										</tr>
									<?php } ?>
								<?php } ?>
							</table>
							
							
						</div>
						
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<style type="text/css">
	.form-group + .form-group {
	border: none;
	}
</style>
<script type="text/javascript">
	$(".onoffswitch input").onoff();
</script>
<script type="text/javascript"><!--
	function show(element) {
		$(element).tab('show');
		
		$('a[href=\'' + element + '\']').parent('li').siblings().removeClass('active');
		
		$('a[href=\'' + element + '\']').parent('li').addClass('active');
		
		return false;
	}
	
	
	$(document).ready(function() {
		$('.date').datetimepicker();
	});
	
	$('select[name=\'quickregistration_field_country[default]\']').on('change', function() {
		$.ajax({
			url: 'index.php?route=extension/module/quickregistration/country&token=<?php echo $token; ?>&country_id=' + this.value,
			dataType: 'json',		
			success: function(json) {
				html = '<option value=""><?php echo $text_select; ?></option>';
				
				if (json['zone'] != '') {
					for (i = 0; i < json['zone'].length; i++) {
						html += '<option value="' + json['zone'][i]['zone_id'] + '"';
						
						if (json['zone'][i]['zone_id'] == '<?php echo !empty($quickregistration_field_zone['default']) ? $quickregistration_field_zone['default'] : ''; ?>') {
							html += ' selected="selected"';
						}
						
						html += '>' + json['zone'][i]['name'] + '</option>';
					}
					} else {
					html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
				}
				
				$('select[name=\'quickregistration_field_zone[default]\']').html(html);
			}
		});
	});
	
	$('select[name=\'quickregistration_field_country[default]\']').trigger('change');
	
	
	function store() {
		location = 'index.php?route=extension/module/quickregistration&token=<?php echo $token; ?>&store_id=' + $('select[name=\'store_id\']').val();
	}
//--></script>
<?php echo $footer; ?>
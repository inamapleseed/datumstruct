<?php
//  Related Options / Связанные опции
//  Support: support@liveopencart.com / Поддержка: help@liveopencart.ru
?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	
	<div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-related-options" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $module_name; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
	<div class="container-fluid">
    <?php if (isset($error_warning) && $error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if (isset($updated) && $updated) { ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $updated; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
		<?php if (isset($success) && $success) { ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    
    <?php
    
      $html_scripts = '';
    
      function show_checkbox($modules, $name, $title, $help, $onchange='') {
        
        $html = '<div class="form-group">';
        $html.= '<label class="col-sm-2 control-label" for="'.$name.'" >';
        if ($help != '') {
          $html.= '<span data-toggle="tooltip" title="" data-original-title="'.$help.'">'.$title.'</span></label>';
        } else {
          $html.= ''.$title.'</label>';
        }
        $html.= '<div class="col-sm-10" >';
        $html.= '<div class="checkbox">';
        $html.= '<label><input type="checkbox" style="float: left" id="'.$name.'" name="related_options['.$name.']" value="1" '.((isset($modules[$name]) && $modules[$name]) ? 'checked' : '').' >';
        $html.= '</label></div>';
        $html.= '</div>';
        $html.= '</div>'."\n";
        
        if ( $onchange ) {
          $html.= '<script type="text/javascript"><!--'."\n";
					$html.= '$(document).ready( function(){ $("#'.$name.'").change(function(){ '.$onchange.' }); $("#'.$name.'").change(); });';
					$html.= "\n".'--></script>';
        }
        
        echo $html;
      }
      
      function show_checkbox_export($field) {
        
        $html = '<div class="form-group" >';
        $html.= '<label class="col-sm-2 control-label" for="'.$field.'" >'.$field.'</label>';
        $html.= '<div class="col-sm-10" >';
        $html.= '<div class="checkbox">';
        $html.= '<label><input type="checkbox" id="'.$field.'" name="export['.$field.']" value="1" '.((isset($export[$field]) && $export[$field])?("checked"):("")).' >';
        //$html.= ' '.$field.'</label></div>';
        $html.= '</label></div>';
        $html.= '</div>';
        $html.= '</div>'."\n";
        echo $html;
      }
      
      function show_select($modules, $name, $title, $help, $values, &$html_scripts, $parent=false, $with_delimiters=false) {
        
        $html = '<div class="form-group" '.($parent ? ' style="display:none" ' : '').'>';
        $html.= '<label class="col-sm-2 control-label" for="'.$name.'" >';
         if ($help != '') {
          $html.= '<span data-toggle="tooltip" title="" data-original-title="'.$help.'">'.$title.'</span></label>';
        } else {
          $html.= ''.$title.'</label>';
        }
        if ( $with_delimiters ) {
					$html.= '<div class="col-sm-2" >';
				} else {
					$html.= '<div class="col-sm-10" >';
				}
        $html.= '<select name="related_options['.$name.']" id="'.$name.'" class="form-control">';
        $vals_cnt = 0;
        foreach ($values as $val=>$text) {
          $selected = ($vals_cnt==0 && !isset($modules[$name])) || (isset($modules[$name]) && $modules[$name]==$val);
          $html.= '<option value="'.$val.'" '.($selected?'selected':'').'>'.$text.'</option>';
          $vals_cnt++;
        }
        
        $html.= '</select>';
        $html.= '</div>';
				
				if ( $with_delimiters ) {
					$delimiters = array('_delimiter_product', '_delimiter_ro');
					foreach ( $delimiters as $delimiter ) {
						$html.= '<label class="col-sm-2 control-label" for="'.$name.$delimiter.'" style="display:none;">';
						$html.= $with_delimiters['entry_'.$name.$delimiter];
						$html.= '</label>';
						$html.= '<div class="col-sm-2" style="display:none;">';
						$html.= '<input type="text" class="form-control" name="related_options['.$name.$delimiter.']" id="'.$name.$delimiter.'" value="'.( isset($modules[$name.$delimiter]) ? $modules[$name.$delimiter] : '' ).'">';
						$html.= '</div>';
					}
				}
				
        $html.= '</div>'."\n";
				
				if ($parent) {
					$html_scripts.= '<script type="text/javascript"><!--'."\n";
					$html_scripts.= '$("#'.$parent.'").change(function(){ $("#'.$name.'").parent().parent().toggle($(this).is(":checked"));  });';
					$html_scripts.= '$("#'.$parent.'").change();';
					$html_scripts.= "\n".'--></script>';
				}
				
        echo $html;
      }
      
    ?>
    
    
		<div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
				
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-related-options" class="form-horizontal">
        
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-settings" data-toggle="tab"><?php echo $entry_settings; ?></a></li>
            <li><a href="#tab-additional" data-toggle="tab"><?php echo $entry_additional; ?></a></li>
            <li><a href="#tab-import" data-toggle="tab"><?php echo $entry_import; ?></a></li>
            <li><a href="#tab-export-new" data-toggle="tab"><?php echo $entry_export_new; ?></a></li>
            <li><a href="#tab-import-new" data-toggle="tab"><?php echo $entry_import_new; ?></a></li>
						<li><a href="#tab-about" data-toggle="tab" id="tab-about-button"><?php echo $entry_about; ?></a></li>
          </ul>
          
          <div class="tab-content">
            <div class="tab-pane active" id="tab-settings">
              
              
              <?php
              
                show_checkbox($modules, 'update_quantity', $entry_update_quantity, $entry_update_quantity_help);
                
                $on_change_function = "$('#update_options_remove').closest('.form-group').toggle( $('#update_options').is(':checked') );";
                show_checkbox($modules, 'update_options', $entry_update_options, $entry_update_options_help, $on_change_function);
                show_checkbox($modules, 'update_options_remove', $entry_update_options_remove, $entry_update_options_remove_help);
                
								show_select($modules, 'subtract_stock', $entry_subtract_stock, $entry_subtract_stock_help, array( 0 => $text_subtract_stock_from_product
																																																								,	1 => $text_subtract_stock_from_product_first_time
																																																								,	2 => $text_yes
																																																								,	3 => $text_no
																																																								), $html_scripts, 'update_options');
								
								show_select($modules, 'required', $entry_required, $entry_required_help, array( 									0 => $text_yes
																																																								,	1 => $text_no
																																																								,	2 => $text_required_first_time
																																																								), $html_scripts, 'update_options');
								
                show_checkbox($modules, 'allow_zero_select', $entry_allow_zero_select, $entry_allow_zero_select_help);
                show_checkbox($modules, 'stock_control', $entry_stock_control, $entry_stock_control_help);
              
                show_select($modules, 'show_clear_options', $entry_show_clear_options, $entry_show_clear_options_help, array( 0 => $option_show_clear_options_not
                                                                                                                   ,1 => $option_show_clear_options_top
                                                                                                                   ,2 => $option_show_clear_options_bot
                                                                                                                  ), $html_scripts);
              
                show_checkbox($modules, 'hide_inaccessible', $entry_hide_inaccessible, $entry_hide_inaccessible_help);
								
								show_checkbox($modules, 'hide_option', $entry_hide_option, $entry_hide_option_help);
								
								show_checkbox($modules, 'unavailable_not_required', $entry_unavailable_not_required, $entry_unavailable_not_required_help);
                
                show_select($modules, 'select_first', $entry_select_first, $entry_select_first_help, array( 0 => $option_select_first_not
                                                                                                 ,1 => $option_select_first
                                                                                                 ,2 => $option_select_first_last
                                                                                                 ,3 => $option_select_first_always
                                                                                                ), $html_scripts);
                
                show_checkbox($modules, 'step_by_step', $entry_step_by_step, $entry_step_by_step_help);
                show_checkbox($modules, 'disable_all_options_variant', $entry_ro_disable_all_options_variant, $entry_ro_disable_all_options_variant_help);
                show_checkbox($modules, 'ro_use_variants', $entry_ro_use_variants, $entry_ro_use_variants_help, 'show_options_variants()');
              
              ?>
              
                
              <div class="table-responsive" id="options_variants" style="display: none">
                <table id="variants_list" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left" style="width: 250px"><?php echo $entry_ro_variant_name; ?></td>
                      <td class="text-left" ><?php echo $entry_ro_options; ?></td>
											<td class="text-left" style="width: 150px"><?php echo $entry_ro_sort_order; ?></td>
                      <td></td>
                    </tr>
                  </thead>
                  
                  <tbody>
                  </tbody>
                  
                  <tfoot>
                    <td colspan="3"></td>
                    <td>
                      <button type="button" onclick="add_new_variant();" data-toggle="tooltip" title="<?php echo $entry_ro_add_variant; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button>
                    </td>
                  </tfoot>
  
                </table>
              </div>
             
              <input type="hidden" name="related_options[related_options_version]" value="<?php echo $modules['related_options_version'].""; ?>">
              
            </div>
            
            <div class="tab-pane" id="tab-additional">
              
              <div style="margin-bottom: 30px;"><?php echo $entry_additional_fields; ?></div>
              
              <?php
              
								$values = array( 	0 => $entry_spec_model_0,
																	1 => $entry_spec_model_1,
																	2 => $entry_spec_model_2,
																	3 => $entry_spec_model_3,
																);
							
								show_select($modules, 'spec_model', $entry_spec_model, $entry_spec_model_help, $values, $html_scripts, false,
															array('entry_spec_model_delimiter_product'=>$entry_spec_model_delimiter_product,
																		'entry_spec_model_delimiter_ro'=>$entry_spec_model_delimiter_ro,
																		) );
							
                //show_checkbox($modules, 'spec_model', $entry_spec_model, $entry_spec_model_help);
                show_checkbox($modules, 'spec_sku', $entry_spec_sku, $entry_spec_sku_help);
                show_checkbox($modules, 'spec_upc', $entry_spec_upc, $entry_spec_upc_help);
								show_checkbox($modules, 'spec_ean', $entry_spec_ean, $entry_spec_ean_help);
                show_checkbox($modules, 'spec_location', $entry_spec_location, $entry_spec_location_help);
								show_checkbox($modules, 'spec_ofs', $entry_spec_ofs, $entry_spec_ofs_help);
                show_checkbox($modules, 'spec_weight', $entry_spec_weight, $entry_spec_weight_help);
								show_checkbox($modules, 'spec_price_prefix', $entry_spec_price_prefix, $entry_spec_price_prefix_help);
                show_checkbox($modules, 'spec_price', $entry_spec_price, $entry_spec_price_help);
								
                show_checkbox($modules, 'spec_price_discount', $entry_spec_price_discount, $entry_spec_price_discount_help);
                show_checkbox($modules, 'spec_price_special', $entry_spec_price_special, $entry_spec_price_special_help);
                
              ?>
              
              
            </div>
            </form>
            
            <div class="tab-pane" id="tab-import">
              <div style="margin-bottom: 30px;"><?php echo $entry_import_description; ?></div>
              
              <?php if ($PHPExcelExists) { ?>
              
                <div class="form-group" >
									
									<label class="col-sm-2 control-label"></label>
									
									<div class="col-sm-10" >
									
										<label class="radio">
											<input type="radio" name="import_delete_before" value="0" checked><?php echo $entry_import_nothing_before; ?>
										</label>
										<label class="radio">
											<input type="radio" name="import_delete_before" value="1"><?php echo $entry_import_delete_before; ?>
										</label>
										<label class="radio">
											<input type="radio" name="import_delete_before" value="2"><?php echo $entry_import_product_before; ?>
										</label>
										
									</div>	
                 
                </div>
              
                <div class="form-group">
                  <label class="col-sm-2 control-label"></label>
                  <div class="col-sm-10" >
                    <button type="button" id="button-upload" data-toggle="tooltip" title="" class='btn btn-primary' data-original-title="<?php echo $button_upload; ?>"><?php echo $button_upload; ?></button>
                    <span class="help-block"><?php echo $button_upload_help ?></span>
                  </div>
                </div>
                
                
              <?php } else { ?>
                <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $entry_PHPExcel_not_found.$PHPExcelPath; ?></div>
              <?php } ?>
              
              
              
              <div style="margin-top: 30px;" id="import_result"></div>
              
              <div style="margin-top: 30px;" id="import_result_text"></div>
            </div>
						
            <div class="tab-pane" id="tab-export-new">
              <div style="margin-bottom: 30px;"><?php echo $entry_export_new_description; ?></div>
              
              <form id="export_new_form" action="<?php echo $export_new_action; ?>" method="post" target="export_new_frame">
								<?php /*
                <fieldset>
                  <legend>
                    <?php echo $entry_export_new_fields; ?>
                  </legend>
                  
                  <div style="padding-left:30px;">
                    <?php foreach ($export_new_fields as $field) { ?>
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="export_fields[]" value="<?php echo $field; ?>" <?php if (!empty($export_new_fields_selected['related_options_export_fields']) && in_array($field, $export_new_fields_selected['related_options_export_fields'])) echo 'checked'; ?> >
                          <?php echo $field; ?>
                        </label>
                      </div>
                    <?php } ?>
                  </div>
                  
                </fieldset>
                */ ?>
								
								<div class="form-group" >
									<label class="col-sm-2 control-label">
										<?php echo $entry_export_new_fields; ?>
									</label>
									<div class="col-sm-10" >
										<?php foreach ($export_new_fields as $field) { ?>
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="export_fields[]" value="<?php echo $field; ?>"
														<?php if (!empty($export_new_settings['export_fields']) && in_array($field, $export_new_settings['export_fields']) ) {
															echo 'checked';
														} ?>
													>
                          <?php echo $field; ?>
                        </label>
                      </div>
                    <?php } ?>
										<br>
										<button type="button" onclick="$('#export_new_form input[type=checkbox]').prop('checked', true);" data-toggle="tooltip" title="" class='btn btn-primary' data-original-title="<?php echo $entry_export_new_check_all; ?>"><?php echo $entry_export_new_check_all; ?></button>
									</div>	
                </div>
								
								<div class="form-group" >
									<label class="col-sm-2 control-label">
										<?php echo $ro_entry_export_method; ?>
									</label>
									<div class="col-sm-10" >
										<select name="export_new_method" class="form-control">
											<option value="0"><?php echo $ro_entry_export_method_all; ?></option>
											<option value="1"><?php echo $ro_entry_export_method_by_product_ids; ?></option>
											<option value="2"><?php echo $ro_entry_export_method_by_ro_variant; ?></option>
										</select>
									</div>
                </div>
              
								<div class="form-group" id="export_settings_by_product_ids" style="display: none;">
									<label class="col-sm-2 control-label">
										<?php echo $ro_entry_start_product_id; ?> ( <?php echo $ro_entry_min_product_id; ?> <?php echo $min_product_id; ?> )
									</label>
									<div class="col-sm-2" >
										<input name="export_new_start_product_id" class="form-control" value="">
									</div>	
									<label class="col-sm-2 control-label">
										<?php echo $ro_entry_end_product_id; ?> ( <?php echo $ro_entry_max_product_id; ?> <?php echo $max_product_id; ?> )
									</label>
									<div class="col-sm-2" >
										<input name="export_new_end_product_id" class="form-control" value="">
									</div>	
                </div>
								
								<div class="form-group" id="export_settings_by_ro_variant" style="display: none;">
									<label class="col-sm-2 control-label">
										<?php echo $ro_entry_export_by_variant; ?>
									</label>
									<div class="col-sm-10" >
										<select name="export_new_variant_id" class="form-control">
											<?php foreach ($variants_options as $vo_id => $vo_data) { ?>
												<option value="<?php echo $vo_id; ?>"><?php echo $vo_data['name']; ?></option>
											<?php } ?>
										</select>
									</div>	
                </div>
                
                <?php if ($export_new_PHPExcelExists) { ?>
									
									<div class="form-group" >
										<label class="col-sm-2 control-label">
										</label>
										<div class="col-sm-10" >
											<button type="button" onclick="$('#export_new_form').submit();" data-toggle="tooltip" title="" class='btn btn-primary' data-original-title="<?php echo $entry_export_new_get_file; ?>"><?php echo $entry_export_new_get_file; ?></button>
										</div>	
									</div>
									<?php /*
                  <div style="padding:20px;">
                      <button type="button" onclick="$('#export_new_form input[type=checkbox]').prop('checked', true);" data-toggle="tooltip" title="" class='btn btn-primary' data-original-title="<?php echo $entry_export_new_check_all; ?>"><?php echo $entry_export_new_check_all; ?></button>
                      <button type="button" onclick="$('#export_new_form').submit();" data-toggle="tooltip" title="" class='btn btn-primary' data-original-title="<?php echo $entry_export_new_get_file; ?>"><?php echo $entry_export_new_get_file; ?></button>
                  </div>
                  */ ?>
                  
                <?php } else { ?>
                  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $entry_PHPExcel_not_found.$PHPExcelPath; ?></div>
                <?php } ?>
                
                <iframe name="export_new_frame" src="" id="export_new_frame" style="display: none">
                </iframe>
                
              </form>
              
            </div>
            
            <div class="tab-pane" id="tab-import-new">
              <div style="margin-bottom: 30px;"><?php echo $entry_import_new_description; ?></div>
              
              <?php if ($PHPExcelExists) { ?>
								
                <div style="padding-left: 40px;" >
                
                  <label class="radio">
                    <input type="radio" name="import_new_delete_before" value="0" checked> <?php echo $entry_import_new_nothing_before; ?>
                  </label>
                  <label class="radio">
                    <input type="radio" name="import_new_delete_before" value="1"> <?php echo $entry_import_new_delete_before; ?>
                  </label>
                  <label class="radio">
                    <input type="radio" name="import_new_delete_before" value="2"> <?php echo $entry_import_new_product_before; ?>
                  </label>
                  
                </div>	
               
                <div style="padding:20px;">
                  <button type="button" id="import-new-button-upload" data-toggle="tooltip" title="" class='btn btn-primary' data-original-title="<?php echo $entry_import_new_button_upload; ?>"><?php echo $entry_import_new_button_upload; ?></button>
                  <span class="help-block"><?php echo $entry_import_new_button_upload_help ?></span>
                </div>
                
                <div style="margin-top: 30px;" id="import_new_result"></div>
              
                <div style="margin-top: 30px;" id="import_new_result_text"></div>
                
              <?php } else { ?>
                <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $entry_PHPExcel_not_found.$PHPExcelPath; ?></div>
              <?php } ?>
              
            </div>
            
						<div class="tab-pane" id="tab-about">
							
							<div id="module_description">
								<?php echo $module_description; ?>
							</div>
							
							<hr>
							<?php echo $text_conversation; ?>
							<hr>
							
							<br>
							<h4><?php echo $entry_we_recommend; ?></h4><br>
							<div id="we_recommend">
								<?php echo $text_we_recommend; ?>
							</div>
							
						</div>
            
          </div>
        
          
        
				<hr>
				<span class="help-block"><?php echo $entry_ro_version.": ".$modules['related_options_version']; ?> | <?php echo $text_ro_support; ?> | <span id="module_page"><?php echo $text_extension_page; ?></span></span><span class="help-block" style="font-size: 80%; line-height: 130%; margin-bottom: 0px;"><?php echo $module_copyright; ?></span>
      </div>
    </div>
  </div>
</div>

<?php
  if ( $html_scripts ) {
    echo $html_scripts; 
  }
?>
<script type="text/javascript"><!--
var import_upload_timer;
$('#button-upload').on('click', function() {
	$('#form-upload').remove();
	
	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name="file"]').trigger('click');
	

	clearInterval(import_upload_timer);
	import_upload_timer = setInterval(function() {
    
    if ( !$('#form-upload input[name="file"]').length ) {
      clearInterval(import_upload_timer);
      return;
    }
    
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(import_upload_timer);		
			
			var form_data = new FormData($('#form-upload')[0]);
			
			form_data.append("import_delete_before", $('input:radio[name="import_delete_before"]:checked').val() );
			
			$.ajax({
				url: 'index.php?route=module/related_options/import&token=<?php echo $token; ?>',
				type: 'post',		
				dataType: 'json',
				data: form_data,
				//data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,		
				beforeSend: function() {
					$('#button-upload').button('loading');
				},
				complete: function() {
					$('#button-upload').button('reset');
				},	
				success: function(json) {
					
          $('#import_result_text').html("<?php echo $entry_server_response; ?>: "+json);
          			
					if (json['success']) {
						alert(json['success']);
					}
        
          if (json['error']) {
            $('#import_result_text').html('Error: '+json['error']);
          } else {
            $('#import_result_text').html('<?php echo $entry_import_result; ?>: '+json['products']+'/'+json['relatedoptions']);
						if (json['warning']) {
							$('#import_result_text').append('<br><br>Warning: '+json['warning']);
						}
          }
          
				},			
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
      
      $('#form-upload').remove();
      
		}
	}, 500);
});

$('#import-new-button-upload').on('click', function() {
	$('#form-upload').remove();
	
	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name="file"]').trigger('click');

	clearInterval(import_upload_timer);
	import_upload_timer = setInterval(function() {
    
    if ( !$('#form-upload input[name="file"]').length ) {
      clearInterval(import_upload_timer);
      return;
    }
    
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(import_upload_timer);		
			
			var form_data = new FormData($('#form-upload')[0]);
			
			form_data.append("import_delete_before", $('input:radio[name="import_new_delete_before"]:checked').val() );
			
			$.ajax({
				url: 'index.php?route=module/related_options/import_new&token=<?php echo $token; ?>',
				type: 'post',		
				dataType: 'json',
				data: form_data,
				//data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,		
				beforeSend: function() {
					$('#import-new-button-upload').button('loading');
					$('#import_new_result_text').html('<i id="loading-animation" class="fa fa-cog fa-spin fa-2x fa-fw"></i>');
					//$('#import-new-button-upload').after('<i id="loading-animation" class="fa fa-cog fa-spin fa-2x fa-fw"></i>');
				},
				complete: function() {
					$('#import-new-button-upload').button('reset');
					$('#loading-animation').remove();
				},	
				success: function(json) {
					
          //$('#import_new_result_text').html("<?php echo $entry_server_response; ?>: "+json);
					// in case of wrong server response
					html = "<?php echo $entry_server_response; ?>: "+json;
          			
					if (json['success']) {
						alert(json['success']);
					}
        
          if (json['error'] && json['error'].length) {
            var html = '';
            for (var i in json['error']) {
              html+= '<br>'+json['error'][i]+'';
            }
            html = '<?php echo $entry_import_new_error; ?>'+html;
          } else {
						var html = '<?php echo $entry_import_new_result; ?>: '+json['products']+'/'+json['relatedoptions'];
          }
          $('#import_new_result_text').html(html);
				},			
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
      
      $('#form-upload').remove();
      
		}
	}, 500);
});

//--></script>

<script type="text/javascript">
  
  var variant_cnt = 0;
  var options_cnt = 0;
  var all_options = <?php echo json_encode($options); ?>;
  
	$('#spec_model').change(function(){
		
		var show_delimiter_product = ( $(this).val() == 3);
		var delimiter_product = 'spec_model_delimiter_product';
		$('#'+delimiter_product).parent().toggle(show_delimiter_product);
		$('label[for="'+delimiter_product+'"]').toggle(show_delimiter_product);
		
		<?php if ( $extension_code == 'ropro' ) { ?>
			var show_delimiter_ro = ( $(this).val() == 2 || $(this).val() == 3);
			var delimiter_ro = 'spec_model_delimiter_ro';
			$('#'+delimiter_ro).parent().toggle(show_delimiter_ro);
			$('label[for="'+delimiter_ro+'"]').toggle(show_delimiter_ro);
		<?php } ?>
	});
	$('#spec_model').change();
  
  function add_option(elem_num, option_id) {
    
    var str_add = "";
    
    str_add += "<div id='variant_option"+options_cnt+"'>";
    str_add += "<div style='float:left;display:inline;margin-right:10px;'>";
    str_add += "<select name='variants["+elem_num+"][options]["+options_cnt+"]' style=\"width: 180px;\" class=\"form-control\" >";
    
    for (var i in all_options) {
			var current_option = all_options[i];
      str_add += '<option value="'+current_option['option_id']+'" '+((option_id==current_option['option_id'])?("selected"):(""))+' >'+current_option['name']+'</option>';  
    }
    str_add += "</select>";
    str_add += "</div>";
    str_add += "<button type=\"button\" onclick=\"$('#variant_option"+options_cnt+"').remove();\" data-toggle=\"tooltip\" title=\"<?php echo $entry_ro_delete_option; ?>\" class=\"btn btn-danger\" data-original-title=\"<?php echo $entry_ro_delete_option; ?>\"><i class=\"fa fa-minus-circle\"></i></button>";
    str_add += "</div>";
    
    options_cnt++;
    
    $('#variant_options'+elem_num).append("<tr><td>"+str_add+"</td></tr>");
  }
  
  function add_new_variant() {
    add_variant('', '', '', '');
  }
  
  function add_variant(variant_id, variant_name, variant_options, variant_sort_order) {
    
    var str_add = "";
    str_add += "<tr id='variant"+variant_cnt+"'>";
    str_add += "<td class='text-right' style='vertical-align: top;'>";
    str_add += '<input name="variants['+variant_cnt+'][name]" size=\'40\' value="'+variant_name+'" class="form-control">';
    str_add += "<input type='hidden' name='variants["+variant_cnt+"][id]' value='"+variant_id+"'>";
    
    str_add += "<button type='button' onclick='add_option("+variant_cnt+",0);' data-toggle='tooltip' title='<?php echo $entry_ro_add_option; ?>' class='btn btn-primary' data-original-title='<?php echo $entry_ro_add_option; ?>'><i class='fa fa-plus-circle'></i></button>";
    
    str_add += "</td>";
    str_add += "<td id='variant_options"+variant_cnt+"'>";
    str_add += "";
    str_add += "</td>";
		
		str_add += "</td>";
    str_add += "<td>";
    str_add += "<input type='text' class='form-control'  name='variants["+variant_cnt+"][sort_order]' value='"+variant_sort_order+"'>";
    str_add += "</td>";
		
    str_add += "<td><button type='button' onclick=\"$('#variant"+variant_cnt+"').remove();\" data-toggle='tooltip' title='<?php echo $entry_ro_delete_variant; ?>' class='btn btn-danger' data-original-title='<?php echo $entry_ro_delete_variant; ?>'><i class='fa fa-minus-circle'></i></button></td>";
    str_add += "";
    str_add += "</tr>";
    
    $('#variants_list').append(str_add);
    
    if (variant_options) {
      for (var i = 0, length = variant_options.length; i < length; i++) {
        if (i in variant_options) {
          add_option(variant_cnt, variant_options[i]);  
        }
      }
    }
    
    variant_cnt++;
  }
  
  function check_for_updates() {
		
		$.ajax({
			url: '//update.liveopencart.com/upd.php',
			type: 'post',
			data: {module:'<?php echo $extension_code; ?>', version:'<?php echo $modules['related_options_version']; ?>', lang: '<?php echo $config_admin_language; ?>'},
			dataType: 'json',
	
			success: function(data) {
				
				if (data) {
					
					if (data['recommend']) {
						$('#we_recommend').html(data['recommend']);
					}
					if (data['update']) {
						$('#tab-about-button').append('&nbsp;&nbsp;<font style="color:red;font-weight:normal;"><?php echo addslashes($text_update_alert); ?></font>');
						$('#module_description').after('<hr><div class="alert alert-info" role="alert">'+data['update']+'</div>');
					}
					if (data['product_pages']) {
						$('#module_page').html(data['product_pages']);
					}
				}
			}
		});
	}
	
  function show_options_variants() {
    $('#options_variants').toggle($('#ro_use_variants').is(':checked'));
  }
  
  $(document).ready(  function () {
    show_options_variants();
    
    <?php
      foreach ($variants_options as $vo_id => $vo_data) {
        
        $str_arr = "";
        foreach ($vo_data['options'] as $current_option) {
          $str_arr .= ",".$current_option['option_id'];
        }
        if ($str_arr != "") $str_arr = substr($str_arr, 1);
        echo "add_variant(".$vo_id.", \"".$vo_data['name']."\", [".$str_arr."], \"".$vo_data['sort_order']."\");";
        
      }
    ?>
		
		check_for_updates();
    
  } );
	
	$('select[name="export_new_method"]').change(function(){
		$('#export_settings_by_product_ids').toggle( $(this).val()==1 );
		$('#export_settings_by_ro_variant').toggle( $(this).val()==2 );
	})
	.change();
	
	<?php if ( !empty($export_new_settings) ) { ?>
		
		var export_new_settings = <?php echo json_encode($export_new_settings); ?>;
		if ( export_new_settings['export_new_method'] ) {
			$('select[name="export_new_method"]').val(export_new_settings['export_new_method'])
			.change();
		}
		if ( export_new_settings['export_new_variant_id'] ) {
			$('select[name="export_new_variant_id"]').val(export_new_settings['export_new_variant_id']);
		}
		if ( export_new_settings['export_new_start_product_id'] ) {
			$('input[name="export_new_start_product_id"]').val(export_new_settings['export_new_start_product_id']);
		}
		if ( export_new_settings['export_new_end_product_id'] ) {
			$('input[name="export_new_end_product_id"]').val(export_new_settings['export_new_end_product_id']);
		}
	<?php } ?>
  
</script>


<?php echo $footer; ?>
<script type="text/javascript"><!--

function ro_getCommonFunctions(ro_instance) {
	<?php
		// << Product Image Option DropDown compatibility
		if ( isset($text_select_your) && isset($options) && is_array($options) ) {
			echo "var ro_piodd_select_texts = [];\n";
			foreach ($options as $option) {
				if ($option['type'] == 'image') {
					echo "ro_piodd_select_texts[".$option['product_option_id']."] = '".$text_select_your.$option['name']."';\n";
				}
			}
		}
		// >> Product Image Option DropDown compatibility
	?>
	
	var ro_functions = {
	
		// << Product Image Option DropDown compatibility
		piodd_setValue : function(product_option_id, value) { // ro_piodd_set_value
		
			var radio_elems = ro_instance.getOptionElement('input[type=radio][name="'+ro_instance.option_prefix+'['+product_option_id+']"]');
			if (radio_elems.length) {
				var piodd_option_div = radio_elems.closest('div[id^=option-]').find('[id^=image-option]');
				if (piodd_option_div.length) {
					
					piodd_option_div.find('a.dd-option').removeClass('dd-option-selected');
					piodd_option_div.find('input.dd-selected-value').val(value);
					if (value) {
						var piodd_selected_a = piodd_option_div.find('input.dd-option-value[value='+value+']').parent().addClass('dd-option-selected');
						piodd_option_div.find('a.dd-selected').html('');
						piodd_option_div.find('a.dd-selected').append( piodd_selected_a.find('img').clone().removeClass('dd-option-image').addClass('dd-selected-image') );
						piodd_option_div.find('a.dd-selected').append( piodd_selected_a.find('label').clone().removeClass('dd-option-text').addClass('dd-selected-text') );
					} else {
						if (ro_piodd_select_texts[product_option_id]) {
							piodd_option_div.find('a.dd-selected').html(ro_piodd_select_texts[product_option_id]);
						}
					}
				}
			}
		},
		// >> Product Image Option DropDown compatibility
		
		clearOptions : function() { // ro_clear_options
		
			ro_instance.getOptionElement('input[type=radio][name^="'+ro_instance.option_prefix+'"]:checked').each(function(){
				var product_option_id = ro_instance.getProductOptionIdFromName($(this).attr('name'));
				ro_instance.setOptionValue(product_option_id, ''); // compatible also with PIODD
			});
		
			ro_instance.getOptionElement('select[name^="'+ro_instance.option_prefix+'"]').val('');
			
			ro_instance.getOptionElement('textarea[name^="'+ro_instance.option_prefix+'"]').val('')
			
			ro_instance.getOptionElement('input[type=text][name^="'+ro_instance.option_prefix+'"]').val('');
			
			ro_instance.getOptionElement('input[type=checkbox][name^="'+ro_instance.option_prefix+'"]').prop('checked', false);
			
			ro_instance.getOptionElement('input[type=hidden][name^="'+ro_instance.option_prefix+'"]').val('')
			
			if ( typeof(ro_instance.controlAccessToValuesOfAllOptions) == 'function' ) {
				ro_instance.controlAccessToValuesOfAllOptions();
			}
			
			/*
			if ( typeof(ro_options_values_access) == 'function' ) {
				ro_options_values_access();
			}
			*/
			ro_functions.updateViewOfBlockOptions();
			ro_functions.updateViewOfJournalPushButtons();
			
			$('#input-quantity, input[name="quantity"]').change();
			
			<?php if (isset($ro_theme_name) && $ro_theme_name=='journal2') { ?>
				if (Journal.updatePrice) {
					Journal.updateProductPrice();
				}
			<?php } elseif ( $ro_theme_name=='so-furnicom') { ?>
				if ( $('#product-quick').length ) {
					$('#product .option-content-box').removeClass('active');
				} else {
					$('#product .options .option-content-box').removeClass('active');
				}
			<?php } ?>
			
			// Quantity per options
			if ( typeof(qpo_resetQuantities) == 'function' ) {
				qpo_resetQuantities( 2 ); // to defaults
			}
			
			// Improved Options - option description compatibility
			if ( typeof(improvedoptions_show_description) == 'function' ) {
				$('select[name^="'+ro_instance.option_prefix+'"], input:radio[name^="'+ro_instance.option_prefix+'"], input:checkbox[name^="'+ro_instance.option_prefix+'"]').each(function(){
					improvedoptions_show_description(this);
				});
			}
			
			// Improved Options - set default value after reset 
			if ( typeof(improvedoptions_set_defaults) == 'function' ) {
				improvedoptions_set_defaults();
			}
			
			/*
			// Parent-child options - compatibility
			if ( typeof(pcop_check_visibility) == 'function' ) {
				pcop_check_visibility();
			}
			
			// old Product Option Image PRO compatibility
			if ( typeof(poipExternalOptionChange)=='function' ) {
				poipExternalOptionChange();
			}
			// new Product Option Image PRO compatibility
			if ( typeof(poip_product)!='undefined' && typeof(poip_product.externalOptionChange) == 'function' ) {
				poip_product.externalOptionChange();
			}
			
			// Live Price
			if ( typeof(liveprice_recalc) == 'function' ) {
				liveprice_recalc(100);
			}
			*/
			
			ro_instance.executeFunctionsFromOtherExtensionsOnOptionChange();
			
			return false;
		},
		
		// Product Block Option & Product Color Option compatibility
		// make option block selected (the same as in original input/select)
		updateViewOfBlockOptions : function() { // ro_set_block_options
			if (ro_instance.use_block_options) {
			
				// Product Block Option & Product Color Option text clear
				ro_instance.getOptionElement('.options span[id^="option-text-"]').html('');
				//$('.option span[id^="option-text-"]').html('');
				
				ro_instance.getOptionElement('select[name^="'+ro_instance.option_prefix+'["]').find('option').each( function () {
					var poid = ro_instance.getProductOptionIdFromName($(this).parent().attr('name'));
					//$(this).parent().attr('name').substr(7, $(this).parent().attr('name').length-8);
					
					// Product Block Option
					// disable all SELECT blocks
					ro_instance.getOptionElement('a[id^="block-"][option-text-id$="-'+poid+'"]').removeClass('block-active');
					if ($(this).parent().val()) {
						ro_instance.getOptionElement('a[id^="block-"][option-text-id$="-'+poid+'"][option-value="'+$(this).parent().val()+'"]').addClass('block-active').click();
					}
					
					// Product Color Option
					ro_instance.getOptionElement('a[id^="color-"][option-text-id$="-'+poid+'"]').removeClass('color-active');
					if ($(this).parent().val()) {
						ro_instance.getOptionElement('a[id^="color-"][option-text-id$="-'+poid+'"][optval="'+$(this).parent().val()+'"]').addClass('color-active').click();
					}
					
				});
				
				// block options use RADIOs for images
				ro_instance.getOptionElement('input[type=radio][name^="'+ro_instance.option_prefix+'["]').each( function () {
					var poid = ro_instance.getProductOptionIdFromName($(this).attr('name'));
					//$(this).attr('name').substr(7, $(this).attr('name').length-8);
					
					// Product Block Option
					// disable only current RADIO block
					ro_instance.getOptionElement('a[id^="block-"][option-text-id$="-'+poid+'"][option-value="'+$(this).val()+'"]').removeClass('block-active');
					if ($(this).is(':checked')) {
						ro_instance.getOptionElement('a[id^="block-"][option-text-id$="-'+poid+'"][option-value="'+$(this).val()+'"]').addClass('block-active').click();
					}
					
					// Product Color Option
					ro_instance.getOptionElement('a[id^="color-"][option-text-id$="-'+poid+'"][optval="'+$(this).val()+'"]').removeClass('color-active');
					if ($(this).is(':checked')) {
						ro_instance.getOptionElement('a[id^="color-"][option-text-id$="-'+poid+'"][optval="'+$(this).val()+'"]').addClass('color-active').click();
					}
					
				});
			}
		},
		
		// Journal2 compatibility
		// make option block selected (the same as in original input/select)
		updateViewOfJournalPushButtons : function() { // ro_set_journal2_options
			
			if (ro_instance.ro_theme_name == 'journal2') {
				ro_instance.getOptionElement('select[name^="'+ro_instance.option_prefix+'["]').find('option').each( function () {
					var poid = $(this).parent().attr('name').substr(7, $(this).parent().attr('name').length-8);
					if ($(this).parent().val()) {
						$(this).parent().parent().find('li[data-value='+$(this).parent().val()+']').removeClass('selected').addClass('selected');
					} else {
						$(this).parent().parent().find('li[data-value]').removeClass('selected');
					}
				});
				
				// block options use RADIOs for images
				ro_instance.getOptionElement('input:radio[name^="'+ro_instance.option_prefix+'["]').each( function () {
					var poid = $(this).attr('name').substr(7, $(this).attr('name').length-8);
					// turn off only current block for RADIO
					//$(this).parent().find('li[data-value]').removeClass('selected');
					
					if ($(this).is(':checked')) {
						$('#input-option'+poid).parent().find('li[data-value='+$(this).val()+']').removeClass('selected').addClass('selected');
					} else {
						$('#input-option'+poid).parent().find('li[data-value='+$(this).val()+']').removeClass('selected');
					}
				});
				
				// block options use RADIOs for images
				ro_instance.getOptionElement('input:checkbox[name^="'+ro_instance.option_prefix+'["]').each( function () {
					var poid = $(this).attr('name').substr(7, $(this).attr('name').length-10);
					// turn off only current block for RADIO
					//$(this).parent().find('li[data-value]').removeClass('selected');
					
					if ($(this).is(':checked')) {
						$('#input-option'+poid).parent().find('li[data-value='+$(this).val()+']').removeClass('selected').addClass('selected');
					} else {
						$('#input-option'+poid).parent().find('li[data-value='+$(this).val()+']').removeClass('selected');
					}
				});
			}
		},
		
		journal2_makeOptionValueSelected : function(product_option_value_id) { // ro_journal2_set_value
		
			if ( ro_instance.ro_theme_name == 'journal2' && $('li[data-value="'+product_option_value_id+'"]').length) {
				
				var push_button_elem = $('li[data-value="'+product_option_value_id+'"]');
		
				push_button_elem.siblings('li').removeClass('selected');
				push_button_elem.removeClass('selected').addClass('selected');
				
				if ( Journal && Journal.updatePrice && typeof(Journal.updateProductPrice) == 'function' ) {
					Journal.updateProductPrice();
				}
				
			}
		},
		
		initBasic : function() { // ro_init_basic
			<?php
			
				if ( !empty($ro_settings['show_clear_options']) ) {
					if ((int)$ro_settings['show_clear_options'] == 1) { ?>
						$(document).ready( function() {
							<?php if ( $ro_theme_name == 'shopme' ) { ?>
								$('#product').prepend('<div class="form-group"><a href="#" id="clear_options"><?php echo $text_ro_clear_options; ?></a></div>');
							<?php } elseif ( $ro_theme_name == 'so-furnicom' ) { ?>
								if ( $('#product-quick').length ) {
									$('#product .form-group:first').before('<div class="form-group"><a href="#" id="clear_options"><?php echo $text_ro_clear_options; ?></a></div>');
								} else {
									$('#product .options .form-group:first').before('<div class="form-group"><a href="#" id="clear_options"><?php echo $text_ro_clear_options; ?></a></div>');
								}
							<?php } elseif ( $ro_theme_name == 'unishop' ) { ?>
								$('#product .option.row .form-group:first').before('<div class="form-group"><div class="col-xs-12"><a href="#" id="clear_options"><?php echo $text_ro_clear_options; ?></a></div></div>');
							<?php } elseif ( $ro_theme_name == 'revolution' ) { ?>
								ro_instance.getOptionElement('[id^="input-option"]:first').closest('.form-group').before('<div class="form-group"><a href="#" id="clear_options"><?php echo $text_ro_clear_options; ?></a></div>');
							<?php } elseif ( $ro_theme_name == 'theme725' ) { ?>
								if ( $('.ajax-quickview-cont').length ) { // quickview
									$('.ajax-quickview-cont .options:first').find('h3').after('<div class="form-group"><div class="col-sm-12"><div class="col-sm-12"><a href="#" id="clear_options"><?php echo $text_ro_clear_options; ?></a></div>');
								} else { // product page
									$('#product').find('h3').after('<div class="form-group"><a href="#" id="clear_options"><?php echo $text_ro_clear_options; ?></a></div>');
								}
							<?php } elseif ( $ro_theme_name == 'sellegance' ) { ?>
								ro_instance.getOptionElement('[name^="'+ro_instance.option_prefix+'["]').first().closest('.form-group').before('<div class="form-group"><a href="#" id="clear_options" ><?php echo $text_ro_clear_options; ?></a></div>');
							<?php } elseif ( $ro_theme_name == 'anystore' ) { ?>
								ro_instance.getOptionElement('[name^="'+ro_instance.option_prefix+'["]').first().closest('.form-group').before('<div class="form-group"><a href="#" id="clear_options" ><?php echo $text_ro_clear_options; ?></a></div>');		
							<?php } elseif ( $ro_theme_name == 'journal3' ) { ?>
								ro_instance.getOptionElement('[name^="'+ro_instance.option_prefix+'["]').first().closest('.form-group').before('<div class="form-group"><a href="#" id="clear_options" ><?php echo $text_ro_clear_options; ?></a></div>');
							<?php } elseif ( $ro_theme_name == 'oct_luxury' ) { ?>
								ro_instance.getOptionElement('[name^="'+ro_instance.option_prefix+'["]').first().closest('.form-group').before('<div class="form-group"><a href="#" id="clear_options" ><?php echo $text_ro_clear_options; ?></a></div>');	
							<?php } else { ?>
								$('#product').find('h3').after('<div class="form-group"><a href="#" id="clear_options"><?php echo $text_ro_clear_options; ?></a></div>');
							<?php } ?>
						});
					<?php } else { ?>
						$(document).ready( function() {
							<?php if ($ro_theme_name=='journal2') { ?>
								$('#product .options').after('<div class="form-group"><a href="#" id="clear_options" ><?php echo $text_ro_clear_options; ?></a></div>');
								//$('#product').find('h3:first').parent().append('<div class="form-group"><a href="#" id="clear_options" ><?php echo $text_ro_clear_options; ?></a></div>');
							<?php } elseif ($ro_theme_name=='sstore' || $ro_theme_name=='storeset') { ?>
								if ($('#popup-product .option').length) {
									$('#popup-product .option:last').after('<div class="form-group"><a href="#" id="clear_options"><?php echo $text_ro_clear_options; ?></a></div>')
								} else {
									$('.product-info .options').append('<div class="form-group"><a href="#" id="clear_options"><?php echo $text_ro_clear_options; ?></a></div>')
								}		
							<?php } elseif ( $ro_theme_name == 'shopme' ) { ?>
								$('#product .options').after('<div class="form-group"><a href="#" id="clear_options" ><?php echo $text_ro_clear_options; ?></a></div>');
							<?php } elseif ( $ro_theme_name == 'so-furnicom' ) { ?>
								if ( $('#product-quick').length ) {
									$('#product .form-group:last').after('<div class="form-group"><a href="#" id="clear_options"><?php echo $text_ro_clear_options; ?></a></div>');
								} else {
									$('#product .options .form-group:last').after('<div class="form-group"><a href="#" id="clear_options"><?php echo $text_ro_clear_options; ?></a></div>');
								}
							<?php } elseif ( $ro_theme_name == 'unishop' ) { ?>
								$('#product .option.row .form-group:last').after('<div class="form-group"><a href="#" id="clear_options"><div class="col-xs-12"><?php echo $text_ro_clear_options; ?></a></div></div>');	
							<?php } elseif ( $ro_theme_name == 'fastor' ) { ?>
								$('#product .options .options2').after('<div class="form-group"><a href="#" id="clear_options" ><?php echo $text_ro_clear_options; ?></a></div>');
							<?php } elseif ( $ro_theme_name == 'revolution' ) { ?>
								ro_instance.getOptionElement('[id^="input-option"]:last').closest('.form-group').after('<div class="form-group"><a href="#" id="clear_options"><?php echo $text_ro_clear_options; ?></a></div>');
							<?php } elseif ( $ro_theme_name == 'theme725' ) { ?>
								if ( $('.ajax-quickview-cont').length ) { // quickview
									$('.ajax-quickview-cont .options:first').append('<div class="form-group"><div class="col-sm-12"><a href="#" id="clear_options"><?php echo $text_ro_clear_options; ?></a></div></div>');
								} else { // product page
									$('#product #input-quantity').parent().before('<div class="form-group"><div class="col-sm-12"><a href="#" id="clear_options"><?php echo $text_ro_clear_options; ?></a></div></div>');
								}
							<?php } elseif ( $ro_theme_name == 'sellegance' ) { ?>
								ro_instance.getOptionElement('[name^="'+ro_instance.option_prefix+'["]').last().closest('.form-group').after('<div class="form-group"><a href="#" id="clear_options" ><?php echo $text_ro_clear_options; ?></a></div>');
							<?php } elseif ( $ro_theme_name == 'journal3' ) { ?>
								ro_instance.getOptionElement('[name^="'+ro_instance.option_prefix+'["]').last().closest('.form-group').after('<div class="form-group"><a href="#" id="clear_options" ><?php echo $text_ro_clear_options; ?></a></div>');
							<?php } elseif ( $ro_theme_name == 'oct_luxury' ) { ?>
								ro_instance.getOptionElement('[name^="'+ro_instance.option_prefix+'["]').last().closest('.form-group').after('<div class="form-group"><a href="#" id="clear_options" ><?php echo $text_ro_clear_options; ?></a></div>');
							<?php } elseif ( $ro_theme_name == 'pav_bigstore' ) { ?>
								ro_instance.getOptionElement('[name^="'+ro_instance.option_prefix+'["]').last().closest('.form-group').after('<div class="form-group"><a href="#" id="clear_options" ><?php echo $text_ro_clear_options; ?></a></div>');
							<?php } else { ?>
								$('#product #input-quantity').parent().before('<div class="form-group"><a href="#" id="clear_options"><?php echo $text_ro_clear_options; ?></a></div>');
							<?php } ?>
						});
					<?php } ?>
					
					$(document).on('click', '#clear_options', function(e){
						e.preventDefault();
						ro_functions.clearOptions();
					});
					
			<?php		
				}
			?>
		},
	}
	//ro_init_basic();
	return ro_functions;
}
	
//--></script>
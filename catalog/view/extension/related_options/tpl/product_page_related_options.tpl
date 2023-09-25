<script type="text/javascript"><!--

function ro_getSpecificFunctions(ro_instance) {

	var ro_functions = {
		stockControl : function(add_to_cart, start_now) { // ro_stock_control
			
			if ( ro_instance.timer_ro_stock_control ) {
				clearTimeout(ro_instance.timer_ro_stock_control);
			}
		
			<?php  if (!isset($ro_settings['stock_control']) || !$ro_settings['stock_control']) { ?>
			if (add_to_cart) {
				$('#button-cart').attr('allow_add_to_cart','allow_add_to_cart');
				$('#button-cart').click();
			}
			return;
			<?php } ?>
			
			if ( !start_now ) {
				ro_instance.timer_ro_stock_control = setTimeout(function(){ // to avoid multiple calls
					ro_functions.stockControl(add_to_cart, true);
				}, 50);
				return;
			}
		
			var erros_msg = '<?php echo $entry_stock_control_error; ?>';
			
			var options_values = ro_instance.getOptionValues([]);
			//var roids = ro_instance.getCurrentROIds(options_values);
			
			$('.alert-warning, .alert-warning').remove();
			<?php if ( $ro_theme_name == 'journal2' ) { ?>
				$('#ro_stock_alert').remove();
			<?php } elseif ( $ro_theme_name == 'journal3' ) { ?>
				$('#ro_stock_alert').remove();	
			<?php } ?>
			if ( $('#quantity_wanted').length ) { // themes like fastor
				$('#quantity_wanted').parent().parent().find('.text-danger').remove();
			}
			
			if ( ro_instance.ro_data && ro_instance.ro_data != [] && ro_instance.ro_data != {}) {
			//if (ro_exists) {
			
				ro_stock_control_last_call = (new Date()).getTime();
			
				$.ajax({
						url: 'index.php?route=module/related_options/get_ro_free_quantity&product_id=<?php echo $ro_product_id; ?>&call='+ro_stock_control_last_call,
						type: 'post',
						dataType: 'json',  
						data: $('select, input:radio:checked, input[type="text"]').filter('[name^="'+ro_instance.option_prefix+'"], [name^="quantity_per_option["]'),
						cache: false,      
						success: function (json) {
						
							if ( json && typeof(json['call']) != 'undefined' && json['call'] != ro_stock_control_last_call ) {
								return;
							}
							
							if ( ro_instance.input_quantity_per_options.length ) {
								$('#ro_stock_alert').remove();
							}
						
							var allow_add_to_cart = true;
							if ( json && json['quantity'] !== false ) {
								
								var ro_quantity = json['quantity'];
								var quantity = ro_instance.getQuantity();
								//var quantity = $('input[type=text][name=quantity]').val();
								
								if (parseInt(ro_quantity) < parseInt(quantity) ) {
									
									allow_add_to_cart = false;
									var alert_message = erros_msg.replace('%s',parseInt(ro_quantity));
									$('#ro_stock_alert').remove();
									<?php if ( $ro_theme_name == 'journal2' ) { ?>
										$('#input-quantity').closest('.form-group').after('<div class="form-group" id="ro_stock_alert"><div class="text-danger">'+alert_message+'</div></div>');
									<?php } elseif ( $ro_theme_name == 'journal3' ) { ?>
										if ( $('#product-quantity').closest('.popup-quickview').length ) {
											$('#product-quantity').closest('.buttons-wrapper').after('<div id="ro_stock_alert" class="form-group"><div class="alert alert-danger">'+alert_message+'</div></div>');
										} else {	
											$('#product-quantity').closest('.stepper-group').after('<div id="ro_stock_alert" class="form-group"><div class="alert alert-danger">'+alert_message+'</div></div>');
										}
									<?php } elseif ( $ro_theme_name == 'pav_bigstore' ) { ?>
										$('.product-extra').after('<div id="ro_stock_alert" class="form-group"><div class="alert alert-danger">'+alert_message+'</div></div>');
									<?php } else { ?>
										if ( $('#input-quantity').length && $('#input-quantity').closest('.tb_purchase_button').length ) { // themes like BurnEngine Shoppica
											$('#input-quantity').closest('.tb_purchase_button').after('<div class="alert alert-warning" id="ro_stock_alert">' + alert_message + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
										} else if ( $('#input-quantity').length ) { // standard
											$('#input-quantity').parent().after('<div class="alert alert-warning" id="ro_stock_alert">' + alert_message + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
										} else if ( $('#quantity_wanted').length ) { // themes like fastor
											$('#quantity_wanted').parent().parent().append('<div class="text-danger" id="ro_stock_alert">' + alert_message + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
										}
									<?php } ?>
									
								}
							}
							if ( json && typeof(json['quantity_per_option_value']) != 'undefined' && json['quantity_per_option_value'] ) {
								$('.alert-warning, .alert-warning, .text-danger').remove();
								// specific notification for quantity input/select per option value
								ro_instance.each(json['quantity_per_option_value'], function(max_quantity_per_value, product_option_value_id){
								//for ( var product_option_value_id in json['quantity_per_option_value'] ) {
								//	if ( !json['quantity_per_option_value'].hasOwnProperty(product_option_value_id) ) continue;
									//var max_quantity_per_value = json['quantity_per_option_value'][product_option_value_id]; 
									$('[name^="quantity_per_option["][data-value="'+product_option_value_id+'"]').closest('.row').after('<div class="text-danger">' + erros_msg.replace('%s',parseInt(max_quantity_per_value)) + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
									allow_add_to_cart = false;
								});
							} 
																		
							if (add_to_cart && allow_add_to_cart) {
								$('#button-cart').attr('allow_add_to_cart','allow_add_to_cart');
								$('#button-cart').click();
							}
							
						},
						error: function(error) {
							console.log(error);
						}
				});
			} else { // if there's no selected related options combination - use standard algorithm
				if (add_to_cart) {
					$('#button-cart').attr('allow_add_to_cart','allow_add_to_cart');
					$('#button-cart').click();
				}
			}
		},
		
		updateModel : function(){ // ro_set_model
		
			var options_values = ro_instance.getOptionValues([]);
			var ro_ids = ro_instance.getCurrentROIds(options_values);
			var product_model = "<?php echo addslashes( (isset($model)) ? $model : $ro_product_model ); ?>";
			var model = "";
			
			if (ro_ids.length) {
				ro_instance.each(ro_instance.ro_data, function(ro_dt){
				//for (var i in ro_instance.ro_data) {
				//	var ro_dt = ro_instance.ro_data[i];
					
					ro_instance.each(ro_ids, function(ro_id){
					//for (var j in ro_ids) {
					//	var ro_id = ro_ids[j];
						if (ro_dt['ro'][ro_id] && ro_dt['ro'][ro_id]['model']) {
							if (ro_instance.ro_settings['spec_model'] == 1) {
								model = ro_dt['ro'][ro_id]['model'];
							} else if (ro_instance.ro_settings['spec_model'] == 2 || ro_instance.ro_settings['spec_model'] == 3) {
								if ( ro_instance.ro_settings['spec_model_delimiter_ro'] && model ) {
									model+= ro_instance.ro_settings['spec_model_delimiter_ro'];
								}
								model+= ro_dt['ro'][ro_id]['model'];
							}
						}
					});
				});
			}
			
			if (model) {
				if (ro_instance.ro_settings['spec_model'] == 3) {
					if ( ro_instance.ro_settings['spec_model_delimiter_product'] ) {
						model = product_model + ro_instance.ro_settings['spec_model_delimiter_product'] + model;
					} else {
						model = product_model + model;
					}
				}
			} else {
				model = product_model;
			}
			$('#product_model').html(model);
		},
		
		updateStockInfo : function() { // ro_set_stock
			
			var stock_text = "<?php echo addslashes( !empty($text_stock) ? $text_stock : '' ); ?>";
			var stock = "<?php echo addslashes( isset($stock) ? $stock : '' ); ?>";
			var journal2_stock_status = '';
			if ( ro_instance.ro_theme_name == 'revolution' ) {
				if ( !stock && $('input.product_max').length ) { // quickorder
					stock = "<?php echo (isset($quantity) ? $quantity : ''); ?>";
				}
			} else if (ro_instance.ro_theme_name == 'journal2') {
				journal2_stock_status = "<?php echo isset($stock_status) ? $stock_status : ''; ?>";
			}
			
			var options_values = ro_instance.getOptionValues([]);
			var ro_ids = ro_instance.getCurrentROIds(options_values);
			
			if (ro_ids.length) {
				ro_instance.each(ro_instance.ro_data, function(ro_dt){
				//for (var i in ro_instance.ro_data) {
				//	var ro_dt = ro_instance.ro_data[i];
					
					ro_instance.each(ro_ids, function(ro_id){
					
						if (ro_dt['ro'][ro_id] && ro_dt['ro'][ro_id]['stock']) {
							stock = ro_dt['ro'][ro_id]['stock'];
							
							if (ro_instance.ro_theme_name == 'journal2') {
								journal2_stock_status = ro_dt['ro'][ro_id]['in_stock'] ? 'instock' : 'outofstock';
							}
							
							return false;
						}
					});
				});
			}
			
			if (ro_instance.ro_theme_name == 'journal2') {
				//journal2 uses specific price and stock update, but it's slow and doesn't swith block class (style)
				$('#product .p-stock .journal-stock').removeClass('instock, outofstock').addClass(journal2_stock_status);
				$('#product .p-stock .journal-stock').html(stock);
			} else if ( ro_instance.ro_theme_name == 'revolution' && $('input.product_max').length ) { // quickorder
				$('input.product_max').val(stock);		
			} else if ( ro_instance.ro_theme_name == 'revolution' && $('#product_stock').find('.pr_quantity').length && stock.indexOf('pr_quantity') == -1 ) {
				$('#product_stock').find('.pr_quantity').html(stock);
			} else {
				$('#product_stock').html(stock);
			}
			
		},
		
		// Block Option & journal2 compatibility
		// show/hide enable/disable options block
		updateAvailabilityOfBlockAndJournalPushButtons : function() { // ro_check_visibility_of_block_options
		
			if (ro_instance.use_block_options || ro_instance.ro_theme_name == 'journal2') {
				
				var available_values = [];
				
				// block options use SELECTs for select & radio
				ro_instance.getOptionElement('select[name^="'+ro_instance.option_prefix+'["]').find('option').each( function () {
					
					if ($(this).val()) {
						if (ro_instance.hide_inaccessible) {
							available_values.push( $(this).val() );
						} else {
							if (! $(this).attr('disabled')) {
								available_values.push( $(this).val() );
							}
						}
					}
					
				});
				
				// block options use RADIOs for images
				ro_instance.getOptionElement('input[type=radio][name^="'+ro_instance.option_prefix+'["]').each( function () {
					
					if (ro_instance.hide_inaccessible) {
						if ($(this)[0].style.display != 'none') {
							available_values.push( $(this).val() );
						}
					} else {
						if (!$(this).attr('disabled')) {
							available_values.push( $(this).val() );
						}
					}
					
				});
				
				// Product Block Option Module
				if ( ro_instance.use_block_options) {
					ro_instance.getOptionElement('a[id^=block-option],a[id^=block-image-option]').each( function () {
						if ($.inArray($(this).attr('option-value'), available_values) == -1) {
							$(this).removeClass('block-active');
							if (ro_instance.hide_inaccessible) {
								$(this).hide();
							} else {
								if (!$(this).attr('disabled')) {
									$(this).attr('disabled', true);
									$(this).fadeTo("fast", 0.2);
								}
							}
						} else {
							if (ro_instance.hide_inaccessible) {
								$(this).show();
							} else {
								if ($(this).attr('disabled')) {
									$(this).attr('disabled', false);
									$(this).fadeTo("fast", 1);
								}
							}
						}
						
					} );
					ro_instance.getOptionElement('a[id^=color-option]').each( function () {
						if ($.inArray($(this).attr('optval'), available_values) == -1) {
							$(this).removeClass('color-active');
							if (ro_instance.hide_inaccessible) {
								$(this).hide();
							} else {
								if (!$(this).attr('disabled')) {
									$(this).attr('disabled', true);
									$(this).fadeTo("fast", 0.2);
								}
							}
						} else {
							if (ro_instance.hide_inaccessible) {
								$(this).show();
							} else {
								if ($(this).attr('disabled')) {
									$(this).attr('disabled', false);
									$(this).fadeTo("fast", 1);
								}
							}
						}
					} );
				}
				
				// Journal2
				if ( ro_instance.ro_theme_name == 'journal2' ) {
				
					$('#product').find('li[data-value]').each(function() {
						if ($.inArray($(this).attr('data-value'), available_values) == -1) {
							$(this).removeClass('selected');
							if (ro_instance.hide_inaccessible) {
								$(this).hide();
							} else {
								if (!$(this).attr('disabled')) {
									$(this).attr('disabled', true);
									$(this).fadeTo("fast", 0.2);
								}
							}
						} else {
							if (ro_instance.hide_inaccessible) {
								$(this).show();
							} else {
								if ($(this).attr('disabled')) {
									$(this).attr('disabled', false);
									$(this).fadeTo("fast", 1);
								}
							}
						}
						
						// change standart Journal2 function
						$(this).unbind('click');
						
						
						$(this).click(function () {
							if ($(this).attr('disabled')) {
								return;
							}
							var product_option_value_id = $(this).attr('data-value');
							
							$(this).siblings().removeClass('selected');
							$(this).addClass('selected');
							$(this).parent().siblings('select').find('option[value="' + product_option_value_id + '"]').attr('selected', 'selected');
							$(this).parent().siblings('select').trigger('change');
							
							$(this).parent().parent().find('.radio input[type=radio][name^="'+ro_instance.option_prefix+'"]').attr('checked', false);
							$(this).parent().parent().find('.radio input[type=radio][name^="'+ro_instance.option_prefix+'"][value="'+product_option_value_id+'"]').attr('checked', true).trigger('change');
							
							if (Journal.updatePrice) {
								Journal.updateProductPrice();
							}
							// compatibility with Product Option Image extension (by another developer)
							if ( typeof(selectvalue) == 'function' ) {
								selectvalue(product_option_value_id);
							}
							
						});
					});
				}
			}
		},
		
		// << EVENT/TRIGGER FUNCTIONS
		event_setOptionValue_after : function(event, product_option_id, product_option_value_id, $touched_elems) { // ro_event_setOptionValue_after
			
			if ( $touched_elems && $touched_elems.length && $touched_elems.is(':radio') ) {
				<?php if ($ro_theme_name=='sstore' || $ro_theme_name=='storeset' ) { ?>
					ro_instance.sstore_setOptionsStyles($touched_elems.first());
					//ro_sstore_setOptionsStyles($touched_elems.first());
				<?php } elseif ( $ro_theme_name == 'logancee' || $ro_theme_name == 'fastor' ) { ?>
					if ( ( !$touched_elems.is(':visible') || $touched_elems.css('visibility') == 'hidden' ) && $touched_elems.siblings('span').length ) {
						$touched_elems.each(function(){
							$(this).siblings('span:first').removeClass('active');
						});
						if ( product_option_value_id ) {
							radio_elem.siblings('span:first').addClass('active');
						}
					}
				<?php } ?>
			}
			ro_instance.common_fn.piodd_setValue(product_option_id, product_option_value_id);
			ro_instance.common_fn.journal2_makeOptionValueSelected(product_option_value_id);
		},
		
		event_init_after : function(event) { // ro_event_init_after
			
			<?php if ( $ro_theme_name=='sstore' || $ro_theme_name=='storeset' ) { ?>
				$('.options [id^="option-"]').on('click', function(){
					var option_elem  = $(this);
					setTimeout(function(){
						var elem = option_elem.find('input:radio:checked');
						if (elem.length) {
							ro_instance.sstore_setOptionsStyles(elem);
							//ro_sstore_setOptionsStyles(elem);
						}
					},10);
				});
			<?php } ?>
			
			ro_instance.getOptionElement('[name^="quantity_per_option["]').change(function(){
				ro_functions.stockControl(0);
			});
			
			$("input[type=text][name=quantity], input[type=number][name=quantity]").change(function(){
				ro_functions.stockControl(0);
			});
			
			if (ro_instance.ro_theme_name == 'journal') { // compatibility for live price update with specials in related options
		
				var div_prod_opt = $('div.product-options');
			
				if (div_prod_opt.length == 1) {
					if ( div_prod_opt.find('div.price').find('span.product-price').length ) {
						div_prod_opt.find('div.price').find('span.product-price').after('<span class="price-old" style="display: none"></span><span class="price-new" style="display: none"></span>');
					} else if ($('div.price').find('span.price-old').length) {
						div_prod_opt.find('div.price').find('span.price-old').before('<span class="product-price" itemprop="price" style="display: none"></span>');
					}
					
					setInterval( function() {
						if ( div_prod_opt.find('div.price').find('span.product-price').html() && div_prod_opt.find('div.price').find('span.price-old').html() && div_prod_opt.find('div.price').find('span.price-new').html() ) {
							if ( div_prod_opt.find('div.price').find('span.price-old').html() == div_prod_opt.find('div.price').find('span.price-new').html()
								|| Number($('div.product-options').find('div.price').find('span.price-new').html().replace(/\D/g, '')) == 0 ) {
								div_prod_opt.find('div.price').find('span.price-old').hide();
								div_prod_opt.find('div.price').find('span.price-new').hide();
								div_prod_opt.find('div.price').find('span.product-price').show();
							} else {
								div_prod_opt.find('div.price').find('span.price-old').show();
								div_prod_opt.find('div.price').find('span.price-new').show();
								div_prod_opt.find('div.price').find('span.product-price').hide();
							}
						}
					}, 200 );
				}
			}
			if (ro_instance.ro_theme_name == 'journal3') {
				$().ready(function(){
					setTimeout(function(){
						if ( ro_instance.hasSelectedOptions() ) {
							ro_instance.controlAccessToValuesOfAllOptions();
						}
					}, 1);
				});
			}
		},
		
		event_setAccessibleOptionValues_select_after : function (event, product_option_id) {
			
			var $select_element = ro_instance.getOptionElement('select[name="'+ro_instance.option_prefix+'['+product_option_id+']"]');
			
			<?php if ( $ro_theme_name == 'theme625' || $ro_theme_name == 'theme628' || $ro_theme_name == 'theme630'
				|| $ro_theme_name == 'theme638' || $ro_theme_name == 'theme649' || $ro_theme_name == 'theme707'
				|| $ro_theme_name == 'theme773'
				|| $ro_theme_name == 'theme759'
				|| $ro_theme_name == 'themeXXX' ) { ?>
			
				$().ready(function(){
					$select_element.selectbox("detach");
					$select_element.selectbox({
						effect: "slide",
						speed: 400
					});
				});	
				
			<?php }	?>
			
			if ( $select_element.data('_styler') ) {
				$select_element.trigger('refresh');
			}
			
			// nice-select
			if ( $select_element.next().is('.nice-select') && typeof($.fn.niceSelect) == 'function' ) {
				$select_element.niceSelect('update');
			}
			
			// fancy-select
			if ( $select_element.parent().is('.fancy-select') ) {
				$select_element.trigger('disable');
				$select_element.parent().find('ul.options').html('');
				$select_element.trigger('enable');
			}
			
			if ( $.fn.customSelect ) {
				$select_element.trigger('render');
				$select_element.trigger('update'); // older customSelect version
			}
			
		},
		
		event_setAccessibleOptionValues_radioUncheck_after : function(event, elem_to_uncheck) {
			if ( elem_to_uncheck.length ) {
				if ( ro_instance.ro_theme_name == 'fastor' && elem_to_uncheck.siblings('span').length && ( !elem_to_uncheck.is(':visible') || elem_to_uncheck.css('visibility') == 'hidden' ) ) {
				// specific selectors - button-style
					elem_to_uncheck.siblings('span').removeClass('active');
				}
				if ( elem_to_uncheck.data('iCheck') ) { // radio and checkboxes nicer
					elem_to_uncheck.iCheck();
				}
			}
			// << Product Image Option DropDown compatibility
			var option_id = ro_instance.getProductOptionIdFromName( elem_to_uncheck.attr('name') );
			ro_instance.common_fn.piodd_setValue(option_id, '');
			// >> Product Image Option DropDown compatibility
		},
		
		event_setAccessibleOptionValues_radioToggle_after : function(event, option_id, $radio) {
			
			// << Product Image Option DropDown compatibility
			var piodd_option_div = $('#image-option-'+option_id);
			var piodd_value = piodd_option_div.find('ul.dd-options input.dd-option-value[value='+$radio.val()+']');
			if (piodd_value.length) {
				piodd_value.parent().toggle(!option_value_disabled);
			}
			// >> Product Image Option DropDown compatibility
			
		},
		
		event_setAccessibleOptionValues_radioEnableDisable_after : function(event, option_id, $radio) {
			
			if ( ro_instance.ro_theme_name == 'theme628' ) {
				// additionally, always opacity = 1
				$radio.siblings('label').fadeTo("fast", 1);
			}
			
			var option_id = ro_instance.getProductOptionIdFromName($radio.attr('name'));
			
			// << Product Image Option DropDown compatibility
			// make copies of unavailable elements, originals hide in hidden div, when element became available again - place it back
			var piodd_option_div = $('#image-option-'+option_id);
			
			if ( piodd_option_div.find('ul.dd-options').length ) {
				
				var ro_hidden_div_id = piodd_option_div.attr('id')+'-ro-hidden';
				
				if ( !$('#'+ro_hidden_div_id).length ) {
					piodd_option_div.after('<div id="'+ro_hidden_div_id+'" style="display: none;"></div>');
				}
				var ro_hidden_div = $('#'+ro_hidden_div_id);
				
				var clone_id = 'clone_'+radio_options[i].value;
				if (option_value_disabled) {
				
					var piodd_value = piodd_option_div.find('ul.dd-options input.dd-option-value[value='+radio_options[i].value+']');
					
					if (piodd_value.length) {
				
						if ( !piodd_option_div.find('[clone_id='+clone_id+']').length ) {
							var ro_clone = piodd_value.parent().clone(true, true).appendTo(ro_hidden_div);
							ro_clone.clone().insertAfter(piodd_value.parent()).attr('clone_id', clone_id).fadeTo('fast', 0.2);
							piodd_value.parent().remove();
						}
					}
					
				} else {
					if (ro_hidden_div.find('[value='+radio_options[i].value+']').length) {
						ro_hidden_div.find('[value='+radio_options[i].value+']').parent().clone(true, true).insertAfter(piodd_option_div.find('[clone_id='+clone_id+']'));
						ro_hidden_div.find('[value='+radio_options[i].value+']').parent().remove();
						piodd_option_div.find('[clone_id='+clone_id+']').remove();
					}
				}
				
			}
			// >> Product Image Option DropDown compatibility
			
		},
		
		event_setSelectedCombination_withAccessControl_after : function(event) {
			ro_instance.common_fn.updateViewOfBlockOptions();
			ro_instance.common_fn.updateViewOfJournalPushButtons();
		},
		
		event_controlAccessToValuesOfAllOptions_after : function(event) {
		
			<?php if ( !empty($ro_settings['spec_model']) ) { ?>
				ro_functions.updateModel();
			<?php } ?>
			
			<?php if ( !empty($ro_settings['spec_ofs']) ) { ?>
				ro_functions.updateStockInfo();
			<?php } ?>
		
			<?php if ( $ro_theme_name == 'revolution' ) { ?>
				if ( typeof(validate_pole_popuporder) == 'function' ) {
					$('input[name="quantity"]').change();
				}
			<?php } ?>
		
			ro_functions.stockControl(0);
			ro_functions.updateAvailabilityOfBlockAndJournalPushButtons();
		},
		
		// >> EVENT/TRIGGER FUNCTIONS
		
		// << CUSTOM FUNCTIONS
		
		custom_getQuantityInput : function() {
			if ( ro_instance.ro_theme_name == 'journal3' ) {
				return ro_instance.getElement('#product-quantity');
			}
		},
		
		custom_radioChangeClass : function() {
			if ( ro_instance.ro_theme_name == 'theme707' ) {
				return false;
			}
			
			return true;
		},
		
		custom_radioToggle : function( $radio, option_value_disabled ) {
			
			if ( ro_instance.ro_theme_name == 'theme707' ) { 
			
				$radio.parent().toggle(!option_value_disabled);
				if ( !option_value_disabled ) {
					$radio.parent().find('label').css('display', 'block');
				}
				
				return true;
			
			} else if ( ro_instance.ro_theme_name == 'theme630' || ro_instance.ro_theme_name == 'theme638' ) { 
				$radio.parent().toggle(!option_value_disabled);
				
				$radios = $(':radio[name="'+$radio.attr('name')+'"]');
				
				// style change for padding change
				if ( $radios.length && $radios.index($radio) == 0) {
					if (option_value_disabled) {
						if ($radio.parent().hasClass('radio')) {
							$radio.parent().removeClass('radio');
							$radio.parent().addClass('_radio_ro');
						}
					} else {
						if ($radio.parent().hasClass('_radio_ro')) {
							$radio.parent().removeClass('_radio_ro');
							$radio.parent().addClass('radio');
						}
					}
				}
				return true;
				
			} else if ( ro_instance.ro_theme_name == 'shopme' && $radio.parent().hasClass('single-option') ) {
			
				$radio.parent().toggle(!option_value_disabled);
				return true;
			
			} else if ( ro_instance.ro_theme_name == 'sstore' || ro_instance.ro_theme_name == 'storeset' ) { // shop-store (compatible with buttons)
				if ($radio.attr('class') != 'none' ) {
					$radio.toggle(!option_value_disabled);
				}
				var $radio_div = $radio.closest('div.radio, div._radio_ro');
				if ( $radio_div && $radio_div.find(':radio').length == 1 ) {
					$radio_div.toggle(!option_value_disabled);
				}
				var $radio_label = ro_instance.getOptionElement('label[for="'+$radio.attr('id')+'"]');
				if ( !$radio_label.length ) {
					$radio_label = $radio.closest('label'); // images
				}
				$radio_label.toggle(!option_value_disabled);
				/*
				if (option_value_disabled) {
					$radio_label.removeClass('selected').addClass('not-selected');
				}
				*/
				ro_instance.sstore_setOptionsStyles($radio);
				//ro_sstore_setOptionsStyles($radio);
				return true;
				
			} else if ( ro_instance.ro_theme_name == 'unishop' ) { 
				$radio.parent().parent().toggle(!option_value_disabled);
				return true;
			
			} else if ( ro_instance.ro_theme_name == 'royal' && $radio.siblings('span.im_option') ) {
				$radio.parent().parent().toggle(!option_value_disabled); // button selector
				return true;
			
			} else if ( ro_instance.ro_theme_name == 'revolution' ) {
				$radio.parent().toggle(!option_value_disabled); // button selector
				return true;
			
			} else if ( ro_instance.ro_theme_name == 'BurnEngine_shoppica' ) {
				$radio.closest('.radio, ._radio_ro').toggle(!option_value_disabled); // button selector
				return true;
			
			} else if ( ro_instance.ro_theme_name == 'journal3' ) {
				$radio.closest('.radio, ._radio_ro').toggle(!option_value_disabled); // button selector
				return true;
			
			} else if ( ro_instance.ro_theme_name == 'anystore' ) {
				$radio.parent().toggle(!option_value_disabled); // button selector
				return true;
			
			}
		},
		
		custom_radioEnableDisable : function( $radio, option_value_disabled ) {
		
			<?php if ( $ro_theme_name == 'mediacenter' ) { ?>
				$radio.closest('div.radio').fadeTo("fast", (option_value_disabled ? 0.2 : 1) );
				return true;
			<?php } elseif ( $ro_theme_name=='sstore' || $ro_theme_name=='storeset' ) { ?>
				var sstore_label = ro_instance.getOptionElement('label[for="'+$radio.attr('id')+'"]');
				if ( !sstore_label.length ) {
					sstore_label = $radio.closest('label');
				}
				if (option_value_disabled) {
					if (!$radio.hasClass('none')) {
						$radio.fadeTo("fast", 0.2);
					}
					ro_instance.sstore_setOptionsStyles($radio);
					//ro_sstore_setOptionsStyles($radio);
					sstore_label.fadeTo("fast", 0.2);
				} else {
			
					if (!$radio.hasClass('none')) {
						$radio.fadeTo("fast", 1);
					}
					sstore_label.fadeTo("fast", 1);
				}
				return true;
			<?php } elseif ( $ro_theme_name == 'fastor' ) {  //  ?>
			
				if (option_value_disabled) {
					$radio.parent().fadeTo("fast", 0.2);
				} else {
					$radio.parent().fadeTo("fast", 1);
				}
				if ( $radio.siblings('span').length && ( !$radio.is(':visible') || $radio.css('visibility') == 'hidden' ) ) {
				// specific selectors - button-style
					if (option_value_disabled) {
						$radio.siblings('span').fadeTo("fast", 0.2);
					} else {
						$radio.siblings('span').fadeTo("fast", 1);
					}
				}
				return true;
			<?php } ?>
		},
		
		sstore_setOptionsStyles : function($radio){
			var $radio_checked = $('input:radio[name="'+$radio.attr('name')+'"]:checked');
			if ($radio.next().is('img') ) { // image
				var sstore_label_img = $radio.closest('label');
				if (sstore_label_img.length) {
					sstore_label_img.closest('.radio').parent().find('label.selected-img').removeClass('selected-img').addClass('not-selected-img');
					$radio_checked.closest('label.not-selected-img').removeClass('not-selected-img').addClass('selected-img');
				}
			} else { // radio
				$radio.siblings('label.selected').removeClass('selected').addClass('not-selected');
				$radio_checked.siblings('label[for="'+$radio_checked.attr('id')+'"].not-selected').removeClass('not-selected').addClass('selected');
			}
		},

	// >> CUSTOM FUNCTIONS
	};
	return ro_functions;
}

//--></script>
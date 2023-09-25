// LIVEOPENCART: Related Options
	
(function ($) { $.fn.liveopencart_RelatedOptions = function(p_params){
	
	$this = this;
	
	var inst = {
		
		each : function(collection, fn){
			for ( var i_item in collection ) {
				if ( !collection.hasOwnProperty(i_item) ) continue;
				if ( fn(collection[i_item], i_item) === false ) {
					return;
				}
			}
		},

		getSetting : function(p_setting_name, p_default) {
			if ( typeof(inst.ro_settings[p_setting_name]) != 'undefined' ) {
				if ( inst.ro_settings[p_setting_name] !== '0' ) {
					return inst.ro_settings[p_setting_name];
				} else {
					return 0;
				}
			} else {
				return p_default;
			}
		},
		
		bind : function(trigger_name, event_function) {
			inst.parentBlock.on(trigger_name, event_function);
		},
		trigger : function(trigger_name, values) {
			inst.getBlockOfOptions().trigger(trigger_name, values);
		},
		
		initRO : function(ro_init_cnt) {
			
			if ( !inst.ro_data || inst.ro_data == {} || inst.ro_data == [] ) {
				return; // no related options
			}
			
			// add specifically displayed options (quantity input/select per option value)
			inst.getOptionElement('[data-quantity-per-option][data-product-option-id]').each(function(){
				var product_option_id = $(this).attr('data-product-option-id');
				if ( $.inArray(product_option_id, inst.ro_product_options) !=-1 ) {
					inst.input_quantity_per_options.push( product_option_id );
				}
			});
			
			// assign for global variable
			inst.options_step_by_step = inst.getOptionsAsSteps();
			
			if ( !inst.options_step_by_step.length && !ro_init_cnt ) {
				// in some themes page options may be not available on this stage, so recall init on document.ready
				$(document).ready( function() {
					inst.initRO(1);
				});
				return;
			}
			
			for (var i=0; i < inst.options_step_by_step.length; i++) {
				inst.all_values_of_options[inst.options_step_by_step[i]] = inst.getAllValuesOfProductOption(inst.options_step_by_step[i]);
			}
		
			inst.getOptionElement('select[name^="'+inst.option_prefix+'"]').change(function(){
				inst.controlAccessToValuesOfAllOptions();
			});
			
			inst.getOptionElement('input[type=radio][name^="'+inst.option_prefix+'"]').change(function(){
				inst.controlAccessToValuesOfAllOptions();
			});
			
			inst.controlAccessToValuesOfAllOptions();
			
			$(document).ready( function() {
			
				if ( typeof(inst.ros_to_select) != 'undefined' && inst.ros_to_select && inst.ros_to_select.length) {
					var ro_id = false;
					inst.each(inst.ros_to_select, function(current_ro_id){
						ro_id = current_ro_id;
						inst.setSelectedCombination(ro_id, true); // without limitaions
					});
					if (ro_id) {
						inst.setSelectedCombination(ro_id); /// with limitations
					}
				} else {
			
					// if there's filter and it's equal to related options model - this related options combination should be selected
					if (!inst.setSelectedCombinationByModel(inst.filter_name)) { // if there's not filter relevant to related options 
						// if any value is selected - all values should be reselected (to be relevant to available related options)
						if ( inst.poip_ov ) {
							setTimeout(function () {
								inst.makeFirstValuesSelected(); // ro_use_first_values();
							}, 1); // if any combination is selected (may be other extension), check it and change if it's not relevant to available related options
						}
					}
					inst.controlAccessToValuesOfAllOptions();
					
					inst.initialAutoSelect();
					
				}
			
			});
			
			inst.trigger('init_after.ro');
			
		},
		
		// correct auto selection for options some values already selected
		makeFirstValuesSelected : function(set_anyway) { // ro_use_first_values
			
			var options_values = inst.getOptionValues([]);
			
			var selected_options = [];
			var has_selected = false;
			inst.each(options_values, function(pov_id, po_id){
				if (pov_id) {
					has_selected = true;
					selected_options.push(po_id);
				}
			});
			
			if (has_selected || set_anyway) {
			
				inst.each(inst.options_step_by_step, function(product_option_id){
					
					if ( $.inArray(product_option_id, inst.ro_product_options) != -1 ) {
					
						var product_option_value_id = false;
						
						if ( inst.getOptionElement('select[name="'+inst.option_prefix+'['+product_option_id+']"]').length) {
							product_option_value_id = inst.getOptionElement('select[name="'+inst.option_prefix+'['+product_option_id+']"] option[value][value!=""]:not(:disabled)').val();
						} else if ( inst.getOptionElement('input[type=radio][name="'+inst.option_prefix+'['+product_option_id+']"]').length > 0) {
							product_option_value_id = inst.getOptionElement('input[type=radio][name="'+inst.option_prefix+'['+product_option_id+']"]:not(:disabled):first').val();
						}
						
						if (product_option_value_id && ($.inArray(product_option_id, selected_options) == -1 || set_anyway) ) {
						// if (product_option_value_id && ($.inArray(product_option_id, selected_options) != -1 || set_anyway) ) {
							inst.setOptionValue(product_option_id, product_option_value_id);
							inst.controlAccessToValuesOfAllOptions();
						}
					}
				});
			}
		},
		
		setOptionValue : function(product_option_id, product_option_value_id) {
		
			var $touched_elems = '';
			if ( inst.getOptionElement('select[name="'+inst.option_prefix+'['+product_option_id+']"]').length > 0) {
				$touched_elems = inst.getOptionElement('[name="'+inst.option_prefix+'['+product_option_id+']"]').val(product_option_value_id);
				
			} else if ( inst.getOptionElement('input[type=radio][name="'+inst.option_prefix+'['+product_option_id+']"]').length > 0) {
				var radio_elems = inst.getOptionElement('input[type=radio][name="'+inst.option_prefix+'['+product_option_id+']"]');
				radio_elems.prop('checked', false);
				
				if ( product_option_value_id ) {
					var radio_elem = inst.getOptionElement('input[type=radio][name="'+inst.option_prefix+'['+product_option_id+']"][value='+product_option_value_id+']');
					radio_elem.prop('checked', true);
				}
				
				$touched_elems = radio_elems;
			}
			
			inst.trigger('setOptionValue_after.ro', [product_option_id, product_option_value_id, $touched_elems]);
			
			// Product Option Image PRO compatibility
			if ( typeof(poipExternalOptionChange)=='function' ) {
				poipExternalOptionChange();
			}
			if ( typeof(poip_product)!='undefined' && typeof(poip_product.externalOptionChange) == 'function' ) {
				poip_product.externalOptionChange();
			}
			
		},
		
		getBlockOfOptions : function() {
			return inst.parentBlock;
		},
		
		getOptionElement : function(selector) {
			return inst.getBlockOfOptions().find(selector);
		},
		
		getProductOptionIdFromName : function(name) {
			return name.substr(inst.option_prefix_length+1, name.lastIndexOf(']')-(inst.option_prefix_length+1) );
		},
		
		getElement : function(selector) {
			return inst.parentBlock.find(selector);
		},
		
		getQuantityInput : function(){
			let $quantity_input = inst.custom_getQuantityInput();
			if ( !$quantity_input ) {
				$quantity_input = inst.getElement('input[type=text][name=quantity], input[type=number][name=quantity]');
			}
			return $quantity_input;
		},
		
		getQuantity : function(){
			return inst.getQuantityInput().val();
		},
		
		getValuesOfSelectOption : function(param_product_option_id) {
			if ( $.isEmptyObject(inst.all_select_ov) ) {
				inst.getOptionElement('select[name^="'+inst.option_prefix+'["]').each( function (si, sel_elem) {
					var product_option_id = inst.getProductOptionIdFromName(sel_elem.name);
					
					inst.all_select_ov[product_option_id] = [];
					
					$.each(sel_elem.options, function (oi, op_elem) {
						inst.all_select_ov[product_option_id].push(op_elem.value);
					});
					
				} );
			}
			return inst.all_select_ov[param_product_option_id];
		},
		
		getCurrentROIds : function(options_values) {
			var ro_ids = [];
			inst.each(inst.ro_data, function(ro_dt){
			
				var all_ok;
				inst.each(ro_dt.ro, function(ro_comb, ro_id){
				
					all_ok = true;
					inst.each(ro_comb.options, function(pov_id, po_id){
						if ( !(po_id in options_values && options_values[po_id]==pov_id) ) {
							all_ok = false;
						}
					});
					if (all_ok) ro_ids.push(ro_id); 
				});
			});
			return ro_ids;
		},
		
		arrayIntersection : function(arr1, arr2) {
			var new_arr = [];
			inst.each(arr1, function(val1){
			//for (var i in arr1) {
				if ($.inArray(val1, arr2) != -1) {
					new_arr.push(val1);
				}
			});
			return new_arr;
		},
		
		arrayCopy : function(arr) {
			var new_arr = [];
			inst.each(arr, function(val, i){
			//for (var i in arr) {
				if ( $.isArray(val) ) {
					new_arr[i] = inst.arrayCopy(val);
				} else {
					new_arr[i] = val;
				}
			});
			return new_arr;
		},
		
		getAllValuesOfProductOption : function(product_option_id) {
			var values = [];
			if ( inst.getOptionElement('select[name="'+inst.option_prefix+'['+product_option_id+']"]').length) {
				var select_options = inst.getValuesOfSelectOption(product_option_id);
				for (var i=0;i<select_options.length;i++) {
					if (select_options[i]) {
						values.push(select_options[i]);
					}
				}
			} else if ( inst.getOptionElement('input[type=radio][name="'+inst.option_prefix+'['+product_option_id+']"]').length) {
				inst.getOptionElement('input[type=radio][name="'+inst.option_prefix+'['+product_option_id+']"]').each(function(){
					values.push($(this).val());
				});
			}
			// add specifically displayed options (quantity input/select per option value)
			if ( $.inArray(product_option_id, inst.input_quantity_per_options) != -1 ) {
				$('[name^="quantity_per_option['+product_option_id+']["]').each(function(){
					var product_option_value_id = $(this).attr('data-value');
					if ( product_option_value_id ) {
						values.push(product_option_value_id);
					}
				});	
			}
			return values;
		},
		
		getOptionsWithDirectRelations : function(prodict_option_id) {
		
			if ( inst.linked_options_cache[prodict_option_id] ) {
				return inst.linked_options_cache[prodict_option_id];
			} else {
		
				var linked_options = [];
				inst.each(inst.ro_data, function(ro_dt){
				
					if ( $.inArray(prodict_option_id, ro_dt.options_ids)!=-1 ) {
						inst.each(ro_dt.options_ids, function(current_po_id){
							if ( current_po_id != prodict_option_id && $.inArray(current_po_id, linked_options) ) {
								linked_options.push(current_po_id);
							}
						});
					}
				});
				inst.linked_options_cache[prodict_option_id] = linked_options;
				return linked_options;
			}
		},
		
		// get available options values
		// option_id - (product_option_id)
		// param_options_values - current options values (selected) - only for related options
		// param_skip_ropv_ids - don't make values addition for this related options combinations
		getAccessibleOptionValues : function(option_id, param_options_values, param_skip_options) {
		
			// make copies od arrays
			var options_values = inst.arrayCopy(param_options_values);
			var skip_options = inst.arrayCopy(param_skip_options);
			skip_options.push(option_id);
			
			var linked_options = inst.getOptionsWithDirectRelations(option_id);
			
			inst.each(linked_options, function(current_option_id){
				if ( options_values[current_option_id] && !options_values[current_option_id].length && current_option_id != option_id) {
					if ( $.inArray(current_option_id, skip_options) == -1 ) {
						options_values[current_option_id] = inst.getAccessibleOptionValues(current_option_id, options_values, skip_options);
					}
				}
			});
			
			var common_accessible_values = false;
			
			var possible_current_option_values = inst.all_values_of_options[option_id];
			
			inst.each(inst.ro_data, function(ro_dt){
			
				if ($.inArray(option_id, ro_dt.options_ids)==-1) {
					return;
				}
				
				var accessible_values = [];
			
				var ro_combs = ro_dt.ro;
				
				var options_for_check = []; // optimization
				inst.each(options_values, function(current_pov_id, current_option_id){
				
					if (current_option_id != option_id && options_values[current_option_id].length && $.inArray(current_option_id, skip_options) == -1 && $.inArray(current_option_id, ro_dt.options_ids)!=-1) {
						options_for_check.push(current_option_id);
					}
				});
				
				if (!options_for_check.length) {
					if (ro_dt.options_ids.length == 1) { // combination contains only one option (this option)
						inst.each(ro_combs, function(ro_comb){
							if ( ro_comb.options[option_id] && $.inArray(ro_comb.options[option_id], accessible_values) == -1 ) {
								accessible_values.push(ro_comb.options[option_id]);
							}
						});
					} else {
						accessible_values = inst.arrayCopy(possible_current_option_values);
					}
				} else {
					inst.each(ro_combs, function(ro_comb){
						
						all_ok = true;
						
						for (var j =0; j < options_for_check.length; j++) {
							var current_option_id = options_for_check[j];
							if ( $.inArray(ro_comb.options[current_option_id], options_values[current_option_id]) == -1  ) {
								all_ok = false;
							}
							if (!all_ok) {
								break;
							}
						}
						
						if (all_ok && ($.inArray(ro_comb.options[option_id], accessible_values) == -1 )) {
							accessible_values.push(ro_comb.options[option_id]);
							if (possible_current_option_values.length == accessible_values.length) { // optimization
								return false;
							}
						}
					});
				}
				
				if (common_accessible_values === false) {
					common_accessible_values = accessible_values;
				} else {
					common_accessible_values = inst.arrayIntersection(common_accessible_values, accessible_values);
				}
			});
			return common_accessible_values;
		},
		
		// only for options with values
		// returns array of accessible values
		controlAccessToValuesOfOption : function(param_options_values, option_id) {
			
			var options_values = [];
			inst.each(param_options_values, function(pov_id, product_option_id){
				options_values[product_option_id] = [];
				if (param_options_values[product_option_id]) {
					options_values[product_option_id].push(param_options_values[product_option_id]);
				}
			});
			
			var skip_ropv_ids = [];
			var accessible_values = inst.getAccessibleOptionValues(option_id, options_values, skip_ropv_ids);
			
			inst.setAccessibleOptionValues(option_id, accessible_values);
			
			return accessible_values;
		},
		
		toggleOptionElement : function(option_id, toggle_flag) {
			
			inst.getOptionElement('#input-option'+option_id).parent().toggle(toggle_flag); 
			if ( inst.getOptionElement('#input-option'+option_id).parent().is('div.select') && inst.getOptionElement('#input-option'+option_id).parent().parent().is('div.form-group') ) { // additional compatibility (fastor theme or custom modification)
				inst.getOptionElement('#input-option'+option_id).parent().parent().toggle(toggle_flag);
			}
			inst.getOptionElement('label[for="input-option'+option_id+'"]').toggle(toggle_flag);
			
			// magazin theme comp 
			if ( inst.getOptionElement('#input-option'+option_id).parent().is('.fancy-select') ) {
				inst.getOptionElement('#input-option'+option_id).parent().parent().toggle(toggle_flag);
			}
			
			inst.trigger( 'toggleOptionElement_after.ro', [option_id, toggle_flag] );
		},
		
		optionIsAccessible : function(option_id) {
			if ( inst.getOptionElement('select[name="'+inst.option_prefix+'['+option_id+']"]').length) {
				return inst.getOptionElement('select[name="'+inst.option_prefix+'['+option_id+']"] option[value][value!=""]:not(:disabled)').length ? true : false;
			} else if ( inst.getOptionElement('input[type=radio][name="'+inst.option_prefix+'['+option_id+']"]').length) {
				return inst.getOptionElement('input[type=radio][name="'+inst.option_prefix+'['+option_id+']"]:not(:disabled)').length ? true : false;
			} else if ( inst.getOptionElement('input[name^="quantity_per_option['+option_id+']["]').length) {
				return inst.getOptionElement('input[name^="quantity_per_option['+option_id+']["]:not(:disabled)').length ? true : false;
			}
		},
		
		hideInaccessibleOptionIfNeeded : function(option_id) {
			if (inst.ro_settings && inst.ro_settings.hide_option) {
				inst.toggleOptionElement(option_id, inst.optionIsAccessible(option_id));
			}
		},
		
		makeInaccessibleOptionsNotRequired : function(option_id) {
			if (inst.ro_settings && inst.ro_settings.unavailable_not_required) {
				var current_ids = [];
				if ($('#ro_not_required').length) {
					current_ids = $('#ro_not_required').val().split(',');
				} else {
					$('#product').append('<input type="hidden" name="ro_not_required" id="ro_not_required" value="">');
				}
				var new_ids = [];
				inst.each(current_ids, function(current_option_id){
				//for (var i in current_ids) {
					if (current_option_id != option_id) {
						new_ids.push(current_option_id);
					}
				});
				if (!inst.optionIsAccessible(option_id)) {
					new_ids.push(option_id);
				}
				$('#ro_not_required').val( new_ids.toString());
			}
		},
		
		setAccessibleOptionValues : function(option_id, accessible_values) {
			
			var current_value = ( inst.getOptionElement('input[type=radio][name="'+inst.option_prefix+'['+option_id+']"]:checked').val() || inst.getOptionElement('select[name="'+inst.option_prefix+'['+option_id+']"]').val());
		
			if ( inst.getOptionElement('select[name="'+inst.option_prefix+'['+option_id+']"]').length) {
			
				if (current_value && $.inArray(current_value, accessible_values)==-1) {
					inst.getOptionElement('select[name="'+inst.option_prefix+'['+option_id+']"]').val("");
				}
				
				if (inst.hide_inaccessible) {
				
					let select_options = inst.getValuesOfSelectOption(option_id);
					//select_options = inst.all_select_ov[inst.option_prefix+"["+option_id+"]"];
					for (let i=0;i<select_options.length;i++) {
						if (select_options[i]) {
							option_value_disabled = ($.inArray(select_options[i],accessible_values) == -1);
							// hiding options for IE
							inst.getOptionElement('select[name="'+inst.option_prefix+'['+option_id+']"]').toggleOption(select_options[i], !option_value_disabled);
						}
					}
					
				} else {
				
					let select_options = inst.getOptionElement('select[name="'+inst.option_prefix+'['+option_id+']"]')[0].options;
					for (let i=0;i<select_options.length;i++) {
						if (select_options[i].value) {
							option_value_disabled = ($.inArray(select_options[i].value,accessible_values) == -1);
							select_options[i].disabled = option_value_disabled;
							if (option_value_disabled) {
								$(select_options[i]).addClass('ro_option_disabled');
							} else {
								$(select_options[i]).removeClass('ro_option_disabled');
							}
						}
					}
				}
				
				inst.trigger( 'setAccessibleOptionValues_select_after.ro', [option_id, accessible_values] );
				
			} else if ( inst.getOptionElement('input[type=radio][name="'+inst.option_prefix+'['+option_id+']"]').length) {	
				
				if (current_value && $.inArray(current_value, accessible_values)==-1) {
				
					var elem_to_uncheck = inst.getOptionElement('input[type=radio][name="'+inst.option_prefix+'['+option_id+']"]:checked');
					
					if ( elem_to_uncheck.length ) {
						elem_to_uncheck.prop('checked', false);
					}
					
					inst.trigger( 'setAccessibleOptionValues_radioUncheck_after.ro', [elem_to_uncheck] );
				}
				
				radio_options = inst.getOptionElement('input[type=radio][name="'+inst.option_prefix+'['+option_id+']"]');
				for (var i=0;i<radio_options.length;i++) {
					
					option_value_disabled = ($.inArray(radio_options[i].value,accessible_values) == -1);
					
					$(radio_options[i]).prop('disabled', option_value_disabled); // hidden should be disabled too
					
					if (inst.hide_inaccessible) {
					
						if ( typeof(inst.custom_radioToggle) != 'undefined' && inst.custom_radioToggle( $(radio_options[i]), option_value_disabled ) ) {
							// do nothing
						} else {
							$(radio_options[i]).parent().parent().toggle(!option_value_disabled);
							$(radio_options[i]).toggle(!option_value_disabled);
						}
						
						// style change for padding change
						if ( typeof(inst.custom_radioChangeClass) != 'undefined' && inst.custom_radioChangeClass() ) {
							if (option_value_disabled) {
								if ($(radio_options[i]).parent().parent().hasClass('radio')) {
									$(radio_options[i]).parent().parent().removeClass('radio');
									$(radio_options[i]).parent().parent().addClass('_radio_ro');
								}
							} else {
								if ($(radio_options[i]).parent().parent().hasClass('_radio_ro')) {
									$(radio_options[i]).parent().parent().removeClass('_radio_ro');
									$(radio_options[i]).parent().parent().addClass('radio');
								}
							}
						}
						
						inst.trigger( 'setAccessibleOptionValues_radioToggle_after.ro', [ option_id, $(radio_options[i]) ] );
						
					} else {
						
						if ( typeof(inst.custom_radioEnableDisable) != 'undefined' && inst.custom_radioEnableDisable( $(radio_options[i]), option_value_disabled ) ) {
							// do nothing
						} else {
							
							if (option_value_disabled) {
								$(radio_options[i]).parent().fadeTo("fast", 0.1);
							} else {
								$(radio_options[i]).parent().fadeTo("fast", 1);
							}
							
						}
						
						inst.trigger( 'setAccessibleOptionValues_radioEnableDisable_after.ro', [ option_id, $(radio_options[i]) ] );
						
					}
				}
				
			} else if ( $.inArray(option_id, inst.input_quantity_per_options) != -1 ) { // for specifically displayed options (quantity input/select per option value)
				
				inst.getOptionElement('[name^="quantity_per_option['+option_id+']["]').each(function(){
					var $qpo_input = $(this);
					var product_option_value_id = qpo_getPOVIdByName( $qpo_input.attr('name') );
					//var product_option_value_id = $qpo_input.attr('data-value');
					if ( product_option_value_id ) {
						option_value_disabled = ( $.inArray(product_option_value_id, accessible_values) == -1 );
						if ( option_value_disabled ) {
							$qpo_input.val('');
						} else if ( $qpo_input.prop('disabled') ) { // re-enable the input - place default values
							$qpo_input.val( $qpo_input.attr('data-default-value' || '0') );
						}
						$qpo_input.prop('disabled', option_value_disabled);
						var $option_value_container = $qpo_input.closest('tr');
						if ( !$option_value_container.length ) {
							$option_value_container = $qpo_input.closest('div');
						}
						if (inst.hide_inaccessible) {
							$option_value_container.toggle(!option_value_disabled);
						} else {
							if ( option_value_disabled ) {
								$option_value_container.fadeTo("fast", 0.1);
							} else {
								$option_value_container.fadeTo("fast", 1);
							}
						}
					}
				});
				
			}
			
			inst.hideInaccessibleOptionIfNeeded(option_id);
			inst.makeInaccessibleOptionsNotRequired(option_id);
		},
		
		getOptionValues : function() {
			
			var options_values = {};
		
			inst.getOptionElement('select[name^="'+inst.option_prefix+'["], input[type=radio][name^="'+inst.option_prefix+'["]').each(function(){
				option_id = inst.getProductOptionIdFromName( $(this).attr('name') );
				
				if ($.inArray(option_id, inst.ro_product_options) != -1) {
					
					if ( typeof(options_values[option_id]) == 'undefined' ) {
						options_values[option_id] = 0;
					}
					
					if ( $(this).find('option[value]').length ) { // select
						options_values[option_id] = $(this).val();
					} else { // radio
						if ( $(this).is(':checked') ) {
							options_values[option_id] = $(this).val();
						}
					}
					
				}
			});
			
			// add specifically displayed options (quantity input/select per option value)
			inst.each(inst.input_quantity_per_options, function(qpo_product_option_id){
				//for (var i_input_quantity_per_options in inst.input_quantity_per_options) {
				//if ( !inst.input_quantity_per_options.hasOwnProperty(i_input_quantity_per_options) ) continue;
				//var product_option_id = qpo_product_option_id;
				
				options_values[qpo_product_option_id] = 0;
				//}
			});
			
			return options_values;
		},
		
		getSelectedOptionValues : function() {
			let selected_options = {};
			inst.each(inst.getOptionValues(), function(pov_id, po_id){
				if ( pov_id ) {
					selected_options[po_id] = pov_id;
				}
			});
			return selected_options;
		},
		
		hasSelectedOptions : function() {
			return Object.keys( inst.getSelectedOptionValues() ).length;
		},
		
		setSelectedCombination : function(ro_id, skip_access) {
		
			var selected_povs = [];
			if (inst.ro_data) {
				inst.each(inst.ro_data, function(ro_dt){
					let ro_combs = ro_dt.ro;
					if ( ro_combs[ro_id] ) {
						inst.each(ro_combs[ro_id].options, function(pov_id, product_option_id){
							inst.setOptionValue(product_option_id, ro_combs[ro_id].options[product_option_id]);
							selected_povs.push(ro_combs[ro_id].options[product_option_id]);
						});
					
						return false;
					}
				});
			}
		
			// access should be checked also for step by step because there may be selects with removed options
			if ( typeof(skip_access) == 'undefined' || !skip_access || (inst.step_by_step && inst.hide_inaccessible) ) { 
	
				inst.controlAccessToValuesOfAllOptions();
				
				inst.trigger('setSelectedCombination_withAccessControl_after.ro');
				
			}
			
			/*
			// poip compatibility
			if ( selected_povs.length && typeof(poip_images_by_options) != 'undefined' && typeof(poip_option_value_selected) == 'function' ) {
				for ( var i in selected_povs ) {
					if ( !selected_povs.hasOwnProperty(i) ) continue;
					
					if ( typeof(poip_images_by_options[selected_povs[i]]) != 'undefined' && poip_images_by_options[selected_povs[i]] && poip_images_by_options[selected_povs[i]].length ) {
						
						var elem_to_run_poip = inst.getOptionElement('select[name^="'+inst.option_prefix+'["] option, input:radio[name^="'+inst.option_prefix+'["]').filter('[value="'+selected_povs[i]+'"]');
						if ( elem_to_run_poip.length ) {
							poip_option_value_selected( elem_to_run_poip.is('option') ? elem_to_run_poip.parent()[0] : elem_to_run_poip[0]);
							break;
						}
					}
				}
			}
			
			// live price compatibility
			if ( typeof(liveprice_recalc) == 'function' ) {
				liveprice_recalc(100);
			}
			*/
			inst.executeFunctionsFromOtherExtensionsOnOptionChange();
			
		},
		
		executeFunctionsFromOtherExtensionsOnOptionChange : function() {
			
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
			
			// Live Price OLD
			if ( typeof(liveprice_recalc) == 'function' ) {
				liveprice_recalc(100);
			}
			
			// Live Price NEW
			if ( window.liveopencart && window.liveopencart.live_price_instances ) {
				var lp_instances = window.liveopencart.live_price_instances;
				if ( Array.isArray(lp_instances) && lp_instances.length ) {
					inst.each(lp_instances, function(lp_instance){
					//for ( var i_lp_instances in lp_instances ) {
						//if ( !lp_instances.hasOwnProperty(i_lp_instances) ) continue;
						//var lp_instance = lp_instances[i_lp_instances];
						lp_instance.updatePrice(100);
					});
				}
			}
			
		},
		
		setSelectedCombinationByModel : function(model) {
			if (model && inst.ro_data) {
				inst.each(inst.ro_data, function(ro_dt){
					inst.each(ro_dt.ro, function(ro_comb){
						if (ro_comb.model && ro_comb.model.toLowerCase() == model.toLowerCase()) {
							inst.setSelectedCombination(ro_id);
							return true;
						}
					});
				});
			}
			return false;
		},
		
		// for step-by-step way
		getOptionsAsSteps : function() {
			var options_steps = [];
			
			inst.getOptionElement('input[name^="'+inst.option_prefix+'["], select[name^="'+inst.option_prefix+'["]').each(function(){
			
				let product_option_id = inst.getProductOptionIdFromName( inst.getOptionElement(this).attr('name'));
				
				if ($.inArray(product_option_id, inst.ro_product_options) != -1) {
					if ($.inArray(product_option_id, options_steps) == -1) {
						options_steps.push(product_option_id);
					}
				}
				
			});
			
			// add specifically displayed options (quantity input/select per option value)
			inst.each(inst.input_quantity_per_options, function(qpo_product_option_id){
			//for (var i_input_quantity_per_options in inst.input_quantity_per_options) {
			//	if ( !inst.input_quantity_per_options.hasOwnProperty(i_input_quantity_per_options) ) continue;
			//	let product_option_id = inst.input_quantity_per_options[i_input_quantity_per_options];
				options_steps.push(qpo_product_option_id);
			});
			
			return options_steps;
		},
		
		controlAccessToValuesOfAllOptions : function() {
		
			if (!inst.ro_data || !Object.keys(inst.ro_data).length) return;
			
			if (inst.step_by_step) {
			
				var prev_options_values = {};
				var prev_options = [];
				var option_accessible_values = [];
				var one_prev_value_is_not_set = false;
				
				for (let i=0;i<inst.options_step_by_step.length;i++) {
				
					if (i == 0) {
						// all values existent in combinations of related options should be available
						inst.controlAccessToValuesOfOption([], inst.options_step_by_step[i]);
					} else {
						// if previous option value is selected or if previous option has no available values
						if (!one_prev_value_is_not_set) {
							// limitaion on previous
							option_accessible_values = inst.controlAccessToValuesOfOption(prev_options_values, inst.options_step_by_step[i]);
							
						} else {
							// disable all
							inst.setAccessibleOptionValues(inst.options_step_by_step[i], []);
							option_accessible_values = [];
						}
					}
					
					prev_options.push( ( inst.getOptionElement('input[type=radio][name="'+inst.option_prefix+'['+inst.options_step_by_step[i]+']"]:checked').val() || inst.getOptionElement('select[name="'+inst.option_prefix+'['+inst.options_step_by_step[i]+']"]').val()) );
					prev_options_values[inst.options_step_by_step[i]] = prev_options[prev_options.length-1];
					
					if ((option_accessible_values.length || i==0) && !prev_options[i] ) { // option has available values, but none of them is selected
						one_prev_value_is_not_set = true;
					}
					
				}
			
			} else {
			
				//var options_keys = [];
				var options_values = inst.getOptionValues();
				var options_keys = Object.keys(options_values);
				
				for (let i=0;i<options_keys.length;i++) {
					inst.controlAccessToValuesOfOption(options_values, options_keys[i]);
				}
				
			}
			
			inst.checkAutoSelect();
			
			inst.trigger('controlAccessToValuesOfAllOptions_after.ro');
			
		},
		
		// autoselection for last available option value & first available always
		checkAutoSelect : function() {
		
			if (inst.auto_select_last || inst.auto_select_first_always) {
			
				inst.each(inst.options_step_by_ste, function(product_option_id){
					
					if ( inst.getOptionElement('select[name="'+inst.option_prefix+'['+product_option_id+']"]').length ) {
						
						let options_elems = inst.getOptionElement('select[name="'+inst.option_prefix+'['+product_option_id+']"]').find('option[value][value!=""]:not(:disabled)');
						
						if ( inst.auto_select_last && options_elems.length == 1 && !$(options_elems[0]).is(':selected') || ( inst.auto_select_first_always && options_elems.length && !options_elems.filter(':selected').length ) ) {
						
							let product_option_value_id = $(options_elems[0]).val();
							
							inst.setOptionValue(product_option_id, product_option_value_id);
							$(options_elems[0]).parent().change();
							return;
						}
						
					} else if ( inst.getOptionElement('input:radio[name="'+inst.option_prefix+'['+product_option_id+']"]').length ) {
					
						//var radio_elems = inst.getOptionElement('input:radio[name="'+inst.option_prefix+'['+product_option_id+']"]:not(:disabled):visible');
						let radio_elems = inst.getOptionElement('input:radio[name="'+inst.option_prefix+'['+product_option_id+']"]:not(:disabled)');
						
						if ( inst.auto_select_last && radio_elems.length == 1 && !$(radio_elems[0]).is(':checked') || ( inst.auto_select_first_always && radio_elems.length && !radio_elems.filter(':checked').length ) ) {
							
							let product_option_value_id = $(radio_elems[0]).val();
							inst.setOptionValue(product_option_id, product_option_value_id);
							
							$(radio_elems[0]).change();
							return;
							
						}
					}
				});
			}
		},
		
		// autorelection for first values (initial)
		initialAutoSelect : function() {
		
			if (inst.ro_settings && inst.ro_settings.select_first && inst.ro_settings.select_first == 1) {
				
				inst.each(inst.options_step_by_step, function(product_option_id){
				//for (var i in inst.options_step_by_step) {
					
					if ( inst.getOptionElement('select[name="'+inst.option_prefix+'['+product_option_id+']"]').length ) {
						
						let elem = inst.getOptionElement('select[name="'+inst.option_prefix+'['+product_option_id+']"]');
						if ( !elem.val() ) {
						
							let elem_option = elem.find('option[value][value!=""]:not(:disabled)');
							if (elem_option.length) {
							
								inst.setOptionValue(product_option_id, elem_option.val() );
							
								elem.change();
							}
							
						}
						
					} else if ( inst.getOptionElement('input:radio[name="'+inst.option_prefix+'['+product_option_id+']"]').length ) {
						
						if ( !inst.getOptionElement('input:radio[name="'+inst.option_prefix+'['+product_option_id+']"]:checked').length ) {
							let elem = inst.getOptionElement('input:radio[name="'+inst.option_prefix+'['+product_option_id+']"]:not(:disabled):first');
							if (elem.length) {
								
								inst.setOptionValue(product_option_id, elem.attr('value') );
								
								elem.change();
								
							}
						}
					}
				});
			}
		},
		
		
	};
	
	inst.parentBlock = this;
	
	var params = $.extend( {
		'ro_settings' 	: {},
		'ro_data' 			: false,
		'ro_theme_name' : '',
		'ros_to_select' : '',
		'poip_ov' 			: '',
		'filter_name'		: '',
		'option_prefix'	: 'option',
	}, p_params);
	inst.ro_settings 	= params.ro_settings;
	inst.ro_theme_name 	= params.ro_theme_name;
	inst.ro_data 		= params.ro_data;
	inst.ros_to_select 	= params.ros_to_select;
	inst.poip_ov 		= params.poip_ov;
	inst.filter_name 	= params.filter_name;
	
	// settings
	inst.hide_inaccessible 	= inst.getSetting('hide_inaccessible');
	inst.step_by_step 			= inst.getSetting('step_by_step');
	
	inst.auto_select_last 	= inst.getSetting('select_first') == 2;
	inst.auto_select_first_always = inst.getSetting('select_first') == 3;
	
	inst.option_prefix = params.option_prefix;
	if ( inst.ro_data.length && !$('[name^="option["]').length && $('[name^="option_oc["]').length) { // 
	//if ($('#mijoshop').length && $('[name^="option_oc["]').length) { // 
		inst.option_prefix = "option_oc";
	}
	inst.option_prefix_length = inst.option_prefix.length;
	
	// data
	inst.ro_product_options = [];
	if ( inst.ro_data ) {
		inst.each(inst.ro_data, function(ro_dt){
		//for ( var i_ro_data in inst.ro_data ) {
			
			inst.each(ro_dt.options_ids, function(product_option_id){
			//for ( var i_option_ids in option_ids ) {
				if ( $.inArray(product_option_id, inst.ro_product_options) == -1 ) {
					inst.ro_product_options.push(product_option_id);
				}
			});
		});
	}
	
	// variables 
	inst.linked_options_cache = {}; // cache
	inst.all_select_ov = {}; // selects cache
	inst.input_quantity_per_options = [];
	inst.ro_stock_control_last_call = '';
	inst.options_types = [];
	inst.options_step_by_step = [];
	inst.all_values_of_options = [];
		
	//inst.initRO(); // init should be started from outside, after assigning all additional functions/triggers
	
	this.data('liveopencart_relatedOptions', inst);
	
	if ( !window.liveopencart ) {
		window.liveopencart = {};
	}
	if ( !window.liveopencart.related_options_instances ) {
		window.liveopencart.related_options_instances = [];
	}
	window.liveopencart.related_options_instances.push(inst);
	
	return inst;

}; })(jQuery);

	
	

	
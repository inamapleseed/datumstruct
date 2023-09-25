<?php
//  Related Options / Связанные опции
//  Support: support@liveopencart.com / Поддержка: help@liveopencart.ru

// Heading
$_['module_name']           = 'Related Options PRO';
$_['heading_title']         = 'LIVEOPENCART: '.$_['module_name'];
$_['text_edit']             = 'Edit '.$_['module_name'].' Module';
$_['related_options_title'] = $_['module_name'];

// Text
$_['text_module']           = 'Modules';
$_['text_success']          = 'Settings is modified!';
$_['text_content_top']      = 'Content Top';
$_['text_content_bottom']   = 'Content Bottom';
$_['text_column_left']      = 'Column Left';
$_['text_column_right']     = 'Column Right';
$_['text_ro_updated_to']    = 'Module updated to version ';
$_['text_ro_all_options']   = 'All available options';
$_['text_ro_support']       = "Developer: <a href='http://liveopencart.com' target='_blank'>liveopencart.com</a> | Support, questions and suggestions: <a href=\"mailto:support@liveopencart.com\">support@liveopencart.com</a>";
$_['text_ro_clear_options'] = 'Reset options';



// Entry
$_['entry_settings']                  = 'Main settings';
$_['entry_additional']                = 'Additional fields';

$_['entry_PHPExcel_not_found']        = '<a href="http://liveopencart.com/PHPExcel" target="_blank" title="What is PHPExcel? How to install PHPExcel?">PHPExcel</a> library not installed. File not found: ';
$_['entry_export']                    = 'Export';
$_['entry_export_description']        = 'Export file format: XLS.<br>First line for fields names, next lines for data.';
$_['entry_export_get_file']           = 'Export file';
$_['entry_export_check_all']          = 'Check all';
$_['entry_export_fields']             = 'Export fields:';
$_['entry_import']                    = 'Import (old versions)';
$_['entry_import_ok']                 = 'Import completed';
$_['entry_import_description']        = '
<b>Import of data exported using old versions of Related Options PRO (lower than 1.1.0) and Related Options 2 (lower than 2.2.0) module.</b><br><br>

Import file format: XLS. Import uses only first sheet for getting data.
<br>First table row should contain fields names (head): product_id, relatedoptions_model, quantity, price, option_id1, option_value_id1, option_id2, option_value_id2, ... (not product_option_id or product_option_value_id).
<br>Next table rows should contain related options data in accordance with fields names in first table row.
<br><br>Products related options combinations will be replaced if the same will be found in file on import.';
$_['entry_import_nothing_before']     = 'Don\'t delete related options before import';
$_['entry_import_delete_before']      = 'Delete all related options data before import';
$_['entry_import_product_before']     = 'Delete related options data only for products existing in import file';
$_['button_upload']		                = 'Import file';
$_['button_upload_help']              = 'import starts immediately, when the file is selected';
$_['entry_server_response']           = 'Server answer:';
$_['entry_import_result']             = 'Processed products / related options';

$_['entry_export_new']                = 'Export';
$_['entry_export_new_fields']         = 'Export fields:';
$_['entry_export_new_get_file']       = 'Export file';
$_['entry_export_new_check_all']      = 'Check all';
$_['entry_export_new_description']    = 'Export file format: XLS.<br>
Separate sheets for each variant of Related Options combinations.<br>
Separate sheets for related options discounts and specials.<br>
First row for fields names, next rows for data.<br>
';
$_['ro_entry_export_method']                = 'Export method';
$_['ro_entry_export_method_all']            = 'Export all combinations of related options';
$_['ro_entry_export_method_by_product_ids']	= 'Export the data by a range of product ID\'s';
$_['ro_entry_export_method_by_ro_variant']	= 'Export the data by a variant of related options';
$_['ro_entry_start_product_id']             = 'Start product id';
$_['ro_entry_end_product_id']               = 'End product id';
$_['ro_entry_export_by_variant']            = 'Export variant';
$_['ro_entry_min_product_id']            		= 'min ID:';
$_['ro_entry_max_product_id']            		= 'max ID:';

$_['entry_import_new']                    = 'Import';
$_['entry_import_new_nothing_before']     = 'Don\'t delete related options before import';
$_['entry_import_new_delete_before']      = 'Delete all related options data before import';
$_['entry_import_new_product_before']     = 'Delete related options data only for products existing in import file';
$_['entry_import_new_button_upload']	    = 'Import file';
$_['entry_import_new_button_upload_help'] = 'import starts immediately, after the file selection';
$_['entry_import_new_ok']                 = 'Import completed';
$_['entry_import_new_server_response']    = 'Server answer:';
$_['entry_import_new_result']             = 'Processed products / related options';
$_['entry_import_new_error_not_uploaded']	= 'the file is not uploaded';
$_['entry_import_new_error_not_found']	  = 'column is not found on sheet';
$_['entry_import_new_error_no_ro']	      = 'relevant related options combination is not found for ';
$_['entry_import_new_error_skipped']	    = 'skipped sheet';
$_['entry_import_new_error_no_data']	    = 'no data on sheet';
$_['entry_import_new_error_no_sheets']    = 'sheets with data is not found';
$_['entry_import_new_error']              = 'Error:';
$_['entry_import_new_description']        = '
Import file format: XLS. <br>
Separate sheets for each variant of Related Options combinations (sheet names should start from "RO").<br>
Two additional sheets for related options discounts and specials ("discounts" and "specials").
First row of every sheet should contain fields names (header): product_id, options_values_ids (options ids and options values ids with comma delimiter - option_id:option_value_id, ...), quantity,	model, sku, ean, upc, location, price_prefix, price, defaultselect, defaultselectpriority, weight_prefix, weight, stock_status_id<br>
Next rows should contain related options data in accordance with fields names in first row.<br>
';

$_['entry_update_quantity']           = 'Recalc product quantity';
$_['entry_update_quantity_help']      = 'calculate product quantity depends on related options quantity';
$_['entry_stock_control']             = 'Use quantity control';
$_['entry_stock_control_help']        = 'prevent adding to cart for product quantity greater than related options quantity';
$_['entry_update_options']            = 'Update standard options data';
$_['entry_update_options_help']    	  = 'update standard OpenCart product options depends on related options';
$_['entry_update_options_remove']     = 'Update options with removing';
$_['entry_update_options_remove_help']= 'If option is completely removed from product related options data (\''.$_['module_name'].'\' tab), it will be automatically removed from product options (\'Option\' tab) ';
$_['entry_subtract_stock']            = 'Subtract stock for options';
$_['entry_subtract_stock_help']    	  = 'set subtract stock settings for options used in related options combinations';
$_['text_subtract_stock_from_product']            = 'From product';
$_['text_subtract_stock_from_product_first_time'] = 'From product (only first time)';
$_['entry_required']                   = 'Related options is required (for customers)';
$_['entry_required_help']    	        = 'make related options required for customers to add to cart';
$_['text_required_first_time']         = 'Yes (only first time)';
$_['entry_options_values']            = 'Options values';
$_['entry_add_related_options']       = 'Add related options';
$_['entry_related_options_quantity']  = 'Quantity';
$_['entry_ro_version']                = $_['module_name'].', version';

$_['entry_additional_fields']         = 'Additional data for related options combinations';
$_['text_ro_set_options_variants']    = 'related options variants should be set on the module settings page';
$_['entry_ro_disable_all_options_variant']= 'Disable variant "'.$_['text_ro_all_options'].'"';
$_['entry_ro_disable_all_options_variant_help']= 'disable specific related options variant containing all options compatible with the module (recommended for most of cases)';
$_['entry_ro_use_variants']           = 'Use different related options variants';
$_['entry_ro_use_variants_help']      = 'allow to combine different options for different products';
$_['entry_ro_variant']                = 'Related options variant';
$_['entry_ro_variant_name']           = 'Variant name';
$_['entry_ro_options']                = 'Variant options';
$_['entry_ro_sort_order']             = 'Sort order';
$_['entry_ro_add_variant']            = 'Add variant';
$_['entry_ro_delete_variant']         = 'Delete variant';
$_['entry_ro_add_option']             = 'Add option';
$_['entry_ro_delete_option']          = 'Delete option';
$_['entry_ro_use']                    = 'Enable related options';
$_['entry_show_clear_options']        = 'Show "Reset options"';
$_['entry_show_clear_options_help']   = 'show button \'Reset options\' for customer on product page to reset all selected options values';
$_['option_show_clear_options_not']   = 'do not use';
$_['option_show_clear_options_top']   = 'above options';
$_['option_show_clear_options_bot']   = 'below options';
$_['entry_hide_inaccessible']         = 'Hide unavailable values';
$_['entry_hide_inaccessible_help']    = 'hide unselectable option values from the customers';
$_['entry_hide_option']               = 'Hide unavailable options';
$_['entry_hide_option_help']          = 'hide option from a customer, if all option values is unavailable/unselectable';
$_['entry_unavailable_not_required']  = 'Unavailable option is not required';
$_['entry_unavailable_not_required_help'] = 'make unavailable/unselectable options not required';
$_['entry_spec_model']                = 'Model';
$_['entry_spec_model_help']           = 'allow to set different models for related options combinations (this models will be shown on the product page and ont the cart page, and will be saved in orders data)';
$_['entry_spec_model_0']              = 'Off';
$_['entry_spec_model_1']              = 'On';
$_['entry_spec_model_2']              = 'On, calculate: related options model 1 + related options model 2 + etc';
$_['entry_spec_model_3']              = 'On, calculate: product model + related options model 1 + related options model 2 + etc';

$_['entry_spec_model_delimiter_product'] 	= 'Delimiter product-options';
$_['entry_spec_model_delimiter_ro']				= 'Delimiter options-options';

$_['entry_spec_sku']                  = 'SKU';
$_['entry_spec_sku_help']             = 'allow to set different SKU for related options combinations (this SKU will be saved in orders data)';
$_['entry_spec_upc']                  = 'UPC';
$_['entry_spec_upc_help']             = 'allow to set different UPC for related options combinations (this UPC will be saved in orders data)';
$_['entry_spec_ean']                  = 'EAN';
$_['entry_spec_ean_help']             = 'allow to set different EAN for related options combinations (this EAN will be saved in orders data)';
$_['entry_spec_location']             = 'Location';
$_['entry_spec_location_help']        = 'allow to set different Location for related options combinations (this Location will be saved in orders data)';
$_['entry_spec_price']                = 'Price';
$_['entry_spec_price_help']           = 'allow to set different prices for related options combinations, if price for related options is not set - standard product price will be used';
$_['entry_spec_ofs']                  = 'Out Of Stock Status';
$_['entry_spec_ofs_help']             = 'allow to set different Out Of Stock Status for related options combinations (this Out Of Stock Status will be shown on the product page, when selected options combination is out of stock )';
$_['entry_spec_weight']               = 'Weight';
$_['entry_spec_weight_help']          = 'allow to set different weights for related options combinations';
$_['entry_spec_price_discount']       = 'Discounts';
$_['entry_spec_price_discount_help']  = 'allow to set different discounts for related options combinations (works if \''.$_['entry_spec_price'].'\' turned on, if discounts for related options is not set - standard product discounts will be used)';
$_['entry_add_discount']              = 'Add discount';
$_['entry_del_discount_title']        = 'Delete discount';
$_['entry_spec_price_special']        = 'Specials';
$_['entry_spec_price_special_help']   = 'allow to set different specials for related options combinations (works if \''.$_['entry_spec_price'].'\' turned on, if specials for related options is not set - standard product specials will be used)';
$_['entry_add_special']               = 'Add special';
$_['entry_del_special_title']         = 'Delete special';
$_['entry_prices']                    = 'Price';
$_['entry_select_first_short']        = 'Auto-select';
$_['entry_select_first_priority']     = 'Priority';
$_['entry_select_first']              = 'Select options automatically';
$_['entry_select_first_help']         = 'select options values automatically on product page in frontend';
$_['option_select_first_not']         = 'off';
$_['option_select_first']             = 'first available combination';
$_['option_select_first_last']        = 'last remaining value';
$_['option_select_first_always']      = 'first available option value (always)';
$_['entry_step_by_step']              = 'Step-by-step options selection';
$_['entry_step_by_step_help']         = 'customer selects first option, then second, then third, and next, and next etc. (customer can change value of selected options anytime - all next options with unsuitable values will be cleared)';
$_['entry_allow_zero_select']         = 'Allow to select zero quantity';
$_['entry_allow_zero_select_help']    = 'allow customer to select related options combinations with zero quantity';
$_['entry_edit_columns']              = 'Related Options editing';
$_['entry_edit_columns_0']            = '1 column';
$_['entry_edit_columns_2']            = '2 columns';
$_['entry_edit_columns_3']            = '3 columns';
$_['entry_edit_columns_4']            = '4 columns';
$_['entry_edit_columns_5']            = '5 columns';
$_['entry_edit_columns_100']          = 'by width';
$_['entry_edit_columns_help']         = 'set position select fields for editing related options (Related Option tab on product editing page';
$_['entry_add_all_variants']          = 'Add all possible options combinations';
$_['entry_add_product_variants']      = 'Add all product options combinations';
$_['text_extension_page']             = '<a href="http://www.opencart.com/index.php?route=marketplace/extension/info&amp;extension_id=23143" target="_blank" title="Related Options PRO on opencart.com">Related Options PRO on opencart.com</a>';
$_['entry_spec_price_prefix']         = "Price prefix";
$_['entry_spec_price_prefix_help']    = "Allow price prefixes '+' or '-' for related options prices";
$_['text_update_alert']               = '(new version available)';
$_['text_combs_number']               = 'Number of options combinations ';
$_['text_combs_number_out_of_limit']  = ' is too high. It is impossible to operate this number of combinations per product.';
$_['text_combs_number_is_big']        = ' is high. To generate all of them may take a long time and sometimes even freeze the browser. Do you want to process?';
$_['text_combs_will_be_added']        = ' options combinations will be added. Continue?';
$_['entry_delete_all_combs']	        = 'Delete all options combinations';
$_['text_delete_all_combs']	        	= 'All options combinations will be deleted. Continue?';

$_['entry_about']         			      = 'About';
$_['text_vqmod_href']									= 'http://github.com/vqmod/vqmod';
$_['text_vqmod_required']							= 'Required <a href="'.$_['text_vqmod_href'].'" target="_blank">vQmod</a> version 2.6.1 or later';
$_['module_description']    = '
The module is designed to create combinations of related product options and set stock, price, model, sku, etc for each combination.<br>
I also allows to limit customers to select only available related options combinations.
<br>This functionality can be useful for sales of products, having interlinked options, such as size and color for clothes.<br>
PRO module version allows to set some different variants of related options combinations per product.
<br><br><span class="help">'.$_['text_vqmod_required'].'.</span>';

$_['text_conversation'] = 'We are open for conversation. If you need modify or integrate our modules, add new functionality or develop new modules, email as to <b><a href="mailto:support@liveopencart.com">support@liveopencart.com</a></b>.';

$_['entry_we_recommend'] = 'We also recommend:';
$_['text_we_recommend'] = '
<strong>Live Price</strong>&nbsp;&nbsp;( <a href="http://www.opencart.com/index.php?route=marketplace/extension/info&amp;extension_id=20835"
target="_blank" title="Live Price on opencart.com">opencart.com</a> )<br>
to dynamic price update (including discounts, special etc) on product page, depending on selected options and quantity
(completely compatible and recommended to use with Related Options module).<br><br>
<strong>Product Option Image PRO</strong>&nbsp;&nbsp;( <a href="http://www.opencart.com/index.php?route=marketplace/extension/info&amp;extension_id=21188"
target="_blank" title="Product Option Image PRO on opencart.com">opencart.com</a> )<br>
to change main product image and list of additional images on product page depending on selected options
(allows to set some images per option value).<br><br>
';
$_['module_copyright'] = '"'.$_['module_name'].'". is a commercial extension. Resell or transfer it to other users is NOT ALLOWED.
<br>By purchasing this module, you get it for use on one site. 
If you want to use the module on multiple sites, you should purchase a separate copy for each site.
<br>Thank you for purchasing the module.
';

//warning
$_['warning_equal_options']           = 'matching set of options';
$_['warning_max_input_vars']          = 'Warning: there a lot of data on the page (options etc), to save it properly php configuration setting <b>max_input_vars</b> should be increased for admin section directory (current value is %s).';

// Error
$_['error_equal_options']             = 'matching set of options';
$_['error_not_enough_options']        = 'not all related options are set';
$_['error_permission']                = 'Warning: You do not have permission to modify module!';
$_['error_vqmod_not_found']           = 'The module modification is not applied! Please, check your <a href="'.$_['text_vqmod_href'].'" target="_blank">vQmod</a> installation. '.$_['text_vqmod_required'].'.';


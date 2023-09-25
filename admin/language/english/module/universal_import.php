<?php
// Heading
$_['heading_title'] = '<img src="view/universal_import/img/icon.png" alt="" style="vertical-align:bottom;padding-right:4px"/><b>Universal</b> <b style="color:#156584;">Import/Export Pro</b>';

// Text 
$_['text_module'] = 'Modules';
$_['text_browse'] = 'Browse';
$_['text_continue'] = 'Next step';
$_['text_add_sub'] = 'Add';
$_['text_add_cat'] = 'Add category';
$_['text_add_subcat'] = 'Add subcategory';
$_['text_add_column'] = 'Add column';
$_['text_remove_column'] = 'Remove column';
$_['not_found'] = 'Not found';
$_['new'] = 'new';

$_['text_success'] = 'Success: You have modified settings!';
$_['text_store_select'] = 'Store:';
$_['text_import'] = 'import';
$_['text_source_upload'] = 'File upload';
$_['text_source_ftp'] = 'FTP';
$_['text_source_url'] = 'URL';
$_['text_source_path'] = 'Local path';
$_['text_type_product'] = 'Product';
$_['text_type_product_update'] = 'Product quick update';
$_['text_type_order'] = 'Order';
$_['text_type_order_status_update'] = 'Order status update';
$_['text_type_category'] = 'Category';
$_['text_type_information'] = 'Information';
$_['text_type_manufacturer'] = 'Manufacturer';
$_['text_type_customer'] = 'Customer';
$_['text_subtype_order_info'] = 'Order information';
$_['text_subtype_order_item'] = 'Order item';
$_['text_type_attribute'] = 'Attribute';
$_['text_type_filter'] = 'Filter';
$_['text_product'] = 'Products';
$_['text_category'] = 'Categories';
$_['text_information'] = 'Informations';
$_['text_manufacturer'] = 'Manufacturers';
$_['text_customer'] = 'Customers';
$_['text_order'] = 'Orders';
$_['text_order_status'] = 'Order statuses';
$_['text_ignore'] = '';
$_['text_column'] = 'column';
$_['text_next_step'] = 'Validate and continue';
$_['text_start_process'] = 'Start import processing';
$_['text_pausing_process'] = 'Pausing, please wait until last batch terminate...';
$_['text_resume_process'] = 'Resume process';
$_['text_start_simu_process'] = 'Start full simulation';
$_['text_pause_process'] = 'Import in progress - Click to pause';
$_['text_pause_simu_process'] = 'Simulation in progress - Click to pause';
$_['text_previous_step'] = 'Previous step';
$_['text_data_preview'] = 'Data preview';
$_['text_row'] = 'Row';
$_['text_status'] = 'Status';
$_['text_message'] = 'Message';

$_['text_action_update'] = 'Update - replace';
$_['text_action_soft_update'] = 'Update - preserve';
$_['text_action_insert'] = 'Insert';
$_['text_action_skip'] = 'Skip';
$_['text_action_option'] = 'Add option';
$_['text_action_delete'] = 'Delete';
$_['text_action_overwrite'] = 'Overwrite';
$_['text_action_rename'] = 'Rename';
$_['text_img_action_keep'] = 'Keep actual image';
$_['text_img_action_rename'] = 'Rename new image';
$_['text_img_action_overwrite'] = 'Overwrite with new image';

// Tabs
$_['text_tab_0'] = 'Import';

$_['text_import_step1'] = '<b>Step 1</b> - File selection';
$_['text_import_step2'] = '<b>Step 2</b> - Import settings';
$_['text_import_step3'] = '<b>Step 3</b> - Column mapping';
$_['text_import_step4'] = '<b>Step 4</b> - Data verification';
$_['text_import_step5'] = '<b>Step 5</b> - Start process';

// Profile manager
$_['text_profile_dir_not_writable'] = 'Profile folder not writable, make sure you have write rights on folder (and subfolders):';
$_['text_profile_name'] = 'Profile name';
$_['text_new_profile'] = '- Save as new profile -';
$_['text_save_profile'] = 'Save profile';
$_['text_delete_profile'] = 'Delete profile';
$_['text_really_delete'] = 'Are you sure you want to delete this profile?';
$_['text_save_profile_i'] = 'Save your current settings set in order to use it on further imports.<br/>You can select existing profile to overwrite it or create a new one.';
$_['text_profile_saved'] = 'Your profile has been correctly saved';

// Step 1
$_['entry_name'] = 'Name';
$_['entry_demo_file'] = 'Load demo file';
$_['entry_demo_file_i'] = 'Use the pre-loaded files to test the behaviour of the module. If you want to upload your own leave that field blank and upload a file in the previous field.';
$_['entry_type'] = 'Import type';
$_['entry_subtype'] = 'Import subtype';
$_['entry_type_i'] = 'What kind of data are you about to import ?';
$_['entry_profile'] = 'Settings profile';
$_['text_select_profile'] = 'Select a setting profile';
$_['entry_profile_i'] = 'Profile contains all settings you can set in steps 2 and 3. You can save a new profile in step 4';
$_['entry_file'] = 'Select file source';
$_['entry_file_i'] = 'Select your file here.<br/>Unlimited size is supported.<br/>Compatible formats : CSV, XML, XLS, XLSX, ODS';
$_['source_ftp'] = 'FTP connexion';
$_['source_ftp_i'] = 'Connect to a FTP to get the file, don\'t forget the initial ftp://';
$_['source_ftp_path'] = 'FTP file path';
$_['source_url'] = 'File link';
$_['source_url_i'] = 'Get a file from an external url, if the file is password protected set the url like this http://user:password@website.tld/...';
$_['source_path'] = 'Local file path';
$_['source_path_i'] = 'This is referring to a file that is on your server';
$_['text_extension_auto'] = 'Auto detect extension';
$_['text_dropzone'] = 'Drop file here or click to upload';
$_['text_file_loaded'] = 'File loaded:';
$_['text_file_error'] = 'Error:';

// Step 2
$_['text_common_settings'] = 'Common settings';
$_['entry_xml_node'] = 'XML item node';
$_['entry_xml_node_i'] = 'Set the xml node for each item in your file for example <product> or <item> (set value without brackets). The system will try to auto-detect it, just change it if incorrect value';
$_['entry_csv_separator'] = 'CSV field separator';
$_['entry_multiple_separator'] = 'Multiple value separator';
$_['entry_multiple_separator_i'] = 'If the field contains multiple values define the character used to separate these';
$_['entry_xls_sheet'] = 'Work sheet';
$_['entry_xls_sheet_i'] = 'If your file contains various sheets you can choose the one to work with';
$_['entry_csv_header'] = 'First row is header';
$_['entry_item_identifier'] = 'Existing item identifier';
$_['entry_item_identifier_i'] = 'Detect if the item you are about to insert exists if it match this field';
$_['entry_item_exists'] = 'Existing item action';
$_['entry_item_exists_i'] = 'Replace mode: all values will be updated, blank column mapping means any actual value will be removed (this is using the default opencart method, and includes any modification applied to it) - Preserve mode: will update only the fields set in column mapping, blank field means no change of actual value (not opencart method, custom values may not be applied here)';
$_['entry_item_not_exists'] = 'New item action';
$_['text_image_settings'] = 'Image settings';
$_['entry_image_download'] = 'Download images';
$_['entry_image_download_i'] = 'If image is external url, automatically download it to your server, to link existing images use the path relative to /image/catalog/';
$_['entry_image_exists'] = 'Image exists action';
$_['text_delete_settings'] = 'Remove or disable items';
$_['entry_delete'] = 'Mode';
$_['entry_delete_action'] = 'Action';
$_['entry_delete_batch'] = 'Import label';
$_['entry_no_delete_skipped'] = 'Skipped';
$_['text_no_delete_skipped_off'] = 'Remove also skipped items';
$_['text_no_delete_skipped_on'] = 'Do not remove skipped items';
$_['text_delete_nothing'] = 'Do not remove anything';
$_['text_delete_all'] = 'For all items before import';
$_['text_delete_batch'] = 'For all items of a specific import label';
$_['text_delete_missing'] = 'For non-updated items only';
$_['text_delete_missing_brand'] = 'For non-updated items but only in updated brands';
$_['text_delete_zero'] = 'Set quantity to zero';
$_['batch_delete_everything'] = 'Any import label';
$_['batch_delete_empty'] = 'No import label';
$_['batch_delete_defined'] = 'Existing import label';
$_['batch_group_general'] = 'Delete items with:';
$_['batch_group_specific'] = 'Or only with specific import label:';
$_['warning_delete'] = 'Warning: Items will be removed from database, make sure this is what you want';
$_['text_deleted'] = 'Deleted item: ';
$_['text_nothing_deleted'] = 'nothing to delete';
$_['text_disabled_all'] = 'Set all items to disabled';
$_['text_delete_delete'] = 'Delete (remove from database)';
$_['text_delete_disable'] = 'Disable (set status to disabled)';
$_['entry_image_exists_i'] = 'What to do if image with same name already exists ?';
$_['entry_image_location'] = 'Image location';
$_['entry_image_location_i'] = 'In case of image download, put them in some specific directory (ex: products/). This parameter is not used in case of existing images';
$_['entry_image_keep_path'] = 'Replicate url path';
$_['entry_image_keep_path_i'] = 'Keep same structure as on current location. For example if url is myshop.com/dir/subdir/image.jpg the image will be saved in dir/subdir/image.jpg.';
$_['text_category_settings'] = 'Category settings';
$_['entry_category_create'] = 'Create if not exists';
$_['entry_include_subcat'] = 'Include product in parents';
$_['entry_include_subcat_i'] = 'If you have a category defined like this Desktop > PC > Keyboards, then by default product will go into Keyboards, if you set parent mode it will go into Keyboards and PC, and if you set to all it will go in all these 3 categories';
$_['entry_filter_to_category'] = 'Auto create filters';
$_['entry_filter_to_category_i'] = 'Automatically add the filters of imported products to their corresponding categories';
$_['text_include_subcat_none'] = 'Put the product only in defined category';
$_['text_include_subcat_parent'] = 'Put the product also in parent category';
$_['text_include_subcat_all'] = 'Put the product in all parent categories';
$_['text_manufacturer_settings'] = 'Manufacturer settings';
$_['entry_manufacturer_create'] = 'Create if not exists';
$_['text_product_settings'] = 'Product settings';
$_['entry_preserve_attribute'] = 'Preserve attributes';
$_['entry_preserve_attribute_i'] = 'If enabled attributes won\'t be reseted when importing, this is working only in update preserve mode!';
$_['entry_filter_generate'] = 'Generate filters from';
$_['entry_filter_generate_i'] = 'Check these options to automatically generate the product filters based on the following fields';
$_['entry_limit'] = 'Limit import';
$_['entry_limit_i'] = 'Limit the number of items to import  Start: begin from this item row - End: stop at this item row';
$_['entry_limit_start'] = 'Start row';
$_['entry_limit_max'] = 'Max items';
$_['text_extra_fields'] = 'Extra fields';
$_['entry_extra_fields'] = 'Extra fields';
$_['entry_extra_fields_i'] = 'Comma separated, add as much as extra fields you need for further use with extra functions, for example you can do some operation on an existing field of your feed and then save the result into an extra field';
$_['text_limit_settings'] = 'Limit data set';
$_['entry_limit_batch_status'] = 'Limit import label';
$_['entry_limit_field_status'] = 'Limit on DB field';
$_['entry_limit_field'] = 'Limit DB field if';
$_['import_all_attribute'] = 'Import all attributes';
$_['import_product_attribute'] = 'Import product attributes';
$_['text_import_product_attributes'] = 'Import attributes directly to your products, you need to select the identifier of the product where the attribute will be added.';
$_['entry_delete_attributes'] = 'Clear attributes';
$_['entry_delete_attributes_i'] = 'Enable this option if you want current product attributes to be cleared before import';
$_['entry_attribute_id'] = 'Attribute ID';
$_['entry_attribute_name'] = 'Attribute name';
$_['import_all_filter'] = 'Import all filters';
$_['import_product_filter'] = 'Import product filters';
$_['text_import_product_filters'] = 'Import filters directly to your products, you need to select the identifier of the product where the filter will be added.';
$_['entry_delete_filter'] = 'Clear attributes';
$_['entry_delete_filter_i'] = 'Enable this option if you want current product filters to be cleared before import';
$_['entry_filter_id'] = 'Filter ID';
$_['entry_filter_name'] = 'Filter name';
$_['entry_sort_order'] = 'Sort order';

// Step 3
$_['tab_order'] = 'Order';
$_['tab_info'] = 'Extra info';
$_['tab_extra'] = 'Custom Fields';
$_['entry_extra'] = 'Custom Field';
$_['entry_extra_ml'] = 'Description Custom Field';
$_['entry_disable_config'] = 'Disable config';
$_['placeholder_disable_config'] = 'config_module_option, config_extra_option';
$_['placeholder_extra_col'] = 'Field name';
$_['tab_disable_cfg'] = 'Disable config options';
$_['info_disable_cfg'] = 'Just like the previous option this could be useful in case some third party module wants to insert data on item creation/edition.<br/>If you don\'t have corresponding data in your import file and the import fails because of the missing extra data you can either use the extra field option with empty value, or in some case it is even easier to just disable the corresponding config option.<br/><br/>For example if you have in your model something like that:<br/><code>if ($this->config->get(\'superfilter_status\')) {<br/>&nbsp;&nbsp;&nbsp;// insert module data...<br/>&nbsp;}</code><br/><br/>Then if this code part is making the import to fail you can just put in the field below "superfilter_status", it will temporary set this parameter to false so you can avoid related errors during import.<br/>Use comma as separator if you need to put various values.';
$_['info_extra_field'] = 'Here you can define your own custom fields, this is useful in case you have some module that is adding data into your tables.<br/>If the custom field is multilingual, then it is stored in description part so you have to use Description custom field, can be useful for seo module for example which are adding SEO H1 and other data, find out which is the name used by this module and put "meta_h1" or "seo_h1" into Field name, and in right selector just select to which column it will take the data.<br/><br/>If during the import you have some undefined index errors it is many time because you have custom fields added by modules, so you have to use this section to add them for the import process, the error will tell you what is the exact name required.<br/><br/>The custom field name must be set as it appears into your database.';
$_['text_remove_extra_col'] = 'Remove custom field';
$_['text_add_extra_field'] = 'Add new custom field';
$_['text_add_extra_field_ml'] = 'Add new description custom field';
$_['tab_functions'] = 'Extra Functions';
$_['text_add_function'] = 'Add function';
$_['text_function_type'] = 'Type';
$_['text_function_action'] = 'Action';
$_['text_function_target'] = 'Save result in';
$_['text_function_same_field'] = 'Current field';
$_['text_function_extra_field'] = 'Extra field';
$_['text_remove_function'] = 'Remove function';
$_['info_identifier_title'] = 'Item identifier';
$_['info_identifier'] = 'The field below is mandatory as it is the one used to identify the item to update.';
$_['tab_quick_update'] = 'Quick update';
$_['entry_option_identifier'] = 'Option identifier';
$_['entry_option_identifier_i'] = 'Leave empty to update main product';
$_['entry_option_identifier_value'] = 'Option value';
$_['text_quick_update_identifier'] = 'Here you can update your items quickly with most common updated values.<br/>The field below is mandatory as it is the one used to identify the item to update.';
$_['text_quick_update_option_identifier'] = '<h4>Update a product option</h4>If you want to update only a product option quantity you have to identify your product option with the next fields, select the option and what correspond to this option in your import feed.<br/>For example Option identifier as "Color" and in Option value select the field where you have the color inside.';
$_['entry_product_id'] = 'Product ID';
$_['entry_title'] = 'Title';
$_['entry_product_name'] = 'Product name';
$_['entry_payment_method_name'] = 'Payment method name';
$_['entry_payment_method_code'] = 'Payment method code';
$_['help_field_payment_method_name'] = 'Name of payment method as it will appear on order, you can set only this one, if code is not set it will just not be pre-selected when editing order';
$_['help_field_payment_method_code'] = 'Here is expected code in opencart format, for example bank_transfer, cod, paypal, free_checkout';
$_['entry_shipping_method_name'] = 'Shipping method name';
$_['entry_shipping_method_code'] = 'Shipping method code';
$_['help_field_shipping_method_name'] = 'Name of shipping method as it will appear on order, you can set only this one, if code is not set it will just not be pre-selected when editing order';
$_['help_field_shipping_method_code'] = 'Here is expected code in opencart format, for example free, flat, fedex, weight';
$_['entry_total_code'] = 'Total code';
$_['help_field_code'] = 'The total code in opencart format, for example total, sub_total, shipping, tax';
$_['entry_model'] = 'Model';
$_['entry_price'] = 'Price';
$_['entry_tax'] = 'Tax';
$_['entry_order_id'] = 'Order ID';
$_['entry_order_id_user'] = 'Random Order ID';
$_['entry_create_order_total'] = 'Auto create total item';
$_['entry_notify'] = 'Notify';
$_['entry_tracking_no'] = 'Tracking number';
$_['entry_tracking_url'] = 'Tracking URL';
$_['entry_order_status_comment'] = 'Comment';
$_['entry_order_status'] = 'Order status';
$_['help_field_order_status'] = 'All order will be updated with given order status, can be order status ID or name, you can also let empty and force the order status with the Default field';
$_['entry_seo_h1'] = 'SEO H1';
$_['entry_seo_h2'] = 'SEO H2';
$_['entry_seo_h3'] = 'SEO H3';
$_['entry_img_title'] = 'Image title';
$_['entry_img_alt'] = 'Image alt';
$_['entry_separator'] = 'Separator';
$_['entry_subcat_separator'] = 'Subcategory separator';
$_['entry_subcat_separator_i'] = 'For example cat1 > subcat1 ; cat2 > subcat2';
$_['entry_dimension_l'] = 'Length';
$_['entry_dimension_w'] = 'Width';
$_['entry_dimension_h'] = 'Height';
$_['entry_category_id'] = 'Category ID';
$_['entry_information_id'] = 'Information ID';
$_['entry_manufacturer_id'] = 'Manufacturer ID';
$_['entry_customer_id'] = 'Customer ID';
$_['entry_value_modifier'] = 'Mode';
$_['entry_value_modifier_i'] = 'Replace: replace the actual value with the new one - Add: add new value to actual one - Subtract: subtract new value to actual one';
$_['entry_email'] = 'Email';
$_['import_default_value'] = 'Default';
$_['entry_default'] = 'Default';
$_['entry_default_i'] = 'Insert here a default value for this field, if the field is empty or not mapped to any value the default value will be used';
$_['help_field_category'] = 'If multiple categories defined in this field, use the multiple value separator, for example: Cat1 > subcat1 ; Cat2 > subcat2';
$_['help_field_related_id'] = 'Use multiple separator to insert multiple values, value can be product ID or item identifier set in step 2';
$_['help_field_image'] = 'In case of multiple values, first one is chosen';
$_['help_field_product_image'] = 'To import various images you can use one field with multiple separator or click add columns to take the images from various fields';
$_['help_field_product_option_xml__'] = 'Use this format: <option> <optionType>type</optionType> <optionNameEn>Name english</optionNameEn> <optionValueEn>Value english</optionValueEn> <optionPrice>Price</optionPrice> <optionRequired>Required</optionRequired> </option> - The options and options values are created automatically if they doesn\'t exist.';
$_['help_field_product_option__'] = 'Can be formatted in various ways - 1: <name>:<value> (type is automatically set to select if the option not exists) - 2: <type>:<name>:<value> 3: <type>:<name>:<value>:<price> - 4: <type>:<name>:<value>:<price>:<qty> - 5: <type>:<name>:<value>:<price>:<qty>:<subtract> - 6: <type>:<name>:<value>:<price>:<qty>:<subtract>:<weight> - 7: <type>:<name>:<value>:<price>:<qty>:<subtract>:<weight>:<required> - The options and options values are created automatically if they doesn\'t exist.';
$_['help_field_product_attribute__'] = 'Can be formatted in various ways - 1: <attribute_group_name>:<attribute_name>:<value> - 2: <attribute_name>:<value> 3: <value> (header is used as attribute name) - 4: <ul><li>Group name: value</li></ul>The group and attribute are created automatically if they doesn\'t exists. In cases 2 and 3, if attribute not exist it is assigned to group `Default`';
$_['help_field_product_special'] = 'Must be formatted like this: <price> - or <customer_group_id>:<price> - or <customer_group_id>:<priority>:<price> - or <customer_group_id>:<priority>:<price>:<date_end> - or <customer_group_id>:<priority>:<price>:<date_start>:<date_end> (if a date is not inserted it will be set as no time limit)';
$_['help_field_product_discount'] = 'Must be formatted like this: <price> - or <price>:<date_end> - or <price>:<date_start>:<date_end> - or <qty>:<price>:<date_start>:<date_end> - or <customer_group_id>:<qty>:<price>:<date_start>:<date_end> - or <customer_group_id>:<qty>:<priority>:<price>:<date_start>:<date_end>';
$_['help_field_product_id'] = 'Use this if you want to force product ID';
$_['help_field_manufacturer_id'] = 'Use either manufacturer ID or manufacturer name';
$_['help_field_product_category'] = 'Use either category ID or category name. You can add as many subcategories as necessary they will follow the order set here';
$_['help_field_product_filter'] = 'Format your filters like this: filter_group:filter_name';
$_['help_field_stock_status_id'] = 'Works with stock name or ID, if name not exists it will be created';
$_['help_field_status'] = $_['help_field_notify'] = $_['help_field_newsletter'] = $_['help_field_safe'] = $_['help_field_approved'] = 'It works with these values: 1/0, on/off, true/false, enabled/disabled, yes/no, active/inactive';
$_['help_field_order_status_comment'] = 'You can use these tags: {tracking_no} {tracking_url}';
$_['help_field_parent_id'] = 'If more than 2 levels this field should be formatted with subcategory separator: Cat1 > Subcat1 > Subcat2';
$_['entry_salt'] = 'salt';
$_['help_field_password'] = '';
$_['help_field_salt'] = 'Salt is additional protection for the password, this field must be set if the passwords you import are already encrypted, if not set your users won\'t be able to login';
$_['entry_pwd_hash'] = 'Encryption';
$_['entry_pwd_hash_i'] = 'If your password is encrypted check the corresponding value here and fill the salt field which is necessary for the customer to login';
$_['text_pwd_clear'] = 'Clear password';
$_['text_pwd_hash'] = 'Encrypted password';
$_['entry_invoice_no'] = 'Invoice No';
$_['entry_invoice_prefix'] = 'Invoice prefix';
$_['entry_user_agent'] = 'User agent';

// extra functions
$_['xfn_group_string'] = 'Text operations';
$_['xfn_group_regex'] = 'Regex text operations';
$_['xfn_group_number'] = 'Number operations';
$_['xfn_group_html'] = 'HTML';
$_['xfn_group_product'] = 'Product';
$_['xfn_group_web'] = 'Web';
$_['xfn_group_other'] = 'Special functions';
$_['xfn_manual_value'] = 'Manual value -&gt;';
$_['xfn_append'] = '<i class="fa fa-fw">&#xf038;</i> Append';
$_['xfn_append_i'] = 'Append something after a field';
$_['xfn_prepend'] = '<i class="fa fa-fw">&#xf036;</i> Prepend';
$_['xfn_prepend_i'] = 'Prepend something before a field';
$_['xfn_uppercase'] = '<i class="fa fa-fw">&#xf031;</i> Uppercase';
$_['xfn_uppercase_i'] = 'Example: LUKE, I AM YOUR FATHER';
$_['xfn_lowercase'] = '<i class="fa fa-fw">&#xf031;</i> Lowercase';
$_['xfn_lowercase_i'] = 'Example: luke, i am your father';
$_['xfn_ucfirst'] = '<i class="fa fa-fw">&#xf031;</i> Uppercase on first letter';
$_['xfn_ucfirst_i'] = 'Example: Luke, I am your father';
$_['xfn_ucwords'] = '<i class="fa fa-fw">&#xf031;</i> Uppercase on first word letters';
$_['xfn_ucwords_i'] = 'Example: Luke, I Am Your Father';
$_['xfn_substr'] = '<i class="fa fa-fw">&#xf0c4;</i> Truncate string';
$_['xfn_substr_i'] = 'Get x first chars of a field';
$_['xfn_urlify'] = '<i class="fa fa-fw">&#xf0c1;</i> Format for url';
$_['xfn_urlify_i'] = 'Format a string for use in seo keyword, apply lowercase and replace whitespaces by dashes, if translit is applied all accentuated chars will be transformed to their ascii equivalent';
$_['xfn_substr_set'] = 'Truncate chars number';
$_['xfn_substr_of'] = 'From';
$_['xfn_nl2br'] = '<i class="fa fa-fw">&#xf121;</i> Convert line breaks to HTML';
$_['xfn_nl2br_i'] = 'Line breaks will be converted to html format with a br tag';
$_['xfn_strip_tags'] = '<i class="fa fa-fw">&#xf121;</i> Strip HTML tags';
$_['xfn_strip_tags_i'] = 'Remove any HTML tag to get a clean text';
$_['xfn_html_encode'] = '<i class="fa fa-fw">&#xf121;</i> Encode HTML entities';
$_['xfn_html_encode_i'] = 'Convert special characters to HTML entities';
$_['xfn_html_decode'] = '<i class="fa fa-fw">&#xf121;</i> Decode HTML entities';
$_['xfn_html_decode_i'] = 'Convert all HTML entities to their applicable characters - can be useful if you have some html content not correctly imported';
$_['xfn_date_convert'] = '<i class="fa fa-fw">&#xf073;</i> Date converter';
$_['xfn_date_convert_i'] = 'Convert a date to SQL format, choose the current format of your date field to get it correctly converted';
$_['xfn_if_table'] = '<i class="fa fa-fw">&#xf059;</i> Conditional value';
$_['xfn_if_table_i'] = 'Set final value depending on your actual value, use an operator to compare to the field value, and choose the final value. Example: ~(wheels):Car means if current field contains the word wheels then set Car as the output.';
$_['xfn_replace'] = '<i class="fa fa-fw">&#xf0d0;</i> Replace text';
$_['xfn_replace_set'] = 'Replace';
$_['xfn_regex_replace'] = '<i class="fa fa-fw">&#xf0d0;</i> Regex replace';
$_['xfn_regex_replace_i'] = 'Replace a part of a string using regex, for example if you want to change a field like brand:Sony|type:Laptop to put MyBrand instead of any other brand, following regex search: brand:(.+)| replace: brand:MyBrand|';
$_['xfn_regex'] = '<i class="fa fa-fw">&#xf0c4;</i> Regex extract';
$_['xfn_regex_i'] = 'Extract a part of a string using regex, use the () to define the part you want to extract, for example if you have a field like brand:Sony|type:Laptop, and you want to extract Sony for inserting in brands apply the following regex: brand:(.+)|';
$_['xfn_regex_remove'] = '<i class="fa fa-fw">&#xf12d;</i> Regex remove';
$_['xfn_regex_remove_i'] = 'Remove text with regex, for example remove any special char from a string: [^0-9a-zA-Z]';
$_['xfn_remove'] = '<i class="fa fa-fw">&#xf12d;</i> Remove text';
$_['xfn_remove_set'] = 'Remove';
$_['xfn_remote_content_set'] = 'Get content from url of field';
$_['xfn_remote_content'] = '<i class="fa fa-fw">&#xf019;</i> Remote content';
$_['xfn_remote_content_i'] = 'Get the content of the page contained in an url, can be useful if only a link is provided in feed to get description or other data.';
$_['xfn_add'] = '<i class="fa fa-fw">&#xf1ec;</i> Add';
$_['xfn_subtract'] = '<i class="fa fa-fw">&#xf1ec;</i> Subtract';
$_['xfn_multiply'] = '<i class="fa fa-fw">&#xf1ec;</i> Multiply';
$_['xfn_divide'] = '<i class="fa fa-fw">&#xf1ec;</i> Divide';
$_['xfn_round'] = '<i class="fa fa-fw">&#xf1ec;</i> Round';
$_['placeholder_xfn_if_table'] = '~(search_value):final_value (one per line)';
$_['placeholder_xfn_wholesale'] = 'max_price:multiplier (one per line)';
$_['xfn_wholesale'] = '<i class="fa fa-fw">&#xf1ec;</i> Wholesale price multiplier';
$_['xfn_wholesale_i'] = 'Multiply the current price by different factor depending on the value, for example if you want all price below 10 to be multiplied by 2 just write 10:2, then if you want all price below 50 to be multiplied by 1.5 add new row and write 50:1.5';
$_['xfn_skip_set'] = 'Skip current item if';
$_['xfn_skip_db_set'] = 'Skip current item if';
$_['xfn_option'] = '<i class="fa fa-fw">&#xf0c9;</i> Product option';
$_['xfn_option_set'] = 'Current row is product option if';
$_['xfn_skip'] = '<i class="fa fa-fw">&#xf054;</i> Skip on import value';
$_['xfn_skip_i'] = 'Skip current import item if value a value in your import file matches another value (import file value or manual value)';
$_['xfn_skip_db_i'] = 'Skip current import item if value a value of your store item matches another value (import file value or manual value)';
$_['xfn_skip_db'] = '<i class="fa fa-fw">&#xf054;</i> Skip on existing value';
$_['xfn_delete_item'] = '<i class="fa fa-fw">&#xf014;</i> Remove or disable item';
$_['xfn_multiple_separator'] = '<i class="fa fa-fw">&#xf03a;</i> Multiple values separator';
$_['xfn_multiple_separator_set'] = 'Set multiple values separator as';
$_['xfn_for_column'] = 'For column';
$_['xfn_source_field'] = 'Source field';
$_['xfn_target_field'] = 'Target field';
$_['text_if_table_table'] = '<div style="cursor:text">Conditional table<br/><span style="color:#888"><b>Is equal</b><br/>=(test):final_value<br/>!=(test):final_value<br/><b>Contains</b><br/>~(test):final_value<br/>!~(test):final_value<br/><b>Is greater</b><br/>&gt;(test):final_value<br/>&lt;(test):final_value<br/></span></div>';
$_['text_wholesale_table'] = 'Multiplier table';
$_['text_round'] = 'Round';
$_['xfn_if'] = 'If';
$_['xfn_of'] = 'Of';
$_['xfn_to'] = 'To';
$_['xfn_by'] = 'By';
$_['xfn_in'] = 'In';
$_['xfn_for'] = 'For';
$_['xfn_from'] = 'From';
$_['xfn_after'] = 'After';
$_['xfn_before'] = 'Before';
$_['xfn_is_equal'] = 'is equal to';
$_['xfn_is_not_equal'] = 'is not equal to';
$_['xfn_contain'] = 'contains';
$_['xfn_not_contain'] = 'do not contain';
$_['xfn_is_greater'] = 'is greater than';
$_['xfn_is_lower'] = 'is lower than';
$_['xfn_mode'] = 'Mode';
$_['xfn_precision'] = 'With a precision of';
$_['xfn_precision_'] = 'No round';
$_['xfn_precision_0'] = 'Integer (1)';
$_['xfn_precision_1'] = '1 decimal (1.2)';
$_['xfn_precision_2'] = '2 decimals (1.23)';
$_['xfn_precision_3'] = '3 decimals (1.234)';
$_['xfn_precision_4'] = '4 decimals (1.2345)';
$_['text_delete_if'] = 'Delete if';
$_['text_value_is'] = 'Is equal to';
$_['text_urlify_basic'] = 'Basic';
$_['text_urlify_default'] = 'Basic formatting';
$_['text_urlify_ascii'] = 'Translit to ascii';
$_['text_translit'] = 'Translit';
$_['info_extra_functions'] = 'With extra functions you can make some specific operations on your source data, like for example multiply a number, or put a string in uppercase, etc.<br/><br/>Usage:<ol><li>Select the function you want in the box below</li><li>Click on "Add function"</li><li>Select the source field on which to apply the function</li><li>Define other parameters if any</li><li>Select the target field in which you want to save the result</li></ol>You can apply multiple operation on a same field, they will be applied in the order they are defined.<br/><br/>If you want to save some data in extra fields don\'t forget to add your extra fields in step 2.';
$_['text_date_format_eu'] = 'EU: DD/MM/YYYY';
$_['text_date_format_us'] = 'US: MM/DD/YYYY';
$_['text_convert_from'] = 'Convert from';

// option bindings
$_['text_optbinding_name'] = 'Type';
$_['text_optbinding_bind_to'] = 'Field';
$_['text_get_optbinding'] = 'Get advanced option fields';

// category bindings
$_['tab_cat_binding'] = 'Category Bindings';
$_['entry_cat_binding_mode'] = 'Binding mode';
$_['text_cat_binding_default'] = 'Mixed - insert bound categories and not bound ones with the field value';
$_['text_cat_binding_exclusive'] = 'Exclusive - insert bound categories only, category field value is ignored';
$_['text_cat_binding_exclusive_skip'] = 'Exclusive, skip empty - insert bound categories only, category field value is ignored, skip item if no category';
$_['text_cat_binding_skip'] = 'Skip unbound - insert bound categories only, items with at least one unbound category will be skipped';
$_['text_catbinding_name'] = 'Source category';
$_['text_catbinding_bind_to'] = 'Bind to';
$_['text_get_bindings'] = 'Refresh category list';
$_['text_no_bindings'] = 'No category binding defined, push the refresh button to see all categories in your actual import file.';
$_['text_no_binding'] = '- none -';
$_['info_cat_binding_title'] = 'Category Bindings';
$_['info_cat_binding'] = 'If in your source data you have category names that are not corresponding to yours you can use this feature to define a binding for each category in your source file.<br/><br/>Usage:<ul><li>If not done yet select your category field in "Links" tab</li><li>Click on "Refresh category list" to get the list of all categories existing in your source file</li><li>Select to which opencart category you want to transfer each source category</li></ul>It is not mandatory to set all categories, just delete the ones that do not require binding, for these ones default behaviour will be applied (check if category exists and try to create it if not).<br/>If you want to have your categories to be inserted only based on bound values select the exclisve binding mode.';
$_['info_col_binding_skip'] = 'Item skipped because no category binding as been found for some category';
$_['info_col_binding_skip_empty'] = 'Item skipped because no category as been binded to this product';

// Step 4
$_['text_simu_summary'] = 'Simulation summary (10 rows):';
$_['text_full_simu_summary'] = 'Full simulation report:';
$_['text_item_to_update'] = 'Item to update';
$_['entry_row_status'] = 'Row action';
$_['text_simu_inserted'] = 'To insert';
$_['text_simu_updated'] = 'To update';
$_['text_simu_disabled'] = 'To disable';
$_['text_simu_qtyzero'] = 'Set qty to zero';
$_['text_simu_deleted'] = 'To delete';
$_['text_simu_skipped'] = 'To skip';
$_['text_simu_error'] = 'Error';

// Step 5
$_['text_import_label'] = 'Import label';
$_['text_import_label_i'] = 'Give a name to this import session, then in product list you will be able to filter the products that have been imported this time. Leave empty to not change the import label if exists.';
$_['text_process_summary'] = 'Process summary';
$_['text_rows_csv'] = 'Total rows in file';
$_['text_rows_process'] = 'Total rows to process';
$_['text_rows_insert'] = 'Total rows to insert';
$_['text_rows_update'] = 'Total rows to update';
$_['text_process_done'] = 'Process status';
$_['text_rows_processed'] = 'Processed';
$_['text_rows_inserted'] = 'Inserted';
$_['text_rows_updated'] = 'Updated';
$_['text_rows_disabled'] = 'Disabled';
$_['text_rows_qtyzero'] = 'Set qty to zero';
$_['text_rows_deleted'] = 'Deleted';
$_['text_rows_skipped'] = 'Skipped';
$_['text_rows_error'] = 'Error';
$_['text_empty_line_skip'] = 'Empty row';


$_['entry_color_scheme'] = 'Color scheme:<span class="help">Quick access to some color schemes, edit them in design tab</span>';
$_['entry_logo'] = 'Logo:';
$_['entry_feed_title'] = 'Name:';
$_['entry_cache_delay'] = 'Cache Delay:<span class="help">How many time to display generated file until re-generation ?</span>';
$_['entry_language'] = 'Language:<span class="help"></span>';
$_['entry_feed_url'] = 'Data Feed Url:<span class="help">Give this url to the destination service</span>';
$_['entry_additional_image'] = 'Additionnal images';

$_['text_no_change'] = '- No change -';
$_['text_on'] = 'On';
$_['text_off'] = 'Off';

// File format
$_['text_format_csv'] = 'CSV';
$_['text_format_xml'] = 'XML';
$_['text_format_xls'] = 'XLS';
$_['text_format_xlsx'] = 'XLSX';
$_['text_format_ods'] = 'ODS';
$_['text_format_pdf'] = 'PDF';
$_['text_format_html'] = 'HTML';

// Export
$_['text_tab_1'] = 'Export';
$_['entry_export_type'] = 'Export type';
$_['entry_export_type_i'] = 'What kind of data do you want to export ?';
$_['entry_export_format'] = 'Export format';
$_['entry_export_format_i'] = 'Choose the output file format';
$_['text_start_export'] = 'Generate export file';
$_['export_all'] = '- All -';
$_['export_all_filters'] = 'All filters';
$_['export_product_filters'] = 'Product filters';
$_['export_all_attributes'] = 'All attributes';
$_['export_product_attributes'] = 'Product attributes';
$_['mode'] = 'Mode';

// Export filters
$_['export_filters'] = 'Filters';
$_['total_export_items'] = 'Total items to export:';
$_['filter_language'] = 'Language';
$_['filter_manufacturer'] = 'Manufacturer';
$_['filter_manufacturer_i'] = 'You can select multiple manufacturers, start to type text to find quickly your manufacturer';
$_['filter_category'] = 'Category';
$_['filter_category_i'] = 'You can select multiple categories, start to type text to find quickly your category';
$_['filter_store'] = 'Store';
$_['filter_limit'] = 'Limit';
$_['filter_limit_i'] = 'Limit the number of items to export  Start: begin export from this item - Limit: how many items to export';
$_['filter_order_id'] = 'Order ID';
$_['filter_order_status'] = 'Order status';
$_['filter_order_status_i'] = 'If none selected it will export all order statuses except missing orders';
$_['filter_customer'] = 'Customer';
$_['filter_date_added'] = 'Date added';
$_['filter_date_modified'] = 'Date modified';
$_['filter_total'] = 'Total';
$_['filter_limit_start'] = 'Start';
$_['filter_limit_limit'] = 'Limit';
$_['filter_start'] = 'Start';
$_['filter_end'] = 'End';
$_['filter_min'] = 'Min';
$_['filter_max'] = 'Max';
$_['text_missing_orders'] = 'Missing orders';

// Export options
$_['export_options'] = 'Options';
$_['export_fields'] = 'Export fields';
$_['export_fields_i'] = 'Choose which fields you want to have in your export file';
$_['param_price_multiplier'] = 'Price multiplier';
$_['param_price_multiplier_i'] = 'Change the price displayed on export file, for example set 1.12 to add 12% to original price';
$_['param_image_path'] = 'Image path mode';
$_['param_image_path_i'] = 'Use full url to get the complete url to your image - Opencart relative path is to use only if you will use this export on same website';
$_['image_path_relative'] = 'Opencart relative path';
$_['image_path_absolute'] = 'Full url';

// Configuration
$_['text_tab_2'] = 'Options';
$_['tab_option_1'] = 'Performance';
$_['tab_option_2'] = 'Cron jobs';
$_['tab_option_3'] = 'Main options';
$_['entry_default_import_label'] = 'Default import label';
$_['default_label_i'] = 'Set here the default label for your import session, this will help you to easily filter products imported in a specific session in product list.<br/><br/>You can use these tags : <code>[profile] [day] [month] [year]</code>';
$_['entry_batch_import'] = 'Import batch number';
$_['entry_batch_export'] = 'Export batch number';
$_['entry_sleep'] = 'Process delay';
$_['batch_import_i'] = '<h4>Batch Number</h4><p>Choose the number of items processed on each request, adjust this setting to improve import/export performance.<br/>High number may speed up the process time but too large number may result in failure depending your server ressources.</p><h4 style="margin-top:20px">Process delay</h4><p>Importing and exporting are using very high amount of ressources from server, on shared host or low-profile server it may use almost all ressources and then your website can be unaccessible during the process.<br/>The delay parameter allows you to wait a few time before each item processing, this time will free up some ressource to let the server breath a while and process other requests.<br/>The number is in milliseconds, which means 1000 ms = 1 second, recommended setting is from 50 to 500.<br/>Keep in mind a big value on this setting will increase a lot the import process time.</p>';

// Cron
$_['text_tab_cron_config'] = 'Configuration';
$_['text_tab_cron_import'] = 'Import';
$_['text_tab_cron_export'] = 'Export';
$_['text_tab_cron_report'] = 'Report';
$_['text_cli_log_save'] = 'Save log file';
$_['text_cli_clear_logs'] = 'Clear logs';
$_['entry_cron_key'] = 'Secure key';
$_['entry_cron_key_i'] = 'Define your own secure key, this must be included in the cron command';
$_['entry_cron_command'] = 'Cron command';
$_['text_cron_label'] = 'Set an import label (will use profile label or default label if not defined):';
$_['text_cron_export_email'] = 'Send export file to an email (this will work only for files not too big):';
$_['entry_cron_params'] = 'Extra parameters';
$_['entry_cron_command_i'] = 'This is the command you will have to enter in your cron jobs (adapt [path_to_php] do make it match with your server executable, can be /usr/bin/php, /usr/local/bin/php, /usr/local/cpanel/3rdparty/bin/php, ...)';
$_['entry_cron_params_i'] = 'These parameters are optional, you can add a parameter after the main command, add space between each parameter';
$_['cron_jobs_i'] = 'You can automatically run your predefined profiles by using cron jobs.<br/><br/>All you have to do is to define a profile by adjusting all the settings in first tab, then save it in step 4. Put the exact name of your profile at the end of the command.<br/>A report will be generated so you can consult here if all gone well.<br/><br/>Please note the cron jobs do work only with files from URL, FTP or Local path, not with uploaded files which are for instant use.<br/><br/><b>Set up your cron jobs:</b><ol><li>Go into import tab and choose the file to work with you must use a file in URL, FTP, or Local path</li><li>Set up your feed with the parameters you need, go until step 4</li><li>In step 4 save your profile</li><li>Go into your cPanel or server administration to set up a cron job</li><li>Copy the command you need below (don\'t forget to change the profile name to yours)</li><li>That\'s done, your cron will run based on your parameters and you can check if all is going well into the report tab.</li></ol>';

$_['text_tab_about'] = 'About';

// Warnings
$_['warning'] = 'Warning';
$_['warning_category_id'] = 'Category ID not found.';
$_['warning_discount_format'] = 'Incorrect discount format, format must be: <b>&lt;customer_group_id&gt;:&lt;quantity&gt;:&lt;priority&gt;:&lt;price&gt;:&lt;date_start&gt;:&lt;date_end&gt;</b>, this discount won\'t be added to the product';
$_['warning_remote_image_not_found'] = 'Remote image not found: ';
$_['warning_store_not_found'] = 'Store not found: %s, make sure that store name exists';

// Entry
$_['entry_status'] = 'Status:';

// Message
$_['text_skip_insert']		= 'New item action is set to skip new items';
$_['text_skip_update']		= 'Existing item action is set to skip existing items';
$_['text_skip_quick_update']		= 'The identifier has not been found in your database';
$_['text_skip_delete']		= 'Item to delete does not exists';
$_['text_insert_option']		= 'Add option';
$_['text_skip_option_not_found']		= 'Skipped because option has not been found';
$_['text_skip_product_not_found']		= 'Skipped because corresponding product not found';
$_['text_skip_option_no_product']		= 'Option row skipped because corresponding product not found';
$_['text_skip_option_no_option']		= 'Option row skipped because no option found';

// Info
$_['info_title_default']		= 'Help';
$_['info_msg_default']	= 'Help section for this topic not found';
$_['info_soft_update_mode']	= 'Update preserve mode: only the displayed fields will be updated.';
$_['current_server_path']	= 'This is the path to your opencart root directory: ';
$_['text_filter_group']		= 'Filter group';
$_['text_filter_name']		= 'Filter name';
$_['info_filter_title']		= 'Advanced filter import';
$_['info_filter_xml']		= '
<p>For example you have an array like:<br/>
<code style="white-space:pre">'.print_r(array('0'=> array('groupEn' => 'Filter group', 'nameEn' => 'Filter name')), true).'</code></p>
<p>In such case define main filter to match the field containing the array of values, and in specific parameters below set Filter group to <b>groupEn</b> and Filter name to <b>nameEn</b>.</p>';
$_['text_attribute_group']		= 'Attribute group';
$_['text_attribute_name']		= 'Attribute name';
$_['text_attribute_value']		= 'Attribute value';
$_['info_attributes_title']		= 'Attribute import';
$_['info_attributes_xml_title']		= 'Advanced parameters for array of values (XML import)';
$_['info_attributes']		= '
<p>Can be formatted in various ways:
<ul><li>attribute_group_name:attribute_name:value</li>
<li>attribute_name:value</li>
<li>value (header is used as attribute name)</li>
<li>&lt;ul&gt;&lt;li&gt;Group name: value&lt;/li&gt;&lt;/ul&gt;</li></ul></p>
<p>The group and attribute are created automatically if they doesn\'t exists. If attribute not exist and group not defined it is automatically assigned to group "Default".</p>';
$_['info_attributes_xml']		= '
<h4>Case 1</h4>
<p>For example you have an array like:<br/>
<code style="white-space:pre">'.print_r(array('0'=> array('name' => 'Attribute name', 'value' => array('val' => 'Attribute value'))), true).'</code></p>
<p>In such case define main attribute to match the field containing the array of values, and in specific parameters below set Attribute name to <b>name</b> and Attribute value to <b>value/val</b>.</p>
<h4>Case 2</h4>
<p>XML formatted like:<br/>
<code style="white-space:pre">&lt;attributes attribute_name_1="attribute_value_1" attribute_name_2="attribute_value_2"&gt;&lt;/attribute&gt;</code></p>
<p>Set Attribute to <b>attributes</b> and put the keyword [attributes] into <b>Attribute name</b></p>
';
$_['info_options_title']		= 'Option import';
$_['info_options_advanced_title']		= 'Advanced parameters for option import';
$_['info_options_xml_title']		= 'Advanced parameters for array of values (XML import)';
$_['info_options']		= '
<p>Can be formatted in various ways:
<ul>
<li>value (name is the name of header or node, type is automatically set to select if the option not exists)</li>
<li>name : value (type is automatically set to select if the option not exists)</li>
<li>type : name : value</li>
<li>type : name : value : price</li>
<li>type : name : value : price : qty</li>
<li>type : name : value : price : qty : subtract</li>
<li>type : name : value : price : qty : subtract : weight</li>
<li>type : name : value : price : qty : subtract : weight : required</li></ul></p>
<p>The options and options values are created automatically if they doesn\'t exist.</p>';
$_['info_options_advanced']		= '
<p><b>Separate fields for options</b><br/>If your options are located in separate fields, leave the main option field empty and click on "Get advanced option fields" to be able set them separatly.</p>
<p><b>Your import file have the options set as a new row</b><br/>Then configure the advanced option fields as described here, and go to "Extra Functions" tab to add the function "Product option" to specify how to identify an option row.';
$_['info_options_xml']		= '
<p>For example you have an array like:<br/>
<code style="white-space:pre">'.print_r(array('0'=> array('name' => 'Option name', 'value' => array('val' => 'Option value'))), true).'</code></p>
<p>In such case define main option to match the field containing the array of values, and in specific parameters below set Option name to <b>name</b> and Option value to <b>value/val</b>.<br/>
If you have multiple value fields and the values can be in one or the other field then you can define to check in both fields using the | to separate them, like this: field1|field2</p>';

// Error
$_['warning_incorrect_image_format'] = 'Image format is not correct:';
$_['error_curl'] = 'There was an error when trying to download the file, can be because of file not exists or forbidden access to server.<br/>Detail: <b>%s</b>';
$_['error_extension'] = 'The file type has not been found or is not compatible for import: <b>%s</b>, allowed import types: csv, xml, xls, xlsx, odt. Try to force file extension.';
$_['error_file_not_found'] = 'Warning: The file cannot be opened, please check if path is correct';
$_['error_xml_no_data'] = 'Warning: No data has been found with given xml node, make sure you set correctly the node name containing your data set';
$_['error_permission'] = 'Warning: You do not have permission to modify this module!';
$_['error_permission_demo'] = 'Demo mode, saving is not allowed';

<?php
// Heading
$_['heading_title']						= 'ExcelPort';
$_['heading_title_version']				= 'ExcelPort 2.5.5';

// Text
$_['text_yes'] = 'Yes';
$_['text_no'] = 'No';
$_['text_module']         				= 'Modules';
$_['text_success']						= 'Success: You have modified module ExcelPort!';
$_['text_activate']						= 'Activate';
$_['text_not_activated']				= 'ExcelPort is not activated.';
$_['text_click_activate']				= 'Activate ExcelPort';
$_['text_success_activation']			= 'ACTIVATED: You have successfully activated ExcelPort!';
$_['text_content_top']					= 'Content Top';
$_['text_content_bottom']				= 'Content Bottom';
$_['text_column_left']					= 'Column Left';
$_['text_column_right']					= 'Column Right';
$_['text_datatype_option_products']		= 'Products';
$_['text_datatype_option_categories']	= 'Categories';
$_['text_datatype_option_attributes']	= 'Attributes and Attribute Groups';
$_['text_datatype_option_coupons']		= 'Coupons';
$_['text_datatype_option_vouchers']		= 'Gift Vouchers';
$_['text_question_data']				= 'What kind of data do you wish to export?';
$_['text_question_store']				= 'Which store do you wish to export?';
$_['text_question_language']			= 'Which language do you wish to export?';
$_['text_note']							= 'Note:';
$_['text_supported_in_oc1541']			= 'If you receive an Error 500, please mind that your server may have a low memory limit.';
$_['text_learn_to_increase']			= 'Learn how to increase it.';
$_['text_feature_unsupported']			= 'This feature is supported only for OpenCart version {VERSION}';
$_['text_question_data_import']			= 'What kind of data do you wish to import?';
$_['text_question_store_import']		= 'In which store do you wish to import?';
$_['text_question_language_import']		= 'Which language do you wish to import?';
$_['text_question_file_import']			= 'Please select the .xlsx or .zip file you wish to import:';
$_['text_file_generating']				= 'Generating file. Please wait...';
$_['text_file_downloading']				= 'Downloading file...';
$_['text_import_done']					= 'Import finished. {COUNT} {TYPE} were imported.';
$_['text_preparing_data']				= 'Preparing data...';
$_['text_export_entries_number']		= 'Number of entries per exported part:<span class="help">Set this to a lower value if you experience memory issues. The lower the vlaue, the more exported files you will receive. Does not apply for Attributes.</span>';
$_['text_import_limit']					= 'Maximum entries to read on each step of the import:<span class="help">Default value is 100. Decrease it if you experience memory issues on Import. Does not apply for Attributes and Options.</span>';
$_['text_export_entries_number']		= '<span data-toggle="tooltip" title="Set this to a lower value if you experience memory issues. The lower the vlaue, the more exported files you will receive. Does not apply for Attributes.">Number of entries per exported part</span>';
$_['text_import_limit']					= '<span data-toggle="tooltip" title="Default value is 100. Decrease it if you experience memory issues on Import. Does not apply for Attributes and Options.">Maximum entries to read on each step of the import.</span>';
$_['text_question_product_type']		= 'How do you want your exported products to be structured?';
$_['text_question_delete_other']		= 'Delete the entries before doing the import? This will first remove all of the database entries of the selected type of import. The import will proceed afterwards. It is recommended to do a full database backup before using this option.';
$_['text_confirm_delete_other']			= 'This will delete all your entries before importing. It is advised to back up your database before the import. If you are sure you wish to continue, click OK.';
$_['text_question_product_type_quick']			= 'Basic export - Single-line export of products, excluding Attributes, Recurring Payment Profiles, Options, Discounts, Specials, Images, Reward Points and Designs.';
$_['text_question_product_type_full']			= 'Grouped export - Exported in a single sheet. Each product is represented in grouped rows.';
$_['text_question_product_type_bulk']			= 'Bulk export - Multi-sheet export, suitable for bulk editing purposes. <a href="https://isenselabs.com/posts/excelport-introducing-bulk-mode" target="_blank"><i class="fa fa-external-link"></i> More information here</a>';
$_['text_question_add_as_new']			= 'Import entries as new ones - This will disregard the ID field and will create new entries, without editing anything.';
$_['text_toggle_filter']				= 'Toggle Filter';
$_['text_conjunction']					= 'Filters Conjunction';
$_['help_conjunction']					= 'If you use &quot;AND&quot;, then ALL of the conditiones must be met. If you choose &quot;OR&quot;, then the entity will be listed if at least 1 condition is met.';
$_['text_the_value']					= 'the value';
$_['text_datatype_option_customers']	= 'Customers';
$_['text_datatype_option_customer_groups'] = 'Customer Groups';
$_['text_datatype_option_options']		= 'Options';
$_['text_datatype_option_orders']		= 'Orders';
$_['text_datatype_option_manufacturers'] = 'Brands';
$_['text_last_import']                  = 'Your last import was the following:<br /><strong>{FILE}</strong>';
$_['text_export_non_store_products'] = 'Export products not assigned to any store?';
$_['text_openstock_installed'] = 'Your ExcelPort supports OpenStock!';

$_['text_export_product_description_html'] = '<span data-toggle="tooltip" title="This option will affect the exporter for Products.">Select how you need ExcelPort to handle your product descriptions:</span>';
$_['option_encoded_html'] = 'Encoded HTML characters: &amp;lt;p&amp;gt;';
$_['option_standard_html'] = 'Standard HTML characters: &lt;p&gt;';
$_['option_no_html'] = 'Without HTML characters';

// Entry
$_['entry_code']						= 'ExcelPort status:<br /><span class="help">Enable or disable ExcelPort</span>';
$_['entry_layouts_active']				= 'Activated on:<br /><span class="help">Choose on which pages ExcelPort to be active</span>';

// Error
$_['error_permission']					= 'Warning: You do not have permission to modify module ExcelPort!';
$_['error_no_file']						= 'File was not uploaded.';

// Button
$_['button_export']						= 'Export Now';
$_['button_import']						= 'Import Data';
$_['button_add_condition']				= 'Add Filter';
$_['button_discard_condition']			= 'Discard Filter';

$_['excelport_unable_cache']			= 'Could not set cache storage method.';
$_['excelport_unable_upload']			= 'Temp file was not moved to the target folder.';
$_['excelport_invalid_file']			= 'File is invalid - it is either too large, or in a wrong format.';
$_['excelport_folder_not_string']		= 'The passed variable is not a string.';
$_['excelport_file_not_exists']			= 'The file you wish to import does not exist on the server.';
$_['excelport_export_limit_invalid'] 	= 'Invalid entry number per file. Please set it between 50 and 800.';
$_['excelport_invalid_import_file']		= 'The imported file does not exist in the file system!';
$_['excelport_unable_zip_file_open']	= 'Cannot open zip file. It is probably corrupt.';
$_['excelport_unable_zip_file_extract'] = 'Cannot extract the zip file.';
$_['excelport_unable_create_unzip_folder'] = 'Cannot create the unzip folder.';
$_['excelport_import_limit_invalid']	= 'Invalid entry import limit. Please set it between 10 and 800.';
$_['excelport_mode_unknown']			= 'The first row (table header) of the imported table is invalid. Please use fields for either Quick Mode or Full Mode. Refer to the ExcelPort documentation for more information.';
$_['excelport_sheet_unknown'] = 'The first sheet in the .XLSX must be called &quot;Products&quot;';

$_['excelport_openstock_failed'] = 'OpenStock for ExcelPort has not been applied. Please copy the file %s to %s.';
$_['excelport_openstock_uninstall_failed'] = 'OpenStock for ExcelPort cannot get uninstalled. Please remove the file %s.';

$_['import_success']					= 'SUCCESS: The products have been imported.';

$_['license_your_license'] = 'Your License';
$_['license_enter_code'] = 'Please enter your product purchase license code:';
$_['license_placeholder'] = 'License Code e.g. XXXXXX-XXXXXX-XXXXXX-XXXXXX-XXXXXX';
$_['license_activate'] = 'Activate License';
$_['license_get_code'] = 'Not having a code? Get it from here.';
$_['license_holder'] = 'License Holder';
$_['license_registered_domains'] = 'Registered Domains';
$_['license_expires'] = 'License Expires on';
$_['license_valid'] = 'VALID LICENSE';
$_['license_manage'] = 'Manage';
$_['license_get_support'] = 'Get Support';
$_['license_community'] = 'Community';
$_['license_community_info'] = 'Ask the community about your issue on the iSenseLabs forum.';
$_['license_forums'] = 'Browse forums';
$_['license_tickets'] = 'Tickets';
$_['license_tickets_info'] = 'Want to comminicate one-to-one with our tech people? Then open a support ticket.';
$_['license_tickets_open'] = 'Open a support ticket';
$_['license_presale'] = 'Pre-sale';
$_['license_presale_info'] = 'Have a brilliant idea for your webstore? Our team of top-notch developers can make it real.';
$_['license_presale_bump'] = 'Bump the sales';
$_['license_missing'] = 'You are running an unlicensed version of this module! <a href="javascript:void(0);" onclick="jq(\'.excelport_tab:eq(3)\').trigger(\'click\'); jq(\'.licenseCodeBox\').focus();">Click here to enter your license code</a> to ensure proper functioning, access to support and updates.';
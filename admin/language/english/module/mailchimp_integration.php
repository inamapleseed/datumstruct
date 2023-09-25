<?php
//==============================================================================
// MailChimp Integration v230.5
// 
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
// 
// All code within this file is copyright Clear Thinking, LLC.
// You may not copy or reuse code within this file without written permission.
//==============================================================================

$version = 'v230.5';

//------------------------------------------------------------------------------
// Heading
//------------------------------------------------------------------------------
$_['heading_title']						= 'MailChimp Integration';
$_['pro_heading_title']					= 'MailChimp Integration Pro';

//------------------------------------------------------------------------------
// Extension Settings
//------------------------------------------------------------------------------
$_['tab_extension_settings']			= 'Extension Settings';
$_['help_extension_settings']			= 'When enabled, MailChimp Integration will automatically sync customers between OpenCart and MailChimp when customers create or edit their account in the front-end, and administrators create, edit, or delete customers in the back-end.';
$_['heading_extension_settings']		= 'Extension Settings';

$_['entry_status']						= 'MailChimp Integration Status: <div class="help-text">Set the status for the extension as a whole.</div>';

$_['entry_testing_mode']				= 'Testing Mode: <div class="help-text">Enabling this will log errors and webhook calls to the System > Error Log. If you choose "Enabled with full logging" then all API requests and responses will also be logged to the error log.</div>';
$_['text_enabled_with_full_logging']	= 'Enabled with full logging';

$_['entry_apikey']						= 'API Key: <div class="help-text">You can find your API Key in MailChimp under:<br />(Your Account Name) > Account Settings > Extras > API Keys</div>';

$_['entry_double_optin']				= 'Double Opt-In Confirmation E-mails: <div class="help-text">Unless you collect confirmation information yourself, it is <strong>NOT</strong> recommended to disable this. <a target="_blank" href="http://kb.mailchimp.com/article/how-does-confirmed-optin-or-double-optin-work">Click here to read why</a>. Confirmation e-mails will be sent for customer-initiated changes, but will NEVER be sent for administrator-initiated changes.</div>';

$_['entry_webhooks']					= 'Webhooks: <div class="help-text">Select the type of actions that cause MailChimp to send information back to OpenCart. Note that Profile Updates can change the customer\'s log-in e-mail address, name, phone number, and default address, so use with caution.</div>';
$_['text_subscribes']					= 'Subscribes';
$_['text_unsubscribes']					= 'Unsubscribes';
$_['text_profile_updates']				= 'Profile/Email Updates';
$_['text_cleaned_addresses']			= 'Cleaned Addresses';

$_['entry_subscribed_group']			= '"Subscribed" Customer Group: <div class="help-text">If desired, select a customer group to which the customer is changed when subscribing to your OpenCart newsletter. This customer group change will occur BEFORE subscribing them to the appropriate List.</div>';
$_['entry_unsubscribed_group']			= '"Unsubscribed" Customer Group: <div class="help-text">If desired, select a customer group to which the customer is changed when unsubscribing from your OpenCart newsletter.</div>';
$_['text_no_change']					= '--- No Change ---';

$_['entry_manual_sync']					= 'Manually Sync Subscribers: <div class="help-text">You should only need to manually sync subscribers once, when you first install this extension. After that, all syncing should happen automatically in the background.<br /><br />If an e-mail exists in both OpenCart and MailChimp, the information associated with it in OpenCart will be used for the sync. Confirmation e-mails are NOT sent when manually syncing, so be sure to have approval from your customers to add them to your mailing list.<br /><br />Fill in the "Starting Customer ID" and "Ending Customer ID" fields to sync a partial list of your customers. The starting and ending ids are inclusive. Leave blank to sync all customers.</div>';
$_['text_starting_customer_id']			= 'Starting Customer ID:';
$_['text_ending_customer_id']			= 'Ending Customer ID:';
$_['button_sync_subscribers']			= 'Sync Subscribers';

$_['text_sync_error']					= 'Sync Error: The API Key and List ID fields must be filled in before syncing!';
$_['text_sync_note']					= 'Note: If you have a large database, this may take some time. Continue?';
$_['text_syncing']						= 'Syncing...';

//------------------------------------------------------------------------------
// Customer Creation Settings
//------------------------------------------------------------------------------
$_['heading_customer_creation_settings']= 'Customer Creation Settings';

$_['entry_autocreate']					= 'Auto-Create Customers: <div class="help-text">If set to "Yes" and an e-mail exists in MailChimp but not OpenCart, a new customer will be created for that e-mail, with a randomly generated password.</div>';
$_['text_yes_disabled']					= 'Yes, disabled by default';
$_['text_yes_enabled']					= 'Yes, enabled by default';

$_['entry_email_password']				= 'E-mail Customers Their Password: <div class="help-text">If "Auto-Create Customers" is enabled, choose whether to e-mail new customers their randomly generated password.</div>';

$_['entry_emailtext_subject']			= 'E-mail Subject: <div class="help-text">Set the subject of the e-mail sent to customers. Use [store] in place of the store name.</div>';
$_['entry_emailtext_body']				= 'E-mail Body: <div class="help-text">Set the body of the e-mail sent to customers. Use [store] in place of the store name, and [password] in place of the customer\'s new password. HTML is supported.</div>';

//------------------------------------------------------------------------------
// List Settings
//------------------------------------------------------------------------------
$_['tab_list_settings']					= 'List Settings';
$_['heading_list_settings']				= 'List Settings';

$_['entry_listid']						= 'MailChimp List: <div class="help-text">Select the MailChimp list used to sync with.</div>';
$_['text_enter_an_api_key']				= 'Enter an API Key and reload the page';

$_['pro_entry_listid']					= 'Default List: <div class="help-text">Select the default MailChimp list used, if the criteria for a list mapping is not met.</div>';

//------------------------------------------------------------------------------
// List Mapping
//------------------------------------------------------------------------------
$_['heading_list_mapping']				= 'List Mapping';
$_['help_list_mapping']					= 'For more advanced functionality in subscribing customers to lists based on address, currency, customer group, language, and/or store, upgrade to <a target="_blank" href="http://www.opencartx.com/mailchimp-integration-pro">MailChimp Integration Pro</a>.<br /><br />The Pro version also includes the ability to choose which customer fields are mapped to which Merge Tags, allows customers to choose and edit Interest Groups, and gives the option to display address fields in the module box.';

$_['pro_help_list_mapping']				= 'If desired, create a list mapping to subscribe customers to different MailChimp lists, based on their address, currency, customer group, language, or store. If there is no eligible list mapping, the default list will be used.';

$_['column_action']						= 'Action';
$_['column_list']						= 'List';
$_['column_rules']						= 'Rules';

$_['button_add_mapping']				= 'Add Mapping';

$_['button_add_rule']					= 'Add Rule';
$_['help_add_rule']						= 'All rules must be true for the mapping to activate. Rules of different types will be combined using AND logic, and rules of the same type using OR logic. For example, if you add these rules:<br /><br />&bull; Customer Group is Default<br />&bull; Customer Group is Wholesale<br />&bull; Store is ABC Store<br /><br />then the customer will be subscribed to the chosen list if they are viewing the ABC Store <b>AND</b> they are part of the Default <b>OR</b> Wholesale customer group.';

$_['text_choose_rule_type']				= '--- Choose rule type ---';
$_['help_rules']						= 'Choose a rule type from the list of options. Once you select a rule type, hover over the input field that is created for more information on that specific rule type.';

$_['text_of']							= 'of';
$_['text_is']							= 'is';
$_['text_is_not']						= 'is not';
$_['text_is_on_or_after']				= 'is after';
$_['text_is_on_or_before']				= 'is before';

$_['text_location_criteria']			= 'Location Criteria';
$_['text_city']							= 'City';
$_['text_geo_zone']						= 'Geo Zone';
$_['text_everywhere_else']				= 'Everywhere Else';
$_['text_postcode']						= 'Postcode';

$_['help_city']							= 'Enter an exact city name, like:<br /><br />New York<br /><br />or multiple city names separated by commas, such as:<br /><br />New York, New York City, London<br /><br />The city entered by the customer will be compared against these values (case-insensitively).';
$_['help_geo_zone']						= 'Select a geo zone, or select "Everywhere Else" to restrict the mapping to anywhere not in a geo zone.';
$_['help_postcode']						= 'Enter a single postcode or prefix (such as AB1) or a range (such as 91000-94499). Ranges are inclusive of the end values. Separate multiple postcodes using commas.';

$_['text_order_criteria']				= 'Order Criteria';
$_['text_currency']						= 'Currency';
$_['text_customer_group']				= 'Customer Group';
$_['text_guests']						= 'Guests';
$_['text_language']						= 'Language';
$_['text_store']						= 'Store';

$_['help_currency']						= 'Select a currency.';
$_['help_customer_group']				= 'Select a customer group, or select "Guests" to restrict the mapping to customers not logged in to an account.';
$_['help_language']						= 'Select a language.';
$_['help_store']						= 'Select a store from your multi-store installation.';

//------------------------------------------------------------------------------
// Merge Tags
//------------------------------------------------------------------------------
$_['tab_merge_tags']					= 'Merge Tags';
$_['heading_merge_tags']				= 'Merge Tags';

$_['help_merge_tags']					= 'You can find your MailChimp list\'s merge tags under Lists > Settings > List Fields and MERGE Tags. Your merge tags need to be set in MailChimp to the following values:
<ul style="margin: 10px 0">
	<li>"First Name" Merge Tag: <b>FNAME</b></li>
	<li>"Last Name" Merge Tag: <b>LNAME</b></li>
	<li>"Address" Merge Tag: <b>ADDRESS</b></li>
	<li>"Phone Number" Merge Tag: <b>PHONE</b></li>
</ul>
The customer\'s MailChimp language (the <b>MC_LANGUAGE</b> merge tag value) will be set using the customer\'s language selection in OpenCart. If this fails, it will attempt to use their browser language, and if that fails, it will use the store\'s default language.';

$_['text_leave_blank']					= '--- Leave Blank ---';
$_['pro_help_merge_tags']				= 'Select the field that will be filled in for each list\'s merge tag when subscribing the customer. To fill in the full customer address for an ADDRESS merge tag, select <code>address_id</code>.';

//------------------------------------------------------------------------------
// Interest Groups
//------------------------------------------------------------------------------
$_['tab_interest_groups']				= 'Interest Groups';
$_['heading_interest_groups']			= 'Interest Groups';

$_['help_interestgroups']				= 'To allow customers to choose and edit their Interest Groups through the module, upgrade to <a target="_blank" href="http://www.opencartx.com/mailchimp-integration-pro">MailChimp Integration Pro</a>.';
$_['pro_help_interestgroups']			= 'Interest Groups allow you to segment your customers based on the areas they select. You can set up Interest Groups for a list by going to your MailChimp account, navigating to a List, and then choosing Manage Subscribers > Groups.';

$_['entry_interest_groups']				= 'Enable Interest Groups: <div class="help-text">Choose whether to allow customers to choose and edit their Interest Groups. If enabled, Interest Groups will appear within the module box when customers subscribe. After they are subscribed, they can also change their Interest Groups through the module box.</div>';
$_['entry_display_routes']				= 'Display on Routes: <div class="help-text">To restrict the Interest Groups to display only on certain pages, enter the routes here. Separate multiple routes by commas, and use % for a wildcard. Leave this field blank to show Interest Groups everywhere the module is displayed.<br /><br />For example, to have Interest Groups only appear on the account pages, you would enter the route <code>account/%</code>. To have them appear only on the account newsletter page, you would enter <code>account/newsletter</code>.</div>';
$_['entry_moduletext_interestgroups']	= 'Interest Groups Message: <div class="help-text">Optionally enter a message to appear above the Interest Groups. HTML is supported.</div>';
$_['entry_moduletext_updatebutton']		= 'Update Button: <div class="help-text">Enter the text for the "Update" button, which will be shown if the customer is logged in, and is already subscribed to a list with Interest Groups.</div>';
$_['entry_moduletext_updated']			= 'Updated Text: <div class="help-text">Enter the text that is displayed when a customer\'s Interests are successfully updated. HTML is supported.</div>';

$_['help_interestgroup_text']			= 'HTML is supported in all fields. Hide a group or option by leaving its field blank.';

$_['entry_group_title']					= 'Group Title:';
$_['entry_option']						= 'Option:';

//------------------------------------------------------------------------------
// E-commerce
//------------------------------------------------------------------------------
$_['tab_ecommerce']						= 'E-commerce';
$_['heading_ecommerce']					= 'E-commerce';

$_['help_ecommerce_upgrade']			= 'To add E-commerce support and send order data to MailChimp, upgrade to <a target="_blank" href="http://www.opencartx.com/mailchimp-integration-pro">MailChimp Integration Pro</a>.';
$_['entry_ecommerce360']				= 'Enable E-commerce: <div class="help-text">If enabled, order data will be sent to MailChimp when a customer successfully places an order in your store.</div>';

$_['entry_sendcarts']					= 'Send Cart Data: <div class="help-text">If enabled, cart data will be sent to MailChimp when a customer adds or removes items from their cart, or starts an order but does not complete it. You can then use this data to set up Abandoned Cart automation workflows in MailChimp.<br /><br />Note: sending data when adding/removing items from the cart will only work for logged-in customers, since it requires an e-mail address for MailChimp. Sending cart data when abandoning an order will only work with the default checkout; if you\'re using a custom checkout, in order to integrate this functionality you will need to send the code snippet from the MailChimp Integration Pro instructions.txt file to your custom checkout developer.</div>';

$_['entry_ordertype']					= 'Orders to Send: <div class="help-text">Choose whether to send all orders, or only orders that come from newsletter readers. If you choose to only send newsletter orders, that means customers must have visited your store via a link within a newsletter, and then made an order in your store. The cookie that tracks this visit from a newsletter link will last as many days as set below.</div>';
$_['text_send_all_orders']				= 'Send All Orders';
$_['text_send_newsletter_orders']		= 'Send Newsletter Orders';

$_['entry_orderstatus']					= 'Triggering Order Status(es): <div class="help-text">Choose which order status(es) trigger the order data being sent to MailChimp.</div>';
$_['entry_deletestatus']				= 'Delete Order Status(es): <div class="help-text">Choose which order status(es) trigger the order data being deleted in MailChimp.<br /><br /><b>Note: deleting an order in OpenCart will always delete it in MailChimp.</b> You can turn off the "Enable Ecommerce" setting first if you don\'t want that happening.</div>';
$_['entry_cookietime']					= 'Cookie Time Limit: <div class="help-text">Set the number of days that the campaign_id and email_id cookies will be kept when the customer visits your store via a link within a newsletter.</div>';

$_['entry_past_orders_sync']			= 'Past Orders Sync: <div class="help-text">You should only need to manually sync once, when you first install this extension. After that, all syncing should happen automatically in the background. Manually synced orders will not be associated with any particular campaign in MailChimp, since that data is not stored in OpenCart.<br /><br />Fill in the "Starting Order ID" and "Ending Order ID" fields to sync a partial list of your orders. The starting and ending ids are inclusive. Leave blank to sync all orders.</div>';
$_['text_starting_order_id']			= 'Starting Order ID:';
$_['text_ending_order_id']				= 'Ending Order ID:';
$_['button_sync_orders']				= 'Sync Orders';

// Stores
$_['heading_stores']					= 'Stores';
$_['help_stores']						= 'Select which List to use for this Store (for E-commerce purposes). <b>Make sure this matches the list you are using for the store in the List Settings tab.</b>';

$_['help_link_tracking_box']			= '<b>Tip:</b> Don\'t forget to check the "E-commerce link tracking" checkbox when sending a newsletter! Otherwise the campaign_id and email_id won\'t be appended to links in the newsletter.';

//------------------------------------------------------------------------------
// Module Settings
//------------------------------------------------------------------------------
$_['tab_module_settings']				= 'Module Settings';
$_['help_module_settings']				= 'Select whether each field below is displayed in the module, and whether it is optional or required. If the customer is logged in, only the E-mail Address field will be shown. Other information will be pulled from their OpenCart account.';
$_['heading_module_settings']			= 'Module Settings';

$_['entry_modules_lists']				= 'List Options: <div class="help-text">If you want to give the customer a choice of which List(s) to sign up to, select them here. To assign the customer a List based on your List Mappings, leave all Lists unchecked. Check "Allow Multiple Selections" to let the customer choose multiple lists at once.<br /><br /><b>Note:</b> If Interest Groups are enabled, this setting will be ignored, and customers will not be given a choice of Lists in the module. The module is only capable of displaying one or the other at this time.</div>';
$_['text_allow_multiple_selections']	= 'Allow Multiple Selections';

$_['entry_modules_firstname']			= 'First Name Field:';
$_['entry_modules_lastname']			= 'Last Name Field:';
$_['entry_modules_telephone']			= 'Telephone Field:';
$_['entry_modules_address']				= 'Address Field:';
$_['entry_modules_city']				= 'City Field:';
$_['entry_modules_postcode']			= 'Postcode Field:';
$_['entry_modules_zone']				= 'State/Region Field:';
$_['entry_modules_country']				= 'Country Field:';

$_['text_hide']							= 'Hide';
$_['text_optional']						= 'Optional';
$_['text_required']						= 'Required';
$_['text_show']							= 'Show';

$_['entry_modules_redirect']			= 'Redirect URL: <div class="help-text">Optionally enter a URL to redirect the customer to after they are successfully subscribed. Leave blank to have them stay on the same page.</div>';
$_['entry_modules_popup']				= 'Display as Pop-up: <div class="help-text">If set to "Yes", you can paste a link somewhere in this format to trigger the pop-up:<br /><code>&lt;a href="javascript:showMailchimpPopup()"&gt;LINK TEXT&lt;/a&gt;</code></div>';

$_['text_yes_trigger_manually']			= 'Yes, trigger manually only';
$_['text_yes_trigger_automatically']	= 'Yes, trigger manually + automatically on first visit';

// Module Text
$_['heading_module_text']				= 'Module Text';

$_['entry_moduletext_heading']			= 'Module Heading: <div class="help-text">HTML is supported.</div>';
$_['entry_moduletext_top']				= 'Top Text: <div class="help-text">Optionally enter text to go at the top of the module. HTML is supported.</div>';
$_['entry_moduletext_list']				= 'List Text: <div class="help-texts">Fill this in if allowing customers to choose the List they subscribe to.</div>';
$_['entry_moduletext_button']			= 'Subscribe Button:';
$_['entry_moduletext_emptyfield']		= 'Empty Field Error:';
$_['entry_moduletext_invalidemail']		= 'Invalid E-mail Error:';
$_['entry_moduletext_success']			= 'Success Text:';
$_['entry_moduletext_error']			= 'General Error Text: <div class="help-text">Leave this field blank to display the error message passed back from MailChimp.</div>';
$_['entry_moduletext_subscribed']		= 'Already Subscribed Text: <div class="help-text">Enter the message displayed in the module when the customer is already subscribed. Use [email] in place of the customer\'s e-mail address. HTML is supported.</div>';

// Module Locations
$_['heading_module_locations']			= 'Module Locations';
$_['entry_module_locations']			= 'Module Locations:';
$_['help_module_locations']				= 'You can set your module locations in';

$_['column_status']						= 'Status';
$_['column_layout']						= 'Layout';
$_['column_position']					= 'Position';
$_['column_sort_order']					= 'Sort Order';

$_['text_content_top']					= 'Content Top';
$_['text_column_left']					= 'Column Left';
$_['text_column_right']					= 'Column Right';
$_['text_content_bottom']				= 'Content Bottom';

$_['button_add_module']					= 'Add Module';

//------------------------------------------------------------------------------
// Standard Text
//------------------------------------------------------------------------------
$_['copyright']							= '<hr /><div class="text-center" style="margin: 15px">' . $_['heading_title'] . ' (' . $version . ') &copy; <a target="_blank" href="http://www.getclearthinking.com">Clear Thinking, LLC</a></div>';

$_['standard_autosaving_enabled']		= 'Auto-Saving Enabled';
$_['standard_confirm']					= 'This operation cannot be undone. Continue?';
$_['standard_error']					= '<strong>Error:</strong> You do not have permission to modify ' . $_['heading_title'] . '!';
$_['standard_max_input_vars']			= '<strong>Warning:</strong> The number of settings is close to your <code>max_input_vars</code> server value. You should enable auto-saving to avoid losing any data.';
$_['standard_please_wait']				= 'Please wait...';
$_['standard_saved']					= 'Saved!';
$_['standard_saving']					= 'Saving...';
$_['standard_select']					= '--- Select ---';
$_['standard_success']					= 'Success!';
$_['standard_testing_mode']				= 'Your log is too large to open! Clear it first, then run your test again.';
$_['standard_vqmod']					= '<strong>Warning:</strong> This extension requires vQmod, which is not installed on your store. Please download it from <a target="_blank" href="http://www.vqmod.com">www.vqmod.com</a> and follow the installation instructions.';

$_['standard_module']					= 'Modules';
$_['standard_shipping']					= 'Shipping';
$_['standard_payment']					= 'Payments';
$_['standard_total']					= 'Order Totals';
$_['standard_feed']						= 'Feeds';
?>
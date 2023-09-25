<?php
// Heading
$_['heading_title']        = 'CyberSource SOP (Q)';

// Specific Entry
$_['entry_mid']            = 'Access Key:';
$_['entry_key']            = 'Secret Key:';
$_['entry_profile_id']     = 'Profile ID:';
$_['entry_orgid']          = 'MDD Org ID:';
$_['entry_merchid']        = 'MDD MID:';
$_['entry_txntype']        = 'Transaction Type:';

// Specific Tooltip
$_['tooltip_mid']	  	   = 'Get this from your merchant/gateway provider.';
$_['tooltip_key']	   	   = 'Get this from your merchant/gateway provider.';
$_['tooltip_profile_id']   = 'Get this from your merchant/gateway provider.';
$_['tooltip_orgid']   	   = 'Only used if provided by the merchant.';
$_['tooltip_merchid']      = 'Only used if provided by the merchant.';
$_['tooltip_txntype']      = 'Sale = process payment immediately. Auth = Hold payment until you manually accept from the Gateway account page.';

// Specific Errors
$_['error_fields']		   = 'mid,key,profile_id'; // do not change this for any language
$_['error_mid']      	   = 'Field Required!';
$_['error_key']       	   = 'Field Required!';
$_['error_profile_id']     = 'Field Required!';

// Specific Help
$_['help_orgid']           = 'Optional. Only use if supplied by merchant provider!';
$_['help_merchid']    	   = 'Optional. Only use if supplied by merchant provider!';

// Common Text
$_['text_edit']            = 'Edit Payment';
$_['text_payment']         = 'Payment';
$_['text_success']         = 'Success: You have modified the payment settings!';
$_['text_guest']           = 'Guest';
$_['text_sale']            = 'Sale';
$_['text_auth']      	   = 'Auth';

// Common Entry
$_['entry_title']        	= 'Title:';
$_['entry_status']         	= 'Status:';
$_['entry_test']       		= 'Testmode:';
$_['entry_sort_order']     	= 'Sort Order:';
$_['entry_order_status']   	= 'Success Order Status:';
$_['entry_geo_zone']       	= 'Geo Zone:';
$_['entry_tax_class']     	= 'Tax Class:';
$_['entry_debug']          	= 'Debug Logging:';
$_['entry_total']   	 	= 'Min Total:';
$_['entry_title']          	= 'Title:';
$_['entry_support']        	= 'Support Info:';
$_['entry_debug_file']      = 'Debug File:';
$_['entry_supported_currencies'] = 'Supported Currencies:';
$_['entry_default_currency'] = 'Default Currency:';

// Tab
$_['tab_support']          	= 'Support';
$_['tab_debug']          	= 'Debug ';

// Common Tooltip
$_['tooltip_title']        = 'The title shown during the checkout payment step';
$_['tooltip_status']       = 'Enable/Disable';
$_['tooltip_total']	       = 'The minimum total the cart must be to show this payment option. Recommend set to 0.01 or higher.';
$_['tooltip_geo_zone']     = 'Allowed Geo Zone';
$_['tooltip_order_status'] = 'The order status that is set upon successful payment';
$_['tooltip_sort_order']   = 'The sort order on the payment checkout step';
$_['tooltip_test']   	   = 'Use the Test server/mode';
$_['tooltip_tax_class']    = 'Which tax should be applied to the payment';
$_['tooltip_debug']		   = 'Logs messages between store and gateway for troubleshooting to the system/logs folder in FTP.';
$_['tooltip_debug_file']   = '';
$_['tooltip_supported_currencies'] = 'Choose which currencies are supported by your merchant account. If you are unsure, contact your merchant provider.';
$_['tooltip_default_currency'] = 'If customer uses a disallowed currency from the list above, it will be converted to this currency before sending to the payment gateway.';

// Common Help
$_['help_debug']	       = 'Log found at "system/logs/'.basename(__FILE__, '.php').'_debug.txt" (in ftp). <span style="color:red;">Please include this log when contacting the developer for help!</span>';

// Error
$_['error_permission']     = 'Warning: You do not have permission to modify this payment!';
?>

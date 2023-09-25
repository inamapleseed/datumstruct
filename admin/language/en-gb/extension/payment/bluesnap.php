<?php
// Heading
$_['heading_title']				= 'BlueSnap (Hosted Payment Fields)';

// Text
$_['text_welcome']				= "
This payment module integrates the BlueSnap hosted payment fields functionality to extend your opencart checkout process with a hosted credit card form. This will limit your PCI compliance burden to the simplest SAQ A level, because sensitive payment data will never hit your server. </p>

<p>Please note that you will need a BlueSnap account in order to generate a Hosted Payment Field token. If you don't have an account yet, you can sign up for a <a href='https://sandbox.bluesnap.com/jsp/new_developer_sandbox.jsp' target='_blank'>sandbox account here.</a></p>

<p>All the settings below can be found in the API Setting section of the bluesnap admin console.</p>
";
$_['text_verification_success']			= 'Successfully authenticated with Bluesnap %S server: 

====
* Sample Payment Field Token: %s 
* Token Expires: %s
====

You may go ahead and save your settings

';

$_['text_verification_failure']			= "Failed to authenticate with Bluesnap %s server. Error: 

=======
%s
=======

Check your settings and try again.
";
$_['text_payment']				= 'Payment';
$_['button_verify_settings']			= 'Verify Settings';
$_['text_success']				= 'Success: You have modified BlueSnap account details!';
$_['text_edit']                     		= 'Edit BlueSnap';
$_['text_bluesnap']				= '<a onclick="window.open(\'http://www.bluesnap.com\');"><img src="view/image/payment/bluesnap.jpg" alt="Bluesnap" title="Bluesnap" style="border: 1px solid #EEEEEE;" /></a>';

// Entry
$_['entry_mode']                            	= 'Mode';
$_['entry_username']              		= 'Username';
$_['entry_server_ip']				= 'Your Server IP';
$_['help_server_ip']				= 'Set in API Settings -> Authorized IPs';
$_['help_username']				= 'Located at API Settings -> Api Credentials -> Username';
$_['entry_password']                      	= 'Password';
$_['help_password']				= 'Located at API Settings -> Api Credentials -> Password';
$_['entry_debug_enabled']  			= 'Debug Enabled';
$_['entry_testmode_enabled']                    = 'Test Mode';
$_['text_mode_production']			= 'Production';
$_['text_mode_sandbox']                      	= 'Sandbox';
$_['entry_merchant']				= 'Merchant ID';
$_['entry_security']				= 'Security Code';
$_['entry_callback']				= 'Alert URL';
$_['entry_total']				= 'Total';
$_['help_total']				= 'The minimum the order must reach before this payment module is enabled';
$_['entry_order_status']			= 'Order Status';
$_['entry_geo_zone']				= 'Geo Zone';
$_['entry_status']				= 'Status';
$_['entry_sort_order']				= 'Sort Order';

$_['entry_description_prefix']                  = 'Soft Descriptor';
$_['help_description_prefix']                   = "Description of the transaction, which appears on the shopper's credit card statement, such as your store name, e.g. %s";
$_['error_description_prefix']			= "Soft Descriptor Required!";


$_['error_permission']				= 'Warning: You do not have permission to modify payment BlueSnap!';
$_['error_username']				= 'Username Required!';
$_['error_password']                            = 'Password Required!';
$_['error_debug_enabled']                     	= 'Please specify whether or not debuggin must be enabled';
$_['error_warning']				= 'Warning: Please check the form carefully for errors!';

$_['column_action']	= 'Action';
$_['column_order_id']	= 'Order ID';
$_['column_outcome']   	= 'Outcome';
$_['column_total']    	= 'Total';
$_['column_date_added']    = 'Date Added';
$_['column_remote_ip']    = 'Remote IP';

$_['entry_order_id']	= 'Order ID';
$_['entry_outcome']	= 'Outcome';
$_['entry_remote_ip']	= 'Remote IP';
$_['entry_date_added']	= 'Date Added';

$_['text_payment_info']	= 'Payment Audit History';
$_['text_audit_entry_title']	= 'Audit Entry #';
$_['text_result_code_success'] = "Success";
$_['text_result_code_failure'] = "Failure";
$_['button_view']	= 'View';
$_['text_no_results']	= 'No entries found!';

$_['modal_popup_title'] 	= 'Audit Entry : Bluesnap Audit ID ';
$_['button_modal_popup_close'] 	= 'Close';



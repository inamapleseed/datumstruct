<?php
/**
* Custom Payment Gateway for OpenCart v2
* Using Payment Gateway API v4.12
* For Credit Card Transactions
* Written 09/20/2014
* ©Merchant e-Solutions 2014
*
* @author nrichardson
* 
*/

// Heading
$_['heading_title']       = 'Merchant e-Solutions';

// Text 
$_['text_payment']        = 'Payment';
$_['text_success']        = 'MeS Plugin settings have been updated!';
$_['text_edit']           = 'Edit Merchant e-Solutions Settings';
$_['text_authorization']  = 'Pre-Authorization';
$_['text_sale']           = 'Sale';
$_['text_mes']     	 	  = '<a target="_BLANK" href="https://merchante-solutions.com"><img src="view/image/payment/mes.png" alt="Merchant e-Solutions" title="MeS" style="border: 1px solid #EEEEEE;" /></a>';
$_['text_pg']			  = 'Payment Gateway';
$_['text_ph']			  = 'PayHere';

// Entry
$_['entry_status']        = 'Status';
$_['entry_profile_id']    = 'MeS Profile ID';
$_['entry_profile_key']   = 'MeS Profile Key';
$_['entry_test']          = 'Test Mode';
$_['entry_auth']          = 'Transaction Type';
$_['entry_completed_status']        = 'Completed Status';
$_['entry_geo_zone']      = 'Geo Zone';
$_['entry_sort_order']    = 'Sort Order';
$_['entry_payment_mode']  = 'Payment Mode';
$_['entry_security_key']  = 'Security Key';

// Tab
$_['tab_general']         = 'General';

// Help
$_['help_profile']		  = 'Get your API keys from your MeS account details page.';
$_['help_test']           = 'Use the live or testing (sandbox) gateway server to process transactions?';
$_['help_auth']           = 'Use the live gateway server to process Pre-Authorizations or Sales Transactions?';
$_['help_mode']			  = 'Choose Payment Gateway to use the MeS Payment Gateway API via a regular credit card form displayed to your customers.<br />Choose PayHere to use the MeS PayHere API and redirect the customer to the MeS PayHere hosted page.<br />Note: You must have either Payment Gateway or PayHere API access on your account!';
$_['help_key']			  = 'Your Security Key is a unique PIN you set up when enrolling your merchant account in PayHere. Consult your account rep for this info.';

// Error
$_['error_permission']    = 'Warning: You do not have permission to modify payment MeS!';
$_['error_profile_id']    = 'MeS Profile ID is Required!'; 
$_['error_profile_key']   = 'MeS Profile Key is Required!';
$_['error_security_keyy'] = 'Security Key is Required!';

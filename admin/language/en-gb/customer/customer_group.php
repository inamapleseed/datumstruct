<?php
// Heading
$_['heading_title']     = 'Customer Groups';

// Text
$_['text_success']      = 'Success: You have modified customer groups!';
$_['text_list']         = 'Customer Group List';
$_['text_add']          = 'Add Customer Group';
$_['text_edit']         = 'Edit Customer Group';
$_['text_cron_note']    = 'To clear reward points, you are required to set CRON job URL: <br>' .HTTP_CATALOG . 'index.php?route=_cron/cron/clearCustomerReward<br>Frequency can be set as once per day<br>Please contact technical support if assistance is needed';
$_['text_not_same_dates'] = 'Note: Please do not use same date for different row of records.';

// Tab
$_['tab_reward']     = 'Reward';
$_['tab_discount']     = 'Discount';


// Column
$_['column_name']       = 'Customer Group';
$_['column_sort_order'] = 'Sort Order';
$_['column_action']     = 'Action';

// Entry
$_['entry_name']        = 'Customer Group';
$_['entry_description'] = 'Description';
$_['entry_approval']    = 'Approve New Customers';
$_['entry_sort_order']  = 'Sort Order';

$_['entry_earn'] = 'Point earned percentage ratio (%)';
$_['entry_spend'] = 'Multiplier Tier to Redeem';
$_['entry_amount'] = 'Points Conversion Amount';
$_['entry_start_date'] = 'Qualifying Period (Start)';
$_['entry_end_date'] = 'Qualifying Period (End)';
$_['entry_clear_date'] = 'Expiry Date';
$_['entry_important'] = 'Important: *';


// Help
$_['help_approval']     = 'Customers must be approved by an administrator before they can login.';

// Error
$_['error_permission']  = 'Warning: You do not have permission to modify customer groups!';
$_['error_name']        = 'Customer Group Name must be between 3 and 32 characters!';
$_['error_default']     = 'Warning: This customer group cannot be deleted as it is currently assigned as the default store customer group!';
$_['error_store']       = 'Warning: This customer group cannot be deleted as it is currently assigned to %s stores!';
$_['error_customer']    = 'Warning: This customer group cannot be deleted as it is currently assigned to %s customers!';


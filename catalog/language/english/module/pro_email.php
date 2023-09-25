<?php
// common
$_['text_hello'] = 'Dear customer,';
$_['text_hello_customer'] = 'Hi {firstname},';
$_['text_account_btn'] = 'My account';
$_['text_affiliate_btn'] = 'My affiliate account';
$_['text_goodbye'] = 'Best regards,<br/>{store_name} team';

// customer.register
$_['subject_customer.register'] = '{store_name} - Thank you for registering';
$_['text_customer_welcome'] = 'Welcome and thank you for registering at {store_name}!';
$_['text_account_created'] = 'Your account has now been created and you can log in by using your email address and password by visiting our website:';
$_['text_account_approval'] = 'Your account must be approved before you can login. Once approved you can log in by using your email address and password by visiting our website:';
$_['text_account_services'] = 'Upon logging in, you will be able to access other services including reviewing past orders, printing invoices and editing your account information.';
$_['text_login'] = 'Login: <b>{email}</b>';
$_['text_password'] = 'Password: <b>{password}</b>';

// customer.forgotten
$_['subject_customer.forgotten'] = '{store_name} - New Password';
// 2.2
$_['text_password_reset'] = 'A new password was requested for {store_name} customer account.';
$_['text_reset_link'] = 'To reset your password click on the button below:';
$_['text_reset_btn'] = 'Reset my password';
$_['text_ip'] = 'The IP used to make this request was: {ip}';
// 1.5 - 2.1
$_['text_password_lost'] = 'Forgot your password ? No problem here is a brand new one for you:';
$_['text_new_password'] = 'Password: <b>{password}</b>';

// customer.approve
$_['subject_customer.approve'] = '{store_name} - Your Account has been activated!';
$_['text_approve_welcome'] = 'Welcome and thank you for registering at {store_name}!';
$_['text_account_approved'] = 'Your account has now been created and you can log in by using your email address and password by visiting our website or at the following URL:';

// customer.deny affiliate.deny
$_['subject_affiliate.deny'] = '{store_name} - Your Affiliate Account has been denied!';
$_['subject_customer.deny'] = '{store_name} - Your Account has been denied!';
$_['text_account_denied']  = 'Unfortunately your request has been denied. For more information please contact the store owner.';

// customer.transaction
$_['subject_customer.credit'] = '{store_name} - Account Credit';
$_['text_credit_received'] = 'You have received {amount} credit!';
$_['text_credit_total'] = 'Your total amount of credit is now {total}.';
$_['text_credit_info'] = 'Your account credit will be automatically deducted from your next purchase.';

// customer.reward
$_['subject_customer.reward'] = '{store_name} - Reward Points';
$_['text_reward_received'] = 'You have received {amount} Reward Points!';
$_['text_reward_total'] = 'Your total number of reward points is now {total}.';

// customer.voucher
$_['subject_customer.voucher'] = 'You have been sent a gift voucher from {from}';
$_['text_voucher_greeting'] = 'Congratulations, You have received a Gift Certificate worth <b>{amount}</b>';
$_['text_voucher_from'] = 'This Gift Certificate has been sent to you by <b>{from}</b>';
$_['text_voucher_message'] = 'With a message saying:<br/>{message}';
$_['text_voucher_code'] = '<b>Redemption code:</b> <b>{code}</b>';
$_['text_voucher_redeem'] = 'To redeem this Gift Certificate, write down the redemption code then click on the the link below and purchase the product you wish to use this gift voucher on. You can enter the gift voucher code on the shopping cart page before you click checkout.';

// affiliate.forgotten
$_['subject_affiliate.forgotten'] = '{store_name} - New password';

// affiliate.register
$_['subject_affiliate.register'] = '{store_name} - Affiliate Program';
$_['text_affiliate_welcome'] = 'Thank you for joining the {store_name} Affiliate Program!';
$_['text_affiliate_services'] = 'Upon logging in, you will be able to generate tracking codes, track commission payments and edit your account information.';

// affiliate.approve
$_['subject_affiliate.approve'] = '{store_name} - Your Affiliate Account has been activated!';
$_['text_affiliate_approve_welcome'] = 'Welcome and thank you for registering at {store_name}!';
$_['text_affiliate_approved'] = 'Your affiliate account has now been created and you can log in by using your email address and password by visiting our website:';

// affiliate.transaction
$_['subject_affiliate.transaction'] = '{store_name} - Affiliate Commission';
$_['text_transaction_received'] = 'You have received {amount} commission!';
$_['text_transaction_total'] = 'Your total amount of commission is now {total}.';

// order.update
$_['subject_order.update'] = '{store_name} - Order Update {order_id}';
$_['text_update_status'] = 'Your order <b>#{order_id}</b> has been updated to the following status: <b>{order_status}</b>';
$_['text_update_comment'] = 'The comments for your order are:';

// order.return
$_['subject_order.return'] = '{store_name} - Return Update {return_id}';
$_['text_return_status'] = 'Your return <b>#{return_id}</b> has been updated to the following status: <b>{order_status}</b>';

// order.confirm
$_['subject_order.confirm'] = '{store_name} - Order {order_id}';
$_['text_order_confirm'] = 'Thank you for your interest in {store_name} products. Your order has been received and will be processed once payment has been confirmed.';
$_['text_order_link'] = 'To view your order click on the link below:';
$_['text_order_btn'] = 'My order';
$_['text_download_btn'] = 'My downloads';
$_['text_no_order_status'] = 'Status not defined';

// sale.contact
$_['subject_sale.contact'] = '';

// admin.information.contact
$_['subject_admin.information.contact'] = 'Enquiry - {enquiry_name}';
$_['text_new_enquiry'] = 'You received a new enquiry.';
$_['text_name'] = '<b>Name:</b> {enquiry_name}';
$_['text_mail'] = '<b>Email:</b> {enquiry_mail}';
$_['text_message'] = '<b>Message:</b><br/>{enquiry_message}';

// admin.order.confirm
$_['subject_admin.order.confirm'] = '{store_name} - Order {order_id}';
$_['text_order_received'] = 'You have received an order.';
$_['text_order_status'] = 'Order Status: <b>{order_status}</b>';

// admin.enquiry.confirm
$_['subject_admin.order.confirm'] = '{store_name} - Enquiry {enquiry_order_id}';
$_['text_enquiry_received'] = 'You have received an Enquiry.';
$_['text_enquiry_status'] = 'Enquiry Status: <b>{order_status}</b>';

// admin.customer.register
$_['subject_admin.customer.register'] = 'New customer';
$_['text_customer_signup'] = 'A new customer has signed up:';
$_['text_customer_info'] = '<table><tr><td style="width:43%">Web Site:</td><td><b>{store_url}</b></td></tr><tr><td>Customer Group:&nbsp;</td><td><b>{customer_group}</b></td></tr><tr><td>First Name:</td><td><b>{firstname}</b></td></tr><tr><td>Last Name:</td><td><b>{lastname}</b></td></tr><tr><td>E-Mail:</td><td><b>{email}</b></td></tr><tr><td>Telephone:</td><td><b>{telephone}</b></td></tr></table>';

// admin.affiliate.register
$_['subject_admin.affiliate.register'] = 'New affiliate';
$_['text_affiliate_signup'] = 'A new affiliate has signed up:';
$_['text_affiliate_info'] = '<table><tr><td style="width:43%">Store:</td><td><b>{store_url}</b></td></tr><tr><td>First Name:</td><td><b>{firstname}</b></td></tr><tr><td>Last Name:</td><td><b>{lastname}</b></td></tr><tr><td>Company:</td><td><b>{company}</b></td></tr><tr><td>Website:</td><td><b>{website}</b></td></tr><tr><td>E-Mail:</td><td><b>{email}</b></td></tr><tr><td>Telephone:</td><td><b>{telephone}</b></td></tr></table>';

# Modules #
// product advertise
$_['product_ad_latest'] = 'Check out our latest products';
$_['product_ad_featured'] = 'Check out our featured products';

// Invoice
$_['date_format'] = 'd/m/Y';
$_['direction'] = 'ltr';
$_['decimal_point'] = '.';
$_['thousand_point'] = ',';
$_['text_invoice'] = 'Invoice';
$_['text_proformat'] = 'Proforma invoice';
$_['text_packingslip'] = 'Packing slip';
$_['text_store_vat'] = 'VAT ID:';
$_['text_store_company'] = 'Company ID:';
$_['text_url'] = 'URL:';
$_['text_company_id'] = 'Company ID:';
$_['text_tax_id'] = 'VAT ID:';
$_['text_order_detail'] = 'Order Details';
$_['text_enquiry_detail'] = 'Enquiry Details';
$_['text_invoice_no'] = 'Invoice No.:';
$_['text_order_id'] = 'Order ID:';
$_['text_status'] = 'Status:';
$_['text_date_added'] = 'Date Added:';
$_['text_date_due'] = 'Due Date:';
$_['text_customer'] = 'Customer:';
$_['text_customer_id'] = 'Customer ID:';
$_['text_shipping_address'] = 'Shipping Address';
$_['text_shipping_method'] = 'Shipping Method:';
$_['text_payment_address'] = 'Payment Address';
$_['text_payment_method'] = 'Payment Method:';
$_['text_products'] = 'Products:';
$_['text_total'] = 'Total:';
$_['text_instruction'] = 'Instructions';
$_['text_customer_comment'] = 'Customer comment';
$_['text_email'] = 'Email:';
$_['text_telephone'] = 'Telephone:';
$_['text_fax'] = 'Fax:';

// Column
$_['column_image'] = '';
$_['column_product'] = 'Product Name';
$_['column_product_id'] = 'Product ID';
$_['column_model'] = 'Model';
$_['column_manufacturer'] = 'Manufacturer';
$_['column_description'] = 'Description';
$_['column_mpn'] = 'MPN';
$_['column_location'] = 'Location';
$_['column_sku'] = 'SKU';
$_['column_upc'] = 'UPC';
$_['column_expected'] = 'Back Ordered (Expected)';
$_['column_weight'] = 'Weight';
$_['column_quantity'] = 'Quantity';
$_['column_slip_qty'] = 'QTY Ordered';
$_['column_qty_check'] = 'QTY Shipped';
$_['column_price'] = 'Price ex. tax';
$_['column_price_tax'] = 'Price';
$_['column_tax'] = 'Tax';
$_['column_tax_total'] = 'Tax (total)';
$_['column_tax_rate'] = 'Tax rate';
$_['column_total'] = 'Total ex. tax';
$_['column_total_tax'] = 'Total';
?>
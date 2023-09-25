<?php
//-----------------------------------------
// Author: Qphoria@gmail.com
// Web: http://www.opencartguru.com/
//-----------------------------------------
class ControllerExtensionPaymentCybersourceSop extends Controller {

	public function index() {

		# Generic Init
		$extension_type 			= 'extension/payment';
		$classname 					= str_replace('vq2-' . basename(DIR_APPLICATION) . '_' . strtolower(get_parent_class($this)) . '_' . $extension_type . '_', '', basename(__FILE__, '.php'));
		$data['classname'] 			= $classname;
		$data 						= array_merge($data, $this->load->language($extension_type . '/' . $classname));

		# Error Check
		$data['error'] = (isset($this->session->data['error'])) ? $this->session->data['error'] : NULL;
		unset($this->session->data['error']);

		# Common fields
		$data['testmode'] 			= $this->config->get($classname . '_test');

		# Form Fields
		$data['action'] 			= 'index.php?route='.$extension_type.'/'.$classname.'/send';
		$data['form_method'] 		= 'post';
		$data['fields']   			= array();
		$data['button_continue']	= $this->language->get('button_continue');

		### START SPECIFIC DATA ###

		// Device Fingerprint javascript fields
		$data['orgid'] = trim($this->config->get($classname . '_orgid'));
		$data['dfid'] = session_id();
		$data['merchid'] = trim($this->config->get($classname . '_merchid'));

		# Data Fields array - Could be included from external file
		$card_types['visa'] 		= 'Visa';
		$card_types['mastercard'] 	= 'MasterCard';
		$card_types['amex'] 		= 'American Express';
		$card_types['discover'] 	= 'Discover';

		$data['fields'][] = array(
			'entry'			=> $this->language->get('entry_card_type'),
			'type'			=> 'select',
			'name'			=> 'card_type',
			'value'			=> '',
			'param'			=> 'style="width:200px;display:inline-block;"',
			'required'		=> '1',
			'options'		=> $card_types,
			'help'			=> '',
		);

		$data['fields'][] = array(
			'entry'			=> $this->language->get('entry_card_name'),
			'type'			=> 'text',
			'placeholder' 	=> 'First Last',
			'name'			=> 'card_name',
			'value'			=> '',
			'size'			=> '50',
			'param'			=> 'style="width:200px;"',
			'required'		=> '1',
			'validate'  	=> ''
		);

		$data['fields'][] = array(
			'entry'			=> $this->language->get('entry_card_num'),
			'type'			=> 'text',
			'placeholder' 	=> 'xxxx-xxxx-xxxx-xxxx',
			'name'			=> 'card_num',
			'value'			=> '',
			'size'			=> '50',
			'param'			=> 'style="width:200px;"',
			'required'		=> '1',
			'validate'  	=> 'creditcard'
		);

		$months = array();
		for($i=1;$i<=12;$i++) {
			$months[sprintf("%02d", $i)] = sprintf("%02d", $i);
		}

		$data['fields'][] = array(
			'entry'			=> $this->language->get('entry_card_exp'),
			'type'			=> 'select',
			'name'			=> 'card_mon',
			'value'			=> '',
			'param'			=> 'style="width:95px;display:inline-block;"',
			'required'		=> '1',
			'no_close'		=> '1',
			'options'		=> $months,
			'help'			=> '/',
		);

		$years = array();
		for($i=0;$i<=10;$i++) {
			$years[date('Y', strtotime('+'.$i.'year'))] = date('Y', strtotime('+'.$i.'year'));
		}

		$data['fields'][] = array(
			'entry'			=> '/',
			'type'			=> 'select',
			'name'			=> 'card_year',
			'value'			=> '',
			'param'			=> 'style="width:95px;display:inline-block;"',
			'required'		=> '1',
			'no_open'		=> '1',
			'options'		=> $years,
			'validate'		=> 'expiry'
		);

		$data['fields'][] = array(
			'entry'			=> $this->language->get('entry_card_cvv'),
			'type'			=> 'text',
			'placeholder' 	=> '3 to 4 digit code',
			'name'			=> 'card_cvv',
			'value'			=> '',
			'size'			=> '50',
			'param'			=> 'style="width:95px;"',
			'required'		=> '1',
		);
		### END SPECIFIC DATA ###

		# Compatibility
		if (version_compare(VERSION, '2.2', '>=')) { // v2.2.x Compatibility
			return $this->load->view($extension_type . '/'. $classname, $data);
		} elseif (version_compare(VERSION, '2.0', '>=')) { // v2.0.x Compatibility
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $extension_type . '/'. $classname . '.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/' . $extension_type . '/'. $classname . '.tpl', $data);
			} else {
				return $this->load->view('default/template/' . $extension_type . '/'. $classname . '.tpl', $data);
			}
		} elseif (version_compare(VERSION, '2.0', '<')) {  // 1.5.x Backwards Compatibility
			$this->data = array_merge($this->data, $data);
			$this->id 	= 'extension/payment';
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/' . $classname . '.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/payment/' . $classname . '.tpl';
			} else {
				$this->template = 'default/template/payment/' . $classname . '.tpl';
			}
        	$this->render();
		}
	}

	public function send() {

		# Generic Init
		$extension_type = 'extension/payment';
		$classname = str_replace('vq2-' . basename(DIR_APPLICATION) . '_' . strtolower(get_parent_class($this)) . '_' . $extension_type . '_', '', basename(__FILE__, '.php'));
		$data['classname'] = $classname;
		$data = array_merge($data, $this->load->language($extension_type . '/' . $classname));

		# Order Info
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		# Common URL Values
		$callbackurl 	= $this->url->link($extension_type . '/' . $classname . '/callback', '', 'SSL');
		$cancelurl 		= $this->url->link('checkout/checkout', '', 'SSL');
		$successurl 	= $this->url->link('checkout/success');
		$declineurl 	= $this->url->link('checkout/checkout', '', 'SSL');


		### START SPECIFIC DATA ###

		# Check for supported currency, otherwise convert
		$supported_currencies = explode(",", $this->config->get($classname . '_supported_currencies'));
		if (in_array($order_info['currency_code'], $supported_currencies)) {
			$currency_code = $order_info['currency_code'];
		} else {
			$currency_code = $this->config->get($classname . '_default_currency');
		}
		
		$amount = str_replace(array(','), '', $this->currency->format($order_info['total'], $currency_code, FALSE, FALSE));

		$json = array();

		# Card Check
		$errornumber = '';
		$errortext = '';
		if (!$this->checkCreditCard ($_POST['card_num'], $_POST['card_type'], $_POST['card_cvv'], $_POST['card_mon'], $_POST['card_year'], $errornumber, $errortext)) {
			$json['error'] = $errortext;
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			return;
		}

		$this->load->model('localisation/country');
		$store_country_info = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));
		$store_country_iso_3 = $store_country_info['iso_code_3'];

		$subDigits = 0; // 0 means all 4 or 2 means just the last 2

		### START SPECIFIC DATA ###
		
		// Card Type to Numeric Conversion
		$cardTypeNumber = array(
			'visa'		=> '001',
			'mastercard'=> '002',
			'amex'		=> '003',
			'discover'	=> '004'
		);
		
		$params = array();
		$params['access_key'] 			= trim($this->config->get($classname . '_mid'));
		$params['profile_id'] 			= trim($this->config->get($classname . '_profile_id'));
		$params['transaction_uuid'] 	= (uniqid() . time());
		$params['signed_field_names'] 	= 'access_key,profile_id,transaction_uuid,signed_field_names,unsigned_field_names,signed_date_time,locale,transaction_type,reference_number,amount,currency,override_custom_cancel_page,override_custom_receipt_page,payment_method,card_type,card_number,card_expiry_date,card_cvn,bill_to_forename,bill_to_surname,bill_to_email,bill_to_address_line1,bill_to_address_city,bill_to_address_state,bill_to_address_country,bill_to_address_postal_code,customer_ip_address,device_fingerprint_id,merchant_defined_data1,merchant_defined_data2,merchant_defined_data3,merchant_defined_data4,merchant_defined_data5,merchant_defined_data7,merchant_defined_data8,merchant_defined_data9,merchant_defined_data10,merchant_defined_data11,merchant_defined_data12,merchant_defined_data13,merchant_defined_data14,merchant_defined_data18,merchant_defined_data19,merchant_defined_data21,merchant_defined_data25';
		$params['unsigned_field_names'] = '';
		$params['signed_date_time']		= gmdate("Y-m-d\TH:i:s\Z", strtotime("0 hours"));
		$params['locale'] 				= 'en';
		$params['transaction_type'] 	= ($this->config->get($classname . '_txntype') ? 'sale' : 'authorization');
		$params['reference_number'] 	= $order_info['order_id'] . '_' . time();
		$params['amount'] 				= $amount;
		$params['currency'] 			= $currency_code;
		$params['override_custom_cancel_page'] = $cancelurl;
		$params['override_custom_receipt_page'] = $callbackurl;
		$params['payment_method'] 		= 'card';
		$params['card_type']	 		= $cardTypeNumber[$this->request->post['card_type']];
		$params['card_number'] 			= preg_replace('/[^0-9]/', '', $this->request->post['card_num']);;
		$params['card_expiry_date']		= ($this->request->post['card_mon'] .'-'. substr($this->request->post['card_year'], $subDigits));
		$params['card_cvn'] 			= preg_replace('/[^0-9]/', '', $this->request->post['card_cvv']);
		$params['bill_to_forename'] 	= $order_info['payment_firstname'];
 		$params['bill_to_surname'] 		= $order_info['payment_lastname'];
 		$params['bill_to_email'] 		= $order_info['email'];
 		$params['bill_to_address_line1'] = $order_info['payment_address_1'];
 		$params['bill_to_address_city'] = $order_info['payment_city'];
 		$params['bill_to_address_state'] = $order_info['payment_zone_code'];
 		$params['bill_to_address_country'] = $order_info['payment_iso_code_2'];
 		$params['bill_to_address_postal_code'] = $order_info['payment_postcode'];
		$params['customer_ip_address'] = $order_info['ip'];
		$params['device_fingerprint_id'] = session_id();
		$params['merchant_defined_data1'] = '0';
		$params['merchant_defined_data2'] = '1';
		$params['merchant_defined_data3'] = 'Web';
		$params['merchant_defined_data4'] = 'NO';
		$params['merchant_defined_data5'] = date('d-m-Y h:ma', strtotime('-1 year')); //'21-05-2014 03:26pm'
		$params['merchant_defined_data7'] = 'NO';
		$params['merchant_defined_data8'] = 'General';
		$params['merchant_defined_data9'] = 'General';
		$params['merchant_defined_data10'] = 'Standard';
		$params['merchant_defined_data11'] = 'Courier';
		$params['merchant_defined_data12'] = 'YES';
		$params['merchant_defined_data13'] = '1';
		$params['merchant_defined_data14'] = date('d-m-Y h:ma', strtotime('-1 year')); //'21-05-2014 03:26pm'
		$params['merchant_defined_data18'] = '0';
		$params['merchant_defined_data19'] = '0';
		$params['merchant_defined_data21'] = '1';
		$params['merchant_defined_data25'] = $store_country_iso_3; //'UAE

		require(DIR_SYSTEM . '../catalog/controller/extension/payment/' . $classname . '.class.php');
		if ($this->config->get($classname . '_debug')) {
 			$payclass = New $classname(DIR_LOGS);
 		} else {
 			$payclass = New $classname();
		}

		$params['signature'] = $payclass->sign($params, trim($this->config->get($classname . '_key')));

 		if ($this->config->get($classname . '_test')) {
 			$params['test'] = 'true';
		}

 		//$result = $payclass->sendPayment($params);
 		$result = $payclass->buildOutput($params);

		// Unset some params before logging:
		$params['card_number'] = 'xxxxxxxxxxxxxxxx';
		$params['card_expiry_date'] = 'xxxx';
		$params['card_cvn'] = 'xxx';
		if ($this->config->get($classname . '_debug')) { file_put_contents(DIR_LOGS . $classname . '_debug.txt', "Request: " . print_r($params,1) . "\r\n Response: " . print_r($result,1) . "\r\n"); }

		$json = array();

		$json['html'] = $result;
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
		//echo $result;
		//exit;
	}


	public function callback() {

		# Generic Init
		$extension_type 			= 'extension/payment';
		$classname 					= str_replace('vq2-' . basename(DIR_APPLICATION) . '_' . strtolower(get_parent_class($this)) . '_' . $extension_type . '_', '', basename(__FILE__, '.php'));
		$data['classname'] 			= $classname;
		$data 						= array_merge($data, $this->load->language($extension_type . '/' . $classname));

		// Debug
		if ($this->config->get($classname . '_debug')) { file_put_contents(DIR_LOGS . $classname . '_debug.txt', __FUNCTION__ . "\r\n$classname GET: " . print_r($_GET,1) . "\r\n" . "$classname POST: " . print_r($_POST,1) . "\r\n", FILE_APPEND); }

		$this->load->model('checkout/order');

		if (!empty($_REQUEST['req_reference_number'])) {
			$order_id = explode("_", $_REQUEST['req_reference_number']);
			$order_id = $order_id[0];
		} else {
			$order_id = 0;
		}

		$order_info = $this->model_checkout_order->getOrder($order_id);

		// If there is no order info then fail.
		if (!$order_info) {
			$this->session->data['error'] = $this->language->get('error_no_order');
			$this->fail();
		}
		
		$message = isset($_REQUEST['message']) ? $_REQUEST['message'] : '';

		// If we get a successful response back...
		if (isset($_REQUEST['decision'])) {
			switch ($_REQUEST['decision']) {
				case 'ACCEPT':
				if (version_compare(VERSION, '2.0', '>=')) { // v20x
					$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('config_order_status_id'), '', true);
					$this->model_checkout_order->addOrderHistory($order_id, $this->config->get($classname . '_order_status_id'), '', false);
				} else { //v15x
					$this->model_checkout_order->confirm($order_id, $this->config->get('config_order_status_id'), '', true);
					$this->model_checkout_order->update($order_id, $this->config->get($classname . '_order_status_id'), '', false);
				}
				$successurl = $this->url->link('checkout/success', '', 'SSL');
				// Mijo Support
				if (strpos(DIR_SYSTEM, 'mijo') !== false) {
					$successurl = str_replace('route', 'option=com_mijoshop&format=raw&tmpl=component&route', $successurl);
				}
				$this->response->redirect($successurl);
				
				case 'CANCEL':
					$this->session->data['error'] = $this->language->get('error_canceled');
					//$html  = '<html><head><base target="_top"><script language="Javascript">parent.location="'. ($store_url . 'index.php?route=checkout/cart') . '"</script>';
					//$html .= '</head><body><a href="'.$store_url.'index.php?route=checkout/cart">--></a></body></html>';
					//print $html;
					//exit;
					break;
				default:
					$this->session->data['error'] = $message . ": " . $_REQUEST['reason_code'];
			}
		} else {
			$this->session->data['error'] = $this->language->get('error_invalid');
		}
		//$this->log->write("$classname: ERROR for order id: $order_id  :: " . $this->session->data['error']);
        $this->fail();

		### END SPECIFIC DATA ###


	}

	private function fail($msg = false) {
		$store_url = ($this->config->get('config_ssl') ? (is_numeric($this->config->get('config_ssl'))) ? str_replace('http', 'https', $this->config->get('config_url')) : $this->config->get('config_ssl') : $this->config->get('config_url'));
		if (!$msg) { $msg = (!empty($this->session->data['error']) ? $this->session->data['error'] : 'Unknown Error'); }
		if (method_exists($this->document, 'addBreadcrumb')) { //1.4.x
			$this->redirect((isset($this->session->data['guest'])) ? ($store_url . 'index.php?route=checkout/guest_step_3') : ($store_url . 'index.php?route=checkout/confirm'));
		} else {
			echo '<html><head><script type="text/javascript">';
			echo 'alert("'.$msg.'");';
			echo 'parent.location="' . ($store_url  . 'index.php?route=checkout/checkout') . '";';
			echo '</script></head></html>';
		}
		exit;
	}

	private function checkCreditCard ($cardnumber, $cardtype, $cvv, $expMon, $expYear, &$errornumber, &$errortext) {

		// Define the cards we support. You may add additional card types.

		//  Name:      As in the selection box of the form - must be same as user's
		//  Length:    List of possible valid lengths of the card number for the card
		//  prefixes:  List of possible prefixes for the card
		//  cvv_length:  Valid cvv code length for the card
		//  luhn Boolean to say whether there is a check digit

		// Don't forget - all but the last array definition needs a comma separator!

		$cards = array(
			array ('name' => 'amex',
				  'length' => '15',
				  'prefixes' => '34,37',
				  'cvv_length' => '4',
				  'luhn' => true
				 ),
			array ('name' => 'diners',
				  'length' => '14,16',
				  'prefixes' => '36,38,54,55',
				  'cvv_length' => '3',
				  'luhn' => true
				 ),
			array ('name' => 'discover',
				  'length' => '16',
				  'prefixes' => '6011,622,64,65',
				  'cvv_length' => '3',
				  'luhn' => true
				 ),
			array ('name' => 'jcb',
				  'length' => '16',
				  'prefixes' => '35',
				  'cvv_length' => '3',
				  'luhn' => true
				 ),
			array ('name' => 'maestro',
				  'length' => '12,13,14,15,16,18,19',
				  'prefixes' => '5018,5020,5038,6304,6759,6761,6762,6763',
				  'cvv_length' => '3',
				  'luhn' => true
				 ),
			array ('name' => 'mastercard',
				  'length' => '16',
				  'prefixes' => '51,52,53,54,55',
				  'cvv_length' => '3',
				  'luhn' => true
				 ),
			array ('name' => 'solo',
				  'length' => '16,18,19',
				  'prefixes' => '6334,6767',
				  'cvv_length' => '3',
				  'luhn' => true
				 ),
			array ('name' => 'switch',
				  'length' => '16,18,19',
				  'prefixes' => '4903,4905,4911,4936,564182,633110,6333,6759',
				  'cvv_length' => '3',
				  'luhn' => true
				 ),
			array ('name' => 'visa',
				  'length' => '16',
				  'prefixes' => '4',
				  'cvv_length' => '3',
				  'luhn' => true
				 ),
			array ('name' => 'visa_electron',
				  'length' => '16',
				  'prefixes' => '417500,4917,4913,4508,4844',
				  'cvv_length' => '3',
				  'luhn' => true
				 ),
			array ('name' => 'laser',
				  'length' => '16,17,18,19',
				  'prefixes' => '6304,6706,6771,6709',
				  'cvv_length' => '3',
				  'luhn' => true
				 )
		);


		$ccErrorNo = 0;
		$ccErrors[0] = $this->language->get('error_card_type');
		$ccErrors[1] = $this->language->get('error_card_num');
		$ccErrors[2] = $this->language->get('error_card_cvv');
		$ccErrors[3] = $this->language->get('error_card_exp');

		// Establish card type
		$cardType = -1;
		for ($i=0; $i<sizeof($cards); $i++) {

			// See if it is this card (ignoring the case of the string)
			if (strtolower($cardtype) == strtolower($cards[$i]['name'])) {
				$cardType = $i;
				break;
			}
		}

		// If card type not found, report an error
		if ($cardType == -1) {
			$errornumber = 0;
			$errortext = $ccErrors[$errornumber];
			return false;
		}

		// Ensure that the user has provided a credit card number
		if (strlen($cardnumber) == 0)  {
			$errornumber = 1;
			$errortext = $ccErrors[$errornumber];
			return false;
		}

		// Remove any spaces from the credit card number
		$cardNo = str_replace (array(' ', '-'), '', $cardnumber);

		// Check that the number is numeric and of the right sort of length.
		if (!preg_match("/^[0-9]{13,19}$/", $cardNo))  {
			$errornumber = 1;
			$errortext = $ccErrors[$errornumber];
			return false;
		}

		// Remove any spaces or non-numerics from the expiry date fields
		$expMon = preg_replace('/[^0-9]/', '', $expMon);
		$expYear = preg_replace('/[^0-9]/', '', $expYear);

		// Check expiry length
		if (strlen($expMon) != 2 || strlen($expYear) != 4) {
			$errornumber = 3;
			$errortext = $ccErrors[$errornumber];
			return false;
		}

		// Check the expiry date
		/* Get timestamp of midnight on day after expiration month. */
		$exp_ts = mktime(0, 0, 0, $expMon + 1, 1, $expYear);

		$cur_ts = time();
		/* Don't validate for dates more than 10 years in future. */
		$max_ts = $cur_ts + (10 * 365 * 24 * 60 * 60);

		if ($exp_ts < $cur_ts || $exp_ts > $max_ts) {
			$errornumber = 3;
			$errortext = $ccErrors[$errornumber];
			return false;
		}

		// Now check the modulus 10 check digit - if required
		if ($cards[$cardType]['luhn']) {
			$checksum = 0;                                  // running checksum total
			$mychar = "";                                   // next char to process
			$j = 1;                                         // takes value of 1 or 2

			// Process each digit one by one starting at the right
			for ($i = strlen($cardNo) - 1; $i >= 0; $i--) {

				// Extract the next digit and multiply by 1 or 2 on alternative digits.
				$calc = $cardNo{$i} * $j;

				// If the result is in two digits add 1 to the checksum total
				if ($calc > 9) {
					$checksum = $checksum + 1;
					$calc = $calc - 10;
				}

				// Add the units element to the checksum total
				$checksum = $checksum + $calc;

				// Switch the value of j
				if ($j ==1) {$j = 2;} else {$j = 1;};
			}

			// All done - if checksum is divisible by 10, it is a valid modulus 10.
			// If not, report an error.
			if ($checksum % 10 != 0) {
				$errornumber = 1;
				$errortext = $ccErrors[$errornumber];
				return false;
			}
		}

		// The following are the card-specific checks we undertake.

		// Load an array with the valid prefixes for this card
		$prefix = explode(',', $cards[$cardType]['prefixes']);

		// Now see if any of them match what we have in the card number
		$PrefixValid = false;
		for ($i=0; $i<sizeof($prefix); $i++) {
			$exp = '/^' . $prefix[$i] . '/';
			if (preg_match($exp,$cardNo)) {
				$PrefixValid = true;
				break;
			}
		}

		// If it isn't a valid prefix there's no point at looking at the length
		if (!$PrefixValid) {
			$errornumber = 1;
			$errortext = $ccErrors[$errornumber];
			return false;
		}

		// See if the length is valid for this card
		$LengthValid = false;
		$lengths = explode(',', $cards[$cardType]['length']);
		for ($j=0; $j<sizeof($lengths); $j++) {
			if (strlen($cardNo) == $lengths[$j]) {
				$LengthValid = true;
				break;
			}
		}

		// See if all is OK by seeing if the length was valid.
		if (!$LengthValid) {
			$errornumber = 1;
			$errortext = $ccErrors[$errornumber];
			return false;
		};

		$cvv_length = $cards[$cardType]['cvv_length'];
		if (strlen($cvv) != $cvv_length) {
			$errornumber = 2;
			$errortext = $ccErrors[$errornumber];
			return false;
		}

		// The credit card is in the required format.
		return true;
	}
}
?>
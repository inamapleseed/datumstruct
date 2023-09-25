<?php
final class Bluesnap {
	const URL_SANDBOX = "https://sandbox.bluesnap.com";
	const URL_PRODUCTION = "https://ws.bluesnap.com";

	const API_PATH_GET_PAYMENT_FIELD_TOKEN = "/services/2/payment-fields-tokens";
	const API_PATH_TRANSACTIONS = "/services/2/transactions";
	
	// MAX TOKEN TTL for Bluesnap is currently 20 minutes. Set it to 15 for safety sake
	const PAYMENT_FIELD_TOKEN_TTL = "15 minutes";
	

	private $registry;
	private $config;
	private $db;
	private $session;
	private $customer;
	private $log;

	private $username;
	private $mode;
	private $password; 
	private $sandbox_mode_enabled;
	private $debug_enabled;
	private $description_prefix;
	private $uuid;

	public function install() {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "bluesnap_audit_trail` (
				`bluesnap_audit_id` int(11) NOT NULL AUTO_INCREMENT,
				`customer_id` int(11) not null,
				`order_id` int(11) not null,
				`hosted_payment_field_id` varchar(1000) not null,
				`fraud_session_id` varchar(100) not null,
				`amount` float not null,
				`currency` varchar(3) not null, 
				`recurring_transaction` varchar(100) not null,
				`description` varchar(100) not null,
				`cardholder_firstname` varchar(100) not null,
				`cardholder_lastname` varchar(100) not null,
				`card_type` varchar(100) not null,
				`card_last_4_digits` varchar(100) not null,
				`card_expdate` varchar(100) not null,
				`curl_request` text not null,
				`curl_reply` text not null,
				`http_status_code` int(11) not null,
				`server_ip` varchar(100),
				`remote_ip` varchar(100),
				`php_server_var` text,
				`php_session_var` text,
				`php_request_var` text,
				`result_code` int(11),
				`result_msg_code` varchar(100),
				`result_msg` text,
				`date_added` datetime,	
				PRIMARY KEY (`bluesnap_audit_id`)
			) ENGINE=InnoDB;
		");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "bluesnap_audit_trail_hosted_payment_fields` (
				`bluesnap_hosted_fields_audit_id` int(11) NOT NULL AUTO_INCREMENT,
				`order_id` int(11) not null, 
				`hosted_payment_field_id` varchar(1000), 
				`expiry_date_time` varchar(1000),
				`curl_request` text not null,
                                `curl_reply` text not null,
                                `http_status_code` int(11) not null,
                                `server_ip` varchar(100),
                                `remote_ip` varchar(100),
                                `php_server_var` text,
                                `php_session_var` text,
                                `php_request_var` text,
                                `result_code` int(11),
                                `result_msg_code` varchar(100),
                                `result_msg` text,
                                `date_added` datetime,  
				 PRIMARY KEY (`bluesnap_hosted_fields_audit_id`)
			) ENGINE=InnoDB;
		");
	}

	public function __construct($registry) {
		$this->registry = $registry;
		$this->config = $registry->get('config');
		$this->customer = $registry->get('customer');
		$this->session = $registry->get('session');
		$this->db = $registry->get('db');
		$this->log = $registry->get('log');
		$this->username = $this->config->get("bluesnap_username");
		$this->password = $this->config->get("bluesnap_password");
		$this->mode = $this->config->get("bluesnap_mode");
		$this->sandbox_mode_enabled = $this->mode == "sandbox" ? 1 : 0;
		$this->debug_enabled = $this->config->get("bluesnap_debug_enabled");
		$this->description_prefix = $this->config->get("bluesnap_description_prefix");
		$this->uuid = uniqid("BS", false) . uniqid("",false);
                if (strlen($this->uuid) > 30)
                        $this->uuid = substr($this->uuid,0,30);
	}

	protected function debug($method, $statement) {
		if ($this->debug_enabled == 1)
		{
			$this->log->write($method . $statement);
		}
	}

	protected function audit_temp_exit($method, $url, $statement) {
		$this->log->write($method . "[AUDIT_TEMP_EXIT] [URL=$url]" . $statement);
	}
	
	protected function audit_reentry($method, $statement) {
                $this->log->write($method . "[AUDIT_REENTRY] " . $statement);
        }

	protected function audit_error($method, $statement, $errors) {
                $this->log->write($method . "[AUDIT_ERROR] " . $statement. ": " . print_r($errors, true));
        }

	public function set_description_prefix($a_description_prefix) {
		$this->description_prefix = $a_description_prefix;
	}
	public function get_description_prefix() {
		return $this->description_prefix;
	}
	
        public function set_debug_enabled($a_debug_enabled)  {
                $this->debug_enabled = $a_debug_enabled;
        }
        public function get_debug_enabled() {
                return $this->debug_enabled;
        }

	public function set_username($a_username)  {
		$this->username = $a_username;
	} 
	public function get_username() {
		return $this->username;
	}


	public function set_password($a_password)  {
                $this->password = $a_password;
        }
	public function get_password() { 
		return $this->password;
	}

        public function set_sandbox_mode_enabled($a_sandbox_mode_enabled) {
               	$this->sandbox_mode_enabled  = $a_sandbox_mode_enabled;
        }

	public function get_sandbox_mode_enabled() {
		return $this->sandbox_mode_enabled;
	}

	public function get_url() {
		if ($this->sandbox_mode_enabled == 1) {
			return self::URL_SANDBOX;
		} else {
			return self::URL_PRODUCTION;
		}
	}

	public function generate_fraud_session_id() {
		return $this->uuid;
	}
	
	protected function do_request($api_path, $expected_http_status_code, $payload = array()) {
		$p = "do_request(" . $this->uuid . "): ";
		$url = $this->get_url() . $api_path;
                $username = $this->get_username();
                $password = $this->get_password();
                $ch = curl_init($url);
                $curl_opts = array(
                        CURLOPT_HEADER => true,
                        CURLOPT_VERBOSE => $this->debug_enabled,
                        CURLOPT_POST => true,
                        CURLOPT_FOLLOWLOCATION => false,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                        CURLOPT_USERPWD => $this->username . ":" . $this->password,
                        CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json'
                        ),
                );
		if (sizeof($payload) > 0) {
			$curl_opts[CURLOPT_POSTFIELDS] = json_encode($payload);
		}
                curl_setopt_array($ch, $curl_opts);
                $this->audit_temp_exit($p, $url, "[CURL_CONFIG: " . print_r($curl_opts, true). "]");    
                $response = curl_exec($ch);
		$headers = "";
		$payload = "";
		$http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);	
		$result_code = 0;
		list($headers, $payload) = explode("\r\n\r\n", $response, 2);
		if($response === FALSE || $http_status_code != $expected_http_status_code){
			$result_code = 1;
                        $curl_errors = curl_error($ch);
			if ($response === FALSE)
				$error_message = "No response was returned";
			else
				$error_message = "Unexpected HTTP Status Code ($http_status_code) received. Expected ($expected_http_status_code)";
                        $this->audit_error($p, "An unexpected error occurred: $error_message", array("CURL_ERRORS" => $curl_errors, "CURL_RESPONSE" => $response));
                } else {
                        $this->audit_reentry($p, "[headers=$headers] [body=$payload]");
		}
		curl_close($ch);
		return array(
			'result_code'	=> $result_code, 
			'headers' 	=> $headers, 
			'payload' 	=> $payload,
			'curl_request'	=> print_r($curl_opts, true),
			'curl_reply'	=> $response,
			'http_status_code'	=> $http_status_code
		);
	}

	public function auth_capture($order_info, $order_total, $hosted_payments_field, $fraud_session_id, $credit_card_info) {
		// print_r($order_info);
		$order_total = round($order_info['total'] * $order_info['currency_value'],4);
		$pf_token = $hosted_payments_field['TOKEN'];
		$pf_token_expiry = $hosted_payments_field['EXPIRY_DATE_TIME'];
		$result_code = 0;
		$result_msg_code = 'OK';
		$result_msg = '';
		$recurring_transaction = "ECOMMERCE";
		$description = $this->db->escape($this->description_prefix); // . " #" . $order_info['order_id']);
		$cardHolderInfo = array(
			'firstName'     => $credit_card_info['cardholderFirstName'],
                        'lastName'      => $credit_card_info['cardholderLastName'],
                       	'email'         => $order_info['email'],
                        'country'       => strtolower($order_info['payment_iso_code_2']),
                        'address'       => isset($order_info['payment_address_1']) ? $order_info['payment_address_1'] : '',
                        'address2'      => isset($order_info['payment_address_2']) ? $order_info['payment_address_2'] : '',
                        'city'          => isset($order_info['payment_city']) ? $order_info['payment_city'] : '',
			'zip'		=> isset($order_info['payment_postcode']) ? $order_info['payment_postcode'] : '',
			'phone'		=> isset($order_info['telephone']) ? strtoupper($order_info['telephone']) : '' ,
			'merchant-shopper-id' => $order_info['customer_id']
		); 
		
		if (strlen($cardHolderInfo['email']) > 100) {
			$cardHolderInfo['email'] = substr($cardHolderInfo['email'],0,100);
		} else if (strlen($cardHolderInfo['email']) < 3) {
			unset($cardHolderInfo['email']);	
		} 
	
		if (strlen($cardHolderInfo['address']) > 100) {
			$cardHolderInfo['address'] = substr($cardHolderInfo['address'], 0, 100);
		} else if (strlen($cardHolderInfo['address']) == 0) { 
			unset($cardHolderInfo['address']);	
		}

		if (strlen($cardHolderInfo['address2']) > 42) { 
			$cardHolderInfo['address2'] = substr($cardHolderInfo['address2'], 0, 42);
		} else if (strlen($cardHolderInfo['address2']) < 2) { 
			unset($cardHolderInfo['address2']);
		}

		if (strlen($cardHolderInfo['city']) > 42) {
                        $cardHolderInfo['city'] = substr($cardHolderInfo['city'], 0, 42);
                } else if (strlen($cardHolderInfo['city']) < 2) {
                        unset($cardHolderInfo['city']);
                }

		if (strlen($cardHolderInfo['phone']) < 2) { 
			unset($cardHolderInfo['phone']);
		} else {
			if (strlen($cardHolderInfo['phone']) > 36) { 
				$cardHolderInfo['phone'] = substr($cardHolderInfo['phone'],0, 36);
			}
			$allowed_chars = str_split('0123456789+.ABCDEFGHIJKLMNOPQRSTUVWXYZ*#/\\()-');
			$phone = str_split($cardHolderInfo['phone']);
			for ($i = sizeof($phone) - 1; $i >= 0; $i--) {
				if (!in_array($phone[$i], $allowed_chars)) {
					unset($phone[$i]);
				}
			}
			$cardHolderInfo['phone'] = implode('', $phone);
		}

		$request = array (
			'amount'			=> $order_total,
			'recurringTransaction' 		=> $recurring_transaction,
			'merchantTransactionId' 	=> $order_info['order_id'],
			'softDescriptor' 		=> $description,
			'cardHolderInfo' => $cardHolderInfo,
			'currency' => $order_info['currency_code'],
			'cardTransactionType' => "AUTH_CAPTURE",
			'pfToken' => $pf_token,
			'transactionFraudInfo' => array (
				'fraudSessionId' => $fraud_session_id,
			),
		);
		//print_r($request);
		//die();
		$result = $this->do_request(self::API_PATH_TRANSACTIONS, 200, $request);
		$result_code = $result['result_code'];
		$result_msg_code = 'OK';
		$result_msg = "";
		if ($result_code != 0) {
			$payload = json_decode($result['payload']);
			$result_msg_code = "BP-" . $payload->message[0]->code . ":" . $payload->message[0]->errorName;
			$result_msg = $payload->message[0]->description;
		}
		$ret = array(
			'result_code' => $result_code,
			'result_msg_code' => $result_msg_code,
			'result_msg' => $result_msg
		);
		
		$order_id = $this->db->escape($order_info['order_id']);
		$hosted_payment_field_id = $this->db->escape($pf_token);
		$fraud_session_id = $this->db->escape($fraud_session_id);
		$amount = $this->db->escape($order_total);
		$currency = $this->db->escape($order_info['currency_code']);
		$cardholder_firstname = $this->db->escape($credit_card_info['cardholderFirstName']);
		$cardholder_lastname = $this->db->escape($credit_card_info['cardholderLastName']);
		$card_type = $this->db->escape($credit_card_info['ccType']);
		$card_last_4_digits = $this->db->escape($credit_card_info['last4Digits']);
		$card_expdate = $this->db->escape($credit_card_info['expiryDate']);
		$curl_request = $this->db->escape($result['curl_request']);
		$curl_reply = $this->db->escape($result['curl_reply']);
		$result_msg_code = $this->db->escape($result_msg_code);
		$result_msg = $this->db->escape($result_msg);
		$server_ip = $this->db->escape($_SERVER['SERVER_NAME'] . "/" . $_SERVER['SERVER_ADDR']);
		$remote_ip = $this->db->escape($_SERVER['REMOTE_ADDR']);
		$php_server_var = $this->db->escape(print_r($_SERVER,true));
		$php_request_var = $this->db->escape(print_r($_REQUEST,true));
		$php_session_var = $this->db->escape(print_r($this->session,true));
		$http_status_code = $this->db->escape($result['http_status_code']);	
		$date_added = date('Y-m-d H:i:s');
		$sql = ("
			insert into `" . DB_PREFIX . "bluesnap_audit_trail` (
				`order_id`, `hosted_payment_field_id`, `fraud_session_id`, `amount`, `currency`,
				`recurring_transaction`, `description`, `cardholder_firstname`, `cardholder_lastname`,
				`card_type`, `card_last_4_digits`, `card_expdate`, `curl_request`, `curl_reply`, 
				`http_status_code`, `server_ip`, `remote_ip`, `php_server_var`, `php_session_var`, `php_request_var`,
				`result_code`, `result_msg_code`, `result_msg`, `date_added`
			) values (
				'$order_id', '$hosted_payment_field_id', '$fraud_session_id', '$amount', '$currency',
                                '$recurring_transaction', '$description', '$cardholder_firstname', '$cardholder_lastname',
                                '$card_type', '$card_last_4_digits', '$card_expdate', '$curl_request', '$curl_reply', 
                                '$http_status_code', '$server_ip', '$remote_ip', '$php_server_var', '$php_session_var', '$php_request_var',
                                '$result_code', '$result_msg_code', '$result_msg', '$date_added'
			)
		");
		$this->db->query($sql);
		return $ret;
	}

       public function get_payment_field_token($order_id = 0) {
                $p = "get_payment_field_token(" . $this->uuid . "): ";
		$result = $this->do_request(self::API_PATH_GET_PAYMENT_FIELD_TOKEN, 201);
		$result_code = $result['result_code'];
                $result_msg_code = 'OK';
                $result_msg = "";
                if ($result_code != 0) {
                        $payload = json_decode($result['payload']);
                        $result_msg_code = "BP-" . $payload->message[0]->code . ":" . $payload->message[0]->errorName;
                        $result_msg = $payload->message[0]->description;
                }

		$response = $result['payload'];
		$headers = $result['headers'];
                $headers = explode("\n", $headers);
		$payment_field_token = null;
		$expiry_date_time = null;
                foreach($headers as $header) {
                       	if (stripos($header, 'Location:') !== false) {
                        	$header = explode('/',$header);
                                $payment_field_token = trim($header[sizeof($header) - 1]);
				$expiry_date_time = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." +" . self::PAYMENT_FIELD_TOKEN_TTL));
                       	}
                }
		$curl_request = $this->db->escape($result['curl_request']);
                $curl_reply = $this->db->escape($result['curl_reply']);
                $result_msg_code = $this->db->escape($result_msg_code);
                $result_msg = $this->db->escape($result_msg);
                $server_ip = $this->db->escape($_SERVER['SERVER_NAME'] . "/" . $_SERVER['SERVER_ADDR']);
                $remote_ip = $this->db->escape($_SERVER['REMOTE_ADDR']);
                $php_server_var = $this->db->escape(print_r($_SERVER,true));
                $php_request_var = $this->db->escape(print_r($_REQUEST,true));
                $php_session_var = $this->db->escape(print_r($this->session,true));
                $http_status_code = $this->db->escape($result['http_status_code']);
                $date_added = date('Y-m-d H:i:s');
	
		$sql = "
 			insert into `" . DB_PREFIX . "bluesnap_audit_trail_hosted_payment_fields` (
                                `order_id`, `hosted_payment_field_id`, `expiry_date_time`, 
                                `curl_request`, `curl_reply`, 
                                `http_status_code`, `server_ip`, `remote_ip`, `php_server_var`, `php_session_var`, `php_request_var`,
                                `result_code`, `result_msg_code`, `result_msg`, `date_added`
                        ) values (
                                '$order_id', '$payment_field_token', '$expiry_date_time', 
                                '$curl_request', '$curl_reply', 
                                '$http_status_code', '$server_ip', '$remote_ip', '$php_server_var', '$php_session_var', '$php_request_var',
                                '$result_code', '$result_msg_code', '$result_msg', '$date_added'
                        )
		";
		$this->db->query($sql);
                if ($payment_field_token == null) {
                        throw new Exception($p . "Could not retrieve payment field token");
                }
                $this->debug($p,"Got payment field_token [$payment_field_token]");
                return array('TOKEN' => $payment_field_token, 'EXPIRY_DATE_TIME' => $expiry_date_time);
        }
}

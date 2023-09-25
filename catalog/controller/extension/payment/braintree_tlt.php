<?php
class ControllerExtensionPaymentBraintreeTlt extends Controller {
	public function index() {
		$this->load->language('extension/payment/braintree_tlt');

		$data['text_credit_card'] = $this->language->get('text_credit_card');
		$data['text_wait'] = $this->language->get('text_wait');
		$data['text_gateway_loading'] = $this->language->get('text_gateway_loading');
		$data['text_help'] = $this->language->get('text_help');

		$data['button_confirm'] = $this->language->get('button_confirm');
		
		$debugmode = $this->config->get('braintree_tlt_debug');

        if (is_file(DIR_SYSTEM . '../../vendor/autoload.php')) {
            $data['error_library'] = '';
        } elseif (file_exists(DIR_SYSTEM . '/braintree/lib/Braintree.php')) {
			require_once(DIR_SYSTEM . '/braintree/lib/Braintree.php');
			$data['error_library'] = '';
		} else {
			$this->log->write('BRAINTREE: Cannot load library.');
			$data['error_library'] = $this->language->get('error_library');

			return $this->load->view('extension/payment/braintree_tlt', $data);
		}

        Braintree_Configuration::environment($this->config->get('braintree_tlt_mode'));
        Braintree_Configuration::merchantId($this->config->get('braintree_tlt_merchant'));
        Braintree_Configuration::publicKey($this->config->get('braintree_tlt_public_key'));
        Braintree_Configuration::privateKey($this->config->get('braintree_tlt_private_key'));

        if ($this->config->get('braintree_tlt_private_tls12')) {
            Braintree_Configuration::sslVersion(6);
        }

        try {
			$data['clientToken'] = Braintree_ClientToken::generate();
		} catch (Exception $e) {
            $this->log->write('BRAINTREE: Cannot generate client token.');
			
			if ($debugmode) {
				$excmessage = 'Code: ' . $e->getCode() . ' ' . $e->getMessage() . ' File: ' . $e->getFile() . ' Line: ' . $e->getLine();
				$debuginfo = $e->getTraceAsString();
				$this->log->write('Error! ' . $excmessage);
				$this->log->write('Info: ' . $debuginfo);
			}

			$data['clientToken'] = '';
			$data['error_library'] = $this->language->get('error_connection');
			
			return $this->load->view('extension/payment/braintree_tlt', $data);
        }
		
		$this->load->model('checkout/order');
		$this->load->model('extension/payment/braintree_tlt');

		$data['text_testmode'] = $this->language->get('text_testmode');

		if ($this->config->get('braintree_tlt_mode') == 'sandbox') {
			$data['testmode'] = true;
		} else {
			$data['testmode'] = false;
		}

		return $this->load->view('extension/payment/braintree_tlt', $data);
	}

	public function send() {
        if (!is_file(DIR_SYSTEM . '../../vendor/autoload.php')) {
            require_once(DIR_SYSTEM . '/braintree/lib/Braintree.php');
        }

        Braintree_Configuration::environment($this->config->get('braintree_tlt_mode'));
        Braintree_Configuration::merchantId($this->config->get('braintree_tlt_merchant'));
        Braintree_Configuration::publicKey($this->config->get('braintree_tlt_public_key'));
        Braintree_Configuration::privateKey($this->config->get('braintree_tlt_private_key'));

        if ($this->config->get('braintree_tlt_private_tls12')) {
            Braintree_Configuration::sslVersion(6);
        }
		
		$debugmode = $this->config->get('braintree_tlt_debug');

		if ($this->config->get('braintree_tlt_mode') == 'sandbox') {
			$braintreemode = 'BRAINTREE Sandbox: ';
		} else {
			$braintreemode = 'BRAINTREE: ';
		}
		
		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$default_currency = $this->config->get('config_currency');
		
		if ($this->config->get('braintree_tlt_use_default') || ($order_info['currency_code'] == $default_currency)) {
			$merchantAccountId = $this->config->get('braintree_tlt_default_account');	
					
        	$amount = $this->currency->format($order_info['total'], $default_currency, '', false);
			
			if ($order_info['currency_code'] != $default_currency) {
				$this->log->write($braintreemode . 'Warning! Order currency differs from the Merchant Account currency. The card will be charged for ' . $amount . ' ' . $default_currency);
			} elseif ($debugmode) {
				$this->log->write($braintreemode . 'The card will be charged for ' . $amount . ' ' . $default_currency);
			}
		} else {
			$merchantAccountArray = $this->config->get('braintree_tlt_merchant_account');
			
			if (isset($merchantAccountArray[$order_info['currency_id']])) {
				$merchantAccountId = $merchantAccountArray[$order_info['currency_id']]['code'];
				
				if ($merchantAccountId) {
					$amount = $this->currency->format($order_info['total'], $order_info['currency_code'], '', false);
					if ($debugmode) {
						$this->log->write($braintreemode . 'The card will be charged for ' . $amount . ' ' . $order_info['currency_code']);
					}
				} else {
					$merchantAccountId = $this->config->get('braintree_tlt_default_account');	
					
					$amount = $this->currency->format($order_info['total'], $default_currency, '', false);
					
					$this->log->write($braintreemode . 'Warning! Merchant Account is not set for the order currency ('. $order_info['currency_code'] .'). Default merchant account (' . $default_currency .') will be used. The card will be charged for ' . $amount . ' ' . $default_currency);
				}
			} else {
				$merchantAccountId = $this->config->get('braintree_tlt_default_account');	
				
				$amount = $this->currency->format($order_info['total'], $default_currency, '', false);
				
				$this->log->write($braintreemode . 'Warning! Merchant Account is not set for the order currency ('. $order_info['currency_code'] .'). Default merchant account (' . $default_currency .') will be used. The card will be charged for ' . $amount . ' ' . $default_currency);
			}
		}
		
		if ($debugmode) {
			$this->log->write('Merchant Account: ' . $merchantAccountId);
		}
		
		if ($this->config->get('braintree_tlt_method') == 'charge') {
			$submitForSettlement = 'true';
		} else {
			$submitForSettlement = 'false';
		}

        $nonce = $_POST["payment_method_nonce"];

		$json = array();
        $result = null;
		
        try {
			$result = Braintree_Transaction::sale(array(
				'amount' => $amount,
				'paymentMethodNonce' => $nonce,
				'merchantAccountId' => $merchantAccountId,
				'orderId' => $this->session->data['order_id'],
				'options' => array('submitForSettlement' => $submitForSettlement),
				'customer' => array(
					'firstName' => html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8'),
					'lastName' => html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8'),
					'email' => $order_info['email']
				)
			));
		        
			if ($result->success) {
				if ($debugmode) {
					$this->log->write($braintreemode . $result);
				}
				
				$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('braintree_tlt_order_status_id'), $result->transaction->status, false);
				$json['success'] = $this->url->link('checkout/success');
			} elseif ($result->transaction) {
				$this->log->write($braintreemode . 'Error! ' . $result->transaction->processorResponseCode . '. ' . $result->transaction->processorResponseText);
				$this->load->language('extension/payment/braintree_tlt');
				if (in_array($result->transaction->processorResponseCode, array('2000', '2001', '2002', '2003', '2038', '2046'))) {
					$json['error'] = '<i class="fa fa-exclamation-circle"></i> ' . $this->language->get('error_bank');
					$json['redirect'] = $this->url->link('checkout/checkout', '', true);
				} elseif ($result->transaction->processorResponseCode == '3000') {
					$json['error'] = '<i class="fa fa-exclamation-circle"></i> ' . $this->language->get('error_connection');
					$json['redirect'] = $this->url->link('checkout/checkout', '', true);
				} else {
					$json['error'] = '<i class="fa fa-exclamation-circle"></i> ' . $this->language->get('error_transaction');
				}
			} else {
				$this->log->write($braintreemode . sizeof($result->errors) . ' total error(s)');
				foreach($result->errors->deepAll() as $error) {
					$this->log->write($braintreemode . 'Error! ' . $error->code . ". " . $error->message);
				}
				$this->load->language('extension/payment/braintree_tlt');
				$json['error'] = '<i class="fa fa-exclamation-circle"></i> ' . $this->language->get('error_transaction');
			}
        } catch (Exception $e) {
			$this->load->language('extension/payment/braintree_tlt');
			$excmessage = 'Code ' . $e->getCode() . ' ' . $e->getMessage() . ' File ' . $e->getFile() . ' Line ' . $e->getLine();
			$debuginfo = $e->getTraceAsString();
			$this->log->write($braintreemode . 'Error! ' . $excmessage);
			
			if ($debugmode) {
				$this->log->write('Debug Info: ' . $debuginfo);
			}
			
			$json['error'] = '<i class="fa fa-exclamation-circle"></i> ' . $this->language->get('error_connection');
        }

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
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

class ControllerPaymentMes extends Controller {
	public function index() {
    	$this->language->load('payment/mes');
		$data['testmode'] = $this->config->get('mes_test');
		$data['mes_mode'] = $this->config->get('mes_mode');
		$data['text_testmode'] = $this->language->get('text_testmode');
		$data['text_wait'] = $this->language->get('text_wait');
		$data['text_ph'] = $this->language->get('text_ph');
		if ($data['mes_mode'] == 'pg') {
			$data['text_credit_card'] = $this->language->get('text_credit_card');
			$data['entry_cc_number'] = $this->language->get('entry_cc_number');
			$data['entry_cc_expire_date'] = $this->language->get('entry_cc_expire_date');
			$data['entry_cc_cvv2'] = $this->language->get('entry_cc_cvv2');
			$data['months'] = array();
			for ($i = 1; $i <= 12; $i++) {
				$data['months'][] = array(
					'text'  => strftime('%B', mktime(0, 0, 0, $i, 1, 2000)),
					'value' => sprintf('%02d', $i)
				);
			}
			$today = getdate();
			$data['year_expire'] = array();
			for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
				$data['year_expire'][] = array(
					'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
					'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i))
				);
			}
		} else {
			$data['button_continue'] = $this->language->get('button_continue');
			$data['button_continue_action'] = $this->url->link('payment/mes/checkout');
		}

		$this->template = $this->config->get('config_template') . '/template/payment/mes.tpl';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/mes.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/mes.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/mes.tpl', $data);
		}
	}

	public function send() {
		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$data['custom'] = $this->session->data['order_id'];

		if( !isset($this->request->post['cc_number']) || preg_replace("/[^0-9]/", "", $this->request->post['cc_number']) == "" ) {
			$json['error'] = "Card number is required.";
			$this->response->setOutput(json_encode($json));
		} else if( !isset($this->request->post['cc_cvv2']) || preg_replace("/[^0-9]/", "", $this->request->post['cc_cvv2']) == "" ) {
			$json['error'] = "Card Security Code is required.";
			$this->response->setOutput(json_encode($json));
		} else {
			// Authentication
			$data['profile_key'] = $this->config->get('mes_profile_key');
			$data['profile_id'] = $this->config->get('mes_profile_id');
			// Customer Data
			$data['cardholder_first_name'] = html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8');
			$data['cardholder_last_name'] = html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
			$data['cardholder_street_address'] = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');
			$data['cardholder_zip'] = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
			$data['country_code'] = $order_info['payment_iso_code_2'];
			$data['cardholder_email'] = html_entity_decode($order_info['email'], ENT_QUOTES, 'UTF-8');
			$data['cardholder_phone'] = html_entity_decode($order_info['telephone'], ENT_QUOTES, 'UTF-8');
			$data['account_name'] = html_entity_decode($order_info['shipping_firstname'], ENT_QUOTES, 'UTF-8');
			$data['account_email'] = html_entity_decode($order_info['email'], ENT_QUOTES, 'UTF-8');
			$data['ip_address'] = html_entity_decode($order_info['ip'], ENT_QUOTES, 'UTF-8');
			$data['browser_language'] = html_entity_decode($order_info['language_code'], ENT_QUOTES, 'UTF-8');
			// Shipping Data
			$data['ship_to_first_name'] = html_entity_decode($order_info['shipping_firstname'], ENT_QUOTES, 'UTF-8');
			$data['ship_to_last_name'] = html_entity_decode($order_info['shipping_lastname'], ENT_QUOTES, 'UTF-8');
			$data['ship_to_address'] = html_entity_decode($order_info['shipping_address_1'], ENT_QUOTES, 'UTF-8');
			$data['ship_to_zip'] = html_entity_decode($order_info['shipping_postcode'], ENT_QUOTES, 'UTF-8');
			$data['dest_country_code'] = $order_info['shipping_iso_code_2'];
			// Order Data
			if (!$this->config->get('mes_transaction')) {
				// Pre-Auth transaction type
				$payment_type = 'P';	
			} else {
				// Sale transaction type
				$payment_type = 'D';
			}
			$data['transaction_type'] = $payment_type;
			$data['invoice_number'] = $order_info['order_id'];
			$data['transaction_amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], 1.00000, FALSE);
			$data['currency_code'] = $order_info['currency_code'];
			// Payment Data
			$data['card_number'] = preg_replace("/[^0-9]/", "", $this->request->post['cc_number']);
			$data['card_exp_date'] = urlencode($this->request->post['cc_expire_date_month'] . $this->request->post['cc_expire_date_year']);
			$data['cvv2'] = preg_replace("/[^0-9]/", "", $this->request->post['cc_cvv2']);
			if (!$this->config->get('mes_test')) {
				// Production api endpoint
				$api_endpoint = 'https://api.merchante-solutions.com/mes-api/tridentApi';
			} else {
				// Test api endpoint
				$api_endpoint = 'https://cert.merchante-solutions.com/mes-api/tridentApi';
			}

			$curl = curl_init($api_endpoint);
			curl_setopt_array($curl, array(
			    CURLOPT_RETURNTRANSFER => 1,
			    CURLOPT_FRESH_CONNECT => 1,
				CURLOPT_FORBID_REUSE => 1,
				CURLOPT_HEADER => 0,
			    CURLOPT_POST => 1,
			    CURLOPT_POSTFIELDS => http_build_query($data)
			));

			$response = curl_exec($curl);

			$json = array();
			if (!$response) {
				$this->log->write('MeS PG :: CURL failed ' . curl_error($curl) . '(' . curl_errno($curl) . ')');
				$json['error'] = 'Authorization attempt failed.<br />Details: '.curl_error($curl) . '(' . curl_errno($curl) . ')';
				$this->response->setOutput(json_encode($json));
			} else {
				$response_data = array();
				parse_str($response, $response_data);

				if($response_data['error_code'] == '000') {
					$message = '';
					if(isset($response_data['avs_result']))
						$message .= 'AVS Result: ' . $response_data['avs_result'] . "<br />";
					if(isset($response_data['cvv2_result']))
						$message .= 'Cvv Result: ' . $response_data['cvv2_result'] . "<br />";
						$message .= 'Transaction ID: ' . $response_data['transaction_id'] . "<br />";
					if(isset($response_data['auth_code']))
						$message .= 'Approval Code: ' . $response_data['auth_code'] . "<br />";
						$message .= 'Gateway Error Code: ' . $response_data['error_code'] . "<br />";
						$message .= 'Gateway Text Response: ' . $response_data['auth_response_text'] . "<br />";
						$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('mes_completed_status_id'), $message, TRUE);
						$json['success'] = $this->url->link('checkout/success');
						$this->response->setOutput(json_encode($json));
				} else {
					$json['error'] =  $response_data['error_code'] . ' - ';
					$json['error'] .= '<br />' . 'Transaction Declined. Please provide a valid credit card.';
					$this->response->setOutput(json_encode($json));
				}
			}
			curl_close($curl);
		}
	}
	public function checkout() {
		if (!$this->cart->hasProducts() || !$this->cart->hasStock()) {
			$this->response->redirect($this->url->link('checkout/cart'));
		}
		$this->load->model('checkout/order');
    	$this->language->load('payment/mes');

		$this->document->setTitle($this->language->get('text_confirm_title'));

		$data['heading_title'] = $this->language->get('text_confirm_title');
		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home'),
			'text' => 'Home'
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('checkout/cart'),
			'text' => 'Cart'
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('payment/mes/checkout'),
			'text' => $this->language->get('text_confirm_title')
		);


		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		if(null !== $this->config->get('mes_security_key')) {
			$tran_key = md5($this->config->get('mes_profile_key').$this->config->get('mes_security_key').$this->currency->format($order_info['total'], $order_info['currency_code'], false, false));
			$data['transaction_key'] = $tran_key;
		}
		if ($this->config->get('mes_test')) {
			$data['use_simulator'] = 'Y';
		}
		$data['action'] = 'https://www.merchante-solutions.com/jsp/tpg/secure_checkout.jsp';
		$data['profile_id'] = $this->config->get('mes_profile_id');
		$data['invoice_number'] = $order_info['order_id'];
		$data['transaction_amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], 1.00000, FALSE);
		$data['use_merch_receipt'] = 'Y';
		$data['cardholder_street_address'] = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');
		$data['cardholder_zip'] = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
		$data['return_url'] = $this->url->link('payment/mes/checkoutsuccess');
		$data['cancel_url'] = $this->url->link('checkout/checkout');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/mes_confirm.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/mes_confirm.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/payment/mes_confirm.tpl', $data));
		}
	}
	public function checkoutsuccess() {
		if ( isset( $this->request->post['invoice_number'] ) ) {	
			$this->load->model('checkout/order');

			$order_id  = $this->request->post['invoice_number'];

			$message = '';
			$message .= 'Transaction ID: ' . $this->request->post['tran_id'] . "<br />";
			$message .= 'Gateway Error Code: ' . $this->request->post['resp_code'] . "<br />";
			$message .= 'Gateway Text Response: ' . $this->request->post['resp_text'] . "<br />";
			$message .= 'Approval Code: ' . $this->request->post['auth_code'] . "<br />";

			$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('mes_completed_status_id'), $message, TRUE);
			$this->response->redirect($this->url->link('checkout/success'));
		}
	}
}
?>
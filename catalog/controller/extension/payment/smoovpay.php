<?php
class ControllerExtensionPaymentSmoovPay extends Controller {
	public function index() {
		$this->language->load('extension/payment/smoovpay');

		$data['text_testmode'] = $this->language->get('text_testmode');		
		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['testmode'] = $this->config->get('smoovpay_test');

		if (!$this->config->get('smoovpay_test')) {
			$data['action'] = 'https://secure.smoovpay.com/access';
		} else {
			$data['action'] = 'https://sandbox.smoovpay.com/access';
		}

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		if ($order_info) {
			$currencies = array(
				'SGD',
				'USD'
			);

			if (!in_array(strtoupper($order_info['currency_code']), $currencies)) {
				if ($this->config->get('smoovpay_convert') == 'sgd') {
					$order_info['currency_code'] = 'SGD';
				} else {
					$order_info['currency_code'] = 'USD';
				}
				
				$order_info['total'] = $this->currency->format($order_info['total'], $order_info['currency_code'], false, false);
			}
		
			$data['transaction'] = $this->config->get('smoovpay_transaction');
			$data['merchant'] = $this->config->get('smoovpay_email');
			$data['ref_id'] = $this->session->data['order_id'];
			$data['currency_code'] = $order_info['currency_code'];
			$data['total'] = $order_info['total'];
			$data['success'] = $this->url->link('checkout/success');
			$data['cancel'] = $this->url->link('checkout/checkout', '', 'SSL');
			$data['callback'] = $this->url->link('extension/payment/smoovpay/callback', '', 'SSL');
			
			$data['signature'] = sha1($this->config->get('smoovpay_secret') . $data['merchant'] . $data['transaction'] . $data['ref_id'] . $data['total'] . $data['currency_code']);

			$data['products'] = array();

			foreach ($this->cart->getProducts() as $product) {
				$data['products'][] = array(
					'name'     => $product['name'],
					'model'    => $product['model'],
					'quantity' => $product['quantity'],
					'price'    => $this->currency->format($product['price'], $data['currency_code'], false, false)
				);
			}

			$data['discount'] = 0;

			$total = $this->currency->format($data['total'] - $this->cart->getSubTotal(), $data['currency_code'], false, false);

			if ($total > 0) {
				$data['products'][] = array(
					'name'     => $this->language->get('text_total'),
					'model'    => '',
					'quantity' => 1,
					'price'    => $total
				);	
			} else {
				$data['discount'] -= $total;
			}

			return $this->load->view('extension/payment/smoovpay', $data);
		}
	}

	public function callback() {
		if (isset($this->request->post['ref_id'])) {
			$order_id = $this->request->post['ref_id'];
		} else {
			$order_id = 0;
		}

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($order_id);

		if ($order_info) {
			if ($this->config->get('smoovpay_debug')) {
				$this->log->write('SMOOVPAY :: CALLBACK DATA: ' . print_r($this->request->post, true));
			}
			
			$merchant = $this->request->post['merchant'];
			$ref_id = $this->request->post['ref_id']; 
			$reference_code = $this->request->post['reference_code']; 
			$response_code = $this->request->post['response_code']; 
			$currency = $this->request->post['currency'];
			$total_amount = $this->request->post['total_amount'];
			$signature = $this->request->post['signature'];
			
			$calculated_signature = sha1($this->config->get('smoovpay_secret') . $merchant . $ref_id . $reference_code . $response_code . $currency . $total_amount);
			
			$order_status_id = $this->config->get('config_order_status_id');
			
			if ($calculated_signature != $signature) {
				$this->log->write('SMOOVPAY :: SIGNATURE FAILED: ' . print_r($this->request->post, true));
			} else {
				if ($response_code == 1) {
					$order_status_id = $this->config->get('smoovpay_approved_status_id');
				} elseif ($response_code == 2) {
					$order_status_id = $this->config->get('smoovpay_declined_status_id');
				} elseif ($response_code == 3) {
					$order_status_id = $this->config->get('smoovpay_error_status_id');
				}
			}
			
			$this->model_checkout_order->addOrderHistory($order_id, $order_status_id);
		}
	}
}
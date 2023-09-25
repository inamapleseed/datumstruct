<?php
class ControllerCommonCart extends Controller {
	public function index() {
		$this->load->language('common/cart');


		// FIX FOR SHIPPING PRICE UPDATE - If shipping isset
		if (isset($this->session->data['shipping_address']) && isset($this->session->data['shipping_method'])) {
			$code = $this->session->data['shipping_method']['code'];
			list($shipping_module, $shipping_option) = explode('.', $code);
			
			// Shipping method disabled
			if(!$this->config->get($shipping_module . '_status')){	
				unset($this->session->data['shipping_method']);
			}
			else{
				$this->load->model('extension/shipping/' . $shipping_module);

				$quote = $this->{'model_extension_shipping_' . $shipping_module}->getQuote($this->session->data['shipping_address']);

				if( $quote && isset($quote['quote'][$shipping_option]['code']) ){
					$this->session->data['shipping_method'] = $quote['quote'][$shipping_option];
				}
			}
		}
		// End FIX FOR SHIPPING PRICE UPDATE - If shipping isset

		// Totals
		$this->load->model('extension/extension');

		$totals = array();
		$taxes = $this->cart->getTaxes();
		$total = 0;

		// Because __call can not keep var references so we put them into an array.
		$total_data = array(
			'totals' => &$totals,
			'taxes'  => &$taxes,
			'total'  => &$total
		);
			
		// Display prices
		if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
			$sort_order = array();

			$results = $this->model_extension_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('extension/total/' . $result['code']);

					// We have to put the totals in an array so that they pass by reference.
					$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
				}
			}

			$sort_order = array();

			foreach ($totals as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $totals);
		}

		$data['text_my_cart'] = $this->language->get('text_my_cart');
		$data['text_quantity'] = $this->language->get('text_quantity');
		$data['text_price'] = $this->language->get('text_price');

		$data['text_empty'] = $this->language->get('text_empty');
		$data['text_cart'] = $this->language->get('text_cart');
		$data['text_checkout'] = $this->language->get('text_checkout');
		$data['text_recurring'] = $this->language->get('text_recurring');
		$data['text_items'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total, $this->session->data['currency']));
		$data['text_loading'] = $this->language->get('text_loading');
		$data['total_item'] = $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0);

		$data['button_remove'] = $this->language->get('button_remove');

		$this->load->model('tool/image');
		$this->load->model('tool/upload');

		$data['products'] = array();

		$width = $this->config->get($this->config->get('config_theme') . '_image_cart_width');
		$height = $this->config->get($this->config->get('config_theme') . '_image_cart_height');

		$placeholder = $this->model_tool_image->resize('no_image.png', $width, $height);

		foreach ($this->cart->getProducts() as $product) {

			$image = $placeholder;

			if ($product['image']) {
				$image = $this->model_tool_image->resize($product['image'], $width, $height);
			}

			$option_data = array();

			foreach ($product['option'] as $option) {
				if ($option['type'] != 'file') {
					$value = $option['value'];
				} else {
					$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

					if ($upload_info) {
						$value = $upload_info['name'];
					} else {
						$value = '';
					}
				}

				$option_data[] = array(
					'name'  => $option['name'],
					'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value),
					'type'  => $option['type']
				);
			}

			// Display prices
			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$unit_price = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));
				
				$price = $this->currency->format($unit_price, $this->session->data['currency']);
				$total = $this->currency->format($unit_price * $product['quantity'], $this->session->data['currency']);
			} else {
				$price = false;
				$total = false;
			}

			$data['products'][] = array(
				'cart_id'   => $product['cart_id'],
				'thumb'     => $image,
				'name'      => $product['name'],
				'model'     => $product['model'],
				'option'    => $option_data,
				'recurring' => ($product['recurring'] ? $product['recurring']['name'] : ''),
				'quantity'  => $product['quantity'],
				'price'     => $price,
				'total'     => $total,
				'href'      => $this->url->link('product/product', 'product_id=' . $product['product_id'])
			);
		}

		// Gift Voucher
		//$data['vouchers'] = array();

		if (!empty($this->session->data['vouchers'])) { 
			foreach ($this->session->data['vouchers'] as $key => $voucher) {

				$vimage = $this->model_tool_image->resize($voucher['image'], $width, $height);

				// $data['vouchers'][] = array(
				$data['products'][] = array(
					//'thumb'			=> $placeholder,
					'thumb'			=> $vimage,
					'key'         	=> $key,
					'name' 			=> $voucher['description'],
					'model' 		=> '',
					'option' 		=> array(),
					'recurring'		=> array(),
					'quantity'		=>	1,
					//'description' => $voucher['description'],
					'price'      	=> $this->currency->format($voucher['amount'], $this->session->data['currency']),
					'total'      	=> $this->currency->format($voucher['amount'], $this->session->data['currency']),
					'amount'      	=> $this->currency->format($voucher['amount'], $this->session->data['currency']),
					'href'			=> $this->url->link('product/gift_card', 'voucher_theme_id='.$voucher['voucher_theme_id']),
				);
			}
		}

		$data['totals'] = array();

		foreach ($totals as $total) {
			$data['totals'][] = array(
				'title' => $total['title'],
				'text'  => $this->currency->format($total['value'], $this->session->data['currency']),
			);
		}

		$data['cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', true);
		$data['free_shipping_indicator'] = $this->freeShippingIndicator();

		return $this->load->view('common/header/cart', $data);
	}

	public function info() {
		$this->response->setOutput($this->index());
	}

	public function freeShippingIndicator(){
		$free_shipping_indicator = '';

		if($this->config->get('free_total') && $this->config->get('free_status')) {
			$data_cart = array();

			$min = $this->config->get('free_total');
			$cart_item_total = $this->cart->getSubtotal();
			$balance = $min - $cart_item_total;	

			$data_cart['percentage'] = 0;

			if($cart_item_total){
				$data_cart['percentage'] = ($cart_item_total / $min) * 100;
				$data_cart['percentage'] = $data_cart['percentage']>100?100:$data_cart['percentage'];
			}
			
			$data_cart['freed']='';
			if($balance > 0){
				$data_cart['text'] =  $this->currency->format($balance, $this->session->data['currency']);
				$data_cart['text'] = sprintf($this->language->get('text_before_free_shipping'), $data_cart['text'], '');
			}
			else{
				$data_cart['text'] = $this->language->get('text_free_shipping');
				$data_cart['freed']='freed';
			}

			$data_cart['text_free_label'] = $this->language->get('text_free_label');
			
			$free_shipping_indicator = $this->load->view('common/header/cart_free_shipping', $data_cart);
			
		}

		return $free_shipping_indicator;
	}
}

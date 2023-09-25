<?php
class ControllerApiEnquiry extends Controller {

	public function add() {
		$this->load->language('api/enquiry');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			// Customer
			if (!isset($this->session->data['customer'])) {
				$json['error'] = $this->language->get('error_customer');
			}

			// Payment Address
			if (!isset($this->session->data['payment_address'])) {
				$json['error'] = $this->language->get('error_payment_address');
			}

			// Payment Method
			if (!$json && !empty($this->request->post['payment_method'])) {
				if (empty($this->session->data['payment_methods'])) {
					$json['error'] = $this->language->get('error_no_payment');
				} elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
					$json['error'] = $this->language->get('error_payment_method');
				}

				if (!$json) {
					$this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];
				}
			}

			if (!isset($this->session->data['payment_method'])) {
				$json['error'] = $this->language->get('error_payment_method');
			}

			// Shipping
			if ($this->enquiry->hasShipping()) {
				// Shipping Address
				if (!isset($this->session->data['shipping_address'])) {
					$json['error'] = $this->language->get('error_shipping_address');
				}

				// Shipping Method
				if (!$json && !empty($this->request->post['shipping_method'])) {
					if (empty($this->session->data['shipping_methods'])) {
						$json['error'] = $this->language->get('error_no_shipping');
					} else {
						$shipping = explode('.', $this->request->post['shipping_method']);

						if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
							$json['error'] = $this->language->get('error_shipping_method');
						}
					}

					if (!$json) {
						$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
					}
				}

				// Shipping Method
				if (!isset($this->session->data['shipping_method'])) {
					$json['error'] = $this->language->get('error_shipping_method');
				}
			} else {
				unset($this->session->data['shipping_address']);
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
			}

			// Cart
			if ((!$this->enquiry->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->enquiry->hasStock() && !$this->config->get('config_stock_checkout'))) {
				$json['error'] = $this->language->get('error_stock');
			}

			// Validate minimum quantity requirements.
			$products = $this->enquiry->getProducts();

			foreach ($products as $product) {
				$product_total = 0;

				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}

				if ($product['minimum'] > $product_total) {
					$json['error'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);

					break;
				}
			}

			if (!$json) {
				$json['success'] = $this->language->get('text_success');
				
				$enquiry_data = array();

				// Store Details
				$enquiry_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
				$enquiry_data['store_id'] = $this->config->get('config_store_id');
				$enquiry_data['store_name'] = $this->config->get('config_name');
				$enquiry_data['store_url'] = $this->config->get('config_url');

				// Customer Details
				$enquiry_data['customer_id'] = $this->session->data['customer']['customer_id'];
				$enquiry_data['customer_group_id'] = $this->session->data['customer']['customer_group_id'];
				$enquiry_data['firstname'] = $this->session->data['customer']['firstname'];
				$enquiry_data['lastname'] = $this->session->data['customer']['lastname'];
				$enquiry_data['email'] = $this->session->data['customer']['email'];
				$enquiry_data['telephone'] = $this->session->data['customer']['telephone'];
				$enquiry_data['fax'] = $this->session->data['customer']['fax'];
				$enquiry_data['custom_field'] = $this->session->data['customer']['custom_field'];

				// Payment Details
				$enquiry_data['payment_firstname'] = $this->session->data['payment_address']['firstname'];
				$enquiry_data['payment_lastname'] = $this->session->data['payment_address']['lastname'];
				$enquiry_data['payment_company'] = $this->session->data['payment_address']['company'];
				$enquiry_data['payment_address_1'] = $this->session->data['payment_address']['address_1'];
				$enquiry_data['payment_address_2'] = $this->session->data['payment_address']['address_2'];
				$enquiry_data['payment_unit_no'] = $this->session->data['payment_address']['unit_no'];
				$enquiry_data['payment_city'] = $this->session->data['payment_address']['city'];
				$enquiry_data['payment_postcode'] = $this->session->data['payment_address']['postcode'];
				$enquiry_data['payment_zone'] = $this->session->data['payment_address']['zone'];
				$enquiry_data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
				$enquiry_data['payment_country'] = $this->session->data['payment_address']['country'];
				$enquiry_data['payment_country_id'] = $this->session->data['payment_address']['country_id'];
				$enquiry_data['payment_address_format'] = $this->session->data['payment_address']['address_format'];
				$enquiry_data['payment_custom_field'] = (isset($this->session->data['payment_address']['custom_field']) ? $this->session->data['payment_address']['custom_field'] : array());

				if (isset($this->session->data['payment_method']['title'])) {
					$enquiry_data['payment_method'] = $this->session->data['payment_method']['title'];
				} else {
					$enquiry_data['payment_method'] = '';
				}

				if (isset($this->session->data['payment_method']['code'])) {
					$enquiry_data['payment_code'] = $this->session->data['payment_method']['code'];
				} else {
					$enquiry_data['payment_code'] = '';
				}

				// Shipping Details
				if ($this->enquiry->hasShipping()) {
					$enquiry_data['shipping_firstname'] = $this->session->data['shipping_address']['firstname'];
					$enquiry_data['shipping_lastname'] = $this->session->data['shipping_address']['lastname'];
					$enquiry_data['shipping_company'] = $this->session->data['shipping_address']['company'];
					$enquiry_data['shipping_address_1'] = $this->session->data['shipping_address']['address_1'];
					$enquiry_data['shipping_address_2'] = $this->session->data['shipping_address']['address_2'];
					$enquiry_data['shipping_unit_no'] = $this->session->data['shipping_address']['unit_no'];
					$enquiry_data['shipping_city'] = $this->session->data['shipping_address']['city'];
					$enquiry_data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
					$enquiry_data['shipping_zone'] = $this->session->data['shipping_address']['zone'];
					$enquiry_data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
					$enquiry_data['shipping_country'] = $this->session->data['shipping_address']['country'];
					$enquiry_data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
					$enquiry_data['shipping_address_format'] = $this->session->data['shipping_address']['address_format'];
					$enquiry_data['shipping_custom_field'] = (isset($this->session->data['shipping_address']['custom_field']) ? $this->session->data['shipping_address']['custom_field'] : array());

					if (isset($this->session->data['shipping_method']['title'])) {
						$enquiry_data['shipping_method'] = $this->session->data['shipping_method']['title'];
					} else {
						$enquiry_data['shipping_method'] = '';
					}

					if (isset($this->session->data['shipping_method']['code'])) {
						$enquiry_data['shipping_code'] = $this->session->data['shipping_method']['code'];
					} else {
						$enquiry_data['shipping_code'] = '';
					}
				} else {
					$enquiry_data['shipping_firstname'] = '';
					$enquiry_data['shipping_lastname'] = '';
					$enquiry_data['shipping_company'] = '';
					$enquiry_data['shipping_address_1'] = '';
					$enquiry_data['shipping_address_2'] = '';
					$enquiry_data['shipping_unit_no'] = '';
					$enquiry_data['shipping_city'] = '';
					$enquiry_data['shipping_postcode'] = '';
					$enquiry_data['shipping_zone'] = '';
					$enquiry_data['shipping_zone_id'] = '';
					$enquiry_data['shipping_country'] = '';
					$enquiry_data['shipping_country_id'] = '';
					$enquiry_data['shipping_address_format'] = '';
					$enquiry_data['shipping_custom_field'] = array();
					$enquiry_data['shipping_method'] = '';
					$enquiry_data['shipping_code'] = '';
				}

				// Products
				$enquiry_data['products'] = array();

				foreach ($this->enquiry->getProducts() as $product) {
					$option_data = array();

					foreach ($product['option'] as $option) {
						$option_data[] = array(
							'product_option_id'       => $option['product_option_id'],
							'product_option_value_id' => $option['product_option_value_id'],
							'option_id'               => $option['option_id'],
							'option_value_id'         => $option['option_value_id'],
							'name'                    => $option['name'],
							'value'                   => $option['value'],
							'type'                    => $option['type']
						);
					}

					$enquiry_data['products'][] = array(
						'product_id' => $product['product_id'],
						'name'       => $product['name'],
						'model'      => $product['model'],
						'sku'      	 => $product['sku'],
						'option'     => $option_data,
						'download'   => $product['download'],
						'quantity'   => $product['quantity'],
						'subtract'   => $product['subtract'],
						'price'      => $product['price'],
						'total'      => $product['total'],
						'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
						'reward'     => $product['reward']
					);

				}

				// Gift Voucher
				$enquiry_data['vouchers'] = array();

				if (!empty($this->session->data['vouchers'])) {
					foreach ($this->session->data['vouchers'] as $voucher) {
						$enquiry_data['vouchers'][] = array(
							'description'      => $voucher['description'],
							'code'             => token(10),
							'to_name'          => $voucher['to_name'],
							'to_email'         => $voucher['to_email'],
							'from_name'        => $voucher['from_name'],
							'from_email'       => $voucher['from_email'],
							'voucher_theme_id' => $voucher['voucher_theme_id'],
							'message'          => $voucher['message'],
							'amount'           => $voucher['amount']
						);
					}
				}

				// enquiry Totals
				$this->load->model('extension/extension');

				$totals = array();
				$taxes = $this->enquiry->getTaxes();
				$total = 0;

				// Because __call can not keep var references so we put them into an array.
				$total_data = array(
					'totals' => &$totals,
					'taxes'  => &$taxes,
					'total'  => &$total
				);
			
				$sort_order = array();

				$results = $this->model_extension_extension->getExtensions('enquiry_total');

				foreach ($results as $key => $value) {
					$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
				}

				array_multisort($sort_order, SORT_ASC, $results);

				foreach ($results as $result) {
					if ($this->config->get($result['code'] . '_status')) {
						$this->load->model('extension/enquiry_total/' . $result['code']);
						
						// We have to put the totals in an array so that they pass by reference.
						$this->{'model_extension_enquiry_total_' . $result['code']}->getTotal($total_data);
					}
				}

				$sort_order = array();

				foreach ($total_data['totals'] as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $total_data['totals']);

				$enquiry_data = array_merge($enquiry_data, $total_data);

				if (isset($this->request->post['comment'])) {
					$enquiry_data['comment'] = $this->request->post['comment'];
				} else {
					$enquiry_data['comment'] = '';
				}

				if (isset($this->request->post['affiliate_id'])) {
					$subtotal = $this->enquiry->getSubTotal();

					// Affiliate
					$this->load->model('affiliate/affiliate');

					$affiliate_info = $this->model_affiliate_affiliate->getAffiliate($this->request->post['affiliate_id']);

					if ($affiliate_info) {
						$enquiry_data['affiliate_id'] = $affiliate_info['affiliate_id'];
						$enquiry_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
					} else {
						$enquiry_data['affiliate_id'] = 0;
						$enquiry_data['commission'] = 0;
					}

					// Marketing
					$enquiry_data['marketing_id'] = 0;
					$enquiry_data['tracking'] = '';
				} else {
					$enquiry_data['affiliate_id'] = 0;
					$enquiry_data['commission'] = 0;
					$enquiry_data['marketing_id'] = 0;
					$enquiry_data['tracking'] = '';
				}

				$enquiry_data['language_id'] = $this->config->get('config_language_id');
				$enquiry_data['currency_id'] = $this->currency->getId($this->session->data['currency']);
				$enquiry_data['currency_code'] = $this->session->data['currency'];
				$enquiry_data['currency_value'] = $this->currency->getValue($this->session->data['currency']);
				$enquiry_data['ip'] = $this->request->server['REMOTE_ADDR'];

				if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
					$enquiry_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
				} elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
					$enquiry_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
				} else {
					$enquiry_data['forwarded_ip'] = '';
				}

				if (isset($this->request->server['HTTP_USER_AGENT'])) {
					$enquiry_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
				} else {
					$enquiry_data['user_agent'] = '';
				}

				if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
					$enquiry_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
				} else {
					$enquiry_data['accept_language'] = '';
				}

				$this->load->model('checkout/enquiry');

				$enquiry_data['reward_earn'] = 0;

				if($this->config->get('reward_status') && $enquiry_data['customer_group_id'] > 0){ 
					$this->load->model('extension/total/reward');

					$reward_info = $this->model_extension_total_reward->getRewardInfoByCustomerGroup($enquiry_data['customer_group_id']);

					if($reward_info){
						$reward_point_earn_rate = $reward_info['reward_point_earn_rate'];
						$enquiry_data['reward_earn'] = $this->enquiry->getSubTotal() * $reward_point_earn_rate;
					}
				}

				$json['enquiry_order_id'] = $this->model_checkout_enquiry->addOrder($enquiry_data);

				// Set the enquiry history
				if (isset($this->request->post['order_status_id'])) {
					$order_status_id = $this->request->post['order_status_id'];
				} else {
					$order_status_id = $this->config->get('config_order_status_id');
				}

				$this->model_checkout_enquiry->addOrderHistory($json['enquiry_order_id'], $order_status_id);

				$products = $this->enquiry->getProducts();
				if ($products && sizeof($products)) {
				$this->facebookcommonutils = new FacebookCommonUtils();
				$this->facebookcommonutils->updateProductAvailability(
					$this->registry,
					$products);
				}
				
				// clear cart since the enquiry has already been successfully stored.
				//$this->enquiry->clear();
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function edit() {
		$this->load->language('api/enquiry');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('checkout/enquiry');

			if (isset($this->request->get['enquiry_order_id'])) {
				$enquiry_order_id = $this->request->get['enquiry_order_id'];
			} else {
				$enquiry_order_id = 0;
			}

			$enquiry_info = $this->model_checkout_enquiry->getOrder($enquiry_order_id);

			if ($enquiry_info) {
				// Customer
				if (!isset($this->session->data['customer'])) {
					$json['error'] = $this->language->get('error_customer');
				}

				// Payment Address
				if (!isset($this->session->data['payment_address'])) {
					$json['error'] = $this->language->get('error_payment_address');
				}

				// Payment Method
				if (!$json && !empty($this->request->post['payment_method'])) {
					if (empty($this->session->data['payment_methods'])) {
						$json['error'] = $this->language->get('error_no_payment');
					} elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
						$json['error'] = $this->language->get('error_payment_method');
					}

					if (!$json) {
						$this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];
					}
				}

				if (!isset($this->session->data['payment_method'])) {
					$json['error'] = $this->language->get('error_payment_method');
				}

				// Shipping
				if ($this->enquiry->hasShipping()) {
					// Shipping Address
					if (!isset($this->session->data['shipping_address'])) {
						$json['error'] = $this->language->get('error_shipping_address');
					}

					// Shipping Method
					if (!$json && !empty($this->request->post['shipping_method'])) {
						if (empty($this->session->data['shipping_methods'])) {
							$json['error'] = $this->language->get('error_no_shipping');
						} else {
							$shipping = explode('.', $this->request->post['shipping_method']);

							if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
								$json['error'] = $this->language->get('error_shipping_method');
							}
						}

						if (!$json) {
							$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
						}
					}

					if (!isset($this->session->data['shipping_method'])) {
						$json['error'] = $this->language->get('error_shipping_method');
					}
				} else {
					unset($this->session->data['shipping_address']);
					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
				}

				// Cart
				if ((!$this->enquiry->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->enquiry->hasStock() && !$this->config->get('config_stock_checkout'))) {
					$json['error'] = $this->language->get('error_stock');
				}

				// Validate minimum quantity requirements.
				$products = $this->enquiry->getProducts();

				foreach ($products as $product) {
					$product_total = 0;

					foreach ($products as $product_2) {
						if ($product_2['product_id'] == $product['product_id']) {
							$product_total += $product_2['quantity'];
						}
					}

					if ($product['minimum'] > $product_total) {
						$json['error'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);

						break;
					}
				}

				if (!$json) {
					$json['success'] = $this->language->get('text_success');
					
					$enquiry_data = array();

					// Store Details
					$enquiry_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
					$enquiry_data['store_id'] = $this->config->get('config_store_id');
					$enquiry_data['store_name'] = $this->config->get('config_name');
					$enquiry_data['store_url'] = $this->config->get('config_url');

					// Customer Details
					$enquiry_data['customer_id'] = $this->session->data['customer']['customer_id'];
					$enquiry_data['customer_group_id'] = $this->session->data['customer']['customer_group_id'];
					$enquiry_data['firstname'] = $this->session->data['customer']['firstname'];
					$enquiry_data['lastname'] = $this->session->data['customer']['lastname'];
					$enquiry_data['email'] = $this->session->data['customer']['email'];
					$enquiry_data['telephone'] = $this->session->data['customer']['telephone'];
					$enquiry_data['fax'] = $this->session->data['customer']['fax'];
					$enquiry_data['custom_field'] = $this->session->data['customer']['custom_field'];

					// Payment Details
					$enquiry_data['payment_firstname'] = $this->session->data['payment_address']['firstname'];
					$enquiry_data['payment_lastname'] = $this->session->data['payment_address']['lastname'];
					$enquiry_data['payment_company'] = $this->session->data['payment_address']['company'];
					$enquiry_data['payment_address_1'] = $this->session->data['payment_address']['address_1'];
					$enquiry_data['payment_address_2'] = $this->session->data['payment_address']['address_2'];
					$enquiry_data['payment_unit_no'] = $this->session->data['payment_address']['unit_no'];
					$enquiry_data['payment_city'] = $this->session->data['payment_address']['city'];
					$enquiry_data['payment_postcode'] = $this->session->data['payment_address']['postcode'];
					$enquiry_data['payment_zone'] = $this->session->data['payment_address']['zone'];
					$enquiry_data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
					$enquiry_data['payment_country'] = $this->session->data['payment_address']['country'];
					$enquiry_data['payment_country_id'] = $this->session->data['payment_address']['country_id'];
					$enquiry_data['payment_address_format'] = $this->session->data['payment_address']['address_format'];
					$enquiry_data['payment_custom_field'] = $this->session->data['payment_address']['custom_field'];

					if (isset($this->session->data['payment_method']['title'])) {
						$enquiry_data['payment_method'] = $this->session->data['payment_method']['title'];
					} else {
						$enquiry_data['payment_method'] = '';
					}

					if (isset($this->session->data['payment_method']['code'])) {
						$enquiry_data['payment_code'] = $this->session->data['payment_method']['code'];
					} else {
						$enquiry_data['payment_code'] = '';
					}

					// Shipping Details
					if ($this->enquiry->hasShipping()) {
						$enquiry_data['shipping_firstname'] = $this->session->data['shipping_address']['firstname'];
						$enquiry_data['shipping_lastname'] = $this->session->data['shipping_address']['lastname'];
						$enquiry_data['shipping_company'] = $this->session->data['shipping_address']['company'];
						$enquiry_data['shipping_address_1'] = $this->session->data['shipping_address']['address_1'];
						$enquiry_data['shipping_address_2'] = $this->session->data['shipping_address']['address_2'];
						$enquiry_data['shipping_unit_no'] = $this->session->data['shipping_address']['unit_no'];
						$enquiry_data['shipping_city'] = $this->session->data['shipping_address']['city'];
						$enquiry_data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
						$enquiry_data['shipping_zone'] = $this->session->data['shipping_address']['zone'];
						$enquiry_data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
						$enquiry_data['shipping_country'] = $this->session->data['shipping_address']['country'];
						$enquiry_data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
						$enquiry_data['shipping_address_format'] = $this->session->data['shipping_address']['address_format'];
						$enquiry_data['shipping_custom_field'] = $this->session->data['shipping_address']['custom_field'];

						if (isset($this->session->data['shipping_method']['title'])) {
							$enquiry_data['shipping_method'] = $this->session->data['shipping_method']['title'];
						} else {
							$enquiry_data['shipping_method'] = '';
						}

						if (isset($this->session->data['shipping_method']['code'])) {
							$enquiry_data['shipping_code'] = $this->session->data['shipping_method']['code'];
						} else {
							$enquiry_data['shipping_code'] = '';
						}
					} else {
						$enquiry_data['shipping_firstname'] = '';
						$enquiry_data['shipping_lastname'] = '';
						$enquiry_data['shipping_company'] = '';
						$enquiry_data['shipping_address_1'] = '';
						$enquiry_data['shipping_address_2'] = '';
						$enquiry_data['shipping_unit_no'] = '';
						$enquiry_data['shipping_city'] = '';
						$enquiry_data['shipping_postcode'] = '';
						$enquiry_data['shipping_zone'] = '';
						$enquiry_data['shipping_zone_id'] = '';
						$enquiry_data['shipping_country'] = '';
						$enquiry_data['shipping_country_id'] = '';
						$enquiry_data['shipping_address_format'] = '';
						$enquiry_data['shipping_custom_field'] = array();
						$enquiry_data['shipping_method'] = '';
						$enquiry_data['shipping_code'] = '';
					}

					// Products
					$enquiry_data['products'] = array();

					foreach ($this->enquiry->getProducts() as $product) {
						$option_data = array();

						foreach ($product['option'] as $option) {
							$option_data[] = array(
								'product_option_id'       => $option['product_option_id'],
								'product_option_value_id' => $option['product_option_value_id'],
								'option_id'               => $option['option_id'],
								'option_value_id'         => $option['option_value_id'],
								'name'                    => $option['name'],
								'value'                   => $option['value'],
								'type'                    => $option['type']
							);
						}

						$enquiry_data['products'][] = array(
							'product_id' => $product['product_id'],
							'name'       => $product['name'],
							'model'      => $product['model'],
							'sku'        => $product['sku'],
							'option'     => $option_data,
							'download'   => $product['download'],
							'quantity'   => $product['quantity'],
							'subtract'   => $product['subtract'],
							'price'      => $product['price'],
							'total'      => $product['total'],
							'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
							'reward'     => $product['reward']
						);
					}

					// Gift Voucher
					$enquiry_data['vouchers'] = array();

					if (!empty($this->session->data['vouchers'])) {
						foreach ($this->session->data['vouchers'] as $voucher) {
							$enquiry_data['vouchers'][] = array(
								'description'      => $voucher['description'],
								'code'             => token(10),
								'to_name'          => $voucher['to_name'],
								'to_email'         => $voucher['to_email'],
								'from_name'        => $voucher['from_name'],
								'from_email'       => $voucher['from_email'],
								'voucher_theme_id' => $voucher['voucher_theme_id'],
								'message'          => $voucher['message'],
								'amount'           => $voucher['amount']
							);
						}
					}

					// enquiry Totals
					$this->load->model('extension/extension');

					$totals = array();
					$taxes = $this->enquiry->getTaxes();
					$total = 0;
					
					// Because __call can not keep var references so we put them into an array. 
					$total_data = array(
						'totals' => &$totals,
						'taxes'  => &$taxes,
						'total'  => &$total
					);
			
					$sort_order = array();

					$results = $this->model_extension_extension->getExtensions('enquiry_total');

					foreach ($results as $key => $value) {
						$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
					}

					array_multisort($sort_order, SORT_ASC, $results);

					foreach ($results as $result) {
						if ($this->config->get($result['code'] . '_status')) {
							$this->load->model('extension/enquiry_total/' . $result['code']);
							
							// We have to put the totals in an array so that they pass by reference.
							$this->{'model_extension_enquiry_total_' . $result['code']}->getTotal($total_data);
						}
					}

					$sort_order = array();

					foreach ($total_data['totals'] as $key => $value) {
						$sort_order[$key] = $value['sort_order'];
					}

					array_multisort($sort_order, SORT_ASC, $total_data['totals']);

					$enquiry_data = array_merge($enquiry_data, $total_data);

					if (isset($this->request->post['comment'])) {
						$enquiry_data['comment'] = $this->request->post['comment'];
					} else {
						$enquiry_data['comment'] = '';
					}

					if (isset($this->request->post['affiliate_id'])) {
						$subtotal = $this->enquiry->getSubTotal();

						// Affiliate
						$this->load->model('affiliate/affiliate');

						$affiliate_info = $this->model_affiliate_affiliate->getAffiliate($this->request->post['affiliate_id']);

						if ($affiliate_info) {
							$enquiry_data['affiliate_id'] = $affiliate_info['affiliate_id'];
							$enquiry_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
						} else {
							$enquiry_data['affiliate_id'] = 0;
							$enquiry_data['commission'] = 0;
						}
					} else {
						$enquiry_data['affiliate_id'] = 0;
						$enquiry_data['commission'] = 0;
					}

					$products_pre_edit = $this->model_checkout_enquiry->getOrderProductIds($enquiry_order_id);

					$enquiry_data['reward_earn'] = 0;

					if($this->config->get('reward_status') &&  
					(!isset($this->session->data['reward']) || (int)$this->session->data['reward'] < 1 ) 
					){ 
						$this->load->model('extension/total/reward');

						$reward_info = $this->model_extension_total_reward->getRewardInfoByCustomerGroup($enquiry_data['customer_group_id']);

						if($reward_info){
							$reward_point_earn_rate = $reward_info['reward_point_earn_rate']/100;
							$enquiry_data['reward_earn'] = $this->enquiry->getSubTotal() * $reward_point_earn_rate;
						}
					}

					$this->model_checkout_enquiry->editOrder($enquiry_order_id, $enquiry_data);

					// Set the enquiry history
					if (isset($this->request->post['order_status_id'])) {
						$order_status_id = $this->request->post['order_status_id'];
					} else {
						$order_status_id = $this->config->get('config_order_status_id');
					}

					$this->model_checkout_enquiry->addOrderHistory($enquiry_order_id, $order_status_id);

					$products_post_edit = $this->enquiry->getProducts();
					$this->facebookcommonutils = new FacebookCommonUtils();
					$products_for_availabilty_update =
					array_merge($products_pre_edit, $products_post_edit);
					$this->facebookcommonutils->updateProductAvailability(
					$this->registry,
					$products_for_availabilty_update);

				}
			} else {
				$json['error'] = $this->language->get('error_not_found');
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete() {
		$this->load->language('api/enquiry');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('checkout/enquiry');

			if (isset($this->request->get['enquiry_order_id'])) {
				$enquiry_order_id = $this->request->get['enquiry_order_id'];
			} else {
				$enquiry_order_id = 0;
			}

			$enquiry_info = $this->model_checkout_enquiry->getOrder($enquiry_order_id);

			if ($enquiry_info) {
				$this->model_checkout_enquiry->deleteenquiry($enquiry_order_id);

				$json['success'] = $this->language->get('text_success');
			} else {
				$json['error'] = $this->language->get('error_not_found');
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function info() {
		$this->load->language('api/enquiry');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('checkout/enquiry');

			if (isset($this->request->get['enquiry_order_id'])) {
				$enquiry_order_id = $this->request->get['enquiry_order_id'];
			} else {
				$enquiry_order_id = 0;
			}

			$enquiry_info = $this->model_checkout_enquiry->getOrder($enquiry_order_id);

			if ($enquiry_info) {
				$json['enquiry'] = $enquiry_info;

				$json['success'] = $this->language->get('text_success');
			} else {
				$json['error'] = $this->language->get('error_not_found');
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function history() {
		$this->load->language('api/enquiry');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			// Add keys for missing post vars
			$keys = array(
				'enquiry_order_status_id',
				'notify',
				'override',
				'comment'
			);

			foreach ($keys as $key) {
				if (!isset($this->request->post[$key])) {
					$this->request->post[$key] = '';
				}
			}

			$this->load->model('checkout/enquiry');

			if (isset($this->request->get['enquiry_order_id'])) {
				$enquiry_order_id = $this->request->get['enquiry_order_id'];
			} else {
				$enquiry_order_id = 0;
			}

			$enquiry_info = $this->model_checkout_enquiry->getOrder($enquiry_order_id);

			if ($enquiry_info) {
				$this->model_checkout_enquiry->addOrderHistory($enquiry_order_id, $this->request->post['enquiry_order_status_id'], $this->request->post['comment'], $this->request->post['notify'], $this->request->post['override']);

				$json['success'] = $this->language->get('text_success');
			} else {
				$json['error'] = $this->language->get('error_not_found');
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
<?php
	class ControllerCheckoutRegister extends Controller {
		public function index() {
			$this->load->language('checkout/checkout');
			
			$data['text_checkout_payment_address'] = $this->language->get('text_checkout_payment_address');
			$data['text_your_details'] = $this->language->get('text_your_details');
			$data['text_your_address'] = $this->language->get('text_your_address');
			$data['text_your_password'] = $this->language->get('text_your_password');
			$data['text_select'] = $this->language->get('text_select');
			$data['text_none'] = $this->language->get('text_none');
			$data['text_loading'] = $this->language->get('text_loading');
			
			$data['entry_customer_group'] = $this->language->get('entry_customer_group');
			$data['entry_firstname'] = $this->language->get('entry_firstname');
			$data['entry_lastname'] = $this->language->get('entry_lastname');
			$data['entry_email'] = $this->language->get('entry_email');
			$data['entry_telephone'] = $this->language->get('entry_telephone');
			$data['entry_fax'] = $this->language->get('entry_fax');
			$data['entry_company'] = $this->language->get('entry_company');
			$data['entry_address_1'] = $this->language->get('entry_address_1');
			$data['entry_address_2'] = $this->language->get('entry_address_2');
			$data['entry_postcode'] = $this->language->get('entry_postcode');
			$data['entry_city'] = $this->language->get('entry_city');
			$data['entry_country'] = $this->language->get('entry_country');
			$data['entry_zone'] = $this->language->get('entry_zone');
			$data['entry_newsletter'] = sprintf($this->language->get('entry_newsletter'), $this->config->get('config_name'));
			$data['entry_password'] = $this->language->get('entry_password');
			$data['entry_confirm'] = $this->language->get('entry_confirm');
			$data['entry_shipping'] = $this->language->get('entry_shipping');
			
			$data['button_continue'] = $this->language->get('button_continue');
			$data['button_upload'] = $this->language->get('button_upload');
			
			$data['customer_groups'] = array();
			
			if (is_array($this->config->get('config_customer_group_display'))) {
				$this->load->model('account/customer_group');
				
				$customer_groups = $this->model_account_customer_group->getCustomerGroups();
				
				foreach ($customer_groups  as $customer_group) {
					if (in_array($customer_group['customer_group_id'], $this->config->get('config_customer_group_display'))) {
						$data['customer_groups'][] = $customer_group;
					}
				}
			}
			
			$data['customer_group_id'] = $this->config->get('config_customer_group_id');
			
			if (isset($this->session->data['shipping_address']['postcode'])) {
				$data['postcode'] = $this->session->data['shipping_address']['postcode'];
				} else {
				$data['postcode'] = '';
			}
			
			if (isset($this->session->data['shipping_address']['country_id'])) {
				$data['country_id'] = $this->session->data['shipping_address']['country_id'];
				} else {
				$data['country_id'] = $this->config->get('config_country_id');
			}
			
			if (isset($this->session->data['shipping_address']['zone_id'])) {
				$data['zone_id'] = $this->session->data['shipping_address']['zone_id'];
				} else {
				$data['zone_id'] = '';
			}
			
			$this->load->model('localisation/country');
			
			$data['countries'] = $this->model_localisation_country->getCountries();
			
			// Custom Fields
			$this->load->model('account/custom_field');
			
			$data['custom_fields'] = $this->model_account_custom_field->getCustomFields();
			
			// Captcha
			if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('register', (array)$this->config->get('config_captcha_page'))) {
				$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));
				} else {
				$data['captcha'] = '';
			}
			
			if ($this->config->get('config_account_id')) {
				$this->load->model('catalog/information');
				
				$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));
				
				if ($information_info) {
					$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_account_id'), true), $information_info['title'], $information_info['title']);
					} else {
					$data['text_agree'] = '';
				}
				} else {
				$data['text_agree'] = '';
			}
			
			$data['shipping_required'] = $this->cart->hasShipping();
			
			$this->response->setOutput($this->load->view('checkout/register', $data));
		}
		
		public function save() {
			$this->load->language('checkout/checkout');
			
			$json = array();
			
			// Validate if customer is already logged out.
			if ($this->customer->isLogged()) {
				$json['redirect'] = $this->url->link('checkout/checkout', '', true);
			}
			
			// Validate cart has products and has stock.
			if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
				$json['redirect'] = $this->url->link('checkout/cart');
			}
			
			// Validate minimum quantity requirements.
			$products = $this->cart->getProducts();
			
			foreach ($products as $product) {
				$product_total = 0;
				
				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}
				
				if ($product['minimum'] > $product_total) {
					$json['redirect'] = $this->url->link('checkout/cart');
					
					break;
				}
			}
			
			if (!$json) {
				$this->load->model('account/customer');
				
				if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
					$json['error']['firstname'] = $this->language->get('error_firstname');
				}
				
				if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
					$json['error']['lastname'] = $this->language->get('error_lastname');
				}
				
				if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
					$json['error']['email'] = $this->language->get('error_email');
				}
				
				if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
					$json['error']['warning'] = $this->language->get('error_exists');
				}
				
				if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
					$json['error']['telephone'] = $this->language->get('error_telephone');
				}
				
				if ((utf8_strlen(trim($this->request->post['address_1'])) < 3) || (utf8_strlen(trim($this->request->post['address_1'])) > 128)) {
					$json['error']['address_1'] = $this->language->get('error_address_1');
				}
				
				if ((utf8_strlen(trim($this->request->post['city'])) < 2) || (utf8_strlen(trim($this->request->post['city'])) > 128)) {
					$json['error']['city'] = $this->language->get('error_city');
				}
				
				$this->load->model('localisation/country');
				
				$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
				
				if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) {
					$json['error']['postcode'] = $this->language->get('error_postcode');
				}
				
				if ($this->request->post['country_id'] == '') {
					$json['error']['country'] = $this->language->get('error_country');
				}
				
				if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '' || !is_numeric($this->request->post['zone_id'])) {
					$json['error']['zone'] = $this->language->get('error_zone');
				}
				
				if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
					$json['error']['password'] = $this->language->get('error_password');
				}
				
				if ($this->request->post['confirm'] != $this->request->post['password']) {
					$json['error']['confirm'] = $this->language->get('error_confirm');
				}

				$password_check = $this->validatePassword();
				if(!$password_check['result']) {
					$this->error['password'] = implode('<br/>', $password_check['error']);
					unset($json['error']['confirm']);
				}
				
				if ($this->config->get('config_account_id')) {
					$this->load->model('catalog/information');
					
					$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));
					
					if ($information_info && !isset($this->request->post['agree'])) {
						$json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
					}
				}
				
				// Customer Group
				if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
					$customer_group_id = $this->request->post['customer_group_id'];
					} else {
					$customer_group_id = $this->config->get('config_customer_group_id');
				}
				
				// Custom field validation
				$this->load->model('account/custom_field');
				
				$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);
				
				foreach ($custom_fields as $custom_field) {
					if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
						$json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
						} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !filter_var($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {
						$json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
					}
				}
				
				// Captcha
				if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('register', (array)$this->config->get('config_captcha_page'))) {
					$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');
					
					if ($captcha) {
						$json['error']['captcha'] = $captcha;
					}
				}
			}
			
			if (!$json) {
				$customer_id = $this->model_account_customer->addCustomer($this->request->post);
				
				// Clear any previous login attempts for unregistered accounts.
				$this->model_account_customer->deleteLoginAttempts($this->request->post['email']);
				
				$this->session->data['account'] = 'register';
				
				$this->load->model('account/customer_group');
				
				$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
				
				if ($customer_group_info && !$customer_group_info['approval']) {
					$this->customer->login($this->request->post['email'], $this->request->post['password']);
					
					// Default Payment Address
					$this->load->model('account/address');
					
					$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
					
					if (!empty($this->request->post['shipping_address'])) {
						$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
					}
					} else {
					$json['redirect'] = $this->url->link('account/success');
				}
				
				unset($this->session->data['guest']);
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
				
				// Add to activity log
				if ($this->config->get('config_customer_activity')) {
					$this->load->model('account/activity');
					
					$activity_data = array(
					'customer_id' => $customer_id,
					'name'        => $this->request->post['firstname'] . ' ' . $this->request->post['lastname']
					);
					
					$this->model_account_activity->addActivity('register', $activity_data);
				}
			}
			
			$this->response->addHeader('Content-Type: application/json');
			
			/* AbandonedCarts - Begin */
			
			$this->load->model('setting/setting');
			
			$abandonedCartsSettings = $this->model_setting_setting->getSetting('abandonedcarts', $this->config->get('store_id'));
			
			if (isset($abandonedCartsSettings['abandonedcarts']['Enabled']) && $abandonedCartsSettings['abandonedcarts']['Enabled']=='yes') { 
				$cart = $this->cart->getProducts();
				$cart = (!empty($cart)) ? $cart : '';
				
				if (!empty($cart)) {
					if (isset($this->session->data['abandonedCart_ID']) & !empty($this->session->data['abandonedCart_ID'])) {
						$id = $this->session->data['abandonedCart_ID'];
						} else if ($this->customer->isLogged()) {
						$id = (!empty($this->session->data['abandonedCart_ID'])) ? $this->session->data['abandonedCart_ID'] : $this->customer->getEmail();
						} else {
						$id = (!empty($this->session->data['abandonedCart_ID'])) ? $this->session->data['abandonedCart_ID'] : session_id();
					}
					$exists = $this->db->query("SELECT * FROM `" . DB_PREFIX . "abandonedcarts` WHERE `restore_id` = '$id' AND `ordered`=0");
					
					if (empty($exists->row['customer_info'])) { 
						$customer = array(); 
						
						if (!empty($this->request->post['telephone'])) {
							$customer['telephone'] = $this->request->post['telephone'];
						}
						if (!empty($this->request->post['email'])) {
							$customer['email'] = $this->request->post['email'];
						}
						if (!empty($this->request->post['firstname'])) {
							$customer['firstname'] = $this->request->post['firstname'];
						}
						if (!empty($this->request->post['lastname'])) {
							$customer['lastname'] = $this->request->post['lastname'];
						}
						if (!empty($this->session->data['language'])) {
							$customer['language'] = $this->session->data['language'];
						}
						$customer = json_encode($customer);
						$this->db->query("UPDATE `" . DB_PREFIX . "abandonedcarts` SET `customer_info`='".$this->db->escape($customer)."', `restore_id`='".$this->request->post['email']."' WHERE `restore_id`='$id' AND `ordered`=0");
						
						$this->session->data['abandonedCart_ID'] = $this->request->post['email'];
					}
				}
			}
			/* AbandonedCarts - End */
			
			$this->response->setOutput(json_encode($json));
		}

		protected function validatePassword() {
			//Options (Set any to 0 to disable checks).
			$min_length = 8; //Minimum password length.
			$max_length = 20;  //Maximum password length.
			$n_numbers = 1;  //Minimum number of numbers (0-9).
			$n_characters = 1; //Minimum number of special characters (/\$%£! .etc).
			$n_lower = 1; //Minimum number of lowercase letters.
			$n_upper = 1; //Minimum number of uppercase letters.

			$error_message = array();
			$error = false;
			$password = $this->request->post['password'];
			if($min_length) {
				$error_message[] = $min_length .' to '. $max_length .' characters.';
				if(utf8_strlen($password) < $min_length || utf8_strlen($password) > $max_length) {
					$error = true;
				}
			}
			if($n_lower) {
				$error_message[] = $n_lower .' or more lowercase letters.';
				if(preg_match_all( "/[a-z]/", $password ) < $n_lower) {
					$error = true;
				}
			}
			if($n_upper) {
				$error_message[] = $n_upper .' or more uppercase letters.';
				if(preg_match_all( "/[A-Z]/", $password ) < $n_upper) {
					$error = true;
				}
			}
			if($n_numbers) {
				$error_message[] = $n_numbers .' or more numbers.';
				if(preg_match_all( "/[0-9]/", $password ) < $n_numbers) {
					$error = true;
				}
			}
			if($n_characters) {
				$error_message[] = $n_characters .' or more special characters (e.g. !?.,#$&%).';
				if(preg_match_all( "/(?=\D)(?=\W)(?=\S)./", $password ) < $n_characters) {
					$error = true;
				}
			}
			if($error) {
				return array('result'=>false, 'error'=>$error_message);
			} else {
				return array('result'=>true);
			}
		}
	}

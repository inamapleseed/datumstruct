<?php
//==============================================================================
// Ultimate Shipping v230.2
// 
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
// 
// All code within this file is copyright Clear Thinking, LLC.
// You may not copy or reuse code within this file without written permission.
//==============================================================================

class ModelShippingUltimateShipping extends Model {
	private $type = 'shipping';
	private $name = 'ultimate_shipping';
	private $testing_mode;
	private $charge;
	
	public function getQuote($address) {
		if (!empty($this->session->data[$this->name . '_settings'])) {
			$settings = $this->session->data[$this->name . '_settings'];
		} else {
			$settings = $this->getSettings();
			$this->session->data[$this->name . '_settings'] = $settings;
		}
		
		$this->testing_mode = $settings['testing_mode'];
		$this->logMessage("\n" . '------------------------------ Starting Test ' . date('Y-m-d G:i:s') . ' ------------------------------');
		
		if (empty($settings['status'])) {
			$this->logMessage('Extension is disabled');
			return;
		}
		
		// Set address info
		$addresses = array();
		$this->load->model('account/address');
		foreach (array('shipping', 'payment', 'geoiptools') as $address_type) {
			if ($address_type == 'geoiptools' && !empty($this->session->data['geoip_data']['location'])) {
				$address = $this->session->data['geoip_data']['location'];
			} elseif (($address_type == 'shipping' && empty($address)) || $address_type == 'payment') {
				$address = array();
				
				if ($this->customer->isLogged()) 										$address = $this->model_account_address->getAddress($this->customer->getAddressId());
				if (!empty($this->session->data['country_id']))							$address['country_id'] = $this->session->data['country_id'];
				if (!empty($this->session->data['zone_id']))							$address['zone_id'] = $this->session->data['zone_id'];
				if (!empty($this->session->data['postcode']))							$address['postcode'] = $this->session->data['postcode'];
				if (!empty($this->session->data['city']))								$address['city'] = $this->session->data['city'];
				
				if (!empty($this->session->data[$address_type . '_country_id']))		$address['country_id'] = $this->session->data[$address_type . '_country_id'];
				if (!empty($this->session->data[$address_type . '_zone_id']))			$address['zone_id'] = $this->session->data[$address_type . '_zone_id'];
				if (!empty($this->session->data[$address_type . '_postcode']))			$address['postcode'] = $this->session->data[$address_type . '_postcode'];
				if (!empty($this->session->data[$address_type . '_city']))				$address['city'] = $this->session->data[$address_type . '_city'];
				
				if (!empty($this->session->data['guest'][$address_type]))				$address = $this->session->data['guest'][$address_type];
				if (!empty($this->session->data[$address_type . '_address_id']))		$address = $this->model_account_address->getAddress($this->session->data[$address_type . '_address_id']);
				if (!empty($this->session->data[$address_type . '_address']))			$address = $this->session->data[$address_type.'_address'];
			}
			
			if (empty($address['address_1']))	$address['address_1'] = '';
			if (empty($address['address_2']))	$address['address_2'] = '';
			if (empty($address['city']))		$address['city'] = '';
			if (empty($address['postcode']))	$address['postcode'] = '';
			if (empty($address['country_id']))	$address['country_id'] = $this->config->get('config_country_id');
			if (empty($address['zone_id']))		$address['zone_id'] =  $this->config->get('config_zone_id');
			
			$country_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = " . (int)$address['country_id']);
			$address['country'] = (isset($country_query->row['name'])) ? $country_query->row['name'] : '';
			$address['iso_code_2'] = (isset($country_query->row['iso_code_2'])) ? $country_query->row['iso_code_2'] : '';
			
			$zone_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = " . (int)$address['zone_id']);
			$address['zone'] = (isset($zone_query->row['name'])) ? $zone_query->row['name'] : '';
			$address['zone_code'] = (isset($zone_query->row['code'])) ? $zone_query->row['code'] : '';
			
			$addresses[$address_type] = $address;
			
			$addresses[$address_type]['geo_zones'] = array();
			$geo_zones_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE country_id = " . (int)$address['country_id'] . " AND (zone_id = 0 OR zone_id = " . (int)$address['zone_id'] . ")");
			if ($geo_zones_query->num_rows) {
				foreach ($geo_zones_query->rows as $geo_zone) {
					$addresses[$address_type]['geo_zones'][] = $geo_zone['geo_zone_id'];
				}
			} else {
				$addresses[$address_type]['geo_zones'] = array(0);
			}
		}
		
		// Set order totals if necessary
		if ($this->type != 'total') {
			$order_totals_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = 'total' ORDER BY `code` ASC");
			$order_totals = $order_totals_query->rows;
			
			$sort_order = array();
			foreach ($order_totals as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}
			array_multisort($sort_order, SORT_ASC, $order_totals);
			
			$total_data = array();
			$order_total = 0;
			$taxes = $this->cart->getTaxes();
			
			foreach ($order_totals as $ot) {
				if ($ot['code'] == 'shipping' && $this->type == 'shipping') break;
				if (!$this->config->get($ot['code'] . '_status')) continue;
				if (version_compare(VERSION, '2.2', '<')) {
					$this->load->model('total/' . $ot['code']);
					$this->{'model_total_' . $ot['code']}->getTotal($total_data, $order_total, $taxes);
				} elseif (version_compare(VERSION, '2.3', '<')) {
					$this->load->model('total/' . $ot['code']);
					$this->{'model_total_' . $ot['code']}->getTotal(array('totals' => &$total_data, 'total' => &$order_total, 'taxes' => &$taxes));
				} else {
					$this->load->model('extension/total/' . $ot['code']);
					$this->{'model_extension_total_' . $ot['code']}->getTotal(array('totals' => &$total_data, 'total' => &$order_total, 'taxes' => &$taxes));
				}
			}
		}
		
		// Set shipping/payment info
		$shipping_method = (isset($this->session->data['shipping_method']['code'])) ? substr($this->session->data['shipping_method']['code'], 0, strpos($this->session->data['shipping_method']['code'], '.')) : '';
		$shipping_rate = (isset($this->session->data['shipping_method']['title'])) ? strtolower($this->session->data['shipping_method']['title']) : '';
		$shipping_cost = (isset($this->session->data['shipping_method']['cost'])) ? $this->session->data['shipping_method']['cost'] : '';		
		
		if (isset($this->session->data['payment_method']['code'])) {
			$payment_method = $this->session->data['payment_method']['code'];
		} elseif (isset($this->request->post['payment_code'])) {
			$payment_method = $this->request->post['payment_code'];
		} else {
			$payment_method = '';
		}
		
		// Set cart and order data
		$this->load->model('catalog/product');
		
		$cart_products = $this->cart->getProducts();
		if (version_compare(VERSION, '2.1', '>=')) {
			foreach ($cart_products as &$cart_product) {
				$cart_product['key'] = $cart_product['product_id'] . json_encode($cart_product['option']);
			}
		}
		
		$cumulative_total_value = $order_total;
		$currency = $this->session->data['currency'];
		$customer_id = (int)$this->customer->getId();
		$customer_group_id = (version_compare(VERSION, '2.0', '<')) ? (int)$this->customer->getCustomerGroupId() : (int)$this->customer->getGroupId();
		$default_currency = $this->config->get('config_currency');
		$distance = 0;
		$language = (isset($this->session->data['language'])) ? $this->session->data['language'] : $this->config->get('config_language');
		$store_id = (isset($this->session->data['store_id'])) ? (int)$this->session->data['store_id'] : (int)$this->config->get('config_store_id');
		
		$this->load->model('account/reward');
		$coupon = (isset($this->session->data['coupon'])) ? $this->session->data['coupon'] : '';
		$reward_points = (isset($this->session->data['reward'])) ? $this->session->data['reward'] : '';
		$reward_points_in_account = $this->model_account_reward->getTotalPoints();
		$voucher = (isset($this->session->data['voucher'])) ? $this->session->data['voucher'] : '';
		
		$customer = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = " . (int)$customer_id);
		if (!empty($customer->row['custom_field'])) {
			$customer_custom_fields = (version_compare(VERSION, '2.1', '<')) ? unserialize($customer->row['custom_field']) : json_decode($customer->row['custom_field'], true);
		} else {
			$customer_custom_fields = array();
		}
		
		// Loop through charges
		$sort_order = array();
		foreach ($settings['charge'] as $key => $value) {
			$sort_order[$key] = (empty($value['group'])) ? 0 : $value['group'];
		}
		array_multisort($sort_order, SORT_ASC, $settings['charge']);
		
		$charges = array();
		
		foreach ($settings['charge'] as $charge) {
			// Set up basic charge data
			if (!empty($charge['title_admin'])) {
				$charge['title'] = $charge['title_admin'];
			} elseif (!empty($charge['title_' . $language])) {
				$charge['title'] = $charge['title_' . $language];
			} elseif (!empty($charge['group'])) {
				$charge['title'] = '(Group ' . $charge['group'] . ')';
			} else {
				$charge['title'] = '';
			}
			
			if (empty($charge['group'])) {
				$charge['group'] = 0;
			}
			if ((int)$charge['group'] < 0) {
				$this->logMessage('"' . $charge['title'] . '" disabled because it has a negative Group value');
				continue;
			}
			
			if (empty($charge['type'])) {
				$charge['type'] = str_replace(array('_based', '_fee'), '', $this->name);
			}
			
			$this->charge = $charge;

			// Compile rules and rule sets
			$rule_list = (!empty($charge['rule'])) ? $charge['rule'] : array();
			$rule_sets = array();
			
			foreach ($rule_list as $rule) {
				if (isset($rule['type']) && $rule['type'] == 'rule_set') {
					$rule_sets[] = $settings['rule_set'][$rule['value']]['rule'];
				}
			}
			
			foreach ($rule_sets as $rule_set) {
				$rule_list = array_merge($rule_list, $rule_set);
			}
			
			$rules = array();
			foreach ($rule_list as $rule) {
				if (empty($rule['type'])) continue;
				
				if (isset($rule['value'])) {
					if (in_array($rule['type'], array('attribute_group', 'category', 'manufacturer', 'product', 'customer', 'zone'))) {
						$value = substr($rule['value'], strrpos($rule['value'], '[') + 1, -1);
					} else {
						$value = $rule['value'];
					}
				} else {
					$value = 1;
				}
				
				if (!isset($rule['comparison'])) $rule['comparison'] = '';
				if (in_array($rule['type'], array('attribute', 'custom_field', 'option'))) {
					$comparison = substr($rule['comparison'], strrpos($rule['comparison'], '[') + 1, -1);
				} else {
					$comparison = $rule['comparison'];
				}
				$rules[$rule['type']][$comparison][] = $value;
			}
			$this->charge['rules'] = $rules;
			
			// Perform settings overrides
			if (!empty($defaults)) {
				foreach ($defaults as $key => $value) {
					$this->config->set($key, $value);
				}
			}
			
			$defaults = array();
			
			if (isset($rules['setting_override'])) {
				foreach ($rules['setting_override'] as $setting => $override) {
					$defaults[$setting] = $this->config->get($setting);
					$this->config->set($setting, $override[0]);
					
					if ($setting == 'config_address') {
						$distance = 0;
					}
				}
			}
			
			// Check date/time criteria
			if ($this->ruleViolation('day', strtolower(date('l'))) ||
				$this->ruleViolation('date', date('Y-m-d')) ||
				$this->ruleViolation('time', date('H:i'))
			) {
				continue;
			}
			
			// Check discount criteria
			if (isset($rules['coupon'])) {
				$coupon_value = 0;
				if ($coupon) {
					foreach ($total_data as $ot) {
						if ($ot['code'] == 'coupon') $coupon_value = -$ot['value'];
					}
					if (!$coupon_value) {
						$temp_total_data = array();
						$temp_total = 1000000;
						$temp_taxes = $this->cart->getTaxes();
						$temp_totals = array(
							'totals'	=> &$temp_total_data,
							'total'		=> &$temp_total,
							'taxes'		=> &$temp_taxes,
						);
						
						if (version_compare(VERSION, '2.2', '<')) {
							$this->load->model('total/coupon');
							$this->model_total_coupon->getTotal($temp_total_data, $temp_total, $temp_taxes);
						} elseif (version_compare(VERSION, '2.3', '<')) {
							$this->load->model('total/coupon');
							$this->model_total_coupon->getTotal($temp_totals);
						} else {
							$this->load->model('extension/total/coupon');
							$this->model_extension_total_coupon->getTotal($temp_totals);
						}
						
						$coupon_value = 1000000 - $temp_total;
					}
				}
				foreach ($rules['coupon'] as $comparison => $rule_coupons) {
					if ($comparison == 'discount') {
						if (!$this->inRange($coupon_value, $rule_coupons, 'coupon value = ')) {
							continue 2;
						}
					} else {
						if (in_array('', $rule_coupons)) {
							if (($comparison == 'is' && !$coupon) || ($comparison == 'not' && $coupon)) {
								continue 2;
							}
						} else {
							if ($this->ruleViolation('coupon', $coupon)) {
								continue 2;
							}
						}
					}
				}
			}
			
			if (isset($rules['gift_voucher'])) {
				$voucher_value = 0;
				if ($voucher) {
					foreach ($total_data as $ot) {
						if ($ot['code'] == 'voucher') $voucher_value = -$ot['value'];
					}
					if (!$voucher_value) {
						$temp_total_data = array();
						$temp_total = 1000000;
						$temp_taxes = $this->cart->getTaxes();
						$temp_totals = array(
							'totals'	=> &$temp_total_data,
							'total'		=> &$temp_total,
							'taxes'		=> &$temp_taxes,
						);
						
						if (version_compare(VERSION, '2.2', '<')) {
							$this->load->model('total/voucher');
							$this->model_total_voucher->getTotal($temp_total_data, $temp_total, $temp_taxes);
						} elseif (version_compare(VERSION, '2.3', '<')) {
							$this->load->model('total/voucher');
							$this->model_total_voucher->getTotal($temp_totals);
						} else {
							$this->load->model('extension/total/voucher');
							$this->model_extension_total_voucher->getTotal($temp_totals);
						}
						
						$voucher_value = 1000000 - $temp_total;
					}
				}
				foreach ($rules['gift_voucher'] as $comparison => $rule_vouchers) {
					$in_range = $this->inRange($voucher_value, $rule_vouchers, 'gift voucher');
					
					if (($comparison == 'is' && !$in_range) || ($comparison == 'not' && $in_range)) {
						continue 2;
					}
				}
			}
			
			if (isset($rules['reward_points'])) {
				$cart_reward_points = 0;
				foreach ($cart_products as $product) {
					$cart_reward_points += $product['reward'];
				}
				foreach ($rules['reward_points'] as $comparison => $rule_reward_points) {
					if ($comparison == 'applied') {
						if (!$this->inRange($reward_points, $rule_reward_points, 'reward points ' . $comparison)) {
							continue 2;
						}
					} elseif ($comparison == 'products') {
						if (!$this->inRange($cart_reward_points, $rule_reward_points, 'reward points of ' . $comparison)) {
							continue 2;
						}
					} elseif ($comparison == 'customer') {
						if (!$this->inRange($reward_points_in_account, $rule_reward_points, 'reward points of ' . $comparison)) {
							continue 2;
						}
					}
				}
			}
			
			// Check location criteria
			if (isset($rules['location_comparison'])) {
				$location_comparison = $rules['location_comparison'][''][0];
			} else {
				$location_comparison = ($this->type == 'shipping' || empty($addresses['payment']['city'])) ? 'shipping' : 'payment';
			}
			$address = $addresses[$location_comparison];
			$postcode = $address['postcode'];
			
			if (isset($rules['address'])) {
				$this->commaMerge($rules['address']);
				$this->row['rules']['address'] = $rules['address'];
				
				$address_line_1 = strtolower($address['address_1']);
				
				foreach ($rules['address'] as $comparison => $values) {
					$skip_charge = ($comparison == 'is');
					$skip_message = '';
					
					foreach ($values as $value) {
						if (strpos($address_line_1, $value) !== false) {
							$skip_charge = ($comparison == 'not');
						} else {
							$skip_message = '"' . $this->charge['title'] . '" disabled for violating rule "address ' . $comparison . ' ' . $value . '"';
						}
					}
					
					if ($skip_charge) {
						$this->logMessage($skip_message);
						continue 2;
					}
				}
			}
			
			if (isset($rules['city'])) {
				$this->commaMerge($rules['city']);
				$this->charge['rules']['city'] = $rules['city'];
			}
			
			if ($this->ruleViolation('city', strtolower($address['city'])) ||
				$this->ruleViolation('country', $address['country_id']) ||
				$this->ruleViolation('geo_zone', $address['geo_zones']) ||
				$this->ruleViolation('zone', $address['zone_id'])
			) {
				continue;
			}
			
			if ((isset($rules['distance']) || $charge['type'] == 'distance') && !$distance) {
				$context = stream_context_create(array('http' => array('ignore_errors' => '1')));
				$store_address = html_entity_decode(preg_replace('/\s+/', '+', $this->config->get('config_address')), ENT_QUOTES, 'UTF-8');
				
				if (!empty($address['geocode'])) {
					$customer_address = $address['geocode'];
				} else {
					$customer_address = $address['address_1'] . ' ' . $address['address_2'] . ' ' . $address['city'] . ' ' . $address['zone'] . ' ' . $address['country'] . ' ' . $address['postcode'];
					$customer_address = html_entity_decode(preg_replace('/\s+/', '+', $customer_address), ENT_QUOTES, 'UTF-8');
				}
				
				if (isset($settings['distance_calculation']) && $settings['distance_calculation'] == 'driving') {
					$directions = json_decode(file_get_contents('http://maps.googleapis.com/maps/api/directions/json?origin=' . $store_address . '&destination=' . $customer_address, false, $context));
					if (empty($directions->routes)) {
						sleep(1);
						$directions = json_decode(file_get_contents('http://maps.googleapis.com/maps/api/directions/json?origin=' . $store_address . '&destination=' . $customer_address, false, $context));
						if (empty($directions->routes)) {
							$this->logMessage('The Google directions service returned the error "' . $directions->status . '" for origin "' . $store_address . '" and destination "' . $customer_address . '"');
							continue;
						}
					}
					$distance = $directions->routes[0]->legs[0]->distance->value / 1609.344;
				} else {
					if ($this->config->get('config_geocode')) {
						$xy = explode(',', $this->config->get('config_geocode'));
						$x1 = $xy[0];
						$y1 = $xy[1];
					} else {
						$geocode = json_decode(file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . $store_address, false, $context));
						if (empty($geocode->results)) {
							sleep(1);
							$geocode = json_decode(file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . $store_address, false, $context));
							if (empty($geocode->results)) {
								$this->logMessage('The Google geocoding service returned the error "' . $geocode->status . '" for address "' . $store_address . '"');
								continue;
							}
						}
						$x1 = $geocode->results[0]->geometry->location->lat;
						$y1 = $geocode->results[0]->geometry->location->lng;
					}
					
					if (!empty($address['geocode'])) {
						$xy = explode(',', $address['geocode']);
						$x2 = $xy[0];
						$y2 = $xy[1];
					} else {
						$geocode = json_decode(file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . $customer_address, false, $context));
						if (empty($geocode->results)) {
							sleep(1);
							$geocode = json_decode(file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . $customer_address, false, $context));
							if (empty($geocode->results)) {
								$this->logMessage('The Google geocoding service returned the error "' . $geocode->status . '" for address "' . $customer_address . '"');
								continue;
							}
						}
						$x2 = $geocode->results[0]->geometry->location->lat;
						$y2 = $geocode->results[0]->geometry->location->lng;
					}
					
					$distance = rad2deg(acos(sin(deg2rad($x1)) * sin(deg2rad($x2)) + cos(deg2rad($x1)) * cos(deg2rad($x2)) * cos(deg2rad($y1 - $y2)))) * 60 * 114 / 99;
				}
				
				if (isset($settings['distance_units']) && $settings['distance_units'] == 'km') {
					$distance *= 1.609344;
				}
				$this->logMessage('Calculated distance between ' . $store_address . ' and ' . $customer_address . ' = ' . round($distance, 3) . ' ' . $settings['distance_units']);
			}
			
			if (isset($rules['distance'])) {
				$this->commaMerge($rules['distance']);
				
				foreach ($rules['distance'] as $comparison => $distances) {
					$in_range = $this->inRange($distance, $distances, 'distance' . ($comparison == 'not' ? ' not' : ''));
					
					if (($comparison == 'is' && !$in_range) || ($comparison == 'not' && $in_range)) {
						continue 2;
					}
				}
			}
			
			if (isset($rules['postcode'])) {
				$this->commaMerge($rules['postcode']);
				
				foreach ($rules['postcode'] as $comparison => $postcodes) {
					$in_range = $this->inRange($address['postcode'], $postcodes, 'postcode' . ($comparison == 'not' ? ' not' : ''));
					
					if (($comparison == 'is' && !$in_range) || ($comparison == 'not' && $in_range)) {
						continue 2;
					}
				}
			}
			
			// Check order criteria
			if ($this->ruleViolation('currency', $currency) ||
				$this->ruleViolation('customer', $customer_id) ||
				$this->ruleViolation('customer_group', $customer_group_id) ||
				$this->ruleViolation('language', $language) ||
				$this->ruleViolation('payment_extension', $payment_method) ||
				$this->ruleViolation('shipping_extension', $shipping_method) ||
				$this->ruleViolation('store', $store_id)
			) {
				continue;
			}
			
			if (isset($rules['custom_field'])) {
				$this->commaMerge($rules['custom_field']);
				
				$custom_fields = $customer_custom_fields;
				if (!empty($address['custom_field'])) {
					$custom_fields += $address['custom_field'];
				}
				
				foreach ($rules['custom_field'] as $comparison => $values) {
					foreach ($custom_fields as $custom_field_id => $custom_field_value) {
						$custom_field_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_field_value_description WHERE custom_field_id = " . (int)$custom_field_id . " AND custom_field_value_id = " . (int)$custom_field_value);
						if ($custom_field_value_query->num_rows) {
							$custom_field_value = $custom_field_value_query->row['name'];
						}
						if ($comparison == $custom_field_id && in_array(strtolower($custom_field_value), $values)) {
							continue 2;
						}
					}
					
					$this->logMessage('"' . $this->charge['title'] . '" disabled for violating rule "custom_field_id ' . $comparison . ' = ' . implode(', ', $values) . '"');
					continue 2;
				}
			}
			
			if (isset($rules['past_orders'])) {
				$this->commaMerge($rules['past_orders']);
				
				$days_sql = "";
				$order_status_sql = " AND o.order_status_id > 0";
				$product_sql = "";
				$total_table = "o.";
				
				foreach ($rules['past_orders'] as $comparison => $values) {
					if ($comparison == 'days') {
						$value = array_pop($values);
						$days = explode('-', $value);
						$days_sql = " AND o.date_added <= (CURDATE() - INTERVAL " . ($days[0] - 1) . " DAY)";
						if (isset($days[1])) $days_sql .= " AND o.date_added >= (CURDATE() - INTERVAL " . $days[1] . " DAY)";
					}
					
					$values = array_map('intval', $values);
					
					if ($comparison == 'manufacturer') {
						$manufacturer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE (manufacturer_id = " . implode(" OR manufacturer_id = ", $values) . ")");
						$product_ids = array();
						foreach ($manufacturer_query->rows as $row) {
							$product_ids[] = (int)$row['product_id'];
						}
						$product_sql .= " AND (op.product_id = " . implode(" OR op.product_id = ", $product_ids) . ")";
						$total_table = "op.";
					}
					
					if ($comparison == 'order_status') {
						$order_status_sql = " AND (o.order_status_id = " . implode(" OR o.order_status_id = ", $values) . ")";
					}
					
					if ($comparison == 'product') {
						$product_sql .= " AND (op.product_id = " . implode(" OR op.product_id = ", $values) . ")";
						$total_table = "op.";
					}
				}
				
				$past_orders_query = $this->db->query("SELECT IFNULL(MIN(ROUND((UNIX_TIMESTAMP() - UNIX_TIMESTAMP(o.date_added)) / 86400)), 0) AS days, IFNULL(COUNT(*), 0) AS quantity, IFNULL(AVG(" . $total_table . "total), 0) AS average, IFNULL(SUM(" . $total_table . "total), 0) AS total FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) WHERE o.customer_id = " . (int)$customer_id . " AND o.customer_id != 0 " . $days_sql . $order_status_sql . $product_sql);
				
				foreach ($rules['past_orders'] as $comparison => $values) {
					if ($comparison == 'manufacturer' || $comparison == 'order_status' || $comparison == 'product') {
						continue;
					}
					if ($comparison == 'order_amount') {
						$skip = true;
						$single_orders_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` o WHERE o.customer_id = " . (int)$customer_id . " AND o.customer_id != 0 " . $days_sql . $order_status_sql);
						
						foreach ($single_orders_query->rows as $order) {
							$order_query = $this->db->query("SELECT SUM(op.total) AS order_amount FROM " . DB_PREFIX . "order_product op WHERE op.order_id = " . (int)$order['order_id'] . $product_sql);
							if ($this->inRange($order_query->row[$comparison], $values, 'past order ' . $comparison, true)) {
								$skip = false;
								break;
							}
						}
						
						if ($skip) {
							continue 2;
						}
					} elseif (!$this->inRange($past_orders_query->row[$comparison], $values, 'past order ' . $comparison)) {
						continue 2;
					}
				}
			}
			
			if (isset($rules['shipping_rate'])) {
				$this->commaMerge($rules['shipping_rate']);
				$is_rule_passed = empty($rules['shipping_rate']['is']);
				$not_rule_violation = false;
				$skip_message = '';
				
				foreach ($rules['shipping_rate'] as $comparison => $values) {
					foreach ($values as $value) {
						if ($comparison == 'is') {
							if (strpos($shipping_rate, $value) !== false) {
								$is_rule_passed = true;
							} else {
								$skip_message = '"' . $this->charge['title'] . '" disabled for violating rule "shipping_rate ' . $comparison . ' ' . $value . '"';
							}
						}
						if ($comparison == 'not') {
							if (strpos($shipping_rate, $value) !== false) {
								$not_rule_violation = true;
								$skip_message = '"' . $this->charge['title'] . '" disabled for violating rule "shipping_rate ' . $comparison . ' ' . $value . '"';
							}
						}
					}
				}
				
				if (!$is_rule_passed || $not_rule_violation) {
					$this->logMessage($skip_message);
					continue;
				}
			}
			
			// Generate comparison values
			$cart_criteria = array(
				'length',
				'width',
				'height',
				'quantity',
				'stock',
				'total',
				'volume',
				'weight',
			);
			
			foreach ($cart_criteria as $spec) {
				${$spec.'s'} = array();
				if (isset($rules[$spec])) {
					$this->commaMerge($rules[$spec]);
				}
			}
			
			$attributes = array();
			$attribute_groups = array();
			$attribute_values = array();
			$categorys = array();
			$manufacturers = array();
			$options = array();
			$option_values = array();
			$option_array = array();
			$products = array();
			
			$other_product_data_charges = array();
			$product_keys = array();
			$total_value = $cumulative_total_value;
			
			foreach ($cart_products as $product) {
				if ($this->type == 'shipping' && !$product['shipping']) {
					$total_value -= $product['total'];
					$this->logMessage($product['name'] . ' (product_id: ' . $product['product_id'] . ') does not require shipping and was ignored');
					continue;
				}
				
				$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id = " . (int)$product['product_id']);
				
				// dimensions
				$length_class_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "length_class WHERE length_class_id = " . (int)$product['length_class_id']);
				if ($length_class_query->num_rows) {
					$lengths[$product['key']] = $this->length->convert($product['length'], $product['length_class_id'], $this->config->get('config_length_class_id'));
					$widths[$product['key']] = $this->length->convert($product['width'], $product['length_class_id'], $this->config->get('config_length_class_id'));
					$heights[$product['key']] = $this->length->convert($product['height'], $product['length_class_id'], $this->config->get('config_length_class_id'));
				} else {
					$message = $product['name'] . ' (product_id: ' . $product['product_id'] . ') does not have a valid length class, which causes a "Division by zero" error, and means it cannot be used for dimension/volume calculations. You can fix this by re-saving the product data.';
					$this->log->write($message);
					$this->logMessage($message);
					
					$lengths[$product['key']] = 0;
					$widths[$product['key']] = 0;
					$heights[$product['key']] = 0;
				}
				
				// stock
				$stocks[$product['key']] = $product_query->row['quantity'] - $product['quantity'];
				
				// quantity
				$quantitys[$product['key']] = $product['quantity'];
				
				// total
				if (isset($rules['total_value'])) {
					$product_info = $this->model_catalog_product->getProduct($product['product_id']);
					$product_price = ($product_info['special']) ? $product_info['special'] : $product_info['price'];
					
					if (in_array('prediscounted', $rules['total_value'][''])) {
						$totals[$product['key']] = $product['total'] + ($product['quantity'] * ($product_query->row['price'] - $product_price));
					} elseif (in_array('nondiscounted', $rules['total_value'][''])) {
						$totals[$product['key']] = ($product_info['special']) ? 0 : $product['total'];
					} elseif (in_array('taxed', $rules['total_value'][''])) {
						$totals[$product['key']] = $this->tax->calculate($product['total'], $product['tax_class_id']);
					}
				}
				if (!isset($totals[$product['key']])) {
					$totals[$product['key']] = $product['total'];
				}
				
				// volume
				$volumes[$product['key']] = $lengths[$product['key']] * $widths[$product['key']] * $heights[$product['key']] * $product['quantity'];
				
				// weight
				$weight_class_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class WHERE weight_class_id = " . (int)$product['weight_class_id']);
				if ($weight_class_query->num_rows) {
					$weights[$product['key']] = $this->weight->convert($product['weight'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));
				} else {
					$message = $product['name'] . ' (product_id: ' . $product['product_id'] . ') does not have a valid weight class, which causes a "Division by zero" error, and means it cannot be used for weight calculations. You can fix this by re-saving the product data.';
					$this->log->write($message);
					$this->logMessage($message);
					
					$weights[$product['key']] = 0;
				}
				
				// attributes
				$attribute_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute a LEFT JOIN " . DB_PREFIX . "product_attribute pa ON (pa.attribute_id = a.attribute_id) WHERE pa.product_id = " . (int)$product['product_id']);
				if ($attribute_query->num_rows) {
					foreach ($attribute_query->rows as $attribute) {
						$attributes[$product['key']][] = $attribute['attribute_id'];
						$attribute_groups[$product['key']][] = $attribute['attribute_group_id'];
						foreach (explode(',', $attribute['text']) as $attribute_value) {
							$attribute_values[$product['key']][$attribute['attribute_id']][] = trim($attribute_value);
						}
					}
				} else {
					$attributes[$product['key']][] = 0;
					$attribute_groups[$product['key']][] = 0;
					$attribute_values[$product['key']][0][] = 0;
				}
				
				// categories
				$category_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = " . (int)$product['product_id']);
				if ($category_query->num_rows) {
					foreach ($category_query->rows as $category) {
						$categorys[$product['key']][] = $category['category_id'];
					}
				} else {
					$categorys[$product['key']][] = 0;
				}
				
				// manufacturer
				$manufacturers[$product['key']][] = $product_query->row['manufacturer_id'];
				
				// options
				if (!empty($product['option'])) {
					foreach ($product['option'] as $option) {
						$options[$product['key']][] = $option['option_id'];
						$option_values[$product['key']][] = $option['option_value_id'];
						$option_array[$product['key']][$option['option_id']][] = (version_compare(VERSION, '2.0') < 0) ? $option['option_value'] : $option['value'];
					}
				} else {
					$options[$product['key']][] = 0;
					$option_values[$product['key']][] = 0;
					$option_array[$product['key']][0][] = 0;
				}
				
				// products
				$products[$product['key']][] = $product['product_id'];
				
				// Check item criteria (entire cart comparisons)
				foreach ($cart_criteria as $spec) {
					if (isset($rules['adjust']['item_' . $spec])) {
						foreach ($rules['adjust']['item_' . $spec] as $adjustment) {
							${$spec.'s'}[$product['key']] += (strpos($adjustment, '%')) ? ${$spec.'s'}[$product['key']] * (float)$adjustment / 100 : (float)$adjustment;
						}
					}
					
					if (isset($rules[$spec]['entire_any'])) {
						if (!$this->inRange(${$spec.'s'}[$product['key']], $rules[$spec]['entire_any'], $spec . ' of any item in entire cart', true)) {
							continue 2;
						}
					}
					
					if (isset($rules[$spec]['entire_every'])) {
						if (!$this->inRange(${$spec.'s'}[$product['key']], $rules[$spec]['entire_every'], $spec . ' of every item in entire cart', true)) {
							continue 3;
						}
					}
				}
				
				// Check product criteria
				if (isset($rules['attribute'])) {
					$this->commaMerge($rules['attribute']);
					
					foreach ($rules['attribute'] as $attribute_id => $values) {
						$attribute_rule_text = 'attribute_id ' . $attribute_id . ' = ' . implode(', ', $values);
						if (empty($values[0]) && isset($attribute_values[$product['key']][$attribute_id])) {
							continue;
						} elseif (isset($attribute_values[$product['key']][$attribute_id])) {
							foreach ($attribute_values[$product['key']][$attribute_id] as $attribute_value) {
								if ($this->inRange(strtolower($attribute_value), $values, 'attribute', true)) {
									continue 2;
								}
							}
						}
						$this->logMessage('Product "' . $product['name'] . ' (product_id: ' . $product['product_id'] . ') is not eligible for charge "' . $this->charge['title'] . '" because it violates rule "' . $attribute_rule_text . '"');
						continue 2;
					}
				}
				
				foreach (array('attribute_group', 'category') as $criteria) {
					if (isset($rules[$criteria])) {
						if ($this->ruleViolation($criteria, ${$criteria . 's'}[$product['key']])) {
							continue 2;
						}
					}
				}
				
				if (isset($rules['option'])) {
					$this->commaMerge($rules['option']);
					
					foreach ($rules['option'] as $option_id => $values) {
						$option_rule_text = 'option_id ' . $option_id . ' = ' . implode(', ', $values);
						if (empty($values[0]) && isset($option_array[$product['key']][$option_id])) {
							continue;
						} elseif (isset($option_array[$product['key']][$option_id])) {
							foreach ($option_array[$product['key']][$option_id] as $option_value) {
								if ($this->inRange(strtolower($option_value), $values, 'option', true)) {
									continue 2;
								}
							}
						}
						$this->logMessage('Product "' . $product['name'] . ' (product_id: ' . $product['product_id'] . ') is not eligible for charge "' . $this->charge['title'] . '" because it violates rule "' . $option_rule_text . '"');
						continue 2;
					}
				}
				
				if (isset($rules['manufacturer']) && $this->ruleViolation('manufacturer', $product_query->row['manufacturer_id'])) {
					continue;
				}
				
				if (isset($rules['product']) && $this->ruleViolation('product', $product['product_id'])) {
					continue;
				}
				
				// Check item criteria (eligible item comparisons)
				foreach ($cart_criteria as $spec) {
					if (isset($rules[$spec]['any'])) {
						if (!$this->inRange(${$spec.'s'}[$product['key']], $rules[$spec]['any'], $spec . ' of any item', true)) {
							continue 2;
						}
					}
					
					if (isset($rules[$spec]['every'])) {
						if (!$this->inRange(${$spec.'s'}[$product['key']], $rules[$spec]['every'], $spec . ' of every item', true)) {
							continue 3;
						}
					}
				}
				
				// Check other product data
				if (isset($rules['other_product_data'])) {
					$this->commaMerge($rules['other_product_data']);
					foreach ($rules['other_product_data'] as $comparison => $values) {
						if ($values[0] == '') {
							if ($charge['type'] == 'flat') {
								$other_product_data_charges[] = (float)$product_query->row[$comparison];
							} elseif ($charge['type'] == 'peritem') {
								$other_product_data_charges[] = (float)($product_query->row[$comparison] * $product['quantity']);
							} else {
								$brackets = array_filter(explode(',', $product_query->row[$comparison]));
								$other_product_data_charges[] = (float)$this->calculateBrackets($brackets, $charge['type'], ${$charge['type'].'s'}[$product['key']], $product['quantity'], $product['total']);
							}
							continue;
						}
						if (!$this->inRange(strtolower($product_query->row[$comparison]), $values, 'other product data')) {
							continue 2;
						}
					}
				}
				
				// product passed all rules and is eligible for charge
				$product_keys[] = $product['key'];
			}
			
			// Check product group rules
			$row_disabled_text = '"' . $this->charge['title'] . '" disabled';
			
			if (isset($rules['product_group'])) {
				$list_types = array(
					'attribute',
					'attribute_group',
					'category',
					'manufacturer',
					'option',
					'option_value',
					'product',
				);
				
				foreach ($list_types as $list_type) {
					${$list_type . 's_array'} = array();
					foreach (${$list_type . 's'} as $list) {
						${$list_type . 's_array'} = array_merge(${$list_type . 's_array'}, $list);
					}
				}
				
				$eligible_products = array();
				$ineligible_products = array();
				
				foreach ($rules['product_group'] as $comparison => $product_group_ids) {
					$rule_satisfied = false;
					
					foreach ($product_group_ids as $product_group_id) {
						if (empty($settings['product_group'][$product_group_id]['member'])) continue;
						
						$product_group_rule_text = 'cart has items from ' . ($comparison == 'none' ? 'none of the' : $comparison) . ' members of ' . $settings['product_group'][$product_group_id]['name'];
						unset($members_array);
						
						foreach ($settings['product_group'][$product_group_id]['member'] as $member) {
							$bracket = strrpos($member, '[');
							$colon = strrpos($member, ':');
							$member_type = substr($member, $bracket + 1, $colon - $bracket - 1);
							$member_id = substr($member, $colon + 1, -1);
							$members_array[$member_type][] = $member_id;
							
							if ($member_type == 'category' && $settings['product_group'][$product_group_id]['subcategories']) {
								$child_category_ids = $this->getChildCategoryIds($member_id);
								foreach ($child_category_ids as $child_category_id) {
									$members_array[$member_type][] = $child_category_id;
								}
							}
						}
						
						foreach ($members_array as $type => $members) {
							// Check "all" and "onlyall" comparisons
							if (($comparison == 'all' || $comparison == 'onlyall') && array_diff($members, ${$type.'s_array'})) {
								$this->logMessage($row_disabled_text . ' for violating product group rule "' . $product_group_rule_text . '", due to missing ' . $type . '_id(s) "' . implode(', ', array_diff($members, ${$type.'s_array'})) . '"');
								continue 4;
							}
							
							// Check product eligibility
							foreach ($cart_products as $product) {
								if ($this->type == 'shipping' && !$product['shipping']) {
									continue;
								}
								
								if ($type == 'category') {
									if (($comparison == 'onlyany' || $comparison == 'onlyall') && array_intersect(${$type.'s'}[$product['key']], $members)) {
										$rule_satisfied = true;
										$eligible_products[] = $product['key'];
										continue;
									}
									if ($comparison == 'not' && array_intersect(${$type.'s'}[$product['key']], $members)) {
										$ineligible_products[] = $product['key'];
										continue;
									}
								}
								
								if ((($comparison == 'onlyany' || $comparison == 'onlyall') && array_diff(${$type.'s'}[$product['key']], $members)) ||
									($comparison == 'none' && array_intersect(${$type.'s'}[$product['key']], $members))
								) {
									$this->logMessage($row_disabled_text . ' for violating product group rule "' . $product_group_rule_text . '"');
									continue 5;
								} elseif (($comparison != 'not' && $comparison != 'none' && !array_intersect(${$type.'s'}[$product['key']], $members)) ||
									(($comparison == 'not' || $comparison == 'none') && !array_diff(${$type.'s'}[$product['key']], $members))
								) {
									$ineligible_products[] = $product['key'];
								} else {
									$rule_satisfied = true;
									$eligible_products[] = $product['key'];
								}
							}
						}
					}
					
					// Check that rule has at least one matching product
					if (!$rule_satisfied) {
						$this->logMessage($row_disabled_text . ' for having no eligible products');
						continue 2;
					}
				}
				
				// Remove ineligible products
				foreach ($ineligible_products as $ineligible_key) {
					if (in_array($ineligible_key, $eligible_products)) continue;
					foreach ($product_keys as $index => $product_key) {
						if ($product_key == $ineligible_key) unset($product_keys[$index]);
					}
				}
			}
			
			// Check for empty product list
			if (empty($product_keys)) {
				$disable_charge = true;
				
				if (!empty($this->session->data['vouchers'])) {
					$disable_charge = false;
					foreach ($rules as $type => $value) {
						if (in_array($type, array('attribute', 'attribute_group', 'category', 'manufacturer', 'option', 'product', 'product_group', 'other_product_data'))) {
							$disable_charge = true;
						}
					}
				}
				
				if ($disable_charge) {
					$this->logMessage($row_disabled_text . ' for having no eligible products');
					continue;
				}
			}
			
			// Check cart criteria and generate total comparison values
			$single_foreign_currency = (isset($rules['currency']['is']) && count($rules['currency']['is']) == 1 && $default_currency != $currency) ? $rules['currency']['is'][0] : '';
			
			foreach ($cart_criteria as $spec) {
				// note: cart_comparison to be added here if requested
				if ($spec == 'total' && isset($rules['total_value']) && in_array('shipping_cost', $rules['total_value'][''])) {
					$total = $shipping_cost;
					$cart_total = $shipping_cost;
				} elseif ($spec == 'total' && isset($rules['total_value']) && in_array('total', $rules['total_value'][''])) {
					$total = $total_value;
					$cart_total = $total_value;
				} else {
					${$spec} = 0;
					foreach ($product_keys as $product_key) {
						${$spec} += ${$spec.'s'}[$product_key];
					}
					${'cart_'.$spec} = array_sum(${$spec.'s'});
				}
				
				if ($spec == 'total' && $single_foreign_currency) {
					$total = $this->currency->convert($total, $default_currency, $single_foreign_currency);
				}
				
				if (isset($rules['adjust']['cart_' . $spec])) {
					foreach ($rules['adjust']['cart_' . $spec] as $adjustment) {
						${$spec} += (strpos($adjustment, '%')) ? ${$spec} * (float)$adjustment / 100 : (float)$adjustment;
						${'cart_'.$spec} += (strpos($adjustment, '%')) ? ${'cart_'.$spec} * (float)$adjustment / 100 : (float)$adjustment;
					}
				}
				
				if (isset($rules[$spec]['cart'])) {
					if (!$this->inRange(${$spec}, $rules[$spec]['cart'], $spec . ' of cart')) {
						continue 2;
					}
				}
				
				if (isset($rules[$spec]['entire_cart'])) {
					if (!$this->inRange(${'cart_'.$spec}, $rules[$spec]['entire_cart'], $spec . ' of entire cart')) {
						continue 2;
					}
				}
			}
			
			// Calculate the charge
			$rate_found = false;
			$brackets = (!empty($charge['charges'])) ? array_filter(explode(',', str_replace(array("\n", ',,'), ',', $charge['charges']))) : array(0);
			
			if ($charge['type'] == 'flat') {
				
				$cost = (strpos($charge['charges'], '%')) ? $total * (float)$charge['charges'] / 100 : (float)$charge['charges'];
				$rate_found = true;
				
			} elseif ($charge['type'] == 'peritem') {
				
				$cost = (strpos($charge['charges'], '%')) ? $total * (float)$charge['charges'] / 100 : (float)$charge['charges'] * $quantity;
				$rate_found = true;
				
			} elseif ($charge['type'] == 'price') {
				
				$cost = 0;
				$rate_found = false;
				
				foreach ($cart_products as $product) {
					if (!in_array($product['key'], $product_keys)) continue;
					
					$product_cost = $this->calculateBrackets($brackets, $charge['type'], $product['price'], $product['quantity'], $product['price']);
					
					if ($product_cost !== false) {
						$cost += $product_cost;
						$rate_found = true;
					}
				}
				
			} elseif (in_array($charge['type'], array('distance', 'postcode', 'quantity', 'total', 'volume', 'weight'))) {
				
				$percentage_total = $total;
				if (isset($rules['total_value']) && in_array('cheapest', $rules['total_value'][''])) {
					foreach ($totals as $key => $value) {
						if (in_array($key, $product_keys) && $value > 0 && $value <= $percentage_total) {
							$percentage_total = $value;
							//$percentage_total = $value / $quantitys[$key];
						}
					}
				}
				
				$cost = $this->calculateBrackets($brackets, $charge['type'], ${$charge['type']}, $quantity, $percentage_total);
				if ($cost !== false) {
					$rate_found = true;
				}
				
			} else {
				
				$product_quantities = array();
				foreach ($product_keys as $product_key) {
					$product_quantities[$product_key] = $quantitys[$product_key];
				}
				
				$shipping_rates = $this->getShippingRates(str_replace('extension_', '', $charge['type']), $product_quantities, $address, isset($rules['setting_override']) ? $rules['setting_override'] : array());
				
				$shipping_rates_found = array();
				foreach ($shipping_rates['quote'] as $shipping_rate) {
					$shipping_rates_found[] = $shipping_rate['title'] . ' (' . $this->currency->format($shipping_rate['cost'], $currency) . ')';
				}
				$this->logMessage('"' . $this->charge['title'] . '" found ' . strtoupper(str_replace('extension_', '', $charge['type'])) . ' rates "' . implode(', ', $shipping_rates_found) . '"');
				
				foreach ($brackets as $bracket) {
					foreach ($shipping_rates['quote'] as $shipping_rate) {
						if (!$bracket || strpos($shipping_rate['title'], trim($bracket)) !== false) {
							$cost = $shipping_rate['cost'];
							$rate_found = true;
							
							$delivery_estimate = explode('(', $shipping_rate['title']);
							if (isset($delivery_estimate[1])) {
								$charge['title_' . $language] .= ' (' . $delivery_estimate[1];
							}
							
							break 2;
						}
					}
				}
				
			}
			
			if (!empty($other_product_data_charges)) {
				$cost = array_sum($other_product_data_charges);
				$rate_found = true;
			}
			
			if (!$rate_found) {
				$this->logMessage('"' . $this->charge['title'] . '" disabled for not matching any brackets in the list "' . implode(', ', $brackets) . '"');
				continue;
			}
			
			// Adjust charge
			if (isset($rules['adjust']['charge'])) {
				foreach ($rules['adjust']['charge'] as $adjustment) {
					$cost += (strpos($adjustment, '%')) ? $cost * (float)$adjustment / 100 : (float)$adjustment;
				}
			}
			if (isset($rules['round'])) {
				foreach ($rules['round'] as $comparison => $values) {
					$round = $values[0];
					if ($comparison == 'nearest') {
						$cost = round($cost / $round) * $round;
					} elseif ($comparison == 'up') {
						$cost = ceil($cost / $round) * $round;
					} elseif ($comparison == 'down') {
						$cost = floor($cost / $round) * $round;
					}
				}
			}
			if (isset($rules['min'])) {
				$cost = max($cost, $rules['min'][''][0]);
			}
			if (isset($rules['max'])) {
				$cost = min($cost, $rules['max'][''][0]);
			}
			if ($single_foreign_currency) {
				$cost = $this->currency->convert($cost, $single_foreign_currency, $default_currency);
			}
			
			// Add to charge array
			$this->logMessage('ENABLED "' . $this->charge['title'] . '" with cost ' . (float)$cost);
			
			
			$replace = array('[distance]', '[postcode]', '[quantity]', '[total]', '[volume]', '[weight]');
			$with = array(round($distance, 2), $postcode, round($quantity, 2), round($total, 2), round($volume, 2), round($weight, 2));
			
			$charges[strtolower($charge['group'])][] = array(
				'title'			=> str_replace($replace, $with, html_entity_decode($charge['title_' . $language], ENT_QUOTES, 'UTF-8')),
				'charge'		=> (float)$cost,
				'tax_class_id'	=> isset($rules['tax_class']) ? $rules['tax_class'][''][0] : $settings['tax_class_id'],
			);
			
			if ($this->type != 'shipping') {
				$cumulative_total_value += (float)$cost;
			}
			
			// Restore setting defaults
			foreach ($defaults as $key => $value) {
				$this->config->set($key, $value);
			}
			
		} // end charge loop
		
		// Combine charges
		$quote_data = array();
		
		if (empty($settings['combination']) || empty($settings['combination'][key($settings['combination'])]['formula'])) {
			
			foreach ($charges as $group_value => $group) {
				foreach ($group as $rate) {
					if (($this->type == 'shipping' && $rate['charge'] < 0) || ($this->type == 'total' && $rate['charge'] == 0)) continue;
					
					$taxed_charge = $this->tax->calculate($rate['charge'], $rate['tax_class_id'], $this->config->get('config_tax'));
					
					$quote_data[$this->name . '_' . count($quote_data)] = array(
						'code'			=> $this->name . '.' . $this->name . '_' . count($quote_data),
						'sort_order'	=> $group_value,
						'title'			=> $rate['title'],
						'cost'			=> $rate['charge'],
						'value'			=> $rate['charge'],
						'tax_class_id'	=> $rate['tax_class_id'],
						'text'			=> $this->currency->format($this->type == 'total' ? $rate['charge'] : $taxed_charge, $currency),
					);
				}
			}
			
		} elseif (!empty($charges)) {
			
			foreach ($settings['combination'] as $combination) {
				if (empty($combination['formula'])) continue;
				
				if (!empty($combination['groups_required'])) {
					foreach (explode(',', strtolower($combination['groups_required'])) as $group) {
						if (!in_array(trim($group), array_keys($charges))) {
							continue 2;
						}
					}
				}
				
				$tax_class_id = 0;
				foreach ($charges as $group) {
					foreach ($group as $rate) {
						$tax_class_id = max($tax_class_id, $rate['tax_class_id']);
					}
				}
				
				$titles = array();
				$formula_array = preg_split('/[\(,\)]/', str_replace(' ', '', strtolower($combination['formula'])));
				
				$current_function = '';
				$current_title = '';
				$current_charge = '';
				
				foreach ($formula_array as $piece) {
					if (empty($piece)) {
						if ($combination['title'] != 'combined_prices' || empty($current_title)) {
							$titles[] = $current_title;
						} else {
							$titles[] = $current_title . ' (' . $this->currency->format($this->tax->calculate($current_charge, $tax_class_id, $this->config->get('config_tax')), $currency) . ')';
						}
						$current_function = '';
						$current_title = '';
						$current_charge = '';
					}
					if (in_array($piece, array('sum', 'max', 'min', 'avg'))) {
						$current_function = $piece;
					}
					if (empty($charges[$piece])) {
						continue;
					}
					if ($current_function == 'max' || $current_function == 'min') {
						foreach ($charges[$piece] as $rate) {
							if ($current_charge === '' || ($current_function == 'max' && $rate['charge'] >= $current_charge) || ($current_function == 'min' && $rate['charge'] <= $current_charge)) {
								$current_title = $rate['title'];
								$current_charge = $rate['charge'];
							}
						}
					} else {
						if (empty($combination['title']) || $combination['title'] == 'single') {
							$titles = array($charges[$piece][0]['title']);
						} else {
							foreach ($charges[$piece] as $rate) {
								if ($combination['title'] == 'combined') {
									$titles[] = $rate['title'];
								} else {
									$titles[] = $rate['title'] . ' (' . $this->currency->format($this->tax->calculate($rate['charge'], $tax_class_id, $this->config->get('config_tax')), $currency) . ')';
								}
							}
						}
					}
				}
				
				$i = 0;
				$cost = $this->calculateFormula($charges, $formula_array, $i);
				$taxed_charge = $this->tax->calculate($cost, $tax_class_id, $this->config->get('config_tax'));
				
				if ($cost === false || ($this->type == 'shipping' && $cost < 0) || ($this->type == 'total' && $cost == 0)) continue;
				
				$quote_data[$this->name . '_' . count($quote_data)] = array(
					'code'			=> $this->name . '.' . $this->name . '_' . count($quote_data),
					'sort_order'	=> (isset($combination['sort_order']) ? $combination['sort_order'] : 0),
					'title'			=> implode(' + ', array_filter($titles)),
					'cost'			=> $cost,
					'value'			=> $cost,
					'tax_class_id'	=> $tax_class_id,
					'text'			=> $this->currency->format($this->type == 'total' ? $cost : $taxed_charge, $currency),
				);
			}
			
		}
		
		$sort_order = array();
		foreach ($quote_data as $key => $value) $sort_order[$key] = $value['sort_order'];
		array_multisort($sort_order, SORT_ASC, $quote_data);
		
		foreach ($quote_data as $quote) {
			$quote['code'] = $this->name;
			$quote['sort_order'] = $settings['sort_order'];
			
			$total_data[] = $quote;
			
			if ($quote['tax_class_id']) {
				foreach ($this->tax->getRates($quote['cost'], $quote['tax_class_id']) as $tax_rate) {
					$taxes[$tax_rate['tax_rate_id']] = (isset($taxes[$tax_rate['tax_rate_id']])) ? $taxes[$tax_rate['tax_rate_id']] + $tax_rate['amount'] : $tax_rate['amount'];
				}
			}
			
			$order_total += $quote['cost'];
		}
		
		if ($this->type == 'shipping' && $quote_data) {
			$replace = array('[distance]', '[postcode]', '[quantity]', '[total]', '[volume]', '[weight]');
			$with = array(round($distance, 2), $postcode, round($cart_quantity, 2), round($cart_total, 2), round($cart_volume, 2), round($cart_weight, 2));
			
			return array(
				'code'			=> $this->name,
				'title'			=> str_replace($replace, $with, html_entity_decode($settings['heading_' . $language], ENT_QUOTES, 'UTF-8')),
				'quote'			=> $quote_data,
				'sort_order'	=> $settings['sort_order'],
				'error'			=> false
			);
		} else {
			return array();
		}
	}
	
	//------------------------------------------------------------------------------
	// Private functions
	//------------------------------------------------------------------------------
	private function getSettings() {
		$settings = array();
		$settings_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `" . (version_compare(VERSION, '2.0.1', '<') ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "' ORDER BY `key` ASC");
		
		foreach ($settings_query->rows as $setting) {
			$value = $setting['value'];
			if ($setting['serialized']) {
				$value = (version_compare(VERSION, '2.1', '<')) ? unserialize($setting['value']) : json_decode($setting['value'], true);
			}
			$split_key = preg_split('/_(\d+)_?/', str_replace($this->name . '_', '', $setting['key']), -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
			
				if (count($split_key) == 1)	$settings[$split_key[0]] = $value;
			elseif (count($split_key) == 2)	$settings[$split_key[0]][$split_key[1]] = $value;
			elseif (count($split_key) == 3)	$settings[$split_key[0]][$split_key[1]][$split_key[2]] = $value;
			elseif (count($split_key) == 4)	$settings[$split_key[0]][$split_key[1]][$split_key[2]][$split_key[3]] = $value;
			else 							$settings[$split_key[0]][$split_key[1]][$split_key[2]][$split_key[3]][$split_key[4]] = $value;
		}
		
		return $settings;
	}
	
	private function logMessage($message) {
		if ($this->testing_mode) {
			file_put_contents(DIR_LOGS . $this->name . '.messages', print_r($message, true) . "\n", FILE_APPEND|LOCK_EX);
		}
	}
	
	private function commaMerge(&$rule) {
		$merged_rule = array();
		foreach ($rule as $comparison => $values) {
			$merged_rule[$comparison] = array();
			foreach ($values as $value) {
				$merged_rule[$comparison] = array_merge($merged_rule[$comparison], array_map('trim', explode(',', strtolower($value))));
			}
		}
		$rule = $merged_rule;
	}
	
	private function ruleViolation($rule, $value) {
		$violation = false;
		$rules = $this->charge['rules'];
		$function = (is_array($value)) ? 'array_intersect' : 'in_array';
		
		if (isset($rules[$rule]['after']) && strtotime($value) < min(array_map('strtotime', $rules[$rule]['after']))) {
			$violation = true;
			$comparison = 'after';
		}
		if (isset($rules[$rule]['before']) && strtotime($value) > max(array_map('strtotime', $rules[$rule]['before']))) {
			$violation = true;
			$comparison = 'before';
		}
		if (isset($rules[$rule]['is']) && !$function($value, $rules[$rule]['is'])) {
			$violation = true;
			$comparison = 'is';
		}
		if (isset($rules[$rule]['not']) && $function($value, $rules[$rule]['not'])) {
			$violation = true;
			$comparison = 'not';
		}
		
		if ($violation) {
			$this->logMessage('"' . $this->charge['title'] . '" disabled for violating rule "' . $rule . ' ' . $comparison . ' ' . implode(', ', $rules[$rule][$comparison]) . '" with value "' . (is_array($value) ? implode(',', $value) : $value) . '"');
		}
		
		return $violation;
	}
	
	private function inRange($value, $range_list, $charge_type = '', $skip_testing = false) {
		$in_range = false;
		
		foreach ($range_list as $range) {
			if ($range == '') continue;
			
			$range = (strpos($range, '::')) ? explode('::', $range) : explode('-', $range);
			
			if (strpos($charge_type, 'distance') === 0) {
				if (empty($range[1])) {
					array_unshift($range, 0);
				}
				if ($value >= (float)$range[0] && $value <= (float)$range[1]) {
					$in_range = true;
				}
			} elseif (strpos($charge_type, 'postcode') === 0) {
				$postcode = preg_replace('/[^A-Z0-9]/', '', strtoupper($value));
				$from = preg_replace('/[^A-Z0-9]/', '', strtoupper($range[0]));
				$to = (isset($range[1])) ? preg_replace('/[^A-Z0-9]/', '', strtoupper($range[1])) : $from;
				
				if (strlen($from) < 3 && !preg_match('/[0-9]/', $from)) $from .= '1';
				if (strlen($to) < 3 && !preg_match('/[0-9]/', $to)) $to .= '99';
				
				if (strlen($from) < strlen($postcode)) $from = str_pad($from, max(strlen($postcode), strlen($from) + 3), ' ');
				if (strlen($to) < strlen($postcode)) $to = str_pad($to, max(strlen($postcode), strlen($to) + 3), preg_match('/[A-Z]/', $postcode) ? 'Z' : '9');
				
				$postcode = substr_replace(substr_replace($postcode, ' ', -3, 0), ' ', -2, 0);
				$from = substr_replace(substr_replace($from, ' ', -3, 0), ' ', -2, 0);
				$to = substr_replace(substr_replace($to, ' ', -3, 0), ' ', -2, 0);
				
				if (strnatcasecmp($postcode, $from) >= 0 && strnatcasecmp($postcode, $to) <= 0) {
					$in_range = true;
				}
			} else {
				if ($charge_type != 'attribute' && $charge_type != 'option' && $charge_type != 'other product data' && !isset($range[1])) {
					$range[1] = 999999999;
				}
				
				if ((count($range) > 1 && $value >= $range[0] && $value <= $range[1]) || (count($range) == 1 && $value == $range[0])) {
					$in_range = true;
				}
			}
		}
		
		if (!$skip_testing) {
			if (strpos($charge_type, ' not') ? $in_range : !$in_range) {
				$this->logMessage('"' . $this->charge['title'] . '" disabled for violating rule "' . $charge_type . (strpos($charge_type, ' not') ? ' ' : ' is ') . implode(', ', $range_list) . '" with value "' . $value . '"');
			}
		}
		
		return $in_range;
	}
	
	private function calculateBrackets($brackets, $charge_type, $comparison_value, $quantity, $total) {
		$to = 0;
		
		foreach ($brackets as $bracket) {
			$bracket = str_replace(array('::', ':'), array('-', '='), $bracket);
			
			$bracket_pieces = explode('=', $bracket);
			if (count($bracket_pieces) == 1) {
				array_unshift($bracket_pieces, ($charge_type == 'postcode') ? '0-ZZZZ' : '0-999999');
			}
			
			$from_and_to = explode('-', $bracket_pieces[0]);
			if (count($from_and_to) == 1) {
				array_unshift($from_and_to, ($charge_type == 'postcode') ? $from_and_to[0] : $to);
			}
			$from = trim($from_and_to[0]);
			$to = trim($from_and_to[1]);
			
			$cost_and_per = explode('/', $bracket_pieces[1]);
			$per = (isset($cost_and_per[1])) ? (float)$cost_and_per[1] : 0;
			
			$top = min($to, $comparison_value);
			$bottom = (isset($this->charge['rules']['cumulative'])) ? $from : 0;
			$difference = ($charge_type == 'postcode' || $charge_type == 'price') ? $quantity : $top - $bottom;
			$multiplier = ($per) ? ceil($difference / $per) : 1;
			
			if (!isset($cost) || !isset($this->charge['rules']['cumulative'])) {
				$cost = 0;
			}
			$cost += (strpos($cost_and_per[0], '%')) ? (float)$cost_and_per[0] * $multiplier * $total / 100 : (float)$cost_and_per[0] * $multiplier;
			
			$in_range = $this->inRange($comparison_value, array($from . '-' . $to), $charge_type, true);
			if ($in_range) {
				return $cost;
			}
		}
		
		return false;
	}
	
	private function calculateFormula($charges, $formula_array, &$i) {
		$settings = $this->getSettings();
		
		$groups = array();
		foreach ($settings['charge'] as $charge) {
			$groups[] = strtolower($charge['group']);
		}
		$groups = array_unique($groups);
		
		$costs = array();
		
		$calculation = $formula_array[$i];
		$i++;
		
		while ($i < count($formula_array)) {
			$piece = $formula_array[$i];
			if ($piece == '') break;
			if (in_array($piece, array('sum', 'max', 'min', 'avg', 'mult'))) {
				$calculation_result = $this->calculateFormula($charges, $formula_array, $i);
				if ($calculation_result !== false) $costs[] = $calculation_result;
			} elseif (!empty($charges[$piece])) {
				$group_costs = array();
				foreach ($charges[$piece] as $rate) {
					$group_costs[] = $rate['charge'];
				}
				$costs[] = $this->arrayCalculation($calculation, $group_costs);
			} elseif (!in_array($piece, $groups)) {
				$costs[] = (float)$piece;
			}
			$i++;
		}
		
		return $this->arrayCalculation($calculation, $costs);
	}
	
	private function arrayCalculation($calculation, $array) {
		if (empty($array)) {
			return false;
		} elseif ($calculation == 'sum') {
			return array_sum($array);
		} elseif ($calculation == 'max') {
			return max($array);
		} elseif ($calculation == 'min') {
			return min($array);
		} elseif ($calculation == 'avg') {
			return array_sum($array) / count($array);
		} elseif ($calculation == 'mult') {
			return array_product($array);
		}
	}
	
	private function getChildCategoryIds($parent_id) {
		$child_ids = array();
		$child_categories = $this->db->query("SELECT * FROM " . DB_PREFIX . "category WHERE parent_id = " . (int)$parent_id)->rows;
		foreach ($child_categories as $child_category) {
			$child_ids[] = $child_category['category_id'];
			$child_ids = array_merge($child_ids, $this->getChildCategoryIds($child_category['category_id']));
		}
		return array_unique($child_ids);
	}
	
	//==============================================================================
	// Ultimate Shipping functions
	//==============================================================================
	private function getShippingRates($method, $product_quantities, $address, $setting_overrides) {
		// Check cached rates
		$hash = md5(json_encode($product_quantities) . json_encode($address) . json_encode($setting_overrides));
		if (isset($this->session->data[$this->name][$hash])) {
			return $this->session->data[$this->name][$hash];
		}
		
		// Save cart and remove ineligible products
		$cart_products = $this->cart->getProducts();
		
		foreach ($cart_products as $product) {
			if (version_compare(VERSION, '2.1', '>=')) {
				$product['key'] = $product['product_id'] . json_encode($product['option']);
			}
			if (!in_array($product['key'], array_keys($product_quantities))) {
				$this->cart->remove(version_compare(VERSION, '2.1', '<') ? $product['key'] : $product['cart_id']);
			}
		}
		
		usleep(200000);
		
		// Get shipping rates
		if ($this->cart->hasShipping()) {
			$enabled = $this->config->get($method . '_status');
			if (!$enabled) {
				$this->config->set($method . '_status', 1);
				$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = 1 WHERE `key` = '" . $method . "_status'");
			}
			
			if (version_compare(VERSION, '2.3', '<')) {
				$this->load->model('shipping/' . $method);
				$shipping_rates = $this->{'model_shipping_' . $method}->getQuote($address);
			} else {
				$this->load->model('extension/shipping/' . $method);
				$shipping_rates = $this->{'model_extension_shipping_' . $method}->getQuote($address);
			}
			
			if (!$enabled) {
				$this->config->set($method . '_status', 0);
				$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = 0 WHERE `key` = '" . $method . "_status'");
			}
		}
		
		// Restore cart
		$this->cart->clear();
		
		foreach ($cart_products as $product) {
			$options = array();
			foreach ($product['option'] as $option) {
				if (isset($options[$option['product_option_id']])) {
					if (!is_array($options[$option['product_option_id']])) $options[$option['product_option_id']] = array($options[$option['product_option_id']]);
					$options[$option['product_option_id']][] = $option['product_option_value_id'];
				} else {
					$options[$option['product_option_id']] = (!empty($option['product_option_value_id'])) ? $option['product_option_value_id'] : $option[version_compare(VERSION, '2.0', '<') ? 'option_value' : 'value'];
				}
			}
			if (version_compare(VERSION, '1.5.6', '<')) {
				$recurring_profile = '';
			} elseif (version_compare(VERSION, '2.0', '<')) {
				$recurring_profile = $product['profile_id'];
			} else {
				$recurring_profile = (!empty($product['recurring']['recurring_id'])) ? $product['recurring']['recurring_id'] : 0;
			}
			$this->cart->add($product['product_id'], $product['quantity'], $options, $recurring_profile);
		}
		
		// Return shipping rates
		if (empty($shipping_rates)) {
			$shipping_rates = array('quote' => array());
		}
		$this->session->data[$this->name][$hash] = $shipping_rates;
		
		return $shipping_rates;
	}
}
?>
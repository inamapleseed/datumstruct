<?php
//==============================================================================
// MailChimp Integration v230.5
// 
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
// 
// All code within this file is copyright Clear Thinking, LLC.
// You may not copy or reuse code within this file without written permission.
//==============================================================================

class Mailchimp_Integration {
	private $type = 'module';
	private $name = 'mailchimp_integration';
	private $settings;
	
	public function __construct($config, $db, $log, $session, $url) {
		$this->config = $config;
		$this->db = $db;
		$this->log = $log;
		$this->session = $session;
		$this->url = $url;
	}
	
	//==============================================================================
	// Utility functions
	//==============================================================================
	public function getLists() {
		$response = $this->curlRequest('GET', 'lists', array('count' => 99));
		return (isset($response['lists'])) ? $response['lists'] : array();
	}
	
	public function getMergeTags($listid) {
		$response = $this->curlRequest('GET', 'lists/' . $listid . '/merge-fields', array('count' => 99));
		return (isset($response['merge_fields'])) ? $response['merge_fields'] : array();
	}
	
	public function getInterestGroups($listid) {
		if (!file_exists(DIR_SYSTEM . 'library/mailchimp_integration_pro.php')) return array();
		$response = $this->curlRequest('GET', 'lists/' . $listid . '/interest-categories');
		$interest_categories = (isset($response['categories'])) ? $response['categories'] : array();
		
		foreach ($interest_categories as &$interest_category) {
			$response = $this->curlRequest('GET', 'lists/' . $listid . '/interest-categories/' . $interest_category['id'] . '/interests', array('count' => 99));
			$interest_category['interests'] = (isset($response['interests'])) ? $response['interests'] : array();
		}
		
		return $interest_categories;
	}
	
	public function getMemberInfo($listid, $email) {
		$response = $this->curlRequest('GET', 'lists/' . $listid . '/members/' . md5(strtolower($email)));
		return (empty($response['error'])) ? $response : array();
	}
	
	public function addWebhooksAndStores($lists) {
		$settings = $this->getSettings();
		if (empty($settings['apikey']) || empty($settings['listid'])) return;
		
		$catalog_url = ($this->config->get('config_ssl') || $this->config->get('config_secure')) ? str_replace('http:', 'https:', HTTP_CATALOG) : HTTP_CATALOG;
		$url = $catalog_url . 'index.php?route=' . $this->type . '/' . $this->name . '/webhook&key=' . md5($this->config->get('config_encryption'));
		
		$webhooks = (!empty($settings['webhooks'])) ? explode(';', $settings['webhooks']) : array();
		
		foreach ($lists as $list) {
			// Webhooks
			if (!empty($webhooks)) {
				$response = $this->curlRequest('GET', 'lists/' . $list['id'] . '/webhooks');
				
				$mc_webhooks = array();
				if (empty($response['error'])) {
					foreach ($response['webhooks'] as $mc_webhook) {
						if ($mc_webhook['url'] == $url) {
							$this->curlRequest('DELETE', 'lists/' . $list['id'] . '/webhooks/' . $mc_webhook['id'], array());
						}
					}
				}
				
				$curl_data = array(
					'url'		=> $url,
					'events'	=> array(
						'subscribe'		=> in_array('subscribe', $webhooks),
						'unsubscribe'	=> in_array('unsubscribe', $webhooks),
						'profile'		=> in_array('profile', $webhooks),
						'upemail'		=> in_array('profile', $webhooks),
						'cleaned'		=> in_array('cleaned', $webhooks),
						'campaign'		=> false,
					),
					'sources'	=> array(
						'user'		=> true,
						'admin'		=> true,
						'api'		=> true,
					),
				);
				
				$response = $this->curlRequest('POST', 'lists/' . $list['id'] . '/webhooks', $curl_data);
			}
			
			// Stores
			$store_ids = array();
			foreach ($settings as $key => $value) {
				$explode = explode('-', $key);
				if ($explode[0] == 'store' && $value == $list['id']) {
					$store_ids[] = $explode[1];
				}
			}
			
			if (empty($store_ids)) continue;
			
			foreach ($store_ids as $store_id) {
				$store_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = " . (int)$store_id);
				
				$store = array();
				foreach ($store_query->rows as $row) {
					$store[$row['key']] = $row['value'];
				}
				if (empty($store['config_url'])) {
					$store['config_url'] = HTTP_CATALOG;
				}
				
				$country = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = " . (int)$store['config_country_id'])->row;
				$zone = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = " . (int)$store['config_zone_id'])->row;
				
				$curl_data = array(
					'id'				=> $list['id'] . '-' . $store_id,
					'list_id'			=> $list['id'],
					'name'				=> $store['config_name'],
					'platform'			=> 'OpenCart',
					'domain'			=> $store['config_url'],
					'email_address'		=> $store['config_email'],
					'currency_code'		=> $store['config_currency'],
					'primary_locale'	=> $store['config_language'],
					'phone'				=> $store['config_telephone'],
					'address'			=> array(
						'country_code'		=> $country['iso_code_2'],
						'province'			=> (!empty($zone['name'])) ? $zone['name'] : '(none)',
					),
				);
			
				$response = $this->curlRequest('POST', 'ecommerce/stores', $curl_data);
			}
		}
	}
	
	public function determineList($customer, $address) {
		$settings = $this->getSettings();
		
		if (!empty($settings['mapping'])) {
			foreach ($settings['mapping'] as $mapping) {
				if (empty($mapping['list']) || empty($mapping['rule'])) continue;
				
				// Build rules list
				$rules = array('list' => $mapping['list']);
				foreach ($mapping['rule'] as $rule) {
					if (empty($rule['type'])) continue;
					$rules[$rule['type']][$rule['comparison']][] = $rule['value'];
				}
				
				if (isset($address['country_id']) && isset($address['zone_id']) && isset($address['city'])) {
					// Find geo zones
					$geo_zones = array();
					$geo_zones_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE country_id = " . (int)$address['country_id'] . " AND (zone_id = 0 OR zone_id = " . (int)$address['zone_id'] . ")");
					if ($geo_zones_query->num_rows) {
						foreach ($geo_zones_query->rows as $geo_zone) {
							$geo_zones[] = $geo_zone['geo_zone_id'];
						}
					} else {
						$geo_zones = array(0);
					}
					
					// Location Criteria
					if (isset($rules['city'])) {
						$this->commaMerge($rules['city']);
					}
					
					if ($this->ruleViolation($rules, 'city', strtolower($address['city'])) ||
						$this->ruleViolation($rules, 'geo_zone', $geo_zones)
					) {
						continue;
					}
					
					if (isset($rules['postcode']) && isset($address['postcode'])) {
						$this->commaMerge($rules['postcode']);
						foreach ($rules['postcode'] as $comparison => $postcodes) {
							$in_range = $this->inRange($address['postcode'], $postcodes, 'postcode ' . $comparison, $mapping['list']);
							if (($comparison == 'is' && !$in_range) || ($comparison == 'not' && $in_range)) {
								continue 2;
							}
						}
					}
				}
				
				// Order Criteria
				if ($this->ruleViolation($rules, 'currency', $this->config->get('config_currency')) ||
					$this->ruleViolation($rules, 'customer_group', $customer['customer_group_id']) ||
					$this->ruleViolation($rules, 'language', !empty($this->session->data['language']) ? $this->session->data['language'] : $this->config->get('config_language')) ||
					$this->ruleViolation($rules, 'store', $this->config->get('config_store_id'))
				) {
					continue;
				}
				
				return $mapping['list'];
			}
		}
		
		return $settings['listid'];
	}
	
	//==============================================================================
	// send()
	//==============================================================================
	public function send($data) {
		$settings = $this->getSettings();
		
		if (empty($settings['status'])) {
			if ($settings['testing_mode']) $this->log->write(strtoupper($this->name) . ' ERROR: Extension is disabled');
			return;
		}
		if (empty($settings['apikey'])) {
			if ($settings['testing_mode']) $this->log->write(strtoupper($this->name) . ' ERROR: API Key is not filled');
			return;
		}
		if (empty($settings['listid'])) {
			if ($settings['testing_mode']) $this->log->write(strtoupper($this->name) . ' ERROR: Default list is not set');
			return;
		}
		
		unset($this->session->data['mailchimp_lists']);
		unset($this->session->data['mailchimp_subscribed_lists']);
		
		// Get customer information
		if (!empty($data['customer_id'])) {
			if (!empty($data['newsletter']) && !empty($settings['subscribed_group'])) {
				$this->db->query("UPDATE " . DB_PREFIX . "customer SET customer_group_id = " . (int)$settings['subscribed_group'] . " WHERE customer_id = " . (int)$data['customer_id']);
			} elseif (empty($data['newsletter']) && !empty($settings['unsubscribed_group'])) {
				$this->db->query("UPDATE " . DB_PREFIX . "customer SET customer_group_id = " . (int)$settings['unsubscribed_group'] . " WHERE customer_id = " . (int)$data['customer_id']);
			}
			
			$customer = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = " . (int)$data['customer_id'])->row;
			if (isset($data['customer_newsletter'])) {
				$customer['newsletter'] = $data['customer_newsletter'];
			}
			
			if (!empty($customer['custom_field'])) {
				$customer['custom_field'] = (version_compare(VERSION, '2.1', '<')) ? unserialize($customer['custom_field']) : json_decode($customer['custom_field'], true);
			} else {
				$customer['custom_field'] = array();
			}
		} else {
			$customer = array(
				'customer_id'		=> 0,
				'customer_group_id'	=> (isset($data['customer_group_id'])) ? $data['customer_group_id'] : 0,
				'email'				=> (isset($data['email'])) ? $data['email'] : '',
				'firstname'			=> '',
				'lastname'			=> '',
				'address_id'		=> '',
				'telephone'			=> '',
				'newsletter'		=> 0,
				'custom_field'		=> (isset($data['custom_field'])) ? $data['custom_field'] : array(),
			);
		}
		
		// Get address information
		if (!empty($data['addresses'])) {
			$data['address'] = $data['addresses'];
		}
		if (!empty($data['address'])) {
			foreach ($data['address'] as $address_data) {
				$address = $address_data;
				if (!empty($address['default'])) break;
			}
		} else {
			$address = $data;
		}
		unset($data['address']);
		
		if (!empty($address['country_id'])) {
			$country_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = " . (int)$address['country_id']);
			$address['iso_code_2'] = (!empty($country_query->row['iso_code_2'])) ? $country_query->row['iso_code_2'] : '';
		}
		if (!empty($address['zone_id'])) {
			$zone_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = " . (int)$address['zone_id']);
			$address['zone'] = (!empty($zone_query->row['name'])) ? html_entity_decode($zone_query->row['name'], ENT_QUOTES, 'UTF-8') : '';
		}
		
		$address_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE address_id = " . (int)$customer['address_id']);
		$default_address = ($address_query->num_rows) ? $address_query->row : array(
			'address_1'		=> '',
			'address_2'		=> '',
			'city'			=> '',
			'postcode'		=> '',
			'zone_id'		=> '',
			'country_id'	=> '',
		);
		
		if (!empty($default_address['custom_field'])) {
			$address_custom_fields = (version_compare(VERSION, '2.1', '<')) ? unserialize($default_address['custom_field']) : json_decode($default_address['custom_field'], true);
			$customer['custom_field'] = array_merge($customer['custom_field'], $address_custom_fields);
		}
		
		$country_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = " . (int)$default_address['country_id']);
		$default_address['iso_code_2'] = (!empty($country_query->row['iso_code_2'])) ? $country_query->row['iso_code_2'] : '';
		
		$zone_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = " . (int)$default_address['zone_id']);
		$default_address['zone'] = (!empty($zone_query->row['name'])) ? html_entity_decode($zone_query->row['name'], ENT_QUOTES, 'UTF-8') : '';
		
		$customer['address'] = array(
			'addr1'		=> (isset($address['address_1']))	? $address['address_1']		: $default_address['address_1'],
			'addr2'		=> (isset($address['address_2']))	? $address['address_2']		: $default_address['address_2'],
			'city'		=> (isset($address['city']))		? $address['city']			: $default_address['city'],
			'state'		=> (isset($address['zone']))		? $address['zone']			: $default_address['zone'],
			'zip'		=> (isset($address['postcode']))	? $address['postcode']		: $default_address['postcode'],
			'country'	=> (isset($address['iso_code_2']))	? $address['iso_code_2']	: $default_address['iso_code_2'],
		);
		
		// Set list_id
		if (empty($data['list'])) {
			$list_ids = array($this->determineList($customer, $address));
		} else {
			$list_ids = (is_array($data['list'])) ? $data['list'] : array($data['list']);
		}
		
		// Loop through lists, and Subscribe or Unsubscribe
		$errors = array();
		$first_loop = true;
		
		foreach ($list_ids as $list_id) {
			if (!empty($data['newsletter'])) {
				// Unsubscribe customer from other lists first
				if (!empty($data['list']) && $first_loop) {
					foreach ($this->getLists() as $list) {
						if (in_array($list['id'], $list_ids)) continue;
						
						$curl_request = 'PATCH';
						$curl_api = 'lists/' . $list['id'] . '/members/' . md5(strtolower($customer['email']));
						$curl_data = array('status' => 'unsubscribed');
						$response = $this->curlRequest($curl_request, $curl_api, $curl_data);
					}
					
					$first_loop = false;
				}
				
				// Set up merge data
				$merge_array = array();
				
				// Language merge tag
				if (!empty($this->session->data['language'])) {
					$merge_array['MC_LANGUAGE'] = $this->session->data['language'];
				} elseif (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
					$language_region = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 5));
					if ($language_region == 'fr-ca' || $language_region == 'pt-pt' || $language_region == 'es-es') {
						$merge_array['MC_LANGUAGE']	= substr($language_region, 0, 2) . '_' . strtoupper(substr($language_region, -2));
					} else {
						$merge_array['MC_LANGUAGE']	= substr($language_region, 0, 2);
					}
				} else {
					$merge_array['MC_LANGUAGE']	= $this->config->get('config_language');
				}
				
				// Other merge tags
				foreach ($this->getMergeTags($list_id) as $merge) {
					if ($merge['tag'] == 'EMAIL') continue;
					
					$merge_setting_value = (!empty($settings[$list_id . '_' . $merge['tag']])) ? $settings[$list_id . '_' . $merge['tag']] : '';
					
					if (empty($merge_setting_value)) {
						if (!$merge['required']) continue;
						$merge_array[$merge['tag']] = ($merge['type'] == 'zip') ? '00000' : '(none)';
					} else {
						$merge_setting_split = explode(':', $merge_setting_value);
						$table = $merge_setting_split[0];
						$column = ($merge_setting_split[1] == 'address_id') ? 'address' : $merge_setting_split[1];
						
						if ($table == 'custom_field') {
							if (!empty($customer['custom_field'][$column])) {
								$custom_field_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_field_value_description WHERE custom_field_id = " . (int)$column . " AND custom_field_value_id = " . (int)$customer['custom_field'][$column]);
								if ($custom_field_value_query->num_rows) {
									$merge_array[$merge['tag']] = $custom_field_value_query->row['name'];
								} else {
									$merge_array[$merge['tag']] = $customer['custom_field'][$column];
								}
							}
						} elseif (!empty($data[$column])) {
							$merge_array[$merge['tag']] = $data[$column];
						} elseif (!empty($customer[$column])) {
							$merge_array[$merge['tag']] = $customer[$column];
						} else {
							$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . $table . " WHERE customer_id = " . (int)$customer['customer_id']);
							if (!empty($customer_query->row[$column])) {
								$merge_array[$merge['tag']] = $customer_query->row[$column];
							} elseif ($merge['required']) {
								$merge_array[$merge['tag']] = ($merge['type'] == 'zip') ? '00000' : '(none)';
							}
						}
						
						if ($merge['type'] == 'phone' && !empty($merge_array[$merge['tag']])) {
							$telephone = preg_replace('/[^0-9]/', '', $merge_array[$merge['tag']]);
							if ($telephone || $merge['required']) {
								$merge_array[$merge['tag']] = substr($telephone, 0, 3) . '-' . substr($telephone, 3, 3) . '-' . substr($telephone, 6);
							} else {
								unset($merge_array[$merge['tag']]);
							}
						}
					}
					
					if ($merge['type'] == 'address') {
						if (!array_filter($merge_array[$merge['tag']])) {
							unset($merge_array[$merge['tag']]);
						} else {
							$zip = trim($merge_array[$merge['tag']]['zip']);
							if (empty($zip)) $merge_array[$merge['tag']]['zip'] = '00000';
						}
					}
				}
				
				foreach ($merge_array as &$merge) {
					if (is_array($merge)) {
						foreach ($merge as &$m) {
							$m = html_entity_decode($m, ENT_QUOTES, 'UTF-8');
						}
					} else {
						$merge = html_entity_decode($merge, ENT_QUOTES, 'UTF-8');
					}
				}
				
				// Subscribe
				if (isset($data['update_existing']) && $data['update_existing'] == false) {
					$curl_request = 'POST';
					$curl_api = 'lists/' . $list_id . '/members';
				} else {
					$curl_request = 'PUT';
					$curl_api = 'lists/' . $list_id . '/members/' . md5(strtolower($customer['email']));
				}
				
				$curl_data = array(
					'email_type'	=> 'html',
					'status'		=> 'subscribed',
					'merge_fields'	=> $merge_array,
					'language'		=> $merge_array['MC_LANGUAGE'],
					'email_address'	=> (isset($data['email'])) ? $data['email'] : $customer['email'],
				);
				
				$double_optin = (isset($data['double_optin'])) ? $data['double_optin'] : $settings['double_optin'];
				
				if ($double_optin && !$customer['newsletter']) {
					if ($curl_request == 'POST') {
						$curl_data['status'] = 'pending';
					} else {
						$curl_data['status_if_new'] = 'pending';
					}
				} else {
					$curl_data['ip_opt'] = $_SERVER['REMOTE_ADDR'];
				}
				
				// Interest Groups
				if (!empty($settings['interest_groups']) && !empty($data['interests']) && file_exists(DIR_SYSTEM . 'library/mailchimp_integration_pro.php')) {
					$interests = array();
					foreach ($data['interests'] as $id) {
						$interests[$id] = true;
					}
					$curl_data['interests'] = $interests;
					unset($this->session->data['mailchimp_interest_groups']);
					unset($this->session->data['mailchimp_interests']);
				}
			} else {
				// Unsubscribe
				$curl_request = 'PATCH';
				$curl_api = 'lists/' . $list_id . '/members/' . md5(strtolower($customer['email']));
				$curl_data = array(
					'status'			=> 'unsubscribed',
				);
			}
			
			$response = $this->curlRequest($curl_request, $curl_api, $curl_data);
			
			if (!empty($response['error'])) {
				$errors[] = $response['error'];
			}
		}
		
		return ($errors) ? '&bull; ' . implode('<br />&bull; ', $errors) : '';
	}
	
	//==============================================================================
	// sync()
	//==============================================================================
	public function sync($start, $end) {
		$settings = $this->getSettings();
		
		if (empty($settings['apikey'])) {
			return 'Error: No API Key is filled in';
		} elseif (empty($settings['listid'])) {
			return 'Error: No List ID is set';
		}
		
		$output = "Completed!\n\n";
		$data_center = explode('-', $settings['apikey']);
		$lists = $this->getLists();
		
		// Get all OpenCart e-mails
		$opencart_emails = array();
		$all_customers = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer")->rows;
		
		foreach ($all_customers as $customer) {
			$opencart_emails[] = strtolower($customer['email']);
		}
		
		// MailChimp to OpenCart
		if ($settings['autocreate']) {
			$created = 0;
			
			foreach ($lists as $list) {
				$context = stream_context_create(array('http' => array('ignore_errors' => '1')));
				$response = @file_get_contents('https://' . $data_center[1] . '.api.mailchimp.com/export/1.0/list/?apikey=' . $settings['apikey'] . '&id=' . $list['id'], false, $context);
				
				$mailchimp_emails = array();
				foreach (explode("\n", $response) as $line) {
					$subscriber = json_decode($line);
					if (strpos($subscriber[0], '@') === false) continue;
					$mailchimp_emails[] = strtolower($subscriber[0]);
				}
				$diff_emails = array_diff($mailchimp_emails, $opencart_emails);
				
				foreach ($diff_emails as $email) {
					$response = $this->curlRequest('GET', 'lists/' . $list['id'] . '/members/' . md5(strtolower($email)));
					
					if (!empty($response['error'])) {
						if ($settings['testing_mode']) $this->log->write(strtoupper($this->name) . ' SYNC ERROR: ' . $errors);
						return $response['error'];
					} else {
						$this->createCustomer($response);
						$created++;
					}
				}
			}
			
			$output .= $created . " customer(s) created in OpenCart\n";
		}
		
		// Get merge tags
		$merge_array = array();
		foreach ($lists as $list) {
			foreach ($this->getMergeTags($list['id']) as $merge) {
				if ($merge['tag'] == 'EMAIL') {
					$merge_array[$list['id']]['EMAIL'] = 'email';
					continue;
				}
				
				$merge_setting_value = (!empty($settings[$list['id'] . '_' . $merge['tag']])) ? $settings[$list['id'] . '_' . $merge['tag']] : '';
				
				if (empty($merge_setting_value)) {
					if (!$merge['required']) continue;
					$merge_array[$list['id']][$merge['tag']] = ($merge['type'] == 'zip') ? '00000' : '(none)';
				} else {
					$merge_setting_split = explode(':', $merge_setting_value);
					if ($merge_setting_split[0] == 'customer' || $merge_setting_split[1] == 'address_id') {
						$merge_array[$list['id']][$merge['tag']] = $merge_setting_split[1];
					} else {
						$merge_array[$list['id']][$merge['tag']] = $merge_setting_value;
					}
				}
			}
		}
			
		// Get OpenCart customers, and change customer groups
		$customer_id_sql = '';
		if ($start) $customer_id_sql .= " AND customer_id >= " . (int)$start;
		if ($end) $customer_id_sql .= " AND customer_id <= " . (int)$end;
		
		$customers = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE TRUE" . $customer_id_sql . " ORDER BY customer_group_id, store_id ASC")->rows;
		
		if (!empty($settings['subscribed_group'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET customer_group_id = " . (int)$settings['subscribed_group'] . " WHERE newsletter = 1" . $customer_id_sql);
		}
		if (!empty($settings['unsubscribed_group'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET customer_group_id = " . (int)$settings['unsubscribed_group'] . " WHERE newsletter = 0" . $customer_id_sql);
		}
		
		// OpenCart to MailChimp
		$operations = array();
		$count = 0;
		
		foreach ($customers as $customer) {
			if (!$customer['newsletter']) continue;
			
			$address = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE address_id = " . (int)$customer['address_id'])->row;
			
			$this->config->set($this->name . '_testing_mode', 0);
			$listid = $this->determineList($customer, $address);
			$this->config->set($this->name . '_testing_mode', $settings['testing_mode']);
			
			$formatted_customer = array('email_address' => $customer['email'], 'status' => 'subscribed');
			
			foreach ($merge_array as $merge_listid => $merges) {
				if ($merge_listid != $listid) continue;
				foreach ($merges as $merge_tag => $opencart_field) {
					if ($opencart_field == 'address_id') {
						$address_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE address_id = " . (int)$customer['address_id']);
						if ($address_query->num_rows) {
							$address = $address_query->row;
							if (!empty($address['country_id'])) {
								$country_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = " . (int)$address['country_id']);
								$address['iso_code_2'] = (!empty($country_query->row['iso_code_2'])) ? $country_query->row['iso_code_2'] : '';
							}
							if (!empty($address['zone_id'])) {
								$zone_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = " . (int)$address['zone_id']);
								$address['zone'] = (!empty($zone_query->row['name'])) ? html_entity_decode($zone_query->row['name'], ENT_QUOTES, 'UTF-8') : '(none)';
							}
							$formatted_customer['merge_fields'][$merge_tag] = array(
								'addr1'		=> $address['address_1'],
								'addr2'		=> $address['address_2'],
								'city'		=> $address['city'],
								'state'		=> (isset($address['zone'])) ? $address['zone'] : '(none)',
								'zip'		=> (!empty($address['postcode'])) ? $address['postcode'] : '00000',
								'country'	=> (isset($address['iso_code_2'])) ? $address['iso_code_2'] : ''
							);
						}
					} elseif ($opencart_field == 'telephone') {
						$telephone = preg_replace('/[^0-9]/', '', $customer[$opencart_field]);
						if ($telephone) {
							$formatted_customer['merge_fields'][$merge_tag] = substr($telephone, 0, 3) . '-' . substr($telephone, 3, 3) . '-' . substr($telephone, 6);
						}
					} elseif ($opencart_field == '00000' || $opencart_field == '(none)') {
						$formatted_customer['merge_fields'][$merge_tag] = $opencart_field;
					} elseif (isset($customer[$opencart_field])) {
						$formatted_customer['merge_fields'][$merge_tag] = $customer[$opencart_field];
					} else {
						$field_split = explode(':', $opencart_field);
						if ($field_split[0] == 'custom_field') {
							$address_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE address_id = " . (int)$customer['address_id']);
							if ($address_query->num_rows && !empty($address_query->row['custom_field'])) {
								$address_custom_fields = (version_compare(VERSION, '2.1', '<')) ? unserialize($address_query->row['custom_field']) : json_decode($address_query->row['custom_field'], true);
							} else {
								$address_custom_fields = array();
							}
							$custom_fields = (version_compare(VERSION, '2.1', '<')) ? unserialize($customer['custom_field']) : json_decode($customer['custom_field'], true);
							$custom_fields += $address_custom_fields;
							
							if (isset($custom_fields[$field_split[1]])) {
								$custom_field_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_field_value_description WHERE custom_field_id = " . (int)$field_split[1] . " AND custom_field_value_id = " . (int)$custom_fields[$field_split[1]]);
								if ($custom_field_value_query->num_rows) {
									$formatted_customer['merge_fields'][$merge_tag] = $custom_field_value_query->row['name'];
								} else {
									$formatted_customer['merge_fields'][$merge_tag] = $custom_fields[$field_split[1]];
								}
							}
							if (empty($formatted_customer['merge_fields'][$merge_tag])) {
								$formatted_customer['merge_fields'][$merge_tag] = '(none)';
							}
						} else {
							if ($field_split[0] == 'address') {
								$database_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE address_id = " . (int)$customer['address_id']);
							} else {
								$database_query = $this->db->query("SELECT * FROM " . DB_PREFIX . $field_split[0] . " WHERE customer_id = " . (int)$customer['customer_id']);
							}
							$formatted_customer['merge_fields'][$merge_tag] = (isset($field_split[1]) && isset($database_query->row[$field_split[1]])) ? $database_query->row[$field_split[1]] : '(none)';
						}
					}
				}
			}
			
			foreach ($formatted_customer['merge_fields'] as &$merge) {
				if (is_array($merge)) {
					foreach ($merge as &$m) {
						$m = html_entity_decode($m, ENT_QUOTES, 'UTF-8');
					}
				} else {
					$merge = html_entity_decode($merge, ENT_QUOTES, 'UTF-8');
				}
			}
			
			$operations[] = array(
				'method'	=> 'PUT',
				'path'		=> 'lists/' . $listid . '/members/' . md5(strtolower($formatted_customer['email_address'])),
				'body'		=> json_encode($formatted_customer),
			);
			
			$count += 1;
		}
		
		$response = $this->curlRequest('POST', 'batches', array('operations' => $operations));
		
		if (!empty($response['error'])) {
			if ($settings['testing_mode']) $this->log->write(strtoupper($this->name) . ' SYNC ERROR: ' . $errors);
			return $response['error'];
		}
		
		$output .= $count . " customer(s) sent to MailChimp\n";
		$output .= "\nNote: Customers are processed by MailChimp as part of a batch request, and added or updated accordingly. You can check the status of the batch request here:\n\n";
		$output .= 'https://' . $data_center[1] . '.api.mailchimp.com/3.0/batches/' . $response['id'] . '?apikey=' . $settings['apikey'];
		
		if ($settings['testing_mode']) {
			$this->log->write(strtoupper($this->name) . ' SYNC SUCCESS: ' . str_replace("\n", ' ', $output));
		}
		
		return $output;
	}
	
	//==============================================================================
	// sendCart()
	//==============================================================================
	public function sendCart($cart, $customer_id) {
		$operations = array();
		$settings = $this->getSettings();
		$config_url = ($this->config->get('config_url')) ? $this->config->get('config_url') : HTTP_CATALOG;
		
		// Set up curl data
		if (strpos($customer_id, '@')) {
			$customer_info = array(
				'email'		=> $customer_id,
				'firstname'	=> (isset($this->session->data['guest']['firstname'])) ? $this->session->data['guest']['firstname'] : '',
				'lastname'	=> (isset($this->session->data['guest']['lastname'])) ? $this->session->data['guest']['lastname'] : '',
			);
		} else {
			$customer_info = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = " . (int)$customer_id)->row;
		}

		$curl_data = array(
			'id'			=> session_id(),
			'customer'		=> array(
				'id'			=> $customer_id,
				'email_address'	=> $customer_info['email'],
				'first_name'	=> $customer_info['firstname'],
				'last_name'		=> $customer_info['lastname'],
				'opt_in_status'	=> false,
			),
			'checkout_url'	=> HTTP_SERVER . 'index.php?route=checkout/checkout',
			'currency_code'	=> $_COOKIE['currency'],
			'order_total'	=> $cart->getTotal(),
			'tax_total'		=> $cart->getTotal() - $cart->getSubTotal(),
		);
		
		if (isset($_COOKIE['mc_cid'])) {
			$curl_data['campaign_id'] = $_COOKIE['mc_cid'];
		}
		
		// Set up line items
		$cart_products = $cart->getProducts();
		
		foreach ($cart_products as &$cart_product) {
			$options = array();
			foreach ($cart_product['option'] as $option) {
				if ($option['type'] == 'file') continue;
				$option_value = (version_compare(VERSION, '2.0', '<')) ? $option['option_value'] : $option['value'];
				$options[] = html_entity_decode($option['name'] . ': ' . $option_value, ENT_QUOTES, 'UTF-8');
			}
			$cart_product['options'] = $options;
		}
		
		$curl_data['lines'] = array();
		$mailchimp_store_id = $settings['store-' . $this->config->get('config_store_id') . '-list'] . '-' . $this->config->get('config_store_id');
		
		$this->buildLineItems($cart_products, $mailchimp_store_id, $operations, $curl_data['lines']);
		if (empty($curl_data['lines'])) {
			return;
		}
		
		// Send cart data
		if (file_exists(DIR_SYSTEM . 'library/mailchimp_integration_pro.php')) {
			$operations[] = array(
				'method'	=> 'DELETE',
				'path'		=> 'ecommerce/stores/' . $mailchimp_store_id . '/carts/' . session_id(),
			);
			$operations[] = array(
				'method'	=> 'POST',
				'path'		=> 'ecommerce/stores/' . $mailchimp_store_id . '/carts',
				'body'		=> json_encode($curl_data),
			);
			
			$response = $this->curlRequest('POST', 'batches', array('operations' => $operations));
			if (!empty($response['error']) && $settings['testing_mode']) {
				$this->log->write(strtoupper($this->name) . ' CART ERROR: ' . $response['error']);
			}
		}
	}
	
	//==============================================================================
	// deleteCart()
	//==============================================================================
	public function deleteCart() {
		$mailchimp_store_id = $this->config->get($this->name . '_store-' . $this->config->get('config_store_id') . '-list') . '-' . $this->config->get('config_store_id');
		$this->curlRequest('DELETE', 'ecommerce/stores/' . $mailchimp_store_id . '/carts/' . session_id());
	}
	
	//==============================================================================
	// buildLineItems()
	//==============================================================================
	private function buildLineItems($products, $mailchimp_store_id, &$operations, &$lines) {
		$settings = $this->getSettings();
		$config_url = ($this->config->get('config_url')) ? $this->config->get('config_url') : HTTP_CATALOG;
		
		foreach ($products as $product) {
			$product_info = $this->db->query("SELECT *, p.image AS image, pd.name AS name, m.name AS manufacturer FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = " . (int)$product['product_id'])->row;
			
			if (empty($product_info['product_id'])) {
				$product['description'] = '';
				$product['manufacturer'] = '';
				$product['image'] = (version_compare(VERSION, '2.0', '<')) ? 'no_image.jpg' : 'placeholder.png';
				$product['quantity'] = 0;
			} else {
				$product = array_merge($product_info, $product);
				$product['quantity'] = $product_info['quantity'];
				if (false) {
					$product_category_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE category_id = (SELECT category_id FROM product_to_category WHERE product_id = " . (int)$product['product_id'] . " ORDER BY category_id DESC LIMIT 1)");
					if ($product_category_query->num_rows) {
						$product['manufacturer'] = $product_category['name'];
					}
				}
			}
			
			// Create product URL
			$product_url = (defined('HTTP_CATALOG')) ? HTTP_CATALOG : HTTP_SERVER;
			$url_alias_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'product_id=" . $product['product_id'] . "'");
			
			if ($url_alias_query->num_rows) {
				$product_url .= $url_alias_query->row['keyword'];
			} else {
				$product_url .= 'index.php?route=product/product&product_id=' . $product['product_id'];
			}
			
			//$product['image'] = str_replace(array('.jpg', '.png'), array('-150x150.jpg', '-150x150.png'), $product['image']);
			
			// Set up curl data
			$product_curl_data = array(
				'id'			=> $product['product_id'],
				'title'			=> html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8'),
				'url'			=> $product_url,
				'description'	=> html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8'),
				'vendor'		=> html_entity_decode($product['manufacturer'], ENT_QUOTES, 'UTF-8'),
				'image_url'		=> $config_url . 'image/' . str_replace(' ', '%20', $product['image']),
				'variants'		=> array(
					array(
						'id'					=> $product['product_id'],
						'title'					=> '',
						'sku'					=> html_entity_decode($product['model'], ENT_QUOTES, 'UTF-8'),
						'price'					=> $product['price'],
						'inventory_quantity'	=> (int)$product['quantity'],
					),
				),
			);
			
			// Add product operations
			$this->config->set($this->name . '_testing_mode', 0);
			
			$operations[] = array(
				'method'	=> 'POST',
				'path'		=> 'ecommerce/stores/' . $mailchimp_store_id . '/products',
				'body'		=> json_encode($product_curl_data),
			);
			$operations[] = array(
				'method'	=> 'PATCH',
				'path'		=> 'ecommerce/stores/' . $mailchimp_store_id . '/products/' . $product['product_id'],
				'body'		=> json_encode($product_curl_data),
			);
			
			// Add variant operations
			if ($product['options']) {
				$product_variant = implode(', ', $product['options']);
				$product_variant_id = md5($product_variant);
				
				$variant_curl_data = array(
					'id'					=> $product_variant_id,
					'title'					=> $product_variant,
					'url'					=> $this->url->link('product/product', 'product_id=' . $product['product_id']),
					'price'					=> $product['price'],
					'inventory_quantity'	=> (int)$product['quantity'],
					'image_url'				=> $config_url . 'image/' . $product['image'],
				);
				
				$operations[] = array(
					'method'	=> 'PUT',
					'path'		=> 'ecommerce/stores/' . $mailchimp_store_id . '/products/' . $product['product_id'] . '/variants/' . $product_variant_id,
					'body'		=> json_encode($variant_curl_data),
				);
			} else {
				$product_variant = $product['name'];
				$product_variant_id = $product['product_id'];
			}
			
			$this->config->set($this->name . '_testing_mode', $settings['testing_mode']);
			
			$lines[] = array(
				'id'					=> 'line_' . (count($lines) + 1),
				'product_id'			=> $product['product_id'],
				'product_variant_id'	=> $product_variant_id,
				'quantity'				=> (int)$product['quantity'],
				'price'					=> $product['price'],
			);
		}
	}
	
	//==============================================================================
	// sendOrder()
	//==============================================================================
	public function sendOrder($order_info, $ordertype) {
		$operations = $this->formatOrder($order_info, $ordertype);
		if ($operations) {
			$response = $this->curlRequest('POST', 'batches', array('operations' => $operations));
		}
	}
	
	//==============================================================================
	// deleteOrder()
	//==============================================================================
	public function deleteOrder($store_id, $order_id) {
		$mailchimp_store_id = $this->config->get($this->name . '_store-' . $store_id . '-list') . '-' . $store_id;
		$response = $this->curlRequest('DELETE', 'ecommerce/stores/' . $mailchimp_store_id . '/orders/' . $order_id);
	}
	
	//==============================================================================
	// formatOrder()
	//==============================================================================
	public function formatOrder($order_info, $ordertype) {
		if ($ordertype == 'newsletter' && empty($_COOKIE['mc_cid']) && empty($_COOKIE['mc_eid'])) {
			return array();
		}
		
		$operations = array();
		$settings = $this->getSettings();
		$config_url = ($this->config->get('config_url')) ? $this->config->get('config_url') : HTTP_CATALOG;
		$mailchimp_store_id = $settings['store-' . $order_info['store_id'] . '-list'] . '-' . $order_info['store_id'];
		
		// Set up shipping and tax costs
		$order_info['shipping_total'] = 0;
		$order_info['tax_total'] = 0;
		
		$order_totals = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = " . (int)$order_info['order_id'])->rows;
		foreach ($order_totals as $order_total) {
			if ($order_total['code'] == 'shipping') {
				$order_info['shipping_total'] = $order_total['value'];
			}
			if ($order_total['code'] == 'tax' || $order_total['code'] == 'taxcloud_integration') {
				$order_info['tax_total'] = $order_total['value'];
			}
		}
		
		$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = " . (int)$order_info['current_order_status_id']);
		$order_status = ($order_status_query->num_rows) ? $order_status_query->row['name'] : '';
		$shipping_country = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = " . (int)$order_info['shipping_country_id'])->row;
		$payment_country = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = " . (int)$order_info['payment_country_id'])->row;
		
		// Check order status
		if (!in_array($order_info['current_order_status_id'], explode(';', $settings['orderstatus']))) {
			if ($settings['testing_mode']) {
				$this->log->write(strtoupper($this->name) . ': order ID ' . $order_info['order_id'] . ' was not sent to MailChimp because its order status is ' . $order_status);
			}
			return array();
		}
		
		// Send customer data
		$past_orders_query = $this->db->query("SELECT COUNT(*) AS orders, SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > 0 AND customer_id = " . (int)$order_info['customer_id'] . " AND customer_id != 0");
		$past_orders_count = (int)$past_orders_query->row['orders'];
		$past_total_spent = round((float)$past_orders_query->row['total'], 2);
		
		$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = " . (int)$order_info['customer_id']);
		$customer_email = ($customer_query->num_rows) ? $customer_query->row['email'] : $order_info['email'];
		$customer_id = ($order_info['customer_id']) ? $order_info['customer_id'] : $order_info['email'];
		
		$customer_data = array(
			'id'				=> $customer_id,
			'email_address'		=> $customer_email,
			'first_name'		=> $order_info['firstname'],
			'last_name'			=> $order_info['lastname'],
			'orders_count'		=> $past_orders_count,
			'total_spent'		=> $past_total_spent,
			'opt_in_status'		=> false,
		);
		
		$operations[] = array(
			'method'	=> 'PUT',
			'path'		=> 'ecommerce/stores/' . $mailchimp_store_id . '/customers/' . $customer_id,
			'body'		=> json_encode($customer_data),
		);
		
		// Set up curl data
		$curl_data = array(
			'id'				=> $order_info['order_id'],
			'customer'			=> array('id' => $customer_id),
			'financial_status'	=> $order_status,
			'currency_code'		=> $order_info[isset($order_info['currency']) ? 'currency' : 'currency_code'],
			'order_total'		=> $order_info['total'],
			'tax_total'			=> $order_info['tax_total'],
			'shipping_total'	=> $order_info['shipping_total'],
			'processed_at_foreign' => $order_info['date_added'],
			'updated_at_foreign' => date('Y-m-d H:i'),
			'shipping_address'	=> array(
				'name'				=> $order_info['shipping_firstname'] . ' ' . $order_info['shipping_lastname'],
				'address1'			=> $order_info['shipping_address_1'],
				'address2'			=> $order_info['shipping_address_2'],
				'city'				=> $order_info['shipping_city'],
				'province'			=> $order_info['shipping_zone'],
				'postal_code'		=> $order_info['shipping_postcode'],
				'country'			=> $order_info['shipping_country'],
				'country_code'		=> (isset($shipping_country['iso_code_2'])) ? $shipping_country['iso_code_2'] : '',
				'phone'				=> $order_info['telephone'],
				'company'			=> $order_info['shipping_company'],
			),
			'billing_address'	=> array(
				'name'				=> $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'],
				'address1'			=> $order_info['payment_address_1'],
				'address2'			=> $order_info['payment_address_2'],
				'city'				=> $order_info['payment_city'],
				'province'			=> $order_info['payment_zone'],
				'postal_code'		=> $order_info['payment_postcode'],
				'country'			=> $order_info['payment_country'],
				'country_code'		=> (isset($payment_country['iso_code_2'])) ? $payment_country['iso_code_2'] : '',
				'phone'				=> $order_info['telephone'],
				'company'			=> $order_info['payment_company'],
			),
		);
		
		// Set campaign_id and email_id if present
		if (isset($_COOKIE['mc_cid'])) {
			$curl_data['campaign_id'] = $_COOKIE['mc_cid'];
		}
		/*
		if (isset($_COOKIE['mc_eid'])) {
			$curl_data['email_id'] = $_COOKIE['mc_eid'];
		}
		*/
		
		// Set up line items
		$order_products = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = " . (int)$order_info['order_id'])->rows;

		foreach ($order_products as &$order_product) {
			$options = array();
			$order_options = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = " . (int)$order_info['order_id'] . " AND order_product_id = " . (int)$order_product['order_product_id'])->rows;
			foreach ($order_options as $option) {
				if ($option['type'] == 'file') continue;
				$options[] = html_entity_decode($option['name'] . ': ' . $option['value'], ENT_QUOTES, 'UTF-8');
			}
			$order_product['options'] = $options;
		}
		
		$curl_data['lines'] = array();
		$this->buildLineItems($order_products, $mailchimp_store_id, $operations, $curl_data['lines']);
		
		if (empty($curl_data['lines'])) {
			if ($settings['testing_mode']) {
				$this->log->write(strtoupper($this->name) . ': No valid products on order #' . $order_info['order_id']);
			}
			return array();
		}
		
		// Send order data
		if (file_exists(DIR_SYSTEM . 'library/mailchimp_integration_pro.php')) {
			$operations[] = array(
				'method'	=> 'POST',
				'path'		=> 'ecommerce/stores/' . $mailchimp_store_id . '/orders',
				'body'		=> json_encode($curl_data),
			);
			$operations[] = array(
				'method'	=> 'PATCH',
				'path'		=> 'ecommerce/stores/' . $mailchimp_store_id . '/orders/' . $order_info['order_id'],
				'body'		=> json_encode($curl_data),
			);
			return $operations;
		}
	}
	
	//==============================================================================
	// syncOrders()
	//==============================================================================
	public function syncOrders($start, $end) {
		$settings = $this->getSettings();
		$data_center = explode('-', $settings['apikey']);
		
		// Delete cookies if the syncing admin has them present
		unset($_COOKIE['mc_cid']);
		unset($_COOKIE['mc_eid']);
		
		// Set is_syncing flag to true
		$stores = $this->db->query("SELECT * FROM " . DB_PREFIX . "store")->rows;
		$stores[] = array('store_id' => 0);
		
		foreach ($stores as $store) {
			$mailchimp_store_id = $settings['store-' . $store['store_id'] . '-list'] . '-' . $store['store_id'];
			$store_response = $this->curlRequest('PATCH', 'ecommerce/stores/' . $mailchimp_store_id, array('is_syncing' => true));
		}
		
		// Send OpenCart orders
		$sql = "SELECT * FROM `" . DB_PREFIX . "order` WHERE order_status_id > 0";
		if ($start) $sql .= " AND order_id >= " . (int)$start;
		if ($end) $sql .= " AND order_id <= " . (int)$end;
		$opencart_orders = $this->db->query($sql)->rows;
		
		$count = 0;
		$operations = array();
		
		foreach ($opencart_orders as $order) {
			$order['current_order_status_id'] = $order['order_status_id'];
			$new_operations = $this->formatOrder($order, 'all');
			if ($new_operations) {
				$operations = array_merge($operations, $new_operations);
				$count += 1;
			}
		}
		
		if (empty($operations)) {
			return 'No eligible orders';
		}
		
		if (file_exists(DIR_SYSTEM . 'library/mailchimp_integration_pro.php')) {
			$response = $this->curlRequest('POST', 'batches', array('operations' => $operations));
		}
		
		// Set is_syncing flag to false
		foreach ($stores as $store) {
			$mailchimp_store_id = $settings['store-' . $store['store_id'] . '-list'] . '-' . $store['store_id'];
			$store_response = $this->curlRequest('PATCH', 'ecommerce/stores/' . $mailchimp_store_id, array('is_syncing' => false));
		}
		
		// Return output
		$output = $count . " orders(s) sent to MailChimp\n";
		$output .= "\nNote: Orders are processed by MailChimp as part of a batch request, and added or updated accordingly. You can check the status of the batch request here:\n\n";
		$output .= 'https://' . $data_center[1] . '.api.mailchimp.com/3.0/batches/' . $response['id'] . '?apikey=' . $settings['apikey'];
		
		if ($settings['testing_mode']) {
			$this->log->write(strtoupper($this->name) . ' SYNC SUCCESS: ' . $output);
		}
		
		return $output;
	}
	
	//==============================================================================
	// webhook()
	//==============================================================================
	public function webhook($type, $data) {
		$settings = $this->getSettings();
		
		if (empty($settings['status'])) {
			if ($settings['testing_mode']) {
				$this->log->write(strtoupper($this->name) . ' ERROR: Extension is disabled in the admin panel');
			}
			return;
		}
		if (empty($settings['webhooks'])) {
			if ($settings['testing_mode']) {
				$this->log->write(strtoupper($this->name) . ' ERROR: No webhooks are enabled in the admin panel');
			}
			return;
		}
		
		$webhooks = explode(';', $settings['webhooks']);
		
		$listid = $settings['listid'];
		$customer_group_id = $this->config->get('config_customer_group_id');
		/*
		foreach ($settings as $key => $value) {
			if (strpos($value, '_list') && $value == $data['list_id']) {
				if (customer group rule exists) {
					$listid = $data['list_id'];
					$customer_group_id = customer group value
					break;
				}
			}
		}
		*/
		$data['customer_group_id'] = $customer_group_id;
		
		$success = false;
		
		if ($type == 'subscribe' && in_array('subscribe', $webhooks)) {
			
			if ($settings['autocreate']) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape($data['email']) . "'");
				if (!$query->num_rows) {
					$this->createCustomer($data);
				}
			}
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = 1 WHERE email = '" . $this->db->escape($data['email']) . "'");
			if (!empty($settings['subscribed_group'])) $this->db->query("UPDATE " . DB_PREFIX . "customer SET customer_group_id = " . (int)$settings['subscribed_group'] . " WHERE email = '" . $this->db->escape($data['email']) . "'");
			$success = true;
			
		} elseif (($type == 'unsubscribe' && in_array('unsubscribe', $webhooks)) || ($type == 'cleaned' && in_array('cleaned', $webhooks))) {
			
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = 0 WHERE email = '" . $this->db->escape($data['email']) . "'");
			if (!empty($settings['unsubscribed_group'])) $this->db->query("UPDATE " . DB_PREFIX . "customer SET customer_group_id = " . (int)$settings['unsubscribed_group'] . " WHERE email = '" . $this->db->escape($data['email']) . "'");
			$success = true;
			
		} elseif ($type == 'profile' && in_array('profile', $webhooks)) {
			
			$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape($data['email']) . "'");
			if (empty($customer_query->row['address_id'])) {
				$this->log->write(strtoupper($this->name) . ' WEBHOOK ERROR: customer ' . $data['email'] . ' does not have a valid address_id');
				return;
			}
			
			foreach ($data['merges'] as $merge_tag => $merge_value) {
				if ($merge_tag == 'EMAIL') continue;
				$merge_mapping = $settings[$data['list_id'] . '_' . $merge_tag];
				if (strpos($merge_mapping, ':firstname')) {
					$this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($merge_value) . "' WHERE email = '" . $this->db->escape($data['email']) . "'");
					$this->db->query("UPDATE " . DB_PREFIX . "address SET firstname = '" . $this->db->escape($merge_value) . "' WHERE address_id = " . (int)$customer_query->row['address_id']);
				} elseif (strpos($merge_mapping, ':lastname')) {
					$this->db->query("UPDATE " . DB_PREFIX . "customer SET lastname = '" . $this->db->escape($merge_value) . "' WHERE email = '" . $this->db->escape($data['email']) . "'");
					$this->db->query("UPDATE " . DB_PREFIX . "address SET lastname = '" . $this->db->escape($merge_value) . "' WHERE address_id = " . (int)$customer_query->row['address_id']);
				} elseif (strpos($merge_mapping, ':telephone')) {
					$this->db->query("UPDATE " . DB_PREFIX . "customer SET telephone = '" . $this->db->escape($merge_value) . "' WHERE email = '" . $this->db->escape($data['email']) . "'");
				} elseif (strpos($merge_mapping, ':address_id')) {
					$country_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE iso_code_2 = '" . $this->db->escape($merge_value['country']) . "'");
					$zone_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE (`name` = '" . $this->db->escape($merge_value['state']) . "' OR `code` = '" . $this->db->escape($merge_value['state']) . "') AND country_id = '" . $this->db->escape($country->row['country_id']) . "'");
					$this->db->query("
						UPDATE " . DB_PREFIX . "address SET
						address_1 = '" . $this->db->escape($merge_value['addr1']) . "',
						address_2 = '" . $this->db->escape($merge_value['addr2']) . "',
						city = '" . $this->db->escape($merge_value['city']) . "',
						zone_id = " . ($zone_query->num_rows ? (int)$zone->row['zone_id'] : 0) . ",
						postcode = '" . $this->db->escape($merge_value['zip']) . "',
						country_id = " . ($country_query->num_rows ? (int)$country->row['country_id'] : 0) . "
						WHERE address_id = " . (int)$customer_query->row['address_id'] . "
					");
				} else {
					$merge_mapping_split = explode(':', $merge_mapping);
					if ($merge_mapping_split[1] != 'customer_id') {
						$this->db->query("UPDATE `" . DB_PREFIX . $merge_mapping_split[0] . "` SET `" . $merge_mapping_split[1] . "` = '" . $this->db->escape($merge_value) . "' WHERE email = '" . $this->db->escape($data['email']) . "'");
					}
				}
			}
			
			$success = true;
			
		} elseif ($type == 'upemail' && in_array('profile', $webhooks)) {
			
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET email = '" . $this->db->escape($data['new_email']) . "' WHERE email = '" . $this->db->escape($data['old_email']) . "'");
			$success = true;
			
		}
		
		if ($settings['testing_mode'] && $success) {
			$this->log->write(strtoupper($this->name) . ' WEBHOOK SUCCESS: ' . $type . ' ' . $data['email'] . ' (List ID ' . $data['list_id'] . ')');
		}
	}
	
	//==============================================================================
	// createCustomer()
	//==============================================================================
	private function createCustomer($data) {
		$settings = $this->getSettings();
		
		$merge_mapping = array();
		foreach ($settings as $key => $value) {
			if (strpos($key, $data['list_id']) !== 0 || is_array($value)) {
				continue;
			}
			if (strpos($value, ':firstname')) {
				$merge_mapping['firstname'] = str_replace($data['list_id'] . '_', '', $key);
			} elseif (strpos($value, ':lastname')) {
				$merge_mapping['lastname'] = str_replace($data['list_id'] . '_', '', $key);
			} elseif (strpos($value, ':telephone')) {
				$merge_mapping['telephone'] = str_replace($data['list_id'] . '_', '', $key);
			} elseif (strpos($value, ':address_id')) {
				$merge_mapping['address'] = str_replace($data['list_id'] . '_', '', $key);
			}
		}
		
		$customer = array(
			'status'			=> (int)($settings['autocreate'] == 2),
			'customer_group_id'	=> (!empty($data['customer_group_id']) ? $data['customer_group_id'] : $this->config->get('config_customer_group_id')),
			'email'				=> (isset($data['email_address'])) ? $data['email_address'] : $data['email'],
			'firstname'			=> (isset($merge_mapping['firstname']) &&!empty($data['merges'][$merge_mapping['firstname']]) ? $data['merges'][$merge_mapping['firstname']] : ''),
			'lastname'			=> (isset($merge_mapping['lastname']) &&!empty($data['merges'][$merge_mapping['lastname']]) ? $data['merges'][$merge_mapping['lastname']] : ''),
			'telephone'			=> (isset($merge_mapping['telephone']) && !empty($data['merges'][$merge_mapping['telephone']]) ? $data['merges'][$merge_mapping['telephone']] : ''),
			'address'			=> (isset($merge_mapping['address']) &&!empty($data['merges'][$merge_mapping['address']]) ? $data['merges'][$merge_mapping['address']] : array()),
			'ip'				=> $data['ip_opt'],
			'password'			=> rand(),
		);
		
		$this->db->query("
			INSERT INTO " . DB_PREFIX . "customer SET
			status = " . (int)$customer['status'] . ",
			approved = 1,
			newsletter = 1,
			customer_group_id = " . (int)$customer['customer_group_id'] . ",
			email = '" . $this->db->escape($customer['email']) . "',
			firstname = '" . $this->db->escape($customer['firstname']) . "',
			lastname = '" . $this->db->escape($customer['lastname']) . "',
			telephone = '" . $this->db->escape($customer['telephone']) . "',
			ip = '" . $this->db->escape($customer['ip']) . "',
			password = '" . $this->db->escape(md5($customer['password'])) . "',
			date_added = NOW()
		");
		
		if (!isset($customer['address']['addr1']))		$customer['address']['addr1']	= '';
		if (!isset($customer['address']['addr2']))		$customer['address']['addr2']	= '';
		if (!isset($customer['address']['city']))		$customer['address']['city']	= '';
		if (!isset($customer['address']['zip']))		$customer['address']['zip']	= '';
		if (!isset($customer['address']['country']))	$customer['address']['country']	= '';
		if (!isset($customer['address']['state']))		$customer['address']['state'] = '';
		
		$customer_id = $this->db->getLastId();
		$country_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE iso_code_2 = '" . $this->db->escape($customer['address']['country']) . "'");
		$country_id = ($country_query->num_rows) ? $country_query->row['country_id'] : 0;
		$zone_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE (`name` = '" . $this->db->escape($customer['address']['state']) . "' OR `code` = '" . $this->db->escape($customer['address']['state']) . "') AND country_id = " . (int)$country_id);
		$zone_id = ($zone_query->num_rows) ? $zone_query->row['zone_id'] : 0;
		
		$this->db->query("
			INSERT INTO " . DB_PREFIX . "address SET
			customer_id = " . (int)$customer_id . ",
			firstname = '" . $this->db->escape($customer['firstname']) . "',
			lastname = '" . $this->db->escape($customer['lastname']) . "',
			address_1 = '" . $this->db->escape($customer['address']['addr1']) . "',
			address_2 = '" . $this->db->escape($customer['address']['addr2']) . "',
			city = '" . $this->db->escape($customer['address']['city']) . "',
			zone_id = " . (int)$zone_id . ",
			postcode = '" . $this->db->escape($customer['address']['zip']) . "',
			country_id = " . (int)$country_id . "
		");
		
		$address_id = $this->db->getLastId();
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = " . (int)$address_id . " WHERE customer_id = " . (int)$customer_id);
		
		$language = (!empty($this->session->data['language'])) ? $this->session->data['language'] : $this->config->get('config_language');
		$email_subject = str_replace('[store]', $this->config->get('config_name'), $settings['emailtext_subject_' . $language]);
		$email_body = html_entity_decode(str_replace(array('[store]', '[password]'), array($this->config->get('config_name'), $customer['password']), $settings['emailtext_body_' . $language]), ENT_QUOTES, 'UTF-8');
		
		if ($settings['email_password'] && $customer['status']) {
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			
			if (version_compare(VERSION, '2.0', '<')) {
				$mail->hostname = $this->config->get('config_smtp_host');
				$mail->username = $this->config->get('config_smtp_username');
				$mail->password = html_entity_decode($this->config->get('config_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->port = $this->config->get('config_smtp_port');
				$mail->timeout = $this->config->get('config_smtp_timeout');
			} elseif (version_compare(VERSION, '2.0.2', '<')) {
				$mail = new Mail($this->config->get('config_mail'));
			} else {
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');			
			}
			
			$mail->setSubject($email_subject);
			$mail->setHtml($email_body);
			$mail->setSender(str_replace(array(',', '&'), array('', 'and'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setTo($customer['email']);
			$mail->send();
		}
		
		if ($settings['testing_mode']) {
			$this->log->write(strtoupper($this->name) . ' CUSTOMER CREATED: ' . $customer['firstname'] . ' ' . $customer['lastname'] . ' (' . $customer['email'] . ')');
		}
	}
	
	//==============================================================================
	// Private functions
	//==============================================================================
	private function getSettings() {
		if (!empty($this->settings)) {
			return $this->settings;
		}
		
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
		
		$this->settings = $settings;
		return $settings;
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
	
	private function ruleViolation($rules, $rule, $value) {
		$violation = false;
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
		
		if ($this->config->get($this->name . '_testing_mode') && $violation) {
			$this->log->write(strtoupper($this->name) . ': Mapping for list ID ' . $rules['list'] . ' ignored due to violating rule "' . $rule . ' ' . $comparison . ' ' . implode(', ', $rules[$rule][$comparison]) . '" with value "' . (is_array($value) ? implode(',', $value) : $value) . '"');
		}
		
		return $violation;
	}
	
	private function inRange($value, $range_list, $charge_type = '', $list) {
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
		
		if ($this->config->get($this->name . '_testing_mode') && (strpos($charge_type, ' not') ? $in_range : !$in_range)) {
			$this->log->write(strtoupper($this->name) . ': mapping for list ID ' . $list . ' ignored for violating rule "' . $charge_type . (strpos($charge_type, ' not') ? ' is not ' : ' is ') . implode(', ', $range_list) . '" with value "' . $value . '"');
		}
		
		return $in_range;
	}
	
	//==============================================================================
	// curlRequest()
	//==============================================================================
	private function curlRequest($request, $api, $data = array()) {
		$apikey = $this->config->get($this->name . '_apikey');
		$data_center = explode('-', $apikey);
		$url = 'https://' . (isset($data_center[1]) ? $data_center[1] : 'us1') . '.api.mailchimp.com/3.0/';
		
		if ($request == 'GET') {
			$curl = curl_init($url . $api . '?' . http_build_query($data));
		} else {
			$curl = curl_init($url . $api);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
			if ($request != 'POST') {
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $request);
			}
		}
		
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_TIMEOUT, 300);
		curl_setopt($curl, CURLOPT_USERPWD, ' :' . $this->config->get($this->name . '_apikey'));
		$response = json_decode(curl_exec($curl), true);
		
		if ($this->config->get($this->name . '_testing_mode') == 'debug') {
			$this->log->write($request . ' ' . $url . $api);
			$this->log->write('Data Sent: ' . print_r($data, true));
			$this->log->write('Data Received: ' . print_r($response, true));
		}
		
		if (curl_error($curl)) {
			$response['error'] = 'CURL ERROR #' . curl_errno($curl) . ' - ' . curl_error($curl);
		} elseif (empty($response) && $request != 'DELETE') {
			$response['error'] = 'Empty CURL gateway response';
		} elseif (!empty($response['detail'])) {
			$response['error'] = $response['detail'];
			if (isset($response['errors'])) {
				foreach ($response['errors'] as $error) {
					$response['error'] .= ' '. $error['message'];
				}
			}
		}
		curl_close($curl);
		
		if (!empty($response['error']) && $this->config->get($this->name . '_testing_mode')) {
			$this->log->write(strtoupper($this->name) . ' ERROR: ' . $response['error']);
		}
		return $response;
	}
}
?>
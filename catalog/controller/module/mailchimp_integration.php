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

class ControllerModuleMailchimpIntegration extends Controller {
	private $type = 'module';
	private $name = 'mailchimp_integration';
	private $popup = false;
	
	public function popup() {
		if (!$this->config->get($this->name . '_modules_popup')) return;
		
		$this->popup = $this->config->get($this->name . '_modules_popup');
		
		if (version_compare(VERSION, '2.0', '<')) {
			$this->index();
		} else {
			return $this->index();
		}
	}
	
	public function index() {

		$data['default_fields'] = array('firstname', 'lastname', 'telephone', 'address', 'city', 'postcode', 'country', 'zone');

		$data['settings'] = $this->getSettings();
		$data['type'] = $this->type;
		$data['name'] = $this->name;
		
		$data['popup'] = $this->popup;
		if (empty($_COOKIE[$this->name . '_popup']) && $data['popup'] == 'auto') {
			setcookie($this->name . '_popup', 'triggered');
			$data['trigger_popup'] = true;
		}
		
		$data = array_merge($data, $this->load->language('account/address'));
		$data = array_merge($data, $this->load->language('account/edit'));
		$data['language'] = $this->session->data['language'];
		
		if (empty($data['settings']['status'])) {
			return;
		}
		
		if (empty($data['settings']['apikey']) || empty($data['settings']['listid'])) {
			echo '<span style="color: red">You must fill in your API Key, and choose a list to sync with in the List Settings tab, before the module will work.</span>';
			return;
		}
		
		// Set customer data
		$data['email'] = $this->customer->getEmail();
		$data['subscribed'] = $this->customer->getNewsletter();
		$customer_group_id = (version_compare(VERSION, '2.0', '<')) ? (int)$this->customer->getCustomerGroupId() : (int)$this->customer->getGroupId();
		
		// Load library
		$address_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE address_id = " . (int)$this->customer->getAddressId());
		$address = (!empty($address_query->row)) ? $address_query->row : array();
		
		if (version_compare(VERSION, '2.1', '<')) $this->load->library($this->name);
		$mailchimp_integration = new MailChimp_Integration($this->config, $this->db, $this->log, $this->session, $this->url);
		$data['settings']['listid'] = $mailchimp_integration->determineList(array('customer_group_id' => $customer_group_id), $address);
		
		// Check for module lists choice
		if (!empty($data['settings']['modules_lists']) && empty($data['settings']['interest_groups'])) {
			if (empty($this->session->data['mailchimp_lists'])) {
				$this->session->data['mailchimp_lists'] = array();
				$this->session->data['mailchimp_subscribed_lists'] = array();
				
				foreach ($mailchimp_integration->getLists() as $list) {
					$this->session->data['mailchimp_lists'][$list['id']] = $list['name'];
					$member_info = $mailchimp_integration->getMemberInfo($list['id'], $data['email']);
					if (!empty($data['email']) && !empty($member_info)) {
						$this->session->data['mailchimp_subscribed_lists'][] = $list['id'];
					}
				}
			}
			$data['mailchimp_lists'] = $this->session->data['mailchimp_lists'];
			$data['subscribed_lists'] = $this->session->data['mailchimp_subscribed_lists'];
		}
		
		// Check for interest groups
		if (!empty($data['settings']['interest_groups'])
			&& file_exists(DIR_SYSTEM . 'library/mailchimp_integration_pro.php')
			&& (empty($data['settings']['display_routes']) || in_array($this->request->get['route'], explode(',', str_replace(array(' ', "\n", '%'), '', $data['settings']['display_routes']))))
		) {
			if (empty($this->session->data['mailchimp_interest_groups'])) {
				$this->session->data['mailchimp_interest_groups'] = $mailchimp_integration->getInterestGroups($data['settings']['listid']);
				$this->session->data['mailchimp_interests'] = array();
				
				$member_info = $mailchimp_integration->getMemberInfo($data['settings']['listid'], $data['email']);
				
				if ($this->customer->isLogged()) {
					foreach ($this->session->data['mailchimp_interest_groups'] as $interest_group) {
						foreach ($interest_group['interests'] as $interest) {
							if (!empty($member_info['interests'][$interest['id']])) {
								$this->session->data['mailchimp_interests'][$interest_group['id']][] = $interest['name'];
							}
						}
					}
				}
			}
			$data['interest_groups'] = $this->session->data['mailchimp_interest_groups'];
			$data['interests'] = $this->session->data['mailchimp_interests'];
		} else {
			$data['interest_groups'] = array();
			$data['interests'] = array();
		}
		
		// Set country/zone data
		$this->load->model('localisation/country');
		$data['countries'] = $this->model_localisation_country->getCountries();
		$data['country_id'] = $this->config->get('config_country_id');
		$data['zone_id'] = $this->config->get('config_zone_id');
		
		// Render
		$template = (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $this->type . '/' . $this->name . '.tpl')) ? $this->config->get('config_template') : 'default';
		$template_file = (version_compare(VERSION, '2.2', '<')) ? $template . '/template/' . $this->type . '/' . $this->name . '.tpl' : $this->type . '/' . $this->name;
		
		if (version_compare(VERSION, '2.0', '<')) {
			$this->data = $data;
			$this->template = $template_file;
			$this->render();
		} else {
			return $this->load->view($template_file, $data);
		}
	}
	
	//==============================================================================
	// Ajax functions
	//==============================================================================
	public function getZones() {
		$this->load->model('localisation/zone');
		$zones = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);
		echo json_encode($zones);
	}
	
	public function subscribe() {
		if (empty($this->request->post)) return;
		
		$customer_id = 0;
		$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE email = '". $this->db->escape($this->request->post['email']) . "'");
		
		if ($customer_query->num_rows) {
			$customer_id = $customer_query->row['customer_id'];
			$this->request->post['customer_id'] = $customer_id;
		}
		if (isset($this->request->post['address'])) {
			$this->request->post['address_1'] = $this->request->post['address'];
			unset($this->request->post['address']);
		}
		if (isset($this->request->post['country'])) {
			$this->request->post['country_id'] = $this->request->post['country'];
		}
		if (isset($this->request->post['zone'])) {
			$this->request->post['zone_id'] = $this->request->post['zone'];
		}
		
		if (version_compare(VERSION, '2.1', '<')) $this->load->library($this->name);
		$mailchimp_integration = new MailChimp_Integration($this->config, $this->db, $this->log, $this->session, $this->url);
		$data = array_merge($this->request->post, array('newsletter' => 1, 'update_existing' => (bool)($this->customer->isLogged() || !empty($settings['interest_groups']))));
		
		$error = $mailchimp_integration->send($data);
		
		if (!$error && $customer_id && empty($customer_query->row['newsletter'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = 1 WHERE customer_id = " . $customer_id);
		}
		
		echo str_replace('Use PUT to insert or update list members.', '', $error);
	}
	
	public function webhook() {
		if (!isset($this->request->post['type']) || !isset($this->request->post['data'])) {
			echo 'The MailChimp Integration webhook is working.';
			if (!isset($this->request->get['key'])) {
				echo ' However, no key is set. This is required for the webhook data to be processed properly.';
			}
			return;
		}
		
		if ($this->request->get['key'] != md5($this->config->get('config_encryption'))) {
			if ($this->config->get($this->name . '_testing_mode')) {
				$this->log->write(strtoupper($this->name) . ' WEBHOOK ERROR: webhook URL key ' . $this->request->get['key'] . ' does not match required key ' . md5($this->config->get('config_encryption')) . ' for action "' . $this->request->post['type'] . '" for e-mail address ' . $this->request->post['data']['email']);
			}
			return;
		}
		
		if (version_compare(VERSION, '2.1', '<')) $this->load->library($this->name);
		$mailchimp_integration = new MailChimp_Integration($this->config, $this->db, $this->log, $this->session, $this->url);
		$mailchimp_integration->webhook($this->request->post['type'], $this->request->post['data']);
	}
	
	//==============================================================================
	// Private functions
	//==============================================================================
	private function getSettings() {
		// custom, to work with list IDs and interest group IDs
		$settings = array();
		$settings_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `" . (version_compare(VERSION, '2.0.1', '<') ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "' ORDER BY `key` ASC");
		foreach ($settings_query->rows as $setting) {
			$value = $setting['value'];
			if ($setting['serialized']) {
				$value = (version_compare(VERSION, '2.1', '<')) ? unserialize($setting['value']) : json_decode($setting['value'], true);
			}
			$settings[str_replace($this->name . '_', '', $setting['key'])] = $value;
		}
		return $settings;
	}
}
?>
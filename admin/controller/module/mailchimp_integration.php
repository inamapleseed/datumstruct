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
	
	public function index() {
		$data = array(
			'type'				=> $this->type,
			'name'				=> $this->name,
			'autobackup'		=> false,
			'vqmod'				=> false,
			'save_type'			=> 'keepediting',
			'token'				=> $this->session->data['token'],
			'permission'		=> $this->user->hasPermission('modify', $this->type . '/' . $this->name),
			'exit'				=> $this->url->link('extension/' . $this->type . '&token=' . $this->session->data['token'], '', 'SSL'),
		);
		
		$this->loadSettings($data);
		
		// Convert old settings
		$old_settings_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `key` = '" . $this->name . "_data'");
		if ($old_settings_query->num_rows) {
			foreach (unserialize($old_settings_query->row['value']) as $key => $value) {
				// extension-specific
				if ($key == 'webhooks') continue;
				// end
				if (is_array($value)) {
					if (is_int(key($value))) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `" . (version_compare(VERSION, '2.0.1', '<') ? 'group' : 'code') . "` = '" . $this->name . "', `key` = '" . $this->name . "_" . $key . "', `value` = '" . implode(';', $value) . "'");
					} else {
						foreach ($value as $value_key => $value_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `" . (version_compare(VERSION, '2.0.1', '<') ? 'group' : 'code') . "` = '" . $this->name . "', `key` = '" . $this->name . "_" . $key . "_" . $value_key . "', `value` = '" . $value_value . "'");
						}
					}
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `" . (version_compare(VERSION, '2.0.1', '<') ? 'group' : 'code') . "` = '" . $this->name . "', `key` = '" . $this->name . "_" . $key . "', `value` = '" . $value . "'");
				}
			}
			$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `key` = '" . $this->name . "_data'");
		}
		
		// extension-specific
		if (version_compare(VERSION, '2.1', '<')) $this->load->library($this->name);
		$mailchimp_integration = new MailChimp_Integration($this->config, $this->db, $this->log, $this->session, $this->url);
		
		$lists = $mailchimp_integration->getLists();
		$mailchimp_integration->addWebhooksAndStores($lists);
		
		unset($this->session->data['mailchimp_interest_groups']);
		unset($this->session->data['mailchimp_interests']);
		setcookie($this->name . '_popup', '', -1);
		
		//------------------------------------------------------------------------------
		// Data Arrays
		//------------------------------------------------------------------------------
		$data['currency_array'] = array($this->config->get('config_currency') => '');
		$this->load->model('localisation/currency');
		foreach ($this->model_localisation_currency->getCurrencies() as $currency) {
			$data['currency_array'][$currency['code']] = $currency['code'];
		}
		
		$data['customer_group_array'] = array(0 => $data['text_guests']);
		$this->load->model((version_compare(VERSION, '2.1', '<') ? 'sale' : 'customer') . '/customer_group');
		foreach ($this->{'model_' . (version_compare(VERSION, '2.1', '<') ? 'sale' : 'customer') . '_customer_group'}->getCustomerGroups() as $customer_group) {
			$data['customer_group_array'][$customer_group['customer_group_id']] = $customer_group['name'];
		}
		
		$data['geo_zone_array'] = array(0 => $data['text_everywhere_else']);
		$this->load->model('localisation/geo_zone');
		foreach ($this->model_localisation_geo_zone->getGeoZones() as $geo_zone) {
			$data['geo_zone_array'][$geo_zone['geo_zone_id']] = $geo_zone['name'];
		}
		
		$data['language_array'] = array($this->config->get('config_language') => '');
		$data['language_flags'] = array();
		$this->load->model('localisation/language');
		foreach ($this->model_localisation_language->getLanguages() as $language) {
			$data['language_array'][$language['code']] = $language['name'];
			$data['language_flags'][$language['code']] = (version_compare(VERSION, '2.2', '<')) ? 'view/image/flags/' . $language['image'] : 'language/' . $language['code'] . '/' . $language['code'] . '.png';
		}
		
		$data['store_array'] = array(0 => $this->config->get('config_name'));
		$store_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store ORDER BY name");
		foreach ($store_query->rows as $store) {
			$data['store_array'][$store['store_id']] = $store['name'];
		}
		
		if (!empty($data['saved']['apikey'])) {
			$data['mailchimp_lists'] = array(0 => $data['standard_select']);
			foreach ($lists as $list) {
				$data['mailchimp_lists'][$list['id']] = $list['name'];
			}
		}
		if (empty($data['saved']['apikey']) || !empty($data['mailchimp_lists']['error'])) {
			$data['mailchimp_lists'] = array(0 => $data['text_enter_an_api_key']);
		}
		
		//------------------------------------------------------------------------------
		// Extension Settings
		//------------------------------------------------------------------------------
		$data['settings'] = array();
		
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '
				<div id="syncing">' . $data['text_syncing'] . '</div>
				<style type="text/css">
					#syncing {
						display: none;
						background: #000;
						opacity: 0.5;
						color: #FFF;
						font-size: 100px;
						text-align: center;
						position: fixed;
						top: 0;
						left: 0;
						height: 100%;
						width: 100%;
						padding-top: 10%;
						z-index: 10000;
					}
				</style>
			',
		);
		
		$data['settings'][] = array(
			'type'		=> 'tabs',
			'tabs'		=> array('extension_settings', 'list_settings', 'merge_tags', 'interest_groups', 'ecommerce', 'module_settings'),
		);
		$data['settings'][] = array(
			'key'		=> 'extension_settings',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-info text-center" style="padding-bottom: 20px">' . $data['help_extension_settings'] . '</div>',
		);
		$data['settings'][] = array(
			'key'		=> 'status',
			'type'		=> 'select',
			'options'	=> array(0 => $data['text_disabled'], 1 => $data['text_enabled']),
		);
		$data['settings'][] = array(
			'key'		=> 'testing_mode',
			'type'		=> 'select',
			'options'	=> array(0 => $data['text_disabled'], 1 => $data['text_enabled'], 'debug' => $data['text_enabled_with_full_logging']),
		);
		$data['settings'][] = array(
			'key'		=> 'apikey',
			'type'		=> 'text',
			'attributes'=> array('style' => 'width: 300px !important'),
		);
		$data['settings'][] = array(
			'key'		=> 'double_optin',
			'type'		=> 'select',
			'options'	=> array(0 => $data['text_disabled'], 1 => $data['text_enabled']),
			'default'	=> 1,
		);
		$data['settings'][] = array(
			'key'		=> 'webhooks',
			'type'		=> 'checkboxes',
			'options'	=> array(
				'subscribe'		=> $data['text_subscribes'],
				'unsubscribe'	=> $data['text_unsubscribes'],
				'profile'		=> $data['text_profile_updates'],
				'cleaned'		=> $data['text_cleaned_addresses'],
			),
		);
		
		$customer_groups = $data['customer_group_array'];
		$customer_groups[0] = $data['text_no_change'];
		$data['settings'][] = array(
			'key'		=> 'subscribed_group',
			'type'		=> 'select',
			'options'	=> $customer_groups,
		);
		$data['settings'][] = array(
			'key'		=> 'unsubscribed_group',
			'type'		=> 'select',
			'options'	=> $customer_groups,
		);
		
		$data['settings'][] = array(
			'key'		=> 'manual_sync',
			'type'		=> 'html',
			'content'	=> '
				' . $data['text_starting_customer_id'] . ' <input type="text" id="starting-customer-id" class="form-control medium" style="margin-bottom: 5px" /><br />
				' . $data['text_ending_customer_id'] . ' <input type="text" id="ending-customer-id" class="form-control medium" style="margin-bottom: 5px" /><br />
				<a class="btn btn-primary" onclick="sync()">' . $data['button_sync_subscribers'] . '</a>
				<script type="text/javascript">
					function sync() {
						if (confirm("' . $data['text_sync_note'] . '")) {
							$("#syncing").fadeIn();
							$.ajax({
								url: "index.php?route=' . $this->type . '/' . $this->name . '/sync&token=' . $data['token'] . '&start=" + $("#starting-customer-id").val() + "&end=" + $("#ending-customer-id").val(),
								success: function(data) {
									alert(data);
									$("#syncing").fadeOut();
								},
								error: function(xhr, status, error) {
									alert(xhr.responseText ? xhr.responseText : error);
									$("#syncing").fadeOut();
								}
							});
						}
					}
				</script>
			',
		);
		
		//------------------------------------------------------------------------------
		// Customer Creation Settings
		//------------------------------------------------------------------------------
		$data['settings'][] = array(
			'key'		=> 'customer_creation_settings',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'key'		=> 'autocreate',
			'type'		=> 'select',
			'options'	=> array(0 => $data['text_no'], 1 => $data['text_yes_disabled'], 2 => $data['text_yes_enabled']),
		);
		$data['settings'][] = array(
			'key'		=> 'email_password',
			'type'		=> 'select',
			'options'	=> array(0 => $data['text_no'], 1 => $data['text_yes']),
		);
		$data['settings'][] = array(
			'key'		=> 'emailtext_subject',
			'type'		=> 'multilingual_text',
			'default'	=> '[store]: Customer Account Created',
		);
		$data['settings'][] = array(
			'key'		=> 'emailtext_body',
			'type'		=> 'multilingual_textarea',
			'default'	=> "Your customer account has been successfully created. Your new password is:\n<br /><br />\n[password]\n<br /><br />\nThanks for choosing [store]!",
			'attributes'=> array('style' => 'height: 120px !important'),
		);
		
		//------------------------------------------------------------------------------
		// List Settings
		//------------------------------------------------------------------------------
		$data['settings'][] = array(
			'key'		=> 'list_settings',
			'type'		=> 'tab',
		);
		$data['settings'][] = array(
			'key'		=> 'list_settings',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'key'		=> 'listid',
			'type'		=> 'select',
			'options'	=> $data['mailchimp_lists'],
		);
		$data['settings'][] = array(
			'key'		=> 'list_mapping',
			'type'		=> 'heading',
		);
		
		if (file_exists(DIR_SYSTEM . 'library/mailchimp_integration_pro.php')) {
			include(DIR_SYSTEM . 'library/mailchimp_integration_pro.php');
		} else {
			$data['settings'][] = array(
				'type'		=> 'html',
				'content'	=> '<div class="text-info text-center" style="padding-bottom: 20px">' . $data['help_list_mapping'] . '</div>',
			);
			
			//------------------------------------------------------------------------------
			// Merge Tags
			//------------------------------------------------------------------------------
			$data['settings'][] = array(
				'key'		=> 'merge_tags',
				'type'		=> 'tab',
			);
			$data['settings'][] = array(
				'key'		=> 'merge_tags',
				'type'		=> 'heading',
			);
			$data['settings'][] = array(
				'type'		=> 'html',
				'content'	=> '<div class="text-info" style="padding-bottom: 20px">' . $data['help_merge_tags'] . '</div>',
			);
			if (empty($data['mailchimp_lists']['error'])) {
				foreach ($data['mailchimp_lists'] as $list_id => $list_name) {
					if (!$list_id) continue;
					$data['settings'][] = array(
						'key'		=> $list_id . '_FNAME',
						'type'		=> 'hidden',
						'default'	=> 'customer:firstname',
					);
					$data['settings'][] = array(
						'key'		=> $list_id . '_LNAME',
						'type'		=> 'hidden',
						'default'	=> 'customer:lastname',
					);
					$data['settings'][] = array(
						'key'		=> $list_id . '_ADDRESS',
						'type'		=> 'hidden',
						'default'	=> 'customer:address_id',
					);
					$data['settings'][] = array(
						'key'		=> $list_id . '_PHONE',
						'type'		=> 'hidden',
						'default'	=> 'customer:telephone',
					);
				}
			}
			
			//------------------------------------------------------------------------------
			// Interest Groups
			//------------------------------------------------------------------------------
			$data['settings'][] = array(
				'key'		=> 'interest_groups',
				'type'		=> 'tab',
			);
			$data['settings'][] = array(
				'key'		=> 'interest_groups',
				'type'		=> 'heading',
			);
			$data['settings'][] = array(
				'type'		=> 'html',
				'content'	=> '<div class="text-info text-center" style="padding-bottom: 20px">' . $data['help_interestgroups'] . '</div>',
			);
			
			//------------------------------------------------------------------------------
			// E-commerce
			//------------------------------------------------------------------------------
			$data['settings'][] = array(
				'key'		=> 'ecommerce',
				'type'		=> 'tab',
			);
			$data['settings'][] = array(
				'key'		=> 'ecommerce',
				'type'		=> 'heading',
			);
			$data['settings'][] = array(
				'type'		=> 'html',
				'content'	=> '<div class="text-info text-center" style="padding-bottom: 20px">' . $data['help_ecommerce_upgrade'] . '</div>',
			);
			
			//------------------------------------------------------------------------------
			// Module Settings
			//------------------------------------------------------------------------------
			$data['settings'][] = array(
				'key'		=> 'module_settings',
				'type'		=> 'tab',
			);
			$data['settings'][] = array(
				'key'		=> 'module_settings',
				'type'		=> 'heading',
			);
			$data['settings'][] = array(
				'type'		=> 'html',
				'content'	=> '<div class="text-info text-center" style="padding-bottom: 20px">' . $data['help_module_settings'] . '</div>',
			);
			foreach (array('firstname', 'lastname') as $field) {
				$data['settings'][] = array(
					'key'		=> 'modules_' . $field,
					'type'		=> 'select',
					'options'	=> array('hide' => $data['text_hide'], 'optional' => $data['text_optional'], 'required' => $data['text_required']),
				);
			}
		}
		
		$data['settings'][] = array(
			'key'		=> 'modules_redirect',
			'type'		=> 'text',
		);
		$data['settings'][] = array(
			'key'		=> 'modules_popup',
			'type'		=> 'select',
			'options'	=> array(0 => $data['text_no'], 'manual' => $data['text_yes_trigger_manually'], 'auto' => $data['text_yes_trigger_automatically']),
		);
		
		$data['settings'][] = array(
			'key'		=> 'module_text',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'key'		=> 'moduletext_heading',
			'type'		=> 'multilingual_text',
			'default'	=> 'Newsletter',
		);
		$data['settings'][] = array(
			'key'		=> 'moduletext_top',
			'type'		=> 'multilingual_text',
		);
		if (file_exists(DIR_SYSTEM . 'library/mailchimp_integration_pro.php')) {
			$data['settings'][] = array(
				'key'		=> 'moduletext_list',
				'type'		=> 'multilingual_text',
				'default'	=> 'List:',
			);
		}
		$data['settings'][] = array(
			'key'		=> 'moduletext_button',
			'type'		=> 'multilingual_text',
			'default'	=> 'Subscribe',
		);
		$data['settings'][] = array(
			'key'		=> 'moduletext_emptyfield',
			'type'		=> 'multilingual_text',
			'default'	=> 'Please fill in the required fields!',
		);
		$data['settings'][] = array(
			'key'		=> 'moduletext_invalidemail',
			'type'		=> 'multilingual_text',
			'default'	=> 'Please use a valid email address!',
		);
		$data['settings'][] = array(
			'key'		=> 'moduletext_success',
			'type'		=> 'multilingual_text',
			'default'	=> 'Success! Please click the confirmation link in the e-mail sent to you.',
		);
		$data['settings'][] = array(
			'key'		=> 'moduletext_error',
			'type'		=> 'multilingual_text',
		);
		$data['settings'][] = array(
			'key'		=> 'moduletext_subscribed',
			'type'		=> 'multilingual_text',
			'default'	=> 'You are subscribed as [email]. Edit your newsletter preferences <a href="index.php?route=account/newsletter">here</a>.',
		);
		
		$data['settings'][] = array(
			'key'		=> 'module_locations',
			'type'		=> 'heading',
		);
		if (version_compare(VERSION, '2.0', '<')) {
			$layouts = array();
			$this->load->model('design/layout');
			foreach ($this->model_design_layout->getLayouts() as $layout) {
				$layouts[$layout['layout_id']] = $layout['name'];
			}
			
			$positions = array(
				'content_top'		=> $data['text_content_top'],
				'column_left'		=> $data['text_column_left'],
				'column_right'		=> $data['text_column_right'],
				'content_bottom'	=> $data['text_content_bottom'],
			);
			
			$table = 'module';
			$sortby = 'layout';
			$data['settings'][] = array(
				'key'		=> $table,
				'type'		=> 'table_start',
				'columns'	=> array('action', 'status', 'layout', 'position', 'sort_order'),
			);
			foreach ($this->getTableRowNumbers($data, $table, $sortby) as $num => $rules) {
				$prefix = $table . '_' . $num . '_';
				$data['settings'][] = array(
					'type'		=> 'row_start',
				);
				$data['settings'][] = array(
					'key'		=> 'delete',
					'type'		=> 'button',
				);
				$data['settings'][] = array(
					'type'		=> 'column',
				);
				$data['settings'][] = array(
					'key'		=> $prefix . 'status',
					'type'		=> 'select',
					'options'	=> array(0 => $data['text_disabled'], 1 => $data['text_enabled']),
				);
				$data['settings'][] = array(
					'type'		=> 'column',
				);
				$data['settings'][] = array(
					'key'		=> $prefix . 'layout_id',
					'type'		=> 'select',
					'options'	=> $layouts,
				);
				$data['settings'][] = array(
					'type'		=> 'column',
				);
				$data['settings'][] = array(
					'key'		=> $prefix . 'position',
					'type'		=> 'select',
					'options'	=> $positions,
				);
				$data['settings'][] = array(
					'type'		=> 'column',
				);
				$data['settings'][] = array(
					'key'		=> $prefix . 'sort_order',
					'type'		=> 'text',
					'attributes'=> array('style' => 'width: 50px !important'),
				);
				$data['settings'][] = array(
					'type'		=> 'row_end',
				);
			}
			$data['settings'][] = array(
				'type'		=> 'table_end',
				'buttons'	=> 'add_row',
				'text'		=> 'button_add_module',
			);
		} else {
			$data['settings'][] = array(
				'key'		=> 'module_locations',
				'type'		=> 'html',
				'content'	=> '<div style="padding-top: 9px">' . $data['help_module_locations'] . ' <a href="index.php?route=design/layout&token=' . $data['token'] . '">' . (version_compare(VERSION, '2.1', '<') ? ' System >' : '') . ' Design > Layouts</a></div>',
			);
		}
		
		//------------------------------------------------------------------------------
		// end settings
		//------------------------------------------------------------------------------
		
		$this->document->setTitle($data['heading_title']);
		
		if (version_compare(VERSION, '2.0', '<')) {
			$this->data = $data;
			$this->template = $this->type . '/' . $this->name . '.tpl';
			$this->children = array(
				'common/header',	
				'common/footer',
			);
			$this->response->setOutput($this->render());
		} else {
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$this->response->setOutput($this->load->view($this->type . '/' . $this->name . (version_compare(VERSION, '2.2', '<') ? '.tpl' : ''), $data));
		}
	}
	
	//==============================================================================
	// Setting functions
	//==============================================================================
	private $encryption_key = '';
	private $columns = 7;
	
	private function getTableRowNumbers(&$data, $table, $sorting) {
		$groups = array();
		$rules = array();
		
		foreach ($data['saved'] as $key => $setting) {
			if (preg_match('/' . $table . '_(\d+)_' . $sorting . '/', $key, $matches)) {
				$groups[$setting][] = $matches[1];
			}
			if (preg_match('/' . $table . '_(\d+)_rule_(\d+)_type/', $key, $matches)) {
				$rules[$matches[1]][] = $matches[2];
			}
		}
		
		if (empty($groups)) $groups = array('' => array('1'));
		ksort($groups, defined('SORT_NATURAL') ? SORT_NATURAL : SORT_REGULAR);
		
		foreach ($rules as $key => $rule) {
			ksort($rules[$key], defined('SORT_NATURAL') ? SORT_NATURAL : SORT_REGULAR);
		}
		
		$data['used_rows'][$table] = array();
		$rows = array();
		foreach ($groups as $group) {
			foreach ($group as $num) {
				$data['used_rows'][preg_replace('/module_(\d+)_/', '', $table)][] = $num;
				$rows[$num] = (empty($rules[$num])) ? array() : $rules[$num];
			}
		}
		sort($data['used_rows'][$table]);
		
		return $rows;
	}
	
	public function loadSettings(&$data) {
		$backup_type = (empty($data)) ? 'manual' : 'auto';
		if ($backup_type == 'manual' && !$this->user->hasPermission('modify', $this->type . '/' . $this->name)) {
			return;
		}
		
		unset($this->session->data[$this->name . '_settings']);
		
		// Load saved settings
		$data['saved'] = array();
		$settings_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `" . (version_compare(VERSION, '2.0.1', '<') ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "' ORDER BY `key` ASC");
		
		foreach ($settings_query->rows as $setting) {
			$key = str_replace($this->name . '_', '', $setting['key']);
			$value = $setting['value'];
			if ($setting['serialized']) {
				$value = (version_compare(VERSION, '2.1', '<')) ? unserialize($setting['value']) : json_decode($setting['value'], true);
			}
			
			$data['saved'][$key] = $value;
			
			if (is_array($value)) {
				foreach ($value as $num => $value_array) {
					foreach ($value_array as $k => $v) {
						$data['saved'][$key . '_' . $num . '_' . $k] = $v;
					}
				}
			}
		}
		
		// Load language and run standard checks
		$data = array_merge($data, $this->load->language($this->type . '/' . $this->name));
		
		if (ini_get('max_input_vars') && ((ini_get('max_input_vars') - count($data['saved'])) < 50)) {
			$data['warning'] = $data['standard_max_input_vars'];
		}
		/*
		if (!empty($data['vqmod']) && !file_exists(DIR_APPLICATION . '../vqmod/vqmod.php')) {
			$data['warning'] = $data['standard_vqmod'];
		}
		*/
		if ($this->type == 'total' && version_compare(VERSION, '2.2', '>=')) {
			file_put_contents(DIR_CATALOG . 'model/' . $this->type . '/' . $this->name . '.php', str_replace('public function getTotal(&$total_data, &$order_total, &$taxes) {', 'public function getTotal($total) { $total_data = &$total["totals"]; $order_total = &$total["total"]; $taxes = &$total["taxes"];', file_get_contents(DIR_CATALOG . 'model/' . $this->type . '/' . $this->name . '.php')));
		}
		
		// Set save type and skip auto-backup if not needed
		if (!empty($data['saved']['autosave'])) {
			$data['save_type'] = 'auto';
		}
		
		if ($backup_type == 'auto' && empty($data['autobackup'])) {
			return;
		}
		
		// Create settings auto-backup file
		$manual_filepath = DIR_LOGS . $this->name . $this->encryption_key . '.backup';
		$auto_filepath = DIR_LOGS . $this->name . $this->encryption_key . '.autobackup';
		$filepath = ($backup_type == 'auto') ? $auto_filepath : $manual_filepath;
		if (file_exists($filepath)) unlink($filepath);
		
		if ($this->columns == 3) {
			file_put_contents($filepath, 'EXTENSION	SETTING	VALUE' . "\n", FILE_APPEND|LOCK_EX);
		} elseif ($this->columns == 5) {
			file_put_contents($filepath, 'EXTENSION	SETTING	NUMBER	SUB-SETTING	VALUE' . "\n", FILE_APPEND|LOCK_EX);
		} else {
			file_put_contents($filepath, 'EXTENSION	SETTING	NUMBER	SUB-SETTING	SUB-NUMBER	SUB-SUB-SETTING	VALUE' . "\n", FILE_APPEND|LOCK_EX);
		}
		
		foreach ($data['saved'] as $key => $value) {
			if (is_array($value)) continue;
			
			$parts = explode('|', preg_replace(array('/_(\d+)_/', '/_(\d+)/'), array('|$1|', '|$1'), $key));
			
			$line = $this->name . "\t" . $parts[0] . "\t";
			for ($i = 1; $i < $this->columns - 2; $i++) {
				$line .= (isset($parts[$i]) ? $parts[$i] : '') . "\t";
			}
			$line .= str_replace(array("\t", "\n"), array('    ', '\n'), $value) . "\n";
			
			file_put_contents($filepath, $line, FILE_APPEND|LOCK_EX);
		}
		
		$data['autobackup_time'] = date('Y-M-d @ g:i a');
		$data['backup_time'] = (file_exists($manual_filepath)) ? date('Y-M-d @ g:i a', filemtime($manual_filepath)) : '';
		
		if ($backup_type == 'manual') {
			echo $data['autobackup_time'];
		}
	}
	
	public function saveSettings() {
		if (!$this->user->hasPermission('modify', $this->type . '/' . $this->name)) {
			echo 'PermissionError';
			return;
		}
		
		unset($this->session->data[$this->name . '_settings']);
		
		if ($this->request->get['saving'] == 'manual') {
			$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `" . (version_compare(VERSION, '2.0.1', '<') ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "' AND `key` != '" . $this->db->escape($this->name . '_module') . "'");
		}
		
		$module_id = 0;
		$modules = array();
		$module_instance = false;
		
		foreach ($this->request->post as $key => $value) {
			if (strpos($key, 'module_') === 0) {
				$parts = explode('_', $key, 3);
				$module_id = $parts[1];
				$modules[$parts[1]][$parts[2]] = $value;
				if ($parts[2] == 'module_id') $module_instance = true;
			} else {
				if ($this->request->get['saving'] == 'auto') {
					$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `" . (version_compare(VERSION, '2.0.1', '<') ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "' AND `key` = '" . $this->db->escape($this->name . '_' . $key) . "'");
				}
				$this->db->query("
					INSERT INTO " . DB_PREFIX . "setting SET
					`store_id` = 0,
					`" . (version_compare(VERSION, '2.0.1', '<') ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "',
					`key` = '" . $this->db->escape($this->name . '_' . $key) . "',
					`value` = '" . $this->db->escape(stripslashes(is_array($value) ? implode(';', $value) : $value)) . "',
					`serialized` = 0
				");
			}
		}
		
		if (version_compare(VERSION, '2.0.1', '<')) {
			$module_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `" . (version_compare(VERSION, '2.0.1', '<') ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "'AND `key` = '" . $this->db->escape($this->name . '_module') . "'");
			if ($module_query->num_rows) {
				foreach (unserialize($module_query->row['value']) as $key => $value) {
					foreach ($value as $k => $v) {
						if (!isset($modules[$key][$k]) && $key != $module_id && $module_instance) {
							$modules[$key][$k] = $v;
						}
					}
				}
			}
			
			if (isset($modules[0])) {
				$index = 1;
				while (isset($modules[$index])) {
					$index++;
				}
				$modules[$index] = $modules[0];
				unset($modules[0]);
				$modules[$index]['module_id'] = $index;
			}
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `" . (version_compare(VERSION, '2.0.1', '<') ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "'AND `key` = '" . $this->db->escape($this->name . '_module') . "'");
			$this->db->query("
				INSERT INTO " . DB_PREFIX . "setting SET
				`store_id` = 0,
				`" . (version_compare(VERSION, '2.0.1', '<') ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "',
				`key` = '" . $this->db->escape($this->name . '_module') . "',
				`value` = '" . $this->db->escape(serialize($modules)) . "',
				`serialized` = 1
			");
		} else {
			foreach ($modules as $module_id => $module) {
				if (!$module_id) {
					$this->db->query("
						INSERT INTO " . DB_PREFIX . "module SET
						`name` = '" . $this->db->escape($module['name']) . "',
						`code` = '" . $this->db->escape($this->name) . "',
						`setting` = ''
					");
					$module_id = $this->db->getLastId();
					$module['module_id'] = $module_id;
				}
				$module_settings = (version_compare(VERSION, '2.1', '<')) ? serialize($module) : json_encode($module);
				$this->db->query("
					UPDATE " . DB_PREFIX . "module SET
					`name` = '" . $this->db->escape($module['name']) . "',
					`code` = '" . $this->db->escape($this->name) . "',
					`setting` = '" . $this->db->escape($module_settings) . "'
					WHERE module_id = " . (int)$module_id . "
				");
			}
		}
	}
	
	public function deleteSetting() {
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `" . (version_compare(VERSION, '2.0.1', '<') ? 'group' : 'code') . "` = '" . $this->db->escape($this->name) . "' AND `key` = '" . $this->db->escape($this->name . '_' . str_replace('[]', '', $this->request->get['setting'])) . "'");
	}
		
	//==============================================================================
	// Custom functions
	//==============================================================================
	public function sync() {
		if (!$this->user->hasPermission('modify', $this->type . '/' . $this->name)) {
			echo 'PermissionError';
			return;
		}
		
		if (!$this->config->get($this->name . '_apikey') || !$this->config->get($this->name . '_listid')) {
			$language = $this->load->language($this->type . '/' . $this->name);
			echo $language['text_sync_error'];
			return;
		}
			
		if (version_compare(VERSION, '2.1', '<')) $this->load->library($this->name);
		$mailchimp_integration = new MailChimp_Integration($this->config, $this->db, $this->log, $this->session, $this->url);
		echo $mailchimp_integration->sync($this->request->get['start'], $this->request->get['end']);
	}
	
	public function syncOrders() {
		if (!$this->user->hasPermission('modify', $this->type . '/' . $this->name)) {
			echo 'PermissionError';
			return;
		}
		
		if (!$this->config->get($this->name . '_apikey') || !$this->config->get($this->name . '_listid')) {
			$language = $this->load->language($this->type . '/' . $this->name);
			echo $language['text_sync_error'];
			return;
		}
			
		if (version_compare(VERSION, '2.1', '<')) $this->load->library($this->name);
		$mailchimp_integration = new MailChimp_Integration($this->config, $this->db, $this->log, $this->session, $this->url);
		echo $mailchimp_integration->syncOrders($this->request->get['start'], $this->request->get['end']);
	}
}
?>
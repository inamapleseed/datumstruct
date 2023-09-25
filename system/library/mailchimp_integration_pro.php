<?php
//==============================================================================
// MailChimp Integration Pro v230.5
// 
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
// 
// All code within this file is copyright Clear Thinking, LLC.
// You may not copy or reuse code within this file without written permission.
//==============================================================================

	//------------------------------------------------------------------------------
	// Data Arrays
	//------------------------------------------------------------------------------
	$data['entry_listid'] = $data['pro_entry_listid'];
	$data['heading_title'] = $data['pro_heading_title'];
	
	$data['order_status_array'] = array();
	$this->load->model('localisation/order_status');
	foreach ($this->model_localisation_order_status->getOrderStatuses() as $order_status) {
		$data['order_status_array'][$order_status['order_status_id']] = $order_status['name'];
	}
	
	$data['rule_options'] = array(
		'location_criteria'	=> array('city', 'geo_zone', 'postcode'),
		'order_criteria'	=> array('currency', 'customer_group', 'language', 'store'),
	);
	
	if (!empty($data['saved']['apikey'])) {
		foreach ($data['mailchimp_lists'] as $list_id => $list_name) {
			if (!$list_id) continue;
			$data['merge_tags'][$list_id] = $mailchimp_integration->getMergeTags($list_id);
			$data['interest_groups'][$list_id] = $mailchimp_integration->getInterestGroups($list_id);
		}
	}
	
	$customer_fields = array('' => $data['text_leave_blank']);
	foreach (array('address', 'customer') as $table) {
		$customer_fields[$table] = '';
		$columns = array();
		foreach ($this->db->query("DESCRIBE " . DB_PREFIX . $table)->rows as $column) {
			$columns[$table . ':' . $column['Field']] = $column['Field'];
		}
		asort($columns);
		$customer_fields = array_merge($customer_fields, $columns);
	}
	
	if (version_compare(VERSION, '2.0', '>=')) {
		$customer_fields['custom_field'] = '';
		$custom_fields = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_field_description WHERE language_id = " . (int)$this->config->get('config_language_id'))->rows;
		foreach ($custom_fields as $custom_field) {
			$customer_fields['custom_field:' . $custom_field['custom_field_id']] = $custom_field['name'];
		}
	}
	
	//------------------------------------------------------------------------------
	// List Settings
	//------------------------------------------------------------------------------
	$data['settings'][] = array(
		'type'		=> 'html',
		'content'	=> '<div class="text-info text-center" style="padding-bottom: 20px">' . $data['pro_help_list_mapping'] . '</div>',
	);
	
	$table = 'mapping';
	$sortby = 'list';
	$data['settings'][] = array(
		'key'		=> $table,
		'type'		=> 'table_start',
		'columns'	=> array('action', 'list', 'rules'),
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
			'key'		=> $prefix . 'list',
			'type'		=> 'select',
			'options'	=> $data['mailchimp_lists'],
		);
		$data['settings'][] = array(
			'type'		=> 'column',
		);
		$data['settings'][] = array(
			'key'		=> $prefix . 'rule',
			'type'		=> 'rule',
			'rules'		=> $rules,
		);
		$data['settings'][] = array(
			'type'		=> 'row_end',
		);
	}
	$data['settings'][] = array(
		'type'		=> 'table_end',
		'buttons'	=> 'add_row',
		'text'		=> 'button_add_mapping',
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
		'content'	=> '<div class="text-info text-center" style="padding-bottom: 20px">' . $data['pro_help_merge_tags'] . '</div>',
	);
	if (!empty($data['merge_tags'])) {
		foreach ($data['merge_tags'] as $list_id => $merge_tags) {
			$data['settings'][] = array(
				'type'		=> 'html',
				'content'	=> '<hr />',
			);
			foreach ($merge_tags as $merge) {
				if ($merge['tag'] == 'EMAIL') {
					continue;
				} elseif ($merge['tag'] == 'FNAME') {
					$default = 'customer:firstname';
				} elseif ($merge['tag'] == 'LNAME') {
					$default = 'customer:lastname';
				} elseif ($merge['tag'] == 'ADDRESS') {
					$default = 'customer:address_id';
				} elseif ($merge['tag'] == 'PHONE') {
					$default = 'customer:telephone';
				} else {
					$default = '';
				}
				$data['settings'][] = array(
					'key'		=> $list_id . '_' . $merge['tag'],
					'title'		=> $data['mailchimp_lists'][$list_id] . ' - ' . $merge['tag'] . ':',
					'type'		=> 'select',
					'options'	=> $customer_fields,
					'default'	=> $default,
				);
			}
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
		'content'	=> '<div class="text-info text-center" style="padding-bottom: 20px">' . $data['pro_help_interestgroups'] . '</div>',
	);
	$data['settings'][] = array(
		'key'		=> 'interest_groups',
		'type'		=> 'select',
		'options'	=> array(0 => $data['text_no'], 1 => $data['text_yes']),
	);
	$data['settings'][] = array(
		'key'		=> 'display_routes',
		'type'		=> 'textarea',
	);
	$data['settings'][] = array(
		'key'		=> 'moduletext_interestgroups',
		'type'		=> 'multilingual_text',
		'default'	=> 'Please choose your interests below',
	);
	$data['settings'][] = array(
		'key'		=> 'moduletext_updatebutton',
		'type'		=> 'multilingual_text',
		'default'	=> 'Update',
	);
	$data['settings'][] = array(
		'key'		=> 'moduletext_updated',
		'type'		=> 'multilingual_text',
		'default'	=> 'Your interests have been successfully updated.',
	);
	if (!empty($data['interest_groups'])) {
		foreach ($data['interest_groups'] as $list_id => $interest_groups) {
			if (empty($interest_groups)) continue;
			$data['settings'][] = array(
				'type'		=> 'heading',
				'text'		=> '"' . $data['mailchimp_lists'][$list_id] . '" ' . $data['heading_interest_groups'],
			);
			$data['settings'][] = array(
				'type'		=> 'html',
				'content'	=> '<div class="text-info text-center" style="padding-bottom: 5px">' . $data['help_interestgroup_text'] . '</div>',
			);
			foreach ($interest_groups as $interest_group) {
				$data['settings'][] = array(
					'type'		=> 'html',
					'content'	=> '<hr />',
				);
				$data['settings'][] = array(
					'key'		=> $list_id . '_' . $interest_group['id'],
					'title'		=> '<strong>"' . $interest_group['title'] . '" ' . $data['entry_group_title'] . '</strong>',
					'type'		=> 'multilingual_text',
					'default'	=> $interest_group['title'],
				);
				foreach ($interest_group['interests'] as $interest) {
					$data['settings'][] = array(
						'key'		=> $list_id . '_' . $interest_group['id'] . '_' . $interest['id'],
						'title'		=> '"' . $interest['name'] . '" ' . $data['entry_option'],
						'type'		=> 'multilingual_text',
						'default'	=> $interest['name'],
					);
				}
			}
		}
	}
	
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
		'key'		=> 'ecommerce360',
		'type'		=> 'select',
		'options'	=> array(0 => $data['text_disabled'], 1 => $data['text_enabled']),
	);
	$data['settings'][] = array(
		'key'		=> 'sendcarts',
		'type'		=> 'select',
		'options'	=> array(0 => $data['text_no'], 1 => $data['text_yes']),
	);
	$data['settings'][] = array(
		'key'		=> 'ordertype',
		'type'		=> 'select',
		'options'	=> array('all' => $data['text_send_all_orders'], 'newsletter' => $data['text_send_newsletter_orders']),
		'default'	=> 'all',
	);
	
	if (version_compare(VERSION, '2.0', '<')) {
		$complete_status_id = $this->config->get('config_complete_status_id');
	} else {
		$complete_status_id = $this->config->get('config_processing_status');
		$complete_status_id = $complete_status_id[0];
	}
	
	$data['settings'][] = array(
		'key'		=> 'orderstatus',
		'type'		=> 'checkboxes',
		'options'	=> $data['order_status_array'],
		'default'	=> $complete_status_id,
	);
	$data['settings'][] = array(
		'key'		=> 'deletestatus',
		'type'		=> 'checkboxes',
		'options'	=> $data['order_status_array'],
	);
	$data['settings'][] = array(
		'key'		=> 'cookietime',
		'type'		=> 'text',
		'class'		=> 'short',
		'default'	=> '30',
	);
	$data['settings'][] = array(
		'key'		=> 'past_orders_sync',
		'type'		=> 'html',
		'content'	=> '
			' . $data['text_starting_order_id'] . ' <input type="text" id="starting-order-id" class="form-control medium" style="margin-bottom: 5px" /><br />
			' . $data['text_ending_order_id'] . ' <input type="text" id="ending-order-id" class="form-control medium" style="margin-bottom: 5px" /><br />
			<a class="btn btn-primary" onclick="syncOrders()">' . $data['button_sync_orders'] . '</a>
			<script type="text/javascript">
				function syncOrders() {
					if (confirm("' . $data['text_sync_note'] . '")) {
						$("#syncing").fadeIn();
						$.ajax({
							url: "index.php?route=' . $this->type . '/' . $this->name . '/syncOrders&token=' . $data['token'] . '&start=" + $("#starting-order-id").val() + "&end=" + $("#ending-order-id").val(),
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
	
	// Stores
	$data['settings'][] = array(
		'key'		=> 'stores',
		'type'		=> 'heading',
	);
	foreach ($data['store_array'] as $store_id => $store_name) {
		$data['settings'][] = array(
			'key'		=> 'store-' . $store_id . '-list',
			'type'		=> 'select',
			'options'	=> $data['mailchimp_lists'],
			'title'		=> $store_name . ': <div class="help-text">' . $data['help_stores'] . '</div>',
		);
	}
	$data['settings'][] = array(
		'type'		=> 'html',
		'content'	=> '<div class="well text-info text-center pad-top">' . $data['help_link_tracking_box'] . '</div>',
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
	
	$mailchimp_lists = $data['mailchimp_lists'];
	array_shift($mailchimp_lists);
	$mailchimp_lists['allow_multiple'] = '<em>' . $data['text_allow_multiple_selections'] . '</em>';
	
	$data['settings'][] = array(
		'key'		=> 'modules_lists',
		'type'		=> 'checkboxes',
		'options'	=> $mailchimp_lists,
	);
	foreach (array('firstname', 'lastname', 'telephone', 'address', 'city', 'postcode') as $field) {
		$data['settings'][] = array(
			'key'		=> 'modules_' . $field,
			'type'		=> 'select',
			'options'	=> array('hide' => $data['text_hide'], 'optional' => $data['text_optional'], 'required' => $data['text_required']),
		);
	}
	$data['settings'][] = array(
		'key'		=> 'modules_zone',
		'type'		=> 'select',
		'options'	=> array('hide' => $data['text_hide'], 'show' => $data['text_show']),
	);
	$data['settings'][] = array(
		'key'		=> 'modules_country',
		'type'		=> 'select',
		'options'	=> array('hide' => $data['text_hide'], 'show' => $data['text_show']),
	);
	
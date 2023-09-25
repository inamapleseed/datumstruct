<?php
class ControllerCatalogDiscountCustomerGroup extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/customer_group_discount');
		
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/discount');
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_discount'] = $this->language->get('text_discount');
		$data['text_fixed'] = $this->language->get('text_fixed');
		$data['text_percentage'] = $this->language->get('text_percentage');
		
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_percentage'] = $this->language->get('entry_percentage');
		$data['entry_priority'] = $this->language->get('entry_priority');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_type'] = $this->language->get('entry_type');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		
		$this->load->language('module/discounts');
		$data['entry_override_special_price'] = $this->language->get('entry_override_special_price');
		$data['entry_override_discount_price'] = $this->language->get('entry_override_discount_price');
		
		$data['help_override_special_price'] = $this->language->get('help_override_special_price');
		$data['help_override_discount_price'] = $this->language->get('help_override_discount_price');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_remove'] = $this->language->get('button_remove');
		
		$data['error_permission'] = $this->language->get('error_permission');	
		
		$data['options'] = array('default', 'exclusive', 'override');
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->success)) {
			$data['success'] = $this->success;
		} else {
			$data['success'] = '';
		}
		
		$url = '';
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/discount_customer_group', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		$data['permission'] = $this->user->hasPermission('modify', 'catalog/discount_customer_group') ? 1 : 0;
		
		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['token'] = $this->session->data['token'];
		
		if (isset($this->request->post['customer_group_discount_status'])) {
			$data['customer_group_discount_status'] = $this->request->post['customer_group_discount_status'];
		} else {
			$data['customer_group_discount_status'] = $this->config->get('customer_group_discount_status');
		}

		if (isset($this->request->post['customer_group_discount_sort_order'])) {
			$data['customer_group_discount_sort_order'] = $this->request->post['customer_group_discount_sort_order'];
		} else {
			$data['customer_group_discount_sort_order'] = $this->config->get('customer_group_discount_sort_order');
		}
		
		if (isset($this->request->post['customer_group_discount_override_special_price'])) {
			$data['customer_group_discount_override_special_price'] = $this->request->post['customer_group_discount_override_special_price'];
		} else {
			$data['customer_group_discount_override_special_price'] = $this->config->get('customer_group_discount_override_special_price');
		}
		
		if (isset($this->request->post['customer_group_discount_override_discount_price'])) {
			$data['customer_group_discount_override_discount_price'] = $this->request->post['customer_group_discount_override_discount_price'];
		} else {
			$data['customer_group_discount_override_discount_price'] = $this->config->get('customer_group_discount_override_discount_price');
		}
		
		
		
		if (version_compare(VERSION, '2.1', '>=')) {
			$this->load->model('customer/customer_group');
			$customer_groups = $this->model_customer_customer_group->getCustomerGroups();
		} else {
			$this->load->model('sale/customer_group');
			$customer_groups = $this->model_sale_customer_group->getCustomerGroups();
		}
		
		array_unshift($customer_groups, array(
            'customer_group_id' => 0,
            'name' 				=> $this->language->get('text_anonymous')
        ));
        
		$data['customer_groups'] = $customer_groups;
		
		$discounts = $this->model_catalog_discount->getAllDiscounts('customer_group');
		
		if (isset($this->request->post['customer_group_discount'])) {
			$customer_group_discounts = $this->request->post['customer_group_discount'];
		} elseif (isset($discounts)) {
			$customer_group_discounts = $discounts;
		} else {
			$customer_group_discounts = array();
		}

		$data['customer_group_discounts'] = array();
		
		foreach ($customer_group_discounts as $customer_group_discount) {
			$data['customer_group_discounts'][] = array(
				'customer_group_discount_id' => $customer_group_discount['customer_group_discount_id'],
				'status'			=> $customer_group_discount['status'],
				'customer_group_id' => $customer_group_discount['customer_group_id'],
				'priority'          => $customer_group_discount['priority'],
				'percentage'       	=> $customer_group_discount['percentage'],
				'date_start'        => ($customer_group_discount['date_start'] != '0000-00-00') ? $customer_group_discount['date_start'] : '',
				'date_end'          => ($customer_group_discount['date_end'] != '0000-00-00') ? $customer_group_discount['date_end'] : ''
			);
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/discount_customer_group.tpl', $data));
	}
	
	public function saveDiscount() {
		
		$json = array();
		$this->load->language('catalog/customer_group_discount');
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			
			$this->load->model('setting/setting');
			$this->load->model('catalog/discount');
			
			parse_str(htmlspecialchars_decode($this->request->post['setting']), $settings);
			
			$this->model_setting_setting->editSetting('customer_group_discount', $settings);
			
			if(!empty($this->request->post['customer_group_discount'])) {
			
				parse_str(htmlspecialchars_decode($this->request->post['customer_group_discount']), $discount_data);

				$this->model_catalog_discount->setDiscount($discount_data['customer_group_discount'], 'customer_group');		
			} else {
				$this->model_catalog_discount->setDiscount(NULL, 'customer_group');	
			}
			
			$json['success'] = $this->language->get('text_success');

		} else {
			$json['error'] = $this->language->get('error_warning');
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
		
	}
	
	public function activate() {
		$this->load->language('catalog/customer_group_discount');
		$json = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['row'])) {
			$this->db->query("UPDATE `". DB_PREFIX . "customer_group_discount` SET `status` = '1' WHERE `customer_group_discount_id` = '" . (int)$this->request->post['row'] . "' ");
		}
		
		$json['success'] = $this->language->get('text_activated');
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function deactivate() {
		$this->load->language('catalog/customer_group_discount');
		$json = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['row'])) {
			$this->db->query("UPDATE `". DB_PREFIX . "customer_group_discount` SET `status` = '0' WHERE `customer_group_discount_id` = '" . (int)$this->request->post['row'] . "' ");
		}
		
		$json['success'] = $this->language->get('text_deactivated');
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
}
<?php
class ControllerCatalogDiscountManufacturer extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/manufacturer_discount');
		
		
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/discount');
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_discount'] = $this->language->get('text_discount');
		
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
		$data['entry_percentage'] = $this->language->get('entry_percentage');
		$data['entry_priority'] = $this->language->get('entry_priority');
		$data['entry_qty'] = $this->language->get('entry_qty');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
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
		$data['button_yes'] = $this->language->get('button_yes');
		$data['button_no'] = $this->language->get('button_no');	
		
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
			'href' => $this->url->link('catalog/discount_manufacturer', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		$data['permission'] = $this->user->hasPermission('modify', 'catalog/discount_manufacturer') ? 1 : 0;
		
		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['token'] = $this->session->data['token'];
		
		if (isset($this->request->post['manufacturer_discount_status'])) {
			$data['manufacturer_discount_status'] = $this->request->post['manufacturer_discount_status'];
		} else {
			$data['manufacturer_discount_status'] = $this->config->get('manufacturer_discount_status');
		}

		if (isset($this->request->post['manufacturer_discount_sort_order'])) {
			$data['manufacturer_discount_sort_order'] = $this->request->post['manufacturer_discount_sort_order'];
		} else {
			$data['manufacturer_discount_sort_order'] = $this->config->get('manufacturer_discount_sort_order');
		}
			
		if (isset($this->request->post['manufacturer_discount_override_special_price'])) {
			$data['manufacturer_discount_override_special_price'] = $this->request->post['manufacturer_discount_override_special_price'];
		} else {
			$data['manufacturer_discount_override_special_price'] = $this->config->get('manufacturer_discount_override_special_price');
		}
		
		if (isset($this->request->post['manufacturer_discount_override_discount_price'])) {
			$data['manufacturer_discount_override_discount_price'] = $this->request->post['manufacturer_discount_override_discount_price'];
		} else {
			$data['manufacturer_discount_override_discount_price'] = $this->config->get('manufacturer_discount_override_discount_price');
		}
		
		$this->load->model('catalog/manufacturer');
		
		$data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();
		
		if (version_compare(VERSION, '2.1', '>=')) {
			$this->load->model('customer/customer_group');
			$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
		} else {
			$this->load->model('sale/customer_group');
			$data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		}
		
		$discounts = $this->model_catalog_discount->getAllDiscounts('manufacturer');
		
		if (isset($this->request->post['manufacturer_discount'])) {
			$manufacturer_discounts = $this->request->post['manufacturer_discount'];
		} elseif (isset($discounts)) {
			$manufacturer_discounts = $discounts;
		} else {
			$manufacturer_discounts = array();
		}

		$data['manufacturer_discounts'] = array();
		
		foreach ($manufacturer_discounts as $manufacturer_discount) {
			$data['manufacturer_discounts'][] = array(
				'manufacturer_discount_id' => $manufacturer_discount['manufacturer_discount_id'],
				'status'			=> $manufacturer_discount['status'],
				'manufacturer_id'	=> $manufacturer_discount['manufacturer_id'],
				'customer_group_id' => $manufacturer_discount['customer_group_id'],
				'priority'          => $manufacturer_discount['priority'],
				'percentage'        => $manufacturer_discount['percentage'],
				'qty'				=> $manufacturer_discount['qty'],
				'date_start'        => ($manufacturer_discount['date_start'] != '0000-00-00') ? $manufacturer_discount['date_start'] : '',
				'date_end'          => ($manufacturer_discount['date_end'] != '0000-00-00') ? $manufacturer_discount['date_end'] : ''
			);
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/discount_manufacturer.tpl', $data));
	}
	
	public function saveDiscount() {
		
		$json = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->load->language('catalog/manufacturer_discount');
			$this->load->model('setting/setting');
			$this->load->model('catalog/discount');
			
			parse_str(htmlspecialchars_decode($this->request->post['setting']), $settings);
			
			$this->model_setting_setting->editSetting('manufacturer_discount', $settings);
			
			if(!empty($this->request->post['manufacturer_discount'])) {
				
				parse_str(htmlspecialchars_decode($this->request->post['manufacturer_discount']), $discount_data);

				$this->model_catalog_discount->setDiscount($discount_data['manufacturer_discount'], 'manufacturer');		
			} else {
				$this->model_catalog_discount->setDiscount(NULL, 'manufacturer');
			}
			
			$json['success'] = $this->language->get('text_success');

		} else {
			$json['error'] = $this->language->get('error_warning');
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
		
	}
	
	public function activate() {
		$this->load->language('catalog/manufacturer_discount');
		$json = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['row'])) {
			$this->db->query("UPDATE `". DB_PREFIX . "manufacturer_discount` SET `status` = '1' WHERE `manufacturer_discount_id` = '" . (int)$this->request->post['row'] . "' ");
		}
		
		$json['success'] = $this->language->get('text_activated');
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function deactivate() {
		$this->load->language('catalog/manufacturer_discount');
		$json = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['row'])) {
			$this->db->query("UPDATE `". DB_PREFIX . "manufacturer_discount` SET `status` = '0' WHERE `manufacturer_discount_id` = '" . (int)$this->request->post['row'] . "' ");
		}
		
		$json['success'] = $this->language->get('text_deactivated');
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
}
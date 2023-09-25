<?php
class ControllerCatalogDiscountOrdertotal extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/ordertotal_discount');

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
		$data['entry_ordertotal'] = $this->language->get('entry_ordertotal');
		$data['entry_discount'] = $this->language->get('entry_discount');
		$data['entry_priority'] = $this->language->get('entry_priority');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_type'] = $this->language->get('entry_type');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_remove'] = $this->language->get('button_remove');
		
		$data['error_permission'] = $this->language->get('error_permission');	
		
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
			'href' => $this->url->link('catalog/discount_ordertotal', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		$data['permission'] = $this->user->hasPermission('modify', 'catalog/discount_ordertotal') ? 1 : 0;
		
		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['token'] = $this->session->data['token'];
		
		if (isset($this->request->post['ordertotal_discount_status'])) {
			$data['ordertotal_discount_status'] = $this->request->post['ordertotal_discount_status'];
		} else {
			$data['ordertotal_discount_status'] = $this->config->get('ordertotal_discount_status');
		}

		if (isset($this->request->post['ordertotal_discount_sort_order'])) {
			$data['ordertotal_discount_sort_order'] = $this->request->post['ordertotal_discount_sort_order'];
		} else {
			$data['ordertotal_discount_sort_order'] = $this->config->get('ordertotal_discount_sort_order');
		}
		
		if (version_compare(VERSION, '2.1', '>=')) {
			$this->load->model('customer/customer_group');
			$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
		} else {
			$this->load->model('sale/customer_group');
			$data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		}
		
		$discounts = $this->model_catalog_discount->getAllDiscounts('ordertotal');
		
		if (isset($this->request->post['ordertotal_discount'])) {
			$ordertotal_discounts = $this->request->post['ordertotal_discount'];
		} elseif (isset($discounts)) {
			$ordertotal_discounts = $discounts;
		} else {
			$ordertotal_discounts = array();
		}

		$data['ordertotal_discounts'] = array();
		
		foreach ($ordertotal_discounts as $ordertotal_discount) {
			$data['ordertotal_discounts'][] = array(
				'ordertotal_discount_id' => $ordertotal_discount['ordertotal_discount_id'],
				'status'			=> $ordertotal_discount['status'],
				'ordertotal'		=> $ordertotal_discount['ordertotal'],
				'customer_group_id' => $ordertotal_discount['customer_group_id'],
				'priority'          => $ordertotal_discount['priority'],
				'type'				=> $ordertotal_discount['type'],
				'discount'        	=> $ordertotal_discount['percentage'],
				'date_start'        => ($ordertotal_discount['date_start'] != '0000-00-00') ? $ordertotal_discount['date_start'] : '',
				'date_end'          => ($ordertotal_discount['date_end'] != '0000-00-00') ? $ordertotal_discount['date_end'] : ''
			);
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/discount_ordertotal.tpl', $data));
	}
	
	public function saveDiscount() {
		
		$json = array();
		$this->load->language('catalog/ordertotal_discount');
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			
			$this->load->model('setting/setting');
			$this->load->model('catalog/discount');
			
			parse_str(htmlspecialchars_decode($this->request->post['setting']), $settings);
			
			$this->model_setting_setting->editSetting('ordertotal_discount', $settings);
			
			if(!empty($this->request->post['ordertotal_discount'])) {
			
				parse_str(htmlspecialchars_decode($this->request->post['ordertotal_discount']), $discount_data);

				$this->model_catalog_discount->setDiscount($discount_data['ordertotal_discount'], 'ordertotal');
						
			} else {
			
				$this->model_catalog_discount->setDiscount(NULL, 'ordertotal');
				
			}
			
			
			$json['success'] = $this->language->get('text_success');

		} else {
			$json['error'] = $this->language->get('error_warning');
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
		
	}
	
	public function activate() {
		$this->load->language('catalog/ordertotal_discount');
		$json = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['row'])) {
			$this->db->query("UPDATE `". DB_PREFIX . "ordertotal_discount` SET `status` = '1' WHERE `ordertotal_discount_id` = '" . (int)$this->request->post['row'] . "' ");
		}
		
		$json['success'] = $this->language->get('text_activated');
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function deactivate() {
		$this->load->language('catalog/ordertotal_discount');
		$json = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['row'])) {
			$this->db->query("UPDATE `". DB_PREFIX . "ordertotal_discount` SET `status` = '0' WHERE `ordertotal_discount_id` = '" . (int)$this->request->post['row'] . "' ");
		}
		
		$json['success'] = $this->language->get('text_deactivated');
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
}
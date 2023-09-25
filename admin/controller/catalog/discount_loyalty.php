<?php
class ControllerCatalogDiscountLoyalty extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/loyalty_discount');

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
		
		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');
		
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_ordertotal'] = $this->language->get('entry_ordertotal');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
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
			'href' => $this->url->link('catalog/discount_loyalty', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		$data['permission'] = $this->user->hasPermission('modify', 'catalog/discount_loyalty') ? 1 : 0;
		
		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['token'] = $this->session->data['token'];
		
		$this->model_catalog_discount->checkTableExist('loyalty');
		
		if (isset($this->request->post['loyalty_discount_status'])) {
			$data['loyalty_discount_status'] = $this->request->post['loyalty_discount_status'];
		} else {
			$data['loyalty_discount_status'] = $this->config->get('loyalty_discount_status');
		}

		if (isset($this->request->post['loyalty_discount_sort_order'])) {
			$data['loyalty_discount_sort_order'] = $this->request->post['loyalty_discount_sort_order'];
		} else {
			$data['loyalty_discount_sort_order'] = $this->config->get('loyalty_discount_sort_order');
		}
		
		if (version_compare(VERSION, '2.1', '>=')) {
			$this->load->model('customer/customer_group');
			$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
		} else {
			$this->load->model('sale/customer_group');
			$data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		}
		
		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		
		$discounts = $this->model_catalog_discount->getAllDiscounts('loyalty');
		
		if (isset($this->request->post['loyalty_discount'])) {
			$loyalty_discounts = $this->request->post['loyalty_discount'];
		} elseif (isset($discounts)) {
			$loyalty_discounts = $discounts;
		} else {
			$loyalty_discounts = array();
		}

		$data['loyalty_discounts'] = array();
		
		foreach ($loyalty_discounts as $loyalty_discount) {
			$data['loyalty_discounts'][] = array(
				'loyalty_discount_id' => $loyalty_discount['loyalty_discount_id'],
				'status'			=> $loyalty_discount['status'],
				'ordertotal'		=> $loyalty_discount['ordertotal'],
				'customer_group_id' => $loyalty_discount['customer_group_id'],
				'priority'          => $loyalty_discount['priority'],
				'order_status'		=> explode(',',$loyalty_discount['order_status']),
				'discount'        	=> $loyalty_discount['percentage'],
				'date_start'        => ($loyalty_discount['date_start'] != '0000-00-00') ? $loyalty_discount['date_start'] : '',
				'date_end'          => ($loyalty_discount['date_end'] != '0000-00-00') ? $loyalty_discount['date_end'] : ''
			);
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/discount_loyalty.tpl', $data));
	}
	
	public function saveDiscount() {
		
		$json = array();
		$this->load->language('catalog/loyalty_discount');
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			
			$this->load->model('setting/setting');
			$this->load->model('catalog/discount');
			
			parse_str(htmlspecialchars_decode($this->request->post['setting']), $settings);
			
			$this->model_setting_setting->editSetting('loyalty_discount', $settings);
			
			if(!empty($this->request->post['loyalty_discount'])) {
			
				parse_str(htmlspecialchars_decode($this->request->post['loyalty_discount']), $discount_data);

				$this->model_catalog_discount->setDiscount($discount_data['loyalty_discount'], 'loyalty');
						
			} else {
			
				$this->model_catalog_discount->setDiscount(NULL, 'loyalty');
				
			}
			
			$json['success'] = $this->language->get('text_success');

		} else {
			$json['error'] = $this->language->get('error_warning');
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
		
	}
	
	public function activate() {
		$this->load->language('catalog/loyalty_discount');
		$json = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['row'])) {
			$this->db->query("UPDATE `". DB_PREFIX . "loyalty_discount` SET `status` = '1' WHERE `loyalty_discount_id` = '" . (int)$this->request->post['row'] . "' ");
		}
		
		$json['success'] = $this->language->get('text_activated');
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function deactivate() {
		$this->load->language('catalog/loyalty_discount');
		$json = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['row'])) {
			$this->db->query("UPDATE `". DB_PREFIX . "loyalty_discount` SET `status` = '0' WHERE `loyalty_discount_id` = '" . (int)$this->request->post['row'] . "' ");
		}
		
		$json['success'] = $this->language->get('text_deactivated');
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
}
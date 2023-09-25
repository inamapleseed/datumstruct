<?php
class ControllerCatalogDiscountProduct extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/product_discount');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/discount');
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_discount'] = $this->language->get('text_discount');
		
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_percentage'] = $this->language->get('entry_percentage');
		$data['entry_price'] = $this->language->get('entry_price');
		$data['entry_priority'] = $this->language->get('entry_priority');
		$data['entry_quantity'] = $this->language->get('entry_quantity');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_yes'] = $this->language->get('button_yes');
		$data['button_no'] = $this->language->get('button_no');	
		
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
			'href' => $this->url->link('catalog/discount_product', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		$data['permission'] = $this->user->hasPermission('modify', 'catalog/discount_product') ? 1 : 0;
		
		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['token'] = $this->session->data['token'];
		
		if (isset($this->request->post['product_discount_status'])) {
			$data['product_discount_status'] = $this->request->post['product_discount_status'];
		} else {
			$data['product_discount_status'] = $this->config->get('product_discount_status');
		}

		if (isset($this->request->post['product_discount_sort_order'])) {
			$data['product_discount_sort_order'] = $this->request->post['product_discount_sort_order'];
		} else {
			$data['product_discount_sort_order'] = $this->config->get('product_discount_sort_order');
		}
		
		$this->load->model('catalog/product');
		
		$data['products'] = $this->model_catalog_product->getProducts();
		
		if (version_compare(VERSION, '2.1', '>=')) {
			$this->load->model('customer/customer_group');
			$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
		} else {
			$this->load->model('sale/customer_group');
			$data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		}
		
		$raw_discounts = $this->model_catalog_discount->getAllDiscounts('product');
		
		foreach ($raw_discounts as $product) {
			
			$discounts[$product['product_id']][] = array(
				'customer_group_id' => $product['customer_group_id'],
				'quantity'          => $product['quantity'],
				'priority'          => $product['priority'],
				'price'        		=> $product['price'],
				'date_start'        => ($product['date_start'] != '0000-00-00') ? $product['date_start'] : '',
				'date_end'          => ($product['date_end'] != '0000-00-00') ? $product['date_end'] : ''
			);
		}
		
		if (isset($this->request->post['product_discount'])) {
			$product_discounts = $this->request->post['product_discount'];
		} elseif (isset($discounts)) {
			$product_discounts = $discounts;
		} else {
			$product_discounts = array();
		}
				
		$data['product_discounts'] = array();
		
		foreach ($product_discounts as $product => $discounts) {
			
			$product_info = $this->model_catalog_discount->getProductInfo($product);
			
			$data['product_discounts'][$product]['product_data'] = array(
				'product_id'		=> $product,
				'product_name'		=> $product_info['name'],
				'product_price'		=> $product_info['price'],
			);
			
			foreach ($discounts as $discount) {
				$data['product_discounts'][$product]['discount_data'][] = array(
					'customer_group_id' => $discount['customer_group_id'],
					'quantity'          => $discount['quantity'],
					'priority'          => $discount['priority'],
					'percentage'        => (1 - $discount['price'] * (1/$product_info['price'])) * 100,
					'price'        		=> $discount['price'],
					'date_start'        => ($discount['date_start'] != '0000-00-00') ? $discount['date_start'] : '',
					'date_end'          => ($discount['date_end'] != '0000-00-00') ? $discount['date_end'] : ''
				);
			}
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/discount_product.tpl', $data));
	}
	
	public function saveDiscount() {
		
		$json = array();
		$this->load->language('catalog/product_discount');
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && !empty($this->request->post['product_discount'])) {
			
			$this->load->model('catalog/discount');

			parse_str(htmlspecialchars_decode($this->request->post['product_discount']), $discount_data);

			$json['discount_data'] = $discount_data;
			if (!empty($discount_data['product_discount'])){
				$json['return'] = $this->model_catalog_discount->setDiscount($discount_data['product_discount'], 'product');		
			} else {
				$json['return'] = $this->model_catalog_discount->setDiscount(NULL, 'product');		
			}
			
			$json['success'] = $this->language->get('text_success');
			
		} else {
			$json['error'] = $this->language->get('error_warning');
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
		
	}
	
}
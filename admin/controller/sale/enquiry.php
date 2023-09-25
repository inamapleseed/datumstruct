<?php
class ControllerSaleEnquiry extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('sale/enquiry');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/enquiry');

		$this->getList();
	}

	public function add() {
		$this->load->language('sale/enquiry');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/enquiry');

		$this->getForm();
	}

	public function edit() {
		$this->load->language('sale/enquiry');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/enquiry');

		$this->getForm();
	}
	
	public function delete() {
		$this->load->language('sale/enquiry');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/enquiry');

		if (isset($this->request->post['selected']) && $this->validate()) {
			foreach ($this->request->post['selected'] as $enquiry_order_id) {
				$this->model_sale_enquiry->deleteenquiry($enquiry_order_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_enquiry_order_id'])) {
				$url .= '&filter_enquiry_order_id=' . $this->request->get['filter_enquiry_order_id'];
			}
	
			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}
	
			if (isset($this->request->get['filter_enquiry_status'])) {
				$url .= '&filter_enquiry_status=' . $this->request->get['filter_enquiry_status'];
			}
	
			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}
	
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
	
			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
			}

			$this->response->redirect($this->url->link('sale/enquiry', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}
	
	protected function getList() {
		if (isset($this->request->get['filter_enquiry_order_id'])) {
			$filter_enquiry_order_id = $this->request->get['filter_enquiry_order_id'];
		} else {
			$filter_enquiry_order_id = null;
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = null;
		}

		if (isset($this->request->get['filter_enquiry_status'])) {
			$filter_enquiry_status = $this->request->get['filter_enquiry_status'];
		} else {
			$filter_enquiry_status = null;
		}

		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = null;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$filter_date_modified = $this->request->get['filter_date_modified'];
		} else {
			$filter_date_modified = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'o.enquiry_order_id';
		}

		if (isset($this->request->get['enquiry'])) {
			$enquiry = $this->request->get['enquiry'];
		} else {
			$enquiry = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_enquiry_order_id'])) {
			$url .= '&filter_enquiry_order_id=' . $this->request->get['filter_enquiry_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_enquiry_status'])) {
			$url .= '&filter_enquiry_status=' . $this->request->get['filter_enquiry_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['enquiry'])) {
			$url .= '&enquiry=' . $this->request->get['enquiry'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/enquiry', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['invoice'] = $this->url->link('sale/enquiry/invoice', 'token=' . $this->session->data['token'], true);
		$data['shipping'] = $this->url->link('sale/enquiry/shipping', 'token=' . $this->session->data['token'], true);
		$data['pickpacklist'] = $this->url->link('sale/enquiry/pickPackList', 'token=' . $this->session->data['token'], true);
		$data['add'] = $this->url->link('sale/enquiry/add', 'token=' . $this->session->data['token'], true);
		$data['delete'] = $this->url->link('sale/enquiry/delete', 'token=' . $this->session->data['token'], true);

		$data['enquirys'] = array();

		$filter_data = array(
			'filter_enquiry_order_id'   => $filter_enquiry_order_id,
			'filter_customer'	   	 	=> $filter_customer,
			'filter_enquiry_status'  	=> $filter_enquiry_status,
			'filter_total'         		=> $filter_total,
			'filter_date_added'    		=> $filter_date_added,
			'filter_date_modified' 		=> $filter_date_modified,
			'sort'                 		=> $sort,
			'enquiry'                	=> $enquiry,
			'start'                		=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'               	 	=> $this->config->get('config_limit_admin')
		);

		$enquiry_total = $this->model_sale_enquiry->getTotalEnqueries($filter_data);

		$results = $this->model_sale_enquiry->getEnqueries($filter_data);

		foreach ($results as $result) {
			$data['enquirys'][] = array(
				'enquiry_order_id'      => $result['enquiry_order_id'],
				'customer'      => $result['customer'],
				'enquiry_status'  => $result['order_status'] ? $result['order_status'] : $this->language->get('text_missing'),
				'total'         => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'shipping_code' => $result['shipping_code'],
				'view'          => $this->url->link('sale/enquiry/info', 'token=' . $this->session->data['token'] . '&enquiry_order_id=' . $result['enquiry_order_id'] . $url, true),
				'edit'          => $this->url->link('sale/enquiry/edit', 'token=' . $this->session->data['token'] . '&enquiry_order_id=' . $result['enquiry_order_id'] . $url, true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_missing'] = $this->language->get('text_missing');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['column_enquiry_order_id'] = $this->language->get('column_enquiry_order_id');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_date_modified'] = $this->language->get('column_date_modified');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_enquiry_order_id'] = $this->language->get('entry_enquiry_order_id');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_enquiry_status'] = $this->language->get('entry_enquiry_status');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_date_modified'] = $this->language->get('entry_date_modified');

		$data['button_invoice_print'] = $this->language->get('button_invoice_print');
		$data['button_shipping_print'] = $this->language->get('button_shipping_print');
		$data['text_pickpacklist'] = $this->language->get('text_pickpacklist');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_view'] = $this->language->get('button_view');
		$data['button_ip_add'] = $this->language->get('button_ip_add');

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_enquiry_order_id'])) {
			$url .= '&filter_enquiry_order_id=' . $this->request->get['filter_enquiry_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_enquiry_status'])) {
			$url .= '&filter_enquiry_status=' . $this->request->get['filter_enquiry_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if ($enquiry == 'ASC') {
			$url .= '&enquiry=DESC';
		} else {
			$url .= '&enquiry=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_order'] = $this->url->link('sale/enquiry', 'token=' . $this->session->data['token'] . '&sort=o.enquiry_order_id' . $url, true);
		$data['sort_customer'] = $this->url->link('sale/enquiry', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, true);
		$data['sort_status'] = $this->url->link('sale/enquiry', 'token=' . $this->session->data['token'] . '&sort=enquiry_status' . $url, true);
		$data['sort_total'] = $this->url->link('sale/enquiry', 'token=' . $this->session->data['token'] . '&sort=o.total' . $url, true);
		$data['sort_date_added'] = $this->url->link('sale/enquiry', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $url, true);
		$data['sort_date_modified'] = $this->url->link('sale/enquiry', 'token=' . $this->session->data['token'] . '&sort=o.date_modified' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_enquiry_order_id'])) {
			$url .= '&filter_enquiry_order_id=' . $this->request->get['filter_enquiry_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_enquiry_status'])) {
			$url .= '&filter_enquiry_status=' . $this->request->get['filter_enquiry_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['enquiry'])) {
			$url .= '&enquiry=' . $this->request->get['enquiry'];
		}

		$pagination = new Pagination();
		$pagination->total = $enquiry_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('sale/enquiry', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($enquiry_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($enquiry_total - $this->config->get('config_limit_admin'))) ? $enquiry_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $enquiry_total, ceil($enquiry_total / $this->config->get('config_limit_admin')));

		$data['filter_enquiry_order_id'] = $filter_enquiry_order_id;
		$data['filter_customer'] = $filter_customer;
		$data['filter_enquiry_status'] = $filter_enquiry_status;
		$data['filter_total'] = $filter_total;
		$data['filter_date_added'] = $filter_date_added;
		$data['filter_date_modified'] = $filter_date_modified;

		$data['sort'] = $sort;
		$data['enquiry'] = $enquiry;

		$this->load->model('localisation/order_status');

		$data['enquiry_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/enquiry_list', $data));
	}

	public function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['enquiry_order_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_ip_add'] = sprintf($this->language->get('text_ip_add'), $this->request->server['REMOTE_ADDR']);
		$data['text_product'] = $this->language->get('text_product');
		$data['text_voucher'] = $this->language->get('text_voucher');
		$data['text_enquiry_detail'] = $this->language->get('text_enquiry_detail');

		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_firstname'] = $this->language->get('entry_firstname');
		$data['entry_lastname'] = $this->language->get('entry_lastname');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_telephone'] = $this->language->get('entry_telephone');
		$data['entry_fax'] = $this->language->get('entry_fax');
		$data['entry_comment'] = $this->language->get('entry_comment');
		$data['entry_affiliate'] = $this->language->get('entry_affiliate');
		$data['entry_address'] = $this->language->get('entry_address');
		$data['entry_company'] = $this->language->get('entry_company');
		$data['entry_address_1'] = $this->language->get('entry_address_1');
		$data['entry_address_2'] = $this->language->get('entry_address_2');
		$data['entry_unit_no'] = $this->language->get('entry_unit_no');
		$data['entry_city'] = $this->language->get('entry_city');
		$data['entry_postcode'] = $this->language->get('entry_postcode');
		$data['entry_zone'] = $this->language->get('entry_zone');
		$data['entry_zone_code'] = $this->language->get('entry_zone_code');
		$data['entry_country'] = $this->language->get('entry_country');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_option'] = $this->language->get('entry_option');
		$data['entry_quantity'] = $this->language->get('entry_quantity');
		$data['entry_to_name'] = $this->language->get('entry_to_name');
		$data['entry_to_email'] = $this->language->get('entry_to_email');
		$data['entry_from_name'] = $this->language->get('entry_from_name');
		$data['entry_from_email'] = $this->language->get('entry_from_email');
		$data['entry_theme'] = $this->language->get('entry_theme');
		$data['entry_message'] = $this->language->get('entry_message');
		$data['entry_amount'] = $this->language->get('entry_amount');
		$data['entry_currency'] = $this->language->get('entry_currency');
		$data['entry_shipping_method'] = $this->language->get('entry_shipping_method');
		$data['entry_payment_method'] = $this->language->get('entry_payment_method');
		$data['entry_coupon'] = $this->language->get('entry_coupon');
		$data['entry_voucher'] = $this->language->get('entry_voucher');
		$data['entry_reward'] = $this->language->get('entry_reward');
		$data['entry_enquiry_status'] = $this->language->get('entry_enquiry_status');

		$data['column_product'] = $this->language->get('column_product');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');
		$data['button_refresh'] = $this->language->get('button_refresh');
		$data['button_product_add'] = $this->language->get('button_product_add');
		$data['button_voucher_add'] = $this->language->get('button_voucher_add');
		$data['button_apply'] = $this->language->get('button_apply');
		$data['button_upload'] = $this->language->get('button_upload');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_ip_add'] = $this->language->get('button_ip_add');

		$data['tab_enquiry'] = $this->language->get('tab_enquiry');
		$data['tab_customer'] = $this->language->get('tab_customer');
		$data['tab_payment'] = $this->language->get('tab_payment');
		$data['tab_shipping'] = $this->language->get('tab_shipping');
		$data['tab_product'] = $this->language->get('tab_product');
		$data['tab_voucher'] = $this->language->get('tab_voucher');
		$data['tab_total'] = $this->language->get('tab_total');

		$url = '';

		if (isset($this->request->get['filter_enquiry_order_id'])) {
			$url .= '&filter_enquiry_order_id=' . $this->request->get['filter_enquiry_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_enquiry_status'])) {
			$url .= '&filter_enquiry_status=' . $this->request->get['filter_enquiry_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['enquiry'])) {
			$url .= '&enquiry=' . $this->request->get['enquiry'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/enquiry', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['cancel'] = $this->url->link('sale/enquiry', 'token=' . $this->session->data['token'] . $url, true);

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->get['enquiry_order_id'])) {
			$enquiry_info = $this->model_sale_enquiry->getEnquiry($this->request->get['enquiry_order_id']);
		}

		if (!empty($enquiry_info)) {
			$data['enquiry_order_id'] = $this->request->get['enquiry_order_id'];
			$data['store_id'] = $enquiry_info['store_id'];
			$data['store_url'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;

			$data['customer'] = $enquiry_info['customer'];
			$data['customer_id'] = $enquiry_info['customer_id'];
			$data['customer_group_id'] = $enquiry_info['customer_group_id'];
			$data['firstname'] = $enquiry_info['firstname'];
			$data['lastname'] = $enquiry_info['lastname'];
			$data['email'] = $enquiry_info['email'];
			$data['telephone'] = $enquiry_info['telephone'];
			$data['fax'] = $enquiry_info['fax'];
			$data['account_custom_field'] = $enquiry_info['custom_field'];

			$this->load->model('customer/customer');

			$data['addresses'] = $this->model_customer_customer->getAddresses($enquiry_info['customer_id']);

			$data['payment_firstname'] = $enquiry_info['payment_firstname'];
			$data['payment_lastname'] = $enquiry_info['payment_lastname'];
			$data['payment_company'] = $enquiry_info['payment_company'];
			$data['payment_address_1'] = $enquiry_info['payment_address_1'];
			$data['payment_address_2'] = $enquiry_info['payment_address_2'];
			$data['payment_unit_no'] = $enquiry_info['payment_unit_no'];
			$data['payment_city'] = $enquiry_info['payment_city'];
			$data['payment_postcode'] = $enquiry_info['payment_postcode'];
			$data['payment_country_id'] = $enquiry_info['payment_country_id'];
			$data['payment_zone_id'] = $enquiry_info['payment_zone_id'];
			$data['payment_custom_field'] = $enquiry_info['payment_custom_field'];
			$data['payment_method'] = $enquiry_info['payment_method'];
			$data['payment_code'] = $enquiry_info['payment_code'];

			$data['shipping_firstname'] = $enquiry_info['shipping_firstname'];
			$data['shipping_lastname'] = $enquiry_info['shipping_lastname'];
			$data['shipping_company'] = $enquiry_info['shipping_company'];
			$data['shipping_address_1'] = $enquiry_info['shipping_address_1'];
			$data['shipping_address_2'] = $enquiry_info['shipping_address_2'];
			$data['shipping_unit_no'] = $enquiry_info['shipping_unit_no'];
			$data['shipping_city'] = $enquiry_info['shipping_city'];
			$data['shipping_postcode'] = $enquiry_info['shipping_postcode'];
			$data['shipping_country_id'] = $enquiry_info['shipping_country_id'];
			$data['shipping_zone_id'] = $enquiry_info['shipping_zone_id'];
			$data['shipping_custom_field'] = $enquiry_info['shipping_custom_field'];
			$data['shipping_method'] = $enquiry_info['shipping_method'];
			$data['shipping_code'] = $enquiry_info['shipping_code'];

			// Products
			$data['enquiry_products'] = array();

			$products = $this->model_sale_enquiry->getEnquiryProducts($this->request->get['enquiry_order_id']);

			foreach ($products as $product) {
				$data['enquiry_products'][] = array(
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'option'     => $this->model_sale_enquiry->getEnquiryOptions($this->request->get['enquiry_order_id'], $product['enquiry_order_product_id']),
					'quantity'   => $product['quantity'],
					'price'      => $product['price'],
					'total'      => $product['total'],
					'reward'     => $product['reward']
				);
			}

			// Vouchers
			$data['enquiry_vouchers'] = $this->model_sale_enquiry->getEnquiryVouchers($this->request->get['enquiry_order_id']);

			$data['coupon'] = '';
			$data['voucher'] = '';
			$data['reward'] = '';

			$data['enquiry_totals'] = array();

			$enquiry_totals = $this->model_sale_enquiry->getEnquiryTotals($this->request->get['enquiry_order_id']);

			foreach ($enquiry_totals as $enquiry_total) {
				// If coupon, voucher or reward points
				$start = strpos($enquiry_total['title'], '(') + 1;
				$end = strrpos($enquiry_total['title'], ')');

				if ($start && $end) {
					$data[$enquiry_total['code']] = substr($enquiry_total['title'], $start, $end - $start);
				}
			}

			$data['enquiry_order_status_id'] = $enquiry_info['enquiry_order_status_id'];
			$data['comment'] = $enquiry_info['comment'];
			$data['affiliate_id'] = $enquiry_info['affiliate_id'];
			$data['affiliate'] = $enquiry_info['affiliate_firstname'] . ' ' . $enquiry_info['affiliate_lastname'];
			$data['currency_code'] = $enquiry_info['currency_code'];
		} else {
			$data['enquiry_order_id'] = 0;
			$data['store_id'] = 0;
			$data['store_url'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;
			
			$data['customer'] = '';
			$data['customer_id'] = '';
			$data['customer_group_id'] = $this->config->get('config_customer_group_id');
			$data['firstname'] = '';
			$data['lastname'] = '';
			$data['email'] = '';
			$data['telephone'] = '';
			$data['fax'] = '';
			$data['customer_custom_field'] = array();

			$data['addresses'] = array();

			$data['payment_firstname'] = '';
			$data['payment_lastname'] = '';
			$data['payment_company'] = '';
			$data['payment_address_1'] = '';
			$data['payment_address_2'] = '';
			$data['payment_unit_no'] = '';
			$data['payment_city'] = '';
			$data['payment_postcode'] = '';
			$data['payment_country_id'] = '';
			$data['payment_zone_id'] = '';
			$data['payment_custom_field'] = array();
			$data['payment_method'] = '';
			$data['payment_code'] = '';

			$data['shipping_firstname'] = '';
			$data['shipping_lastname'] = '';
			$data['shipping_company'] = '';
			$data['shipping_address_1'] = '';
			$data['shipping_address_2'] = '';
			$data['shipping_unit_no'] = '';
			$data['shipping_city'] = '';
			$data['shipping_postcode'] = '';
			$data['shipping_country_id'] = '';
			$data['shipping_zone_id'] = '';
			$data['shipping_custom_field'] = array();
			$data['shipping_method'] = '';
			$data['shipping_code'] = '';

			$data['enquiry_products'] = array();
			$data['enquiry_vouchers'] = array();
			$data['enquiry_totals'] = array();

			$data['enquiry_status_id'] = $this->config->get('config_enquiry_status_id');
			$data['comment'] = '';
			$data['affiliate_id'] = '';
			$data['affiliate'] = '';
			$data['currency_code'] = $this->config->get('config_currency');

			$data['coupon'] = '';
			$data['voucher'] = '';
			$data['reward'] = '';
		}

		// Stores
		$this->load->model('setting/store');

		$data['stores'] = array();

		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		);

		$results = $this->model_setting_store->getStores();

		foreach ($results as $result) {
			$data['stores'][] = array(
				'store_id' => $result['store_id'],
				'name'     => $result['name']
			);
		}

		// Customer Groups
		$this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		// Custom Fields
		$this->load->model('customer/custom_field');

		$data['custom_fields'] = array();

		$filter_data = array(
			'sort'  => 'cf.sort_order',
			'enquiry' => 'ASC'
		);

		$custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);

		foreach ($custom_fields as $custom_field) {
			$data['custom_fields'][] = array(
				'custom_field_id'    => $custom_field['custom_field_id'],
				'custom_field_value' => $this->model_customer_custom_field->getCustomFieldValues($custom_field['custom_field_id']),
				'name'               => $custom_field['name'],
				'value'              => $custom_field['value'],
				'type'               => $custom_field['type'],
				'location'           => $custom_field['location'],
				'sort_order'         => $custom_field['sort_order']
			);
		}

		$this->load->model('localisation/order_status');

		$data['enquiry_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		$this->load->model('localisation/currency');

		$data['currencies'] = $this->model_localisation_currency->getCurrencies();

		$data['voucher_min'] = $this->config->get('config_voucher_min');

		$this->load->model('sale/voucher_theme');

		$data['voucher_themes'] = $this->model_sale_voucher_theme->getVoucherThemes();

		// API login
		$data['catalog'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;
		
		$this->load->model('user/api');

		$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

		if ($api_info) {
			
			$data['api_id'] = $api_info['api_id'];
			$data['api_key'] = $api_info['key'];
			$data['api_ip'] = $this->request->server['REMOTE_ADDR'];
		} else {
			$data['api_id'] = '';
			$data['api_key'] = '';
			$data['api_ip'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/enquiry_form', $data));
	}

	public function info() {

		$this->load->model('sale/enquiry');

		if (isset($this->request->get['enquiry_order_id'])) {
			$enquiry_order_id = $this->request->get['enquiry_order_id'];
		} else {
			$enquiry_order_id = 0;
		}

		$enquiry_info = $this->model_sale_enquiry->getEnquiry($enquiry_order_id);

		if ($enquiry_info) { 
			$this->load->language('sale/enquiry');

			$this->document->setTitle($this->language->get('heading_title'));

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_ip_add'] = sprintf($this->language->get('text_ip_add'), $this->request->server['REMOTE_ADDR']);
			$data['text_enquiry_detail'] = $this->language->get('text_enquiry_detail');
			$data['text_customer_detail'] = $this->language->get('text_customer_detail');
			$data['text_option'] = $this->language->get('text_option');
			$data['text_store'] = $this->language->get('text_store');
			$data['text_date_added'] = $this->language->get('text_date_added');
			$data['text_payment_method'] = $this->language->get('text_payment_method');
			$data['text_shipping_method'] = $this->language->get('text_shipping_method');
			$data['text_customer'] = $this->language->get('text_customer');
			$data['text_customer_group'] = $this->language->get('text_customer_group');
			$data['text_email'] = $this->language->get('text_email');
			$data['text_telephone'] = $this->language->get('text_telephone');
			$data['text_invoice'] = $this->language->get('text_invoice');
			$data['text_reward'] = $this->language->get('text_reward');
			$data['text_affiliate'] = $this->language->get('text_affiliate');
			$data['text_enquiry'] = sprintf($this->language->get('text_enquiry'), $this->request->get['enquiry_order_id']);
			$data['text_payment_address'] = $this->language->get('text_payment_address');
			$data['text_shipping_address'] = $this->language->get('text_shipping_address');
			$data['text_comment'] = $this->language->get('text_comment');
			$data['text_account_custom_field'] = $this->language->get('text_account_custom_field');
			$data['text_payment_custom_field'] = $this->language->get('text_payment_custom_field');
			$data['text_shipping_custom_field'] = $this->language->get('text_shipping_custom_field');
			$data['text_browser'] = $this->language->get('text_browser');
			$data['text_ip'] = $this->language->get('text_ip');
			$data['text_forwarded_ip'] = $this->language->get('text_forwarded_ip');
			$data['text_user_agent'] = $this->language->get('text_user_agent');
			$data['text_accept_language'] = $this->language->get('text_accept_language');
			$data['text_history'] = $this->language->get('text_history');
			$data['text_history_add'] = $this->language->get('text_history_add');
			$data['text_loading'] = $this->language->get('text_loading');

			$data['column_product'] = $this->language->get('column_product');
			$data['column_model'] = $this->language->get('column_model');
			$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_price'] = $this->language->get('column_price');
			$data['column_total'] = $this->language->get('column_total');

			$data['entry_enquiry_status'] = $this->language->get('entry_enquiry_status');
			$data['entry_notify'] = $this->language->get('entry_notify');
			$data['entry_override'] = $this->language->get('entry_override');
			$data['entry_comment'] = $this->language->get('entry_comment');

			$data['help_override'] = $this->language->get('help_override');

			$data['button_invoice_print'] = $this->language->get('button_invoice_print');
			$data['button_shipping_print'] = $this->language->get('button_shipping_print');
			$data['button_edit'] = $this->language->get('button_edit');
			$data['button_cancel'] = $this->language->get('button_cancel');
			$data['button_generate'] = $this->language->get('button_generate');
			$data['button_reward_add'] = $this->language->get('button_reward_add');
			$data['button_reward_remove'] = $this->language->get('button_reward_remove');
			$data['button_commission_add'] = $this->language->get('button_commission_add');
			$data['button_commission_remove'] = $this->language->get('button_commission_remove');
			$data['button_history_add'] = $this->language->get('button_history_add');
			$data['button_ip_add'] = $this->language->get('button_ip_add');

			$data['tab_history'] = $this->language->get('tab_history');
			$data['tab_additional'] = $this->language->get('tab_additional');

			$url = '';

			if (isset($this->request->get['filter_enquiry_order_id'])) {
				$url .= '&filter_enquiry_order_id=' . $this->request->get['filter_enquiry_order_id'];
			}

			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_enquiry_status'])) {
				$url .= '&filter_enquiry_status=' . $this->request->get['filter_enquiry_status'];
			}

			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['enquiry'])) {
				$url .= '&enquiry=' . $this->request->get['enquiry'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('sale/enquiry', 'token=' . $this->session->data['token'] . $url, true)
			);

			$data['shipping'] = $this->url->link('sale/enquiry/shipping', 'token=' . $this->session->data['token'] . '&enquiry_order_id=' . (int)$this->request->get['enquiry_order_id'], true);
			$data['invoice'] = $this->url->link('sale/enquiry/invoice', 'token=' . $this->session->data['token'] . '&enquiry_order_id=' . (int)$this->request->get['enquiry_order_id'], true);
			$data['edit'] = $this->url->link('sale/enquiry/edit', 'token=' . $this->session->data['token'] . '&enquiry_order_id=' . (int)$this->request->get['enquiry_order_id'], true);
			$data['cancel'] = $this->url->link('sale/enquiry', 'token=' . $this->session->data['token'] . $url, true);

			$data['token'] = $this->session->data['token'];

			$data['enquiry_order_id'] = $this->request->get['enquiry_order_id'];

			$data['store_id'] = $enquiry_info['store_id'];
			$data['store_name'] = $enquiry_info['store_name'];
			
			if ($enquiry_info['store_id'] == 0) {
				$data['store_url'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;
			} else {
				$data['store_url'] = $enquiry_info['store_url'];
			}

			if ($enquiry_info['invoice_no']) {
				$data['invoice_no'] = $enquiry_info['invoice_prefix'] . $enquiry_info['invoice_no'];
			} else {
				$data['invoice_no'] = '';
			}

			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($enquiry_info['date_added']));

			$data['firstname'] = $enquiry_info['firstname'];
			$data['lastname'] = $enquiry_info['lastname'];

			if ($enquiry_info['customer_id']) {
				$data['customer'] = $this->url->link('customer/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $enquiry_info['customer_id'], true);
			} else {
				$data['customer'] = '';
			}

			$this->load->model('customer/customer_group');

			$customer_group_info = $this->model_customer_customer_group->getCustomerGroup($enquiry_info['customer_group_id']);

			if ($customer_group_info) {
				$data['customer_group'] = $customer_group_info['name'];
			} else {
				$data['customer_group'] = '';
			}

			$data['email'] = $enquiry_info['email'];
			$data['telephone'] = $enquiry_info['telephone'];

			$data['shipping_method'] = $enquiry_info['shipping_method'];
			$data['payment_method'] = $enquiry_info['payment_method'];

			// Payment Address
			if ($enquiry_info['payment_address_format']) {
				$format = $enquiry_info['payment_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{unit_no}{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{unit_no}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);

			$replace = array(
				'firstname' => $enquiry_info['payment_firstname'],
				'lastname'  => $enquiry_info['payment_lastname'],
				'company'   => $enquiry_info['payment_company'],
				'address_1' => $enquiry_info['payment_address_1'],
				'address_2' => $enquiry_info['payment_address_2'],
				'unit_no'	=> $enquiry_info['payment_unit_no']?$enquiry_info['payment_unit_no'].', ':'',
				'city'      => $enquiry_info['payment_city'],
				'postcode'  => $enquiry_info['payment_postcode'],
				'zone'      => $enquiry_info['payment_zone'],
				'zone_code' => $enquiry_info['payment_zone_code'],
				'country'   => $enquiry_info['payment_country']
			);

			$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			// Shipping Address
			if ($enquiry_info['shipping_address_format']) {
				$format = $enquiry_info['shipping_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{unit_no}{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{unit_no}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);

			$replace = array(
				'firstname' => $enquiry_info['shipping_firstname'],
				'lastname'  => $enquiry_info['shipping_lastname'],
				'company'   => $enquiry_info['shipping_company'],
				'address_1' => $enquiry_info['shipping_address_1'],
				'address_2' => $enquiry_info['shipping_address_2'],
				'unit_no'   => $enquiry_info['shipping_unit_no']?$enquiry_info['shipping_unit_no'].', ':'',
				'city'      => $enquiry_info['shipping_city'],
				'postcode'  => $enquiry_info['shipping_postcode'],
				'zone'      => $enquiry_info['shipping_zone'],
				'zone_code' => $enquiry_info['shipping_zone_code'],
				'country'   => $enquiry_info['shipping_country']
			);

			$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			// Uploaded files
			$this->load->model('tool/upload');

			$data['products'] = array();

			$products = $this->model_sale_enquiry->getEnquiryProducts($this->request->get['enquiry_order_id']);

			foreach ($products as $product) {
				$option_data = array();

				$options = $this->model_sale_enquiry->getEnquiryOptions($this->request->get['enquiry_order_id'], $product['enquiry_order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $option['value'],
							'type'  => $option['type']
						);
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$option_data[] = array(
								'name'  => $option['name'],
								'value' => $upload_info['name'],
								'type'  => $option['type'],
								'href'  => $this->url->link('tool/upload/download', 'token=' . $this->session->data['token'] . '&code=' . $upload_info['code'], true)
							);
						}
					}
				}

				$data['products'][] = array(
					'enquiry_order_product_id' => $product['enquiry_order_product_id'],
					'product_id'       => $product['product_id'],
					'name'    	 	   => $product['name'],
					'model'    		   => $product['model'],
					'option'   		   => $option_data,
					'quantity'		   => $product['quantity'],
					'price'    		   => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $enquiry_info['currency_code'], $enquiry_info['currency_value']),
					'total'    		   => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $enquiry_info['currency_code'], $enquiry_info['currency_value']),
					'href'     		   => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], true)
				);
			}

			$data['vouchers'] = array();

			$vouchers = $this->model_sale_enquiry->getEnquiryVouchers($this->request->get['enquiry_order_id']);

			foreach ($vouchers as $voucher) {
				$data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $enquiry_info['currency_code'], $enquiry_info['currency_value']),
					'href'        => $this->url->link('sale/voucher/edit', 'token=' . $this->session->data['token'] . '&voucher_id=' . $voucher['voucher_id'], true)
				);
			}

			$data['totals'] = array();

			$totals = $this->model_sale_enquiry->getEnquiryTotals($this->request->get['enquiry_order_id']);

			foreach ($totals as $total) {
				//$this->currency->format($total['value'], $enquiry_info['currency_code'], $enquiry_info['currency_value'])
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => (int)$total['value']
				);
			}

			$data['comment'] = nl2br($enquiry_info['comment']);

			$this->load->model('customer/customer');

			$data['reward'] = $enquiry_info['reward_earn'];

			// Reward received
			$data['reward_total'] = $this->model_customer_customer->getTotalCustomerRewardsByOrderId($this->request->get['enquiry_order_id']);

			$data['affiliate_firstname'] = $enquiry_info['affiliate_firstname'];
			$data['affiliate_lastname'] = $enquiry_info['affiliate_lastname'];

			if ($enquiry_info['affiliate_id']) {
				$data['affiliate'] = $this->url->link('marketing/affiliate/edit', 'token=' . $this->session->data['token'] . '&affiliate_id=' . $enquiry_info['affiliate_id'], true);
			} else {
				$data['affiliate'] = '';
			}

			$data['commission'] = $this->currency->format($enquiry_info['commission'], $enquiry_info['currency_code'], $enquiry_info['currency_value']);

			$this->load->model('marketing/affiliate');

			$data['commission_total'] = $this->model_marketing_affiliate->getTotalTransactionsByOrderId($this->request->get['enquiry_order_id']);

			$this->load->model('localisation/order_status');

			$enquiry_status_info = $this->model_localisation_order_status->getOrderStatus($enquiry_info['enquiry_order_status_id']);

			if ($enquiry_status_info) {
				$data['enquiry_status'] = $enquiry_status_info['name'];
			} else {
				$data['enquiry_status'] = '';
			}

			$data['enquiry_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

			$data['enquiry_order_status_id'] = $enquiry_info['enquiry_order_status_id'];

			$data['account_custom_field'] = $enquiry_info['custom_field'];

			// Custom Fields
			$this->load->model('customer/custom_field');

			$data['account_custom_fields'] = array();

			$filter_data = array(
				'sort'  => 'cf.sort_order',
				'enquiry' => 'ASC'
			);

			$custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'account' && isset($enquiry_info['custom_field'][$custom_field['custom_field_id']])) {
					if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
						$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($enquiry_info['custom_field'][$custom_field['custom_field_id']]);

						if ($custom_field_value_info) {
							$data['account_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $custom_field_value_info['name']
							);
						}
					}

					if ($custom_field['type'] == 'checkbox' && is_array($enquiry_info['custom_field'][$custom_field['custom_field_id']])) {
						foreach ($enquiry_info['custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
							$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);

							if ($custom_field_value_info) {
								$data['account_custom_fields'][] = array(
									'name'  => $custom_field['name'],
									'value' => $custom_field_value_info['name']
								);
							}
						}
					}

					if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
						$data['account_custom_fields'][] = array(
							'name'  => $custom_field['name'],
							'value' => $enquiry_info['custom_field'][$custom_field['custom_field_id']]
						);
					}

					if ($custom_field['type'] == 'file') {
						$upload_info = $this->model_tool_upload->getUploadByCode($enquiry_info['custom_field'][$custom_field['custom_field_id']]);

						if ($upload_info) {
							$data['account_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $upload_info['name']
							);
						}
					}
				}
			}

			// Custom fields
			$data['payment_custom_fields'] = array();

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'address' && isset($enquiry_info['payment_custom_field'][$custom_field['custom_field_id']])) {
					if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
						$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($enquiry_info['payment_custom_field'][$custom_field['custom_field_id']]);

						if ($custom_field_value_info) {
							$data['payment_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $custom_field_value_info['name'],
								'sort_order' => $custom_field['sort_order']
							);
						}
					}

					if ($custom_field['type'] == 'checkbox' && is_array($enquiry_info['payment_custom_field'][$custom_field['custom_field_id']])) {
						foreach ($enquiry_info['payment_custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
							$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);

							if ($custom_field_value_info) {
								$data['payment_custom_fields'][] = array(
									'name'  => $custom_field['name'],
									'value' => $custom_field_value_info['name'],
									'sort_order' => $custom_field['sort_order']
								);
							}
						}
					}

					if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
						$data['payment_custom_fields'][] = array(
							'name'  => $custom_field['name'],
							'value' => $enquiry_info['payment_custom_field'][$custom_field['custom_field_id']],
							'sort_order' => $custom_field['sort_order']
						);
					}

					if ($custom_field['type'] == 'file') {
						$upload_info = $this->model_tool_upload->getUploadByCode($enquiry_info['payment_custom_field'][$custom_field['custom_field_id']]);

						if ($upload_info) {
							$data['payment_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $upload_info['name'],
								'sort_order' => $custom_field['sort_order']
							);
						}
					}
				}
			}

			// Shipping
			$data['shipping_custom_fields'] = array();

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'address' && isset($enquiry_info['shipping_custom_field'][$custom_field['custom_field_id']])) {
					if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
						$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($enquiry_info['shipping_custom_field'][$custom_field['custom_field_id']]);

						if ($custom_field_value_info) {
							$data['shipping_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $custom_field_value_info['name'],
								'sort_order' => $custom_field['sort_order']
							);
						}
					}

					if ($custom_field['type'] == 'checkbox' && is_array($enquiry_info['shipping_custom_field'][$custom_field['custom_field_id']])) {
						foreach ($enquiry_info['shipping_custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
							$custom_field_value_info = $this->model_customer_custom_field->getCustomFieldValue($custom_field_value_id);

							if ($custom_field_value_info) {
								$data['shipping_custom_fields'][] = array(
									'name'  => $custom_field['name'],
									'value' => $custom_field_value_info['name'],
									'sort_order' => $custom_field['sort_order']
								);
							}
						}
					}

					if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
						$data['shipping_custom_fields'][] = array(
							'name'  => $custom_field['name'],
							'value' => $enquiry_info['shipping_custom_field'][$custom_field['custom_field_id']],
							'sort_order' => $custom_field['sort_order']
						);
					}

					if ($custom_field['type'] == 'file') {
						$upload_info = $this->model_tool_upload->getUploadByCode($enquiry_info['shipping_custom_field'][$custom_field['custom_field_id']]);

						if ($upload_info) {
							$data['shipping_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $upload_info['name'],
								'sort_order' => $custom_field['sort_order']
							);
						}
					}
				}
			}

			$data['ip'] = $enquiry_info['ip'];
			$data['forwarded_ip'] = $enquiry_info['forwarded_ip'];
			$data['user_agent'] = $enquiry_info['user_agent'];
			$data['accept_language'] = $enquiry_info['accept_language'];

			// Additional Tabs
			$data['tabs'] = array();

			if ($this->user->hasPermission('access', 'extension/payment/' . $enquiry_info['payment_code'])) {
				if (is_file(DIR_CATALOG . 'controller/extension/payment/' . $enquiry_info['payment_code'] . '.php')) {
					$content = $this->load->controller('extension/payment/' . $enquiry_info['payment_code'] . '/enquiry');
				} else {
					$content = null;
				}

				if ($content) {
					$this->load->language('extension/payment/' . $enquiry_info['payment_code']);

					$data['tabs'][] = array(
						'code'    => $enquiry_info['payment_code'],
						'title'   => $this->language->get('heading_title'),
						'content' => $content
					);
				}
			}

			$this->load->model('extension/extension');

			$extensions = $this->model_extension_extension->getInstalled('fraud');

			foreach ($extensions as $extension) {
				if ($this->config->get($extension . '_status')) {
					$this->load->language('extension/fraud/' . $extension);

					$content = $this->load->controller('extension/fraud/' . $extension . '/enquiry');

					if ($content) {
						$data['tabs'][] = array(
							'code'    => $extension,
							'title'   => $this->language->get('heading_title'),
							'content' => $content
						);
					}
				}
			}
			
			// The URL we send API requests to
			$data['catalog'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;
			
			// API login
			$this->load->model('user/api');

			$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

			if ($api_info) {
				$data['api_id'] = $api_info['api_id'];
				$data['api_key'] = $api_info['key'];
				$data['api_ip'] = $this->request->server['REMOTE_ADDR'];
			} else {
				$data['api_id'] = '';
				$data['api_key'] = '';
				$data['api_ip'] = '';
			}

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('sale/enquiry_info', $data));
		} else {
			return new Action('error/not_found');
		}
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'sale/enquiry')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	public function createInvoiceNo() {
		$this->load->language('sale/enquiry');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/enquiry')) {
			$json['error'] = $this->language->get('error_permission');
		} elseif (isset($this->request->get['enquiry_order_id'])) {
			if (isset($this->request->get['enquiry_order_id'])) {
				$enquiry_order_id = $this->request->get['enquiry_order_id'];
			} else {
				$enquiry_order_id = 0;
			}

			$this->load->model('sale/enquiry');

			$invoice_no = $this->model_sale_enquiry->createInvoiceNo($enquiry_order_id);

			if ($invoice_no) {
				$json['invoice_no'] = $invoice_no;
			} else {
				$json['error'] = $this->language->get('error_action');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function addReward() {
		$this->load->language('sale/enquiry');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/enquiry')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['enquiry_order_id'])) {
				$enquiry_order_id = $this->request->get['enquiry_order_id'];
			} else {
				$enquiry_order_id = 0;
			}

			$this->load->model('sale/enquiry');

			$enquiry_info = $this->model_sale_enquiry->getEnquiry($enquiry_order_id);

			if ($enquiry_info && $enquiry_info['customer_id'] && ($enquiry_info['reward_earn'] > 0)) {
				$this->load->model('customer/customer');

				$reward_total = $this->model_customer_customer->getTotalCustomerRewardsByOrderId($enquiry_order_id);

				if (!$reward_total) {
					$this->model_customer_customer->addReward($enquiry_info['customer_id'], $this->language->get('text_enquiry_order_id') . ' #' . $enquiry_order_id, $enquiry_info['reward_earn'], $enquiry_order_id);
				}
			}

			$json['success'] = $this->language->get('text_reward_added');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function removeReward() {
		$this->load->language('sale/enquiry');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/enquiry')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['enquiry_order_id'])) {
				$enquiry_order_id = $this->request->get['enquiry_order_id'];
			} else {
				$enquiry_order_id = 0;
			}

			$this->load->model('sale/enquiry');

			$enquiry_info = $this->model_sale_enquiry->getEnquiry($enquiry_order_id);

			if ($enquiry_info) {
				$this->load->model('customer/customer');

				$this->model_customer_customer->deleteReward($enquiry_order_id);
			}

			$json['success'] = $this->language->get('text_reward_removed');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function addCommission() {
		$this->load->language('sale/enquiry');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/enquiry')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['enquiry_order_id'])) {
				$enquiry_order_id = $this->request->get['enquiry_order_id'];
			} else {
				$enquiry_order_id = 0;
			}

			$this->load->model('sale/enquiry');

			$enquiry_info = $this->model_sale_enquiry->getEnquiry($enquiry_order_id);

			if ($enquiry_info) {
				$this->load->model('marketing/affiliate');

				$affiliate_total = $this->model_marketing_affiliate->getTotalTransactionsByOrderId($enquiry_order_id);

				if (!$affiliate_total) {
					$this->model_marketing_affiliate->addTransaction($enquiry_info['affiliate_id'], $this->language->get('text_enquiry_order_id') . ' #' . $enquiry_order_id, $enquiry_info['commission'], $enquiry_order_id);
				}
			}

			$json['success'] = $this->language->get('text_commission_added');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function removeCommission() {
		$this->load->language('sale/enquiry');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/enquiry')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['enquiry_order_id'])) {
				$enquiry_order_id = $this->request->get['enquiry_order_id'];
			} else {
				$enquiry_order_id = 0;
			}

			$this->load->model('sale/enquiry');

			$enquiry_info = $this->model_sale_enquiry->getEnquiry($enquiry_order_id);

			if ($enquiry_info) {
				$this->load->model('marketing/affiliate');

				$this->model_marketing_affiliate->deleteTransaction($enquiry_order_id);
			}

			$json['success'] = $this->language->get('text_commission_removed');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function history() {
		$this->load->language('sale/enquiry');

		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_notify'] = $this->language->get('column_notify');
		$data['column_comment'] = $this->language->get('column_comment');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['histories'] = array();

		$this->load->model('sale/enquiry');

		$results = $this->model_sale_enquiry->getEnquiryHistories($this->request->get['enquiry_order_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {
			$data['histories'][] = array(
				'notify'     => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
				'status'     => $result['status'],
				'comment'    => nl2br($result['comment']),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$history_total = $this->model_sale_enquiry->getTotalEnquiryHistories($this->request->get['enquiry_order_id']);

		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('sale/enquiry/history', 'token=' . $this->session->data['token'] . '&enquiry_order_id=' . $this->request->get['enquiry_order_id'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));

		$this->response->setOutput($this->load->view('sale/enquiry_history', $data));
	}

	public function invoice() {
		$this->load->language('sale/enquiry');

		$data['title'] = $this->language->get('text_invoice');

		if ($this->request->server['HTTPS']) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}

		$data['direction'] = $this->language->get('direction');
		$data['lang'] = $this->language->get('code');

		$data['text_invoice'] = $this->language->get('text_invoice');
		$data['text_enquiry_detail'] = $this->language->get('text_enquiry_detail');
		$data['text_enquiry_order_id'] = $this->language->get('text_enquiry_order_id');
		$data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$data['text_invoice_date'] = $this->language->get('text_invoice_date');
		$data['text_date_added'] = $this->language->get('text_date_added');
		$data['text_telephone'] = $this->language->get('text_telephone');
		$data['text_fax'] = $this->language->get('text_fax');
		$data['text_email'] = $this->language->get('text_email');
		$data['text_website'] = $this->language->get('text_website');
		$data['text_payment_address'] = $this->language->get('text_payment_address');
		$data['text_shipping_address'] = $this->language->get('text_shipping_address');
		$data['text_payment_method'] = $this->language->get('text_payment_method');
		$data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$data['text_comment'] = $this->language->get('text_comment');

		$data['column_product'] = $this->language->get('column_product');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_total'] = $this->language->get('column_total');

		$this->load->model('sale/enquiry');

		$this->load->model('setting/setting');

		$data['enquirys'] = array();

		$enquirys = array();

		if (isset($this->request->post['selected'])) {
			$enquirys = $this->request->post['selected'];
		} elseif (isset($this->request->get['enquiry_order_id'])) {
			$enquirys[] = $this->request->get['enquiry_order_id'];
		}

		foreach ($enquirys as $enquiry_order_id) {
			$enquiry_info = $this->model_sale_enquiry->getEnquiry($enquiry_order_id);

			if ($enquiry_info) {
				$store_info = $this->model_setting_setting->getSetting('config', $enquiry_info['store_id']);

				if ($store_info) {
					$store_address = $store_info['config_address'];
					$store_email = $store_info['config_email'];
					$store_telephone = $store_info['config_telephone'];
					$store_fax = $store_info['config_fax'];
				} else {
					$store_address = $this->config->get('config_address');
					$store_email = $this->config->get('config_email');
					$store_telephone = $this->config->get('config_telephone');
					$store_fax = $this->config->get('config_fax');
				}

				if ($enquiry_info['invoice_no']) {
					$invoice_no = $enquiry_info['invoice_prefix'] . $enquiry_info['invoice_no'];
				} else {
					$invoice_no = '';
				}

				if ($enquiry_info['payment_address_format']) {
					$format = $enquiry_info['payment_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{unit_no}{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{unit_no}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);

				$replace = array(
					'firstname' => $enquiry_info['payment_firstname'],
					'lastname'  => $enquiry_info['payment_lastname'],
					'company'   => $enquiry_info['payment_company'],
					'address_1' => $enquiry_info['payment_address_1'],
					'address_2' => $enquiry_info['payment_address_2'],
					'unit_no' 	=> $enquiry_info['payment_unit_no']?$enquiry_info['payment_unit_no'].', ':'',
					'city'      => $enquiry_info['payment_city'],
					'postcode'  => $enquiry_info['payment_postcode'],
					'zone'      => $enquiry_info['payment_zone'],
					'zone_code' => $enquiry_info['payment_zone_code'],
					'country'   => $enquiry_info['payment_country']
				);

				$payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				if ($enquiry_info['shipping_address_format']) {
					$format = $enquiry_info['shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{unit_no}{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{unit_no}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);

				$replace = array(
					'firstname' => $enquiry_info['shipping_firstname'],
					'lastname'  => $enquiry_info['shipping_lastname'],
					'company'   => $enquiry_info['shipping_company'],
					'address_1' => $enquiry_info['shipping_address_1'],
					'address_2' => $enquiry_info['shipping_address_2'],
					'unit_no' 	=> $enquiry_info['shipping_unit_no']?$enquiry_info['shipping_unit_no'].', ':'',
					'city'      => $enquiry_info['shipping_city'],
					'postcode'  => $enquiry_info['shipping_postcode'],
					'zone'      => $enquiry_info['shipping_zone'],
					'zone_code' => $enquiry_info['shipping_zone_code'],
					'country'   => $enquiry_info['shipping_country']
				);

				$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				$this->load->model('tool/upload');

				$product_data = array();

				$products = $this->model_sale_enquiry->getEnquiryProducts($enquiry_order_id);

				foreach ($products as $product) {
					$option_data = array();

					$options = $this->model_sale_enquiry->getEnquiryOptions($enquiry_order_id, $product['enquiry_order_product_id']);

					foreach ($options as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

							if ($upload_info) {
								$value = $upload_info['name'];
							} else {
								$value = '';
							}
						}

						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $value
						);
					}

					$product_data[] = array(
						'name'     => $product['name'],
						'model'    => $product['model'],
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $enquiry_info['currency_code'], $enquiry_info['currency_value']),
						'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $enquiry_info['currency_code'], $enquiry_info['currency_value'])
					);
				}

				$voucher_data = array();

				$vouchers = $this->model_sale_enquiry->getEnquiryVouchers($enquiry_order_id);

				foreach ($vouchers as $voucher) {
					$voucher_data[] = array(
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'], $enquiry_info['currency_code'], $enquiry_info['currency_value'])
					);
				}

				$total_data = array();

				$totals = $this->model_sale_enquiry->getEnquiryTotals($enquiry_order_id);

				foreach ($totals as $total) {
					$total_data[] = array(
						'title' => $total['title'],
						'text'  => $this->currency->format($total['value'], $enquiry_info['currency_code'], $enquiry_info['currency_value'])
					);
				}

				$data['enquirys'][] = array(
					'enquiry_order_id'	       => $enquiry_order_id,
					'invoice_no'       => $invoice_no,
					'date_added'       => date($this->language->get('date_format_short'), strtotime($enquiry_info['date_added'])),
					'store_name'       => $enquiry_info['store_name'],
					'store_url'        => rtrim($enquiry_info['store_url'], '/'),
					'store_address'    => nl2br($store_address),
					'store_email'      => $store_email,
					'store_telephone'  => $store_telephone,
					'store_fax'        => $store_fax,
					'email'            => $enquiry_info['email'],
					'telephone'        => $enquiry_info['telephone'],
					'shipping_address' => $shipping_address,
					'shipping_method'  => $enquiry_info['shipping_method'],
					'payment_address'  => $payment_address,
					'payment_method'   => $enquiry_info['payment_method'],
					'product'          => $product_data,
					'voucher'          => $voucher_data,
					'total'            => $total_data,
					'comment'          => nl2br($enquiry_info['comment'])
				);
			}
		}

		$this->response->setOutput($this->load->view('sale/enquiry_invoice', $data));
	}

	public function shipping() {
		$this->load->language('sale/enquiry');

		$data['title'] = $this->language->get('text_shipping');

		if ($this->request->server['HTTPS']) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}

		$data['direction'] = $this->language->get('direction');
		$data['lang'] = $this->language->get('code');

		$data['text_shipping'] = $this->language->get('text_shipping');
		$data['text_picklist'] = $this->language->get('text_picklist');
		$data['text_enquiry_detail'] = $this->language->get('text_enquiry_detail');
		$data['text_enquiry_order_id'] = $this->language->get('text_enquiry_order_id');
		$data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$data['text_invoice_date'] = $this->language->get('text_invoice_date');
		$data['text_date_added'] = $this->language->get('text_date_added');
		$data['text_telephone'] = $this->language->get('text_telephone');
		$data['text_fax'] = $this->language->get('text_fax');
		$data['text_email'] = $this->language->get('text_email');
		$data['text_website'] = $this->language->get('text_website');
		$data['text_contact'] = $this->language->get('text_contact');
		$data['text_shipping_address'] = $this->language->get('text_shipping_address');
		$data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$data['text_sku'] = $this->language->get('text_sku');
		$data['text_upc'] = $this->language->get('text_upc');
		$data['text_ean'] = $this->language->get('text_ean');
		$data['text_jan'] = $this->language->get('text_jan');
		$data['text_isbn'] = $this->language->get('text_isbn');
		$data['text_mpn'] = $this->language->get('text_mpn');
		$data['text_comment'] = $this->language->get('text_comment');

		$data['column_location'] = $this->language->get('column_location');
		$data['column_reference'] = $this->language->get('column_reference');
		$data['column_product'] = $this->language->get('column_product');
		$data['column_weight'] = $this->language->get('column_weight');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_quantity'] = $this->language->get('column_quantity');

		$data['column_price'] = $this->language->get('column_price');
		$data['column_total'] = $this->language->get('column_total');

		$this->load->model('sale/enquiry');

		$this->load->model('catalog/product');

		$this->load->model('setting/setting');

		$data['enquirys'] = array();

		$enquirys = array();

		if (isset($this->request->post['selected'])) {
			$enquirys = $this->request->post['selected'];
		} elseif (isset($this->request->get['enquiry_order_id'])) {
			$enquirys[] = $this->request->get['enquiry_order_id'];
		}

		foreach ($enquirys as $enquiry_order_id) {
			$enquiry_info = $this->model_sale_enquiry->getEnquiry($enquiry_order_id);

			// Make sure there is a shipping method
			if ($enquiry_info && $enquiry_info['shipping_code']) {
				$store_info = $this->model_setting_setting->getSetting('config', $enquiry_info['store_id']);

				if ($store_info) {
					$store_address = $store_info['config_address'];
					$store_email = $store_info['config_email'];
					$store_telephone = $store_info['config_telephone'];
					$store_fax = $store_info['config_fax'];
				} else {
					$store_address = $this->config->get('config_address');
					$store_email = $this->config->get('config_email');
					$store_telephone = $this->config->get('config_telephone');
					$store_fax = $this->config->get('config_fax');
				}

				if ($enquiry_info['invoice_no']) {
					$invoice_no = $enquiry_info['invoice_prefix'] . $enquiry_info['invoice_no'];
				} else {
					$invoice_no = '';
				}

				if ($enquiry_info['shipping_address_format']) {
					$format = $enquiry_info['shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{unit_no}{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{unit_no}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);

				$replace = array(
					'firstname' => $enquiry_info['shipping_firstname'],
					'lastname'  => $enquiry_info['shipping_lastname'],
					'company'   => $enquiry_info['shipping_company'],
					'address_1' => $enquiry_info['shipping_address_1'],
					'address_2' => $enquiry_info['shipping_address_2'],
					'unit_no' 	=> $enquiry_info['shipping_unit_no']?$enquiry_info['shipping_unit_no'].', ':'',
					'city'      => $enquiry_info['shipping_city'],
					'postcode'  => $enquiry_info['shipping_postcode'],
					'zone'      => $enquiry_info['shipping_zone'],
					'zone_code' => $enquiry_info['shipping_zone_code'],
					'country'   => $enquiry_info['shipping_country']
				);

				$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				$this->load->model('tool/upload');

				$product_data = array();

				$products = $this->model_sale_enquiry->getEnquiryProducts($enquiry_order_id);

				foreach ($products as $product) {
					$option_weight = '';

					$product_info = $this->model_catalog_product->getProduct($product['product_id']);

					if ($product_info) {
						$option_data = array();

						$options = $this->model_sale_enquiry->getEnquiryOptions($enquiry_order_id, $product['enquiry_order_product_id']);

						foreach ($options as $option) {
							if ($option['type'] != 'file') {
								$value = $option['value'];
							} else {
								$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

								if ($upload_info) {
									$value = $upload_info['name'];
								} else {
									$value = '';
								}
							}

							$option_data[] = array(
								'name'  => $option['name'],
								'value' => $value
							);

							$product_option_value_info = $this->model_catalog_product->getProductOptionValue($product['product_id'], $option['product_option_value_id']);

							if ($product_option_value_info) {
								if ($product_option_value_info['weight_prefix'] == '+') {
									$option_weight += $product_option_value_info['weight'];
								} elseif ($product_option_value_info['weight_prefix'] == '-') {
									$option_weight -= $product_option_value_info['weight'];
								}
							}
						}

						$product_data[] = array(
							'name'     => $product_info['name'],
							'model'    => $product_info['model'],
							'option'   => $option_data,
							'quantity' => $product['quantity'],
							'location' => $product_info['location'],
							'sku'      => $product_info['sku'],
							'upc'      => $product_info['upc'],
							'ean'      => $product_info['ean'],
							'jan'      => $product_info['jan'],
							'isbn'     => $product_info['isbn'],
							'mpn'      => $product_info['mpn'],
							'weight'   => $this->weight->format(($product_info['weight']?$product_info['weight']:0 + $option_weight) * $product['quantity'], $product_info['weight_class_id'], $this->language->get('decimal_point'), $this->language->get('thousand_point')),

							'price'    => $this->currency->format($product_info['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $enquiry_info['currency_code'], $enquiry_info['currency_value']),
							'total'    => $this->currency->format( ($product_info['price'] * $product['quantity'])  + ($this->config->get('config_tax') ? ($product['tax'] * $product_info['quantity']) : 0), $enquiry_info['currency_code'], $enquiry_info['currency_value'])
						);
					}
				}

				$data['enquirys'][] = array(
					'enquiry_order_id'	       => $enquiry_order_id,
					'invoice_no'       => $invoice_no,
					'date_added'       => date($this->language->get('date_format_short'), strtotime($enquiry_info['date_added'])),
					'store_name'       => $enquiry_info['store_name'],
					'store_url'        => rtrim($enquiry_info['store_url'], '/'),
					'store_address'    => nl2br($store_address),
					'store_email'      => $store_email,
					'store_telephone'  => $store_telephone,
					'store_fax'        => $store_fax,
					'email'            => $enquiry_info['email'],
					'telephone'        => $enquiry_info['telephone'],
					'shipping_address' => $shipping_address,
					'shipping_method'  => $enquiry_info['shipping_method'],
					'product'          => $product_data,
					'comment'          => nl2br($enquiry_info['comment'])
				);
			}
		}

		$this->response->setOutput($this->load->view('sale/enquiry_shipping', $data));
	}


	// Pick Pack List

	public function pickPackList() {
		$this->load->language('sale/enquiry');

		$data['title'] = $this->language->get('text_invoice');

		if ($this->request->server['HTTPS']) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}

		$data['direction'] = $this->language->get('direction');
		$data['lang'] = $this->language->get('code');

		$data['text_pickpacklist'] = $this->language->get('text_pickpacklist');
		
		$data['text_ppl_invoice_no'] = $this->language->get('text_ppl_invoice_no');
		$data['text_ppl_enquiry_date'] = $this->language->get('text_ppl_enquiry_date');
		$data['text_ppl_product_name'] = $this->language->get('text_ppl_product_name');
		$data['text_ppl_sku'] = $this->language->get('text_ppl_sku');
		$data['text_ppl_quantity'] = $this->language->get('text_ppl_quantity');
		$data['text_ppl_customer_name'] = $this->language->get('text_ppl_customer_name');
		$data['text_ppl_customer_contact_no'] = $this->language->get('text_ppl_customer_contact_no');
		$data['text_ppl_delivery_address'] = $this->language->get('text_ppl_delivery_address');
		$data['text_ppl_delivery_instruction'] = $this->language->get('text_ppl_delivery_instruction');

		$data['text_enquiry_detail'] = $this->language->get('text_enquiry_detail');
		$data['text_enquiry_order_id'] = $this->language->get('text_enquiry_order_id');
		$data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$data['text_invoice_date'] = $this->language->get('text_invoice_date');
		$data['text_date_added'] = $this->language->get('text_date_added');
		$data['text_telephone'] = $this->language->get('text_telephone');
		$data['text_fax'] = $this->language->get('text_fax');
		$data['text_email'] = $this->language->get('text_email');
		$data['text_website'] = $this->language->get('text_website');
		$data['text_payment_address'] = $this->language->get('text_payment_address');
		$data['text_shipping_address'] = $this->language->get('text_shipping_address');
		$data['text_payment_method'] = $this->language->get('text_payment_method');
		$data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$data['text_comment'] = $this->language->get('text_comment');

		$data['column_product'] = $this->language->get('column_product');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_total'] = $this->language->get('column_total');

		$this->load->model('sale/enquiry');

		$this->load->model('setting/setting');

		$data['enquirys'] = array();

		$enquirys = array();

		if (isset($this->request->post['selected'])) {
			$enquirys = $this->request->post['selected'];
		} elseif (isset($this->request->get['enquiry_order_id'])) {
			$enquirys[] = $this->request->get['enquiry_order_id'];
		}

		foreach ($enquirys as $enquiry_order_id) {
			$enquiry_info = $this->model_sale_enquiry->getEnquiry($enquiry_order_id);

			if ($enquiry_info) {
				$store_info = $this->model_setting_setting->getSetting('config', $enquiry_info['store_id']);

				if ($store_info) {
					$store_address = $store_info['config_address'];
					$store_email = $store_info['config_email'];
					$store_telephone = $store_info['config_telephone'];
					$store_fax = $store_info['config_fax'];
				} else {
					$store_address = $this->config->get('config_address');
					$store_email = $this->config->get('config_email');
					$store_telephone = $this->config->get('config_telephone');
					$store_fax = $this->config->get('config_fax');
				}

				if ($enquiry_info['invoice_no']) {
					$invoice_no = $enquiry_info['invoice_prefix'] . $enquiry_info['invoice_no'];
				} else {
					$invoice_no = '';
				}

				if ($enquiry_info['payment_address_format']) {
					$format = $enquiry_info['payment_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{unit_no}{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{unit_no}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);

				$replace = array(
					'firstname' => $enquiry_info['payment_firstname'],
					'lastname'  => $enquiry_info['payment_lastname'],
					'company'   => $enquiry_info['payment_company'],
					'address_1' => $enquiry_info['payment_address_1'],
					'address_2' => $enquiry_info['payment_address_2'],
					'unit_no' 	=> $enquiry_info['payment_unit_no']?$enquiry_info['payment_unit_no'].', ':'',
					'city'      => $enquiry_info['payment_city'],
					'postcode'  => $enquiry_info['payment_postcode'],
					'zone'      => $enquiry_info['payment_zone'],
					'zone_code' => $enquiry_info['payment_zone_code'],
					'country'   => $enquiry_info['payment_country']
				);

				$payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				if ($enquiry_info['shipping_address_format']) {
					$format = $enquiry_info['shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{unit_no}{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{unit_no}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);

				$replace = array(
					'firstname' => $enquiry_info['shipping_firstname'],
					'lastname'  => $enquiry_info['shipping_lastname'],
					'company'   => $enquiry_info['shipping_company'],
					'address_1' => $enquiry_info['shipping_address_1'],
					'address_2' => $enquiry_info['shipping_address_2'],
					'unit_no' 	=> $enquiry_info['shipping_unit_no']?$enquiry_info['shipping_unit_no'].', ':'',
					'city'      => $enquiry_info['shipping_city'],
					'postcode'  => $enquiry_info['shipping_postcode'],
					'zone'      => $enquiry_info['shipping_zone'],
					'zone_code' => $enquiry_info['shipping_zone_code'],
					'country'   => $enquiry_info['shipping_country']
				);

				$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				$this->load->model('tool/upload');

				$product_data = array();

				$products = $this->model_sale_enquiry->getEnquiryProducts($enquiry_order_id);

				foreach ($products as $product) {
					$option_data = array();

					$options = $this->model_sale_enquiry->getEnquiryOptions($enquiry_order_id, $product['enquiry_order_product_id']);

					foreach ($options as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

							if ($upload_info) {
								$value = $upload_info['name'];
							} else {
								$value = '';
							}
						}

						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $value
						);
					}

					$product_data[] = array(
						'name'     => $product['name'],
						'sku'      => $product['sku'],
						'model'    => $product['model'],
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $enquiry_info['currency_code'], $enquiry_info['currency_value']),
						'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $enquiry_info['currency_code'], $enquiry_info['currency_value'])
					);
				}

				$voucher_data = array();

				$vouchers = $this->model_sale_enquiry->getEnquiryVouchers($enquiry_order_id);

				foreach ($vouchers as $voucher) {
					$voucher_data[] = array(
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'], $enquiry_info['currency_code'], $enquiry_info['currency_value'])
					);
				}

				$total_data = array();

				$totals = $this->model_sale_enquiry->getEnquiryTotals($enquiry_order_id);

				foreach ($totals as $total) {
					$total_data[] = array(
						'title' => $total['title'],
						'text'  => $this->currency->format($total['value'], $enquiry_info['currency_code'], $enquiry_info['currency_value'])
					);
				}

				$data['enquirys'][] = array(
					'enquiry_order_id'	       => $enquiry_order_id,
					'name'	       	   => $enquiry_info['firstname'] . ' ' . $enquiry_info['lastname'],
					'invoice_no'       => $invoice_no,
					'date_added'       => date($this->language->get('date_format_short'), strtotime($enquiry_info['date_added'])),
					'store_name'       => $enquiry_info['store_name'],
					'store_url'        => rtrim($enquiry_info['store_url'], '/'),
					'store_address'    => nl2br($store_address),
					'store_email'      => $store_email,
					'store_telephone'  => $store_telephone,
					'store_fax'        => $store_fax,
					'email'            => $enquiry_info['email'],
					'telephone'        => $enquiry_info['telephone'],
					'shipping_address' => $shipping_address,
					'shipping_method'  => $enquiry_info['shipping_method'],
					'payment_address'  => $payment_address,
					'payment_method'   => $enquiry_info['payment_method'],
					'product'          => $product_data,
					'voucher'          => $voucher_data,
					'total'            => $total_data,
					'comment'          => nl2br($enquiry_info['comment'])
				);
			}
		}

		$this->response->setOutput($this->load->view('sale/enquiry_pick_pack_list', $data));
	}

	// End Pick Pack List
}

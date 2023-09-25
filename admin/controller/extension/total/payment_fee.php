<?php
class ControllerExtensionTotalPaymentfee extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/total/payment_fee');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_fee', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . "&type=module", 'SSL')); 
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled'); 
		$data['text_none'] = $this->language->get('text_none');

		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_fee'] = $this->language->get('entry_fee');
		$data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_payment_method'] = $this->language->get('entry_payment_method');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_all'] = $this->language->get('entry_all');
		$data['entry_fee_help'] = $this->language->get('entry_fee_help');
		$data['entry_total_help'] = $this->language->get('entry_total_help');
		$data['entry_tax_class_help'] = $this->language->get('entry_tax_class_help');
		$data['button_insert'] = $this->language->get('button_insert');
		$data['button_remove'] = $this->language->get('button_remove');

		$data['help_total'] = $this->language->get('help_total');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_total'),
			'href' => $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/total/payment_fee', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('extension/total/payment_fee', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['payment_fee_total'])) {
			$data['payment_fee_total'] = $this->request->post['payment_fee_total'];
		} else {
			$data['payment_fee_total'] = $this->config->get('payment_fee_total');
		}

		if (isset($this->request->post['payment_fee_fee'])) {
			$data['payment_fee_fee'] = $this->request->post['payment_fee_fee'];
		} else {
			$data['payment_fee_fee'] = $this->config->get('payment_fee_fee');
		}

		if (isset($this->request->post['payment_fee_tax_class_id'])) {
			$data['payment_fee_tax_class_id'] = $this->request->post['payment_fee_tax_class_id'];
		} else {
			$data['payment_fee_tax_class_id'] = $this->config->get('payment_fee_tax_class_id');
		}

		if (isset($this->request->post['payment_fees'])){
				$data['payment_fees'] = $this->request->post['payment_fees'];
			} elseif ( $this->config->get('payment_fees')){
				$data['payment_fees'] = $this->config->get('payment_fees');
			} else {
				$data['payment_fees'] = array();
			}
			$this->load->model('extension/extension');
			$payment = $this->model_extension_extension->getInstalled('payment');  
			
			$data['payment_methods'] = array();
			
			if ($payment){
				foreach($payment as $payment_method_code){
					$this->language->load('extension/payment/' . $payment_method_code);
					
					$data['payment_methods'][] = array(
						'code' => $payment_method_code,
						'name' => $this->language->get('heading_title')
					);
				}
			}
			
			$this->load->model('customer/customer_group');	
			$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['payment_fee_status'])) {
			$data['payment_fee_status'] = $this->request->post['payment_fee_status'];
		} else {
			$data['payment_fee_status'] = $this->config->get('payment_fee_status');
		}

		if (isset($this->request->post['payment_fee_sort_order'])) {
			$data['payment_fee_sort_order'] = $this->request->post['payment_fee_sort_order'];
		} else {
			$data['payment_fee_sort_order'] = $this->config->get('payment_fee_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/total/payment_fee.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/total/payment_fee')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
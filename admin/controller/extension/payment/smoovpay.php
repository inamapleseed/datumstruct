<?php
class ControllerExtensionPaymentSmoovPay extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/smoovpay');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('smoovpay', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_authorize'] = $this->language->get('text_authorize');
		$data['text_pay'] = $this->language->get('text_pay');
		$data['text_disable_payment'] = $this->language->get('text_disable_payment');
		$data['text_convert_usd'] = $this->language->get('text_convert_usd');
		$data['text_convert_sgd'] = $this->language->get('text_convert_sgd');

		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_secret'] = $this->language->get('entry_secret');
		$data['entry_convert'] = $this->language->get('entry_convert');
		$data['entry_test'] = $this->language->get('entry_test');
		$data['entry_transaction'] = $this->language->get('entry_transaction');
		$data['entry_debug'] = $this->language->get('entry_debug');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_approved_status'] = $this->language->get('entry_approved_status');
		$data['entry_declined_status'] = $this->language->get('entry_declined_status');
		$data['entry_error_status'] = $this->language->get('entry_error_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_test'] = $this->language->get('help_test');
		$data['help_debug'] = $this->language->get('help_debug');
		$data['help_total'] = $this->language->get('help_total');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_order_status'] = $this->language->get('tab_order_status');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}
		
		if (isset($this->error['secret'])) {
			$data['error_secret'] = $this->error['secret'];
		} else {
			$data['error_secret'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/smoovpay', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('extension/payment/smoovpay', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', 'SSL');

		if (isset($this->request->post['smoovpay_email'])) {
			$data['smoovpay_email'] = $this->request->post['smoovpay_email'];
		} else {
			$data['smoovpay_email'] = $this->config->get('smoovpay_email');
		}
		
		if (isset($this->request->post['smoovpay_secret'])) {
			$data['smoovpay_secret'] = $this->request->post['smoovpay_secret'];
		} else {
			$data['smoovpay_secret'] = $this->config->get('smoovpay_secret');
		}
		
		if (isset($this->request->post['smoovpay_convert'])) {
			$data['smoovpay_convert'] = $this->request->post['smoovpay_convert'];
		} else {
			$data['smoovpay_convert'] = $this->config->get('smoovpay_convert');
		}

		if (isset($this->request->post['smoovpay_test'])) {
			$data['smoovpay_test'] = $this->request->post['smoovpay_test'];
		} else {
			$data['smoovpay_test'] = $this->config->get('smoovpay_test');
		}

		if (isset($this->request->post['smoovpay_transaction'])) {
			$data['smoovpay_transaction'] = $this->request->post['smoovpay_transaction'];
		} else {
			$data['smoovpay_transaction'] = $this->config->get('smoovpay_transaction');
		}

		if (isset($this->request->post['smoovpay_debug'])) {
			$data['smoovpay_debug'] = $this->request->post['smoovpay_debug'];
		} else {
			$data['smoovpay_debug'] = $this->config->get('smoovpay_debug');
		}

		if (isset($this->request->post['smoovpay_total'])) {
			$data['smoovpay_total'] = $this->request->post['smoovpay_total'];
		} else {
			$data['smoovpay_total'] = $this->config->get('smoovpay_total');
		}

		if (isset($this->request->post['smoovpay_approved_status_id'])) {
			$data['smoovpay_approved_status_id'] = $this->request->post['smoovpay_approved_status_id'];
		} else {
			$data['smoovpay_approved_status_id'] = $this->config->get('smoovpay_approved_status_id');
		}

		if (isset($this->request->post['smoovpay_declined_status_id'])) {
			$data['smoovpay_declined_status_id'] = $this->request->post['smoovpay_declined_status_id'];
		} else {
			$data['smoovpay_declined_status_id'] = $this->config->get('smoovpay_declined_status_id');
		}

		if (isset($this->request->post['smoovpay_error_status_id'])) {
			$data['smoovpay_error_status_id'] = $this->request->post['smoovpay_error_status_id'];
		} else {
			$data['smoovpay_error_status_id'] = $this->config->get('smoovpay_error_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['smoovpay_geo_zone_id'])) {
			$data['smoovpay_geo_zone_id'] = $this->request->post['smoovpay_geo_zone_id'];
		} else {
			$data['smoovpay_geo_zone_id'] = $this->config->get('smoovpay_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['smoovpay_status'])) {
			$data['smoovpay_status'] = $this->request->post['smoovpay_status'];
		} else {
			$data['smoovpay_status'] = $this->config->get('smoovpay_status');
		}

		if (isset($this->request->post['smoovpay_sort_order'])) {
			$data['smoovpay_sort_order'] = $this->request->post['smoovpay_sort_order'];
		} else {
			$data['smoovpay_sort_order'] = $this->config->get('smoovpay_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/smoovpay', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/smoovpay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['smoovpay_email']) {
			$this->error['email'] = $this->language->get('error_email');
		}
		
		if (!$this->request->post['smoovpay_secret']) {
			$this->error['secret'] = $this->language->get('error_secret');
		}

		return !$this->error;
	}
}
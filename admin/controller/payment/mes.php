<?php
/**
* Custom Payment Gateway for OpenCart v2
* Using Payment Gateway API v4.11
* For Credit Card Transactions
* Written 09/20/2014
* ©Merchant e-Solutions 2014
*
* @author nrichardson
* 
*/

class ControllerPaymentMes extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/mes');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('mes', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_authorization'] = $this->language->get('text_authorization');
		$data['text_sale'] = $this->language->get('text_sale');
		$data['text_pg'] = $this->language->get('text_pg');
		$data['text_ph'] = $this->language->get('text_ph');
		
		$data['entry_profile_id'] = $this->language->get('entry_profile_id');
		$data['entry_profile_key'] = $this->language->get('entry_profile_key');
		$data['entry_test'] = $this->language->get('entry_test');
		$data['entry_auth'] = $this->language->get('entry_auth');
		$data['entry_transaction'] = $this->language->get('entry_transaction');
		$data['entry_completed_status'] = $this->language->get('entry_completed_status');	
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_payment_mode'] = $this->language->get('entry_payment_mode');
		$data['entry_security_key'] = $this->language->get('entry_security_key');

		$data['help_test'] = $this->language->get('help_test');
		$data['help_auth'] = $this->language->get('help_auth');
		$data['help_profile'] = $this->language->get('help_profile');
		$data['help_mode'] = $this->language->get('help_mode');
		$data['help_key'] = $this->language->get('help_key');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

 		if (isset($this->error['profile_id'])) {
			$data['error_profile_id'] = $this->error['profile_id'];
		} else {
			$data['error_profile_id'] = '';
		}
		
 		if (isset($this->error['profile_key'])) {
			$data['error_profile_key'] = $this->error['profile_key'];
		} else {
			$data['error_profile_key'] = '';
		}
		
 		if (isset($this->error['security_key'])) {
			$data['error_security_key'] = $this->error['security_key'];
		} else {
			$data['error_security_key'] = '';
		}

		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_payment'),
      		'separator' => ' :: '
   		);

   		$data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('payment/mes', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
				
		$data['action'] = $this->url->link('payment/mes', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['mes_profile_id'])) {
			$data['mes_profile_id'] = $this->request->post['mes_profile_id'];
		} else {
			$data['mes_profile_id'] = $this->config->get('mes_profile_id');
		}
		
		if (isset($this->request->post['mes_profile_key'])) {
			$data['mes_profile_key'] = $this->request->post['mes_profile_key'];
		} else {
			$data['mes_profile_key'] = $this->config->get('mes_profile_key');
		}
		
		if (isset($this->request->post['mes_test'])) {
			$data['mes_test'] = $this->request->post['mes_test'];
		} else {
			$data['mes_test'] = $this->config->get('mes_test');
		}
		
		if (isset($this->request->post['mes_transaction'])) {
			$data['mes_transaction'] = $this->request->post['mes_transaction'];
		} else {
			$data['mes_transaction'] = $this->config->get('mes_transaction');
		}

		if (isset($this->request->post['mes_completed_status_id'])) {
			$data['mes_completed_status_id'] = $this->request->post['mes_completed_status_id'];
		} else {
			$data['mes_completed_status_id'] = $this->config->get('mes_completed_status_id');
		}

		if (isset($this->request->post['mes_security_key'])) {
			$data['mes_security_key'] = $this->request->post['mes_security_key'];
		} else {
			$data['mes_security_key'] = $this->config->get('mes_security_key');
		}

		if (isset($this->request->post['mes_mode'])) {
			$data['mes_mode'] = $this->request->post['mes_mode'];
		} else {
			$data['mes_mode'] = $this->config->get('mes_mode');
		}

		$this->load->model('localisation/order_status');
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['mes_geo_zone_id'])) {
			$data['mes_geo_zone_id'] = $this->request->post['mes_geo_zone_id'];
		} else {
			$data['mes_geo_zone_id'] = $this->config->get('mes_geo_zone_id'); 
		} 
		
		$this->load->model('localisation/geo_zone');
										
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['mes_status'])) {
			$data['mes_status'] = $this->request->post['mes_status'];
		} else {
			$data['mes_status'] = $this->config->get('mes_status');
		}
		
		if (isset($this->request->post['mes_sort_order'])) {
			$data['mes_sort_order'] = $this->request->post['mes_sort_order'];
		} else {
			$data['mes_sort_order'] = $this->config->get('mes_sort_order');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('payment/mes.tpl', $data));
	}

	private function validate() {
	
		if (!$this->user->hasPermission('modify', 'payment/mes')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['mes_profile_id']) {
			$this->error['profile_id'] = $this->language->get('error_profile_id');
		}

		if (!$this->request->post['mes_profile_key']) {
			$this->error['profile_key'] = $this->language->get('error_profile_key');
		}

		if (!$this->request->post['security_key']) {
			$this->error['security_key'] = $this->language->get('error_security_keyy');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>
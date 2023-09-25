<?php
class ControllerExtensionPaymentPayDollar extends Controller {
	private $error = array(); 

	public function index() {
		$this->language->load('extension/payment/paydollar');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {			
			$this->model_setting_setting->editSetting('paydollar', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		
		$data['entry_merchant'] = $this->language->get('entry_merchant');
		$data['entry_security'] = $this->language->get('entry_security');
		$data['entry_payserverurl'] = $this->language->get('entry_payserverurl');
		$data['entry_mps_mode'] = $this->language->get('entry_mps_mode');
		$data['entry_currency'] = $this->language->get('entry_currency');
		$data['entry_payment_type'] = $this->language->get('entry_payment_type');
		$data['entry_paymethod'] = $this->language->get('entry_paymethod');
		$data['entry_lang'] = $this->language->get('entry_lang');
		$data['entry_callback'] = $this->language->get('entry_callback');
		$data['callback'] = $this->language->get('callback');
		
		$data['entry_order_status'] = $this->language->get('entry_order_status');		
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

  		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

 		if (isset($this->error['payserverurl'])) {
			$data['error_payserverurl'] = $this->error['payserverurl'];
		} else {
			$data['error_payserverurl'] = '';
		}
		
 		if (isset($this->error['merchant'])) {
			$data['error_merchant'] = $this->error['merchant'];
		} else {
			$data['error_merchant'] = '';
		}

 		if (isset($this->error['security'])) {
			$data['error_security'] = $this->error['security'];
		} else {
			$data['error_security'] = '';
		}
		
		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text' => $this->language->get('text_home'),
   			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
   		);

   		$data['breadcrumbs'][] = array(
       		'text' => $this->language->get('text_payment'),
   			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true)
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/paydollar', 'token=' . $this->session->data['token'], 'SSL')
   		);
				
		$data['action'] = $this->url->link('extension/payment/paydollar', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL');	
		
		if (isset($this->request->post['paydollar_payserverurl'])) {
			$data['paydollar_payserverurl'] = $this->request->post['paydollar_payserverurl'];
		} else {
			$data['paydollar_payserverurl'] = $this->config->get('paydollar_payserverurl');
		}
		
		$data['paydollar_payserverurls'] = array(
				'https://www.paydollar.com/b2c2/eng/payment/payForm.jsp',
				'https://test.paydollar.com/b2cDemo/eng/payment/payForm.jsp',
				'https://www.pesopay.com/b2c2/eng/payment/payForm.jsp',
				'https://test.pesopay.com/b2cDemo/eng/payment/payForm.jsp',
				'https://www.siampay.com/b2c2/eng/payment/payForm.jsp',
				'https://test.siampay.com/b2cDemo/eng/payment/payForm.jsp',
		);
		
		if (isset($this->request->post['paydollar_merchant'])) {
			$data['paydollar_merchant'] = $this->request->post['paydollar_merchant'];
		} else {
			$data['paydollar_merchant'] = $this->config->get('paydollar_merchant');
		}

		if (isset($this->request->post['paydollar_security'])) {
			$data['paydollar_security'] = $this->request->post['paydollar_security'];
		} else {
			$data['paydollar_security'] = $this->config->get('paydollar_security');
		}
		
		if (isset($this->request->post['paydollar_order_status_id'])) {
			$data['paydollar_order_status_id'] = $this->request->post['paydollar_order_status_id'];
		} else {
			$data['paydollar_order_status_id'] = $this->config->get('paydollar_order_status_id'); 
		} 
		
		$this->load->model('localisation/order_status');
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['paydollar_geo_zone_id'])) {
			$data['paydollar_geo_zone_id'] = $this->request->post['paydollar_geo_zone_id'];
		} else {
			$data['paydollar_geo_zone_id'] = $this->config->get('paydollar_geo_zone_id'); 
		} 

		$this->load->model('localisation/geo_zone');
										
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['paydollar_status'])) {
			$data['paydollar_status'] = $this->request->post['paydollar_status'];
		} else {
			$data['paydollar_status'] = $this->config->get('paydollar_status');
		}
		
		if (isset($this->request->post['paydollar_sort_order'])) {
			$data['paydollar_sort_order'] = $this->request->post['paydollar_sort_order'];
		} else {
			$data['paydollar_sort_order'] = $this->config->get('paydollar_sort_order');
		}
		
		if (isset($this->request->post['paydollar_lang'])) {
			$data['paydollar_lang'] = $this->request->post['paydollar_lang'];
		} else {
			$data['paydollar_lang'] = $this->config->get('paydollar_lang'); 
		} 
		
		$data['paydollar_langs']= array(
				'E-English',
				'C-Traditional Chinese',			
				'X-Simplified Chinese',
				'K-Korean',
				'J-Japanese',
				'T-Thai',
				'F-French',
				'G-German',
				'R-Russian',
				'S-Spanish',
				'V-Vietnamese',
		);
		
		if (isset($this->request->post['paydollar_payment_type'])) {
			$data['paydollar_payment_type'] = $this->request->post['paydollar_payment_type'];
		} else {
			$data['paydollar_payment_type'] = $this->config->get('paydollar_payment_type'); 
		} 
		$data['paydollar_payment_types'] = array(
				'N-Normal Payment (Sales)',
				'H-Hold Payment (Authorize only)',
		);
		
		if (isset($this->request->post['paydollar_mps_mode'])) {
			$data['paydollar_mps_mode'] = $this->request->post['paydollar_mps_mode'];
		} else {
			$data['paydollar_mps_mode'] = $this->config->get('paydollar_mps_mode'); 
		} 
		$data['paydollar_mps_modes'] = array(
				'NIL',
				'SCP',
				'DCC',
				'MCP'
		);
		
		if (isset($this->request->post['paydollar_paymethod'])) {
			$data['paydollar_paymethod'] = $this->request->post['paydollar_paymethod'];
		} else {
			$data['paydollar_paymethod'] = $this->config->get('paydollar_paymethod'); 
		} 
		
		$data['paydollar_paymethods'] = array('ALL','CC','VISA','Master','JCB','AMEX','Diners','PPS','PAYPAL','CHINAPAY','ALIPAY','TENPAY','99BILL','MEPS','SCB','BPM','KTB','UOB','KRUNGSRIONLINE','TMB','IBANKING','BancNet','GCash','SMARTMONEY','PAYCASH');	
		
		if (isset($this->request->post['paydollar_currency'])) {
			$data['paydollar_currency'] = $this->request->post['paydollar_currency'];
		} else {
			$data['paydollar_currency'] = $this->config->get('paydollar_currency'); 
		} 
		
		$data['paydollar_currencies'] = array(
				'784-AED',
				'036-AUD',
				'096-BND',
				'124-CAD',
				'156-CNY (RMB)',
				'978-EUR',
				'826-GBP',
				'344-HKD',
				'360-IDR',
				'356-INR',
				'392-JPY',
				'410-KRW',
				'446-MOP',
				'458-MYR',
				'554-NZD',
				'608-PHP',
				'682-SAR',
				'702-SGD',
				'764-THB',
				'901-TWD',
				'840-USD',
				'704-VND',					
		);
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/payment/paydollar.tpl', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/paydollar')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['paydollar_payserverurl']) {
			$this->error['payserverurl'] = $this->language->get('error_payserverurl');
		}
		
		if (!$this->request->post['paydollar_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>
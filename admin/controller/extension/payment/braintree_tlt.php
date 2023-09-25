<?php
class ControllerExtensionPaymentBraintreeTlt extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/braintree_tlt');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

        if (is_file(DIR_SYSTEM . '../../vendor/autoload.php')) {
            $data['braintree'] = '';
        } elseif (file_exists(DIR_SYSTEM . '/braintree/lib/Braintree.php')) {
			    require_once(DIR_SYSTEM . '/braintree/lib/Braintree.php');
			    $data['braintree'] = '';
		} else {
			$data['braintree'] = $this->language->get('text_braintree');
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('braintree_tlt', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_test'] = $this->language->get('text_test');
		$data['text_live'] = $this->language->get('text_live');
		$data['text_authorization'] = $this->language->get('text_authorization');
		$data['text_charge'] = $this->language->get('text_charge');		
		$data['text_default_currency'] = $this->language->get('text_default_currency');		
		$data['text_copyright'] = '&copy; 2016, <a href="http://taiwanleaftea.com" target="_blank" class="alert-link" title="Authentic tea from Taiwan">Taiwanleaftea.com</a>';
		$data['text_donation'] = 'If you find this software usefull and to support further development please buy me a cup of <a href="http://taiwanleaftea.com" class="alert-link" target="_blank" title="Authentic tea from Taiwan">tea</a> or like us on <a href="https://www.facebook.com/taiwanleaftea" class="alert-link" target="_blank" title="Taiwanleaftea on Facebook">Facebook</a>.';

		$data['help_total'] = $this->language->get('help_total');		
		$data['help_merchant_account'] = $this->language->get('help_merchant_account');		
		$data['help_use_default'] = $this->language->get('help_use_default');		
		$data['help_debug'] = $this->language->get('help_debug');
        $data['help_tls12'] = $this->language->get('help_tls12');

		$data['entry_merchant'] = $this->language->get('entry_merchant');
		$data['entry_use_default'] = $this->language->get('entry_use_default');
		$data['entry_default_account'] = $this->language->get('entry_default_account');
		$data['entry_merchant_account'] = $this->language->get('entry_merchant_account');
        $data['entry_public'] = $this->language->get('entry_public');
		$data['entry_key'] = $this->language->get('entry_key');
		$data['entry_debug'] = $this->language->get('entry_debug');
		$data['entry_mode'] = $this->language->get('entry_mode');
		$data['entry_method'] = $this->language->get('entry_method');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_order_status'] = $this->language->get('entry_order_status');		
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_tls12'] = $this->language->get('entry_tls12');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['public'])) {
			$data['error_public'] = $this->error['public'];
		} else {
			$data['error_public'] = '';
		}

		if (isset($this->error['key'])) {
			$data['error_key'] = $this->error['key'];
		} else {
			$data['error_key'] = '';
		}
        if (isset($this->error['merchant'])) {
			$data['error_merchant'] = $this->error['merchant'];
		} else {
			$data['error_merchant'] = '';
		}
		
        if (isset($this->error['default_account'])) {
			$data['error_default_account'] = $this->error['default_account'];
		} else {
			$data['error_default_account'] = '';
		}

        if (isset($this->error['merchant_account'])) {
			$data['error_merchant_account'] = $this->error['merchant_account'];
		} else {
			$data['error_merchant_account'] = array();
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/braintree_tlt', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/braintree_tlt', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);

		if (isset($this->request->post['braintree_tlt_merchant'])) {
			$data['braintree_tlt_merchant'] = $this->request->post['braintree_tlt_merchant'];
		} else {
			$data['braintree_tlt_merchant'] = $this->config->get('braintree_tlt_merchant');
		}

		if (isset($this->request->post['braintree_tlt_use_default'])) {
			$data['braintree_tlt_use_default'] = $this->request->post['braintree_tlt_use_default'];
		} elseif ($this->config->has('braintree_tlt_use_default')) {
			$data['braintree_tlt_use_default'] = $this->config->get('braintree_tlt_use_default');
		} else {
			$data['braintree_tlt_use_default'] = '1';
		}

		if (isset($this->request->post['braintree_tlt_default_account'])) {
			$data['braintree_tlt_default_account'] = $this->request->post['braintree_tlt_default_account'];
		} else {
			$data['braintree_tlt_default_account'] = $this->config->get('braintree_tlt_default_account');
		}

		$this->load->model('localisation/currency');
		
		$data['currencies'] = $this->model_localisation_currency->getCurrencies();
		$data['default_currency'] = $this->config->get('config_currency');

		if (isset($this->request->post['braintree_tlt_merchant_account'])) {
			$data['braintree_tlt_merchant_account'] = $this->request->post['braintree_tlt_merchant_account'];
		} else {
			$data['braintree_tlt_merchant_account'] = $this->config->get('braintree_tlt_merchant_account');
		}

        if (isset($this->request->post['braintree_tlt_public_key'])) {
			$data['braintree_tlt_public_key'] = $this->request->post['braintree_tlt_public_key'];
		} else {
			$data['braintree_tlt_public_key'] = $this->config->get('braintree_tlt_public_key');
		}

        if (isset($this->request->post['braintree_tlt_private_key'])) {
			$data['braintree_tlt_private_key'] = $this->request->post['braintree_tlt_private_key'];
		} else {
			$data['braintree_tlt_private_key'] = $this->config->get('braintree_tlt_private_key');
		}
        
		if (isset($this->request->post['braintree_tlt_debug'])) {
			$data['braintree_tlt_debug'] = $this->request->post['braintree_tlt_debug'];
		} else {
			$data['braintree_tlt_debug'] = $this->config->get('braintree_tlt_debug');
		}

		if (isset($this->request->post['braintree_tlt_mode'])) {
			$data['braintree_tlt_mode'] = $this->request->post['braintree_tlt_mode'];
		} else {
			$data['braintree_tlt_mode'] = $this->config->get('braintree_tlt_mode');
		}

		if (isset($this->request->post['braintree_tlt_method'])) {
			$data['braintree_tlt_method'] = $this->request->post['braintree_tlt_method'];
		} else {
			$data['braintree_tlt_method'] = $this->config->get('braintree_tlt_method');
		}

		if (isset($this->request->post['braintree_tlt_order_status_id'])) {
			$data['braintree_tlt_order_status_id'] = $this->request->post['braintree_tlt_order_status_id'];
		} else {
			$data['braintree_tlt_order_status_id'] = $this->config->get('braintree_tlt_order_status_id'); 
		}

        if (isset($this->request->post['braintree_tlt_tls12'])) {
            $data['braintree_tlt_tls12'] = $this->request->post['braintree_tlt_tls12'];
        } else {
            $data['braintree_tlt_tls12'] = $this->config->get('braintree_tlt_tls12');
        }

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['braintree_tlt_geo_zone_id'])) {
			$data['braintree_tlt_geo_zone_id'] = $this->request->post['braintree_tlt_geo_zone_id'];
		} else {
			$data['braintree_tlt_geo_zone_id'] = $this->config->get('braintree_tlt_geo_zone_id'); 
		} 

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['braintree_tlt_status'])) {
			$data['braintree_tlt_status'] = $this->request->post['braintree_tlt_status'];
		} else {
			$data['braintree_tlt_status'] = $this->config->get('braintree_tlt_status');
		}

		if (isset($this->request->post['braintree_tlt_total'])) {
			$data['braintree_tlt_total'] = $this->request->post['braintree_tlt_total'];
		} else {
			$data['braintree_tlt_total'] = $this->config->get('braintree_tlt_total');
		}

		if (isset($this->request->post['braintree_tlt_sort_order'])) {
			$data['braintree_tlt_sort_order'] = $this->request->post['braintree_tlt_sort_order'];
		} else {
			$data['braintree_tlt_sort_order'] = $this->config->get('braintree_tlt_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/braintree_tlt', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/braintree_tlt')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['braintree_tlt_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}
        
		if (!$this->request->post['braintree_tlt_default_account']) {
			$this->error['default_account'] = $this->language->get('error_merchant_account');
		}

		if (strcmp($this->request->post['braintree_tlt_default_account'], $this->request->post['braintree_tlt_merchant']) === 0) {
			$this->error['default_account'] = $this->language->get('error_mismatch');
		}

		if (!$this->request->post['braintree_tlt_use_default']) {
			$default_currency = $this->config->get('config_currency');
			foreach ($this->request->post['braintree_tlt_merchant_account'] as $currency_id => $value) {
				if (!$value['code'] && $currency_id != $default_currency) {
					$this->error['merchant_account'][$currency_id] = $this->language->get('error_merchant_account');
				}
				if (strcmp($value['code'], $this->request->post['braintree_tlt_merchant']) === 0) {
					$this->error['merchant_account'][$currency_id] = $this->language->get('error_mismatch');
				}
				if (strcmp($value['code'], $this->request->post['braintree_tlt_default_account']) === 0) {
					$this->error['merchant_account'][$currency_id] = $this->language->get('error_mismatch_default');
				}
			}
		}

        if (!$this->request->post['braintree_tlt_public_key']) {
			$this->error['public'] = $this->language->get('error_public');
		}

		if (!$this->request->post['braintree_tlt_private_key']) {
			$this->error['key'] = $this->language->get('error_key');
		}
        
		return !$this->error;
	}
}
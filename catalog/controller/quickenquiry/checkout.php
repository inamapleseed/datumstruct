<?php
//require_once(DIR_SYSTEM . 'library/equotix/quickcheckout/equotix.php');
class ControllerQuickEnquiryCheckout extends Controller {
	
	protected $code = 'quickcheckout';
	protected $extension_id = '58';
	
	public function index() {

		$this->document->addScript('catalog/view/javascript/jquery/quickcheckout/quickcheckout.js');
		
		if ($this->config->get('quickcheckout_load_screen')) {
			$this->document->addScript('catalog/view/javascript/jquery/quickcheckout/quickcheckout.block.js');
		}
		
		if ($this->config->get('quickcheckout_countdown')) {
			$this->document->addScript('catalog/view/javascript/jquery/quickcheckout/quickcheckout.countdown. js');
		}
		
		$theme = $this->config->get('config_theme');
		
		if (file_exists(DIR_TEMPLATE . $theme . '/stylesheet/quickcheckout.css')) {
			$this->document->addStyle('catalog/view/theme/' . $theme . '/stylesheet/quickcheckout.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/quickcheckout.css');
		}
		
		if ($this->config->get('quickcheckout_layout') == '1') {
			$stylesheet = 'one';
		} elseif ($this->config->get('quickcheckout_layout') == '2') {
			$stylesheet = 'two';
		} else {
			$stylesheet = 'three';
		}

		$stylesheet = 'one';
		
		if (file_exists(DIR_TEMPLATE . $theme . '/stylesheet/quickcheckout_' . $stylesheet . '.css')) {
			$data['stylesheet'] = 'catalog/view/theme/' . $theme . '/stylesheet/quickcheckout_' . $stylesheet . '.css';
		} else {
			$data['stylesheet'] = 'catalog/view/theme/default/stylesheet/quickcheckout_' . $stylesheet . '.css';
		}
		
		if ($this->config->get('quickcheckout_responsive')) {
			if (file_exists(DIR_TEMPLATE . $theme . '/stylesheet/quickcheckout_mobile.css')) {
				$data['mobile_stylesheet'] = 'catalog/view/theme/' . $theme . '/stylesheet/quickcheckout_mobile.css';
			} else {
				$data['mobile_stylesheet'] = 'catalog/view/theme/default/stylesheet/quickcheckout_mobile.css';
			}
		} else {
			$data['mobile_stylesheet'] = '';
		}
		
		
		if (!$this->config->get('quickcheckout_debug') || !isset($this->request->get['debug'])) {
			if (!$this->config->get('quickcheckout_status')) {
				$this->response->redirect($this->url->link('enquiry/checkout', '', true));
			}
		}
		
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
		$this->document->addScript('catalog/view/javascript/jquery/quickcheckout/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/quickcheckout/bootstrap-datetimepicker.min.css');

		// Required by klarna
		if ($this->config->get('klarna_account') || $this->config->get('klarna_invoice')) {
			$this->document->addScript('http://cdn.klarna.com/public/kitt/toc/v1.0/js/klarna.terms.min.js');
		}
		
		// Validate cart has products and has stock.
		if ((!$this->enquiry->hasProducts()) || (!$this->enquiry->hasStock() && !$this->config->get('config_stock_checkout'))) {
	  		$this->response->redirect($this->url->link('enquiry/cart'));
    	}
		
		// Validate minimum quantity requirements.			
		$products = $this->enquiry->getProducts();
				
		foreach ($products as $product) {
			$product_total = 0;
				
			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}		
			
			if ($product['minimum'] > $product_total) {
				$this->response->redirect($this->url->link('enquiry/cart'));
			}				
		}

		if (sizeof($products)) {
			$this->facebookcommonutils = new FacebookCommonUtils();
			$params = new DAPixelConfigParams(array(
			  'eventName' => 'InitiateEnquiry',
			  'products' => $products,
			  'currency' => $this->currency,
			  'currencyCode' => $this->session->data['currency'],
			  'hasQuantity' => true));
			$facebook_pixel_event_params_FAE =
			  $this->facebookcommonutils->getDAPixelParamsForProducts($params);
			// stores the pixel params in the session
			$this->request->post['facebook_pixel_event_params_FAE'] =
			  addslashes(json_encode($facebook_pixel_event_params_FAE));
		  }
		
		$data = array_merge($data, $this->load->language('checkout/checkout'));
		$data = array_merge($data, $this->load->language('quickcheckout/checkout'));
		// Custom added
		$data = array_merge($data, $this->load->language('quickenquiry/overwrite'));
		
		// Validate minimum order total
		if ($this->enquiry->getTotal() < (float)$this->config->get('quickcheckout_minimum_order')) {
			$this->session->data['error'] = sprintf($this->language->get('error_minimum_order'), $this->currency->format($this->config->get('quickcheckout_minimum_order'), $this->session->data['currency']));
			
			$this->response->redirect($this->url->link('enquiry/cart'));
		}
		
		$this->document->setTitle($this->language->get('overwrite_confirm_heading_title')); 
		
		$data['breadcrumbs'] = array();

      	$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home')
      	); 

      	$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_cart'),
			'href'      => $this->url->link('enquiry/cart')
      	);
		
      	$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('quickenquiry/checkout', '', true)
      	);	
		
		if ($this->config->get('quickcheckout_html_header')) {
			$html_header = $this->config->get('quickcheckout_html_header');
			
			if (!empty($html_header[$this->config->get('config_language_id')])) {
				$data['html_header'] = html($html_header[$this->config->get('config_language_id')]);
			} else {
				$data['html_header'] = '';
			}
		} else {
			$data['html_header'] = '';
		}
		
		if ($this->config->get('quickcheckout_html_footer')) {
			$html_footer = $this->config->get('quickcheckout_html_footer');
			
			if (!empty($html_footer[$this->config->get('config_language_id')])) {
				$data['html_footer'] = html($html_footer[$this->config->get('config_language_id')]);
			} else {
				$data['html_footer'] = '';
			}
		} else {
			$data['html_footer'] = '';
		}
		
		$data['countdown_end'] = false;
		
		if ($this->config->get('quickcheckout_countdown')) {
			$data['timezone'] = substr(date('O'), 0, 3);
			
			$text = $this->config->get('quickcheckout_countdown_text');
			
			if (!empty($text[$this->config->get('config_language_id')])) {
				if ($this->config->get('quickcheckout_countdown_start')) {
					$time = date('d M Y') . ' ' . $this->config->get('quickcheckout_countdown_time') . ':00';

					if (time() > strtotime($time)) {
						$data['countdown_end'] = date('d M Y H:i:s', (strtotime($time) + 86400));
					} else {
						$data['countdown_end'] = $time;
					}
				} else {
					if (time() >= strtotime($this->config->get('quickcheckout_countdown_date_start')) && time() <= strtotime($this->config->get('quickcheckout_countdown_date_end'))) {
						$data['countdown_end'] = $this->config->get('quickcheckout_countdown_date_end');
					}
				}
			
				$text = explode('{timer}', html($text[$this->config->get('config_language_id')]));
				
				$data['countdown_before'] = $text[0];
				
				if (isset($text[1])) {
					$data['countdown_after'] = $text[1];
				} else {
					$data['countdown_after'] = '';
				}
				
				$data['countdown_timer'] = '{dn} {dl} {hnn} {hl} {mnn} {ml} {snn} {sl}';
			}
		}
		
		// All variables
		$data['logged'] = $this->customer->isLogged();
		$data['shipping_required'] = $this->enquiry->hasShipping();
		$data['load_screen'] = $this->config->get('quickcheckout_load_screen');
		$data['loading_display'] = $this->config->get('quickcheckout_loading_display') ? 'true' : 'false';
		$data['edit_cart'] = $this->config->get('quickcheckout_edit_cart');
		$data['highlight_error'] = $this->config->get('quickcheckout_highlight_error');
		$data['text_error'] = $this->config->get('quickcheckout_text_error');
		$data['quickcheckout_layout'] = 1; //$this->config->get('quickcheckout_layout');
		$data['slide_effect'] = $this->config->get('quickcheckout_slide_effect');
		$data['debug'] = $this->config->get('quickcheckout_debug');
		$data['auto_submit'] = $this->config->get('quickcheckout_auto_submit');
		$data['coupon_module'] = $this->config->get('quickcheckout_coupon');
		$data['voucher_module'] = $this->config->get('quickcheckout_voucher');
		$data['reward_module'] = $this->config->get('quickcheckout_reward');
		$data['cart_module'] = $this->config->get('quickcheckout_cart');
		$data['payment_module'] = $this->config->get('ccccc');
		$data['shipping_module'] = $this->config->get('quickcheckout_shipping_module');
		$data['login_module'] = $this->config->get('quickcheckout_login_module');
		$data['countdown'] = $this->config->get('quickcheckout_countdown');
		$data['save_data'] = $this->config->get('quickcheckout_save_data');
		$data['custom_css'] = html($this->config->get('quickcheckout_custom_css'));
		$data['confirmation_page'] = $this->config->get('quickcheckout_confirmation_page');
		
		if (!$this->customer->isLogged()) {
			$data['guest'] = $this->load->controller('quickenquiry/guest');
			$data['guest_shipping'] = $this->load->controller('quickenquiry/guest_shipping');
			
			if ($this->config->get('quickcheckout_login_module')) {
				$data['login'] = $this->load->controller('quickenquiry/login');
			}
		} else {
			$data['payment_address'] = $this->load->controller('quickenquiry/payment_address');
			$data['shipping_address'] = $this->load->controller('quickenquiry/shipping_address');
		}
		
		$data['voucher'] = ''; //$this->load->controller('quickenquiry/voucher');
		$data['terms'] = $this->load->controller('quickenquiry/terms');
		
		$data = $this->load->controller('common/common', $data);

		$this->response->setOutput($this->load->view('quickenquiry/checkout', $data));
  	}
	
	public function country() {
		$json = array();
		
		$this->load->model('localisation/country');

    	$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);
		
		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']		
			);
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function customfield() {
		$json = array();

		$this->load->model('account/custom_field');

		// Customer Group
		if (isset($this->request->get['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->get['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $this->request->get['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

		foreach ($custom_fields as $custom_field) {
			$json[] = array(
				'custom_field_id' => $custom_field['custom_field_id'],
				'required'        => $custom_field['required']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function save() {
		$json = array();

		if (!$this->customer->isLogged() && isset($this->request->get['type'])) {
			if ($this->request->get['type'] == 'payment') {
				$this->session->data['guest']['firstname'] = $this->request->post['firstname'];
				$this->session->data['guest']['lastname'] = $this->request->post['lastname'];
				$this->session->data['guest']['email'] = $this->request->post['email'];
				$this->session->data['guest']['telephone'] = $this->request->post['telephone'];
				$this->session->data['guest']['fax'] = $this->request->post['fax'];
				$this->session->data['guest']['shipping_address'] = isset($this->request->post['shipping_address']) ? true : false;
				$this->session->data['guest']['create_account'] = isset($this->request->post['create_account']) ? true : false;
				
				$this->session->data['payment_address']['firstname'] = $this->request->post['firstname'];
				$this->session->data['payment_address']['lastname'] = $this->request->post['lastname'];
				$this->session->data['payment_address']['company'] = $this->request->post['company'];
				$this->session->data['payment_address']['address_1'] = $this->request->post['address_1'];
				$this->session->data['payment_address']['address_2'] = $this->request->post['address_2'];
				$this->session->data['payment_address']['postcode'] = $this->request->post['postcode'];
				$this->session->data['payment_address']['city'] = $this->request->post['city'];
				$this->session->data['payment_address']['country_id'] = $this->request->post['country_id'];
				$this->session->data['payment_address']['zone_id'] = $this->request->post['zone_id'];
				
				if (isset($this->request->post['custom_field']['address'])) {
					$this->session->data['payment_address']['custom_field'] = $this->request->post['custom_field']['address'];
				} else {
					$this->session->data['payment_address']['custom_field'] = array();
				}
			} else {
				$this->session->data['shipping_address']['firstname'] = $this->request->post['firstname'];
				$this->session->data['shipping_address']['lastname'] = $this->request->post['lastname'];
				$this->session->data['shipping_address']['company'] = $this->request->post['company'];
				$this->session->data['shipping_address']['address_1'] = $this->request->post['address_1'];
				$this->session->data['shipping_address']['address_2'] = $this->request->post['address_2'];
				$this->session->data['shipping_address']['postcode'] = $this->request->post['postcode'];
				$this->session->data['shipping_address']['city'] = $this->request->post['city'];
				$this->session->data['shipping_address']['country_id'] = $this->request->post['country_id'];
				$this->session->data['shipping_address']['zone_id'] = $this->request->post['zone_id'];
				
				if (isset($this->request->post['custom_field'])) {
					$this->session->data['shipping_address']['custom_field'] = $this->request->post['custom_field']['address'];
				} else {
					$this->session->data['shipping_address']['custom_field'] = array();
				}
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
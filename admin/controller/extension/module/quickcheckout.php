<?php
require_once(DIR_SYSTEM . 'library/equotix/quickcheckout/equotix.php');
class ControllerExtensionModuleQuickCheckout extends Equotix {
	protected $version = '11.0.0';
	protected $code = 'quickcheckout';
	protected $extension = 'Quick Checkout';
	protected $extension_id = '58';
	protected $purchase_url = 'quick-checkout';
	protected $purchase_id = '7382';
	protected $error = array();

	public function index() {
		$this->load->language('extension/module/quickcheckout');

		$this->document->setTitle(strip_tags($this->language->get('heading_title')));
		
		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->get['store_id'];
		} else {
			$store_id = 0;
		}
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('quickcheckout', $this->request->post, $store_id);		
			
			$this->session->data['success'] = $this->language->get('text_success');
		
			if (!isset($this->request->get['continue'])) {
				$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
			} else {
				$this->response->redirect($this->url->link('extension/module/quickcheckout', 'token=' . $this->session->data['token'] . '&store_id=' . $store_id, true));
			}
		}
	
		// All fields
		$fields = array(
			'firstname',
			'lastname',
			'email',
			'telephone',
			'fax',
			'company',
			'customer_group',
			'address_1',
			'address_2',
			'unit_no',
			'city',
			'postcode',
			'country',
			'zone',
			'newsletter',
			'register',
			'comment'
		);
		
		$data['fields'] = $fields;

		// Heading
		$data['heading_title'] = $this->language->get('heading_title');
		
		// Tab
		$data['tab_home'] = $this->language->get('tab_home');
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_design'] = $this->language->get('tab_design');
		$data['tab_field'] = $this->language->get('tab_field');
		$data['tab_module'] = $this->language->get('tab_module');
		$data['tab_payment'] = $this->language->get('tab_payment');
		$data['tab_shipping'] = $this->language->get('tab_shipping');
		$data['tab_survey'] = $this->language->get('tab_survey');
		$data['tab_delivery'] = $this->language->get('tab_delivery');
		$data['tab_countdown'] = $this->language->get('tab_countdown');
		$data['tab_analytics'] = $this->language->get('tab_analytics');
		
		// Help
		$data['help_status'] = $this->language->get('help_status');
		$data['help_confirmation_page'] = $this->language->get('help_confirmation_page');
		$data['help_load_screen'] = $this->language->get('help_load_screen');
		$data['help_loading_display'] = $this->language->get('help_loading_display');
		$data['help_payment_logo'] = $this->language->get('help_payment_logo');
		$data['help_shipping_logo'] = $this->language->get('help_shipping_logo');
		$data['help_payment'] = $this->language->get('help_payment');
		$data['help_shipping'] = $this->language->get('help_shipping');
		$data['help_payment_default'] = $this->language->get('help_payment_default');
		$data['help_shipping_default'] = $this->language->get('help_shipping_default');
		$data['help_edit_cart'] = $this->language->get('help_edit_cart');
		$data['help_highlight_error'] = $this->language->get('help_highlight_error');
		$data['help_text_error'] = $this->language->get('help_text_error');
		$data['help_layout'] = $this->language->get('help_layout');
		$data['help_slide_effect'] = $this->language->get('help_slide_effect');
		$data['help_minimum_order'] = $this->language->get('help_minimum_order');
		$data['help_save_data'] = $this->language->get('help_save_data');
		$data['help_debug'] = $this->language->get('help_debug');
		$data['help_auto_submit'] = $this->language->get('help_auto_submit');
		$data['help_payment_target'] = $this->language->get('help_payment_target');
		$data['help_proceed_button_text'] = $this->language->get('help_proceed_button_text');
		$data['help_responsive'] = $this->language->get('help_responsive');
		$data['help_payment_reload'] = $this->language->get('help_payment_reload');
		$data['help_shipping_reload'] = $this->language->get('help_shipping_reload');
		$data['help_voucher'] = $this->language->get('help_voucher');
		$data['help_coupon'] = $this->language->get('help_coupon');
		$data['help_reward'] = $this->language->get('help_reward');
		$data['help_cart'] = $this->language->get('help_cart');
		$data['help_shipping_module'] = $this->language->get('help_shipping_module');
		$data['help_payment_module'] = $this->language->get('help_payment_module');
		$data['help_login_module'] = $this->language->get('help_login_module');
		$data['help_html_header'] = $this->language->get('help_html_header');
		$data['help_html_footer'] = $this->language->get('help_html_footer');
		$data['help_survey_required'] = $this->language->get('help_survey_required');
		$data['help_survey_text'] = $this->language->get('help_survey_text');
		$data['help_survey_type'] = $this->language->get('help_survey_type');
		$data['help_survey_answer'] = $this->language->get('help_survey_answer');
		$data['help_delivery'] = $this->language->get('help_delivery');
		$data['help_delivery_time'] = $this->language->get('help_delivery_time');
		$data['help_delivery_required'] = $this->language->get('help_delivery_required');
		$data['help_delivery_unavailable'] = $this->language->get('help_delivery_unavailable');
		$data['help_delivery_min'] = $this->language->get('help_delivery_min');
		$data['help_delivery_max'] = $this->language->get('help_delivery_max');
		$data['help_delivery_min_hour'] = $this->language->get('help_delivery_min_hour');
		$data['help_delivery_max_hour'] = $this->language->get('help_delivery_max_hour');
		$data['help_delivery_days_of_week'] = $this->language->get('help_delivery_days_of_week');
		$data['help_delivery_times'] = $this->language->get('help_delivery_times');
		$data['help_countdown'] = $this->language->get('help_countdown');
		$data['help_countdown_start'] = $this->language->get('help_countdown_start');
		$data['help_countdown_date_start'] = $this->language->get('help_countdown_date_start');
		$data['help_countdown_date_end'] = $this->language->get('help_countdown_date_end');
		$data['help_countdown_time'] = $this->language->get('help_countdown_time');
		$data['help_countdown_text'] = $this->language->get('help_countdown_text');
		$data['help_display_more'] = $this->language->get('help_display_more');
		
		// Home
		$data['text_default_store'] = $this->language->get('text_default_store');
		$data['text_general'] = $this->language->get('text_general');
		$data['text_design'] = $this->language->get('text_design');
		$data['text_field'] = $this->language->get('text_field');
		$data['text_module_home'] = $this->language->get('text_module_home');
		$data['text_payment'] = $this->language->get('text_payment');
		$data['text_shipping'] = $this->language->get('text_shipping');
		$data['text_survey'] = $this->language->get('text_survey');
		$data['text_delivery'] = $this->language->get('text_delivery');
		$data['text_countdown'] = $this->language->get('text_countdown');
		$data['text_analytics'] = $this->language->get('text_analytics');
		
		// System
		$data['entry_store'] = $this->language->get('entry_store');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_radio_type'] = $this->language->get('text_radio_type');
		$data['text_select_type'] = $this->language->get('text_select_type');
		$data['text_text_type'] = $this->language->get('text_text_type');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_one_column'] = $this->language->get('text_one_column');
		$data['text_two_column'] = $this->language->get('text_two_column');
		$data['text_three_column'] = $this->language->get('text_three_column');
		$data['text_estimate'] = $this->language->get('text_estimate');
		$data['text_choose'] = $this->language->get('text_choose');
		$data['text_day'] = $this->language->get('text_day');
		$data['text_specific'] = $this->language->get('text_specific');
		$data['text_display'] = $this->language->get('text_display');
		$data['text_required'] = $this->language->get('text_required');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_placeholder'] = $this->language->get('text_placeholder');
		$data['text_sort_order'] = $this->language->get('text_sort_order');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_purchase_analytics'] = $this->language->get('text_purchase_analytics');
		$data['text_overlay'] = $this->language->get('text_overlay');
		$data['text_spinner'] = $this->language->get('text_spinner');
		
		// General
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_minimum_order'] = $this->language->get('entry_minimum_order');
		$data['entry_debug'] = $this->language->get('entry_debug');
		$data['entry_confirmation_page'] = $this->language->get('entry_confirmation_page');
		$data['entry_save_data'] = $this->language->get('entry_save_data');
		$data['entry_edit_cart'] = $this->language->get('entry_edit_cart');
		$data['entry_highlight_error'] = $this->language->get('entry_highlight_error');
		$data['entry_text_error'] = $this->language->get('entry_text_error');
		$data['entry_auto_submit'] = $this->language->get('entry_auto_submit');
		$data['entry_payment_target'] = $this->language->get('entry_payment_target');
		$data['entry_proceed_button_text'] = $this->language->get('entry_proceed_button_text');

		// Design
		$data['entry_load_screen'] = $this->language->get('entry_load_screen');
		$data['entry_loading_display'] = $this->language->get('entry_loading_display');
		$data['entry_layout'] = $this->language->get('entry_layout');
		$data['entry_responsive'] = $this->language->get('entry_responsive');
		$data['entry_slide_effect'] = $this->language->get('entry_slide_effect');
		$data['entry_custom_css'] = $this->language->get('entry_custom_css');
		
		// Fields
		foreach ($fields as $field) {
			$data['entry_field_' . $field] = $this->language->get('entry_field_' . $field);
		}
		
		// Modules
		$data['entry_voucher'] = $this->language->get('entry_voucher');
		$data['entry_coupon'] = $this->language->get('entry_coupon');
		$data['entry_reward'] = $this->language->get('entry_reward');
		$data['entry_cart'] = $this->language->get('entry_cart');
		$data['entry_login_module'] = $this->language->get('entry_login_module');
		$data['entry_html_header'] = $this->language->get('entry_html_header');
		$data['entry_html_footer'] = $this->language->get('entry_html_footer');
		
		// Payment
		$data['entry_payment_module'] = $this->language->get('entry_payment_module');
		$data['entry_payment_reload'] = $this->language->get('entry_payment_reload');
		$data['entry_payment'] = $this->language->get('entry_payment');
		$data['entry_payment_default'] = $this->language->get('entry_payment_default');
		$data['entry_payment_logo'] = $this->language->get('entry_payment_logo');

		// Shipping
		$data['entry_shipping_module'] = $this->language->get('entry_shipping_module');
		$data['entry_shipping_reload'] = $this->language->get('entry_shipping_reload');
		$data['entry_shipping'] = $this->language->get('entry_shipping');
		$data['entry_shipping_default'] = $this->language->get('entry_shipping_default');
		$data['entry_shipping_logo'] = $this->language->get('entry_shipping');
		
		// Survey
		$data['entry_survey'] = $this->language->get('entry_survey');
		$data['entry_survey_required'] = $this->language->get('entry_survey_required');
		$data['entry_survey_text'] = $this->language->get('entry_survey_text');
		$data['entry_survey_type'] = $this->language->get('entry_survey_type');
		$data['entry_survey_answer'] = $this->language->get('entry_survey_answer');
		
		// Delivery
		$data['entry_delivery'] = $this->language->get('entry_delivery');
		$data['entry_delivery_date'] = $this->language->get('entry_delivery_date');
		$data['entry_delivery_time'] = $this->language->get('entry_delivery_time');
		$data['entry_delivery_required'] = $this->language->get('entry_delivery_required');
		$data['entry_delivery_unavailable'] = $this->language->get('entry_delivery_unavailable');
		$data['entry_delivery_min'] = $this->language->get('entry_delivery_min');
		$data['entry_delivery_max'] = $this->language->get('entry_delivery_max');
		$data['entry_delivery_min_hour'] = $this->language->get('entry_delivery_min_hour');
		$data['entry_delivery_max_hour'] = $this->language->get('entry_delivery_max_hour');
		$data['entry_delivery_days_of_week'] = $this->language->get('entry_delivery_days_of_week');
		$data['entry_delivery_times'] = $this->language->get('entry_delivery_times');
		
		// Countdown
		$data['entry_countdown'] = $this->language->get('entry_countdown');
		$data['entry_countdown_start'] = $this->language->get('entry_countdown_start');
		$data['entry_countdown_date_start'] = $this->language->get('entry_countdown_date_start');
		$data['entry_countdown_date_end'] = $this->language->get('entry_countdown_date_end');
		$data['entry_countdown_time'] = $this->language->get('entry_countdown_time');
		$data['entry_countdown_text'] = $this->language->get('entry_countdown_text');
		
		// Button
		$data['button_save'] = $this->language->get('button_save');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_remove'] = $this->language->get('button_remove');
		
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
		
		$setting = $this->model_setting_setting->getSetting('quickcheckout', $store_id);
		
  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], true)
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/module/quickcheckout', 'token=' . $this->session->data['token'], true)
   		);
		
		$data['action'] = $this->url->link('extension/module/quickcheckout', 'token=' . $this->session->data['token'] . '&store_id=' . $store_id, true);
		$data['continue'] = $this->url->link('extension/module/quickcheckout', 'token=' . $this->session->data['token'] . '&continue=1&store_id=' . $store_id, true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);
		
		// General
		if (isset($this->request->post['quickcheckout_status'])) {
			$data['quickcheckout_status'] = $this->request->post['quickcheckout_status'];
		} elseif (isset($setting['quickcheckout_status'])) {
			$data['quickcheckout_status'] = $setting['quickcheckout_status'];
		} else {
			$data['quickcheckout_status'] = 0;
		}
		
		if (isset($this->request->post['quickcheckout_minimum_order'])) {
			$data['quickcheckout_minimum_order'] = $this->request->post['quickcheckout_minimum_order'];
		} elseif (isset($setting['quickcheckout_minimum_order'])) {
			$data['quickcheckout_minimum_order'] = $setting['quickcheckout_minimum_order'];
		} else {
			$data['quickcheckout_minimum_order'] = 0;
		}
		
		if (isset($this->request->post['quickcheckout_debug'])) {
			$data['quickcheckout_debug'] = $this->request->post['quickcheckout_debug'];
		} elseif (isset($setting['quickcheckout_debug'])) {
			$data['quickcheckout_debug'] = $setting['quickcheckout_debug'];
		} else {
			$data['quickcheckout_debug'] = 0;
		}
		
		if (isset($this->request->post['quickcheckout_confirmation_page'])) {
			$data['quickcheckout_confirmation_page'] = $this->request->post['quickcheckout_confirmation_page'];
		} elseif (isset($setting['quickcheckout_confirmation_page'])) {
			$data['quickcheckout_confirmation_page'] = $setting['quickcheckout_confirmation_page'];
		} else {
			$data['quickcheckout_confirmation_page'] = 1;
		}
		
		if (isset($this->request->post['quickcheckout_save_data'])) {
			$data['quickcheckout_save_data'] = $this->request->post['quickcheckout_save_data'];
		} elseif (isset($setting['quickcheckout_save_data'])) {
			$data['quickcheckout_save_data'] = $setting['quickcheckout_save_data'];
		} else {
			$data['quickcheckout_save_data'] = 0;
		}
		
		if (isset($this->request->post['quickcheckout_edit_cart'])) {
			$data['quickcheckout_edit_cart'] = $this->request->post['quickcheckout_edit_cart'];
		} elseif (isset($setting['quickcheckout_edit_cart'])) {
			$data['quickcheckout_edit_cart'] = $setting['quickcheckout_edit_cart'];
		} else {
			$data['quickcheckout_edit_cart'] = 1;
		}
		
		if (isset($this->request->post['quickcheckout_highlight_error'])) {
			$data['quickcheckout_highlight_error'] = $this->request->post['quickcheckout_highlight_error'];
		} elseif (isset($setting['quickcheckout_highlight_error'])) {
			$data['quickcheckout_highlight_error'] = $setting['quickcheckout_highlight_error'];
		} else {
			$data['quickcheckout_highlight_error'] = 1;
		}
		
		if (isset($this->request->post['quickcheckout_text_error'])) {
			$data['quickcheckout_text_error'] = $this->request->post['quickcheckout_text_error'];
		} elseif (isset($setting['quickcheckout_text_error'])) {
			$data['quickcheckout_text_error'] = $setting['quickcheckout_text_error'];
		} else {
			$data['quickcheckout_text_error'] = 1;
		}
		
		if (isset($this->request->post['quickcheckout_auto_submit'])) {
			$data['quickcheckout_auto_submit'] = $this->request->post['quickcheckout_auto_submit'];
		} elseif (isset($setting['quickcheckout_auto_submit'])) {
			$data['quickcheckout_auto_submit'] = $setting['quickcheckout_auto_submit'];
		} else {
			$data['quickcheckout_auto_submit'] = 0;
		}
		
		if (isset($this->request->post['quickcheckout_payment_target'])) {
			$data['quickcheckout_payment_target'] = $this->request->post['quickcheckout_payment_target'];
		} elseif (isset($setting['quickcheckout_payment_target'])) {
			$data['quickcheckout_payment_target'] = $setting['quickcheckout_payment_target'];
		} else {
			$data['quickcheckout_payment_target'] = '#button-confirm, .button, .btn';
		}
		
		if (isset($this->request->post['quickcheckout_proceed_button_text'])) {
			$data['quickcheckout_proceed_button_text'] = $this->request->post['quickcheckout_proceed_button_text'];
		} elseif (isset($setting['quickcheckout_proceed_button_text']) && is_array($setting['quickcheckout_proceed_button_text'])) {
			$data['quickcheckout_proceed_button_text'] = $setting['quickcheckout_proceed_button_text'];
		} else {
			$data['quickcheckout_proceed_button_text'] = array();
		}
		
		// Design
		if (isset($this->request->post['quickcheckout_load_screen'])) {
			$data['quickcheckout_load_screen'] = $this->request->post['quickcheckout_load_screen'];
		} elseif (isset($setting['quickcheckout_load_screen'])) {
			$data['quickcheckout_load_screen'] = $setting['quickcheckout_load_screen'];
		} else {
			$data['quickcheckout_load_screen'] = 0;
		}
		
		if (isset($this->request->post['quickcheckout_loading_display'])) {
			$data['quickcheckout_loading_display'] = $this->request->post['quickcheckout_loading_display'];
		} elseif (isset($setting['quickcheckout_loading_display'])) {
			$data['quickcheckout_loading_display'] = $setting['quickcheckout_loading_display'];
		} else {
			$data['quickcheckout_loading_display'] = 0;
		}
		
		if (isset($this->request->post['quickcheckout_layout'])) {
			$data['quickcheckout_layout'] = $this->request->post['quickcheckout_layout'];
		} elseif (isset($setting['quickcheckout_layout'])) {
			$data['quickcheckout_layout'] = $setting['quickcheckout_layout'];
		} else {
			$data['quickcheckout_layout'] = 2;
		}
		
		if (isset($this->request->post['quickcheckout_responsive'])) {
			$data['quickcheckout_responsive'] = $this->request->post['quickcheckout_responsive'];
		} elseif (isset($setting['quickcheckout_responsive'])) {
			$data['quickcheckout_responsive'] = $setting['quickcheckout_responsive'];
		} else {
			$data['quickcheckout_responsive'] = 1;
		}
		
		if (isset($this->request->post['quickcheckout_slide_effect'])) {
			$data['quickcheckout_slide_effect'] = $this->request->post['quickcheckout_slide_effect'];
		} elseif (isset($setting['quickcheckout_slide_effect'])) {
			$data['quickcheckout_slide_effect'] = $setting['quickcheckout_slide_effect'];
		} else {
			$data['quickcheckout_slide_effect'] = 0;
		}
		
		if (isset($this->request->post['quickcheckout_custom_css'])) {
			$data['quickcheckout_custom_css'] = $this->request->post['quickcheckout_custom_css'];
		} elseif (isset($setting['quickcheckout_custom_css'])) {
			$data['quickcheckout_custom_css'] = $setting['quickcheckout_custom_css'];
		} else {
			$data['quickcheckout_custom_css'] = '';
		}
		
		// Fields
		foreach ($fields as $field) {
			if (isset($this->request->post['quickcheckout_field_' . $field])) {
				$data['quickcheckout_field_' . $field] = $this->request->post['quickcheckout_field_' . $field];
			} elseif (isset($setting['quickcheckout_field_' . $field]) && is_array($setting['quickcheckout_field_' . $field])) {
				$data['quickcheckout_field_' . $field] = $setting['quickcheckout_field_' . $field];
			} else {
				$data['quickcheckout_field_' . $field] = array();
			}
			// debug($data['quickcheckout_field_' . $field]);
		}
		
		// Modules
		if (isset($this->request->post['quickcheckout_coupon'])) {
			$data['quickcheckout_coupon'] = $this->request->post['quickcheckout_coupon'];
		} elseif (isset($setting['quickcheckout_coupon'])) {
			$data['quickcheckout_coupon'] = $setting['quickcheckout_coupon'];
		} else {
			$data['quickcheckout_coupon'] = 0;
		}
		
		if (isset($this->request->post['quickcheckout_voucher'])) {
			$data['quickcheckout_voucher'] = $this->request->post['quickcheckout_voucher'];
		} elseif (isset($setting['quickcheckout_voucher'])) {
			$data['quickcheckout_voucher'] = $setting['quickcheckout_voucher'];
		} else {
			$data['quickcheckout_voucher'] = 0;
		}
		
		if (isset($this->request->post['quickcheckout_reward'])) {
			$data['quickcheckout_reward'] = $this->request->post['quickcheckout_reward'];
		} elseif (isset($setting['quickcheckout_reward'])) {
			$data['quickcheckout_reward'] = $setting['quickcheckout_reward'];
		} else {
			$data['quickcheckout_reward'] = 0;
		}
		
		if (isset($this->request->post['quickcheckout_cart'])) {
			$data['quickcheckout_cart'] = $this->request->post['quickcheckout_cart'];
		} elseif (isset($setting['quickcheckout_cart'])) {
			$data['quickcheckout_cart'] = $setting['quickcheckout_cart'];
		} else {
			$data['quickcheckout_cart'] = 0;
		}
		
		if (isset($this->request->post['quickcheckout_login_module'])) {
			$data['quickcheckout_login_module'] = $this->request->post['quickcheckout_login_module'];
		} elseif (isset($setting['quickcheckout_login_module'])) {
			$data['quickcheckout_login_module'] = $setting['quickcheckout_login_module'];
		} else {
			$data['quickcheckout_login_module'] = 0;
		}
		
		if (isset($this->request->post['quickcheckout_html_header'])) {
			$data['quickcheckout_html_header'] = $this->request->post['quickcheckout_html_header'];
		} elseif (isset($setting['quickcheckout_html_header']) && is_array($setting['quickcheckout_html_header'])) {
			$data['quickcheckout_html_header'] = $setting['quickcheckout_html_header'];
		} else {
			$data['quickcheckout_html_header'] = array();
		}
		
		if (isset($this->request->post['quickcheckout_html_footer'])) {
			$data['quickcheckout_html_footer'] = $this->request->post['quickcheckout_html_footer'];
		} elseif (isset($setting['quickcheckout_html_footer']) && is_array($setting['quickcheckout_html_footer'])) {
			$data['quickcheckout_html_footer'] = $setting['quickcheckout_html_footer'];
		} else {
			$data['quickcheckout_html_footer'] = array();
		}
		
		// Payment
		if (isset($this->request->post['quickcheckout_payment_module'])) {
			$data['quickcheckout_payment_module'] = $this->request->post['quickcheckout_payment_module'];
		} elseif (isset($setting['quickcheckout_payment_module'])) {
			$data['quickcheckout_payment_module'] = $setting['quickcheckout_payment_module'];
		} else {
			$data['quickcheckout_payment_module'] = 0;
		}
		
		if (isset($this->request->post['quickcheckout_payment_reload'])) {
			$data['quickcheckout_payment_reload'] = $this->request->post['quickcheckout_payment_reload'];
		} elseif (isset($setting['quickcheckout_payment_reload'])) {
			$data['quickcheckout_payment_reload'] = $setting['quickcheckout_payment_reload'];
		} else {
			$data['quickcheckout_payment_reload'] = 0;
		}
		
		if (isset($this->request->post['quickcheckout_payment'])) {
			$data['quickcheckout_payment'] = $this->request->post['quickcheckout_payment'];
		} elseif (isset($setting['quickcheckout_payment'])) {
			$data['quickcheckout_payment'] = $setting['quickcheckout_payment'];
		} else {
			$data['quickcheckout_payment'] = 0;
		}
		
		if (isset($this->request->post['quickcheckout_payment_default'])) {
			$data['quickcheckout_payment_default'] = $this->request->post['quickcheckout_payment_default'];
		} elseif (isset($setting['quickcheckout_payment_default'])) {
			$data['quickcheckout_payment_default'] = $setting['quickcheckout_payment_default'];
		} else {
			$data['quickcheckout_payment_default'] = '';
		}
		
		if (isset($this->request->post['quickcheckout_payment_logo'])) {
			$data['quickcheckout_payment_logo'] = $this->request->post['quickcheckout_payment_logo'];
		} elseif (isset($setting['quickcheckout_payment_logo']) && is_array($setting['quickcheckout_payment_logo'])) {
			$data['quickcheckout_payment_logo'] = $setting['quickcheckout_payment_logo'];
		} else {
			$data['quickcheckout_payment_logo'] = array();
		}
		
		$this->load->model('tool/image');

		$data['placeholder'] = $placeholder = $this->model_tool_image->resize('no_image.png', 100, 100);

		if($data['quickcheckout_payment_logo']){
			foreach($data['quickcheckout_payment_logo'] as $code => &$logo){ // debug($code);
				if( is_file(DIR_IMAGE . $logo) ){
					$logo = array(
						'image'	=>	$logo,
						'thumb'	=>	$this->model_tool_image->resize($logo, 100, 100),
					);
				}
				else{
					$logo = array(
						'image'	=>	'',
						'thumb'	=>	$placeholder,
					);
				}
			}
		}

		// Shipping
		if (isset($this->request->post['quickcheckout_shipping_module'])) {
			$data['quickcheckout_shipping_module'] = $this->request->post['quickcheckout_shipping_module'];
		} elseif (isset($setting['quickcheckout_shipping_module'])) {
			$data['quickcheckout_shipping_module'] = $setting['quickcheckout_shipping_module'];
		} else {
			$data['quickcheckout_shipping_module'] = 0;
		}
		
		if (isset($this->request->post['quickcheckout_shipping'])) {
			$data['quickcheckout_shipping'] = $this->request->post['quickcheckout_shipping'];
		} elseif (isset($setting['quickcheckout_shipping'])) {
			$data['quickcheckout_shipping'] = $setting['quickcheckout_shipping'];
		} else {
			$data['quickcheckout_shipping'] = 0;
		}
		
		if (isset($this->request->post['quickcheckout_shipping_default'])) {
			$data['quickcheckout_shipping_default'] = $this->request->post['quickcheckout_shipping_default'];
		} elseif (isset($setting['quickcheckout_shipping_default'])) {
			$data['quickcheckout_shipping_default'] = $setting['quickcheckout_shipping_default'];
		} else {
			$data['quickcheckout_shipping_default'] = 0;
		}
		
		if (isset($this->request->post['quickcheckout_shipping_reload'])) {
			$data['quickcheckout_shipping_reload'] = $this->request->post['quickcheckout_shipping_reload'];
		} elseif (isset($setting['quickcheckout_shipping_reload'])) {
			$data['quickcheckout_shipping_reload'] = $setting['quickcheckout_shipping_reload'];
		} else {
			$data['quickcheckout_shipping_reload'] = 0;
		}
		
		if (isset($this->request->post['quickcheckout_shipping_general_notice'])) {
			$data['quickcheckout_shipping_general_notice'] = $this->request->post['quickcheckout_shipping_general_notice'];
		} elseif (isset($setting['quickcheckout_shipping_general_notice'])) {
			$data['quickcheckout_shipping_general_notice'] = $setting['quickcheckout_shipping_general_notice'];
		} else {
			$data['quickcheckout_shipping_general_notice'] = array();
		}
		
		if (isset($this->request->post['quickcheckout_shipping_logo'])) {
			$data['quickcheckout_shipping_logo'] = $this->request->post['quickcheckout_shipping_logo'];
		} elseif (isset($setting['quickcheckout_shipping_logo']) && is_array($setting['quickcheckout_shipping_logo'])) {
			$data['quickcheckout_shipping_logo'] = $setting['quickcheckout_shipping_logo'];
		} else {
			$data['quickcheckout_shipping_logo'] = array();
		}

		if($data['quickcheckout_shipping_logo']){
			foreach($data['quickcheckout_shipping_logo'] as $code => &$logo){ // debug($code);
				if( is_file(DIR_IMAGE . $logo) ){
					$logo = array(
						'image'	=>	$logo,
						'thumb'	=>	$this->model_tool_image->resize($logo, 100, 100),
					);
				}
				else{
					$logo = array(
						'image'	=>	'',
						'thumb'	=>	$placeholder,
					);
				}
			}
		}

		if (isset($this->request->post['quickcheckout_shipping_individual_shipping_notice'])) {
			$data['quickcheckout_shipping_individual_shipping_notice'] = $this->request->post['quickcheckout_shipping_individual_shipping_notice'];
		} elseif (isset($setting['quickcheckout_shipping_individual_shipping_notice']) && is_array($setting['quickcheckout_shipping_individual_shipping_notice'])) {
			$data['quickcheckout_shipping_individual_shipping_notice'] = $setting['quickcheckout_shipping_individual_shipping_notice'];
		} else {
			$data['quickcheckout_shipping_individual_shipping_notice'] = array();
		}
		
		// Survey
		if (isset($this->request->post['quickcheckout_survey'])) {
			$data['quickcheckout_survey'] = $this->request->post['quickcheckout_survey'];
		} elseif (isset($setting['quickcheckout_survey'])) {
			$data['quickcheckout_survey'] = $setting['quickcheckout_survey'];
		} else {
			$data['quickcheckout_survey'] = 0;
		}
		
		if (isset($this->request->post['quickcheckout_survey_required'])) {
			$data['quickcheckout_survey_required'] = $this->request->post['quickcheckout_survey_required'];
		} elseif (isset($setting['quickcheckout_survey_required'])) {
			$data['quickcheckout_survey_required'] = $setting['quickcheckout_survey_required'];
		} else {
			$data['quickcheckout_survey_required'] = 0;
		}
		
		if (isset($this->request->post['quickcheckout_survey_text'])) {
			$data['quickcheckout_survey_text'] = $this->request->post['quickcheckout_survey_text'];
		} elseif (isset($setting['quickcheckout_survey_text']) && is_array($setting['quickcheckout_survey_text'])) {
			$data['quickcheckout_survey_text'] = $setting['quickcheckout_survey_text'];
		} else {
			$data['quickcheckout_survey_text'] = array();
		}
		
		if (isset($this->request->post['quickcheckout_survey_type'])) {
			$data['quickcheckout_survey_type'] = $this->request->post['quickcheckout_survey_type'];
		} elseif (isset($setting['quickcheckout_survey_type'])) {
			$data['quickcheckout_survey_type'] = $setting['quickcheckout_survey_type'];
		} else {
			$data['quickcheckout_survey_type'] = 0;
		}
		
		if (isset($this->request->post['quickcheckout_survey_answers'])) {
			$data['quickcheckout_survey_answers'] = $this->request->post['quickcheckout_survey_answers'];
		} elseif (isset($setting['quickcheckout_survey_answers']) && is_array($setting['quickcheckout_survey_answers'])) {
			$data['quickcheckout_survey_answers'] = $setting['quickcheckout_survey_answers'];
		} else {
			$data['quickcheckout_survey_answers'] = array();
		}
		
		// Delivery
		if (isset($this->request->post['quickcheckout_delivery'])) {
			$data['quickcheckout_delivery'] = $this->request->post['quickcheckout_delivery'];
		} elseif (isset($setting['quickcheckout_delivery'])) {
			$data['quickcheckout_delivery'] = $setting['quickcheckout_delivery'];
		} else {
			$data['quickcheckout_delivery'] = 0;
		}
		
		if (isset($this->request->post['quickcheckout_delivery_date'])) {
			$data['quickcheckout_delivery_date'] = $this->request->post['quickcheckout_delivery_date'];
		} elseif (isset($setting['quickcheckout_delivery_date'])) {
			$data['quickcheckout_delivery_date'] = $setting['quickcheckout_delivery_date'];
		} else {
			$data['quickcheckout_delivery_date'] = 0;
		}
		
		if (isset($this->request->post['quickcheckout_delivery_time'])) {
			$data['quickcheckout_delivery_time'] = $this->request->post['quickcheckout_delivery_time'];
		} elseif (isset($setting['quickcheckout_delivery_time'])) {
			$data['quickcheckout_delivery_time'] = $setting['quickcheckout_delivery_time'];
		} else {
			$data['quickcheckout_delivery_time'] = 0;
		}
		
		if (isset($this->request->post['quickcheckout_delivery_required'])) {
			$data['quickcheckout_delivery_required'] = $this->request->post['quickcheckout_delivery_required'];
		} elseif (isset($setting['quickcheckout_delivery_required'])) {
			$data['quickcheckout_delivery_required'] = $setting['quickcheckout_delivery_required'];
		} else {
			$data['quickcheckout_delivery_required'] = 0;
		}
		
		if (isset($this->request->post['quickcheckout_delivery_unavailable'])) {
			$data['quickcheckout_delivery_unavailable'] = $this->request->post['quickcheckout_delivery_unavailable'];
		} elseif (isset($setting['quickcheckout_delivery_unavailable'])) {
			$data['quickcheckout_delivery_unavailable'] = $setting['quickcheckout_delivery_unavailable'];
		} else {
			$data['quickcheckout_delivery_unavailable'] = '"6-3-2013", "7-3-2013", "8-3-2013"';
		}
		
		if (isset($this->request->post['quickcheckout_delivery_min'])) {
			$data['quickcheckout_delivery_min'] = $this->request->post['quickcheckout_delivery_min'];
		} elseif (isset($setting['quickcheckout_delivery_min'])) {
			$data['quickcheckout_delivery_min'] = $setting['quickcheckout_delivery_min'];
		} else {
			$data['quickcheckout_delivery_min'] = 1;
		}
		
		if (isset($this->request->post['quickcheckout_delivery_max'])) {
			$data['quickcheckout_delivery_max'] = $this->request->post['quickcheckout_delivery_max'];
		} elseif (isset($setting['quickcheckout_delivery_max'])) {
			$data['quickcheckout_delivery_max'] = $setting['quickcheckout_delivery_max'];
		} else {
			$data['quickcheckout_delivery_max'] = 30;
		}
		
		if (isset($this->request->post['quickcheckout_delivery_min_hour'])) {
			$data['quickcheckout_delivery_min_hour'] = $this->request->post['quickcheckout_delivery_min_hour'];
		} elseif (isset($setting['quickcheckout_delivery_min_hour'])) {
			$data['quickcheckout_delivery_min_hour'] = $setting['quickcheckout_delivery_min_hour'];
		} else {
			$data['quickcheckout_delivery_min_hour'] = '09';
		}
		
		if (isset($this->request->post['quickcheckout_delivery_max_hour'])) {
			$data['quickcheckout_delivery_max_hour'] = $this->request->post['quickcheckout_delivery_max_hour'];
		} elseif (isset($setting['quickcheckout_delivery_max_hour'])) {
			$data['quickcheckout_delivery_max_hour'] = $setting['quickcheckout_delivery_max_hour'];
		} else {
			$data['quickcheckout_delivery_max_hour'] = '17';
		}
		
		if (isset($this->request->post['quickcheckout_delivery_days_of_week'])) {
			$data['quickcheckout_delivery_days_of_week'] = $this->request->post['quickcheckout_delivery_days_of_week'];
		} elseif (isset($setting['quickcheckout_delivery_days_of_week'])) {
			$data['quickcheckout_delivery_days_of_week'] = $setting['quickcheckout_delivery_days_of_week'];
		} else {
			$data['quickcheckout_delivery_days_of_week'] = '';
		}
		
		if (isset($this->request->post['quickcheckout_delivery_times'])) {
			$data['quickcheckout_delivery_times'] = $this->request->post['quickcheckout_delivery_times'];
		} elseif (isset($setting['quickcheckout_delivery_times'])) {
			$data['quickcheckout_delivery_times'] = $setting['quickcheckout_delivery_times'];
		} else {
			$data['quickcheckout_delivery_times'] = array();
		}
		
		// Countdown
		if (isset($this->request->post['quickcheckout_countdown'])) {
			$data['quickcheckout_countdown'] = $this->request->post['quickcheckout_countdown'];
		} elseif (isset($setting['quickcheckout_countdown'])) {
			$data['quickcheckout_countdown'] = $setting['quickcheckout_countdown'];
		} else {
			$data['quickcheckout_countdown'] = 0;
		}
		
		if (isset($this->request->post['quickcheckout_countdown_start'])) {
			$data['quickcheckout_countdown_start'] = $this->request->post['quickcheckout_countdown_start'];
		} elseif (isset($setting['quickcheckout_countdown_start'])) {
			$data['quickcheckout_countdown_start'] = $setting['quickcheckout_countdown_start'];
		} else {
			$data['quickcheckout_countdown_start'] = 0;
		}
		
		if (isset($this->request->post['quickcheckout_countdown_date_start'])) {
			$data['quickcheckout_countdown_date_start'] = $this->request->post['quickcheckout_countdown_date_start'];
		} elseif (isset($setting['quickcheckout_countdown_date_start'])) {
			$data['quickcheckout_countdown_date_start'] = $setting['quickcheckout_countdown_date_start'];
		} else {
			$data['quickcheckout_countdown_date_start'] = '';
		}
		
		if (isset($this->request->post['quickcheckout_countdown_date_end'])) {
			$data['quickcheckout_countdown_date_end'] = $this->request->post['quickcheckout_countdown_date_end'];
		} elseif (isset($setting['quickcheckout_countdown_date_end'])) {
			$data['quickcheckout_countdown_date_end'] = $setting['quickcheckout_countdown_date_end'];
		} else {
			$data['quickcheckout_countdown_date_end'] = '';
		}
		
		if (isset($this->request->post['quickcheckout_countdown_time'])) {
			$data['quickcheckout_countdown_time'] = $this->request->post['quickcheckout_countdown_time'];
		} elseif (isset($setting['quickcheckout_countdown_time'])) {
			$data['quickcheckout_countdown_time'] = $setting['quickcheckout_countdown_time'];
		} else {
			$data['quickcheckout_countdown_time'] = '00:00';
		}
		
		if (isset($this->request->post['quickcheckout_countdown_text'])) {
			$data['quickcheckout_countdown_text'] = $this->request->post['quickcheckout_countdown_text'];
		} elseif (isset($setting['quickcheckout_countdown_text'])) {
			$data['quickcheckout_countdown_text'] = $setting['quickcheckout_countdown_text'];
		} else {
			$data['quickcheckout_countdown_text'] = '';
		}
		
		// Stores
		$data['store_id'] = $store_id;
		
		$this->load->model('setting/store');
		
		$data['stores'] = $this->model_setting_store->getStores();
		
		// Languages
		$this->load->model('localisation/language');
		
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		// Countries
		$this->load->model('localisation/country');
		
		$data['countries'] = $this->model_localisation_country->getCountries();
		
		// Payment
		$files = glob(DIR_APPLICATION . 'controller/extension/payment/*.php');
		
		$data['payment_modules'] = array();
		
		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');

				if ($this->config->get($extension . '_status')) {
					$this->load->language('extension/payment/' . $extension);

					$data['payment_modules'][] = array(
						'name'		=> $this->language->get('heading_title'),
						'code'		=> $extension
					);
				}
			}
		}
		
		// Shipping
		$files = glob(DIR_APPLICATION . 'controller/extension/shipping/*.php');
		
		$data['shipping_modules'] = array();
		
		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');

				if ($this->config->get($extension . '_status')) {
					$this->load->language('extension/shipping/' . $extension);

					$data['shipping_modules'][] = array(
						'name'		=> $this->language->get('heading_title'),
						'code'		=> $extension
					);
				}
			}
		}
		
		// Analytics
		if (file_exists(DIR_APPLICATION . 'controller/extension/module/rac.php')) {
			$data['analytics'] = $this->url->link('extension/module/rac', 'token=' . $this->session->data['token'], true);
		} else {
			$data['analytics'] = false;
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->generateOutput('extension/module/quickcheckout', $data);
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

		$this->response->setOutput(json_encode($json));
	}
	
	public function install(){
		if (!$this->user->hasPermission('modify', 'extension/extension/module')) {
			return;
		}
		
		$this->load->language('module/quickcheckout');
		
		$this->load->model('setting/setting');
		
		$data = array(
			'quickcheckout_status'				=> '0',
			'quickcheckout_minimum_order'		=> '0',
			'quickcheckout_debug'				=> '0',
			'quickcheckout_confirmation_page'	=> '1',
			'quickcheckout_save_data'			=> '1',
			'quickcheckout_edit_cart'			=> '1',
			'quickcheckout_highlight_error'		=> '1',
			'quickcheckout_text_error'			=> '1',
			'quickcheckout_auto_submit'			=> '0',
			'quickcheckout_payment_target'		=> '#button-confirm, .button, .btn',
			'quickcheckout_load_screen'			=> '1',
			'quickcheckout_loading_display'		=> '1',
			'quickcheckout_layout'				=> '2',
			'quickcheckout_responsive'			=> '1',
			'quickcheckout_slide_effect'		=> '0',
			'quickcheckout_field_firstname'		=> array(
					'display'		=> '1',
					'required'		=> '1',
					'default'		=> '',
					'sort_order'	=> '1'
				),
			'quickcheckout_field_lastname'		=> array(
					'display'		=> '1',
					'required'		=> '1',
					'default'		=> '',
					'sort_order'	=> '2'
				),
			'quickcheckout_field_email'			=> array(
					'display'		=> '1',
					'required'		=> '1',
					'default'		=> '',
					'sort_order'	=> '3'
				),
			'quickcheckout_field_telephone'		=> array(
					'display'		=> '1',
					'required'		=> '1',
					'default'		=> '',
					'sort_order'	=> '4'
				),
			'quickcheckout_field_fax'			=> array(
					'display'		=> '0',
					'required'		=> '0',
					'default'		=> '',
					'sort_order'	=> '5'
				),
			'quickcheckout_field_company'		=> array(
					'display'		=> '1',
					'required'		=> '0',
					'default'		=> '',
					'sort_order'	=> '6'
				),
			'quickcheckout_field_customer_group' => array(
					'display'		=> '1',
					'required'		=> '',
					'default'		=> '',
					'sort_order'	=> '7'
				),
			'quickcheckout_field_address_1'		=> array(
					'display'		=> '1',
					'required'		=> '1',
					'default'		=> '',
					'sort_order'	=> '8'
				),
			'quickcheckout_field_address_2'		=> array(
					'display'		=> '0',
					'required'		=> '0',
					'default'		=> '',
					'sort_order'	=> '9'
				),
			'quickcheckout_field_city'			=> array(
					'display'		=> '1',
					'required'		=> '1',
					'default'		=> '',
					'sort_order'	=> '10'
				),
			'quickcheckout_field_postcode'		=> array(
					'display'		=> '1',
					'required'		=> '0',
					'default'		=> '',
					'sort_order'	=> '11'
				),
			'quickcheckout_field_country'		=> array(
					'display'		=> '1',
					'required'		=> '1',
					'default'		=> $this->config->get('config_country_id'),
					'sort_order'	=> '12'
				),
			'quickcheckout_field_zone'			=> array(
					'display'		=> '1',
					'required'		=> '1',
					'default'		=> $this->config->get('config_zone_id'),
					'sort_order'	=> '13'
				),
			'quickcheckout_field_newsletter'	=> array(
					'display'		=> '1',
					'required'		=> '0',
					'default'		=> '1',
					'sort_order'	=> ''
				),
			'quickcheckout_field_register'		=> array(
					'display'		=> '1',
					'required'		=> '0',
					'default'		=> '',
					'sort_order'	=> ''
				),
			'quickcheckout_field_comment'		=> array(
					'display'		=> '1',
					'required'		=> '0',
					'default'		=> '',
					'sort_order'	=> ''
				),
			'quickcheckout_coupon'				=> '1',
			'quickcheckout_voucher'				=> '1',
			'quickcheckout_reward'				=> '1',
			'quickcheckout_cart'				=> '1',
			'quickcheckout_login_module'		=> '1',
			'quickcheckout_html_header'			=> array(),
			'quickcheckout_html_footer'			=> array(),
			'quickcheckout_payment_module'		=> '1',
			'quickcheckout_payment_reload'		=> '0',
			'quickcheckout_payment'				=> '1',
			'quickcheckout_payment_logo'		=> array(),
			'quickcheckout_shipping_module'		=> '1',
			'quickcheckout_shipping'			=> '1',
			'quickcheckout_shipping_reload'		=> '0',
			'quickcheckout_shipping_logo'		=> array(),
			'quickcheckout_survey'				=> '0',
			'quickcheckout_survey_required'		=> '0',
			'quickcheckout_survey_text'			=> array(),
			'quickcheckout_delivery'			=> '0',
			'quickcheckout_delivery_time'		=> '0',
			'quickcheckout_delivery_required'	=> '0',
			'quickcheckout_delivery_unavailable'=> '"2013-10-31", "2013-08-11", "2013-12-25"',
			'quickcheckout_delivery_min'		=> '1',
			'quickcheckout_delivery_max'		=> '30',
			'quickcheckout_delivery_days_of_week'	=> ''
		);
		
		$this->model_setting_setting->editSetting('quickcheckout', $data);
		
		$this->load->model('setting/store');
		
		$stores = $this->model_setting_store->getStores();
		
		foreach ($stores as $store) {
			$this->model_setting_setting->editSetting('quickcheckout', $data, $store['store_id']);
		}
		
		// Layout
		if (!$this->getLayout()) {
			$this->load->model('design/layout');
			
			$layout_data = array(
				'name'			=> 'Quick Checkout',
				'layout_route'	=> array(
					array(
						'store_id'	=> 0,
						'route'		=> 'quickcheckout/checkout'
					)
				)
			);
			
			$this->model_design_layout->addLayout($layout_data);
		}
	}
	
	public function uninstall() {
		if (!$this->user->hasPermission('modify', 'extension/extension/module')) {
			return;
		}
		
		if ($this->getLayout()) {
			$this->load->model('design/layout');
			
			$this->model_design_layout->deleteLayout($this->getLayout());
		}
	}
	
	private function getLayout() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_route WHERE route = 'quickcheckout/checkout'");
		
		if ($query->num_rows) {
			return $query->row['layout_id'];
		}
		
		return false;
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/' . $this->code) || !$this->validated()) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		return !$this->error;
	}
}
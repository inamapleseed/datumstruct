<?php
class ControllerAccountRegister extends Controller {
	private $error = array();	

	private function addCustomFields($data){
		$this->load->model('account/custom_field');

		$group_id = $this->customer->getGroupId();

		$custom_fields = $this->model_account_custom_field->getCustomFields($group_id);

		foreach($custom_fields as $custom){
			if( $custom['location'] == 'account' ){
				// Left
				$this->insertField($custom, $data['form_left']);
			}
			else{
				// Right
				$this->insertField($custom, $data['form_right']);
			}
		}

		//debug($data['form_left']);
		return $data;
	}

	private function insertField($custom, &$form){

		$list = array();
		
		foreach($custom['custom_field_value'] as $value){
			$list[] = array(
				'label'	=>	$value['name'],
				'value'	=>	$value['custom_field_value_id'],
			);
		}

		$class = $custom['type'];

		$type = 'text';
		if($custom['type'] == 'select' || $custom['type'] == 'file'){
			$type = $custom['type'];
		}

		$case = array(
				'type' 			=> $type,
				'extra_class'	=> $class,
				'list'			=> $list
		);

		$location = $custom['location'];
		$custom_field_id = $custom['custom_field_id'];
		$name = 'custom_field[' . $location . '][' . $custom_field_id . ']';

		$value = $custom['value'];
		if(isset($this->request->post[$name])){
			$value = $this->request->post[$name];
		}

		$error = '';

		if(isset($this->error['custom_field'][$custom_field_id])){
			$error  = $this->error['custom_field'][$custom_field_id];
		}

		$field = array(
			'name'	=> $name,
			'label'	=> $custom['name'],
			'value' => $value,
			'error'	=> $error,
			'case'	=> $case
		);

		$field = json_encode($field);

		// First parameter is pass by reference
		array_splice($form, $custom['sort_order'], 0, $field);
	}

	public function index() {

		if ($this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/account', '', true));
		}

		$data = array();
		$data = array_merge($data, $this->load->language('account/register'));
		$this->load->language('account/register');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		$this->load->model('account/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$customer_id = $this->model_account_customer->addCustomer($this->request->post);

			// Clear any previous login attempts for unregistered accounts.
			$this->model_account_customer->deleteLoginAttempts($this->request->post['email']);

			$this->customer->login($this->request->post['email'], $this->request->post['password']);

			unset($this->session->data['guest']);

			// Add to activity log
			if ($this->config->get('config_customer_activity')) {
				$this->load->model('account/activity');

				$activity_data = array(
					'customer_id' => $customer_id,
					'name'        => $this->request->post['firstname'] . ' ' . $this->request->post['lastname']
				);

				$this->model_account_activity->addActivity('register', $activity_data);
			}

			$this->response->redirect($this->url->link('account/success'));
		}

		// debug($this->error);

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_register'),
			'href' => $this->url->link('account/register', '', true)
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->url->link('account/login', '', true));
		
		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_upload'] = $this->language->get('button_upload');
		$data['button_proceed'] = $this->language->get('button_proceed');

		$data['error_warning'] = '';
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		}

		$data['action'] = $this->url->link('account/register', '', true);

		$data['customer_groups'] = array();

		if (is_array($this->config->get('config_customer_group_display'))) {
			$this->load->model('account/customer_group');

			$customer_groups = $this->model_account_customer_group->getCustomerGroups();

			foreach ($customer_groups as $customer_group) {
				if (in_array($customer_group['customer_group_id'], $this->config->get('config_customer_group_display'))) {
					$data['customer_groups'][] = $customer_group;
				}
			}
		}

		if (isset($this->request->post['customer_group_id'])) {
			$data['customer_group_id'] = $this->request->post['customer_group_id'];
		} else {
			$data['customer_group_id'] = $this->config->get('config_customer_group_id');
		}

		// Var => Required
		// Account
		$left = array(
			'firstname'		=> true,
			'lastname'		=> true,
			'email'			=> true,
			'telephone'		=> true,
			'fax'			=> false,
			'password'		=> true,
			'confirm'		=> true,
		);

		// Var => Required
		// Address
		$right = array(			
			'postcode'		=> true,
			'address_1'		=> true,
			'address_2'		=> false,
			'unit_no'		=> false,
			'city'			=> false,
			'country_id'	=> true,
			'zone_id'		=> true,
			'company'		=> false,
		);

		$default_values = array(
			'country_id'	=>	$this->config->get('config_country_id'),
		);

		$this->load->model('localisation/country');

		$countries = $this->model_localisation_country->getCountries();

		// Case X Then Y
		/*
		X = variable name
		Y = array(
			'type' 			=>	'input|password|select|number'	// For printing proper input type
			'extra_class'	=>	'class1 class2 class3',			// For Javascript handling
			'list'			=>	array() 						// For select type only
		);
		*/

		foreach($countries as $index => $country){
			
			$label = $country['name'];
			$value = $country['country_id'];

			unset($countries[$index]);

			$countries[$index] = array(
				'label'	=>	$label,
				'value'	=>	$value
			);

		}

		$cases = array(			
			'company'		=>	array('type' => 'text',			'parent_class' => 'hidden', 	'extra_class' => 'hidden',			'list' => array()),
			'email'			=>	array('type' => 'email',		'parent_class' => '', 			'extra_class' => '',				'list' => array()),
			'telephone'		=>	array('type' => 'text',			'parent_class' => '', 			'extra_class' => 'input-number',	'list' => array()),		
			'password'		=>	array('type' => 'password', 	'parent_class' => '', 			'extra_class' => '', 				'list' => array()),
			'confirm'		=>	array('type' => 'password', 	'parent_class' => '', 			'extra_class' => '', 				'list' => array()),								
			'country_id'	=>	array('type' => 'select',		'parent_class' => '', 			'extra_class' => '', 				'list' => $countries),
			'zone_id'		=>	array('type' => 'select',		'parent_class' => '', 			'extra_class' => '', 				'list' => array()),
		);

		$data['form_left'] = array();
		$data['form_right'] = array();

		foreach($left as $var => $required){
			$form_left = array(
				'name'	=>	$var,
				'label'	=>	($required?'*':'') . $this->language->get('entry_' . $var),
				'value' =>	isset($this->request->post[$var])?$this->request->post[$var]:(isset($default_values[$var])?$default_values[$var]:''),
				'error'	=>	isset($this->error[$var])?$this->error[$var]:'',
				'case'	=>	isset($cases[$var])?$cases[$var]:array('type' => 'text', 'parent_class' => '', 'extra_class' => '', 'list' => array()),
			);

			$data['form_left'][] = json_encode($form_left);
		}

		foreach($right as $var => $required){
			$form_right = array(
				'name'	=>	$var,
				'label'	=>	($required?'*':'') . $this->language->get('entry_' . $var),
				'value' =>	isset($this->request->post[$var])?$this->request->post[$var]:(isset($default_values[$var])?$default_values[$var]:''),
				'error'	=>	isset($this->error[$var])?$this->error[$var]:'',
				'case'	=>	isset($cases[$var])?$cases[$var]:array('type' => 'text', 'parent_class' => '', 'extra_class' => '', 'list' => array()),
			);

			$data['form_right'][] = json_encode($form_right);
		}

		$data['zone_id'] = '';

		$data = $this->addCustomFields($data);

		// Undo json_encode

		foreach($data['form_left'] as &$json_left){
			$json_left = json_decode($json_left, true);
		}

		foreach($data['form_right'] as &$json_right){
			$json_right = json_decode($json_right, true);
		}

		$data['newsletter'] = '';
		if (isset($this->request->post['newsletter'])) {
			$data['newsletter'] = $this->request->post['newsletter'];
		}

		// Captcha
		$data['captcha'] = '';
		if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('register', (array)$this->config->get('config_captcha_page'))) {
			$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'), $this->error);
		} 

		$data['text_agree'] = '';
		if ($this->config->get('config_account_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

			$data['text_agree'] = '';
			if ($information_info) {
				$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_account_id'), true), $information_info['title'], $information_info['title']);
			}
		}

		$data['agree'] = false;
		if (isset($this->request->post['agree'])) {
			$data['agree'] = $this->request->post['agree'];
		}

		$data = $this->load->controller('common/common', $data);

		$this->response->setOutput($this->load->view('account/register', $data));
	}

	private function validate() {

		if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32))
			$this->error['firstname'] = $this->language->get('error_firstname');

		if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) 
			$this->error['lastname'] = $this->language->get('error_lastname');

		if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) 
			$this->error['email'] = $this->language->get('error_email');
	
		if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) 
			$this->error['warning'] = $this->language->get('error_exists');
		
		if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) 
			$this->error['telephone'] = $this->language->get('error_telephone');
		
		if ((utf8_strlen(trim($this->request->post['address_1'])) < 3) || (utf8_strlen(trim($this->request->post['address_1'])) > 128)) 
			$this->error['address_1'] = $this->language->get('error_address_1');

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

		if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) 
			$this->error['postcode'] = $this->language->get('error_postcode');
		
		if ($this->request->post['country_id'] == '') 
			$this->error['country_id'] = $this->language->get('error_country');
		
		if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '' || !is_numeric($this->request->post['zone_id']))
			$this->error['zone_id'] = $this->language->get('error_zone');
		
		// Customer Group
		$customer_group_id = $this->config->get('config_customer_group_id');
		if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $this->request->post['customer_group_id'];
		}

		// Custom field validation
		$this->load->model('account/custom_field');

		$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

		foreach ($custom_fields as $custom_field) {
			$location = $custom_field['location'];
			$custom_field_id = $custom_field['custom_field_id'];
            if (
				$custom_field['required'] && 
				empty($this->request->post['custom_field'][$location][$custom_field_id])
			) {
				$this->error['custom_field'][$custom_field_id] = 
				sprintf($this->language->get('error_custom_field'), $custom_field['name']);
			} 
			elseif (
				($custom_field['type'] == 'text') && 
				!empty($custom_field['validation']) && 
				!filter_var($this->request->post['custom_field'][$location][$custom_field_id], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))
			) {
				$this->error['custom_field'][$custom_field_id] = 
				sprintf($this->language->get('error_custom_field'), $custom_field['name']);
			}
		}


		if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) 
			$this->error['password'] = $this->language->get('error_password');
		
		if ($this->request->post['confirm'] != $this->request->post['password'])
			$this->error['confirm'] = $this->language->get('error_confirm');
		
		if(ADVANCE_PASSWORD){
			$password_check = $this->validatePassword();
			if(!$password_check['result']) {
				$this->error['password'] = implode('<br/>', $password_check['error']);
				unset($this->error['error']['confirm']);
			}
		}
		
		// Captcha
		if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('register', (array)$this->config->get('config_captcha_page'))) {
			$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

			if ($captcha) {
				$this->error['captcha'] = $captcha;
			}
		}

		// Agree to terms
		if ($this->config->get('config_account_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

			if ($information_info && !isset($this->request->post['agree'])) {
				$this->error['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
			}
		}

		return !$this->error;
	}

	protected function validatePassword() {
		//Options (Set any to 0 to disable checks).
		$min_length = 8; //Minimum password length.
		$max_length = 20;  //Maximum password length.
		$n_numbers = 1;  //Minimum number of numbers (0-9).
		$n_characters = 1; //Minimum number of special characters (/\$%£! .etc).
		$n_lower = 1; //Minimum number of lowercase letters.
		$n_upper = 1; //Minimum number of uppercase letters.

		$error_message = array();
		$error = false;
		$password = $this->request->post['password'];
		if($min_length) {
			$error_message[] = $min_length .' to '. $max_length .' characters.';
			if(utf8_strlen($password) < $min_length || utf8_strlen($password) > $max_length) {
				$error = true;
			}
		}
		if($n_lower) {
			$error_message[] = $n_lower .' or more lowercase letters.';
			if(preg_match_all( "/[a-z]/", $password ) < $n_lower) {
				$error = true;
			}
		}
		if($n_upper) {
			$error_message[] = $n_upper .' or more uppercase letters.';
			if(preg_match_all( "/[A-Z]/", $password ) < $n_upper) {
				$error = true;
			}
		}
		if($n_numbers) {
			$error_message[] = $n_numbers .' or more numbers.';
			if(preg_match_all( "/[0-9]/", $password ) < $n_numbers) {
				$error = true;
			}
		}
		if($n_characters) {
			$error_message[] = $n_characters .' or more special characters (e.g. !?.,#$&%).';
			if(preg_match_all( "/(?=\D)(?=\W)(?=\S)./", $password ) < $n_characters) {
				$error = true;
			}
		}
		if($error) {
			return array('result'=>false, 'error'=>$error_message);
		} else {
			return array('result'=>true);
		}
	}
}
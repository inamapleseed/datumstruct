<?php
class ControllerExtensionModuleDiscounts extends Controller {
	private $error = array();
	//private $discount_modules = array('category', 'customer_group', 'manufacturer', 'ordertotal', 'volume', 'loyalty');
	private $discount_modules = array('category', 'customer_group', 'manufacturer');
	
	public function index() {
		$this->load->language('extension/module/discounts');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$settings = array('status', 'sort_order', 'override_special_price','override_discount_price');
		
		$this->load->model('setting/setting');
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		
		$data['discount_modules'] = $this->discount_modules;
		
		foreach ($this->discount_modules as $i) {
			$key = 'text_' . $i;
			$data[$key] = $this->language->get($key);
		}
		
		$data['text_category'] = $this->language->get('text_category');
		$data['text_upgrade'] = $this->language->get('text_upgrade');
		$data['text_edit_discounts'] = $this->language->get('text_edit_discounts');
		
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$data['entry_include_specials'] = $this->language->get('entry_include_specials');
		
		$data['entry_override_special_price'] = $this->language->get('entry_override_special_price');
		$data['entry_override_discount_price'] = $this->language->get('entry_override_discount_price');
	
		$data['help_override_special_price'] = $this->language->get('help_override_special_price');
		$data['help_override_discount_price'] = $this->language->get('help_override_discount_price');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_save_stay'] = $this->language->get('button_save_stay');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		$data['tab_settings'] = $this->language->get('tab_settings');
		$data['tab_about'] = $this->language->get('tab_about');
		
		//$data['options_discount'] = array('default', 'override');
		$data['options_discount'] = array('default' => 'Yes', 'override' => 'No');
		//$data['options_special'] = array('default', 'exclusive', 'override');
		$data['options_special'] = array('default' => 'Yes', 'override' => 'No');
		
		$data['permission'] = $this->user->hasPermission('modify', 'extension/module/discounts');
		$data['token'] = $this->session->data['token'];
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		$data['breadcrumbs'] = array();
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/discounts', 'token=' . $this->session->data['token'], true)
		);
		
		$data['action'] = $this->url->link('extension/module/discounts', 'token=' . $this->session->data['token'], true);
		
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

		
		if (isset($this->request->post['discounts_status'])) {
			$data['discounts_status'] = $this->request->post['discounts_status'];
		} else {
			$data['discounts_status'] = $this->config->get('discounts_status');
		}
		
		if (isset($this->request->post['discounts_include_specials'])) {
			$data['discounts_include_specials'] = $this->request->post['discounts_include_specials'];
		} else {
			$data['discounts_include_specials'] = $this->config->get('discounts_include_specials');
		}
		
		if (isset($this->request->post['discounts_override_special_price'])) {
			$data['discounts_override_special_price'] = $this->request->post['discounts_override_special_price'];
		} else {
			$data['discounts_override_special_price'] = $this->config->get('discounts_override_special_price');
		}
		
		if (isset($this->request->post['discounts_override_discount_price'])) {
			$data['discounts_override_discount_price'] = $this->request->post['discounts_override_discount_price'];
		} else {
			$data['discounts_override_discount_price'] = $this->config->get('discounts_override_discount_price');
		}
		
		$data['settings'] = $settings;
		foreach ($this->discount_modules as $i) {
			foreach ($settings as $j) {
				$key = $i . '_discount_' . $j;
				
				if (isset($this->request->post[$key])) {
					$data[$key] = $this->request->post[$key];
				} else {
					$data[$key] = $this->config->get($key);
				}
			}
		}
		
		$data['link'] = $this->url->link('catalog/discount_%s', 'token=' . $this->session->data['token'], true);
		$data['upgrade'] = $this->checkTables();
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/module/discounts.tpl', $data));
	}
	
	public function saveSettings() {
		
		$json = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->load->model('setting/setting');
			$this->load->language('extension/module/discounts');
			
			parse_str(htmlspecialchars_decode($this->request->post['settings']), $settings);
			$this->model_setting_setting->editSetting('discounts', $settings);
			
			$discount_modules = $this->discount_modules;
			
			foreach ($discount_modules as $i) {
				
				parse_str(htmlspecialchars_decode($this->request->post[$i]), $settings_data);
				$code = $i . '_discount';
				
				$this->model_setting_setting->editSetting($code, $settings_data);
			}
				
			$json['success'] = $this->language->get('text_success');
			//$json['success'] = $settings;

		} else {
			$json['error'] = $this->language->get('error_warning');
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
		
	}
	
	public function upgrade() {
		
		$json = array();
		
		$this->load->model('catalog/discount');
		// DB changes upgrade v1.0 to v1.1
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "category_discount` ADD `qty` INT  NOT NULL  DEFAULT '0' AFTER `date_end`, ADD `status` INT  NOT NULL DEFAULT '1' AFTER `date_end` ;");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "manufacturer_discount` ADD `qty` INT  NOT NULL  DEFAULT '0' AFTER `date_end`, ADD `status` INT  NOT NULL DEFAULT '1'  AFTER `date_end`;");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer_group_discount` ADD `status` INT  NOT NULL DEFAULT '1' AFTER `date_end`;");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "ordertotal_discount` ADD `status` INT  NOT NULL DEFAULT '1' AFTER `date_end`;");
		
		$json['success'] = 'Database tables upgrade successful!';
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function checkTables() {
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "category_discount` LIKE 'qty';");		
		return empty($query->num_rows) ? true : false ;
	}
	
	public function install() {
		
		// Enable Discounts
		$this->db->query("INSERT INTO `". DB_PREFIX ."setting` (`setting_id`, `store_id`, `code`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'discounts', 'discounts_status', '1', '0'); ");
		//$this->db->query("INSERT INTO `". DB_PREFIX ."setting` (`setting_id`, `store_id`, `code`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'discounts', 'discounts_override_discount_price', 'override', '0'); ");
		//$this->db->query("INSERT INTO `". DB_PREFIX ."setting` (`setting_id`, `store_id`, `code`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'discounts', 'discounts_override_special_price', 'exclusive', '0'); ");
		$this->db->query("INSERT INTO `". DB_PREFIX ."setting` (`setting_id`, `store_id`, `code`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'discounts', 'discounts_override_discount_price', 'default', '0'); ");
		$this->db->query("INSERT INTO `". DB_PREFIX ."setting` (`setting_id`, `store_id`, `code`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'discounts', 'discounts_override_special_price', 'default', '0'); ");

		// Category Discount  
		$sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX ."category_discount` (`category_discount_id` int(11) NOT NULL AUTO_INCREMENT, `category_id` int(11) NOT NULL, `customer_group_id` int(11) NOT NULL, `priority` int(5) NOT NULL DEFAULT '1', ";
  		$sql .= "`percentage` decimal(15,4) NOT NULL DEFAULT '0.0000', `affect` int(1) NOT NULL DEFAULT '0', `qty` int(1) NOT NULL DEFAULT '0',`status` INT  NOT NULL DEFAULT '1', `date_start` date NOT NULL DEFAULT '0000-00-00', `date_end` date NOT NULL DEFAULT '0000-00-00', PRIMARY KEY (`category_discount_id`), KEY `category_id` (`category_id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		
		$this->db->query($sql);
		
		$this->db->query("INSERT INTO `". DB_PREFIX ."extension` (`extension_id`, `type`, `code`) VALUES (NULL, 'total', 'category_discount'); ");
		$this->db->query("INSERT INTO `". DB_PREFIX ."setting` (`setting_id`, `store_id`, `code`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'category_discount', 'category_discount_sort_order', '2', '0'); ");
		$this->db->query("INSERT INTO `". DB_PREFIX ."setting` (`setting_id`, `store_id`, `code`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'category_discount', 'category_discount_status', '1', '0');");
		
		// Customer Group Discount  
		$sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX ."customer_group_discount` (`customer_group_discount_id` int(11) NOT NULL AUTO_INCREMENT, `customer_group_id` int(11) NOT NULL, `priority` int(5) NOT NULL DEFAULT '1', `type`char(1) NOT NULL, ";
		$sql .= "`percentage` decimal(15,4) NOT NULL DEFAULT '0.0000',`status` INT  NOT NULL DEFAULT '1', `date_start` date NOT NULL DEFAULT '0000-00-00', `date_end` date NOT NULL DEFAULT '0000-00-00', PRIMARY KEY (`customer_group_discount_id`), KEY `customer_group_id` (`customer_group_id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		
		$this->db->query($sql);
		
		$this->db->query("INSERT INTO `". DB_PREFIX ."extension` (`extension_id`, `type`, `code`) VALUES (NULL, 'total', 'customer_group_discount'); ");
		$this->db->query("INSERT INTO `". DB_PREFIX ."setting` (`setting_id`, `store_id`, `code`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'customer_group_discount', 'customer_group_discount_sort_order', '3', '0'); ");
		$this->db->query("INSERT INTO `". DB_PREFIX ."setting` (`setting_id`, `store_id`, `code`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'customer_group_discount', 'customer_group_discount_status', '1', '0');");
		
		// Manufacturer Discount
		$sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX ."manufacturer_discount` (`manufacturer_discount_id` int(11) NOT NULL AUTO_INCREMENT, `manufacturer_id` int(11) NOT NULL, `customer_group_id` int(11) NOT NULL, `priority` int(5) NOT NULL DEFAULT '1', ";
  		$sql .= "`percentage` decimal(15,4) NOT NULL DEFAULT '0.0000', `qty` int(1) NOT NULL DEFAULT '0',`status` INT  NOT NULL DEFAULT '1', `date_start` date NOT NULL DEFAULT '0000-00-00', `date_end` date NOT NULL DEFAULT '0000-00-00', PRIMARY KEY (`manufacturer_discount_id`), KEY `manufacturer_id` (`manufacturer_id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		
		$this->db->query($sql);
		
		$this->db->query("INSERT INTO `". DB_PREFIX ."extension` (`extension_id`, `type`, `code`) VALUES (NULL, 'total', 'manufacturer_discount'); ");
		$this->db->query("INSERT INTO `". DB_PREFIX ."setting` (`setting_id`, `store_id`, `code`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'manufacturer_discount', 'manufacturer_discount_sort_order', '4', '0'); ");
		$this->db->query("INSERT INTO `". DB_PREFIX ."setting` (`setting_id`, `store_id`, `code`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'manufacturer_discount', 'manufacturer_discount_status', '1', '0');");
		
		// Ordertotal Discount
		
		// $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX ."ordertotal_discount` (`ordertotal_discount_id` int(11) NOT NULL AUTO_INCREMENT, `ordertotal` int(11) NOT NULL, `customer_group_id` int(11) NOT NULL, `priority` int(5) NOT NULL DEFAULT '1', `type`char(1) NOT NULL, ";
  		// $sql .= "`percentage` decimal(15,4) NOT NULL DEFAULT '0.0000',`status` INT  NOT NULL DEFAULT '1', `date_start` date NOT NULL DEFAULT '0000-00-00', `date_end` date NOT NULL DEFAULT '0000-00-00', PRIMARY KEY (`ordertotal_discount_id`), KEY `ordertotal` (`ordertotal`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		
		// $this->db->query($sql);
		
		// $this->db->query("INSERT INTO `". DB_PREFIX ."extension` (`extension_id`, `type`, `code`) VALUES (NULL, 'total', 'ordertotal_discount'); ");
		// $this->db->query("INSERT INTO `". DB_PREFIX ."setting` (`setting_id`, `store_id`, `code`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'ordertotal_discount', 'ordertotal_discount_sort_order', '5', '0'); ");
		// $this->db->query("INSERT INTO `". DB_PREFIX ."setting` (`setting_id`, `store_id`, `code`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'ordertotal_discount', 'ordertotal_discount_status', '1', '0');");
		
		// Volume Discount  
		// $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX ."volume_discount` (`volume_discount_id` int(11) NOT NULL AUTO_INCREMENT, `customer_group_id` int(11) NOT NULL, `priority` int(5) NOT NULL DEFAULT '1', ";
  		// $sql .= "`percentage` decimal(15,4) NOT NULL DEFAULT '0.0000', `qty` int(1) NOT NULL DEFAULT '0',`status` INT  NOT NULL DEFAULT '1', `date_start` date NOT NULL DEFAULT '0000-00-00', `date_end` date NOT NULL DEFAULT '0000-00-00', PRIMARY KEY (`volume_discount_id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		
		// $this->db->query($sql);
		
		// $this->db->query("INSERT INTO `". DB_PREFIX ."extension` (`extension_id`, `type`, `code`) VALUES (NULL, 'total', 'volume_discount'); ");
		// $this->db->query("INSERT INTO `". DB_PREFIX ."setting` (`setting_id`, `store_id`, `code`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'volume_discount', 'volume_discount_sort_order', '2', '0'); ");
		// $this->db->query("INSERT INTO `". DB_PREFIX ."setting` (`setting_id`, `store_id`, `code`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'volume_discount', 'volume_discount_status', '1', '0');");
		
		// Loyalty Discount
		
		// $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX ."loyalty_discount` (`loyalty_discount_id` int(11) NOT NULL AUTO_INCREMENT, `ordertotal` int(11) NOT NULL, `customer_group_id` int(11) NOT NULL, `priority` int(5) NOT NULL DEFAULT '1', `order_status` VARCHAR(96) NOT NULL, ";
  		// $sql .= "`percentage` decimal(15,4) NOT NULL DEFAULT '0.0000',`status` INT  NOT NULL DEFAULT '1', `date_start` date NOT NULL DEFAULT '0000-00-00', `date_end` date NOT NULL DEFAULT '0000-00-00', PRIMARY KEY (`loyalty_discount_id`), KEY `ordertotal` (`ordertotal`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		
		// $this->db->query($sql);
		
		// $this->db->query("INSERT INTO `". DB_PREFIX ."extension` (`extension_id`, `type`, `code`) VALUES (NULL, 'total', 'loyalty_discount'); ");
		// $this->db->query("INSERT INTO `". DB_PREFIX ."setting` (`setting_id`, `store_id`, `code`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'loyalty_discount', 'loyalty_discount_sort_order', '5', '0'); ");
		// $this->db->query("INSERT INTO `". DB_PREFIX ."setting` (`setting_id`, `store_id`, `code`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'loyalty_discount', 'loyalty_discount_status', '1', '0');");
			
	}
	
	public function uninstall() {
		
		$tables = $this->discount_modules;
		foreach ($tables as $key) {
			$this->db->query("DROP TABLE `" . DB_PREFIX . $key . "_discount`");
			$this->db->query("DELETE FROM `". DB_PREFIX ."extension` WHERE `code` = '" . $key . "_discount';");
			$this->db->query("DELETE FROM `". DB_PREFIX ."setting` WHERE `code` = '" . $key . "_discount';");
		}
		$this->db->query("DELETE FROM `". DB_PREFIX ."setting` WHERE `code` = 'discounts';");
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/discounts')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		return !$this->error;
	}
}
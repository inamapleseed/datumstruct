<?php
	class ControllerCommonFooter extends Controller {
		public function index() {
			

			$this->load->language('common/footer');
			
			/* AbandonedCarts - Begin */
			$this->load->model('setting/setting');
			
			$abandonedCartsSettings = $this->model_setting_setting->getSetting('abandonedcarts', $this->config->get('store_id'));
			
			if (isset($abandonedCartsSettings['abandonedcarts']['Enabled']) && $abandonedCartsSettings['abandonedcarts']['Enabled']=='yes') { 
				$this->register_abandonedCarts();
			}
			/* AbandonedCarts - End */
			
			// Enhanced CKEditor
			if (!file_exists(DIR_CATALOG.'../vqmod/xml/enhanced_file_manager.xml') || file_exists(DIR_CATALOG.'../vqmod/xml/enhanced_file_manager.xml_')) {
				$data['fm_installed'] = 0;
			}
			
			if (file_exists(DIR_CATALOG.'../vqmod/xml/enhanced_file_manager.xml') && $this->config->get('fm_installed') == 1) {
				$data['fm_installed'] = 1;
			}
			
			if ($this->config->get('ea_cke_enable_ckeditor') == 1) {
				$data['ckeditor_enabled'] = 1;
				} else {
				$data['ckeditor_enabled'] = 0;
			}
			
			if ($this->config->get('ea_cke_ckeditor_skin')) {
				$data['ckeditor_skin'] = $this->config->get('ea_cke_ckeditor_skin');
				} else {
				$data['ckeditor_skin'] = 'moono-lisa';
			}
			
			if ($this->config->get('ea_cke_codemirror_skin')) {
				$data['codemirror_skin'] = $this->config->get('ea_cke_codemirror_skin');
				} else {
				$data['codemirror_skin'] = 'eclipse';
			}
			// Enhanced CKEditor
			
			$data['pim_status'] = $this->config->get('pim_status');
			$data['text_footer'] = sprintf($this->language->get('text_footer'), $this->config->get('config_name'));
			$data['lang'] = 'en';
			$data['width'] = $this->config->get('pim_width');
			$data['height'] = $this->config->get('pim_height');
			
			if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
				$data['text_version'] = sprintf($this->language->get('text_version'), VERSION);
				} else {
				$data['text_version'] = '';
			}
			
			$data['ajax_save'] = false;
			if($this->user->isLogged()){
				$data['ajax_save'] = true;
			}
			
			if(!$this->user->isLogged()){
				$data['ckeditor_enabled'] = false;
				$data['ajax_save'] = false;
				$data['pim_status'] = false;
			}
			
			return $this->load->view('common/footer', $data);
		}
		
		/* AbandonedCarts - Begin */
		protected function register_abandonedCarts() {
			
			$ip = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '*HiddenIP*';
			if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
				$ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
			}
			
			if (isset($this->session->data['abandonedCart_ID']) & !empty($this->session->data['abandonedCart_ID'])) {
				$id = $this->session->data['abandonedCart_ID'];
				} else if ($this->customer->isLogged()) {
				$id = (!empty($this->session->data['abandonedCart_ID'])) ? $this->session->data['abandonedCart_ID'] : $this->customer->getEmail();
				} else {
				$id = (!empty($this->session->data['abandonedCart_ID'])) ? $this->session->data['abandonedCart_ID'] : session_id();
			}
			
			$exists = $this->db->query("SELECT * FROM `" . DB_PREFIX . "abandonedcarts` WHERE `restore_id` = '$id' AND `ordered`=0");
			$cart = $this->cart->getProducts();
			$store_id = (int)$this->config->get('config_store_id');
			$cart = (!empty($cart)) ? $cart : '';
			
			$lastpage = "$_SERVER[REQUEST_URI]";
			
			$checker = $this->customer->getId();
			if (!empty($checker)) {
				$customer = array(
				'id'        => $this->customer->getId(), 
				'email'     => $this->customer->getEmail(),		
				'telephone' => $this->customer->getTelephone(),
				'firstname' => $this->customer->getFirstName(),
				'lastname'  => $this->customer->getLastName(),
				'language'  => $this->session->data['language']
				);
			} 
			
			$route = isset($this->request->get['route']) ? $this->request->get['route'] : '';
			if ($route!='checkout/success') {
				if (empty($exists->row)) {
					if (!empty($cart)) {
						if (!isset($customer)) {
							$customer = array(
							'language' => $this->session->data['language']
							);
						}
						$cart = json_encode($cart);
						$customer = (!empty($customer)) ? json_encode($customer) : '';
						$this->db->query("INSERT INTO `" . DB_PREFIX . "abandonedcarts` SET `cart`='".$this->db->escape($cart)."', `customer_info`='".$this->db->escape($customer)."', `last_page`='$lastpage', `ip`='$ip', `date_created`=NOW(), `date_modified`=NOW(), `restore_id`='".$id."', `store_id`='".$store_id."'");
						$this->session->data['abandonedCart_ID'] = $id;
					} 
					} else {
					if (!empty($cart)) {
						$cart = json_encode($cart);
						$this->db->query("UPDATE `" . DB_PREFIX . "abandonedcarts` SET `cart` = '".$this->db->escape($cart)."', `last_page`='".$this->db->escape($lastpage)."', `date_modified`=NOW() WHERE `restore_id`='$id' AND `ordered`=0");
					}
					if (isset($customer)) {
						$customer = json_encode($customer);
						$this->db->query("UPDATE `" . DB_PREFIX . "abandonedcarts` SET `customer_info` = '".$this->db->escape($customer)."', `last_page`='".$this->db->escape($lastpage)."', `date_modified`=NOW() WHERE `restore_id`='$id' AND `ordered`=0");
					}
				}
			}
		}
		/* AbandonedCarts - End */
		
	}

<?php
class ControllerExtensionModulebganycombi extends Controller {	
	private $error = array();
	private $modpath = 'module/bganycombi'; 
	private $modtpl = 'module/bganycombi.tpl';
	private $modname = 'bganycombi';
	private $modtext = 'Buy Any Get Any Product Combination Pack';
	private $modid = '30594';
	private $modssl = 'SSL';
	private $modemail = 'opencarttools@gmail.com';
	private $token_str = '';
	private $modurl = 'extension/module';
	private $modurltext = '';

	public function __construct($registry) {
		parent::__construct($registry);
 		
		if(substr(VERSION,0,3)>='3.0' || substr(VERSION,0,3)=='2.3') { 
			$this->modtpl = 'extension/module/bganycombi';
			$this->modpath = 'extension/module/bganycombi';
		} else if(substr(VERSION,0,3)=='2.2') {
			$this->modtpl = 'module/bganycombi';
		} 
		
		if(substr(VERSION,0,3)>='3.0') { 
			$this->modname = 'module_bganycombi';
			$this->modurl = 'marketplace/extension'; 
			$this->token_str = 'user_token=' . $this->session->data['user_token'] . '&type=module';
		} else if(substr(VERSION,0,3)=='2.3') {
			$this->modurl = 'extension/extension';
			$this->token_str = 'token=' . $this->session->data['token'] . '&type=module';
		} else {
			$this->token_str = 'token=' . $this->session->data['token'];
		}
		
		if(substr(VERSION,0,3)>='3.0' || substr(VERSION,0,3)=='2.3' || substr(VERSION,0,3)=='2.2') { 
			$this->modssl = true;
		} 
 	} 
	
	public function index() {
		$data = $this->load->language($this->modpath);
		$this->modurltext = $this->language->get('text_extension');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting($this->modname, $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			if(! (isset($this->request->post['svsty']) && $this->request->post['svsty'] == 1)) {
				$this->response->redirect($this->url->link($this->modurl, $this->token_str, $this->modssl));
			}
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
 		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
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
			'href' => $this->url->link('common/dashboard', $this->token_str, $this->modssl)
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->modurltext,
			'href' => $this->url->link($this->modurl, $this->token_str, $this->modssl)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link($this->modpath, $this->token_str, $this->modssl)
		);

		$data['action'] = $this->url->link($this->modpath, $this->token_str, $this->modssl);
		
		$data['cancel'] = $this->url->link($this->modurl, $this->token_str , $this->modssl); 
		
		if(substr(VERSION,0,3)>='3.0') { 
			$data['user_token'] = $this->session->data['user_token'];
		} else {
			$data['token'] = $this->session->data['token'];
		}
		 
		$data[$this->modname.'_status'] = $this->setvalue($this->modname.'_status');	
 		
  		$data['modname'] = $this->modname;
		$data['modemail'] = $this->modemail;
  		  
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view($this->modtpl, $data));
	}
	
	protected function setvalue($postfield) {
		if (isset($this->request->post[$postfield])) {
			$postfield_value = $this->request->post[$postfield];
		} else {
			$postfield_value = $this->config->get($postfield);
		} 	
 		return $postfield_value;
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', $this->modpath)) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	public function install() {
		$tbl_query1 = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "bganycombi' ");
		if($tbl_query1->num_rows == 0) {
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "bganycombi` (
				  `bganycombi_id` int(11) NOT NULL AUTO_INCREMENT,
				  `name` varchar(255) NOT NULL,
				  `discount_type` tinyint(1) NOT NULL,
				  `discount_value` decimal(10,2),
				  `buyqty` int(5),
				  `getqty` int(5),
				  `buyproduct` text,
				  `buycategory` text,
				  `buymanufacturer` text,
				  `getproduct` text,
				  `getcategory` text,
				  `getmanufacturer` text,
				  `store` varchar(255) NOT NULL,
				  `customer_group` varchar(255) NOT NULL,
				  `ribbontext` text,
				  `ordertotaltext` text,
				  `display_offer_at` tinyint(1) NOT NULL,
				  `offer_heading_text` text,
				  `offer_content` text,				  
				  `status` tinyint(1) NOT NULL,
 				  `date_start` date, 
				  `date_end` date,
				  PRIMARY KEY (`bganycombi_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
			");
		}
		
		// if(function_exists('curl_version')) { 
		// 	$pdata['extid'] = $this->modid;
		// 	$pdata['version'] = VERSION;
		// 	$pdata['installat'] = HTTP_CATALOG;
		// 	$pdata['email'] = $this->config->get('config_email'); 
			
 	// 		$curl = curl_init("http://www.opencarttools.net/licentry.php");
	
 	// 		curl_setopt($curl, CURLOPT_HEADER, 0);
 	// 		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		// 	curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		// 	curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		// 	curl_setopt($curl, CURLOPT_POST, 1);
		// 	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
		// 	curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		// 	curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($pdata, '', '&'));
	
		// 	$response = curl_exec($curl); 
			
		// 	//echo $response; exit;
		// }      
	}
	public function uninstall() { 
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "bganycombi` ");
 		
		// uninstalled       
	}
}
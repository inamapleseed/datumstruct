<?php
class ControllerExtensionBganycombi extends Controller {	
	private $error = array();
	private $modpath = 'extension/bganycombi'; 
	private $modpath_model = 'total/bganycombitotal';
	private $modtpl = 'default/template/extension/bganycombi.tpl'; 
 	private $modname = 'bganycombi';
	private $modtext = 'Buy Any Get Any Product Combination Pack';
	private $modid = '30594';
	private $modssl = 'SSL';
	private $modemail = 'opencarttools@gmail.com'; 
	private $langid = 0;
	private $storeid = 0;
	private $custgrpid = 0;
	
	public function __construct($registry) {
		parent::__construct($registry);
		
		$this->langid = (int)$this->config->get('config_language_id');
		$this->storeid = (int)$this->config->get('config_store_id');
		$this->custgrpid = (int)$this->config->get('config_customer_group_id');
 		
		if(substr(VERSION,0,3)>='3.0' || substr(VERSION,0,3)=='2.3') { 
 			$this->modpath_model = 'extension/total/bganycombitotal';
		} 
		
		if(substr(VERSION,0,3)>='3.0') { 
			$this->modname = 'module_bganycombi';
		} 
		
		if(substr(VERSION,0,3)>='3.0' || substr(VERSION,0,3)=='2.3' || substr(VERSION,0,3)=='2.2') { 
			$this->modssl = true;
			$this->modtpl = 'extension/bganycombi';
		} 
 	} 
	
	public function index() {
  		$data['modpath'] = $this->modpath;
		
		$this->load->model($this->modpath_model);
 		
 		// for product details : 
		$data['product_id'] = 0;
		if (isset($this->request->get['product_id'])) {
			$data['product_id'] = (int)$this->request->get['product_id'];
		} 
		
		$data['product_route'] = 0;
 		if (isset($this->request->get['route']) && $this->request->get['route'] == 'product/product') {
			$data['product_route'] = 1;
		} 
 		
 		$data['bganycombi_status'] = $this->setvalue($this->modname.'_status');
		
		$data['display_offer_at'] = 0;
		$data['offer_heading_text'] = '';
		$data['offer_content'] = '';
 		
 		if($data['bganycombi_status']) {
			if(isset($this->request->get['product_id'])) { 
				$this->load->model('catalog/product');
				
				$product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
				
				if($product_info && (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price'))) {
				
					if(substr(VERSION,0,3) >='3.0' || substr(VERSION,0,3) =='2.3') { 
						$bganycombi_discount_info = $this->model_extension_total_bganycombitotal->checkbganycombidiscount($product_info['product_id']); 
					} else {
						$bganycombi_discount_info = $this->model_total_bganycombitotal->checkbganycombidiscount($product_info['product_id']); 
					}
					
					// echo "<pre>"; print_r($bganycombi_discount_info);exit;
					
					$data['bganycombi_info'] = array();
				
					if(!empty($bganycombi_discount_info)) {
						foreach($bganycombi_discount_info as $bganycombi_id => $info) { 
							if($info) { 					
								$data['bganycombi_info'][$bganycombi_id]['bganycombi_id'] = $bganycombi_id;
								$data['bganycombi_info'][$bganycombi_id]['display_offer_at'] = $info['display_offer_at'];
								$data['bganycombi_info'][$bganycombi_id]['offer_heading_text'] = $info['offer_heading_text'];
								$offer_content = html_entity_decode($info['offer_content'], ENT_QUOTES, 'UTF-8');	
								$data['bganycombi_info'][$bganycombi_id]['offer_content'] = str_replace(array("\n", "\r"), '', $offer_content);
							}
						}
					}
					//echo "<pre>"; print_r($data['bganycombi_info']);exit;
				}
			}
		
			return $this->load->view($this->modtpl, $data);
		}
	}  
	
	public function getjson() {    		
  		$data['modpath'] = $this->modpath;
 		
 		// for product details : 
		$data['product_id'] = 0;
		if (isset($this->request->get['product_id'])) {
			$data['product_id'] = (int)$this->request->get['product_id'];
		} 
		
		$data['product_route'] = 0;
 		if (isset($this->request->get['route']) && $this->request->get['route'] == 'product/product') {
			$data['product_route'] = 1;
		} 
 		
 		$data['bganycombi_status'] = $this->setvalue($this->modname.'_status');
  		
 		$data['bganycombi_products_data'] = array();
 		$data['bganycombi_products_data']['all'] = 0;
		$data['bganycombi_products_data']['ribbontext'] = '';
		
 		if($data['bganycombi_status']) {  
			$q = $this->db->query("SELECT * FROM " . DB_PREFIX . "bganycombi WHERE status = 1 and date_start <= curdate() and date_end >= curdate() and find_in_set(".$this->storeid.", store) and find_in_set(".$this->custgrpid.", customer_group)");
			if($q->num_rows) {
				foreach($q->rows as $rs) {
				
					$ribbontext = json_decode($rs['ribbontext'], true);
					$ribbontext = $ribbontext[(int)$this->langid];
				 	
					// buy 
					
					if(isset($rs['buyproduct']) && $rs['buyproduct']) {
						$rs['buyproduct'] = explode(",",$rs['buyproduct']);
						foreach($rs['buyproduct'] as $prodid) {
							$data['bganycombi_products_data'][$prodid] = $ribbontext;
						}
					}
				
					if(isset($rs['buycategory']) && $rs['buycategory']) {
						$rs['buycategory'] = explode(",",$rs['buycategory']);
						foreach($rs['buycategory'] as $catid) {
							$carqry = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product_to_category WHERE category_id = ".(int)$catid);
							foreach ($carqry->rows as $catdata) {
								$data['bganycombi_products_data'][$catdata['product_id']] = $ribbontext;
							}
						}
					}
					
					if(isset($rs['buymanufacturer']) && $rs['buymanufacturer']) {
						$rs['buymanufacturer'] = explode(",",$rs['buymanufacturer']);
						foreach($rs['buymanufacturer'] as $manufacturer_id) {
							$manqry = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE manufacturer_id = ".(int)$manufacturer_id);
							foreach ($manqry->rows as $mandata) {
								$data['bganycombi_products_data'][$mandata['product_id']] = $ribbontext;
							}
						}
					}
					
					// get 
					
					if(isset($rs['getproduct']) && $rs['getproduct']) {
						$rs['getproduct'] = explode(",",$rs['getproduct']);
						foreach($rs['getproduct'] as $prodid) {
							$data['bganycombi_products_data'][$prodid] = $ribbontext;
						}
					}
 					
 					if(isset($rs['getcategory']) && $rs['getcategory']) {
						$rs['getcategory'] = explode(",",$rs['getcategory']);
						foreach($rs['getcategory'] as $catid) {
							$carqry = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product_to_category WHERE category_id = ".(int)$catid);
							foreach ($carqry->rows as $catdata) {
								$data['bganycombi_products_data'][$catdata['product_id']] = $ribbontext;
							}
						}
					}
					
					if(isset($rs['getmanufacturer']) && $rs['getmanufacturer']) {
						$rs['getmanufacturer'] = explode(",",$rs['getmanufacturer']);
						foreach($rs['getmanufacturer'] as $manufacturer_id) {
							$manqry = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE manufacturer_id = ".(int)$manufacturer_id);
							foreach ($manqry->rows as $mandata) {
								$data['bganycombi_products_data'][$mandata['product_id']] = $ribbontext;
							}
						}
					}
				
					if(! ((isset($rs['buyproduct']) && $rs['buyproduct']) || (isset($rs['buycategory']) && $rs['buycategory']) || (isset($rs['buymanufacturer']) && $rs['buymanufacturer']) && (isset($rs['getproduct']) && $rs['getproduct']) || (isset($rs['getcategory']) && $rs['getcategory']) || (isset($rs['getmanufacturer']) && $rs['getmanufacturer'])) ) {				
						$data['bganycombi_products_data']['all'] = 1;
						$data['bganycombi_products_data']['ribbontext'] = $ribbontext;
					} 
				
 				}
			} 
			
   			//return $this->load->view($this->modtpl, $data);
			
			header('Content-Type: application/x-javascript'); 
			
			echo 'var bganycombi_products_data = ' . json_encode($data['bganycombi_products_data']);
		}
	} 
	
	protected function setvalue($postfield) {
		return $this->config->get($postfield);
	}
}
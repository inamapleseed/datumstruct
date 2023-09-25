<?php
class ControllerExtensionModuleCategory extends Controller {
	
	private $gets = array(
		'path'	=> 'underscore,number', 
		'manufacturer_id' => 'comma,number',
		'price_min'	=> 'number', 
		'price_max'	=> 'number',
		'sort'	=> array('p.sort_order', 'pd.name', 'p.price', 'p.model', 'p.sku'), 
		'order'	=> array('ASC','DESC','asc','desc'),
		'filter' => 'comma,number'
	);

	private $route = array(
		'product/category',
		'product/special',
	);

	public function index() {
		                // << Related Options / Связанные опции  
		$this->load->language('module/related_options');
		$data['text_ro_clear_options'] 			= $this->language->get('text_ro_clear_options');
                // >> Related Options / Связанные опции 
		$this->load->model('extension/module/category');

		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_close'] = $this->language->get('button_close');

		// $route = 'product/category';

		if ($this->session->data['main_product'] && isset($this->session->data['main_product'])) {
			$main_pcategories = $this->session->data['main_product'];
		}else{
			$main_pcategories = "";
		}
		// debug($main_pcategories);
		if ($main_pcategories === 'enquiry') {
			$route = 'product/category';
		} elseif ($main_pcategories === 'shop_product' || $main_pcategories === "" ) {
			$route = 'product/shop_product';	
		} 
		
		if( isset($this->request->get['route']) && in_array($this->request->get['route'], $this->route) ){
			$route = $this->request->get['route'];
		}
		if( isset($this->request->get['path']) ){
			$route = $this->url->link($route, 'path=' . $this->request->get['path'], true);
		}else{
			$route = $this->url->link($route, '', true);
		}

		$data['page_url'] = $data['route'] = $route;

		// Category
		$data['categories']='';
		
		if($this->config->get('category_status')){
			$data['categories'] = $this->getCategoryFilter();
		}
		// End Category
		
		
		// Manufacturer
		$data['manufacturers']='';
		
		if($this->config->get('category_brand_status')){
			$data['manufacturers'] = $this->getManufacturerFilter();
		}
		// End Manufacturer
		
		// Prices
		$data['prices']='';
		
		if($this->config->get('category_price_status')) { 
			$data['prices'] = $this->getPriceFilter();
		}
		// End Prices

		// Filter
		$data['filters']='';
		
		if($this->config->get('category_filter_status')) { 
			$data['filters'] = $this->getFilter();
		}
		// End Filter
		
		return $this->load->view('extension/module/category', $data);
	}

	// Version 2
	private function getCategories($levels = 1, $category_id = 0, $current_level = 1){
		$categories = $this->model_extension_module_category->getCategories($category_id);
		
		$continue = false;

		if($current_level < $levels){
			$current_level++;
			$continue = true;
		}
		
		foreach($categories as &$category){
			$category['child'] = ($continue)?$this->getCategories($levels, $category['category_id'], $current_level):array();
		}
		
		return $categories;
	}
	// End Version 2

	private function getFilter(){
		// List by Category
		if($this->config->get('category_filter_status') == 2 && isset($this->request->get['path'])){
			return $this->getFilterByCategory();
		}
		// Auto Grab
		else{
			return $this->getFilterByAuto();
		}
	}

	private function getFilterByCategory(){
		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		$category_id = end($parts);

		$this->load->model('catalog/category');

		$category_info = $this->model_catalog_category->getCategory($category_id);

		if ($category_info) {
			$this->load->language('extension/module/filter');

			$data['heading_title'] = $this->language->get('heading_title');

			$data['button_filter'] = $this->language->get('button_filter');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['action'] = str_replace('&amp;', '&', $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url));

			if (isset($this->request->get['filter'])) {
				$data['filter_category'] = explode(',', $this->request->get['filter']);
			} else {
				$data['filter_category'] = array();
			}

			$this->load->model('catalog/product');

			$data['filter_groups'] = array();

			$filter_groups = $this->model_catalog_category->getCategoryFilters($category_id);

			if ($filter_groups) {
				foreach ($filter_groups as $filter_group) {
					$childen_data = array();

					foreach ($filter_group['filter'] as $filter) {
						$filter_data = array(
							'filter_category_id' => $category_id,
							'filter_filter'      => $filter['filter_id']
						);

						$childen_data[] = array(
							'filter_id' => $filter['filter_id'],
							'name'      => $filter['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : '')
						);
					}

					$data['filter_groups'][] = array(
						'filter_group_id' => $filter_group['filter_group_id'],
						'name'            => $filter_group['name'],
						'filter'          => $childen_data
					);
				}

				return $this->load->view('extension/module/filter_group/filters', $data);
			}
		}
	}

	private function getFilterByAuto(){
		$url = $this->loadUrl();

		$param = explode('&', $url);

		$filter_data = array();

		$category_id = 0;

		foreach($param as $var){
			if(trim($var)){
				list($key, $value) = explode('=', $var);
				$filter_data[$key] = $value;
			}
		}

		if( isset($filter_data['path']) ){
			$filter_data['path'] = explode('_', $filter_data['path']);
			$filter_data['path'] = end($filter_data['path']);
			$category_id = (int)$filter_data['path'];
		}

		if (isset($this->request->get['filter'])) {
			$data['filter_category'] = explode(',', $this->request->get['filter']);
		} else {
			$data['filter_category'] = array();
		}

		$this->load->model('catalog/product');

		$filter_groups = $this->model_catalog_product->getFilterByProducts($filter_data);

		if ($filter_groups) {
			foreach ($filter_groups as $filter_group) {
				$childen_data = array();

				foreach ($filter_group['filter'] as $filter) {
					$filter_data = array(
						'filter_category_id' => $category_id,
						'filter_filter'      => $filter['filter_id']
					);

					$childen_data[] = array(
						'filter_id' => $filter['filter_id'],
						'name'      => $filter['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : '')
					);
				}

				$data['filter_groups'][] = array(
					'filter_group_id' => $filter_group['filter_group_id'],
					'name'            => $filter_group['name'],
					'filter'          => $childen_data
				);
			}

			return $this->load->view('extension/module/filter_group/filters', $data);
		}
	}
	
	private function getPriceFilter(){
		
		$url =	$this->loadUrl('price_min,price_max');

		$path = 0;
		
		if(isset($this->request->get['path'])){
			$path = $this->request->get['path'];
		}

		$paths = explode('_', $path);

		$paths = end($paths);


		$manufacturer_ids = array();

		if(isset($this->request->get['manufacturer_id']) && isset($this->request->get['manufacturer_id']) ){
			foreach(explode(',', $this->request->get['manufacturer_id']) as $mid){
				if((int)$mid > 0){
					$manufacturer_ids[] = (int)$mid;
				}
			}
		}

		$active = $this->url->link('product/category') . $url;
		
		$price_data = array();
		
		$currency_code = isset($this->session->data['currency'])?$this->session->data['currency']:$this->config->get('config_currency');
		$left_symbol = '';
		
		if($currency_code) {
			$left_symbol = $this->currency->getSymbolLeft($currency_code);
		}
		
		$this->load->model('catalog/product');
		
		$price_min = $lowest_price = $this->model_catalog_product->getLowesetPrice($paths, $manufacturer_ids);
		$price_max = $highest_price = $this->model_catalog_product->getHighestPrice($paths, $manufacturer_ids);
		
		if( isset($this->request->get['price_min']) && (float)$this->request->get['price_min'] > -1 ){
			$price_min = (float)$this->request->get['price_min'];
		}
		
		if( isset($this->request->get['price_max']) && (float)$this->request->get['price_max'] > -1 ){
			$price_max = (float)$this->request->get['price_max'];
		}

		$this->convertCurrency($price_min);
		$this->convertCurrency($price_max);
		$this->convertCurrency($lowest_price);
		$this->convertCurrency($highest_price);

		$this->load->language('extension/module/price');
		
		$data = array(
			'heading_title'	=> $this->language->get('heading_title'),
			'active'		=> $active,
			'left_symbol'	=> $left_symbol,
			'lowest_price'	=> sprintf('%.2f', floor($lowest_price)),
			'highest_price'	=> sprintf('%.2f', ceil($highest_price)),
			'price_min'		=> sprintf('%.2f', floor($price_min)),
			'price_max'		=> sprintf('%.2f', ceil($price_max)),
			'button_apply'	=> $this->language->get('button_apply')
		);
		
		return $this->load->view('extension/module/filter_group/prices', $data);
	}
	
	private function getManufacturerFilter(){

		$get_related_manufacturer = $this->relatedManufacturer(); //debug($get_related_manufacturer);

		$manufacturers = array();

		$manufacturer_ids = isset($this->request->get['manufacturer_id'])?$this->request->get['manufacturer_id']:'';
		
		$manufacturer_ids = explode(',', $manufacturer_ids);

		foreach($manufacturer_ids as $index => $manufacturer_id){
			$manufacturer_ids[$index] = (int)$manufacturer_id;
		}
		
		$this->load->language('extension/module/manufacturer');

		$data['heading_title'] = $this->language->get('heading_title');

		$this->load->model('catalog/manufacturer');
		
		//$results = $this->model_catalog_manufacturer->getManufacturers();

		$results = $get_related_manufacturer;
		
		//debug($results);
		
		foreach($results as $result){
			
			$checked = in_array($result['manufacturer_id'], $manufacturer_ids)?true:false;
			
			$url_with_other_manufacturer = array();
			
			$manufacturer_id_set = array();

			foreach($manufacturer_ids as $each_id){
				if($each_id != $result['manufacturer_id']){
					$manufacturer_id_set[]= $each_id;
				}
			}
			
			if(!$checked){
				$manufacturer_id_set[]=$result['manufacturer_id'];
			}

			$url_with_other_manufacturer[] = 'manufacturer_id=' . implode(',', $manufacturer_id_set);
			
			$url_with_other_manufacturer = implode('&', $url_with_other_manufacturer);
			
			$manufacturers[] = array(
				'mid'			=>	$result['manufacturer_id'],
				'checked'		=>	$checked,
				'name'			=>	$result['name'],
				'href'			=>	$this->url->link('product/category', $url_with_other_manufacturer),
			);
			
		}

		$data['manufacturers'] = $manufacturers;
		
		return $this->load->view('extension/module/filter_group/manufacturers', $data);  
	}

	private function relatedManufacturer(){
		$category_id = 0;

		$manufacturers = array();

		if(isset($this->request->get['path']) && !is_array($this->request->get['path'])){
			$paths = explode('_', $this->request->get['path']);
			$category_id = end($paths);
			$category_id = (int)$category_id;
		}

		$this->load->model('catalog/category');

		$category_info = $this->model_catalog_category->getCategory($category_id);

		if($category_info){
			$query = $this->db->query('
				SELECT manufacturer_id, name FROM `'.DB_PREFIX.'manufacturer` WHERE manufacturer_id IN (
				SELECT DISTINCT p.manufacturer_id FROM `' . DB_PREFIX . 'product` p LEFT JOIN `' . DB_PREFIX . 'product_to_category` p2c ON (p.product_id = p2c.product_id) LEFT JOIN `' . DB_PREFIX . 'category_path` cp ON (p2c.category_id = cp.category_id) WHERE cp.path_id="' . $category_id . '" AND p.manufacturer_id > 0 AND p.status=1
				) ORDER BY sort_order ASC, name ASC
				');
			
			if($query->num_rows){
				$manufacturers = $query->rows;
			}
		}
		else{
			$query = $this->db->query('
				SELECT manufacturer_id, name FROM `'.DB_PREFIX.'manufacturer` WHERE manufacturer_id IN (
				SELECT DISTINCT manufacturer_id FROM `' . DB_PREFIX . 'product` WHERE manufacturer_id > 0 AND status=1
				) ORDER BY sort_order ASC, name ASC
				');
			
			if($query->num_rows){
				$manufacturers = $query->rows;
			}
		}

		return $manufacturers;
	}
	

	
	private function getCategoryFilter(){
		
		$url = $this->loadUrl('path');
		$this->load->model('catalog/category');

	// Start products or gallery
		if ($this->session->data['main_product'] && isset($this->session->data['main_product'])) {
			$main_pcategories = $this->session->data['main_product'];
		}else{
			$main_pcategories = "";
		}
		// debug($main_pcategories);
		if ($main_pcategories === 'enquiry') {
			$route = 'product/category';
		} elseif ($main_pcategories === 'shop_product' || $main_pcategories === "" ) {
			$route = 'product/shop_product';	
		} 

		// debug($main_pcategories);
		// End products or gallery		
		
		if( isset($this->request->get['route']) && in_array($this->request->get['route'], $this->route) ){
			$route = $this->request->get['route'];
		}

		$path = isset($this->request->get['path'])?$this->request->get['path']:0;
		
		$paths = explode('_', $path);

		$this->load->language('extension/module/category');

		$data['heading_title'] = $this->language->get('heading_title');
		
		$this->load->model('catalog/category');
		
		$categories = $this->model_catalog_category->getCategories(0);
		$return_categories  = array();
		
		foreach($categories as $category){
			$child_1 = array();
			
			$active = '';
			
			$child_1_categories = $this->model_catalog_category->getCategories($category['category_id']);
		// debug($child_1_categories);
			
			foreach($child_1_categories as $child_1_category){
				$child_2 = array();
				
				$active_1 = '';
				
				$child_2_categories = $this->model_catalog_category->getCategories($child_1_category['category_id']);
				
				foreach($child_2_categories as $child_2_category){
					
					$active = $active_1 = $active_2 = in_array($child_2_category['category_id'], $paths)?'active':'';
					
					$path = $category['category_id'] . '_' . $child_1_category['category_id'] . '_' . $child_2_category['category_id'];

					$child_2[] = array(
						'category_id'		=>	$child_2_category['category_id'],
						'active'			=>	$active_2,
						'name'				=>	$child_2_category['name'],
						'path'				=>	$path,
						'href'				=>	$this->url->link($route, 'path=' . $path)
					);
				}
				
				//=====================
				
				$active = $active_1 = $active_1?'active':(in_array($child_1_category['category_id'], $paths)?'active':'');

				$path = $category['category_id'] . '_' . $child_1_category['category_id'];
				
				$child_1[] = array(
					'category_id'		=>	$child_1_category['category_id'],
					'active'				=>	$active_1,
					'name'				=>	$child_1_category['name'],
					'path'				=>	$path,
					'href'				=>	$this->url->link($route, 'path=' . $path . $url),
					'child'				=>	$child_2
				);
			}
			
			// ====================

			$active = $active?'active':(in_array($category['category_id'], $paths)?'active':'');

			$path = $category['category_id'];

			$return_categories[] = array(
				'category_id'		=>	$category['category_id'],
				'active'				=>	$active,
				'name'				=>	$category['name'],
				'path'				=>	$path,
				'href'				=>	$this->url->link($route, 'path=' . $path . $url),
				'child'				=>	$child_1
			);
		}
		
		

       usort($return_categories, function($a, $b){
            return $a['name'] - $b['name'];
       });

		$data['categories'] = $return_categories;
		
		return $this->load->view('extension/module/filter_group/categories', $data);
	}
	



	private function convertCurrency(&$price){
		if($this->config->get('config_currency') != $this->session->data['currency']){
			$price = $this->currency->convert($price, $this->config->get('config_currency'), $this->session->data['currency']);
		}
	}

	private function loadUrl($current_type = ''){
		$url = '';
		
		foreach($this->gets as $var_get => $value_type){

			if( strpos('_' . $current_type, $var_get) ) continue;

			if(gettype($value_type) == 'string'){
				if($this->validate_get_match($var_get, $value_type)){
					if(strpos('_' . $value_type, 'array')){
						foreach($this->request->get[$var_get] as $each){
							if((int)$each < 1) continue;
							
							$url .= '&' . $var_get . '[]=' . $each;
						}
					}
					else{
						$url .= '&' . $var_get . '=' . $this->request->get[$var_get];
					}
				}
				else{

				}
			}
			elseif( isset($this->request->get[$var_get]) && in_array($this->request->get[$var_get], $value_type) ){
				$url .= '&' . $var_get . '=' . $this->request->get[$var_get];
			}

		}
		
		return $url;
	}

	// Validate and make sure value exist
	private function validate_get_match($var, $checkfor){
		
		if(!isset($this->request->get[$var])) return false;

		$checks = explode(',', $checkfor); //debug($checks);

		if(in_array('array', $checks) && is_array($this->request->get[$var]) ){
			if(isset($checks[1]) && $checks[1] == 'number'){
				foreach($this->request->get[$var] as $each){
					if($each < 1){
						return false;
					}
				}
				return true;
			}
			return true;
		}
		elseif( in_array('comma', $checks) ){  
			$list = explode(',', $this->request->get[$var]);
			if($list){ 
				if(isset($checks[1]) && $checks[1] == 'number'){ 
					foreach($list as $each){ 
						if($each < 1){
							return false;
						}
					}
					return true;
				}
				return true; // nothing to check so if isset then return true
			}
			return false;
		}
		elseif(in_array('underscore', $checks) && $this->request->get[$var] > -1){
			$list = explode('_', $this->request->get[$var]);
			if($list){ 
				if(isset($checks[1]) && $checks[1] == 'number'){ 
					foreach($list as $each){ 
						if($each < 1){
							return false;
						}
					}
					return true;
				}
				return true; // nothing to check so if isset then return true
			}
			return false;
		}
		elseif(in_array('number', $checks) && $this->request->get[$var] > -1){
			return true;
		}

		// Rule not defined
		return false;
	}
}
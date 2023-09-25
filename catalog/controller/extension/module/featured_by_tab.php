<?php
class ControllerExtensionModuleFeaturedByTab extends Controller {
	public function index($setting) {

		$data['uqid'] = $setting['module_id'];

		$this->load->language('extension/module/featured_by_tab');

		$data['heading_title'] = $this->language->get('heading_title');
		if(isset($setting['title'][$this->config->get('config_language_id')]['title']))  $data['heading_title'] = $setting['title'][$this->config->get('config_language_id')]['title'];

		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_view_more'] = $this->language->get('button_view_more');
		$data['button_cart'] = $this->language->get('button_cart'); 
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$data['tabs'] = array();

		if(!isset($setting['tabs'])) return;

		foreach($setting['tabs'] as $tab){
			$category_info = $this->model_catalog_category->getCategory($tab['category_id']);

			//if(!$category_info) continue;	// Category deleted / Category disabled

			if(!$tab['status']) continue;	// Tab disabled

			if( !isset($tab['product_ids']) || !is_array($tab['product_ids']) || !$tab['product_ids']) continue; // Product not added / Added Wrongly (Rare!)

			$products = array();

			foreach($tab['product_ids'] as $product_id){
				$products[] = $this->load->controller('component/product_info', $product_id);
			}

			$data['tabs'][] = array(
				'tab_name'		=>	$tab['tab_name'],
				'category_id'	=>	$category_info?$category_info['category_id']:0,
				'name'			=>	$category_info?$category_info['name']:'',
				'products'		=>	$products
			);
		}

		//debug($data['tabs']);

		if ($data['tabs']) { 
			$this->document->addStyle('catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.css');
			$this->document->addScript('catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.js');
			$data['slider_script'] = $this->load->view('extension/module/featured_by_tab_owl_script', $data);
			return $this->load->view('extension/module/featured_by_tab', $data);
		}
		
	}
}
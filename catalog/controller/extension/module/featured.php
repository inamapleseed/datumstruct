<?php
class ControllerExtensionModuleFeatured extends Controller {
	private static $module_id = 1;

	public function index($setting) {
		$this->load->language('extension/module/featured');

		$data["uqid"] = $setting['module_id'] . '_' . $this->module_id;

		$data['heading_title'] = $this->language->get('heading_title');

		if( isset($setting['title'][(int)$this->config->get('config_language_id')]) ){
			$data['heading_title'] = $setting['title'][(int)$this->config->get('config_language_id')];
		}

		$this->document->addStyle('catalog/view/javascript/slick/slick.min.css');
		$this->document->addScript('catalog/view/javascript/slick/slick-custom.min.js');

		//$this->document->addStyle('catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.css');
		//$this->document->addScript('catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.js');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');
		// if (isset($this->request->get['route'])) {
			// $route = (string)$this->request->get['route'];
		// }
		// debug($route);
		$data['humanscale'] = $this->load->controller('extension/module/humanscale/migrate');

		$data['products'] = array();

		if (!empty($setting['product'])) { 
			$products = $setting['product'];

			$width = $this->config->get($this->config->get('config_theme') . '_image_product_width');
			$height = $this->config->get($this->config->get('config_theme') . '_image_product_height');

			foreach ($products as $product_id) {
				$data['products'][] = $this->load->controller('component/product_info', $product_id);
			}
		}

		if(!$data['products']) return '';

		$this->module_id++;

		return $this->load->view('extension/module/featured_slick', $data);
	}
}
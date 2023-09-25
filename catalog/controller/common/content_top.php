<?php
	class ControllerCommonContentTop extends Controller {
		public function index() {
			$this->load->model('design/layout');
			
			$route = 'common/home';

			if (isset($this->request->get['route'])) {
				$route = (string)$this->request->get['route'];
			}
			
			$layout_id = 0;
			
			if ($route == 'news/article' && isset($this->request->get['news_id'])) {
				$layout_id = $this->model_catalog_news->getNewsLayoutId($this->request->get['news_id']);
			}
			if ($route == 'news/ncategory' && isset($this->request->get['ncat'])) {
				$ncat = explode('_', (string)$this->request->get['ncat']);
				
				$layout_id = $this->model_catalog_ncategory->getncategoryLayoutId(end($ncat));			
			}
			if ($route == 'product/category' && isset($this->request->get['path'])) {
				$this->load->model('catalog/category');
				
				$path = explode('_', (string)$this->request->get['path']);
				
				$layout_id = $this->model_catalog_category->getCategoryLayoutId(end($path));
			}
			
			if ($route == 'product/product' && isset($this->request->get['product_id'])) {
				$this->load->model('catalog/product');
				
				$layout_id = $this->model_catalog_product->getProductLayoutId($this->request->get['product_id']);
			}
			
			if ($route == 'information/information' && isset($this->request->get['information_id'])) {
				$this->load->model('catalog/information');
				$this->load->model('catalog/news');
				$this->load->model('catalog/ncategory');
				
				$layout_id = $this->model_catalog_information->getInformationLayoutId($this->request->get['information_id']);
			}
			
			if (!$layout_id) {
				$layout_id = $this->model_design_layout->getLayout($route);
			}
			
			if (!$layout_id) {
				$layout_id = $this->config->get('config_layout_id');
			}
			
			$this->load->model('extension/module');
			
			$data['modules'] = array();
			
			$modules = $this->model_design_layout->getLayoutModules($layout_id, 'content_top'); // debug($modules);
			
			foreach ($modules as $module) {

				$mode = $module['mode'];

				$part = explode('.', $module['code']);
				
				if (isset($part[0]) && $this->config->get($part[0] . '_status')) {
					
					$module_data = $this->load->controller('extension/module/' . $part[0]);
					
					if ($module_data) {
						$this->encapsulate($module_data, $mode, $part[0], $module['background']);
						$data['modules'][] = $module_data;
					}
				}
				
				if (isset($part[1])) {
					$setting_info = $this->model_extension_module->getModule($part[1]);
					
					if ($setting_info && $setting_info['status']) {
						$setting_info['module_id'] = $part[1];
						
						$output = $this->load->controller('extension/module/' . $part[0] , $setting_info);
						
						if ($output) {
							$this->encapsulate($output, $mode, $part[0]. ' ' . $part[0] . '-' . $part[1], $module['background']);
							$data['modules'][] = $output;
						}
					}
				}
			}
			
			return $this->load->view('common/content_top', $data);
		}

		private function encapsulate(&$output, $mode="", $module="", $background = ''){
			$style = '';
			
			if( is_file ( DIR_IMAGE . $background) ){
				$style = 'style="background-color: transparent; background-image: url(\'image/'.$background.'\'); background-position: center center; background-size: cover;"';
			}

			if($mode == "full-width"){
				$output = "<div class='section-space max-offset $module' $style>" . $output . "</div>";
			}
			elseif($mode == "full-width-with-container"){
				$output = "<div class='section-space max-offset $module' $style><div class='container'>" . $output . "</div></div>";
			}else{
				$output = "<div class='section-space $module' $style>" . $output . "</div>";
			}
		}
	}

	

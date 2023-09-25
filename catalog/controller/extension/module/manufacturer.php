<?php
class ControllerExtensionModuleManufacturer extends Controller {
	
	public function index($setting) {  
		$this->load->language('extension/module/manufacturer');

		$this->document->addStyle("catalog/view/javascript/slick/slick.css");
		$this->document->addScript("catalog/view/javascript/slick/slick.min.js");

		$data['uqid'] = 'feature-manufacturer';

		$data['heading_title'] = $this->language->get('heading_title');

		$this->load->model('catalog/manufacturer');

		$this->load->model('tool/image');

		$data['manufacturers'] = array();

		$manufacturers = $this->model_catalog_manufacturer->getManufacturers(0);

		$placeholder = $this->model_tool_image->resize("placeholder.png", 109, 71);

		foreach ($manufacturers as $manufacturer) { 

			$image = $placeholder;
			if( $manufacturer['image'] && is_file(DIR_IMAGE . $manufacturer['image']) ) {
				$image = $this->model_tool_image->resize($manufacturer['image'], 109, 71);
			}

			$data['manufacturers'][] = array(
				'manufacturer_id' => $manufacturer['manufacturer_id'],
				'name'        => $manufacturer['name'],
				'image'		  => $image,
				'href'        => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id'])
			);


			if(count($data['manufacturers']) >= 50){
				break;
			}
		}

		if(isset($setting['return_json']) && $setting['return_json'] === true){
			return $data;
		}

		return $this->load->view('extension/module/manufacturer', $data);
	}
}
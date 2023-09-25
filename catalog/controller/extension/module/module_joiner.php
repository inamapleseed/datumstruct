<?php
class ControllerExtensionModuleModuleJoiner extends Controller {
	public function index($setting) {
	
		$data['btn_view'] = $this->language->get('btn_view');

		$this->load->language('extension/module/module_joiner');
		
		$data['uqid']	= $setting['module_id'];

		$data['setting'] = $setting;
		
		$this->load->model('extension/module');
		
		$language_id = (int)$this->config->get('config_language_id');

		$data['heading_title'] = $setting['title'][$language_id];

		$data['modules'] = array();

		$data['modules_no_tab'] = array(); 
		
		$modules = $setting['layout_module'];
		
		foreach ($modules as $module_info) {
			$part = explode('.', $module_info['code']);

			

			if (isset($part[0]) && $this->config->get($part[0] . '_status')) {
				$data['modules'][] = array(
					'module'		=> $this->load->controller('extension/module/' . $part[0]),
				);	
			}

			if (isset($part[1])) {
				$setting_info = $this->model_extension_module->getModule($part[1]);
				
				$setting_info['module_id']	= $part[1];

				$tab_title = isset( $setting_info['title'][$language_id] )?$setting_info['title'][$language_id]:'';

				if ($setting_info && $setting_info['status']) {
					if(isset($setting['return_json']) && $setting['return_json'] === true){
						$setting_info['return_json'] = true;
					}


					if($tab_title){
						$data['modules'][] = array(
							'tab'			=> $tab_title,
							'module'		=> $this->load->controller('extension/module/' . $part[0], $setting_info),
							'href'			=> $module_info['url'],
						);
					}
					else{
						$data['modules_no_tab'] = array(
							'module'		=> $this->load->controller('extension/module/' . $part[0], $setting_info),
						);
					}
				}
			}
		}

		
		if(isset($setting['return_json']) && $setting['return_json'] === true){
			return $data;
		}
		else{
			return $this->load->view('extension/module/module_joiner', $data);		
		}
	}
}
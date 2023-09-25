<?php
    class ControllerApiModule extends Controller{
        public function loadModule(){

            if ($_SERVER['SERVER_ADDR'] != $_SERVER['REMOTE_ADDR']){
                echo 'No Remote Access Allowed'; exit;
            }

            $response = array();

            if(
                isset($this->request->get['module']) && 
                !is_array($this->request->get['module'])
            ){
                $module = $this->db->escape($this->request->get['module']);

                $part = explode('.', $module);
                
                $this->load->model('extension/module');

                if (isset($part[0]) && $this->config->get($part[0] . '_status')) {
					$module_data = $this->load->controller('extension/module/' . $part[0], true);
					
					if ($module_data) {
						$response = $module_data;
					}
				}
				
				if (isset($part[1])) {
					$setting_info = $this->model_extension_module->getModule($part[1]);
					
					if ($setting_info && $setting_info['status']) {
                        $setting_info['module_id'] = $part[1];
                        $setting_info['return_json'] = true;
						
						$output = $this->load->controller('extension/module/' . $part[0], $setting_info);
						
						if ($output) {
							$response = $output;
						}
					}
				}

            }

            //debug($response);

            $this->response->addHeader('Content-type: application/json');
            $this->response->setOutput(json_encode($response));
        }
    }
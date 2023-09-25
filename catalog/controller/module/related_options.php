<?php
//  Related Options / Связанные опции 
//  Support: support@liveopencart.com / Поддержка: help@liveopencart.ru

class ControllerModuleRelatedOptions extends Controller {
	
  public function get_ro_free_quantity() {
    

		if ( !$this->model_module_related_options ) {
			$this->load->model('module/related_options');
		}
		
		$json = $this->model_module_related_options->get_ro_free_quantity();
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
      

  }
  
}

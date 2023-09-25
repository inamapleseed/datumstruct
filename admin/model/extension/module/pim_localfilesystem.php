<?php 
class ModelExtensionModulePimLocalFileSystem extends Model {
	public function validate() {
  	$this->language->load('extension/module/pim_localfilesystem');
    $error = array();
    if ((utf8_strlen($this->request->post['alias']) < 1) || (utf8_strlen($this->request->post['alias']) > 64)) {
			$error['error_localfilesystem_alias'] = $this->language->get('error_localfilesystem_alias');
		} else if(!isset($this->request->get['alias']) || (isset($this->request->get['alias']) && $this->request->get['alias']!= $this->request->post['alias'])) {  // check if this volume alias already exist.
  		$current_volumes = $this->config->get('pim_volumes');
  		if (!empty($current_volumes) && is_array($current_volumes)) { 
    		foreach ($current_volumes as $key => $volume) {
      		if (is_array($volume)) {
        		foreach ($volume as $vkey => $val) {
        		  if ($vkey == $this->request->post['alias']) {
          		  $error['error_alias_exist'] = $this->language->get('error_alias_exist'); 
        		  }
        		}
      		}
    		}
    		
  		}  		
  		
		}
    if ((utf8_strlen($this->request->post['path']) < 1) || (utf8_strlen($this->request->post['path']) > 600)) {
			$error['error_localfilesystem_path'] = $this->language->get('error_localfilesystem_path');
		}

	
    return $error;
	}



}
?>
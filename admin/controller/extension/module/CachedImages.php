<?php
class ControllerExtensionModuleCachedImages extends Controller {
	private $error = array();

	public function getDirContents($dir, $filter = '', &$results = array()) {

		$files = scandir($dir);
		
    	foreach($files as $key => $value){
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value); 

        if(!is_dir($path)) {
            if(empty($filter) || preg_match($filter, $path)) {
                
					
            	$results[] = $path;
            	
           
			}
        } elseif($value != "." && $value != "..") {
            $this->getDirContents($path, $filter, $results);
        }
    }

    return $results;
}

	public function getTotalImages() {		

	return (count($this->getDirContents(DIR_IMAGE.'catalog'.DIRECTORY_SEPARATOR)));

}

	public function getTotalCacheImages() {		

	return (count($this->getDirContents(DIR_IMAGE.'cache'.DIRECTORY_SEPARATOR)));

}

	public function getTotalProductImgImages() {

		$result = $this->db->query("SELECT COUNT(image) AS total FROM `" . DB_PREFIX . "product_image`");

        return $result->row['total'];

	}
	
	public function getTotalProductImages() {

		$result = $this->db->query("SELECT COUNT(image) AS total FROM `" . DB_PREFIX . "product` WHERE image != '' " );

        return $result->row['total'];

	}
	
	public function getTotalCategoryImages() {

		$result = $this->db->query("SELECT COUNT(image) AS total FROM `" . DB_PREFIX . "category` WHERE image != '' " );

        return $result->row['total'];

	}

	public function deleteCachedImages($skip = false) {
		
		$files = $this->getDirContents(DIR_IMAGE.'cache'.DIRECTORY_SEPARATOR);
		foreach($files as $file){ // iterate files
		if(is_file($file))
		unlink($file); // delete file
		}
		
		if($skip) return;

		$this->session->data['success'] = 'Deleted';
		$this->response->redirect($this->url->link('extension/module/CachedImages', 'token=' . $this->session->data['token'] . '&type=module', true));

	}
	
	public function optimzieCachedImages(){
		set_time_limit(0);

		$this->deleteCachedImages(true);
		$files = $this->getDirContents(DIR_IMAGE);
		
		$ch = curl_init('http://api.resmush.it/ws.php');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		
		//Supported File Format: PNG, JPG, GIF, BMP and TIF
		
		foreach($files as $index => $file) {
			if( !strpos($file, '.htaccess') && 
				!strpos($file, '.pdf') && 
				!strpos($file, '/.') && 
				!strpos($file, '\.') && 
				!strpos($file, '.svg') &&
				!strpos($file, '.html') && 
				!strpos($file, '.php') && 
				!strpos($file, '.ico') 
			){
				curl_setopt($ch, CURLOPT_POSTFIELDS, array(
					'files'	=>	new CURLFile($file)
				));

				$data = curl_exec($ch);

				$json = json_decode($data);

				if(!isset($json->error))
					file_put_contents($file, $json->dest);
				//else
				//	debug($file);
			}
			
			// End Foreach

		}
		curl_close($ch);
		
		
	}

	public function index() {
		$this->load->language('extension/module/CachedImages');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_extension_module->addModule('CachedImages', $this->request->post);
			} else {
				$this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		}

		$data['images_total'] = $this->getTotalImages();

		$data['images_cache_total'] = $this->getTotalCacheImages();

		$data['images_product_total'] =(int)$this->getTotalProductImgImages()+(int)$this->getTotalProductImages()+(int)$this->getTotalCategoryImages();

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['delete_cached_image'] = $this->url->link('extension/module/CachedImages/deleteCachedImages', 'token=' . $this->session->data['token'], true);
		$data['optimize_cached_image'] = $this->url->link('extension/module/CachedImages/optimzieCachedImages', 'token=' . $this->session->data['token'], true);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/CachedImages', 'token=' . $this->session->data['token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/CachedImages', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/CachedImages', 'token=' . $this->session->data['token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/CachedImages', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true);
		}

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}
        
		 

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/CachedImages', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/CachedImages')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		return !$this->error;
	}
}
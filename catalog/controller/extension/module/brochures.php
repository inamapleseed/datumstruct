<?php
class ControllerExtensionModuleBrochures extends Controller {
	public function index() {
		// Handle custom fields
		$oc = $this;
		$language_id = $this->config->get('config_language_id');
		$modulename  = 'brochures';
	    $this->load->library('modulehelper');
	    $Modulehelper = Modulehelper::get_instance($this->registry);

        $this->load->model('account/download');
        $files =  $this->model_account_download->getAllDownloads();
        $file_array = array();
        foreach ($files as $file) {
            $file_array[] = array(
                    "download_id" => $file['download_id'],
                    "name"        => $file['name'],
                    "description" => $file['description'],
                    "image"        => $file['image'],
                    "link"        => $this->url->link('account/download/download2', 'download_id='.$file['download_id'])
            );
        }
        $data['files'] = $file_array;

		return $this->load->view('extension/module/brochures', $data);
	}

    public function brochures4home() {
        $oc = $this;
        $language_id = $this->config->get('config_language_id');
        $modulename  = 'brochures';
        $this->load->library('modulehelper');
        $Modulehelper = Modulehelper::get_instance($this->registry);

        $this->load->model('account/download');
        $files =  $this->model_account_download->getAllDownloads();
        $file_array = array();
        foreach ($files as $file) {
            $file_array[] = array(
                    "download_id" => $file['download_id'],
                    "name"        => $file['name'],
                    "description" => $file['description'],
                    "image" => $file['image'],
                    "link"        => $this->url->link('account/download/download2', 'download_id='.$file['download_id'])
            );
        }
        $data['files'] = $file_array;
        return $data;
    }
}
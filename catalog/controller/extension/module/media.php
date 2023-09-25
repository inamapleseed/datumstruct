<?php
class ControllerExtensionModuleMedia extends Controller {
	public function index() {
		// Handle custom fields
		$oc = $this;
		$language_id = $this->config->get('config_language_id');
		$modulename  = 'media';
	    $this->load->library('modulehelper');
	    $Modulehelper = Modulehelper::get_instance($this->registry);

		$data['items'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'items' );
		$data['social_icons'] = $this->load->controller('component/social_icons');

		return $this->load->view('extension/module/media', $data);

	}
	public function media() {
		// Handle custom fields
		$oc = $this;
		$language_id = $this->config->get('config_language_id');
		$modulename  = 'media';
	    $this->load->library('modulehelper');
	    $Modulehelper = Modulehelper::get_instance($this->registry);

		$data['items'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'items' );
		
		return $data;
	}
	
}
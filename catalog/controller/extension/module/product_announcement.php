<?php
class ControllerExtensionModuleProductAnnouncement extends Controller {
	public function index() {
		// Handle custom fields
		$oc = $this;
		$language_id = $this->config->get('config_language_id');
		$modulename  = 'product_announcement';
	    $this->load->library('modulehelper');
	    $Modulehelper = Modulehelper::get_instance($this->registry);

		$data['text'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'text' );

		return $this->load->view('extension/module/product_announcement', $data);
	}
		public function announcement() {
		// Handle custom fields
		$oc = $this;
		$language_id = $this->config->get('config_language_id');
		$modulename  = 'product_announcement';
	    $this->load->library('modulehelper');
	    $Modulehelper = Modulehelper::get_instance($this->registry);

		$data['text'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'text' );

		return $data;
	}
	
}
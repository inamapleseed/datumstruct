<?php
class ControllerExtensionModuleAboutUs extends Controller {
	public function index() {
		// Handle custom fields
		$oc = $this;
		$language_id = $this->config->get('config_language_id');
		$modulename  = 'aboutus';
	    $this->load->library('modulehelper');
	    $Modulehelper = Modulehelper::get_instance($this->registry);

		$data['items'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'items' );
		// $data['cinfo'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'cinfo');

		return $this->load->view('extension/module/aboutus', $data);
	}
		public function about() {
		// Handle custom fields
		$oc = $this;
		$language_id = $this->config->get('config_language_id');
		$modulename  = 'aboutus';
	    $this->load->library('modulehelper');
	    $Modulehelper = Modulehelper::get_instance($this->registry);

		$data['items'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'items' );


		return $data;
	}
	
}
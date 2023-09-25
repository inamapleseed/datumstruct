<?php
class ControllerExtensionModuleProductFeatures extends Controller {
	public function index() {
		// Handle custom fields
		$oc = $this;
		$language_id = $this->config->get('config_language_id');
		$modulename  = 'product_features';
	    $this->load->library('modulehelper');
	    $Modulehelper = Modulehelper::get_instance($this->registry);

		$data['items'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'items' );

		return $this->load->view('extension/module/product_features', $data);
	}
		public function features() {
		// Handle custom fields
		$oc = $this;
		$language_id = $this->config->get('config_language_id');
		$modulename  = 'product_features';
	    $this->load->library('modulehelper');
	    $Modulehelper = Modulehelper::get_instance($this->registry);

		$data['items'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'items' );

		return $data;
	}
	
}
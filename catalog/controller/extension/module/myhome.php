<?php
class ControllerExtensionModuleMyHome extends Controller {
	public function index() {
		// Handle custom fields
		$oc = $this;
		$language_id = $this->config->get('config_language_id');
		$modulename  = 'myhome';
	    $this->load->library('modulehelper');
	    $Modulehelper = Modulehelper::get_instance($this->registry);

		$data['heading'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'heading' );
		$data['desc'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'desc' );
		$data['image'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'image' );

		return $this->load->view('extension/module/myhome', $data);
	}

}
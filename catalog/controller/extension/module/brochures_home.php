<?php
class ControllerExtensionModuleBrochuresHome extends Controller {
	public function index() {
		// Handle custom fields
		$oc = $this;
		$language_id = $this->config->get('config_language_id');
		$modulename  = 'brochures_home';
	    $this->load->library('modulehelper');
	    $Modulehelper = Modulehelper::get_instance($this->registry);

		$data['brochures'] = $this->load->controller('extension/module/brochures/brochures_home');

// 		$data['items'] = $this->load->controller('extension/module/media/media');
		$data['files'] = $this->load->controller('extension/module/brochures/brochures4home');

		$data['repeater'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'repeater' );

		return $this->load->view('extension/module/brochures_home', $data);
	}

}
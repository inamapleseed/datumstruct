<?php
class ControllerExtensionModuleHumanScale extends Controller {
	public function index() {
		// Handle custom fields
		$oc = $this;
		$language_id = $this->config->get('config_language_id');
		$modulename  = 'humanscale';
	    $this->load->library('modulehelper');
	    $Modulehelper = Modulehelper::get_instance($this->registry);

		$data['bg'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'bg' );
		$data['btitle'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'btitle' );
		$data['stitle'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'stitle' );

		return $this->load->view('extension/module/humanscale', $data);
	}
		public function migrate() {
		// Handle custom fields
		$oc = $this;
		$language_id = $this->config->get('config_language_id');
		$modulename  = 'humanscale';
	    $this->load->library('modulehelper');
	    $Modulehelper = Modulehelper::get_instance($this->registry);

		$data['bg'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'bg' );
		$data['btitle'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'btitle' );
		$data['stitle'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'stitle' );


		return $data;
	}
	
}
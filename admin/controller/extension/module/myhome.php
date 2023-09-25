<?php
class ControllerExtensionModuleMyHome extends Controller {
	public function index() {
		$array = array(
            'oc' => $this,
            'heading_title' => 'Home (Custom)',
            'modulename' => 'myhome',
            'fields' => array(
                array('type' => 'text', 'label' => 'Foreword Heading', 'name' => 'heading'),
                array('type' => 'text', 'label' => 'Foreword Description', 'name' => 'desc'),
                array('type' => 'image', 'label' => 'Foreword Image', 'name' => 'image'),

          )
        );
        $this->modulehelper->init($array);    
	}
}

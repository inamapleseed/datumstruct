<?php
class ControllerExtensionModulePartners extends Controller {
	public function index() {
		$array = array(
            'oc' => $this,
            'heading_title' => 'Partners',
            'modulename' => 'partners',
            'fields' => array(
                array('type' => 'repeater', 'label' => 'Items', 'name' => 'items',
                    'fields' => array(
                array('type' => 'text', 'label' => 'Partner Name', 'name' => 'name'),
                array('type' => 'image', 'label' => 'Image', 'name' => 'image'),
                ),
            ),
          )
        );
        $this->modulehelper->init($array);    
	}
}

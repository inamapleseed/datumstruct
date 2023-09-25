<?php
class ControllerExtensionModuleBrochures extends Controller {
	public function index() {
		$array = array(
            'oc' => $this,
            'heading_title' => 'Brochures',
            'modulename' => 'brochures',
            'fields' => array(
                array('type' => 'repeater', 'label' => 'Items', 'name' => 'items',
                    'fields' => array(
                // array('type' => 'image', 'label' => 'Icon', 'name' => 'image'),
                // array('type' => 'text', 'label' => 'Label', 'name' => 'label'),
                    )
                ),
            ),
        );
        $this->modulehelper->init($array);    
	}
    
}

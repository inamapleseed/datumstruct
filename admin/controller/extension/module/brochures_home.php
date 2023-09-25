<?php
class ControllerExtensionModuleBrochuresHome extends Controller {
	public function index() {
		$array = array(
            'oc' => $this,
            'heading_title' => 'Brochures / Home',
            'modulename' => 'brochures_home',
            'fields' => array(
                array('type' => 'repeater', 'label' => 'Content Repeater', 'name' => 'repeater', 'fields' => array(
                    array('type' => 'image', 'label' => 'Image', 'name' => 'image'),
                )),
            )
        );
        $this->modulehelper->init($array);    
	}
}

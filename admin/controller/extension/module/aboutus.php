<?php
class ControllerExtensionModuleAboutUs extends Controller {
	public function index() {
		$array = array(
            'oc' => $this,
            'heading_title' => 'About Us',
            'modulename' => 'aboutus',
            'fields' => array(
                array('type' => 'repeater', 'label' => 'Items', 'name' => 'items',
                    'fields' => array(
                array('type' => 'text', 'label' => 'Tab Title', 'name' => 'tab'),
                array('type' => 'textarea', 'label' => 'Description', 'name' => 'desc', 'ckeditor' =>true),
                array('type' => 'image', 'label' => 'Image', 'name' => 'image'),
                ),
            ),
          )
        );
        $this->modulehelper->init($array);    
	}
}

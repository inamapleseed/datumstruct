<?php
class ControllerExtensionModuleMedia extends Controller {
	public function index() {
		$array = array(
            'oc' => $this,
            'heading_title' => 'Media',
            'modulename' => 'media',
            'fields' => array(
                array('type' => 'repeater', 'label' => 'Items', 'name' => 'items',
                    'fields' => array(
                array('type' => 'textarea', 'label' => 'Facebook Embed Link', 'name' => 'desc', 'ckeditor' =>true),
                ),
            ),
          )
        );
        $this->modulehelper->init($array);    
	}
}

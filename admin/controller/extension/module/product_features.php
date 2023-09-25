<?php
class ControllerExtensionModuleProductFeatures extends Controller {
	public function index() {
		$array = array(
            'oc' => $this,
            'heading_title' => 'Product Features',
            'modulename' => 'product_features',
            'fields' => array(
                array('type' => 'repeater', 'label' => 'Items', 'name' => 'items',
                    'fields' => array(
                array('type' => 'image', 'label' => 'Icon/Image', 'name' => 'image'),
                array('type' => 'text', 'label' => 'Brief Description', 'name' => 'desc'),
                ),
            ),
          )
        );
        $this->modulehelper->init($array);    
	}
}

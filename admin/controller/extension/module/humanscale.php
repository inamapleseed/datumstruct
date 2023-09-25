<?php
class ControllerExtensionModuleHumanScale extends Controller {
	public function index() {
		$array = array(
            'oc' => $this,
            'heading_title' => 'Humanscale',
            'modulename' => 'humanscale',
            'fields' => array(
                    array('type' => 'image', 'label' => 'Background', 'name' => 'bg'),
                    array('type' => 'text', 'label' => 'Brand Title', 'name' => 'btitle'),
                    array('type' => 'text', 'label' => 'Sub Title', 'name' => 'stitle'),
                array('type' => 'repeater', 'label' => 'Items', 'name' => 'pop_items',
                    'fields' => array(
                    array('type' => 'image', 'label' => 'Product Image', 'name' => 'pimage'),
                    array('type' => 'text', 'label' => 'Product Name', 'name' => 'pname'),
                    array('type' => 'text', 'label' => 'Price', 'name' => 'price'),
                    )
                ),
            ),
        );
        $this->modulehelper->init($array);    
	}
}

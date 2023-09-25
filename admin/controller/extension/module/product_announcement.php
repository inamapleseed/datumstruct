<?php
class ControllerExtensionModuleProductAnnouncement extends Controller {
	public function index() {
		$array = array(
            'oc' => $this,
            'heading_title' => 'Product Announcement',
            'modulename' => 'product_announcement',
            'fields' => array(
                array('type' => 'text', 'label' => 'Announcement', 'name' => 'text'),
             )
        );
        $this->modulehelper->init($array);    
	}
}

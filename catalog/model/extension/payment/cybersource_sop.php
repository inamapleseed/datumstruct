<?php
class ModelExtensionPaymentCybersourceSop extends Model {
  	public function getMethod($address, $total = false) {

		$extension_type = 'extension/payment';
		$classname = str_replace('vq2-', '', str_replace(basename(DIR_APPLICATION) . '_' . strtolower(get_parent_class($this)) . '_' . $extension_type . '_', '', basename(__FILE__, '.php')));

		$this->load->language($extension_type . '/' . $classname);

		// v14x backwards compatible
		if ($total === false) { $total = $this->cart->getTotal(); }

		// Return if total is used and too low
		if ($this->config->get($classname . '_total')) { if ($this->config->get($classname . '_total') > $total) { return array(); } }

		if ($this->config->get($classname . '_status')) {

			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get($classname . '_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

			if (!$this->config->get($classname . '_geo_zone_id')) {
        		$status = TRUE;
      		} elseif ($query->num_rows) {
      		  	$status = TRUE;
      		} else {
     	  		$status = FALSE;
			}
      	} else {
			$status = FALSE;
		}

		$method_data = array();

		if ($status) {
      		$method_data = array(
				'code'		 => $classname,
        		'title'      => ($this->config->get($classname . '_title_' . $this->config->get('config_language_id')) ? $this->config->get($classname . '_title_' . $this->config->get('config_language_id')) : ucwords(str_replace(array('-','_','.'), " ", $classname))),
        		'terms'		 => '',
				'sort_order' => $this->config->get($classname . '_sort_order')
      		);
    	}

    	return $method_data;
  	}
}
?>
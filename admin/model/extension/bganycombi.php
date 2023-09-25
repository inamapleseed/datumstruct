<?php
class ModelExtensionBganycombi extends Model {
	public function addbganycombi($data) {
		$buyproduct = (isset($data['buyproduct']) && $data['buyproduct']) ? implode(",",$data['buyproduct']) : '';
		$buycategory = (isset($data['buycategory']) && $data['buycategory']) ? implode(",",$data['buycategory']) : '';
		$buymanufacturer = (isset($data['buymanufacturer']) && $data['buymanufacturer']) ? implode(",",$data['buymanufacturer']) : '';	
		$getproduct = (isset($data['getproduct']) && $data['getproduct']) ? implode(",",$data['getproduct']) : '';
		$getcategory = (isset($data['getcategory']) && $data['getcategory']) ? implode(",",$data['getcategory']) : '';
		$getmanufacturer = (isset($data['getmanufacturer']) && $data['getmanufacturer']) ? implode(",",$data['getmanufacturer']) : '';	
		$customer_group = (isset($data['customer_group']) && $data['customer_group']) ? implode(",",$data['customer_group']) : '';
		$store = (isset($data['store']) && $data['store'] != '') ? implode(",",$data['store']) : ''; 
		//print_r($store);exit;
 
		$this->db->query("INSERT INTO " . DB_PREFIX . "bganycombi SET name = '" . $this->db->escape($data['name']) . "', discount_type = '" . (int)$data['discount_type'] . "', discount_value = '" . (float)$data['discount_value'] . "', buyqty = '" . (int)$data['buyqty'] . "', getqty = '" . (int)$data['getqty'] . "', `ribbontext` = '" . $this->db->escape(json_encode($data['ribbontext'], true)) . "',  `ordertotaltext` = '" . $this->db->escape(json_encode($data['ordertotaltext'], true)) . "', display_offer_at = '" . (int)$data['display_offer_at'] . "', `offer_heading_text` = '" . $this->db->escape(json_encode($data['offer_heading_text'], true)) . "', `offer_content` = '" . $this->db->escape(json_encode($data['offer_content'], true)) . "', buyproduct = '" . $this->db->escape($buyproduct) . "', buycategory = '" . $this->db->escape($buycategory) . "', buymanufacturer = '" . $this->db->escape($buymanufacturer) . "', getproduct = '" . $this->db->escape($getproduct) . "', getcategory = '" . $this->db->escape($getcategory) . "', getmanufacturer = '" . $this->db->escape($getmanufacturer) . "', customer_group = '" . $this->db->escape($customer_group) . "', store = '" . $this->db->escape($store) . "', status = '" . (int)$data['status'] . "', date_start = '" . $this->db->escape(date("Y-m-d",strtotime($data['date_start']))) . "', date_end = '" . $this->db->escape(date("Y-m-d",strtotime($data['date_end']))) . "' ");

		$bganycombi_id = $this->db->getLastId();
    
 		$this->cache->delete('bganycombi');

		return $bganycombi_id;
	}

	public function editbganycombi($bganycombi_id, $data) {
		$buyproduct = (isset($data['buyproduct']) && $data['buyproduct']) ? implode(",",$data['buyproduct']) : '';
		$buycategory = (isset($data['buycategory']) && $data['buycategory']) ? implode(",",$data['buycategory']) : '';
		$buymanufacturer = (isset($data['buymanufacturer']) && $data['buymanufacturer']) ? implode(",",$data['buymanufacturer']) : '';	
		$getproduct = (isset($data['getproduct']) && $data['getproduct']) ? implode(",",$data['getproduct']) : '';
		$getcategory = (isset($data['getcategory']) && $data['getcategory']) ? implode(",",$data['getcategory']) : '';
		$getmanufacturer = (isset($data['getmanufacturer']) && $data['getmanufacturer']) ? implode(",",$data['getmanufacturer']) : '';	
		$customer_group = (isset($data['customer_group']) && $data['customer_group']) ? implode(",",$data['customer_group']) : '';
		$store = (isset($data['store']) && $data['store'] != '') ? implode(",",$data['store']) : ''; 
		
		$this->db->query("UPDATE " . DB_PREFIX . "bganycombi SET name = '" . $this->db->escape($data['name']) . "', discount_type = '" . (int)$data['discount_type'] . "', discount_value = '" . (float)$data['discount_value'] . "', buyqty = '" . (int)$data['buyqty'] . "', getqty = '" . (int)$data['getqty'] . "', `ribbontext` = '" . $this->db->escape(json_encode($data['ribbontext'], true)) . "',  `ordertotaltext` = '" . $this->db->escape(json_encode($data['ordertotaltext'], true)) . "', display_offer_at = '" . (int)$data['display_offer_at'] . "', `offer_heading_text` = '" . $this->db->escape(json_encode($data['offer_heading_text'], true)) . "', `offer_content` = '" . $this->db->escape(json_encode($data['offer_content'], true)) . "', buyproduct = '" . $this->db->escape($buyproduct) . "', buycategory = '" . $this->db->escape($buycategory) . "', buymanufacturer = '" . $this->db->escape($buymanufacturer) . "', getproduct = '" . $this->db->escape($getproduct) . "', getcategory = '" . $this->db->escape($getcategory) . "', getmanufacturer = '" . $this->db->escape($getmanufacturer) . "', customer_group = '" . $this->db->escape($customer_group) . "', store = '" . $this->db->escape($store) . "', status = '" . (int)$data['status'] . "', date_start = '" . $this->db->escape(date("Y-m-d",strtotime($data['date_start']))) . "', date_end = '" . $this->db->escape(date("Y-m-d",strtotime($data['date_end']))) . "' WHERE bganycombi_id = '" . (int)$bganycombi_id . "'");
				 
 		$this->cache->delete('bganycombi');
	}

	public function deletebganycombi($bganycombi_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "bganycombi WHERE bganycombi_id = '" . (int)$bganycombi_id . "'");
  
		$this->cache->delete('bganycombi');
	}

	public function getbganycombi($bganycombi_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bganycombi WHERE bganycombi_id = '" . (int)$bganycombi_id . "' ");

		return $query->row;
	}

	public function getbganycombis($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "bganycombi WHERE 1 ";

		if (!empty($data['filter_name'])) {
			$sql .= " AND name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		
		if (!empty($data['filter_customer_group_id'])) {
			$sql .= " AND find_in_set(".$data['filter_customer_group_id'].", customer_group)";
		}
		
		if (!empty($data['filter_discount_type'])) {
			$sql .= " AND discount_type = " . $data['filter_discount_type'] . "";
		} 
		
		if (!empty($data['filter_buyqty'])) {
			$sql .= " AND buyqty = " . $data['filter_buyqty'] . "";
		}
		
		if (!empty($data['filter_getqty'])) {
			$sql .= " AND getqty = " . $data['filter_getqty'] . "";
		} 
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(date_start) = DATE('" . $this->db->escape($data['filter_date_start']) . "')";
		}
		
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(date_end) = DATE('" . $this->db->escape($data['filter_date_end']) . "')";
		}
		
		if (!empty($data['filter_buyproduct_id'])) {
			$sql .= " AND find_in_set(".$data['filter_buyproduct_id'].", buyproduct)";
		}
		
		if (!empty($data['filter_buycategory_id'])) {
			$sql .= " AND find_in_set(".$data['filter_buycategory_id'].", buycategory)";
		}
		
		if (!empty($data['filter_buymanufacturer_id'])) {
			$sql .= " AND find_in_set(".$data['filter_buymanufacturer_id'].", buymanufacturer)";
		}
		
		if (!empty($data['filter_getproduct_id'])) {
			$sql .= " AND find_in_set(".$data['filter_getproduct_id'].", getproduct)";
		}
		
		if (!empty($data['filter_getcategory_id'])) {
			$sql .= " AND find_in_set(".$data['filter_getcategory_id'].", getcategory)";
		}
		
		if (!empty($data['filter_getmanufacturer_id'])) {
			$sql .= " AND find_in_set(".$data['filter_getmanufacturer_id'].", getmanufacturer)";
		}

		$sql .= " GROUP BY bganycombi_id";

		$sort_data = array(
			'name',
			'bganycombi_id',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
 
	public function getTotalbganycombis($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "bganycombi WHERE 1 ";
  		
		if (!empty($data['filter_name'])) {
			$sql .= " AND name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		
		if (!empty($data['filter_customer_group_id'])) {
			$sql .= " AND find_in_set(".$data['filter_customer_group_id'].", customer_group)";
		}
		
		if (!empty($data['filter_discount_type'])) {
			$sql .= " AND discount_type = " . $data['filter_discount_type'] . "";
		}
		
		if (!empty($data['filter_buyqty'])) {
			$sql .= " AND buyqty = " . $data['filter_buyqty'] . "";
		}
		
		if (!empty($data['filter_getqty'])) {
			$sql .= " AND getqty = " . $data['filter_getqty'] . "";
		}
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(date_start) = DATE('" . $this->db->escape($data['filter_date_start']) . "')";
		}
		
		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(date_end) = DATE('" . $this->db->escape($data['filter_date_end']) . "')";
		}
		
		if (!empty($data['filter_buyproduct_id'])) {
			$sql .= " AND find_in_set(".$data['filter_buyproduct_id'].", buyproduct)";
		}
		
		if (!empty($data['filter_buycategory_id'])) {
			$sql .= " AND find_in_set(".$data['filter_buycategory_id'].", buycategory)";
		}
		
		if (!empty($data['filter_buymanufacturer_id'])) {
			$sql .= " AND find_in_set(".$data['filter_buymanufacturer_id'].", buymanufacturer)";
		}
		
		if (!empty($data['filter_getproduct_id'])) {
			$sql .= " AND find_in_set(".$data['filter_getproduct_id'].", getproduct)";
		}
		
		if (!empty($data['filter_getcategory_id'])) {
			$sql .= " AND find_in_set(".$data['filter_getcategory_id'].", getcategory)";
		}
		
		if (!empty($data['filter_getmanufacturer_id'])) {
			$sql .= " AND find_in_set(".$data['filter_getmanufacturer_id'].", getmanufacturer)";
		}
		
		$query = $this->db->query($sql);

		return $query->row['total'];
	} 
}
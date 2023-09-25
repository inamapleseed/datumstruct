<?php
class ModelExtensionModuleProductSortOrders extends Model {
	
	public function saveSortOrders($data) {
		$category_id = $data['category_id'];
		
		if(isset($category_id) && !empty($category_id)) {
			foreach($data as $key => $value) {
				if(preg_match('/sort_order_\d*/',$key)) {
					$product_id = str_replace('sort_order_', '', $key);
					$sort_order = $value;
					
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category_order WHERE category_id = " . (int)$category_id . " AND product_id = ".(int)$product_id.";");
					if($query->num_rows == 0) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category_order SET sort_order = ".(int)$sort_order.", category_id = " . (int)$category_id . ", product_id = ".(int)$product_id.";");
					} else {
						$this->db->query("UPDATE " . DB_PREFIX . "product_to_category_order SET sort_order = ".(int)$sort_order." WHERE category_id = " . (int)$category_id . " AND product_id = ".(int)$product_id.";");
					}
				}
			}
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category_order WHERE category_id = '" . (int)$category_id . "' AND product_id NOT IN (SELECT product_id FROM " . DB_PREFIX . "product_to_category WHERE category_id = '" . (int)$category_id . "');");
		}
	}
	
	public function getProducts($data = array()) {
		if ($data) {
	
			$sql = "
				SELECT
					p.product_id,
					pd.name,
					p.model,
					p.price,
					p2co.sort_order,
					p.quantity,
					p.status,
					p.image
				FROM " . DB_PREFIX . "product p
				LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category_order p2co ON (p.product_id = p2co.product_id AND p2c.category_id = p2co.category_id)";
			$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'"; 
			
			if (!empty($data['filter_category'])) {
				if (!empty($data['filter_sub_category'])) {
					$implode_data = array();
					
					$implode_data[] = "category_id = '" . (int)$data['filter_category_id'] . "'";
					
					$this->load->model('catalog/category');
					
					$categories = $this->model_catalog_category->getCategories($data['filter_category_id']);
					
					foreach ($categories as $category) {
						$implode_data[] = "p2c.category_id = '" . (int)$category['category_id'] . "'";
					}
					
					$sql .= " AND (" . implode(' OR ', $implode_data) . ")";			
				} else {
					$sql .= " AND p2c.category_id = '" . (int)$data['filter_category'] . "'";
				}
			}
			
			$sql .= " GROUP BY p.product_id";
						
			$sort_data = array(
				'pd.name',
				'p.model',
				'p.price',
                'p2c.category_id',
				'manu.name',
				'p.quantity',
				'p.status',
				'p2co.sort_order,p.sort_order'
			);
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY pd.name";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC, LCASE(pd.name) DESC";
			} else {
				$sql .= " ASC, LCASE(pd.name) ASC";
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
	}
	
	
	public function getProductCategories($product_id) {
		$product_category_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_category_data[] = $result['category_id'];
		}

		return $product_category_data;
	}
	
	public function getProductSpecials($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' ORDER BY priority, price");
		
		return $query->rows;
	}
	
	public function install() {
		$sql = "DROP TABLE IF EXISTS ".DB_PREFIX."product_to_category_order;";
		$this->db->query($sql);
		
		$sql = "
			CREATE TABLE ".DB_PREFIX."product_to_category_order (
				product_id int(11) NOT NULL,
				category_id int(11) NOT NULL,
				sort_order int(11) NOT NULL DEFAULT '0',
				PRIMARY KEY (product_id,category_id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$this->db->query($sql);

		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('product_category_sort_order', array('product_category_sort_order_status' => 1));
	}
	
	public function uninstall() {
		$sql = "DROP TABLE IF EXISTS ".DB_PREFIX."product_to_category_order;";
		$this->db->query($sql);

		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('product_category_sort_order', array('product_category_sort_order_status' => 0));
	}
}

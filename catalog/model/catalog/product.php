<?php
class ModelCatalogProduct extends Model {

	public function getProductMainCategories($product_id, $level = 0){
		$query= $this->db->query('SELECT cp.path_id FROM `' . DB_PREFIX . 'category_path` cp LEFT JOIN `' . DB_PREFIX . 'product_to_category` p2c ON (cp.category_id = p2c.category_id) WHERE p2c.product_id="' . (int)$product_id . '" AND cp.level="' . (int)$level . '"');

		return $query->rows;
	}
	public function getProductMainCategories1($product_id, $level = 0){
			$query= $this->db->query('SELECT * FROM `' . DB_PREFIX . 'category_path` cp LEFT JOIN `' . DB_PREFIX . 'product_to_category` p2c ON (cp.category_id = p2c.category_id) LEFT JOIN `' . DB_PREFIX . 'category_description` cd ON (cp.category_id = cd.category_id) WHERE p2c.product_id="' . (int)$product_id . '" AND cp.level="' . (int)$level . '"');

			return $query->rows;
		}	
	public function getOptionAvailability($product_id = 0, $product_option_value_id = 0, $quantity=0){
		$sql = 'SELECT quantity FROM `' . DB_PREFIX . 'product_option_value` WHERE product_id="' . (int)$product_id . '" AND product_option_value_id="' . (int)$product_option_value_id . '" AND quantity >= "'.(int)$quantity.'"';

		$query = $this->db->query($sql);

		return $query->num_rows;
	}
	public function updateViewed($product_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET viewed = (viewed + 1) WHERE product_id = '" . (int)$product_id . "'");
	}
	
	public function getProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, 
			(SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, 
			(SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, 
			(SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
		
		if ($query->num_rows) {
				// if ($this->config->get('discounts_status')) {
				// 	$this->load->model('catalog/discount');
				// 	$query->row['special'] = $this->model_catalog_discount->specialPriceOverride($query->row['product_id'],$query->row['special'],$query->row['price'],$query->row['discount'],$this->config->get('config_customer_group_id'));
				// }

			return array(
				'product_id'       => $query->row['product_id'],
				'name'             => $query->row['name'],
				'description'      => $query->row['description'],
				'meta_title'       => $query->row['meta_title'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'tag'              => $query->row['tag'],
				'model'            => $query->row['model'],
				'sku'              => $query->row['sku'],
				'upc'              => $query->row['upc'],
				'ean'              => $query->row['ean'],
				'jan'              => $query->row['jan'],
				'isbn'             => $query->row['isbn'],
				'mpn'              => $query->row['mpn'],
				'location'         => $query->row['location'],
				'quantity'         => $query->row['quantity'],
				'stock_status'     => $query->row['stock_status'],
				'image'            => $query->row['image'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'manufacturer'     => $query->row['manufacturer'],
				'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
				'special'          => $query->row['special'],
				'reward'           => $query->row['reward'],
				'points'           => $query->row['points'],
				'tax_class_id'     => $query->row['tax_class_id'],
				'date_available'   => $query->row['date_available'],
				'weight'           => $query->row['weight'],
				'weight_class_id'  => $query->row['weight_class_id'],
				'length'           => $query->row['length'],
				'width'            => $query->row['width'],
				'height'           => $query->row['height'],
				'length_class_id'  => $query->row['length_class_id'],
				'subtract'         => $query->row['subtract'],
				'rating'           => round($query->row['rating']),
				'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
				'minimum'          => $query->row['minimum'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified'],
				// Start products or gallery
				'products_category'=> $query->row['products_category'],
				// End products or gallery				
				'viewed'           => $query->row['viewed']
			);
		}
		else {
			return false;
		}
	}
	
	public function getProducts($data = array(), $sub_query = false) {

		$sql = "SELECT DISTINCT p.product_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";
		
		if($sub_query) $sql = 'SELECT DISTINCT p.product_id ';

		if (!empty($data['filter_category_id']) && $data['filter_category_id']) {

			$data['filter_category_id'] = explode('_', $data['filter_category_id']);
			$data['filter_category_id'] = end($data['filter_category_id']);

			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			}
			else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}
			
			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";

				if($this->config->get('product_category_sort_order_status')){
					$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category_order p2co ON (pf.product_id = p2co.product_id AND p2c.category_id = p2co.category_id)";
				}
			}
			else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";

				if($this->config->get('product_category_sort_order_status')){
					$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category_order p2co ON (p.product_id = p2co.product_id AND p2c.category_id = p2co.category_id)";
				}
			}
		}
		else {
			$sql .= " FROM " . DB_PREFIX . "product p";

			$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p.product_id = pf.product_id) ";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		
		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			}
			else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}
		}

		if (!empty($data['filter_filter'])) {
			$implode = array();
			
			$filters = explode(',', $data['filter_filter']);
			
			foreach ($filters as $filter_id) {
				$implode[] = (int)$filter_id;
			}
			
			$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";

				// FixFilter
			$minq=$this->db->query("
				SELECT id.product_id FROM
				(SELECT 
				product_id, count(i.product_id) as t, c.filter_group as b
				FROM
				(SELECT 
				f.filter_id, f.product_id, p.filter_group_id
				FROM
				". DB_PREFIX ."product_filter f
				LEFT JOIN ". DB_PREFIX ."filter p ON p.filter_id = f.filter_id
				WHERE
				p.filter_id IN (" . implode(',', $implode) . ")
				GROUP BY product_id , filter_group_id) as i,
				(SELECT 
				COUNT(distinct filter_group_id) as filter_group
				FROM
				". DB_PREFIX ."filter_description f_b
				WHERE f_b.filter_id in (" . implode(',', $implode) . ")) as c
				GROUP BY product_id
				HAVING t = b) as id");
			
			$min=array();
			
			for($x=0;$x<count($minq->rows);$x++){	
				
				foreach($minq->rows[$x] as $value){
					$min[]=$value;
				}
			}
			
			$imp=implode(',',$min);
				// FixFilter END
		}
					// Start products or gallery
		if (!empty($data['filter_main_product'])) {
			$sql .= " AND p.products_category = '" . $data['filter_main_product'] ."'";
		}
		// End products or gallery
		
		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";
			
			if (!empty($data['filter_name'])) {
				$implode = array();
				
				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));
				
				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}
				
				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
				
				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}
			
			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}
			
			if (!empty($data['filter_tag'])) {
				$implode = array();
				
				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));
				
				foreach ($words as $word) {
					$implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";
				}
				
				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}
			
			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}
			
			$sql .= ")";
		}
		
		if ( isset($data['filter_manufacturer']) && $data['filter_manufacturer'] ) {
			
			$manufacturer_ids = explode(',', $data['filter_manufacturer']);

			foreach($manufacturer_ids as &$filter_manufacturer_id){
				$filter_manufacturer_id = (int)$this->db->escape($filter_manufacturer_id);
			}
			$sql .= " AND p.manufacturer_id IN ('" . implode("','", $manufacturer_ids) . "') ";
		}

			// FixFilter
		if(isset($filters)){
			if(!count($min)){
				$imp='0000000';
			}
			$sql .= " AND p.product_id IN(".$imp.")";
		}
			// FixFilter END

		if(	isset($data['price_min']) && 
			isset($data['price_max']) && 
			$data['price_min'] > -1 &&  
			$data['price_max'] >= $data['price_min'] &&
			$data['price_max'] > 0
		){
			
			$price_min = $data['price_min'];
		$price_max = $data['price_max'];

		if($this->config->get('config_currency') != $this->session->data['currency']){
			
			$price_min = $this->currency->convert($data['price_min'], $this->session->data['currency'], $this->config->get('config_currency'));
			$price_max = $this->currency->convert($data['price_max'], $this->session->data['currency'], $this->config->get('config_currency'));
			
		}
		
		$std_conditions = "
		DB_REF.product_id = p.product_id AND 
		DB_REF.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND 
		((DB_REF.date_start = '0000-00-00' OR DB_REF.date_start < NOW()) AND (DB_REF.date_end = '0000-00-00' OR DB_REF.date_end > NOW()))
		ORDER BY DB_REF.priority ASC, DB_REF.price ASC LIMIT 1";
		
		$sql .= " AND ("; 

		$sql .= "(";
		$sql .= "	CASE";
		$sql .= "		WHEN (SELECT cps.price FROM `" . DB_PREFIX . "product_special` cps WHERE " . str_replace('DB_REF', 'cps', $std_conditions) . ') IS NOT NULL ';
		$sql .= "	THEN";
		$sql .= "		(SELECT cps.price FROM `" . DB_PREFIX . "product_special` cps WHERE " . str_replace('DB_REF', 'cps', $std_conditions) . ') ';
		$sql .= "	ELSE";
		$sql .= "		p.price";
		$sql .= "	END";
		$sql .= ") >= '".(float)$price_min."' ";

		$sql .= " AND ";

		$sql .= "(";
		$sql .= "	CASE";
		$sql .= "		WHEN (SELECT cps.price FROM `" . DB_PREFIX . "product_special` cps WHERE " . str_replace('DB_REF', 'cps', $std_conditions) . ') IS NOT NULL ';
		$sql .= "	THEN";
		$sql .= "		(SELECT cps.price FROM `" . DB_PREFIX . "product_special` cps WHERE " . str_replace('DB_REF', 'cps', $std_conditions) . ') ';
		$sql .= "	ELSE";
		$sql .= "		p.price";
		$sql .= "	END";
		$sql .= ")  <= '".(float)$price_max."'";

				$sql .= " ) "; // END special price or price

			}

			// Product in special list
			if( isset($data['filter_special']) && $data['filter_special'] ){
				$sql .= " AND p.product_id IN (
				SELECT DISTINCT ps2.product_id 
				FROM `" . DB_PREFIX . "product_special` ps2 
				WHERE 
				ps2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' 
				AND (
				(ps2.date_start = '0000-00-00' OR ps2.date_start < NOW()) 
				AND 
				(ps2.date_end = '0000-00-00' OR ps2.date_end > NOW())
				)
			)";
		}
			// End Product in special list
		
		$sql .= " GROUP BY p.product_id";

		if($sub_query){
			return $sql;
		}
		
		$sort_data = array(
			'pd.name',
			'p.model',
			'p.quantity',
			'p.price',
			'rating',
			'p.sort_order',
			'p.date_added',
			'p2co.sort_order, p.sort_order, LCASE(pd.name)'
		);
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			}
			elseif ($data['sort'] == 'p.price') {
				$sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			}
			else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		}
		else {
				//$sql .= " ORDER BY p.sort_order";
			if(!empty($data['filter_category_id']) && $data['filter_category_id'] && $this->config->get('product_category_sort_order_status'))  
				$sql .= " ORDER BY p2co.sort_order, p.sort_order, LCASE(pd.name)";
			else 
				$sql .= " ORDER BY p.sort_order";
		}
		
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		}
		else {
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
		
		$product_data = array();

			$query = $this->db->query($sql);  // debug($sql);
			
			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}
			
			return $product_data;
		}
		
		public function getFilterByProducts($filters = array()){
			$implode = array();

			$sub_query = $this->getProducts($filters, true);
			
			$sql = 'SELECT DISTINCT filter_id FROM `' . DB_PREFIX . 'product_filter` WHERE product_id IN (' . $sub_query . ')';

			$query = $this->db->query($sql);

			foreach ($query->rows as $result) {
				$implode[] = (int)$result['filter_id'];
			}

			$filter_group_data = array();
			
			if ($implode) {
				$filter_group_query = $this->db->query("SELECT DISTINCT f.filter_group_id, fgd.name, fg.sort_order FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_group fg ON (f.filter_group_id = fg.filter_group_id) LEFT JOIN " . DB_PREFIX . "filter_group_description fgd ON (fg.filter_group_id = fgd.filter_group_id) WHERE f.filter_id IN (" . implode(',', $implode) . ") AND fgd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY f.filter_group_id ORDER BY fg.sort_order, LCASE(fgd.name)");

				foreach ($filter_group_query->rows as $filter_group) {
					$filter_data = array();

					$filter_query = $this->db->query("SELECT DISTINCT f.filter_id, fd.name FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_description fd ON (f.filter_id = fd.filter_id) WHERE f.filter_id IN (" . implode(',', $implode) . ") AND f.filter_group_id = '" . (int)$filter_group['filter_group_id'] . "' AND fd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY f.sort_order, LCASE(fd.name)");

					foreach ($filter_query->rows as $filter) {
						$filter_data[] = array(
							'filter_id' => $filter['filter_id'],
							'name'      => $filter['name']
						);
					}

					if ($filter_data) {
						$filter_group_data[] = array(
							'filter_group_id' => $filter_group['filter_group_id'],
							'name'            => $filter_group['name'],
							'filter'          => $filter_data
						);
					}
				}
			}

			return $filter_group_data;

		}

		public function getProductSpecials($data = array()) {
			$sql = "SELECT DISTINCT ps.product_id, (SELECT AVG(rating) FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = ps.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) GROUP BY ps.product_id";
			
			$sort_data = array(
				'pd.name',
				'p.model',
				'ps.price',
				'rating',
				'p.sort_order'
			);
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
					$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
				}
				else {
					$sql .= " ORDER BY " . $data['sort'];
				}
			}
			else {
				$sql .= " ORDER BY p.sort_order";
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC, LCASE(pd.name) DESC";
			}
			else {
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
			
			$product_data = array();
			
			$query = $this->db->query($sql);
			
			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}
			
			return $product_data;
		}
		
		public function getLatestProducts($limit) {
			$product_data = $this->cache->get('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);
			
			if (!$product_data) {
				$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.date_added DESC LIMIT " . (int)$limit);
				
				foreach ($query->rows as $result) {
					$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
				}
				
				$this->cache->set('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
			}
			
			return $product_data;
		}
		
		public function getPopularProducts($limit) {
			$product_data = $this->cache->get('product.popular.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);
			
			if (!$product_data) {
				$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.viewed DESC, p.date_added DESC LIMIT " . (int)$limit);
				
				foreach ($query->rows as $result) {
					$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
				}
				
				$this->cache->set('product.popular.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
			}
			
			return $product_data;
		}
		
		public function getBestSellerProducts($limit) {
			$product_data = $this->cache->get('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);
			
			if (!$product_data) {
				$product_data = array();
				
				$query = $this->db->query("SELECT op.product_id, SUM(op.quantity) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit);
				
				foreach ($query->rows as $result) {
					$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
				}
				
				$this->cache->set('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
			}
			
			return $product_data;
		}
		
		public function getProductAttributes($product_id) {
			$product_attribute_group_data = array();
			
			$product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int)$product_id . "' AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");
			
			foreach ($product_attribute_group_query->rows as $product_attribute_group) {
				$product_attribute_data = array();
				
				$product_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY a.sort_order, ad.name");
				
				foreach ($product_attribute_query->rows as $product_attribute) {
					$product_attribute_data[] = array(
						'attribute_id' => $product_attribute['attribute_id'],
						'name'         => $product_attribute['name'],
						'text'         => $product_attribute['text']
					);
				}
				
				$product_attribute_group_data[] = array(
					'attribute_group_id' => $product_attribute_group['attribute_group_id'],
					'name'               => $product_attribute_group['name'],
					'attribute'          => $product_attribute_data
				);
			}
			
			return $product_attribute_group_data;
		}
		
		public function getProductOptions($product_id) {
			$product_option_data = array();
			
			$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");
			
			foreach ($product_option_query->rows as $product_option) {
				$product_option_value_data = array();
				
				$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");
				
				foreach ($product_option_value_query->rows as $product_option_value) {
					$product_option_value_data[] = array(
						'product_option_value_id' => $product_option_value['product_option_value_id'],
						'option_value_id'         => $product_option_value['option_value_id'],
						'name'                    => $product_option_value['name'],
						'image'                   => $product_option_value['image'],
						'quantity'                => $product_option_value['quantity'],
						'subtract'                => $product_option_value['subtract'],
						'price'                   => $product_option_value['price'],
						'price_prefix'            => $product_option_value['price_prefix'],
						'weight'                  => $product_option_value['weight'],
						'weight_prefix'           => $product_option_value['weight_prefix']
					);
				}
				
				$product_option_data[] = array(
					'product_option_id'    => $product_option['product_option_id'],
					'product_option_value' => $product_option_value_data,
					'option_id'            => $product_option['option_id'],
					'name'                 => $product_option['name'],
					'type'                 => $product_option['type'],
					'value'                => $product_option['value'],
					'required'             => $product_option['required']
				);
			}
			
			return $product_option_data;
		}
		
		public function getProductDiscounts($product_id) {
			// if ($this->config->get('discounts_status')) {
			// 	$this->load->model('catalog/discount');
			// 	return $this->model_catalog_discount->discountPriceOverride($product_id, $this->config->get('config_customer_group_id'));
			// } else {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity > 1 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity ASC, priority ASC, price ASC");
			// }
			return $query->rows;
		}
		
		public function getProductImages($product_id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");
			
			return $query->rows;
		}

		// <<OPTIONS IMAGE
		public function getProductOptionImagesByOption($product_id, $product_option_id, $product_option_value_id) {
			$query = $this->db->query("SELECT * FROM ".DB_PREFIX."product_option_image WHERE product_id = ".(int)$product_id." AND product_option_id = ".(int)$product_option_id." AND product_option_value_id = ".(int)$product_option_value_id." ORDER BY sort_order ");
			
			return $query->rows;
		}
		
		public function getProductColorOptions($product_id) {
			$product_option_data = array();
			
			$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po 
				LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) 
				LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) 
				WHERE po.product_id = '" . (int)$product_id . "' 
				AND o.option_id = '1' 
				AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' 
				ORDER BY o.sort_order");
			
			foreach ($product_option_query->rows as $product_option) {
				$product_option_value_data = array();
				
				$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov 
					LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) 
					LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) 
					WHERE pov.product_id = '" . (int)$product_id . "' 
					AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' 
					AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
					ORDER BY ov.sort_order");				
				foreach ($product_option_value_query->rows as $product_option_value) {
					$product_option_value_data[] = array(
						'product_option_value_id' => $product_option_value['product_option_value_id'],
						'option_value_id'         => $product_option_value['option_value_id'],
						'name'                    => $product_option_value['name'],
						'image'                   => $product_option_value['image'],
						'quantity'                => $product_option_value['quantity'],
						'subtract'                => $product_option_value['subtract'],
						'price'                   => $product_option_value['price'],
						'price_prefix'            => $product_option_value['price_prefix'],
						'weight'                  => $product_option_value['weight'],
						'weight_prefix'           => $product_option_value['weight_prefix']
					);
				}
				
				$product_option_data[] = array(
					'product_option_id'    => $product_option['product_option_id'],
					'product_option_value' => $product_option_value_data,
					'option_id'            => $product_option['option_id'],
					'name'                 => $product_option['name'],
					'type'                 => $product_option['type'],
					'value'                => $product_option['value'],
					'required'             => $product_option['required']
				);
			}
			
			
			return $product_option_data;
		}
		
		public function getProductColorImage($product_id, $product_option_id, $product_option_value_id) {
			$sql = "SELECT * FROM " . DB_PREFIX . "product_option_image WHERE product_id = '" . (int)$product_id . "' AND product_option_id = '" . (int)$product_option_id . "' AND product_option_value_id = '" . (int)$product_option_value_id . "' ORDER BY sort_order ASC LIMIT 1";
			$query = $this->db->query($sql);
			
			return $query->row;
		}
                // >>OPTIONS IMAGE
		
		public function getProductRelated($product_id) {
			$product_data = array();
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.related_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.product_id = '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
			
			foreach ($query->rows as $result) {
				$product_data[$result['related_id']] = $this->getProduct($result['related_id']);
			}
			
			return $product_data;
		}
		
		public function getProductLayoutId($product_id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");
			
			if ($query->num_rows) {
				return $query->row['layout_id'];
			}
			else {
				return 0;
			}
		}
		
		public function getCategories($product_id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
			
			return $query->rows;
		}
		
		public function getTotalProducts($data = array()) {

			$sql = "SELECT COUNT(DISTINCT p.product_id) AS total";
			
			if (!empty($data['filter_category_id'])) {
				if (!empty($data['filter_sub_category'])) {
					$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
				}
				else {
					$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
				}
				
				if (!empty($data['filter_filter'])) {
					$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";

					if($this->config->get('product_category_sort_order_status')){
						$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category_order p2co ON (pf.product_id = p2co.product_id AND p2c.category_id = p2co.category_id)";
					}
				}
				else {
					$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";

					if($this->config->get('product_category_sort_order_status')){
						$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category_order p2co ON (p.product_id = p2co.product_id AND p2c.category_id = p2co.category_id)";
					}
				}
			}
			else {
				$sql .= " FROM " . DB_PREFIX . "product p";

				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p.product_id = pf.product_id) ";
			}
			
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			
			if (!empty($data['filter_category_id'])) {
				if (!empty($data['filter_sub_category'])) {
					$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
				}
				else {
					$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
				}
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();
				
				$filters = explode(',', $data['filter_filter']);
				
				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}
				
				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";

				// FixFilter
				$minq=$this->db->query("
					SELECT id.product_id FROM(SELECT 
					product_id, count(i.product_id) as t, c.filter_group as b
					FROM
					(SELECT 
					f.filter_id, f.product_id, p.filter_group_id
					FROM
					". DB_PREFIX ."product_filter f
					left join ". DB_PREFIX ."filter p ON p.filter_id = f.filter_id
					where
					p.filter_id IN (" . implode(',', $implode) . ")
					GROUP BY product_id , filter_group_id) as i,
					(select 
					count(distinct filter_group_id) as filter_group
					from
					". DB_PREFIX ."filter_description f_b
					WHERE f_b.filter_id in (" . implode(',', $implode) . ")) as c
					GROUP BY product_id
					HAVING t = b) as id");
				
				$min=array();
				
				for($x=0;$x<count($minq->rows);$x++){	
					
					foreach($minq->rows[$x] as $value){
						$min[]=$value;
					}
				}
				
				$imp=implode(',',$min);
				// FixFilter END
			}
// Start products or gallery
			// for products
		if (!empty($data['filter_main_product'])) {
			$sql .= " AND p.products_category = '" . $data['filter_main_product'] ."'";
		}
		// End products or gallery			
			if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
				$sql .= " AND (";
				
				if (!empty($data['filter_name'])) {
					$implode = array();
					
					$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));
					
					foreach ($words as $word) {
						$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
					}
					
					if ($implode) {
						$sql .= " " . implode(" AND ", $implode) . "";
					}
					
					if (!empty($data['filter_description'])) {
						$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
					}
				}
				
				if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
					$sql .= " OR ";
				}
				
				if (!empty($data['filter_tag'])) {
					$implode = array();
					
					$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));
					
					foreach ($words as $word) {
						$implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";
					}
					
					if ($implode) {
						$sql .= " " . implode(" AND ", $implode) . "";
					}
				}
				
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";

					$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
					$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
					$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
					$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
					$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
					$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
				
				$sql .= ")";
			}
			
			if ( isset($data['filter_manufacturer']) && $data['filter_manufacturer'] ) {
				
				$manufacturer_ids = explode(',', $data['filter_manufacturer']);

				foreach($manufacturer_ids as &$filter_manufacturer_id){
					$filter_manufacturer_id = (int)$this->db->escape($filter_manufacturer_id);
				}
				$sql .= " AND p.manufacturer_id IN ('" . implode("','", $manufacturer_ids) . "') ";
			}

			// FixFilter
			if(isset($filters)){
				if(!count($min)){
					$imp='0000000';
				}
				$sql .= " AND p.product_id IN(".$imp.")";
			}
			// FixFilter END

			if( isset($data['price_min']) && 
				isset($data['price_max']) && 
				$data['price_min'] > -1 &&  
				$data['price_max'] >= $data['price_min'] &&
				$data['price_max'] > 0
			) {

				$price_min = $data['price_min'];
			$price_max = $data['price_max'];

			if($this->config->get('config_currency') != $this->session->data['currency']){
				
				$price_min = $this->currency->convert($data['price_min'], $this->session->data['currency'], $this->config->get('config_currency'));
				$price_max = $this->currency->convert($data['price_max'], $this->session->data['currency'], $this->config->get('config_currency'));
				
			}
			
			$std_conditions = "
			DB_REF.product_id = p.product_id AND 
			DB_REF.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND 
			((DB_REF.date_start = '0000-00-00' OR DB_REF.date_start < NOW()) AND (DB_REF.date_end = '0000-00-00' OR DB_REF.date_end > NOW()))
			ORDER BY DB_REF.priority ASC, DB_REF.price ASC LIMIT 1";
			
			$sql .= " AND (";

			$sql .= "(";
			$sql .= "	CASE";
			$sql .= "		WHEN (SELECT cps.price FROM `" . DB_PREFIX . "product_special` cps WHERE " . str_replace('DB_REF', 'cps', $std_conditions) . ') IS NOT NULL ';
			$sql .= "	THEN";
			$sql .= "		(SELECT cps.price FROM `" . DB_PREFIX . "product_special` cps WHERE " . str_replace('DB_REF', 'cps', $std_conditions) . ') ';
			$sql .= "	ELSE";
			$sql .= "		p.price";
			$sql .= "	END";
			$sql .= ") >= '".(float)$price_min."' ";

			$sql .= " AND ";

			$sql .= "(";
			$sql .= "	CASE";
			$sql .= "		WHEN (SELECT cps.price FROM `" . DB_PREFIX . "product_special` cps WHERE " . str_replace('DB_REF', 'cps', $std_conditions) . ') IS NOT NULL ';
			$sql .= "	THEN";
			$sql .= "		(SELECT cps.price FROM `" . DB_PREFIX . "product_special` cps WHERE " . str_replace('DB_REF', 'cps', $std_conditions) . ') ';
			$sql .= "	ELSE";
			$sql .= "		p.price";
			$sql .= "	END";
			$sql .= ")  <= '".(float)$price_max."'";

				$sql .= " ) "; // END special price or price
			}

			// Product in special list
			if( isset($data['filter_special']) && $data['filter_special'] ){
				$sql .= " AND p.product_id IN (
				SELECT DISTINCT ps2.product_id 
				FROM `" . DB_PREFIX . "product_special` ps2 
				WHERE 
				ps2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' 
				AND (
				(ps2.date_start = '0000-00-00' OR ps2.date_start < NOW()) 
				AND 
				(ps2.date_end = '0000-00-00' OR ps2.date_end > NOW())
				)
			)";
		}
			// End Product in special list
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	public function getProfile($product_id, $recurring_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recurring r JOIN " . DB_PREFIX . "product_recurring pr ON (pr.recurring_id = r.recurring_id AND pr.product_id = '" . (int)$product_id . "') WHERE pr.recurring_id = '" . (int)$recurring_id . "' AND status = '1' AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'");
		
		return $query->row;
	}
	
	public function getProfiles($product_id) {
		$query = $this->db->query("SELECT rd.* FROM " . DB_PREFIX . "product_recurring pr JOIN " . DB_PREFIX . "recurring_description rd ON (rd.language_id = " . (int)$this->config->get('config_language_id') . " AND rd.recurring_id = pr.recurring_id) JOIN " . DB_PREFIX . "recurring r ON r.recurring_id = rd.recurring_id WHERE pr.product_id = " . (int)$product_id . " AND status = '1' AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' ORDER BY sort_order ASC");
		
		return $query->rows;
	}
	
	public function getTotalProductSpecials() {
		$query = $this->db->query("SELECT COUNT(DISTINCT ps.product_id) AS total FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))");
		
		if (isset($query->row['total'])) {
			return $query->row['total'];
		}
		else {
			return 0;
		}
	}

	public function getProductDownload($product_id = 0) {
		$product_id = (int)$product_id;
		$query = $this->db->query("SELECT DISTINCT product_pdf_id FROM " . DB_PREFIX ."product_pdf WHERE product_id='" .$product_id . "' LIMIT 0,1");
		if($query->num_rows){
			return $query->row['product_pdf_id'];
		}

		return false;
	}

	public function getDownload($product_pdf_id = 0) {
		$product_pdf_id = (int)$product_pdf_id;
		$query = $this->db->query("SELECT DISTINCT mask, filename FROM " . DB_PREFIX ."product_pdf WHERE product_pdf_id='" .$product_pdf_id . "' LIMIT 0,1");
		if($query->num_rows){
			return $query->row;
		}

		return false;
	}

	public function getLowesetPrice($paths=0, $manufacturer_ids = array()){

		$customer_group_id= $this->config->get('config_customer_group_id');

		if($this->customer->isLogged()){
			$customer_group_id = $this->customer->getGroupId();
		}
		// debug($this->session->data['main_product']);
// Start products or gallery
			if ($this->session->data['main_product'] && isset($this->session->data['main_product'])) {
				$main_pcategories = $this->session->data['main_product']; 
			}else{
				$main_pcategories = "";
			}
// End products or gallery
		$price = 0;

		$sub_query = '';

		if ($paths || $manufacturer_ids) { 
				$sub_query = 'SELECT DISTINCT p.product_id FROM `' . DB_PREFIX . 'product` p ' ; //debug($paths); debug($manufacturer_ids);

				if($paths){
					$sub_query .= " LEFT JOIN `" . DB_PREFIX . "product_to_category` p2c ON (p2c.product_id = p.product_id) LEFT JOIN `" . DB_PREFIX . "category_path` cp ON (cp.category_id = p2c.category_id) ";
				}

				$conds = array();

				if($paths){
					$conds[] = " cp.path_id = '" . (int)$paths . "' ";
				}

				if ( $manufacturer_ids && is_array($manufacturer_ids) ) {
					
					$manufacturer_id = array();
					
					foreach($manufacturer_ids as $filter_manufacturer_id){
						$manufacturer_id[] = (int)$this->db->escape($filter_manufacturer_id);
					}

					$conds[] = " p.manufacturer_id IN ('" . implode("','", $manufacturer_ids) . "') ";
				}

				if($conds){
					$sub_query .= " WHERE " . implode(' AND ', $conds);
				}
			if ($main_pcategories) {
				$sub_query .= "AND p.products_category='". $main_pcategories ."'";
				// ' AND p.products_category='. $main_pcategories .''
			}
			}
			// Start products or gallery
			// End products or gallery			
			if($sub_query){
				$sql = 'SELECT d.product_id, MIN(d.price) as price FROM (
				SELECT * FROM (
				
				SELECT product_id, MIN(a.price) as price FROM (SELECT product_id, price FROM `' . DB_PREFIX . 'product` UNION ALL SELECT product_id, price FROM `' . DB_PREFIX . 'product_special` WHERE customer_group_id="'.(int)$customer_group_id.'") a GROUP BY a.product_id
				) b 
				
				WHERE product_id IN 
				('.$sub_query.')
			) as d LEFT JOIN `' . DB_PREFIX . 'product` p ON (p.product_id = d.product_id) WHERE p.status=1';
		}
			// Start products or gallery
			else{
				$sql = 'SELECT d.product_id, MIN(d.price) as price FROM (
					SELECT * FROM (
					
					SELECT product_id, MIN(a.price) as price FROM (SELECT product_id, price FROM `' . DB_PREFIX . 'product` UNION ALL SELECT product_id, price FROM `' . DB_PREFIX . 'product_special` WHERE customer_group_id="'.(int)$customer_group_id.'") a GROUP BY a.product_id
					) b 
					WHERE product_id IN 
					(
						SELECT p.product_id FROM `' . DB_PREFIX . 'product` as p WHERE p.products_category = "'. $main_pcategories .'"
					)

				) as d LEFT JOIN `' . DB_PREFIX . 'product` p ON (p.product_id = d.product_id) WHERE p.status=1';
			}
			// End products or gallery
	$query = $this->db->query($sql);
	
	if($query->num_rows){
		
		$price = $query->row['price'];
	}
	
	return $price;
}

public function getHighestPrice($paths=0, $manufacturer_ids = array()){ 

	$customer_group_id= $this->config->get('config_customer_group_id');

	if($this->customer->isLogged()){
		$customer_group_id = $this->customer->getGroupId();
	}

// Start products or gallery
			if ($this->session->data['main_product'] && isset($this->session->data['main_product'])) {
				$main_pcategories = $this->session->data['main_product']; 
			}else{
				$main_pcategories = "";
			}
// End products or gallery
	$price = 0;

	$sub_query = '';

	if ($paths || $manufacturer_ids) {
				$sub_query = 'SELECT DISTINCT p.product_id FROM `' . DB_PREFIX . 'product` p ' ; //debug($paths); debug($manufacturer_ids);

				if($paths){
					$sub_query .= " LEFT JOIN `" . DB_PREFIX . "product_to_category` p2c ON (p2c.product_id = p.product_id) LEFT JOIN `" . DB_PREFIX . "category_path` cp ON (cp.category_id = p2c.category_id) ";
				}

				$conds = array();

				if($paths){
					$conds[] = " cp.path_id = '" . (int)$paths . "' ";
				}

				if ( $manufacturer_ids && is_array($manufacturer_ids) ) {
					
					$manufacturer_id = array();
					
					foreach($manufacturer_ids as $filter_manufacturer_id){
						$manufacturer_id[] = (int)$this->db->escape($filter_manufacturer_id);
					}

					$conds[] = " p.manufacturer_id IN ('" . implode("','", $manufacturer_ids) . "') ";
				}

				if($conds){
					$sub_query .= " WHERE " . implode(' AND ', $conds);
				}
				// Start products or gallery
				if ($main_pcategories) {
					$sub_query .= "AND p.products_category='". $main_pcategories ."'";
					// ' AND p.products_category='. $main_pcategories .''
				}
				// End products or gallery
			}
			
			if($sub_query){
				$sql = 'SELECT d.product_id, MAX(d.price) as price FROM (
				SELECT * FROM (
				
				SELECT product_id, MIN(a.price) as price FROM (SELECT product_id, price FROM `' . DB_PREFIX . 'product` UNION ALL SELECT product_id, price FROM `' . DB_PREFIX . 'product_special` WHERE customer_group_id="'.(int)$customer_group_id.'") a GROUP BY a.product_id
				) b 
				
				WHERE product_id IN 
				('.$sub_query.')
			) as d LEFT JOIN `' . DB_PREFIX . 'product` p ON (p.product_id = d.product_id) WHERE p.status=1';
		}
			// Start products or gallery
			else{
				$sql = 'SELECT d.product_id, MAX(d.price) as price FROM (
					SELECT * FROM (
					
					SELECT product_id, MIN(a.price) as price FROM (SELECT product_id, price FROM `' . DB_PREFIX . 'product` UNION ALL SELECT product_id, price FROM `' . DB_PREFIX . 'product_special` WHERE customer_group_id="'.(int)$customer_group_id.'") a GROUP BY a.product_id
					) b 
					WHERE product_id IN 
					(
						SELECT p.product_id FROM `' . DB_PREFIX . 'product` as p WHERE p.products_category = "'. $main_pcategories .'"
					)

				) as d LEFT JOIN `' . DB_PREFIX . 'product` p ON (p.product_id = d.product_id) WHERE p.status=1';
			}
			// End products or gallery
	$query = $this->db->query($sql);

	if($query->num_rows){
		
		$price = $query->row['price'];
	}
	
	return $price;
}

		// Start: Extension: live price update 
public function getUpdateOptionsList($product_id, $product_option_id) {		
	$option_query = $this->db->query("SELECT po.product_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
	if($option_query->num_rows) {
		return $option_query->row;
	} else {
		return '';
	}					
}

public function getUpdateOptionValues($value, $product_option_id) {
	$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$value . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
	if($option_value_query->num_rows) {
		return $option_value_query->row;
	} else {
		return '';
	}
}

public function getUpdateOptionChcekboxValues($product_option_value_id, $product_option_id) {					
	$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
	if($option_value_query->num_rows) {
		return $option_value_query->row;
	} else {
		return '';
	}
}

public function getDiscountAmountForUpdatePrice($product_id, $quantity) {					
	$product_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity <= '" . (int)$quantity . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");
	
	if($product_discount_query->num_rows) {
		return $product_discount_query->row['price'];
	} else {
		return '';
	}
}
		// End: Extension: live price update 
}

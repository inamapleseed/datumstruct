<?php
class ModelCatalogDiscount extends Model {
	
	public function setDiscount($data, $table) {
		
		$id = $table . '_id';
		
		$this->db->query("TRUNCATE TABLE " . DB_PREFIX . $table . "_discount;");
		
		if(!empty($data)) {
			foreach ($data as $key) {
			
				switch ($table) {
					case 'category':
						$sql = "INSERT INTO " . DB_PREFIX . $table . "_discount SET " . $table  . "_id = '" . (int)$key[$id] . "', customer_group_id = '" . (int)$key['customer_group_id'] . "', priority = '" . (int)$key['priority'] . "', percentage = '" . (float)$key['percentage'] . "', affect = '" . $key['affect'] . "', qty = '" . $key['qty'] . "', status = '" . $key['status']. "',  date_start = '" . $this->db->escape($key['date_start']) . "', date_end = '" . $this->db->escape($key['date_end']) . "'";
						break;
					case 'customer':
						$sql = "INSERT INTO " . DB_PREFIX . $table . "_discount SET " . $table  . "_id = '" . (int)$key[$id] . "', product_id = '" . (int)$key['product_id'] . "',  priority = '" . (int)$key['priority'] . "', price = '" . (float)$key['price'] . "', quantity = '" . $key['quantity'] . "', status = '" . $key['status']. "',  date_start = '" . $this->db->escape($key['date_start']) . "', date_end = '" . $this->db->escape($key['date_end']) . "'";
						break;
					case 'customer_group':
						$sql = "INSERT INTO " . DB_PREFIX . $table . "_discount SET " . $table  . "_id = '" . (int)$key[$id] . "', priority = '" . (int)$key['priority'] . "', percentage = '" . (float)$key['percentage'] . "', status = '" . $key['status']. "', date_start = '" . $this->db->escape($key['date_start']) . "', date_end = '" . $this->db->escape($key['date_end']) . "'";
						break;
					case 'ordertotal':
						$sql = "INSERT INTO " . DB_PREFIX . $table . "_discount SET " . $table  . " = '" . (int)$key['ordertotal'] . "', customer_group_id = '" . (int)$key['customer_group_id'] . "', priority = '" . (int)$key['priority'] . "', type = '" . $key['type'] . "', percentage = '" . (float)$key['discount'] . "', status = '" . $key['status']. "', date_start = '" . $this->db->escape($key['date_start']) . "', date_end = '" . $this->db->escape($key['date_end']) . "'";
						break;
					case 'manufacturer':
						$sql = "INSERT INTO " . DB_PREFIX . $table . "_discount SET " . $table  . "_id = '" . (int)$key[$id] . "', customer_group_id = '" . (int)$key['customer_group_id'] . "', priority = '" . (int)$key['priority'] . "', percentage = '" . (float)$key['percentage'] . "', qty = '" . $key['qty'] . "', status = '" . $key['status']. "', date_start = '" . $this->db->escape($key['date_start']) . "', date_end = '" . $this->db->escape($key['date_end']) . "'";
						break;
					case 'product':
						$sql = "INSERT INTO " . DB_PREFIX . $table . "_discount SET " . $table  . "_id = '" . (int)$key[$id] . "', customer_group_id = '" . (int)$key['customer_group_id'] . "', quantity = '" . (int)$key['quantity'] . "', priority = '" . (int)$key['priority'] . "', price = '" . (float)$key['price'] . "', date_start = '" . $this->db->escape($key['date_start']) . "', date_end = '" . $this->db->escape($key['date_end']) . "'";
						break;
					case 'volume':
						$sql = "INSERT INTO " . DB_PREFIX . $table . "_discount SET customer_group_id = '" . (int)$key['customer_group_id'] . "', priority = '" . (int)$key['priority'] . "', percentage = '" . (float)$key['percentage'] . "', qty = '" . $key['qty'] . "', status = '" . $key['status']. "',  date_start = '" . $this->db->escape($key['date_start']) . "', date_end = '" . $this->db->escape($key['date_end']) . "'";
						break;
					case 'loyalty':
						$sql = "INSERT INTO " . DB_PREFIX . $table . "_discount SET ordertotal = '" . (int)$key['ordertotal'] . "', customer_group_id = '" . (int)$key['customer_group_id'] . "', priority = '" . (int)$key['priority'] . "', order_status = '" . implode(',',$key['order_status']) . "', percentage = '" . (float)$key['discount'] . "', status = '" . $key['status']. "', date_start = '" . $this->db->escape($key['date_start']) . "', date_end = '" . $this->db->escape($key['date_end']) . "'";	
						break;
				}
			
				$this->db->query($sql);
			}
		}
		
		return $data;
	}
	
	public function getDiscounts($id, $table) {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . $table . "_discount WHERE " . $table . "_id = '" . (int)$id . "' ORDER BY priority, percentage");

		return $query->rows;
	}
	
	public function getAllDiscounts($table) {
		
		if ($table != 'product' && $table != 'customer') {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . $table . "_discount ORDER BY priority, percentage");
		} elseif($table == 'product') {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . $table . "_discount ORDER BY quantity, priority, price");
		} elseif ($table == 'customer') {
			$query = $this->db->query("SELECT cd.*, CONCAT(c.firstname, ' ', c.lastname ) as customer_name, pd.name as product_name, p.price as original_price FROM `" . DB_PREFIX . $table . "_discount` cd INNER JOIN `" . DB_PREFIX ."customer` c ON cd.customer_id = c.customer_id INNER JOIN `" . DB_PREFIX ."product_description` pd ON cd.product_id = pd.product_id INNER JOIN `" . DB_PREFIX ."product` p ON cd.product_id = p.product_id WHERE pd.language_id = '" . $this->config->get('config_language_id') ."' ORDER BY quantity, priority, price");
		}
		
		return $query->rows;
	}
	
	public function getProductInfo($id) {
		
		$query = $this->db->query("SELECT p.price, pd.name FROM `" . DB_PREFIX . "product` p INNER JOIN `" . DB_PREFIX . "product_description` pd ON p.product_id = pd.product_id WHERE p.product_id = '" . (int)$id . "'");
		
		return array('price' => $query->row['price'], 'name' => $query->row['name']);
	}
	
	public function checkTableExist($table){
		
		$table_name = $table . '_discount';
		
		switch($table) {
			case 'loyalty': 
					// Loyalty Discount
					$query = $this->db->query("SELECT COUNT(*) as cond FROM information_schema.tables WHERE table_schema = '" . DB_DATABASE. "' AND table_name = '" . DB_PREFIX . $this->db->escape($table_name) . "'");
					
					if (empty($query->row['cond'])) {
					
						$sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX ."loyalty_discount` (`loyalty_discount_id` int(11) NOT NULL AUTO_INCREMENT, `ordertotal` int(11) NOT NULL, `customer_group_id` int(11) NOT NULL, `priority` int(5) NOT NULL DEFAULT '1', `order_status` VARCHAR(96) NOT NULL, ";
						$sql .= "`percentage` decimal(15,4) NOT NULL DEFAULT '0.0000',`status` INT  NOT NULL DEFAULT '1', `date_start` date NOT NULL DEFAULT '0000-00-00', `date_end` date NOT NULL DEFAULT '0000-00-00', PRIMARY KEY (`loyalty_discount_id`), KEY `ordertotal` (`ordertotal`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		
						$this->db->query($sql);
		
						$this->db->query("INSERT INTO `". DB_PREFIX ."extension` (`extension_id`, `type`, `code`) VALUES (NULL, 'total', 'loyalty_discount'); ");
						$this->db->query("INSERT INTO `". DB_PREFIX ."setting` (`setting_id`, `store_id`, `code`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'loyalty_discount', 'loyalty_discount_sort_order', '3', '0'); ");
						$this->db->query("INSERT INTO `". DB_PREFIX ."setting` (`setting_id`, `store_id`, `code`, `key`, `value`, `serialized`) VALUES (NULL, '0', 'loyalty_discount', 'loyalty_discount_status', '1', '0');");
					
					}
					
					break;			
		}
		
	
	}
}
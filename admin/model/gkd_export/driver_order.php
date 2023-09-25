<?php
class ModelGkdExportDriverOrder extends Model {
  private $langIdToCode = array();
  
  public function getItems($data = array(), $count = false) {
    $select = ($count) ? 'COUNT(*) AS total' : "o.*, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS order_status";
    
    $sql = "SELECT ".$select." FROM `" . DB_PREFIX . "order` o";

		if (!empty($data['filter_order_status'])) {
			$implode = array();

			//$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($data['filter_order_status'] as $order_status_id) {
				$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

    if (isset($data['filter_store']) && $data['filter_store'] !== '') {
			$sql .= " AND o.store_id = '" . (int)$data['filter_store'] . "'";
    }
    
		if (!empty($data['filter_order_id_min'])) {
			$sql .= " AND o.order_id >= '" . (int)$data['filter_order_id_min'] . "'";
		}
    
    if (!empty($data['filter_order_id_max'])) {
			$sql .= " AND o.order_id <= '" . (int)$data['filter_order_id_max'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added_min'])) {
			$sql .= " AND DATE(o.date_added) >= DATE('" . $this->db->escape($data['filter_date_added_min']) . "')";
		}
    
    if (!empty($data['filter_date_added_max'])) {
			$sql .= " AND DATE(o.date_added) <= DATE('" . $this->db->escape($data['filter_date_added_max']) . "')";
		}

		if (!empty($data['filter_date_modified_min'])) {
			$sql .= " AND DATE(o.date_modified) >= DATE('" . $this->db->escape($data['filter_date_modified_min']) . "')";
		}
    
    if (!empty($data['filter_date_modified_max'])) {
			$sql .= " AND DATE(o.date_modified) <= DATE('" . $this->db->escape($data['filter_date_modified_max']) . "')";
		}

		if (!empty($data['filter_total_min'])) {
			$sql .= " AND o.total >= '" . (float)$data['filter_total_min'] . "'";
		}
    
    if (!empty($data['filter_total_max'])) {
			$sql .= " AND o.total <= '" . (float)$data['filter_total_max'] . "'";
		}

		$sort_data = array(
			'o.order_id',
			'customer',
			'order_status',
			'o.date_added',
			'o.date_modified',
			'o.total'
		);
    
    // return count
    if ($count) {
      return $this->db->query($sql)->row['total'];
    }
    
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.order_id";
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

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
    //echo $sql; die;
		$query = $this->db->query($sql);

    $result = array();
    $i = 0;
    foreach ($query->rows as &$row) {
      $products = $this->getOrderProducts($row['order_id']);
      
      unset($row['payment_country_id'], $row['payment_zone_id'], $row['shipping_country_id'], $row['shipping_zone_id'], $row['order_status_id'], $row['marketing_id'], $row['language_id'], $row['currency_id'], $row['ip'], $row['forwarded_ip'], $row['user_agent'], $row['accept_language']);
      
      foreach ($products as $product) {
        $result[$i] = $row;
        $result[$i]['product_id'] = $product['product_id'];
        $result[$i]['product_name'] = $product['name'];
        $result[$i]['product_model'] = $product['model'];
        $result[$i]['product_quantity'] = $product['quantity'];
        $result[$i]['product_price'] = $product['price'];
        $result[$i]['product_total'] = $product['total'];
        $result[$i]['product_tax'] = $product['tax'];
        $result[$i]['product_reward'] = $product['reward'];
        $i++;
      }
    }
    
		return $result;
	}
  
  public function getOrderProducts($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}
  
  public function getTotalItems($data = array()) {
    return $this->getItems($data, true);
  }
}
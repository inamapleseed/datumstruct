<?php
class ModelCatalogDiscount extends Model {
	protected $discount_modules = array('category', 'manufacturer'); 
	
	public function getDiscountProducts($data = array(), $specials, $total) {
		
		$page = $data['start']/$data['limit'] + 1;
		
		$this->load->model('catalog/product');
		$special_override = $this->config->get('discounts_override_special_price');
		
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}
		
		switch ($special_override) {
			case 'exclusive' : 
				$product_data = $specials;
				break;
			case 'override' :
				$product_data = array();
				$total = 0;
				break;
			case 'default' :
				$product_data = $specials;
				$total = 0;
				break;
		}
			
		$statement = array();
		
		foreach ($this->discount_modules as $module) {
			$key = $module . '_discount_status';
			$status = $this->config->get($key);
			
			if (!empty($status)) {
				
				$sql = " SELECT p.product_id as product_id, p.sort_order as sort_order, LCASE(pd.name) as name, p.model as model, ";
				$sql .=	"(SELECT AVG(rating) FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating ";
				$sql .=	" FROM " . DB_PREFIX . $module . "_discount dm";
						
				if ($module == 'category') {
					$sql .=	" LEFT JOIN " . DB_PREFIX . "product_to_category ptc ON (ptc.category_id = dm.category_id) ";
					$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (ptc.product_id = p.product_id) ";
				} elseif ($module == 'manufacturer') {
					$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (dm.manufacturer_id = p.manufacturer_id) ";
				}
				
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) ";
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) ";
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id) ";

				$sql .= " WHERE p.status = '1' AND dm.status = '1' AND p.date_available <= NOW() ";
				$sql .= " AND dm.qty <= '1' ";
				$sql .= " AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ";
				$sql .= " AND dm.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' ";
				$sql .= " AND ((dm.date_start = '0000-00-00' OR dm.date_start < NOW()) ";
				$sql .= " AND (dm.date_end = '0000-00-00' OR dm.date_end > NOW())) GROUP BY p.product_id ";
				
				$statement[] = $sql;
			}
		}
		
		if (!empty($sql)) {
			$sql = implode(" UNION ", $statement);	
				
			$sort_data = array(
				'pd.name',
				'p.model',
				'p.price',
				'rating',
				'p.sort_order'
			);
		
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
		
				switch ($data['sort']) {
					case 'pd.name' : $data['sort'] = 'name';
						break;
					case 'p.model':  $data['sort'] = 'model';
						break;
					case 'p.sort_order' : $data['sort'] = 'sort_order';
						break;	
				}
			
				if ($data['sort'] == 'name' || $data['sort'] == 'model') {
					$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
				} else {
					$sql .= " ORDER BY " . $data['sort'];
				}
			} else {
				$sql .= " ORDER BY sort_order";
			}

			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC, name DESC";
			} else {
				$sql .= " ASC, name ASC";
			}

			if (isset($data['start']) || isset($data['limit'])) {
				// Calculate number of Product Specials on the page
				if ($special_override == 'exclusive') {
					if (!empty($specials)) {
						$data['start'] = $total - count($specials) - $data['limit'];
					} else {
						$data['start'] = $data['start'] - $total;
					}
				}
				
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				} elseif (!empty($specials)) {
					$data['limit'] = $data['limit'] - count($specials);
				}
				
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$query = $this->db->query($sql);
		
			foreach ($query->rows as $result) {
				if($special_override == 'exclusive') {
					if(empty($this->hasSpecialPrice($result['product_id'], $customer_group_id))) {
						$product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
					}
				} else {
					$product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
				}
			}
		}
		
		return $product_data;
	}
	
	public function getTotalDiscountProducts($total) {
		
		
		
		$special_override = $this->config->get('discounts_override_special_price');
		
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}
		
		
		switch ($special_override) {
			case 'exclusive' : 
				$products = $total;
				break;
			case 'override' :
				$products = 0;
				break;
			case 'default' :
				$products = 0;
				break;
		
		}
		
		foreach ($this->discount_modules as $module) {
			$key = $module . '_discount_status';
			$status = $this->config->get($key);
			
			if (!empty($status)) {
				
				$sql = " SELECT p.product_id as total";
				$sql .=	" FROM " . DB_PREFIX . $module . "_discount dm";
						
				if ($module == 'category') {
					$sql .=	" LEFT JOIN " . DB_PREFIX . "product_to_category ptc ON (ptc.category_id = dm.category_id) ";
					$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (ptc.product_id = p.product_id) ";
				} elseif ($module == 'manufacturer') {
					$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (dm.manufacturer_id = p.manufacturer_id) ";
				}
				
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) ";
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) ";
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id) ";
				
				$sql .= " WHERE p.status = '1' AND dm.status = '1' AND p.date_available <= NOW() ";
				
				$sql .= " AND dm.qty <= '1' ";
				$sql .= " AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ";
				$sql .= " AND dm.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' ";
				$sql .= " AND ((dm.date_start = '0000-00-00' OR dm.date_start < NOW()) ";
				$sql .= " AND (dm.date_end = '0000-00-00' OR dm.date_end > NOW())) ";
			
				$statement[] = $sql;
			
			}
		}
		
		
		if (!empty($sql)) {
			$sql = implode(" UNION ", $statement);	
			
			$query = $this->db->query($sql);
		
			if (!empty($query->num_rows)) {
				foreach($query->rows as $row) {
					if($special_override == 'exclusive') {
						if(empty($this->hasSpecialPrice($row['total'], $customer_group_id))) {
							$products++;
						}
					}
				}
			}
		}
		
		return $products;
	}
	
	public function getCategoryDiscount($product_id) {
		
		$category_discount = array();
		$category_discount_queries = array();
		
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}
		
		if ($this->config->get('discounts_override_special_price') == 'exclusive' && $this->hasSpecialPrice($product_id, $customer_group_id)) {
			return false;
		}
		
		$query = $this->db->query("SELECT ptc.category_id as category_id, c.parent_id as parent_id FROM `" . DB_PREFIX . "product_to_category` ptc INNER JOIN `" . DB_PREFIX . "category` c ON ptc.category_id = c.category_id WHERE product_id = '" . (int)$product_id . "' ORDER BY c.sort_order DESC, c.category_id DESC");	
				
		if ($query->num_rows > 1) {
			foreach ($query->rows as $category) {
				$discount_cat_id = $this->categoryDiscountQuery2($category['category_id'], $customer_group_id, false);
				if (!empty($discount_cat_id)) {
					$discount_query[$category['category_id']][] = $discount_cat_id;
				}
				if (empty($discount_query) && !empty($category['parent_id'])) {
					$discount_par_id = $this->categoryDiscountQuery2($category['parent_id'], $customer_group_id, true);
					if (!empty($discount_par_id)) {
						$discount_query[$category['parent_id']][] = $discount_par_id;
					}
				}
			}
			if (!empty($discount_query)) {
				foreach ($discount_query as $key => $value) {
					if (empty($high)) {
						$high[$key] = $value[0][0]['percentage'];
					} else {
						arsort($high);
						if ($value[0][0]['percentage'] > reset($high)) {
							$high[$key] =  $value[0][0]['percentage'];
						}
					}
				}
				reset($high);
				$discount_query = $discount_query[key($high)][0];
				$category_discount_query = $this->getMatchingCategoryDiscount($discount_query, $customer_group_id);				
			}
		} elseif (!empty($query->num_rows)) {
			$category = $query->row;
			$discount_query = $this->categoryDiscountQuery2($category['category_id'], $customer_group_id, false);
			if ($discount_query) {
				$category_discount_query = $this->getMatchingCategoryDiscount($discount_query, $customer_group_id);	
			} elseif (empty($discount_query) && !empty($category['parent_id'])) {
				$discount_query = $this->categoryDiscountQuery2($category['parent_id'], $customer_group_id, true);			
				if ($discount_query) {
					$category_discount_query = $this->getMatchingCategoryDiscount($discount_query, $customer_group_id);	
				}
			}
		}
		
		if (isset($category_discount_query)) {
			$category_discount = array(
				'name' 			=> $category_discount_query['name'],
				'percentage'	=> $category_discount_query['percentage'],
				'affect' 		=> $category_discount_query['affect'],
				'category_id'	=> $category_discount_query['category_id'],
				'qty'			=> $category_discount_query['qty']
			);
		}
		
		if (empty($category_discount)) {
			return false;
		} else if (!empty($category_discount['qty'])) {
			if ($this->getCategoryProductQty($category_discount, $customer_group_id) >= $category_discount['qty']) {
				return $category_discount;
			}
		} else {
			return $category_discount;
		}	
	}
	
	protected function getMatchingCategoryDiscount($discount_query, $customer_group_id) {
		
		foreach ($discount_query as $discount) {	
			if (empty($category_discount_query)) {
					$category_discount_query = array (
						'name' 			=> $discount['name'],
						'percentage' 	=> $discount['percentage'],
						'affect' 		=> $discount['affect'],
						'category_id'	=> $discount['category_id'],
						'qty'			=> $discount['qty']
					);
			} else if ($category_discount_query['percentage'] < $discount['percentage'] && $this->getCategoryProductQty($category_discount_query, $customer_group_id) >= $discount['qty']) {
					$category_discount_query = array (
						'name' 			=> $discount['name'],
						'percentage' 	=> $discount['percentage'],
						'affect' 		=> $discount['affect'],
						'category_id'	=> $discount['category_id'],
						'qty'			=> $discount['qty']
					);
			}			
		}
		return $category_discount_query;
	}
	
	protected function getCategoryProductQty($discount, $customer_group_id) {
		
		$category_quantity = 0;
		
		
		
		if (version_compare(VERSION, '2.1.0.0', '>=')) {
			$cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
		
			foreach ($cart_query->rows as $cart) {
				
				$product = $cart['product_id'];
				$quantity = $cart['quantity'];
				$categories = array();
				
				$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product . "'");
				
				foreach ($query->rows as $key) {
					$categories[] = $key['category_id'];
				}
								
				foreach ($categories as $category) {
					
					if ($discount['category_id'] == $category) {
						
						$exclusive = $this->config->get('discounts_override_special_price') == 'exclusive' ? !$this->hasSpecialPrice($product, $customer_group_id) : 1 ;
						
						if ($exclusive) {
							$category_quantity += $quantity;
						}	
					}
					
					if ($discount['affect']) {
						$query = $this->db->query("SELECT parent_id FROM `" . DB_PREFIX . "category` WHERE `category_id` = '" . (int)$category . "'");
						$parent_id = $query->row['parent_id'];
						
						while (!empty($parent_id)) {
							
							if ($discount['category_id'] == $parent_id) {
								$category_quantity += $quantity;
							}
							
							$query = $this->db->query("SELECT parent_id FROM `" . DB_PREFIX . "category` WHERE `category_id` = '" . (int)$parent_id . "'");
							$parent_id = $query->row['parent_id'];
						}
					}
				}
			}
		} else {
			foreach ($this->session->data['cart'] as $key => $quantity) {
				$product = unserialize(base64_decode($key));
				$categories = array();
				
				$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product['product_id'] . "'");
				
				foreach ($query->rows as $key) {
					$categories[] = $key['category_id'];
				}
								
				foreach ($categories as $category) {
					
					if ($discount['category_id'] == $category) {
						
						$exclusive = $this->config->get('discounts_override_special_price') == 'exclusive' ? !$this->hasSpecialPrice($product['product_id'], $customer_group_id) : 1 ;
						
						if ($exclusive) {
							$category_quantity += $quantity;
						}	
					}
					
					if ($discount['affect']) {
						$query = $this->db->query("SELECT parent_id FROM `" . DB_PREFIX . "category` WHERE `category_id` = '" . (int)$category . "'");
						$parent_id = $query->row['parent_id'];
						
						while (!empty($parent_id)) {
							
							if ($discount['category_id'] == $parent_id) {
								$category_quantity += $quantity;
							}
							
							$query = $this->db->query("SELECT parent_id FROM `" . DB_PREFIX . "category` WHERE `category_id` = '" . (int)$parent_id . "'");
							$parent_id = $query->row['parent_id'];
						}
					}
				}
			}
		}
		
		return $category_quantity;
	}
	
	public function getManufacturerDiscount($product_id) {
		
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}
		
		if ($this->config->get('discounts_override_special_price') == 'exclusive' && $this->hasSpecialPrice($product_id, $customer_group_id)) {
			return false;
		}
		
		$manufacturer_discount = array();
		$manufacturer_discount_queries = array();
		
		$query = $this->db->query("SELECT manufacturer_id FROM `" . DB_PREFIX . "product` WHERE product_id = '" . (int)$product_id . "'");	
		
		$manufacturer_id = $query->row['manufacturer_id'];
		
		$sql = "SELECT md.percentage as percentage, m.name as name, md.qty as qty FROM `" . DB_PREFIX . "manufacturer_discount` md INNER JOIN `" . DB_PREFIX . "manufacturer` m ON md.manufacturer_id = m.manufacturer_id WHERE md.status = '1' AND md.manufacturer_id = '" . (int)$manufacturer_id . "' AND md.customer_group_id = '" . (int)$customer_group_id . "' AND ((md.date_start = '0000-00-00' OR md.date_start < NOW()) AND (md.date_end = '0000-00-00' OR md.date_end > NOW())) ORDER BY md.priority ASC, md.qty ASC, md.percentage ASC";
		$query = $this->db->query($sql);
		
		if ($query->num_rows > 1) {
			foreach ($query->rows as $discount) {
				$manufacturer_discount_queries[] = $discount;
			}
		} else {
			$manufacturer_discount_queries = $query->rows;
		}
		
		foreach ($manufacturer_discount_queries as $discount) {
			if (empty($manufacturer_discount_query)) {
					$manufacturer_discount_query = array (
						'name' 			=> $discount['name'],
						'percentage' 	=> $discount['percentage'],
						'qty'			=> $discount['qty']
					);
			} else if ($manufacturer_discount_query['percentage'] < $discount['percentage'] && $this->getManufacturerProductQty($manufacturer_id, $customer_group_id) >= $discount['qty']) {
					$manufacturer_discount_query = array (
						'name' 			=> $discount['name'],
						'percentage' 	=> $discount['percentage'],
						'qty'			=> $discount['qty']
					);
			}		
		
		
		}
		if (!empty($manufacturer_discount_query)) {
			$manufacturer_discount = array(
				'name' 			=> $manufacturer_discount_query['name'],
				'percentage'	=> $manufacturer_discount_query['percentage'],
				'qty'			=> $manufacturer_discount_query['qty']
			);
		}
		
		if (empty($manufacturer_discount)) {
			return false;
		} else if (!empty($manufacturer_discount['qty'])) {
			if ($this->getManufacturerProductQty($manufacturer_id, $customer_group_id) >= $manufacturer_discount['qty']) {
				return $manufacturer_discount;
			}
		} else {
			return $manufacturer_discount;
		}
	}
	
	protected function getManufacturerProductQty($manufacturer_id, $customer_group_id) {
		$manufacturer_qty = 0;
		
		if (version_compare(VERSION, '2.1.0.0', '>=')) {
				
			$cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
		
			foreach ($cart_query->rows as $cart) {
				
					$product = $cart['product_id'];
					$quantity = $cart['quantity'];
				
					$query = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product . "'");	
				
					$manufacturer = $query->row;
				
					$exclusive = $this->config->get('discounts_override_special_price') == 'exclusive' ? !$this->hasSpecialPrice($product, $customer_group_id) : 1 ;
				
					if ($exclusive) {
						if ($manufacturer_id == $manufacturer['manufacturer_id']) {
							$manufacturer_qty += $quantity;
						}
					}
			}
		} else {
			foreach ($this->session->data['cart'] as $key => $quantity) {
				$product = unserialize(base64_decode($key));
				
				$query = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product['product_id'] . "'");	
				
				$manufacturer = $query->row;
				
				$exclusive = $this->config->get('discounts_override_special_price') == 'exclusive' ? !$this->hasSpecialPrice($product['product_id'], $customer_group_id) : 1 ;
				
				if ($exclusive) {
					if ($manufacturer_id == $manufacturer['manufacturer_id']) {
						$manufacturer_qty += $quantity;
					}
				}
			}
		}
		
		return $manufacturer_qty;	
	}
	
	public function getOrdertotalDiscount($total) {
		
		$ordertotal_discount = array();
		
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}
		
		$sql = "SELECT percentage as discount, type, ordertotal FROM `" . DB_PREFIX . "ordertotal_discount` WHERE status = '1' AND ordertotal < '" . $this->cart->getTotal() . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY ordertotal DESC, priority ASC, percentage ASC LIMIT 1";
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows) {
			$ordertotal_discount = array(
				'discount'	=> $query->row['discount'],
				'ordertotal'=> $query->row['ordertotal'],
				'type'		=> $query->row['type']
			);
		}
		
		if (empty($ordertotal_discount)) {
			return false;
		} else {
			return $ordertotal_discount;
		}
	}
	
	public function getLoyaltyDiscount($total) {
		
		$ordertotal_discount = array();
		
		if (!$this->customer->isLogged()) {
			return false;
		} else {
			$customer_group_id = $this->customer->getGroupId();
			$customer_id = $this->customer->getId();
			
			$discounts_query = $this->db->query("SELECT percentage as discount, ordertotal, order_status FROM `" . DB_PREFIX . "loyalty_discount` WHERE status = '1' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY ordertotal ASC, priority ASC, percentage ASC");
			
			if(!empty($discounts_query->num_rows) && $discounts_query->num_rows == 1) {
				$discount = $discounts_query->row;

				$query = $this->db->query("SELECT SUM(total) as total FROM `" . DB_PREFIX . "order` WHERE customer_id = '" . (int)$customer_id . "' AND order_status_id IN (" . $discount['order_status'] . ")");
				
				if (!empty($query->num_rows)) {
					if ($query->row['total'] >= $discount['ordertotal']) {
						$loyalty_discount = array(
							'discount'	=> $discount['discount'],
							'ordertotal'=> $discount['ordertotal'],
						);
					}
				}
		
			} elseif ($discounts_query->num_rows > 1) {
				$discounts = $discounts_query->rows;
				
				foreach ($discounts as $discount) {
					$query = $this->db->query("SELECT SUM(total) as total FROM `" . DB_PREFIX . "order` WHERE customer_id = '" . (int)$customer_id . "' AND order_status_id IN (" . $discount['order_status'] . ")");
				
					if (!empty($query->num_rows)) {
						if ($query->row['total'] >= $discount['ordertotal']) {
							$loyalty_discount = array(
								'discount'	=> $discount['discount'],
								'ordertotal'=> $discount['ordertotal'],
							);
						}
					}
				}
				
			} else {
				return false;
			}
		
			if (empty($loyalty_discount)) {
				return false;
			} else {
				return $loyalty_discount;				
			}
		}
	
	}
	
	public function getCustomerDiscount($customer_id, $product_id) {
		
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_discount` WHERE `customer_id` = '" . (int)$customer_id. "'  AND `product_id` = '" . (int)$product_id. "' AND `status` = '1' ORDER BY quantity, priority, price");
		
		return $query->rows;
	}
	
	public function getCustomerGroupDiscount($customer_group_id) {
		
		$customer_group_discount = array();
		
		$sql = "SELECT percentage, customer_group_id FROM `" . DB_PREFIX . "customer_group_discount` WHERE status = '1' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, percentage ASC LIMIT 1";
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows) {
			$customer_group_discount = array(
				'percentage'		=> $query->row['percentage'],
				'customer_group_id' => $query->row['customer_group_id'],
			);
		}
		
		if (empty($customer_group_discount)) {
			return false;
		} else {
			return $customer_group_discount;
		}
	}
	
	public function getVolumeDiscount($product_id) {
		$this->load->model('catalog/product');
		
		$product = $this->model_catalog_product->getProduct($product_id);
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}
		
		if ($this->config->get('discounts_override_special_price') == 'exclusive' && $this->hasSpecialPrice($product_id, $customer_group_id)) {
			return false;
		}
		
		$volume_discount = array();
		$volume_discount_queries = array();
		
		$sql = "SELECT percentage as percentage, qty as qty FROM `" . DB_PREFIX . "volume_discount`  WHERE status = '1' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, qty ASC, percentage ASC";
		$query = $this->db->query($sql);
		
		if ($query->num_rows > 1) {
			foreach ($query->rows as $discount) {
				$volume_discount_queries[] = $discount;
			}
		} else {
			$volume_discount_queries = $query->rows;
		}
		
		foreach ($volume_discount_queries as $discount) {
			if (empty($volume_discount_query)) {
					$volume_discount_query = array (
						'name' 			=> $product['name'],
						'percentage' 	=> $discount['percentage'],
						'qty'			=> $discount['qty']
					);
			} else if ($volume_discount_query['percentage'] < $discount['percentage'] && $this->getVolumeProductQty($product_id, $customer_group_id) >= $discount['qty']) {
					$volume_discount_query = array (
						'name' 			=> $product['name'],
						'percentage' 	=> $discount['percentage'],
						'qty'			=> $discount['qty']
					);
			}		
		
		
		}
		if (!empty($volume_discount_query)) {
			$volume_discount = array(
				'name' 			=> $product['name'],
				'percentage'	=> $volume_discount_query['percentage'],
				'qty'			=> $volume_discount_query['qty']
			);
		}
		
		if (empty($volume_discount)) {
			return false;
		} else if (!empty($volume_discount['qty'])) {
			if ($this->getVolumeProductQty($product_id, $customer_group_id) >= $volume_discount['qty']) {
				return $volume_discount;
			}
		} else {
			return $volume_discount;
		}
	}
	
	protected function getVolumeProductQty($product_id, $customer_group_id) {
		$qty = 0;
		
		
		if (version_compare(VERSION, '2.1.0.0', '>=')) {		
			$cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
	
			foreach ($cart_query->rows as $cart) {
			
					$product = $cart['product_id'];
					$quantity = $cart['quantity'];
			
					$exclusive = $this->config->get('discounts_override_special_price') == 'exclusive' ? !$this->hasSpecialPrice($product, $customer_group_id) : 1 ;
			
					if ($exclusive) {
						if ($product_id == $cart['product_id']) {
							$qty += $quantity;
						}
					}
			}
		} else {
			foreach ($this->session->data['cart'] as $key => $quantity) {
				$product = unserialize(base64_decode($key));
		
				$exclusive = $this->config->get('discounts_override_special_price') == 'exclusive' ? !$this->hasSpecialPrice($product['product_id'], $customer_group_id) : 1 ;
			
					if ($exclusive) {
						if ($product_id == $product['product_id']) {
							$qty += $quantity;
						}
					}
			
			}
		}
		
		
		
		return $qty;	
	}
	
	protected function hasDiscountPrice($product_id, $customer_group_id) {
		
		$product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");

		if ($product_special_query->num_rows) {
			return true;
		} else {
			return false;
		}
	
	}
	
	protected function hasSpecialPrice($product_id, $customer_group_id) {
		
		$product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");

		if ($product_special_query->num_rows) {
			return true;
		} else {
			return false;
		}
	
	}
	
	public function specialPriceOverride($product_id, $special_price, $standard_price, $discount_price, $customer_group_id) {
	
		$discount_override = $this->config->get('discounts_override_discount_price');
		$special_override = $this->config->get('discounts_override_special_price');
		
		$discounts = $this->getDiscountsSpecialPrice($product_id, $customer_group_id);
				
		if ($discount_override == 'override') {
			$price = $standard_price;
		} else {
			$price = $discount_price ? $discount_price : $standard_price;
		}
					
		switch ($special_override) {
			case 'default':
				$display_special = $special_price ? $special_price : $price ;
				$calc_price = $display_special;
				$calc_special_price = $this->calcSpecialPrice($discounts,$display_special,$calc_price);
				if (!empty($calc_special_price)) {
					$special_price = $calc_special_price;
				}
				break;
			case 'exclusive':
				if (empty($special_price)) {
					$display_special = $price;
					$special_price = $this->calcSpecialPrice($discounts,$display_special,$price);
				}
				break;
			case 'override':
				$display_special = $price ;
				$special_price = $this->calcSpecialPrice($discounts,$display_special,$price);
				break;
		}
 		return $special_price;
	}
	
	protected function getDiscountsSpecialPrice($product_id, $customer_group_id) {
		
		$discounts = array();
		
		if ($this->config->get('category_discount_status')) {
			$query = $this->db->query("SELECT ptc.category_id as category_id, c.parent_id as parent_id FROM `" . DB_PREFIX . "product_to_category` ptc INNER JOIN `" . DB_PREFIX . "category` c ON ptc.category_id = c.category_id WHERE product_id = '" . (int)$product_id . "' ORDER BY c.sort_order DESC, c.category_id ASC");
			
			if ($query->num_rows) {
				$discounts['category'] = $this->getCategoryDiscountsArray($query->rows, $customer_group_id, true);
			}
		}
		
		if ($this->config->get('manufacturer_discount_status')) {
			$subquery = $this->db->query("SELECT manufacturer_id FROM `" . DB_PREFIX . "product` WHERE product_id = '" . (int)$product_id . "'");	

			$manufacturer_id = $subquery->row['manufacturer_id'];

			$discount_query = $this->db->query("SELECT *, md.qty as quantity FROM `" . DB_PREFIX . "manufacturer_discount` md INNER JOIN `" . DB_PREFIX . "manufacturer` m ON md.manufacturer_id = m.manufacturer_id WHERE md.status = '1' AND md.manufacturer_id = '" . (int)$manufacturer_id . "' AND md.customer_group_id = '" . (int)$customer_group_id . "' AND ((md.date_start = '0000-00-00' OR md.date_start < NOW()) AND (qty = '0' OR qty = '1') AND (md.date_end = '0000-00-00' OR md.date_end > NOW())) ORDER BY md.qty ASC, md.percentage ASC LIMIT 1");
	
			if ($discount_query->num_rows) {
				
				$discounts['manufacturer'] = array (
					'qty' 			=> $discount_query->row['quantity'],
					'percentage'  	=> $discount_query->row['percentage']
				);
				
			}
		}
		if ($this->config->get('customer_group_discount_status')) {
			if ($this->customer->isLogged()) {
				$customer_group_id2 = $this->customer->getGroupId();
			} else {
				$customer_group_id2 = 0;
			}
	
			$discount_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_group_discount` WHERE status = '1' AND customer_group_id = '" . (int)$customer_group_id2 . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, percentage ASC LIMIT 1");

			if ($discount_query->num_rows){
				$discounts['customer_group'] = array (
					'qty' 			=> 0,
					'percentage'  	=> $discount_query->row['percentage']
				);
			}	
		}
		if ($this->config->get('volume_discount_status')) {

			$discount_query = $this->db->query("SELECT percentage, qty as quantity FROM `" . DB_PREFIX . "volume_discount` WHERE status = '1' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (qty = '0' OR qty = '1') AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY qty ASC, percentage ASC LIMIT 1");
	
			if ($discount_query->num_rows) {
				
				$discounts['volume'] = array (
					'qty' 			=> $discount_query->row['quantity'],
					'percentage'  	=> $discount_query->row['percentage']
				);
				
			}
		}
		
		return $discounts;
	}
	
	public function discountPriceOverride($product_id,$customer_group_id) {
		
		$discount_override = $this->config->get('discounts_override_discount_price');
		$special_override = $this->config->get('discounts_override_special_price');
		
		$query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
		$price = $query->row['price'];
		
		if ($special_override != 'override') {
			$special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");
			if ($special_query->num_rows) {
				$special_price =  $special_query->row['price'];
			}
		}
		
		if ($special_override == 'default' && !empty($special_price)) {
			$price = $special_price;
		}
		
		if ($discount_override == 'default' && empty($special_price)) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND quantity > 1 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity ASC, priority ASC, price ASC");
			$regular_discounts = $query->rows;
		}
		
		$i = array();
		
		if (!empty($regular_discounts)) {
			foreach ($regular_discounts as $discount) {
				$i[] = $discount['quantity'];
			}
			$discounts_prices = $this->createPricesArray($price,$regular_discounts);
			
			$response = $this->getDiscountsDiscountPrice($product_id, $customer_group_id, $i);
		
			$discounts = $response['discounts'];
			$i = $response['i'];
		
			if (!empty($discounts)) {
				$discounts_tmp = $this->addArrays($this->createPercentageArray($discounts, $i));

				if ($discounts_tmp) {
					$highest_qty = end($i);
					if (empty($discounts_prices[$highest_qty])) {
						$price_highest_qty = end($discounts_prices);
						$diff = $highest_qty - $price_highest_qty['quantity'];
						while (!empty($diff)) {
							$price_highest_qty['quantity']++;
							array_push($discounts_prices,$price_highest_qty);
							$diff--;
						}
					}
					$discounts = array();		
					foreach ($i as $key) {
						$discount_percentage = $discounts_tmp[$key]['percentage'] > 100 ? 100 : $discounts_tmp[$key]['percentage'];
						$discounts[] = array (
							'quantity' 	=> empty($discounts_prices[$key]['quantity']) ? 1 : $discounts_prices[$key]['quantity'] ,
							'price'		=> $discounts_prices[$key]['price'] - ($discounts_prices[$key]['price'] / 100 * $discount_percentage)
						);
					}
				}
			}
			return $discounts;	
		} else {
  			
  			if($special_override == 'default' && !empty($special_price)) {
  				$price = $special_price;
  			}
  			
  			if ($special_override == 'exclusive' && !empty($special_price)) {
  				return array();
  			} else {
				$response = $this->getDiscountsDiscountPrice($product_id, $customer_group_id, $i);
		
				$discounts = $response['discounts'];
				$i = $response['i'];
			
				if (!empty($discounts)) {
					$discounts_tmp = $this->addArrays($this->createPercentageArray($discounts, $i));
					if ($discounts_tmp) {
				
						$discounts = array();
						asort($i);
				
						foreach ($i as $key) {
							$discount_percentage = $discounts_tmp[$key]['percentage'] > 100 ? 100 : $discounts_tmp[$key]['percentage'];
							$discounts[] = array (
								'quantity' 	=> empty($discounts_tmp[$key]['quantity']) ? 1 : $discounts_tmp[$key]['quantity'] ,
								'price'		=> $price - ($price / 100 * $discount_percentage)
							);
						}
					}
				
				}
				return $discounts;
			}
		}
	}
	
	protected function addArrays($array) {
	
		$tmp = array_shift($array);

		$prev_discount = !empty($tmp[0]['percentage']) ? $tmp[0]['percentage'] : 0 ;	

		foreach ($tmp as $key => $qty) {						
			if (empty($qty['percentage'])) {
				$tmp[$key]['percentage'] = $prev_discount;
			} else {
				$prev_discount = $qty['percentage'];
			}
		}
		foreach ($array as $key => $discounts) {	
			$i = 0;
			$prev_discount = 0;
			foreach ($discounts as $discount) {
				if ((empty($tmp[$i]['percentage']) && !empty($discount['percentage'])) || (!empty($tmp[$i]['percentage']) && !empty($discount['percentage']))) {
					$tmp[$i]['percentage'] += $discount['percentage'];
					$prev_discount = $discount['percentage'];
				} else {
					$tmp[$i]['percentage'] += $prev_discount;
				}
				$i++;
			}
		}	
		return $tmp;
	}
	
	protected function createPercentageArray($discounts, &$i) {
	
		if (!empty($i)) {
			asort($i);
			$largest_qty = end($i);
		} else {
			$largest_qty = 0;
		}

		foreach($discounts as $key => $discount) {
			$qty = end($discount);
			$largest_qty = empty($largest_qty) ? $qty['quantity'] :  ($qty['quantity'] > $largest_qty) ? $qty['quantity'] : $largest_qty ;
		}

		foreach($discounts as $key => $discount) {
			for ($count = 0; $count <= $largest_qty; $count++) {
				$percentage_array[$key][] = array('quantity' => $count, 'percentage' => 0);
			}
			foreach ($discount as $qty) {
				$percentage_array[$key][$qty['quantity']]['percentage'] = $percentage_array[$key][$qty['quantity']]['percentage'] + $qty['percentage'];
			}
		}
		return $percentage_array;
	}
	
	protected function createPricesArray($price, $discounts_prices) {

		$price_array = array();

		$largest_qty = end($discounts_prices);

		for ($count = 0; $count <= $largest_qty['quantity']; $count++) {
			$price_array[] = array('quantity' => $count, 'price' => 0);
		}
		foreach ($discounts_prices as $qty) {
			$price_array[$qty['quantity']]['price'] = $price_array[$qty['quantity']]['price'] + $qty['price'];
		}

		$j = 0;
		$prev_price = !empty($price_array[0]['price']) ? $price_array[0]['price'] : $price ;	
		foreach ($price_array as $discount) {					
			if (empty($price_array[$j]['price'])) {
				$price_array[$j]['price'] += $prev_price;
			} else {
				$prev_price = $discount['price'];
			}

			$j++;
		}
		return $price_array;		
	}
	
	protected function getDiscountsDiscountPrice($product_id, $customer_group_id, $i) {
		$discounts = array();
		if ($this->config->get('category_discount_status')) {
			$query = $this->db->query("SELECT ptc.category_id as category_id, c.parent_id as parent_id FROM `" . DB_PREFIX . "product_to_category` ptc INNER JOIN `" . DB_PREFIX . "category` c ON ptc.category_id = c.category_id WHERE product_id = '" . (int)$product_id . "' ORDER BY c.sort_order DESC, c.category_id ASC");
			if ($query->num_rows) {
				$discounts['category'] = $this->getCategoryDiscountsArray($query->rows, $customer_group_id, false, $i);
			}
		}			
		if ($this->config->get('manufacturer_discount_status')) {
			$query = $this->db->query("SELECT manufacturer_id FROM `" . DB_PREFIX . "product` WHERE product_id = '" . (int)$product_id . "'");	

			$manufacturer_id = $query->row['manufacturer_id'];

			$discount_query = $this->db->query("SELECT *, md.qty as quantity FROM `" . DB_PREFIX . "manufacturer_discount` md INNER JOIN `" . DB_PREFIX . "manufacturer` m ON md.manufacturer_id = m.manufacturer_id WHERE md.status = '1' AND md.manufacturer_id = '" . (int)$manufacturer_id . "' AND md.customer_group_id = '" . (int)$customer_group_id . "' AND ((md.date_start = '0000-00-00' OR md.date_start < NOW()) AND (md.date_end = '0000-00-00' OR md.date_end > NOW())) ORDER BY md.qty ASC, md.percentage ASC");

			if ($discount_query) {
				foreach ($discount_query->rows as $discount) {
					$discounts['manufacturer'][] = array (
						'quantity' 		=> $discount['quantity'],
						'percentage'  	=> $discount['percentage']
					);
					if (!in_array($discount['quantity'], $i)) {
						$i[] = $discount['quantity'];
					}
				}
			}
		}
		if ($this->config->get('customer_group_discount_status')) {
			if ($this->customer->isLogged()) {
				$customer_group_id2 = $this->customer->getGroupId();
			} else {
				$customer_group_id2 = 0;
			}

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_group_discount` WHERE status = '1' AND customer_group_id = '" . (int)$customer_group_id2 . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, percentage ASC LIMIT 1");

			if ($query->num_rows){
				$discounts['customer_group'][] = array (
					'quantity' 		=> 0,
					'percentage'  	=> $query->row['percentage']
				);
			}
		}
		if ($this->config->get('volume_discount_status')) {

			$discount_query = $this->db->query("SELECT percentage, qty as quantity FROM `" . DB_PREFIX . "volume_discount`WHERE status = '1' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY qty ASC, percentage ASC");
	
			if ($discount_query) {
				foreach ($discount_query->rows as $discount) {
					$discounts['volume'][] = array (
						'quantity' 		=> $discount['quantity'],
						'percentage'  	=> $discount['percentage']
					);
					if (!in_array($discount['quantity'], $i)) {
						$i[] = $discount['quantity'];
					}
				}
			}
		}
		
		return array('discounts' => $discounts, 'i' => $i);
	}
 	
	protected function getCategoryDiscountsArray($categories, $customer_group_id, $special = false, &$i = array()) {
		$discounts['category'] = array();
		if (count($categories > 1)) {
			foreach ($categories as $category) {
				$discounts_cat_id = $this->categoryDiscountQuery($category['category_id'], $customer_group_id, $special);	
				if (!empty($discounts_cat_id)) {
					$discount_query[$category['category_id']][] = $discounts_cat_id;
				}
				if (empty($discount_query) && !empty($category['parent_id'])) {
					$discounts_par_id = $this->categoryDiscountQuery($category['parent_id'], $customer_group_id, $special, true);	
					if(!empty($discounts_par_id)) {
						$discount_query[$category['parent_id']][] = $discounts_par_id;
					}
				}
			}
			if (!empty($discount_query) && !$special) {
				foreach ($discount_query as $key => $value) {
					if (empty($high)) {
						$high[$key] = $value[0][0]['percentage'];
					} else {
						arsort($high);
						if ($value[0][0]['percentage'] > reset($high)) {
							$high[$key] =  $value[0][0]['percentage'];
						}
					}
					
				}
				reset($high);
				$discount_query = $discount_query[key($high)][0];
				foreach ($discount_query as $discount) {
					$discounts['category'][] = array (
						'quantity' 		=> $discount['quantity'],
						'percentage'  	=> $discount['percentage']
					);
					if (!in_array($discount['quantity'], $i)) {
						$i[] = $discount['quantity'];
					}
				}
			} elseif (!empty($discount_query) && $special) {
				foreach ($discount_query as $key => $value) {
					if (empty($high)) {
						$high[$key] = $value[0]['percentage'];
					} else {
						arsort($high);
						if ($value[0]['percentage'] > reset($high)) {
							$high[$key] =  $value[0]['percentage'];
						}
					}
				}
				reset($high);
				$discount_query = $discount_query[key($high)];
				foreach ($discount_query as $discount) {
					$discounts['category'] = array (
						'quantity' 		=> $discount['quantity'],
						'percentage'  	=> $discount['percentage']
					);
				}			
			}
		} else {
			$category = $query->row;
			$discount_query = $this->categoryDiscountQuery($category['category_id'], $customer_group_id, $special);
			if ($discount_query) {
				foreach ($discount_query as $discount) {
					if ($special) {
						$discounts['category'] = array (
							'quantity' 		=> $discount['quantity'],
							'percentage'  	=> $discount['percentage']
						);
					} else {
						$discounts['category'][] = array (
							'quantity' 		=> $discount['quantity'],
							'percentage'  	=> $discount['percentage']
						);
						if (!in_array($discount['quantity'], $i)) {
							$i[] = $discount['quantity'];
						}
					}
				}
			} elseif (empty($discount_query) && !empty($category['parent_id'])) {
				$discount_query = $this->categoryDiscountQuery($category['parent_id'], $customer_group_id, $special, true);			
				if ($discount_query) {
					foreach ($discount_query as $discount) {
						if ($special) {
							$discounts['category'] = array (
								'quantity' 		=> $discount['quantity'],
								'percentage'  	=> $discount['percentage']
							);
						} else {
							$discounts['category'][] = array (
								'quantity' 		=> $discount['quantity'],
								'percentage'  	=> $discount['percentage']
							);
							if (!in_array($discount['quantity'], $i)) {
								$i[] = $discount['quantity'];
							}
						}
					}
				}
			}
		}
		return $discounts['category'];
	}
	
	protected function categoryDiscountQuery($category_id, $customer_group_id, $flag = true, $parent = false) {
		$sql = "SELECT *, qty as quantity FROM " . DB_PREFIX . "category_discount cd WHERE category_id = '" . (int)$category_id . "' AND status = '1' AND customer_group_id = '" . (int)$customer_group_id . "' ";
		if ($flag) {
			$sql .= " AND (qty = '0' OR qty = '1') ";
		}
		if ($parent) {
			$sql .= " AND affect = '1'";
		}
		$sql .= " AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, qty ASC, percentage DESC";
		if ($flag) {
			$sql .= " LIMIT 1";
		}
		$discount_query = $this->db->query($sql);
		
		if ($flag) {
			return !empty($discount_query->num_rows) ? $discount_query->row : false ;
		} else {
			return !empty($discount_query->num_rows) ? $discount_query->rows : false ;
		}
	}
	
	protected function categoryDiscountQuery2($category_id, $customer_group_id, $flag = false) {
		$sql = "SELECT cd.percentage as percentage, cd.qty as qty, cdesc.name as name, cd.affect as affect, cd.category_id as category_id FROM `" . DB_PREFIX . "category_discount` cd INNER JOIN `" . DB_PREFIX . "category_description` cdesc ON cd.category_id = cdesc.category_id WHERE cd.status = '1' AND cd.category_id = '" . (int)$category_id . "' AND cd.customer_group_id = '" . (int)$customer_group_id . "' AND ((cd.date_start = '0000-00-00' OR cd.date_start < NOW()) AND (cd.date_end = '0000-00-00' OR cd.date_end > NOW())) AND cdesc.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		if ($flag) { 
			$sql .= " AND cd.affect = '1'";
		}
		
		$sql .= " ORDER BY cd.priority ASC, cd.qty ASC, cd.percentage ASC"; 
		
		$query = $this->db->query($sql);
	
		return $query->rows;	
	}
	
	protected function calcSpecialPrice ($discounts, $display_special, $price) {
		foreach ($discounts as $key => $discount) {
			if ($key == 'category' || $key == 'manufacturer' || $key == 'volume' ) {
				if (!empty($discount) && (empty($discount['qty']) || $discount['qty'] == 1)) {
					$discount[$key]['amount'] = $price * ($discount['percentage']/100);
				}
			} else if ($key == 'customer_group') {
				if (!empty($discount)) {
					$discount[$key]['amount'] = $price * ($discount['percentage']/100);
				}
			}
			if (!empty ($discount[$key]['amount'])) {
				$display_special -= $discount[$key]['amount'];
			}
		}
		
		if ($display_special != $price) {
			$special_price = $display_special;
		} else {
			$special_price = 0;
		}
		
		return $special_price;
	}
}
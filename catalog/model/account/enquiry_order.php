<?php
	class ModelAccountEnquiryOrder extends Model {

		public function getOrderPrint($enquiry_order_id) {
			
			$enquiry_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "enquiry_order` WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "' AND enquiry_order_status_id > '0'");
			
			//debug($enquiry_query);

			if ($enquiry_query->num_rows) {
				$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$enquiry_query->row['payment_country_id'] . "'");
				
				if ($country_query->num_rows) { 
					$payment_iso_code_2 = $country_query->row['iso_code_2'];
					$payment_iso_code_3 = $country_query->row['iso_code_3'];
				}
				else {
					$payment_iso_code_2 = '';
					$payment_iso_code_3 = '';
				}
				
				$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$enquiry_query->row['payment_zone_id'] . "'");
				
				if ($zone_query->num_rows) {
					$payment_zone_code = $zone_query->row['code'];
				}
				else {
					$payment_zone_code = '';
				}
				
				$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$enquiry_query->row['shipping_country_id'] . "'");
				
				if ($country_query->num_rows) {
					$shipping_iso_code_2 = $country_query->row['iso_code_2'];
					$shipping_iso_code_3 = $country_query->row['iso_code_3'];
				}
				else {
					$shipping_iso_code_2 = '';
					$shipping_iso_code_3 = '';
				}
				
				$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$enquiry_query->row['shipping_zone_id'] . "'");
				
				if ($zone_query->num_rows) {
					$shipping_zone_code = $zone_query->row['code'];
				}
				else {
					$shipping_zone_code = '';
				}
				
				return array(
				'enquiry_order_id'       => $enquiry_query->row['enquiry_order_id'],
				'invoice_no'              => $enquiry_query->row['invoice_no'],
				'invoice_prefix'          => $enquiry_query->row['invoice_prefix'],
				'store_id'                => $enquiry_query->row['store_id'],
				'store_name'              => $enquiry_query->row['store_name'],
				'store_url'               => $enquiry_query->row['store_url'],
				'customer_id'             => $enquiry_query->row['customer_id'],
				'firstname'               => $enquiry_query->row['firstname'],
				'lastname'                => $enquiry_query->row['lastname'],
				'telephone'               => $enquiry_query->row['telephone'],
				'fax'                     => $enquiry_query->row['fax'],
				'email'                   => $enquiry_query->row['email'],
				'payment_firstname'       => $enquiry_query->row['payment_firstname'],
				'payment_lastname'        => $enquiry_query->row['payment_lastname'],
				'payment_company'         => $enquiry_query->row['payment_company'],
				'payment_address_1'       => $enquiry_query->row['payment_address_1'],
				'payment_address_2'       => $enquiry_query->row['payment_address_2'],
				'payment_postcode'        => $enquiry_query->row['payment_postcode'],
				'payment_city'            => $enquiry_query->row['payment_city'],
				'payment_zone_id'         => $enquiry_query->row['payment_zone_id'],
				'payment_zone'            => $enquiry_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $enquiry_query->row['payment_country_id'],
				'payment_country'         => $enquiry_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $enquiry_query->row['payment_address_format'],
				'payment_method'          => $enquiry_query->row['payment_method'],
				'shipping_firstname'      => $enquiry_query->row['shipping_firstname'],
				'shipping_lastname'       => $enquiry_query->row['shipping_lastname'],
				'shipping_company'        => $enquiry_query->row['shipping_company'],
				'shipping_address_1'      => $enquiry_query->row['shipping_address_1'],
				'shipping_address_2'      => $enquiry_query->row['shipping_address_2'],
				'shipping_postcode'       => $enquiry_query->row['shipping_postcode'],
				'shipping_city'           => $enquiry_query->row['shipping_city'],
				'shipping_zone_id'        => $enquiry_query->row['shipping_zone_id'],
				'shipping_zone'           => $enquiry_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $enquiry_query->row['shipping_country_id'],
				'shipping_country'        => $enquiry_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $enquiry_query->row['shipping_address_format'],
				'shipping_method'         => $enquiry_query->row['shipping_method'],
				'comment'                 => $enquiry_query->row['comment'],
				'total'                   => $enquiry_query->row['total'],
				'enquiry_order_status_id' => $enquiry_query->row['enquiry_order_status_id'],
				'language_id'             => $enquiry_query->row['language_id'],
				'currency_id'             => $enquiry_query->row['currency_id'],
				'currency_code'           => $enquiry_query->row['currency_code'],
				'currency_value'          => $enquiry_query->row['currency_value'],
				'date_modified'           => $enquiry_query->row['date_modified'],
				'date_added'              => $enquiry_query->row['date_added'],
				'ip'                      => $enquiry_query->row['ip']
				);
			}
			else {
				return false;
			}
		}
		
		public function getOrder($enquiry_order_id) {
			$enquiry_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "enquiry_order` WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND enquiry_order_status_id > '0'");
			
			if ($enquiry_query->num_rows) {
				$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$enquiry_query->row['payment_country_id'] . "'");
				
				if ($country_query->num_rows) {
					$payment_iso_code_2 = $country_query->row['iso_code_2'];
					$payment_iso_code_3 = $country_query->row['iso_code_3'];
				}
				else {
					$payment_iso_code_2 = '';
					$payment_iso_code_3 = '';
				}
				
				$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$enquiry_query->row['payment_zone_id'] . "'");
				
				if ($zone_query->num_rows) {
					$payment_zone_code = $zone_query->row['code'];
				}
				else {
					$payment_zone_code = '';
				}
				
				$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$enquiry_query->row['shipping_country_id'] . "'");
				
				if ($country_query->num_rows) {
					$shipping_iso_code_2 = $country_query->row['iso_code_2'];
					$shipping_iso_code_3 = $country_query->row['iso_code_3'];
				}
				else {
					$shipping_iso_code_2 = '';
					$shipping_iso_code_3 = '';
				}
				
				$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$enquiry_query->row['shipping_zone_id'] . "'");
				
				if ($zone_query->num_rows) {
					$shipping_zone_code = $zone_query->row['code'];
				}
				else {
					$shipping_zone_code = '';
				}
				
				return array(
				'enquiry_order_id'        => $enquiry_query->row['enquiry_order_id'],
				'invoice_no'              => $enquiry_query->row['invoice_no'],
				'invoice_prefix'          => $enquiry_query->row['invoice_prefix'],
				'store_id'                => $enquiry_query->row['store_id'],
				'store_name'              => $enquiry_query->row['store_name'],
				'store_url'               => $enquiry_query->row['store_url'],
				'customer_id'             => $enquiry_query->row['customer_id'],
				'firstname'               => $enquiry_query->row['firstname'],
				'lastname'                => $enquiry_query->row['lastname'],
				'telephone'               => $enquiry_query->row['telephone'],
				'fax'                     => $enquiry_query->row['fax'],
				'email'                   => $enquiry_query->row['email'],
				'payment_firstname'       => $enquiry_query->row['payment_firstname'],
				'payment_lastname'        => $enquiry_query->row['payment_lastname'],
				'payment_company'         => $enquiry_query->row['payment_company'],
				'payment_address_1'       => $enquiry_query->row['payment_address_1'],
				'payment_address_2'       => $enquiry_query->row['payment_address_2'],
				'payment_postcode'        => $enquiry_query->row['payment_postcode'],
				'payment_city'            => $enquiry_query->row['payment_city'],
				'payment_zone_id'         => $enquiry_query->row['payment_zone_id'],
				'payment_zone'            => $enquiry_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $enquiry_query->row['payment_country_id'],
				'payment_country'         => $enquiry_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $enquiry_query->row['payment_address_format'],
				'payment_method'          => $enquiry_query->row['payment_method'],
				'shipping_firstname'      => $enquiry_query->row['shipping_firstname'],
				'shipping_lastname'       => $enquiry_query->row['shipping_lastname'],
				'shipping_company'        => $enquiry_query->row['shipping_company'],
				'shipping_address_1'      => $enquiry_query->row['shipping_address_1'],
				'shipping_address_2'      => $enquiry_query->row['shipping_address_2'],
				'shipping_postcode'       => $enquiry_query->row['shipping_postcode'],
				'shipping_city'           => $enquiry_query->row['shipping_city'],
				'shipping_zone_id'        => $enquiry_query->row['shipping_zone_id'],
				'shipping_zone'           => $enquiry_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $enquiry_query->row['shipping_country_id'],
				'shipping_country'        => $enquiry_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $enquiry_query->row['shipping_address_format'],
				'shipping_method'         => $enquiry_query->row['shipping_method'],
				'comment'                 => $enquiry_query->row['comment'],
				'total'                   => $enquiry_query->row['total'],
				'enquiry_order_status_id' => $enquiry_query->row['enquiry_order_status_id'],
				'language_id'             => $enquiry_query->row['language_id'],
				'currency_id'             => $enquiry_query->row['currency_id'],
				'currency_code'           => $enquiry_query->row['currency_code'],
				'currency_value'          => $enquiry_query->row['currency_value'],
				'date_modified'           => $enquiry_query->row['date_modified'],
				'date_added'              => $enquiry_query->row['date_added'],
				'ip'                      => $enquiry_query->row['ip']
				);
			}
			else {
				return false;
			}
		}
		
		public function getOrders($start = 0, $limit = 20) {
			if ($start < 0) {
				$start = 0;
			}
			
			if ($limit < 1) {
				$limit = 1;
			}
			
			$query = $this->db->query("SELECT o. enquiry_order_id, o.firstname, o.lastname, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "enquiry_order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.enquiry_order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int)$this->customer->getId() . "' AND o.enquiry_order_status_id > '0' AND o.store_id = '" . (int)$this->config->get('config_store_id') . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o. enquiry_order_id DESC LIMIT " . (int)$start . "," . (int)$limit);
			
			// debug($query->rows);

			return $query->rows;
		}
		
		public function getOrderProduct($enquiry_order_id, $enquiry_product_id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "enquiry_order_product WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "' AND enquiry_order_product_id = '" . (int)$enquiry_product_id . "'");
			
			return $query->row;
		}
		
		public function getOrderProducts($enquiry_order_id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "enquiry_order_product WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "'");
			
			return $query->rows;
		}
		
		public function getOrderOptions($enquiry_order_id, $enquiry_product_id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "enquiry_order_option WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "' AND enquiry_order_product_id = '" . (int)$enquiry_product_id . "'");
			
			return $query->rows;
		}
		
		public function getOrderVouchers($enquiry_order_id) {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "enquiry_order_voucher` WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "'");
			
			return $query->rows;
		}
		
		public function getOrderTotals($enquiry_order_id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "enquiry_order_total WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "' ORDER BY sort_order");
			
			return $query->rows;
		}
		
		public function getOrderHistories($enquiry_order_id) {
			$query = $this->db->query("SELECT date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "enquiry_order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.enquiry_order_status_id = os.order_status_id WHERE oh. enquiry_order_id = '" . (int)$enquiry_order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added");
			
			return $query->rows;
		}
		
		public function getTotalOrders() {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "enquiry_order` o WHERE customer_id = '" . (int)$this->customer->getId() . "' AND o.enquiry_order_status_id > '0' AND o.store_id = '" . (int)$this->config->get('config_store_id') . "'");
			
			return $query->row['total'];
		}
		
		public function getTotalOrderProductsByOrderId($enquiry_order_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "enquiry_order_product WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "'");
			
			return $query->row['total'];
		}
		
		public function getTotalOrderVouchersByOrderId($enquiry_order_id) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "enquiry_order_voucher` WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "'");
			
			return $query->row['total'];
		}
	}		
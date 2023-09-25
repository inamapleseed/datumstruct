<?php
class ModelCheckoutEnquiry extends Model {

	public function getOrderProductIds($enquiry_order_id) {
		$sql = "SELECT product_id FROM " . DB_PREFIX . "enquiry_order_product " .
		"WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "'";
		return $this->db->query($sql)->rows;
	}

	public function addOrder($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "enquiry_order` SET invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_unit_no = '" . $this->db->escape($data['payment_unit_no']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', payment_custom_field = '" . $this->db->escape(isset($data['payment_custom_field']) ? json_encode($data['payment_custom_field']) : '') . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_unit_no = '" . $this->db->escape($data['shipping_unit_no']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', shipping_custom_field = '" . $this->db->escape(isset($data['shipping_custom_field']) ? json_encode($data['shipping_custom_field']) : '') . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', total = '" . (float)$data['total'] . "', affiliate_id = '" . (int)$data['affiliate_id'] . "', commission = '" . (float)$data['commission'] . "', marketing_id = '" . (int)$data['marketing_id'] . "', tracking = '" . $this->db->escape($data['tracking']) . "', language_id = '" . (int)$data['language_id'] . "', currency_id = '" . (int)$data['currency_id'] . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', currency_value = '" . (float)$data['currency_value'] . "', ip = '" . $this->db->escape($data['ip']) . "', forwarded_ip = '" .  $this->db->escape($data['forwarded_ip']) . "', user_agent = '" . $this->db->escape($data['user_agent']) . "', accept_language = '" . $this->db->escape($data['accept_language']) . "', date_added = NOW(), date_modified = NOW(), reward_earn='".(int)$data['reward_earn']."'");

		$enquiry_order_id = $this->db->getLastId();

		// Products
		if (isset($data['products'])) {
			foreach ($data['products'] as $product) {

				$sku = '';
				if( isset($product['sku']) ){
					$sku = $product['sku'];
				}

				$this->db->query("INSERT INTO " . DB_PREFIX . "enquiry_order_product SET enquiry_order_id = '" . (int)$enquiry_order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (int)$product['quantity'] . "', price = '" . (float)$product['price'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', reward = '" . (int)$product['reward'] . "', sku='". $this->db->escape($sku) ."'");

				$enquiry_product_id = $this->db->getLastId();

				foreach ($product['option'] as $option) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "enquiry_order_option SET enquiry_order_id = '" . (int)$enquiry_order_id . "', enquiry_order_product_id = '" . (int)$enquiry_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
				}
			}
		}

		// Gift Voucher
		$this->load->model('extension/total/voucher');

		// Vouchers
		if (isset($data['vouchers'])) {
			foreach ($data['vouchers'] as $voucher) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "enquiry_order_voucher SET enquiry_order_id = '" . (int)$enquiry_order_id . "', description = '" . $this->db->escape($voucher['description']) . "', code = '" . $this->db->escape($voucher['code']) . "', from_name = '" . $this->db->escape($voucher['from_name']) . "', from_email = '" . $this->db->escape($voucher['from_email']) . "', to_name = '" . $this->db->escape($voucher['to_name']) . "', to_email = '" . $this->db->escape($voucher['to_email']) . "', voucher_theme_id = '" . (int)$voucher['voucher_theme_id'] . "', message = '" . $this->db->escape($voucher['message']) . "', amount = '" . (float)$voucher['amount'] . "'");

				$enquiry_voucher_id = $this->db->getLastId();

				$voucher_id = $this->model_extension_total_voucher->addVoucher($enquiry_order_id, $voucher);

				$this->db->query("UPDATE " . DB_PREFIX . "enquiry_order_voucher SET voucher_id = '" . (int)$voucher_id . "' WHERE order_voucher_id = '" . (int)$enquiry_voucher_id . "'");
			}
		}

		// Totals
		if (isset($data['totals'])) {
			foreach ($data['totals'] as $total) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "enquiry_order_total SET enquiry_order_id = '" . (int)$enquiry_order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
			}
		}

		return $enquiry_order_id;
	}

	public function editOrder($enquiry_order_id, $data) {

		if( !isset($data['reward_earn']) ){ 
			$data['reward_earn'] = 0;
		}

		// Void the order first
		$this->addOrderHistory($enquiry_order_id, 0);

		$this->db->query("UPDATE `" . DB_PREFIX . "enquiry_order` SET invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(json_encode($data['custom_field'])) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_unit_no = '" . $this->db->escape($data['payment_unit_no']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', payment_custom_field = '" . $this->db->escape(json_encode($data['payment_custom_field'])) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_unit_no = '" . $this->db->escape($data['shipping_unit_no']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', shipping_custom_field = '" . $this->db->escape(json_encode($data['shipping_custom_field'])) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', total = '" . (float)$data['total'] . "', affiliate_id = '" . (int)$data['affiliate_id'] . "', commission = '" . (float)$data['commission'] . "', date_modified = NOW(), reward_earn='".(int)$data['reward_earn']."' WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "enquiry_order_product WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "enquiry_order_option WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "'");

		// Products
		if (isset($data['products'])) {
			foreach ($data['products'] as $product) {

				$sku = '';
				if( isset($product['sku']) ){
					$sku = $product['sku'];
				}

				$this->db->query("INSERT INTO " . DB_PREFIX . "enquiry_order_product SET enquiry_order_id = '" . (int)$enquiry_order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (int)$product['quantity'] . "', price = '" . (float)$product['price'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', reward = '" . (int)$product['reward'] . "', sku='".$this->db->escape($sku)."'");

				$enquiry_product_id = $this->db->getLastId();

				foreach ($product['option'] as $option) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "enquiry_order_option SET enquiry_order_id = '" . (int)$enquiry_order_id . "', enquiry_order_product_id = '" . (int)$enquiry_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
				}
			}
		}

		// Gift Voucher
		$this->load->model('extension/total/voucher');

		$this->model_extension_total_voucher->disableVoucher($enquiry_order_id);

		// Vouchers
		$this->db->query("DELETE FROM " . DB_PREFIX . "enquiry_order_voucher WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "'");

		if (isset($data['vouchers'])) {
			foreach ($data['vouchers'] as $voucher) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "enquiry_order_voucher SET enquiry_order_id = '" . (int)$enquiry_order_id . "', description = '" . $this->db->escape($voucher['description']) . "', code = '" . $this->db->escape($voucher['code']) . "', from_name = '" . $this->db->escape($voucher['from_name']) . "', from_email = '" . $this->db->escape($voucher['from_email']) . "', to_name = '" . $this->db->escape($voucher['to_name']) . "', to_email = '" . $this->db->escape($voucher['to_email']) . "', voucher_theme_id = '" . (int)$voucher['voucher_theme_id'] . "', message = '" . $this->db->escape($voucher['message']) . "', amount = '" . (float)$voucher['amount'] . "'");

				$enquiry_voucher_id = $this->db->getLastId();

				$voucher_id = $this->model_extension_total_voucher->addVoucher($enquiry_order_id, $voucher);

				$this->db->query("UPDATE " . DB_PREFIX . "enquiry_order_voucher SET voucher_id = '" . (int)$voucher_id . "' WHERE order_voucher_id = '" . (int)$enquiry_voucher_id . "'");
			}
		}

		// Totals
		$this->db->query("DELETE FROM " . DB_PREFIX . "enquiry_order_total WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "'");

		if (isset($data['totals'])) {
			foreach ($data['totals'] as $total) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "enquiry_order_total SET enquiry_order_id = '" . (int)$enquiry_order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
			}
		}
	}

	public function deleteOrder($enquiry_order_id) {
		// Void the order first
		$this->addOrderHistory($enquiry_order_id, 0);

		$this->db->query("DELETE FROM `" . DB_PREFIX . "enquiry_order` WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "enquiry_order_product` WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "enquiry_order_option` WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "enquiry_order_voucher` WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "enquiry_order_total` WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "enquiry_order_history` WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "'");
		$this->db->query("DELETE `or`, ort FROM `" . DB_PREFIX . "enquiry_order_recurring` `or`, `" . DB_PREFIX . "enquiry_order_recurring_transaction` `ort` WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "' AND ort.enquiry_order_recurring_id = `or`.enquiry_order_recurring_id");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "affiliate_transaction` WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "'");

		// Gift Voucher
		$this->load->model('extension/total/voucher');

		$this->model_extension_total_voucher->disableVoucher($enquiry_order_id);
	}

	public function getOrder($enquiry_order_id) {

		$enquiry_query = $this->db->query("SELECT *, (SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE os.order_status_id = o.enquiry_order_status_id AND os.language_id = o.language_id) AS order_status FROM `" . DB_PREFIX . "enquiry_order` o WHERE o.enquiry_order_id = '" . (int)$enquiry_order_id . "'");

		if ($enquiry_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$enquiry_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$enquiry_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$enquiry_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$enquiry_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}

			$this->load->model('localisation/language');

			$language_info = $this->model_localisation_language->getLanguage($enquiry_query->row['language_id']);

			if ($language_info) {
				$language_code = $language_info['code'];
			} else {
				$language_code = $this->config->get('config_language');
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
				'email'                   => $enquiry_query->row['email'],
				'telephone'               => $enquiry_query->row['telephone'],
				'fax'                     => $enquiry_query->row['fax'],
				'custom_field'            => json_decode($enquiry_query->row['custom_field'], true),
				'payment_firstname'       => $enquiry_query->row['payment_firstname'],
				'payment_lastname'        => $enquiry_query->row['payment_lastname'],
				'payment_company'         => $enquiry_query->row['payment_company'],
				'payment_address_1'       => $enquiry_query->row['payment_address_1'],
				'payment_address_2'       => $enquiry_query->row['payment_address_2'],
				'payment_unit_no'         => $enquiry_query->row['payment_unit_no'],
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
				'payment_custom_field'    => json_decode($enquiry_query->row['payment_custom_field'], true),
				'payment_method'          => $enquiry_query->row['payment_method'],
				'payment_code'            => $enquiry_query->row['payment_code'],
				'shipping_firstname'      => $enquiry_query->row['shipping_firstname'],
				'shipping_lastname'       => $enquiry_query->row['shipping_lastname'],
				'shipping_company'        => $enquiry_query->row['shipping_company'],
				'shipping_address_1'      => $enquiry_query->row['shipping_address_1'],
				'shipping_address_2'      => $enquiry_query->row['shipping_address_2'],
				'shipping_unit_no'        => $enquiry_query->row['shipping_unit_no'],
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
				'shipping_custom_field'   => json_decode($enquiry_query->row['shipping_custom_field'], true),
				'shipping_method'         => $enquiry_query->row['shipping_method'],
				'shipping_code'           => $enquiry_query->row['shipping_code'],
				'comment'                 => $enquiry_query->row['comment'],
				'total'                   => $enquiry_query->row['total'],
				'enquiry_order_status_id' => $enquiry_query->row['enquiry_order_status_id'],
				'order_status'    		  => $enquiry_query->row['order_status'],
				'affiliate_id'            => $enquiry_query->row['affiliate_id'],
				'commission'              => $enquiry_query->row['commission'],
				'language_id'             => $enquiry_query->row['language_id'],
				'language_code'           => $language_code,
				'currency_id'             => $enquiry_query->row['currency_id'],
				'currency_code'           => $enquiry_query->row['currency_code'],
				'currency_value'          => $enquiry_query->row['currency_value'],
				'ip'                      => $enquiry_query->row['ip'],
				'forwarded_ip'            => $enquiry_query->row['forwarded_ip'],
				'user_agent'              => $enquiry_query->row['user_agent'],
				'accept_language'         => $enquiry_query->row['accept_language'],
				'date_added'              => $enquiry_query->row['date_added'],
				'date_modified'           => $enquiry_query->row['date_modified'],
				'reward_earn'           	=> $enquiry_query->row['reward_earn'],
			);
		} else {
			return false;
		}
	}

	public function addOrderHistory($enquiry_order_id, $enquiry_order_status_id = 2, $comment = '', $notify = false, $override = false) {
		$enquiry_info = $this->getOrder($enquiry_order_id);
		
		if ($enquiry_info) {
			// Fraud Detection
			$this->load->model('account/customer');

			$customer_info = $this->model_account_customer->getCustomer($enquiry_info['customer_id']);

			if ($customer_info && $customer_info['safe']) {
				$safe = true;
			} else {
				$safe = false;
			}

			// If current order status is not processing or complete but new status is processing or complete then commence completing the order
			if (!in_array($enquiry_info['enquiry_order_status_id'], array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status'))) && in_array($enquiry_order_status_id, array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status')))) {
				// Redeem coupon, vouchers and reward points
				$enquiry_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "enquiry_order_total` WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "' ORDER BY sort_order ASC");

				foreach ($enquiry_total_query->rows as $enquiry_total) {
					$this->load->model('extension/enquiry_total/' . $enquiry_total['code']);

					if (property_exists($this->{'model_extension_enquiry_total_' . $enquiry_total['code']}, 'confirm')) {
						// Confirm coupon, vouchers and reward points
						$fraud_status_id = $this->{'model_extension_enquiry_total_' . $enquiry_total['code']}->confirm($enquiry_info, $enquiry_total);
						
						// If the balance on the coupon, vouchers and reward points is not enough to cover the transaction or has already been used then the fraud order status is returned.
						if ($fraud_status_id) {
							$enquiry_order_status_id = $fraud_status_id;
						}
					}
				}

				// Add commission if sale is linked to affiliate referral.
				if ($enquiry_info['affiliate_id'] && $this->config->get('config_affiliate_auto')) {
					$this->load->model('affiliate/affiliate');

					$this->model_affiliate_affiliate->addTransaction($enquiry_info['affiliate_id'], $enquiry_info['commission'], $enquiry_order_id);
				}

				// Stock subtraction
				$enquiry_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "enquiry_order_product WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "'");

				/*
				foreach ($enquiry_product_query->rows as $enquiry_product) {
					$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$enquiry_product['quantity'] . ") WHERE product_id = '" . (int)$enquiry_product['product_id'] . "' AND subtract = '1'");

					$enquiry_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "enquiry_order_option WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "' AND order_product_id = '" . (int)$enquiry_product['enquiry_order_product_id'] . "'");

					foreach ($enquiry_option_query->rows as $option) {
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$enquiry_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
					}
				}
				*/
			}

			// Update the DB with the new statuses
			$this->db->query("UPDATE `" . DB_PREFIX . "enquiry_order` SET enquiry_order_status_id = '" . (int)$enquiry_order_status_id . "', date_modified = NOW() WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "'");

			$this->db->query("INSERT INTO " . DB_PREFIX . "enquiry_order_history SET enquiry_order_id = '" . (int)$enquiry_order_id . "', enquiry_order_status_id = '" . (int)$enquiry_order_status_id . "', notify = '" . (int)$notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");

			// If old order status is the processing or complete status but new status is not then commence restock, and remove coupon, voucher and reward history
			if (in_array($enquiry_info['enquiry_order_status_id'], array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status'))) && !in_array($enquiry_order_status_id, array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status')))) {
				// Restock
				$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "enquiry_order_product WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "'");

				/*
				foreach($product_query->rows as $product) {
					$this->db->query("UPDATE `" . DB_PREFIX . "product` SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_id = '" . (int)$product['product_id'] . "' AND subtract = '1'");

					$option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "enquiry_order_option WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "' AND order_product_id = '" . (int)$product['enquiry_order_product_id'] . "'");

					foreach ($option_query->rows as $option) {
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
					}
				}
				*/

				// Remove coupon, vouchers and reward points history
				$this->load->model('account/order');

				$enquiry_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "enquiry_order_total` WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "' ORDER BY sort_order ASC");

				foreach ($enquiry_total_query->rows as $enquiry_total) {
					$this->load->model('extension/enquiry_total/' . $enquiry_total['code']);

					if (property_exists($this->{'model_extension_enquiry_total_' . $enquiry_total['code']}, 'unconfirm')) {
						$this->{'model_extension_enquiry_total_' . $enquiry_total['code']}->unconfirm($enquiry_order_id);
					}
				}

				// Remove commission if sale is linked to affiliate referral.
				if ($enquiry_info['affiliate_id']) {
					$this->load->model('affiliate/affiliate');

					$this->model_affiliate_affiliate->deleteTransaction($enquiry_order_id);
				}
			}

			$this->cache->delete('product');
			
			// If order status is 0 then becomes greater than 0 send main html email
			if (!$enquiry_info['enquiry_order_status_id'] && $enquiry_order_status_id) {
				// Check for any downloadable products
				$download_status = false;
	
				$enquiry_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "enquiry_order_product WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "'");
	
				foreach ($enquiry_product_query->rows as $enquiry_product) {
					// Check if there are any linked downloads
					$product_download_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product_to_download` WHERE product_id = '" . (int)$enquiry_product['product_id'] . "'");
	
					if ($product_download_query->row['total']) {
						$download_status = true;
					}
				}
	
				// Load the language for any mails that might be required to be sent out
				$language = new Language($enquiry_info['language_code']);
				$language->load($enquiry_info['language_code']);
				$language->load('mail/enquiry');
	
				$enquiry_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$enquiry_order_status_id . "' AND language_id = '" . (int)$enquiry_info['language_id'] . "'");
	
				if ($enquiry_status_query->num_rows) {
					$enquiry_status = $enquiry_status_query->row['name'];
				} else {
					$enquiry_status = '';
				}
	
				$subject = sprintf($language->get('text_new_subject'), html_entity_decode($enquiry_info['store_name'], ENT_QUOTES, 'UTF-8'), $enquiry_order_id);
	
				// HTML Mail
				$data = array();
	
				$data['title'] = sprintf($language->get('text_new_subject'), $enquiry_info['store_name'], $enquiry_order_id);
	
				$data['text_greeting'] = sprintf($language->get('text_new_greeting'), $enquiry_info['store_name']);
				$data['text_link'] = $language->get('text_new_link');
				$data['text_download'] = $language->get('text_new_download');
				$data['text_order_detail'] = $language->get('text_new_order_detail');
				$data['text_instruction'] = $language->get('text_new_instruction');
				$data['text_enquiry_order_id'] = $language->get('text_new_enquiry_order_id');
				$data['text_date_added'] = $language->get('text_new_date_added');
				$data['text_payment_method'] = $language->get('text_new_payment_method');
				$data['text_shipping_method'] = $language->get('text_new_shipping_method');
				$data['text_email'] = $language->get('text_new_email');
				$data['text_telephone'] = $language->get('text_new_telephone');
				$data['text_ip'] = $language->get('text_new_ip');
				$data['text_order_status'] = $language->get('text_new_order_status');
				$data['text_payment_address'] = $language->get('text_new_payment_address');
				$data['text_shipping_address'] = $language->get('text_new_shipping_address');
				$data['text_product'] = $language->get('text_new_product');
				$data['text_model'] = $language->get('text_new_model');
				$data['text_quantity'] = $language->get('text_new_quantity');
				$data['text_price'] = $language->get('text_new_price');
				$data['text_total'] = $language->get('text_new_total');
				$data['text_footer'] = $language->get('text_new_footer');
	
				$data['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');
				$data['store_name'] = $enquiry_info['store_name'];
				$data['store_url'] = $enquiry_info['store_url'];
				$data['customer_id'] = $enquiry_info['customer_id'];
				$data['link'] = $enquiry_info['store_url'] . 'index.php?route=account/enquiry/info&enquiry_order_id=' . $enquiry_order_id;
	
				if ($download_status) {
					$data['download'] = $enquiry_info['store_url'] . 'index.php?route=account/download';
				} else {
					$data['download'] = '';
				}
	
				$data['enquiry_order_id'] = $enquiry_order_id;
				$data['date_added'] = date($language->get('date_format_short'), strtotime($enquiry_info['date_added']));
				$data['payment_method'] = $enquiry_info['payment_method'];
				$data['shipping_method'] = $enquiry_info['shipping_method'];
				$data['email'] = $enquiry_info['email'];
				$data['telephone'] = $enquiry_info['telephone'];
				$data['ip'] = $enquiry_info['ip'];
				$data['enquiry_order_status'] = $enquiry_status;
	
				if ($comment && $notify) {
					$data['comment'] = nl2br($comment);
				} else {
					$data['comment'] = '';
				}
	
				if ($enquiry_info['payment_address_format']) {
					$format = $enquiry_info['payment_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{unit_no} {address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}
	
				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{unit_no}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);
	
				$replace = array(
					'firstname' => $enquiry_info['payment_firstname'],
					'lastname'  => $enquiry_info['payment_lastname'],
					'company'   => $enquiry_info['payment_company'],
					'address_1' => $enquiry_info['payment_address_1'],
					'address_2' => $enquiry_info['payment_address_2'],
					'unit_no'   => $enquiry_info['payment_unit_no']?$enquiry_info['payment_unit_no'].', ':'',
					'city'      => $enquiry_info['payment_city'],
					'postcode'  => $enquiry_info['payment_postcode'],
					'zone'      => $enquiry_info['payment_zone'],
					'zone_code' => $enquiry_info['payment_zone_code'],
					'country'   => $enquiry_info['payment_country']
				);
	
				$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
	
				if ($enquiry_info['shipping_address_format']) {
					$format = $enquiry_info['shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{unit_no}{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}
	
				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{unit_no}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);
	
				$replace = array(
					'firstname' => $enquiry_info['shipping_firstname'],
					'lastname'  => $enquiry_info['shipping_lastname'],
					'company'   => $enquiry_info['shipping_company'],
					'address_1' => $enquiry_info['shipping_address_1'],
					'address_2' => $enquiry_info['shipping_address_2'],
					'unit_no' 	=> $enquiry_info['shipping_unit_no']?$enquiry_info['shipping_unit_no'] .', ':'',
					'city'      => $enquiry_info['shipping_city'],
					'postcode'  => $enquiry_info['shipping_postcode'],
					'zone'      => $enquiry_info['shipping_zone'],
					'zone_code' => $enquiry_info['shipping_zone_code'],
					'country'   => $enquiry_info['shipping_country']
				);
	
				$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
	
				$this->load->model('tool/upload');
	
				// Products
				$data['products'] = array();
	
				foreach ($enquiry_product_query->rows as $product) {
					$option_data = array();
	
					$enquiry_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "enquiry_order_option WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "' AND enquiry_order_product_id = '" . (int)$product['enquiry_order_product_id'] . "'");
	
					foreach ($enquiry_option_query->rows as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);
	
							if ($upload_info) {
								$value = $upload_info['name'];
							} else {
								$value = '';
							}
						}
	
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
						);
					}
	
					$data['products'][] = array(
						'name'     => $product['name'],
						'model'    => $product['model'],
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $enquiry_info['currency_code'], $enquiry_info['currency_value']),
						'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $enquiry_info['currency_code'], $enquiry_info['currency_value'])
					);
				}
	
				// Vouchers
				$data['vouchers'] = array();
				/*
				$enquiry_voucher_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "enquiry_order_voucher WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "'");
	
				foreach ($enquiry_voucher_query->rows as $voucher) {
					$data['vouchers'][] = array(
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'], $enquiry_info['currency_code'], $enquiry_info['currency_value']),
					);
				}
				*/
	
				// Order Totals
				$data['totals'] = array();
				
				$enquiry_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "enquiry_order_total` WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "' ORDER BY sort_order ASC");
	
				foreach ($enquiry_total_query->rows as $total) {
					$data['totals'][] = array(
						'title' => $total['title'],
						'text'  => $this->currency->format($total['value'], $enquiry_info['currency_code'], $enquiry_info['currency_value']),
					);
				}
	
				// Text Mail
				$text  = sprintf($language->get('text_new_greeting'), html_entity_decode($enquiry_info['store_name'], ENT_QUOTES, 'UTF-8')) . "\n\n";
				$text .= $language->get('text_new_enquiry_order_id') . ' ' . $enquiry_order_id . "\n";
				$text .= $language->get('text_new_date_added') . ' ' . date($language->get('date_format_short'), strtotime($enquiry_info['date_added'])) . "\n";
				$text .= $language->get('text_new_order_status') . ' ' . $enquiry_status . "\n\n";
	
				if ($comment && $notify) {
					$text .= $language->get('text_new_instruction') . "\n\n";
					$text .= $comment . "\n\n";
				}
	
				// Products
				$text .= $language->get('text_new_products') . "\n";
	
				foreach ($enquiry_product_query->rows as $product) {
					$text .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode($this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $enquiry_info['currency_code'], $enquiry_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
	
					$enquiry_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "enquiry_order_option WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "' AND enquiry_order_product_id = '" . $product['enquiry_order_product_id'] . "'");
	
					foreach ($enquiry_option_query->rows as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);
	
							if ($upload_info) {
								$value = $upload_info['name'];
							} else {
								$value = '';
							}
						}
	
						$text .= chr(9) . '-' . $option['name'] . ' ' . (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value) . "\n";
					}
				}
				/*
				foreach ($enquiry_voucher_query->rows as $voucher) {
					$text .= '1x ' . $voucher['description'] . ' ' . $this->currency->format($voucher['amount'], $enquiry_info['currency_code'], $enquiry_info['currency_value']);
				}
				*/
	
				$text .= "\n";
	
				$text .= $language->get('text_new_order_total') . "\n";
	
				foreach ($enquiry_total_query->rows as $total) {
					$text .= $total['title'] . ': ' . html_entity_decode($this->currency->format($total['value'], $enquiry_info['currency_code'], $enquiry_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
				}
	
				$text .= "\n";
	
				if ($enquiry_info['customer_id']) {
					$text .= $language->get('text_new_link') . "\n";
					$text .= $enquiry_info['store_url'] . 'index.php?route=account/enquiry/info&enquiry_order_id=' . $enquiry_order_id . "\n\n";
				}
	
				if ($download_status) {
					$text .= $language->get('text_new_download') . "\n";
					$text .= $enquiry_info['store_url'] . 'index.php?route=account/download' . "\n\n";
				}
	
				// Comment
				if ($enquiry_info['comment']) {
					$text .= $language->get('text_new_comment') . "\n\n";
					$text .= $enquiry_info['comment'] . "\n\n";
				}
	
				$text .= $language->get('text_new_footer') . "\n\n";
	
				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
	
				$mail->setTo($enquiry_info['email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($enquiry_info['store_name'], ENT_QUOTES, 'UTF-8'));
				$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
				$mail->setHtml($this->load->view('mail/order', $data));
				$mail->setText($text);
				
				// Pro email Template Mod
				if($this->config->get('pro_email_template_status')){
					$this->load->model('tool/pro_email');

					if (!empty($data['payment_instruction']) && strpos($enquiry_info['payment_code'],'xpayment.') !== false) {
					$comment = $data['payment_instruction'];
					}
					
					$email_params = array(
					'type' => 'enquiry.confirm',
					'mail' => $mail,
					'enquiry_order_info' => $enquiry_info,
					'enquiry_order_status_id' => $enquiry_order_status_id,
					'enquiry_order_comment' => nl2br($comment),
					);
					
					$this->model_tool_pro_email->generate($email_params);
				}
				else{
					$mail->send();
				}
	
				// Admin Alert Mail
				if (in_array('order', (array)$this->config->get('config_mail_alert'))) {
					$subject = sprintf($language->get('text_new_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'), $enquiry_order_id);
	
					// HTML Mail
					$data['text_greeting'] = $language->get('text_new_received');
	
					if ($comment) {
						if ($enquiry_info['comment']) {
							$data['comment'] = nl2br($comment) . '<br/><br/>' . $enquiry_info['comment'];
						} else {
							$data['comment'] = nl2br($comment);
						}
					} else {
						if ($enquiry_info['comment']) {
							$data['comment'] = $enquiry_info['comment'];
						} else {
							$data['comment'] = '';
						}
					}
	
					$data['text_download'] = '';
	
					$data['text_footer'] = '';
	
					$data['text_link'] = '';
					$data['link'] = '';
					$data['download'] = '';
	
					// Text
					$text  = $language->get('text_new_received') . "\n\n";
					$text .= $language->get('text_new_enquiry_order_id') . ' ' . $enquiry_order_id . "\n";
					$text .= $language->get('text_new_date_added') . ' ' . date($language->get('date_format_short'), strtotime($enquiry_info['date_added'])) . "\n";
					$text .= $language->get('text_new_order_status') . ' ' . $enquiry_status . "\n\n";
					$text .= $language->get('text_new_products') . "\n";
	
					foreach ($enquiry_product_query->rows as $product) {
						$text .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode($this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $enquiry_info['currency_code'], $enquiry_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
	
						$enquiry_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "enquiry_order_option WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "' AND enquiry_order_product_id = '" . $product['enquiry_order_product_id'] . "'");
	
						foreach ($enquiry_option_query->rows as $option) {
							if ($option['type'] != 'file') {
								$value = $option['value'];
							} else {
								$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
							}
	
							$text .= chr(9) . '-' . $option['name'] . ' ' . (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value) . "\n";
						}
					}
					/*
					foreach ($enquiry_voucher_query->rows as $voucher) {
						$text .= '1x ' . $voucher['description'] . ' ' . $this->currency->format($voucher['amount'], $enquiry_info['currency_code'], $enquiry_info['currency_value']);
					}
					*/
	
					$text .= "\n";
	
					$text .= $language->get('text_new_order_total') . "\n";
	
					foreach ($enquiry_total_query->rows as $total) {
						$text .= $total['title'] . ': ' . html_entity_decode($this->currency->format($total['value'], $enquiry_info['currency_code'], $enquiry_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
					}
	
					$text .= "\n";
	
					if ($enquiry_info['comment']) {
						$text .= $language->get('text_new_comment') . "\n\n";
						$text .= $enquiry_info['comment'] . "\n\n";
					}
	
					$mail = new Mail();
					$mail->protocol = $this->config->get('config_mail_protocol');
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
					$mail->smtp_username = $this->config->get('config_mail_smtp_username');
					$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
					$mail->smtp_port = $this->config->get('config_mail_smtp_port');
					$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
	
					$mail->setTo($this->config->get('config_email'));
					$mail->setFrom($this->config->get('config_email'));
					$mail->setSender(html_entity_decode($enquiry_info['store_name'], ENT_QUOTES, 'UTF-8'));
					$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
					$mail->setHtml($this->load->view('mail/order', $data));
					$mail->setText($text);
					// $mail->send();

					// Pro email Template Mod
					if($this->config->get('pro_email_template_status')){
						$this->load->model('tool/pro_email');

						$email_params = array(
						'type' => 'admin.enquiry.confirm',
						'mail' => &$mail,
						'reply_to' => $enquiry_info['email'],
						'enquiry_order_info' => $enquiry_info,
						'enquiry_order_status_id' => $enquiry_order_status_id,
						'enquiry_order_comment' => nl2br($comment),
						);
						
						$this->model_tool_pro_email->generate($email_params);
					}
					else{
						$mail->send();
					}
					// End
	
					// Send to additional alert emails
					$emails = explode(',', $this->config->get('config_alert_email'));
	
					foreach ($emails as $email) {
						if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
							$mail->setTo($email);
							// $mail->send();

							// Pro email Template Mod
							if($this->config->get('pro_email_template_status')){
								$this->load->model('tool/pro_email');

								$email_params = array(
								'type' => 'admin.enquiry.confirm',
								'mail' => &$mail,
								'reply_to' => $enquiry_info['email'],
								'enquiry_order_info' => $enquiry_info,
								'enquiry_order_status_id' => $enquiry_order_status_id,
								'enquiry_order_comment' => nl2br($comment),
								);
								
								$this->model_tool_pro_email->generate($email_params);
							}
							else{
								$mail->send();
							}
							// End Pro email Template Mod
						}
					}
				}
			}
	
			// If order status is not 0 then send update text email
			if ($enquiry_info['enquiry_order_status_id'] && $enquiry_order_status_id && $notify) {
				$language = new Language($enquiry_info['language_code']);
				$language->load($enquiry_info['language_code']);
				$language->load('mail/order');
	
				$subject = sprintf($language->get('text_update_subject'), html_entity_decode($enquiry_info['store_name'], ENT_QUOTES, 'UTF-8'), $enquiry_order_id);
	
				$message  = $language->get('text_update_order') . ' ' . $enquiry_order_id . "\n";
				$message .= $language->get('text_update_date_added') . ' ' . date($language->get('date_format_short'), strtotime($enquiry_info['date_added'])) . "\n\n";
	
				$enquiry_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$enquiry_order_status_id . "' AND language_id = '" . (int)$enquiry_info['language_id'] . "'");
	
				if ($enquiry_status_query->num_rows) {
					$message .= $language->get('text_update_order_status') . "\n\n";
					$message .= $enquiry_status_query->row['name'] . "\n\n";
				}
	
				if ($enquiry_info['customer_id']) {
					$message .= $language->get('text_update_link') . "\n";
					$message .= $enquiry_info['store_url'] . 'index.php?route=account/order/info&enquiry_order_id=' . $enquiry_order_id . "\n\n";
				}
	
				if ($comment) {
					$message .= $language->get('text_update_comment') . "\n\n";
					$message .= strip_tags($comment) . "\n\n";
				}
	
				$message .= $language->get('text_update_footer');
	
				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
	
				$mail->setTo($enquiry_info['email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($enquiry_info['store_name'], ENT_QUOTES, 'UTF-8'));
				$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
				$mail->setText($message);
				// $mail->send();

				// Pro email Template Mod
				if($this->config->get('pro_email_template_status')){
					$this->load->model('tool/pro_email');

					$email_params = array(
						'type' => 'enquiry.update',
						'mail' => $mail,
						'enquiry_order_info' => $enquiry_info,
						'enquiry_order_status_id' => (int)$enquiry_order_status_id,
						'enquiry_order_status_name' => $enquiry_status_query->num_rows ? $enquiry_status_query->row['name'] : '',
						'data' => array(
							'enquiry_order_status' => $enquiry_status_query->num_rows ? $enquiry_status_query->row['name'] : '',
							'message' => nl2br($comment),
						),
						'conditions' => array(
							'message' => $comment ? 1 : 0,
						),
					);
					
					$this->model_tool_pro_email->generate($email_params);
				}
				else{
					$mail->send();
				}
				// End Pro email Template Mod
			}
		}
	}
}
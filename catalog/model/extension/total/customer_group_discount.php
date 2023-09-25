<?php
class ModelExtensionTotalCustomerGroupDiscount extends Model {
	
	/* OC <=  2.2 */
	/* public function getTotal(&$total_data, &$total, &$taxes) { */
	public function getTotal($total) {
		if ($this->config->get('discounts_status') && $this->cart->hasProducts() && $this->config->get('customer_group_discount_status')) {
			$this->load->language('extension/total/customer_group_discount');
			$this->load->model('catalog/discount');
			
			$discount_total = 0;
			
			if ($this->customer->isLogged() == false) {
				$customer_group_id = 0;			
			} else {
				$customer_group_id = $this->customer->getGroupId();
			}
			
			$customer_group_discount = $this->model_catalog_discount->getCustomerGroupDiscount($customer_group_id);

			if ($customer_group_discount) {
			
				foreach ($this->cart->getProducts() as $product) {
					$discount = 0;
					
					if ($this->config->get('discounts_override_special_price') == 'exclusive' && $this->hasSpecialPrice($product['product_id'], $customer_group_id)) {
						$discount = 0;
					} else {
						$discount = $product['total'] / 100 * $customer_group_discount['percentage'];
					}
								
					if ($product['tax_class_id']) {
						$tax_rates = $this->tax->getRates($product['total'] - ($product['total'] - $discount), $product['tax_class_id']);

						foreach ($tax_rates as $tax_rate) {
							if (version_compare(VERSION, '2.2', '>=') && !empty($total['taxes'][$tax_rate['tax_rate_id']])) { 
								$total['taxes'][$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
							} elseif (!empty($taxes[$tax_rate['tax_rate_id']])) {
								$taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
							}
						}
					}
		
					if (empty($discount_data[($customer_group_discount['customer_group_id'])]) && !empty($discount)) {
					
						$decimals = explode('.', $customer_group_discount['percentage']);
						$discount_data[($customer_group_discount['customer_group_id'])] = array(
							'code'       => 'customer_group_discount',
							'title'      => sprintf($this->language->get('text_customer_group_discount'), '-' . (($decimals[1]) == '0000' ? $decimals[0] : number_format($customer_group_discount['percentage'], 2)). '%'),
							'value'      => -$discount,
							'sort_order' => $this->config->get('customer_group_discount_sort_order')
						);	
					} elseif (!empty($discount)) {
						$discount_data[($customer_group_discount['customer_group_id'])]['value'] += -$discount;
					}
		
					$discount_total += $discount;
				}
			} 
			
			if (!empty($discount_data)) {
				foreach ($discount_data as $key) {
					
					if (version_compare(VERSION, '2.2', '>=')) { 
						$total['totals'][] = array(
							'code'       => $key['code'],
							'title'      => $key['title'],
							'value'      => $key['value'],
							'sort_order' => $key['sort_order']
						);
					} else {
						$total_data[] = array(
							'code'       => $key['code'],
							'title'      => $key['title'],
							'value'      => $key['value'],
							'sort_order' => $key['sort_order']
						);
					}
				}
			}
			if (version_compare(VERSION, '2.2', '>=')) { 
				$total['total'] -= $discount_total;
			} else {
				$total -= $discount_total;
			}
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
}
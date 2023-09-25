<?php
class ModelExtensionTotalManufacturerDiscount extends Model {
	/* OC <=  2.2 */
	/* public function getTotal(&$total_data, &$total, &$taxes) { */
	public function getTotal($total) {
		if ($this->config->get('discounts_status') && $this->cart->hasProducts() && $this->config->get('manufacturer_discount_status')) {
			$this->load->language('extension/total/manufacturer_discount');
			$this->load->model('catalog/discount');
			
			$discount_total = 0;
			
			foreach ($this->cart->getProducts() as $product) {
				$discount = 0;

				$manufacturer_discount = $this->model_catalog_discount->getManufacturerDiscount($product['product_id']);

				if ($manufacturer_discount) {
					$discount = $product['total'] / 100 * $manufacturer_discount['percentage'];

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
			
					if (empty($discount_data[strtolower($manufacturer_discount['name'])])) {
						
						$decimals = explode('.', $manufacturer_discount['percentage']);
						$discount_data[strtolower($manufacturer_discount['name'])] = array(
							'code'       => 'manufacturer_discount',
							'title'      => sprintf($this->language->get('text_manufacturer_discount'), '-' . (($decimals[1]) == '0000' ? $decimals[0] : number_format($manufacturer_discount['percentage'], 2)). '%', $manufacturer_discount['name']),
							'value'      => -$discount,
							'sort_order' => $this->config->get('manufacturer_discount_sort_order')
						);	
					} else {
						$discount_data[strtolower($manufacturer_discount['name'])]['value'] += -$discount;
			
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
}
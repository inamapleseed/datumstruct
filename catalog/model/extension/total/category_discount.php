<?php
class ModelExtensionTotalCategoryDiscount extends Model {
	
	/* OC <=  2.2 */
	/* public function getTotal(&$total_data, &$total, &$taxes) { */
	public function getTotal($total) {
			if ($this->config->get('discounts_status') && $this->cart->hasProducts() && $this->config->get('category_discount_status')) {
				$this->load->language('extension/total/category_discount');
				$this->load->model('catalog/discount');
			
				$discount_total = 0;
						
				foreach ($this->cart->getProducts() as $product) {
					$discount = 0;

					$category_discount = $this->model_catalog_discount->getCategoryDiscount($product['product_id']);

					if ($category_discount) {
						$discount = $product['total'] / 100 * $category_discount['percentage'];

						if ($product['tax_class_id']) {
							$tax_rates = $this->tax->getRates($product['total'] - ($product['total'] - $discount), $product['tax_class_id']);

							foreach ($tax_rates as $tax_rate) {
								if ($tax_rate['type'] == 'P') {
									if (version_compare(VERSION, '2.2', '>=') && !empty($total['taxes'][$tax_rate['tax_rate_id']])) { 
										$total['taxes'][$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
									} elseif (!empty($taxes[$tax_rate['tax_rate_id']])) {
										$taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
									}
								}
							}
			
						}
			
						if (empty($discount_data[strtolower($category_discount['name'])])) {
						
							$parts = explode('.', $category_discount['percentage']);
							$discount_data[strtolower($category_discount['name'])] = array(
								'code'       => 'category_discount',
								'title'      => sprintf($this->language->get('text_category_discount'), '-' . (($parts[1]) == '0000' ? $parts[0] : number_format($category_discount['percentage'], 2)). '%', $category_discount['name']),
								'value'      => -$discount,
								'sort_order' => $this->config->get('category_discount_sort_order')
							);	
						} else {
							$discount_data[strtolower($category_discount['name'])]['value'] += -$discount;
			
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
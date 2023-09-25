<?php
class ModelExtensionTotalOrdertotalDiscount extends Model {
	/* OC <=  2.2 */
	/* public function getTotal(&$total_data, &$total, &$taxes) { */
	
	public function getTotal($total) {
		if ($this->config->get('discounts_status') && $this->cart->hasProducts() && $this->config->get('ordertotal_discount_status')) {
			$this->load->language('extension/total/ordertotal_discount');
			$this->load->model('catalog/discount');
			
			$discount_total = 0;
			
			$total_discount = $this->model_catalog_discount->getOrdertotalDiscount($total);

			if ($total_discount && $total_discount['type'] == 'P') {
			
				foreach ($this->cart->getProducts() as $product) {
					$discount = 0;
					
					$discount = $product['total'] / 100 * $total_discount['discount'];
									
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
		
					if (empty($discount_data[($total_discount['ordertotal'])])) {
					
						$decimals = explode('.', $total_discount['discount']);
						$discount_data[($total_discount['ordertotal'])] = array(
							'code'       => 'ordertotal_discount',
							'title'      => sprintf($this->language->get('text_ordertotal_discount'), '-' . (($decimals[1]) == '0000' ? $decimals[0] : number_format($total_discount['discount'], 2)). '%', $this->currency->format($total_discount['ordertotal'], $this->session->data['currency'])),
							'value'      => -$discount,
							'sort_order' => $this->config->get('ordertotal_discount_sort_order')
						);	
					} else {
						$discount_data[($total_discount['ordertotal'])]['value'] += -$discount;
					}
		
					$discount_total += $discount;
				}
			} elseif ($total_discount && $total_discount['type'] == 'F') {
				
				$discount = $total_discount['discount'];
				$discount_partial = $discount/($this->cart->countProducts());
				
				foreach ($this->cart->getProducts() as $product) {
									
					if ($product['tax_class_id']) {
						$tax_rates = $this->tax->getRates($product['total'] - ($product['total'] - $discount_partial), $product['tax_class_id']);

						foreach ($tax_rates as $tax_rate) {
							if (version_compare(VERSION, '2.2', '>=')) { 
								$total['taxes'][$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
							} else {
								$taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
							}
						}
			
		
					}
				}
				
				if (empty($discount_data[($total_discount['ordertotal'])])) {
					
						$decimals = explode('.', $total_discount['discount']);
						$discount_data[($total_discount['ordertotal'])] = array(
							'code'       => 'total_discount_' . ($total_discount['ordertotal']),
							'title'      => sprintf($this->language->get('text_ordertotal_discount'), '-' . $this->currency->format(($decimals[1]) == '0000' ? $decimals[0] : number_format($total_discount['discount'], 2),  $this->session->data['currency']), $this->currency->format($total_discount['ordertotal']),  $this->session->data['currency']),
							'value'      => -$discount,
							'sort_order' => $this->config->get('ordertotal_discount_sort_order')
						);	
					} else {
						$discount_data[($total_discount['ordertotal'])]['value'] += -$discount;
					}
		
					$discount_total += $discount;
				
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
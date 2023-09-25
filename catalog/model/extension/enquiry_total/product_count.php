<?php
class ModelExtensionEnquirytotalProductcount extends Model {
	public function getTotal($total) {
		$this->load->language('extension/enquiry_total/product_count');

		$product_count = $this->enquiry->countProducts();

		//$sub_total = $this->enquiry->getSubTotal();


		$total['totals'][] = array(
			'code'       => 'product_count',
			'title'      => $this->language->get('text_product_count'),
			'value'      => $product_count,
			'sort_order' => $this->config->get('product_count_sort_order')
		);

	}
}

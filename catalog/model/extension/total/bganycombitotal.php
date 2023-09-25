<?php
class ModelExtensiontotalbganycombitotal extends Controller {	
	private $error = array();
	private $modpath = 'total/bganycombitotal'; 
 	private $modname = 'bganycombitotal';
	private $modname_module = 'bganycombi';
	private $modtext = 'Buy Any Get Any Product Combination Pack';
	private $modid = '30594';
	private $modssl = 'SSL';
	private $modemail = 'opencarttools@gmail.com'; 
	private $langid = 0;
	private $storeid = 0;
	private $custgrpid = 0;
	
	public function __construct($registry) {
		parent::__construct($registry);
		
		$this->langid = (int)$this->config->get('config_language_id');
		$this->storeid = (int)$this->config->get('config_store_id');
		$this->custgrpid = (int)$this->config->get('config_customer_group_id');
 		
		if(substr(VERSION,0,3)>='3.0' || substr(VERSION,0,3)=='2.3') { 
 			$this->modpath = 'extension/total/bganycombitotal';
 		} 
		
		if(substr(VERSION,0,3)>='3.0') { 
			$this->modname = 'total_bganycombitotal';
			$this->modname_module = 'module_bganycombi';
		} 
		
		if(substr(VERSION,0,3)>='3.0' || substr(VERSION,0,3)=='2.3' || substr(VERSION,0,3)=='2.2') { 
			$this->modssl = true;
		} 
 	} 
	
	public function getTotal($total) {
		$data['bganycombitotal_status'] = $this->setvalue($this->modname_module.'_status'); 	
		
		if ($this->cart->hasProducts() && $data['bganycombitotal_status']) { 
			
			$sub_total = 0;
			$buy_product = array();
			$get_product = array();	
			
			foreach ($this->cart->getProducts() as $product) {
				$bganycombi_discount_info = $this->checkbganycombidiscount($product['product_id']); 
				
				if($bganycombi_discount_info) { 
					foreach($bganycombi_discount_info as $bganycombi_id => $info) { 
						if($info) {	
							if($info['buyflag']) { 
								$buy_product[$bganycombi_id]['bganycombi_discount_info'] = $info;
								for($x = 1; $x <= $product['quantity']; $x++) {
									$buy_product[$bganycombi_id]['products'][] = $product;
								}
							}
							
							if($info['getflag']) { 
								$get_product[$bganycombi_id]['bganycombi_discount_info'] = $info;
								for($x = 1; $x <= $product['quantity']; $x++) {
									$get_product[$bganycombi_id]['products'][] = $product;
								}
							}
						}
					}
				}
			}
			//echo "<pre>"; 			print_r($buy_product); 				print_r($get_product); 			exit;
			
			if($buy_product && $get_product) {
				
				$sub_total = $this->cart->getSubTotal();
				
				foreach($get_product as $getproduct) {
					$info = $getproduct['bganycombi_discount_info'];
					$bganycombi_id = $info['bganycombi_id'];
					
					if(isset($buy_product[$bganycombi_id]) && count($buy_product[$bganycombi_id]['products']) >= (int)$info['buyqty']) {
						usort($getproduct['products'], array($this, "sortByPrice")); 
						
						$getfreeqty = floor((count($buy_product[$bganycombi_id]['products']) / $info['buyqty']) * $info['getqty']);
						for($i = 0; $i < min($getfreeqty, count($getproduct['products'])); $i++) {
							
							$discount_total = 0;
				
							$discount = 0;
							
							$product = $getproduct['products'][$i];
							//$product['price'] = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));
							$freeqty = 1;
							
							// 1 = free
							// 2 = fixed amount
							// 3 = percentage
							
 							if($info['discount_type'] == 1) {  // free
								$discount = ($product['price'] * $freeqty);
							}
							if($info['discount_type'] == 2) { // fixed amount
								$discount = ($info['discount_value'] * $freeqty);
							}
							if($info['discount_type'] == 3) { // percentage
 								$discount = ($product['price'] / 100 * $info['discount_value']) * $freeqty;
							} 
							
 							if(isset($discount_total_array[$bganycombi_id][$product['product_id']])) {
								$discount += $discount_total_array[$bganycombi_id][$product['product_id']]['discount'];
							}
							$discount_total_array[$bganycombi_id][$product['product_id']] = array(
								'title' => sprintf($info['ordertotaltext'], $product['name']),
								'discount' => $discount,
								'product' => $product,
								'sort_order' => $this->config->get($this->modname.'_sort_order')
							); 							
						}  						 
					}
				}
				
				if(isset($discount_total_array) && $discount_total_array) { 
					foreach($discount_total_array as $discount_totalarray) { 
						foreach($discount_totalarray as $discount_totalarray) { 
							$product = $discount_totalarray['product'];
							$discount = $discount_totalarray['discount'];
							
							if ($product['tax_class_id']) {
								$tax_rates = $this->tax->getRates($product['total'] - ($product['total'] - $discount), $product['tax_class_id']);
								
								if(substr(VERSION,0,3)>='3.0' || substr(VERSION,0,3)=='2.3' || substr(VERSION,0,3)=='2.2') {
									foreach ($tax_rates as $tax_rate) {
										if ($tax_rate['type'] == 'P') {
											$total['taxes'][$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
										}
									}
								} else {
									foreach ($tax_rates as $tax_rate) {
										if ($tax_rate['type'] == 'P') {
											$taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
										}
									}
								} 
							}
							
							if ($discount > $total) {
								$discount = $total;
							}
							
							if ($discount > 0) {
								if(substr(VERSION,0,3)>='3.0' || substr(VERSION,0,3)=='2.3' || substr(VERSION,0,3)=='2.2') {
									$total['totals'][] = array(
										'code'       => 'bganycombitotal',
										'title'      => $discount_totalarray['title'],
										'value'      => -$discount,
										'sort_order' => $discount_totalarray['sort_order']
									);
				
									$total['total'] -= $discount;
								} else {
									$total_data[] = array(
										'code'       => 'bganycombitotal',
										'title'      => $discount_totalarray['title'],
										'value'      => -$discount,
										'sort_order' => $discount_totalarray['sort_order']
									);
				
									$total -= $discount;
								}
							} 
						}
					}
				}
				//echo "<pre>"; print_r($discount_total_array); exit;
			} 	 
		}
	}
	
	public function checkbganycombidiscount($product_id) {
		if($this->config->get((substr(VERSION,0,3)>='3.0' ? 'module_bganycombi_status' : 'bganycombi_status'))) { 
			$bganycombi_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bganycombi WHERE status = 1 and date_start <= curdate() and date_end >= curdate() and find_in_set(".$this->storeid.", store) and find_in_set(".$this->custgrpid.", customer_group)");
			if($bganycombi_query->num_rows) {
				$result = array();
				foreach($bganycombi_query->rows as $rs) {
					$checkproduct = $this->validateproduct($rs, $product_id);
					if($checkproduct) {
						$ribbontext = json_decode($rs['ribbontext'], true);
						$ordertotaltext = json_decode($rs['ordertotaltext'], true);
						$offer_heading_text = json_decode($rs['offer_heading_text'], true);
						$offer_content = json_decode($rs['offer_content'], true);

						$result[$rs['bganycombi_id']] = $rs;
						$result[$rs['bganycombi_id']]['ribbontext'] = $ribbontext[$this->langid];
						$result[$rs['bganycombi_id']]['ordertotaltext'] = $ordertotaltext[$this->langid];
						$result[$rs['bganycombi_id']]['offer_heading_text'] = $offer_heading_text[$this->langid];
						$result[$rs['bganycombi_id']]['offer_content'] = $offer_content[$this->langid]; 
						
						$result[$rs['bganycombi_id']]['buyflag'] = isset($checkproduct['buyflag']) ? $checkproduct['buyflag'] : false;
						$result[$rs['bganycombi_id']]['getflag'] = isset($checkproduct['getflag']) ? $checkproduct['getflag'] : false;
 					}
				}
				return $result;
			} else {
				return false;
			} 
		}
	}
	
	public function validateproduct($data, $product_id) { 		
		// check product , category , manufacturer
		
		//=================== Buy ==================//
		$buyflag = false;
		
		if( (isset($data['buyproduct']) && $data['buyproduct']) || (isset($data['buycategory']) && $data['buycategory']) || (isset($data['buymanufacturer']) && $data['buymanufacturer'])) {	
		 
			// product
			$product_array = ($data['buyproduct']) ? explode(",",$data['buyproduct']) : array();
						
			if($data['buyproduct'] && in_array($product_id , $product_array)) { $buyflag = true; } 
				
			// category
			if($data['buycategory'] && $buyflag == false) {
				
				$category_array = ($data['buycategory']) ? explode(",",$data['buycategory']) : array();
				
				foreach($category_array as $category_id) {
					$category_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category where 1 and product_id = '".(int)$product_id."' and category_id = '".(int)$category_id."' ");
					if($category_query->num_rows) { $buyflag = true; }
				}
			}
				
			// manufacturer
			if($data['buymanufacturer'] && $buyflag == false) {
			
				$manufacturer_array = ($data['buymanufacturer']) ? explode(",",$data['buymanufacturer']) : array();
				
				foreach($manufacturer_array as $manufacturer_id) {
					$manufacturer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product where 1 and product_id = '".(int)$product_id."' and  manufacturer_id = '".(int)$manufacturer_id."'  ");
					if($manufacturer_query->num_rows) { $buyflag = true; }
				} 
			} 
		} else { 
			$buyflag = true; 
		}
		
		
		//=================== Get ==================//
		$getflag = false;
		
		if($data['getproduct'] || $data['getcategory'] || $data['getmanufacturer']) {	 
			// product
			$product_array = ($data['getproduct']) ? explode(",",$data['getproduct']) : array();
						
			if($data['getproduct'] && in_array($product_id , $product_array)) { $getflag = true; } 
				
			// category
			if($data['getcategory'] && $getflag == false) {
				
				$category_array = ($data['getcategory']) ? explode(",",$data['getcategory']) : array();
				
				foreach($category_array as $category_id) {
					$category_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category where 1 and product_id = '".(int)$product_id."' and category_id = '".(int)$category_id."' ");
					if($category_query->num_rows) { $getflag = true; }
				}
			}
				
			// manufacturer
			if($data['getmanufacturer'] && $getflag == false) {
			
				$manufacturer_array = ($data['getmanufacturer']) ? explode(",",$data['getmanufacturer']) : array();
				
				foreach($manufacturer_array as $manufacturer_id) {
					$manufacturer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product where 1 and product_id = '".(int)$product_id."' and  manufacturer_id = '".(int)$manufacturer_id."'  ");
					if($manufacturer_query->num_rows) { $getflag = true; }
				} 
			} 
		} else { 
			$getflag = true; 
		} 
			
		if($buyflag || $getflag) {
			return array('flag' => true, 'buyflag' => $buyflag, 'getflag' => $getflag);
		} 		
	} 
 	
	private function sortByPrice($a, $b) {
		return $a['price'] - $b['price'];
	}
	
	protected function setvalue($postfield) {
		return $this->config->get($postfield);
	}
}
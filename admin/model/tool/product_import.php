<?php
class ModelToolProductimport extends Model {
	public function addoldproduct($data,$language_id,$store_id,$product_id){
		$this->db->query("INSERT INTO " . DB_PREFIX . "product SET product_id = '".(int)$product_id."', model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . (int)$data['tax_class_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW(), points = '" . (int)$data['points'] . "', viewed = '" . (int)$data['viewed'] . "'");
		
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
		}
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "',tag = '" . $this->db->escape($data['tag']) . "', meta_title = '" . $this->db->escape($data['meta_titile']) . "', meta_description = '" . $this->db->escape($data['meta_description']) . "', meta_keyword = '" . $this->db->escape($data['meta_keyword']) . "'");
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
		
		if (isset($data['attributes'])) {
			foreach ($data['attributes'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute['text']) . "'");
				}
			}
		}

		
		 foreach($data['options'] as $product_option){
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					if (isset($product_option['optionvalue'])){
						$query = $this->db->query("SELECT product_option_id FROM ".DB_PREFIX."product_option WHERE product_id = '" . (int)$product_id . "' AND option_id = '" . (int)$product_option['option_id'] . "'");
						if(!empty($query->row['product_option_id'])){
							$product_option_id = $query->row['product_option_id'];
						}else{
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");
							$product_option_id = $this->db->getLastId();
						}
						$optionvalue = $product_option['optionvalue'];
						if($optionvalue){
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$optionvalue['option_value_id'] . "', quantity = '" . (int)$optionvalue['qty'] . "', subtract = '" . (int)$optionvalue['subtract'] . "', price = '" . (float)$optionvalue['price'] . "', price_prefix = '+', weight = '" . (float)$optionvalue['weight'] . "',points = '" . (int)$optionvalue['points'] . "'");
						}
						
					}
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int)$product_option['required'] . "'");
				}
		   }
		   
		   if(isset($data['discounts'])){
				foreach ($data['discounts'] as $product_discount) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['startdate']) . "', date_end = '" . $this->db->escape($product_discount['enddate']) . "'");
				}
			}
			
			if(isset($data['specails'])){
				foreach ($data['specails'] as $product_special){
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['startdate']) . "', date_end = '" . $this->db->escape($product_special['enddate']) . "'");
				}
			}
			
			
			if (isset($data['images'])) {
				foreach ($data['images'] as $k => $img) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape(trim($img)) . "', sort_order = '" . (int)$k . "'");
				}
			}
			
			if (isset($data['downloads'])) {
				foreach ($data['downloads'] as $download_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
				}
			}
			
			if (isset($data['categories'])) {
				foreach($data['categories'] as $category_id){
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
				}
			}
			
			if (isset($data['filters'])) {
				foreach ($data['filters'] as $filter_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
				}
			}
			
			if(isset($data['relaled_products'])){
				foreach ($data['relaled_products'] as $related_id){
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
				}
			}
			
			if (isset($data['keyword'])) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
			}
	}
	
	public function addproduct($data,$language_id,$store_id){
		$this->db->query("INSERT INTO " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . (int)$data['tax_class_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', points = '" . (int)$data['points'] . "', viewed = '" . (int)$data['viewed'] . "', date_added = NOW()");
		
		$product_id = $this->db->getLastId();
		
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
		}
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "',tag = '" . $this->db->escape($data['tag']) . "', meta_title = '" . $this->db->escape($data['meta_titile']) . "', meta_description = '" . $this->db->escape($data['meta_description']) . "', meta_keyword = '" . $this->db->escape($data['meta_keyword']) . "'");
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
		
		if (isset($data['attributes'])) {
			foreach ($data['attributes'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute['text']) . "'");
				}
			}
		}

		
		 foreach($data['options'] as $product_option){
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					if (isset($product_option['optionvalue'])){
						$query = $this->db->query("SELECT product_option_id FROM ".DB_PREFIX."product_option WHERE product_id = '" . (int)$product_id . "' AND option_id = '" . (int)$product_option['option_id'] . "'");
						if(!empty($query->row['product_option_id'])){
							$product_option_id = $query->row['product_option_id'];
						}else{
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");
							$product_option_id = $this->db->getLastId();
						}
						$optionvalue = $product_option['optionvalue'];
						if($optionvalue){
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$optionvalue['option_value_id'] . "', quantity = '" . (int)$optionvalue['qty'] . "', subtract = '" . (int)$optionvalue['subtract'] . "', price = '" . (float)$optionvalue['price'] . "', price_prefix = '+', weight = '" . (float)$optionvalue['weight'] . "',points = '" . (int)$optionvalue['points'] . "'");
						}
						
					}
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int)$product_option['required'] . "'");
				}
		   }
		   
		   if(isset($data['discounts'])){
				foreach ($data['discounts'] as $product_discount) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['startdate']) . "', date_end = '" . $this->db->escape($product_discount['enddate']) . "'");
				}
			}
			
			if(isset($data['specails'])){
				foreach ($data['specails'] as $product_special){
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['startdate']) . "', date_end = '" . $this->db->escape($product_special['enddate']) . "'");
				}
			}
			
			
			if (isset($data['images'])) {
				foreach ($data['images'] as $k => $img) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape(trim($img)) . "', sort_order = '" . (int)$k . "'");
				}
			}
			
			if (isset($data['downloads'])) {
				foreach ($data['downloads'] as $download_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
				}
			}
			
			if (isset($data['categories'])) {
				foreach($data['categories'] as $category_id){
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
				}
			}
			
			if (isset($data['filters'])) {
				foreach ($data['filters'] as $filter_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
				}
			}
			
			if(isset($data['relaled_products'])){
				foreach ($data['relaled_products'] as $related_id){
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
				}
			}
			
			if (isset($data['keyword'])) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
			}
	}
	
	public function Editproduct($data,$product_id,$language_id,$store_id){
		$this->db->query("UPDATE " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . (int)$data['tax_class_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW(),points = '" . (int)$data['points'] . "', viewed = '" . (int)$data['viewed'] . "' WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
		}
		
		//Product Description
		$ifpdesc = $this->db->query("SELECT * FROM ".DB_PREFIX."product_description WHERE product_id = '".(int)$product_id."' AND language_id ='".(int)$language_id."'");
		if($ifpdesc->row){
			$this->db->query("UPDATE " . DB_PREFIX . "product_description SET name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "', tag = '" . $this->db->escape($data['tag']) . "', meta_title = '" . $this->db->escape($data['meta_titile']) . "', meta_description = '" . $this->db->escape($data['meta_description']) . "', meta_keyword = '" . $this->db->escape($data['meta_keyword']) . "' WHERE product_id = '" . (int)$product_id . "' AND language_id ='".(int)$language_id."'");
		}else{
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "', tag = '" . $this->db->escape($data['tag']) . "', meta_title = '" . $this->db->escape($data['meta_titile']) . "', meta_description = '" . $this->db->escape($data['meta_description']) . "', meta_keyword = '" . $this->db->escape($data['meta_keyword']) . "'");
		}
		
		//Store
		$ifpstore = $this->db->query("SELECT * FROM ".DB_PREFIX."product_to_store WHERE product_id = '" . (int)$product_id . "' AND store_id = '" . (int)$store_id . "'");
		if(!$ifpstore->row){
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
		}
		
		//Attribute
		if (isset($data['attributes'])){
			foreach ($data['attributes'] as $product_attribute){
				if ($product_attribute['attribute_id']){
					$ifattribute = $this->db->query("SELECT * FROM ".DB_PREFIX."product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "' AND language_id = '" . (int)$language_id . "'");
					if($ifattribute->row){
					  $this->db->query("UPDATE " . DB_PREFIX . "product_attribute SET text = '" .  $this->db->escape($product_attribute['text']) . "' WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "' AND language_id = '" . (int)$language_id . "'");
					}else{
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute['text']) . "'");
					}
				}
			}
		}
		
		 foreach($data['options'] as $product_option){
			if($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image'){
				if (isset($product_option['optionvalue'])){
					$query = $this->db->query("SELECT product_option_id FROM ".DB_PREFIX."product_option WHERE product_id = '" . (int)$product_id . "' AND option_id = '" . (int)$product_option['option_id'] . "'");
					if(!empty($query->row['product_option_id'])){
						$product_option_id = $query->row['product_option_id'];
					}else{
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");
						$product_option_id = $this->db->getLastId();
					}
					$optionvalue = $product_option['optionvalue'];
					if($optionvalue){
				 	$ifoptionvalue = $this->db->query("SELECT * FROM ".DB_PREFIX."product_option_value WHERE  product_option_id = '" . (int)$product_option_id . "' AND option_value_id = '" . (int)$optionvalue['option_value_id'] ."'");
						if($ifoptionvalue->row){
							$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = '" . (int)$optionvalue['qty'] . "', subtract = '" . (int)$optionvalue['subtract'] . "', price = '" . (float)$optionvalue['price'] . "', price_prefix = '+', weight = '" . (float)$optionvalue['weight'] . "', points = '" . (int)$optionvalue['points'] . "' WHERE  product_option_value_id = '".(int)$ifoptionvalue->row['product_option_value_id']."'");
						}else{
						  $this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$optionvalue['option_value_id'] . "', quantity = '" . (int)$optionvalue['qty'] . "', subtract = '" . (int)$optionvalue['subtract'] . "', price = '" . (float)$optionvalue['price'] . "', price_prefix = '+', weight = '" . (float)$optionvalue['weight'] . "',points = '" . (int)$optionvalue['points'] . "'");
						}
					}
				}
			} else {
				$query = $this->db->query("SELECT product_option_id FROM ".DB_PREFIX."product_option WHERE product_id = '" . (int)$product_id . "' AND option_id = '" . (int)$product_option['option_id'] . "'");
				if(!empty($query->row['product_option_id'])){
					$this->db->query("UPDATE " . DB_PREFIX . "product_option SET value = '" . $this->db->escape($product_option['value']) . "' WHERE product_option_id = '".(int)$query->row['product_option_id']."'");
				}else{
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int)$product_option['required'] . "'");
				}
			}
		 }
			
			
			if(!empty($data['discounts'])){
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");
				foreach ($data['discounts'] as $product_discount) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['startdate']) . "', date_end = '" . $this->db->escape($product_discount['enddate']) . "'");
				}
			}
			
			if(!empty($data['specails'])){
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
				foreach ($data['specails'] as $product_special){
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['startdate']) . "', date_end = '" . $this->db->escape($product_special['enddate']) . "'");
				}
			}
			
			
			if (!empty($data['images'])) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
				foreach ($data['images'] as $k => $img) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape(trim($img)) . "', sort_order = '" . (int)$k . "'");
				}
			}
			
			if (!empty($data['downloads'])) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
				foreach ($data['downloads'] as $download_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
				}
			}
			
			if (!empty($data['categories'])) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
				foreach($data['categories'] as $category_id){
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
				}
			}
			
			if (!empty($data['filters'])) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
				foreach ($data['filters'] as $filter_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
				}
			}
			
			if(!empty($data['relaled_products'])){
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
			    $this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");
				foreach ($data['relaled_products'] as $related_id){
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
				}
			}
			
			if (!empty($data['keyword'])) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
			}
	}
		
	public function CheckTaxClass($title){
		$query = $this->db->query("SELECT tax_class_id FROM ".DB_PREFIX."tax_class WHERE LCASE(title) = '".utf8_strtolower($this->db->escape($title))."'");
		if($query->row){
			return $query->row['tax_class_id'];
		}else{
			return false;
		}
	}
	
	public function CheckStockStatus($title){
		$query = $this->db->query("SELECT stock_status_id FROM ".DB_PREFIX."stock_status WHERE LCASE(name) = '".utf8_strtolower($this->db->escape($title))."'");
		if($query->row){
			return $query->row['stock_status_id'];
		}else{
			$languages = $this->getLanguages(array());
			foreach($languages as $language){
				if(isset($stock_status_id)){
					$this->db->query("INSERT INTO " . DB_PREFIX . "stock_status SET stock_status_id = '" . (int)$stock_status_id . "', language_id = '" . (int)$language['language_id'] . "', name = '" . $this->db->escape($title) . "'");
				}else{
					$this->db->query("INSERT INTO " . DB_PREFIX . "stock_status SET language_id = '" . (int)$language['language_id'] . "', name = '" . $this->db->escape($title) . "'");
					$stock_status_id = $this->db->getLastId();
				}
			}
			return $stock_status_id;
		}
	}
	
	//Manufacture
	public function CheckManufacturer($title,$store_id){
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."manufacturer WHERE LCASE(name) = '".$this->db->escape(utf8_strtolower($title))."'");
		if($query->row){
			$qmanufacturer = $this->db->query("SELECT * FROM ".DB_PREFIX."manufacturer_to_store WHERE manufacturer_id = '".(int)$query->row['manufacturer_id']."' AND store_id = '".$store_id."'")->row;
			if($qmanufacturer){
				return $query->row['manufacturer_id'];
			}else{
				$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '".(int)$store_id."'");
			}
		}else{
			$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer SET name = '" . $this->db->escape($title) . "'");
			$manufacturer_id = $this->db->getLastId();
			$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '".(int)$store_id."'");
		}
	}
	
	//Categories
	public function CheckCategories($category,$store_id){
		$categoriesdata=array();
		$categories = explode(',',$category);
		foreach($categories as $category){
			$parent_id=0;
			$multiplecategories = explode('>',$category);
			foreach($multiplecategories as $ct){
				$parent_id = $this->getcategorybyname(trim($ct),$parent_id,$store_id);
			}
			$categoriesdata[] = $parent_id;
		}
		return $categoriesdata;
	}
	
	//Categories Start
	public function getcategorybyname($title,$parent_id,$store_id){
		$cquery = $this->db->query("SELECT c.category_id FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE LCASE(cd.name) =  '".$this->db->escape(utf8_strtolower($title))."' AND c.parent_id = '".(int)$parent_id."'");
		if($cquery->row){
			return $cquery->row['category_id'];
		}else{
			$this->db->query("INSERT INTO " . DB_PREFIX . "category SET parent_id = '" . (int)$parent_id . "', status = 1, date_added = NOW()");
			
			$category_id = $this->db->getLastId();
			
			foreach($this->getLanguages() as $language){
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language['language_id'] . "', name = '" . $this->db->escape($title) . "', meta_title = '" . $this->db->escape($title) . "'");
			}
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
			$this->addCategorypath($category_id,$parent_id);
			
			return $category_id;
		}
	}
	
	public function addCategorypath($category_id,$parent_id){
		// MySQL Hierarchical Data Closure Table Pattern
		$level = 0;

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$parent_id . "' ORDER BY `level` ASC");

		foreach($query->rows as $result){
			$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");

			$level++;
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', `level` = '" . (int)$level . "'");
		
	}
	//Categories END
	
	//Filter Start
	public function checkFilter($filters){
		 $filterids=array();
		 $results = explode(',',$filters);
		 foreach($results as $result){
				$filterdata=array();
				$filterdata = explode('::',$result);
				$filtergroup = (isset($filterdata[0]) ? $filterdata[0] :'');
				$filtervalue = (isset($filterdata[1]) ? $filterdata[1] :'');
				if($filtervalue && $filtergroup){
						 $rfiltergroup = $result = $this->db->query("SELECT fg.filter_group_id FROM `" . DB_PREFIX . "filter_group` fg LEFT JOIN " . DB_PREFIX . "filter_group_description fgd ON (fg.filter_group_id = fgd.filter_group_id) WHERE LCASE(fgd.name) = '".$this->db->escape(trim(utf8_strtolower($filtergroup)))."' LIMIT 0,1");
						 if(isset($rfiltergroup->row['filter_group_id'])){
								 $fquery = $this->db->query("SELECT f.filter_id FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_description fd ON (f.filter_id = fd.filter_id) WHERE f.filter_group_id = '".(int)$rfiltergroup->row['filter_group_id']."' AND LCASE(fd.name) = '".$this->db->escape(trim(utf8_strtolower($filtervalue)))."' LIMIT 0,1");
								 if(empty($fquery->row['filter_id'])){
										 $filterids[] = $this->addonlyfilter($rfiltergroup->row['filter_group_id'],trim($filtervalue));
								 }else{
										 $filterids[] = $fquery->row['filter_id'];
								 }
						 }else{
								$filterids[] = $this->addFilter(trim($filtergroup),trim($filtervalue));
						}
				}
			}
	return $filterids;
  }
	
	public function addFilter($filtergroup,$filtervalue){
		
		$this->db->query("INSERT INTO `" . DB_PREFIX . "filter_group` SET sort_order = 0");

		$filter_group_id = $this->db->getLastId();

		foreach($this->getLanguages() as $language){
			$this->db->query("INSERT INTO " . DB_PREFIX . "filter_group_description SET filter_group_id = '" . (int)$filter_group_id . "', language_id = '" . (int)$language['language_id'] . "', name = '" . $this->db->escape($filtergroup) . "'");
		}
		
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "filter SET filter_group_id = '" . (int)$filter_group_id . "'");

		
		$filter_id = $this->db->getLastId();
		
		foreach($this->getLanguages() as $language){
			$this->db->query("INSERT INTO " . DB_PREFIX . "filter_description SET filter_id = '" . (int)$filter_id . "', language_id = '" . (int)$language['language_id'] . "', filter_group_id = '" . (int)$filter_group_id . "', name = '" . $this->db->escape($filtervalue) . "'");
		}
		
		return $filter_id;
	}
	
	public function addonlyfilter($filter_group_id,$filter){
		$this->db->query("INSERT INTO " . DB_PREFIX . "filter SET filter_group_id = '" . (int)$filter_group_id . "'");
		
		$filter_id = $this->db->getLastId();
		
		foreach($this->getLanguages() as $language){
			$this->db->query("INSERT INTO " . DB_PREFIX . "filter_description SET filter_id = '" . (int)$filter_id . "', language_id = '" . (int)$language['language_id'] . "', filter_group_id = '" . (int)$filter_group_id . "', name = '" . $this->db->escape($filter) . "'");
		}
		return $filter_id;
	}
	//Filter END
	
	//Attribute Start
	public function checkAttribute($attributedatas){
		$attributes=array();
		$results = explode(',',$attributedatas);
		foreach($results as $result){
			$attr = explode('::',$result);
			$attrgroup = (isset($attr[0]) ? trim($attr[0]) : '');
			$attribute = (isset($attr[1]) ? trim($attr[1]) : '');
			$text 	   = (isset($attr[2]) ? trim($attr[2]) : '');
			if($attrgroup && $attribute && $text){
				$rattrgroup = $this->db->query("SELECT ag.attribute_group_id FROM " . DB_PREFIX . "attribute_group ag LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE LCASE(agd.name) = '".$this->db->escape(utf8_strtolower($attrgroup))."' LIMIT 0,1");
				if(!empty($rattrgroup->row['attribute_group_id'])){
					$aresult = $this->db->query("SELECT a.attribute_id FROM " . DB_PREFIX . "attribute a LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE a.attribute_group_id = '".(int)$rattrgroup->row['attribute_group_id']."' AND LCASE(ad.name) = '".$this->db->escape(utf8_strtolower($attribute))."' LIMIT 0,1");
					if(!empty($aresult->row['attribute_id'])){
						$attributes[]=array(
						  'attribute_id' => $aresult->row['attribute_id'],
						  'text'		 => $text,
						);
					}else{
						$attributes[]=array(
						  'attribute_id' => $this->addonlyAttribute($attribute,$rattrgroup->row['attribute_group_id']),
						  'text'		 => $text,
						);
					}
				}else{
					$attribute_group_id = $this->addAttributegroup($attrgroup);
					$attributes[]=array(
						  'attribute_id' => $this->addonlyAttribute($attribute,$attribute_group_id),
						  'text'		 => $text,
					);
				}
			}
		}
		return $attributes;
	}
	
	public function addAttributegroup($attrgroup){
		$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_group SET sort_order = 0");

		$attribute_group_id = $this->db->getLastId();

		foreach($this->getLanguages() as $language){
			$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_group_description SET attribute_group_id = '" . (int)$attribute_group_id . "', language_id = '" . (int)$language['language_id'] . "', name = '" . $this->db->escape($attrgroup) . "'");
		}
		return $attribute_group_id;
	}
	
	public function addonlyAttribute($attribute,$attribute_group_id){
		$this->db->query("INSERT INTO " . DB_PREFIX . "attribute SET attribute_group_id = '" . (int)$attribute_group_id . "'");

		$attribute_id = $this->db->getLastId();

		foreach($this->getLanguages() as $language){
			$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_description SET attribute_id = '" . (int)$attribute_id . "', language_id = '" . (int)$language['language_id'] . "', name = '" . $this->db->escape($attribute) . "'");
		}
		
		return $attribute_id;
	}
	//Attribute END
	
	//Options Start
	public function checkoptions($optionsdata){
		$options=array();
		$results = explode(';',$optionsdata);
		foreach($results as $result){
			$opt = explode('::',$result);
			$option 	 = (isset($opt[0]) ? trim($opt[0]) : '');
			$type 		 = (isset($opt[1]) ? trim($opt[1]) : '');
			$optionvalue = (isset($opt[2]) ? trim($opt[2]) : '');
			if($option && $type){
				$ovaluedata=array();
				if($optionvalue){
				  $ovaluedata = explode('~',$optionvalue);	
				}
				$optionv = '';
				if(isset($ovaluedata[0])){
					$optionv = trim($ovaluedata[0]);
				}
				if(($type == 'select' || $type == 'radio' || $type == 'checkbox' || $type == 'image')){
					$oquery = $this->db->query("SELECT o.option_id FROM `" . DB_PREFIX . "option` o LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE o.type = '".$this->db->escape(utf8_strtolower($type))."' AND LCASE(od.name) = '".$this->db->escape(utf8_strtolower($option))."' LIMIT 0,1");
					if(!empty($oquery->row['option_id'])){
						$ovquery = $this->db->query("SELECT ov.option_value_id FROM " . DB_PREFIX . "option_value ov LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE ov.option_id = '".(int)$oquery->row['option_id']."' AND LCASE(ovd.name) = '".$this->db->escape(utf8_strtolower($optionv))."' LIMIT 0,1");
						if(!empty($ovquery->row['option_value_id'])){
							   $optionvalues=array(
								   'option_value_id' => $ovquery->row['option_value_id'],
								   'qty'			 => (isset($ovaluedata[1]) ? $ovaluedata[1] : 0),
								   'subtract'		 => (isset($ovaluedata[2]) ? $ovaluedata[2] : 0),
								   'price'			 => (isset($ovaluedata[3]) ? $ovaluedata[3] : 0),
								   'points'			 => (isset($ovaluedata[4]) ? $ovaluedata[4] : 0),
								   'weight'			 => (isset($ovaluedata[5]) ? $ovaluedata[5] : 0),
								);
								$options[]=array(
								  'option_id' 	=> $oquery->row['option_id'],
								  'value'		=> '',
								  'required'	=> 1,
								  'optionvalue' => $optionvalues,
								  'type' => $type,
								);
						}else{
							if($optionv){
								$option_value_id = $this->addoptionvalue($oquery->row['option_id'],$optionv);
								   $optionvalues=array(
									   'option_value_id' => $option_value_id,
									   'qty'			 => (isset($ovaluedata[1]) ? $ovaluedata[1] : 0),
									   'subtract'		 => (isset($ovaluedata[2]) ? $ovaluedata[2] : 0),
									   'price'			 => (isset($ovaluedata[3]) ? $ovaluedata[3] : 0),
									   'points'			 => (isset($ovaluedata[4]) ? $ovaluedata[4] : 0),
									   'weight'			 => (isset($ovaluedata[5]) ? $ovaluedata[5] : 0),
									);
									$options[]=array(
									  'option_id' 	=> $oquery->row['option_id'],
									  'value'		=> '',
									  'required'	=> 1,
									  'optionvalue' => $optionvalues,
									  'type' => $type,
									);
							}else{
								$options[]=array(
								  'option_id' 	=> $oquery->row['option_id'],
								  'value'		=> '',
								  'required'	=> 1,
								  'type' => $type,
								  'optionvalue' => array(),
								);
							}
						}
					}else{
						$option_id = $this->addoptions($option,$type);
						if($optionv){
						$option_value_id = $this->addoptionvalue($option_id,$optionv);
							$optionvalues=array(
							   'option_value_id' => $option_value_id,
							   'qty'			 => (isset($ovaluedata[1]) ? $ovaluedata[1] : 0),
							   'subtract'		 => (isset($ovaluedata[2]) ? $ovaluedata[2] : 0),
							   'price'			 => (isset($ovaluedata[3]) ? $ovaluedata[3] : 0),
							   'points'			 => (isset($ovaluedata[4]) ? $ovaluedata[4] : 0),
							   'weight'			 => (isset($ovaluedata[5]) ? $ovaluedata[5] : 0),
							);
							$options[]=array(
							  'option_id' 	=> $option_id,
							  'value'		=> '',
							  'required'	=> 1,
							  'optionvalue' => $optionvalues,
							  'type' => $type,
							);
						}else{
							$options[]=array(
							  'option_id' 	=> $option_id,
							  'value'		=> '',
							  'required'	=> 1,
							  'optionvalue' => array(),
							  'type' => $type,
							);
						}
					}
				}else{
					if(($type == 'text' || $type == 'textarea' || $type == 'file' || $type == 'date' || $type == 'time' || $type == 'datetime')){
						$oquery = $this->db->query("SELECT o.option_id FROM `" . DB_PREFIX . "option` o LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE o.type = '".$this->db->escape(utf8_strtolower($type))."' AND LCASE(od.name) = '".$this->db->escape(utf8_strtolower($option))."' LIMIT 0,1");
						if(!empty($oquery->row['option_id'])){
							$options[]=array(
							  'option_id' 	=> $oquery->row['option_id'],
							  'value'		=> $optionv,
							  'required'	=> 1,
							  'optionvalue' => array(),
							  'type' => $type,
							);
						}else{
							$option_id = $this->addoptions($option,$type);
							$options[]=array(
							  'option_id' 	=> $option_id,
							  'value'		=> $optionv,
						      'required'	=> 1,
						      'optionvalue' => array(),
						      'type' => $type,
							);
						}
					}
				}
			}
		}
		return $options;
	}
	
	public function addoptions($option,$type){
		
		$this->db->query("INSERT INTO `" . DB_PREFIX . "option` SET type = '" . $this->db->escape($type) . "'");
		
		$option_id = $this->db->getLastId();
		
		foreach($this->getLanguages() as $language){
			$this->db->query("INSERT INTO " . DB_PREFIX . "option_description SET option_id = '" . (int)$option_id . "', language_id = '" . (int)$language['language_id'] . "', name = '" . $this->db->escape($option) . "'");
		}
		
		return $option_id;
	}
	
	public function addoptionvalue($option_id,$optionv){
		$this->db->query("INSERT INTO " . DB_PREFIX . "option_value SET option_id = '" . (int)$option_id . "'");

		$option_value_id = $this->db->getLastId();

		foreach($this->getLanguages() as $language){
			$this->db->query("INSERT INTO " . DB_PREFIX . "option_value_description SET option_value_id = '" . (int)$option_value_id . "', language_id = '" . (int)$language['language_id'] . "', option_id = '" . (int)$option_id . "', name = '" . $this->db->escape($optionv) . "'");
		}
		return $option_value_id;
	}
	//Options END
	
	
	//Discounts
	public function checkdiscount($discountdata){
		$results = explode(',',$discountdata);
		$discounts=array();
		foreach($results as $result){
			if($result){
				$rdiscount = explode('::',$result);
				$discounts[]=array(
				  'customer_group_id' => (isset($rdiscount[0]) ? $rdiscount[0] : ''),
				  'quantity' 		  => (isset($rdiscount[1]) ? $rdiscount[1] : ''),
				  'priority' 		  => (isset($rdiscount[2]) ? $rdiscount[2] : ''),
				  'price' 		 	  => (isset($rdiscount[3]) ? $rdiscount[3] : ''),
				  'startdate' 		  => (isset($rdiscount[4]) ? $rdiscount[4] : ''),
				  'enddate' 		  => (isset($rdiscount[5]) ? $rdiscount[5] : ''),
				);
		    }
		}
		return $discounts;
	}
	
	//Specails
	public function checkspecial($specialdata){
		$results = explode(',',$specialdata);
		$special=array();
		foreach($results as $result){
			if($result){
				$rdiscount = explode('::',$result);
				$special[]=array(
				  'customer_group_id' => (isset($rdiscount[0]) ? $rdiscount[0] : ''),
				  'priority' 		  => (isset($rdiscount[1]) ? $rdiscount[1] : ''),
				  'price' 		 	  => (isset($rdiscount[2]) ? $rdiscount[2] : ''),
				  'startdate' 		  => (isset($rdiscount[3]) ? $rdiscount[3] : ''),
				  'enddate' 		  => (isset($rdiscount[4]) ? $rdiscount[4] : ''),
				);
		    }
		}
		return $special;
	}
	
	///Images
	public function getLanguages($data = array()){
		$language_data = $this->cache->get('language');
		if(!$language_data){
			$language_data = array();

			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language ORDER BY sort_order, name");

			foreach($query->rows as $result){
				$language_data[$result['code']] = array(
					'language_id' => $result['language_id'],
					'name'        => $result['name'],
					'code'        => $result['code'],
					'locale'      => $result['locale'],
					'image'       => $result['image'],
					'directory'   => $result['directory'],
					'sort_order'  => $result['sort_order'],
					'status'      => $result['status']
				);
			}

			$this->cache->set('language', $language_data);
		}
		
		return $language_data;
	}
	
	public function getproductIDbymodel($model){
		$query = $this->db->query("SELECT product_id FROM ".DB_PREFIX."product WHERE LCASE(model) = '".$this->db->escape(utf8_strtolower($model))."'");
		
		return (isset($query->row['product_id']) ? $query->row['product_id'] : '');
	}
	
	public function checkoptionsrequred($optionsrequireddata){
	   $optionrequireds = array();
	   $results = explode(';',html_entity_decode($optionsrequireddata));
		$i=0;
	   foreach($results as $result){
		$opt = explode('=',$result);
		if(!empty($opt[1])){
		  $required = $opt[1];
		}else{
		  $required = 0;
		}
		 
		if(!empty($opt[0])){
			$path = explode(':',$opt[0]);
			$option = (isset($path[0]) ? trim($path[0]) : '');
			$type   = (isset($path[1]) ? trim($path[1]) : '');
			if($option && $type){
				$oquery = $this->db->query("SELECT o.option_id FROM `" . DB_PREFIX . "option` o LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE o.type = '".$this->db->escape(utf8_strtolower($type))."' AND LCASE(od.name) = '".$this->db->escape(utf8_strtolower($option))."' LIMIT 0,1");
			
				if(!empty($oquery->row['option_id'])){
					$i++;
					$optionrequireds[]=array(
						'option_id'	=> $oquery->row['option_id'],
						'required'	=> $required,
					);
				}
			}
		 }
	   }
		return $optionrequireds;
	}
	
	public function fetchingimage($image_url, $image_file){
		if(strpos($image_url,'https://') !== false){
			$image_url = str_replace ( ' ', '%20', $image_url);
			$imagename = preg_replace ('/[^\p{L}\p{N}]/u', '', $image_file);
			$pathinfo = pathinfo($image_url);
			if(!empty($pathinfo['extension'])){
			  $extension = $pathinfo['extension'];
			}else{
			  $extension = 'jpeg';
			}
			
			$path = DIR_IMAGE.'catalog/products/';
			if(!file_exists($path)){
			  mkdir($path);
			  chmod($path,0777);
			}
			
			$fp = fopen ($path.$imagename.'.'.$extension, 'wb');
			$ch = curl_init($image_url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_exec($ch);
			curl_close($ch);                            
			fclose($fp); 
			return 'catalog/products/'.$imagename.'.'.$extension;
		}elseif(strpos($image_url,'http://') !== false){
			$image_url = str_replace ( ' ', '%20', $image_url);
			$pathinfo = pathinfo($image_url);
			if(!empty($pathinfo['extension'])){
			  $extension = $pathinfo['extension'];  			
			}else{
			  $extension = 'jpeg';
			}
			
			$path = DIR_IMAGE.'catalog/products/';
			if(!file_exists($path)){
			  mkdir($path);
			  chmod($path,0777);
			}
			$imagename = preg_replace ('/[^\p{L}\p{N}]/u', '', $image_file);
			$fp = fopen ($path.$imagename.'.'.$extension, 'wb');
			$ch = curl_init($image_url);
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_exec($ch);
			curl_close($ch); 
			fclose($fp); 
			return 'catalog/products/'.$imagename.'.'.$extension;
		}else{
			return $image_url;
		}
	}
}
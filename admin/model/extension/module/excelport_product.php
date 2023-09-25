<?php 
class ModelExtensionModuleExcelportproduct extends ModelExtensionModuleExcelport {
	public function importXLSProductsFull($language, $allLanguages, $file, $importLimit, $addAsNew = false) {
		$this->language->load('extension/module/excelport');
		if (!is_numeric($importLimit) || $importLimit < 10 || $importLimit > 800) throw new Exception($this->language->get('excelport_import_limit_invalid'));
		
		$default_language = $this->config->get('config_language_id');
		$this->config->set('config_language_id', $language);
		
		$progress = $this->getProgress();
		$progress['importedCount'] = !empty($progress['importedCount']) ? $progress['importedCount'] : 0;
		$progress['done'] = false;
		
		require_once(IMODULE_ROOT.'vendors/phpexcel/PHPExcel.php');
		// Create new PHPExcel object
		
		require_once(IMODULE_ROOT.'vendors/phpexcel/CustomReadFilter.php');
		$chunkFilter = new CustomReadFilter(array("Products" => array('A', ($this->productSize*$progress['importedCount'] + 2), 'Q', ($this->productSize*($progress['importedCount'] + $importLimit) + 1)), "Product" => array('A', ($this->productSize*$progress['importedCount'] + 2), 'Q', ($this->productSize*($progress['importedCount'] + $importLimit) + 1)), "product" => array('A', ($this->productSize*$progress['importedCount'] + 2), 'Q', ($this->productSize*($progress['importedCount'] + $importLimit) + 1)), "products" => array('A', ($this->productSize*$progress['importedCount'] + 2), 'Q', ($this->productSize*($progress['importedCount'] + $importLimit) + 1))), true); 
		
		$madeImports = false;
		$objReader = new PHPExcel_Reader_Excel2007();
		$objReader->setReadFilter($chunkFilter);
		$objReader->setReadDataOnly(true);
		$objReader->setLoadSheetsOnly(array("Products", "products", "Product", "product"));
		$objPHPExcel = $objReader->load($file);
		$progress['importingFile'] = substr($file, strripos($file, '/') + 1);
		$productsSheet = 0;
		
		$productSheetObj = $this->getSheet($objPHPExcel, array('products', 'product'));
		
		$progress['all'] = -1; //(int)(($productSheetObj->getHighestRow() - 2)/$this->productSize);
		$this->setProgress($progress);
		
		$this->load->model('catalog/product');
		
		$optionValues = array();
		$this->load->model('catalog/option');
		$options = $this->model_catalog_option->getOptions();
		for ($i = 0; $i < count($options); $i++) {
			$optionValues[] = trim($options[$i]['name']);
		}
		
		$map = array(
			'product_id' 		=> array(0,0),
			'name'				=> array(1,0),
			'model'				=> array(2,0),
			'meta_description' 	=> array(1,2),
			'meta_keyword' 		=> array(1,3),
			'meta_title' 		=> array(17,0),
			'description' 		=> array(16,0),
			'tag'				=> array(1,4),
			'sku'				=> array(3,0),
			'upc'				=> array(1,6),
			'ean'				=> array(1,7),
			'jan'				=> array(1,8),
			'isbn'				=> array(1,9),
			'mpn'				=> array(1,10),
			'location'			=> array(1,11),
			'price'				=> array(14,0),
			'tax_class'			=> array(1,12),
			'quantity'			=> array(13,0),
			'minimum'			=> array(1,13),
			'subtract'			=> array(1,14),
			'stock_status'		=> array(1,15),
			'shipping'			=> array(1,16),
			'keyword'			=> array(1,17),
			'image'				=> array(6,0),
			'date_available'	=> array(1,18),
			'length'			=> array(7,0),
			'width'				=> array(8,0),
			'height'			=> array(9,0),
			'length_class'		=> array(1,19),
			'weight'			=> array(10,0),
			'weight_class'		=> array(1,20),
			'status'			=> array(5,0),
			'sort_order'		=> array(1,21),
			'manufacturer'		=> array(11,0),
			'categories'		=> array(4,0),
			'stores'			=> array(12,0),
			'downloads'			=> array(1,23),
			'filters'			=> array(1,24),
			'recurrings'			=> array(1,25),
			'related'			=> array(15,0),
			'attribute'			=> array(4,1),
			'option'			=> array(4,3),
			'discount'			=> array(4,9),
			'special'			=> array(4,15),
			'product_image'		=> array(4,20),
			'points'			=> array(4,23),
			'reward_points'		=> array(5,22),
			'design'			=> array(4,24)
		);
		
		$source = array(0,2 + $this->productSize*($progress['importedCount']));
		
		$this->load->model('localisation/tax_class');
		$product_tax_classes = $this->model_localisation_tax_class->getTaxClasses();
		
		$this->load->model('localisation/stock_status');
		$product_stock_statusses = $this->model_localisation_stock_status->getStockStatuses();
		
		$this->load->model('localisation/length_class');
		$product_length_classes = $this->model_localisation_length_class->getLengthClasses();
		
		$this->load->model('localisation/weight_class');
		$product_weight_classes = $this->model_localisation_weight_class->getWeightClasses();
		
		$this->load->model('catalog/manufacturer');
		$product_manufacturers = $this->model_catalog_manufacturer->getManufacturers();
		
		$this->load->model('catalog/attribute');
		$attributes = $this->model_catalog_attribute->getAttributes();
		
		$this->load->model('catalog/attribute_group');
		$attributeGroups = $this->model_catalog_attribute_group->getAttributeGroups();
		
		$this->load->model('catalog/option');
		$options = $this->model_catalog_option->getOptions();
		
        if (version_compare(VERSION, '2.1.0.1', '>=')) {
            $this->load->model('customer/customer_group');
            $customer_groups = $this->model_customer_customer_group->getCustomerGroups();
        } else {
            $this->load->model('sale/customer_group');
            $customer_groups = $this->model_sale_customer_group->getCustomerGroups();
        }
		
		$this->load->model('setting/store');
		$stores = array_merge(array(0 => array('store_id' => 0, 'name' => 'Default', 'url' => NULL, 'ssl' => NULL)),$this->model_setting_store->getStores());
		$this->load->model('design/layout');
		$layouts = $this->model_design_layout->getLayouts();
		
		do {
			$this->custom_set_time_limit();
			$product_name = strval($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['name'][0]) . ($source[1] + $map['name'][1]))->getValue());
			$product_name = $this->special_chars($product_name);
			if (!empty($product_name)) {
				$product_model = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['model'][0]) . ($source[1] + $map['model'][1]))->getValue();
				$product_id = (int)trim($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['product_id'][0]) . ($source[1] + $map['product_id'][1]))->getValue());
				$product_price = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['price'][0]) . ($source[1] + $map['price'][1]))->getValue();
				$product_price = (float)str_replace(array(' ', ','), array('', '.'), $product_price);
				
				$found = false;
				foreach ($product_tax_classes as $product_tax_class) {
					if ($product_tax_class['title'] == $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['tax_class'][0]) . ($source[1] + $map['tax_class'][1]))->getValue()) {
						$found = true;
						$product_tax_class_id = $product_tax_class['tax_class_id'];
						break;
					}
				}
				if (!$found) $product_tax_class_id = 0;
				
				$product_quantity = (int)str_replace(' ', '', $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['quantity'][0]) . ($source[1] + $map['quantity'][1]))->getValue());
				$product_minimum = (int)str_replace(' ', '', $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['minimum'][0]) . ($source[1] + $map['minimum'][1]))->getValue());
				$product_subtract = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['subtract'][0]) . ($source[1] + $map['subtract'][1]))->getValue() == 'Yes' ? 1 : 0;
				
				$found = false;
				foreach ($product_stock_statusses as $product_stock_status) {
					if ($product_stock_status['name'] == $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['stock_status'][0]) . ($source[1] + $map['stock_status'][1]))->getValue()) {
						$found = true;
						$product_stock_status_id = $product_stock_status['stock_status_id'];
						break;
					}
				}
				if (!$found) $product_stock_status_id = 0;
				
				$product_shipping = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['shipping'][0]) . ($source[1] + $map['shipping'][1]))->getValue() == 'Yes' ? 1 : 0;
				$product_length = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['length'][0]) . ($source[1] + $map['length'][1]))->getValue();
				$product_length = (float)str_replace(array(' ', ','), array('', '.'), $product_length);
				$product_width = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['width'][0]) . ($source[1] + $map['width'][1]))->getValue();
				$product_width = (float)str_replace(array(' ', ','), array('', '.'), $product_width);
				$product_height = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['height'][0]) . ($source[1] + $map['height'][1]))->getValue();
				$product_height = (float)str_replace(array(' ', ','), array('', '.'), $product_height);
				
				$found = false;
				foreach ($product_length_classes as $product_length_class) {
					if ($product_length_class['title'] == $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['length_class'][0]) . ($source[1] + $map['length_class'][1]))->getValue()) {
						$found = true;
						$product_length_class_id = $product_length_class['length_class_id'];
						break;
					}
				}
				if (!$found) $product_length_class_id = 0;
				
				$product_weight = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['weight'][0]) . ($source[1] + $map['weight'][1]))->getValue();
				$product_weight = (float)str_replace(array(' ', ','), array('', '.'), $product_weight);
				
				$found = false;
				foreach ($product_weight_classes as $product_weight_class) {
					if ($product_weight_class['title'] == $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['weight_class'][0]) . ($source[1] + $map['weight_class'][1]))->getValue()) {
						$found = true;
						$product_weight_class_id = $product_weight_class['weight_class_id'];
						break;
					}
				}
				if (!$found) $product_weight_class_id = 0;
				
				$product_status = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['status'][0]) . ($source[1] + $map['status'][1]))->getValue() == 'Enabled' ? 1 : 0;
				$product_sort_order = (int)str_replace(' ', '', $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['sort_order'][0]) . ($source[1] + $map['sort_order'][1]))->getValue());
				
				$found = false;
				foreach ($product_manufacturers as $product_manufacturer) {
					if ($product_manufacturer['name'] == $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['manufacturer'][0]) . ($source[1] + $map['manufacturer'][1]))->getValue()) {
						$found = true;
						$product_manufacturer_id = $product_manufacturer['manufacturer_id'];
						break;
					}
				}
				if (!$found) $product_manufacturer_id = 0;
				
				$product_store = array();
				$product_stores = explode(',', str_replace('.', ',', strval($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['stores'][0]) . ($source[1] + $map['stores'][1]))->getValue())));
				foreach ($product_stores as $store) {
					$store = trim($store);
					if ($store !== '') $product_store[] = $store;
				}
				
				$product_category = array();
				$categories = explode(',', str_replace('.', ',', strval($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['categories'][0]) . ($source[1] + $map['categories'][1]))->getValue())));
				foreach ($categories as $category) {
					$category = trim($category);
					if (!empty($category)) $product_category[] = trim($category);
				}
				
				$product_filter = array();
				if (version_compare(VERSION, '1.5.5', '>=')) {
					$filters = explode(',', strval($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['filters'][0]) . ($source[1] + $map['filters'][1]))->getValue()));
					foreach ($filters as $filter) {
						$filter = trim($filter);
						if (!empty($filter)) $product_filter[] = trim($filter);
					}
				} 
				
				$product_recurring = array();
				if (version_compare(VERSION, '2.0', '>=')) {
					$recurring_customer_groups = explode(',', strval($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['recurrings'][0]) . ($source[1] + $map['recurrings'][1]))->getValue()));
					
					foreach ($recurring_customer_groups as $recurring_customer_group) {
						$recurring_customer_group = explode(':', trim($recurring_customer_group));
						
						if (!empty($recurring_customer_group[0]) && !empty($recurring_customer_group[1])) $product_recurring[] = array(
							'recurring_id' => trim($recurring_customer_group[0]),
							'customer_group_id' => trim($recurring_customer_group[1])
						);
					}
				}
				
				$product_download = array();
				$downloads = explode(',', str_replace('.', ',', strval($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['downloads'][0]) . ($source[1] + $map['downloads'][1]))->getValue())));
				foreach ($downloads as $download) {
					$download = trim($download);
					if (!empty($download)) $product_download[] = trim($download);
				}
				
				$product_related = array();

				if (!$addAsNew) {
					$related = explode(',', str_replace('.', ',', strval($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['related'][0]) . ($source[1] + $map['related'][1]))->getValue())));
					foreach ($related as $relate) {
						$relate = trim($relate);
						if (!empty($relate)) $product_related[] = trim($relate);
					}
				}
				
				$i = 0;
				$attributeName = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['attribute'][0] + $i) . ($source[1] + $map['attribute'][1]))->getValue();
				
				$product_attribute = array();
				while(!empty($attributeName)) {
					$attributeName = explode($this->productAttributeSeparator, $attributeName);
					if (!empty($attributeName[0]) && !empty($attributeName[1])) {
						foreach ($attributes as $attribute) {
							foreach ($attributeGroups as $attributeGroup) {
								if (trim($attribute['name']) == trim($attributeName[1]) && trim($attributeGroup['name']) == trim($attributeName[0]) && $attribute['attribute_group_id'] == $attributeGroup['attribute_group_id']) {
									$product_attribute[] = array(
										'name' => $attributeName[1],
										'attribute_id' => $attribute['attribute_id'],
										'product_attribute_description' => array(
											$language => array(
												'text' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['attribute'][0] + $i) . ($source[1] + $map['attribute'][1] + 1))->getValue()
											)
										)
									);
									break 2;
								}
							}
						}
					}
					$i++;
					$attributeName = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['attribute'][0] + $i) . ($source[1] + $map['attribute'][1]))->getValue();	
				}
				
				$i = -1;
				$j = -1;
				$k = -1;
				
				$product_option = array();
				$product_option_value = array();
				do {
					$i++;
					$optionCell = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['option'][0] + $i) . ($source[1] + $map['option'][1]));
					$optionValue = trim($optionCell->getValue());
					$optionCellCheck = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['option'][0] + $i) . ($source[1] + $map['option'][1] + 1));
					$optionValueCheck = trim($optionCellCheck->getValue());
					if (strval($optionValue) !== "") {
						
						/*foreach($productSheetObj->getMergeCells() as $cells) {
							if ($optionCellCheck->isInRange($cells)) {
								$isParent = true;	
							}
						}*/
						$isParent = in_array($optionValueCheck, $optionValues, true);
						if ($isParent) {
							foreach ($options as $option) {
								if ($optionValueCheck == trim($option['name'])) {
									$j++;
									$product_option[$j] = array(
										'product_option_id' => '',
										'name' => $option['name'],
										'option_id' => $option['option_id'],
										'type' => $option['type'],
										'required' => $optionValue == 'Required: Yes' ? 1 : 0,
										'value' => ''
									);
									break;
								}
							}
						} else {
							if (!empty($product_option[$j]['option_id'])) {
								if (in_array($product_option[$j]['type'], array('radio', 'checkbox', 'select', 'image'))) {
									$option_values = $this->model_catalog_option->getOptionValues($product_option[$j]['option_id']);
									foreach ($option_values as $option_value) {
										if (strval(trim($option_value['name'])) === strval(trim($optionValue))) {
											$k++;
											$product_option_value_price = trim($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['option'][0] + $i) . ($source[1] + $map['option'][1] + 3))->getValue());
											$product_option_value_price_prefix = stripos($product_option_value_price, '-') === 0 ? '-' : '+';
											$product_option_value_price = (float)str_replace(array('+', '-', ' ', ','), array('', '', '', '.'), $product_option_value_price);
											$product_option_value_points = trim($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['option'][0] + $i) . ($source[1] + $map['option'][1] + 4))->getValue());
											$product_option_value_points_prefix = stripos($product_option_value_points, '-') === 0 ? '-' : '+';
											$product_option_value_points = (float)str_replace(array('+', '-', ' ', ','), array('', '', '', '.'), $product_option_value_points);
											$product_option_value_weight = trim($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['option'][0] + $i) . ($source[1] + $map['option'][1] + 5))->getValue());
											$product_option_value_weight_prefix = stripos($product_option_value_weight, '-') === 0 ? '-' : '+';
											$product_option_value_weight = (float)str_replace(array('+', '-', ' ', ','), array('', '', '', '.'), $product_option_value_weight);
											unset($product_option[$j]['value']);
											$product_option[$j]['product_option_value'][$k] = array (
												'option_value_id' => $option_value['option_value_id'],
												'product_option_value_id' => '',
												'quantity' => (int)trim($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['option'][0] + $i) . ($source[1] + $map['option'][1] + 1))->getValue()),
												'subtract' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['option'][0] + $i) . ($source[1] + $map['option'][1] + 2))->getValue() == 'Yes' ? 1 : 0,
												'price_prefix' => $product_option_value_price_prefix,
												'price' => $product_option_value_price,
												'points_prefix' => $product_option_value_points_prefix,
												'points' => $product_option_value_points,
												'weight_prefix' => $product_option_value_weight_prefix,
												'weight' => $product_option_value_weight
											);
											break;
										}
									}
								} else {
									$product_option[$j]['value'] = $optionValue;
								}
							}
						}
					}
				} while (strval($optionValue) !== "");

				// Discount
				$i = 0;
				$discountCustomerGroupName = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['discount'][0] + $i) . ($source[1] + $map['discount'][1]))->getValue();
				$product_discount = array();
				while(!empty($discountCustomerGroupName)) {
					foreach ($customer_groups as $customer_group) {
						if (trim(strtolower($customer_group['name'])) == trim(strtolower($discountCustomerGroupName))) {
							$product_discount[] = array(
								'customer_group_id' => $customer_group['customer_group_id'],
								'quantity' => (int)trim($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['discount'][0] + $i) . ($source[1] + $map['discount'][1] + 5))->getValue()),
								'priority' => (int)trim($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['discount'][0] + $i) . ($source[1] + $map['discount'][1] + 1))->getValue()),
								'price' => (float)trim(str_replace(array(' ', ','), array('', '.'),$productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['discount'][0] + $i) . ($source[1] + $map['discount'][1] + 2))->getValue())),
								'date_start' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['discount'][0] + $i) . ($source[1] + $map['discount'][1] + 3))->getValue(),
								'date_end' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['discount'][0] + $i) . ($source[1] + $map['discount'][1] + 4))->getValue()
							);
							break;
						}
					}
					$i++;
					$discountCustomerGroupName = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['discount'][0] + $i) . ($source[1] + $map['discount'][1]))->getValue();
				}
				
				// Special
				$i = 0;
				$specialCustomerGroupName = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['special'][0] + $i) . ($source[1] + $map['special'][1]))->getValue();
				$product_special = array();
				while(!empty($specialCustomerGroupName)) {
					foreach ($customer_groups as $customer_group) {
						if (trim(strtolower($customer_group['name'])) == trim(strtolower($specialCustomerGroupName))) {
							$product_special[] = array(
								'customer_group_id' => $customer_group['customer_group_id'],
								'priority' => (int)trim($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['special'][0] + $i) . ($source[1] + $map['special'][1] + 1))->getValue()),
								'price' => (float)trim(str_replace(array(' ', ','), array('', '.'),$productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['special'][0] + $i) . ($source[1] + $map['special'][1] + 2))->getValue())),
								'date_start' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['special'][0] + $i) . ($source[1] + $map['special'][1] + 3))->getValue(),
								'date_end' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['special'][0] + $i) . ($source[1] + $map['special'][1] + 4))->getValue()
							);
							break;
						}
					}
					$i++;
					$specialCustomerGroupName = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['special'][0] + $i) . ($source[1] + $map['special'][1]))->getValue();
				}
				
				// Image
				$i = 0;
				$imagePath = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['product_image'][0] + $i) . ($source[1] + $map['product_image'][1]))->getValue();
				$product_image = array();
				while(!empty($imagePath)) {
					$product_image[] = array(
						'image' => trim($imagePath),
						'sort_order' => (int)trim($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['product_image'][0] + $i) . ($source[1] + $map['product_image'][1] + 1))->getValue())
					);
					$i++;
					$imagePath = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['product_image'][0] + $i) . ($source[1] + $map['product_image'][1]))->getValue();
				}
				
				// Reward Points
				$i = 0;
				$rewardCustomerGroupName = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['reward_points'][0] + $i) . ($source[1] + $map['reward_points'][1]))->getValue();
				$product_reward = array();
				while(!empty($rewardCustomerGroupName)) {
					foreach ($customer_groups as $customer_group) {
						if (trim(strtolower($customer_group['name'])) == trim(strtolower($rewardCustomerGroupName))) {
							$product_reward[$customer_group['customer_group_id']] = array(
								'points' => (int)trim($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['reward_points'][0] + $i) . ($source[1] + $map['reward_points'][1] + 1))->getValue())
							);
							break;
						}
					}
					$i++;
					$rewardCustomerGroupName = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['reward_points'][0] + $i) . ($source[1] + $map['reward_points'][1]))->getValue();
				}
				
				// Layouts (Design)
				$i = 0;
				
				$storeName = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['design'][0] + $i) . ($source[1] + $map['design'][1]))->getValue();
				$product_layout = array();
				while(!empty($storeName)) {
					foreach ($stores as $store) {
						if ($store['name'] == $storeName) {
							$found = false;
							foreach ($layouts as $layout) {
								if ($layout['name'] == $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['design'][0] + $i) . ($source[1] + $map['design'][1] + 1))->getValue()) {
										$product_layout[$store['store_id']] = array(
											'layout_id' => $layout['layout_id']
										);
										$found = true;
										break;
								}
							}
							if (!$found) {
								$product_layout[$store['store_id']] = array(
									'layout_id' => ''
								);
							}
						}
					}
					$i++;
					$storeName = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['design'][0] + $i) . ($source[1] + $map['design'][1]))->getValue();
				}
				
				$product = array(
					'product_description' => array(
						$language => array(
							'name' => $product_name,
							'meta_description' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['meta_description'][0]) . ($source[1] + $map['meta_description'][1]))->getValue(),
							'meta_keyword' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['meta_keyword'][0]) . ($source[1] + $map['meta_keyword'][1]))->getValue(),
							'meta_title' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['meta_title'][0]) . ($source[1] + $map['meta_title'][1]))->getValue(),
							'description' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['description'][0]) . ($source[1] + $map['description'][1]))->getValue(),
							'tag' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['tag'][0]) . ($source[1] + $map['tag'][1]))->getValue(),
						)
					),
					'model' => $product_model,
					'sku' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['sku'][0]) . ($source[1] + $map['sku'][1]))->getValue(),
					'upc' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['upc'][0]) . ($source[1] + $map['upc'][1]))->getValue(),
					'ean' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['ean'][0]) . ($source[1] + $map['ean'][1]))->getValue(),
					'jan' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['jan'][0]) . ($source[1] + $map['jan'][1]))->getValue(),
					'isbn' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['isbn'][0]) . ($source[1] + $map['isbn'][1]))->getValue(),
					'mpn' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['mpn'][0]) . ($source[1] + $map['mpn'][1]))->getValue(),
					'location' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['location'][0]) . ($source[1] + $map['location'][1]))->getValue(),
					'price' => $product_price,
					'tax_class_id' => $product_tax_class_id,
					'quantity' => trim($product_quantity),
					'minimum' => trim($product_minimum),
					'subtract' => $product_subtract,
					'stock_status_id' => $product_stock_status_id,
					'shipping' => $product_shipping,
					'keyword' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['keyword'][0]) . ($source[1] + $map['keyword'][1]))->getValue(),
					'image' => trim($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['image'][0]) . ($source[1] + $map['image'][1]))->getValue()),
					'date_available' => trim($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['date_available'][0]) . ($source[1] + $map['date_available'][1]))->getValue()),
					'length' => $product_length,
					'width' => $product_width,
					'height' => $product_height,
					'length_class_id' => $product_length_class_id,
					'weight' => $product_weight,
					'weight_class_id' => $product_weight_class_id,
					'status' => $product_status,
					'sort_order' => $product_sort_order,
					'manufacturer_id' => $product_manufacturer_id,
					'product_category' => $product_category,
					'product_filter' => $product_filter,
					'product_store' => $product_store,
					'product_download' => $product_download,
					'related' => '',
					'product_related' => $product_related,
					'product_attribute' => $product_attribute,
					'option' => '',
					'product_option' => $product_option,
					'product_discount' => $product_discount,
					'product_special' => $product_special,
					'product_image' => $product_image,
					'product_recurrings' => $product_recurring,
					'points' => (int)trim($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['points'][0]) . ($source[1] + $map['points'][1]))->getValue()),
					'product_reward' => $product_reward,
					'product_layout' => $product_layout
				);
				
				if (version_compare(VERSION, '1.5.4', '<')) {
					$product['product_tag'] = array(
						$language => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['tag'][0]) . ($source[1] + $map['tag'][1]))->getValue()
					);	
				}
				
				// Extras
				foreach ($this->extraGeneralFields['Products'] as $extra) {
					if (!empty($extra['name']) && !empty($extra['column_full'])) {
						$product[$extra['name']] = $productSheetObj->getCell($extra['column_full'] . $source[1])->getValue();	
					}
				}
				
				if (!$addAsNew) {
					$exists = false;
					$existsQuery = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE product_id = ".$product_id);
					
					$exists = $existsQuery->num_rows > 0;
							
					if ($exists) {
						$this->editProduct($product_id, $product, $allLanguages);
					} else {
						$this->addProduct($product_id, $product, $allLanguages);
					}
				} else {
					$this->addProduct('', $product, $allLanguages);
				}
				
				$progress['current']++;
				$progress['importedCount']++;
				$madeImports = true;
				$this->setProgress($progress);
			}
			$source[1] += $this->productSize;
		} while (!empty($product_name));
		$progress['done'] = true;
		if (!$madeImports) {
			$progress['importedCount'] = 0;
			array_shift($this->session->data['uploaded_files']);
		}
		$this->setProgress($progress);
		
		
		$this->config->set('config_language_id', $default_language);	
	}
	
	public function importXLSProductsLight($language, $allLanguages, $file, $importLimit, $addAsNew = false) {
		$this->language->load('extension/module/excelport');
		if (!is_numeric($importLimit) || $importLimit < 10 || $importLimit > 800) throw new Exception($this->language->get('excelport_import_limit_invalid'));
		
		$default_language = $this->config->get('config_language_id');
		$this->config->set('config_language_id', $language);
		
		$progress = $this->getProgress();
		$progress['importedCount'] = !empty($progress['importedCount']) ? $progress['importedCount'] : 0;
		$progress['done'] = false;
		
		require_once(IMODULE_ROOT.'vendors/phpexcel/PHPExcel.php');
		// Create new PHPExcel object
		
		require_once(IMODULE_ROOT.'vendors/phpexcel/CustomReadFilter.php');
		$chunkFilter = new CustomReadFilter(array("Products" => array('A', ($progress['importedCount'] + 2), 'AM', (($progress['importedCount'] + $importLimit) + 1)), "products" => array('A', ($progress['importedCount'] + 2), 'AM', (($progress['importedCount'] + $importLimit) + 1))), true); 
		
		$madeImports = false;
		$objReader = new PHPExcel_Reader_Excel2007();
		$objReader->setReadFilter($chunkFilter);
		$objReader->setReadDataOnly(true);
		$objReader->setLoadSheetsOnly(array("Products", "products"));
		$objPHPExcel = $objReader->load($file);
		$progress['importingFile'] = substr($file, strripos($file, '/') + 1);
		$productsSheet = 0;
		
		$productSheetObj = $objPHPExcel->setActiveSheetIndex($productsSheet);
		
		$progress['all'] = -1; //(int)(($productSheetObj->getHighestRow() - 2)/$this->productSize);
		$this->setProgress($progress);
		
		$this->load->model('catalog/product');
		
		$map = array(
			'product_id' 		=> 0,
			'name'				=> 1,
			'meta_description'	=> 5,
			'meta_keyword'		=> 6,
			'description'		=> 4,
			'tag'				=> 7,
			'model' 			=> 2,
			'sku'				=> 3,
			'upc'				=> 8,
			'ean'				=> 9,
			'jan'				=> 10,
			'isbn'				=> 11,
			'mpn'				=> 12,
			'location'			=> 14,
			'price'				=> 13,
			'tax_class'	 		=> 16,
			'quantity'			=> 17,
			'minimum'			=> 18,
			'subtract'			=> 20,
			'stock_status' 		=> 21,
			'shipping'			=> 22,
			'keyword'			=> 23,
			'image'				=> 19,
			'date_available'	=> 24,
			'length'			=> 25,
			'width'				=> 26,
			'height'			=> 27,
			'length_class'		=> 28,
			'weight'			=> 29,
			'weight_class'		=> 30,
			'status'			=> 15,
			'sort_order'		=> 31,
			'points'			=> 32,
			'manufacturer'		=> 33,
			'categories'		=> 34,
			'filters'			=> 35,
			'stores'			=> 36,
			'downloads'			=> 37,
			'related'			=> 38,
			'meta_title'			=> 39
		);
		
		$source = array(0,2 + ($progress['importedCount']));
		
		$this->load->model('localisation/tax_class');
		$product_tax_classes = $this->model_localisation_tax_class->getTaxClasses();
		
		$this->load->model('localisation/stock_status');
		$product_stock_statusses = $this->model_localisation_stock_status->getStockStatuses();
		
		$this->load->model('localisation/length_class');
		$product_length_classes = $this->model_localisation_length_class->getLengthClasses();
		
		$this->load->model('localisation/weight_class');
		$product_weight_classes = $this->model_localisation_weight_class->getWeightClasses();
		
		$this->load->model('catalog/manufacturer');
		$product_manufacturers = $this->model_catalog_manufacturer->getManufacturers();
		
		$product_reward = array();

        if (version_compare(VERSION, '2.1.0.1', '>=')) {
            $this->load->model('customer/customer_group');
            $customer_groups = $this->model_customer_customer_group->getCustomerGroups();
        } else {
            $this->load->model('sale/customer_group');
            $customer_groups = $this->model_sale_customer_group->getCustomerGroups();
        }
		
		foreach ($customer_groups as $customer_group) {
			$product_reward[$customer_group['customer_group_id']] = array(
				'points' => ''
			);
		}
		
		$product_layout = array();
		$this->load->model('setting/store');
		$stores = array_merge(array(0 => array('store_id' => 0, 'name' => 'Default', 'url' => NULL, 'ssl' => NULL)),$this->model_setting_store->getStores());
		foreach ($stores as $store) {
			$product_layout[$store['store_id']] = array(
				'layout_id' => ''
			);
		}
		
		do {
			$this->custom_set_time_limit();
			$product_name = strval($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['name']) . ($source[1]))->getValue());
			$product_name = $this->special_chars($product_name);
			if (!empty($product_name)) {
				$product_model = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['model']) . ($source[1]))->getValue();
				$product_id = (int)trim($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['product_id']) . ($source[1]))->getValue());
				$product_price = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['price']) . ($source[1]))->getValue();
				$product_price = (float)str_replace(array(' ', ','), array('', '.'), $product_price);
				
				$found = false;
				foreach ($product_tax_classes as $product_tax_class) {
					if ($product_tax_class['title'] == $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['tax_class']) . ($source[1]))->getValue()) {
						$found = true;
						$product_tax_class_id = $product_tax_class['tax_class_id'];
						break;
					}
				}
				if (!$found) $product_tax_class_id = 0;
				
				$product_quantity = (int)str_replace(' ', '', $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['quantity']) . ($source[1]))->getValue());
				$product_minimum = (int)str_replace(' ', '', $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['minimum']) . ($source[1]))->getValue());
				$product_subtract = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['subtract']) . ($source[1]))->getValue() == 'Yes' ? 1 : 0;
				
				$found = false;
				foreach ($product_stock_statusses as $product_stock_status) {
					if ($product_stock_status['name'] == $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['stock_status']) . ($source[1]))->getValue()) {
						$found = true;
						$product_stock_status_id = $product_stock_status['stock_status_id'];
						break;
					}
				}
				if (!$found) $product_stock_status_id = 0;
				
				$product_shipping = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['shipping']) . ($source[1]))->getValue() == 'Yes' ? 1 : 0;
				$product_length = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['length']) . ($source[1]))->getValue();
				$product_length = (float)str_replace(array(' ', ','), array('', '.'), $product_length);
				$product_width = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['width']) . ($source[1]))->getValue();
				$product_width = (float)str_replace(array(' ', ','), array('', '.'), $product_width);
				$product_height = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['height']) . ($source[1]))->getValue();
				$product_height = (float)str_replace(array(' ', ','), array('', '.'), $product_height);
				
				$found = false;
				foreach ($product_length_classes as $product_length_class) {
					if ($product_length_class['title'] == $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['length_class']) . ($source[1]))->getValue()) {
						$found = true;
						$product_length_class_id = $product_length_class['length_class_id'];
						break;
					}
				}
				if (!$found) $product_length_class_id = 0;
				
				$product_weight = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['weight']) . ($source[1]))->getValue();
				$product_weight = (float)str_replace(array(' ', ','), array('', '.'), $product_weight);
				
				$found = false;
				foreach ($product_weight_classes as $product_weight_class) {
					if ($product_weight_class['title'] == $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['weight_class']) . ($source[1]))->getValue()) {
						$found = true;
						$product_weight_class_id = $product_weight_class['weight_class_id'];
						break;
					}
				}
				if (!$found) $product_weight_class_id = 0;
				
				$product_status = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['status']) . ($source[1]))->getValue() == 'Enabled' ? 1 : 0;
				$product_sort_order = (int)str_replace(' ', '', $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['sort_order']) . ($source[1]))->getValue());
				
				$found = false;
				foreach ($product_manufacturers as $product_manufacturer) {
					if ($product_manufacturer['name'] == $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['manufacturer']) . ($source[1]))->getValue()) {
						$found = true;
						$product_manufacturer_id = $product_manufacturer['manufacturer_id'];
						break;
					}
				}
				if (!$found) $product_manufacturer_id = 0;
				
				$product_store = array();
				$product_stores = explode(',', str_replace('.', ',', strval($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['stores']) . ($source[1]))->getValue())));
				foreach ($product_stores as $store) {
					$store = trim($store);
					if ($store !== '') $product_store[] = $store;
				}
				
				$product_category = array();
				$categories = explode(',', str_replace('.', ',', strval($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['categories']) . ($source[1]))->getValue())));
				foreach ($categories as $category) {
					$category = trim($category);
					if (!empty($category)) $product_category[] = trim($category);
				}
				
				$product_filter = array();
				if (version_compare(VERSION, '1.5.5', '>=')) {
					$filters = explode(',', str_replace('.', ',', strval($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['filters']) . ($source[1]))->getValue())));
					foreach ($filters as $filter) {
						$filter = trim($filter);
						if (!empty($filter)) $product_filter[] = trim($filter);
					}
				} 
				
				$product_download = array();
				$downloads = explode(',', str_replace('.', ',', strval($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['downloads']) . ($source[1]))->getValue())));
				foreach ($downloads as $download) {
					$download = trim($download);
					if (!empty($download)) $product_download[] = trim($download);
				}
				
				$product_related = array();

				if (!$addAsNew) {
					$related = explode(',', str_replace('.', ',', strval($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['related']) . ($source[1]))->getValue())));
					foreach ($related as $relate) {
						$relate = trim($relate);
						if (!empty($relate)) $product_related[] = trim($relate);
					}
				}
				
				$product = array(
					'product_description' => array(
						$language => array(
							'name' => $product_name,
							'meta_description' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['meta_description']) . ($source[1]))->getValue(),
							'meta_keyword' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['meta_keyword']) . ($source[1]))->getValue(),
							'meta_title' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['meta_title']) . ($source[1]))->getValue(),
							'description' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['description']) . ($source[1]))->getValue(),
							'tag' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['tag']) . ($source[1]))->getValue(),
						)
					),
					'model' => $product_model,
					'sku' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['sku']) . ($source[1]))->getValue(),
					'upc' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['upc']) . ($source[1]))->getValue(),
					'ean' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['ean']) . ($source[1]))->getValue(),
					'jan' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['jan']) . ($source[1]))->getValue(),
					'isbn' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['isbn']) . ($source[1]))->getValue(),
					'mpn' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['mpn']) . ($source[1]))->getValue(),
					'location' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['location']) . ($source[1]))->getValue(),
					'price' => $product_price,
					'tax_class_id' => $product_tax_class_id,
					'quantity' => trim($product_quantity),
					'minimum' => trim($product_minimum),
					'subtract' => $product_subtract,
					'stock_status_id' => $product_stock_status_id,
					'shipping' => $product_shipping,
					'keyword' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['keyword']) . ($source[1]))->getValue(),
					'image' => trim($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['image']) . ($source[1]))->getValue()),
					'date_available' => trim($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['date_available']) . ($source[1]))->getValue()),
					'length' => $product_length,
					'width' => $product_width,
					'height' => $product_height,
					'length_class_id' => $product_length_class_id,
					'weight' => $product_weight,
					'weight_class_id' => $product_weight_class_id,
					'status' => $product_status,
					'sort_order' => $product_sort_order,
					'manufacturer_id' => $product_manufacturer_id,
					'product_category' => $product_category,
					'product_filter' => $product_filter,
					'product_store' => $product_store,
					'product_download' => $product_download,
					'related' => '',
					'product_related' => $product_related,
					'option' => '',
					'points' => (int)trim($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['points']) . ($source[1]))->getValue()),
					'product_reward' => $product_reward,
					'product_layout' => $product_layout
				);
				
				if (version_compare(VERSION, '1.5.4', '<')) {
					$product['product_tag'] = array(
						$language => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['tag']) . ($source[1]))->getValue()
					);	
				}
				
				// Extras
				foreach ($this->extraGeneralFields['Products'] as $extra) {
					if (!empty($extra['name']) && !empty($extra['column_light'])) {
						$product[$extra['name']] = $productSheetObj->getCell($extra['column_light'] . $source[1])->getValue();	
					}
				}
				
				if (!$addAsNew) {
					$exists = false;
					$existsQuery = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE product_id = ".$product_id);
					
					$exists = $existsQuery->num_rows > 0;
							
					if ($exists) {
						$this->editProduct($product_id, $product, $allLanguages, true);
					} else {
						$this->addProduct($product_id, $product, $allLanguages);
					}
				} else {
					$this->addProduct('', $product, $allLanguages);
				}
				
				$progress['current']++;
				$progress['importedCount']++;
				$madeImports = true;
				$this->setProgress($progress);
			}
			$source[1] += 1;
		} while (!empty($product_name));
		$progress['done'] = true;
		if (!$madeImports) {
			$progress['importedCount'] = 0;
			array_shift($this->session->data['uploaded_files']);
		}
		$this->setProgress($progress);
		
		$this->config->set('config_language_id', $default_language);	
	}
	
	public function exportXLSProductsFull($language, $store, $destinationFolder = '', $productNumber, $export_filters = array(), $targetHTMLFormat) {
		$this->language->load('extension/module/excelport');
		$this->folderCheck($destinationFolder);
		
		$progress = $this->getProgress();
		$progress['done'] = false;
		
		$file = IMODULE_ROOT . 'vendors/excelport/template_product_full.xlsx';
		
		$default_language = $this->config->get('config_language_id');
		$this->config->set('config_language_id', $language);
		require_once(IMODULE_ROOT.'vendors/phpexcel/PHPExcel.php');
		
		if (!empty($progress['populateAll'])) {
			$all = $this->db->query($this->getQuery($export_filters, $store, $language, true));
			$progress['all'] = $all->num_rows ? (int)$all->row['count'] : 0;
			unset($progress['populateAll']);
			$this->setProgress($progress);
		}
		
		$this->setData('Products', $destinationFolder, $language);
		
		$styleMap = array(
			0 => array(
				'alignment' => array(
					'horizontal' => 'left',
					'vertical' => 'center'
				)
			),
			3 => array(
				'font' => array(
					'name' => 'Calibri',
					'size' => '9',
					'bold' => true
				),
				'alignment' => array(
					'horizontal' => 'center',
					'vertical' => 'center'
				)
			),
			4 => array(
				'borders' => array(
					'right' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THICK
					)
				)
			),
			5 => array(
				'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_NONE
				)
			),
			8 => array(
				'borders' => array(
					'bottom' => array(
						'style' => PHPExcel_Style_Border::BORDER_THICK
					)
				)
			)
		);
		
		$source = array(0,2,16,$this->productSize + 1);
		$productsSheet = 0;
		$templateSheet = 1;
		$metaSheet = 2;
		
		$taxClassesStart = array(0,2);
		$this->load->model('localisation/tax_class');
		$taxClasses = array_merge(array(0 => array('tax_class_id' => 0, 'title' => '--- None ---', 'description' => '--- None ---', 'date_added' => '0000-00-00 00:00:00', 'date_modified' => '0000-00-00 00:00:00')), $this->model_localisation_tax_class->getTaxClasses());
		
		$stockStatesStart = array(2,2);
		$this->load->model('localisation/stock_status');
		$stockStates = $this->model_localisation_stock_status->getStockStatuses();
		
		$lengthClassesStart = array(3,2);
		$this->load->model('localisation/length_class');
		$lengthClasses = $this->model_localisation_length_class->getLengthClasses();
		
		$weightClassesStart = array(4,2);
		$this->load->model('localisation/weight_class');
		$weightClasses = $this->model_localisation_weight_class->getWeightClasses();
		
		$manufacturersStart = array(6,2);
		$this->load->model('catalog/manufacturer');
		$manufacturers = array_merge(array(0 => array('manufacturer_id' => 0, 'name' => '--- None ---', 'image' => NULL, 'sort_order' => 0)), $this->model_catalog_manufacturer->getManufacturers());
		
		$categoriesStart = array(7,3);
		$this->load->model('catalog/category');
		
		if (version_compare(VERSION, '1.5.5', '>=')) {
			$categories = $this->model_catalog_category->getCategories(array());
		} 
		
		if (version_compare(VERSION, '1.5.5', '<')) {
			$categories = $this->model_catalog_category->getCategories();
		} 
		
		if (version_compare(VERSION, '1.5.5', '>=')) {
			$filtersStart = array(19,3);
			$this->load->model('catalog/filter');
			$filters = $this->model_catalog_filter->getFilters(array());
		}
		
		if (version_compare(VERSION, '2.0', '>=')) {
			$recurringsStart = array(21,3);
			$this->load->model('catalog/recurring');
			$recurrings = $this->model_catalog_recurring->getRecurrings();
		}
		
		$storesStart = array(9,3);
		$this->load->model('setting/store');
		$stores = array_merge(array(0 => array('store_id' => 0, 'name' => 'Default', 'url' => NULL, 'ssl' => NULL)),$this->model_setting_store->getStores());
		
		$downloadsStart = array(11,3);
		$this->load->model('catalog/download');
		$downloads = $this->model_catalog_download->getDownloads();
		
		$attributesStart = array(13,2);
		$this->load->model('catalog/attribute');
		$this->load->model('catalog/attribute_group');
		$attributes = $this->model_catalog_attribute->getAttributes();
		$attributeGroups = $this->model_catalog_attribute_group->getAttributeGroups();
		
		$optionsStart = array(14,2);
		$this->load->model('catalog/option');
		$options = $this->model_catalog_option->getOptions();
		
		$requiredCoordinates = array(15,2,15,3);
		
		$customerGroupsStart = array(16,3);

        if (version_compare(VERSION, '2.1.0.1', '>=')) {
            $this->load->model('customer/customer_group');
            $customerGroups = $this->model_customer_customer_group->getCustomerGroups();
        } else {
            $this->load->model('sale/customer_group');
            $customerGroups = $this->model_sale_customer_group->getCustomerGroups();
        }
		
		$layoutsStart = array(18,2);
		$this->load->model('design/layout');
		$layouts = $this->model_design_layout->getLayouts();
		
		$merges = array(0,1,1,1, 0,5,1,5, 0,22,1,22, 2,1,2,2, 2,3,2,8, 2,9,2,14, 2,15,2,19, 2,20,2,21, 2,22,2,23, 2,24,2,25);
		$generals = array(
			'product_id' 		=> array(0,0),
			'name'				=> array(1,0),
			'meta_description'	=> array(1,2),
			'meta_keyword'		=> array(1,3),
			'meta_title'		=> array(17,0),
			'description'		=> array(16,0),
			'tag'				=> array(1,4)
		);
		$datas = array(
			'model' 		=> array(2,0),
			'sku'			=> array(3,0),
			'upc'			=> array(1,6),
			'ean'			=> array(1,7),
			'jan'			=> array(1,8),
			'isbn'			=> array(1,9),
			'mpn'			=> array(1,10),
			'location'		=> array(1,11),
			'price'			=> array(14,0),
			'tax_class'	 	=> array(1,12),
			'quantity'		=> array(13,0),
			'minimum'		=> array(1,13),
			'subtract'		=> array(1,14),
			'stock_status' 	=> array(1,15),
			'shipping'		=> array(1,16),
			'keyword'		=> array(1,17),
			'image'			=> array(6,0),
			'date_available'=> array(1,18),
			'length'		=> array(7,0),
			'width'			=> array(8,0),
			'height'		=> array(9,0),
			'length_class'	=> array(1,19),
			'weight'		=> array(10,0),
			'weight_class'	=> array(1,20),
			'status'		=> array(5,0),
			'sort_order'	=> array(1,21)
		);
		$links = array(
			'manufacturer'		=> array(11,0),
			'categories'		=> array(4,0),
			'filters'			=> array(1,24),
			'stores'			=> array(12,0),
			'downloads'			=> array(1,23),
			'related'			=> array(15,0),
			'recurrings'			=> array(1,25),
		);
		$rewards = array(
			'points'			=> array(4,23)
		);
		
		// Extra fields
		$extras = array();
		foreach ($this->extraGeneralFields['Products'] as $extra) {
			if (!empty($extra['name']) && !empty($extra['column_full'])) {
				$extras[$extra['name']] = array($extra['column_full'],0);
			}
		}
		
		$dynamicTemplates = array(
			'attributes' => array(4,1,4,2),
			'option_types' => array(4,3,4,8),
			'option_values' => array(5,3,5,8),
			'discounts' => array(4,9,4,14),
			'specials' => array(4,15,4,19),
			'images' => array(4,20,4,21),
			'reward_points' => array(5,22,5,23),
			'designs' => array(4,24,4,25)
		);
		
		$dataValidations = array(
			array(
				'type' => 'list',
				'field' => $datas['tax_class'],
				'data' => array($taxClassesStart[0], $taxClassesStart[1], $taxClassesStart[0], $taxClassesStart[1] + count($taxClasses) - 1),
				'range' => '',
				'count' => count($taxClasses)
			),
			array(
				'type' => 'list',
				'field' => $datas['subtract'],
				'data' => array(1,2,1,3),
				'range' => ''
			),
			array(
				'type' => 'list',
				'field' => $datas['stock_status'],
				'data' => array($stockStatesStart[0], $stockStatesStart[1], $stockStatesStart[0], $stockStatesStart[1] + count($stockStates) - 1),
				'range' => '',
				'count' => count($stockStates)
			),
			array(
				'type' => 'list',
				'field' => $datas['shipping'],
				'data' => array(1,2,1,3),
				'range' => ''
			),
			array(
				'type' => 'list',
				'field' => $datas['length_class'],
				'data' => array($lengthClassesStart[0], $lengthClassesStart[1], $lengthClassesStart[0], $lengthClassesStart[1] + count($lengthClasses) - 1),
				'range' => '',
				'count' => count($lengthClasses)
			),
			array(
				'type' => 'list',
				'field' => $datas['weight_class'],
				'data' => array($weightClassesStart[0], $weightClassesStart[1], $weightClassesStart[0], $weightClassesStart[1] + count($weightClasses) - 1),
				'range' => '',
				'count' => count($weightClasses)
			),
			array(
				'type' => 'list',
				'field' => $datas['status'],
				'data' => array(5,2,5,3),
				'range' => ''
			),
			array(
				'type' => 'list',
				'field' => $links['manufacturer'],
				'data' => array($manufacturersStart[0], $manufacturersStart[1], $manufacturersStart[0], $manufacturersStart[1] + count($manufacturers) - 1),
				'range' => '',
				'count' => count($manufacturers)
			)
		);
		
		$leftColumnStaticText = array(array(null));
		$leftColumnStaticTextColumn1 = array('General', 'Meta Tag Description', 'Meta Tag Keywords', 'Product Tags', 'Data', 'UPC', 'EAN', 'JAN', 'ISBN', 'MPN', 'Location', 'Tax Class', 'Minimum Quantity', 'Subtract Stock', 'Out Of Stock Status', 'Requires Shipping', 'SEO Keywords', 'Date Available', 'Length Class', 'Weight Class', 'Sort Order', 'Links', 'Downloads', 'Filters', 'Recurrings');
		$leftColumnStaticTextColumn2 = array_fill(0, $this->productSize - 1, null);
		$leftColumnStaticTextColumn3 = array('Attribute', null, 'Option', null, null, null, null, null, 'Discount', null, null, null, null, null, 'Special', null, null, null, null, 'Image', null, 'Reward Points', null, 'Design', null);
		$leftColumnStaticTextColumn4 = array('Attribute Name', 'Attribute Text', 'Option Value', 'Quantity', 'Subtract Stock', 'Price', 'Points', 'Weight', 'Customer Group', 'Priority', 'Price', 'Date Start', 'Date End', 'Quantity', 'Customer Group', 'Priority', 'Price', 'Date Start', 'Date End', 'Image Path', 'Sort Order', 'Customer Group', 'Reward Points', 'Store', 'Layout Override');
		
		for ($i = 0; $i < count($leftColumnStaticTextColumn1); $i++) {
			$leftColumnStaticText[$i+1] = array($leftColumnStaticTextColumn1[$i], $leftColumnStaticTextColumn2[$i], $leftColumnStaticTextColumn3[$i], $leftColumnStaticTextColumn4[$i]);
		}
		
		$widths = array(18, 13, 12, 16, 16, 20, 19, 14, 14, 14, 14, 15, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12);
		
		$target = array(0,2);
		$template = array(0,2);
		
		$this->load->model('localisation/language');
		$languageQuery = $this->model_localisation_language->getLanguage($this->config->get('config_language_id'));
		
		$name = 'products_excelport_full_' . $languageQuery['code'] . '_' . str_replace('/', '_', substr(HTTP_CATALOG, 7, strlen(HTTP_CATALOG) - 8)) . '_' . date("Y-m-d_H-i-s") . '_' . $progress['current'];
		$resultName = $name . '.xlsx';
		$result = $destinationFolder . '/' . $name . '.xlsx';

		$objPHPExcel = PHPExcel_IOFactory::load($file);
		
		// Set document properties
		$objPHPExcel->getProperties()
					->setCreator($this->user->getUserName())
					->setLastModifiedBy($this->user->getUserName())
					->setTitle($name)
					->setSubject($name)
					->setDescription("Backup for Office 2007 and later, generated using PHPExcel and ExcelPort.")
					->setKeywords("office 2007 2010 2013 xlsx openxml php phpexcel excelport")
					->setCategory("Backup");
		
		$objPHPExcel->getDefaultStyle()->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		
		$metaSheetObj = $objPHPExcel->setActiveSheetIndex($metaSheet);
		$templateSheetObj = $objPHPExcel->setActiveSheetIndex($templateSheet);
		
		for ($i = 0; $i < count($taxClasses); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($taxClassesStart[0]) . ($taxClassesStart[1] + $i), $taxClasses[$i]['title'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		for ($i = 0; $i < count($stockStates); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($stockStatesStart[0]) . ($stockStatesStart[1] + $i), $stockStates[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		for ($i = 0; $i < count($lengthClasses); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($lengthClassesStart[0]) . ($lengthClassesStart[1] + $i), $lengthClasses[$i]['title'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		for ($i = 0; $i < count($weightClasses); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($weightClassesStart[0]) . ($weightClassesStart[1] + $i), $weightClasses[$i]['title'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		for ($i = 0; $i < count($manufacturers); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($manufacturersStart[0]) . ($manufacturersStart[1] + $i), $manufacturers[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		for ($i = 0; $i < count($categories); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($categoriesStart[0]) . ($categoriesStart[1] + $i), $categories[$i]['category_id'], PHPExcel_Cell_DataType::TYPE_STRING);
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($categoriesStart[0] + 1) . ($categoriesStart[1] + $i), $categories[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		
		if (version_compare(VERSION, '1.5.5', '>=')) {
			for ($i = 0; $i < count($filters); $i++) {
				$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($filtersStart[0]) . ($filtersStart[1] + $i), $filters[$i]['filter_id'], PHPExcel_Cell_DataType::TYPE_STRING);
				$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($filtersStart[0] + 1) . ($filtersStart[1] + $i), $filters[$i]['group'] . $this->productFilterSeparator . $filters[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
			}
		}
		
		if (version_compare(VERSION, '2.0', '>=')) {
			for ($i = 0; $i < count($recurrings); $i++) {
				$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($recurringsStart[0]) . ($recurringsStart[1] + $i), $recurrings[$i]['recurring_id'], PHPExcel_Cell_DataType::TYPE_STRING);
				$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($recurringsStart[0] + 1) . ($recurringsStart[1] + $i), $recurrings[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
			}
		}
		
		for ($i = 0; $i < count($stores); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($storesStart[0]) . ($storesStart[1] + $i), $stores[$i]['store_id'], PHPExcel_Cell_DataType::TYPE_STRING);
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($storesStart[0] + 1) . ($storesStart[1] + $i), $stores[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		for ($i = 0; $i < count($downloads); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($downloadsStart[0]) . ($downloadsStart[1] + $i), $downloads[$i]['download_id'], PHPExcel_Cell_DataType::TYPE_STRING);
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($downloadsStart[0] + 1) . ($downloadsStart[1] + $i), $downloads[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		for ($i = 0; $i < count($attributes); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($attributesStart[0]) . ($attributesStart[1] + $i), $attributes[$i]['attribute_group'] . $this->productAttributeSeparator . $attributes[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		for ($i = 0; $i < count($options); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($optionsStart[0]) . ($optionsStart[1] + $i), $options[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		for ($i = 0; $i < count($customerGroups); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($customerGroupsStart[0]) . ($customerGroupsStart[1] + $i), $customerGroups[$i]['customer_group_id'], PHPExcel_Cell_DataType::TYPE_STRING);
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($customerGroupsStart[0] + 1) . ($customerGroupsStart[1] + $i), $customerGroups[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		for ($i = 0; $i < count($layouts); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($layoutsStart[0]) . ($layoutsStart[1] + $i), $layouts[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
			
		$this->load->model('catalog/product');
		
		$extra_select = "";
		
		$this->db->query("SET SESSION group_concat_max_len = 1000000;");
		
		$products = $this->db->query($this->getQuery($export_filters, $store, $language) . " ORDER BY p.product_id LIMIT ". $progress['current'] . ", " . $productNumber);
		
		$productSheetObj = $objPHPExcel->setActiveSheetIndex($productsSheet);
		
		$dataValidationRanges = array(
			'attribute_name' => array('range' => ''),
			'option_required' => array('range' => ''),
			'option_type' => array('range' => ''),
			'option_subtract_strock' => array('range' => ''),
			'customer_group' => array('range' => ''),
			'design_layout' => array('range' => '', 'template_range' => '')
		);
		
		$cellToStyleRange = '';
		
		$productSheetObj->getDefaultStyle()->applyFromArray($styleMap[3]);
		$productSheetObj->getStyle('D')->applyFromArray($styleMap[4], false);
		$productSheetObj->getStyle('B')->applyFromArray($styleMap[0]);
		$productSheetObj->getStyle('F')->applyFromArray($styleMap[0]);
		$productSheetObj->getStyle('P')->applyFromArray($styleMap[0]);
		
		foreach ($this->extraGeneralFields['Products'] as $extra) {
			if (!empty($extra['title']) && !empty($extra['column_full'])) {
				$productSheetObj->setCellValueExplicit($extra['column_full'] . '1', $extra['title'], PHPExcel_Cell_DataType::TYPE_STRING);
			}
		}
		
		if ($products->num_rows > 0) {
			foreach ($products->rows as $myProductIndex => $row) {
				
				$this->getData('Products', $row);

				$row['description'] = $this->prepareExportHTMLFormatting($row['description'], $targetHTMLFormat);
				
				$productSheetObj->fromArray($leftColumnStaticText, null, 'A'.(($myProductIndex*$this->productSize)+2));
				$productSheetObj->getStyle('D'.(($myProductIndex*$this->productSize)+2))->applyFromArray($styleMap[5]);
				$productSheetObj->getStyle('A'.(($myProductIndex*$this->productSize)+3).':'.'B'.(($myProductIndex*$this->productSize)+3))->applyFromArray($styleMap[8]);
				$productSheetObj->getStyle('A'.(($myProductIndex*$this->productSize)+7).':'.'B'.(($myProductIndex*$this->productSize)+7))->applyFromArray($styleMap[8]);
				$productSheetObj->getStyle('A'.(($myProductIndex*$this->productSize)+24).':'.'B'.(($myProductIndex*$this->productSize)+24))->applyFromArray($styleMap[8]);
				
				// Prepare data
				foreach ($taxClasses as $taxClass) {
					if ($taxClass['tax_class_id'] == $row['tax_class_id']) { $row['tax_class'] = $taxClass['title']; break; }
				}
				if (empty($row['tax_class'])) $row['tax_class'] = $taxClasses[0]['title'];
				$row['subtract'] = empty($row['subtract']) ? 'No' : 'Yes';
				foreach ($stockStates as $stockStatus) {
					if ($stockStatus['stock_status_id'] == $row['stock_status_id']) { $row['stock_status'] = $stockStatus['name']; }
					//if ($stockStatus['stock_status_id'] == $this->config->get('config_stock_status_id')) { $defaultStockStatus = $stockStatus['name']; }	
				}
                $defaultStockStatus = '';
                if (empty($defaultStockStatus) && !empty($stockStates[0]['name'])) {
                    $defaultStockStatus = $stockStates[0]['name'];
                }
				if (empty($row['stock_status'])) $row['stock_status'] = $defaultStockStatus;
				$row['shipping'] = empty($row['shipping']) ? 'No' : 'Yes';
				$row['length_class'] = !empty($lengthClasses[0]['title']) ? $lengthClasses[0]['title'] : '';
				foreach ($lengthClasses as $lengthClass) {
					if ($lengthClass['length_class_id'] == $row['length_class_id']) { $row['length_class'] = $lengthClass['title']; break; }
				}
				$row['weight_class'] = !empty($weightClasses[0]['title']) ? $weightClasses[0]['title'] : '';
				foreach ($weightClasses as $weightClass) {
					if ($weightClass['weight_class_id'] == $row['weight_class_id']) { $row['weight_class'] = $weightClass['title']; break; }
				}
				$row['sort_order'] = empty($row['sort_order']) ? '0' : $row['sort_order'];
				$row['status'] = empty($row['status']) ? 'Disabled' : 'Enabled';
				foreach ($manufacturers as $manufacturer) {
					if ($manufacturer['manufacturer_id'] == $row['manufacturer_id']) { $row['manufacturer'] = $manufacturer['name']; break; }
				}
				if (empty($row['manufacturer'])) $row['manufacturer'] = $manufacturers[0]['name'];
				if (empty($row['filters'])) $row['filters'] = '';
				if (empty($row['recurrings'])) $row['recurrings'] = '';
				if (empty($row['ean'])) $row['ean'] = '';
				if (empty($row['jan'])) $row['jan'] = '';
				if (empty($row['isbn'])) $row['isbn'] = '';
				if (empty($row['mpn'])) $row['mpn'] = '';
				if (empty($row['name'])) $row['name'] = '-';
				$row['name'] = $this->entity_decode($row['name']);

				for ($i = 0; $i < count($merges); $i += 4) {
					$productSheetObj->mergeCells(PHPExcel_Cell::stringFromColumnIndex($target[0] + $merges[$i]) . ($target[1] + $merges[$i+1]) . ':' . PHPExcel_Cell::stringFromColumnIndex($target[0] + $merges[$i+2]) . ($target[1] + $merges[$i+3]));	
				}
				
				// Add data
				// Extras
				foreach ($extras as $name => $position) {
					$productSheetObj->setCellValueExplicit($position[0] . ($target[1] + $position[1]), empty($row[$name]) ? '' : $row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
				}
				// General
				foreach ($generals as $name => $position) {
					$productSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target[0] + $position[0]) . ($target[1] + $position[1]), empty($row[$name]) ? '' : $row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
				}
				// Data
				foreach ($datas as $name => $position) {
					$productSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target[0] + $position[0]) . ($target[1] + $position[1]), empty($row[$name]) && $row[$name] !== '0' ? '' : $row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
				}
				// Links
				foreach ($links as $name => $position) {
					$productSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target[0] + $position[0]) . ($target[1] + $position[1]), empty($row[$name]) && $row[$name] !== '0' ? '' : $row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
				}
				// Attributes
				$productAttributes = $this->model_catalog_product->getProductAttributes($row['product_id']);
				$i3 = $dynamicTemplates['attributes'][0];
				
				$attributesRange = array(PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['attributes'][1]), '');
				
				if (empty($dataValidationRanges['attribute_name']['root'])) {
					$dataValidationRanges['attribute_name']['root'] = PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['attributes'][1]);
				}
				
				foreach ($productAttributes as $productAttributeIndex => $productAttribute) {
					if (version_compare(VERSION, '1.5.5', '>=')) {
						$myProductAttribute = $this->model_catalog_attribute->getAttribute($productAttribute['attribute_id']);
						if (empty($myProductAttribute['name'])) continue;
						$productAttributes[$productAttributeIndex]['name'] = !empty($myProductAttribute['name']) ? $myProductAttribute['name'] : '';
						$productAttribute['name'] = $myProductAttribute['name'];
					}
					
					$productAttributeGroup = NULL;
					
					foreach ($attributeGroups as $attributeGroupIndex => $attributeGroup) {
						foreach ($attributes as $attributeIndex => $attribute) {
							if ($attributeGroup['attribute_group_id'] == $attribute['attribute_group_id'] && $attribute['attribute_id'] == $productAttribute['attribute_id']) {
								$productAttributeGroup = $attributeGroup['name'];
								break 2;
							}
						}
					}
					
					if (!empty($productAttributeGroup)) {
						$productAttribute['name'] = $productAttributeGroup . $this->productAttributeSeparator . $productAttribute['name'];
						
						for ($j = 0; $j <= $dynamicTemplates['attributes'][3] - $dynamicTemplates['attributes'][1]; $j++) {
							if (empty($productAttribute['product_attribute_description'][$language])) {
								continue 2;
							}
							
							if ($j == 0) {
								$productSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['attributes'][1] + $j), $productAttribute['name'], PHPExcel_Cell_DataType::TYPE_STRING);
								$attributesRange[1] =  PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['attributes'][1] + $j);
							} else {
								$productSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['attributes'][1] + $j), $productAttribute['product_attribute_description'][$language]['text'], PHPExcel_Cell_DataType::TYPE_STRING);	
							}
						}
						$i3++;
					}
				}
				
				if (!empty($attributesRange[1])) {
					if ($attributesRange[1] != $attributesRange[0]) {
						$dataValidationRanges['attribute_name']['range'] .= ' '.$attributesRange[0].':'.$attributesRange[1];
					} else {
						$dataValidationRanges['attribute_name']['range'] .= ' '.$attributesRange[0];
					}
				}
				
				// Options
				$productOptions = $this->model_catalog_product->getProductOptions($row['product_id']);
				$i3 = $dynamicTemplates['option_types'][0];
				$optionsRange = array(PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['option_types'][1]), '');
				if (empty($dataValidationRanges['option_required']['root'])) {
					$dataValidationRanges['option_required']['root'] = PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['option_types'][1]);
					$dataValidationRanges['option_type']['root'] = PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['option_types'][1] + 1);
					$dataValidationRanges['option_subtract_strock']['root'] = PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3+1) . ($target[1] + $dynamicTemplates['option_values'][1] + 2);
				}
				$optionsRequired = array();
				$optionsType = array();
				$optionsSubtract = array();
				
				foreach ($productOptions as $productOptionIndex => $productOption) {
					if (version_compare(VERSION, '1.5.5', '>=')) {
						$myProductOption = $this->model_catalog_option->getOption($productOption['option_id']);
						$productOptions[$productOptionIndex]['name'] = $myProductOption['name'];
						$productOption['name'] = $myProductOption['name'];
					}
					
					// Create the main column
					for ($j = 0; $j <= 1; $j++) {
						if ($j == 0) {
							$optionsRequired[] = PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['option_types'][1] + $j);
							
							$productSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['option_types'][1] + $j), empty($productOption['required']) ? 'Required: No' : 'Required: Yes', PHPExcel_Cell_DataType::TYPE_STRING);
						} else {
							$optionsType[] = PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['option_types'][1] + $j);
							$productSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['option_types'][1] + $j), $productOption['name'], PHPExcel_Cell_DataType::TYPE_STRING);
							$productSheetObj->mergeCells(PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['option_types'][1] + $j) . ':' . PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['option_types'][3]));
						}
					}
					$i3++;
					// Populate option values
					$optionDataFields = array();
					if (!empty($productOption['product_option_value']) && is_array($productOption['product_option_value'])) {
						foreach ($productOption['product_option_value'] as $product_option_value) {
							
							if (version_compare(VERSION, '1.5.5', '>=')) {
								$productOptionValue = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);
								if (!isset($productOptionValue['name']) || $productOptionValue['name'] === '') continue;
								$product_option_value['name'] = $productOptionValue['name'];
							}
							
							$optionDataFields[] = array(
								$product_option_value['name'],
								$product_option_value['quantity'],
								!empty($product_option_value['subtract']) ? 'Yes' : 'No',
								$product_option_value['price_prefix'] . $product_option_value['price'],
								$product_option_value['points_prefix'] . $product_option_value['points'],
								$product_option_value['weight_prefix'] . $product_option_value['weight']
							);
						}
					} else if (!empty($productOption['value'])) {
						$optionDataFields[] = array(
							$productOption['value'],
							'',
							'',
							'',
							'',
							''
						);
					}
					
					$optionsRange[1] = PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['option_types'][1]);
					
					if (!empty($optionDataFields)) {
						foreach ($optionDataFields as $optionDataField) {
							for ($j = 0; $j < count($optionDataField); $j++) {
								if ($j == 2) {
									$optionsSubtract[] = PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['option_values'][1] + $j);
									
									$productSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['option_values'][1] + $j), $optionDataField[$j], PHPExcel_Cell_DataType::TYPE_STRING);
								} else {
									$productSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['option_values'][1] + $j), $optionDataField[$j], PHPExcel_Cell_DataType::TYPE_STRING);	
								}
							}
							$i3++;
						}
					}
				}
				
				if (!empty($optionsRequired)) {
					$dataValidationRanges['option_required']['range'] .= ' '.implode(' ',$optionsRequired);
				}
				if (!empty($optionsType)) {
					$dataValidationRanges['option_type']['range'] .= ' '.implode(' ',$optionsType);
				}
				if (!empty($optionsSubtract)) {
					$dataValidationRanges['option_subtract_strock']['range'] .= ' '.implode(' ',$optionsSubtract);
				}
				
				// Discount
				$productDiscounts = $this->model_catalog_product->getProductDiscounts($row['product_id']);
				$i3 = $dynamicTemplates['discounts'][0];
				
				$customerGroupRange = array(PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['discounts'][1]), '');
				
				if (empty($dataValidationRanges['customer_group']['root'])) {
					$dataValidationRanges['customer_group']['root'] = PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['discounts'][1]);
				}
				
				$discountJMap = array(
					0 => 'customer_group',
					1 => 'priority',
					2 => 'price',
					3 => 'date_start',
					4 => 'date_end',
					5 => 'quantity'
				);
				foreach ($productDiscounts as $productDiscount) {
					$found = false;
					foreach ($customerGroups as $customerGroup) {
						if ($customerGroup['customer_group_id'] == $productDiscount['customer_group_id']) { $found = true; $productDiscount['customer_group'] = $customerGroup['name']; break; }
					}
					if ($found) {
						for ($j = 0; $j <= $dynamicTemplates['discounts'][3] - $dynamicTemplates['discounts'][1]; $j++) {
							$productSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['discounts'][1] + $j), $productDiscount[$discountJMap[$j]], PHPExcel_Cell_DataType::TYPE_STRING);
							
							if ($j == 0) {
								$customerGroupRange[1] = PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['discounts'][1] + $j);
							}
							
						}
						$i3++;
					}
				}
				
				if (!empty($customerGroupRange[1])) {
					if ($customerGroupRange[1] != $customerGroupRange[0]) {
						$dataValidationRanges['customer_group']['range'] .= ' '.$customerGroupRange[0].':'.$customerGroupRange[1];
					} else {
						$dataValidationRanges['customer_group']['range'] .= ' '.$customerGroupRange[0];
					}
				}
				
				// Special
				$productSpecials = $this->model_catalog_product->getProductSpecials($row['product_id']);
				$i3 = $dynamicTemplates['specials'][0];
				
				$customerGroupRange = array(PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['specials'][1]), '');
				
				if (empty($dataValidationRanges['customer_group']['root'])) {
					$dataValidationRanges['customer_group']['root'] = PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['specials'][1]);
				}
				
				$specialJMap = array(
					0 => 'customer_group',
					1 => 'priority',
					2 => 'price',
					3 => 'date_start',
					4 => 'date_end'
				);
				foreach ($productSpecials as $productSpecial) {
					$found = false;
					foreach ($customerGroups as $customerGroup) {
						if ($customerGroup['customer_group_id'] == $productSpecial['customer_group_id']) { $found = true; $productSpecial['customer_group'] = $customerGroup['name']; break; }
					}
					if ($found) {
						for ($j = 0; $j <= $dynamicTemplates['specials'][3] - $dynamicTemplates['specials'][1]; $j++) {
							$productSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['specials'][1] + $j), $productSpecial[$specialJMap[$j]], PHPExcel_Cell_DataType::TYPE_STRING);
							
							if ($j == 0) {
								$customerGroupRange[1] = PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['specials'][1] + $j);
							}
						}
						$i3++;
					}
				}
				
				if (!empty($customerGroupRange[1])) {
					if ($customerGroupRange[1] != $customerGroupRange[0]) {
						$dataValidationRanges['customer_group']['range'] .= ' '.$customerGroupRange[0].':'.$customerGroupRange[1];
					} else {
						$dataValidationRanges['customer_group']['range'] .= ' '.$customerGroupRange[0];
					}
				}
				
				// Image
				$productImages = $this->model_catalog_product->getProductImages($row['product_id']);
				$i3 = $dynamicTemplates['images'][0];
				
				$imagePathRange = array(PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['images'][1]), '');
				
				$imageJMap = array(
					0 => 'image',
					1 => 'sort_order'
				);
				foreach ($productImages as $productImage) {
					$imagePathRange[1] = PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['images'][1]);
					for ($j = 0; $j <= $dynamicTemplates['images'][3] - $dynamicTemplates['images'][1]; $j++) {
						$productSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['images'][1] + $j), $productImage[$imageJMap[$j]], PHPExcel_Cell_DataType::TYPE_STRING);
					}
					$i3++;
				}
				
				// Reward Points
				foreach ($rewards as $name => $position) {
					$productSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target[0] + $position[0]) . ($target[1] + $position[1]), empty($row[$name]) && $row[$name] !== '0' ? '' : $row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
				}
				$productRewards = $this->model_catalog_product->getProductRewards($row['product_id']);
				
				$i3 = $dynamicTemplates['reward_points'][0];
				
				$rewardJMap = array(
					0 => 'customer_group',
					1 => 'points'
				);
				foreach ($productRewards as $customer_group_id => $productReward) {
					$found = false;
					foreach ($customerGroups as $customerGroup) {
						if ($customerGroup['customer_group_id'] == $customer_group_id) { $found = true; $productReward['customer_group'] = $customerGroup['name']; break; }
					}
					if ($found) {
						for ($j = 0; $j <= $dynamicTemplates['reward_points'][3] - $dynamicTemplates['reward_points'][1]; $j++) {
							$productSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['reward_points'][1] + $j), $productReward[$rewardJMap[$j]], PHPExcel_Cell_DataType::TYPE_STRING);
							if ($j == 0) {
								if (($i3 - $dynamicTemplates['reward_points'][0]) == 1) {
									$customerGroupRange = array(PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['reward_points'][1] + $j), '');
					
									if (empty($dataValidationRanges['customer_group']['root'])) {
										$dataValidationRanges['customer_group']['root'] = PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['reward_points'][1] + $j);
									}
								}
								
								if (($i3 - $dynamicTemplates['reward_points'][0]) > 0) {
									$customerGroupRange[1] = PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['reward_points'][1] + $j);
								}
							}
						}
						$i3++;
					}
				}
				
				if (!empty($customerGroupRange[1])) {
					if ($customerGroupRange[1] != $customerGroupRange[0]) {
						$dataValidationRanges['customer_group']['range'] .= ' '.$customerGroupRange[0].':'.$customerGroupRange[1];
					} else {
						$dataValidationRanges['customer_group']['range'] .= ' '.$customerGroupRange[0];
					}
				}
				
				// Design
				$productLayouts = $this->model_catalog_product->getProductLayouts($row['product_id']);
				$i3 = $dynamicTemplates['designs'][0];
				
				$designLayoutRange = array();
				$designLayoutTemplateRange = !empty($designLayoutTemplateRange) ? $designLayoutTemplateRange : array();
				$templateSet = false;
				
				foreach ($stores as $store) {
					for ($j = 0; $j <= $dynamicTemplates['designs'][3] - $dynamicTemplates['designs'][1]; $j++) {
						if ($j == 0) {
							$productSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['designs'][1] + $j), $store['name'], PHPExcel_Cell_DataType::TYPE_STRING);
							if (!$templateSet) {
								$templateSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($template[0] + $i3) . ($template[1] + $dynamicTemplates['designs'][1] + $j), $store['name'], PHPExcel_Cell_DataType::TYPE_STRING);
								$templateSet = true;
							}
						} else if ($j == 1) {
							
							if (empty($designLayoutRange)) {
								$designLayoutRange = array(PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['designs'][1] + $j), '');
							}
							
							if (empty($designLayoutTemplateRange)) {
								$designLayoutTemplateRange = array(PHPExcel_Cell::stringFromColumnIndex($template[0] + $i3) . ($template[1] + $dynamicTemplates['designs'][1] + $j), '');
							}
				
							if (empty($dataValidationRanges['design_layout']['root'])) {
								$dataValidationRanges['design_layout']['root'] = PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['designs'][1]);
							}
							
							if (!empty($productLayouts[$store['store_id']])) {
								$layout_id = $productLayouts[$store['store_id']];
								$productLayout = '';
								foreach ($layouts as $layout) {
									if ($layout['layout_id'] == $layout_id) { $productLayout = $layout['name']; break; }
								}
								$productSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['designs'][1] + $j), $productLayout, PHPExcel_Cell_DataType::TYPE_STRING);
							} else {
								$productSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['designs'][1] + $j), '', PHPExcel_Cell_DataType::TYPE_STRING);
							}
							$designLayoutRange[1] = PHPExcel_Cell::stringFromColumnIndex($target[0] + $i3) . ($target[1] + $dynamicTemplates['designs'][1] + $j);
							if (empty($designLayoutTemplateRange[1])) {
								$designLayoutTemplateRange[1] = PHPExcel_Cell::stringFromColumnIndex($template[0] + $i3) . ($template[1] + $dynamicTemplates['designs'][1] + $j);
							}
						}
					}
					$i3++;	
				}
				
				if (!empty($designLayoutRange[1])) {
					if ($designLayoutRange[1] != $designLayoutRange[0]) {
						$dataValidationRanges['design_layout']['range'] .= ' '.implode(':', $designLayoutRange);
					} else {
						$dataValidationRanges['design_layout']['range'] .= ' '.$designLayoutRange[0];
					}
				}
				
				if (!empty($designLayoutTemplateRange[1])) {
					if ($designLayoutTemplateRange[1] != $designLayoutTemplateRange[0]) {
						$dataValidationRanges['design_layout']['template_range'] .= ' '.implode(':', $designLayoutTemplateRange);
					} else {
						$dataValidationRanges['design_layout']['template_range'] .= ' '.$designLayoutTemplateRange[0];
					}
				}
				
				// Data validations
				
				foreach ($dataValidations as $dataValidationIndex => $dataValidation) {
					if (isset($dataValidations[$dataValidationIndex]['count']) && $dataValidations[$dataValidationIndex]['count'] == 0) continue;
					$dataValidations[$dataValidationIndex]['range'] .= ' '.PHPExcel_Cell::stringFromColumnIndex($target[0] + $dataValidation['field'][0]) . ($target[1] + $dataValidation['field'][1]);
					if (empty($dataValidations[$dataValidationIndex]['root'])) $dataValidations[$dataValidationIndex]['root'] = PHPExcel_Cell::stringFromColumnIndex($target[0] + $dataValidation['field'][0]) . ($target[1] + $dataValidation['field'][1]);
				}
				
				//Collapse
				for ($i = $target[1] + 1; $i <= $target[1] + $source[3] - $source[1]; $i++) { 
					$productSheetObj->getRowDimension($i)->setOutlineLevel(1);
					$productSheetObj->getRowDimension($i)->setVisible(false);
				}
				$productSheetObj->getRowDimension($i)->setCollapsed(true);
				$target[1] = $target[1] + ($source[3] - $source[1] + 1);
				$progress['current']++;
				$progress['memory_get_usage'] = round(memory_get_usage(true)/(1024*1024));
				$progress['percent'] = 100 / ($products->num_rows / $progress['current']);
				
				$this->setProgress($progress);
			}
			
			for ($i = $target[0]; $i <= $target[0] + $source[2] - $source[0]; $i++) {
				$productSheetObj->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($i))->setWidth($widths[$i]);
			}
			
			//Apply data validation for:
			// Generals
			foreach ($dataValidations as $dataValidation) {
				$range = trim($dataValidation['range']);
				if (isset($dataValidation['count']) && $dataValidation['count'] == 0) continue;
				if ($dataValidation['type'] == 'list' && !empty($dataValidation['root']) && !empty($range)) {
					$objValidation = $productSheetObj->getCell($dataValidation['root'])->getDataValidation();
					$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
					$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
					$objValidation->setAllowBlank(false);
					$objValidation->setShowInputMessage(true);
					$objValidation->setShowErrorMessage(true);
					$objValidation->setShowDropDown(true);
					$objValidation->setErrorTitle('Input error');
					$objValidation->setError('Value is not in list.');
					$objValidation->setPromptTitle('Pick from list');
					$objValidation->setPrompt('Please pick a value from the drop-down list.');
					$objValidation->setFormula1($metaSheetObj->getTitle() . '!$' . PHPExcel_Cell::stringFromColumnIndex($dataValidation['data'][0]) . '$' . ($dataValidation['data'][1]) . ':$' . PHPExcel_Cell::stringFromColumnIndex($dataValidation['data'][2]) . '$' . ($dataValidation['data'][3]));
					$productSheetObj->setDataValidation(trim($dataValidation['range']), $objValidation);
					
					$templateSheetObj->setDataValidation(PHPExcel_Cell::stringFromColumnIndex($template[0] + $dataValidation['field'][0]) . ($template[1] + $dataValidation['field'][1]), $objValidation);
				}
			}
			
			//Attributes
			$range = trim($dataValidationRanges['attribute_name']['range']);
			if (count($attributes) > 0 && !empty($range) && !empty($dataValidationRanges['attribute_name']['root'])) {
				$objValidation = $productSheetObj->getCell($dataValidationRanges['attribute_name']['root'])->getDataValidation();
				$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
				$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
				$objValidation->setAllowBlank(false);
				$objValidation->setShowInputMessage(true);
				$objValidation->setShowErrorMessage(true);
				$objValidation->setShowDropDown(true);
				$objValidation->setErrorTitle('Input error');
				$objValidation->setError('Value is not in list.');
				$objValidation->setPromptTitle('Pick from list');
				$objValidation->setPrompt('Please pick a value from the drop-down list.');
				$objValidation->setFormula1($metaSheetObj->getTitle() . '!$' . PHPExcel_Cell::stringFromColumnIndex($attributesStart[0]) . '$' . ($attributesStart[1]) . ':$' . PHPExcel_Cell::stringFromColumnIndex($attributesStart[0]) . '$' . ($attributesStart[1] + count($attributes) - 1));
				$productSheetObj->setDataValidation(trim($dataValidationRanges['attribute_name']['range']), $objValidation);
			}
			
			//Options
			//required
			$range = trim($dataValidationRanges['option_required']['range']);
			if (count($options) > 0 && !empty($range) && !empty($dataValidationRanges['option_required']['root'])) {
				$objValidation = $productSheetObj->getCell($dataValidationRanges['option_required']['root'])->getDataValidation();
				$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
				$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
				$objValidation->setAllowBlank(false);
				$objValidation->setShowInputMessage(true);
				$objValidation->setShowErrorMessage(true);
				$objValidation->setShowDropDown(true);
				$objValidation->setErrorTitle('Input error');
				$objValidation->setError('Value is not in list.');
				$objValidation->setPromptTitle('Pick from list');
				$objValidation->setPrompt('Please pick a value from the drop-down list.');
				$objValidation->setFormula1($metaSheetObj->getTitle() . '!$' . PHPExcel_Cell::stringFromColumnIndex($requiredCoordinates[0]) . '$' . ($requiredCoordinates[1]) . ':$' . PHPExcel_Cell::stringFromColumnIndex($requiredCoordinates[2]) . '$' . ($requiredCoordinates[3]));
				$productSheetObj->setDataValidation(trim($dataValidationRanges['option_required']['range']), $objValidation);
				
				//type
				$objValidation = $productSheetObj->getCell($dataValidationRanges['option_type']['root'])->getDataValidation();
				$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
				$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
				$objValidation->setAllowBlank(false);
				$objValidation->setShowInputMessage(true);
				$objValidation->setShowErrorMessage(true);
				$objValidation->setShowDropDown(true);
				$objValidation->setErrorTitle('Input error');
				$objValidation->setError('Value is not in list.');
				$objValidation->setPromptTitle('Pick from list');
				$objValidation->setPrompt('Please pick a value from the drop-down list.');
				$objValidation->setFormula1($metaSheetObj->getTitle() . '!$' . PHPExcel_Cell::stringFromColumnIndex($optionsStart[0]) . '$' . ($optionsStart[1]) . ':$' . PHPExcel_Cell::stringFromColumnIndex($optionsStart[0]) . '$' . ($optionsStart[1] + count($options) - 1));
				$productSheetObj->setDataValidation(trim($dataValidationRanges['option_type']['range']), $objValidation);
				
				//sutract stock
				$objValidation = $productSheetObj->getCell($dataValidationRanges['option_subtract_strock']['root'])->getDataValidation();
				$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
				$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
				$objValidation->setAllowBlank(true);
				$objValidation->setShowInputMessage(true);
				$objValidation->setShowErrorMessage(true);
				$objValidation->setShowDropDown(true);
				$objValidation->setErrorTitle('Input error');
				$objValidation->setError('Value is not in list.');
				$objValidation->setPromptTitle('Pick from list');
				$objValidation->setPrompt('Please pick a value from the drop-down list.');
				$objValidation->setFormula1($metaSheetObj->getTitle() . '!$' . PHPExcel_Cell::stringFromColumnIndex(1) . '$' . (2) . ':$' . PHPExcel_Cell::stringFromColumnIndex(1) . '$' . (3));
				$productSheetObj->setDataValidation(trim($dataValidationRanges['option_subtract_strock']['range']), $objValidation);
			}
			
			//Customer groups
			if (!empty($dataValidationRanges['customer_group']['range']) && !empty($dataValidationRanges['customer_group']['root'])) {
				$objValidation = $productSheetObj->getCell($dataValidationRanges['customer_group']['root'])->getDataValidation();
				$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
				$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
				$objValidation->setAllowBlank(false);
				$objValidation->setShowInputMessage(true);
				$objValidation->setShowErrorMessage(true);
				$objValidation->setShowDropDown(true);
				$objValidation->setErrorTitle('Input error');
				$objValidation->setError('Value is not in list.');
				$objValidation->setPromptTitle('Pick from list');
				$objValidation->setPrompt('Please pick a value from the drop-down list.');
				$objValidation->setFormula1($metaSheetObj->getTitle() . '!$' . PHPExcel_Cell::stringFromColumnIndex($customerGroupsStart[0] + 1) . '$' . ($customerGroupsStart[1]) . ':$' . PHPExcel_Cell::stringFromColumnIndex($customerGroupsStart[0] + 1) . '$' . ($customerGroupsStart[1] + count($customerGroups) - 1));
				$productSheetObj->setDataValidation(trim($dataValidationRanges['customer_group']['range']), $objValidation);
			}
			
			//Design -> Layout
			$range = trim($dataValidationRanges['design_layout']['range']);
			if (count($layouts) && !empty($range) && !empty($dataValidationRanges['design_layout']['root'])) {
				$objValidation = $productSheetObj->getCell($dataValidationRanges['design_layout']['root'])->getDataValidation();
				$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
				$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
				$objValidation->setAllowBlank(false);
				$objValidation->setShowInputMessage(true);
				$objValidation->setShowErrorMessage(true);
				$objValidation->setShowDropDown(true);
				$objValidation->setErrorTitle('Input error');
				$objValidation->setError('Value is not in list.');
				$objValidation->setPromptTitle('Pick from list');
				$objValidation->setPrompt('Please pick a value from the drop-down list.');
				$objValidation->setFormula1($metaSheetObj->getTitle() . '!$' . PHPExcel_Cell::stringFromColumnIndex($layoutsStart[0]) . '$' . ($layoutsStart[1]) . ':$' . PHPExcel_Cell::stringFromColumnIndex($layoutsStart[0]) . '$' . ($layoutsStart[1] + count($layouts) - 1));
				$productSheetObj->setDataValidation(trim($dataValidationRanges['design_layout']['range']), $objValidation);
				$templateSheetObj->setDataValidation(trim($dataValidationRanges['design_layout']['template_range']), $objValidation);
			}
			
			unset($objValidation);
		} else {
			$progress['done'] = true;
		}
		
		$this->config->set('config_language_id', $default_language);
		
		$this->session->data['generated_file'] = $result;
		$this->session->data['generated_files'][] = $resultName;
		$this->setProgress($progress);
		
		try {
			$this->custom_set_time_limit();
			
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->setPreCalculateFormulas(false);
			
			$objWriter->save($result);
			
			$progress['done'] = true;
		} catch (Exception $e) {
			$progress['message'] = $e->getMessage();
			$progress['error'] = true;
			$progress['done'] = false;
			$this->setProgress($progress);
		}
		$objPHPExcel->disconnectWorksheets();
		unset($metaSheetObj);
		unset($objWriter);
		unset($productSheetObj);
		unset($objPHPExcel);
		
		
		$progress['done'] = true;
		$this->setProgress($progress);
		
		return true;
	}
	
	public function exportXLSProductsLight($language, $store, $destinationFolder = '', $productNumber, $export_filters = array(), $targetHTMLFormat) {
		$this->language->load('extension/module/excelport');
		$this->folderCheck($destinationFolder);
		
		$progress = $this->getProgress();
		$progress['done'] = false;
		
		$file = IMODULE_ROOT . 'vendors/excelport/template_product_light.xlsx';
		
		$default_language = $this->config->get('config_language_id');
		$this->config->set('config_language_id', $language);
		require_once(IMODULE_ROOT.'vendors/phpexcel/PHPExcel.php');
		
		if (!empty($progress['populateAll'])) {
			$all = $this->db->query($this->getQuery($export_filters, $store, $language, true));
			$progress['all'] = $all->num_rows ? (int)$all->row['count'] : 0;
			unset($progress['populateAll']);
			$this->setProgress($progress);
		}
		
		$this->setData('Products', $destinationFolder, $language);
		
		$productsSheet = 0;
		$metaSheet = 1;
		
		$taxClassesStart = array(0,2);
		$this->load->model('localisation/tax_class');
		$taxClasses = array_merge(array(0 => array('tax_class_id' => 0, 'title' => '--- None ---', 'description' => '--- None ---', 'date_added' => '0000-00-00 00:00:00', 'date_modified' => '0000-00-00 00:00:00')), $this->model_localisation_tax_class->getTaxClasses());
		
		$stockStatesStart = array(2,2);
		$this->load->model('localisation/stock_status');
		$stockStates = $this->model_localisation_stock_status->getStockStatuses();
		
		$lengthClassesStart = array(3,2);
		$this->load->model('localisation/length_class');
		$lengthClasses = $this->model_localisation_length_class->getLengthClasses();
		
		$weightClassesStart = array(4,2);
		$this->load->model('localisation/weight_class');
		$weightClasses = $this->model_localisation_weight_class->getWeightClasses();
		
		$manufacturersStart = array(6,2);
		$this->load->model('catalog/manufacturer');
		$manufacturers = array_merge(array(0 => array('manufacturer_id' => 0, 'name' => '--- None ---', 'image' => NULL, 'sort_order' => 0)), $this->model_catalog_manufacturer->getManufacturers());
		
		$categoriesStart = array(7,3);
		$this->load->model('catalog/category');
		
		if (version_compare(VERSION, '1.5.5', '>=')) {
			$categories = $this->model_catalog_category->getCategories(array());
		} 
		
		if (version_compare(VERSION, '1.5.5', '<')) {
			$categories = $this->model_catalog_category->getCategories();
		} 
		
		if (version_compare(VERSION, '1.5.5', '>=')) {
			$filtersStart = array(13,3);
			$this->load->model('catalog/filter');
			$filters = $this->model_catalog_filter->getFilters(array());
		} 
		
		$storesStart = array(9,3);
		$this->load->model('setting/store');
		$stores = array_merge(array(0 => array('store_id' => 0, 'name' => 'Default', 'url' => NULL, 'ssl' => NULL)),$this->model_setting_store->getStores());
		
		$downloadsStart = array(11,3);
		$this->load->model('catalog/download');
		$downloads = $this->model_catalog_download->getDownloads();
		
		$generals = array(
			'product_id' 		=> 0,
			'name'				=> 1,
			'meta_description'	=> 5,
			'meta_keyword'		=> 6,
			'description'		=> 4,
			'tag'				=> 7,
			'model' 			=> 2,
			'sku'				=> 3,
			'upc'				=> 8,
			'ean'				=> 9,
			'jan'				=> 10,
			'isbn'				=> 11,
			'mpn'				=> 12,
			'location'			=> 14,
			'price'				=> 13,
			'tax_class'	 		=> 16,
			'quantity'			=> 17,
			'minimum'			=> 18,
			'subtract'			=> 20,
			'stock_status' 		=> 21,
			'shipping'			=> 22,
			'keyword'			=> 23,
			'image'				=> 19,
			'date_available'	=> 24,
			'length'			=> 25,
			'width'				=> 26,
			'height'			=> 27,
			'length_class'		=> 28,
			'weight'			=> 29,
			'weight_class'		=> 30,
			'status'			=> 15,
			'sort_order'		=> 31,
			'points'			=> 32,
			'manufacturer'		=> 33,
			'categories'		=> 34,
			'filters'			=> 35,
			'stores'			=> 36,
			'downloads'			=> 37,
			'related'			=> 38,
			'meta_title'			=> 39
		);
		
		// Extra fields
		$extras = array();
		foreach ($this->extraGeneralFields['Products'] as $extra) {
			if (!empty($extra['name']) && !empty($extra['column_light'])) {
				$extras[$extra['name']] = $extra['column_light'];
			}
		}
		
		$dataValidations = array(
			array(
				'type' => 'list',
				'field' => $generals['tax_class'],
				'data' => array($taxClassesStart[0], $taxClassesStart[1], $taxClassesStart[0], $taxClassesStart[1] + count($taxClasses) - 1),
				'range' => '',
				'count' => count($taxClasses)
			),
			array(
				'type' => 'list',
				'field' => $generals['subtract'],
				'data' => array(1,2,1,3),
				'range' => ''
			),
			array(
				'type' => 'list',
				'field' => $generals['stock_status'],
				'data' => array($stockStatesStart[0], $stockStatesStart[1], $stockStatesStart[0], $stockStatesStart[1] + count($stockStates) - 1),
				'range' => '',
				'count' => count($stockStates)
			),
			array(
				'type' => 'list',
				'field' => $generals['shipping'],
				'data' => array(1,2,1,3),
				'range' => ''
			),
			array(
				'type' => 'list',
				'field' => $generals['length_class'],
				'data' => array($lengthClassesStart[0], $lengthClassesStart[1], $lengthClassesStart[0], $lengthClassesStart[1] + count($lengthClasses) - 1),
				'range' => '',
				'count' => count($lengthClasses)
			),
			array(
				'type' => 'list',
				'field' => $generals['weight_class'],
				'data' => array($weightClassesStart[0], $weightClassesStart[1], $weightClassesStart[0], $weightClassesStart[1] + count($weightClasses) - 1),
				'range' => '',
				'count' => count($weightClasses)
			),
			array(
				'type' => 'list',
				'field' => $generals['status'],
				'data' => array(5,2,5,3),
				'range' => ''
			),
			array(
				'type' => 'list',
				'field' => $generals['manufacturer'],
				'data' => array($manufacturersStart[0], $manufacturersStart[1], $manufacturersStart[0], $manufacturersStart[1] + count($manufacturers) - 1),
				'range' => '',
				'count' => count($manufacturers)
			)
		);
		
		$target = array(0,2);
		
		$this->load->model('localisation/language');
		$languageQuery = $this->model_localisation_language->getLanguage($this->config->get('config_language_id'));
		
		$name = 'products_excelport_light_' . $languageQuery['code'] . '_' . str_replace('/', '_', substr(HTTP_CATALOG, 7, strlen(HTTP_CATALOG) - 8)) . '_' . date("Y-m-d_H-i-s") . '_' . $progress['current'];
		$resultName = $name . '.xlsx';
		$result = $destinationFolder . '/' . $name . '.xlsx';

		$objPHPExcel = PHPExcel_IOFactory::load($file);
		
		// Set document properties
		$objPHPExcel->getProperties()
					->setCreator($this->user->getUserName())
					->setLastModifiedBy($this->user->getUserName())
					->setTitle($name)
					->setSubject($name)
					->setDescription("Backup for Office 2007 and later, generated using PHPExcel and ExcelPort.")
					->setKeywords("office 2007 2010 2013 xlsx openxml php phpexcel excelport")
					->setCategory("Backup");
		
		$objPHPExcel->getDefaultStyle()->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		
		$metaSheetObj = $objPHPExcel->setActiveSheetIndex($metaSheet);
		
		for ($i = 0; $i < count($taxClasses); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($taxClassesStart[0]) . ($taxClassesStart[1] + $i), $taxClasses[$i]['title'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		for ($i = 0; $i < count($stockStates); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($stockStatesStart[0]) . ($stockStatesStart[1] + $i), $stockStates[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		for ($i = 0; $i < count($lengthClasses); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($lengthClassesStart[0]) . ($lengthClassesStart[1] + $i), $lengthClasses[$i]['title'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		for ($i = 0; $i < count($weightClasses); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($weightClassesStart[0]) . ($weightClassesStart[1] + $i), $weightClasses[$i]['title'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		for ($i = 0; $i < count($manufacturers); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($manufacturersStart[0]) . ($manufacturersStart[1] + $i), $manufacturers[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		for ($i = 0; $i < count($categories); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($categoriesStart[0]) . ($categoriesStart[1] + $i), $categories[$i]['category_id'], PHPExcel_Cell_DataType::TYPE_STRING);
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($categoriesStart[0] + 1) . ($categoriesStart[1] + $i), $categories[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		if (version_compare(VERSION, '1.5.5', '>=')) {
			for ($i = 0; $i < count($filters); $i++) {
				$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($filtersStart[0]) . ($filtersStart[1] + $i), $filters[$i]['filter_id'], PHPExcel_Cell_DataType::TYPE_STRING);
				$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($filtersStart[0] + 1) . ($filtersStart[1] + $i), $filters[$i]['group'] . $this->productFilterSeparator . $filters[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
			}
		} 
		for ($i = 0; $i < count($stores); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($storesStart[0]) . ($storesStart[1] + $i), $stores[$i]['store_id'], PHPExcel_Cell_DataType::TYPE_STRING);
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($storesStart[0] + 1) . ($storesStart[1] + $i), $stores[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		for ($i = 0; $i < count($downloads); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($downloadsStart[0]) . ($downloadsStart[1] + $i), $downloads[$i]['download_id'], PHPExcel_Cell_DataType::TYPE_STRING);
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($downloadsStart[0] + 1) . ($downloadsStart[1] + $i), $downloads[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		
		$this->load->model('catalog/product');
		
		$extra_select = "";
		
		$this->db->query("SET SESSION group_concat_max_len = 1000000;");
		
		$products = $this->db->query($this->getQuery($export_filters, $store, $language) . " ORDER BY p.product_id LIMIT ". $progress['current'] . ", " . $productNumber);
		
		$productSheetObj = $objPHPExcel->setActiveSheetIndex($productsSheet);
		
		foreach ($this->extraGeneralFields['Products'] as $extra) {
			if (!empty($extra['title']) && !empty($extra['column_light'])) {
				$productSheetObj->setCellValueExplicit($extra['column_light'] . '1', $extra['title'], PHPExcel_Cell_DataType::TYPE_STRING);
			}
		}
		
		if ($products->num_rows > 0) {
			foreach ($products->rows as $myProductIndex => $row) {
				
				$this->getData('Products', $row);

				$row['description'] = $this->prepareExportHTMLFormatting($row['description'], $targetHTMLFormat);
				
				// Prepare data
				foreach ($taxClasses as $taxClass) {
					if ($taxClass['tax_class_id'] == $row['tax_class_id']) { $row['tax_class'] = $taxClass['title']; break; }
				}
				if (empty($row['tax_class'])) $row['tax_class'] = $taxClasses[0]['title'];
				$row['subtract'] = empty($row['subtract']) ? 'No' : 'Yes';
				foreach ($stockStates as $stockStatus) {
					if ($stockStatus['stock_status_id'] == $row['stock_status_id']) { $row['stock_status'] = $stockStatus['name']; }
					//if ($stockStatus['stock_status_id'] == $this->config->get('config_stock_status_id')) { $defaultStockStatus = $stockStatus['name']; }	
				}
                $defaultStockStatus = '';
                if (empty($defaultStockStatus) && !empty($stockStates[0]['name'])) {
                    $defaultStockStatus = $stockStates[0]['name'];
                }
				if (empty($row['stock_status'])) $row['stock_status'] = $defaultStockStatus;
				$row['shipping'] = empty($row['shipping']) ? 'No' : 'Yes';
				$row['length_class'] = !empty($lengthClasses[0]['title']) ? $lengthClasses[0]['title'] : '';
				foreach ($lengthClasses as $lengthClass) {
					if ($lengthClass['length_class_id'] == $row['length_class_id']) { $row['length_class'] = $lengthClass['title']; break; }
				}
				$row['weight_class'] = !empty($weightClasses[0]['title']) ? $weightClasses[0]['title'] : '';
				foreach ($weightClasses as $weightClass) {
					if ($weightClass['weight_class_id'] == $row['weight_class_id']) { $row['weight_class'] = $weightClass['title']; break; }
				}
				$row['sort_order'] = empty($row['sort_order']) ? '0' : $row['sort_order'];
				$row['status'] = empty($row['status']) ? 'Disabled' : 'Enabled';
				foreach ($manufacturers as $manufacturer) {
					if ($manufacturer['manufacturer_id'] == $row['manufacturer_id']) { $row['manufacturer'] = $manufacturer['name']; break; }
				}
				if (empty($row['manufacturer'])) $row['manufacturer'] = $manufacturers[0]['name'];
				if (empty($row['filters'])) $row['filters'] = '';
				if (empty($row['ean'])) $row['ean'] = '';
				if (empty($row['jan'])) $row['jan'] = '';
				if (empty($row['isbn'])) $row['isbn'] = '';
				if (empty($row['mpn'])) $row['mpn'] = '';
				if (empty($row['name'])) $row['name'] = '-';
				$row['name'] = $this->entity_decode($row['name']);
				
				// Add data
				// Extras
				foreach ($extras as $name => $position) {
					$productSheetObj->setCellValueExplicit($position . ($target[1]), empty($row[$name]) ? '' : $row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
				}
				// General
				foreach ($generals as $name => $position) {
					$productSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target[0] + $position) . ($target[1]), empty($row[$name]) && $row[$name] !== '0' ? '' : $row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
				}
				
				// Data validations
				foreach ($dataValidations as $dataValidationIndex => $dataValidation) {
					if (isset($dataValidations[$dataValidationIndex]['count']) && $dataValidations[$dataValidationIndex]['count'] == 0) continue;
					$dataValidations[$dataValidationIndex]['range'] = PHPExcel_Cell::stringFromColumnIndex($target[0] + $dataValidation['field']) . ($target[1]);
					if (empty($dataValidations[$dataValidationIndex]['root'])) $dataValidations[$dataValidationIndex]['root'] = PHPExcel_Cell::stringFromColumnIndex($target[0] + $dataValidation['field']) . ($target[1]);
				}
				
				$target[1] = $target[1] + 1;
				$progress['current']++;
				$progress['memory_get_usage'] = round(memory_get_usage(true)/(1024*1024));
				$progress['percent'] = 100 / ($products->num_rows / $progress['current']);
				
				$this->setProgress($progress);
			}
			
			foreach ($dataValidations as $dataValidationIndex => $dataValidation) {
				if (isset($dataValidations[$dataValidationIndex]['count']) && $dataValidations[$dataValidationIndex]['count'] == 0) continue;
				if ($dataValidations[$dataValidationIndex]['range'] != $dataValidations[$dataValidationIndex]['root']) {
					$dataValidations[$dataValidationIndex]['range'] = $dataValidations[$dataValidationIndex]['root'] . ':' . $dataValidations[$dataValidationIndex]['range'];
				}
			}
			
			//Apply data validation for:
			// Generals
			foreach ($dataValidations as $dataValidation) {
				$range = trim($dataValidation['range']);
				if (isset($dataValidation['count']) && $dataValidation['count'] == 0) continue;
				if ($dataValidation['type'] == 'list' && !empty($dataValidation['root']) && !empty($range)) {
					$objValidation = $productSheetObj->getCell($dataValidation['root'])->getDataValidation();
					$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
					$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
					$objValidation->setAllowBlank(false);
					$objValidation->setShowInputMessage(true);
					$objValidation->setShowErrorMessage(true);
					$objValidation->setShowDropDown(true);
					$objValidation->setErrorTitle('Input error');
					$objValidation->setError('Value is not in list.');
					$objValidation->setPromptTitle('Pick from list');
					$objValidation->setPrompt('Please pick a value from the drop-down list.');
					$objValidation->setFormula1($metaSheetObj->getTitle() . '!$' . PHPExcel_Cell::stringFromColumnIndex($dataValidation['data'][0]) . '$' . ($dataValidation['data'][1]) . ':$' . PHPExcel_Cell::stringFromColumnIndex($dataValidation['data'][2]) . '$' . ($dataValidation['data'][3]));
					$productSheetObj->setDataValidation($range, $objValidation);
				}
			}
			
			unset($objValidation);
		} else {
			$progress['done'] = true;
		}
		
		$this->config->set('config_language_id', $default_language);
		
		$this->session->data['generated_file'] = $result;
		$this->session->data['generated_files'][] = $resultName;
		$this->setProgress($progress);
		
		try {
			$this->custom_set_time_limit();
			
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->setPreCalculateFormulas(false);
			
			$objWriter->save($result);
			
			$progress['done'] = true;
		} catch (Exception $e) {
			$progress['message'] = $e->getMessage();
			$progress['error'] = true;
			$progress['done'] = false;
			$this->setProgress($progress);
		}
		$objPHPExcel->disconnectWorksheets();
		unset($metaSheetObj);
		unset($objWriter);
		unset($productSheetObj);
		unset($objPHPExcel);
		
		$progress['done'] = true;
		$this->setProgress($progress);
		
		return true;
	}
	
	public function getQuery($filters = array(), $store = 0, $language = 1, $count = false) {
		if (empty($filters) || !in_array($filters['Conjunction'], array('AND', 'OR'))) $filters['Conjunction'] = 'OR';
		
		$join_rules = array(
			'product_description' => "LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id AND pd.language_id = '" . $language . "')",
			'product_to_store' => "LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)",
			'stock_status' => "LEFT JOIN " . DB_PREFIX . "stock_status ss ON (ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . $language . "')",
			'url_alias' => "LEFT JOIN " . DB_PREFIX . "url_alias ua ON (ua.query = CONCAT('product_id=', p.product_id))",
			'manufacturer' => "LEFT JOIN " . DB_PREFIX . "manufacturer m ON (m.manufacturer_id = p.manufacturer_id)",
			'product_tag' => "LEFT OUTER JOIN " . DB_PREFIX . "product_tag pt ON (p.product_id = pt.product_id AND pt.language_id = '" . $language . "')",
			'category' => "JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (cd.category_id = p2c.category_id AND cd.language_id = '" . $language . "')",
			'filter' => "JOIN " . DB_PREFIX . "product_filter pf ON (p.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "filter_description fd ON (fd.filter_id = pf.filter_id AND fd.language_id = '" . $language . "')",
			'download' => "JOIN " . DB_PREFIX . "product_to_download p2d ON (p.product_id = p2d.product_id) LEFT JOIN " . DB_PREFIX . "download_description dd ON (dd.download_id = p2d.download_id AND dd.language_id = '" . $language . "')",
			'product_related' => "LEFT JOIN " . DB_PREFIX . "product_related pr ON (p.product_id = pr.product_id) LEFT JOIN " . DB_PREFIX . "product_description prd ON (pr.related_id = prd.product_id AND prd.language_id = '" . $language . "')",
			'product_attribute' => "JOIN " . DB_PREFIX . "product_attribute pa ON (p.product_id = pa.product_id AND pa.language_id = '" . $language . "') LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (ad.attribute_id = pa.attribute_id AND ad.language_id = '" . $language . "')",
			'product_option' => "JOIN " . DB_PREFIX . "product_option po ON (p.product_id = po.product_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (po.option_id = od.option_id AND od.language_id = '" . $language . "') LEFT JOIN " . DB_PREFIX . "product_option_value pov ON (pov.product_option_id = po.product_option_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (pov.option_value_id = ovd.option_value_id AND ovd.language_id = '" . $language . "')",
			'product_discount' => "JOIN " . DB_PREFIX . "product_discount pdis ON (p.product_id = pdis.product_id)",
			'product_special' => "JOIN " . DB_PREFIX . "product_special pspe ON (p.product_id = pspe.product_id)",
			'product_image' => "JOIN " . DB_PREFIX . "product_image pi ON (p.product_id = pi.product_id)",
			'product_reward' => "JOIN " . DB_PREFIX . "product_reward pr ON (p.product_id = pr.product_id)",
			'product_to_layout' => "JOIN " . DB_PREFIX . "product_to_layout p2l ON (p.product_id = p2l.product_id AND p2l.store_id = '" . $store . "') LEFT JOIN " . DB_PREFIX . "layout l ON (p2l.layout_id = l.layout_id)"
		);
		
		$joins = array();
		$joins['product_description'] = $join_rules['product_description'];
		$joins['product_to_store'] = $join_rules['product_to_store'];
		if (version_compare(VERSION, '1.5.4', '<')) {
			$joins['product_tag'] = $join_rules['product_tag'];
		}
		
		$wheres = array();
		$alter_joins_array = array();
		
		foreach ($filters as $i => $filter) {
			if (is_array($filter)) {
				if (!array_key_exists($this->conditions['Products'][$filter['Field']]['join_table'], $joins) && array_key_exists($this->conditions['Products'][$filter['Field']]['join_table'], $join_rules)) {
					$joins[$this->conditions['Products'][$filter['Field']]['join_table']] = $join_rules[$this->conditions['Products'][$filter['Field']]['join_table']];
				}
				if (!is_array($this->conditions['Products'][$filter['Field']]['field_name'])) {
					if ($this->conditions['Products'][$filter['Field']]['type'] !== 'not_exists') {
						$condition = str_replace(array('{FIELD_NAME}', '{WORD}'), array($this->conditions['Products'][$filter['Field']]['field_name'], stripos($this->conditions['Products'][$filter['Field']]['type'], 'number') !== FALSE ? (int)$this->db->escape($filter['Value']) : $this->db->escape($filter['Value'])), $this->operations[$filter['Condition']]['operation']);
						if (in_array($this->conditions['Products'][$filter['Field']]['field_name'], array('pdis.date_start', 'pdis.date_end', 'pspe.date_start', 'pspe.date_end'))) {
							$condition = "(" . $condition . " OR " . $this->conditions['Products'][$filter['Field']]['field_name'] . " = '0000-00-00')";
						}
					} else {
						$condition = $this->conditions['Products'][$filter['Field']]['field_name'] . " IS NULL";
						$alter_joins_array_entry = $this->conditions['Products'][$filter['Field']]['join_table'];
						if (!in_array($alter_joins_array_entry, $alter_joins_array)) $alter_joins_array[] = $alter_joins_array_entry;
					}
				} else {
					$sub_conditions = array();
					foreach ($this->conditions['Products'][$filter['Field']]['field_name'] as $field_name) {
						$sub_conditions[] = str_replace(array('{FIELD_NAME}', '{WORD}'), array($field_name, stripos($this->conditions['Products'][$filter['Field']]['type'], 'number') !== FALSE ? (int)$this->db->escape($filter['Value']) : $this->db->escape($filter['Value'])), $this->operations[$filter['Condition']]['operation']);
					}
					$condition = '(' . implode(' OR ', $sub_conditions) . ')';
				}
				if (!in_array($condition, $wheres)) $wheres[] = $condition;
			}
		}
		
		foreach ($alter_joins_array as $alter_joins_array_item) {
			if (array_key_exists($alter_joins_array_item, $joins)) {
				$joins[$alter_joins_array_item]	= preg_replace("/^JOIN/", "LEFT JOIN", $joins[$alter_joins_array_item]);
			}
		}
		
		$select = $count ? "COUNT(*)" : "*, pd.name as name, pd.description as description, pd.meta_description as meta_description, pd.meta_keyword as meta_keyword, pd.meta_title as meta_title, " . (version_compare(VERSION, '1.5.4', '<') ? 'pt.tag as tag' : 'pd.tag as tag') . ", p.*";
		
		$this->load->model('setting/setting');
        $configValue = $this->model_setting_setting->getSetting('ExcelPort');

        if (empty($configValue['ExcelPort']['Settings']['ExportNonStoreProducts'])) {
            $store_condition = "p2s.store_id = '" . $store . "'";
        } else {
            $store_condition = "(p2s.store_id = '" . $store . "' OR p2s.store_id IS NULL)";
        }

        $query = ($count ? "SELECT COUNT(*) as count FROM (" : "") . "SELECT " . $select . " FROM " . DB_PREFIX . "product p " . implode(" ", $joins) . " WHERE " . $store_condition . " " . (!empty($wheres) ? " AND (" . implode(" " . $filters['Conjunction'] . " ", $wheres) . ")" : "") . " GROUP BY p.product_id" . ($count ? ") as count_table" : "");
		
		return $query;
	}
	
	public function addProduct($product_id = '', $data, $allLanguages) {
		$this->addProductLanguages($data, $allLanguages);
		$extra_select = '';
		if (version_compare(VERSION, '1.5.4', '>=')) {
			$extra_select = ", ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "'";
		}
		$product_id = trim($product_id);
		$this->db->query("INSERT INTO " . DB_PREFIX . "product SET ".(!empty($product_id) ? "product_id = '" . (int)trim($product_id) . "', " : "")."model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "'" . $extra_select . ", location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . $this->db->escape($data['tax_class_id']) . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW()");
		
		$product_id = $this->db->getLastId();
		
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE product_id = '" . (int)$product_id . "'");
		}
		
		$language_ids = array();
		foreach ($allLanguages as $language) {
			$language_ids[] = $language['language_id'];	
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "' AND language_id NOT IN (" . implode(',', $language_ids) . ")");
		
		foreach ($data['product_description'] as $language_id => $value) {
			$extra_insert = "";
			if (version_compare(VERSION, '1.5.4', '>=')) {
				$extra_insert = ", tag = '" . $this->db->escape($value['tag']) . "'";
			}
			
			$value['description'] = htmlentities(html_entity_decode($value['description'], ENT_COMPAT, 'UTF-8'), ENT_COMPAT, 'UTF-8');

			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "'" . $extra_insert . " ON DUPLICATE KEY UPDATE name = '" . $this->db->escape($value['name']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "'" . $extra_insert);
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND language_id NOT IN (" . implode(',', $language_ids) . ")");
		
		if (isset($data['product_attribute'])) {
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");
					
					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {				
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
					}
				}
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if (empty($product_option['option_id'])) continue;
				
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");
				
					$product_option_id = $this->db->getLastId();
				
					if (isset($product_option['product_option_value'])) {
                        foreach ($product_option['product_option_value'] as $product_option_value) {
                            $this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
                        }
                    }
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int)$product_option['required'] . "'");
				}
			}
		}
		
		/* OPENSTOCK_HOOK */
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $product_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_special'])) {
			foreach ($data['product_special'] as $product_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape(html_entity_decode($product_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				$this->db->query("REPLACE INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT IGNORE INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}
		
		if (version_compare(VERSION, '1.5.5', '>=')) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
			
			if (isset($data['product_filter'])) {
				foreach ($data['product_filter'] as $filter_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
				}
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				//$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT IGNORE INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				//$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
				//$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_reward'])) {
			foreach ($data['product_reward'] as $customer_group_id => $product_reward) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$product_reward['points'] . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_layout'])) {
			foreach ($data['product_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}
		
		if (version_compare(VERSION, '1.5.4', '<')) {
			$keys = array_keys($data['product_tag']);
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_tag WHERE product_id = '" . (int)$product_id. "' AND (language_id NOT IN (" . implode(',', $language_ids) . ") || language_id = '" . $keys[0] . "')");
			
			foreach ($data['product_tag'] as $language_id => $value) {
				if ($value) {
					$tags = explode(',', $value);
					
					foreach ($tags as $tag) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_tag SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', tag = '" . $this->db->escape(trim($tag)) . "'");
					}
				}
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id. "'");
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$seo_url_alias_table_query = $this->db->query("SELECT table_name FROM information_schema.tables WHERE table_schema = '" . DB_DATABASE . "' AND table_name = '" . DB_PREFIX . "seo_url_alias'");

        if ($seo_url_alias_table_query->num_rows) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url_alias WHERE query = 'product_id=" . (int)$product_id. "'");

            if ($data['keyword']) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "', language_id='" . (int)$this->config->get('config_language_id') . "'");
            }
        }
		
		if (version_compare(VERSION, '2.0', '>=')) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "product_recurring` WHERE product_id = " . (int) $product_id);
			
			if (isset($data['product_recurrings'])) {
				foreach ($data['product_recurrings'] as $recurring) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "product_recurring` SET `product_id` = " . (int) $product_id . ", customer_group_id = " . (int) $recurring['customer_group_id'] . ", `recurring_id` = " . (int) $recurring['recurring_id']);
				}
			}
		}
		
		// Extras
		foreach ($this->extraGeneralFields['Products'] as $extra) {
			if (!empty($extra['eval_add'])) {
				eval($extra['eval_add']);
			}
		}
		
		$this->cache->delete('product');
	}
	
	public function editProduct($product_id, $data, &$languages, $light = false, $specific_field = null) {
		$extra_select = '';
		if (version_compare(VERSION, '1.5.4', '>=')) {
			$extra_select = ", ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "'";
		}
		
		$this->db->query("UPDATE " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "'" . $extra_select . ", location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . $this->db->escape($data['tax_class_id']) . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW() WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE product_id = '" . (int)$product_id . "'");
		}
		
		$language_ids = array();
		foreach ($languages as $language) {
			$language_ids[] = $language['language_id'];	
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "' AND language_id NOT IN (" . implode(',', $language_ids) . ")");
		
		foreach ($data['product_description'] as $language_id => $value) {
			
			$extra_insert = "";
			if (version_compare(VERSION, '1.5.4', '>=')) {
				$extra_insert = ", tag = '" . $this->db->escape($value['tag']) . "'";
			}
			
			$value['description'] = htmlentities(html_entity_decode($value['description'], ENT_COMPAT, 'UTF-8'), ENT_COMPAT, 'UTF-8');

			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "'" . $extra_insert . " ON DUPLICATE KEY UPDATE name = '" . $this->db->escape($value['name']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "'" . $extra_insert);
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
		
		if (!$light) {
			if (is_null($specific_field) || !empty($specific_field['attribute'])) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND language_id NOT IN (" . implode(',', $language_ids) . ")");
		
				if (!empty($data['product_attribute'])) {
					foreach ($data['product_attribute'] as $product_attribute) {
						if ($product_attribute['attribute_id']) {
							$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "' AND language_id NOT IN (" . implode(',', $language_ids) . ")");
							
							foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {				
								$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "' ON DUPLICATE KEY UPDATE text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
							}
						}
					}
				}
			}
	
			if (is_null($specific_field) || !empty($specific_field['option'])) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");
				
				if (isset($data['product_option'])) {
					foreach ($data['product_option'] as $product_option) {
						if (empty($product_option['option_id'])) continue;
						
						if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");
						
							$product_option_id = $this->db->getLastId();
						
							if (isset($product_option['product_option_value'])) {
								foreach ($product_option['product_option_value'] as $product_option_value) {
									$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "', product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
								}
							}
						} else { 
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int)$product_option['required'] . "'");
						}					
					}
				}
			}
			
			/* OPENSTOCK_HOOK */

			if (is_null($specific_field) || !empty($specific_field['discount'])) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");
		 
				if (isset($data['product_discount'])) {
					foreach ($data['product_discount'] as $product_discount) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
					}
				}
			}
			
			if (is_null($specific_field) || !empty($specific_field['special'])) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
				
				if (isset($data['product_special'])) {
					foreach ($data['product_special'] as $product_special) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
					}
				}
			}
			
			if (is_null($specific_field) || !empty($specific_field['image'])) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
				
				if (isset($data['product_image'])) {
					foreach ($data['product_image'] as $product_image) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape(html_entity_decode($product_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
					}
				}
			}
			
			if (is_null($specific_field) || !empty($specific_field['recurring'])) {
				if (version_compare(VERSION, '2.0', '>=')) {
					$this->db->query("DELETE FROM `" . DB_PREFIX . "product_recurring` WHERE product_id = " . (int) $product_id);
					
					if (isset($data['product_recurrings'])) {
						foreach ($data['product_recurrings'] as $recurring) {
							$this->db->query("INSERT INTO `" . DB_PREFIX . "product_recurring` SET `product_id` = " . (int) $product_id . ", customer_group_id = " . (int) $recurring['customer_group_id'] . ", `recurring_id` = " . (int) $recurring['recurring_id']);
						}
					}
				}
			}
			
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				$this->db->query("REPLACE INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT IGNORE INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}		
		}
		
		if (version_compare(VERSION, '1.5.5', '>=')) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
        
			if (isset($data['product_filter'])) {
				foreach ($data['product_filter'] as $filter_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
				}       
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");

		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				//$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT IGNORE INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				//$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
				//$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}
		}
		
		if (!$light) {
			if (is_null($specific_field) || !empty($specific_field['reward points'])) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");
		
				if (isset($data['product_reward'])) {
					foreach ($data['product_reward'] as $customer_group_id => $value) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$value['points'] . "'");
					}
				}
			}
		
			if (is_null($specific_field) || !empty($specific_field['design'])) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");
		
				if (isset($data['product_layout'])) {
					foreach ($data['product_layout'] as $store_id => $layout) {
						if ($layout['layout_id']) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
						}
					}
				}
			}
		
		}
		
		if (version_compare(VERSION, '1.5.4', '<')) {
			$keys = array_keys($data['product_tag']);
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_tag WHERE product_id = '" . (int)$product_id. "' AND (language_id NOT IN (" . implode(',', $language_ids) . ") || language_id = '" . $keys[0] . "')");
		
			foreach ($data['product_tag'] as $language_id => $value) {
				if ($value) {
					$tags = explode(',', $value);
					
					foreach ($tags as $tag) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_tag SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', tag = '" . $this->db->escape(trim($tag)) . "'");
					}
				}
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id. "'");
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$seo_url_alias_table_query = $this->db->query("SELECT table_name FROM information_schema.tables WHERE table_schema = '" . DB_DATABASE . "' AND table_name = '" . DB_PREFIX . "seo_url_alias'");

        if ($seo_url_alias_table_query->num_rows) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url_alias WHERE query = 'product_id=" . (int)$product_id. "'");

            if ($data['keyword']) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "', language_id='" . (int)$this->config->get('config_language_id') . "'");
            }
        }
		
		// Extras
		foreach ($this->extraGeneralFields['Products'] as $extra) {
			if (!empty($extra['eval_edit'])) {
				eval($extra['eval_edit']);
			}
		}
		
        if (file_exists(DIR_APPLICATION . 'model/tool/nitro.php')) {
            $this->load->model('tool/nitro');

            if (method_exists($this->model_tool_nitro, 'clearProductCache')) {
                $this->model_tool_nitro->clearProductCache($product_id);
            }
        }

		$this->cache->delete('product');
	}
	
	public function addProductLanguages(&$data, $allLanguages) {
		// Add Product Description Languages
		if (!empty($data['product_description'])) {
			$entered_keys = array_keys($data['product_description']);
			foreach ($allLanguages as $language) {
				if (!in_array($language['language_id'], $entered_keys)) {
					$data['product_description'][$language['language_id']] = array(
						'name' => $data['product_description'][$entered_keys[0]]['name'],
						'meta_description' => '',
						'meta_keyword' => '',
						'meta_title' => '',
						'description' => '',
						'tag' => ''
					);
				}
			}
		}
		// Add Product Attributes Languages
		if (!empty($data['product_attribute'])) {
			$entered_keys = array_keys($data['product_attribute'][0]['product_attribute_description']);
			foreach ($allLanguages as $language) {
				if (!in_array($language['language_id'], $entered_keys)) {
					$current_keys = array_keys($data['product_attribute']);
					foreach ($current_keys as $data_product_attribute_id) {
						$data['product_attribute'][$data_product_attribute_id]['product_attribute_description'][$language['language_id']] = array(
							'text' => ''
						);
					}
				}
			}
		}
		// Add Product Description Languages
		if (!empty($data['product_tag'])) {
			$entered_keys = array_keys($data['product_tag']);
			foreach ($allLanguages as $language) {
				if (!in_array($language['language_id'], $entered_keys)) {
					$data['product_tag'][$language['language_id']] = '';
				}
			}
		}
	}
	
	public function deleteProducts() {
		$this->load->model('catalog/product');
		
		$ids = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product p");
		
		foreach ($ids->rows as $row) {
			$this->model_catalog_product->deleteProduct($row['product_id']);	
		}
	}
}
?>
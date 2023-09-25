<?php 
class ModelExtensionModuleExcelportproductbulk extends ModelExtensionModuleExcelportproduct {
	
	public function getAttributeRowsByProductId($sheet, $product_id) {
		$data = array();

		$this->load->model('catalog/attribute');
		$this->load->model('catalog/attribute_group');
		$attributes = $this->model_catalog_attribute->getAttributes();
		$attributeGroups = $this->model_catalog_attribute_group->getAttributeGroups();

		$attribute_read_map = array(
			'product_id' => 'A',
			'attribute' => 'B',
			'text' => 'C'
		);

		foreach ($sheet as $row) {
			$new_attribute = array(
				'attribute_id' => null,
				'product_attribute_description' => array(
					$this->config->get('config_language_id') => array(
						'text' => null
					)
				)
			);

			foreach ($row as $cell_index => $cell_value) {
				switch ($cell_index) {
					case $attribute_read_map['product_id'] : {
						if ((int)$cell_value != (int)$product_id) {
							continue 3;
						}
					} break;

					case $attribute_read_map['attribute'] : {
						$candidate_value = trim($cell_value);

						if (empty($candidate_value)) {
							continue 3;
						}

						$attribute_parts = array_filter(array_map('trim', explode($this->productAttributeSeparator, $candidate_value)));

						if (count($attribute_parts) < 2) {
							continue 3;
						}

						$found = false;

						foreach ($attributes as $attribute) {
							foreach ($attributeGroups as $attributeGroup) {
								if (trim($attribute['name']) == $attribute_parts[1] && trim($attributeGroup['name']) == $attribute_parts[0] && $attribute['attribute_group_id'] == $attributeGroup['attribute_group_id']) {
									$found = true;
									$new_attribute['attribute_id'] = $attribute['attribute_id'];
								}
							}
						}

						if (!$found) {
							continue 3;
						}
					} break;

					case $attribute_read_map['text'] : {
						$new_attribute['product_attribute_description'][$this->config->get('config_language_id')]['text'] = trim($cell_value);
					} break;
				}
			}
		
			$data[] = $new_attribute;
		}

		return $data;
	}

	private function getOptionInfoByName($options, $name) {
		foreach($options as $option) {
			if (trim($option['name']) === trim($name)) {
				return $option;
			}
		}

		return array(
			'type' => null,
			'option_id' => 0
		);
	}

	private function getOptionValueIdByOptionValueNameAndOptionId($option_value_name, $option_id) {
		$this->load->model('catalog/option');
		$values = $this->model_catalog_option->getOptionValues($option_id);

		foreach ($values as $value) {
			if (trim($value['name']) === trim($option_value_name)) {
				return (int)$value['option_value_id'];
			}
		}

		return 0;
	}

	public function getOptionRowsByProductId($sheet, $product_id) {
		$data = array();

		$optionValues = array();
		$this->load->model('catalog/option');
		$options = $this->model_catalog_option->getOptions();
		foreach ($options as $optionEntity) {
			$optionValues[] = trim($optionEntity['name']);
		}

		$option_read_map = array(
			'product_id' => 'A',
			'option' => 'B',
			'required' => 'C',
			'option_value' => 'D',
			'quantity' => 'E',
			'subtract' => 'F',
			'price' => 'G',
			'points' => 'H',
			'weight' => 'I'
		);

		$this_product_id = null;
		$this_option_id = null;
		$this_option_name = null;
		$this_required = null;
		$this_option = array();

		foreach ($sheet as $row) {
			$new_option_row = array();

			foreach ($row as $cell_index => $cell_value) {

				foreach ($option_read_map as $map_key => $map_value) {
					if ($cell_index != $map_value) continue;

					$new_option_row[$map_key] = trim($cell_value);
				}

			}

			if (!empty($new_option_row['product_id'])) {
				$this_product_id = (int)$new_option_row['product_id'];

				if ($this_product_id != (int)$product_id) {
					continue;
				}
				
				$this_option_name = trim($new_option_row['option']);
				$this_option_info = $this->getOptionInfoByName($options, $this_option_name);
				$this_option_id = $this_option_info['option_id'];
				$this_option_type = $this_option_info['type'];
				$this_required = strtolower($new_option_row['required']) == 'yes' ? 1 : 0;

				if (!empty($this_option['product_id']) && $this_option['product_id'] == (int)$product_id) {
					$data[] = $this_option;
				}

				$this_option = array(
					'product_id' => $this_product_id,
					'product_option_id' => '',
					'type' => $this_option_type,
					'option_id' => $this_option_id,
					'required' => $this_required
				);
			}

			if ((int)$this_product_id != (int)$product_id || empty($this_option_id)) {
				continue;
			}

			if (empty($new_option_row['product_id']) && trim($new_option_row['option_value']) === "") {
                break;
            }

			$this_option_value = $new_option_row['option_value'];

			if ($this_option_type == 'select' || $this_option_type == 'radio' || $this_option_type == 'checkbox' || $this_option_type == 'image') {
				if (!isset($this_option['product_option_value'])) {
					$this_option['product_option_value'] = array();
				}

				$this_option_value_id = $this->getOptionValueIdByOptionValueNameAndOptionId($this_option_value, $this_option_id);

				if (empty($this_option_value_id)) continue;

				$product_option_value_price = (float)str_replace(array('+', '-', ' ', ','), array('', '', '', '.'), $new_option_row['price']);
				$product_option_value_price_prefix = stripos($new_option_row['price'], '-') === 0 ? '-' : '+';
				$product_option_value_points = (int)str_replace(array('+', '-', ' ', ','), array('', '', '', '.'), $new_option_row['points']);
				$product_option_value_points_prefix = stripos($new_option_row['points'], '-') === 0 ? '-' : '+';
				$product_option_value_weight = (float)str_replace(array('+', '-', ' ', ','), array('', '', '', '.'), $new_option_row['weight']);
				$product_option_value_weight_prefix = stripos($new_option_row['weight'], '-') === 0 ? '-' : '+';
				
				$this_option['product_option_value'][] = array (
					'option_value_id' => $this_option_value_id,
					'product_option_value_id' => '',
					'quantity' => (int)trim($new_option_row['quantity']),
					'subtract' => strtolower($new_option_row['subtract']) == 'yes' ? 1 : 0,
					'price_prefix' => $product_option_value_price_prefix,
					'price' => $product_option_value_price,
					'points_prefix' => $product_option_value_points_prefix,
					'points' => $product_option_value_points,
					'weight_prefix' => $product_option_value_weight_prefix,
					'weight' => $product_option_value_weight
				);
			} else {
				$this_option['value'] = $this_option_value;
			}
		}

		if (!empty($this_option['product_id']) && $this_option['product_id'] == (int)$product_id) {
			$data[] = $this_option;
		}

		return $data;
	}

	public function getDiscountRowsByProductId($sheet, $product_id) {
		$data = array();

		$discount_read_map = array(
			'product_id' => 'A',
			'customer_group' => 'B',
			'quantity' => 'C',
			'priority' => 'D',
			'price' => 'E',
			'date_start' => 'F',
			'date_end' => 'G'
		);

		foreach ($sheet as $row) {
			
			$new_discount = array(
				'customer_group_id' => null,
				'quantity' => null,
				'priority' => null,
				'price' => null,
				'date_start' => null,
				'date_end' => null
			);

			foreach ($row as $cell_index => $cell_value) {
				switch ($cell_index) {
					case $discount_read_map['product_id'] : {
						if ((int)$cell_value != (int)$product_id) {
							continue 3;
						}
					} break;

					case $discount_read_map['customer_group'] : {
						$candidate_value = trim($cell_value);

						if (empty($candidate_value)) {
							continue 3;
						}

						$customer_group_id = $this->getCustomerGroupIdByName($candidate_value);

						if (empty($customer_group_id)) {
							continue 3;
						}
					} break;

					case $discount_read_map['quantity'] : {
						$quantity = (int)trim($cell_value);
					} break;

					case $discount_read_map['priority'] : {
						$priority = (int)trim($cell_value);
					} break;

					case $discount_read_map['price'] : {
						$price = (float)str_replace(array('+', '-', ' ', ','), array('', '', '', '.'), $cell_value);
					} break;

					case $discount_read_map['date_start'] : {
						$date_start = trim($cell_value);
					} break;

					case $discount_read_map['date_end'] : {
						$date_end = trim($cell_value);
					} break;
				}

			}
		
			$new_discount['customer_group_id'] = $customer_group_id;
			$new_discount['quantity'] = $quantity;
			$new_discount['priority'] = $priority;
			$new_discount['price'] = $price;
			$new_discount['date_start'] = $date_start;
			$new_discount['date_end'] = $date_end;

			$data[] = $new_discount;
		}

		return $data;
	}

	public function getSpecialRowsByProductId($sheet, $product_id) {
		$data = array();

		$special_read_map = array(
			'product_id' => 'A',
			'customer_group' => 'B',
			'priority' => 'C',
			'price' => 'D',
			'date_start' => 'E',
			'date_end' => 'F'
		);

		foreach ($sheet as $row) {
			
			$new_special = array(
				'customer_group_id' => null,
				'priority' => null,
				'price' => null,
				'date_start' => null,
				'date_end' => null
			);

			foreach ($row as $cell_index => $cell_value) {
				switch ($cell_index) {
					case $special_read_map['product_id'] : {
						if ((int)$cell_value != (int)$product_id) {
							continue 3;
						}
					} break;

					case $special_read_map['customer_group'] : {
						$candidate_value = trim($cell_value);

						if (empty($candidate_value)) {
							continue 3;
						}

						$customer_group_id = $this->getCustomerGroupIdByName($candidate_value);

						if (empty($customer_group_id)) {
							continue 3;
						}
					} break;

					case $special_read_map['priority'] : {
						$priority = (int)trim($cell_value);
					} break;

					case $special_read_map['price'] : {
						$price = (float)str_replace(array('+', '-', ' ', ','), array('', '', '', '.'), $cell_value);
					} break;

					case $special_read_map['date_start'] : {
						$date_start = trim($cell_value);
					} break;

					case $special_read_map['date_end'] : {
						$date_end = trim($cell_value);
					} break;
				}

			}
		
			$new_special['customer_group_id'] = $customer_group_id;
			$new_special['priority'] = $priority;
			$new_special['price'] = $price;
			$new_special['date_start'] = $date_start;
			$new_special['date_end'] = $date_end;

			$data[] = $new_special;
		}

		return $data;
	}

	public function getImageRowsByProductId($sheet, $product_id) {
		$data = array();

		$image_read_map = array(
			'product_id' => 'A',
			'image' => 'B',
			'sort_order' => 'C'
		);

		foreach ($sheet as $row) {
			
			$new_image = array(
				'image' => null,
				'sort_order' => null
			);

			foreach ($row as $cell_index => $cell_value) {
				switch ($cell_index) {
					case $image_read_map['product_id'] : {
						if ((int)$cell_value != (int)$product_id) {
							continue 3;
						}
					} break;

					case $image_read_map['image'] : {
						$candidate_value = trim($cell_value);

						if (empty($candidate_value)) {
							continue 3;
						}

						$image = $candidate_value;
					} break;

					case $image_read_map['sort_order'] : {
						$sort_order = (int)trim($cell_value);
					} break;
				}

			}
		
			$new_image['image'] = $image;
			$new_image['sort_order'] = $sort_order;

			$data[] = $new_image;
		}

		return $data;
	}

	private function getProfileIdByName($profile_name) {
		$this->load->model('catalog/recurring');
		$profiles = $this->model_catalog_recurring->getRecurrings();

		foreach ($profiles as $profile) {
			if ($profile['name'] == $profile_name) {
				return (int)$profile['recurring_id'];
			}
		}

		return 0;
	}

	private function getCustomerGroupIdByName($customer_group_name) {
		if (version_compare(VERSION, '2.1.0.1', '>=')) {
            $this->load->model('customer/customer_group');
            $customer_groups = $this->model_customer_customer_group->getCustomerGroups();
        } else {
            $this->load->model('sale/customer_group');
            $customer_groups = $this->model_sale_customer_group->getCustomerGroups();
        }

		foreach ($customer_groups as $customer_group) {
			if (trim(strtolower($customer_group['name'])) == trim(strtolower($customer_group_name))) {
				return (int)$customer_group['customer_group_id'];
			}
		}

		return 0;
	}

	public function getRecurringRowsByProductId($sheet, $product_id) { //a big @TODO
		if (version_compare(VERSION, '1.5.6', '<')) return array();

		$data = array();

		$recurring_read_map = array(
			'product_id' => 'A',
			'profile' => 'B',
			'customer_group' => 'C'
		);

		foreach ($sheet as $row) {
			
			$new_recurring = array(
				'recurring_id' => null,
				'customer_group_id' => null
			);

			foreach ($row as $cell_index => $cell_value) {
				switch ($cell_index) {
					case $recurring_read_map['product_id'] : {
						if ((int)$cell_value != (int)$product_id) {
							continue 3;
						}
					} break;

					case $recurring_read_map['profile'] : {
						$candidate_value = trim($cell_value);

						if (empty($candidate_value)) {
							continue 3;
						}

						$recurring_id = $this->getProfileIdByName($candidate_value);

						if (empty($recurring_id)) {
							continue 3;
						}
					} break;

					case $recurring_read_map['customer_group'] : {
						$candidate_value = trim($cell_value);

						if (empty($candidate_value)) {
							continue 3;
						}

						$customer_group_id = $this->getCustomerGroupIdByName($candidate_value);

						if (empty($customer_group_id)) {
							continue 3;
						}
					} break;
				}

			}
		
			$new_recurring['recurring_id'] = $recurring_id;
			$new_recurring['customer_group_id'] = $customer_group_id;

			$data[] = $new_recurring;
		}

		return $data;
	}

	public function getRewardRowsByProductId($sheet, $product_id) {
		$data = array();

		$reward_point_read_map = array(
			'product_id' => 'A',
			'customer_group' => 'B',
			'points' => 'C'
		);

		if (version_compare(VERSION, '2.1.0.1', '>=')) {
            $this->load->model('customer/customer_group');
            $customer_groups = $this->model_customer_customer_group->getCustomerGroups();
        } else {
            $this->load->model('sale/customer_group');
            $customer_groups = $this->model_sale_customer_group->getCustomerGroups();
        }

		foreach ($customer_groups as $customer_group) {
			$data[$customer_group['customer_group_id']] = array(
				'points' => 0
			);
		}

		foreach ($sheet as $row) {
			
			foreach ($row as $cell_index => $cell_value) {
				switch ($cell_index) {
					case $reward_point_read_map['product_id'] : {
						if ((int)$cell_value != (int)$product_id) {
							continue 3;
						}
					} break;

					case $reward_point_read_map['customer_group'] : {
						$candidate_value = trim($cell_value);

						if (empty($candidate_value)) {
							continue 3;
						}

						$customer_group_id = $this->getCustomerGroupIdByName($candidate_value);

						if (empty($customer_group_id)) {
							continue 3;
						}
					} break;

					case $reward_point_read_map['points'] : {
						$points = (int)trim($cell_value);
					} break;
				}

			}
		
			$data[$customer_group_id]['points'] = $points;
		}

		return $data;
	}

	private function getStoreIdByName($store_name) {
		$this->load->model('setting/store');
		$stores = array_merge(array(0 => array('store_id' => 0, 'name' => 'Default', 'url' => NULL, 'ssl' => NULL)), $this->model_setting_store->getStores());

		foreach ($stores as $store) {
			if ($store['name'] == $store_name) {
				return (int)$store['store_id'];
			}
		}

		return 0;
	}

	private function getLayoutIdByName($layout_name) {
		$this->load->model('design/layout');

		$layouts = $this->model_design_layout->getLayouts();

		foreach ($layouts as $layout) {
			if ($layout['name'] == $layout_name) {
				return (int)$layout['layout_id'];
			}
		}

		return 0;
	}

	public function getDesignRowsByProductId($sheet, $product_id) {
		$data = array();

		$design_read_map = array(
			'product_id' => 'A',
			'store' => 'B',
			'layout' => 'C'
		);

		$this->load->model('setting/store');
		$stores = array_merge(array(0 => array('store_id' => 0, 'name' => 'Default', 'url' => NULL, 'ssl' => NULL)), $this->model_setting_store->getStores());

		foreach ($stores as $store) {
			$data[$store['store_id']] = array(
				'layout_id' => 0
			);
		}

		foreach ($sheet as $row) {
			
			foreach ($row as $cell_index => $cell_value) {
				switch ($cell_index) {
					case $design_read_map['product_id'] : {
						if ((int)$cell_value != (int)$product_id) {
							continue 3;
						}
					} break;

					case $design_read_map['store'] : {
						$candidate_value = trim($cell_value);

						if (empty($candidate_value)) {
							continue 3;
						}

						$store_id = $this->getStoreIdByName($candidate_value);
					} break;

					case $design_read_map['layout'] : {
						$candidate_value = trim($cell_value);

						if (empty($candidate_value)) {
							continue 3;
						}

						$layout_id = $this->getLayoutIdByName($candidate_value);

						if (empty($layout_id)) {
							continue 3;
						}
					} break;
				}

			}
		
			$data[$store_id]['layout_id'] = $layout_id;
		}

		return $data;
	}

	public function sheetRowsToType($type, $product_id) {
		switch ($type) {
			case 'attribute' : {
				return $this->getAttributeRowsByProductId($this->readSheetCache($type), $product_id);
			} break;
			case 'option' : {
				return $this->getOptionRowsByProductId($this->readSheetCache($type), $product_id);
			} break;

			case 'discount' : {
				return $this->getDiscountRowsByProductId($this->readSheetCache($type), $product_id);
			} break;

			case 'special' : {
				return $this->getSpecialRowsByProductId($this->readSheetCache($type), $product_id);
			} break;

			case 'image' : {
				return $this->getImageRowsByProductId($this->readSheetCache($type), $product_id);
			} break;

			case 'recurring' : {
				return $this->getRecurringRowsByProductId($this->readSheetCache($type), $product_id);
			} break;

			case 'reward points' : {
				return $this->getRewardRowsByProductId($this->readSheetCache($type), $product_id);
			} break;

			case 'design' : {
				return $this->getDesignRowsByProductId($this->readSheetCache($type), $product_id);
			} break;
		}
	}

	public function exportXLSProductsBulk($language, $store, $destinationFolder = '', $productNumber, $export_filters = array(), $targetHTMLFormat) {
		$this->language->load('extension/module/excelport');
		$this->folderCheck($destinationFolder);

		$progress = $this->getProgress();
		$progress['done'] = false;

		$file = IMODULE_ROOT . 'vendors/excelport/template_product_bulk.xlsx';

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

		$productSheet = 0;
		$attributeSheet = 1;
		$optionSheet = 2;
		$recurringSheet = 3;
		$discountSheet = 4;
		$specialSheet = 5;
		$imageSheet = 6;
		$rewardSheet = 7;
		$designSheet = 8;
		$metaSheet = 9;
		
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
		
		if (version_compare(VERSION, '1.5.6', '>=')) {
			$profilesStart = array(21,3);
			$this->load->model('catalog/recurring');
			$profiles = $this->model_catalog_recurring->getRecurrings();
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
			'meta_title'		=> 39
		);

		$attribute_generals = array(
			'product_id' => 0,
			'attribute' => 1,
			'text' => 2
		);

		$option_generals = array(
			'product_id' => 0,
			'option' => 1,
			'required' => 2,
			'option_value' => 3,
			'quantity' => 4,
			'subtract' => 5,
			'price' => 6,
			'points' => 7,
			'weight' => 8
		);

		$recurring_generals = array(
			'product_id' => 0,
			'profile' => 1,
			'customer_group' => 2
		);

		$discount_generals = array(
			'product_id' => 0,
			'customer_group' => 1,
			'quantity' => 2,
			'priority' => 3,
			'price' => 4,
			'date_start' => 5,
			'date_end' => 6
		);

		$special_generals = array(
			'product_id' => 0,
			'customer_group' => 1,
			'priority' => 2,
			'price' => 3,
			'date_start' => 4,
			'date_end' => 5
		);

		$image_generals = array(
			'product_id' => 0,
			'image' => 1,
			'sort_order' => 2
		);

		$reward_point_generals = array(
			'product_id' => 0,
			'customer_group' => 1,
			'points' => 2
		);

		$design_generals = array(
			'product_id' => 0,
			'store' => 1,
			'layout' => 2
		);

		// Extra fields
		$extras = array();
		foreach ($this->extraGeneralFields['Products'] as $extra) {
			if (!empty($extra['name']) && !empty($extra['column_bulk']) && is_array($extra['column_bulk'])) {
				foreach ($extra['column_bulk'] as $column_bulk_sheet => $column_bulk_column) {
					$extras[$column_bulk_sheet][$extra['name']] = array($column_bulk_column,0);
				}
			}
		}

		$dataValidationsGenerals = array(
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

		$dataValidationsAttributes = array(
			array(
				'type' => 'list',
				'field' => $attribute_generals['attribute'],
				'data' => array($attributesStart[0], $attributesStart[1], $attributesStart[0], $attributesStart[1] + count($attributes) - 1),
				'range' => '',
				'count' => count($attributes)
			)
		);

		$dataValidationsOptions = array(
			array(
				'type' => 'list',
				'field' => $option_generals['required'],
				'data' => array(1,2,1,3),
				'range' => ''
			),
			array(
				'type' => 'list',
				'field' => $option_generals['subtract'],
				'data' => array(1,2,1,3),
				'range' => ''
			)
		);

		$dataValidationsRecurrings = array(
			array(
				'type' => 'list',
				'field' => $recurring_generals['customer_group'],
				'data' => array($customerGroupsStart[0] + 1, $customerGroupsStart[1], $customerGroupsStart[0] + 1, $customerGroupsStart[1] + count($customerGroups) - 1),
				'range' => ''
			)
		);

		$dataValidationsDiscounts = array(
			array(
				'type' => 'list',
				'field' => $discount_generals['customer_group'],
				'data' => array($customerGroupsStart[0] + 1, $customerGroupsStart[1], $customerGroupsStart[0] + 1, $customerGroupsStart[1] + count($customerGroups) - 1),
				'range' => ''
			)
		);

		$dataValidationsSpecials = array(
			array(
				'type' => 'list',
				'field' => $special_generals['customer_group'],
				'data' => array($customerGroupsStart[0] + 1, $customerGroupsStart[1], $customerGroupsStart[0] + 1, $customerGroupsStart[1] + count($customerGroups) - 1),
				'range' => ''
			)
		);

		$dataValidationsRewardPoints = array(
			array(
				'type' => 'list',
				'field' => $reward_point_generals['customer_group'],
				'data' => array($customerGroupsStart[0] + 1, $customerGroupsStart[1], $customerGroupsStart[0] + 1, $customerGroupsStart[1] + count($customerGroups) - 1),
				'range' => ''
			)
		);

		$dataValidationsDesigns = array(
			array(
				'type' => 'list',
				'field' => $design_generals['store'],
				'data' => array($storesStart[0] + 1, $storesStart[1], $storesStart[0] + 1, $storesStart[1] + count($stores) - 1),
				'range' => ''
			),
			array(
				'type' => 'list',
				'field' => $design_generals['layout'],
				'data' => array($layoutsStart[0], $layoutsStart[1], $layoutsStart[0], $layoutsStart[1] + count($layouts) - 1),
				'range' => ''
			)
		);

		$this->load->model('localisation/language');
		$languageQuery = $this->model_localisation_language->getLanguage($this->config->get('config_language_id'));
		
		$name = 'products_excelport_bulk_' . $languageQuery['code'] . '_' . str_replace('/', '_', substr(HTTP_CATALOG, 7, strlen(HTTP_CATALOG) - 8)) . '_' . date("Y-m-d_H-i-s") . '_' . $progress['current'];
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
		
		if (version_compare(VERSION, '1.5.6', '>=')) {
			for ($i = 0; $i < count($profiles); $i++) {
				$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($profilesStart[0]) . ($profilesStart[1] + $i), $profiles[$i]['recurring_id'], PHPExcel_Cell_DataType::TYPE_STRING);
				$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($profilesStart[0] + 1) . ($profilesStart[1] + $i), $profiles[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
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

		$this->db->query("SET SESSION group_concat_max_len = 1000000;");

		$products = $this->db->query($this->getQuery($export_filters, $store, $language) . " ORDER BY p.product_id LIMIT ". $progress['current'] . ", " . $productNumber);

		$productSheetObj = $objPHPExcel->setActiveSheetIndex($productSheet);
		$attributeSheetObj = $objPHPExcel->setActiveSheetIndex($attributeSheet);
		$optionSheetObj = $objPHPExcel->setActiveSheetIndex($optionSheet);
		$recurringSheetObj = $objPHPExcel->setActiveSheetIndex($recurringSheet);
		$discountSheetObj = $objPHPExcel->setActiveSheetIndex($discountSheet);
		$specialSheetObj = $objPHPExcel->setActiveSheetIndex($specialSheet);
		$imageSheetObj = $objPHPExcel->setActiveSheetIndex($imageSheet);
		$rewardSheetObj = $objPHPExcel->setActiveSheetIndex($rewardSheet);
		$designSheetObj = $objPHPExcel->setActiveSheetIndex($designSheet);

		$t = array(
			'product' => array(0,2),
			'attribute' => array(0,2),
			'option' => array(0,2),
			'recurring' => array(0,2),
			'discount' => array(0,2),
			'special' => array(0,2),
			'image' => array(0,2),
			'reward' => array(0,2),
			'design' => array(0,2)
		);

		foreach ($this->extraGeneralFields['Products'] as $extra) {
			if (!empty($extra['title']) && !empty($extra['column_bulk']) && is_array($extra['column_bulk'])) {
				foreach ($extra['column_bulk'] as $column_bulk_sheet => $column_bulk_column) {
					${$column_bulk_sheet . 'SheetObj'}->setCellValueExplicit($column_bulk_column . '1', $extra['title'], PHPExcel_Cell_DataType::TYPE_STRING);
				}
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
                $defaultStockStatus = "";
				foreach ($stockStates as $stockStatus) {
					if ($stockStatus['stock_status_id'] == $row['stock_status_id']) { $row['stock_status'] = $stockStatus['name']; }
					if ($stockStatus['stock_status_id'] == $this->config->get('config_stock_status_id')) { $defaultStockStatus = $stockStatus['name']; }	
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

				// Add data
				// Extras
				if (!empty($extras['product']) && is_array($extras['product'])) {
					foreach ($extras['product'] as $name => $position) {
						$productSheetObj->setCellValueExplicit($position[0] . ($t['product'][1]), empty($row[$name]) ? '' : $row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
					}
				}
				// General
				foreach ($generals as $name => $position) {
					$productSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['product'][0] + $position) . ($t['product'][1]), !isset($row[$name]) ? '' : $row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
				}
				
				// Data validations
				foreach ($dataValidationsGenerals as $dataValidationIndex => $dataValidation) {
					if (isset($dataValidationsGenerals[$dataValidationIndex]['count']) && $dataValidationsGenerals[$dataValidationIndex]['count'] == 0) continue;
					$dataValidationsGenerals[$dataValidationIndex]['range'] = PHPExcel_Cell::stringFromColumnIndex($t['product'][0] + $dataValidation['field']) . ($t['product'][1]);
					if (empty($dataValidationsGenerals[$dataValidationIndex]['root'])) $dataValidationsGenerals[$dataValidationIndex]['root'] = PHPExcel_Cell::stringFromColumnIndex($t['product'][0] + $dataValidation['field']) . ($t['product'][1]);
				}

				// Attributes
				$productAttributes = $this->model_catalog_product->getProductAttributes($row['product_id']);
				
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
						
						if (empty($productAttribute['product_attribute_description'][$language])) {
							continue;
						}

						foreach ($attribute_generals as $attribute_name => $attribute_position) {
							switch ($attribute_name) {
								case 'product_id' : $attributeSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['attribute'][0] + $attribute_position) . ($t['attribute'][1]), $row['product_id'], PHPExcel_Cell_DataType::TYPE_STRING);
									break;

								case 'attribute' : $attributeSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['attribute'][0] + $attribute_position) . ($t['attribute'][1]), $productAttribute['name'], PHPExcel_Cell_DataType::TYPE_STRING);
									break;

								case 'text' : $attributeSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['attribute'][0] + $attribute_position) . ($t['attribute'][1]), $productAttribute['product_attribute_description'][$language]['text'], PHPExcel_Cell_DataType::TYPE_STRING);
									break;
							}
						}

						$t['attribute'][1]++;
					}
				}

				// Data validations
				foreach ($dataValidationsAttributes as $dataValidationIndex => $dataValidation) {
					if (isset($dataValidationsAttributes[$dataValidationIndex]['count']) && $dataValidationsAttributes[$dataValidationIndex]['count'] == 0) continue;
					$dataValidationsAttributes[$dataValidationIndex]['range'] = PHPExcel_Cell::stringFromColumnIndex($t['attribute'][0] + $dataValidation['field']) . ($t['attribute'][1] - 1);
					if (empty($dataValidationsAttributes[$dataValidationIndex]['root'])) $dataValidationsAttributes[$dataValidationIndex]['root'] = PHPExcel_Cell::stringFromColumnIndex($t['attribute'][0] + $dataValidation['field']) . ($t['attribute'][1] - 1);
				}

				// Options
				$productOptions = $this->model_catalog_product->getProductOptions($row['product_id']);
				
				foreach ($productOptions as $productOptionIndex => $productOption) {
					if (version_compare(VERSION, '1.5.5', '>=')) {
						$myProductOption = $this->model_catalog_option->getOption($productOption['option_id']);
						$productOption['name'] = $myProductOption['name'];
					}
					
					$productOption['required'] = (int)$productOption['required'];

					foreach ($option_generals as $option_name => $option_position) {
						switch ($option_name) {
							case 'product_id' :
								$optionSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['option'][0] + $option_position) . ($t['option'][1]), $row['product_id'], PHPExcel_Cell_DataType::TYPE_STRING);
							break;
							case 'option' :
								$optionSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['option'][0] + $option_position) . ($t['option'][1]), $productOption['name'], PHPExcel_Cell_DataType::TYPE_STRING);
							break;
							case 'required' :
								$optionSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['option'][0] + $option_position) . ($t['option'][1]), !empty($productOption['required']) ? 'Yes' : 'No', PHPExcel_Cell_DataType::TYPE_STRING);
							break;
						}
					}

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
					} else if (isset($productOption['value'])) {
						$optionDataFields[] = array(
							$productOption['value'],
							'',
							'',
							'',
							'',
							''
						);
					}
					
					if (!empty($optionDataFields)) {
						foreach ($optionDataFields as $optionDataField) {
							foreach ($option_generals as $option_name => $option_position) {
								switch ($option_name) {
									case 'option_value' :
										$optionSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['option'][0] + $option_position) . ($t['option'][1]), $optionDataField[0], PHPExcel_Cell_DataType::TYPE_STRING);
									break;
									case 'quantity' :
										$optionSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['option'][0] + $option_position) . ($t['option'][1]), $optionDataField[1], PHPExcel_Cell_DataType::TYPE_STRING);
									break;
									case 'subtract' :
										$optionSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['option'][0] + $option_position) . ($t['option'][1]), $optionDataField[2], PHPExcel_Cell_DataType::TYPE_STRING);
									break;
									case 'price' :
										$optionSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['option'][0] + $option_position) . ($t['option'][1]), $optionDataField[3], PHPExcel_Cell_DataType::TYPE_STRING);
									break;
									case 'points' :
										$optionSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['option'][0] + $option_position) . ($t['option'][1]), $optionDataField[4], PHPExcel_Cell_DataType::TYPE_STRING);
									break;
									case 'weight' :
										$optionSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['option'][0] + $option_position) . ($t['option'][1]), $optionDataField[5], PHPExcel_Cell_DataType::TYPE_STRING);
									break;
								}
							}

							$t['option'][1]++;
						}
					}
				}
				
				// Data validations
				foreach ($dataValidationsOptions as $dataValidationIndex => $dataValidation) {
					if (isset($dataValidationsOptions[$dataValidationIndex]['count']) && $dataValidationsOptions[$dataValidationIndex]['count'] == 0) continue;
					$dataValidationsOptions[$dataValidationIndex]['range'] = PHPExcel_Cell::stringFromColumnIndex($t['option'][0] + $dataValidation['field']) . ($t['option'][1] - 1);
					if (empty($dataValidationsOptions[$dataValidationIndex]['root'])) $dataValidationsOptions[$dataValidationIndex]['root'] = PHPExcel_Cell::stringFromColumnIndex($t['option'][0] + $dataValidation['field']) . ($t['option'][1] - 1);
				}

				// Recurring
				if (!empty($row['recurrings']) && !empty($profiles)) {
					$productProfiles = array_map('trim', explode(',', $row['recurrings']));

					foreach ($productProfiles as $productProfile) {
						$productProfileParts = array_map('trim', explode(':', $productProfile));

						$profile_result = $this->model_catalog_recurring->getRecurringDescription($productProfileParts[0]);
                        
						if (version_compare(VERSION, '2.1.0.1', '>=')) {
                            $customer_group = $this->model_customer_customer_group->getCustomerGroup($productProfileParts[1]);
                        } else {
                            $customer_group = $this->model_sale_customer_group->getCustomerGroup($productProfileParts[1]);
                        }

						if (empty($profile_result[$this->config->get('config_language_id')]['name']) || empty($customer_group['name'])) continue;
						
						foreach ($recurring_generals as $recurring_name => $recurring_position) {
							switch ($recurring_name) {
								case 'product_id' :
									$recurringSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['recurring'][0] + $recurring_position) . ($t['recurring'][1]), $row['product_id'], PHPExcel_Cell_DataType::TYPE_STRING);
								break;
								case 'profile' :
									$recurringSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['recurring'][0] + $recurring_position) . ($t['recurring'][1]), $profile_result[$this->config->get('config_language_id')]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
								break;
								case 'customer_group' :
									$recurringSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['recurring'][0] + $recurring_position) . ($t['recurring'][1]), $customer_group['name'], PHPExcel_Cell_DataType::TYPE_STRING);
								break;
							}
						}

						$t['recurring'][1]++;
					}
				}

				// Data validations
				foreach ($dataValidationsRecurrings as $dataValidationIndex => $dataValidation) {
					if (isset($dataValidationsRecurrings[$dataValidationIndex]['count']) && $dataValidationsRecurrings[$dataValidationIndex]['count'] == 0) continue;
					$dataValidationsRecurrings[$dataValidationIndex]['range'] = PHPExcel_Cell::stringFromColumnIndex($t['recurring'][0] + $dataValidation['field']) . ($t['recurring'][1] - 1);
					if (empty($dataValidationsRecurrings[$dataValidationIndex]['root'])) $dataValidationsRecurrings[$dataValidationIndex]['root'] = PHPExcel_Cell::stringFromColumnIndex($t['recurring'][0] + $dataValidation['field']) . ($t['recurring'][1] - 1);
				}

				// Discount
				$productDiscounts = $this->model_catalog_product->getProductDiscounts($row['product_id']);

				foreach ($productDiscounts as $productDiscount) {
					$found = false;
					
					foreach ($customerGroups as $customerGroup) {
						if ($customerGroup['customer_group_id'] == $productDiscount['customer_group_id']) {
							$found = true;
							$productDiscount['customer_group'] = $customerGroup['name'];
							break;
						}
					}

					if (!$found) continue;

					foreach ($discount_generals as $discount_name => $discount_position) {
						switch ($discount_name) {
							case 'product_id' :
								$discountSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['discount'][0] + $discount_position) . ($t['discount'][1]), $row['product_id'], PHPExcel_Cell_DataType::TYPE_STRING);
							break;
							case 'customer_group' :
								$discountSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['discount'][0] + $discount_position) . ($t['discount'][1]), $productDiscount['customer_group'], PHPExcel_Cell_DataType::TYPE_STRING);
							break;
							case 'quantity' :
								$discountSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['discount'][0] + $discount_position) . ($t['discount'][1]), $productDiscount['quantity'], PHPExcel_Cell_DataType::TYPE_STRING);
							break;
							case 'priority' :
								$discountSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['discount'][0] + $discount_position) . ($t['discount'][1]), $productDiscount['priority'], PHPExcel_Cell_DataType::TYPE_STRING);
							break;
							case 'price' :
								$discountSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['discount'][0] + $discount_position) . ($t['discount'][1]), $productDiscount['price'], PHPExcel_Cell_DataType::TYPE_STRING);
							break;
							case 'date_start' :
								$discountSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['discount'][0] + $discount_position) . ($t['discount'][1]), $productDiscount['date_start'], PHPExcel_Cell_DataType::TYPE_STRING);
							break;
							case 'date_end' :
								$discountSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['discount'][0] + $discount_position) . ($t['discount'][1]), $productDiscount['date_end'], PHPExcel_Cell_DataType::TYPE_STRING);
							break;
						}
					}

					$t['discount'][1]++;
				}

				// Data validations
				foreach ($dataValidationsDiscounts as $dataValidationIndex => $dataValidation) {
					if (isset($dataValidationsDiscounts[$dataValidationIndex]['count']) && $dataValidationsDiscounts[$dataValidationIndex]['count'] == 0) continue;
					$dataValidationsDiscounts[$dataValidationIndex]['range'] = PHPExcel_Cell::stringFromColumnIndex($t['discount'][0] + $dataValidation['field']) . ($t['discount'][1] - 1);
					if (empty($dataValidationsDiscounts[$dataValidationIndex]['root'])) $dataValidationsDiscounts[$dataValidationIndex]['root'] = PHPExcel_Cell::stringFromColumnIndex($t['discount'][0] + $dataValidation['field']) . ($t['discount'][1] - 1);
				}
				
				// Special
				$productSpecials = $this->model_catalog_product->getProductSpecials($row['product_id']);

				foreach ($productSpecials as $productSpecial) {
					$found = false;
					
					foreach ($customerGroups as $customerGroup) {
						if ($customerGroup['customer_group_id'] == $productSpecial['customer_group_id']) {
							$found = true;
							$productSpecial['customer_group'] = $customerGroup['name'];
							break;
						}
					}

					if (!$found) continue;
					
					foreach ($special_generals as $special_name => $special_position) {
						switch ($special_name) {
							case 'product_id' :
								$specialSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['special'][0] + $special_position) . ($t['special'][1]), $row['product_id'], PHPExcel_Cell_DataType::TYPE_STRING);
							break;
							case 'customer_group' :
								$specialSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['special'][0] + $special_position) . ($t['special'][1]), $productSpecial['customer_group'], PHPExcel_Cell_DataType::TYPE_STRING);
							break;
							case 'priority' :
								$specialSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['special'][0] + $special_position) . ($t['special'][1]), $productSpecial['priority'], PHPExcel_Cell_DataType::TYPE_STRING);
							break;
							case 'price' :
								$specialSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['special'][0] + $special_position) . ($t['special'][1]), $productSpecial['price'], PHPExcel_Cell_DataType::TYPE_STRING);
							break;
							case 'date_start' :
								$specialSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['special'][0] + $special_position) . ($t['special'][1]), $productSpecial['date_start'], PHPExcel_Cell_DataType::TYPE_STRING);
							break;
							case 'date_end' :
								$specialSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['special'][0] + $special_position) . ($t['special'][1]), $productSpecial['date_end'], PHPExcel_Cell_DataType::TYPE_STRING);
							break;
						}
					}

					$t['special'][1]++;
				}

				// Data validations
				foreach ($dataValidationsSpecials as $dataValidationIndex => $dataValidation) {
					if (isset($dataValidationsSpecials[$dataValidationIndex]['count']) && $dataValidationsSpecials[$dataValidationIndex]['count'] == 0) continue;
					$dataValidationsSpecials[$dataValidationIndex]['range'] = PHPExcel_Cell::stringFromColumnIndex($t['special'][0] + $dataValidation['field']) . ($t['special'][1] - 1);
					if (empty($dataValidationsSpecials[$dataValidationIndex]['root'])) $dataValidationsSpecials[$dataValidationIndex]['root'] = PHPExcel_Cell::stringFromColumnIndex($t['special'][0] + $dataValidation['field']) . ($t['special'][1] - 1);
				}

				// Image
				$productImages = $this->model_catalog_product->getProductImages($row['product_id']);
				
				foreach ($productImages as $productImage) {
					foreach ($image_generals as $image_name => $image_position) {
						switch ($image_name) {
							case 'product_id' :
								$imageSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['image'][0] + $image_position) . ($t['image'][1]), $row['product_id'], PHPExcel_Cell_DataType::TYPE_STRING);
							break;
							case 'image' :
								$imageSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['image'][0] + $image_position) . ($t['image'][1]), $productImage['image'], PHPExcel_Cell_DataType::TYPE_STRING);
							break;
							case 'sort_order' :
								$imageSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['image'][0] + $image_position) . ($t['image'][1]), $productImage['sort_order'], PHPExcel_Cell_DataType::TYPE_STRING);
							break;
						}
					}

					$t['image'][1]++;
				}
				
				$productRewards = $this->model_catalog_product->getProductRewards($row['product_id']);
				
				foreach ($productRewards as $customer_group_id => $productReward) {
					$found = false;
					
					foreach ($customerGroups as $customerGroup) {
						if ($customerGroup['customer_group_id'] == $customer_group_id) {
							$found = true;
							$productReward['customer_group'] = $customerGroup['name'];
							break;
						}
					}

					if ($found) {
						foreach ($reward_point_generals as $reward_name => $reward_position) {
							switch ($reward_name) {
								case 'product_id' :
									$rewardSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['reward'][0] + $reward_position) . ($t['reward'][1]), $row['product_id'], PHPExcel_Cell_DataType::TYPE_STRING);
								break;
								case 'customer_group' :
									$rewardSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['reward'][0] + $reward_position) . ($t['reward'][1]), $productReward['customer_group'], PHPExcel_Cell_DataType::TYPE_STRING);
								break;
								case 'points' :
									$rewardSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['reward'][0] + $reward_position) . ($t['reward'][1]), $productReward['points'], PHPExcel_Cell_DataType::TYPE_STRING);
								break;
							}
						}

						$t['reward'][1]++;
					}
				}

				// Data validations
				foreach ($dataValidationsRewardPoints as $dataValidationIndex => $dataValidation) {
					if (isset($dataValidationsRewardPoints[$dataValidationIndex]['count']) && $dataValidationsRewardPoints[$dataValidationIndex]['count'] == 0) continue;
					$dataValidationsRewardPoints[$dataValidationIndex]['range'] = PHPExcel_Cell::stringFromColumnIndex($t['reward'][0] + $dataValidation['field']) . ($t['reward'][1] - 1);
					if (empty($dataValidationsRewardPoints[$dataValidationIndex]['root'])) $dataValidationsRewardPoints[$dataValidationIndex]['root'] = PHPExcel_Cell::stringFromColumnIndex($t['reward'][0] + $dataValidation['field']) . ($t['reward'][1] - 1);
				}
				
				// Design
				$productLayouts = $this->model_catalog_product->getProductLayouts($row['product_id']);
				
				// returned array
				// store_id => layout_id


				foreach ($stores as $store) {

					if (empty($productLayouts[$store['store_id']])) {
						$productLayoutName = '';
					} else {
						$layout_id = $productLayouts[$store['store_id']];

						$productLayoutName = '';

						foreach ($layouts as $layout) {
							if ($layout['layout_id'] == $layout_id) {
								$productLayoutName = $layout['name'];
								break;
							}
						}
					}

					foreach ($design_generals as $design_name => $design_position) {
						switch ($design_name) {
							case 'product_id' :
								$designSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['design'][0] + $design_position) . ($t['design'][1]), $row['product_id'], PHPExcel_Cell_DataType::TYPE_STRING);
							break;
							case 'store' :
								$designSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['design'][0] + $design_position) . ($t['design'][1]), $store['name'], PHPExcel_Cell_DataType::TYPE_STRING);
							break;
							case 'layout' :
								$designSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($t['design'][0] + $design_position) . ($t['design'][1]), $productLayoutName, PHPExcel_Cell_DataType::TYPE_STRING);
							break;
						}
					}

					$t['design'][1]++;
				}
				
				// Data validations
				foreach ($dataValidationsDesigns as $dataValidationIndex => $dataValidation) {
					if (isset($dataValidationsDesigns[$dataValidationIndex]['count']) && $dataValidationsDesigns[$dataValidationIndex]['count'] == 0) continue;
					$dataValidationsDesigns[$dataValidationIndex]['range'] = PHPExcel_Cell::stringFromColumnIndex($t['design'][0] + $dataValidation['field']) . ($t['design'][1] - 1);
					if (empty($dataValidationsDesigns[$dataValidationIndex]['root'])) $dataValidationsDesigns[$dataValidationIndex]['root'] = PHPExcel_Cell::stringFromColumnIndex($t['design'][0] + $dataValidation['field']) . ($t['design'][1] - 1);
				}

				$t['product'][1] = $t['product'][1] + 1;
				$progress['current']++;
				$progress['memory_get_usage'] = round(memory_get_usage(true)/(1024*1024));
				$progress['percent'] = 100 / ($products->num_rows / $progress['current']);
				
				$this->setProgress($progress);
			}

			$this->updateDataValidations($productSheetObj, $dataValidationsGenerals, $metaSheetObj->getTitle());
			$this->updateDataValidations($attributeSheetObj, $dataValidationsAttributes, $metaSheetObj->getTitle());
			$this->updateDataValidations($optionSheetObj, $dataValidationsOptions, $metaSheetObj->getTitle());
			$this->updateDataValidations($recurringSheetObj, $dataValidationsRecurrings, $metaSheetObj->getTitle());
			$this->updateDataValidations($discountSheetObj, $dataValidationsDiscounts, $metaSheetObj->getTitle());
			$this->updateDataValidations($specialSheetObj, $dataValidationsSpecials, $metaSheetObj->getTitle());
			$this->updateDataValidations($rewardSheetObj, $dataValidationsRewardPoints, $metaSheetObj->getTitle());
			$this->updateDataValidations($designSheetObj, $dataValidationsDesigns, $metaSheetObj->getTitle());
			
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

	public function importXLSProductsBulk($language, $allLanguages, $file, $importLimit, $addAsNew = false) {
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
		$chunkFilter = new CustomReadFilter(array("Products" => array('A', ($progress['importedCount'] + 2), 'AM', (($progress['importedCount'] + $importLimit) + 1)), "Product" => array('A', ($progress['importedCount'] + 2), 'AM', (($progress['importedCount'] + $importLimit) + 1)), "product" => array('A', ($progress['importedCount'] + 2), 'AM', (($progress['importedCount'] + $importLimit) + 1)), "products" => array('A', ($progress['importedCount'] + 2), 'AM', (($progress['importedCount'] + $importLimit) + 1))), true);
		
		$madeImports = false;
		$objReader = new PHPExcel_Reader_Excel2007();
		$objReader->setReadFilter($chunkFilter);
		$objReader->setReadDataOnly(true);
		$objReader->setLoadSheetsOnly(array("Products", "products", "Product", "product"));
		$objPHPExcel = $objReader->load($file);
		$progress['importingFile'] = substr($file, strripos($file, '/') + 1);
		$productsSheet = 0;
		
		$productSheetObj = $this->getSheet($objPHPExcel, array('products', 'product'));
		
		$progress['all'] = -1;
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
			'meta_title'		=> 39
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
					'points' => (int)trim($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['points']) . ($source[1]))->getValue())
				);
				
				if (version_compare(VERSION, '1.5.4', '<')) {
					$product['product_tag'] = array(
						$language => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['tag']) . ($source[1]))->getValue()
					);	
				}
				
				// Extras
				foreach ($this->extraGeneralFields['Products'] as $extra) {
					if (!empty($extra['name']) && !empty($extra['column_bulk']) && is_array($extra['column_bulk']) && array_key_exists('product', $extra['column_bulk'])) {
						$product[$extra['name']] = $productSheetObj->getCell($extra['column_bulk']['product'] . $source[1])->getValue();
					}
				}
				
				$product['product_attribute'] = $this->loadBulkSheetData('attribute', $file, $product_id);
				$product['product_option'] = $this->loadBulkSheetData('option', $file, $product_id);
				$product['product_discount'] = $this->loadBulkSheetData('discount', $file, $product_id);
				$product['product_special'] = $this->loadBulkSheetData('special', $file, $product_id);
				$product['product_image'] = $this->loadBulkSheetData('image', $file, $product_id);
				$product['product_recurrings'] = $this->loadBulkSheetData('recurring', $file, $product_id);
				$product['product_reward'] = $this->loadBulkSheetData('reward points', $file, $product_id);
				$product['product_layout'] = $this->loadBulkSheetData('design', $file, $product_id);

				if (!$addAsNew) {
					$exists = false;
					$existsQuery = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE product_id = ".$product_id);
					
					$exists = $existsQuery->num_rows > 0;
					
					if ($exists) {
						$this->editProduct($product_id, $product, $allLanguages, false, $this->specific_field);
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
}

<?php 
class ModelExtensionModuleExcelportcategory extends ModelExtensionModuleExcelport {
	public function importXLSCategories($language, $allLanguages, $file, $importLimit = 100) {
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
		$chunkFilter = new CustomReadFilter(array('Categories' => array('A', ($progress['importedCount'] + 2), 'AM', (($progress['importedCount'] + $importLimit) + 1)), 'categories' => array('A', ($progress['importedCount'] + 2), 'AM', (($progress['importedCount'] + $importLimit) + 1))), true); 
		
		$madeImports = false;
		$objReader = new PHPExcel_Reader_Excel2007();
		$objReader->setReadFilter($chunkFilter);
		$objReader->setReadDataOnly(true);
		$objReader->setLoadSheetsOnly(array("Categories", "categories"));
		$objPHPExcel = $objReader->load($file);
		$progress['importingFile'] = substr($file, strripos($file, '/') + 1);
		$categoriesSheet = 0;
		
		$categoriesSheetObj = $objPHPExcel->setActiveSheetIndex($categoriesSheet);
		
		$progress['all'] = -1; //(int)(($categoriesSheetObj->getHighestRow() - 2)/$this->productSize);
		$this->setProgress($progress);
		
		$category_map = array(
			'category_id' 		=> 0,
			'name'				=> 1,
			'meta_description'	=> 3,
			'meta_keyword'		=> 4,
			'meta_title'		=> 15,
			'description'		=> 2,
			'parent_id'			=> 5,
			'stores'			=> 6,
			'filters'			=> 7,
			'keyword'			=> 8,
			'image'				=> 9,
			'top'				=> 10,
			'column'			=> 11,
			'sort_order'		=> 12,
			'status'			=> 13,
			'category_layout'	=> 14
		);
		
		$source = array(0,2 + ($progress['importedCount']));
		
		$category_layout = array();
		$this->load->model('setting/store');
		$stores = array_merge(array(0 => array('store_id' => 0, 'name' => 'Default', 'url' => NULL, 'ssl' => NULL)),$this->model_setting_store->getStores());
		
		do {
			$this->custom_set_time_limit();
			$category_name = strval($categoriesSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $category_map['name']) . ($source[1]))->getValue());
			if (!empty($category_name)) {
				$category_id = $categoriesSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $category_map['category_id']) . ($source[1]))->getValue();
				
				$category_column = (int)str_replace(' ', '', $categoriesSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $category_map['column']) . ($source[1]))->getValue());
				$category_top = $categoriesSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $category_map['top']) . ($source[1]))->getValue() == 'Yes' ? 1 : 0;
				$category_status = $categoriesSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $category_map['status']) . ($source[1]))->getValue() == 'Enabled' ? 1 : 0;
				$category_sort_order = (int)str_replace(' ', '', $categoriesSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $category_map['sort_order']) . ($source[1]))->getValue());
				
				$category_store = array();
				$category_stores = explode(',', str_replace('.', ',', strval($categoriesSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $category_map['stores']) . ($source[1]))->getValue())));
				foreach ($category_stores as $store) {
					$store = trim($store);
					if ($store !== '') $category_store[] = $store;
				}
				
				$category_filter = array();
				if (version_compare(VERSION, '1.5.5', '>=')) {
					$filters = explode(',', str_replace('.', ',', strval($categoriesSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $category_map['filters']) . ($source[1]))->getValue())));
					foreach ($filters as $filter) {
						$filter = trim($filter);
						if (!empty($filter)) $category_filter[] = trim($filter);
					}
				}
				
				$categoryStoreToLayouts = trim($categoriesSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $category_map['category_layout']) . ($source[1]))->getValue());
				$categoryStoreToLayouts = explode(',', $categoryStoreToLayouts);
				
				foreach ($stores as $store) {
					$layout_id = '';
					if (!empty($categoryStoreToLayouts)) {
						foreach($categoryStoreToLayouts as $categoryStoreToLayout) {
							$categoryStoreToLayout = explode(':', $categoryStoreToLayout);
							if (is_array($categoryStoreToLayout) && count($categoryStoreToLayout) == 2 && $store['store_id'] == trim($categoryStoreToLayout[0])) {
								$layout_id = trim($categoryStoreToLayout[1]);
							}
						}
					}
					$category_layout[$store['store_id']] = array(
						'layout_id' => $layout_id
					);
				}
				
				$category = array(
					'category_description' => array(
						$language => array(
							'name' => $category_name,
							'meta_description' => $categoriesSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $category_map['meta_description']) . ($source[1]))->getValue(),
							'meta_keyword' => $categoriesSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $category_map['meta_keyword']) . ($source[1]))->getValue(),
							'meta_title' => $categoriesSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $category_map['meta_title']) . ($source[1]))->getValue(),
							'description' => $categoriesSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $category_map['description']) . ($source[1]))->getValue()
						)
					),
					'parent_id' => trim($categoriesSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $category_map['parent_id']) . ($source[1]))->getValue()),
					'category_store' => $category_store,
					'category_filter' => $category_filter,
					'keyword' => $categoriesSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $category_map['keyword']) . ($source[1]))->getValue(),
					'image' => trim($categoriesSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $category_map['image']) . ($source[1]))->getValue()),
					'top' => $category_top,
					'column' => $category_column,
					'sort_order' => $category_sort_order,
					'status' => $category_status,
					'category_layout' => $category_layout
				);
				
				// Extras
				foreach ($this->extraGeneralFields['Categories'] as $extra) {
					if (!empty($extra['name']) && !empty($extra['column_light'])) {
						$category[$extra['name']] = $categoriesSheetObj->getCell($extra['column_light'] . $source[1])->getValue();	
					}
				}
				
				if (!empty($category_id)) {
					$exists = false;
					$existsQuery = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category WHERE category_id = ".$category_id);
					
					$exists = $existsQuery->num_rows > 0;
							
					if ($exists) {
						$this->editCategory($category_id, $category, $allLanguages);
					} else {
						$this->addCategory($category_id, $category, $allLanguages);
					}
				} else {
					$this->addCategory('', $category, $allLanguages);
				}
				
				$progress['current']++;
				$progress['importedCount']++;
				$madeImports = true;
				$this->setProgress($progress);
			}
			$source[1] += 1;
		} while (!empty($category_name));
		$progress['done'] = true;
		if (!$madeImports) {
			$progress['importedCount'] = 0;
			array_shift($this->session->data['uploaded_files']);
		}
		$this->setProgress($progress);
		
		$this->config->set('config_language_id', $default_language);
	}
	
	public function exportXLSCategories($language, $store, $destinationFolder = '', $categoryNumber = 800, $export_filters = array()) {
		$this->language->load('extension/module/excelport');
		$this->folderCheck($destinationFolder);
	
		$progress = $this->getProgress();
		$progress['done'] = false;
		
		$file = IMODULE_ROOT . 'vendors/excelport/template_category.xlsx';
		
		$default_language = $this->config->get('config_language_id');
		$this->config->set('config_language_id', $language);
		require_once(IMODULE_ROOT.'vendors/phpexcel/PHPExcel.php');
		
		if (!empty($progress['populateAll'])) {
			$all = $this->db->query($this->getQuery($export_filters, $store, $language, true));
			$progress['all'] = $all->num_rows ? (int)$all->row['count'] : 0;
			unset($progress['populateAll']);
			$this->setProgress($progress);
		}
		
		$this->setData('Categories', $destinationFolder, $language);
		
		$categoriesSheet = 0;
		$categoriesMetaSheet = 1;
		
		$storesStart = array(2,3);
		$this->load->model('setting/store');
		$stores = array_merge(array(0 => array('store_id' => 0, 'name' => 'Default', 'url' => NULL, 'ssl' => NULL)),$this->model_setting_store->getStores());
		
		if (version_compare(VERSION, '1.5.5', '>=')) {
			$filtersStart = array(6,3);
			$this->load->model('catalog/filter');
			$filters = $this->model_catalog_filter->getFilters(array());
		}
		
		$layoutsStart = array(4,3);
		$layouts = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout l LEFT JOIN " . DB_PREFIX . "layout_route lr ON (lr.layout_id = l.layout_id AND lr.store_id = '" . $store . "') WHERE lr.store_id = '" . $store . "' GROUP BY l.layout_id");
		$layouts = $layouts->rows;
		
		$categories_generals = array(
			'category_id' 		=> 0,
			'name'				=> 1,
			'meta_description'	=> 3,
			'meta_keyword'		=> 4,
			'meta_title'		=> 15,
			'description'		=> 2,
			'parent_id'			=> 5,
			'stores'			=> 6,
			'filters'			=> 7,
			'keyword'			=> 8,
			'image'				=> 9,
			'top'				=> 10,
			'column'			=> 11,
			'sort_order'		=> 12,
			'status'			=> 13,
			'category_layout'	=> 14
		);
		
		// Extra fields
		$extras = array();
		foreach ($this->extraGeneralFields['Categories'] as $extra) {
			if (!empty($extra['name']) && !empty($extra['column_light'])) {
				$extras[$extra['name']] = $extra['column_light'];
			}
		}
		
		$categoriesDataValidations = array(
			array(
				'type' => 'list',
				'field' => $categories_generals['top'],
				'data' => array(0,2,0,3),
				'range' => ''
			),
			array(
				'type' => 'list',
				'field' => $categories_generals['status'],
				'data' => array(1,2,1,3),
				'range' => ''
			)
		);
		
		$categories_target = array(0,2);
		
		$this->load->model('localisation/language');
		$languageQuery = $this->model_localisation_language->getLanguage($this->config->get('config_language_id'));
		
		$name = 'categories_excelport_' . $languageQuery['code'] . '_' . str_replace('/', '_', substr(HTTP_CATALOG, 7, strlen(HTTP_CATALOG) - 8)) . '_' . date("Y-m-d_H-i-s") . '_' . $progress['current'];
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
		
		$categoriesMetaSheetObj = $objPHPExcel->setActiveSheetIndex($categoriesMetaSheet);
		
		for ($i = 0; $i < count($stores); $i++) {
			$categoriesMetaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($storesStart[0]) . ($storesStart[1] + $i), $stores[$i]['store_id'], PHPExcel_Cell_DataType::TYPE_STRING);
			$categoriesMetaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($storesStart[0] + 1) . ($storesStart[1] + $i), $stores[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		for ($i = 0; $i < count($layouts); $i++) {
			$categoriesMetaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($layoutsStart[0]) . ($layoutsStart[1] + $i), $layouts[$i]['layout_id'], PHPExcel_Cell_DataType::TYPE_STRING);
			$categoriesMetaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($layoutsStart[0] + 1) . ($layoutsStart[1] + $i), $layouts[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		if (version_compare(VERSION, '1.5.5', '>=')) {
			for ($i = 0; $i < count($filters); $i++) {
				$categoriesMetaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($filtersStart[0]) . ($filtersStart[1] + $i), $filters[$i]['filter_id'], PHPExcel_Cell_DataType::TYPE_STRING);
				$categoriesMetaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($filtersStart[0] + 1) . ($filtersStart[1] + $i), $filters[$i]['group'] . $this->categoryFilterSeparator . $filters[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
			}
		} 
		
		$this->load->model('catalog/category');
		
		$extra_select = "";
		
		$this->db->query("SET SESSION group_concat_max_len = 1000000;");
		
		$categories_result = $this->db->query($this->getQuery($export_filters, $store, $language) . " ORDER BY c.category_id LIMIT ". $progress['current'] . ", " . $categoryNumber);
		
		$categoriesSheetObj = $objPHPExcel->setActiveSheetIndex($categoriesSheet);
		
		foreach ($this->extraGeneralFields['Categories'] as $extra) {
			if (!empty($extra['title']) && !empty($extra['column_light'])) {
				$categoriesSheetObj->setCellValueExplicit($extra['column_light'] . '1', $extra['title'], PHPExcel_Cell_DataType::TYPE_STRING);
			}
		}
		
		if ($categories_result->num_rows > 0) {
			foreach ($categories_result->rows as $myCategoriesIndex => $category_row) {
				
				$this->getData('Categories', $category_row);
				
				// Prepare data
				$category_row['top'] = empty($category_row['top']) ? 'No' : 'Yes';
				$category_row['sort_order'] = empty($category_row['sort_order']) ? '0' : $category_row['sort_order'];
				$category_row['status'] = empty($category_row['status']) ? 'Disabled' : 'Enabled';
				if (empty($category_row['name'])) $category_row['name'] = '-';
				if (empty($category_row['filters'])) $category_row['filters'] = '';
				
				// Add data
				// Extras
				foreach ($extras as $name => $position) {
					$categoriesSheetObj->setCellValueExplicit($position . ($categories_target[1]), empty($category_row[$name]) ? '' : $category_row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
				}
				// General
				foreach ($categories_generals as $name => $position) {
					$categoriesSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($categories_target[0] + $position) . ($categories_target[1]), empty($category_row[$name]) && $category_row[$name] !== '0' ? '' : $category_row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
				}
				
				// Data validations
				foreach ($categoriesDataValidations as $dataValidationIndex => $dataValidation) {
					if (isset($categoriesDataValidations[$dataValidationIndex]['count']) && $categoriesDataValidations[$dataValidationIndex]['count'] == 0) continue;
					$categoriesDataValidations[$dataValidationIndex]['range'] = PHPExcel_Cell::stringFromColumnIndex($categories_target[0] + $dataValidation['field']) . ($categories_target[1]);
					if (empty($categoriesDataValidations[$dataValidationIndex]['root'])) $categoriesDataValidations[$dataValidationIndex]['root'] = PHPExcel_Cell::stringFromColumnIndex($categories_target[0] + $dataValidation['field']) . ($categories_target[1]);
				}
				
				$categories_target[1] = $categories_target[1] + 1;
				$progress['current']++;
				$progress['memory_get_usage'] = round(memory_get_usage(true)/(1024*1024));
				$progress['percent'] = 100 / ($categories_result->num_rows / $progress['current']);
				
				$this->setProgress($progress);
			}
			
			foreach ($categoriesDataValidations as $dataValidationIndex => $dataValidation) {
				if (isset($categoriesDataValidations[$dataValidationIndex]['count']) && $categoriesDataValidations[$dataValidationIndex]['count'] == 0) continue;
				if ($categoriesDataValidations[$dataValidationIndex]['range'] != $categoriesDataValidations[$dataValidationIndex]['root']) {
					$categoriesDataValidations[$dataValidationIndex]['range'] = $categoriesDataValidations[$dataValidationIndex]['root'] . ':' . $categoriesDataValidations[$dataValidationIndex]['range'];
				}
			}
			
			//Apply data validation for:
			// Generals
			foreach ($categoriesDataValidations as $dataValidation) {
				$range = trim($dataValidation['range']);
				if (isset($dataValidation['count']) && $dataValidation['count'] == 0) continue;
				if ($dataValidation['type'] == 'list' && !empty($dataValidation['root']) && !empty($range)) {
					$objValidation = $categoriesSheetObj->getCell($dataValidation['root'])->getDataValidation();
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
					$objValidation->setFormula1($categoriesMetaSheetObj->getTitle() . '!$' . PHPExcel_Cell::stringFromColumnIndex($dataValidation['data'][0]) . '$' . ($dataValidation['data'][1]) . ':$' . PHPExcel_Cell::stringFromColumnIndex($dataValidation['data'][2]) . '$' . ($dataValidation['data'][3]));
					$categoriesSheetObj->setDataValidation($range, $objValidation);
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
		unset($categoriesMetaSheetObj);
		unset($objWriter);
		unset($categoriesSheetObj);
		unset($objPHPExcel);
		
		$progress['done'] = true;
		$this->setProgress($progress);
		
		return true;
	}
	public function getQuery($filters = array(), $store = 0, $language = 1, $count = false) {
		if (empty($filters) || !in_array($filters['Conjunction'], array('AND', 'OR'))) $filters['Conjunction'] = 'OR';
		
		$join_rules = array(
			'category_description' => "LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id AND cd.language_id = '" . $language . "')",
			'category_to_store' => "LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id AND c2s.store_id = '" . $store . "')",
			'category_parent' => "LEFT JOIN " . DB_PREFIX . "category_description cpard ON (c.parent_id = cpard.category_id AND cpard.language_id = '" . $language . "')",
			'filter' => "JOIN " . DB_PREFIX . "category_filter cf ON (c.category_id = cf.category_id) LEFT JOIN " . DB_PREFIX . "filter_description fd ON (fd.filter_id = cf.filter_id AND fd.language_id = '" . $language . "')",
			'url_alias' => "LEFT JOIN " . DB_PREFIX . "url_alias ua ON (ua.query = CONCAT('category_id=', c.category_id))",
			'category_to_layout' => "JOIN " . DB_PREFIX . "category_to_layout c2l ON (c.category_id = c2l.category_id AND c2l.store_id = '" . $store . "') LEFT JOIN " . DB_PREFIX . "layout l ON (c2l.layout_id = l.layout_id)"
		);
		
		$joins = array();
		$joins['category_description'] = $join_rules['category_description'];
		$joins['category_to_store'] = $join_rules['category_to_store'];
		
		$wheres = array();
		
		foreach ($filters as $i => $filter) {
			if (is_array($filter)) {
				if (!array_key_exists($this->conditions['Categories'][$filter['Field']]['join_table'], $joins) && array_key_exists($this->conditions['Categories'][$filter['Field']]['join_table'], $join_rules)) {
					$joins[$this->conditions['Categories'][$filter['Field']]['join_table']] = $join_rules[$this->conditions['Categories'][$filter['Field']]['join_table']];
				}
				$condition = str_replace(array('{FIELD_NAME}', '{WORD}'), array($this->conditions['Categories'][$filter['Field']]['field_name'], stripos($this->conditions['Categories'][$filter['Field']]['type'], 'number') !== FALSE ? (int)$this->db->escape($filter['Value']) : $this->db->escape($filter['Value'])), $this->operations[$filter['Condition']]['operation']);
				if (!in_array($condition, $wheres)) $wheres[] = $condition;
			}
		}
		
		$select = $count ? "COUNT(*)" : "*, cd.name as name, cd.description as description, cd.meta_description as meta_description, cd.meta_keyword as meta_keyword, cd.meta_title as meta_title, c.*";
		
		$query = ($count ? "SELECT COUNT(*) as count FROM (" : "") . "SELECT " . $select . " FROM " . DB_PREFIX . "category c " . implode(" ", $joins) . " WHERE c2s.store_id = '" . $store . "' " . (!empty($wheres) ? " AND (" . implode(" " . $filters['Conjunction'] . " ", $wheres) . ")" : "") . " GROUP BY c.category_id" . ($count ? ") as count_table" : "");
		
		return $query;
	}
	public function addCategory($category_id = '', $data, $allLanguages) {
		$this->addCategoryLanguages($data, $allLanguages);
		
		$category_id = trim($category_id);
			
		$this->db->query("INSERT INTO " . DB_PREFIX . "category SET ".(!empty($category_id) ? "category_id = '" . (int)trim($category_id) . "', " : "")."parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW(), date_added = NOW()");
	
		$category_id = $this->db->getLastId();
		
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE category_id = '" . (int)$category_id . "'");
		}
		
		$language_ids = array();
		foreach ($allLanguages as $language) {
			$language_ids[] = $language['language_id'];	
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "' AND language_id NOT IN (" . implode(',', $language_ids) . ")");
		
		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "' ON DUPLICATE KEY UPDATE name = '" . $this->db->escape($value['name']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
		
		if (version_compare(VERSION, '1.5.5', '>=')) {
			// MySQL Hierarchical Data Closure Table Pattern
			$level = 0;
			
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY `level` ASC");
			
			foreach ($query->rows as $result) {
				$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");
				
				$level++;
			}
			
			$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', `level` = '" . (int)$level . "'");
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");
			
			if (isset($data['category_filter'])) {
				foreach ($data['category_filter'] as $filter_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "category_filter SET category_id = '" . (int)$category_id . "', filter_id = '" . (int)$filter_id . "'");
				}
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");
		
		if (isset($data['category_store'])) {
			foreach ($data['category_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");
		
		if (isset($data['category_layout'])) {
			foreach ($data['category_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_layout SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id. "'");
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		$seo_url_alias_table_query = $this->db->query("SELECT table_name FROM information_schema.tables WHERE table_schema = '" . DB_DATABASE . "' AND table_name = '" . DB_PREFIX . "seo_url_alias'");

        if ($seo_url_alias_table_query->num_rows) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url_alias WHERE query = 'category_id=" . (int)$category_id. "'");

            if ($data['keyword']) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($data['keyword']) . "', language_id='" . (int)$this->config->get('config_language_id') . "'");
            }
        }

		// Extras
		foreach ($this->extraGeneralFields['Categories'] as $extra) {
			if (!empty($extra['eval_add'])) {
				eval($extra['eval_add']);
			}
		}
		
		$this->cache->delete('category');
	}
	
	public function editCategory($category_id, $data, &$languages) {
		$this->db->query("UPDATE " . DB_PREFIX . "category SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE category_id = '" . (int)$category_id . "'");
		}
		
		$language_ids = array();
		foreach ($languages as $language) {
			$language_ids[] = $language['language_id'];	
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "' AND language_id NOT IN (" . implode(',', $language_ids) . ")");

		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "' ON DUPLICATE KEY UPDATE name = '" . $this->db->escape($value['name']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
		
		if (version_compare(VERSION, '1.5.5', '>=')) {
			// MySQL Hierarchical Data Closure Table Pattern
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE path_id = '" . (int)$category_id . "' ORDER BY level ASC");
			
			if ($query->rows) {
				foreach ($query->rows as $category_path) {
					// Delete the path below the current one
					$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' AND level < '" . (int)$category_path['level'] . "'");
					
					$path = array();
					
					// Get the nodes new parents
					$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");
					
					foreach ($query->rows as $result) {
						$path[] = $result['path_id'];
					}
					
					// Get whats left of the nodes current path
					$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' ORDER BY level ASC");
					
					foreach ($query->rows as $result) {
						$path[] = $result['path_id'];
					}
					
					// Combine the paths with a new level
					$level = 0;
					
					foreach ($path as $path_id) {
						$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_path['category_id'] . "', `path_id` = '" . (int)$path_id . "', level = '" . (int)$level . "'");
						
						$level++;
					}
				}
			} else {
				// Delete the path below the current one
				$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_id . "'");
				
				// Fix for records with no paths
				$level = 0;
				
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");
				
				foreach ($query->rows as $result) {
					$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");
					
					$level++;
				}
				
				$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', level = '" . (int)$level . "'");
			}
	
			$this->db->query("DELETE FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");
			
			if (isset($data['category_filter'])) {
				foreach ($data['category_filter'] as $filter_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "category_filter SET category_id = '" . (int)$category_id . "', filter_id = '" . (int)$filter_id . "'");
				}		
			}	
		}
		
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");
		
		if (isset($data['category_store'])) {		
			foreach ($data['category_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['category_layout'])) {
			foreach ($data['category_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_layout SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}
						
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id. "'");
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$seo_url_alias_table_query = $this->db->query("SELECT table_name FROM information_schema.tables WHERE table_schema = '" . DB_DATABASE . "' AND table_name = '" . DB_PREFIX . "seo_url_alias'");

        if ($seo_url_alias_table_query->num_rows) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url_alias WHERE query = 'category_id=" . (int)$category_id. "'");

            if ($data['keyword']) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($data['keyword']) . "', language_id='" . (int)$this->config->get('config_language_id') . "'");
            }
        }
		
		// Extras
		foreach ($this->extraGeneralFields['Categories'] as $extra) {
			if (!empty($extra['eval_edit'])) {
				eval($extra['eval_edit']);
			}
		}
		
		$this->cache->delete('category');
	}
	public function addCategoryLanguages(&$data, $allLanguages) {
		// Add Category Description Languages
		if (!empty($data['category_description'])) {
			$entered_keys = array_keys($data['category_description']);
			foreach ($allLanguages as $language) {
				if (!in_array($language['language_id'], $entered_keys)) {
					$data['category_description'][$language['language_id']] = array(
						'name' => $data['category_description'][$entered_keys[0]]['name'],
						'meta_description' => '',
						'meta_keyword' => '',
						'meta_title' => '',
						'description' => ''
					);
				}
			}
		}
	}
	public function deleteCategories() {
		$this->load->model('catalog/category');
		
		$ids = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category c");
		
		foreach ($ids->rows as $row) {
			$this->model_catalog_category->deleteCategory($row['category_id']);	
		}
	}
}
?>
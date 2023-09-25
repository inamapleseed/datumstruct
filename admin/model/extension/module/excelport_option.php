<?php 
class ModelExtensionModuleExcelportoption extends ModelExtensionModuleExcelport {
	public function importXLSOptions($language, $allLanguages, $file, $addAsNew = false) {
		$this->language->load('extension/module/excelport');
		
		$default_language = $this->config->get('config_language_id');
		$this->config->set('config_language_id', $language);
		
		$progress = $this->getProgress();
		$progress['importedCount'] = !empty($progress['importedCount']) ? $progress['importedCount'] : 0;
		$progress['done'] = false;
		
		require_once(IMODULE_ROOT.'vendors/phpexcel/PHPExcel.php');
		// Create new PHPExcel object
		
		$madeImports = false;
		$objReader = new PHPExcel_Reader_Excel2007();
		$objReader->setReadDataOnly(true);
		$objReader->setLoadSheetsOnly(array("Options", "options"));
		$objPHPExcel = $objReader->load($file);
		$progress['importingFile'] = substr($file, strripos($file, '/') + 1);
		$optionsSheet = 0;
		
		$optionsSheetObj = $objPHPExcel->setActiveSheetIndex($optionsSheet);
		
		$progress['all'] = -1; //(int)(($optionsSheetObj->getHighestRow() - 2)/$this->productSize);
		$this->setProgress($progress);
		
		$option_map = array(
			'option_id' 				=> 0,
			'name'						=> 1,
			'type'						=> 2,
			'sort_order'				=> 3,
			'option_value_id'			=> 4,
			'option_value_name'			=> 5,
			'option_value_image'		=> 6,
			'option_value_sort_order'	=> 7
		);
		
		$types = array(
			'Select' 			=> 'select',
			'Radio' 			=> 'radio',
			'Checkbox' 			=> 'checkbox',
			'Image' 			=> 'image',
			'Text' 				=> 'text',
			'Textarea' 			=> 'textarea',
			'File' 				=> 'file',
			'Date' 				=> 'date',
			'Time' 				=> 'time',
			'Date and Time' 	=> 'datetime'
		);
		
		$source = array(0,2 + ($progress['importedCount']));
		
		do {
			$this->custom_set_time_limit();
			
			$option_name = trim(strval($optionsSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $option_map['name']) . ($source[1]))->getValue()));
			$option_type = trim(strval($optionsSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $option_map['type']) . ($source[1]))->getValue()));
			
			if (!empty($option_name) && !empty($option_type)) {
				$option_id = (int)$optionsSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $option_map['option_id']) . ($source[1]))->getValue();
				
				if (array_key_exists($option_type, $types)) $option_type = $types[$option_type];
				
				$option_sort_order = (int)trim($optionsSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $option_map['sort_order']) . ($source[1]))->getValue());
				
				$option_values = array();
				
				do {
		    	$option_value_name = (string)trim(strval($optionsSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $option_map['option_value_name']) . ($source[1]))->getValue()));
		    
		    	if ($option_value_name !== '') {
		        $option_values[] = array(
		        		'option_value_id' => trim(strval($optionsSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $option_map['option_value_id']) . ($source[1]))->getValue())),
		      	  	'option_value_description' => array(
		            $language => array(
		              'name' => $option_value_name
		            )
		          ),
		          'image' => trim(strval($optionsSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $option_map['option_value_image']) . ($source[1]))->getValue())),
		          'sort_order' => (int)trim(strval($optionsSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $option_map['option_value_sort_order']) . ($source[1]))->getValue()))
		        );
		        
		        $source[1] += 1;
		        
		       	$option_name_for_next_option_value = trim(strval($optionsSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $option_map['name']) . ($source[1]))->getValue()));
		    	}

				} while ($option_value_name !== '' && $option_name_for_next_option_value === '');
				
				if (!empty($option_values)) $source[1] -= 1;
				
				$option = array(
					'option_description' => array(
						$language => array(
							'name' => $option_name
						)
					),
					'type' => $option_type,
					'sort_order' => $option_sort_order,
					'option_value' => $option_values
				);
				
				// Extras
				foreach ($this->extraGeneralFields['Options'] as $extra) {
					if (!empty($extra['name']) && !empty($extra['column_light'])) {
						$option[$extra['name']] = $optionsSheetObj->getCell($extra['column_light'] . $source[1])->getValue();	
					}
				}
				
				if (!$addAsNew) {
					$exists = false;
					$existsQuery = $this->db->query("SELECT option_id FROM `" . DB_PREFIX . "option` WHERE option_id = ".$option_id);
					
					$exists = $existsQuery->num_rows > 0;
							
					if ($exists) {
						$this->editOption($option_id, $option, $allLanguages);
					} else {
						$this->addOption($option_id, $option, $allLanguages);
					}
				} else {
					$this->addOption('', $option, $allLanguages);
				}
				
				$progress['current']++;
				$progress['importedCount']++;
				$madeImports = true;
				$this->setProgress($progress);
			}
			$source[1] += 1;
		} while (!empty($option_name) && !empty($option_type));
		$progress['done'] = true;
		if (!$madeImports) {
			$progress['importedCount'] = 0;
			array_shift($this->session->data['uploaded_files']);
		}
		$this->setProgress($progress);
		
		$this->config->set('config_language_id', $default_language);
	}
	
	public function exportXLSOptions($language, $destinationFolder = '', $optionNumber = 800, $export_filters = array()) {
		$this->language->load('extension/module/excelport');
		$this->folderCheck($destinationFolder);
	
		$progress = $this->getProgress();
		$progress['done'] = false;
		
		$file = IMODULE_ROOT . 'vendors/excelport/template_option.xlsx';
		
		$default_language = $this->config->get('config_language_id');
		$this->config->set('config_language_id', $language);
		require_once(IMODULE_ROOT.'vendors/phpexcel/PHPExcel.php');
		
		if (!empty($progress['populateAll'])) {
			$all = $this->db->query($this->getQuery($export_filters, $language, true));
			$progress['all'] = $all->num_rows ? (int)$all->row['count'] : 0;
			unset($progress['populateAll']);
			$this->setProgress($progress);
		}
		
		$this->setData('Options', $destinationFolder, $language);
		
		$optionsSheet = 0;
		$optionsMetaSheet = 1;
		
		$typesStart = array(0,2);
		
		$types = array(
			'select'	=> 'Select',
			'radio'		=> 'Radio',
			'checkbox'	=> 'Checkbox',
			'image'		=> 'Image',
			'text'		=> 'Text',
			'textarea'	=> 'Textarea',
			'file'		=> 'File',
			'date'		=> 'Date',
			'time'		=> 'Time',
			'datetime'	=> 'Date and Time'
		);
		
		$options_generals = array(
			'option_id' 				=> 0,
			'name'						=> 1,
			'type'						=> 2,
			'sort_order'				=> 3
		);
		
		$option_values_generals = array(
			'option_value_id'			=> 4,
			'option_value_name'			=> 5,
			'option_value_image'		=> 6,
			'option_value_sort_order'	=> 7
		);
		
		// Extra fields
		$extras = array();
		foreach ($this->extraGeneralFields['Options'] as $extra) {
			if (!empty($extra['name']) && !empty($extra['column_light'])) {
				$extras[$extra['name']] = $extra['column_light'];
			}
		}
		
		$optionsDataValidations = array(
			array(
				'type' => 'list',
				'field' => $options_generals['type'],
				'data' => array($typesStart[0], $typesStart[1], $typesStart[0], $typesStart[1] + count($types) - 1),
				'range' => '',
				'count' => count($types)
			)
		);
		
		$options_target = array(0,2);
		
		$this->load->model('localisation/language');
		$languageQuery = $this->model_localisation_language->getLanguage($this->config->get('config_language_id'));
		
		$name = 'options_excelport_' . $languageQuery['code'] . '_' . str_replace('/', '_', substr(HTTP_CATALOG, 7, strlen(HTTP_CATALOG) - 8)) . '_' . date("Y-m-d_H-i-s") . '_' . $progress['current'];
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
		
		$optionsMetaSheetObj = $objPHPExcel->setActiveSheetIndex($optionsMetaSheet);
		
		$typesKeys = array_keys($types);
		for ($i = 0; $i < count($types); $i++) {
			$optionsMetaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($typesStart[0]) . ($typesStart[1] + $i), $types[$typesKeys[$i]], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		
		$this->load->model('catalog/option');
		
		$extra_select = "";
		
		$this->db->query("SET SESSION group_concat_max_len = 1000000;");
		
		$options_result = $this->db->query($this->getQuery($export_filters, $language) . " ORDER BY o.option_id LIMIT ". $progress['current'] . ", " . $optionNumber);
		
		$optionsSheetObj = $objPHPExcel->setActiveSheetIndex($optionsSheet);
		
		foreach ($this->extraGeneralFields['Options'] as $extra) {
			if (!empty($extra['title']) && !empty($extra['column_light'])) {
				$optionsSheetObj->setCellValueExplicit($extra['column_light'] . '1', $extra['title'], PHPExcel_Cell_DataType::TYPE_STRING);
			}
		}
		
		if ($options_result->num_rows > 0) {
			foreach ($options_result->rows as $myOptionsIndex => $option_row) {
				
				$this->getData('Options', $option_row);
				
				// Prepare data
				$option_row['sort_order'] = empty($option_row['sort_order']) ? '0' : $option_row['sort_order'];
				if (empty($option_row['name'])) $option_row['name'] = '-';
				if (in_array($option_row['type'], $typesKeys)) $option_row['type'] = $types[$option_row['type']];
				
				// Add data
				// Extras
				foreach ($extras as $name => $position) {
					$optionsSheetObj->setCellValueExplicit($position . ($options_target[1]), empty($option_row[$name]) ? '' : $option_row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
				}
				// General
				foreach ($options_generals as $name => $position) {
					$optionsSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($options_target[0] + $position) . ($options_target[1]), empty($option_row[$name]) && $option_row[$name] !== '0' ? '' : $option_row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
				}
				
				// Data validations
				foreach ($optionsDataValidations as $dataValidationIndex => $dataValidation) {
					if (isset($optionsDataValidations[$dataValidationIndex]['count']) && $optionsDataValidations[$dataValidationIndex]['count'] == 0) continue;
					$optionsDataValidations[$dataValidationIndex]['range'] = PHPExcel_Cell::stringFromColumnIndex($options_target[0] + $dataValidation['field']) . ($options_target[1]);
					if (empty($optionsDataValidations[$dataValidationIndex]['root'])) $optionsDataValidations[$dataValidationIndex]['root'] = PHPExcel_Cell::stringFromColumnIndex($options_target[0] + $dataValidation['field']) . ($options_target[1]);
				}
				
				$option_values = $this->model_catalog_option->getOptionValues($option_row['option_id']);
				if (!empty($option_values)) {
					foreach ($option_values as $option_value) {
						$optionsSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($options_target[0] + $option_values_generals['option_value_id']) . ($options_target[1]), $option_value['option_value_id'], PHPExcel_Cell_DataType::TYPE_STRING);
						$optionsSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($options_target[0] + $option_values_generals['option_value_name']) . ($options_target[1]), $option_value['name'], PHPExcel_Cell_DataType::TYPE_STRING);
						$optionsSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($options_target[0] + $option_values_generals['option_value_image']) . ($options_target[1]), $option_value['image'], PHPExcel_Cell_DataType::TYPE_STRING);
						$optionsSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($options_target[0] + $option_values_generals['option_value_sort_order']) . ($options_target[1]), !empty($option_value['sort_order']) ? $option_value['sort_order'] : '0', PHPExcel_Cell_DataType::TYPE_STRING);
						$options_target[1] = $options_target[1] + 1;
					}
					$options_target[1] = $options_target[1] - 1;
				}
				
				$options_target[1] = $options_target[1] + 1;
				$progress['current']++;
				$progress['memory_get_usage'] = round(memory_get_usage(true)/(1024*1024));
				$progress['percent'] = 100 / ($options_result->num_rows / $progress['current']);
				
				$this->setProgress($progress);
			}
			
			foreach ($optionsDataValidations as $dataValidationIndex => $dataValidation) {
				if (isset($optionsDataValidations[$dataValidationIndex]['count']) && $optionsDataValidations[$dataValidationIndex]['count'] == 0) continue;
				if ($optionsDataValidations[$dataValidationIndex]['range'] != $optionsDataValidations[$dataValidationIndex]['root']) {
					$optionsDataValidations[$dataValidationIndex]['range'] = $optionsDataValidations[$dataValidationIndex]['root'] . ':' . $optionsDataValidations[$dataValidationIndex]['range'];
				}
			}
			
			//Apply data validation for:
			// Generals
			foreach ($optionsDataValidations as $dataValidation) {
				$range = trim($dataValidation['range']);
				if (isset($dataValidation['count']) && $dataValidation['count'] == 0) continue;
				if ($dataValidation['type'] == 'list' && !empty($dataValidation['root']) && !empty($range)) {
					$objValidation = $optionsSheetObj->getCell($dataValidation['root'])->getDataValidation();
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
					$objValidation->setFormula1($optionsMetaSheetObj->getTitle() . '!$' . PHPExcel_Cell::stringFromColumnIndex($dataValidation['data'][0]) . '$' . ($dataValidation['data'][1]) . ':$' . PHPExcel_Cell::stringFromColumnIndex($dataValidation['data'][2]) . '$' . ($dataValidation['data'][3]));
					$optionsSheetObj->setDataValidation($range, $objValidation);
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
		unset($optionsMetaSheetObj);
		unset($objWriter);
		unset($optionsSheetObj);
		unset($objPHPExcel);
		
		$progress['done'] = true;
		$this->setProgress($progress);
		
		return true;
	}
	public function getQuery($filters = array(), $language = 1, $count = false) {
		if (empty($filters) || !in_array($filters['Conjunction'], array('AND', 'OR'))) $filters['Conjunction'] = 'OR';
		
		$join_rules = array(
			'option_description' => "LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id AND od.language_id = '" . $language . "')",
			'option_value' => "JOIN " . DB_PREFIX . "option_value ov ON (ov.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ovd.option_id = od.option_id AND ovd.language_id = '" . $language . "')"
		);
		
		$joins = array();
		$joins['option_description'] = $join_rules['option_description'];
		
		$wheres = array();
		
		foreach ($filters as $i => $filter) {
			if (is_array($filter)) {
				if (!array_key_exists($this->conditions['Options'][$filter['Field']]['join_table'], $joins) && array_key_exists($this->conditions['Options'][$filter['Field']]['join_table'], $join_rules)) {
					$joins[$this->conditions['Options'][$filter['Field']]['join_table']] = $join_rules[$this->conditions['Options'][$filter['Field']]['join_table']];
				}
				$condition = str_replace(array('{FIELD_NAME}', '{WORD}'), array($this->conditions['Options'][$filter['Field']]['field_name'], stripos($this->conditions['Options'][$filter['Field']]['type'], 'number') !== FALSE ? (int)$this->db->escape($filter['Value']) : $this->db->escape($filter['Value'])), $this->operations[$filter['Condition']]['operation']);
				if (!in_array($condition, $wheres)) $wheres[] = $condition;
			}
		}
		
		$select = $count ? "COUNT(*)" : "*, od.name as name, o.*";
		
		$query = ($count ? "SELECT COUNT(*) as count FROM (" : "") . "SELECT " . $select . " FROM `" . DB_PREFIX . "option` o " . implode(" ", $joins) . " " . (!empty($wheres) ? " WHERE (" . implode(" " . $filters['Conjunction'] . " ", $wheres) . ")" : "") . " GROUP BY o.option_id" . ($count ? ") as count_table" : "");
		
		return $query;
	}
	public function addOption($option_id = '', $data, $allLanguages) {
		$this->addOptionLanguages($data, $allLanguages);
		
		$option_id = trim($option_id);
			
		$this->db->query("INSERT INTO `" . DB_PREFIX . "option` SET ".(!empty($option_id) ? "option_id = '" . (int)trim($option_id) . "', " : "")."type = '" . $this->db->escape($data['type']) . "', sort_order = '" . (int)$data['sort_order'] . "'");
		
		$option_id = $this->db->getLastId();
		
		$language_ids = array();
		foreach ($allLanguages as $language) {
			$language_ids[] = $language['language_id'];	
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "option_description WHERE option_id = '" . (int)$option_id . "' AND language_id NOT IN (" . implode(',', $language_ids) . ")");
		
		foreach ($data['option_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "option_description SET option_id = '" . (int)$option_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "' ON DUPLICATE KEY UPDATE name = '" . $this->db->escape($value['name']) . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "option_value WHERE option_id = '" . (int)$option_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "option_value_description WHERE option_id = '" . (int)$option_id . "' AND language_id NOT IN (" . implode(',', $language_ids) . ")");
		
		if (isset($data['option_value'])) {
			foreach ($data['option_value'] as $option_value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "option_value SET option_id = '" . (int)$option_id . "', image = '" . $this->db->escape(html_entity_decode($option_value['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$option_value['sort_order'] . "'");
				
				$option_value_id = $this->db->getLastId();
				
				foreach ($option_value['option_value_description'] as $language_id => $option_value_description) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "option_value_description SET option_value_id = '" . (int)$option_value_id . "', language_id = '" . (int)$language_id . "', option_id = '" . (int)$option_id . "', name = '" . $this->db->escape($option_value_description['name']) . "' ON DUPLICATE KEY UPDATE name = '" . $this->db->escape($option_value_description['name']) . "'");
				}
			}
		}
		
		// Extras
		foreach ($this->extraGeneralFields['Options'] as $extra) {
			if (!empty($extra['eval_add'])) {
				eval($extra['eval_add']);
			}
		}
		
		$this->cache->delete('option');
	}
	
	public function editOption($option_id, $data, &$languages) {
		$this->db->query("UPDATE `" . DB_PREFIX . "option` SET type = '" . $this->db->escape($data['type']) . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE option_id = '" . (int)$option_id . "'");
		
		$language_ids = array();
		foreach ($languages as $language) {
			$language_ids[] = $language['language_id'];	
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "option_description WHERE option_id = '" . (int)$option_id . "' AND language_id NOT IN (" . implode(',', $language_ids) . ")");

		foreach ($data['option_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "option_description SET option_id = '" . (int)$option_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "' ON DUPLICATE KEY UPDATE name = '" . $this->db->escape($value['name']) . "'");
		}
				
		$this->db->query("DELETE FROM " . DB_PREFIX . "option_value WHERE option_id = '" . (int)$option_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "option_value_description WHERE option_id = '" . (int)$option_id . "' AND language_id NOT IN (" . implode(',', $language_ids) . ")");
		
		if (isset($data['option_value'])) {
			foreach ($data['option_value'] as $option_value) {
				if ($option_value['option_value_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "option_value SET option_value_id = '" . (int)$option_value['option_value_id'] . "', option_id = '" . (int)$option_id . "', image = '" . $this->db->escape(html_entity_decode($option_value['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$option_value['sort_order'] . "'");
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "option_value SET option_id = '" . (int)$option_id . "', image = '" . $this->db->escape(html_entity_decode($option_value['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$option_value['sort_order'] . "'");
				}
				
				$option_value_id = $this->db->getLastId();
				
				foreach ($option_value['option_value_description'] as $language_id => $option_value_description) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "option_value_description SET option_value_id = '" . (int)$option_value_id . "', language_id = '" . (int)$language_id . "', option_id = '" . (int)$option_id . "', name = '" . $this->db->escape($option_value_description['name']) . "' ON DUPLICATE KEY UPDATE name = '" . $this->db->escape($option_value_description['name']) . "'");
				}
			}
		}
		
		// Extras
		foreach ($this->extraGeneralFields['Options'] as $extra) {
			if (!empty($extra['eval_edit'])) {
				eval($extra['eval_edit']);
			}
		}
		
		$this->cache->delete('option');
	}
	public function addOptionLanguages(&$data, $allLanguages) {
		// Add Option Description Languages
		if (!empty($data['option_description'])) {
			$entered_keys = array_keys($data['option_description']);
			foreach ($allLanguages as $language) {
				if (!in_array($language['language_id'], $entered_keys)) {
					$data['option_description'][$language['language_id']] = array(
						'name' => $data['option_description'][$entered_keys[0]]['name']
					);
				}
			}
		}
		
		// Add Option Value Description Languages
		foreach ($data['option_value'] as &$option_value) {
			if (!empty($option_value['option_value_description'])) {
				$entered_keys = array_keys($option_value['option_value_description']);
				foreach ($allLanguages as $language) {
					if (!in_array($language['language_id'], $entered_keys)) {
						$option_value['option_value_description'][$language['language_id']] = array(
							'name' => $option_value['option_value_description'][$entered_keys[0]]['name']
						);
					}
				}
			}
		}
	}
	public function deleteOptions() {
		$this->load->model('catalog/option');
		
		$ids = $this->db->query("SELECT option_id FROM `" . DB_PREFIX . "option`");
		
		foreach ($ids->rows as $row) {
			$this->model_catalog_option->deleteOption($row['option_id']);	
		}
	}
}
?>
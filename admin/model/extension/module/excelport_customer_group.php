<?php 
class ModelExtensionModuleExcelportcustomergroup extends ModelExtensionModuleExcelport {
	public function importXLSCustomerGroups($language, $allLanguages, $file, $importLimit = 100, $addAsNew = false) {
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
		$chunkFilter = new CustomReadFilter(array('CustomerGroups' => array('A', ($progress['importedCount'] + 2), 'AM', (($progress['importedCount'] + $importLimit) + 1)), 'customergroups' => array('A', ($progress['importedCount'] + 2), 'AM', (($progress['importedCount'] + $importLimit) + 1))), true); 
		
		$madeImports = false;
		$objReader = new PHPExcel_Reader_Excel2007();
		$objReader->setReadFilter($chunkFilter);
		$objReader->setReadDataOnly(true);
		$objReader->setLoadSheetsOnly(array("CustomerGroups", "customergroups"));
		$objPHPExcel = $objReader->load($file);
		$progress['importingFile'] = substr($file, strripos($file, '/') + 1);
		$customerGroupsSheet = 0;
		
		$customerGroupSheetObj = $objPHPExcel->setActiveSheetIndex($customerGroupsSheet);
		
		$progress['all'] = -1; //(int)(($customerGroupSheetObj->getHighestRow() - 2)/$this->customer_groupSize);
		$this->setProgress($progress);
		
        if (version_compare(VERSION, '2.1.0.1', '>=')) {
		    $this->load->model('customer/customer_group');
        } else {
            $this->load->model('sale/customer_group');
        }
		
		$map = array(
			'customer_group_id' 	=> 0,
			'name'					=> 1,
			'description'			=> 2,
			'approval'				=> 3,
			'sort_order'			=> 8
		);
		
		$source = array(0,2 + ($progress['importedCount']));
			
		do {
			$this->custom_set_time_limit();
			
			$customer_group_name = strval($customerGroupSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['name']) . ($source[1]))->getValue());
			if (!empty($customer_group_name)) {
				
				$customer_group_id = (int)trim($customerGroupSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['customer_group_id']) . ($source[1]))->getValue());
				
				$customer_group_approval = $customerGroupSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['approval']) . ($source[1]))->getValue() == 'Enabled' ? 1 : 0;
				
				
				$customer_group_sort_order = (int)trim($customerGroupSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['sort_order']) . ($source[1]))->getValue());
				
				$customer_group = array(
					'customer_group_id' => $customer_group_id,
					'customer_group_description' => array(
						$language => array(
							'name' => $customer_group_name,
							'description' => strval($customerGroupSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['description']) . ($source[1]))->getValue())
						)
					),
					'approval' => $customer_group_approval,
					'sort_order' => $customer_group_sort_order
				);
				
				// Extras
				foreach ($this->extraGeneralFields['CustomerGroups'] as $extra) {
					if (!empty($extra['name']) && !empty($extra['column_light'])) {
						$customer_group[$extra['name']] = $customerGroupSheetObj->getCell($extra['column_light'] . $source[1])->getValue();	
					}
				}
				
				if (!$addAsNew) {
					$exists = false;
					$existsQuery = $this->db->query("SELECT customer_group_id FROM " . DB_PREFIX . "customer_group WHERE customer_group_id = ".$customer_group_id);
					
					$exists = $existsQuery->num_rows > 0;
							
					if ($exists) {
						$this->editCustomerGroup($customer_group_id, $customer_group, $allLanguages);
					} else {
						$this->addCustomerGroup($customer_group_id, $customer_group, $allLanguages);
					}
				} else {
					$this->addCustomerGroup('', $customer_group, $allLanguages);
				}
				
				$progress['current']++;
				$progress['importedCount']++;
				$madeImports = true;
				$this->setProgress($progress);
			}
			$source[1] += 1;
		} while (!empty($customer_group_name));
		$progress['done'] = true;
		if (!$madeImports) {
			$progress['importedCount'] = 0;
			array_shift($this->session->data['uploaded_files']);
		}
		$this->setProgress($progress);
		
		$this->config->set('config_language_id', $default_language);	
	}
	
	public function exportXLSCustomerGroups($language, $destinationFolder = '', $customerGroupNumber, $export_filters = array()) {
		$this->language->load('extension/module/excelport');
		$this->folderCheck($destinationFolder);
		
		$progress = $this->getProgress();
		$progress['done'] = false;
		
		$file = IMODULE_ROOT . 'vendors/excelport/template_customer_group.xlsx';
		
		$default_language = $this->config->get('config_language_id');
		$this->config->set('config_language_id', $language);
		require_once(IMODULE_ROOT.'vendors/phpexcel/PHPExcel.php');
		
		if (!empty($progress['populateAll'])) {
			$all = $this->db->query($this->getQuery($export_filters, $language, true));
			
			$progress['all'] = $all->num_rows ? (int)$all->row['count'] : 0;
			unset($progress['populateAll']);
			$this->setProgress($progress);
		}
		
		$customerGroupsSheet = 0;
		$metaSheet = 1;
		
		$generals = array(
			'customer_group_id' 	=> 0,
			'name'					=> 1,
			'description'			=> 2,
			'approval'				=> 3,
			'sort_order'			=> 8
		);
		
		// Extra fields
		$extras = array();
		foreach ($this->extraGeneralFields['CustomerGroups'] as $extra) {
			if (!empty($extra['name']) && !empty($extra['column_light'])) {
				$extras[$extra['name']] = $extra['column_light'];
			}
		}
		
		$dataValidations = array(
			array(
				'type' => 'list',
				'field' => $generals['approval'],
				'data' => array(0,2,0,3),
				'range' => '',
			)
		);
		
		$target = array(0,2);
		
		$this->load->model('localisation/language');
		$languageQuery = $this->model_localisation_language->getLanguage($this->config->get('config_language_id'));
		
		$name = 'customer_groups_excelport_' . $languageQuery['code'] . '_' . str_replace('/', '_', substr(HTTP_CATALOG, 7, strlen(HTTP_CATALOG) - 8)) . '_' . date("Y-m-d_H-i-s") . '_' . $progress['current'];
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
		
        if (version_compare(VERSION, '2.1.0.1', '>=')) {
            $this->load->model('customer/customer_group');
        } else {
            $this->load->model('sale/customer_group');
        }
		
		$extra_select = "";
		
		$this->db->query("SET SESSION group_concat_max_len = 1000000;");
		
		$customer_groups = $this->db->query($this->getQuery($export_filters, $language) . " ORDER BY cg.customer_group_id LIMIT ". $progress['current'] . ", " . $customerGroupNumber);
		
		$customerGroupSheetObj = $objPHPExcel->setActiveSheetIndex($customerGroupsSheet);
		
		foreach ($this->extraGeneralFields['CustomerGroups'] as $extra) {
			if (!empty($extra['title']) && !empty($extra['column_light'])) {
				$customerGroupSheetObj->setCellValueExplicit($extra['column_light'] . '1', $extra['title'], PHPExcel_Cell_DataType::TYPE_STRING);
			}
		}
		
		if ($customer_groups->num_rows > 0) {
			foreach ($customer_groups->rows as $myCustomerGroupIndex => $row) {
				
				//$this->getData('CustomerGroups', $row);
				
				// Prepare data
				$row['approval'] = empty($row['approval']) ? 'Disabled' : 'Enabled';
				$row['description'] = !empty($row['description']) ? $row['description'] : '';
				$row['sort_order'] = !empty($row['sort_order']) ? $row['sort_order'] : '0';
				$row['name'] = !empty($row['name']) ? $row['name'] : '-';
				
				// Add data
				// Extras
				foreach ($extras as $name => $position) {
					$customerGroupSheetObj->setCellValueExplicit($position . ($target[1]), empty($row[$name]) ? '' : $row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
				}
				// General
				foreach ($generals as $name => $position) {
					$customerGroupSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target[0] + $position) . ($target[1]), empty($row[$name]) && $row[$name] !== '0' ? '' : $row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
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
				$progress['percent'] = 100 / ($customer_groups->num_rows / $progress['current']);
				
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
					$objValidation = $customerGroupSheetObj->getCell($dataValidation['root'])->getDataValidation();
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
					$customerGroupSheetObj->setDataValidation($range, $objValidation);
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
		unset($customerGroupSheetObj);
		unset($objPHPExcel);
		
		$progress['done'] = true;
		$this->setProgress($progress);
		
		return true;
	}
	
	public function getQuery($filters = array(), $language = 1, $count = false) {
		if (empty($filters) || !in_array($filters['Conjunction'], array('AND', 'OR'))) $filters['Conjunction'] = 'OR';
		
		$join_rules = array(
			'customer_group_description' => "LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id AND cgd.language_id = '" . $language . "')"
		);
		
		$joins = array();
		
		if (version_compare(VERSION, '1.5.3', '>=')) {
			$joins['customer_group_description'] = $join_rules['customer_group_description'];
		}
		
		$wheres = array();
		
		foreach ($filters as $i => $filter) {
			if (is_array($filter)) {
				if (!array_key_exists($this->conditions['CustomerGroups'][$filter['Field']]['join_table'], $joins) && array_key_exists($this->conditions['CustomerGroups'][$filter['Field']]['join_table'], $join_rules)) {
					$joins[$this->conditions['CustomerGroups'][$filter['Field']]['join_table']] = $join_rules[$this->conditions['CustomerGroups'][$filter['Field']]['join_table']];
				}
				if (!is_array($this->conditions['CustomerGroups'][$filter['Field']]['field_name'])) {
					$condition = str_replace(array('{FIELD_NAME}', '{WORD}'), array($this->conditions['CustomerGroups'][$filter['Field']]['field_name'], stripos($this->conditions['CustomerGroups'][$filter['Field']]['type'], 'number') !== FALSE ? (int)$this->db->escape($filter['Value']) : $this->db->escape($filter['Value'])), $this->operations[$filter['Condition']]['operation']);
				} else {
					$sub_conditions = array();
					foreach ($this->conditions['CustomerGroups'][$filter['Field']]['field_name'] as $field_name) {
						$sub_conditions[] = str_replace(array('{FIELD_NAME}', '{WORD}'), array($field_name, stripos($this->conditions['CustomerGroups'][$filter['Field']]['type'], 'number') !== FALSE ? (int)$this->db->escape($filter['Value']) : $this->db->escape($filter['Value'])), $this->operations[$filter['Condition']]['operation']);
					}
					$condition = '(' . implode(' OR ', $sub_conditions) . ')';
				}
				if (!in_array($condition, $wheres)) $wheres[] = $condition;
			}
		}
		
		$select = $count ? "COUNT(*)" : "*, " . (version_compare(VERSION, '1.5.3', '<') ? 'cg.name as name' : 'cgd.name as name') . ", cg.*";
		
		$query = ($count ? "SELECT COUNT(*) as count FROM (" : "") . "SELECT " . $select . " FROM " . DB_PREFIX . "customer_group cg " . implode(" ", $joins) . " " . (!empty($wheres) ? " WHERE (" . implode(" " . $filters['Conjunction'] . " ", $wheres) . ")" : "") . " GROUP BY cg.customer_group_id" . ($count ? ") as count_table" : "");
		
		return $query;
	}
	
	public function addCustomerGroup($customer_group_id = '', $data, $allLanguages) {
		$this->addCustomerGroupLanguages($data, $allLanguages);
		$customer_group_id = trim($customer_group_id);
		
		if (version_compare(VERSION, '1.5.3', '<')) {
			foreach ($data['customer_group_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "customer_group SET ".(!empty($customer_group_id) ? "customer_group_id = '" . (int)trim($customer_group_id) . "', " : "")."name = '" . $this->db->escape($value['name']) . "'");
			}
		} else {
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_group SET ".(!empty($customer_group_id) ? "customer_group_id = '" . (int)trim($customer_group_id) . "', " : "")."approval = '" . (int)$data['approval'] . "', sort_order = '" . (int)$data['sort_order'] . "'");
			
			$customer_group_id = $this->db->getLastId();
			
			$language_ids = array();
			foreach ($allLanguages as $language) {
				$language_ids[] = $language['language_id'];	
			}
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "customer_group_description WHERE customer_group_id = '" . (int)$customer_group_id . "' AND language_id NOT IN (" . implode(',', $language_ids) . ")");
			
			foreach ($data['customer_group_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "customer_group_description SET customer_group_id = '" . (int)$customer_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "' ON DUPLICATE KEY UPDATE name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'");
			}
		}
		
		// Extras
		foreach ($this->extraGeneralFields['CustomerGroups'] as $extra) {
			if (!empty($extra['eval_add'])) {
				eval($extra['eval_add']);
			}
		}
		
		$this->cache->delete('customer_group');
	}
	
	public function editCustomerGroup($customer_group_id, $data, &$languages) {
		$customer_group_id = trim($customer_group_id);
		
		if (version_compare(VERSION, '1.5.3', '<')) {
			foreach ($data['customer_group_description'] as $language_id => $value) {
				$this->db->query("UPDATE " . DB_PREFIX . "customer_group SET name = '" . $this->db->escape($value['name']) . "' WHERE customer_group_id='" . (int)trim($customer_group_id) . "'");
			}
		} else {
			$this->db->query("UPDATE " . DB_PREFIX . "customer_group SET approval = '" . (int)$data['approval'] . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE customer_group_id='" . (int)trim($customer_group_id) . "'");
			
			$language_ids = array();
			foreach ($languages as $language) {
				$language_ids[] = $language['language_id'];	
			}
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "customer_group_description WHERE customer_group_id = '" . (int)$customer_group_id . "' AND language_id NOT IN (" . implode(',', $language_ids) . ")");
			
			foreach ($data['customer_group_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "customer_group_description SET customer_group_id = '" . (int)$customer_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "' ON DUPLICATE KEY UPDATE name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'");
			}
		}
		
		// Extras
		foreach ($this->extraGeneralFields['CustomerGroups'] as $extra) {
			if (!empty($extra['eval_edit'])) {
				eval($extra['eval_edit']);
			}
		}
		
		$this->cache->delete('customer_group');
	}
	
	public function addCustomerGroupLanguages(&$data, $allLanguages) {
		// Add Product Description Languages
		if (!empty($data['customer_group_description'])) {
			$entered_keys = array_keys($data['customer_group_description']);
			foreach ($allLanguages as $language) {
				if (!in_array($language['language_id'], $entered_keys)) {
					$data['customer_group_description'][$language['language_id']] = array(
						'name' => $data['customer_group_description'][$entered_keys[0]]['name'],
						'description' => $data['customer_group_description'][$entered_keys[0]]['description']
					);
				}
			}
		}
	}
	
	public function deleteCustomerGroups() {
        if (version_compare(VERSION, '2.1.0.1', '>=')) {
		    $this->load->model('customer/customer_group');
        } else {
            $this->load->model('sale/customer_group');
        }
		
		$ids = $this->db->query("SELECT customer_group_id FROM " . DB_PREFIX . "customer_group c");
		
		foreach ($ids->rows as $row) {
            if (version_compare(VERSION, '2.1.0.1', '>=')) {
                $this->model_customer_customer_group->deleteCustomerGroup($row['customer_group_id']);
            } else {
                $this->model_sale_customer_group->deleteCustomerGroup($row['customer_group_id']);
            }
		}
	}
}
?>
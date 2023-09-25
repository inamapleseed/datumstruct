<?php 
class ModelExtensionModuleExcelportattribute extends ModelExtensionModuleExcelport {
	public function importXLSAttributes($language, $allLanguages, $file, $addAsNew = false) {
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
		$objReader->setLoadSheetsOnly(array("Attributes", "Attribute Groups", "attributes", "attribute groups"));
		$objPHPExcel = $objReader->load($file);
		$progress['importingFile'] = substr($file, strripos($file, '/') + 1);
		$attributesSheet = 0;
		$attributeGroupsSheet = 1;
		
		$attributesSheetObj = $objPHPExcel->setActiveSheetIndex($attributesSheet);
		$attributeGroupsSheetObj = $objPHPExcel->setActiveSheetIndex($attributeGroupsSheet);
		
		$progress['all'] = -1; //(int)(($attributesSheetObj->getHighestRow() - 2)/$this->productSize);
		$this->setProgress($progress);
		
		$attribute_map = array(
			'attribute_id' 		=> 0,
			'name'				=> 1,
			'attribute_group_id'=> 2,
			'sort_order'		=> 3
		);
		
		$attribute_group_map = array(
			'attribute_group_id'=> 0,
			'name'				=> 1,
			'sort_order'		=> 2
		);
		
		$source1 = array(0,2);
		$source2 = array(0,2);
		
		do {
			$this->custom_set_time_limit();
			$attribute_group_name = strval($attributeGroupsSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source2[0] + $attribute_group_map['name']) . ($source2[1]))->getValue());
			if (!empty($attribute_group_name)) {
				$attribute_group_id = $attributeGroupsSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source2[0] + $attribute_group_map['attribute_group_id']) . ($source2[1]))->getValue();
				
				$attribute_sort_order = (int)str_replace(' ', '', $attributeGroupsSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source2[0] + $attribute_group_map['sort_order']) . ($source2[1]))->getValue());
				
				$attribute_group = array(
					'attribute_group_description' => array(
						$language => array(
							'name' => $attribute_group_name
						)
					),
					'sort_order' => $attribute_sort_order
				);
				
				// Extras
				foreach ($this->extraGeneralFields['AttributeGroups'] as $extra) {
					if (!empty($extra['name']) && !empty($extra['column_light'])) {
						$attribute_group[$extra['name']] = $attributeGroupsSheetObj->getCell($extra['column_light'] . $source2[1])->getValue();	
					}
				}
				
				if (!empty($attribute_group_id)) {
					$exists = false;
					$existsQuery = $this->db->query("SELECT attribute_group_id FROM " . DB_PREFIX . "attribute_group WHERE attribute_group_id = ".$attribute_group_id);
					
					$exists = $existsQuery->num_rows > 0;
							
					if ($exists) {
						$this->editAttributeGroup($attribute_group_id, $attribute_group, $allLanguages);
					} else {
						$this->addAttributeGroup($attribute_group_id, $attribute_group, $allLanguages);
					}
				} else {
					$this->addAttributeGroup('', $attribute_group, $allLanguages);
				}
				
				$progress['current']++;
				$madeImports = true;
				$this->setProgress($progress);
			}
			$source2[1] += 1;
		} while (!empty($attribute_group_name));
		
		do {
			$this->custom_set_time_limit();
			$attribute_name = strval($attributesSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source1[0] + $attribute_map['name']) . ($source1[1]))->getValue());
			if (!empty($attribute_name)) {
				$attribute_id = (int)$attributesSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source1[0] + $attribute_map['attribute_id']) . ($source1[1]))->getValue();
				$attribute_group_id = $attributesSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source1[0] + $attribute_map['attribute_group_id']) . ($source1[1]))->getValue();
				$attribute_sort_order = (int)str_replace(' ', '', $attributesSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source1[0] + $attribute_map['sort_order']) . ($source1[1]))->getValue());
				
				$attribute = array(
					'attribute_description' => array(
						$language => array(
							'name' => $attribute_name
						)
					),
					'attribute_group_id' => $attribute_group_id,
					'sort_order' => $attribute_sort_order
				);
				
				// Extras
				foreach ($this->extraGeneralFields['Attributes'] as $extra) {
					if (!empty($extra['name']) && !empty($extra['column_light'])) {
						$attribute[$extra['name']] = $attributesSheetObj->getCell($extra['column_light'] . $source1[1])->getValue();	
					}
				}
				
				if (!$addAsNew) {
					$exists = false;
					$existsQuery = $this->db->query("SELECT attribute_id FROM " . DB_PREFIX . "attribute WHERE attribute_id = ".$attribute_id);
					
					$exists = $existsQuery->num_rows > 0;
							
					if ($exists) {
						$this->editAttribute($attribute_id, $attribute, $allLanguages);
					} else {
						$this->addAttribute($attribute_id, $attribute, $allLanguages);
					}
				} else {
					$this->addAttribute('', $attribute, $allLanguages);
				}
				
				$progress['current']++;
				$madeImports = true;
				$this->setProgress($progress);
			}
			$source1[1] += 1;
		} while (!empty($attribute_name));
		
		$progress['done'] = true;
		$progress['importedCount'] = 0;
		array_shift($this->session->data['uploaded_files']);
		
		$this->setProgress($progress);
		
		$this->config->set('config_language_id', $default_language);
	}
	
	public function exportXLSAttributes($language, $destinationFolder = '', $export_filters) {
		$this->language->load('extension/module/excelport');
		$this->folderCheck($destinationFolder);
	
		$progress = $this->getProgress();
		$progress['done'] = false;
		
		$file = IMODULE_ROOT . 'vendors/excelport/template_attribute.xlsx';
		
		$default_language = $this->config->get('config_language_id');
		$this->config->set('config_language_id', $language);
		require_once(IMODULE_ROOT.'vendors/phpexcel/PHPExcel.php');
		
		if (!empty($progress['populateAll'])) {
			$all = $this->db->query($this->getAttributesQuery($export_filters, $language, true));
			$progress['all'] = $all->num_rows ? (int)$all->row['count'] : 0;
			unset($progress['populateAll']);
			$this->setProgress($progress);
		}
		
		$this->setData('Attributes', $destinationFolder, $language);
		$this->setData('AttributeGroups', $destinationFolder, $language);		
		
		$attributesSheet = 0;
		$attributeGroupsSheet = 1;
		
		$attribute_map = array(
			'attribute_id' 		=> 0,
			'name'				=> 1,
			'attribute_group_id'=> 2,
			'sort_order'		=> 3
		);
		
		$attribute_group_map = array(
			'attribute_group_id'=> 0,
			'name'				=> 1,
			'sort_order'		=> 2
		);
		
		// Extra fields
		$extras = array();
		foreach ($this->extraGeneralFields['Attributes'] as $extra) {
			if (!empty($extra['name']) && !empty($extra['column_light'])) {
				$extras[$extra['name']] = $extra['column_light'];
			}
		}
		
		// Extra fields
		$extras_groups = array();
		foreach ($this->extraGeneralFields['AttributeGroups'] as $extra) {
			if (!empty($extra['name']) && !empty($extra['column_light'])) {
				$extras_groups[$extra['name']] = $extra['column_light'];
			}
		}
		
		$attributes_target = array(0,2);
		
		$this->load->model('localisation/language');
		$languageQuery = $this->model_localisation_language->getLanguage($this->config->get('config_language_id'));
		
		$name = 'attributes_excelport_' . $languageQuery['code'] . '_' . str_replace('/', '_', substr(HTTP_CATALOG, 7, strlen(HTTP_CATALOG) - 8)) . '_' . date("Y-m-d_H-i-s") . '_' . $progress['current'];
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
		
		$attributesSheetObj = $objPHPExcel->setActiveSheetIndex($attributesSheet);
		$attributeGroupsSheetObj = $objPHPExcel->setActiveSheetIndex($attributeGroupsSheet);
		
		$extra_select = "";
		
		$this->db->query("SET SESSION group_concat_max_len = 1000000;");
		
		$attributes_result = $this->db->query($this->getAttributesQuery($export_filters, $language) . " ORDER BY a.attribute_id");
		
		foreach ($this->extraGeneralFields['Attributes'] as $extra) {
			if (!empty($extra['title']) && !empty($extra['column_light'])) {
				$attributesSheetObj->setCellValueExplicit($extra['column_light'] . '1', $extra['title'], PHPExcel_Cell_DataType::TYPE_STRING);
			}
		}
		
		if ($attributes_result->num_rows > 0) {
			foreach ($attributes_result->rows as $myAttributesIndex => $attribute_row) {
				
				$this->getData('Attributes', $attribute_row);
				$attribute_row['sort_order'] = empty($attribute_row['sort_order']) ? '0' : $attribute_row['sort_order'];
				if (empty($attribute_row['name'])) $attribute_row['name'] = '-';
				
				// Add data
				// Extras
				foreach ($extras as $name => $position) {
					$attributesSheetObj->setCellValueExplicit($position . ($attributes_target[1]), empty($attribute_row[$name]) ? '' : $attribute_row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
				}
				// General
				foreach ($attribute_map as $name => $position) {
					$attributesSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($attributes_target[0] + $position) . ($attributes_target[1]), empty($attribute_row[$name]) && $attribute_row[$name] !== '0' ? '' : $attribute_row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
				}
				
				$attributes_target[1] = $attributes_target[1] + 1;
				$progress['current']++;
				$progress['memory_get_usage'] = round(memory_get_usage(true)/(1024*1024));
				$progress['percent'] = 100 / ($attributes_result->num_rows / $progress['current']);
				
				$this->setProgress($progress);
			}
		} else {
			$progress['done'] = true;
		}
		
		$attribute_groups_target = array(0,2);
		
		$attribute_groups_result = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_group ag LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id AND agd.language_id = '" . $language . "') GROUP BY ag.attribute_group_id ORDER BY ag.attribute_group_id");
		
		foreach ($this->extraGeneralFields['AttributeGroups'] as $extra) {
			if (!empty($extra['title']) && !empty($extra['column_light'])) {
				$attributesSheetObj->setCellValueExplicit($extra['column_light'] . '1', $extra['title'], PHPExcel_Cell_DataType::TYPE_STRING);
			}
		}
		
		$all = $this->db->query("SELECT DISTINCT ag.attribute_group_id, COUNT(*) AS total FROM " . DB_PREFIX . "attribute_group ag");
		$progress['all'] = (int)$all->row['total'];
		$progress['current'] = 0;
		
		if ($attribute_groups_result->num_rows > 0) {
			foreach ($attribute_groups_result->rows as $myAttributeGroupsIndex => $attribute_group_row) {
				
				$this->getData('AttributeGroups', $attribute_group_row);
				$attribute_group_row['sort_order'] = empty($attribute_group_row['sort_order']) ? '0' : $attribute_group_row['sort_order'];
				if (empty($attribute_group_row['name'])) $attribute_group_row['name'] = '-';
				
				// Add data
				// Extras
				foreach ($extras_groups as $name => $position) {
					$attributeGroupsSheetObj->setCellValueExplicit($position . ($attribute_groups_target[1]), empty($attribute_group_row[$name]) ? '' : $attribute_group_row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
				}
				// General
				foreach ($attribute_group_map as $name => $position) {
					$attributeGroupsSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($attribute_groups_target[0] + $position) . ($attribute_groups_target[1]), empty($attribute_group_row[$name]) && $attribute_group_row[$name] !== '0' ? '' : $attribute_group_row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
				}
				
				$attribute_groups_target[1] = $attribute_groups_target[1] + 1;
				$progress['current']++;
				$progress['memory_get_usage'] = round(memory_get_usage(true)/(1024*1024));
				$progress['percent'] = 100 / ($attribute_groups_result->num_rows / $progress['current']);
				
				$this->setProgress($progress);
			}
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
		unset($attributeGroupsSheetObj);
		unset($objWriter);
		unset($attributesSheetObj);
		unset($objPHPExcel);
		
		$progress['done'] = true;
		$this->setProgress($progress);
		
		return true;
	}
	public function getAttributesQuery($filters = array(), $language = 1, $count = false) {
		if (empty($filters) || !in_array($filters['Conjunction'], array('AND', 'OR'))) $filters['Conjunction'] = 'OR';
		
		$join_rules = array(
			'attribute_group' => "LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id)",
			'attribute_description' => "LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id AND ad.language_id = '" . $language . "')",
			'attribute_group_description' => "LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id AND agd.language_id = '" . $language . "')"
		);
		
		$joins = array();
		$joins['attribute_group'] = $join_rules['attribute_group'];
		$joins['attribute_description'] = $join_rules['attribute_description'];
		$joins['attribute_group_description'] = $join_rules['attribute_group_description'];
		
		$wheres = array();
		
		foreach ($filters as $i => $filter) {
			if (is_array($filter)) {
				if (!array_key_exists($this->conditions['Attributes'][$filter['Field']]['join_table'], $joins) && array_key_exists($this->conditions['Attributes'][$filter['Field']]['join_table'], $join_rules)) {
					$joins[$this->conditions['Attributes'][$filter['Field']]['join_table']] = $join_rules[$this->conditions['Attributes'][$filter['Field']]['join_table']];
				}
				$condition = str_replace(array('{FIELD_NAME}', '{WORD}'), array($this->conditions['Attributes'][$filter['Field']]['field_name'], stripos($this->conditions['Attributes'][$filter['Field']]['type'], 'number') !== FALSE ? (int)$this->db->escape($filter['Value']) : $this->db->escape($filter['Value'])), $this->operations[$filter['Condition']]['operation']);
				if (!in_array($condition, $wheres)) $wheres[] = $condition;
			}
		}
		
		$select = $count ? "COUNT(*)" : "*, ad.name AS name, a.*";
		
		$query = ($count ? "SELECT COUNT(*) as count FROM (" : "") . "SELECT " . $select . " FROM " . DB_PREFIX . "attribute a " . implode(" ", $joins) . " " . (!empty($wheres) ? " WHERE (" . implode(" " . $filters['Conjunction'] . " ", $wheres) . ")" : "") . " GROUP BY a.attribute_id" . ($count ? ") as count_table" : "");
		
		return $query;
	}
	
	public function addAttributeGroup($attribute_group_id = '', $data, $allLanguages) {
		$this->addAttributeGroupLanguages($data, $allLanguages);
		
		$attribute_group_id = trim($attribute_group_id);
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_group SET ".(!empty($attribute_group_id) ? "attribute_group_id = '" . (int)trim($attribute_group_id) . "', " : "")."sort_order = '" . (int)$data['sort_order'] . "'");
		
		$attribute_group_id = $this->db->getLastId();
		
		foreach ($data['attribute_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_group_description SET attribute_group_id = '" . (int)$attribute_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		
		// Extras
		foreach ($this->extraGeneralFields['AttributeGroups'] as $extra) {
			if (!empty($extra['eval_add'])) {
				eval($extra['eval_add']);
			}
		}
	}

	public function editAttributeGroup($attribute_group_id, $data, &$languages) {
		$this->db->query("UPDATE " . DB_PREFIX . "attribute_group SET sort_order = '" . (int)$data['sort_order'] . "' WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");
		
		$language_ids = array();
		foreach ($languages as $language) {
			$language_ids[] = $language['language_id'];	
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "attribute_group_description WHERE attribute_group_id = '" . (int)$attribute_group_id . "' AND language_id NOT IN (" . implode(',', $language_ids) . ")");

		foreach ($data['attribute_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_group_description SET attribute_group_id = '" . (int)$attribute_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "' ON DUPLICATE KEY UPDATE name = '" . $this->db->escape($value['name']) . "'");
		}
		
		// Extras
		foreach ($this->extraGeneralFields['AttributeGroups'] as $extra) {
			if (!empty($extra['eval_edit'])) {
				eval($extra['eval_edit']);
			}
		}
	}
	public function addAttribute($attribute_id = '', $data, $allLanguages) {
		$this->addAttributeLanguages($data, $allLanguages);
		
		$attribute_id = trim($attribute_id);
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "attribute SET ".(!empty($attribute_id) ? "attribute_id = '" . (int)trim($attribute_id) . "', " : "")."attribute_group_id = '" . (int)$data['attribute_group_id'] . "', sort_order = '" . (int)$data['sort_order'] . "'");
		
		$attribute_id = $this->db->getLastId();
		
		$language_ids = array();
		foreach ($allLanguages as $language) {
			$language_ids[] = $language['language_id'];	
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "attribute_description WHERE attribute_id = '" . (int)$attribute_id . "' AND language_id NOT IN (" . implode(',', $language_ids) . ")");
		
		foreach ($data['attribute_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_description SET attribute_id = '" . (int)$attribute_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "' ON DUPLICATE KEY UPDATE name = '" . $this->db->escape($value['name']) . "'");
		}
		
		// Extras
		foreach ($this->extraGeneralFields['Attributes'] as $extra) {
			if (!empty($extra['eval_add'])) {
				eval($extra['eval_add']);
			}
		}
	}

	public function editAttribute($attribute_id, $data, &$languages) {
		$this->db->query("UPDATE " . DB_PREFIX . "attribute SET attribute_group_id = '" . (int)$data['attribute_group_id'] . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE attribute_id = '" . (int)$attribute_id . "'");
		
		$language_ids = array();
		foreach ($languages as $language) {
			$language_ids[] = $language['language_id'];	
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "attribute_description WHERE attribute_id = '" . (int)$attribute_id . "' AND language_id NOT IN (" . implode(',', $language_ids) . ")");

		foreach ($data['attribute_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_description SET attribute_id = '" . (int)$attribute_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "' ON DUPLICATE KEY UPDATE name = '" . $this->db->escape($value['name']) . "'");
		}
		
		// Extras
		foreach ($this->extraGeneralFields['Attributes'] as $extra) {
			if (!empty($extra['eval_edit'])) {
				eval($extra['eval_edit']);
			}
		}
	}
	public function addAttributeGroupLanguages(&$data, $allLanguages) {
		// Add Attribute Group Description Languages
		if (!empty($data['attribute_group_description'])) {
			$entered_keys = array_keys($data['attribute_group_description']);
			foreach ($allLanguages as $language) {
				if (!in_array($language['language_id'], $entered_keys)) {
					$data['attribute_group_description'][$language['language_id']] = array(
						'name' => $data['attribute_group_description'][$entered_keys[0]]['name']
					);
				}
			}
		}
	}
	public function addAttributeLanguages(&$data, $allLanguages) {
		// Add Attribute Description Languages
		if (!empty($data['attribute_description'])) {
			$entered_keys = array_keys($data['attribute_description']);
			foreach ($allLanguages as $language) {
				if (!in_array($language['language_id'], $entered_keys)) {
					$data['attribute_description'][$language['language_id']] = array(
						'name' => $data['attribute_description'][$entered_keys[0]]['name']
					);
				}
			}
		}
	}
	public function deleteAttributeGroups() {
		$this->load->model('catalog/attribute_group');
		
		$ids = $this->db->query("SELECT attribute_group_id FROM " . DB_PREFIX . "attribute_group ag");
		
		foreach ($ids->rows as $row) {
			$this->model_catalog_attribute_group->deleteAttributeGroup($row['attribute_group_id']);	
		}
	}
	public function deleteAttributes() {
		$this->load->model('catalog/attribute');
		
		$ids = $this->db->query("SELECT attribute_id FROM " . DB_PREFIX . "attribute ag");
		
		foreach ($ids->rows as $row) {
			$this->model_catalog_attribute->deleteAttribute($row['attribute_id']);	
		}
	}
}
?>
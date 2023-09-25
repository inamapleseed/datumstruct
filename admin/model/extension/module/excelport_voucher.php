<?php

class ModelExtensionModuleExcelportvoucher extends ModelExtensionModuleExcelport {
	public function getHistoryByVoucherId($sheet_data, $voucher_id) {
		$data = array();

		$history_read_map = array(
			'voucher_id' => 'A',
			'order_id' => 'B',
			'amount' => 'C',
			'date_added' => 'D'
		);

		foreach ($sheet_data as $row) {
			
			$new_history = array(
				'order_id' => null,
				'amount' => null,
				'date_added' => null
			);

			foreach ($row as $cell_index => $cell_value) {
				switch ($cell_index) {
					case $history_read_map['voucher_id'] : {
						if ((int)$cell_value != (int)$voucher_id) {
							continue 3;
						}
					} break;

					case $history_read_map['order_id'] : {
						$candidate_value = trim($cell_value);

						if (empty($candidate_value)) {
							continue 3;
						}

						$order_id = $candidate_value;
					} break;

					case $history_read_map['amount'] : {
						$amount = (float)str_replace(array(' ', ','), array('', '.'), $cell_value);
					} break;

					case $history_read_map['date_added'] : {
						$date_added = trim($cell_value);
					} break;
				}

				
			}
		
			$new_history['order_id'] = $order_id;
			$new_history['amount'] = $amount;
			$new_history['date_added'] = $date_added;

			$data[] = $new_history;
		}

		return $data;
	}

	public function sheetRowsToType($type, $voucher_id) {
		switch ($type) {
			case 'history' : {
				return $this->getHistoryByVoucherId($this->readSheetCache($type), $voucher_id);
			} break;
		}
	}

	public function getVoucherThemeIdByName($theme_name) {
		$this->load->model('sale/voucher_theme');

		$all_voucher_themes = $this->model_sale_voucher_theme->getVoucherThemes();

		foreach ($all_voucher_themes as $some_voucher_theme) {
			if ($some_voucher_theme['name'] == $theme_name) {
				return $some_voucher_theme['voucher_theme_id'];
			}
		}

		return 0;
	}

	public function importXLSVouchers($file, $importLimit, $addAsNew = false) {
		$this->language->load('extension/module/excelport');
        if (!is_numeric($importLimit) || $importLimit < 10 || $importLimit > 800) throw new Exception($this->language->get('excelport_import_limit_invalid'));
		
		$default_language = $this->config->get('config_language_id');
		
		$progress = $this->getProgress();
		$progress['importedCount'] = !empty($progress['importedCount']) ? $progress['importedCount'] : 0;
		$progress['done'] = false;
		
		require_once(IMODULE_ROOT.'vendors/phpexcel/PHPExcel.php');
		// Create new PHPExcel object
		
		require_once(IMODULE_ROOT.'vendors/phpexcel/CustomReadFilter.php');
        $chunkFilter = new CustomReadFilter(array('Vouchers' => array('A', ($progress['importedCount'] + 2), 'Z', (($progress['importedCount'] + $importLimit) + 1)), 'vouchers' => array('A', ($progress['importedCount'] + 2), 'Z', (($progress['importedCount'] + $importLimit) + 1))), true);

		$madeImports = false;
		$objReader = new PHPExcel_Reader_Excel2007();
		$objReader->setReadFilter($chunkFilter);
        $objReader->setReadDataOnly(true);
		$objReader->setLoadSheetsOnly(array("Vouchers", "vouchers"));
		$objPHPExcel = $objReader->load($file);
		$progress['importingFile'] = substr($file, strripos($file, '/') + 1);
		
		$vouchersSheetObj = $this->getSheet($objPHPExcel, array('vouchers'));
		
		$progress['all'] = -1;
		$this->setProgress($progress);
		
		$voucher_map = array(
			'voucher_id' 	=> 0,
			'order_id'		=> 1,
			'code'			=> 2,
			'from_name'		=> 3,
			'from_email'	=> 4,
			'to_name'		=> 5,
			'to_email'		=> 6,
			'theme'			=> 7,
			'message'		=> 8,
			'amount'		=> 9,
			'status'		=> 10,
			'date_added'	=> 11
		);

		$source = array(0,2 + ($progress['importedCount']));

		do {
			$this->custom_set_time_limit();
			
			$voucher_code = trim(strval($vouchersSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $voucher_map['code']) . ($source[1]))->getValue()));
			
			if ($voucher_code !== "") {
				$voucher_id = (int)$vouchersSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $voucher_map['voucher_id']) . ($source[1]))->getValue();
				
				$order_id = $vouchersSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $voucher_map['order_id']) . ($source[1]))->getValue();
				
				$voucher_from_name = trim($vouchersSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $voucher_map['from_name']) . ($source[1]))->getValue());

				$voucher_from_email = trim($vouchersSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $voucher_map['from_email']) . ($source[1]))->getValue());

				$voucher_to_name = trim($vouchersSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $voucher_map['to_name']) . ($source[1]))->getValue());

				$voucher_to_email = trim($vouchersSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $voucher_map['to_email']) . ($source[1]))->getValue());

				$voucher_theme = trim($vouchersSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $voucher_map['theme']) . ($source[1]))->getValue());
				$voucher_theme_id = $this->getVoucherThemeIdByName($voucher_theme);

				$voucher_message = trim($vouchersSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $voucher_map['message']) . ($source[1]))->getValue());

				$voucher_amount = (float)str_replace(array(' ', ','), array('', '.'), $vouchersSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $voucher_map['amount']) . ($source[1]))->getValue());

				$voucher_status = trim($vouchersSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $voucher_map['status']) . ($source[1]))->getValue());
				$voucher_status = $voucher_status == 'Enabled' ? 1 : 0;

				$voucher_date_added = trim($vouchersSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $voucher_map['date_added']) . ($source[1]))->getValue());

				$voucher = array(
					'code' => $voucher_code,
					'from_name' => $voucher_from_name,
					'from_email' => $voucher_from_email,
					'to_name' => $voucher_to_name,
					'to_email' => $voucher_to_email,
					'voucher_theme_id' => $voucher_theme_id,
					'message' => $voucher_message,
					'amount' => $voucher_amount,
					'status' => $voucher_status,
					'date_added' => $voucher_date_added,
					'voucher_history' => $this->loadBulkSheetData('history', $file, $voucher_id)
				);
				
				// Extras
				foreach ($this->extraGeneralFields['Vouchers'] as $extra) {
					if (!empty($extra['name']) && !empty($extra['column_light'])) {
						$voucher[$extra['name']] = $vouchersSheetObj->getCell($extra['column_light'] . $source[1])->getValue();	
					}
				}
				
				if (!$addAsNew) {
					$exists = false;
					$existsQuery = $this->db->query("SELECT voucher_id FROM `" . DB_PREFIX . "voucher` WHERE voucher_id = ".$voucher_id);
					
					$exists = $existsQuery->num_rows > 0;
							
					if ($exists) {
						$this->editVoucher($voucher_id, $voucher);
					} else {
						$this->addVoucher($voucher_id, $voucher);
					}
				} else {
					$this->addVoucher('', $voucher);
				}
				
				$progress['current']++;
				$progress['importedCount']++;
				$madeImports = true;
				$this->setProgress($progress);
			}
			$source[1] += 1;
		} while ($voucher_code !== "");
		$progress['done'] = true;
		if (!$madeImports) {
			$progress['importedCount'] = 0;
			array_shift($this->session->data['uploaded_files']);
		}
		$this->setProgress($progress);
	}
	
	public function exportXLSVouchers($destinationFolder = '', $voucherNumber = 800, $export_filters) {
		$this->language->load('extension/module/excelport');
		$this->folderCheck($destinationFolder);

		$progress = $this->getProgress();
		$progress['done'] = false;
		
		$file = IMODULE_ROOT . 'vendors/excelport/template_voucher.xlsx';
		
		$default_language = $this->config->get('config_language_id');

		require_once(IMODULE_ROOT.'vendors/phpexcel/PHPExcel.php');
		
		if (!empty($progress['populateAll'])) {
			$all = $this->db->query($this->getQuery($export_filters, $default_language, true));
			$progress['all'] = $all->num_rows ? (int)$all->row['count'] : 0;
			unset($progress['populateAll']);
			$this->setProgress($progress);
		}
		
		$this->setData('Vouchers', $destinationFolder, $default_language);

		$voucherSheet = 0;
		$voucherHistorySheet = 1;
		$voucherMetaSheet = 2;

		$target = array(0,2);
		$target_history = array(0,2);
		
		$name = 'vouchers_excelport_' . str_replace('/', '_', substr(HTTP_CATALOG, 7, strlen(HTTP_CATALOG) - 8)) . '_' . date("Y-m-d_H-i-s") . '_' . $progress['current'];
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
		
		$voucherSheetObj = $objPHPExcel->setActiveSheetIndex($voucherSheet);
		$historySheetObj = $objPHPExcel->setActiveSheetIndex($voucherHistorySheet);
		$metaSheetObj = $objPHPExcel->setActiveSheetIndex($voucherMetaSheet);

		$themesStart = array(0,3);
		$this->load->model('sale/voucher_theme');
		$themes = $this->model_sale_voucher_theme->getVoucherThemes();

		for ($i = 0; $i < count($themes); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($themesStart[0]) . ($themesStart[1] + $i), $themes[$i]['voucher_theme_id'], PHPExcel_Cell_DataType::TYPE_STRING);
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($themesStart[0] + 1) . ($themesStart[1] + $i), $themes[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
		}

		// Extra fields
		$extras = array();
		foreach ($this->extraGeneralFields['Vouchers'] as $extra) {
			if (!empty($extra['name']) && !empty($extra['column_light'])) {
				$extras[$extra['name']] = $extra['column_light'];
			}
		}

		$vouchers_generals = array(
			'voucher_id' 	=> 0,
			'order_id'		=> 1,
			'code'			=> 2,
			'from_name'		=> 3,
			'from_email'	=> 4,
			'to_name'		=> 5,
			'to_email'		=> 6,
			'theme'			=> 7,
			'message'		=> 8,
			'amount'		=> 9,
			'status'		=> 10,
			'date_added'	=> 11
		);

		$vouchers_histories = array(
			'voucher_id'	=> 0,
			'order_id'		=> 1,
			'amount'		=> 2,
			'date_added'	=> 3
		);

		$this->db->query("SET SESSION group_concat_max_len = 1000000;");
		
		$vouchers_result = $this->db->query($this->getQuery($export_filters, $default_language) . " ORDER BY v.voucher_id LIMIT ". $progress['current'] . ", " . $voucherNumber);
		
		foreach ($this->extraGeneralFields['Vouchers'] as $extra) {
			if (!empty($extra['title']) && !empty($extra['column_light'])) {
				$voucherSheetObj->setCellValueExplicit($extra['column_light'] . '1', $extra['title'], PHPExcel_Cell_DataType::TYPE_STRING);
			}
		}

		if ($vouchers_result->num_rows > 0) {
			foreach ($vouchers_result->rows as $row) {
				
				$this->getData('Vouchers', $row);
				
				// Prepare data
				if (empty($row['code'])) $row['code'] = '-';
				$row['status'] = !empty($row['status']) ? "Enabled" : "Disabled";
				
				// Add data
				// Extras
				foreach ($extras as $name => $position) {
					$voucherSheetObj->setCellValueExplicit($position . ($target[1]), empty($row[$name]) ? '' : $row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
				}
				// General
				foreach ($vouchers_generals as $name => $position) {
					$voucherSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target[0] + $position) . ($target[1]), empty($row[$name]) && $row[$name] !== '0' ? '' : $row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
				}

				$history_result = $this->db->query("SELECT vh.* FROM " . DB_PREFIX . "voucher_history vh WHERE vh.voucher_id = '" . (int)$row['voucher_id'] . "' ORDER BY vh.date_added ASC");

				foreach ($history_result->rows as $history_row) {
					foreach ($history_row as $history_col => $history_val) {
						if (!isset($vouchers_histories[$history_col])) continue;

						$historySheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target_history[0] + $vouchers_histories[$history_col]) . ($target_history[1]), empty($history_val) && $history_val !== '0' ? '' : $history_val, PHPExcel_Cell_DataType::TYPE_STRING);
					}

					$target_history[1]++;
				}

				$target[1] = $target[1] + 1;
				$progress['current']++;
				$progress['memory_get_usage'] = round(memory_get_usage(true)/(1024*1024));
				$progress['percent'] = 100 / ($vouchers_result->num_rows / $progress['current']);
				
				$this->setProgress($progress);
			}
		} else {
			$progress['done'] = true;
		}

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

	public function getQuery($filters = array(), $language_id, $count = false) {
	    if (empty($filters) || !in_array($filters['Conjunction'], array('AND', 'OR'))) $filters['Conjunction'] = 'OR';
	    
	    $join_rules = array(
	    	'voucher_theme_description' => "LEFT JOIN " . DB_PREFIX . "voucher_theme_description vtd ON (vtd.voucher_id = v.voucher_id AND vtd.language_id='" . $language_id . "')",
	    	'voucher_history' => "JOIN " . DB_PREFIX . "voucher_history vh ON (v.voucher_id = vh.voucher_id)",
	    );
	    
	    $joins = array();
	    
	    $wheres = array();
	    
	    foreach ($filters as $i => $filter) {
	        if (is_array($filter)) {
	            if (!array_key_exists($this->conditions['Vouchers'][$filter['Field']]['join_table'], $joins) && array_key_exists($this->conditions['Vouchers'][$filter['Field']]['join_table'], $join_rules)) {
	                $joins[$this->conditions['Vouchers'][$filter['Field']]['join_table']] = $join_rules[$this->conditions['Vouchers'][$filter['Field']]['join_table']];
	            }
	            $condition = str_replace(array('{FIELD_NAME}', '{WORD}'), array($this->conditions['Vouchers'][$filter['Field']]['field_name'], stripos($this->conditions['Vouchers'][$filter['Field']]['type'], 'number') !== FALSE ? (int)$this->db->escape($filter['Value']) : $this->db->escape($filter['Value'])), $this->operations[$filter['Condition']]['operation']);
	            if (!in_array($condition, $wheres)) $wheres[] = $condition;
	        }
	    }
	    
	    $select = $count ? "COUNT(*)" : "*";
	    
	    $query = ($count ? "SELECT COUNT(*) as count FROM (" : "") . "SELECT " . $select . " FROM " . DB_PREFIX . "voucher v " . implode(" ", $joins) . " " . (!empty($wheres) ? " WHERE (" . implode(" " . $filters['Conjunction'] . " ", $wheres) . ")" : "") . " GROUP BY v.voucher_id" . ($count ? ") as count_table" : "");
	    
	    return $query;
	}

	public function addVoucher($voucher_id = '', $data) {
		$voucher_id = trim($voucher_id);

		$this->db->query("INSERT INTO " . DB_PREFIX . "voucher SET ".(!empty($voucher_id) ? "voucher_id = '" . (int)trim($voucher_id) . "', " : "")."code = '" . $this->db->escape($data['code']) . "', from_name = '" . $this->db->escape($data['from_name']) . "', from_email = '" . $this->db->escape($data['from_email']) . "', to_name = '" . $this->db->escape($data['to_name']) . "', to_email = '" . $this->db->escape($data['to_email']) . "', voucher_theme_id = '" . (int)$data['voucher_theme_id'] . "', message = '" . $this->db->escape($data['message']) . "', amount = '" . (float)$data['amount'] . "', status = '" . (int)$data['status'] . "', date_added = " . (!empty($data['date_added']) ? "'" . $this->db->escape($data['date_added']) . "'" : "NOW()"));

		$voucher_id = $this->db->getLastId();

		$this->db->query("DELETE FROM " . DB_PREFIX . "voucher_history WHERE voucher_id = '" . (int)$voucher_id . "'");
		if (isset($data['voucher_history'])) {
			foreach ($data['voucher_history'] as $history_data) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "voucher_history SET voucher_id = '" . (int)$voucher_id . "', order_id = '" . (int)$history_data['order_id'] . "', amount = '" . (float)$history_data['amount'] . "', date_added = " . (!empty($history_data['date_added']) ? "'" . $this->db->escape($history_data['date_added']) . "'" : "NOW()"));
			}
		}

		// Extras
		foreach ($this->extraGeneralFields['Vouchers'] as $extra) {
			if (!empty($extra['eval_add'])) {
				eval($extra['eval_add']);
			}
		}
	}

	public function editVoucher($voucher_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "voucher SET code = '" . $this->db->escape($data['code']) . "', from_name = '" . $this->db->escape($data['from_name']) . "', from_email = '" . $this->db->escape($data['from_email']) . "', to_name = '" . $this->db->escape($data['to_name']) . "', to_email = '" . $this->db->escape($data['to_email']) . "', voucher_theme_id = '" . (int)$data['voucher_theme_id'] . "', message = '" . $this->db->escape($data['message']) . "', amount = '" . (float)$data['amount'] . "', status = '" . (int)$data['status'] . "', date_added = " . (!empty($data['date_added']) ? "'" . $this->db->escape($data['date_added']) . "'" : "NOW()") . " WHERE voucher_id='" . (int)$voucher_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "voucher_history WHERE voucher_id = '" . (int)$voucher_id . "'");
		if (isset($data['voucher_history'])) {
			foreach ($data['voucher_history'] as $history_data) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "voucher_history SET voucher_id = '" . (int)$voucher_id . "', order_id = '" . (int)$history_data['order_id'] . "', amount = '" . (float)$history_data['amount'] . "', date_added = " . (!empty($history_data['date_added']) ? "'" . $this->db->escape($history_data['date_added']) . "'" : "NOW()"));
			}
		}

		// Extras
		foreach ($this->extraGeneralFields['Vouchers'] as $extra) {
			if (!empty($extra['eval_edit'])) {
				eval($extra['eval_edit']);
			}
		}
	}

	public function deleteVouchers() {
		$this->load->model('sale/voucher');
		
		$ids = $this->db->query("SELECT voucher_id FROM " . DB_PREFIX . "voucher c");
		
		foreach ($ids->rows as $row) {
			$this->model_sale_voucher->deleteVoucher($row['voucher_id']);	
		}
	}
}
<?php

class ModelExtensionModuleExcelportcoupon extends ModelExtensionModuleExcelport {
	public function getHistoryByCouponId($sheet_data, $coupon_id) {
		$data = array();

		$history_read_map = array(
			'coupon_id' => 'A',
			'order_id' => 'B',
			'customer_id' => 'C',
			'amount' => 'D',
			'date_added' => 'E'
		);

		foreach ($sheet_data as $row) {
			
			$new_history = array(
				'order_id' => null,
				'customer_id' => null,
				'amount' => null,
				'date_added' => null
			);

			foreach ($row as $cell_index => $cell_value) {
				switch ($cell_index) {
					case $history_read_map['coupon_id'] : {
						if ((int)$cell_value != (int)$coupon_id) {
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

					case $history_read_map['customer_id'] : {
						$customer_id = (int)trim($cell_value);
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
			$new_history['customer_id'] = $customer_id;
			$new_history['amount'] = $amount;
			$new_history['date_added'] = $date_added;

			$data[] = $new_history;
		}

		return $data;
	}

	public function sheetRowsToType($type, $coupon_id) {
		switch ($type) {
			case 'history' : {
				return $this->getHistoryByCouponId($this->readSheetCache($type), $coupon_id);
			} break;
		}
	}

	public function importXLSCoupons($file, $importLimit, $addAsNew = false) {
		$this->language->load('extension/module/excelport');
        if (!is_numeric($importLimit) || $importLimit < 10 || $importLimit > 800) throw new Exception($this->language->get('excelport_import_limit_invalid'));
		
		$default_language = $this->config->get('config_language_id');
		
		$progress = $this->getProgress();
		$progress['importedCount'] = !empty($progress['importedCount']) ? $progress['importedCount'] : 0;
		$progress['done'] = false;
		
		require_once(IMODULE_ROOT.'vendors/phpexcel/PHPExcel.php');
		// Create new PHPExcel object
		
		require_once(IMODULE_ROOT.'vendors/phpexcel/CustomReadFilter.php');
        $chunkFilter = new CustomReadFilter(array('Coupons' => array('A', ($progress['importedCount'] + 2), 'Z', (($progress['importedCount'] + $importLimit) + 1)), 'coupons' => array('A', ($progress['importedCount'] + 2), 'Z', (($progress['importedCount'] + $importLimit) + 1))), true);

		$madeImports = false;
		$objReader = new PHPExcel_Reader_Excel2007();
		$objReader->setReadFilter($chunkFilter);
        $objReader->setReadDataOnly(true);
		$objReader->setLoadSheetsOnly(array("Coupons", "coupons"));
		$objPHPExcel = $objReader->load($file);
		$progress['importingFile'] = substr($file, strripos($file, '/') + 1);
		
		$couponsSheetObj = $this->getSheet($objPHPExcel, array('coupons'));
		
		$progress['all'] = -1;
		$this->setProgress($progress);
		
		$coupon_map = array(
			'coupon_id' 	=> 0,
			'name'			=> 1,
			'code'			=> 2,
			'type'			=> 3,
			'discount'		=> 4,
			'total'			=> 5,
			'logged'		=> 6,
			'shipping'		=> 7,
			'products'		=> 8,
			'categories'	=> 9,
			'date_start'	=> 10,
			'date_end'		=> 11,
			'uses_total'	=> 12,
			'uses_customer'	=> 13,
			'status'		=> 14,
			'date_added'	=> 15
		);

		$source = array(0,2 + ($progress['importedCount']));

		do {
			$this->custom_set_time_limit();
			
			$coupon_name = trim(strval($couponsSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $coupon_map['name']) . ($source[1]))->getValue()));
			$coupon_code = trim(strval($couponsSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $coupon_map['code']) . ($source[1]))->getValue()));
			
			if (!empty($coupon_name) && !empty($coupon_code)) {
				$coupon_id = (int)$couponsSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $coupon_map['coupon_id']) . ($source[1]))->getValue();
				
				$coupon_type = trim($couponsSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $coupon_map['type']) . ($source[1]))->getValue());
				$coupon_type = $coupon_type == 'P' ? 'P' : 'F';

				$coupon_logged = trim($couponsSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $coupon_map['logged']) . ($source[1]))->getValue());
				$coupon_logged = $coupon_logged == 'Yes' ? 1 : 0;

				$coupon_shipping = trim($couponsSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $coupon_map['shipping']) . ($source[1]))->getValue());
				$coupon_shipping = $coupon_shipping == 'Yes' ? 1 : 0;

				$coupon_status = trim($couponsSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $coupon_map['status']) . ($source[1]))->getValue());
				$coupon_status = $coupon_status == 'Enabled' ? 1 : 0;

				$coupon_category = array_filter(array_map('trim', explode(',', $couponsSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $coupon_map['categories']) . ($source[1]))->getValue())));

				$coupon_product = array_filter(array_map('trim', explode(',', $couponsSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $coupon_map['products']) . ($source[1]))->getValue())));

				$coupon = array(
					'name' => $coupon_name,
					'code' => $coupon_code,
					'discount' => (float)str_replace(array(' ', ','), array('', '.'), $couponsSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $coupon_map['discount']) . ($source[1]))->getValue()),
					'type' => $coupon_type,
					'total' => (float)str_replace(array(' ', ','), array('', '.'), $couponsSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $coupon_map['total']) . ($source[1]))->getValue()),
					'logged' => $coupon_logged,
					'shipping' => $coupon_shipping,
					'date_start' => trim($couponsSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $coupon_map['date_start']) . ($source[1]))->getValue()),
					'date_end' => trim($couponsSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $coupon_map['date_end']) . ($source[1]))->getValue()),
					'date_added' => trim($couponsSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $coupon_map['date_added']) . ($source[1]))->getValue()),
					'status' => $coupon_status,
					'uses_total' => (int)trim($couponsSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $coupon_map['uses_total']) . ($source[1]))->getValue()),
					'uses_customer' => (int)trim($couponsSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $coupon_map['uses_customer']) . ($source[1]))->getValue()),
					'coupon_product' => $coupon_product,
					'coupon_category' => $coupon_category,
					'coupon_history' => $this->loadBulkSheetData('history', $file, $coupon_id)
				);
				
				// Extras
				foreach ($this->extraGeneralFields['Coupons'] as $extra) {
					if (!empty($extra['name']) && !empty($extra['column_light'])) {
						$coupon[$extra['name']] = $couponsSheetObj->getCell($extra['column_light'] . $source[1])->getValue();	
					}
				}
				
				if (!$addAsNew) {
					$exists = false;
					$existsQuery = $this->db->query("SELECT coupon_id FROM `" . DB_PREFIX . "coupon` WHERE coupon_id = ".$coupon_id);
					
					$exists = $existsQuery->num_rows > 0;
							
					if ($exists) {
						$this->editCoupon($coupon_id, $coupon);
					} else {
						$this->addCoupon($coupon_id, $coupon);
					}
				} else {
					$this->addCoupon('', $coupon);
				}
				
				$progress['current']++;
				$progress['importedCount']++;
				$madeImports = true;
				$this->setProgress($progress);
			}
			$source[1] += 1;
		} while (!empty($coupon_name) && !empty($coupon_code));
		$progress['done'] = true;
		if (!$madeImports) {
			$progress['importedCount'] = 0;
			array_shift($this->session->data['uploaded_files']);
		}
		$this->setProgress($progress);
	}
	
	public function exportXLSCoupons($destinationFolder = '', $couponNumber = 800, $export_filters) {
		$this->language->load('extension/module/excelport');
		$this->folderCheck($destinationFolder);

		$progress = $this->getProgress();
		$progress['done'] = false;
		
		$file = IMODULE_ROOT . 'vendors/excelport/template_coupon.xlsx';
		
		$default_language = $this->config->get('config_language_id');

		require_once(IMODULE_ROOT.'vendors/phpexcel/PHPExcel.php');
		
		if (!empty($progress['populateAll'])) {
			$all = $this->db->query($this->getQuery($export_filters, $default_language, true));
			$progress['all'] = $all->num_rows ? (int)$all->row['count'] : 0;
			unset($progress['populateAll']);
			$this->setProgress($progress);
		}
		
		$this->setData('Coupons', $destinationFolder, $default_language);

		$couponSheet = 0;
		$couponHistorySheet = 1;
		$couponMetaSheet = 2;

		$target = array(0,2);
		$target_history = array(0,2);
		
		$name = 'coupons_excelport_' . str_replace('/', '_', substr(HTTP_CATALOG, 7, strlen(HTTP_CATALOG) - 8)) . '_' . date("Y-m-d_H-i-s") . '_' . $progress['current'];
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
		
		$couponSheetObj = $objPHPExcel->setActiveSheetIndex($couponSheet);
		$historySheetObj = $objPHPExcel->setActiveSheetIndex($couponHistorySheet);
		$metaSheetObj = $objPHPExcel->setActiveSheetIndex($couponMetaSheet);

		$categoriesStart = array(0,3);
		$this->load->model('catalog/category');
		if (version_compare(VERSION, '1.5.5', '>=')) {
			$categories = $this->model_catalog_category->getCategories(array());
		}
		if (version_compare(VERSION, '1.5.5', '<')) {
			$categories = $this->model_catalog_category->getCategories();
		}

		for ($i = 0; $i < count($categories); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($categoriesStart[0]) . ($categoriesStart[1] + $i), $categories[$i]['category_id'], PHPExcel_Cell_DataType::TYPE_STRING);
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($categoriesStart[0] + 1) . ($categoriesStart[1] + $i), $categories[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
		}

		// Extra fields
		$extras = array();
		foreach ($this->extraGeneralFields['Coupons'] as $extra) {
			if (!empty($extra['name']) && !empty($extra['column_light'])) {
				$extras[$extra['name']] = $extra['column_light'];
			}
		}

		$coupons_generals = array(
			'coupon_id' 	=> 0,
			'name'			=> 1,
			'code'			=> 2,
			'type'			=> 3,
			'discount'		=> 4,
			'total'			=> 5,
			'logged'		=> 6,
			'shipping'		=> 7,
			'products'		=> 8,
			'categories'	=> 9,
			'date_start'	=> 10,
			'date_end'		=> 11,
			'uses_total'	=> 12,
			'uses_customer'	=> 13,
			'status'		=> 14,
			'date_added'	=> 15
		);

		$coupons_histories = array(
			'coupon_id'		=> 0,
			'order_id'		=> 1,
			'customer_id'	=> 2,
			'amount'		=> 3,
			'date_added'	=> 4
		);

		$this->db->query("SET SESSION group_concat_max_len = 1000000;");
		
		$coupons_result = $this->db->query($this->getQuery($export_filters, $default_language) . " ORDER BY c.coupon_id LIMIT ". $progress['current'] . ", " . $couponNumber);
		
		foreach ($this->extraGeneralFields['Coupons'] as $extra) {
			if (!empty($extra['title']) && !empty($extra['column_light'])) {
				$couponSheetObj->setCellValueExplicit($extra['column_light'] . '1', $extra['title'], PHPExcel_Cell_DataType::TYPE_STRING);
			}
		}

		if ($coupons_result->num_rows > 0) {
			foreach ($coupons_result->rows as $row) {
				
				$this->getData('Coupons', $row);
				
				// Prepare data
				if (empty($row['code'])) $row['code'] = '-';
				$row['status'] = !empty($row['status']) ? "Enabled" : "Disabled";
				$row['logged'] = !empty($row['logged']) ? "Yes" : "No";
				$row['shipping'] = !empty($row['shipping']) ? "Yes" : "No";
				
				// Add data
				// Extras
				foreach ($extras as $name => $position) {
					$couponSheetObj->setCellValueExplicit($position . ($target[1]), empty($row[$name]) ? '' : $row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
				}
				// General
				foreach ($coupons_generals as $name => $position) {
					$couponSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target[0] + $position) . ($target[1]), empty($row[$name]) && $row[$name] !== '0' ? '' : $row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
				}

				$history_result = $this->db->query("SELECT ch.* FROM " . DB_PREFIX . "coupon_history ch WHERE ch.coupon_id = '" . (int)$row['coupon_id'] . "' ORDER BY ch.date_added ASC");

				foreach ($history_result->rows as $history_row) {
					foreach ($history_row as $history_col => $history_val) {
						if (!isset($coupons_histories[$history_col])) continue;

						$historySheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target_history[0] + $coupons_histories[$history_col]) . ($target_history[1]), empty($history_val) && $history_val !== '0' ? '' : $history_val, PHPExcel_Cell_DataType::TYPE_STRING);
					}

					$target_history[1]++;
				}

				$target[1] = $target[1] + 1;
				$progress['current']++;
				$progress['memory_get_usage'] = round(memory_get_usage(true)/(1024*1024));
				$progress['percent'] = 100 / ($coupons_result->num_rows / $progress['current']);
				
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
	    	'coupon_product' => "JOIN " . DB_PREFIX . "coupon_product cp ON (c.coupon_id = cp.coupon_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (pd.product_id = cp.product_id AND pd.language_id='" . $language_id . "')",
	    	'coupon_category' => "JOIN " . DB_PREFIX . "coupon_category cc ON (c.coupon_id = cc.coupon_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (cd.product_id = cc.product_id AND cd.language_id='" . $language_id . "')",
	    	'coupon_history' => "JOIN " . DB_PREFIX . "coupon_history ch ON (c.coupon_id = ch.coupon_id) LEFT JOIN " . DB_PREFIX . "customer cu ON (cu.customer_id = ch.customer_id)"
	    );
	    
	    $joins = array();
	    
	    $wheres = array();
	    
	    foreach ($filters as $i => $filter) {
	        if (is_array($filter)) {
	            if (!array_key_exists($this->conditions['Coupons'][$filter['Field']]['join_table'], $joins) && array_key_exists($this->conditions['Coupons'][$filter['Field']]['join_table'], $join_rules)) {
	                $joins[$this->conditions['Coupons'][$filter['Field']]['join_table']] = $join_rules[$this->conditions['Coupons'][$filter['Field']]['join_table']];
	            }
	            $condition = str_replace(array('{FIELD_NAME}', '{WORD}'), array($this->conditions['Coupons'][$filter['Field']]['field_name'], stripos($this->conditions['Coupons'][$filter['Field']]['type'], 'number') !== FALSE ? (int)$this->db->escape($filter['Value']) : $this->db->escape($filter['Value'])), $this->operations[$filter['Condition']]['operation']);
	            if (!in_array($condition, $wheres)) $wheres[] = $condition;
	        }
	    }
	    
	    $select = $count ? "COUNT(*)" : "*";
	    
	    $query = ($count ? "SELECT COUNT(*) as count FROM (" : "") . "SELECT " . $select . " FROM " . DB_PREFIX . "coupon c " . implode(" ", $joins) . " " . (!empty($wheres) ? " WHERE (" . implode(" " . $filters['Conjunction'] . " ", $wheres) . ")" : "") . " GROUP BY c.coupon_id" . ($count ? ") as count_table" : "");
	    
	    return $query;
	}

	public function addCoupon($coupon_id = '', $data) {
		$coupon_id = trim($coupon_id);

		$this->db->query("INSERT INTO " . DB_PREFIX . "coupon SET ".(!empty($coupon_id) ? "coupon_id = '" . (int)trim($coupon_id) . "', " : "")."name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', discount = '" . (float)$data['discount'] . "', type = '" . $this->db->escape($data['type']) . "', total = '" . (float)$data['total'] . "', logged = '" . (int)$data['logged'] . "', shipping = '" . (int)$data['shipping'] . "', date_start = '" . $this->db->escape($data['date_start']) . "', date_end = '" . $this->db->escape($data['date_end']) . "', uses_total = '" . (int)$data['uses_total'] . "', uses_customer = '" . (int)$data['uses_customer'] . "', status = '" . (int)$data['status'] . "', date_added = " . (!empty($data['date_added']) ? "'" . $this->db->escape($data['date_added']) . "'" : "NOW()"));

		$coupon_id = $this->db->getLastId();

		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");
		if (isset($data['coupon_product'])) {
			foreach ($data['coupon_product'] as $product_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_product SET coupon_id = '" . (int)$coupon_id . "', product_id = '" . (int)$product_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_category WHERE coupon_id = '" . (int)$coupon_id . "'");
		if (isset($data['coupon_category'])) {
			foreach ($data['coupon_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_category SET coupon_id = '" . (int)$coupon_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_history WHERE coupon_id = '" . (int)$coupon_id . "'");
		if (isset($data['coupon_history'])) {
			foreach ($data['coupon_history'] as $history_data) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_history SET coupon_id = '" . (int)$coupon_id . "', order_id = '" . (int)$history_data['order_id'] . "', customer_id = '" . (int)$history_data['customer_id'] . "', amount = '" . (float)$history_data['amount'] . "', date_added = " . (!empty($history_data['date_added']) ? "'" . $this->db->escape($history_data['date_added']) . "'" : "NOW()"));
			}
		}

		// Extras
		foreach ($this->extraGeneralFields['Coupons'] as $extra) {
			if (!empty($extra['eval_add'])) {
				eval($extra['eval_add']);
			}
		}
	}

	public function editCoupon($coupon_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "coupon SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', discount = '" . (float)$data['discount'] . "', type = '" . $this->db->escape($data['type']) . "', total = '" . (float)$data['total'] . "', logged = '" . (int)$data['logged'] . "', shipping = '" . (int)$data['shipping'] . "', date_start = '" . $this->db->escape($data['date_start']) . "', date_end = '" . $this->db->escape($data['date_end']) . "', uses_total = '" . (int)$data['uses_total'] . "', uses_customer = '" . (int)$data['uses_customer'] . "', status = '" . (int)$data['status'] . "', date_added = " . (!empty($data['date_added']) ? "'" . $this->db->escape($data['date_added']) . "'" : "NOW()") . " WHERE coupon_id='" . (int)$coupon_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");
		if (isset($data['coupon_product'])) {
			foreach ($data['coupon_product'] as $product_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_product SET coupon_id = '" . (int)$coupon_id . "', product_id = '" . (int)$product_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_category WHERE coupon_id = '" . (int)$coupon_id . "'");
		if (isset($data['coupon_category'])) {
			foreach ($data['coupon_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_category SET coupon_id = '" . (int)$coupon_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_history WHERE coupon_id = '" . (int)$coupon_id . "'");
		if (isset($data['coupon_history'])) {
			foreach ($data['coupon_history'] as $history_data) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_history SET coupon_id = '" . (int)$coupon_id . "', order_id = '" . (int)$history_data['order_id'] . "', customer_id = '" . (int)$history_data['customer_id'] . "', amount = '" . (float)$history_data['amount'] . "', date_added = " . (!empty($history_data['date_added']) ? "'" . $this->db->escape($history_data['date_added']) . "'" : "NOW()"));
			}
		}

		// Extras
		foreach ($this->extraGeneralFields['Coupons'] as $extra) {
			if (!empty($extra['eval_edit'])) {
				eval($extra['eval_edit']);
			}
		}
	}

	public function deleteCoupons() {
		$this->load->model('marketing/coupon');
		
		$ids = $this->db->query("SELECT coupon_id FROM " . DB_PREFIX . "coupon c");
		
		foreach ($ids->rows as $row) {
			$this->model_marketing_coupon->deleteCoupon($row['coupon_id']);	
		}
	}
}
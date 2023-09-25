<?php 
class ModelExtensionModuleExcelportcustomer extends ModelExtensionModuleExcelport {
	public function importXLSCustomers($language, $allLanguages, $file, $importLimit = 100, $addAsNew = false) {
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
		$chunkFilter = new CustomReadFilter(array('Customers' => array('A', ($progress['importedCount'] + 2), 'AM', (($progress['importedCount'] + $importLimit) + 1)), 'customers' => array('A', ($progress['importedCount'] + 2), 'AM', (($progress['importedCount'] + $importLimit) + 1))), true); 
		
		$madeImports = false;
		$objReader = new PHPExcel_Reader_Excel2007();
		$objReader->setReadFilter($chunkFilter);
		$objReader->setReadDataOnly(true);
		$objReader->setLoadSheetsOnly(array("Customers", "customers", "Addresses", "addresses"));
		$objPHPExcel = $objReader->load($file);
		$progress['importingFile'] = substr($file, strripos($file, '/') + 1);
		$customersSheet = 0;
		$addressesSheet = 1;
		
		$customerSheetObj = $objPHPExcel->setActiveSheetIndex($customersSheet);
		$addressSheetObj = $objPHPExcel->setActiveSheetIndex($addressesSheet);
		
		$progress['all'] = -1; //(int)(($customerSheetObj->getHighestRow() - 2)/$this->customerSize);
		$this->setProgress($progress);
		
        if (version_compare(VERSION, '2.1.0.1', '>=')) {
		    $this->load->model('customer/customer');
        } else {
            $this->load->model('sale/customer');
        }
		
		$map = array(
			'customer_id' 		=> 0,
			'firstname'			=> 1,
			'lastname'			=> 2,
			'email'				=> 3,
			'telephone'			=> 4,
			'fax'				=> 5,
			'password' 			=> 6,
			'salt'				=> 7,
			'newsletter'		=> 8,
			'status'			=> 9,
			'approved'			=> 10,
			'customer_group'	=> 11,
			'address_id'		=> 12,
			'cart'				=> 13,
			'wishlist'			=> 14,
			'history'			=> 15,
			'transactions'		=> 16,
			'reward_points'		=> 17,
			'ip_addresses'	 	=> 18,
			'custom_field'	 	=> 19,
			'safe' 				=> 20,
			'store_id'			=> 21,
			'ip'				=> 22,
			'date_added'		=> 23
		);

		$field_address = array(
		  'customer_id'	=> 0,
			'address_id'	=> 1,
			'firstname'		=> 2,
			'lastname'		=> 3,
			'company'			=> 4,
			'address_1'		=> 7,
			'address_2'		=> 8,
			'city'				=> 9,
			'postcode'		=> 10,
			'country'			=> 11,
			'zone'				=> 12,
			'custom_field'				=> 13
		);
		
		$source = array(0,2 + ($progress['importedCount']));
		
        if (version_compare(VERSION, '2.1.0.1', '>=')) {
            $this->load->model('customer/customer_group');
            $customer_groups = $this->model_customer_customer_group->getCustomerGroups();
        } else {
            $this->load->model('sale/customer_group');
            $customer_groups = $this->model_sale_customer_group->getCustomerGroups();
        }
		
		$this->load->model('localisation/zone');
		$zones = $this->model_localisation_zone->getZones();

		$this->load->model('localisation/country');
		$countries = $this->model_localisation_country->getCountries();
				
		do {
			$this->custom_set_time_limit();
			
			$customer_email = strval($customerSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['email']) . ($source[1]))->getValue());
			$customer_id = (int)trim($customerSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['customer_id']) . ($source[1]))->getValue());

			if (!empty($customer_email) && !empty($customer_id)) {
				
				$found = false;
				foreach ($customer_groups as $customer_group) {
					if (trim(strtolower($customer_group['name'])) == trim(strtolower($customerSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['customer_group']) . ($source[1]))->getValue()))) {
						$found = true;
						$customer_group_id = $customer_group['customer_group_id'];
						break;
					}
				}
				if (!$found) $customer_group_id = $this->config->get('config_customer_group_id');
				
				$customer_status = $customerSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['status']) . ($source[1]))->getValue() == 'Enabled' ? 1 : 0;
				$customer_approved = $customerSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['approved']) . ($source[1]))->getValue() == 'Enabled' ? 1 : 0;
				$customer_newsletter = $customerSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['newsletter']) . ($source[1]))->getValue() == 'Enabled' ? 1 : 0;
								
				$customer_history = trim($customerSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['history']) . ($source[1]))->getValue());
				if (!empty($customer_history)) $customer_history = json_decode($customer_history, true);
				else $customer_history = array();
				
				$customer_transactions = trim($customerSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['transactions']) . ($source[1]))->getValue());
				if (!empty($customer_transactions)) $customer_transactions = json_decode($customer_transactions, true);
				else $customer_transactions = array();
				
				$customer_reward_points = trim($customerSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['reward_points']) . ($source[1]))->getValue());
				if (!empty($customer_reward_points)) $customer_reward_points = json_decode($customer_reward_points, true);
				else $customer_reward_points = array();
				
				$customer_ip_addresses = trim($customerSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['ip_addresses']) . ($source[1]))->getValue());
				if (!empty($customer_ip_addresses)) $customer_ip_addresses = json_decode($customer_ip_addresses, true);
				else $customer_ip_addresses = array();
				
				$customer_custom_field = strval($customerSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['custom_field']) . ($source[1]))->getValue());
				$customer_safe = $customerSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['safe']) . ($source[1]))->getValue() == 'Enabled' ? 1 : 0;

				$customer_addresses = array();

				$address_source = array(0,2);

				do {
					$address_firstname = strval($addressSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($address_source[0] + $field_address['firstname']) . ($address_source[1]))->getValue());
					$address_address_id = (int)($addressSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($address_source[0] + $field_address['address_id']) . ($address_source[1]))->getValue());
					$address_customer_id = (int)($addressSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($address_source[0] + $field_address['customer_id']) . ($address_source[1]))->getValue());
					
					if (!empty($address_firstname) && !empty($address_address_id) && !empty($address_customer_id) && $address_customer_id == $customer_id) {
						$address_lastname = strval($addressSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($address_source[0] + $field_address['lastname']) . ($address_source[1]))->getValue());
						$address_company = strval($addressSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($address_source[0] + $field_address['company']) . ($address_source[1]))->getValue());
						$address_address_1 = strval($addressSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($address_source[0] + $field_address['address_1']) . ($address_source[1]))->getValue());
						$address_address_2 = strval($addressSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($address_source[0] + $field_address['address_2']) . ($address_source[1]))->getValue());
						$address_city = strval($addressSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($address_source[0] + $field_address['city']) . ($address_source[1]))->getValue());
						$address_postcode = strval($addressSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($address_source[0] + $field_address['postcode']) . ($address_source[1]))->getValue());
						$address_custom_field = strval($addressSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($address_source[0] + $field_address['custom_field']) . ($address_source[1]))->getValue());

						$address_country = strval($addressSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($address_source[0] + $field_address['country']) . ($address_source[1]))->getValue());
						$address_zone = strval($addressSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($address_source[0] + $field_address['zone']) . ($address_source[1]))->getValue());

						$address_country_id = 0;
						foreach ($countries as $country) {
							if (trim(strtolower($address_country)) == trim(strtolower($country['name']))) {
								$address_country_id = $country['country_id'];
								break;
							}
						}

						$address_zone_id = 0;
						foreach ($zones as $zone) {
							if (trim(strtolower($address_zone)) == trim(strtolower($zone['name']))) {
								$address_zone_id = $zone['zone_id'];
								break;
							}
						}

						$customer_addresses[] = array(
						  'customer_id'	=> $address_customer_id,
							'address_id'	=> $address_address_id,
							'firstname'		=> $address_firstname,
							'lastname'		=> $address_lastname,
							'company'		=> $address_company,
							'address_1'		=> $address_address_1,
							'address_2'		=> $address_address_2,
							'city'			=> $address_city,
							'postcode'		=> $address_postcode,
							'country_id'	=> $address_country_id,
							'custom_field'	=> $address_custom_field,
							'zone_id'		=> $address_zone_id					
						);
					}

					$address_source[1] += 1;

				} while (!empty($address_firstname) && !empty($address_address_id) && !empty($address_customer_id));

				$customer = array(
					'customer_id' => $customer_id,
					'firstname' => trim($customerSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['firstname']) . ($source[1]))->getValue()),
					'lastname' => trim($customerSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['lastname']) . ($source[1]))->getValue()),
					'email' => trim($customerSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['email']) . ($source[1]))->getValue()),
					'telephone' => trim($customerSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['telephone']) . ($source[1]))->getValue()),
					'fax' => trim($customerSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['fax']) . ($source[1]))->getValue()),
					'password' => trim($customerSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['password']) . ($source[1]))->getValue()),
					'salt' => trim($customerSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['salt']) . ($source[1]))->getValue()),
					'newsletter' => $customer_newsletter,
					'customer_group_id' => $customer_group_id,
					'cart' => trim($customerSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['cart']) . ($source[1]))->getValue()),
					'wishlist' => trim($customerSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['wishlist']) . ($source[1]))->getValue()),
					'status' => $customer_status,
                    'address_id' => trim($customerSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['address_id']) . ($source[1]))->getValue()),
					'approved' => $customer_approved,
					'addresses' => $customer_addresses,
					'history' => $customer_history,
					'transactions' => $customer_transactions,
					'reward_points' => $customer_reward_points,
					'ip_addresses' => $customer_ip_addresses,
					'custom_field' => $customer_custom_field,
					'safe' => $customer_safe,
					'store_id'			=> (int)trim($customerSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['store_id']) . ($source[1]))->getValue()),
					'ip'				=> trim($customerSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['ip']) . ($source[1]))->getValue()),
					'date_added'		=> trim($customerSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['date_added']) . ($source[1]))->getValue())
				);

				// Extras
				foreach ($this->extraGeneralFields['Customers'] as $extra) {
					if (!empty($extra['name']) && !empty($extra['column_light'])) {
						$customer[$extra['name']] = $customerSheetObj->getCell($extra['column_light'] . $source[1])->getValue();	
					}
				}
				
				if (!$addAsNew) {
					$exists = false;
					$existsQuery = $this->db->query("SELECT customer_id FROM " . DB_PREFIX . "customer WHERE customer_id = ".$customer_id);
					
					$exists = $existsQuery->num_rows > 0;
							
					if ($exists) {
						$this->editCustomer($customer_id, $customer, $allLanguages);
					} else {
						$this->addCustomer($customer_id, $customer, $allLanguages);
					}
				} else {
					$this->addCustomer('', $customer, $allLanguages);
				}
				
				$progress['current']++;
				$progress['importedCount']++;
				$madeImports = true;
				$this->setProgress($progress);
			}

			$source[1] += 1;
		} while (!empty($customer_email) && !empty($customer_id));
		$progress['done'] = true;
		if (!$madeImports) {
			$progress['importedCount'] = 0;
			array_shift($this->session->data['uploaded_files']);
		}
		$this->setProgress($progress);
		
		$this->config->set('config_language_id', $default_language);	
	}
	
	public function exportXLSCustomers($language, $store, $destinationFolder = '', $customerNumber, $export_filters = array()) {
		$this->language->load('extension/module/excelport');
		$this->folderCheck($destinationFolder);
		
		$progress = $this->getProgress();
		$progress['done'] = false;
		
		$file = IMODULE_ROOT . 'vendors/excelport/template_customer.xlsx';
		
		$default_language = $this->config->get('config_language_id');
		$this->config->set('config_language_id', $language);
		require_once(IMODULE_ROOT.'vendors/phpexcel/PHPExcel.php');
		
		if (!empty($progress['populateAll'])) {
			$all = $this->db->query($this->getQuery($export_filters, $store, $language, true));
			
			$progress['all'] = $all->num_rows ? (int)$all->row['count'] : 0;
			unset($progress['populateAll']);
			$this->setProgress($progress);
		}
		
		$customersSheet = 0;
		$addressesSheet = 1;
		$metaSheet = 2;
		
		$customerGroupsStart = array(1,2);
        
        if (version_compare(VERSION, '2.1.0.1', '>=')) {
            $this->load->model('customer/customer_group');
            $customerGroups = $this->model_customer_customer_group->getCustomerGroups(array());
        } else {
            $this->load->model('sale/customer_group');
            $customerGroups = $this->model_sale_customer_group->getCustomerGroups(array());
        }
		
		$generals = array(
			'customer_id' 		=> 0,
			'firstname'			=> 1,
			'lastname'			=> 2,
			'email'				=> 3,
			'telephone'			=> 4,
			'fax'				=> 5,
			'password' 			=> 6,
			'salt'				=> 7,
			'newsletter'		=> 8,
			'status'			=> 9,
			'approved'			=> 10,
			'customer_group'	=> 11,
			'address_id'		=> 12,
			'cart'				=> 13,
			'wishlist'			=> 14,
			'custom_field'			=> 19,
			'safe'			=> 20,
			'store_id'			=> 21,
			'ip'				=> 22,
			'date_added'		=> 23
		);
		
		$additional = array(
			'history'			=> 15,
			'transactions'		=> 16,
			'reward_points'		=> 17,
			'ip_addresses'	 	=> 18
		);

		$field_address = array(
		  'customer_id'	=> 0,
			'address_id'	=> 1,
			'firstname'		=> 2,
			'lastname'		=> 3,
			'company'		=> 4,
			'address_1'		=> 7,
			'address_2'		=> 8,
			'city'			=> 9,
			'postcode'		=> 10,
			'country_id'	=> 11,
			'zone_id'		=> 12,
			'custom_field'		=> 13
		);
		
		// Extra fields
		$extras = array();
		foreach ($this->extraGeneralFields['Customers'] as $extra) {
			if (!empty($extra['name']) && !empty($extra['column_light'])) {
				$extras[$extra['name']] = $extra['column_light'];
			}
		}
		
		$dataValidations = array(
			array(
				'type' => 'list',
				'field' => $generals['newsletter'],
				'data' => array(0,2,0,3),
				'range' => '',
			),
			array(
				'type' => 'list',
				'field' => $generals['status'],
				'data' => array(0,2,0,3),
				'range' => '',
			),
			array(
				'type' => 'list',
				'field' => $generals['approved'],
				'data' => array(0,2,0,3),
				'range' => '',
			),
			array(
				'type' => 'list',
				'field' => $generals['safe'],
				'data' => array(0,2,0,3),
				'range' => '',
			),
			array(
				'type' => 'list',
				'field' => $generals['customer_group'],
				'data' => array($customerGroupsStart[0], $customerGroupsStart[1], $customerGroupsStart[0], $customerGroupsStart[1] + count($customerGroups) - 1),
				'range' => '',
				'count' => count($customerGroups)
			)
		);
		
		$target = array(0,2);
		$address_target = array(0,2);
		
		$this->load->model('localisation/language');
		$languageQuery = $this->model_localisation_language->getLanguage($this->config->get('config_language_id'));
		
		$this->load->model('localisation/zone');

		$this->load->model('localisation/country');

		$name = 'customers_excelport_' . $languageQuery['code'] . '_' . str_replace('/', '_', substr(HTTP_CATALOG, 7, strlen(HTTP_CATALOG) - 8)) . '_' . date("Y-m-d_H-i-s") . '_' . $progress['current'];
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
		
		for ($i = 0; $i < count($customerGroups); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($customerGroupsStart[0]) . ($customerGroupsStart[1] + $i), $customerGroups[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		
        if (version_compare(VERSION, '2.1.0.1', '>=')) {
            $this->load->model('customer/customer');
        } else {
            $this->load->model('sale/customer');
        }
		
		$extra_select = "";
		
		$this->db->query("SET SESSION group_concat_max_len = 1000000;");
		
		$customers = $this->db->query($this->getQuery($export_filters, $store, $language) . " ORDER BY c.customer_id LIMIT ". $progress['current'] . ", " . $customerNumber);
		
		$customerSheetObj = $objPHPExcel->setActiveSheetIndex($customersSheet);
		
		$addressSheetObj = $objPHPExcel->setActiveSheetIndex($addressesSheet);

		foreach ($this->extraGeneralFields['Customers'] as $extra) {
			if (!empty($extra['title']) && !empty($extra['column_light'])) {
				$customerSheetObj->setCellValueExplicit($extra['column_light'] . '1', $extra['title'], PHPExcel_Cell_DataType::TYPE_STRING);
			}
		}
		
		if ($customers->num_rows > 0) {
			foreach ($customers->rows as $myCustomerIndex => $row) {
				
				//$this->getData('Customers', $row);
				
				// Prepare data
				foreach ($customerGroups as $customerGroup) {
					if ($customerGroup['customer_group_id'] == $row['customer_group_id']) { $row['customer_group'] = $customerGroup['name']; }
					if ($customerGroup['customer_group_id'] == $this->config->get('config_customer_group_id')) { $defaultCustomerGroup = $customerGroup['name']; }	
				}
				if (empty($row['customer_group'])) $row['customer_group'] = $defaultCustomerGroup;
				
				$row['status'] = empty($row['status']) ? 'Disabled' : 'Enabled';
				
				$row['newsletter'] = empty($row['newsletter']) ? 'Disabled' : 'Enabled';
				
				$row['approved'] = empty($row['approved']) ? 'Disabled' : 'Enabled';
			
				$row['safe'] = empty($row['safe']) ? 'Disabled' : 'Enabled';
			
				if (empty($row['salt'])) $row['salt'] = '';
				if (empty($row['email'])) $row['email'] = '-';
				
				// Add data
				// Extras
				foreach ($extras as $name => $position) {
					$customerSheetObj->setCellValueExplicit($position . ($target[1]), empty($row[$name]) ? '' : $row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
				}
				// General
				foreach ($generals as $name => $position) {
					$customerSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target[0] + $position) . ($target[1]), empty($row[$name]) && $row[$name] !== '0' ? '' : $row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
				}

				// History
				if (version_compare(VERSION, '1.5.5', '>=')) {
                    if (version_compare(VERSION, '2.1.0.1', '>=')) {
                        $customerHistory = json_encode($this->model_customer_customer->getHistories($row['customer_id'], 0, 10000));
                    } else {
                        $customerHistory = json_encode($this->model_sale_customer->getHistories($row['customer_id'], 0, 10000));
                    }

					$customerSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target[0] + $additional['history']) . ($target[1]), $customerHistory, PHPExcel_Cell_DataType::TYPE_STRING);
				}
				
				// Transactions

                if (version_compare(VERSION, '2.1.0.1', '>=')) {
				    $customerTransactions = json_encode($this->model_customer_customer->getTransactions($row['customer_id'], 0, 10000));
                } else {
                    $customerTransactions = json_encode($this->model_sale_customer->getTransactions($row['customer_id'], 0, 10000));
                }

				$customerSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target[0] + $additional['transactions']) . ($target[1]), $customerTransactions, PHPExcel_Cell_DataType::TYPE_STRING);
				
				// Rewards
                if (version_compare(VERSION, '2.1.0.1', '>=')) {
				    $customerRewards = json_encode($this->model_customer_customer->getRewards($row['customer_id'], 0, 10000));
                } else {
                    $customerRewards = json_encode($this->model_sale_customer->getRewards($row['customer_id'], 0, 10000));
                }
				$customerSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target[0] + $additional['reward_points']) . ($target[1]), $customerRewards, PHPExcel_Cell_DataType::TYPE_STRING);
				
				// IPs
                if (version_compare(VERSION, '2.1.0.1', '>=')) {
                    $customerIPs = json_encode($this->model_customer_customer->getIps($row['customer_id']));
                } else {
                    $customerIPs = json_encode($this->model_sale_customer->getIps($row['customer_id']));
                }
				
				$customerSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target[0] + $additional['ip_addresses']) . ($target[1]), $customerIPs, PHPExcel_Cell_DataType::TYPE_STRING);
				
				// Data validations
				foreach ($dataValidations as $dataValidationIndex => $dataValidation) {
					if (isset($dataValidations[$dataValidationIndex]['count']) && $dataValidations[$dataValidationIndex]['count'] == 0) continue;
					$dataValidations[$dataValidationIndex]['range'] = PHPExcel_Cell::stringFromColumnIndex($target[0] + $dataValidation['field']) . ($target[1]);
					if (empty($dataValidations[$dataValidationIndex]['root'])) $dataValidations[$dataValidationIndex]['root'] = PHPExcel_Cell::stringFromColumnIndex($target[0] + $dataValidation['field']) . ($target[1]);
				}
				
				// Addresses
                if (version_compare(VERSION, '2.1.0.1', '>=')) {
                    $customerAddresses = $this->model_customer_customer->getAddresses($row['customer_id']);    
                } else {
                    $customerAddresses = $this->model_sale_customer->getAddresses($row['customer_id']);
                }
				
				foreach ($customerAddresses as $address_id => $address) {
					foreach ($field_address as $name => $position) {

						if ($name == 'country_id') {
							$country = $this->model_localisation_country->getCountry($address[$name]);
							$value = !empty($country['name']) ? trim($country['name']) : '';
						} elseif ($name == 'zone_id') {
							$zone = $this->model_localisation_zone->getZone($address[$name]);
							$value = !empty($zone['name']) ? trim($zone['name']) : '';
						} else {	
							$value = !empty($address[$name]) ? $address[$name] : '';
						}

                        if (is_array($value)) {
                            $value = serialize($value);
                        }

						$addressSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($address_target[0] + $position) . ($address_target[1]), $value, PHPExcel_Cell_DataType::TYPE_STRING);
					}

					$address_target[1] = $address_target[1] + 1;
				}

				$target[1] = $target[1] + 1;

				$progress['current']++;
				$progress['memory_get_usage'] = round(memory_get_usage(true)/(1024*1024));
				$progress['percent'] = 100 / ($customers->num_rows / $progress['current']);
				
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
					$objValidation = $customerSheetObj->getCell($dataValidation['root'])->getDataValidation();
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
					$customerSheetObj->setDataValidation($range, $objValidation);
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
		unset($customerSheetObj);
		unset($objPHPExcel);
		
		$progress['done'] = true;
		$this->setProgress($progress);
		
		return true;
	}
	
	public function getQuery($filters = array(), $store = 0, $language = 1, $count = false) {
		if (empty($filters) || !in_array($filters['Conjunction'], array('AND', 'OR'))) $filters['Conjunction'] = 'OR';
		
		$join_rules = array(
			'customer_group_description' => "LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id AND cgd.language_id = '" . $language . "')",
			'customer_group' => "LEFT JOIN " . DB_PREFIX . "customer_group cg ON (c.customer_group_id = cg.customer_group_id)",
			'address' => "JOIN " . DB_PREFIX . "address a ON (a.customer_id = c.customer_id)",
		);
		
		$joins = array();
		
		if (version_compare(VERSION, '1.5.3', '<')) {
			$joins['customer_group'] = $join_rules['customer_group'];
		} else {
			$joins['customer_group_description'] = $join_rules['customer_group_description'];
		}
		
		$wheres = array();
		
		foreach ($filters as $i => $filter) {
			if (is_array($filter)) {
				if (!array_key_exists($this->conditions['Customers'][$filter['Field']]['join_table'], $joins) && array_key_exists($this->conditions['Customers'][$filter['Field']]['join_table'], $join_rules)) {
					$joins[$this->conditions['Customers'][$filter['Field']]['join_table']] = $join_rules[$this->conditions['Customers'][$filter['Field']]['join_table']];
				}
				if (!is_array($this->conditions['Customers'][$filter['Field']]['field_name'])) {
					$condition = str_replace(array('{FIELD_NAME}', '{WORD}'), array($this->conditions['Customers'][$filter['Field']]['field_name'], stripos($this->conditions['Customers'][$filter['Field']]['type'], 'number') !== FALSE ? (int)$this->db->escape($filter['Value']) : $this->db->escape($filter['Value'])), $this->operations[$filter['Condition']]['operation']);
				} else {
					$sub_conditions = array();
					foreach ($this->conditions['Customers'][$filter['Field']]['field_name'] as $field_name) {
						$sub_conditions[] = str_replace(array('{FIELD_NAME}', '{WORD}'), array($field_name, stripos($this->conditions['Customers'][$filter['Field']]['type'], 'number') !== FALSE ? (int)$this->db->escape($filter['Value']) : $this->db->escape($filter['Value'])), $this->operations[$filter['Condition']]['operation']);
					}
					$condition = '(' . implode(' OR ', $sub_conditions) . ')';
				}
				if (!in_array($condition, $wheres)) $wheres[] = $condition;
			}
		}
		
		$select = $count ? "COUNT(*)" : "*, " . (version_compare(VERSION, '1.5.3', '<') ? 'cg.name as name' : 'cgd.name as name') . ", c.*";
		
		$query = ($count ? "SELECT COUNT(*) as count FROM (" : "") . "SELECT " . $select . " FROM " . DB_PREFIX . "customer c " . implode(" ", $joins) . " WHERE c.store_id = '" . $store . "' " . (!empty($wheres) ? " AND (" . implode(" " . $filters['Conjunction'] . " ", $wheres) . ")" : "") . " GROUP BY c.customer_id" . ($count ? ") as count_table" : "");
		
		return $query;
	}
	
	public function addCustomer($customer_id = '', $data, $allLanguages) {
		$extra_select = '';
		if (version_compare(VERSION, '1.5.4', '>=')) {
			$extra_select = ", salt = '" . $this->db->escape($data['salt']) . "'";
		}
		
		if(isset($data['salt']) && $data['salt'] == ''){
			$data['password'] = md5($data['password']);
		}

		$customer_id = trim($customer_id);
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET ".(!empty($customer_id) ? "customer_id = '" . (int)trim($customer_id) . "', " : "")."firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "'" . $extra_select . ", email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape($data['custom_field']) . "', newsletter = '" . (int)$data['newsletter'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', safe = '" . (int)$data['safe'] . "', cart = '" . $data['cart'] . "', wishlist = '" . $data['wishlist'] . "', password = '" . $this->db->escape($data['password']) . "', ip = '" . $this->db->escape($data['ip']) . "', store_id = '" . (int)($data['store_id']) . "', status = '" . (int)$data['status'] . "', approved = '" . (int)$data['approved'] . "', date_added = " . (!empty($data['date_added']) ? "'" . $this->db->escape($data['date_added']) . "'" : "NOW()") . "");
		
		$customer_id = $this->db->getLastId();
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");
		
		if (isset($data['addresses'])) {		
      		foreach ($data['addresses'] as $address) {
				$extra_select = '';
				if (version_compare(VERSION, '1.5.3', '>=')) {
					
				}	

				$is_default = !empty($data['address_id']) && !empty($address['address_id']) && $data['address_id'] == $address['address_id'];

      			$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($address['firstname']) . "', lastname = '" . $this->db->escape($address['lastname']) . "', company = '" . $this->db->escape($address['company']) . "'" . $extra_select . ", address_1 = '" . $this->db->escape($address['address_1']) . "', address_2 = '" . $this->db->escape($address['address_2']) . "', custom_field = '" . $this->db->escape($address['custom_field']) . "', city = '" . $this->db->escape($address['city']) . "', postcode = '" . $this->db->escape($address['postcode']) . "', country_id = '" . (int)$address['country_id'] . "', zone_id = '" . (int)$address['zone_id'] . "'");
				
				$address_id = $this->db->getLastId();
				
				if ($is_default) {
					$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . $address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
				}
			}
		}
		
      	if (version_compare(VERSION, '1.5.5', '>=')) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "customer_history WHERE customer_id = '" . (int)$customer_id . "'");
			
			if (isset($data['history'])) {
				foreach ($data['history'] as $history) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "customer_history SET customer_id = '" . (int)$customer_id . "', comment = '" . $this->db->escape(strip_tags($history['comment'])) . "', date_added = '" . $history['date_added'] . "'");
				}
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");
		
		if (isset($data['transactions'])) {
			foreach ($data['transactions'] as $transaction) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "customer_transaction SET customer_id = '" . (int)$customer_id . "', order_id = '" . (int)$transaction['order_id'] . "', description = '" . $this->db->escape($transaction['description']) . "', amount = '" . (float)$transaction['amount'] . "', date_added = '" . $transaction['date_added'] . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "'");
		
		if (isset($data['reward_points'])) {			
			foreach ($data['reward_points'] as $reward) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "customer_reward SET customer_id = '" . (int)$customer_id . "', order_id = '" . (int)$reward['order_id'] . "', points = '" . (int)$reward['points'] . "', description = '" . $this->db->escape($reward['description']) . "', date_added = '" . $reward['date_added'] . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int)$customer_id . "'");
		
		if (isset($data['ip_addresses'])) {			
			$added = false;
			foreach ($data['ip_addresses'] as $ip) {
				if (!$added) {
					$max_ip = '';
					$max_date = '';
					
					foreach ($data['ip_addresses'] as $ip2) {
						if (strcmp($ip2['date_added'], $max_date) > 0) {
							$max_date = $ip2['date_added'];
							$max_ip = $ip2['ip'];
						}
					}
					
					if (!empty($max_ip)) {
						$this->db->query("UPDATE " . DB_PREFIX . "customer SET ip = '" . $this->db->escape($max_ip) . "' WHERE customer_id = '" . (int)$customer_id . "'");
					}
					
					$added = true;
				}
				
				$this->db->query("INSERT INTO " . DB_PREFIX . "customer_ip SET customer_id = '" . (int)$customer_id . "', ip = '" . $this->db->escape($ip['ip']) . "', date_added = '" . $ip['date_added'] . "'");
			}
		}
		
		// Extras
		foreach ($this->extraGeneralFields['Customers'] as $extra) {
			if (!empty($extra['eval_add'])) {
				eval($extra['eval_add']);
			}
		}
		
		$this->cache->delete('customer');
	}
	
	public function editCustomer($customer_id, $data, &$languages) {
		$extra_select = '';
		if (version_compare(VERSION, '1.5.4', '>=')) {
			$extra_select = ", salt = '" . $this->db->escape($data['salt']) . "'";
		}

		if(isset($data['salt']) && $data['salt'] == ''){
			$data['password'] = md5($data['password']);
		}

		$customer_id = trim($customer_id);
		
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "'" . $extra_select . ", email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape($data['custom_field']) . "', newsletter = '" . (int)$data['newsletter'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', safe = '" . (int)$data['safe'] . "', cart = '" . $data['cart'] . "', wishlist = '" . $data['wishlist'] . "', password = '" . $this->db->escape($data['password']) . "', ip = '" . $this->db->escape($data['ip']) . "', store_id = '" . (int)($data['store_id']) . "', status = '" . (int)$data['status'] . "', approved = '" . (int)$data['approved'] . "', date_added = " . (!empty($data['date_added']) ? "'" . $this->db->escape($data['date_added']) . "'" : "NOW()") . " WHERE customer_id='" . $customer_id . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");
		
		if (isset($data['addresses'])) {		
      		foreach ($data['addresses'] as $address) {
				$extra_select = '';
				if (version_compare(VERSION, '1.5.3', '>=')) {
					
				}	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "address SET " . ( !empty($address['address_id']) ? "address_id = '" . (int)$address['address_id'] . "', " : "" ) . "customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($address['firstname']) . "', lastname = '" . $this->db->escape($address['lastname']) . "', company = '" . $this->db->escape($address['company']) . "'" . $extra_select . ", address_1 = '" . $this->db->escape($address['address_1']) . "', address_2 = '" . $this->db->escape($address['address_2']) . "', city = '" . $this->db->escape($address['city']) . "', postcode = '" . $this->db->escape($address['postcode']) . "', custom_field = '" . $this->db->escape($address['custom_field']) . "', country_id = '" . (int)$address['country_id'] . "', zone_id = '" . (int)$address['zone_id'] . "'");
				
				if (!empty($data['address_id'])) {
					$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . $data['address_id'] . "' WHERE customer_id = '" . (int)$customer_id . "'");
				}
			}
		}
		
      	if (version_compare(VERSION, '1.5.5', '>=')) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "customer_history WHERE customer_id = '" . (int)$customer_id . "'");
			
			if (isset($data['history'])) {
				foreach ($data['history'] as $history) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "customer_history SET customer_id = '" . (int)$customer_id . "', comment = '" . $this->db->escape(strip_tags($history['comment'])) . "', date_added = '" . $history['date_added'] . "'");
				}
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");
		
		if (isset($data['transactions'])) {
			foreach ($data['transactions'] as $transaction) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "customer_transaction SET customer_id = '" . (int)$customer_id . "', order_id = '" . (int)$transaction['order_id'] . "', description = '" . $this->db->escape($transaction['description']) . "', amount = '" . (float)$transaction['amount'] . "', date_added = '" . $transaction['date_added'] . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "'");
		
		if (isset($data['reward_points'])) {			
			foreach ($data['reward_points'] as $reward) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "customer_reward SET customer_id = '" . (int)$customer_id . "', order_id = '" . (int)$reward['order_id'] . "', points = '" . (int)$reward['points'] . "', description = '" . $this->db->escape($reward['description']) . "', date_added = '" . $reward['date_added'] . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int)$customer_id . "'");
		
		if (isset($data['ip_addresses'])) {
			foreach ($data['ip_addresses'] as $ip) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "customer_ip SET customer_id = '" . (int)$customer_id . "', ip = '" . $this->db->escape($ip['ip']) . "', date_added = '" . $ip['date_added'] . "'");
			}
		}
		
		// Extras
		foreach ($this->extraGeneralFields['Customers'] as $extra) {
			if (!empty($extra['eval_edit'])) {
				eval($extra['eval_edit']);
			}
		}
		
		$this->cache->delete('customer');
	}
	
	public function deleteCustomers() {
        if (version_compare(VERSION, '2.1.0.1', '>=')) {
		    $this->load->model('customer/customer');
        } else {
            $this->load->model('sale/customer');
        } 
		
		$ids = $this->db->query("SELECT customer_id FROM " . DB_PREFIX . "customer c");
		
		foreach ($ids->rows as $row) {
            if (version_compare(VERSION, '2.1.0.1', '>=')) {
                $this->model_customer_customer->deleteCustomer($row['customer_id']);
            } else {
                $this->model_sale_customer->deleteCustomer($row['customer_id']);
            }
				
		}
	}
}
?>
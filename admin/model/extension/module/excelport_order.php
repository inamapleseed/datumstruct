<?php 
class ModelExtensionModuleExcelportorder extends ModelExtensionModuleExcelport {
	public function importXLSOrders($file, $importLimit = 100) {
		if (!is_numeric($importLimit) || $importLimit < 10 || $importLimit > 800) throw new Exception($this->language->get('excelport_import_limit_invalid'));
		
		$progress = $this->getProgress();
		$progress['importedCount'] = !empty($progress['importedCount']) ? $progress['importedCount'] : 0;
		$progress['done'] = false;
		
		require_once(IMODULE_ROOT.'vendors/phpexcel/PHPExcel.php');
		// Create new PHPExcel object
		
		require_once(IMODULE_ROOT.'vendors/phpexcel/CustomReadFilter.php');
		$chunkFilter = new CustomReadFilter(array(
			'Orders' => array('A', ($progress['importedCount'] + 2), 'AM', (($progress['importedCount'] + $importLimit) + 1)),
		), true); 
		
		$madeImports = false;
		$objReader = new PHPExcel_Reader_Excel2007();
		$objReader->setReadFilter($chunkFilter);
		$objReader->setReadDataOnly(true);
		$objReader->setLoadSheetsOnly(array("Orders", "Products", "Options", "Downloads", "Totals", "Histories", "Vouchers", "orders", "products", "options", "downloads", "totals", "histories", "vouchers"));
		$objPHPExcel = $objReader->load($file);
		$progress['importingFile'] = substr($file, strripos($file, '/') + 1);
		
		$ordersSheet = 0;
		$productsSheet = 1;
		$optionsSheet = 2;
		$downloadsSheet = 3;
		$totalsSheet = 4;
		$historiesSheet = 5;
		$vouchersSheet = 6;
		
		$orderSheetObj = $objPHPExcel->setActiveSheetIndex($ordersSheet);
		$productSheetObj = $objPHPExcel->setActiveSheetIndex($productsSheet);
		$optionSheetObj = $objPHPExcel->setActiveSheetIndex($optionsSheet);
		$downloadSheetObj = $objPHPExcel->setActiveSheetIndex($downloadsSheet);
		$totalSheetObj = $objPHPExcel->setActiveSheetIndex($totalsSheet);
		$historySheetObj = $objPHPExcel->setActiveSheetIndex($historiesSheet);
		$voucherSheetObj = $objPHPExcel->setActiveSheetIndex($vouchersSheet);
		
		$progress['all'] = -1; //(int)(($orderSheetObj->getHighestRow() - 2)/$this->orderSize);
		$this->setProgress($progress);
		
		$this->load->model('sale/order');
		
		$map = array(
			'order_id' 					=> 0,
			'invoice_no'				=> 1,
			'invoice_prefix'			=> 2,
			'store_id'					=> 3,
			'customer_id' 				=> 4,
			'customer_group_id'			=> 5,
			'firstname'					=> 6,
			'lastname'					=> 7,
			'email'						=> 8,
			'telephone'					=> 9,
			'fax'						=> 10,
			'payment_firstname'			=> 11,
			'payment_lastname'			=> 12,
			'payment_company'			=> 13,
			'payment_address_1'			=> 16,
			'payment_address_2'			=> 17,
			'payment_city'				=> 18,
			'payment_postcode'			=> 19,
			'payment_country'			=> 20,
			'payment_zone'				=> 21,
			'payment_address_format'	=> 22,
			'payment_method'			=> 23,
			'payment_code'				=> 24,
			'shipping_firstname'		=> 25,
			'shipping_lastname'			=> 26,
			'shipping_company'			=> 27,
			'shipping_address_1'		=> 28,
			'shipping_address_2'		=> 29,
			'shipping_city'				=> 30,
			'shipping_postcode'			=> 31,
			'shipping_country'			=> 32,
			'shipping_zone'				=> 33,
			'shipping_address_format'	=> 34,
			'shipping_method'			=> 35,
			'shipping_code'				=> 36,
			'comment'					=> 37,
			'total'						=> 38,
			'order_status_id'			=> 39,
			'affiliate_id'				=> 40,
			'commission'				=> 41,
			'language_id'				=> 42,
			'currency_id'				=> 43,
			'currency_code'				=> 44,
			'currency_value'			=> 45,
			'ip'						=> 46,
			'forwarded_ip'				=> 47,
			'user_agent'				=> 48,
			'accept_language'			=> 49,
			'date_added'				=> 50,
			'date_modified'				=> 51,
			'reward'					=> 52,
			'custom_field'					=> 53,
			'payment_custom_field'	=> 54,
			'shipping_custom_field'	=> 55,
			'marketing_id'					=> 56,
			'tracking'							=> 57
		);

		$map_product = array(
			'order_product_id' 			=> 0,
			'order_id' 					=> 1,
			'product_id' 				=> 2,
			'name' 						=> 3,
			'model' 					=> 4,
			'quantity' 					=> 5,
			'price' 					=> 6,
			'total' 					=> 7,
			'tax' 						=> 8,
			'reward' 					=> 9
		);

		$map_option = array(
			'order_option_id' 			=> 0,
			'order_id' 					=> 1,
			'order_product_id' 			=> 2,
			'product_option_id'			=> 3,
			'product_option_value_id'	=> 4,
			'name' 						=> 5,
			'value' 					=> 6,
			'type' 						=> 7
		);

		// $map_download = array(
		// 	'order_download_id' 		=> 0,
		// 	'order_id' 					=> 1,
		// 	'order_product_id' 			=> 2,
		// 	'name' 						=> 3,
		// 	'filename' 					=> 4,
		// 	'mask' 						=> 5,
		// 	'remaining' 				=> 6
		// );

		$map_total = array(
			'order_total_id' 			=> 0,
			'order_id' 					=> 1,
			'code' 						=> 2,
			'title' 					=> 3,
			'value' 					=> 5,
			'sort_order' 				=> 6
		);

		$map_history = array(
			'order_history_id' 			=> 0,
			'order_id' 					=> 1,
			'order_status_id' 			=> 2,
			'notify' 					=> 3,
			'comment' 					=> 4,
			'date_added' 				=> 5
		);

		$map_voucher = array(
			'order_voucher_id' => 0,
			'order_id' => 1,
			'voucher_id' => 2,
			'description' => 3,
			'code' => 4,
			'from_name' => 5,
			'from_email' => 6,
			'to_name' => 7,
			'to_email' => 8,
			'voucher_theme_id' => 9,
			'message' => 10,
			'amount' => 11
		);
		
		$source = array(0,2 + ($progress['importedCount']));
		
		$this->load->model('setting/store');
		$stores = array_merge(array(0 => array('store_id' => 0, 'name' => $this->config->get('config_name'), 'url' => HTTP_CATALOG, 'ssl' => HTTP_CATALOG)), $this->model_setting_store->getStores());

		$this->load->model('localisation/country');
		$countries = $this->model_localisation_country->getCountries();

		$this->load->model('localisation/zone');
		$zones = $this->model_localisation_zone->getZones();

		do {
			$this->custom_set_time_limit();
			
			$source_product = array(0,2);
			$source_option = array(0,2);
			$source_download = array(0,2);
			$source_total = array(0,2);
			$source_history = array(0,2);
			$source_voucher = array(0,2);

			$order_store_id = strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['store_id']) . ($source[1]))->getValue()));

			if ($order_store_id !== '') {
				$store_name = '';
				$store_url = '';

				foreach ($stores as $store) {
					if ($store['store_id'] == $order_store_id) {
						$store_name = $store['name'];
						$store_url = $store['url'];
						break;
					}
				}

				$payment_country_id = 0;
				$shipping_country_id = 0;
				$payment_country = strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['payment_country']) . ($source[1]))->getValue()));
				$shipping_country = strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['shipping_country']) . ($source[1]))->getValue()));
				foreach ($countries as $country) {
					if (strtolower($payment_country) == strtolower($country['name'])) {
						$payment_country_id = $country['country_id'];
					}

					if (strtolower($shipping_country) == strtolower($country['name'])) {
						$shipping_country_id = $country['country_id'];
					}
				}

				$payment_zone_id = 0;
				$shipping_zone_id = 0;
				$payment_zone = strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['payment_zone']) . ($source[1]))->getValue()));
				$shipping_zone = strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['shipping_zone']) . ($source[1]))->getValue()));
				foreach ($zones as $zone) {
					if (strtolower($payment_zone) == strtolower($zone['name'])) {
						$payment_zone_id = $zone['zone_id'];
					}

					if (strtolower($shipping_zone) == strtolower($zone['name'])) {
						$shipping_zone_id = $zone['zone_id'];
					}
				}

				$order_id = strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['order_id']) . ($source[1]))->getValue()));

				$order_product = array();
				do {
					$order_product_order_id = strval(trim($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source_product[0] + $map_product['order_id']) . ($source_product[1]))->getValue()));
					if ($order_product_order_id == $order_id) {
						$order_product_temp = array();
						foreach ($map_product as $order_product_key => $order_product_val) {
							$order_product_temp[$order_product_key] = strval(trim($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source_product[0] + $order_product_val) . ($source_product[1]))->getValue()));
						}
						$order_product[] = $order_product_temp;
					}
					$source_product[1] += 1;
				} while (!empty($order_product_order_id));

				$order_option = array();
				do {
					$order_option_order_id = strval(trim($optionSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source_option[0] + $map_option['order_id']) . ($source_option[1]))->getValue()));
					if ($order_option_order_id == $order_id) {
						$order_option_temp = array();
						foreach ($map_option as $order_option_key => $order_option_val) {
							$order_option_temp[$order_option_key] = strval(trim($optionSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source_option[0] + $order_option_val) . ($source_option[1]))->getValue()));
						}
						$order_option[] = $order_option_temp;
					}
					$source_option[1] += 1;
				} while (!empty($order_option_order_id));

				// $order_download = array();
				// do {
				// 	$order_download_order_id = strval(trim($downloadSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source_download[0] + $map_download['order_id']) . ($source_download[1]))->getValue()));
				// 	if ($order_download_order_id == $order_id) {
				// 		$order_download_temp = array();
				// 		foreach ($map_download as $order_download_key => $order_download_val) {
				// 			$order_download_temp[$order_download_key] = strval(trim($downloadSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source_download[0] + $order_download_val) . ($source_download[1]))->getValue()));
				// 		}
				// 		$order_download[] = $order_download_temp;
				// 	}
				// 	$source_download[1] += 1;
				// } while (!empty($order_download_order_id));

				$order_total = array();
				do {
					$order_total_order_id = strval(trim($totalSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source_total[0] + $map_total['order_id']) . ($source_total[1]))->getValue()));
					if ($order_total_order_id == $order_id) {
						$order_total_temp = array();
						foreach ($map_total as $order_total_key => $order_total_val) {
							$order_total_temp[$order_total_key] = strval(trim($totalSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source_total[0] + $order_total_val) . ($source_total[1]))->getValue()));
						}
						$order_total[] = $order_total_temp;
					}
					$source_total[1] += 1;
				} while (!empty($order_total_order_id));

				$order_history = array();
				do {
					$order_history_order_id = strval(trim($historySheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source_history[0] + $map_history['order_id']) . ($source_history[1]))->getValue()));
					if ($order_history_order_id == $order_id) {
						$order_history_temp = array();
						foreach ($map_history as $order_history_key => $order_history_val) {
							$order_history_temp[$order_history_key] = strval(trim($historySheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source_history[0] + $order_history_val) . ($source_history[1]))->getValue()));
						}
						$order_history[] = $order_history_temp;
					}
					$source_history[1] += 1;
				} while (!empty($order_history_order_id));

				$order_voucher = array();
				do {
					$order_voucher_order_id = strval(trim($voucherSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source_voucher[0] + $map_voucher['order_id']) . ($source_voucher[1]))->getValue()));
					if ($order_voucher_order_id == $order_id) {
						$order_voucher_temp = array();
						foreach ($map_voucher as $order_voucher_key => $order_voucher_val) {
							$order_voucher_temp[$order_voucher_key] = strval(trim($voucherSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source_voucher[0] + $order_voucher_val) . ($source_voucher[1]))->getValue()));
						}
						$order_voucher[] = $order_voucher_temp;
					}
					$source_voucher[1] += 1;
				} while (!empty($order_voucher_order_id));

				$order = array(
					'order_id' 					=> $order_id,
					'invoice_no'				=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['invoice_no']) . ($source[1]))->getValue())),
					'invoice_prefix'			=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['invoice_prefix']) . ($source[1]))->getValue())),
					'store_id'					=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['store_id']) . ($source[1]))->getValue())),
					'store_name'				=> $store_name,
					'store_url'					=> $store_url,
					'customer_id' 				=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['customer_id']) . ($source[1]))->getValue())),
					'customer_group_id'			=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['customer_group_id']) . ($source[1]))->getValue())),
					'firstname'					=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['firstname']) . ($source[1]))->getValue())),
					'lastname'					=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['lastname']) . ($source[1]))->getValue())),
					'email'						=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['email']) . ($source[1]))->getValue())),
					'telephone'					=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['telephone']) . ($source[1]))->getValue())),
					'fax'						=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['fax']) . ($source[1]))->getValue())),
					'payment_firstname'			=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['payment_firstname']) . ($source[1]))->getValue())),
					'payment_lastname'			=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['payment_lastname']) . ($source[1]))->getValue())),
					'payment_company'			=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['payment_company']) . ($source[1]))->getValue())),
					'payment_address_1'			=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['payment_address_1']) . ($source[1]))->getValue())),
					'payment_address_2'			=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['payment_address_2']) . ($source[1]))->getValue())),
					'payment_city'				=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['payment_city']) . ($source[1]))->getValue())),
					'payment_postcode'			=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['payment_postcode']) . ($source[1]))->getValue())),
					'payment_country'			=> $payment_country, 
					'payment_country_id'		=> $payment_country_id, 
					'payment_zone'				=> $payment_zone,
					'payment_zone_id'			=> $payment_zone_id,
					'payment_address_format'	=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['payment_address_format']) . ($source[1]))->getValue())),
					'payment_method'			=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['payment_method']) . ($source[1]))->getValue())),
					'payment_code'				=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['payment_code']) . ($source[1]))->getValue())),
					'shipping_firstname'		=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['shipping_firstname']) . ($source[1]))->getValue())),
					'shipping_lastname'			=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['shipping_lastname']) . ($source[1]))->getValue())),
					'shipping_company'			=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['shipping_company']) . ($source[1]))->getValue())),
					'shipping_address_1'		=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['shipping_address_1']) . ($source[1]))->getValue())),
					'shipping_address_2'		=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['shipping_address_2']) . ($source[1]))->getValue())),
					'shipping_city'				=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['shipping_city']) . ($source[1]))->getValue())),
					'shipping_postcode'			=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['shipping_postcode']) . ($source[1]))->getValue())),
					'shipping_country'			=> $shipping_country, 
					'shipping_country_id'		=> $shipping_country_id, 
					'shipping_zone'				=> $shipping_zone,
					'shipping_zone_id'			=> $shipping_zone_id,
					'shipping_address_format'	=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['shipping_address_format']) . ($source[1]))->getValue())),
					'shipping_method'			=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['shipping_method']) . ($source[1]))->getValue())),
					'shipping_code'				=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['shipping_code']) . ($source[1]))->getValue())),
					'comment'					=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['comment']) . ($source[1]))->getValue())),
					'total'						=> (float)str_replace(array(' ', ','), array('', '.'), strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['total']) . ($source[1]))->getValue()))),
					'order_status_id'			=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['order_status_id']) . ($source[1]))->getValue())), 
					'affiliate_id'				=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['affiliate_id']) . ($source[1]))->getValue())), 
					'commission'				=> (float)str_replace(array(' ', ','), array('', '.'), strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['commission']) . ($source[1]))->getValue()))),
					'language_id'				=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['language_id']) . ($source[1]))->getValue())), 
					'currency_id'				=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['currency_id']) . ($source[1]))->getValue())), 
					'currency_code'				=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['currency_code']) . ($source[1]))->getValue())), 
					'currency_value'			=> (float)str_replace(array(' ', ','), array('', '.'), strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['currency_value']) . ($source[1]))->getValue()))),
					'ip'						=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['ip']) . ($source[1]))->getValue())),
					'forwarded_ip'				=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['forwarded_ip']) . ($source[1]))->getValue())),
					'user_agent'				=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['user_agent']) . ($source[1]))->getValue())),
					'accept_language'			=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['accept_language']) . ($source[1]))->getValue())),
					'date_added'				=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['date_added']) . ($source[1]))->getValue())),
					'date_modified'				=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['date_modified']) . ($source[1]))->getValue())),
					'order_product' 			=> $order_product,
					'order_option' 				=> $order_option,
					//'order_download' 			=> $order_download,
					'order_total'				=> $order_total,
					'order_history' 			=> $order_history,
					'order_voucher' 			=> $order_voucher,
					'reward'					=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['reward']) . ($source[1]))->getValue())),
					'custom_field'				=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['custom_field']) . ($source[1]))->getValue())),
					'payment_custom_field'				=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['payment_custom_field']) . ($source[1]))->getValue())),
					'shipping_custom_field'				=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['shipping_custom_field']) . ($source[1]))->getValue())),
					'marketing_id'				=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['marketing_id']) . ($source[1]))->getValue())),
					'tracking'				=> strval(trim($orderSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['tracking']) . ($source[1]))->getValue()))
				);
				
				// Extras
				foreach ($this->extraGeneralFields['Orders'] as $extra) {
					if (!empty($extra['name']) && !empty($extra['column_light'])) {
						$order[$extra['name']] = $orderSheetObj->getCell($extra['column_light'] . $source[1])->getValue();	
					}
				}
				
				if (!empty($order_id)) {
					$exists = false;
					$existsQuery = $this->db->query("SELECT order_id FROM `" . DB_PREFIX . "order` WHERE order_id = ".$order_id);
					
					$exists = $existsQuery->num_rows > 0;
							
					if ($exists) {
						$this->editOrder($order_id, $order);
					} else {
						$this->addOrder($order_id, $order);
					}
				} else {
					$this->addOrder('', $order);
				}
				
				$progress['current']++;
				$progress['importedCount']++;
				$madeImports = true;
				$this->setProgress($progress);
			}
			$source[1] += 1;
		} while ($order_store_id !== '');
		$progress['done'] = true;
		if (!$madeImports) {
			$progress['importedCount'] = 0;
			array_shift($this->session->data['uploaded_files']);
		}
		$this->setProgress($progress);
	}
	
	public function exportXLSOrders($destinationFolder = '', $orderNumber, $export_filters = array()) {
		$this->language->load('extension/module/excelport');
		$this->folderCheck($destinationFolder);
		
		$progress = $this->getProgress();
		$progress['done'] = false;
		
		$file = IMODULE_ROOT . 'vendors/excelport/template_order.xlsx';
		require_once(IMODULE_ROOT.'vendors/phpexcel/PHPExcel.php');
		
		if (!empty($progress['populateAll'])) {
			$all = $this->db->query($this->getQuery($export_filters, true));
			$progress['all'] = $all->num_rows ? (int)$all->row['count'] : 0;
			unset($progress['populateAll']);
			$this->setProgress($progress);
		}
		
		$ordersSheet = 0;
		$productsSheet = 1;
		$optionsSheet = 2;
		$downloadsSheet = 3;
		$totalsSheet = 4;
		$historiesSheet = 5;
		$vouchersSheet = 6;
		$metaSheet = 7;
		
		$generals = array(
			'order_id' 					=> 0,
			'invoice_no'				=> 1,
			'invoice_prefix'			=> 2,
			'store_id'					=> 3, // Need to list the stores
			'customer_id' 				=> 4,
			'customer_group_id'			=> 5, // Need to be outputted in the Meta sheet
			'firstname'					=> 6,
			'lastname'					=> 7,
			'email'						=> 8,
			'telephone'					=> 9,
			'fax'						=> 10,
			'payment_firstname'			=> 11,
			'payment_lastname'			=> 12,
			'payment_company'			=> 13,
			'payment_address_1'			=> 16,
			'payment_address_2'			=> 17,
			'payment_city'				=> 18,
			'payment_postcode'			=> 19,
			'payment_country'			=> 20, // Need to be outputted in the Meta sheet
			'payment_zone'				=> 21, // Needs to be a valid value
			'payment_address_format'	=> 22,
			'payment_method'			=> 23,
			'payment_code'				=> 24,
			'shipping_firstname'		=> 25,
			'shipping_lastname'			=> 26,
			'shipping_company'			=> 27,
			'shipping_address_1'		=> 28,
			'shipping_address_2'		=> 29,
			'shipping_city'				=> 30,
			'shipping_postcode'			=> 31,
			'shipping_country'			=> 32, // Need to be outputted in the Meta sheet
			'shipping_zone'				=> 33, // Needs to be a valid value
			'shipping_address_format'	=> 34,
			'shipping_method'			=> 35,
			'shipping_code'				=> 36,
			'comment'					=> 37,
			'total'						=> 38,
			'order_status_id'			=> 39, // Need to be outputted in the Meta sheet
			'affiliate_id'				=> 40, // Need to be outputted in the Meta sheet
			'commission'				=> 41,
			'language_id'				=> 42, // Need to be outputted in the Meta sheet
			'currency_id'				=> 43, // Need to be outputted in the Meta sheet
			'currency_code'				=> 44, // Need to be outputted in the Meta sheet
			'currency_value'			=> 45,
			'ip'						=> 46,
			'forwarded_ip'				=> 47,
			'user_agent'				=> 48,
			'accept_language'			=> 49,
			'date_added'				=> 50,
			'date_modified'				=> 51,
			'reward'					=> 52,
			'custom_field'					=> 53,
			'payment_custom_field'					=> 54,
			'shipping_custom_field'					=> 55,
			'marketing_id'					=> 56,
			'tracking'					=> 57
		);
		
		$generals_products = array(
			'order_product_id' 			=> 0,
			'order_id' 					=> 1,
			'product_id' 				=> 2,
			'name' 						=> 3,
			'model' 					=> 4,
			'quantity' 					=> 5,
			'price' 					=> 6,
			'total' 					=> 7,
			'tax' 						=> 8,
			'reward' 					=> 9
		);
		


		$generals_options = array(
			'order_option_id' 			=> 0,
			'order_id' 					=> 1,
			'order_product_id' 			=> 2,
			'product_option_id'			=> 3,
			'product_option_value_id'	=> 4,
			'name' 						=> 5,
			'value' 					=> 6,
			'type' 						=> 7
		);
		
		// $generals_downloads = array(
		// 	'order_download_id' 		=> 0,
		// 	'order_id' 					=> 1,
		// 	'order_product_id' 			=> 2,
		// 	'name' 						=> 3,
		// 	'filename' 					=> 4,
		// 	'mask' 						=> 5,
		// 	'remaining' 				=> 6
		// );
		
		$generals_totals = array(
			'order_total_id' 			=> 0,
			'order_id' 					=> 1,
			'code' 						=> 2,
			'title' 					=> 3,
			'value' 					=> 5,
			'sort_order' 				=> 6
		);
		
		$generals_histories = array(
			'order_history_id' 			=> 0,
			'order_id' 					=> 1,
			'order_status_id' 			=> 2,
			'notify' 					=> 3,
			'comment' 					=> 4,
			'date_added' 				=> 5
		);
		
		$generals_vouchers = array(
			'order_voucher_id' => 0,
			'order_id' => 1,
			'voucher_id' => 2,
			'description' => 3,
			'code' => 4,
			'from_name' => 5,
			'from_email' => 6,
			'to_name' => 7,
			'to_email' => 8,
			'voucher_theme_id' => 9,
			'message' => 10,
			'amount' => 11
		);

		if (VERSION > '1.5.1.3') {
			unset($generals['reward']);
		}

		if (VERSION <= '1.5.1.3') {
			unset($generals['shipping_code']);
			unset($generals['payment_code']);
			unset($generals['forwarded_ip']);
			unset($generals['user_agent']);
			unset($generals['accept_language']);
			unset($generals_products['reward']);
		}

		if (VERSION < '1.5.3') {

		}

		// Extra fields
		$extras = array();
		foreach ($this->extraGeneralFields['Orders'] as $extra) {
			if (!empty($extra['name']) && !empty($extra['column_light'])) {
				$extras[$extra['name']] = $extra['column_light'];
			}
		}
		
		$storesStart = array(0,3);
		$this->load->model('setting/store');
		$stores = array_merge(array(0 => array('store_id' => 0, 'name' => 'Default', 'url' => NULL, 'ssl' => NULL)), $this->model_setting_store->getStores());
		
		$customerGroupsStart = array(2,3);
        if (version_compare(VERSION, '2.1.0.1', '>=')) {
            $this->load->model('customer/customer_group');
            $customerGroups = $this->model_customer_customer_group->getCustomerGroups();
        } else {
            $this->load->model('sale/customer_group');
            $customerGroups = $this->model_sale_customer_group->getCustomerGroups();
        }
		
		$countriesStart = array(4,2);
		$this->load->model('localisation/country');
		$countries = $this->model_localisation_country->getCountries();
		
		$orderStatusesStart = array(5,3);
		$this->load->model('localisation/order_status');
		$orderStatuses = $this->model_localisation_order_status->getOrderStatuses();
		
		$affiliatesStart = array(7,3);
		$this->load->model('marketing/affiliate');
		$affiliates = $this->model_marketing_affiliate->getAffiliates();
		
		$languagesStart = array(9,3);
		$this->load->model('localisation/language');
		$languages = array_values($this->model_localisation_language->getLanguages());
		
		$currenciesStart = array(11,3);
		$this->load->model('localisation/currency');
		$currencies = array_values($this->model_localisation_currency->getCurrencies());
		
		$shippingMethodsStart = array(13,3);
		$shippingMethods = $this->getShippingMethods();

		$paymentMethodsStart = array(15,3);
		$paymentMethods = $this->getPaymentMethods();

		$dataValidations = array(
			array(
				'type' => 'list',
				'field' => $generals['payment_country'],
				'data' => array($countriesStart[0], $countriesStart[1], $countriesStart[0], $countriesStart[1] + count($countries) - 1),
				'range' => '',
			)
		);
		
		$target = array(0,2);
		$target_products = array(0,2);
		$target_options = array(0,2);
		$target_downloads = array(0,2);
		$target_totals = array(0,2);
		$target_histories = array(0,2);
		$target_vouchers = array(0,2);
		
		$this->load->model('localisation/language');
		$languageQuery = $this->model_localisation_language->getLanguage($this->config->get('config_language_id'));
		
		$name = 'orders_excelport_' . $languageQuery['code'] . '_' . str_replace('/', '_', substr(HTTP_CATALOG, 7, strlen(HTTP_CATALOG) - 8)) . '_' . date("Y-m-d_H-i-s") . '_' . $progress['current'];
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
		
		for ($i = 0; $i < count($stores); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($storesStart[0]) . ($storesStart[1] + $i), $stores[$i]['store_id'], PHPExcel_Cell_DataType::TYPE_STRING);
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($storesStart[0] + 1) . ($storesStart[1] + $i), $stores[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		
		for ($i = 0; $i < count($customerGroups); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($customerGroupsStart[0]) . ($customerGroupsStart[1] + $i), $customerGroups[$i]['customer_group_id'], PHPExcel_Cell_DataType::TYPE_STRING);
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($customerGroupsStart[0] + 1) . ($customerGroupsStart[1] + $i), $customerGroups[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		
		for ($i = 0; $i < count($countries); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($countriesStart[0]) . ($countriesStart[1] + $i), $countries[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		
		for ($i = 0; $i < count($orderStatuses); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($orderStatusesStart[0]) . ($orderStatusesStart[1] + $i), $orderStatuses[$i]['order_status_id'], PHPExcel_Cell_DataType::TYPE_STRING);
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($orderStatusesStart[0] + 1) . ($orderStatusesStart[1] + $i), $orderStatuses[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		
		for ($i = 0; $i < count($affiliates); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($affiliatesStart[0]) . ($affiliatesStart[1] + $i), $affiliates[$i]['affiliate_id'], PHPExcel_Cell_DataType::TYPE_STRING);
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($affiliatesStart[0] + 1) . ($affiliatesStart[1] + $i), $affiliates[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		
		foreach ($languages as $i => $temp_language) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($languagesStart[0]) . ($languagesStart[1] + $i), $languages[$i]['language_id'], PHPExcel_Cell_DataType::TYPE_STRING);
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($languagesStart[0] + 1) . ($languagesStart[1] + $i), $languages[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		
		foreach ($currencies as $i => $temp_currency) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($currenciesStart[0]) . ($currenciesStart[1] + $i), $currencies[$i]['currency_id'], PHPExcel_Cell_DataType::TYPE_STRING);
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($currenciesStart[0] + 1) . ($currenciesStart[1] + $i), $currencies[$i]['code'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		
		foreach ($shippingMethods as $i => $temp_shipping_method) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($shippingMethodsStart[0]) . ($shippingMethodsStart[1] + $i), $shippingMethods[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($shippingMethodsStart[0] + 1) . ($shippingMethodsStart[1] + $i), $shippingMethods[$i]['code'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		
		foreach ($paymentMethods as $i => $temp_payment_method) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($paymentMethodsStart[0]) . ($paymentMethodsStart[1] + $i), $paymentMethods[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($paymentMethodsStart[0] + 1) . ($paymentMethodsStart[1] + $i), $paymentMethods[$i]['code'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		
		$this->load->model('sale/order');
		
		$extra_select = "";
		
		$this->db->query("SET SESSION group_concat_max_len = 1000000;");
		
		$orders = $this->db->query($this->getQuery($export_filters) . " ORDER BY o.order_id LIMIT ". $progress['current'] . ", " . $orderNumber);
		
		$orderSheetObj = $objPHPExcel->setActiveSheetIndex($ordersSheet);
		$productSheetObj = $objPHPExcel->setActiveSheetIndex($productsSheet);
		$optionSheetObj = $objPHPExcel->setActiveSheetIndex($optionsSheet);
		$downloadSheetObj = $objPHPExcel->setActiveSheetIndex($downloadsSheet);
		$totalSheetObj = $objPHPExcel->setActiveSheetIndex($totalsSheet);
		$historySheetObj = $objPHPExcel->setActiveSheetIndex($historiesSheet);
		$voucherSheetObj = $objPHPExcel->setActiveSheetIndex($vouchersSheet);
		
		foreach ($this->extraGeneralFields['Orders'] as $extra) {
			if (!empty($extra['title']) && !empty($extra['column_light'])) {
				$orderSheetObj->setCellValueExplicit($extra['column_light'] . '1', $extra['title'], PHPExcel_Cell_DataType::TYPE_STRING);
			}
		}

		if ($orders->num_rows > 0) {
			foreach ($orders->rows as $myCustomerGroupIndex => $row) {

				// Add data
				// Extras
				foreach ($extras as $name => $position) {
					$orderSheetObj->setCellValueExplicit($position . ($target[1]), empty($row[$name]) ? '' : $row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
				}

				// General
				foreach ($generals as $name => $position) {
					$orderSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target[0] + $position) . ($target[1]), empty($row[$name]) && $row[$name] !== '0' ? '' : $row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
				}
				
				$order_products = $this->model_sale_order->getOrderProducts($row['order_id']);
				
				foreach($order_products as $order_product) {
					// Order Products
					foreach ($generals_products as $name_2 => $position_2) {
						$productSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target_products[0] + $position_2) . ($target_products[1]), empty($order_product[$name_2]) && $order_product[$name_2] !== '0' ? '' : $order_product[$name_2], PHPExcel_Cell_DataType::TYPE_STRING);
					}
					$target_products[1] = $target_products[1] + 1;
				}
				
				$order_options = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$row['order_id'] . "'");
				
				foreach($order_options->rows as $order_option) {
					// Order options
					foreach ($generals_options as $name_3 => $position_3) {
						$optionSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target_options[0] + $position_3) . ($target_options[1]), empty($order_option[$name_3]) && $order_option[$name_3] !== '0' ? '' : $order_option[$name_3], PHPExcel_Cell_DataType::TYPE_STRING);
					}
					$target_options[1] = $target_options[1] + 1;
				}
				
				// $order_downloads = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$row['order_id'] . "'");
				
				// foreach($order_downloads->rows as $order_download) {
				// 	// Order downloads
				// 	foreach ($generals_downloads as $name_4 => $position_4) {
				// 		$downloadSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target_downloads[0] + $position_4) . ($target_downloads[1]), empty($order_download[$name_4]) && $order_download[$name_4] !== '0' ? '' : $order_download[$name_4], PHPExcel_Cell_DataType::TYPE_STRING);
				// 	}
				// 	$target_downloads[1] = $target_downloads[1] + 1;
				// }
				
				$order_totals = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$row['order_id'] . "'");
				
				foreach($order_totals->rows as $order_total) {
					// Order totals
					foreach ($generals_totals as $name_5 => $position_5) {
						$totalSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target_totals[0] + $position_5) . ($target_totals[1]), empty($order_total[$name_5]) && $order_total[$name_5] !== '0' ? '' : $order_total[$name_5], PHPExcel_Cell_DataType::TYPE_STRING);
					}
					$target_totals[1] = $target_totals[1] + 1;
				}
				
				$order_histories = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int)$row['order_id'] . "'");
				
				foreach($order_histories->rows as $order_history) {
					// Order histories
					foreach ($generals_histories as $name_5 => $position_5) {
						$historySheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target_histories[0] + $position_5) . ($target_histories[1]), empty($order_history[$name_5]) && $order_history[$name_5] !== '0' ? '' : $order_history[$name_5], PHPExcel_Cell_DataType::TYPE_STRING);
					}
					$target_histories[1] = $target_histories[1] + 1;
				}
				
				if (VERSION > '1.5.1.3') {
					$order_vouchers = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$row['order_id'] . "'");
					
					foreach($order_vouchers->rows as $order_voucher) {
						// Order vouchers
						foreach ($generals_vouchers as $name_6 => $position_6) {
							$voucherSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target_vouchers[0] + $position_6) . ($target_vouchers[1]), empty($order_voucher[$name_6]) && $order_voucher[$name_6] !== '0' ? '' : $order_voucher[$name_6], PHPExcel_Cell_DataType::TYPE_STRING);
						}
						$target_vouchers[1] = $target_vouchers[1] + 1;
					}
				}

				$target[1] = $target[1] + 1;
				$progress['current']++;
				$progress['memory_get_usage'] = round(memory_get_usage(true)/(1024*1024));
				$progress['percent'] = 100 / ($orders->num_rows / $progress['current']);
				
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
		unset($metaSheetObj);
		unset($objWriter);
		unset($orderSheetObj);
		unset($objPHPExcel);
		
		$progress['done'] = true;
		$this->setProgress($progress);
		
		return true;
	}
	
	public function getShippingMethods() {
		return $this->db->query("SELECT DISTINCT shipping_method as name, shipping_code as code FROM `" . DB_PREFIX . "order` ORDER BY shipping_method ASC")->rows;
	}
	
	public function getPaymentMethods() {
		return $this->db->query("SELECT DISTINCT payment_method as name, payment_code as code FROM `" . DB_PREFIX . "order` ORDER BY payment_method ASC")->rows;
	}

	public function getQuery($filters = array(), $count = false) {
		if (empty($filters) || !in_array($filters['Conjunction'], array('AND', 'OR'))) $filters['Conjunction'] = 'OR';
		
		$join_rules = array(
			//'order_description' => "LEFT JOIN " . DB_PREFIX . "order_description cgd ON (cg.order_id = cgd.order_id AND cgd.language_id = '" . $language . "')"
		);
		
		$joins = array();
		
		if (version_compare(VERSION, '1.5.3', '>=')) {
			//$joins['order_description'] = $join_rules['order_description'];
		}
		
		$wheres = array();
		
		foreach ($filters as $i => $filter) {
			if (is_array($filter)) {
				if (!array_key_exists($this->conditions['Orders'][$filter['Field']]['join_table'], $joins) && array_key_exists($this->conditions['Orders'][$filter['Field']]['join_table'], $join_rules)) {
					$joins[$this->conditions['Orders'][$filter['Field']]['join_table']] = $join_rules[$this->conditions['Orders'][$filter['Field']]['join_table']];
				}
				if (!is_array($this->conditions['Orders'][$filter['Field']]['field_name'])) {
					$condition = str_replace(array('{FIELD_NAME}', '{WORD}'), array($this->conditions['Orders'][$filter['Field']]['field_name'], stripos($this->conditions['Orders'][$filter['Field']]['type'], 'number') !== FALSE ? (int)$this->db->escape($filter['Value']) : $this->db->escape($filter['Value'])), $this->operations[$filter['Condition']]['operation']);
				} else {
					$sub_conditions = array();
					foreach ($this->conditions['Orders'][$filter['Field']]['field_name'] as $field_name) {
						$sub_conditions[] = str_replace(array('{FIELD_NAME}', '{WORD}'), array($field_name, stripos($this->conditions['Orders'][$filter['Field']]['type'], 'number') !== FALSE ? (int)$this->db->escape($filter['Value']) : $this->db->escape($filter['Value'])), $this->operations[$filter['Condition']]['operation']);
					}
					$condition = '(' . implode(' OR ', $sub_conditions) . ')';
				}
				if (!in_array($condition, $wheres)) $wheres[] = $condition;
			}
		}
		
		$select = $count ? "COUNT(*)" : "*" . ('') . "";
		
		$query = ($count ? "SELECT COUNT(*) as count FROM (" : "") . "SELECT " . $select . " FROM `" . DB_PREFIX . "order` o " . implode(" ", $joins) . " " . (!empty($wheres) ? " WHERE (" . implode(" " . $filters['Conjunction'] . " ", $wheres) . ")" : "") . " GROUP BY o.order_id" . ($count ? ") as count_table" : "");
		
		return $query;
	}
	
	public function addOrder($order_id = '', $data) {
		$order_id = trim($order_id);

		$extra_insert = array();

		if (VERSION <= '1.5.1.3') {
			$extra_insert[] = "reward = '" . $this->db->escape($data['reward']) . "'";
		}

		if (VERSION > '1.5.1.3') {
			$extra_insert[] = "shipping_code = '" . $this->db->escape($data['shipping_code']) . "'";
			$extra_insert[] = "payment_code = '" . $this->db->escape($data['payment_code']) . "'";
			$extra_insert[] = "forwarded_ip = '" . $this->db->escape($data['forwarded_ip']) . "'";
			$extra_insert[] = "user_agent = '" . $this->db->escape($data['user_agent']) . "'";
			$extra_insert[] = "accept_language = '" . $this->db->escape($data['accept_language']) . "'";
		}

		if (VERSION >= '1.5.3') {
			
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET ".(!empty($order_id) ? "order_id = '" . (int)trim($order_id) . "', " : "")."invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', store_id = '" . (int)$data['store_id'] . "', invoice_no = '" . (int)$data['invoice_no'] . "', store_name = '" . $this->db->escape($data['store_name']) . "',store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', comment = '" . $this->db->escape($data['comment']) . "', total = '" . $this->db->escape($data['total']) . "', order_status_id = '" . (int)$data['order_status_id'] . "', affiliate_id  = '" . (int)$data['affiliate_id'] . "', commission = '" . $this->db->escape($data['commission']) . "', language_id = '" . (int)$data['language_id'] . "', currency_id = '" . (int)$data['currency_id'] . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', custom_field = '" . $this->db->escape($data['custom_field']) . "', payment_custom_field = '" . $this->db->escape($data['payment_custom_field']) . "', shipping_custom_field = '" . $this->db->escape($data['shipping_custom_field']) . "', marketing_id = '" . $this->db->escape($data['marketing_id']) . "', tracking = '" . $this->db->escape($data['tracking']) . "', currency_value = '" . $this->db->escape($data['currency_value']) . "', ip = '" . $this->db->escape($data['ip']) . "', date_added = '" . $this->db->escape($data['date_added']) . "', " . (!empty($extra_insert) ? implode(', ', $extra_insert) . ', ' : '') . "date_modified = '" . $this->db->escape($data['date_modified']) . "'");

		$order_id = $this->db->getLastId();

		if (!empty($data['order_product'])) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "order_product` WHERE order_id='" . $order_id . "'");

			foreach ($data['order_product'] as $order_product) {
				$extra_product_insert = array();

				if (VERSION > '1.5.1.3') {
					$extra_product_insert[] = "reward = '" . $this->db->escape($order_product['reward']) . "'";
				}

				$this->db->query("INSERT INTO `" . DB_PREFIX . "order_product` SET ".(!empty($order_product['order_product_id']) ? "order_product_id = '" . (int)trim($order_product['order_product_id']) . "', " : "")."order_id = '" . (int)$order_product['order_id'] . "', product_id = '" . (int)($order_product['product_id']) . "', name = '" . $this->db->escape($order_product['name']) . "', model = '" . $this->db->escape($order_product['model']) . "', quantity = '" . $this->db->escape($order_product['quantity']) . "', price = '" . $this->db->escape($order_product['price']) . "', total = '" . $this->db->escape($order_product['total']) . "', " . (!empty($extra_product_insert) ? implode(', ', $extra_product_insert) . ', ' : '') . "tax = '" . $this->db->escape($order_product['tax']) . "'");
			}
		}

		if (!empty($data['order_option'])) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "order_option` WHERE order_id='" . $order_id . "'");

			foreach ($data['order_option'] as $order_option) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "order_option` SET ".(!empty($order_option['order_option_id']) ? "order_option_id = '" . (int)trim($order_option['order_option_id']) . "', " : "")."order_id = '" . (int)$order_option['order_id'] . "', order_product_id = '" . (int)($order_option['order_product_id']) . "', product_option_id = '" . (int)($order_option['product_option_id']) . "', product_option_value_id = '" . (int)($order_option['product_option_value_id']) . "', name = '" . $this->db->escape($order_option['name']) . "', value = '" . $this->db->escape($order_option['value']) . "', type = '" . $this->db->escape($order_option['type']) . "'");
			}
		}

		// if (!empty($data['order_download'])) {
		// 	$this->db->query("DELETE FROM `" . DB_PREFIX . "order_download` WHERE order_id='" . $order_id . "'");

		// 	foreach ($data['order_download'] as $order_download) {
		// 		$this->db->query("INSERT INTO `" . DB_PREFIX . "order_download` SET ".(!empty($order_download['order_download_id']) ? "order_download_id = '" . (int)trim($order_download['order_download_id']) . "', " : "")."order_id = '" . (int)$order_download['order_id'] . "', order_product_id = '" . (int)($order_download['order_product_id']) . "', name = '" . $this->db->escape($order_download['name']) . "', filename = '" . $this->db->escape($order_download['filename']) . "', mask = '" . $this->db->escape($order_download['mask']) . "', remaining = '" . $this->db->escape($order_download['remaining']) . "'");
		// 	}
		// }

		if (!empty($data['order_total'])) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "order_total` WHERE order_id='" . $order_id . "'");

			foreach ($data['order_total'] as $order_total) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "order_total` SET ".(!empty($order_total['order_total_id']) ? "order_total_id = '" . (int)trim($order_total['order_total_id']) . "', " : "")."order_id = '" . (int)$order_total['order_id'] . "', code = '" . $this->db->escape($order_total['code']) . "', title = '" . $this->db->escape($order_total['title']) . "', value = '" . $this->db->escape($order_total['value']) . "', sort_order = '" . $this->db->escape($order_total['sort_order']) . "'");
			}
		}

		if (!empty($data['order_history'])) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "order_history` WHERE order_id='" . $order_id . "'");

			foreach ($data['order_history'] as $order_history) {
				$this->db->query("REPLACE INTO `" . DB_PREFIX . "order_history` SET ".(!empty($order_history['order_history_id']) ? "order_history_id = '" . (int)trim($order_history['order_history_id']) . "', " : "")."order_id = '" . (int)$order_history['order_id'] . "', order_status_id = '" . (int)($order_history['order_status_id']) . "', notify = '" . $this->db->escape($order_history['notify']) . "', comment = '" . $this->db->escape($order_history['comment']) . "', date_added = '" . $this->db->escape($order_history['date_added']) . "'");
			}
		}

		if (!empty($data['order_voucher']) && VERSION > '1.5.1.3') {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "order_voucher` WHERE order_id='" . $order_id . "'");

			foreach ($data['order_voucher'] as $order_voucher) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "order_voucher` SET ".(!empty($order_voucher['order_voucher_id']) ? "order_voucher_id = '" . (int)trim($order_voucher['order_voucher_id']) . "', " : "")."order_id = '" . (int)$order_voucher['order_id'] . "', voucher_id = '" . (int)($order_voucher['voucher_id']) . "', description = '" . $this->db->escape($order_voucher['description']) . "', code = '" . $this->db->escape($order_voucher['code']) . "', from_name = '" . $this->db->escape($order_voucher['from_name']) . "', from_email = '" . $this->db->escape($order_voucher['from_email']) . "', to_name = '" . $this->db->escape($order_voucher['to_name']) . "', to_email = '" . $this->db->escape($order_voucher['to_email']) . "', voucher_theme_id = '" . (int)($order_voucher['voucher_theme_id']) . "', message = '" . $this->db->escape($order_voucher['message']) . "', amount = '" . $this->db->escape($order_voucher['amount']) . "'");
			}
		}

		// Extras
		foreach ($this->extraGeneralFields['Orders'] as $extra) {
			if (!empty($extra['eval_add'])) {
				eval($extra['eval_add']);
			}
		}
		
		$this->cache->delete('order');
	}
	
	public function editOrder($order_id, $data) {
		$order_id = trim($order_id);

		if (VERSION <= '1.5.1.3') {
			$extra_insert[] = "reward = '" . $this->db->escape($data['reward']) . "'";
		}

		if (VERSION > '1.5.1.3') {
			$extra_insert[] = "shipping_code = '" . $this->db->escape($data['shipping_code']) . "'";
			$extra_insert[] = "payment_code = '" . $this->db->escape($data['payment_code']) . "'";
			$extra_insert[] = "forwarded_ip = '" . $this->db->escape($data['forwarded_ip']) . "'";
			$extra_insert[] = "user_agent = '" . $this->db->escape($data['user_agent']) . "'";
			$extra_insert[] = "accept_language = '" . $this->db->escape($data['accept_language']) . "'";
		}

		if (VERSION >= '1.5.3') {
			
		}

		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', invoice_no = '" . (int)$data['invoice_no'] . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "',store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', comment = '" . $this->db->escape($data['comment']) . "', total = '" . $this->db->escape($data['total']) . "', order_status_id = '" . (int)$data['order_status_id'] . "', affiliate_id  = '" . (int)$data['affiliate_id'] . "', commission = '" . $this->db->escape($data['commission']) . "', language_id = '" . (int)$data['language_id'] . "', currency_id = '" . (int)$data['currency_id'] . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', custom_field = '" . $this->db->escape($data['custom_field']) . "', payment_custom_field = '" . $this->db->escape($data['payment_custom_field']) . "', shipping_custom_field = '" . $this->db->escape($data['shipping_custom_field']) . "', marketing_id = '" . $this->db->escape($data['marketing_id']) . "', tracking = '" . $this->db->escape($data['tracking']) . "', currency_value = '" . $this->db->escape($data['currency_value']) . "', ip = '" . $this->db->escape($data['ip']) . "', date_added = '" . $this->db->escape($data['date_added']) . "', " . (!empty($extra_insert) ? implode(', ', $extra_insert) . ', ' : '') . "date_modified = '" . $this->db->escape($data['date_modified']) . "' WHERE order_id = '" . (int)$order_id . "'");

		if (!empty($data['order_product'])) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "order_product` WHERE order_id='" . $order_id . "'");

			foreach ($data['order_product'] as $order_product) {
				$extra_product_insert = array();

				if (VERSION > '1.5.1.3') {
					$extra_product_insert[] = "reward = '" . $this->db->escape($order_product['reward']) . "'";
				}
				
				$this->db->query("INSERT INTO `" . DB_PREFIX . "order_product` SET ".(!empty($order_product['order_product_id']) ? "order_product_id = '" . (int)trim($order_product['order_product_id']) . "', " : "")."order_id = '" . (int)$order_product['order_id'] . "', product_id = '" . (int)($order_product['product_id']) . "', name = '" . $this->db->escape($order_product['name']) . "', model = '" . $this->db->escape($order_product['model']) . "', quantity = '" . $this->db->escape($order_product['quantity']) . "', price = '" . $this->db->escape($order_product['price']) . "', total = '" . $this->db->escape($order_product['total']) . "', " . (!empty($extra_product_insert) ? implode(', ', $extra_product_insert) . ', ' : '') . "tax = '" . $this->db->escape($order_product['tax']) . "'");
			}
		}

		if (!empty($data['order_option'])) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "order_option` WHERE order_id='" . $order_id . "'");

			foreach ($data['order_option'] as $order_option) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "order_option` SET ".(!empty($order_option['order_option_id']) ? "order_option_id = '" . (int)trim($order_option['order_option_id']) . "', " : "")."order_id = '" . (int)$order_option['order_id'] . "', order_product_id = '" . (int)($order_option['order_product_id']) . "', product_option_id = '" . (int)($order_option['product_option_id']) . "', product_option_value_id = '" . (int)($order_option['product_option_value_id']) . "', name = '" . $this->db->escape($order_option['name']) . "', value = '" . $this->db->escape($order_option['value']) . "', type = '" . $this->db->escape($order_option['type']) . "'");
			}
		}

		// if (!empty($data['order_download'])) {
		// 	$this->db->query("DELETE FROM `" . DB_PREFIX . "order_download` WHERE order_id='" . $order_id . "'");

		// 	foreach ($data['order_download'] as $order_download) {
		// 		$this->db->query("INSERT INTO `" . DB_PREFIX . "order_download` SET ".(!empty($order_download['order_download_id']) ? "order_download_id = '" . (int)trim($order_download['order_download_id']) . "', " : "")."order_id = '" . (int)$order_download['order_id'] . "', order_product_id = '" . (int)($order_download['order_product_id']) . "', name = '" . $this->db->escape($order_download['name']) . "', filename = '" . $this->db->escape($order_download['filename']) . "', mask = '" . $this->db->escape($order_download['mask']) . "', remaining = '" . $this->db->escape($order_download['remaining']) . "'");
		// 	}
		// }

		if (!empty($data['order_total'])) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "order_total` WHERE order_id='" . $order_id . "'");

			foreach ($data['order_total'] as $order_total) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "order_total` SET ".(!empty($order_total['order_total_id']) ? "order_total_id = '" . (int)trim($order_total['order_total_id']) . "', " : "")."order_id = '" . (int)$order_total['order_id'] . "', code = '" . $this->db->escape($order_total['code']) . "', title = '" . $this->db->escape($order_total['title']) . "', value = '" . $this->db->escape($order_total['value']) . "', sort_order = '" . $this->db->escape($order_total['sort_order']) . "'");
			}
		}

		if (!empty($data['order_history'])) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "order_history` WHERE order_id='" . $order_id . "'");

			foreach ($data['order_history'] as $order_history) {
				$this->db->query("REPLACE INTO `" . DB_PREFIX . "order_history` SET ".(!empty($order_history['order_history_id']) ? "order_history_id = '" . (int)trim($order_history['order_history_id']) . "', " : "")."order_id = '" . (int)$order_history['order_id'] . "', order_status_id = '" . (int)($order_history['order_status_id']) . "', notify = '" . $this->db->escape($order_history['notify']) . "', comment = '" . $this->db->escape($order_history['comment']) . "', date_added = '" . $this->db->escape($order_history['date_added']) . "'");
			}
		}

		if (!empty($data['order_voucher']) && VERSION > '1.5.1.3') {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "order_voucher` WHERE order_id='" . $order_id . "'");

			foreach ($data['order_voucher'] as $order_voucher) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "order_voucher` SET ".(!empty($order_voucher['order_voucher_id']) ? "order_voucher_id = '" . (int)trim($order_voucher['order_voucher_id']) . "', " : "")."order_id = '" . (int)$order_voucher['order_id'] . "', voucher_id = '" . (int)($order_voucher['voucher_id']) . "', description = '" . $this->db->escape($order_voucher['description']) . "', code = '" . $this->db->escape($order_voucher['code']) . "', from_name = '" . $this->db->escape($order_voucher['from_name']) . "', from_email = '" . $this->db->escape($order_voucher['from_email']) . "', to_name = '" . $this->db->escape($order_voucher['to_name']) . "', to_email = '" . $this->db->escape($order_voucher['to_email']) . "', voucher_theme_id = '" . (int)($order_voucher['voucher_theme_id']) . "', message = '" . $this->db->escape($order_voucher['message']) . "', amount = '" . $this->db->escape($order_voucher['amount']) . "'");
			}
		}

		// Extras
		foreach ($this->extraGeneralFields['Orders'] as $extra) {
			if (!empty($extra['eval_edit'])) {
				eval($extra['eval_edit']);
			}
		}
		
		$this->cache->delete('order');
	}
	
	public function deleteOrders() {
		$this->load->model('sale/order');
		
		$ids = $this->db->query("SELECT order_id FROM `" . DB_PREFIX . "order` o");
		
		foreach ($ids->rows as $row) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "order` WHERE order_id='" . $row['order_id'] . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "order_product` WHERE order_id='" . $row['order_id'] . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "order_option` WHERE order_id='" . $row['order_id'] . "'");
			//$this->db->query("DELETE FROM `" . DB_PREFIX . "order_download` WHERE order_id='" . $row['order_id'] . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "order_total` WHERE order_id='" . $row['order_id'] . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "order_history` WHERE order_id='" . $row['order_id'] . "'");
			if (VERSION > '1.5.1.3') {
				$this->db->query("DELETE FROM `" . DB_PREFIX . "order_voucher` WHERE order_id='" . $row['order_id'] . "'");
			}
		}
	}
}
?>
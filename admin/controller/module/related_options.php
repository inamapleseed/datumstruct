<?php
//  Related Options / Связанные опции
//  Support: support@liveopencart.com / Поддержка: help@liveopencart.ru

class ControllerModuleRelatedOptions extends Controller {
	private $error = array();
	
	private function getLinks() {
		
		$data = array();
		
		if (VERSION >= '2.3.0.0') {
			$routeHomePage 				= 'common/dashboard';
			$routeExtensions			= 'extension/extension';
			$routeExtensionsType 	= '&type=module';
			$routeModule 					= 'module/related_options';
		} else { // OLDER OC
			$routeHomePage 				= 'common/home';
			$routeExtensions			= 'extension/module';
			$routeExtensionsType 	= '';
			$routeModule 					= 'module/related_options';
		}
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link($routeHomePage, 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link($routeExtensions, 'token=' . $this->session->data['token'].$routeExtensionsType, 'SSL'),
			'separator' => ' :: '
		);
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('module_name'),
			'href'      => $this->url->link($routeModule, 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);
		
		$data['action'] = $this->url->link($routeModule, 'token=' . $this->session->data['token'], 'SSL');
		$data['action_export'] = $this->url->link($routeModule.'/export', '&token=' . $this->session->data['token'], 'SSL');
	
		$data['cancel'] = $this->url->link($routeExtensions, 'token=' . $this->session->data['token'].$routeExtensionsType, 'SSL');
		
		$data['redirect'] = $this->url->link($routeModule, 'token=' . $this->session->data['token'], 'SSL');
		
		return $data;
	}
	
  public function index() {
    
    $ro_language = $this->load->language('module/related_options');
		
		$links = $this->getLinks();

		$this->document->setTitle($this->language->get('module_name'));
		
		$this->load->model('setting/setting');
    $this->load->model('module/related_options');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      
      if (isset($this->request->post['variants'])) {
        $this->model_module_related_options->set_variants_options($this->request->post['variants']);
        unset($this->request->post['variants']);
      } else {
				$this->model_module_related_options->set_variants_options(array());
			}
      
			$this->model_setting_setting->editSetting('related_options', $this->request->post);		
			
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->response->redirect($links['redirect']);
      
		}
    
		//$data['export'] = $this->model_setting_setting->getSetting('related_options_export');
    
    foreach ( $ro_language as $lang_key => $lang_val ) {
			$data[$lang_key]      									= $this->language->get($lang_key);
		}
		
		$data['breadcrumbs'] 		= $links['breadcrumbs'];
		$data['action'] 				= $links['action'];
		$data['action_export'] 	= $links['action_export'];
		$data['cancel'] 				= $links['cancel'];
		
		
		$PHPExcelPath = $this->PHPExcelPath();
		$data['PHPExcelPath'] = str_replace(DIR_SYSTEM,"./system",$PHPExcelPath);
		$data['PHPExcelExists'] = file_exists($PHPExcelPath);
		
		$data['token'] = $this->session->data['token'];
    
    if (!empty($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			if (empty($vQmodInstalled)) {
				$data['error_warning'] = $this->language->get('error_vqmod_not_found');
			} else {
				$data['error_warning'] = '';
			}
		}
    
    if (isset($this->session->data['success'])) {
      $data['success'] = $this->session->data['success'];
      unset($this->session->data['success']);
    } 
		
		if (isset($this->error['image'])) {
			$data['error_image'] = $this->error['image'];
		} else {
			$data['error_image'] = array();
		}
		
		$data['modules'] = array();
    if (isset($this->request->post['related_options'])) {
			$data['modules'] = $this->request->post['related_options'];
		} elseif ($this->config->get('related_options')) {
			$data['modules'] = $this->config->get('related_options');
		}
		
		$current_version = $this->model_module_related_options->current_version();
		if ( !isset($data['modules']['related_options_version']) || $data['modules']['related_options_version'] < $current_version
				|| "".$data['modules']['related_options_version'] == ("".$current_version."b")
				|| (substr($data['modules']['related_options_version'],0,2) == '2.' && substr($current_version,0,2) == '1.' ) // RO2 to ROPRO upgrade
				) {
			$this->model_module_related_options->install_additional_tables();
			$data['modules']['related_options_version'] = $current_version;
			$this->model_setting_setting->editSetting('related_options', array('related_options' => $data['modules']) );
			$data['updated'] = $this->language->get('text_ro_updated_to')." ".($current_version);
		}
		$data['config_admin_language'] = $this->config->get('config_admin_language');
    
		$data['export_new_action'] 					= $this->url->link('module/related_options/export_new', '&token=' . $this->session->data['token'], 'SSL');
		$data['export_new_fields'] 					= $this->model_module_related_options->getExportNewFields();
		$related_options_export = $this->model_setting_setting->getSetting('related_options_export');
		if ( !empty($related_options_export['related_options_export']) ) {
			$data['export_new_settings'] 				= $related_options_export['related_options_export'];
		}
		$data['export_new_PHPExcelExists'] 	= $this->model_module_related_options->PHPExcelExists();
    
		$data['min_product_id'] 						= $this->model_module_related_options->getMinProductIdWithRO();
		$data['max_product_id'] 						= $this->model_module_related_options->getMaxProductIdWithRO();
		
    $data['options'] = $this->model_module_related_options->get_compatible_options();
    $data['variants_options'] = $this->model_module_related_options->get_variants_options();
    $data['extension_code'] = $this->model_module_related_options->getExtensionCode();
    
    $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
				
		$this->response->setOutput($this->load->view('module/related_options.tpl', $data));
    
  }
	
	public function export_new() {
		
		if ( $this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['export_fields'])
			&& is_array($this->request->post['export_fields']) && count($this->request->post['export_fields'])>0 ) {
			
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting('related_options_export', array('related_options_export'=>$this->request->post) );	
			
			if ( !$this->model_module_related_options ) {
				$this->load->model('module/related_options');
			}
			$this->model_module_related_options->makeExport();
			
		}
		
	}
	
	public function import_new() {
		
		if ( !$this->model_module_related_options ) {
			$this->load->model('module/related_options');
		}
		$json = $this->model_module_related_options->makeImport();
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
  
	private function PHPExcelPath() { // to remove (moved to model)
		return DIR_SYSTEM . '/PHPExcel/Classes/PHPExcel.php';
	}
	
	public function export() { // to remove (old)
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['export']) && is_array($this->request->post['export']) && count($this->request->post['export'])>0) {
			
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting('related_options_export', $this->request->post['export']);
			$export_settings = $this->request->post['export'];
			
			
			$this->load->model('module/related_options');
			$data = $this->model_module_related_options->getExportData();
			
			require_once $this->PHPExcelPath();
			PHPExcel_Shared_File::setUseUploadTempDirectory(true);
			
			$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_discISAM; //PHPExcel_CachedObjectStorageFactory::cache_to_discISAM ; //
			$cacheSettings = array( 'memoryCacheSize' => '32MB');
			if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings)) {
				$this->log->write("Related options, PHPExcel cache error");
			}
			
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);
			
			$show_settings = array();
			foreach ($data as $data_str) {
				
				foreach ($data_str as $data_key => $data_value) {
					foreach ($export_settings as $setting => $setting_on) {
						if ($setting_on) {
							if (($data_key == $setting) || (substr($data_key, 0, strlen($setting)) == $setting) ) {
								if (!in_array($data_key, $show_settings)) {
									$show_settings[] = $data_key;
								}
							}
						}
					}
				}
			}
			
			
			$column = 0;
			foreach ($show_settings as $setting) {
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $setting);
				$column++;
			}
			
			$current_data = array();
			$line_cnt = 2;
			$loop_cnt = 0;
			foreach ($data as &$data_str) {
				$loop_cnt++;
				
				$current_str = array();
				foreach ($show_settings as $setting) {
					$current_str[$setting] = isset($data_str[$setting]) ? $data_str[$setting] : null;
				}
				$current_data[] = $current_str;
				$data_str = ""; // memory opt
				
				if ($loop_cnt%1000 == 0) {
					$objPHPExcel->getActiveSheet()->fromArray($current_data, null, 'A'.$line_cnt);
					$line_cnt=2+$loop_cnt;
					$current_data = array();
					//sleep(1);
				}
					
			}
			unset($data);

			if (count($current_data)>0) {
				$objPHPExcel->getActiveSheet()->fromArray($current_data, null, 'A'.$line_cnt);
			}
			
			
			$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
			
			
			$file = DIR_CACHE."/roexport.xls";
			
			$objWriter->save($file);
			
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . basename($file));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			// read file and send to user
			readfile($file);
			exit;
			
		}
	}
  
	public function import() { // to remove (old)
		
		$this->load->language('module/related_options');
		
		$json = array();
		
		if (!empty($this->request->files['file']['name']) && $this->request->files['file']['tmp_name'] ) {
			
			require_once $this->PHPExcelPath();
			
			$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp; //PHPExcel_CachedObjectStorageFactory::cache_to_discISAM ; //
			$cacheSettings = array( 'memoryCacheSize' => '32MB');
			PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
			
			$excel = PHPExcel_IOFactory::load($this->request->files['file']['tmp_name']); // PHPExcel
			$sheet = $excel->getSheet(0);
			
			$data = $sheet->toArray();
			
			
			if (count($data) > 1) {
				
				$head = array_flip($data[0]);
				
				if (!isset($head['product_id'])) {
					$json['error'] = "product_id not found";
				}
				
				if (!isset($head['quantity'])) {
					$json['error'] = "quantity not found";
				}
				
				if (!isset($head['option_id1'])) {
					$json['error'] = "option_id1 not found";
				}
				
				if (!isset($head['option_value_id1'])) {
					$json['error'] = "option_value_id1 not found";
				}
				
				if (!isset($json['error'])) {
					
					$f_options = array();
					for ($i=1;$i<=100;$i++) {
						if ( isset($head['option_id'.$i]) && isset($head['option_value_id'.$i]) ) {
							$f_options[] = $i;
						}
					}
					
					
					$products = array();
					
					for ($i=1;$i<count($data);$i++) {
						
						$row = $data[$i];
						
						$product_id = (int)$row[$head['product_id']];
						if (!isset($products[$product_id])) {
							$products[$product_id] = array('relatedoptions'=>array(), 'related_options_use'=>true, 'related_options_variant_search'=>true);
						}
						
						$options = array();
						foreach ($f_options as $opt_num) {
							if ((int)$row[$head['option_id'.$opt_num]]!=0) {
								$options[(int)$row[$head['option_id'.$opt_num]]] = (int)$row[$head['option_value_id'.$opt_num]];
							}
						}
						
						$products[$product_id]['relatedoptions'][] = array(	'options'=>$options
																															, 'quantity'=>$row[(int)$head['quantity']]
																															, 'price_prefix'=> isset($head['price_prefix']) ? (string)$row[(int)$head['price_prefix']] : ''
																															, 'price'=> isset($head['price']) ? (float)$row[(int)$head['price']] : 0
																															, 'model'=> isset($head['relatedoptions_model']) ? $row[(int)$head['relatedoptions_model']] : ''
																															, 'sku'=> isset($head['relatedoptions_sku']) ? $row[(int)$head['relatedoptions_sku']] : ''
																															, 'upc'=> isset($head['relatedoptions_upc']) ? $row[(int)$head['relatedoptions_upc']] : ''
																															, 'ean'=> isset($head['relatedoptions_ean']) ? $row[(int)$head['relatedoptions_ean']] : ''
																															, 'stock_status_id'=> isset($head['stock_status_id']) ? $row[(int)$head['stock_status_id']] : ''
																															, 'weight_prefix'=> isset($head['weight_prefix']) ? $row[(int)$head['weight_prefix']] : ''
																															, 'weight'=> isset($head['weight']) ? $row[(int)$head['weight']] : ''
																															);
						
						
					}
					
					
					$this->load->model('module/related_options');
					
					if (isset($this->request->post['import_delete_before']) && $this->request->post['import_delete_before'] == 1) {
						$this->model_module_related_options->delete_all_related_options();
					}
					
					$ro_cnt = 0;
					foreach ($products as $product_id => $product) {
						$ro_cnt+= count($product['relatedoptions']);
						
						if (isset($this->request->post['import_delete_before']) && $this->request->post['import_delete_before'] == 2) {
							$this->model_module_related_options->delete_all_related_options($product_id);
						}
						
						
						$ro_data = $this->model_module_related_options->get_ro_data($product_id);
						
						$new_ro_combs = array();
						
						
						foreach ($product['relatedoptions'] as $new_ro) {
							
							$new_options_ids = array();
							foreach ($new_ro['options'] as $option_id => $option_value_id) {
								$new_options_ids[] = $option_id;
							}
							
							$ro_found = false;
							foreach ($ro_data as &$ro_dt) {
								
								// combination set is relevant, let's find current new combination in this set
								if ( !array_diff_assoc($new_options_ids, $ro_dt['options_ids']) && count($new_ro['options']) == count($ro_dt['options_ids']) ) {
									
									foreach ($ro_dt['ro'] as &$ro_comb) {
										if ( !array_diff_assoc($new_ro['options'], $ro_comb['options']) && count($new_ro['options']) == count($ro_comb['options'])) {
											// refresh relevant combination field accordingly to new combination
											foreach ($ro_comb as $ro_comb_key => &$ro_comb_value) {
												if (isset($new_ro[$ro_comb_key])) {
													$ro_comb_value = $new_ro[$ro_comb_key];
												}
											}
											unset($ro_comb_value);
											$ro_found = true;
											break;
										}
									}
									unset($ro_comb);
									
									// relevant combination is not found, but combination set is relevant, let's add this combination to this set
									if (!$ro_found) {
										$ro_dt['ro'][] = $new_ro;
										$ro_found = true;
									}
								}
							}
							unset($ro_dt);
							if (!$ro_found) { // if there's not relevant set of options combinations, let's add new set
								
								$new_ro_combs_set = array(	'rovp_id' => ''
																					,	'use' 		=> true
																					,	'related_options_variant_search' 	=> true
																					, 'ro'			=> array($new_ro)
																					, 'options_ids'	=> $new_options_ids
																					);
								
								$ro_data[] = $new_ro_combs_set;
								
							}
						}
						
						$product_data = array('ro_data_included'=>true, 'ro_data'=>$ro_data);
						$this->model_module_related_options->set_ro_data($product_id, $product_data);	
						
					}
					$json['products'] = count($products);
					$json['relatedoptions'] = $ro_cnt;
					
					$json['success'] = $this->language->get('entry_import_ok');
					
				}
				
			} else {
				$json['error'] = "empty table";
			}
			
		} else {
			$json['error'] = "file not uploaded";
		}
		
		$this->response->setOutput(json_encode($json));
	}
	
	public function install() {
		$this->load->model('module/related_options');
		$this->model_module_related_options->install();
			
			$this->load->model('setting/setting');
			$msettings = array('related_options'=>array('update_quantity'=>1,'update_options'=>1,'ro_use_variants'=>1,'disable_all_options_variant'=>1,'related_options_version'=>$this->model_module_related_options->current_version()));
			$this->model_setting_setting->editSetting('related_options', $msettings);

			
	  }
  
  public function uninstall() {
    $this->load->model('module/related_options');
    $this->model_module_related_options->uninstall();
  }
  
  private function validate() {
    if (!$this->user->hasPermission('modify', 'module/related_options') && !$this->user->hasPermission('modify', 'extension/module/related_options')) {
      $this->error['warning'] = $this->language->get('error_permission');
    }
    
    if (!$this->error) {
      return true;
    } else {
      return false;
    }	
  }
  
}
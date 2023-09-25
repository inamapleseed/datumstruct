<?php
header('Cache-Control: no-cache, no-store');
ini_set('memory_limit', '1024M');
ini_set('max_execution_time', 900);
ini_set('error_reporting', E_ALL);
include DIR_SYSTEM.'library/PHPExcel.php';
class ControllerToolExcelImport extends Controller {
	private $error = array();

	public function index(){
		$this->load->language('catalog/product');
		
		$this->load->language('tool/excel_point');
	

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('tool/excel_point');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['product_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		
		$data['text_default'] = $this->language->get('text_default');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_missing'] = $this->language->get('text_missing');
		$data['text_email_already'] = $this->language->get('text_email_already');
		$data['text_password'] = $this->language->get('text_password');
		$data['help_email'] = $this->language->get('help_email');
		$data['help_password'] = $this->language->get('help_password');
		
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_import'] = $this->language->get('entry_import');
		$data['entry_language'] = $this->language->get('entry_language');
		
		$data['tab_import'] = $this->language->get('tab_import');
		
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_price'] = $this->language->get('entry_price');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_quantity'] = $this->language->get('entry_quantity');
		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_language'] = $this->language->get('entry_language');
		$data['entry_stock_status'] = $this->language->get('entry_stock_status');
		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_categories'] = $this->language->get('entry_categories');
		$data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
		$data['entry_pricerange'] = $this->language->get('entry_pricerange');
		$data['entry_quantityrange'] = $this->language->get('entry_quantityrange');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_approved'] = $this->language->get('entry_approved');
		$data['entry_ip'] = $this->language->get('entry_ip');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_return_id'] = $this->language->get('entry_return_id');
		$data['entry_order_id'] = $this->language->get('entry_order_id');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_date_modified'] = $this->language->get('entry_date_modified');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_export'] = $this->language->get('button_export');
		
		
		$data['token'] = $this->session->data['token'];
		
		if(isset($this->error['warning'])){
			$data['error_warning'] = $this->error['warning'];
		}elseif(isset($this->session->data['error_warning'])){
			$data['error_warning'] = $this->session->data['error_warning'];
			unset($this->session->data['error_warning']);
		}else{
			$data['error_warning'] = '';
		}
		
		if(isset($this->session->data['success'])){
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}else{
			$data['success'] = '';
		}
		
		if (isset($this->request->get['filter_name'])) {
			$data['filter_name'] = $this->request->get['filter_name'];
		} else {
			$data['filter_name'] = null;
		}

		if (isset($this->request->get['filter_model'])) {
			$data['filter_model'] = $this->request->get['filter_model'];
		} else {
			$data['filter_model'] = null;
		}

		if (isset($this->request->get['filter_price'])) {
			$data['filter_price'] = $this->request->get['filter_price'];
		} else {
			$data['filter_price'] = null;
		}

		if (isset($this->request->get['filter_quantity'])) {
			$data['filter_quantity'] = $this->request->get['filter_quantity'];
		} else {
			$data['filter_quantity'] = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$data['filter_status'] = $this->request->get['filter_status'];
		} else {
			$data['filter_status'] = null;
		}
		
		if (isset($this->request->get['filter_limit'])) {
			$data['filter_limit'] = $this->request->get['filter_limit'];
		} else {
			$data['filter_limit'] = $this->config->get('config_limit_admin');
		}
		
		$url = '';
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->load->model('setting/store');
		$data['stores'] = $this->model_setting_store->getStores();
		
		$this->load->model('localisation/stock_status');
		$data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();
		
		$this->load->model('catalog/category');
		$data['categories'] = $this->model_catalog_category->getCategories();
		
		$this->load->model('catalog/manufacturer');
		$data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();
		
		$data['customer_groups'] = $this->model_tool_excel_point->getCustomerGroups();
		
		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->get['filter_limit'])) {
			$data['filter_limit'] = $this->request->get['filter_limit'];
		} else {
			$data['filter_limit'] = $this->config->get('config_limit_admin');
		}
		
		////Import Start
		$examplefiles = '';
		
		$data['entry_store'] = $this->language->get('entry_store');
		
		$exampleproductfiles = HTTP_CATALOG.'system/excel_point/productexample.xls';
		
		$data['entry_prodimportimport'] = sprintf($this->language->get('entry_prodimportimport'),$exampleproductfiles);
		
		$examplecategoriesfiles = HTTP_CATALOG.'system/excel_point/categoriesexample.xls';
		
		$data['entry_categoriesimport'] = sprintf($this->language->get('entry_categoriesimport'),$examplecategoriesfiles);
		
		$examplemanufiles = HTTP_CATALOG.'system/excel_point/manufacturerexample.xls';
		
		$data['entry_manufacturerimport'] = sprintf($this->language->get('entry_manufacturerimport'),$examplemanufiles);
		
		$examplecustomerfiles = HTTP_CATALOG.'system/excel_point/customerexample.xls';
		$data['text_import_customer'] = sprintf($this->language->get('text_import_customer'),$examplecustomerfiles);
		
		$examplecustomergroupfiles = HTTP_CATALOG.'system/excel_point/customerGroupexample.xls';
		
		$data['entry_customergroupimport'] = sprintf($this->language->get('entry_customergroupimport'),$examplecustomergroupfiles);
		
		$exampleaffillatesfiles = HTTP_CATALOG.'system/excel_point/affiliateslistexample.xls';
		
		$data['text_import_affiliates'] = sprintf($this->language->get('text_import_affiliates'),$exampleaffillatesfiles);
		
		$examplecouponfiles = HTTP_CATALOG.'system/excel_point/couponlistexample.xls';
		$data['entry_couponimport'] = sprintf($this->language->get('entry_couponimport'),$examplecouponfiles);
		
		$exampleusersfiles = HTTP_CATALOG.'system/excel_point/userslistexample.xls';
		$data['entry_userimport'] = sprintf($this->language->get('entry_userimport'),$exampleusersfiles);
		
		$exampleusersfiles = HTTP_CATALOG.'system/excel_point/orderexample.xls';
		$data['entry_order_import'] = sprintf($this->language->get('entry_order_import'),$exampleusersfiles);
		
		$exampleproductreviewfiles = HTTP_CATALOG.'system/excel_point/productreviewexample.xls';
		$data['text_import_productreview'] = sprintf($this->language->get('text_import_productreview'),$exampleproductreviewfiles);
		
		$data['entry_language'] = $this->language->get('entry_language');
		$data['text_importtype'] = $this->language->get('text_importtype');
		$data['text_productid'] = $this->language->get('text_productid');
		$data['text_model'] = $this->language->get('text_model');
		$data['productaction'] = $this->url->link('tool/excel_import/importproduct','token='.$this->session->data['token'],'SSL');
		
		$data['categoriesaction'] = $this->url->link('tool/excel_import/importcategories','token='.$this->session->data['token'],'SSL');
		
		$data['manufactureaction'] = $this->url->link('tool/excel_import/importmanufacture','token='.$this->session->data['token'],'SSL');
		
		$data['customergroupaction'] = $this->url->link('tool/excel_import/importcsutomergroup','token='.$this->session->data['token'],'SSL');
		
		$data['customeraction'] = $this->url->link('tool/excel_import/importcustomer','token='.$this->session->data['token'],'SSL');
		
		$data['affiliatesaction'] = $this->url->link('tool/excel_import/importaffiliate','token='.$this->session->data['token'],'SSL');
		
		$data['counponaction'] = $this->url->link('tool/excel_import/importcoupon','token='.$this->session->data['token'],'SSL');
		
		$data['useraction'] = $this->url->link('tool/excel_import/importuser','token='.$this->session->data['token'],'SSL');
		
		$data['orderaction'] = $this->url->link('tool/excel_import/importorder','token='.$this->session->data['token'],'SSL');
		
		$data['productreviewaction'] = $this->url->link('tool/excel_import/importproductreview','token='.$this->session->data['token'],'SSL');
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tool/excel_import.tpl', $data));
	}
	
	public function importmanufacture(){
	    $this->load->language('tool/excel_point');
	    $this->load->model('tool/excel_point');
	    $this->load->model('catalog/manufacturer');
		if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()){
		  if($this->request->files){
			$file = basename($this->request->files['import']['name']);
			move_uploaded_file($this->request->files['import']['tmp_name'], $file);
			$inputFileName = $file;
			$extension = pathinfo($inputFileName);
			if($extension['basename']){
				if($extension['extension']=='xlsx' || $extension['extension']=='xls'){
					try{
						$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
					}catch(Exception $e){
						die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
					}
					$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					$i=0;
					$updateproduct = 0;
					$newproduct = 0;
					
					foreach($allDataInSheet as $k=> $value){
						if($i!=0){
							$stores=array();
							$storesx = explode(',',$value['B']);
							foreach($storesx as $store){
							  $stores[]= $this->model_tool_excel_point->getstoreidbyname($store);
							}
							
							$data=array(
							  'manufacture_id' 		=> (isset($value['A']) ? $value['A'] : ''),
							  'stores' 				=> $stores,
							  'name' 				=> (isset($value['C']) ? $value['C'] : ''),
							  'keyword'  			=> (isset($value['D']) ? $value['D'] : ''),
							  'image'    			=> (isset($value['E']) ? $value['E'] : ''),
							  'sort_order'    		=> (isset($value['F']) ? $value['F'] : ''),
							);
							
							if((int)$value['A']){
								$manufacture = $this->model_catalog_manufacturer->getManufacturer($value['A']);
								if($manufacture){
									$this->model_tool_excel_point->editmanufacturer($data,$value['A']);
									$updateproduct++;
								}else{
									$this->model_tool_excel_point->addoldmanufacturer($data,$value['A']);
									$newproduct++;
								}
							}else{
								$this->model_tool_excel_point->addmanufacturer($data);
								$newproduct++;
							}
						}
						$i++;
					}
					if($newproduct || $updateproduct){
						$this->session->data['success'] = sprintf($this->language->get('text_success_manufacture'),$newproduct,$updateproduct);
					}
				
					if(!$newproduct && !$updateproduct){
						$this->session->data['error_warning'] = $this->language->get('text_no_data');
					}
				} else{
					$this->session->data['error_warning'] = $this->language->get('error_warning');
				}
			}else{
				$this->session->data['error_warning'] = $this->language->get('error_warning');
			}
			if($inputFileName){
				unlink($inputFileName);
			}
		  }else{
			$this->session->data['error_warning'] = $this->language->get('error_warning');
		  }
	    }
		$this->response->redirect($this->url->link('tool/excel_import', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
	public function importcategories(){
	  $this->load->language('tool/excel_point');
	  $this->load->model('tool/excel_point');
	  $this->load->model('tool/product_import');
	  $this->load->model('catalog/category');
	  if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()){
		  if($this->request->files){
			if(!empty($this->request->post['store_id'])){
				$store_id = $this->request->post['store_id'];
			}else{
				$store_id = 0;
			}
			
			if(!empty($this->request->post['language_id'])){
				$language_id = $this->request->post['language_id'];
			}else{
				$language_id = $this->config->get('config_langauge_id');
			}
				
			$file = basename($this->request->files['import']['name']);
			move_uploaded_file($this->request->files['import']['tmp_name'], $file);
			$inputFileName = $file;
			$extension = pathinfo($inputFileName);
			if($extension['basename']){
				if($extension['extension']=='xlsx' || $extension['extension']=='xls' || $extension['extension']=='csv') {
					try{
						if($extension['extension']=='csv'){
							$inputFileType = 'CSV';
							$objReader = PHPExcel_IOFactory::createReader($inputFileType);
							$objPHPExcel = $objReader->load($inputFileName);
						}else{
							$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
						}
					}catch(Exception $e){
						die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
					}
					$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					$i=0;
					$updateproduct = 0;
					$newproduct = 0;
					
					foreach($allDataInSheet as $k=> $value){
						if($i!=0){
							
							//Image
							$imagen = str_replace(' ','_',$value['D']);
							$mainimage = $value['K'];
							if(!empty($value['K'])){
							  $value['K'] = str_replace('?dl=0','?raw=1',$value['K']);
							  $mainimage = $this->model_tool_product_import->fetchingimage($value['K'],$imagen);	
							}
							
							$data=array(
							  'category_id' 		=> (isset($value['A']) ? $value['A'] : ''),
							  'language_id' 		=> $language_id,
							  'store_id' 			=> $store_id,
							  'name' 				=> (isset($value['D']) ? $value['D'] : ''),
							  'description' 		=> (isset($value['E']) ? $value['E'] : ''),
							  'meta_title'  		=> (isset($value['F']) ? $value['F'] : ''),
							  'meta_description'    => (isset($value['G']) ? $value['G'] : ''),
							  'meta_keyword'    	=> (isset($value['H']) ? $value['H'] : ''),
							  'parent_id'    		=> (isset($value['I']) ? $value['I'] : ''),
							  'keyword'    			=> (isset($value['J']) ? $value['J'] : ''),
							  'image'    			=> $mainimage,
							  'top'    				=> (isset($value['L']) ? $value['L'] : ''),
							  'column'    			=> (isset($value['M']) ? $value['M'] : ''),
							  'sort_order'    		=> (isset($value['N']) ? $value['N'] : ''),
							  'status'    			=> (isset($value['O']) ? $value['O'] : ''),
							);
							
							if((int)$value['A']){
								$categories = $this->model_catalog_category->getCategory($value['A']);
								if($categories){
									$this->model_tool_excel_point->editcategories($data,$value['A']);
									$updateproduct++;
								}else{
									$this->model_tool_excel_point->addoldcategories($data,$value['A']);
									$newproduct++;
								}
							}else{
								$this->model_tool_excel_point->addcategories($data);
								$newproduct++;
							}
						}
						$i++;
					}
					if($newproduct || $updateproduct){
						$this->session->data['success'] = sprintf($this->language->get('text_success_categories'),$newproduct,$updateproduct);
					}
				
					if(!$newproduct && !$updateproduct){
						$this->session->data['error_warning'] = $this->language->get('text_no_data');
					}
				} elseif($extension['extension']=='xml'){
					try{
						$xml = simplexml_load_file($inputFileName);
					}catch(Exception $e){
						die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
					}
					$i=0;
					$updateproduct = 0;
					$newproduct = 0;					
					foreach($xml->children() as $k=> $value){
					
							if($value->category_name){
								//Image
								$imagen = str_replace(' ','_',$value->category_name);
								$mainimage = $value->Image;
								if(!empty($value->Image)){
								  $value->Image = str_replace('?dl=0','?raw=1',$value->Image);
								  $mainimage = $this->model_tool_product_import->fetchingimage($value->Image,$imagen);	
								}
								
								$data=array(
								  'category_id' 		=> (isset($value->category_id) ? $value->category_id : ''),
								  'language_id' 		=> $language_id,
								  'store_id' 			=> $store_id,
								  'name' 				=> $value->category_name,
								  'description' 		=> $value->description,
								  'meta_title'  		=> $value->meta_title,
								  'meta_description'    => $value->meta_description,
								  'meta_keyword'    	=> $value->meta_keyword,
								  'parent_id'    		=> $value->parent_id,
								  'keyword'    			=> $value->keyword,
								  'image'    			=> $mainimage,
								  'top'    				=> $value->Top,
								  'column'    			=> $value->Column,
								  'sort_order'    		=> $value->Sort_Order,
								  'status'    			=> $value->Status,
								);
								
								if((int)$value->category_id){
									$categories = $this->model_catalog_category->getCategory($value->category_id);
									if($categories){
										$this->model_tool_excel_point->editcategories($data,$value->category_id);
										$updateproduct++;
									}else{
										$this->model_tool_excel_point->addoldcategories($data,$value->category_id);
										$newproduct++;
									}
								}else{
									$this->model_tool_excel_point->addcategories($data);
									$newproduct++;
								}
							}
					
						$i++;
					}
					
					if($newproduct || $updateproduct){
						$this->session->data['success'] = sprintf($this->language->get('text_success_categories'),$newproduct,$updateproduct);
					}
				
					if(!$newproduct && !$updateproduct){
						$this->session->data['error_warning'] = $this->language->get('text_no_data');
					}
				}else{
					$this->session->data['error_warning'] = $this->language->get('error_warning');
				}
			}else{
				$this->session->data['error_warning'] = $this->language->get('error_warning');
			}
			if($inputFileName){
				unlink($inputFileName);
			}
		  }else{
			$this->session->data['error_warning'] = $this->language->get('error_warning');
		  }
	    }
		$this->response->redirect($this->url->link('tool/excel_import', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
	public function importproduct(){
		$this->load->language('tool/excel_point');
		$this->load->model('tool/product_import');
		$this->load->model('catalog/product');
		if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if($this->request->files) {
				if(!empty($this->request->post['store_id'])){
					$store_id = $this->request->post['store_id'];
				}else{
					$store_id = 0;
				}
				
				if(!empty($this->request->post['language_id'])){
					$language_id = $this->request->post['language_id'];
				}else{
					$language_id = $this->config->get('config_langauge_id');
				}
				
				
			$file = basename($this->request->files['import']['name']);
			move_uploaded_file($this->request->files['import']['tmp_name'], $file);
			$inputFileName = $file;
			$extension = pathinfo($inputFileName);
			if($extension['basename']){
				if($extension['extension']=='xlsx' || $extension['extension']=='xls' || $extension['extension']=='csv') {
					try{
						if($extension['extension']=='csv'){
							$inputFileType = 'CSV';
							$objReader = PHPExcel_IOFactory::createReader($inputFileType);
							$objPHPExcel = $objReader->load($inputFileName);
						}else{
							$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
						}
					}catch(Exception $e){
						die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
					}
					$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					$i=0;
					$updateproduct = 0;
					$newproduct = 0;
					
					$this->load->model('localisation/tax_class');
					foreach($allDataInSheet as $k=> $value){
						if($i!=0){
							if($value['D']){
						
							if(!empty($value['Z'])){
								 $value['Z'] = str_replace(' ','_',$value['Z']);
							}
							
							//Categories
							$categoryids=array();
							if(!empty($value['AK'])){
								 $categoryids = explode(',',$value['AK']);
							}
							
							//Filter
							$filters=array();
							if(!empty($value['AL'])){
								$filters = $this->model_tool_product_import->checkFilter($value['AL']);
							}
							
							//Download
							$downloads = array();
							if(!empty($value['AM'])){
								$downloads = explode(',',trim($value['AM']));
							}
							
							//Relaled Products
							$relaled_products = array();
							if(!empty($value['AN'])){
								$relaled_products = explode(',',trim($value['AN']));
							}
							
							//Attribute Group
							$attributes = array();
							if(!empty($value['AO'])){
								$attributes = $this->model_tool_product_import->checkAttribute($value['AO']);
							}
							
							//Options
							$options = array();
							if(!empty($value['AP'])){
								$options = $this->model_tool_product_import->checkoptions($value['AP']);
							}
							
							//Discount
							$discounts = array();
							if(!empty($value['AR'])){
								$discounts = $this->model_tool_product_import->checkdiscount($value['AR']);
							}
							
							//Specail
							$specails = array();
							if(!empty($value['AS'])){
								$specails = $this->model_tool_product_import->checkspecial($value['AS']);
							}
							
							//main Image
							$imagen = str_replace(' ','_',$value['D']);
							$mainimage = $value['K'];
							if(!empty($value['K'])){
							  $value['K'] = str_replace('?dl=0','?raw=1',$value['K']);
							  $mainimage = $this->model_tool_product_import->fetchingimage($value['K'],$imagen);	
							}
							
							//Image
							$images = array();
							if(!empty($value['AT'])){
								$ic=1;
								foreach(explode(';',trim($value['AT'])) as $imageurl){
								  $imageurl = str_replace('?dl=0','?raw=1',$imageurl);
								  $imagename = $imagen.$ic++;
								  $images[] = $this->model_tool_product_import->fetchingimage($imageurl,$imagename);
								}
							}
							
							//Options Required
							$optionsrequired = array();
							if(!empty($value['AQ'])){
								$optionsrequired = $this->model_tool_product_import->checkoptionsrequred($value['AQ']);
							}
							
							$importdata=array(
							  'name' 	 			=> $value['D'],
							  'model'  	 			=> $value['E'],
							  'description' 		=> $value['F'],
							  'meta_titile' 		=> $value['G'],
							  'meta_description' 	=> $value['H'],
							  'meta_keyword' 		=> $value['I'],
							  'tag' 				=> $value['J'],
							  'image' 				=> $mainimage,
							  'sku' 				=> $value['L'],
							  'upc' 				=> $value['M'],
							  'ean' 				=> $value['N'],
							  'jan' 				=> $value['O'],
							  'isbn' 				=> $value['P'],
							  'mpn' 				=> $value['Q'],
							  'location' 			=> $value['R'],
							  'price' 				=> $value['S'],
							  'tax_class_id' 		=> $value['T'],
							  'quantity' 			=> $value['U'],
							  'minimum' 			=> $value['V'],
							  'subtract' 			=> $value['W'],
							  'stock_status_id' 	=> $value['X'],
							  'shipping' 			=> $value['Y'],
							  'keyword' 			=> $value['Z'],
							  'date_available' 		=> ($value['AA'] ? $value['AA'] : date('Y-m-d')),
							  'length' 				=> $value['AB'],
							  'length_class_id' 	=> $value['AC'],
							  'width' 				=> $value['AD'],
							  'height' 				=> $value['AE'],
							  'weight' 				=> $value['AF'],
							  'weight_class_id' 	=> $value['AG'],
							  'status' 				=> $value['AH'],
							  'sort_order' 			=> $value['AI'],
							  'manufacturer_id' 	=> $value['AJ'],
							  'categories'			=> array_unique($categoryids),
							  'filters'				=> array_unique($filters),
							  'downloads' 			=> $downloads,
							  'relaled_products' 	=> $relaled_products,
							  'attributes'			=> $attributes,
							  'options'				=> $options,
							  'discounts'			=> $discounts,
							  'specails'			=> $specails,
							  'images'				=> $images,
							  'points'				=> $value['AU'],
							  'viewed'				=> $value['AV'],
							);
							
							if($this->request->post['importtype']==2){
							 $product_id = $this->model_tool_product_import->getproductIDbymodel($value['E']);
								 if($product_id){
									 $this->model_tool_product_import->Editproduct($importdata,$product_id,$language_id,$store_id);
									 $updateproduct++;
								 }else{
									 $this->model_tool_product_import->addproduct($importdata,$language_id,$store_id);
									 $newproduct++;
								 }
							}else{
								if((int)$value['A']){
								$product_info = $this->model_catalog_product->getProduct($value['A']);
									if($product_info){
										$this->model_tool_product_import->Editproduct($importdata,$value['A'],$language_id,$store_id);
										$updateproduct++;
									}else{
										$this->model_tool_product_import->addoldproduct($importdata,$language_id,$store_id,$value['A']);
										$newproduct++;
									}
								}else{
									$this->model_tool_product_import->addproduct($importdata,$language_id,$store_id);
									$newproduct++;
								}
							}
						 }
						}
						$i++;
					}
					if($newproduct || $updateproduct){
						$this->session->data['success'] = sprintf($this->language->get('text_success_product'),$newproduct,$updateproduct);
					}
				
					if(!$newproduct && !$updateproduct){
						$this->session->data['error_warning'] = $this->language->get('text_no_data');
					}
				}elseif($extension['extension']=='xml'){
					try{
						$xml = simplexml_load_file($inputFileName);
					}catch(Exception $e){
						die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
					}

					$i=0;
					$updateproduct = 0;
					$newproduct = 0;					
					$this->load->model('localisation/tax_class');
					foreach($xml->children() as $k=> $value){
						 if($value->Name){
								if(!empty($value->SEO)){
									 $value->SEO = str_replace(' ','_',$value->SEO);
								}
							
							//Categories
							$categoryids=array();
							if(!empty($value->Categories_IDs)){
								 $categoryids = explode(',',$value->Categories_IDs);
							}
							
							//Filter
							$filters=array();
							if(!empty($value->Filter_Data)){
								$filters = $this->model_tool_product_import->checkFilter($value->Filter_Data);
							}
							
							//Download
							$downloads = array();
							if(!empty($value->Downloads)){
								$downloads = explode(',',trim($value->Downloads));
							}
							
							//Relaled Products
							$relaled_products = array();
							if(!empty($value->Related)){
								$relaled_products = explode(',',trim($value->Related));
							}
							
							//Attribute Group
							$attributes = array();
							if(!empty($value->Attribute)){
								$attributes = $this->model_tool_product_import->checkAttribute($value->Attribute);
							}
							
							//Options
							$options = array();
							if(!empty($value->Options)){
								$options = $this->model_tool_product_import->checkoptions($value->Options);
							}
							
							//Discount
							$discounts = array();
							if(!empty($value->Discounts)){
								$discounts = $this->model_tool_product_import->checkdiscount($value->Discounts);
							}
							
							//Specail
							$specails = array();
							if(!empty($value->Specials)){
								$specails = $this->model_tool_product_import->checkspecial($value->Specials);
							}
							
							//main Image
							$imagen = str_replace(' ','_',$value->Image);
							$mainimage = $value->Image;
							if(!empty($value->Image)){
							  $value->Image = str_replace('?dl=0','?raw=1',$value->Image);
							  $mainimage = $this->model_tool_product_import->fetchingimage($value->Image,$imagen);	
							}
							
							//Image
							$images = array();
							if(!empty($value->SubImages)){
								$ic=1;
								foreach(explode(';',trim($value->SubImages)) as $imageurl){
								  $imageurl = str_replace('?dl=0','?raw=1',$imageurl);
								  $imagename = $imagen.$ic++;
								  $images[] = $this->model_tool_product_import->fetchingimage($imageurl,$imagename);
								}
							}
							
							//Options Required
							$optionsrequired = array();
							if(!empty($value->Option_Required)){
								$optionsrequired = $this->model_tool_product_import->checkoptionsrequred($value->Option_Required);
							}
							
							$importdata=array(
							  'name' 	 			=> $value->Name,
							  'model'  	 			=> $value->Model,
							  'description' 		=> $value->Description,
							  'meta_titile' 		=> $value->MetaTitle,
							  'meta_description' 	=> $value->MetaDescription,
							  'meta_keyword' 		=> $value->MetaKeyword,
							  'tag' 				=> $value->Tag,
							  'image' 				=> $mainimage,
							  'sku' 				=> $value->SKU,
							  'upc' 				=> $value->UPC,
							  'ean' 				=> $value->EAN,
							  'jan' 				=> $value->JAN,
							  'isbn' 				=> $value->ISBN,
							  'mpn' 				=> $value->MPN,
							  'location' 			=> $value->Location,
							  'price' 				=> $value->Price,
							  'tax_class_id' 		=> $value->Tax_Class_ID,
							  'quantity' 			=> $value->Quantity,
							  'minimum' 			=> $value->Minimum,
							  'subtract' 			=> $value->Subtract,
							  'stock_status_id' 	=> $value->Stock_Status_ID,
							  'shipping' 			=> $value->Shipping,
							  'keyword' 			=> $value->SEO,
							  'date_available' 		=> ($value->Date_Available ? $value->Date_Available : date('Y-m-d')),
							  'length' 				=> $value->length,
							  'length_class_id' 	=> $value->Length_Class_ID,
							  'width' 				=> $value->Width,
							  'height' 				=> $value->Height,
							  'weight' 				=> $value->Weight,
							  'weight_class_id' 	=> $value->Weight_Class_ID,
							  'status' 				=> $value->Status,
							  'sort_order' 			=> $value->Sort_Order,
							  'manufacturer_id' 	=> $value->Manufacturer_ID,
							  'categories'			=> array_unique($categoryids),
							  'filters'				=> array_unique($filters),
							  'downloads' 			=> $downloads,
							  'relaled_products' 	=> $relaled_products,
							  'attributes'			=> $attributes,
							  'options'				=> $options,
							  'discounts'			=> $discounts,
							  'specails'			=> $specails,
							  'images'				=> $images,
							  'points'				=> $value->Points,
							  'viewed'				=> $value->Viewed,
							);
							
							if($this->request->post['importtype']==2){
							 $product_id = $this->model_tool_product_import->getproductIDbymodel($value->Model);
								 if($product_id){
									 $this->model_tool_product_import->Editproduct($importdata,$product_id,$language_id,$store_id);
									 $updateproduct++;
								 }else{
									 $this->model_tool_product_import->addproduct($importdata,$language_id,$store_id);
									 $newproduct++;
								 }
							}else{
								if((int)$value->product_id){
								$product_info = $this->model_catalog_product->getProduct($value->product_id);
									if($product_info){
										$this->model_tool_product_import->Editproduct($importdata,$value->product_id,$language_id,$store_id);
										$updateproduct++;
									}else{
										$this->model_tool_product_import->addoldproduct($importdata,$language_id,$store_id,$value->product_id);
										$newproduct++;
									}
								}else{
									$this->model_tool_product_import->addproduct($importdata,$language_id,$store_id);
									$newproduct++;
								}
							}
						 }
						
						$i++;						
					}
					if($newproduct || $updateproduct){
						$this->session->data['success'] = sprintf($this->language->get('text_success_product'),$newproduct,$updateproduct);
					}
				
					if(!$newproduct && !$updateproduct){
						$this->session->data['error_warning'] = $this->language->get('text_no_data');
					}
				}else{
					$this->session->data['error_warning'] = $this->language->get('error_warning');
				}
			}else{
				$this->session->data['error_warning'] = $this->language->get('error_warning');
			}
			if($inputFileName){
				unlink($inputFileName);
			}
			
		  }else{
			$this->session->data['error_warning'] = $this->language->get('error_warning');
		  }
		}
		$this->response->redirect($this->url->link('tool/excel_import', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
	protected function validate(){
		if(!$this->user->hasPermission('modify', 'tool/excel_import')){
			$this->error['warning'] = $this->language->get('error_permission');
			$this->session->data['error_warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
	
	protected function validatecustomerForm(){
		if(!$this->user->hasPermission('modify', 'tool/excel_import')){
			$this->error['warning'] = $this->language->get('error_permission');
			$this->session->data['error_warning'] = $this->language->get('error_permission');
		}
		
		if(empty($this->request->post['password_format'])){
			$this->error['warning'] = $this->language->get('error_password_format');
			$this->session->data['error_warning'] = $this->error['warning'];
		}
		
		return !$this->error;
	}
}
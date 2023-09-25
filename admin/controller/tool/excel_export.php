<?php
header('Cache-Control: no-cache, no-store');
ini_set('memory_limit', '1024M');
ini_set('max_execution_time', 900);
ini_set('error_reporting', E_ALL);
include DIR_SYSTEM.'library/PHPExcel.php';
class ControllerToolExcelExport extends Controller {
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
		
		$data['tab_export'] = $this->language->get('tab_export');
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
		$data['entry_base_id'] = $this->language->get('entry_base_id');
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
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tool/excel_point.tpl', $data));
	}
	
	
	public function exportManufacture(){
		$this->load->model('tool/excel_point');
		$this->load->model('setting/store');
		
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		
		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = null;
		}
		
		if (!empty($this->request->get['filter_start'])) {
			$filter_start = $this->request->get['filter_start'];
		} else {
			$filter_start = 0;
		}
		
		if (!empty($this->request->get['filter_limit'])) {
			$filter_limit = $this->request->get['filter_limit'];
		} else {
			$filter_limit = '';
		}
		
		$filter_data=array(
			'filter_status'   		=> $filter_status,
		    'filter_store'			=> $filter_store,
		    'start'           		=> $filter_start,
		    'limit'           		=> $filter_limit,
		);
		
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setTitle("Manufacturer");
		$i=1;
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, 'Manufacturer ID')->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, 'Stores')->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, 'Manufacturer Name')->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, 'SEO Keyword')->getColumnDimension('D')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, 'Image')->getColumnDimension('E')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, 'Sort Order')->getColumnDimension('F')->setAutoSize(true);
		
		$results = $this->model_tool_excel_point->getManufacturerdata($filter_data);
		
		foreach($results as $result){
			$i++;
			$storeinfo = $this->model_setting_store->getStore($result['store_id']);
			if($storeinfo){
				$store = $storeinfo['name'];
			}else{
				$store = 'default';
			}
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $result['manufacturer_id'])->getColumnDimension('A')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $store)->getColumnDimension('B')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $result['name'])->getColumnDimension('C')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $this->model_tool_excel_point->getmanufactureKeyword($result['manufacturer_id']))->getColumnDimension('D')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $result['image'])->getColumnDimension('E')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $result['sort_order'])->getColumnDimension('F')->setAutoSize(true);
		}
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename = 'manufacturer'.time().'.xls';
		header('Content-Type: application/vnd.ms-excel'); 
		header('Content-Disposition: attachment;filename='.$filename); 
		header('Cache-Control: max-age=0'); 
		$objWriter->save('php://output'); 
		exit();
	}
	
	public function exportCategories(){
		$this->load->model('tool/excel_point');
		$this->load->model('setting/store');
		
		if(!empty($this->request->get['filter_language'])){
			$language_id = $this->request->get['filter_language'];
		}else{
			$language_id = $this->config->get('config_language_id');
		}
		
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		
		if (isset($this->request->get['filter_categories'])) {
			$filter_categories = $this->request->get['filter_categories'];
		} else {
			$filter_categories = null;
		}
		
		
		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = null;
		}
		
		if (!empty($this->request->get['filter_limit'])) {
			$filter_limit = $this->request->get['filter_limit'];
		} else {
			$filter_limit = '';
		}
		
		if(!empty($this->request->get['filter_start'])){
			$filter_start = $this->request->get['filter_start'];
		}else{
			$filter_start = 0;
		}
		
		$filter_data=array(
			'filter_status'   		=> $filter_status,
		    'filter_language_id'	=> $language_id,
		    'filter_store'			=> $filter_store,
		    'filter_categories'		=> $filter_categories,
		    'start'           		=> $filter_start,
		    'limit'           		=> $filter_limit,
		);
		
		$results = $this->model_tool_excel_point->getCategories($filter_data);
	
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setTitle("Category");
		$i=1;
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, 'Category ID')->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, 'Language')->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, 'Store')->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, 'Name')->getColumnDimension('D')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, 'Description')->getColumnDimension('E')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, 'Meta Title')->getColumnDimension('F')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, 'Meta Description')->getColumnDimension('G')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, 'Meta Keyword')->getColumnDimension('H')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, 'Parent ID')->getColumnDimension('I')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, 'SEO Keyword')->getColumnDimension('J')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, 'Image')->getColumnDimension('K')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, 'Top')->getColumnDimension('L')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, 'Columns')->getColumnDimension('M')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, 'Sort Order')->getColumnDimension('N')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, 'Status')->getColumnDimension('O')->setAutoSize(true);
		
		foreach($results as $result){
			$i++;
			$storeinfo = $this->model_setting_store->getStore($result['store_id']);
			if($storeinfo){
				$store = $storeinfo['name'];
			}else{
				$store = 'default';
			}
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $result['category_id']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $result['language']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $store);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $result['name']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $result['description']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $result['meta_title']);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, $result['meta_description']);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $result['meta_keyword']);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, $result['parent_id']);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, $this->model_tool_excel_point->getcategoryKeyword($result['category_id']));
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, $result['image']);
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, $result['top']);
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, $result['column']);
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, $result['sort_order']);
			$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, $result['status']);
		}
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename = 'categories'.time().'.xls';
		header('Content-Type: application/vnd.ms-excel'); 
		header('Content-Disposition: attachment;filename='.$filename); 
		header('Cache-Control: max-age=0'); 
		$objWriter->save('php://output'); 
		exit();
	}
	
	public function exportproducts(){
		
		if(!empty($this->request->get['filter_language'])){
			$language_id = $this->request->get['filter_language'];
		}else{
			$language_id = $this->config->get('config_language_id');
		}
		
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = null;
		}

		if (isset($this->request->get['filter_price_to'])) {
			$filter_price_to = $this->request->get['filter_price_to'];
		} else {
			$filter_price_to = null;
		}
		
		if (isset($this->request->get['filter_price_form'])) {
			$filter_price_form = $this->request->get['filter_price_form'];
		} else {
			$filter_price_form = null;
		}

		if (isset($this->request->get['filter_quantity_to'])) {
			$filter_quantity_to = $this->request->get['filter_quantity_to'];
		} else {
			$filter_quantity_to = null;
		}
		
		if (isset($this->request->get['filter_quantity_form'])) {
			$filter_quantity_form = $this->request->get['filter_quantity_form'];
		} else {
			$filter_quantity_form = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		
		if (isset($this->request->get['filter_store'])) {
			$filter_store = $this->request->get['filter_store'];
		} else {
			$filter_store = null;
		}
		
		if (isset($this->request->get['filter_stock_status'])) {
			$filter_stock_status = $this->request->get['filter_stock_status'];
		} else {
			$filter_stock_status = null;
		}
		
		if (!empty($this->request->get['filter_start'])) {
			$filter_start = $this->request->get['filter_start'];
		} else {
			$filter_start = 0;
		}
		
		if (!empty($this->request->get['filter_limit'])) {
			$filter_limit = $this->request->get['filter_limit'];
		} else {
			$filter_limit = '';
		}
		
		if (!empty($this->request->get['filter_idstart'])) {
			$filter_idstart = $this->request->get['filter_idstart'];
		} else {
			$filter_idstart = 0;
		}
		
		if (!empty($this->request->get['filter_idend'])) {
			$filter_idend = $this->request->get['filter_idend'];
		} else {
			$filter_idend = '';
		}
		
		if (isset($this->request->get['filter_categories'])) {
			$filter_categories = $this->request->get['filter_categories'];
		} else {
			$filter_categories = null;
		}
		
		if (isset($this->request->get['filter_manufacturer'])) {
			$filter_manufacturer = $this->request->get['filter_manufacturer'];
		} else {
			$filter_manufacturer = null;
		}
		
		$filter_data=array(
			'filter_name'	  		=> $filter_name,
			'filter_model'	  		=> $filter_model,
			'filter_price_to'	  	=> $filter_price_to,
			'filter_price_form'	  	=> $filter_price_form,
			'filter_quantity_to' 	=> $filter_quantity_to,
			'filter_quantity_form' 	=> $filter_quantity_form,
			'filter_status'   		=> $filter_status,
		    'filter_language_id'	=> $language_id,
		    'filter_store'			=> $filter_store,
		    'filter_categories'		=> $filter_categories,
		    'filter_manufacturer'	=> $filter_manufacturer,
		    'start'           		=> $filter_start,
		    'limit'           		=> $filter_limit,
		    'filter_idstart'		=> $filter_idstart,
		    'filter_idend'			=> $filter_idend,
		    'filter_stock_status'   => $filter_stock_status,
		);
		
		$this->load->model('catalog/product');
		$this->load->model('tool/excel_point');
		$this->load->model('setting/store');
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setTitle("Product");
		$objPHPExcel->getActiveSheet()->getStyle('S')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$i=1;
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, 'Product ID')->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, 'Language')->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, 'Store')->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, 'Name')->getColumnDimension('D')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, 'Model')->getColumnDimension('E')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, 'Description')->getColumnDimension('F')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, 'Meta Title')->getColumnDimension('G')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, 'Meta Description')->getColumnDimension('H')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, 'Meta Keyword')->getColumnDimension('I')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, 'Tag')->getColumnDimension('J')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, 'Main Image')->getColumnDimension('K')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, 'SKU')->getColumnDimension('L')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, 'UPC')->getColumnDimension('M')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, 'EAN')->getColumnDimension('N')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, 'JAN')->getColumnDimension('O')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('P'.$i, 'ISBN')->getColumnDimension('P')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, 'MPN')->getColumnDimension('Q')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('R'.$i, 'Location')->getColumnDimension('R')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('S'.$i, 'Price')->getColumnDimension('S')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('T'.$i, 'Tax Class ID')->getColumnDimension('T')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('U'.$i, 'Quantity')->getColumnDimension('U')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('V'.$i, 'Minimum Quantity')->getColumnDimension('V')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('W'.$i, 'Subtract Stock')->getColumnDimension('W')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('X'.$i, 'Stock Status ID')->getColumnDimension('X')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('Y'.$i, 'Shipping')->getColumnDimension('Y')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('Z'.$i, 'SEO')->getColumnDimension('Z')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('AA'.$i, 'Date Available')->getColumnDimension('AA')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('AB'.$i, 'Length')->getColumnDimension('AB')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('AC'.$i, 'Length Class ID')->getColumnDimension('AC')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('AD'.$i, 'Width')->getColumnDimension('AD')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('AE'.$i, 'Height')->getColumnDimension('AE')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('AF'.$i, 'Weight')->getColumnDimension('AF')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('AG'.$i, 'Weight Class ID')->getColumnDimension('AG')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('AH'.$i, 'Status')->getColumnDimension('AH')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('AI'.$i, 'Sort Order')->getColumnDimension('AI')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('AJ'.$i, 'Manufacturer ID')->getColumnDimension('AJ')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('AK'.$i, 'Category ids')->getColumnDimension('AK')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('AL'.$i, 'Filters (Filter Group :: filter Value)')->getColumnDimension('AL')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('AM'.$i, 'Download')->getColumnDimension('AM')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('AN'.$i, 'Related Products')->getColumnDimension('AN')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('AO'.$i, 'Attributes (attribute_Group::attribute::text)')->getColumnDimension('AO')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('AP'.$i, 'Options (Option::Type::optionvalue~qty~subtract~price~points~weight)')->getColumnDimension('AP')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('AQ'.$i, 'Discount (Customer Group id::Quantity::Priority::Price::startdate::Enddate)')->getColumnDimension('AQ')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('AR'.$i, 'Special (Customer_group_id::Priority::Price::startdate::Enddate)')->getColumnDimension('AR')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('AS'.$i, 'Sub Images (image1,image2)')->getColumnDimension('AS')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('AT'.$i, 'Reward Points')->getColumnDimension('AT')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('AU'.$i, 'Viewed')->getColumnDimension('AU')->setAutoSize(true);
		
		$products = $this->model_tool_excel_point->getProducts($filter_data);
		foreach($products as $product){
			$storeinfo = $this->model_setting_store->getStore($product['store_id']);
			if($storeinfo){
				$store = $storeinfo['name'];
			}else{
				$store = 'default';
			}
		
			$i++;
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $product['product_id']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $product['language']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $store);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, html_entity_decode($product['name']));
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $product['model']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, html_entity_decode($product['description']));
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, $product['meta_title']);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $product['meta_description']);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, $product['meta_keyword']);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, $product['tag']);
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, $product['image']);
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, $product['sku']);
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, $product['upc']);
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, $product['ean']);
			$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, $product['jan']);
			$objPHPExcel->getActiveSheet()->setCellValue('P'.$i, $product['isbn']);
			$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, $product['mpn']);
			$objPHPExcel->getActiveSheet()->setCellValue('R'.$i, $product['location']);
			$objPHPExcel->getActiveSheet()->setCellValue('S'.$i, $product['price']);
			$objPHPExcel->getActiveSheet()->setCellValue('T'.$i, $product['tax_class_id']);
			$objPHPExcel->getActiveSheet()->setCellValue('U'.$i, $product['quantity']);
			$objPHPExcel->getActiveSheet()->setCellValue('V'.$i, $product['minimum']);
			$objPHPExcel->getActiveSheet()->setCellValue('W'.$i, $product['subtract']);
			$objPHPExcel->getActiveSheet()->setCellValue('X'.$i, $product['stock_status_id']);
			$objPHPExcel->getActiveSheet()->setCellValue('Y'.$i, $product['shipping']);
			$objPHPExcel->getActiveSheet()->setCellValue('Z'.$i, $this->model_tool_excel_point->getKeyword($product['product_id']));
			$objPHPExcel->getActiveSheet()->setCellValue('AA'.$i,  $product['date_available']);
			$objPHPExcel->getActiveSheet()->setCellValue('AB'.$i, $product['length']);
			$objPHPExcel->getActiveSheet()->setCellValue('AC'.$i, $product['length_class_id']);
			$objPHPExcel->getActiveSheet()->setCellValue('AD'.$i, $product['width']);
			$objPHPExcel->getActiveSheet()->setCellValue('AE'.$i, $product['height']);
			$objPHPExcel->getActiveSheet()->setCellValue('AF'.$i, $product['weight']);
			$objPHPExcel->getActiveSheet()->setCellValue('AG'.$i, $product['weight_class_id']);
			$objPHPExcel->getActiveSheet()->setCellValue('AH'.$i, $product['status']);
			$objPHPExcel->getActiveSheet()->setCellValue('AI'.$i, $product['sort_order']);
			$objPHPExcel->getActiveSheet()->setCellValue('AJ'.$i, $product['manufacturer_id']);
			$categories = $this->model_catalog_product->getProductCategories($product['product_id']);
			$objPHPExcel->getActiveSheet()->setCellValue('AK'.$i, (!empty($categories) ? implode(',',$categories) : ''));
			$filterdata=array();
			$this->load->model('catalog/filter');
			$filteres = $this->model_catalog_product->getProductFilters($product['product_id']);
			foreach($filteres as $filter_id){
				$filter_info = $this->model_catalog_filter->getFilter($filter_id);
				if($filter_info){
					$filterdata[] = html_entity_decode(($filter_info['group'] ? $filter_info['group']. ' :: ' . $filter_info['name'] : $filter_info['name']));
				}
			}
			$objPHPExcel->getActiveSheet()->setCellValue('AL'.$i, implode(', ',$filterdata));
			$downloads = $this->model_catalog_product->getProductDownloads($product['product_id']);
			$objPHPExcel->getActiveSheet()->setCellValue('AM'.$i, implode(', ',$downloads));
			$realated = $this->model_catalog_product->getProductRelated($product['product_id']);
			$objPHPExcel->getActiveSheet()->setCellValue('AN'.$i, implode(', ',$realated));
			
			//GetAttribute
			$this->load->model('catalog/attribute');
			$this->load->model('catalog/attribute_group');
			$attributes = $this->model_tool_excel_point->getProductAttributes($product['product_id'],$language_id);
			$objPHPExcel->getActiveSheet()->setCellValue('AO'.$i, implode(', ',$attributes));
			
			//options
			$productoptions = $this->model_tool_excel_point->getProductOptions($product['product_id'],$language_id);
			$objPHPExcel->getActiveSheet()->setCellValue('AP'.$i, implode('; ',$productoptions));
			
			///getDiscount
			$discounts=array();
			$productdiscounts = $this->model_catalog_product->getProductDiscounts($product['product_id']);
			foreach($productdiscounts as $pdiscount){
				$discounts[]= $pdiscount['customer_group_id'].'::'.$pdiscount['quantity'].'::'.$pdiscount['priority'].'::'.$pdiscount['price'].'::'.$pdiscount['date_start'].'::'.$pdiscount['date_end'];
			}
			$objPHPExcel->getActiveSheet()->setCellValue('AQ'.$i, implode(', ',$discounts));
			
			//GetSpecial
			$specials=array();
			$productspecials = $this->model_catalog_product->getProductSpecials($product['product_id']);
			foreach($productspecials as $pspecial){
				$specials[]= $pspecial['customer_group_id'].'::'.$pspecial['priority'].'::'.$pspecial['price'].'::'.$pspecial['date_start'].'::'.$pspecial['date_end'];
			}
			$objPHPExcel->getActiveSheet()->setCellValue('AR'.$i, implode(', ',$specials));
			
			//GET Images
			$images=array();
			$productimages = $this->model_catalog_product->getProductImages($product['product_id']);
			foreach($productimages as $pimage){
				$images[]= $pimage['image'];
			}
			$objPHPExcel->getActiveSheet()->setCellValue('AS'.$i, implode(', ',$images));
			$objPHPExcel->getActiveSheet()->setCellValue('AT'.$i, $product['points']);
			$objPHPExcel->getActiveSheet()->setCellValue('AU'.$i, $product['viewed']);
		}
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename = 'product'.time().'.xls';
		header('Content-Type: application/vnd.ms-excel'); 
		header('Content-Disposition: attachment;filename='.$filename); 
		header('Cache-Control: max-age=0'); 
		$objWriter->save('php://output'); 
		exit();
	}
	
	
	protected function validatecustomerForm(){
		if (!$this->user->hasPermission('modify', 'catalog/customer_import')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if(empty($this->request->post['emailaction'])){
			$this->error['warning'] = $this->language->get('error_email_action');	
		}
		
		if(empty($this->request->post['password_format'])){
			$this->error['warning'] = $this->language->get('error_password_format');
		}
		
		return !$this->error;
	}
}
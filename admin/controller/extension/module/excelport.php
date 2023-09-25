<?php
class ControllerExtensionModuleExcelport extends Controller {
	private $error = array();

	// public function __construct($registry) {
	// 	// Apply custom DB wrapper if ExcelPort is running on MijoShop

    //        if (method_exists($registry->get('db'), 'run') && stripos($this->request->server['REQUEST_URI'], 'com_mijoshop') !== FALSE) {
    //            require_once(DIR_SYSTEM . 'library/excelport_db.php');
    //            $registry->set('db', new ExcelPortDB($registry->get('db')));
    //        }
	// 	parent::__construct($registry);
	// }
	
	protected function validate() {
		$this->language->load('extension/module/excelport');

		if (!$this->user->hasPermission('modify', 'extension/module/excelport')) {
			$this->error[] = $this->language->get('error_permission');
			return false;
		}

		return true;
	}

    public function controller_before() {
        if (!defined('IMODULE_ROOT')) define('IMODULE_ROOT', substr(DIR_APPLICATION, 0, strrpos(DIR_APPLICATION, '/', -2)) . '/');

        if (!defined('IMODULE_ADMIN_ROOT')) define('IMODULE_ADMIN_ROOT', DIR_APPLICATION);
        if (!defined('IMODULE_SERVER_NAME')) define('IMODULE_SERVER_NAME', substr((defined('HTTP_CATALOG') ? HTTP_CATALOG : HTTP_SERVER), 7, strlen((defined('HTTP_CATALOG') ? HTTP_CATALOG : HTTP_SERVER)) - 8));

        if (!defined('IMODULE_CONFIG_LOCAL')) define('IMODULE_CONFIG_LOCAL', 'vendors/excelport/config_local.php');

        if (version_compare(VERSION, '2.1.0.1', '>=')) {
            if (!defined('IMODULE_TEMP_FOLDER')) define('IMODULE_TEMP_FOLDER', 'system/storage/cache/temp_excelport');
        } else {
        	if (!defined('IMODULE_TEMP_FOLDER')) define('IMODULE_TEMP_FOLDER', 'system/cache/temp_excelport');
        }

        if (!defined('IMODULE_UPMOST_VERSION')) define('IMODULE_UPMOST_VERSION', '2.99');

        if (!is_dir(IMODULE_ROOT . IMODULE_TEMP_FOLDER)) {
	        mkdir(IMODULE_ROOT . IMODULE_TEMP_FOLDER, 0755);
	        file_put_contents(IMODULE_ROOT . IMODULE_TEMP_FOLDER . DIRECTORY_SEPARATOR . 'index.html', 'Hello!');
        }

        $htaccess_file = IMODULE_ROOT . IMODULE_TEMP_FOLDER . DIRECTORY_SEPARATOR . '.htaccess';

        if (!file_exists($htaccess_file)) {
            $this->load->model('extension/module/excelport');
            $htaccess = '
                AddType text/excelport excelport
                <FilesMatch "\.(html|xlptemp|zip|xlsx|' . pathinfo($this->model_extension_module_excelport->get_progress_name(), PATHINFO_EXTENSION) . ')$">
                    allow from all
                </FilesMatch>
            ';
            file_put_contents($htaccess_file, $htaccess);
        }

        $this->now = time();
    }

	public function index() { 
		$data = array();

		$this->language->load('extension/module/excelport');

        $this->controller_before();

		$this->load->model('extension/module/excelport');
        $this->load->model('setting/store');
		$this->load->model('setting/setting');
		$this->load->model('localisation/language');
		$this->model_extension_module_excelport->openstock_integrate();
		
        if ($this->model_extension_module_excelport->openstock_installed()) {
            $data['openstock_installed'] = $this->language->get('text_openstock_installed');
        }

		$this->response->addHeader('Cache-Control: no-cache, no-store');
		
		$this->model_extension_module_excelport->ini_settings();

        if (stripos($this->request->server['REQUEST_URI'], 'com_mijoshop') !== FALSE) {
            $this->document->addStyle('../opencart/admin/view/stylesheet/excelport.css');
        } else {
	        $this->document->addStyle('view/stylesheet/excelport.css');
        }

		$this->document->setTitle($this->language->get('heading_title_version'));
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
			/*
			if (!empty($this->request->post['OaXRyb1BhY2sgLSBDb21'])) {
				$this->request->post['ExcelPort']['LicensedOn'] = $this->request->post['OaXRyb1BhY2sgLSBDb21'];
			}

			if (!empty($this->request->post['cHRpbWl6YXRpb24ef4fe'])) {
				$this->request->post['ExcelPort']['License'] = json_decode(base64_decode($this->request->post['cHRpbWl6YXRpb24ef4fe']), true);
			}
			*/
			$this->model_setting_setting->editSetting('ExcelPort', $this->request->post);
			
			$submitAction = empty($this->request->get['submitAction']) ? null : $this->request->get['submitAction'];

			if (empty($submitAction)) {
				$this->session->data['excelport_success'][] = $this->language->get('text_success');
			}
			
			try {
				switch ($submitAction) {
					case 'export' : {
						unset($this->session->data['generated_files']);
						unset($this->session->data['generated_file']);
						$this->session->data['generated_files'] = array();
						$this->model_extension_module_excelport->deleteProgress();
						$this->session->data['ajaxgenerate'] = true;
						$this->model_extension_module_excelport->cleanTemp(IMODULE_ROOT . IMODULE_TEMP_FOLDER);
					} break;
					case 'import' : {
						$this->model_extension_module_excelport->deleteProgress();
						$this->session->data['ajaximport'] = true;
						
						$uploadedFile = $this->model_extension_module_excelport->getStandardFile($this->request->files['ExcelPort'], 'Import', 'File');
						
						$this->session->data['uploaded_files'] = $this->model_extension_module_excelport->prepareUploadedFile($uploadedFile);
						
						if (!empty($this->session->data['uploaded_files']) && !empty($this->request->post['ExcelPort']['Import']['Delete'])) {
							if ($this->request->post['ExcelPort']['Import']['DataType'] == 'Products') {
								$this->load->model('extension/module/excelport_product');
								$this->model_extension_module_excelport_product->deleteProducts();
							} else if ($this->request->post['ExcelPort']['Import']['DataType'] == 'Categories') {
								$this->load->model('extension/module/excelport_category');
								$this->model_extension_module_excelport_category->deleteCategories();	
							} else if ($this->request->post['ExcelPort']['Import']['DataType'] == 'Options') {
								$this->load->model('extension/module/excelport_option');
								$this->model_extension_module_excelport_option->deleteOptions();	
							} else if ($this->request->post['ExcelPort']['Import']['DataType'] == 'Attributes') {
								$this->load->model('extension/module/excelport_attribute');
								$this->model_extension_module_excelport_attribute->deleteAttributes();
								$this->model_extension_module_excelport_attribute->deleteAttributeGroups();	
							} else if ($this->request->post['ExcelPort']['Import']['DataType'] == 'Customers') {
								$this->load->model('extension/module/excelport_customer');
								$this->model_extension_module_excelport_customer->deleteCustomers();
							} else if ($this->request->post['ExcelPort']['Import']['DataType'] == 'CustomerGroups') {
								$this->load->model('extension/module/excelport_customer_group');
								$this->model_extension_module_excelport_customer_group->deleteCustomerGroups();
							} else if ($this->request->post['ExcelPort']['Import']['DataType'] == 'Orders') {
								$this->load->model('extension/module/excelport_order');
								$this->model_extension_module_excelport_order->deleteOrders();
							} else if ($this->request->post['ExcelPort']['Import']['DataType'] == 'Coupons') {
								$this->load->model('extension/module/excelport_coupon');
								$this->model_extension_module_excelport_coupon->deleteCoupons();
							} else if ($this->request->post['ExcelPort']['Import']['DataType'] == 'Vouchers') {
								$this->load->model('extension/module/excelport_voucher');
								$this->model_extension_module_excelport_voucher->deleteVouchers();
							}
						}
					} break;
				}
			} catch(Exception $e) {
				$this->session->data['excelport_error'][] = $e->getMessage();	
			}
			
			$selectedTab = (empty($this->request->post['selectedTab'])) ? 0 : $this->request->post['selectedTab'];

			$this->response->redirect($this->url->link('extension/module/excelport', 'token=' . $this->session->data['token'] . '&tab='.$selectedTab, 'SSL'));
		}

		// Set language data
		$variables = array(
			'heading_title',
			'heading_title_version',
			'text_enabled',
			'text_disabled',
			'text_content_top',
			'text_content_bottom',
			'text_column_left',
			'text_column_right',
			'text_activate',
			'text_not_activated',
			'text_click_activate',
			'entry_code',
			'button_save',
			'button_cancel',
			'entry_layouts_active',
			'text_question_data',
			'text_datatype_option_products',
			'text_question_store',
			'text_question_language',
			'button_export',
			'text_note',
			'text_learn_to_increase',
			'button_import',
			'text_question_data_import',
			'text_question_store_import',
			'text_question_language_import',
			'text_question_file_import',
			'text_file_generating',
			'text_file_downloading',
			'text_import_done',
			'text_preparing_data',
			'text_export_entries_number',
			'text_import_limit',
			'text_confirm_delete_other',
			'text_question_delete_other',
			'text_question_type_export',
			'text_question_add_as_new',
			'text_datatype_option_categories',
			'text_datatype_option_attributes',
			'text_toggle_filter',
            'text_last_import',
			'button_add_condition',
			'button_discard_condition',
			'text_conjunction',
            'text_datatype_option_manufacturers',
			'text_the_value',
			'help_conjunction',
			'text_datatype_option_customers',
			'text_datatype_option_customer_groups',
			'text_datatype_option_options',
			'text_datatype_option_orders',
			'text_question_product_type',
			'text_question_product_type_full',
			'text_question_product_type_bulk',
			'text_question_product_type_quick',
			'text_datatype_option_coupons',
			'text_datatype_option_vouchers',
			'text_export_product_description_html',
			'text_export_non_store_products',
			'text_yes',
			'text_no',
			'option_encoded_html',
			'option_standard_html',
			'option_no_html'
		);

		foreach ($variables as $variable) $data[$variable] = $this->language->get($variable);
		
		$data['license_your_license'] = $this->language->get('license_your_license');
        $data['license_enter_code'] = $this->language->get('license_enter_code');
        $data['license_placeholder'] = $this->language->get('license_placeholder');
        $data['license_activate'] = $this->language->get('license_activate');
        $data['license_get_code'] = $this->language->get('license_get_code');
        $data['license_holder'] = $this->language->get('license_holder');
        $data['license_registered_domains'] = $this->language->get('license_registered_domains');
        $data['license_expires'] = $this->language->get('license_expires');
        $data['license_valid'] = $this->language->get('license_valid');
        $data['license_manage'] = $this->language->get('license_manage');
        $data['license_get_support'] = $this->language->get('license_get_support');
        $data['license_community'] = $this->language->get('license_community');
        $data['license_community_info'] = $this->language->get('license_community_info');
        $data['license_forums'] = $this->language->get('license_forums');
        $data['license_tickets'] = $this->language->get('license_tickets');
        $data['license_tickets_info'] = $this->language->get('license_tickets_info');
        $data['license_tickets_open'] = $this->language->get('license_tickets_open');
        $data['license_presale'] = $this->language->get('license_presale');
        $data['license_presale_info'] = $this->language->get('license_presale_info');
        $data['license_presale_bump'] = $this->language->get('license_presale_bump');
        $data['license_missing'] = $this->language->get('license_missing');

		$data['error_warning'] = '';
		$data['success_message'] = '';

		if (!empty($this->session->data['excelport_success'])) {
			$data['success_message'] = implode('<br />', $this->session->data['excelport_success']);
			unset($this->session->data['excelport_success']);
		}

		if (!empty($this->session->data['excelport_error'])) {
			$this->error = array_merge($this->error, $this->session->data['excelport_error']);
			unset($this->session->data['excelport_error']);
		}

		if (!empty($this->error)) {
			$data['error_warning'] = implode('<br />', $this->error);
		}

		$data['text_supported_in_oc1541'] = sprintf($this->language->get('text_supported_in_oc1541'), IMODULE_UPMOST_VERSION);
		$data['default_store_name'] = $this->config->get('config_name') . $this->language->get('text_default');

		$data['progress_name'] = $this->model_extension_module_excelport->get_progress_name();

		$data['stores'] = array_values($this->model_setting_store->getStores());

		$data['languages'] = array_values($this->model_localisation_language->getLanguages());
		
  	    $data['breadcrumbs'] = array(
			array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
			),
			array(
				'text'      => $this->language->get('text_module'),
				'href'      => $this->url->link('extension/extension', 'type=module&token=' . $this->session->data['token'], 'SSL')
			),
			array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('extension/module/excelport', 'token=' . $this->session->data['token'], 'SSL')
			)
		);

		$data['action'] = $this->url->link('extension/module/excelport', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/extension', 'type=module&token=' . $this->session->data['token'], 'SSL');
		
		$data['https_server'] = dirname(HTTPS_SERVER);
		$data['http_server'] = dirname(HTTP_SERVER);

		$data['ajaxgenerate'] = empty($this->session->data['ajaxgenerate']) ? 'false' : $this->session->data['ajaxgenerate'];
		unset($this->session->data['ajaxgenerate']);

		$data['ajaximport'] = empty($this->session->data['ajaximport']) ? 'false' : $this->session->data['ajaximport'];
		unset($this->session->data['ajaximport']);

		if (isset($this->request->post['ExcelPort'])) {
			foreach ($this->request->post['ExcelPort'] as $key => $value) {
				$data['data']['ExcelPort'][$key] = $this->request->post['ExcelPort'][$key];
			}
		} else {
			$configValue = $this->model_setting_setting->getSetting('ExcelPort');
			$data['data'] = $configValue;

			/*
			if (empty($configValue['ExcelPort']['LicensedOn'])) {
			    $data['error_excelport_licensed_on'] = $this->language->get('license_missing');
			}
			*/
		}

        $data['conditions'] = $this->model_extension_module_excelport->getConditions();
		$data['operations'] = $this->model_extension_module_excelport->getOperations();

		$data['tabs'] = $this->model_extension_module_excelport->getTabs();
		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');
		$data['column_left'] = $this->load->controller('common/column_left');

		$this->response->setOutput($this->load->view('extension/module/excelport.tpl', $data));
	}
	
	public function ajaxgenerate() {
        $this->controller_before();

		header('Cache-Control: no-cache, no-store');
		$this->session->data['start_time'] = time();
		ini_set('memory_limit', '1024M');
		ini_set('max_execution_time', 900);
		ini_set('default_charset', 'UTF-8');
		ini_set('display_errors', 1);
		ini_set('error_reporting', E_ALL);
		$this->load->model('extension/module/excelport');
		$error = false;
		//$this->model_extension_module_excelport->deleteProgress();
		
		set_error_handler(array($this, 'error_handler'));
		
		try {
			$this->session->data['success'] = array();
			if ($this->model_extension_module_excelport->exportXLS(
				$this->request->post['ExcelPort']['Export']['DataType'], 
				$this->request->post['ExcelPort']['Export']['Language'], 
				$this->request->post['ExcelPort']['Export']['Store'], IMODULE_ROOT . IMODULE_TEMP_FOLDER, 
				$this->request->post['ExcelPort']['Settings'], 
				(int)$this->request->post['ExcelPort']['Export']['ProductExportMode'], 
				!empty($this->request->post['ExcelPort']['Export']['Filter']), 
				!empty($this->request->post['ExcelPort']['Export']['Filters']) ? $this->request->post['ExcelPort']['Export']['Filters'] : array()
			)) {
				//$this->session->data['success'][] = 'Success'; // TODO - AJAX
			} else {
				//$this->session->data['error_warning'][] = 'I\'m a Failure :(';
			}
		} catch (Exception $e) {
			$error = $e->getMessage();	
		}
		
		restore_error_handler();
		$progress = $this->model_extension_module_excelport->getProgress($error);
		header('Content-Type: application/json');
		echo json_encode($progress);
		exit;
	}
	
	public function ajaximport() {
        $this->controller_before();

		header('Cache-Control: no-cache, no-store');
		$this->session->data['start_time'] = time();
		ini_set('memory_limit', '1024M');
		ini_set('max_execution_time', 900);
		ini_set('default_charset', 'UTF-8');
		ini_set('display_errors', 1);
		ini_set('error_reporting', E_ALL);
		$this->load->model('extension/module/excelport');
		$error = false;
		
		//$this->model_extension_module_excelport->deleteProgress();
		if (!empty($this->session->data['uploaded_files'])) {
			$file = $this->session->data['uploaded_files'][0];
			
			set_error_handler(array($this, 'error_handler'));
			
			try {
				$this->session->data['success'] = array();

				$this->model_extension_module_excelport->importXLS($this->request->post['ExcelPort']['Import']['DataType'], $this->request->post['ExcelPort']['Import']['Language'], $file, $this->request->post['ExcelPort']['Settings'], !empty($this->request->post['ExcelPort']['Import']['AddAsNew']));

                $this->load->model('setting/setting');

                $settings = $this->model_setting_setting->getSetting('ExcelPort');
                
                $settings['ExcelPort']['LastImport'] = $file;
                $this->model_setting_setting->editSetting('ExcelPort', $settings);
			} catch (Exception $e) {
				$error = $e->getMessage();	
			}
			
			restore_error_handler();
			
		} else {
			$this->language->load('extension/module/excelport');
			$progress = $this->model_extension_module_excelport->getProgress();
			$progress['finishedImport'] = true;
			$this->model_extension_module_excelport->setProgress($progress);
		}
		
		$progress = $this->model_extension_module_excelport->getProgress($error);
		header('Content-Type: application/json');
		echo json_encode($progress);
		exit;
	}
	
	public function download() {
        $this->controller_before();

		header('Cache-Control: no-cache, no-store');
		$files = $this->session->data['generated_files'];
		$this->load->model('extension/module/excelport');
		
		if (!empty($files)) {
			$this->load->model('localisation/language');
			
			$name = 'excelport_' . str_replace('/', '_', substr(HTTP_CATALOG, 7, strlen(HTTP_CATALOG) - 8)) . '_' . date("Y-m-d_H-i-s") . ".zip";
			
			$file = $this->model_extension_module_excelport->createZip($files, IMODULE_ROOT . IMODULE_TEMP_FOLDER . '/' . $name, true, IMODULE_ROOT . IMODULE_TEMP_FOLDER . '/');
			if (file_exists($file) && !empty($file)) {
				$this->model_extension_module_excelport->createDownload($file, false);
			} else {
				$this->model_extension_module_excelport->cleanTemp();	
			}
		} else {
			$this->model_extension_module_excelport->cleanTemp();	
		}
	}
	
	public function install() {
		
	}
	
	public function uninstall() {
		$this->load->model('extension/module/excelport');
		$this->model_extension_module_excelport->deleteSetting('excelport');
	}
	
	public function error_handler($severity, $message, $file, $line) {
		throw new Exception($message . " in file " . $file . " on line " . $line);
	}
}
?>
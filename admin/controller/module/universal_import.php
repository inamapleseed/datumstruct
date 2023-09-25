<?php
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

ini_set('memory_limit', -1);
@set_time_limit(3600);

class ControllerModuleUniversalImport extends Controller {
	private $error = array();
	private $separators = array(',' => ',', ';' => ';', '|' => '|', '^' => '^', '~' => '~', '\t' => 'Tab');
	private $import_types = array('product', 'product_update', 'order_status_update', 'category', 'information', 'manufacturer', 'customer', 'attribute', 'filter');
	private $identifiers_product = array('model', 'sku', 'upc', 'ean', 'jan', 'isbn', 'mpn', 'product_id');
	private $identifiers_category = array('name', 'category_id');
	private $identifiers_customer = array('email', 'customer_id');
	private $identifiers_attribute = array('attribute_id', 'attribute_name');
	private $identifiers_filter = array('filter_id', 'filter_name');
	private $identifiers_information = array('title');
	private $identifiers_order = array('order_id');
	private $identifiers_order_status = array('order_id');
	private $identifiers_common = array('name');
  
	private $export_types = array('product', 'category', 'information', 'manufacturer', 'customer', 'order', 'attribute', 'filter');
  
  private $module = 'universal_import';
  
  const MODULE = 'universal_import';
  const PREFIX = 'gkd_impexp';
  
  private $token;
  private $languages;
	
  public function __construct($registry) {
		parent::__construct($registry);
    
    if (!defined('GKD_CRON')) {
      $this->token = isset($this->session->data['user_token']) ? 'user_token='.$this->session->data['user_token'] : 'token='.$this->session->data['token'];
    }
    
    $this->load->model('tool/universal_import');
    
    if (version_compare(VERSION, '3', '>=')) {
      $this->load->language('extension/module/universal_import');
    } else {
      $this->language->load('module/universal_import');
    }
    
    $this->load->model('localisation/language');
    $this->languages = $this->model_localisation_language->getLanguages();
    
    foreach ($this->languages as &$language) {
      if (version_compare(VERSION, '2.2', '>=')) {
        $language['image'] = 'language/'.$language['code'].'/'.$language['code'].'.png';
      } else {
        $language['image'] = 'view/image/flags/'. $language['image'];
      }
    }
    
    if (!defined('GKD_UNIV_IMPORT')) {
      define('GKD_UNIV_IMPORT', 1);
    }
	}

	public function index() {
    $asset_path = $data['_asset_path'] = 'view/universal_import/';
    defined('_JEXEC') && $asset_path  = $data['_asset_path'] = 'admin/' . $asset_path;
    $data['_img_path'] = $asset_path . 'img/';
		$data['_language'] = &$this->language;
		$data['_config'] = &$this->config;
		$data['_url'] = &$this->url;
		$data['token'] = $this->token;
    $data['OC_V2'] = version_compare(VERSION, '2', '>=');
    
    $data['demo_mode'] = !$this->user->hasPermission('modify', 'module/universal_import');
		
		if (!version_compare(VERSION, '2', '>=')) {
			$this->document->addStyle($asset_path . 'awesome/css/font-awesome.min.css');
      $data['style_v15'] = file_get_contents($asset_path . 'bootstrap.min.css');
			$data['style_v15'] .= file_get_contents($asset_path . 'style.css');
			$this->document->addScript($asset_path . 'bootstrap.min.js');
		}
    // file upload script
		$this->document->addScript($asset_path . 'file-upload/vendor/jquery.ui.widget.js');
		$this->document->addScript($asset_path . 'file-upload/jquery.iframe-transport.js');
		$this->document->addScript($asset_path . 'file-upload/jquery.fileupload.js');
    
		$this->document->addStyle($asset_path . 'file-upload/css/jquery.fileupload.css');
		$this->document->addStyle($asset_path . 'prettyCheckable.css');
		$this->document->addScript($asset_path . 'jquery.tablednd.js');
		$this->document->addScript($asset_path . 'prettyCheckable.js');
    
		$this->document->addScript($asset_path . 'selectize.js');
		$this->document->addStyle($asset_path . 'selectize.css');
		$this->document->addStyle($asset_path . 'selectize.bootstrap3.css');
		$this->document->addStyle($asset_path . 'style.css');
    
    // CLI logs
    if (!empty($this->request->get['clear_cli_logs']) && file_exists(DIR_LOGS.'universal_import_cron.log')) {
      unlink(DIR_LOGS.'universal_import_cron.log');
      
      if (version_compare(VERSION, '2', '>=')) {
        $this->response->redirect($this->url->link('module/universal_import', $this->token, 'SSL'));
      } else {
        $this->redirect($this->url->link('module/universal_import', $this->token, 'SSL'));
      }
    }
    
    $data['cli_log'] = $data['cli_log_link'] = '';
    
    $file = DIR_LOGS.'universal_import_cron.log';
    
		if (file_exists($file)) {
      $data['cli_log_link'] = $this->url->link('module/universal_import/save_cli_log', $this->token, 'SSL');
      $data['cli_log'] = $this->readLogFile($file, 10000);
      
			$size = filesize($file);

      $suffix = array(
        'B',
        'KB',
        'MB',
        'GB',
        'TB',
        'PB',
        'EB',
        'ZB',
        'YB'
      );

      $i = 0;

      while (($size / 1024) > 1) {
        $size = $size / 1024;
        $i++;
      }
      
      $data['cli_log_size'] = round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i];
		}
    
    // Checks
    if (!is_writable(DIR_APPLICATION . 'view/universal_import/profiles')) {
      $this->session->data['warning'] = $this->language->get('text_profile_dir_not_writable') . ' ' . DIR_APPLICATION . 'view/universal_import/profiles';
    }
    
    // create temp if not exists
    if (!file_exists(DIR_CACHE . 'universal_import')) {
      mkdir(DIR_CACHE . 'universal_import', 0755, true);
    }
    
    // delete temp files
    if (glob(DIR_CACHE.'universal_import/*')) {
      foreach (glob(DIR_CACHE.'universal_import/*') as $file) {
        if (is_file($file) && filemtime($file) < time() - 86400) {
          unlink($file);
        }
      }
    }

    $data['languages'] = $this->languages;
    
		$this->document->setTitle(strip_tags($this->language->get('heading_title')));

    // multi-stores management
		$this->load->model('setting/store');
		$data['stores'] = array();
		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->config->get('config_name')
		);

		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$data['stores'][] = array(
				'store_id' => $store['store_id'],
				'name'     => $store['name']
			);
		}
		
		$data['store_id'] = $store_id = 0;
    
    // Overwrite store settings
		if (isset($this->request->get['store_id']) && $this->request->get['store_id']) {
			$data['store_id'] = $store_id = (int) $this->request->get['store_id'];
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '".$store_id."'");
			
			foreach ($query->rows as $setting) {
				if (!$setting['serialized']) {
					$this->config->set($setting['key'], $setting['value']);
				} else {
					$this->config->set($setting['key'], unserialize($setting['value']));
				}
			}
		}
    
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting(self::PREFIX, $this->request->post, $store_id);				

			$this->session->data['success'] = $this->language->get('text_success');

      $redirect_store = '';
			if ($store_id) {
				$redirect_store = '&store_id=' . $store_id;
      }
      
      if (version_compare(VERSION, '2', '>=')) {
				$this->response->redirect($this->url->link('module/universal_import', $this->token . $redirect_store, 'SSL'));
			} else {
				$this->redirect($this->url->link('module/universal_import', $this->token . $redirect_store, 'SSL'));
			}
		}

    
		$data['heading_title'] = strip_tags($this->language->get('heading_title'));

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');
    
		$data['prefix'] = $prefix = self::PREFIX.'_';

    $data['import_extensions'] = array('csv', 'xml', 'xslx', 'odt');
    $data['export_extensions'] = array('csv', 'xml', 'xlsx', 'odt');
    
    if (file_exists(DIR_SYSTEM . 'library/PHPExcel/PHPExcel.php')) {
      $data['import_extensions'] = array('csv', 'xml', 'xls', 'xslx', 'ods');
      $data['export_extensions'] = array('csv', 'xml', 'xls', 'xlsx', 'ods', 'html');
    }
    
    // params
    $params_array = array(
      $prefix . 'batch_imp',
      $prefix . 'batch_exp',
      $prefix . 'sleep',
      $prefix . 'cron_key',
      $prefix . 'default_label',
    );
    
    foreach ($params_array as $param_name) {
      if (isset($this->request->post[$param_name])) {
        $data[$param_name] = $this->request->post[$param_name];
      } else {
        $data[$param_name] = $this->config->get($param_name);
      }
    }
    
    if (version_compare(VERSION, '3', '>=')) {
      $this->load->model('setting/extension');
			$extension_model = $this->model_setting_extension;
    } else if (version_compare(VERSION, '2', '>=')) {
      $this->load->model('extension/extension');
			$extension_model = $this->model_extension_extension;
		} else {
			$this->load->model('setting/extension');
			$extension_model = $this->model_setting_extension;
		}

    $data['installed_modules'] = $extension_model->getInstalled('module');
    
    foreach (array('success', 'error', 'info', 'warning') as $notifiy_msg) {
      if (isset($this->session->data[$notifiy_msg])) {
        $data[$notifiy_msg] = $this->session->data[$notifiy_msg];
        unset($this->session->data[$notifiy_msg]);
      } else {
        $data[$notifiy_msg] = '';
      }
    }

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', $this->token, 'SSL'),
			'separator' => false
		);

		if (version_compare(VERSION, '3', '>=')) {
      $extension_link = $this->url->link('marketplace/extension', 'type=module&' . $this->token, 'SSL');
    } else if (version_compare(VERSION, '2.3', '>=')) {
      $extension_link = $this->url->link('extension/extension', 'type=module&' . $this->token, 'SSL');
    } else {
      $extension_link = $this->url->link('extension/module', $this->token, 'SSL');
    }
    
    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_module'),
      'href'      => $extension_link,
      'separator' => ' :: '
    );

		$data['breadcrumbs'][] = array(
			'text'      => strip_tags($this->language->get('heading_title')),
			'href'      => $this->url->link('module/universal_import', $this->token, 'SSL'),
			'separator' => ' :: '
		);
    
		$data['action'] = $this->url->link('module/universal_import', $this->token . '&store_id=' . $store_id, 'SSL');
    
		$data['cancel'] = $extension_link;

    $module_xml = 'universal_import_pro';
    
		if (is_file(DIR_SYSTEM.'../vqmod/xml/'.$module_xml.'.xml')) {
			$data['module_version'] = @simplexml_load_file(DIR_SYSTEM.'../vqmod/xml/'.$module_xml.'.xml')->version;
      $data['module_type'] = 'vqmod';
		} else if (is_file(DIR_SYSTEM.'../system/'.$module_xml.'.ocmod.xml')) {
      $data['module_version'] = simplexml_load_file(DIR_SYSTEM.'../system/'.$module_xml.'.ocmod.xml')->version;
      $data['module_type'] = 'ocmod';
    } else {
      $data['module_version'] = 'not found';
      $data['module_type'] = '';
		}
    
    $data['templates'] = array();
    
    // Import
    $data['import_types'] = $this->import_types;
    
    $data['profiles'] = array();
    foreach ($this->import_types as $import_type) {
      $profiles = glob(DIR_APPLICATION . $asset_path . 'profiles/' . $import_type . '/*.cfg');
      
      if ($profiles) {
        foreach ($profiles as $file) {
          $data['profiles'][] = array(
            'name' => basename($file, '.cfg'),
            'type' => $import_type,
          );
        }
      }
    }
    
    // categories
    $this->load->model('catalog/category');
    $categories = $this->model_catalog_category->getCategories(array());
    
    $data['categories'] = array();
    //$data['categories'][''] = '';
    foreach ($categories as $category) {
      $data['categories'][$category['category_id']] = $category['name'];
    }
      
    // check tables
    $this->db_tables();
    
    // Export
    $data['export_types'] = $this->export_types;
    
		if (version_compare(VERSION, '2', '>=')) {
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			
      if (version_compare(VERSION, '3', '>=')) {
        $this->config->set('template_engine', 'template');
        $this->response->setOutput($this->load->view('module/universal_import', $data));
      } else {
        $this->response->setOutput($this->load->view('module/universal_import.tpl', $data));
      }
		} else {
			$data['column_left'] = '';
			$this->data = &$data;
			$this->template = 'module/universal_import.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);
			
      if(version_compare(VERSION, '2', '>=')) {
        $render = $this->render();
      } else {
        $render = str_replace(array('view/javascript/jquery/jquery-1.6.1.min.js','view/javascript/jquery/jquery-1.7.1.min.js'), 'https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js', $this->render());
      }
      
			$this->response->setOutput($render);
		}
	}

  public function modal_info() {
    $this->load->language('module/universal_import');
    
    $items = explode(',', $this->request->post['info']);
    
    $extra_class = $this->language->get('info_css_' . $items[0]) != 'info_css_' . $items[0] ? $this->language->get('info_css_' . $items[0]) : 'modal-lg';
    $title = $this->language->get('info_title_' . $items[0]) != 'info_title_' . $items[0] ? $this->language->get('info_title_' . $items[0]) : $this->language->get('info_title_default');
    
    $message = '';
    
    foreach ($items as $item) {
      $message .= $this->language->get('info_msg_' . $item) != 'info_msg_' . $item ? $this->language->get('info_msg_' . $item) : $this->language->get('info_msg_default') .': ' . $item;
    }
      
    echo '<div class="modal-dialog ' . $extra_class . '">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><i class="fa fa-info-circle"></i> ' . $title . '</h4>
        </div>
        <div class="modal-body">' . $message . '</div>
      </div>
    </div>';
    
    die;
	}

  public function save_profile() {
    if (!$this->user->hasPermission('modify', 'module/universal_import')) {
      echo json_encode(array('error' => $this->language->get('error_permission')));
      exit;
    }
    
    if (!empty($this->request->post['save_profile'])) {
      $name = $this->request->post['save_profile'];
    } else if (!empty($this->request->post['profile_name'])) {
      $name = $this->request->post['profile_name'];
    } else {
      $name = 'New profile';
    }
    
    //$array = self::array_filter_recursive($this->request->post); // do not filter, cron jobs needs also empty values
    $array = $this->request->post;
    
    if (!is_dir(DIR_APPLICATION . 'view/universal_import/profiles/'. $this->request->post['import_type'])) {
      mkdir(DIR_APPLICATION . 'view/universal_import/profiles/'. $this->request->post['import_type'], 0766, true);
    }
    
    $filename = DIR_APPLICATION . 'view/universal_import/profiles/'. $this->request->post['import_type'] .'/' . $name . '.cfg';
    
    file_put_contents($filename, '<?php return ' . var_export($array, true) . ';');
    
    echo json_encode(array('success'=> $this->language->get('text_profile_saved')));
    exit;
  }
  
  public function import_file() {
    /*
    if (!$this->user->hasPermission('modify', 'module/universal_import')) {
      echo json_encode(array('files' => array(0 => array('error' => 'You must have write access to this module in order to upload file.<br/>You can add rights in System > Users > User groups.'))));
      die;
    }
    */
    
    require_once(DIR_APPLICATION.'model/tool/universal_import_upload.php');
    new UploadHandler();
  }
  
  public function get_profile_source() {
    $profile = array();
    if (!empty($this->request->post['profile'])) {
      $profile = include DIR_APPLICATION . 'view/universal_import/profiles/'. str_replace(array('/','\\'), '', $this->request->post['import_type']) .'/' . str_replace(array('/','\\'), '', $this->request->post['profile']) . '.cfg';
    }
    
    $settings = array();
    
    if (!empty($profile['import_source'])) {
      $settings['source'] = $profile['import_source'];
    } else {
      $settings['source'] = 'upload';
    }
    
    if (!empty($profile['import_extension'])) {
      $settings['extension'] = $profile['import_extension'];
    }
    
    header('Content-type: application/json');
    echo json_encode($settings);
  }
  
  public function import_step1() {
    $data['_language'] = $this->language;
		$data['_config'] = $this->config;
    
    $data['import_source'] = $this->request->post['import_source'];
    $data['update'] = $update = strpos($this->request->post['import_type'], '_update');
    $data['type'] = $type = str_replace('_update', '', $this->request->post['import_type']);
    
    // set profile
    $data['profile'] = array();
    if (!empty($this->request->post['profile'])) {
      $data['profile'] = include DIR_APPLICATION . 'view/universal_import/profiles/'. str_replace(array('/','\\'), '', $this->request->post['import_type']) .'/' . str_replace(array('/','\\'), '', $this->request->post['profile']) . '.cfg';
    }
    
    if (version_compare(VERSION, '3', '>=')) {
      $this->config->set('template_engine', 'template');
      $this->response->setOutput($this->load->view('module/universal_import_file', $data));
    } else if (version_compare(VERSION, '2', '>=')) {
      $this->response->setOutput($this->load->view('module/universal_import_file.tpl', $data));
		} else {
			$this->data = &$data;
			$this->template = 'module/universal_import_file.tpl';
			$this->response->setOutput($this->render());
		}
  }
  
  public function import_step2() {
    $data['_language'] = $this->language;
		$data['_config'] = $this->config;
    
    $data['update'] = $update = strpos($this->request->post['import_type'], '_update');
    $data['type'] = $type = str_replace('_update', '', $this->request->post['import_type']);
    
    if (!file_exists(DIR_CACHE . 'universal_import')) {
      mkdir(DIR_CACHE . 'universal_import', 0755, true);
    }
    
    if (!$this->user->hasPermission('modify', 'module/universal_import')) {
      if (!empty($this->request->post['demo_file']) && in_array($this->request->post['demo_file'], array('products.csv', 'categories.csv', 'informations.csv', 'manufacturers.csv', 'customers.csv'))) {
        copy(DIR_APPLICATION . 'view/universal_import/demo/'. $this->request->post['demo_file'], DIR_CACHE . 'universal_import/'.$this->request->post['demo_file']);
      } else if ($this->request->post['import_source'] != 'upload') {
        die('<div class="alert alert-danger" style="margin-top:15px"><i class="fa fa-exclamation-circle"></i> Demo mode: only file upload or demo files are allowed</div>');
      }
    }
    
    $filetype = '';
    
    if (!empty($this->request->post['import_extension']) && in_array($this->request->post['import_extension'], array('csv', 'xml', 'xls', 'xlsx', 'json', 'ods'))) {
      $filetype = $data['filetype'] = $this->request->post['import_extension'];
    } else {
      $filetype = $data['filetype'] = strtolower(pathinfo($this->request->post['import_file'], PATHINFO_EXTENSION));
    }
    
    $data['importLabels'] = array();
    $importLabels = $this->db->query("SELECT import_batch FROM " . DB_PREFIX . "product WHERE import_batch <> '' GROUP BY import_batch")->rows;
    
    foreach ($importLabels as $importLabel) {
      $data['importLabels'][] = $importLabel['import_batch'];
    }
    
    // test if file exists
    if ($this->request->post['import_source'] == 'ftp') {
      if(!file_exists($this->request->post['import_ftp'] . $this->request->post['import_file'])) {
        sleep(1);
        header('Content-type: application/json');
        echo json_encode(array('file_error' => $this->language->get('error_file_not_found')));
        die;
      }
    } else if ($this->request->post['import_source'] == 'url') {
      $headers = @get_headers($this->request->post['import_file']);
      
      if (!in_array($filetype, array('csv', 'xml', 'xls', 'xlsx', 'json', 'ods'))) {
        $headerFiletype = $this->getHeaderFileType(html_entity_decode($this->request->post['import_file'], ENT_QUOTES, 'UTF-8'));
        if (in_array(strtolower($filetype), array('csv', 'xml', 'xls', 'xlsx', 'json', 'ods'))) {
          $filetype = $data['filetype'] = $headerFiletype;
        }
      }
      
      if (!in_array($filetype, array('csv', 'xml', 'xls', 'xlsx', 'json', 'ods'))) {
        sleep(1);
        header('Content-type: application/json');
        echo json_encode(array('file_error' => sprintf($this->language->get('error_extension'), $filetype)));
        die;
      }
      
      //$this->request->post['import_file'] = $this->downloadFile($this->request->post['import_file']);
      
      /* Some servers return 403 when trying to access headers but access to file is ok, so better check the download
      if (!stripos($headers[0], '200 OK')) {
        sleep(1);
        echo 'file_not_found';
        die;
      }
      */
    } else if ($this->request->post['import_source'] == 'path') {
      if (!file_exists(html_entity_decode($this->request->post['import_file'], ENT_QUOTES, 'UTF-8'))) {
        sleep(1);
        header('Content-type: application/json');
        echo json_encode(array('file_error' => $this->language->get('error_file_not_found')));
        die;
      }
      
      if (!in_array($filetype, array('csv', 'xml', 'xls', 'xlsx', 'json', 'ods'))) {
        $headerFiletype = $this->getHeaderFileType(html_entity_decode($this->request->post['import_file'], ENT_QUOTES, 'UTF-8'));
        if (in_array(strtolower($filetype), array('csv', 'xml', 'xls', 'xlsx', 'json', 'ods'))) {
          $filetype = $data['filetype'] = $headerFiletype;
        }
      }
      
      if (!in_array($filetype, array('csv', 'xml', 'xls', 'xlsx', 'json', 'ods'))) {
        sleep(1);
        header('Content-type: application/json');
        echo json_encode(array('file_error' => sprintf($this->language->get('error_extension'), $filetype)));
        die;
      }
    }
    
    // reset temp file
    if (isset($this->session->data['univimport_temp_file'])) {
      unset($this->session->data['univimport_temp_file']);
    }
    
    if ($this->request->post['import_source'] == 'upload') {
      $import_file = DIR_CACHE.'universal_import/'.str_replace(array('../', '..\\'), '', $this->request->post['import_file']);
    } else if ($this->request->post['import_source'] == 'url') {
      $import_file = DIR_CACHE.'universal_import/remote-'.uniqid().'.'.$filetype;
      
      // copy remote file in temp file
      $localCopy = fopen($import_file, "w+");
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, html_entity_decode($this->request->post['import_file'], ENT_QUOTES, 'UTF-8'));
      curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_FAILONERROR, true);
      curl_setopt($ch, CURLOPT_FILE, $localCopy);
      
      $dlSuccess = curl_exec($ch);
      //$info = curl_getinfo($ch);
      
      if ($dlSuccess) {
        fclose($localCopy);
        $this->session->data['univimport_temp_file'] = $import_file;
      } else if(curl_errno($ch)) {
        sleep(1);
        header('Content-type: application/json');
        echo json_encode(array('file_error' => sprintf($this->language->get('error_curl'), curl_error($ch))));
        fclose($localCopy);
        curl_close($ch);
        die;
      }
    } else if ($this->request->post['import_source'] == 'ftp') {
      $import_file = $this->request->post['import_ftp'].html_entity_decode($this->request->post['import_file'], ENT_QUOTES, 'UTF-8');
    } else {
      $import_file = $this->request->post['import_file'];
    }
    
    if (in_array($type, array('product', 'category', 'information', 'manufacturer'))) {
      $this->language->load('catalog/'.$type);
    }
    
    if (in_array($type, array('attribute', 'filter'))) {
      $this->language->load('catalog/product');
    }
    
    // set profile
    $data['profile'] = array();
    if (!empty($this->request->post['profile'])) {
      $data['profile'] = include DIR_APPLICATION . 'view/universal_import/profiles/'. str_replace(array('/','\\'), '', $this->request->post['import_type']) .'/' . str_replace(array('/','\\'), '', $this->request->post['profile']) . '.cfg';
    }

    $data['separators'] = $this->separators;
    
    if (isset($this->{'identifiers_'.$type})) {
      $data['identifiers'] = $this->{'identifiers_'.$type};
    } else {
      $data['identifiers'] = $this->identifiers_common;
    }
    
    if (in_array($type, array('attribute', 'filter'))) {
      $data['identifiers_product'] =  $this->identifiers_product;
    }
    
    if ($type == 'order_status' && $this->config->get('ordIdMan_rand_ord_num')) {
      $data['identifiers'][] = 'order_id_user';
    }
    
    // auto-detect item node (depth=1, repeated at least 2 times)
    if ($data['filetype'] == 'xml') {
      $xml = new XMLReader;
      //$xml->open($import_file, 'ISO-8859-1');
      $xml->open($import_file);
      
      $found = false;
      $prev_name = null;
      
      while ($xml->read() && !$found) {
        if ($xml->nodeType == XMLReader::ELEMENT && $xml->depth === 1) {
          if ($prev_name === $xml->name) {
            $found = $prev_name;
          }
          $prev_name = $xml->name;
        }
      }
      
      $data['xml_node'] = $found ? $found : 'product';
    }
    
    $data['sheets'] = array();
    
    if ($data['filetype'] == 'ods' || $data['filetype'] == 'xlsx') {
      $data['sheets'] = $this->getSheets();
    }
    
    // get example rows
    /*
    $csv = $this->getDataRows(2, true);
    
    $data['rows'] = &$csv;
    
    if ($data['filetype'] != 'xml') {
      if (!empty($this->request->post['csv_header'])) {
        $data['columns'] = array_shift($csv);
      } else {
        $data['columns'] = array_keys($csv[0]);
        foreach ($data['columns'] as &$col) {
          $col = $this->language->get('text_column') . '_' . $col;
        }
      }
    }
    */
    
    if (version_compare(VERSION, '3', '>=')) {
      $this->config->set('template_engine', 'template');
      $this->response->setOutput($this->load->view('module/universal_import_settings', $data));
    } else if (version_compare(VERSION, '2', '>=')) {
			$this->response->setOutput($this->load->view('module/universal_import_settings.tpl', $data));
		} else {
			$this->data = &$data;
			$this->template = 'module/universal_import_settings.tpl';
			$this->response->setOutput($this->render());
		}
  }
  
  public function import_step3() {
    $data['_language'] = $this->language;
		$data['_config'] = $this->config;
    
    $data['update'] = $update = strpos($this->request->post['import_type'], '_update');
    $data['type'] = $type = str_replace('_update', '', $this->request->post['import_type']);
    
    $data['filetype'] = !empty($this->request->post['import_filetype']) ? $this->request->post['import_filetype'] : strtolower(pathinfo($this->request->post['import_file'], PATHINFO_EXTENSION));
    $subtype = $data['subtype'] = !empty($this->request->post['import_subtype']) ? $this->request->post['import_subtype'] : '';
    
    if (in_array($type, array('product', 'category', 'information', 'manufacturer'))) {
      $this->language->load('catalog/'.$type);
    } else if ($type == 'order') {
      $this->language->load('sale/order');
    }

		$data['languages'] = $this->languages;
    
    // get installed modules
    if (version_compare(VERSION, '3', '>=')) {
      $this->load->model('setting/extension');
			$extension_model = $this->model_setting_extension;
    } else if (version_compare(VERSION, '2', '>=')) {
      $this->load->model('extension/extension');
			$extension_model = $this->model_extension_extension;
		} else {
			$this->load->model('setting/extension');
			$extension_model = $this->model_setting_extension;
		}
    
    $data['installed_modules'] = $extension_model->getInstalled('module');
    
    // product
    if ($type == 'product') {
      // vars
      $data['config_length_class_id'] = $this->config->get('config_length_class_id');
      $data['config_weight_class_id'] = $this->config->get('config_weight_class_id');
      
      // categories
      $this->load->model('catalog/category');
      $categories = $this->model_catalog_category->getCategories(array());
      
      $data['categories'][''] = '';
      foreach ($categories as $category) {
        $data['categories'][$category['category_id']] = $category['name'];
      }
      
      // manufacturers
      $this->load->model('catalog/manufacturer');
      $manufacturers = $this->model_catalog_manufacturer->getManufacturers();
      
      $data['manufacturers'][''] = '';
      foreach ($manufacturers as $manufacturer) {
        $data['manufacturers'][$manufacturer['manufacturer_id']] = $manufacturer['name'];
      }
      
      // tax classes
      $this->load->model('localisation/tax_class');
      $data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
      
      // stock statuses
      $this->load->model('localisation/stock_status');
      $data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

      // weight classes
      $this->load->model('localisation/weight_class');
      $data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

      // length classes
      $this->load->model('localisation/length_class');
      $data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();
      
      if ($update) {
        $this->load->model('catalog/option');
        $data['product_options'] = $this->model_catalog_option->getOptions();
      }
    } else if ($type == 'category') {
      // categories
      $this->load->model('catalog/category');
      $categories = $this->model_catalog_category->getCategories(array());
      
      $data['categories'][''] = '';
      foreach ($categories as $category) {
        $data['categories'][$category['category_id']] = $category['name'];
      }
    }
    
    if (in_array($type, array('customer', 'order'))) {
      $this->language->load('customer/customer');
      
      if (version_compare(VERSION, '2.1', '>=')) {
        $this->load->model('customer/customer_group');
        $customer_groups = $this->model_customer_customer_group->getCustomerGroups();
      } else {
        $this->load->model('sale/customer_group');
        $customer_groups = $this->model_sale_customer_group->getCustomerGroups();
      }
      
      foreach ($customer_groups as $cg) {
        $data['customer_groups'][$cg['customer_group_id']] = $cg['name'];
      }
      
      // Custom Fields
      $this->load->model('customer/custom_field');

      $data['custom_fields'] = array();

      $filter_data = array(
        'sort'  => 'cf.sort_order',
        'order' => 'ASC'
      );

      $custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);

      foreach ($custom_fields as $custom_field) {
        $data['custom_fields'][] = array(
          'custom_field_id'    => $custom_field['custom_field_id'],
          'custom_field_value' => $this->model_customer_custom_field->getCustomFieldValues($custom_field['custom_field_id']),
          'name'               => $custom_field['name'],
          'value'              => $custom_field['value'],
          'type'               => $custom_field['type'],
          'location'           => $custom_field['location'],
          'sort_order'         => $custom_field['sort_order']
        );
      }
      
    }

    if (in_array($type, array('order_status', 'order'))) {
      $this->load->model('localisation/order_status');
      $order_statuses = $this->model_localisation_order_status->getOrderStatuses();
      foreach ($order_statuses as $order_status) {
        $data['order_statuses'][$order_status['order_status_id']] = $order_status['name'];
      }
    }
    
    if (in_array($type, array('order'))) {
      $this->load->model('localisation/currency');
      $currencies = $this->model_localisation_currency->getCurrencies();
      
      foreach ($currencies as $currency) {
        $data['currencies'][$currency['code']] = $currency['code'];
      }
      
      // $data['installed_payments'] = $extension_model->getInstalled('payment');
      // $data['installed_shippings'] = $extension_model->getInstalled('shipping');
    }
    
    // stores
    $this->load->model('setting/store');
    $data['stores'] = array();
    $data['stores'][0] = $this->config->get('config_name');
    $stores = $this->model_setting_store->getStores();
    foreach ($stores as $store) {
      $data['stores'][$store['store_id']] = $store['name'];
    }
    
    // get example rows
    $data['profile'] = array();
    
    if ($data['filetype'] == 'xml') {
      $csv = $this->getDataRows(500, true);
      
      if (!empty($csv)) {
        //$data['columns'] = array_combine(array_keys($csv[0]), array_keys($csv[0]));
        // get nodes on some rows
        $data['columns'] = array();
        foreach ($csv as $row) {
          $data['columns'] = array_merge($data['columns'], array_combine(array_keys($row), array_keys($row)));
        }
        
        // auto-detect
        if (empty($this->request->post['profile'])) {
          $data['profile'] = array_merge($this->request->post, array('columns' => $data['columns']));
          
          foreach ($data['profile']['columns'] as $col => $idx) {
            if (in_array($col, array('name', 'description', 'meta_title', 'meta_description', 'meta_keyword', 'tag'))) {
              foreach ($this->languages as $language) {
                $data['profile']['columns'][$type.'_description'][$language['language_id']][$col] = $idx;
              }
            }
          }
        }
      } else {
        ?>
        <div class="spacer"></div>
        
        <div class="alert alert-danger"><?php echo $this->language->get('error_xml_no_data'); ?></div>
        
        <div class="pull-right">
          <button type="button" class="btn btn-default cancel" data-step="3"><i class="fa fa-reply"></i> <?php echo $this->language->get('text_previous_step'); ?></button>
        </div>
        
        <div class="spacer"></div>
        <?php
        exit;
      }
      
      $csv = $this->getDataRows(2, true);
    } else if (!empty($this->request->post['csv_header'])) {
      $csv = $this->getDataRows(2, true);
      $data['columns'] = array_shift($csv);
      
      /*
      foreach ($data['columns'] as $key => &$col) {
        $col .= ' (' . substr($csv[0][$key], 0, 20).(count($csv[0][$key])>20 ? '...':'').')';
      }
      */
      
      // auto-detect
      if (empty($this->request->post['profile'])) {
        foreach ($data['columns'] as $k => $col) {
          if (!$col) $data['columns'][$k] = 'column_' . ($k+1);
        }
        
        $data['profile'] = array_merge($this->request->post, array('columns' => array_flip($data['columns'])));
        
        foreach ($data['profile']['columns'] as $col => $idx) {
          if (in_array($col, array('name', 'description', 'meta_title', 'meta_description', 'meta_keyword', 'tag'))) {
            foreach ($this->languages as $language) {
              $data['profile']['columns'][$type.'_description'][$language['language_id']][$col] = $idx;
            }
          }
        }
      }
    } else {
      $csv = $this->getDataRows(2, true);
      $data['columns'] = array_keys($csv[0]);
      foreach ($data['columns'] as &$col) {
        $col = $this->language->get('text_column') . '_' . $col;
      }
    }
    
    // set profile
    if (!empty($this->request->post['profile'])) {
      $data['profile'] = include DIR_APPLICATION . 'view/universal_import/profiles/'. str_replace(array('/','\\'), '', $this->request->post['import_type']) .'/' . str_replace(array('/','\\'), '', $this->request->post['profile']) . '.cfg';
    }
    
    $data['profile'] = array_merge($data['profile'], $this->request->post);
    
    $data['profile']['filetype'] = $data['filetype'];
    
    $data['rows'] = &$csv;
    
    // for tpl call
    if ($update) {
      $type .= '_update';
    }
    
    $subtype = $subtype ? '_'.$subtype : '';
    
    if (version_compare(VERSION, '3', '>=')) {
      $this->config->set('template_engine', 'template');
			$this->response->setOutput($this->load->view('module/universal_import_'.$type.$subtype, $data));
    } else if (version_compare(VERSION, '2', '>=')) {
			$this->response->setOutput($this->load->view('module/universal_import_'.$type.$subtype.'.tpl', $data));
		} else {
			$this->data = &$data;
			$this->template = 'module/universal_import_'.$type.'.tpl';
			$this->response->setOutput($this->render());
		}
  }
  
  public function import_step4() {
    $data['_language'] = $this->language;
		$data['_config'] = $this->config;
    $data['token'] = $this->token;
    
    $data['update'] = $update = strpos($this->request->post['import_type'], '_update');
    $data['type'] = $type = str_replace('_update', '', $this->request->post['import_type']);
    $subtype = !empty($this->request->post['import_subtype']) ? '_'.$this->request->post['import_subtype'] : '';
    
    $data['filetype'] = !empty($this->request->post['import_filetype']) ? $this->request->post['import_filetype'] : strtolower(pathinfo($this->request->post['import_file'], PATHINFO_EXTENSION));
    
    $data['languages'] = $this->languages;
    
    // reset session data
    $this->session->data['obui_current_line'] = 0;
    
    $this->session->data['obui_identifiers'] = array();
    
    $this->session->data['obui_errors'] = array();
    
    $this->session->data['obui_log'] = array();
    
    $this->session->data['obui_processed'] = array(
      'processed' => 0,
      'inserted' => 0,
      'updated' => 0,
      'deleted' => 0,
      'skipped' => 0,
      'error' => 0,
    );
    
    if (in_array($type, array('product', 'category', 'information', 'manufacturer'))) {
      $this->language->load('catalog/'.$type);
    } else if ($type == 'customer') {
      $this->language->load((version_compare(VERSION, '2.1', '>=') ? 'customer':'sale').'/customer');
    }
    
    if (in_array($type, array('product', 'category', 'information', 'manufacturer'))) {
      $this->load->model('catalog/'.$type);
    } else if ($type == 'customer') {
      $this->load->model((version_compare(VERSION, '2.1', '>=') ? 'customer':'sale').'/customer');
      // $customer_model = 'model_'.(version_compare(VERSION, '2.1', '>=') ? 'customer':'sale').'_customer';
    } else if ($type == 'order_status') {
      $this->load->model('sale/order');
      $this->load->model('gkd_import/order');
    }
    
    if (isset($this->request->post['item_exists']) && $this->request->post['item_exists'] == 'soft_update') {
      $data['soft_update'] = true;
      $data['alert_info'] = $this->language->get('info_soft_update_mode');
    }
    
    // set profile
    if (!empty($this->request->post['profile'])) {
      $data['profile'] = include DIR_APPLICATION . 'view/universal_import/profiles/'. str_replace(array('/','\\'), '', $this->request->post['import_type']) .'/' . str_replace(array('/','\\'), '', $this->request->post['profile']) . '.cfg';
    }

    // get example rows
    $csv = $this->getDataRows(10);
    $data['rows'] = &$csv;
    
    if (!empty($this->request->post['csv_header'])) {
      $data['columns'] = array_shift($csv);
    } else {
      $data['columns'] = array_keys($csv[0]);
      foreach ($data['columns'] as &$col) {
        $col = $this->language->get('text_column') . '_' . $col;
      }
    }
    
    if ($update) {
      $type .= '_update';
    }
    
    // get data
    $data['simulate'] = array();
    foreach ($csv as &$row) {
      $this->session->data['obui_current_line']++;
      try {
        $data['simulate'][] = $this->model_tool_universal_import->{'process_' . $type . $subtype}($this->request->post, $row);
      } catch (Exception $e) {
        $this->session->data['obui_processed']['processed']++;
        $this->session->data['obui_processed']['error']++;
        $this->session->data['obui_errors'][] = $e->getMessage();
      }
    }
    
    foreach ($data['simulate'] as &$row) {
      if (empty($row['weight'])) {
        unset($row['weight_class_id']);
      }
      
      if (empty($row['width']) && empty($row['length']) && empty($row['height'])) {
        unset($row['length_class_id']);
      }
      
      foreach ($row as $key => &$val) {
        // tax classes
        if ($key === 'tax_class_id') {
          $this->load->model('localisation/tax_class');
          $res = $this->model_localisation_tax_class->getTaxClass($val);
          if (!empty($res['title'])) {
            $val = $res['title'];
          }
        }
        
        // stock statuses
        if ($key === 'stock_status_id') {
          $this->load->model('localisation/stock_status');
          $res = $this->model_localisation_stock_status->getStockStatus($val);
          if (!empty($res['name'])) {
            $val = $res['name'];
          }
        }
        
        // weight classes
        if ($key === 'weight_class_id') {
          $this->load->model('localisation/weight_class');
          $res = $this->model_localisation_weight_class->getWeightClass($val);
          if (!empty($res['title'])) {
            $val = $res['title'];
          }
        }
        
        // length classes
        if ($key === 'length_class_id') {
          $this->load->model('localisation/length_class');
          $res = $this->model_localisation_length_class->getLengthClass($val);
          if (!empty($res['title'])) {
            $val = $res['title'];
          }
        }
        
        // stores
        if ($key === 'product_store') {
          $this->load->model('setting/store');
          $stores = array();
          $stores[0] = $this->config->get('config_name');
          $res = $this->model_setting_store->getStores();
          foreach ($res as $store) {
            $stores[$store['store_id']] = $store['name'];
          }
          foreach ($val as &$store_id) {
            if (is_numeric($store_id) && isset($stores[$store_id])) {
              $store_id = $stores[$store_id];
            }
          }
        }
      }
    }
    
    foreach ($data['simulate'] as &$simu) {
      $simu = array_filter($simu, array($this, 'filterEmptyArrays'));
    }
    
    $data['errors'] = $this->session->data['obui_errors'];
    
    foreach ($this->session->data['obui_log'] as $error) {
      $data['errors'][] = '['.$this->language->get('text_row').' '.$error['row'].'] '.$error['title'].': '.$error['msg'];
    }
    
    $data['processed'] = $this->session->data['obui_processed'];
    
    if (!empty($this->session->data['univimport_temp_file'])) {
      $import_file = $this->session->data['univimport_temp_file'];
    } else if ($this->request->post['import_source'] == 'upload') {
      $import_file = DIR_CACHE.'universal_import/'.str_replace(array('../', '..\\'), '', $this->request->post['import_file']);
    } else if ($this->request->post['import_source'] == 'ftp') {
      $import_file = $this->request->post['import_ftp'].$this->request->post['import_file'];
    } else {
      $import_file = $this->request->post['import_file'];
    }
    
    $currentSheet = !empty($this->request->post['sheet']) ? (int) $this->request->post['sheet'] : 0;
    
    $summary['total_rows'] = $this->model_tool_universal_import->getTotalRows($import_file, !empty($this->request->post['csv_header']), !empty($this->request->post['xml_node']) ? $this->request->post['xml_node'] : '', $data['filetype'], $currentSheet);
    
    if (!empty($this->request->post['row_end']) && $this->request->post['row_end'] < $summary['total_rows']) {
      $summary['total_rows'] = !empty($this->request->post['csv_header']) ? $this->request->post['row_end']-1 : $this->request->post['row_end'];
    }
    
    $this->request->post['summary'] = $summary;
    $data['summary'] = &$summary;
    
    // reset session data
    $this->session->data['obui_current_line'] = 0;
    
    $this->session->data['obui_identifiers'] = array();
    
    $this->session->data['obui_errors'] = array();
    
    $this->session->data['obui_log'] = array();
    
    $this->session->data['obui_processed'] = array(
      'processed' => 0,
      'inserted' => 0,
      'updated' => 0,
      'deleted' => 0,
      'skipped' => 0,
      'error' => 0,
    );
    
    // save current settings for process
    file_put_contents(DIR_CACHE . 'univ_import_process.cfg', '<?php return ' . var_export($this->request->post, true) . ';');
    
    $this->session->data['obui_progress'] = 0;
    $this->session->data['obui_last_position'] = 0;
    
    if (version_compare(VERSION, '3', '>=')) {
      $this->config->set('template_engine', 'template');
      $this->response->setOutput($this->load->view('module/universal_import_check', $data));
    } else if (version_compare(VERSION, '2', '>=')) {
			$this->response->setOutput($this->load->view('module/universal_import_check.tpl', $data));
		} else {
			$this->data = &$data;
			$this->template = 'module/universal_import_check.tpl';
			$this->response->setOutput($this->render());
		}
  }
  
  public function import_step5() {
    $data['_language'] = $this->language;
		$data['_config'] = $this->config;
    $data['token'] = $this->token;
    $data['config'] = $this->request->post['import_file'];
    
    $data['type'] = $type = $this->request->post['import_type'];
    
    $data['demo_mode'] = !$this->user->hasPermission('modify', 'module/universal_import');
    
    if (in_array($type, array('product', 'category', 'information', 'manufacturer'))) {
      $this->language->load('catalog/'.$type);
    }
    
    $summary = array();
    
    if (!empty($this->session->data['univimport_temp_file'])) {
      $import_file = $this->session->data['univimport_temp_file'];
    } else if ($this->request->post['import_source'] == 'upload') {
      $import_file = DIR_CACHE.'universal_import/'.str_replace(array('../', '..\\'), '', $this->request->post['import_file']);
    } else if ($this->request->post['import_source'] == 'ftp') {
      $import_file = $this->request->post['import_ftp'].$this->request->post['import_file'];
    } else {
      $import_file = $this->request->post['import_file'];
    }
    
    $currentSheet = !empty($this->request->post['sheet']) ? (int) $this->request->post['sheet'] : 0;
    
    $summary['total_rows'] = $this->model_tool_universal_import->getTotalRows($import_file, !empty($this->request->post['csv_header']), !empty($this->request->post['xml_node']) ? $this->request->post['xml_node'] : '', $this->request->post['import_filetype'], $currentSheet);
    
    if (!empty($this->request->post['row_end']) && $this->request->post['row_end'] < $summary['total_rows']) {
      $summary['total_rows'] = !empty($this->request->post['csv_header']) ? $this->request->post['row_end']-1 : $this->request->post['row_end'];
    }
    
    $this->request->post['summary'] = $summary;
    $data['summary'] = &$summary;
    
    if (!empty($this->request->post['delete']) && !empty($this->request->post['delete_action']) && $this->request->post['delete_action'] == 'delete') {
      $data['warning_message'] = $this->language->get('warning_delete');
      //$data['delete'] = $this->request->post['delete'];
    }
    
    //$this->session->data['obui_current_line'] = empty($this->request->post['csv_header']) ? 0 : 1;
    $this->session->data['obui_current_line'] = 0;
    
    $this->session->data['obui_errors'] = array();
    
    $this->session->data['obui_log'] = array();
    
    $this->session->data['obui_processed'] = array(
      'processed' => 0,
      'inserted' => 0,
      'updated' => 0,
      'deleted' => 0,
      'skipped' => 0,
      'error' => 0,
    );
  
    // save current settings for process
    file_put_contents(DIR_CACHE . 'univ_import_process.cfg', '<?php return ' . var_export($this->request->post, true) . ';');
    
    $this->session->data['obui_progress'] = 0;
    $this->session->data['obui_last_position'] = 0;
    
    // set profile
    if (!empty($this->request->post['profile'])) {
      $data['profile'] = include DIR_APPLICATION . 'view/universal_import/profiles/'. str_replace(array('/','\\'), '', $this->request->post['import_type']) .'/' . str_replace(array('/','\\'), '', $this->request->post['profile']) . '.cfg';
    }
    
    if (empty($data['profile']['import_label'])) {
      $data['profile']['import_label'] = str_replace(array('[profile]', '[day]', '[month]', '[year]'), array(!empty($this->request->post['profile'])?$this->request->post['profile']:'Import', date('d'), date('m'), date('Y')), $this->config->get('gkd_impexp_default_label'));
    }
    
    if (version_compare(VERSION, '3', '>=')) {
      $this->config->set('template_engine', 'template');
      $this->response->setOutput($this->load->view('module/universal_import_proceed', $data));
    } else if (version_compare(VERSION, '2', '>=')) {
			$this->response->setOutput($this->load->view('module/universal_import_proceed.tpl', $data));
		} else {
			$this->data = &$data;
			$this->template = 'module/universal_import_proceed.tpl';
			$this->response->setOutput($this->render());
		}
  }
  
  public function delete_profile() {
    $asset_path = 'view/universal_import/';
    defined('_JEXEC') && $asset_path = 'admin/' . $asset_path;
    
    $import_type = str_replace('.', '', $this->request->post['import_type']);
    $profile = str_replace('.', '', $this->request->post['profile']);
    
    if (is_file(DIR_APPLICATION . $asset_path . 'profiles/' . $import_type . '/' . $profile . '.cfg')) {
      unlink(DIR_APPLICATION . $asset_path . 'profiles/' . $import_type . '/' . $profile . '.cfg');
    }
    
    echo json_encode(array('success'=> 1));
    
    exit;
  }
  
  public function process() {
    if (defined('GKD_CRON')) {
      if (is_file(DIR_APPLICATION . 'view/universal_import/profiles/' . $this->request->get['type'] . '/' . $this->request->get['profile'] . '.cfg')) {
        $config = include DIR_APPLICATION . 'view/universal_import/profiles/' . $this->request->get['type'] . '/' . $this->request->get['profile'] . '.cfg';
      } else {
        $this->model_tool_universal_import->cron_log('Profile not found: '.DIR_APPLICATION . 'view/universal_import/profiles/' . $this->request->get['type'] . '/' . $this->request->get['profile'] . '.cfg');
        die;
      }
      
      // copy remote file
      if ($config['import_source'] == 'url') {
        if (!file_exists(DIR_CACHE . 'universal_import')) {
          mkdir(DIR_CACHE . 'universal_import', 0755, true);
        }
        
        $import_filename = 'remote-'.uniqid().'.'.$config['import_filetype'];
        $import_file = DIR_CACHE.'universal_import/'.$import_filename;
        
        // copy remote file in temp file
        $localCopy = fopen($import_file, "w+");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, html_entity_decode($config['import_file'], ENT_QUOTES, 'UTF-8'));
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_FILE, $localCopy);
        
        $remoteFile = curl_exec($ch);
        curl_close($ch);
        fclose($localCopy);
        
        //$this->session->data['univimport_temp_file'] = $import_file;
        
        $config['import_source'] = 'upload';
        $config['import_file'] = $import_filename;
        //copy($this->request->post['import_file'], $import_file);
      }
    } else {
      $config = include DIR_CACHE . 'univ_import_process.cfg';
    }
    
    if (!empty($this->request->post['import_label'])) {
      $config['import_label'] = $this->request->post['import_label'];
    }
    
    $config['simulation'] = !empty($this->request->get['simu']);
    
    $postproc = false;
    //sleep(1);
    if (!empty($this->request->get['del'])) {
      $this->model_tool_universal_import->delete($config);
      $this->session->data['obui_progress'] = 100;
    } else {
      if (defined('GKD_CRON')) {
        $this->model_tool_universal_import->process($config, 9999999999);
      } else {
        $this->model_tool_universal_import->process($config);
      }
    }
    
    if (defined('GKD_CRON')) {
      //$this->model_tool_universal_import->cron_log('-------------------------------------------------------------' . PHP_EOL);
      
      if (!empty($config['delete']) && $config['delete'] != 'all') {
        $this->model_tool_universal_import->delete($config);
      }
      
      $this->model_tool_universal_import->cron_log(PHP_EOL . $this->language->get('entry_type').': ' . $this->language->get('text_type_'.$config['import_type']));
      $this->model_tool_universal_import->cron_log($this->language->get('text_file_loaded').' ' . $config['import_file']. PHP_EOL);
      
      $this->model_tool_universal_import->cron_log($this->language->get('text_rows_csv').': ' . $this->session->data['obui_processed']['processed']);
      foreach ($this->session->data['obui_processed'] as $item => $count) {
        if ($item != 'processed' && !empty($count)) {
          $this->model_tool_universal_import->cron_log('- ' . $this->language->get('text_rows_'.$item) . ': ' . $count);
        }
      }
      $this->model_tool_universal_import->cron_log(PHP_EOL . '> Process successfully terminated' . PHP_EOL);
      die;
    }
    // foreach($processed as $k => $v) {
      // if (array_key_exists($k, $this->session->data['obui_processed'])) {
        // $this->session->data['obui_processed'][$k] += $v;
      // }
    // }
    
    if ($config['summary']['total_rows'] > 0) {
      $this->session->data['obui_progress'] = round(($this->session->data['obui_processed']['processed'] / $config['summary']['total_rows']) * 100);
    } else {
      $this->session->data['obui_progress'] = 100;
    }
    
    if ($this->session->data['obui_progress'] >= 100 && !empty($config['delete'])) {
      if ($config['delete'] != 'all') {
        $postproc = true;
        $this->session->data['obui_progress'] = 99;
      }
    }
    
    // header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    // header("Cache-Control: post-check=0, pre-check=0", false);
    // header("Pragma: no-cache");

    echo json_encode(array(
      'success'=> 1,
      'finished'=> ($this->session->data['obui_processed']['processed'] == $config['summary']['total_rows'] && !$postproc),
      'processed' => $this->session->data['obui_processed'],
      'progress' => $this->session->data['obui_progress'],
      'postproc' => $postproc,
      //'errors' => $this->session->data['obui_errors'],
      'errors' => '',
      'log' => $this->session->data['obui_log'],
      ));
    exit;
  }
  
  public function postproc() {
    $config = include DIR_CACHE . 'univ_import_process.cfg';
   
    $config['simulation'] = !empty($this->request->get['simu']);

    $this->session->data['obui_errors'] = array();
    $this->session->data['obui_log'] = array();
    
    $this->model_tool_universal_import->delete($config);

    $this->session->data['obui_progress'] = round(($this->session->data['obui_processed']['processed'] / $config['summary']['total_rows']) * 100);

    echo json_encode(array(
      'success'=> 1,
      'finished'=> 1,
      'processed' => $this->session->data['obui_processed'],
      'progress' => $this->session->data['obui_progress'],
      'postproc' => false,
      //'errors' => $this->session->data['obui_errors'],
      'errors' => '',
      'log' => $this->session->data['obui_log'],
      ));
    exit;
  }
  
  public function get_option_fields() {
    // profile
    $profile = array();
    if (!empty($this->request->post['profile'])) {
      $profile = include DIR_APPLICATION . 'view/universal_import/profiles/'. str_replace(array('/','\\'), '', $this->request->post['import_type']) .'/' . str_replace(array('/','\\'), '', $this->request->post['profile']) . '.cfg';
    }
    
    $columns = (array) json_decode(base64_decode($this->request->post['column_headers']));
    
    $fieldSelector = '<select class="form-control" name="[source_column]">';
    $fieldSelector .= ' <option value="">'.$this->language->get('text_ignore').'</option>';
    foreach ($columns as $key => $row) {
      $fieldSelector .= '<option value="'.$key.'">'.$row.'</option>';
    }
    if (!empty($profile['extra_fields'])) {
      foreach (explode(',', $profile['extra_fields']) as $i => $extra_field_name) {
        $fieldSelector .= '<option value="__extra_field_'.$i.'">'.trim($extra_field_name).'</option>';
      }
    }
    $fieldSelector .= '</select>';
    
    /*
    $column_headers = json_decode(base64_decode($this->request->post['column_headers']));
    var_dump($column_headers);
    
    $csv = $this->getDataRows(1, true);
    //var_dump($csv);
    // get data
    $data['categories'] = array();
    */
    $output = '';
    
    //var_dump($this->request->post['columns']['product_option']);die;
    if (1) {
      foreach (array('type', 'name', 'value', 'image', 'price_prefix', 'price', 'quantity', 'subtract', 'weight', 'required') as $field) {
        $output .= '<tr>';
        $output .= '<td>Option '.str_replace('_', ' ', $field).'</td>';
        if ($this->request->post['import_filetype'] != 'xml') {
          $output .= '<td>';
          if (in_array($field, array('name', 'value'))) {
            foreach ($this->languages as $lang) {
              $output .= '<div class="input-group">';
              $output .= '<span class="input-group-addon"><img src="'.$lang['image'].'" title="'.$lang['name'].'"></span>';
              $output .= '<select class="form-control" name="option_fields['.$field.']['.$lang['language_id'].']">';
              $output .= ' <option value="">'.$this->language->get('text_ignore').'</option>';
              foreach ($columns as $key => $row) {
                if (isset($profile['option_fields'][$field][$lang['language_id']]) && $profile['option_fields'][$field][$lang['language_id']] !== '' && $profile['option_fields'][$field][$lang['language_id']] == $key) {
                  $output .= '<option value="'.$key.'" selected="selected">'.$row.'</option>';
                } else {
                  $output .= '<option value="'.$key.'">'.$row.'</option>';
                }
              }
              if (!empty($this->request->post['extra_fields'])) {
                foreach (explode(',', $this->request->post['extra_fields']) as $i => $extra_field_name) {
                  $output .= '<option value="__extra_field_'.$i.'">'.trim($extra_field_name).'</option>';
                }
              }
              $output .= '</select>';
              $output .= '</span>';
              $output .= '</div>';
            }
          } else {
            $output .= '<select class="form-control" name="option_fields['.$field.']">';
            $output .= ' <option value="">'.$this->language->get('text_ignore').'</option>';
            foreach ($columns as $key => $row) {
              if (isset($profile['option_fields'][$field]) && $profile['option_fields'][$field] !== '' && $profile['option_fields'][$field] == $key) {
                $output .= '<option value="'.$key.'" selected="selected">'.$row.'</option>';
              } else {
                $output .= '<option value="'.$key.'">'.$row.'</option>';
              }
            }
            if (!empty($this->request->post['extra_fields'])) {
              foreach (explode(',', $this->request->post['extra_fields']) as $i => $extra_field_name) {
                $output .= '<option value="__extra_field_'.$i.'">'.trim($extra_field_name).'</option>';
              }
            }
            $output .= '</select>';
            $output .= '</td>';
          }
        } else {
          $output .= '<td>';
          if (in_array($field, array('name', 'value'))) {
            foreach ($this->languages as $lang) {
              $output .= '<div class="input-group">';
              $output .= '<span class="input-group-addon"><img src="'.$lang['image'].'" title="'.$lang['name'].'"></span>';
              $output .= '<input type="text" name="option_fields['.$field.']['.$lang['language_id'].']" value="'.(isset($profile['option_fields'][$field][$lang['language_id']]) ? $profile['option_fields'][$field][$lang['language_id']]:'').'" class="form-control"/>';
              $output .= '</span>';
              $output .= '</div>';
            }
          } else {
            $output .= '<input type="text" name="option_fields['.$field.']" value="'.(isset($profile['option_fields'][$field]) ? $profile['option_fields'][$field]:'').'" class="form-control"/>';
          }
          $output .= '</td>';
        }
        
        $output .= '<td>';
        if ($field == 'type') {
          //$output .= '<td><input type="text" name="option_fields_default['.$field.']" value="" class="form-control"></td>';
          $output .= '<select name="option_fields_default['.$field.']" class="form-control">';
          foreach (array('select', 'radio', 'checkbox', 'text', 'textarea', 'file', 'data', 'time', 'datetime') as $col) {
            if (!empty($profile['option_fields_default'][$field]) && $profile['option_fields_default'][$field] == $col) {
              $output .= '<option value="'.$col.'" selected>'.ucfirst($col).'</option>';
            } else {
              $output .= '<option value="'.$col.'">'.ucfirst($col).'</option>';
            }
          }
          $output .= '</select>';
        } else if (in_array($field, array('name', 'value'))) {
          foreach ($this->languages as $lang) {
            $output .= '<div class="input-group">';
            $output .= '<span class="input-group-addon"><img src="'.$lang['image'].'" title="'.$lang['name'].'"></span>';
            $output .= '<input type="text" name="option_fields_default['.$field.']['.$lang['language_id'].']" value="'.(isset($profile['option_fields_default'][$field][$lang['language_id']]) ? $profile['option_fields_default'][$field][$lang['language_id']] : '').'" class="form-control"/>';
            $output .= '</span>';
            $output .= '</div>';
          }
        } else if (in_array($field, array('subtract', 'required'))) {
          $output .= '<select name="option_fields_default['.$field.']" class="form-control">';
          $output .= '<option value="">Disabled</option>';
          if (!empty($profile['option_fields_default'][$field])) {
            $output .= '<option value="1" selected>Enabled</option>';
          } else {
            $output .= '<option value="1">Enabled</option>';
          }
          $output .= '</select>';
        } else {
          $output .= '<input type="text" name="option_fields_default['.$field.']" value="'.(isset($profile['option_fields_default'][$field]) ? $profile['option_fields_default'][$field] : '').'" class="form-control"/>';
        }
        $output .= '</td>';
        /*
        $output .= '<td><select name="option_fields['.$field.']">';
        foreach ($columns as $col) {
          $output .= '<option value="'.$to.'" selected></option>';
        }
        $output .= '</select></td>';
        */
        $output .= '<td><button title="' . $this->language->get('text_remove_function') . '" type="button" data-toggle="tooltip" class="btn btn-danger remove-function"><i class="fa fa-minus-circle"></i></button></td>';
        $output .= '</tr>';
      }
    } else {
      $output = '<tr>';
      $output .= '<td colspan="3">No categories found, make sure you selected the good columns for categories.</td>';
      $output .= '</tr>'; 
    }
    
    $output .= '<tr><td colspan="3" style="text-align:center" class="form-inline"><button type="button" class="btn btn-success get-option-fields"><i class="fa fa-refresh"></i> ' . $this->language->get('text_get_optbinding') . '</button></td></tr>';
    echo $output;
    exit;
  }
  
  public function get_bindings() {
    // profile
    $profile = array();
    if (!empty($this->request->post['profile'])) {
      $profile = include DIR_APPLICATION . 'view/universal_import/profiles/'. str_replace(array('/','\\'), '', $this->request->post['import_type']) .'/' . str_replace(array('/','\\'), '', $this->request->post['profile']) . '.cfg';
    }
    
    // get data
    $data['categories'] = array();
    
    $output = '';
    
    $categories = $this->model_tool_universal_import->getFeedCategories();
    
    if (count($categories)) {
      foreach ($categories as $from => $to) {
        $output .= '<tr>';
        $output .= '<td><input type="hidden" name="col_binding_names['.md5($from).']" value="'.htmlspecialchars($from, ENT_QUOTES, 'UTF-8').'"/>'.$from.'</td>';
        $output .= '<td><select name="col_binding['.md5($from).'][]" class="catBindSelect" multiple>';
        if (!empty($profile['col_binding'][md5($from)])) {
          foreach((array) $to as $val) {
            $output .= '<option value="'.$val.'" selected></option>';
          }
        }
        $output .= '</select></td>';
        $output .= '<td><button title="' . $this->language->get('text_remove_function') . '" type="button" data-toggle="tooltip" class="btn btn-danger remove-function"><i class="fa fa-minus-circle"></i></button></td>';
        $output .= '</tr>';
      }
    } else {
      $output = '<tr>';
      $output .= '<td colspan="3">No categories found, make sure you selected the good columns for categories.</td>';
      $output .= '</tr>'; 
    }
    
    $output .= '<tr><td colspan="3" style="text-align:center" class="form-inline"><button type="button" class="btn btn-success get-bindings"><i class="fa fa-refresh"></i> ' . $this->language->get('text_get_bindings') . '</button></td></tr>';
    echo $output;
    exit;
  }
  
  protected function filterEmptyArrays($val) {
    return is_numeric($val) || (is_array($val) && !empty($val)) || !empty($val);
  }
  
  protected function getSheets() {
    $extension = !empty($this->request->post['import_filetype']) ? $this->request->post['import_filetype'] : strtolower(pathinfo($this->request->post['import_file'], PATHINFO_EXTENSION));
    
    if (!empty($this->session->data['univimport_temp_file'])) {
      $import_file = $this->session->data['univimport_temp_file'];
    } else if ($this->request->post['import_source'] == 'upload') {
      $import_file = DIR_CACHE.'universal_import/'.str_replace(array('../', '..\\'), '', $this->request->post['import_file']);
    } else if ($this->request->post['import_source'] == 'ftp') {
      $import_file = $this->request->post['import_ftp'].$this->request->post['import_file'];
    } else {
      $import_file = $this->request->post['import_file'];
    }
    
    $sheets = array();
    
    if ($extension == 'ods' || $extension == 'xlsx') { // Spout
      require_once DIR_SYSTEM.'library/Spout/Autoloader/autoload.php';
      
      libxml_disable_entity_loader(false);
      
      if ($extension == 'xlsx') {
        $reader = ReaderFactory::create(Type::XLSX);
      } else if ($extension == 'ods') {
        $reader = ReaderFactory::create(Type::ODS);
      }
      //$reader = ReaderFactory::create(Type::CSV);
      
      $reader->setShouldFormatDates(true);

      $reader->open($import_file);

      foreach ($reader->getSheetIterator() as $sheet) {
        $sheets[$sheet->getIndex()] = $sheet->getName();
      }
      
      $reader->close();
    }
    
    return $sheets;
  }
  
	protected function getDataRows($limit = false, $cutted = false) {
    if ($limit && !empty($this->request->post['csv_header'])) {
      $limit++;
    }
    
    $rows = array();
    $i = 0;
    
    $extension = !empty($this->request->post['import_filetype']) ? $this->request->post['import_filetype'] : strtolower(pathinfo($this->request->post['import_file'], PATHINFO_EXTENSION));
    $currentSheet = !empty($this->request->post['sheet']) ? (int) $this->request->post['sheet'] : 0;
    
    if (!empty($this->session->data['univimport_temp_file'])) {
      $import_file = $this->session->data['univimport_temp_file'];
    } else if ($this->request->post['import_source'] == 'upload') {
      $import_file = DIR_CACHE.'universal_import/'.str_replace(array('../', '..\\'), '', $this->request->post['import_file']);
    } else if ($this->request->post['import_source'] == 'ftp') {
      $import_file = $this->request->post['import_ftp'].$this->request->post['import_file'];
    } else {
      $import_file = $this->request->post['import_file'];
    }
    
    if ($extension == 'csv') {
      $separator = !empty($this->request->post['csv_separator']) ? $this->request->post['csv_separator'] : ',';
      
      $file = fopen($import_file, 'r');
      
      if ($file) {
        while (!feof($file) && $i < $limit) {
          if ($line = trim(fgets($file))) {
            if ($cutted) {
              $rows[] = array_map(array($this, 'limitText'), str_getcsv($line, $separator));
            } else {
              $rows[] = str_getcsv($line, $separator);
            }
            $i++;
          }
        }

        fclose($file);
      } else {
        // error opening the file.
      }
    } else if ($extension == 'xml') {
      $xml = new XMLReader;
      $xml->open($import_file);

      //$doc = new DOMDocument;
      
      $rows = array();
      $i = 0;
      
      $nodeName = $this->request->post['xml_node'];
      // find the node name
      while ($xml->read() && $xml->name !== $nodeName);

      // now that we're at the right depth, hop to the next <product/> until the end of the tree
      while ($xml->name === $nodeName && $i < $limit) {
        $node = new SimpleXMLElement($xml->readOuterXML()); // other method to get data
        //$node = simplexml_import_dom($doc->importNode($xml->expand(), true));
        
        if ($cutted) {
          $rows[] = array_map(array($this, 'limitText'), $this->model_tool_universal_import->XML2Array($node));
        } else {
          $rows[] = $this->model_tool_universal_import->XML2Array($node);
        }
        
        // go to next node
        $xml->next($nodeName);
        $i++;
      }
    } else if ($extension == 'ods' || $extension == 'xlsx') { // Spout
      require_once DIR_SYSTEM.'library/Spout/Autoloader/autoload.php';
      
      libxml_disable_entity_loader(false);
      
      if ($extension == 'xlsx') {
        $reader = ReaderFactory::create(Type::XLSX);
      } else if ($extension == 'ods') {
        $reader = ReaderFactory::create(Type::ODS);
      }
      //$reader = ReaderFactory::create(Type::CSV);
      
      $reader->setShouldFormatDates(true);

      $reader->open($import_file);

      foreach ($reader->getSheetIterator() as $sheet) {
        if ($sheet->getIndex() === (int) $currentSheet) {
          foreach ($sheet->getRowIterator() as $line) {
            if ($cutted) {
              $rows[] = array_map(array($this, 'limitText'), $line);
            } else {
              $rows[] = $line;
            }
            
            if (++$i >= $limit) {
              break;
            }
          }
        }
      }
      
      $reader->close();
     } else if ($extension == 'json') {
      require_once DIR_SYSTEM.'library/JsonReader/JsonReader.php';
      
      $reader = new pcrov\JsonReader\JsonReader();
      $reader->open($import_file);

      $values = array();
      
      while ($reader->read()) {
        if ($reader->depth()) {
          $values[$reader->name()] = $reader->value();
        } else if ($values) {
          $rows[] = $values;
        }
      }

      $reader->close();
    } else if ($extension == 'xls') {
      // PHPExcel
      require_once(DIR_SYSTEM.'library/PHPExcel/PHPExcel.php');
      /* to try for better perf:
      $objReader = PHPExcel_IOFactory::createReader('Excel2007');
      $objReader->setReadDataOnly(true);
      $objReader->load($import_file);
      */
      $objPHPExcel = PHPExcel_IOFactory::load($import_file);
      
      $sheet = $objPHPExcel->getSheet(0);
      $highestRow = $sheet->getHighestRow();
      $highestColumn = $sheet->getHighestColumn();

      if ($highestRow < $limit) {
        $limit = $highestRow;
      }
      $rows = array();
      
      $pop = false;
      
      for ($row = 1; $row <= $limit; $row++) {
        $arrRow = $row-1;
        $resrow = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, false, false);

        if ($cutted) {
          $rows[$arrRow] = array_map(array($this, 'limitText'), $resrow[0]);
        } else {
          $rows[$arrRow] = $resrow[0];
        }
        
        // pop last element if null
        if ($arrRow === 0 && !empty($this->request->post['csv_header']) && is_null(end($rows[$arrRow]))) {
          $pop = true;
        }
        
        if ($pop) {
          array_pop($rows[$arrRow]);
        }
      }
    }
    
    return $rows;
  }
  
	private function limitText($val) {
    if (is_string($val) && strlen($val) > 250) {
      return substr(strip_tags($val), 0, 250) . '[...]';
    }
    return $val;
  }
  /*
	protected function get_csv($limit = false) {
    if ($limit && !empty($this->request->post['csv_header'])) {
      $limit++;
    }
    $i = 0;
    
    $separator = !empty($this->request->post['csv_separator']) ? $this->request->post['csv_separator'] : ',';
    
    $file = fopen(DIR_CACHE.'universal_import/'.str_replace('../', '', $this->request->post['import_file']), 'r');
    $csv = array();
    if ($file) {
      while (!feof($file) && $i < $limit) {
        if ($line = trim(fgets($file))) {
          $csv[] = str_getcsv($line, $separator);
          $i++;
        }
      }

      fclose($file);
    } else {
      // error opening the file.
    }
    
    return $csv;
  }
  */
  
  // Export
  public function export_count() {
    $default_config = array(
      'export_format' => 'xml',
      'display_quantity' => 0,
      'cache_delay' => 0,
      'cache_unit' => 'minute',
      'language' => '',
    );
    
    $config = $this->config->get('univfeed_feeds');
    
    $config = array_merge($default_config, $this->request->post);
    
    // load driver
    $this->load->model('gkd_export/driver_'.$config['export_type']);
    
    // load processor
    if (in_array($config['export_format'], array('xlsx', 'ods'))) {
      $this->load->model('gkd_export/processor_spout');
      $processor = $this->{'model_gkd_export_processor_spout'};
    } else if (in_array($config['export_format'], array('xls', 'html', 'pdf'))) {
      $this->load->model('gkd_export/processor_phpexcel');
      $processor = $this->{'model_gkd_export_processor_phpexcel'};
    } else {
      $this->load->model('gkd_export/processor_'.$config['export_format']);
      $processor = $this->{'model_gkd_export_processor_'.$config['export_format']};
    }
    
    if(empty($config['filter-start'])) $config['filter-start'] = 0;
    if(empty($config['filter-limit'])) $config['filter-limit'] = '';
    
    $total = $processor->getTotalItems($config);
    
    $total = $total - $config['filter-start'];
    
    $total = ($config['filter-limit'] > 0 && $total > $config['filter-limit']) ? $config['filter-limit'] : $total;
    $total = ($total < 0) ? 0 : $total;
    
    echo $total;
  }
  
  public function export_form() {
    $data['_language'] = $this->language;
		$data['_config'] = $this->config;
		$data['token'] = $this->token;
    
    $data['format'] = $format = $this->request->post['export_format'];
    $data['type'] = $type = str_replace('..', '', $this->request->post['export_type']);
    
    // Params data
      # languages
      $data['languages'] = $this->languages;
      
      # stores
      $this->load->model('setting/store');
      $data['stores'] = array();
      $data['stores'][] = array(
        'store_id' => 0,
        'name'     => $this->config->get('config_name')
      );

      $stores = $this->model_setting_store->getStores();

      foreach ($stores as $store) {
        $action = array();

        $data['stores'][] = array(
          'store_id' => $store['store_id'],
          'name'     => $store['name']
        );
      }
      
      if ($type == 'product') {
        // categories
        $this->load->model('catalog/category');
        $data['categories'] = $this->model_catalog_category->getCategories(array());
        
        // manufacturers
        $this->load->model('catalog/manufacturer');
        $data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();
      } else if ($type == 'order') {
        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
      }
     
    
    if (version_compare(VERSION, '3', '>=')) {
      $this->config->set('template_engine', 'template');
			$this->response->setOutput($this->load->view('module/universal_export_'.$type, $data));
    } else if (version_compare(VERSION, '2', '>=')) {
			$this->response->setOutput($this->load->view('module/universal_export_'.$type.'.tpl', $data));
		} else {
			$this->data = &$data;
			$this->template = 'module/universal_export_'.$type.'.tpl';
			$this->response->setOutput($this->render());
		}
  }
  
  private $final_file, $temp_file;
  
  public function process_export() {
    //sleep(1);
    ini_set('memory_limit', -1);
    $this->start_time = microtime(true)*1000;

    if (in_array($this->request->post['export_format'], array('csv', 'xml', 'xls', 'xlsx', 'ods', 'html', 'pdf'))) {
      $data['format'] = $format = $this->request->post['export_format'];
    } else {
      $data['format'] = $format = 'csv';
    }
    
    $data['type'] = $type = str_replace('..', '', $this->request->post['export_type']);
    
    if (defined('GKD_CRON')) {
      $this->model_tool_universal_import->cron_log('Export type: '.$type. PHP_EOL);
    }
      
    $default_config = array(
      'export_format' => 'xml',
      'display_quantity' => 0,
      'cache_delay' => 0,
      'cache_unit' => 'minute',
      'language' => '',
    );
    
    $config = $this->config->get('univfeed_feeds');
    
    $config = array_merge($default_config, $this->request->post);

    $save_path = DIR_CACHE . 'export/';
  
    if (!is_dir($save_path)) {
      mkdir($save_path);
    }
    
    if (!is_writable($save_path)) {
      if (defined('GKD_CRON')) {
        $this->model_tool_universal_import->cron_log('The directory '.$save_path.' is not writable, make sur the directory exists and it have sufficient rights'. PHP_EOL);
      }
      die('The directory '.$save_path.' is not writable, make sur the directory exists and it have sufficient rights');
    }
    
    $filename = $type . '.' . $format;
    $filepath = $save_path . $type . '.' . $format;
    //$this->temp_file = DIR_CACHE . $type . '.tmp';
    
    // load driver
    $this->load->model('gkd_export/driver_'.$config['export_type']);
    
    // load processor
    if (in_array($config['export_format'], array('xlsx', 'ods'))) {
      $this->load->model('gkd_export/processor_spout');
      $processor = $this->{'model_gkd_export_processor_spout'};
    } else if (in_array($config['export_format'], array('xls', 'html', 'pdf'))) {
      $this->load->model('gkd_export/processor_phpexcel');
      $processor = $this->{'model_gkd_export_processor_phpexcel'};
    } else {
      $this->load->model('gkd_export/processor_'.$config['export_format']);
      $processor = $this->{'model_gkd_export_processor_'.$config['export_format']};
    }
    
    if (!empty($config['language'])) {
      $this->config->set('config_language_id', $config['language']);
    }
    
    $config['price_modifier'] = 1;
    $config['currency'] = 'EUR';
    
    
    $params = array();
    if (!empty($this->request->get['start'])) {
      // sleep(1);
      $total_items = $processor->getTotalItems($config);
      
      $init = ($this->request->get['start'] == 'init') ? true : false;
      
      $config['start'] = (int) $this->request->get['start'];
      
      $filter_start = !empty($this->request->post['filter-start']) ? (int) $this->request->post['filter-start'] : 0;
      $filter_limit = !empty($this->request->post['filter-limit']) ? (int) $this->request->post['filter-limit'] : 0;
      
      if ($init and $filter_start) {
        $config['start'] = $filter_start;
      }
      
      if (defined('GKD_CRON')) {
        $config['limit'] = 9999999999;
      } else {
        $config['limit'] = 200;
      
        if ((int) $this->config->get('gkd_impexp_batch_exp') > 0) {
          $config['limit'] = (int) $this->config->get('gkd_impexp_batch_exp');
        }
      }

      if ($filter_limit) {
        //$total_items = ($total_items > $filter_limit) ? $filter_limit : $total_items;
        $total_items = ($total_items > $filter_start + $filter_limit) ? $filter_start + $filter_limit : $total_items;
        
        if (($config['start'] + $config['limit'] - $filter_start) > $filter_limit) {
          $config['limit'] = $filter_limit - ($config['start'] - $filter_start);
          //$config['limit'] = ($config['start'] + $filter_limit) - $filter_start;
        }
        // if (($filter_start + $config['limit']) > $filter_limit) {
          // $config['limit'] = ($config['start'] + $filter_limit) - $filter_start;
        // }
        
      }
      
      $fh = $processor->getFile($filepath, $init);
      
      if ($init) {
        $processor->writeHeader($fh, $config);
      }
      
      $processor->writeBody($fh, $config);
      
      $processed = $config['start'] + $config['limit'];
      
      if ($processed > $total_items) {
        $processed = $total_items;
      }
      
      if ($processed >= $total_items) {
        $processor->writeFooter($fh);
      }
      
      $processor->closeFile($fh);
      
      if ($total_items == 0) {
        $progress = 100;
      } else {
        $progress = round(($processed / $total_items) * 100);
      }
    
      if (defined('GKD_CRON')) {
        if (isset($this->request->get['email'])) {
          $mail = new Mail();
          $mail->protocol = $this->config->get('config_mail_protocol');
          $mail->parameter = $this->config->get('config_mail_parameter');
          $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
          $mail->smtp_username = $this->config->get('config_mail_smtp_username');
          $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
          $mail->smtp_port = $this->config->get('config_mail_smtp_port');
          $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
    
          $mail->setTo($this->request->get['email']);
          $mail->setFrom($this->config->get('config_email'));
          $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
          $mail->setSubject('Export '.$type);
          $mail->setText('Export '.$type.' - '.date('d/m/Y'));
          $mail->addAttachment($filepath);
          $mail->send();
          echo 'Process complete - email sent to ' . $this->request->get['email'];
        } else {
          $final_file = realpath(DIR_CATALOG.'../export').'/'.$type.'-'.date('Y-m-d-H-i').'.'.$data['format'];
          
          if (!is_dir(DIR_CATALOG.'../export')) {
            mkdir(DIR_CATALOG.'../export', 0755);
          }
          
          if (!is_writable(DIR_CATALOG.'../export')) {
            echo realpath(DIR_CATALOG.'../export'). ' is not writable, make sure to set sufficient rights on this folder.'; die;
          }
          
          rename($filepath, $final_file);
          echo 'Process complete - file saved as ' . $final_file;
          $this->model_tool_universal_import->cron_log('Process complete - Exported ' . $processed . ' items - File saved as ' . $final_file . PHP_EOL);
        }
        
        die;
      } 
      
      echo json_encode(array(
        'success'=> 1,
        'processed' => $processed,
        'progress' => $progress,
        'finished' => $processed >= $total_items,
        'file' => $type . '.' . $format,
        //'mem' => memory_get_usage()
      ));
      
    } /*
    else {
      $config['start'] = 0;
      $config['limit'] = 99999999;
    
      $fh = fopen($this->temp_file, 'w');
      
      $processor->writeHeader($fh, $config);
      $processor->writeBody($fh, $config, $params);
      $processor->writeFooter($fh);
      
      fclose($fh);
    
      //rename($this->temp_file, $this->final_file);
      
      $this->display();
    }
    */
	}
  
  public function get_export() {
    $file = DIR_CACHE . 'export/' . str_replace('..', '', $this->request->get['file']);
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    
    /*
    if ($ext == 'csv') {
      header('Content-type: text/csv');
    } else {
      header('Content-Type: application/'.$ext);
    }
    */
    
    header('Content-Type: application/octet-stream');
    
    header('Content-disposition: attachment; filename="' . basename($file) . '"');
    
    header('Cache-Control: must-revalidate');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
  }
  
	protected static function array_filter_recursive($array) {
    foreach ($array as &$value) {
      if (is_array($value)) {
        $value = self::array_filter_recursive($value);
      }
    }
   
    return array_filter($array, 'self::filter_condition');
  }
  
  protected static function filter_condition($item) {
    return is_array($item) || strlen($item);
  }
  
  public function cron($params = '') {
    $this->session->data['obui_current_line'] = 0;
    
    $this->session->data['obui_errors'] = array();
    
    $this->session->data['obui_log'] = array();
    
    $this->session->data['obui_processed'] = array(
      'processed' => 0,
      'inserted' => 0,
      'updated' => 0,
      'deleted' => 0,
      'skipped' => 0,
      'error' => 0,
    );
    
    $this->session->data['obui_progress'] = 0;
    $this->session->data['obui_last_position'] = 0;
    
    $this->model_tool_universal_import->cron_log(PHP_EOL . '##### Cron Request - ' . (isset($this->request->get['export']) ? 'EXPORT' : 'IMPORT') . ' - ' . date('d/m/Y H:i:s') . ' #####'.PHP_EOL);
    
    // basic checks
    if (!isset($this->request->get['k'])) {
      $this->model_tool_universal_import->cron_log('Missing secure key parameter.');
      echo 'Invalid feed format, check logs for details';
      die;
    }
    
    if ($this->request->get['k'] !== $this->config->get(self::PREFIX.'_cron_key')) {
      $this->model_tool_universal_import->cron_log('Incorrect secure key, process aborted. Input key:' . $this->request->get['k']);
      echo 'Invalid feed format, check logs for details';
      die;
    }
    
    if (!isset($this->request->get['type'])) {
      $this->request->get['type'] = 'product';
    }
    
    if (!in_array($this->request->get['type'], $this->import_types)) {
      $this->model_tool_universal_import->cron_log('Incorrect type.');
      echo 'Invalid feed format, check logs for details';
      die;
    }
    
    if (isset($this->request->get['label'])) {
      $this->request->post['import_label'] = $this->request->get['label'];
    }
    
    if (isset($this->request->get['export'])) {
      $defaults = array(
        'export_format' => 'csv',
        'export_type' => $this->request->get['type'],
        'filter_language' => '',
        'filter_store' => '',
        'filter_category' => array(),
        'filter_manufacturer' => array(),
        'param_image_path' => '',
      );
      
      $this->request->get['start'] = 'init';
      
      $this->request->post = array_merge($defaults, $this->request->get);
      
      $this->process_export();
    } else {
      if (!isset($this->request->get['profile'])) {
        $this->model_tool_universal_import->cron_log('Missing profile parameter.');
        echo 'Invalid feed format, check logs for details';
        die;
      }
      
      $this->process();
    }
  }
  
  function readLogFile($filename, $lines, $revers = false){
    $offset = -1;
    $c = '';
    $read = '';
    $i = 0;
    $fp = @fopen($filename, "r");
    
    while( $lines && fseek($fp, $offset, SEEK_END) >= 0 ) {
      $c = fgetc($fp);
      if($c == "\n" || $c == "\r"){
        $lines--;
        if( $revers ){
          $read[$i] = strrev($read[$i]);
          $i++;
        }
      }
      if( $revers ) $read[$i] .= $c;
      else $read .= $c;
      $offset--;
    }
    
    fclose ($fp);
    
    if($revers){
      if($read[$i] == "\n" || $read[$i] == "\r")
        array_pop($read);
      else $read[$i] = strrev($read[$i]);
      return implode('',$read);
    }
    
    return strrev(rtrim($read,"\n\r"));
  }

  private function getHeaderFileType($url) {
    $headers = @get_headers($url, true);
    
    if (!$headers) {
      return '';
    }
    
    $headers = array_combine(array_map('strtolower', array_keys($headers)), $headers);

    $filename = isset($headers['content-disposition']) ? strstr($headers['content-disposition'], "=") : null ;
    $filename = trim($filename, "=\"'");
    
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
  }
  
  /*
  private function downloadFile($url) {
    $filetype = strtolower(pathinfo($url, PATHINFO_EXTENSION));
    
    if (!in_array($filetype, array('csv', 'xml', 'xls', 'xlsx', 'json', 'ods'))) {
      $headers = get_headers($url, true);
      $headers = array_combine(array_map('strtolower', array_keys($headers)), $headers);

      $filename = isset($headers['content-disposition']) ? strstr($headers['content-disposition'], "=") : null ;
      $filename = trim($filename, "=\"'");
      
      $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
      $filename = strtolower(pathinfo($filename, PATHINFO_FILENAME));
      
      if (!in_array($filetype, array('csv', 'xml', 'xls', 'xlsx', 'json', 'ods'))) {
        die('Incorrect file type');
      }
    }
    
    if (!file_exists(DIR_CACHE . 'universal_import')) {
      mkdir(DIR_CACHE . 'universal_import', 0755, true);
    }
    
    if (isset($filename)) {
      $cacheFilename = DIR_CACHE . 'universal_import/'.$filename.'-'.time().'.'.$filetype;
    } else {
      $cacheFilename = DIR_CACHE . 'universal_import/download-'.time().'.'.$filetype;
    }
    
    $remoteFile = fopen($url, 'rb');

    if ($remoteFile) {
      $cacheFile = fopen($cacheFilename, 'wb');
      
      if ($cacheFile) {
        while (!feof($remoteFile)) {
          fwrite($cacheFile, fread($remoteFile, 1024 * 8), 1024 * 8);
        }
      }
    }
    
    if ($remoteFile) {
      fclose($remoteFile);
    }
    
    if ($cacheFile) {
      fclose($cacheFile);
    }
    
    return pathinfo($cacheFilename, PATHINFO_BASENAME);
  }
  */

  public function save_cli_log() {
    $file = DIR_LOGS.'universal_import_cron.log';
    header('Content-Description: File Transfer');
    header('Content-Disposition: attachment; filename=seo_package_cron.log');
    header('Content-Type: text/plain');
    header('Cache-Control: must-revalidate');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
  }
  
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/universal_import')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
      $this->session->data['error'] = $this->error['warning'];
			return false;
		}	
	}
  
  public function db_tables() {
    if (!$this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "product` LIKE 'import_batch'")->row)
      $this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `import_batch` VARCHAR(64) NULL");
  }
  
  public function install($redir = false) {
    // rights
    $this->load->model('user/user_group');
    
    $this->model_user_user_group->addPermission(version_compare(VERSION, '2.0.2', '>=') ? $this->user->getGroupId() : 1, 'access', 'module/' . self::MODULE);
    $this->model_user_user_group->addPermission(version_compare(VERSION, '2.0.2', '>=') ? $this->user->getGroupId() : 1, 'modify', 'module/' . self::MODULE);
    
    // settings
		$this->load->model('setting/setting');
		/*
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();
		
		$ml_settings = array();
		foreach($languages as $language)
		{
			$ml_settings['pdf_invoice_filename_'.$language['language_id']] = 'Invoice';
		}
    */
		
    /*
		$this->model_setting_setting->editSetting('univimport', array(
			//'univimport_layout' => 'simple_clean',
    ));
    */
    
    if ($redir || !empty($this->request->get['redir'])) {
      if (version_compare(VERSION, '2', '>=')) {
				$this->response->redirect($this->url->link('module/'.self::MODULE, $this->token, 'SSL'));
			} else {
				$this->redirect($this->url->link('module/'.self::MODULE, $this->token, 'SSL'));
			}
    }
	}
}
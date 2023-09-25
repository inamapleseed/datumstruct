<?php

/**
 * HTML Code
 * 
 * @author info@ocdemo.eu
 */
class ControllerExtensionModuleHtmlCode extends Controller {	
	
	private $_name	  = 'html_code';
	
	private $_version = '2.5';
	
	private $data = array();
	
	private function _messages() {
		/**
		 * Alerts
		 */
		if( isset( $this->session->data['error'] ) ) {
			$this->data['error_warning'] = $this->session->data['error'];

			unset( $this->session->data['error'] );
		} else if( empty( $this->data['error_warning'] ) ) {
			$this->data['error_warning'] = '';
		}

		/**
		 * Messages
		 */
		if( isset( $this->session->data['success'] ) ) {
			$this->data['success'] = $this->session->data['success'];

			unset( $this->session->data['success'] );
		} else if( empty( $this->data['success'] ) ) {
			$this->data['success'] = '';
		}
	}
	
	/**
	 * __construct()
	 * 
	 * @param type $registry
	 */
	public function __construct($registry) {
		parent::__construct($registry);
		
		$id = isset($this->request->get['module_id']) ? '&module_id='.$this->request->get['module_id'] : null;
		
		$this->data = array_merge($this->data, $this->load->language('extension/module/' . $this->_name));
		
		$this->document->setTitle($this->language->get('heading_title'));

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
			'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], true),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/module/' . $this->_name, 'token=' . $this->session->data['token'], true),
      		'separator' => ' :: '
   		);
		
		$this->data['tab_action_main'] = $this->url->link('extension/module/' . $this->_name, 'token=' . $this->session->data['token'].$id, true);
		$this->data['tab_action_settings'] = $this->url->link('extension/module/' . $this->_name. '/settings', 'token=' . $this->session->data['token'].$id, true);
		$this->data['tab_action_about'] = $this->url->link('extension/module/' . $this->_name. '/about', 'token=' . $this->session->data['token'].$id, true);		
		
		$this->data['back'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);
		
		$this->_messages();
	}
	
	public function index() {
		$this->load->model('setting/setting');
		$this->load->model('localisation/language');
		$this->load->model('extension/module');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		$mod_id = isset($this->request->get['module_id']) ? $this->request->get['module_id'] : null;
		
		
		if( ! $this->config->get( 'html_code_layout_i' ) && ! $this->config->get( 'html_code_layout_c' ) )
			$this->_setDefaultSettings();
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			if( ! isset( $this->request->post['html_code_module'] ) )
				$this->request->post['html_code_module'] = array();
			
			if( ! empty( $this->request->post['html_code_module'] ) ) {
							
					if( empty( $this->request->post['html_code_module']['layout_id'] ) ) {
						$this->request->post['html_code_module']['layout_id'] = array();
					}
					
					if( ! isset( $this->request->post['html_code_module']['store_id'] ) ) {
						$this->request->post['html_code_module']['store_id'] = array('0');
					}
					
					if( ! in_array( $this->config->get('html_code_layout_i'), $this->request->post['html_code_module']['layout_id'] ) ) {
						$this->request->post['html_code_module']['information_id'] = array();
					}
						
					if( ! in_array( $this->config->get('html_code_layout_c'), $this->request->post['html_code_module']['layout_id'] ) ) {
						$this->request->post['html_code_module']['category_id'] = array();
					}
						
					if( ! in_array( $this->config->get('html_code_layout_p')?$this->config->get('html_code_layout_p'):'2', $this->request->post['html_code_module']['layout_id'] ) ) {
						$this->request->post['html_code_module']['product_id'] = array();
					}
					
					$this->request->post['html_code_module']['time'] = time();
					$this->request->post['html_code_module']['md5'] = array();
					
					foreach( $this->request->post['html_code_module']['description'] as $v2 ) {
						$this->request->post['html_code_module']['md5'][] = md5( $v2 );
					}
			}
//			print_r('<pre>');
//			print_r($this->request->post['html_code_module']);
//			print_r('</pre>');
//			die();
			if (!$mod_id) {
				$this->model_extension_module->addModule($this->_name, $this->request->post['html_code_module']);
				$mod_id = $this->db->getLastId();
			
				if( NULL != ( $tmp_files = glob( DIR_CACHE . 'html_code_tmp.*' ) ) ) {
					foreach( $tmp_files as $filename ) {
						unlink( $filename );
					}
				}
			} else {
				$this->db->query("DELETE FROM `" . DB_PREFIX . "layout_module` WHERE `code` = 'html_code." . (int) $mod_id . "'" );		
				$this->model_extension_module->editModule($mod_id, $this->request->post['html_code_module']);
			}	

			if( ! empty( $this->request->post['html_code_module']['layout_id'] ) ) {
				foreach( $this->request->post['html_code_module']['layout_id'] as $layout_id ) {
					$this->db->query("
						INSERT INTO 
							`" . DB_PREFIX . "layout_module` 
						SET
							`layout_id` = '" . (int) $layout_id . "',
							`code` = 'html_code." . (int)$mod_id . "',
							`position` = '" . $this->db->escape( $this->request->post['html_code_module']['position'] ) . "',
							`sort_order` = '" . $this->db->escape( $this->request->post['html_code_module']['sort_order'] ) . "'
					");
				}
			}

			$this->session->data['success'] = $this->language->get('text_success');
						
			if( ! empty( $this->request->post['exit'] ) )
				$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], true));
			
			$this->response->redirect($this->url->link('extension/module/' . $this->_name, 'token=' . $this->session->data['token'].'&module_id='.$mod_id, true));
		}
		
		$this->_messages();
		
		$this->data['action'] = $this->url->link('extension/module/' . $this->_name, 'token=' . $this->session->data['token'].'&module_id='.$mod_id, true);
		$this->data['token'] = $this->session->data['token'];
				
		$this->data['module'] = array();
		
		$this->load->model('localisation/language');
		$this->load->model('extension/module');
		if (isset($this->request->post['html_code_module'])) {
			$this->data['module'] = $this->request->post['html_code_module'];
		} elseif ($this->model_extension_module->getModule($mod_id)) {
			$this->data['module'] = $this->model_extension_module->getModule($mod_id);
		}

		// Products ////////////////////////////////////////////////////////////
		$products_ids = array();

			if( !empty( $this->data['module']['product_id'] ) ){

				foreach( $this->data['module']['product_id'] as $product_id ) {
					$products_ids[] = (int) $product_id;
				}
			}

		$this->data['products'] = array();
		
		if( ! empty( $products_ids ) ) {
			$products	= array();
			$query		= "
				SELECT 
					p.product_id, pd.name 
				FROM 
					" . DB_PREFIX . "product AS p
				LEFT JOIN
					" . DB_PREFIX . "product_description AS pd
				ON
					p.product_id = pd.product_id AND pd.language_id = " . $this->config->get( 'config_language_id' ) . "
				WHERE
					p.product_id IN(" . implode( ',', array_unique( $products_ids ) ) . ")
			";
			
			foreach( $this->db->query( $query )->rows as $product ) {
				$products[$product['product_id']] = $product['name'];
			}
				
			if( ! empty( $this->data['module']['product_id'] ) ){

				foreach( $this->data['module']['product_id'] as $product_id ) {
					$this->data['products'][$product_id] = $products[$product_id];
				}
			}
		}
		
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->load->model('catalog/category');
		$this->load->model('catalog/information');
		
		$this->load->model('customer/customer_group');
		$this->data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups(array());
		
		$this->data['categories'] = $this->model_catalog_category->getCategories(array());
		$this->data['informations'] = $this->model_catalog_information->getInformations(array());
		
		$this->load->model('setting/setting');
		
		// Read current settings
		$this->data['settings'] = $this->model_setting_setting->getSetting( 'html_code' );
		
		// @since 1.1.1
		if( empty( $this->data['settings']['html_code_layout_p'] ) )
			$this->data['settings']['html_code_layout_p'] = '2';
		
		$this->load->model('setting/store');
		
		$this->data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->config->get('config_name') . $this->language->get('text_default')
		);
				
		foreach( $this->model_setting_store->getStores() as $result ) {
			$this->data['stores'][] = array(
				'store_id' => $result['store_id'],
				'name'     => $result['name']
			);
		}
		
		$this->data['header'] = $this->load->controller('common/header');
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput( $this->load->view('extension/module/' . $this->_name . '.tpl', $this->data) );
	}
	
	protected function validate() {
		$this->language->load('extension/module/' . $this->_name);
		
		if( ! $this->user->hasPermission('modify', 'extension/module/' . $this->_name) ) {
			$this->session->data['error'] = $this->language->get('error_permission');
			return false;
		} else {
			return true;
		}	
	}
	

	public function settings() {
		$this->data = array_merge($this->data, $this->language->load('extension/module/' . $this->_name));
		
		// Action for the form
		$id = isset($this->request->get['module_id']) ? '&module_id='.$this->request->get['module_id'] : null;
		$this->data['action'] = $this->url->link('extension/module/' . $this->_name. '/settings', 'token=' . $this->session->data['token'].$id, true);
		
		$this->load->model('setting/setting');
		
		if( ! $this->config->get( 'html_code_layout_i' ) && ! $this->config->get( 'html_code_layout_c' ) )
			$this->_setDefaultSettings();
		
		// save settings
		if( $this->request->server['REQUEST_METHOD'] == 'POST' ) {	
			/**
			 * save settings
			 */
			$this->model_setting_setting->editSetting($this->_name, $this->request->post['settings']);
			
			$this->session->data['success'] = $this->language->get('text_success');
						
			if( ! empty( $this->request->post['exit'] ) )
				$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], true));
			
			$this->response->redirect($this->url->link('extension/module/' . $this->_name. '/settings', 'token=' . $this->session->data['token'].$id, true));
		}
		
		// Read current settings
		$this->data['settings'] = $this->model_setting_setting->getSetting( 'html_code' );
		
		// @since 1.1.1
		if( empty( $this->data['settings']['html_code_layout_p'] ) )
			$this->data['settings']['html_code_layout_p'] = '2';
		
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		// Template settings
		$this->data['header'] = $this->load->controller('common/header');
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput( $this->load->view('extension/module/' . $this->_name . '_settings.tpl', $this->data) );
	}
	
	public function about() {
		// Template settings
		$this->data['action'] = $this->url->link('extension/module/' . $this->_name. '/about', 'token=' . $this->session->data['token'], true);
		$this->data['ext_version'] = $this->_version;
				
		$this->data['header'] = $this->load->controller('common/header');
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput( $this->load->view('extension/module/' . $this->_name . '_about.tpl', $this->data) );
	}

	/**
	 * @param string $permission
	 * @return boolean
	 */
	private function userPermission($permission = 'modify') {
		$this->language->load('extension/module/'. $this->_name);

		if( ! $this->user->hasPermission($permission, 'extension/module/'. $this->_name) ) {
			$this->session->data['error'] = $this->language->get('error_permission');
			return false;
		} else {
			return true;
		}
	}

	private function _setDefaultSettings() {
		$this->load->model('setting/setting');			
			
		/**
		 * Save settings
		 */
		$this->model_setting_setting->editSetting($this->_name, array(
			'html_code_layout_i' => '11',
			'html_code_layout_c' => '3',
			'html_code_layout_p' => '2'
		));
	}

	public function install() {
		if( $this->userPermission() ) {		
			$this->load->language('extension/module/' . $this->_name);
			
			// load module
			$this->load->model('setting/setting');
			$this->load->model('extension/extension');
			
			$this->_setDefaultSettings();
			if( ! in_array( $this->_name, $this->model_extension_extension->getInstalled('module') ) ){
				$this->model_extension_extension->install('module', $this->_name);
			}
			
			$this->session->data['success'] = $this->language->get('success_install');
			
			unset( $this->session->data['error'] );
		} 
	}

	public function uninstall() {
		if( $this->userPermission() ) {			
			$this->load->model('setting/setting');
			$this->load->model('extension/extension');
			
			$this->model_setting_setting->deleteSetting( $this->_name . '_module' );
			$this->model_setting_setting->deleteSetting( $this->_name );
			
			$this->model_extension_extension->uninstall('module', $this->_name);
			
			if( isset( $this->session->data['error_install'] ) ) {
				unset( $this->session->data['error_install'] );
			} else {
				$this->session->data['success'] = $this->language->get('success_uninstall');
			}
		}
	}
}
?>
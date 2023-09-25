<?php
class ControllerExtensionModulePim extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('extension/module/pim');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('pim', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'));
		}
		$data['heading_title'] = $this->language->get('heading_title');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['entry_delete_def_image'] = $this->language->get('entry_delete_def_image');
		$data['text_yes'] = $this->language->get('text_yes');
    $data['text_no'] = $this->language->get('text_no');
    $data['tab_general'] = $this->language->get('tab_general');    
    $data['tab_help'] = $this->language->get('tab_help'); 
    $data['tab_module']  = $this->language->get('tab_module');
    $data['tab_volume'] = $this->language->get('tab_volume');
    $data['text_enabled'] = $this->language->get('text_enabled');    
    $data['text_disabled'] = $this->language->get('text_disabled'); 
    $data['entry_status']= $this->language->get('entry_status');
		$data['column_name'] = $this->language->get('column_name');
    $data['column_description'] = $this->language->get('column_description');
		$data['column_action'] = $this->language->get('column_action');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_install'] = $this->language->get('button_install');
		$data['button_uninstall'] = $this->language->get('button_uninstall');
    
    $data['entry_aceshop'] = $this->language->get('entry_aceshop');
    $data['entry_dimensions']    = $this->language->get('entry_dimensions');
    $data['entry_language'] = $this->language->get('entry_language');
    $data['entry_miu_patch']  = $this->language->get('entry_miu_patch');
    $data['entry_thumb_size'] = $this->language->get('entry_thumb_size');

    // Root options
    $data['entry_copyOverwrite']   = $this->language->get('entry_copyOverwrite');
    $data['entry_uploadOverwrite'] = $this->language->get('entry_uploadOverwrite');
    $data['entry_uploadMaxSize']   = $this->language->get('entry_uploadMaxSize');
    
    // Client options
    $data['entry_defaultView']     = $this->language->get('entry_defaultView');
    $data['entry_dragUploadAllow'] = $this->language->get('entry_dragUploadAllow');
    $data['entry_loadTmbs']        = $this->language->get('entry_loadTmbs');
    
    
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];

			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}    
		
 		if (isset($this->error['folder'])) {
			$data['error_folder'] = $this->error['folder'];
		} else {
			$data['error_folder'] = '';
		}    
		
		$data['breadcrumbs'] = array();

 		$data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('text_home'),
     		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
    		'separator' => false
 		);

 		$data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('text_module'),
     		'href'      => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'),
    		'separator' => ' :: '
 		);
	
 		$data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('heading_title'),
     		'href'      => $this->url->link('extension/module/pim', 'token=' . $this->session->data['token'], 'SSL'),
    		'separator' => ' :: '
 		);
		
		$data['action'] = $this->url->link('extension/module/pim', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL');


		
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
    
		if (isset($this->request->post['pim_status'])) {
			$data['pim_status'] = $this->request->post['pim_status'];
		} else {
			$data['pim_status'] = $this->config->get('pim_status');
		}	
		if (isset($this->request->post['pim_joomla'])) {
			$data['pim_joomla'] = $this->request->post['pim_joomla'];
		} else {
			$data['pim_joomla'] = $this->config->get('pim_joomla');
		}			
		if (isset($this->request->post['pim_miu'])) {
			$data['pim_miu'] = $this->request->post['pim_miu'];
		} else {
			$data['pim_miu'] = $this->config->get('pim_miu');
		}	

		if (isset($this->request->post['pim_width'])) {
			$data['pim_width'] = $this->request->post['pim_width'];
		} else if ($this->config->get('pim_width')){
			$data['pim_width'] = $this->config->get('pim_width');
		}	else {
  		$data['pim_width'] = 800;
		}

		if (isset($this->request->post['pim_height'])) {
			$data['pim_height'] = $this->request->post['pim_height'];
		} else if($this->config->get('pim_height')){
			$data['pim_height'] = $this->config->get('pim_height');
		} else {
  		$data['pim_height'] = 400;
		}			
		if (isset($this->request->post['pim_uploadMaxSize'])) {
			$data['pim_uploadMaxSize'] = $this->request->post['pim_uploadMaxSize'];
		} else if($this->config->get('pim_uploadMaxSize')){
			$data['pim_uploadMaxSize'] = $this->config->get('pim_uploadMaxSize');
		} else {
  		$data['pim_uploadMaxSize'] = 999;
		}		
		if (isset($this->request->post['pim_uploadMaxType'])) {
			$data['pim_uploadMaxType'] = $this->request->post['pim_uploadMaxType'];
		} else if($this->config->get('pim_uploadMaxType')){
			$data['pim_uploadMaxType'] = $this->config->get('pim_uploadMaxType');
		} else {
  		$data['pim_uploadMaxType'] = 'M';
		}		

		if (isset($this->request->post['pim_uploadOverwrite'])) {
			$data['pim_uploadOverwrite'] = $this->request->post['pim_uploadOverwrite'];
		} else {
			$data['pim_uploadOverwrite'] = $this->config->get('pim_uploadOverwrite');
		}	
		if (isset($this->request->post['pim_copyOverwrite'])) {
			$data['pim_copyOverwrite'] = $this->request->post['pim_copyOverwrite'];
		} else {
			$data['pim_copyOverwrite'] = $this->config->get('pim_copyOverwrite');
		}
		if (isset($this->request->post['pim_language'])) {
			$data['pim_language'] = $this->request->post['pim_language'];
		} else {
			$data['pim_language'] = $this->config->get('pim_language');
		}

    // loading external extensions
    $extensions = $this->config->get('pim_modules');
   
    if (!$extensions) {
      $extensions = array();
    }

		$data['extensions'] = array();

		$files = glob(DIR_SYSTEM . 'library/filemanager/plugins/*/*.php');

		if ($files) {
			foreach ($files as $file) {
				//$extension = basename($file, '.php');
        $names = explode('/', $file);
      
        $extension =  basename(dirname($file));
         $code = strtolower($extension);
         if (file_exists(DIR_LANGUAGE . 'en-gb/extension/module/pim_'.$code.'.php')) {
          $this->load->language('extension/module/pim_' . $code);
         }
       
				$module_data = array();				
				$data['extensions'][] = array(
					'name'      => $extension,
					'module'    => $module_data,
          'text'      => $this->language->get('text_'.$extension),
					'install'   => $this->url->link('extension/module/pim/mod_install', 'token=' . $this->session->data['token'] . '&extension=' . $extension, 'SSL'),
					'uninstall' => $this->url->link('extension/module/pim/mod_uninstall', 'token=' . $this->session->data['token'] . '&extension=' . $extension, 'SSL'),
					'installed' => (isset($extensions[$extension]))?1:0,
					'edit'      => $this->url->link('extension/module/pim/module', 'token=' . $this->session->data['token'] . '&extension=' . $extension, 'SSL'),
				);
			}
		}
    // end of extensions.
    // loading volumes.  
    $volumes = $this->config->get('pim_volumes');

        if (!$volumes) {
          $volumes = array();
        }

        $data['volumes'] = array();

       $files = glob(DIR_SYSTEM . 'library/filemanager/volumes/*.php');

        if ($files) {
          foreach ($files as $file) {
            //$extension = basename($file, '.php');

            $volume =  basename($file);
            $volume = str_replace('.class.php', '', $volume);
            $code = strtolower($volume);
            $vol_description  ='';

            if (file_exists(DIR_LANGUAGE . 'en-gb/extension/module/pim_'.$code.'.php')) {
              require(DIR_LANGUAGE . 'en-gb/extension/module/pim_'.$code.'.php');
              foreach($_ as $key=>$value) {
                if (strpos($key,'error') !== false) {continue;}
                $vol_description = $_['text_'.$code.'_description'];
              }
            }            
            if (!$vol_description  && $this->language->get('text_'.$volume)) {
              $vol_description = $this->language->get('text_'.$volume);
            }
            $childs = array();
            if (isset($volumes[$volume]) && is_array($volumes[$volume])) {
              foreach ($volumes[$volume] as $key => $child) {
                $childs[] = array (
                    'name'      => $key,
                    'dir'       => $child['path'],
                    'edit'      => $this->url->link('extension/module/pim/volume', 'token=' . $this->session->data['token'] . '&volume=' . $volume.'&alias='.urlencode($key).'', 'SSL'),
                    'delete'    => $this->url->link('extension/module/pim/volume', 'token=' . $this->session->data['token'] . '&volume=' . $volume.'&alias='.urlencode($key).'&delete=true', 'SSL'),
                    'status'    => $child['status']
                );
              }
            }
            
            $module_data = array();				
            $data['volumes'][] = array(
              'name'        => $volume,
              'module'      => $module_data,
              'text'        => $vol_description,
              'install'     => $this->url->link('extension/module/pim/vol_install', 'token=' . $this->session->data['token'] . '&volume=' . $volume, 'SSL'),
              'uninstall'   => $this->url->link('extension/module/pim/vol_uninstall', 'token=' . $this->session->data['token'] . '&volume=' . $volume, 'SSL'),
              'installed'   => (isset($volumes[$volume]))?1:0,
              'edit'        => $this->url->link('extension/module/pim/volume', 'token=' . $this->session->data['token'] . '&volume=' . $volume, 'SSL'),
              'childs'      => $childs
            );
          }
        }    
    // end of volumes



		if (isset($this->request->post['pim_deletedef'])) {
			$data['pim_deletedef'] = $this->request->post['pim_deletedef'];
		} else {
			$data['pim_deletedef'] = $this->config->get('pim_deletedef');
		}			
		
		$data['langs'] = array();
		$ignore = array(
			'LANG'
		);


		$files = glob(DIR_APPLICATION . 'view/javascript/pim/i18n/*.js');
		
		foreach ($files as $file) {
			$dataaa = explode('/', dirname($file));
			
			$permission = basename($file, '.js');
			
			if (!in_array($permission, $ignore)) {
				$data['langs'][] = $permission;
			}
		}		
		
		$data['heading_title'] = 'Power Image Manager';
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		
		$this->response->setOutput($this->load->view('extension/module/pim.tpl', $data));
	}
	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/pim')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
    $this->load->model('extension/modification');
    $mui = $this->model_extension_modification->getModificationByCode('L69NKY2UE1Jkef4NI');
    
    if ($this->request->post['pim_miu'] && empty($mui)) {
      $this->error['warning'] = $this->language->get('error_mui');
    }
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
  public function mod_uninstall() {
    $this->load->language('extension/module/pim');
    $error = array();
    if (!$this->request->get['extension']) {
       $error = 'No extension';
    }
    if (!$error) {
      $this->load->model('setting/setting');
      $raw_extensions = $this->config->get('pim_modules');
      
      
      
      if (isset($raw_extensions[$this->request->get['extension']])) {
        unset($raw_extensions[$this->request->get['extension']]);
      }
      $extensions = array();
      foreach ($raw_extensions as $key=>$val) {
        $extensions['pim_modules'][$key] = $val;
      }
      
      $this->model_setting_setting->editSetting('pim_modules', $extensions);    
      $this->session->data['success'] = sprintf($this->language->get('text_module_uninstalled'), $this->request->get['extension']);
    } else {
       $this->session->data['warning'] = $error;
    }
    $this->response->redirect($this->url->link('extension/module/pim', 'token=' . $this->session->data['token'], 'SSL'));    
  }
  
  public function mod_install() {
    $this->load->language('extension/module/pim');
    $error = array();
    if (!$this->request->get['extension']) {
       $error = 'No extension';
    }
    if (!$error) {
      $this->load->model('setting/setting');
      $raw_extensions = $this->config->get('pim_modules');
      if (!$raw_extensions) {
        $raw_extensions = array();
      } 
      $extensions = array();
      
      if (!isset($raw_extensions[$this->request->get['extension']])) {
        $extensions['pim_modules'][$this->request->get['extension']] = array(
          'name'  => $this->request->get['extension']
        );
      }
      foreach ($raw_extensions as $key=>$val) {
        $extensions['pim_modules'][$key] = $val;
      }
      
      

      $this->model_setting_setting->editSetting('pim_modules', $extensions);    
      $this->session->data['success'] = sprintf($this->language->get('text_module_installed'), $this->request->get['extension']);
    } else {
       $this->session->data['error_warning'] = $error;
    }
    $this->response->redirect($this->url->link('extension/module/pim', 'token=' . $this->session->data['token'], 'SSL'));
  }
  public function vol_install() {
    $this->load->language('extension/module/pim');
    $error = array();
    if (!$this->request->get['volume']) {
       $error = 'No volume';
    }
    if (!$error) {

      // check if there is install method.
      $code = strtolower($this->request->get['volume']);
        if (file_exists(DIR_APPLICATION . 'model/module/pim_'.$code.'.php')) {  	
          require_once(DIR_APPLICATION . 'model/module/pim_'.$code.'.php');
          $class = 'ModelModulePim' . preg_replace('/[^a-zA-Z0-9]/', '', $code);
          $mod = new $class($this->registry);
          if (method_exists($mod, 'install')) {
            $mod->install();
          }
      }         
      
      $this->load->model('setting/setting');
      $raw_volumes = $this->config->get('pim_volumes');
      if (!$raw_volumes) {
        $raw_volumes = array();
      } 
      $volumes = array();
      
      if (!isset($raw_volumes[$this->request->get['volume']])) {
        $volumes['pim_volumes'][$this->request->get['volume']] = '';
      }
      foreach ($raw_volumes as $key=>$val) {
        $volumes['pim_volumes'][$key] = $val;
      }

      $this->model_setting_setting->editSetting('pim_volumes', $volumes);    
      $this->session->data['success'] = sprintf($this->language->get('text_volume_installed'), $this->request->get['volume']);
      
   
      
      
    } else {
       $this->session->data['error_warning'] = $error;
    }
    $this->response->redirect($this->url->link('extension/module/pim/volume', 'token=' . $this->session->data['token']."&volume=".$this->request->get['volume'], 'SSL'));
  }  
  public function vol_uninstall() {
    $this->load->language('extension/module/pim');
    $error = array();
    if (!$this->request->get['volume']) {
       $error = 'No volume';
    }
    if (!$error) {
      $this->load->model('setting/setting');
      $raw_volumes = $this->config->get('pim_volumes');
      
      
      
      if (isset($raw_volumes[$this->request->get['volume']])) {
        unset($raw_volumes[$this->request->get['volume']]);
      }
      $volumes = array();
      foreach ($raw_volumes as $key=>$val) {
        $volumes['pim_volumes'][$key] = $val;
      }
      
      $this->model_setting_setting->editSetting('pim_volumes', $volumes);    
      $this->session->data['success'] = sprintf($this->language->get('text_volume_uninstalled'), $this->request->get['volume']);
    } else {
       $this->session->data['warning'] = $error;
    }
    $this->response->redirect($this->url->link('extension/module/pim', 'token=' . $this->session->data['token'], 'SSL'));    
  }  
  
  // plugins
  public function Sanitizer() {
    $this->session->data['error_warning'] = 'This module does not have any settings';
    $this->response->redirect($this->url->link('extension/module/pim', 'token=' . $this->session->data['token'], 'SSL'));
  }
  
  
  public function module() {
  
    $error='';
    if (!$this->request->get['extension']) {
      $error='Error occurred. Please try again.';
    } else {
      $extension = $this->request->get['extension'];
    }
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
    
  		$this->load->model('setting/setting');
      
      if (isset($this->request->post[$this->request->post['module']]['enable'])) {
       $this->request->post[$this->request->post['module']]['enable'] = (bool) ($this->request->post[$this->request->post['module']]['enable'] == 'true');
      }
 
			$this->model_setting_setting->editSetting($this->request->post['module'], $this->request->post);		
      
      // load the success message.
      $code = strtolower($this->request->get['extension']);
      if (file_exists(DIR_LANGUAGE . 'en-gb/extension/module/pim_'.$code.'.php')) {
          //$this->load->language('extension/module/pim_'.$code.'.php');
          require(DIR_LANGUAGE . 'en-gb/extension/module/pim_'.$code.'.php');
      }      
			$this->session->data['success'] = $_['text_success'];						
			$this->response->redirect($this->url->link('extension/module/pim', 'token=' . $this->session->data['token'], 'SSL'));
		}      
    
    $code = strtolower($this->request->get['extension']);
    $module ='pim_'.$extension;
    $this->load->model('tool/image');    
    $data['module'] = $module;
    
    $data['text_disabled'] = $this->language->get('text_disabled');
    $data['text_enabled'] = $this->language->get('text_enabled');

    $module_data = $this->config->get($module);
    
    $data['source'] = '';
    $data['source_thumb'] = $this->model_tool_image->resize('no_image.png', 40, 40);
    $data['enable'] = false;

    if (!empty($module_data)) {    
      foreach ($module_data as $key=>$val) {        
        if($key == 'source'){
          if (is_file(DIR_IMAGE . $val)) {
            $image = $this->model_tool_image->resize($val, 40, 40);
          } else {
            $image = $this->model_tool_image->resize('no_image.png', 40, 40);
          }           
          $data[$key."_thumb"] = $image;
        }
        $data[$key] = $val;
      }
    }
    $this->load->language('extension/module/pim');
    
    if (file_exists(DIR_LANGUAGE . 'en-gb/extension/module/pim_'.$code.'.php')) {
        //$this->load->language('extension/module/pim_'.$code.'.php');
        require(DIR_LANGUAGE . 'en-gb/extension/module/pim_'.$code.'.php');
        foreach($_ as $key=>$value) {
          if (strpos($key,'error') !== false) {continue;}
          $data[$key] = $value;
        }
    }
    
    
  
		$data['breadcrumbs'] = array();

 		$data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('text_home'),
     		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
    		'separator' => false
 		);

 		$data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('text_module'),
     		'href'      => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'),
    		'separator' => ' :: '
 		);
	
 		$data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('heading_title'),
     		'href'      => $this->url->link('extension/module/pim', 'token=' . $this->session->data['token'], 'SSL'),
    		'separator' => ' :: '
 		);

 		$data['breadcrumbs'][] = array(
     		'text'      => $extension,
     		'href'      => $this->url->link('extension/module/pim/extension', 'token=' . $this->session->data['token'].'&extension='.$extension, 'SSL'),
    		'separator' => ' :: '
 		);
				
		$data['action'] = $this->url->link('extension/module/pim/module', 'token=' . $this->session->data['token'] . '&extension=' . $extension, 'SSL');
		
		$data['cancel'] = $this->url->link('extension/module/pim', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];

			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}    
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}  

    
    
    if (file_exists(DIR_TEMPLATE .'/extension/module/pim_modules/'.$code.'.tpl')) {
      
      $this->document->setTitle($data['heading_title']);
      $data['header'] = $this->load->controller('common/header');
      $data['column_left'] = $this->load->controller('common/column_left');
      $data['footer'] = $this->load->controller('common/footer');        
      $this->response->setOutput($this->load->view('extension/module/pim_modules/'.$code.'.tpl', $data));
    } else {
        $this->session->data['error_warning'] = 'This module does not require any setup.';
        $this->response->redirect($this->url->link('extension/module/pim', 'token=' . $this->session->data['token'], 'SSL'));
    }
  }
  
 
  public function volume() {
    $error='';
    if (!$this->request->get['volume']) {
      $error='Error occurred. Please try again.';
    } else {
      $volume = $this->request->get['volume'];
    }
    $alias = '';
    if (isset($this->request->get['alias'])) {
      $alias = urldecode($this->request->get['alias']);
    } else if(isset($this->request->post['alias'])){
      $alias = $this->request->post['alias'];
    }
    
    $url = '';
    if ($alias) {
      $url = '&alias='.urlencode($alias);
    }

    $code = strtolower($this->request->get['volume']);
    $this->load->language('extension/module/pim');    
  
        
		$this->load->model('setting/setting');
		$validate_check = array();
		if (($this->request->server['REQUEST_METHOD'] == 'POST') || isset($this->request->get['delete'])) {

        if (file_exists(DIR_APPLICATION . 'model/module/pim_'.$code.'.php')) {  	
          require_once(DIR_APPLICATION . 'model/module/pim_'.$code.'.php');
          $class = 'ModelModulePim' . preg_replace('/[^a-zA-Z0-9]/', '', $code);
          $validate = new $class($this->registry);
          $validate_check = array();
          if (!isset($this->request->get['delete'])) {
            $validate_check = $validate->validate($this->request->post);
          }
          if (!empty($validate_check)) {
            foreach ($validate_check as $key=> $error) {
              $data[$key] = $error;
            }
            
            $this->error['warning']  = $this->language->get('error_warning');
          }
          
        }	

  		if (empty($validate_check)) {
  		  $module_data = $this->config->get('pim_volumes');
  		  if (isset($module_data[$this->request->get['volume']][$alias])) {
    		  unset($module_data[$this->request->get['volume']][$alias]);  
  		  }
  		  
        $module_data[$this->request->get['volume']][(isset($this->request->post['alias'])?$this->request->post['alias']:$this->request->get['alias'])] = isset($this->request->post)?$this->request->post:array();
        
    if (isset($this->request->get['delete'])) {
       unset($module_data[$this->request->get['volume']][$this->request->get['alias']]);
     }    

         $this->model_setting_setting->editSettingValue('pim_volumes', 'pim_volumes', $module_data);							
       
  			$this->session->data['success'] = $this->language->get('text_success');						
  			$this->response->redirect($this->url->link('extension/module/pim', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}  


    $module ='pim_volume_'.$volume;
    
    $data['module'] = $module;
    $module_data = $this->config->get('pim_volumes');
    
    if ($alias<>'' && !empty($module_data[$volume][$alias])) {
      foreach ($module_data[$volume][$alias] as $key=>$val) {
        $data[$key] = $val;
      }
    }
    

    
    if (file_exists(DIR_LANGUAGE . 'en-gb/extension/module/pim_'.$code.'.php')) {
      require(DIR_LANGUAGE . 'en-gb/extension/module/pim_'.$code.'.php');
      foreach($_ as $key=>$value) {
        if (preg_match('/^error/', $key)) { continue; }
        $data[$key] = $value;
      }
    }
  
		$data['breadcrumbs'] = array();

 		$data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('text_home'),
     		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
    		'separator' => false
 		);

 		$data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('text_module'),
     		'href'      => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'),
    		'separator' => ' :: '
 		);
	
 		$data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('heading_title'),
     		'href'      => $this->url->link('extension/module/pim', 'token=' . $this->session->data['token'], 'SSL'),
    		'separator' => ' :: '
 		);

 		$data['breadcrumbs'][] = array(
     		'text'      => $volume,
     		'href'      => $this->url->link('extension/module/pim/volume', 'token=' . $this->session->data['token'].'&volume='.$volume, 'SSL'),
    		'separator' => ' :: '
 		);
				
		$data['action'] = $this->url->link('extension/module/pim/volume', 'token=' . $this->session->data['token'] . '&volume=' . $volume.$url, 'SSL');
		
		$data['cancel'] = $this->url->link('extension/module/pim', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else 		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];

			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}  

    if (file_exists(DIR_TEMPLATE .'/extension/module/pim_volumes/'.$code.'.tpl')) {
      
      $this->document->setTitle($data['heading_title']);
      $data['header'] = $this->load->controller('common/header');
      $data['column_left'] = $this->load->controller('common/column_left');
      $data['footer'] = $this->load->controller('common/footer');        
      $this->response->setOutput($this->load->view('extension/module/pim_volumes/'.$code.'.tpl', $data));
    } else {
        $this->session->data['error_warning'] = 'This volume does not require any setup.';
        $this->response->redirect($this->url->link('extension/module/pim', 'token=' . $this->session->data['token'], 'SSL'));
    }
  }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             	
}
?>
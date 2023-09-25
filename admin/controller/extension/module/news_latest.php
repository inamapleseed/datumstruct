<?php
class ControllerExtensionModuleNewsLatest extends Controller {
	private $error = array(); 

	public function index() {   
		$this->language->load('extension/module/news_latest');

		$this->document->setTitle($this->language->get('title_title'));

		$this->load->model('extension/module');
		$this->load->model('catalog/ncategory');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {	
            if (!isset($this->request->get['module_id'])) {
				$this->model_extension_module->addModule('news_latest', $this->request->post);
			} else {
				$this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
			}
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		}

		//delete old module files			
		if (isset($this->request->get['deleteOldVersion']) && $this->validatePermissions()) {
			@unlink(DIR_APPLICATION . 'controller/module/news.php');
			@unlink(DIR_APPLICATION . 'language/english/module/news.php');
			@unlink(DIR_APPLICATION . 'language/en-gb/module/news.php');
			@unlink(DIR_APPLICATION . 'view/template/module/news.tpl');
			@unlink(DIR_CATALOG . 'controller/module/news.php');
			@unlink(DIR_CATALOG . 'language/english/module/news.php');
			@unlink(DIR_CATALOG . 'language/en-gb/module/news.php');
			@unlink(DIR_CATALOG . 'view/theme/default/module/news.tpl');
			$this->session->data['success'] = 'You succesfully deleted the old version of the module!';
			$this->response->redirect($this->url->link('extension/module/news_latest', 'token=' . $this->session->data['token'], true));
		} elseif (isset($this->request->get['deleteOldVersion'])) {
			$this->response->redirect($this->url->link('extension/module/news_latest', 'nopermissiontodelete=1&token=' . $this->session->data['token'], true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_bnews_dscols'] = $this->language->get('text_bnews_dscols');
		$data['text_bnews_dscol'] = $this->language->get('text_bnews_dscol');

		$data['entry_limit'] = $this->language->get('entry_limit');
        $data['entry_name'] = $this->language->get('entry_name');
		$data['entry_nocat'] = $this->language->get('entry_nocat');
		$data['entry_cat'] = $this->language->get('entry_cat');
		$data['entry_layout'] = $this->language->get('entry_layout');
		$data['entry_position'] = $this->language->get('entry_position');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_npages'] = $this->language->get('entry_npages');
		$data['entry_display_style'] = $this->language->get('entry_display_style');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['ncategories'] = array();
		
		$results = $this->model_catalog_ncategory->getncategories(0);

		foreach ($results as $result) {
							
			$data['ncategories'][] = array(
				'ncategory_id' => $result['ncategory_id'],
				'name'         => $result['name']
			);
		}
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
        
		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->get['nopermissiontodelete']) && $this->request->get['nopermissiontodelete']) {
			$data['error_warning']  = $this->language->get('error_permission');
		}

		if (is_file(DIR_APPLICATION . 'controller/module/news.php')) {
			$data['error_warning']  = $this->language->get('error_old_version');
			$data['error_warning'] .= '<a href="' . $this->url->link('extension/module/news_latest', 'deleteOldVersion=1&token=' . $this->session->data['token'], true) . '" class="btn btn-xs btn-danger">' . $this->language->get('delete_old_version') . '</a>';
		}

		if (!$this->validatePermissions()) {
			$data['error_warning']  = $this->language->get('error_permission_instructions');
		}


  		$data['breadcrumbs'] = array();

  		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], true)
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
   		);

  		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/module/news_latest', 'token=' . $this->session->data['token'], true)
   		);

        if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/news_latest', 'token=' . $this->session->data['token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/news_latest', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true);
		}
		
		$data['npages'] = $this->url->link('catalog/news', 'token=' . $this->session->data['token'], true);
		
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);
        
        if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
		}
        if (isset($this->request->get['module_id'])) {
            $data['istemplate'] = 'no';   
        } else {
            $data['istemplate'] = 'yes';     
        }
		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}
        
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}
        
        if (isset($this->request->post['news_limit'])) {
			$data['news_limit'] = $this->request->post['news_limit'];
		} elseif (!empty($module_info)) {
			$data['news_limit'] = $module_info['news_limit'];
		} else {
			$data['news_limit'] = 4;
		}
        
        if (isset($this->request->post['ncategory_id'])) {
			$data['ncategory_id'] = $this->request->post['ncategory_id'];
		} elseif (!empty($module_info)) {
			$data['ncategory_id'] = $module_info['ncategory_id'];
		} else {
			$data['ncategory_id'] = 'all';
		}

        if (isset($this->request->post['display_style'])) {
			$data['display_style'] = $this->request->post['display_style'];
		} elseif (!empty($module_info)) {
			$data['display_style'] = isset($module_info['display_style']) ? $module_info['display_style'] : 1;
		} else {
			$data['display_style'] = 1;
		}
		
 		$data['header'] = $this->load->controller('common/header');
 		$data['newspanel'] = $this->load->controller('common/newspanel');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/news_latest.tpl', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/news_latest')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		return !$this->error;
	}

	private function validatePermissions() {
		if (!$this->user->hasPermission('modify', 'extension/module/news_latest')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
}
?>

<?php 
class ControllerCatalogncategory extends Controller { 
	private $error = array();
 
	public function index() {
		$this->language->load('catalog/ncategory');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/ncategory');
		 
		$this->getList();
	}

	public function insert() {
		$this->language->load('catalog/ncategory');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/ncategory');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_ncategory->addncategory($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->response->redirect($this->url->link('catalog/ncategory', 'token=' . $this->session->data['token'], 'SSL')); 
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('catalog/ncategory');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/ncategory');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_ncategory->editncategory($this->request->get['ncategory_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->response->redirect($this->url->link('catalog/ncategory', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('catalog/ncategory');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/ncategory');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $ncategory_id) {
				$this->model_catalog_ncategory->deletencategory($ncategory_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('catalog/ncategory', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getList();
	}

	private function getList() {
   		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/ncategory', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
									
		$data['insert'] = $this->url->link('catalog/ncategory/insert', 'token=' . $this->session->data['token'], 'SSL');
		$data['delete'] = $this->url->link('catalog/ncategory/delete', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['ncategories'] = array();
		
		$results = $this->model_catalog_ncategory->getncategories(0);

		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('button_edit'),
				'href' => $this->url->link('catalog/ncategory/update', 'token=' . $this->session->data['token'] . '&ncategory_id=' . $result['ncategory_id'], 'SSL')
			);
					
			$data['ncategories'][] = array(
				'ncategory_id' => $result['ncategory_id'],
				'name'        => $result['name'],
				'sort_order'  => $result['sort_order'],
				'selected'    => isset($this->request->post['selected']) && in_array($result['ncategory_id'], $this->request->post['selected']),
				'action'      => $action
			);
		}
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_insert'] = $this->language->get('button_insert');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['text_confirm'] = $this->language->get('text_confirm');
 
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['newspanel'] = $this->load->controller('common/newspanel');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/ncategory_list.tpl', $data));
	}

	private function getForm() {

		$data["base_url"] = HTTPS_CATALOG;
		$data["token"]	=	$this->session->data['token'];

		// Enhanced CKEditor
		if (!file_exists(DIR_CATALOG.'../vqmod/xml/enhanced_file_manager.xml') || file_exists(DIR_CATALOG.'../vqmod/xml/enhanced_file_manager.xml_')) {				
			$data['fm_installed'] = 0;
		}
		if (file_exists(DIR_CATALOG.'../vqmod/xml/enhanced_file_manager.xml') && $this->config->get('fm_installed') == 1) {				
			$data['fm_installed'] = 1;
		}
					
		if ($this->config->get('ea_cke_enable_ckeditor') == 1) {
			$data['ckeditor_enabled'] = 1;
		} else {
			$data['ckeditor_enabled'] = 0;
		}
		
		if ($this->config->get('ea_cke_ckeditor_skin')) {
		  $data['ckeditor_skin'] = $this->config->get('ea_cke_ckeditor_skin');
		} else {
		  $data['ckeditor_skin'] = 'moono-lisa';
		}
		
		if ($this->config->get('ea_cke_codemirror_skin')) {
		  $data['codemirror_skin'] = $this->config->get('ea_cke_codemirror_skin');
		} else {
		  $data['codemirror_skin'] = 'eclipse';
		}
		// Enhanced CKEditor	

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_none'] = $this->language->get('text_none');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_image_manager'] = $this->language->get('text_image_manager');
		$data['text_browse'] = $this->language->get('text_browse');
		$data['text_clear'] = $this->language->get('text_clear');		
		$data['text_enabled'] = $this->language->get('text_enabled');
    	$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_percent'] = $this->language->get('text_percent');
		$data['text_amount'] = $this->language->get('text_amount');
				
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_groups'] = $this->language->get('entry_groups');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['entry_parent'] = $this->language->get('entry_parent');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_top'] = $this->language->get('entry_column_display');
		$data['entry_column'] = $this->language->get('entry_article_limit');		
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_layout'] = $this->language->get('entry_layout');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

    	$data['tab_general'] = $this->language->get('tab_general');
    	$data['tab_data'] = $this->language->get('tab_data');
		$data['tab_design'] = $this->language->get('tab_design');
		
		$data['bnews_html_editor'] = $this->config->get('ncategory_bnews_html_editor');
		
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
	
 		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}

		if (isset($this->error['keyword'])) {
			$data['error_keyword'] = $this->error['keyword'];
		} else {
			$data['error_keyword'] = '';
		}
        
  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/ncategory', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		if (!isset($this->request->get['ncategory_id'])) {
			$data['action'] = $this->url->link('catalog/ncategory/insert', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/ncategory/update', 'token=' . $this->session->data['token'] . '&ncategory_id=' . $this->request->get['ncategory_id'], 'SSL');
		}
		
		$data['cancel'] = $this->url->link('catalog/ncategory', 'token=' . $this->session->data['token'], 'SSL');

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->get['ncategory_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$ncategory_info = $this->model_catalog_ncategory->getncategory($this->request->get['ncategory_id']);
    	}
		
		$this->load->model('localisation/language');
		
		$data['languages'] = $this->model_localisation_language->getLanguages();

		//opencart 2.2.0.0 code

	  	if (version_compare(VERSION, '2.2.0.0') >= 0) {
	  		$languages = $data['languages'];
	  		$data['languages'] = array();
	  		foreach ($languages as $language) {
	  			$data['languages'][] = array(
	  				'name' => $language['name'],
	  				'language_id' => $language['language_id'],
	  				'image' => "../../../language/$language[code]/$language[code].png",
	  				'code' => $language['code']
	  				);
	  		}

	  	}

	  	//opencart 2.2.0.0 code ends

		if (isset($this->request->post['ncategory_description'])) {
			$data['ncategory_description'] = $this->request->post['ncategory_description'];
		} elseif (isset($this->request->get['ncategory_id'])) {
			$data['ncategory_description'] = $this->model_catalog_ncategory->getncategoryDescriptions($this->request->get['ncategory_id']);
		} else {
			$data['ncategory_description'] = array();
		}

		$ncategories = $this->model_catalog_ncategory->getncategories(0);

		// Remove own id from list
		if (!empty($ncategory_info)) {
			foreach ($ncategories as $key => $ncategory) {
				if ($ncategory['ncategory_id'] == $ncategory_info['ncategory_id']) {
					unset($ncategories[$key]);
				}
			}
		}

		$data['ncategories'] = $ncategories;

		if (isset($this->request->post['parent_id'])) {
			$data['parent_id'] = $this->request->post['parent_id'];
		} elseif (!empty($ncategory_info)) {
			$data['parent_id'] = $ncategory_info['parent_id'];
		} else {
			$data['parent_id'] = 0;
		}
						
		$this->load->model('setting/store');
		
		$data['stores'] = $this->model_setting_store->getStores();
		
		if (isset($this->request->post['ncategory_store'])) {
			$data['ncategory_store'] = $this->request->post['ncategory_store'];
		} elseif (isset($this->request->get['ncategory_id'])) {
			$data['ncategory_store'] = $this->model_catalog_ncategory->getncategoryStores($this->request->get['ncategory_id']);
		} else {
			$data['ncategory_store'] = array(0);
		}		

		if (version_compare(VERSION, '2.1.0.0') >= 0 || VERSION =='2.1.0.0_rc1') {
			$this->load->model('customer/customer_group');

			$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
		} else {
			$this->load->model('sale/customer_group');

			$data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		}

		if (isset($this->request->post['ncategory_group'])) {
			$data['ncategory_group'] = $this->request->post['ncategory_group'];
		} elseif (isset($this->request->get['ncategory_id'])) {
			$data['ncategory_group'] = $this->model_catalog_ncategory->getncategoryGroups($this->request->get['ncategory_id']);
		} else {
			$ng=array(); for($i=1; $i<151; $i++) $ng[] = $i;
			$data['ncategory_group'] = $ng;
		}		
		
		if (isset($this->request->post['keyword'])) {
			$data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($ncategory_info)) {
			$data['keyword'] = $ncategory_info['keyword'];
		} else {
			$data['keyword'] = '';
		}

		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($ncategory_info)) {
			$data['image'] = $ncategory_info['image'];
		} else {
			$data['image'] = '';
		}
		
		$this->load->model('tool/image');

		if (!empty($ncategory_info) && $ncategory_info['image'] && file_exists(DIR_IMAGE . $ncategory_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($ncategory_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		
		if (isset($this->request->post['top'])) {
			$data['top'] = $this->request->post['top'];
		} elseif (!empty($ncategory_info)) {
			$data['top'] = $ncategory_info['top'];
		} else {
			$data['top'] = 0;
		}
		
		if (isset($this->request->post['column'])) {
			$data['column'] = $this->request->post['column'];
		} elseif (!empty($ncategory_info)) {
			$data['column'] = $ncategory_info['column'];
		} else {
			$data['column'] = 10;
		}
				
		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($ncategory_info)) {
			$data['sort_order'] = $ncategory_info['sort_order'];
		} else {
			$data['sort_order'] = 0;
		}
		
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($ncategory_info)) {
			$data['status'] = $ncategory_info['status'];
		} else {
			$data['status'] = 1;
		}
				
		if (isset($this->request->post['ncategory_layout'])) {
			$data['ncategory_layout'] = $this->request->post['ncategory_layout'];
		} elseif (isset($this->request->get['ncategory_id'])) {
			$data['ncategory_layout'] = $this->model_catalog_ncategory->getncategoryLayouts($this->request->get['ncategory_id']);
		} else {
			$data['ncategory_layout'] = array();
		}

		$this->load->model('design/layout');
		
		$data['layouts'] = $this->model_design_layout->getLayouts();
				
		$data['header'] = $this->load->controller('common/header');
		$data['newspanel'] = $this->load->controller('common/newspanel');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/ncategory_form.tpl', $data));
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/ncategory')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['ncategory_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 2) || (utf8_strlen($value['name']) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
					
		if (utf8_strlen($this->request->post['keyword']) > 0) {
			$this->load->model('catalog/url_alias');

			$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

			if ($url_alias_info && isset($this->request->get['ncategory_id']) && $url_alias_info['query'] != 'ncategory_id=' . $this->request->get['ncategory_id']) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}

			if ($url_alias_info && !isset($this->request->get['ncategory_id'])) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}
		}
        
		return !$this->error;
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/ncategory')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
 
		return !$this->error;
	}
}
?>
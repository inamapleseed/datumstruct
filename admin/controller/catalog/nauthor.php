<?php    
class ControllerCatalogNauthor extends Controller { 
	private $error = array();
	
	public function index() {
		$this->language->load('catalog/nauthor');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/nauthor');

		$this->getList();
	}

	public function insert() {
		$this->language->load('catalog/nauthor');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/nauthor');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
		$url = '';
			$this->model_catalog_nauthor->addAuthor($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('catalog/nauthor', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('catalog/nauthor');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/nauthor');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
		$url = '';
			$this->model_catalog_nauthor->editAuthor($this->request->get['nauthor_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('catalog/nauthor', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('catalog/nauthor');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/nauthor');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
		$url = '';
			foreach ($this->request->post['selected'] as $nauthor_id) {
				$this->model_catalog_nauthor->deleteAuthor($nauthor_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('catalog/nauthor', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {

		$url = '';
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/nauthor', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$data['insert'] = $this->url->link('catalog/nauthor/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('catalog/nauthor/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	

		$data['nauthors'] = array();

		$results = $this->model_catalog_nauthor->getAuthors();

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('button_edit'),
				'href' => $this->url->link('catalog/nauthor/update', 'token=' . $this->session->data['token'] . '&nauthor_id=' . $result['nauthor_id'] . $url, 'SSL')
			);

			$data['nauthors'][] = array(
				'nauthor_id' => $result['nauthor_id'],
				'name'            => $result['name'],
				'selected'        => isset($this->request->post['selected']) && in_array($result['nauthor_id'], $this->request->post['selected']),
				'action'          => $action
			);
		}	

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['column_name'] = $this->language->get('column_name');
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

		$this->response->setOutput($this->load->view('catalog/nauthor_list.tpl', $data));
	}

	protected function getForm() {
		$url = '';
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_image_manager'] = $this->language->get('text_image_manager');
		$data['text_browse'] = $this->language->get('text_browse');
		$data['text_clear'] = $this->language->get('text_clear');		

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_adminid'] = $this->language->get('entry_adminid');
		$data['entry_ctitle'] = $this->language->get('entry_ctitle');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');

		$data['bnews_html_editor'] = $this->config->get('ncategory_bnews_html_editor');

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
			'href'      => $this->url->link('catalog/nauthor', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['nauthor_id'])) {
			$data['action'] = $this->url->link('catalog/nauthor/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/nauthor/update', 'token=' . $this->session->data['token'] . '&nauthor_id=' . $this->request->get['nauthor_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('catalog/nauthor', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['nauthor_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$nauthor_info = $this->model_catalog_nauthor->getAuthor($this->request->get['nauthor_id']);
		}

		$data['token'] = $this->session->data['token'];
		
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

		if (isset($this->request->post['nauthor_description'])) {
			$data['nauthor_description'] = $this->request->post['nauthor_description'];
		} elseif (isset($this->request->get['nauthor_id'])) {
			$data['nauthor_description'] = $this->model_catalog_nauthor->getNauthorDescriptions($this->request->get['nauthor_id']);
		} else {
			$data['nauthor_description'] = array();
		}
		
		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($nauthor_info)) {
			$data['name'] = $nauthor_info['name'];
		} else {	
			$data['name'] = '';
		}

		if (isset($this->request->post['adminid'])) {
			$data['adminid'] = $this->request->post['adminid'];
		} elseif (!empty($nauthor_info)) {
			$data['adminid'] = $nauthor_info['adminid'];
		} else {	
			$data['adminid'] = 0;
		}
		
		if (isset($this->request->post['keyword'])) {
			$data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($nauthor_info)) {
			$data['keyword'] = $nauthor_info['keyword'];
		} else {
			$data['keyword'] = '';
		}

		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($nauthor_info)) {
			$data['image'] = $nauthor_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && file_exists(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($nauthor_info) && $nauthor_info['image'] && file_exists(DIR_IMAGE . $nauthor_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($nauthor_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		
		$data['header'] = $this->load->controller('common/header');
		$data['newspanel'] = $this->load->controller('common/newspanel');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/nauthor_form.tpl', $data));
	}  

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/nauthor')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}
        if (utf8_strlen($this->request->post['keyword']) > 0) {
			$this->load->model('catalog/url_alias');

			$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

			if ($url_alias_info && isset($this->request->get['nauthor_id']) && $url_alias_info['query'] != 'nauthor_id=' . $this->request->get['nauthor_id']) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}

			if ($url_alias_info && !isset($this->request->get['nauthor_id'])) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}
		}
        
		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/nauthor')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('catalog/news');

		foreach ($this->request->post['selected'] as $nauthor_id) {
			$news_total = $this->model_catalog_news->getTotalNewsByAuthorId($nauthor_id);

			if ($news_total) {
				$this->error['warning'] = sprintf($this->language->get('error_product'), $news_total);
			}	
		}

		return !$this->error;
	}
}
?>
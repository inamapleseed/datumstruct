<?php
class ControllerCatalogNcomments extends Controller {
	private $error = array();
 
	public function index() {
		$this->language->load('catalog/ncomments');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/ncomments');
		
		$this->getList();
	} 

	public function insert() {
		$this->language->load('catalog/ncomments');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/ncomments');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_ncomments->addComment($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
						
			$this->response->redirect($this->url->link('catalog/ncomments', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('catalog/ncomments');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/ncomments');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_ncomments->editComment($this->request->get['ncomment_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
						
			$this->response->redirect($this->url->link('catalog/ncomments', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() { 
		$this->language->load('catalog/ncomments');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/ncomments');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $ncomment_id) {
				$this->model_catalog_ncomments->deleteComment($ncomment_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
						
			$this->response->redirect($this->url->link('catalog/ncomments', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	private function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'n.date_added';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
				
		$url = '';
			
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/ncomments', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		$data['insert'] = $this->url->link('catalog/ncomments/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('catalog/ncomments/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	

		$data['ncomments'] = array();

		$sdata = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$ncomments_total = $this->model_catalog_ncomments->getTotalComments();
	
		$results = $this->model_catalog_ncomments->getComments($sdata);
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('button_edit'),
				'href' => $this->url->link('catalog/ncomments/update', 'token=' . $this->session->data['token'] . '&ncomment_id=' . $result['ncomment_id'] . $url, 'SSL')
			);
						
			$data['ncomments'][] = array(
				'ncomment_id'  => $result['ncomment_id'],
				'name'       => $result['title'],
				'author'     => $result['author'],
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'   => isset($this->request->post['selected']) && in_array($result['ncomment_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}	
	
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['column_product'] = $this->language->get('column_product');
		$data['column_author'] = $this->language->get('column_author');
		$data['column_rating'] = $this->language->get('column_rating');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');	
        $data['npages'] = $this->url->link('catalog/news', 'token=' . $this->session->data['token'], 'SSL');		
		$data['gotonpages'] = $this->language->get('gotonpages');
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

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['sort_product'] = $this->url->link('catalog/ncomments', 'token=' . $this->session->data['token'] . '&sort=bd.name' . $url, 'SSL');
		$data['sort_author'] = $this->url->link('catalog/ncomments', 'token=' . $this->session->data['token'] . '&sort=n.author' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('catalog/ncomments', 'token=' . $this->session->data['token'] . '&sort=n.status' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('catalog/ncomments', 'token=' . $this->session->data['token'] . '&sort=n.date_added' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $ncomments_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/ncomments', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$data['pagination'] = $pagination->render();
		
		$data['results'] = sprintf($this->language->get('text_pagination'), ($ncomments_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($ncomments_total - $this->config->get('config_limit_admin'))) ? $ncomments_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $ncomments_total, ceil($ncomments_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;
	
		$data['header'] = $this->load->controller('common/header');
		$data['newspanel'] = $this->load->controller('common/newspanel');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/ncomments_list.tpl', $data));
	}

	private function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_select'] = $this->language->get('text_select');

		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_author'] = $this->language->get('entry_author');
		$data['entry_rating'] = $this->language->get('entry_rating');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_text'] = $this->language->get('entry_text');
		$data['entry_good'] = $this->language->get('entry_good');
		$data['entry_bad'] = $this->language->get('entry_bad');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
 		
		if (isset($this->error['article'])) {
			$data['error_article'] = $this->error['article'];
		} else {
			$data['error_article'] = '';
		}
		
 		if (isset($this->error['author'])) {
			$data['error_author'] = $this->error['author'];
		} else {
			$data['error_author'] = '';
		}
		
 		if (isset($this->error['text'])) {
			$data['error_text'] = $this->error['text'];
		} else {
			$data['error_text'] = '';
		}
		
 		if (isset($this->error['rating'])) {
			$data['error_rating'] = $this->error['rating'];
		} else {
			$data['error_rating'] = '';
		}

		$url = '';
			
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
				
   		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/ncomments', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
										
		if (!isset($this->request->get['ncomment_id'])) { 
			$data['action'] = $this->url->link('catalog/ncomments/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/ncomments/update', 'token=' . $this->session->data['token'] . '&ncomment_id=' . $this->request->get['ncomment_id'] . $url, 'SSL');
		}
		
		$data['cancel'] = $this->url->link('catalog/ncomments', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->get['ncomment_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$ncomment_info = $this->model_catalog_ncomments->getComment($this->request->get['ncomment_id']);
		}
			
		$this->load->model('catalog/news');
		$anews = $this->model_catalog_news->getNews();

		foreach ($anews as $result) {
							
			$data['anews'][] = array(
				'news_id' => $result['news_id'],
				'title'        => $result['title']
			);
		}
		
		if (isset($this->request->post['news_id'])) {
			$data['news_id'] = $this->request->post['news_id'];
		} elseif (isset($ncomment_info)) {
			$data['news_id'] = $ncomment_info['news_id'];
		} else {
			$data['news_id'] = '';
		}

		if (isset($this->request->post['article'])) {
			$data['article'] = $this->request->post['article'];
		} elseif (isset($ncomment_info)) {
			$data['article'] = $ncomment_info['article'];
		} else {
			$data['article'] = '';
		}
				
		if (isset($this->request->post['author'])) {
			$data['author'] = $this->request->post['author'];
		} elseif (isset($ncomment_info)) {
			$data['author'] = $ncomment_info['author'];
		} else {
			$data['author'] = '';
		}

		if (isset($this->request->post['text'])) {
			$data['text'] = $this->request->post['text'];
		} elseif (isset($ncomment_info)) {
			$data['text'] = $ncomment_info['text'];
		} else {
			$data['text'] = '';
		}
		if (isset($this->request->post['language_id'])) {
			$data['language_id'] = $this->request->post['language_id'];
		} elseif (isset($ncomment_info)) {
			$data['language_id'] = $ncomment_info['language_id'];
		} else {
			$data['language_id'] = '';
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
	  	
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (isset($ncomment_info)) {
			$data['status'] = $ncomment_info['status'];
		} else {
			$data['status'] = '';
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['newspanel'] = $this->load->controller('common/newspanel');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/ncomments_form.tpl', $data));
	}
	
	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/ncomments')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['news_id']) {
			$this->error['article'] = $this->language->get('error_product');
		}
		
		if ((strlen(utf8_decode($this->request->post['author'])) < 3) || (strlen(utf8_decode($this->request->post['author'])) > 64)) {
			$this->error['author'] = $this->language->get('error_author');
		}

		if (strlen(utf8_decode($this->request->post['text'])) < 1) {
			$this->error['text'] = $this->language->get('error_text');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/ncomments')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}	
}
?>
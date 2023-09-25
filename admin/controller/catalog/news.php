<?php
class ControllerCatalogNews extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('catalog/news');
		
		$this->load->model('catalog/ncomments');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/news');
		
		$this->getList();
	}

	public function insert() {
		$this->language->load('catalog/news');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/news');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_catalog_news->addNews($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			$url = '';
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			if ($this->config->get('news_archive_build')) {
				$this->buildArchive();
			}
			$this->response->redirect($this->url->link('catalog/news', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			
		}
		$this->getForm();
	}

	public function update() {
		$this->language->load('catalog/news');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/news');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm()) && $this->validateAuthor()) {
			$this->model_catalog_news->editNews($this->request->get['news_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			$url = '';
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			if ($this->config->get('news_archive_build')) {
				$this->buildArchive();
			}
			$this->response->redirect($this->url->link('catalog/news', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$this->getForm();
	}

	public function copy() { 
		$this->language->load('catalog/news');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/news');

		if (isset($this->request->post['delete']) && $this->validateCopy()) {
			foreach ($this->request->post['delete'] as $news_id) {
				$this->model_catalog_news->copyNews($news_id);
			}

			$this->session->data['success'] = $this->language->get('text_success_copy');

			$url = '';
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			$this->response->redirect($this->url->link('catalog/news', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}
	
	public function delete() {
		$this->language->load('catalog/news');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/news');
		
		if ((isset($this->request->post['delete'])) && ($this->validateDelete())) {
			foreach ($this->request->post['delete'] as $news_id) {
				$this->model_catalog_news->deleteNews($news_id);
			}
			
			$this->session->data['success'] = $this->language->get('text_success_delete');
			$url = '';
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			$this->response->redirect($this->url->link('catalog/news', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$this->getList();
	}

	private function getList() {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'n.date_added';
		}
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		$url = '';
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/news', 'token=' . $this->session->data['token'] . $url, 'SSL')
   		);
		
		$data['insert'] = $this->url->link('catalog/news/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		$data['npages'] = $this->url->link('module/news', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['tocomments'] = $this->url->link('catalog/ncomments', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['delete'] = $this->url->link('catalog/news/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	
		
		$data['copy'] = $this->url->link('catalog/news/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		$data['text_articles'] = $this->language->get('text_articles');
		
		$data['text_comtot'] = $this->language->get('text_comtot');
		
		$data['text_tcaa'] = $this->language->get('text_tcaa');
		
		$data['text_commod'] = $this->language->get('text_commod');
		
		$data['entry_nauthor'] = $this->language->get('entry_nauthor');
		
		$article_limit = $this->config->get('ncategory_bnews_admin_limit') ? $this->config->get('ncategory_bnews_admin_limit') : 20;
		
		$data['news'] = array();
		
		$sdata = array(
			'filter_name'   => $filter_name,
			'filter_status' => $filter_status,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $article_limit,
			'limit' => $article_limit
		);
		$news_total = $this->model_catalog_news->getTotalNews($sdata);
		
		$results = $this->model_catalog_news->getNewsLimited($sdata);
		
    	foreach ($results as $result) {
			$action = array();
			$action[] = array(
				'text' => $this->language->get('button_edit'),
				'href' => $this->url->link('catalog/news/update', 'token=' . $this->session->data['token'] . '&news_id=' . $result['news_id'] . $url, 'SSL')
				          
			);  
			if ($result['name']) { $authorlist = $result['name']; } else { $authorlist = "No author"; }
			$data['news'][] = array(
				'news_id'     => $result['news_id'],
				'title'       => $result['title'],
				'author'      => $authorlist,
				'status'	  => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'delete'      => isset($this->request->post['delete']) && in_array($result['news_id'], $this->request->post['delete']),
				'action'      => $action
			);
		}
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_enabled'] = $this->language->get('text_enabled');
		
		$data['text_confirm'] = $this->language->get('text_confirm');
		
		$data['entry_npages'] = $this->language->get('entry_npages');
		
		$data['text_disabled'] = $this->language->get('text_disabled');
		
		$data['text_no_results'] = $this->language->get('text_no_results');
		
		$data['column_title'] = $this->language->get('column_title');
		
		$data['column_status'] = $this->language->get('column_status');
		
		$data['column_action'] = $this->language->get('column_action');
		
		$data['button_insert'] = $this->language->get('button_insert');
		
		$data['button_delete'] = $this->language->get('button_delete');
		
		$data['button_copy'] = $this->language->get('button_copy');
		
		$data['button_filter'] = $this->language->get('button_filter');
		
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
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['sort_title'] = $this->url->link('catalog/news', 'token=' . $this->session->data['token'] . '&sort=nd.title' . $url, 'SSL');
		
		$data['sort_author'] = $this->url->link('catalog/news', 'token=' . $this->session->data['token'] . '&sort=na.name' . $url, 'SSL');
		                            
		$data['sort_sort_order'] = $this->url->link('catalog/news', 'token=' . $this->session->data['token'] . '&sort=n.sort_order' . $url, 'SSL');
		                                 
		$url = '';
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		$pagination = new Pagination();
		$pagination->total = $news_total;
		$pagination->page = $page;
		$pagination->limit = $article_limit; 
		$pagination->url = $this->url->link('catalog/news', 'token=' . $this->session->data['token'] . '&page={page}' . $url, 'SSL');;
		$data['pagination'] = $pagination->render();
		
		$data['results'] = sprintf($this->language->get('text_pagination'), ($news_total) ? (($page - 1) * $article_limit) + 1 : 0, ((($page - 1) * $article_limit) > ($news_total - $article_limit)) ? $news_total : ((($page - 1) * $article_limit) + $article_limit), $news_total, ceil($news_total / $article_limit));
		
		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['filter_name'] = $filter_name;
		$data['filter_status'] = $filter_status;
		$data['token'] = $this->session->data['token'];
		
		$data['header'] = $this->load->controller('common/header');
		$data['newspanel'] = $this->load->controller('common/newspanel');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/news_list.tpl', $data));
	}

	private function getForm() {

		$this->load->language('catalog/download');
		
		$data['entry_filename'] = $this->language->get('entry_filename');
		$data['entry_mask'] = $this->language->get('entry_mask');
		$data['entry_product_catalog'] = $this->language->get('entry_product_catalog');
		
		$data['help_filename'] = $this->language->get('help_filename');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['help_mask'] = $this->language->get('help_mask');

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

		$data['heading_title'] = $this->language->get('heading_titlei');
    	$data['text_enabled'] = $this->language->get('text_enabled');
    	$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_image_manager'] = $this->language->get('text_image_manager');
		$data['text_browse'] = $this->language->get('text_browse');
		$data['text_clear'] = $this->language->get('text_clear');
    	$data['text_fullsize'] = $this->language->get('text_fullsize');
    	$data['text_thumbnail'] = $this->language->get('text_thumbnail');
		$data['text_upload'] = $this->language->get('text_upload');
		$data['text_default'] = $this->language->get('text_default');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_description2'] = $this->language->get('entry_description2');
		$data['entry_addsdesc'] = $this->language->get('entry_addsdesc');
		$data['entry_meta_desc'] = $this->language->get('entry_meta_desc');
		$data['entry_meta_key'] = $this->language->get('entry_meta_key');
		$data['entry_ctitle'] = $this->language->get('entry_ctitle');
		$data['entry_ntags'] = $this->language->get('entry_ntags');
		$data['entry_nauthor'] = $this->language->get('entry_nauthor');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_image2'] = $this->language->get('entry_image2');
		$data['entry_datea'] = $this->language->get('entry_datea');
		$data['entry_dateu'] = $this->language->get('entry_dateu');
		$data['entry_cfield'] = $this->language->get('entry_cfield');
		$data['entry_image_size'] = $this->language->get('entry_image_size');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_data'] = $this->language->get('tab_data');
		$data['tab_seo'] = $this->language->get('tab_seo');
		$data['tab_settings'] = $this->language->get('tab_settings');
		$data['tab_image'] = $this->language->get('tab_image');
		$data['entry_category'] = $this->language->get('tab_category');
		$data['entry_store'] = $this->language->get('tab_store');
		$data['entry_groups'] = $this->language->get('entry_groups');
		$data['entry_layout'] = $this->language->get('tab_layout');
		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$data['entry_image'] = $this->language->get('entry_image');
    	$data['entry_acom'] = $this->language->get('entry_acom');
		$data['entry_related'] = $this->language->get('entry_related');
		$data['entry_nrelated'] = $this->language->get('entry_nrelated');
		$data['tab_related'] = $this->language->get('tab_related');
		$data['tab_custom'] = $this->language->get('tab_custom');
		$data['tab_gallery'] = $this->language->get('tab_gallery');
		$data['tab_video'] = $this->language->get('tab_video');
		$data['button_add_image'] = $this->language->get('button_add_image');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['entry_gallery_text'] = $this->language->get('entry_gallery_text');
		$data['entry_gallery_thumb'] = $this->language->get('entry_gallery_thumb');
		$data['entry_gallery_popup'] = $this->language->get('entry_gallery_popup');
		$data['entry_gallery_slider'] = $this->language->get('entry_gallery_slider');
		$data['entry_gallery_slidert'] = $this->language->get('entry_gallery_slidert');
		$data['entry_video_id'] = $this->language->get('entry_video_id');
		$data['entry_video_text'] = $this->language->get('entry_video_text');
		$data['entry_video_size'] = $this->language->get('entry_video_size');
		$data['button_add_video'] = $this->language->get('button_add_video');
		$data['entry_datep'] = $this->language->get('entry_datep');

		$data['bnews_html_editor'] = $this->config->get('ncategory_bnews_html_editor');
		
		$data['token'] = $this->session->data['token'];
		
	if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

 		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = '';
		}
		
	 	if (isset($this->error['description'])) {
			$data['error_description'] = $this->error['description'];
		} else {
			$data['error_description'] = '';
		}
        
		if (isset($this->error['keyword'])) {
			$data['error_keyword'] = $this->error['keyword'];
		} else {
			$data['error_keyword'] = '';
		}
		
		$url = '';
		
  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/news', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		if (!isset($this->request->get['news_id'])) {
			$data['action'] = $this->url->link('catalog/news/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
			
		} else {
			$data['action'] = $this->url->link('catalog/news/update', 'token=' . $this->session->data['token'] . '&news_id=' . $this->request->get['news_id'] . $url, 'SSL');
		                  
		}
		$data['cancel'] = $this->url->link('catalog/news', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		if ((isset($this->request->get['news_id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$news_info = $this->model_catalog_news->getNewsStory($this->request->get['news_id']);
		}
		
		$data['authors'] = array();

		$this->load->model('catalog/nauthor');
		
		$authors = $this->model_catalog_nauthor->getAuthors();
		
		if ($authors) {
			foreach ($authors as $author) {
				$authorvisibility = $author['adminid'];
				if ($authorvisibility === "0") {
					$data['authors'][] = array(
						'name'       => $author['name'],
						'nauthor_id' => $author['nauthor_id']
					);
				} elseif($authorvisibility == $this->user->getUserName()) {
					$data['authors'][] = array(
						'name'       => $author['name'],
						'nauthor_id' => $author['nauthor_id']
					);
				}
			}
		} 

		if (!$data['authors']) {
			$data['authors'][] = array(
						'name'       => 'none',
						'nauthor_id' => 0
			);
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
		
		if (isset($this->request->post['news_description'])) {
			$data['news_description'] = $this->request->post['news_description'];
		} elseif (isset($this->request->get['news_id'])) {
			$data['news_description'] = $this->model_catalog_news->getNewsDescriptions($this->request->get['news_id']);
		} else {
			$data['news_description'] = array();
		}
		if (isset($this->request->post['keyword'])) {
			$data['keyword'] = $this->request->post['keyword'];
		} elseif (isset($news_info)) {
			$data['keyword'] = $news_info['keyword'];
		} else {
			$data['keyword'] = '';
		}
		if (isset($this->request->post['acom'])) {
			$data['acom'] = $this->request->post['acom'];
		} elseif (isset($news_info)) {
			$data['acom'] = $news_info['acom'];
		} else {
			$data['acom'] = '';
		}
		if (isset($this->request->post['gal_thumb_w'])) {
			$data['gal_thumb_w'] = $this->request->post['gal_thumb_w'];
		} elseif (isset($news_info) && $news_info['gal_thumb_w']) {
			$data['gal_thumb_w'] = $news_info['gal_thumb_w'];
		} else {
			$data['gal_thumb_w'] = 150;
		}
		if (isset($this->request->post['gal_thumb_h'])) {
			$data['gal_thumb_h'] = $this->request->post['gal_thumb_h'];
		} elseif (isset($news_info) && $news_info['gal_thumb_h']) {
			$data['gal_thumb_h'] = $news_info['gal_thumb_h'];
		} else {
			$data['gal_thumb_h'] = 150;
		}
		if (isset($this->request->post['gal_popup_w'])) {
			$data['gal_popup_w'] = $this->request->post['gal_popup_w'];
		} elseif (isset($news_info) && $news_info['gal_popup_w']) {
			$data['gal_popup_w'] = $news_info['gal_popup_w'];
		} else {
			$data['gal_popup_w'] = 700;
		}
		if (isset($this->request->post['gal_popup_h'])) {
			$data['gal_popup_h'] = $this->request->post['gal_popup_h'];
		} elseif (isset($news_info) && $news_info['gal_popup_h']) {
			$data['gal_popup_h'] = $news_info['gal_popup_h'];
		} else {
			$data['gal_popup_h'] = 700;
		}
		if (isset($this->request->post['gal_slider_h'])) {
			$data['gal_slider_h'] = $this->request->post['gal_slider_h'];
		} elseif (isset($news_info) && $news_info['gal_slider_h']) {
			$data['gal_slider_h'] = $news_info['gal_slider_h'];
		} else {
			$data['gal_slider_h'] = 400;
		}
		if (isset($this->request->post['gal_slider_w'])) {
			$data['gal_slider_w'] = $this->request->post['gal_slider_w'];
		} elseif (isset($news_info) && $news_info['gal_slider_w']) {
			$data['gal_slider_w'] = $news_info['gal_slider_w'];
		} else {
			$data['gal_slider_w'] = 980;
		}
		if (isset($this->request->post['gal_slider_t'])) {
			$data['gal_slider_t'] = $this->request->post['gal_slider_t'];
		} elseif (isset($news_info) && $news_info['gal_slider_t']) {
			$data['gal_slider_t'] = $news_info['gal_slider_t'];
		} else {
			$data['gal_slider_t'] = 1;
		}
		
		if (isset($this->request->post['date_added'])) {
			$data['date_added'] = $this->request->post['date_added'];
		} elseif (isset($news_info)) {
			$data['date_added'] = $news_info['date_added'];
		} else {
			$data['date_added'] = date('Y-m-d H:i:s');
		}
		
		if (isset($this->request->post['date_updated'])) {
			$data['date_updated'] = $this->request->post['date_updated'];
		} elseif (isset($news_info)) {
			$data['date_updated'] = date('Y-m-d H:i:s');
		} else {
			$data['date_updated'] = date('Y-m-d H:i:s');
		}
		
		if (isset($this->request->post['date_pub'])) {
			$data['date_pub'] = $this->request->post['date_pub'];
		} elseif (isset($news_info)) {
			$data['date_pub'] = $news_info['date_pub'];
		} else {
			$data['date_pub'] = date('Y-m-d H:i:s',strtotime("-1 days"));
		}
		
		if (isset($this->request->post['acom'])) {
			$data['acom'] = $this->request->post['acom'];
		} elseif (isset($news_info)) {
			$data['acom'] = $news_info['acom'];
		} else {
			$data['acom'] = '';
		}
		if (isset($this->request->post['nauthor_id'])) {
			$data['nauthor_id'] = $this->request->post['nauthor_id'];
		} elseif (isset($news_info)) {
			$data['nauthor_id'] = $news_info['nauthor_id'];
		} else {
			$data['nauthor_id'] = '';
		}
		if (isset($this->request->post['sort_order'])) {
      		$data['sort_order'] = $this->request->post['sort_order'];
    	} elseif (isset($news_info)) {
      		$data['sort_order'] = $news_info['sort_order'];
    	} else {
			$data['sort_order'] = 1;
		}
		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($news_info)) {
			$data['image'] = $news_info['image'];
		} else {
			$data['image'] = '';
		}
		if (isset($this->request->post['image2'])) {
			$data['image2'] = $this->request->post['image2'];
		} elseif (!empty($news_info)) {
			$data['image2'] = $news_info['image2'];
		} else {
			$data['image2'] = '';
		}
		
		$this->load->model('tool/image');
		
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		
		if (!empty($news_info) && $news_info['image'] && file_exists(DIR_IMAGE . $news_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($news_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		
		if (!empty($news_info) && $news_info['image2'] && file_exists(DIR_IMAGE . $news_info['image2'])) {
			$data['thumb2'] = $this->model_tool_image->resize($news_info['image2'], 100, 100);
		} else {
			$data['thumb2'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		$this->load->model('setting/store');
		
		$data['stores'] = $this->model_setting_store->getStores();
		
		if (isset($this->request->post['news_store'])) {
			$data['news_store'] = $this->request->post['news_store'];
		} elseif (isset($this->request->get['news_id'])) {
			$data['news_store'] = $this->model_catalog_news->getNewsStores($this->request->get['news_id']);
		} else {
			$data['news_store'] = array(0);
		}	
		if (version_compare(VERSION, '2.1.0.0') >= 0 || VERSION =='2.1.0.0_rc1') {
			$this->load->model('customer/customer_group');

			$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
		} else {
			$this->load->model('sale/customer_group');

			$data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		}

		if (isset($this->request->post['news_group'])) {
			$data['news_group'] = $this->request->post['news_group'];
		} elseif (isset($this->request->get['news_id'])) {
			$data['news_group'] = $this->model_catalog_news->getNewsGroups($this->request->get['news_id']);
		} else {
			$ng=array(); for($i=1; $i<151; $i++) $ng[] = $i;
			$data['news_group'] = $ng;
		}	
		
		$this->load->model('catalog/ncategory');
				
		$data['ncategories'] = $this->model_catalog_ncategory->getncategories(0);
		
		if (isset($this->request->post['news_ncategory'])) {
			$data['news_ncategory'] = $this->request->post['news_ncategory'];
		} elseif (isset($this->request->get['news_id'])) {
			$data['news_ncategory'] = $this->model_catalog_news->getNewsNcategories($this->request->get['news_id']);
		} else {
			$data['news_ncategory'] = array();
		}	
		
		if (isset($this->request->post['news_nrelated'])) {
			$nrelated = $this->request->post['news_nrelated'];
		} elseif (isset($this->request->get['news_id'])) {		
			$nrelated = $this->model_catalog_news->getNewsNrelated($this->request->get['news_id']);
		} else {
			$nrelated = array();
		}
		
		$data['news_nrelated'] = array();
		foreach ($nrelated as $narticle_id) {
			$article_related_info = $this->model_catalog_news->getNewsStory($narticle_id);
			
			if ($article_related_info) {
				$data['news_nrelated'][] = array(
					'news_id' => $article_related_info['news_id'],
					'title'       => $article_related_info['title']
				);
			}
		}
		
		if (isset($this->request->post['news_related'])) {
			$products = $this->request->post['news_related'];
		} elseif (isset($this->request->get['news_id'])) {		
			$products = $this->model_catalog_news->getNewsRelated($this->request->get['news_id']);
		} else {
			$products = array();
		}
	
		$data['news_related'] = array();
		$this->load->model('catalog/product');
		foreach ($products as $product_id) {
			$related_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($related_info) {
				$data['news_related'][] = array(
					'product_id' => $related_info['product_id'],
					'name'       => $related_info['name']
				);
			}
		}
		
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		}  elseif (isset($news_info)) {
			$data['status'] = $news_info['status'];
		} else {
      		$data['status'] = 1;
    	}
		if (isset($this->request->post['news_layout'])) {
			$data['news_layout'] = $this->request->post['news_layout'];
		} elseif (isset($this->request->get['news_id'])) {
			$data['news_layout'] = $this->model_catalog_news->getNewsLayouts($this->request->get['news_id']);
		} else {
			$data['news_layout'] = array();
		}
		
		// Gallery
		if (isset($this->request->post['news_gallery'])) {
			$news_gallery = $this->request->post['news_gallery'];
		} elseif (isset($this->request->get['news_id'])) {
			$news_gallery = $this->model_catalog_news->getArticleImages($this->request->get['news_id']);
		} else {
			$news_gallery = array();
		}

		$data['news_gallery'] = array();

		foreach ($news_gallery as $news_image) {
			if ($news_image['image'] && file_exists(DIR_IMAGE . $news_image['image'])) {
				$image = $news_image['image'];
			} else {
				$image = 'no_image.jpg';
			}

			$data['news_gallery'][] = array(
				'image'      => $image,
				'thumb'      => $this->model_tool_image->resize($image, 100, 100),
				'text'       => $news_image['text'],
				'sort_order' => $news_image['sort_order']
			);
		}
		
		// Videos
		if (isset($this->request->post['news_video'])) {
			$news_video = $this->request->post['news_video'];
		} elseif (isset($this->request->get['news_id'])) {
			$news_video = $this->model_catalog_news->getArticleVideos($this->request->get['news_id']);
		} else {
			$news_video = array();
		}

		$data['news_video'] = array();

		foreach ($news_video as $video) {
			$data['news_video'][] = array(
				'text'       => $video['text'],
				'video'      => $video['video'],
				'width'      => $video['width'],
				'height'     => $video['height'],
				'sort_order' => $video['sort_order']
			);
		}

		$this->load->model('design/layout');
		
		$data['layouts'] = $this->model_design_layout->getLayouts();
			
		$data['header'] = $this->load->controller('common/header');
		$data['newspanel'] = $this->load->controller('common/newspanel');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		if(isset($this->request->get['news_id'])){
			$brochure_info = $this->model_catalog_news->getBrochure($this->request->get['news_id']);
		}
		

		if (isset($this->request->post['brochure_pdf_id'])) {
			$data['brochure_pdf_id'] = $this->request->post['brochure_pdf_id'];
		} elseif (!empty($brochure_info)) {
			$data['brochure_pdf_id'] = $brochure_info['brochure_pdf_id'];
		} else {
			$data['brochure_pdf_id'] = '';
		}
		if (isset($this->request->post['brochure_id'])) {
			$data['brochure_id'] = $this->request->post['brochure_id'];
		} elseif (!empty($brochure_info)) {
			$data['brochure_id'] = $brochure_info['brochure_id'];
		} else {
			$data['brochure_id'] = '';
		}
	
		if (isset($this->request->post['filename'])) {
					$data['filename'] = $this->request->post['filename'];
		} elseif (!empty($brochure_info)) {
			$data['filename'] = $brochure_info['filename'];
		} else {
			$data['filename'] = '';
		}

		if (isset($this->request->post['mask'])) {
			$data['mask'] = $this->request->post['mask'];
		} elseif (!empty($brochure_info)) {
			$data['mask'] = $brochure_info['mask'];
		} else {
			$data['mask'] = ''; 
		}

		$this->response->setOutput($this->load->view('catalog/news_form.tpl', $data));
	}
	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/news')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		foreach ($this->request->post['news_description'] as $language_id => $value) {
			if ((strlen($value['title']) < 3) || (strlen($value['title']) >100)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
			if (strlen($value['description']) < 3) {
				$this->error['description'][$language_id] = $this->language->get('error_description');
			}
		}
        if (utf8_strlen($this->request->post['keyword']) > 0) {
			$this->load->model('catalog/url_alias');

			$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

			if ($url_alias_info && isset($this->request->get['news_id']) && $url_alias_info['query'] != 'news_id=' . $this->request->get['news_id']) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}

			if ($url_alias_info && !isset($this->request->get['news_id'])) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}
		}
        
		return !$this->error;
	}
	
	private function validateAuthor() {
		$this->load->model('catalog/news');
		$this->load->model('catalog/nauthor');
		
		$author_id = $this->model_catalog_news->getAuthorIdbyArticle($this->request->get['news_id']);
		
		if ($author_id ) {
			$adminid = $this->model_catalog_nauthor->getAuthorAdminID($author_id);
			if ($adminid != $this->user->getUserName() && $adminid !== "0") {
				$this->error['warning'] = 'You are not the author of this article so you cant edit it!';
			}
		}
		
		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/news')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
        
		if (!$this->error) {
			$this->load->model('catalog/news');
			$this->load->model('catalog/nauthor');
			foreach ($this->request->post['delete'] as $news_id) {
		
				$author_id = $this->model_catalog_news->getAuthorIdbyArticle($news_id);
		
				if ($author_id ) {
					$adminid = $this->model_catalog_nauthor->getAuthorAdminID($author_id);
					if ($adminid != $this->user->getUserName() && $adminid !== "0") {
						$this->error['warning'] = 'You are not the author of all the articles you are trying to delete!';
					}
				}
				
				if ($this->error) break;
			}
		}
        
		return !$this->error;
	}
	protected function validateCopy() {
		if (!$this->user->hasPermission('modify', 'catalog/news')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_aname'])) {
			$this->load->model('catalog/news');

			if (isset($this->request->get['filter_aname'])) {
				$filter_name = $this->request->get['filter_aname'];
			} else {
				$filter_name = '';
			}

			$data = array(
				'filter_name'  => $filter_name,
				'start'        => 0,
				'limit'        => 20
			);

			$results = $this->model_catalog_news->getNewsLimited($data);

			foreach ($results as $result) {
				
				$json[] = array(
					'news_id'    => $result['news_id'],
					'title'      => strip_tags(html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8'))
				);	
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	private function buildArchive() {
	  if ($this->validateCopy()) {
		  
		$this->load->model('catalog/news');
		
		$this->load->model('setting/store');

		if (version_compare(VERSION, '2.1.0.0') >= 0 || VERSION =='2.1.0.0_rc1') {
			$this->load->model('customer/customer_group');

			$customer_groups = $this->model_customer_customer_group->getCustomerGroups();
		} else {
			$this->load->model('sale/customer_group');

			$customer_groups = $this->model_sale_customer_group->getCustomerGroups();
		}

		$restr = $this->config->get('ncategory_bnews_restrictgroup');
		
		$stores = $this->model_setting_store->getStores();
		
		$this->language->load('module/news_archive');
		  
		if ($this->config->get('news_archive_date') == "dp") {
			$articles = $this->model_catalog_news->getNewsDP(); //get all articles with date published
			$field = 'date_pub'; // archive grouping field set to date_pub
		} elseif($this->config->get('news_archive_date') == "du") {
			$articles = $this->model_catalog_news->getNewsDU(); //get all articles with date updated
			$field = 'date_updated'; // archive grouping field set to date update
		} else {
			$articles = $this->model_catalog_news->getNewsDA(); //get all articles
			$field = 'date_added'; // archive grouping field
		}
		
		
		$this->model_catalog_news->deleteArchive(); //clear the archive
		
		
		
		/*for default store*/
		$archive_data = array();
		
if ($restr) {  

	foreach($customer_groups as $group) {
		$archive_data = array();
		foreach ($articles as $article) {
		  if ($article['store_id'] == 0) {
		  if ($article['group_id'] == $group['customer_group_id']) {
			$date = strtotime($article[$field]);
			$year = date("Y",$date);
			$month = (int)date("m",$date);
			if (!array_key_exists($year, $archive_data)) {
				$archive_data[$year] = array(
					'year' => $year,
					'months' => array()
				);
			}
			if(!array_key_exists($month, $archive_data[$year]['months'])) {
				$archive_data[$year]['months'][$month] = 1;
			} else {
				$archive_data[$year]['months'][$month]++;
			}
		  }
		  }
		}
		
		if ($archive_data) {
			$rid =  10000 + ($group['customer_group_id'] * 10);
			foreach ($archive_data as $archive) {
				$this->model_catalog_news->addArchiveYear($archive, $rid);
			}
		}
		/*end of default store*/
	}
		
		/*start aditional stores*/
		foreach ($stores as $store) {
		foreach($customer_groups as $group) {
			$archive_data = array();
			foreach ($articles as $article) {
		  	  if ($article['store_id'] == $store['store_id']) {
		  	  if ($article['group_id'] == $group['customer_group_id']) {
				$date = strtotime($article[$field]);
				$year = date("Y",$date);
				$month = (int)date("m",$date);
				if (!array_key_exists($year, $archive_data)) {
					$archive_data[$year] = array(
						'year' => $year,
						'months' => array()
					);
				}
				if(!array_key_exists($month, $archive_data[$year]['months'])) {
					$archive_data[$year]['months'][$month] = 1;
				} else {
					$archive_data[$year]['months'][$month]++;
				}
			  }
			  }
		  	}
		
			if ($archive_data) {
				$rid =  (($store['store_id']+1) * 10000) + ($group['customer_group_id'] * 10);
				foreach ($archive_data as $archive) {
					$this->model_catalog_news->addArchiveYear($archive, $rid);
				}
			}
		}
		}
} else {
		foreach ($articles as $article) {
		  if ($article['store_id'] == 0) {
			$date = strtotime($article[$field]);
			$year = date("Y",$date);
			$month = (int)date("m",$date);
			if (!array_key_exists($year, $archive_data)) {
				$archive_data[$year] = array(
					'year' => $year,
					'months' => array()
				);
			}
			if(!array_key_exists($month, $archive_data[$year]['months'])) {
				$archive_data[$year]['months'][$month] = 1;
			} else {
				$archive_data[$year]['months'][$month]++;
			}
		  }
		}
		
		if ($archive_data) {
			foreach ($archive_data as $archive) {
				$this->model_catalog_news->addArchiveYear($archive);
			}
		}
		/*end of default store*/
		
		/*start aditional stores*/
		foreach ($stores as $store) {
			$archive_data = array();
			foreach ($articles as $article) {
		  	  if ($article['store_id'] == $store['store_id']) {
				$date = strtotime($article[$field]);
				$year = date("Y",$date);
				$month = (int)date("m",$date);
				if (!array_key_exists($year, $archive_data)) {
					$archive_data[$year] = array(
						'year' => $year,
						'months' => array()
					);
				}
				if(!array_key_exists($month, $archive_data[$year]['months'])) {
					$archive_data[$year]['months'][$month] = 1;
				} else {
					$archive_data[$year]['months'][$month]++;
				}
			  }
		  	}
		
			if ($archive_data) {
				foreach ($archive_data as $archive) {
					$this->model_catalog_news->addArchiveYear($archive, (int)$store['store_id']);
				}
			}
		}
}
		/*end of additional stores*/
	
		$this->session->data['success'] .= '<br />Archive Index Created!';
	
      } else {
	
		$this->session->data['warning'] .= '<br />You don\'t have permissions to modify the blog!';
	
	  }
	}
}
?>

<?php
class ControllerCatalogPagebanner extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/page_banner');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/page_banner');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/page_banner');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/page_banner');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_page_banner->addPageBanner($this->request->post);

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

			$this->response->redirect($this->url->link('catalog/page_banner', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/page_banner');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/page_banner');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_page_banner->editPageBanner($this->request->get['pb_id'], $this->request->post);

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

			$this->response->redirect($this->url->link('catalog/page_banner', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/page_banner');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/page_banner');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $pb_id) {
				$this->model_catalog_page_banner->deletePageBanner($pb_id);
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

			$this->response->redirect($this->url->link('catalog/page_banner', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'page_name';
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
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/page_banner', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('catalog/page_banner/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('catalog/page_banner/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['banners'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$banner_total = $this->model_catalog_page_banner->getTotalPageBanners();

		$results = $this->model_catalog_page_banner->getPageBanners($filter_data);

		foreach ($results as $result) {
			$data['banners'][] = array(
				'pb_id' => $result['pb_id'],
				'page_name' => $result['page_name'],
				'status'    => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'edit'      => $this->url->link('catalog/page_banner/edit', 'token=' . $this->session->data['token'] . '&pb_id=' . $result['pb_id'] . $url, true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
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

		$data['sort_name'] = $this->url->link('catalog/page_banner', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_status'] = $this->url->link('catalog/page_banner', 'token=' . $this->session->data['token'] . '&sort=status' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $banner_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/page_banner', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($banner_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($banner_total - $this->config->get('config_limit_admin'))) ? $banner_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $banner_total, ceil($banner_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/page_banner_list', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['pb_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_default'] = $this->language->get('text_default');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_link'] = $this->language->get('entry_link');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_banner_add'] = $this->language->get('button_banner_add');
		$data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['page_name'])) {
			$data['error_name'] = $this->error['page_name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['banner_image'])) {
			$data['error_banner_image'] = $this->error['banner_image'];
		} else {
			$data['error_banner_image'] = array();
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
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/page_banner', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['pb_id'])) {
			$data['action'] = $this->url->link('catalog/page_banner/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('catalog/page_banner/edit', 'token=' . $this->session->data['token'] . '&pb_id=' . $this->request->get['pb_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('catalog/page_banner', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['pb_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$banner_info = $this->model_catalog_page_banner->getPageBanner($this->request->get['pb_id']);
		}

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->post['page_name'])) {
			$data['page_name'] = $this->request->post['page_name'];
		} elseif (!empty($banner_info)) {
			$data['page_name'] = $banner_info['page_name'];
		} else {
			$data['page_name'] = '';
		}

		$this->load->model('tool/image');


		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($banner_info)) {
			$data['image'] = $banner_info['image'];
		} else {
			$data['image'] = '';
		}

		$data['image_thumb'] = $data['placeholder'];
		if(is_file( DIR_IMAGE . $data['image']) ){
			$data['image_thumb'] = $this->model_tool_image->resize($data['image'], 100, 100);
		}

		if (isset($this->request->post['mobile_image'])) {
			$data['mobile_image'] = $this->request->post['mobile_image'];
		} elseif (!empty($banner_info)) {
			$data['mobile_image'] = $banner_info['mobile_image'];
		} else {
			$data['mobile_image'] = '';
		}

		$data['mobile_image_thumb'] = $data['placeholder'];
		if(is_file( DIR_IMAGE . $data['mobile_image']) ){
			$data['mobile_image_thumb'] = $this->model_tool_image->resize($data['mobile_image'], 100, 100);
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($banner_info)) {
			$data['status'] = $banner_info['status'];
		} else {
			$data['status'] = true;
		}

		if (isset($this->request->post['route'])) {
			$data['route'] = $this->request->post['route'];
		} elseif (!empty($banner_info)) {
			$data['route'] = $banner_info['route'];
		} else {
			$data['route'] = '';
		}

		if (isset($this->request->post['query'])) {
			$data['query'] = $this->request->post['query'];
		} elseif (!empty($banner_info)) {
			$data['query'] = $banner_info['query'];
		} else {
			$data['query'] = '';
		}

		$data['value'] = $data['route'] . '-' . $data['query']; //debug($data['value']);

		$data['route_sets'] = $this->getRouteSets(); // debug($data['route_sets']);

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/page_banner_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/page_banner')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['page_name']) < 3) || (utf8_strlen($this->request->post['page_name']) > 64)) {
			$this->error['page_name'] = $this->language->get('error_name');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/page_banner')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function getRouteSets(){
		$data = array();

		// Static
		$static = array(
			'all' 						=> 'All Pages (Exclude Home Page)',

			'account/%'					=>	'All Account (Dashboard / Register / Login / Etc)',
			
			'product/special'			=>	'Special',
			'product/search'			=>	'Search',
			'product/compare'			=>	'Compare',

			'information/contact'		=>	'Contact Us',
			'testimonial/testimonial'	=>	'Testimonials',
			'information/sitemap'		=>	'Site Map',
			'information/faq'			=>	'Faqs',

			'checkout/%'				=>	'Cart',
			'quickcheckout/%'			=>	'Checkout / Quickcheckout',
		);

		$static_array = array();

		foreach($static as $route => $name){
			$static_array[] = array(
				'name'	=>	$name,
				'route' =>	$route,
				'query' =>	'*',
				'value' =>	$route . '-*',
			);
		}

		if($static_array) {
			$data[] = array(
				'optgroup'	=>	'Static',
				'options'	=>	$static_array
			);
		}
		
		// Information
		$info_array = array();
		$info_array[] = array(
			'name'	=>	'All Information',
			'route' =>	'information/information',
			'query' =>	'*',
			'value' => 	'information/information-*',
		); $informations = $this->getInformations();

		foreach($informations as $information) { 
			$info_array[] = array(
				'name'	=>	$information['name'],
				'route' =>	'information/information',
				'query'	=>	'information_id=' . $information['information_id'],
				'value'	=>	'information/information-' . 'information_id=' . $information['information_id'],
			);
		}

		$data[] = array(
			'optgroup'	=>	'Information',
			'options'	=>	$info_array
		);

		// Category
		$category_array = array();
		$category_array[] = array(
			'name'	=>	'All Category',
			'route' =>	'product/category',
			'query' =>	'*',
			'value' =>	'product/category-*'
		); $categories = $this->getCategories();

		foreach($categories as $category){
			$category_array[] = array(
				'name'	=>	$category['name'],
				'route' =>	'product/category',
				'query'	=>	'path=' . $category['category_id'],
				'value'	=>	'product/category-' . 'path=' . $category['category_id'],
			);
		}

		$data[] = array(
			'optgroup'	=>	'Category',
			'options'	=>	$category_array
		);

		// News / Blog Category
		$news_array = array();
		$news_array[] = array(
			'name'	=>	'Brochures Banner',
			'route' =>	'news/ncategory',
			'query' =>	'*',
			'value'	=>	'news/ncategory-*',
		); $ncategories = $this->getNcategories();

		foreach($ncategories as $ncategory){ 
			$news_array[] = array(
				'name'	=>	$ncategory['name'],
				'route' =>	'news/ncategory',
				'query'	=>	'ncat=' . $ncategory['ncategory_id'],
				'value'	=>	'news/ncategory-' . 'ncat=' . $ncategory['ncategory_id'],
			);
		}

		$data[] = array(
			'optgroup'	=>	'News / Blogs',
			'options'	=>	$news_array
		);

		// News / Blog Article
		$article_array = array();
		$article_array[] = array(
			'name'	=>	'All News / Blog Article',
			'route' =>	'news/article',
			'query' =>	'*',
			'value'	=>	'news/article-*',
		); $article = $this->getArticle();

		//debug($article);

		$data[] = array(
			'optgroup'	=>	'News / Blogs Articles',
			'options'	=>	$article_array
		);

		// Products
		$products_array = array();
		$products_array[] = array(
			'name'	=>	'All Products',
			'route' =>	'product/product',
			'query' =>	'*',
			'value'	=>	'product/product-*',
		); 

		/*
		$product_results = $this->getProducts();

		foreach($product_results as $product){ 
			$products_array[] = array(
				'name'	=>	$product['name'],
				'route' =>	'product/product',
				'query'	=>	'product_id=' . $product['product_id'],
				'value'	=>	'product/product-' . 'product_id=' . $product['product_id'],
			);
		}
		*/
		$data[] = array(
			'optgroup'	=>	'Products',
			'options'	=>	$products_array
		);

		return $data;
	}

	public function getCategories(){
		$query = $this->db->query('SELECT DISTINCT category_id, name FROM `'. DB_PREFIX . 'category_description` WHERE language_id="'.(int)$this->config->get('config_language_id').'" ORDER BY name ASC');
		return $query->rows;
	}

	public function getInformations(){
		$query = $this->db->query('SELECT DISTINCT information_id, title as name FROM `'. DB_PREFIX . 'information_description` WHERE language_id="'.(int)$this->config->get('config_language_id').'" ORDER BY title ASC');
		return $query->rows;
	}

	public function getNcategories(){
		$query = $this->db->query('SELECT DISTINCT ncategory_id, name FROM `'. DB_PREFIX . 'sb_ncategory_description` WHERE language_id="'.(int)$this->config->get('config_language_id').'" ORDER BY name ASC');
		return $query->rows;
	}

	public function getArticle(){
		$query = $this->db->query('SELECT DISTINCT news_id, title as name FROM `'. DB_PREFIX . 'sb_news_description` WHERE language_id="'.(int)$this->config->get('config_language_id').'" ORDER BY title ASC');
		return $query->rows;
	}

	public function getProducts(){
		$query = $this->db->query('SELECT DISTINCT product_id, name FROM `'. DB_PREFIX . 'product_description` WHERE language_id="'.(int)$this->config->get('config_language_id').'" ORDER BY name ASC');
		return $query->rows;
	}
}
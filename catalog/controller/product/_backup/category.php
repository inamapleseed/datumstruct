<?php
	class ControllerProductCategory extends Controller {
		public function index() {
			$this->load->language('product/category');
			
			$this->load->model('catalog/category');
			
			$this->load->model('catalog/product');
			
			$this->load->model('tool/image');
			
			$filter = '';
			$sort = 'p.sort_order';
			$order = 'ASC';
			$page = 1;
			$theme = $this->config->get('config_theme');

			$limit = $this->config->get($theme . '_product_limit');

			$manufacturer_ids = array();
			
			$price_min = 0;
			$price_max = 0;
			
			if (isset($this->request->get['filter'])) 
			$filter = $this->request->get['filter'];
			
			if (isset($this->request->get['sort'])) 
			$sort = $this->request->get['sort'];
			
			if (isset($this->request->get['order'])) 
			$order = $this->request->get['order'];
			
			if (isset($this->request->get['page']) && (int)$this->request->get['page']) 
			$page = (int)$this->request->get['page'];
			
			if (isset($this->request->get['limit']) && (int)$this->request->get['limit']) 
			$limit = (int)$this->request->get['limit'];

			if (isset($this->request->get['price_min'])) 
			$price_min = (float)$this->request->get['price_min'];

			if (isset($this->request->get['price_max'])) 
			$price_max = (float)$this->request->get['price_max'];

			if (isset($this->request->get['manufacturer_ids']) && is_array($this->request->get['manufacturer_ids'])) {
				// Clean 
				foreach($this->request->get['manufacturer_ids'] as $index => $each){
					if((int)$each < 1){
						unset($this->request->get['manufacturer_ids'][$index]);
					}else{
						$this->request->get['manufacturer_ids'][$index] = (int)$each;
					}
				}

				$this->request->get['manufacturer_ids']  = array_values($this->request->get['manufacturer_ids']);

				$manufacturer_ids = $this->request->get['manufacturer_ids'];
			}
			
			$data['breadcrumbs'] = array();
			
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);
			
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_product'),
				'href' => $this->url->link('product/category')
			);
			
			$category_id = 0;

			$url = '';
			
			if (isset($this->request->get['sort'])) 
			$url .= '&sort=' . $this->request->get['sort'];
			
			if (isset($this->request->get['order'])) 
			$url .= '&order=' . $this->request->get['order'];
			
			if (isset($this->request->get['limit']))
			$url .= '&limit=' . $this->request->get['limit'];

			if($manufacturer_ids){
				foreach($manufacturer_ids as $each_id){
					$url .= '&manufacturer_ids[]=' . $each_id;
				}
			}

			if ($price_min) 
			$url .= '&price_min=' . $this->request->get['price_min'];

			if ($price_max) 
			$url .= '&price_max=' . $this->request->get['price_max'];

			/*
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_products'),
				'href' => $this->url->link('product/category'.$url)
			);
			*/

			if (isset($this->request->get['path'])) {
				
				$path = '';
				
				$parts = explode('_', (string)$this->request->get['path']);
				
				$category_id = (int)array_pop($parts);
				
				foreach ($parts as $path_id) {
					if (!$path) {
						$path = (int)$path_id;
					} 
					else {
						$path .= '_' . (int)$path_id;
					}
					
					$category_info = $this->model_catalog_category->getCategory($path_id);
					
					if ($category_info) {
						$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'path=' . $path . $url)
						);
					}
				}
			}

			$data['text_refine'] = $this->language->get('text_refine');
			$data['text_empty'] = $this->language->get('text_empty');
			$data['text_quantity'] = $this->language->get('text_quantity');
			$data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$data['text_model'] = $this->language->get('text_model');
			$data['text_price'] = $this->language->get('text_price');
			$data['text_tax'] = $this->language->get('text_tax');
			$data['text_points'] = $this->language->get('text_points');
			$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
			$data['text_sort'] = $this->language->get('text_sort');
			$data['text_limit'] = $this->language->get('text_limit');
			
			$data['button_cart'] = $this->language->get('button_cart');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare'] = $this->language->get('button_compare');
			$data['button_continue'] = $this->language->get('button_continue');
			$data['button_list'] = $this->language->get('button_list');
			$data['button_grid'] = $this->language->get('button_grid');

			$category_info = $this->model_catalog_category->getCategory($category_id);
			
			$data['heading_title'] = $this->language->get('text_products');
			$data['thumb'] = '';
			$data['description'] = '';
			$data['compare'] = $this->url->link('product/compare');
			$data['categories'] = array();

			$category_path='';

			if ($category_info) { 

				$this->document->setTitle($category_info['meta_title']);
				$this->document->setDescription($category_info['meta_description']);
				$this->document->setKeywords($category_info['meta_keyword']);
				
				$data['heading_title'] = $category_info['name'];
				
				// Set the last category breadcrumb
				$data['breadcrumbs'][] = array(
				'text' => $category_info['name'],
				'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url)
				);
				
				if ($category_info['image']) {
					$data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get($theme . '_image_category_width'), $this->config->get($theme . '_image_category_height'));
				}
				
				$data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
				
				$url = '';
				
				if (isset($this->request->get['filter'])) {
					$url .= '&filter=' . $this->request->get['filter'];
				}
				
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				
				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}

				if($manufacturer_ids){
					foreach($manufacturer_ids as $each_id){
						$url .= '&manufacturer_ids[]=' . $each_id;
					}
				}
				
				if ($price_min) 
				$url .= '&price_min=' . $this->request->get['price_min'];

				if ($price_max) 
				$url .= '&price_max=' . $this->request->get['price_max'];
				
				$results = $this->model_catalog_category->getCategories($category_id);
				
				foreach ($results as $result) {
					$filter_data = array(
					'filter_category_id'  => $result['category_id'],
					'filter_sub_category' => true
					);
					
					$data['categories'][] = array(
					'name' => $result['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
					'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url)
					);
				}
			}
			else{
				$this->document->setTitle($this->language->get('text_products'));
				$this->document->setDescription($this->config->get('config_meta_description'));
				$this->document->setKeywords($this->config->get('config_meta_keyword'));
			}
				
			$data['products'] = array();
			
			$filter_data = array(
				'filter_category_id' => (int)$category_id,
				'filter_manufacturer'=> $manufacturer_ids,
				'filter_sub_category'=> true,
				'filter_special'	 => false,
				'price_min'			 => $price_min,
				'price_max'			 => $price_max,
				'filter_filter'      => $filter,
				'sort'               => $sort,
				'order'              => $order,
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit,				
			);
				
			$product_total = $this->model_catalog_product->getTotalProducts($filter_data);
				
			$results = $this->model_catalog_product->getProducts($filter_data);
	
			if($category_info){
				$this->facebookcommonutils = new FacebookCommonUtils();
				$params = new DAPixelConfigParams(array(
				'eventName' => 'ViewCategory',
				'products' => $results,
				'currency' => $this->currency,
				'currencyCode' => $this->session->data['currency'],
				'hasQuantity' => false,
				'isCustomEvent' => false,
				'paramNameUsedInProductListing' => 'content_category',
				'paramValueUsedInProductListing' => $category_info['name']));
				$facebook_pixel_event_params_FAE =
				$this->facebookcommonutils->getDAPixelParamsForProductListing($params);
				// stores the pixel params in the session
				$this->request->post['facebook_pixel_event_params_FAE'] =
				addslashes(json_encode($facebook_pixel_event_params_FAE));
			}
			else{

				// Default category
				$this->facebookcommonutils = new FacebookCommonUtils();
				$params = new DAPixelConfigParams(array(
				'eventName' => 'ViewCategory',
				'products' => $results,
				'currency' => $this->currency,
				'currencyCode' => $this->session->data['currency'],
				'hasQuantity' => false,
				'isCustomEvent' => false,
				'paramNameUsedInProductListing' => 'content_category',
				'paramValueUsedInProductListing' => $this->language->get('text_products')));
				$facebook_pixel_event_params_FAE =
				$this->facebookcommonutils->getDAPixelParamsForProductListing($params);
				// stores the pixel params in the session
				$this->request->post['facebook_pixel_event_params_FAE'] =
				addslashes(json_encode($facebook_pixel_event_params_FAE));
				
			}
				
			foreach ($results as $result) {
				$data['products'][] = $this->load->controller('component/product_info', $result['product_id']);
			}
			
			// Sort
			
			$type_of_sort = array(
				'name'	=>	'pd.name',
				'price'	=>	'p.price',
				'price'	=>	'p.price',
				'model'	=>	'p.model',
			);

			if ($this->config->get('config_review_status')) {
				$type_of_sort['rating'] = 'rating';
			}

			$data['sorts'] = array();

			$path_url = '';

			if($category_id){
				$path_url = 'path=' . $category_id .'&';
			}

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_default_asc'),
				'value' => 'sort_order-ASC',
			);

			foreach($type_of_sort as $type => $column){
				$data['sorts'][] = array(
					'text'  => $this->language->get('text_' . $type . '_asc'),
					'value' => $column.'-ASC',
				);
				$data['sorts'][] = array(
					'text'  => $this->language->get('text_' . $type . '_desc'),
					'value' => $column.'-DESC',
				);
			}
			// End Sort

			// Limit
			$data['limits'] = array();
			
			$config_limit = $this->config->get($theme . '_product_limit');

			$limits = range($config_limit, $config_limit*5, $config_limit);
			
			sort($limits);

			foreach($limits as $value) {
				$data['limits'][] = array(
				'text'  => $value,
				'value' => $value,
				);
			}
			
			// End Limit

			$url = '';

			if(isset($this->request->get['path']))		$url .= 'path=' . $category_id;
			if (isset($this->request->get['filter']))	$url .= '&filter=' . $this->request->get['filter'];
			if (isset($this->request->get['sort']))		$url .= '&sort=' . $this->request->get['sort'];
			if (isset($this->request->get['order']))	$url .= '&order=' . $this->request->get['order'];
			if (isset($this->request->get['limit']))	$url .= '&limit=' . $this->request->get['limit'];
			
			if($manufacturer_ids){
				foreach($manufacturer_ids as $each_id){
					$url .= '&manufacturer_ids[]=' . $each_id;
				}
			}

			if ($price_min) $url .= '&price_min=' . $price_min;
			if ($price_max) $url .= '&price_max=' . $price_max;
			
			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('product/category',  'page={page}') . $url;
			
			$data['pagination'] = $pagination->render();
			
			$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));
			
			$url = '';

			if($category_id){
				$url = 'path=' . $category_id;
			}
			if($manufacturer_ids){
				foreach($manufacturer_ids as $each_id){
					$url .= '&manufacturer_ids[]=' . $each_id;
				}
			}

			if($price_min){
				$url .= '&price_min=' . $price_min;
			}

			if($price_max){
				$url .= '&price_max=' . $price_max;
			}

			// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
			if ($page == 1) {
				$this->document->addLink($this->url->link('product/category', $url , true), 'canonical');
			}
			elseif ($page == 2) {
				$this->document->addLink($this->url->link('product/category', $url , true), 'prev');
			}
			else {
				$this->document->addLink($this->url->link('product/category', $url  . '&page='. ($page - 1), true), 'prev');
			}
			
			if ($limit && ceil($product_total / $limit) > $page) {
				$this->document->addLink($this->url->link('product/category', $url  . '&page='. ($page + 1), true), 'next');
			}
			
			$data['sort'] = $sort;		// debug($data['sort']);
			$data['order'] = $order;
			$data['limit'] = $limit;
			
			$data['continue'] = $this->url->link('common/home');
			
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			
			$this->response->setOutput($this->load->view('product/category', $data));
			
		}
	}

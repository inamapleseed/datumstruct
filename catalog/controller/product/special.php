<?php
class ControllerProductSpecial extends Controller {

	public function index() {

		$data = array();

		// Load Languages
		$data = array_merge($data, $this->load->language('product/special'));

		$this->document->setTitle($data['heading_title']);

		$data['text_compare'] = sprintf($data['text_compare'], (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$theme = $this->config->get('config_theme');

		$sort_default = 'p.sort_order';

		if($this->config->get('product_category_sort_order_status') && isset($this->request->get['category_id']) && (int)$this->request->get['category_id']){
			$sort_default = 'p2co.sort_order, p.sort_order, LCASE(pd.name)';
		}

		$listing_conditions = array(
			// Listing
			'sort'				=>	$sort_default,
			'order'				=>	'ASC',
			'page'				=>	1,
			'limit'				=>	(int)$this->config->get( $theme . '_product_limit'),
			// Filtering
			'path'				=>	0,
			'price_min' 		=>	0,
			'price_max' 		=>	0,
			'manufacturer_id'	=>	'',
			'filter'			=>	'',
		);

		// Filter Url to apply for Pagination / Breadcrumbs
		$url_filter = array(
			'pagination_filter'		=>	'page',
			'breadcrumbs_filter'	=>	'page',
		);

		foreach ($listing_conditions as $var => &$default){
			if(isset($this->request->get[$var])){
				$default	=	$this->request->get[$var];
			}

			if($var=='sort'){
				$sort_n_order = explode('-', $default);
				
				$order = $listing_conditions[$var];
				if(count($sort_n_order) > 1){
					$order	=	$sort_n_order[1];
				}

				${$var}	=	$sort_n_order[0];
			}
			elseif($var != 'order'){
				${$var}	=	$default;
			}
		}

		foreach($url_filter as $url => $skip){
			${$url}	= '';
			foreach ($listing_conditions as $var => $default){
				if( !strpos( '_' . $skip, $var) && $default){ 
					${$url} .= '&' . $var . '=' . ${$var};
				}
			}
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $data['heading_title'],
			'href' => $this->url->link('product/special') . $breadcrumbs_filter
		);

		$data['compare'] = $this->url->link('product/compare');

		$category_info = array();

		$categories = array();

		if($path){
			$this->load->model('catalog/category');

			$categories = explode('_', $path);

			$category_path = array();
			foreach($categories as $category_id){
				$category_info = $this->model_catalog_category->getCategory($category_id);

				if($category_info){
					$category_path[] = $category_id;

					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'path=' . implode('_', $category_path) . $breadcrumbs_filter)
					);
				}

				if($category_info){
					
					$data['heading_title'] = $category_info['name'];

					$this->document->setTitle($category_info['meta_title']);

					$this->document->setDescription($category_info['meta_description']);

					$this->document->setKeywords($category_info['meta_keyword']);

				}
			}
			
		}

		$data['products'] = array();

		$filter_data = array(
			'filter_category_id' => $categories?end($categories):0,
			'filter_manufacturer'=> $manufacturer_id,
			'filter_sub_category'=> true,
			'filter_special'	 => true,
			'price_min'			 => $price_min,
			'price_max'			 => $price_max,
			'filter_filter'      => $filter,
			'sort'               => $sort,
			'order'              => $order,
			'start'              => ($page - 1) * $limit,
			'limit'              => $limit,				
		); // debug($filter_data);

		$product_total = $this->model_catalog_product->getTotalProducts($filter_data);
			
		$results = $this->model_catalog_product->getProducts($filter_data);
		
		if (!empty($this->config->get('discounts_include_specials')) && !empty($this->config->get('discounts_status'))) {
			$this->load->model('catalog/discount');
			
			$results = $this->model_catalog_discount->getDiscountProducts($filter_data, $results, $product_total);
			
			$product_total = $this->model_catalog_discount->getTotalDiscountProducts($product_total);
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

		// Default
		$data['sorts'][] = array(
			'text'  => $this->language->get('text_default_asc'),
			'value' => 'sort_order-ASC',
		);

		// The rest of the ordering from $type_of_sort
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

		$page_data = array(
			'total'	=>	$product_total,
			'page'	=>	$page,
			'limit'	=>	$limit,
			'url'	=>	$this->url->link('product/special', 'page={page}'. $pagination_filter),
		); 
		//debug($page_data);
		$data = array_merge($this->load->controller('component/pagination', $page_data), $data);
		
		// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
		if ($page == 1) {
		    $this->document->addLink($this->url->link('product/special', '', true), 'canonical');
		} elseif ($page == 2) {
		    $this->document->addLink($this->url->link('product/special', '', true), 'prev');
		} else {
		    $this->document->addLink($this->url->link('product/special', 'page='. ($page - 1), true), 'prev');
		}

		if ($limit && ceil($product_total / $limit) > $page) {
		    $this->document->addLink($this->url->link('product/special', 'page='. ($page + 1), true), 'next');
		}

		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['limit'] = $limit;

		$data['continue'] = $this->url->link('common/home');

		$data = $this->load->controller('component/common', $data);

		$this->response->setOutput($this->load->view('product/special', $data));
	}
}

// Original Line: 271
// After reduced: 180
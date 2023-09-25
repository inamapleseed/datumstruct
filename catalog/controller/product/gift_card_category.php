<?php
class ControllerProductGiftCardCategory extends Controller {

	public function index() {

		$data = array();

		// Load Languages
		$data = array_merge($data, $this->load->language('product/gift_card_category'));

		$this->document->setTitle($data['heading_title']);

		$this->load->model('tool/image');

		$theme = $this->config->get('config_theme');

		$listing_conditions = array(
			// Listing
			//'order'				=>	'ASC',
			'page'				=>	1,
			'limit'				=>	(int)$this->config->get( $theme . '_product_limit'),
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
			'text' => $data['breadcrumb_title'],
			'href' => $this->url->link('product/gift_card_category') . $breadcrumbs_filter
		);

		$data['products'] = array();

		$filter_data = array(
			//'order'              => $order,
			'start'              => ($page - 1) * $limit,
			'limit'              => $limit,		
			'type' => 2,		
		); 
		// debug($filter_data);

		$this->load->model('extension/total/voucher_theme');

		$results = $this->model_extension_total_voucher_theme->getVoucherThemes($filter_data);

		$results_total = $this->model_extension_total_voucher_theme->getTotalVoucherThemes($filter_data);

		// if($category_info){
		// 	$this->facebookcommonutils = new FacebookCommonUtils();
		// 	$params = new DAPixelConfigParams(array(
		// 	'eventName' => 'ViewCategory',
		// 	'products' => $results,
		// 	'currency' => $this->currency,
		// 	'currencyCode' => $this->session->data['currency'],
		// 	'hasQuantity' => false,
		// 	'isCustomEvent' => false,
		// 	'paramNameUsedInProductListing' => 'content_category',
		// 	'paramValueUsedInProductListing' => $category_info['name']));
		// 	$facebook_pixel_event_params_FAE =
		// 	$this->facebookcommonutils->getDAPixelParamsForProductListing($params);
		// 	// stores the pixel params in the session
		// 	$this->request->post['facebook_pixel_event_params_FAE'] =
		// 	addslashes(json_encode($facebook_pixel_event_params_FAE));
		// }
		// else{

			// Default category
			// $this->facebookcommonutils = new FacebookCommonUtils();
			// $params = new DAPixelConfigParams(array(
			// 'eventName' => 'ViewCategory',
			// 'products' => $results,
			// 'currency' => $this->currency,
			// 'currencyCode' => $this->session->data['currency'],
			// 'hasQuantity' => false,
			// 'isCustomEvent' => false,
			// 'paramNameUsedInProductListing' => 'content_category',
			// 'paramValueUsedInProductListing' => $this->language->get('text_products')));
			// $facebook_pixel_event_params_FAE =
			// $this->facebookcommonutils->getDAPixelParamsForProductListing($params);
			// // stores the pixel params in the session
			// $this->request->post['facebook_pixel_event_params_FAE'] =
			// addslashes(json_encode($facebook_pixel_event_params_FAE));
			
		// }

		$after_clean = '';
		// if( isset($this->request->get['path']) && !is_array($this->request->get['path']) ){
		// 	$after_clean = '&';
		// 	$before_clean = $this->request->get['path'];
		// 	$before_clean = explode('_', $before_clean);
		// 	foreach($before_clean as $key => $category_id){
		// 		if((int)$category_id < 1){
		// 			unset($before_clean[$key]);
		// 		}
		// 	}

		// 	if($before_clean){
		// 		$after_clean = 'path=' .implode('_', $before_clean);
		// 	}
		// }

		foreach ($results as $result) {
			$data['products'][] = $this->load->controller('component/gift_card_info', array( 'voucher_theme_id' => $result['voucher_theme_id'], 'url' => $after_clean));
		}

		// Sort
			
		// $type_of_sort = array(
		// 	'name'	=>	'pd.name',
		// 	'price'	=>	'p.price',
		// 	'price'	=>	'p.price',
		// 	//'model'	=>	'p.model',
		// );

		// if ($this->config->get('config_review_status')) {
		// 	$type_of_sort['rating'] = 'rating';
		// }

		// $data['sorts'] = array();

		// // Default
		// $data['sorts'][] = array(
		// 	'text'  => $this->language->get('text_default_asc'),
		// 	'value' => 'sort_order-ASC',
		// );

		// // The rest of the ordering from $type_of_sort
		// foreach($type_of_sort as $type => $column){
		// 	$data['sorts'][] = array(
		// 		'text'  => $this->language->get('text_' . $type . '_asc'),
		// 		'value' => $column.'-ASC',
		// 	);
		// 	$data['sorts'][] = array(
		// 		'text'  => $this->language->get('text_' . $type . '_desc'),
		// 		'value' => $column.'-DESC',
		// 	);
		// }
		// End Sort

		// Limit
		$data['limits'] = array();
		
		$config_limit = $this->config->get($theme . '_product_limit');

		//$limits = range($config_limit, $config_limit*5, $config_limit);
		$limits = array($config_limit, 12, 24, 30);
		
		sort($limits);

		foreach($limits as $value) {
			$data['limits'][] = array(
				'text'  => $value,
				'value' => $value,
			);
		}
		
		// End Limit

		$page_data = array(
			'total'	=>	$results_total,
			'page'	=>	$page,
			'limit'	=>	$limit,
			'url'	=>	$this->url->link('product/gift_card_category', 'page={page}'. $pagination_filter),
		); 

		//debug($page_data);
		$data = array_merge($this->load->controller('component/pagination', $page_data), $data);
		
		// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
		if ($page == 1) {
		    $this->document->addLink($this->url->link('product/gift_card_category', '', true), 'canonical');
		} elseif ($page == 2) {
		    $this->document->addLink($this->url->link('product/gift_card_category', '', true), 'prev');
		} else {
		    $this->document->addLink($this->url->link('product/gift_card_category', 'page='. ($page - 1), true), 'prev');
		}

		if ($limit && ceil($results_total / $limit) > $page) {
		    $this->document->addLink($this->url->link('product/gift_card_category', 'page='. ($page + 1), true), 'next');
		}

	//	$data['sort'] = $sort;
	//	$data['order'] = $order;
		$data['limit'] = $limit;

		$data['continue'] = $this->url->link('common/home');

		$data = $this->load->controller('component/common', $data);

		$this->response->setOutput($this->load->view('product/gift_card_category', $data));
	}
}

// Original Line: 422
// After reduced: 268
<?php
	class ControllerStartupSeoUrl extends Controller {
		
		private $static = array(
			// SEO Keyword => Route
			'faqs'					=>	'information/faq',
			'contact-us'			=>	'information/contact',
			'login'					=>	'account/login',
			'register'				=>	'account/register',
			'forgotten'				=>	'account/forgotten',
			'search'				=>	'product/search',
			'special'				=>	'product/special',
			// Start products or gallery
			'shop-product' 		=> 	'product/shop_product',
			// End products or gallery			
			'products'				=>	'product/category',
			'manufacturer'			=>	'product/manufacturer',	
			'cart'					=>	'checkout/cart',
			'checkout'				=>	'quickcheckout/checkout',
			'checkout-success' 		=>	'checkout/success',
			'print'	 		   		=>	'account/print',
			'contact-success'		=>	'information/contact/success',
			'account'				=>	'account/account',
			'edit'					=>	'account/edit',
			'address'				=>	'account/address',
			'address-update'		=>	'account/address/edit',
			'address-new'			=>	'account/address/add',
			'account-password'		=>	'account/password',
			'order-history'			=>	'account/order',
			'order-info'			=>	'account/order/info',
			'testimonial'			=>	'testimonial/testimonial',
			'gift-cards' 			=> 	'product/gift_card_category',
		);
		
		public function index() {

			// Add rewrite to url class
			if ($this->config->get('config_seo_url')) {
				$this->url->addRewrite($this);
			}
			
			// Decode URL
			if (isset($this->request->get['_route_'])) {
				$parts = explode('/', $this->request->get['_route_']);
				
				// remove any empty arrays from trailing
				if (utf8_strlen(end($parts)) == 0) {
					array_pop($parts);
				}
				
				foreach ($parts as $part) {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($part) . "'");
					
					if ($query->num_rows) {
						$url = explode('=', $query->row['query']);
						
						if ($url[0] == 'product_id') {
							$this->request->get['product_id'] = $url[1];
						}
						
						if ($url[0] == 'category_id') {
							if (!isset($this->request->get['path'])) {
								$this->request->get['path'] = $url[1];
							}
							else {
								$this->request->get['path'] .= '_' . $url[1];
							}
						}
						
						if ($url[0] == 'manufacturer_id') {
							$this->request->get['manufacturer_id'] = $url[1];
						}
						
						if ($url[0] == 'information_id') {
							$this->request->get['information_id'] = $url[1];
						}
						
						if ($query->row['query'] && $url[0] != 'information_id' && $url[0] != 'manufacturer_id' && $url[0] != 'category_id' && $url[0] != 'product_id') {
							$this->request->get['route'] = $query->row['query'];
						}
					}
					elseif( isset($this->static[$part]) ){
						$this->request->get['route'] = $this->static[$part];
						//break;
					}
					elseif( $part == 'products' ){
						$this->request->get['route'] = 'product/category';
						//break;
					}
					else {
						$this->request->get['route'] = 'error/not_found';
						
						break;
					}
				}
				
				if (!isset($this->request->get['route'])) {
					if (isset($this->request->get['product_id'])) {
						$this->request->get['route'] = 'product/product';
					}
					elseif (isset($this->request->get['path'])) {
						$this->request->get['route'] = 'product/category';
					}
					elseif (isset($this->request->get['manufacturer_id'])) {
						$this->request->get['route'] = 'product/manufacturer/info';
					}
					elseif (isset($this->request->get['information_id'])) {
						$this->request->get['route'] = 'information/information';
					}
				}
				
				if ($this->request->get['route'] != 'product/product' && $this->request->get['route'] != 'product/category' && $this->request->get['route'] != 'product/manufacturer/info' && $this->request->get['route'] != 'information/information') {
					$blog_headlines = $this->config->get('ncategory_bnews_headlines_url') ? $this->config->get('ncategory_bnews_headlines_url') : 'blog-headlines';
					
					$blogparts = explode('/', $this->request->get['_route_']);
					
					if (utf8_strlen(end($blogparts)) == 0) {
						array_pop($blogparts);
					}
					
					
					foreach ($blogparts as $part) {
						/* default article seo urls */
						if (strpos($part, 'blogcat') === 0) {
							$ncatid = (int)str_replace("blogcat", "", $part);
							if (!isset($this->request->get['ncat'])) {
								$this->request->get['ncat'] = $ncatid;
							}
							else {
								$this->request->get['ncat'] .= '_' . $ncatid;
							}
						}
						if (strpos($part, 'blogart') === 0) {
							$this->request->get['news_id'] = (int)str_replace("blogart", "", $part);
						}
						if (strpos($part, 'blogauthor') === 0) {
							$this->request->get['author'] = (int)str_replace("blogauthor", "", $part);
						}
						if (strpos($part, 'blogarchive-') === 0) {
							$this->request->get['archive'] = (string)str_replace("blogarchive-", "", $part);
						}
						/* end of default article urls */
						$query     =     $this->db->query( " SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($part) . "' " );
						
						if ($part == $blog_headlines) {
							$query->num_rows = true;
							$query->row['query'] = "-=-";
						}
						
						if ($query->num_rows) {
							$url = explode('=', $query->row['query']);
							/* custom article urls */
							if ($url[0] == 'news_id') {
								$this->request->get['news_id'] = $url[1];
							}
							if ($url[0] == 'nauthor_id') {
								$this->request->get['author'] = $url[1];
							}
							if ($url[0] == 'ncategory_id') {
								if (!isset($this->request->get['ncat'])) {
									$this->request->get['ncat'] = $url[1];
								}
								else {
									$this->request->get['ncat'] .= '_' . $url[1];
								}
							}
							/* end of custom article urls */
						}
					}
					if (!isset($this->request->get['route']) || (isset($this->request->get['route']) && $this->request->get['route'] == "error/not_found")) {
						
						if (isset($this->request->get['news_id'])) {
							$this->request->get['route'] = 'news/article';
						}
						elseif (isset($this->request->get['ncat']) || isset($this->request->get['author']) || $this->request->get['_route_'] ==  $blog_headlines || isset($this->request->get['archive'])) {
							$this->request->get['route'] = 'news/ncategory';
						}
					}
				}
				
			}
		}
		
		public function rewrite($link) {
			$url_info = parse_url(str_replace('&amp;', '&', $link));
			
			$url = '';
			
			$data = array();
			
			parse_str($url_info['query'], $data); // debug($data);

			if ( in_array($data['route'], $this->static) ){
				$url .= '/' . array_search($data['route'], $this->static);
			}
			elseif ($data['route'] == 'common/home'){
				$url = '/';
			}
			
			foreach ($data as $key => $value) {
				if (isset($data['route'])) {
					
					if (($data['route'] == 'product/product' && $key == 'product_id') || (($data['route'] == 'product/manufacturer/info' || $data['route'] == 'product/product') && $key == 'manufacturer_id') || ($data['route'] == 'information/information' && $key == 'information_id')) {
						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");
						
						if ($query->num_rows && $query->row['keyword']) {
							$url .= '/' . $query->row['keyword'];
							
							unset($data[$key]);
						}
					}
					elseif ($data['route'] == 'news/article' && $key == 'news_id') { 
						$query    =    $this->db->query( "SELECT * FROM " . DB_PREFIX . "url_alias where  `query` = '" . $this->db->escape($key . '=' . (int)$value ) . "'");
						if ($query->num_rows) {
							$url .= '/' . $query->row['keyword'];
							unset($data[$key]);
						}
						else {
							$url .= '/blogart' . (int)$value;	
							unset($data[$key]);
						}
					}
					elseif (($data['route'] == 'news/ncategory' || $data['route'] == 'news/article') && $key == 'author') { 
						$realkey = "nauthor_id";
						$query   =    $this->db->query( "SELECT * FROM " . DB_PREFIX . "url_alias where  `query` = '" . $this->db->escape($realkey . '=' . (int)$value) . "'" );
						if ($query->num_rows) {
							$url .= '/' . $query->row['keyword'];
							unset($data[$key]);
						}
						else {
							$url .= '/blogauthor' . (int)$value;	
							unset($data[$key]);
						}
					}
					elseif (($data['route'] == 'news/ncategory' || $data['route'] == 'news/article') && $key == 'archive') { 
						$url .= '/blogarchive-' . (string)$value;	
						unset($data[$key]);
					}
					elseif ($key == 'ncat') {
						$ncategories = explode('_', $value);
						
						foreach ($ncategories as $ncategory) {
							$query    =    $this->db->query( "SELECT * FROM " . DB_PREFIX . "url_alias where  `query` = 'ncategory_id=" . (int)$ncategory . "'" );
							if ($query->num_rows) {
								$url .= '/' . $query->row['keyword'];
							}
							else {
								$url .= '/blogcat' . $ncategory;
							}
						}
						unset($data[$key]);
					}
					elseif ((isset($data['route']) && $data['route'] == 'news/ncategory' && $key != 'ncat' && $key != 'author' && $key != 'page' && $key != 'archive') || (isset($data['route']) && $data['route'] == 'news/article' && $key != 'page')) { 
						$blog_headlines = $this->config->get('ncategory_bnews_headlines_url') ? $this->config->get('ncategory_bnews_headlines_url') : 'blog-headlines';
						$url .=  '/'.$blog_headlines;
					}
					elseif ($key == 'path') {
						$categories = explode('_', $value);
						
						foreach ($categories as $category) {
							$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int)$category . "'");
							
							if ($query->num_rows && $query->row['keyword']) {
								$url .= '/' . $query->row['keyword'];
							}
							else {
								$url = '';
								
								break;
							}
						}
						
						unset($data[$key]);
					}
					
				}
			}
			
			if ($url) {
				unset($data['route']);
				
				$query = '';
				
				if ($data) {
					foreach ($data as $key => $value) {
						$query .= '&' . rawurlencode((string)$key) . '=' . rawurlencode((is_array($value) ? http_build_query($value) : (string)$value));
					}
					
					if ($query) {
						$query = '?' . str_replace('&', '&amp;', trim($query, '&'));
					}
				}
				
				return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url . $query;
			}
			else {
				return $link;
			}
		}
	}

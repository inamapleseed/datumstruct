<?php 
class ControllerNewsSearch extends Controller { 	
	public function index() { 
	
		$this->language->load('news/search');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->document->addStyle('catalog/view/theme/default/stylesheet/blog-news.css');
		
    	$data['heading_title'] = $this->language->get('heading_title');
		
		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array( 
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
      		'separator' => false
   		);
		
		$url = '';
		
		if (isset($this->request->get['filter_artname'])) {
			$url .= '&filter_artname=' . urlencode(html_entity_decode($this->request->get['filter_artname'], ENT_QUOTES, 'UTF-8'));
		}
			
		if (isset($this->request->get['article_tag'])) {
			$url .= '&article_tag=' . urlencode(html_entity_decode($this->request->get['article_tag'], ENT_QUOTES, 'UTF-8'));
		}
			
		if (isset($this->request->get['filter_description'])) {
			$url .= '&filter_description=' . (int)$this->request->get['filter_description'];
		}
				
		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . (int)$this->request->get['filter_category_id'];
		}
		
		if (isset($this->request->get['filter_sub_category'])) {
			$url .= '&filter_sub_category=' . (int)$this->request->get['filter_sub_category'];
		}
				
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . (int)$this->request->get['page'];
		}	
						
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('news/search', $url),
      		'separator' => $this->language->get('text_separator')
   		);
	
		$data['button_continue'] = $this->language->get('go_to_headlines');
		$data['continue'] = $this->url->link('news/ncategory');
		
		$data['description'] = $this->getPageContent();
		
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (!$this->config->get('ncategory_bnews_tplpick')) {
				if (version_compare(VERSION, '2.2.0.0') >= 0) {
					$this->response->setOutput($this->load->view('news/layout', $data));
				} else {
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/news/layout.tpl')) {
						$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/news/layout.tpl', $data));
					} else {
						$this->response->setOutput($this->load->view('default/template/news/layout.tpl', $data));
					}
				}
			} else {
				if (version_compare(VERSION, '2.2.0.0') >= 0) {
					$this->response->setOutput($this->load->view('information/information', $data));
				} else {
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/information.tpl')) {
						$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/information/information.tpl', $data));
					} else {
						$this->response->setOutput($this->load->view('default/template/information/information.tpl', $data));
					}
				}
			}
  	}
	protected function getPageContent() {	
		
		if(isset($this->request->get['route'])) {
			if(strpos(strtolower($this->request->get['route']), 'getpagecontent')) {
				$this->response->redirect($this->url->link('news/search'));
			}
		}
		
		$this->language->load('news/search');
		
		$this->load->model('catalog/ncategory');
		
		$this->load->model('catalog/news');
		
		$this->load->model('tool/image'); 
	
		$this->load->model('catalog/ncomments');
		
		if (isset($this->request->get['filter_artname'])) {
			$filter_name = $this->lowerString($this->request->get['filter_artname']);
		} else {
			$filter_name = '';
		} 
		if (isset($this->request->get['article_tag'])) {
			$article_tag = $this->lowerString($this->request->get['article_tag']);
		} else {
			$article_tag = '';
		} 
				
		if (isset($this->request->get['filter_description'])) {
			$filter_description = (int)$this->request->get['filter_description'];
		} else {
			$filter_description = '';
		} 
				
		if (isset($this->request->get['filter_category_id'])) {
			$filter_category_id = (int)$this->request->get['filter_category_id'];
		} else {
			$filter_category_id = 0;
		} 
		
		if (isset($this->request->get['filter_sub_category'])) {
			$filter_sub_category = (int)$this->request->get['filter_sub_category'];
		} else {
			$filter_sub_category = '';
		}
  		
		if (isset($this->request->get['page'])) {
				$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$limit = $this->config->get('ncategory_bnews_catalog_limit') ? $this->config->get('ncategory_bnews_catalog_limit') : ($this->config->get('config_product_limit') ? $this->config->get('config_product_limit') : $this->config->get($this->config->get('config_theme') . '_product_limit'));
		
		
		$url = '';
		
		if (isset($this->request->get['filter_artname'])) {
			$url .= '&filter_artname=' . urlencode(html_entity_decode($this->request->get['filter_artname'], ENT_QUOTES, 'UTF-8'));
		}
			
		if (isset($this->request->get['article_tag'])) {
			$url .= '&article_tag=' . urlencode(html_entity_decode($this->request->get['article_tag'], ENT_QUOTES, 'UTF-8'));
		}
			
		if (isset($this->request->get['filter_description'])) {
			$url .= '&filter_description=' . (int)$this->request->get['filter_description'];
		}
				
		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . (int)$this->request->get['filter_category_id'];
		}
		
		if (isset($this->request->get['filter_sub_category'])) {
			$url .= '&filter_sub_category=' . (int)$this->request->get['filter_sub_category'];
		}
				
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . (int)$this->request->get['page'];
		}	
		
		$data['text_empty'] = $this->language->get('text_empty');
    	$data['text_critea'] = $this->language->get('text_critea');
    	$data['text_search'] = $this->language->get('text_search');
		$data['text_keyword'] = $this->language->get('text_keyword');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_sub_category'] = $this->language->get('text_sub_category');
		$data['button_more'] = $this->language->get('button_more');
		$data['entry_search'] = $this->language->get('entry_search');
    	$data['entry_description'] = $this->language->get('entry_description');
		  
		$data['text_posted_by'] = $this->language->get('text_posted_by');
		$data['text_posted_on'] = $this->language->get('text_posted_on');
		$data['text_posted_pon'] = $this->language->get('text_posted_pon');
		$data['text_posted_in'] = $this->language->get('text_posted_in');
		$data['text_updated_on'] = $this->language->get('text_updated_on');
		$data['text_comments'] = $this->language->get('text_comments');	
		$data['text_comments_v'] = $this->language->get('text_comments_v');		
		$data['disqus_sname'] = $this->config->get('ncategory_bnews_disqus_sname');
		$data['disqus_status'] = $this->config->get('ncategory_bnews_disqus_status');
		$data['fbcom_status'] = $this->config->get('ncategory_bnews_fbcom_status');
		$data['fbcom_appid'] = $this->config->get('ncategory_bnews_fbcom_appid');
		$data['fbcom_theme'] = $this->config->get('ncategory_bnews_fbcom_theme');
		$data['fbcom_posts'] = $this->config->get('ncategory_bnews_fbcom_posts');
		$data['display_s'] = $this->config->get('ncategory_bnews_display_style');
		$date_format = $this->config->get('ncategory_bnews_date_format') ? $this->config->get('ncategory_bnews_date_format') : 'd.m.Y';
		
    	$data['button_search'] = $this->language->get('button_search');
		
		$this->load->model('catalog/ncategory');
		
		// 3 Level Category Search
		$data['categories'] = array();
					
		$categories_1 = $this->model_catalog_ncategory->getncategories(0);
		
		foreach ($categories_1 as $category_1) {
			$level_2_data = array();
			
			$categories_2 = $this->model_catalog_ncategory->getncategories($category_1['ncategory_id']);
			
			foreach ($categories_2 as $category_2) {
				$level_3_data = array();
				
				$categories_3 = $this->model_catalog_ncategory->getncategories($category_2['ncategory_id']);
				
				foreach ($categories_3 as $category_3) {
					$level_3_data[] = array(
						'category_id' => $category_3['ncategory_id'],
						'name'        => $category_3['name'],
					);
				}
				
				$level_2_data[] = array(
					'category_id' => $category_2['ncategory_id'],	
					'name'        => $category_2['name'],
					'children'    => $level_3_data
				);					
			}
			
			$data['categories'][] = array(
				'category_id' => $category_1['ncategory_id'],
				'name'        => $category_1['name'],
				'children'    => $level_2_data
			);
		}
		
		$data['article'] = array();
		
		if (isset($this->request->get['filter_artname']) || isset($this->request->get['article_tag'])) {
			$sdata = array(
				'filter_name'         => $filter_name,
				'filter_tag'          => $article_tag,
				'filter_description'  => $filter_description,
				'filter_ncategory_id' => $filter_category_id, 
				'filter_sub_ncategory' => $filter_sub_category, 
				'start'               => ($page - 1) * $limit,
				'limit'               => $limit
			);
			
			
			$bbwidth = ($this->config->get('ncategory_bnews_image_width')) ? $this->config->get('ncategory_bnews_image_width') : 80;
			$bbheight = ($this->config->get('ncategory_bnews_image_height')) ? $this->config->get('ncategory_bnews_image_height') : 80;		
			if($this->config->get('ncategory_bnews_display_elements')) {
				$elements = $this->config->get('ncategory_bnews_display_elements');
			} else {
				$elements = array("name","image","da","du","author","category","desc","button","com","custom1","custom2","custom3","custom4");
			}
			$news_total = $this->model_catalog_news->getTotalNews($sdata);
								
			$results = $this->model_catalog_news->getNews($sdata);
					
			foreach ($results as $result) {
				$name = (in_array("name", $elements) && $result['title']) ? $result['title'] : '';
				$da = (in_array("da", $elements)) ? date($date_format, strtotime($result['date_added'])) : '';
				$du = (in_array("du", $elements) && $result['date_updated'] && $result['date_updated'] != $result['date_added']) ? date($date_format, strtotime($result['date_updated'])) : '';
				$button = (in_array("button", $elements)) ? true : false;
				$custom1 = (in_array("custom1", $elements) && $result['cfield1']) ? html_entity_decode($result['cfield1'], ENT_QUOTES, 'UTF-8') : '';
				$custom2 = (in_array("custom2", $elements) && $result['cfield2']) ? html_entity_decode($result['cfield2'], ENT_QUOTES, 'UTF-8') : '';
				$custom3 = (in_array("custom3", $elements) && $result['cfield3']) ? html_entity_decode($result['cfield3'], ENT_QUOTES, 'UTF-8') : '';
				$custom4 = (in_array("custom4", $elements) && $result['cfield4']) ? html_entity_decode($result['cfield4'], ENT_QUOTES, 'UTF-8') : '';
				if (in_array("image", $elements) && ($result['image'] || $result['image2'])) {
					if ($result['image2']) {
						$image = 'image/'.$result['image2'];
					} else {
						$image = $this->model_tool_image->resize($result['image'], $bbwidth, $bbheight);
					}
				} else {
					$image = false;
				}
				if (in_array("author", $elements) && $result['author']) {
					$author = $result['author'];
					$author_id = $result['nauthor_id'];
					$author_link = $this->url->link('news/ncategory', 'author=' . $result['nauthor_id']);
				} else {
					$author = '';
					$author_id = '';
					$author_link = '';
				}
				if (in_array("desc", $elements) && ($result['description'] || $result['description2'])) {
					if($result['description2'] && (strlen(html_entity_decode($result['description2'], ENT_QUOTES, 'UTF-8')) > 20)) {
						$desc = html_entity_decode($result['description2'], ENT_QUOTES, 'UTF-8');
					} else {
						$desc_limit = $this->config->get('ncategory_bnews_desc_length') ? $this->config->get('ncategory_bnews_desc_length') : 600;
						$desc = utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $desc_limit) . '..';
					}
				} else {
					$desc = '';
				}
				if (in_array("com", $elements) && $result['acom']) {
					$com = $this->model_catalog_ncomments->getTotalNcommentsByNewsId($result['news_id']);
					if (!$com) {
						$com = " 0 ";
					}
				} else {
					$com = '';
				}
				if (in_array("category", $elements)) {
					$category = "";
					$cats = $this->model_catalog_news->getNcategoriesbyNewsId($result['news_id']);
					if ($cats) {
						$comma = 0;
						foreach($cats as $catid) {
							$catinfo = $this->model_catalog_ncategory->getncategory($catid['ncategory_id']);
							if ($catinfo) {
								if ($comma) {
									$category .= ', <a href="'.$this->url->link('news/ncategory', 'ncat=' . $catinfo['ncategory_id']).'">'.$catinfo['name'].'</a>';
								} else {
									$category .= '<a href="'.$this->url->link('news/ncategory', 'ncat=' . $catinfo['ncategory_id']).'">'.$catinfo['name'].'</a>';
								}
								$comma++;
							}
						}
					}
				} else {
					$category = '';
				}
				
				$data['article'][] = array(
					'article_id'  => $result['news_id'],
					'name'        => $name,
					'thumb'       => $image,
					'date_added'  => $da,
					'du'          => $du,
					'author'      => $author,
					'author_id'   => $author_id,
					'author_link' => $author_link,
					'description' => $desc,
					'button'      => $button,
					'custom1'     => $custom1,
					'custom2'     => $custom2,
					'custom3'     => $custom3,
					'custom4'     => $custom4,
					'category'    => $category,
					'href'        => $this->url->link('news/article', '&news_id=' . $result['news_id']),
					'total_comments' => $com
				);
			}
					
			$url = '';
			
			if (isset($this->request->get['filter_artname'])) {
				$url .= '&filter_artname=' . urlencode(html_entity_decode($this->request->get['filter_artname'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['article_tag'])) {
				$url .= '&article_tag=' . urlencode(html_entity_decode($this->request->get['article_tag'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_description'])) {
				$url .= '&filter_description=' . (int)$this->request->get['filter_description'];
			}
			
			if (isset($this->request->get['filter_category_id'])) {
				$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
			}
			
			if (isset($this->request->get['filter_sub_category'])) {
				$url .= '&filter_sub_category=' . $this->request->get['filter_sub_category'];
			}
					
			$pagination = new Pagination();
			$pagination->total = $news_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('news/search', $url . '&page={page}');
			
			$data['pagination'] = $pagination->render();
			
			$data['pag_results'] = sprintf($this->language->get('text_pagination'), ($news_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($news_total - $limit)) ? $news_total : ((($page - 1) * $limit) + $limit), $news_total, ceil($news_total / $limit));
		}	
		
		$data['filter_name'] = $filter_name;
		$data['filter_description'] = $filter_description;
		$data['filter_category_id'] = $filter_category_id;
		$data['filter_sub_category'] = $filter_sub_category;
		
		if (version_compare(VERSION, '2.2.0.0') >= 0) {
			return $this->load->view('news/search', $data);
		} else {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/news/search.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/news/search.tpl', $data);
			} else {
				return $this->load->view('default/template/news/search.tpl', $data);
			}
		}
	}

	private function lowerString($string) {
		if (function_exists('mb_strtolower')) {
			return mb_strtolower($string);
		} else {
			return strtolower($string);
		}
	}
}
?>
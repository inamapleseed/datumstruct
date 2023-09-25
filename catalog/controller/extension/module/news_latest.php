<?php
class ControllerExtensionModuleNewsLatest extends Controller {
	public function index($setting) {
		$this->language->load('extension/module/news_latest');
		
		$this->load->model('catalog/news');
		
		$this->load->model('catalog/ncomments');
		
		$this->load->model('catalog/ncategory');
		
		$this->load->model('tool/image'); 

		$this->document->addStyle('catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.css');
		$this->document->addScript('catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.js');
		
		$this->document->addStyle('catalog/view/theme/default/stylesheet/blog-news.css');

		$filter_name = $article_tag = $filter_description = '';

		if (isset($this->request->get['route']) && $this->request->get['route'] == 'product/search' && isset($this->request->get['search']) && $this->request->get['search']) {
			$product_search = true;
			$filter_name = strtolower($this->request->get['search']);
			if(isset($this->request->get['tag']) && $this->request->get['tag']) $article_tag = strtolower($this->request->get['tag']);
			if(isset($this->request->get['description']) && $this->request->get['description']) $filter_description = 1;
		} else {
			$product_search = false;
		}

    	$data['text_headlines'] = $this->language->get('text_headlines');
		$data['display_style'] = $this->config->get('ncategory_bnews_display_style');
		$data['text_posted_by'] = $this->language->get('text_posted_by');
		$data['text_posted_on'] = $this->language->get('text_posted_on');
		$data['text_posted_pon'] = $this->language->get('text_posted_pon');
		$data['text_posted_in'] = $this->language->get('text_posted_in');
		$data['text_updated_on'] = $this->language->get('text_updated_on');
		$data['text_comments'] = $this->language->get('text_comments');	
		$data['text_comments_v'] = $this->language->get('text_comments_v');
		$data['button_more'] = $this->language->get('button_more');	
		$data['disqus_sname'] = $this->config->get('ncategory_bnews_disqus_sname');
		$data['disqus_status'] = $this->config->get('ncategory_bnews_disqus_status');
		$data['fbcom_status'] = $this->config->get('ncategory_bnews_fbcom_status');
		$data['fbcom_appid'] = $this->config->get('ncategory_bnews_fbcom_appid');
		$data['fbcom_theme'] = $this->config->get('ncategory_bnews_fbcom_theme');
		$data['fbcom_posts'] = $this->config->get('ncategory_bnews_fbcom_posts');

		$date_format = $this->config->get('ncategory_bnews_date_format') ? $this->config->get('ncategory_bnews_date_format') : 'd.m.Y';

		if (isset($setting['display_style'])) {
			$data['display_style'] = $setting['display_style'];
		}
		
		if ($setting['ncategory_id'] == 'all') {
    	 $data['heading_title'] = $this->language->get('heading_title');
		 $data['newslink'] = $this->url->link('news/ncategory');
		} else {
		 $ncategory_info = $this->model_catalog_ncategory->getncategory($setting['ncategory_id']);
		 if (isset($ncategory_info['name'])) {
			$data['heading_title'] = $ncategory_info['name'];
			$data['newslink'] = $this->url->link('news/ncategory', 'ncat=' . $setting['ncategory_id']);
			$catexists = true;
         } else {
			$data['heading_title'] = $this->language->get('heading_title');
		 	$data['newslink'] = $this->url->link('news/ncategory');
		 }
		}

		if ($product_search) {
			$data['heading_title'] = $this->language->get('text_search');
			$search_param = '&filter_artname=' . urlencode(html_entity_decode($filter_name, ENT_QUOTES, 'UTF-8'));
			if ($article_tag) $search_param .= '&article_tag=' . urlencode(html_entity_decode($article_tag, ENT_QUOTES, 'UTF-8'));
			if ($filter_description) $search_param .= '&filter_description=' . urlencode(html_entity_decode($filter_description, ENT_QUOTES, 'UTF-8'));
			$data['newslink'] = $this->url->link('news/search', $search_param);
			$data['text_headlines'] = $this->language->get('text_search_advanced');
			$data['text_search_no_results'] = $this->language->get('text_search_no_results');
		}
		
		$data['article'] = array(); 
		
		if ($product_search) {	
			$sdata = array(
				'filter_name'         => $filter_name,
				'filter_tag'          => $article_tag,
				'filter_description'  => $filter_description,
				'start'				  => 0,
				'limit'               => $setting['news_limit']
			);

			$news_total = $this->model_catalog_news->getTotalNews($sdata);
			$results = $this->model_catalog_news->getNews($sdata);
			if ($news_total > $setting['news_limit']) $data['text_headlines'] = $this->language->get('text_search_more');
		} elseif ($setting['ncategory_id'] == 'all' || !isset($catexists)) {	
			$sdata = array(
					'start'           => 0,
					'limit'           => $setting['news_limit'] 
			);
			$results = $this->model_catalog_news->getNews($sdata);	
		} else {	
			$sdata = array(
					'filter_ncategory_id' => $setting['ncategory_id'],
					'start'           => 0,
					'limit'           => $setting['news_limit']
			);
			$results = $this->model_catalog_news->getNews($sdata);	
		}
		
		$bbwidth = ($this->config->get('ncategory_bnews_image_width')) ? $this->config->get('ncategory_bnews_image_width') : 80;
		$bbheight = ($this->config->get('ncategory_bnews_image_height')) ? $this->config->get('ncategory_bnews_image_height') : 80;
			
		if($this->config->get('ncategory_bnews_display_elements')) {
				$elements = $this->config->get('ncategory_bnews_display_elements');
		} else {
				$elements = array("name","image","da","du","author","category","desc","button","com","custom1","custom2","custom3","custom4");
		}
			
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
				$href = $this->url->link('news/article', 'news_id=' . $result['news_id']);
				
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
					'href'        => $href,
					'total_comments' => $com
				);
		}
		

		return $this->load->view('extension/module/news_latest', $data);
	
	}
}
?>

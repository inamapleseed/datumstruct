<?php 
class ControllerNewsNcategory extends Controller {  
	public function index() { 
	
		$this->language->load('news/ncategory');
		
		$this->load->model('catalog/ncategory');
		
		$this->load->model('catalog/news');
		
		$this->document->addStyle('catalog/view/theme/default/stylesheet/blog-news.css');

   		$data['breadcrumbs'][] = array( 
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
      		'separator' => false
   		);
		
	    $data['breadcrumbs'][] = array(
   	    	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('news/ncategory')
        );
		
		$url = '';
				
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . (int)$this->request->get['page'];
		}	
		
		if (isset($this->request->get['ncat'])) {
			$ncat = '';
		
			$parts = explode('_', (string)$this->request->get['ncat']);
		
			foreach ($parts as $ncat_id) {
				if (!$ncat) {
					$ncat = $ncat_id;
				} else {
					$ncat .= '_' . $ncat_id;
				}
									
				$ncategory_info = $this->model_catalog_ncategory->getncategory($ncat_id);
				
				if ($ncategory_info) {
	       			$data['breadcrumbs'][] = array(
   	    				'text'      => $ncategory_info['name'],
						'href'      => $this->url->link('news/ncategory', 'ncat=' . $ncat)
        			);
				}
			}		
			$ncategory_id = array_pop($parts);
			$this->document->addLink($this->url->link('news/ncategory', 'ncat=' . $ncategory_id), 'canonical');
		} else {
			$ncategory_id = 0;
		}
		if (isset($this->request->get['author'])) {
			$author_id = (int)$this->request->get['author'];
		} else {
			$author_id = 0;
		}
		$author_info = $this->model_catalog_news->getNauthor($author_id);
		if ($author_info) {
			$data['breadcrumbs'][] = array(
   	    		'text'      => $author_info['name'],
				'href'      => $this->url->link('news/ncategory', 'author=' . $author_id),
        		'separator' => $this->language->get('text_separator')
        	);
			$this->document->setTitle($author_info['name']);
			
			$data['heading_title'] = $author_info['name'];
			
			$authordesc = $this->model_catalog_news->getNauthorDescriptions($author_info['nauthor_id']);
			
			if (isset($authordesc[$this->config->get('config_language_id')])) {
				$this->document->setDescription($authordesc[$this->config->get('config_language_id')]['meta_description']);
				$this->document->setKeywords($authordesc[$this->config->get('config_language_id')]['meta_keyword']);
				if ($authordesc[$this->config->get('config_language_id')]['ctitle']) {
					$this->document->setTitle($authordesc[$this->config->get('config_language_id')]['ctitle']);
				}
			}
		
		}
		
		$ncategory_info = $this->model_catalog_ncategory->getncategory($ncategory_id);
		
		//new archive
		if (isset($this->request->get['archive'])) {
			$archive = (string)$this->request->get['archive'];
		} else {
			$archive = false;
		}
		
		if ($ncategory_info) {
			$settings = $ncategory_info;
		} elseif ($author_info) {
			$settings = array('author' => $author_info, 'author_info' => $authordesc);
		} elseif ($archive) {
			$date = explode('-', $archive);
			$year = isset($date[0]) ? (int)$date[0] : 2015;
			$month = (isset($date[1]) && $date[1] > 0 && $date[1] < 13) ? (int)$date[1] : 1;
			$months = $this->config->get('news_archive_months');
			$lid = $this->config->get('config_language_id');
			$m_name = array();
			$m_name[1] = (isset($months['jan'][$lid]) && $months['jan'][$lid]) ? $months['jan'][$lid] : 'January';
			$m_name[2] = (isset($months['feb'][$lid]) && $months['feb'][$lid]) ? $months['feb'][$lid] : 'February';
			$m_name[3] = (isset($months['march'][$lid]) && $months['march'][$lid]) ? $months['march'][$lid] : 'March';
			$m_name[4] = (isset($months['april'][$lid]) && $months['april'][$lid]) ? $months['april'][$lid] : 'April';
			$m_name[5] = (isset($months['may'][$lid]) && $months['may'][$lid]) ? $months['may'][$lid] : 'May';
			$m_name[6] = (isset($months['june'][$lid]) && $months['june'][$lid]) ? $months['june'][$lid] : 'June';
			$m_name[7] = (isset($months['july'][$lid]) && $months['july'][$lid]) ? $months['july'][$lid] : 'July';
			$m_name[8] = (isset($months['aug'][$lid]) && $months['aug'][$lid]) ? $months['aug'][$lid] : 'August';
			$m_name[9] = (isset($months['sep'][$lid]) && $months['sep'][$lid]) ? $months['sep'][$lid] : 'September';
			$m_name[10] = (isset($months['oct'][$lid]) && $months['oct'][$lid]) ? $months['oct'][$lid] : 'October';
			$m_name[11] = (isset($months['nov'][$lid]) && $months['nov'][$lid]) ? $months['nov'][$lid] : 'November';
			$m_name[12] = (isset($months['dec'][$lid]) && $months['dec'][$lid]) ? $months['dec'][$lid] : 'December';
			$month_name = $m_name[$month];
			$settings = array('year' => $year, 'month' => $month, 'month_name' => $month_name);
		} else {
			$settings = array();
		}
		if ($archive) {
	       	$data['breadcrumbs'][] = array(
   	    		'text'      => $this->language->get('heading_title_archive') . $month_name . ' ' . $year,
				'href'      => $this->url->link('news/ncategory', 'archive=' . $year . '-' . $month)
        	);
			$data['heading_title'] = $this->language->get('heading_title_archive') . $month_name . ' ' . $year;
			$this->document->setTitle($this->language->get('heading_title_archive') . $month_name . ' ' . $year);
			$this->document->addLink($this->url->link('news/ncategory', 'archive=' . $year . '-' . $month), 'canonical');
		}
		if (!$ncategory_info && !$author_info && !$archive) {
			$data['heading_title'] = $this->language->get('heading_title');
			$this->document->setTitle($this->language->get('heading_title'));
		}

		if ((!isset($this->request->get['ncat']) && !isset($this->request->get['author']) && !isset($this->request->get['archive'])) || (isset($this->request->get['ncat']) && $ncategory_info) || (isset($this->request->get['author']) && $author_info) || (isset($this->request->get['archive']) && $archive)) {
			if ($ncategory_info) {
				$this->document->setTitle($ncategory_info['name']);
				$this->document->setDescription($ncategory_info['meta_description']);
				$this->document->setKeywords($ncategory_info['meta_keyword']);
				$data['heading_title'] = $ncategory_info['name'];
			} 
	
			$data['button_continue'] = $this->language->get('go_to_headlines');
			$data['continue'] = $this->url->link('news/ncategory');
		
			$data['description'] = $this->getPageContent($settings);
		
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
		} else {
			$url = '';
			
			if (isset($this->request->get['ncat'])) {
				$url .= '&ncat=' . urlencode(html_entity_decode($this->request->get['ncat'], ENT_QUOTES, 'UTF-8'));
			}
				
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . (int)$this->request->get['page'];
			}
						
			$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_error'),
				'href'      => $this->url->link('news/ncategory', $url),
				'separator' => $this->language->get('text_separator')
			);
				
			$this->document->setTitle($this->language->get('text_error'));

      		$data['heading_title'] = $this->language->get('text_error');

      		$data['text_error'] = $this->language->get('text_error');

      		$data['button_continue'] = $this->language->get('button_continue');

      		$data['continue'] = $this->url->link('common/home');
			
			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 404 Not Found');
			
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (version_compare(VERSION, '2.2.0.0') >= 0) {
				$this->response->setOutput($this->load->view('error/not_found', $data));
			} else {
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
					$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
				} else {
					$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
				}
			}
		}
  	}
	protected function getPageContent($settings) {	
		
		if(isset($this->request->get['route'])) {
			if(strpos(strtolower($this->request->get['route']), 'getpagecontent')) {
				$this->response->redirect($this->url->link('news/ncategory'));
			}
		} 
		
		$this->language->load('news/ncategory');
		
		$this->load->model('catalog/ncategory');
		
		$this->load->model('catalog/news');
		
		$this->load->model('tool/image'); 
		
		$this->load->model('catalog/ncomments');
		
		$data['text_empty'] = $this->language->get('text_empty');			
		$data['button_more'] = $this->language->get('button_more');
		$data['text_refine'] = $this->language->get('text_refine');
		  
		$data['text_posted_by'] = $this->language->get('text_posted_by');
		$data['text_posted_on'] = $this->language->get('text_posted_on');
		$data['text_posted_pon'] = $this->language->get('text_posted_pon');
		$data['text_posted_in'] = $this->language->get('text_posted_in');
		$data['text_updated_on'] = $this->language->get('text_updated_on');
		$data['text_comments'] = $this->language->get('text_comments');	
		$data['text_comments_v'] = $this->language->get('text_comments_v');	
		$data['continue'] = $this->url->link('common/home');
		$data['is_category'] = false;
		$data['is_author'] = false;
		$data['disqus_sname'] = $this->config->get('ncategory_bnews_disqus_sname');
		$data['disqus_status'] = $this->config->get('ncategory_bnews_disqus_status');
		$data['fbcom_status'] = $this->config->get('ncategory_bnews_fbcom_status');
		$data['fbcom_appid'] = $this->config->get('ncategory_bnews_fbcom_appid');
		$data['fbcom_theme'] = $this->config->get('ncategory_bnews_fbcom_theme');
		$data['fbcom_posts'] = $this->config->get('ncategory_bnews_fbcom_posts');
		$date_format = $this->config->get('ncategory_bnews_date_format') ? $this->config->get('ncategory_bnews_date_format') : 'd.m.Y';
		
		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else { 
			$page = 1;
		}	
							
		$limit = $this->config->get('ncategory_bnews_catalog_limit') ? $this->config->get('ncategory_bnews_catalog_limit') : ($this->config->get('config_product_limit') ? $this->config->get('config_product_limit') : $this->config->get($this->config->get('config_theme') . '_product_limit'));
		
		if (isset($this->request->get['ncat'])) {
			$parts = explode('_', (string)$this->request->get['ncat']);	
			$ncategory_id = array_pop($parts);
			$ncategory_info = $settings;
	
		if ($ncategory_info) {
				$data['is_category'] = true;
				$limit = $ncategory_info['column'];
				$display_image = $ncategory_info['top'];
					
				if ($ncategory_info['image']) {
					$data['thumb'] = $this->model_tool_image->resize($ncategory_info['image'], 100, 100);
				} else {
					$data['thumb'] = '';
				}
				$data['heading_title'] = $ncategory_info['name'];						
				$data['description'] = html_entity_decode($ncategory_info['description'], ENT_QUOTES, 'UTF-8');
								
				$data['ncategories'] = array();
			
				$results = $this->model_catalog_ncategory->getncategories($ncategory_id);
			
				foreach ($results as $result) {
					$data['ncategories'][] = array(
						'name'  => $result['name'],
						'href'  => $this->url->link('news/ncategory', 'ncat=' . urlencode(html_entity_decode($this->request->get['ncat'], ENT_QUOTES, 'UTF-8')) . '_' . $result['ncategory_id'])
					);
				}
		}
		} else {
			$ncategory_id = 0;
			$ncategory_info = '';
		}
		
		if (isset($this->request->get['author'])) {
			$author_id = $author_pid = (int)$this->request->get['author'];
			$author_info = $settings['author'];
			if ($author_info) {
				$data['is_author'] = true;
				$data['author'] = $author_info['name'];
				$data['author_image'] = ($author_info['image']) ? $this->model_tool_image->resize($author_info['image'], 80, 80) : false;
				$authordesc = $settings['author_info'];
				if (isset($authordesc[$this->config->get('config_language_id')])) {
					$data['author_desc'] = html_entity_decode($authordesc[$this->config->get('config_language_id')]['description'], ENT_QUOTES, 'UTF-8');
				} else { 
					$data['author_desc'] = ''; 
				}
			}
		} else {
			$author_id = $author_pid =  0;
			$author_info = '';
		}
		
		if (isset($settings['year'])) {
			$year = $settings['year'];
			$month = $settings['month'];
		} else {
			$year = false;
		}
		$data['display_style'] = $this->config->get('ncategory_bnews_display_style');
		
			
			$data['article'] = array();
			
			if ($ncategory_info) {
				$sdata = array(
					'filter_ncategory_id' => $ncategory_id,
					'start'           => ($page - 1) * $limit,
					'limit'           => $limit 
				);
				$data['display_style'] = $ncategory_info['top'];
			} elseif ($author_id) {
				$sdata = array(
					'filter_author_id' => $author_id,
					'start'           => ($page - 1) * $limit,
					'limit'           => $limit 
				);
			} elseif ($year) {
				$sdata = array(
					'filter_year' 	  => $year,
					'filter_month'	  => $month,
					'start'           => ($page - 1) * $limit,
					'limit'           => $limit 
				);
			} else {
				$sdata = array(
					'start'           => ($page - 1) * $limit,
					'limit'           => $limit 
				);
			}

			if(isset($this->request->get['filter_name'])){
				$sdata['filter_name'] = $this->lowerString($this->request->get['filter_name']);
			}

			$bbwidth = ($this->config->get('ncategory_bnews_image_width')) ? $this->config->get('ncategory_bnews_image_width') : 80;
			$bbheight = ($this->config->get('ncategory_bnews_image_height')) ? $this->config->get('ncategory_bnews_image_height') : 80;
			
			if($this->config->get('ncategory_bnews_display_elements')) {
				$elements = $this->config->get('ncategory_bnews_display_elements');
			} else {
				$elements = array("name","image","da","du","author","category","desc","button","com","custom1","custom2","custom3","custom4");
			}
			$news_total = $this->model_catalog_news->getTotalNews($sdata);
			$results = $this->model_catalog_news->getNews($sdata);
			// debug($results);
			// die();
			
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
						$desc = utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $desc_limit);
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
				
				if ($ncategory_id) {
					$href = $this->url->link('news/article', 'ncat=' . $ncategory_id . '&news_id=' . $result['news_id']);
				} elseif($author_pid) {
					$href  = $this->url->link('news/article', 'author=' . $author_pid . '&news_id=' . $result['news_id']);
				} elseif($year) {	
					$href  = $this->url->link('news/article', 'archive=' . $year . '-' . $month . '&news_id=' . $result['news_id']);
				} else {
					$href  = $this->url->link('news/article','news_id=' . $result['news_id']);
				}
				
				$canhref =  $this->url->link('news/article','news_id=' . $result['news_id']);

				$data['download'] = $this->model_catalog_news->getBlogDownloadID($result['news_id']);
// debug($data['download']);
			if($data['download']){
				$data['download'] = $this->url->link('news/ncategory/download', 'document_id='.$data['download']);
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
					'href'        => $href,
					'canhref'     => $canhref,
					'total_comments' => $com,
					'download' => $data['download'] 
				);
			}
		$url = '';
			$pagination = new Pagination();
			$pagination->total = $news_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			
			if ($ncategory_id) {
				$pagination->url = $this->url->link('news/ncategory', 'ncat=' . $ncategory_id . $url . '&page={page}');
			} elseif($author_pid) {
				$pagination->url = $this->url->link('news/ncategory', 'author=' . $author_pid . $url . '&page={page}');
			} elseif($year) {	
				$pagination->url = $this->url->link('news/ncategory', 'archive=' . $year . '-' . $month . $url . '&page={page}');
			} else {
				$pagination->url = $this->url->link('news/ncategory', $url . '&page={page}');
			}

			$data['pagination'] = $pagination->render();
			
			$data['pag_results'] = sprintf($this->language->get('text_pagination'), ($news_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($news_total - $limit)) ? $news_total : ((($page - 1) * $limit) + $limit), $news_total, ceil($news_total / $limit));
		
		if (version_compare(VERSION, '2.2.0.0') >= 0) {
			return $this->load->view('news/ncategory', $data);
		} else {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/news/ncategory.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/news/ncategory.tpl', $data);
			} else {
				return $this->load->view('default/template/news/ncategory.tpl', $data);
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


	public function download(){

		$this->load->model('catalog/news');

		$product_pdf_id = 0;

		if( isset($this->request->get['document_id']) && (int)$this->request->get['document_id'] ){
			$product_pdf_id = (int)$this->request->get['document_id'];
		}

		$download_info = $this->model_catalog_news->getBlogDownload($product_pdf_id);
		/*debug($product_pdf_id);
		debug($download_info);
		die();*/

		if ($download_info) {
			$file = DIR_DOWNLOAD . $download_info['filename'];
			$mask = basename($download_info['mask']);

			if (!headers_sent()) {
				if (file_exists($file)) {
					header('Content-Type: application/octet-stream');
					header('Content-Disposition: attachment; filename="' . ($mask ? $mask : basename($file)) . '"');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Pragma: public');
					header('Content-Length: ' . filesize($file));

					if (ob_get_level()) {
						ob_end_clean();
					}

					readfile($file, 'rb');

					exit();
				} else {
					exit('Error: Could not find file ' . $file . '!');
				}
			} else {
				exit('Error: Headers already sent out!');
			}
		} else {
			$this->response->redirect($this->url->link('news/ncategory', '', true));
		}
	}

}
<?php
class ControllerNewsArticle extends Controller {
	public function index() {
		$this->language->load('news/article');
		
		$this->load->model('catalog/news');
	
		$this->load->model('catalog/ncategory');	
		
		$this->document->addStyle('catalog/view/theme/default/stylesheet/blog-news.css');
		$this->document->addScript('catalog/view/theme/default/blog-mp/jquery.magnific-popup.min.js');
		$this->document->addStyle('catalog/view/theme/default/blog-mp/magnific-popup.css');

		if ($this->config->get('config_google_captcha_status')) {
			$this->document->addScript('https://www.google.com/recaptcha/api.js');
		}

		$data['breadcrumbs'] = array();
		
      	$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	);
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('button_news'),
			'href'      => $this->url->link('news/ncategory')
		);
		
		if (isset($this->request->get['ncat'])) {
			$ncat = '';
				
			foreach (explode('_', $this->request->get['ncat']) as $ncat_id) {
				if (!$ncat) {
					$ncat = (int)$ncat_id;
				} else {
					$ncat .= '_' . (int)$ncat_id;
				}
				
				$ncategory_info = $this->model_catalog_ncategory->getncategory($ncat_id);
				
				if ($ncategory_info) {
					$data['breadcrumbs'][] = array(
						'text'      => $ncategory_info['name'],
						'href'      => $this->url->link('news/ncategory', 'ncat=' . $ncat)
					);
				}
			}
		} 
		//new archive
		if (isset($this->request->get['archive'])) {
			$archive = (string)$this->request->get['archive'];
		} else {
			$archive = false;
		}
		if ($archive) {
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
			$data['breadcrumbs'][] = array(
   	    		'text'      => $month_name . ' ' . $year,
				'href'      => $this->url->link('news/ncategory', 'archive=' . $year . '-' . $month)
        	);
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
				'href'      => $this->url->link('news/ncategory', 'author=' . $author_id)
        	);
		}
		if (isset($this->request->get['news_id'])) {
			$news_id = (int)$this->request->get['news_id'];
		} else {
			$news_id = 0;
		}
		$this->document->addLink($this->url->link('news/article', 'news_id=' . $news_id), 'canonical');
			
		$news_info = $this->model_catalog_news->getNewsStory($news_id);
			
		if ($news_info) {
				if ($news_info['ctitle']) {
					$this->document->setTitle($news_info['ctitle']); 
				} else {
					$this->document->setTitle($news_info['title']); 
				}
				$this->document->setDescription($news_info['meta_desc']);
			    $this->document->setKeywords($news_info['meta_key']);
				if ($archive) {
					$art_url = $this->url->link('news/article', 'archive=' . $year . '-' . $month . '&news_id=' . $news_id);
				} elseif ($author_id) {
					$art_url = $this->url->link('news/article', 'author=' . $author_id . '&news_id=' . $news_id);
				} elseif (isset($this->request->get['ncat'])) {
					$art_url = $this->url->link('news/article', 'ncat=' . urlencode(html_entity_decode($this->request->get['ncat'], ENT_QUOTES, 'UTF-8')) . '&news_id=' . $news_id);
				} else {
					$art_url = $this->url->link('news/article', 'news_id=' . $news_id);
				}
				$data['breadcrumbs'][] = array(
					'text'      => $news_info['title'],
					'href'      => $art_url
				);


			$data['download'] = $this->model_catalog_news->getBlogDownload($news_id);

			if($data['download']){
				$data['download'] = $this->url->link('product/product/download', 'document_id='.$data['download']);
			}
				
				$data['heading_title'] = $news_info['title'];
				$data['button_continue'] = $this->language->get('button_news');
				$data['continue'] = $this->url->link('news/ncategory');
		
				$data['description'] = $this->getPageContent($news_info);
		
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
				$this->document->setTitle = $this->language->get('text_error');
				
				$data['breadcrumbs'][] = array(
					'text'      => $this->language->get('text_error'),
					'href'      => $this->url->link('news/article', 'news_id=' .  $news_id),      		
					'separator' => $this->language->get('text_separator')
				);	
			
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

	public function download(){

		$this->load->model('catalog/product');

		$product_pdf_id = 0;

		if( isset($this->request->get['document_id']) && (int)$this->request->get['document_id'] ){
			$product_pdf_id = (int)$this->request->get['document_id'];
		}

		$download_info = $this->model_catalog_product->getDownload($product_pdf_id);

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
			$this->response->redirect($this->url->link('product/product', '', true));
		}
	}

	protected function getPageContent($news_info) {
	    if(isset($this->request->get['route'])) {
			if(strpos(strtolower($this->request->get['route']), 'getpagecontent')) {
				$this->response->redirect($this->url->link('news/ncategory'));
			}
		} 
		$this->language->load('news/article');
		
		$this->load->model('catalog/news');
		
		$this->load->model('catalog/ncomments');
		
		$this->load->model('tool/image');
		
		$this->load->model('catalog/ncategory');	
		
		if ($this->request->get['news_id']) {
			$data['news_id'] = (int)$this->request->get['news_id'];
		} else {
			$data['news_id'] = 0;
		}
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_review'] = $this->language->get('entry_comment');
		$data['entry_captcha'] = $this->language->get('entry_captcha');
		$data['text_note'] = $this->language->get('text_note');
		$data['nocomment'] = $this->language->get('nocomment');
		$data['writec'] = $this->language->get('writec');
		$data['text_wait'] = $this->language->get('text_wait');
		$data['text_send'] = $this->language->get('bsend');
		$data['title_comments'] = sprintf($this->model_catalog_ncomments->getTotalNcommentsByNewsId($data['news_id']));
		$data['text_coms'] = $this->language->get('title_comments');
		$data['text_posted_pon'] = $this->language->get('text_posted_pon');
		$data['text_posted_in'] = $this->language->get('text_posted_in');
		$data['text_updated_on'] = $this->language->get('text_updated_on');
		$data['text_tags'] = $this->language->get('text_tags');
		$data['text_posted_by'] = $this->language->get('text_posted_by');
		$data['text_posted_on'] = $this->language->get('text_posted_on');
		$data['text_comments'] = $this->language->get('text_comments');	
		$data['text_comments_v'] = $this->language->get('text_comments_v');
		$data['text_comments_to'] = $this->language->get('text_comments_to');
		$data['text_reply_to'] = $this->language->get('text_reply_to');
		$data['text_reply'] = $this->language->get('text_reply');
		$data['author_text'] = $this->language->get('author_text');			
		$data['button_more'] = $this->language->get('button_more');	
		$data['category'] = '';
		$date_format = $this->config->get('ncategory_bnews_date_format') ? $this->config->get('ncategory_bnews_date_format') : 'd.m.Y';
		
		if ($this->config->get('config_google_captcha_status') && (version_compare(VERSION, '2.1.0.0') < 0 && VERSION !='2.1.0.0_rc1')) {
			$data['site_key'] = $this->config->get('config_google_captcha_public');
		} else {
			$data['site_key'] = '';
		}

		$cats = $this->model_catalog_news->getNcategoriesbyNewsId($data['news_id']);
		if ($cats) {
			$comma = 0;
			foreach($cats as $catid) {
				$catinfo = $this->model_catalog_ncategory->getncategory($catid['ncategory_id']);
				if ($catinfo) {
					if ($comma) {
						$data['category'] .= ', <a href="'.$this->url->link('news/ncategory', 'ncat=' . $catinfo['ncategory_id']).'">'.$catinfo['name'].'</a>';
					} else {
						$data['category'] .= '<a href="'.$this->url->link('news/ncategory', 'ncat=' . $catinfo['ncategory_id']).'">'.$catinfo['name'].'</a>';
					}
					$comma++;
				}
			}
		}
		
		$data['gallery_type'] = isset($news_info['gal_slider_t']) ? $news_info['gal_slider_t'] : 1;
		if ($data['gallery_type'] != 1) {
			$this->document->addScript('catalog/view/theme/default/blog-mp/jssor.slider.mini.js');
		}
		$data['gallery_height'] = $news_info['gal_slider_h'];
		$data['gallery_width'] = $news_info['gal_slider_w'];
		$data['acom'] = $news_info['acom'];
		$data['heading_title'] = $news_info['title'];
		$data['description'] = html_entity_decode($news_info['description'], ENT_QUOTES, 'UTF-8');
		$data['description'] = str_replace("<video", "<iframe", $data['description']);
		$data['description'] = str_replace("</video>", "</iframe>", $data['description']);
		$data['custom1'] = html_entity_decode($news_info['cfield1'], ENT_QUOTES, 'UTF-8');
		$data['custom2'] = html_entity_decode($news_info['cfield2'], ENT_QUOTES, 'UTF-8');
		$data['custom3'] = html_entity_decode($news_info['cfield3'], ENT_QUOTES, 'UTF-8');
		$data['custom4'] = html_entity_decode($news_info['cfield4'], ENT_QUOTES, 'UTF-8');
		$data['date_added'] = date($date_format, strtotime($news_info['date_added']));
		$data['date_updated'] = date($date_format, strtotime($news_info['date_updated']));
		if ($data['date_added'] == $data['date_updated']) { $data['date_updated'] = ''; }
		if ($news_info['nauthor_id']) {
			$data['author_link'] = $this->url->link('news/ncategory', 'author=' . $news_info['nauthor_id']);
			$data['author'] = $news_info['author'];
			if ($data['author']) {
				if (method_exists($this->document , 'addExtraTag')) {
					$this->document->addExtraTag('noprop', $data['author'], 'author');
				}
			}
			$data['author_image'] = ($news_info['nimage']) ? $this->model_tool_image->resize($news_info['nimage'], 70, 70) : false;
			$authordesc = $this->model_catalog_news->getNauthorDescriptions($news_info['nauthor_id']);
			if (isset($authordesc[$this->config->get('config_language_id')])) {
				$data['author_desc'] = html_entity_decode($authordesc[$this->config->get('config_language_id')]['description'], ENT_QUOTES, 'UTF-8');
			} else { 
				$data['author_desc'] = ''; 
			}
		} else {
			$data['author'] = '';
		}
		$data['ntags'] = array();
		if ($news_info['ntags']) {		
			$ntags = explode(',', $news_info['ntags']);
			foreach ($ntags as $ntag) {
				$data['ntags'][] = array(
					'ntag' => trim($ntag),
					'href' => $this->url->link('news/search', 'article_tag=' . trim($ntag))
				);
			}
		}
		$data['button_news'] = $this->language->get('button_news');
				
		$data['button_cart'] = $this->language->get('button_cart');
		
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		
		$data['button_compare'] = $this->language->get('button_compare');
				
		$data['news_prelated'] = $this->language->get('news_prelated');
				
		$data['news_related'] = $this->language->get('news_related');
		
		$bwidth = ($this->config->get('ncategory_bnews_thumb_width')) ? $this->config->get('ncategory_bnews_thumb_width') : 230;
        $bheight = ($this->config->get('ncategory_bnews_thumb_height')) ? $this->config->get('ncategory_bnews_thumb_height') : 230;
		if ($news_info['image']) {
				$data['thumb'] = $this->model_tool_image->resize($news_info['image'], $bwidth, $bheight);
				$data['popup'] = $this->model_tool_image->resize($news_info['image'], 600, 600);
		} else {
				$data['thumb'] = '';
				$data['popup'] = '';
		}
				
		$data['article'] = array();
		
		$bbwidth = ($this->config->get('ncategory_bnews_image_width')) ? $this->config->get('ncategory_bnews_image_width') : 80;
        $bbheight = ($this->config->get('ncategory_bnews_image_height')) ? $this->config->get('ncategory_bnews_image_height') : 80;
			
		if($this->config->get('ncategory_bnews_display_elements')) {
				$elements = $this->config->get('ncategory_bnews_display_elements');
		} else {
				$elements = array("name","image","da","du","author","category","desc","button","com","custom1","custom2","custom3","custom4");
		}
		
		$data['page_url'] = $this->url->link('news/article', '&news_id=' . $data['news_id']);
		$data['disqus_sname'] = $this->config->get('ncategory_bnews_disqus_sname');
		$data['disqus_id'] = 'article_'.$data['news_id'];
		$data['disqus_status'] = $this->config->get('ncategory_bnews_disqus_status');
		$data['fbcom_status'] = $this->config->get('ncategory_bnews_fbcom_status');
		$data['fbcom_appid'] = $this->config->get('ncategory_bnews_fbcom_appid');
		$data['fbcom_theme'] = $this->config->get('ncategory_bnews_fbcom_theme');
		$data['fbcom_posts'] = $this->config->get('ncategory_bnews_fbcom_posts');
		
		if (method_exists($this->document , 'addExtraTag')) {
		  if (!$this->config->get('ncategory_bnews_facebook_tags')) {
			$this->document->addExtraTag('og:title', $data['heading_title']);
			if ($data['thumb']) {
				$this->document->addExtraTag('og:image', $data['thumb']);
			}
			$this->document->addExtraTag('og:url', $data['page_url']);
			$this->document->addExtraTag('og:type', 'article');
			$this->document->addExtraTag('og:description', trim(utf8_substr(strip_tags(html_entity_decode($data['description'], ENT_QUOTES, 'UTF-8')), 0, 200) . '...'));
		  }
		  if (!$this->config->get('ncategory_bnews_twitter_tags')) {
			$this->document->addExtraTag('twitter:card', 'summary');
			$this->document->addExtraTag('twitter:url', $data['page_url']);
			$this->document->addExtraTag('twitter:title', $data['heading_title']);
			$this->document->addExtraTag('twitter:description', trim(utf8_substr(strip_tags(html_entity_decode($data['description'], ENT_QUOTES, 'UTF-8')), 0, 200) . '...'));
			if ($data['thumb']) {
				$this->document->addExtraTag('twitter:image', $data['thumb']);
			}
		  }
		}
		
		$data['article_videos'] = array();	
		
		$vid_results = $this->model_catalog_news->getArticleVideos($data['news_id']);
		
		foreach ($vid_results as $result) {
			$result['text'] = unserialize($result['text']); 
			$result['text'] = isset($result['text'][$this->config->get('config_language_id')]) ? $result['text'][$this->config->get('config_language_id')] : '' ;
			$code = '<iframe frameborder="0" allowfullscreen src="' . str_replace("watch?v=","embed/",$result['video']) . '" height="'.$result['height'].'"width="100%" style="max-width:'.$result['width'].'px"></iframe>';
			
			$data['article_videos'][] = array(
					'text'  => $result['text'],
					'code' => $code
			);
		}
		
		$data['gallery_images'] = array();

		$gal_results = $this->model_catalog_news->getArticleGallery($data['news_id']);

		foreach ($gal_results as $result) {
			$result['text'] = unserialize($result['text']); 
			$result['text'] = isset($result['text'][$this->config->get('config_language_id')]) ? $result['text'][$this->config->get('config_language_id')] : '' ;
			$data['gallery_images'][] = array(
					'text'  => $result['text'],
					'popup' => $this->model_tool_image->resize($result['image'], $news_info['gal_popup_w'], $news_info['gal_popup_h']),
					'thumb' => $this->model_tool_image->resize($result['image'], $news_info['gal_thumb_w'], $news_info['gal_thumb_h'])
			);
		}
		$data['products'] = array();
		$data['text_tax'] = $this->language->get('text_tax');
		$results = $this->model_catalog_news->getProductRelated($data['news_id']);
			
		foreach ($results as $result) {
			if (!$result['product_id']) continue;
				if (version_compare(VERSION, '2.2.0.0') >= 0) {
					$image = ($result['image']) ? $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_related_width'), $this->config->get($this->config->get('config_theme') . '_image_related_height')) : false;
				} else {
					$image = ($result['image']) ? $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height')) : false;
				}
				
				if (version_compare(VERSION, '2.2.0.0') >= 0) {
					$price = (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) ? $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']) : false;	
				} else {
					$price = (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) ? $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))) : false;	
				}
				if (version_compare(VERSION, '2.2.0.0') >= 0) {
					$special = ((float)$result['special']) ? $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']) : false;
				} else {
					$special = ((float)$result['special']) ? $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'))) : false;
				}

				if ($this->config->get('config_tax')) {
					$tax = (version_compare(VERSION, '2.2.0.0') >= 0) ? ($this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency'])) : $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
				} else {
					$tax = false;
				}
				
				$rating = ($this->config->get('config_review_status')) ? (int)$result['rating'] : false;
				
				$data['products'][] = array(
					'product_id' => $result['product_id'],
					'thumb'   	 => $image,
					'name'    	 => $result['name'],
					'description' => (version_compare(VERSION, '2.2.0.0') >= 0) ? (utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..') : (utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..'),
					'price'   	 => $price,
					'tax'         => $tax,
					'special' 	 => $special,
					'rating'     => $rating,
					'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
				);
		}	
		$results = $this->model_catalog_news->getNewsRelated($data['news_id']);
			
		foreach ($results as $result) {
			if ($result['title']) {
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
		}

		$data['prev_news'] = '';
		$data['next_news'] = '';

		$data['back'] = $this->url->link('news/ncategory');

		if(isset($catid['ncategory_id'])){

			$data['back'] = $this->url->link('news/ncategory', 'ncat=' . $catid['ncategory_id']);

			$PrevNex_array = $this->model_catalog_news->getPrevNext($catid['ncategory_id'], $news_info['news_id']);

			if($PrevNex_array['prev_news_id'] > 0){
				$data['prev_news']     = $this->url->link('news/article', 'ncat=' . $catid['ncategory_id']. '&news_id=' .  $PrevNex_array['prev_news_id']);
			}

			if($PrevNex_array['next_news_id'] > 0){
				$data['next_news']     = $this->url->link('news/article', 'ncat=' . $catid['ncategory_id']. '&news_id=' .  $PrevNex_array['next_news_id']);
			}
		}
		
		$data['news'] = $this->url->link('news/headlines');
		if (isset($this->request->get['page'])) {
				$page = (int)$this->request->get['page'];
		} else {
				$page = 1;
		}

		// Captcha
		if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
			$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));
		} else {
			$data['captcha'] = '';
		}

		$data['captcha_type'] = $this->config->get('config_captcha');
				
		$data['comment'] = array();
		
		$data['customer_name'] = $this->customer->getFirstName() ? $this->customer->getFirstName() : '';
		
		$comment_total = $this->model_catalog_ncomments->getTotalJNcommentsByNewsId($data['news_id']);
			
		$results = $this->model_catalog_ncomments->getCommentsByNewsId($data['news_id'], ($page - 1) * 10, 10);
      		
		foreach ($results as $result) {
			$replies = array();
			$allreplies = $this->model_catalog_ncomments->getCommentsByNewsId($data['news_id'], 0, 1000, $result['ncomment_id']);
			foreach ($allreplies as $reply) {
				$replies[] = array (
        		'ncomment_id' => $reply['author'],
        		'author'      => $reply['author'],
				'text'        => strip_tags($reply['text']),
        		'date_added'  => date($date_format, strtotime($reply['date_added']))
				);
			}
        	$data['comment'][] = array(
        		'ncomment_id' => $result['ncomment_id'],
        		'author'      => $result['author'],
				'replies'     => $replies,
				'text'        => strip_tags($result['text']),
        		'date_added'  => date($date_format, strtotime($result['date_added']))
        	);
		}
			$limit = 10;
			$pagination = new Pagination();
			$pagination->total = $comment_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('news/article', 'news_id=' . $data['news_id'] . '&page={page}');

			$data['pagination'] = $pagination->render();
			
			$data['pag_results'] = sprintf($this->language->get('text_pagination'), ($comment_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($comment_total - $limit)) ? $comment_total : ((($page - 1) * $limit) + $limit), $comment_total, ceil($comment_total / $limit));
			

		if (version_compare(VERSION, '2.2.0.0') >= 0) {
			return $this->load->view('news/article', $data);
		} else {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/news/article.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/news/article.tpl', $data);
			} else {
				return $this->load->view('default/template/news/article.tpl', $data);
			}
		}	

	}
	public function writecomment() {
		$this->language->load('news/article');
		
		$this->load->model('catalog/ncomments');
		
		$json = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
		
		if (isset($this->request->post['name']) && (strlen(utf8_decode($this->request->post['name'])) < 3) || (strlen(utf8_decode($this->request->post['name'])) > 25)) {
			$json['error'] = $this->language->get('error_name');
		}
		
		if (isset($this->request->post['text']) && (strlen(utf8_decode($this->request->post['text'])) < 25) || (strlen(utf8_decode($this->request->post['text'])) > 1000)) {
			$json['error'] = $this->language->get('error_text');
		}

		if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
			$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

			if ($captcha) {
				$json['error'] = $captcha;
			}
		}
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && !isset($json['error'])) {
			$this->model_catalog_ncomments->addComment($this->request->get['news_id'], $this->request->post);
			
			$json['success'] = $this->language->get('text_success');
		}
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

}

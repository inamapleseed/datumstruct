<?php
class ControllerEventSbNews extends Controller {

	public function menu(&$view, &$data) {
		
		$this->language->load('common/newspanel');
		// //blog links
		
		$news_blog = array();

		

		if ($this->user->hasPermission('access', 'catalog/ncategory')) {
			$news_blog[] = array(
				'name'	   => $this->language->get('entry_ncategory'),
				'href'     => $this->url->link('catalog/ncategory', 'token=' . $this->session->data['token'], true),
				'children' => array()		
			);	
		}

		if ($this->user->hasPermission('access', 'catalog/news')) {
			$news_blog[] = array(
				'name'	   => $this->language->get('entry_npages'),
				'href'     => $this->url->link('catalog/news', 'token=' . $this->session->data['token'], true),
				'children' => array()		
			);	
		}

		if ($this->user->hasPermission('access', 'catalog/nauthor')) {
			$news_blog[] = array(
				'name'	   => $this->language->get('text_nauthor'),
				'href'     => $this->url->link('catalog/nauthor', 'token=' . $this->session->data['token'], true),
				'children' => array()		
			);	
		}
		
		if ($this->user->hasPermission('access', 'catalog/ncomments')) {
			$news_blog[] = array(
				'name'	   => $this->language->get('text_commod'),
				'href'     => $this->url->link('catalog/ncomments', 'token=' . $this->session->data['token'], true),
				'children' => array()		
			);	
		}

		
		
		/*
		if ($this->user->hasPermission('access', 'extension/module/ncategory')) {
			$news_blog[] = array(
				'name'	   => $this->language->get('entry_ncmod'),
				'href'     => $this->url->link('extension/module/ncategory', 'token=' . $this->session->data['token'], true),
				'children' => array()		
			);	
		}

		if ($this->user->hasPermission('access', 'extension/module/news_archive')) {
			$news_blog[] = array(
				'name'	   => $this->language->get('entry_namod'),
				'href'     => $this->url->link('extension/module/news_archive', 'token=' . $this->session->data['token'], true),
				'children' => array()		
			);	
		}

		if ($this->user->hasPermission('access', 'extension/module/news_latest')) {
			$news_blog[] = array(
				'name'	   => $this->language->get('entry_nmod'),
				'href'     => $this->url->link('extension/module/news_latest', 'token=' . $this->session->data['token'], true),
				'children' => array()		
			);	
		}
		*/
		if ($news_blog) {
			$news_blog = array(
				'id'       => 'menu-news-blog',
				'icon'	   => 'fa-book', 
				'name'	   => 'News / Blog',
				'href'     => '',
				'children' => $news_blog
			);

			array_splice($data['menus'], 2, 0, array($news_blog));
		}

	}

}

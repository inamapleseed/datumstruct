<?php
class ControllerCommonNewspanel extends Controller {
	private $error = array(); 

	public function index() {
		
		if(!$this->validate()){
			return '';
		}
	
		$this->language->load('common/newspanel');
		
		$this->load->model('catalog/ncomments');
		
		$this->load->model('catalog/news');
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else if (isset($this->session->data['warning']) ) {
			$data['error_warning'] = $this->session->data['warning'];
			unset($this->session->data['warning']);
		}
		else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['nmod'] = $this->url->link('extension/module/news_latest', 'token=' . $this->session->data['token'], 'SSL');

		$data['ncmod'] = $this->url->link('extension/module/ncategory', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['namod'] = $this->url->link('extension/module/news_archive', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['npages'] = $this->url->link('catalog/news', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['nauthor'] = $this->url->link('catalog/nauthor', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['ncategory'] = $this->url->link('catalog/ncategory', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['tocomments'] = $this->url->link('catalog/ncomments', 'token=' . $this->session->data['token'], 'SSL');	
		
		$data['validatingblog'] = $this->checkForStuff();
		
		$data['adddb'] = $this->url->link('common/newspanel/install', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['updb'] = $this->url->link('common/newspanel/upgradev3', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['updb2'] = $this->url->link('common/newspanel/upgradev4', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['updb3'] = $this->url->link('common/newspanel/upgradev5', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['updb4'] = $this->url->link('common/newspanel/upgradev6', 'token=' . $this->session->data['token'], 'SSL');

		$data['updb5'] = $this->url->link('common/newspanel/upgradev7', 'token=' . $this->session->data['token'], 'SSL');

		$data['reg_events'] = $this->url->link('common/newspanel/registerEvents', 'token=' . $this->session->data['token'], 'SSL');
		
		
		if ($this->checkForStuff() == "ok") {
			$data['total_coments'] = $this->model_catalog_ncomments->getTotalComments(); 
		
			$data['total_comments_approval'] = $this->model_catalog_ncomments->getTotalCommentsAwaitingApproval();
		
			$data['total_articles'] =  $this->model_catalog_news->getTotalNews();
		}

		$data['text_articles'] = $this->language->get('text_articles');
		
		$data['text_comtot'] = $this->language->get('text_comtot');
		
		$data['text_tcaa'] = $this->language->get('text_tcaa');
		
		$data['text_nauthor'] = $this->language->get('text_nauthor');
		
		$data['button_save'] = $this->language->get('button_save');
		
		$data['text_yess'] = $this->language->get('text_bysort');
		
		$data['text_noo'] = $this->language->get('text_latest');
		
		$data['text_commod'] = $this->language->get('text_commod');
		
		$data['text_bnews_image'] = $this->language->get('text_bnews_image');
		
		$data['text_bnews_thumb'] = $this->language->get('text_bnews_thumb');
		
		$data['text_bnews_order'] = $this->language->get('text_bnews_order');
		
		$data['text_bsettings'] = $this->language->get('text_bsettings');
		
		$data['text_bwidth'] = $this->language->get('text_bwidth');
		
		$data['text_bheight'] = $this->language->get('text_bheight');
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['entry_npages'] = $this->language->get('entry_npages');
		
		$data['entry_nmod'] = $this->language->get('entry_nmod');
		
		$data['entry_ncmod'] = $this->language->get('entry_ncmod');
		
		$data['entry_namod'] = $this->language->get('entry_namod');
		
		$data['entry_ncategory'] = $this->language->get('entry_ncategory');
		
		$data['text_no_results'] = $this->language->get('text_no_results');
		
		$data['column_title'] = $this->language->get('column_title');
		
		$data['column_status'] = $this->language->get('column_status');
		
		return $this->load->view('common/newspanel.tpl', $data);
	}

	private function match_table_field($table, $field = NULL) {
		$table = $this->db->escape($table);
		$query = $this->db->query("SHOW TABLES LIKE '{$table}'");
			if ($query->num_rows) {
				if (empty($field)) {
					return TRUE;
				} else {
					$query = $this->db->query("SHOW COLUMNS FROM `{$table}` LIKE '{$field}'");
					if ($query->num_rows) {
						//$row = $query->row;
						//return isset($row[$field]);
						//return in_array($field, $row);
						return true;
					}
				}
			}
			return FALSE;
	}

	private function checkEvent() {

		$this->load->model('extension/event');

		return $this->model_extension_event->getEvent('sb_news', 'admin/view/common/column_left/before', 'event/sb_news/menu');

	}

	private function checkForStuff() {
		$checknews = $this->match_table_field(DB_PREFIX ."ncomments");

		$checknews_after_v6 = $this->match_table_field(DB_PREFIX ."sb_ncomments");

		$checkupdate = $this->match_table_field(DB_PREFIX ."news_description", "ctitle");

		$checkupdate2 = $this->match_table_field(DB_PREFIX ."news", "date_pub");

		$checkupdate3 = $this->match_table_field(DB_PREFIX ."ncomments", "language_id");

		$checkupdate4 = $this->match_table_field(DB_PREFIX ."sb_news_archive");

		$checkupdate5 = $this->match_table_field(DB_PREFIX ."sb_ncategory_to_group");

		$checknamechange = $this->match_table_field(DB_PREFIX ."sb_news");

		$checkevents = $this->checkEvent();

		if ($checknamechange && $checkupdate5 && $checkevents) {
			return "ok";
		} elseif ($checknamechange && $checkupdate5 && !$checkevents) {
			return "ne";
		} elseif ($checknews && !$checkupdate && !$checknamechange) {
			return "nu";
		} elseif ($checknews && !$checkupdate2 && !$checknamechange) {
			return "nu2";
		} elseif ($checknews && !$checkupdate3 && !$checknamechange) {
			return "nu3";
		} elseif ($checknews && !$checkupdate4) {
			return "nu4";
		} elseif ($checknews_after_v6 && !$checkupdate5) {
			return "nu5";
		} else {
			return "none";
		}
	}

	public function registerEvents() { //register the blog extension events

		$this->language->load('common/newspanel');

		if ($this->validate() && $this->checkForStuff() == "ne") {
			
			$this->load->model('extension/event');

			$this->model_extension_event->deleteEvent('sb_news');

			$this->model_extension_event->addEvent('sb_news', 'admin/view/common/column_left/before', 'event/sb_news/menu');
			$this->model_extension_event->addEvent('sb_news', 'catalog/view/common/header/before', 'event/sb_news/menu');

			$this->session->data['success'] = 'News/Blog Events were succesfully registered! You should now see a news/blog entry in the left menu.';
			$this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
		} else {
			$this->session->data['warning'] = $this->language->get('text_blogpanel_permissions');
			$this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}

	public function upgradev7() { //add news archive table
		$this->language->load('common/newspanel');
		if ($this->validate() && $this->checkForStuff() == "nu5") {
			$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_news_to_group (
							`news_id` int(11) NOT NULL,
							`group_id` int(11) NOT NULL DEFAULT '0',
							PRIMARY KEY (`news_id`,`group_id`)
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
			$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_ncategory_to_group (
							`ncategory_id` int(11) NOT NULL,
							`group_id` int(11) NOT NULL,
							PRIMARY KEY (`ncategory_id`,`group_id`)
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
			$this->session->data['success'] = $this->language->get('text_blog_upgrade_success');
			$this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
		} else {
			$this->session->data['warning'] = $this->language->get('text_blogpanel_permissions');
			$this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}
	public function upgradev6() { //add news archive table
		$this->language->load('common/newspanel');
		if ($this->validate() && $this->checkForStuff() == "nu4") {
			$this->db->query("RENAME TABLE ". DB_PREFIX . "ncategory TO ". DB_PREFIX . "sb_ncategory,
				". DB_PREFIX . "ncategory_description TO ". DB_PREFIX . "sb_ncategory_description,
				". DB_PREFIX . "ncategory_to_layout TO ". DB_PREFIX . "sb_ncategory_to_layout,
				". DB_PREFIX . "ncategory_to_store TO ". DB_PREFIX . "sb_ncategory_to_store,
				". DB_PREFIX . "ncomments TO ". DB_PREFIX . "sb_ncomments,
				". DB_PREFIX . "news TO ". DB_PREFIX . "sb_news,
				". DB_PREFIX . "news_description TO ". DB_PREFIX . "sb_news_description,
				". DB_PREFIX . "news_related TO ". DB_PREFIX . "sb_news_related,
				". DB_PREFIX . "news_to_layout TO ". DB_PREFIX . "sb_news_to_layout,
				". DB_PREFIX . "news_to_ncategory TO ". DB_PREFIX . "sb_news_to_ncategory,
				". DB_PREFIX . "news_to_store TO ". DB_PREFIX . "sb_news_to_store,
				". DB_PREFIX . "nauthor TO ". DB_PREFIX . "sb_nauthor,
				". DB_PREFIX . "nauthor_description TO ". DB_PREFIX . "sb_nauthor_description,
				". DB_PREFIX . "news_gallery TO ". DB_PREFIX . "sb_news_gallery,
				". DB_PREFIX . "news_video TO ". DB_PREFIX . "sb_news_video;
			");
			$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_news_archive (
						`news_archive_id` int(11) NOT NULL AUTO_INCREMENT,
						`store_id` int(3) NOT NULL,
						`year` int(4) NOT NULL,
						`months` varchar(1024) DEFAULT NULL,
						PRIMARY KEY (`news_archive_id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
			$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_news_to_group (
							`news_id` int(11) NOT NULL,
							`group_id` int(11) NOT NULL DEFAULT '0',
							PRIMARY KEY (`news_id`,`group_id`)
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
			$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_ncategory_to_group (
							`ncategory_id` int(11) NOT NULL,
							`group_id` int(11) NOT NULL,
							PRIMARY KEY (`ncategory_id`,`group_id`)
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
	
	
			$this->session->data['success'] = $this->language->get('text_blog_upgrade_success');
			$this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
		} else {
			$this->session->data['warning'] = $this->language->get('text_blogpanel_permissions');
			$this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}
	public function upgradev5() { //add comment language
		$this->language->load('common/newspanel');
	if ($this->validate() && $this->checkForStuff() == "nu3") {
		  $this->db->query("RENAME TABLE ". DB_PREFIX . "ncategory TO ". DB_PREFIX . "sb_ncategory,
				". DB_PREFIX . "ncategory_description TO ". DB_PREFIX . "sb_ncategory_description,
				". DB_PREFIX . "ncategory_to_layout TO ". DB_PREFIX . "sb_ncategory_to_layout,
				". DB_PREFIX . "ncategory_to_store TO ". DB_PREFIX . "sb_ncategory_to_store,
				". DB_PREFIX . "ncomments TO ". DB_PREFIX . "sb_ncomments,
				". DB_PREFIX . "news TO ". DB_PREFIX . "sb_news,
				". DB_PREFIX . "news_description TO ". DB_PREFIX . "sb_news_description,
				". DB_PREFIX . "news_related TO ". DB_PREFIX . "sb_news_related,
				". DB_PREFIX . "news_to_layout TO ". DB_PREFIX . "sb_news_to_layout,
				". DB_PREFIX . "news_to_ncategory TO ". DB_PREFIX . "sb_news_to_ncategory,
				". DB_PREFIX . "news_to_store TO ". DB_PREFIX . "sb_news_to_store,
				". DB_PREFIX . "nauthor TO ". DB_PREFIX . "sb_nauthor,
				". DB_PREFIX . "nauthor_description TO ". DB_PREFIX . "sb_nauthor_description,
				". DB_PREFIX . "news_gallery TO ". DB_PREFIX . "sb_news_gallery,
				". DB_PREFIX . "news_video TO ". DB_PREFIX . "sb_news_video;
			");
			$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_news_archive (
						`news_archive_id` int(11) NOT NULL AUTO_INCREMENT,
						`store_id` int(3) NOT NULL,
						`year` int(4) NOT NULL,
						`months` varchar(1024) DEFAULT NULL,
						PRIMARY KEY (`news_archive_id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
			$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_news_to_group (
							`news_id` int(11) NOT NULL,
							`group_id` int(11) NOT NULL DEFAULT '0',
							PRIMARY KEY (`news_id`,`group_id`)
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
			$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_ncategory_to_group (
							`ncategory_id` int(11) NOT NULL,
							`group_id` int(11) NOT NULL,
							PRIMARY KEY (`ncategory_id`,`group_id`)
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
		
	$this->db->query("ALTER TABLE ". DB_PREFIX ."sb_ncomments ADD `language_id` int(2) NOT NULL");
	
	$this->load->model('localisation/language');
	$languages = array();
	$langcode = $this->config->get('config_language');
	$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE status = '1'");
	foreach ($query->rows as $result) {
		$languages[$result['code']] = $result;
	}
	$langid = $languages[$langcode]['language_id'];
	$this->db->query("UPDATE ". DB_PREFIX ."sb_ncomments set language_id = '" . (int)$langid  . "'");
	
	$this->session->data['success'] = $this->language->get('text_blog_upgrade_success');
	$this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
	} else {
	$this->session->data['warning'] = $this->language->get('text_blogpanel_permissions');
	$this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
	}
	}
	public function upgradev4() { //new columns + new tables
		$this->language->load('common/newspanel');
	if ($this->validate() && $this->checkForStuff() == "nu2") {
		   $this->db->query("RENAME TABLE ". DB_PREFIX . "ncategory TO ". DB_PREFIX . "sb_ncategory,
				". DB_PREFIX . "ncategory_description TO ". DB_PREFIX . "sb_ncategory_description,
				". DB_PREFIX . "ncategory_to_layout TO ". DB_PREFIX . "sb_ncategory_to_layout,
				". DB_PREFIX . "ncategory_to_store TO ". DB_PREFIX . "sb_ncategory_to_store,
				". DB_PREFIX . "ncomments TO ". DB_PREFIX . "sb_ncomments,
				". DB_PREFIX . "news TO ". DB_PREFIX . "sb_news,
				". DB_PREFIX . "news_description TO ". DB_PREFIX . "sb_news_description,
				". DB_PREFIX . "news_related TO ". DB_PREFIX . "sb_news_related,
				". DB_PREFIX . "news_to_layout TO ". DB_PREFIX . "sb_news_to_layout,
				". DB_PREFIX . "news_to_ncategory TO ". DB_PREFIX . "sb_news_to_ncategory,
				". DB_PREFIX . "news_to_store TO ". DB_PREFIX . "sb_news_to_store,
				". DB_PREFIX . "nauthor TO ". DB_PREFIX . "sb_nauthor,
				". DB_PREFIX . "nauthor_description TO ". DB_PREFIX . "sb_nauthor_description;
			");
			$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_news_archive (
						`news_archive_id` int(11) NOT NULL AUTO_INCREMENT,
						`store_id` int(3) NOT NULL,
						`year` int(4) NOT NULL,
						`months` varchar(1024) DEFAULT NULL,
						PRIMARY KEY (`news_archive_id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
		
	$this->db->query("ALTER TABLE ". DB_PREFIX ."sb_news ADD `gal_thumb_w` int(5) NOT NULL, ADD `gal_thumb_h` int(5) NOT NULL, ADD `gal_popup_w` int(5) NOT NULL, ADD `gal_popup_h` int(5) NOT NULL, ADD `gal_slider_h` int(4) NOT NULL, ADD `gal_slider_t` int(1) NOT NULL, ADD `date_pub` datetime DEFAULT NULL, ADD `gal_slider_w` int(4) NOT NULL");
	$this->db->query("ALTER TABLE ". DB_PREFIX ."sb_ncomments ADD `language_id` int(2) NOT NULL");
	
	$this->load->model('localisation/language');
	$languages = array();
	$langcode = $this->config->get('config_language');
	$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE status = '1'");
	foreach ($query->rows as $result) {
		$languages[$result['code']] = $result;
	}
	$langid = $languages[$langcode]['language_id'];
	$this->db->query("UPDATE ". DB_PREFIX ."sb_ncomments set language_id = '" . (int)$langid  . "'");
	
	$this->db->query("UPDATE ". DB_PREFIX ."sb_news SET date_pub = date_added");
	$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_news_gallery (
						`news_image_id` int(11) NOT NULL AUTO_INCREMENT,
						`news_id` int(11) NOT NULL,
						`image` varchar(512) DEFAULT NULL,
						`text` text NOT NULL,
						`sort_order` int(3) NOT NULL DEFAULT '0',
						PRIMARY KEY (`news_image_id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
	$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_news_video (
						`news_video_id` int(11) NOT NULL AUTO_INCREMENT,
						`news_id` int(11) NOT NULL,
						`text` text COLLATE utf8_bin NOT NULL,
						`video` varchar(255) COLLATE utf8_bin DEFAULT NULL,
						`width` int(11) NOT NULL,
						`height` int(11) NOT NULL,
						`sort_order` int(3) NOT NULL DEFAULT '0',
						PRIMARY KEY (`news_video_id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1");
	$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_news_to_group (
							`news_id` int(11) NOT NULL,
							`group_id` int(11) NOT NULL DEFAULT '0',
							PRIMARY KEY (`news_id`,`group_id`)
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
	$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_ncategory_to_group (
							`ncategory_id` int(11) NOT NULL,
							`group_id` int(11) NOT NULL,
							PRIMARY KEY (`ncategory_id`,`group_id`)
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
	
	$this->session->data['success'] = $this->language->get('text_blog_upgrade_success');
	$this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
	} else {
	$this->session->data['warning'] = $this->language->get('text_blogpanel_permissions');
	$this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
	}
	}
	public function upgradev3() { //lots of new stuff
		$this->language->load('common/newspanel');
	if ($this->validate() && $this->checkForStuff() == "nu") {
			$this->db->query("RENAME TABLE ". DB_PREFIX . "ncategory TO ". DB_PREFIX . "sb_ncategory,
				". DB_PREFIX . "ncategory_description TO ". DB_PREFIX . "sb_ncategory_description,
				". DB_PREFIX . "ncategory_to_layout TO ". DB_PREFIX . "sb_ncategory_to_layout,
				". DB_PREFIX . "ncategory_to_store TO ". DB_PREFIX . "sb_ncategory_to_store,
				". DB_PREFIX . "ncomments TO ". DB_PREFIX . "sb_ncomments,
				". DB_PREFIX . "news TO ". DB_PREFIX . "sb_news,
				". DB_PREFIX . "news_description TO ". DB_PREFIX . "sb_news_description,
				". DB_PREFIX . "news_related TO ". DB_PREFIX . "sb_news_related,
				". DB_PREFIX . "news_to_layout TO ". DB_PREFIX . "sb_news_to_layout,
				". DB_PREFIX . "news_to_ncategory TO ". DB_PREFIX . "sb_news_to_ncategory,
				". DB_PREFIX . "news_to_store TO ". DB_PREFIX . "sb_news_to_store;
			");
			$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_news_archive (
						`news_archive_id` int(11) NOT NULL AUTO_INCREMENT,
						`store_id` int(3) NOT NULL,
						`year` int(4) NOT NULL,
						`months` varchar(1024) DEFAULT NULL,
						PRIMARY KEY (`news_archive_id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
		
	$this->db->query("ALTER TABLE ". DB_PREFIX ."sb_news_description ADD `ctitle` VARCHAR( 255 ) NOT NULL ,ADD `description2` text COLLATE utf8_bin NOT NULL ,ADD `ntags` text NOT NULL, ADD `cfield1` VARCHAR( 255 ) NOT NULL, ADD `cfield2` VARCHAR( 255 ) NOT NULL, ADD `cfield3` VARCHAR( 255 ) NOT NULL, ADD `cfield4` VARCHAR( 255 ) NOT NULL");
	$this->db->query("ALTER TABLE ". DB_PREFIX ."sb_news ADD `nauthor_id` int(11) NOT NULL, ADD `date_updated` datetime DEFAULT NULL, ADD`image2` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0', ADD `gal_thumb_w` int(5) NOT NULL, ADD `gal_thumb_h` int(5) NOT NULL, ADD `gal_popup_w` int(5) NOT NULL, ADD `gal_popup_h` int(5) NOT NULL, ADD `gal_slider_h` int(4) NOT NULL, ADD `gal_slider_t` int(1) NOT NULL, ADD `date_pub` datetime DEFAULT NULL, ADD `gal_slider_w` int(4) NOT NULL");
	$this->db->query("ALTER TABLE ". DB_PREFIX ."sb_ncomments ADD `reply_id` int(11) NOT NULL DEFAULT '0', ADD `language_id` int(2) NOT NULL");
	
	$this->load->model('localisation/language');
	$languages = array();
	$langcode = $this->config->get('config_language');
	$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE status = '1'");
	foreach ($query->rows as $result) {
		$languages[$result['code']] = $result;
	}
	$langid = $languages[$langcode]['language_id'];
	$this->db->query("UPDATE ". DB_PREFIX ."sb_ncomments set language_id = '" . (int)$langid  . "'");
	
	$this->db->query("UPDATE ". DB_PREFIX ."sb_news SET date_updated = date_added");
	$this->db->query("UPDATE ". DB_PREFIX ."sb_news SET date_pub = date_added");
	$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_nauthor (
							`nauthor_id` int(11) NOT NULL AUTO_INCREMENT,
							`adminid` varchar(64) NOT NULL,
							`name` varchar(64) NOT NULL,
							`image` varchar(255) DEFAULT NULL,
							PRIMARY KEY (`nauthor_id`)
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
	$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_nauthor_description (
							`nauthor_id` int(11) NOT NULL,
							`language_id` int(11) NOT NULL,
							`ctitle` varchar(255) NOT NULL,
							`description` text NOT NULL,
							`meta_description` varchar(255) NOT NULL,
							`meta_keyword` varchar(255) NOT NULL,
							PRIMARY KEY (`nauthor_id`,`language_id`)
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
	$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_news_gallery (
						`news_image_id` int(11) NOT NULL AUTO_INCREMENT,
						`news_id` int(11) NOT NULL,
						`image` varchar(512) DEFAULT NULL,
						`text` text NOT NULL,
						`sort_order` int(3) NOT NULL DEFAULT '0',
						PRIMARY KEY (`news_image_id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
	$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_news_video (
						`news_video_id` int(11) NOT NULL AUTO_INCREMENT,
						`news_id` int(11) NOT NULL,
						`text` text COLLATE utf8_bin NOT NULL,
						`video` varchar(255) COLLATE utf8_bin DEFAULT NULL,
						`width` int(11) NOT NULL,
						`height` int(11) NOT NULL,
						`sort_order` int(3) NOT NULL DEFAULT '0',
						PRIMARY KEY (`news_video_id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1");
	$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_news_to_group (
							`news_id` int(11) NOT NULL,
							`group_id` int(11) NOT NULL DEFAULT '0',
							PRIMARY KEY (`news_id`,`group_id`)
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin");


	$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_ncategory_to_group (
							`ncategory_id` int(11) NOT NULL,
							`group_id` int(11) NOT NULL,
							PRIMARY KEY (`ncategory_id`,`group_id`)
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
	
	$this->session->data['success'] = $this->language->get('text_blog_upgrade_success');
	$this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
	} else {
	$this->session->data['warning'] = $this->language->get('text_blogpanel_permissions');
	$this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
	}
	}
	public function install() { //you know this one
		$this->language->load('common/newspanel');
	if ($this->validate() && $this->checkForStuff() == "none") {
		
		$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_news_archive (
						`news_archive_id` int(11) NOT NULL AUTO_INCREMENT,
						`store_id` int(3) NOT NULL,
						`year` int(4) NOT NULL,
						`months` varchar(1024) DEFAULT NULL,
						PRIMARY KEY (`news_archive_id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_ncategory (
							`ncategory_id` int(11) NOT NULL AUTO_INCREMENT,
							`image` varchar(255) COLLATE utf8_bin DEFAULT NULL,
							`parent_id` int(11) NOT NULL DEFAULT '0',
							`top` tinyint(1) NOT NULL,
							`column` int(3) NOT NULL,
							`sort_order` int(3) NOT NULL DEFAULT '0',
							`status` tinyint(1) NOT NULL,
							`date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
							`date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
							PRIMARY KEY (`ncategory_id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=59");
		$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_ncategory_description (
							`ncategory_id` int(11) NOT NULL,
							`language_id` int(11) NOT NULL,
							`name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
							`description` text COLLATE utf8_bin NOT NULL,
							`meta_description` varchar(255) COLLATE utf8_bin NOT NULL,
							`meta_keyword` varchar(255) COLLATE utf8_bin NOT NULL,
							PRIMARY KEY (`ncategory_id`,`language_id`),
							KEY `name` (`name`)
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
		$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_ncategory_to_layout (
							`ncategory_id` int(11) NOT NULL,
							`store_id` int(11) NOT NULL,
							`layout_id` int(11) NOT NULL,
							PRIMARY KEY (`ncategory_id`,`store_id`)
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
		$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_ncategory_to_store (
							`ncategory_id` int(11) NOT NULL,
							`store_id` int(11) NOT NULL,
							PRIMARY KEY (`ncategory_id`,`store_id`)
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
		$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_ncomments (
							`ncomment_id` int(11) NOT NULL AUTO_INCREMENT,
							`news_id` int(11) NOT NULL,
							`language_id` int(2) NOT NULL,
							`reply_id` int(11) NOT NULL DEFAULT '0',
							`author` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
							`text` text COLLATE utf8_bin NOT NULL,
							`status` tinyint(1) NOT NULL DEFAULT '0',
							`date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
							`date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
							PRIMARY KEY (`ncomment_id`),
							KEY `news_id` (`news_id`)
							) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=37");
		$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_news (
							`news_id` int(11) NOT NULL AUTO_INCREMENT,
							`nauthor_id` int(11) NOT NULL,
							`status` int(1) NOT NULL DEFAULT '0',
							`image` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
							`acom` int(1) NOT NULL DEFAULT '0',
							`date_added` datetime DEFAULT NULL,
							`date_updated` datetime DEFAULT NULL,
							`image2` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
							`sort_order` int(11) DEFAULT NULL,
							`gal_thumb_w` int(5) NOT NULL,
							`gal_thumb_h` int(5) NOT NULL,
							`gal_popup_w` int(5) NOT NULL,
							`gal_popup_h` int(5) NOT NULL,
							`gal_slider_h` int(4) NOT NULL,
							`gal_slider_t` int(1) NOT NULL,
							`date_pub` datetime DEFAULT NULL,
							`gal_slider_w` int(4) NOT NULL,
							PRIMARY KEY (`news_id`)
							) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1");
		$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_news_description (
							`news_id` int(11) NOT NULL DEFAULT '0',
							`language_id` int(11) NOT NULL DEFAULT '0',
							`title` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
							`ctitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
							`description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
							`description2` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
							`meta_desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
							`meta_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
							`ntags` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
							`cfield1` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
							`cfield2` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
							`cfield3` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
							`cfield4` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
							PRIMARY KEY (`news_id`,`language_id`)
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
		$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_news_related (
							`news_id` int(11) NOT NULL,
							`product_id` int(11) NOT NULL,
							PRIMARY KEY (`news_id`,`product_id`)
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
		$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_news_to_layout (
							`news_id` int(11) NOT NULL,
							`store_id` int(11) NOT NULL,
							`layout_id` int(11) NOT NULL,
							PRIMARY KEY (`news_id`,`store_id`)
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
		$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_news_to_ncategory (
							`news_id` int(11) NOT NULL,
							`ncategory_id` int(11) NOT NULL,
							PRIMARY KEY (`news_id`,`ncategory_id`)
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
		$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_news_to_store (
							`news_id` int(11) NOT NULL,
							`store_id` int(11) NOT NULL DEFAULT '0',
							PRIMARY KEY (`news_id`,`store_id`)
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
		$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_nauthor (
							`nauthor_id` int(11) NOT NULL AUTO_INCREMENT,
							`adminid` varchar(64) NOT NULL,
							`name` varchar(64) NOT NULL,
							`image` varchar(255) DEFAULT NULL,
							PRIMARY KEY (`nauthor_id`)
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
		$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_nauthor_description (
							`nauthor_id` int(11) NOT NULL,
							`language_id` int(11) NOT NULL,
							`ctitle` varchar(255) NOT NULL,
							`description` text NOT NULL,
							`meta_description` varchar(255) NOT NULL,
							`meta_keyword` varchar(255) NOT NULL,
							PRIMARY KEY (`nauthor_id`,`language_id`)
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
	$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_news_gallery (
						`news_image_id` int(11) NOT NULL AUTO_INCREMENT,
						`news_id` int(11) NOT NULL,
						`image` varchar(512) DEFAULT NULL,
						`text` text NOT NULL,
						`sort_order` int(3) NOT NULL DEFAULT '0',
						PRIMARY KEY (`news_image_id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
	$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_news_video (
						`news_video_id` int(11) NOT NULL AUTO_INCREMENT,
						`news_id` int(11) NOT NULL,
						`text` text COLLATE utf8_bin NOT NULL,
						`video` varchar(255) COLLATE utf8_bin DEFAULT NULL,
						`width` int(11) NOT NULL,
						`height` int(11) NOT NULL,
						`sort_order` int(3) NOT NULL DEFAULT '0',
						PRIMARY KEY (`news_video_id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1");
		$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_news_to_group (
							`news_id` int(11) NOT NULL,
							`group_id` int(11) NOT NULL DEFAULT '0',
							PRIMARY KEY (`news_id`,`group_id`)
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin");


		$this->db->query("CREATE TABLE IF NOT EXISTS ". DB_PREFIX . "sb_ncategory_to_group (
							`ncategory_id` int(11) NOT NULL,
							`group_id` int(11) NOT NULL,
							PRIMARY KEY (`ncategory_id`,`group_id`)
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
		$this->db->query('DELETE FROM '. DB_PREFIX . 'url_alias WHERE `query` = "news/headlines"');
		$this->db->query("INSERT INTO ". DB_PREFIX ."url_alias (query, keyword) VALUES ('news/headlines', 'blogspage')");

		$this->model_extension_event->deleteEvent('sb_news');

		$this->model_extension_event->addEvent('sb_news', 'admin/view/common/column_left/before', 'event/sb_news/menu');
		$this->model_extension_event->addEvent('sb_news', 'catalog/view/common/header/before', 'event/sb_news/menu');
		
		$this->session->data['success'] = $this->language->get('text_blog_install_success');
						
		$this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
	} else {
		$this->session->data['warning'] = $this->language->get('text_blogpanel_permissions');
		$this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
	}
	}	
	private function validate() {
		if (!$this->user->hasPermission('access', 'common/newspanel')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}	
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>
<?php
class ControllerExtensionModulencategory extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->language->load('extension/module/ncategory');

		$this->document->setTitle($this->language->get('title_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('ncategory', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->response->redirect($this->url->link('extension/module/ncategory', 'token=' . $this->session->data['token'], true));
		}
		
		//delete old module files			
		if (isset($this->request->get['deleteOldVersion']) && $this->validate()) {
			@unlink(DIR_APPLICATION . 'controller/module/ncategory.php');
			@unlink(DIR_APPLICATION . 'language/english/module/ncategory.php');
			@unlink(DIR_APPLICATION . 'language/en-gb/module/ncategory.php');
			@unlink(DIR_APPLICATION . 'view/template/module/ncategory.tpl');
			@unlink(DIR_CATALOG . 'controller/module/ncategory.php');
			@unlink(DIR_CATALOG . 'language/english/module/ncategory.php');
			@unlink(DIR_CATALOG . 'language/en-gb/module/ncategory.php');
			@unlink(DIR_CATALOG . 'view/theme/default/module/ncategory.tpl');
			$this->session->data['success'] = 'You succesfully deleted the old version of the module!';
			$this->response->redirect($this->url->link('extension/module/ncategory', 'token=' . $this->session->data['token'], 'SSL'));
		} elseif (isset($this->request->get['deleteOldVersion'])) {
			$this->response->redirect($this->url->link('extension/module/ncategory', 'nopermissiontodelete=1&token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_content_top'] = $this->language->get('text_content_top');
		$data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$data['text_column_left'] = $this->language->get('text_column_left');
		$data['text_column_right'] = $this->language->get('text_column_right');
		$data['text_bnews_image'] = $this->language->get('text_bnews_image');
		$data['text_bnews_thumb'] = $this->language->get('text_bnews_thumb');
		$data['text_bnews_order'] = $this->language->get('text_bnews_order');
		$data['text_bsettings'] = $this->language->get('text_bsettings');
		$data['text_bwidth'] = $this->language->get('text_bwidth');
		$data['text_bheight'] = $this->language->get('text_bheight');
		$data['text_yess'] = $this->language->get('text_bysort');
		$data['text_noo'] = $this->language->get('text_latest');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['entry_layout'] = $this->language->get('entry_layout');
		$data['entry_position'] = $this->language->get('entry_position');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['tab_disqus'] = $this->language->get('tab_disqus');
		$data['tab_other'] = $this->language->get('tab_other');
		$data['tab_disqus_enable'] = $this->language->get('tab_disqus_enable');
		$data['tab_disqus_sname'] = $this->language->get('tab_disqus_sname');
		$data['text_module_form'] = $this->language->get('text_module_form');
		$data['tab_facebook'] = $this->language->get('tab_facebook');
		$data['tab_facebook_status'] = $this->language->get('tab_facebook_status');
		$data['tab_facebook_appid'] = $this->language->get('tab_facebook_appid');
		$data['tab_facebook_theme'] = $this->language->get('tab_facebook_theme');
		$data['tab_facebook_posts'] = $this->language->get('tab_facebook_posts');
		$data['text_date_format'] = $this->language->get('text_date_format');
		$data['text_top_menu_link'] = $this->language->get('text_top_menu_link');
		$data['text_blog_html_editor'] = $this->language->get('text_blog_html_editor');
		
		
		$data['text_bnews_display_style'] = $this->language->get('text_bnews_display_style');
		$data['text_bnews_dscol'] = $this->language->get('text_bnews_dscol');
		$data['text_bnews_dscols'] = $this->language->get('text_bnews_dscols');
		$data['text_bnews_dselements'] = $this->language->get('text_bnews_dselements');
		$data['text_bnews_dse_image'] = $this->language->get('text_bnews_dse_image');
		$data['text_bnews_dse_name'] = $this->language->get('text_bnews_dse_name');
		$data['text_bnews_dse_da'] = $this->language->get('text_bnews_dse_da');
		$data['text_bnews_dse_du'] = $this->language->get('text_bnews_dse_du');
		$data['text_bnews_dse_author'] = $this->language->get('text_bnews_dse_author');
		$data['text_bnews_dse_category'] = $this->language->get('text_bnews_dse_category');
		$data['text_bnews_dse_desc'] = $this->language->get('text_bnews_dse_desc');
		$data['text_bnews_dse_button'] = $this->language->get('text_bnews_dse_button');
		$data['text_bnews_dse_com'] = $this->language->get('text_bnews_dse_com');
		$data['text_bnews_dse_custom'] = $this->language->get('text_bnews_dse_custom');
		$data['text_tplpick'] = $this->language->get('text_tplpick');
		$data['text_facebook_tags'] = $this->language->get('text_facebook_tags');
		$data['text_twitter_tags'] = $this->language->get('text_twitter_tags');
		$data['text_bnews_catalog_limit'] = $this->language->get('text_bnews_catalog_limit');
		$data['text_bnews_admin_limit'] = $this->language->get('text_bnews_admin_limit');
		$data['text_bnews_headlines_url'] = $this->language->get('text_bnews_headlines_url');
		$data['text_bnews_desc_length'] = $this->language->get('text_bnews_desc_length');
		$data['text_bnews_restrict_group'] = $this->language->get('text_bnews_restrict_group');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add_module'] = $this->language->get('button_add_module');
		$data['button_remove'] = $this->language->get('button_remove');
		
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->get['nopermissiontodelete']) && $this->request->get['nopermissiontodelete']) {
			$data['error_warning']  = $this->language->get('error_permission');
		}

		if (is_file(DIR_APPLICATION . 'controller/module/ncategory.php')) {
			$data['error_warning']  = $this->language->get('error_old_version');
			$data['error_warning'] .= '<a href="' . $this->url->link('extension/module/ncategory', 'deleteOldVersion=1&token=' . $this->session->data['token'], 'SSL') . '" class="btn btn-xs btn-danger">' . $this->language->get('delete_old_version') . '</a>';
		}
		if (!$this->validate()) {
			$data['error_warning']  = $this->language->get('error_permission_instructions');
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], true),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true),
      		'separator' => ' :: '
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/module/ncategory', 'token=' . $this->session->data['token'], true),
      		'separator' => ' :: '
   		);
		
		$data['action'] = $this->url->link('extension/module/ncategory', 'token=' . $this->session->data['token'], true);
		
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);
		
		if (isset($this->request->post['ncategory_bnews_order'])) {
			$data['bnews_order'] = $this->request->post['ncategory_bnews_order'];
		} else {
			$data['bnews_order'] = $this->config->get('bnews_order') ? $this->config->get('bnews_order') : $this->config->get('ncategory_bnews_order');
		}
		if (isset($this->request->post['ncategory_bnews_fbcom_status'])) {
			$data['bnews_fbcom_status'] = $this->request->post['ncategory_bnews_fbcom_status'];
		} else {
			$data['bnews_fbcom_status'] = ($this->config->get('bnews_fbcom_status')) ? $this->config->get('bnews_fbcom_status') : $this->config->get('ncategory_bnews_fbcom_status');
		}
		if (isset($this->request->post['ncategory_bnews_fbcom_appid'])) {
			$data['bnews_fbcom_appid'] = $this->request->post['ncategory_bnews_fbcom_appid'];
		} else {
			$data['bnews_fbcom_appid'] = $this->config->get('bnews_fbcom_appid') ? $this->config->get('bnews_fbcom_appid') : $this->config->get('ncategory_bnews_fbcom_appid');
		}
		if (isset($this->request->post['ncategory_bnews_fbcom_theme'])) {
			$data['bnews_fbcom_theme'] = $this->request->post['ncategory_bnews_fbcom_theme'];
		} else {
			$data['bnews_fbcom_theme'] = $this->config->get('bnews_fbcom_theme') ? $this->config->get('bnews_fbcom_theme') : $this->config->get('ncategory_bnews_fbcom_theme');
		}
		if (isset($this->request->post['ncategory_bnews_fbcom_posts'])) {
			$data['bnews_fbcom_posts'] = $this->request->post['ncategory_bnews_fbcom_posts'];
		} else {
			$data['bnews_fbcom_posts'] = $this->config->get('bnews_fbcom_posts') ? $this->config->get('bnews_fbcom_posts') : $this->config->get('ncategory_bnews_fbcom_posts');
		}
		if (isset($this->request->post['ncategory_bnews_disqus_status'])) {
			$data['bnews_disqus_status'] = $this->request->post['ncategory_bnews_disqus_status'];
		} else {
			$data['bnews_disqus_status'] = $this->config->get('bnews_disqus_status') ? $this->config->get('bnews_disqus_status') : $this->config->get('ncategory_bnews_disqus_status');
		}
		if (isset($this->request->post['ncategory_bnews_disqus_sname'])) {
			$data['bnews_disqus_sname'] = $this->request->post['ncategory_bnews_disqus_sname'];
		} else {
			$data['bnews_disqus_sname'] = $this->config->get('bnews_disqus_sname') ? $this->config->get('bnews_disqus_sname'): $this->config->get('ncategory_bnews_disqus_sname');
		}
		if (isset($this->request->post['ncategory_bnews_facebook_tags'])) {
			$data['bnews_facebook_tags'] = $this->request->post['ncategory_bnews_facebook_tags'];
		} else {
			$data['bnews_facebook_tags'] = $this->config->get('bnews_facebook_tags') ? $this->config->get('bnews_facebook_tags') : $this->config->get('ncategory_bnews_facebook_tags');
		}
		if (isset($this->request->post['ncategory_bnews_twitter_tags'])) {
			$data['bnews_twitter_tags'] = $this->request->post['ncategory_bnews_twitter_tags'];
		} else {
			$data['bnews_twitter_tags'] = $this->config->get('bnews_twitter_tags') ? $this->config->get('bnews_twitter_tags') : $this->config->get('ncategory_bnews_twitter_tags');
		}
		if (isset($this->request->post['ncategory_bnews_display_style'])) {
			$data['bnews_display_style'] = $this->request->post['ncategory_bnews_display_style'];
		} else {
			$data['bnews_display_style'] = $this->config->get('bnews_display_style') ? $this->config->get('bnews_display_style') : $this->config->get('ncategory_bnews_display_style');
		}

		$data['bnews_display_elements_s'] = "";
		if (isset($this->request->post['ncategory_bnews_display_elements'])) {
			$data['bnews_display_elements'] = $this->request->post['ncategory_bnews_display_elements'];
		} elseif($this->config->get('ncategory_bnews_display_elements')) {
			$data['bnews_display_elements'] = $this->config->get('ncategory_bnews_display_elements');
		} elseif($this->config->get('bnews_display_elements')) {
			$data['bnews_display_elements'] = $this->config->get('bnews_display_elements');
		} else {
			$data['bnews_display_elements'] = array();
			$data['bnews_display_elements_s'] = "none";
		}
		if (isset($this->request->post['ncategory_bnews_image_width'])) {
			$data['bnews_image_width'] = $this->request->post['ncategory_bnews_image_width'];
		} else {
			$data['bnews_image_width'] = $this->config->get('bnews_image_width') ? $this->config->get('bnews_image_width') : $this->config->get('ncategory_bnews_image_width');
		}
		if (isset($this->request->post['ncategory_bnews_image_height'])) {
			$data['bnews_image_height'] = $this->request->post['ncategory_bnews_image_height'];
		} else {
			$data['bnews_image_height'] = $this->config->get('bnews_image_height') ? $this->config->get('bnews_image_height') : $this->config->get('ncategory_bnews_image_height');
		}
		if (isset($this->request->post['ncategory_bnews_thumb_width'])) {
			$data['bnews_thumb_width'] = $this->request->post['ncategory_bnews_thumb_width'];
		} else {
			$data['bnews_thumb_width'] = $this->config->get('bnews_thumb_width') ? $this->config->get('bnews_thumb_width') : $this->config->get('ncategory_bnews_thumb_width');
		}
		if (isset($this->request->post['ncategory_bnews_thumb_height'])) {
			$data['bnews_thumb_height'] = $this->request->post['ncategory_bnews_thumb_height'];
		} else {
			$data['bnews_thumb_height'] = $this->config->get('bnews_thumb_height') ? $this->config->get('bnews_thumb_height') : $this->config->get('ncategory_bnews_thumb_height');
		}
		if (isset($this->request->post['ncategory_bnews_tplpick'])) {
			$data['bnews_tplpick'] = $this->request->post['ncategory_bnews_tplpick'];
		} else {
			$data['bnews_tplpick'] = $this->config->get('bnews_tplpick') ? $this->config->get('bnews_tplpick') : $this->config->get('ncategory_bnews_tplpick');
		}
		if (isset($this->request->post['ncategory_status'])) {
			$data['ncategory_status'] = $this->request->post['ncategory_status'];
		} else {
			$data['ncategory_status'] = $this->config->get('ncategory_status');
		}
		if (isset($this->request->post['ncategory_bnews_catalog_limit'])) {
			$data['bnews_catalog_limit'] = $this->request->post['ncategory_bnews_catalog_limit'];
		} else {
			$data['bnews_catalog_limit'] = $this->config->get('bnews_catalog_limit') ? $this->config->get('bnews_catalog_limit') : $this->config->get('ncategory_bnews_catalog_limit');
		}
		if (isset($this->request->post['ncategory_bnews_admin_limit'])) {
			$data['bnews_admin_limit'] = $this->request->post['ncategory_bnews_admin_limit'];
		} else {
			$data['bnews_admin_limit'] = $this->config->get('bnews_admin_limit') ? $this->config->get('bnews_admin_limit') : $this->config->get('ncategory_bnews_admin_limit');
		}
		if (isset($this->request->post['ncategory_bnews_headlines_url'])) {
			$data['bnews_headlines_url'] = $this->request->post['ncategory_bnews_headlines_url'];
		} else {
			$data['bnews_headlines_url'] = $this->config->get('bnews_headlines_url') ? $this->config->get('bnews_headlines_url') : $this->config->get('ncategory_bnews_headlines_url');
		}
		if (isset($this->request->post['ncategory_bnews_desc_length'])) {
			$data['bnews_desc_length'] = $this->request->post['ncategory_bnews_desc_length'];
		} else {
			$data['bnews_desc_length'] = $this->config->get('bnews_desc_length') ? $this->config->get('bnews_desc_length') : $this->config->get('ncategory_bnews_desc_length');
		}
		if (isset($this->request->post['ncategory_bnews_restrictgroup'])) {
			$data['ncategory_bnews_restrictgroup'] = $this->request->post['ncategory_bnews_restrictgroup'];
		} else {
			$data['ncategory_bnews_restrictgroup'] = $this->config->get('ncategory_bnews_restrictgroup');
		}

		if (isset($this->request->post['ncategory_bnews_date_format'])) {
			$data['bnews_date_format'] = $this->request->post['ncategory_bnews_date_format'];
		} else {
			$data['bnews_date_format'] = $this->config->get('ncategory_bnews_date_format') ? $this->config->get('ncategory_bnews_date_format') : 'd.m.Y';
		}
		$data['bnews_date_formats'] = array(
				'd.m.Y' => '26.05.2016',
				'd.m.Y H:i' => '26.05.2016 12:00',
				'd-m-Y' => '26-05-2016',
				'd-m-Y H:i' => '26-05-2016 12:00',
				'd/m/Y' => '26/05/2016',
				'd/m/Y H:i' => '26/05/2016 12:00',
				'm/d/Y' => '05/26/2016',
				'd M Y' => '26 May 2016',
				'M d Y' => 'May 26 2016',
				'M d, Y' => 'May 26, 2016',
				'M d Y \a\t ga' => 'May 26 2016 at 12am',
				'M dS Y' => 'May 26th 2016',
				'M dS Y \a\t ga' => 'May 26th 2016 at 12am',
				'M dS' => 'May 26th',
				'M dS \a\t ga' => 'May 26th at 12am',
				'D, d M Y' => 'Thu, 26 May 2016',
				'D, M d Y' => 'Thu, May 26 2016',
				'D, M dS Y' => 'Thu, May 26th 2016',
				'D, M dS' => 'Thu, May 26th',
				'l, M dS' => 'Thursday, May 26th',
			);

		if (isset($this->request->post['ncategory_bnews_top_link'])) {
			$data['bnews_top_link'] = $this->request->post['ncategory_bnews_top_link'];
		} else {
			$data['bnews_top_link'] = $this->config->get('ncategory_bnews_top_link');
		}

		if (isset($this->request->post['ncategory_bnews_html_editor'])) {
			$data['bnews_html_editor'] = $this->request->post['ncategory_bnews_html_editor'];
		} else {
			$data['bnews_html_editor'] = $this->config->get('ncategory_bnews_html_editor');
		}

		$data['html_editors'] = array();
		$data['html_editors']['sn'] = array (
				'code' => 'summernote',
				'name' => 'Opencart Default(Summernote)',
				'enabled' => true
			);
		$data['html_editors']['ck'] = array (
				'code' => 'ckeditor',
				'name' => 'CKEDITOR',
				'enabled' => false
			);
		$data['html_editors']['te'] = array (
				'code' => 'tinymce',
				'name' => 'TinyMCE',
				'enabled' => false
			);
		if (file_exists(DIR_APPLICATION . 'view/blog-res/ckeditor/ckeditor.js')) {
			$data['html_editors']['ck']['enabled'] = true;
		}
		if (file_exists(DIR_APPLICATION . 'view/blog-res/tinymce/tinymce.min.js')) {
			$data['html_editors']['te']['enabled'] = true;
		}


		
		$data['header'] = $this->load->controller('common/header');
		$data['newspanel'] = $this->load->controller('common/newspanel');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
				
		$this->response->setOutput($this->load->view('extension/module/ncategory.tpl', $data));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/ncategory')) {
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
<?php 
class ControllerExtensionFeedArticlesGoogleBase extends Controller {
	private $error = array(); 
	
	public function index() {
		$this->language->load('extension/feed/articles_google_base');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('articles_google_base', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=feed', true));
		}

		//delete old module files			
		if (isset($this->request->get['deleteOldVersion']) && $this->validate()) {
			@unlink(DIR_APPLICATION . 'controller/feed/articles_google_base.php');
			@unlink(DIR_APPLICATION . 'language/english/feed/articles_google_base.php');
			@unlink(DIR_APPLICATION . 'language/en-gb/feed/articles_google_base.php');
			@unlink(DIR_APPLICATION . 'view/template/feed/articles_google_base.tpl');
			@unlink(DIR_CATALOG . 'controller/feed/articles_google_base.php');
			$this->session->data['success'] = 'You succesfully deleted the old version of the feed!';
			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=feed', true));
		} elseif (isset($this->request->get['deleteOldVersion'])) {
			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=feed', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_data_feed'] = $this->language->get('entry_data_feed');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		if (is_file(DIR_APPLICATION . 'controller/feed/articles_google_base.php')) {
			$data['error_warning']  = $this->language->get('error_old_version');
			$data['error_warning'] .= '<a href="' . $this->url->link('extension/feed/articles_google_base', 'deleteOldVersion=1&token=' . $this->session->data['token'], 'SSL') . '" class="btn btn-xs btn-danger">' . $this->language->get('delete_old_version') . '</a>';
		}
		if (!$this->validate()) {
			$data['error_warning']  = $this->language->get('error_permission_instructions');
		}
		
  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_feed'),
			'href'      => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=feed', true),
      		'separator' => ' :: '
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/feed/articles_google_base', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
				
		$data['action'] = $this->url->link('extension/feed/articles_google_base', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=feed', true);
		
		if (isset($this->request->post['articles_google_base_status'])) {
			$data['articles_google_base_status'] = $this->request->post['articles_google_base_status'];
		} else {
			$data['articles_google_base_status'] = $this->config->get('articles_google_base_status');
		}
		
		$data['data_feed'] = HTTP_CATALOG . 'index.php?route=extension/feed/articles_google_base';

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/feed/articles_google_base', $data));
	} 
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/feed/articles_google_base')) {
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
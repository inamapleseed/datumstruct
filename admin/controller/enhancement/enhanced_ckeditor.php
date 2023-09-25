<?php
class ControllerEnhancementEnhancedCkeditor extends Controller {
	private $error = array();
	
	public function index() {
		$language_data = $this->load->language('enhancement/enhanced_ckeditor');
		foreach($language_data as $key=>$value){
			$data[$key] = $value;
		}

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->document->addStyle('view/template/enhancement/js/toastr/toastr.min.css');	
		$this->document->addScript('view/template/enhancement/js/toastr/toastr.min.js');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('ea_cke', $this->request->post);			
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('enhancement/enhanced_ckeditor', 'token=' . $this->session->data['token'], true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_ea_breadcrumb'),
			'href' => $this->url->link('enhancement/enhanced_ckeditor', 'token=' . $this->session->data['token'], true)
		);
		
		$data['token'] = $this->session->data['token'];
		
		$data['action'] = $this->url->link('enhancement/enhanced_ckeditor', 'token=' . $this->session->data['token'], true);	
		
		$data['ckeditor_modes'] = array('standard', 'advanced');		

		$data['skins'] = array();
		$ignore_skin = array('moono-lisa');
		$ckskins = glob(DIR_APPLICATION . 'view/template/enhancement/js/ckeditor/skins/*');
		foreach ($ckskins as $ckskin) {
			$skin = basename($ckskin);
			if (!in_array($skin, $ignore_skin)) { $data['skins'][] = $skin ; }	
		}		

		$data['cmskins'] = array();
		$ignore_cmskin = array('eclipse');
		$cmskins = glob(DIR_APPLICATION . 'view/template/enhancement/js/ckeditor/plugins/codemirror/theme/*.css');	
		foreach ($cmskins as $cmskin) {
			$cmskin_clean = basename($cmskin, '.css');
			if (!in_array($cmskin_clean, $ignore_cmskin)) { $data['cmskins'][] = $cmskin_clean ; }	
		}

		if (isset($this->request->post['ea_cke_enable_ckeditor'])) {
			$data['ea_cke_enable_ckeditor'] = $this->request->post['ea_cke_enable_ckeditor'];
		} else {
			$data['ea_cke_enable_ckeditor'] = $this->config->get('ea_cke_enable_ckeditor');
		}	

		if (isset($this->request->post['ea_cke_ckeditor_mode'])) {
			$data['ea_cke_ckeditor_mode'] = $this->request->post['ea_cke_ckeditor_mode'];
		} else if ($this->config->get('ea_cke_ckeditor_mode')) {
			$data['ea_cke_ckeditor_mode'] = $this->config->get('ea_cke_ckeditor_mode');
		} else {
			$data['ea_cke_ckeditor_mode'] = 'advanced';
		}
		
		if (isset($this->request->post['ea_cke_ckeditor_skin'])) {
			$data['ea_cke_ckeditor_skin'] = $this->request->post['ea_cke_ckeditor_skin'];
		} else if ($this->config->get('ea_cke_ckeditor_skin')) {
			$data['ea_cke_ckeditor_skin'] = $this->config->get('ea_cke_ckeditor_skin');
		} else {
			$data['ea_cke_ckeditor_skin'] = 'moono-lisa';
		}
		
		if (isset($this->request->post['ea_cke_codemirror_skin'])) {
			$data['ea_cke_codemirror_skin'] = $this->request->post['ea_cke_codemirror_skin'];
		} else if ($this->config->get('ea_cke_codemirror_skin')) {
			$data['ea_cke_codemirror_skin'] = $this->config->get('ea_cke_codemirror_skin');
		} else {
			$data['ea_cke_codemirror_skin'] = 'eclipse';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('enhancement/enhanced_ckeditor', $data));
	}	

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'enhancement/enhanced_ckeditor')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}

<?php
	
	class ControllerExtensionModuleMmosLastView extends Controller {
		
		private $error = array();
		
		public function index() {
			$this->language->load('extension/module/mmos_last_view');
			
			$this->document->setTitle($this->language->get('heading_title1'));
			
			$this->load->model('setting/setting');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
				$this->model_setting_setting->editSetting('mmos_last_view', $this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token']."&type=module", 'SSL'));
			}
			
			$data['heading_title'] = $this->language->get('heading_title');
			
			$data['text_enabled'] = $this->language->get('text_enabled');
			$data['text_disabled'] = $this->language->get('text_disabled');
			$data['text_content_top'] = $this->language->get('text_content_top');
			$data['text_content_bottom'] = $this->language->get('text_content_bottom');
			$data['text_column_left'] = $this->language->get('text_column_left');
			$data['text_column_right'] = $this->language->get('text_column_right');
			
			$data['entry_limit'] = $this->language->get('entry_limit');
			$data['entry_image'] = $this->language->get('entry_image');
			$data['entry_layout'] = $this->language->get('entry_layout');
			$data['entry_position'] = $this->language->get('entry_position');
			$data['entry_status'] = $this->language->get('entry_status');
			$data['entry_sort_order'] = $this->language->get('entry_sort_order');
			
			$data['tab_setting'] = $this->language->get('tab_setting');
			$data['tab_support'] = $this->language->get('tab_support');
			
			$data['text_edit'] = $this->language->get('text_edit');
			$data['button_save'] = $this->language->get('button_save');
			$data['button_cancel'] = $this->language->get('button_cancel');
			$data['button_add_module'] = $this->language->get('button_add_module');
			$data['button_remove'] = $this->language->get('button_remove');
			
			if (isset($this->error['warning'])) {
				$data['error_warning'] = $this->error['warning'];
				} else {
				$data['error_warning'] = '';
			}
			
			if (isset($this->error['image'])) {
				$data['error_image'] = $this->error['image'];
				} else {
				$data['error_image'] = array();
			}
			
			$data['breadcrumbs'] = array();
			
			$data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
			);
			
			$data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
			);
			
			$data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title1'),
            'href' => $this->url->link('extension/module/mmos_last_view', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
			);
			
			$data['action'] = $this->url->link('extension/module/mmos_last_view', 'token=' . $this->session->data['token'], 'SSL');
			
			$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
			
			$data['extension/modules'] = array();
			
			if (isset($this->request->post['last_view_status'])) {
				$data['mmos_last_view_status'] = $this->request->post['mmos_last_view_status'];
			} 
			else{
				$data['mmos_last_view_status'] = $this->config->get('mmos_last_view_status');
			}
			
			$this->load->model('design/layout');
			
			$data['header'] = $this->load->controller("common/header");
			$data['footer'] = $this->load->controller("common/footer");
			$data['column_left'] = $this->load->controller("common/column_left");
			
			$this->response->setOutput($this->load->view('extension/module/mmos_last_view', $data));
		}
		
		protected function validate() {
			if (!$this->user->hasPermission('modify', 'extension/module/mmos_last_view')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			if (isset($this->request->post['mmos_last_view_module'])) {
				foreach ($this->request->post['mmos_last_view_module'] as $key => $value) {
					if (!$value['image_width'] || !$value['image_height']) {
						$this->error['image'][$key] = $this->language->get('error_image');
					}
				}
			}
			
			if (!$this->error) {
				return true;
			} 
			else {
				return false;
			}
		}
		
	}
	
?>
<?php
	class ControllerExtensionModuleFpcarousel extends Controller {
		private $error = array();
		
		public function index() {
			$this->load->language('extension/module/fpcarousel');
			
			$this->document->setTitle($this->language->get('heading_title'));
			$data['entry_itemscolumn'] = $this->language->get('entry_itemscolumn');
			$data['entry_itemspage'] = $this->language->get('entry_itemspage');
			
			$this->load->model('extension/module');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
				if (!isset($this->request->get['module_id'])) {
					$this->model_extension_module->addModule('fpcarousel', $this->request->post);
					} else {
					$this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
				}
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'].'&type=module', 'SSL'));
			}
			
			$data['heading_title'] = $this->language->get('heading_title');
			
			$data['text_edit'] = $this->language->get('text_edit');
			$data['text_enabled'] = $this->language->get('text_enabled');
			$data['text_disabled'] = $this->language->get('text_disabled');
			
			$data['text_yes'] = $this->language->get('text_yes');
			$data['text_no'] = $this->language->get('text_no');
			
			$data['entry_name'] = $this->language->get('entry_name');
			$data['entry_name_as_title'] = $this->language->get('entry_name_as_title');
			$data['entry_product'] = $this->language->get('entry_product');
			$data['entry_limit'] = $this->language->get('entry_limit');
			$data['entry_thumb_size'] = $this->language->get('entry_thumb_size');
			$data['entry_thumb_width'] = $this->language->get('entry_thumb_width');
			$data['entry_thumb_height'] = $this->language->get('entry_thumb_height');
			$data['entry_status'] = $this->language->get('entry_status');
			$data['entry_shuffle_items'] = $this->language->get('entry_shuffle_items');
			$data['entry_auto_play'] = $this->language->get('entry_auto_play');
			$data['entry_pause_on_hover'] = $this->language->get('entry_pause_on_hover');
			$data['entry_show_pagination'] = $this->language->get('entry_show_pagination');
			$data['entry_show_navigation'] = $this->language->get('entry_show_navigation');
			
			$data['help_name_as_title'] = $this->language->get('help_name_as_title');
			$data['help_product'] = $this->language->get('help_product');
			$data['help_shuffle_items'] = $this->language->get('help_shuffle_items');
			$data['help_auto_play'] = $this->language->get('help_auto_play');
			$data['help_pause_on_hover'] = $this->language->get('help_pause_on_hover');
			$data['help_show_pagination'] = $this->language->get('help_show_pagination');
			$data['help_show_navigation'] = $this->language->get('help_show_pagination');
			
			$data['button_save'] = $this->language->get('button_save');
			$data['button_cancel'] = $this->language->get('button_cancel');
			
			if (isset($this->error['warning'])) {
				$data['error_warning'] = $this->error['warning'];
				} else {
				$data['error_warning'] = '';
			}
			
			if (isset($this->error['name'])) {
				$data['error_name'] = $this->error['name'];
				} else {
				$data['error_name'] = '';
			}
			
			$data['breadcrumbs'] = array();
			
			$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
			);
			
			$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
			);
			
			if (!isset($this->request->get['module_id'])) {
				$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/fpcarousel', 'token=' . $this->session->data['token'], 'SSL')
				);
				} else {
				$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/fpcarousel', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
				);			
			}
			
			if (!isset($this->request->get['module_id'])) {
				$data['action'] = $this->url->link('extension/module/fpcarousel', 'token=' . $this->session->data['token'], 'SSL');
				} else {
				$data['action'] = $this->url->link('extension/module/fpcarousel', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
			}
			
			$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
			
			if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
				$module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
			}
			
			$data['token'] = $this->session->data['token'];
			
			if (isset($this->request->post['name'])) {
				$data['name'] = $this->request->post['name'];
				} elseif (!empty($module_info)&&isset($module_info['name'])) {
				$data['name'] = $module_info['name'];
				} else {
				$data['name'] = '';
			}
			
			if (isset($this->request->post['mask'])) {
				$data['mask'] = $this->request->post['mask'];
				} elseif (!empty($module_info)&&isset($module_info['mask'])) {
				$data['mask'] = $module_info['mask'];
				} else {
				$data['mask'] = '';
			}
			
			if (isset($this->request->post['category_id'])) {
				$data['category_id'] = $this->request->post['category_id'];
				} elseif (!empty($module_info)&&isset($module_info['category_id'])) {
				$data['category_id'] = $module_info['category_id'];
				} else {
				$data['category_id'] = 0;
			}
			
			// name as title
			if (isset($this->request->post['name_as_title'])) {
				$data['name_as_title'] = $this->request->post['name_as_title'];
				} elseif (!empty($module_info)&&isset($module_info['name_as_title'])) {
				$data['name_as_title'] = $module_info['name_as_title'];
				} else {
				$data['name_as_title'] = 1;
			}
			
			// module status
			if (isset($this->request->post['status'])) {
				$data['status'] = $this->request->post['status'];
				} elseif (!empty($module_info)&&isset($module_info['status'])) {
				$data['status'] = $module_info['status'];
				} else {
				$data['status'] = '';
			}
			
			// carousel products		
			$this->load->model('catalog/product');
			
			$data['products'] = array();
			
			if (isset($this->request->post['product'])) {
				$products = $this->request->post['product'];
				} elseif (!empty($module_info)&&isset($module_info['product'])) {
				$products = $module_info['product'];
				} else {
				$products = array();
			}	
			
			foreach ($products as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);
				
				if ($product_info) {
					$data['products'][] = array(
					'product_id' => $product_info['product_id'],
					'name'       => $product_info['name']
					);
				}
			}
			
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			
			$this->response->setOutput($this->load->view('extension/module/fpcarousel.tpl', $data));
		}
		
		protected function validate() {
			if (!$this->user->hasPermission('modify', 'extension/module/fpcarousel')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
				$this->error['name'] = $this->language->get('error_name');
			}
			
			return !$this->error;
		}
	}			
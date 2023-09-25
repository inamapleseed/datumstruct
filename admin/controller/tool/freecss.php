<?php
class ControllerToolfreecss extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('tool/freecss');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['button_clear'] = $this->language->get('button_clear');
		$data['button_save'] = $this->language->get('button_save');

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
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('tool/freecss', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['clear'] = $this->url->link('tool/freecss/clear', 'token=' . $this->session->data['token'], 'SSL');
		$data['action'] = $this->url->link('tool/freecss/save', 'token=' . $this->session->data['token'], 'SSL');

		$data['css'] = '';

		$file = DIR_CATALOG . 'view/theme/default/stylesheet/freecss.css';

		if (file_exists($file)) {
			$data['css'] = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tool/freecss.tpl', $data));
	}

	public function clear() {
		$this->load->language('tool/freecss');

		if (!$this->user->hasPermission('modify', 'tool/freecss')) {
			$this->session->data['error'] = $this->language->get('error_permission');
		} else {

			$file = DIR_CATALOG . 'view/theme/default/stylesheet/freecss.css';

			$handle = fopen($file, 'w+');

			fclose($handle);

			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->response->redirect($this->url->link('tool/freecss', 'token=' . $this->session->data['token'], 'SSL'));
	}

	public function save() {
		$this->load->language('tool/freecss');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$value = $this->request->post['css'];
			$file = DIR_CATALOG . 'view/theme/default/stylesheet/freecss.css';
			$handle = fopen($file, 'w+');
			fwrite($handle, html_entity_decode($value));
			fclose($handle);
			$this->session->data['success'] = $this->language->get('text_success');
		}	
		$this->response->redirect($this->url->link('tool/freecss', 'token=' . $this->session->data['token'], 'SSL'));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'tool/freecss')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
}
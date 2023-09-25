<?php

class ControllerFeedFacebookCsv extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('feed/facebook_csv');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('facebook_csv', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_secret'] = $this->language->get('entry_secret');
        $data['entry_secret_info'] = $this->language->get('entry_secret_info');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_feed'),
            'href' => $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('feed/facebook_csv', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['action'] = $this->url->link('feed/facebook_csv', 'token=' . $this->session->data['token'], 'SSL');
        $data['cancel'] = $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['facebook_csv_status'])) {
            $data['facebook_csv_status'] = $this->request->post['facebook_csv_status'];
        } else {
            $data['facebook_csv_status'] = $this->config->get('facebook_csv_status');
        }
        
        if (isset($this->request->post['facebook_csv_secret'])) {
            $data['facebook_csv_secret'] = $this->request->post['facebook_csv_secret'];
        } else {
            $data['facebook_csv_secret'] = $this->config->get('facebook_csv_secret');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('feed/facebook_csv.tpl', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'feed/facebook_csv')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}

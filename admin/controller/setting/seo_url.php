<?php
    class ControllerSettingSeoUrl extends Controller {
        
        private $error = array();
        
        public function index() {
            $this->load->language('setting/seo_url');
            
            $this->document->setTitle($this->language->get('heading_title'));
            
            $this->load->model('setting/seo_url');

            if ($this->request->server['REQUEST_METHOD'] == 'POST'){
                
                $url_alias = $this->request->post['url_alias'];
                if(!empty($url_alias)){
                    $this->db->query("DELETE FROM ".DB_PREFIX."seo_url");
                    $values = array();
                    foreach($url_alias as $urla){
                        if($urla['route'] !=""){
                            $values[] =  "('" . $urla['route'] . "', '" . $urla['keyword'] . "')";
                        }
                    }
                    $sql = "INSERT INTO ".DB_PREFIX."seo_url (query, keyword) VALUES " . implode(', ',$values). "";
                    $this->db->query($sql);
                    $this->session->data['success'] = $this->language->get('text_success');
                    $this->response->redirect($this->url->link('setting/seo_url', 'token=' . $this->session->data['token'], true));
                    
                }
            }
            
            $seourls = $this->model_setting_seo_url->getSeourls();
            $data['seourls'] = $seourls;
            
            $data['heading_title'] = $this->language->get('heading_title');
            
            $data['text_list'] = $this->language->get('text_list');
            $data['text_edit'] = $this->language->get('text_edit');
            
            
            $data['column_route'] = $this->language->get('column_route');
            $data['column_keyword'] = $this->language->get('column_keyword');
            
            
            $data['button_add'] = $this->language->get('button_add');
            $data['button_cancel'] = $this->language->get('button_cancel');
            
            
            if (isset($this->error['warning'])) {
                $data['error_warning'] = $this->error['warning'];
                } else {
                $data['error_warning'] = '';
            }

            $data['breadcrumbs'] = array();
            
            $data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
            );
            
            $data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('setting/seo_url', 'token=' . $this->session->data['token'], true)
            );
            
            if (isset($this->session->data['success'])) {
                $data['success'] = $this->session->data['success'];
                
                unset($this->session->data['success']);
                } else {
                $data['success'] = '';
            }
            
            $data['action'] = $this->url->link('setting/seo_url', 'token=' . $this->session->data['token'], true);
            $data['cancel'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], true);
            
            $data['token'] = $this->session->data['token'];
            
            $data['header'] = $this->load->controller('common/header');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');
            
            $this->response->setOutput($this->load->view('setting/seo_url', $data));
        }
        
        
    }                                
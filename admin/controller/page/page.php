<?php 
    class ControllerPagePage extends Controller{
        public function index(){
            $token = 'token=' . $data['token'] = $this->session->data['token'];

            foreach($data = $this->load->language('page/page') as $string_variable => $string){
                ${$string_variable} = $string;
            } $this->document->setTitle($heading_title);
            
            $url = $this->url;
            $data['breadcrumbs'][] = array(
                'text'      =>  $text_home,
                'href'      =>  $url->link('common/dashboard', $token, true),
            );
            $data['breadcrumbs'][] = array(
                'text'      =>  $heading_title,
                'href'      =>  $url->link('page/page', $token, true),
            );

            $data +=    $this->load->controller('common/common');
            $this->response->setOutput($this->load->view('page/page' , $data));
        }
    }
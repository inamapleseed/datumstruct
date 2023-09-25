<?php
    class ControllerCommonCommon extends Controller{
        public function index($data){
            $data['header'] = $this->load->controller('common/header');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');
            return $data;
        }
    }
<?php
    class ControllerComponentPagination extends Controller{
        private $standards = array(
            'total' =>  0,
            'page'  =>  0,
            'limit' =>  0,
            'url'   =>  '',
        );

        public function index($settings){ // debug($settings);
            $data = array();

            if(!$settings) return $data;

            foreach($this->standards as $var => &$value){
                if(!isset($settings[$var])){
                    return $data;
                    break;
                }
                else{
                    ${$var} = $settings[$var];
                    $value = $settings[$var];
                }
            }

            //debug($this->standards);
            $pagination = new Pagination();
            foreach($this->standards as $var => $final){ // Don't change $final to $value
                $pagination->{$var} = $final;
            }
            //debug($pagination);

            $data['pagination'] = $pagination->render();

            $data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit), $total, ceil($total / $limit));

            return $data;
        }
    }